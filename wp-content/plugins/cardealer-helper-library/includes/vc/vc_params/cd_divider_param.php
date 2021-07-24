<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Divider parameter for Visual Composer
 *
 * @package car-dealer-helper/functions
 */

if ( function_exists( 'vc_add_shortcode_param' ) ) {
	vc_add_shortcode_param( 'cd_divider', 'cdhl_divider_settings' );
}

/**
 * Parsing settings field.
 *
 * @param array $settings Settings array.
 * @param array $value    Values array.
 *
 * @return string
 */
function cdhl_divider_settings( $settings, $value ) {
	$title    = isset( $settings['title'] ) ? '<div class="wpb_element_label tc-vc-divider">' . $settings['title'] . '</div>' : '';
	$subtitle = isset( $settings['subtitle'] ) ? '<span class="vc_description vc_clearfix">' . $settings['subtitle'] . '</span>' : '';

	$input = '<input id="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value" name="' . esc_attr( $settings['param_name'] ) . '" value="" type="hidden">';
	if ( isset( $settings['advanced'] ) ) {
		$input = '<label class="tc-vc-advanced-wrap tc-advanced-field">' . esc_html__( 'Advanced Settings', 'cardealer-helper' ) . '<input id="' . esc_attr( $settings['param_name'] ) . '-true" class="wpb_vc_param_value tc-vc-advanced ' . esc_attr( $settings['param_name'] ) . ' checkbox" name="' . esc_attr( $settings['param_name'] ) . '" ' . checked( $value, 'true', false ) . ' value="true" type="checkbox"></label>';
	}

	return $input . $title . $subtitle;
}
