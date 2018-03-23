<?php
session_start();

require('config/load.php');
require('config/session.php');
require('includes/history.inc.php');
require('includes/header.php');

?>
		
		<!-- Formulaire de sélection d'emplacement -->
		<section id="input-history-area">
			<div class="loader"></div>
			<div class="container" id="history-product">
				<h1>Historique de produit</h1>
				<form method="post" class="well" id="history-form" action="">
					<div class="row">
						<div class="form-group col-md-2" id="search-ref">
							<label for="history-ref" class="control-label">Référence</label>
							<input type="text" class="form-control input-sm" id="history-ref" name="history-ref" placeholder="référence" />
      						<span class="help-block" id="help-hist-ref"></span>
						</div>
						<div class="form-group col-md-6" id="search-des">
							<label for="history-des" class="control-label">Désignation</label>
							<input type="text" class="form-control input-sm" id="history-des" name="history-des" placeholder="désignation" />
      						<span class="help-block" id="help-hist-des"></span>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-2" id="hist-date-one">
							<label for="date" class="control-label">Le</label>
							 <div class="input-group">
						        <div class="input-group-addon">
						         	<span class="glyphicon glyphicon-calendar"></span>
						        </div>
						        <input class="form-control input-sm" id="date-one" name="date-one" placeholder="JJ/MM/YYYY" type="text" />
   							</div>
   							<span class="help-block" id="help-hist-date-one"></span>
						</div>
						<div class="form-group col-md-2 col-md-offset-2" id="hist-date-first">
							<label for="date" class="control-label">De</label>
							 <div class="input-group">
						        <div class="input-group-addon">
						         	<span class="glyphicon glyphicon-calendar"></span>
						        </div>
						        <input class="form-control input-sm" id="date-first" name="date-first" placeholder="JJ/MM/YYYY" type="text" />
   							</div>
   							<span class="help-block" id="help-hist-date"></span>
						</div>
						<div class="form-group col-md-2" id="hist-date-last">
							<label for="date" class="control-label">À</label>
							 <div class="input-group">
						        <div class="input-group-addon">
						         	<span class="glyphicon glyphicon-calendar"></span>
						        </div>
						        <input class="form-control input-sm" id="date-last" name="date-last" placeholder="JJ/MM/YYYY" type="text" />
   							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<button type="submit" class="btn btn-warning" id="hist-button"><span class="glyphicon glyphicon-search"></span> Chercher</button>
						</div>
					</div>
				</form>
			</div>
		</section>

		<!-- Liste des emplacements sélectionnés -->
		<section id="history-area">
			<div class="container">	
				<button id="print-hist-button" class="btn btn-default btn-sm pull-right"><span class="glyphicon glyphicon-print"></span> Imprimer l'historique</button>
				<h1>Historique de <?php echo $result_title = isset($result_title) ? $result_title : ''; ?></span></h1>
				<h2><?php echo $result_date = isset($result_date) ? $result_date : ''; ?></h2>
				<table class="table table-hover nowrap" id="table-history" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Date</th>
							<th>Emplacement</th>
							<th>Quantité</th>
							<th>Observations</th>
							<th>Statut</th>
							<th>Action</th>
							<th>Date action</th>
							<!-- <th>Suivi stock</th> -->
							<!-- <th>Dernière modification</th> -->
							<th>Dernier utilisateur</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ( $array as $list )
							{
								echo '<tr>';
								echo	'<td>' . $list['date_action'] . '</td>';
								echo	'<td>' . $list['new_loc'] . '</td>';
								echo	'<td>' . $list['new_qty'] . '</td>';
								echo	'<td>' . $list['comment'] . '</td>';
								echo	'<td>' . $list['status'] . '</td>';
								echo	'<td>' . $list['action'] . '</td>';
								echo	'<td>' . $list['date_action'] . '</td>';
								// echo	'<td>' . $list['status'] . '</td>';
								echo	'<td>' . $list['user'] . '</td>';
								echo '</tr>';
								// echo	'<td>' . $list['date_update'] . '</td>';
								// echo	'<td>' . getUsernameById($list['user_id']) . '</td>
							}
						?>
					</tbody>
				</table>
			</div>
		</section>

		<?php require('includes/footer.php'); ?>


		