<?php
session_start();
$user_alias = $_SESSION['user-alias'];

require_once('../config/load.php');

$username = getUsernameByAlias($user_alias);

// Récupération des données du formulaire du modal
$id = $_POST['delete-id'];

// On récupère les données du produit
$location     = checkInput($_POST['delete-loc']);
$reference    = trim($_POST['delete-ref']);
$designation  = trim($_POST['delete-des']);
$quantity     = checkInput($_POST['delete-qty']);
$status       = checkInput($_POST['delete-sts']);
$comment      = trim($_POST['delete-comment']);
$new_qty      = 0;

$req = $db->prepare('DELETE FROM entry WHERE id = :id');

$req->bindParam(':id', $id, PDO::PARAM_INT);

$req->execute();

$req->closeCursor();

// Historique
insertLog($location, $location, $reference, $designation, $quantity, $new_qty, $status, $comment, 'Sortie de stock', $username);