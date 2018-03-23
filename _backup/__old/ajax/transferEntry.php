<?php
session_start();
$user_alias = $_SESSION['user-alias'];

require_once('../config/load.php');

$username = getUsernameByAlias($user_alias);
$user_id  = getUserId($user_alias);

// Initialisation de l'erreur
$array = array("locError"  => "",
			   "isSuccess" => true);

// Récupération de l'id du produit
$id = $_POST['transfer-id'];

// On récupère les données du produit
$old_location = checkInput($_POST['transfer-loc']);
$new_location = checkInput($_POST['new-transfer-loc']);
$reference    = checkInput($_POST['transfer-ref']);
$designation  = checkInput($_POST['transfer-des']);
$quantity     = checkInput($_POST['transfer-qty']);
$status       = checkInput($_POST['transfer-sts']);
$comment      = checkInput($_POST['transfer-comment']);

$location_id  = getLocationId($new_location);
$product_id   = getProductId($reference, $designation);

// Si le produit est suivi en stock
if ( isset($_POST['transfer-stock']) && $_POST['transfer-stock'] == -1 )
{
	// Contrôle si le produit existe déjà à cet emplacement à statut identique
	if ( checkIfEntryExist($location_id, $product_id, $status) )
	{
		$array["isSuccess"]  = false;
		$array["locError"] = "Produit déjà présent: voir pour ajustement de stock.";
	}
	elseif ( checkIfLocationLocked($location_id) )
	{
		$array["isSuccess"] = false;
		$array["locError"]  = "Emplacement bloqué.";
	}
	elseif ( checkIfLocationExist($new_location) && $old_location != $new_location )
	{
		$req = $db->prepare('UPDATE stock_entry
							 SET location_id = :location_id, date_update = NOW(), user_id = :user_id
							 WHERE stock_id = :id');

		$req->bindParam(':location_id', $location_id, PDO::PARAM_INT);
		$req->bindParam(':user_id', $user_id, PDO::PARAM_INT);
		$req->bindParam(':id', $id, PDO::PARAM_INT);

		$req->execute();

		$req->closeCursor();

		// Historique
		insertLog($old_location, $new_location, $reference, $designation, $quantity, $quantity, $status, $comment, 'Transfert d\'emplacement', $username);
	}
	else
	{
		$array["isSuccess"] = false;
		$array["locError"]  = "Erreur sur le nouvel emplacement";
	}
}
elseif ( isset($_POST['transfer-stock']) && $_POST['transfer-stock'] == 0 )
{
	if ( checkIfLocationExist($new_location) && $old_location != $new_location )
	{
		$req = $db->prepare('UPDATE free_entry
							 SET location_id = :location_id, date_update = NOW(), user_id = :user_id
							 WHERE free_id = :id');

		$req->bindParam(':location_id', $location_id, PDO::PARAM_INT);
		$req->bindParam(':user_id', $user_id, PDO::PARAM_INT);
		$req->bindParam(':id', $id, PDO::PARAM_INT);

		$req->execute();

		$req->closeCursor();

		// Historique
		insertLog($old_location, $new_location, $reference, $designation, $quantity, $quantity, $status, $comment, 'Transfert d\'emplacement', $username);
	}
	else
	{
		$array["isSuccess"] = false;
		$array["locError"]  = "Erreur sur le nouvel emplacement";
	}
}

echo json_encode($array);
