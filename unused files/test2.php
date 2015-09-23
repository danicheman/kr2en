<html> 
<head><title>테스트</title>
<style type="text/css">
<!--
.yellowText {background-color: yellow}
.blueText {color:white; background-color: blue}
.greenText {color:white; background-color: green}
.redText {color:white; background-color: red}
.grayText {color:white; background-color: gray}
-->
</style>
</head>
<body>
<span class="grayText">past tense</span>
<span class="blueText">present tense</span>
<span class="greenText">future tense</span><BR><BR><pre>
<?php

include("connect.php"); 
include("../phpBB3/includes/utf/utf_normalizer.php");
include("jamo_con.php");

echo "strip slashes test";
$my_string = "Nick said, \"This is crazy dude!\"<br>";
echo $my_string;
stripslashes($my_string);
echo $my_string;
?>
</pre>
</body>
</html>