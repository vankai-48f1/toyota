( function( $ ) {
	/*
	 * Create dynamically form fields for filtering
	 */
	function get_form_field($this){
		var get_id         = jQuery($this).attr('id');
		var lay_style      = "view-grid-left";
		var form_data      = '';
		var form_data_ajax = '';
		var cars_grid      = 'yes';

		// cardealer_filter_generate_filter_stripe();
		var serch_filter = '';
		jQuery('.select-sort-filters').each(function(){
			var tid      = jQuery(this).attr('data-id');
			var sel_val  = jQuery(this).val();
			var sel_txt  = jQuery(this).attr('data-tax');
			var sel_term = jQuery(this).find('option:selected').html();
			if(sel_val != ""){
				form_data += '<input type="text" name="'+tid+'" value="' + sel_val + '" />';
				form_data_ajax += "&"+tid+"="+sel_val;
				serch_filter += '<li id="stripe-item-'+tid+'" data-type="'+tid+'" ><a href="javascript:void(0)"><i class="far fa-times-circle"></i> '+sel_txt+' :  <span data-key="'+sel_val+'">'+sel_term+'</span></a></li>';
			}
		});

		jQuery('.stripe-item').html(serch_filter);

		var pgs_cars_pp = jQuery('#pgs_cars_pp').val();
		if(pgs_cars_pp){
			form_data += '<input type="text" name="cars_pp" value="' + pgs_cars_pp + '" />';
			form_data_ajax += "&cars_pp="+pgs_cars_pp;
		}

		var cars_orderby = jQuery('#pgs_cars_orderby').val();
		if(cars_orderby){
			form_data += '<input type="text" name="cars_orderby" value="' + cars_orderby + '" />';
			form_data_ajax += "&cars_orderby="+cars_orderby;
		}

		var pgs_min_price = jQuery('#pgs_min_price').val();
		var pgs_max_price = jQuery('#pgs_max_price').val();
		var default_min_val = jQuery('#pgs_min_price').attr('data-min');
		var default_max_val = jQuery('#pgs_max_price').attr('data-max');
		if(default_min_val != pgs_min_price || pgs_max_price != default_max_val){
			form_data += '<input type="text" name="min_price" value="' + pgs_min_price + '" />';
			form_data += '<input type="text" name="max_price" value="' + pgs_max_price + '" />';
			form_data_ajax += "&min_price="+pgs_min_price;
			form_data_ajax += "&max_price="+pgs_max_price;
		}

		//check is active year range slider
		if(cars_year_range_slider_params.is_year_range_active){
			var pgs_year_range_min = jQuery('#pgs_year_range_min').val();
			var pgs_year_range_max = jQuery('#pgs_year_range_max').val();
			var default_year_min_val = jQuery('#pgs_year_range_min').attr('data-yearmin');
			var default_year_max_val = jQuery('#pgs_year_range_max').attr('data-yearmax');
			if(default_year_min_val != pgs_year_range_min || pgs_year_range_max != default_year_max_val){
				form_data += '<input type="text" name="min_year" value="' + pgs_year_range_min + '" />';
				form_data += '<input type="text" name="max_year" value="' + pgs_year_range_max + '" />';
				form_data_ajax += "&min_year="+pgs_year_range_min;
				form_data_ajax += "&max_year="+pgs_year_range_max;
			}
		}

		var cars_order = '';
		if(get_id == "pgs_cars_order"){
			cars_order = jQuery($this).attr('data-order');
			if(cars_order && cars_order != "" && cars_order != 'undefined'){
				form_data += '<input type="text" name="cars_order" value="' + cars_order + '" />';
				form_data_ajax += "&cars_order="+cars_order;
			}
		} else {
			cars_order = jQuery('#pgs_cars_order').attr('data-current_order');
			if(cars_order && cars_order != "" && cars_order != 'undefined'){
				form_data += '<input type="text" name="cars_order" value="' + cars_order + '" />';
				form_data_ajax += "&cars_order="+cars_order;
			}
		}

		if(jQuery($this).hasClass('catlog-layout')){
			lay_style = jQuery($this).attr('data-id');
			cookies.set( 'lay_style' , lay_style);
			if(jQuery($this).hasClass('view-grid')){
				form_data += '<input type="text" name="cars_grid"  value="yes"/>';
			}

			if(jQuery($this).hasClass('view-list')){
				form_data += '<input type="text" name="cars_grid"  value="no"/>';
			}

			form_data += '<input type="text" name="lay_style"  value="'+lay_style+'"/>';
			var cn_paged = null;
			jQuery('.pagination-nav ul li span').each(function(){
				if(jQuery(this).hasClass('current')){
					var cuernt_page = jQuery(this).text();
					if(cuernt_page != null){
						cn_paged = parseInt(cuernt_page);
					}
				}
			});
			form_data += '<input type="text" name="paged" value="' + cn_paged + '" />';
		} else {
			//if cookies not set then get default option value
			if(cardealer_obj.lay_style != ''){
				lay_style = cardealer_obj.lay_style;
				cars_grid = cardealer_obj.cars_grid;
			}

			laystyle = cookies.get('lay_style');
			carsgrid = cookies.get('cars_grid');
			if(carsgrid == 'yes' || carsgrid == 'no'){
				cars_grid = carsgrid;
			}
			if(laystyle != null){
				lay_style = laystyle;
			}

			form_data += '<input type="text" name="lay_style"  value="'+lay_style+'"/>';
			form_data_ajax += "&lay_style="+lay_style;
			form_data_ajax += "&cars_grid="+cars_grid;
		}

		var pgs_cars_search = jQuery('#pgs_cars_search').val();

		if(pgs_cars_search && pgs_cars_search != '' && pgs_cars_search != 'undefined'){
			form_data += '<input type="search" name="s" value="' + pgs_cars_search + '" />';
			form_data += '<input type="hidden" name="post_type" value="cars" />';
			form_data_ajax += "&s="+pgs_cars_search;
			form_data_ajax += "&post_type=cars";
		}

		if(cardealer_obj.is_vehicle_cat){
			/*form_data += '<input type="hidden" name="is_vehicle_cat" value="yes" />';*/
			form_data_ajax += "&is_vehicle_cat=yes";
			form_data_ajax += "&vehicle_cat="+cardealer_obj.vehicle_cat;

		}

		if(cardealer_obj.vehicle_location){
			form_data_ajax += "&vehicle_location="+cardealer_obj.vehicle_location;
			form_data += '<input type="hidden" name="vehicle_location" value="' + cardealer_obj.vehicle_location + '" />';
		}

		if(typeof cars_filter_methods != 'undefined' && cars_filter_methods.cars_filter_with == 'yes'){
			if(jQuery($this).hasClass('catlog-layout')){
				jQuery('<form>', {
					"id": "getCarsData",
					"html": form_data,
					"action": cars_price_slider_params.cars_form_url
				}).appendTo(document.body).submit();
			} else {
				return form_data_ajax;
			}
		} else {
			jQuery('<form>', {
				"id": "getCarsData",
				"html": form_data,
				"action": cars_price_slider_params.cars_form_url
			}).appendTo(document.body).submit();
		}
	}

	function cardealer_filter_generate_filter_stripe() {
		var serch_filter = '';

		$( 'select.select-sort-filters' ).each( function() {
			var tid      = jQuery(this).attr('data-id');
			var sel_val  = jQuery(this).val();
			var sel_txt  = jQuery(this).attr('data-tax');
			var sel_term = jQuery(this).find('option:selected').html();
			if ( sel_val != '' ) {
				serch_filter += '<li id="stripe-item-'+tid+'" data-type="'+tid+'" ><a href="javascript:void(0)"><i class="far fa-times-circle"></i> '+sel_txt+' :  <span data-key="'+sel_val+'">'+sel_term+'</span></a></li>';
			}
		});

		$('.stripe-item').html( serch_filter );
	}

	/*
	 * Get data using ajax method
	 */
	function do_ajax_call(form_data){
		var make_widgets  = {},
			form_data_new = form_data;

		// Prepapre make_widget data.
		if ( $( '.widget.widget-vehicle-make-logos' ).length > 0 ) {
			$( '.widget.widget-vehicle-make-logos' ).each(function( index ) {
				make_widgets[ $(this).attr('id') ] = $( this ).find('.cardealer-make-logos-wrap').data('widget_params');
			});
		}

		form_data_new = get_query_parameters( 'action=cardealer_cars_filter_query'+form_data_new+'&query_nonce='+cars_price_slider_params.cardealer_cars_filter_query_nonce );
		form_data_new.make_widgets = make_widgets;

		if(typeof cars_filter_methods != 'undefined' && cars_filter_methods.cars_filter_with == 'yes'){
			jQuery.ajax({
				url: cardealer_js.ajaxurl,
				type: 'post',
				dataType: 'json',
				data:form_data_new,
				beforeSend: function(){
					jQuery('.filter-loader').html('<span class="filter-loader"><i class="cd-loader"></i></span>');
					if( jQuery('.cars-top-filters-box').length ){
						jQuery('.cars-top-filters-box').append('<span class="filter-loader"><i class="cd-loader"></i></span>');
					}
					jQuery('.pagination-loader').html('<span class="pagination-loader"><i class="cd-loader"></i></span>');
					jQuery('.select-sort-filters').prop('disabled',true);
					jQuery('#submit_all_filters').prop('disabled',true);
				},
				success: function(response){
					// Lazyload
					if( jQuery('section.lazyload').length > 0 ){
						if(typeof response.tot_result != 'undefined' && response.tot_result < 1){
							jQuery('.all-cars-list-arch').attr('data-records', 0);
						} else {
							jQuery('.all-cars-list-arch').removeAttr('data-records');
						}
					}

					jQuery('.all-cars-list-arch').html(response.data_html);
					jQuery('.pagination-nav').html(response.pagination_html);
					jQuery('.cars-order').html(response.order_html);
					set_result_filters(response);
					jQuery('.select-sort-filters').prop('disabled',false);
					jQuery('#submit_all_filters').prop('disabled',false);
					jQuery('.filter-loader').html('');
					if( jQuery('.cars-top-filters-box .filter-loader').length ){
						jQuery('.cars-top-filters-box .filter-loader').remove();
					}
					jQuery('.pagination-loader').html('');
					jQuery('.number_of_listings').html(response.tot_result);
					setTimeout(function(){
						set_layout_height();
					}, 1000);
					// masonry style
					if( jQuery('.masonry-main .all-cars-list-arch.masonry').length > 0 ){
						setTimeout(function(){
							cardealer_load_masonry(); // Reload shuffle masonry
						}, 1000);
					}

					transmission_dots();
					jQuery('select').niceSelect('update');
					if ( jQuery( '[data-toggle="tooltip"]' ).length > 0 ) {
						jQuery( '[data-toggle="tooltip"]' ).tooltip();
					}

					var get_first_charecter = form_data.charAt(0);
					var qur_first = '?1=1';
					if(get_first_charecter == '&'){
						form_data = form_data.replace('&','?');
					}
					window.history.pushState(null, null, cars_price_slider_params.cars_form_url+form_data);
					if( jQuery( '.cars-top-filters-box' ).length ){
						jQuery("html, body").animate( { scrollTop: jQuery('.cars-top-filters-box').offset().top }, 200 );
					}
					$( document.body ).trigger( 'cardealer_filter_ajax_done', [ response ] );
				},
				error: function(msg){
					alert('Something went wrong!');
					jQuery('.filter-loader').html('');
					jQuery('.pagination-loader').html('');
				}
			});
		}
		return false;
	}

	function get_query_parameters( str ) {
		return str.toString().replace(/(^\?)/,'').split("&").map(function(n){return n = n.split("="),this[n[0]] = n[1],this}.bind({}))[0];
	}

	function set_result_filters(response){
		var sel_obj = {};
		jQuery('.select-sort-filters').each(function(){
			var tid = jQuery(this).attr('data-id');
			var sel_val = jQuery(this).val();
			if(sel_val != ""){
				sel_obj[tid] = sel_val;
			}
		});

		if(typeof response.all_filters == "object") {
			jQuery.each(response.all_filters, function(key, value) {
				var tax_label = jQuery('#sort_'+key).attr('data-tax');
				var new_options = "<option value=''>" + tax_label + "</option>";
				if (typeof value == "object") {
					jQuery.each(value, function (value_key, value_value) {
						jQuery.each(value_value, function (new_value_key, new_value_value) {
							var selected_val=''; var selopkey = '';
							jQuery.each(sel_obj, function (sel_obj_key, sel_obj_value) {
								if(sel_obj_key == key){
									selected_val = "selected='selected'";
								}
							});
							if(key != "car_mileage"){
								new_options += "<option value='" + new_value_key + "' "+selected_val+">" + new_value_value + "</option>";
							}
						});
					});
				}
				if(key != "car_mileage"){
					jQuery('#sort_'+key).html(new_options);
				}
			});
		}
	}

	function get_cfb_ajax_parameter_with_cfb_type($this){

		var parameters_arr = {};
		var selectedattr = [];
		var selobj = {};
		var formdata="";
		var uid = "";
		var tid='';
		var currentselectedattr = jQuery($this).attr('data-id');
		jQuery('.custom-filters-box').each(function(){
			tid = jQuery(this).attr('data-id');
			var sel_val = jQuery(this).val();
			if(tid){
				uid = jQuery(this).attr('data-uid');
				selectedattr.push(tid);
				if(sel_val != ""){
					formdata += "&"+tid+"="+sel_val;
					selobj[tid] = sel_val;
				}
			}
		});
		var col_class='4';
		var formId = jQuery($this).closest(".col-6").attr('id');
		var box_type = jQuery($this).hasClass('col-6');
		if(box_type) {
			col_class = '6';
		}
		if(document.getElementById('pgs_year_range_min')){
			var pgs_year_range_min = jQuery('#pgs_year_range_min').val();
			var pgs_year_range_max = jQuery('#pgs_year_range_max').val();
			var default_year_min_val = jQuery('#pgs_year_range_min').attr('data-yearmin');
			var default_year_max_val = jQuery('#pgs_year_range_max').attr('data-yearmax');
			if(default_year_min_val != pgs_year_range_min || pgs_year_range_max != default_year_max_val){
				formdata += "&min_year="+pgs_year_range_min;
				formdata += "&max_year="+pgs_year_range_max;
			}
		}

		formdata += "&current_attr="+currentselectedattr;

		parameters_arr['selected_attr'] = selectedattr;
		parameters_arr['sel_obj']       = selobj;
		parameters_arr['form_data']     = formdata;
		parameters_arr['uid']           = uid;
		parameters_arr['tid']           = tid;
		parameters_arr['col_class']     = col_class;
		parameters_arr['current_selected_attr'] = currentselectedattr;
		return parameters_arr;
	}
	/**
	 * This function only used in custom filter box widget
	 */
	function do_cfb_ajax_call(form_data,col_class,selected_attr,sel_obj,current_selected_attr,uid){
		var empty_select_opt_label = jQuery('.search-block').attr('data-empty-lbl');
		if(empty_select_opt_label){
			select_empty_opt_label = empty_select_opt_label;
		} else {
			select_empty_opt_label = '--Select--';
		}
		jQuery.ajax({
			url: cardealer_js.ajaxurl,
			type: 'post',
			dataType: 'json',
			data:'action=cardealer_cars_filter_query'+form_data+'&cfb=yes&box_type='+col_class+'&selected_attr='+selected_attr+'&query_nonce='+cars_price_slider_params.cardealer_cars_filter_query_nonce,
			beforeSend: function(){
				jQuery('.filter-loader').html('<span class="filter-loader"><i class="cd-loader"></i></span>');
				jQuery('.pagination-loader').html('<span class="pagination-loader"><i class="cd-loader"></i></span>');
				jQuery('.custom-filters-box').prop('disabled',true);
				jQuery('.cfb-submit-btn').prop('disabled',true);
			},
			success: function(response){
				if(response.status == "success"){
					if(typeof response.all_filters == "object") {
						jQuery.each(response.all_filters, function(key, value) {
							var new_options    = "<option value=''>"+select_empty_opt_label+"</option>";
							if (typeof value == "object") {
								jQuery.each(value, function (value_key, value_value) {
									jQuery.each(value_value, function (new_value_key, new_value_value) {
										var selected_val='';
										jQuery.each(sel_obj, function (sel_obj_key, sel_obj_value) {
											if(sel_obj_key == key){
												selected_val = "selected='selected'";
											}
										});
										if(key != "car_mileage"){
											new_options += "<option value='" + new_value_key + "' "+selected_val+">" + new_value_value + "</option>";
										}
									});
								});
							}
							if(key != "car_mileage" && key != current_selected_attr){
								jQuery('#sort_'+key+'_'+uid).html(new_options);
							}
						});
					}
					jQuery('.filter-loader').html('');
					jQuery('.pagination-loader').html('');
					jQuery('.custom-filters-box').prop('disabled',false);
					jQuery('.cfb-submit-btn').prop('disabled',false);
					jQuery('select').niceSelect('update');
					if ( jQuery( '[data-toggle="tooltip"]' ).length > 0 ) {
						jQuery( '[data-toggle="tooltip"]' ).tooltip();
					}

					// Lazyload
					if( jQuery('section.lazyload').length > 0 ){
						if(typeof response.tot_result != 'undefined' && response.tot_result < 1){
							jQuery('.all-cars-list-arch').attr('data-records', 0);
						} else {
							jQuery('.all-cars-list-arch').removeAttr('data-records');
						}
					}

				}
			},
			error: function(msg){
				alert('Something went wrong!');
				jQuery('.filter-loader').html('');
				jQuery('.pagination-loader').html('');
			}
		});
	}

	jQuery(document).ready(function($){
		// remove a filter
		$("ul.stripe-item").on("click", "li", function(e){
			e.preventDefault();
			$(this).fadeOut(400, function(){
				$(this).remove();
				var data_type = $(this).attr('data-type');
				$('#sort_'+data_type).val('');
				form_data = get_form_field(this);
				do_ajax_call(form_data);
			});
		});

		var form_data = '';
		$(document).on('change','.select-sort-filters',function(){
			var current_clicked_attr = $(this).attr('data-id');
			var current_clicked_value = $(this).val();
			var form_data = '';var buil_data = '';
			form_data += get_form_field(this);
			if(current_clicked_value != ''){
				current_value = current_clicked_value;
				form_data +="&current_value="+current_value;
				form_data +="&current_attr="+current_clicked_attr;
			}
			do_ajax_call(form_data);
		});

		if ( $('.cardealer-make-logos').hasClass('vehicle-archive-page') && $( '.sort-filters select[name="car_make"]' ).length > 0 ) {
			$( document ).on( 'click', ".cardealer-make-logos .cardealer-make-logo a", function(e){
				e.preventDefault();

				var car_make_link  = $( this ),
					car_makes_wrap = car_make_link.closest('.cardealer-make-logos'),
					car_make_wrap  = car_make_link.closest('.cardealer-make-logo'),
					tax_name       = car_make_link.data('tax_name'),
					tax_slug       = car_make_link.data('tax_slug');

				car_makes_wrap.find('.cardealer-make-logo').removeClass('active');
				car_make_wrap.addClass('active');

				$( '.sort-filters select[name="car_make"]' ).val(tax_slug).trigger('change');
			});
		}

		$( document.body ).on( 'cardealer_filter_ajax_done', function(e , response ) {
			cardealer_filter_generate_filter_stripe();

			if ( response.hasOwnProperty('vehicle_make') && $.trim( response.vehicle_make ) ) {
				jQuery.each( response.vehicle_make, function( key, value ) {
					$( '#' + key + ' .cardealer-make-logos-wrap' ).html( value );
				} );
			}
		});

		/*
		 * This function use for custom filters sortcode
		 */
		$(document).on('change','.custom-filters-box',function(){
			var cfb_ajax_paramete = get_cfb_ajax_parameter_with_cfb_type(this);
			do_cfb_ajax_call(cfb_ajax_paramete['form_data'],cfb_ajax_paramete['col_class'],cfb_ajax_paramete['selected_attr'],cfb_ajax_paramete['sel_obj'],cfb_ajax_paramete[''],cfb_ajax_paramete['uid']);
		});

		jQuery(document).on("click", ".cfb-submit-btn", function (e) {
			e.preventDefault();
			get_cfb_form_field(this);
		});

		/* End CFS */
		if(typeof cars_filter_methods != 'undefined' && cars_filter_methods.cars_filter_with == 'yes'){
			/*
			* With ajax post dynamic filters
			*/
			$(document).on('click','#submit_all_filters',function(){
				form_data = get_form_field(this);
				do_ajax_call(form_data);
			});

			$(document).on('click','#pgs_price_filter_btn,#pgs_cars_search_btn,#pgs_cars_order,.catlog-layout',function(){
				if($(this).hasClass('catlog-layout')){
					form_data = get_form_field(this);
				}else{
					form_data = get_form_field(this);
					do_ajax_call(form_data);
				}
			});

			$('#pgs_cars_search').keypress(function(e){
				if(e.which == 13){//Enter key pressed
					$('#pgs_cars_search_btn').click();
				}
			});

			$('#pgs_cars_pp,#pgs_cars_orderby').on('change',function(){
				form_data = get_form_field(this);
				do_ajax_call(form_data);
			});

			$(document).on('click','#cars-pagination-nav ul li a',function(){
				var page_link = $(this).text();
				var prev = $(this).hasClass('prev');
				var next = $(this).hasClass('next');
				form_data = get_form_field(this);
				if(prev){
					$('.pagination-nav ul li span').each(function(){

						if($(this).hasClass('current')){
							var cuernt_page = $(this).text();
							page_link = parseInt(cuernt_page)-1;
						}
					});
				}
				if(next){
					$('.pagination-nav ul li span').each(function(){
						if($(this).hasClass('current')){
							var cuernt_page = $(this).text();
							page_link = parseInt(cuernt_page)+1;
						}
					});
				}

				jQuery.ajax({
					url: cardealer_js.ajaxurl,
					type: 'post',
					dataType: 'json',
					data:'action=cardealer_cars_filter_query&paged='+page_link+form_data+'&query_nonce='+cars_price_slider_params.cardealer_cars_filter_query_nonce,
					beforeSend: function(){
						jQuery('.filter-loader').html('<span class="filter-loader"><i class="cd-loader"></i></span>');
						jQuery('.pagination-loader').html('<span class="pagination-loader"><i class="cd-loader"></i></span>');
						if( jQuery('.cars-top-filters-box').length ){
							jQuery('.cars-top-filters-box').append('<span class="filter-loader"><i class="cd-loader"></i></span>');
						}
						jQuery('.select-sort-filters').prop('disabled',true);
						jQuery('#submit_all_filters').prop('disabled',true);
					},
					success: function(response){
						set_result_filters(response);
						jQuery('.filter-loader').html('');
						jQuery('.pagination-loader').html('');
						if( jQuery('.cars-top-filters-box .filter-loader').length ){
							jQuery('.cars-top-filters-box .filter-loader').remove();
						}
						jQuery('.all-cars-list-arch').html(response.data_html);
						$('#cars-pagination-nav').html(response.pagination_html);
						$('.cars-order').html(response.order_html);
						jQuery('.select-sort-filters').prop('disabled',false);
						jQuery('#submit_all_filters').prop('disabled',false);
						setTimeout(function(){
							set_layout_height();
						}, 1000);
						// masonry style
						if( jQuery('.masonry-main .all-cars-list-arch.masonry').length > 0 ){
							setTimeout(function(){
								cardealer_load_masonry(); // Reload shuffle masonry
							}, 1000);
						}
						transmission_dots();
						jQuery('select').niceSelect('update');
						if ( jQuery( '[data-toggle="tooltip"]' ).length > 0 ) {
							jQuery( '[data-toggle="tooltip"]' ).tooltip();
						}
						window.history.pushState(null, null, cars_price_slider_params.cars_form_url+'?paged='+page_link+form_data);
						if( jQuery( '.cars-top-filters-box' ).length ){
							jQuery("html, body").animate( { scrollTop: jQuery('.cars-top-filters-box').offset().top }, 200 );
						}
					},
					error: function(msg){
						alert('Something went wrong!');
						jQuery('.filter-loader').html('');
						jQuery('.pagination-loader').html('');
					}
				});
				return false;
			});

			$(document).on('click','#reset_filters', function( event ){
				event.preventDefault();
				var get_id = $(this).attr('id');
				var form_data = '';
				var sort_by = cardealer_obj.default_sort_by;//default dropdown sort option
				$('.select-sort-filters').each(function(){
					var sel_val = $(this).val('');
				});
				$('#pgs_cars_search').val('');
				$('#pgs_cars_pp').val($("#pgs_cars_pp option:first").val());
				$('#pgs_cars_orderby').val(sort_by);

				var pgs_min_price = jQuery( '#pgs_min_price' ).data( 'min' ),
					pgs_max_price = jQuery( '#pgs_max_price' ).data( 'max' ),
					pgs_current_min_price = parseInt( pgs_min_price, 10 ),
					pgs_current_max_price = parseInt( pgs_max_price, 10 );
				var currency_symbol = cars_price_slider_params.currency_symbol;
				$('#pgs_min_price').val(pgs_min_price);
				$('#pgs_max_price').val(pgs_max_price);
				switch( cars_price_slider_params.currency_pos ){
					case 'left':
						jQuery('#dealer-slider-amount').val(currency_symbol + cardealer_addCommas(pgs_current_min_price) + " - " + currency_symbol + cardealer_addCommas(pgs_current_max_price));
					break;
					case 'left-with-space':
						jQuery('#dealer-slider-amount').val(currency_symbol + ' ' + cardealer_addCommas(pgs_current_min_price) + " - " + currency_symbol + cardealer_addCommas(pgs_current_max_price));
					break;
					case 'right-with-space':
						jQuery('#dealer-slider-amount').val(  cardealer_addCommas(pgs_current_min_price) +currency_symbol+ " - " +   cardealer_addCommas(pgs_current_max_price) + ' ' + currency_symbol );
					break;
					default:
						jQuery('#dealer-slider-amount').val(  cardealer_addCommas(pgs_current_min_price) +currency_symbol+ " - " +   cardealer_addCommas(pgs_current_max_price) + currency_symbol );
				}
				jQuery( document.body ).trigger( 'price_slider_updated', [ pgs_current_min_price, pgs_current_max_price ] );

				if(cars_year_range_slider_params.is_year_range_active){
					// Year range slider uses jquery ui
					var pgs_year_range_min = jQuery( '#pgs_year_range_min' ).data( 'yearmin' ),
						pgs_year_range_max = jQuery( '#pgs_year_range_max' ).data( 'yearmax' ),
						pgs_current_min_year = parseInt( pgs_year_range_min, 10 ),
						pgs_current_max_year = parseInt( pgs_year_range_max, 10 );
					$('#pgs_year_range_min').val(pgs_year_range_min);
					$('#pgs_year_range_max').val(pgs_year_range_max);

					jQuery('#dealer-slider-year-range').val(pgs_current_min_year + " - " + pgs_current_max_year);
					jQuery( document.body ).trigger( 'year_range_slider_updated', [ pgs_current_min_year, pgs_current_max_year ] );
				}

				form_data = get_form_field(this);
				do_ajax_call(form_data);
				dealer_price_filter();

				if(cars_year_range_slider_params.is_year_range_active){
					dealer_year_range_filter();
				}
			});
		} else {

			$(document).on('click','#submit_all_filters',function(){
				get_form_field(this);
			});

			$('#pgs_price_filter_btn,#pgs_cars_search_btn,#pgs_cars_order,.catlog-layout').on('click',function(){
				get_form_field(this);
			});

			$('#pgs_cars_search').keypress(function(e){
				if(e.which == 13){//Enter key pressed
					$('#pgs_cars_search_btn').click();
				}
			});

			$('#pgs_cars_pp,#pgs_cars_orderby').on('change',function(){
				get_form_field(this);
			});

			$(document).on('click','#reset_filters', function( event ){
				event.preventDefault();
				var get_id = $(this).attr('id');
				var form_data = '';
				$('.select-sort-filters').each(function(){
					var sel_val = $(this).val('');
				});
				$('#pgs_cars_search').val('');
				$('<form>', {
					"id": "getCarsData",
					"html": form_data,
					"action": cars_price_slider_params.cars_form_url
				}).appendTo(document.body).submit();
			});
		}
		/* end */

		dealer_price_filter();
		dealer_year_range_filter();

		/**************************************************
			Financing Calculator
		**************************************************/
		$('.do_calculator').click(function(){

			if ( typeof cars_price_slider_params === 'undefined' ) {
				return false;
			}
			var form_id = $(this).attr('data-form-id');
			var loan_amount = $('#loan-amount-'+form_id).val();
			var down_payment = $('#down-payment-'+form_id).val();
			var interest_rate = $('#interest-rate-'+form_id).val();
			var period = $('#period-'+form_id).val();
			var currency_symbol = cars_price_slider_params.currency_symbol;

			var t = down_payment;
			var I = interest_rate;
			var N = period;
			var P = loan_amount;

			var vTempP = String(P).replace(currency_symbol, '').replace(',', '');
			if (!fnisNum(vTempP)) {
				alert("Please enter a valid number for the Loan Amount (P).");
				document.getElementById('loan-amount-'+form_id).focus();
				return false;
			}

			var vTempT = String(t).replace(currency_symbol, '').replace(',', '');
			if (!fnisNum(vTempT)) {
				alert("Please enter a valid number for the Down Payment (P).");
				document.getElementById('down-payment-'+form_id).focus();
				return false;
			}

			if (!fnisNum(I)) {
				alert("Enter an Interest Rate (r).");
				document.getElementById('interest-rate-'+form_id).focus();
				return false;
			}
			if (!fnisNum(N)) {
				alert("Please enter the Total Number of Payments (N).");
				document.getElementById('period-'+form_id).focus();
				return false;
			}

			P = vTempP;
			t = vTempT;
			var X = (P - t);
			var Y = ((I / 100) / 12);
			var z = (Math.pow((1 + ((I / 100) / 12)), -N));
			var a = (X * Y);
			var b = (1 - z);
			var Tot = (a / b);
			var ans2 = Tot.toFixed(2);

			document.getElementById('txtPayment-'+form_id).innerHTML = currency_symbol + ans2 + '<sup>&#47;mo</sup>';
		});

		$('.do_calculator_clear').click(function(){
			var form_id = $(this).attr('data-form-id');
			$('#loan-amount-'+form_id).val('');
			$('#down-payment-'+form_id).val('');
			$('#interest-rate-'+form_id).val('');
			$('#period-'+form_id).val('');
			$('#txtPayment-'+form_id).html('');
		});

		$('.view-list').on('click',function(){
			$('.view-grid').removeClass('sel-active');
			$('.view-list').addClass('sel-active');
			$("div.cars-loop").fadeOut(300, function() {
				$(this).addClass("cars-list").fadeIn(300);
			});
			cookies.set('cars_grid', 'no');
		});

		$('.view-grid').on('click',function(){
			$( '.view-list' ).removeClass('sel-active');
			$( '.view-grid' ).addClass('sel-active');
			$("div.cars-loop").fadeOut(300, function() {
				$(this).removeClass("cars-list").fadeIn(300);
			});
			cookies.set('cars_grid', 'yes');
		});
		transmission_dots();
		setTimeout(function(){
			set_layout_height();
		}, 1000);
		/*
		* Active first tab in cars details page
		*/
		$('#tabs ul li').first().trigger('click');

		/********************************************
		:: Search cars with autocomplte for listing
		*********************************************/

		if(document.getElementById('pgs_cars_search')){
			jQuery( '#pgs_cars_search' ).autocomplete({
				search: function(event, ui) {
					jQuery('.auto-compalte-list ul').empty();
				},
				source: function( request, response ) {
					jQuery.ajax({
						url: cardealer_js.ajaxurl,
						type: 'POST',
						dataType: "json",
						data: {'action': 'pgs_cars_list_search_auto_compalte','search': request.term, 'ajax_nonce': cars_price_slider_params.pgs_cars_list_search_auto_compalte_nonce},
						success: function( resp ) {
							response( jQuery.map( resp, function( result ) {
								return {
									status: result.status,
									image: result.image,
									title: result.title,
									link_url: result.link_url,
									msg: result.msg
								};
							}));
						}
					});
				},
				minLength: 2,
			}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {

				var html = '';
				if(item.status){
					html += '<a href="'+item.link_url+'">';
					html += '<div class="search-item-container">';
					if(item.image){
						html += item.image;
					}
					html += item.title;
					html += '</div>';
					html += '</a>';
				} else {
					html += item.msg;
				}
				return jQuery( "<li></li>" )
					.data( "ui-autocomplete-item", item )
					.append(html)
					.appendTo(jQuery('.auto-compalte-list ul'));
			};
		}

		/********************************************
		:: Search for car listing page with full width
		*********************************************/
		jQuery('.pgs_cars_search_btn').on("click", function () {
			jQuery('#pgs_cars_search').val('');
			jQuery('.pgs_cars_search').toggle();
			jQuery('.auto-compalte-list ul').empty();
			return false;
		});

		$('#pgs_cars_pp_sold,#pgs_cars_orderby_sold').on('change',function(){
			var form_data = get_sold_filter_fields(this);
			$(form_data).appendTo('.sold-filter-frm');
			$('.sold-filter-frm').submit();
		});

		$('#pgs_cars_order_sold,#pgs_price_filter_btn-sold,.catlog-layout-sold').on('click',function(e){
			e.preventDefault();
			var form_data = get_sold_filter_fields(this);
			$(form_data).appendTo('.sold-filter-frm');
			$('.sold-filter-frm').submit();
		});
	});

	/**
	 * get cars sold page template filters fields function
	 */
	function get_sold_filter_fields($this){

		var form_data = '';
		var order_sold = '';
		if($($this).hasClass('cars-order-sold')){
			order_sold = $($this).attr('data-order');
		} else {
			order_sold = $('#pgs_cars_order_sold').attr('data-current_order');
		}
		form_data += '<input type="hidden" name="cars_order"  value="'+order_sold+'"/>';

		var cars_grid = '';
		var sold_layout = '';

		if($($this).hasClass('catlog-layout-sold')){
			if(jQuery($this).hasClass('view-grid-sold')){
				cars_grid = 'yes';
			}
			if(jQuery($this).hasClass('view-list-sold')){
				cars_grid = "no";
			}
			sold_layout = $($this).attr('data-id');
		} else {
			var sts = jQuery('.view-grid-sold').attr('data-sts');
			if(sts == 'act'){
				cars_grid = 'yes';
			} else {
				cars_grid = "no";
			}

			if(sts == 'act'){
				sold_layout = 'view-grid-full';
			} else {
				sold_layout = 'view-list-full';
			}
		}
		form_data += '<input type="hidden" name="cars_grid"  value="'+cars_grid+'"/>';
		form_data += '<input type="hidden" name="lay_style"  value="'+sold_layout+'"/>';
		return form_data;
	}

	function transmission_dots(){
		jQuery(".car-transmission-dots").dotdotdot({
			maxLength:5
		});
	}

	$( window ).resize(function() {
		set_layout_height();
	});

	function set_layout_height(){
		if( jQuery("section.product-listing.default .car-item").length > 0 && jQuery('.masonry-main .all-cars-list-arch.masonry').length == 0) {
			// Select and loop the container element of the elements you want to equalise
			var highestBox = 0;

			// Reset height of elements
			$( '.car-item' ).height('');

			// Select and loop the elements you want to equalise
			jQuery('.car-item').each(function(){
				// If this box is higher than the cached highest then store it
				if(jQuery(this).height() > highestBox) {
					highestBox = jQuery(this).height();
				}
			});
			/* Set the height of all those children to whichever was highest*/
			if( jQuery("body").hasClass("page-template-sold-cars") || jQuery('.all-cars-list-arch').length > 0 ) {
				jQuery('.car-item').height(highestBox);
			}
		}
	}


	function fnisNum(x) {
		var filter = /(^\d+\.?$)|(^\d*\.\d+$)/;
		if (filter.test(x)) {
			return true;
		}
		return false;
	}

	function dealer_price_filter(){

			if ( typeof cars_price_slider_params === 'undefined' ) {
				return false;
			}

			// Price slider uses jquery ui
			var pgs_min_price = jQuery( '#pgs_min_price' ).data( 'min' ),
				pgs_max_price = jQuery( '#pgs_max_price' ).data( 'max' ),
				pgs_current_min_price = parseInt( pgs_min_price, 10 ),
				pgs_current_max_price = parseInt( pgs_max_price, 10 );

			if ( cars_price_slider_params.min_price ) {
				pgs_current_min_price = parseInt( cars_price_slider_params.min_price, 10 );
			}
			if ( cars_price_slider_params.max_price ) {
				pgs_current_max_price = parseInt( cars_price_slider_params.max_price, 10 );
			}
			jQuery( document.body ).bind( 'pgs_price_slider_create pgs_price_slider_slide', function( event, min, max ) {
				var currency_symbol = cars_price_slider_params.currency_symbol;

				switch( cars_price_slider_params.currency_pos ){
						case 'left':
							jQuery('#dealer-slider-amount').val(currency_symbol + cardealer_addCommas(min) + " - " + currency_symbol + cardealer_addCommas(max));
						break;
						case 'left-with-space':
							jQuery('#dealer-slider-amount').val(currency_symbol + ' ' + cardealer_addCommas(min) + " - " + currency_symbol + ' ' + cardealer_addCommas(max));
						break;
						case 'right-with-space':
							jQuery('#dealer-slider-amount').val( cardealer_addCommas(min) + ' ' + currency_symbol + " - "  + cardealer_addCommas(max) + ' ' + currency_symbol);
						break;
						default:
							jQuery('#dealer-slider-amount').val( cardealer_addCommas(min) + currency_symbol + " - "  + cardealer_addCommas(max) + currency_symbol);
					}
				jQuery( document.body ).trigger( 'price_slider_updated', [ min, max ] );
			});

			if ( jQuery.isFunction(jQuery.fn.slider) ) {
				var pgs_price_range_step = jQuery('#pgs_max_price').data('step');
				var range_step = 100;
				if(pgs_price_range_step){
					range_step = pgs_price_range_step;
				}
				jQuery("#slider-range,#slider-range-2").slider({
					range: true,
					min: pgs_min_price,
					max: pgs_max_price,
					values: [pgs_current_min_price, pgs_current_max_price],
					step: range_step,
					create: function() {
						jQuery( '#pgs_min_price' ).val( pgs_current_min_price );
						jQuery( '#pgs_max_price' ).val( pgs_current_max_price );
						jQuery( document.body ).trigger( 'pgs_price_slider_create', [ pgs_current_min_price, pgs_current_max_price ] );
					},
					slide: function( event, ui ) {
						var min = ui.values[0],
							max = ui.values[1];
						jQuery('#pgs_min_price').val(min);
						jQuery('#pgs_max_price').val(max);
						jQuery( document.body ).trigger( 'pgs_price_slider_slide', [ ui.values[0], ui.values[1] ] );
					},
					change: function( event, ui ) {
						jQuery( document.body ).trigger( 'pgs_price_slider_change', [ ui.values[0], ui.values[1] ] );
					}
				});
			}
	}

	function dealer_year_range_filter(){
		if ( typeof cars_year_range_slider_params === 'undefined' ) {
			return false;
		}
		if(document.getElementById('slider-year-range')){
			// Year range slider uses jquery ui
			var pgs_year_range_min = jQuery( '#pgs_year_range_min' ).data( 'yearmin' ),
				pgs_year_range_max = jQuery( '#pgs_year_range_max' ).data( 'yearmax' ),
				pgs_current_min_year = parseFloat( pgs_year_range_min, 10 ),
				pgs_current_max_year = parseFloat( pgs_year_range_max, 10 );

			if ( cars_year_range_slider_params.min_year ) {
				pgs_current_min_year = parseFloat( cars_year_range_slider_params.min_year, 10 );
			}
			if ( cars_year_range_slider_params.max_year ) {
				pgs_current_max_year = parseFloat( cars_year_range_slider_params.max_year, 10 );
			}
			jQuery( document.body ).bind( 'pgs_year_range_slider_create pgs_year_range_slider_slide', function( event, min, max ) {
				//jQuery('#dealer-slider-year-range').val( min + " - " + max);
				jQuery('#dealer-slider-year-range').each(function(){
					jQuery(this).val( min + " - " + max);
				});
				jQuery( document.body ).trigger( 'year_range_slider_updated', [ min, max ] );
			});
			if ( jQuery.isFunction(jQuery.fn.slider) ) {
				jQuery(".slider-year-range").each(function() {
					jQuery(this).slider({
						range: true,
						min: pgs_year_range_min,
						max: pgs_year_range_max,
						values: [pgs_current_min_year, pgs_current_max_year],
						create: function() {
							jQuery( '#pgs_year_range_min' ).val( pgs_current_min_year );
							jQuery( '#pgs_year_range_max' ).val( pgs_current_max_year );
							jQuery( document.body ).trigger( 'pgs_year_range_slider_create', [ pgs_current_min_year, pgs_current_max_year ] );
						},
						slide: function( event, ui ) {
							var min = ui.values[0],
								max = ui.values[1];
							jQuery('#pgs_year_range_min').val(min);
							jQuery('#pgs_year_range_max').val(max);
							jQuery( document.body ).trigger( 'pgs_year_range_slider_slide', [ ui.values[0], ui.values[1] ] );
						},
						change: function( event, ui ) {
							jQuery( document.body ).trigger( 'pgs_year_range_slider_change', [ ui.values[0], ui.values[1] ] );
						},
						stop: function( event, ui ) {
							var is_cfb = jQuery(this).attr('data-cfb');
							if(is_cfb == 'yes'){
								var cfb_ajax_paramete = get_cfb_ajax_parameter_with_cfb_type(this);
								do_cfb_ajax_call(cfb_ajax_paramete['form_data'],cfb_ajax_paramete['col_class'],cfb_ajax_paramete['selected_attr'],cfb_ajax_paramete['sel_obj'],cfb_ajax_paramete[''],cfb_ajax_paramete['uid']);
							} else {
								form_data = get_form_field(this);
								do_ajax_call(form_data);
							}
						}
					});
				});
			}
		}
	}

	function get_cfb_form_field($this){
		var form_data = '';
		var form_data_ajax = '';
		jQuery('.custom-filters-box').each(function(){
			var tid = jQuery(this).attr('data-id');
			var sel_val = jQuery(this).val();

			if(sel_val != ""){
				form_data += '<input type="text" name="'+tid+'" value="' + sel_val + '" />';
			}
		});

		var pgs_min_price = jQuery('#pgs_min_price').val();
		var pgs_max_price = jQuery('#pgs_max_price').val();
		form_data += '<input type="text" name="min_price" value="' + pgs_min_price + '" />';
		form_data += '<input type="text" name="max_price" value="' + pgs_max_price + '" />';

		if(document.getElementById('pgs_year_range_min')){
			var pgs_year_range_min = jQuery('#pgs_year_range_min').val();
			var pgs_year_range_max = jQuery('#pgs_year_range_max').val();
			form_data += '<input type="text" name="min_year" value="' + pgs_year_range_min + '" />';
			form_data += '<input type="text" name="max_year" value="' + pgs_year_range_max + '" />';
		}
		jQuery('<form>', {
			"id": "getCarsData",
			"html": form_data,
			"action": cardealer_obj.cars_url
		}).appendTo(document.body).submit();
	}

	// Code for GRID / LIST view layout in cookie variable
	$( window ).load(function() {
		/* Change Layout */
		var $cars_grid = cookies.get( 'cars_grid' );
		if( $cars_grid == 'yes' ){
			$('.view-grid').addClass('sel-active');
			$('.view-list').removeClass('sel-active');
		}else{
			$('.view-grid').removeClass('sel-active');
			$('.view-list').addClass('sel-active');
		}
		/* End Change Layout */
	});

	function cardealer_addCommas(nStr){
		return cardealer_number_format(nStr, parseInt(cars_price_slider_params.decimal_places), cars_price_slider_params.decimal_separator_symbol, cars_price_slider_params.thousand_seperator_symbol);
	}

	function cardealer_number_format(number, decimals, decPoint, thousandsSep){
		decimals = decimals || 0;
		number = parseFloat(number);

		if(!decPoint || !thousandsSep){
			decPoint = '.';
			thousandsSep = ',';
		}

		var roundedNumber = Math.round( Math.abs( number ) * ('1e' + decimals) ) + '';
		var numbersString = decimals ? roundedNumber.slice(0, decimals * -1) : roundedNumber;
		var decimalsString = decimals ? roundedNumber.slice(decimals * -1) : '';
		var formattedNumber = "";

		while(numbersString.length > 3){
			formattedNumber = thousandsSep + numbersString.slice(-3) + formattedNumber;
			numbersString = numbersString.slice(0,-3);
		}
		return (number < 0 ? '-' : '') + numbersString + formattedNumber + (decimalsString ? (decPoint + decimalsString) : '');
	}

	/* CODE FOR LAZY LOAD VEHICLES START */

	jQuery(document).ready( function(){
		if(jQuery('#cd-scroll-to').length == 0){
			return;
		}

		var element_position = jQuery('#cd-scroll-to').offset().top;
		var screen_height = jQuery(window).height();
		var activation_offset = 1;//determines how far up the the page the element needs to be before triggering the function
		var activation_point = element_position - (screen_height * activation_offset);
		var max_scroll_height = jQuery('body').height() - screen_height - 5;//-5 for a little bit of buffer

		//Does something when user scrolls to it OR
		//Does it when user has reached the bottom of the page and hasn't triggered the function yet
		jQuery(document).on('scroll', function() {
			if ($(window).data('ajax_in_progress') === true){
				return;
			}
			var records = jQuery('.all-cars-list-arch').attr('data-records');
			if( records !== null && ( records == 0 || records == -2 ) ){
				$(window).data('records_processed', 0);
				$(window).data('paged', 2);
				jQuery(".all-cars-list-arch").attr('data-paged', 2);
				return;
			}

			if (! $(window).data('paged')){
				$(window).data('paged', 2);
			}
			if (! $(window).data('records_processed')){
				$(window).data('records_processed', 0);
			}
			var y_scroll_pos = window.pageYOffset + 1000;
			var element_in_view = y_scroll_pos > activation_point;
			var has_reached_bottom_of_page = max_scroll_height <= y_scroll_pos && !element_in_view;

			if(element_in_view || has_reached_bottom_of_page) {

				var currentRequest = null;
				var query_params = cd_getUrlVars();
				if( jQuery(".cars-total-vehicles ul.stripe-item li").length > 0 ){
					var filter_att = [];
					var query_params_obj = JSON.parse(query_params);
					jQuery(".cars-total-vehicles ul.stripe-item li").each(function(){
						var filterAttr = jQuery(this).attr('data-type');
						if( typeof query_params_obj.filterAttr == 'undefined'){
							var filterAttr = jQuery(this).attr('data-type');
							var obj = {};
							obj[filterAttr] = jQuery(this).find('span').attr('data-key');
							jQuery.extend(query_params_obj, obj);
						}

					});
					query_params = JSON.stringify(query_params_obj);
				}

				var paged = $(window).data('paged');
				$(window).data('ajax_in_progress', true);
				var data = {
					'action' : 'cardealer_load_more_vehicles',
					'filter_vars' : query_params,
					'paged' : $(window).data('paged'),
					'records_processed' : $(window).data('records_processed'),
					'ajax_nonce': cars_price_slider_params.load_more_vehicles_nonce
				};
				currentRequest = jQuery.ajax({
					url: cardealer_js.ajaxurl,
					type: 'post',
					dataType: 'json',
					data: data,
					beforeSend: function(){
						jQuery(".all-cars-list-arch").after('<div class="col-md-12 text-center cd-inv-loader"><span class="cd-loader"></span><div>');
						if(currentRequest != null) {
							currentRequest.abort();
						}
					},
					success: function(response){
						jQuery(".cd-inv-loader").remove();
						if(response.status == 2){
							if( jQuery(".load-status").length == 0 ){
								jQuery(".all-cars-list-arch").after(response.data_html);
							}
							jQuery(".load-status").fadeIn(2000);
							jQuery(".load-status").fadeOut(2000);
						} else {
							jQuery(".all-cars-list-arch").append(response.data_html);
							cardealer_load_masonry(); // Reload shuffle masonry
							setTimeout(function(){
								cardealer_load_masonry(); // Reload shuffle masonry
							}, 1000);
						}

						jQuery(".all-cars-list-arch").attr('data-paged', response.paged);

						$(window).data('ajax_in_progress', false);
						$(window).data('paged', response.paged);
						$(window).data('records_processed', response.records_processed);

						var cars_pp = $(window).data('records_processed');
						var filterURL = '?cars_pp='+cars_pp;
						jQuery.each(JSON.parse(query_params), function(index, value){
							if(index != 'cars_pp'){
								filterURL += '&' + index + '=' + value;
							}
						});

						var pgs_min_price = jQuery('#pgs_min_price').val();
						var pgs_max_price = jQuery('#pgs_max_price').val();
						if (typeof pgs_max_price !== typeof undefined && pgs_max_price !== false && typeof pgs_min_price !== typeof undefined && pgs_min_price !== false) {
							filterURL += '&max_price' + '=' + pgs_max_price;
							filterURL += '&min_price' + '=' + pgs_min_price;
						}

						if( jQuery('.all-cars-list-arch').attr('data-records') == -1 ){
							jQuery('.all-cars-list-arch').attr('data-records', -2);
						} else if( jQuery('.all-cars-list-arch').attr('data-records') >= response.records_processed ){
							jQuery('.all-cars-list-arch').attr('data-records', -1);
						} else {
							jQuery('.all-cars-list-arch').attr('data-records', cars_pp);
						}

						// add value in per page select drop down if not exist
						if($("#pgs_cars_pp option[value='"+cars_pp+"']").length == 0){
							jQuery("#pgs_cars_pp").append("<option value="+ cars_pp +">"+ cars_pp + "</option>");
						}
						$('#pgs_cars_pp option').filter(function() { return $.trim( $(this).text() ) == cars_pp; }).attr('selected',true);
						jQuery('select#pgs_cars_pp').niceSelect('update');
						window.history.pushState(null, null, cars_price_slider_params.cars_form_url+filterURL);
						lazyload();
						if( jQuery('[data-toggle="tooltip"]').length > 0 ){
							jQuery('[data-toggle="tooltip"]').tooltip();
						}
					},
					error: function(msg){
						//alert('Something went wrong!');
					}
				});
			}
		});
	});

	jQuery('section.lazyload .cars_filters h6 a').on('click', function(){
		if( jQuery(this).attr('aria-expanded') == 'false' ){
			jQuery(this).find(".fa-plus").removeClass("fa-plus").addClass("fa-minus");
		} else {
			jQuery(this).find(".fa-minus").removeClass("fa-minus").addClass("fa-plus");
		}

	});


	function cd_getUrlVars(){
		var vars = {};
		var hash;
		var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');

		vars['cars_orderby'] = jQuery('#pgs_cars_orderby').val();
		vars['cars_order'] = jQuery('#pgs_cars_order').data('current_order');
		for(var i = 0; i < hashes.length; i++){
			hash = hashes[i].split('=');
			vars[hash[0]] = hash[1];
		}
		return JSON.stringify(vars);
	}
} )( jQuery );
