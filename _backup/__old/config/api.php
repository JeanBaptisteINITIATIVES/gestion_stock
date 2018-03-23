<?php
require_once('load.php');

if ( $_SERVER["REQUEST_METHOD"] == "POST" )
{
	$_POST['refresh'] = checkInput($_POST['refresh']);

	if ( isset($_POST['refresh']) && $_POST['refresh'] == 'stock' )
	{
		$req_api = 'http://sccoreapi/v1/product/?filter={"is_stock_managed":-1}&rows=99999';
	}
	elseif ( isset($_POST['refresh']) && $_POST['refresh'] == 'non-stock' )
	{
		$req_api = 'http://sccoreapi/v1/product/?filter={"is_stock_managed":0}&rows=99999';
	}
	elseif ( isset($_POST['refresh']) && $_POST['refresh'] == 'all' )
	{
		$req_api = 'http://sccoreapi/v1/product/?rows=99999';
	}
}

getAPI($req_api); // on effectue la synchro avec l'API