<?php
/**
 * Init
 *
 * @package Cardealer Vin Import
 */

if ( ! function_exists( 'cdvi_script_style_admin' ) ) {
	/**
	 * Enqueue script
	 */
	function cdvi_script_style_admin() {
		global $post_type;

		$current_screen = get_current_screen();

		wp_register_script( 'cdhl-jquery-vin-import', trailingslashit( CDVI_URL ) . 'js/cardealer_vin_import.js', array(), false, true );

		if ( 'cars' === $post_type || ( 'cars_page_cars-vin-import' === $current_screen->id ) ) {
			wp_enqueue_script( 'cdhl-jquery-vin-import' );
		}
	}
}
add_action( 'admin_enqueue_scripts', 'cdvi_script_style_admin' );
