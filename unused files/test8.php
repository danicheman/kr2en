<?php
	mb_internal_encoding( 'UTF-8' );
	mb_regex_encoding('UTF-8');
	
	echo "<pre>";

print_r(find_particles ("(원)희룡 의원은 26일 보도자료를 통해 \"국회의 주택법 개정안 심사가 한나라당의 적극적 반대로 인해 파행으로 치달았고, 더 놀라운 것은 한나라당이 원희룡 의원이 작년에 발의한 공공택지에만 한정해서 분양원가를 공개하자고 주장했다\"며 \"제가 작년에 발의한 주택법 개정안은 대통령이 앞장 서 분양원가 공개를 막고 정치권이 침묵으로 일관하던 시대에 분양원가 공개에 대한 논의의 불씨를 당겼다는데 그 시대적 소명이 있었던 것으로 민간 택지에 대한 분양원가 공개를 앞둔 지금 제가 제출했던 법안은 그 시대적 소명을 다했다고 생각한다\"고 주장했다."));


function find_particles ($sentance) {
	
	//Assumes sentances have already been separated. $sentance should really just be a sentance ;)
	$pattern[0] = '([가-힣A-z\d]*(\.\d+)?[^이가를을에고는은서\W]\W+)*[가-힣A-z\d]+(\.\d+)?(은|는)\W'; //topic - functions under the assumtion sentances are separated.
	$pattern[1] = '([가-힣A-z\d]*(\.\d+)?[^이가를을에고는은서\W]\W+)*[가-힣A-z\d]+(\.\d+)?(이|가)\W'; //subject, don't match 많이
	$pattern[2] = '([가-힣A-z\d]*(\.\d+)?[^이가를을에고는은서\W]\W+)*[가-힣A-z\d]+(\.\d+)?(을|를)\W'; //object 
	$pattern[3] = '([가-힣A-z\d]*(\.\d+)?[^이가를을에고는은서\W]\W+)*[가-힣A-z\d]+(\.\d+)?(에|에서)\W'; //verb modifier
	
	//find, mark particles and print sentance excluding verb.
	
	for ($i = 0;$i<4;$i++) {
	
		mb_ereg_search_init($sentance, $pattern[$i]);
		$lastmatch=0;
		
		while($regs = mb_ereg_search_regs()) {
			echo $regs[0] . "\n";
			$lastmatch = mb_strpos($sentance, $regs[0],$lastmatch, "utf8");
			$length =  mb_strlen($regs[0], "utf8");
			$stack[$lastmatch] = array($length, $i);
			
			//echo "lastmatch is $lastmatch and length is $length \n";
			$lastmatch++;
		}
	}
	
	ksort($stack);
	return $stack;
	
}
?>