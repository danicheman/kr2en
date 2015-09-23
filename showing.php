<?php

$needle = "nick";
// $haystack = "nick is the man nick is the dude nick is the person";
$offset = -3;
$haystack = "nick";
while (($result = strrpos($haystack, $needle, $offset)) !== false) {
	echo "here's a result: $result<br>\n";
	$offset =  ($result - strlen($haystack) -1);
	echo "offset: $offset<br>";
}
?>