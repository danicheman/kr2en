<?php
//include("../phpBB3/includes/utf/utf_normalizer.php");

/* Set internal character encoding to UTF-8 */
mb_internal_encoding("UTF-8");
/*//Test the function
$korean="ㅂㅈㄷㄱ했 pimp";

utf_normalizer::nfd(&$korean);
echo "$korean<br>";
$korean = jamo_to_co_jamo($korean);
echo "$korean<br>";
*/
/** Function Jamo to Jamo compatibility
 *---------------------------------------
 * Converts a decomposed UTF8 Jamo string to a UTF8 compatibility Jamo string
 * Input a decomposed string of multi-byte Hangeul in Jamo
 * Output a decomposed string of multi-byte Hangeul in compatibility Jamo
 */
 function jamo_to_co_jamo($jamo_string) {
 	
	$jamo_string_length = mb_strlen($jamo_string, 'utf-8');
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
 					case ($subhexval< hexdec(89) ):
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
 			
 				$base=0;
 				$offset=0;
 				break;
 		}
 		
 		$out_string .= pack('H*', dechex(hexdec($hexval) + $base + $offset));
	}
	return $out_string;
 }

	
 ?>