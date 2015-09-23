<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<meta http-equiv="content-language" content="ko"> 
<meta http-equiv="content-type" content="text/html; charset=utf8"> 
<html> 
<head><title>T5</title>
<style type="text/css";>
blockquote { display: inline; border-bottom: thin solid gray; }

</style>
</head>
<body><pre>
<blockquote>your link text</blockquote>
<?php
	$test_string = "혼전계약에 따른 스피어스와 페더라인 간 재산분할 흥정은 1000만달러(95억4000만원)에서 출발했다.주간 ‘스타’는 “브리트니가 이혼과 동시에 두 아들의 양육권을 포기하면 1000만달러를 주겠다고 하자 케빈은 웃고 말았다”고 전했다. 1차 협상 결렬.";
/* Parser.  Mark with color Korean text which is part of the object, subject, object or subject modifiers and verbs.
	This split splits periods followed by a space.  This is not always the case.
	Some news sentances do not start with a space!
	need to split without space not following a capital english letter or between digits.
*/
	mb_internal_encoding( 'UTF-8' );
	mb_regex_encoding('utf-8');

	//	create isquestion array
	//	search and split question marks, and periods

	echo "mb ereg: " . preg_match_all("/(?:((이|가|은|는|을|를)\s+)|(\.|\!|\?)\s*)(([가-힣]|[ㄱ-ㅎ])+[^이,가,은,는,을,를]\s)*([가-힣]|[ㄱ-ㅎ])+(은|는)\s/um", $test_string, $regs) . " and regs: ";
	print_r($regs);
	
	//$test_string = mb_eregi_replace("\.$","",$test_string);
	
//clean up tabs and new lines, remove last period to prevent extra split.
  //$test_string = mb_eregi_replace("\s\s+|\t\t+|\n\r+|\n\n+|\r\r+|\.$|\?$","",$test_string);

/* check for ///// at the end of words and conjuctions ~ ~ ~ 
 *
 * note: need to remove linebreaks if present in input...did we already?
 *
 * 1. find sentances: more than one alphabetic character before a period.
 * note: need to only match sentances with 2 or more characters
 */
?></pre>