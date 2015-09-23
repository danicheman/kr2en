<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<meta http-equiv="content-language" content="ko"> 
<meta http-equiv="content-type" content="text/html; charset=utf8"> 
<html> 
<head><title>테스트</title></head>
<body>
안녕하세요<br><font="dotum">
<?php 
include("connect.php"); 
// now you are connected and can query the database 
mysql_query("SET NAMES 'utf8'");
$result = mysql_query("select vid, knocon from kverbs where knocon LIKE \"가\""); 

// loop through the results with mysql_fetch_array() 
while($row = mysql_fetch_array($result)){ 
  echo $row[0]." / ".$row[1]."<br>\n"; 
} 
echo "안녕하세요";
// don't forget to close the mysql connection 
mysql_close(); 
?> </font>
</body>
</html>