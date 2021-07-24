<?php
/**
 * CarDealer Visual Composer promocode image Shortcode
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package car-dealer-helper/functions
 */

add_shortcode( 'promocode', 'cdhl_shortcode_promocode' );

/**
 * Shortcode HTML.
 *
 * @param array $atts .
 */
function cdhl_shortcode_promocode( $atts ) {
	$atts = shortcode_atts(
		array(
			'element_id'  => uniqid( 'cdhl-promo-' ),
			'title_align' => 'text-center',
			'css'         => '',
			'promo_title' => '',
			'color_style' => 'promocode-color-light',
		),
		$atts
	);
	extract( $atts );

	$css          = $atts['css'];
	$custom_class = vc_shortcode_custom_css_class( $css, ' ' );
	ob_start();
	?>	
	<div class="<?php echo esc_attr( $custom_class ); ?>">
		<div class="promocode-box <?php echo esc_attr( $title_align ); ?> <?php echo esc_attr( $color_style ); ?>">	
			<div class="promocode-form form-inline" id="<?php echo esc_attr( $element_id ); ?>">                

				<?php
				if ( ! empty( $atts['promo_title'] ) ) {
					?>
					<h4 class="text-red"><?php echo esc_html( $atts['promo_title'] ); ?></h4>
				<?php } ?>

				<input type="hidden" name="action" class="promocode_action" value="validate_promocode">            
				<input type="hidden" name="promocode_nonce" class="promocode_nonce" value="<?php echo wp_create_nonce( 'cdhl-promocode-form' ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>">
				<div class="form-group">
					<label for="promocode" class="sr-only"><?php echo esc_html__( 'Promo Code', 'cardealer-helper' ); ?></label>
					<input type="text" name="promocode" class="form-control promocode" placeholder="<?php echo esc_html__( 'Promo Code', 'cardealer-helper' ); ?>">
					<span class="spinimg"></span>
				</div>            
				<button type="button" class="button promocode-btn" data-fid="<?php echo esc_attr( $element_id ); ?>"><?php echo esc_html__( 'Go', 'cardealer-helper' ); ?></button>
				<p class="promocode-msg" style="display:none;"></p>
			</div>    
		</div>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Shortcode mapping.
 *
 * @return void
 */
function cdhl_promocode_shortcode_vc_map() {
	if ( function_exists( 'vc_map' ) ) {
		vc_map(
			array(
				'name'                    => esc_html__( 'Potenza Promo Code', 'cardealer-helper' ),
				'description'             => esc_html__( 'Promocode input box', 'cardealer-helper' ),
				'base'                    => 'promocode',
				'class'                   => 'cardealer_helper_element_wrapper',
				'controls'                => 'full',
				'icon'                    => cardealer_vc_shortcode_icon( 'promocode' ),
				'category'                => esc_html__( 'Potenza', 'cardealer-helper' ),
				'show_settings_on_create' => true,
				'params'                  => array(
					array(
						'type'        => 'textfield',
						'class'       => '',
						'heading'     => esc_html__( 'Title', 'cardealer-helper' ),
						'description' => esc_html__( 'Enter title.', 'cardealer-helper' ),
						'param_name'  => 'promo_title',
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Input Box Align', 'cardealer-helper' ),
						'param_name'  => 'title_align',
						'value'       => array(
							esc_html__( 'Left', 'cardealer-helper' ) => 'text-left',
							esc_html__( 'Center', 'cardealer-helper' ) => 'text-center',
							esc_html__( 'Right', 'cardealer-helper' ) => 'text-right',
						),
						'save_always' => true,

					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Newsletter Layout Color', 'cardealer-helper' ),
						'param_name' => 'color_style',
						'value'      => array(
							esc_html__( 'Light', 'cardealer-helper' ) => 'promocode-color-light',
							esc_html__( 'Dark', 'cardealer-helper' ) => 'promocode-color-dark',
						),
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
add_action( 'vc_before_init', 'cdhl_promocode_shortcode_vc_map' );
