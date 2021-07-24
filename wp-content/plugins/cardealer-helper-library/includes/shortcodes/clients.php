<?php
/**
 * CarDealer Visual Composer our client Shortcode
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package car-dealer-helper/functions
 */

add_shortcode( 'cd_ourclients', 'cdhl_shortcode_ourclients' );

/**
 * Shortcode HTML.
 *
 * @param array $atts .
 */
function cdhl_shortcode_ourclients( $atts ) {
	global $car_dealer_options;
	$atts = shortcode_atts(
		array(
			'slider_images'    => '',
			'number_of_column' => 1,
			'data_md_items'    => 3,
			'data_sm_items'    => 2,
			'data_sm_items'    => 2,
			'data_xs_items'    => 1,
			'data_xx_items'    => 1,
			'data_space'       => 20,
			'dots'             => 'true',
			'arrow'            => 'true',
			'autoplay'         => 'true',
			'data_loop'        => 'true',
			'list_style'       => 'with_slider',
			'element_id'       => uniqid( 'cd_clients_' ),
		),
		$atts
	);
	extract( $atts );
	if ( empty( $atts['slider_images'] ) ) {
		return null;
	}
	$images_url      = ( ! empty( $atts['slider_images'] ) ? $atts['slider_images'] : '' );
	$imagesurl       = explode( ',', $images_url );
	$cnt             = count( $imagesurl );
	$element_classes = array(
		'our-clients',
	);
	$element_classes = implode( ' ', array_filter( array_unique( $element_classes ) ) );

	if ( 'true' === (string) $arrow && $cnt >= $data_md_items ) {
			$arrow = 'true';
	} else {
		$arrow = 'false';
	}

	if ( 'true' === (string) $dots && $cnt >= $data_md_items ) {
		$dots = 'true';
	} else {
		$dots = 'false';
	}

	if ( 'true' === (string) $autoplay ) {
		$autoplay = 'true';
	} else {
		$autoplay = 'false';
	}

	if ( 'true' === (string) $data_loop ) {
		$data_loop = 'true';
	} else {
		$data_loop = 'false';
	}

	ob_start();
	?>
	<div id="<?php echo esc_attr( $element_id ); ?>" class="<?php echo esc_attr( $element_classes ); ?>">
		<div class="col-lg-12 col-md-12">
			<?php
			$lazyload       = cardealer_lazyload_enabled();
			$lazyload_class = ( 'true' === (string) $data_loop && 'with_slider' === (string) $list_style ) ? 'cardealer-lazy-load owl-lazy' : 'cardealer-lazy-load';
			if ( 'with_slider' === (string) $list_style ) {
				?>
			<div class="owl-carousel" 
			data-nav-arrow='<?php echo esc_attr( $arrow ); ?>' 
			data-nav-dots='<?php echo esc_attr( $dots ); ?>' 
			data-items='<?php echo esc_attr( $data_md_items ); ?>' 
			data-md-items='<?php echo esc_attr( $data_md_items ); ?>' 
			data-sm-items='<?php echo esc_attr( $data_sm_items ); ?>' 
			data-xs-items='<?php echo esc_attr( $data_xs_items ); ?>' 
			data-xx-items='<?php echo esc_attr( $data_xx_items ); ?>' 
			data-space='<?php echo esc_attr( $data_space ); ?>'
			data-autoplay='<?php echo esc_attr( $autoplay ); ?>'
			data-loop='<?php echo esc_attr( $data_loop ); ?>'
			data-lazyLoad='<?php echo esc_attr( $lazyload ); ?>'>
				<?php
			}
			$k = 0;
			// foreach in array.
			foreach ( $imagesurl as $image_id ) {
				$img_url = wp_get_attachment_url( $image_id, 'full' );
				$img_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );

				if ( 'with_slider' === (string) $list_style ) {
					?>
					<div class="item">
					<?php if ( $lazyload ) { ?>
							<img class="center-block <?php echo esc_attr( $lazyload_class ); ?>" data-src="<?php echo esc_html( $img_url ); ?>" src="<?php echo esc_url( LAZYLOAD_IMG ); ?>" alt="<?php echo esc_html( $img_alt ); ?>">
						<?php } else { ?>
							<img class="center-block" src="<?php echo esc_html( $img_url ); ?>" alt="<?php echo esc_html( $img_alt ); ?>">
						<?php } ?>
					</div>
					<?php
				} else {
					$i = (int) ( 12 / $number_of_column );
					if ( 0 === (int) $k % $number_of_column ) {
						?>
					<div class="row">
						<?php
					}
					?>

					<div class='col-sm-<?php echo esc_attr( $i ); ?>'>
						<div class="item">
							<?php if ( isset( $car_dealer_options['enable_lazyload'] ) && $car_dealer_options['enable_lazyload'] ) { ?>
								<img class="center-block <?php echo esc_attr( $lazyload_class ); ?>" data-src="<?php echo esc_html( $img_url ); ?>" src="<?php echo esc_url( LAZYLOAD_IMG ); ?>" alt="<?php echo esc_html( $img_alt ); ?>">
							<?php } else { ?>
								<img class="center-block" src="<?php echo esc_html( $img_url ); ?>" alt="<?php echo esc_html( $img_alt ); ?>">
							<?php } ?>
						</div>
					</div>
					<?php
					$k++;
					if ( ( 0 === (int) $k % $number_of_column ) || ( (string) $k === (string) $slider_images ) || ( count( $imagesurl ) == $k ) ) {
						?>
						</div>
						<?php
					}
				}
			}
			if ( 'with_slider' === (string) $list_style ) {
				?>
				</div>
				<?php
			}
			?>
		</div>
	</div>
	<?php
	/* Restore original Post Data */
	wp_reset_postdata();

	return ob_get_clean();
}

/**
 * Shortcode mapping.
 *
 * @return void
 */
function cdhl_ourclients_shortcode_vc_map() {
	if ( function_exists( 'vc_map' ) ) {
		vc_map(
			array(
				'name'                    => esc_html__( 'Potenza Clients', 'cardealer-helper' ),
				'description'             => esc_html__( 'Potenza Clients', 'cardealer-helper' ),
				'base'                    => 'cd_ourclients',
				'class'                   => 'cardealer_helper_element_wrapper',
				'controls'                => 'full',
				'icon'                    => cardealer_vc_shortcode_icon( 'cd_ourclients' ),
				'category'                => esc_html__( 'Potenza', 'cardealer-helper' ),
				'show_settings_on_create' => true,
				'params'                  => array(
					array(
						'type'        => 'attach_images',
						'heading'     => esc_html__( 'Clients Logo', 'cardealer-helper' ),
						'param_name'  => 'slider_images',
						'description' => esc_html__( 'Select Clients logo.', 'cardealer-helper' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'List Style', 'cardealer-helper' ),
						'param_name'  => 'list_style',
						'value'       => array(
							esc_html__( 'Carousel', 'cardealer-helper' ) => 'with_slider',
							esc_html__( 'Grid', 'cardealer-helper' ) => 'without_slider',
						),
						'save_always' => true,
						'description' => esc_html__( 'Layout style of displaying clients logo.', 'cardealer-helper' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Number of column', 'cardealer-helper' ),
						'param_name'  => 'number_of_column',
						'value'       => array(
							esc_html__( '1', 'cardealer-helper' ) => '1',
							esc_html__( '2', 'cardealer-helper' ) => '2',
							esc_html__( '3', 'cardealer-helper' ) => '3',
							esc_html__( '4', 'cardealer-helper' ) => '4',
							esc_html__( '5', 'cardealer-helper' ) => '5',
							esc_html__( '6', 'cardealer-helper' ) => '6',
							esc_html__( '7', 'cardealer-helper' ) => '7',
							esc_html__( '8', 'cardealer-helper' ) => '8',
							esc_html__( '9', 'cardealer-helper' ) => '9',
							esc_html__( '10', 'cardealer-helper' ) => '10',
							esc_html__( '11', 'cardealer-helper' ) => '11',
							esc_html__( '12', 'cardealer-helper' ) => '12',

						),
						'dependency'  => array(
							'element' => 'list_style',
							'value'   => array( 'without_slider' ),
						),
						'save_always' => true,
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Number of slide for desktops per rows', 'cardealer-helper' ),
						'param_name'       => 'data_md_items',
						'value'            => array(
							esc_html__( '3', 'cardealer-helper' ) => '3',
							esc_html__( '4', 'cardealer-helper' ) => '4',
							esc_html__( '5', 'cardealer-helper' ) => '5',
							esc_html__( '6', 'cardealer-helper' ) => '6',
						),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Slider Settings', 'cardealer-helper' ),
						'save_always'      => true,
						'dependency'       => array(
							'element' => 'list_style',
							'value'   => 'with_slider',
						),
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Number of slide for tablets', 'cardealer-helper' ),
						'param_name'       => 'data_sm_items',
						'value'            => array(
							esc_html__( '2', 'cardealer-helper' ) => '2',
							esc_html__( '3', 'cardealer-helper' ) => '3',
							esc_html__( '4', 'cardealer-helper' ) => '4',
							esc_html__( '5', 'cardealer-helper' ) => '5',

						),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Slider Settings', 'cardealer-helper' ),
						'save_always'      => true,
						'dependency'       => array(
							'element' => 'list_style',
							'value'   => 'with_slider',
						),
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Number of slide for mobile landscape', 'cardealer-helper' ),
						'param_name'       => 'data_xs_items',
						'value'            => array(
							esc_html__( '1', 'cardealer-helper' ) => '1',
							esc_html__( '2', 'cardealer-helper' ) => '2',
							esc_html__( '3', 'cardealer-helper' ) => '3',
							esc_html__( '4', 'cardealer-helper' ) => '4',

						),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Slider Settings', 'cardealer-helper' ),
						'save_always'      => true,
						'dependency'       => array(
							'element' => 'list_style',
							'value'   => 'with_slider',
						),
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Number of slide for mobile portrait', 'cardealer-helper' ),
						'param_name'       => 'data_xx_items',
						'value'            => array(
							esc_html__( '1', 'cardealer-helper' ) => '1',
							esc_html__( '2', 'cardealer-helper' ) => '2',
							esc_html__( '3', 'cardealer-helper' ) => '3',

						),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Slider Settings', 'cardealer-helper' ),
						'save_always'      => true,
						'dependency'       => array(
							'element' => 'list_style',
							'value'   => 'with_slider',
						),
					),

					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Navigation Arrow', 'cardealer-helper' ),
						'param_name'       => 'arrow',
						'value'            => array(
							esc_html__( 'Yes', 'cardealer-helper' ) => 'true',
							esc_html__( 'No', 'cardealer-helper' ) => 'false',
						),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Slider Settings', 'cardealer-helper' ),
						'save_always'      => true,
						'dependency'       => array(
							'element' => 'list_style',
							'value'   => 'with_slider',
						),
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Navigation Dots', 'cardealer-helper' ),
						'param_name'       => 'dots',
						'value'            => array(
							esc_html__( 'Yes', 'cardealer-helper' ) => 'true',
							esc_html__( 'No', 'cardealer-helper' ) => 'false',
						),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Slider Settings', 'cardealer-helper' ),
						'save_always'      => true,
						'dependency'       => array(
							'element' => 'list_style',
							'value'   => 'with_slider',
						),
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Autoplay', 'cardealer-helper' ),
						'param_name'       => 'autoplay',
						'value'            => array(
							esc_html__( 'Yes', 'cardealer-helper' ) => 'true',
							esc_html__( 'No', 'cardealer-helper' ) => 'false',
						),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Slider Settings', 'cardealer-helper' ),
						'save_always'      => true,
						'dependency'       => array(
							'element' => 'list_style',
							'value'   => 'with_slider',
						),
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Loop', 'cardealer-helper' ),
						'param_name'       => 'data_loop',
						'value'            => array(
							esc_html__( 'Yes', 'cardealer-helper' ) => 'true',
							esc_html__( 'No', 'cardealer-helper' ) => 'false',
						),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Slider Settings', 'cardealer-helper' ),
						'save_always'      => true,
						'dependency'       => array(
							'element' => 'list_style',
							'value'   => 'with_slider',
						),
					),
					array(
						'type'             => 'cd_number_min_max',
						'heading'          => esc_html__( 'Space between two slide', 'cardealer-helper' ),
						'param_name'       => 'data_space',
						'min'              => '1',
						'max'              => '9999',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Slider Settings', 'cardealer-helper' ),
						'save_always'      => true,
						'std'              => 20,
						'dependency'       => array(
							'element' => 'list_style',
							'value'   => 'with_slider',
						),
					),
				),
			)
		);
	}
}
add_action( 'vc_before_init', 'cdhl_ourclients_shortcode_vc_map' );
