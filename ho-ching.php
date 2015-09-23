<?php

include("k2e_name.php");
include("connect2.php"); 
include("utf/utf_normalizer.php");
include("jamo_con.php");
include("verb_matching.php");
include("matching.php");
include("printing.php");
include("k2e_noun_clause.php");

	mb_internal_encoding( 'UTF-8' );
	mb_regex_encoding('utf-8');
	mysql_query("SET NAMES 'utf8'");

if (empty($_POST['test_input'])) {
//	$test_string = "김태연 중학교 담임 선생님";
// 	$test_string = "태연이네 중학교 담임 선생님";
// 	$test_string = "그 중학교 담임 선생님";
// 	$test_string = "이 이모";
// 	$test_string = "이 독고영재 이모";
// 	$test_string = "그 독고 영재 이모";
// 	$test_string = "이 태연이 이모";
// 	$test_string = "그 태연이네 이모";
// 	$test_string = "그 김영삼 전 대통령";
//  $test_string = "그 아저씨"; // supposed to translate as "the man". but it is not doing like that now.
	$test_string = "이 전 대통령";  // supposed to translate as former president Lee. but it is not doing like that now.

}
else
{ 
	$test_string = $_POST['test_input'];
}
 echo "input string is ".$test_string."<br /><br />";


$b4_title = preg_split('/ /', $test_string, -1, PREG_SPLIT_NO_EMPTY);


//echo sizeof($title);//returns number of arrays.
$special = check_sp_case($b4_title);

if($special)
{
	
	$decomp_test_string = $test_string;
	utf_normalizer::nfd(&$decomp_test_string);
	$shifted_test_string = jamo_to_co_jamo($decomp_test_string);
	
	$title = preg_split('/ /', $test_string, -1, PREG_SPLIT_NO_EMPTY);
	$decomp_title = preg_split('/ /', $decomp_test_string, -1, PREG_SPLIT_NO_EMPTY);
	$shifted_title = preg_split('/ /', $shifted_test_string, -1, PREG_SPLIT_NO_EMPTY);

	//if last word found in title table, then try to find second last word from adj table, and third last word, so on.
	//there are 2 ending conditions of loop.
	//1. can not find anymore adj from table.
	//2. 3 adjs found
	//when it can not find in adj table anymore, then try to find pronoun, then if it matches use pronoun, if it doesn't try to do name translator.
	

}
else
{
	
	if(mb_substr($test_string,-1,1) == "님")
	{
		$test_string = mb_substr($test_string,0,-1);
	}
	else
	{
		$test_string = $test_string;
	}
	
	
	
	$combined_eng = "";
	$catagory = "";
	
	//if last word found in title table, then try to find second last word from adj table, and third last word, so on.
	//there are 2 ending conditions of loop.
	//1. can not find anymore adj from table.
	//2. 3 adjs found
	//when it can not find in adj table anymore, then try to find pronoun, then if it matches with a pronoun, if it doesn't try to do name translator.
	
	
	$title1 = preg_split('/ /', $test_string, -1, PREG_SPLIT_NO_EMPTY);
	
	if(is_pronoun($title1[0]) == "")
	{
		
	}
	else
	{
		$pronoun_combined_eng = is_pronoun($title1[0]);
		$test_string = mb_substr($test_string,2,mb_strlen($test_string));

		
	}
	
	$decomp_test_string = $test_string;
	utf_normalizer::nfd(&$decomp_test_string);
	$shifted_test_string = jamo_to_co_jamo($decomp_test_string);
	
	$title = preg_split('/ /', $test_string, -1, PREG_SPLIT_NO_EMPTY);
	$decomp_title = preg_split('/ /', $decomp_test_string, -1, PREG_SPLIT_NO_EMPTY);
	$shifted_title = preg_split('/ /', $shifted_test_string, -1, PREG_SPLIT_NO_EMPTY);
	
	
	
	$SQL1 = "SELECT ktitle,etitle,catagory FROM trans_ktitle WHERE ktitle='".$title[sizeof($title)-1]."'";
	$result1 = mysql_query($SQL1);
 	if(mysql_num_rows($result1) > 0)//if title does match with title_database, then get into this if.
 	{
	 	$row1 = mysql_fetch_array($result1);
	 	$catagory = $row1[catagory];
	 	$combined_eng = $row1[etitle].$combined_eng;
	 	
	 	for($i=1;$i<5;$i++)
	 	{//try to find title_adj until fourth time.
		 	$SQL2 = "SELECT ktitle_adj,etitle_adj FROM trans_kadj_".$catagory." WHERE ktitle_adj='".$title[sizeof($title) - (1 + $i)]."'";
		 	$result2 = mysql_query($SQL2);
		 	
		 	if(mysql_num_rows($result2) > 0)
		 	{
			 	$row2 = mysql_fetch_array($result2);
			 	if($row2[etitle_adj] == substr($combined_eng,0,strpos($combined_eng," ")))
			 	{//get into this if when adj found was added twice
				 	
			 	}
			 	else
			 	{
				 	$combined_eng = $row2[etitle_adj]." ".$combined_eng;
			 	}
			 	
		 	}
		 	else
		 	{//we assume here the word infront is not adj, then it might be a name, or pronoun. In the case of name, check 태연이네, and also check 태연이 삼촌.

				 	$check_surname_only = check_surname($title[sizeof($title) - (1 + $i)]);
				 	if(!($check_surname_only == ""))
				 	{
					 	$combined_eng = $combined_eng." ".$check_surname_only;
				 	}
				 	else
				 	{
					 	//echo $title[sizeof($title) - (1 + $i)]."<br><br><br>";
					 	$is_ne = check_ne($title[sizeof($title) - (1 + $i)]);
					 	
					 	if($is_ne == "'s")//the name left contains 네 at the end.
					 	{
						 	$only_name = mb_substr($title[sizeof($title) - (1 + $i)],0,-1);
						 	if (mb_substr($only_name,-1,1) == "이")
						 	{
							 	$only_name = mb_substr($only_name,0,-1);
						 	}
	
						 	$name = k2e_name($only_name,1);
						 	$name = $name.$is_ne;
						 	$combined_eng = $name." ".$combined_eng;
					 	}
					 	else
					 	{
						 	
						 	if(check_i_exist($title[sizeof($title) - (1 + $i)]))
						 	{//if the name ends with 이 then get into this if.(eg. 태연이, 경석이, etc)
							 	$name = k2e_name(mb_substr($title[sizeof($title) - (1 + $i)],0,-1),1);
						 	}
						 	else
						 	{
							 	if(check_surname(mb_substr($title[sizeof($title) - (1 + $i)],0,1)) == "")
							 	{//check if first character is a surname or not, get into this if, if that 1st character is not a surname
								 	$name = k2e_name($title[sizeof($title) - (2 + $i)].$title[sizeof($title) - (1 + $i)],1);
							 	}
							 	else
							 	{
								 	$name = k2e_name($title[sizeof($title) - (2 + $i)].$title[sizeof($title) - (1 + $i)],0);
							 	}
							 	
						 	}
						 	$combined_eng = $combined_eng." ".$name;
					 	}
				 	}

			 	$combined_eng = $pronoun_combined_eng." ".$combined_eng;
			 	break;
		 	}
	 	}
	 	
	 	echo "catagory:".$catagory."<br><br>";
	 	echo "<b>combined:   ".$combined_eng."</b><br><br>";
 	}
 	else
 	{
	 	echo "SORRY this is not a title";
 	}
	
	
	
}





function check_sp_case($title)
{
	if(($title[sizeof($title)-1] == "스님") || ($title[sizeof($title)-1] == "도련님"))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function check_ne($first_name)
{
	//echo mb_substr($first_name,-1,1);
	
	if((mb_substr($first_name,-2,2) == "이네") || (mb_substr($first_name,-1,1) == "네"))
	{
		return "'s";
	}
	else
	{
		return "";
	}	
	
}

function check_surname($is_surname)
{
	$SQL_func1 = "SELECT kname,ename FROM trans_kspsurnames WHERE kname='".mb_convert_encoding($is_surname,'UTF-8')."'";
	$result_func1 = mysql_query($SQL_func1);
 	if(mysql_num_rows($result_func1) > 0)
 	{
	 	$row_func1 = mysql_fetch_array($result_func1);
	 	$only_surname = $row_func1[ename];
	 	return $only_surname;
 	}
	else
	{
		$SQL_func2 = "SELECT kname,ename FROM trans_ksurnames WHERE kname='".mb_convert_encoding($is_surname,'UTF-8')."'";
		$result_func2 = mysql_query($SQL_func2);
	 	if(mysql_num_rows($result_func2) > 0)
	 	{
		 	$row_func2 = mysql_fetch_array($result_func2);
		 	$only_surname = $row_func2[ename];
		 	return $only_surname;
	 	}
	 	else
	 	{
		 	return "";
	 	}
	}
	
	
	
}

function check_i_exist($fname)
{
	if(mb_substr($fname,-1,1) == "이")
	{
		return true;
	}
	else
	{
		return false;
	}
}

function is_pronoun($left_over)
{
	if($left_over == "그")
	{
		return "the";
	}
	if($left_over == "저")
	{
		return "that";
	}
	if($left_over == "이")
	{
		return "this";	
	}
	else
	{
		return "";
	}
	
}

?>
<html>
<body>
<p><form action="ho-ching.php" method="post"> Input Korean here:<br>
<textarea rows="5" cols="25" name="test_input" style="background:#C9AEFF;"></textarea>
<input type="submit" style="background:#C9AEFF;padding:3px;"/></p> 
</form> 
</body>
</html>