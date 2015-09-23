<?php
function grammar_pattern_handle($sentence, $words, $decomp_words, $shifted_words, $orign_sentence, $offset){

	if(is_null($orign_sentence)) die("origin sentence is required");

	//find out how long is current sentence_length
 	$sentence_length = sizeof($words);
 	
	//searching words 
	//$grammar_pattern="법은";
		$query ="SELECT * FROM trans_kgrammar_pattern";
		$result = mysql_query($query);
		$numrow=mysql_num_rows($result);
		//avoid duplication of tense
		$tense_counter=0;
		//avoid duplication of negative
		$negative_counter=0;

		for($i=0; $i<$numrow; $i++){
			$row=mysql_fetch_array($result);
			//this for loop searches the positive gp and then it's negative counterpart.
			for($d=1; $d<3; $d++){
				$grammar_pattern=$row[$d];
		 		if(!is_null($grammar_pattern)){
					utf_normalizer::nfd($grammar_pattern);
					utf_normalizer::nfd($sentence);
					$decom= $sentence;
					$grammar_pattern_pure = "$decom";
					$grammar_pattern = "$grammar_pattern.*?\b";
					$grammar_pattern_position=$row[4];
					//seach sentence have garmmar pattern or not
					mb_ereg_search_init($decom,$grammar_pattern);
					//store matched garmmar pattern if it found
					$regs = mb_ereg_search_regs();
			
					if($regs[0]!=""){
						$id_num=$row[0];
						$choice_gp[$id_num] = $row[1];
					}
				}	
			}
		}
	
		$number = count($choice_gp);
			
		//This will not be necessary because we're always searching for the longer strings first.

		if(count($choice_gp)>=1){	
			//set great value 
			$greatest_value=0;
			$gp=0;
			///// find out which one is the longest hoching		
			foreach($choice_gp as $key => $value){
				// check longest hoching and store value 
				if(mb_strlen($value) > $greatest_value){
					$greatest_value = mb_strlen($value);
					//store longest hoching from array
					$gp=$key;
				}			
			}
			//counting total matched hoching were found from database
			$number = count($choice_gp);
			//try to find out longest length matched gp from database 
			$query3="select * from trans_kgrammar_pattern WHERE id='$gp'";
			$result3=mysql_query($query3);
			$numofrow3=mysql_num_rows($result3);
			//for loop for search gp database 
			for ($u=0; $u<$numofrow3; $u++){
				$row=mysql_fetch_array($result3);
				//for loop for seraching database possible and negative. "$d = 1" is posstive  "$d = 2" is negative 
				for($d=1; $d<3; $d++){
					$grammar_pattern=$row[$d];
					//some grammar pattern can be null value because gp can not have negative epression, so it check before make an error 
			 		if(!is_null($grammar_pattern)){
				 	//make decom words for search
					utf_normalizer::nfd($grammar_pattern);
					//make decom words for search
					utf_normalizer::nfd($sentence);
					$decom= $sentence;
					$grammar_pattern_pure = "$decom";
					$grammar_pattern = "$grammar_pattern.*?\W|$grammar_pattern.*?\b";
					//gp position indecate location of word
					$grammar_pattern_position=$row[4];
					
					//seach sentence have garmmar pattern or not
					mb_ereg_search_init($decom,$grammar_pattern);
					//store matched garmmar pattern if it found
					$regs = mb_ereg_search_regs();
					//if found garmmar pattern is not empty. process below line
						if($regs[0]!=""){
							//sotre decomp_ sentence for later on comp words because shifted sentence/words can not put it back normal
							utf_normalizer::nfc($grammar_pattern_plus_particle);
							//store $regs[0] is for remind what is grammar pattern and plus tense(might be some particle)
							$grammar_pattern_plus_particle = $regs[0];
							
							utf_normalizer::nfd($remove_part);
							$decomp_original=$s_and_w_g->decomp_sentence;
							$grammar_pattern_t_English=$row[3];
							$replace="";
							//generate grammar_pattern object
		 					$grammr_pattern = new grammar_pattern($row[1],$grammar_pattern_t_English,$row[4]);
							$remove_part=$row[1];
							utf_normalizer::nfd($remove_part);
							//removed tense part and get ture grammar pattern
							$moved_grammar_pattern = mb_ereg_replace($remove_part,$replace,$regs[0]);
							//store grammar pattern to remind
							$grammr_pattern->grammar_pattern = $row[1];
							//store only tense_part to remind
							$grammr_pattern->tense_part = $moved_grammar_pattern;
							//set tense it might change it later if find real tense but if do not find any tense it stay as present
							$grammr_pattern->tense = "present";
							//set English meaning of gp to remind
							$grammr_pattern->english = $row[3];
							//set grammar pattern possition to remind it will might help to place/replace word order
							$grammr_pattern->position = $row[4];
							
							utf_normalizer::nfc($grammar_pattern_plus_particle);
							//set grammar_pattern_plus_patricle it will help to find match_m_verb later one
							$grammr_pattern->grammar_pattern_plus_particle = $grammar_pattern_plus_particle;
							//total length of gp (it include tense part and particle)
							$grammr_pattern->total_length = mb_strlen($grammar_pattern_plus_particle);

							$nfc_moved_grammar_pattern=$moved_grammar_pattern;
							utf_normalizer::nfc($nfc_moved_grammar_pattern);
							$grammr_pattern->tense_part_nfc = $nfc_moved_grammar_pattern;
							$grammar_pattern_matching_part = $regs[0];
							utf_normalizer::nfc($grammar_pattern_matching_part);
							//trim grammar pattern matched part because it may contain a space
							$grammar_pattern_matching_part = trim($grammar_pattern_matching_part, " ");
							$removed_space_regs = trim($regs[0], " ");
							$grammar_pattern_last_word="\s.*$";
						
							//seach sentence have garmmar pattern or not
							mb_ereg_search_init($removed_space_regs,$grammar_pattern_last_word);
							//store matched garmmar pattern if it found
							$regs_last_word = mb_ereg_search_regs();
							$grammar_pattern_last_word = $regs_last_word[0];
							$grammar_pattern_last_word = trim($grammar_pattern_last_word , " ");
							utf_normalizer::nfc($grammar_pattern_last_word);
							$grammar_pattern_first_word_finding_pattern=".*\s";
						
							//seach sentence have garmmar pattern or not
							mb_ereg_search_init($removed_space_regs,$grammar_pattern_first_word_finding_pattern);
							//store matched garmmar pattern if it found
							$grammar_pattern_first_word_for_removed_patricle = mb_ereg_search_regs();
							$grammar_pattern_first_word_for_removed_patricle = $grammar_pattern_first_word_for_removed_patricle[0];
							$grammar_pattern_first_word_for_removed_patricle = trim($grammar_pattern_first_word_for_removed_patricle , " ");
							utf_normalizer::nfc($grammar_pattern_first_word_for_removed_patricle);
							$last_particle = mb_substr($grammar_pattern_first_word_for_removed_patricle, -1, 1);
		
							//if the last character in the sentence is a period, question mark, or exclamation mark, omit it.
							if ($last_particle == "을"|| $last_particle == "를"|| $last_particle == "이" || $last_particle == "가" || $last_particle == "도"){
								//omit the last character from the end of the first verb word.
								$grammr_pattern->finding_particle = true;
							}
							
							//set orgingin_offset for $grammar pattern last pos because the offset is changed by grammar pattern start pos
							$origin_offset=$offset;
							//set grammar pattern start pos 
							$grammar_pattern_start_pos=mb_strpos($orign_sentence, $grammar_pattern_matching_part, $offset);
							$grammr_pattern->grammar_pattern_start_pos = mb_strpos($orign_sentence, $grammar_pattern_matching_part, $offset);
							
							$result_pos = $grammr_pattern->grammar_pattern_start_pos;
							
							//reset offset for next part search 
							$offset = $result_pos + mb_strlen($grammar_pattern_matching_part);
							$grammr_pattern->grammar_pattern_offset= $offset;
							
							//if grammar pattern composes two or more words, process below line
							if($regs_last_word[0]!=""){
								$gpw=$grammr_pattern->grammar_pattern_last_pos;
								$gpw = trim($gpw, " ");
								$gpwlen=mb_strlen($gpw);
								$offsetgpw2 = $origin_offset;
 								$grammr_pattern->grammar_pattern_last_pos = mb_strpos($orign_sentence, $grammar_pattern_last_word, $offsetgpw2);
							}else{	
								$grammr_pattern->grammar_pattern_last_pos = $grammr_pattern->grammar_pattern_start_pos;
							}
							
							$grammar_pattern_start_pos = $grammr_pattern->grammar_pattern_start_pos;
							//calculate grammar pattern length
							$grammar_pattern_plus_particle_length = mb_strlen($grammar_pattern_plus_particle);
							//set grammar pattern end postion (-1 for array start with 0)
							$grammr_pattern->grammar_pattern_end_postion =  $grammar_pattern_start_pos + $grammar_pattern_plus_particle_length -1;
							//set orign_sentence_length (-1 for array start with 0)
							$orign_sentence_length = mb_strlen($orign_sentence) - 1;
							//set original_setence_length
							$grammr_pattern->orign_sentence_length = $orign_sentence_length;
							//set grammar pattern end postion (-1 for array start with 0)
							$grammr_pattern->grammar_pattern_end_postion =  $grammar_pattern_start_pos + $grammar_pattern_plus_particle_length -1;
							//if it is same length or one size bigger, set match_m_verb true 
							if($orign_sentence_length == $grammr_pattern->grammar_pattern_end_postion || $orign_sentence_length == $grammr_pattern->grammar_pattern_end_postion + 1){
								$grammr_pattern->match_m_verb = true;
							}
							//if $d is 1 possbile 2 is negative
							if($d == 1){
								$grammr_pattern->possitive = true;
							}else{
								$grammr_pattern->negative = true;
							}
								
							$shifted_moved_grammar_pattern=jamo_to_co_jamo($moved_grammar_pattern);
							$pattern_past = "ㅇㅓㅆ";	
				
							//initalize the search
							mb_ereg_search_init($shifted_moved_grammar_pattern, $pattern_past);
							
				 			//finding tense part and set value
					 		while ($pos = mb_ereg_search_pos()) {
								$byte_start = $pos[0];
								$byte_length = $pos[1];
					
					 			$mb_start = mb_strlen(substr($shifted_moved_grammar_pattern, 0, $byte_start));
					 			$mb_length = mb_strlen(substr($shifted_moved_grammar_pattern, $byte_start, $byte_length));
					 			$mb_pos_past[] = array($mb_start, $mb_length);
					
					 			$result_past = mb_substr($moved_grammar_pattern, $mb_start, $mb_length);
					 			utf_normalizer::nfc($result_past);
					 		
								if($result_past == "었"){
									$grammr_pattern->tense = "past";
									$grammr_pattern->tense_word = $result_past;
								}else if($result_past == "어왔" || $result_past == "왔"){
									$grammr_pattern->tense = "present perfect";
									$grammr_pattern->tense_word = $result_past;
								}
					 		}
					 		//finding conjunction part it if is compose inside grammar pattern ending part
				 			$pattern_conj = "ㄷㅓㄴ|ㄱㅗ|ㄴㅏ|ㄹㅕ|ㄹㅕㄱㅗ|ㄷㅏㄱㅏ|ㄹㅓ|ㄷㅗ|ㅅㅓ|ㄷㅓㄴㅣ|ㅈㅣㅁㅏㄴ|ㄱㅣ ㄸㅐㅁㅜㄴㅇㅔ|ㄱㅣ ㅈㅓㄴㅇㅔ|ㄸㅐ|ㄴㅣㄲㅏ|ㅁㅕㄴ|ㄷㅏㅇㅡㅁㅇㅔ|ㄷㅔ";	
		
							//initalize the search
							mb_ereg_search_init($shifted_moved_grammar_pattern, $pattern_conj);
				
				 			//finding location of work and store decleared information
				 			while ($pos = mb_ereg_search_pos()) {
								$byte_start = $pos[0];
								$byte_length = $pos[1];
					
					 			$mb_start = mb_strlen(substr($shifted_moved_grammar_pattern, 0, $byte_start));
					 			$mb_length = mb_strlen(substr($shifted_moved_grammar_pattern, $byte_start, $byte_length));
					 			$mb_pos_past[] = array($mb_start, $mb_length);
					
					 			$result_conj = mb_substr($moved_grammar_pattern, $mb_start, $mb_length);
					 			utf_normalizer::nfc($result_conj);
					 			//if found conjunction set the conj value 
								if($result_conj){
									$grammr_pattern->conj = $result_conj;
								}
				 			}	
						}
					}
				}
			}
		}
	
// 	//store object value as array
  	if($grammr_pattern)$grammr_pattern_array[]= $grammr_pattern;
// 	//returnt result of process
 	return $grammr_pattern_array;
}
	
?>