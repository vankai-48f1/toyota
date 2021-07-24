<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Import CSV data functions
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

if ( ! function_exists( 'cdhl_check_import_rules' ) ) {
	/**
	 * Import rules
	 */
	function cdhl_check_import_rules() {
		if ( ! headers_sent() && ! session_id() && cdhl_is_vehicle_import_page() ) {
			session_start();
		}

		global $cars,$car_dealer_options;
		$insert_rules                                  = array();
		$insert_rules['overwrite_existing_car_images'] = ( isset( $_POST['overwrite_existing_car_images'] ) && ! empty( $_POST['overwrite_existing_car_images'] ) ? 'true' : 'false' );
		$insert_rules['overwrite_existing_car_price']  = ( isset( $_POST['overwrite_existing_car_price'] ) && ! empty( $_POST['overwrite_existing_car_price'] ) ? 'true' : 'false' );

		$csv_rows = $_SESSION['cars_csv']['file_row'];
		// First condition.
		$new_vin = ( isset( $_POST['new_vin'] ) && ! empty( $_POST['new_vin'] ) ? $_POST['new_vin'] : '' );

		// Second condition.
		$vin_not_in_csv       = ( isset( $_POST['vin_not_in_csv'] ) && ! empty( $_POST['vin_not_in_csv'] ) ? $_POST['vin_not_in_csv'] : '' );
		$vin_not_in_csv_in_db = ( isset( $_POST['vin_not_in_csv_in_db'] ) && ! empty( $_POST['vin_not_in_csv_in_db'] ) ? $_POST['vin_not_in_csv_in_db'] : '' );

		// last condition.
		$cdhl_duplicate_check_vin = ( isset( $_POST['duplicate_check_vin'] ) && ! empty( $_POST['duplicate_check_vin'] ) ? $_POST['duplicate_check_vin'] : '' );

		$overwrite_car_price   = ( isset( $_POST['overwrite_existing_car_price'] ) && ! empty( $_POST['overwrite_existing_car_price'] ) ? $_POST['overwrite_existing_car_price'] : '' );
		$overwrite_car_images  = ( isset( $_POST['overwrite_existing_car_images'] ) && ! empty( $_POST['overwrite_existing_car_images'] ) ? $_POST['overwrite_existing_car_images'] : '' );
		$remove_cars_not_found = ( isset( $_POST['remove_cars_not_found'] ) && ! empty( $_POST['remove_cars_not_found'] ) ? $_POST['remove_cars_not_found'] : 'false' );

		// if no selection done for overrite, then reset duplicate_check_vin to null.
		if ( empty( $overwrite_car_price ) && empty( $overwrite_car_images ) && isset( $_POST['overwrite_existing'] ) && 'on' !== $_POST['overwrite_existing'] ) {
			$cdhl_duplicate_check_vin = '';
		}

		$csv_post_data        = ( isset( $_POST['csv'] ) && ! empty( $_POST['csv'] ) ? $_POST['csv'] : '' );
		$cars_attributes      = cardealer_get_all_taxonomy_with_terms();
		$cars_attributes_safe = cardealer_get_all_taxonomy_with_terms();

		$imported_cars_data = array();

		if ( ! empty( $csv_post_data ) ) {
			$current_cars = get_posts(
				array(
					'post_type'      => 'cars',
					'posts_per_page' => -1,
					'post_status'    => 'any',
				)
			);

			$all_cars                  = array();
			$current_check             = array();
			$terms_array['vin-number'] = array();
			$all_db_vin_array          = array();
			$args                      = array(
				'orderby' => 'name',
				'order'   => 'ASC',
				'fields'  => 'all',
			);
			foreach ( $current_cars as $cars ) {
				$terms = wp_get_post_terms( $cars->ID, 'car_vin_number', $args );
				if ( isset( $terms ) && ! empty( $terms ) ) {
					/**
					* Get all vin number from database
					*/
					$terms_array['vin-number'] = (array) $terms[0];
					$all_db_vin_array[]        = $terms[0]->name;
				}
				if ( isset( $terms_array[ $cdhl_duplicate_check_vin ] ) && is_array( $terms_array[ $cdhl_duplicate_check_vin ] ) && ! empty( $terms_array[ $cdhl_duplicate_check_vin ] ) ) {
					$check_label = ( isset( $terms_array[ $cdhl_duplicate_check_vin ]['name'] ) && ! empty( $terms_array[ $cdhl_duplicate_check_vin ]['name'] ) ? $terms_array[ $cdhl_duplicate_check_vin ]['name'] : '' );
					if ( isset( $check_label ) ) {
						$current_check[ $cars->ID ] = $check_label;
						$all_cars[ $cars->ID ]      = $check_label;
					}
				}
			}

			$all_vin_from_csv_arr = array();
			foreach ( $csv_rows as $key => $row ) {
				$vin_number             = cdhl_search_array_keys( $row, 'vin-number', $csv_post_data );
				$all_vin_from_csv_arr[] = trim( $vin_number );
			}

			$diff_arr            = array_diff( $all_db_vin_array, $all_vin_from_csv_arr );
			$updated_cars_status = array();
			$updated_post_status = array();
			$delete_post         = array();

			/**
			* Rule II
			* If Vehicle exist in Database and not in CSV
			*/
			$vin_not_in_csv       = ( isset( $_POST['vin_not_in_csv'] ) && ! empty( $_POST['vin_not_in_csv'] ) ? $_POST['vin_not_in_csv'] : '' );
			$vin_not_in_csv_in_db = ( isset( $_POST['vin_not_in_csv_in_db'] ) && ! empty( $_POST['vin_not_in_csv_in_db'] ) ? $_POST['vin_not_in_csv_in_db'] : '' );

			if ( ! empty( $diff_arr ) ) {
				foreach ( $diff_arr as $nkey ) {
					if ( ! empty( $vin_not_in_csv ) ) {
						$args  = array(
							'post_type'      => 'cars',
							'post_status'    => 'any',
							'posts_per_page' => -1,
							'tax_query'      => array(
								array(
									'taxonomy' => 'car_vin_number',
									'field'    => 'name',
									'terms'    => $nkey,
								),
							),
						);
						$query = new WP_Query( $args );
						if ( $query->have_posts() ) {
							while ( $query->have_posts() ) :
								$query->the_post();
								$theid = get_the_ID();
								// Perform action based on action given in drop down.
								switch ( $vin_not_in_csv_in_db ) {
									case 'sold':
										update_field( 'car_status', $vin_not_in_csv_in_db, $theid );
										$updated_cars_status[] = array(
											'id'    => $theid,
											'title' => get_the_title(),
										);
										break;
									case 'unpublished':
										$my_post = array(
											'ID'          => $theid,
											'post_status' => 'draft',
										);
										wp_update_post( $my_post );
										$updated_post_status[] = array(
											'id'    => $theid,
											'title' => get_the_title(),
										);
										break;
									case 'delete':
										$delete_post[] = array(
											'id'    => $theid,
											'title' => get_the_title(),
										);
										wp_trash_post( $theid );
										break;
								}
							endwhile;
							wp_reset_postdata();
						}
					}
				}
			}

			/**
			* Rule I
			* CSV rows array
			* If Vehicle exist in CSV not in Database
			*/
			$new_vin_number_array = array();

			// Post Status.
			$post_status = 'publish';
			if ( isset( $car_dealer_options['import_post_status'] ) && ! empty( $car_dealer_options['import_post_status'] ) ) {
				$post_status = $car_dealer_options['import_post_status'];
			}

			// import post status.
			$imp_post_status = ( isset( $_POST['new_vin_imp_post_status'] ) && ! empty( $_POST['new_vin_imp_post_status'] ) ? $_POST['new_vin_imp_post_status'] : '' );

			// total rows to import.
			$_SESSION['cdhl_total_csv_rows'] = count( $csv_rows );
			foreach ( $csv_rows as $key => $row ) {

				if ( isset( $_SESSION['cdhl_server_error'] ) ) {
					break;
				}

				if ( ! empty( $_POST['vehicle_title'] ) ) { // Ordering vehicle title.
					$_POST['car_titles'] = explode( ',', $_POST['vehicle_title'] );
					unset( $_POST['vehicle_title'] );
				}

				if ( isset( $_POST['car_titles'] ) && ! empty( $_POST['car_titles'] ) ) {
					$post_title = cdhl_get_multiple_values( $_POST['car_titles'], $row, $csv_post_data );
				}
				if ( empty( $post_title ) ) {
					$post_title = 'N/A';
				}
				$vin_number = cdhl_search_array_keys( $row, 'vin-number', $csv_post_data );

				/**
				* Rules one if Vehicle exist in CSV not in Database
				*/
				if ( ! empty( $new_vin ) ) {

					$insertinfo = cdhl_get_car_meta( $cars_attributes, $cars_attributes_safe, $row, $csv_post_data );

					if ( ! empty( $vin_number ) ) {
						if ( ! in_array( $vin_number, $all_db_vin_array, true ) ) {
							$new_vin_number_array[] = $vin_number;
							if ( ! empty( $imp_post_status ) ) {
								$post_status = $imp_post_status;

								$insert_info = array(
									'post_type'   => 'cars',
									'post_title'  => ( $post_title ),
									'post_status' => $post_status,
								);
								$insert_id   = wp_insert_post( $insert_info );
								foreach ( $insertinfo as $k => $val ) {
									insert_update_cars_attributs_and_meta( $k, $val, $insert_id );
								}

								$car_status = cdhl_search_array_keys( $row, 'car_status', $csv_post_data );
								if ( isset( $car_status ) && ! empty( $car_status ) ) {
									update_field( 'car_status', strtolower( $car_status ), $insert_id );
								}

								$attach_id = cdhl_import_pdf_file( $row, $csv_post_data );
								if ( ! empty( $attach_id ) && 'None' !== $attach_id ) {
									update_field( 'pdf_file', $attach_id, $insert_id );
								}

								$post_content = cdhl_search_array_keys( $row, 'vehicle_overview', $csv_post_data );
								if ( ! empty( $post_content ) ) {
									update_field( 'vehicle_overview', $post_content, $insert_id );
								}
								$imported_cars[ $insert_id ] = ( $post_title );
							}
						}
					}
				}

				if ( ! empty( $cdhl_duplicate_check_vin ) ) {

					/**
					* Rule III
					* IF Check for duplicate cars using: VIN Number is checked
					*/
					$search_value  = cdhl_search_array_keys( $row, $cdhl_duplicate_check_vin, $csv_post_data );
					$duplicate_ids = array_keys( $current_check, $search_value, true );

					if ( ! empty( $duplicate_ids ) ) {
						foreach ( $duplicate_ids as $duplicate_id ) {
							$update_post                                     = get_post( $duplicate_id, ARRAY_A );
							$insert_id                                       = $update_post['ID'];
							$imported_cars['duplicate_vin'][ $duplicate_id ] = $update_post['post_title'];

							if ( isset( $all_cars[ $duplicate_id ] ) ) {
								unset( $all_cars[ $duplicate_id ] );
							}

							if ( ! empty( $update_post ) && isset( $_POST['overwrite_existing'] ) && 'on' === (string) $_POST['overwrite_existing'] ) {
								$post_title                = cdhl_get_multiple_values( $_POST['car_titles'], $row, $csv_post_data );
								$dependancy_categories     = array();
								$update_post['post_title'] = ( $post_title );

								wp_update_post( $update_post );
								$post_content = cdhl_search_array_keys( $row, 'vehicle_overview', $csv_post_data );
								if ( isset( $post_content ) && ! empty( $post_content ) ) {
									update_field( 'vehicle_overview', $post_content, $insert_id );
								}

								foreach ( $cars_attributes as $key => $option ) {
									$key   = ( isset( $option['slug'] ) && ! empty( $option['slug'] ) ? $option['slug'] : str_replace( ' ', '_', strtolower( $key ) ) );
									$value = cdhl_search_array_keys( $row, $key, $csv_post_data );

									if ( isset( $option['multiple'] ) ) {
										$value = cdhl_search_array_keys( $row, $key, $csv_post_data );
									} else {
										$value = cdhl_search_array_keys( $row, $key, $csv_post_data );
										insert_update_cars_attributs_and_meta( $key, $value, $insert_id );
									}

									$tax_label = cdhl_search_array_keys( $row, 'tax_label', $csv_post_data );
									if ( isset( $tax_label ) && ! empty( $tax_label ) ) {
										update_field( 'tax_label', $tax_label, $insert_id );
									}

									$car_status = cdhl_search_array_keys( $row, 'car_status', $csv_post_data );
									if ( isset( $car_status ) && ! empty( $car_status ) ) {
										update_field( 'car_status', $car_status, $insert_id );
									}

									$city_mpg = cdhl_search_array_keys( $row, 'city_mpg', $csv_post_data );
									if ( isset( $city_mpg ) && ! empty( $city_mpg ) ) {
										update_field( 'city_mpg', $city_mpg, $insert_id );
									}

									$highway_mpg = cdhl_search_array_keys( $row, 'highway_mpg', $csv_post_data );
									if ( isset( $highway_mpg ) && ! empty( $highway_mpg ) ) {
										update_field( 'highway_mpg', $highway_mpg, $insert_id );
									}

									$pdf_file = cdhl_search_array_keys( $row, 'pdf_file', $csv_post_data );
									if ( isset( $pdf_file ) && ! empty( $pdf_file ) ) {
										update_field( 'pdf_file', $pdf_file, $insert_id );
									}

									$video_link = cdhl_search_array_keys( $row, 'video_link', $csv_post_data );
									if ( isset( $video_link ) && ! empty( $video_link ) ) {
										update_field( 'video_link', $video_link, $insert_id );
									}

									$technical_specifications = cdhl_search_array_keys( $row, 'technical_specifications', $csv_post_data );
									if ( isset( $technical_specifications ) && ! empty( $technical_specifications ) ) {
										update_field( 'technical_specifications', $technical_specifications, $insert_id );
									}

									$general_information = cdhl_search_array_keys( $row, 'general_information', $csv_post_data );
									if ( isset( $general_information ) && ! empty( $general_information ) ) {
										update_field( 'general_information', $general_information, $insert_id );
									}

									$post_excerpt = cdhl_search_array_keys( $row, 'excerpt', $csv_post_data );
									if ( isset( $post_excerpt ) && ! empty( $post_excerpt ) ) {
										$my_post = array(
											'ID'           => $insert_id,
											'post_excerpt' => $post_excerpt,
										);
										wp_update_post( $my_post );
									}

									// Features & Options.
									$values               = cdhl_search_array_keys( $row, 'features_options', $csv_post_data );
									$features_and_options = array();
									$dynamicsep           = '';

									if ( ! empty( $values ) ) {
										if ( empty( $dynamicsep ) ) {
											if ( strstr( $values, ',' ) ) {
												$dynamicsep = ',';
											} elseif ( strstr( $values, '<br>' ) ) {
												$dynamicsep = '<br>';
											} elseif ( strstr( $values, '|' ) ) {
												$dynamicsep = '|';
											} elseif ( strstr( $values, ';' ) ) {
												$dynamicsep = ';';
											}
										}

										if ( isset( $dynamicsep ) && ! empty( $dynamicsep ) ) {
											$values = explode( $dynamicsep, $values );
											foreach ( $values as $val ) {
												$features_and_options[] = $val;
											}
										} else {
											$features_and_options[] = $values;
										}
									}

									if ( ! empty( $features_and_options ) ) {

										$options = @$cars_attributes_safe['features-options']['terms'];

										foreach ( $features_and_options as $option ) {
											$option = trim( $option );
											$option = preg_replace( '/\x{EF}\x{BF}\x{BD}/u', '', @iconv( mb_detect_encoding( $option ), 'UTF-8', $option ) );

											if ( is_array( $options ) && ! in_array( $option, $options, true ) ) {
												if ( ! empty( $option ) && 'None' !== $option ) {
													wp_set_object_terms( $insert_id, $option, 'car_features_options', false );
												}
											}
										}
									}

									/* Default Latitude & Longitude */
									$default_latitude  = ( isset( $car_dealer_options['default_value_lat'] ) && ! empty( $car_dealer_options['default_value_lat'] ) ? $car_dealer_options['default_value_lat'] : '' );
									$default_longitude = ( isset( $car_dealer_options['default_value_long'] ) && ! empty( $car_dealer_options['default_value_long'] ) ? $car_dealer_options['default_value_long'] : '' );

									// map location.
									$vehicle_location = '';
									$vehicle_location = cdhl_search_array_keys( $row, 'vehicle_location', $csv_post_data );
									$get_lat_lnt      = cdhl_getLatLnt( $vehicle_location );
									$latitude         = $get_lat_lnt['lat'];
									$longitude        = $get_lat_lnt['lng'];

									// map location.
									$location = array(
										'address'   => $vehicle_location,
										'latitude'  => $latitude,
										'longitude' => $longitude,
										'zoom'      => '10',
									);
									if ( isset( $location ) && '0' !== (string) $get_lat_lnt['addr_found'] ) {
										update_field( 'vehicle_location', $location, $insert_id );
									}
								}
							}

							if ( isset( $overwrite_car_images ) && ( 'true' === (string) $overwrite_car_images || 'on' === (string) $overwrite_car_images ) ) {
								$car_images = get_post_meta( $duplicate_id, 'car_images', true );
								if ( ! empty( $car_images ) ) {
									foreach ( $car_images as $image_id ) {
										wp_delete_attachment( $image_id, true );
									}
								}
								$new_car_images = cdhl_import_car_images( $row, $csv_post_data );
								update_field( 'car_images', $new_car_images, $duplicate_id );
							}

							if ( isset( $overwrite_car_price ) && ( 'true' === (string) $overwrite_car_price || 'on' === (string) $overwrite_car_price ) ) {
								$regular_price = cdhl_search_array_keys( $row, 'regular_price', $csv_post_data );
								if ( isset( $regular_price ) && ! empty( $regular_price ) ) {
									update_field( 'regular_price', $regular_price, $insert_id );
									$final_price = $regular_price;
								}

								$sale_price = cdhl_search_array_keys( $row, 'sale_price', $csv_post_data );
								if ( isset( $sale_price ) && ! empty( $sale_price ) ) {
									update_field( 'sale_price', $sale_price, $insert_id );
									$final_price = $sale_price;
								}
								// set final price.
								if ( isset( $final_price ) && function_exists( 'update_field' ) ) {
									update_field( 'final_price', $final_price, $insert_id );
								}
							}
						}
					}
				}
			}

			$return     = '';
			$return_arr = array();

			$duplicates = ( isset( $imported_cars['duplicate_vin'] ) && ! empty( $imported_cars['duplicate_vin'] ) ? $imported_cars['duplicate_vin'] : '' );
			if ( isset( $imported_cars['duplicate_vin'] ) ) {
				unset( $imported_cars['duplicate_vin'] );
			}

			$title_empty       = esc_html__( 'Title Empty', 'cardealer-helper' );
			$edit_label        = esc_html__( 'Edit', 'cardealer-helper' );
			$records_processed = array();
			if ( ! empty( $imported_cars ) ) {
				$records_processed['inserted'] = count( $imported_cars );

				/* translators: %s: string */
				$return .= sprintf( __( 'Successfully inserted %1$d vehicles', 'cardealer-helper' ), $records_processed['inserted'] ) . ':<br>';

				if ( ! empty( $new_vin_number_array ) ) {
					$return .= "<div class='clear'></div><ul class='cdhl_import_list'>";
					foreach ( $imported_cars as $key => $car ) {
						if ( 'duplicate' !== $key ) {
							$return .= "<li class='ddd'><a href='" . get_permalink( $key ) . "'>" . ( ! empty( $car ) ? $car : $title_empty ) . "</a> (<a href='" . get_edit_post_link( $key ) . "'>" . $edit_label . '</a>)</li>';
						}
					}
					$return .= "</ul><div class='clear'></div>";
				}
			}

			if ( ! empty( $updated_cars_status ) ) {
				$records_processed['updated_cars_status'] = count( $updated_cars_status );

				/* translators: %s: string */
				$return .= sprintf( __( 'Total %1$d vehicles status updated, ', 'cardealer-helper' ), $records_processed['updated_cars_status'] );

				$return .= esc_html__( 'Following is the list:', 'cardealer-helper' ) . '<br>';
				$return .= "<div class='clear'></div><ul class='cdhl_import_list'>";

				foreach ( $updated_cars_status as $updatedcarsstatus ) {
					$return .= "<li class='ddsssd'><a href='" . get_permalink( $updatedcarsstatus['id'] ) . "'>" . ( ! empty( $updatedcarsstatus['title'] ) ? $updatedcarsstatus['title'] : $title_empty ) . "</a> (<a href='" . get_permalink( $updatedcarsstatus['id'] ) . "'>" . $edit_label . '</a>)</li>';
				}
				$return .= "</ul><div class='clear'></div>";
			}

			if ( ! empty( $updated_post_status ) ) {
				$records_processed['updated'] = count( $updated_post_status );

				/* translators: %s: string */
				$return .= sprintf( __( 'Total %1$d cars updated, ', 'cardealer-helper' ), $records_processed['updated'] );

				$return .= esc_html__( 'Following is the list:', 'cardealer-helper' ) . ':<br>';
				$return .= "<div class='clear'></div><ul class='cdhl_import_list'>";

				foreach ( $updated_post_status as $updatedpoststatus ) {
					$return .= "<li class='daaaadd'><a href='" . get_permalink( $updatedpoststatus['id'] ) . "'>" . ( ! empty( $updatedpoststatus['title'] ) ? $updatedpoststatus['title'] : $title_empty ) . "</a> (<a href='" . get_permalink( $updatedpoststatus['id'] ) . "'>" . $edit_label . '</a>)</li>';
				}
				$return .= "</ul><div class='clear'></div>";
			}

			if ( ! empty( $delete_post ) ) {
				$records_processed['deleted'] = count( $delete_post );

				/* translators: %s: string */
				$return .= sprintf( __( 'Total %1$d vehicles deleted, Following is the list:', 'cardealer-helper' ), $records_processed['deleted'] ) . '<br>';

				$return .= "<div class='clear'></div><ul class='cdhl_import_list'>";

				foreach ( $delete_post as $deletepost ) {
					$return .= "<li class='dAWDADdd'>" . ( ! empty( $deletepost['title'] ) ? $deletepost['title'] : $title_empty ) . '</li>';
				}
				$return .= "</ul><div class='clear'></div>";
			}

			if ( ! empty( $duplicates ) ) {
				if ( ! empty( $output ) && 'array' === (string) $output ) {
					$return_arr['duplicates'] = $duplicates;
				} else {
					$records_processed['duplicates'] = count( $duplicates );
					if ( isset( $_POST['overwrite_existing'] ) && 'on' === (string) $_POST['overwrite_existing'] ) {
						/* translators: %s: count */
						$return .= sprintf( __( 'Total %1$d vehicles were updated with new information from the imported file', 'cardealer-helper' ), count( $duplicates ) ) . ':<br>';
					} else {
						/* translators: %s: count */
						$return .= sprintf( __( 'Total %1$d vehicles were not imported because a duplicate car was detected.', 'cardealer-helper' ), count( $duplicates ) ) . ':<br>';
					}
					$return .= "<div class='clear'></div><ul class='cdhl_import_list'>";
					foreach ( $duplicates as $key => $car ) {
						$return .= "<li class='w232ddd'>" . ( ! empty( $car ) ? $car : $title_empty ) . " (<a href='" . get_edit_post_link( $key ) . "'>" . $edit_label . '</a>)</li>';
					}
					$return .= "</ul><div class='clear'></div>";
				}
			}

			if ( 'false' !== $remove_cars_not_found ) {
				$deleted_not_found = array();

				if ( isset( $duplicate_ids ) && ! empty( $duplicate_ids ) ) {
					$not_found_cars = array_diff( $all_cars, $duplicate_ids );

					if ( ! empty( $not_found_cars ) ) {
						foreach ( $not_found_cars as $not_found_id => $not_found_title ) {
							wp_trash_post( $not_found_id );
							$deleted_not_found[ $not_found_id ] = $not_found_title;
						}
					}

					if ( ! empty( $output ) && 'array' === (string) $output ) {
						$return['deleted'] = $deleted_not_found;
					} else {
						$records_processed['deleted_not_found'] = count( $deleted_not_found );

						/* translators: %s: string */
						$return .= sprintf( __( 'Total %1$d vehicles were deleted since they were not found in the import file', 'cardealer-helper' ), $records_processed['deleted_not_found'] ) . ':<br>';

						$return .= "<div class='clear'></div><ul class='cdhl_import_list'>";

						foreach ( $deleted_not_found as $key => $car ) {
							$return .= '<li>' . ( ! empty( $car ) ? $car : $title_empty ) . ' </li>';
						}
						$return .= "</ul><div class='clear'></div>";
					}
				}
			}

			// Records processed.
			$records_imported = 0;
			foreach ( $records_processed as $record ) {
				$records_imported += $record;
			}
			$_SESSION['records_processed'] = $records_imported;

			// Generate entry for import log.
			$ins_post = array(
				'post_type'    => 'pgs_import_log',
				/* translators: %s: string */
				'post_title'   => sprintf( __( 'CSV Imported ( %s )', 'cardealer-helper' ), date( 'Y-m-d' ) ),
				'post_content' => '',
			);
			$post_id  = wp_insert_post( $ins_post );
			update_field( 'records_imported', $records_imported, $post_id );

			$result = array(
				'result'     => $return,
				'return_arr' => $return_arr,
			);

			return $result;
		}
	}
}

if ( ! function_exists( 'insert_update_cars_attributs_and_meta' ) ) {
	/**
	 * Insert/update cars attributs & meta
	 *
	 * @param string $k .
	 * @param string $val .
	 * @param string $insert_id .
	 */
	function insert_update_cars_attributs_and_meta( $k, $val, $insert_id ) {
		do_action( 'cdhl_insert_update_attrs_meta_start', $k, $val, $insert_id );
		switch ( $k ) {
			case 'car_images':
				if ( ! empty( $val ) ) {
					update_field( 'car_images', $val, $insert_id );
				}
				break;
			case 'regular_price':
			case 'sale_price':
				if ( 'regular_price' === (string) $k ) {
					$regular_price = $val;
					$final_price   = $regular_price;
					update_field( 'regular_price', $regular_price, $insert_id );
				}

				if ( 'sale_price' === (string) $k ) {
					$sale_price  = $val;
					$final_price = $sale_price;
					update_field( 'sale_price', $sale_price, $insert_id );
				}

				// set final price.
				if ( isset( $final_price ) && function_exists( 'update_field' ) ) {
					update_field( 'final_price', $final_price, $insert_id );
				}
				break;
			case 'tax_label':
				if ( ! empty( $val ) && 'None' !== $val ) {
					update_field( 'tax_label', $val, $insert_id );
				}
				break;
			case 'city_mpg':
				if ( ! empty( $val ) && 'None' !== $val ) {
					update_field( 'city_mpg', $val, $insert_id );
				}
				break;
			case 'highway_mpg':
				update_field( 'highway_mpg', $val, $insert_id );
				break;
			case 'video_link':
				update_field( 'video_link', $val, $insert_id );
				break;
			case 'year':
				$year = (string) $val;
				if ( ! empty( $year ) && 'None' !== $year ) {
					wp_set_object_terms( $insert_id, $year, 'car_year', false );
				}
				break;
			case 'make':
				$make = $val;
				if ( ! empty( $val ) && 'None' !== $val ) {
					wp_set_object_terms( $insert_id, $make, 'car_make', false );
				}
				break;
			case 'model':
				$model = $val;
				if ( ! empty( $val ) && 'None' !== $val ) {
					wp_set_object_terms( $insert_id, $model, 'car_model', false );
				}
				break;
			case 'body-style':
				if ( ! empty( $val ) && 'None' !== $val ) {
					$body_style = $val;
					wp_set_object_terms( $insert_id, $body_style, 'car_body_style', false );
				}
				break;
			case 'mileage':
				if ( ! empty( $val ) && 'None' !== $val ) {
					$mileage = $val;
					wp_set_object_terms( $insert_id, $mileage, 'car_mileage', false );
				}
				break;
			case 'fuel-type':
				if ( ! empty( $val ) && 'None' !== $val ) {
					wp_set_object_terms( $insert_id, $val, 'car_fuel_type', false );
				}
				break;
			case 'fuel-economy':
				if ( ! empty( $val ) && 'None' !== $val ) {
					$fuel_economy = $val;
					wp_set_object_terms( $insert_id, $fuel_economy, 'car_fuel_economy', false );
				}
				break;
			case 'trim':
				if ( ! empty( $val ) && 'None' !== $val ) {
					wp_set_object_terms( $insert_id, $val, 'car_trim', false );
				}
				break;
			case 'transmission':
				$transmission = $val;
				if ( ! empty( $val ) && 'None' !== $val ) {
					wp_set_object_terms( $insert_id, $transmission, 'car_transmission', false );
				}
				break;
			case 'condition':
				$condition = $val;
				if ( ! empty( $val ) && 'None' !== $val ) {
					wp_set_object_terms( $insert_id, $condition, 'car_condition', false );
				}
				break;
			case 'drivetrain':
				$drivetrain = $val;
				if ( ! empty( $val ) && 'None' !== $val ) {
					wp_set_object_terms( $insert_id, $drivetrain, 'car_drivetrain', false );
				}
				break;
			case 'engine':
				$engine = $val;
				if ( ! empty( $val ) && 'None' !== $val ) {
					wp_set_object_terms( $insert_id, $engine, 'car_engine', false );
				}
				break;
			case 'exterior-color':
				$exterior_color = $val;
				if ( ! empty( $val ) && 'None' !== $val ) {
					wp_set_object_terms( $insert_id, $exterior_color, 'car_exterior_color', false );
				}
				break;
			case 'interior-color':
				$interior_color = $val;
				if ( ! empty( $val ) && 'None' !== $val ) {
					wp_set_object_terms( $insert_id, $interior_color, 'car_interior_color', false );
				}
				break;
			case 'stock-number':
				$stock_number = $val;
				if ( ! empty( $val ) && 'None' !== $val ) {
					wp_set_object_terms( $insert_id, $stock_number, 'car_stock_number', false );
				}
				break;
			case 'vin-number':
				$vin_number = $val;
				if ( ! empty( $val ) && 'None' !== $val ) {
					wp_set_object_terms( $insert_id, $vin_number, 'car_vin_number' );
				}
				break;
			case 'features-options':
				if ( ! empty( $val ) && is_array( $val ) ) {

					foreach ( $val as $option ) {
						wp_set_object_terms( $insert_id, $option, 'car_features_options', true );
					}
				} else {
					if ( ! is_array( $val ) && 'None' !== $val ) {
						$car_features_options = explode( ',', $val );
						foreach ( $car_features_options as $option ) {
							wp_set_object_terms( $insert_id, $option, 'car_features_options', true );
						}
					}
				}
				break;
			case 'technical_specifications':
				if ( ! empty( $val ) && 'None' !== $val ) {
					update_field( 'technical_specifications', $val, $insert_id );
				}
				break;
			case 'general_information':
				if ( ! empty( $val ) && 'None' !== $val ) {
					update_field( 'general_information', $val, $insert_id );
				}
				break;
			case 'post_excerpt':
				if ( ! empty( $val ) && 'None' !== $val ) {
					$my_post = array(
						'ID'           => $insert_id,
						'post_excerpt' => $val,
					);
					wp_update_post( $my_post );
				}
				break;
			case 'location_map':
				$get_lat_lnt  = cdhl_getLatLnt( $val['vehicle_location'] );
				$latitude     = $get_lat_lnt['lat'];
				$longitude    = $get_lat_lnt['lng'];
				$mapvalue     = array(
					'address' => $val['vehicle_location'],
					'lng'     => $longitude,
					'lat'     => $latitude,
				);
				$location_map = $mapvalue;
				if ( isset( $location_map ) && '0' !== (string) $get_lat_lnt['addr_found'] ) {
					update_field( 'vehicle_location', $location_map, $insert_id );
				}
				break;
			case 'engine':
				break;
			default:
				/**
				 * Additional attributes
				 */
				$new_tax_obj = get_taxonomy( $k );
				if( isset($new_tax_obj->include_in_filters) && $new_tax_obj->include_in_filters == true ) {
					$new_tax_val = $val;
					if ( ! empty( $new_tax_val ) && 'None' !== $new_tax_val ) {
						wp_set_object_terms( $insert_id, $new_tax_val, $k, false );
					}
				}
				break;
		}
	}
}

if ( ! function_exists( 'cdhl_get_file_data' ) ) {
	/**
	 * Get file data
	 *
	 * @param string $type .
	 * @param string $file .
	 */
	function cdhl_get_file_data( $type, $file ) {
		$data = array(
			'status' => 'error',
			'msg'    => esc_html__( 'Somthing went wrong. Please try again.', 'cardealer-helper' ),
		);
		if ( 'from_url' === (string) $type ) {
			/**
			 * Check is valid url
			 */
			if ( filter_var( $file, FILTER_VALIDATE_URL ) ) {
				/**
				 * Get file content from url
				 */
				$wp_file_get = wp_remote_get( $file, array( 'timeout' => 30 ) );

				/**
				 * Check for error
				 */
				if ( ! is_wp_error( $wp_file_get ) ) {

					$import_file_conten = $wp_file_get['body'];
					$data               = array(
						'status'      => 'success',
						'msg'         => '',
						'import_data' => $import_file_conten,
					);

					$_SESSION['cars_csv']['import_conten'] = $import_file_conten;
				} else {
					$data[] = array(
						'status' => 'error',
						'msg'    => $wp_file_get->get_error_message(),
					);
				}
			} else {
				$data[] = array(
					'status' => 'error',
					'msg'    => esc_html__( 'Not a valid URL', 'cardealer-helper' ),
				);
			}
		} elseif ( 'from_file' === (string) $type ) {
			$file_error_code = (int) $file['error'];

			$empty_file_string = esc_html__( 'There was no file uploaded', 'cardealer-helper' );

			if ( empty( $file ) ) {
				$data[] = array(
					'status' => 'error',
					'msg'    => esc_html__( 'There was no file uploaded', 'cardealer-helper' ),
				);
			}
			$file['error'] = 3;

			$file_upload_errors = array(
					0 => esc_html__( 'There is no error, the file uploaded with success.', 'cardealer-helper' ),
					1 => esc_html__( 'The uploaded file exceeds the "Maximum Upload File Size" limit of the server (upload_max_filesize directive in php.ini).', 'cardealer-helper' ),
					2 => esc_html__( 'The uploaded file exceeds the "Maximum File Size" (MAX_FILE_SIZE directive) that was specified in the HTML form.', 'cardealer-helper' ),
					3 => esc_html__( 'The uploaded file was only partially uploaded.', 'cardealer-helper' ),
					4 => esc_html__( 'No file was uploaded.', 'cardealer-helper' ),
					6 => esc_html__( 'Missing a temporary folder on your server.', 'cardealer-helper' ),
					7 => esc_html__( 'Failed to write file to disk on your server.', 'cardealer-helper' ),
					8 => esc_html__( 'A PHP extension stopped the file upload. PHP does not provide a way to ascertain which extension caused the file upload to stop; examining the list of loaded extensions with phpinfo() may help. For further assistance, contact your hosting service provider.', 'cardealer-helper' ),
			);

			if ( 0 === $file_error_code ) {
				$import_file_conten = file_get_contents( $file['tmp_name'] );

				$data = array(
					'status'      => 'success',
					'import_data' => $import_file_conten,
				);

				$_SESSION['cars_csv']['import_conten'] = $import_file_conten;
			} else {
				$data[] = array(
					'status' => 'error',
					'msg'    => $file_upload_errors[ $file_error_code ],
				);
			}

			if ( 'success' !== $data['status'] ) {
				$_SESSION['cars_csv']['error'] = $data;
			}
		}
		return $data;
	}
}

if ( ! function_exists( 'cdhl_add_file_content_to_array' ) ) {
	/**
	 * Add CSV file content into a array
	 *
	 * @param string $import_file_content .
	 * @param string $xml_parent .
	 */
	function cdhl_add_file_content_to_array( $import_file_content, $xml_parent = '' ) {
		global $car_dealer_options;
		$return = array();

		$csv = new ParseCsv\Csv();

		// Set delimiter
		$csv->delimiter = ( isset( $car_dealer_options['csv_delimiter'] ) && ! empty( $car_dealer_options['csv_delimiter'] ) ? $car_dealer_options['csv_delimiter'] : ',' );

		// Parse CSV content.
		$csv->parse( $import_file_content );

		$arr_data = cdhl_check_empty_array( $csv->data );

		$csv_rows = array_values( $arr_data );
		$titles   = $csv->titles;
		$is_arr   = cdhl_array_validate( $csv_rows );

		if ( $is_arr ) {
			$return = array(
				'titles'   => $titles,
				'csv_rows' => $csv_rows,
			);
		} else {
			$invalid_csv_msg = __( 'Invalid CSV content. Please upload a valid CSV file. There are pretty strict rules that CSV files must conform to for them to work. You can validate your CSV data using the below free online service:', 'cardealer-helper' ) . '<br><a href="http://csvlint.io" target="_blank">http://csvlint.io</a>';
			$return = new WP_Error( 'invalid_csv', $invalid_csv_msg );
		}
		return $return;
	}
}

if ( ! function_exists( 'cdhl_check_empty_array' ) ) {
	/**
	 * Check empty array
	 *
	 * @param array $arr_data .
	 */
	function cdhl_check_empty_array( $arr_data ) {
		foreach ( $arr_data as $key => $value ) {
			if ( is_array( $value ) ) {
				$arr_data[ $key ] = cdhl_check_empty_array( $arr_data[ $key ] );
			}

			if ( empty( $arr_data[ $key ] ) ) {
				unset( $arr_data[ $key ] );
			}
		}
		return $arr_data;
	}
}

if ( ! function_exists( 'cdhl_array_validate' ) ) {
	/**
	 * Array validate
	 *
	 * @param array $array_data .
	 */
	function cdhl_array_validate( $array_data ) {
		$array_valid = true;

		if ( ! empty( $array_data ) ) {
			foreach ( $array_data[0] as $key => $value ) {
				if ( is_int( $key ) ) {
					$array_valid = false;
					break;
				}
			}
		} else {
			$array_valid = false;
		}
		return $array_valid;
	}
}

if ( ! function_exists( 'cdhl_get_feature_and_option' ) ) {
	/**
	 * Get feature & option
	 */
	function cdhl_get_feature_and_option() {
		$attributs = array();
		$terms     = get_terms(
			array(
				'taxonomy'   => 'car_features_options',
				'hide_empty' => false,
			)
		);

		$taxonomy_name = get_taxonomy( 'car_features_options' );
		$slug          = $taxonomy_name->rewrite['slug'];
		$label         = $taxonomy_name->labels->menu_name;

		foreach ( $terms as $tdata ) {
			$attributs[ $slug ]['terms'][] = $tdata->slug;
			$attributs[ $slug ]['label']   = $label;
			$attributs[ $slug ]['slug']    = $slug;
		}
		return $attributs;
	}
}

if ( ! function_exists( 'cdhl_get_multiple_values' ) ) {
	/**
	 * Get multiple values
	 *
	 * @param string $value .
	 * @param string $row .
	 * @param string $csv .
	 */
	function cdhl_get_multiple_values( $value, $row, $csv ) {
		$return = '';
		if ( ! empty( $value ) ) {
			foreach ( $value as $title_value ) {

				$return .= cdhl_search_array_keys( $row, $title_value, $csv, true ) . ' ';
			}
			$return = rtrim( $return, ' ' );
		}
		return $return;
	}
}

if ( ! function_exists( 'cdhl_search_array_keys' ) ) {
	/**
	 * Search array keys
	 *
	 * @param array  $array .
	 * @param string $term .
	 * @param string $references .
	 * @param string $title_val .
	 */
	function cdhl_search_array_keys( $array, $term, $references, $title_val = '' ) {

		$count = array_count_values( $references );

		$return = '';
		if ( isset( $count[ $term ] ) && $count[ $term ] >= 2 ) {
			$keys = array_keys( $references, $term, true );
			foreach ( $keys as $key ) {
				if ( strpos( $key, '|' ) !== false ) {
					$paths = explode( '|', $key );
					$items = $array;
					foreach ( $paths as $ndx ) {
						if ( isset( $items[ $ndx ] ) ) {
							$items = $items[ $ndx ];
						}
					}

					if ( ! is_array( $items ) ) {
						$return .= $items . ',';
					}
				} else {
					$return .= ( isset( $array[ $key ] ) && ! empty( $array[ $key ] ) ? $array[ $key ] : '' ) . ',';
				}
			}
		} else {
			$key = array_search( $term, $references, true );
			if ( strpos( $key, '|' ) !== false ) {
				$paths = explode( '|', $key );
				$items = $array;
				foreach ( $paths as $ndx ) {
					$items = $items[ $ndx ];
				}

				$return .= $items;
			} else {
				if ( ! empty( $title_val ) ) {
					if ( strpos( $term, '|' ) !== false ) {
						$paths = explode( '|', $term );
						$items = $array;

						foreach ( $paths as $ndx ) {
							$items = $items[ $ndx ];
						}

						$value = ( isset( $items ) && ! empty( $items ) ? $items : '' );
					} else {
						$value = ( isset( $array[ $term ] ) && ! empty( $array[ $term ] ) ? $array[ $term ] : '' );
					}

					$return .= $value;
				} else {
					$return .= ( isset( $array[ array_search( $term, $references, true ) ] ) && ! empty( $array[ array_search( $term, $references, true ) ] ) ? $array[ array_search( $term, $references, true ) ] : '' );
				}
			}
		}
		return $return;
	}
}

if ( ! function_exists( 'cdhl_get_car_meta' ) ) {
	/**
	 * Get car meta
	 *
	 * @param array  $cars_attributes .
	 * @param string $cdhl_cars_attributes_safe .
	 * @param string $row .
	 * @param string $csv .
	 */
	function cdhl_get_car_meta( &$cars_attributes, &$cdhl_cars_attributes_safe, $row, $csv ) {
		global $cars, $car_dealer_options;
		$post_meta = array();

		/* Default Latitude & Longitude */
		$default_latitude  = ( isset( $car_dealer_options['default_value_lat'] ) && ! empty( $car_dealer_options['default_value_lat'] ) ? $car_dealer_options['default_value_lat'] : '' );
		$default_longitude = ( isset( $car_dealer_options['default_value_long'] ) && ! empty( $car_dealer_options['default_value_long'] ) ? $car_dealer_options['default_value_long'] : '' );

		foreach ( $cars_attributes as $key => $option ) {
			if ( isset( $option['multiple'] ) ) {
				$key   = ( isset( $option['slug'] ) && ! empty( $option['slug'] ) ? $option['slug'] : str_replace( ' ', '_', strtolower( $key ) ) );
				$value = cdhl_search_array_keys( $row, $key, $csv );
			} else {
				$value = cdhl_search_array_keys( $row, $key, $csv );
				if ( empty( $value ) ) {
					$value = esc_html__( 'None', 'cardealer-helper' );
				}

				$terms = ( isset( $cdhl_cars_attributes_safe[ $key ]['terms'] ) && ! empty( $cdhl_cars_attributes_safe[ $key ]['terms'] ) ? $cdhl_cars_attributes_safe[ $key ]['terms'] : array() );
				if ( is_array( $terms ) && ! in_array( $value, $terms, true ) && ! empty( $value ) && isset( $option['compare_value'] ) && '=' === $option['compare_value'] ) {
					$slug_decode = slug_decode( $value );
					$cdhl_cars_attributes_safe[ $key ]['terms'][ $slug_decode ] = $value;
				}
			}

			if ( ! empty( $value ) && 'n-a' !== $value ) {
				$post_meta[ $key ] = $value;
				if ( esc_html__( 'None', 'cardealer-helper' ) !== (string) $value ) {
					$slug_decode = slug_decode( $value );
				}
			}
		}

		$car_images = cdhl_import_car_images( $row, $csv );     // get single csv record img array.
		if ( isset( $car_images ) && ! empty( $car_images ) ) {
			$post_meta['car_images'] = $car_images;
		}

		$pdf_file = cdhl_import_pdf_file( $row, $csv );
		if ( isset( $pdf_file ) && ! empty( $pdf_file ) ) {
			$post_meta['pdf_file'] = $pdf_file;
		}

		$post_meta['sale_price']               = cdhl_search_array_keys( $row, 'sale_price', $csv );
		$post_meta['regular_price']            = cdhl_search_array_keys( $row, 'regular_price', $csv );
		$post_meta['tax_label']                = cdhl_search_array_keys( $row, 'tax_label', $csv );
		$post_meta['car_status']               = cdhl_search_array_keys( $row, 'car_status', $csv );
		$post_meta['city_mpg']                 = cdhl_search_array_keys( $row, 'city_mpg', $csv );
		$post_meta['highway_mpg']              = cdhl_search_array_keys( $row, 'highway_mpg', $csv );
		$post_meta['technical_specifications'] = cdhl_search_array_keys( $row, 'technical_specifications', $csv );
		$post_meta['general_information']      = cdhl_search_array_keys( $row, 'general_information', $csv );
		$post_meta['post_excerpt']             = cdhl_search_array_keys( $row, 'excerpt', $csv );

		// Features & Options.
		$values               = cdhl_search_array_keys( $row, 'features_options', $csv );
		$features_and_options = array();
		$dynamicsep           = '';
		if ( ! empty( $values ) ) {
			if ( empty( $dynamicsep ) ) {
				if ( strstr( $values, ',' ) ) {
					$dynamicsep = ',';
				} elseif ( strstr( $values, '<br>' ) ) {
					$dynamicsep = '<br>';
				} elseif ( strstr( $values, '|' ) ) {
					$dynamicsep = '|';
				} elseif ( strstr( $values, ';' ) ) {
					$dynamicsep = ';';
				}
			}

			if ( isset( $dynamicsep ) && ! empty( $dynamicsep ) ) {
				$values = explode( $dynamicsep, $values );

				foreach ( $values as $val ) {
					$features_and_options[] = $val;
				}
			} else {
				$features_and_options[] = $values;
			}
		}
		$features_and_options = array_map( 'trim', $features_and_options );

		if ( ! empty( $features_and_options ) ) {
			$options = @$cdhl_cars_attributes_safe['features-options']['terms'];

			foreach ( $features_and_options as $option ) {
				$option = trim( $option );
				$option = preg_replace( '/\x{EF}\x{BF}\x{BD}/u', '', @iconv( mb_detect_encoding( $option ), 'UTF-8', $option ) );
				// features-options.
				if ( is_array( $options ) && ! in_array( $option, $options, true ) ) {
					$cdhl_cars_attributes_safe['features-options']['terms'][] = $option;
				}
			}
			$post_meta['features-options'] = $features_and_options;

		}
		// map location.
		$vehicle_location = '';
		$vehicle_location = cdhl_search_array_keys( $row, 'vehicle_location', $csv );
		$get_lat_lnt      = cdhl_getLatLnt( $vehicle_location );
		$latitude         = $get_lat_lnt['lat'];
		$longitude        = $get_lat_lnt['lng'];

		// map location.
		$location = array(
			'vehicle_location' => $vehicle_location,
			'latitude'         => $latitude,
			'longitude'        => $longitude,
			'zoom'             => '10',
		);
		if ( '0' !== (string) $get_lat_lnt['addr_found'] ) {
			$post_meta['location_map'] = $location;
		}
		$post_meta['video_link'] = cdhl_search_array_keys( $row, 'video_link', $csv );
		return $post_meta;
	}
}

if ( ! function_exists( 'cdhl_import_pdf_file' ) ) {
	/**
	 * Import pdf file
	 *
	 * @param string $row .
	 * @param string $csv .
	 */
	function cdhl_import_pdf_file( $row, $csv ) {
		$values     = cdhl_search_array_keys( $row, 'pdf_file', $csv );
		$pdf_file   = array();
		$dynamicsep = '';

		if ( ! empty( $values ) ) {
			if ( empty( $dynamicsep ) ) {
				if ( strstr( $values, ',' ) ) {
					$dynamicsep = ',';
				} elseif ( strstr( $values, '<br>' ) ) {
					$dynamicsep = '<br>';
				} elseif ( strstr( $values, '|' ) ) {
					$dynamicsep = '|';
				} elseif ( strstr( $values, ';' ) ) {
					$dynamicsep = ';';
				}
			}

			if ( isset( $dynamicsep ) && ! empty( $dynamicsep ) ) {
				$values = explode( $dynamicsep, $values );

				foreach ( $values as $val ) {
					if ( ! empty( $val ) ) {
						$val = cdhl_auto_add_http( trim( urldecode( $val ) ) );

						if ( 'http://Array' !== $val && filter_var( $val, FILTER_VALIDATE_URL ) !== false ) {
							$upload_image = cdhl_get_upload_pdf_file( $val );
						}
					}
				}
			} else {
				$values = cdhl_auto_add_http( trim( $values ) );

				if ( 'http://Array' !== $values && filter_var( $values, FILTER_VALIDATE_URL ) !== false ) {
					$upload_image = cdhl_get_upload_pdf_file( $values );
				}
			}
		}
		return ( ! empty( $upload_image ) ? $upload_image : '' );
	}
}

if ( ! function_exists( 'cdhl_auto_add_http' ) ) {
	/**
	 * Auto add http
	 *
	 * @param string $url .
	 */
	function cdhl_auto_add_http( $url ) {
		if ( ! preg_match( '~^(?:f|ht)tps?://~i', $url ) ) {
			$url = 'http://' . $url;
		}
		return $url;
	}
}

if ( ! function_exists( 'cdhl_get_upload_pdf_file' ) ) {
	/**
	 * Get upload pdf file
	 *
	 * @param string $file_url .
	 */
	function cdhl_get_upload_pdf_file( $file_url ) {
		$file     = str_replace( '"', '', $file_url );
		$file_use = str_replace( '"', '', $file_url );
		$pdf_args = array(
			'timeout'     => 600,
			'redirection' => 5,
			'headers'     => array( 'content-type' ),
			'cookies'     => null,
			'compress'    => true,
			'decompress'  => false,
			'sslverify'   => true,
			'stream'      => false,
			'filename'    => null,
			'body'        => null,
			'blocking'    => true,
		);
		$get      = wp_remote_get( $file, $pdf_args );
		if ( ! is_wp_error( $get ) && 200 === (int) $get['response']['code'] ) {
			// Access protected property of obj.
			$get_data_array = Closure::bind(
				function( $prop ) {
					return $this->$prop;
				},
				$get['headers'],
				$get['headers']
			);

			$type = $get_data_array( 'data' )['content-type'];

			$allowed_images = array( 'application/pdf' );
			$extension      = pathinfo( $file, PATHINFO_EXTENSION );
			if ( empty( $type ) ) {
				if ( 'pdf' === (string) $extension ) {
					$type = 'application/pdf';
				}
			}
			if ( ! $type && in_array( $type, $allowed_images, true ) ) {
				return false;
			}
			if ( empty( $extension ) ) {
				$content_type = $type;
				if ( strstr( $content_type, 'application/pdf' ) ) {
					$file_use = $file . '.pdf';
					$type     = 'application/pdf';
				}
			}
			$mirror     = wp_upload_bits( basename( $file_use ), '', $get['body'] );
			$attachment = array(
				'post_title'     => basename( $file ),
				'post_mime_type' => $type,
			);

			if ( isset( $mirror ) && ! empty( $mirror ) ) {
				$attach_id = wp_insert_attachment( $attachment, $mirror['file'] );
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				$attach_data = wp_generate_attachment_metadata( $attach_id, $mirror['file'] );
				wp_update_attachment_metadata( $attach_id, $attach_data );
			} else {
				$attach_id = '';
			}
			return $attach_id;
		} else {
			return '';
		}
	}
}

if ( ! function_exists( 'cdhl_import_car_images' ) ) {
	/**
	 * Import car images
	 *
	 * @param string $row .
	 * @param string $csv .
	 */
	function cdhl_import_car_images( $row, $csv ) {
		if ( isset( $_SESSION['cdhl_server_error'] ) ) {
			return; }
		global $cars, $car_dealer_options;
		$values = cdhl_search_array_keys( $row, 'car_images', $csv );

		$car_images = array();
		$dynamicsep = '';

		if ( ! empty( $values ) ) {
			if ( empty( $dynamicsep ) ) {
				if ( strstr( $values, ',' ) ) {
					$dynamicsep = ',';
				} elseif ( strstr( $values, '<br>' ) ) {
					$dynamicsep = '<br>';
				} elseif ( strstr( $values, '|' ) ) {
					$dynamicsep = '|';
				} elseif ( strstr( $values, ';' ) ) {
					$dynamicsep = ';';
				}
			}

			if ( isset( $dynamicsep ) && ! empty( $dynamicsep ) ) {
				$values = explode( $dynamicsep, $values );

				foreach ( $values as $val ) {
					if ( isset( $_SESSION['cdhl_server_error'] ) ) {
						break; }
					if ( ! empty( $val ) ) {
						$val = cdhl_auto_add_http( trim( urldecode( $val ) ) );
						if ( 'http://Array' !== $val && filter_var( $val, FILTER_VALIDATE_URL ) !== false ) {
							$upload_image = cdhl_get_upload_image( $val );
							if ( ! empty( $upload_image ) ) {
								$car_images[] = $upload_image;
							}
						}
					}
				}
			} else {
				if ( isset( $_SESSION['cdhl_server_error'] ) ) {
					return; }
				$values = cdhl_auto_add_http( trim( $values ) );

				if ( 'http://Array' !== $values && filter_var( $values, FILTER_VALIDATE_URL ) !== false ) {
					$upload_image = cdhl_get_upload_image( $values );
					if ( ! empty( $upload_image ) ) {
						$car_images[] = $upload_image;
					}
				}
			}
		}
		return ( ! empty( $car_images ) ? $car_images : '' );
	}
}

if ( ! function_exists( 'cdhl_get_upload_image' ) ) {
	/**
	 * Get upload image
	 *
	 * @param string $image_url .
	 */
	function cdhl_get_upload_image( $image_url ) {
		if ( ! isset( $_SESSION ) ) {
			session_start();
		}
		if ( isset( $_SESSION['cdhl_server_error'] ) ) {
			return; }
		$image     = str_replace( '"', '', $image_url );
		$image_use = str_replace( '"', '', $image_url );
		$img_args  = array(
			'timeout'     => 50,
			'redirection' => 0,
			'httpversion' => '1.1',
			'sslverify'   => false,
			'stream'      => false,
		);

		try {
			$start_time = time(); // start to mesure time.
			// put your long-time loop condition here.
			if ( ! defined( 'CDHL_START_IMP_TIME' ) ) {
				define( 'CDHL_START_IMP_TIME', $start_time );
			}
			$start_time  = CDHL_START_IMP_TIME;
			$server_time = cdhl_time_sublimit();
			$time_spent  = time() - $start_time;

			if ( $time_spent >= $server_time ) {
				throw new Exception( 'Execution time exceeded' );
			}
			$get = wp_remote_get( $image, $img_args );
		} catch ( Exception $e ) {
			// catch exception here.
			$_SESSION['cdhl_server_error'] = 1;
			return;
		}

		if ( ! is_wp_error( $get ) && 200 === (int) $get['response']['code'] ) {
			$type           = wp_remote_retrieve_header( $get, 'content-type' );
			$allowed_images = array( 'image/jpg', 'image/jpeg', 'image/png', 'image/gif' );
			$extension      = pathinfo( $image, PATHINFO_EXTENSION );

			if ( empty( $type ) ) {
				if ( 'jpg' === (string) $extension || 'jpeg' === (string) $extension ) {
					$type = 'image/jpg';
				} elseif ( 'png' === (string) $extension ) {
					$type = 'image/png';
				} elseif ( 'gif' === (string) $extension ) {
					$type = 'image/gif';
				}
			}

			if ( ! $type && in_array( $type, $allowed_images, true ) ) {
				return false;
			}

			if ( empty( $extension ) ) {
				$content_type = $type;
				if ( strstr( $content_type, 'image/jpg' ) || strstr( $content_type, 'image/jpeg' ) ) {
					$image_use = $image . '.jpg';
					$type      = 'image/jpg';
				} elseif ( strstr( $content_type, 'image/png' ) ) {
					$image_use = $image . '.png';
					$type      = 'image/png';
				} elseif ( strstr( $content_type, 'image/gif' ) ) {
					$image_use = $image . '.gif';
					$type      = 'image/gif';
				}
			}

			$mirror     = wp_upload_bits( basename( $image_use ), '', wp_remote_retrieve_body( $get ) );
			$attachment = array(
				'post_title'     => basename( $image ),
				'post_mime_type' => $type,
			);

			if ( isset( $mirror ) && ! empty( $mirror ) ) {
				$attach_id = wp_insert_attachment( $attachment, $mirror['file'] );
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				$attach_data = wp_generate_attachment_metadata( $attach_id, $mirror['file'] );
				wp_update_attachment_metadata( $attach_id, $attach_data );
			} else {
				$attach_id = '';
			}
			return $attach_id;
		} else {
			return '';
		}
	}
}

if ( ! function_exists( 'cdhl_time_sublimit' ) ) {
	/**
	 * Capture server execution time.
	 */
	function cdhl_time_sublimit() {
		$limit     = ini_get( 'max_execution_time' ); // changes even when you set_time_limit().
		$sub_limit = round( ( $limit * 40 ) / 100 );
		if ( 0 === (int) $sub_limit ) {
			$sub_limit = INF;
		}
		return ( $sub_limit - 20 );
	}
}

if ( ! function_exists( 'slug_decode' ) ) {
	/**
	 * Slug decode
	 *
	 * @param string $text .
	 */
	function slug_decode( $text ) {
		if ( ! empty( $text ) && is_string( $text ) ) {
			$char_map = array(
				'©' => 'c',
				'®' => 'r',
			);
			$text     = str_replace( array_keys( $char_map ), $char_map, $text );
			$text     = preg_replace( '~[^\\pL\d]+~u', '-', $text );
			$text     = trim( $text, '-' );
			$text     = iconv( 'UTF-8', 'ASCII//TRANSLIT', utf8_encode( $text ) );
			$text     = strtolower( $text );
			$text     = preg_replace( '~[^-\w]+~', '', $text );

			if ( empty( $text ) ) {
				return 'n-a';
			}
		}
		return $text;
	}
}

if ( ! function_exists( 'cdhl_getLatLnt' ) ) {
	/**
	 * Get LatLnt
	 *
	 * @param string $address .
	 */
	function cdhl_getLatLnt( $address ) {
		global $car_dealer_options;
		$gapi             = isset( $car_dealer_options['google_maps_api'] ) ? $car_dealer_options['google_maps_api'] : '';
		$vehicle_location = urlencode( $address );
		$url              = esc_url( 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode( $vehicle_location ) . '&sensor=false&key=' . $gapi );

		$api_args = array( 'timeout' => 600 );
		$response = wp_remote_get( $url, $api_args );

		if ( ! is_wp_error( $response ) ) {
			$results = json_decode( $response['body'], true );
			if ( isset( $response['body'] ) && isset( $results['results'][0] ) ) {
				$lat  = $results['results'][0]['geometry']['location']['lat'];
				$long = $results['results'][0]['geometry']['location']['lng'];
			} else {
				$lat  = '';
				$long = '';
			}
		} else {
			$lat  = '';
			$long = '';
		}

		$data = array(
			'lat' => $lat,
			'lng' => $long,
		);

		if ( empty( $lat ) || empty( $long ) ) {
			$data['addr_found'] = '0';
		} else {
			$data['addr_found'] = '1';
		}
		return $data;
	}
}
