<?php

$list_loc_taken        = array();
$list_loc_empty_locked = array();

if ( isset($_POST['loc-by']) && $_POST['loc-by'] != NULL )
{
	if ( $_POST['loc-by'] == 'les emplacements occupés' )
	{
		$req1 = 'SELECT s.quantity AS quantity, s.status AS status, s.comment AS comment, DATE_FORMAT(s.date_entry, "%d/%m/%Y %H:%i:%s") AS date_entry, DATE_FORMAT(s.date_update, "%d/%m/%Y %H:%i:%s") AS date_update, s.user_id AS user_id, l.num_location AS num_location, p.reference AS reference, p.designation AS designation, p.stock AS stock, u.id AS user_id
		         FROM stock_entry AS s
		         INNER JOIN location AS l
		         ON s.location_id = l.id
		         INNER JOIN products AS p
		         ON s.product_id = p.id
		         INNER JOIN users AS u
		         ON s.user_id = u.id';

		$req2 = 'SELECT f.reference AS reference, f.designation AS designation, f.quantity AS quantity, f.status AS status, f.comment AS comment, DATE_FORMAT(f.date_entry, "%d/%m/%Y %H:%i:%s") AS date_entry, DATE_FORMAT(f.date_update, "%d/%m/%Y %H:%i:%s") AS date_update, f.user_id AS user_id, f.stock AS stock, l.num_location AS num_location, u.id AS user_id
		         FROM free_entry AS f
		         INNER JOIN location AS l
		         ON f.location_id = l.id
		         INNER JOIN users AS u
		         ON f.user_id = u.id';

		$stt1 = $db->prepare($req1);
		$stt2 = $db->prepare($req2);

		$stt1->execute();
		$stt2->execute();
	
		while ( $row = $stt1->fetch() )
		{
			$list_loc_taken[] = $row;
		}
		while ( $row = $stt2->fetch() )
		{
			$list_loc_taken[] = $row;
		}

		$stt1->closeCursor();
		$stt2->closeCursor();
	}
	elseif ( $_POST['loc-by'] == 'les emplacements vides' )
	{
		$req1 = 'SELECT l.id, l.num_location AS num_location
				 FROM location AS l
				 LEFT JOIN stock_entry AS s
				 ON s.location_id = l.id
				 WHERE s.location_id IS NULL';

		$req2 = 'SELECT l.id, l.num_location AS num_location
				 FROM location AS l
				 LEFT JOIN free_entry AS f
				 ON f.location_id = l.id
				 WHERE f.location_id IS NULL';

		$stt1 = $db->prepare($req1);
		$stt2 = $db->prepare($req2);

		$stt1->execute();
		$stt2->execute();
	
		while ( $row = $stt1->fetch() )
		{
			$list_loc_empty_locked[] = $row;
		}
		while ( $row = $stt2->fetch() )
		{
			$list_loc_empty_locked[] = $row;
		}

		$stt1->closeCursor();
		$stt2->closeCursor();
	}
	elseif ( $_POST['loc-by'] == 'les emplacements bloqués' )
	{
		$req = 'SELECT num_location
				FROM location
				WHERE locked = 1';

		$stt = $db->prepare($req);

		$stt->execute();

		while ( $row = $stt->fetch() )
		{
			$list_loc_empty_locked[] = $row;
		}

		$stt->closeCursor();
	}
	// echo '<pre>', print_r($list_location), '</pre>';
}
