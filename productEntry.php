<?php
session_start();

require('config/load.php');
require('includes/header.php');

$list_products = getStorageProducts();
updateLastActivity($_SESSION['user-alias']);

?>
		
		<!-- Formulaire de saisie de produits en stock -->
		<section id="input-area">
			<div class="loader"></div>
			<div class="container" id="actions">
				<h1>Actions</h1>
				<button type="button" class="btn btn-success" id="entry-button"><span class="glyphicon glyphicon-plus"></span> Entrée de stock</button>
				<button type="button" class="btn btn-dark" id="loc-button"><span class="glyphicon glyphicon-ban-circle"></span> Bloquer / Débloquer un emplacement</button>
			</div>
			<div class="container" id="product-entry">
				<h2>Entrée produit :</h2>
				<form method="post" class="well" id="add-form" action="">
					<div class="row">
						<div class="form-group col-md-4 col-sm-8" id="stock-radio">
							<div class="radio-inline">
								<label for="storage"><input type="radio" id="storage" name="entry-by" value="tracked" checked="checked" />Suivi en stock</label>
							</div>
							<div class="radio-inline">
								<label for="free"><input type="radio" id="free" name="entry-by" value="non-tracked" />Non-suivi en stock</label>
							</div>
						</div>
						<div class="checkbox col-md-3 col-sm-3" id="check-loc">
							<label><input type="checkbox" id="mem-loc" name="mem-loc"> Mémoriser l'emplacement</label>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-2 col-sm-2" id="add-loc">
							<label for="location" class="control-label">Emplacement</label>
							<input type="text" class="form-control input-sm" id="location" name="location" placeholder="emplacement" />
      						<span class="help-block" id="help-loc"></span>
						</div>
						<div class="form-group col-md-2 col-sm-2" id="add-ref">
							<label for="reference" class="control-label">Référence</label>
							<input type="text" class="form-control input-sm" id="reference" name="reference" placeholder="référence" />
      						<span class="help-block" id="help-ref"></span>
						</div>
						<div class="form-group col-md-8 col-sm-8" id="add-des">
							<label for="designation" class="control-label">Désignation</label>
							<input type="text" class="form-control input-sm" id="designation" name="designation" placeholder="désignation" />
      						<span class="help-block" id="help-des"></span>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-2 col-sm-2" id="add-qty">
							<label for="quantity" class="control-label">Quantité</label>
							<input type="text" class="form-control input-sm" id="quantity" name="quantity" placeholder="quantité" />
      						<span class="help-block" id="help-qty"></span>
						</div>
						<div class="form-group col-md-2 col-sm-2">
							<label for="statut" class="control-label">Statut</label>
							<select class="form-control input-sm" id="status" name="status">
								<optgroup label="Statut">
									<option selected>A</option>
									<option>Q</option>
									<option>R</option>
								</optgroup>
							</select>
						</div>
						<div class="form-group col-md-8 col-sm-8">
							<label for="comment" class="control-label">Commentaire</label>
							<input type="text" class="form-control input-sm" id="comment" name="comment" placeholder="commentaire" />
						</div>
						<button type="submit" class="btn btn-warning btn-sm pull-right" id="add-button"><span class="glyphicon glyphicon-ok"></span> Ajouter</button>
					</div>
					<p id="input-help-block" class="form-text text-muted"></p>
				</form>
			</div>
			<div class="container" id="block-location">
				<h2>Blocage d'emplacement :</h2>
				<form method="post" class="well" id="block-form" action="">
					<div class="row">
						<div class="form-group col-md-3 col-sm-3" id="block-input">
							<label for="block" class="control-label">Entrer un emplacement</label>
							<input type="text" class="form-control input-sm" id="block" name="block" placeholder="emplacement" />
      						<span class="help-block" id="help-block-loc"></span>
						</div>
						<div class="form-group">
							<button type="button" class="btn btn-dark btn-sm" id="block-button"><span class="glyphicon glyphicon-ban-circle"></span> Bloquer</button>
							<button type="button" class="btn btn-default btn-sm" id="unlock-button"><span class="glyphicon glyphicon-ok-circle"></span> Débloquer</button>
						</div>
					</div>
				</form>
			</div>
			<!-- <div class="container" id="refresh-products">
				<h2>Mise à jour produits Scorre :</h2>
				<form method="post" class="well" id="refresh-form" action="">
					<div class="row">
						<div class="form-group col-md-3" id="refresh-input">
							<label for="refresh" class="control-label">Mise à jour pour :</label>
							<select class="form-control" id="refresh" name="refresh" required>
								<option value="stock" selected>Produits suivis en stock</option>
								<option value="non-stock">Produits non-suivis en stock</option>
								<option value="all">Tous les produits</option>
							</select>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-warning btn-sm" id="refresh-button"><span class="glyphicon glyphicon-ok"></span> Valider</button>
						</div>
					</div>
				</form>
			</div> -->

			<!-- Modal de confirmation pour ajout produit sur emplacement déjà utilisé -->
			<div class="modal fade" tabindex="-1" role="dialog" id="modal-confirm">
  				<div class="modal-dialog" role="document">
    				<div class="modal-content">
      					<div class="modal-header">
        					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        					<h4 class="modal-title"></h4>
      					</div>
      					<div class="modal-body">
        					<p id="confirm-add"></p>
        					<p id="confirm-product"></p>
        					<p id="confirm-add"></p>
      					</div>
      					<div class="modal-footer">
					        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Annuler</button>
					        <button type="button" class="btn btn-success" id="btn-modal-ok"><span class="glyphicon glyphicon-ok"></span> Gerber</button>
      					</div>
    				</div>
  				</div>
			</div>
		</section>
		
		<!-- Liste des produits saisis du formulaire -->
		<section id="products-area">
			<div class="container">	
				<h1>Produits enlogés</h1>
				<table class="table table-striped nowrap" id="table-entry" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Date</th>
							<th>Emplacement</th>
							<th>Référence</th>
							<th id="table-des">Désignation</th>
							<th>Quantité</th>
							<th>Date entrée</th>
							<th>Suivi stock</th>
							<th>Actions</th>
							<th>Statut</th>
							<th>Commentaire :</th>
							<th>Dernière modification :</th>
							<th>Dernier utilisateur :</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ( $list_products as $list )
							{
								$index = empty($list['comment']) ? "" : '<span class="isComment">* </span>';
								
								echo '<tr>';
								echo	'<td>' . $list['date_update'] . '</td>';
								echo	'<td>' . $list['num_location'] . '</td>';
								echo	'<td>' . $list['reference'] . '</td>';
								echo	'<td>' . $index . $list['designation'] . '</td>';
								echo	'<td>' . $list['quantity'] . '</td>';
								echo	'<td>' . $list['date_entry'] . '</td>';
								echo	'<td>' . isProductTracked($list['stock']) . '</td>';
								echo	'<td>' . isLocationLocked($list['locked'], $list) . '</td>';
								echo	'<td>' . $list['status'] . '</td>';
								echo	'<td>' . $list['comment'] . '</td>';
								echo	'<td>' . $list['date_update'] . '</td>';
								echo	'<td>' . getUsernameById($list['user_id']) . '</td>
									</tr>';
							}
						?>
						
						<!-- Modal d'ajustement positif d'une entrée -->
						<div class="modal fade" tabindex="-1" role="dialog" id="modal-plus">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times</span></button>
										<h2>Ajustement positif</h2>
									</div>
									<div class="modal-body">
										<form method="post" action="" id="plus-form">
											<div class="row">
												<div class="form-group col-md-2">
													<label for="plus-loc" class="control-label">Emplacement</label>
													<input type="text" name="plus-loc" id="plus-loc" class="form-control" readonly />
												</div>
												<div class="form-group col-md-2">
													<label for="plus-ref" class="control-label">Référence</label>
													<input type="text" name="plus-ref" id="plus-ref" class="form-control" readonly />
													</div>
												<div class="form-group col-md-8">
													<label for="plus-des" class="control-label">Désignation</label>
													<input type="text" name="plus-des" id="plus-des" class="form-control" readonly />
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-2">
													<label for="plus-qty" class="control-label">Quantité</label>
													<input type="text" name="plus-qty" id="plus-qty" class="form-control" readonly />
												</div>
												<div class="form-group col-md-3" id="new-input-plus-qty">
												<label for="new-plus-qty" class="control-label">Ajout de</label>
													<div class="input-group">
														<input type="text" name="new-plus-qty" id="new-plus-qty" class="form-control" aria-describedby="plus-addon" />
														<span class="input-group-addon" id="plus-addon">pièces</span>
													</div>
														<span class="help-block" id="help-plus-qty"></span>
												</div>
												<div class="form-group col-md-2">
													<label for="plus-sts" class="control-label">Statut</label>
													<select class="form-control" id="plus-sts" name="plus-sts" readonly />
														<optgroup label="Statut">
															<option>A</option>
															<option>Q</option>
															<option>R</option>
															</optgroup>
													</select>
												</div>
												<div class="form-group col-md-3">
													<label for="plus-date" class="control-label">Dernière modification</label>
													<input type="text" name="plus-date" id="plus-date" class="form-control" readonly />
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-7">
													<label for="plus-comment" class="control-label">Commentaire</label>
													<input type="text" class="form-control" id="plus-comment" name="plus-comment" />
												</div>
											</div>
											<input type="hidden" id="plus-id" name="plus-id" />
										</form>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Annuler</button>
										<button type="button" id="plus-button" class="btn btn-success success"><span class="glyphicon glyphicon-ok"></span> Valider</button>
									</div>
								</div>
							</div>
						</div>

						<!-- Modal d'ajustement négatif d'une entrée -->
						<div class="modal fade" tabindex="-1" role="dialog" id="modal-minus">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times</span></button>
										<h2>Ajustement négatif</h2>
									</div>
									<div class="modal-body">
										<form method="post" action="" id="minus-form">
											<div class="row">
												<div class="form-group col-md-2">
													<label for="minus-loc" class="control-label">Emplacement</label>
													<input type="text" name="minus-loc" id="minus-loc" class="form-control" readonly />
												</div>
												<div class="form-group col-md-2">
													<label for="minus-ref" class="control-label">Référence</label>
													<input type="text" name="minus-ref" id="minus-ref" class="form-control" readonly />
													</div>
												<div class="form-group col-md-8">
													<label for="minus-des" class="control-label">Désignation</label>
													<input type="text" name="minus-des" id="minus-des" class="form-control" readonly />
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-2">
													<label for="minus-qty" class="control-label">Quantité</label>
													<input type="text" name="minus-qty" id="minus-qty" class="form-control" readonly />
												</div>
												<div class="form-group col-md-3" id="new-input-minus-qty">
													<label for="new-minus-qty" class="control-label">Retrait de</label>
													<div class="input-group">
														<input type="text" name="new-minus-qty" id="new-minus-qty" class="form-control" aria-describedby="minus-addon" />
														<span class="input-group-addon" id="minus-addon">pièces</span>
													</div>
														<span class="help-block" id="help-minus-qty"></span>
												</div>
												<div class="form-group col-md-2">
													<label for="minus-sts" class="control-label">Statut</label>
													<select class="form-control" id="minus-sts" name="minus-sts" readonly />
														<optgroup label="Statut">
															<option>A</option>
															<option>Q</option>
															<option>R</option>
															</optgroup>
													</select>
												</div>
												<div class="form-group col-md-3">
													<label for="minus-date" class="control-label">Dernière modification</label>
													<input type="text" name="minus-date" id="minus-date" class="form-control" readonly />
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-7">
													<label for="minus-comment" class="control-label">Commentaire</label>
													<input type="text" class="form-control" id="minus-comment" name="minus-comment" />
												</div>
											</div>
											<input type="hidden" id="minus-id" name="minus-id" />
										</form>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Annuler</button>
										<button type="button" id="minus-button" class="btn btn-success success"><span class="glyphicon glyphicon-ok"></span> Valider</button>
									</div>
								</div>
							</div>
						</div>
						
						<!-- Modal de sortie stock d'une entrée -->
						<div class="modal fade" tabindex="-1" role="dialog" id="modal-delete">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times</span></button>
										<h2>Sortie de stock</h2>
									</div>
									<div class="modal-body">
										<form method="post" action="" id="delete-form">
											<div class="row">
												<div class="form-group col-md-2">
													<label for="delete-loc" class="control-label">Emplacement</label>
													<input type="text" name="delete-loc" id="delete-loc" class="form-control" readonly />
												</div>
												<div class="form-group col-md-2">
													<label for="delete-ref" class="control-label">Référence</label>
													<input type="text" name="delete-ref" id="delete-ref" class="form-control" readonly />
													</div>
												<div class="form-group col-md-8">
													<label for="delete-des" class="control-label">Désignation</label>
													<input type="text" name="delete-des" id="delete-des" class="form-control" readonly />
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-2">
													<label for="delete-qty" class="control-label">Quantité</label>
													<input type="text" name="delete-qty" id="delete-qty" class="form-control" readonly />
												</div>
												<div class="form-group col-md-2">
													<label for="delete-sts" class="control-label">Statut</label>
													<select class="form-control" id="delete-sts" name="delete-sts" readonly />
														<optgroup label="Statut">
															<option>A</option>
															<option>Q</option>
															<option>R</option>
															</optgroup>
													</select>
												</div>
												<div class="form-group col-md-3">
													<label for="delete-date" class="control-label">Dernière modification</label>
													<input type="text" name="delete-date" id="delete-date" class="form-control" readonly />
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-7">
													<label for="delete-comment" class="control-label">Commentaire</label>
													<input type="text" class="form-control" id="delete-comment" name="delete-comment" />
												</div>
											</div>
											<input type="hidden" id="delete-id" name="delete-id" />
										</form>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Annuler</button>
										<button type="button" id="delete-button" class="btn btn-success success"><span class="glyphicon glyphicon-ok"></span> Valider</button>
									</div>
								</div>
							</div>
						</div>

						<!-- Modal de transfert d'une entrée -->
						<div class="modal fade" tabindex="-1" role="dialog" id="modal-transfer">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times</span></button>
										<h2>Transfert</h2>
									</div>
									<div class="modal-body">
										<form method="post" action="" id="transfer-form">
											<div class="row">
												<div class="form-group col-md-4">
													<label for="transfer-loc" class="control-label">Emplacement de départ</label>
													<input type="text" name="transfer-loc" id="transfer-loc" class="form-control" readonly />
												</div>
												<div class="form-group col-md-4 col-md-offset-1" id="new-input-transfer-loc">
													<label for="new-transfer-loc" class="control-label">Emplacement de destination</label>
													<input type="text" name="new-transfer-loc" id="new-transfer-loc" class="form-control" />
													<span class="help-block" id="help-transfer-loc"></span>
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-2">
													<label for="transfer-ref" class="control-label">Référence</label>
													<input type="text" name="transfer-ref" id="transfer-ref" class="form-control" readonly />
													</div>
												<div class="form-group col-md-10">
													<label for="transfer-des" class="control-label">Désignation</label>
													<input type="text" name="transfer-des" id="transfer-des" class="form-control" readonly />
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-2">
													<label for="transfer-qty" class="control-label">Quantité</label>
													<input type="text" name="transfer-qty" id="transfer-qty" class="form-control" readonly />
												</div>
												<div class="form-group col-md-2">
													<label for="transfer-sts" class="control-label">Statut</label>
													<select class="form-control" id="transfer-sts" name="transfer-sts" readonly />
														<optgroup label="Statut">
															<option>A</option>
															<option>Q</option>
															<option>R</option>
															</optgroup>
													</select>
												</div>
												<div class="form-group col-md-3">
													<label for="transfer-date" class="control-label">Dernière modification</label>
													<input type="text" name="transfer-date" id="transfer-date" class="form-control" readonly/>
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-7">
													<label for="transfer-comment" class="control-label">Commentaire</label>
													<input type="text" class="form-control" id="transfer-comment" name="transfer-comment" />
												</div>
											</div>
											<input type="hidden" id="transfer-id" name="transfer-id" />
										</form>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Annuler</button>
										<button type="button" id="transfer-button" class="btn btn-success success"><span class="glyphicon glyphicon-ok"></span> Valider</button>
									</div>
								</div>
							</div>
						</div>

						<!-- Modal de changement de commentaire -->
						<div class="modal fade" tabindex="-1" role="dialog" id="modal-update">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times</span></button>
										<h2>Modifications</h2>
									</div>
									<div class="modal-body">
										<form method="post" action="" id="update-form">
											<div class="row">
												<div class="form-group col-md-2">
													<label for="update-loc" class="control-label">Emplacement</label>
													<input type="text" name="update-loc" id="update-loc" class="form-control" readonly />
												</div>
												<div class="form-group col-md-2">
													<label for="update-ref" class="control-label">Référence</label>
													<input type="text" name="update-ref" id="update-ref" class="form-control" readonly />
													</div>
												<div class="form-group col-md-8">
													<label for="update-des" class="control-label">Désignation</label>
													<input type="text" name="update-des" id="update-des" class="form-control" readonly />
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-2">
													<label for="update-qty" class="control-label">Quantité</label>
													<input type="text" name="update-qty" id="update-qty" class="form-control" readonly />
												</div>
												<div class="form-group col-md-2">
													<label for="update-sts" class="control-label">Statut</label>
													<select class="form-control" id="update-sts" name="update-sts" />
														<optgroup label="Statut">
															<option>A</option>
															<option>Q</option>
															<option>R</option>
															</optgroup>
													</select>
												</div>
												<div class="form-group col-md-3">
													<label for="update-date" class="control-label">Dernière modification</label>
													<input type="text" name="update-date" id="update-date" class="form-control" readonly/>
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-7">
													<label for="update-comment" class="control-label">Commentaire</label>
													<input type="text" class="form-control" id="update-comment" name="update-comment" />
												</div>
											</div>
											<input type="hidden" id="update-id" name="update-id" />
										</form>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Annuler</button>
										<button type="button" id="update-button" class="btn btn-success success"><span class="glyphicon glyphicon-ok"></span> Valider</button>
									</div>
								</div>
							</div>
						</div>
					</tbody>
				</table>
			</div>
		</section>

		<?php require('includes/footer.php'); ?>


		