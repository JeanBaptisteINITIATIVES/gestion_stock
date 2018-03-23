<?php
session_start();
$user_alias = $_SESSION['user-alias'];

require_once('../config/load.php');

$username = getUsernameByAlias($user_alias);
$user_id  = getUserId($user_alias);

// Récupération de l'id du produit
$id = $_POST['transfer-id'];

// On récupère les données du produit
$old_location = checkInput($_POST['transfer-loc']);
$new_location = checkInput($_POST['new-transfer-loc']);
$reference    = trim($_POST['transfer-ref']);
$designation  = trim($_POST['transfer-des']);
$quantity     = checkInput($_POST['transfer-qty']);
$status       = checkInput($_POST['transfer-sts']);
$comment      = trim($_POST['transfer-comment']);

$location_id  = getLocationId($new_location);

$req = $db->prepare('UPDATE entry
					 SET location_id = :location_id, date_update = NOW(), user_id = :user_id
					 WHERE id = :id');

$req->bindParam(':location_id', $location_id, PDO::PARAM_INT);
$req->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$req->bindParam(':id', $id, PDO::PARAM_INT);

$req->execute();

$req->closeCursor();

// Historique
insertLog($old_location, $new_location, $reference, $designation, $quantity, $quantity, $status, $comment, 'Transfert d\'emplacement', $username);