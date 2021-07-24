<?php
/**
 * CarDealer Visual Composer space Shortcode
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package car-dealer-helper/functions
 */

add_shortcode( 'cd_space', 'cdhl_space_shortcode' );

/**
 * Shortcode HTML.
 *
 * @param array $atts .
 */
function cdhl_space_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'tablet'          => 70,
			'portrait'        => 60,
			'mobile'          => 40,
			'desktop'         => 50,
			'mobile_portrait' => 10,
			'css'             => '',
			'id'              => uniqid(),
			'element_id'      => uniqid( 'cd_space_' ),
		),
		$atts
	);
	extract( $atts );
	$css             = $atts['css'];
	$custom_class    = vc_shortcode_custom_css_class( $css, ' ' );
	$element_classes = array(
		'cd-space',
		uniqid( 'cd-space-' ),
		$custom_class,
	);
	$element_classes = implode( ' ', array_filter( array_unique( $element_classes ) ) );
	ob_start();
	?>
	<div id="<?php echo esc_attr( $element_id ); ?>" class="<?php echo esc_attr( $element_classes ); ?>" data-id="<?php echo esc_attr( $id ); ?>" data-tablet="<?php echo esc_attr( $tablet ); ?>" data-tablet-portrait="<?php echo esc_attr( $portrait ); ?>" data-mobile="<?php echo esc_attr( $mobile ); ?>" data-mobile-portrait="<?php echo esc_attr( $mobile_portrait ); ?>" data-desktop="<?php echo esc_attr( $desktop ); ?>" style="<?php echo esc_attr( 'clear:both; display:block;' ); ?>"></div>
	<?php
	return ob_get_clean();
}

/**
 * Shortcode mapping.
 *
 * @return void
 */
function cdhl_space_shortcode_vc_map() {
	$base = array(
		array(
			'type'        => 'cd_number_min_max',
			'param_name'  => 'desktop',
			'heading'     => esc_html__( 'Desktop', 'cardealer-helper' ),
			'min'         => '1',
			'max'         => '9999',
			'description' => 'Select height of space element in desktop view',
		),
		array(
			'type'        => 'cd_number_min_max',
			'param_name'  => 'tablet',
			'heading'     => esc_html__( 'Tablet', 'cardealer-helper' ),
			'min'         => '1',
			'max'         => '9999',
			'description' => 'Select height of space element in tablet view',
		),
		array(
			'type'        => 'cd_number_min_max',
			'param_name'  => 'portrait',
			'heading'     => esc_html__( 'Portrait', 'cardealer-helper' ),
			'min'         => '1',
			'max'         => '9999',
			'description' => 'Select height of space element in portrait view',
		),
		array(
			'type'        => 'cd_number_min_max',
			'param_name'  => 'mobile',
			'heading'     => esc_html__( 'Mobile', 'cardealer-helper' ),
			'min'         => '1',
			'max'         => '9999',
			'description' => 'Select height of space element in mobile view',
		),
		array(
			'type'        => 'cd_number_min_max',
			'param_name'  => 'mobile_portrait',
			'heading'     => esc_html__( 'Mobile Portrait', 'cardealer-helper' ),
			'min'         => '1',
			'max'         => '9999',
			'description' => 'Select height of space element in mobile portrait view',
		),
		array(
			'type'       => 'css_editor',
			'heading'    => esc_html__( 'CSS box', 'cardealer-helper' ),
			'param_name' => 'css',
			'group'      => esc_html__( 'Design Options', 'cardealer-helper' ),
		),
	);
	// Params.
	$params = array(
		'name'                    => esc_html__( 'Potenza Space', 'cardealer-helper' ),
		'description'             => esc_html__( 'Potenza Space block', 'cardealer-helper' ),
		'base'                    => 'cd_space',
		'class'                   => 'cardealer_helper_element_wrapper',
		'controls'                => 'full',
		'icon'                    => cardealer_vc_shortcode_icon( 'cd_space' ),
		'category'                => esc_html__( 'Potenza', 'cardealer-helper' ),
		'show_settings_on_create' => true,
		'params'                  => array_merge( $base ),
	);
	if ( function_exists( 'vc_map' ) ) {
		vc_map( $params );
	}
}
add_action( 'vc_before_init', 'cdhl_space_shortcode_vc_map' );
