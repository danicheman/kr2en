<?php
/*
index is the main function of the program, within this file are the objects used by 
the other functions, for clause and verb searching. The functionality of test 3 appart
from using the included files is html screen output. 
 
*/

// include("array_functions.php"); -->these functions are not necessary so far...
include("connect.php"); 
include("utf/utf_normalizer.php");
include("jamo_con.php");
include("verb_matching.php");
include("matching.php");
include("printing.php");
include("sentence.php");
include("object_sentence_functions.php");
include("new_conjunction.php");
include("k2e_noun_clause_test.php");
include("hoching.php");
include("english_functions.php");
include("word_order_functions.php");
include("k2e_name.php");
include("adnominal_ending_particle_back.php");
include("grammar_pattern_finder.php");
include("grammar_pattern_handle.php");
include("adverb.php");
//name translation disabled for testing
$name_trans_on = false;

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
class grammar_pattern {
	function grammar_gattern($g, $e, $p){
		
			$this->grammar_pattern = $g;
			
			$this->english = $e;
			
			$this->position = $p;
	}
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<meta http-equiv="content-language" content="ko"> 
<meta http-equiv="content-type" content="text/html; charset=utf8"> 
<html> 
<head><title>Translator Main Page</title>
<script language="JavaScript" src="motionpack.js"></script>
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
		background-image: url(img/tile7.jpg);
		}
	
	#main {
		border:1px solid #62979E; 
		background:#FFFFFF;
	}

/* Looks like you have to specify the width of #menu
or IE5 Mac stretches it all the way across the div, and 
Opera streches it half way. */

	#main #menu {
		border-left:1px solid #62979E; 
		border-bottom:1px solid #62979E;
		float:right;
		width:230px;
		background-image: url(img/tile4.jpg);
		margin:0px 0px 10px 10px;
		}
	#main #wordmenu {
		float:left;
		width:250px;
		border: 1px solid green;
		margin: 1px 1px 1px 1px;
		background-image: url(img/tile4.jpg);
	}	
	div.spacer {
		clear: both;
	}
	p,h1,pre {
		margin:0px 10px 10px 10px;
		white-space: pre-wrap;       /* css-3 */
		white-space: -moz-pre-wrap;  /* Mozilla, since 1999 */
		white-space: -pre-wrap;      /* Opera 4-6 */
		white-space: -o-pre-wrap;    /* Opera 7 */
		word-wrap: break-word;       /* Internet Explorer 5.5+ */
	}
		
	h1 {
		font-size:14px;
		padding-top:10px;
		}
	
	#menu p { font-size:10px}
	#menu li { font-size:10px}

	pre {
	 white-space: pre-wrap;       /* css-3 */
	 white-space: -moz-pre-wrap;  /* Mozilla, since 1999 */
	 white-space: -pre-wrap;      /* Opera 4-6 */
	 white-space: -o-pre-wrap;    /* Opera 7 */
	 word-wrap: break-word;       /* Internet Explorer 5.5+ */
	}

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
<!--<h1>
<span id="subject">subject</span>
<span id="topic">topic</span>
<span id="object">object</span>
<span id="verbMod">verb modifier</span>
<span id="gray">past tense</span>
<span id="blue">present tense</span>
<span id="red">present continuous</span>
<span id="green">future tense</span></h1>-->
<p>Input Korean here:</p> 
<form action="index.php" method="post"> 
<textarea rows="5" cols="25" name="test_input" style="background-image: url(img/tile4.jpg);"></textarea>
<input type="submit" style="background:#FFF7EE;padding:3px;"/>
</form> <!--
<p>미국의 연예잡지 ''인터치''가 실시한 최근 설문조사에서 제니퍼 로페즈와 비욘세가 각각 ''헐리우드에서 가장 섹시한 엉덩이'' 1,2위에 선정되었다.</p>
<p>우리나라가 중국과 일본 틈바구니에서 고생길로 접어들었다는 사실은 어제오늘 일이 아닙니다. </p>
<p>Our country, in the gap between China and Japan, having entered a worrying path, actually, is not a recent thing.</p>
<p>정동영 전 열린우리당 의장은 21일 한나라당 대통령 후보로 선출된 이명박 후보에 대해 "국민의 눈높이에서 검증을 철저히 하겠다"며 "박근혜 전 대표가 이 후보를 시한폭탄이라 규정했지만, 저는 시한폭탄을 해체하겠다"고 포문을 열었다. </p>
-->
<?php
// error_reporting(E_ALL );
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
	foreach($sentences as $key => $sentence)echo  $key+1 . ": " . $sentence . " \t"; 
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

	//from now on, my_m_verb will be created within the grammar pattern section
	
// 	$my_m_verb = handle_verb ($s_and_w->sentence, $s_and_w->words, $s_and_w->decomp_words, $s_and_w->shifted_words);


// 	if(is_null($my_m_verb->english)) unset($my_m_verb);
	//split the sentence by particles
	$particle_array = new_find_particles($s_and_w->sentence);
	
	
	//adnominal part starts here <<<<<<<<<<<<<<-------------------------------------------
	
    //this function will check possible adnominal pattern
	if(has_adnom_ending($s_and_w)){
		
		//find all adnominal ending verbs and return in an array
		$adnominal_array = find_out_adnominal_and_store($s_and_w);
	}
	
	
	if(isset($adnominal_array[0]->english)){

		//find grammar pattern and return in an anrray
		$number_of_adnominal_array=count($adnominal_array);

		//this line will creat grammar pattern array
// 		$grammar_pattern_array = create_gp_array($adnominal_array, $s_and_w -> sentence);

	}
	
	$grammar_pattern_array_number=count($grammar_pattern_array);
	if (!isset($grammar_pattern_array[$grammar_pattern_array_number-1]->match_m_verb)){
 		$my_m_verb = handle_verb ($s_and_w->sentence, $s_and_w->words, $s_and_w->decomp_words, $s_and_w->shifted_words);
	}
//grammar pattern part finish there <<<<<<<<<<<<<<<<<<<<<<-------------------------------------------


	//divide the sentence by conjunctions
	$conjunction_array = new_divide_clauses($s_and_w, $my_m_verb, $grammar_pattern_array);

	if ($particle_array) {
		$lastkey = end(array_keys($particle_array));
		
		if ($my_m_verb->removed_particle) {
			//remove the last particle type from the array 
			unset($particle_array[$lastkey]);
		}
	}

	
	//divide the sentence into objects
	$object_sentence_array = create_object_sentence( $s_and_w->sentence, $particle_array, $conjunction_array, $my_m_verb );
	

	$hoching_particle_array = new_find_particles_for_hoching($s_and_w->sentence);
	
	//divide the sentence into objects
	$object_sentence_array_for_hoching = create_object_sentence( $s_and_w->sentence, $hoching_particle_array, $conjunction_array, $my_m_verb );
		
	//calling hoching function
	hoching($hoching_particle_array, $object_sentence_array_for_hoching, $object_sentence_array);
	
	echo "<pre>";
// 	print_r($object_sentence_array);
	echo "</pre>";
	

	if ($conjunction_array)foreach($conjunction_array as $conjunction_object) unset($conjunction_object->m_verb->mverb);



	
	if($conjunction_array) {
		echo"<div id=\"conjunctions\" style=\"display: none; overflow:hidden; height:100px;\">";
		echo "<pre>";
		echo "---------------------------------------\n--      Conjunction Sentence         --\n---------------------------------------\n\n";
		//print sentance to browser with highlighted conjunctions if any were found
		new_print_sentence_conjunctions($s_and_w->sentence, $conjunction_array);
		echo "</pre>";		
 		echo "</div>";
 		echo "<p><a href=\"javascript:;\" onmousedown=\"slidedown('conjunctions');\">Show</a> / ";
		echo "<a href=\"javascript:;\" onmousedown=\"slideup('conjunctions');\">Hide</a> Conjunctions</p>";

		//apply conjunction splits to the object sentence
		apply_conjunctions_to_object_sentence($conjunction_array, $object_sentence_array);


	}	



//adnominal part starts here <<<<<<<<<<<<<<-------------------------------------------
    //this function will check possible adnominal pattern
 	$result_has_adnom_ending=has_adnom_ending($s_and_w);
 
// 	echo "result_has_adnom_ending:".$result_has_adnom_ending;
	
	if($result_has_adnom_ending){
		//find all adnominal ending verbs and return in an array
		$adnominal_array = find_out_adnominal_and_store($s_and_w);
		//if adnominal_array's english valuse is set, process underline
		
		if(isset($adnominal_array[0]->english)){
			
			//english values and m_verb objects into osa
			adnominal_values ($object_sentence_array, $adnominal_array);
			
			//append verbmod clauses to adnominal clauses and adnominal clauses to noun clauses 
			//
			// [verbmod clause] >> [adnominal clause] >> [noun clause]
 			apply_adnominals_to_osa($object_sentence_array, $adnominal_array, $t_action_list);
		}
	}
//adnominal part finishes here <<<<<<<<<<<<<<<<<<<<<<-------------------------------------------

//grammar pattern part starts here <<<<<<<<<<<<<<-------------------------------------------
    //this like only process when adnominal array egnlish valuse is setted
	if(isset($adnominal_array[0]->english)){
		//find grammar pattern and return in an anrray
// 		$grammar_pattern_array = create_gp_array($s_and_w);
		//find out how many array in grammar_pattern_array
		$number_of_grammar_pattern_array = count($grammar_pattern_array);
		
// 		if(isset($grammar_pattern_array[0]->english)){
	
		//if number of grammar pattern array is bigger than 0, process lines blow it meant grammar pattern/s is/are in inserted string
	    if($number_of_grammar_pattern_array > 0 ){
		    //english values and m_verb objects into osa
			grammar_pattern_values ($object_sentence_array, $grammar_pattern_array);
			//rearranging osa as grammar_pattern_array's order 
   			apply_grammar_pattern_to_osa($object_sentence_array, $grammar_pattern_array);
		}
	
	}
//grammar pattern part finish there <<<<<<<<<<<<<<<<<<<<<<-------------------------------------------
	
	//Translate everything that hasn't already been translated by conjunction, grammar pattern, or verb searching
	foreach($object_sentence_array as $key => $clause) {
		//since there is no reordering happening at this stage, just pass the t_action_list to noun_clause
		k2e_new_noun_clause($object_sentence_array, $key, $t_action_list);
		
		//name searching, disabled to show which words are undefined.
		if($name_trans_on && is_null($clause->english)){
			handle_names($clause);
		}
		
			
		//change prepositions because of verbs
		if(isset($clause['conj']) || isset($clause['mverb'])) {
			if (	  isset($clause['mverb']->translation_preposition_modify)) {
				$function = $clause['mverb']->translation_preposition_modify;
				
				//call the variable as a function
				$function(&$object_sentence_array, $key);
			} 
		} 
	} 
	
	//WORD ORDERING FUNCTIONS - Changing Korean word order to english word order
	
	apply_movement_rules_to_sentence ( &$object_sentence_array, $t_action_list );


	//prepositions only go in front of the first noun in the clause.
	// eg. downtown in the house -> in the downtown house
		
	sort_prepositions (&$object_sentence_array);
	
	//NEW~!
	sort_adverbs (&$object_sentence_array);
	
	
	
	 
	move_verbs_and_conjunctions ($object_sentence_array);

	
	/* 
	   swap a 'verbmod' clause which comes before an 
	   'object' clause, for example,
	   I am doing at lunchime homework. -> I am doing homework at lunchtime.
	 */
 	swap_verbmod_object_pattern ($object_sentence_array);

 	//END OF WORD ORDERING FUNCTIONS
 	
 	

	//inserting missing subjects (in the form of pronouns) into the english sentence. 
	//(since we can't assume subjects in english)	
 			
	insert_sentence_pronouns ($object_sentence_array);

	
	
	
	//for conjunctions and sentence final..
	conjugate_verbs(&$object_sentence_array);


	/*
		If the adnominal verb contains a modifier, ie.
		빨리 가는 차, then the adnominal verb goes after the noun.
		The noun's 'qword' and the verb's auxillary verb are both
		added and conjugated.
		
		[verbmod] [adnominal verb] [noun] ->
		
		[noun] [qword] [auxillary verb] [ adnominal verb ] [optional preposition] [verbmod]
	
	*/
	
	
	translate_adnominals($object_sentence_array);
	


 	$osa_printr = print_r($object_sentence_array, true);	
//  	$split_osa = preg_split('/[^.]/u',$osa_printr, PREG_SPLIT_NO_EMPTY);

		$no_of_lines = mb_substr_count($osa_printr, "\n");
		$line_height = 18;
		
		echo"<div id=\"osa\" style=\"display: none; overflow:hidden; height:".$no_of_lines * $line_height."px;\">";
		echo "<p><a href=\"javascript:;\" onmousedown=\"slidedown('osa');\">Show</a> / ";
		echo "<a href=\"javascript:;\" onmousedown=\"slideup('osa');\">Hide</a> OSA</p>";
		echo "<pre>";
		echo "---------------------------------------\n--      Object Sentence Array        --\n---------------------------------------\n\n";
 		echo $osa_printr;
		echo "</pre>";	
		echo "</div>";
 		echo "<p><a href=\"javascript:;\" onmousedown=\"slidedown('osa');\">Show</a> / ";
		echo "<a href=\"javascript:;\" onmousedown=\"slideup('osa');\">Hide</a> OSA<br></p>";

	
	//clause shading, and sentence printing
	print_english_sentence($object_sentence_array);

	
}


// don't forget to close the mysql connection 
mysql_close(); 


?>
</div>
</body>
</html>