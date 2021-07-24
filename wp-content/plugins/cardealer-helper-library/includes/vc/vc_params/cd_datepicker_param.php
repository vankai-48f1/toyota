<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Datepicker parameter for Visual Composer
 *
 * @package Custom_vc
 *
 * # Usage -
 * array(
 * 'type' => 'cd_datepicker',
 * )
 */

if ( function_exists( 'vc_add_shortcode_param' ) ) {
	vc_add_shortcode_param( 'cd_datepicker', 'cdhl_datepicker_settings_field', CDHL_VC_URL . '/assets/js/cd_datepicker.js' );
}

/**
 * Parsing settings field.
 *
 * @param array $settings Settings array.
 * @param array $value    Values array.
 *
 * @return string
 */
function cdhl_datepicker_settings_field( $settings, $value ) {
	$options = isset( $settings['options'] ) ? $settings['options'] : array();

	$class = isset( $settings['class'] ) ? $settings['class'] : '';

	$output     = '';
	$selected   = '';
	$css_option = str_replace( '#', 'hash-', vc_get_dropdown_option( $settings, $value ) );

	$output .= '<div class="datepicker_block">';
	$output .= '<input ';
	$output .= 'name="' . esc_attr( $settings['param_name'] ) . '" ';
	$output .= 'class="wpb_vc_param_value wpb-input wpb-textinput wpb-datepicker vc_datepicker ' . esc_attr( $settings['param_name'] ) . '  ' . esc_attr( $settings['type'] ) . '_field ' . $class . '" ';
	$output .= 'value="' . esc_attr( $value ) . '" ';
	$output .= '>';

	if ( is_array( $options ) ) {
		foreach ( $options as $key => $val ) {
			if ( '' !== $css_option && $css_option === $key ) {
				$selected = ' selected="selected"';
			} else {
				$selected = '';
			}

			$tooltip = $key;
			$img_url = $val;

			$output .= '<option data-tooltip="' . esc_attr( $tooltip ) . '"  data-img-src="' . esc_url( $img_url ) . '"  value="' . esc_attr( $key ) . '" ' . $selected . '>' . $key . '</option>';
		}
	}
	$output .= '</select>';

	return $output;
}
