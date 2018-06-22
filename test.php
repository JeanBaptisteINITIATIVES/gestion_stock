<?php
require_once('config/load.php');

$location = '2A12';
$location_id  = getLocationId($location);
$designation = "Porte-clés Attrape' Rêves";
$des2 = 'Tirelire Dons "autocollants à coller par vos soins + stickers offerts"';
// $des3 = addslashes($des2);
// $des = addslashes($designaton);
// $desi = htmlspecialchars($designation, ENT_NOQUOTES);
// $desi = str_replace("\"", "'", $designation);
$test2 = urlencode(json_string_encode($designation));
$test3 = urlencode(json_string_encode($des2));

$req_url_des = json_decode(file_get_contents('http://sccoreapi/v1/product/?filter="' . urlencode(json_encode(array("is_stock_managed" => -1, "name__exact" => $des2))) . '"}&rows=99999'));

// $json = file_get_contents('http://sccoreapi/v1/product/?filter={"is_stock_managed":-1,"name__exact":"' . rawurlencode($des2) . '"}&rows=99999');
// $decode = json_decode($json);
// $test = checkIfLocationEmpty($location_id);
$test = json_decode(file_get_contents('http://sccoreapi/v1/product/?filter={"is_stock_managed":-1,"name__exact":"' . rawurlencode(json_string_encode($designation)) . '"}&rows=99999'));

// echo addslashes($des2);
// echo addslashes($designation);
// var_dump($test3);
// var_dump($test2);
var_dump($test);

// var_dump($decode);
// var_dump(count($test));
// var_dump($req_url_des);
// var_dump($des3);
// var_dump(count($req_url_des));