<?php
/**
 * This function used to compare cars.
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

add_action( 'wp_ajax_car_compare_action', 'cdhl_car_compare_action' );
add_action( 'wp_ajax_nopriv_car_compare_action', 'cdhl_car_compare_action' );
if ( ! function_exists( 'cdhl_car_compare_action' ) ) {
	/**
	 * Get compare option
	 */
	function cdhl_car_compare_action() {
		cdhl_get_template_part( 'content', 'compare' );
		die;
	}
}
