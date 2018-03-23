<?php
session_start();
$user_alias = $_SESSION['user-alias'];

require_once('../config/load.php');

$username = getUsernameByAlias($user_alias);
$user_id  = getUserId($user_alias);

// Initialisation de l'erreur
$array = array("locError"  => "",
			   "isLocationEmpty" => true,
			   "isEntryOnLocation" => false,
			   "isSuccess" => true);

// On récupère les données du produit
$old_location = checkInput($_POST['transfer-loc']);
$new_location = checkInput($_POST['new-transfer-loc']);
$reference    = trim($_POST['transfer-ref']);
$designation  = trim($_POST['transfer-des']);
$quantity     = checkInput($_POST['transfer-qty']);
$status       = checkInput($_POST['transfer-sts']);
$comment      = trim($_POST['transfer-comment']);

$location_id  = getLocationId($new_location);
// $product_id   = getProductId($reference, $designation);

// Contrôle si le produit existe déjà à cet emplacement à statut identique
if ( checkIfEntryExist($location_id, $reference, $designation, $status) )
{
	$array["isEntryOnLocation"]  = true;
}
if ( checkIfLocationLocked($location_id) )
{
	$array["isSuccess"] = false;
	$array["locError"]  = "Emplacement bloqué.";
}
if ( !checkIfLocationEmpty($location_id) )
{
	$array["isLocationEmpty"] = false;
}
if ( !checkIfLocationExist($new_location) || $old_location == $new_location )
{
	$array["isSuccess"] = false;
	$array["locError"]  = "Erreur sur le nouvel emplacement";
}

echo json_encode($array);
