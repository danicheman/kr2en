<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<meta http-equiv="content-language" content="ko"> 
<meta http-equiv="content-type" content="text/html; charset=utf8">
<html><body><?php 
include("idna_convert.class.php");
//not working
$idna= new idna_convert(false);
$my_test_string = "¾È³ç";
echo $my_test_string;
$my_test_string = utf8_encode($my_test_string);
echo $my_test_string;
$my_test_string = $idna->decode($my_test_string, 'utf8');
print_r($my_test_string);
$my_test_string = utf8_decode($my_test_string);
print_r($my_test_string);
?></html></body>