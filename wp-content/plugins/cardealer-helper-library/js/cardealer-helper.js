jQuery(document).ready(function($) {
	// Check element exists.
	$.fn.exists = function () {
		return this.length > 0;
	};

    /* ---------------------------------------------
     Promocode
     --------------------------------------------- */
	jQuery('.promocode-btn').on('click',function(){
        var form_id=jQuery(this).attr('data-fid');
		var form=jQuery("#"+form_id);
        var promocode_action = jQuery("#"+form_id+' .promocode_action').val();
        var promocode_nonce = jQuery("#"+form_id+' .promocode_nonce').val();
        var promocode = jQuery("#"+form_id+' .promocode').val();
        if(promocode == 'Promocode'){
            promocode = '';
        }
        jQuery.ajax({
            type:"POST",
			url: cardealer_js.ajaxurl,
			data:{action:promocode_action,promocode_nonce:promocode_nonce,promocode:promocode},
            dataType:'json',
			beforeSend: function() {
                jQuery('#'+form_id+' .spinimg').html('<span class="cd-loader"></span>');
            },
			success: function(responce){
				jQuery('#'+form_id+' .promocode-msg').removeClass('error_msg');
				jQuery('#'+form_id+' .spinimg').html('');
                if(responce.status == 'success'){
                    form_data = '<input type="hidden" name="promocode" value="asasas' + promocode + '" />';
                    jQuery('<form>', {
                        "html": form_data,
                        "action": responce.url,
                        "method":'POST'
                    }).appendTo(document.body).submit();
				} else {
					jQuery('#'+form_id+' .promocode-msg').show();
				    jQuery('#'+form_id+' .promocode-msg').html(responce.msg);
				}

			},
			error: function(responce){
				jQuery('#'+form_id+' .promocode-msg').addClass('error_msg');
                jQuery('#'+form_id+' .promocode-msg').html(responce.msg);
				jQuery('#'+form_id+' .promocode-msg').html(msg);
				jQuery('#'+form_id+' .promocode-msg').show();
			}
		});
        return false;
	});

    jQuery(document).on('click','.geo-closed',function(){
        var minutes = 30;
        var tMinutes = new Date(new Date().getTime() + minutes * 60 * 1000);
        cookies.set( 'geo_closed' , true, { expires: tMinutes });
        jQuery('.geo-bar').hide();
    });

	/*Code for search filter box*/
	jQuery("select.search-filters-box").prop('selectedIndex',0);
	jQuery("select.search-filters-box").niceSelect('update');
	jQuery(".cardealer-tabs input.vehicle_location").val('');
	jQuery(document).on('change', '.search-filters-box', function(){
		var attributes = {};
		var taxAttrs = {};
		var total_vehicles = 0;
		var parent = jQuery(this).parents('.vehicle-search-section').addClass('parentsssss');
		attributes['car_year'] = jQuery(this).parents('.cardealer-tabcontent').find('select[data-id=car_year]');
		attributes['car_make'] = jQuery(this).parents('.cardealer-tabcontent').find('select[data-id=car_make]');
		attributes['car_model'] = jQuery(this).parents('.cardealer-tabcontent').find('select[data-id=car_model]');
		var matching_vehicles = jQuery(this).parents('.cardealer-tabcontent').find('p.matching-vehicles');

		jQuery.each( attributes, function(taxonomy, attr){
			taxAttrs[taxonomy] = jQuery(attr).val();
		});

		var paramData = {
			action: 'cdhl_get_search_attr',
			tax_data: taxAttrs,
			term_tax: jQuery(this).data('id'),
			term_value: jQuery(this).val(),
			condition: jQuery(this).parents('.cardealer-tabcontent').data('condition')
		};
		jQuery.ajax({
			url : cardealer_js.ajaxurl,
			type:'post',
			dataType:'json',
			data: paramData,
			beforeSend: function(){
				jQuery(parent).find('.filter-loader').html('<span class="filter-loader"><i class="cd-loader"></i></span>');
				jQuery(parent).find('.pagination-loader').html('<span class="pagination-loader"><i class="cd-loader"></i></span>');
				jQuery(parent).find('.search-filters-box').prop('disabled',true);
				jQuery(parent).find('.csb-submit-btn').prop('disabled',true);
			},
			success: function(response){
				if( response.status == true ){
					if( response.attr_array.length > 0 ){
						jQuery(response.attr_array).each( function(index, attr){
							jQuery(attributes[attr.taxonomy]).html(jQuery("<option />").val('').text(attr.tax_label));
							total_vehicles = attr.vehicles_matched;
							jQuery(attr.tax_terms).each( function(index, terms){
								if( index == 0 && taxAttrs[attr.taxonomy] != '' ){
									jQuery(attributes[attr.taxonomy]).append(jQuery("<option />").attr('selected','selected').val(terms.slug).text(terms.name));
								} else {
									jQuery(attributes[attr.taxonomy]).append(jQuery("<option />").val(terms.slug).text(terms.name));
								}
							});
							jQuery(attributes[attr.taxonomy]).prop('disabled',false);
							jQuery(attributes[attr.taxonomy]).niceSelect('update');
						});
						matching_vehicles.html(total_vehicles);
					}
				}
				//console.log(response);
			},
			complete: function(){
				jQuery(parent).find('.filter-loader').html('');
                jQuery(parent).find('.pagination-loader').html('');
                jQuery(parent).find('.search-filters-box').prop('disabled',false);
                jQuery(parent).find('.csb-submit-btn').prop('disabled',false);
			},
			error: function(){
				console.log('Something went wrong!');
			}
		});
	});

	// Submit filters
	jQuery( ".vehicle-search-section form" ).each(function( index ) {
		var search_form = jQuery( this ),
			search_btn  = jQuery( search_form ).find( '.csb-submit-btn' );

		jQuery(document).on('keyup keypress', '.vehicle-search-section form, .vehicle-search-section form input', function(e) {
		  jQuery( search_form ).trigger( 'submit' );
		});

		jQuery( search_btn ).on( 'click', function(){
			jQuery( search_form ).trigger( 'submit' );
		});

		jQuery( search_form ).on( 'submit', function(e){
			console.log( search_form );
			validated = true;
			jQuery( search_form ).find( 'input, select' ).each( function(){
				if( jQuery(this).val() == '' ){
					jQuery(this).attr('disabled','disabled');
				}
			});
			jQuery( search_form ).attr( 'action', cars_price_slider_params.cars_form_url );
			if (validated) {
				jQuery(search_form).unbind('submit').submit();
			}
		});
	});

	// video slider shortcode scripts starts
	if( jQuery('.sliderMain').length > 0 ){
		jQuery('.sliderMain').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			fade: true,
			asNavFor: '.sliderSidebar',
			autoplay: false,
			autoplaySpeed: 3000
		});
		jQuery('.sliderSidebar').slick({
			slidesToShow: 5,
			slidesToScroll: 1,
			asNavFor: '.sliderMain',
			dots: false,
			centerMode: false,
			focusOnSelect: true,
			vertical: false,
			arrows: true,
			responsive: [{
				breakpoint: 980, // tablet breakpoint
				settings: {
					slidesToShow: 4,
					slidesToScroll: 4
				}
			},
			{
				breakpoint: 480, // mobile breakpoint
				settings: {
					slidesToShow: 3,
					slidesToScroll: 3
				}
			}]
		});
	}
	//bind our event here, it gets the current slide and pauses the video before each slide changes.
	jQuery(".sliderMain").on("beforeChange", function(event, slick, currentSlide) {
      var slideType, player, command;
	  currentSlide = jQuery(slick.$slider).find(".slick-current");
      console.log('currentSlide :'+currentSlide);
      //determine which type of slide this, via a class on the slide container. This reads the second class, you could change this to get a data attribute or something similar if you don't want to use classes.
      slideType = currentSlide.attr("class").split(" ")[1];

      //get the iframe inside this slide.
      player = currentSlide.find("iframe").get(0);

      if (slideType == "vimeo") {
        command = {
          "method": "pause",
          "value": "true"
        };
      } else {
        command = {
          "event": "command",
          "func": "pauseVideo"
        };
      }

      //check if the player exists.
      if (player != undefined) {
        //post our command to the iframe.
        player.contentWindow.postMessage(JSON.stringify(command), "*");
      }
    });
	// video slider shortcode scripts ends

	if ( $( '.widget.widget-vehicle-categories select.vehicle-categories-dropdown' ).exists() ) {
		$( document ).on( 'change', '.widget.widget-vehicle-categories select.vehicle-categories-dropdown', function ( event ) {
			if ( $(this).val() != '' ) {
				window.location.href = $(this).find(':selected').data('uri');
			}
		} );
	}
});

// Location sharing for geo fencing
if(parseInt(cdhl_obj.geofenc) > 0){
    var msg = '';
    var geo_lat = cookies.get('geo_lat');
    var geo_lng = cookies.get('geo_lng');
    if(!cookies.get('geo_lat')){
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            msg = "Geolocation is not supported by this browser.";
        }
    }
}

function showPosition(position) {
    var minutes = 30;
    var tMinutes = new Date(new Date().getTime() + minutes * 60 * 1000);

    if(!cookies.get('geo_lat')){
        cookies.set( 'geo_lat' , position.coords.latitude, { expires: tMinutes });
        cookies.set( 'geo_lng' , position.coords.longitude, { expires: tMinutes });
        cookies.set( 'geo_closed' , true, { expires: tMinutes });
    }

    var data = {
    	'action': 'findGeolocation',
        'lat': position.coords.latitude,
        'lng': position.coords.longitude
    };

    var html = '';
    jQuery.ajax({
        type: "POST",
        url: cardealer_js.ajaxurl,
        data: data,
        dataType: 'json',
        success: function(response) {
            if(response.status == 'success'){
                cookies.set( 'geo_msg' , response.content, { expires: tMinutes });
                html += '<div class="geo-bar">';
                    html += '<span class="geo-closed">&times;</span>';
                    html += '<div class="container">';
                        html += '<div class="row">';
                            html += '<div class="col-lg-12 col-sm-12">';
                                html += '<marquee class="geo-fencing" behavior="scroll" direction="left" scrollamount="5" style="width:100%; height:100%; vertical-align:middle; cursor:pointer;" onmouseover="javascript:this.setAttribute(\'scrollamount\',\'0\');" onmouseout="javascript:this.setAttribute(\'scrollamount\',\'5\');"><div class="geo-content">'+response.content+'</div></marquee>';
                            html += '</div>';
                        html += '</div>';
                     html += '</div>';
                html += '</div>';
                jQuery('#page').prepend(html);
                jQuery('.geo-bar').show();
            }
        }
    });
}

function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            msg = "User denied the request for Geolocation."
            break;
        case error.POSITION_UNAVAILABLE:
            msg = "Location information is unavailable."
            break;
        case error.TIMEOUT:
            msg = "The request to get user location timed out."
            break;
        case error.UNKNOWN_ERROR:
            msg = "An unknown error occurred."
            break;
    }
}

// Promocode Print Shortcode
jQuery(document).on("click", ".pgs_print_btn", function () {
	var contents = jQuery('#'+jQuery(this).data('print_id')).html();
	var frame1 = jQuery('<iframe />');
	frame1[0].name = "frame1";
	frame1.css({ "position": "absolute", "top": "-1000000px" });
	jQuery("body").append(frame1);
	var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
	frameDoc.document.open();
	//Create a new HTML document.
	frameDoc.document.write('<html><head><title>Promocode</title>');
	frameDoc.document.write('</head><body>');
	//Append the DIV contents.
	frameDoc.document.write(contents);
	frameDoc.document.write('</body></html>');
	frameDoc.document.close();
	setTimeout(function () {
		window.frames["frame1"].focus();
		window.frames["frame1"].print();
		frame1.remove();
	}, 500);
});

// shortcode tabs
if( jQuery(".cardealer-tabs li[data-tabs]").length ){
	jQuery(".cardealer-tabs").each(function(){
		var currentTabSection = jQuery(this);
		var $tabsdata = jQuery(this).find("li[data-tabs]");
		var $tabscontent = jQuery(this).find(".cardealer-tabcontent"),
		$tabsnav = jQuery(this).find(".tabs li");
		$tabsdata.on('click', function () {
			$tabsdata.removeClass('active');
			$tabscontent.hide();
			var tab = jQuery(this).data('tabs');
			jQuery(this).addClass('active');
			jQuery('#' + tab).fadeIn().show();
		});
		$tabsnav.on('click', function () {
			var  cur = $tabsnav.index(this);
			var elm = jQuery( currentTabSection).find('.cardealer-tabcontent:eq('+cur+')');
			elm.addClass("pulse");
			setTimeout(function() {
				elm.removeClass("pulse");
			}, 220);
		});
		$tabscontent.hide().filter(':first').addClass('test').show();

		jQuery( currentTabSection).find(".tabs_wrapper" ).each(function( index ) {
			jQuery(this).find('.cardealer-tabcontent').hide().filter(':first').show();
		});
		jQuery( currentTabSection).find('.tabs_wrapper li[data-tabs]').on('click', function () {
			var tabs_parent = jQuery(this).closest('.tabs_wrapper');
			jQuery(tabs_parent).find('li[data-tabs]').removeClass('active');
			jQuery(tabs_parent).find('.cardealer-tabcontent').hide();
			var tab =jQuery(this).data('tabs');
			jQuery(this).addClass('active');
			jQuery('#' + tab).fadeIn().show();
		});
		jQuery( currentTabSection).find(".tabs li").click(function(){
			var cur =jQuery(this).index(this);
			var elm =jQuery( currentTabSection).find('.cardealer-tabcontent:eq('+cur+')');
			elm.addClass("pulse");
			setTimeout(function() {
				elm.removeClass("pulse");
			}, 220);
		});
	});
}
