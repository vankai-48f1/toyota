<?php
/**
 * CarDealer Visual Composer vehicles search Shortcode
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package car-dealer-helper/functions
 */

add_shortcode( 'cd_vehicles_search', 'cdhl_cd_vehicles_search_shortcode' );

/**
 * Shortcode HTML.
 *
 * @param array $atts .
 */
function cdhl_cd_vehicles_search_shortcode( $atts ) {
	extract(
		shortcode_atts(
			array(
				'search_style'          => 'style_1',
				'search_condition_tabs' => '',
				'section_title'         => esc_html__( 'I want to Buy', 'cardealer-helper' ),
				'filter_position'       => 'top',
				'filter_background'     => 'dark',
				'src_button_label'      => esc_html__( 'Search Inventory', 'cardealer-helper' ),
				'condition_tab_lables'  => 'default'
			),
			$atts
		)
	);
	extract( $atts );

	if ( empty( $search_style ) ) {
		return;
	}

	if ( isset($condition_tab_lables) && empty($condition_tab_lables) ) {
		$condition_tab_lables = 'default'	;
	}

	// Vehicle Serach Criterias.
	$carsfilters = array(
		'car_year',
		'car_make',
		'car_model',
	);
	$vehicle_conditions = array(
		'car_year'  => esc_html__( 'Year', 'cardealer-helper' ),
		'car_make'  => esc_html__( 'Make', 'cardealer-helper' ),
		'car_model' => esc_html__( 'Model', 'cardealer-helper' ),
	);
	$vehicle_conditions = apply_filters( 'car_search_attrs', $vehicle_conditions );

	ob_start();
	$uid               = uniqid();
	$tab_label         = apply_filters( 'search_cars_tab_label', $section_title );
	$matching_vehicles = 0;
	if ( 'style_1' === (string) $search_style ) {
		?>
		<div class="slider-content vehicle-search-section text-center clearfix box <?php echo esc_attr( $filter_position ); ?> <?php echo esc_attr( $filter_background ); ?>">
			<div class="row sort-filters-box search-tab">
				<div id="tabs-<?php echo esc_attr( $uid ); ?>" class="cardealer-tabs">
					<h6> <?php echo esc_html( $tab_label ); ?></h6>
					<?php
					if ( $search_condition_tabs ) {
						$search_condition_tabs = explode( ',', $search_condition_tabs );
					}
					?>
					<ul class="tabs text-left">
						<?php
						if ( empty( $search_condition_tabs ) ) {
							$tax_conditions = cdhl_get_terms( array( 'taxonomy' => 'car_condition' ) );
							if ( "default" == $condition_tab_lables ) {
								?>
								<li data-tabs="all_vehicles-<?php echo esc_attr( $uid ); ?>" class="active">  <?php esc_html_e( 'All vehicles', 'cardealer-helper' ); ?></li>
								<?php
							} else {
								?>
								<li data-tabs="all_vehicles-<?php echo esc_attr( $uid ); ?>" class="active"><?php echo esc_html( $custom_lbl_all_vehicles ); ?></li>
								<?php
							}

							if ( ! empty($tax_conditions) ) {
								foreach ( $tax_conditions as $tax_label => $tax_slug ) {
									$tax_cond_label = ( "custom" == $condition_tab_lables ) ? $atts['custom_lbl_' . $tax_slug]  : $tax_label;
									?>
									<li data-tabs="<?php echo esc_attr( $tax_slug . '-'. $uid ); ?>"><?php echo esc_html( $tax_cond_label ); ?></li>
									<?php
								}
							}
						} else {
							$first_position = $search_condition_tabs[0];
							foreach ( $search_condition_tabs as $search_condition_slug ) {
								if ( 'all_vehicles' === $search_condition_slug ) {
									$all_vehicles_lable = ( "custom" == $condition_tab_lables ) ? $atts['custom_lbl_all_vehicles'] : esc_html__( 'All vehicles', 'cardealer-helper' );
									?>
									<li data-tabs="all_vehicles-<?php echo esc_attr( $uid ); ?>" class="<?php echo ( 'all_vehicles' === (string) $first_position ) ? 'active' : ''; ?>">
										<?php echo esc_html($all_vehicles_lable); ?>
									</li>
									<?php
								} else {
									$term = get_term_by( 'slug', $search_condition_slug, 'car_condition' );
									if ( $term ) {
										$term_label = ( "custom" == $condition_tab_lables ) ? $atts['custom_lbl_' . $term->slug]  : $term->name;
										?>
										<li data-tabs="<?php echo esc_attr( $search_condition_slug . '-' . $uid ); ?>" class="<?php echo ( $search_condition_slug === (string) $first_position ) ? 'active' : ''; ?>">
											<?php echo esc_html( $term_label ); ?>
										</li>
										<?php
									}
								}
							}
						}
						?>
					</ul>
					<?php
					if ( empty( $search_condition_tabs ) ) {
						?>
						<div id="all_vehicles-<?php echo esc_attr( $uid ); ?>" class="cardealer-tabcontent" data-condition="all_vehicles">
							<form>
								<?php
								global $wpdb;
								if ( cardealer_is_wpml_active() ) {
									$all_cars = "SELECT count(p.id) FROM $wpdb->posts p LEFT JOIN " . $wpdb->prefix . "icl_translations t
									ON p.ID = t.element_id
									WHERE p.post_type = 'cars'
									AND p.post_status = 'publish'
									AND t.language_code = '" . ICL_LANGUAGE_CODE . "'";
								} else {
									$all_cars = "SELECT count(p.id) FROM $wpdb->posts p WHERE p.post_type = 'cars' and p.post_status = 'publish'";
								}

								$matching_vehicles = $wpdb->get_var( $all_cars );
								foreach ( $vehicle_conditions as $filters => $label ) :
									$tax_terms     = cdhl_get_terms(
										array(
											'taxonomy' => $filters,
											'orderby'  => 'name',
										)
									);
									$label = cardealer_get_field_label_with_tax_key( $filters );
									?>
									<div class="col-lg-2 col-md-2 col-sm-4">
										<div class="selected-box">
											<select data-uid="<?php echo esc_attr( $uid ); ?>" id="all-cars_sort_<?php echo esc_attr( $filters . '_' . $uid ); ?>" data-id="<?php echo esc_attr( $filters ); ?>" class="selectpicker search-filters-box col-4 cd-select-box" name="<?php echo esc_attr( $filters ); ?>">
												<option value=""><?php echo esc_html( $label ); ?></option>
												<?php
												foreach ( $tax_terms as $key => $term ) :
													?>
													<option value="<?php echo esc_attr( $term ); ?>"><?php echo esc_html( $key ); ?></option>
													<?php
												endforeach;
												?>
											</select>
										</div>
									</div>
									<?php
								endforeach;
								?>
								<div class="col-lg-4 col-md-4 col-sm-12">
									<div class="form-group">
										<input id="vehicle_location" type="text" placeholder="<?php esc_html_e( 'Location', 'cardealer-helper' ); ?>" class="form-control placeholder" name="vehicle_location">
									</div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-12">
									<div class="text-center">
										<button class="button btn-block red csb-submit-btn" type="button">
										<?php
											echo ( ! empty( $src_button_label ) ) ? esc_html( $src_button_label ) : esc_html__( 'Search', 'cardealer-helper' )// phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
										?>
										</button>
									</div>
								</div>
								<div class="car-total pull-right">
									<h5 class="text-white"><i class="fas fa-caret-right"></i>(<p class="matching-vehicles"><?php echo esc_html( $matching_vehicles ); ?></p>) <span class="text-red"><?php echo esc_html__( 'Vehicles', 'cardealer-helper' ); ?></span></h5>
								</div>
							</form>
						</div>
						<?php
						if ( ! empty($tax_conditions) ) {

							foreach ( $tax_conditions as $tax_label => $tax_slug ) {
								$tax_cond_label = ( "custom" == $condition_tab_lables ) ? $atts['custom_lbl_' . $tax_slug]  : $tax_label;
								?>
								<div id="<?php echo esc_attr( $tax_slug . '-'. $uid ); ?>" class="cardealer-tabcontent" data-condition="<?php echo esc_attr( $tax_slug ); ?>">
									<form>
										<input type="hidden" name="car_condition" value="<?php echo esc_attr( $tax_slug ); ?>" />
										<?php
										$term      = get_term_by( 'slug', $tax_slug, 'car_condition' );
										$car_terms = cdhl_get_car_attrs_by_condition( array( $term->name, $tax_slug ) );
										foreach ( $car_terms as $terms ) {
											$label             = $terms['tax_label'];
											$matching_vehicles = $terms['vehicles_matched'];
											?>
											<div class="col-lg-2 col-md-2 col-sm-4">
												<div class="selected-box">
													<select data-uid="<?php echo esc_attr( $uid ); ?>" id="certified_sort_<?php echo esc_attr( $terms['taxonomy'] . '_' . $uid ); ?>" data-id="<?php echo esc_attr( $terms['taxonomy'] ); ?>" class="selectpicker search-filters-box col-4 cd-select-box" name="<?php echo esc_attr( $terms['taxonomy'] ); ?>">
														<option value=""><?php echo esc_html( $label ); ?></option>
														<?php
														foreach ( $terms['tax_terms'] as $key => $term ) {
															?>
															<option value="<?php echo esc_attr( $term['slug'] ); ?>"><?php echo esc_html( $term['name'] ); ?></option>
															<?php
														}
														?>
													</select>
												</div>
											</div>
											<?php
										}
										?>
										<div class="col-lg-4 col-md-4 col-sm-12">
											<div class="form-group">
												<input id="vehicle_location" type="text" placeholder="<?php esc_html_e( 'Location', 'cardealer-helper' ); ?>" class="form-control placeholder" name="vehicle_location">
											</div>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12">
											<div class="text-center">
												<button class="button btn-block red csb-submit-btn" type="button">
												<?php
													echo ( ! empty( $src_button_label ) ) ? esc_html( $src_button_label ) : esc_html__( 'Search', 'cardealer-helper' )// phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
												?>
												</button>
											</div>
										</div>
										<div class="car-total pull-right">
											<h5 class="text-white"><i class="fas fa-caret-right"></i>(<p class="matching-vehicles"><?php echo esc_html( $matching_vehicles ); ?></p>) <span class="text-red"><?php esc_html_e( 'Vehicles', 'cardealer-helper' ); ?></span></h5>
										</div>
									</form>
								</div>
								<?php
							}
						}
					} else {
						if ( is_array( $search_condition_tabs ) ) {
							foreach ( $search_condition_tabs as $search_condition_slug ) {
								?>
								<div id="<?php echo esc_attr( $search_condition_slug . '-' . $uid ); ?>" class="cardealer-tabcontent" data-condition="<?php echo esc_attr( $search_condition_slug ); ?>">
									<form>
										<?php
										if ( 'all_vehicles' === $search_condition_slug ) {
											global $wpdb;
											$all_cars          = "SELECT count(DISTINCT pm.post_id) FROM $wpdb->postmeta pm JOIN $wpdb->posts p ON (p.ID = pm.post_id) WHERE p.post_type = 'cars' and p.post_status = 'publish'";
											$matching_vehicles = $wpdb->get_var( $all_cars );
											foreach ( $vehicle_conditions as $filters => $label ) :
												$tax_terms     = cdhl_get_terms(
													array(
														'taxonomy' => $filters,
														'orderby'  => 'name',
													)
												);
												$label = cardealer_get_field_label_with_tax_key( $filters );
												?>
												<div class="col-lg-2 col-md-2 col-sm-4">
													<div class="selected-box">
														<select data-uid="<?php echo esc_attr( $uid ); ?>" id="all-cars_sort_<?php echo esc_attr( $filters . '_' . $uid ); ?>" data-id="<?php echo esc_attr( $filters ); ?>" class="selectpicker search-filters-box col-4 cd-select-box" name="<?php echo esc_attr( $filters ); ?>">
															<option value=""><?php echo esc_html( $label ); ?></option>
															<?php
															foreach ( $tax_terms as $key => $term ) :
																?>
																<option value="<?php echo esc_attr( $term ); ?>"><?php echo esc_html( $key ); ?></option>
																<?php
															endforeach;
															?>
														</select>
													</div>
												</div>
												<?php
											endforeach;
										} else {
											?>
											<input type="hidden" name="car_condition" value="<?php echo esc_attr( $search_condition_slug ); ?>" />
											<?php
											$term      = get_term_by( 'slug', $search_condition_slug, 'car_condition' );
											$car_terms = cdhl_get_car_attrs_by_condition( array( $term->name, $search_condition_slug ) );
											if ( ! empty( $car_terms ) ) {
												foreach ( $car_terms as $terms ) {
													$label             = $terms['tax_label'];
													$matching_vehicles = $terms['vehicles_matched'];
													?>
													<div class="col-lg-2 col-md-2 col-sm-4">
														<div class="selected-box">
															<select data-uid="<?php echo esc_attr( $uid ); ?>" id="new-cars_sort_<?php echo esc_attr( $terms['taxonomy'] . '_' . $uid ); ?>" data-id="<?php echo esc_attr( $terms['taxonomy'] ); ?>" class="selectpicker search-filters-box col-4 cd-select-box" name="<?php echo esc_attr( $terms['taxonomy'] ); ?>">
																<option value=""><?php echo esc_html( $label ); ?></option>
																<?php
																foreach ( $terms['tax_terms'] as $key => $term ) {
																	?>
																	<option value="<?php echo esc_attr( $term['slug'] ); ?>"><?php echo esc_html( $term['name'] ); ?></option>
																	<?php
																}
																?>
															</select>
														</div>
													</div>
													<?php
												}
											}
										}
										?>
										<div class="col-lg-4 col-md-4 col-sm-12">
											<div class="form-group">
												<input id="vehicle_location" type="text" placeholder="<?php esc_html_e( 'Location', 'cardealer-helper' ); ?>" class="form-control placeholder" name="vehicle_location">
											</div>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12">
											<div class="text-center">
												<button class="button btn-block red csb-submit-btn" type="button">
												<?php
												echo ( ! empty( $src_button_label ) ) ? esc_html( $src_button_label ) : esc_html__( 'Search', 'cardealer-helper' )// phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
												?>
												</button>
											</div>
										</div>
										<div class="car-total pull-right">
											<h5 class="text-white"><i class="fas fa-caret-right"></i>(<p class="matching-vehicles"><?php echo esc_html( $matching_vehicles ); ?></p>) <span class="text-red"><?php esc_html_e( 'Vehicles', 'cardealer-helper' ); ?></span></h5>
										</div>
									</form>
								</div>
								<?php
							}
						}
					}
					?>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="filter-loader"></div>
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
function cdhl_vehicle_search_shortcode_integrateWithVC() {
	if ( function_exists( 'vc_map' ) ) {
		$default_condition  = array(
			esc_html__( 'All vehicles', 'cardealer-helper' ) => 'all_vehicles',
		);
		$taxonomy_condition  = cdhl_get_terms( array( 'taxonomy' => 'car_condition' ) );
		$car_conditions      = array_merge( $default_condition, $taxonomy_condition );
		$car_condition_label = cardealer_get_field_label_with_tax_key( 'car_condition' );

		$defult_array = array(
			array(
				'type'       => 'cd_radio_image_2',
				'heading'    => esc_html__( 'Search Style', 'cardealer-helper' ),
				'param_name' => 'search_style',
				'options'    => array(
					array(
						'value' => 'style_1',
						'title' => esc_html__( 'Style 1', 'cardealer-helper' ),
						'image' => trailingslashit( CDHL_VC_URL ) . 'vc_images/options/cd_vehicle_search/style-1.png',
					),
				),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Section Title', 'cardealer-helper' ),
				'description' => esc_html__( 'Enter search section title', 'cardealer-helper' ),
				'param_name'  => 'section_title',
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Search Vehicle ', 'cardealer-helper' ) . $car_condition_label . esc_html__( ' Tab ', 'cardealer-helper' ),
				'param_name'  => 'search_condition_tabs',
				'description' => sprintf( esc_html__( 'Select search vehicle "%1$s" in tabs. If no selected, then deafult "%2$s" will be displayed.', 'cardealer-helper' ), $car_condition_label, $car_condition_label ),
				'value'       => $car_conditions,
			),
			array(
				'type'        => 'checkbox',
				'heading'     => $car_condition_label . esc_html__( ' Tab Labels', 'cardealer-helper' ),
				'param_name'  => 'condition_tab_lables',
				'value'       => array(
					esc_html__( 'Custom labels', 'cardealer-helper' ) => 'custom',
				),
				'admin_label' => true,
				'save_always' => true,
				'description' => sprintf( esc_html__( 'Check this box to add custom vehicle "%1$s" tab labels.', 'cardealer-helper' ), $car_condition_label ),
			),
		);

		$custom_labes_fields = array();
		foreach ( $car_conditions as $cond_lbl => $cond_val ) {
			$custom_labes_fields[] = array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Custom Label For ', 'cardealer-helper' ) . $cond_lbl,
				'description' => esc_html__( 'Enter custom label for ', 'cardealer-helper' ) . $cond_lbl,
				'param_name'  => 'custom_lbl_' . $cond_val,
				'value'       => $cond_lbl,
				'dependency'  => array(
					'element' => 'condition_tab_lables',
					'value'   => array( 'custom' ),
				),
				'group'       => esc_html__( 'Custom Labels', 'cardealer-helper' ),
				'save_always' => true,
			);
		}

		$defult_array_2 = 	array(
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Filters Position', 'cardealer-helper' ),
				'param_name'  => 'filter_position',
				'value'       => array(
					esc_html__( 'Top', 'cardealer-helper' ) => 'top',
					esc_html__( 'Default', 'cardealer-helper' ) => 'default',
				),
				'save_always' => true,
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Filters Background', 'cardealer-helper' ),
				'param_name'  => 'filter_background',
				'value'       => array(
					esc_html__( 'Dark', 'cardealer-helper' )   => 'dark',
					esc_html__( 'Light', 'cardealer-helper' )  => 'light',
				),
				'save_always' => true,
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Button label', 'cardealer-helper' ),
				'description' => esc_html__( 'Enter search button label', 'cardealer-helper' ),
				'param_name'  => 'src_button_label',
				'value'       => esc_html__( 'Search Inventory', 'cardealer-helper' ),
			),
		);

		vc_map(
			array(
				'name'     => esc_html__( 'Potenza Vehicles Search', 'cardealer-helper' ),
				'base'     => 'cd_vehicles_search',
				'class'    => '',
				'category' => esc_html__( 'Potenza', 'cardealer-helper' ),
				'icon'     => cardealer_vc_shortcode_icon( 'cd_vehicles_search' ),
				'params'   => array_merge( $defult_array, $custom_labes_fields, $defult_array_2 ),
			)
		);
	}
}
add_action( 'vc_before_init', 'cdhl_vehicle_search_shortcode_integrateWithVC' );
