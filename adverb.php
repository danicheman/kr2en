<?php				

/* This function searches for ONE adverb and returns true
   if it is found.  It also adds the information associated
   with that adverb, the table entry, english meaning, and
   so on into the adverb array contained in the $my_m_verb
   object
  */


  
function find_adverb($check, &$my_m_verb) {
	//search for typical verb form ending, as a derived adverb (같이, 멀리)
	//~히 ~리 ~이 ~(으)로
	
	if(preg_match('/\S*?(히|리|이|(으)?로)$/u', $check, $matches)) {
		//found a "derived adverb ending" on $check
// 					$matches[0];//whole word
// 					$matches[1];//particle
		switch($matches[1]) {
			case '히':
				$check = mb_substr($check, 0, -1) . '하';
			break;
			case '리':
				//will still have one more to check after this..
				$check = mb_substr($check, 0, -1);
				
				//do this to check and possibly remove a ㄹ at the end
				utf_normalizer::nfd($check);
				$test = jamo_to_co_jamo($check);
				
				if(mb_substr($test, -1) == 'ㄹ') {
					$check = mb_substr($check, 0, -1);
				}
				utf_normalizer::nfc($check);
				$check .= '르';
			break;
			case '이':
				$check = mb_substr($check, 0, -1);
			break; 
			case '으로':
			//on a noun
				$noun = true;
				$check = mb_substr($check, 0, -2);
			break;
			case '로':
			//on a noun
				$noun = true;
				$check = mb_substr($check, 0, -1);
			break;
			default:
			die('didn\'t match what it was supposed to!!');
			return false;
		}
		
		if($noun) {
			//search noun table
			$query  = 'SELECT * FROM `trans_knouns` AS kn, `trans_knoun_meanings` AS km WHERE ( `noun1` LIKE \'' . $check . '\' OR `noun2` LIKE \'' . $check . '\' ) AND km.nin = kn.nin LIMIT 1';
			
			$result = mysql_query($query);
			if ($result && mysql_num_rows($result)==0) {
				//couldn't find anything, stop searching for iv_particles.
				return false;
			} else {
				 
				//matched NOUN adverb, add it to adverb array
				$row = mysql_fetch_assoc($result);
				$row['type'] = 'derived';
				
			}
		} else {
			//search verb table
			$query  = 'SELECT * FROM `trans_kverbs` AS kv, `trans_kverb_meanings` AS km WHERE `knocon` LIKE \'' . $check . '\' AND km.vin = kv.vin LIMIT 1';
			$result = mysql_query($query);
			if (!mysql_num_rows($result) && '르' == mb_substr($check, -1)) {
				//search again without the last particle
				$check = mb_substr($check, -1);
				$query = 'SELECT * FROM `trans_kverbs` AS kv, `trans_kverb_meanings` AS km WHERE `knocon` LIKE \'' . $check . '\' AND km.vin = kv.vin LIMIT 1';
				$result = mysql_query($query);
				if (mysql_num_rows($result) == 0) {
					//stop the loop, couldn't find any known iv_particle (Adverb). break;
					return false;
				}
			}
			//found a result 
			$row = mysql_fetch_assoc($result);
			$row['type'] = 'derived';
			
		}
		$my_m_verb->adverb[] = $row;
		//get english meaning from different adverb table?
	} else {
		//search for non-derived adverbs in adverb table (더, 다시)
		$query = 'SELECT * FROM `trans_non_derived_adverbs` WHERE `word` LIKE \'' . $check . '\' LIMIT 1';
		$result = mysql_query($query);
		if (mysql_num_rows($result) == 0) {
			//stop the loop, couldn't find any known iv_particle (Adverb). break;
			return false;
		} else {
			//found an iv_particle (Derived Adverb) in the adverb table
			$row = mysql_fetch_assoc($result);
			$row['type'] = 'underived';
			$my_m_verb->adverb[] = $row;
		}
	}
	return true;
}				
/*this function moves adverbs according to their type:

	->beginning of clause
	
		special 	( usually, normally, often, frequently, sometimes and occasionally )
	
    ---------------------------------------------------------------
    
	-> after be / auxillary verbs, before main verb (default)
	
		frequency 	( never, rarely, sometimes, often, usually, always, ever )
		time 		( still, yet, finally, eventually, just )
		focusing 	( even, only, also, mainly, just )
		
	---------------------------------------------------------------
	
	->end of clause
	
		frequency	( daily, weekly, monthly, yearly, today, every week, soon, last )
		time 		( today, every week, already, soon )
		manner 		( slowly, suddenly, badly, quietly )
	
	*/
function sort_adverbs(&$osa) {
	
  $special_adverbs = array('usually', 'normally', 'often', 'sometimes', 'occasionally', 'firstly', 'lastly', 'secondly','thirdly');
  $mid_frequency	= array( 'never', 'rarely', 'sometimes', 'often', 'usually', 'always', 'ever' );
  $mid_time 		= array( 'still', 'yet', 'finally', 'eventually', 'just' );
  $end_frequency	= array( 'daily', 'weekly', 'monthly', 'yearly', 'last' );
  $end_time 		= array( 'today', 'already', 'soon','yesterday','tomorrow'  );
  $mid_certainty	= array( 'certainly', 'definitely', 'clearly', 'obviously', 'probably', 'totally', 'absolutely', 'undoubtedly' );
  
	foreach ($osa as $clause_index => $clause) {
		foreach ($clause as $word_index => $word) {
			if(($word->type == 'derived adverb' || $word->type == 'underived adverb') && !$word->moved) {
				
				$osa[$clause_index][$word_index]->moved = true;
				
				switch ($word->english) {
					
					case (in_array($word->english, $special_adverbs)) :
					//move to the front
					break;
					case (isset($word->keywords) && in_array('time', $word->keywords)) :
						//time adverb...mid or end of clause?
						if(in_array($word->english, $mid_time)) {
							//move in between auxillary /be verbs
						} elseif (in_array($word->english, $end_time)){
							// put at end of clause before conjunction 
						}
						
					break;
					case ( isset($word->keywords) && in_array('frequency',$word->keywords) ) :
						//frequency adverb...mid or end of clause?
						
						if ( in_array( $word->english, $mid_frequency )) {
							//move in between auxillary /be verbs
						} elseif ( in_array( $word->english, $end_frequency )) {
							// put at end of clause before conjunction 
							$osa[$clause_index]['mverb']->postpositions[] = ' ' . $word->english . ' ';
							$osa[$clause_index][$word_index]->english = 1;	
						}
						
					break;
					case ( isset($word->keywords) && in_array('certainty',$word->keywords) ) :
						//frequency adverb...mid or end of clause?
						
						// put at end of clause before conjunction 
						$osa[$clause_index]['mverb']->prepositions[] = ' ' . $word->english . ' ';
						$osa[$clause_index][$word_index]->english = 1;	
					break;
					default:
						//if it's already defined as a noun, don't attempt to do anything with it.
						if($clause['type'] == 'object')break;
						// adverbs of manner, go at the end of the clause or sentence!!
						clause_back_of_sentence_or_conj($osa, $clause_index, $osa[$clause_index][$word_index]);
						unset ($osa[$clause_index][$word_index]);
					break;
				}
			}
		}
	}
}

?>