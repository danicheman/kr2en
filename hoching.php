<?php
//*****************************FUNCTION HOCHING****************************************************************************************
//*****************************TASK OF FUCNTION:1. FIND OUT HOCHING, NAME AND ADJECTIVE.***********************************************
//**********************************************2. REMOVE HOCHING AND ADJECTIVE *******************************************************
//**********************************************3. PRINT OUT RESULT OF THOSE PROCESSES ************************************************
//***********************************$object_sentence_array**************************************************************************************************
function hoching($particle_array, $object_sentence_array_for_hoching, $object_sentence_array){	

	
	if ($particle_array) {
		$lastkey = end(array_keys($particle_array));
		
		if ($my_m_verb->removed_particle) {
			//remove the last particle type from the array 
			unset($particle_array[$lastkey]);
		}
	}
	

	//find out how many array inside $objext_sentence_array and store value to $hoching_osa_length
	$hoching_osa_length = count($object_sentence_array_for_hoching);

	//display line 

	for($i=0; $i<$hoching_osa_length; $i++){

		// store each object_sentence_array's clause to $ word_From_sentence
		$word_from_sentence=$object_sentence_array[$i]["clause"];
	

		//replace comma to space
 		$replace=" ";
 		//remove part from $word_from_sentence
		$remove_comma=",";
		//remove comma process
 		$word_from_sentence = mb_ereg_replace($remove_comma,$replace,$word_from_sentence);
 			
		//cut word out if it is not necessary for hoching
 		$cut_no_need_part =".*?($hoching)?(는|은|/w이|가|을|를|를.*|들|에|에서|로|등|고|과|에게|께서|령간|(?<!할아버지|할머니|이모|고모|삼촌|어머니의|아버지의|그녀|그)의|(?<!상|북|기|청|원|경|라)도|하고|이랑|와)\b|.*($hoching)?\s";

		//remove no need part from sentence
		mb_ereg_search_init($word_from_sentence,$cut_no_need_part);
		//after removed part store remain part 
		$need_part = mb_ereg_search_regs();
		//alarm for removing process success or not

		$word_from_sentence=$need_part[0];
		//call mysql database
		$query2="select * from trans_khoching";
		$result2=mysql_query($query2);
		$numofrow2=mysql_num_rows($result2);
		//use for loop for read out database 
		for ($i=0; $i<$numofrow2; $i++){
			$row=mysql_fetch_array($result2);
			//store hoching korean part to $hoching 
			$hoching=$row[1];
			
		
			//pattern matching for hoching 
 			$pattern_hoching = "($hoching)(?<!군)(님|씨|양|군)?(들)?(는|측은|은|이|가|께서|을|로|를|에|에서|등|고|과|에게|의|도|하고|이랑|와|간|이.*)?\b";
			//find out hocing plus joint inside $word_from_sentence
			mb_ereg_search_init($word_from_sentence,$pattern_hoching);

			$regs = mb_ereg_search_regs();
			

			//if result is nothing simply do not do anything 
			if($regs == null){

			}else{
				$hoching_plus_joint=$regs[0];
			}
			//find out which hoching parttern 	
			if($regs[1]==$hoching){
					
				$choice_hoching[$i] = $hoching;
				// if hoching is not 씨 
				if($hoching != "씨"){
					
					if($regs[3]=='들'){
					// one hoching with many people 	
						$plural=1;			
					}
					// name + hoching pattern if they can not joint
					if($regs[4]!=null){
						$rule=1;
					// otherwise hoching pattern is hoching + name	
					}else{	
						$rule=2;
					}
					//store hoching info from the database 
					$type=$row[2];
					$english=$row[3];
				}
			}	
		}
		///if find out hochings are more than one, process below 	
		if(count($choice_hoching)>1){	
			//set great value 
			$grreat_val=0;
			$g_k=0;
			///// find out which one is the longest hoching		
			foreach($choice_hoching as $key => $value){
				// check longest hoching and store value 
				if(mb_strlen($value) > $grreat_val){
					$grreat_val = mb_strlen($value);
					//store longest hoching from array
					$g_k=$value;
				}			
			}
			//counting total matched hoching were found from database
			$numbe = count($choice_hoching);

			$hoching=$g_k;
			
			//try to find out longest length matched hoching from database 
			$query3="select * from trans_khoching WHERE id='$g_k'";
			$result3=mysql_query($query3);
			$numofrow3=mysql_num_rows($result3);
		
			for ($i=0; $i<$numofrow3; $i++){
				$row=mysql_fetch_array($result3);
				//store hoching from database 
				$hoching=$row[1];
				//pattern matching for hoching 
				$pattern_hoching1 = "$hoching(님|씨|양|군)?(들)?(는|은|이|가|로|을|를|에|에서|등|고|과|에게|의|도|하고|이랑|와|이.*)?\b";
			
				//
				mb_ereg_search_init($word_from_sentence,$pattern_hoching1);
			
				$reg = mb_ereg_search_regs();
				//store fist array from $reg for removing process
				$hoching_plus_joint=$reg[0];
				//store English meaning of hoching
				$egnlish=$row[3];
				
			}	
			if($plural==1){
				
				//reset plural value
				$plural=0;
			}
			//process below lines if hoching pattern is name + hoching 
			if($rule==1){
					$num_array=count($object_sentence_array);

							for($a=0; $a<=$num_array; $a++){
								$number_of_words=num_of_clause_words($object_sentence_array[0]);

								for($aa=0; $aa<=$number_of_words; $aa++){
									$word=$object_sentence_array[$a][$aa]->word;

									$trimed_word = trim($word, " ");
									$trimed_hoching_plus_joint = trim($hoching_plus_joint, " ");
												
									$pattern_hoching_two_words = "\s.*";
									//finding two words hoching
									mb_ereg_search_init($trimed_hoching_plus_joint,$pattern_hoching_two_words);
						
									$regs = mb_ereg_search_regs();
									$two_words = false;
									if ($regs[0]!=""){
										$trimed_hoching_plus_joint = trim($regs[0], " ");
										$two_words = true;
									}

									if($trimed_word==$trimed_hoching_plus_joint){
													
										if($two_words == true){
											$object_sentence_array[$a][$aa-1]->english = true;
											$two_words = false;
										}
										$object_sentence_array[$a][$aa]->english = $english;		
										$object_sentence_array[$a][$aa]->type = 'hoching';		
									}

								}
							}
				$rule=0;

				//replace nothing 
				$replace="";
				//reqular expression for select removeing part 
				$remove_hoching_part="(^$hoching_plus_joint\s|\s$hoching_plus_joint\s|\s$hoching_plus_joint$|$hoching_plus_joint)";

				//removing hoching from sentence 
				$removed_hoching = mb_ereg_replace($remove_hoching_part,$replace,$word_from_sentence);
				
				//finding adjactive part
				$query3="select * from trans_kadj";
				$result3=mysql_query($query3);
				$numofrow3=mysql_num_rows($result3);
				// user for loop for seaching and rmoving adjective process 
				for($i=0; $i<$numofrow3; $i++){
					$row=mysql_fetch_array($result3);
					
					$raw_adj= $row[1];
					//reqular expression for find out adjective on string
					$adjs = "(^$raw_adj\s|\s$raw_adj\s|\s$raw_adj$)";
				
					mb_ereg_search_init($removed_hoching,$adjs);
				
					$filtered_adjs = mb_ereg_search_regs();

					//print all what it found out from sentence it will displayed by array


						//precess below lines if filtered_adjs[0] is not null
						if($filtered_adjs[0]!=""){
							//alert for finding adjective out
							
							//store the finding part 
							$filtered_adjs_store = $filtered_adjs[0];
							//store English meaning of adjective
							$adj_in_english = $row[3];
							//just a break
							
						}
						//print adjactive from sentence and trans to English 
						if($filtered_adjs!=""){

							$num_array=count($object_sentence_array);

							for($a=0; $a<=$num_array; $a++){
								$number_of_words=num_of_clause_words($object_sentence_array[0]);

									for($aa=0; $aa<=$number_of_words; $aa++){
										$word=$object_sentence_array[$a][$aa]->word;

										$trimed_word = trim($word, " ");
										$trimed_filtered_adjs_store = trim($filtered_adjs_store, " ");
	
										if($trimed_word==$trimed_filtered_adjs_store){
											$object_sentence_array[$a][$aa]->english = $adj_in_english;
										}else{

										}

									}
								}
				
							//sotre adjactive we find out from sentence	
							$adjective_number01 = $filtered_adjs[0];
					
							//reqular express for removing adjactive from sentence
							$remove_part="(^$raw_adj\s|\s$raw_adj\s|\s$raw_adj$)";
							
							//replace a space
							$replace=" ";
					
							//removing adjactive from sentence and replace nothing
							$after_removed_adj = mb_ereg_replace($remove_part,$replace,$removed_hoching);
							
							//print out after removed adjective from the words 

					
							//store remain part of words
							$removed_hoching = $after_removed_adj;
							
							//just a break

		
						}else{
							//this case is no adjective and fill up the value from $removed_hoching
							$after_removed_adj = $removed_hoching;	
						}
					}
					
					//print out result after process (removing adjective and no need part)
					
					if($after_removed_adj != ""){


					}
					//restore infomation for filtering name part
					$after_name_filter = $after_removed_adj;
					//process below lines if $after_name_filter is bigger than 0 and it is not just a speace
					if(strlen($after_name_filter)>0 && $after_name_filter != " "){
						//regular expresstion for fintering name
						$pattern_finding_name = "\S*$|\S*\s$";
						//find out matching part 
						mb_ereg_search_init($after_name_filter,$pattern_finding_name);
						//store matching part
						$after_name_filter = mb_ereg_search_regs();
						//store first array matching part from $after_name_filter
						$after_name_filter=$after_name_filter[0];
				
					}
					//print out after capture last word from words


					//store word will be translated 
					$bracket_fillter = "(사진)";
					//store replace English word
					$replace_pic ="pictured";
					
					//translanting process from 사진 to pictured 
					$after_name_filter = mb_ereg_replace($bracket_fillter,$replace_pic,$after_name_filter);
					//print out result

					//after_find_name end 
					//if $after_name_filter is empty or a space, process blow line
					if($after_name_filter=="" || $after_name_filter==" "){
						
					}else{
			
						for($a=0; $a<=$num_array; $a++){
							$number_of_words=num_of_clause_words($object_sentence_array[0]);
	
							for($aa=0; $aa<=$number_of_words; $aa++){
								$word=$object_sentence_array[$a][$aa]->word;
								$trimed_word = trim($word, " ");
								$trimed_after_name_filter = trim($after_name_filter, " ");
								if($object_sentence_array[$a][$aa]->english == "")
									{
										if($trimed_word==$trimed_after_name_filter){
												$object_sentence_array[$a][$aa]->type = "name";

										}else{
										}
									}	
							}
						}
					}
					
					unset($choice_hoching);	
					}else{

						$num_array=count($object_sentence_array);

							for($a=0; $a<=$num_array; $a++){
								$number_of_words=num_of_clause_words($object_sentence_array[0]);

									for($aa=0; $aa<=$number_of_words; $aa++){
										$word=$object_sentence_array[$a][$aa]->word;

							
							
										$trimed_word = trim($word, " ");
										$trimed_hoching_plus_joint = trim($hoching_plus_joint, " ");

							$pattern_hoching_two_words = "\s.*";
									//finding two words hoching
									mb_ereg_search_init($trimed_hoching_plus_joint,$pattern_hoching_two_words);
						
									$regs = mb_ereg_search_regs();
									$two_words = false;
									if ($regs[0]!=""){
										$trimed_hoching_plus_joint = trim($regs[0], " ");
										$two_words = true;
									}

									if($trimed_word==$trimed_hoching_plus_joint){
													
										if($two_words == true){
											$object_sentence_array[$a][$aa-1]->english = true;
											$two_words = false;
										}
										$object_sentence_array[$a][$aa]->english = $english;
												
									}

								}
							}

						$rule=0;
						$hoching_plus_joint=$hoching;
				
						//replace nothing 
						$replace=" ";
						//reqular expression for select removeing part 
						$remove_hoching_part="(^$hoching_plus_joint\s|\s$hoching_plus_joint\s|\s$hoching_plus_joint$)";
		
						//removing hoching from sentence 
						$removed_hoching = mb_ereg_replace($remove_hoching_part,$replace,$word_from_sentence);
				
						//printing the result of removing hoching from then sentence

						//finding adjactive part
						//reading adjective from database
						$query3="select * from trans_kadj";
						$result3=mysql_query($query3);
						$numofrow3=mysql_num_rows($result3);
		
						for ($i=0; $i<$numofrow3; $i++){
							
							$row=mysql_fetch_array($result3);
							//store adjective from database
							$raw_adj= $row[1];
							//make a regular expresstion for finding adjactive 
							$adjs = "(^$raw_adj\s|\s$raw_adj\s|\s$raw_adj$)";
							//finding process
							mb_ereg_search_init($removed_hoching,$adjs);
							//finding result
							$filtered_adjs = mb_ereg_search_regs();
							

							//if $filtered_adjs is not empty. process below	
							if($filtered_adjs[0]!=""){
								//print out matched adjective
								
								// store the finding part 
								$filtered_adjs_store = $filtered_adjs[0];
								//store English meaning of adjactive 
								$adj_in_english = $row[3];
								//just a break

							}
							//print adjactive from sentence and translate to English 
							if($filtered_adjs!=""){

								$num_array=count($object_sentence_array);

								for($a=0; $a<=$num_array; $a++){
									$number_of_words=num_of_clause_words($object_sentence_array[0]);

										for($aa=0; $aa<=$number_of_words; $aa++){
											$word=$object_sentence_array[$a][$aa]->word;
											$trimed_word = trim($word, " ");
											$trimed_filtered_adjs_store = trim($filtered_adjs_store, " ");
		
											if($trimed_word==$trimed_filtered_adjs_store){
												$object_sentence_array[$a][$aa]->english = $adj_in_english;
											}else{

											}

										}
									}
				
								//sotre adjactive we find out from sentence	
								$adjective_number01 = $filtered_adjs[0];
						
								//reqular express for removing adjactive from sentence
								$remove_part="(^$raw_adj\s|\s$raw_adj\s|\s$raw_adj$)";
				
								//replace a space...
								$replace=" ";
						
								//removing adjactive from sentence and replace nothing
								$after_removed_adj = mb_ereg_replace($remove_part,$replace,$removed_hoching);
					
								//print out result
								
				
								// restore value 
								$removed_hoching = $after_removed_adj;
								
								//print out result
							}else{
								//restore value it does not have adjective
								$after_removed_adj = $removed_hoching;
							}
						}
						//print out result after removing adjective process
						
						//just a break
						
						//restore value 
						$after_name_filter = $after_removed_adj;
						//if $after_name_filter string length is bigger than 0 and not just a space, process below lines 
						if(strlen($after_name_filter)>0 && $after_name_filter != " "){
							//make reqular expresstion for finding last word
							$pattern_finding_name = "\S*$|\S*\s$";
							//process finding last world
							mb_ereg_search_init($after_name_filter,$pattern_finding_name);
							//store what it found
							$after_name_filter = mb_ereg_search_regs();
							//store first array from $after_name_filter
							$after_name_filter=$after_name_filter[0];
							$name_plus_particle=$after_name_filter;

						}
						//print out the result of finding last word 
						
						//removed no need part of name
						$name_filter = "(는|은|가|을|를|에|에서|등|고|과|에게|의|로|도|하고|이랑|씨.*|이.*)$";
		
						//rmoving filtering process
						$after_name_filter = mb_ereg_replace($name_filter,$replace,$after_name_filter);
						
						//result print 
						
						//just a break
						
						//store word will be replaced
						$bracket_fillter = "(사진)";
						//store replace English word
						$replace_pic ="pictured";
						//replace process
						$after_name_filter = mb_ereg_replace($bracket_fillter,$replace_pic,$after_name_filter);
						//print out reuslt after bracket replace filter
						
						// if $after_name_filter is not empty or a space, print out below line
						if($after_name_filter=="" || $after_name_filter==" "){
							
						}else{
					
						for($a=0; $a<=$num_array; $a++){
							$number_of_words=num_of_clause_words($object_sentence_array[0]);
	
							for($aa=0; $aa<=$number_of_words; $aa++){
								$word=$object_sentence_array[$a][$aa]->word;
								$trimed_word = trim($word, " ");
								$trimed_after_name_filter = trim($name_plus_particle, " ");
								if($object_sentence_array[$a][$aa]->english == "")
								{
									if($trimed_word==$trimed_after_name_filter){
											$object_sentence_array[$a][$aa]->type = "name + particle";
									}else{
				
									}
								}		
									
							}
						
						}
					
					}
						unset($choice_hoching);	
			
					}
				///if find out hoching is only one hoching, do this if 			
				}elseif(count($choice_hoching)==1){
					
				//print_r($choice_hoching);
				
					//if hoching is "씨" process below lines 
					if ($choice_hoching[25] =="씨"){
					// make a reqular expresstion 
					$pattern_hoching1 = "(씨)(님|씨|양|군)?(들)?(는|은|로|이|가|을|를|에|에서|등|고|과|에게|의|도|하고|이랑)?\b";
					//seaching for matching part
					mb_ereg_search_init($word_from_sentence,$pattern_hoching1);
					//store searching result in array
					$regs1 = mb_ereg_search_regs();
				
					print_r ($regs1);
					//if regs1[2] is 들, hoching are plural
					if($regs1[2]=='들'){
						$plural=1;				
					}
					//set hoching pattern and hochig's detail
					$rule=1;
					$type=$row[2];
					$english=$row[3];
				}
				foreach($choice_hoching as $key => $value){
					$hoching = $value;			
				}

				// print out if hoching is plural
				if($plural==1){
					//reset plural
					$plural=0;
				}
				// if rule is rule 1, run below  
				if($rule==1){
				
					$num_array=count($object_sentence_array);

						for($a=0; $a<=$num_array; $a++){
							$number_of_words=num_of_clause_words($object_sentence_array[0]);

								for($aa=0; $aa<=$number_of_words; $aa++){
									$word=$object_sentence_array[$a][$aa]->word;
									$trimed_word = trim($word, " ");
									$trimed_hoching_plus_joint = trim($hoching_plus_joint, " ");
									
									$pattern_hoching_two_words = "\s.*";
									//finding two words hoching
									mb_ereg_search_init($trimed_hoching_plus_joint,$pattern_hoching_two_words);
						
									$regs = mb_ereg_search_regs();
									$two_words = false;
									if ($regs[0]!=""){
										$trimed_hoching_plus_joint = trim($regs[0], " ");
										$two_words = true;
									}

									if($trimed_word==$trimed_hoching_plus_joint){
													
										if($two_words == true){
											$object_sentence_array[$a][$aa-1]->english = true;
											$two_words = false;
										}
										
										
										$object_sentence_array[$a][$aa]->english = $english;
										$object_sentence_array[$a][$aa]->type = "hoching";
										$posible_the= true;												
									}

								}
							}

					$rule=0;
					//replace nothing 
					$replace=" ";
					//reqular expression for select removeing part 
					$remove_hoching_part="(^$hoching_plus_joint\s|\s$hoching_plus_joint\s|\s$hoching_plus_joint$|$hoching_plus_joint)";
				
					//removing hoching from sentence 
					$removed_hoching = mb_ereg_replace($remove_hoching_part,$replace,$word_from_sentence);
				
					//printing the result of removing hoching from then sentence
					
					//finding adjactive part
					//read adjective from database
					$query3="select * from trans_kadj";
					$result3=mysql_query($query3);
					$numofrow3=mysql_num_rows($result3);
		
					for ($i=0; $i<$numofrow3; $i++){
						$row=mysql_fetch_array($result3);
						//store each adjective
						$raw_adj= $row[1];
						//make a regluar express for removing/replacing adjective part
						$adjs = "(^$raw_adj\s|\s$raw_adj\s|\s$raw_adj$)";
						//removing/replacing process
						mb_ereg_search_init($removed_hoching,$adjs);
						//store result of process above
						$filtered_adjs = mb_ereg_search_regs();
						//print all what it found out from sentence it will displayed by array

							
						//if result is not empty, process below lines
						if($filtered_adjs[0]!=""){
							//print out found adjective  
							
							// store the finding part 
							$filtered_adjs_store = $filtered_adjs[0];
							//store English meaning of adjective from database 
							$adj_in_english = $row[3];
							//just a break 
							
						}
						//print adjactive from sentence and trans to English 
						if($filtered_adjs!=""){
							
						$num_array=count($object_sentence_array);

						for($a=0; $a<=$num_array; $a++){
							$number_of_words=num_of_clause_words($object_sentence_array[0]);

								for($aa=0; $aa<=$number_of_words; $aa++){
									$word=$object_sentence_array[$a][$aa]->word;

			
			
									$trimed_word = trim($word, " ");
									$trimed_filtered_adjs_store = trim($filtered_adjs_store, " ");
									
		
		
									if($trimed_word==$trimed_filtered_adjs_store){
										$object_sentence_array[$a][$aa]->english = $adj_in_english;
									}else{

									}
									
								}
							}
		
				
							//store adjective we found out from sentence	
							$adjective_number01 = $filtered_adjs[0];
					
							//regular expression for removing adjective from sentence
							$remove_part="(^$raw_adj\s|\s$raw_adj\s|\s$raw_adj$)";
							
							//replace with a space
							$replace=" ";
							
							//replace process (removing adjective from words)
							$after_removed_adj = mb_ereg_replace($remove_part,$replace,$removed_hoching);
							
							//print out result of removing process
							
				
							//restore value 
						    $removed_hoching = $after_removed_adj;
							
	
						}else{
							//if the words do not have any adjective, just store value after removed hoching
							$after_removed_adj = $removed_hoching;
						}	
					}
					//print out result
	
					//start name filtering process
					//resttore value for name filter
					$after_name_filter = $after_removed_adj;
					// process below line if $after_name_filter is bigger than 0 and it is not empty
					if(strlen($after_name_filter)>0 && $after_name_filter != " "){
						// make a regular express find out last word
						$pattern_finding_name = "\S*$|\S*\s$";
						// processing last word finding
						mb_ereg_search_init($after_name_filter,$pattern_finding_name);
						//sotre what it found
						$after_name_filter = mb_ereg_search_regs();
						//store first array
						$after_name_filter=$after_name_filter[0];
					}
					
					//store word for will be replaced
					$bracket_fillter = "(사진)";
					//store replace word
					$replace_pic ="pictured";
					//replacing process
					$after_name_filter = mb_ereg_replace($bracket_fillter,$replace_pic,$after_name_filter);
					//print out replacing process
					
			
					//print out below if $after_name_filter is empty or a space
					if($after_name_filter!="" && $after_name_filter!=" "){
				
						for($a=0; $a<=$num_array; $a++){
							$number_of_words=num_of_clause_words($object_sentence_array[0]);
	
							for($aa=0; $aa<=$number_of_words; $aa++){
								$word=$object_sentence_array[$a][$aa]->word;
								$trimed_word = trim($word, " ");
								$trimed_after_name_filter = trim($after_name_filter, " ");
								if($object_sentence_array[$a][$aa]->english == "")
								{
									if($trimed_word==$trimed_after_name_filter){
										$object_sentence_array[$a][$aa]->type = "name";
										$object_sentence_array[$a][$aa]->name = true;
										$name_confirmer=true;
										
									}
								}	
							}
						}
					}
				
					//unset $choice_hoching for next time use 
					unset($choice_hoching);	
					
					//hoching pattern is hoching + name
					}else{

						//print out what pattern of hoching
						$num_array=count($object_sentence_array);

							for($a=0; $a<=$num_array; $a++){
								$number_of_words=num_of_clause_words($object_sentence_array[0]);

									for($aa=0; $aa<=$number_of_words; $aa++){
										$word=$object_sentence_array[$a][$aa]->word;

										$trimed_word = trim($word, " ");
										$trimed_hoching_plus_joint = trim($hoching_plus_joint, " ");
										$pattern_hoching_two_words = "\s.*";
									//finding two words hoching
									mb_ereg_search_init($trimed_hoching_plus_joint,$pattern_hoching_two_words);
						
									$regs = mb_ereg_search_regs();
									$two_words = false;
									if ($regs[0]!=""){
										$trimed_hoching_plus_joint = trim($regs[0], " ");
										$two_words = true;
									}

									if($trimed_word==$trimed_hoching_plus_joint){
													
										if($two_words == true){
											$object_sentence_array[$a][$aa-1]->english = true;
											$two_words = false;
										}
										$object_sentence_array[$a][$aa]->english = $english;
										$object_sentence_array[$a][$aa]->type = 'hoching';
									}

								}
							}
			
						$rule=0;		

						//replace nothing 
						$replace=" ";
						//reqular expression for select removeing part 
						$remove_hoching_part="(^$hoching_plus_joint\s|\s$hoching_plus_joint\s|\s$hoching_plus_joint$)";
				
						//replacing hoching from sentence 
						$removed_hoching = mb_ereg_replace($remove_hoching_part,$replace,$word_from_sentence);
						//pint out reuslt of replace process
						
						
						//read adjective from database
						$query3="select * from trans_kadj";
						$result3=mysql_query($query3);
						$numofrow3=mysql_num_rows($result3);
							
							//use for loop for reading all database of adjective
							for ($i=0; $i<$numofrow3; $i++){
								$row=mysql_fetch_array($result3);
								//store each adjective from database
								$raw_adj= $row[1];
								//make a regular expression for adjective searching process
								$adjs = "(^$raw_adj\s|\s$raw_adj\s|\s$raw_adj$)";
								//adjective searching process
								mb_ereg_search_init($removed_hoching,$adjs);
								//store result of searching process
								$filtered_adjs = mb_ereg_search_regs();
				
								//print all what it found out from sentence it will displayed by array

			
									//if $filtered_adjs[0] is not empty, process below lines 
									if($filtered_adjs[0]!=""){
										//print out result of seaching prcoess
										
										//store the finding part 
										$filtered_adjs_store = $filtered_adjs[0];
										//store English meaning of adjective
										$adj_in_english = $row[3];
										//just a break
										
									}

									//print adjactive from sentence and trans to English 
									if($filtered_adjs!=""){
										
										$num_array=count($object_sentence_array);

										for($a=0; $a<=$num_array; $a++){
											$number_of_words=num_of_clause_words($object_sentence_array[0]);

												for($aa=0; $aa<=$number_of_words; $aa++){
													$word=$object_sentence_array[$a][$aa]->word;
													$trimed_word = trim($word, " ");
													$trimed_filtered_adjs_store = trim($filtered_adjs_store, " ");
						// 							$trimed_hoching_plus_joint = trim($hoching_plus_joint, " ");
													if($trimed_word==$trimed_filtered_adjs_store){
														$object_sentence_array[$a][$aa]->english = $adj_in_english;
													}else{

													}
													
												}
											}

										//sotre adjactive we find out from sentence	
										$adjective_number01 = $filtered_adjs[0];
											
										//reqular express for removing adjactive from sentence
										$remove_part="(^$raw_adj\s|\s$raw_adj\s|\s$raw_adj$)";
										
										//replace a space
										$replace=" ";
											
										//removing adjactive from sentence and replace nothing
										$after_removed_adj = mb_ereg_replace($remove_part,$replace,$removed_hoching);
										
										
										//restore value after removed adjective	
										$removed_hoching = $after_removed_adj;
								
										//just a break
										
									}else{
										//restore value if it does not contain any adjective
										$after_removed_adj = $removed_hoching;
									}
								}
							
							//restore information from $after_removed_adj to $after_name_filter for name filtering process
							$after_name_filter = $after_removed_adj;
				
							//process below lines if $after_name_filter is longer than 0 and it is not empty
							if(strlen($after_name_filter)>0 && $after_name_filter != " "){
								//make a regular expression for finding last word 
								$pattern_finding_name = "\S*$|\S*\s$";
								//finding last word process
								mb_ereg_search_init($after_name_filter,$pattern_finding_name);
								//store finding last world process result
								$after_name_filter = mb_ereg_search_regs();
								//store finding first array
								$after_name_filter=$after_name_filter[0];
								$name_plus_particle=$after_name_filter;
								
							}
							//make a regular express for removing unnecessary part of name 
							$name_filter = "(는|은|가|을|를|에|에서|로|등|고|과|에게|의|도|하고|이랑|씨.*|이.*)$";
				
							//rmoving filtering process
							$after_name_filter = mb_ereg_replace($name_filter,$replace,$after_name_filter);
							
							
							//store word, will be replaced
							$bracket_fillter = "(사진)";
							//store word for replace
							$replace_pic ="pictured";
							//replacing process
							$after_name_filter = mb_ereg_replace($bracket_fillter,$replace_pic,$after_name_filter);
							//print out result after replace pictured from 사진  
							
							
							//if after_name_filter is empty or just a space, print out bolew line
							if($after_name_filter=="" || $after_name_filter==" "){
								
							}else{
								
								for($a=0; $a<=$num_array; $a++){
									$number_of_words=num_of_clause_words($object_sentence_array[0]);
			
									for($aa=0; $aa<=$number_of_words; $aa++){
										$word=$object_sentence_array[$a][$aa]->word;
										$trimed_word = trim($word, " ");
										
										$trimed_after_name_filter = trim($name_plus_particle, " ");
										if($object_sentence_array[$a][$aa]->english == "")
										{
											if($trimed_word==$trimed_after_name_filter){
													$object_sentence_array[$a][$aa]->type = "name + particle";
													$name_confirmer= true;
													$object_sentence_array[$a][$aa]->name = true;										
											}else{
				
											}
										}	
											
									}
								
								}
							}
							//unset for $choice_hoching for next time use 
							unset($choice_hoching);	
						}
						//just a break			
						
					}
					//just a line
				
			//unset $rule(hoching pattern) for next time use
			unset($rule);
		//just a space
		
	}
	
//this part add "THE" if the hoching without name
	if($posible_the){
		$num_array=count($object_sentence_array);
		for($x=0; $x<=$num_array; $x++){
			$number_of_words=num_of_clause_words($object_sentence_array[$x]);
			for($xx=0; $xx<=$number_of_words; $xx++){
				if ($object_sentence_array[$x][$xx]->type == "hoching"){	
					if ($object_sentence_array[$x][$xx-1]->type!=name || is_null($object_sentence_array[$x][$xx-1]->type)){
						$english_the=$object_sentence_array[$x][$xx]->english;					
						$english_the = "the"." ".$english_the; 					
						$object_sentence_array[$x][$xx]->english = $english_the;
					}	
				}
			}
		}
	}
	

//this this function will help to add "ial or 's" after hoching	if next word type is not name + particle or name
	function hoching_plus_s(&$object_sentence_array){
		
	$num_array=count($object_sentence_array);
		for($q=0; $q<=$num_array; $q++){
			$number_of_words=num_of_clause_words($object_sentence_array[$q]);
			for($qq=0; $qq<=$number_of_words; $qq++){
				if ($object_sentence_array[$q][$qq]->type == "hoching" && $object_sentence_array[$q][$qq]->no_particle){
					if ($object_sentence_array[$q][$qq+1]->type != "name + particle" || $object_sentence_array[$q][$qq+1]->type != "name"){
						$object_sentence_array[$q][$qq]->english .= "'s";
					}
				}
			}
		}
	}
// call the function hoching_plus_s
	hoching_plus_s($object_sentence_array);
}




//*************************************************************************************************************************************
//*****************************FUNCTION NEW_FIND_PARTICLES_FOR_HOCHING*****************************************************************
//*****************************TASK OF FUCNTION:1. SPLIT SENTENCE WITH SPECAIL REGULAR EXPRESSION.*************************************
//*************************************************************************************************************************************
//*************************************************************************************************************************************
//*************************************************************************************************************************************
function new_find_particles_for_hoching($sentence) {
	//match anything but stop at word barriers ending with the characters in the square brackets below
 	$pattern = '.*?(\B는\b|\B로\b|\B은\b|\B이\b|\B가\b|\B을\b|\B를\b|\B에\b|\B에서\b|\B등\b|\B고\b|\B과\b|\B와\b|,|\s측\b)';	
	
	//use this variable so that we don't match anything more than once.
	$last_match = 0;
		
	//initalize the search
	mb_ereg_search_init($sentence, $pattern);

	//perform the search and handle the results
	while ($regs = mb_ereg_search_regs()) {
		
		$length = mb_strlen($regs[0]);
		
		//swich case on last element of the particle
		switch (mb_substr($regs[0],-1)) {
			case "은":
			case "는":
				//type 0
				$particle_type = 0;
			break;
			case "이":
			case "가":
				//type 1
				$particle_type = 1;
			break;
			case "을":
			case "를":
				//type 2
				$particle_type = 2;
			break;
			case "에":
			case "서":
				//type 3
				$particle_type = 3;
			break;
			default:
				unset($particle_type);
			break;
		}
		
		//find the position of the match using the "last match" as the offset
		//so we don't match the same thing twice (if it appeared previously)
		$last_match = mb_strpos($sentence, $regs[0],$last_match);
		
		//****NEED TO IMPROVE THIS FUNCTIONALITY****
		//to copy the behavior of the previous find_particle, we do not store
		//anything when we find a conjunction.
		
		//if (isset($particle_type)) {
			
			//the lastmatch is the beginning of the current section.
			$particle_array[$last_match] = array($length, $particle_type);
			
		//}
		$last_match++;
	}
	$nonparticle = NULL;		
	
	if (isset($particle_array)) {
		ksort($particle_array);
		//need to remove incorrect 을 and 는 markers
		foreach ($particle_array as $start_pos => $length_and_type_array) {
			
			//if $nonparticle is set, the previous array element is not affixed with a particle.
			//attempt to join the two blocks
			
			if(isset($nonparticle)) {
				//if previous length and offset are same as current offset, set previous as current.
				if (($particle_array[$nonparticle][0] + $nonparticle) >= ($start_pos -1)) {
					/*
					print_r($particle_array_element);
					
					$particle_array[$nonparticle][1] = $length_and_type_array[1];
					$particle_array[$nonparticle][0] += $length_and_type_array[0];
					/* we are removing this current element from the array.
					 * if we find the same situation with the current element, 
					 * we again will need to add it to the previous element.
					 * POSSIBLY UNCERTAIN RESULTS HERE.
					 */
					unset ($particle_array[$start_pos]);
				} else {
					unset($particle_array[$nonparticle]);
				}
				//clear nonparticle flag
				unset($nonparticle);
			}
			if($length_and_type_array[1] == 0 || $length_and_type_array[1] == 2) {
				//if pattern is 0 we have to check either way, if pattern is 2 need to check only 을
				$last_character = mb_substr($sentence, $start_pos + $length_and_type_array[0]-1, 1);
				// check for ~는 거 , ~는 게 , ~는 것 
				if ($last_character == "는") {
					$to_check = mb_substr($sentence, $start_pos + $length_and_type_array[0]+1,1);
	
					//this is an action verb if the following is true.  No need to check the definition.
					if ( $to_check== "거" || $to_check== "게" || $to_check== "것" ) {
						//check the word ahead of the '는'
						$sentence_section = mb_substr($sentence, 0, $start_pos + $length_and_type_array[0]-1);
						if($space_pos = mb_strrpos($sentence_section, " ")) {
							//if we find a space before the possible verb, get first verb word position
							$start_of_pverb = $space_pos + 1;
						} else {
							//this is the beginning of the sentence, search from position zero
							$start_of_pverb = 0;
						}
						$possible_verb = mb_substr($sentence_section, $start_of_pverb);
						
						if (is_nocon_verb($possible_verb)) {
							$nonparticle = $start_pos;
							continue;
						}
					}
				}
				// checking 을 는 은 for preceeding verbs.
				
				if ($last_character != "를"){
					$candidate = mb_substr($sentence, $start_pos, $length_and_type_array[0]-2);
					$candidate_split = mb_split(' ', $candidate);
					//
					//search for the small verb
					if (sizeof($candidate_split) >1) {
						//**** inefficient
						//need to do what we do by this if's else statement here with 
						//decomposing and recomposing.
						$for_find_tense_d = $candidate_split[sizeof($candidate_split) - 2] . " " . end($candidate_split);
						utf_normalizer::nfd($for_find_tense_d);
						$for_find_tense = jamo_to_co_jamo($for_find_tense_d);
						//
						$my_m_verb = find_tense ($for_find_tense);
						//if mverb has a space remove the beginning.
						if(mb_strpos($my_m_verb->mverb," ")) {
							
							$my_m_verb->mverb = mb_ereg_replace("[^\s]+\s+", "", $my_m_verb->mverb);
							$my_m_verb->length = mb_strlen($my_m_verb->mverb);
							$my_m_verb->two_word_tense(false);
							
						} else {
							
							$my_m_verb->two_word_tense(true);
							
						}
						$decomp_words = preg_split('/ /', $for_find_tense_d, -1, PREG_SPLIT_NO_EMPTY);
						if($my_m_verb->two_word_tense) {
							$decomp_lasttwoword = $decomp_words[sizeof($decomp_words) - 2] . " " . end($decomp_words);
						} else {
							$decomp_lasttwoword = end($decomp_words);
						}
						$firstpart = mb_substr($decomp_lasttwoword, 0, $my_m_verb->length, 'utf8');
						
						utf_normalizer::nfc($firstpart);
						
						$big_verb = $candidate_split[sizeof($candidate_split) - 2] . " " . $firstpart;
						k2e_verb($firstpart,$big_verb,$my_m_verb);
						
					} else {
											
						$for_find_tense_d = end($candidate_split);
						utf_normalizer::nfd($for_find_tense_d);
						$for_find_tense =  jamo_to_co_jamo($for_find_tense_d);
						$my_m_verb = find_tense ($for_find_tense);
						$firstpart = mb_substr($for_find_tense_d, 0, $my_m_verb->length, 'utf8');
						utf_normalizer::nfc($firstpart);
						k2e_verb($firstpart,NULL,$my_m_verb);
						
					}
					if(!empty($my_m_verb->english)) $nonparticle = $start_pos;
				}
			}
		}
	}

	return $particle_array;
}
//*****************************FUNCTION result_object_sentence_array*******************************************************************
//*****************************TASK OF FUCNTION:1. SAVE HOCHING DETIALS FROM HOCHING FUNCTION *****************************************
//**********************************************2. MATCHING WORD FROM OBJECT SENTENCE ARRAY *******************************************
//**********************************************3. REPLACE KOREAN WORD TO ENGLISH WORD ************************************************
//*************************************************************************************************************************************



function result_object_sentence_array(){
	
	
}

?>