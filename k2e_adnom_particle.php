<?php

//need to build array which describes words in the sentance as they are identified.
class found_words {
	function __construct( $wn, $c, $t, $sp,$e,$m,$f,$plu,$pe,$g,$th,$pla ) {
			$this->word_numbers = $wn;	//the numbers translated here correspond to which numbers in the sentance?
			$this->context = $c;		//context that the word is to be translated in.  1 is default.
			$this->type = $t;	//verb/phrase/noun/etc.
			$this->sentance_position = $sp;
			$this->english = $e;		//english meaning
			$this->is_masculine =$m; 	//can be replaced by "he/his" pronouns
			$this->is_feminine = $f;	//can be replaced by "she/her" pronouns	
			$this->is_plural = $plu; 	//needs an s at the end of the word	
			$this->is_person = $pe;		//verb translation should be one corresponding to a person
			$this->is_group = $g;		//verb translation should be one corresponding to a group
			$this->is_thing = $th;		//verb translation should be one corresponding to a thing
			$this->is_place = $pla;		//verb translation should be one corresponding to a place
	}
}



