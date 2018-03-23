<?php
require_once('../config/load.php');

// Initialisation variable
$array        = array("isLocationEmpty"   => true,
					  "isEntryOnLocation" => false
				);

$location     = checkInput($_POST['location']);
$reference    = trim($_POST['reference']);
$designation  = trim($_POST['designation']);
$status       = checkInput($_POST['status']);

$location_id  = getLocationId($location);

if ( !checkIfLocationEmpty($location_id) )
{
	$array["isLocationEmpty"] = false;
}
// Contrôle si le produit existe déjà à cet emplacement à statut identique
if ( checkIfEntryExist($location_id, $reference, $designation, $status) )
{
	$array["isEntryOnLocation"]  = true;
}

echo json_encode($array);


