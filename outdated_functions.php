<?php
		//OUTDATED REMOVE PARTICLE

		
		//Handle the verbs from clause array with removed_particle true and use the verb start pos to locate the section
		foreach ( $conjunction_array as $clause ) {
			if ( $clause->m_verb->removed_particle ) {
				//use the verb at the start of the array
				//verb start pos needs to be in a section or at the start of a section.
				foreach ( $particle_array as $key => $value ) {
					if ($key <= $clause->m_verb->verb_start_pos && $clause->m_verb->verb_start_pos <$value[0]) {
// 						echo "unsetting key: $key and length: " . $value[0] . "<br>";
						unset($particle_array[$key]); //unset or special type particle for containing part of a long verb?
						break;//found the place in the array where we need to unset the value, this section is part of the verb
					}
				}
			}
		}	



	//OUTDATED PARTICLE MATCHING



// ()\'\"\ is new
//'([°¡-ÆRA-z\\d\\pP]*(\\.\\d+)?[^ÀÌ°¡¸¦À»¿¡°í´ÂÀº¼­]\\pP*\\W)*[°¡-ÆRA-z\\d\\pP]+(\\.\\d+)?(À»|¸¦)\\W'
//new candidate: ([°¡-ÆRA-z\d\pP]*(\.\d+)?[^ÀÌ°¡¸¦À»¿¡°í´ÂÀº¼­]\pP*\W)*[°¡-ÆRA-z\d\pP]+(\.\d+)?(À»|¸¦)\W
//old string: (\pP*[^(ÀÌ\W)(°¡\W)(¸¦\W)(À»\W)(¿¡\W)(°í\W)(´Â\W)(Àº\W)(¼­\W)]\pP*\W?)*[°¡-ÆR]+(Àº|´Â)\W

/* 
   This function finds sentence particles like ÀÌ °¡ ¸¦ À» ¿¡ °í ´Â Àº that may specify a particle.
   Further testing needs to be done after the particle is found to determine if it is really a particle
   or not, some of that post-processing is done in this function.
*/

function find_particles ($sentence) {
	//Assumes sentences have already been separated. $sentence should really just be a sentence ;)
	$pattern[0] = '([°¡-ÆRA-z()\\\'\"\d]*(\.\d+)?[^ÀÌ°¡¸¦À»¿¡°í´ÂÀº¼­(¾Ê¾Æ)\W]\W+)*[°¡-ÆRA-z()\\\'\"\d]+(\.\d+)?(Àº|´Â)\W'; //topic - functions under the assumtion sentences are separated.
	$pattern[1] = '([°¡-ÆRA-z()\\\'\"\d]*(\.\d+)?[^ÀÌ°¡¸¦À»¿¡°í´ÂÀº¼­(¾Ê¾Æ)\W]\W+)*[°¡-ÆRA-z()\\\'\"\d]+(\.\d+)?(ÀÌ|°¡)\W'; //subject, don't match ¸¹ÀÌ
	$pattern[2] = '([°¡-ÆRA-z()\\\'\"\d]*(\.\d+)?[^ÀÌ°¡¸¦À»¿¡°í´ÂÀº¼­(¾Ê¾Æ)\W]\W+)*[°¡-ÆRA-z()\\\'\"\d\`]+(\.\d+)?(À»|¸¦)\W'; //object 
	$pattern[3] = '([°¡-ÆRA-z()\\\'\"\d]*(\.\d+)?[^ÀÌ°¡¸¦À»¿¡°í´ÂÀº¼­(¾Ê¾Æ)\W]\W+)*[°¡-ÆRA-z()\\\'\"\d]+(\.\d+)?(¿¡|¿¡¼­)\W'; //verb modifier
	
	//find, mark particles and print sentence excluding verb.
	
	for ($p_type = 0;$p_type<4;$p_type++) {
	
		mb_ereg_search_init($sentence, $pattern[$p_type]);
		$lastmatch=0;
		
		while($regs = mb_ereg_search_regs()) {
			$lastmatch = mb_strpos($sentence, $regs[0],$lastmatch, "utf8");
			$length =  mb_strlen($regs[0], "utf8");
			$stack[$lastmatch] = array($length, $p_type);
			$lastmatch++;
		}
	}
	$nonparticle = NULL;		
	ksort($stack);
	//need to remove incorrect À» and ´Â markers
	foreach ($stack as $key => $stack_element) {
		
		//if $nonparticle is set, the previous array element is not affixed with a particle.
		//attempt to join the two blocks
		
		if(isset($nonparticle)) {
			//if previous length and offset are same as current offset, set previous as current.
			if (($stack[$nonparticle][0] + $nonparticle) >= ($key -1)) {
				/*echo "key is $key and stackelement is ";
				print_r($stack_element);
				echo "<br>";*/
				$stack[$nonparticle][1] = $stack_element[1];
				$stack[$nonparticle][0] += $stack_element[0];
				/* we are removing this current element from the array.
				 * if we find the same situation with the current element, 
				 * we again will need to add it to the previous element.
				 * POSSIBLY UNCERTAIN RESULTS HERE.
				 */
				unset ($stack[$key]);
			} else {
				unset($stack[$nonparticle]);
			}
			//clear nonparticle flag
			unset($nonparticle);
		}
		if($stack_element[1] == 0 || $stack_element[1] == 2) {
			//if pattern is 0 we have to check either way, if pattern is 2 need to check only À»
			$last_character = mb_substr($sentence, $key + $stack_element[0]-2, 1);
			// check for ~´Â °Å , ~´Â °Ô , ~´Â °Í 
			if ($last_character == "´Â") {
				$to_check = mb_substr($sentence, $key + $stack_element[0],1);
				
				//this is an action verb if the following is true.  No need to check the definition.
				if ( $to_check== "°Å" || $to_check== "°Ô" || $to_check== "°Í" ) {
					//check the word ahead of the '´Â'
					$sentence_section = mb_substr($sentence, 0, $key + $stack_element[0]-2);
					if($space_pos = mb_strrpos($sentence_section, " ")) {
						//if we find a space before the possible verb, get first verb word position
						$start_of_pverb = $space_pos + 1;
					} else {
						//this is the beginning of the sentence, search from position zero
						$start_of_pverb = 0;
					}
					$possible_verb = mb_substr($sentence_section, $start_of_pverb);
					if (is_nocon_verb($possible_verb)) {
						$nonparticle = $key;
						continue;
					}
				}
			}
			// checking À» ´Â Àº for preceeding verbs.
			
			if ($last_character != "¸¦"){
				$candidate = mb_substr($sentence, $key, $stack_element[0]-2);
				$candidate_split = mb_split(' ', $candidate);
				//echo "here is the size of candidate split" . sizeof($candidate_split) ;
				//search for the small verb
				if (sizeof($candidate_split) >1) {
					//**** inefficient
					//need to do what we do by this if's else statement here with 
					//decomposing and recomposing.
					$for_find_tense_d = $candidate_split[sizeof($candidate_split) - 2] . " " . end($candidate_split);
					utf_normalizer::nfd($for_find_tense_d);
					$for_find_tense = jamo_to_co_jamo($for_find_tense_d);
					//echo "here's for find tense: $for_find_tense";
					$my_m_verb = find_tense ($for_find_tense);
					//if mverb has a space remove the beginning.
					if(mb_strpos($my_m_verb->mverb," ")) {
						$my_m_verb->mverb = mb_ereg_replace("[^\s]+\s+", "", $my_m_verb->mverb);
						$my_m_verb->length = mb_strlen($my_m_verb->mverb);
						$my_m_verb->two_word_tense(false);
						
					} else {
						$my_m_verb->two_word_tense(true);
						
					}
					$decomp_words = preg_split('/ /', $for_find_tense_d, -1, PREG_SPLIT_NO_EMPTY);
					if($my_m_verb->two_word_tense) {
						$decomp_lasttwoword = $decomp_words[sizeof($decomp_words) - 2] . " " . end($decomp_words);
					} else {
						$decomp_lasttwoword = end($decomp_words);
					}
					$firstpart = mb_substr($decomp_lasttwoword, 0, $my_m_verb->length, 'utf8');
					
					utf_normalizer::nfc($firstpart);
					
					$big_verb = $candidate_split[sizeof($candidate_split) - 2] . " " . $firstpart;
					k2e_verb($firstpart,$big_verb,$my_m_verb);
					
				} else {					
					$for_find_tense_d = end($candidate_split);
					utf_normalizer::nfd($for_find_tense_d);
					$for_find_tense =  jamo_to_co_jamo($for_find_tense_d);
					$my_m_verb = find_tense ($for_find_tense);
					$firstpart = mb_substr($for_find_tense_d, 0, $my_m_verb->length, 'utf8');
					utf_normalizer::nfc($firstpart);
					k2e_verb($firstpart,NULL,$my_m_verb);
				}
				if(!empty($my_m_verb->english)) $nonparticle = $key;
			}
		}
	}
	
	return $stack;
	
	
}
//OUTDATED UNUSED FOUND_WORDS OBJECT


//need to build array which describes words in the sentence as they are identified.
class found_words {
	function __construct( $wn, $c, $t, $sp,$e,$m,$f,$plu,$pe,$g,$th,$pla,$r, $l, $o ) {
			$this->word_numbers = $wn;	//the numbers translated here correspond to which numbers in the sentence?
			$this->context = $c;		//context that the word is to be translated in.  1 is default.
			$this->type = $t;			//verb/phrase/noun/etc.
			$this->sentence_position = $sp;	//is this word a subject, object, or verb modifier
			$this->english = $e;		//english meaning
			$this->other;
			$this->is_masculine =$m; 	//can be replaced by "he/his" pronouns
			$this->is_feminine = $f;	//can be replaced by "she/her" pronouns	
			$this->is_plural = $plu; 	//needs an s at the end of the word	
			$this->is_person = $pe;		//verb translation should be one corresponding to a person
			$this->is_group = $g;		//verb translation should be one corresponding to a group
			$this->is_thing = $th;		//verb translation should be one corresponding to a thing
			$this->is_place = $pla;		//verb translation should be one corresponding to a place
			$this->req_conj = $r;		//numerical value specifying what word number requires conjugation
			$this->length = $l;			//if something is just part of a word, how long is it?
			$this->offset = $o;			//offset of location within word.
	}
}



		//UNUSED T_aCTION TEST



	$result = mysql_query("SELECT * FROM trans_knoun_translation WHERE nin = 30 LIMIT 1");
	
	//not using a while loop for the result because we only want one result here.
	if ($result && $row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		
		if( $ta_function_name = $row["translation_action"] ) $object_sentence_array = $ta_function_name($object_sentence_array, 1,2);
		if( $tc_function_name = $row["translation_choice"] ) {
			if ($tc_function_name($object_sentence_array, 1)) {
				$object_sentence_array[1][0]->noun_clause_array[0]->meaning .= " " . $row["translation_choice_true"];
			} else {
				$object_sentence_array[1][0]->noun_clause_array[0]->meaning .= " " . $row["translation_choice_false"];
			}
		}
	}	
	
	
	
	//OUTDATED NOUN CLAUSE
	
	include("connect.php"); 

	mb_internal_encoding( 'UTF-8' );
	mb_regex_encoding('utf-8');
	mysql_query("SET NAMES 'utf8'");
	$clause_index = 1;
	$korean_clause = "Áß±¹°ú ÀÏº» Æ´¹Ù±¸´Ï¿¡¼­";
	echo "<pre>";
	echo "translating: $korean_clause <br>"; 
	print_r(k2e_noun($korean_clause, $clause_index));

//good up to 31 words, noun clause contains words, numbers, english words, and punctuation.
class noun_clause {
	function can_add($sc, $v, $e) {
// 		echo "can add ( $sc , $v , $e )<br>";
		if (!($this->code & $sc)) {
			$this->code += $sc;//add the search code to the nc's code
			$this->value += $v;//add the search value to the nc's search value
			$this->english[$sc] = $e;//add the english word
			ksort($this->english);
			return true;
		} else return false;
	}
	function __construct($sc, $v, $e) {
		$this->code = $sc;
		$this->value = $v;
		$this->english[$sc] = $e;
	}
}
function k2e_noun($noun_clause, $nc_array) {
	/*	
		add adjective-form verb parser (will be done outside of this function.  
		This program is designed already to handle english words).  As well, 
		we will need to test for this outside the function anyway.
	*/
	//search multiple words, then search
// 	$nc_array=NULL;
	//search entire noun
	
	rtrim($noun_clause);
	
	//prepare the clause by removing unnecessary characters	
	$stripped_noun_clause = mb_ereg_replace("(Àº|¿¡?´Â|ÀÌ|°¡|À»|¸¦|)$","", $noun_clause); //take out the endings
	$stripped_noun_clause = mb_ereg_replace("[\"\'\,]","", $stripped_noun_clause); //take out the punctuation
	$stripped_noun_clause = stripslashes($stripped_noun_clause); //strip slashes
	
	//echo "This is the stripped noun clause: $stripped_noun_clause";
	$words = preg_split('/ /', $stripped_noun_clause, -1, PREG_SPLIT_NO_EMPTY); //split up the words.
	
	for ($i = 2; $i>-1;$i--) {//search using groups of 3 down to 1 words.  $i goes from 2 to 0.
		$pointer = 0;


		//search using groups of $i words, be careful not to search over 12 words
		//so the program won't slow down, this is the maximum length for a noun clause.
		if(($words_length = sizeof($words)) > $i && $words_length < 12) {
			while($words[$pointer+$i]) {
				$search_code = 0;	
				
				//before the search string is built, remove decimal numbers or english from hangul
				if($i==0 && mb_ereg("([°¡-ÆR]+([A-z0-9\.]+))|(([A-z0-9\.]+)[°¡-ÆR]+)", $words[$pointer])) {
					//remove everything before the word, and everything after.
					$post_mixed = mb_ereg_replace("(([A-z0-9\.]*)[°¡-ÆR]+)", "", $words[$pointer]);
					$pre_mixed = mb_ereg_replace("([°¡-ÆR]+([A-z0-9\.]*))", "", $words[$pointer]);
					$words[$pointer] = mb_ereg_replace("([A-z0-9\.]+)", "", $words[$pointer]);
					echo "here's the backup: ". $post_mixed . "and also " . $pre_mixed ."and here's the filtered word: " . $words[$pointer] . "\n";
				}
				
				//calculate values for the noun words
				for ($j = 0; $j <= $i; $j++) {
					if($j>0)$search_string .= " ";
					$search_string .= $words[$pointer+$j];
					$search_code += pow(2,$pointer+$j);
				}

				//find english words and numbers not attached to words then add them if they didn't match part of a larger word
				
				if ($i==0 && ereg('[A-Za-z0-9\%\.]', $words[$pointer])) {
					if (!$nc_array) { 
	 					$nc_array[] = new noun_clause( $search_code, $i + 1, $words[$pointer]);
	 				} else {
		 				foreach($nc_array as $nc_element) {
			 				//echo "can_add $search_code $i ". $row["meaning"] . "\n";
			 				$nc_element->can_add($search_code, $i + 1, $words[$pointer]);
		 				}
		 				if ($i >0) { 
			 				$nc_array[] = new noun_clause( $search_code, $i + 1, $words[$pointer]);
		 				}
	 				}
				}
				//search here		
				$query = "select * from trans_knouns as tk, trans_knoun_meanings as tkm WHERE tk.nin = tkm.nin AND tk.noun1 = \"".$search_string."\"";
				$result = mysql_query($query); 
				//echo "$query \n";
				while($row = mysql_fetch_assoc($result)){ 
 					//print_r($row); 
 					$query2 = "SELECT * FROM trans_knoun_translation as tkt WHERE tkt.nin = ".$row["nin"] . " LIMIT 1";
//  					echo "here is query2: " . $query2 . "<br>";
 					$result2 = mysql_query($query2);
 					if ($result2 && mysql_num_rows($result2)) { // if result is not equal to 0 rows
 						$row2 = mysql_fetch_assoc($result2);
 						
 						//found some rules, add them to the object.
 						
 						if($row2["translation_choice"]) {
	 						if($row2["translation_choice"]($noun_clause)) { //true, (ie. found ¿Í or °ú)
	 							$append_me = $row2["translation_choice_true"];
 							} else {
 								$append_me = $row2["translation_choice_false"];
							}
							$row["meaning"] .= " " . $append_me;
						}
 						
 						//if there was a translation action, add this rule to the array.
 						t_action_add_rule($row2["translation_action"], $search_code, $clause_index);
 						
 						//put all the values into result
 						$row = array_merge( $row, $row2 );
 						echo "here is the merged object";
 						print_r($row2);
					}
 					if($pre_mixed || $post_mixed) {
	 					$row["meaning"] = $pre_mixed . $row["meaning"] . $post_mixed;
	 					unset($pre_mixed);
	 					unset($post_mixed);
 					}
 					if (!$nc_array) { 
						//first word found, inset into nc_array
	 					$nc_array[] = new noun_clause( $search_code, $i + 1, $row["meaning"] );
	 					//insert other word values
	 					foreach ($row as $key =>$value) {
				 			if(!is_numeric($key)&&$value) end($nc_array)->$key = $value;
		 				}
	 				} else {
		 				//see if the word can be added to any of the elements in the noun clause array
		 				foreach($nc_array as $nc_element) {
			 				//if it was added, add the other values from the table.
			 				if($nc_element->can_add($search_code, $i + 1, $row["meaning"])) {
			 					//insert other word values
	 							foreach ($row as $key =>$value) {
				 					if(!is_numeric($key)&&$value) $nc_element->$key = $value;
		 						}
		 					}
		 				}
		 				if ($i >0) {
			 				//since wordlength is greater than one, we create a new $nc_array object for it. 
			 				$nc_array[] = new noun_clause( $search_code, $i + 1, $row["meaning"] );
		 					//insert other word values
		 					foreach ($row as $key =>$value) {
					 			if(!is_numeric($key)&&$value) end($nc_array)->$key = $value;
		 					}
		 				}
	 				}
				}
				unset($search_string);
				$pointer++;
			}
		}
	}
//if: max ( value ) == $words_length  return the maximum value elements, delete the rest.
//else: do not delete, do not return, continue to do particle search.

	for ($i = 0; $i< $words_length; $i++) {
		if ($nc_array[$i]->value == $words_length) $can_return = true;
	}
	
	if($can_return) {
		for ($i = 0; $i< $words_length; $i++) {
			if ($nc_array[$i]->value < $words_length) unset($nc_array[$i]);
		}
		return array_values($nc_array);
	}

	//search taking appart individual words

	for($i = 0; $i < $words_length; $i++) {
		$search_code = 0;
		$word_length = mb_strlen($words[$i], "utf8");
		//echo $words[$i] . " and wordlength is: " . $word_length . "\n\n";
		if ($word_length >3) {
			//separate two particles from the front and back
			
			//search front two
			$query_part = "select * from trans_kfront_part as fp WHERE fp.particle = \"".mb_substr($words[$i], 0,2) ."\" ";
			$result_part = mysql_query($query_part); 
			if($fetch_part = mysql_fetch_array($result_part)){ 
				//at this point, we've matched a length 2 front particle, try to find the noun.
				$query_noun = "select * from trans_knouns as tk, trans_knoun_meanings as tkm WHERE tk.nin = tkm.nin AND tk.noun1 = \"". mb_substr($words[$i], 2) ."\" ";
				$result_noun = mysql_query($query_noun); 
				if($fetch_noun = mysql_fetch_array($result_noun)){ 
					$search_code += pow(2,$i);
					add_mpart_noun ($nc_array, $fetch_part['meaning'], $fetch_part['connector'], $fetch_noun['meaning'], $search_code);
					continue;
 				}
			}
			
			//search back two			
			$query_part = "select * from trans_kback_part as fb WHERE fb.particle = \"".mb_substr($words[$i], $word_length-2) ."\" ";
			$result_part = mysql_query($query_part); 
			if($fetch_part = mysql_fetch_array($result_part)){ 
				//at this point, we've matched a length 2 back particle, try to find the noun.
				$query_noun = "select * from trans_knouns as tk, trans_knoun_meanings as tkm WHERE tk.nin = tkm.nin AND tk.noun1 = \"". mb_substr($words[$i], 0, $word_length-2) ."\" ";
				$result_noun = mysql_query($query_noun); 
				if($fetch_noun = mysql_fetch_array($result_noun)){ 
					$search_code += pow(2,$i);
					add_mpart_noun ($nc_array, $fetch_noun['meaning'], $fetch_part['connector'], $fetch_part['meaning'], $search_code);
					continue;
 				}
			}
		}
		if ($word_length >1) {
			
			
			
			
			//echo "w[".$i."]"."[0]=" . mb_substr($words[$i], 0,1);
			$query_part = "select * from trans_kfront_part as fp WHERE fp.particle = \"".mb_substr($words[$i], 0,1) ."\" ";
			$result_part = mysql_query($query_part); 
			//echo "a query: $query_part <br> a result: $result_part";
			if($fetch_part = mysql_fetch_array($result_part)){ 
				//at this point, we've matched a length 1 front particle, try to find the noun.
				$query_noun = "select * from trans_knouns as tk, trans_knoun_meanings as tkm WHERE tk.nin = tkm.nin AND tk.noun1 = \"". mb_substr($words[$i], 1) ."\" ";
				$result_noun = mysql_query($query_noun); 
				if($fetch_noun = mysql_fetch_array($result_noun)){ 
					$search_code += pow(2,$i);
					add_mpart_noun ($nc_array, $fetch_part['meaning'], $fetch_part['connector'], $fetch_noun['meaning'], $search_code); 
					continue;
 				}
			}
			$query_part = "select * from trans_kback_part as fb WHERE fb.particle = \"".mb_substr($words[$i], $word_length-1) ."\" ";
			$result_part = mysql_query($query_part);
			if($fetch_part = mysql_fetch_array($result_part)){ 
				//at this point, we've matched a length 1 front particle, try to find the noun.
				
				$query_noun = "select * from trans_knouns as tk, trans_knoun_meanings as tkm WHERE tk.nin = tkm.nin AND tk.noun1 = \"". mb_substr($words[$i], 0,$word_length - 1) ."\" ";
				$result_noun = mysql_query($query_noun); 
				if($fetch_noun = mysql_fetch_array($result_noun)){ 
						
					$search_code += pow(2,$i);
					add_mpart_noun ($nc_array, $fetch_noun['meaning'], $fetch_part['connector'], $fetch_part['meaning'], $search_code); 
					continue;
 				}
			}
			

			//echo "   w[".$i."]"."[last]=" . mb_substr($words[$i], $word_length-1,1) . "\n\n";
		}
	}
	return $nc_array;
}
function add_mpart_noun (&$nc_array, $part1, $connector, $part2 , $search_code ) {
	switch($connector) {
		case 0: 
			$connector = " ";
		break;
		case 1:
			$connector = "-";
		break;
		case 2:
			$connector = "";
		break;
		default: 
		$connector = " ";
	}
 	if (!$nc_array) { 
		$nc_array[] = new noun_clause( $search_code, 1, $part1 . $connector . $part2 );
	} else {
		foreach($nc_array as $nc_element) $nc_element->can_add($search_code, 1, $part1 .$connector.$part2);
	}
}

				//OUTDATED NUMBER AND ENGLISH WORD REMOVAL FROM SEARCH STRING
				
				//before the search string is built, remove decimal numbers or english from hangul
				if($i==0 && mb_ereg("([°¡-ÆR]+([A-z0-9\.]+))|(([A-z0-9\.]+)[°¡-ÆR]+)", $this_clause[$pointer]->word)) {
					//remove everything before the word, and everything after.
					$post_mixed = mb_ereg_replace("(([A-z0-9\.]*)[°¡-ÆR]+)", "", $this_clause[$pointer]->word);
					$pre_mixed = mb_ereg_replace("([°¡-ÆR]+([A-z0-9\.]*))", "", $this_clause[$pointer]->word);
					$this_clause[$pointer]->word = mb_ereg_replace("([A-z0-9\.]+)", "", $this_clause[$pointer]->word);
// 					echo "here's the backup: " . $post_mixed . "and also " . $pre_mixed . "and here's the filtered word: " . $this_clause[$pointer]->word . "\n";
				} else {
					unset($post_mixed);
					unset($pre_mixed);
				}
				
				
				
				
				
				
				
									//find english words and numbers not attached to words then add them if they didn't match part of a larger word
					if ($i==0 && ereg('[A-Za-z0-9\%\.]', $this_clause[$pointer]->word)) {
						if (!$nc_array) { 
		 					$nc_array[] = new noun_clause( $search_code, $i + 1, $this_clause[$pointer]->word );
		 				} else {
			 				foreach($nc_array as $nc_element) {
				 				//echo "can_add $search_code $i ". $row["meaning"] . "\n";
				 				$nc_element->can_add( $search_code, $i + 1, $this_clause[$pointer]->word );
			 				}
			 				if ($i >0) { 
				 				$nc_array[] = new noun_clause( $search_code, $i + 1, $this_clause[$pointer]->word );
			 				}
		 				}
					}
					
					
					
					
					
		//NOT WORKING CONJUNCTION/VERB  SEPARATOR
		
		
		
		
		
					//bring to front of conjunction or sentence
					echo "in the right place";
					//create clause for  conjunction              
					$conj_clause[0] = new Word($clause['conj']->conjunction, '');
					$conj_clause['type'] = "conjunction";
					$conj_clause[0]->english = $clause['conj']->english;
// 					$object_sentence_array[$key]['mverb'] = $clause['conj']->m_verb;
					foreach ($clause['conj']->m_verb as $key => $value) {
						$new_m_verb->$key = $value;
					}	
					$object_sentence_array[$key]['mverb'] = $new_m_verb;
					unset($object_sentence_array[$key]['conj']);
					
					echo "<pre>##############";
					print_r($conj_clause);
					echo "##############";
					print_r($clause);
					echo "##############</pre>";
					$object_sentence_array = array_merge(array($conj_clause), $object_sentence_array);
					
					clause_front_of_sentence_or_conj( $object_sentence_array, $key - 1, $conj_clause );
					
						foreach ($clause['conj']->m_verb as $key => $value) {
							$object_sentence_array[$key]['mverb']->$key = $value;
						}
						$clause['conj']->m_verb = null;
						
						
						
						
						
						
//verb conjugation sentence OUTDATED BECAUSE VERBS ARE STORED DIFFERENTLY NOW.

function conjugate_verbs(&$object_sentence_array) {
	foreach ( $object_sentence_array as $key => $clause ) {
		if (isset($clause['mverb'])) {
			//adjust the tense of the final verb word.
			switch ($clause['mverb']->tense) {
				case "ing" :
				//apply the "ing" to the first word in the verb..
					if($first_space = mb_strpos($clause['mverb']->english, ' ')) {
						$last_char= mb_substr($clause['mverb']->english, $first_space-1, 1);
						if (mb_ereg_match('[aeiouAEIOU]', $last_char)) $del_char = 1;
						$clause['mverb']->english = mb_substr($clause['mverb']->english, 0 , $first_space -$del_char). "ing" . mb_substr($clause['mverb']->english, $first_space);
					} else {
						$last_char= mb_strlen($clause['mverb']->english) - 1;
						if (mb_ereg_match('[aeiouAEIOU]', $last_char)) $del_char = true;
						if ($del_char)$clause['mverb']->english = mb_substr($clause['mverb']->english, 0 , -1) . "ing";
	 					else $clause['mverb']->english .= "ing";
					}
					continue;
				break;
				case "past" :
					$query = "SELECT preterite FROM `trans_everb_tense` WHERE infinitive = \"". $clause['mverb']->english . "\" LIMIT 1";
					$result = mysql_query($query);
					if($result && mysql_num_rows($result)) {
						$row = mysql_fetch_assoc($result);
						$clause['mverb']->english = $row['preterite'];
					} else {
						$clause['mverb']->english .= "ed";
					}
					continue;
					break;
				default:
				break;
			}			
		}
	} 
}




	//OUTDATED SIMPLIFIED PRINT OBJECT SENTENCE FROM INDEX.PHP
	//just show what objects are in the osa
	
	foreach($object_sentence_array as $clause_num=>$clause_obj) {
		foreach($clause_obj as $word_num=>$word_obj) {
			echo "\nclause [$clause_num]" . " word [$word_num]";
			if(gettype($word_obj) == "object" || gettype($word_obj)  == "array") {
				foreach($word_obj as $word_var_name => $word_var) {
					if ($word_var_name == "english" || $word_var_name == "word") {
						echo "\n\t\t [$word_var_name] = $word_var";
					}
				}
			} else echo $word_obj."\n";
		}
		echo "\n";
	}
	
	
	
	
	
	
	
	//OUTDATED HANDLE FINAL VERB.  ALL VERBS TO BE TREATED THE SAME NOW...KKEEKEKEKEKE
		//handle sentence final verb
	$last_obj = $object_sentence_array[$osa_size - 1];
	if($last_obj['type'] == "final verb") {
		$key = $osa_size - 1;
		switch ($last_obj['mverb']->order_action) {
			case 0:
// 			echo "switch verb with object";
				if($object_sentence_array[$key - 1]['type'] == "object") {
					//swap the clauses..
					unset($object_sentence_array[$key]);
					array_splice($object_sentence_array, $key - 1, 0, array($last_obj));
				}
			break;
			case 1:
// 			echo "switch with verbmod";
				if($object_sentence_array[$key - 1]['type'] == "verbmod") {
					//swap the clauses..
					unset ($object_sentence_array[$key]);
					array_splice($object_sentence_array, $key - 1, 0, array($clause));
				}
			break;
			case 2:
// 			echo "switch with oe or object";
				if($object_sentence_array[$key -1]['type'] == "verbmod" || $object_sentence_array[$key - 1]['type'] == "object") {
					$go_back = 1;
					
					if($object_sentence_array[$key -2]['type'] == "verbmod" || $object_sentence_array[$key - 2]['type'] == "object") $go_back = 2;
					//bring it back one or two spaces.
					//if it gets brought back two spaces, then ¿¡¼­ and ¸¦ need to be swapped.
					unset ($object_sentence_array[$key]);
					array_splice($object_sentence_array, $key - $go_back, 0, array($clause));
				}
			break;
			default:
// 				echo "no final verb found";
			break;
		}
	}