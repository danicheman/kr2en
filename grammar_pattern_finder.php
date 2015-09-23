<?php
mb_internal_encoding( 'UTF-8' );
mb_regex_encoding('utf-8');
//*****************function has_adnom_ending()*****************************************************************************
//this is first step for finding adnominal ending, it try to find unique adnomial pattern from string**********************
//if it find unique pattern of adnominal ending it will returne true and process first step for adnominal edning***********
//this takes one parametar : $s_and_w and resturn true or false************************************************************
//*************************************************************************************************************************
function has_adnom_ending($s_and_w) {
	/*
	  check the end of the word using ereg match for
	  adnominal verb endings and return true if it 
	  does, and false if it does not.
	 */

	// $shifted_sentence = $s_and_w->shifted_sentence;
	// print_r($shifted_sentence);
	// print_r($s_and_w);

	$pattern = "(?<=\S)((ㄴㅡ)?ㄴ|(ㅇㅡ)?ㄹ|ㄷㅓㄴ)\s";	
		 
	if(preg_match("/".$pattern ."/u", $s_and_w->shifted_sentence)) return true;
	 
	return false;
}
function create_gp_array($adnominal_array, $original_sentence){
	//count how many adnominal array for looping each adnominal array
 	$number_adnominal_array=count($adnominal_array);
 	//create array for return value from grammar pattern check function
 	$results = array();
 	$adnominal_array = array_reverse($adnominal_array);
	//if number_adnominal_array is bigger than 0 it meant we found out possible grammar pattern from sentencse
	if($number_adnominal_array > 0){
		//gp offset
		$offset=0;
		//this for loop for check each possible grammar pattern if it find any retrun value as named results and merge with existing array
		for ($o=0; $o<$number_adnominal_array; $o++){
			$s_and_w = new sentence_and_words($adnominal_array[$o]->possible_gp); 
			$results = array_merge($results,grammar_pattern_check($s_and_w, $original_sentence, $offset));
							
			$offset= $results[$o]->grammar_pattern_offset;	
		}
	}else{
		$results = array_merge($results,grammar_pattern_check($s_and_w, $original_sentence));
	}
	//return results 
	return $results;
}

//****************************function adno_check**************************************************
//*****this function take 1 parameters $s_and_w****************************************************
//*****it find out adnominal ending display one screen ********************************************
//*************************************************************************************************

function grammar_pattern_check($s_and_w, $original_sentence, $offset){
	//make empty array
	$grammar_array=array();
	//call grammar_pattern_handle function and make a grammar pattern array
	
	
	if($results = grammar_pattern_handle ($s_and_w->sentence, $s_and_w->words, $s_and_w->decomp_words, $s_and_w->shifted_words, $original_sentence, $offset)){
 		$grammar_array = array_merge($grammar_array, $results );
	}
	
 //return grammar array
	return array_values($grammar_array);
}

//****************************function grammar_pattern_values********************************************
//*****this function take 2 parameters &$object_sentence_array $grammar_pattern_array********************
//*************************************************************************************************
function grammar_pattern_values (&$object_sentence_array, $grammar_pattern_array) {
	
	foreach($grammar_pattern_array as $grammar_object) {
			
		//if the adnominal verb is the last word in the clause, join it to the next one
		//if the adnominal verb ended in a particle,  을 or 는, this is necessary
		//------------------------------------------------------------

		$start_pos = get_sentence_pos($object_sentence_array, $grammar_object->grammar_pattern_start_pos);
		$last_pos = get_sentence_pos($object_sentence_array, $grammar_object->grammar_pattern_last_pos);
		
		$start = $grammar_object->grammar_pattern_start_pos;
		$last = $grammar_object->grammar_pattern_last_pos;
		
		
		if($last_pos->i != $start_pos->i) {
			//start and mid position clause are different so set all values after start position in clause to true
			$clause_word_count = num_of_clause_words($object_sentence_array[$start_pos->i]);
			
			for ( $word_counter = $start_pos->j; $word_counter < $clause_word_count; $word_counter++ ) {
				if(is_null($object_sentence_array[$start_pos->i][$word_counter]->english)) {
				$object_sentence_array[$start_pos->i][$word_counter]->english = true;
				//NEW~! (1 line)
				$object_sentence_array[$start_pos->i][$word_counter]->type = "grammar pattern";
				}
			}
			//set word_counter to zero for the next loop
			$word_counter = 0;
		} else {
			//the start pos and mid pos clause ($start_pos->j/$last_pos->j) values are the same, so start 
			//the word counter at the start position ($start_pos) word
			$word_counter = $start_pos->j;
		}
		if($grammar_object->grammar_pattern_start_pos != $grammar_object->grammar_pattern_last_pos ) {
			//now we MUST be in the same clause as the final verb word
			for (;$word_counter < $last_pos->j; $word_counter++) {
				if(is_null($object_sentence_array[$last_pos->i][$word_counter]->english)) {
					$object_sentence_array[$last_pos->i][$word_counter]->english = true;
					//NEW~! (1 line)
					$object_sentence_array[$start_pos->i][$word_counter]->type = "grammar pattern";
				}
			}
			
		}
		
		$object_sentence_array[$last_pos->i][$last_pos->j]->grammar_pattern = $grammar_object;
		//NEW~! (6 lines)
		$object_sentence_array[$last_pos->i][$last_pos->j]->type = "grammar pattern";
		//set has_subject to true in the clase when the grammar pattern starts with "there"
		if(mb_substr($grammar_object->english, 0, 5) == 'there') {
			//clause object has_subject is true so no subject will be inserted
			$object_sentence_array[$last_pos->i]['has_subject'] = true;
		}
	}
}
//****************************function apply_grammar_pattern_to_osa********************************
//*****this function take 3 parameters &$object_sentence_array $grammar_pattern_array &$t_action_list****
//*************************************************************************************************

function apply_grammar_pattern_to_osa(&$object_sentence_array, $grammar_pattern_array) {
	
	//nothing to do...
	if(is_null($grammar_pattern_array)) return false;

	//conjunction and verb should be in their own section labelled appropriately
	//initialize variable for the offset of the last conjugation we delt with
	$last_conj_offset = 0;
	
	//deal with each conjunction object individually.
	foreach ($grammar_pattern_array as $grammar_object) {
		
		/* if this conjunction's verb has a "removed particle"(ie. 를), we need to MERGE
		 * the two "clauses" together.
		 */

		//DANGER: ADDING "+ 1" HERE TO MAKE SURE IT'S THE FIRST VERB WORD POS..
		$start_pos = get_sentence_pos($object_sentence_array, $grammar_object->grammar_pattern_start_pos + 1);

		if ($grammar_object->finding_particle) {

			//for example: $second_verb_word would be the "운전" in "운전 하는"
			$second_verb_word = $object_sentence_array[$start_pos->i][$start_pos->j];

			//since we're going to use the second verb word's clause to merge with the adnominal
			//clause, we need to get the difference between the two clause offsets 
			$offset_shift = $object_sentence_array[$start_pos->i+1]['offset'] - $object_sentence_array[$start_pos->i]['offset'];

			//using the difference between the two clauses in $offset_shift, we can call the 
			//function shift_all_offsets() located in sentence.php.  All the word objects in the
			//clause will be shifted by the amount of $offset_shift.
							
			shift_all_offsets($object_sentence_array[$start_pos->i+1], $offset_shift);
			
			//merge the two clause object strings
			$new_merged_clause = $object_sentence_array[$start_pos->i]['clause'] . $object_sentence_array[$start_pos->i +1]['clause'];
			
			//use the offset from the first clause object after the merge
			$new_merged_offset = $object_sentence_array[$start_pos->i]['offset'];
			
			//merge the clause objects
			$new_clause = array_merge($object_sentence_array[$start_pos->i], $object_sentence_array[$start_pos->i +1]);
			
			//set the merged clause value
			$new_clause['clause'] = $new_merged_clause;
			
			//set the merged offset value
			$new_clause['offset'] = $new_merged_offset;
			
			//sort the objects in the new clause
			ksort($new_clause, SORT_STRING);

			//overwrite the old conjunction clause with a new merged conjunction clause
			$object_sentence_array[ $start_pos->i + 1 ] = $new_clause;
			
			//remove the "removed particle" part of the sentence
			unset( $object_sentence_array[$start_pos->i] );
			
			//reset the array values for the sentence_array (since we unset one of them)
			$object_sentence_array = array_values( $object_sentence_array );

		}	
		$last_pos = get_sentence_pos($object_sentence_array, $grammar_object->grammar_pattern_last_pos);

		//if the position of the last verb word is the same as the last word in the clause, join to next clause
		if($start_pos->j == (num_of_clause_words($object_sentence_array[$start_pos->i]) - 1) ) {
			
			$offset_shift = $object_sentence_array[$start_pos->i+1]['offset'] - $object_sentence_array[$start_pos->i]['offset'];
			
// 			echo "<h2>offset_shift is $offset_shift </h2>";
							
			shift_all_offsets($object_sentence_array[$start_pos->i+1], $offset_shift);
			
			//merge the two clause object strings
			$new_merged_clause = $object_sentence_array[$start_pos->i]['clause'] . $object_sentence_array[$start_pos->i +1]['clause'];
			
			
			//today i did it 
			$object_sentence_array[$start_pos->i]['clause'] = $object_sentence_array[$start_pos->i]['clause'] . $object_sentence_array[$start_pos->i +1]['clause'];
			
// 			$object_sentence_array[$start_pos->i]['Word'] = $object_sentence_array[$start_pos->i]['Word'] . $object_sentence_array[$start_pos->i +1]['Word'];
			
			
			//use the offset from the first clause object after the merge
			$new_merged_offset = $object_sentence_array[$start_pos->i]['offset'];

			
			//merge the clause objects
// 			$new_clause = array_merge($object_sentence_array[$last_pos->i], $object_sentence_array[$last_pos->i +1]);
			
			//set the merged clause value
			$new_clause['clause'] = $new_merged_clause;
			
// 			//set the merged offset value
			$new_clause['offset'] = $new_merged_offset;
			
			//sort the objects in the new clause
			ksort($new_clause, SORT_STRING);
			
			//overwrite the old conjunction clause with a new merged conjunction clause
 			$object_sentence_array[ $start_pos->i + 1 ] = $new_clause;
			
			//reset the array values for the sentence_array (since we unset one of them)
			$object_sentence_array = array_values( $object_sentence_array );
		}
 	}
	return true;
}
?>



