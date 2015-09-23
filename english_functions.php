<?php

//verb conjugation sentence

function conjugate_verbs(&$object_sentence_array) {
	foreach ( $object_sentence_array as $key => $clause ) {
		//new - handle tense in adnominal verbs
		foreach ($clause as $word_obj) {
			if (isset($word_obj->adnom)) {
				if($word_obj->adnom->verb_word) $vw = $word_obj->adnom->verb_word;
				else $vw = 0;

				$del_char = 0;			
				//this assumes that all multi-word english verbs in the 
				//database are separated by single spaces only.
				$verb_words = explode(" ", $word_obj->adnom->english);
				$word = $verb_words[$vw];
				
				switch ($word_obj->adnom->tense) {
					case "ing":
						if($word_obj->adnom->mot) { 
							array_unshift( $verb_words, 'isn\'t');
							$word = 'isn\'t';
							//why was there a break here?
						}
						
						$last_char= mb_substr($word, -1, 1);
						if ($word != 'be' && mb_ereg_match('[aeiouAEIOU]', $last_char)) $del_char = 1;
						if($del_char) $word = mb_substr($word, 0 , -$del_char). "ing";
						else $word .= 'ing';
					break;
					case "past":
						if($word->adnom->mot) { 
							array_unshift( $verb_words, 'couldn\'t');
							$word = 'couldn\'t';
							break;//why is there a break here??
						}
						$last_char= mb_substr($word, -1, 1);
						if (mb_ereg_match('[aeiouAEIOU]', $last_char)) $del_char = 1;
						if($word == 'be') {
							$word = 'were';
							break;
						}
						$query = "SELECT preterite FROM `trans_everb_tense` WHERE infinitive = \"". $word . "\" LIMIT 1";
						$result = mysql_query($query);
						if($result && mysql_num_rows($result)) {
							$row = mysql_fetch_assoc($result);
							$word = $row['preterite'];
						} else {
							if($del_char) $word = mb_substr($word, 0 , -$del_char). 'ed';
							else $word .= 'ed';
						}
					break;
					default:
					break;
				}
				//set the conjugated word
				$verb_words[$vw] = $word;
				//prepend the word 'also' if the verb contained the 'do' particle
				if($word_obj->adnom->do && $word_obj->adnom->isdouble) array_unshift( $verb_words, 'also');
				
				if($word_obj->adnom->jal)array_push( $verb_words, 'well');
				if($word_obj->adnom->an)array_unshift( $verb_words, 'not');
				//set the conjugated verb back into the mverb object.
				$word_obj->adnom->english = implode(" ", $verb_words);
// 				echo  "the new english: " . $word_obj->adnom->english;
			}
		}
		if (isset($clause['mverb'])) {
			if($clause['mverb']->verb_word) $vw = $clause['mverb']->verb_word;
			else $vw = 0;

			$del_char = 0;			
			//this assumes that all multi-word english verbs in the 
			//database are separated by single spaces only.
			$verb_words = explode(" ", $clause['mverb']->english);
			$word = $verb_words[$vw];
			if(is_null($word)) die('verb words pointed to an invalid word in the verb words array verb word is ' . $vw);
						
			
			
			//adjust the tense of the final verb word.
			switch ($clause['mverb']->tense) {
				
				case "present continuous":
					switch($clause['mverb']->auxillary_verb) {
						case 1:
						break;
						case 2:
						break;
						default:
						//auxillary verb = 0 'be'
						if($clause['mverb']->subject == 'it' || $clause['mverb']->subject == 'he' || $clause['mverb']->subject == 'she') {
							$word = 'is ' . $word . 'ing';
						} else {
							$word = 'are ' . $word . 'ing';
						}
						break;
					}						
						
				
				
				break;
				case "present":
				if (($word) == 'be') {
					if($clause['mverb']->subject == 'it' || $clause['mverb']->subject == 'he' || $clause['mverb']->subject == 'she') {
						$word = 'is';
					} else {
						$word = 'are';
					}
				}
				break;
				case "ing" :
					if($clause['mverb']->mot) { 
						array_unshift( $verb_words, 'isn\'t');
						$word = 'isn\'t';
						break;
					}
					$last_char= mb_substr($word, -1, 1);
					
					if ($word != 'be' && mb_ereg_match('[aeiouAEIOU]', $last_char)) $del_char = 1;
					if($del_char) $word = mb_substr($word, 0 , -$del_char). "ing";
					else $word .= 'ing';
				break;
				case "past" :
					if($clause['mverb']->mot) { 
						array_unshift( $verb_words, 'couldn\'t');
						$word = 'couldn\'t';
						break;
					}
					$last_char= mb_substr($word, -1, 1);
					if ($word != 'be' && mb_ereg_match('[aeiouAEIOU]', $last_char)) $del_char = 1;//ends in a vowel
					elseif ($last_char=='y' || $last_char=='Y') $word = mb_substr($word, 0,-1) . 'i';//ends in i
					elseif($word == 'be') {
						
						if($clause['mverb']->subject == 'it' || $clause['mverb']->subject == 'he' || $clause['mverb']->subject == 'she') {
							$word = 'was';
						} else {
							$word = 'were';
						}
						break;
					}
					$query = "SELECT preterite FROM `trans_everb_tense` WHERE infinitive = \"". $word . "\" LIMIT 1";
					$result = mysql_query($query);
					if($result && mysql_num_rows($result)) {
						$row = mysql_fetch_assoc($result);
						$word = $row['preterite'];
					} else {
						if($del_char) $word = mb_substr($word, 0 , -$del_char). 'ed';
						else $word .= 'ed';
					}
					break;
				default:
				break;
			}
			//set the conjugated word
			$verb_words[$vw] = $word;
			//prepend the word 'also' if the verb contained the 'do' particle
			if($clause['mverb']->do && $clause['mverb']->isdouble) array_unshift( $verb_words, 'also');
			
			if($clause['mverb']->jal)array_push( $verb_words, 'well');
			if($clause['mverb']->an)array_unshift( $verb_words, 'not');
			//set the conjugated verb back into the mverb object.
			$clause['mverb']->english = implode(" ", $verb_words);
		}
	} 
}
function print_english_sentence ($object_sentence_array) {
	//show what we got:
	
	echo "</pre><p><font size=6>";
	if($object_sentence_array[0]['english'])$object_sentence_array[0]['english'] = ucfirst($object_sentence_array[0]['english']);
	elseif($object_sentence_array[0][0]->prepositions[0])$object_sentence_array[0][0]->prepositions[0] = ucfirst($object_sentence_array[0][0]->prepositions[0]);
	//elseif($object_sentence_array[0][0]->english == 1 && isset($object_sentence_array[0]['mverb']) &&) $object_sentence_array[0]['mverb']->english = ucfirst($object_sentence_array[0]['mverb']->english);
	elseif($object_sentence_array[0][0]->english) $object_sentence_array[0][0]->english = ucfirst($object_sentence_array[0][0]->english);
	
	$color = 'FFCCFF';
	foreach($object_sentence_array as $key=>$clause) {
		
		//alternate background colors between the clauses
		if($color == 'FFCCFF') $color = 'CCCCFF';
		elseif($color == 'CCCCFF') $color = 'FFCCFF';
		echo '<span style=\'background-color: #'.$color.'\'>';
		
		
		if($clause['english']) {
			
			echo $clause['english'] . " ";
			continue;
		}
		$nocw = no_of_clause_words($clause);
		for($i =0; $i<$nocw;$i++) {
			if( $clause[$i]->prepositions ) {
				foreach ($clause[$i]->prepositions as $prep) echo $prep. " ";
			}
			if     ($clause[$i]->no_particle && $clause[$i]->no_particle != 1 && ($nocw -1) != $i && gettype($clause[$i]->english) == 'string') echo $clause[$i]->no_particle;
			elseif ($clause[$i]->english == 1 || $clause[$i]->type == "verb word") {}
			elseif ($clause[$i]->english && gettype($clause[$i]->english) == 'string') echo $clause[$i]->english;
			elseif ($clause[$i]->adnom->english) echo $clause[$i]->adnom->english;
			elseif ($clause[$i]->grammar_pattern->english) echo $clause[$i]->grammar_pattern->english;
			elseif ( $clause[$i]->word == '것을' || $clause[$i]->word == '것이' ) {/*do nothing, don't print 것을 & 것이*/}
			else echo $clause[$i]->word;
			if( $clause[$i]->postpositions ) {
				foreach ($clause[$i]->postpositions as $popo) echo $popo;
			}
// 			if($clause[$i]->word == 'quit') print_r($clause);
			//no space at the end of the clause
			if($i + 1 !=$nocw) echo " ";
		}
		//order_action 0 = leave in place 1 = swap with verb 2 = swap with object, 3 = swap with oe, 4 = swap with oe or object

		if ($clause['mverb']) {
			if(isset($clause['mverb']->prepositions))foreach ($clause['mverb']->prepositions as $prepo) echo $prepo;
			echo $clause['mverb']->english. " ";
			if(isset($clause['mverb']->postpositions))foreach ($clause['mverb']->postpositions as $popo) echo $popo;
		} elseif ($clause['adnom']) {
			if(isset($clause['adnom']->prepositions))foreach ($clause['adnom']->prepositions as $prepo) echo $prepo;
			echo $clause['adnom']->english;
			if(isset($clause['adnom']->postpositions))foreach ($clause['adnom']->postpositions as $popo) echo $popo;
		} 
		//no comma before verb and at end of sentence.
		if ($key != end(array_keys($object_sentence_array)) && is_null($object_sentence_array[$key]['mverb']) && is_null($object_sentence_array[$key + 1]['mverb'])) {
			echo ', ';
		} elseif ($key != end(array_keys($object_sentence_array))) {
			echo ' ';
		} 
		echo '</span>';
	}
	
	echo "</font></p>";
}

/* the purpose of this function is to scan the sentence 
for pronouns, then insert those pronouns after conjunctions 
where necessary 

maybe it will be able to access global vars 'current topic'
and 'current subject'...
*/


function insert_sentence_pronouns(&$osa) {

	//0 it 1 he 2 she NULL not 3rd person singular
	//markers are conjunction + verb
	
	// at the beginning of the sentence and following conjunctions there must be pronouns 
	// if there are not topics or subjects.
	
	//find the pronoun
	$found_conjunction = true;
	
	for ($clause = 0; $osa[$clause]; $clause++) {
		
		//removing the condition found_conjunction from here
// 		if($osa[$clause]['type'] == 'topic' || $osa[$clause]['type'] == 'subject' && $found_conjunction) {
		if($osa[$clause]['type'] == 'topic' || $osa[$clause]['type'] == 'subject' ) {
			
			//found a new topic/subject, update globals
			$last_word_index = num_of_clause_words($osa[$clause]) - 1;
// 			echo "\$last_word_index : $last_word_index";
// 			echo $osa[$clause][$last_word_index]->word ." has this value for three_ps " .
// 			$osa[$clause][$last_word_index]->three_ps;

			//if we find "I" or "you"
			if ( $osa[$clause][$last_word_index]->english == 'I' || $osa[$clause][$last_word_index]->english == 'you'||$osa[$clause][$last_word_index]->english == 'we' ) {
				$GLOBALS[$osa[$clause]['type']] = $osa[$clause][$last_word_index]->english;
			} else {
				//for all other pronoun types, it/he/she/they
				switch($osa[$clause][$last_word_index]->three_ps) {
					
					case 0:
						$GLOBALS[$osa[$clause]['type']] = 'it';
					break;
					case 1:
						$GLOBALS[$osa[$clause]['type']] = 'he';
	// 					echo "global subject: " . $GLOBALS['subject'] ." & global topic ". $GLOBALS['topic'] ;
					break;
					case 2:
						$GLOBALS[$osa[$clause]['type']] = 'she';
					break;
					default:
						$GLOBALS[$osa[$clause]['type']] = 'they';
					break;
					//when to use I ? (when there is no subject or topic in the first sentence?)
					
				}
			}
			$found_conjunction = false;
			
		} elseif ($clause == 0 && !$GLOBALS['subject'] && !$GLOBALS['topic']) {//and this first clause is neither a topic nor a subject
			
			//check the m_verb for tense (if it's propositive, we need to add a special topic/subject
			for($i = count($osa) - 1; $i > 1; $i--) {
				if($osa[$i]['mverb'] && $osa[$i]['mverb']->tense == 'propositive') $GLOBALS['subject'] = 'Let\'s';
				else $GLOBALS['subject'] = 'I';
			}
			$offset = 0;
			$word = $GLOBALS['subject']? $GLOBALS['subject']: $GLOBALS['topic'];
			
			$pronoun = new Word($word, $offset);
			$pronoun->type = 'pronoun';
			$pronoun->english = $word;
			
			//if the clause has an english definition maybe it doesn't need any words.
			$pronoun_clause = array($pronoun, 'type' => 'pronoun');
			$pronoun_clause['english'] = $word;
			
			//2 insert it into the osa, and increment $clause
			array_splice($osa, $clause, 0, array($pronoun_clause));
			
			//since we inserted a new element into the array, 
			//we need to increment the counter an extra time.
			$clause++;
			$found_conjunction = false;
		} elseif ( $found_conjunction && ($GLOBALS['subject'] || $GLOBALS['topic'])) {
			
			/*EXCEPTION!!
			
			IF there is just a subject/topic in the previous clause, ie, "Nick", we
			don't need a pronoun following the conjunction.
			*/
			
			
			/*EXCEPTION!!
			
			IF there is a grammar pattern in the preceeding clause which starts with 
			"there" there is no need to insert a pronoun, look for a conjunction,
			end of sentence, or a grammar pattern including a subject.
			*/
			for ($i = $clause; isset($osa[$i]) && is_null($osa[$i]['conj']) && is_null($osa[$i]['has_subject']); $i++) {}
			
			if($osa[$i]['has_subject']) {
				if(is_null($osa[$i]['conj']))$found_conjunction = false;
				//$clause can continue from the point where there is a subject.
				$clause = $i;
				continue;
			} //in any other situation, just continue.
			
			
			//insert the assumed pronoun into the sentence because 
			//this clause is not a topic or subject
			
			//1 create pronoun clause
			$offset = 0;
			$word = $GLOBALS['subject']? $GLOBALS['subject']: $GLOBALS['topic'];
			
			$pronoun = new Word($word, $offset);
			$pronoun->type = 'pronoun';
			$pronoun->english = $word;
			
			//if the clause has an english definition maybe it doesn't need any words.
			$pronoun_clause = array($pronoun, 'type' => 'pronoun');
			$pronoun_clause['english'] = $word;
			
			//2 insert it into the osa, and increment $clause
			array_splice($osa, $clause, 0, array($pronoun_clause));
			
			//since we inserted a new element into the array, 
			//we need to increment the counter an extra time.
			$clause++;
			
			//we handled the pronoun insertion so we can deactivate
			//$found_conjunction
			$found_conjunction = false;
			
		} elseif($osa[$clause]['type'] == 'conjunction' ) {
			if($osa[$clause]['english'] != 'for the purpose of') {
				$found_conjunction = true;
			}
		} elseif($osa[$clause]['mverb']) {
			$osa[$clause]['mverb']->subject = $GLOBALS['subject']? $GLOBALS['subject']: $GLOBALS['topic'];
		}
	}
}


//find, unset, then re-add action-verb adnominals behind the noun

function translate_adnominals(&$osa) {
// 	print_r($adnominal_array);
	
	foreach($osa as $key => $clause) {
		//get all the Word objects between each verb_start_pos and verb_mid_pos
		if($clause['adnominal_clause']) {
			
			unset($clause['adnominal_clause']);
			
			$cw = no_of_clause_words($clause);
			//when english is not == 1, that's a separate adnominal verb
			
			
			$i = 0;
			//advance the pointer to the first adnominal verb words
			while (isset($clause[$i]) && $clause[$i]->type != 'adnominal verbmod' && $clause[$i]->type != 'adnominal verb' && $clause[$i]->type != 'grammar pattern') {$i++;}
			
			//reasons not to handle the adnominal in this way (next 2 lines)
			if(is_null($clause[$i]) || $clause[$i]->type =='grammar pattern' )return;
			//more conditions to terminate
			//next word in clause is NOT:
			// any TYPE of noun (with or without a particle)
			// any type of adverb or adnominal 
				if(strpos($clause[$i+1]->type, 'noun') === false && 
					empty($clause[$i+1]->noun1) && 
					strpos($clause[$i+1]->type, 'adnominal')===false && 
					strpos($clause[$i+1]->type, 'adverb')===false )return;
			
			
			for (; $clause[$i]->type == 'adnominal verbmod' || $clause[$i]->type == 'adnominal verb'; $i++){
				//1 collect adnominal verbmod words into adnominal_verb_array
				$adnominal_verb_array[] = $clause[$i];
				unset($clause[$i]);
			}
			if( !$adnominal_verb_array ) {
				echo "dying<pre>";
				print_r($clause);
				echo "</pre>";
				die("clause is $key and didn't find the proper word markings");
			}
			
			$adnominal_verb_array = array_reverse($adnominal_verb_array);
			
			//3 add qword to noun's 'english' value
			if($clause[$cw -1]->type == 'word' || $clause[$cw -1]->type == 'noun + particle') {
				if($clause[$cw -1]->qword) {
					switch ($clause[$cw -1]->qword) {
						case 1://who
							$clause[$cw-1]->postpositions[] = ' who ';
						break;
						case 2://where
							$clause[$cw-1]->postpositions[] = ' where ';
						break;
						case 3://when
							$clause[$cw-1]->postpositions[] = ' when ';
						break;
						case 4://of
							$clause[$cw-1]->postpositions[] = ' of ';
						break;
					}
				} else {
					$clause[$cw-1]->postpositions[] = ' which ';
				}
				if($clause[$cw -1]->three_ps) {
					$pronoun = $clause[$cw -1]->three_ps;
// 						switch($clause[$cw -1]->three_ps) {
// 							case 0:
// 							$pronoun = 'it';
// 							break;
// 							case 1:
// 							$pronoun = 'he';
// 							break;
// 							case 2:
// 							$pronoun = 'she';
// 							break;
// 						}
				} elseif ($clause[$cw -1]->isplural){
// 						$pronoun = 'they';
					$pronoun = 3; //they
				} else {
					// I or you?
				}
			} else {
				
				//this clause may end in a grammar pattern
// 				echo "ERROR, clause not ending with noun + particle, word object type is: " .$clause[$cw -1]->type;

			}
			//4 insert adnominal verb after the noun with it's **conjugated** auxillary verb
// 			print_r($adnominal_verb_array);
			foreach($adnominal_verb_array as $adnominal_element) {
				if($adnominal_element->type == 'adnominal verb') {
					
					//call function to conjugate auxillary verb using "pronoun"
					$adnominal_element->adnom->prepositions[] =  conjugate_auxillary ( $pronoun, $adnominal_element->adnom->auxillary_verb, $adnominal_element->adnom->tense );
					$adnominal_element->adnom->english = $adnominal_element->adnom->english;
				} 
				//just add it to the end?
				$clause = array_merge($clause, array($adnominal_element));
				
			}
			unset($adnominal_verb_array);
// 			print_r($clause);
			$osa[$key] = $clause;
		}
	}
}

function conjugate_auxillary ( $pronoun, $auxillary_verb, $tense ) {
	//be have do go
	
switch($tense) {
	case 'ing' :
		return;
	break;
	case 'past':
	
// 	echo "\nhere's the tense: $tense \n here's the aux verb: $auxillary_verb\n";
	if($pronoun < 3) { //it's a 3ps pronoun
		switch ($auxillary_verb) {
			case 0:
				return 'was';
			break;
			case 1:
				return 'had';
			break;
			case 2:
				return 'did';
			break;
			case 3:
				return 'went';
			break;
			default:
				echo "problem, invalid \$auxillary verb!";
		}
	} elseif ($pronoun == 3) {
		//we or they?
		switch ($auxillary_verb) {
			case 0:
				return 'were';
			break;
			case 1:
				return 'had';
			break;
			case 2:
				return 'did';
			break;
			case 3:
				return 'went';
			break;
			default:
				echo "problem, invalid \$auxillary verb!";
		}
	}
	break;
	case 'future' :
	// 	echo "\nhere's the tense: $tense \n here's the aux verb: $auxillary_verb\n";
		//we or they?
		switch ($auxillary_verb) {
			case 0:
				return 'will be';
			break;
			case 1:
				return 'will have';
			break;
			case 2:
				return 'will do';
			break;
			case 3:
				return 'will go';
			break;
			default:
				echo "problem, invalid \$auxillary verb!";
		}
	break;	
	}
	
}
?>