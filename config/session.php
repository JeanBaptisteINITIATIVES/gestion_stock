<?php

// if ( !isset($_SESSION['user-alias']) )
// {
// 	header('Location: index.php');
// 	exit();
// }

// if ( isset($_SESSION['timestamp']) )
// {
//     if( $_SESSION['timestamp'] + 360000 > time() )
//     {    
//         $_SESSION['timestamp'] = time();
//         updateLastActivity($_SESSION['user-alias']);
//     }
//     else
//     {
//     	header('Location: config/disconnect.php');
//     	exit();
//     }
// }
// else { $_SESSION['timestamp'] = getLastActivity($_SESSION['user-alias']); }