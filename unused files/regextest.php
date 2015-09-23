<?php
/* Set internal character encoding to UTF-8 */
mb_internal_encoding("UTF-8");

/* Display current internal character encoding */
echo mb_internal_encoding() . "<br><br>";

mb_regex_encoding('utf-8');
$haystack = 'ㅇㅣㅂㄴㅣㄷㅏㄴ';
$pattern = '/[ㅂ|ㅈ]*ㄷㄱ/';
//if (mb_eregi ('ㅆㄷㅏ|ㅓㅆㅅㅡㅂㄴㅣㄷㅏ|ㅆㅇㅓ|ㅆㅇㅓㅇㅛ|ㅅㅓㅆㅇㅓㅇㅛ', 'ㅇㅓㅆㅅㅡㅂㄴㅣㄷㅏ')) {
if ( mb_eregi ('[ㅆㄷㅏ|ㅅㅕㅆㅇㅓㅇㅛ]$', 'ㅎㅏㅅㅕㅆㅇㅓㅇㅛ') ) {
	echo "good";
	echo  mb_eregi ('ㅅㅅㅅ|ㅅㅕㅆㅇㅓㅇㅛ$', 'ㅎㅏㅅㅕㅆㅇㅓㅇㅛ', $regs);
	print_r($regs);
} else { echo "bad"; }
if ( mb_eregi ('ㅆㄷㅏ|ㅅㅕㅆㅇㅓㅇㅛ$', 'ㅎㅏㅅㅕㅆㅇㅓㅇ') ) {
	echo "bad";
	echo mb_eregi ('[ㅆㄷㅏ|ㅅㅕㅆㅇㅓㅇㅛ]$', 'ㅎㅏㅅㅕㅆㅇㅓㅇ', $regs) ;
	print_r($regs);
} else { echo "good"; }

$haystack = mb_ereg_replace('ㅂㄴㅣㄷㅏ',"", $haystack); 
	echo "replaced  ~ㅂ니다 ->" . $haystack;
?>