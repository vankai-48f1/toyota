<?php
/**
 * This function use to store new inquiries.
 *
 * @package car-dealer-helper
 */

add_action( 'wp_ajax_car_inquiry_action', 'cdhl_car_inquiry_action' );
add_action( 'wp_ajax_nopriv_car_inquiry_action', 'cdhl_car_inquiry_action' );
if ( ! function_exists( 'cdhl_car_inquiry_action' ) ) {
	/**
	 * Car inquiry action
	 */
	function cdhl_car_inquiry_action() {
		global $car_dealer_options;
		$response_array = array();
		if ( isset( $_POST['action'] ) && 'car_inquiry_action' === $_POST['action'] ) {
			$captcha            = isset( $_POST['g-recaptcha-response'] ) ? sanitize_text_field( wp_unslash( $_POST['g-recaptcha-response'] ) ) : '';
			$recaptcha_response = cardealer_validate_google_captch( $captcha );
			$first_name         = isset( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : '';
			$last_name          = isset( $_POST['last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) : '';
			$state              = isset( $_POST['state'] ) ? sanitize_text_field( wp_unslash( $_POST['state'] ) ) : '';
			$zip                = isset( $_POST['zip'] ) ? sanitize_text_field( wp_unslash( $_POST['zip'] ) ) : '';
			$contact            = isset( $_POST['contact'] ) ? sanitize_text_field( wp_unslash( $_POST['contact'] ) ) : '';
			$email              = isset( $_POST['email'] ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';
			$mobile             = isset( $_POST['mobile'] ) ? sanitize_text_field( wp_unslash( $_POST['mobile'] ) ) : '';
			$address            = isset( $_POST['address'] ) ? sanitize_text_field( wp_unslash( $_POST['address'] ) ) : '';
			$car_id             = isset( $_POST['car_id'] ) ? sanitize_text_field( wp_unslash( $_POST['car_id'] ) ) : '';
			$author_id          = get_post_field( 'post_author', $car_id );
			$user               = get_userdata( $author_id );
			$user_roles         = $user->roles;
			$from_name          = $user->display_name;

			if ( in_array( 'administrator', $user_roles, true ) ) {
				$from_name = $car_dealer_options['inq_mail_from_name'];
			}

			if ( false === (bool) $recaptcha_response['success'] ) {
				$errors[] = esc_html__( 'Please check the captcha form', 'cardealer-helper' );
			} elseif ( ! isset( $_POST['rmi_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['rmi_nonce'] ), 'req-info-form' ) ) {
				$errors[] = esc_html__( 'Sorry, Something went wrong. Refresh Page and try again.', 'cardealer-helper' );
			} else {
				$mail_set     = 0;
				$request_date = ( isset( $recaptcha_response['challenge_ts'] ) && ! empty( $recaptcha_response['challenge_ts'] ) ) ? $recaptcha_response['challenge_ts'] : '';
				$post_id      = wp_insert_post(
					array(
						'post_status'  => 'publish',
						'post_type'    => 'pgs_inquiry',
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
					update_field( 'contact', $contact, $post_id );

					// MAIL STARTS.
					$from_name     = empty( $car_dealer_options['inq_mail_from_name'] ) ? 'no-reply@' . sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : $car_dealer_options['inq_mail_from_name'];
					$from_mail     = empty( $car_dealer_options['inq_mail_id_from'] ) ? 'no-reply@' . sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : $car_dealer_options['inq_mail_id_from'];
					$reply_to_mail = empty( $email ) ? 'no-reply@' . sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : $email;
					$reply_to_name = empty( $first_name ) ? 'User' : $first_name;
					$subject       = empty( $car_dealer_options['inq_subject'] ) ? esc_html__( 'Test Drive Inquiry Mail', 'cardealer-helper' ) : $car_dealer_options['inq_subject'];

					$headers    = 'From: ' . strip_tags( $from_name ) . ' <' . $from_mail . ">\r\n";
					$headers   .= 'Reply-To: ' . strip_tags( $reply_to_name ) . ' <' . $reply_to_mail . ">\r\n";
					$mail_error = 0;

					// PREPARE PRODUCT DETAIL FOR MAIL.
					$adf_data   = '';
					$plain_mail = '';
					$product    = '';
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
						$inquiry   = '';
						$inquiry  .= esc_html__( 'Request More Inquiry Information : ', 'cardealer-helper' ) . PHP_EOL . PHP_EOL;
						$inquiry  .= cardealer_get_theme_option( 'cstfrm_lbl_first_name', esc_html__( 'First Name', 'cardealer-helper' ) ) . ' :' . $first_name . PHP_EOL;
						$inquiry  .= cardealer_get_theme_option( 'cstfrm_lbl_last_name', esc_html__( 'Last Name', 'cardealer-helper' ) ) . ' :' . $last_name . PHP_EOL;
						$inquiry  .= cardealer_get_theme_option( 'cstfrm_lbl_email', esc_html__( 'Email', 'cardealer-helper' ) ) . ' :' . $email . PHP_EOL;
						$inquiry  .= cardealer_get_theme_option( 'cstfrm_lbl_mobile', esc_html__( 'Mobile', 'cardealer-helper' ) ) . ' :' . $mobile . PHP_EOL;
						$inquiry  .= cardealer_get_theme_option( 'cstfrm_lbl_address', esc_html__( 'Address', 'cardealer-helper' ) ) . ' :' . $address . PHP_EOL;
						$inquiry  .= cardealer_get_theme_option( 'cstfrm_lbl_state', esc_html__( 'State', 'cardealer-helper' ) ) . ' :' . $state . PHP_EOL;
						$inquiry  .= cardealer_get_theme_option( 'cstfrm_lbl_zip', esc_html__( 'Zip', 'cardealer-helper' ) ) . ' :' . $zip . PHP_EOL;
						if ( 'email' === $contact ) {
							$inquiry .= cardealer_get_theme_option( 'cstfrm_lbl_preferred_contact', esc_html__( 'Preferred Contact', 'cardealer-helper' ) ) . ' :' . cardealer_get_theme_option( 'cstfrm_lbl_email', esc_html__( 'Email', 'cardealer-helper' ) ) . PHP_EOL;
						} else {
							$inquiry .= cardealer_get_theme_option( 'cstfrm_lbl_preferred_contact', esc_html__( 'Preferred Contact', 'cardealer-helper' ) ) . ' :' . cardealer_get_theme_option( 'cstfrm_lbl_phone', esc_html__( 'Phone', 'cardealer-helper' ) ) . PHP_EOL;
						}
						$adf_data .= $inquiry . PHP_EOL;
						$adf_data .= '</comments>' . PHP_EOL;
						$adf_data .= '</customer>' . PHP_EOL;
						$adf_data .= '<vendor>' . PHP_EOL;
						$adf_data .= '<contact>' . PHP_EOL;
						$adf_data .= '<name part="full">' . $car_dealer_options['inq_mail_from_name'] . '</name>' . PHP_EOL;
						$adf_data .= '</contact>' . PHP_EOL;
						$adf_data .= '</vendor>' . PHP_EOL;
						$adf_data .= '</prospect>' . PHP_EOL;
						$adf_data .= '</adf>' . PHP_EOL;
					} else {
						// SET MAIL BODY FOR ADF MAIL.
						$adf_data .= '<?xml version="1.0"?>' . PHP_EOL;
						$adf_data .= '<?ADF VERSION="1.0"?>' . PHP_EOL;
						$adf_data .= '<adf>' . PHP_EOL;
						$adf_data .= '<prospect>' . PHP_EOL;
						$adf_data .= '<requestdate>' . $request_date . '</requestdate>' . PHP_EOL;
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
						$inquiry   = '';
						$inquiry  .= esc_html__( 'Request More Inquiry Information : ', 'cardealer-helper' ) . PHP_EOL . PHP_EOL;
						$inquiry  .= cardealer_get_theme_option( 'cstfrm_lbl_first_name', esc_html__( 'First Name', 'cardealer-helper' ) ) . ' :' . $first_name . PHP_EOL;
						$inquiry  .= cardealer_get_theme_option( 'cstfrm_lbl_last_name', esc_html__( 'Last Name', 'cardealer-helper' ) ) . ' :' . $last_name . PHP_EOL;
						$inquiry  .= cardealer_get_theme_option( 'cstfrm_lbl_email', esc_html__( 'Email', 'cardealer-helper' ) ) . ' :' . $email . PHP_EOL;
						$inquiry  .= cardealer_get_theme_option( 'cstfrm_lbl_mobile', esc_html__( 'Mobile', 'cardealer-helper' ) ) . ' :' . $mobile . PHP_EOL;
						$inquiry  .= cardealer_get_theme_option( 'cstfrm_lbl_address', esc_html__( 'Address', 'cardealer-helper' ) ) . ' :' . $address . PHP_EOL;
						$inquiry  .= cardealer_get_theme_option( 'cstfrm_lbl_state', esc_html__( 'State', 'cardealer-helper' ) ) . ' :' . $state . PHP_EOL;
						$inquiry  .= cardealer_get_theme_option( 'cstfrm_lbl_zip', esc_html__( 'Zip', 'cardealer-helper' ) ) . ' :' . $zip . PHP_EOL;
						if ( 'email' === $contact ) {
							$inquiry .= cardealer_get_theme_option( 'cstfrm_lbl_preferred_contact', esc_html__( 'Preferred Contact', 'cardealer-helper' ) ) . ' :' . cardealer_get_theme_option( 'cstfrm_lbl_email', esc_html__( 'Email', 'cardealer-helper' ) ) . PHP_EOL;
						} else {
							$inquiry .= cardealer_get_theme_option( 'cstfrm_lbl_preferred_contact', esc_html__( 'Preferred Contact', 'cardealer-helper' ) ) . ' :' . cardealer_get_theme_option( 'cstfrm_lbl_phone', esc_html__( 'Phone', 'cardealer-helper' ) ) . PHP_EOL;
						}
						$adf_data .= $inquiry . PHP_EOL;
						$adf_data .= '</comments>' . PHP_EOL;
						$adf_data .= '</customer>' . PHP_EOL;
						$adf_data .= '</prospect>' . PHP_EOL;
						$adf_data .= '</adf>' . PHP_EOL;
					}

					// Sending ADF Mail.
					if ( isset( $car_dealer_options['inq_adf_mail'] ) && 'off' !== $car_dealer_options['inq_adf_mail'] ) {
						if ( isset( $car_dealer_options['inq_adf_mail_to'] ) && ! empty( $car_dealer_options['inq_adf_mail_to'] ) ) {
							$mail_set = 1;

							$dealer_email = cdhl_get_dealer_mail( $car_id ); // Get dealer email id.

							$to = ! empty( $dealer_email ) ? $dealer_email : $car_dealer_options['inq_adf_mail_to'];
							// Mail body.
							$adf_headers  = $headers;
							$adf_headers .= "MIME-Version: 1.0\r\n";
							$adf_headers .= "content-type: text/plain; charset=UTF-8\r\n";

							if ( ! wp_mail( $to, $subject, $adf_data, $adf_headers ) ) {
								$mail_error = 1;
							}
						}
					}

					// Mail body.
					$contact = ( 'email' === $contact ) ? esc_html__( 'Email', 'cardealer-helper' ) : esc_html__( 'Phone', 'cardealer-helper' );

					$fields = array(
						'CD_FROM_NAME'         => $from_name,
						'CD_FIRST_NAME'        => $first_name,
						'CD_LAST_NAME'         => $last_name,
						'CD_EMAIL'             => $email,
						'CD_MOBILE'            => $mobile,
						'CD_ADDRESS'           => $address,
						'CD_STATE'             => $state,
						'CD_ZIP'               => $zip,
						'CD_PREFERRED_CONTACT' => $contact,
					);

					// Sending HTML Mail.
					if ( isset( $car_dealer_options['inq_html_mail'] ) && 'off' !== $car_dealer_options['inq_html_mail'] ) {
						if ( isset( $car_dealer_options['inq_html_mail_to'] ) && ! empty( $car_dealer_options['inq_html_mail_to'] ) ) {
							$mail_set     = 1;
							$dealer_email = cdhl_get_dealer_mail( $car_id ); // Get dealer email id.

							$to = ! empty( $dealer_email ) ? $dealer_email : $car_dealer_options['inq_html_mail_to'];
							// Mail body.
							if ( isset( $car_id ) && ! empty( $car_id ) ) {
								$html_mail_body           = $car_dealer_options['req_info_html_mail_body'];
								$fields['PRODUCT_DETAIL'] = $product;
							} else {
								$html_mail_body = $car_dealer_options['inq_wid_html_mail_body'];
							}

							if ( isset( $html_mail_body ) && ! empty( $html_mail_body ) ) {
								$inq_html_body = $html_mail_body;

								foreach ( $fields as $tag => $value ) {
									if ( strpos( $inq_html_body, '#' . $tag . '#' ) !== false ) {
										$inq_html_body = str_replace( '#' . $tag . '#', $value, $inq_html_body );
									}
								}
							} else {
								$inq_html_body = esc_html__( 'One Inquiry Received', 'cardealer-helper' );
							}

							$html_headers  = $headers;
							$html_headers .= "MIME-Version: 1.0\r\n";
							$html_headers .= "Content-Type: text/html; charset=UTF-8\r\n";

							if ( ! wp_mail( $to, $subject, $inq_html_body, $html_headers ) ) {
								$mail_error = 1;
							}
						}
					}

					// Sending Text Mail.
					if ( isset( $car_dealer_options['inq_text_mail'] ) && 'off' !== $car_dealer_options['inq_text_mail'] ) {
						if ( isset( $car_dealer_options['inq_text_mail_to'] ) && ! empty( $car_dealer_options['inq_text_mail_to'] ) ) {
							$mail_set     = 1;
							$dealer_email = cdhl_get_dealer_mail( $car_id ); // Get dealer email id.

							$to = ! empty( $dealer_email ) ? $dealer_email : $car_dealer_options['inq_text_mail_to'];

							// Mail body.
							if ( isset( $car_id ) && ! empty( $car_id ) ) {
								$text_mail_body           = $car_dealer_options['req_info_text_mail_body'];
								$fields['PRODUCT_DETAIL'] = $plain_mail;
							} else {
								$text_mail_body = $car_dealer_options['inq_wid_text_mail_body'];
							}
							if ( isset( $text_mail_body ) && ! empty( $text_mail_body ) ) {
								$inq_txt_body = $text_mail_body;

								foreach ( $fields as $tag => $value ) {
									if ( strpos( $inq_txt_body, '#' . $tag . '#' ) !== false ) {
										$inq_txt_body = str_replace( '#' . $tag . '#', $value, $inq_txt_body );
									}
								}
							} else {
								$inq_txt_body = esc_html__( 'One Inquiry Received', 'cardealer-helper' );
							}

							$text_headers  = $headers;
							$text_headers .= "MIME-Version: 1.0\r\n";
							$text_headers .= "content-type: text/plain; charset=UTF-8\r\n";
							if ( ! wp_mail( $to, $subject, $inq_txt_body, $text_headers ) ) {
								$mail_error = 1;
							}
						}
					}

					if ( ( isset( $car_dealer_options['inq_adf_mail'] ) && 'off' !== $car_dealer_options['inq_adf_mail'] ) || ( isset( $car_dealer_options['inq_html_mail'] ) && 'off' !== $car_dealer_options['inq_html_mail'] ) || ( isset( $car_dealer_options['inq_text_mail'] ) && 'off' !== $car_dealer_options['inq_text_mail'] ) ) {
						// If not mail is set from admin form options then mail will sent to admin ( if no dealer is available ).
						if ( 0 === (int) $mail_set ) {
							$html_headers  = $headers;
							$html_headers .= "MIME-Version: 1.0\r\n";
							$html_headers .= "Content-Type: text/html; charset=UTF-8\r\n";
							$dealer_email  = cdhl_get_dealer_mail( $car_id ); // Get dealer email id.

							$to = ! empty( $dealer_email ) ? $dealer_email : get_option( 'admin_email' );

							// Mail body.
							if ( isset( $car_dealer_options['req_info_html_mail_body'] ) && ! empty( $car_dealer_options['req_info_html_mail_body'] ) ) {
								$inq_html_body = $car_dealer_options['req_info_html_mail_body'];
								( isset( $car_id ) && ! empty( $car_id ) ) ? $fields['PRODUCT_DETAIL'] = $product : $fields['PRODUCT_DETAIL'] = '';

								foreach ( $fields as $tag => $value ) {
									if ( strpos( $inq_html_body, '#' . $tag . '#' ) !== false ) {
										$inq_html_body = str_replace( '#' . $tag . '#', $value, $inq_html_body );
									}
								}
							} else {
								$inq_html_body = esc_html__( 'One Inquiry Received', 'cardealer-helper' );
							}

							$inq_html_body = 'Car Dealer Inquiry Received';

							if ( ! wp_mail( $to, $subject, $inq_html_body, $html_headers ) ) {
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

/**
 * ******==  GDPR  ==*******
 * Get Inquiry Personal info
 */
add_filter(
	'wp_privacy_personal_data_exporters',
	'cdhl_register_lead_info_exporter',
	10
);

if ( ! function_exists( 'cdhl_register_lead_info_exporter' ) ) {
	/**
	 * Register lead info exporter
	 *
	 * @param array $exporters array variable.
	 */
	function cdhl_register_lead_info_exporter( $exporters ) {
		$exporters['cardealer-helper-inq-exporter'] = array(
			'exporter_friendly_name' => esc_html__( 'Inquiry Lead data', 'cardealer-helper' ),
			'callback'               => 'cdhl_gdpr_export_inq_lead_info',
		);
		return $exporters;
	}
}

if ( ! function_exists( 'cdhl_gdpr_export_inq_lead_info' ) ) {
	/**
	 * Export inqury lead info
	 *
	 * @param string $email_address email address.
	 */
	function cdhl_gdpr_export_inq_lead_info( $email_address ) {
		if ( empty( $email_address ) ) {
			return;
		}
		return cdhl_gdpr_export_get_lead_info( $email_address, 'pgs_inquiry' );
	}
}


// Erase data.
if ( ! function_exists( 'cdhl_pgs_inquiry_eraser' ) ) {
	/**
	 * PGS inquiry
	 *
	 * @param string $email_address email address.
	 */
	function cdhl_pgs_inquiry_eraser( $email_address ) {
		if ( empty( $email_address ) ) {
			return;
		}
		$number = 500; // Limit us to avoid timing out.
		global $wpdb;

		$lead_query = "SELECT DISTINCT(cdhl_meta.post_id)
		FROM $wpdb->postmeta cdhl_meta
		JOIN $wpdb->posts cdhl_posts ON cdhl_meta.post_id = cdhl_posts.ID
		WHERE cdhl_posts.post_type = 'pgs_inquiry'
		AND cdhl_meta.meta_value = '$email_address'";
		$post_ids   = $wpdb->get_results( $lead_query, ARRAY_A ); // get post ids.

		$items_removed = false;
		if ( ! empty( $post_ids ) ) {
			foreach ( $post_ids as $id ) {
				$inq_post = array(
					'ID'         => $id['post_id'],
					'post_title' => esc_html__( 'Anonymous', 'cardealer-helper' ),
				);
				wp_update_post( $inq_post );

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

