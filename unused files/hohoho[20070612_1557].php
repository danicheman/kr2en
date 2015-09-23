<?php
include("connect.php"); 
include("utf/utf_normalizer.php");
include("jamo_con.php");
include("verb_matching.php");
include("matching.php");
include("printing.php");
include("k2e_noun_clause.php");

	mb_internal_encoding( 'UTF-8' );
	mb_regex_encoding('utf-8');
	mysql_query("SET NAMES 'utf8'");

if (empty($_POST['test_input'])) {
	$test_string = "김태연";
} else { 
	$test_string = $_POST['test_input'];
}
// echo $test_string;

// echo strlen($test_string);

$names = preg_split('/ /', $test_string, -1, PREG_SPLIT_NO_EMPTY);

if (mb_strpos($test_string," "))//if this is odd number.. but this is not really perfect. because, if there is more than 1 space then this method is not gonna work.
{//we assume that if there is space in name, it is separated by surname, and givenname.

	mysql_selectdb("dictionary2");
	
	$SQL1 = "SELECT kname,ename FROM trans_kspsurnames";
	$result1 = mysql_query($SQL1);
	while($row1 = mysql_fetch_array($result1))
	{
		//echo "***".mb_convert_encoding(mb_substr($test_string,0,4),'utf-8')."*********************<br><br><br><br>".mb_convert_encoding($row1[kname],'utf-8')."****".$row1[ename];
		if(mb_convert_encoding(names[0],'utf-8') == mb_convert_encoding($row1[kname],'utf-8'))
		{//names with space, and found surname as special(2 letters for surname)
			echo "<br><br>surname with space:".$row1[ename]."<br><br>";//if it gets to inside this if, then it means names without space have special surname.
			echo "<br><br>givenname with space:"
			determine_order(
			k2e_romanization(mb_ereg_replace(' ','',mb_substr($test_string,2,mb_strlen($test_string))))."<br><br>";
		}
	}
	

	
}
else
{//get into this else {} when there is no spaces
	echo "$$$$$$$$".mb_convert_encoding(mb_substr($test_string,0,2),'utf-8')."$$$$$$$$$$$$";
	$decomposed_test_string = $test_string;
	utf_normalizer::nfd(&$decomposed_test_string);//& makes send a address of variable, not a variable itself.

	$shifted_test_string = jamo_to_co_jamo($decomposed_test_string);	


mysql_selectdb("dictionary2");

$SQL1 = "SELECT kname,ename FROM trans_kspsurnames";
$result1 = mysql_query($SQL1);
if(mysql_num_rows($result1) > 0)
{
	while($row1 = mysql_fetch_array($result1))
	{
		//echo "***".mb_convert_encoding(mb_substr($test_string,0,4),'utf-8')."*********************<br><br><br><br>".mb_convert_encoding($row1[kname],'utf-8')."****".$row1[ename];
		if(mb_convert_encoding(mb_substr($test_string,0,2),'utf-8') == mb_convert_encoding($row1[kname],'utf-8'))
		{
			echo "this is special case";//if it gets to inside this if, then it means names without space have special surname.
			echo "<br><br><br>surname without space:".$row1[ename];
			//put code to romanize korean to eng for given name.
		}
		else
		{// put code to assume first char is surname and rest of the names are given name.
			
		}
	}
}	
	//if() if first 2 chars is in special_surname db, then take first 2 chars as surname.
	//else() just take first char as surname.
	
//	$surname = substr($test_string,0,strpos($test_string," "));
//	echo "<br><br>surname with space:".$surname."<br><br>";
//	$givenname = substr($test_string,strpos($test_string," ")+1,strlen($test_string));
//	echo "<br><br>givenname with space:".$givenname."<br><br>";
}

$decomposed_test_string = $test_string;
utf_normalizer::nfd($decomposed_test_string);

$shifted_test_string = jamo_to_co_jamo($decomposed_test_string);

// echo $test_string;
// echo "<br/><br/>";
//utf_normalizer::nfc($decomposed_test_string);
//echo $decomposed_test_string;
//echo "<br/><br/>";
//utf_normalizer::nfc($shifted_test_string);
//echo $shifted_test_string;

function k2e_romanization($korean)
{
	$decomposed_test_string1 = mb_ereg_replace(' ','',$korean);
	utf_normalizer::nfd($decomposed_test_string1);
	$shifted_test_string1 = jamo_to_co_jamo($decomposed_test_string1);
	$total_num_string = mb_strlen($shifted_test_string1);
	echo $shifted_test_string1;
	//echo mb_substr($shifted_test_string1,0,1);
	
	//$tmp = k2e_romanization2(mb_substr($shifted_test_string1,0,1),1);
	
	
	echo k2e_romanization2(mb_substr($shifted_test_string1,0,1),1);
	echo k2e_romanization2(mb_substr($shifted_test_string1,1,1),2);
	echo k2e_romanization2(mb_substr($shifted_test_string1,2,1),3);
	
	echo k2e_romanization2(mb_substr($shifted_test_string1,3,1),1);
	echo k2e_romanization2(mb_substr($shifted_test_string1,4,1),2);
	
	echo k2e_romanization2(mb_substr($shifted_test_string1,5,1),1);
	echo k2e_romanization2(mb_substr($shifted_test_string1,6,1),2);
// 	echo $decomposed_test_string1[0];
// 	echo $decomposed_test_string1[1];

// 	for($i=0;$i < $total_num_string; $i++)
// 	{
// 		k2e_romanization2();
// 	}
}
function k2e_romanization2($k_letter,$order)
{
	$compiled_str = "";
	switch($order)
	{
		case 1:
			switch($k_letter)
			{
				case "ㄱ":
					$compiled_str = $compiled_str."g";
				break;
				
				case "ㄲ":
					$compiled_str = $compiled_str."kk";
					//echo "compiled".$compiled_str;
				break;
				
				case 'ㄴ':
					$compiled_str = $compiled_str.'n';
				break;
				
				case 'ㄷ':
					$compiled_str = $compiled_str.'d';
				break;
				
				case 'ㄸ':
					$compiled_str = $compiled_str.'tt';
				break;
				
				case 'ㄹ':
					$compiled_str = $compiled_str.'r';
				break;
				
				case 'ㅁ':
					$compiled_str = $compiled_str.'m';
				break;
				
				case 'ㅂ':
					$compiled_str = $compiled_str.'b';
				break;
				
				case 'ㅃ':
					$compiled_str = $compiled_str.'pp';
				break;
				
				case 'ㅅ':
					$compiled_str = $compiled_str.'s';
				break;
				
				case 'ㅆ':
					$compiled_str = $compiled_str.'ss';
				break;
					
				case 'ㅇ':
					$compiled_str = $compiled_str;
				break;	
					
				case 'ㅈ':
					$compiled_str = $compiled_str.'j';
				break;	
					
				case 'ㅉ':
					$compiled_str = $compiled_str.'jj';
				break;	
					
				case 'ㅊ':
					$compiled_str = $compiled_str.'ch';
				break;	
					
				case 'ㅋ':
					$compiled_str = $compiled_str.'k';
				break;	
					
				case 'ㅌ':
					$compiled_str = $compiled_str.'t';
				break;	
					
				case 'ㅍ':
					$compiled_str = $compiled_str.'p';
				break;	
					
				case 'ㅎ':
					$compiled_str = $compiled_str.'h';
				break;
				
				default:
					$compiled_str = $compiled_str;
				break;	
			}
		break;
		case 2:
			switch($k_letter)
			{
				case 'ㅏ':
					$compiled_str = $compiled_str.'a';
				break;
				
				case 'ㅑ':
					$compiled_str = $compiled_str.'ya';
				break;	
				
				case 'ㅓ':
					$compiled_str = $compiled_str.'eo';
				break;	
					
				case 'ㅕ':
					$compiled_str = $compiled_str.'yeo';
				break;	
					
				case 'ㅗ':
					$compiled_str = $compiled_str.'o';
				break;
						
				case 'ㅛ':
					$compiled_str = $compiled_str.'yo';
				break;	
					
				case 'ㅜ':
					$compiled_str = $compiled_str.'u';//I think we better change this to 'woo'
				break;	
					
				case 'ㅠ':
					$compiled_str = $compiled_str.'yu';//I think we better change this to 'you'
				break;	
					
				case 'ㅡ':
					$compiled_str = $compiled_str.'eu';
				break;	
					
				case 'ㅣ':
					$compiled_str = $compiled_str.'i';
				break;	
				
				case 'ㅐ':
					$compiled_str = $compiled_str.'ae';
				break;	
					
				case 'ㅔ':
					$compiled_str = $compiled_str.'e';
				break;	
					
				case 'ㅒ':
					$compiled_str = $compiled_str.'yae';
				break;	
					
				case 'ㅖ':
					$compiled_str = $compiled_str.'ye';
				break;	
					
				case 'ㅙ':
					$compiled_str = $compiled_str.'wae';
				break;	
					
				case 'ㅘ':
					$compiled_str = $compiled_str.'wa';
				break;	
					
				case 'ㅚ':
					$compiled_str = $compiled_str.'oe';
				break;	
					
				case 'ㅝ':
					$compiled_str = $compiled_str.'wo';//I think we better change this to 'war'
				break;	
					
				case 'ㅞ':
					$compiled_str = $compiled_str.'we';//I think we better change this to 'whe'. 'whe' is from where
				break;	
					
				case 'ㅟ':
					$compiled_str = $compiled_str.'wi';
				break;	
					
				case 'ㅢ':
					$compiled_str = $compiled_str.'ui';
				break;	
					
				default:
					$compiled_str = $compiled_str;
				break;
			}
		break;
		case 3:
			switch($k_letter)
			{
				case 'ㄱ':
					$compiled_str = $compiled_str.'k';
				break;	
					
				case 'ㄴ':
					$compiled_str = $compiled_str.'n';
				break;	
					
				case 'ㄷ':
					$compiled_str = $compiled_str.'t';
				break;	
					
				case 'ㄹ':
					$compiled_str = $compiled_str.'l';
				break;	
					
				case 'ㅁ':
					$compiled_str = $compiled_str.'m';
				break;	
					
				case 'ㅂ':
					$compiled_str = $compiled_str.'p';
				break;	
					
				case 'ㅅ':
					$compiled_str = $compiled_str.'t';
				break;	
					
				case 'ㅇ':
					$compiled_str = $compiled_str.'ng';
				break;	
					
				case 'ㅈ':
					$compiled_str = $compiled_str.'t';
				break;	
					
				case 'ㅊ':
					$compiled_str = $compiled_str.'t';
				break;	
					
				case 'ㅋ':
					$compiled_str = $compiled_str.'k';
				break;	
					
				case 'ㅌ':
					$compiled_str = $compiled_str.'t';
				break;	
					
				case 'ㅍ':
					$compiled_str = $compiled_str.'p';
				break;	
					
				case 'ㅎ':
					$compiled_str = $compiled_str.'h';
				break;	
					
				default:
					$compiled_str = $compiled_str;
				break;
			}
		break;
	}
	return $compiled_str;
	
}

function determine_order($decomp_str) {
 	
	$jamo_string_length = mb_strlen($decomp_str, 'utf-8');
	$out_string = "";
	for ($i = 0; $i <$jamo_string_length;$i++) {
		$jamo_char = mb_substr($jamo_string,$i,1);
		$hexval = bin2hex($jamo_char);

		$subhexval = hexdec(substr($hexval, 4));
		
		if(substr($hexval,0,3) != "e18") {//if not in the right range to translate.
			$out_string .= $jamo_char;
			continue; 
		}
 		switch ($hexval[3]) {
	 		case 4: //초
 				$base = 131121;
 				 				
 				switch ($subhexval) {
 					case ($subhexval< hexdec(82)):
 						$offset = 0;
 						break;
 					case ($subhexval< hexdec(83) ):
 						$offset = 1;
 						break;
 					case ($subhexval< hexdec(86) ):
 						$offset = 3;
 						break;
 					case ($subhexval< hexdec(88) ):
 						$offset = 202;
 						break;
 					default:
 						$offset = 203;
 						break;
				}
 				break;
 			case 5: //중
 				$base = 131054;
 				$offset = 0;
 				break;
 			case 6: //종
 				$base = 130569;
 				 				
 				switch ($subhexval) {
	 				case ($subhexval < hexdec(AF)):
	 				$offset = 0;
	 				break;
	 				
	 				case ($subhexval < hexdec(B6)):
	 				$offset = 1;
	 				break;
	 				
	 				case ($subhexval < hexdec(B9)):
	 				$offset = 193;
	 				break;
	 				
	 				case ($subhexval < hexdec(BE)):
	 				$offset = 194;
	 				break;
	 				
	 				default:
	 				$offset = 195;
	 				break;
 				}
 				
 				break;
 			default:
 				//echo " ";
 				$base=0;
 				$offset=0;
 				break;
 		}
 		
 		$out_string .= pack('H*', dechex(hexdec($hexval) + $base + $offset));
	}
	return $out_string;
 }


?>
<html>
<body>
<p><form action="hohoho.php" method="post"> Input Korean here:<br>
<textarea rows="5" cols="25" name="test_input" style="background:#C9AEFF;"></textarea>
<input type="submit" style="background:#C9AEFF;padding:3px;"/></p> 
</form> 
</body>
</html>