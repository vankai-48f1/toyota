/*global redux_change, redux*/

(function( $ ) {
    "use strict";

    redux.field_objects = redux.field_objects || {};
    redux.field_objects.pgs_repeater = redux.field_objects.pgs_repeater || {};

    redux.field_objects.pgs_repeater.sort_repeaters = function( selector ) {
        if ( !selector.hasClass( 'redux-container-pgs_repeater' ) ) {
            selector = selector.parents( '.redux-container-pgs_repeater:first' );
        }

        selector.find( '.redux-pgs_repeater-accordion-pgs_repeater' ).each(
            function( idx ) {

                var id = $( this ).attr( 'data-sortid' );
                var input = $( this ).find( "input[name*='[" + id + "]']" );
                input.each(
                    function() {
                        $( this ).attr( 'name', $( this ).attr( 'name' ).replace( '[' + id + ']', '[' + idx + ']' ) );
                    }
                );

                var select = $( this ).find( "select[name*='[" + id + "]']" );
                select.each(
                    function() {
                        $( this ).attr( 'name', $( this ).attr( 'name' ).replace( '[' + id + ']', '[' + idx + ']' ) );
                    }
                );
                $( this ).attr( 'data-sortid', idx );

                // Fix the accordian header
                var header = $( this ).find( '.ui-accordion-header' );
                var split = header.attr( 'id' ).split( '-header-' );
                header.attr( 'id', split[0] + '-header-' + idx );
                split = header.attr( 'aria-controls' ).split( '-panel-' );
                header.attr( 'aria-controls', split[0] + '-panel-' + idx );

                // Fix the accordian content
                var content = $( this ).find( '.ui-accordion-content' );
                var split = content.attr( 'id' ).split( '-panel-' );
                content.attr( 'id', split[0] + '-panel-' + idx );
                split = content.attr( 'aria-labelledby' ).split( '-header-' );
                content.attr( 'aria-labelledby', split[0] + '-header-' + idx );

            }
        );
    };


    redux.field_objects.pgs_repeater.init = function( selector ) {

        if ( !selector ) {
            selector = $( document ).find( ".redux-group-tab:visible" ).find( '.redux-container-pgs_repeater:visible' );
        }

        $( selector ).each(
            function( idx ) {

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

                var parent = el;

                if ( !el.hasClass( 'redux-field-container' ) ) {
                    parent = el.parents( '.redux-field-container:first' );
                }

                var gid = parent.attr( 'data-id' );

                var blank = el.find( '.redux-pgs_repeater-accordion-pgs_repeater:last-child' );
                redux.pgs_repeater[gid].blank = blank.clone().wrap( '<p>' ).parent().html();


                if ( parent.hasClass( 'redux-container-pgs_repeater' ) ) {
                    parent.addClass( 'redux-field-init' );
                }

                if ( parent.hasClass( 'redux-field-init' ) ) {
                    parent.removeClass( 'redux-field-init' );
                } else {
                    return;
                }

                //if ( el.find( '.slide-title' ).length < 2 ) {
                //    active = 0;
                //}

                var base            = el.find('.redux-pgs_repeater-accordion');
                var panelsClosed    = Boolean(base.data('panels-closed'));
                var active;
                
                if (panelsClosed == true) {
                    active = Boolean(false);
                } else {
                    active = parseInt(0);
                }
                
                var accordian = el.find( ".redux-pgs_repeater-accordion" ).accordion(
                    {
                        header: "> div > fieldset > h3",
                        collapsible: true,
                        active: active,

                        beforeActivate: function (event, ui) {
                            if (typeof reduxRepeaterAccordionBeforeActivate == 'function') {
                                reduxRepeaterAccordionBeforeActivate($(this), el, event, ui);
                            }                            
                        },
                        activate: function( event, ui ) {
                            $.redux.initFields();
                            
                            if (typeof reduxRepeaterAccordionActivate == 'function') {
                                reduxRepeaterAccordionActivate($(this), el, event, ui);
                            }                            
                        },
                        heightStyle: "content",
                        icons: {
                            "header": "ui-icon-plus",
                            "activeHeader": "ui-icon-minus"
                        }
                    }
                );
                if ( redux.pgs_repeater[gid].sortable == 1 ) {
                    accordian.sortable(
                        {
                            axis: "y",
                            handle: "h3",
                            connectWith: ".redux-pgs_repeater-accordion",
                            placeholder: "ui-state-highlight",
                            start: function( e, ui ) {
                                ui.placeholder.height( ui.item.height() );
                                ui.placeholder.width( ui.item.width() );
                            },
                            stop: function( event, ui ) {
                                // IE doesn't register the blur when sorting
                                // so trigger focusout handlers to remove .ui-state-focus
                                ui.item.children( "h3" ).triggerHandler( "focusout" );

                                redux.field_objects.pgs_repeater.sort_repeaters( $( this ) );

                            }
                        }
                    );
                } else {
                    accordian.find( 'h3.ui-accordion-header' ).css( 'cursor', 'pointer' );
                }

                el.find( '.redux-pgs_repeater-accordion-pgs_repeater .bind_title' ).on(
                    'change keyup', function( event ) {
                        var value;
                        
                        if ( $( event.target ).find( ':selected' ).text().length > 0 ) {
                            value = $( event.target ).find( ':selected' ).text();
                        } else {
                            value = $( event.target ).val();
                        }
                        $( this ).closest( '.redux-pgs_repeater-accordion-pgs_repeater' ).find( '.redux-pgs_repeater-header' ).text( value );
                    }
                );

                // Handler to remove the given repeater
                el.find( '.redux-pgs_repeaters-remove' ).live(
                    'click', function() {
                        redux_change( $( this ) );
                        var parent = $( this ).parents( '.redux-container-pgs_repeater:first' );
                        var gid = parent.attr( 'data-id' );
                        redux.pgs_repeater[gid].blank = $( this ).parents( '.redux-pgs_repeater-accordion-pgs_repeater:first' ).clone(
                            true, true
                        );
                        $( this ).parents( '.redux-pgs_repeater-accordion-pgs_repeater:first' ).slideUp(
                            'medium', function() {
                                $( this ).remove();
                                redux.field_objects.pgs_repeater.sort_repeaters( el );
                                if ( redux.pgs_repeater[gid].limit != '' ) {
                                    var count = parent.find( '.redux-pgs_repeater-accordion-pgs_repeater' ).length;
                                    if ( count < redux.pgs_repeater[gid].limit ) {
                                        parent.find( '.redux-pgs_repeaters-add' ).removeClass( 'button-disabled' );
                                    }
                                }
                                parent.find( '.redux-pgs_repeater-accordion-pgs_repeater:last .ui-accordion-header' ).click();
                            }
                        );

                    }
                );

                var x = el.find('.redux-pgs_repeater-accordion-pgs_repeater');
                if (x.hasClass('close-me')) {
                    el.find( '.redux-pgs_repeaters-remove' ).click();
                }
                
                String.prototype.reduxReplaceAll = function( s1, s2 ) {
                    return this.replace(
                        new RegExp( s1.replace( /[.^$*+?()[{\|]/g, '\\$&' ), 'g' ),
                        s2
                    );
                };


                el.find( '.redux-pgs_repeaters-add' ).click(
                    function() {

                        if ( $( this ).hasClass( 'button-disabled' ) ) {
                            return;
                        }

                        var parent = $( this ).parent().find( '.redux-pgs_repeater-accordion:first' );
                        var count = parent.find( '.redux-pgs_repeater-accordion-pgs_repeater' ).length;

                        var gid = parent.attr( 'data-id' ); // Group id
                        if ( redux.pgs_repeater[gid].limit != '' ) {
                            if ( count >= redux.pgs_repeater[gid].limit ) {
                                $( this ).addClass( 'button-disabled' );
                                return;
                            }
                        }
                        count++;

                        var id = parent.find( '.redux-pgs_repeater-accordion-pgs_repeater' ).size(); // Index number


                        if ( parent.find( '.redux-pgs_repeater-accordion-pgs_repeater:last' ).find( '.ui-accordion-header' ).hasClass( 'ui-state-active' ) ) {
                            parent.find( '.redux-pgs_repeater-accordion-pgs_repeater:last' ).find( '.ui-accordion-header' ).click();
                        }

                        var newSlide = parent.find( '.redux-pgs_repeater-accordion-pgs_repeater:last' ).clone( true, true );

                        if ( newSlide.length == 0 ) {
                            newSlide = redux.pgs_repeater[gid].blank;
                        }

                        if ( redux.pgs_repeater[gid] ) {
                            redux.pgs_repeater[gid].count = el.find( '.redux-pgs_repeater-header' ).length;
                            var html = redux.pgs_repeater[gid].html.reduxReplaceAll( '99999', id );
                            $( newSlide ).find( '.redux-pgs_repeater-header' ).text( '' );
                        }

                        newSlide.find( '.ui-accordion-content' ).html( html );
                        // Append to the accordian
                        $( parent ).append( newSlide );
                        // Reorder
                        redux.field_objects.pgs_repeater.sort_repeaters( newSlide );
                        // Refresh the JS object
                        var newSlide = $( this ).parent().find( '.redux-pgs_repeater-accordion:first' );
                        newSlide.find( '.redux-pgs_repeater-accordion-pgs_repeater:last .ui-accordion-header' ).click();
                        newSlide.find( '.redux-pgs_repeater-accordion-pgs_repeater:last .bind_title' ).on(
                            'change keyup', function( event ) {
                                var value;
                                
                                if ( $( event.target ).find( ':selected' ).text().length > 0 ) {
                                    value = $( event.target ).find( ':selected' ).text();
                                } else {
                                    value = $( event.target ).val()
                                }
                                $( this ).closest( '.redux-pgs_repeater-accordion-pgs_repeater' ).find( '.redux-pgs_repeater-header' ).text( value );
                            }
                        );
                        if ( redux.pgs_repeater[gid].limit > 0 && count >= redux.pgs_repeater[gid].limit ) {
                            $( this ).addClass( 'button-disabled' );
                        }
                        
                        if (panelsClosed == true) {
                            if (count >= 2) {
                                el.find( ".redux-pgs_repeater-accordion" ).accordion('option', {active: false})
                            }
                        }
                    }
                );
            }
        );
    };
	
	
	
	
})( jQuery );


jQuery( document ).ready( function() {
	var $parent_element = jQuery( '.redux-field-container.redux-field.redux-container-pgs_repeater .redux-pgs_repeater-accordion' );	
	$parent_element.set_social_media_pgs_repeater_custom_field_logic();
	
	jQuery( '.redux-pgs_repeaters-add' ).click( function() {
		setTimeout( function() {
			$parent_element = jQuery( '.redux-field-container.redux-field.redux-container-pgs_repeater .redux-pgs_repeater-accordion' );
			$parent_element.set_social_media_pgs_repeater_custom_field_logic();
		}, 50 );
	});
	
});

jQuery.fn.set_social_media_pgs_repeater_custom_field_logic = function() {
	jQuery( this ).each( function( i, obj ) {

		var $icon_select    = jQuery( '#social_media_type-' + i + '-select' );
		var $custom_fields  = jQuery( '#' + redux_vars.option_name + '-custom_social_title-' + i + ', #' + redux_vars.option_name + '-custom_soical_icon-' + i );

		// Get the initial value of the select input and depending on its value
		// show or hide the custom icon input elements
		if ( 'custom' == $icon_select.val() ) {
			// show input fields & headers
			$custom_fields.show();
			$custom_fields.prev().show();
		} else {
			// hide input fields & headers
			$custom_fields.hide();
			$custom_fields.prev().hide();
		}

		if ( ! $icon_select.val() ) {
			$icon_select.parents( '.ui-accordion-content' ).css( 'height', '' );
		}

		// check if the value of the select has changed and show/hide the elements conditionally.
		$icon_select.change( function() {
			$icon_select.parents( '.ui-accordion-content' ).css( 'height', '' );

			if ( 'custom' == jQuery( this ).val() ) {
				// show input fields & headers
				$custom_fields.show();
				$custom_fields.prev().show();
			} else {
				// hide input fields & headers
				$custom_fields.hide();
				$custom_fields.prev().hide();
			}
		});
	});
};