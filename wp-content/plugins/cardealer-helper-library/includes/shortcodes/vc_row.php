<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * CarDealer Visual Composer row Shortcode
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package car-dealer-helper/functions
 */

add_action( 'init', 'cdhl_vc_row_extend', 1000 );

/**
 * VC row extend.
 *
 * @return void
 */
function cdhl_vc_row_extend() {
	if ( ! function_exists( 'vc_add_params' ) ) {
		return;
	}

	$params     = array();
	$params_x[] = array(
		'type'             => 'ult_param_heading',
		'text'             => esc_html__( 'Background settings', 'cardealer-helper' ),
		'param_name'       => 'bg_main',
		'edit_field_class' => 'ult-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
		'group'            => esc_attr__( 'Background options', 'cardealer-helper' ),
	);
	$params[]   = array(
		'type'       => 'dropdown',
		'heading'    => esc_html__( 'Select Row Background Type', 'cardealer-helper' ),
		'param_name' => 'cd_bg_type',
		'value'      => array(
			esc_attr__( 'Light background', 'cardealer-helper' ) => 'row-background-light',
			esc_attr__( 'Dark background', 'cardealer-helper' )  => 'row-background-dark',
		),
		'group'      => esc_attr__( 'Background options', 'cardealer-helper' ),
	);
	$params[]   = array(
		'type'       => 'checkbox',
		'heading'    => esc_html__( 'Enable Overlay?', 'cardealer-helper' ),
		'param_name' => 'cd_enable_overlay',
		'group'      => esc_attr__( 'Background options', 'cardealer-helper' ),
	);
	$params[]   = array(
		'type'        => 'colorpicker',
		'heading'     => esc_html__( 'Overlay color', 'cardealer-helper' ),
		'param_name'  => 'cd_overlay_color',
		'description' => esc_html__( 'Select overlay color.', 'cardealer-helper' ),
		'dependency'  => array(
			'element' => 'cd_enable_overlay',
			'value'   => 'true',
		),
		'group'       => esc_attr__( 'Background options', 'cardealer-helper' ),
	);
	$params[]   = array(
		'type'        => 'cd_number_min_max',
		'heading'     => esc_html__( 'Overlay Opacity', 'cardealer-helper' ),
		'param_name'  => 'cd_overlay_opacity',
		'value'       => '80',
		'min'         => '0',
		'max'         => '100',
		'suffix'      => '%',
		'group'       => esc_attr__( 'Background options', 'cardealer-helper' ),
		'dependency'  => array(
			'element' => 'cd_enable_overlay',
			'value'   => 'true',
		),
		'description' => esc_html__( 'Enter value between 0 to 100 (0 is maximum transparency, while 100 is minimum)', 'cardealer-helper' ),
	);

	$atts           = vc_get_shortcode( 'vc_row' );
	$atts['params'] = array_merge( $atts['params'], $params );
	unset( $atts['base'] );
	vc_map_update( 'vc_row', $atts );
}
