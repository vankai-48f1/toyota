<?php
/**
 * CarDealer Visual Composer Car condition carousel Shortcode
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package car-dealer-helper/functions
 */

add_shortcode( 'cars_condition_carousel', 'cdhl_cars_condition_carousel_shortcode' );

/**
 * Shortcode HTML.
 *
 * @param array $atts .
 */
function cdhl_cars_condition_carousel_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'makes'              => '',
			'number_of_item'     => 5,
			'carousel_layout'    => 'carousel_1',
			'condition_tabs'     => '',
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
			'hide_sold_vehicles' => false,
			'section_label'      => esc_html__( 'which vehicle You need?', 'cardealer-helper' ),
		),
		$atts
	);

	extract( $atts );

	if ( empty( $condition_tabs ) ) {
		return;
	}
	ob_start();
	$uid = uniqid();

	if ( 'true' === (string) $arrow ) {
		$arrow = 'true';
	} else {
		$arrow = 'false';
	}
	if ( 'true' === (string) $dots ) {
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

	$list_style = cardealer_get_inv_list_style();

	// Compare Cars.
	if ( isset( $_COOKIE['cars'] ) && ! empty( $_COOKIE['cars'] ) ) {
		$car_in_compare = json_decode( $_COOKIE['cars'] );
	}
	?>
	<div class="cars_condition_carousel-wrapper">
		<?php
		$item_wrapper_classes = array(
			'cars_condition_carousel-items',
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
				'data-lazyload'  => 'data-lazyload="' . cardealer_lazyload_enabled() . '"',
			);
			$item_wrapper_attrs = implode( ' ', array_filter( array_unique( $item_wrapper_attrs ) ) );
			if ( $item_wrapper_attrs && ! empty( $item_wrapper_attrs ) ) {
				$item_wrapper_attr = $item_wrapper_attrs;
			}
		}
		$item_wrapper_classes = implode( ' ', array_filter( array_unique( $item_wrapper_classes ) ) );
		// Empty vehicle msg.
		$empty_msg = apply_filters( 'vehicle_conditions_tabs_empty_msg', esc_html__( 'No Vehicles found!', 'cardealer-helper' ) );
		?>

		<div id="tabs-<?php echo esc_attr( $uid ); ?>" class="cardealer-tabs clearfix">
			<h6><?php echo esc_html( $section_label ); ?> </h6>
			<ul class="tabs pull-right">
				<?php
				$condition_tabs = explode( ',', $condition_tabs );
				foreach ( $condition_tabs as $key => $vehicle_condition ) {
					$active_class = ( 0 === (int) $key ) ? 'active' : '';
					$vehicle_cond = get_term_by( 'slug', $vehicle_condition, 'car_condition' );
					$conditions   = ( ! empty( $vehicle_cond ) && isset( $vehicle_cond->name ) ) ? $vehicle_cond->name : '';

					$vehicles = cdhl_get_condition_tab_vehicles( $vehicle_condition, $makes, $number_of_item, $vehicle_category, $hide_sold_vehicles );
					if ( is_wp_error( $vehicles ) || empty( $vehicles ) ) {
						continue;
					}
					if ( ! empty( $conditions ) && ( ! is_wp_error( $vehicles ) && ! empty( $vehicles ) ) ) {
						?>

						<li data-tabs="tab-<?php echo esc_attr( $key ); ?>-<?php echo esc_attr( $uid ); ?>" class="<?php echo esc_attr( $active_class ); ?>">
							<?php echo esc_html( $conditions ); ?>
						</li>
						<?php
					}
				}
				?>
			</ul>
			<?php
			foreach ( $condition_tabs as $key => $vehicle_condition ) {
				// get vehicles.
				$vehicles = cdhl_get_condition_tab_vehicles( $vehicle_condition, $makes, $number_of_item, $vehicle_category, $hide_sold_vehicles );
				if ( is_wp_error( $vehicles ) || empty( $vehicles ) ) {
					continue;
				}
				$carousel_attrs = empty( $vehicles ) ? '' : $item_wrapper_attr;

				$cnt            = $vehicles->post_count;
				$carousel_attrs = explode( ' ', $carousel_attrs );
				if ( $cnt < $data_md_items ) {
					$carousel_attrs[0] = "data-nav-arrow='false'";
					$carousel_attrs[1] = "data-nav-dots='false'";
				}
				$carousel_attrs = implode( ' ', $carousel_attrs );
				?>
					<div id="tab-<?php echo esc_attr( $key ); ?>-<?php echo esc_attr( $uid ); ?>" class="<?php echo esc_attr( $item_wrapper_classes ); ?> cardealer-tabcontent" <?php echo $carousel_attrs; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>>
					<?php
					if ( 'with_silder' !== (string) $silder_type ) {
						?>
							<div class="row">
						<?php
					}

					if ( ! empty( $vehicles ) ) {
						while ( $vehicles->have_posts() ) {
							$vehicles->the_post();

							$item_classes = array(
								'item',
							);

							$car_item_classes = array(
								'car-item',
								'text-center',
								'style-' . $list_style,
							);

							if ( 'with_silder' !== (string) $silder_type ) {
								$item_classes[] = 'col-sm-' . 12 / $number_of_column;
							}

							$item_classes     = implode( ' ', array_filter( array_unique( $item_classes ) ) );
							$car_item_classes = implode( ' ', array_filter( array_unique( $car_item_classes ) ) );
							?>
								<div class='<?php echo esc_attr( $item_classes ); ?>'>
									<div class='<?php echo esc_attr( $car_item_classes ); ?> <?php echo esc_attr( $item_background ); ?>'>
								<?php
								$img_size = '';
								if ( 3 === (int) $data_md_items ) {
									$img_size = 'car_tabs_image';
								}
								$id               = get_the_ID();
								$is_hover_overlay = cardealer_is_hover_overlay();
								if ( 'classic' === (string) $list_style ) {
									?>
										<div class='car-image'>
											<?php
											/**
											 * Action called when anchor tag for vehicle link opens.
											 *
											 * @since 1.0.0
											 * @param int       $id Vehicle ID.
											 * @param string    $is_hover_overlay Enable/Disable vehicle hover effect.
											 * @visible         true
											 */
											do_action( 'cardealer_car_loop_link_open', $id, $is_hover_overlay );

											/**
											* Action called before vehicle overlay contents.
											*
											* @since 1.0.0
											*
											* @param int       $id             Vehicle ID
											* @param boolean   $output_type    Weather to return Or echo the response
											* @hooked cardealer_get_cars_condition - 10
											* @hooked cardealer_get_cars_status - 20
											* @visible true
											*/
											do_action( 'car_before_overlay_banner', $id, true );
											if ( ( 'with_silder' !== (string) $silder_type ) || ( $cnt < $data_md_items ) ) {
												echo ( function_exists( 'cardealer_get_cars_image' ) ) ? cardealer_get_cars_image( $img_size ) : ''; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
											} else {
												echo ( function_exists( 'cardealer_get_cars_owl_image' ) ) ? cardealer_get_cars_owl_image( $img_size ) : ''; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
											}

											if ( 'yes' === (string) $is_hover_overlay ) {
												?>

												<div class="car-overlay-banner">
													<ul>
														<?php
														/**
														 * Action called when vehicle overlay is displayed for classic layout with grid style.
														 *
														 * @since 1.0.0
														 * @param int       $id Vehicle ID
														 * @visible         true
														 *
														 * @hooked cardealer_compare_cars_overlay_link - 10
														 * @hooked cardealer_images_cars_overlay_link - 20 30
														 */
														do_action( 'vehicle_classic_grid_overlay', $id );
														?>
													</ul>
												</div>
												<?php
											}
											/**
											 * Action called when vehicle anchor tag in list closed.
											 *
											 * @since 1.0.0
											 *
											 * @param int       $id Vehicle ID
											 * @param string    $is_hover_overlay True/False for show/hide vehicle overlay effect.
											 * @visible true
											 */
											do_action( 'cardealer_car_loop_link_close', $id, $is_hover_overlay );
											?>
										</div>
										<div class='car-content'>
											<?php
											/**
											 * Action called when vehicle title is displayed for classic layout style.
											 *
											 * @since 1.0.0
											 *
											 * @hooked cardealer_list_car_link_title - 10
											 * @visible true
											 */

											do_action( 'cardealer_classic_list_car_title' );
											cardealer_car_price_html();
											cardealer_get_cars_list_attribute();
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
										/**
										 * Action called when anchor tab for vehicle details is opened.

										 * @since 1.0.0

										 * @param int       $id Vehicle ID.
										 * @param string    $is_hover_overlay Enable/Disable vehicle hover effect.
										 * @visible         true
										 */
										do_action( 'cardealer_car_loop_link_open', $id, $is_hover_overlay );

										/**
										 * Action called before vehicle overlay contents.
										 *
										 * @since 1.0.0
										 *
										 * @param int       $id             Vehicle ID
										 * @param boolean   $output_type    Weather to return Or echo the response
										 * @hooked cardealer_get_cars_condition - 10
										 * @hooked cardealer_get_cars_status - 20
										 * @visible true
										 */
										do_action( 'car_before_overlay_banner', $id, true );

										if ( ( 'with_silder' != $silder_type ) || ( $cnt < $data_md_items ) || ( 'true' !== (string) $data_loop ) ) {
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
														 * Action called when vehicle overlay contents are displayed.
														 *
														 * @since 1.0.0
														 *
														 * @param int $id Vehicle ID
														 * @hooked cardealer_view_cars_overlay_link - 10
														 * @hooked cardealer_compare_cars_overlay_link - 20
														 * @hooked cardealer_images_cars_overlay_link - 30
														 * @visible true
														 */
														do_action( 'car_overlay_banner', $id );
														?>
													</ul>
												</div>
												<?php
										}

										/**
										 * Action called when vehicle anchor tag in list closed.
										 *
										 * @since 1.0.0
										 *
										 * @param int       $id Vehicle ID
										 * @param string    $is_hover_overlay True/False for show/hide vehicle overlay effect.
										 * @visible true
										 */
										do_action( 'cardealer_car_loop_link_close', $id, $is_hover_overlay );
										cardealer_get_cars_list_attribute();
										?>
										</div>
										<div class='car-content'>
										<?php
										/**
										 * Action called when vehicle title is shown in default view layout.
										 *
										 * @since 1.0.0
										 *
										 * @hooked cardealer_list_car_link_title - 5
										 * @hooked cardealer_list_car_title_separator - 10
										 * @visible true
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
								</div>
								<?php
						}
						wp_reset_postdata();
					} else {
						?>
							<p><?php echo esc_html( $empty_msg ); ?></p>
						<?php
					}
					if ( 'with_silder' !== (string) $silder_type ) {
						?>
							</div>
						<?php
					}
					?>
						</div>
				<?php
			}
			?>
			</div><!-- .cars_condition_carousel-wrapper -->
		</div>
	<?php
	return ob_get_clean();
}


/**
 * Shortcode mapping.
 *
 * @return void
 */
function cdhl_cars_condition_carousel_shortcode_integrateWithVC() {
	// Build vehicles conditions array.
	$conditions_terms = get_terms(
		array(
			'taxonomy'   => 'car_condition',
			'hide_empty' => true,
		)
	);
	$condition_array  = array();

	if ( ! empty( $conditions_terms ) && ! is_wp_error( $conditions_terms ) ){
		foreach ( $conditions_terms as $terms ) {
			$condition_array[ $terms->name ] = $terms->slug;
		}
	}

	$car_condition_label   = cardealer_get_field_label_with_tax_key( 'car_condition' );
	$p_car_condition_label = cardealer_get_field_label_with_tax_key( 'car_condition', 'plural' );
	$car_make_label        = cardealer_get_field_label_with_tax_key( 'car_make' );
	$p_car_make_label      = cardealer_get_field_label_with_tax_key( 'car_make', 'plural' );

	if ( function_exists( 'vc_map' ) ) {
		$car_categories = cdhl_get_terms( array( 'taxonomy' => 'car_make' ) );
		$vehicle_cat    = cdhl_get_terms( array( 'taxonomy' => 'vehicle_cat' ) );
		$array1         = array(
			array(
				'type'       => 'cd_radio_image_2',
				'heading'    => esc_html__( 'Style', 'cardealer-helper' ),
				'param_name' => 'carousel_layout',
				'options'    => array(
					array(
						'value' => 'carousel_1',
						'title' => 'Style 1',
						'image' => trailingslashit( CDHL_VC_URL ) . 'vc_images/options/cd_carousel/carousel_1.png',
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
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Section Title', 'cardealer-helper' ),
				'description' => esc_html__( 'Enter tab section label', 'cardealer-helper' ),
				'param_name'  => 'section_label',
				'value'       => esc_html__( 'which vehicle You need?', 'cardealer-helper' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => $car_condition_label . esc_html__( ' Tab', 'cardealer-helper' ),
				'param_name'  => 'condition_tabs',
				'description' => sprintf( esc_html__( 'Select at least two vehicle "%1$s" to include in tabs. If no selected, then no vehicle will display.', 'cardealer-helper' ), $p_car_condition_label ),
				'value'       => $condition_array,
				'admin_label' => true,
			),
			array(
				'type'             => 'checkbox',
				'heading'          => esc_html__( 'Hide Sold Vehicles', 'cardealer-helper' ),
				'param_name'       => 'hide_sold_vehicles',
				'description'      => esc_html__( 'Check this checkbox to hide sold vehicles.', 'cardealer-helper' ),
				'save_always'      => true,
				'edit_field_class' => 'vc_col-sm-6 vc_column',
				'admin_label'      => true,
			),
			/*-------------------------- Grid Settings --------------------------*/
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

			/*----------------------- Carousel Settings ---------------------*/
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

			/*----------------------------- Posts Settings ----------------------*/
			array(
				'type'        => 'cd_number_min_max',
				'class'       => '',
				'heading'     => esc_html__( 'Number of item', 'cardealer-helper' ),
				'param_name'  => 'number_of_item',
				'min'         => '1',
				'max'         => '9999',
				'description' => esc_html__( 'Select Number of items to display.', 'cardealer-helper' ),
				'value'       => 5,
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
					'description'      => esc_html__( 'Select vehicle category to limit result from. To display result from all categories, leave all categories unselected.', 'cardealer-helper' ),
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
				'param_name'  => 'makes',
				'description' => sprintf( esc_html__( 'Select "%1$s" to limit result from. To display result from all "%2$s", leave all "%3$s" unselected.', 'cardealer-helper' ), $car_make_label, $p_car_make_label, $p_car_make_label ),
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
				'name'     => esc_html__( 'Potenza Vehicles Conditions Tabs', 'cardealer-helper' ),
				'base'     => 'cars_condition_carousel',
				'class'    => '',
				'icon'     => cardealer_vc_shortcode_icon( 'cars_condition_carousel' ),
				'category' => esc_html__( 'Potenza', 'cardealer-helper' ),
				'params'   => array_merge( $array1, $array2, $array3 ),
			)
		);
	}
}
add_action( 'vc_before_init', 'cdhl_cars_condition_carousel_shortcode_integrateWithVC' );
