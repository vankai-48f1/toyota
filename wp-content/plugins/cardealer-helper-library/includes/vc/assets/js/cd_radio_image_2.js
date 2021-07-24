/**
 * Backend JS for cd_radio_image_2 - parameter
 */

( function( $ ) {
	"use strict";

    vc.atts.cd_radio_image_2 = {
        render: function ( param, value ) {
            return value;
        },
        init: function ( param, $field ) {
			$field.find( '.cd_radio_image_2' ).imagepicker({
				hide_select : $field.find( '.cd_radio_image_2' ).data('hide_select'),
				show_label  : $field.find( '.cd_radio_image_2' ).data('show_label'),
			});
        }
    };

})(window.jQuery);