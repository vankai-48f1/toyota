<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * CarDealer Visual Composer vertical multi tabs Shortcode
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package car-dealer-helper/functions
 */

add_shortcode( 'cd_vertical_multi_tabs', 'cdhl_shortcode_vertical_multi_tabs' );

/**
 * Shortcode HTML.
 *
 * @param array $atts .
 */
function cdhl_shortcode_vertical_multi_tabs( $atts ) {
	extract(
		shortcode_atts(
			array(
				'number_of_item'     => 5,
				'padding'            => 0,
				'tab_type'           => 'pgs_new_arrivals',
				'car_make_slugs'     => '',
				'hide_sold_vehicles' => false,
				'number_of_column'   => '',
				'element_id'         => uniqid(),
				'vehicle_category'   => '',
			),
			$atts
		)
	);
	extract( $atts );
	if ( empty( $car_make_slugs ) ) {
		return;
	}

	// Car compare code.
	if ( isset( $_COOKIE['cars'] ) && ! empty( $_COOKIE['cars'] ) ) {
		$car_in_compare = json_decode( $_COOKIE['cars'] );
	}

	$car_make_slugs = explode( ',', $car_make_slugs );
	$list_style     = cardealer_get_inv_list_style();

	ob_start();?>
	<div class="tab-vertical tabs-left">
		<div class="left-tabs-block">
			<ul class="nav nav-tabs vartical-tab-nav" id="vertical-tab">
				<?php
				$i = 1;
				foreach ( $car_make_slugs as $tab ) {
					$term         = get_term_by( 'slug', $tab, 'car_make' );
					$make_name    = ( ! is_wp_error( $term ) && ! empty( $term ) ) ? $term->name : '';
					$make_term_id = ( ! is_wp_error( $term ) && ! empty( $term ) ) ? $term->term_id : '';
					?>
					<li class="<?php echo ( 1 === (int) $i ) ? 'active' : ''; ?>"><a href="#<?php echo esc_attr( $make_term_id . '_' . $element_id ); ?>" data-toggle="tab"><?php echo esc_html( $make_name ); ?></a></li>
					<?php
					$i++;
				};
				?>

			</ul>
			<?php
			$images_url = ( ! empty( $atts['advertise_img'] ) ? $atts['advertise_img'] : '' );
			if ( ! empty( $images_url ) ) {
				$img_url = wp_get_attachment_url( $images_url, 'full' );
				$img_alt = get_post_meta( $images_url, '_wp_attachment_image_alt', true );
			}
			if ( ! empty( $img_url ) ) {
				?>
			<div class="ads-img">
				<?php if ( cardealer_lazyload_enabled() ) { ?>
				<img class="cardealer-lazy-load" data-src="<?php echo esc_html( $img_url ); ?>" src="<?php echo esc_url( LAZYLOAD_IMG ); ?>">
			<?php } else { ?>
				<img class="" src="<?php echo esc_html( $img_url ); ?>" alt="<?php echo esc_html( $img_alt ); ?>">
			<?php } ?>
			</div>
			<?php } ?>
		</div>

		<?php
		$args = array(
			'post_type'      => 'cars',
			'posts_status'   => 'publish',
			'posts_per_page' => $number_of_item,
		);

		// meta_query for sold/unsold vehicles.
		$car_status_query = array();
		if ( isset( $hide_sold_vehicles ) && ( true === (bool) $hide_sold_vehicles ) ) {
			$car_status_query = array(
				'key'     => 'car_status',
				'value'   => 'sold',
				'compare' => '!=',
			);
		}

		if ( 'pgs_featured' === (string) $tab_type ) {
			/* Featured cars */
			$args['meta_query'] = array(
				'relation' => 'AND',
				array(
					'key'     => 'featured',
					'value'   => '1',
					'compare' => '=',
				),
				$car_status_query,
			);
		} elseif ( 'pgs_on_sale' === (string) $tab_type ) {
			/* On Sale cars */
			$args['meta_query']     = array(
				'relation' => 'AND',
				array(
					'key'     => 'sale_price',
					'value'   => '',
					'compare' => '!=',
					'type'    => 'NUMERIC',
				),
				array(
					'key'     => 'regular_price',
					'value'   => '',
					'compare' => '!=',
					'type'    => 'NUMERIC',
				),
				$car_status_query,
			);
			$args['meta_value_num'] = 'sale_price';
			$args['orderby']        = 'meta_value_num';
			$args['order']          = 'asc';
		} elseif ( 'pgs_cheapest' === (string) $tab_type ) {
			/* Cheapest Product */
			unset( $args['meta_query'] );
			$args['meta_query']     = array(
				'relation' => 'AND',
				array(
					'key'     => 'final_price',
					'value'   => '',
					'compare' => '!=',
					'type'    => 'NUMERIC',
				),
				$car_status_query,
			);
			$args['meta_value_num'] = 'final_price';
			$args['orderby']        = 'meta_value_num';
			$args['order']          = 'asc';
		} else {
			$args['meta_query'] = array(
				$car_status_query,
			);
		}
		$vehicle_category = trim( $vehicle_category );
		if ( ! empty( $vehicle_category ) ) {
			$vehicle_cat_array = array(
				'taxonomy' => 'vehicle_cat',
				'field'    => 'slug',
				'terms'    => $vehicle_category,
			);
		}
		?>
		<div class="tab-content column-<?php echo esc_attr( $number_of_column ); ?>">
			<?php
			$t = 1;
			foreach ( $car_make_slugs as $tab ) :
				$term         = get_term_by( 'slug', $tab, 'car_make' );
				$make_term_id = ( ! is_wp_error( $term ) && ! empty( $term ) ) ? $term->term_id : '';
				$get_id       = $make_term_id . '_' . $element_id;

				$car_make_array = array(
					'taxonomy' => 'car_make',
					'field'    => 'slug',
					'terms'    => $tab,
				);
				if ( ! empty( $vehicle_category ) ) {
					$args['tax_query'] = array(
						'relation' => 'AND',
						$vehicle_cat_array,
						$car_make_array,
					);
				} else {
					$args['tax_query'] = array(
						$car_make_array,
					);
				}

				$loop = new WP_Query( $args );
				?>
				<div class="tab-pane <?php echo ( 1 === (int) $t ) ? 'active' : ''; ?>" id="<?php echo esc_attr( $get_id ); ?>">
					<?php
					if ( $loop->have_posts() ) {
						while ( $loop->have_posts() ) :
							$loop->the_post();
							$cars_cat_slug = '';
							$terms         = get_the_terms( get_the_ID(), 'car_make' );
							if ( empty( $terms ) ) {
								$style = '';
								if ( isset( $padding ) && $padding > 0 ) {
									$style = 'style="padding:' . $padding . 'px"';
								}
								echo '<div class="grid-item no-data ' . $get_id . '" ' . $style . '><div class="no-data-found">No vehicle found.</div></div>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
							} else {

								foreach ( $terms  as $term ) {
									$cars_cat_id   = $term->term_id;
									$cars_cat_slug = $term->slug . '_' . $element_id;
								}
								$style = '';
								if ( isset( $padding ) && $padding > 0 ) {
									$style = 'style="padding:' . $padding . 'px"';
								}
								?>

								<div class="grid-item <?php echo esc_attr( $cars_cat_slug ); ?>" <?php echo $style; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>>
									<div class="car-item text-center car-item-2 style-<?php echo esc_attr( $list_style ); ?>">
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
											echo ( function_exists( 'cardealer_get_cars_image' ) ) ? cardealer_get_cars_image( 'car_tabs_image' ) : ''; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
											if ( 'yes' === (string) $is_hover_overlay ) {
												?>

											<div class='car-overlay-banner'>
												<ul>
													<?php
													if ( 'classic' === (string) $list_style ) {
														/**
														 * Hook vehicle_classic_grid_overlay.
														 *
														 * @hooked cardealer_compare_cars_overlay_link - 10
														 * @hooked cardealer_images_cars_overlay_link - 10
														 */
														do_action( 'vehicle_classic_grid_overlay', $id );
													} else {
														/**
														 * Hook car_overlay_banner.
														 *
														 * @hooked cardealer_view_cars_overlay_link - 10
														 * @hooked cardealer_compare_cars_overlay_link - 20
														 * @hooked cardealer_images_cars_overlay_link - 30
														 */
														do_action( 'car_overlay_banner', $id );
													}
													?>

												</ul>
											</div>
												<?php
											}
											do_action( 'cardealer_car_loop_link_close', $id, $is_hover_overlay );
											?>

										</div>
										<?php
										if ( 'classic' === (string) $list_style ) {
											?>
											<div class='car-content'>
												<?php
												/**
												 * Hook cardealer_list_car_title.
												 *
												 * @hooked cardealer_list_car_link_title - 10
												 */
												do_action( 'cardealer_classic_list_car_title' );
												cardealer_get_cars_list_attribute();
												cardealer_get_vehicle_review_stamps( $id );
												?>
												<ul class="car-bottom-actions classic-grid">
												<?php
													cardealer_classic_view_cars_overlay_link( $id );
													cardealer_classic_vehicle_video_link( $id );
												?>
												</ul>
												<?php
												cardealer_car_price_html();
												?>

											</div>
											<?php
										} else {
											cardealer_get_cars_list_attribute();
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
												cardealer_get_vehicle_review_stamps( $id );
												cardealer_car_price_html();
												?>

											</div>
											<?php
										}
										?>

									</div>
								</div>
								<?php
							}
						endwhile;
						wp_reset_postdata();
						/*End cars type tab */
					} else {
						$style = '';
						if ( isset( $padding ) && $padding > 0 ) {
							$style = 'style="padding:' . $padding . 'px"';
						}
						echo '<div class="grid-item no-data ' . $get_id . '" ' . $style . '><div class="no-data-found">No vehicle found.</div></div>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE

					}
					?>
				</div>
				<?php
				$t++;
			endforeach;
			?>

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
function cdhl_vertical_multi_tabs_shortcode_vc_map() {
	if ( function_exists( 'vc_map' ) ) {
		$car_make       = array();
		$vehicle_cat    = cdhl_get_terms( array( 'taxonomy' => 'vehicle_cat' ) );
		$car_make_label = cardealer_get_field_label_with_tax_key( 'car_make' );
		$car_make       = cdhl_get_terms(
			array( // You can pass arguments from get_terms (except hide_empty).
				'taxonomy' => 'car_make',
			)
		);

		$array1      = array(
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Display Vehicles Type', 'cardealer-helper' ),
				'description' => esc_html__( 'Select vehicles types. which will be display at frontend', 'cardealer-helper' ),
				'param_name'  => 'tab_type',
				'value'       => array(
					esc_html__( 'Newest', 'cardealer-helper' ) => 'pgs_new_arrivals',
					esc_html__( 'Featured', 'cardealer-helper' ) => 'pgs_featured',
					esc_html__( 'On sale', 'cardealer-helper' ) => 'pgs_on_sale',
					esc_html__( 'Cheapest', 'cardealer-helper' ) => 'pgs_cheapest',
				),
				'save_always' => true,
			),
			array(
				'type'        => 'attach_image',
				'heading'     => esc_html__( 'Advertise banner image', 'cardealer-helper' ),
				'param_name'  => 'advertise_img',
				'description' => esc_html__( 'Select image.', 'cardealer-helper' ),
			),
		);
		$array2      = array();
		if ( isset( $vehicle_cat ) && ! empty( $vehicle_cat ) ) {
			$vehicle_cat_array = array( 'Select' => '' ) + $vehicle_cat;

			$array2 = array(
				array(
					'type'             => 'dropdown',
					'heading'          => esc_html__( 'Vehicle Category', 'cardealer-helper' ),
					'param_name'       => 'vehicle_category',
					'value'            => $vehicle_cat_array,
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'save_always'      => true,
					'admin_label'      => true,
					'default'          => '',
				),
			);
		}
		$array3 = array(
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Select ', 'cardealer-helper' ) . $car_make_label,
				'description' => sprintf( esc_html__( 'If no "%1$s" selected then no vehicle will be shown.', 'cardealer-helper' ), $car_make_label ),
				'param_name'  => 'car_make_slugs',
				'value'       => $car_make,
			),
			array(
				'type'             => 'checkbox',
				'heading'          => esc_html__( 'Hide sold vehicles', 'cardealer-helper' ),
				'param_name'       => 'hide_sold_vehicles',
				'description'      => esc_html__( 'Check this checkbox to hide sold vehicles.', 'cardealer-helper' ),
				'save_always'      => true,
				'edit_field_class' => 'vc_col-sm-6 vc_column',
			),
			array(
				'type'             => 'cd_number_min_max',
				'class'            => '',
				'edit_field_class' => 'vc_col-sm-6 vc_column',
				'heading'          => esc_html__( 'Number of item', 'cardealer-helper' ),
				'param_name'       => 'number_of_item',
			),
			array(
				'type'             => 'cd_number_min_max',
				'class'            => '',
				'edit_field_class' => 'vc_col-sm-6 vc_column',
				'heading'          => esc_html__( 'Padding', 'cardealer-helper' ),
				'param_name'       => 'padding',
				'min'              => '1',
				'max'              => '200',
			),
		);
		$params = array_merge( $array1, $array2, $array3 );
		vc_map(
			array(
				'name'     => esc_html__( 'Potenza Verticular Multi Tabs', 'cardealer-helper' ),
				'base'     => 'cd_vertical_multi_tabs',
				'class'    => 'cardealer_helper_element_wrapper',
				'icon'     => cardealer_vc_shortcode_icon( 'cd_vertical_multi_tabs' ),
				'controls' => 'full',
				'category' => esc_html__( 'Potenza', 'cardealer-helper' ),
				'params'   => $params,
			)
		);
	}
}
add_action( 'vc_before_init', 'cdhl_vertical_multi_tabs_shortcode_vc_map' );
