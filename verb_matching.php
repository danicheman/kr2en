<?php
/* k2e_verb checks the dictionary for $verb and the second last word in 
   sentence checking conjugated and unconjugated forms
   
	 - $verb is the part that was disconnected from the tense and recomposed
	 - $bigverb contains $verb as well as the word which preceeded it
	 - $mverb the object for the modified verb, used to return the english
	   		definition of the verb if found.
 */
 
function k2e_verb($verb, $bigverb, $mverb) {
	//1. search verb dictionary for size of verb + second last word in sentence
	if(!$verb || $verb == ' ') return false;
	
	if (isset($bigverb)) {
		$one_word_bigverb = mb_ereg_replace('\s','',$bigverb);
		$query = "select km.* from trans_kverbs as kv,trans_kverb_meanings as km WHERE kv.vin = km.vin AND (kv.knocon = \"".$bigverb."\" OR kv.kcon = \"".$bigverb."\" OR kv.other = \"" . $bigverb . "\")";

		$result = mysql_query($query); 
		if ( $result ) {
			if($row = mysql_fetch_assoc($result)){ 
				$mverb->english($row["english"], 1); //only inserting one definition..found under single (isdouble ->false)
				foreach($row as $name=>$value) {
					if($name != 'english' && isset($value)) $mverb->$name = $value;
				}
			}
		} else {
			//search again with bigverb as one word
			$bigverb = mb_ereg_replace('\s','',$bigverb);
			$query = "select km.* from trans_kverbs as kv,trans_kverb_meanings as km WHERE kv.vin = km.vin AND (kv.knocon = \"".$bigverb."\" OR kv.kcon = \"".$bigverb."\" OR kv.other = \"" . $bigverb . "\")";

			$result = mysql_query($query); 
			if ( $result ) {
				if($row = mysql_fetch_assoc($result)){ 
					$mverb->english($row["english"], 1); //only inserting one definition..found under single (isdouble ->false)
					foreach($row as $name=>$value) {
						if($name != 'english' && isset($value)) $mverb->$name = $value;
					}
				}
			}
		}
	}

	
	//2. look for just the word that was disconnected from the tense
	
	if ( empty($result) || 0 == mysql_num_rows($result) ) {
		unset($result);
				
	
		$query = "select * from trans_kverbs as kv,trans_kverb_meanings as km WHERE kv.vin = km.vin AND (kv.knocon = \"".$verb."\" OR kv.kcon = \"".$verb."\" OR kv.other = \"" . $verb . "\")";

		$result = mysql_query($query); 
		
		if ( empty($result) || 0 == mysql_num_rows( $result ) ) {
			return false;  //no verb found, return false.
		}
		
		if($row = mysql_fetch_assoc($result)){ 
// 			echo $row[0]; //echo the search result
			$mverb->english($row['english'], 0); //only inserting one definition..the last found..
			foreach($row as $name=>$value) {
				if($name != 'english' && isset($value)) $mverb->$name = $value;
			}
			if($row['special']) {
				$query = 'select * from trans_kverb_translation as tkt WHERE tkt.vin = '.$row['vin'];

				$result2 = mysql_query($query); 
				if ( empty($result) || 1 != mysql_num_rows( $result2 ) ) {
					die('special translation indicated but no special translation found');  
				}else {
					$row2 = mysql_fetch_assoc($result2);
					foreach($row2 as $key=>$value) $mverb->$key = $value;
				}
			}
		} 
	}
	
	if (!$result) {
	   die('Invalid query: ' . mysql_error());
	}
	$mverb->order_action = $row['order_action'];
	 return true;
}
function k2e_con_verb($verb, $bigverb, $mverb) {
	//1. search verb dictionary for size of verb + second last word in sentence
	
	//need to be added to make sure that quotations do not interfere with the sql searching for the verb.
	
	$verb = mb_ereg_replace('\"', '', $verb);
	if (isset($bigverb)) {
		$bigverb = mb_ereg_replace('\"', '', $bigverb);
		$query = "select * from trans_kverbs as kv,trans_kverb_meanings as km WHERE kv.vin = km.vin AND (kv.kcon = \"".$bigverb."\" OR kv.other = \"" . $bigverb . "\")";

		$result = mysql_query($query); 
		if ( $result && 0 != mysql_num_rows($result) ) {
			if($row = mysql_fetch_assoc($result)){ 
				$mverb->english($row['english'], 1); //only inserting one definition..found under single (isdouble ->false)
				foreach($row as $name=>$value) {
					if($name != 'english' && isset($value)) $mverb->$name = $value;
				}				
				if($row['special']) {
					$query = 'select * from trans_kverb_translation as tkt WHERE tkt.vin = '.$row['vin'];
	
					$result2 = mysql_query($query); 
					if ( empty($result) || 1 != mysql_num_rows( $result2 ) ) {
						die('special translation indicated but no special translation found');  
					}else {
						$row2 = mysql_fetch_assoc($result2);
						foreach($row2 as $key=>$value) $mverb->$key = $value;
					}
				}
			}
		} else {
			//search again with bigverb as one word
			$bigverb = mb_ereg_replace('\s','',$bigverb);
			$query = "select km.* from trans_kverbs as kv,trans_kverb_meanings as km WHERE kv.vin = km.vin AND (kv.knocon = \"".$bigverb."\" OR kv.kcon = \"".$bigverb."\" OR kv.other = \"" . $bigverb . "\")";

			$result = mysql_query($query); 
			if ( $result ) {
				if($row = mysql_fetch_assoc($result)){ 
					$mverb->english($row["english"], 1); //only inserting one definition..found under single (isdouble ->false)
					foreach($row as $name=>$value) {
						if($name != 'english' && isset($value)) $mverb->$name = $value;
					}
					if($row['special']) {
						$query = 'select * from trans_kverb_translation as tkt WHERE tkt.vin = '.$row['vin'];
	
						$result2 = mysql_query($query); 
						if ( empty($result) || 1 != mysql_num_rows( $result2 ) ) {
							die('special translation indicated but no special translation found');  
						}else {
							$row2 = mysql_fetch_assoc($result2);
							foreach($row2 as $key=>$value) $mverb->$key = $value;
						}
					}
				}
			}
		} 				
	}
	
	//2. look for just the word that was disconnected from the tense
	
	if ( empty($result) || 0==mysql_num_rows($result) ) {
		unset($result);
		
		
		$query = "select km.* from trans_kverbs as kv,trans_kverb_meanings as km WHERE kv.vin = km.vin AND (kv.kcon = \"".$verb."\" OR kv.other = \"" . $verb . "\")";

		$result = mysql_query($query); 
		
		if ( empty($result) || 0 == mysql_num_rows( $result ) ) {
			return false;  //no verb found, return false.
		}
		
		if($row = mysql_fetch_assoc($result)){ 
// 			echo $row[0]; //echo the search result
			$mverb->english($row['english'], 0); //only inserting one definition..the last found..
			foreach($row as $name=>$value) {
				if($name != 'english' && isset($value)) $mverb->$name = $value;
			}
			if($row['special']) {
				$query = 'select * from trans_kverb_translation as tkt WHERE tkt.vin = '.$row['vin'];

				$result2 = mysql_query($query); 
				if ( empty($result) || 1 != mysql_num_rows( $result2 ) ) {
					die('special translation indicated but no special translation found');  
				}else {
					$row2 = mysql_fetch_assoc($result2);
					foreach($row2 as $key=>$value) $mverb->$key = $value;
				}
			}
		}
	}	
	if (!$result) {
	   die('Invalid query: ' . mysql_error());
	}
	$mverb->order_action = $row['order_action'];
	return true;
}
function is_nocon_verb($to_check) {
	if (!isset($to_check) || is_numeric($to_check)) return false;
	//checking non-conjugated only...
	$query = "select km.* from trans_kverbs as kv,trans_kverb_meanings as km WHERE kv.vin = km.vin AND kv.knocon = \"".$to_check."\"";
	$result = mysql_query($query); 
	if(mysql_num_rows($result)) return true; 
	else return false;
	
}

//same as above but only seaching unconjugated verbs.
function k2e_nocon_verb($verb, $bigverb, $mverb) {
	if (empty($verb)) return false; //nothing to translate, unsuccessful.

	//translate verb DOES NOT RETURN ANYTHING YET, JUST ECHOES
	//1. search verb dictionary for size of verb + second last word in sentence
	
	
	if (isset($bigverb)) {
		
		$query = "select km.* from trans_kverbs as kv,trans_kverb_meanings as km WHERE kv.vin = km.vin AND kv.knocon = \"".$bigverb."\"";
		$result = mysql_query($query); 
		if($result && 0 != mysql_num_rows($result)) {
			if($row = mysql_fetch_assoc($result)){ 
					//echo $row[0]; 
					$mverb->english($row['english'], 1); //only inserting one definition..found under single (isdouble ->false)
					foreach($row as $name=>$value) {
						if($name != 'english' && isset($value)) $mverb->$name = $value;
					}
					if($row['special']) {
					$query = 'select * from trans_kverb_translation as tkt WHERE tkt.vin = '.$row['vin'];
	
					$result2 = mysql_query($query); 
					if ( empty($result) || 1 != mysql_num_rows( $result2 ) ) {
						die('special translation indicated but no special translation found');  
					}else {
						$row2 = mysql_fetch_assoc($result2);
						foreach($row2 as $key=>$value) $mverb->$key = $value;
					}
				}
			}
		} else {
			//search again with bigverb as one word
			$bigverb = mb_ereg_replace('\s','',$bigverb);
			$query = "select km.* from trans_kverbs as kv,trans_kverb_meanings as km WHERE kv.vin = km.vin AND (kv.knocon = \"".$bigverb."\" OR kv.kcon = \"".$bigverb."\" OR kv.other = \"" . $bigverb . "\")";

			$result = mysql_query($query); 
			if ( $result ) {
				if($row = mysql_fetch_assoc($result)){ 
					$mverb->english($row["english"], 1); //only inserting one definition..found under single (isdouble ->false)
					foreach($row as $name=>$value) {
						if($name != 'english' && isset($value)) $mverb->$name = $value;
					}
					if($row['special']) {
						$query = 'select * from trans_kverb_translation as tkt WHERE tkt.vin = '.$row['vin'];
	
						$result2 = mysql_query($query); 
						if ( empty($result) || 1 != mysql_num_rows( $result2 ) ) {
							die('special translation indicated but no special translation found');  
						}else {
							$row2 = mysql_fetch_assoc($result2);
							foreach($row2 as $key=>$value) $mverb->$key = $value;
						}
					}
				}
			}
		}
	}
	if (empty($result) || 0 == mysql_num_rows($result)) {
		unset($result);
		//2. look for smaller verb
		$query = "select km.* from trans_kverbs as kv,trans_kverb_meanings as km WHERE kv.vin = km.vin AND kv.knocon = \"".$verb."\"";
		
		$result = mysql_query($query); 
		if($result && 0 != mysql_num_rows($result)) {
			if($row = mysql_fetch_assoc($result)){ 
					//echo $row[0]; 
					$mverb->english($row['english'], 0); //only inserting one definition..
					foreach($row as $name=>$value) {
						if($name != 'english' && isset($value)) $mverb->$name = $value;
					}
					$mverb->order_action = $row['order_action'];
					if($row['special']) {
					$query = 'select * from trans_kverb_translation as tkt WHERE tkt.vin = '.$row['vin'];
	
					$result2 = mysql_query($query); 
					if ( empty($result) || 1 != mysql_num_rows( $result2 ) ) {
						die('special translation indicated but no special translation found');  
					} else {
						$row2 = mysql_fetch_assoc($result2);
						foreach($row2 as $key=>$value) $mverb->$key = $value;
					}
				}
			}
		}
	} 
	if(!$result) echo "no result from query $query";
	if ( 0 == mysql_num_rows($result) ) {
			return false;  //no verb found, return false.
	} else {
		return false; //no results found.
	}
	if (!$result) {
	   die('Invalid query: ' . mysql_error());
	}
	return true;
}

//same as above but only seaching unconjugated verbs.
function k2e_nocon_conj_verb($verb, $bigverb, $mverb) {
	if (empty($verb)) return false; //nothing to translate, unsuccessful.

	//need to be added to make sure that quotations do not interfere with the sql searching for the verb.
	
	$verb = mb_ereg_replace('\"', '', $verb);
	//translate verb DOES NOT RETURN ANYTHING YET, JUST ECHOES
	//1. search verb dictionary for size of verb + second last word in sentence
	
	//$verb = mb_ereg_replace("^잘|^못", '', $verb);
	//$bigverb = mb_ereg_replace("^잘|^못", '', $bigverb);

	
	if (isset($bigverb)) {
		$bigverb = mb_ereg_replace('\"', '', $bigverb);
		$query = "select * from trans_kverbs as kv,trans_kverb_meanings as km WHERE kv.vin = km.vin AND (kv.knocon = \"".$bigverb."\" OR kv.knocon_conj = \"".$bigverb."\")";
// 				echo "query: " . $query . "<br>";
		$result = mysql_query($query); 
		if($result && 0 != mysql_num_rows($result)) {
			if($row = mysql_fetch_assoc($result)){ 
				//echo $row[0]; 
				$mverb->english($row['english'], 1); //only inserting one definition..found under single (isdouble ->false)
				foreach($row as $name=>$value) {
					if($name != 'english' && isset($value)) $mverb->$name = $value;
				}
				if($row['special']) {
					$query = 'select * from trans_kverb_translation as tkt WHERE tkt.vin = '.$row['vin'];
	
					$result2 = mysql_query($query); 
					if ( empty($result) || 1 != mysql_num_rows( $result2 ) ) {
						die('special translation indicated but no special translation found');  
					}else {
						$row2 = mysql_fetch_assoc($result2);
						foreach($row2 as $key=>$value) $mverb->$key = $value;
					}
				}
			}
		}
	}
	
	if (empty($result) || 0 == mysql_num_rows($result)) {
		unset($result);
		//2. look for smaller verb
		$query = "select * from trans_kverbs as kv,trans_kverb_meanings as km WHERE kv.vin = km.vin AND (kv.knocon = \"".$verb."\" OR kv.knocon_conj = \"".$verb."\")";
// 				echo "query: " . $query . "<br>";
		$result = mysql_query($query); 
		if($result && 0 != mysql_num_rows($result)) {
			if($row = mysql_fetch_assoc($result)){ 
				//echo $row[0]; 
				$mverb->english($row['english'], 0); //only inserting one definition..
				foreach($row as $name=>$value) {
					if($name != 'english' && isset($value)) $mverb->$name = $value;
				}
				if($row['special']) {
					$query = 'select * from trans_kverb_translation as tkt WHERE tkt.vin = '.$row['vin'];
					$result2 = mysql_query($query); 
					if ( empty($result) || 1 != mysql_num_rows( $result2 ) ) {
						die('special translation indicated but no special translation found');  
					}else {
						$row2 = mysql_fetch_assoc($result2);
						foreach($row2 as $key=>$value) $mverb->$key = $value;
					}
				}
			} 
			if ( 0 == mysql_num_rows ( $result ) ) {
				return false;  //no verb found, return false.
			}
		} else {
			return false; //no results found.
		}
	}
	
	if (!$result) {
	   die('Invalid query: ' . mysql_error());
	}
			
	return true;
}

//returns an m_verb (modified verb) object
function handle_verb( $sentence, $words, $decomp_words, $shifted_words ) {
	
	$sentence_length = sizeof($words);

	//remove the sentence ending punctuation, if it exists.
	
	$last_word = end($shifted_words);
	
	//if the last character in the sentence is a period, question mark, or exclamation mark, omit it.
	$last_word = preg_replace("/\p{P}/u", "", $last_word);

	if ($sentence_length == 1) {//if the sentence is just one word long, consider that word a verb			
		$my_m_verb = find_tense( $last_word );
		if(isset($my_m_verb))$my_m_verb->two_word_tense( false );
	} else {
			//sentence has two or more words, use them to determine the tense.
			$second_last = $shifted_words[sizeof($shifted_words) - 2];		
			$my_m_verb = find_tense($second_last . " " . $last_word);

			//since we just want to highlight the part of the verb which was
			//separated from the tense, if there is another word in the "mverb"
			//variable, we will remove it.

			//alot of the if statements depend on whether my_m_verb was set... combine them?
			if ( isset($my_m_verb) && mb_strpos($my_m_verb->mverb," ") ) {
				
				$my_m_verb->mverb = mb_ereg_replace("[^\s]+\s+", "", $my_m_verb->mverb);
				$my_m_verb->length = mb_strlen($my_m_verb->mverb);
				$my_m_verb->two_word_tense( false );
			} elseif ( isset($my_m_verb)) {
				//tense would have been like ~고 있다 or a tense containing a space.
				$my_m_verb->two_word_tense( true );
			} else return; //we couldn't match a tense to the verb, handle it as a single noun.
	} 

	//search for the verb (disconnected from the tense) in the dictionary.
	if($my_m_verb->two_word_tense) {
		//Using a two-word verb for the verb display
		$decomp_verb_and_tense = $decomp_words[sizeof($decomp_words) - 2] . " " . end($decomp_words);
 	} else {
		//Using a one-word verb and tense for the verb display
		$decomp_verb_and_tense = end($decomp_words);
	}
	$decomp_verb_and_tense = preg_replace("/\p{P}/u", "", $decomp_verb_and_tense);
	
	//Normalize the found tense separated from the verb
	$my_m_verb->verb_part = mb_substr($decomp_verb_and_tense, 0, $my_m_verb->length);
	$my_m_verb->tense_part = mb_substr($decomp_verb_and_tense, $my_m_verb->length);
		
	utf_normalizer::nfc($my_m_verb->verb_part);
	utf_normalizer::nfc($my_m_verb->tense_part);
	
// 	echo "handling: verb part: " .$my_m_verb->verb_part ."tense part : " . $my_m_verb->tense_part;
	//if there is a third word that can be included in the verb search, add it.
	
	if ( $my_m_verb->two_word_tense && $sentence_length > 2 ) {
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
	for ( $i = 0, $check = $words[$sentence_length - $i - $rev_offset]; $i < 3; $i++, $check = $words[$sentence_length - $i - $rev_offset]) {
		//remove punctuation from $check
		$check = preg_replace("/[\p{P}\s]/u", "", $check);
		
		switch ($check) {
			case "잘":
				$my_m_verb->jal = true;
				break;
			case "못":
				$my_m_verb->mot = true;
				break;
			case "안":
				$my_m_verb->an = true;
				break;
			/*case "꼭":
				$my_m_verb->ggog = true;
				break;*/	
			case "다시":
				$my_m_verb->dashi = true;
				break;	
			default:
				if(!find_adverb($check, $my_m_verb)) {
					$i = 3;
					$iv_particle--;
				}
			break;
		}	
		$iv_particle++;
	}
	
	$first_verb_word = $words[$sentence_length - $iv_particle - $rev_offset];
	if($iv_particle) $my_m_verb->iv_particle = $iv_particle;
	
	//use last particle removal to remove 을, 를, 이, 가 from end of $first_verb_word
	$last_particle = mb_substr($first_verb_word, -1, 1);

	//if the last character in the sentence is a period, question mark, or exclamation mark, omit it.

	if ($last_particle == "을"|| $last_particle == "를"|| $last_particle == "이" || $last_particle == "가" || $last_particle == "도"){
		//omit the last character from the end of the first verb word.
		$first_verb_word = mb_substr($first_verb_word, 0, -1);
		if ($last_particle == "도") $my_m_verb->do = true;
		$my_m_verb->removed_particle = true;
	}
	//the big verb is composed of the verb part removed from the tense part
	// and the word preceeding it.
	$first_verb_syllable = mb_substr($my_m_verb->verb_part, 0,1);
	$last_verb_syllable = mb_substr($my_m_verb->verb_part, -1);
	$verb_part_backup = $my_m_verb->verb_part;
	//need to remove and handle special particles connected to second verb word
	if (($first_verb_syllable == "잘" || $first_verb_syllable == "못") && ($last_verb_syllable == "해" || $last_verb_syllable == "하" || $last_verb_syllable == "되" || $last_verb_syllable == "돼")) {
		//need to remove at least the first syllable, maybe the second.
		$second_verb_syllable = mb_substr($my_m_verb->verb_part, 1,1);
		if ($second_verb_syllable == "잘" || $second_verb_syllable == "못") {
			$my_m_verb->jal = true;
			$my_m_verb->mot = true;
			$my_m_verb->verb_part = mb_substr($my_m_verb->verb_part, 2); 
		} elseif ($first_verb_syllable == "잘") {
			$my_m_verb->jal = true;
			$my_m_verb->verb_part = mb_substr($my_m_verb->verb_part, 1);
		} elseif ($first_verb_syllable == "못") {
			$my_m_verb->mot = true;
			$my_m_verb->verb_part = mb_substr($my_m_verb->verb_part, 1);
		} else echo "error!! unhandled pre verb word syllable.";
	}
	//***since "verb part" is now part of my_m_verb, it doesn't need to be passed seperately.
	$bigverb = $first_verb_word . " " . $my_m_verb->verb_part;
	//need to fix printing for chal and mot in one word verbs. by changing the value for word start.
	if ($my_m_verb->conjugated) {
		echo "Looking for conjugated: ". $bigverb . " and " . $my_m_verb->verb_part. "<br>\n";
		k2e_con_verb($my_m_verb->verb_part, $bigverb, $my_m_verb);
	} else {
		echo "Looking for unconjugated: ". $bigverb . " and " . $my_m_verb->verb_part. "<br>\n";
		k2e_nocon_verb($my_m_verb->verb_part, $bigverb, $my_m_verb);
	}
	$my_m_verb->verb_part = $verb_part_backup;
// 	echo "m_verb length is: " . $my_m_verb->length . " and length is: " . mb_strlen($my_m_verb->verb_part);
	if ($my_m_verb->isdouble) { 
		
		//wherever the first verb word is
		$verb_start = $first_verb_word; 
		
	} else {
		//use IV particle value to set it up.
		$my_m_verb->removed_particle = false;
		
		if($my_m_verb->two_word_tense) {
			//second last word of the sentence
			$verb_start = $words[$sentence_length - 2 - $iv_particle];
		} else {
			//last word of the sentence
			$verb_start = $words[$sentence_length - 1 - $iv_particle];
		}
	}
	
	if ($my_m_verb->two_word_tense) $verb_and_tense_word = $words[$sentence_length - 2];
	else $verb_and_tense_word = $words[$sentence_length - 1];
	//*** this is a quick-fix solution to removed_particle being incorrectly set above.
	
	
	$my_m_verb->verb_mid_pos = mb_strrpos($sentence, $verb_and_tense_word);
	
	/* If $verb_start is set, then that's the start of the verb, otherwise, 
	   it's the same as the mid pos.
	 */
	if($verb_start) $my_m_verb->verb_start_pos = mb_strrpos($sentence, $verb_start);
	else $my_m_verb->verb_start_pos = $my_m_verb->verb_mid_pos;
	
	
	$to_return = $my_m_verb->english ? $my_m_verb:NULL;
	return $to_return;	
}



//returns an m_verb (modified verb) object
function handle_verb_adnominal( $sentence, $words, $decomp_words, $shifted_words, $orign_sentence) {
	if(empty($orign_sentence)) die("origin sentence is required");
	
	//find out how long is current sentence_length
 	$sentence_length = sizeof($words);
//  	echo "\$sentence $sentence<br>";
// 	echo "\$sentence_length $sentence_length<br>";
	
	//remove the sentence ending punctuation, if it exists.
	$last_word = end($shifted_words);
	
	$last_particle = mb_substr($last_word, -1, 1);

	//if the last character in the sentence is a period, question mark, or exclamation mark, omit it.
	if ($last_particle == "."|| $last_particle == "!"|| $last_particle == "?"){

		//omit the last character from the end of the sentence (last word of the sentence).
		$last_word = mb_substr($last_word, 0, -1);
		
	}
	
	if ($sentence_length == 1 || $sentence_length == 0) {//if the sentence is just one word long, consider that word a verb		
		$my_m_verb = find_tense_for_adno( $last_word );
		if(isset($my_m_verb))$my_m_verb->two_word_tense= false;
	} else {

			
		
			//sentence has two or more words, use them to determine the tense.
			$second_last = $shifted_words[sizeof($shifted_words) - 2];		
			
			$my_m_verb = find_tense_for_adno($second_last . " " . $last_word);
		
		
			//since we just want to highlight the part of the verb which was
			//separated from the tense, if there is another word in the "mverb"
			//variable, we will remove it.
			
			//alot of the if statements depend on whether my_m_verb was set... combine them?
			if ( isset($my_m_verb) && mb_strpos($my_m_verb->mverb," ") ) {
				$my_m_verb->mverb = mb_ereg_replace("[^\s]+\s+", "", $my_m_verb->mverb);
				$my_m_verb->length = mb_strlen($my_m_verb->mverb);
				$my_m_verb->two_word_tense( false );
				
				
			} elseif ( isset($my_m_verb)) {
				//tense would have been like ~고 있다 or a tense containing a space.
				$my_m_verb->two_word_tense( true );
			} else return; //we couldn't match a tense to the verb, handle it as a single noun.
	} 

	//search for the verb (disconnected from the tense) in the dictionary.
	if($my_m_verb->two_word_tense) {
		//Using a two-word verb for the verb display
		$decomp_verb_and_tense = $decomp_words[sizeof($decomp_words) - 2] . " " . end($decomp_words);
	} else {
		//Using a one-word verb and tense for the verb display
		$decomp_verb_and_tense = end($decomp_words);
	}
	
	//Normalize the found tense separated from the verb
	$my_m_verb->verb_part = mb_substr($decomp_verb_and_tense, 0, $my_m_verb->length);
	$my_m_verb->tense_part = mb_substr($decomp_verb_and_tense, $my_m_verb->length);
		
	utf_normalizer::nfc($my_m_verb->verb_part);
	utf_normalizer::nfc($my_m_verb->tense_part);
	
	if($my_m_verb->english !=""){
	
	
 	}
	//if there is a third word that can be included in the verb search, add it.
	
	if ( $my_m_verb->two_word_tense && $sentence_length > 2 ) {
		//search for a definition, with the last 3 words of the sentence.
		//get the third last word from the sentence
		// 1 못 2 하고 3 있어요
		$rev_offset = 3;

	} else {
		//search for a definition with the last 1 or 2 words of the sentence.
		// 1 못 2 했어요
		$rev_offset = 2;
	}
	if ($sentence_length == 1){
		$rev_offset = 1;
	}
	

	//inter-verb particle count
	$iv_particle = 0;
	
	//searching for particles that can come between two-word verbs
	//$i can be 0, 1 or 2, this allows up to three particles
	for ( $i = 0, $check = $words[$sentence_length - $i - $rev_offset]; $i < 3; $i++, $check = $words[$sentence_length - $i - $rev_offset]) {
		//remove punctuation from $check
		$check = preg_replace("/[\p{P}\s]/u", "", $check);
		if(empty($check))break;
		switch ($check) {
			case "잘":
				$my_m_verb->jal = true;
				break;
			case "못":
				$my_m_verb->mot = true;
				break;
			case "안":
				$my_m_verb->an = true;
				break;
			/*case "꼭":
				$my_m_verb->ggog = true;
				break;*/	
			case "다시":
				$my_m_verb->dashi = true;
				break;	
			default:
				if(!find_adverb($check, $my_m_verb)) {
					$i = 3;
					$iv_particle--;
				}
			break;
		}	
		
		$iv_particle++;
	}
   
	$first_verb_word = $words[$sentence_length - $iv_particle - $rev_offset];

	if($iv_particle) $my_m_verb->iv_particle = $iv_particle;

	$last_particle = mb_substr($first_verb_word, -1, 1);

	//if the last character in the sentence is a period, question mark, or exclamation mark, omit it.
	
	if ($last_particle == "을"|| $last_particle == "를"|| $last_particle == "이" || $last_particle == "가" || $last_particle == "도"){
		//omit the last character from the end of the first verb word.
		$first_verb_word = mb_substr($first_verb_word, 0, -1);
		if ($last_particle == "도") $my_m_verb->do = true;
		$my_m_verb->removed_particle = true;
	}
	//the big verb is composed of the verb part removed from the tense part
	// and the word preceeding it.
	$first_verb_syllable = mb_substr($my_m_verb->verb_part, 0,1);

	$last_verb_syllable = mb_substr($my_m_verb->verb_part, -1);

	$verb_part_backup = $my_m_verb->verb_part;
	//need to remove and handle special particles connected to second verb word
	if (($first_verb_syllable == "잘" || $first_verb_syllable == "못") && ($last_verb_syllable == "해" || $last_verb_syllable == "하" || $last_verb_syllable == "되" || $last_verb_syllable == "돼")) {
		//need to remove at least the first syllable, maybe the second.
		$second_verb_syllable = mb_substr($my_m_verb->verb_part, 1,1);
		if ($second_verb_syllable == "잘" || $second_verb_syllable == "못") {
			$my_m_verb->jal = true;
			$my_m_verb->mot = true;
			$my_m_verb->verb_part = mb_substr($my_m_verb->verb_part, 2); 
		} elseif ($first_verb_syllable == "잘") {
			$my_m_verb->jal = true;
			$my_m_verb->verb_part = mb_substr($my_m_verb->verb_part, 1);
		} elseif ($first_verb_syllable == "못") {
			$my_m_verb->mot = true;
			$my_m_verb->verb_part = mb_substr($my_m_verb->verb_part, 1);
		} else echo "error!! unhandled pre verb word syllable.";
	}
	//***since "verb part" is now part of my_m_verb, it doesn't need to be passed seperately.

 	$bigverb = $first_verb_word . " " . $my_m_verb->verb_part;

	//need to fix printing for chal and mot in one word verbs. by changing the value for word start.
	if ($my_m_verb->conjugated) {
		k2e_con_verb($my_m_verb->verb_part, $bigverb, $my_m_verb);
		if($my_m_verb->english!=""){;
			$my_m_verb->bigverb = $bigverb;
		}
	} else {
		k2e_nocon_verb($my_m_verb->verb_part, $bigverb, $my_m_verb);
		if($my_m_verb->english!=""){
			$my_m_verb->bigverb = $bigverb;
		}
	}
	
	$my_m_verb->verb_part = $verb_part_backup;

	if ($my_m_verb->isdouble) { 
		//wherever the first verb word is
		$verb_start = $first_verb_word; 
	} else {
		//use IV particle value to set it up.
		$my_m_verb->removed_particle = false;
		
		if($my_m_verb->two_word_tense) {
			//second last word of the sentence
			$verb_start = $words[$sentence_length - 2 - $iv_particle];
		} else {
			//last word of the sentence
			$verb_start = $words[$sentence_length - 1 - $iv_particle];
		}
	}
	
	if ($my_m_verb->two_word_tense) $verb_and_tense_word = $words[$sentence_length - 2];
	else $verb_and_tense_word = $words[$sentence_length - 1];
	//*** this is a quick-fix solution to removed_particle being incorrectly set above.
		//set origin_offest because we need to keep for use second word of verb and preg_match
	$origin_offset=$offset;	
// 	$verb_start_pos=mb_strpos($orign_sentence, $verb_start, $offset);
	//find out starting posItion of verb	
	$my_m_verb->verb_start_pos=mb_strpos($orign_sentence, $verb_start, $offset);
 	//set result_pos for calculating offeset
 	$result_pos = $my_m_verb->verb_start_pos;
 							
 	//reset offset for next part search 
  	$offset = $result_pos + mb_strlen($verb_start);
 	
  	//set offest in my_m_verb object
	$my_m_verb->my_m_verb_offset = $offset;
 	//set second word position of verb in my_m_verb
  	$my_m_verb->verb_mid_pos = mb_strpos($orign_sentence, $verb_and_tense_word, $origin_offset);

  	//pattern for get adnominal patter plus 1 ,2 or 3 words from original sentence
	$pattern = "/$verb_and_tense_word(\s+[^\s\p{P}]+){1,3}/u";
	
	//it converts multi-byte offeset to byte offset because preg_match only take byte no multibyte
	$byte_offset = strlen(mb_substr($orign_sentence, 0, $origin_offset));
	
	//search patter from orignal sentence and ruturn string and btye position
	preg_match($pattern, $orign_sentence, $regs, PREG_OFFSET_CAPTURE, $byte_offset);
				//store matched garmmar pattern if it found
						
	//if found garmmar pattern is not empty. process below line
	if($regs[0]!=""){
 		$my_m_verb->possible_gp = $regs[0][0];
	}
	
	//check active/description verb and judge tense
	if($my_m_verb->ktype == 0 && ($my_m_verb->tense_part == "은" || $my_m_verb->tense_part == "ㄴ")){
		
		$my_m_verb->tense = "past";
	}
	
	//store object value as array
	$my_m_verb_array[]= $my_m_verb;

	//returnt result of process
	return $my_m_verb_array;
}

?>