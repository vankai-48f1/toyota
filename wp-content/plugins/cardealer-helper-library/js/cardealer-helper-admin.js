
/* Code for dialog on reduct dealer forms */
( function( $ ) {
	"use strict";

	var $document = $( document );

	// Check element exists.
	$.fn.exists = function () {
		return this.length > 0;
	};

	/************************************
	:: Theme Option Search
	************************************/
	if ( $('#redux-header').exists() ) {

		var $ThemeOptions = jQuery('#redux-header');
		// if( $ThemeOptions.length == 0 ) return;

		var $searchForm = jQuery('<div class="cardealer-option-search"><form><input id="cardealer-option-search-input" placeholder="' +  cardealer_search_config.search_option_placeholder_text + '" type="text" /></form></div>'),
		$searchInput = $searchForm.find('input');

		// Add Seach Input Option in Theme options
		$ThemeOptions.find('.display_header').after($searchForm);

		$searchForm.find('form').submit(function(e) {
			e.preventDefault();
		});

		// Covert Object To Array
		var OptionsArray = jQuery.map(cardealer_search_config.reduxThemeOptions, function(value, index) {
			return [value];
		});

		var $autocomplete = $searchInput.autocomplete({
			source: function( request, response ) {
				response( OptionsArray.filter( function( value ) {
					return value.title.search( new RegExp(request.term, "i")) != -1
				}) );
			},

			select: function( event, ui ) {
				var $field = jQuery('[data-id="' + ui.item.id + '"]');
				jQuery('#' + ( ui.item.section_id + 1 ) + '_section_group_li_a').click();
				jQuery('.redux-current-options').removeClass('redux-current-options');
				$field.parent().parent().find('.redux_field_th').parents('tr').addClass('redux-current-options');

				var new_position = jQuery($field).offset();
				if( new_position ){
					jQuery('html, body').stop().animate({ scrollTop: new_position.top - 150 }, 1500);
				}
			}

		}).data( "ui-autocomplete" );

		$autocomplete._renderItem = function( ul, item ) {
			var $icon = '';
			if( item.icon ){
				$icon = '<i class="el ' + item.icon + '"></i>';
			}
			var $SearchItemContent = $icon + item.title + '</span><br><span class="settting-path">' + item.path + '</span>'
			return jQuery( "<li>" )
				.append( $SearchItemContent )
				.appendTo( ul );
		};

		$autocomplete._renderMenu = function( ul, items ) {
			var this_var = this;
			jQuery.each( items, function( index, item ) {
				this_var._renderItemData( ul, item );
			});
			jQuery( ul ).addClass( "cardealer-reduxoptions-result" );
		};
	}

	jQuery(window).on('load', function(){
		setTimeout( function() {
			$document.trigger( 'hide_all_cdhl_dismissible_notices' );
		}, 5000 );
	});

	$document.on( 'hide_all_cdhl_dismissible_notices', hide_all_cdhl_dismissible_notices );

	function hide_all_cdhl_dismissible_notices() {
		$( '.notice.cdhl-is-dismissible' ).each( function() {
			var $el = $( this );
			$el.fadeTo( 100, 0, function() {
				$el.slideUp( 100, function() {
					$el.remove();
				});
			});
		});
	}

	$( document ).ready(function() {

		$(document).on('click', '.notice-dismiss', function(event) {
			event.preventDefault();

			var $button = $( this ),
				$el     = $button.closest('.cdhl-is-dismissible');

			$el.fadeTo( 100, 0, function() {
				$el.slideUp( 100, function() {
					$el.remove();
				});
			});
		});

		/**
		 * Add Addtional attributes
		 */
		if ( $('#new_additional_attributes_form').exists() ) {
			var new_addtnl_attr_form     = $('#new_additional_attributes_form'),
				singular_name            = $( new_addtnl_attr_form ).find('#singular_name'),
				plural_name              = $( new_addtnl_attr_form ).find('#plural_name'),
				attribute_slug           = $( new_addtnl_attr_form ).find('#attribute_slug'),
				new_add_attr_form_submit = $( new_addtnl_attr_form ).find('#add-additional-attributes-submit');

			$(document).on('click', '#add-additional-attributes-submit', function(event) {
				event.preventDefault();
				var paramData = {
					action: 'add_additional_attributes',
					nonce: new_addtnl_attr_form.attr('data-nonce'),
					singular_name: singular_name.val(),
					plural_name: plural_name.val(),
					attribute_slug: attribute_slug.val(),
				};
				console.log(paramData);
				$.ajax({
					url : cdhl.ajaxurl,
					type:'POST',
					dataType:'json',
					data: paramData,
					beforeSend: function(){
						jQuery('#add-additional-attributes-submit').parent().find('.spinner').css('visibility','visible');
						jQuery('#add-additional-attributes-submit').prop('disabled',true);
					},
					success: function(response){
						jQuery('#add-additional-attributes-submit').parent().find('.spinner').css('visibility','hidden');
						jQuery('#add-additional-attributes-submit').prop('disabled',false);
						if(response.status) {
							if(response.status == 'success') {
								jQuery('.additional-attributes-data').html(response.data);

								// Reset fields.
								singular_name.val('');
								plural_name.val('');
								attribute_slug.val('');

								// Redirect after success.
								if ( 'undefined' !== typeof response.redirect ) {
									top.location.replace( response.redirect );
								} else {
									location.reload();
								}
							}

							jQuery('html, body').animate({
								scrollTop: (0)
							}, 500);
							jQuery('.cdhl-admin-notice').html(response.msg);
						}
					},
					error: function(){
						console.log('Something went wrong!');
					}
				});
			});
		}

		/**
		 * Delete Addtional attributes
		 */
		jQuery(document).on('click', '.delete-attr', function(event) {
			event.preventDefault();
			if(confirm(jQuery(this).attr('data-alerttxt'))){
				var paramData = {
					action: 'delete_additional_attributes',
					nonce: jQuery('#edit_additional_attributes').attr('data-nonce'),
					index_id: jQuery(this).attr('data-id'),
					attribute_slug: jQuery(this).attr('data-slug')
				};
				var $this = jQuery(this);
				jQuery.ajax({
					url : cdhl.ajaxurl,
					type:'POST',
					dataType:'json',
					data: paramData,
					beforeSend: function(){
						$this.parent().find('.spinner').css('visibility','visible');
						jQuery('.delete-attr').prop('disabled',true);
					},
					success: function(response){
						$this.parent().find('.spinner').css('visibility','hidden');
						jQuery('.delete-attr').prop('disabled',false);
						if(response.status) {
							if(response.status == 'success') {
								jQuery('.additional-attributes-data').html(response.data);
								if ( 'undefined' !== typeof response.redirect ) {
									top.location.replace( response.redirect );
								} else {
									location.reload();
								}
							}
							jQuery('html, body').animate({
								scrollTop: (0)
							}, 500);
							jQuery('.cdhl-admin-notice').html(response.msg);
						}
					},
					error: function(){
						console.log('Something went wrong!');
						jQuery(this).find('.spinner').css('visibility','hidden');
						jQuery('.delete-attr').prop('disabled',false);
					}
				});
			}
		});

		/**
		 * Edit Addtional attributes
		 */
		jQuery(document).on('click', '.edit-additional-attr', function(event) {
			event.preventDefault();
			jQuery('.edit-row').hide('data-id');
			var index_id = jQuery(this).attr('data-id');
			jQuery('#'+index_id).show();
		});

		jQuery(document).on('click', '.edit-additional-attributes-submit', function(event) {
			event.preventDefault();

			var index_id       = jQuery(this).attr('data-id');
			var singular_name  = jQuery('#singular-name-'+index_id).val();
			var plural_name    = jQuery('#plural-name-'+index_id).val();
			var attribute_slug = jQuery(this).attr('data-slug');
			var $this          = jQuery(this);
			var paramData = {
				action: 'edit_additional_attributes',
				nonce: jQuery('#edit_additional_attributes').attr('data-nonce'),
				singular_name: singular_name,
				plural_name: plural_name,
				attribute_slug:attribute_slug
			};
			jQuery.ajax({
				url : cdhl.ajaxurl,
				type:'POST',
				dataType:'json',
				data: paramData,
				beforeSend: function(){
					$this.parent().find('.spinner').css('visibility','visible');
					$this.prop('disabled',true);
				},
				success: function(response){
					$this.parent().find('.spinner').css('visibility','hidden');
					$this.prop('disabled',false);
					if(response.status) {
						if(response.status == 'success') {
							jQuery('.additional-attributes-data').html(response.data);
							if ( 'undefined' !== typeof response.redirect ) {
								top.location.replace( response.redirect );
							} else {
								location.reload();
							}
						}
						jQuery('html, body').animate({
							scrollTop: (0)
						}, 500);
						jQuery('.cdhl-admin-notice').html(response.msg);
					}
				},
				error: function(){
					console.log('Something went wrong!');
				}
			});
		});

		/**
		 * Edit core attributes
		 */
		 if( document.getElementById('edit_core_attributes') ) {
			jQuery(document).on('click', '.edit-core-attr', function(event) {
				event.preventDefault();
				jQuery('.edit-core-row').hide('data-coreid');
				var index_id = jQuery(this).attr('data-coreid');
				jQuery('#'+index_id).show();
			});

			jQuery(document).on('click', '.edit-core-attributes-submit', function(event) {
				event.preventDefault();
				var index_id       = jQuery(this).attr('data-id');
				var singular_name  = jQuery('#core-singular-name-'+index_id).val();
				var plural_name    = jQuery('#core-plural-name-'+index_id).val();
				var slug           = jQuery(this).attr('data-slug');
				var taxonomy       = jQuery(this).attr('data-taxonomy');
				var $this          = jQuery(this);
				var paramData = {
					action: 'edit_core_attributes',
					nonce: jQuery('#edit_core_attributes').attr('data-nonce'),
					singular_name: singular_name,
					plural_name: plural_name,
					slug:slug,
					taxonomy: taxonomy
				};
				jQuery.ajax({
					url : cdhl.ajaxurl,
					type:'POST',
					dataType:'json',
					data: paramData,
					beforeSend: function(){
						$this.parent().find('.spinner').css('visibility','visible');
						$this.prop('disabled',true);
					},
					success: function(response){
						$this.parent().find('.spinner').css('visibility','hidden');
						$this.prop('disabled',false);
						if(response.status) {
							if(response.status == 'success') {
								jQuery('.core-attributes-data').html(response.data);
								if ( 'undefined' !== typeof response.redirect ) {
									top.location.replace( response.redirect );
								} else {
									location.reload();
								}
							}
							jQuery('html, body').animate({
								scrollTop: (0)
							}, 500);
							jQuery('.cdhl-admin-notice').html(response.msg);
						}
					},
					error: function(){
						console.log('Something went wrong!');
					}
				});
			});
		}

		if ( $('.redux-form-wrapper').exists() ) {

			var mileage_option_fields = $( '#car_dealer_options-min_mileage #min_mileage, #car_dealer_options-add_per_mileage #add_per_mileage, #car_dealer_options-mileage_step #mileage_step' ),
				min_mileage           = $( '#car_dealer_options-min_mileage #min_mileage' ),
				add_per_mileage       = $( '#car_dealer_options-add_per_mileage #add_per_mileage' ),
				mileage_step          = $( '#car_dealer_options-mileage_step #mileage_step' ),
				min_mileage_val       = parseInt( min_mileage.val() ),
				add_per_mileage_val   = parseInt( add_per_mileage.val() ),
				mileage_step_val      = parseInt( mileage_step.val() ),
				mileage_brkwn_view_el = $( '#car_dealer_options-vehicle_mileage_breakdown #cardealer-options-display-mileage-breakdown' ),
				max_mileage           = parseInt( $( mileage_brkwn_view_el ).data( 'max_mileage' ) );

			cardealer_get_mileage_breakdown( min_mileage_val, add_per_mileage_val, mileage_step_val );

			$( 'body' ).on( 'keyup change', mileage_option_fields, function() {
				min_mileage_val       = parseInt( min_mileage.val() );
				add_per_mileage_val   = parseInt( add_per_mileage.val() );
				mileage_step_val      = parseInt( mileage_step.val() );

				cardealer_get_mileage_breakdown( min_mileage_val, add_per_mileage_val, mileage_step_val );
			});
		}

		function cardealer_get_mileage_breakdown( min_mileage, add_per_mileage, mileage_step ) {
			var mileage_array = new Array( '&leq; ' + min_mileage );
			var new_mileage = min_mileage;

			var i;
			for ( i = 1; i <= ( mileage_step - 1 ); i++ ){
				new_mileage = new_mileage + add_per_mileage;
				mileage_array.push( '&leq; ' + new_mileage );
			}

			if ( new_mileage < max_mileage ) {
				mileage_array.push( '<= ' + max_mileage );
			}

			var mileage_array_str = '<ul><li>' + mileage_array.join( '</li><li>' ) + '</li></ul>';
			$( '#car_dealer_options-vehicle_mileage_breakdown #cardealer-options-display-mileage-breakdown' ).html( mileage_array_str );
		}

	});

	if ( $('#pgs-page-title-actions .pgs-page-title-action').exists() ) {
		$('#pgs-page-title-actions .pgs-page-title-action').detach().insertBefore('hr.wp-header-end');
	}

})( jQuery );


// Dashboard Goal scripts
jQuery(document).ready( function(){

	if( jQuery( ".variable-content" ).length ) {
		jQuery( ".variable-content" ).dialog({
		  autoOpen: false,
		  show: {
			effect: "blind",
			duration: 1000
		  },
		  hide: {
			effect: "explode",
			duration: 1000
		  }
		});
		jQuery( ".cd_dialog" ).on( "click", function( event ) {
			event.preventDefault();
			var dialog = jQuery(this).attr('data-id');
			jQuery( '#' + dialog ).dialog( "open" );
			/* Add pgs_dialog class to parent div of dialog to differenciate from other dialogs for close button issue */
			if ( jQuery('.ui-dialog').attr('aria-describedby') == dialog ) jQuery('.ui-dialog').addClass('pgs_dialog');
		});
	}

	/**
	 * Reset pdf samples
	 */
	jQuery('#reset-pdf-sample').on('click', function(event) {
		event.preventDefault();
		var paramData = {
			action: 'reset_pdf_sample_template_fields',
			nonce: jQuery(this).attr('data-nonce')
		};
		jQuery.ajax({
			url : cdhl.ajaxurl,
			type:'POST',
			dataType:'json',
			data: paramData,
			beforeSend: function(){
				jQuery('#reset-pdf-sample').find('.spinner').css('visibility','visible');
				jQuery('.publish').prop('disabled',true);
			},
			success: function(response){
				jQuery(this).find('.spinner').css('visibility','hidden');
				jQuery('.publish').prop('disabled',false);
				if(response.status) {
					location.reload();
				}
			},
			error: function(){
				console.log('Something went wrong!');
			}
		});
	});

});

(function($){
	if(typeof acf === 'undefined')
        return;

	/*
     * Init
     */
    var taxonomy       = acf.getFieldType('taxonomy');
    var taxonomy_model = taxonomy.prototype;

	taxonomy_model.onClickAdd = function( e, $el ){

		// vars
		var field = this;
		var popup = false;
		var $form = false;
		var $name = false;
		var $parent = false;
		var $button = false;
		var $message = false;
		var notice = false;

		// step 1.
		var step1 = function(){

			// popup
			popup = acf.newPopup({
				title: $el.attr('title'),
				loading: true,
				width: '300px'
			});

			// ajax
			var ajaxData = {
				action:		'acf/fields/taxonomy/add_term',
				field_key:	field.get('key')
			};

			// get HTML
			$.ajax({
				url: acf.get('ajaxurl'),
				data: acf.prepareForAjax(ajaxData),
				type: 'post',
				dataType: 'html',
				success: step2
			});
		};

		// step 2.
		var step2 = function( html ){

			// update popup
			popup.loading(false);
			popup.content(html);

			// vars
			$form = popup.$('form');
			$name = popup.$('input[name="term_name"]');
			$parent = popup.$('select[name="term_parent"]');
			$button = popup.$('.acf-submit-button');

			// focus
			$name.focus();

			// submit form
			popup.on('submit', 'form', step3);
		};

		// step 3.
		var step3 = function( e, $el ){

			// prevent
			e.preventDefault();
			e.stopImmediatePropagation();

			// basic validation
			if( $name.val() === '' ) {
				$name.focus();
				return false;
			}

			// disable
			acf.startButtonLoading( $button );

			// ajax
			var ajaxData = {
				action: 		'acf/fields/taxonomy/add_term',
				field_key:		field.get('key'),
				term_name:		$name.val(),
				term_parent:	$parent.length ? $parent.val() : 0
			};

			$.ajax({
				url: acf.get('ajaxurl'),
				data: acf.prepareForAjax(ajaxData),
				type: 'post',
				dataType: 'json',
				success: step4
			});
		};

		// step 4.
		var step4 = function( json ){

			// enable
			acf.stopButtonLoading( $button );

			// remove prev notice
			if( notice ) {
				notice.remove();
			}

			// success
			if( acf.isAjaxSuccess(json) ) {

				// clear name
				$name.val('');

				// update term lists
				step5( json.data );

				// notice
				notice = acf.newNotice({
					type: 'success',
					text: acf.getAjaxMessage(json),
					target: $form,
					timeout: 2000,
					dismiss: false
				});

			} else {

				// notice
				notice = acf.newNotice({
					type: 'error',
					text: acf.getAjaxError(json),
					target: $form,
					timeout: 2000,
					dismiss: false
				});
			}

			// focus
			$name.focus();
		};

		// step 5.
		var step5 = function( term ){

			// update parent dropdown
			var $option = $('<option value="' + term.term_id + '">' + term.term_label + '</option>');
			if( term.term_parent ) {
				if ( 'field_588f17606f58c' != field.get('key') ) {
					$parent.children('option[value="' + term.term_parent + '"]').after( $option );
				}
			} else {
				$parent.append( $option );
			}

			// add this new term to all taxonomy field
			var fields = acf.getFields({
				type: 'taxonomy'
			});

			fields.map(function( otherField ){
				if( otherField.get('taxonomy') == field.get('taxonomy') ) {
					otherField.appendTerm( term );
				}
			});

			// select
			field.selectTerm( term.term_id );
		};

		// run
		step1();
	}

})(jQuery);
