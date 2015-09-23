<?php

class employee {

	function employee($id, $name, $division) {
		$this->id = $id;
		$this->name = $name;
		$this->division = $division;
		
	}
}

//construct employees

$my_employee = new employee( "A77", "Nick O", "Sales");

$my_other_employee = new employee ( "A78", "Amy C", "Executive Management");

$employee_array = array($my_employee,$my_other_employee, 235452332, "Bobby", "Blue");

echo "<pre>";
print_r($employee_array);
echo "</pre>";

$my_favorite_number = 7;

$my_favorite_squared_number = square($my_favorite_number);

echo "My favorite number is: " . $my_favorite_number . " and when you square it it becomes: " .
$my_favorite_squared_number;

function square($number) {
	$square_of_number = $number * $number;
	return $square_of_number;
}
?>