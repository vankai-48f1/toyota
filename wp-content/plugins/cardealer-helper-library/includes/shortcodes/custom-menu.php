<?php
/**
 * CarDealer Visual Composer custom menu Shortcode
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package car-dealer-helper/functions
 */

add_shortcode( 'cd_custom_menu', 'cdhl_shortcode_custom_menu' );

/**
 * Shortcode HTML.
 *
 * @param array $atts .
 */
function cdhl_shortcode_custom_menu( $atts ) {
	$atts = shortcode_atts(
		array(
			'extra_classes'         => '',
			'pgs_custom_menu_style' => 'horizontal',
			'nav_menu'              => 'default',
			'element_id'            => uniqid( 'cd_custom_menu_' ),
		),
		$atts
	);

	$menu_obj = get_term_by( 'slug', $atts['nav_menu'], 'nav_menu' );
	if ( empty( $menu_obj ) ) {
		return;
	} else {
		$atts['nav_menu'] = $menu_obj->term_id;
	}
	extract( $atts );

	$element_classes = array(
		'potenza-custom-menu',
		$extra_classes,
		$pgs_custom_menu_style,
	);
	$element_classes = implode( ' ', array_filter( array_unique( $element_classes ) ) );

	$output = '<div id="' . esc_attr( $element_id ) . '" class="cd_custommenu ' . esc_attr( $element_classes ) . '">';
	$type   = 'WP_Nav_Menu_Widget';
	$args   = array();
	global $wp_widget_factory;
	// to avoid unwanted warnings let's check before using widget.
	if ( is_object( $wp_widget_factory ) && isset( $wp_widget_factory->widgets, $wp_widget_factory->widgets[ $type ] ) ) {
		ob_start();
		the_widget( $type, $atts, $args );
		$output .= ob_get_clean();
		$output .= '</div>';
		return $output;
	} else {
		return esc_html__( 'Widget ' . esc_attr( $type ) . 'Not found in custommenu', 'cardealer-helper' );
	}
}

/**
 * Shortcode mapping.
 *
 * @return void
 */
function cdhl_custom_menu_shortcode_vc_map() {
	if ( function_exists( 'vc_map' ) ) {
		$custom_menus = array();
		$menus        = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
		if ( is_array( $menus ) && ! empty( $menus ) ) {
			foreach ( $menus as $single_menu ) {
				if ( is_object( $single_menu ) && isset( $single_menu->name, $single_menu->term_id ) ) {
					$custom_menus[ $single_menu->name ] = $single_menu->slug;
				}
			}
		}

		$params = array(
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Menu', 'cardealer-helper' ),
				'param_name'  => 'nav_menu',
				'value'       => $custom_menus,
				'description' => empty( $custom_menus ) ? esc_html__( 'Custom menus not found. Please visit <b>Appearance > Menus</b> page to create new menu.', 'cardealer-helper' ) : esc_html__( 'Select menu to display.', 'cardealer-helper' ),
				'save_always' => true,
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Extra class name', 'cardealer-helper' ),
				'param_name'  => 'extra_classes',
				'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'cardealer-helper' ),
			),
			array(
				'type'       => 'dropdown',
				'class'      => 'pgs_custom_menu_style',
				'param_name' => 'pgs_custom_menu_style',
				'value'      => array(
					esc_html__( 'Horizontal', 'cardealer-helper' ) => 'horizontal',
					esc_html__( 'Vertical', 'cardealer-helper' )   => 'vertical',
				),
				'heading'    => esc_html__( 'Select Menu Style', 'cardealer-helper' ),
			),
		);

		vc_map(
			array(
				'name'                    => esc_html__( 'Potenza Custom Menu', 'cardealer-helper' ),
				'description'             => esc_html__( 'Potenza Custom Menu', 'cardealer-helper' ),
				'base'                    => 'cd_custom_menu',
				'class'                   => 'cardealer_helper_element_wrapper',
				'controls'                => 'full',
				'icon'                    => cardealer_vc_shortcode_icon( 'cd_custom_menu' ),
				'category'                => esc_html__( 'Potenza', 'cardealer-helper' ),
				'show_settings_on_create' => true,
				'params'                  => $params,
			)
		);
	}
}
add_action( 'vc_before_init', 'cdhl_custom_menu_shortcode_vc_map' );
