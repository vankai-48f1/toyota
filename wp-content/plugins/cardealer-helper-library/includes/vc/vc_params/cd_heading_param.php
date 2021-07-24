<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Heading parameter for Visual Composer
 *
 * @package car-dealer-helper/functions
 */

if ( function_exists( 'vc_add_shortcode_param' ) ) {
	vc_add_shortcode_param( 'cd_heading', 'cdhl_heading_field' );
}

/**
 * Parsing settings field.
 *
 * @param array $settings Settings array.
 * @param array $value    Values array.
 *
 * @return string
 */
function cdhl_heading_field( $settings, $value ) {
	$param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
	$class      = isset( $settings['class'] ) ? $settings['class'] : '';
	$text       = isset( $settings['text'] ) ? $settings['text'] : '';
	$output     = '<h4 class="wpb_vc_param_value ' . $class . '">' . $text . '</h4>';
	$output    .= '<input type="hidden" name="' . $settings['param_name'] . '" class="wpb_vc_param_value tc-param-heading ' . $settings['param_name'] . ' ' . $settings['type'] . '_field" value="' . $value . '" />';

	return $output;
}
