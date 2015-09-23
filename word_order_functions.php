<?php



//function to move something to right after a conjunction or the beginning of the sentence.
function front_of_sentence_or_conj(&$object_sentence_array, $clause_index, $word_index ) {
	
	for ($insertion_index = $clause_index; $insertion_index > 0; $insertion_index--) {
		if(!$object_sentence_array[$insertion_index]['conj']) {
			continue;
		} else {
			//insert this as the first word in the next clause index.
			$insertion_index++;
			break;
		}
	}
	//insertion_index is either after a conjunction or the beginning of the sentence.  put word here.
	$temp = $object_sentence_array[$clause_index][$word_index];
	unset($object_sentence_array[$clause_index][$word_index]);
	$object_sentence_array[$insertion_index] = array_merge(array( $temp ), $object_sentence_array[$insertion_index]);
	return $object_sentence_array;
}



//function to put an object from a certain clause at either the front of the sentence or
//right before the previous conjunction.
function clause_front_of_sentence_or_conj( &$object_sentence_array, $clause_index, $temp ) {
	$clause_index--;
	for ($insertion_index = $clause_index; $insertion_index > 0; $insertion_index--) {
		if($object_sentence_array[$insertion_index]['conj']) {
			//insert this as the first word in the next clause index.
// 			echo "breaking...OBJECT SENTENCE ARRAY\n";
// 			print_r($object_sentence_array);
			$insertion_index++;
			break;
		}
	}
// 	if ($object_sentence_array[$insertion_index]['conj']) echo "index $insertion_index has a conjunction";
	$new_clause = array('conj' => $temp, 'type' => 'conjunction', 'english'=> $temp->english);

	//insertion_index is either after a conjunction or the beginning of the sentence.  put word here.
	array_splice($object_sentence_array, $insertion_index, 0,array($new_clause));
	return $object_sentence_array;
	
}

//function to put an object from a certain clause at either the back of the sentence or
//right after the following conjunction.
function clause_back_of_sentence_or_conj( &$object_sentence_array, $clause_index, $temp ) {

	//move ahead to the next position to begin the search
	$clause_index++;
	
	//start searching after the orginal position ($clause_index).  Insertion index will hold the clause index
	//of the place where this will be inserted.
	
	for ($insertion_index = $clause_index; isset($object_sentence_array[$insertion_index]); $insertion_index++) {
		if($object_sentence_array[$insertion_index]['conj']) {
			//insert this as the last word in the previous index, so decrement insertion_index
			$insertion_index--;
			break;
		}
	}
// 	if ($object_sentence_array[$insertion_index]['conj']) echo "index $insertion_index has a conjunction";
	$new_clause = array('word' => $temp->word, 'type' => $temp->type, 'english'=> $temp->english);

	//insertion_index is either after a conjunction or the beginning of the sentence.  put word here.
	array_splice($object_sentence_array, $insertion_index, 0,array($new_clause));
	
	return $object_sentence_array;
	
}


function swap_with_previous_clause (&$object_sentence_array, $clause_index) {
	array_splice($object_sentence_array, $clause_index-1, 0, $object_sentence_array[$clause_index]);
}


//swap a specified word to the back of a specified clause
function bring_to_back_of_clause ( &$object_sentence_array, $clause_index, $word_index  ) {
	
	if (is_null( $word_index ) || is_null( $clause_index ) || $word_index == 0 || is_null($object_sentence_array)) return; //nothing to do
	
	//unset no particle in the previous word if this word was the end of the clause
// 	if (num_of_clause_words($object_sentence_array[$clause_index]) - 1 == $word_index) {
// 		//if we get here, the word to be moved is the last word in the clause
// 		if($word_index != 0) {
// 			/*if the word that's being moved isn't the only word in the clause, then
// 			  there's another word in the clause that needs to have it's "no particle"
// 			  unset. */
// 			  
// 			  unset($object_sentence_array[$clause_index][$word_index - 1]->no_particle);
// 			  for($i = $word_index -2;$object_sentence_array[$clause_index][$i]->postpositions && in_array(' and',$object_sentence_array[$clause_index][$i]->postpositions);$i--) {
// 				  unset($object_sentence_array[$clause_index][$i]->no_particle);
// 			  }
// 		}
// 	}
	
	
	//perform object insertion
	$temp = $object_sentence_array[$clause_index][$word_index];
	unset($object_sentence_array[$clause_index][$word_index]);
	
	$object_sentence_array[$clause_index] = array_merge($object_sentence_array[$clause_index], array( $temp ));
	
	//************TESTING***************
	print_r($object_sentence_array[$clause_index]);
	
// 	return $object_sentence_array;
}


//swap a specified word to the front of it's clause
function bring_to_front_of_clause ( &$object_sentence_array, $clause_index, $word_index  ) {
	
	if (is_null( $word_index ) || is_null( $clause_index ) || $word_index == 0 || is_null($object_sentence_array)) return; //nothing to do
	
	//unset no particle in the previous word if this word was the end of the clause
	if (num_of_clause_words($object_sentence_array[$clause_index]) - 1 == $word_index) {
		//if we get here, the word to be moved is the last word in the clause
		if($word_index != 0) {
			/*if the word that's being moved isn't the only word in the clause, then
			  there's another word in the clause that needs to have it's "no particle"
			  unset. */
			  
			  unset($object_sentence_array[$clause_index][$word_index - 1]->no_particle);
			  for($i = $word_index -2;$object_sentence_array[$clause_index][$i]->postpositions && in_array(' and',$object_sentence_array[$clause_index][$i]->postpositions);$i--) {
				  unset($object_sentence_array[$clause_index][$i]->no_particle);
			  }
		}
	}
	
	
	
	//perform object insertion
	$temp = $object_sentence_array[$clause_index][$word_index];
	unset($object_sentence_array[$clause_index][$word_index]);
	
	$object_sentence_array[$clause_index] = array_merge(array( $temp ), $object_sentence_array[$clause_index]);
	return $object_sentence_array;
}

//make a function to take all words from the beginning of a clause to a 
//certain word to infront of the object, (아주 많이 ~~~)

//swap a specified word to the front of a specified OBJECT CLAUSE (for 많이)
function bring_to_front_of_object ( &$object_sentence_array, $clause_index, $word_index  ) {
	
	//make sure all the values are as expected and check that there is something to do ( $clause_index != 0 )	
	if (is_null( $word_index ) || is_null( $clause_index ) || $clause_index == 0 || is_null($object_sentence_array)) return; //nothing to do
	
	$clause_words_before_unset = num_of_clause_words($object_sentence_array[$clause_index]);
	
	//unset no particle in the previous word if this word was the end of the clause
	if ($clause_words_before_unset - 1 == $word_index) {
		//if we get here, the word to be moved is the last word in the clause
		if($word_index != 0) {
			/*if the word that's being moved isn't the only word in the clause, then
			  there's another word in the clause that needs to have it's "no particle"
			  unset. */
			  //$object_sentence_array[$clause_index][$word_index - 1]->message = "unset particle in this word";
			  unset($object_sentence_array[$clause_index][$word_index - 1]->no_particle);
		}
	}
	if($object_sentence_array[$clause_index-1]['type'] == 'object' || $object_sentence_array[$clause_index-1]['type'] == 'also') {
		//perform object insertion
		$temp = $object_sentence_array[$clause_index][$word_index];
		unset($object_sentence_array[$clause_index][$word_index]);
		
		//shift back the words in the clause after the one that has been removed.
		for($i = $word_index; $i<($clause_words_before_unset - 1); $i++) $object_sentence_array[$clause_index][$i] = $object_sentence_array[$clause_index][$i + 1];
		
		//since we've just unset the word that is being moved, the clause will now be 
		//EMPTY (num_of_clause_words == 0) if the moved word was the only one in the clause
		if( $word_index == 0 && num_of_clause_words( $object_sentence_array[$clause_index] ) == 0 ) {
			//unset the whole clause
			unset($object_sentence_array[$clause_index]);
			//reset the array
			$object_sentence_array = array_values($object_sentence_array);
		}
		$object_sentence_array[$clause_index-1] = array_merge(array( $temp ), $object_sentence_array[$clause_index-1]);
		
	}
	return $object_sentence_array;
}
function move_verbs_and_conjunctions (&$object_sentence_array) {
	
	//move verbs and conjunctions within the sentence.
	$osa_size = sizeof($object_sentence_array);

		for ($key=0;$key<$osa_size;$key++) {
		$clause = $object_sentence_array[$key];
		
		//type becomes just verb.  This clause is moving independant of the conjunction
		$clause['type'] = 'verb';
		
		if (isset($clause['mverb']->order_action)) {
			//UNLIKE CONJUNCTIONS, MVERBS MOVE WITH ADJECTIVES, SO the whole clause moves.
// 			echo 'found an order action';
			switch ($clause['mverb']->order_action) {
				case 0:
// 				echo 'switch verb with object';
					if($object_sentence_array[$key - 1]['type'] == 'object') {
						//insert the verb before the object clause..
						unset($object_sentence_array[$key]['mverb']->order_action);
						unset($object_sentence_array[$key]);
						$go_back = 1;
						array_splice($object_sentence_array, $key - $go_back, 0, array($clause));
					}
				break;
				case 1:
// 				echo "switch with verbmod";
					if($object_sentence_array[$key - 1]['type'] == "verbmod") {
						unset($object_sentence_array[$key]['mverb']->order_action);
						//swap the clauses..
						$go_back = 1;
						unset($object_sentence_array[$key]);
						array_splice($object_sentence_array, $key - $go_back, 0, array($clause));
					}
				break;
				case 2:
// 				echo "switch with oe or object or ro";
					if($object_sentence_array[$key -1]['type'] == "verbmod" || $object_sentence_array[$key - 1]['type'] == "object" || $object_sentence_array[$key - 1]['type'] == "direction" ) {
						$go_back = 1;
						unset($object_sentence_array[$key]['mverb']->order_action);
						if($object_sentence_array[$key - 2]['type'] != $object_sentence_array[$key - 1]['type'] && $object_sentence_array[$key -2]['type'] == "verbmod" || $object_sentence_array[$key - 2]['type'] == "object" || $object_sentence_array[$key - 2]['type'] == "direction" ) $go_back = 2;
						//bring it back one or two spaces.
						unset ($object_sentence_array[$key]);
						array_splice($object_sentence_array, ($key - $go_back), 0, array($clause));
					}
				break;
				case 3:
// 				echo "switch with object or subject";
					if($object_sentence_array[$key -1]['type'] == "subject" || $object_sentence_array[$key - 1]['type'] == "object") {
						unset($object_sentence_array[$key]['mverb']->order_action);
						$go_back = 1;
						if( ($object_sentence_array[$key -2]['type'] == "subject" || $object_sentence_array[$key - 2]['type'] == "object") && $object_sentence_array[$key - 2]['type'] != $object_sentence_array[$key - 1]['type']) $go_back = 2;
						//bring it back one or two spaces.
						echo "go back = $go_back";
						unset ($object_sentence_array[$key]);
						array_splice($object_sentence_array, ($key - $go_back), 0, array($clause));
					}
				break;
				case 4:
// 				echo "verb is translated together with object and is not moved";
				break;
				default:
					echo "no order action found";
				break;
			}
			unset($object_sentence_array[$key-$go_back]['mverb']->order_action);
			//remember how far this clause has been brought back.
			$object_sentence_array[$key-$go_back]['mverb']->gone_back = $go_back;
// 			print_english_sentence($object_sentence_array);
		}
	}
	//the size could have changed if there were verb conjunctions
	$osa_size = sizeof($object_sentence_array);
	
	for ($key=0;$key<$osa_size;$key++) {
// 		if ($object_sentence_array[$key]['conj']) echo "index $key has a conjunction";
		$clause = $object_sentence_array[$key];
		if($object_sentence_array[$key]['mverb']->gone_back) $gone_back = $object_sentence_array[$key]['mverb']->gone_back;
		else $gone_back = 0;
		if (isset($clause['conj']->order_action)) {
			switch ($clause['conj']->order_action) {
				case 0:
					//in place (고 or 서 conjunctions, and more?)
					unset($object_sentence_array[$key]['conj']->order_action);
					
					$new_clause = array('conj' => $clause['conj'], 'type' => 'conjunction', 'english'=> $clause['conj']->english);
					
					//put word after the clause.
					array_splice($object_sentence_array, $key + $gone_back +1, 0,array($new_clause));
					unset($object_sentence_array[$key]['conj']);
					$key++;
					$osa_size++;
					
				break;
				case 1:
				echo "clause_front_of_sentence_or_conj";
					//front of sentence or prev clause.
					clause_front_of_sentence_or_conj( $object_sentence_array, $key, $clause['conj'] );
					//not adding "$gone_back" here because if the verb moved then there was no conjunction
					//in front of it to begin with
					
					
					//the conjunction has been added as a new clause, so the for-loop pointer must be 
					//advanced.  As well, the array size has increased, so the osa_size is increased
					//as well.
					$key++;
					$osa_size++;
					//since the conjunction has been moved, remove it from the current clause
					unset($object_sentence_array[$key]['conj']);
// 						echo "here is clause $key\n";
// 						print_r($object_sentence_array[$key]);
				break;
				case 2:
				echo "switch with oe or object + verb";
				unset($object_sentence_array[$key]['conj']->order_action);
				$new_clause = array('conj' => $clause['conj'], 'type' => 'conjunction', 'english'=> $clause['conj']->english);
				
					if($object_sentence_array[$key+$gone_back -1]['type'] == "verbmod" || $object_sentence_array[$key +$gone_back- 1]['type'] == "object"||$object_sentence_array[$key +$gone_back- 1]['type'] == "verb") {
						$go_back = 1;
						if($object_sentence_array[$key +$gone_back-2]['type'] == "verbmod" || $object_sentence_array[$key +$gone_back- 2]['type'] == "object" || $object_sentence_array[$key +$gone_back- 2]['type'] == "verb") $go_back = 2;
						if($go_back == 2 && $object_sentence_array[$key +$gone_back- 3]['type'] == "verb") $go_back = 3;
						//bring the conjunction back one or two spaces.
						array_splice($object_sentence_array, $key +$gone_back - $go_back, 0,array($new_clause));

					} else {
						//insert at the end of the current clause
						array_splice($object_sentence_array, $key +$gone_back+ 1, 0,array($new_clause));
					}
					unset($object_sentence_array[$key]['conj']);
					$key++;
					$osa_size++;										
				break;
				default:
				break;
			}
		}
	}

// 	echo "size is $osa_size";


}
function swap_verbmod_object_pattern (&$object_sentence_array) {
	foreach($object_sentence_array as $index => $clause) {
		if($clause['type'] != 'verbmod')continue;
		if($object_sentence_array[$index + 1] && $object_sentence_array[$index + 1]['type'] == 'object') {
			unset($object_sentence_array[$index]);
			array_splice($object_sentence_array, $index + 1, 0, array($clause));
		}
	}
}


//what's really going on here??
function apply_movement_rules_to_sentence ( &$object_sentence_array, $t_action_list ) {
	$rules = $t_action_list->get_rules();
// 	echo "in apply move rules";
// 	print_r($rules);
	foreach ($rules as $rule_obj) {
// 	 		echo "function name:". $rule_obj->rule."(o_s_a, ".$rule_obj->clause_index. ", ".$rule_obj->search_code . ")";
		$function = $rule_obj->rule;
		
		$function($object_sentence_array, $rule_obj->clause_index, $rule_obj->search_code);
	}
}