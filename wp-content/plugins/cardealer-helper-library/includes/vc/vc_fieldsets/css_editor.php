<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Cardealer Visual Composer css editor.
 *
 * @package car-dealer-helper/functions
 */

/**
 * CSS Editor.
 */
function cardealer_helper_css_editor() {
	return array(
		array(
			'type'       => 'css_editor',
			'heading'    => esc_html__( 'CSS box', 'cardealer-helper' ),
			'param_name' => 'css',
			'group'      => esc_html__( 'Design Options', 'cardealer-helper' ),
		),
	);
}
