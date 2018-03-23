<?php
session_start();
$user_alias = $_SESSION['user-alias'];

require_once('../config/load.php');

if ( $_SERVER["REQUEST_METHOD"] == "POST" )
{
	$location     = checkInput($_POST['location']);
	$reference    = checkInput($_POST['reference']);
	$designation  = checkInput($_POST['designation']);
	$quantity     = checkInput($_POST['quantity']);
	$status       = checkInput($_POST['status']);
	$comment      = checkInput($_POST['comment']);

	$typeOfEntry  = checkInput($_POST['entry-by']);

	$location_id  = getLocationId($location);
	// $product_id   = getProductId($reference, $designation);
	$user_id      = getUserId($user_alias);
	$username     = getUsernameByAlias($user_alias);

	// Si produit suivi en stock
	if ( $typeOfEntry == "tracked" )
	{	
		$stock = -1;
		// Insertion produits en bdd
		insertEntry($location, $reference, $designation, $quantity, $status, $comment, $user_id, $stock);
	}
	else
	{
		$stock = 0;
		insertEntry($location, $reference, $designation, $quantity, $status, $comment, $user_id, $stock);
	}

	// Infos logs
	insertLog($location, $location, $reference, $designation, $quantity, $quantity, $status, $comment, 'Entrée de stock', $username);
}

