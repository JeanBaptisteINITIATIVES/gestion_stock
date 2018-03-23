$(function() {

	// Loader
	$('.loader').fadeOut('500');

	// Focus sur champ "alias" à l'ouverture de la page d'index
	$('#user-alias').focus();

	// Menu collapse se referme au clic d'un lien
    $(document).on('click','.navbar-collapse.in',function(e)
    {
        if( $(e.target).is('li') )
        {
            $(this).collapse('hide');
        }
    });

    
    // On masque les boutons de changement d'état d'emplacement au chargement 
	$('#unlock-button, #block-button').hide();
	

	// Affichage formulaire d'entrée de stock au clic
    $('#entry-button').on('click', function() {
		$('#block-location').css('display', 'none');
		$('#refresh-products').css('display', 'none');
    	$('#product-entry').toggle('400', function() {
    		$('#add-form')[0].reset();
    		$('#location').focus();
    	});
    });


    // Affichage formulaire de blocage d'emplacement
    $('#loc-button').on('click', function() {
		$('#product-entry').css('display', 'none');
		$('#refresh-products').css('display', 'none');
    	$('#block-location').toggle('400', function() {
    		$('#block-form')[0].reset();
    		$('#block').focus();
    	});
	});
	

	// Affichage formulaire de synchronisation avec Scorre
	$('#db-button').on('click', function () {
		$('#product-entry').css('display', 'none');
		$('#block-location').css('display', 'none');
		$('#refresh-products').toggle('400', function () {
			$('#refresh-form')[0].reset();
			$('#refresh-button').focus();
		});
	});


    // Focus sur "emplacement" au changement de choix bouton radio
    $('input[type="radio"][name="entry-by"]').change(function() {
    	$('#location').focus();
    });


	// Focus sur le champ "emplacement" au chargement de la page
	$('#location, #history-ref').focus();

	
	// Sélection du texte des champs au focus
	$('#location, #reference, #designation, #quantity, #comment, #block, #history-ref, #history-des, #date-one, #date-first, #date-last').on('focus', function() {
		$(this).select();
	});


	// Tooltips des boutons d'actions
	$('[data-tooltip="tooltip"]').tooltip();

	
	// Loader à la connexion
	$('#auth-form').submit(function(e) {
		if ( $('#sync').is('checked') )
		{
			$('#index-loader').show();
			$('#index-loader').html("<p class='text-api'>Chargement en cours...</p>");
		}
	});


	
	// $('#refresh-form').submit(function(e) {
	// 	e.preventDefault();
	// 	var postdata = $('#refresh-form').serialize();

	// 	$.ajax({
	// 		type: 'POST',
	// 		url: '../../config/api.php',
	// 		data: postdata,
	// 		beforeSend: function() {
	// 			$('.loader').fadeIn();
	// 		},
	// 		complete: function() {
	// 			location.reload();
	// 		}
	// 	});
	// });
							

	// Contrôle données envoyées dans entrée stock
	$('#add-form').submit(function(e) {
        e.preventDefault();
        $('#add-form .help-block').empty();
		var postdata    = $('#add-form').serialize();
		var reference   = $('#reference').val();
		var designation = $('#designation').val(); 
		var stock 	    = $('input[name=entry-by]:checked').val();
		
		if ( stock == 'tracked' )
		{
			$.ajax({
				url: 'http://sccoreapi/v1/product/?filter={"is_stock_managed":-1,"id__startswith":"' + reference + '"}&rows=50',
				dataType: 'json',
				async: true,
				success: function(data) {
					if ( data.length === 0 || reference == '' )
					{
						$('#add-ref').addClass('has-error');
						$('#help-ref').html('Référence erronée');
					}
					else
					{
						$('#add-ref').removeClass('has-error');
						$('#help-ref').html();
					}
				}
			});
			$.ajax({
				url: 'http://sccoreapi/v1/product/?filter={"is_stock_managed":-1,"name__startswith":"' + designation + '"}&rows=50',
				dataType: 'json',
				async: true,
				success: function (data) {
					if ( data.length === 0 || designation == '' )
					{
						$('#add-des').addClass('has-error');
						$('#help-des').html('Désignation erronée');
					}
					else {
						$('#add-des').removeClass('has-error');
						$('#help-des').html();
					}
				}
			});
		}
		else
		{
			$.ajax({
				url: 'http://sccoreapi/v1/product/?filter={"is_stock_managed":0,"id__startswith":"' + reference + '"}&rows=50',
				dataType: 'json',
				async: true,
				success: function (data) {
					if (data.length === 0 || reference == '') {
						$('#add-ref').addClass('has-error');
						$('#help-ref').html('Référence erronée');
					}
					else {
						$('#add-ref').removeClass('has-error');
						$('#help-ref').html();
					}
				}
			});
			$.ajax({
				url: 'http://sccoreapi/v1/product/?filter={"is_stock_managed":0,"name__startswith":"' + designation + '"}&rows=50',
				dataType: 'json',
				async: true,
				success: function (data) {
					if (data.length === 0 || designation == '') {
						$('#add-des').addClass('has-error');
						$('#help-des').html('Désignation erronée');
					}
					else {
						$('#add-des').removeClass('has-error');
						$('#help-des').html();
					}
				}
			});
		}
		
		$.ajax({
            type: 'POST',
            url: '../../ajax/checkEntry.php',
            data: postdata,
            dataType: 'json',
            success: function(json) {
                
                if (json.isSuccess) 
                {
					$('#add-form .form-group').removeClass('has-error');
                    $.ajax({
			        	type: 'POST',
			        	url: '../../ajax/checkLocation.php',
			        	data: postdata,
			        	dataType: 'json',
			        	success: function(json) {

			        		if ( !json.isLocationEmpty )
			        		{
			        			$('#modal-confirm').on('show.bs.modal', function() {
			        				var modal = $(this);
				        			modal.find('.modal-title').text('Emplacement ' + $('#location').val());
				        			modal.find('.modal-body p:eq(0)').text('Voulez-vous gerber le produit');
			        				modal.find('.modal-body p:eq(1)').text($('#designation').val());
			        				modal.find('.modal-body p:eq(2)').text('en quantité ' + $('#quantity').val() + ' ?');
			        			});

			        			$('#modal-confirm').modal('show');

			        			$('#btn-modal-ok').on('click', function() {
						        	$.ajax({
						        		type: 'POST',
						        		url: '../../ajax/insertEntry.php',
						        		data: postdata,
						        		success: function() {
						        			$('#add-form')[0].reset();
						                    location.reload();
						        		}
						        	});
        						});
			        		}
			        		else
			        		{
			        			$.ajax({
					        		type: 'POST',
					        		url: '../../ajax/insertEntry.php',
					        		data: postdata,
					        		success: function() {
					        			$('#add-form')[0].reset();
					                    location.reload();
					        		}
					        	});
			        		}
			        	}
			        });
                }
                else
                {
                    $('#add-loc, #add-qty').addClass('has-error');
                    $('#help-loc').html(json.locError);
                    $('#help-qty').html(json.qtyError);
                    
                    if ( json.locError == "" )
                    {
                    	$('#add-loc').removeClass('has-error');
                    }
                	if ( json.qtyError == "" )
                	{
                		$('#add-qty').removeClass('has-error');
                	}
                	if ( json.inputError != "" )
                	{
                		$('#input-help-block').text(json.inputError);
                	}
                }                
            }
        });       
    });


    // Affichage des infos du produit dans le modal d'ajustement positif ou négatif
	$('#modal-plus').on('show.bs.modal', function(e) {
		var button     = $(e.relatedTarget);
		var idInput    = button.data('id');
		$('#new-plus-qty').val('');
		
		$.ajax({
		 	type: 'GET',
		 	url: '../../ajax/displayEntry.php',
		 	data: 'id=' + idInput,
		 	dataType: 'json',
		 	success: function(json) {
				$('#plus-loc').val(json.location);
				$('#plus-ref').val(json.reference);
				$('#plus-des').val(json.designation);
				$('#plus-qty').val(json.quantity);
				$('#plus-sts').val(json.status);
				$('#plus-date').val(json.date_update);
				$('#plus-comment').val(json.comment);

				$('#plus-id').val(idInput);
		 	}
		});
	});

	$('#modal-minus').on('show.bs.modal', function(e) {
		var button     = $(e.relatedTarget);
		var idInput    = button.data('id');
		$('#new-minus-qty').val('');
		
		$.ajax({
		 	type: 'GET',
		 	url: '../../ajax/displayEntry.php',
		 	data: 'id=' + idInput,
		 	dataType: 'json',
		 	success: function(json) {
				$('#minus-loc').val(json.location);
				$('#minus-ref').val(json.reference);
				$('#minus-des').val(json.designation);
				$('#minus-qty').val(json.quantity);
				$('#minus-sts').val(json.status);
				$('#minus-date').val(json.date_update);
				$('#minus-comment').val(json.comment);

				$('#minus-id').val(idInput);
		 	}
		});
	});

	// Affichage des infos du produit dans le modal de sortie de stock
	$('#modal-delete').on('show.bs.modal', function(e) {
		var button  = $(e.relatedTarget);
		var idInput = button.data('id');

		$.ajax({
		 	type: 'GET',
		 	url: '../../ajax/displayEntry.php',
		 	data: 'id=' + idInput,
		 	dataType: 'json',
		 	success: function(json) {
				$('#delete-loc').val(json.location);
				$('#delete-ref').val(json.reference);
				$('#delete-des').val(json.designation);
				$('#delete-qty').val(json.quantity);
				$('#delete-sts').val(json.status);
				$('#delete-date').val(json.date_update);
				$('#delete-comment').val(json.comment);

				$('#delete-id').val(idInput);
		 	}
		});
	});

	// Affichage des infos du produit dans le modal de transfert
	$('#modal-transfer').on('show.bs.modal', function(e) {
		var button  = $(e.relatedTarget);
		var idInput = button.data('id');
		$('#new-transfer-loc').val('');

		$.ajax({
		 	type: 'GET',
		 	url: '../../ajax/displayEntry.php',
		 	data: 'id=' + idInput,
		 	dataType: 'json',
		 	success: function(json) {
				$('#transfer-loc').val(json.location);
				$('#transfer-ref').val(json.reference);
				$('#transfer-des').val(json.designation);
				$('#transfer-qty').val(json.quantity);
				$('#transfer-sts').val(json.status);
				$('#transfer-date').val(json.date_update);
				$('#transfer-comment').val(json.comment);

				$('#transfer-id').val(idInput);
		 	}
		});
	});

	// Affichage des infos du produit dans le modal de commentaires
	$('#modal-update').on('show.bs.modal', function(e) {
		var button  = $(e.relatedTarget);
		var idInput = button.data('id');
		
		$.ajax({
		 	type: 'GET',
		 	url: '../../ajax/displayEntry.php',
		 	data: 'id=' + idInput,
		 	dataType: 'json',
		 	success: function(json) {
				$('#update-loc').val(json.location);
				$('#update-ref').val(json.reference);
				$('#update-des').val(json.designation);
				$('#update-qty').val(json.quantity);
				$('#update-sts').val(json.status);
				$('#update-date').val(json.date_update);
				$('#update-comment').val(json.comment);

				$('#update-id').val(idInput);
		 	}
		});
	});

	// Focus sur bouton de validation à l'ouverture du modal de sortie de stock
	$('#modal-delete').on('shown.bs.modal', function() {
		$('#delete-button').focus();
	});

	// Focus après ouverture modal d'ajustement sur champ "quantité"
	$('#modal-plus').on('shown.bs.modal', function() {
		$('#new-plus-qty').focus();
	});

	$('#modal-minus').on('shown.bs.modal', function() {
		$('#new-minus-qty').focus();
	});

	// Focus sur le champ "emplacement de destination" à l'ouverture du modal de transfert
	$('#modal-transfer').on('shown.bs.modal', function() {
		$('#new-transfer-loc').focus();
	});

	// Focus sur le champ "nouveau commentaire" à l'ouverture du modal de commentaires
	$('#modal-update').on('shown.bs.modal', function() {
		$('#update-comment').focus();
	});

	// Focus sur le bouton "gerber" à l'ouverture du modal
	$('#modal-confirm').on('shown.bs.modal', function () {
		$('#btn-modal-ok').focus();
	});

	// On enlève les messages d'erreur à la fermeture des modals
	$('#modal-plus').on('hidden.bs.modal', function() {
		$('#new-input-plus-qty').removeClass('has-error');
		$('#help-plus-qty').empty();
	});
	
	$('#modal-minus').on('hidden.bs.modal', function() {
		$('#new-input-minus-qty').removeClass('has-error');
		$('#help-minus-qty').empty();
	});
	
	$('#modal-transfer').on('hidden.bs.modal', function() {
		$('#new-input-transfer-loc').removeClass('has-error');
		$('#help-transfer-loc').empty();
	});

	// Blur à la fermeture du modal
	$('[data-tooltip="tooltip"]').on('focus', function() {
        $(this).blur();
	}); 


	// Modification des données à validation modal d'ajustement positif
	$('#plus-button').on('click', function(e) {
		e.preventDefault();
		var postdata     = $('#plus-form').serialize();
		var typeOfAdjust = 'plus';
		postdata += '&typeOfAdjust=' + typeOfAdjust;
		$('#plus-form .help-block').empty();

		$.ajax({
		 	type: 'POST',
		 	url: '../../ajax/adjustEntry.php',
		 	data: postdata,
		 	dataType: 'json',
		 	success: function(json) {
				if ( json.isSuccess )
				{
					$('.loader').show();
					$('#plus-form .form-group').removeClass('has-error');
					$('#modal-plus').fadeOut();
					location.reload();
				}
				else
				{
					$('#new-input-plus-qty').addClass('has-error');
                    $('#help-plus-qty').html(json.qtyError);
				}
		 	}
		});
	});

	// Modification des données à validation modal d'ajustement négatif
	$('#minus-button').on('click', function(e) {
		e.preventDefault();
		var postdata     = $('#minus-form').serialize();
		var typeOfAdjust = 'minus';
		postdata += '&typeOfAdjust=' + typeOfAdjust;
		$('#minus-form .help-block').empty();

		$.ajax({
		 	type: 'POST',
		 	url: '../../ajax/adjustEntry.php',
		 	data: postdata,
		 	dataType: 'json',
		 	success: function(json) {
				if ( json.isSuccess )
				{
					$('.loader').show();
					$('#minus-form .form-group').removeClass('has-error');
					$('#modal-minus').fadeOut();
					location.reload();
				}
				else
				{
					$('#new-input-minus-qty').addClass('has-error');
                    $('#help-minus-qty').html(json.qtyError);
				}
		 	}
		});
	});

	// Suppression du produit sélectionné
	$('#delete-button').on('click', function(e) {
		e.preventDefault();
		var postdata = $('#delete-form').serialize();

		$.ajax({
		 	type: 'POST',
		 	url: '../../ajax/deleteEntry.php',
		 	data: postdata,
		 	success: function() {
				$('.loader').show();
				$('#modal-delete').fadeOut();
			 },
			 complete: function() {
				 location.reload();
			 }
		});
	});

	// Tranfert d'un produit à un autre emplacement
	$('#transfer-button').on('click', function(e) {
		e.preventDefault();
		var postdata = $('#transfer-form').serialize();
		$('#transfer-form .help-block').empty();

		$.ajax({
		 	type: 'POST',
		 	url: '../../ajax/transferEntry.php',
		 	data: postdata,
		 	dataType: 'json',
		 	success: function(json) {
				if ( json.isSuccess )
				{
					$('.loader').show();
					$('#transfer-form .form-group').removeClass('has-error');
					$('#modal-transfer').fadeOut();
					location.reload();
				}
				else
				{
					$('#new-input-transfer-loc').addClass('has-error');
                    $('#help-transfer-loc').html(json.locError);
				}
				
		 	}
		});
	});

	// Modification du commentaire à validation
	$('#update-button').on('click', function(e) {
		e.preventDefault();
		var postdata = $('#update-form').serialize();

		$.ajax({
		 	type: 'POST',
		 	url: '../../ajax/updateEntry.php',
		 	data: postdata,
		 	success: function() {
				$('.loader').show();
				$('#modal-update').fadeOut();
			 },
			complete: function() {
				location.reload();
			}
		});
	});

	// Blocage / déblocage d'emplacement
	$('#block-button, #unlock-button').on('click', function(e) {
		e.preventDefault();
		var postdata = $('#block-form').serialize();

		$.ajax({
		 	type: 'POST',
		 	url: '../../ajax/blockLocation.php',
		 	data: postdata,
		 	success: function() {
				$('.loader').show();
				$('#block-form')[0].reset();
			},
			complete: function() {
				location.reload();
			} 
		});
	});

	//Contrôle données envoyées dans recherche historique produit
	$('#hist-button').on('click', function(e) {
        e.preventDefault();
        $('#history-form .help-block').empty();
		var postdata    = $('#history-form').serialize();
		var reference   = $('#history-ref').val();
		var designation = $('#history-des').val();
        
		$.ajax({
			url: 'http://sccoreapi/v1/product/?filter={"id__startswith":"' + reference + '"}&rows=50',
			dataType: 'json',
			async: true,
			success: function (data) {
				if (data.length === 0 || reference == '') {
					$('#search-ref').addClass('has-error');
					$('#help-hist-ref').html('Référence erronée');
				}
				else {
					$('#search-ref').removeClass('has-error');
					$('#help-hist-ref').html();
				}
			}
		});
		$.ajax({
			url: 'http://sccoreapi/v1/product/?filter={"name__startswith":"' + designation + '"}&rows=50',
			dataType: 'json',
			async: true,
			success: function (data) {
				if (data.length === 0 || designation == '') {
					$('#search-des').addClass('has-error');
					$('#help-hist-des').html('Désignation erronée');
				}
				else {
					$('#search-des').removeClass('has-error');
					$('#help-hist-des').html();
				}
			}
		});
		$.ajax({
            type: 'POST',
            url: '../../ajax/checkHistorySearch.php',
            data: postdata,
            dataType: 'json',
            success: function(json) {
                
                if (json.isSuccess) 
                {
                    $('#history-form .form-group').removeClass('has-error');
					$('#history-form').submit();
                }
                else
                {
                    $('#hist-date-one, #hist-date-first, #hist-date-last').addClass('has-error');
                    $('#help-hist-date-one').html(json.dateOneError);
                    $('#help-hist-date').html(json.dateError);
                    
                	if ( json.dateOneError == "" )
                	{
                		$('#hist-date-one').removeClass('has-error');
                	}
                	if ( json.dateError == "" )
                	{
                		$('#hist-date-first, #hist-date-last').removeClass('has-error');
                	}
                }                
            }
        });       
    });
	
	
	//////////////////////////////////////////////////////////////////////
	//////////////////////// DATATABLE ///////////////////////////////////
	//////////////////////////////////////////////////////////////////////

	// https://datatables.net
	// https://connect.ed-diamond.com/GNU-Linux-Magazine/GLMF-189/DataTables-interagir-avec-les-tableaux-HTML
	
	// Datatable responsive "entrée produit"
	$('#table-entry').dataTable({
		fixedHeader: true,
		responsive: true,
		pagingType: "full_numbers",
		lengthMenu: [5, 10, 15, 20, 25, 50, 100, 500],
		pageLength: 25,
		order: [0, 'desc'],
		columns: [
			{type: "text", visible: false, orderable: false, searchable: false},
			{type: "text", orderable: false},
			{type: "text", orderable: false},
			{type: "text", orderable: false},
			{type: "num", orderable: false, searchable: false},
			{type: "text"},
			{orderable: false, searchable: false},
			{type: "text", orderable: false, searchable: false},
			{orderable: false, searchable: false},
			{type: "text", orderable: false, searchable: false},
			{type: "text", orderable: false, searchable: false},
			{type: "text", orderable: false}
		],
		language: {
	        url: '../../assets/lang/datatableFrench.json'
    	}
	});

	// Datatable responsive "visualisation d'emplacements"
	$('#table-list-loc').dataTable({
		fixedHeader: true,
		responsive: true,
		pagingType: "full_numbers",
		lengthMenu: [5, 10, 15, 20, 25, 50, 100, 500],
		pageLength: 50,
		order: [0, 'asc'],
		columns: [
			{type: "text"},
			{type: "text", orderable: false},
			{type: "text", orderable: false},
			{type: "num", orderable: false, searchable: false},
			// {type: "text"},
			{type: "text", orderable: false, searchable: false},
			{orderable: false, searchable: false},
			{type: "text", orderable: false, searchable: false}
			// {type: "text", orderable: false, searchable: false},
			// {type: "text", orderable: false, searchable: false}
		],
		language: {
	        url: '../../assets/lang/datatableFrench.json'
    	}
	});

	// Datatable responsive "historique"
	$('#table-history').dataTable({
		fixedHeader: true,
		responsive: true,
		pagingType: "full_numbers",
		lengthMenu: [5, 10, 15, 20, 25, 50, 100],
		pageLength: 25,
		order: [0, 'asc'],
		columns: [
			{type: "text", visible: false, orderable: false, searchable: false},
			{type: "text", orderable: false},
			{type: "num", orderable: false, searchable: false},
			{type: "text", orderable: false, searchable: false},
			{type: "text", orderable: false, searchable: false},
			{type: "text"},
			{type: "text"},
			{type: "text"}
		],
		language: {
	        url: '../../assets/lang/datatableFrench.json'
    	}
	});


	//////////////////////////////////////////////////////////////////////
	//////////////////////// JQUERY PRINT ////////////////////////////////
	//////////////////////////////////////////////////////////////////////
	var date       = new Date();
	var print_date = date.toLocaleString();

	$('#print-button').on('click', function() {
		$("#table-list-loc").print({ title: 'Emplacements_' + print_date });
	});

	$('#print-hist-button').on('click', function() {
		$("#table-history").print({ title: 'Historique_' + print_date });
	});


	//////////////////////////////////////////////////////////////////////
	//////////////////////// BOOTSTRAP DATEPICKER ////////////////////////
	//////////////////////////////////////////////////////////////////////
	$('input[name="date-one"], input[name="date-first"], input[name="date-last"]').datepicker({
		format: 'dd/mm/yyyy',
		todayHighlight: true,
		autoclose: true
	});
	
	
	//////////////////////////////////////////////////////////////////////
	//////////////////////// AUTOCOMPLETION //////////////////////////////
	//////////////////////////////////////////////////////////////////////

	var cache       = {};   // Variable pour la mise en cache des réponses
	var term        = null; // Initialisation des réponses

	if ( $(location).attr('pathname') == '/productEntry.php' )
	{
		// Autocomplétion entrée produit "emplacements"
		$("#location").autocomplete({
			minLength: 1,
			delay: 200,
			source: function(request, response) {
						term = request.term;
						if (term in cache)
						{
							response(cache[term]);
						}
						else
						{
							$.ajax({
								type: 'GET',
								url: '../../ajax/getLocation.php',
								data: 'term=' + request.term,
								dataType: 'json',
								async: true,
								cache: true,
								success: function(data) {
										 	cache[term] = data;
										 	if (!data.length)
										 	{
										 		var result = [{
										 			label: "Aucun emplacement trouvé...",
										 			value: null
										 		}];
										 		response(result);
										 	}
										 	else
										 	{
										 		response(data);
											}
								}
							});
						}
			},
			select: function(e, ui)
			{
				$("#reference").focus();
			}
		});

		// Autocomplétion transfert produit "emplacements"
		$("#new-transfer-loc").autocomplete({
			minLength: 1,
			delay: 200,
			source: function(request, response) {
						term = request.term;
						if (term in cache)
						{
							response(cache[term]);
						}
						else
						{
							$.ajax({
								type: 'GET',
								url: '../../ajax/getLocation.php',
								data: 'term=' + request.term,
								dataType: 'json',
								async: true,
								cache: true,
								success: function(data) {
										 	cache[term] = data;
										 	if (!data.length)
										 	{
										 		var result = [{
										 			label: "Aucun emplacement trouvé...",
										 			value: null
										 		}];
										 		response(result);
										 	}
										 	else
										 	{
										 		response(data);
											}
								}
							});
						}
			},
			select: function(e, ui)
			{
				$("#transfer-button").focus();
			}
		});


		// Autocomplétion transfert produit "emplacements"
		$("#block").autocomplete({
			minLength: 1,
			delay: 200,
			source: function(request, response) {
						term = request.term;
						if (term in cache)
						{
							response(cache[term]);
						}
						else
						{
							$.ajax({
								type: 'GET',
								url: '../../ajax/getLocation.php',
								data: 'term=' + request.term,
								dataType: 'json',
								async: true,
								cache: true,
								success: function(data) {
										 	cache[term] = data;
										 	if ( !data.length )
										 	{
										 		var result = [{
										 			label: "Aucun emplacement trouvé...",
										 			value: null
										 		}];
										 		response(result);
										 	}
										 	else
										 	{
										 		response(data);
											}
								}
							});
						}
			},
			select: function(e, ui)
			{
				$('#block-button, #unlock-button').hide();
				var postdata = $('#block').val(ui.item.value);

				$.ajax({
		        	type: 'POST',
		        	url: '../../ajax/checkIfLocationLocked.php',
		        	data: postdata,
		        	dataType: 'json',
		        	success: function(json) {

		        		if ( json.isSuccess )
		        		{
		        			$('#block-form .form-group').removeClass('has-error');
		        			$('#block-form .help-block').empty();

		        			if ( json.isLocationLocked )
			        		{
			        			$('#unlock-button').show().focus();
			        		}
			        		else
			        		{
			        			$('#block-button').show().focus();
			        		}
		        		}
		        		else
		        		{
		        			$('#block-input').addClass('has-error');
                    		$('#help-block-loc').html(json.locError);
                    		$('#block').focus().select();
		        		}
		        	}
		        });
		        return false;
			}
		});

		// var products = $.ajax({
		// 	url: 'http://sccoreapi/v1/product/?filter={"is_stock_managed":-1,"id__startswith":"98786344"}&rows=50',
		// 	dataType: 'json',
		// 	async: true
		// });
		// console.log(products);

		// Autocomplétion "référence" entrée produit
		$("#reference").autocomplete({
	        minLength: 2,
	        delay: 400,
	        source: function(request, response) {
						var typeOfEntry = $('input[name=entry-by]:checked').val();
						var stock_managed;
						typeOfEntry == 'tracked' ? stock_managed = -1 : stock_managed = 0;

						$.ajax({
							type: 'GET',
							url: 'http://sccoreapi/v1/product/?filter={"is_stock_managed":' + stock_managed + ',"id__startswith":"' + request.term + '"}&rows=50',
							data: {
								term: request.term
							},
							dataType: 'json',
							async: true,
							cache: true,
							success: function(data) {
										 response(data);
							}
						});
			},
	      	select: function(e, ui) {
		        $("#reference").val(ui.item.id);
				$("#designation").val(ui.item.name);
				$("#quantity").focus();
	        	return false;
	      	}
	    })
	    .data('ui-autocomplete')._renderItem = function(ul, item) {
	      	return $("<li>")
		        .append( "<div>" + item.id + " - " + item.name + "</div>" )
		        .appendTo(ul);
	    };

	    // Autocomplétion "désignation" saisie stock
	    $("#designation").autocomplete({
	      	minLength: 3,
	      	delay: 400,
	      	source: function(request, response) {
						var typeOfEntry = $('input[name=entry-by]:checked').val();
						var stock_managed;
						typeOfEntry == 'tracked' ? stock_managed = -1 : stock_managed = 0;

						$.ajax({
							type: 'GET',
							url: 'http://sccoreapi/v1/product/?filter={"is_stock_managed":' + stock_managed + ',"name__contains":"' + request.term + '"}&rows=50',
							data: {
								term: request.term
							},
							dataType: 'json',
							async: true,
							cache: true,
							success: function(data) {
									 	response(data);
							}
						});
			},
	      	select: function(e, ui) {
		        $("#reference").val(ui.item.reference);
				$("#designation").val(ui.item.designation);
				$("#quantity").focus();
	        	return false;
	      	}
	    })
	    .data('ui-autocomplete')._renderItem = function(ul, item) {
	      	return $("<li>")
		        .append( "<div>" + item.id + " - " + item.name + "</div>" )
		        .appendTo(ul);
	    };
	}

	if ( $(location).attr('pathname') == '/locations.php' )
	{
		// Autocomplétion transfert produit "emplacements"
		$("#list-loc").autocomplete({
			minLength: 1,
			delay: 200,
			source: function(request, response) {
						term = request.term;
						if (term in cache)
						{
							response(cache[term]);
						}
						else
						{
							$.ajax({
								type: 'GET',
								url: '../../ajax/getLocation.php',
								data: 'term=' + request.term,
								dataType: 'json',
								async: true,
								cache: true,
								success: function(data) {
										 	cache[term] = data;
										 	if ( !data.length )
										 	{
										 		var result = [{
										 			label: "Aucun emplacement trouvé...",
										 			value: null
										 		}];
										 		response(result);
										 	}
										 	else
										 	{
										 		response(data);
											}
								}
							});
						}
			},
			select: function(e, ui)
			{
				$('#list-button').focus();
			}
		});
	}

	if ( $(location).attr('pathname') == '/history.php' )
	{
		// Autocomplétion "référence" historique
		$("#history-ref").autocomplete({
	        minLength: 2,
	        delay: 400,
	        source: function(request, response) {

						$.ajax({
							type: 'GET',
							url: 'http://sccoreapi/v1/product/?filter={"id__startswith":"' + request.term + '"}&rows=50',
							data: {
								term: request.term
							},
							dataType: 'json',
							async: true,
							cache: true,
							success: function(data) {
									 	response(data);
							}
						});
			},
	      	select: function(e, ui) {
		        $("#history-ref").val(ui.item.id);
				$("#history-des").val(ui.item.name);
				$("#hist-button").focus();
	        	return false;
	      	}
	    })
	    .data('ui-autocomplete')._renderItem = function(ul, item) {
	      	return $("<li>")
		        .append( "<div>" + item.id + " - " + item.name + "</div>" )
		        .appendTo(ul);
	    };

	    // Autocomplétion "désignation" historique
	    $("#history-des").autocomplete({
	      	minLength: 3,
	      	delay: 400,
	      	source: function(request, response) {

						$.ajax({
							type: 'GET',
							url: 'http://sccoreapi/v1/product/?filter={"name__contains":"' + request.term + '"}&rows=50',
							data: {
								term: request.term
							},
							dataType: 'json',
							async: true,
							cache: true,
							success: function(data) {
									 	response(data);
							}
						});
			},
	      	select: function(e, ui) {
		        $("#history-ref").val(ui.item.id);
				$("#history-des").val(ui.item.name);
				$("#hist-button").focus();
	        	return false;
	      	}
	    })
	    .data('ui-autocomplete')._renderItem = function(ul, item) {
	      	return $("<li>")
		        .append( "<div>" + item.id + " - " + item.name + "</div>" )
		        .appendTo(ul);
	    };
	}
});