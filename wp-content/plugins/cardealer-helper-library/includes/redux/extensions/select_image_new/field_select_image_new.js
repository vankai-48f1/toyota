/*global redux_change, redux*/

(function( $ ) {
    "use strict";

    redux.field_objects = redux.field_objects || {};
    redux.field_objects.select_image_new = redux.field_objects.select_image_new || {};

    $( document ).ready(
        function() {
            //redux.field_objects.select_image_new.init();
        }
    );

    redux.field_objects.select_image_new.init = function( selector ) {

        if ( !selector ) {
            selector = $( document ).find( ".redux-group-tab:visible" ).find( '.redux-container-select_image_new:visible' );
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
                var default_params = {
                    width: 'resolve',
                    triggerChange: true,
                    allowClear: true
                };

                var select2_handle = el.find( '.select2_params' );

                if ( select2_handle.size() > 0 ) {
                    var select2_params = select2_handle.val();

                    select2_params = JSON.parse( select2_params );
                    default_params = $.extend( {}, default_params, select2_params );
                }

                el.find( 'select.redux-select-images' ).select2( default_params );

                el.find( '.redux-select-images' ).on(
                    'change', function() {
                        var preview = $( this ).parents( '.redux-field:first' ).find( '.redux-preview-image' );

                        if ( $( this ).val() == "" ) {
                            preview.fadeOut(
                                'medium', function() {
                                    preview.attr( 'src', '' );
                                }
                            );
                        } else {
							// console.log(this);
							// console.log( $( this ).val() );
							// console.log( $(this).find('option:selected').data('src') );
                            preview.attr( 'src', $(this).find('option:selected').data('src') );
                            preview.fadeIn().css( 'visibility', 'visible' );
                        }
                    }
                );
            }
        );
    };
})( jQuery );