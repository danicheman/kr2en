<pre><?php
//m_verb class.  Contains modified Korean verb and later can hold english translation.
class m_verb {
	function english($e, $w) {
		$this->english = $e;
		$this->isdouble = $w; // was the verb located by searching for two words?
	}
	function double_trans($dt) {
		$this->double_trans = $dt;
	}
	function __construct($t, $mv, $l) {
			$this->tense = $t;
			$this->mverb = $mv;
			$this->length = $l;
	}
}

include("connect.php"); 
include("../phpBB3/includes/utf/utf_normalizer.php");
include("jamo_con.php");
include("matching.php");

	mb_internal_encoding( 'UTF-8' );
	mb_regex_encoding('utf-8');
	mysql_query("SET NAMES 'utf8'");
	$sentance = "너 여기에 누구 만나러 왔니?";
	echo $sentance . "\n";
	$shifted_sentance = $sentance;
	$decomp_sentance = $shifted_sentance;
	utf_normalizer::nfd(&$decomp_sentance);
	$shifted_sentance = jamo_to_co_jamo($decomp_sentance);
	
	$result = k2e_auxillary($shifted_sentance, $decomp_sentance, NULL);

	
// auxillary class.  handle sentance endings with auxillaries built in.
//"(ㅇㅡ)ㄹㅓ\W(ㄱㅏ)|(ㅇ(ㅗ|ㅘ))"
function k2e_auxillary($shifted_sentance, $decomp_sentance, $words_array) {
	if( !$shifted_sentance || !$decomp_sentance ) return false;
	$patterns = array( "(ㅇㅡ)?ㄹㅓ\Wㄱㅏ","(ㅇㅡ)?ㄹㅓ\Wㅇ(ㅗ|ㅘ)",	"ㄱㅏ$", 	"ㅇ(ㅗ|ㅘ)$", 
	"(ㅇㅡ)ㄹㅓ ㄷㅡㄹ", "ㄴㅐ", "ㄱㅗ ㄴㅏ", 	"ㅂㅓㄹ(ㅣ|ㅕ)", 	"ㄱㅗ ㅁㅏㄹ",
	"ㅃㅏㅈ(ㅣ|ㅕ)", "ㅊㅣㅇ(ㅜ|ㅝ)", "(ㅈ(ㅜ|ㅝ))|(ㄷㅡㄹ(ㅣ|ㅕ))", "ㄷㅡㄹㅣㄹㄲㅏ",
	"ㅈㅜㅅㅣㅂㅅㅣㅇㅗ", 	"ㅈㅜㅅㅣㄱㅔㅆㅅㅡㅂㄴㅣㄲㅏ",		"ㄷㅡㄹㅣㄱㅔㅆㅅㅡㅂㄴㅣㄷㅏ",
	"ㅂㅗㄹㄲㅏ", 			"ㅂㅗㄴ ㅇㅣㄹㅇㅣ ㅇㅣㅆ",			"ㅂㅗㄴ ㅇㅣㄹㅇㅣ ㅇㅓㅄ(ㅇㅓ)?",
	"ㅂ(ㅗ|ㅘ)",	"ㄷㅐ", "ㄱㅗㄴ ㅎ(ㅏ|ㅐ)", "ㄴㅗㅎ(ㅇㅏ)?","ㄷ(ㅜ|ㅝ)","ㄱㅏㅈㅣㄱㅗ",
	"ㄱㅗ ㅅㅣㅍ(ㅇㅓ)?", "ㄱㅗ ㅅㅣㅍㅇㅓㅎ(ㅏ|ㅐ)", "ㅆㅇㅡㅁㅕㄴ ㅅㅣㅍ(ㅇㅓ)?",
	"ㄴㅏ ㅅㅣㅍ(ㅇㅓ)?", "ㅇㅣㅆ(ㅇㅓ)?", "ㅈ(ㅣ|ㅕ)", "ㅈㅣ ㅁㅏㄹ(ㅇㅏ)?", "ㅁㅏㅅㅣㅇㅗ",
	"ㅈㅣ ㅇㅏㄶ(ㅇㅏ)?", "ㅈㅣ ㅇㅏㄴㅣㅎ(ㅏ|ㅐ)", "ㅈㅣ ㅁㅗㅅㅎ(ㅏ|ㅐ)");
	//0 is nocon (false) and 1 is con (true)
	$nocon_or_con = array( 0,0,1,1,0,1,0,1,0,1,1,1,1,1,1,1,1,1,1,1,1,0,1,1,1,0,0,1,0,1,1,0,0,0,0,0 );
	//0 - swv 1 - in place 2 - to front 3 - front & back
	$t_action = array( 0,0,0,0,0,2,0,2,0,0,1,0,3,3,3,3,0,0,0,0,0,0,1,0,1,0,0,0,0,1,0,2,2,0,0,0 );
	$subtype = array( 0,0,0,0,0,1,1,1,1,1,1,2,2,2,2,2,3,3,3,3,4,4,5,5,5,6,6,6,6,7,7,8,8,8,8,8 );
	$subtype_names = array(	'Progressive', 'Terminative', 'Donatory', 'Exploratory', 
							'Iterative', 'Retentive', 'Desiderative', 'Descriptive',
							'Negative' );
	$english = array( 	'Go for the purpose of', 'Come for the purpose of', 'In the end', 'just now', 'ended up', 'badly', 
						'thoroughly', 'graciously', 'Shall I ... for you?', 'Please ... for me.',
						'Would you please ... for me?', "I'll ... for you.", 'try','have tried',
						'have not tried', 'try', 'constantly', 'regularly', 'in advance.',
						'carefully', 'so', 'to want to', 'to want to', 'really feel like',
						'supposing','currently.','becoming','Do not','Please do not','to do not',
						'to do not','cannot');
						
	for ($i = 0; $i < 35;$i++) {
		

		mb_ereg_search_init($shifted_sentance,$patterns[$i]);
		
		while($regs = mb_ereg_search_regs()) {
			
			echo $regs[0]." ";
			echo $english[$i] . "\n";
			
			$au_pos = mb_strpos($shifted_sentance, $regs[0]); 
			$au_length = mb_strlen($regs[0]);
			
			//split sentance by portion found
			$without_au = mb_split($regs[0], $shifted_sentance, 2);
			
			$wo_au_words = mb_split(" ", $without_au[0]);
			$w_a_w_length = sizeof($wo_au_words);
			if ( $w_a_w_length > 1 )$bigverb = $wo_au_words[$w_a_w_length -2] . " " . end($wo_au_words);
			
			$verb = end($wo_au_words);
			
			//are we searching for con or no con in front? 
			
			if ($nocon_or_con) { //searching for con in front.
				$query = "select km.english from trans_kverbs as kv,trans_kverb_meanings as km WHERE kv.vin = km.vin AND kv.kcon = \"".$verb."\"";
			} else { //searching for nocon in front.
				$query = "select km.english from trans_kverbs as kv,trans_kverb_meanings as km WHERE kv.vin = km.vin AND kv.knocon = \"".$verb."\"";
			}
		}
	}
}
?> </pre>