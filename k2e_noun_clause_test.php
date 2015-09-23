<?php
define('FRONT', true);
define('BACK', false);

define('LETTER_TOKEN', '***');
define('NUMBER_TOKEN', '###');
define('MIXED_TOKEN', '*#*');
	//for a double sql result
class Multi_part_noun {
	function __construct($n_q, $p_q) {
		$this->noun_query = $n_q;
		$this->part_query = $p_q;
	}
}
class Term {
	//for storing all elements of a returned search
	function __construct($values) {
		foreach($values as $key => $value) {
			if($value && $value != 'NULL') $this->$key = $value; 
		}
	}
}
//need to pass word objects rather than just English..
//3 elements ENGLISH  T_ACTIONS  prepositionS

class noun_clause {
	//t_action is an optional argument, $prepositions is an optional array of prepositions.

	function can_add( $code, $value, $term ) {

		if (!($this->code & $code)) {
			$this->code += $code;//add the search code to the nc's code
			$this->value += $value;//add the search value to the nc's search value
			$this->terms[$code] = $term; //add the term object
			return true;
		} else return false;
	}
	
	function __construct($code, $value, $term) {
		$this->code = $code;
		$this->value = $value;
		$this->terms[$code] = $term;
	}
}
//This function adds a noun to another noun which may be separated by a space, nothing, or a hyphen
function add_mpart_noun ( &$nc_array, $mpc , $search_code, $front ) {
	
	$term = new Term($mpc->noun_query);
	
	switch($mpc->part_query['connector']) {
		
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
		break;
	}
	
	if ($front) {
		$term->prepositions[] = $mpc->noun_query['english'].$connector;
	} else {
		$term->postpositions[] = $connector.$mpc->part_query['english'];
	}
	
	
 	if (!$nc_array) { 
		$nc_array[] = new noun_clause( $search_code, 1, $term);
	} else {
		foreach( $nc_array as $nc_element ) $nc_element->can_add( $search_code, 1, $term );
	}

}
//special add if the word has special translation actions required.
   function nc_array_add(&$nc_array, $i, $search_code, $term) {
	if (!$nc_array) { 
		$nc_array[] = new noun_clause( $search_code, $i + 1, $term);
	} else {
		foreach($nc_array as $nc_element) {
			$nc_element->can_add($search_code, $i + 1, $term);
		}
		if ($i >0) { 
			$nc_array[] = new noun_clause( $search_code, $i + 1, $term );
		}
	} 														 																		
}

function mpart_noun_search ($noun, $part, $front) {
	//need to specify front or back.
	if($front) {
		$query_part = "select * from trans_kfront_part as fp WHERE fp.particle = \"".$part ."\" ";
	} else { 
		$query_part = "select * from trans_kback_part as fp WHERE fp.particle = \"".$part ."\" ";
	}
	$result_part = mysql_query($query_part); 
	if($fetch_part = mysql_fetch_assoc($result_part)){ 
		//at this point, we've matched a length 2 front particle, try to find the noun.
		$query_noun = "select * from trans_knouns as tk, trans_knoun_meanings as tkm WHERE tk.nin = tkm.nin AND (tk.noun1 = \"". $noun ."\" OR tk.noun2 = \"". $noun ."\")"; 
		$result_noun = mysql_query($query_noun); 
		if($fetch_noun = mysql_fetch_assoc($result_noun)) {
			return new Multi_part_noun($fetch_noun,$fetch_part);
		}
	}
	return false;
}
/*
  (from here on use $nc_array)

1 Search the last word of this clause and the first word of the
  next clause.  It's possible to have a two word phrase that 
  contains a particle and was split.

2 If found, add it to the $nc_array 
    
4 Search words in groups from this clause with punctuation removed.
  Don't search for words that already have "english" set in them.

5 Search words in groups from this clause with punctuation and 
  particles removed.
  
6 Break down the words in groups and further break off suffixes and prefixes 
  from the word, and search individually. (Handle dates and words
  with numbers).
 
7 Return the nc_array.

*** not currently removing punctuation properly ***
*/

function k2e_new_noun_clause( &$object_sentence_array, $clause_pointer, &$t_action_list ) {
	
	if (isset($this_clause["english"])) return true;
	
	$starting_code = 0;
	$this_clause = $object_sentence_array[$clause_pointer];
	//following line commented out because it's currently unused
// 	$next_clause = $object_sentence_array[$clause_pointer + 1];
	
	$word_count = no_of_clause_words($this_clause);

	
				
//PARTS REMOVED FROM HERE...TO DO: else  IF IT'S A GRAMMAR PATTERN... OR ALREADY DEFINED 'HO-CHING'...


	
	/* 
		End of special cases
	-------------------------------------
	  after this point conjunctions and over-particle cases have been handled
	  now we go into a regular search, using the starting code we made.
	*/  

	
	//mark the words if they are a part of the conjunction in this clause
	if(isset($this_clause['conj'])) {
		
		//find the word which is the beginning of the conjunction
		$conj_start_pos = get_clause_pos($this_clause, $this_clause['mverb']->verb_start_pos);
		
		//find the word which is the end of the conjunction
		$conj_end_pos = get_clause_pos($this_clause, $this_clause['conj']->conj_pos + $this_clause['conj']->conj_length - 1);
		
		//the conjunction code contains definitions for this range of words
		for ($i = $conj_start_pos; $i <= $conj_end_pos; $i++) {
// 			echo $this_clause[$i]->word ."<br>";
// 			if(empty($this_clause[$i]->english))$this_clause[$i]->english = true;
			


			if ($this_clause['mverb']->isdouble && $i == $conj_start_pos) {
				//the first word of "verb words" is part of the verb
 				$object_sentence_array[$clause_pointer][$i]->type = "verb word";			
				if(empty($object_sentence_array[$clause_pointer][$i]->english))$object_sentence_array[$clause_pointer][$i]->english = true;
 			} elseif ( $object_sentence_array[$clause_pointer]['mverb']->iv_particle && gettype($object_sentence_array[$clause_pointer]['mverb']->adverb) == 'array' && $adverb = array_pop($object_sentence_array[$clause_pointer]['mverb']->adverb) ) {
				//we were able to remove an adverb, add it to this verb
				if($adverb['type'] == 'underived') {
					$object_sentence_array[$clause_pointer][$i]->english = $adverb['english'];
					$object_sentence_array[$clause_pointer][$i]->type = 'underived adverb';
				}else{
					//is it a noun or a verb?
					$object_sentence_array[$clause_pointer][$i]->english = $adverb['noun']?$adverb['noun']:$adverb['with_ro'];
					$object_sentence_array[$clause_pointer][$i]->type = 'derived adverb';
				}
			} elseif($iv_particle && $word == "잘" || $word == "못" || $word == "안" ||$word == "꼭" || $word == "다시") {
				$object_sentence_array[$clause_pointer][$i]->type = "underived adverb";
				$object_sentence_array[$clause_pointer][$i]->english = true;
			} else {
				$object_sentence_array[$clause_pointer][$i]->type = "verb word";
			}







			if ($starting_code ^ pow(2,$i)) $starting_code += pow(2,$i);
			
		}
		
			
		
	} elseif(isset($this_clause['mverb'])) {
		//this clause has an mverb but not a conjunction.
		$verb_start_pos = get_clause_pos($this_clause, $this_clause['mverb']->verb_start_pos);
		$verb_end_pos = num_of_clause_words($this_clause) - 1;
		for ($i = $verb_start_pos; $i <= $verb_end_pos; $i++) {
// 			echo "setting true: i is $i".$this_clause[$i]->word ."<br>";
			if(!$this_clause[$i]->english)$this_clause[$i]->english = true;
			if ($starting_code ^ pow(2,$i)) $starting_code += pow(2,$i);
			
		}
	}
	for ($i = 0; $i < $word_count; $i++) {
		
		//"word" objects that will not be part of the search, these include english, numbers, 
		if( isset($this_clause[$i]->english) || $this_clause[$i]->type == 'punctuation' || 
			$this_clause[$i]->type == 'english' || $this_clause[$i]->type == 'internet address' || 
			$this_clause[$i]->type == 'email address' || $this_clause[$i]->type == 'number' ||
			$this_clause[$i]->type == 'verb word' ) {
			
			//add this word to the 'starting code' so that it isn't part of the search
			if ($starting_code ^ pow(2,$i)) $starting_code += pow(2,$i);
			
		}
	}
	
		for ($i = 2; $i>-1;$i--) {//search using groups of 3 down to 1 words.  $i goes from 2 to 0.
		$pointer = 0;

		//search using groups of $i words, be careful not to search over 12 words
		//so the program won't slow down, this is the maximum length for a noun clause.
		if($i < $word_count && $word_count < 20) {
			while( $this_clause[$pointer + $i]->word ) {
				$search_code = 0;	
				
				//calculate values for the noun words
				for ($j = 0; $j <= $i; $j++) {
					$search_string .= $this_clause[$pointer+$j]->word;
					$search_code += pow(2,$pointer+$j);
				}
				unset ($with_oe, $with_oeseo, $with_ro, $no_particle);
				
				if($this_clause[$pointer+$j-1]->with_oe) {
					$with_oe = true;
				} elseif($this_clause[$pointer+$j-1]->with_oeseo) {
					$with_oeseo = true;
				} elseif($this_clause[$pointer+$j-1]->with_ro) {
					$with_ro = true;
			    } elseif($this_clause[$pointer+$j-1]->no_particle) {
					$no_particle = true;
			    } 
				
				//NAND of starting code with search code.
				if(!($starting_code & $search_code) != 0) {
// 			    echo "searching for >$search_string<<br>";
//				echo "start code: $starting_code and search code: $search_code<br><br>";
					
						//since we're throwing away the search string, it's fine to escape any slashes that may be included.
						$search_string = addslashes($search_string);
						$search_string = trim($search_string);
	 					
						//search here		
						$query = "select * from trans_knouns as tk, trans_knoun_meanings as tkm WHERE tk.nin = tkm.nin AND (tk.noun1 = \"".$search_string."\" OR tk.noun2 = \"".$search_string."\")";
						$result = mysql_query($query); 

						//strip particles and search again
						if (!mysql_num_rows($result)) {
							//strip particles and search again
							$search_string = mb_ereg_replace("(은|(에서?)?는|이|가|을|를|에서|에|으?로|엔)$","", $search_string); //take out the endings
							$query = "select * from trans_knouns as tk, trans_knoun_meanings as tkm WHERE tk.nin = tkm.nin AND (tk.noun1 = \"".$search_string."\" OR tk.noun2 = \"".$search_string."\")";
// 							echo " removed particles and: $search_string\n";
							$result = mysql_query($query); 
						}
											
						//strip english and numbers and search again
						if (!mysql_num_rows($result) && mb_ereg_match('[A-z0-9]+', $search_string)) {

							//Stripping letters & numbers and putting them in the me_array (MIXED ELEMENT ARRAY) 
							while (mb_ereg('[A-z\d]+', $search_string, $matches)) {
								
								$match = $matches[0];
								//remember the positions of the matches
								$pos = mb_strpos($search_string, $match, $pos);
								$match_length = mb_strlen($match);
								
								
								//need to differentiate between matches at the front and back of the word.
								if(is_numeric($match)) {
									//is number
									$search_string = mb_substr($search_string, 0, $pos) . NUMBER_TOKEN . mb_substr($search_string, $pos + $match_length);
									
								} elseif (mb_ereg_match('^[A-z]+$', $match)){
									$search_string = mb_substr($search_string, 0, $pos) . LETTER_TOKEN . mb_substr($search_string, $pos + $match_length);
								} else {
									//is alpha or mixed..
									$search_string = mb_substr($search_string, 0, $pos) . MIXED_TOKEN . mb_substr($search_string, $pos + $match_length);
								}
								$me_array[] = $match;
							}

							
							//strip english and numbers in front of the word
							$query = "select * from trans_knouns as tk, trans_knoun_meanings as tkm WHERE tk.nin = tkm.nin AND (tk.noun1 = \"".$search_string."\" OR tk.noun2 = \"".$search_string."\")";
// 							echo "$query\n";
							$result = mysql_query($query); 
						}						
						
						if (mysql_num_rows($result)) {
							//found a result from the first search
							while($row = mysql_fetch_assoc($result)) { 
								$term = new Term ($row);
								if($with_oe && $row['with_oe']) $term->prepositions[] = $row['with_oe'];
								elseif($with_oeseo && $row['with_oeseo']) $term->prepositions[] = $row['with_oeseo'];
								//using this with ro replaces the whole particle.
								elseif($with_ro && $row['with_ro'])$term->english = $row['with_ro'];
								if($no_particle && $row['no_particle'])$term->no_particle = $row['no_particle'];
								if(empty($term->english)) $term->english = $row['english'];
								if ($row['iscount']) $term->iscount = true;
								if ($row['isplural']) $term->isplural = true;
								if ($row['particle']) $term->prepositions[] = 'the';
								//if we replaced letters or numbers...
// 								print_r( $term );
								if($me_array) {
									/*NOTE:
									
									  it would be possible to double check the answers by 
									  matching the search pattern to the element in the array.
									  If the token were string, then that array element would
									  have to match it.
									 */
									 
									 
									//search pattern for matching the tokens
									$pattern = '(###|\*#\*|\*\*\*)';	
		
									//initalize the search
									mb_ereg_search_init($term->english, $pattern);
								
									//perform the search and handle the results
									while ($regs = mb_ereg_search_regs()) {
										if(!$me_array)break;
										$replacement = array_shift($me_array);
										$token_pos = mb_strpos($term->english, $regs[0]);
										//"$token_pos + 3" because 3 is the length of the tokens
										$term->meaning = mb_substr($term->english, 0, $token_pos) . $replacement . mb_substr($term->meaning, $token_pos + 3);
									}
	 							}
								if($row['special']) {
				 					$query2 = "SELECT * FROM trans_knoun_translation as tkt WHERE tkt.nin = ".$row["nin"] . " LIMIT 1";
				 					$result2 = mysql_query($query2);
				 					if ($result2 && mysql_num_rows($result2)) { // if result is not equal to 0 rows
				 						$row2 = mysql_fetch_assoc($result2);
				 						
										//This is for changing the meaning of the word based on a clause condition (currently disabled)
										if($row2['translation_meaning']) {
											//get position of last word in the search string
											$shifter = $search_code;
											$word_index = 0;
											while ($shifter = $shifter>>1) $word_index++;
// 											echo "k2e nct::word_index : $word_index";
											if($new_meaning = $row2['translation_meaning']($object_sentence_array, $clause_pointer, $word_index)) {
// 												echo "new meaning is $new_meaning <br>";
												$term->english = $new_meaning;
							 				}
										}
										if($row2['translation_movement']) {
											$term->t_action = $row2['translation_movement'];
										}
				 						//handle choice rules
				 						if($row2['translation_preposition_select']) {
					 						switch ( $row2['translation_preposition_select']($this_clause['clause']) ) {
						 						case 0:
						 							//do nothing
						 						break;
						 						case 1:
						 							$term->prepositions[] = $row2['translation_preposition_1'];	
						 						break;
						 						case 2:
						 							$term->prepositions[] = $row2['translation_preposition_2'];
						 						break;
						 						case 3:
						 							$term->prepositions[] = $row2['translation_preposition_3'];
						 						break;
						 						//can add more case handling here if necessary.
						 						default:
						 							echo "ERROR, translation_preposition_select result without case";
						 						break;
					 						}
			 							}
				 						
									}
									//special add
									nc_array_add(&$nc_array, $i, $search_code, $term);
								} else {
									//regular add
									nc_array_add(&$nc_array, $i, $search_code, $term);
				 				}
							}
						} 
					}
				
				unset($search_string);
				$search_code = 0;
				$pointer++;
			}
		}		
	}
//inserted here.
		for ($i = 0; $i< $word_count; $i++) {
		if ($nc_array[$i]->value == $word_count) $can_return = true;
	}
// 	echo "NC ARRAY:<PRE>";
// 	print_r($nc_array);
// 	echo "</PRE>";
		
	if($can_return) {
		//what's going on here?  in the for loop $word_count should be the length of nc_array..?
		for ($i = 0; $i< $word_count; $i++) {
			if ($nc_array[$i]->value < $word_count) unset($nc_array[$i]);
		}
		
		$nc_array = array_values($nc_array);
		nc_array_to_so_array($nc_array[0], $this_clause, $t_action_list, $clause_pointer);
		return true;
	}
	
	//search taking appart individual words

	for($i = 0; $i < $word_count; $i++) {
		$search_code = 0;
		$to_split_search = $this_clause[$i]->word;
		$to_split_search = trim($to_split_search);
		$to_split_search = addslashes($to_split_search);	
		$to_split_search = mb_ereg_replace("(은|(에서?)?는|이|가|을|를|에서|에|으?로|엔)$","", $to_split_search); //take out the endings
// 		echo "single word $to_split_search <br>";
		$word_length = mb_strlen($to_split_search, "utf8");
		$search_code = pow(2,$i);	
		
		if ($word_length >3 && $this_clause[$i]->type != "punctuation" && !isset($this_clause[$i]->english)) {
			//separate two prepositions from the front and back
			
			//search front two
			$particle = mb_substr($to_split_search, 0,2);
			$noun = mb_substr($to_split_search, 2); 
			
			if($m_p_n = mpart_noun_search($noun, $particle, FRONT)){ 
				add_mpart_noun ($nc_array, $m_p_n, $search_code, FRONT);
				continue;
			}
			
			//search back two			
			$particle = mb_substr($to_split_search, $word_length-2);
			$noun = mb_substr($to_split_search, 0, $word_length-2);
			
			if($m_p_n = mpart_noun_search($noun, $particle, BACK)){ 
				add_mpart_noun ($nc_array, $m_p_n, $search_code, BACK);
				continue;
			}
		}
		if ($word_length >1 && $this_clause[$i]->type != "punctuation" && !isset($this_clause[$i]->english)) {
			
			//search single particle in the front of the noun
			$particle = mb_substr($to_split_search, 0,1);
			$noun = mb_substr($to_split_search, 1);
			
			if($m_p_n = mpart_noun_search($noun, $particle, FRONT)){ 
				add_mpart_noun ($nc_array, $m_p_n, $search_code, FRONT); 
				continue;
			}

			//search single particle in the back of the noun
			$particle = mb_substr($to_split_search, $word_length-1);
			$noun = mb_substr($to_split_search, 0,$word_length - 1);	
			
			
			if( $m_p_n = mpart_noun_search( $noun, $particle, BACK ) ) { 
				add_mpart_noun( $nc_array, $m_p_n, $search_code, BACK ); 
				continue;
			}
		}
	}

// 	print_r($nc_array);
	nc_array_to_so_array($nc_array[0], $this_clause, $t_action_list, $clause_pointer);			
	if($nc_array) return true;
}
/* Function noun clause array to sentence object array
   ---------------------------------------------------
   This function takes elements which have been found and adds them into
   the object sentence array.
   
   $nc_zero - first element of the nc_array, should have the highest point value.
   $this_clause - values from the previous object are inserted into this one.
 */
function nc_array_to_so_array($nc_zero, &$this_clause, &$t_action_list, $clause_no) {
	if(is_null($nc_zero->terms)) return;
	foreach ($nc_zero->terms as $key => $word) {
// 		echo 'here is nc_zero<br>';
// 		print_r($nc_zero);
		$count = 0;
		$power_total = 0;
		while ( $key ) {
			$power = pow(2, $count);
			$power_total += $power;
			
// 		echo "Count is: $count and power is: $power and power total = $power_total\n";
		
		//I'm not checking if english is already set or true because if it were, we shouldn't have been searching
		//for a string involving it in the first place.  If we did search we did it regardless of what it was 
		//already set as.
			if( $key % 2 && $key >2 ) {
				$this_clause[$count]->english = true;
				
			} elseif ($key % 2) {
				
				//this if block is error checking, it can be deleted
// 				if(gettype($nc_zero->terms[$power_total]) != 'object' && gettype($nc_zero->terms[$power]) != 'object' ) {
// 					echo "error unexpected type for nc_zero->terms[$power_total] which is: " . gettype($nc_zero->terms[$power_total]);
// 					print_r($nc_zero->terms);
// 					exit;
// 				}

				// if nc->zero->terms[ $power_total ] is defined, use it, otherwise, use $power as the index
				$term = $nc_zero->terms[$power_total] ? $nc_zero->terms[$power_total] : $nc_zero->terms[$power];

				foreach ($term as $var_name => $var_value) {
					
					//special case: 'keywords' becomes an array of keywords rather than a 'comma separated values' list
					if($var_name == 'keywords') $var_value = preg_split("/\s*,\s*/", $var_value);
					//add the t_action rule
					elseif($var_name == 't_action') {
// 						echo "vv: $var_value cn: $clause_no";
						$t_action_list->add_rule($var_value, $clause_no, $count);
					}

					$this_clause[$count]->$var_name = $var_value;
				}
				if($this_clause[$count]->type && $this_clause[$count]->type == 'name') {
					$this_clause[$count]->type = 'noun';
				} elseif ($this_clause[$count]->type && $this_clause[$count]->type == 'name + particle') {
					$this_clause[$count]->type = 'noun + particle';
				}
				
			}
			
			$key = $key>>1;
			$count++;
			
		}
// 		echo "here is this clause<br>";
// 		print_r($this_clause);
	}
}