<?php
session_start();
$user_alias = $_SESSION['user-alias'];

require_once('../config/load.php');

$username = getUsernameByAlias($user_alias);
$user_id  = getUserId($user_alias);

// Récupération de l'id du produit
$id = $_POST['update-id'];

// On récupère les données du produit
$comment     = checkInput($_POST['update-comment']);
$location    = checkInput($_POST['update-loc']);
$reference   = checkInput($_POST['update-ref']);
$designation = checkInput($_POST['update-des']);
$quantity    = checkInput($_POST['update-qty']);
$status      = checkInput($_POST['update-sts']);

// Si le produit est suivi en stock
if ( isset($_POST['update-stock']) && $_POST['update-stock'] == -1 )
{
	$req = $db->prepare('UPDATE stock_entry
						 SET status = :status, comment = :comment, date_update = NOW(), user_id = :user_id
						 WHERE stock_id = :id');

	$req->bindParam(':status', $status, PDO::PARAM_STR);
	$req->bindParam(':comment', $comment, PDO::PARAM_STR);
	$req->bindParam(':user_id', $user_id, PDO::PARAM_INT);
	$req->bindParam(':id', $id, PDO::PARAM_INT);

	$req->execute();

	$req->closeCursor();

	// Historique
	insertLog($location, $location, $reference, $designation, $quantity, $quantity, $status, $comment, 'Changement commentaire/statut', $username);
}
elseif ( isset($_POST['update-stock']) && $_POST['update-stock'] == 0 )
{
	$req = $db->prepare('UPDATE free_entry
						 SET status = :status, comment = :comment, date_update = NOW(), user_id = :user_id
						 WHERE free_id = :id');

	$req->bindParam(':status', $status, PDO::PARAM_STR);
	$req->bindParam(':comment', $comment, PDO::PARAM_STR);
	$req->bindParam(':user_id', $user_id, PDO::PARAM_INT);
	$req->bindParam(':id', $id, PDO::PARAM_INT);

	$req->execute();

	$req->closeCursor();

	// Historique
	insertLog($location, $location, $reference, $designation, $quantity, $quantity, $status, $comment, 'Changement commentaire/statut', $username);
}