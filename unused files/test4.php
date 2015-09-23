<?php

//default values function test

function tester($a, $b="default", $c="default2") {
	echo "a= $a b= $b c= $c<br>";
}



tester(234, 1214);
tester("cow");
tester(123,456,789);

?>