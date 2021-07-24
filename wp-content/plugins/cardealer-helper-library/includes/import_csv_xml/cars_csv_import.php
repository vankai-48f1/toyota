<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Cars csv import
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

if ( ! function_exists( 'cdhl_car_import_csv_xml_session' ) ) {
	/**
	 * Import csv xml session
	 */
	function cdhl_car_import_csv_xml_session() {
		$ver = 0;
		$ver = phpversion();
		if ( $ver >= '5.4.0' ) {
			if ( ! headers_sent() && ! session_id() && cdhl_is_vehicle_import_page() ) {
				session_start();
			}
		} else {
			if ( session_id() === '' && cdhl_is_vehicle_import_page() ) {
				session_start();
			}
		}
	}
	add_action( 'init', 'cdhl_car_import_csv_xml_session' );
}

if ( ! function_exists( 'cdhl_admin_csv_import_menu' ) ) {
	/**
	 * Register admin sidebar nav menu
	 */
	function cdhl_admin_csv_import_menu() {
		add_submenu_page( 'edit.php?post_type=cars', esc_html__( 'Car Dealer Vehicle Import', 'cardealer-helper' ), esc_html__( 'Vehicle CSV Import', 'cardealer-helper' ), 'manage_options', 'cars-import', 'cdhl_cars_import' );
	}
	add_action( 'admin_menu', 'cdhl_admin_csv_import_menu' );
}

if ( ! function_exists( 'cdhl_admin_cars_imports_errors' ) ) {
	/**
	 * Catch and display errors
	 */
	function cdhl_admin_cars_imports_errors() {
		if ( cdhl_is_vehicle_import_page() ) {
			$message = '';

			$error = ( isset( $_GET['error'] ) && ! empty( $_GET['error'] ) ? sanitize_text_field( $_GET['error'] ) : '' );

			if ( ! empty( $error ) || ( isset( $_SESSION['cars_csv']['error'] ) && ! empty( $_SESSION['cars_csv']['error'] ) ) ) {
				if ( isset( $_SESSION['cars_csv']['error'] ) && ! empty( $_SESSION['cars_csv']['error'] ) ) {
					$cars_csv_erros = array();

					if ( is_array( $_SESSION['cars_csv']['error'] ) ) {
						foreach ( $_SESSION['cars_csv']['error'] as $key => $message ) {
							$cars_csv_erros[] = $message['msg'];
						}
						$cars_csv_erros = array_filter( array_map( 'trim', $cars_csv_erros ) );

						if ( ! empty( $cars_csv_erros ) ) {
							$cars_csv_erros_str = join( '<br>', $cars_csv_erros );
							$message = $cars_csv_erros_str;
						}
					} else {
						$message = $_SESSION['cars_csv']['error'];
					}

					unset( $_SESSION['cars_csv']['error'] );

				} elseif ( 'invalid-file-format' === (string) $error ) {
					$message = esc_html__( 'The uploaded file is not a valid file format. Please upload a CSV file.', 'cardealer-helper' );
				} elseif ( 'file' === (string) $error ) {
					$message = esc_html__( 'The file uploaded is not a valid CSV file, please try again.', 'cardealer-helper' );
				} elseif ( 'url' === (string) $error ) {
					$message = esc_html__( 'The URL submitted is not a valid URL, please try again.', 'cardealer-helper' );
				} elseif ( 'int_file' === (string) $error ) {
					$message = esc_html__( 'The file must not contain numbers as the column title.', 'cardealer-helper' );
				}
			}

			if ( '' !== $message ) {
				printf(
					'<div class="%1$s"><p>%2$s</p></div>',
					'notice notice-error is-dismissible',
					wp_kses( $message, cardealer_allowed_html( 'div,strong,p,a,br' ) )
				);
			}
		}
	}
	add_action( 'admin_notices', 'cdhl_admin_cars_imports_errors' );
}

if ( ! function_exists( 'cdhl_cars_import' ) ) {
	/**
	 * Cars import
	 */
	function cdhl_cars_import() {
		if ( ! headers_sent() && ! session_id() && cdhl_is_vehicle_import_page() ) {
			session_start();
		}

		// import functions.
		include_once trailingslashit( CDHL_PATH ) . 'includes/import_csv_xml/cars_import_functions.php';
		?>
		<div class="wrap cdhl_dealer-cars-import">
			<h2 style="display: inline-block;"><?php esc_html_e( 'Vehicles CSV Import', 'cardealer-helper' ); ?></h2>
			<?php
			if ( isset( $_SESSION['cars_csv']['import_results'] ) && ! empty( $_SESSION['cars_csv']['import_results'] ) ) {
				echo '<br />';
				$return_arr = array();

				cdhl_wp_all_import_alert();

				// Records processed.
				$processed_records = 0;
				if ( isset( $_SESSION['records_processed'] ) ) {
					$total_records        = $_SESSION['cdhl_total_csv_rows'];
					$processed_records    = $_SESSION['records_processed'];
					$not_imported         = $total_records - $processed_records;
					$records_not_imported = ( $not_imported > 0 ) ? $not_imported : 0;
					printf(
						wp_kses(
							/* translators: %s: string */
							__( '<br><b><u>Import Summary: </u></b><br><p>Total Records: <b>%1$d</b><br> Records processed: <b>%2$d</b><br> Records not imported: <b>%3$d</b></p>', 'cardealer-helper' ),
							array(
								'b'  => array(),
								'p'  => array(),
								'u'  => array(),
								'br' => array(),
							)
						),
						$total_records,
						$processed_records,
						$records_not_imported
					); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
				}
				if ( $processed_records > 0 ) {
					echo wp_kses(
						__( '<p> See detailed import results as below: </p>', 'cardealer-helper' ),
						array(
							'p' => array(),
						)
					);
				}

				echo $_SESSION['cars_csv']['import_results']['result']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
				$return_arr = $_SESSION['cars_csv']['import_results']['return_arr'];
				if ( ! empty( $return_arr ) ) {
					echo '<br />';
					foreach ( $return_arr as $res ) {
						echo $res . '<br />'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
					}
				}
				if ( isset( $_SESSION['cdhl_server_error'] ) ) {
					?>
						<p class="cdhl_server_error">
							<strong style="color:red">
						<?php esc_html_e( 'Import process has been stopped due to GATEWAY TIMEOUT error of your server. Please try again after sometime!', 'cardealer-helper' ); ?>
							</strong>
							<div class='clear'></div>
							<a href='<?php esc_attr_e( 'http://www.wpallimport.com/', 'cardealer-helper' ); ?>' target="_blank">
							<?php esc_html_e( 'Use WP All Import Pro to import this feed.', 'cardealer-helper' ); ?>
							</a>
						</p>
						<p class="cdhl_install-guide">
						<?php cdhl_import_troubleshooting(); ?>
						</p>
						<?php
				} else {
					?>
						<p>
						<a href='<?php echo esc_url( admin_url( 'edit.php?post_type=cars&page=cars-import' ) ); ?>'>
							<button class='button button-primary'>
							<?php esc_html_e( 'Go to import more vehicles', 'cardealer-helper' ); ?>
							</button>
						</a>
						</p>
					<?php
				}
				unset( $_SESSION['cars_csv'] );
			} else {
				if ( ! isset( $_GET['import_file_content'] ) ) {
					/**
					 * CSV File Import form
					 */
					if ( isset( $_SESSION['cdhl_server_error'] ) ) {
						unset( $_SESSION['cdhl_server_error'] );
					}
					cdhl_wp_all_import_alert();
					?>
					<p><?php esc_html_e( 'Please select a CSV file and click Import Now button to proceed with the import process.', 'cardealer-helper' ); ?></p>
					<div class="cdhl_upload-cars-file">
						<form method="post" enctype="multipart/form-data" class="cdhl-cars-upload-form" action="<?php echo remove_query_arg( 'error' ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>" name="cars_import_upload">
							<input type="hidden" name="post_type" value="cars">
							<input type="hidden" name="page" value="cars-import">
							<input type="hidden" name="file" value="is_uploaded">
							<input type="file" id="cars_import_upload" name="cars_import_upload">
							<input type="submit" name="cars_import_submit" id="cars-install-plugin-submit" class="cdhl_button button button-primary" value="<?php esc_html_e( 'Import Now', 'cardealer-helper' ); ?>" disabled />
						</form>
						<h4 class="cdhl-vehicle-import-notes-title"><?php esc_html_e( 'Notes:', 'cardealer-helper' ); ?></h4>
						<ul class="cdhl-vehicle-import-notes">
							<li><?php echo wp_kses( __( 'The "Vehicles CSV Import" tool is designed to import a small feed (feed with limited records and images). If you have a large feed then we recommend using the <strong>WP All Import Pro</strong> plugin.', 'cardealer-helper' ), array( 'strong' => array() ) ); ?></li>
							<li><?php esc_html_e( 'Also, make sure the CSV file contains the VIN; otherwise, it will not import vehicles.', 'cardealer-helper' ); ?></li>
						</ul>
						<div class="cdhl_sample_csv">
							<p>
								<?php
								echo sprintf(
									wp_kses(
										/* translators: %1$s: url */
										__( 'Click <a href="%1$s" download>here</a> to download the sample CSV file.', 'cardealer-helper' ),
										array(
											'a' => array(
												'href'     => array(),
												'download' => array(),
											),
										)
									),
									esc_url( trailingslashit( CDHL_URL ) . 'includes/import_csv_xml/sample/cardealer-sample-inventory.csv' )
								)
								?>
							</p>
						</div>
						<div class="cdhl_install-guide">
						<?php cdhl_import_troubleshooting(); ?>
						</div>
					</div>
					<?php
				}
			}
			if ( isset( $_GET['import_file_content'] ) ) {
				$cars_attributes = cardealer_get_all_taxonomy_with_terms();

				// Sort VIN to first position.
				$cars_attributes_vin_number = $cars_attributes['vin-number'];
				unset( $cars_attributes['vin-number'] );
				$cars_attributes = array_merge( array( 'vin-number' => $cars_attributes_vin_number ), $cars_attributes );

				// Post metas.
				$custom_meta_fields = array(
					'car_images'               => array( esc_html__( 'Vehicle Images', 'cardealer-helper' ), 0 ),
					'regular_price'            => array( esc_html__( 'Regular price', 'cardealer-helper' ), 1 ),
					'tax_label'                => array( esc_html__( 'Tax Label', 'cardealer-helper' ), 1 ),
					'fuel_efficiency'          => array( esc_html__( 'Fuel Efficiency', 'cardealer-helper' ), 1 ),
					'pdf_file'                 => array( esc_html__( 'PDF Brochure', 'cardealer-helper' ), 1 ),
					'video_link'               => array( esc_html__( 'Video Link', 'cardealer-helper' ), 1 ),
					'car_status'               => array( esc_html__( 'Vehicle Status( sold/unsold )', 'cardealer-helper' ), 1 ),
					'vehicle_overview'         => array( esc_html__( 'Vehicle Overview', 'cardealer-helper' ), 0 ),
					'features_options'         => array( esc_html__( 'Features Options', 'cardealer-helper' ), 0 ),
					'technical_specifications' => array( esc_html__( 'Technical Specifications', 'cardealer-helper' ), 0 ),
					'general_information'      => array( esc_html__( 'General Information', 'cardealer-helper' ), 0 ),
					'vehicle_location'         => array( esc_html__( 'Vehicle Location', 'cardealer-helper' ), 1 ),
					'excerpt'                  => array( esc_html__( 'Excerpt(Short content)', 'cardealer-helper' ), 1 ),
				);
				$prev_assoc_data    = get_option( 'cdhl_csv_mapping_associations' );
				$association_data   = ( $prev_assoc_data ? $prev_assoc_data : array() );

				$new_vin                 = ( isset( $association_data['new_vin'] ) && ! empty( $association_data['new_vin'] ) ? $association_data['new_vin'] : '' );
				$new_vin_imp_post_status = ( isset( $association_data['new_vin_imp_post_status'] ) && ! empty( $association_data['new_vin_imp_post_status'] ) ? $association_data['new_vin_imp_post_status'] : '' );

				$vin_not_in_csv       = ( isset( $association_data['vin_not_in_csv'] ) && ! empty( $association_data['vin_not_in_csv'] ) ? $association_data['vin_not_in_csv'] : '' );
				$vin_not_in_csv_in_db = ( isset( $association_data['vin_not_in_csv_in_db'] ) && ! empty( $association_data['vin_not_in_csv_in_db'] ) ? $association_data['vin_not_in_csv_in_db'] : '' );

				$duplicate_check_vin           = ( isset( $association_data['duplicate_check_vin'] ) && ! empty( $association_data['duplicate_check_vin'] ) ? $association_data['duplicate_check_vin'] : '' );
				$overwrite_existing            = ( isset( $association_data['overwrite_existing'] ) && ! empty( $association_data['overwrite_existing'] ) ? $association_data['overwrite_existing'] : '' );
				$overwrite_existing_car_price  = ( isset( $association_data['overwrite_existing_car_price'] ) && ! empty( $association_data['overwrite_existing_car_price'] ) ? $association_data['overwrite_existing_car_price'] : '' );
				$overwrite_existing_car_images = ( isset( $association_data['overwrite_existing_car_images'] ) && ! empty( $association_data['overwrite_existing_car_images'] ) ? $association_data['overwrite_existing_car_images'] : '' );

				if ( empty( $new_vin ) && empty( $vin_not_in_csv ) && empty( $duplicate_check_vin ) ) {
					$new_vin = 'on';
				}
				?>

					<form method="post" id="cdhl_csv_import" data-one-per="<?php esc_html_e( 'Only one per list!', 'cardealer-helper' ); ?>" data-nonce="<?php echo wp_create_nonce( 'cardealer_csv_import' ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>">
						<p class="cdhl_install-guide">
					<?php cdhl_wp_all_import_alert(); ?>
						</p>
						<h3><?php esc_html_e( 'Vehicles import rules', 'cardealer-helper' ); ?></h3>
						<div class="cdhl-form-group">
							<label><input type="checkbox" id="cdhl_new_vin" name="new_vin" value="on" <?php echo ( isset( $new_vin ) && ! empty( $new_vin ) ? "checked='checked'" : '' ); ?> />
						<?php esc_html_e( 'Vehicle exist in CSV not in Database', 'cardealer-helper' ); ?>:</label>
							<div class="cdhl_new_vin cdhl-form-group" style="display: <?php echo ( ( isset( $new_vin ) && 'on' === $new_vin ) ? 'block' : 'none' ); ?>;">
								<select name="new_vin_imp_post_status">
									<option <?php echo ( 'publish' === $new_vin_imp_post_status ) ? "selected='selected'" : ''; ?> value="publish"><?php esc_html_e( 'Publish', 'cardealer-helper' ); ?></option>
									<option <?php echo ( 'draft' === $new_vin_imp_post_status ) ? "selected='selected'" : ''; ?> value="draft"><?php esc_html_e( 'Draft', 'cardealer-helper' ); ?></option>
									<option <?php echo ( 'pending' === $new_vin_imp_post_status ) ? "selected='selected'" : ''; ?> value="pending"><?php esc_html_e( 'Pending', 'cardealer-helper' ); ?></option>
									<option <?php echo ( 'private' === $new_vin_imp_post_status ) ? "selected='selected'" : ''; ?> value="private"><?php esc_html_e( 'Private', 'cardealer-helper' ); ?></option>
								</select>
							</div>
						</div>
						<div class="cdhl-form-group">
						<label><input type="checkbox" id="cdhl_vin_not_in_csv" name="vin_not_in_csv" value="on" <?php echo ( isset( $vin_not_in_csv ) && ! empty( $vin_not_in_csv ) ? "checked='checked'" : '' ); ?> />
					<?php esc_html_e( 'Vehicle exist in Database and not in CSV', 'cardealer-helper' ); ?>:</label>
						<div class="cdhl_vin_not_in_csv cdhl-form-group" style="display: <?php echo ( ( isset( $vin_not_in_csv ) && 'on' === $vin_not_in_csv ) ? 'block' : 'none' ); ?>;">
								<select name="vin_not_in_csv_in_db">
									<option <?php echo ( empty( $vin_not_in_csv_in_db ) ) ? "selected='selected'" : ''; ?> value=""><?php esc_html_e( '--Select--', 'cardealer-helper' ); ?></option>
									<option <?php echo ( 'sold' === $vin_not_in_csv_in_db ) ? "selected='selected'" : ''; ?> value="sold"><?php esc_html_e( 'Sold', 'cardealer-helper' ); ?></option>
									<option <?php echo ( 'delete' === $vin_not_in_csv_in_db ) ? "selected='selected'" : ''; ?> value="delete"><?php esc_html_e( 'Delete', 'cardealer-helper' ); ?></option>
									<option <?php echo ( 'unpublished' === $vin_not_in_csv_in_db ) ? "selected='selected'" : ''; ?> value="unpublished"><?php esc_html_e( 'Unpublished', 'cardealer-helper' ); ?></option>
								</select>
							</div>
						</div>
						<div class="cdhl-form-group cdhl-duplicate-check-vin">
							<label><input type="checkbox" id="cdhl_duplicate_check_vin" name="duplicate_check_vin" value="vin-number" <?php echo ( ! empty( $duplicate_check_vin ) ? "checked='checked'" : '' ); ?> />
						<?php esc_html_e( 'Vehicle exist in both CSV and Database', 'cardealer-helper' ); ?>: <strong><?php esc_html_e( 'VIN Number', 'cardealer-helper' ); ?></strong></label>
								<div class="cdhl_overwrite_existing_car_images cdhl-form-group" style="display: <?php echo ( ( isset( $duplicate_check_vin ) && 'vin-number' === $duplicate_check_vin ) ? 'block' : 'none' ); ?>;">
									<label><input type="checkbox" name="overwrite_existing" value="on" <?php echo ( ! empty( $overwrite_existing ) ? "checked='checked'" : '' ); ?>/>
								<?php esc_html_e( 'Overwrite all duplicate vehicles with new data(without images and price)', 'cardealer-helper' ); ?></label>
								</div>

								<div class="cdhl_overwrite_existing_car_images" style="display: <?php echo ( ( isset( $duplicate_check_vin ) && 'vin-number' === $duplicate_check_vin ) ? 'block' : 'none' ); ?>;">
									<label><input type="checkbox" name="overwrite_existing_car_price" value="on" <?php echo ( ( isset( $overwrite_existing_car_price ) && 'on' === $overwrite_existing_car_price ) ? "checked='checked'" : '' ); ?>/>
								<?php esc_html_e( 'Overwrite price on existing vehicles', 'cardealer-helper' ); ?></label>
								</div>

								<div class="cdhl_overwrite_existing_car_images" style="display: <?php echo ( ( isset( $duplicate_check_vin ) && 'vin-number' === $duplicate_check_vin ) ? 'block' : 'none' ); ?>;">
									<label><input type="checkbox" name="overwrite_existing_car_images" value="on" <?php echo ( ( isset( $overwrite_existing_car_images ) && 'on' === $overwrite_existing_car_images ) ? "checked='checked'" : '' ); ?>/> <?php esc_html_e( 'Overwrite images on existing vehicles', 'cardealer-helper' ); ?></label>
								</div>
								<div class="cdhl-form-group res-msg"></div>
						</div>
						<div class="cdhl-csv-import cdhl-csv-import-vin-select-notice">
							<div class="cdhl-notice cdhl-notice-warning cdhl-notice-icon cdhl-notice-icon-info">
								<p><?php esc_html_e( 'VIN Number must be mapped in the Attributes tab. Otherwise, the import process will not import the vehicles.', 'cardealer-helper' ); ?></p>
							</div>
						</div>
						<div class="cdhl-import-area-left">
							<div class="cdhl-area-title">
								<h3><?php esc_html_e( 'Vehicles import area', 'cardealer-helper' ); ?></h3>
								<div class="cdhl-button-group">
									<button class="cdhl_save_current_mapping button cdhl_button-primary"><?php esc_html_e( 'Save current mapping', 'cardealer-helper' ); ?></button>
									<button class="cdhl_submit_csv button button-primary" style="vertical-align: super;"><?php esc_html_e( 'Import Vehicles', 'cardealer-helper' ); ?></button>
									<span class="cdhl-loader-img"></span>
								</div>
								<div class="clr"></div>
							</div>
							<div id="tabs">
								<ul>
									<li><a href="#tabs-1"><?php esc_html_e( 'Vehicle Title', 'cardealer-helper' ); ?></a></li>
									<li><a href="#tabs-2"><?php esc_html_e( 'Attributes', 'cardealer-helper' ); ?></a></li>
									<li><a href="#tabs-3"><?php esc_html_e( 'Vehicle Images', 'cardealer-helper' ); ?></a></li>
									<li><a href="#tabs-4"><?php esc_html_e( 'Regular price', 'cardealer-helper' ); ?></a></li>
									<li><a href="#tabs-5"><?php esc_html_e( 'Tax Label', 'cardealer-helper' ); ?></a></li>
									<li><a href="#tabs-6"><?php esc_html_e( 'Fuel Efficiency', 'cardealer-helper' ); ?></a></li>
									<li><a href="#tabs-7"><?php esc_html_e( 'PDF Brochure', 'cardealer-helper' ); ?></a></li>
									<li><a href="#tabs-8"><?php esc_html_e( 'Video', 'cardealer-helper' ); ?></a></li>
									<li><a href="#tabs-9"><?php esc_html_e( 'Vehicle Status', 'cardealer-helper' ); ?></a></li>
									<li><a href="#tabs-10"><?php esc_html_e( 'Vehicle Overview', 'cardealer-helper' ); ?></a></li>
									<li><a href="#tabs-11"><?php esc_html_e( 'Features & Options', 'cardealer-helper' ); ?></a></li>
									<li><a href="#tabs-12"><?php esc_html_e( 'Technical Specifications', 'cardealer-helper' ); ?></a></li>
									<li><a href="#tabs-13"><?php esc_html_e( 'General Information', 'cardealer-helper' ); ?></a></li>
									<li><a href="#tabs-14"><?php esc_html_e( 'Vehicle Location', 'cardealer-helper' ); ?></a></li>
									<li><a href="#tabs-15"><?php esc_html_e( 'Excerpt(Short content)', 'cardealer-helper' ); ?></a></li>
								</ul>
								<div class="cdhl-form-group" id="tabs-1">
									<label><?php esc_html_e( 'Vehicle Titles', 'cardealer-helper' ); ?></label><br />
									<select name="car_titles[]" id="cdhl-car-title" class="cdhl-car-title cdhl_multiselect" multiple="multiple" data-placeholder="<?php esc_attr_e( 'Year make model', 'cardealer-helper' ); ?>" tabindex="8">
									<?php
									$vehicle_title = array();
									if ( ! headers_sent() && ! session_id() && cdhl_is_vehicle_import_page() ) {
										session_start();
									}
									if ( isset( $_SESSION['cars_csv']['titles'] ) && ! empty( $_SESSION['cars_csv']['titles'] ) ) {
										$titles      = $_SESSION['cars_csv']['titles'];
										$cars_titles = $_SESSION['cars_csv']['titles'];
									}

									if ( ! empty( $titles ) ) {
										if ( ! isset( $association_data['car_titles'] ) || empty( $association_data['car_titles'] ) ) {
											$association_data['car_titles'] = array();
										}

										if ( ! empty( $association_data['car_titles'] ) ) {
											$vehicle_title = $association_data['car_titles'];
											foreach ( $association_data['car_titles'] as $title_value ) {
												if ( ! empty( $title_value ) ) {
													if ( in_array( $title_value, $titles, true ) ) {
														echo "<option value='" . $title_value . "' selected='selected'>" . $title_value . '</option>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
														$title_index = array_search( $title_value, $titles, true );
														if ( false !== $title_index ) {
															unset( $titles[ $title_index ] );
														}
													}
												}
											}
										}

										foreach ( $titles as $key => $value ) {
											$option_value = $value;
											echo "<option value='" . $option_value . "'" . ( in_array( $option_value, $association_data['car_titles'], true ) ? " selected='selected'" : '' ) . '>' . $value . "</option>\n"; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
										}
									}
									?>
									</select>
									<input type="hidden" name="vehicle_title" value='<?php echo implode( ',', $vehicle_title ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>'/>
									<div class="dec-content">
										<div class="cdhl-form-group">
										<?php esc_html_e( 'Select multiple value like Year, Make, Model', 'cardealer-helper' ); ?>
										</div>
									</div>
								</div>
								<div id="tabs-2">
								<?php
								foreach ( $cars_attributes as $key => $option ) {
									$needle         = $option['slug'];
									$is_association = ( isset( $association_data['csv'] ) && is_array( $association_data['csv'] ) && array_search( $needle, $association_data['csv'], true ) ? true : false );
									if ( 'features-options' !== $key ) {
										?>
											<div class="cdhl_attributes cdhl-csv-import-field-mapping cdhl-csv-import-field-mapping-<?php echo esc_attr( $key ); ?>">
												<label><?php echo esc_html( $option['label'] ); ?></label>
												<div class="cars_attributes">
													<ul class="cdhl_cars_attributes cdhl_connected_sortable" data-limit="1"
														data-name="<?php echo esc_attr( $key ); ?>">
														<?php
														if ( $is_association ) {
															$values = array_keys( $association_data['csv'], $needle, true );
															if ( is_array( $cars_titles ) && ( array_search( $values[0], $cars_titles, true ) !== false || isset( $cars_titles[ $values[0] ] ) ) ) {
																$label = $values[0];
																echo '<li class="ui-state-default ui-sortable-handle"><input type="hidden" name="csv[' . $values[0] . ']" value="' . $needle . '"> ' . $label . '</li>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
															}
														}
														?>
													</ul>
												</div>
											</div>
											<?php
									}
								}
								?>
								</div>
							<?php
							$fl_2 = 3;
							foreach ( $custom_meta_fields as $key => $option ) {
								$needle         = $key;
								$is_association = ( isset( $association_data['csv'] ) && is_array( $association_data['csv'] ) && array_search( $needle, $association_data['csv'], true ) ? true : false );
								if ( 'features_options' === (string) $key ) {
									$taxonomy_name = get_taxonomy( 'car_features_options' );
									$slug          = $taxonomy_name->rewrite['slug'];
									$label         = $taxonomy_name->labels->menu_name;
									?>
									<div id="tabs-<?php echo esc_attr( $fl_2 ); ?>">
										<div class="cdhl_attributes">
											<label><?php echo esc_html( $label ); ?></label>
											<div class="cars_attributes_area">
												<ul class="cdhl_cars_attributes cdhl_connected_sortable" data-limit="0"
													data-name="<?php echo esc_attr( $key ); ?>">
													<?php
													if ( $is_association ) {
														$values = array_keys( $association_data['csv'], $needle, true );
														if ( is_array( $titles ) && ( array_search( $values[0], $titles, true ) !== false || isset( $titles[ $values[0] ] ) ) ) {
															$label = $values[0];
															echo '<li class="ui-state-default ui-sortable-handle"><input type="hidden" name="csv[' . $values[0] . ']" value="' . $needle . '"> ' . $label . '</li>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
														}
													}
													?>
												</ul>
											</div>
											<div class="dec-content">
												<div class="cdhl-form-group">
													<?php esc_html_e( 'We can map multiple values', 'cardealer-helper' ); ?>
												</div>
											</div>
										</div>
									</div>
									<?php
								}

								if ( 'regular_price' === (string) $key ) {
									?>
									<div id="tabs-<?php echo esc_attr( $fl_2 ); ?>">
										<div class="cdhl_attributes">
											<label><?php esc_html_e( 'Regular Price', 'cardealer-helper' ); ?></label>
											<div class="cars_attributes_area">
												<ul class="cdhl_cars_attributes cdhl_connected_sortable" data-limit="1"
													data-name="regular_price">
													<?php
													$needle         = 'regular_price';
													$is_association = ( isset( $association_data['csv'] ) && is_array( $association_data['csv'] ) && array_search( $needle, $association_data['csv'], true ) ? true : false );
													if ( $is_association ) {
														$values = array_keys( $association_data['csv'], $needle, true );
														if ( is_array( $titles ) && ( array_search( $values[0], $titles, true ) !== false || isset( $titles[ $values[0] ] ) ) ) {
															$label = $values[0];
															echo '<li class="ui-state-default ui-sortable-handle"><input type="hidden" name="csv[' . $values[0] . ']" value="' . $needle . '"> ' . $label . '</li>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
														}
													}
													?>
												</ul>
											</div>
										</div>
										<div class="cdhl_attributes">
											<label><?php esc_html_e( 'Sale Price', 'cardealer-helper' ); ?></label>
											<div class="cars_attributes_area">
												<ul class="cdhl_cars_attributes cdhl_connected_sortable" data-limit="1"
													data-name="sale_price">
													<?php
													$needle         = 'sale_price';
													$is_association = ( isset( $association_data['csv'] ) && is_array( $association_data['csv'] ) && array_search( $needle, $association_data['csv'], true ) ? true : false );
													if ( $is_association ) {
														$values = array_keys( $association_data['csv'], $needle, true );
														if ( is_array( $titles ) && ( array_search( $values[0], $titles, true ) !== false || isset( $titles[ $values[0] ] ) ) ) {
															$label = $values[0];
															echo '<li class="ui-state-default ui-sortable-handle"><input type="hidden" name="csv[' . $values[0] . ']" value="' . $needle . '"> ' . $label . '</li>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
														}
													}
													?>
												</ul>
											</div>
										</div>
									</div>
									<?php
								}

								if ( 'fuel_efficiency' === (string) $key ) {
									?>
									<div id="tabs-<?php echo esc_attr( $fl_2 ); ?>">
										<div class="cdhl_attributes">
											<label><?php esc_html_e( 'City MPG', 'cardealer-helper' ); ?></label>
											<div class="cars_attributes_area">
												<ul class="cdhl_cars_attributes cdhl_connected_sortable" data-limit="1"
													data-name="city_mpg">
													<?php
													$is_association = ( isset( $association_data['csv'] ) && is_array( $association_data['csv'] ) && array_search( 'city_mpg', $association_data['csv'], true ) ? true : false );
													if ( $is_association ) {
														$needle = 'city_mpg';
														$values = array_keys( $association_data['csv'], $needle, true );
														if ( is_array( $titles ) && ( array_search( $values[0], $titles, true ) !== false || isset( $titles[ $values[0] ] ) ) ) {
															$label = $values[0];
															echo '<li class="ui-state-default ui-sortable-handle"><input type="hidden" name="csv[' . $values[0] . ']" value="city_mpg"> ' . $label . '</li>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
														}
													}
													?>
												</ul>
											</div>
										</div>
										<div class="cdhl_attributes">
											<label><?php esc_html_e( 'Highway MPG', 'cardealer-helper' ); ?></label>
											<div class="cars_attributes_area">
												<ul class="cdhl_cars_attributes cdhl_connected_sortable" data-limit="1"
													data-name="highway_mpg">
													<?php
													$is_association = ( isset( $association_data['csv'] ) && is_array( $association_data['csv'] ) && array_search( 'highway_mpg', $association_data['csv'], true ) ? true : false );
													if ( $is_association ) {
														$needle = 'highway_mpg';
														$values = array_keys( $association_data['csv'], $needle, true );
														if ( is_array( $titles ) && ( array_search( $values[0], $titles, true ) !== false || isset( $titles[ $values[0] ] ) ) ) {
															$label = $values[0];
															echo '<li class="ui-state-default ui-sortable-handle"><input type="hidden" name="csv[' . $values[0] . ']" value="highway_mpg"> ' . $label . '</li>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
														}
													}
													?>
												</ul>
											</div>
										</div>
									</div>
									<?php
								}
								if ( 'features_options' !== $key && 'regular_price' !== $key && 'fuel_efficiency' !== $key ) {
									?>
								<div id="tabs-<?php echo esc_attr( $fl_2 ); ?>">
									<div class="cdhl_attributes">
										<label><?php echo esc_html( $option[0] ); ?></label>
										<div class="cars_attributes_area">
											<ul class="cdhl_cars_attributes cdhl_connected_sortable" data-limit="<?php echo esc_attr( $option[1] ); ?>" data-name="<?php echo str_replace( ' ', '_', strtolower( $key ) ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>">
												<?php
												if ( $is_association ) {
													$safe_val = $needle;
													$values   = array_keys( $association_data['csv'], $safe_val, true );
													foreach ( $values as $val_key => $val_val ) {
														if ( is_array( $titles ) && ( array_search( $val_val, $titles, true ) !== false || isset( $titles[ $val_val ] ) ) ) {
															$label = $val_val;
															echo '<li class="ui-state-default ui-sortable-handle"><input type="hidden" name="csv[' . $val_val . ']" value="' . $safe_val . '"> ' . $label . '</li>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
														}
													}
												}
												?>
											</ul>
										</div>
										<div class="dec-content">
											<?php
											if ( 0 === (int) $option[1] ? " <i class='fas fa-bars'></i>" : '' ) {
												?>
												<div class="cdhl-form-group">
													<?php esc_html_e( 'We can map multiple values', 'cardealer-helper' ); ?>
												</div>
												<?php
											}
											?>

										</div>
									</div>
								</div>
									<?php
								}
								$fl_2++;
							}
							?>

							</div>
							<div class="cdhl-area-title cdhl-footer">
								<div class="cdhl-button-group">
									<button class="cdhl_save_current_mapping button cdhl_button-primary"><?php esc_html_e( 'Save current mapping', 'cardealer-helper' ); ?></button>
									<button class="cdhl_submit_csv button button-primary" style="vertical-align: super;"><?php esc_html_e( 'Import Vehicles', 'cardealer-helper' ); ?></button>
									<span class="cdhl-loader-img"></span>
								</div>
								<div class="clr"></div>
							</div>
						</div>
					</form>
					<div class="cdhl-import-area-right">
						<ul id="cdhl_csv_items" class="cdhl_connected_sortable">
							<?php
							if ( ! headers_sent() && ! session_id() && cdhl_is_vehicle_import_page() ) {
								session_start();
							}
							if ( isset( $_SESSION['cars_csv']['titles'] ) && ! empty( $_SESSION['cars_csv']['titles'] ) ) {
								$titles = $_SESSION['cars_csv']['titles'];
							}
							if ( ! empty( $titles ) ) {
								asort( $titles );
								foreach ( $titles as $key => $value ) {
									if ( ! isset( $association_data['csv'][ $key ] ) ) {
										echo "<li class='ui-state-default'><input type='hidden' name='csv[" . $value . "]' > " . $value . '</li>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
									}
								}
							}
							?>
						</ul>
					</div>
					<div class="cdhl_import_hidden">
						<div class="cdhl-loader-img"></div>
					</div>
					<?php
					cdhl_import_troubleshooting();
			}
			?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'cdhl_check_file_type' ) ) {
	/**
	 * Call Wp hook when file uploaded
	 */
	function cdhl_check_file_type() {

		if ( ! headers_sent() && ! session_id() && cdhl_is_vehicle_import_page() ) {
			session_start();
		}
		include_once( CDHL_PATH . 'includes/import_csv_xml/cars_import_functions.php' );

		if ( isset( $_POST['csv'] ) && ! empty( $_POST['csv'] ) ) {
			$csv_rows     = $_SESSION['cars_csv']['file_row'];
			$insert_rules = array();

			$insert_rules['overwrite_existing_car_images'] = ( isset( $_POST['overwrite_existing_car_images'] ) && ! empty( $_POST['overwrite_existing_car_images'] ) ? 'true' : 'false' );
			$insert_rules['overwrite_existing_car_price']  = ( isset( $_POST['overwrite_existing_car_price'] ) && ! empty( $_POST['overwrite_existing_car_price'] ) ? 'true' : 'false' );

			$_SESSION['cars_csv']['import_results'] = cdhl_check_import_rules();

			$redirect_uri = add_query_arg(
				array(
					'post_type' => 'cars',
					'page'      => 'cars-import',
				),
				admin_url( 'edit.php' )
			);
			wp_redirect( $redirect_uri );
			exit;
		}

		if ( isset( $_FILES['cars_import_upload']['name'] ) && ! empty( $_FILES['cars_import_upload']['name'] ) ) {
			if ( ! class_exists( 'parseCSV' ) ) {
				include( CDHL_PATH . 'includes/import_csv_xml/parsecsv.lib.php' );
			}

			$ext      = '';
			$filetype = wp_check_filetype( $_FILES['cars_import_upload']['name'] );
			$ext      = strtolower( $filetype['ext'] );

			if ( 'csv' === (string) $ext ) {
				$import_file_content = cdhl_get_file_data( 'from_file', $_FILES['cars_import_upload'] );
				// $import_file_content = cdhl_get_file_data( 'from_file', CDHL_PATH . 'includes/import_csv_xml/sample/kkm-01-col-incorect.csv' );

				if ( 'success' === (string) $import_file_content['status'] ) {

					/**
					 * Get file content in proper formates
					 */
					$file_content_array = cdhl_add_file_content_to_array( $import_file_content['import_data'] );

					if ( is_wp_error( $file_content_array ) ) {

						$_SESSION['cars_csv']['error'] = $file_content_array->get_error_message();

						$redirect_uri = add_query_arg(
							array(
								'post_type' => 'cars',
								'page'      => 'cars-import',
								// 'error'     => 'import_file_content',
								'error'     => 'invalid_file_content',
							),
							admin_url( 'edit.php' )
						);
					} else {
						$_SESSION['cars_csv']['titles']   = $file_content_array['titles'];
						$_SESSION['cars_csv']['file_row'] = $file_content_array['csv_rows'];

						$redirect_uri = add_query_arg(
							array(
								'post_type'           => 'cars',
								'page'                => 'cars-import',
								'import_file_content' => 'yes',
							),
							admin_url( 'edit.php' )
						);
					}
					wp_redirect( $redirect_uri );
					exit;
				} else {
					$redirect_uri = add_query_arg(
						array(
							'post_type' => 'cars',
							'page'      => 'cars-import',
							'error'     => 'file',
						),
						admin_url( 'edit.php' )
					);
					wp_redirect( $redirect_uri );
					exit;
				}
			} else {
				$redirect_uri = add_query_arg(
					array(
						'post_type' => 'cars',
						'page'      => 'cars-import',
						'error'     => 'invalid-file-format',
					),
					admin_url( 'edit.php' )
				);
				wp_redirect( $redirect_uri );
				exit;
			}
		}
	}
	add_action( 'wp_loaded', 'cdhl_check_file_type' );
}

if ( ! function_exists( 'cdhl_save_current_mapping' ) ) {
	/**
	 * Save current mapping
	 */
	function cdhl_save_current_mapping() {
		if ( isset( $_POST['form'] ) && ! empty( $_POST['form'] ) ) {
			$nonce = ( isset( $_POST['nonce'] ) && ! empty( $_POST['nonce'] ) ? $_POST['nonce'] : '' );
			if ( wp_verify_nonce( $nonce, 'cardealer_csv_import' ) ) {
				parse_str( $_POST['form'], $form );
				$option = 'cdhl_csv_mapping_associations';
				if ( ! empty( $form['vehicle_title'] ) ) { // Ordering vehicle title.
					$form['car_titles'] = explode( ',', $form['vehicle_title'] );
					unset( $form['vehicle_title'] );
				}
				update_option( $option, $form );
				esc_html_e( 'Done', 'cardealer-helper' );
			} else {
				esc_html_e( 'Nonce not valid, clear your cache and try again', 'cardealer-helper' );
			}
		}
		die;
	}
	add_action( 'wp_ajax_cdhl_save_current_mapping', 'cdhl_save_current_mapping' );
	add_action( 'wp_ajax_nopriv_cdhl_save_current_mapping', 'cdhl_save_current_mapping' );
}

add_action( 'pmxi_saved_post', 'post_saved', 10, 1 );
if ( ! function_exists( 'post_saved' ) ) {
	/**
	 * WP ALL IMPORT
	 * action after save post
	 * save final price of imported vehicles
	 *
	 * @param string $id .
	 */
	function post_saved( $id ) {
		if ( 'cars' === (string) get_post_type( $id ) ) {
			$final_price = 0;
			$sale_price  = get_post_meta( $id, 'sale_price', true );
			if ( $sale_price ) {
				$final_price = $sale_price;
			} else {
				$regular_price = get_post_meta( $id, 'regular_price', true );
				if ( $regular_price ) {
					$final_price = $regular_price;
				}
			}echo 'wp all import final_price: ' . $final_price; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			update_field( 'final_price', $final_price, $id );
		}
	}
}

if ( ! function_exists( 'cdhl_import_empty_selection' ) ) {
	/**
	 * If noe selection is made in import rules on mapping page
	 */
	function cdhl_import_empty_selection() {
		?>
		<div class="notice notice-success is-dismissible">
			<p><?php esc_html_e( 'Please make selection given in import rules!', 'cardealer-helper' ); ?></p>
		</div>
		<?php
	}
}

if ( ! function_exists( 'cdhl_is_dir_empty' ) ) {
	/**
	 * Function to check directory empty
	 *
	 * @param string $dir .
	 */
	function cdhl_is_dir_empty( $dir ) {
		if ( ! is_readable( $dir ) ) {
			return null;
		}
		return ( count( scandir( $dir ) ) === 2 );
	}
}

if ( ! function_exists( 'cdhl_import_config_instructions' ) ) {
	/**
	 * Import config instructions
	 */
	function cdhl_import_config_instructions() {
		?>
		<div class="cdhl_configuration postbox metabox-holder">
			<h2 class="hndle"><?php esc_html_e( 'Server Configuration Status', 'cardealer-helper' ); ?></h2>
			<div class="inside">
			<?php
				printf(
					wp_kses(
						/* translators: %s: url */
						__( '<p>NOTE: Please check <a href="%1$s" target="_blank" rel="noopener noreferrer">server configuration</a> page for your server configuration status. Ensure all server requirements are satisfied before proceed.</p>', 'cardealer-helper' ),
						array(
							'a' => array(
								'href'   => array(),
								'target' => array(),
								'rel'    => array(),
							),
							'p' => array(),
						)
					),
					esc_url_raw( admin_url( 'admin.php?page=cardealer-system-status' ) )
				);
				$cdhl_warnings = array();
				$memory        = cdhl_convert_memory( WP_MEMORY_LIMIT );
			if ( $memory < 512000000 ) {
				$cdhl_warnings[] = sprintf(
					/* translators: %s: url */
					__( 'Current MEMORY LIMIT: %1$s - We recommend setting memory to at least <strong>512MB</strong>. <br /> Please define memory limit in <strong>wp-config.php</strong> file. To learn how, see: <a href="%2$s" target="_blank" rel="noopener noreferrer">Increasing memory allocated to PHP.</a>', 'cardealer-helper' ),
					size_format( $memory ),
					'http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP'
				);
			}

			$time_limit = (int) ini_get( 'max_execution_time' );
			if ( 6000 > $time_limit && 0 !== $time_limit ) {
				$cdhl_warnings[] = sprintf(
					/* translators: %s: link */
					__( 'Current MAX EXECUTION TIME: %1$s Seconds - We recommend setting max execution time to at least 6000 seconds. <br />See: <a href="%2$s" target="_blank" rel="noopener noreferrer">Increasing max execution to PHP</a>', 'cardealer-helper' ),
					$time_limit,
					'http://codex.wordpress.org/Common_WordPress_Errors#Maximum_execution_time_exceeded'
				);
			}

			// Max upload size.
			$upload_max_filesize = (int) ( ini_get( 'upload_max_filesize' ) );
			if ( 256 > $upload_max_filesize && 0 !== $upload_max_filesize ) {
				$cdhl_warnings[] = sprintf(
					/* translators: %s: url */
					__( 'Current MAX UPLOAD FILE SIZE: %1$s - We recommend setting upload maximum file size to at least 256MB. <br />See: <a href="%2$s" target="_blank" rel="noopener noreferrer">Increasing upload maximum file size in WordPress</a>', 'cardealer-helper' ),
					ini_get( 'upload_max_filesize' ),
					'http://www.wpbeginner.com/wp-tutorials/how-to-increase-the-maximum-file-upload-size-in-wordpress/'
				);
			}

			// Post max size.
			$post_max_size = (int) ( ini_get( 'post_max_size' ) );
			if ( 256 > $post_max_size && 0 !== $post_max_size ) {
				$cdhl_warnings[] = sprintf(
					/* translators: %s: url */
					__( 'Current POST MAX FILE SIZE: %1$s - We recommend setting upload maximum file size to at least 256MB. <br />See: <a href="%2$s" target="_blank" rel="noopener noreferrer">Increasing post maximum file size in WordPress</a>', 'cardealer-helper' ),
					ini_get( 'post_max_size' ),
					'http://www.wpbeginner.com/wp-tutorials/how-to-increase-the-maximum-file-upload-size-in-wordpress/'
				);
			}

			if ( ! empty( $cdhl_warnings ) ) {
				foreach ( $cdhl_warnings as $warning ) {
					?>
					<p><?php echo $warning; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></p>
					<?php
				}
			}
			?>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'cdhl_wp_all_import_alert' ) ) {
	/**
	 * All import alert
	 */
	function cdhl_wp_all_import_alert() {
		?>
		<div class="cdhl-vehicle-import-recommendation notice-info notice">
			<h3 class="cdhl-vehicle-import-recommendation-title"><?php esc_html_e( 'Recommendations - Use WP All Import Pro Plugin', 'cardealer-helper' ); ?></h3>
			<p>
				<?php
					printf(
						wp_kses(
							/* translators: %s: url */
							__( 'To import a large record, we recommend you use <a href="%1$s" target="_blank" rel="noopener noreferrer">WP All Import Pro</a> plugin. Refer to our <a href="%2$s" target="_blank" rel="noopener noreferrer">Car Dealer Theme User Guide</a> and <a href="%3$s" target="_blank" rel="noopener noreferrer">video</a>, where you can find how to import a vehicle listing using the <a href="%1$s" target="_blank" rel="noopener noreferrer">WP All Import Pro</a> plugin.', 'cardealer-helper' ),
							array(
								'a' => array(
									'href'   => array(),
									'target' => array(),
									'rel'    => array(),
								),
							)
						),
						esc_url_raw( 'http://www.wpallimport.com/' ),
						esc_url_raw( 'http://docs.potenzaglobalsolutions.com/docs/cardealer/#import-functionality' ),
						esc_url_raw( 'https://www.youtube.com/watch?v=uNqyLM2ek-g' )
					);
				?>
			</p>
		</div>
		<?php
	}
}

if ( ! function_exists( 'cdhl_import_troubleshooting' ) ) {
	/**
	 * Import troubleshooting
	 */
	function cdhl_import_troubleshooting() {
		cdhl_load_template( 'instructions' );
	}
}

if ( ! function_exists( 'cdhl_load_template' ) ) {
	/**
	 * Load template
	 *
	 * @param string $path .
	 */
	function cdhl_load_template( $path ) {
		$located = locate_template( 'templates/' . $path . '.php' );
		if ( $located ) {
			load_template( $located );
		} else {
			load_template( dirname( __FILE__ ) . '/templates/' . $path . '.php' );
		}
	}
}

function cdhl_is_vehicle_import_page() {
	$is_vehicle_import_page = ( ( isset( $_GET['post_type'] ) && 'cars' === $_GET['post_type'] ) && ( isset( $_GET['page'] ) && 'cars-import' === $_GET['page'] ) );
	return $is_vehicle_import_page;
}
