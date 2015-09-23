<?php


function k2e_name($korean_name,$only_fname)
{
	
	mb_internal_encoding( 'UTF-8' );
	mb_regex_encoding('utf-8');
	mysql_query("SET NAMES 'utf8'");

	$test_string = mb_convert_encoding($korean_name, "UTF-8");
    //English first and last names
	$eng_fname = "";
	$eng_lname = "";	
		
	 	$decomp_test_string = $test_string;
		utf_normalizer::nfd(&$decomp_test_string);
		$shifted_test_string = jamo_to_co_jamo($decomp_test_string);
	
	$names = preg_split('/ /', $test_string, -1, PREG_SPLIT_NO_EMPTY);
	$decomp_names = preg_split('/ /', $decomp_test_string, -1, PREG_SPLIT_NO_EMPTY);
	$shifted_names = preg_split('/ /', $shifted_test_string, -1, PREG_SPLIT_NO_EMPTY);
	//We are only translating a Korean first name to English
	if($only_fname)
	{
		$composed_str_givenname = "";
		//romanize the first name syllable by syllable.
		for($i=0;$i<mb_strlen(&$decomp_names[0]);$i++)
		{
			$ii = determine_order(mb_substr($decomp_names[0],$i,1));
			$composed_str_givenname .= k2e_romanization2(mb_ereg_replace(' ','',mb_substr($shifted_names[0],$i,1)),$ii);
		}
		
		return ucfirst($composed_str_givenname);
				
	}
	else
	{
		
		
		if (mb_strpos($test_string," "))//try to find space(" ") in string, and if it finds it returns true
		{//we assume that if there is space in names, it is separated by surname, and givenname.
		
			
			$SQL1 = "SELECT kname,ename FROM trans_kspsurnames WHERE kname = '".mb_convert_encoding($names[0],'utf-8')."'";
			$result1 = mysql_query($SQL1);
		 	if(mysql_num_rows($result1) > 0)//if input surname with space exists in special db, then get inside this if
		 	{
			 	while($row1 = mysql_fetch_array($result1))
			 	{
					if(mb_convert_encoding($names[0],'utf-8') == mb_convert_encoding($row1[kname],'utf-8'))
					{//names with space, and found surname as special(2 letters for surname)
						//if it gets to inside this if, then it means names without space have special surname.
						$eng_lname = $row1[ename];
						
						$composed_str = "";
						for($i=0;$i<mb_strlen(&$decomp_names[1]);$i++)
						{
			
							$ii = determine_order(mb_substr($decomp_names[1],$i,1));
							$composed_str .= k2e_romanization2(mb_ereg_replace(' ','',mb_substr($shifted_names[1],$i,1)),$ii);
						}
						$eng_fname = ucfirst($composed_str);
					}
				}
			}
			else
			{
				$SQL2 = "SELECT kname, ename FROM trans_ksurnames WHERE kname = '".mb_convert_encoding($names[0],'utf-8')."'";
				$result2 = mysql_query($SQL2);
				if(mysql_num_rows($result2) > 0)//if input surname with space does not exists in special db, then check normal surname table.
				{
					while($row2 = mysql_fetch_array($result2))
				 	{
						if(mb_convert_encoding($names[0],'utf-8') == mb_convert_encoding($row2[kname],'utf-8'))
						{//names with space, and found surname as special(2 letters for surname)
							//if it gets to inside this if, then it means names with space have special surname.
							$eng_lname = $row2[ename];
							$composed_str = "";
							for($i=0;$i<mb_strlen(&$decomp_names[1]);$i++)
							{
								$ii = determine_order(mb_substr($decomp_names[1],$i,1));
								$composed_str .= k2e_romanization2(mb_ereg_replace(' ','',mb_substr($shifted_names[1],$i,1)),$ii);
							}
							$eng_fname = ucfirst($composed_str);
						}
					}
				}
				else
				{
					$composed_str_surname = "";
					$composed_str_givenname = "";
					for($i=0;$i<mb_strlen(&$decomp_names[0]);$i++)
					{
		
						$ii = determine_order(mb_substr($decomp_names[0],$i,1));
						$composed_str_surname .= k2e_romanization2(mb_ereg_replace(' ','',mb_substr($shifted_names[0],$i,1)),$ii);
					}
					for($i=0;$i<mb_strlen(&$decomp_names[1]);$i++)
					{
		
						$ii = determine_order(mb_substr($decomp_names[1],$i,1));
						$composed_str_givenname .= k2e_romanization2(mb_ereg_replace(' ','',mb_substr($shifted_names[1],$i,1)),$ii);
					}
					//echo "<br><br>surname with space:".ucfirst($composed_str_surname)."<br><br>";
					$eng_lname = ucfirst($composed_str_surname);
					//echo "<br><br>givenname with space:".ucfirst($composed_str_givenname);
					$eng_fname = ucfirst($composed_str_givenname);
				}
			}
		}
		else
		{//get into this else {} when there is no spaces
			
			$SQL1 = "SELECT kname,ename FROM trans_kspsurnames WHERE kname = '".mb_convert_encoding(mb_substr($test_string,0,2),'utf-8')."'";
			$result1 = mysql_query($SQL1);
		 	if(mysql_num_rows($result1) > 0)//if input surname without space exists in special db, then get inside this if
		 	{
		 	 	while($row1 = mysql_fetch_array($result1))
			 	{
		// 		 	echo mb_convert_encoding(mb_substr($test_string,0,2),'utf-8')."<br><br>";
		// 		 	echo mb_convert_encoding($row1[kname],'utf-8');
						if(mb_convert_encoding(mb_substr($test_string,0,2),'utf-8') == mb_convert_encoding($row1[kname],'utf-8'))
						{//first 2 chars of names without space, and found surname as special
						
							//echo "<br><br>surname without space:".$row1[ename]."<br><br>";//if it gets to inside this if, then it means names without space have special surname.
							$eng_lname = $row1[ename];
							//echo "<br><br>givenname without space:";
							
							
							
							
							$nospace_decomp_test_string = mb_convert_encoding(mb_substr($test_string,2),'utf-8');
							utf_normalizer::nfd(&$nospace_decomp_test_string);
							$nospace_shifted_test_string = jamo_to_co_jamo($nospace_decomp_test_string);
							
							
							$composed_str = "";
							for($i=0;$i<mb_strlen(&$nospace_decomp_test_string);$i++)
							{
				
								$ii = determine_order(mb_substr($nospace_decomp_test_string,$i,1));
								$composed_str .= k2e_romanization2(mb_ereg_replace(' ','',mb_substr($nospace_shifted_test_string,$i,1)),$ii);
							}
							$eng_fname = ucfirst($composed_str);
						}
			 	}
		 	}
		 	else
		 	{//looking for a one syllable sirname
		 	
		 		//create first and last names in decomposed and shifted variables.
			 	$nospace_fname_decomp_test_string = mb_convert_encoding(mb_substr($test_string,1),'utf-8');
				utf_normalizer::nfd(&$nospace_fname_decomp_test_string);
				$nospace_fname_shifted_test_string = jamo_to_co_jamo($nospace_fname_decomp_test_string);
			 	
			 	
			 	$nospace_lname_decomp_test_string = mb_convert_encoding(mb_substr($test_string,0,1),'utf-8');
				utf_normalizer::nfd(&$nospace_lname_decomp_test_string);
				$nospace_lname_shifted_test_string = jamo_to_co_jamo($nospace_lname_decomp_test_string);
		
			 	
			 	
			 	//create & execute sql query for regular one syllable sirname
				$SQL2 = "SELECT kname, ename FROM trans_ksurnames WHERE kname = '".mb_convert_encoding(mb_substr($test_string,0,1),'utf-8')."'";
				$result2 = mysql_query($SQL2);
				if(mysql_num_rows($result2) > 0)//if input surname with space does not exists in special db, then check normal surname table.
				{	//this while loop may not be necessary
					while($row2 = mysql_fetch_array($result2))
				 	{//this if statement may also not be necessary
						if(mb_convert_encoding(mb_substr($test_string,0,1),'utf-8') == mb_convert_encoding($row2[kname],'utf-8'))
						{//names without space, and found surname as special
							//echo "<br><br>surname without space:".$row2[ename]."<br><br>";//if it gets to inside this if, then it means names without space have special surname.
							$eng_lname = $row2[ename];
							//echo "<br><br>givenname without space:";
							$composed_str = "";
							//begin romanization of first name using decomposed and shifted test strings
							for($i=0;$i<mb_strlen(&$nospace_fname_decomp_test_string);$i++)
							{
								//determine the position of the hangul character and store in $ii as 1,2,or 3
								$ii = determine_order(mb_substr($nospace_fname_decomp_test_string,$i,1));
								//append the romanized hangul to $composed_str
								$composed_str .= k2e_romanization2(mb_ereg_replace(' ','',mb_substr($nospace_fname_shifted_test_string,$i,1)),$ii);
							}
							//Since this is a name, make sure that the first letter of the first name is upper case.
							$eng_fname = ucfirst($composed_str);
						}
					}
				}
				else
				{//cannot find the first character in the regular (1 syllable) sirname table
					
					//romanize both the last name and the first name.
					$composed_str_surname = "";
					$composed_str_givenname = "";
					for($i=0;$i<mb_strlen(&$nospace_lname_decomp_test_string);$i++)
					{
		
						$ii = determine_order(mb_substr($nospace_lname_decomp_test_string,$i,1));
						$composed_str_surname .= k2e_romanization2(mb_ereg_replace(' ','',mb_substr($nospace_lname_shifted_test_string,$i,1)),$ii);
					}
					for($i=0;$i<mb_strlen(&$nospace_fname_decomp_test_string);$i++)
					{
		
						$ii = determine_order(mb_substr($nospace_fname_decomp_test_string,$i,1));
						$composed_str_givenname .= k2e_romanization2(mb_ereg_replace(' ','',mb_substr($nospace_fname_shifted_test_string,$i,1)),$ii);
					}
					//echo "<br><br>surname without space:".ucfirst($composed_str_surname)."<br><br>";
					$eng_lname = ucfirst($composed_str_surname);
					//echo "<br><br>givenname without space:".ucfirst($composed_str_givenname);
					$eng_fname = ucfirst($composed_str_givenname);
				}
			}
			 	
			 	
		}
		return $eng_fname." ".$eng_lname;
	}
}
	
function k2e_romanization2($k_letter,$order)
{
	switch($order)
	{
		case 1:
			switch($k_letter)
			{
				case "ㄱ":
				return "g"; 
				break;
				
				case "ㄲ":
				return "kk"; 
				break;
				
				case 'ㄴ':
				return "n"; 
				break;
				
				case 'ㄷ':
				return "d"; 
				break;
				
				case 'ㄸ':
				return "tt";
				break;
				
				case 'ㄹ':
				return "r";
				break;
				
				case 'ㅁ':
				return "m";
				break;
				
				case 'ㅂ':
				return "b";
				break;
				
				case 'ㅃ':
				return "pp";
				break;
				
				case 'ㅅ':
				return "s";
				break;
				
				case 'ㅆ':
				return "ss";
				break;
					
				case 'ㅇ':
				return "";
				break;	
					
				case 'ㅈ':
				return "j";
				break;	
					
				case 'ㅉ':
				return "jj";
				break;	
					
				case 'ㅊ':
				return "ch";
				break;	
					
				case 'ㅋ':
				return "k";
				break;	
					
				case 'ㅌ':
				return "t";
				break;	
					
				case 'ㅍ':
				return "p";
				break;	
					
				case 'ㅎ':
				return "h";
				break;
				
				default:
				return "";
				break;	
			}
		break;
		case 2:
			switch($k_letter)
			{
				case 'ㅏ':
				return "a";
				break;
				
				case 'ㅑ':
				return "ya";
				break;	
				
				case 'ㅓ':
				return "eo";
				break;	
					
				case 'ㅕ':
				return "yeo";
				break;	
					
				case 'ㅗ':
				return "o";
				break;
						
				case 'ㅛ':
				return "yo";
				break;	
					
				case 'ㅜ':
				return "u";//I think we better change this to 'woo' or 'oo'
				break;	
					
				case 'ㅠ':
				return "yu";//I think we better change this to 'you'
				break;	
					
				case 'ㅡ':
				return "eu";
				break;	
					
				case 'ㅣ':
				return "i";
				break;	
				
				case 'ㅐ':
				return "ae";
				break;	
					
				case 'ㅔ':
				return "e";
				break;	
					
				case 'ㅒ':
				return "yae";
				break;	
					
				case 'ㅖ':
				return "ye";
				break;	
					
				case 'ㅙ':
				return "wae";
				break;	
					
				case 'ㅘ':
				return "wa";
				break;	
					
				case 'ㅚ':
				return "oe";
				break;	
					
				case 'ㅝ':
				return "wo";//I think we better change this to 'war'
				break;	
					
				case 'ㅞ':
				return "we";//I think we better change this to 'whe'. 'whe' is from where
				break;	
					
				case 'ㅟ':
				return "wi";
				break;	
					
				case 'ㅢ':
				return "ui";
				break;	
					
				default:
				return "";
				break;
			}
		break;
		case 3:
			switch($k_letter)
			{
				case 'ㄱ':
				return "k";
				break;	
					
				case 'ㄴ':
				return "n";
				break;	
					
				case 'ㄷ':
				return "t";
				break;	
					
				case 'ㄹ':
				return "l";
				break;	
					
				case 'ㅁ':
				return "m";
				break;	
					
				case 'ㅂ':
				return "p";
				break;	
					
				case 'ㅅ':
				return "t";
				break;	
					
				case 'ㅇ':
				return "ng";
				break;	
					
				case 'ㅈ':
				return "t";
				break;	
					
				case 'ㅊ':
				return "t";
				break;	
					
				case 'ㅋ':
				return "k";
				break;	
					
				case 'ㅌ':
				return "t";
				break;	
					
				case 'ㅍ':
				return "p";
				break;	
					
				case 'ㅎ':
				return "h";
				break;	
					
				default:
				return "";
				break;
			}
		break;
	}	
}
	
	function determine_order($decomp_str) {
	 	
		$jamo_string_length = mb_strlen($decomp_str, 'utf-8');
		for ($i = 0; $i <$jamo_string_length;$i++) {
			$jamo_char = mb_substr($decomp_str,$i,1);
			$hexval = bin2hex($jamo_char);
	
			$subhexval = hexdec(substr($hexval, 4));
			
			if(substr($hexval,0,3) != "e18") {//if not in the right range to translate.
				$out_string .= $jamo_char;
				continue; 
			}
	 		switch ($hexval[3]) {
		 		case 4: //
	 			return 1;
	 				break;
	 			case 5: //
	 			return 2;
	 				break;
	 			case 6: //
	 			return 3;
	 				break;
	 			default:
	 				//echo " ";
	 				$base=0;
	 				$offset=0;
	 				break;
	 		}
		}

	 }
?>