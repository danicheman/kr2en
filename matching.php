<?php
/*
include("connect.php"); 
mb_internal_encoding( 'UTF-8' );
mb_regex_encoding('utf-8');
mysql_query("SET NAMES 'utf8'");


$sentence = '촬영이 시작되고, 광고멘트에는 "저도 이 상품을 구입했습니다"라는 문구가 들어있는 것을 발견한 그는 그 자리에서 해당 부분을 수정해 줄 것을 부탁했다';

new_find_particles($sentence);
*/
function new_find_particles ($sentence) {
	
	//match anything but stop at word barriers ending with the characters in the square brackets below
	//added (?<!많) to 이 to avoid matches with "many"
// 	$pattern = '.*?\S(는|은|(?<![많\s+])이|가|을(?!\s+때)|를|에(\s+대해)?|에서|등|엔|으?로|도)\b';	
	$pattern = '.*?\S(는|은|(?<![많\s+])이|가|을(?!\s+때)|를|에(\s+대해)?|에서|등|엔|도)\b';	
	//use this variable so that we don't match anything more than once.
	$last_match = 0;
		
	//initalize the search
	mb_ereg_search_init($sentence, $pattern);

	//perform the search and handle the results
	while ($regs = mb_ereg_search_regs()) {
		
		$length = mb_strlen($regs[0]);
		//swich case on last element of the particle
		switch (mb_substr($regs[0],-1)) {
			case "은":
			case "는":
				//type 0
				$particle_type = 0;
			break;
			case "이":
			case "가":
				//type 1
				$particle_type = 1;
			break;
			case "을":
			case "를":
				//type 2
				$particle_type = 2;
			break;
			case "에":
			case "서":
			case "엔":
				//type 3
				$particle_type = 3;
			break;
			case "로":
				//type 4
				$particle_type = 4;
			break;
			case "해":
				//type 5 (about)
				$particle_type = 2;
			break;
			case "도":
				//type 6 (also)
				$particle_type = 5;
			break;
			default:
				unset($particle_type);
			break;
		}
		
		//find the position of the match using the "last match" as the offset
		//so we don't match the same thing twice (if it appeared previously)
		$last_match = mb_strpos($sentence, $regs[0],$last_match);
		
		//****NEED TO IMPROVE THIS FUNCTIONALITY****
		//to copy the behavior of the previous find_particle, we do not store
		//anything when we find a conjunction.
		
		//if (isset($particle_type)) {
			//the lastmatch is the beginning of the current section.
			$particle_array[$last_match] = array($length, $particle_type);
			
		//}
		$last_match++;
	}
	$nonparticle = NULL;		
	
	if (isset($particle_array)) {
		ksort($particle_array);
		//need to remove incorrect 을 and 는 markers
		foreach ($particle_array as $start_pos => $length_and_type_array) {
			
			//if $nonparticle is set, the previous array element is not affixed with a particle.
			//attempt to join the two blocks
			
			if(isset($nonparticle)) {
				//if previous length and offset are same as current offset, set previous as current.
				if (($particle_array[$nonparticle][0] + $nonparticle) >= ($start_pos -1)) {
					$particle_array[$nonparticle][1] = $length_and_type_array[1];
					$particle_array[$nonparticle][0] += $length_and_type_array[0];
					/* we are removing this current element from the array.
					 * if we find the same situation with the current element, 
					 * we again will need to add it to the previous element.
					 * POSSIBLY UNCERTAIN RESULTS HERE.
					 */
					unset ($particle_array[$start_pos]);
				} else {
					unset($particle_array[$nonparticle]);
				}
				//clear nonparticle flag
				unset($nonparticle);
			}
			if($length_and_type_array[1] == 0 || $length_and_type_array[1] == 2) {
				//if pattern is 0 we have to check either way, if pattern is 2 need to check only 을
				$last_character = mb_substr($sentence, $start_pos + $length_and_type_array[0]-1, 1);
				// check for ~는 거 , ~는 게 , ~는 것 
				if ($last_character == "는") {
					$to_check = mb_substr($sentence, $start_pos + $length_and_type_array[0]+1,1);
	
					//this is an action verb if the following is true.  No need to check the definition.
					if ( $to_check== "거" || $to_check== "게" || $to_check== "것" ) {
						//check the word ahead of the '는'
						$sentence_section = mb_substr($sentence, 0, $start_pos + $length_and_type_array[0]-1);
						if($space_pos = mb_strrpos($sentence_section, " ")) {
							//if we find a space before the possible verb, get first verb word position
							$start_of_pverb = $space_pos + 1;
						} else {
							//this is the beginning of the sentence, search from position zero
							$start_of_pverb = 0;
						}
						$possible_verb = mb_substr($sentence_section, $start_of_pverb);
						if (is_nocon_verb($possible_verb)) {
							$nonparticle = $start_pos;
							continue;
						}
					}
				}
				// checking 을 는 은 for preceeding verbs.
				
				if ($last_character != "를"){
					$candidate = mb_substr($sentence, $start_pos, $length_and_type_array[0]-2);
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
					if(!empty($my_m_verb->english)) {
						$nonparticle = $start_pos;
					}
				}
			}
		}
	}

	return $particle_array;
}


//  divides clauses in the sentence by finding and validating 
//  possible sentence conjunctions

function divide_clauses($shifted_sentence, $decomp_sentence, $my_m_verb) {
	if (is_null($shifted_sentence) || is_null($decomp_sentence)) die("fatal call to 'Split Sentnace Conjunctions': called with undefined variables."); //nothing to translate, unsuccessful.
	
	$shifted_sentence = trim($shifted_sentence);
	$decomp_sentence = trim($decomp_sentence);
	
	//use my_m_verb to remove a portion of shifted and decomposed sentences
	$words_to_chop = 0;
	if ($my_m_verb) {
		$words_to_chop++;
		if ($my_m_verb->isdouble)$words_to_chop++;
		if ($my_m_verb->two_word_tense)$words_to_chop++;
		if ($my_m_verb->iv_particle) $words_to_chop += $my_m_verb->iv_particle;
	}
	$pattern = '(\s+\S+){' . $words_to_chop . '}$';
	
	$shifted_sentence = mb_ereg_replace($pattern, "", $shifted_sentence);
	$decomp_sentence = mb_ereg_replace($pattern, "", $decomp_sentence);
	
	// *~러, ~서, ~도, 고, ~기 전에, *~떠니~, *기 때문에
	// *~(느)ㄴ데,* (으)ㄴ 다음에, *~(으)려고~, *~(으)니까~, *~(으)면, *~(으)ㄹ 때 ~  
	/*
 
	*/
	
	preg_match_all("/((ㄱㅗ|ㄷㅏㄱㅏ|(?<!ㅇㅔ)ㅅㅓ|ㄷㅗ|ㄹㅓ|ㄷㅓㄴㅣ|ㅎㅏㅈㅣㅁㅏㄴ|ㅈㅣㅁㅏㄴ|ㄱㅣ\sㄸㅐㅁㅜㄴㅇㅔ|ㄱㅣ\sㅈㅓㄴㅇㅔ|(ㅇㅡ)?ㄹ\s?ㄸㅐ|((ㅇㅡ)|[^ㅂ,\s])ㄴㅣㄲㅏ|(ㅇㅡ)?ㅁㅕㄴ|(ㅇㅡ)?ㄴ\sㄷㅏㅇㅡㅁㅇㅔ|(ㄴㅡ)?ㄴㄷㅔ|(ㅇㅡ)?ㄹㅕㄱㅗ|\W?않아)(?=\W))+/u", $shifted_sentence, $matches); 
	$offset=0;
	
	foreach($matches[0] as $conj) {
		echo "matched : $conj";
		/* Right now if we can't find the verb in front of the conjunction
		 * (it should be an action verb), we won't add it to the 
		 * conjunction/clause array.
		 * ------------------
		 * To find the position of the conjunction, search PAST the offset, 
		 * so we don't find any results which we've already found again.  
		 * Then add back on the offset.
		 */
		 
		$to_search = mb_substr($shifted_sentence, $offset);
		$conj_pos = mb_strpos($to_search, $conj);

		$conj_pos += $offset;
		$conj_length = mb_strlen($conj);
		
// 		echo "Conjunction position is $conj_pos ,here is some text at that location: <br>";
// 		echo mb_substr($shifted_sentence, $conj_pos, 10) . "<br><br>";
	
		$clause_with_conj = mb_substr( $shifted_sentence, $offset, ( $conj_pos + $conj_length ) );
				$conjugated = false;
		//cases for each match? **test verb???** need decomp sentence for verb search
		//need to collect the verb before the conjunction, max two words.
		//if we're not doing anything special with a particular case, just let it go to default.
		switch (true) {
			case (mb_ereg("ㄷㅓㄴㅣ", $conj)):
				$clause_without_conj = mb_ereg_replace ("ㄷㅓㄴㅣ", "",$clause_with_conj);
				//conjugated in past.
				if (mb_substr($clause_without_conj, -1) == "ㅆ")$conjugated = true;
				$english = "resulted in";
			break;
			case (mb_ereg("ㅎㅏㅈㅣㅁㅏㄴ", $conj)):
				$clause_without_conj = mb_ereg_replace ("ㅎㅏㅈㅣㅁㅏㄴ", "",$clause_with_conj);
				//special no verb. (REMOVE?)
				$english = "but";
			break;
			case (mb_ereg("ㅈㅣㅁㅏㄴ", $conj)):
				$clause_without_conj = mb_ereg_replace ("ㅈㅣㅁㅏㄴ", "",$clause_with_conj);
				//conjugated in past
				$english = "but";
			break;
				case (mb_ereg("ㄱㅣ\sㄸㅐㅁㅜㄴㅇㅔ", $conj)):
				$clause_without_conj = mb_ereg_replace ("ㄱㅣ\Wㄸㅐㅁㅜㄴㅇㅔ", "",$clause_with_conj);
				//conjugated in past
				$english = "because";
			break;
				case (mb_ereg("ㄱㅣ\sㅈㅓㄴㅇㅔ", $conj)):
				$clause_without_conj = mb_ereg_replace ("ㄱㅣ\Wㅈㅓㄴㅇㅔ", "",$clause_with_conj);
				$english = "before";
				//conjugated in past
			break;
			case (mb_ereg("(ㅇㅡ)?ㄹ\s?ㄸㅐ", $conj)):
				$clause_without_conj = mb_ereg_replace ("(ㅇㅡ)?ㄹ\W?ㄸㅐ", "",$clause_with_conj);
				//conjugated in past
				$english = "while";
			break;
			case (mb_ereg("((ㅇㅡ)|[^ㅂ,\s])ㄴㅣㄲㅏ", $conj)):
				$clause_without_conj = mb_ereg_replace ("(ㅇㅡ)?ㄴㅣㄲㅏ", "",$clause_with_conj);
				//conjugated in past
				$english = "so";
			break;
			case (mb_ereg("(ㅇㅡ)?ㅁㅕㄴ", $conj)):
				$clause_without_conj = mb_ereg_replace ("(ㅇㅡ)?ㅁㅕㄴ", "",$clause_with_conj);
				//conjugated in past
				$english = "if";
			break;
			case (mb_ereg("(ㅇㅡ)?ㄴ\sㄷㅏㅇㅡㅁㅇㅔ", $conj)):
				$clause_without_conj = mb_ereg_replace ("(ㅇㅡ)?ㄴ\Wㄷㅏㅇㅡㅁㅇㅔ", "",$clause_with_conj);
				//conjugated in past
				$english = "after";
			break;
			case (mb_ereg("(ㄴㅡ)?ㄴㄷㅔ", $conj)):
				$clause_without_conj = mb_ereg_replace ("(ㄴㅡ)?ㄴㄷㅔ", "",$clause_with_conj);
				//conjugated in past
				// look for 인데 special case...
				$english = "but";
			break;
			case (mb_ereg("(ㅇㅡ)?ㄹㅕㄱㅗ", $conj)):
				$clause_without_conj = mb_ereg_replace ("(ㅇㅡ)?ㄹㅕㄱㅗ", "",$clause_with_conj);
				//conjugated in past
				$english = "for the purpose of";
			break;
			case (mb_ereg("ㄱㅗ", $conj)):
				$clause_without_conj = mb_ereg_replace ("ㄱㅗ", "",$clause_with_conj);
				//conjugated in past. (if cwc is ㅆ..)
				$english = "and";
			break;
			case (mb_ereg("ㅅㅓ", $conj)):
				$clause_without_conj = mb_ereg_replace ("ㅅㅓ", "",$clause_with_conj);
				$conjugated = true;
				$english = "so";
			break;
			case (mb_ereg("ㄷㅗ", $conj)):
				$clause_without_conj = mb_ereg_replace ("ㄷㅗ", "",$clause_with_conj);				
				$conjugated = true;
				$english = "even though";
			break;
			case (mb_ereg("ㄹㅓ", $conj)):
				$clause_without_conj = mb_ereg_replace ("ㄹㅓ", "",$clause_with_conj);
				//conjucated in past.
				$english = "for the purpose of";
			break;
			default:
			echo "UNHANDLED CONJUNCTION. conj is: $conj<br>";
			break;			
		}
		
		$last_character = mb_substr($clause_without_conj, -1);
		if ($last_character == "ㅆ") $conjugated = true;
		
		$clause_con_length = mb_strlen( $clause_with_conj );
// 		echo "clause con Length = " . $clause_con_length . " and clause without conj = " . mb_strlen( $clause_without_conj ) . "<br>";
		$conj_length = $clause_con_length - mb_strlen( $clause_without_conj );
		
		//length of the clause, length of the conjunction, offset(from previous find)
		$clause_candidate = new s_clause($conj_pos, $conj_length, $offset);
		$clause_candidate->english = $english; 
// 		echo "clause candidate: \n";
// 		print_r($clause_candidate);
		//attempt to add the clause to the clause array if the verb preceeding the clause can be found		
		if ( add_clause ( $clause_candidate, $decomp_sentence, $clause_without_conj, $conjugated ) ) {
			//need to do more testing with clauses... this fixes the sentence printing..
			if(!isset($clause_array)) $clause_candidate->offset = 0;
			$clause_array[] = $clause_candidate;
		}
		
		$offset = $conj_length + $conj_pos;
		
	}
	return $clause_array; //if there were no matches then it will be NULL..
	
}

// $c_c   - is the new clause candidate, to be possibly added to the array
// $c_w_c - is the clause without the conjunction (from the shifted sentence)
//*** modify add clause to work with handle verb function.
function add_clause ( $c_c, $decomp_sentence, $c_w_c, $conjugated ) {
	
	//need to remove whitespace from c_w_c because it's being appended to.

	$c_w_c = trim($c_w_c);
	
	
	//clauses are only used with "action" verbs not "descriptive" verbs

	//get the lastword of this clause, without the conjunction 
	$decomp_clause = mb_substr($decomp_sentence, $c_c->offset , $c_c->conj_pos - $c_c->offset);
	
// 	echo "decomp clause: $decomp_clause<br>\n"; 
// 	echo "a substring: ". mb_substr($c_w_c, -2);
	
	//This is REALLY insufficient. need to know which conjunctions require 다 and which don't.
	
	//if the verb is past tense add 다 else add 요
	if ($conjugated) echo "conjugated true\n";
	if (mb_substr($c_w_c, -1) == "ㅆ") echo "ssang shiot at end\n";
	if (mb_substr($c_w_c, -1) == "ㅆ" || !$conjugated) {
		echo "adding da";
		$c_w_c .= "ㄷㅏ";
		$da = "다";
		utf_normalizer::nfd($da); 	
		$decomp_clause .= $da;
	} else {
		$c_w_c .= "ㅇㅛ";
		$yo = "요";
		utf_normalizer::nfd($yo); 	
		$decomp_clause .= $yo;
	} 

	$shifted_words = preg_split('/ /', $c_w_c, -1, PREG_SPLIT_NO_EMPTY);

	$clause = $decomp_clause;
	
	utf_normalizer::nfc($clause);
	
	echo "clause is $clause\n";
	
	echo "decomp is $decomp_clause \n\nand cwc is $c_w_c and clause is $clause\n";
	$decomp_words = preg_split('/ /', $decomp_clause, -1, PREG_SPLIT_NO_EMPTY);	
	$words = preg_split('/ /', $clause, -1, PREG_SPLIT_NO_EMPTY);	
	
	
	echo "values for handle verb: \n";
	echo "clause: $clause\nwords: ";
	print_r($words);
	echo "\ndecomp words: ";
	print_r($decomp_words);
	echo "\n shifted words: ";
	print_r($shifted_words); 
	echo "\n\n";
	
	//check for verb and tense preceeding the conjunction.
	$clause_m_verb = handle_verb($clause, $words, $decomp_words, $shifted_words);

	//remove 다 by taking off the last two characters of "tense part"
	$clause_m_verb->tense_part = mb_substr($clause_m_verb->tense_part, 0, -1);
	
// 	print_r($clause_m_verb);
	//if we found a verb preceeding the conjunction
	if(isset($clause_m_verb->english)) {

		//add normalized clause length values to array
		
		$clause_without_conj_length = $c_c->conj_pos;

		$decomp_clause_without_conj = mb_substr($decomp_sentence, 0, $clause_without_conj_length);
		utf_normalizer::nfc($decomp_clause_without_conj);
		
		$c_c->reg_clause_length = mb_strlen( $decomp_clause_without_conj, "utf8" );
		
		//find normalized string length of conjunction
	
		$decomp_conj = mb_substr($decomp_sentence, $c_c->conj_pos, $c_c->conj_length);
		utf_normalizer::nfc($decomp_conj);
		$c_c->reg_conj_length = mb_strlen($decomp_conj);
		
		$c_c->m_verb = $clause_m_verb;

		//add the successful candidate to the array
		return true;
	} 

}

function find_tense ($verb) {
//For ~ㅂ니다 need to remove ending and detect future, past or present 습 needs to be checked for as well.

// Verb matching constant patterns TENSE: ***지금의 추세라면 새터민들의 수는 앞으로 더욱 가파른 속도로 증가할 것으로 보인다.***
// The above sentence is near future special case..

$past_continuous 	= 'ㄱㅗ\s?ㅇㅣㅆㅇㅓㅆㄷㅏ$|ㄱㅗ\s?ㅇㅣㅆㅇㅓㅆㅅㅡㅂㄴㅣㄷㅏ$|ㄱㅗ\s?ㅇㅣㅆㅇㅓㅆㅇㅓ$|ㄱㅗ\s?ㅇㅣㅆㅇㅓㅆㅇㅓㅇㅛ$|ㄱㅗ\s?ㅇㅣㅆㅇㅓㅅㅕㅆㅇㅓㅇㅛ$';
$future_continuous 	= 'ㄱㅗ\s?ㅇㅣㅆㄱㅓㄷㅏ$|ㄱㅗ\s?ㅇㅣㅆㄱㅓㅂㄴㅣㄷㅏ$|ㄱㅗ\s?ㅇㅣㅆㅇㄹㄱㅓㅇㅑ$|ㄱㅗ\s?ㅇㅣㅆㅇㄹㄱㅓ예ㅇㅛ$';
$present_continuous = 'ㄱㅗ\s?ㅇㅣㅆㄷㅏ$|ㄱㅗ\s?ㅇㅣㅆㅅㅡㅂㄴㅣㄷㅏ$|ㄱㅗ\s?ㅇㅣㅆㅇㅓ$|ㄱㅗ\s?ㅇㅣㅆㅇㅓㅇㅛ$';
$intentional_future = 'ㄱㅐㅆㄷㅏ$|ㄱㅐㅆㅅㅡㅂㄴㅣㄷㅏ$|ㄱㅐㅆㅇㅓ$|ㄱㅐㅆㅇㅓㅇㅛ$';
$past_match			= '[^ㅣ]ㅆㄷㅏ$|[^ㅣ]ㅆㅅㅡㅂㄴㅣㄷㅏ$|ㅆㅇㅓ$|ㅆㅇㅓㅇㅛ$|ㅅㅕㅆㅇㅓㅇㅛ$';
$past_replace		= 'ㅆㄷㅏ$|ㅆㅅㅡㅂㄴㅣㄷㅏ$|ㅆㅇㅓ$|ㅆㅇㅓㅇㅛ$|ㅅㅕㅆㅇㅓㅇㅛ$';
$future 			= 'ㄹ\s*ㄱㅓㄷㅏ$|ㄹ\s*ㄱㅓㅂㄴㅣㄷㅏ$|ㄹㄱㅓㅇㅑ$|ㄹ\s*ㄱㅓㅇㅔㅇㅛ$|ㅅㅣㄹ\s*ㄱㅓㅇㅔㅇㅛ$|ㄹ\s*ㄱㅓㅇㅑ$';
$present 			= 'ㄷㅏ$|(ㅅㅡ)?ㅂㄴㅣㄷㅏ$|ㅇㅓㅇㅛ|ㅇㅓ$|ㅇㅛ$|ㅇㅑ$';
$reported_speech	= 'ㄴㄷㅏ$'; //**보인다**
$past_negative		= 'ㅈㅣ ㅇㅏㄶㅇㅏㅆㄷㅏ$|ㅈㅣ ㅇㅏㄶㅇㅏㅆㅅㅡㅂㄴㅣㄷㅏ$|ㅈㅣ ㅇㅏㄶㅇㅡㅅㅕㅆㅇㅓㅇㅛ$';
$propositive		= '(ㅇㅡ)?ㅅㅣㅂㅅㅣㄷㅏ$|ㅂㅅㅣㄷㅏ$|ㄹㄲㅏ$|ㄹㄹㅐ$|ㄴㅡㄴㄱㅜㄴㅇㅛ$|ㅈㅣ(ㅇㅛ)?$|(ㅇㅣ)?ㄱㅜㄴ(ㅇㅛ)?$|(ㅇㅣ)?ㄹㅗㄱㅜㄴ(ㅇㅛ)?$';  
$interrogative		= '(ㅅㅡ|ㅇㅣ)?ㅂㄴㅣㄲㅏ$|(ㅇㅡ)?ㄴㅣㄲㅏ$|(ㅇㅡ)?ㄹㄲㅏ$|ㄴㅡㄴ$';  

	

	//using the constant patterns above, try to match tense to verb.
	switch (true) {
		case (mb_ereg ($past_continuous, $verb)):
			$mverb = mb_eregi_replace ($past_continuous, "",$verb);
			$to_return = new m_verb("past continuous", $mverb, mb_strlen($mverb, "utf8"));
		break;
		case (mb_ereg ($future_continuous, $verb)):
			$mverb = mb_eregi_replace ($future_continuous, "",$verb);
			$to_return = new m_verb("future continuous", $mverb, mb_strlen($mverb, "utf8"));
		break;
		case (mb_ereg ($present_continuous, $verb)):
			$mverb = mb_eregi_replace ($present_continuous, "", $verb);
			$to_return = new m_verb("present continuous", $mverb, mb_strlen($mverb, "utf8"));
		break;
		case (  mb_ereg ($intentional_future, $verb)):
			$mverb = mb_eregi_replace ($intentional_future, "", $verb);
			$to_return = new m_verb("intentional future", $mverb, mb_strlen($mverb, "utf8"));
		break;
		case (  mb_ereg ($past_match, $verb)):
			$mverb = mb_eregi_replace ($past_replace, "", $verb);
			$to_return = new m_verb("past", $mverb, mb_strlen($mverb, "utf8") );
		break;
		case (  mb_ereg($future, $verb)):
			$mverb = mb_eregi_replace ($future, "", $verb);
			$to_return = new m_verb("future", $mverb, mb_strlen($mverb, "utf8"));
		break;
		case (  mb_ereg($propositive, $verb)):
			$mverb = mb_eregi_replace ($propositive, "", $verb);
			print_r($mverb);
			$to_return = new m_verb("propositive", $mverb, mb_strlen($mverb, "utf8") );
		break;
		case (  mb_ereg($reported_speech, $verb)):
			$mverb = mb_eregi_replace ($reported_speech, "", $verb);
			$to_return = new m_verb("reported_speech", $mverb, mb_strlen($mverb, "utf8") );
		break;
		case (  mb_ereg($present, $verb)):
			$mverb = mb_eregi_replace ($present, "", $verb);
			$to_return = new m_verb("present", $mverb, mb_strlen($mverb, "utf8") );
		break;
		default: //nothing to replace
			$mverb = $verb;
			$to_return = new m_verb("present", $mverb, mb_strlen($mverb, "utf8") );
		break;
	}

	//determine whether to search for conjugated or non-con. verb.
	switch (mb_substr($verb, $to_return->length, 1)) {
		case "ㅆ":
		$to_return->conjugated = true;
		break;
		case "ㅇ":
		$to_return->conjugated = true;
		break;
		case "ㄹ":
		$to_return->conjugated = false;
		break;
		case "ㄱ":
		$to_return->conjugated = false;
		break;
		case "ㄷ":
		$to_return->conjugated = false;
		break;
		default: $to_return->conjugated = false;
	}
	return $to_return;
} //end function
function find_tense_for_adno ($verb) {

	// making check tense list
	$present	= 'ㄴㅡㄴ$|ㅇㅡㄴ$|(?<!ㄷㅓ)ㄴ$';
 	$future 			= 'ㅇㅡㄹ$|ㄹ$';
 	$past_retrospective = 'ㅆ?ㄷㅓㄴ$|(ㅇㅡ)?ㄹㅕㄷㅓㄴ$|(ㅇㅡㄹ)ㄹ?ㄹㅕㄷㅓㄴ$';



	//using the constant patterns above, try to match tense to verb.
	switch (true) {

		case (mb_ereg ($present, $verb)):
			$mverb = mb_eregi_replace ($present, "",$verb);
			$to_return = new m_verb("present", $mverb, mb_strlen($mverb, "utf8"));
		break;
				case (mb_ereg ($future, $verb)):
			$mverb = mb_eregi_replace ($future, "",$verb);
			$to_return = new m_verb("future", $mverb, mb_strlen($mverb, "utf8"));
		break;
		
		case (mb_ereg ($past_retrospective, $verb)):
			$mverb = mb_eregi_replace ($past_retrospective, "",$verb);
			$to_return = new m_verb("past_retrospective", $mverb, mb_strlen($mverb, "utf8"));
		break;
		
	}
	return $to_return;
} //end function



?>