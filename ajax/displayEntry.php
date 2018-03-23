<?php

require_once('../config/load.php');

if ( isset($_GET['id']) )
{
	$sql = 'SELECT e.reference AS reference, e.designation AS designation, e.quantity AS quantity, e.status AS status, e.comment AS comment, DATE_FORMAT(e.date_update, "%d/%m/%Y %H:%i:%s") AS date_update, l.num_location AS location
			FROM entry AS e
			INNER JOIN location AS l
			ON e.location_id = l.id
			WHERE e.id = ?';
	
	// else
	// {
	// 	$sql = 'SELECT f.reference AS reference, f.designation AS designation, f.quantity AS quantity, f.status AS status, f.comment AS comment, DATE_FORMAT(f.date_update, "%d/%m/%Y %H:%i:%s") AS date_update, l.num_location AS location
	// 		    FROM free_entry AS f
	// 		    INNER JOIN location AS l
	// 		    ON f.location_id = l.id
	// 		    WHERE f.free_id = ?';
	// }

	$req = $db->prepare($sql);

	$req->execute(array($_GET['id']));
	
	$result = $req->fetch();

	$req->closeCursor();

	echo json_encode($result);
}
