<?php
/**
 * CarDealer Visual Composer image slider Shortcode
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package car-dealer-helper/functions
 */

add_shortcode( 'image_sliderl', 'cdhl_image_sliderl_shortcode' );

/**
 * Shortcode HTML.
 *
 * @param array $atts .
 */
function cdhl_image_sliderl_shortcode( $atts ) {
	global $car_dealer_options;
	extract(
		shortcode_atts(
			array(
				'data_md_items'  => 1,
				'data_sm_items'  => 1,
				'data_xs_items'  => 1,
				'data_xx_items'  => 1,
				'data_space'     => 0,
				'car_slider_opt' => '',
			),
			$atts
		)
	);
	if ( ! empty( $atts ) ) {
		extract( $atts );
	}
	if ( ! empty( $custom_title ) ) {
		$title = $custom_title;
	}

	ob_start();

	$images_url     = ( ! empty( $atts['slider_images'] ) ? $atts['slider_images'] : '' );

	if ( $images_url ) {

		$car_slider_opt = explode( ',', $car_slider_opt );
		$lazyload       = ( isset( $car_dealer_options['enable_lazyload'] ) && $car_dealer_options['enable_lazyload'] ) ? true : false;
		$arrow          = 'false';
		$autoplay       = 'false';
		$dots           = 'false';
		$data_loop      = 'false';
		foreach ( $car_slider_opt as $option ) {
			if ( 'Autoplay' === (string) $option ) {
				$autoplay = 'true';
			} elseif ( 'Loop' === (string) $option ) {
				$data_loop = 'true';
			} elseif ( 'Navigation Dots' === (string) $option ) {
				$dots = 'true';
			} elseif ( 'Navigation Arrow' === (string) $option ) {
				$arrow = 'true';
			}
		}

		?>     
		<div class='owl-carousel' 
		data-nav-arrow='<?php echo esc_attr( $arrow ); ?>' 
		data-nav-dots='<?php echo esc_attr( $dots ); ?>'     
		data-md-items='<?php echo esc_attr( $data_md_items ); ?>' 
		data-sm-items='<?php echo esc_attr( $data_sm_items ); ?>' 
		data-xs-items='<?php echo esc_attr( $data_xs_items ); ?>' 
		data-xx-items='<?php echo esc_attr( $data_xx_items ); ?>' 
		data-space='<?php echo esc_attr( $data_space ); ?>'
		data-autoplay='<?php echo esc_attr( $autoplay ); ?>'
		data-loop='<?php echo esc_attr( $data_loop ); ?>'
		data-lazyLoad='<?php echo esc_attr( $lazyload ); ?>'>

			<?php
			$imagesurl      = explode( ',', $images_url );
			$lazyload_class = ( 'true' === (string) $data_loop ) ? 'cardealer-lazy-load owl-lazy' : 'cardealer-lazy-load';

			// foreach in array.
			foreach ( $imagesurl as $image_id ) {
				$img_url = wp_get_attachment_url( $image_id, 'full' );
				$img_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
				?>
				<div class="item">
					<?php if ( isset( $car_dealer_options['enable_lazyload'] ) && $car_dealer_options['enable_lazyload'] ) { ?>
						<img class="center-block <?php echo esc_attr( $lazyload_class ); ?>" src="<?php echo esc_url( LAZYLOAD_IMG ); ?>" data-src="<?php echo esc_html( $img_url ); ?>" alt="<?php echo esc_html( $img_alt ); ?>">
					<?php } else { ?>
						<img class="center-block" src="<?php echo esc_html( $img_url ); ?>" alt="<?php echo esc_html( $img_alt ); ?>">
					<?php } ?>
				</div>
				<?php
			}
			?>

		</div>      
		<?php
	}
	return ob_get_clean();
}

/**
 * Shortcode mapping.
 *
 * @return void
 */
function cdhl_image_sliderl_shortcode_integrateWithVC() {
	if ( function_exists( 'vc_map' ) ) {
		vc_map(
			array(
				'name'     => esc_html__( 'Potenza Image Slider', 'cardealer-helper' ),
				'base'     => 'image_sliderl',
				'class'    => '',
				'icon'     => cardealer_vc_shortcode_icon( 'image_sliderl' ),
				'category' => esc_html__( 'Potenza', 'cardealer-helper' ),
				'params'   => array(

					array(
						'type'        => 'attach_images',
						'heading'     => esc_html__( 'Images', 'cardealer-helper' ),
						'param_name'  => 'slider_images',
						'description' => esc_html__( 'Select images.', 'cardealer-helper' ),
					),
					array(
						'type'       => 'checkbox',
						'heading'    => esc_html__( 'Slider style option', 'cardealer-helper' ),
						'param_name' => 'car_slider_opt',
						'value'      => array(
							esc_html__( 'Navigation Arrow', 'cardealer-helper' ) => 'Navigation Arrow',
							esc_html__( 'Navigation Dots', 'cardealer-helper' ) => 'Navigation Dots',
							esc_html__( 'Autoplay', 'cardealer-helper' ) => 'Autoplay',
							esc_html__( 'Loop', 'cardealer-helper' ) => 'Loop',
						),
					),
				),
			)
		);
	}
}
add_action( 'vc_before_init', 'cdhl_image_sliderl_shortcode_integrateWithVC' );
