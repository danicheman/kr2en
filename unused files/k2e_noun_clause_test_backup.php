<?php


		//unused: remove punctuation
// 		$to_search = preg_replace("/\p{P}+/u","", $to_search); 		


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
//This function adds a noun to another noun which may be separated by a space, nothing, or a hyphen
function add_mpart_noun (&$nc_array, $part1, $connector, $part2 , $search_code ) {
	switch($connector) {
		case 0: 
			$connector = " ";
		break;
		case 1:
			$connector = "-";
		break;
		case 2:
			$connector = "";
		break;
		default: 
		$connector = " ";
	}
 	if (!$nc_array) { 
		$nc_array[] = new noun_clause( $search_code, 1, $part1 . $connector . $part2 );
	} else {
		foreach($nc_array as $nc_element) $nc_element->can_add($search_code, 1, $part1 .$connector.$part2);
	}
}

/*
  (from here on use $nc_array)

1 Search the last word of this clause and the first word of the
  next clause.  It's possible to have a two word phrase that 
  contains a particle and was split.

2 If found, add it to the $nc_array 
    
4 Search words in groups from this clause with punctuation removed.
  Don't search for words that already have "english" set in them.

5 Search words in groups from this clause with punctuation and 
  particles removed.
  
6 Break down the words in groups and further break off suffixes and prefixes 
  from the word, and search individually. (Handle dates and words
  with numbers).
 
7 Return the nc_array.

*** not currently removing punctuation properly ***
*/

function k2e_new_noun_clause(&$this_clause, &$next_clause) {
	
	if (isset($this_clause["english"])) return true;
	
	$starting_code = 0;

	$word_count = no_of_clause_words($this_clause);
			
	//first handle special case for multi-word defs that span a particle (눈이 높은)
	if (isset($next_clause)) {
		
		$last_word = $this_clause[$word_count - 1];
		$first_word = $next_clause[0];

		//search the last word of this clause and first word of the next clause
		if($last_word->type != "punctuation" && $first_word->type != "punctuation") {
		
			$to_search = $last_word->word . $first_word->word;
	
			//remove leading / trailing spaces
			$to_search = trim($to_search);
				
			
				
			echo "here is combined clauses >$to_search< <br>";
		
			$query = "select * from trans_knouns as tk, trans_knoun_meanings as tkm WHERE tk.nin = tkm.nin AND (tk.noun1 = \"".$to_search."\" OR tk.noun2 = \"".$to_search."\")";
			$result = mysql_query($query); 
				
				
			if (mysql_num_rows($result)) {
				//found a result from the search with particles still on
				if($row = mysql_fetch_assoc($result)){ 
					//add the search result to the last word of the match, set other words->english to true.
						$next_clause[0]->english = $row["meaning"];
						$starting_code += pow(2,$last_word_pos);
				}
			} else {
	
				//search again after removing pa rticles
		 		$to_search = mb_ereg_replace( "(?!\s)(에?는|은|(?<!많)이|가|을(?!\s때)|를|에|에서|으?로|엔)\b", "", $to_search ); //take out the endings
		 		
				echo "here is combined clauses after particle removal $to_search <br>";
				$query = "select * from trans_knouns as tk, trans_knoun_meanings as tkm WHERE tk.nin = tkm.nin AND (tk.noun1 = \"".$to_search."\" OR tk.noun2 = \"".$to_search."\")";
				$result = mysql_query($query); 
				echo "This is the query: $query <br><br>";
				if (mysql_num_rows($result)) {
					//found a result from the first search
					if($row = mysql_fetch_assoc($result)){ 
						$this_clause[$last_word_pos]->english = true;
						$next_clause[0]->english = $row["meaning"];
						$starting_code += pow(2,$last_word_pos);
					}
				}
			}
		} 
	}//end over-particle search
	
		//mark the words if they are a part of the conjunction in this clause
	if(isset($this_clause["conj"])) {
		
		//find the word which is the beginning of the conjunction
		$conj_start_pos = get_clause_pos($this_clause, $this_clause["conj"]->m_verb->verb_start_pos);
		
		//find the word which is the end of the conjunction
		$conj_end_pos = get_clause_pos($this_clause, $this_clause["conj"]->conj_pos + $this_clause["conj"]->conj_length - 1);
		
		//the conjunction code contains definitions for this range of words
		for ($i = $conj_start_pos; $i <= $conj_end_pos; $i++) {
// 			echo $this_clause[$i]->word ."<br>";
			$this_clause[$i]->english = true;
			if ($starting_code ^ pow(2,$i)) $starting_code += pow(2,$i);
			
		}
		
			
		
	}//TO DO: else  IF IT'S A GRAMMAR PATTERN... OR ALREADY DEFINED 'HO-CHING'...
	
	
	for ($i = 0; $i < $word_count; $i++) {
		
		//"word" objects that will not be part of the search, these include english, numbers, 
		if( isset($this_clause[$i]->english) || $this_clause[$i]->type == 'punctuation' || 
			$this_clause[$i]->type == 'english' || $this_clause[$i]->type == 'internet address' || 
			$this_clause[$i]->type == 'email address' || $this_clause[$i]->type == 'number' ||
			$this_clause[$i]->type == 'verb word' ) {
			
			//add this word to the 'starting code' so that it isn't part of the search
			if ($starting_code ^ pow(2,$i)) $starting_code += pow(2,$i);
			
		}
	}
	
	/* 
		End of special cases
	-------------------------------------
	  after this point conjunctions and over-particle cases have been handled
	  now we go into a regular search, using the starting code we made.
	*/  
	
		for ($i = 2; $i>-1;$i--) {//search using groups of 3 down to 1 words.  $i goes from 2 to 0.
		$pointer = 0;

		//search using groups of $i words, be careful not to search over 12 words
		//so the program won't slow down, this is the maximum length for a noun clause.
		if($i < $word_count && $word_count < 12) {
			while( $this_clause[$pointer + $i]->word ) {
				$search_code = 0;	
				
				//LIMITATION: cannot find numbers or english in between hangul words 이2002년
				
				//before the search string is built, remove decimal numbers or english from hangul
				if($i==0 && mb_ereg("([가-힣]+([A-z0-9\.]+))|(([A-z0-9\.]+)[가-힣]+)", $this_clause[$pointer]->word)) {
					//remove everything before the word, and everything after.
					$post_mixed = mb_ereg_replace("(([A-z0-9\.]*)[가-힣]+)", "", $this_clause[$pointer]->word);
					$pre_mixed = mb_ereg_replace("([가-힣]+([A-z0-9\.]*))", "", $this_clause[$pointer]->word);
					$this_clause[$pointer]->word = mb_ereg_replace("([A-z0-9\.]+)", "", $this_clause[$pointer]->word);
// 					echo "here's the backup: " . $post_mixed . "and also " . $pre_mixed . "and here's the filtered word: " . $this_clause[$pointer]->word . "\n";
				} else {
					unset($post_mixed);
					unset($pre_mixed);
				}
				
				//calculate values for the noun words
				for ($j = 0; $j <= $i; $j++) {
					$search_string .= $this_clause[$pointer+$j]->word;
					$search_code += pow(2,$pointer+$j);
				}
				if($this_clause[$pointer+$j-1]->with_oe) {
// 					echo "oe true\n";
					$with_oe = true;
					$with_ro = false;
				} elseif($this_clause[$pointer+$j-1]->with_ro) {
// 					echo "ro true\n";

					$with_ro = true;
					$with_oe = false;
			    } else {
// 				    echo "both false\n";
					$with_oe = false;
					$with_ro = false;
				}
				
				
// 				echo "Will we search for: $search_string?<br>  ";
				
				//NAND of starting code with search code.
				if(!($starting_code & $search_code) != 0) {
// 					echo "YES<br>";
				//find english words and numbers not attached to words then add them if they didn't match part of a larger word
				
				if ($i==0 && ereg('[A-Za-z0-9\%\.]', $this_clause[$pointer]->word)) {
					if (!$nc_array) { 
	 					$nc_array[] = new noun_clause( $search_code, $i + 1, $this_clause[$pointer]->word);
	 				} else {
		 				foreach($nc_array as $nc_element) {
			 				//echo "can_add $search_code $i ". $row["meaning"] . "\n";
			 				$nc_element->can_add($search_code, $i + 1, $this_clause[$pointer]->word);
		 				}
		 				if ($i >0) { 
			 				$nc_array[] = new noun_clause( $search_code, $i + 1, $this_clause[$pointer]->word);
		 				}
	 				}
				}
// 				echo "start code: $starting_code and search code: $search_code<br><br>";
				
					//since we're throwing away the search string, it's fine to escape any slashes that may be included.
					$search_string = addslashes($search_string);
					$search_string = trim($search_string);
					echo "searching for >$search_string<<br>";
					//search here		
					$query = "select * from trans_knouns as tk, trans_knoun_meanings as tkm WHERE tk.nin = tkm.nin AND (tk.noun1 = \"".$search_string."\" OR tk.noun2 = \"".$search_string."\")";
					$result = mysql_query($query); 
					
					if (mysql_num_rows($result)) {
						//found a result from the first search
						while($row = mysql_fetch_assoc($result)) { 
							
							if($with_oe && $row["with_oe"]) $meaning = $row["with_oe"];
							elseif($with_ro && $row["with_ro"])$meaning = $row["with_ro"];
							else $meaning = $row["meaning"];
							if(isset($pre_mixed) || isset($post_mixed)) {
	 							$meaning = $pre_mixed ." ". $meaning ." ". $post_mixed;
 							}							
							
							echo "meaning is $meaning\n";

							if (!$nc_array) { 
			 					$nc_array[] = new noun_clause( $search_code, $i + 1, $meaning);
			 				} else {
				 				foreach($nc_array as $nc_element) {
					 				$nc_element->can_add($search_code, $i + 1, $meaning);
				 				}
				 				if ($i >0) { 
					 				$nc_array[] = new noun_clause( $search_code, $i + 1, $meaning);
				 				}
			 				}

		 					$query2 = "SELECT * FROM trans_knoun_translation as tkt WHERE tkt.nin = ".$row["nin"] . " LIMIT 1";
// 							echo "here is query2: " . $query2 . "<br>";
		 					$result2 = mysql_query($query2);
		 					if ($result2 && mysql_num_rows($result2)) { // if result is not equal to 0 rows
		 						$row2 = mysql_fetch_assoc($result2);
		 						print_r($row2);
		 						//found some rules, add them to the object.
							}
							
// 							Unneccessary now..	 						
// 	 						if(isset($pre_mixed) || isset($post_mixed)) {
// 		 						$this_clause[$pointer]->word = $pre_mixed . $this_clause[$pointer]->word . $post_mixed;
// 		 						unset($pre_mixed);
// 		 						unset($post_mixed);
// 	 						}

						}
					} else {
						//strip particles and search again
						$search_string = mb_ereg_replace("(은|(에서?)?는|이|가|을|를|에서|에|으?로|엔)$","", $search_string); //take out the endings
// 						$search_string = mb_ereg_replace("[\"\'\,]","", $search_string); //take out the punctuation
						echo "searching for $search_string<br>";
											
						$query = "select * from trans_knouns as tk, trans_knoun_meanings as tkm WHERE tk.nin = tkm.nin AND ( tk.noun1 = \"".$search_string."\" OR tk.noun2 = \"".$search_string."\")";
						echo "here's the query: <br>$query<br>";
						$result = mysql_query($query); 
					
						if (mysql_num_rows($result)) {
							while($row = mysql_fetch_assoc($result)){ 
								
								if($with_oe && $row["with_oe"]) $meaning = $row["with_oe"];
								elseif($with_ro && $row["with_ro"])$meaning = $row["with_ro"];
								else $meaning = $row["meaning"];
								if(isset($pre_mixed) || isset($post_mixed)) {
		 							$meaning = $pre_mixed ." ". $meaning ." ". $post_mixed;
	 							}
								echo "meaning is $meaning\n";
								if (!$nc_array) { 
				 					$nc_array[] = new noun_clause( $search_code, $i + 1, $meaning);
				 				} else {
					 				foreach($nc_array as $nc_element) {
						 				$nc_element->can_add($search_code, $i + 1, $meaning);
					 				}
					 				if ($i >0) { 
						 				$nc_array[] = new noun_clause( $search_code, $i + 1, $meaning);
					 				}
				 				}	
	 						}
	 						
		 					$query2 = "SELECT * FROM trans_knoun_translation as tkt WHERE tkt.nin = ".$row["nin"] . " LIMIT 1";
// 							echo "here is query2: " . $query2 . "<br>";
		 					$result2 = mysql_query($query2);
		 					if ($result2 && mysql_num_rows($result2)) { // if result is not equal to 0 rows
		 						$row2 = mysql_fetch_assoc($result2);
		 						print_r($row2);
		 						//found some rules, add them to the object.
							}
								 						
	 						if(isset($pre_mixed) || isset($post_mixed)) {
		 						$this_clause[$pointer]->word = $pre_mixed . $this_clause[$pointer]->word . $post_mixed;
		 						unset($pre_mixed);
		 						unset($post_mixed);
	 						}
	 						print_r($nc_array);
 						}
					}
 					
				} 
				unset($search_string);
				$search_code = 0;
				$pointer++;
			}
		}		
	}
//inserted here.
		for ($i = 0; $i< $word_count; $i++) {
		if ($nc_array[$i]->value == $word_count) $can_return = true;
	}
	
	if($can_return) {
		echo "can_return is true";
		for ($i = 0; $i< $word_count; $i++) {
			if ($nc_array[$i]->value < $word_count) unset($nc_array[$i]);
		}
		
		$nc_array = array_values($nc_array);
		print_r($nc_array);
		$counter = 0;
		$nc_code = $nc_array[0]->code;
		while ( $nc_code ) {
			if( $nc_code % 2 ) echo "\ncounter is $counter power is $power\n";
			$nc_code = $nc_code>>1;
			$power = pow(2,$counter);
			$this_clause[$counter]->english = $nc_array[0]->english[$power];
			$counter++;
		}
		return true;
	}

	//search taking appart individual words

	for($i = 0; $i < $word_count; $i++) {
		$search_code = 0;
		$to_split_search = $this_clause[$i]->word;
		$to_split_search = trim($to_split_search);
		$to_split_search = addslashes($to_split_search);	
		$word_length = mb_strlen($to_split_search, "utf8");
		
		if ($word_length >3 && $this_clause[$i]->type != "punctuation" && !isset($this_clause[$i]->english)) {
			echo $to_split_search . " and wordlength is: " . $word_length . "\n\n";
			//separate two particles from the front and back
			
			//search front two
			$query_part = "select * from trans_kfront_part as fp WHERE fp.particle = \"".mb_substr($to_split_search, 0,2) ."\" ";
			$result_part = mysql_query($query_part); 
			if($fetch_part = mysql_fetch_array($result_part)){ 
				//at this point, we've matched a length 2 front particle, try to find the noun.
				$query_noun = "select * from trans_knouns as tk, trans_knoun_meanings as tkm WHERE tk.nin = tkm.nin AND (tk.noun1 = \"". mb_substr($to_split_search, 2) ."\" OR tk.noun2 = \"". mb_substr($to_split_search, 2) ."\")"; 

				$result_noun = mysql_query($query_noun); 
				if($fetch_noun = mysql_fetch_array($result_noun)){ 
					$search_code += pow(2,$i);
					add_mpart_noun ($nc_array, $fetch_part['meaning'], $fetch_part['connector'], $fetch_noun['meaning'], $search_code);
					continue;
 				}
			}
			
			//search back two			
			$query_part = "select * from trans_kback_part as fb WHERE fb.particle = \"".mb_substr($to_split_search, $word_length-2) ."\" ";
			$result_part = mysql_query($query_part); 
			if($fetch_part = mysql_fetch_array($result_part)){ 
				//at this point, we've matched a length 2 back particle, try to find the noun.
				$query_noun = "select * from trans_knouns as tk, trans_knoun_meanings as tkm WHERE tk.nin = tkm.nin AND (tk.noun1 = \"". mb_substr($to_split_search, 0, $word_length-2) ."\" OR tk.noun2 = \"". mb_substr($to_split_search, 0, $word_length-2) ."\") ";
				$result_noun = mysql_query($query_noun); 
				if($fetch_noun = mysql_fetch_array($result_noun)){ 
					$search_code += pow(2,$i);
					add_mpart_noun ($nc_array, $fetch_noun['meaning'], $fetch_part['connector'], $fetch_part['meaning'], $search_code);
					continue;
 				}
			}
		}
		if ($word_length >1 && $this_clause[$i]->type != "punctuation" && !isset($this_clause[$i]->english)) {
			
// 			echo $to_split_search . " and wordlength is: " . $word_length . "\n\n";
			
			
			//echo "w[".$i."]"."[0]=" . mb_substr($to_split_search, 0,1);
			$query_part = "select * from trans_kfront_part as fp WHERE fp.particle = \"".mb_substr($to_split_search, 0,1) ."\" ";
			$result_part = mysql_query($query_part); 
// 			echo "a query: $query_part <br> a result: $result_part";
			if($fetch_part = mysql_fetch_array($result_part)){ 
				//at this point, we've matched a length 1 front particle, try to find the noun.
				$query_noun = "select * from trans_knouns as tk, trans_knoun_meanings as tkm WHERE tk.nin = tkm.nin AND (tk.noun1 = \"". mb_substr($to_split_search, 1) ."\" OR tk.noun2 = \"". mb_substr($to_split_search, 1) ."\") ";
				$result_noun = mysql_query($query_noun); 
				if($fetch_noun = mysql_fetch_array($result_noun)){ 
					$search_code += pow(2,$i);
					add_mpart_noun ($nc_array, $fetch_part['meaning'], $fetch_part['connector'], $fetch_noun['meaning'], $search_code); 
					continue;
 				}
			}
			$query_part = "select * from trans_kback_part as fb WHERE fb.particle = \"".mb_substr($to_split_search, $word_length-1) ."\" ";
			//echo "here's the query yall: $query_part<br>";
			$result_part = mysql_query($query_part);
			if($fetch_part = mysql_fetch_array($result_part)){ 
				//at this point, we've matched a length 1 front particle, try to find the noun.
				
				$query_noun = "select * from trans_knouns as tk, trans_knoun_meanings as tkm WHERE tk.nin = tkm.nin AND (tk.noun1 = \"". mb_substr($to_split_search, 0,$word_length - 1) ."\"OR tk.noun2 = \"". mb_substr($to_split_search, 0,$word_length - 1) ."\") ";
				//echo "here's the query yall: $query_noun<br>";
				$result_noun = mysql_query($query_noun); 
				if($fetch_noun = mysql_fetch_array($result_noun)){ 
						
					$search_code += pow(2,$i);
					add_mpart_noun ($nc_array, $fetch_noun['meaning'], $fetch_part['connector'], $fetch_part['meaning'], $search_code); 
					continue;
 				}
			}
			

// 			echo "   w[".$i."]"."[last]=" . mb_substr($to_split_search, $word_length-1,1) . "\n\n";
		}
	}
	
			$counter = 0;
			$nc_code = $nc_array[0]->code;
			while ( $nc_code ) {
				if( $nc_code % 2 ) echo "counter is $counter power is $power\n";
				$nc_code = $nc_code>>1;
				$power = pow(2,$counter);
				$this_clause[$counter]->english = $nc_array[0]->english[$power];
				$counter++;
			}
	
	if($nc_array) return true;
}

