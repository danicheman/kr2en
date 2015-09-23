<?php

//rule object
class Rule {
	function Rule($rule, $search_code, $clause_index) {
		$this->rule = $rule;
		$this->search_code = $search_code;
		$this->clause_index = $clause_index;
	}
}

//a list(array) of rules
class Translation_action_list {
	function Translation_action_list() {
		$this->rulelist = array();
	}

	function add_rule( $rule, $clause_index, $search_code ) {
		//add a rule to the rule list		
		if(gettype($rule) != "string") {
			die("ERROR! \$rule must be a string");
		} else {
			$this->rulelist[] = new Rule($rule, $search_code, $clause_index);	
		}
	}
	function get_rules() {
		return $this->rulelist;
	}
}
/*
  This function is currently just for "많이" to determine whether or not
  we will use the english "much" or "many".  If 많이 preceeds a non-count 
  noun or a verb, it will be use in it's non-count form "much", otherwise
  it will be "many". Since it's an adjective, it should never be at the end
  of a clause.
*/

//osa is $object_sentence_array

function count_or_non ($osa, $clause_index, $word_index) {
	if($osa[$clause_index -1]['type'] != 'object' && $osa[$clause_index]['mverb']) {
		if ($osa[$clause_index]['mverb']->ktype == 0)return 'often';
		else return 'very';
	} elseif (!$osa[$clause_index]['mverb']) {
		//maybe the adjective is already in front of a noun
		
		//ro means "reverse offset"
		$ro = 0;
	} else $ro = 1; 
	
	
	$cw = no_of_clause_words($osa[$clause_index - 1]);
	
	//get the last word of the previous object clause
	if($osa[$clause_index - $ro][$cw - $ro]->iscount) return 'many';
	else return 'much';
	
}

//set all words in a clause to a given type
function set_clause_words_type (&$clause, $new_type) {
	$cw = no_of_clause_words($clause);
	
	for($i = 0; $i < $cw; $i++) $clause[$i]->type = $new_type;
}

/* This function searches backwards from a given clause, until
   it reaches either a conjunction or the beginning of the sentence.
   On the way, whenever it finds an 에 particle with a "place" type
   word before it, the preposition array's "at(s)" are changed to
   "to(s)"
*/

function change_at_to_to( &$object_sentence_array, $clause_index ) {
	//we are given the position of the verb, need to search backwards looking for particles and conjunctions
	//start by decreasing $clause_index
	$clause_index--;
	
	
	while( $clause_index > -1 && is_null( $object_sentence_array[$clause_index]['conj']) ) {
		
		
		//last particle of clause must be 에, check it
		$trimmed_clause = trim( $object_sentence_array[$clause_index]['clause'] );
		$last_particle = mb_substr($trimmed_clause, -1);
		if($last_particle == "에") {
			$clause_word_count = no_of_clause_words( $object_sentence_array[$clause_index] );
			for ($i = $clause_word_count - 1; $i> -1; $i--) {
				//need to check keywords of the nouns with 에
				if(  $object_sentence_array[$clause_index][$i]->prepositions  && $object_sentence_array[$clause_index][$i]->keywords && in_array('place',$object_sentence_array[$clause_index][$i]->keywords) && $object_sentence_array[$clause_index][$i]->with_oe ) {
					$change = array_search('at', $object_sentence_array[$clause_index][$i]->prepositions);
					$object_sentence_array[$clause_index][$i]->prepositions[$change] = 'to';
				}
			}
		}
		$clause_index--;
	}
}



	
//function to return whether or not the clause contains multiple nouns (or just returns an error..)
function clause_contains_multiple_nouns ( $clause ) {
	if (is_null($clause))return 0;  //no clause index specified.
	
	// if a word (not the last one) in the clause contains 와 or 과 as an ending particle, return true.
	$words = preg_split('/ /', $clause, -1, PREG_SPLIT_NO_EMPTY);
	foreach ( $words as $word ) {
		
		//looking at the last syllable of each word
		$last_syllable = mb_substr($word, -1);
		
		//there could be other ways to determine multiple nouns...
		if( $last_syllable == "과" || $last_syllable == "와" || $last_syllable == "들" )return 1;
	}
	return 2;
}


function translate_as_verb ($object_sentence_array) {
	//to do
	
}

function apply_conjunctions_to_object_sentence ($conjunction_array, &$sentence_array ) {
	
	//nothing to do...
	if(is_null($conjunction_array)) return false;
	
	//conjunction and verb should be in their own section labelled appropriately
	//initialize variable for the offset of the last conjugation we delt with
	$last_conj_offset = 0;
	
	//deal with each conjunction object individually.
	foreach ($conjunction_array as $conjunction_object) {
		
		/* if this conjunction's verb has a "removed particle"(ie. 를), we need to MERGE
		 * the two "clauses" together, for example:
		 *
		 * imagine the following are the "clause" arrays from an example sentence
		 * and 숙제를's definition contains a removed "를" particle; here's what
		 * happens:
		 *
		 *  BEFORE:
		 *
		 * [0]->너는 [1]->숙제를 [2]->했지만 나는 [3]->잘 못 했어요
		 *
		 *  AFTER:
		 *
		 * [0]->너는 [1]->숙제를 했지만 [2]->나는 [3]->잘 못 했어요
		 *
		 * NOTE: the two-word verb is together with the conjunction
		 * in it's own clause.
		 */
		 
	 	/* See "get_sentence_pos" in sentence.php for an explanation of this
		 * function.
		 */
		 $sp = get_sentence_pos($sentence_array, $conjunction_object->m_verb->verb_start_pos);
		 
		if ($conjunction_object->m_verb->removed_particle) {
			/* $sp is an object that holds the position for the start of the verb.
			 * NEW: if there's a double verb, with or without a removed particle,
			 * it should be moved as a clause to join with the conjunction.
			 */

			
				
// 				echo "Move and unset: " . $sp->i . " and " . $sp->j . "\n" ;
			
			//for example: $second_verb_word would be the "운전" in "운전 하다"
			$second_verb_word = $sentence_array[$sp->i][$sp->j];
			
			
			//since we're going to use the second verb word's clause to merge with the conjunction
			//clause, we need to get the difference between the two clause offsets 
			$offset_shift = $sentence_array[$sp->i+1]['offset'] - $sentence_array[$sp->i]['offset'];

			//using the difference between the two clauses in $offset_shift, we can call the 
			//function shift_all_offsets() located in sentence.php.  All the word objects in the
			//clause will be shifted by the amount of $offset_shift.
							
			shift_all_offsets($sentence_array[$sp->i+1], $offset_shift);
			
			//merge the two clause object strings
			$new_merged_clause = $sentence_array[$sp->i]['clause'] . $sentence_array[$sp->i +1]['clause'];
			
			//use the offset from the first clause object after the merge
			$new_merged_offset = $sentence_array[$sp->i]['offset'];
			
			//merge the clause objects
			$new_clause = array_merge($sentence_array[$sp->i], $sentence_array[$sp->i +1]);
			
			//set the merged clause value
			$new_clause['clause'] = $new_merged_clause;
			
			//set the merged offset value
			$new_clause['offset'] = $new_merged_offset;
			
			//sort the objects in the new clause
			ksort($new_clause, SORT_STRING);
			
			
			
			//overwrite the old conjunction clause with a new merged conjunction clause
			$sentence_array[$sp->i + 1] = $new_clause;
			
			//remove the "removed particle" part of the sentence
			unset($sentence_array[$sp->i]);
			
			//reset the array values for the sentence_array (since we unset one of them)
			$sentence_array=array_values($sentence_array);

		}	
		
		//move words trailing after the conjunction to their own clause
		//------------------------------------------------------------
// 			echo "here is the sentence array: \n";
// 			print_r($sentence_array);
		

		//get the location of the last word of the conjunction
		$cp = get_sentence_pos($sentence_array, $conjunction_object->conj_pos + $conjunction_object->conj_length-1);

		//use "array_slice" to remove all the elements from after the end of 
		//the conjunction to the end of the clause
		$new_clause = array_slice($sentence_array[$cp->i], $cp->j + 1);
		
		//retain the original type for this clause as we separate it from the conjunction
		$new_clause["type"] = $sentence_array[$cp->i]["type"]; 
					
		//the type of the clause with the conjunction becomes "verb + conjunction"
		$sentence_array[$cp->i]["type"] = "verb + conjunction";
		
		//the conjunction object is added into the clause
		$sentence_array[$cp->i]["conj"] = $conjunction_object;
		$sentence_array[$cp->i]["mverb"] = $conjunction_object->m_verb;
		$sentence_array[$cp->i]["conj"]->m_verb = NULL;
		
		

		//for all the elements we "array_slice(d)" in $new_clause, they must
		//now be set to null after the final conjunction word.
		for($k= 1; isset($sentence_array[$cp->i][$cp->j + $k]->word); $k++) unset($sentence_array[$cp->i][$cp->j + $k]);
		
		//get the offset for the new clause object
		$new_clause["offset"] = mb_strlen($sentence_array[$cp->i][$cp->j]->word) + $sentence_array[$cp->i][$cp->j]->offset + $sentence_array[$cp->i]["offset"];
		
		//get the clause for the new clause object
		$new_clause["clause"] = mb_substr($sentence_array[$cp->i]["clause"], $new_clause["offset"] - $sentence_array[$cp->i]["offset"]);
		
		//shift the old offsets to the new offsets
		shift_all_offsets($new_clause, $sentence_array[$cp->i]["offset"] - $new_clause["offset"] );
		
		//remove the words from the conjunction clause that were moved to the next
		//clause
		$sentence_array[$cp->i]["clause"] = mb_substr($sentence_array[$cp->i]["clause"], 0, $new_clause["offset"] - $sentence_array[$cp->i]["offset"]);
		
		if($new_clause["clause"]) {
			ksort($new_clause, SORT_STRING);
			//the "new clause" is being inserted into its own spot 
			array_splice($sentence_array, $cp->i+1,0,array($new_clause)); 
		}
	}
	return true;
}
	
function sort_prepositions (&$object_sentence_array) {
	//try to move particles to the first word in the complex noun
	
	//move forward through the words until we hit the end of the sentence or a preposition,
	//prepositions cannot move over a clause...
	foreach($object_sentence_array as $clause) {
		
		for($i =0;$clause[$i];$i++) {
			if ($clause[$i]->prepositions) {
				//found a preposition, try to move it back
				for ( $j = $i; $j >-1; $j-- ) {
					/*look for terminate conditions:
					  1 last particle is 와 or 과
					  2 last particle is 하고	
					  3 punctuation
					  4 adnominal verb...shouldn't move..
					  (beginning of clause is already covered)
					  */	
// 					  echo "j is $j type is" .$clause[$j]->type . "<br>";			
					  if($clause[$j]->type == 'punctuation' || $clause[$j]->particle_no_pass || ($clause[$j]->type=='adnominal verb')) {
						  $j++;
						  break;
					  }
					  $last_particle = mb_substr($clause[$j]->word, -1);
					  $last_two = mb_substr($clause[$j]->word, -2);
					
					if ($last_particle == '와' || $last_particle == '과' || $last_two == '하고') {
						$j++;
						break;
					}
					if ($last_particle == '로' || in_array('proper noun', $clause[$j]->keywords )||in_array('name', $clause[$j]->keywords ) ) {
						//cancel the "the" particle
						$index_of_the = array_search('the',$clause[$i]->prepositions);
						unset($clause[$i]->prepositions[$index_of_the]);
						break;
					}
				}
				if($j == -1) $j++;
// 				echo "j is $j i is $i<br>";
				if(isset($j) && $i != $j) {
					//move and unset the particle
					if(isset($clause[$j]->prepositions)) {
						$clause[$j]->prepositions = array_merge($clause[$j]->prepositions, $clause[$i]->prepositions);
					} else {
						$clause[$j]->prepositions = $clause[$i]->prepositions;
					}
					unset($clause[$i]->prepositions);
					unset($j);
				}
			}
		}
	}
}
?>