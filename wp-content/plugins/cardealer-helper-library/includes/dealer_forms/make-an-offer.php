<?php
/**
 * This function use to store new inquiries of test drive.
 *
 * @package car-dealer-helper
 */

add_action( 'wp_ajax_make_an_offer_action', 'cdhl_make_an_offer_action' );
add_action( 'wp_ajax_nopriv_make_an_offer_action', 'cdhl_make_an_offer_action' );
if ( ! function_exists( 'cdhl_make_an_offer_action' ) ) {
	/**
	 * Make an offer
	 */
	function cdhl_make_an_offer_action() {
		global $car_dealer_options;
		$response_array = array();
		$response_array = array(
			'status' => 'error',
			'msg'    => '<div class="alert alert-danger">Something went wrong!</div>',
		);
		if ( isset( $_POST['action'] ) && 'make_an_offer_action' === (string) sanitize_text_field( wp_unslash( $_POST['action'] ) ) ) {
			$captcha            = isset( $_POST['g-recaptcha-response'] ) ? sanitize_text_field( wp_unslash( $_POST['g-recaptcha-response'] ) ) : '';
			$recaptcha_response = cardealer_validate_google_captch( $captcha );
			$mao_fname          = isset( $_POST['mao_fname'] ) ? sanitize_text_field( wp_unslash( $_POST['mao_fname'] ) ) : '';
			$mao_lname          = isset( $_POST['mao_lname'] ) ? sanitize_text_field( wp_unslash( $_POST['mao_lname'] ) ) : '';
			$mao_phone          = isset( $_POST['mao_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['mao_phone'] ) ) : '';
			$mao_email          = isset( $_POST['mao_email'] ) ? sanitize_text_field( wp_unslash( $_POST['mao_email'] ) ) : '';
			$mao_message        = isset( $_POST['mao_message'] ) ? sanitize_text_field( wp_unslash( $_POST['mao_message'] ) ) : '';
			$mao_reques_price   = isset( $_POST['mao_reques_price'] ) ? sanitize_text_field( wp_unslash( $_POST['mao_reques_price'] ) ) : '';
			$car_id             = isset( $_POST['car_id'] ) ? sanitize_text_field( wp_unslash( $_POST['car_id'] ) ) : '';
			$author_id          = get_post_field( 'post_author', $car_id );
			$user               = get_userdata( $author_id );
			$user_roles         = $user->roles;
			$from_name          = $user->display_name;

			if ( in_array( 'administrator', $user_roles, true ) ) {
				$from_name = $car_dealer_options['mao_from_name'];
			}

			if ( ! isset( $_POST['mno_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['mno_nonce'] ), 'make_an_offer' ) ) {
				$errors[] = esc_html__( 'Sorry, Something went wrong. Refresh Page and try again.', 'cardealer-helper' );
			} elseif ( false === (bool) $recaptcha_response['success'] ) {
				$errors[] = esc_html__( 'Please check the the captcha form', 'cardealer-helper' );
			} else {
				$mail_set     = 0;
				$request_date = ( isset( $recaptcha_response['challenge_ts'] ) && ! empty( $recaptcha_response['challenge_ts'] ) ) ? $recaptcha_response['challenge_ts'] : '';
					$post_id  = wp_insert_post(
						array(
							'post_status'  => 'publish',
							'post_type'    => 'make_offer',
							'post_title'   => $mao_fname . ' ' . $mao_lname,
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
					update_field( 'first_name', $mao_fname, $post_id );
					update_field( 'last_name', $mao_lname, $post_id );
					update_field( 'email_id', $mao_email, $post_id );
					update_field( 'home_phone', $mao_phone, $post_id );
					update_field( 'comment', $mao_message, $post_id );
					update_field( 'request_price', $mao_reques_price, $post_id );

					$from_name     = empty( $car_dealer_options['mao_from_name'] ) ? 'no-reply@' . sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : $car_dealer_options['mao_from_name'];
					$from_mail     = empty( $car_dealer_options['mao_mail_id_from'] ) ? 'no-reply@' . sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : $car_dealer_options['mao_mail_id_from'];
					$reply_to_mail = empty( $mao_email ) ? 'no-reply@' . sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : $mao_email;
					$reply_to_name = empty( $mao_fname ) ? 'User' : $mao_fname;
					$subject       = empty( $car_dealer_options['mao_subject'] ) ? esc_html__( 'Make an Offer Mail', 'cardealer-helper' ) : $car_dealer_options['mao_subject'];

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
						$adf_data .= '<price type="offer">' . $mao_reques_price . '</price>' . PHP_EOL;
						$adf_data .= '<trim>' . $car_trim . '</trim>' . PHP_EOL;
						$adf_data .= '</vehicle>' . PHP_EOL;
						$adf_data .= '<customer>' . PHP_EOL;
						$adf_data .= '<contact>' . PHP_EOL;
						$adf_data .= '<name part="full">' . $mao_fname . ' ' . $mao_lname . '</name>' . PHP_EOL;
						$adf_data .= '<email>' . $mao_email . '</email>' . PHP_EOL;
						$adf_data .= '<phone type="voice" time="day">' . $mao_phone . '</phone>' . PHP_EOL;
						$adf_data .= '<comment>' . $mao_message . '</comment>' . PHP_EOL;
						$adf_data .= '</contact>' . PHP_EOL;
						$adf_data .= '</customer>' . PHP_EOL;
						$adf_data .= '<vendor>' . PHP_EOL;
						$adf_data .= '<contact>' . PHP_EOL;
						$adf_data .= '<name part="full">' . $car_dealer_options['mao_from_name'] . '</name>' . PHP_EOL;
						$adf_data .= '</contact>' . PHP_EOL;
						$adf_data .= '</vendor>' . PHP_EOL;
						$adf_data .= '</prospect>' . PHP_EOL;
						$adf_data .= '</adf>' . PHP_EOL;
					}

					// Sending ADF Mail.
					if ( isset( $car_dealer_options['mao_adf_mail'] ) && 'off' !== $car_dealer_options['mao_adf_mail'] ) {
						if ( isset( $car_dealer_options['mao_adf_mail_to'] ) && ! empty( $car_dealer_options['mao_adf_mail_to'] ) ) {
							$mail_set     = 1;
							$dealer_email = cdhl_get_dealer_mail( $car_id ); // Get dealer email id.

							$to = ! empty( $dealer_email ) ? $dealer_email : $car_dealer_options['mao_adf_mail_to'];

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
					$fields = array(
						'CD_FROM_NAME'  => $from_name,
						'CD_FIRST_NAME' => $mao_fname,
						'CD_LAST_NAME'  => $mao_lname,
						'CD_EMAIL'      => $mao_email,
						'CD_HOME_PHONE' => $mao_phone,
						'CD_COMMENT'    => $mao_message,
						'CD_REQ_PRICE'  => $mao_reques_price,
					);

					// Sending HTML Mail.
					if ( isset( $car_dealer_options['mao_html_mail'] ) && 'off' !== $car_dealer_options['mao_html_mail'] ) {
						if ( isset( $car_dealer_options['mao_html_mail_to'] ) && ! empty( $car_dealer_options['mao_html_mail_to'] ) ) {
							$mail_set     = 1;
							$dealer_email = cdhl_get_dealer_mail( $car_id ); // Get dealer email id.

							$to = ! empty( $dealer_email ) ? $dealer_email : $car_dealer_options['mao_html_mail_to'];

							// Mail body.
							if ( isset( $car_dealer_options['mmao_html_body'] ) && ! empty( $car_dealer_options['mmao_html_body'] ) ) {
								$mmao_html_body           = $car_dealer_options['mmao_html_body'];
								$fields['PRODUCT_DETAIL'] = $product;

								foreach ( $fields as $tag => $value ) {
									if ( strpos( $mmao_html_body, '#' . $tag . '#' ) !== false ) {
										$mmao_html_body = str_replace( '#' . $tag . '#', $value, $mmao_html_body );
									}
								}
							} else {
								$mmao_html_body = esc_html__( 'One Inquiry Received', 'cardealer-helper' );
							}

							$html_headers  = $headers;
							$html_headers .= "MIME-Version: 1.0\r\n";
							$html_headers .= "Content-Type: text/html; charset=UTF-8\r\n";
							if ( ! wp_mail( $to, $subject, $mmao_html_body, $html_headers ) ) {
								$mail_error = 1;
							}
						}
					}

					// Sending Text Mail.
					if ( isset( $car_dealer_options['mao_text_mail'] ) && 'off' !== $car_dealer_options['mao_text_mail'] ) {
						if ( isset( $car_dealer_options['mao_text_mail_to'] ) && ! empty( $car_dealer_options['mao_text_mail_to'] ) ) {
							$mail_set     = 1;
							$dealer_email = cdhl_get_dealer_mail( $car_id ); // Get dealer email id.

							$to = ! empty( $dealer_email ) ? $dealer_email : $car_dealer_options['mao_text_mail_to'];

							// Mail body.
							if ( isset( $car_dealer_options['mmao_text_body'] ) && ! empty( $car_dealer_options['mmao_text_body'] ) ) {
								$mmao_text_body = $car_dealer_options['mmao_text_body'];

								$fields['PRODUCT_DETAIL'] = $plain_mail;
								foreach ( $fields as $tag => $value ) {
									if ( strpos( $mmao_text_body, '#' . $tag . '#' ) !== false ) {
										$mmao_text_body = str_replace( '#' . $tag . '#', $value, $mmao_text_body );
									}
								}
							} else {
								$mmao_text_body = esc_html__( 'One Inquiry Received', 'cardealer-helper' );
							}

							$text_headers  = $headers;
							$text_headers .= "MIME-Version: 1.0\r\n";
							$text_headers .= "content-type: text/plain; charset=UTF-8\r\n";
							if ( ! wp_mail( $to, $subject, $mmao_text_body, $text_headers ) ) {
								$mail_error = 1;
							}
						}
					}

					if ( ( isset( $car_dealer_options['mao_adf_mail'] ) && 'off' !== $car_dealer_options['mao_adf_mail'] ) || ( isset( $car_dealer_options['mao_html_mail'] ) && 'off' !== $car_dealer_options['mao_html_mail'] ) || ( isset( $car_dealer_options['mao_text_mail'] ) && 'off' !== $car_dealer_options['mao_text_mail'] ) ) {
						// If not mail is set from admin form options then mail will sent to admin( if no dealer is available for this car ).
						if ( 0 === (int) $mail_set ) {
							$html_headers  = $headers;
							$html_headers .= "MIME-Version: 1.0\r\n";
							$html_headers .= "Content-Type: text/html; charset=UTF-8\r\n";
							$dealer_email  = cdhl_get_dealer_mail( $car_id ); // Get dealer email id.

							$to = ! empty( $dealer_email ) ? $dealer_email : get_option( 'admin_email' );

							// Mail body.
							if ( isset( $car_dealer_options['mmao_html_body'] ) && ! empty( $car_dealer_options['mmao_html_body'] ) ) {
								$mmao_html_body           = $car_dealer_options['mmao_html_body'];
								$fields['PRODUCT_DETAIL'] = $product;

								foreach ( $fields as $tag => $value ) {
									if ( strpos( $mmao_html_body, '#' . $tag . '#' ) !== false ) {
										$mmao_html_body = str_replace( '#' . $tag . '#', $value, $mmao_html_body );
									}
								}
							} else {
								$mmao_html_body = esc_html__( 'One Inquiry Received', 'cardealer-helper' );
							}
							if ( ! wp_mail( $to, $subject, $mmao_html_body ) ) {
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

/* Get Make an Offer Personal info */
add_filter(
	'wp_privacy_personal_data_exporters',
	'cdhl_register_mno_lead_info_exporter',
	10
);
if ( ! function_exists( 'cdhl_register_mno_lead_info_exporter' ) ) {
	/**
	 * Register mno lead info exporter
	 *
	 * @param array $exporters array variable.
	 */
	function cdhl_register_mno_lead_info_exporter( $exporters ) {
		$exporters['cardealer-helper-mno-exporter'] = array(
			'exporter_friendly_name' => esc_html__( 'Make an Offer Lead Data', 'cardealer-helper' ),
			'callback'               => 'cdhl_gdpr_export_mno_inq_lead_info',
		);
		return $exporters;
	}
}

if ( ! function_exists( 'cdhl_gdpr_export_mno_inq_lead_info' ) ) {
	/**
	 * GDPR export
	 *
	 * @param string $email_address email id.
	 */
	function cdhl_gdpr_export_mno_inq_lead_info( $email_address ) {
		if ( empty( $email_address ) ) {
			return;
		}
		return cdhl_gdpr_export_get_lead_info( $email_address, 'make_offer' );
	}
}

// Erase data.
if ( ! function_exists( 'cdhl_make_offer_eraser' ) ) {
	/**
	 * Make offer eraser
	 *
	 * @param string $email_address email id.
	 */
	function cdhl_make_offer_eraser( $email_address ) {
		if ( empty( $email_address ) ) {
			return;
		}
		$number = 500; // Limit us to avoid timing out.
		global $wpdb;

		$lead_query = "SELECT DISTINCT(cdhl_meta.post_id)
		FROM $wpdb->postmeta cdhl_meta
		JOIN $wpdb->posts cdhl_posts ON cdhl_meta.post_id = cdhl_posts.ID
		WHERE cdhl_posts.post_type = 'make_offer'
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
				$email_id = get_post_meta( $id['post_id'], 'email_id', true );
				if ( ! empty( $email_id ) ) {
					update_post_meta( $id['post_id'], 'email_id', 'deleted@site.invalid' );
					$items_removed = true;
				}
				$home_phone = get_post_meta( $id['post_id'], 'home_phone', true );
				if ( ! empty( $home_phone ) ) {
					update_post_meta( $id['post_id'], 'home_phone', 'xxxxxxxxxx' );
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

