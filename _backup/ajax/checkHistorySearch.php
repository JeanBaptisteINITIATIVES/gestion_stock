<?php
require_once('../config/load.php');

// Initialisation du tableau contenant les infos à renvoyer en json
$array = array("dateOneError" => "",
			   "dateError"    => "",
			   "isSuccess"    => false
			  );
// print_r($_POST);

if ( $_SERVER["REQUEST_METHOD"] == "POST" )
{
	$array["isSuccess"] = true;
	// Récupération des infos saisies
	$reference    = checkInput($_POST['history-ref']);
	$designation  = checkInput($_POST['history-des']);

	// $product_id  = getProductId($reference, $designation);

	// Contrôle des dates si renseignées
	if ( !empty($_POST['date-one']) )
	{
		$date     = explode("/", $_POST['date-one']);
		$month    = (int) $date[1];
		$day      = (int) $date[0];
		$year     = (int) $date[2];
		$isDateOk = checkdate($month, $day, $year);

		if ( !$isDateOk )
		{
			$array["isSuccess"] = false;
			$array["dateOneError"] = "Problème de date";
		}
	}
	elseif ( !empty($_POST['date-first']) && !empty($_POST['date-last']) )
	{
		$dateFirst     = explode("/", $_POST['date-first']);
		$monthF        = (int) $dateFirst[1];
		$dayF      	   = (int) $dateFirst[0];
		$yearF     	   = (int) $dateFirst[2];
		$isDateFirstOk = checkdate($monthF, $dayF, $yearF);

		$dateLast      = explode("/", $_POST['date-last']);
		$monthL        = (int) $dateLast[1];
		$dayL      	   = (int) $dateLast[0];
		$yearL     	   = (int) $dateLast[2];
		$isDateLastOk  = checkdate($monthL, $dayL, $yearL);

		if ( !$isDateFirstOk || !$isDateLastOk || $_POST['date-first'] >= $_POST['date-last'] )
		{
			$array["isSuccess"] = false;
			$array["dateError"] = "Problème de date";
		}
	}    

    // // Contrôle de l'existence de la référence
	// if ( !checkIfReferenceExist($reference) || empty($reference) )
	// {
	// 	$array["isSuccess"] = false;
	// 	$array["refError"] = "Référence non-valide";
	// }

	// // Contrôle de l'existence de la désignation
	// if ( !checkIfDesignationExist($designation) || empty($designation) )
	// {
	// 	$array["isSuccess"] = false;
	// 	$array["desError"] = "Désignation non-valide";
	// }

	echo json_encode($array);
}