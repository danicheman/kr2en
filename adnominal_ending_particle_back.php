<?php

//****************************function remove_particle_for_adnomianl*******************************
//*****this function take 1 parameters $s_and_w****************************************************
//*****it find out adnominal ending display one screen ********************************************
//*************************************************************************************************
function find_out_adnominal_and_store($s_and_w){
	
	$original_sentence = $s_and_w -> sentence;
	
	
	
	$array_result_after_filted_punctuation=adno_punctuation_remover($s_and_w);
	
	
	
	$number_of_adno_punc=count($array_result_after_filted_punctuation);
	
	
// 	echo "<h2>number_of_adno_punc: $number_of_adno_punc</h2>";

	$results = array();
	
	if ($number_of_adno_punc > 0){
		for ($o=0; $o<$number_of_adno_punc; $o++){
			$s_and_w = new sentence_and_words($array_result_after_filted_punctuation[$o]); 
			
			if($result = adno_check($s_and_w, $original_sentence)) {
				if($results) {
					$results = array_merge($results,$result);
				} else {
					$results = $result;
				}
			}
		}
	} else {
		$results = array_merge($results,adno_check($s_and_w, $original_sentence));
	}
	return $results;
}


//****************************function remove_particle_for_adnomianl*******************************
//*****this function take 1 parameters $s_and_w****************************************************
//*****it find out adnominal ending display one screen ********************************************
//*************************************************************************************************

function adno_punctuation_remover($s_and_w){
	
// 	$preg_pattern = '/.*?([\p{Pd}\p{Ps}\p{Pe}\p{Pi}\p{Pf}\p{Pc}\`\"\'·;:,><]\s*|([^\s\p{Pd}\p{Ps}\p{Pe}\p{Pi}\p{Pf}\p{Pc}\`\"\'·;:,><]+\s*))|(\S+$)/u';

	$pattern_fun = "\`|\"|\'|·|;|:|,|>|<|\.|[A-z]";

	//use this variable so that we don't match anything more than once.
	$last_match = 0;
		
	//initalize the search
	mb_ereg_search_init($s_and_w->shifted_sentence, $pattern_fun);
	
	//finding location of work and store decleared information
	while ($pos = mb_ereg_search_pos()) {
		$byte_start = $pos[0];
		$byte_length = $pos[1];
		
		$mb_start = mb_strlen(substr($s_and_w->shifted_sentence, 0, $byte_start));
		$mb_length = mb_strlen(substr($s_and_w->shifted_sentence, $byte_start, $byte_length));
		$mb_pos[] = array($mb_start, $mb_length);
		
		$result = mb_substr($s_and_w->decomp_sentence, $mb_start, $mb_length);
		utf_normalizer::nfc($result);
	}
	if(isset($mb_pos)){
// 		$mb_pos = array_reverse($mb_pos);

	$total_length=0;
	foreach ($mb_pos as $value) {
		$mb_start = $value[0];
		$mb_length = $value[1];
		$possible_adnominal_particle = mb_substr($s_and_w->decomp_sentence, $mb_start, $mb_length);
		utf_normalizer::nfc($possible_adnominal_particle);
		
// 		echo "<br /> $s_and_w->decomp_sentence";
		
		$the_length=$mb_start + $mb_length -1;
// 		echo "<br />this is total length: $total_length";
// 		echo "<br />this is the length: $the_length";
		
		$result = mb_substr($s_and_w->decomp_sentence, $total_length,  $the_length - $total_length);
		
// 		echo $result "<br />result1 is $result<br />";
		
// 		echo "<br />this is the length: $the_length";
		
		$total_length = $mb_start + $mb_length;
		
	
 		if($result != "" && $result != " "){
			utf_normalizer::nfc($result);
			$result.=" ";
			$array_result[]= $result;
 			}
		
	
	
		
		utf_normalizer::nfc($result);

	}
}
	
	//count how many search results are exist
	$num_of_array=count($array_result);

	$my_m_verb_array=array();

	for ($aa=0; $aa<$num_of_array; $aa++){
		$array_result[$aa];
	}



return $array_result;



}


//****************************function adno_check**************************************************
//*****this function take 1 parameters $s_and_w****************************************************
//*****it find out adnominal ending display one screen ********************************************
//*************************************************************************************************

function adno_check($s_and_w, $original_sentence){
	
	$pattern = "(?<=\S)((ㄴㅡ)?ㄴ|(ㅇㅡ)?ㄹ|ㄷㅓㄴ|ㄱㅗㄴ)\s";	

	//use this variable so that we don't match anything more than once.
	$last_match = 0;
		
	//initalize the search
	mb_ereg_search_init($s_and_w->shifted_sentence, $pattern);
	
// 	echo "<br />shifted_sentence : ".$s_and_w->shifted_sentence. "<br/>";
	
	//finding location of work and store decleared information
	while ($pos = mb_ereg_search_pos()) {
		$byte_start = $pos[0];
		$byte_length = $pos[1];
		
		$mb_start = mb_strlen(substr($s_and_w->shifted_sentence, 0, $byte_start));
		$mb_length = mb_strlen(substr($s_and_w->shifted_sentence, $byte_start, $byte_length));
		$mb_pos[] = array($mb_start, $mb_length);
		
		$result = mb_substr($s_and_w->decomp_sentence, $mb_start, $mb_length);
		utf_normalizer::nfc($result);

	}
	//no adnominals found, return
	if(!$mb_pos) return;
	$mb_pos = array_reverse($mb_pos);
	foreach ($mb_pos as $value) {
		$mb_start = $value[0];
		$mb_length = $value[1];
		$possible_adnominal_particle = mb_substr($s_and_w->decomp_sentence, $mb_start, $mb_length);
		utf_normalizer::nfc($possible_adnominal_particle);
		$result = mb_substr($s_and_w->decomp_sentence, 0, $mb_start + $mb_length);
		utf_normalizer::nfc($result);
		$array_result[]= $result;
		
	}
	//count how many search results are exist
	$num_of_array=count($array_result);
	$array_result = array_reverse($array_result);
	$adnominal_array=array();

	$offest = 0;
	for ($iii=0; $iii<$num_of_array; $iii++){
		$s_and_w = new sentence_and_words($array_result[$iii]);
		//set result_adno value from handle_verb_adnominal function it will return 
		$result_adno = handle_verb_adnominal ($s_and_w->sentence, $s_and_w->words, $s_and_w->decomp_words, $s_and_w->shifted_words, $original_sentence, $offest);
		//if result_adno is setted english value it means handle_verb_adnominal found word from dictionary, so increase the offset
		if(	isset($result_adno[0]->english)){
			$offest = $result_adno[0]-> my_m_verb_offset;
		}
		//if result_adno is set, merged array with existing array
		if(isset($result_adno))$adnominal_array = array_merge($adnominal_array, $result_adno);
	}
	
    $adnominal_array_length = count( $adnominal_array );

	for ($j = 0; $j< $adnominal_array_length; $j++) {
	    
		//check english value for decleare it is usable information or not
	    
		if(!$adnominal_array[$j]->english) {
			unset($adnominal_array[$j]);
	 	} elseif ($adnominal_array[$j]->tense == 'present') {
		 	$adnominal_array[$j]->tense = 'ing';
	 	}
	}
	return array_values($adnominal_array);
}

//set values in osa to true and place the adnominal verb..do the same thing as conjunction?

// function apply_adnominals_to_osa(&$object_sentence_array, $adnominal_array, &$t_action_list) {
// 	
// 	//nothing to do...
// 	if(is_null($adnominal_array)) return false;
// 	
// // 	print_r($adnominal_array);
// 	
// 	
// 	//conjunction and verb should be in their own section labelled appropriately
// 	//initialize variable for the offset of the last conjugation we delt with
// 	$last_conj_offset = 0;
// 	
// 	//deal with each conjunction object individually.
// 	foreach ($adnominal_array as $adnominal_object) {
// 		
// 		/* if this conjunction's verb has a "removed particle"(ie. 를), we need to MERGE
// 		 * the two "clauses" together.
// 		 */

// 		//DANGER: ADDING "+ 1" HERE TO MAKE SURE IT'S THE FIRST VERB WORD POS..
// 		$start_pos = get_sentence_pos($object_sentence_array, $adnominal_object->verb_start_pos + 1);

// 		 		 
// 		if ($adnominal_object->removed_particle) {

// 			//for example: $mid_pos would be the "운전" in "운전 하는"
// 		

// 			//since we're going to use the second verb word's clause to merge with the adnominal
// 			//clause, we need to get the difference between the two clause offsets 
// 			$offset_shift = $object_sentence_array[$start_pos->i+1]['offset'] - $object_sentence_array[$start_pos->i]['offset'];

// 			//using the difference between the two clauses in $offset_shift, we can call the 
// 			//function shift_all_offsets() located in sentence.php.  All the word objects in the
// 			//clause will be shifted by the amount of $offset_shift.
// 							
// 			shift_all_offsets($object_sentence_array[$start_pos->i+1], $offset_shift);
// 			
// 			//merge the two clause object strings
// 			$new_merged_clause = $object_sentence_array[$start_pos->i]['clause'] . $object_sentence_array[$start_pos->i +1]['clause'];
// 			
// 			//use the offset from the first clause object after the merge
// 			$new_merged_offset = $object_sentence_array[$start_pos->i]['offset'];
// 			
// 			//merge the clause objects
// 			$new_clause = array_merge($object_sentence_array[$start_pos->i], $object_sentence_array[$start_pos->i +1]);
// 			
// 			//set the merged clause value
// 			$new_clause['clause'] = $new_merged_clause;
// 			
// 			//set the merged offset value
// 			$new_clause['offset'] = $new_merged_offset;
// 			
// 			//sort the objects in the new clause
// 			ksort($new_clause, SORT_STRING);

// 			
// 			
// 			//overwrite the old conjunction clause with a new merged conjunction clause
// 			$object_sentence_array[ $start_pos->i + 1 ] = $new_clause;
// 			
// 			//remove the "removed particle" part of the sentence
// 			unset( $object_sentence_array[$start_pos->i] );
// 			
// 			//reset the array values for the sentence_array (since we unset one of them)
// 			$object_sentence_array = array_values( $object_sentence_array );

// 		}	
// 		$mid_pos = get_sentence_pos($object_sentence_array, $adnominal_object->verb_mid_pos);
// 		
// 		if ($adnominal_object->verb_start_pos != $adnominal_object->verb_mid_pos) {
// 			//set all words up to verb start pos as adnominal verb
// 			
// 			for($adnominal_pointer = $start_pos->j; $adnominal_pointer < $mid_pos->j; $adnominal_pointer++) {
// 				$object_sentence_array[$start_pos->i][$adnominal_pointer]->type = 'adnominal verb';
// 			}
// 		}
// 		
// 		$object_sentence_array[$mid_pos->i][$mid_pos->j]->type = 'adnominal verb';
// 		
// 				//if the position of the last verb word is the same as the last word in the clause, join to next clause
// 		if($mid_pos->j == (num_of_clause_words($object_sentence_array[$mid_pos->i]) - 1) ) {
// 			
// 			$offset_shift = $object_sentence_array[$mid_pos->i+1]['offset'] - $object_sentence_array[$mid_pos->i]['offset'];
// 							
// 			shift_all_offsets($object_sentence_array[$mid_pos->i+1], $offset_shift);
// 			
// 			//merge the two clause object strings
// 			$new_merged_clause = $object_sentence_array[$mid_pos->i]['clause'] . $object_sentence_array[$mid_pos->i +1]['clause'];
// 			
// 			//use the offset from the first clause object after the merge
// 			$new_merged_offset = $object_sentence_array[$mid_pos->i]['offset'];
// 			
// 			//merge the clause objects
// 			$new_clause = array_merge($object_sentence_array[$mid_pos->i], $object_sentence_array[$mid_pos->i +1]);
// 			
// 			//set the merged clause value
// 			$new_clause['clause'] = $new_merged_clause;
// 			
// 			//set the merged offset value
// 			$new_clause['offset'] = $new_merged_offset;
// 			

// 			//sort the objects in the new clause
// 			ksort($new_clause, SORT_STRING);
// 			
// 			//overwrite the old conjunction clause with a new merged conjunction clause
// 			$object_sentence_array[ $start_pos->i + 1 ] = $new_clause;
// 			
// 			//remove the "removed particle" part of the sentence
// 			unset( $object_sentence_array[$start_pos->i] );
// 			
// 			//reset the array values for the sentence_array (since we unset one of them)
// 			$object_sentence_array = array_values( $object_sentence_array );
// 		}
// 		
// 		
// 				
// 		//is this is an action verb? join with oeseo clauses
// 		if ($adnominal_object->ktype == 0 && $object_sentence_array[$start_pos->i - 1]) {
// 			//last two syllables are correct?
// 			
// 			if ( '에서' == mb_substr(trim($object_sentence_array[$start_pos->i - 1]['clause']), -2)) {
// 				
// 				//since these words modify an adnominal, let them become an
// 				//adnominal verbmod
// // 				echo "setting types for: ";
// // 				print_r($object_sentence_array[$start_pos->i -1]);
// 				
// 				set_clause_words_type($object_sentence_array[$start_pos->i - 1], 'adnominal verbmod');
// // 				echo "set types???";
// // 				print_r($object_sentence_array[$start_pos->i -1]);
// 					//proceed with joining
// 				$offset_shift = $object_sentence_array[$start_pos->i]['offset'] - $object_sentence_array[$start_pos->i-1]['offset'];
// 	
// 				//using the difference between the two clauses in $offset_shift, we can call the 
// 				//function shift_all_offsets() located in sentence.php.  All the word objects in the
// 				//clause will be shifted by the amount of $offset_shift.
// 								
// 				shift_all_offsets($object_sentence_array[$start_pos->i], $offset_shift);
// 				
// 				
// 				
// 				//merge the two clause object strings
// 				$new_merged_clause = $object_sentence_array[$start_pos->i-1]['clause'] . $object_sentence_array[$start_pos->i]['clause'];
// 				
// 	
// 				//use the offset from the first clause object after the merge
// 				$new_merged_offset = $object_sentence_array[$start_pos->i-1]['offset'];
// 				
// 				//merge the clause objects
// 				$new_clause = array_merge($object_sentence_array[$start_pos->i-1], $object_sentence_array[$start_pos->i]);
// 				
// 				
// 				//array splice
// // 				array_splice($object_sentence_array[$mid_pos->i], $mid_pos->j,0,$object_sentence_array[$start_pos->i-1]);
// 				//set the merged clause value
// // 				$new_clause = $object_sentence_array[$mid_pos->i];
// 				$new_clause['clause'] = $new_merged_clause;
// 				
// 				//set the merged offset value
// 				$new_clause['offset'] = $new_merged_offset;
// 	
// 				//set special value for adnominal clause
// 				$new_clause['adnominal_clause'] = true;
// 				//sort the objects in the new clause
// 				ksort( $new_clause, SORT_STRING );
// 	
// 				//overwrite the old conjunction clause with a new merged conjunction clause
// 				$object_sentence_array[ $start_pos->i ] = $new_clause;
// 				
// 				//remove the "removed particle" part of the sentence
// 				unset( $object_sentence_array[$start_pos->i-1] );
// 				
// 				//reset the array values for the sentence_array (since we unset one of them)
// 				$object_sentence_array = array_values( $object_sentence_array );

// 				//swap positions of verbmod and adnominal
// 				
// 				
// 			}
// 		}
// 	}
// 	return true;
// }
function apply_adnominals_to_osa(&$object_sentence_array, $adnominal_array, &$t_action_list) {
	
	//nothing to do...
	if(is_null($adnominal_array)) return false;
	
// 	print_r($adnominal_array);
	
	
	//conjunction and verb should be in their own section labelled appropriately
	//initialize variable for the offset of the last conjugation we delt with
	$last_conj_offset = 0;
	
	//deal with each conjunction object individually.
	foreach ($adnominal_array as $adnominal_object) {
		
		/* if this conjunction's verb has a "removed particle"(ie. 를), we need to MERGE
		 * the two "clauses" together.
		 */

		//DANGER: ADDING "+ 1" HERE TO MAKE SURE IT'S THE FIRST VERB WORD POS..
		$start_pos = get_sentence_pos($object_sentence_array, $adnominal_object->verb_start_pos + 1);

		 		 
		if ($adnominal_object->removed_particle) {

			//for example: $mid_pos would be the "운전" in "운전 하는"
		

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
		$mid_pos = get_sentence_pos($object_sentence_array, $adnominal_object->verb_mid_pos);
		
		if ($adnominal_object->verb_start_pos != $adnominal_object->verb_mid_pos) {
			//set all words up to verb mid pos as adnominal verb
			
			for($adnominal_pointer = $start_pos->j; $adnominal_pointer < $mid_pos->j; $adnominal_pointer++) {
				$object_sentence_array[$start_pos->i][$adnominal_pointer]->type = 'adnominal verb';
			}
		}
		
		$object_sentence_array[$mid_pos->i][$mid_pos->j]->type = 'adnominal verb';
		
				//if the position of the last verb word is the same as the last word in the clause, join to next clause
		if($mid_pos->j == (num_of_clause_words($object_sentence_array[$mid_pos->i]) - 1) && isset($object_sentence_array[$mid_pos->i + 1])) {
			
			$offset_shift = $object_sentence_array[$mid_pos->i+1]['offset'] - $object_sentence_array[$mid_pos->i]['offset'];
							
			shift_all_offsets($object_sentence_array[$mid_pos->i+1], $offset_shift);
			
			//merge the two clause object strings
			$new_merged_clause = $object_sentence_array[$mid_pos->i]['clause'] . $object_sentence_array[$mid_pos->i +1]['clause'];
			
			//use the offset from the first clause object after the merge
			$new_merged_offset = $object_sentence_array[$mid_pos->i]['offset'];
			
			//merge the clause objects
			$new_clause = array_merge($object_sentence_array[$mid_pos->i], $object_sentence_array[$mid_pos->i +1]);
			
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
		
		
				
		//is this is an action verb? join with oeseo clauses
		if ($adnominal_object->ktype == 0) {
			
			//set special value for adnominal clause (it needs to be moved)
			$object_sentence_array[$start_pos->i]['adnominal_clause'] = true;
			 
			//last two syllables are correct?
			
			if ( isset($object_sentence_array[$start_pos->i - 1]) && '에서' == mb_substr(trim($object_sentence_array[$start_pos->i - 1]['clause']), -2)) {
				
				//since these words modify an adnominal, let them become an
				//adnominal verbmod
// 				echo "setting types for: ";
// 				print_r($object_sentence_array[$start_pos->i -1]);
				
				set_clause_words_type($object_sentence_array[$start_pos->i - 1], 'adnominal verbmod');
// 				echo "set types???";
// 				print_r($object_sentence_array[$start_pos->i -1]);
					//proceed with joining
				$offset_shift = $object_sentence_array[$start_pos->i]['offset'] - $object_sentence_array[$start_pos->i-1]['offset'];
	
				//using the difference between the two clauses in $offset_shift, we can call the 
				//function shift_all_offsets() located in sentence.php.  All the word objects in the
				//clause will be shifted by the amount of $offset_shift.
								
				shift_all_offsets($object_sentence_array[$start_pos->i], $offset_shift);
				
				
				
				//merge the two clause object strings
				$new_merged_clause = $object_sentence_array[$start_pos->i-1]['clause'] . $object_sentence_array[$start_pos->i]['clause'];
				
	
				//use the offset from the first clause object after the merge
				$new_merged_offset = $object_sentence_array[$start_pos->i-1]['offset'];
				
				//merge the clause objects
				$new_clause = array_merge($object_sentence_array[$start_pos->i-1], $object_sentence_array[$start_pos->i]);
				
				
				//array splice
// 				array_splice($object_sentence_array[$mid_pos->i], $mid_pos->j,0,$object_sentence_array[$start_pos->i-1]);
				//set the merged clause value
// 				$new_clause = $object_sentence_array[$mid_pos->i];
				$new_clause['clause'] = $new_merged_clause;
				
				//set the merged offset value
				$new_clause['offset'] = $new_merged_offset;
				
				//sort the objects in the new clause
				ksort( $new_clause, SORT_STRING );
	
				//overwrite the old conjunction clause with a new merged conjunction clause
				$object_sentence_array[ $start_pos->i ] = $new_clause;
				
				//remove the "removed particle" part of the sentence
				unset( $object_sentence_array[$start_pos->i-1] );
				
				//reset the array values for the sentence_array (since we unset one of them)
				$object_sentence_array = array_values( $object_sentence_array );

				//swap positions of verbmod and adnominal
				
				
			}
		}
	}
	return true;
}




















function adnominal_values (&$object_sentence_array, $adnominal_array) {
	
	foreach($adnominal_array as $adnominal_object) {
			
		//if the adnominal verb is the last word in the clause, join it to the next one
		//if the adnominal verb ended in a particle,  을 or 는, this is necessary
		//------------------------------------------------------------
// 			echo "here is the sentence array: \n";
// 			print_r($object_sentence_array);
		$start_pos = get_sentence_pos($object_sentence_array, $adnominal_object->verb_start_pos);
		$mid_pos = get_sentence_pos($object_sentence_array, $adnominal_object->verb_mid_pos);
		
		
		if($mid_pos->i != $start_pos->i) {
			//start and mid position clause are different so set all values after start position in clause to true
			$clause_word_count = num_of_clause_words($object_sentence_array[$start_pos->i]);
			
			for ( $word_counter = $start_pos->j; $word_counter < $clause_word_count; $word_counter++ ) {
				if(is_null($object_sentence_array[$start_pos->i][$word_counter]->english)) {
				$object_sentence_array[$start_pos->i][$word_counter]->english = true;
				}
			}
			//set word_counter to zero for the next loop
			$word_counter = 0;
		} else {
			//the start pos and mid pos clause ($start_pos->j/$mid_pos->j) values are the same, so start 
			//the word counter at the start position ($start_pos) word
			$word_counter = $start_pos->j;
		}
		if($adnominal_object->verb_start_pos != $adnominal_object->verb_mid_pos ) {
			//now we MUST be in the same clause as the final verb word
			for (;$word_counter < $mid_pos->j; $word_counter++) {
				if(is_null($object_sentence_array[$mid_pos->i][$word_counter]->english)) {
					$object_sentence_array[$mid_pos->i][$word_counter]->english = true;
				}
			}
			
		}
				
				
				
		$object_sentence_array[$mid_pos->i][$mid_pos->j]->adnom = $adnominal_object;
		//$object_sentence_array[$mid_pos->i][$mid_pos->j]->english = $adnominal_object->english;
		
	}
}
	

?>