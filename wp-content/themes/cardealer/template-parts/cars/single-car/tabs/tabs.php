<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

global $car_dealer_options;

$location_exist = false;
$lat            = '';
$lan            = '';

if ( isset( $car_dealer_options['default_value_lat'] ) && isset( $car_dealer_options['default_value_long'] ) && ! empty( $car_dealer_options['default_value_lat'] ) && ! empty( $car_dealer_options['default_value_long'] ) ) {
	$location_exist = true;
	$lat            = $car_dealer_options['default_value_lat'];
	$lan            = $car_dealer_options['default_value_long'];
}

$car_id   = get_the_ID();
$location = get_post_meta( $car_id, 'vehicle_location', true );

if ( ! empty( $location ) ) {
	$location_exist = true;
}


$vehicle_overview_display         = ( isset( $car_dealer_options['cars-vehicle-overview-option'] ) ) ? $car_dealer_options['cars-vehicle-overview-option'] : 1;
$car_features_options_display     = ( isset( $car_dealer_options['cars-features-options-option'] ) ) ? $car_dealer_options['cars-features-options-option'] : 1;
$technical_specifications_display = ( isset( $car_dealer_options['cars-technical-specifications-option'] ) ) ? $car_dealer_options['cars-technical-specifications-option'] : 1;
$general_information_display      = ( isset( $car_dealer_options['cars-general-information-option'] ) ) ? $car_dealer_options['cars-general-information-option'] : 1;
$location_display                 = ( isset( $car_dealer_options['cars-vehicle-location-option'] ) ) ? $car_dealer_options['cars-vehicle-location-option'] : 1;

if ( $vehicle_overview_display || $car_features_options_display || $technical_specifications_display || $general_information_display || $location_display ) {
	?>
	<div id="tabs">
		<ul class="tabs">
			<?php
			if ( $vehicle_overview_display ) {
				$vehicle_overview = get_post_meta( $car_id, 'vehicle_overview', true );
				if ( ! empty( $vehicle_overview ) ) {
					?>
					<li data-tabs="tab1" class="active"><i aria-hidden="true" class="fas fa-sliders-h"></i>&nbsp;<?php esc_html_e( 'Vehicle Overview', 'cardealer' ); ?></li>
					<?php
				}
			}

			if ( $car_features_options_display ) {
				$car_features_options = wp_get_post_terms( get_the_ID(), 'car_features_options' );
				if ( ! empty( $car_features_options ) ) {
					$features_options_name = cardealer_get_field_label_with_tax_key( 'car_features_options', 'plural' );
					?>
					<li data-tabs="tab2"><i aria-hidden="true" class="fas fa-list"></i>&nbsp;<?php echo esc_html( $features_options_name ); ?></li>
					<?php
				}
			}

			if ( $technical_specifications_display ) {
				$technical_specifications = get_post_meta( $car_id, 'technical_specifications', true );
				if ( ! empty( $technical_specifications ) ) {
					?>
					<li data-tabs="tab3"><i aria-hidden="true" class="fas fa-list"></i>&nbsp;<?php esc_html_e( 'Technical Specifications', 'cardealer' ); ?></li>
					<?php
				}
			}

			if ( $general_information_display ) {
				$general_information = get_post_meta( $car_id, 'general_information', true );
				if ( ! empty( $general_information ) ) {
					?>
					<li data-tabs="tab4"><i aria-hidden="true" class="fas fa-info-circle"></i>&nbsp;<?php esc_html_e( 'General Information', 'cardealer' ); ?></li>
					<?php
				}
			}

			if ( $location_display ) {
				if ( $location_exist ) {
					?>
					<li class="cd-tab-map" data-tabs="tab5"><i class="fas fa-map-marker-alt" aria-hidden="true"></i>&nbsp;<?php esc_html_e( 'Vehicle Location', 'cardealer' ); ?></li>
					<?php
				}
			}
			?>
		</ul>

		<?php
		if ( $vehicle_overview_display ) {
			?>
			<div id="tab1" class="tabcontent">
				<?php echo do_shortcode( wpautop( get_post_meta( $car_id, 'vehicle_overview', true ) ) ); ?>
			</div>
			<?php
		}

		if ( $car_features_options_display ) {
			?>
			<div id="tab2" class="tabcontent cd-detail-page">
				<div class="masonry-main">
					<?php
					$child_features_and_options = wp_get_post_terms( get_the_ID(), 'car_features_options' );
					$features_and_options       = $child_features_and_options;

					// check all are parent features.
					$parent_found = 0;

					foreach ( $features_and_options as $feature ) {
						if ( 0 !== $feature->parent ) {
							$parent_found = 1;
							break;
						}
					}

					if ( 1 === $parent_found ) {
						?>
						<div class="tab-isotope-2 masonry">
							<div class="grid-sizer"></div>
							<?php
							$all_featuer_array = array();
							$display_array     = array();

							foreach ( $features_and_options as $feature ) {
								if ( 0 === $feature->parent ) {
									$term_id = $feature->term_id;
									$head    = 0;
									foreach ( $child_features_and_options as $key => $features ) {
										// Parent feature and child feature.
										if ( 0 !== $features->parent && $term_id === $features->parent ) {
											if ( 0 === $head ) {
												echo "<div class='col-sm-4 masonry-item'>"; // start div.
													echo '<h4>' . esc_html( $feature->name ) . '</h4>';// heading.
													$head            = 1;
													$display_array[] = $feature->term_id;
													echo "<ul class='list-style-1'>";
											}
											?>
											<li><i class='fas fa-check'></i><?php echo esc_html( $features->name ); ?></li>
											<?php
											$display_array[] = $features->term_id;
										}
										if ( ( count( $child_features_and_options ) - 1 ) === $key && 0 !== $head ) {
											echo '</ul>';
											echo '</div>'; // close div.
										}
									}
								}
								$all_featuer_array[] = $feature->term_id;
							}

							// rest of features.
							$class_parent       = '';
							$remaining_features = array_diff( $all_featuer_array, $display_array );

							if ( count( $child_features_and_options ) !== count( $remaining_features ) ) {
								$class_parent = 'masonry-item';
							}

							if ( ! empty( $remaining_features ) ) {
								foreach ( $remaining_features as $r_feature ) {
									?>
									<div class="col-sm-4 <?php echo esc_attr( $class_parent ); ?>">
										<ul class='list-style-1'>
											<?php
											$feat_terms = get_term_by( 'id', $r_feature, 'car_features_options' );
											if ( ! empty( $feat_terms ) && isset( $feat_terms->name ) ) {
												?>
												<li><i class='fas fa-check'></i><?php echo esc_html( $feat_terms->name ); ?></li>
												<?php
											}
											?>
										</ul>
									</div>
									<?php
								}
							}
							?>
						</div>
						<?php
					} else { // if all child features, no parents / all are at same level.
						?>
						<ul class="list-style-1 list-col-3">
							<?php
							foreach ( $features_and_options as $feature ) {
								?>
								<li><i class='fas fa-check'></i><?php echo esc_html( $feature->name ); ?></li>
								<?php
							}
							?>
						</ul>
						<?php
					}
					?>
				</div>
			</div>
			<?php
		}

		if ( $technical_specifications_display ) {
			?>
			<div id="tab3" class="tabcontent">
				<?php echo do_shortcode( wpautop( get_post_meta( $car_id, 'technical_specifications', true ) ) ); ?>
			</div>
			<?php
		}

		if ( $general_information_display ) {
			?>
			<div id="tab4" class="tabcontent">
				<?php echo do_shortcode( wpautop( get_post_meta( $car_id, 'general_information', true ) ) ); ?>
			</div>
			<?php
		}

		if ( $location_display ) {
			if ( $location_exist ) {
				if ( ! empty( $location['address'] ) ) {
					$lat = $location['lat'];
					$lan = $location['lng'];
					if ( empty( $lat ) && ! empty( $lan ) ) {

						$get_lat_long = cardealer_getLatLnt( $location['address'] );
						$latitude     = ! empty( $get_lat_long['lat'] ) ? $get_lat_long['lat'] : '';
						$longitude    = ! empty( $get_lat_long['lng'] ) ? $get_lat_long['lng'] : '';

						// map location.
						if ( ! empty( $latitude ) && ! empty( $longitude ) ) {
							$new_location = array(
								'address' => $location['address'],
								'lat'     => $latitude,
								'lng'     => $longitude,
								'zoom'    => '10',
							);
							if ( '1' === $get_lat_long['addr_found'] ) {
								update_field( 'vehicle_location', $new_location, $car_id );
								$location = $new_location;
								$lat      = $location['lat'];
								$lan      = $location['lng'];
							}
						}
					}
				}
				?>
				<div id="tab5" class="tabcontent">
					<div class="acf-map">
						<div class="marker" data-lat="<?php echo esc_attr( $lat ); ?>" data-lng="<?php echo esc_attr( $lan ); ?>"></div>
					</div>
				</div>
				<?php
			}
		}
		?>
	</div>
	<?php
}
