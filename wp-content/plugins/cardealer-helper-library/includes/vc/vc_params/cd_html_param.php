<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * HTML parameter for Visual Composer
 *
 * @package car-dealer-helper/functions
 */

if ( function_exists( 'vc_add_shortcode_param' ) ) {
	vc_add_shortcode_param( 'cd_html', 'cdhl_html_field' );
}

/**
 * Parsing settings field.
 *
 * @param array $settings Settings array.
 * @param array $value    Values array.
 *
 * @return string
 */
function cdhl_html_field( $settings, $value ) {

	$param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
	$class      = isset( $settings['class'] ) ? $settings['class'] : '';
	$html       = isset( $settings['html'] ) ? $settings['html'] : '';

	$output  = '<div class="wpb_vc_param_value' . ( ! empty( $class ) ? ' ' . $class : '' ) . '">' . $html . '</div>';
	$output .= '<input type="hidden" name="' . $settings['param_name'] . '" class="wpb_vc_param_value tc-param-heading ' . $settings['param_name'] . ' ' . $settings['type'] . '_field" value="' . $value . '" />';

	return $output;
}
