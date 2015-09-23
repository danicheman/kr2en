<?php
include("../phpBB3/includes/utf/utf_normalizer.php");

$korean="테스트";
/* Set internal character encoding to UTF-8 */
mb_internal_encoding("UTF-8");

/* Display current internal character encoding */
echo mb_internal_encoding() . "<br><br>";

//test multi-byte stringlength function
echo "The \"string length\" of \"Korean\": " . mb_strlen($korean, 'utf-8') . "<br><br>";

//double check that the string is UTF-8
if(mb_check_encoding($korean, "utf8"))echo $korean . " is UTF8<br><br>";

//Normal Decompose
utf_normalizer::nfd(&$korean);

echo "Here is the \"test\" string decomposed: " . $korean . "<br><br>";
//Normal Recompose
utf_normalizer::nfc(&$korean);
echo "Here is the \"test\" string recomposed: " . $korean . "<br><br>";


?>