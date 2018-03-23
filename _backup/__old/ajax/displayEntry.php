<?php

require_once('../config/load.php');

if ( isset($_GET['id']) && isset($_GET['stock']) )
{
	if ( $_GET['stock'] == -1 )
	{
		$sql = 'SELECT s.quantity AS quantity, s.status AS status, s.comment AS comment, DATE_FORMAT(s.date_update, "%d/%m/%Y %H:%i:%s") AS date_update, l.num_location AS location, p.reference AS reference, p.designation AS designation
			    FROM stock_entry AS s
			    INNER JOIN location AS l
			    ON s.location_id = l.id
			    INNER JOIN products AS p
			    ON s.product_id = p.id
			    WHERE s.stock_id = ?';
	}
	else
	{
		$sql = 'SELECT f.reference AS reference, f.designation AS designation, f.quantity AS quantity, f.status AS status, f.comment AS comment, DATE_FORMAT(f.date_update, "%d/%m/%Y %H:%i:%s") AS date_update, l.num_location AS location
			    FROM free_entry AS f
			    INNER JOIN location AS l
			    ON f.location_id = l.id
			    WHERE f.free_id = ?';
	}
	

	$req = $db->prepare($sql);

	$req->execute(array($_GET['id']));
	
	$result = $req->fetch();

	$req->closeCursor();

	echo json_encode($result);
}
