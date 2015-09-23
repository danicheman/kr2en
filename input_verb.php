<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<title>Verb Entry Form</title>
<style type="text/css">
<!--
.special {
	background-color: #996666;
}
.meaning {
	background-color: #99FFFF;
}
.korean {
	background-color: #66FF66;
}
-->
</style>
</head>

<body>
<script language="javascript">

	<!-- function for input_verb form's validation -->

	function valid_verb() {
		
		var knocon = document.input_verb.knocon.value;
		var kcon = document.input_verb.kcon.value;
		var validation = true;
		var errorMsg="";
		
		// check if given and family name are entered
		if (knocon=="") {	
			errorMsg = errorMsg + "Please enter knocon.\n";
			validation = false;
		}
		if (kcon=="") {	
			errorMsg = errorMsg + "Please enter kcon.\n";
			validation = false;
		}

		// alert message 
		if (!validation) {
			alert("Application incomplete!\n\n"+errorMsg);
		}
		return validation;	 	
	}
</script>






<?php
//if we have the values, input them into the database.
include('connect.php');

	mb_internal_encoding( 'UTF-8' );
	mb_regex_encoding('utf-8');
	mysql_query("SET NAMES 'utf8'");
	
// if (isset($_POST)) {
// 	echo '<pre>';
// 	print_r($_POST);
// 	echo '</pre>';
// }

?>
<?php
function display(){
	?>

<h1 align="center" >Verb Entry Form</h1>
<form method="post" action="<?php echo("$_SERVER[PHP_SELF]"); ?>" onsubmit="return valid_verb()" name="input_verb" >
<table width="713" border="1" cellspacing="3" cellpadding="3" align="center">
  <tr>
    <th nowrap="nowrap">Variable</th>
    <td>NULL</td>
    <td>Value</td>
    <td>Comments</td>
    </tr>
  <tr>
    <th width="197" nowrap="nowrap" class="korean">knocon</th>
    <td width="30">&nbsp;</td>
    <td width="144"><input type="text" name="knocon" /></td>
    <td width="293">Unconjugated, not null (줍) </td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="korean">knocon_conj</th>
    <td><input type="checkbox" checked name="knocon_conj_c" value="checkbox" /></td>
    <td><input type="text" name="knocon_conj" /></td>
    <td>주우</td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="korean">kcon</th>
    <td>&nbsp;</td>
    <td><input type="text" name="kcon" /></td>
    <td>Conjugated 주워 </td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="korean">other</th>
    <td><input type="checkbox" checked name="other_c" value="checkbox" /></td>
    <td><input type="text" name="other" /></td>
    <td>돼 -&gt; 되어 </td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="meaning">context</th>
    <td>&nbsp;</td>
    <td><input name="context" type="text" value="1"/></td>
    <td>default 1 </td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="meaning">type</th>
    <td>&nbsp;</td>
    <td><input type="text" name="type" /></td>
    <td>0 is intrinsitive 1 is seperable 2 is inseperable</td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="meaning">ktype</th>
    <td>&nbsp;</td>
    <td><input type="text" name="ktype" /></td>
    <td>0 is action verb  1 is descriptive verb</td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="meaning">verb_word</th>
    <td><input type="checkbox" checked name="verb_word_c" value="checkbox" /></td>
    <td><input type="text" name="verb_word" /></td>
    <td>which word in the multi-word verb is conjugated?</td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="meaning">auxillary_verb</th>
    <td><input type="checkbox" checked name="auxillary_verb_c" value="checkbox" /></td>
    <td><input type="text" name="auxillary_verb" /></td>
    <td>0 be 1 have 2 do 3 go</td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="meaning">withhuman</th>
    <td>&nbsp;</td>
    <td><input name="withhuman" type="text" value="0" /></td>
    <td>boolean, 0 is false </td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="meaning">withthing</th>
    <td>&nbsp;</td>
    <td><input name="withthing" type="text" value="0" /></td>
    <td>boolean, 0 is false </td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="meaning">withplace</th>
    <td>&nbsp;</td>
    <td><input name="withplace" type="text" value="0" /></td>
    <td>boolean, 0 is false </td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="meaning">withabstract</th>
    <td>&nbsp;</td>
    <td><input name="withabstract" type="text" value="0" /></td>
    <td>boolean, 0 is false </td>
    </tr>
  <tr>
    <th height="28" nowrap="nowrap" class="meaning">english</th>
    <td>&nbsp;</td>
    <td><input type="text" name="english" /></td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="meaning">adjective</th>
    <td><input type="checkbox" checked name="adjective_c" value="checkbox" /></td>
    <td><input type="text" name="adjective" /></td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="meaning">noun</th>
    <td><input type="checkbox" checked name="noun_c" value="checkbox" /></td>
    <td><input type="text" name="noun" /></td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="meaning">order_action</th>
    <td>&nbsp;</td>
    <td><input name="order_action" type="text" value="0" /></td>
    <td>0 = swap with object, 1 swap with oe, 2 swap with oe or object</td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="meaning">special</th>
    <td>&nbsp;</td>
    <td><input name="special" type="text" value="0" /></td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="meaning">entry_id</th>
    <td>&nbsp;</td>
    <td><input name="entry_id" type="text" value="daniche" /></td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="special">translation_meaning</th>
    <td>&nbsp;</td>
    <td><input type="text" name="translation_meaning" /></td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="special">translation_movement</th>
    <td>&nbsp;</td>
    <td><input type="text" name="translation_movement" /></td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="special">translation_preposition_modify</th>
    <td>&nbsp;</td>
    <td><input type="text" name="translation_preposition_modify" /></td>
    <td>&nbsp;</td>
  </tr>
    <tr>
    <td colspan="4" align="right"><input type="submit" name="submit" value="Submit" /></td>

  </tr>
  
</table>
</form>

<?php
}
?>

<?php

function database_writing(){
	$knocon .='\'' ;	
	$knocon .= $_POST['knocon'];
	$knocon .='\'' ;
	$knocon_conj_c = $_POST['knocon_conj_c'];
	
	//this will have check box
	if ($knocon_conj_c=="checkbox"){
		$knocon_conj="null";
	}else{
		$knocon_conj .='\'' ;
		$knocon_conj .= $_POST['knocon_conj'];
		$knocon_conj .='\'' ;
	}
	$kcon .='\'' ;
	$kcon .= $_POST['kcon'];
	$kcon .='\'' ;

	//this will have check box
	$other_c= $_POST['other_c'];

	if ($other_c=="checkbox"){
		$other="null";
	}else{
		$other.='\'' ;
		$other.= $_POST['other'];
		$other.='\'' ;
	}

	$query="insert into trans_kverbs(knocon,knocon_conj,kcon,other,entry_date) values($knocon,$knocon_conj,$kcon,$other,now())";
	$result=mysql_query($query) or die("could not query database".mysql_error());;
	
	if(mysql_affected_rows()>0){
		$trans_kverbs = true;
	}else{
		$trans_kverbs = false;
	}

	//build query 
	$query1="Select * From trans_kverbs Where knocon = $knocon";

	//query database
	$results1=mysql_query($query1) or die("failed to query database");
	
	//check how many store rows of data of id and password
	$rows_number1=mysql_num_rows($results1);
			
	while ($row = mysql_fetch_array($results1)){
		$vin .= $row[0];
	}
		
	$context = $_POST['context'];
	$type = $_POST['type'];

	$ktype = $_POST['ktype'];
	$verb_word_c=$_POST['verb_word_c'];
	if ($verb_word_c=="checkbox"){
		$verb_word='NULL';
	}else{
		$verb_word = $_POST['verb_word'];
	}
	
	$auxillary_verb_c=$_POST['auxillary_verb_c'];
	if ($auxillary_verb_c=="checkbox"){
		$auxillary_verb='NULL';
	}else{
		$auxillary_verb = $_POST['auxillary_verb'];
	}
	
	$withhuman = $_POST['withhuman'];
	$withthing = $_POST['withthing'];
	$withplace = $_POST['withplace'];
	$withabstract = $_POST['withabstract'];
	$english = $_POST['english'];
		
	$adjective_c = $_POST['adjective_c'];
	if ($adjective_c=="checkbox"){
	$adjective='NULL';
	}else{
	$adjective = $_POST['adjective'];	
	}
	
	$noun_c = $_POST['noun_c'];
	if ($noun_c=="checkbox"){
		$noun='NULL';
	}else{
		$noun = $_POST['noun'];	
	}
	
	$order_action .= $_POST['order_action'];
	$special .= $_POST['type'];

	$query="insert into trans_kverb_meanings(vin,context,type,ktype,verb_word,auxillary_verb,withhuman,withthing,withplace,withabstract,english,adjective,noun,order_action,special) values('$vin','$contex','$type','$ktype',$verb_word,$auxillary_verb,'$withhuman','$withthing','$withplace','$withabstract','$english','$adjective','$noun','$order_action','$special')";

	$result=mysql_query($query) or die("could not query database".mysql_error());;
	
	if(mysql_affected_rows()>0){
		$trans_kverb_meanings = true;
	}else{
		$trans_kverb_meanings = false;
	}
	
	$trans_kverb_translation = true;
	if($special==1){
		$translation_meaning = $_POST['translation_meaning'];
		$translation_movement = $_POST['translation_movement'];
		$translation_preposition_modify = $_POST['translation_preposition_modify'];
		$query="insert into trans_kverb_translation(vin,translation_meaning,translation_movement,translation_preposition_modify) values('$vin','$translation_meaning','$translation_movement','$translation_preposition_modify')";
		$result=mysql_query($query) or die("could not query database".mysql_error());;
		
		if(mysql_affected_rows()>0){
			$trans_kverb_translation = true;
		}else{
			$trans_kverb_translation = false;
		}
	}

	if($trans_kverb_translation == true && $trans_kverbs == true && $trans_kverb_meanings == true){
		echo "<h2 color='#FF6633'>verb was successfully added to the database</h2>";
		$trans_kverb_translation = false;  
		$trans_kverbs = false;  
		$trans_kverb_meanings = false;
	}else{
		echo "<h2 color='#FF6633'align='center'>Please check your information again, it did not save on the database</h2>";
	}
}

if(isset($_POST['submit'])){
	//database_writing();
	database_writing();
	display();
}else{
display();
}

?>












<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
