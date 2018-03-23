<?php

require_once('../config/load.php');

$term = $_GET['term'];

$req = $db->prepare('SELECT *
					 FROM location
					 WHERE num_location LIKE :term
					 LIMIT 0, 20');

$req->execute(array('term' => $term . '%'));

$location = array();

while( $results = $req->fetch() )
{
    array_push($location, $results['num_location']);
}

$req->closeCursor();

echo json_encode($location);

?>