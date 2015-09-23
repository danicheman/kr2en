<?php

include_once("utf/utf_normalizer.php");
include_once("jamo_con.php");
	
class Name {
	function Name($first, $last, $title = NULL) {	
		$this->first = $first;
		$this->last = $last;
		if($title)$this->title= $title;
	}
}
	

function handle_names(&$clause){
	for($i = 0;isset($clause[$i]);$i++) {
		//is there a word we could translate as a name?
		if(is_null($clause[$i]->english)) {
			if(isset($clause[$i + 1]) && isset($clause[$i + 1]->word) && is_null($clause[$i + 1]->english) && $clause[$i + 1]->type != 'punctuation') {
				//two words translated as a name.
// 				echo "translating: #" . $clause[$i]->word . "# and #" . $clause[$i + 1]->word . "#";
				$name_obj = k2e_name($clause[$i]->word . " " . $clause[$i + 1]->word);
				$two_word_name = true;
				//$i++;
			} else { 
				//just the current word is to be translated.
// 				echo "one word trans: #" . $clause[$i]->word . "#";
				$name_obj = k2e_name($clause[$i]->word);
			}
// 					print_r($name_obj);
			$clause[$i]->english = $name_obj->title;
			$clause[$i]->name = $name_obj->title;
			if($name_obj->last) {
				
				$clause[$i]->english .= $name_obj->last;
				$clause[$i]->name .= $name_obj->last;
				$clause[$i]->type = 'name';
			}
			if($name_obj->first && $two_word_name) {
				
				$clause[$i + 1]->english .= $name_obj->first;
				$clause[$i + 1]->name .= $name_obj->first;
				$clause[$i + 1]->type = 'name';
			} elseif($name_obj->first) {
				
				$clause[$i]->english .= ' ' . $name_obj->first;
				$clause[$i]->name .= ' ' .$name_obj->first;
				$clause[$i]->type = 'name';
			}
		}
	}
}



//commented out testing code
/*
mb_internal_encoding( 'UTF-8' );
mb_regex_encoding('utf-8');

print_r(k2e_name("브리트니 스페어스"));
echo "<br />";
print_r(k2e_name("이호진군"));
echo "<br />";
print_r(k2e_name("오 승철"));
echo "<br />";
print_r(k2e_name("노무현"));
echo "<br />";
print_r(k2e_name("김영진양"));
echo "<br />";
print_r(k2e_name("조지 부시"));
echo "<br />";
print_r(k2e_name("김님"));
echo "<br />";
print_r(k2e_name("남미영"));
echo "<br />";
*/

//take decomposed and shifted versions of a korean "name" and return a romanized english name
function romanize_name ( $decomp_kname, $shifted_kname ) {
	$kname_length = mb_strlen($shifted_kname);
	for($i=0;$i< $kname_length;$i++) {
		$char_position = determine_order( mb_substr($decomp_kname,$i,1) );
		$lname .= k2e_romanization( mb_substr($shifted_kname,$i,1), $char_position );
	}
	
	return ucfirst($lname);
}

//Convert a Korean name into an english name and return it in a word object.

 	//name is a string with one or two words separated by a space.
function k2e_name($name) {
	
	//since we can recieve the $name variable with white space and/or particles, we need to filter them out first.
	$name = trim($name);
	
	$name = mb_ereg_replace('씨?(은|(에서?|이)?는|이|이?가|을|를|에서|에|으?로|엔)$','', $name); //take out the endings
	
	
	// personal suffixes 씨/님/양/군
	if(mb_ereg("(님|양|군)$", $name, $regs)) {
		echo "REGS";
		print_r($regs);
		if($regs[0] == '님' || $regs[0] == '군') $title = 'Mr. ';
	 	else $title = 'Ms. ';
	 	
	 	$name = mb_ereg_replace('(님|양|군)$','', $name);
		 	
	}
	
	//regular last names
	$lastnames = array(	'김' => 'Kim',  '이' => 'Lee',  '박' => 'Park', '최' => 'Choi', 
						'조' => 'Cho',  '정' => 'Jung', '배' => 'Bae',  '임' => 'Lim', 
						'강' => 'Kang', '윤' => 'Yoon', '장' => 'Jang', '한' => 'Han', 
						'신' => 'Shin', '오' => 'Oh',   '서' => 'Seo',  '노' => 'Roh');
						
	//special two-syllable last names					
	$sp_lastnames = array('선우' => 'Sunwoo', '남궁' => 'Namgoong', '독고' => 'Dokgo', '황보' => 'Hwangbo');	

		 
 	$decomp_name = $name;
	utf_normalizer::nfd(&$decomp_name);
	$shifted_name = jamo_to_co_jamo($decomp_name);

	
	if (mb_strpos($name," "))//try to find space(" ") in string, and if it finds it returns true
	{//we assume that if there is space in names, it is separated as: <lastname> <firstname>.

		$names = preg_split('/ /', $name, -1, PREG_SPLIT_NO_EMPTY);
		$decomp_names = preg_split('/ /', $decomp_name, -1, PREG_SPLIT_NO_EMPTY);
		$shifted_names = preg_split('/ /', $shifted_name, -1, PREG_SPLIT_NO_EMPTY);
	
		
		if(!$lname = $sp_lastnames[$names[0]] ) {
			//last name was not a special two syllable type name
			if (!$lname = $lastnames[$names[0]]) {
				//last name was not a regular one syllable type name, romanize it.
				$lname = romanize_name ( $decomp_names[0], $shifted_names[0] );
			}
		}
		//romanize the second word in the string as the first name
		$fname = romanize_name ( $decomp_names[1], $shifted_names[1] );
	}
	else
	{//get into this else {} when there is no spaces
		if($lname = $sp_lastnames[mb_substr($name,0,2)]) {
		//first 2 chars of names without space, and found lastname as special
		
			$nospace_decomp_name = mb_substr($name,2);
			utf_normalizer::nfd(&$nospace_decomp_name);
			$nospace_shifted_name = jamo_to_co_jamo($nospace_decomp_name);
			
			$fname = romanize_name($nospace_decomp_name, $nospace_shifted_name);
		}
	 	else
	 	{   //assume last name is 1 character
		 	$nospace_fname_decomp_name = mb_convert_encoding(mb_substr($name,1),'utf-8');
			utf_normalizer::nfd(&$nospace_fname_decomp_name);
			$nospace_fname_shifted_name = jamo_to_co_jamo($nospace_fname_decomp_name);
			if(!$lname = $lastnames[mb_substr($name,0,1)]) {
				
				//no lastname found, translate all as a single name
			 	$nospace_lname_decomp_name = mb_convert_encoding(mb_substr($name,0,1),'utf-8');
				utf_normalizer::nfd(&$nospace_lname_decomp_name);
				$nospace_lname_shifted_name = jamo_to_co_jamo($nospace_lname_decomp_name);
							
				$fname = romanize_name( $decomp_name, $shifted_name );
			} else {
				$fname = romanize_name( $nospace_fname_decomp_name, $nospace_fname_shifted_name );
			}
		}
	}
// 	echo "<br>lname $lname<br>";
// 	echo "fname $fname<br>";
	return new Name($fname, $lname, $title);
}


function k2e_romanization($k_letter,$order)
{
	switch($order)
	{
		case 1:
			switch($k_letter)
			{
				case 'ㄱ':
				return "g"; 
				break;
				
				case 'ㄲ':
				return "kk"; 
				break;
				
				case 'ㄴ':
				return "n"; 
				break;
				
				case 'ㄷ':
				return "d"; 
				break;
				
				case 'ㄸ':
				return "tt";
				break;
				
				case 'ㄹ':
				return "r";
				break;
				
				case 'ㅁ':
				return "m";
				break;
				
				case 'ㅂ':
				return "b";
				break;
				
				case 'ㅃ':
				return "pp";
				break;
				
				case 'ㅅ':
				return "s";
				break;
				
				case 'ㅆ':
				return "ss";
				break;
					
				case 'ㅇ':
				return "";
				break;	
					
				case 'ㅈ':
				return "j";
				break;	
					
				case 'ㅉ':
				return "jj";
				break;	
					
				case 'ㅊ':
				return "ch";
				break;	
					
				case 'ㅋ':
				return "k";
				break;	
					
				case 'ㅌ':
				return "t";
				break;	
					
				case 'ㅍ':
				return "p";
				break;	
					
				case 'ㅎ':
				return "h";
				break;
				
				default:
				return "";
				break;	
			}
		break;
		case 2:
			switch($k_letter)
			{
				case 'ㅏ':
				return "a";
				break;
				
				case 'ㅑ':
				return "ya";
				break;	
				
				case 'ㅓ':
				return "eo";
				break;	
					
				case 'ㅕ':
				return "yeo";
				break;	
					
				case 'ㅗ':
				return "o";
				break;
						
				case 'ㅛ':
				return "yo";
				break;	
					
				case 'ㅜ':
				return "u";//I think we better change this to 'woo'
				break;	
					
				case 'ㅠ':
				return "yu";//I think we better change this to 'you'
				break;	
					
				case 'ㅡ':
				return "eu";
				break;	
					
				case 'ㅣ':
				return "i";
				break;	
				
				case 'ㅐ':
				return "ae";
				break;	
					
				case 'ㅔ':
				return "e";
				break;	
					
				case 'ㅒ':
				return "yae";
				break;	
					
				case 'ㅖ':
				return "ye";
				break;	
					
				case 'ㅙ':
				return "wae";
				break;	
					
				case 'ㅘ':
				return "wa";
				break;	
					
				case 'ㅚ':
				return "oe";
				break;	
					
				case 'ㅝ':
				return "wo";//I think we better change this to 'war'
				break;	
					
				case 'ㅞ':
				return "we";//I think we better change this to 'whe'. 'whe' is from where
				break;	
					
				case 'ㅟ':
				return "wi";
				break;	
					
				case 'ㅢ':
				return "ui";
				break;	
					
				default:
				return "";
				break;
			}
		break;
		case 3:
			switch($k_letter)
			{
				case 'ㄱ':
				return "k";
				break;	
					
				case 'ㄴ':
				return "n";
				break;	
					
				case 'ㄶ':
				return 'n';
				break;
				
				case 'ㄷ':
				return "t";
				break;	
					
				case 'ㄹ':
				return "l";
				break;	
					
				case 'ㅁ':
				return "m";
				break;	
					
				case 'ㅂ':
				return "p";
				break;	
					
				case 'ㅅ':
				return "t";
				break;	
				
				case 'ㅆ':
				return "t";
				break;
					
				case 'ㅇ':
				return "ng";
				break;	
					
				case 'ㅈ':
				return "t";
				break;	
					
				case 'ㅊ':
				return "t";
				break;	
					
				case 'ㅋ':
				return "k";
				break;	
					
				case 'ㅌ':
				return "t";
				break;	
					
				case 'ㅍ':
				return "p";
				break;	
					
				case 'ㅎ':
				return "h";
				break;	
					
				default:
// 					$compiled_str = $compiled_str;
				return "";
				break;
			}
		break;
	}
}

function determine_order($decomp_str) {
 	
	$jamo_string_length = mb_strlen($decomp_str, 'utf-8');
	//$out_string = "";
	for ($i = 0; $i <$jamo_string_length;$i++) {
		$jamo_char = mb_substr($decomp_str,$i,1);
		$hexval = bin2hex($jamo_char);

		$subhexval = hexdec(substr($hexval, 4));
		
		if(substr($hexval,0,3) != "e18") {//if not in the right range to translate.
			$out_string .= $jamo_char;
			continue; 
		}
 		switch ($hexval[3]) {
	 		case 4: //초
 			return 1;
 				break;
 			case 5: //중
 			return 2;
 				break;
 			case 6: //종
 			return 3;
 				break;
 			default:
 				//echo " ";
 				$base=0;
 				$offset=0;
 				break;
 		}
	}
}


?>