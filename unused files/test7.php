<?php //bitwise opperation

$a = pow(2,1)+pow(2,5);
$b = pow(2,2)+pow(2,3)+pow(2,4);
$c = pow(2,1)+pow(2,6);

if (!($a & $b)) echo "FALSE";//should be able to nand.
else echo "TRUE";

if (!($a & $c)) echo "TRUE";//should NOT be able to nand.
else echo "FALSE";

if (!($b & $c)) echo "FALSE";//should be able to nand.
else echo "TRUE";
?>