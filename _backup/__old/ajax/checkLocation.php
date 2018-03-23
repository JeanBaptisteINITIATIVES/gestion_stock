<?php
require_once('../config/load.php');

// Initialisation variable
$array        = array("isLocationEmpty" => true);

$location     = checkInput($_POST['location']);

$location_id  = getLocationId($location);

if ( !checkIfLocationEmpty($location_id) )
{
	$array["isLocationEmpty"] = false;
}

echo json_encode($array);


