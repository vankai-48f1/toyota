<?php
/**
 * Initialized
 *
 * @package Vinquery Import
 */

if ( ! function_exists( 'cdvqi_script_style_admin' ) ) {
	/**
	 * Enqueue script
	 */
	function cdvqi_script_style_admin() {
		global $post_type;

		$current_screen = get_current_screen();

		wp_register_script( 'cdhl-jquery-vinquery-import', trailingslashit( CDVQI_URL ) . 'js/cardealer_vinquery_import.js', array(), false, true );

		if ( 'cars' === $post_type || ( 'cars_page_cars-vinquery-import' === $current_screen->id ) ) {
			wp_enqueue_script( 'cdhl-jquery-vinquery-import' );
		}
	}
}

add_action( 'admin_enqueue_scripts', 'cdvqi_script_style_admin' );
