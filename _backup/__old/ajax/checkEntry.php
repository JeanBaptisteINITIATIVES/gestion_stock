<?php
session_start();
$user = $_SESSION['user-alias'];

require_once('../config/load.php');

// Initialisation du tableau contenant les infos à renvoyer en json
$array = array("locError"        => "",
			   "refError"        => "",
			   "desError"        => "",
			   "qtyError"        => "",
			   "inputError"      => "",
			   "isSuccess"       => false,
			  );

if ( $_SERVER["REQUEST_METHOD"] == "POST" )
{
	// Récupération des infos saisies
	$location  	  = checkInput($_POST['location']);
	$reference    = checkInput($_POST['reference']);
	$designation  = checkInput($_POST['designation']);
	$quantity     = checkInput($_POST['quantity']);
	$status       = checkInput($_POST['status']);

	$typeOfEntry  = checkInput($_POST['entry-by']); 

	$location_id = getLocationId($location);
	$product_id  = getProductId($reference, $designation);     

	$array["isSuccess"] = true;

	if ( $typeOfEntry == "tracked" )
	{
		// Contrôle de l'existence de l'emplacement
		if ( !checkIfLocationExist($location) || empty($location) )
		{
			$array["isSuccess"] = false;
			$array["locError"] = "Emplacement non-valide";
		}

	    // Contrôle de l'existence de la référence
		if ( !checkIfReferenceExist($reference) || empty($reference) )
		{
			$array["isSuccess"] = false;
			$array["refError"] = "Référence non-valide";
		}

		// Contrôle de l'existence de la désignation
		if ( !checkIfDesignationExist($designation) || empty($designation) )
		{
			$array["isSuccess"] = false;
			$array["desError"] = "Désignation non-valide";
		}

		// Contrôle de la quantité saisie
		if ( !isValidQuantity($quantity) )
		{
			$array["isSuccess"] = false;
			$array["qtyError"] = "Erreur sur la quantité";
		}

		// Contrôle si le produit existe déjà à cet emplacement à statut identique
		if ( checkIfEntryExist($location_id, $product_id, $status) )
		{
			$array["isSuccess"]  = false;
			$array["inputError"] = "Produit déjà présent: voir pour ajustement de stock.";
		}

		// Contrôle si emplacement bloqué
		if ( checkIfLocationLocked($location_id) )
		{
			$array["isSuccess"] = false;
			$array["inputError"] = "Emplacement bloqué.";
		}
	}
	else
	{
		// Contrôle de l'existence de l'emplacement
		if ( !checkIfLocationExist($location) || empty($location) )
		{
			$array["isSuccess"] = false;
			$array["locError"] = "Emplacement non-valide";
		}

		// Contrôle de la quantité saisie
		if ( !isValidQuantity($quantity) )
		{
			$array["isSuccess"] = false;
			$array["qtyError"] = "Erreur sur la quantité";
		}

		if ( empty($reference) )
		{
			$array["isSuccess"] = false;
			$array["refError"] = "Entrer une référence";
		}

		if ( empty($designation) )
		{
			$array["isSuccess"] = false;
			$array["desError"] = "Entrer une désignation";
		}
	}	

	echo json_encode($array);
}