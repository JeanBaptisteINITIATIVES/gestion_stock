<?php
$_SESSION = array();

// Initialisation des messages d'erreurs
$userAliasError = $passwordError = "none";


if( $_SERVER["REQUEST_METHOD"] == "POST" )
{
	// Initialisation des variables
	$user_alias      = "";
	$user_password   = false;
	$userIsLoggedIn  = true;
	
	if ( empty($_POST['user-alias']) || !checkIfAliasExist($_POST['user-alias']) )
	{
	    $userIsLoggedIn = false;
	    $userAliasError = 'block';
	}
	else
	{
	    $user_alias    = checkInput($_POST['user-alias']);
	    $user_password = getUserPassword($_POST['user-alias']);
	}

	if ( empty($_POST['password']) || $user_password != $_POST['password'] )
	{
    	$userIsLoggedIn = false;
    	$passwordError = 'block';
	}

    if ( $userIsLoggedIn === true )
    {
    	session_start();
		$_SESSION['user-alias'] = $user_alias;

		// if ( isset($_POST['sync']) )
		// {
		// 	$req_api = 'http://sccoreapi/v1/product/?filter={"is_stock_managed":-1}&rows=99999';
			
		// 	getAPI($req_api);
		// }
		
		if ( !checkIfUserExist($user_alias) ) // L'utilisateur n'existe pas
		{
			// Ecriture infos connexion en bdd
		    insertNewUser($user_alias);
		    
		    // Redirection
		    header('Location: productEntry.php');
		    exit();
		}
		else
		{
			// Mise à jour infos de l'utilisateur
			updateUser($user_alias);
			
			// Redirection
		    header('Location: productEntry.php');
		    exit();
    	}
    }    
}