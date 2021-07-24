<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * CarDealer Visual Composer multi tab Shortcode
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package car-dealer-helper/functions
 */

add_shortcode( 'cd_multi_tabs', 'cdhl_shortcode_multi_tabs' );

/**
 * Shortcode HTML.
 *
 * @param array $atts .
 */
function cdhl_shortcode_multi_tabs( $atts ) {
	extract(
		shortcode_atts(
			array(
				'number_of_item'     => 5,
				'number_of_column'   => 4,
				'multi_tabs'         => 'multi_tab_1',
				'tab_type'           => 'pgs_new_arrivals',
				'car_make_slugs'     => array(),
				'element_id'         => uniqid(),
				'vehicle_category'   => '',
				'hide_sold_vehicles' => false,
			),
			$atts
		)
	);
	extract( $atts );

	if ( empty( $car_make_slugs ) ) {
		return;
	}
	$list_style = cardealer_get_inv_list_style();

	// car compare code.
	if ( isset( $_COOKIE['cars'] ) && ! empty( $_COOKIE['cars'] ) ) {
		$car_in_compare = json_decode( $_COOKIE['cars'] );
	}

	$car_make_slugs      = explode( ',', $car_make_slugs );
	$term                = get_term_by( 'slug', $car_make_slugs[0], 'car_make' );
	$make_active_term_id = ( ! is_wp_error( $term ) && ! empty( $term ) ) ? $term->term_id : '';
	$unique_number       = wp_rand( 0, 9999 ) . time();
	ob_start();
	?>
	<div class="isotope-wrapper" data-layout="grid" data-unique_class="<?php echo esc_attr( $element_id ); ?>">
		<div class="isotope-filters multi-tab multi-tab-isotope-filter  multi-tab-isotope-filter-<?php echo esc_attr( $element_id ); ?>">
			<div class="isotope-filters">
			<?php
			foreach ( $car_make_slugs as $tab ) :
				$term         = get_term_by( 'slug', $tab, 'car_make' );
				$make_name    = ( ! is_wp_error( $term ) && ! empty( $term ) ) ? $term->name : '';
				$make_term_id = ( ! is_wp_error( $term ) && ! empty( $term ) ) ? $term->term_id : '';
				$d_filter     = $make_term_id . $unique_number;
				if ( 0 === (int) $term->count ) {
					$d_filter = $unique_number;
				}
				?>
				<button
				<?php
				if ( (string) $make_active_term_id === (string) $make_term_id ) {
					echo 'class="active"';}
				?>
				data-filter="<?php echo $d_filter; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>"><?php echo esc_html( $make_name ); ?></button>
				<?php
			endforeach;
			?>
			</div>
		</div>
		<div class="<?php echo esc_attr( $element_id ); ?>">
			<div class="horizontal-tabs isotope cd-multi-tab-isotope-<?php echo esc_attr( $element_id ); ?> column-<?php echo esc_attr( $number_of_column ); ?> filter-container">
				<?php
				global $car_dealer_options;
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
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'car_make',
						'field'    => 'slug',
						'terms'    => 'sss',
					),
				);

				foreach ( $car_make_slugs as $tab ) :

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
					$get_term         = get_term_by( 'slug', $tab, 'car_make' );
					$make_term_id     = ( ! is_wp_error( $get_term ) && ! empty( $term ) ) ? $get_term->term_id : '';
					$is_hover_overlay = cardealer_is_hover_overlay();
					$loop             = new WP_Query( $args );
					$data             = '';
					if ( $loop->have_posts() ) {
						while ( $loop->have_posts() ) :
							$loop->the_post();
							$cars_cat_slug = '';
							$terms         = get_the_terms( get_the_ID(), 'car_make' );
							if ( empty( $terms ) ) {
								$style = '';
								if ( isset( $padding ) && $padding > 0 ) {
									$style = 'style="padding:' . $padding . 'px;"';
								}
								echo '<div class="grid-item no-data ' . $make_term_id . $unique_number . '" ' . $style . ' data-groups="[' . esc_attr( $make_term_id . $unique_number ) . ']"><div class="no-data-found">' . esc_html__( 'No vehicle found', 'cardealer-helper' ) . '.</div></div>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
							} else {
								foreach ( $terms  as $term ) {
									$cars_cat_id    = $term->term_id;
									$cars_cat_slug .= $make_term_id . $unique_number;
									$carscatslug[]  = $term->slug;
								}
								$style = '';
								if ( isset( $padding ) && $padding > 0 ) {
									$style = 'style="padding:' . $padding . 'px"';
								}
								?>
								<div class="grid-item <?php echo esc_attr( $cars_cat_slug ); ?> grid-item-<?php echo esc_attr( $element_id ); ?>" <?php echo $style; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?> data-groups="[<?php echo esc_attr( $cars_cat_slug ); ?>]">
										<?php
										$id = get_the_ID();
										if ( 'multi_tab_2' === (string) $multi_tabs ) {
											?>
											<div class='car-item text-center style-<?php echo esc_attr( $list_style ); ?>'>
											<?php
											if ( 'classic' === (string) $list_style ) {
												?>
												<div class='car-image'>
													<?php
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
																/**
																 * Hook vehicle_classic_grid_overlay.
																 *
																 * @hooked cardealer_compare_cars_overlay_link - 10
																 * @hooked cardealer_images_cars_overlay_link - 10
																 */
																do_action( 'vehicle_classic_grid_overlay', $id );
																?>

															</ul>
														</div>
														<?php
													}
													do_action( 'cardealer_car_loop_link_close', $id, $is_hover_overlay );
													?>
												</div>
												<div class='car-content'>
													<?php
													/**
													 * Hook cardealer_classic_list_car_title.
													 *
													 * @hooked cardealer_list_car_link_title - 10
													 */

													do_action( 'cardealer_classic_list_car_title' );
													cardealer_car_price_html();
													cardealer_get_cars_list_attribute( $id );
													cardealer_get_vehicle_review_stamps( $id );
													?>
													<ul class="car-bottom-actions classic-grid">
													<?php
														cardealer_classic_view_cars_overlay_link( $id );
														cardealer_classic_vehicle_video_link( $id );
													?>
													</ul>
												</div>
												<?php
											} else {
												?>
												<div class='car-image'>
													<?php
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
													if ( function_exists( 'cardealer_get_cars_list_attribute' ) ) {
														cardealer_get_cars_list_attribute( $id );}
													?>
												</div>
												<div class='car-content'>
												<?php
													/**
													 * Hook cardealer_list_car_title.
													 *
													 * @hooked cardealer_list_car_link_title - 5
													 * @hooked cardealer_list_car_title_separator - 10
													 */
													do_action( 'cardealer_list_car_title' );
													cardealer_car_price_html();
													cardealer_get_vehicle_review_stamps( $id );
												?>
												</div>
												<?php
											}
											?>
											</div>
											<?php
										} else {
											?>
											<div class="car-item-3">
												<?php echo cardealer_get_cars_image( 'car_tabs_image' ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>
												<div class="car-popup">
													<a class="popup-img" href="<?php echo esc_url( get_the_permalink() ); ?>"><i class="fas fa-plus"></i></a>
												</div>
												<div class="car-overlay text-center">
												<a class="link" href="<?php echo esc_url( get_the_permalink() ); ?>"><?php the_title(); ?></a>
												</div>
											</div>
											<?php
										}
										?>

								</div>
								<?php
							}
						endwhile;
						wp_reset_postdata();
					} else {
						$style = 'style=""';
						if ( isset( $padding ) && $padding > 0 ) {
							$style = 'style="padding:' . $padding . 'px;"';
						}
						echo '<div class="grid-item no-data ' . $make_term_id . '_' . $element_id . '" ' . $style . ' data-groups="[' . esc_attr( $make_term_id . $unique_number ) . ']"><div class="no-data-found">' . esc_html__( 'No vehicle found', 'cardealer-helper' ) . '.</div></div>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
					}
				endforeach;
				/*End cars type tab */
				?>
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
function cdhl_multi_tabs_shortcode_vc_map() {
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
				'type'       => 'cd_radio_image',
				'heading'    => esc_html__( 'Tabs type', 'cardealer-helper' ),
				'param_name' => 'multi_tabs',
				'options'    => cdhl_get_shortcode_param_data( 'cd_multi_tabs' ),
			),
			array(
				'type'             => 'cd_number_min_max',
				'class'            => '',
				'edit_field_class' => 'vc_col-sm-4 vc_column',
				'heading'          => esc_html__( 'Number of item', 'cardealer-helper' ),
				'param_name'       => 'number_of_item',
				'admin_label'      => false,
				'min'              => '1',
				'value'            => '1',
			),
			array(
				'type'             => 'cd_number_min_max',
				'heading'          => esc_html__( 'Padding', 'cardealer-helper' ),
				'edit_field_class' => 'vc_col-sm-4 vc_column',
				'param_name'       => 'padding',
				'min'              => '1',
				'max'              => '200',
			),
			array(
				'type'             => 'dropdown',
				'heading'          => esc_html__( 'Number of column', 'cardealer-helper' ),
				'param_name'       => 'number_of_column',
				'edit_field_class' => 'vc_col-sm-4 vc_column',
				'value'            => array(
					esc_html__( '3', 'cardealer-helper' ) => '3',
					esc_html__( '4', 'cardealer-helper' ) => '4',
					esc_html__( '5', 'cardealer-helper' ) => '5',
				),
				'default'          => '4',
				'save_always'      => true,
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Select ', 'cardealer-helper' ) . $car_make_label,
				'description' => sprintf( esc_html__( 'If no "%1$s" selected then no vehicle will be shown.', 'cardealer-helper' ), $car_make_label ),
				'param_name'  => 'car_make_slugs',
				'value'       => $car_make,
			),
			array(
				'type'             => 'dropdown',
				'heading'          => esc_html__( 'Display vehicles type', 'cardealer-helper' ),
				'edit_field_class' => 'vc_col-sm-6 vc_column',
				'param_name'       => 'tab_type',
				'value'            => array(
					esc_html__( 'Newest', 'cardealer-helper' ) => 'pgs_new_arrivals',
					esc_html__( 'Featured', 'cardealer-helper' ) => 'pgs_featured',
					esc_html__( 'On sale', 'cardealer-helper' ) => 'pgs_on_sale',
					esc_html__( 'Cheapest', 'cardealer-helper' ) => 'pgs_cheapest',
				),
				'save_always'      => true,
				'description'      => esc_html__( 'Select vehicles type for tabs. which will be displayed on frontend', 'cardealer-helper' ),
			),
			array(
				'type'             => 'checkbox',
				'heading'          => esc_html__( 'Hide sold vehicles', 'cardealer-helper' ),
				'param_name'       => 'hide_sold_vehicles',
				'description'      => esc_html__( 'Check this checkbox to hide sold vehicles.', 'cardealer-helper' ),
				'save_always'      => true,
				'edit_field_class' => 'vc_col-sm-6 vc_column',
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
		$params = array_merge( $array1, $array2 );
		vc_map(
			array(
				'name'     => esc_html__( 'Potenza Multi Tabs', 'cardealer-helper' ),
				'base'     => 'cd_multi_tabs',
				'class'    => 'cardealer_helper_element_wrapper',
				'controls' => 'full',
				'icon'     => cardealer_vc_shortcode_icon( 'cd_multi_tabs' ),
				'category' => esc_html__( 'Potenza', 'cardealer-helper' ),
				'params'   => $params,
			)
		);
	}
}
add_action( 'vc_before_init', 'cdhl_multi_tabs_shortcode_vc_map' );
