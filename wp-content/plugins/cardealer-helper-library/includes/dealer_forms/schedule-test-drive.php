<?php
/**
 * This function use to store new inquiries of test drive.
 *
 * @package car-dealer-helper
 */

add_action( 'wp_ajax_shedule_test_drive_action', 'cdhl_shedule_test_drive_action' );
add_action( 'wp_ajax_nopriv_shedule_test_drive_action', 'cdhl_shedule_test_drive_action' );
if ( ! function_exists( 'cdhl_shedule_test_drive_action' ) ) {
	/**
	 * Shedule test drive
	 */
	function cdhl_shedule_test_drive_action() {
		global $car_dealer_options;
		$response_array = array();
		$response_array = array(
			'status' => 'error',
			'msg'    => '<div class="alert alert-danger">Something went wrong!</div>',
		);
		if ( isset( $_POST['action'] ) && 'shedule_test_drive_action' === (string) sanitize_text_field( wp_unslash( $_POST['action'] ) ) ) {

			$captcha            = isset( $_POST['g-recaptcha-response'] ) ? sanitize_text_field( wp_unslash( $_POST['g-recaptcha-response'] ) ) : '';
			$recaptcha_response = cardealer_validate_google_captch( $captcha );
			$first_name         = isset( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : '';
			$last_name          = isset( $_POST['last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) : '';
			$state              = isset( $_POST['state'] ) ? sanitize_text_field( wp_unslash( $_POST['state'] ) ) : '';
			$zip                = isset( $_POST['zip'] ) ? sanitize_text_field( wp_unslash( $_POST['zip'] ) ) : '';
			$preferred_contact  = isset( $_POST['preferred_contact'] ) ? sanitize_text_field( wp_unslash( $_POST['preferred_contact'] ) ) : '';
			$email              = isset( $_POST['email'] ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';
			$mobile             = isset( $_POST['mobile'] ) ? sanitize_text_field( wp_unslash( $_POST['mobile'] ) ) : '';
			$test_drive         = isset( $_POST['test_drive'] ) ? sanitize_text_field( wp_unslash( $_POST['test_drive'] ) ) : '';
			$address            = isset( $_POST['address'] ) ? sanitize_text_field( wp_unslash( $_POST['address'] ) ) : '';
			$date               = isset( $_POST['date'] ) ? sanitize_text_field( wp_unslash( $_POST['date'] ) ) : '';
			$time               = isset( $_POST['time'] ) ? sanitize_text_field( wp_unslash( $_POST['time'] ) ) : '';
			$car_id             = isset( $_POST['car_id'] ) ? sanitize_text_field( wp_unslash( $_POST['car_id'] ) ) : '';
			$author_id          = get_post_field( 'post_author', $car_id );
			$user               = get_userdata( $author_id );
			$user_roles         = $user->roles;
			$from_name          = $user->display_name;

			if ( in_array( 'administrator', $user_roles, true ) ) {
				$from_name = $car_dealer_options['std_mail_from_name'];
			}

			$field_label_state = isset( $car_dealer_options['schedule_drive_field_label_state'] ) && ! empty( $car_dealer_options['schedule_drive_field_label_state'] ) ? $car_dealer_options['schedule_drive_field_label_state'] : esc_html__( 'State', 'cardealer-helper' );
			$field_label_zip   = isset( $car_dealer_options['schedule_drive_field_label_zip'] ) && ! empty( $car_dealer_options['schedule_drive_field_label_zip'] ) ? $car_dealer_options['schedule_drive_field_label_zip'] : esc_html__( 'Zip', 'cardealer-helper' );

			if ( ! isset( $_POST['shedule_test_drive_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['shedule_test_drive_nonce'] ), 'shedule_test_drive_nonce' ) ) {
				$errors[] = esc_html__( 'Sorry, Something went wrong. Refresh Page and try again.', 'cardealer-helper' );
			} elseif ( false === (bool) $recaptcha_response['success'] ) {
				$errors[] = esc_html__( 'Please check the the captcha form', 'cardealer-helper' );
			} else {
				$mail_set     = 0;
				$request_date = ( isset( $recaptcha_response['challenge_ts'] ) && ! empty( $recaptcha_response['challenge_ts'] ) ) ? $recaptcha_response['challenge_ts'] : '';
				$post_id      = wp_insert_post(
					array(
						'post_status'  => 'publish',
						'post_type'    => 'schedule_test_drive',
						'post_title'   => $first_name . ' ' . $last_name,
						'post_content' => '',
					)
				);
				if ( $post_id ) {
					if ( isset( $car_id ) && ! empty( $car_id ) ) {
						$caryear           = wp_get_post_terms( $car_id, 'car_year' );
						$car_year          = ( ! is_wp_error( $caryear ) && ! empty( $caryear ) ) ? $caryear[0]->name : '';
						$carmake           = wp_get_post_terms( $car_id, 'car_make' );
						$car_make          = ( ! is_wp_error( $carmake ) && ! empty( $carmake ) ) ? $carmake[0]->name : '';
						$carmodel          = wp_get_post_terms( $car_id, 'car_model' );
						$car_model         = ( ! is_wp_error( $carmodel ) && ! empty( $carmodel ) ) ? $carmodel[0]->name : '';
						$cartrim           = wp_get_post_terms( $car_id, 'car_trim' );
						$car_trim          = ( ! is_wp_error( $cartrim ) && ! empty( $cartrim ) ) ? $cartrim[0]->name : '';
						$carvin            = wp_get_post_terms( $car_id, 'car_vin_number' );
						$car_vin           = ( ! is_wp_error( $carvin ) && ! empty( $carvin ) ) ? $carvin[0]->name : '';
						$carstock          = wp_get_post_terms( $car_id, 'car_stock_number' );
						$car_stock         = ( ! is_wp_error( $carstock ) && ! empty( $carstock ) ) ? $carstock[0]->name : '';
						$regularprice      = get_post_meta( $car_id, 'regular_price', true );
						$car_regular_price = ( ! empty( $regularprice ) ) ? $regularprice : '';
						$saleprice         = get_post_meta( $car_id, 'sale_price', true );
						$car_sale_price    = ( ! empty( $saleprice ) ) ? $saleprice : '';

						update_field( 'car_id', $car_id, $post_id );
						update_field( 'car_year_inq', $car_year, $post_id );
						update_field( 'car_make_inq', $car_make, $post_id );
						update_field( 'car_model_inq', $car_model, $post_id );
						update_field( 'car_trim_inq', $car_trim, $post_id );
						update_field( 'vin_number', $car_vin, $post_id );
						update_field( 'stock_number', $car_stock, $post_id );
						update_field( 'regular_price', $car_regular_price, $post_id );
						update_field( 'sale_price', $car_sale_price, $post_id );
					}
					update_field( 'first_name', $first_name, $post_id );
					update_field( 'last_name', $last_name, $post_id );
					update_field( 'email', $email, $post_id );
					update_field( 'mobile', $mobile, $post_id );
					update_field( 'address', $address, $post_id );
					update_field( 'state', $state, $post_id );
					update_field( 'zip', $zip, $post_id );
					update_field( 'preferred_contact', $preferred_contact, $post_id );
					update_field( 'test_drive', $test_drive, $post_id );
					update_field( 'date', $date, $post_id );
					update_field( 'time', $time, $post_id );

					// MAIL STARTS.
					$from_name     = empty( $car_dealer_options['std_mail_from_name'] ) ? 'no-reply@' . sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : $car_dealer_options['std_mail_from_name'];
					$from_mail     = empty( $car_dealer_options['std_mail_id_from'] ) ? 'no-reply@' . sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : $car_dealer_options['std_mail_id_from'];
					$reply_to_mail = empty( $email ) ? 'no-reply@' . sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : $email;
					$reply_to_name = empty( $first_name ) ? 'User' : $first_name;
					$subject       = empty( $car_dealer_options['std_subject'] ) ? esc_html__( 'Test Drive Inquiry Mail', 'cardealer-helper' ) : $car_dealer_options['std_subject'];

					$headers    = 'From: ' . strip_tags( $from_name ) . ' <' . $from_mail . ">\r\n";
					$headers   .= 'Reply-To: ' . strip_tags( $reply_to_name ) . ' <' . $reply_to_mail . ">\r\n";
					$mail_error = 0;

					// PREPARE PRODUCT DETAIL FOR MAIL.
					$adf_data   = '';
					$plain_mail = '';
					$product    = '';
					$car_id     = sanitize_text_field( wp_unslash( $_POST['car_id'] ) );
					if ( isset( $car_id ) && ! empty( $car_id ) ) {
						$product    = cdhl_get_html_mail_body( $car_id );
						$plain_mail = cdhl_get_text_mail_body( $car_id );

						// SET MAIL BODY FOR ADF MAIL.
						$adf_data .= '<?xml version="1.0"?>' . PHP_EOL;
						$adf_data .= '<?ADF VERSION="1.0"?>' . PHP_EOL;
						$adf_data .= '<adf>' . PHP_EOL;
						$adf_data .= '<prospect>' . PHP_EOL;
						$adf_data .= '<requestdate>' . $request_date . '</requestdate>' . PHP_EOL;
						$adf_data .= '<vehicle>' . PHP_EOL;
						$adf_data .= '<year>' . $car_year . '</year>' . PHP_EOL;
						$adf_data .= '<make>' . $car_make . '</make>' . PHP_EOL;
						$adf_data .= '<model>' . $car_model . '</model>' . PHP_EOL;
						$adf_data .= '<vin>' . $car_vin . '</vin>' . PHP_EOL;
						$adf_data .= '<stock>' . $car_stock . '</stock>' . PHP_EOL;
						$adf_data .= '<trim>' . $car_trim . '</trim>' . PHP_EOL;
						$adf_data .= '</vehicle>' . PHP_EOL;
						$adf_data .= '<customer>' . PHP_EOL;
						$adf_data .= '<contact>' . PHP_EOL;
						$adf_data .= '<name part="full">' . $first_name . ' ' . $last_name . '</name>' . PHP_EOL;
						$adf_data .= '<email>' . $email . '</email>' . PHP_EOL;
						$adf_data .= '<phone type="voice" time="day">' . $mobile . '</phone>' . PHP_EOL;
						$adf_data .= '<address>' . PHP_EOL;
						$adf_data .= '<street line="1">' . $address . '</street>' . PHP_EOL;
						$adf_data .= '<postalcode>' . $zip . '</postalcode>' . PHP_EOL;
						$adf_data .= '</address>' . PHP_EOL;
						$adf_data .= '</contact>' . PHP_EOL;
						$adf_data .= '<comments>' . PHP_EOL;
						$schedule  = '';
						$schedule .= esc_html__( 'Inquiry Information: ', 'cardealer-helper' ) . PHP_EOL . PHP_EOL;
						$schedule .= cardealer_get_theme_option( 'cstfrm_lbl_first_name', esc_html__( 'First Name', 'cardealer-helper' ) ) . ' :' . $first_name . PHP_EOL;
						$schedule .= cardealer_get_theme_option( 'cstfrm_lbl_last_name', esc_html__( 'Last Name', 'cardealer-helper' ) ) . ' :' . $last_name . PHP_EOL;
						$schedule .= cardealer_get_theme_option( 'cstfrm_lbl_email', esc_html__( 'Email', 'cardealer-helper' ) ) . ' :' . $email . PHP_EOL;
						$schedule .= cardealer_get_theme_option( 'cstfrm_lbl_mobile', esc_html__( 'Mobile', 'cardealer-helper' ) ) . ' :' . $mobile . PHP_EOL;
						$schedule .= cardealer_get_theme_option( 'cstfrm_lbl_address', esc_html__( 'Address', 'cardealer-helper' ) ) . ' :' . $address . PHP_EOL;
						$schedule .= cardealer_get_theme_option( 'cstfrm_lbl_state', esc_html__( 'State', 'cardealer-helper' ) ) . ' :' . $state . PHP_EOL;
						$schedule .= cardealer_get_theme_option( 'cstfrm_lbl_zip', esc_html__( 'Zip', 'cardealer-helper' ) ) . ' :' . $zip . PHP_EOL;

						if ( 'email' === (string) $preferred_contact ) {
							$schedule .= cardealer_get_theme_option( 'cstfrm_lbl_preferred_contact', esc_html__( 'Preferred Contact', 'cardealer-helper' ) ) . ' :' . cardealer_get_theme_option( 'cstfrm_lbl_email', esc_html__( 'Email', 'cardealer-helper' ) ) . PHP_EOL;
						} else {
							$schedule .= cardealer_get_theme_option( 'cstfrm_lbl_preferred_contact', esc_html__( 'Preferred Contact', 'cardealer-helper' ) ) . ' :' . cardealer_get_theme_option( 'cstfrm_lbl_phone', esc_html__( 'Phone', 'cardealer-helper' ) ) . PHP_EOL;
						}

						if ( 'yes' === (string) $test_drive ) {
							$schedule .= cardealer_get_theme_option( 'cstfrm_lbl_test_drive', esc_html__( 'Test Drive?', 'cardealer-helper' ) ) . ' :' . esc_html__( 'Yes', 'cardealer-helper' ) . PHP_EOL;
							$schedule .= cardealer_get_theme_option( 'cstfrm_lbl_date', esc_html__( 'Date', 'cardealer-helper' ) ) . ' :' . $date . PHP_EOL;
							$schedule .= cardealer_get_theme_option( 'cstfrm_lbl_time', esc_html__( 'Time', 'cardealer-helper' ) ) . ' :' . $time . PHP_EOL;
						} else {
							$schedule .= cardealer_get_theme_option( 'cstfrm_lbl_test_drive', esc_html__( 'Test Drive?', 'cardealer-helper' ) ) . ' :' . esc_html__( 'No', 'cardealer-helper' ) . PHP_EOL;
						}
											$adf_data     .= $schedule . PHP_EOL;
											$adf_data     .= '</comments>' . PHP_EOL;
										$adf_data         .= '</customer>' . PHP_EOL;
										$adf_data         .= '<vendor>' . PHP_EOL;
											$adf_data     .= '<contact>' . PHP_EOL;
												$adf_data .= '<name part="full">' . $car_dealer_options['std_mail_from_name'] . '</name>' . PHP_EOL;
											$adf_data     .= '</contact>' . PHP_EOL;
										$adf_data         .= '</vendor>' . PHP_EOL;
									$adf_data             .= '</prospect>' . PHP_EOL;
									$adf_data             .= '</adf>' . PHP_EOL;
					}

					// Sending ADF Mail.
					if ( isset( $car_dealer_options['std_adf_mail'] ) && 'off' !== $car_dealer_options['std_adf_mail'] ) {
						if ( isset( $car_dealer_options['std_adf_mail_to'] ) && ! empty( $car_dealer_options['std_adf_mail_to'] ) ) {
							$mail_set     = 1;
							$dealer_email = cdhl_get_dealer_mail( $car_id ); // Get dealer email id.

							$to = ! empty( $dealer_email ) ? $dealer_email : $car_dealer_options['std_adf_mail_to'];

							// Mail body.
							$adf_headers  = $headers;
							$adf_headers .= "MIME-Version: 1.0\r\n";
							$adf_headers .= "content-type: text/plain; charset=UTF-8\r\n";
							if ( ! wp_mail( $to, $subject, $adf_data, $adf_headers ) ) {
								$mail_error = 1;
							}
						}
					}

					// REPLACE TAGS WITH ACTUAL FIEDLS.
					$test_drive        = ( 'yes' === (string) $test_drive ) ? esc_html__( 'Yes', 'cardealer-helper' ) : esc_html__( 'No', 'cardealer-helper' );
					$preferred_contact = ( 'email' === (string) $preferred_contact ) ? esc_html__( 'Email', 'cardealer-helper' ) : esc_html__( 'Phone', 'cardealer-helper' );
					$fields            = array(
						'CD_FROM_NAME'         => $from_name,
						'CD_FIRST_NAME'        => $first_name,
						'CD_LAST_NAME'         => $last_name,
						'CD_EMAIL'             => $email,
						'CD_MOBILE'            => $mobile,
						'CD_ADDRESS'           => $address,
						'CD_STATE'             => $state,
						'CD_ZIP'               => $zip,
						'CD_PREFERRED_CONTACT' => $preferred_contact,
						'CD_TEST_DRIVE'        => $test_drive,
						'CD_DATE'              => $date,
						'CD_TIME'              => $time,
					);

					// Sending HTML Mail.
					if ( isset( $car_dealer_options['std_html_mail'] ) && 'off' !== $car_dealer_options['std_html_mail'] ) {
						if ( isset( $car_dealer_options['std_html_mail_to'] ) && ! empty( $car_dealer_options['std_html_mail_to'] ) ) {
							$mail_set     = 1;
							$dealer_email = cdhl_get_dealer_mail( $car_id ); // Get dealer email id.

							$to = ! empty( $dealer_email ) ? $dealer_email : $car_dealer_options['std_html_mail_to'];

							// Mail body.
							if ( isset( $car_dealer_options['sstd_html_body'] ) && ! empty( $car_dealer_options['sstd_html_body'] ) ) {
								$std_html_body            = $car_dealer_options['sstd_html_body'];
								$fields['PRODUCT_DETAIL'] = $product;

								foreach ( $fields as $tag => $value ) {
									if ( strpos( $std_html_body, '#' . $tag . '#' ) !== false ) {
										$std_html_body = str_replace( '#' . $tag . '#', $value, $std_html_body );
									}
								}
							} else {
								$std_html_body = esc_html__( 'One Inquiry Received', 'cardealer-helper' );
							}

							$html_headers  = $headers;
							$html_headers .= "MIME-Version: 1.0\r\n";
							$html_headers .= "Content-Type: text/html; charset=UTF-8\r\n";
							if ( ! wp_mail( $to, $subject, $std_html_body, $html_headers ) ) {
								$mail_error = 1;
							}
						}
					}

					// Sending Text Mail.
					if ( isset( $car_dealer_options['std_txt_mail'] ) && 'off' !== $car_dealer_options['std_txt_mail'] ) {
						if ( isset( $car_dealer_options['std_txt_mail_to'] ) && ! empty( $car_dealer_options['std_txt_mail_to'] ) ) {
							$mail_set     = 1;
							$dealer_email = cdhl_get_dealer_mail( $car_id ); // Get dealer email id.

							$to = ! empty( $dealer_email ) ? $dealer_email : $car_dealer_options['std_txt_mail_to'];

							// Mail body.
							if ( isset( $car_dealer_options['std_txt_body'] ) && ! empty( $car_dealer_options['std_txt_body'] ) ) {
								$std_txt_body = $car_dealer_options['std_txt_body'];

								$fields['PRODUCT_DETAIL'] = $plain_mail;
								foreach ( $fields as $tag => $value ) {
									if ( strpos( $std_txt_body, '#' . $tag . '#' ) !== false ) {
										$std_txt_body = str_replace( '#' . $tag . '#', $value, $std_txt_body );
									}
								}
							} else {
								$std_txt_body = esc_html__( 'One Inquiry Received', 'cardealer-helper' );
							}

							$text_headers  = $headers;
							$text_headers .= "MIME-Version: 1.0\r\n";
							$text_headers .= "content-type: text/plain; charset=UTF-8\r\n";
							if ( ! wp_mail( $to, $subject, $std_txt_body, $text_headers ) ) {
								$mail_error = 1;
							}
						}
					}
					if ( ( isset( $car_dealer_options['std_adf_mail'] ) && 'off' !== $car_dealer_options['std_adf_mail'] ) || ( isset( $car_dealer_options['std_html_mail'] ) && 'off' !== $car_dealer_options['std_html_mail'] ) || ( isset( $car_dealer_options['std_txt_mail'] ) && 'off' !== $car_dealer_options['std_txt_mail'] ) ) {
						// If not mail is set from admin form options then mail will sent to admin.
						if ( 0 === (int) $mail_set ) {
							$html_headers  = $headers;
							$html_headers .= "MIME-Version: 1.0\r\n";
							$html_headers .= "Content-Type: text/html; charset=UTF-8\r\n";
							$dealer_email  = cdhl_get_dealer_mail( $car_id ); // Get dealer email id.

							$to = ! empty( $dealer_email ) ? $dealer_email : get_option( 'admin_email' );
							// Mail body.
							if ( isset( $car_dealer_options['sstd_html_body'] ) && ! empty( $car_dealer_options['sstd_html_body'] ) ) {
								$std_html_body            = $car_dealer_options['sstd_html_body'];
								$fields['PRODUCT_DETAIL'] = $product;

								foreach ( $fields as $tag => $value ) {
									if ( strpos( $std_html_body, '#' . $tag . '#' ) !== false ) {
										$std_html_body = str_replace( '#' . $tag . '#', $value, $std_html_body );
									}
								}
							} else {
								$std_html_body = esc_html__( 'One Inquiry Received', 'cardealer-helper' );
							}
							if ( ! wp_mail( $to, $subject, $std_html_body, $html_headers ) ) {
								$mail_error = 1;
							}
						}

						if ( 1 === (int) $mail_error ) {
							$errors[] = esc_html__( 'Sorry there was an error sending your message. Please try again later.', 'cardealer-helper' );
						} else {
							$response_array = array(
								'status' => esc_html__( '1', 'cardealer-helper' ),
								'msg'    => '<div class="alert alert-success">' . esc_html__( 'Request sent successfully', 'cardealer-helper' ) . '</div>',
							);
						}
					} else {
						$response_array = array(
							'status' => esc_html__( '1', 'cardealer-helper' ),
							'msg'    => '<div class="alert alert-success">' . esc_html__( 'Request sent successfully', 'cardealer-helper' ) . '</div>',
						);
					}
				} else {
					$errors[] = esc_html__( 'Something went wrong, please try again later!', 'cardealer-helper' );
				}
			}
			if ( ! empty( $errors ) ) {
				$err = '';
				foreach ( $errors as $error ) {
					$err .= '<p>' . $error . '</p>';
				}
				$response_array = array(
					'status' => 'error',
					'msg'    => '<div class="alert alert-danger">' . $err . '</div>',
				);
			}
		}
		echo wp_json_encode( $response_array );
		die;
	}
}

/* Get Schedule Test Drive Personal info */
add_filter(
	'wp_privacy_personal_data_exporters',
	'cdhl_register_std_lead_info_exporter',
	10
);

if ( ! function_exists( 'cdhl_register_std_lead_info_exporter' ) ) {
	/**
	 * Register STD lead
	 *
	 * @param array $exporters array variable.
	 */
	function cdhl_register_std_lead_info_exporter( $exporters ) {
		$exporters['cardealer-helper-std-exporter'] = array(
			'exporter_friendly_name' => esc_html__( 'Schedule Test Drive Lead Data', 'cardealer-helper' ),
			'callback'               => 'cdhl_gdpr_export_std_inq_lead_info',
		);
		return $exporters;
	}
}
if ( ! function_exists( 'cdhl_gdpr_export_std_inq_lead_info' ) ) {
	/**
	 * GDPR export
	 *
	 * @param string $email_address email id.
	 */
	function cdhl_gdpr_export_std_inq_lead_info( $email_address ) {
		if ( empty( $email_address ) ) {
			return;
		}
		return cdhl_gdpr_export_get_lead_info( $email_address, 'schedule_test_drive' );
	}
}


// Erase data.
if ( ! function_exists( 'cdhl_schedule_test_drive_eraser' ) ) {
	/**
	 * Schedule test drive
	 *
	 * @param string $email_address email id.
	 */
	function cdhl_schedule_test_drive_eraser( $email_address ) {
		if ( empty( $email_address ) ) {
			return;
		}
		$number = 500; // Limit us to avoid timing out.
		global $wpdb;

		$lead_query = "SELECT DISTINCT(cdhl_meta.post_id)
		FROM $wpdb->postmeta cdhl_meta
		JOIN $wpdb->posts cdhl_posts ON cdhl_meta.post_id = cdhl_posts.ID
		WHERE cdhl_posts.post_type = 'schedule_test_drive'
		AND cdhl_meta.meta_value = '$email_address'";
		$post_ids   = $wpdb->get_results( $lead_query, ARRAY_A ); // get post ids.

		$items_removed = false;
		if ( ! empty( $post_ids ) ) {
			foreach ( $post_ids as $id ) {
				$std_post = array(
					'ID'         => $id['post_id'],
					'post_title' => esc_html__( 'Anonymous', 'cardealer-helper' ),
				);
				wp_update_post( $std_post );

				$first_name = get_post_meta( $id['post_id'], 'first_name', true );
				if ( ! empty( $first_name ) ) {
					update_post_meta( $id['post_id'], 'first_name', esc_html__( 'Anonymous', 'cardealer-helper' ) );
					$items_removed = true;
				}
				$last_name = get_post_meta( $id['post_id'], 'last_name', true );
				if ( ! empty( $last_name ) ) {
					update_post_meta( $id['post_id'], 'last_name', esc_html__( 'Anonymous', 'cardealer-helper' ) );
					$items_removed = true;
				}
				$email = get_post_meta( $id['post_id'], 'email', true );
				if ( ! empty( $email ) ) {
					update_post_meta( $id['post_id'], 'email', 'deleted@site.invalid' );
					$items_removed = true;
				}
				$mobile = get_post_meta( $id['post_id'], 'mobile', true );
				if ( ! empty( $mobile ) ) {
					update_post_meta( $id['post_id'], 'mobile', 'xxxxxxxxxx' );
					$items_removed = true;
				}
				$address = get_post_meta( $id['post_id'], 'address', true );
				if ( ! empty( $address ) ) {
					update_post_meta( $id['post_id'], 'address', '' );
					$items_removed = true;
				}
				$state = get_post_meta( $id['post_id'], 'state', true );
				if ( ! empty( $state ) ) {
					update_post_meta( $id['post_id'], 'state', '' );
					$items_removed = true;
				}
				$zip = get_post_meta( $id['post_id'], 'zip', true );
				if ( ! empty( $zip ) ) {
					update_post_meta( $id['post_id'], 'zip', '' );
					$items_removed = true;
				}
			}
		}

		// Tell core if we have more inquiries to work on still.
		$done = count( $post_ids ) < $number;
		return array(
			'items_removed'  => $items_removed,
			'items_retained' => false, // always false in this example.
			'messages'       => array(), // no messages in this example.
			'done'           => $done,
		);
	}
}

