<?php
/**
 * CDFS car form handler.
 *
 * @author  PotenzaGlobalSolutions
 * @package CDFS
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Handle frontend forms.
 *
 * @author PotenzaGlobalSolutions
 */
class CDFS_Cars_Form_Handler {

	/**
	 * Hook in methods.
	 */
	public static function init() {
		// add_action( 'wp_loaded', array( __CLASS__, 'process_car_save' ), 20 );.
	}

	/**
	 * Get logged in user id.
	 */
	public static function get_user_id() {
		if ( is_user_logged_in() ) {
			return get_current_user_id();
		}
		return false;
	}

	/**
	 * Save car save [ insert / update ]
	 */
	public static function process_car_save() {
		global $car_dealer_options;
		if ( empty( $_POST['action'] ) || 'cdfs_save_car' !== $_POST['action'] ) {
			return array( 'status' => 2 );
		}

		$user = self::get_user_id();
		if ( ! $user ) { // return if not logged in.
			return array( 'status' => 2 );
		}
		if ( isset( $_POST['cdfs-car-form-nonce-field'] ) ) {
			if ( empty( $_POST['cdfs-car-form-nonce-field'] ) || ! wp_verify_nonce( $_POST['cdfs-car-form-nonce-field'], 'cdfs-car-form' ) ) {
				return array( 'status' => 2 );
			}
		}

		nocache_headers();
		$car_data = array();
		$errors   = new WP_Error();

		if ( ! cdfs_validate_captcha( false ) ) { // captcha serverd side validation.
			return array( 'status' => 3 );
		}

		$car_data = $_POST['car_data'];

		if ( ! isset( $_POST['cdfs_action_car_id'] ) || empty( $_POST['cdfs_action_car_id'] ) ) {
			// Validate insertion of car if insert vehicle action is called.
			$validateinsert = self::validate_car_insert();
			if ( 0 == $validateinsert['status'] ) { // Somthing went wrong.
				return array( 'status' => 2 );
			} elseif ( 2 == $validateinsert['status'] ) {
				return array(
					'status' => 5,
					'limit'  => $validateinsert['limit'],
				); // car insert limit exceeded.
			} elseif ( 3 == $validateinsert['status'] ) {
				return array(
					'status' => 6,
					'limit'  => $validateinsert['limit'],
				); // car img upload limit exceeded.
			}
		}
		/**
		 * Filters the array of vehicle required fields.
		 *
		 * @since 1.0
		 * @param array      $required_fields   Array of fields to put in required fields in add car form(server side validation).
		 * @visible           true
		 */
		$required_fields = apply_filters(
			'cdfs_car_required_fields',
			array(
				'year'          => esc_html__( 'Year', 'cdfs-addon' ),
				'make'          => esc_html__( 'Make', 'cdfs-addon' ),
				'model'         => esc_html__( 'Model', 'cdfs-addon' ),
				'regular_price' => esc_html__( 'Regular Price', 'cdfs-addon' ),
			)
		);

		// check required fields.
		$val_err_fields = array();
		foreach ( $required_fields as $field_key => $field_name ) {
			if ( empty( $car_data[ $field_key ] ) ) {
				$val_err_fields[] = $field_name;
			}
		}
		if ( ! empty( $val_err_fields ) ) {
			return array(
				'status'     => 4,
				'err_fields' => $val_err_fields,
			);
		}

		if ( cdfs_notice_count( 'error' ) !== 0 ) {
			return array( 'status' => 2 );
		}

		// Attributes.
		if ( ! empty( $car_data ) ) {

			$post_status = 'pending';
			if ( isset( $car_dealer_options['cdfs_auto_publish']['auto_publish'] ) && 1 == $car_dealer_options['cdfs_auto_publish']['auto_publish'] ) {
				$post_status = 'publish';
			}

			$car_title  = cdfs_clean( $car_data['year'] ) . ' ' . cdfs_clean( $car_data['make'] ) . ' ' . cdfs_clean( $car_data['model'] ); // car title.
			$car_title  = apply_filters( 'cdfs_car_title', $car_title, $car_data );
			$taxonomies = cdfs_get_cars_taxonomy();

			if ( isset( $_POST['cdfs_action_car_id'] ) && ! empty( $_POST['cdfs_action_car_id'] ) ) { // Update car.
				$car_id = cdfs_clean( $_POST['cdfs_action_car_id'] );
				$car_id = wp_update_post(
					array(
						'ID'         => $car_id,
						'post_title' => $car_title,
					)
				);
			} else { // Insert car.
				$car_id = wp_insert_post(
					array(
						'post_status' => $post_status,
						'post_type'   => 'cars',
						'post_title'  => $car_title,
					)
				);
			}

			// checkbox fields i.e. array('taxonomy'=> 'fieldname').
			$checkbox_fields = apply_filters(
				'cdfs_checkbox_fields',
				array(
					'car_features_options'      => 'features_and_options',
					'car_vehicle_review_stamps' => 'vehicle_review_stamps',
				)
			);
			$editor_fields   = apply_filters( 'cdfs_editor_fields', array( 'vehicle_overview', 'technical_specifications', 'general_information' ) );

			if ( ! is_wp_error( $car_id ) ) {

				$additional_tax_arr = array();
				foreach ( $taxonomies as $new_tax ) {
					$new_tax_obj = get_taxonomy( $new_tax );
					if( isset($new_tax_obj->include_in_filters) && $new_tax_obj->include_in_filters == true ) {
						$additional_tax_arr[] = $new_tax;
					}
				}

				// Add user name for this car post_content.
				update_post_meta( $car_id, 'cdfs_car_user', $user );

				// enter empty data for check box fields if check box fields are not set.
				foreach ( $checkbox_fields as $tax => $c_field ) {
					if ( ! in_array( $c_field, $car_data ) ) {
						wp_set_object_terms( $car_id, array(), $tax, false );
					}
				}

				/**
				 * Filters vehicle post data submitted from front submission form.
				 *
				 * @since 1.0
				 * @param array       $car_data Array of vehicle data.
				 * @param int         $car_id   Vehicle ID.
				 * @visible           true
				 */
				$car_data = apply_filters( 'cdfs_custom_car_data', $car_data, $car_id );

				foreach ( $car_data as $field => $value ) {
					$field_taxonomy = 'car_' . $field;
					if ( in_array( $field_taxonomy, $taxonomies ) && ! in_array( $field, $checkbox_fields ) ) { // check for taxonomy fields.
						wp_set_object_terms( $car_id, cdfs_clean( $value ), $field_taxonomy, false );
					} elseif ( in_array( $field, $additional_tax_arr ) ) { // check for taxonomy fields.
						wp_set_object_terms( $car_id, cdfs_clean( $value ), $field, false );
					} elseif ( in_array( $field, $checkbox_fields ) ) { // checkbox fields.
						// Checkbox options input.
						$car_options = cdfs_clean( $value );
						if ( 'features_and_options' === $field ) {
							$field_taxonomy = 'car_features_options';

							// Code to add other options for features_and_options.
							if ( isset( $car_data['cdfs-other'] ) && ! empty( $car_data['cdfs-other'] ) && ! empty( $car_data['cdfs-other-opt'] ) ) {
								$fno_opts = explode( ',', cdfs_clean( $car_data['cdfs-other-opt'] ) );
								if ( ! empty( $fno_opts ) ) {
									foreach ( $fno_opts as $fno_opt ) {
										$car_options[] = trim( cdfs_clean( $fno_opt ) );
									}
								}
							}
						}
						wp_set_object_terms( $car_id, $car_options, $field_taxonomy, false );
					} elseif ( 'vehicle_location' === $field ) { // Variable car_location.
						if ( ! empty( $_POST['cdfs_lat'] ) && ! empty( $_POST['cdfs_lng'] ) ) {
							$location = array(
								'address' => cdfs_clean( $value ),
								'lng'     => cdfs_clean( $_POST['cdfs_lng'] ),
								'lat'     => cdfs_clean( $_POST['cdfs_lat'] ),
								'zoom'    => '10',
							);
							update_post_meta( $car_id, $field, $location );
						}
					} else {
						if ( in_array( $field, $editor_fields ) ) { // Do not sanitize text editor fields.
							update_post_meta( $car_id, $field, wp_kses_post( $value ) ); // echo '<pre>'; print_r($value);die();.
						} else {
							update_post_meta( $car_id, $field, cdfs_clean( $value ) );
						}
					}
				}

				// car excerpt save.
				if ( isset( $_POST['car_excerpt'] ) ) {
					$post_car_excerpt = array(
						'ID'           => $car_id,
						'post_excerpt' => $_POST['car_excerpt'],
					);
					wp_update_post( $post_car_excerpt );
				}

				// Send notification mail to admin about new car add.
				if ( ! isset( $_POST['cdfs_action_car_id'] ) ) { // if new car added [ donot send notification on update car ].
					// get site details.
					$site_title  = get_bloginfo( 'name' );
					$site_email  = get_bloginfo( 'admin_email' );
					$admin       = get_user_by( 'email', $site_email );
					$dealer_data = get_user_by( 'id', $user );

					// Send email notification.
					$subject  = esc_html__( 'New Vehicle Added!', 'cdfs-addon' );
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type:text/html;charset=UTF-8' . "\r\n";
					$headers .= 'From: ' . $site_title . ' <' . $site_email . '>' . "\r\n";

					$car_data = array(
						'admin_name'   => $admin->user_login,
						'dealer_name'  => $dealer_data->user_login,
						'dealer_email' => $dealer_data->user_email,
						'mail_html'    => cdfs_get_html_mail_body( $car_id ),
					);
					// Mail to admin.
					ob_start();
					cdfs_get_template(
						'mails/mail-add-car-admin-notification.php',
						array(
							'car_data'  => $car_data,
							'site_data' => array( 'site_title' => $site_title ),
						)
					);
					$admin_message = ob_get_contents();
					ob_end_clean();

					// send mail.
					try {
						wp_mail( $site_email, $subject, $admin_message, $headers );
					} catch ( Exception $e ) {
						cdfs_add_notice( $e->getMessage(), 'error' );
						return array( 'status' => 2 );
					}
				}
				return array(
					'status'  => 1,
					'post_id' => $car_id,
				);
			} else {
				// There was an error in the car insertion.
				return array( 'status' => 2 );
			}
		} else {
			return array( 'status' => 2 );
		}
	}

	/**
	 * Function to upload car images and pdf
	 * return: attachment id if success and false if error
	 */
	public static function process_image_upload() {
		$sorted_img_list    = explode( ',', $_POST['file_attachments'] ); // img order.
		$car_id             = cdfs_clean( $_POST['car_id'] );
		$max_file_size      = apply_filters( 'cdfs_max_media_upload_size', 1048576 * 4 ); /* 4mb is highest media upload here */
		$imgs_size_error    = '';
		$files_not_uploaded = '';

		if ( isset( $_FILES ) && ! empty( $_FILES ) ) {
			$files_uploaded = false;
			foreach ( $_FILES as $field => $images ) {
				$car_attachments    = array();
				$files_not_uploaded = array();
				$imgs_size_error    = array();

				// Allowed file types.
				switch ( $field ) {
					case 'pdf_file':
						$allowed_types = array( 'pdf' );
						break;
					default:
						$allowed_types = array( 'jpg', 'jpeg', 'png', 'gif' );
				}

				if ( is_array( $images['name'] ) ) {
					foreach ( $images['name'] as $key => $value ) {

						// do not upload if size exceeds.
						if ( $images['size'][ $key ] > $max_file_size ) {
							$imgs_size_error[] = $images['name'][ $key ];

							// also remove image from sorted array.
							$ordered_arr_key = array_search( $value, $sorted_img_list );
							if ( $ordered_arr_key ) {
								unset( $sorted_img_list[ $ordered_arr_key ] );
							}
							continue;
						}
						$file_array    = array(
							'name'     => $images['name'][ $key ],
							'type'     => $images['type'][ $key ],
							'tmp_name' => $images['tmp_name'][ $key ],
							'error'    => $images['error'][ $key ],
							'size'     => $images['size'][ $key ],
						);
						$_FILES        = array( $field => $file_array );
						$attachment_id = cdfs_handle_attachment( $field, $car_id, $allowed_types );
						if ( $attachment_id ) {
							$car_attachments[ $attachment_id ] = $images['name'][ $key ]; // map attachment_id with image.
						} else {
							$files_not_uploaded[] = $images['name'][ $key ];
						}
					}

					// Assign attachment_ids to post.
					if ( ! empty( $car_attachments ) ) {
						$files_to_upload = array();
						foreach ( $sorted_img_list as $s_key => $sorted_img ) {
							$img_idx = array_search( $sorted_img, $car_attachments );
							if ( $img_idx ) {
								$files_to_upload[] = $img_idx;
								unset( $car_attachments[ $img_idx ] );
							} else {
								$files_to_upload[] = $sorted_img;
							}
						}

						$files_uploaded = true;
						update_field( $field, $files_to_upload, $car_id );
					} else {
						update_field( $field, null, $car_id );
						$files_uploaded = true;
					}
				} else { // single file.
					if ( isset( $images ) && ! empty( $images ) ) {
						$_FILES = array( $field => $images );
						foreach ( $_FILES as $field_name => $array ) {
							$attachment_id = cdfs_handle_attachment( $field_name, $car_id, $allowed_types );
							if ( $attachment_id ) {
								$files_uploaded = true;
								update_field( $field, $attachment_id, $car_id );
							} else {
								$files_not_uploaded[] = $images['name'];
							}
						}
					}
				}
			}
		} elseif ( ! empty( $sorted_img_list ) ) {// if no files uploaded then perform only img sorting update.
			update_field( 'car_images', $sorted_img_list, $car_id );
			$files_uploaded = true;
		}
		if ( true == $files_uploaded ) {
			return array(
				'status'             => true,
				'file_size_error'    => $imgs_size_error,
				'files_not_uploaded' => $files_not_uploaded,
				'file_size_limit'    => ( $max_file_size / 1048576 ),
			);
		} else {
			return array( 'status' => false );
		}
	}

	/**
	 * Validate car insert limit and image limit
	 *
	 * Return : true (if validated) / false ( if not validated )
	 */
	public static function validate_car_insert() {
		global $car_dealer_options;

		$img_limit     = isset( $car_dealer_options['cdfs_cars_img_limit'] ) ? $car_dealer_options['cdfs_cars_img_limit'] : null;
		$car_limit     = isset( $car_dealer_options['cdfs_cars_limit'] ) ? $car_dealer_options['cdfs_cars_limit'] : null;
		$uploaded_imgs = isset( $_POST['car_img_cnt'] ) ? cdfs_clean( $_POST['car_img_cnt'] ) : 0;

		$user = self::get_user_id();

		if ( $user ) {
			if ( class_exists( 'Subscriptio' ) || class_exists( 'RP_SUB' ) ) {
				$user_subscriptions = array();

				if ( function_exists( 'subscriptio_get_customer_subscriptions' ) ) {
					$user_subscriptions = subscriptio_get_customer_subscriptions( $user );
				}

				if( ! empty( $user_subscriptions ) ) {

					$user_subscription = reset( $user_subscriptions );

					$status = $user_subscription->get_status();

					$_product_id = '';

					if ( 'active' === $status ) {
						$car_limit = intval( get_user_meta( $user, 'cdfs_car_limt', true ) );
						$img_limit = intval( get_user_meta( $user, 'cdfs_img_limt', true ) );
					}
				}
			}
			$args = array(
				'post_type'   => 'cars',
				'post_status' => array( 'draft', 'publish', 'trash', 'pending' ),
				'meta_query'  => array(
					array(
						'key'     => 'cdfs_car_user',
						'value'   => $user,
						'compare' => '=',
					),
				),
			);

			$loop     = new WP_Query( $args );
			$numposts = $loop->post_count;

			if ( null != $car_limit && ( $numposts >= $car_limit ) ) {
				return array(
					'status' => 2,
					'limit'  => $car_limit,
				);
			}

			if ( $uploaded_imgs > $img_limit ) {
				return array(
					'status' => 3,
					'limit'  => $img_limit,
				);
			}
			return array( 'status' => 1 );
		}
		return array( 'status' => 0 );
	}

}

CDFS_Cars_Form_Handler::init();
