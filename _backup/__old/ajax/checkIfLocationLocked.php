<?php
require_once('../config/load.php');

// Initialisation variable
$array        = array("isLocationLocked" => false,
					  "isSuccess"        => true,
					  "locError"         => "");

$location     = checkInput($_POST['block']);

$location_id  = getLocationId($location);

if ( !checkIfLocationExist($location) || empty($location) )
{
	$array["isSuccess"] = false;
	$array["locError"] = "Emplacement non-valide";
}

if ( checkIfLocationLocked($location_id) )
{
	$array["isLocationLocked"] = true;
}

echo json_encode($array);