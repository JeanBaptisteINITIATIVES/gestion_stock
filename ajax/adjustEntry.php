<?php
session_start();
$user_alias = $_SESSION['user-alias'];

require_once('../config/load.php');

$username = getUsernameByAlias($user_alias);
$user_id  = getUserId($user_alias);

// Type d'ajustement
$typeOfAdjust = $_POST['typeOfAdjust'];

// Initialisation de l'erreur
$array = array("qtyError"  =>  "",
			   "isSuccess" => true);

// Si c'est un ajustement positif
if ( $typeOfAdjust == 'plus' )
{
	// Récupération de l'id du produit
	$id = $_POST['plus-id'];

	// On récupère les données du produit
	$location     = checkInput($_POST['plus-loc']);
	$reference    = trim($_POST['plus-ref']);
	$designation  = trim($_POST['plus-des']);
	$quantity     = checkInput($_POST['new-plus-qty']);
	$status       = checkInput($_POST['plus-sts']);
	$comment      = trim($_POST['plus-comment']);

	// On récupère la quantité pour la comparer à la nouvelle
	$req = $db->prepare('SELECT quantity
						 FROM entry
						 WHERE id = :id');

	$req->bindParam(':id', $id, PDO::PARAM_INT);

	$req->execute();

	$oldQty = $req->fetch();

	$req->closeCursor();

	if ( isValidQuantity($quantity) )
	{
		// Nouvelle quantité à insérer en bdd
		$newQty = $oldQty['quantity'] + $quantity;

		$req = $db->prepare('UPDATE entry
							 SET quantity = :quantity, date_update = NOW(), user_id = :user_id
							 WHERE id = :id');

		$req->bindParam(':quantity', $newQty, PDO::PARAM_INT);
		$req->bindParam(':user_id', $user_id, PDO::PARAM_INT);
		$req->bindParam(':id', $id, PDO::PARAM_INT);

		$req->execute();

		$req->closeCursor();

		// Historique
		insertLog($location, $location, $reference, $designation, $oldQty['quantity'], $newQty, $status, $comment, 'Ajustement positif', $username);
	}
	else
	{
		$array["isSuccess"] = false;
		$array["qtyError"]  = "Erreur sur la quantité";
	}
}
elseif ( $typeOfAdjust == 'minus' )
{
	// On récupère les données du produit
	$location     = checkInput($_POST['minus-loc']);
	$reference    = checkInput($_POST['minus-ref']);
	$designation  = checkInput($_POST['minus-des']);
	$quantity     = checkInput($_POST['new-minus-qty']);
	$status       = checkInput($_POST['minus-sts']);
	$comment      = checkInput($_POST['minus-comment']);

	// Récupération de l'id du produit
	$id = $_POST['minus-id'];

	// On récupère la quantité pour la comparer à la nouvelle
	$req = $db->prepare('SELECT quantity
						 FROM entry
						 WHERE id = :id');

	$req->bindParam(':id', $id, PDO::PARAM_INT);

	$req->execute();

	$oldQty = $req->fetch();

	$req->closeCursor();

	if ( isValidQuantity($quantity) && $oldQty['quantity'] > $quantity )
	{
		// Nouvelle quantité à insérer en bdd
		$newQty = $oldQty['quantity'] - $quantity;

		$req = $db->prepare('UPDATE entry
							 SET quantity = :quantity, date_update = NOW(), user_id = :user_id
							 WHERE id = :id');

		$req->bindParam(':quantity', $newQty, PDO::PARAM_INT);
		$req->bindParam(':user_id', $user_id, PDO::PARAM_INT);
		$req->bindParam(':id', $id, PDO::PARAM_INT);

		$req->execute();

		$req->closeCursor();

		// Historique
		insertLog($location, $location, $reference, $designation, $oldQty['quantity'], $newQty, $status, $comment, 'Ajustement négatif', $username);
	}
	else
	{
		$array["isSuccess"] = false;
		$array["qtyError"]  = "Erreur sur la quantité";
	}
}

echo json_encode($array);
