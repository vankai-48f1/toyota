<?php
/**
 * CDFS Cars Functions
 *
 * @author   PotenzaGlobalSolutions
 * @category Class
 * @package  CDFS/Classes
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Add car menu items.
if ( ! function_exists( 'cdfs_add_car_menu' ) ) {
	/**
	 * Add car menu
	 *
	 * @param string $items .
	 * @param string $args .
	 */
	function cdfs_add_car_menu( $items, $args ) {
		global $car_dealer_options;
		if ( ! isset( $car_dealer_options['cdfs-menu'] ) || 1 !== (int) $car_dealer_options['cdfs-menu'] || ! class_exists( 'CDFS' ) ) {
			return $items; }

		$menu_label = ! empty( $car_dealer_options['cdfs-menu-label'] ) ? $car_dealer_options['cdfs-menu-label'] : esc_html__( 'Add vehicle', 'cdfs-addon' );

		if ( 'primary-menu' !== $args->theme_location ) {
			return $items;
		}
		$url = home_url( 'add-car' );

		$pages = get_pages(
			array(
				'meta_key'   => '_wp_page_template',
				'meta_value' => 'templates/cardealer-front-submission.php',
			)
		);

		if ( ! empty( $pages ) && isset( $pages[0] ) ) {
			$dashboard_link = get_permalink( $pages[0]->ID );
		} else {
			$dashboard_link = cdfs_get_page_permalink( 'myuseraccount' );
		}
		if ( isset( $car_dealer_options['cdfs_user_activation'] ) && ( 'default' !== $car_dealer_options['cdfs_user_activation'] ) ) {
			$url = $dashboard_link;
		}
		if ( is_user_logged_in() ) {
			$url = $dashboard_link . 'add-car';
		}

		$menu_items          = '<li class="menu-item cdfs-add-vehicle"><a href="' . esc_url( $url ) . '" class="listing_add_cart heading-font button">';
			$menu_items     .= '<div>';
				$menu_items .= $menu_label;
			$menu_items     .= '</div>';
		$menu_items         .= '</a></li>';

		return $items . $menu_items;
	}
}
add_filter( 'wp_nav_menu_items', 'cdfs_add_car_menu', 20, 2 );



/*
 * Display add cars form
 */
if ( ! function_exists( 'cdfs_get_car_form_fields' ) ) {
	/**
	 * Assign fieldgroup key
	 *
	 * @param string $fieldgroup .
	 */
	function cdfs_get_car_form_fields( $fieldgroup = 'car_data' ) {
		if ( 'car_data' === $fieldgroup ) {
			$fields = acf_get_fields( 'group_588f1cea78c99' );
		} else {
			$fields = acf_get_fields( 'group_588f0eef75bc1' );
		}

		$form_fields            = array();
		$form_fields_additional = array();
		$taxonomies             = cdfs_get_cars_taxonomy();
		$additional_attrs       = get_option( 'cdhl_additional_attributes' );
		$additional_attrs_slugs = array_column( $additional_attrs, 'slug' );

		/**
		 * Filters the array of fields to put in required fields for add vehicle form in front submission.
		 *
		 * @since 1.0
		 * @param array         $required_fields    Array of fields to put in required fields in add car form(front side validation).
		 * @visible             true
		 */
		$required_fields = apply_filters( 'cdfs_form_required_fields', array( 'year', 'make', 'model', 'regular_price' ) );

		foreach ( $fields as $field ) {
			if ( in_array( strtolower( $field['type'] ), array( 'tab', 'file', 'google_map', 'message' ), true ) ) {
				continue;
			}

			$fieldname     = $field['name'];
			$require_class = ( in_array( $fieldname, $required_fields ) ) ? 'cdhl_validate' : '';
			$extra_classes = apply_filters( 'cdfs_additional_classes', array( $require_class ) );
			$extra_classes = ' ' . implode( ' ', $extra_classes );

			switch ( $fieldname ) {
				case 'condition':
				case 'car_status':
					// radio button.
					$options = array();
					if ( 'condition' === $fieldname ) {
						// Get all conditions from database.
						$conditions = get_terms(
							array(
								'taxonomy'   => 'car_condition',
								'hide_empty' => false,
							)
						);
						$options    = array();

						foreach ( $conditions as $condition ) {
							$options[ $condition->slug ] = $condition->name;
						}
					} else {
						$options = array(
							'unsold' => esc_html__( 'Unsold', 'cdfs-addon' ),
							'sold'   => esc_html__( 'Sold', 'cdfs-addon' ),
						);
					}
					if ( 'taxonomy' === $field['type'] && in_array( $field['taxonomy'], $additional_attrs_slugs, true ) ) {
						$form_fields_additional[] = array(
							'type'        => 'radio',
							'name'        => $fieldname,
							'class'       => 'radio' . $extra_classes,
							'placeholder' => $field['label'],
							'options'     => $options,
						);
					} else {
						$form_fields[] = array(
							'type'        => 'radio',
							'name'        => $fieldname,
							'class'       => 'radio' . $extra_classes,
							'placeholder' => $field['label'],
							'options'     => $options,
						);
					}

					break;
				case 'year':
					// number fields.
					if ( 'taxonomy' === $field['type'] && in_array( $field['taxonomy'], $additional_attrs_slugs, true ) ) {
						$form_fields_additional[] = array(
							'type'        => 'number',
							'name'        => $fieldname,
							'class'       => 'cdfs_len_limit cdfs-autofill' . $extra_classes,
							'placeholder' => $field['label'],
						);
					}else {
						$form_fields[] = array(
							'type'        => 'number',
							'name'        => $fieldname,
							'class'       => 'cdfs_len_limit cdfs-autofill' . $extra_classes,
							'placeholder' => $field['label'],
						);
					}
					break;
				case 'mileage':
				case 'fuel_economy':
				case 'stock_number':
				case 'city_mpg':
				case 'highway_mpg':
				case 'regular_price':
				case 'sale_price':
					// number fields.
					if ( 'taxonomy' === $field['type'] && in_array( $field['taxonomy'], $additional_attrs_slugs, true ) ) {
						$form_fields_additional[] = array(
							'type'        => 'number',
							'name'        => $fieldname,
							'class'       => $extra_classes,
							'placeholder' => $field['label'],
						);
					} else {
						$form_fields[] = array(
							'type'        => 'number',
							'name'        => $fieldname,
							'class'       => $extra_classes,
							'placeholder' => $field['label'],
						);
					}
					break;
				case 'video_link':
					// url fields.
					if ( 'taxonomy' === $field['type'] && in_array( $field['taxonomy'], $additional_attrs_slugs, true ) ) {
						$form_fields_additional[] = array(
							'type'        => 'url',
							'name'        => $fieldname,
							'class'       => 'url' . $extra_classes,
							'placeholder' => $field['label'],
						);
					} else {
						$form_fields[] = array(
							'type'        => 'url',
							'name'        => $fieldname,
							'class'       => 'url' . $extra_classes,
							'placeholder' => $field['label'],
						);
					}
					break;
				case 'vehicle_review_stamps':
				case 'features_and_options':
					// checkbox fields.
					if ( 'taxonomy' === $field['type'] && in_array( $field['taxonomy'], $additional_attrs_slugs, true ) ) {
						$form_fields_additional[] = array(
							'type'        => 'checkbox',
							'name'        => $fieldname,
							'class'       => 'cdfs-checkbox' . $extra_classes,
							'placeholder' => $field['label'],
						);
					} else {
						$form_fields[] = array(
							'type'        => 'checkbox',
							'name'        => $fieldname,
							'class'       => 'cdfs-checkbox' . $extra_classes,
							'placeholder' => $field['label'],
						);
					}
					break;
				case 'vehicle_overview':
				case 'technical_specifications':
				case 'general_information':
					// All Editor fields.
					if ( 'taxonomy' === $field['type'] && in_array( $field['taxonomy'], $additional_attrs_slugs, true ) ) {
						$form_fields_additional[] = array(
							'type'        => 'editor',
							'name'        => $fieldname,
							'class'       => 'cdfs-editor' . $extra_classes,
							'placeholder' => $field['label'],
						);
					} else {
						$form_fields[] = array(
							'type'        => 'editor',
							'name'        => $fieldname,
							'class'       => 'cdfs-editor' . $extra_classes,
							'placeholder' => $field['label'],
						);
					}
					break;
				case 'pdf_file':
					break;
				case 'car_images':
					if ( 'taxonomy' === $field['type'] && in_array( $field['taxonomy'], $additional_attrs_slugs, true ) ) {
						$form_fields_additional[] = array(
							'type'        => 'gallery',
							'name'        => $fieldname,
							'class'       => 'cdfs-editor' . $extra_classes,
							'placeholder' => $field['label'],
						);
					} else {
						$form_fields[] = array(
							'type'        => 'gallery',
							'name'        => $fieldname,
							'class'       => 'cdfs-editor' . $extra_classes,
							'placeholder' => $field['label'],
						);
					}
					break;
				default:
					// All text fields.
					$class = '';
					if ( in_array( 'car_' . $fieldname, $taxonomies ) && 'vin_number' !== $fieldname ) { // add autofill only for taxonomy fields.
						$class = 'cdfs-autofill';
					}
					if ( isset($field['taxonomy']) && ! empty($field['taxonomy']) ) {
						$new_tax_obj = get_taxonomy( $field['taxonomy'] );
						if( isset($new_tax_obj->include_in_filters) && $new_tax_obj->include_in_filters == true ) {
							$class = 'cdfs-autofill';
							$fieldname = $field['taxonomy'];
						}
					}

					if ( 'taxonomy' === $field['type'] && in_array( $field['taxonomy'], $additional_attrs_slugs, true ) ) {
						$form_fields_additional[] = array(
							'type'        => 'text',
							'name'        => $fieldname,
							'class'       => $class . $extra_classes,
							'placeholder' => $field['label'],
						);
					} else {
						$form_fields[] = array(
							'type'        => 'text',
							'name'        => $fieldname,
							'class'       => $class . $extra_classes,
							'placeholder' => $field['label'],
						);
					}
			}
		}

		$form_fields = array_merge( $form_fields, $form_fields_additional );

		/**
		 * Filters the vehicle form fields for add new vehicle page in front submission.
		 *
		 * @since 1.0
		 * @param array         $form_fields    An array of vehicle fields to be displayed on add vehicle page.
		 * @visible             true
		 */
		return apply_filters( 'cdfs_car_form_fields_items', $form_fields );
	}
}

if ( ! function_exists( 'cdfs_get_cars_taxonomy' ) ) {
	/**
	 * Get Taxomony of Cars Posttype
	 */
	function cdfs_get_cars_taxonomy() {
		$taxonomies    = get_object_taxonomies( 'cars' );
		$taxonomyarray = array();
		foreach ( $taxonomies as $taxonomy ) {
			$tax_obj = get_taxonomy( $taxonomy );
			if ( 'car_features_options' !== $taxonomy ) {
				$taxonomyarray[ $tax_obj->label ] = $taxonomy;
			}
		}
		return $taxonomyarray;
	}
}



add_action( 'wp_ajax_cdfs_get_autocomplete', 'cdfs_autocomplete_fields' );
add_action( 'wp_ajax_nopriv_cdfs_get_autocomplete', 'cdfs_autocomplete_fields' );
if ( ! function_exists( 'cdfs_autocomplete_fields' ) ) {
	/**
	 * Auto complete fields in car form.
	 */
	function cdfs_autocomplete_fields() {
		$responsearray = array();

		if ( isset( $_POST['action'] ) && 'cdfs_get_autocomplete' === $_POST['action'] ) {
				$fieldname  = 'car_' . cdfs_clean( wp_unslash( $_POST['fieldName'] ) );
				$search_val = cdfs_clean( $_POST['search'] );
				$options    = array(
					'orderby'    => 'name',
					'order'      => 'ASC',
					'hide_empty' => false, // can be 1, '1' too.
					'name__like' => $search_val,
					'meta_query' => '',
					'meta_key'   => array(),
					'meta_value' => '',
				);
				// search value.
				$fieldvalue = get_terms( $fieldname, $options );
				if ( is_wp_error( $fieldvalue ) ) {
					$fieldname  = cdfs_clean( wp_unslash( $_POST['fieldName'] ) );
					$new_tax_obj = get_terms( $fieldname, $options );
					if ( ! is_wp_error( $new_tax_obj ) ) {
						$result = array();
						foreach ( $new_tax_obj as $key => $value ) {
							$result[] = array(
								'label' => $value->name,
								'value' => $value->name,
							);
						}
						// Prepare response.
						$responsearray = array(
							'status' => true,
							'msg'    => esc_html__( 'Found Match!', 'cdfs-addon' ),
							'data'   => $result,
						);
					} else {
						$responsearray = array(
							'status' => true,
							'msg'    => esc_html__( 'Not Found', 'cdfs-addon' ),
							'data'   => array(),
						);
					}
				} else {
					if ( ! empty( $fieldvalue ) ) { // If found match.
						$result = array();
						foreach ( $fieldvalue as $key => $value ) {
							$result[] = array(
								'label' => $value->name,
								'value' => $value->name,
							);
						}

						// Prepare response.
						$responsearray = array(
							'status' => true,
							'msg'    => esc_html__( 'Found Match!', 'cdfs-addon' ),
							'data'   => $result,
						);
					} else {
						$responsearray = array(
							'status' => true,
							'msg'    => esc_html__( 'Not Found', 'cdfs-addon' ),
							'data'   => array(),
						);
					}
				}
		} else {
			$responsearray = array( 'status' => false );
		}

		// Send Result.
		echo json_encode( $responsearray );
		die;
	}
}

if ( ! function_exists( 'cdfs_handle_attachment' ) ) {
	/**
	 * Image upload handle.
	 *
	 * @param string $file_handler .
	 * @param string $post_id .
	 * @param string $allowed_types .
	 */
	function cdfs_handle_attachment( $file_handler, $post_id, $allowed_types = array( 'jpg', 'jpeg', 'png', 'gif' ) ) {
		// check to make sure its a successful upload.
		if ( ! isset( $_FILES[ $file_handler ]['error'] ) ) {
			return false;
		}
		if ( UPLOAD_ERR_OK !== $_FILES[ $file_handler ]['error'] ) {
			return false; }

		$ext = pathinfo( $_FILES[ $file_handler ]['name'] );
		$ext = $ext['extension'];
		if ( ! in_array( strtolower( $ext ), $allowed_types ) ) {
			/* translators: $s: Please upload file */
			cdfs_add_notice( sprintf( esc_html__( 'Please upload file(s) with %s extension.', 'cdfs-addon' ), implode( ', ', $allowed_types ) ), 'error' );
			return false;
		}

		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';

		$attach_id = media_handle_upload( $file_handler, $post_id );
		if ( is_wp_error( $attach_id ) ) {
			return false;
		}
		return $attach_id;
	}
}

if ( ! function_exists( 'cdfs_delete_post_attachments' ) ) {
	/**
	 * Unset / Remove attachments
	 *
	 * @param string $post_id .
	 * @param string $field .
	 * @param string $attachment_ids .
	 */
	function cdfs_delete_post_attachments( $post_id, $field, $attachment_ids ) {
		foreach ( $attachment_ids as $id ) {
			try {
				// Update post attachments.
				$attachments = get_post_meta( $post_id, $field, true );
				if ( ( $key = array_search( $id, $attachments ) ) !== false ) {
					unset( $attachments[ $key ] );
					update_post_meta( $post_id, $field, $attachments );
				}
			} catch ( Exception $e ) {
				cdfs_add_notice( $e->getMessage(), 'error' );
			}
		}
	}
}



add_action( 'wp_ajax_cdfs_delete_attachment', 'cdfs_delete_attachment' );
add_action( 'wp_ajax_nopriv_cdfs_delete_attachment', 'cdfs_delete_attachment' );
if ( ! function_exists( 'cdfs_delete_attachment' ) ) {
	/**
	 * Remove attachments.
	 */
	function cdfs_delete_attachment() {
		$responsearray = array(
			'status' => false,
			'msg'    => esc_html__( 'Something went wrong!', 'cdfs-addon' ),
		);

		if ( isset( $_POST['action'] ) && 'cdfs_delete_attachment' === $_POST['action'] ) {
			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'cdfs-car-form' ) ) {
				$responsearray = array( 'status' => false );
			} else {
				$attach_id   = cdfs_clean( $_POST['attach_id'] );
				$parent_id   = cdfs_clean( $_POST['parent_id'] );
				$field       = cdfs_clean( $_POST['field'] );
				if ( isset( $attach_id ) ) {
					// Update post attachments.
					$attachments = get_post_meta( $parent_id, $field, true );
					if ( ( $key = array_search( $attach_id, $attachments ) ) !== false ) {
						unset( $attachments[ $key ] );
						update_post_meta( $parent_id, $field, $attachments );
					}

					$responsearray = array(
						'status' => true,
						'msg'    => esc_html__( 'Successfully dropped image!', 'cdfs-addon' ),
					);
				}
			}
		}
		// Send Result.
		echo wp_json_encode( $responsearray );
		die;
	}
}

add_action( 'wp_ajax_cdfs_save_car', 'cdfs_do_save' );
add_action( 'wp_ajax_nopriv_cdfs_save_car', 'cdfs_do_save' );
if ( ! function_exists( 'cdfs_do_save' ) ) {
	/**
	 * Function to save car data
	 */
	function cdfs_do_save() {
		$status = false;
		$msg    = esc_html__( 'Something went wrong!', 'cdfs-addon' );
		$car_id = '';
		if ( isset( $_POST['action'] ) && 'cdfs_save_car' === $_POST['action'] ) {
			$result         = CDFS_Cars_Form_Handler::process_car_save();
			$invalid_fields = array();
			if ( ! empty( $result ) ) {
				switch ( $result['status'] ) {
					case 1:
						$status = true;
						$msg    = esc_html__( 'Vehicle data successfully saved!', 'cdfs-addon' );
						$car_id = $result['post_id'];
						break;
					case 3:
						$status = false;
						$msg    = esc_html__( 'Please check captcha form.', 'cdfs-addon' );
						break;
					case 4:
						$status = false;
						foreach ( $result['err_fields'] as $field ) {
							$invalid_fields[] = strtolower( str_replace( ' ', '_', $field ) );
						}
						$msg = sprintf(
							/* translators: %s: list of required fields */
							esc_html__( 'Please fill required fields: %s', 'cdfs-addon' ),
							esc_html( implode( ', ', $result['err_fields'] ) )
						);
						break;
					case 5:
						$status = false;
						$msg    = sprintf(
							/* translators: %s: number of vehicles */
							esc_html__( 'Sorry! you can add at most %s vehicles.', 'cdfs-addon' ),
							esc_html( $result['limit'] )
						);
						break;
					case 6:
						$status = false;
						$msg    = sprintf(
							/* translators: %s: number of images */
							esc_html__( 'Sorry! you can upload vehicle images at most %s.', 'cdfs-addon' ),
							esc_html( $result['limit'] )
						);
						break;
					default:
						$status = false;
						$msg    = esc_html__( 'There is an error inserting vehicle, please try again later!', 'cdfs-addon' );
				}
			}
		}
		echo wp_json_encode(
			array(
				'status'         => $status,
				'car_id'         => $car_id,
				'message'        => $msg,
				'invalid_fields' => $invalid_fields,
			)
		);
		die;
	}
}



add_action( 'wp_ajax_cdfs_upload_images', 'cdfs_do_upload_images' );
add_action( 'wp_ajax_nopriv_cdfs_upload_images', 'cdfs_do_upload_images' );
if ( ! function_exists( 'cdfs_do_upload_images' ) ) {
	/**
	 * Remove attachments
	 */
	function cdfs_do_upload_images() {
		$status     = false;
		$redirect   = '';
		$status_msg = esc_html__( 'Something went wrong!', 'cdfs-addon' );

		if ( isset( $_POST['action'] ) && 'cdfs_upload_images' === $_POST['action'] ) {
			$imgupload = CDFS_Cars_Form_Handler::process_image_upload();
			if ( true === (bool) $imgupload['status'] ) {
				$redirect   = cdfs_get_page_permalink( 'myuseraccount' );
				$status_msg = esc_html__( 'Vehicle images uploaded successfully!!', 'cdfs-addon' );
				cdfs_add_notice( esc_html__( 'Vehicle is successfully saved!', 'cdfs-addon' ) );
				$status = true;
				if ( ! empty( $imgupload['file_size_error'] ) ) {
					/* translators: $s: Following images not uploaded due to image size exceeded than */
					cdfs_add_notice( sprintf( esc_html__( 'Following images not uploaded due to image size exceeded than %1$s MB: %2$s.', 'cdfs-addon' ), $imgupload['file_size_limit'], implode( ',', $imgupload['file_size_error'] ) ), 'error' );
				}
			}
		}
		echo wp_json_encode(
			array(
				'status'     => $status,
				'redirect'   => $redirect,
				'status_msg' => $status_msg,
			)
		);
		die;
	}
}

if ( ! function_exists( 'cdfs_get_html_mail_body' ) ) {
	/**
	 * Function For HTML Mail Body
	 *
	 * @param string $car_id .
	 */
	function cdfs_get_html_mail_body( $car_id ) {
		$car_dealer_options = get_option( 'car_dealer_options' );

		$vehivle_data = array();
		$product      = '';

		// Image
		$vehivle_data['car_thumbnail'] = array(
			'value'   => cardealer_get_cars_image( 'car_thumbnail', $car_id ),
		);

		// Price
		$sale_price    = false;
		$regular_price = false;
		$price         = array();
		// '&nbsp;';
		if ( function_exists( 'get_field' ) ) {
			$sale_price    = (int) get_field( 'sale_price', $car_id );
			$regular_price = (int) get_field( 'regular_price', $car_id );
		} else {
			$sale_price    = (int) get_post_meta( $post_id, 'sale_price', true );
			$regular_price = (int) get_post_meta( $post_id, 'regular_price', true );
		}

		$currency_code            = ( isset( $car_dealer_options['cars-currency-symbol'] ) && ! empty( $car_dealer_options['cars-currency-symbol'] ) ) ? $car_dealer_options['cars-currency-symbol'] : 'USD';
		$currency_symbol          = ( function_exists( 'cdhl_get_currency_symbols' ) ) ? cdhl_get_currency_symbols( $currency_code ) : '$';
		$symbol_position          = (int) ( ( isset( $car_dealer_options['cars-currency-symbol-placement'] ) && ! empty( $car_dealer_options['cars-currency-symbol-placement'] ) ) ? $car_dealer_options['cars-currency-symbol-placement'] : '1' );
		$seperator                = (bool) ( ( isset( $car_dealer_options['cars-disable-currency-separators'] ) && '' != $car_dealer_options['cars-disable-currency-separators'] ) ? $car_dealer_options['cars-disable-currency-separators'] : '1' );
		$seperator_symbol         = ( isset( $car_dealer_options['cars-thousand-separator'] ) && ! empty( $car_dealer_options['cars-thousand-separator'] ) ) ? $car_dealer_options['cars-thousand-separator'] : ',';
		$decimal_places           = ( ! empty( $car_dealer_options['cars-number-decimals'] ) && is_numeric( $car_dealer_options['cars-number-decimals'] ) ) ? $car_dealer_options['cars-number-decimals'] : 0;
		$decimal_separator_symbol = ( isset( $car_dealer_options['cars-decimal-separator'] ) && ! empty( $car_dealer_options['cars-decimal-separator'] ) ) ? $car_dealer_options['cars-decimal-separator'] : '.';

		if ( $regular_price || $sale_price ) {

			if ( $sale_price ) {
				if ( $seperator ) {
					$sale_price = number_format( $sale_price, $decimal_places, $decimal_separator_symbol, $seperator_symbol );
				}
				if ( 1 === $symbol_position || 3 === $symbol_position ) {
					$price[] = '<span>' . esc_html__( 'Sale Price: ', 'cdfs-addon' ) . ( ( 1 === $symbol_position ) ? "{$currency_symbol}{$sale_price}" : "{$currency_symbol} {$sale_price}" ) . '</span>';
				} else {
					$price[] = '<span>' . esc_html__( 'Sale Price: ', 'cdfs-addon' ) . ( ( 2 === $symbol_position ) ? "{$sale_price}{$currency_symbol}" : "{$sale_price} {$currency_symbol}" ) . '</span>';
				}
			}
			if ( $regular_price ) {
				if ( $seperator ) {
					$regular_price = number_format( $regular_price, $decimal_places, $decimal_separator_symbol, $seperator_symbol );
				}
				if ( 1 === $symbol_position || 3 === $symbol_position ) {
					$price[] = '<span>' . esc_html__( 'Sale Price: ', 'cdfs-addon' ) . ( ( 1 === $symbol_position ) ? "{$currency_symbol}{$regular_price}" : "{$currency_symbol} {$regular_price}" ) . '</span>';
				} else {
					$price[] = '<span>' . esc_html__( 'Sale Price: ', 'cdfs-addon' ) . ( ( 2 === $symbol_position ) ? "{$regular_price}{$currency_symbol}" : "{$regular_price} {$currency_symbol}" ) . '</span>';
				}
			}
		}
		if ( is_array( $price ) && ! empty( $price ) ) {
			$price = implode( '&nbsp;&nbsp;', $price );
		} else {
			$price = '&mdash;';
		}

		$vehivle_data['vehicle_price'] = array(
			'label'   => esc_html__( 'Vehicle Price', 'cdfs-addon' ),
			'value'   => $price,
			'valuex'   => '<span>' . esc_html__( 'Sale Price: ', 'cdfs-addon' ) . $sale_price . '</span><span>&nbsp;&nbsp;' . esc_html__( 'Regular Price : ', 'cdfs-addon' ) . $regular_price . '</span>',
		);

		$cars_taxonomy_array  = cdfs_get_cars_taxonomy();
		$cars_taxonomy_array  = array_flip( $cars_taxonomy_array );

		foreach ( $cars_taxonomy_array as $vehicle_tax => $vehicle_tax_label ) {
			$vehicle_terms = wp_get_post_terms( $car_id, $vehicle_tax );
			if ( ! is_wp_error( $vehicle_terms ) ) {
				$vehivle_data[ $vehicle_tax ] = array(
					'label' => $vehicle_tax_label,
					'value' => ( ! empty( $vehicle_terms ) ) ? $vehicle_terms[0]->name : '&mdash;',
				);
			}
		}

		/**
		 * Filters the HTML of mail body for vehicle attributes.
		 *
		 * @since 1.0
		 * @param string    $product    contents of the mail body for vehicle.
		 * $param int       $car_id     Vehicle ID.
		 * @visible         true
		 */
		$vehivle_data = apply_filters( 'cdfs_get_mail_body_vehicle_data', $vehivle_data, $car_id );

		$product .= '<table class="compare-list compare-datatable" width="100%" border="1" cellspacing="0" cellpadding="5">';
		$product .= '<tbody>';

		foreach ( $vehivle_data as $vehivle_data_k => $vehivle_data_v ) {

			if ( ! isset( $vehivle_data_v['value'] ) ) {
				continue;
			}

			$product .= '<tr class="' . esc_attr( $vehivle_data_k ) . '">';
			if ( ! isset( $vehivle_data_v['label'] ) || '' === $vehivle_data_v['label'] ) {
				$product .= '<td colspan=2 style="text-align:center">';
			} else {
				$product .= '<td>';
				$product .= $vehivle_data_v['label'];
				$product .= '</td>';
				$product .= '<td>';
			}
			$product .= $vehivle_data_v['value'];
			$product .= '</td>';
			$product .= '</tr>';
		}

		$product .= '</tbody>';
		$product .= '</table>';

		/**
		 * Filters the HTML of mail body for vehicle attributes.
		 *
		 * @since 1.0
		 * @param string    $product    contents of the mail body for vehicle.
		 * $param int       $car_id     Vehicle ID.
		 * @visible         true
		 */
		return apply_filters( 'cdfs_get_html_mail_body', $product, $car_id );
	}
}

/**
 * User vehicle notification on post status publish.
 */
if ( ! is_admin() ) {
	add_action( 'transition_post_status', 'cdfs_send_publish_notification', 10, 3 );
}

if ( ! function_exists( 'cdfs_send_publish_notification' ) ) {
	/**
	 * Send publish notification
	 *
	 * @param string $new_status .
	 * @param string $old_status .
	 * @param string $post .
	 */
	function cdfs_send_publish_notification( $new_status, $old_status, $post ) {
		if ( 'publish' !== $new_status || 'publish' === $old_status ) {
			return;
		}

		if ( 'cars' !== $post->post_type ) {
			return; // restrict the filter to a specific post type.
		}

		// send notification.
		// get site details.
		$site_title = get_bloginfo( 'name' );
		$site_email = get_bloginfo( 'admin_email' );
		$admin      = get_user_by( 'email', $site_email );

		$dealer_id = get_post_meta( $post->ID, 'cdfs_car_user', true );
		$dealer_id = 6;
		if ( empty( $dealer_id ) ) { // return if no dealer is assigned.
			return;
		}
		$dealer_data = get_user_by( 'id', $dealer_id );

		// Send email notification.
		$subject  = esc_html__( 'Vehicle published!', 'cdfs-addon' );
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type:text/html;charset=UTF-8' . "\r\n";
		$headers .= 'From: ' . html_entity_decode( $site_title, ENT_QUOTES ) . ' <' . $site_email . '>' . "\r\n";

		$car_data = array(
			'admin_name'    => $admin->user_login,
			'dealer_name'   => $dealer_data->user_login,
			'vehicle_title' => $post->post_title,
			'vehicle_link'  => get_permalink( $post->ID ),
			'dealer_email'  => $dealer_data->user_email,
			'mail_html'     => cdfs_get_html_mail_body( $post->ID ),
		);
		// Mail to admin.
		ob_start();
		cdfs_get_template(
			'mails/mail-dealer-publish-notification.php',
			array(
				'car_data'  => $car_data,
				'site_data' => array( 'site_title' => $site_title ),
			)
		);
		$dealer_message = ob_get_contents();
		ob_end_clean();

		// send mail.
		try {
			wp_mail( $site_email, $subject, $dealer_message, $headers );
		} catch ( Exception $e ) {
			cdfs_add_notice( $e->getMessage(), 'error' );
		}
	}
}

/**
 * Check whether car clone functionality is enabled.
 *
 * @return void
 */
function cdfs_is_vehicle_clone_enabled() {
	return apply_filters( 'cdfs_is_vehicle_clone_enabled', false );
}
