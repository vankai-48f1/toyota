<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Coming Soon page ajax call.
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper/functions
 * @version 1.0.0
 */

/*
 * This function used on coming soon page for getting mail addresses of visitors to notify
 */
add_action( 'wp_ajax_cardealer_notify_action', 'cdhl_notify_action' );
add_action( 'wp_ajax_nopriv_cardealer_notify_action', 'cdhl_notify_action' );

if ( ! function_exists( 'cdhl_notify_action' ) ) {
	/**
	 * Notify action ajax call.
	 *
	 * @return void
	 */
	function cdhl_notify_action() {
		global $car_dealer_options;
		$response_array = array();
		if ( isset( $_REQUEST['action'] ) && 'cardealer_notify_action' === $_REQUEST['action'] ) {
			if ( '' === (string) wp_unslash( $_REQUEST['email'] ) ) {
				$response_array = array(
					'status' => esc_html__( '0', 'cardealer-helper' ),
					'msg'    => esc_html__( 'Please provide email id', 'cardealer-helper' ),
				);
			} else {
				if ( isset( $_REQUEST['email'] ) && ! empty( $_REQUEST['email'] ) && (bool) filter_var( wp_unslash( $_REQUEST['email'] ), FILTER_VALIDATE_EMAIL ) === false ) {
					$response_array = array(
						'status' => esc_html__( '0', 'cardealer-helper' ),
						'msg'    => esc_html__( 'Please enter valid email address', 'cardealer-helper' ),
					);
					echo wp_json_encode( $response_array );
					die;
				}
				if ( ! isset( $_REQUEST['notify_nonce'] ) || ! wp_verify_nonce( $_REQUEST['notify_nonce'], 'coming_soon' ) ) {
					$response_array = array(
						'status' => esc_html__( '2', 'cardealer-helper' ),
						'msg'    => esc_html__( 'Sorry, your nonce did not verified. Refresh Page and try again.', 'cardealer-helper' ),
					);
				} else {
					$headers[] = 'From: ' . wp_unslash( $_REQUEST['email'] ) . ' <' . wp_unslash( $_REQUEST['email'] ) . '>';
					$headers[] = 'Content-Type: text/html; charset=UTF-8';
					$headers[] = 'Reply-To: ' . wp_unslash( $_REQUEST['email'] ) . '\r\n';
					$to        = get_bloginfo( 'admin_email' );
					$subject   = esc_html__( 'Notify User Mail', 'cardealer-helper' );
					$body      = sprintf(
						wp_kses(
							/* translators: %1$s: email */
							__( 'Hello, <br> User visited site and requested to notify once site available. Following is the email id : <br><b>%1$s</b><br><br>Regards, <br>Cardealer Team' ),
							array(
								'br' => array(),
								'b'  => array(),
							)
						),
						wp_unslash( $_REQUEST['email'] )
					);
					// send inquiry mail.
					if ( wp_mail( $to, $subject, $body, $headers ) ) {
						$response_array = array(
							'status' => esc_html__( '1', 'cardealer-helper' ),
							'msg'    => esc_html__( 'Thank You! Your email id received successfully.', 'cardealer-helper' ),
						);
					} else {
						$response_array = array(
							'status' => esc_html__( '0', 'cardealer-helper' ),
							'msg'    => esc_html__( 'Something went wrong, please try again later!', 'cardealer-helper' ),
						);
					}
				}
			}
		}
		echo wp_json_encode( $response_array );
		die;
	}
}

