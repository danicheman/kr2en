<?php
//m_verb class.  Contains modified Korean verb and later can hold english translation.
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

include("connect.php"); 
include("../phpBB3/includes/utf/utf_normalizer.php");
include("jamo_con.php");
include("matching.php");

	mb_internal_encoding( 'UTF-8' );
	mb_regex_encoding('utf-8');
	mysql_query("SET NAMES 'utf8'");

	
	echo "<pre>";
	
	echo "Example 1: ";
	$sentance = "사장님은 해외에 나갔기가 쉬워.";
	echo $sentance . "\n";
	$shifted_sentance = $sentance;
	$decomp_sentance = $shifted_sentance;
	utf_normalizer::nfd(&$decomp_sentance);
	$shifted_sentance = jamo_to_co_jamo($decomp_sentance);
	
	$result = k2e_adnominal($shifted_sentance, $decomp_sentance);

	utf_normalizer::nfc(&$result);
	print_r($result);
	
	
	echo "\n\nExample 2: ";
	$sentance = "위국에서 살기가 재미있을 것 같다.";
	echo $sentance . "\n";
	$shifted_sentance = $sentance;
	$decomp_sentance = $shifted_sentance;
	utf_normalizer::nfd(&$decomp_sentance);
	$shifted_sentance = jamo_to_co_jamo($decomp_sentance);
	
	$result = k2e_adnominal($shifted_sentance, $decomp_sentance);

	utf_normalizer::nfc(&$result);
	print_r($result);
	echo "\n\nExample 3: ";
	$sentance = "어린 아이들은 걷다가 넘어지기가 일쑤다.";
	echo $sentance . "\n";
	$shifted_sentance = $sentance;
	$decomp_sentance = $shifted_sentance;
	utf_normalizer::nfd(&$decomp_sentance);
	$shifted_sentance = jamo_to_co_jamo($decomp_sentance);
	
	$result = k2e_adnominal($shifted_sentance, $decomp_sentance);

	utf_normalizer::nfc(&$result);
	print_r($result);	
//adnominal ending finder/translator.  Find adnominals and translate in place.

function k2e_adnominal($shifted_sentance, $decomp_sentance) {
	if(!$shifted_sentance) return false;
	$patterns = array(
					"ㄱㅣ(ㄱㅏ)?\Wㅅㅟ",				"ㄱㅣ(ㄱㅏ)?\Wㅇㅣㄹㅆㅜ(ㅇㅣ)?",	
	"ㄱㅣ\Wㄱㅡㅈㅣㅇㅓㅄ",		"ㄱㅣ\Wㄴㅏㄹㅡㅁㅇ\w+\W",				"ㄱㅣㄴㅡㄴ(ㅇㅛ)?\.", 	
	"ㄱㅣㄴㅡㄴ\Wㅎ(ㅏ|ㅐ)", 	"ㄱㅣㄴㅡㄴ\Wㅋㅓㄴㅑㅇ\W" , 		"ㄱㅣㄷㅗ\Wㅎㅏㄹㅕㄴㅣㅇㅘ\w+\W", 								"ㄱㅣㄷㅗ","ㄱㅣ\Wㄸㅐㅁㅜㄴㅇㅔ\W",
	"ㄱㅣㄹㅏㄴ\W",				"ㄱㅣ\Wㅁㅏㄹㅕㄴㅇㅣ",				"ㄱㅣㅁㅏㄴ\W", 
	"ㄱㅣㅇㅑ",					"ㄱㅣㅇㅔ\W",							"ㄱㅣㄹㅗㅅㅓㄴㅣ\W",
	"ㄱㅣㄹㅗ\W",				"ㄱㅣㄹㄹㅐ\W",						"ㄱㅣ\Wㅇㅟㅎㅐ(ㅅㅓ)?\W", 		
	"ㄱㅣ\Wㅈㅓㄴㅇㅔ",			"ㄱㅣ\Wㅉㅏㄱㅇㅣ\Wㅇㅓㅄ",			"ㄱㅣ\Wㅎㅏㄴㅇㅣ\Wㅇㅓㅄ",
	"ㄱㅣ(ㄱㅏ)?\W");
	
	//action 0: 17,18 -> inplace
	//action 1: 1 to 4 -> translate verb and swap with it 5,6,8, 9,11,14,16-> to beginning of sentance, insert comma where the word was.
	//action 2: 7,10,12,13,15,19 -> translate verb and swap with it
	$english = array(
	" be likely that ", " be typically ", "be incredibly", "depends on ", "Really? ",
	"Unfortunately ", " be far from ", "Besides ", "indeed, "," Because ","As for ", " inevitably ", " as soon as ",
	" certainly ", "for/by/in/from", "even though", "plan to", "Since ", " in order to ",
	 " before ", "be incredibly ", "be incredibly", " "); 
	 //0 in place, 1 
	$t_action = array( 2,2,2,2,1,1,2,1,0,1,2,2,1,1,2,1,2,1,0,0,2,1,0);

			//suggest two more arrays 1- is a conjunction boundary 2- verb to translate (with which number in the english translation the word is)
			
			//need to add more error checking for if the verb isn't found...how do we handle those errors? New array?
	for ($i = 0; $i < 23;$i++) {
		

		mb_ereg_search_init($shifted_sentance,$patterns[$i]);
		
		while($regs = mb_ereg_search_regs()) {
		/*
		echo "<b>pattern:</b> " . $patterns[$i] . "\n	<b>english:</b> ". $english[$i] . "	<b>action:</b> " .$t_action[$i];
		switch ($t_action[$i]) {
			case 0: echo " in place\n\n";break;
			case 1: echo " beginning of sentance\n\n";break;
			case 2: echo " swap with verb\n\n"; break;
		}*/
		
		
		//search unconjugated verbs.	
		
		
		//echo "regs are: ".$regs[0] . "\n";
		
		//find position and length of the adnominal
		$ad_pos = mb_strpos($shifted_sentance, $regs[0]); 
		$ad_length = mb_strlen($regs[0]);
		
		//retrieve the portion of the sentance with the verb to translate
		$before_ad = mb_split($regs[0], $shifted_sentance, 2);
		//delete the current adnominal that was found from the sentance (decomp and shifted).
		//print_r($before_ad);
		if(0 == mb_substr_count($before_ad[1], ' ')) {
			//call find tense!
			$last_char_pos = mb_strlen($before_ad[1]) - 1;
			$last_char = mb_substr($before_ad[1], $last_char_pos,1);
			if ($last_char== '.' || $last_char == '?' || $last_char == '!') {
				$lastword = mb_substr($before_ad[1], 0, mb_strlen($before_ad[1]) - 1);
			}
									
			$the_mverb = find_tense($lastword);
			//remove the after match part from the sentance but leave the period or question mark.
			//find the beginning of section to delete.
			$after_ad_pos = mb_strrpos($shifted_sentance, $lastword);
			
			//delete last part
			$shifted_sentance = mb_substr($shifted_sentance, 0, $after_ad_pos) . $last_char;
			$decomp_sentance = mb_substr($decomp_sentance, 0, $after_ad_pos) . $last_char;

		}			
		//translate verb, unconjugated, possibly with two words.
		//use decomposed sentance.

		$ba_decomp = mb_substr($decomp_sentance, 0, mb_strlen($before_ad[0]));
		//destroys punctuation
		$ba_decomp_words = mb_split ( '\W+', $ba_decomp);
		$verb = end($ba_decomp_words);
		utf_normalizer::nfc(&$verb);
		if(sizeof($ba_decomp_words)>1) {
			$decomp_lasttwoword = $ba_decomp_words[(sizeof($ba_decomp_words) - 2)] . " " . end($ba_decomp_words);
			utf_normalizer::nfc(&$decomp_lasttwoword);
			$my_mverb = new m_verb("present", $decomp_lasttwoword, mb_strlen($decomp_lasttwoword));
					
			$query = "select km.english from trans_kverbs as kv,trans_kverb_meanings as km WHERE kv.vin = km.vin AND kv.knocon = \"".$decomp_lasttwoword."\" AND km.withhuman = 1";
			$result = mysql_query($query); 
			if (empty($result) || 0==mysql_num_rows($result)) {
				$query = "select km.english from trans_kverbs as kv,trans_kverb_meanings as km WHERE kv.vin = km.vin AND kv.knocon = \"".$verb."\" AND km.withhuman = 1";
				$result = mysql_query($query); 
				//if there is a result for the shorter verb, modify the other m_verb properties.
				if(mysql_num_rows($result)) {
					$my_mverb->mverb = $verb;
					$my_mverb->length = mb_strlen($verb);
				}
			}
						
			if($row = mysql_fetch_array($result)) { 
 				$my_mverb->english($row[0], 0); //only inserting one definition..found under single (isdouble ->true)
			}
		} else {
			$my_mverb = new m_verb("present", $verb, mb_strlen($decomp_lasttwoword));
			$query = "select km.english from trans_kverbs as kv,trans_kverb_meanings as km WHERE kv.vin = km.vin AND kv.knocon = \"".$verb."\" AND km.withhuman = 1";
			$result = mysql_query($query); 
						
			if($row = mysql_fetch_array($result)) { 
 				$my_mverb->english($row[0], 0); //only inserting one definition..found under single (isdouble ->false)
			}
		}

		//echo "adpos: $ad_pos and adlength: $ad_length\n shifted sen: $shifted_sentance\n";
			switch ($t_action[$i]) {
				case 0: //in place translation
									

					//if we have an english mverb translation and a type 2 t_action, match the verb and switch it in place~!
					if ( $my_mverb->english ) {
						$found_verb = $my_mverb->mverb;
						utf_normalizer::nfd(&$found_verb);
						$noun_form = noun_form($my_mverb->english);
						$verb_pos = mb_strpos($decomp_sentance, $found_verb);
						
						$shifted_sentance = mb_substr($shifted_sentance,0,$verb_pos) . $noun_form . $english[$i] . mb_substr($shifted_sentance, $ad_pos + $ad_length);
						$decomp_sentance  = mb_substr($decomp_sentance,0,$verb_pos) .$noun_form . $english[$i] . mb_substr($decomp_sentance, $ad_pos + $ad_length);
					} else {
						$shifted_sentance = mb_substr($shifted_sentance,0,$ad_pos) . $english[$i] . mb_substr($shifted_sentance, $ad_pos + $ad_length);
						$decomp_sentance  = mb_substr($decomp_sentance,0,$ad_pos) .$english[$i] . mb_substr($decomp_sentance, $ad_pos + $ad_length);
					}
				break;
				case 1: // to beginning of sentance or **previous comma**Is that a good idea?
					if ( $my_mverb->english ) {
						//find last comma in area preceeding search
						$found_verb = $my_mverb->mverb;
						utf_normalizer::nfd(&$found_verb);
						$noun_form = noun_form($my_mverb->english);
						$verb_pos = mb_strpos($decomp_sentance, $found_verb);
						
						$shifted_sentance = $english[$i] . mb_substr($shifted_sentance,0,$verb_pos) . $noun_form . ', ' . mb_substr($shifted_sentance, $ad_pos + $ad_length);
						$decomp_sentance  = $english[$i] . mb_substr($decomp_sentance,0,$verb_pos) . $noun_form . ', ' . mb_substr($decomp_sentance, $ad_pos + $ad_length);
					} else {
						$shifted_sentance = $english[$i] . mb_substr($shifted_sentance,0,$ad_pos) . ', ' . mb_substr($shifted_sentance, $ad_pos + $ad_length);
						$decomp_sentance  = $english[$i] . mb_substr($decomp_sentance,0,$ad_pos) . ', ' . mb_substr($decomp_sentance, $ad_pos + $ad_length);
					}
				break;
				case 2: // translate verb and swap
					//if we have an english mverb translation and a type 2 t_action, match the verb and switch it in place~!
					if ( $my_mverb->english ) {
						$found_verb = $my_mverb->mverb;
						$noun_form = noun_form($my_mverb->english);
					} else {
						$found_verb = $verb;
						$noun_form = $verb;
					}
						utf_normalizer::nfd(&$found_verb);
						$verb_pos = mb_strpos($decomp_sentance, $found_verb);
						//knock out extra "be" verb from sentance.
						if (mb_substr_count($english[$i],'be ') && mb_substr_count($noun_form,'be ')) {
								$noun_form = mb_eregi_replace('be ', '', $noun_form);
						 }
						// 1 - from beginning of sentance to beginning of verb. 2- english adnominal translation 3 - translated verb and rest of sentance.
						$shifted_sentance = mb_substr($shifted_sentance,0,$verb_pos) . $english[$i] . $noun_form . mb_substr($shifted_sentance, $ad_pos+$ad_length );
						$decomp_sentance  = mb_substr($decomp_sentance,0,$verb_pos) . $english[$i] . $noun_form . mb_substr($decomp_sentance, $ad_pos + $ad_length );

				break;
				default: die('invalid t_action');//error
			}	
		}	
	}
	return $decomp_sentance;	
}
//change verb to noun.
function noun_form($noun) {
	if ( !$noun ) return false;

	$noun_words = mb_split("\s", $noun, 2);

	//remove e from non 'be' words
	if ( $noun_words[0] != 'be') $noun_words[0] = mb_eregi_replace("e\Z", "", $noun_words[0]);
	//return a noun form second word if the first word is 'not'
	if ( $noun_words[0] == 'not' && $noun_words[1])return $noun_words[0] . " " . $noun_words[1] . "ing";
	//return a noun form first word if the first word is not 'be' and there is more than one word
	if ( $noun_words[0] != 'be' && $noun_words[1])return $noun_words[0] . 'ing ' . $noun_words[1];
	//return a noun form withe the first word holding 'ing' with no space if there is no second word.
	if ( $noun_words[0] != 'be' )return $noun_words[0] . 'ing';
	// do not add 'ing' if the first word is be.
	if ( $noun_words[1]) return $noun_words[0] . " " . $noun_words[1];
	return $noun_words[0];
}
	