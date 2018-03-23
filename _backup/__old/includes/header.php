<?php

// On recupère l'URL de la page pour ensuite affecter la classe "active" aux liens de nav
$page = $_SERVER['REQUEST_URI'];

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>Gestion des stocks</title>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Asap:500" /> 
		<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
		<link rel="stylesheet" type="text/css" href="libs/css/bootstrap_datepicker/datepicker_1.7.1.min.css" />
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/dt-1.10.16/fh-3.1.3/r-2.2.0/datatables.min.css" />
		<link rel="stylesheet" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css" />
		<link rel="stylesheet" type="text/css" href="libs/css/styles.css" />
	</head>

	<body>
		<header>
			<nav class="navbar navbar-default" id="nav-header" role="navigation">
				<div class="container">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-inventory" aria-expanded="false">
					        <span class="icon-bar"></span>
					        <span class="icon-bar"></span>
					        <span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="http://www.initiatives.fr" target="_blank">
							<img src="../assets/img/logo_init.png" id="logo-init" alt="Inventaire Initiatives">
						</a>
					</div>
					<div class="collapse navbar-collapse" id="navbar-inventory">
			  			<ul class="nav navbar-nav" id="menu">
							<li <?php if($page == "/productEntry.php") { echo 'class="active"'; } ?>><a href="productEntry.php">Mouvements de stock</a></li>
							<li <?php if($page == "/locations.php") { echo 'class="active"'; } ?>><a href="locations.php">Emplacements</a></li>
							<li <?php if($page == "/history.php") { echo 'class="active"'; } ?>><a href="history.php">Historique</a></li>
			  			</ul>
			  			<ul class="nav navbar-nav navbar-right">
							<li class="infos-log">
								<p>Connecté : <span class="infos-coul"><?php echo getUsernameByAlias($_SESSION['user-alias']); ?></span></p>
							</li>
							<li class="vertical-divider"></li>
							<li class="infos-log">
								<a id="disconnect-link" href="../config/disconnect.php" class="pull-right">Déconnexion</a>
							</li>
			  			</ul>			
					</div>
				</div>		        
			</nav>
		</header>