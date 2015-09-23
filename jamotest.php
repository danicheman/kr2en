<?php
include("jamo_con.php");
include("utf/utf_normalizer.php");
mb_internal_encoding( 'UTF-8' );
mb_regex_encoding('utf-8');
$my_string  = "here is bbal 빨<br>";
echo $my_string;
utf_normalizer::nfd($my_string);
echo $my_string;
$my_string = jamo_to_co_jamo($my_string);
echo $my_string;
$my_string  = "here is bal 발<br>";
echo $my_string;
utf_normalizer::nfd($my_string);
echo $my_string;
$my_string = jamo_to_co_jamo($my_string);
echo $my_string;
$my_string  = "here is bal 살<br>";
echo $my_string;
utf_normalizer::nfd($my_string);
echo $my_string;
$my_string = jamo_to_co_jamo($my_string);
echo $my_string;

?>