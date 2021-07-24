<?php // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
/**
 * CarDealer Visual Composer Car custom filter Shortcode
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package car-dealer-helper/functions
 */

add_shortcode( 'cars_custom_filters', 'cdhl_cars_custom_filters_shortcode' );

/**
 * Shortcode HTML.
 *
 * @param array $atts .
 */
function cdhl_cars_custom_filters_shortcode( $atts ) {
	extract(
		shortcode_atts(
			array(
				'cars_filters'               => '',
				'custom_filters_style'       => 'car_filter_style_1',
				'filter_style'               => 'box',
				'filter_position'            => 'top',
				'filter_background'          => 'white-bg',
				'cars_year_range_slider_cfb' => false,
				'src_button_label'           => esc_html__( 'Search Inventory', 'cardealer-helper' ),
			),
			$atts
		)
	);
	extract( $atts );
	if ( empty( $cars_filters ) ) {
		return;
	}
	$carsfilters = explode( ',', $cars_filters );
	ob_start();

	if ( $cars_year_range_slider_cfb ) {
		$key = array_search( 'car_year', $carsfilters );
		unset( $carsfilters[ $key ] );
	}

	$uid = uniqid();

	// Range Slider Step.
	global $car_dealer_options;
	$step = 100;
	if ( isset( $car_dealer_options['price_range_step'] ) && ! empty( $car_dealer_options['price_range_step'] ) ) {
		$step = $car_dealer_options['price_range_step'];
	}
	?>
	<div class="search-block clearfix <?php echo esc_attr( $filter_style ); ?> <?php echo esc_attr( $filter_position ); ?> <?php echo esc_attr( $filter_background ); ?>" data-empty-lbl="<?php esc_html_e( '--Select--', 'cardealer-helper' ); ?>">
		<?php
		if ( 'car_filter_style_2' === (string) $custom_filters_style ) {
			?>
		<div class="col-sm-12">
			<div class="row sort-filters-box">
				<?php
				if ( $cars_year_range_slider_cfb ) {

					$html  = '<div class="col-sm-6">';
					$html .= cardealer_get_year_range_filters( 'yes' );
					$html .= '</div>';
					echo $html; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
				}

				$t = 0;
				$j = 1;
				foreach ( $carsfilters as $filters ) :
					$tax_terms     = cdhl_get_terms( array( 'taxonomy' => $filters ) );
					$taxonomy_name = get_taxonomy( $filters );
					$label         = $taxonomy_name->labels->singular_name;
					?>
					<div class="col-sm-6">
						<span><?php esc_html_e( 'Select', 'cardealer-helper' ); ?> <?php echo esc_html( $label ); ?></span>
						<div class="selected-box">
							<select data-uid="<?php echo esc_attr( $uid ); ?>" id="sort_<?php echo esc_attr( $filters . '_' . $uid ); ?>" data-id="<?php echo esc_attr( $filters ); ?>" class="selectpicker custom-filters-box col-6 cd-select-box">
								<option value=""><?php esc_html_e( '--Select--', 'cardealer-helper' ); ?></option>
								<?php
								foreach ( $tax_terms as $key => $term ) :
									if ( 'car_mileage' === (string) $taxonomy_name->name ) {
										$mileage_array = cardealer_get_mileage_array();
										if ( 1 === (int) $j ) {
											foreach ( $mileage_array as $mileage ) {
												?>
												<option value="<?php echo esc_attr( $mileage ); ?>">&leq; <?php echo esc_html( $mileage ); ?></option>
												<?php
											}
										}
										$j++;
									} else {
										?>
										<option value="<?php echo esc_attr( $term ); ?>"><?php echo esc_html( $key ); ?></option>
										<?php
									}
								endforeach;
								?>
							</select>
						</div>
					</div>
					<?php
					$t++;
				endforeach;
				?>
			</div>
		</div>
		<div class="col-sm-12">
			<?php
			// Find min and max price in current result set.
			$pgs_min_price = isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : '';
			$pgs_max_price = isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : '';
			$prices        = ( function_exists( 'cardealer_get_car_filtered_price' ) ) ? cardealer_get_car_filtered_price() : '';

			$min = floor( $prices->min_price );
			$max = ceil( $prices->max_price );
			?>
			<div class="price_slider_wrapper">
				<div class="price-slide">
					<div class="price">
						<?php if ( (string) $min !== (string) $max ) { ?>
						<input type="hidden" id="pgs_min_price" name="min_price" value="<?php echo esc_attr( $pgs_min_price ); ?>" data-min="<?php echo esc_attr( $min ); ?>"/>
						<input type="hidden" id="pgs_max_price" name="max_price" value="<?php echo esc_attr( $pgs_max_price ); ?>" data-max="<?php echo esc_attr( $max ); ?>" data-step="<?php echo esc_attr( $step ); ?>"/>

						<label for="dealer-slider-amount"><?php echo esc_html__( 'Price Range', 'cardealer-helper' ); ?></label>
						<input type="text" id="dealer-slider-amount" readonly class="amount" value="" />
						<div id="slider-range"></div>
						<?php } ?>
						<a class="button cfb-submit-btn" href="javascript:void(0);"><?php echo esc_html( $src_button_label ); ?></a>
					</div>
				</div>
			</div>

		</div>

		<?php } else { ?>

		<div class="col-lg-8 col-md-8 col-sm-8">
			<div class="row sort-filters-box">
				<?php
				if ( $cars_year_range_slider_cfb ) {
					$html  = '<div class="col-lg-4 col-md-4 col-sm-4">';
					$html .= cardealer_get_year_range_filters( 'yes' );
					$html .= '</div>';
					echo $html; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
				}
				$t = 0;
				$j = 1;
				foreach ( $carsfilters as $filters ) :
					$tax_terms     = cdhl_get_terms( array( 'taxonomy' => $filters ) );
					$taxonomy_name = get_taxonomy( $filters );
					$label         = $taxonomy_name->labels->singular_name;
					?>
					<div class="col-lg-4 col-md-4 col-sm-4">
						<span><?php esc_html_e( 'Select', 'cardealer-helper' ); ?> <?php echo esc_html( $label ); ?></span>
						<div class="selected-box">
							<select data-uid="<?php echo esc_attr( $uid ); ?>" id="sort_<?php echo esc_attr( $filters . '_' . $uid ); ?>" data-id="<?php echo esc_attr( $filters ); ?>" class="selectpicker custom-filters-box col-4 cd-select-box">
								<option value=""><?php esc_html_e( '--Select--', 'cardealer-helper' ); ?></option>
								<?php
								foreach ( $tax_terms as $key => $term ) :
									if ( 'car_mileage' === (string) $taxonomy_name->name ) {
										$mileage_array = cardealer_get_mileage_array();
										if ( 1 === (int) $j ) {
											foreach ( $mileage_array as $mileage ) {
												?>
												<option value="<?php echo esc_attr( $mileage ); ?>">&leq; <?php echo esc_html( $mileage ); ?></option>
												<?php
											}
										}
										$j++;
									} else {
										?>
										<option value="<?php echo esc_attr( $term ); ?>"><?php echo esc_html( $key ); ?></option>
										<?php
									}
								endforeach;
								?>
							</select>
						</div>
					</div>
					<?php
					$t++;
				endforeach;
				?>
			</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4">
			<?php
			// Find min and max price in current result set.
			$pgs_min_price = isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : '';
			$pgs_max_price = isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : '';
			$prices        = ( function_exists( 'cardealer_get_car_filtered_price' ) ) ? cardealer_get_car_filtered_price() : '';
			$min           = floor( $prices->min_price );
			$max           = ceil( $prices->max_price );
			?>
			<div class="price_slider_wrapper">
				<div class="price-slide">
					<div class="price">
						<?php if ( $min > 0 || $max > 0 ) { ?>
						<input type="hidden" id="pgs_min_price" name="min_price" value="<?php echo esc_attr( $pgs_min_price ); ?>" data-min="<?php echo esc_attr( $min ); ?>"/>
						<input type="hidden" id="pgs_max_price" name="max_price" value="<?php echo esc_attr( $pgs_max_price ); ?>" data-max="<?php echo esc_attr( $max ); ?>" data-step="<?php echo esc_attr( $step ); ?>"/>

						<label for="dealer-slider-amount"><?php echo esc_html__( 'Price Range', 'cardealer-helper' ); ?></label>
						<input type="text" id="dealer-slider-amount" readonly class="amount" value="" />
						<div id="slider-range"></div>
						<?php } ?>
						<a class="button cfb-submit-btn" href="javascript:void(0);"><?php echo esc_html( $src_button_label ); ?></a>
					</div>
				</div>
			</div>

		</div>
		<?php } ?>
		<div class="clearfix"></div>
		<div class="filter-loader"></div>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Shortcode mapping.
 *
 * @return void
 */
function cdhl_cars_custom_filters_shortcode_integrateWithVC() {
	if ( function_exists( 'vc_map' ) ) {
		$cars_taxonomy = cdhl_get_cars_taxonomy();
		vc_map(
			array(
				'name'     => esc_html__( 'Potenza Custom Filters', 'cardealer-helper' ),
				'base'     => 'cars_custom_filters',
				'class'    => '',
				'category' => esc_html__( 'Potenza', 'cardealer-helper' ),
				'icon'     => cardealer_vc_shortcode_icon( 'cars_custom_filters' ),
				'params'   => array(
					array(
						'type'       => 'cd_radio_image',
						'heading'    => esc_html__( 'Filter Type', 'cardealer-helper' ),
						'param_name' => 'custom_filters_style',
						'options'    => cdhl_get_shortcode_param_data( 'cd_car_filters' ),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Select Filters', 'cardealer-helper' ),
						'description' => esc_html__( 'If no attributes selected, then no filters will be shown on front.', 'cardealer-helper' ),
						'param_name'  => 'cars_filters',
						'value'       => $cars_taxonomy,
						'save_always' => true,
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Year Range Slider', 'cardealer-helper' ),
						'description' => esc_html__( 'Filter with year range slider.', 'cardealer-helper' ),
						'param_name'  => 'cars_year_range_slider_cfb',
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Filters Style', 'cardealer-helper' ),
						'param_name'  => 'filter_style',
						'value'       => array(
							esc_html__( 'Box', 'cardealer-helper' ) => 'box',
							esc_html__( 'Wide', 'cardealer-helper' ) => 'wide',
						),
						'save_always' => true,
					),
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
							esc_html__( 'White', 'cardealer-helper' ) => 'white-bg',
							esc_html__( 'Red', 'cardealer-helper' ) => 'red-bg',
							esc_html__( 'Transparent', 'cardealer-helper' ) => 'transparent',
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
				),
			)
		);
	}
}
add_action( 'vc_before_init', 'cdhl_cars_custom_filters_shortcode_integrateWithVC' );
