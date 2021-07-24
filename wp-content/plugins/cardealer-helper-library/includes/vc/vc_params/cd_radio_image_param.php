<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Radio image parameter for Visual Composer
 *
 * @package car-dealer-helper/functions
 *
 * # Usage -
 * array(
 * 'type' => 'radio_image_select',
 * 'options' => array(
 *           'image-1' => '../assets/images/patterns/01.png',
 *           'image-2' => '../assets/images/patterns/02.png',
 * ),
 * )
 */

if ( function_exists( 'vc_add_shortcode_param' ) ) {
	vc_add_shortcode_param( 'cd_radio_image', 'cdhl_radio_image_settings_field', CDHL_VC_URL . '/assets/js/cd_radio_image.js' );
}

/**
 * Parsing settings field.
 *
 * @param array $settings Settings array.
 * @param array $value    Values array.
 *
 * @return string
 */
function cdhl_radio_image_settings_field( $settings, $value ) {
	$options = isset( $settings['options'] ) ? $settings['options'] : array();

	$class = isset( $settings['class'] ) ? $settings['class'] : '';

	$output   = '';
	$selected = '';

	$css_option = str_replace( '#', 'hash-', vc_get_dropdown_option( $settings, $value ) );

	$output .= '<select name="'
			. $settings['param_name']
			. '" class="vc_radio_select wpb_vc_param_value wpb-input wpb-select ' . $class
			. ' ' . $settings['param_name']
			. ' ' . $settings['type']
			. ' ' . $css_option
			. '" data-option="' . $css_option . '">';

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
