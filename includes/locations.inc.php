<?php

$list_loc_taken        = array();
$list_loc_empty_locked = array();

if ( isset($_POST['loc-by']) && $_POST['loc-by'] != NULL )
{
	if ( $_POST['loc-by'] == 'les emplacements occupés' )
	{
		$req = 'SELECT e.reference AS reference, e.designation AS designation, e.quantity AS quantity, e.status AS status, e.comment AS comment, DATE_FORMAT(e.date_entry, "%d/%m/%Y %H:%i:%s") AS date_entry, DATE_FORMAT(e.date_update, "%d/%m/%Y %H:%i:%s") AS date_update, e.user_id AS user_id, e.stock AS stock, l.num_location AS num_location, u.id AS user_id
		        FROM entry AS e
		        INNER JOIN location AS l
		        ON e.location_id = l.id
		        INNER JOIN users AS u
		        ON e.user_id = u.id';

		$stt = $db->prepare($req);

		$stt->execute();
	
		while ( $row = $stt->fetch() )
		{
			$list_loc_taken[] = $row;
		}

		$stt->closeCursor();
	}
	elseif ( $_POST['loc-by'] == 'les emplacements vides' )
	{
		$req = 'SELECT l.id, l.num_location AS num_location
				FROM location AS l
				LEFT JOIN entry AS e
				ON e.location_id = l.id
				WHERE e.location_id IS NULL';
		
		$stt = $db->prepare($req);

		$stt->execute();
	
		while ( $row = $stt->fetch() )
		{
			$list_loc_empty_locked[] = $row;
		}

		$stt->closeCursor();
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
