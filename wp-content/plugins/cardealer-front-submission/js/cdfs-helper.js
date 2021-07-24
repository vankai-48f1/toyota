( function( $ ) {
	jQuery(document).ready(function () {

		/* Car form for autocomplete input fields*/
		if( jQuery(".cdfs-autofill").length > 0 ) {
			var currentRequest = null;
			jQuery(".cdfs-autofill").autocomplete({
				delay: 0,
				minLength:1,
				source: function( request, response ) {
				   /* JSON Request */
				   var fieldName = jQuery(this.element).data("name");
				   var response;

					currentRequest = jQuery.ajax({
						url: front_js.ajax_url,
						type: 'post',
						dataType: 'json',
						jsonCallback: 'jsonCallback',
						data: {action: 'cdfs_get_autocomplete', search: request.term, fieldName: fieldName },
						beforeSend: function(){
							 if(currentRequest != null) {
								currentRequest.abort();
							}
						},
						success: function(resultArray){
							response( jQuery.map( resultArray.data, function( result ) {
								if( resultArray.data.length > 0 ) {
									return result;
								} else {
									return;
								}
							}));
						},
						error: function(msg){}
					});
				}
			});
		}

		/* Added for checkbox fields on cars*/
		jQuery(document).on('change', '#cdfs-other', function(){
			if ( jQuery(this).is(':checked') ) {
				jQuery('#cdfs-cdfs-other-opt').removeClass('cdfs-hidden');
			} else {
				jQuery('#cdfs-cdfs-other-opt').addClass('cdfs-hidden');
			}
		});


		/* Trash car alert*/
		jQuery(document).on( 'click', 'div.car-overlay-banner a.delete-car',  function(e){
			e.preventDefault();
			var link = this;

			jQuery.confirm({
				title: cdfs_obj.alerttxt,
				content: cdfs_obj.delalerttex,
				buttons: {
					confirm: function () {
						window.location = link.href;
					},
					cancel: function () {}
				}
			});
		});

		/* Delete post attachment*/
		jQuery(document).on( 'click', '.drop_img_item',  function(){
			var nonce = jQuery('#cdfs-car-form-nonce-field').val();
			var attach_id = jQuery(this).data("attach_id");
			var parent_id = jQuery(this).data("parent_id");
			var field = jQuery(this).data("field");
			var parent_div = jQuery(this).parent('.cdfs-item');

			jQuery.confirm({
				title: cdfs_obj.alerttxt,
				content: cdfs_obj.delalerttex,
				buttons: {
					confirm: function () {
						jQuery.ajax({
							url: front_js.ajax_url,
							type: 'post',
							dataType: 'json',
							data: {
								action: 'cdfs_delete_attachment',
								nonce: nonce,
								attach_id: attach_id,
								field: field,
								parent_id: parent_id
							},
							beforeSend: function(){

							},
							success: function( resultArray ){
								if( resultArray.status == true ) {
									parent_div.remove();
									//cdfs_add_order();
									cdfs_reload_order();
								}
							},
							error: function(msg){}
						});
					},
					cancel: function () {}
				}
			});
		});

		/* Process ajax login*/
		jQuery(document).on('submit','.cdfs_add_car_page form#cdfs-form-user-login',function(event){
			event.preventDefault();
			var postArray = jQuery(this).serializeArray();
			var elementDiv = jQuery(this);
			var captchaWidgetId = jQuery(this).find('#login_captcha').data('widget_id');
			var btnlbl = jQuery('#form-user-login').text();
			postArray.push({ name: "action", value: "cdfs_do_ajax_user_login" });
			jQuery.ajax({
				url: front_js.ajax_url,
				type: 'post',
				dataType: 'json',
				data: postArray,
				beforeSend: function(){
					cdfs_action_before_login_register(elementDiv);
					jQuery('#form-user-login').html( btnlbl+' <i class="fa fa-spinner fa-spin car-form-loader" aria-hidden="true"></i>');
				},
				success: function( resultObj ){
					jQuery('#form-user-login').html(btnlbl);
					jQuery('#form-user-login').attr('disabled', false);
					cdfs_action_after_login_register( elementDiv, captchaWidgetId,  resultObj );
				},
				error: function(msg){}
			});
		});

		/* Process ajax user registration*/
		jQuery(document).on('submit','.cdfs_add_car_page form#cdfs-form-register',function(event){
			event.preventDefault();
			var elementDiv = jQuery('.cdfs_add_car_page form#cdfs-form-register');
			var postArray = jQuery(this).serializeArray();
			var captchaWidgetId = jQuery(this).find('#register_captcha').data('widget_id');
			var btnlbl = jQuery('#cdfs-form-register-btn').text();
			postArray.push({ name: "action", value: "cdfs_do_ajax_user_register" });
			jQuery.ajax({
				url: front_js.ajax_url,
				type: 'post',
				dataType: 'json',
				data: postArray,
				beforeSend: function(){
					cdfs_action_before_login_register(elementDiv);
					jQuery('#cdfs-form-register-btn').html( btnlbl+' <i class="fa fa-spinner fa-spin car-form-loader" aria-hidden="true"></i>');
				},
				success: function( resultObj ){
					jQuery('#cdfs-form-register-btn').html(btnlbl);
					jQuery('#cdfs-form-register-btn').attr('disabled', false);
					cdfs_action_after_login_register( elementDiv, captchaWidgetId,  resultObj );

				},
				error: function(msg){}
			});
		});

	});

	jQuery( window ).load( function() {
		/* Add / Update car*/
		if( document.getElementById('car_form_captcha') ){
			var car_form_captcha_ele = document.getElementById('car_form_captcha');
			var car_form_captcha_sitekey = car_form_captcha_ele.dataset.sitekey;
			var car_form_captcha = grecaptcha.render('car_form_captcha', {
				'sitekey' : car_form_captcha_sitekey, /*Replace this with your Site key*/
				'theme' : 'light'
			});
			document.getElementById("car_form_captcha").dataset.widget_id = car_form_captcha;
		}

		/* User Login*/
		if( document.getElementById("login_captcha") ){
			var elementCaptcha = document.getElementById('login_captcha');
			var elementSitekey = elementCaptcha.dataset.sitekey;
			var login_captcha = grecaptcha.render('login_captcha', {
				'sitekey' : elementSitekey, //Replace this with your Site key
				'theme' : 'light'
			});
			document.getElementById("login_captcha").dataset.widget_id = login_captcha;
		}

		/* User Registration*/
		if( document.getElementById("register_captcha") ){
			var elementCaptcha = document.getElementById('register_captcha');
			var elementSitekey = elementCaptcha.dataset.sitekey;
			var register_captcha = grecaptcha.render('register_captcha', {
				'sitekey' : elementSitekey, //Replace this with your Site key
				'theme' : 'light'
			});
			document.getElementById("register_captcha").dataset.widget_id = register_captcha;
		}
	});

	function cdfs_action_before_login_register( elementDiv ){
		elementDiv.find(':submit').attr( 'disabled', 'disabled' );
		elementDiv.find(':submit').addClass('disabled');
	}

	function cdfs_action_after_login_register( elementDiv, captchaWidgetId, resultObj ){

		if( resultObj.status == 1 ){
			jQuery(elementDiv).find("p.cdfs-msg").addClass('cdfs-message').html( resultObj.message );
			jQuery(elementDiv).find("p.cdfs-msg").fadeIn('slow');

			setTimeout(function(){
				jQuery('#cdfs_user_login').remove();

				/* fill user section*/
				if( jQuery('.cdfs-user-account').length ){
					jQuery('#cdfs_user_name').html(resultObj.cdfs_user_name);
					jQuery('.cdfs-user-account').css('visibility', 'visible');
				}

				/* display car form captcha*/
				jQuery('#car_form_captcha').show();

				/* Enable submit car button*/
				jQuery('button#cdfs-submit-car').removeAttr('disabled');
				jQuery('button#cdfs-submit-car').removeClass('disabled');
			}, 5000);
		} else {
			jQuery(elementDiv).find("p.cdfs-msg").addClass('cardealer-error').html( resultObj.message );
			jQuery(elementDiv).find("p.cdfs-msg").fadeIn('slow');
			if (typeof grecaptcha !== "undefined") {
				grecaptcha.reset( captchaWidgetId ); // reset captcha
			}
		}
		/* ENABLE login / register buttons*/
		elementDiv.find(':submit').removeAttr('disabled');
		elementDiv.find(':submit').removeClass('disabled');
	}

	/************************************************************
	* CODE FOR MULTIFILE UPLOAD WITH PREVIEW AND ORDERING STARTED
	*************************************************************/
	jQuery(document).ready(function() {

		var storedFiles = [];
		var older = [];

		/* Apply sort function*/
		if( jQuery('.cdfs_uploaded_files').length > 0 ){
			jQuery(function() {
				jQuery('.cdfs_uploaded_files').sortable({
					cursor: 'move',
					placeholder: 'highlight',
					start: function (event, ui) {
						ui.item.toggleClass('highlight');
					},
					stop: function (event, ui) {
						ui.item.toggleClass('highlight');
					},
					update: function () {
						cdfs_reload_order();
					},
					create:function(){
						var list = this;
						resize = function(){
							jQuery(list).css('height','auto');
							jQuery(list).height(jQuery(list).height());
						};
						jQuery(list).height(jQuery(list).height());
						jQuery(list).find('img').load(resize).error(resize);
					}
				});
				jQuery('.cdfs_uploaded_files').disableSelection();
			});
		}

		jQuery('body').on('change', '.user_picked_files', function() {
			var files = this.files;
			var i = 0;

			/* uploaded imgs*/
			var total_imgs = jQuery('.cdfs_uploaded_files li').length;
			total_imgs = total_imgs + this.files.length;

			var Imgs_limit = jQuery(this).data('img_up_limit'); /* Img limit*/

			if( total_imgs > Imgs_limit ){ /* return if limit exceeded*/
				jQuery.alert({
					title: cdfs_obj.errortxt,
					content: cdfs_obj.imglimittxt,
				});
				jQuery(this).val('');
				return;
			}

			for (i = 0; i < files.length; i++) {
				var readImg = new FileReader();
				var file = files[i];

				if (file.type.match('image.*')){
					storedFiles.push(file);
					readImg.onload = (function(file) {
						return function(e) {
							jQuery('.cdfs_uploaded_files').append(
							"<li file = '" + file.name + "'>" +
								"<img class = 'img-thumb' src = '" + e.target.result + "' />" +
								"<a href = '#' class = 'cdfs_delete_image' title = 'Cancel'><span class=remove>x</span></a>" +
							"</li>"
							);
						};
					})(file);
					readImg.readAsDataURL(file);

				} else {
					jQuery.alert({
						title: cdfs_obj.errortxt,
						content: cdfs_obj.imgtypetxt.replace("[file]", file.name)
					});
				}

				if(files.length === (i+1)){
					setTimeout(function(){
						cdfs_add_order();
					}, 1000);
				}
			}

		});

		jQuery('body').on('change', '#car-pdf', function() {
			var files = this.files;
			if( ! files[0].type.match('application/pdf') ){
				jQuery.alert({
					title: cdfs_obj.errortxt,
					content: cdfs_obj.pdftypetxt.replace("[file]", files[0].name),
				});
			}
		});



		/* Delete Image from Queue*/
		jQuery('body').on('click','a.cdfs_delete_image',function(e){
			e.preventDefault();
			jQuery(this).parent().remove('');

			var file = jQuery(this).parent().attr('file');
			for(var i = 0; i < storedFiles.length; i++) {
				if(storedFiles[i].name == file) {
					storedFiles.splice(i, 1);
					break;
				}
			}

			/*cdfs_reload_order();*/

		});

		/* Submit add / update car form*/
		jQuery(document).on('click', '#cdfs-submit-car', function(){
			cdfs_reload_order();
			jQuery(this).attr('disabled', true).addClass('disabled');
			jQuery('#cdfs-submit-car').append('<i class="fa fa-spinner fa-spin car-form-loader" aria-hidden="true"></i>');
			jQuery('.switch-tmce').click();
			/* Map editor fields values with textarea*/
			jQuery('textarea.cdfs_editor').each( function(index, value){
				var editor_val = tinyMCE.get( jQuery(this).attr('id') ).getContent();
				jQuery(this).val(editor_val);
			});

			jQuery('#cdfs_car_form').submit();

		});


		jQuery(document).on('submit', '#cdfs_car_form', function( event ){
			event.stopPropagation(); /* Stop stuff happening*/
			event.preventDefault(); /* Totally stop stuff happening*/
			var carcaptchaWidgetId = jQuery(this).find('#car_form_captcha').data('widget_id');

			/* Create a formdata object and add the files to upload*/
			var imgData = new FormData();
			var car_img_cnt = 0;
			jQuery.each(storedFiles, function(key, value){
				imgData.append('car_images[' + key + ']', value);
				car_img_cnt++;
			});

			/* Add PDF file*/
			if( jQuery('#car-pdf').length > 0 ){
				var pdf_file = jQuery('#car-pdf').prop('files')[0];
				imgData.append('pdf_file', pdf_file);
			}

			imgData.append('action', 'cdfs_upload_images');
			imgData.append('file_attachments', jQuery('#file_attachments').val());

			/* Serialize the form data*/
			var formData = jQuery(event.target).serializeArray();
			formData.push( { name: 'action', value: 'cdfs_save_car' } );
			formData.push( { name: 'car_img_cnt', value: car_img_cnt } );

			$.ajax({
				url  : front_js.ajax_url,
				type : 'POST',
				data : formData,
				cache: false,
				dataType: 'json',
				beforeSend: function(){
					jQuery('.invalid_fields').removeClass('invalid_fields');
				},
				success: function(data, textStatus, jqXHR){
					if(data.status === true){
						/* Success so call function to process the form*/
						imgData.append('car_id', data.car_id); /* add car id to attach attachments*/
						cdfs_save_car_imgs(imgData);
					} else{
						jQuery('.cardealer-error').remove();
						var html = '<ul class="cardealer-error"><li>' + data.message + '</li></ul>';
						jQuery('.entry-content .cdfs').prepend(html);

						/* check validation*/
						if( data.invalid_fields.length > 0 ){
							jQuery(data.invalid_fields).each( function(index, val){
								jQuery('#cdfs_car_form').find('input#cdfs-'+val).addClass('invalid_fields');
							});
						}

						/* reset captcha*/
						if (typeof grecaptcha !== "undefined") {
							grecaptcha.reset( carcaptchaWidgetId ); // reset captcha
						}
						/* move cursor to top*/
						var scrolltop_target = '#main';
						if ( $( '#main .content-wrapper-vc-enabled' ).length > 0 ) {
							scrolltop_target = '#main .content-wrapper-vc-enabled';
						} else if ( $( '#main .page-section-ptb.content-wrapper' ).length > 0 ) {
							scrolltop_target = '#main .page-section-ptb.content-wrapper';
						} else if ( $( '#main > #primary' ).length > 0 ) {
							scrolltop_target = '#main > #primary';
						}
						jQuery('html, body').animate({
						  scrollTop: jQuery( scrolltop_target ).offset().top
						}, 800);
						/* STOP LOADING SPINNER & ENABLE SUBMIT BUTTON*/
						jQuery('.car-form-loader').remove();
						jQuery('#cdfs-submit-car').removeAttr('disabled', false);
						jQuery('#cdfs-submit-car').removeClass('disabled');
					}
				},
				error: function(jqXHR, textStatus, errorThrown){
					/* Handle errors here*/
					console.log('ERRORS: ' + textStatus);
					/* STOP LOADING SPINNER & ENABLE SUBMIT BUTTON*/
					jQuery('.car-form-loader').remove();
					jQuery('#cdfs-submit-car').removeAttr('disabled', false);
					jQuery('#cdfs-submit-car').removeClass('disabled');

					/* reset captcha*/
					if (typeof grecaptcha !== "undefined") {
						grecaptcha.reset( carcaptchaWidgetId ); // reset captcha
					}
				},
				complete: function(){
					/* STOP LOADING SPINNER*/
				}
			});


		});

	});

	/*
		Ajax call for car attachments
	*/
	function cdfs_save_car_imgs( imgDataObj ){
		jQuery.ajax({
			url  : front_js.ajax_url,
			type : 'POST',
			data : imgDataObj,
			cache: false,
			contentType: false,
			processData: false,
			success: function(data, textStatus, jqXHR){
			var response = jQuery.parseJSON(data);
				if(response.status === true){
					window.location.href = response.redirect;
				} else{
					/* reset captcha*/
					if (typeof grecaptcha !== "undefined") {
						grecaptcha.reset( carcaptchaWidgetId ); // reset captcha
					}
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				/* Handle errors here*/
				console.log('ERRORS: ' + textStatus);

				/* reset captcha*/
				if (typeof grecaptcha !== "undefined") {
					grecaptcha.reset( carcaptchaWidgetId ); // reset captcha
				}
				/* STOP LOADING SPINNER & ENABLE SUBMIT BUTTON*/
				jQuery('.car-form-loader').remove();
				jQuery('#cdfs-submit-car').removeAttr('disabled', false);
				jQuery('#cdfs-submit-car').removeClass('disabled');
			},
			complete: function(){
			}
		});
	}



	function cdfs_reload_order() {
		var order = jQuery('.cdfs_uploaded_files').sortable('toArray', {attribute: 'item'});
		jQuery('.cdfs_hidden_field').val(order);
		var attachments  = jQuery('.cdfs_uploaded_files').sortable('toArray', {attribute: 'file'});
		jQuery('.file_attachments').val(attachments);
	}

	function cdfs_add_order() {
		jQuery('.cdfs_uploaded_files li').each(function(n) {
			jQuery(this).attr('item', n);
		});
	}

	function fileUpload(event){
	  /*to notify user the file is being uploaded*/
	 files = event.target.files;
	 /* get the selected files*/
	 var data = new FormData();
	 /* Form Data check the above bullet for what it is*/
	 var error = 0;
	 /* Flag to notify in case of error and abort the upload*/
	/* File data is presented as an array. In this case we can just jump to the index file using files[0] but this array traversal is recommended*/

	 for (var i = 0; i < files.length; i++) {
	  var file = files[i];
	  if(!file.type.match('application/pdf')) {
	   /* Check for File type. the 'type' property is a string, it facilitates usage if match() function to do the matching*/
		jQuery.alert({
			title: cdfs_obj.errortxt,
			content: cdfs_obj.pdftypetxt,
		});
		error = 1;
	   }else if(file.size > (1024 * 4000)){
	   /* File size is provided in bytes*/
		jQuery.alert({
			title: cdfs_obj.errortxt,
			content: cdfs_obj.exceededtxt,
		});
		 error = 1;
	   }else{
		/* If all goes well, append the up-loadable file to FormData object*/
		data.append('image', file, file.name);
		/* Comparing it to a standard form submission the 'image' will be name of input*/
		}
	  }
	}

	/**********************************************************
	* CODE FOR MULTIFILE UPLOAD WITH PREVIEW AND ORDERING END *
	/**********************************************************/

	/***********************************************************
	/* *********** CODE FOR VEHICLE LOCATION ***************** *
	/**********************************************************/
	jQuery(document).ready(function()
	{
		if( jQuery('#cdfs-vehicle-location-area').length > 0 ) {
			jQuery('#cdfs-vehicle-location-area').locationpicker({
				location: {
					latitude: jQuery('#cdfs-lat').val(),
					longitude: jQuery('#cdfs-lng').val()
				},
				radius: 0,
				inputBinding: {
					latitudeInput: jQuery('#cdfs-lat'),
					longitudeInput: jQuery('#cdfs-lng'),
					locationNameInput: jQuery('#cdfs-vehicle-location')
				},
				enableAutocomplete: true,
				onchanged: function (currentLocation, radius, isMarkerDropped) {
					/* Uncomment line below to show alert on each Location Changed event*/
					/* alert("Location changed. New location (" + currentLocation.latitude + ", " + currentLocation.longitude + ")");*/
				}
			});
		}
	});
	/***********************************************************
	/************* CODE FOR VEHICLE LOCATION END ***************
	/**********************************************************/
} )( jQuery );
