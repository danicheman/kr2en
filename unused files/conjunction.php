<?php

function mb_rtrim($str,$charlist=''){    
	if (strlen($charlist)==0) {        
		return rtrim($str);    
	} else {        
		$charlist = preg_quote($charlist,'#');        
		return preg_replace('#['.$charlist.']+$#u','',$str);    
	}
}

class Conjunction {
	function Conjunction($cp, $cl) {
		//position in the sentence of the conjunction
		$this->conj_pos = $cp;
		//length of the conjunction
		$this->conj_length = $cl;
	}
}

function new_divide_clauses($s_and_w, $my_m_verb) {
	if (is_null($s_and_w->shifted_sentence) || is_null($s_and_w->decomp_sentence)) die("fatal call to 'Split Sentnace Conjunctions': called with undefined variables."); //nothing to translate, unsuccessful.
	
	/* 
	 * use my_m_verb to remove a portion of shifted and decomposed sentences 
	 * (because the verb already matched those parts).
	 */
	 
	$words_to_chop = 0;

	if ($my_m_verb) {
		$words_to_chop++;
		if ($my_m_verb->isdouble)$words_to_chop++;
		if ($my_m_verb->two_word_tense)$words_to_chop++;
		if ($my_m_verb->iv_particle) $words_to_chop += $my_m_verb->iv_particle;
	}
	$pattern = '(\s+\S+){' . $words_to_chop . '}$';
	
	$shifted_sentence = mb_ereg_replace($pattern, "", $s_and_w->shifted_sentence);
	$decomp_sentence = mb_ereg_replace($pattern, "", $s_and_w->decomp_sentence);
// 	removed following:
// 	 '',

		$pattern = '(\B으?나|(?<!에)\B서|\B려고?|\B다가|\B도|\B러|\B더니|\B지만|\B기\s+때문에|\B기\s+전에|을?\s+때|\B으?니까|\B으?면|\s+다음에|\B는?데|(?<![냐다라러려자])고(?!\s있))\b';	
	
	//use this variable so that we don't match anything more than once.
	$last_match = 0;
		
	//initalize the search
	mb_ereg_search_init($s_and_w->sentence, $pattern);
	
	//edited to search BACKWARDS through the sentence to find larger verbs
	while ($pos = mb_ereg_search_pos()) {
		//----new----
		$positions[] = $pos;
	}
	for ($i = sizeof($positions) - 1; isset($positions[$i]); $i--) {
		
		$pos = $positions[$i];
		//--end new--
		
		//convert the BYTE positions returned from "mb_ereg_search_pos" as CHARACTER positions
		$start = mb_strlen(substr($s_and_w->sentence, 0, $pos[0]));
		$length = mb_strlen(substr($s_and_w->sentence, $pos[0], $pos[1]));

		//echo "Pre-match: " . mb_substr($s_and_w->sentence, $start, $length) . "\n";
		
		/* if the previously verified conjunction's verb's start position is before the current 
		   conjunction's start, skip to the next found conjunction.
		*/
		if(gettype($conjunctions) == 'array' && end($conjunctions)->m_verb->verb_start_pos < $start) {
 			//echo "overlap!";
			continue;
		}
		//determine whether the matched portion begins with 으 and if it does, 
		//set the variable $eu to true and shift the "start" pointer forward one position
		$first_conjunction_syllable = mb_substr($s_and_w->sentence, $start,1);
		
		//length of the clause, length of the conjunction, offset(from previous find)
		$conjunction_obj = new Conjunction($start, $length);
				
	   /* if the conjunction is preceeded by a verb particle, remove that particle
		* in order to get the proper definition for the word.
		*
		* NOTE: this will ONLY be possible if the particular conjunction allows us to match
		* such a particle. 
		*/
		
		if( $first_conjunction_syllable == '을' || $first_conjunction_syllable == '는' || $first_conjunction_syllable == '으' ) {
			$start++;
			$length--;
			$conjunction_obj->moved = true;
		}else $conjunction_obj->moved = false;

		//the text of the conjunction part
		$conjunction = mb_substr($s_and_w->sentence, $start, $length);
		
		echo "Matched: $conjunction \n";
		
				

				
		//order_action 0 = leave in place 1 = swap with verb 2 = swap with object, 2 = swap with oe, 3 = swap with oe or object
		

		

		
		//everything from the beginning of the sentence to the start of this conjunction
		$pre_conjunction = mb_substr($s_and_w->sentence, 0, $start);
		
		//do we need to adjust the pre-conjunction object?
		if($conjunction_obj->moved) $pre_conjunction = mb_substr($pre_conjunction, 0, -1);
		
		//swich case on last two elements of the particle
		switch ($conjunction) {
			case '나':
				//only with past or present verb
				$cm_verb = find_conjunction_tense($pre_conjunction);
				$cm_verb = handle_conjunction($pre_conjunction, $cm_verb);
				$conjunction_obj->english = "but";
				$conjunction_obj->order_action = 0;
			break;
			case '려':
			case '려고':
				//since there was an 으 preceeding the conjunction, we need to search
				//without it in order to match the verb
				
				//can be past present or future ->look at the next verb to find the tense of this conjunction verb.
				// it the last element of the conjunction array or my_m_verb otherwise.
				
				//find out if next verb is hada
				
				$conjunction_obj->english = "intend to";	
				$cm_verb = find_conjunction_tense($pre_conjunction);
				//force handle to always look for a conjugated verb for this conjunction.
				$cm_verb->conjugated = false;
				$cm_verb = handle_conjunction($pre_conjunction, $cm_verb);
			break;				
			case '다가':
			
				
				$conjunction_obj->english = "while";
				$cm_verb = find_conjunction_tense($pre_conjunction);
				$cm_verb->conjugated = false;
				$cm_verb = handle_conjunction($pre_conjunction, $cm_verb);
				$cm_verb->tense = "ing";
				$conjunction_obj->order_action = 2;
			break;
			case '러':
			
				//can be past present or future ->look at the next verb to find the tense of this conjunction verb.
				// it the last element of the conjunction array or my_m_verb otherwise.
				
				if($my_m_verb->english != "go" && $my_m_verb->english != "come")break;
				$cm_verb = find_conjunction_tense($pre_conjunction);
				$conjunction_obj->english = "for the purpose of";
				//order action for conjunction in relation to verb..
				//0 means swap with verb
				$conjunction_obj->order_action = 2;
				//verbs are never conjugated with this conjunction
				$cm_verb->conjugated = false;
				$cm_verb = handle_conjunction($pre_conjunction, $cm_verb);
				$cm_verb->tense = "ing";
			break;
			case '도':
			
				//forced "ing" tense for action verb
				//forced future
				
				$conjunction_obj->english = "though";
				$cm_verb = find_conjunction_tense($pre_conjunction);
				$cm_verb = handle_conjunction($pre_conjunction, $cm_verb);
				$cm_verb->tense = "ing";
				$conjunction_obj->order_action = 2;

			break;
			case '서':
			
				//find tense from final verb
				
				$cm_verb = find_conjunction_tense($pre_conjunction);
				//currently getting the tense from the last verb.
				$cm_verb->tense = $my_m_verb->tense;
				$conjunction_obj->english = "so";
				//force handle to always look for a conjugated verb for this conjunction.
				if($cm_verb->tense != "future")$cm_verb->conjugated = true;
				$cm_verb = handle_conjunction($pre_conjunction, $cm_verb);
				$conjunction_obj->order_action = 0;
			break;
			case '더니':
				//always past tense
			
				$conjunction_obj->english = "but then";
				$cm_verb = find_conjunction_tense($pre_conjunction);
				$cm_verb = handle_conjunction($pre_conjunction, $cm_verb);
				$conjunction_obj->order_action = 0;
			break;
			case '지만':
			
				//we find past or present verb here
				$cm_verb = find_conjunction_tense($pre_conjunction);
				$cm_verb = handle_conjunction($pre_conjunction, $cm_verb);
				$conjunction_obj->english = "but";
				$conjunction_obj->order_action = 0;
			break;
			case '기 때문에':
				$cm_verb = find_conjunction_tense($pre_conjunction);
				$cm_verb = handle_conjunction($pre_conjunction, $cm_verb);
				$conjunction_obj->english = "because";
				if($cm_verb->tense == 'present') $cm_verb->tense = "ing";
				$conjunction_obj->order_action = 1;
			break;
			case '기 전에':
				$cm_verb = find_conjunction_tense($pre_conjunction);
				$cm_verb = handle_conjunction($pre_conjunction, $cm_verb);

				$conjunction_obj->english = "before";
				if($cm_verb->tense == 'present') $cm_verb->tense = "ing";
				$conjunction_obj->order_action = 1;

			break;
			case ' 때':
			$conjunction_obj->english = "while";
// 			case '때':
				//possibly need to remove ㄹ from end of verb, if $eul is not set
				if(!$conjunction_obj->moved) {
					//look for ㄹ at the end of the verb, remove it and attempt to translate.
					if ($pre_conjunction = remove_last_character($pre_conjunction, 'ㄹ')) {
					} else break;
				} 
					//active and past or present ->while studying / while I was studying
					$cm_verb = find_conjunction_tense($pre_conjunction);
					$cm_verb = handle_conjunction($pre_conjunction, $cm_verb);
					$conjunction_obj->order_action = 0;
			break;
			case '니까':
				//past present or future, keep the tense 
				$conjunction_obj->english = "since";
				$cm_verb = find_conjunction_tense($pre_conjunction);
				$cm_verb = handle_conjunction($pre_conjunction, $cm_verb);
				$conjunction_obj->order_action = 1;
			break;
			case '면':
				//past present or future, active tense
				$cm_verb = find_conjunction_tense($pre_conjunction);
				$cm_verb = handle_conjunction($pre_conjunction, $cm_verb);
				$conjunction_obj->english = "if";
			break;
			case '고':
				//past present or future, active tense
				$conjunction_obj->english = "and";
				$cm_verb = find_conjunction_tense($pre_conjunction);
				$cm_verb = handle_conjunction($pre_conjunction, $cm_verb);
				$conjunction_obj->order_action = 0;
				//currently getting the tense from the last verb..
				$cm_verb->tense = $my_m_verb->tense;
				
			break;
			
			case ' 다음에': //give 다음에 conjunction the same treatment as the following case.
				//past or present, depends on final verb.

				//look for ㄴ at the end of the verb, remove it and attempt to translate.
				if ($pre_conjunction_chop = remove_last_character($pre_conjunction, 'ㄴ')) {
					$conjunction_m_verb = handle_conjunction($pre_conjunction, false);
				} else break;
					
				$conjunction_obj->english = "after";
			break;
			case '데':
				//change nothing, special 이다 case..
				$conjunction_obj->english = "however";
				if(!$conjunction_obj->moved) {
					//look for ㄴ at the end of the verb, remove it and attempt to translate.
					if ($pre_conjunction_chop = remove_last_character($pre_conjunction, 'ㄴ')) {
						$conjunction_m_verb = handle_conjunction($pre_conjunction, false);
					} else break;
				} 
				$conjunction_obj->order_action = 1;	
				$cm_verb = find_conjunction_tense($pre_conjunction);
				$cm_verb = handle_conjunction($pre_conjunction, $cm_verb);
			break;
			default:
			echo "<pre>";
			echo "UNHANDLED CONJUNCTION : >".$conjunction."<\n";
			echo "</pre>";
			break;
		}
		//if an english definition was found, it's a valid conjunction and can be added to the conjunction array.
		if($cm_verb->english) { 
			//add the conjunction
			$conjunction_obj->m_verb = $cm_verb;
			$conjunction_obj->conjunction = mb_substr($s_and_w->sentence, $conjunction_obj->conj_pos, $conjunction_obj->conj_length);
			$conjunctions[] = $conjunction_obj;
		}				
		unset($cm_verb);
	}
	if($conjunctions) {
		//elements were found in reverse order, rearrange the order, and reset the array key.
		krsort($conjunctions);
		$conjunctions = array_values($conjunctions);
	}
	return $conjunctions; //if there were no matches then it will be NULL..
	
}
//separate conjunction characers from verb
function remove_last_character($pre_conjunction, $character_to_remove) {

	//special case
	if($character_to_remove == 'ㅆ' && mb_substr($pre_conjunction, -1) == '있') return mb_substr($pre_conjunction, 0, -1);
	
	//regular case
	utf_normalizer::nfd($pre_conjunction);
	$last_character = jamo_to_co_jamo(mb_substr($pre_conjunction, -1));
	
	if( $last_character == $character_to_remove) {
		//found char to match, remove it
		$pre_conjunction = mb_substr($pre_conjunction, 0,-1);
		utf_normalizer::nfc($pre_conjunction);
		return $pre_conjunction;
	} else return false;
}
//search for conjunction verb
function handle_conjunction($pre_conjunction, $cm_verb) {
// 	echo "here is pc: $pre_conjunction";
// 	echo "here is cm_verb";
// 	print_r ($cm_verb);


	//words array from pre_conjunction
	$words = preg_split('/ /', $pre_conjunction, -1, PREG_SPLIT_NO_EMPTY);	
	//number of words in array from pre_conjunction
	$words_length = sizeof($words);
	
	//length of everything before this conjunction
	
	if ( $cm_verb->two_word_tense && $words_length > 2 ) {
		//search for a definition, with the last 3 words of the sentence.
		//get the third last word from the sentence
		// 1 못 2 하고 3 있어요
		$rev_offset = 3;

	} else {
		//search for a definition with the last 1 or 2 words of the sentence.
		// 1 못 2 했어요
		$rev_offset = 2;
	}

	//inter-verb particle count
	$iv_particle = 0;
	
	//searching for particles that can come between two-word verbs
	//$i can be 0, 1 or 2, this allows up to three particles
	for ( $check = $words[$words_length - $i - $rev_offset], $i = 0; $i < 3 && $check == "잘" || $check == "못" || $check == "안" ||$check == "꼭" || $check == "다시"; $i++, $check = $words[$words_length - $i - $rev_offset]) {
		switch ($check) {
			case "잘":
				$cm_verb->jal = true;
				break;
			case "못":
				$cm_verb->mot = true;
				break;
			case "안":
				$cm_verb->an = true;
				break;
			case "꼭":
				$cm_verb->ggog = true;
				break;	
			case "다시":
				$cm_verb->dashi = true;
				break;	
			default:
				die ("this is an error...looking for verb seperating particle.");
				break;
		}	
		$iv_particle++;
	}
	$first_verb_word = $words[$words_length - $i - $rev_offset];
	if($iv_particle) $cm_verb->iv_particle = $iv_particle;
	
	//use last particle removal to remove 을, 를, 이, 가 from end of $first_verb_word
	$last_particle = mb_substr($first_verb_word, -1, 1);

	//if the last character in the sentence is a period, question mark, or exclamation mark, omit it.
	
	
	//WHAT HAPPENS WHEN WE REMOVE A PARTICLE?!?!?
	
	
	if ($last_particle == "을"|| $last_particle == "를"|| $last_particle == "이" || $last_particle == "가" || $last_particle == '도'){
		//omit the last character from the end of the first verb word.
		$first_verb_word = mb_substr($first_verb_word, 0, -1);
		$cm_verb->removed_particle = true;
	}
	
	//the big verb is composed of the verb part removed from the tense part
	// and the word preceeding it.
	$first_verb_syllable = mb_substr($cm_verb->verb_part, 0,1);
	$last_verb_syllable = mb_substr($cm_verb->verb_part, -1);
	$verb_part_backup = $cm_verb->verb_part;
	
	//need to remove and handle special particles connected to second verb word
	if (($first_verb_syllable == "잘" || $first_verb_syllable == "못") && ($last_verb_syllable == "해" || $last_verb_syllable == "하" || $last_verb_syllable == "되" || $last_verb_syllable == "돼")) {
		//need to remove at least the first syllable, maybe the second.
		$second_verb_syllable = mb_substr($cm_verb->verb_part, 1,1);
		if ($second_verb_syllable == "잘" || $second_verb_syllable == "못") {
			$cm_verb->jal = true;
			$cm_verb->mot = true;
			$cm_verb->verb_part = mb_substr($cm_verb->verb_part, 2); 
		} elseif ($first_verb_syllable == "잘") {
			$cm_verb->jal = true;
			$cm_verb->verb_part = mb_substr($cm_verb->verb_part, 1);
		} elseif ($first_verb_syllable == "못") {
			$cm_verb->mot = true;
			$cm_verb->verb_part = mb_substr($cm_verb->verb_part, 1);
		} else echo "error!! unhandled pre verb word syllable.";
	}
	//***since "verb part" is now part of cm_verb, it doesn't need to be passed seperately.
	$bigverb = $first_verb_word . " " . $cm_verb->verb_part;
	
// 	echo "bigverb is $bigverb";
// 	echo "print_r of cm_verb : ";
// 	print_r($cm_verb);
	//need to fix printing for chal and mot in one word verbs. by changing the value for word start.
	if ($cm_verb->conjugated) {
		echo "Looking for conjugated: ". $bigverb . " and " . $cm_verb->verb_part. "<br>\n";
		k2e_con_verb($cm_verb->verb_part, $bigverb, $cm_verb);
	} else {
		echo "Looking for non-conjugated: ". $bigverb. " and " . $cm_verb->verb_part. "<br>\n";
		k2e_nocon_conj_verb($cm_verb->verb_part, $bigverb, $cm_verb);
	}
	
	//get the value for $verb_start
	if ($cm_verb->isdouble) $verb_start = $first_verb_word; //it was a two word verb...
	else {
		//use IV particle value to set it up.
		$cm_verb->removed_particle = false;
		
		if($my_m_verb->two_word_tense) {
			//second last word of the sentence
			$verb_start = $words[$words_length - 2 - $iv_particle];
		} else {
			//last word of the sentence
			$verb_start = $words[$words_length - 1 - $iv_particle];
		}
	}

// 		echo "Verb start is : $verb_start\n\n\n";
	if ($cm_verb->two_word_tense) $verb_and_tense_word = $words[$words_length - 2];
	else $verb_and_tense_word = $words[$words_length - 1];
	
	$cm_verb->verb_start_pos = mb_strrpos($pre_conjunction, $verb_start);
	$cm_verb->verb_mid_pos = mb_strrpos($pre_conjunction, $verb_and_tense_word);
	
	return $cm_verb;
}










function find_conjunction_tense ($pre_conjunction) {
//For ~ㅂ니다 need to remove ending and detect future, past or present 습 needs to be checked for as well.
utf_normalizer::nfd($pre_conjunction);

$verb = jamo_to_co_jamo($pre_conjunction);
// Verb matching constant patterns TENSE: ***지금의 추세라면 새터민들의 수는 앞으로 더욱 가파른 속도로 증가할 것으로 보인다.***
// The above sentence is near future special case..
$past_match			= '[^ㅣ]ㅆㅇㅓㅆ$|[^ㅣ]ㅆㅇㅓ$|ㅅㅕㅆㅇㅓㅆ$|[^ㅣ]ㅆ$';
$past_replace		= 'ㅆㅇㅓㅆ$|ㅆㅇㅓ$|ㅅㅕㅆ$|ㅆ$';
$future 			= 'ㄹㄱㅓ$|ㄹㄱㅓㅂㄴㅣ$|ㅅㅣㄹㄱㅓ$';
	
	//using the constant patterns above, try to match tense to verb.
	switch (true) {
		case (  mb_ereg ($past_match, $verb)):
			$mverb = mb_eregi_replace ($past_replace, "", $verb);
			$length = mb_strlen($mverb);
			$to_return = new m_verb("past", mb_substr($pre_conjunction, 0, $length), mb_strlen($mverb, "utf8") );
		break;
		case (  mb_ereg($future, $verb)):
			$mverb = mb_eregi_replace ($future, "", $verb);
			$length = mb_strlen($mverb);
			$to_return = new m_verb("future", mb_substr($pre_conjunction, 0, $length), mb_strlen($mverb, "utf8"));
		break;
		default: //nothing to replace
			$mverb = $verb;
			$length = mb_strlen($mverb);
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

	//cut pre_conjunction's "last word" off to create "verb_part"
	if($last_space = mb_strrpos($pre_conjunction, " "))$last_space++;
	
	
	//*** added end position to verb part.
// 	echo "verb part inputs: $pre_conjunction\n LAST SPACE: $last_space \n LENGTH: $length\n";
	$verb_part = mb_substr($pre_conjunction, $last_space, $length - $last_space);
	$tense_part = mb_substr($pre_conjunction, $length);
	utf_normalizer::nfc($verb_part);
	//add the 'verb part' into the to_return m_verb object.
	$to_return->verb_part = $verb_part;
	$to_return->tense_part = $tense_part;
	return $to_return;
} //end function

?>