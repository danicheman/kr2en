<?php
$hostname="localhost"; 
$mysql_login="root"; 
$mysql_password=""; 
$database="dictionary2"; 

// connect to the database server 
if (!($db = mysql_pconnect($hostname, $mysql_login , $mysql_password))){ 
  die("Can't connect to database server.");     
}else{ 
  // select a database 
    if (!(mysql_select_db("$database",$db))){ 
      die("Can't connect to database."); 
    } 
} 
?> 