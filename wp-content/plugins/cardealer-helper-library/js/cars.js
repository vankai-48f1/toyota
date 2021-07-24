( function( $ ) {
	jQuery(document).ready( function(){
		/*
		* Code for select checkboxes by default on car post type for export thirdparty
		*/
		jQuery('select#bulk-action-selector-top').change( function(){
			if( jQuery(this).val() == 'export_autotrader' || jQuery(this).val() == 'export_car_com' || jQuery(this).val() == 'export_cars' ) {
				if( jQuery('#cb-select-all-1').prop("checked") != true )
					jQuery('#cb-select-all-1').trigger('click');
			} else {
				/*if( jQuery('#cb-select-all-1').prop("checked") == true )
					jQuery('#cb-select-all-1').trigger('click');*/
			}
		});

		/*
		* Code for download PDF Brochure admin side
		*/
		jQuery(document).on("click", ".download-pdf", function (e) {

			e.preventDefault();

			var generate_btn       = $( this ),
				generate_pdf_wrap  = generate_btn.closest( '.admin-column-pdf-actions' ),
				generate_spinner   = generate_pdf_wrap.find( '.spinner' ),
				id                 = jQuery(this).attr('id'),
				pdf_template_title = jQuery( "#download-"+id+" #casr_pdf_styles option:selected" ).text();

			if ( generate_btn.hasClass('disabled') ) {
				return false;
			}

			jQuery.ajax({

				url:ajaxurl,
				type:'post',
				data: {
					'action':'cdhl_generate_pdf',
					'pdf_template_title': pdf_template_title,
					'id' : id
				},
				beforeSend: function(){
					generate_btn.addClass('disabled');
					generate_spinner.addClass('is-active');
					jQuery('#downloadlink-'+id).hide();
				},
				success:function(data) {
					generate_spinner.removeClass('is-active');

					// This outputs the result of the ajax request
					if ( data.status ) {
						if ( jQuery('#generate-pdf-notice').length > 0 ) {
							$('#generate-pdf-notice').remove();
						}
						jQuery( ".wp-header-end" ).after(cars_pdf_message.pdf_generated_message);
						jQuery('#downloadlink-'+id).html('');
						jQuery('#downloadlink-'+id).show();
						jQuery('#downloadlink-'+id).append('<a href="'+data.url+'" class="button button-primary" target="_blank" download>'+cars_pdf_message.download_pdf_str+'</a>');
					} else {
						jQuery('#downloadlink-'+id).show();
						if ( jQuery('#generate-pdf-notice').length > 0 ) {
							$('#generate-pdf-notice').remove();
						}
						jQuery( ".wp-header-end" ).after('<div id="generate-pdf-notice" class="notice notice-error"><p>'+data.msg+'</p></div>');
					}
					generate_btn.removeClass('disabled');

					jQuery('html, body').animate({
						scrollTop: 0
					}, 'slow' );
				},
				error: function(errorThrown){
					console.log(errorThrown);
				}
			});
		});


		jQuery(document).on( 'change', '.casr_pdf_styles', function(){
			var id = jQuery(this).attr('data-id');
			jQuery('#downloadlink-'+id).hide();
		});

		/* Code tobe use for export to third party */
		jQuery(document).on( 'change', '#bulk-action-selector-top', function(){
			(jQuery(this).val() == 'export_autotrader' || jQuery(this).val() == 'export_car_com' ) ? jQuery('#ftp_now').css( 'display', 'block' ) : jQuery('#ftp_now').css( 'display', 'none' );
		});

		/*Code Of Export Log List*/
		if(document.getElementById('export-log')){
			jQuery('#export-log').DataTable({
				processing: true,
				serverSide: true,
				responsive: true,
				'bLengthChange': false,
				'iDisplayLength': 20,
				'bFilter': false,
				"bSort" : false,
				ajax:  jQuery.fn.dataTable.pipeline({
					url: ajaxurl + '?action=get_export_log',
					pages: 4 // number of pages to cache
				})
			});
		}
		/*Code Of Export Log List*/
		if(document.getElementById('export-log')){
			jQuery('#export-log').DataTable({
				destroy: true,
				processing: true,
				serverSide: true,
				responsive: true,
				'bLengthChange': false,
				'iDisplayLength': 20,
				'bFilter': false,
				"bSort" : false,
				ajax:  jQuery.fn.dataTable.pipeline({
					url: ajaxurl + '?action=get_export_log',
					pages: 4 // number of pages to cache
				})
			});
		}

		/*Code Of Import Log List*/
		if(document.getElementById('import-log')){
			jQuery('#import-log').DataTable({
				destroy: true,
				processing: true,
				serverSide: true,
				responsive: true,
				'bLengthChange': false,
				'iDisplayLength': 20,
				'bFilter': false,
				"bSort" : false,
				ajax:  jQuery.fn.dataTable.pipeline({
					url: ajaxurl + '?action=get_import_log',
					pages: 4 // number of pages to cache
				})
			});
		}

		/*Code Of Export Cars List*/
		if(document.getElementById('export-cars-list')){
			jQuery('#export-cars-list').DataTable({
				destroy: true,
				processing: true,
				serverSide: true,
				responsive: true,
				'bLengthChange': false,
				'iDisplayLength': 20,
				'bFilter': false,
				"bSort" : false,
				ajax:  jQuery.fn.dataTable.pipeline({
					url: ajaxurl + '?action=get_export_car_list',
					pages: 5 // number of pages to cache
				})
			});
		}

		/**
		* Call advance select box for vehicle mapping metabox
		*/
		if(document.getElementById('car_to_woo_product_meta_id')){
			$('#car_to_woo_product_meta_id').select2();
		}
	});
} )( jQuery );
