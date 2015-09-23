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

	function add_rule($rule, $search_code, $clause_index) {
		//add a rule to the rule list		
		echo "add rule! $rule, $search_code, $clause_index <br>";	
		$this->rulelist[] = new Rule($rule, $search_code, $clause_index);	
	}
	function get_rules() {
		return $this->rulelist;
	}
}


//swap a specified word to the front of a specified clause
function bring_to_front_of_clause ( $object_sentence_array, $clause_index, $word_index  ) {
	
	if (is_null( $word_index ) || is_null( $clause_index ) || $word_index == 0 || is_null($object_sentence_array)) return; //nothing to do
	
	//perform object insertion
	$temp = $object_sentence_array[$clause_index][$word_index];
	unset($object_sentence_array[$clause_index][$word_index]);
	
	$object_sentence_array[$clause_index] = array_merge(array( $temp ), $object_sentence_array[$clause_index]);
	return $object_sentence_array;
}
	

//boolean function to return whether or not the clause contains multiple nouns
function clause_contains_multiple_nouns ( $clause ) {
	if (is_null($clause))return false;  //no clause index specified.
	
	// if a word (not the last one) in the clause contains 와 or 과 as an ending particle, return true.
	$words = preg_split('/ /', $clause, -1, PREG_SPLIT_NO_EMPTY);
	foreach ( $words as $word ) {
		
		//looking at the last syllable of each word
		$last_syllable = mb_substr($word, -1);
		
		//there could be other ways to determine multiple nouns...
		if( $last_syllable == "과" || $last_syllable == "와" || $last_syllable == "들" )return true;
	}
	return false;
}

function swap_with_previous_clause ($object_sentence_array, $clause_index) {
	array_splice($object_sentence_array, $clause_index-1, 0, $object_sentence_array[$clause_index]);
}
function translate_as_verb ($object_sentence_array) {
	//to do
	
}
function get_word_objects_by_search_code( $search_code, $sentence_array ){
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
		 
		if ($conjunction_object->m_verb->removed_particle) {
			
			/* $sp is an object that holds the position for the start of the verb.
			 * NEW: if there's a double verb, with or without a removed particle,
			 * it should be moved as a clause to join with the conjunction.
			 *
			 * See "get_sentence_pos" in sentence.php for an explanation of this
			 * function.
			 */
			$sp = get_sentence_pos($sentence_array, $conjunction_object->m_verb->verb_start_pos);
				
				echo "Move and unset: " . $sp->i . " and " . $sp->j . "\n" ;
				
				//for example: $second_verb_word would be the "운전" in "운전 하다"
				$second_verb_word = $sentence_array[$sp->i][$sp->j];
				//since we're going to use the second verb word as the start of the new clause, 
				//we need to get a new clause offset by getting the total of the "second_verb_word"
				//clause offset + word offset
				$new_clause_offset = $sentence_array[$sp->i][$sp->j]->offset + $sentence_array[$sp->i]["offset"];
				//the offset from this new word in the clause
				$additional_offset = mb_strlen($second_verb_word->word);
				//this new word
				$second_verb_word->offset = 0;
				
				$new_clause = array_merge(array($second_verb_word), $sentence_array[$sp->i +1]);
			
				//when we are constructing the new clause, need to reset the offset and the clause
				$new_clause["offset"] = $new_clause_offset;
				$new_clause["clause"] = $second_verb_word->word . $new_clause["clause"];
				
				shift_offsets($new_clause, $additional_offset, 0);
				
				//new clause becomes part of the sentence
				$sentence_array[$sp->i + 1] = $new_clause;
				
				//remove the original part of the sentence
			if ($sp->j == 0) {
				//the first verb word is in its own clause, unset that clause.
				unset($sentence_array[$sp->i]);
				$sentence_array=array_values($sentence_array);
			}else {
				//$sp->j != 0 unset the word only. ( it will be the last word in its clause, since it has a particle )
				$sentence_array[$sp->i]["clause"] = mb_substr($sentence_array[$sp->i]["clause"], 0 , $sentence_array[$sp->i][$sp->j-1]->offset + mb_strlen($sentence_array[$sp->i][$sp->j-1]->word) );
				unset($sentence_array[$sp->i]["type"]);
				unset($sentence_array[$sp->i][$sp->j]);
			}
		}	
			//move words trailing after the conjunction to their own clause
			//------------------------------------------------------------
// 			echo "here is the sentence array: \n";
// 			print_r($sentence_array);
			
			//get the location of the last word of the conjunction
			$cp = get_sentence_pos($sentence_array, $conjunction_object->conj_pos + $conjunction_object->conj_length-1);
			//rename the "type" of the old clause now that the sentence has been found.
			$new_clause = array_slice($sentence_array[$cp->i], $cp->j + 1);
// 			echo "here's the array slice!!";
// 			print_r ($new_clause);
			$new_clause["type"] = $sentence_array[$cp->i]["type"]; 
						
			$sentence_array[$cp->i]["type"] = "verb + conjunction";
			$sentence_array[$cp->i]["conj"] = $conjunction_object;
			//move from the next word after the conjunction to the end of the clause into it's own clause

			//nullify these elements in the old array
			for($k= 1; isset($sentence_array[$cp->i][$cp->j + $k]->word); $k++) unset($sentence_array[$cp->i][$cp->j + $k]);

			$new_clause["offset"] = mb_strlen($sentence_array[$cp->i][$cp->j]->word) + $sentence_array[$cp->i][$cp->j]->offset + $sentence_array[$cp->i]["offset"];
			$new_clause["clause"] = mb_substr($sentence_array[$cp->i]["clause"], $new_clause["offset"] - $sentence_array[$cp->i]["offset"]);
			
			shift_all_offsets($new_clause, $sentence_array[$cp->i]["offset"] - $new_clause["offset"] );
			
			$sentence_array[$cp->i]["clause"] = mb_substr($sentence_array[$cp->i]["clause"], 0, $new_clause["offset"] - $sentence_array[$cp->i]["offset"]);
			
			
			
			if($new_clause["clause"]) {
				//the "new clause" is being inserted into its own spot 
				array_splice($sentence_array, $cp->i+1,0,array($new_clause)); 
			}
	}
	return true;
}
	

?>