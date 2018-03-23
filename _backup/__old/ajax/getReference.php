<?php
require_once('../config/load.php');

$term = $_GET['term'];

$req = 'SELECT *
		FROM products
		WHERE reference LIKE :term';

if ( isset($_GET['entry']) )
{
	$typeOfEntry = $_GET['entry'];

	if ( $typeOfEntry == 'tracked' )
	{
		$req .= ' AND stock = -1
			      LIMIT 0, 40';
	}
	else
	{
		$req .= ' AND stock = 0
				  LIMIT 0, 40';
	}
}
else
{
	$req .= ' LIMIT 0, 40';
}


$result = $db->prepare($req);

$result->execute(array('term' => $term . '%'));

$array = array();

while( $results = $result->fetch() )
{
    
    $array_results = array("reference"   => $results['reference'],
    					   "designation" => $results['designation']);
    
    array_push($array, $array_results);
}

$result->closeCursor();

echo json_encode($array);