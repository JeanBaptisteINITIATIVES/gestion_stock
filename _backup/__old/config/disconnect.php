<?php
session_start();

require('load.php');

disconnectUser($_SESSION['user-alias']);

unset($_SESSION);
session_destroy();

Database::disconnect();

header('Location: ../index.php');
exit();