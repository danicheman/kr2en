<?php

class fruit {
	function fruit ( $type, $color ) {
		$this->type = $type;
		$this->color = $color;
	}
}

function make_fruit ( $the_type, $the_color) {
	
	$my_fruit = new fruit( "apple", "yellow");
	return $my_fruit;
	
}
echo "<pre>";
print_r ($my_fruit);
echo "</pre>";


?>