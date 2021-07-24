<?php
/**
 * Visual Composer Shortcode
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper/functions
 * @version 1.0.0
 */

/**
 * Enable shortcodes in widgets.
 */
add_filter( 'widget_text', 'do_shortcode' );


if ( ! function_exists( 'cdhl_shortcodes_loader' ) ) {
	/**
	 * Shortcodes Loader
	 */
	function cdhl_shortcodes_loader() {
		if ( cdhl_plugin_active_status( 'js_composer/js_composer.php' ) ) {
			$shortcodes_path = trailingslashit( CDHL_PATH ) . 'includes/shortcodes/';
			if ( is_dir( $shortcodes_path ) ) {
				$shortcodes = ( function_exists( 'cardealer_helper_get_file_list' ) ) ? cardealer_helper_get_file_list( 'php', $shortcodes_path ) : '';
				if ( ! empty( $shortcodes ) ) {
					foreach ( $shortcodes as $shortcode ) {
						include $shortcode;
					}
				}
			}
		}
	}
}

cdhl_shortcodes_loader();
