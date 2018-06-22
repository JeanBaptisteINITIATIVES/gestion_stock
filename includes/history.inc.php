<?php

$array = array();

if ( $_SERVER["REQUEST_METHOD"] == "POST" )
{
	$reference = $_POST['history-ref'];
	$designation = $_POST['history-des'];

	$date_one    = !empty($_POST['date-one']) ? $_POST['date-one'] : NULL;
	$date_first  = !empty($_POST['date-first']) ? dateFrtoUS($_POST['date-first']) : NULL;
	$date_last   = !empty($_POST['date-last']) ? dateFrtoUS($_POST['date-last']) : NULL;

	if ( $date_one != NULL )
	{
		$req = $db->prepare('SELECT old_loc, new_loc, old_qty, new_qty, status, comment, action, date_action, user
							 FROM logs
							 WHERE reference = :reference
							 AND designation = :designation
							 AND DATE_FORMAT(date_action, "%d/%m/%Y") = :date_action');

		$req->bindParam(':reference', $reference, PDO::PARAM_STR);
		$req->bindParam(':designation', $designation, PDO::PARAM_STR);
		$req->bindParam(':date_action', $date_one, PDO::PARAM_STR);

		$req->execute();

		while ( $row = $req->fetch() )
		{
			$array[] = $row;
		}

		$req->closeCursor();
	}
	elseif ( $date_first != NULL && $date_last != NULL )
	{
		$req = $db->prepare('SELECT old_loc, new_loc, old_qty, new_qty, status, comment, action, date_action, user
							 FROM logs
							 WHERE reference = :reference
							 AND designation = :designation
							 AND date_action
							 BETWEEN :date_first AND :date_last');

		$req->bindParam(':reference', $reference, PDO::PARAM_STR);
		$req->bindParam(':designation', $designation, PDO::PARAM_STR);
		$req->bindParam(':date_first', $date_first, PDO::PARAM_STR);
		$req->bindParam(':date_last', $date_last, PDO::PARAM_STR);

		$req->execute();

		while ( $row = $req->fetch() )
		{
			$array[] = $row;
		}

		$req->closeCursor();
	}
	else
	{
		$req = $db->prepare('SELECT old_loc, new_loc, old_qty, new_qty, status, comment, action, date_action, user
							 FROM logs
							 WHERE reference = :reference
							 AND designation = :designation');

		$req->bindParam(':reference', $reference, PDO::PARAM_STR);
		$req->bindParam(':designation', $designation, PDO::PARAM_STR);

		$req->execute();

		while ( $row = $req->fetch() )
		{
			$array[] = $row;
		}

		$req->closeCursor();
	}

	// Infos titre tableau
	if( !empty($_POST['history-ref']) && !empty($_POST['history-des']) )
	{
		$result_title = "<span class='hist-ref'>" . $_POST['history-ref'] . "<span class='hist-des'> " . $_POST['history-des'];
	}
	if ( !empty($_POST['date-one']) )
	{
		$result_date = "Le <span class='date-history'>" . $_POST['date-one'] . "</span>";
	}
	elseif ( !empty($_POST['date-first']) && !empty($_POST['date-last']) )
	{
		$result_date = "Entre le <span class='date-history'>" . $_POST['date-first'] . "</span> et le <span class='date-history'>" . $_POST['date-last'] . "</span>";
	}
}

updateLastActivity($_SESSION['user-alias']);


