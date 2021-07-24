<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Cardealer Visual Composer tab id.
 *
 * @package car-dealer-helper/functions
 */

/**
 * Tab id.
 */
function cardealer_helper_tab_id() {
	return array(
		array(
			'type'             => 'tab_id',
			'heading'          => esc_html__( 'Tab ID', 'cardealer-helper' ),
			'param_name'       => 'element_id',
			'edit_field_class' => 'hidden',
		),
	);
}
