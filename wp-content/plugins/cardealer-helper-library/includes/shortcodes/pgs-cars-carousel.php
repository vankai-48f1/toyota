<?php
/**
 * CarDealer Visual Composer cars carousel Shortcode
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package car-dealer-helper/functions
 */

add_shortcode( 'pgs_cars_carousel', 'cdhl_pgs_cars_carousel_shortcode' );

/**
 * Shortcode HTML.
 *
 * @param array $atts .
 */
function cdhl_pgs_cars_carousel_shortcode( $atts ) {

	$atts = shortcode_atts(
		array(
			'categories'         => '',
			'number_of_item'     => 5,
			'carousel_layout'    => 'carousel_1',
			'hide_sold_vehicles' => false,
			'custom_title'       => esc_html__( 'New Arrivals', 'cardealer-helper' ),
			'carousel_type'      => 'pgs_new_arrivals',
			'data_md_items'      => 4,
			'data_sm_items'      => 2,
			'data_xs_items'      => 1,
			'data_xx_items'      => 1,
			'data_space'         => 20,
			'dots'               => 'true',
			'arrow'              => 'true',
			'autoplay'           => 'true',
			'data_loop'          => 'true',
			'item_background'    => 'white-bg',
			'silder_type'        => 'with_silder',
			'number_of_column'   => 1,
			'vehicle_category'   => '',
		),
		$atts
	);

	extract( $atts );
	$lazyload = cardealer_lazyload_enabled();
	if ( ! empty( $custom_title ) ) {
		$title = $custom_title;
	}
	$list_style = cardealer_get_inv_list_style();
	$categories = trim( $categories );
	$args       = array(
		'post_type'      => 'cars',
		'posts_status'   => 'publish',
		'posts_per_page' => $number_of_item,

	);
	if ( ! empty( $categories ) ) {
		$categories_array = explode( ',', $categories );
		if ( is_array( $categories_array ) && ! empty( $categories_array ) ) {
			// Make wise filter.
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'car_make',
					'field'    => 'slug',
					'terms'    => $categories_array,
				),
			);
		}
	}

	$vehicle_category = trim( $vehicle_category );
	if ( ! empty( $vehicle_category ) ) {
		$vehicle_cat_array = array(
			'taxonomy' => 'vehicle_cat',
			'field'    => 'slug',
			'terms'    => $vehicle_category,
		);
		if ( isset( $args['tax_query'] ) ) {
			$car_make_array    = $args['tax_query'];
			$args['tax_query'] = array(
				'relation' => 'AND',
				$vehicle_cat_array,
				$car_make_array,
			);
		} else {
			$args['tax_query'] = array(
				$vehicle_cat_array,
			);
		}
	}

	// Meta_query for sold/unsold vehicles.
	$car_status_query = array();
	if ( isset( $hide_sold_vehicles ) && ( true === (bool) $hide_sold_vehicles ) ) {
		$car_status_query = array(
			'key'     => 'car_status',
			'value'   => 'sold',
			'compare' => '!=',
		);
	}

	if ( 'pgs_featured' === (string) $carousel_type ) {
		// Featured product.
		$args['meta_query'] = array(
			'relation' => 'AND',
			array(
				'key'     => 'featured',
				'value'   => '1',
				'compare' => '=',
			),
			$car_status_query,
		);
	} elseif ( 'pgs_on_sale' === (string) $carousel_type ) {
		// On Sale product.
		$args['meta_query'] = array(
			'relation' => 'AND',
			array(
				'key'     => 'sale_price',
				'value'   => '',
				'compare' => '!=',
			),
			$car_status_query,
		);
	} elseif ( 'pgs_cheapest' === (string) $carousel_type ) {
		// Cheapest Product.
		unset( $args['meta_query'] );
		if ( ! empty( $car_status_query ) ) {
			$args['meta_query'] = array(
				$car_status_query,
			);
		}
		$args['meta_key']       = 'regular_price';
		$args['meta_value_num'] = 'regular_price';
		$args['orderby']        = 'meta_value_num';
		$args['order']          = 'ASC';
	} else {
		$args['meta_query'] = array(
			$car_status_query,
		);
	}
	$loop = new WP_Query( $args );
	// Bail if no posts found.
	if ( ! $loop->have_posts() ) {
		return;
	}
	$cnt = $loop->post_count;
	ob_start();

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
	if ( 'true' === (string) $data_loop && $cnt >= $data_md_items ) {
		$data_loop = 'true';
	} else {
		$data_loop = 'false';
	}

	// Compare Cars.
	if ( isset( $_COOKIE['cars'] ) && ! empty( $_COOKIE['cars'] ) ) {
		$car_in_compare = json_decode( $_COOKIE['cars'] );
	}
	?>
	<div class="pgs_cars_carousel-wrapper">
		<?php
		$item_wrapper_classes = array(
			'pgs_cars_carousel-items',
		);
		$item_wrapper_attr    = '';
		if ( 'with_silder' === (string) $silder_type ) {
			$item_wrapper_classes[] = 'owl-carousel';
			$item_wrapper_classes[] = 'pgs-cars-carousel';

			$item_wrapper_attrs = array(
				'data-nav-arrow' => 'data-nav-arrow="' . esc_attr( $arrow ) . '"',
				'data-nav-dots'  => 'data-nav-dots="' . esc_attr( $dots ) . '"',
				'data-items'     => 'data-items="' . esc_attr( $data_md_items ) . '"',
				'data-md-items'  => 'data-md-items="' . esc_attr( $data_md_items ) . '"',
				'data-sm-items'  => 'data-sm-items="' . esc_attr( $data_sm_items ) . '"',
				'data-xs-items'  => 'data-xs-items="' . esc_attr( $data_xs_items ) . '"',
				'data-xx-items'  => 'data-xx-items="' . esc_attr( $data_xx_items ) . '"',
				'data-space'     => 'data-space="' . esc_attr( $data_space ) . '"',
				'data-autoplay'  => 'data-autoplay="' . esc_attr( $autoplay ) . '"',
				'data-loop'      => 'data-loop="' . esc_attr( $data_loop ) . '"',
				'data-lazyload'  => 'data-lazyload="' . esc_attr( $lazyload ) . '"',
			);
			$item_wrapper_attrs = implode( ' ', array_filter( array_unique( $item_wrapper_attrs ) ) );
			if ( $item_wrapper_attrs && ! empty( $item_wrapper_attrs ) ) {
				$item_wrapper_attr = $item_wrapper_attrs;
			}
		}
		$item_wrapper_classes = implode( ' ', array_filter( array_unique( $item_wrapper_classes ) ) );
		$img_size             = '';
		$k                    = 0;
		?>
		<div class="<?php echo esc_attr( $item_wrapper_classes ); ?>" <?php echo $item_wrapper_attr; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>>
			<?php
			if ( 'with_silder' !== (string) $silder_type ) {
				switch ( $number_of_column ) {
					case 4:
						$img_size = 'car_catalog_image';
						break;
					case 3:
						$img_size = 'car_tabs_image';
						break;
					case 2:
						$img_size = 'cardealer-homepage-thumb';
						break;
					default:
						$img_size = 'cardealer-blog-thumb';
				}
				?>
				<div class="row">
				<?php
			} else {
				if ( 3 === (int) $data_md_items ) {
					$img_size = 'car_tabs_image';
				}
			}

			$img_size = apply_filters( 'vehicle_carousel_image_size', $img_size );
			while ( $loop->have_posts() ) :
				$loop->the_post();

				$item_classes = array(
					'item',
				);

				$car_item_classes = array(
					'car-item',
					'text-center',
				);

				if ( 'carousel_2' === (string) $carousel_layout ) {
					$car_item_classes[] = 'car-item-2';
				}

				if ( 'with_silder' !== (string) $silder_type ) {
					$item_classes[] = 'col-sm-' . 12 / $number_of_column;
				}

				$item_classes     = implode( ' ', array_filter( array_unique( $item_classes ) ) );
				$car_item_classes = implode( ' ', array_filter( array_unique( $car_item_classes ) ) );
				?>
				<div class='<?php echo esc_attr( $item_classes ); ?>'>
					<div class='<?php echo esc_attr( $car_item_classes ); ?> <?php echo esc_attr( $item_background ); ?>'>
						<div class='car-image'>
							<?php
							$id               = get_the_ID();
							$is_hover_overlay = cardealer_is_hover_overlay();
							do_action( 'cardealer_car_loop_link_open', $id, $is_hover_overlay );
							/**
							 * Hook car_before_overlay_banner.
							 *
							 * @hooked cardealer_get_cars_condition - 10
							 * @hooked cardealer_get_cars_status - 20
							 */
							do_action( 'car_before_overlay_banner', $id, true );
							if ( 'with_silder' !== (string) $silder_type || 'true' !== (string) $data_loop ) {
								echo ( function_exists( 'cardealer_get_cars_image' ) ) ? cardealer_get_cars_image( $img_size ) : ''; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
							} else {
								echo ( function_exists( 'cardealer_get_cars_owl_image' ) ) ? cardealer_get_cars_owl_image( $img_size ) : ''; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
							}

							if ( 'yes' === (string) $is_hover_overlay ) {
								?>
								<div class='car-overlay-banner'>
									<ul>
										<?php
										/**
										 * Hook car_overlay_banner.
										 *
										 * @hooked cardealer_view_cars_overlay_link - 10
										 * @hooked cardealer_compare_cars_overlay_link - 20
										 * @hooked cardealer_images_cars_overlay_link - 30
										 */
										do_action( 'car_overlay_banner', $id );
										?>
									</ul>
								</div>
								<?php
							}
							do_action( 'cardealer_car_loop_link_close', $id, $is_hover_overlay );
							if ( 'carousel_2' !== (string) $carousel_layout && 'classic' !== (string) $list_style ) {
								if ( function_exists( 'cardealer_get_cars_list_attribute' ) ) {
									cardealer_get_cars_list_attribute();}
							}
							?>
						</div>
						<?php
						if ( 'carousel_2' === (string) $carousel_layout ) {
							if ( function_exists( 'cardealer_get_cars_list_attribute' ) ) {
								cardealer_get_cars_list_attribute();}
						}
						?>
						<div class='car-content'>
							<?php
							/**
							 * Hook cardealer_list_car_title.
							 *
							 * @hooked cardealer_list_car_link_title - 5
							 * @hooked cardealer_list_car_title_separator - 10
							 */
							do_action( 'cardealer_list_car_title' );
							if ( function_exists( 'cardealer_car_price_html' ) ) {
								cardealer_car_price_html();}
							cardealer_get_vehicle_review_stamps( $id );
							if ( 'carousel_2' !== (string) $carousel_layout && 'classic' === (string) $list_style ) {
								if ( function_exists( 'cardealer_get_cars_list_attribute' ) ) {
									cardealer_get_cars_list_attribute();}
							}
							?>
						</div>
					</div>
				</div>
				<?php
			endwhile;
			wp_reset_postdata();
			if ( 'with_silder' !== (string) $silder_type ) {
				?>
				</div>
				<?php
			}
			?>
		</div>
	</div><!-- .pgs_cars_carousel-wrapper -->
	<?php
	return ob_get_clean();
}

/**
 * Shortcode mapping.
 *
 * @return void
 */
function cdhl_pgs_cars_carousel_shortcode_integrateWithVC() {
	if ( function_exists( 'vc_map' ) ) {
		$car_categories = cdhl_get_terms( array( 'taxonomy' => 'car_make' ) );
		$vehicle_cat    = cdhl_get_terms( array( 'taxonomy' => 'vehicle_cat' ) );

		$car_make_label   = cardealer_get_field_label_with_tax_key( 'car_make' );
		$p_car_make_label = cardealer_get_field_label_with_tax_key( 'car_make', 'plural' );

		$array1         = array(
			array(
				'type'       => 'cd_radio_image_2',
				'heading'    => esc_html__( 'Tabs type', 'cardealer-helper' ),
				'param_name' => 'carousel_layout',
				'options'    => array(
					array(
						'value' => 'carousel_1',
						'title' => 'Style 1',
						'image' => trailingslashit( CDHL_VC_URL ) . 'vc_images/options/cd_carousel/carousel_1.png',
					),
					array(
						'value' => 'carousel_2',
						'title' => 'Style 2',
						'image' => trailingslashit( CDHL_VC_URL ) . 'vc_images/options/cd_carousel/carousel_2.png',
					),
				),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'List Style', 'cardealer-helper' ),
				'param_name'  => 'silder_type',
				'value'       => array(
					esc_html__( 'Carousel', 'cardealer-helper' )  => 'with_silder',
					esc_html__( 'Grid', 'cardealer-helper' )  => 'without_silder',
				),
				'admin_label' => true,
				'save_always' => true,
				'description' => esc_html__( 'It will display carousel slider or grid listing based on selection.', 'cardealer-helper' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Items Type', 'cardealer-helper' ),
				'param_name'  => 'carousel_type',
				'value'       => array(
					esc_html__( 'Newest', 'cardealer-helper' ) => 'pgs_new_arrivals',
					esc_html__( 'Featured', 'cardealer-helper' ) => 'pgs_featured',
					esc_html__( 'On sale', 'cardealer-helper' ) => 'pgs_on_sale',
					esc_html__( 'Cheapest', 'cardealer-helper' ) => 'pgs_cheapest',
				),
				'admin_label' => true,
				'save_always' => true,
			),
			array(
				'type'             => 'checkbox',
				'heading'          => esc_html__( 'Hide sold vehicles', 'cardealer-helper' ),
				'param_name'       => 'hide_sold_vehicles',
				'description'      => esc_html__( 'Check this checkbox to hide sold vehicles.', 'cardealer-helper' ),
				'save_always'      => true,
				'edit_field_class' => 'vc_col-sm-6 vc_column',
			),

			/*------------------------ Grid Settings -----------------------*/
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Number of column', 'cardealer-helper' ),
				'param_name'  => 'number_of_column',
				'value'       => array(
					esc_html__( '1', 'cardealer-helper' ) => '1',
					esc_html__( '2', 'cardealer-helper' ) => '2',
					esc_html__( '3', 'cardealer-helper' ) => '3',
					esc_html__( '4', 'cardealer-helper' ) => '4',
				),
				'group'       => esc_html__( 'Grid Settings', 'cardealer-helper' ),
				'dependency'  => array(
					'element' => 'silder_type',
					'value'   => array( 'without_silder' ),
				),
				'save_always' => true,
			),

			/*------------------------- Carousel Settings -----------------------*/
			array(
				'type'             => 'dropdown',
				'heading'          => esc_html__( 'Number of slide desktops per rows', 'cardealer-helper' ),
				'param_name'       => 'data_md_items',
				'value'            => array(
					esc_html__( '3', 'cardealer-helper' ) => '3',
					esc_html__( '4', 'cardealer-helper' ) => '4',
				),
				'edit_field_class' => 'vc_col-sm-6 vc_column',
				'group'            => esc_html__( 'Slider Settings', 'cardealer-helper' ),
				'save_always'      => true,
				'dependency'       => array(
					'element' => 'silder_type',
					'value'   => 'with_silder',
				),
			),
			array(
				'type'             => 'dropdown',
				'heading'          => esc_html__( 'Number of slide tablets', 'cardealer-helper' ),
				'param_name'       => 'data_sm_items',
				'value'            => array(
					esc_html__( '2', 'cardealer-helper' ) => '2',
					esc_html__( '3', 'cardealer-helper' ) => '3',
					esc_html__( '4', 'cardealer-helper' ) => '4',
				),
				'edit_field_class' => 'vc_col-sm-6 vc_column',
				'group'            => esc_html__( 'Slider Settings', 'cardealer-helper' ),
				'save_always'      => true,
				'dependency'       => array(
					'element' => 'silder_type',
					'value'   => 'with_silder',
				),
			),
			array(
				'type'             => 'dropdown',
				'heading'          => esc_html__( 'Number of slide mobile landscape', 'cardealer-helper' ),
				'param_name'       => 'data_xs_items',
				'value'            => array(
					esc_html__( '1', 'cardealer-helper' ) => '1',
					esc_html__( '2', 'cardealer-helper' ) => '2',
					esc_html__( '3', 'cardealer-helper' ) => '3',
				),
				'edit_field_class' => 'vc_col-sm-6 vc_column',
				'group'            => esc_html__( 'Slider Settings', 'cardealer-helper' ),
				'save_always'      => true,
				'dependency'       => array(
					'element' => 'silder_type',
					'value'   => 'with_silder',
				),
			),
			array(
				'type'             => 'dropdown',
				'heading'          => esc_html__( 'Number of slide mobile portrait', 'cardealer-helper' ),
				'param_name'       => 'data_xx_items',
				'value'            => array(
					esc_html__( '1', 'cardealer-helper' ) => '1',
					esc_html__( '2', 'cardealer-helper' ) => '2',
				),
				'edit_field_class' => 'vc_col-sm-6 vc_column',
				'group'            => esc_html__( 'Slider Settings', 'cardealer-helper' ),
				'save_always'      => true,
				'dependency'       => array(
					'element' => 'silder_type',
					'value'   => 'with_silder',
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
					'element' => 'silder_type',
					'value'   => 'with_silder',
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
					'element' => 'silder_type',
					'value'   => 'with_silder',
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
					'element' => 'silder_type',
					'value'   => 'with_silder',
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
					'element' => 'silder_type',
					'value'   => 'with_silder',
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
				'dependency'       => array(
					'element' => 'silder_type',
					'value'   => 'with_silder',
				),
			),

			/*----------------------------- Posts Settings ------------------------*/
			array(
				'type'        => 'cd_number_min_max',
				'class'       => '',
				'heading'     => esc_html__( 'Number of item', 'cardealer-helper' ),
				'param_name'  => 'number_of_item',
				'min'         => '1',
				'max'         => '9999',
				'std'         => '5',
				'description' => esc_html__( 'Select Number of items to display.', 'cardealer-helper' ),
				'group'       => esc_html__( 'Posts', 'cardealer-helper' ),
				'admin_label' => true,
			),
		);
		$array2 = array();
		if ( isset( $vehicle_cat ) && ! empty( $vehicle_cat ) ) {
			$vehicle_cat_array = array( 'Select' => '' ) + $vehicle_cat;

			$array2 = array(
				array(
					'type'             => 'dropdown',
					'heading'          => esc_html__( 'Vehicle Category', 'cardealer-helper' ),
					'param_name'       => 'vehicle_category',
					'value'            => $vehicle_cat_array,
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'group'            => esc_html__( 'Posts', 'cardealer-helper' ),
					'save_always'      => true,
					'admin_label'      => true,
					'default'          => '',
				),
			);
		}
		$array3 = array(
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Vehicle ', 'cardealer-helper' ) . $car_make_label,
				'param_name'  => 'categories',
				'description' => sprintf( esc_html__( 'Select vehicle "%1$s" to limit result from. To display result from all "%2$s" leave all "%3$s" unselected.', 'cardealer-helper' ), $car_make_label, $p_car_make_label, $p_car_make_label ),
				'value'       => $car_categories,
				'group'       => esc_html__( 'Posts', 'cardealer-helper' ),
				'admin_label' => true,
			),          /*------------------------------------------------ Design Settings ------------------------------------------------*/
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Item Background', 'cardealer-helper' ),
				'param_name'  => 'item_background',
				'value'       => array(
					esc_html__( 'White', 'cardealer-helper' )  => 'white-bg',
					esc_html__( 'Grey', 'cardealer-helper' )   => 'grey-bg',
				),
				'group'       => esc_html__( 'Design Settings', 'cardealer-helper' ),
				'save_always' => true,
			),
		);
		vc_map(
			array(
				'name'     => esc_html__( 'Potenza Vehicles Carousel', 'cardealer-helper' ),
				'base'     => 'pgs_cars_carousel',
				'class'    => '',
				'icon'     => cardealer_vc_shortcode_icon( 'pgs_cars_carousel' ),
				'category' => esc_html__( 'Potenza', 'cardealer-helper' ),
				'params'   => array_merge( $array1, $array2, $array3 ),
			)
		);
	}
}
add_action( 'vc_before_init', 'cdhl_pgs_cars_carousel_shortcode_integrateWithVC' );
