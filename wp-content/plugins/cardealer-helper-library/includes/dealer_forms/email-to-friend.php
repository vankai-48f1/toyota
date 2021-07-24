<?php
/**
 * This function used for email to friend
 *
 * @package car-dealer-helper
 */

add_action( 'wp_ajax_email_to_friend', 'cdhl_email_to_friend' );
add_action( 'wp_ajax_nopriv_email_to_friend', 'cdhl_email_to_friend' );
if ( ! function_exists( 'cdhl_email_to_friend' ) ) {
	/**
	 * Email to friend
	 */
	function cdhl_email_to_friend() {
		global $car_dealer_options;

		$response_array = array(
			'status' => 'error',
			'msg'    => 'Something went wrong!',
		);
		$errors         = array();

		if ( ! isset( $_POST['etf_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['etf_nonce'] ), 'email-to-friend-form' ) ) {
			$errors[] = esc_html__( 'Sorry, Something went wrong. Refresh Page and try again.', 'cardealer-helper' );
		} else {
			$action = ( isset( $_POST['action'] ) && ! empty( $_POST['action'] ) ) ? sanitize_text_field( wp_unslash( $_POST['action'] ) ) : '';

			if ( $action && 'email_to_friend' === (string) $action ) {
				$captcha            = ( isset( $_POST['g-recaptcha-response'] ) && ! empty( $_POST['g-recaptcha-response'] ) ) ? sanitize_text_field( wp_unslash( $_POST['g-recaptcha-response'] ) ) : '';
				$recaptcha_response = cardealer_validate_google_captch( $captcha );
				$uname              = ( isset( $_POST['uname'] ) && ! empty( $_POST['uname'] ) ) ? sanitize_text_field( wp_unslash( $_POST['uname'] ) ) : '';
				$friends_email      = ( isset( $_POST['friends_email'] ) && ! empty( $_POST['friends_email'] ) ) ? sanitize_email( wp_unslash( $_POST['friends_email'] ) ) : '';
				$yourname           = ( isset( $_POST['yourname'] ) && ! empty( $_POST['yourname'] ) ) ? sanitize_text_field( wp_unslash( $_POST['yourname'] ) ) : '';
				$youremail          = ( isset( $_POST['email'] ) && ! empty( $_POST['email'] ) ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
				$message            = ( isset( $_POST['message'] ) && ! empty( $_POST['message'] ) ) ? sanitize_text_field( wp_unslash( $_POST['message'] ) ) : '';

				if ( empty( $friends_email ) || empty( $youremail ) ) {
					$errors[] = esc_html__( 'Your or your friend\'s email is empty. Please enter the missing email.', 'cardealer-helper' );
				} else {
					if ( false === (bool) $recaptcha_response['success'] ) {
						$errors[] = esc_html__( 'Please check the the captcha form', 'cardealer-helper' );
					} else {
						/* translators: %s site title */
						$subject   = cardealer_get_theme_option( 'email_friend_subject', sprintf( esc_html__( '%s - Mail to Friend', 'cardealer-helper' ), get_bloginfo( 'name' ) ) );
						$from_name = cardealer_get_theme_option( 'email_friend_from_name', get_bloginfo( 'name' ) );
						$from_mail = cardealer_get_theme_option( 'email_friend_from_email', get_bloginfo( 'admin_email' ) );

						$headers    = 'From: ' . strip_tags( $from_name ) . ' <' . $from_mail . ">\r\n";
						$headers   .= 'Reply-To: ' . strip_tags( $yourname ) . ' <' . $youremail . ">\r\n";
						$headers   .= "MIME-Version: 1.0\r\n";
						$headers   .= "Content-Type: text/html; charset=UTF-8\r\n";
						$mail_error = 0;

						$car_id  = isset( $_POST['car_id'] ) ? sanitize_text_field( wp_unslash( $_POST['car_id'] ) ) : '';
						$product = '';
						if ( isset( $car_id ) && ! empty( $car_id ) ) {
							$product = cdhl_get_html_mail_body( $car_id );
						}

						$message_body = esc_html__( 'Hello,', 'cardealer-helper' );
						if ( ( isset( $uname ) && ! empty( $uname ) ) ) {
							$message_body = sprintf(
								/* translators: %s user name */
								esc_html__( 'Hello %s', 'cardealer-helper' ),
								esc_html( $uname )
							);
						}
						$message_body .= '<br><br>';

						$friend        = ( ! empty( $uname ) ? "$uname <$friends_email>" : $friends_email );
						$message_body .= ( isset( $message ) && ! empty( $message ) ? $message : '' );

						$body  = $message_body . '<br><br>';
						$body .= esc_html__( 'Product Detail :', 'cardealer-helper' );
						$body .= '<br>' . $product . '<br><br>';
						$body .= sprintf(
							/* translators: %s: site url */
							esc_html__( '-- This e-mail was sent from a contact form on Cardealer %1$s.', 'cardealer-helper' ),
							esc_url( get_site_url() )
						);

						if ( wp_mail( $friend, $subject, $body, $headers ) ) {
							$response_array = array(
								'status' => '1',
								'msg'    => '<div class="alert alert-success">' . esc_html__( 'Sent Successfully', 'cardealer-helper' ) . '</div>',
							);
						} else {
							$errors[] = esc_html__( 'Sorry there was an error sending your message. Please try again later.', 'cardealer-helper' );
						}
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
		}
		echo wp_json_encode( $response_array );
		exit();
	}
}

