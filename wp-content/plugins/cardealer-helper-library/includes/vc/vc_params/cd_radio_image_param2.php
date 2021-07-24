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
	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	vc_add_shortcode_param( 'cd_radio_image_2', 'cdhl_radio_image_settings_field_2', trailingslashit( CDHL_VC_URL ) . '/assets/js/cd_radio_image_2' . $suffix . '.js' );
}

/**
 * Parsing settings field.
 *
 * @param array $settings Settings array.
 * @param array $value    Values array.
 *
 * @return string
 */
function cdhl_radio_image_settings_field_2( $settings, $value ) {
	$options = isset( $settings['options'] ) && is_array( $settings['options'] ) && ! empty( $settings['options'] ) ? $settings['options'] : array();

	$class = isset( $settings['class'] ) ? $settings['class'] : '';

	$output   = '';
	$selected = '';

	$css_option = str_replace( '#', 'hash-', vc_get_dropdown_option( $settings, $value ) );

	$select_attrs = array();

	// Classes.
	$select_classes   = array();
	$select_classes[] = 'vc_radio_select wpb_vc_param_value wpb-input wpb-select';
	$select_classes[] = esc_attr( $class );
	$select_classes[] = esc_attr( $settings['param_name'] );
	$select_classes[] = esc_attr( $settings['type'] );
	$select_classes[] = esc_attr( $css_option );

	$select_classes = implode( ' ', array_filter( array_unique( $select_classes ) ) );

	$data_show_label  = isset( $settings['show_label'] ) && is_bool( $settings['show_label'] ) ? ( ( true === $settings['show_label'] ) ? 'true' : 'false' ) : 'false';
	$data_hide_select = isset( $settings['hide_select'] ) && is_bool( $settings['hide_select'] ) ? ( ( true === $settings['hide_select'] ) ? 'true' : 'false' ) : 'true';

	$select_attrs['name']             = 'name="' . esc_attr( $settings['param_name'] ) . '"';
	$select_attrs['class']            = 'class="' . esc_attr( $select_classes ) . '"';
	$select_attrs['data-option']      = 'data-option="' . esc_attr( $css_option ) . '"';
	$select_attrs['data-show_label']  = 'data-show_label="' . esc_attr( $data_show_label ) . '"';
	$select_attrs['data-hide_select'] = 'data-hide_select="' . esc_attr( $data_hide_select ) . '"';

	$select_attr = '';
	$select_attr = implode( ' ', array_filter( array_unique( $select_attrs ) ) );

	$output .= '<select ' . $select_attr . '>';

	foreach ( $options as $option ) {
		if ( '' !== $css_option && $css_option === $option['value'] ) {
			$selected = ' selected="selected"';
		} else {
			$selected = '';
		}

		$output .= '<option';
		$output .= ' data-tooltip="' . esc_attr( $option['title'] ) . '"';
		$output .= ' data-img-label="' . esc_attr( $option['title'] ) . '"';
		$output .= ' data-img-src="' . esc_url( $option['image'] ) . '"';
		$output .= ' value="' . esc_attr( $option['value'] ) . '"';
		$output .= $selected;
		$output .= ' >';
		$output .= $option['title'];
		$output .= '</option>';
	}

	$output .= '</select>';

	return $output;
}
