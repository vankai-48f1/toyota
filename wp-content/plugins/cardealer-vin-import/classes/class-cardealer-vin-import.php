<?php
/**
 * Sub menu class
 *
 * @author Potenza
 *
 * @package Cardealer Vin Import
 */

if ( ! class_exists( 'Cardealer_Vin_Import' ) ) {

	/**
	 * Vin import
	 */
	class Cardealer_Vin_Import extends CDVI {

		/**
		 * Autoload method
		 *
		 * @return void
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'cdvi_admin_vin_menu' ) );
			add_action( 'admin_notices', array( $this, 'cdvi_all_vin_errors' ) );
		}

		/**
		 * Register submenu
		 *
		 * @return void
		 */
		public function cdvi_admin_vin_menu() {
			add_submenu_page( 'edit.php?post_type=cars', esc_html__( 'Vehicle VIN Import', 'cdvi-addon' ), esc_html__( 'Vehicle VIN Import', 'cdvi-addon' ), 'manage_options', 'cars-vin-import', array( &$this, 'cdvi_cars_vin_import' ) );
		}

		/**
		 * Render submenu
		 *
		 * @return void
		 */
		public function cdvi_cars_vin_import() {
			global $car_dealer_options, $cars;

			$vin = ( isset( $_GET['vin'] ) && ! empty( $_GET['vin'] ) ? sanitize_text_field( wp_unslash( $_GET['vin'] ) ) : '' );

			if ( ! empty( $vin ) ) {

				$responce_body = $this->cdvi_get_vin_data( $vin );

				if ( isset( $responce_body->errorType ) && ! empty( $responce_body->errorType ) ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

					if ( isset( $responce_body->errorType ) && ! empty( $responce_body->errorType ) ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
						?>
						<div class="notice notice-error cars-vin-error">
							<p class="cars_text_error">
								<?php echo wp_kses_post( $responce_body->message ); ?>
							</p>
							<p>
								<?php
								printf(
									wp_kses(
										/* translators: 1: Vehicle VINquery Import page link. */
										__( 'Return to <a href="%1$s">vehicle import</a> page.', 'cdvi-addon' ),
										array(
											'a' => array(
												'href' => true,
											),
										)
									),
									esc_url( admin_url( 'edit.php?post_type=cars&page=cars-vin-import' ) )
								);
								?>
							</p>
						</div>
						<?php
					}
					die;

				} else {

					$body_arr   = array();
					$body_media = array();

					if ( isset( $responce_body['body_encode'] ) && ! empty( $responce_body['body_encode'] ) ) {
						$body_data = $responce_body['body_encode'];
						$body_arr  = $this->cdvi_object_to_array_conversion( $body_data );
					}
					if ( isset( $responce_body['style_media'] ) && ! empty( $responce_body['style_media'] ) ) {
						$body_media = $responce_body['style_media'];
					}

					$body = array_merge( $body_arr, $body_media );
				}
			}
			?>
			<div class="wrap cdhl_car_vin_import">
				<h2 style="display: inline-block;"><?php esc_html_e( 'Vehicle VIN Import', 'cdvi-addon' ); ?></h2>
				<br />
				<?php
				/**
				 * When post mapped data for save
				 */
				if ( isset( $_POST['vin_import'] ) && ! empty( $_POST['vin_import'] ) ) {
					$msg = $this->cdvi_insert_vehicle_data( $body );
					echo wp_kses(
						$msg,
						array(
							'a' => array(
								'href' => true,
							),
						)
					);
				} else {
					/**
					 * Get VIN details and mapping area
					 */
					unset( $_GET['error'] );
					if ( ! empty( $vin ) && ! isset( $_GET['error'] ) ) {
						$vin_import_var = get_option( 'vin_import_mapping' );

						if ( ! empty( $vin_import_var ) ) {
							$vin_import_mapping = ( ! empty( $vin_import_var ) ? $vin_import_var : '' );
							$vin_import_mapping = $vin_import_mapping['vin_import'];
						} else {
							$vin_import_mapping = array();
						}
						?>
							<div class="cdhl-import-area-left">
								<div class="cdhl-area-title">
									<?php
									echo '<p>' . esc_html__( 'To import your vehicles simply drag and drop the right column items returned from the API into the vehicles atributtes and meta box on the right hand side tabs. We can save current mapping for further use and import data with vehicles import button.', 'cdvi-addon' ) . '</p><br>';
									?>
									<h3 class="res-msg"></h3>
									<div class="cdhl-button-group">
										<button class="cdhl_save_current_mapping current_vin_mapping button cdhl_button-primary"><?php esc_html_e( 'Save current mapping', 'cdvi-addon' ); ?></button>
										<button class="cdhl_submit_vin button button-primary" style="vertical-align: super;"><?php esc_html_e( 'Import Vehicles', 'cdvi-addon' ); ?></button>
										<span class="cdhl-loader-img"></span>
									</div>
									<div class="clr"></div>
								</div>
								<form method="post" action="" name="cars_vin_import_form" id="cars_vin_import_form">
									<?php wp_nonce_field( 'cdvi_cars_vin_import', 'cdvi_vinquery_import_nonce' ); ?>
									<div id="tabs">
										<ul>
											<li><a href="#tabs-1"><?php esc_html_e( 'Vehicle Title', 'cdvi-addon' ); ?></a></li>
											<li><a href="#tabs-2"><?php esc_html_e( 'Attributes', 'cdvi-addon' ); ?></a></li>
											<li><a href="#tabs-3"><?php esc_html_e( 'Vehicle Images', 'cdvi-addon' ); ?></a></li>
											<li><a href="#tabs-4"><?php esc_html_e( 'Regular price', 'cdvi-addon' ); ?></a></li>
											<li><a href="#tabs-5"><?php esc_html_e( 'Tax Label', 'cdvi-addon' ); ?></a></li>
											<li><a href="#tabs-6"><?php esc_html_e( 'Fuel Efficiency', 'cdvi-addon' ); ?></a></li>
											<li><a href="#tabs-7"><?php esc_html_e( 'PDF Brochure', 'cdvi-addon' ); ?></a></li>
											<li><a href="#tabs-8"><?php esc_html_e( 'Video', 'cdvi-addon' ); ?></a></li>
											<li><a href="#tabs-9"><?php esc_html_e( 'Vehicle Status', 'cdvi-addon' ); ?></a></li>
											<li><a href="#tabs-10"><?php esc_html_e( 'Vehicle Overview', 'cdvi-addon' ); ?></a></li>
											<li><a href="#tabs-11"><?php esc_html_e( 'Features & Options', 'cdvi-addon' ); ?></a></li>
											<li><a href="#tabs-12"><?php esc_html_e( 'Technical Specifications', 'cdvi-addon' ); ?></a></li>
											<li><a href="#tabs-13"><?php esc_html_e( 'General Information', 'cdvi-addon' ); ?></a></li>
											<li><a href="#tabs-14"><?php esc_html_e( 'Vehicle Location', 'cdvi-addon' ); ?></a></li>
											<li><a href="#tabs-15"><?php esc_html_e( 'Excerpt(Short content)', 'cdvi-addon' ); ?></a></li>
										</ul>
										<div class="cdhl-form-group" id="tabs-1">
											<div class="cdhl_attributes">
												<label><?php esc_html_e( 'Vehicle Titles', 'cdvi-addon' ); ?></label>
												<div class="cars_attributes">
													<ul class="cdhl_cars_attributes cdhl_form_data" data-limit="0" data-name="cars_title">
														<?php $this->cdvi_cars_vin_import_item( 'cars_title', $vin_import_mapping, $body, 0 ); ?>
													</ul>
												</div>
											</div>
										</div>
										<div class="cdhl-form-group" id="tabs-2">
											<?php
											$cars_attributes = cardealer_get_all_taxonomy_with_terms();
											foreach ( $cars_attributes as $key => $value ) {
												$attr_safe_name = $value['slug'];
												if ( 'features-options' !== $key ) {
													?>
													<div class="cdhl_attributes">
														<label><?php echo esc_html( $value['label'] ); ?></label>
														<div class="cars_attributes_area">
															<ul class="cdhl_cars_attributes cdhl_form_data" data-limit="1" data-name="<?php echo esc_attr( $attr_safe_name ); ?>">
																<?php $this->cdvi_cars_vin_import_item( $key, $vin_import_mapping, $body ); ?>
															</ul>
														</div>
													</div>
													<?php
												}
											}
											?>
										</div>
										<?php
										// extra spots.
										$extra_spots = array(
											'car_images' => array(
												esc_html__( 'Vehicle Images', 'cdvi-addon' ),
												0,
											),
											'regular_price' => array(
												esc_html__( 'Regular price', 'cdvi-addon' ),
												1,
											),
											'tax_label'  => array(
												esc_html__( 'Tax Label', 'cdvi-addon' ),
												1,
											),
											'fuel_efficiency' => array(
												esc_html__( 'Fuel Efficiency', 'cdvi-addon' ),
												1,
											),
											'pdf_file'   => array(
												esc_html__( 'PDF Brochure', 'cdvi-addon' ),
												1,
											),
											'video_link' => array(
												esc_html__( 'Video Link', 'cdvi-addon' ),
												1,
											),
											'car_status' => array(
												esc_html__( 'Vehicle Status( sold/unsold )', 'cdvi-addon' ),
												1,
											),
											'vehicle_overview' => array(
												esc_html__( 'Vehicle Overview', 'cdvi-addon' ),
												0,
											),
											'features_options' => array(
												esc_html__( 'Features Options', 'cdvi-addon' ),
												0,
											),
											'technical_specifications' => array(
												esc_html__( 'Technical Specifications', 'cdvi-addon' ),
												0,
											),
											'general_information' => array(
												esc_html__( 'General Information', 'cdvi-addon' ),
												0,
											),
											'vehicle_location' => array(
												esc_html__( 'Vehicle Location', 'cdvi-addon' ),
												1,
											),
											'excerpt'    => array(
												esc_html__( 'Excerpt(Short content)', 'cdvi-addon' ),
												1,
											),

										);
										$ef = 3;
										foreach ( $extra_spots as $key => $option ) {

											if ( 'features_options' === $key ) {
												$taxonomy_name = get_taxonomy( 'car_features_options' );
												$slug          = $taxonomy_name->rewrite['slug'];
												$label         = $taxonomy_name->labels->menu_name;
												?>
												<div id="tabs-<?php echo esc_attr( $ef ); ?>">
													<div class="cdhl_attributes">
														<label><?php echo esc_html( $label ); ?></label>
														<div class="cars_attributes_area">
															<ul class="cdhl_cars_attributes cdhl_form_data" data-limit="<?php echo esc_attr( $option[1] ); ?>" data-name="<?php echo esc_attr( $key ); ?>">
																<?php $this->cdvi_cars_vin_import_item( $key, $vin_import_mapping, $body, $option[1] ); ?>
															</ul>
														</div>
													</div>
												</div>
												<?php
											}

											if ( 'regular_price' === $key ) {
												?>
												<div id="tabs-<?php echo esc_attr( $ef ); ?>">
													<div class="cdhl_attributes">
														<label><?php esc_html_e( 'Regular Price', 'cdvi-addon' ); ?></label>
														<div class="cars_attributes_area">
															<ul class="cdhl_cars_attributes cdhl_form_data" data-limit="<?php echo esc_attr( $option[1] ); ?>" data-name="regular_price">
																<?php $this->cdvi_cars_vin_import_item( 'regular_price', $vin_import_mapping, $body, $option[1] ); ?>
															</ul>
														</div>
													</div>
													<div class="cdhl_attributes">
														<label><?php esc_html_e( 'Sale Price', 'cdvi-addon' ); ?></label>
														<div class="cars_attributes_area">
															<ul class="cdhl_cars_attributes cdhl_form_data" data-limit="<?php echo esc_attr( $option[1] ); ?>" data-name="sale_price">
																<?php $this->cdvi_cars_vin_import_item( 'sale_price', $vin_import_mapping, $body, $option[1] ); ?>
															</ul>
														</div>
													</div>
												</div>
												<?php
											}

											if ( 'fuel_efficiency' === $key ) {
												?>
												<div id="tabs-<?php echo esc_attr( $ef ); ?>">
													<div class="cdhl_attributes">
														<label><?php esc_html_e( 'City MPG', 'cdvi-addon' ); ?></label>
														<div class="cars_attributes_area">
															<ul class="cdhl_cars_attributes cdhl_form_data" data-limit="<?php echo esc_attr( $option[1] ); ?>" data-name="city_mpg">
																<?php $this->cdvi_cars_vin_import_item( 'city_mpg', $vin_import_mapping, $body, $option[1] ); ?>
															</ul>
														</div>
													</div>
													<div class="cdhl_attributes">
														<label><?php esc_html_e( 'Highway MPG', 'cdvi-addon' ); ?></label>
														<div class="cars_attributes_area">
															<ul class="cdhl_cars_attributes cdhl_form_data" data-limit="<?php echo esc_attr( $option[1] ); ?>" data-name="highway_mpg">
																<?php $this->cdvi_cars_vin_import_item( 'highway_mpg', $vin_import_mapping, $body, $option[1] ); ?>
															</ul>
														</div>
													</div>
												</div>
												<?php
											}

											if ( 'features_options' !== $key && 'regular_price' !== $key && 'fuel_efficiency' !== $key ) {
												?>
												<div id="tabs-<?php echo esc_attr( $ef ); ?>">
													<div class="cdhl_attributes">
														<label><?php echo esc_html( $option[0] ); ?></label>
														<div class="cars_attributes_area">
															<ul class="cdhl_cars_attributes cdhl_form_data" data-limit="<?php echo esc_attr( $option[1] ); ?>" data-name="<?php echo esc_attr( $key ); ?>">
																<?php $this->cdvi_cars_vin_import_item( $key, $vin_import_mapping, $body, $option[1] ); ?>
															</ul>
														</div>
													</div>
												</div>
												<?php
											}
											$ef++;
										}
										?>
									</div>
								</form>
								<div class="cdhl-area-title cdvi-footer">
									<div class="cdhl-button-group">
										<button class="cdhl_save_current_mapping current_vin_mapping button cdhl_button-primary"><?php esc_html_e( 'Save current mapping', 'cdvi-addon' ); ?></button>
										<button class="cdhl_submit_csv button button-primary" style="vertical-align: super;"><?php esc_html_e( 'Import Vehicles', 'cdvi-addon' ); ?></button>
										<span class="cdhl-loader-img"></span>
									</div>
									<div class="clr"></div>
								</div>
							</div>
							<div class="cdhl-import-area-right">
								<h3><?php esc_html_e( 'API Result', 'cdvi-addon' ); ?></h3>
								<?php

								echo '<ul id="cdhl_vin_items" class="cdhl_form_data ui-sortable">';

								if ( ! empty( $body ) ) {

									$common_type_option = array( 'make', 'model', 'engine', 'transmission', 'categories', 'MPG', 'price', 'car_images' );
									$single_type_option = array( 'drivenWheels', 'numOfDoors', 'manufacturer', 'vin', 'squishVin', 'matchingType', 'manufacturerCode' );
									$nested_type_option = array( 'options', 'colors', 'years' );

									foreach ( $body as $key => $datainfo ) {

										if ( ! empty( $datainfo ) ) {
											if ( 'car_images' === $key ) {
												echo "<h2>Vehicle images</h2>\n";
											} else {
												echo '<h2>' . esc_html( ucwords( $key ) ) . "</h2>\n";
											}

											// normal option display.
											if ( in_array( $key, $common_type_option, true ) ) {
												if ( ! empty( $datainfo ) ) {
													foreach ( $datainfo as $datainfo_key => $datainfo_value ) {

														if ( 'car_images' === $key ) {
															echo "<li class='ui-state-default'> <img src='" . esc_url( $datainfo_value ) . "' width='114' height='70'/> <input type='hidden' name='' value='" . esc_attr( $key . '|' . $datainfo_key ) . "' /></li>\n";
														} else {
															if ( is_array( $datainfo_value ) && ! empty( $datainfo_value ) ) {
																foreach ( $datainfo_value as $datainfo_deep_key => $datainfo_nested_value ) {
																	echo "<li class='ui-state-default'>" . esc_html( $datainfo_deep_key . ': ' . $datainfo_nested_value ) . " <input type='hidden' name='' value='" . esc_attr( $key . '|' . $datainfo_key . '|' . $datainfo_deep_key ) . "' /></li>\n";
																}
															} else {
																echo "<li class='ui-state-default'>" . esc_html( $datainfo_key . ': ' . $datainfo_value ) . " <input type='hidden' name='' value='" . esc_attr( $key . '|' . $datainfo_key ) . "' /></li>\n";
															}
														}
													}
												}
											} elseif ( in_array( $key, $single_type_option, true ) ) {
												echo "<li class='ui-state-default'>" . esc_html( $key . ': ' . $datainfo ) . " <input type='hidden' name='' value='" . esc_attr( $key ) . "' /></li>\n";

											} elseif ( in_array( $key, $nested_type_option, true ) ) {
												if ( ! empty( $datainfo ) ) {
													foreach ( $datainfo[0] as $datainfo_key => $datainfo_value ) {

														if ( ! is_array( $datainfo_value ) ) {
															echo "<li class='ui-state-default'>" . esc_html( $datainfo_key . ': ' . $datainfo_value ) . " <input type='hidden' name='' value='" . esc_attr( $key . '|0|' . $datainfo_key ) . "' /></li>\n";

														} else {
															$i = 0;
															foreach ( $datainfo_value as $deep_key => $nested_value ) {
																if ( 'options' === $key || 'colors' === $key ) {

																	if ( ! empty( $nested_value ) ) {
																		echo '<h5>' . esc_html( ucwords( $key ) ) . ' ' . esc_html__( 'option set', 'cdvi-addon' ) . ' ' . esc_html( $i ) . "</h5>\n";

																		if ( ! empty( $nested_value ) ) {
																			foreach ( $nested_value as $deepest_key => $deepest_value ) {
																				echo "<li class='ui-state-default'>" . esc_html( $deepest_key . ': ' . $deepest_value ) . " <input type='hidden' name='' value='" . esc_attr( $key . '|' . $deep_key . '|' . $datainfo_key . '|' . $i . '|' . $deepest_key ) . "' /></li>\n";
																			}
																		}

																		echo '<hr>';
																	}
																} elseif ( 'years' === $key ) {

																	$carsubmodels = $nested_value['submodel'];
																	unset( $nested_value['submodel'] );

																	$nested_value['submodel'] = $carsubmodels;

																	echo '<h5>' . esc_html( ucwords( $key ) ) . ' ' . esc_html__( 'submodel', 'cdvi-addon' ) . ' ' . esc_html( $i ) . "</h5>\n";

																	if ( ! empty( $nested_value ) ) {
																		foreach ( $nested_value as $deeper_key => $deeper_value ) {
																			echo ( ! is_array( $deeper_value ) && ! is_object( $deeper_value ) ? "<li class='ui-state-default'>" . esc_html( $deeper_key . ': ' . $deeper_value ) . " <input type='hidden' name='' value='" . esc_attr( $key . '|' . $datainfo_key . '|' . $i . '|' . $deeper_key ) . "' /></li>\n" : '' );
																		}
																	}

																	if ( ! empty( $nested_value['submodel'] ) ) {
																		foreach ( $nested_value['submodel'] as $submodel_key => $submodel_value ) {
																			echo "<li class='ui-state-default'>" . esc_html( $submodel_key . ': ' . $submodel_value ) . " <input type='hidden' name='' value='" . esc_attr( $key . '|' . $datainfo_key . '|' . $i . '|submodel|' . $submodel_key ) . "' /></li>\n";
																		}
																	}

																	if ( isset( $nested_value['equipment'] ) && ! empty( $nested_value['equipment'] ) ) {
																		echo '<h2>' . esc_html__( 'Equipment Options', 'cdvi-addon' ) . '</h2>';
																		echo "<div class='accordion accordion_parent'>";
																		echo '<h3>' . esc_html__( 'Equipment Options', 'cdvi-addon' ) . '</h3>';

																		echo "<div class='accordion accordion_child'>";
																		foreach ( $nested_value['equipment'] as $carequipment_key => $carequipment_option_value ) {

																			if ( ! empty( $carequipment_option_value['attributes'] ) ) {
																				echo '<h3>' . esc_html( $carequipment_option_value['name'] ) . '</h3>';

																				echo '<div>';
																				foreach ( $carequipment_option_value['attributes'] as $carequipment_value_id => $carequipment_values ) {
																					echo '<li class="ui-state-default">name: ' . esc_html( $carequipment_values['name'] ) . ' <input type="hidden" name="" value="' . esc_attr( $key . '|' . $i . '|' . $datainfo_key . '|' . $i . '|equipment|' . $carequipment_key . '|attributes|' . $carequipment_value_id . '|name' ) . '" /></li>' . "\n";
																					echo '<li class="ui-state-default">value: ' . esc_html( $carequipment_values['value'] ) . ' <input type="hidden" name="" value="' . esc_attr( $key . '|' . $i . '|' . $datainfo_key . '|' . $i . '|equipment|' . $carequipment_key . '|attributes|' . $carequipment_value_id . '|value' ) . '" /></li>' . "\n";
																				}
																				echo '</div>';
																			}
																		}
																		echo '</div>';
																		echo '</div>';
																	}

																	echo "<hr>\n";
																}

																$i++;
															}
														}
													}
												}
											}
										}
									}
								}
								echo '</ul>';
								?>
							</div>
						<?php
					} else {

						/**
						 * Pass VIN number here ( Enter vin number here )
						 */
						$cars_api_key    = ( isset( $car_dealer_options['edmunds_api_key'] ) && ! empty( $car_dealer_options['edmunds_api_key'] ) ? $car_dealer_options['edmunds_api_key'] : '' );
						$cars_api_secret = ( isset( $car_dealer_options['edmunds_api_secret'] ) && ! empty( $car_dealer_options['edmunds_api_secret'] ) ? $car_dealer_options['edmunds_api_secret'] : '' );

						if ( ! empty( $cars_api_key ) && ! empty( $cars_api_secret ) ) {
							?>
							<div class="upload-plugin-pgs">
								<form method="GET" class="wp-upload-form" action="" name="import_url">
									<input type="hidden" name="post_type" value="cars">
									<input type="hidden" name="page" value="cars-vin-import">
									<label class="screen-reader-text" for="pluginzip"><?php esc_html_e( 'Vehicle file', 'cdvi-addon' ); ?></label>
									<input type="text" name="vin" placeholder="<?php esc_html_e( 'VIN #', 'cdvi-addon' ); ?>" style="width: 60%;">
									<button onclick="jQuery(this).closest('form').submit()" class="button"><?php esc_html_e( 'Get vehicle details', 'cdvi-addon' ); ?></button>
								</form>
							</div>
							<?php
						} else {
							?>
							<a href="<?php echo esc_url( admin_url( 'admin.php?page=cardealer' ) ); ?>"><?php esc_html_e( 'Please set both your edmunds API keys in the API Keys panel.', 'cdvi-addon' ); ?></a>
							<?php
						}
					}
				}
				?>
			</div>
			<?php
		}
	}
}
new Cardealer_Vin_Import();
