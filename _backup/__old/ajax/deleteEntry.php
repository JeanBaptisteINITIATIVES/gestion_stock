<?php
session_start();
$_SESSION['user_alias'] = $user_alias;

require_once('../config/load.php');

$username = getUsernameByAlias($user_alias);

// Récupération des données du formulaire du modal
$id    = $_POST['delete-id'];
$stock = $_POST['delete-stock'];

// On récupère les données du produit
$location     = checkInput($_POST['delete-loc']);
$reference    = checkInput($_POST['delete-ref']);
$designation  = checkInput($_POST['delete-des']);
$quantity     = checkInput($_POST['delete-qty']);
$status       = checkInput($_POST['delete-sts']);
$comment      = checkInput($_POST['delete-comment']);

if ( $stock == -1 )
{
	$req = $db->prepare('DELETE FROM stock_entry WHERE stock_id = :id');

	$req->bindParam(':id', $id, PDO::PARAM_INT);

	$req->execute();

	$req->closeCursor();
}
else
{
	$req = $db->prepare('DELETE FROM free_entry WHERE free_id = :id');

	$req->bindParam(':id', $id, PDO::PARAM_INT);

	$req->execute();

	$req->closeCursor();
}

// Historique
insertLog($location, $location, $reference, $designation, $quantity, 0, $status, $comment, 'Sortie de stock', $username);
