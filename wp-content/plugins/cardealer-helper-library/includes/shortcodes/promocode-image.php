<?php
/**
 * CarDealer Visual Composer promocode image Shortcode
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package car-dealer-helper/functions
 */

add_shortcode( 'cd_promocode_image', 'cdhl_shortcode_promocode_image' );

/**
 * Shortcode HTML.
 *
 * @param array $atts .
 */
function cdhl_shortcode_promocode_image( $atts ) {
	$atts = shortcode_atts(
		array(
			'print_btn_title' => esc_html__( 'Print Now', 'cardealer-helper' ),
			'promocode_image' => '',
			'css'             => '',
			'element_id'      => uniqid( 'cd_promocode_image_' ),
		),
		$atts
	);
	extract( $atts );
	$css             = $atts['css'];
	$custom_class    = vc_shortcode_custom_css_class( $css, ' ' );
	$element_classes = array(
		'pgs_promocode_image',
		$custom_class,
	);
	$element_classes = implode( ' ', array_filter( array_unique( $element_classes ) ) );

	if ( empty( $atts['promocode_image'] ) ) {
		return;
	}
	$promocode_image_arr = ( function_exists( 'cardealer_get_attachment_detail' ) ) ? cardealer_get_attachment_detail( $atts['promocode_image'] ) : '';
	ob_start();
	?>
	<div id="<?php echo esc_attr( $element_id ); ?>" class="<?php echo esc_attr( $element_classes ); ?>">
		<div id="promocode_img_<?php echo esc_attr( $element_id ); ?>" class="promocode_img_container">
			<?php if ( cardealer_lazyload_enabled() ) { ?>
				<img class="img-responsive cardealer-lazy-load" src="<?php echo esc_url( LAZYLOAD_IMG ); ?>" data-src="<?php echo esc_url( wp_get_attachment_url( $atts['promocode_image'], 'full' ) ); ?>" alt="<?php echo ! empty( $promocode_image_arr['alt'] ) ? $promocode_image_arr['alt'] : esc_html__( 'Promocode Image', 'cardealer-helper' ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>">
			<?php } else { ?>
				<img class="img-responsive" src="<?php echo esc_url( wp_get_attachment_url( $atts['promocode_image'], 'full' ) ); ?>" alt="<?php echo ! empty( $promocode_image_arr['alt'] ) ? $promocode_image_arr['alt'] : esc_html__( 'Promocode Image', 'cardealer-helper' ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>">
			<?php } ?>
		</div>
		<a href="javascript:void(0)" class="button pgs_print_btn default large" data-print_id="promocode_img_<?php echo esc_attr( $element_id ); ?>" > <?php echo esc_html( $atts['print_btn_title'] ); ?>	</a>
	</div>
	<?php
	return ob_get_clean();
}


/**
 * Shortcode mapping.
 *
 * @return void
 */
function cdhl_promocode_image_shortcode_vc_map() {
	if ( function_exists( 'vc_map' ) ) {
		global $vc_gitem_add_link_param;
		vc_map(
			array(
				'name'                    => esc_html__( 'Potenza Promocode Image', 'cardealer-helper' ),
				'base'                    => 'cd_promocode_image',
				'class'                   => 'cardealer_helper_element_wrapper',
				'controls'                => 'full',
				'icon'                    => cardealer_vc_shortcode_icon( 'cd_promocode_image' ),
				'category'                => esc_html__( 'Potenza', 'cardealer-helper' ),
				'show_settings_on_create' => true,
				'params'                  => array(
					array(
						'type'        => 'attach_image',
						'class'       => '',
						'heading'     => esc_html__( 'Promocode Image', 'cardealer-helper' ),
						'description' => esc_html__( 'Promocode Image', 'cardealer-helper' ),
						'param_name'  => 'promocode_image',
					),
					array(
						'type'        => 'textfield',
						'class'       => 'print_btn_title',
						'heading'     => esc_html__( 'Button title', 'cardealer-helper' ),
						'description' => esc_html__( 'Enter print button title here', 'cardealer-helper' ),
						'param_name'  => 'print_btn_title',
						'value'       => esc_html__( 'Print Now', 'cardealer-helper' ),
					),
					array(
						'type'       => 'css_editor',
						'heading'    => esc_html__( 'CSS box', 'cardealer-helper' ),
						'param_name' => 'css',
						'group'      => esc_html__( 'Design Options', 'cardealer-helper' ),
					),
				),
			)
		);
	}
}
add_action( 'vc_before_init', 'cdhl_promocode_image_shortcode_vc_map' );
