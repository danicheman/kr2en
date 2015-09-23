<?php
	mb_internal_encoding( 'UTF-8' );
	mb_regex_encoding('utf-8');
	
	include("utf/utf_normalizer.php");
	include("jamo_con.php");
	include("verb_matching.php");
	include("connect.php"); 	
	include("matching.php");
	
	mysql_query("SET NAMES 'utf8'");
		
	class m_verb {
		function english($e, $w) {
			$this->english = $e;
			$this->isdouble = $w; // was the verb located by searching for two words?
		}
		function double_trans($dt) {
			$this->double_trans = $dt;
		}
		function __construct($t, $mv, $l) {
				$this->tense = $t;
				$this->mverb = $mv;
				$this->length = $l;
		}
	}

	
	//촬영이 시작되고, 광고멘트에는 \"저도 이 상품을 구입했습니다\"라는 문구가 들어있는 것을 발견한 그는 그 자리에서 해당 부분을 수정해 줄 것을 
	$sentance = "촬영이 시작되고, 광고멘트에는 \"저도 이 상품을 구입했습니다\"라는 문구가 들어있는 것을 발견한 그는 그 자리에서 해당 부분을 수정해 줄 것을 부탁했다.";
	
	//create decomposed sentance
	$decomp_sentance = $sentance;
	utf_normalizer::nfd(&$decomp_sentance);
	
	//create shifted sentance
	$shifted_sentance = jamo_to_co_jamo($decomp_sentance);
	
	//break sentance into an array of words
	$words = preg_split('/ /', $sentance, -1, PREG_SPLIT_NO_EMPTY);
	$decomp_words = preg_split('/ /', $decomp_sentance, -1, PREG_SPLIT_NO_EMPTY);
	$shifted_words = preg_split('/ /', $shifted_sentance, -1, PREG_SPLIT_NO_EMPTY);

	//remove the sentance ending.
	
	$last_word = end($shifted_words);
	
	//if the last character in the sentance is a period, question mark, or exclamation mark, omit it.
	if (mb_substr($last_word, -1, 1) == ("."||"!"||"?")){

		//omit the last character from the end of the sentance (last word of the sentance).
		$last_word = mb_substr($last_word, 0, -1);

	}
	
	
	//split the sentance by particles
	//$particle_array = find_particles($sentance);
	
	//*** need to try to remove 멋 잘 안 particles from verbs and store them.
	
	//double trans explains whether or not the second word was part of
	//the verb, or part of the tense.  When double_trans is true,
	//it means that the tense contained a space, (~고 있다)otherwise 
	//the tense did not contain a space.

	if (sizeof($shifted_words) == 1) {			
		$my_m_verb = find_tense( $last_word );
		if(isset($my_m_verb))$my_m_verb->double_trans( false );
	} elseif (sizeof($shifted_words) > 1) {
			//sentance has two or more words, use them to find the tense.
			$second_last = $shifted_words[sizeof($shifted_words) - 2];		
			$my_m_verb = find_tense($second_last . " " . $last_word);

			//since we just want to highlight the part of the verb which was
			//separated from the tense, if there is another word in the "mverb"
			//variable, we will remove it.
			
			
			if ( isset($my_m_verb) && mb_strpos($my_m_verb->mverb," ") ) {
				$my_m_verb->mverb = mb_ereg_replace("[^\s]+\s+", "", $my_m_verb->mverb);
				$my_m_verb->length = mb_strlen($my_m_verb->mverb);
				$my_m_verb->double_trans( false );
			} else {
				//tense would have been like ~고 있다 or a tense containing a space.
				$my_m_verb->double_trans( true );
			}
	}
		
		
	//if we haven't been able to find a VERB TENSE at the end of the sentance...

	//search for the verb (disconnected from the tense) in the dictionary.
	if(isset($my_m_verb) && $my_m_verb->double_trans) {
		//Using a two-word verb for the verb display
		$decomp_verb_and_tense = $decomp_words[sizeof($decomp_words) - 2] . " " . end($decomp_words);
	} elseif (isset($my_m_verb)) {
		//Using a one-word verb and tense for the verb display
		$decomp_verb_and_tense = end($decomp_words);
	}
	
	//Normalize the found tense separated from the verb
	$firstpart = mb_substr($decomp_verb_and_tense, 0, $my_m_verb->length);
	$secondpart = mb_substr($decomp_verb_and_tense, $my_m_verb->length);
		
	utf_normalizer::nfc($firstpart);
	utf_normalizer::nfc($secondpart);
	echo "here now first and second $firstpart $secondpart <br>";
	//if there is a third word that can be included in the verb search, add it.
	
	if ($my_m_verb->double_trans && $words[sizeof($words) - 3]) {
		//search for a definition, with the last 3 words of the sentance.
		//get the third last word from the sentance
		$third_last = $words[sizeof($words) - 3];
		//bigverb is connected to the last and second last words of the sentance.
		$bigverb = $third_last . " " . $firstpart;
	} else {
		//search for a definition with the last 1 or 2 words of the sentance.
		if (sizeof($words) > 1) {
			$second_last = $words[sizeof($words) - 2];
			$bigverb = $second_last . " " . $firstpart;
		} else {
			k2e_verb($firstpart, NULL, $my_m_verb);
		}
	}
	k2e_verb($firstpart, $bigverb, $my_m_verb);
	echo "<pre>";
	print_r($my_m_verb);
	?>
	
	
