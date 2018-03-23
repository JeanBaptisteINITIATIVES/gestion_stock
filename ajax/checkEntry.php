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
	$reference    = trim($_POST['reference']);
	$designation  = trim($_POST['designation']);
	$quantity     = checkInput($_POST['quantity']);
	$status       = checkInput($_POST['status']);

	$typeOfEntry  = checkInput($_POST['entry-by']); 
	
	$location_id = getLocationId($location);
	// $product_id  = getProductId($reference, $designation);     
	
	$array["isSuccess"] = true;
	
	if ( $typeOfEntry == "tracked" ) // Si suivi en stock
	{
		$req_url_ref = json_decode(file_get_contents('http://sccoreapi/v1/product/?filter={"is_stock_managed":-1,"id__exact":"' . urlencode($reference) . '"}&rows=99999'));
		$req_url_des = json_decode(file_get_contents('http://sccoreapi/v1/product/?filter={"is_stock_managed":-1,"name__exact":"' . urlencode(addslashes($designation)) . '"}&rows=99999'));

		// Contrôle de l'existence de l'emplacement
		if ( !checkIfLocationExist($location) || empty($location) )
		{
			$array["isSuccess"] = false;
			$array["locError"] = "Emplacement non-valide";
		}

	    // // Contrôle de l'existence de la référence
		// if ( !checkIfReferenceExist($reference) || empty($reference) )
		// {
		// 	$array["isSuccess"] = false;
		// 	$array["refError"] = "Référence non-valide";
		// }

		// Contrôle de l'existence de la d ésignation
		// if ( !checkIfDesignationExist($designation) || empty($designation) )
		// {
		// 	$array["isSuccess"] = false;
		// 	$array["desError"] = "Désignation non-valide";
		// }

		// Teste si référence existe dans Scorre
		if ( count($req_url_ref) === 0 || empty($reference) )
		{
			$array["isSuccess"] = false;
			$array["refError"]  = "Référence inexistante";
		}
		
		// Teste si désignation existe dans Scorre
		if ( count($req_url_des) === 0  || empty($designation) )
		{
			$array["isSuccess"] = false;
			$array["desError"]  = "Désignation inexistante";
		}
		
		// Contrôle de la quantité saisie
		if ( !isValidQuantity($quantity) )
		{
			$array["isSuccess"] = false;
			$array["qtyError"]  = "Erreur sur la quantité";
		}

		// Contrôle si emplacement bloqué
		if ( checkIfLocationLocked($location_id) )
		{
			$array["isSuccess"]  = false;
			$array["inputError"] = "Emplacement bloqué.";
		}
	}
	else
	{
		// Contrôle de l'existence de l'emplacement
		if ( !checkIfLocationExist($location) || empty($location) )
		{
			$array["isSuccess"] = false;
			$array["locError"]  = "Emplacement non-valide";
		}

		// Contrôle de la quantité saisie
		if ( !isValidQuantity($quantity) )
		{
			$array["isSuccess"] = false;
			$array["qtyError"]  = "Erreur sur la quantité";
		}

		if ( empty($reference) )
		{
			$array["isSuccess"] = false;
			$array["refError"]  = "Entrez une référence";
		}

		if ( empty($designation) )
		{
			$array["isSuccess"] = false;
			$array["desError"]  = "Entrez une désignation";
		}
	}	

	echo json_encode($array);
}