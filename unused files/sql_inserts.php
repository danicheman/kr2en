<?php
  /* What's being tested here is the ability to add,
   * edit, (and delete?) SQL entries for the words,
   * from VARIABLES.  
   * NOTE: Need to separate entry information (dates, id)
   * from word tables to optimize them.
   */
	include("connect.php"); 
	include("utf/utf_normalizer.php");
	include("jamo_con.php");

	mb_internal_encoding( 'UTF-8' );
	mb_regex_encoding('utf-8');
	mysql_query("SET NAMES 'utf8'");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<meta http-equiv="content-language" content="ko"> 
<meta http-equiv="content-type" content="text/html; charset=utf8"> 
<html> 
<?php
	mysql_query("INSERT INTO `trans_kverbs` ( `vin` , `knocon` , `kcon` , `other` , `entry_date` , `entry_id` ) VALUES ( NULL , 'ธิ', 'ธิพ๎', NULL , '2007-05-13', 'niche')");