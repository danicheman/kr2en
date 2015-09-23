<?php
class noun_clause {
	function can_add($sc, $v, $e) {
// 		echo "can add ( $sc , $v , $e )<br>";
		if (!($this->code & $sc)) {
			$this->code += $sc;//add the search code to the nc's code
			$this->value += $v;//add the search value to the nc's search value
			$this->english[$sc] = $e;//add the english word
			ksort($this->english);
			return true;
		} else return false;
	}
	function __construct($sc, $v, $e) {
		$this->code = $sc;
		$this->value = $v;
		$this->english[$sc] = $e;
	}
}
/* Explanation of variables:
 *
 * $starting code: this variable is the total from the "power of 2" values for 
 * all the words in the clause which were defined BEFORE "new noun clause" was
 * called.  In new noun clause, we assume that if we translated something 
 * outside the function, that that translation is correct.
 *
 */
function k2e_new_noun_clause(&$clause_object) {
	
	if (isset($clause_object["english"])) return true;

	//create power of 2 code for words which have an existing translation
	if(isset($clause_object["conj"])) {
		
		//find the word which is the beginning of the conjunction
		$conj_start_pos = get_clause_pos($clause_object, $clause_object["conj"]->m_verb->verb_start_pos);
		$starting_code = 0;
		//find the word which is the end of the conjunction
		$conj_end_pos = get_clause_pos($clause_object, $clause_object["conj"]->conj_pos + $clause_object["conj"]->conj_length - 1);
		echo "not searching for: ";
		//the conjunction code contains definitions for this range of words
		for ($i = $conj_start_pos; $i <= $conj_end_pos; $i++) {
			echo $clause_object[$i]->word ."<br>";
// 			since we have the object sentence this isn't necessary
// 			$words .= " " + $clause_object[$i]->word;
			$clause_object[$i]->english = true;
			$starting_code += pow(2,$i);
			
		}
		
		echo "here is clause code " . $starting_code;
			
		
	}//TO DO: else  IF IT'S A GRAMMAR PATTERN... OR ALREADY DEFINED 'HO-CHING'...
	
	//this returns ONLY the number of words in the CLAUSE
	$word_count = no_of_clause_words($clause_object);
	
	for ($i = 0; $i < $word_count; $i++) {
// 		echo "ALSO NOT SEARCHING FOR: ";
		//"word" objects that will not be part of the search, these include english, numbers, 
		if( isset($clause_object[$i]->english) || $clause_object[$i]->type == 'punctuation' || 
		$clause_object[$i]->type == 'english' || $clause_object[$i]->type == 'internet address' || 
		$clause_object[$i]->type == 'email address' || $clause_object[$i]->type == 'number' ||
		$clause_object[$i]->type == 'verb word' ) {
			
			//add this word to the 'starting code' so that it isn't part of the search
			//need to "xor"
			if ($starting_code ^ pow(2,$i)) {
				echo "not: ";
				echo $clause_object[$i]->word ."<br>";
				$starting_code += pow(2,$i);
			}
			
		}
	}
	$clause_object["code"] = $starting_code;
	//if the last object has a particle, and it hasn't been defined
	if ($clause_object[$word_count - 1]->type == 'noun + particle' && $starting_code < pow(2,$word_count-1)) {
		//this is the last word in the clause
		if( $clause_object["type"] == "verbmod" ) {
			//에 or 에서
			$clause_object[$word_count - 1]->with_oe = true;
		} 
		if ( $clause_object["type"] == "topic" ) {
			//check second particle of last word
// 			echo "here's the particle to check: " . mb_substr($clause_object[$word_count -1]->word, -2,1). "\n\n\n\n";
			if("에" == mb_substr($clause_object[$word_count -1]->word, -2,1)) {
				//에는
				$clause_object[$word_count - 1]->with_oe = true;
			}
		}
			
				
			
		
	}	
	
// 	$stripped_noun_clause = mb_ereg_replace("(은|에?는|이|가|을|를|)$","", $noun_clause); //take out the endings
// 	$stripped_noun_clause = mb_ereg_replace("[\"\'\,]","", $stripped_noun_clause); //take out the punctuation
// 	$stripped_noun_clause = stripslashes($stripped_noun_clause); //strip slashes
	//do the search, keeping in mind the start code and that the words are 
	//already separated
	for ($i = 2; $i>-1;$i--) {//search using groups of 3 down to 1 words.  $i goes from 2 to 0.
		$pointer = 0;

// 		echo "i is $i<br>";
		//search using groups of $i words, be careful not to search over 12 words
		//so the program won't slow down, this is the maximum length for a noun clause.
		if($i < $word_count && $word_count < 12) {
			while( $clause_object[$pointer + $i]->word ) {
				$search_code = 0;	
				
				//LIMITATION: cannot find numbers or english in between hangul words 이2002년
				
				//before the search string is built, remove decimal numbers or english from hangul
				if($i==0 && mb_ereg("([가-힣]+([A-z0-9\.]+))|(([A-z0-9\.]+)[가-힣]+)", $clause_object[$pointer]->word)) {
					//remove everything before the word, and everything after.
					$post_mixed = mb_ereg_replace("(([A-z0-9\.]*)[가-힣]+)", "", $clause_object[$pointer]->word);
					$pre_mixed = mb_ereg_replace("([가-힣]+([A-z0-9\.]*))", "", $clause_object[$pointer]->word);
					$clause_object[$pointer] = mb_ereg_replace("([A-z0-9\.]+)", "", $clause_object[$pointer]->word);
					echo "here's the backup: " . $post_mixed . "and also " . $pre_mixed . "and here's the filtered word: " . $clause_object[$pointer] . "\n";
				}
				//calculate values for the noun words
				for ($j = 0; $j <= $i; $j++) {
					$search_string .= $clause_object[$pointer+$j]->word;
					$search_code += pow(2,$pointer+$j);
				}
				echo "Will we search for: $search_string?  ";
				
				if(!($starting_code & $search_code) != 0) {
					echo "YES<br>";
				//find english words and numbers not attached to words then add them if they didn't match part of a larger word
				
				if ($i==0 && ereg('[A-Za-z0-9\%\.]', $clause_object[$pointer]->word)) {
					if (!$nc_array) { 
	 					$nc_array[] = new noun_clause( $search_code, $i + 1, $clause_object[$pointer]->word);
	 				} else {
		 				foreach($nc_array as $nc_element) {
			 				//echo "can_add $search_code $i ". $row["meaning"] . "\n";
			 				$nc_element->can_add($search_code, $i + 1, $clause_object[$pointer]->word);
		 				}
		 				if ($i >0) { 
			 				$nc_array[] = new noun_clause( $search_code, $i + 1, $clause_object[$pointer]->word);
		 				}
	 				}
				}
// 				echo "start code: $starting_code and search code: $search_code<br><br>";
				
					//since we're throwing away the search string, it's fine to escape any slashes that may be included.
					$search_string = addslashes($search_string);
					echo "searching for $search_string<br>";
					//search here		
					$query = "select * from trans_knouns as tk, trans_knoun_meanings as tkm WHERE tk.nin = tkm.nin AND tk.noun1 = \"".$search_string."\"";
					$result = mysql_query($query); 
					
					if (mysql_num_rows($result)) {
						//found a result from the first search
						while($row = mysql_fetch_assoc($result)){ 
		 					print_r($row); 
		 					$query2 = "SELECT * FROM trans_knoun_translation as tkt WHERE tkt.nin = ".$row["nin"] . " LIMIT 1";
	// 	 					echo "here is query2: " . $query2 . "<br>";
		 					$result2 = mysql_query($query2);
		 					if ($result2 && mysql_num_rows($result2)) { // if result is not equal to 0 rows
		 						$row2 = mysql_fetch_assoc($result2);
		 						print_r($row2);
		 						//found some rules, add them to the object.
							}
						}
					} else {
						//strip particles and search again
						$search_string = mb_ereg_replace("(은|에?는|이|가|을|를|)$","", $search_string); //take out the endings
						$search_string = mb_ereg_replace("[\"\'\,]","", $search_string); //take out the punctuation
						echo "searching for $search_string<br>";
											
						$query = "select * from trans_knouns as tk, trans_knoun_meanings as tkm WHERE tk.nin = tkm.nin AND tk.noun1 = \"".$search_string."\"";
// 						echo "here's the query: <br>$query<br>";
						$result = mysql_query($query); 
					
						if (mysql_num_rows($result)) {
							while($row = mysql_fetch_assoc($result)){ 
								echo "found result with search code $search_code\n";
		 						print_r($row); 
	 						}	
 						}
					}
					if ($row) {
						if (!$nc_array) { 
		 					$nc_array[] = new noun_clause( $search_code, $i + 1, $words[$pointer]);
		 				} else {
			 				foreach($nc_array as $nc_element) {
				 				//echo "can_add $search_code $i ". $row["meaning"] . "\n";
				 				$nc_element->can_add($search_code, $i + 1, $words[$pointer]);
			 				}
			 				if ($i >0) { 
				 				$nc_array[] = new noun_clause( $search_code, $i + 1, $words[$pointer]);
			 				}
		 				}
 					}

				} else echo "NO<br>";
				unset($search_string);
				$search_code = 0;
				$pointer++;
			}
		}		
	}
	return $nc_array;
}
?>