<?php
include("connect.php"); 

	mb_internal_encoding( 'UTF-8' );
	mb_regex_encoding('utf-8');
	mysql_query("SET NAMES 'utf8'");
	
	echo "<pre>";
//print_r(k2e_noun("대우 누비라의 경우 누비라1.5 오토 ABS 미장착 차량은"));
//print_r(k2e_noun("햇사과는"));
print_r(k2e_noun("예상치인 20% 수준을"));

//good up to 31 words, noun clause contains words and 
class noun_clause {
	function can_add($sc, $v, $e) {
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
function k2e_noun($noun_clause) {
	/*	
		add adjective-form verb parser (will be done outside of this function.  
		This program is designed already to handle english words).  As well, 
		we will need to test for this outside the function anyway.
	*/
	
	//search multiple words, then search
	$nc_array=NULL;
	//search entire noun
	echo "$noun_clause <br>";
	rtrim($noun_clause);
	$stripped_noun_clause = preg_replace("/(은|는|이|가|을|를)$/um","", $noun_clause);
	$words = preg_split('/ /', $stripped_noun_clause, -1, PREG_SPLIT_NO_EMPTY);
	
	for ($i = 2; $i>-1;$i--) {//search using groups of 3 down to 1 words.  $i goes from 2 to 0.
		$pointer = 0;


		//search using groups of $i words, be careful not to search over 12 words
		//so the program won't slow down, this is the maximum length for a noun clause.
		if(($words_length = sizeof($words)) > $i && $words_length < 12) {
			while($words[$pointer+$i]) {
				$search_code = 0;	
				
				//before the search string is built, remove decimal numbers or english from hangul
				if($i==0 && mb_ereg("([가-힣]+([A-z0-9\.]+))|(([A-z0-9\.]+)[가-힣]+)", $words[$pointer])) {
					//remove everything before the word, and everything after.
					$post_mixed = mb_ereg_replace("(([A-z0-9\.]*)[가-힣]+)", "", $words[$pointer]);
					$pre_mixed = mb_ereg_replace("([가-힣]+([A-z0-9\.]*))", "", $words[$pointer]);
					$words[$pointer] = mb_ereg_replace("([A-z0-9\.]+)", "", $words[$pointer]);
					//echo "here's the backup: ". $post_mixed . "and also " . $pre_mixed ."and here's the filtered word: " . $words[$pointer] . "\n";
				}
				
				//calculate values for the noun words
				for ($j = 0; $j <= $i; $j++) {
					if($j>0)$search_string .= " ";
					$search_string .= $words[$pointer+$j];
					$search_code += pow(2,$pointer+$j);
				}

				//find english words and numbers not attached to words then add them if they didn't match part of a larger word
				
				if ($i==0 && ereg('[A-Za-z0-9\%\.]', $words[$pointer])) {
					if (!$nc_array) { 
	 					$nc_array[] = new noun_clause( $search_code, $i + 1, $words[$pointer] );
	 				} else {
		 				foreach($nc_array as $nc_element) {
			 				//echo "can_add $search_code $i ". $row["meaning"] . "\n";
			 				$nc_element->can_add($search_code, $i + 1, $words[$pointer]);
		 				}
		 				if ($i >0) { 
			 				$nc_array[] = new noun_clause( $search_code, $i + 1, $words[$pointer] );
		 				}
	 				}
				}
				

				
				//search here		
				$query = "select * from trans_knouns as tk, trans_knoun_meanings as tkm WHERE tk.nin = tkm.nin AND tk.noun1 = \"".$search_string."\" ";
				$result = mysql_query($query); 
				//echo "$query \n";
				while($row = mysql_fetch_array($result)){ 
 					//print_r($row); 
 					if($pre_mixed || $post_mixed) {
	 					$row["meaning"] = $pre_mixed . $row["meaning"] . $post_mixed;
	 					unset($pre_mixed);
	 					unset($post_mixed);
 					}
 					if (!$nc_array) { 
	 					$nc_array[] = new noun_clause( $search_code, $i + 1, $row["meaning"] );
	 				} else {
		 				foreach($nc_array as $nc_element) {
			 				$nc_element->can_add($search_code, $i + 1, $row["meaning"]);
		 				}
		 				if ($i >0) { 
			 				$nc_array[] = new noun_clause( $search_code, $i + 1, $row["meaning"] );
		 				}
	 				}
 					//print_r($nc_array);
				}

				unset($search_string);
				$pointer++;
				
			}
		}
	}
//if: max ( value ) == $words_length  return the maximum value elements, delete the rest.
//else: do not delete, do not return, continue to do particle search.

	for ($i = 0; $i< $words_length; $i++) {
		if ($nc_array[$i]->value == $words_length) $can_return = true;
	}
	
	if($can_return) {
		for ($i = 0; $i< $words_length; $i++) {
			if ($nc_array[$i]->value < $words_length) unset($nc_array[$i]);
		}
		return array_values($nc_array);
	}

	//search taking appart individual words

	for($i = 0; $i < $words_length; $i++) {
		$search_code = 0;
		$word_length = mb_strlen($words[$i], "utf8");
		//echo $words[$i] . " and wordlength is: " . $word_length . "\n\n";
		if ($word_length >3) {
			//separate two particles from the front and back
			
			//search front two
			$query_part = "select * from trans_kfront_part as fp WHERE fp.particle = \"".mb_substr($words[$i], 0,2) ."\" ";
			$result_part = mysql_query($query_part); 
			if($fetch_part = mysql_fetch_array($result_part)){ 
				//at this point, we've matched a length 2 front particle, try to find the noun.
				$query_noun = "select * from trans_knouns as tk, trans_knoun_meanings as tkm WHERE tk.nin = tkm.nin AND tk.noun1 = \"". mb_substr($words[$i], 2) ."\" ";
				$result_noun = mysql_query($query_noun); 
				if($fetch_noun = mysql_fetch_array($result_noun)){ 
					$search_code += pow(2,$i);
					add_mpart_noun ($nc_array, $fetch_part['meaning'], $fetch_part['connector'], $fetch_noun['meaning'], $search_code);
					continue;
 				}
			}
			
			//search back two			
			$query_part = "select * from trans_kback_part as fb WHERE fb.particle = \"".mb_substr($words[$i], $word_length-2) ."\" ";
			$result_part = mysql_query($query_part); 
			if($fetch_part = mysql_fetch_array($result_part)){ 
				//at this point, we've matched a length 2 back particle, try to find the noun.
				$query_noun = "select * from trans_knouns as tk, trans_knoun_meanings as tkm WHERE tk.nin = tkm.nin AND tk.noun1 = \"". mb_substr($words[$i], 0, $word_length-2) ."\" ";
				$result_noun = mysql_query($query_noun); 
				if($fetch_noun = mysql_fetch_array($result_noun)){ 
					$search_code += pow(2,$i);
					add_mpart_noun ($nc_array, $fetch_noun['meaning'], $fetch_part['connector'], $fetch_part['meaning'], $search_code);
					continue;
 				}
			}
			
			//search front one
			//echo "w[".$i."]"."[0 & 1]=" . mb_substr($words[$i], 0,2);

			//echo "   w[".$i."]"."[second last & last]=" . mb_substr($words[$i], $word_length-2) . "\n";
		}
		if ($word_length >1) {
			
			
			
			
			//echo "w[".$i."]"."[0]=" . mb_substr($words[$i], 0,1);
			$query_part = "select * from trans_kfront_part as fp WHERE fp.particle = \"".mb_substr($words[$i], 0,1) ."\" ";
			$result_part = mysql_query($query_part); 
			if($fetch_part = mysql_fetch_array($result_part)){ 
				//at this point, we've matched a length 1 front particle, try to find the noun.
				$query_noun = "select * from trans_knouns as tk, trans_knoun_meanings as tkm WHERE tk.nin = tkm.nin AND tk.noun1 = \"". mb_substr($words[$i], 1) ."\" ";
				$result_noun = mysql_query($query_noun); 
				if($fetch_noun = mysql_fetch_array($result_noun)){ 
					$search_code += pow(2,$i);
					add_mpart_noun ($nc_array, $fetch_part['meaning'], $fetch_part['connector'], $fetch_noun['meaning'], $search_code); 
					continue;
 				}
			}
			$query_part = "select * from trans_kback_part as fb WHERE fb.particle = \"".mb_substr($words[$i], $word_length-1) ."\" ";
			$result_part = mysql_query($query_part);
			if($fetch_part = mysql_fetch_array($result_part)){ 
				//at this point, we've matched a length 1 front particle, try to find the noun.
				
				$query_noun = "select * from trans_knouns as tk, trans_knoun_meanings as tkm WHERE tk.nin = tkm.nin AND tk.noun1 = \"". mb_substr($words[$i], 0,$word_length - 1) ."\" ";
				$result_noun = mysql_query($query_noun); 
				if($fetch_noun = mysql_fetch_array($result_noun)){ 
						
					$search_code += pow(2,$i);
					add_mpart_noun ($nc_array, $fetch_noun['meaning'], $fetch_part['connector'], $fetch_part['meaning'], $search_code); 
					continue;
 				}
			}
			

			//echo "   w[".$i."]"."[last]=" . mb_substr($words[$i], $word_length-1,1) . "\n\n";
		}
	}
	return $nc_array;
}
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
?></pre>