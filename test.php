<?php
require_once('config/load.php');

$location = '2A12';
$location_id  = getLocationId($location);
$designation = 'Plaquette Initiatives Fleurs & Nature Printemps 18';
// $test = checkIfLocationEmpty($location_id);
$test = json_decode(file_get_contents('http://sccoreapi/v1/product/?filter={"is_stock_managed":-1,"name__exact":"' . urlencode(addslashes($designation)) . '"}&rows=99999'));


var_dump(count($test));