<?php


/* 
 * The new print sentence conjunctions function is the same as the old one except that
 * it does not use decomposed / shifted conjunction values.  it only uses regular 
 * values.
 */
function new_print_sentence_conjunctions ($sentence, $clause_array) {
// 	echo $sentence . "<br>";
// 	echo "<div style=\"color: blue;\">Now printing found sentence conjunctions in BLUE</div>";
	$last_pos = 0;
	foreach( $clause_array as $c_object ) {
		echo "<span style=\"color: gray;\">";
		$clause = mb_substr( $sentence, $last_pos, $c_object->m_verb->verb_start_pos - $last_pos );
		echo $clause;
		echo "</span><span style=\"color: green;\">";
		$pre_conj = mb_substr( $sentence, $c_object->m_verb->verb_start_pos, $c_object->conj_pos - $c_object->m_verb->verb_start_pos );
		echo $pre_conj;
		echo "</span>";
		echo "</span><span style=\"color: blue;\">";
		$conj = mb_substr( $sentence, $c_object->conj_pos, $c_object->conj_length );
		echo $conj;
		echo "</span>";
		$last_pos = $c_object->conj_pos + $c_object->conj_length;
		if (!next($clause_array)) {
			//no more conjunctions so print from here to the end of the sentence.
			$remaining = mb_substr( $sentence, $last_pos );
			echo "<span style=\"color: gray;\">";
			echo $remaining;
			echo "</span>";
		}
	}
	echo "<br>";
}
?>