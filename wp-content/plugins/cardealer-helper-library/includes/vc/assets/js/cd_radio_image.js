/**
 * Backend JS for cd_radio_image - parameter
 */

(function ($) {

    vc.atts.cd_radio_image = {
        render: function ( param, value ) {
            return value;
        },
        init: function ( param, $field ) {
            $field.find( '.wpb_vc_param_value' ).imagepicker();
        }
    };

})(window.jQuery);