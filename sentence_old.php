<?php

/* class "sentences and words"
	s - sentence
	ds - decomposed sentence
	ss - shifted sentence
	w - words from sentence
	dw - decomposed words
	sw - shifted words
*/

class sentence_and_words {
	function __construct($s) {
		
		//change multiple spaces to just one space.
		$s = mb_ereg_replace( "\s\s+", " ", $s);
		//create decomposed sentence
		$ds = $s;
		utf_normalizer::nfd(&$ds);
		
		//create shifted sentence
		$ss = jamo_to_co_jamo($ds);
		
		//break sentence into an array of words
		$w = preg_split('/ /', $s, -1, PREG_SPLIT_NO_EMPTY);
		$dw = preg_split('/ /', $ds, -1, PREG_SPLIT_NO_EMPTY);
		$sw = preg_split('/ /', $ss, -1, PREG_SPLIT_NO_EMPTY);

		$this->sentence = $s;
		$this->decomp_sentence = $ds;
		$this->shifted_sentence = $ss;
		$this->words = $w;
		$this->decomp_words = $dw;
		$this->shifted_words = $sw;
	}
}

class Word {
	function __construct($w, $o) {
		$this->word = $w;
		$this->offset = $o;
	}
}
	
class Sentence_pos {
	function Sentence_pos($x,$y) {
		$this->i = $x;
		$this->j = $y;
	}
}

function test_object_sentence($s, $osa) {
	foreach ($osa as $key => $clause) {
		echo "CLAUSE $key: ". mb_substr($s, $clause["offset"]) . "<br>\n";
		echo $clause["clause"]. "<br>\n";
		foreach ($clause as $ckey => $word) {
			if(is_numeric($ckey)) {
				echo "WORD $ckey: ". mb_substr($s, $word->offset + $clause["offset"]) . "<br>\n";
				echo $word->word. "<br>\n";
			}
		}
	}
}
function no_of_clause_words($clause_array) {
	if(!$clause_array)return 0;
	if(gettype($clause_array) != "array") {
		echo "clause array is type " . gettype($clause_array);
		return 0;
	}
	$to_return = 0;
	foreach($clause_array as $key => $value) if(is_numeric($key)) $to_return++;
	return $to_return;
}
function num_of_clause_words($clause_array) {
	if(!$clause_array)return 0;
	
	$to_return = 0;
	foreach($clause_array as $key => $value) if(is_numeric($key)) $to_return++;
	return $to_return;
}
//currently unused.
function get_clause_words($clause_obj) {
	//return only the Korean words in the clause
	foreach($clause_obj as $key =>$value) {
		if(!is_numeric($key)) continue;
		elseif($value->type == "punctuation") continue;//could add other unwanted exceptions here.
		else $to_return[$key] = $value;
	}	
	return $to_return;
}

function get_clause_pos ($word_array, $pos) {
	for($j = 0; is_numeric($word_array[$j+1]->offset) && $word_array[$j+1]->offset + $word_array["offset"] <= $pos; $j++ ) {if ($j == 100)die("j is crazy");}
	return $j;
}	

//return the clause and word indexes for a position in the sentence
function get_sentence_pos ($sentence_array, $pos) {
	for($i = 0; isset($sentence_array[$i + 1]["offset"]) && $sentence_array[$i + 1]["offset"] <= $pos; $i++ ) {if ($i == 100)die("\$i is out of control");}
	for($j = 0; is_numeric($sentence_array[$i][$j+1]->offset) && $sentence_array[$i][$j+1]->offset + $sentence_array[$i]["offset"] <= $pos; $j++ ) {if ($j == 100)die("j is crazy");}
	return new Sentence_pos($i, $j);
}

function shift_all_offsets (&$clause, $new_offset) {
	//shift all word offsets in a clause
// 	echo "*********************************<br />";
// 	print_r($new_offset);
// 	echo "*********************************<br />";
// 	print_r($clause);
	foreach($clause as $key => $word) {
		if (isset($word->offset)) $word->offset += $new_offset;
	}
}


function create_object_sentence	( $sentence, $particle_array, $conjunction_array, $my_m_verb ) {
	
	//modified the search to check for escaped quotations (matching the backslash together with the quotation)
	$preg_pattern = '/.*?([\p{Pd}\p{Ps}\p{Pe}\p{Pi}\p{Pf}\p{Pc}\`\"\'·;:,><]\s*|([^\s\p{Pd}\p{Ps}\p{Pe}\p{Pi}\p{Pf}\p{Pc}\`\"\'·;:,><]+\s*))|(\S+$)/u';
	$ca_ptr=0;	

	//numbering for particle spans
	
	if($particle_array) {
		for($i = 0; list($start_pos, $length_and_type) = each($particle_array); $i++) { 
			unset($type);
			//store type and particle's span in array
			switch ($length_and_type[1]) {
				case 0:
					$type = "topic";
				break;
				case 1:
					$type = "subject";
				break;
				case 2:
					$type = "object";
				break;
				case 3:
					$type = "verbmod";
				break;
				case 4:
					$type = "direction";
				break;
				case 5:
					$type = "also";
				break;
			}
			
			
			$clause = mb_substr($sentence, $start_pos, $length_and_type[0]);
			unset($regs);
			
			preg_match_all($preg_pattern, $clause, $regs);
			
			//need the following loop to remove unnecessary values
			//could we modify preg_match_all?
			$last_offset=0;			

			//non value elements have been removed, reorganize keys
			$regs[0] = array_values($regs[0]);
			
			//set positions after filtering non-values from array and reseting the index of $regs[0]
			unset($pos);
			
			foreach($regs[0] as $key => $reg_values) {
				$pos[$key] = mb_strpos($clause, $reg_values, $last_offset);
				$last_offset = $pos[$key];
			}
			
			foreach($regs[0] as $key => $word) {
				$sentence_array[$i][$key] = new Word($word, $pos[$key]);
				
				switch(true) {
					case ( isset($sentence_array[$i][$key]->type) ) :
						//if the word has (으)로 at the end, assign it
 						$to_search = trim($word);
// 						if (preg_match("/으?로$/u", $to_search)) {
// 							$sentence_array[$i][$key]->with_ro = true;
// 						}
					break;
					
					case ( preg_match("/^\s*\d+\s*$/u", $word ) ) :
						$sentence_array[$i][$key]->type = "number";
						$sentence_array[$i][$key]->english = $word;
					break;
					case ( preg_match("/^\s*[\p{P}\`]\s*$/u", $word ) ) :
						if(mb_strlen($word) < 3) {
							$sentence_array[$i][$key]->type = "punctuation";
						} elseif (preg_match("/@/u", $word ) ) {
							$sentence_array[$i][$key]->type = "E-mail address";
						} elseif (preg_match("/\d\.\d/u", $word ) ) {
							$sentence_array[$i][$key]->type = "Decimal Number";
						} else {
							$sentence_array[$i][$key]->type = "Website address";
						}
						$sentence_array[$i][$key]->english = $word;
					break;

					case ( is_null($pos[$key+1] ) ) :
						$to_search = trim($word);
						//assign with oe to a word
						if (preg_match("/((에는?)|엔)$/u", $to_search)) $sentence_array[$i][$key]->with_oe = true;
						if (preg_match("/(에서는?)$/u", $to_search)) $sentence_array[$i][$key]->with_oeseo = true;
	 					$sentence_array[$i][$key]->type = "noun + particle";
						//search without the particle
					break;
					case (isset($pos[$key+1])) :
						$sentence_array[$i][$key]->no_particle = true;
						
					break;
					default:
						$to_search = trim($word);
					break;
				}
			}

			//$sentence_array[$i]->$i = $regs[0];
			$sentence_array[$i]["type"] = $type;
			$sentence_array[$i]["clause"] = $clause;
			$sentence_array[$i]["offset"] = $start_pos;
		}
	}
	/* Final words / Final verb
	 * -------------------------------------------------------------------
	 * i is pointing to the next position in the array this is okay.  Final 
	 * verb words and verbs will go in their own clause(s).
	 */
	
	//get the last position that has been stored in the array.  
	if($i) {
		$last_stored_position = $sentence_array[$i-1]["offset"] + mb_strlen($clause);
	} else {
		$last_stored_position = 0;
		$i = 0;
	}
	//print optional final sentence words in their own clause, or print to the end of the sentence?
	if (!isset($my_m_verb->verb_start_pos)) $clause = mb_substr($sentence, $last_stored_position);
	else $clause = mb_substr($sentence, $last_stored_position, $my_m_verb->verb_start_pos - $last_stored_position);
	//make the clause part of the last array
	if ($clause == " ") {
		$sentence_array[$i-1]["clause"] .= " ";
		//need to do other things to other values?
		unset($clause);
	} elseif ($clause != "") {
		unset($regs);
		unset($type);
		preg_match_all($preg_pattern, $clause, $regs);
		
		//need the following loop to remove unnecessary values
		//could we modify preg_match_all?
		$last_offset=0;			
	
		//set positions after filtering non-values from array and reseting the index of $regs[0]
		unset($pos);
// 		echo "final sentence regs match from preg";
		foreach($regs[0] as $key => $reg_values) {
			$pos[$key] = mb_strpos($clause, $reg_values, $last_offset, "utf-8");
			$last_offset = $pos[$key];
		}
		foreach($regs[0] as $key => $word) {
			$sentence_array[$i][$key] = new Word($word, $pos[$key]);
			
			switch(true) {
				case ( isset($sentence_array[$i][$key]->type) ) :
					//do nothing, already found type for this object
				break;
				case ( preg_match("/\p{P}/u", $word ) ) :
					$sentence_array[$i][$key]->type = "punctuation";
				break;
				default:
					//default name for the word is word?
					$sentence_array[$i][$key]->type = "word";
					$to_search = trim($word);
// 					if (preg_match("/으?로$/u", $to_search)) {
// 						$sentence_array[$i][$key]->with_ro = true;
// 					}
				break;
			}
		}

		$sentence_array[$i]["type"] = "final sentence words";
		$sentence_array[$i]["clause"] = $clause;
		$sentence_array[$i]["offset"] = $last_stored_position;
		ksort($sentence_array[$i], SORT_STRING);
	}
	//finished everything up to the beginning of the verb
	if(isset($sentence_array[$i]["type"])) $i++;
	if(isset($my_m_verb->verb_start_pos)) {
		
		//print all verb words
		
		$clause = mb_substr($sentence, $my_m_verb->verb_start_pos);
		$verb_words = preg_split('/ /', $clause, -1, PREG_SPLIT_NO_EMPTY);

		$last_offset=0;			
	
		//set positions after filtering non-values from array and reseting the index of $regs[0]
		unset($pos);
		
		foreach($verb_words as $key => $reg_values) {
			$pos[$key] = mb_strpos($clause, $reg_values, $last_offset, "utf-8");
			$last_offset = $pos[$key];
		}

		
		foreach($verb_words as $key => $word) {
			$sentence_array[$i][$key] = new Word($word, $pos[$key]);
			if($iv_particle && $word == "잘" || $word == "못" || $word == "안" ||$word == "꼭" || $word == "다시") {
				$sentence_array[$i][$key]->type = "iv particle";
			} else {
				$sentence_array[$i][$key]->type = "verb word";
			}
		}
				
		$sentence_array[$i]["mverb"] = $my_m_verb;
		$sentence_array[$i]["type"] = "final verb";
		$sentence_array[$i]["clause"] = $clause;
		$sentence_array[$i]["offset"] = $my_m_verb->verb_start_pos;
		ksort($sentence_array[$i], SORT_STRING);
	}
			
	return $sentence_array;	
}
