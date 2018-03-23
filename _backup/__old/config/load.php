<?php

// -----------------------------------------------------------------------
// DEFINIT LES ALIAS DE SEPARATEURS
// -----------------------------------------------------------------------
define('URL_SEPARATOR', '/');
define('DS', DIRECTORY_SEPARATOR);

// -----------------------------------------------------------------------
// DEFINIT LES CHEMINS DES DOSSIERS
// -----------------------------------------------------------------------
defined('SITE_ROOT') ? null : define('SITE_ROOT', realpath(dirname(__FILE__)));
define('LIB_PATH_INC', SITE_ROOT . DS);

require(LIB_PATH_INC . 'functions.php');
require(LIB_PATH_INC . 'database.php');
require(LIB_PATH_INC . 'sql.php');