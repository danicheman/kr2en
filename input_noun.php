<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<title>Noun Entry Form</title>
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

	function valid_noun() {
		var noun1 = document.input_noun.noun1.value;
		var validation = true;
		var errorMsg="";
	
		// check if given and family name are entered
		if (noun1=="") {	
			errorMsg = errorMsg + "Please enter noun1\n";
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

<h1 align="center" >Noun Entry Form</h1>
<form method="post" action="<?php echo("$_SERVER[PHP_SELF]"); ?>" onsubmit="return valid_noun()" name="input_noun" >
<table width="713" border="1px" cellspacing="3" cellpadding="3" align="center">
  <tr>
    <th nowrap="nowrap">Variable</th>
    <td>NULL</td>
    <td>Value</td>
    <td>Comments</td>
    </tr>
  <tr>
    <th width="197" nowrap="nowrap" class="korean">noun1</th>
    <td width="30">&nbsp;</td>
    <td width="144"><input type="text" name="noun1" /></td>
    <td width="293">&nbsp;</td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="korean">noun2</th>
    <td>&nbsp;</td>
    <td><input type="text" name="noun2" /></td>
    <td>&nbsp;</td>
  </tr>
    <th nowrap="nowrap" class="meaning">context</th>
    <td>&nbsp;</td>
    <td><input name="context" type="text" value="1"/></td>
    <td>default 1</td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="meaning">isplural</th>
    <td>&nbsp;</td>
    <td><input type="text" name="isplural" value="0"/></td>
    <td>default 0</td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="meaning">iscount</th>
    <td>&nbsp;</td>
    <td><input type="text" name="iscount" value="0"/></td>
    <td>default 0</td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="meaning">keywords</th>
    <td>&nbsp;</td>
    <td><input type="text" name="keywords" /></td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="meaning">particle</th>
    <td>&nbsp;</td>
    <td><input type="text" name="particle" /></td>
    <td>0 no particle, 1 the</td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="meaning">particle_no_pass</th>
    <td><input type="checkbox" checked name="particle_no_pass_c" value="checkbox" /></td>
    <td><input name="particle_no_pass" type="text" /></td>
    <td>can particle like 'the' move past this?</td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="meaning">three_ps</th>
    <td><input type="checkbox" checked name="three_ps_c" value="checkbox" /></td>
    <td><input name="three_ps" type="text" value="1" /></td>
    <td>0 it 1 he 2 she NULL not 3rd person singular</td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="meaning">english</th>
    <td>&nbsp;</td>
    <td><input name="meaning" type="text"/></td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="meaning">qword</th>
    <td>&nbsp;</td>
    <td><input name="qword" type="text" value="0" /></td>
    <td>default 0. (0. which, 1 who 2. where 3. when)</td>
    </tr>
  <tr>
    <th height="28" nowrap="nowrap" class="meaning">no_particle</th>
    <td><input type="checkbox" checked name="no_particle_c" value="checkbox" /></td>
    <td><input type="text" name="no_particle" /></td>
    <td>if the word does not come at the end of clause</td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="meaning">with_oe</th>
    <td><input type="checkbox" checked name="with_oe_c" value="checkbox" /></td>
    <td><input type="text" name="with_oe" /></td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="meaning">with_oeseo</th>
    <td><input type="checkbox" checked name="with_oeseo_c" value="checkbox" /></td>
    <td><input type="text" name="with_oeseo" /></td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="meaning">with_ro</th>
    <td><input type="checkbox" checked name="with_ro_c" value="checkbox" /></td>
    <td><input name="with_ro" type="text" /></td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="meaning">special</th>
    <td>&nbsp;</td>
    <td><input name="special" type="text" value="0" /></td>
    <td>default 0</td>
  </tr>
  <tr>
    <th nowrap="nowrap" class="special">translation_meaning</th>
    <td><input type="checkbox" checked name="translation_meaning_c" value="checkbox" /></td>
    <td><input type="text" name="translation_meaning" /></td>
    <td>in put a word and possibly get a new meaning</td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="special">translation_movement</th>
    <td><input type="checkbox" checked name="translation_movement_c" value="checkbox" /></td>
    <td><input type="text" name="translation_movement" /></td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <th nowrap="nowrap" class="special">translation_preposition_select</th>
    <td><input type="checkbox" checked name="translation_preposition_select_c" value="checkbox" /></td>
    <td><input type="text" name="translation_preposition_select" /></td>
    <td>&nbsp;</td>
  </tr>
   <tr>
    <th nowrap="nowrap" class="special">translation_preposition_1</th>
    <td><input type="checkbox" checked name="translation_preposition_1_c" value="checkbox" /></td>
    <td><input type="text" name="translation_preposition_1" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <th nowrap="nowrap" class="special">translation_preposition_2</th>
    <td><input type="checkbox" checked name="translation_preposition_2_c" value="checkbox" /></td>
    <td><input type="text" name="translation_preposition_2" /></td>
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
	$noun1 .='\'' ;	
	$noun1 .= $_POST['noun1'];
	$noun1 .='\'' ;

	$noun2 .='\'' ;
	$noun3 .= $_POST['noun2'];
	$noun2 .='\'' ;
	
	$query="insert into trans_knouns(noun1,noun2) values($noun1,$noun2)";
	$result=mysql_query($query) or die("could not query database".mysql_error());;
	
	if(mysql_affected_rows()>0){
		$trans_kverbs = true;
	}else{
		$trans_kverbs = false;
	}

	//build query 
	$query1="Select * From trans_knouns Where noun1 = $noun1";

	//query database
	$results1=mysql_query($query1) or die("failed to query database");
	
	//check how many store rows of data of id and password
	$rows_number1=mysql_num_rows($results1);
			
	while ($row = mysql_fetch_array($results1)){
		$nin .= $row[0];
	}
		
	$context = $_POST['context'];
	$isplural = $_POST['isplural'];
	$iscount = $_POST['iscount'];
	$keywords = $_POST['keywords'];
	$particle = $_POST['particle'];
	
	$particle_no_pass_c=$_POST['particle_no_pass_c'];
	$particle_no_pass = $_POST['particle_no_pass'];
	
	if ($particle_no_pass_c=="checkbox"){
		$particle_no_pass='NULL';
	}else{
		$particle_no_pass = $_POST['particle_no_pass'];
	}

	$three_ps = $_POST['three_ps'];
	$meaning = $_POST['meaning'];
	$qword = $_POST['qword'];
	
	$no_particle_c=$_POST['no_particle_c'];
	if ($no_particle_c=="checkbox"){
		$no_particle='NULL';
	}else{
		$no_particle = $_POST['no_particle'];
	}
	
	$three_ps_c=$_POST['three_ps_c'];
	if ($three_ps_c=="checkbox"){
		$three_ps='NULL';
	}else{
		$three_ps = $_POST['three_ps'];
	}
	
	$with_oe_c=$_POST['with_oe_c'];
	if ($with_oe_c=="checkbox"){
		$with_oe='NULL';
	}else{
		$with_oe = $_POST['with_oe'];
	}
	$with_oeseo_c=$_POST['with_oeseo_c'];
	if ($with_oeseo_c=="checkbox"){
		$with_oeseo='NULL';
	}else{
		$with_oeseo = $_POST['with_oeseo'];
	}
	$with_ro_c=$_POST['with_ro_c'];
	if ($with_ro_c=="checkbox"){
		$with_ro='NULL';
	}else{
		$with_ro = $_POST['with_ro'];
	}
	
	$special = $_POST['special'];

	$query="insert into trans_knoun_meanings(nin,context,isplural,iscount,keywords,particle,particle_no_pass,three_ps,english,qword,no_particle,with_oe,with_oeseo,with_ro,special) values('$nin','$context','$isplural','$iscount','$keywords','$particle','$particle_no_pass','$three_ps','$meaning','$qword','$no_particle','$with_oe','$with_oeseo','$with_ro','$special')";
	
	$result=mysql_query($query) or die("could not query database".mysql_error());;
	
	if(mysql_affected_rows()>0){
		$trans_kverb_meanings = true;
	}else{
		$trans_kverb_meanings = false;
	}
	$trans_kverb_translation = true;
	
	if($special==1){
		$translation_meaning_c=$_POST['translation_meaning_c'];
		if ($translation_meaning_c=="checkbox"){
			$translation_meaning='NULL';
		}else{
			$translation_meaning = $_POST['translation_meaning'];
		}
		$translation_movement_c=$_POST['translation_movement_c'];
		if ($translation_movement_c=="checkbox"){
			$translation_movement='NULL';
		}else{
			$translation_movement = $_POST['translation_movement'];
		}
		$translation_preposition_select_c=$_POST['translation_preposition_select_c'];
		if ($translation_preposition_select_c=="checkbox"){
			$translation_preposition_select='NULL';
		}else{
			$translation_preposition_select = $_POST['translation_preposition_select'];
		}
		$translation_preposition_1_c=$_POST['translation_preposition_1_c'];
		if ($translation_preposition_1_c=="checkbox"){
			$translation_preposition_1='NULL';
		}else{
			$translation_preposition_1 = $_POST['translation_preposition_1'];
		}
		$translation_preposition_2 = $_POST['translation_preposition_2'];
			$translation_preposition_2_c=$_POST['translation_preposition_2_c'];
		if ($translation_preposition_2_c=="checkbox"){
			$translation_preposition_2='NULL';
		}else{
			$translation_preposition_2 = $_POST['translation_preposition_2'];
		}
		$query="insert into trans_knoun_translation(nin,translation_meaning,translation_movement,translation_preposition_select,translation_preposition_1,translation_preposition_2) values('$nin','$translation_meaning','$translation_movement','$translation_preposition_select','$translation_preposition_1','$translation_preposition_2')";
		$result=mysql_query($query) or die("could not query database".mysql_error());;
		if(mysql_affected_rows()>0){
			$trans_kverb_translation = true;
			echo "success";
		}else{
			$trans_kverb_translation = false;
		}
	}

	if($trans_kverb_translation == true && $trans_kverbs == true && $trans_kverb_meanings == true){
		echo "<h2 color='#FF6633'>all inputs have been successfully inserted </h2>";
		$trans_kverb_translation = false;  
		$trans_kverbs = false;  
		$trans_kverb_meanings = false;
	}else{
		echo "<h2 color='#FF6633'align='center'>Please check database your inserted information did not save on your database</h2>";
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
