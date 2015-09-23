<?php
/*
index is the main function of the program, within this file are the objects used by 
the other functions, for clause and verb searching. The functionality of test 3 appart
from using the included files is html screen output. 
 
*/
include("connect.php"); 
include("utf/utf_normalizer.php");
include("jamo_con.php");
include("verb_matching.php");
include("matching.php");
include("printing.php");
// include("k2e_noun_clause.php");
include("sentence.php");
include("object_sentence_functions.php");
include("new_conjunction.php");
include("k2e_noun_clause_test.php");
	
//sentence clause object - it's meant to be used as an array so there's no next link
class s_clause {
	function is_clause($ic) {
		$this->isclause = $ic;
	}
	function __construct($cp, $cl, $o) {
		//비가 와서 집에 갔다 	 
		//conj_pos 7
		//conj_length 3
		//offset 0
		$this->conj_pos = $cp;//position in the sentence of the conjunction
		$this->conj_length = $cl;//length of the conjunction
		$this->offset = $o;//position from the last found conjunction or ZERO if there is no other conjunction
	}
}

//m_verb class.  Contains modified Korean verb and later can hold english translation.
class m_verb {
	function english($e, $w) {
		$this->english = $e;
		$this->isdouble = $w; // was the verb located by searching for two words?
	}
	function two_word_tense($dt) {
		$this->two_word_tense = $dt;
	}
	function __construct($t, $mv, $l) {
			$this->tense = $t;
			$this->mverb = $mv;
			$this->length = $l;
	}
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<meta http-equiv="content-language" content="ko"> 
<meta http-equiv="content-type" content="text/html; charset=utf8"> 
<html> 
<head><title>T3</title>
<style type="text/css">
<!--
#subject {color:white;background:#14223D}
#topic {color:white;background:#543C1C}
#object {background:#8B9BBA}
#verbMod {background:#DDC5A6}
#yellow {background: yellow}
#blue {color:white; background: blue}
#green {color:white; background: green}
#red {color:white; background: red}
#gray {color:white; background: gray}
blockquote { display: inline; border-bottom: thin solid gray; }

	body {
		margin:20px 20px 20px 20px;
		background:#FFE2CE;
		}
	
	#main {
		border:1px solid #62979E; 
		background:#FFF0E6;
	}

/* Looks like you have to specify the width of #menu
or IE5 Mac stretches it all the way across the div, and 
Opera streches it half way. */

	#main #menu {
		border-left:1px solid #62979E; 
		border-bottom:1px solid #62979E;
		float:right;
		width:230px;
		background:#FFF0E6;
		margin:0px 0px 10px 10px;
		}
	#main #wordmenu {
		float:left;
		width:250px;
		border: 1px solid green;
		margin: 1px 1px 1px 1px;
		background:#FFF0E6;
	}	
	div.spacer {
		clear: both;
	}
	p,h1,pre {
		margin:0px 10px 10px 10px;
		}
		
	h1 {
		font-size:14px;
		padding-top:10px;
		}
	
	#menu p { font-size:10px}
	#menu li { font-size:10px}

-->
</style>
</head>
<body>
<div id="main">
	<div id="menu">
		<h1>menu</h1><p>
		<ol>
			<li><a href="k2e_adnominal.php">This parses and translates nominalizer ending grammar patterns</a>
			<li><a href="k2e_auxillary.php">Preliminary Auxiliary Verb translator</a>
			<li><a href="..">Index page</a></p>
		</ol>
	</div>
<h1>
<span id="subject">subject</span>
<span id="topic">topic</span>
<span id="object">object</span>
<span id="verbMod">verb modifier</span>
<span id="gray">past tense</span>
<span id="blue">present tense</span>
<span id="red">present continuous</span>
<span id="green">future tense</span></h1>
<p><form action="index.php" method="post"> Input Korean here:<br>
<textarea rows="5" cols="25" name="test_input" style="background:#FFE2CE;"></textarea>
<input type="submit" style="background:#FFF7EE;padding:3px;"/></p> 
</form> 
<p>미국의 연예잡지 ''인터치''가 실시한 최근 설문조사에서 제니퍼 로페즈와 비욘세가 각각 ''헐리우드에서 가장 섹시한 엉덩이'' 1,2위에 선정되었다.</p>
<p>우리나라가 중국과 일본 틈바구니에서 고생길로 접어들었다는 사실은 어제오늘 일이 아닙니다. </p>

<?php

//check for string inputted through webpage's form, or use the "test string".
if (empty($_POST['test_input'])) {
	$test_string = "촬영이 시작되고, 광고멘트에는 \"저도 이 상품을 구입했습니다\"라는 문구가 들어있는 것을 발견한 그는 그 자리에서 해당 부분을 수정해 줄 것을 부탁했다.";
	$test_string = stripslashes($test_string);
} else { 
	if (get_magic_quotes_gpc()) {
		$test_string = stripslashes($_POST['test_input']);
	} else {
		$test_string = $_POST['test_input'];
	}
}

/* Parser.  Mark with color Korean text which is part of the object, subject, object or subject modifiers and verbs.*/
	//This split splits periods followed by a space.  This is not always the case.
	//Some news sentences do not start with a space!
	//**need to split without space not following a capital english letter or between digits.**

	mb_internal_encoding( 'UTF-8' );
	mb_regex_encoding('utf-8');
	mysql_query("SET NAMES 'utf8'");
	
	//split sentences with questions and periods but not decimal numbers.  **Does not handle hanja.**
// 	preg_match_all("/([가-힣]|[ㄱ-ㅎ]|\s|(\d+(\.\d+)?)|\'|\"|\[|\]|\(|\)|\,|\/|\=|[A-z]|[‘-”]|)+(\.|\?|\!)/um", $test_string, $regs);
	
	preg_match_all("/.*?(\.(?!\S)|\?|\!|\S\s*$|\S\r\r)/um", $test_string, $regs);
	foreach ($regs[0] as $reg) {if ($reg == "" ) unset($reg);}
	//The sentences split into an array.
	$sentences= $regs[0];
	//if the sentence is missing a period, it won't be matched and we have to manually add it.
	
	if(!$regs[0]) { 
		$sentences[0] = $test_string; 
	}
	//The sentence endings split into an array.
	$isquestion= $regs[4];
	
	/* This part will be for people working on the translator to later edit and add definitions.
	   It will be a link to the data input form, taking the current sentence and already 
	defined word data, however it is currently just a blank link.
	*/
	
// 	Click on the sentence to define it's individual words:(but not yet)<br><br>
	echo "<p><font size=6>";
	foreach($sentences as $key => $sentence)echo  $key+1 . ": <a href=\"#\">" . $sentence . "</a> \t"; 
	echo "</font></p>";
	
	//self-explanitory, sentences have been split into individual arrays of sentences.  Define them.	
foreach ($sentences as $sentence){
	
	//SENTENCE PRE-FORMATTING (following two commands)
	
	//reduce multiple consecutive spaces to single spaces
	$sentence = mb_ereg_replace("\s\s+"," ", $sentence);
	
	//change two single quote characters to one double quote character
	$sentence = mb_ereg_replace("''", "\"", $sentence);
	
	unset($s_and_w);
	//Create container object for different types of setences and word variables
	$s_and_w = new sentence_and_words($sentence);

	$t_action_list = new Translation_action_list();	
	
	//double trans explains whether or not the second word was part of
	//the verb, or part of the tense.  When two_word_tense is true,
	//it means that the tense contained a space, (~고 있다)otherwise 
	//the tense did not contain a space.

	$my_m_verb = handle_verb ($s_and_w->sentence, $s_and_w->words, $s_and_w->decomp_words, $s_and_w->shifted_words);
	
	//split the sentence by particles
	$particle_array = new_find_particles($s_and_w->sentence);
	
// 	echo "<pre>";
	
	//divide the sentence by conjunctions
	$conjunction_array = new_divide_clauses($s_and_w, $my_m_verb);
// 	echo "</pre>";

	if ($particle_array) {
		$lastkey = end(array_keys($particle_array));
		
		if ($my_m_verb->removed_particle && !$my_m_verb->do) {
			//remove the last particle type from the array 
			unset($particle_array[$lastkey]);
// 			print_r($particle_array[$lastkey]);
		}
	}

	
	//divide the sentence into objects
	$object_sentence_array = create_object_sentence( $s_and_w->sentence, $particle_array, $conjunction_array, $my_m_verb );
	
	//apply conjunction splits to the object sentence
	apply_conjunctions_to_object_sentence($conjunction_array, $object_sentence_array);
// 	apply_translation_actions();	
			
	if ($conjunction_array)foreach($conjunction_array as $conjunction_object) unset($conjunction_object->m_verb->mverb);

	echo "<pre>";

	
	if($conjunction_array) {
		
		//print sentance to browser with highlighted conjunctions if any were found
		echo "---------------------------------------\n--      Conjunction Sentence         --\n---------------------------------------\n\n";
		new_print_sentence_conjunctions($s_and_w->sentence, $conjunction_array);
		
		//dump values for the conjunction array to the screen
		echo "---------------------------------------\n--      Conjunction array            --\n---------------------------------------\n\n";
		print_r($conjunction_array);
	}	

	echo "---------------------------------------\n--      Object Sentence Array        --\n---------------------------------------\n\n";


	//currently not doing anything with the last word in a sentence.
	
// 	k2e_new_noun_clause($object_sentence_array[1],$object_sentence_array[2]);
	foreach($object_sentence_array as $key => $clause) {
		if ($object_sentence_array[$key+1]) {
			//since there is no reordering happening at this stage, just pass the t_action_list to noun_clause
			if(k2e_new_noun_clause($object_sentence_array, $key, $t_action_list)) {
// 				print_r($clause);
			} 
		} 
	}
	apply_movement_rules_to_sentence ( &$object_sentence_array, $t_action_list );
	print_r($object_sentence_array);	
	
	echo "</pre>";
	
	
// 	print_sentence ($particle_array, $sentence, $my_m_verb, $conjunction_array);		
}


// don't forget to close the mysql connection 
mysql_close(); 


?>
</div>
</body>
</html>