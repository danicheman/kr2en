<?php


	mb_internal_encoding( 'UTF-8' );
	mb_regex_encoding('utf-8');
	
	$my_string = "교수와 학생 교감선생님은 잘지낸다";
	$my_pattern_preg = "/(교수)(와)?\b/u";
	$my_pattern_ereg = "(교수)(와)?\b";
	echo "<pre>This is preg match all: \n";
	preg_match_all($my_pattern_preg, $my_string, $regs);
	print_r ($regs);
	echo "\n This is EREG for multibyte\n\n";
	mb_ereg ( $my_pattern_ereg, $my_string, $regs);
	print_r ($regs);
	echo "\n This is EREG for init//get regs\n\n";
	mb_ereg_search_init($my_string, $my_pattern_ereg);
	$regs = mb_ereg_search_regs();
	print_r ($regs);
	?>