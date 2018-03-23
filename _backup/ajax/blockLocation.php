<?php
session_start();
$user_alias = $_SESSION['user-alias'];

require_once('../config/load.php');

$username     = getUsernameByAlias($user_alias);
$location     = checkInput($_POST['block']);
$location_id  = getLocationId($location);
$locked       = checkIfLocationLocked($location_id);

blockLocation($location_id, $locked);

// Historique
if ( $locked == 1 )
{
	insertLog($location, $location, '', '', 0, 0, '', '', 'Déblocage emplacement', $username);
}
elseif ( $locked == 0 )
{
	insertLog($location, $location, '', '', 0, 0, '', '', 'Blocage emplacement', $username);
}