<?php
/**
 * Sub menu class
 *
 * @author Potenza
 *
 * @package Cardealer Vin Import
 */

/**
 * Class CDVI.
 */
class CDVI {
	/**
	 * Autoload method
	 *
	 * @param string $body .
	 */
	public function cdvi_insert_vehicle_data( $body ) {
		$msg        = '';
		$post_title = '';
		$vin_import = isset( $_POST['vin_import'] ) && ! empty( $_POST['vin_import'] ) ? wp_unslash( $_POST['vin_import'] ) : array();

		if ( isset( $vin_import['cars_title'] ) && ! empty( $vin_import['cars_title'] ) ) {
			foreach ( $vin_import['cars_title'] as $cars_title ) {
				$cars_title  = $this->cdvi_get_car_vin_value( $cars_title, $body );
				$post_title .= sanitize_text_field( $cars_title ) . ' ';
			}
		}

		$post_title = trim( $post_title );

		// insert post, get id.
		$insert_info = array(
			'post_type'   => 'cars',
			'post_title'  => $post_title,
			'post_status' => 'publish',
		);

		$insert_id = wp_insert_post( $insert_info );

		if ( $insert_id ) {

			$cdvi_cars_attributes = cardealer_get_all_taxonomy_with_terms();
			// add.
			foreach ( $cdvi_cars_attributes as $key => $attributes ) {

				$value         = '';
				$attr_safe_key = ( isset( $attributes['slug'] ) && ! empty( $attributes['slug'] ) ? $attributes['slug'] : str_replace( ' ', '_', strtolower( $key ) ) ); // str_replace(" ", "_", strtolower($key));.
				if ( isset( $vin_import[ $attr_safe_key ] ) && ! empty( $vin_import[ $attr_safe_key ] ) && is_array( $vin_import[ $attr_safe_key ] ) ) {
					foreach ( $vin_import[ $attr_safe_key ] as $key => $vin_import_value ) {
						$value .= $this->cdvi_get_car_vin_value( $vin_import_value, $body ) . ' ';
					}
				} elseif ( isset( $vin_import[ $attr_safe_key ] ) && ! empty( $vin_import[ $attr_safe_key ] ) ) {
					$value = $this->cdvi_get_car_vin_value( $vin_import[ $attr_safe_key ], $body );
				}

				if ( 'year' === $key ) {
					$year = (string) $value;
					if ( isset( $year ) && ! empty( $year ) && 'None' !== $year ) {
						wp_set_object_terms( $insert_id, $year, 'car_year', false );
					}
				}

				if ( 'make' === $key ) {
					$make = $value;
					if ( ! empty( $value ) && 'None' !== $value ) {
						wp_set_object_terms( $insert_id, $make, 'car_make', false );
					}
				}

				if ( 'model' === $key ) {
					$model = $value;
					if ( ! empty( $value ) && 'None' !== $value ) {
						wp_set_object_terms( $insert_id, $model, 'car_model', false );
					}
				}

				if ( 'body-style' === $key ) {
					if ( ! empty( $value ) && 'None' !== $value ) {
						$body_style = $value;
						wp_set_object_terms( $insert_id, $body_style, 'car_body_style', false );
					}
				}

				if ( 'mileage' === $key ) {
					if ( ! empty( $value ) && 'None' !== $value ) {
						$mileage = (string) $value;
						wp_set_object_terms( $insert_id, $mileage, 'car_mileage', false );
					}
				}

				if ( 'fuel-economy' === $key ) {
					if ( ! empty( $value ) && 'None' !== $value ) {
						$fuel_economy = $value;
						wp_set_object_terms( $insert_id, $fuel_economy, 'car_fuel_economy', false );
					}
				}

				if ( 'transmission' === $key ) {
					$transmission = $value;
					if ( ! empty( $value ) && 'None' !== $value ) {
						wp_set_object_terms( $insert_id, $transmission, 'car_transmission', false );
					}
				}

				if ( 'condition' === $key ) {
					$condition = $value;
					if ( ! empty( $value ) && 'None' !== $value ) {
						if ( preg_match( '(new|New|N|n)', $condition ) === 1 ) {
							wp_set_object_terms( $insert_id, 'New', 'car_condition', false );
						} elseif ( preg_match( '(used|Used|U|u)', $condition ) === 1 ) {
							wp_set_object_terms( $insert_id, 'Used', 'car_condition', false );
						} else {
							wp_set_object_terms( $insert_id, 'Certified', 'car_condition', false );
						}
					}
				}

				if ( 'drivetrain' === $key ) {
					$drivetrain = $value;
					if ( ! empty( $value ) && 'None' !== $value ) {
						wp_set_object_terms( $insert_id, $drivetrain, 'car_drivetrain', false );
					}
				}

				if ( 'engine' === $key ) {
					$engine = (string) $value;
					if ( ! empty( $engine ) && 'None' !== $engine ) {
						wp_set_object_terms( $insert_id, $engine, 'car_engine', false );
					}
				}

				if ( 'exterior-color' === $key ) {
					$exterior_color = (string) $value;
					if ( ! empty( $value ) && 'None' !== $value ) {
						wp_set_object_terms( $insert_id, $exterior_color, 'car_exterior_color', false );
					}
				}

				if ( 'interior-color' === $key ) {
					$interior_color = (string) $value;
					if ( ! empty( $interior_color ) && 'None' !== $interior_color ) {
						wp_set_object_terms( $insert_id, $interior_color, 'car_interior_color', false );
					}
				}

				if ( 'stock-number' === $key ) {
					$stock_number = (string) $value;
					if ( ! empty( $value ) && 'None' !== $value ) {
						wp_set_object_terms( $insert_id, $stock_number, 'car_stock_number', false );
					}
				}

				if ( 'vin-number' === $key ) {
					$vin_number = (string) $value;
					if ( ! empty( $value ) && 'None' !== $value ) {
						wp_set_object_terms( $insert_id, $vin_number, 'car_vin_number' );
					}
				}
			}

			if ( isset( $vin_import['features_options'] ) && ! empty( $vin_import['features_options'] ) ) {
				if ( isset( $vin_import['features_options'] ) && ! empty( $vin_import['features_options'] ) && is_array( $vin_import['features_options'] ) ) {
					$car_features_options = $vin_import['features_options'];
					foreach ( $car_features_options as $option ) {
						$value = $this->cdvi_get_car_vin_value( $option, $body );
						wp_set_object_terms( $insert_id, $value, 'car_features_options', true );
					}
				} elseif ( isset( $vin_import['features_options'] ) && ! empty( $vin_import['features_options'] ) ) {
					$value = $this->cdvi_get_car_vin_value( $vin_import['features_options'], $body );
					wp_set_object_terms( $insert_id, $value, 'car_features_options', true );
				}
			}

			$regular_price = ( isset( $vin_import['regular_price'] ) && ! empty( $vin_import['regular_price'] ) ? $this->cdvi_get_car_vin_value( $vin_import['regular_price'], $body ) : '' );
			if ( isset( $regular_price ) && ! empty( $regular_price ) ) {
				update_field( 'regular_price', $regular_price, $insert_id );
				$final_price = $regular_price;
			}

			$sale_price = ( isset( $vin_import['sale_price'] ) && ! empty( $vin_import['sale_price'] ) ? $this->cdvi_get_car_vin_value( $vin_import['sale_price'], $body ) : '' );
			if ( isset( $sale_price ) && ! empty( $sale_price ) ) {
				update_field( 'sale_price', $sale_price, $insert_id );
				$final_price = $sale_price;
			}
			// set final price.
			if ( isset( $final_price ) ) {
				update_post_meta( $insert_id, 'final_price', $final_price );
			}

			$tax_label = ( isset( $vin_import['tax_label'] ) && ! empty( $vin_import['tax_label'] ) ? $this->cdvi_get_car_vin_value( $vin_import['tax_label'], $body ) : '' );
			if ( isset( $tax_label ) && ! empty( $tax_label ) ) {
				update_field( 'tax_label', $tax_label, $insert_id );
			}

			$post_excerpt = ( isset( $vin_import['excerpt'] ) && ! empty( $vin_import['excerpt'] ) ? $this->cdvi_get_car_vin_value( $vin_import['excerpt'], $body ) : '' );
			if ( isset( $post_excerpt ) && ! empty( $post_excerpt ) ) {
				$my_post = array(
					'ID'           => $insert_id,
					'post_excerpt' => $post_excerpt,
				);
				wp_update_post( $my_post );
			}

			$city_mpg = ( isset( $vin_import['city_mpg'] ) && ! empty( $vin_import['city_mpg'] ) ? $this->cdvi_get_car_vin_value( $vin_import['city_mpg'], $body ) : '' );
			if ( isset( $city_mpg ) && ! empty( $city_mpg ) ) {
				update_field( 'city_mpg', $city_mpg, $insert_id );
			}

			$highway_mpg = ( isset( $vin_import['highway_mpg'] ) && ! empty( $vin_import['highway_mpg'] ) ? $this->cdvi_get_car_vin_value( $vin_import['highway_mpg'], $body ) : '' );
			if ( isset( $highway_mpg ) && ! empty( $highway_mpg ) ) {
				update_field( 'highway_mpg', $highway_mpg, $insert_id );
			}

			$video_link = ( isset( $vin_import['video_link'] ) && ! empty( $vin_import['video_link'] ) ? $this->cdvi_get_car_vin_value( $vin_import['video_link'], $body ) : '' );
			if ( isset( $video_link ) && ! empty( $video_link ) ) {
				update_field( 'video_link', $video_link, $insert_id );
			}

			$technicalspecification = '';
			if ( isset( $vin_import['technical_specifications'] ) && ! empty( $vin_import['technical_specifications'] ) ) {
				foreach ( $vin_import['technical_specifications'] as $technical_specification ) {
					$technical_specification = $this->cdvi_get_car_vin_value( $technical_specification, $body );
					$technicalspecification .= sanitize_text_field( $technical_specification ) . ' ';
				}
			}
			$technicalspecification = trim( $technicalspecification );
			if ( isset( $technicalspecification ) && ! empty( $technicalspecification ) ) {
				update_field( 'technical_specifications', $technicalspecification, $insert_id );
			}

			$generalinformation = '';

			if ( isset( $vin_import['general_information'] ) && ! empty( $vin_import['general_information'] ) ) {
				foreach ( $vin_import['general_information'] as $general_information ) {
					$general_information = $this->cdvi_get_car_vin_value( $general_information, $body );
					$generalinformation .= sanitize_text_field( $general_information ) . ' ';
				}
			}
			$generalinformation = trim( $generalinformation );
			if ( isset( $generalinformation ) && ! empty( $generalinformation ) ) {
				update_field( 'general_information', $generalinformation, $insert_id );
			}

			// PDF File.
			$pdf_file = ( isset( $vin_import['pdf_file'] ) && ! empty( $vin_import['pdf_file'] ) ? $vin_import['pdf_file'] : '' );
			if ( isset( $pdf_file ) && ! empty( $pdf_file ) ) {
				$val = $this->cdvi_get_car_vin_value( $pdf_file, $body );
				if ( filter_var( $val, FILTER_VALIDATE_URL ) ) {
					$pdf_file = get_upload_pdf_file( $val );
					update_field( 'pdf_file', $pdf_file, $insert_id );
				}
			}

			$post_content = '';
			if ( isset( $vin_import['vehicle_overview'] ) && ! empty( $vin_import['vehicle_overview'] ) ) {
				foreach ( $vin_import['vehicle_overview'] as $overview ) {
					$overview      = $this->cdvi_get_car_vin_value( $overview, $body );
					$post_content .= sanitize_text_field( $overview ) . ' ';
				}
			}
			$post_content = trim( $post_content );
			if ( '' !== $post_content ) {
				update_field( 'vehicle_overview', $post_content, $insert_id );
			}
			$location  = ( isset( $vin_import['location_map'] ) && ! empty( $vin_import['location_map'] ) ? $this->cdvi_get_car_vin_value( $vin_import['location_map'], $body ) : '' );
			$latitude  = ( isset( $vin_import['latitude'] ) && ! empty( $vin_import['latitude'] ) ? $this->cdvi_get_car_vin_value( $vin_import['latitude'], $body ) : '' );
			$longitude = ( isset( $vin_import['longitude'] ) && ! empty( $vin_import['longitude'] ) ? $this->cdvi_get_car_vin_value( $vin_import['longitude'], $body ) : '' );
			if ( '' !== $location ) {
				$valuearr     = array(
					'address' => $location,
					'lng'     => $latitude,
					'lat'     => $longitude,
				);
				$location_map = $valuearr;
			}

			if ( isset( $location_map ) && ! empty( $location_map ) ) {
				update_field( 'vehicle_location', $location_map, $insert_id );
			}

			if ( isset( $vin_import['car_images'] ) && ! empty( $vin_import['car_images'] ) ) {

				foreach ( $vin_import['car_images'] as $galleryimages ) {

					$gallery_image_url = $this->cdvi_get_car_vin_value( $galleryimages, $body );
					if ( filter_var( $gallery_image_url, FILTER_VALIDATE_URL ) ) {
						$car_images[] = $this->cdvi_image_upload( $gallery_image_url );
					}
				}
			}
			if ( isset( $car_images ) && ! empty( $car_images ) ) {
				update_field( 'car_images', $car_images, $insert_id );
			}
			$msg  = esc_html__( 'Congratulations, you successfully imported this vehicle details: ', 'cdvi-addon' );
			$msg .= "<a href='" . get_permalink( $insert_id ) . "'>" . ( ! empty( $post_title ) ? $post_title : esc_html__( 'Untitled', 'cdvi-addon' ) ) . '</a>';
		} else {
			$msg = esc_html__( 'Error importing your vehicle', 'cdvi-addon' );
		}
		return $msg;
	}

	/**
	 * Get vin data
	 *
	 * @param string $vin .
	 */
	public function cdvi_get_vin_data( $vin ) {
		global $car_dealer_options;

		$cars_api_key = ( isset( $car_dealer_options['edmunds_api_key'] ) && ! empty( $car_dealer_options['edmunds_api_key'] ) ? $car_dealer_options['edmunds_api_key'] : '' );
		if ( ! empty( $cars_api_key ) ) {
			$request_url = add_query_arg(
				array(
					'fmt'     => 'json',
					'api_key' => $cars_api_key,
				),
				'https://api.edmunds.com/api/vehicle/v2/vins/' . $vin
			);
			$response    = wp_remote_get( $request_url );
			$style_media = array();
			if ( ! is_wp_error( $response ) ) {
				$body_encode = json_decode( $response['body'] );
				if ( isset( $body_encode->years[0]->styles ) && ! empty( $body_encode->years[0]->styles ) ) {
					foreach ( $body_encode->years[0]->styles as $year_key => $year_details ) {
						$style_id        = $year_details->id;
						$style_equipment = $this->cdvi_get_vin_feature_options( $style_id );
						$body_encode->years[0]->styles[ $year_key ]->{'equipment'} = '';
						if ( isset( $style_equipment->equipment ) && ! empty( $style_equipment->equipment ) ) {
							$body_encode->years[0]->styles[ $year_key ]->{'equipment'} = $style_equipment->equipment;
						}
						$style_media = $this->cdvi_get_vin_media( $style_id );
						$body_encode = array(
							'body_encode' => $body_encode,
							'style_media' => $style_media,
						);

					}
				}
			} else {
				$body_encode = array();
			}
			return $body_encode;
		} else {
			header( 'Location: ' . admin_url( 'edit.php?post_type=cars&page=cars-vin-import' ) );
			die;
		}

	}
	/**
	 * Get vin feature options
	 *
	 * @param string $style_id .
	 */
	public function cdvi_get_vin_feature_options( $style_id ) {
		global $car_dealer_options;
		$body_encode  = array();
		$cars_api_key = ( isset( $car_dealer_options['edmunds_api_key'] ) && ! empty( $car_dealer_options['edmunds_api_key'] ) ? $car_dealer_options['edmunds_api_key'] : '' );
		$response     = wp_remote_get( 'https://api.edmunds.com/api/vehicle/v2/styles/' . $style_id . '/equipment?availability=standard&fmt=json&api_key=' . $cars_api_key );
		if ( ! is_wp_error( $response ) ) {
			$body_encode = $response['body'];
		}
		return json_decode( $body_encode );
	}
	/**
	 * Get vin media
	 *
	 * @param string $style_id .
	 */
	public function cdvi_get_vin_media( $style_id = 200467056 ) {
		global $car_dealer_options;

		$cars_api_key = ( isset( $car_dealer_options['edmunds_api_key'] ) && ! empty( $car_dealer_options['edmunds_api_key'] ) ? $car_dealer_options['edmunds_api_key'] : '' );

		$url         = 'https://api.edmunds.com/v1/api/vehiclephoto/service/findphotosbystyleid?styleId=' . $style_id . '&fmt=json&api_key=' . $cars_api_key;
		$response    = wp_remote_get( $url );
		$body_encode = $response['body'];
		$body        = json_decode( $body_encode );
		$car_images  = array();
		$carimages   = array();
		$image_data  = array();
		if ( isset( $body ) && ! empty( $body ) ) {
			foreach ( $body as $data ) {
				if ( isset( $data->shotTypeAbbreviation ) || isset( $data->photoSrcs ) ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
					$bodyencode[] = array(
						'shotTypeAbbreviation' => $data->shotTypeAbbreviation, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
						'photoSrcs'            => $data->photoSrcs, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
					);
				}
			}

			foreach ( $bodyencode as $bdata ) {
				foreach ( $bdata['photoSrcs'] as $photo_srcs ) {
					preg_match( '/1600.jpg/', $photo_srcs, $matches );
					if ( ! empty( $matches ) ) {
						$car_images[] = array(
							'shotTypeAbbreviation' => $bdata['shotTypeAbbreviation'],
							'car_image'            => 'http://media.ed.edmunds-media.com' . $photo_srcs,
						);
					}
				}
			}
			if ( ! empty( $car_images ) ) {
				foreach ( $car_images as $carimage ) {
					if ( 'FQ' === $carimage['shotTypeAbbreviation'] ) {
						$carimages[] = $carimage['car_image'];
					} else {
						$carimages_2[] = $carimage['car_image'];
					}
				}
			}
			$image_data['car_images'] = array_merge( $carimages, $carimages_2 );
		}
		return $image_data;
	}
	/**
	 * Object to array
	 *
	 * @param string $obj .
	 */
	public function cdvi_object_to_array_conversion( $obj ) {
		if ( is_object( $obj ) ) {
			$obj = (array) $obj;
		}
		if ( is_array( $obj ) ) {
			$new = array();
			foreach ( $obj as $key => $val ) {
				$new[ $key ] = $this->cdvi_object_to_array_conversion( $val );
			}
		} else {
			$new = $obj;
		}
		return $new;
	}

	/**
	 * Get all errors
	 */
	public function cdvi_all_vin_errors() {

		$vin = ( isset( $_GET['vin'] ) && ! empty( $_GET['vin'] ) ? sanitize_text_field( wp_unslash( $_GET['vin'] ) ) : '' );

		if ( ! empty( $vin ) && isset( $_GET['error'] ) ) {
			$body = $this->cdvi_get_vin_data( $vin );

			if ( isset( $body->errorType ) && ! empty( $body->errorType ) ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				?>
				<div class='notice notice-error cars-vin-error'>
					<p class='cars_text_error'>
						<?php echo esc_html( 'Error: ' . $body->message ); ?>
					</p>
				</div>
				<?php
			}
		}

	}

	/**
	 * Get car vin value
	 *
	 * @param string $location .
	 * @param string $body .
	 */
	public function cdvi_get_car_vin_value( $location, $body ) {

		$return_value = '';
		if ( strpos( $location, '|' ) !== false ) {
			$search_array = explode( '|', $location );
		} else {
			$search_array = array( $location );
		}

		if ( ! empty( $search_array ) ) {
			$return_value = $body;

			foreach ( $search_array as $value ) {

				if ( isset( $return_value[ $value ] ) && ! empty( $return_value[ $value ] ) ) {
					$return_value = $return_value[ $value ];
				} else {
					$return_value = '';
					break;
				}
			}
		}
		return $return_value;
	}
	/**
	 * Image upload
	 *
	 * @param string $image_url .
	 */
	public function cdvi_image_upload( $image_url ) {
		$image     = str_replace( '"', '', $image_url );
		$image_use = str_replace( '"', '', $image_url );
		$get       = wp_remote_get( $image );

		if ( ! is_wp_error( $get ) && 200 === $get['response']['code'] ) {
			$type = wp_remote_retrieve_header( $get, 'content-type' );

			$allowed_images = array( 'image/jpg', 'image/jpeg', 'image/png', 'image/gif' );
			$extension      = pathinfo( $image, PATHINFO_EXTENSION );

			if ( empty( $type ) ) {
				if ( 'jpg' === $extension || 'jpeg' === $extension ) {
					$type = 'image/jpg';
				} elseif ( 'png' === $extension ) {
					$type = 'image/png';
				} elseif ( 'gif' === $extension ) {
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

			$mirror = wp_upload_bits( basename( $image_use ), null, wp_remote_retrieve_body( $get ) );

			$attachment = array(
				'post_title'     => basename( $image ),
				'post_mime_type' => $type,
			);

			if ( isset( $mirror ) && ! empty( $mirror ) ) {
				$attach_id = wp_insert_attachment( $attachment, $mirror['file'] );

				require_once ABSPATH . 'wp-admin/includes/image.php';

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
	/**
	 * Vin atributte value
	 *
	 * @param string $vin_mapp .
	 * @param string $attr_safe_name .
	 * @param string $body .
	 * @param string $option .
	 */
	public function cdvi_vin_atributte_value( $vin_mapp, $attr_safe_name, $body, $option ) {

		$check_exists_value = true;
		$data_current_value = $body;
		$manage_data        = explode( '|', $vin_mapp );

		foreach ( $manage_data as $exp_value ) {

			if ( isset( $data_current_value[ $exp_value ] ) ) {
				$data_current_value = $data_current_value[ $exp_value ];
			} else {
				$check_exists_value = false;
				break;
			}
		}
		if ( $check_exists_value ) {
			$attr_safe_name = trim( $attr_safe_name );
			if ( 'car_images' === $attr_safe_name ) {
				echo "<li class='ui-state-default'> <img src='" . esc_url( $data_current_value ) . "' width='135' height='70'/> <input type='hidden' name='vin_import[" . esc_attr( $attr_safe_name ) . ']' . ( 0 === $option ? '[]' : '' ) . "' value='" . esc_attr( $vin_mapp ) . "' /><span class='rmv-row'>X</span></li>";
			} else {
				echo "<li class='ui-state-default'>" . esc_html( end( $manage_data ) . ': ' . $data_current_value ) . " <input type='hidden' name='vin_import[" . esc_attr( $attr_safe_name ) . ']' . ( 0 === $option ? '[]' : '' ) . "' value='" . esc_attr( $vin_mapp ) . "' /><span class='rmv-row'>X</span></li>";
			}
		}
	}
	/**
	 * Vin import item
	 *
	 * @param string $attr_safe_name .
	 * @param string $vin_import_mapping .
	 * @param string $body .
	 * @param string $option .
	 */
	public function cdvi_cars_vin_import_item( $attr_safe_name, $vin_import_mapping, $body, $option = 1 ) {

		if ( ! empty( $vin_import_mapping ) && isset( $vin_import_mapping[ $attr_safe_name ] ) && ! empty( $vin_import_mapping[ $attr_safe_name ] ) ) {
			$vin_mapp = $vin_import_mapping[ $attr_safe_name ];
			if ( is_array( $vin_mapp ) ) {
				foreach ( $vin_mapp as $single_vin_mapp ) {
					$this->cdvi_vin_atributte_value( $single_vin_mapp, $attr_safe_name, $body, $option );
				}
			} elseif ( strpos( $vin_mapp, '|' ) === false ) {
				echo "<li class='ui-state-default'>" . esc_html( $vin_mapp . ': ' . $body[ $vin_mapp ] ) . " <input type='hidden' name='vin_import[" . esc_attr( $attr_safe_name ) . ']' . ( 0 === $option ? '[]' : '' ) . "' value='" . esc_attr( $vin_mapp ) . "' /><span class='rmv-row'>X</span></li>";
			} else {
				$this->cdvi_vin_atributte_value( $vin_mapp, $attr_safe_name, $body, $option );
			}
		}
	}
}

