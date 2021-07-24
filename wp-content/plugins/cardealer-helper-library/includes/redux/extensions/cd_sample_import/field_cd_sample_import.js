/*global jQuery, document, redux*/
(function( $ ) {
	"use strict";

	redux.field_objects = redux.field_objects || {};
	redux.field_objects.cd_sample_import = redux.field_objects.cd_sample_import || {};
	
	$( document ).ready(
		function() {
			//redux.field_objects.cd_sample_import.init();
		}
	);

	redux.field_objects.cd_sample_import.init = function( selector ) {
		
		if ( !selector ) {
			selector = $( document ).find( ".redux-group-tab:visible" ).find( '.redux-container-cd_sample_import:visible' );
		}

		$( selector ).each(
			function() {
				var el = $( this );
				var parent = el;
				if ( !el.hasClass( 'redux-field-container' ) ) {
					parent = el.parents( '.redux-field-container:first' );
				}
				if ( parent.is( ":hidden" ) ) { // Skip hidden fields
					return;
				}
				if ( parent.hasClass( 'redux-field-init' ) ) {
					parent.removeClass( 'redux-field-init' );
				} else {
					return;
				}
				
				el.each( function() {
					
					$( '.import-this-sample' ).click( function( e ) {
						e.preventDefault();
						
						if( $(this).hasClass('disabled') ){
							return false;
						}
						
						var current_element = $(e.target);
						
						if( current_element.data('message') ){
							var import_message = unescape(current_element.data('message'));
						}else{
							var import_message = sample_data_import_object.alert_default_message;
						}
						
						var install_required_plugins = false;
						if( current_element.data('required-plugins') ){
							install_required_plugins = true;
						}
						
						var template = wp.template( 'cardealer-helper-sample-import-alert' );
						var template_content = template( {
							title: current_element.data('title'),
							message: import_message,
							import_requirements_list: sample_data_import_object.sample_data_requirements,
							required_plugins_list: sample_data_import_object.sample_data_required_plugins_list
						});
						
						$.confirm({
							title: sample_data_import_object.alert_title,
							content: template_content,
							type: 'red',
							icon: 'fa fa-warning',
							animation: 'scale',
							closeAnimation: 'scale',
							bgOpacity: 0.8,
							columnClass: 'col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1 sample-data-confirm',
							buttons: {
								'confirm': {
									text: sample_data_import_object.alert_proceed,
									btnClass: 'btn-green',
									action: function () { 
										if( install_required_plugins ){
											window.location = sample_data_import_object.tgmpa_url;
										}else{
											// ********************************** Ajax Start **********************************
											var overlay = $( document.getElementById( 'redux_ajax_overlay' ) );
											var loader = $('#redux-sticky #info_bar .spinner');
											
											// Display Loader
											$(loader).addClass('is-active');
											
											var loader_html = '<div class="loader_message content">';
												loader_html += '<img src="'+ cdhl.cdhl_url +'/images/sample-data-loader.gif" alt="spinner">';
												loader_html += '<h1 class="stm_message_title">Importing Demo Content...</h1>';
												loader_html += '<p class="stm_message_text">Duration of demo content importing depends on your server speed.</p>';
												loader_html += '</div>';
											overlay.append(loader_html);
											
											// Display Overlay
											overlay.fadeIn();
											// Disable all import buttons
											var $nonce = current_element.data( "nonce" );
											
											$.ajax({
												type: 'POST',
												dataType: 'json',
												url: sample_data_import_object.ajaxurl,
												data: { 
													'action': 'theme_import_sample', //calls wp_ajax_nopriv_ajaxlogin
													'security': $('#sample_data_security').val(),
													'sample_id': current_element.data('id'),
													'nonce': $nonce,
												},
												success: function(data){
													// Hide Loader
													$(loader).removeClass('is-active');
													
													// Hide Overlay
													overlay.fadeOut( 'fast' );
													
													$('#redux_notification_bar .admin-demo-data-notice').hide().slideDown('slow').delay(5000).slideUp('slow');
													$('#redux_notification_bar .admin-demo-data-reload').hide().delay(1000).slideDown('slow').delay(15000).slideUp('slow');
													
													// Reload Page
													window.setTimeout(function(){
														document.location.href = document.location.href;
													}, 5000);
													
													return data;
												}
											});
											//**********************************  Ajax End  **********************************
										}
									}
								},
								'cancel': {
									text: sample_data_import_object.alert_cancel,
									btnClass: 'btn-red',
								},
							},
							onContentReady: function () {
								if( install_required_plugins ){
									this.buttons.confirm.setText(sample_data_import_object.alert_install_plugins);
								}
							},
							onOpen: function () {
							},
						});
						
						window.onbeforeunload = null;
						redux.args.ajax_save = false;
					} );
					
				});
			}
		);
	};
})( jQuery );