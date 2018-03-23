<?php
session_start();

require('config/load.php');
require('config/session.php');
require('includes/locations.inc.php');
require('includes/header.php');

?>
		
		<!-- Formulaire de sélection d'emplacement -->
		<section id="input-loc-area">
			<div class="loader"></div>
			<div class="container" id="list-locations">
				<h1>Sélectionner</h1>
				<form method="post" class="well" id="list-loc-form" action="">
					<div class="row">
						<div class="form-group" id="loc-radio">
							<div class="radio-inline">
								<label for="all-loc"><input type="radio" id="all-loc" name="loc-by" value="les emplacements occupés" checked="checked" />Les emplacements occupés</label>
							</div>
							<div class="radio-inline">
								<label for="empty-loc"><input type="radio" id="empty-loc" name="loc-by" value="les emplacements vides" />Les emplacements vides</label>
							</div>
							<div class="radio-inline">
								<label for="block-loc"><input type="radio" id="block-loc" name="loc-by" value="les emplacements bloqués" />Les emplacements bloqués</label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<button type="submit" class="btn btn-warning" id="list-button"><span class="glyphicon glyphicon-eye-open"></span> Voir</button>
						</div>
					</div>
				</form>
			</div>
		</section>

		<!-- Liste des emplacements sélectionnés -->
		<section id="location-area">
			<div class="container">	
				<button id="print-button" class="btn btn-default btn-sm pull-right"><span class="glyphicon glyphicon-print"></span> Imprimer le tableau</button>
				<h1>Visualiser <?php if(isset($_POST['loc-by'])) { echo "<span class='loc-search'>" . $_POST['loc-by']; } ?></span></h1>
				<table class="table table-hover nowrap" id="table-list-loc" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Emplacement</th>
							<th>Référence</th>
							<th>Désignation</th>
							<th>Quantité</th>
							<!-- <th>Date entrée</th> -->
							<th>Statut</th>
							<th>Suivi stock</th>
							<th>Observations</th>
							<!-- <th>Dernière modification</th> -->
							<!-- <th>Dernier utilisateur</th> -->
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ( $list_loc_taken as $list )
							{
								echo '<tr>';
								echo	'<td>' . $list['num_location'] . '</td>';
								echo	'<td>' . $list['reference'] . '</td>';
								echo	'<td>' . $list['designation'] . '</td>';
								echo	'<td>' . $list['quantity'] . '</td>';
								// echo	'<td>' . $list['date_entry'] . '</td>';
								echo	'<td>' . $list['status'] . '</td>';
								echo	'<td>' . isProductTracked($list['stock']) . '</td>';
								echo	'<td>' . $list['comment'] . '</td>';
								echo '</tr>';
								// echo	'<td>' . $list['date_update'] . '</td>';
								// echo	'<td>' . getUsernameById($list['user_id']) . '</td>
							}
							foreach ( $list_loc_empty_locked as $list )
							{
								echo '<tr>';
								echo	'<td>' . $list['num_location'] . '</td>';
								echo	'<td></td>';
								echo	'<td></td>';
								echo	'<td></td>';
								// echo	'<td>' . $list['date_entry'] . '</td>';
								echo	'<td></td>';
								echo	'<td></td>';
								echo	'<td></td>';
								echo '</tr>';
							}
						?>
					</tbody>
				</table>
			</div>
		</section>

		<?php require('includes/footer.php'); ?>


		