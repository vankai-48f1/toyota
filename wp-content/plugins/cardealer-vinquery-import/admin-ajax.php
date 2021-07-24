<?php
/**
 * Vinquery admin ajax
 *
 * @package Vinquery Import
 */

if ( ! function_exists( 'cdvqi_save_vin_current_mapping' ) ) {
	/**
	 * Save vin current mapping
	 */
	function cdvqi_save_vin_current_mapping() {
		$status  = 'error';
		$message = esc_html__( 'Something went wrong!', 'cdvqi-addon' );
		if ( isset( $_POST['form'] ) && ! empty( $_POST['form'] ) ) {

			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['nonce'] ) ), 'cdvi_cars_vin_import' ) ) {
				$status  = 'error';
				$message = esc_html__( 'Nonce not valid, clear your cache and try again.', 'cdvqi-addon' );
			} else {
				parse_str( wp_unslash( $_POST['form'] ), $form );
				update_option( 'vin_query_import_mapping', $form );
				$status  = 'success';
				$message = esc_html__( 'Mapping saved successfully.', 'cdvqi-addon' );
			}
		}
		$responce = array(
			'status'  => $status,
			'message' => $message,
		);
		echo wp_json_encode( $responce );
		die;
	}
}
add_action( 'wp_ajax_cdvqi_save_vin_current_mapping', 'cdvqi_save_vin_current_mapping' );
