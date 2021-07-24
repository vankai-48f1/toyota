<?php
/**
 * This function use for subscribe user in mailchimp.
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper/functions
 * @version 1.0.0
 */

add_action( 'wp_ajax_mailchimp_singup', 'cdhl_mailchimp_signup_action' );
add_action( 'wp_ajax_nopriv_mailchimp_singup', 'cdhl_mailchimp_signup_action' );

if ( ! function_exists( 'cdhl_mailchimp_signup_action' ) ) {

	/**
	 * Mailchimp signup ajax call.
	 *
	 * @return void
	 */
	function cdhl_mailchimp_signup_action() {

		require_once trailingslashit( CDHL_PATH ) . 'includes/lib/mailchimp/Mailchimp.php'; // mailchimp class library.

		global $car_dealer_options;

		$mailchimp_api_key = isset( $car_dealer_options['mailchimp_api_key'] ) ? $car_dealer_options['mailchimp_api_key'] : '';
		$mailchimp_list_id = isset( $car_dealer_options['mailchimp_list_id'] ) ? $car_dealer_options['mailchimp_list_id'] : '';

		// INCLUDE DEFAULT MAILCHIMP FILES FINISH.
		$apikey = $mailchimp_api_key;

		// $list_id : YOUR MAILCHIMP LIST ID - see lists() method.
		$list_id = $mailchimp_list_id;

		if ( ! isset( $_REQUEST['mailchimp_nonce'] ) || ! wp_verify_nonce( $_REQUEST['mailchimp_nonce'], 'mailchimp_news' ) ) {
			esc_html_e( 'Sorry, Something went wrong, Please refresh page and try again.', 'cardealer-helper' );
			wp_die();
		}

		if ( isset( $_REQUEST['news_letter_email'] ) && ! empty( $_REQUEST['news_letter_email'] ) ) {
			if ( is_email( $_REQUEST['news_letter_email'] ) ) {
				$email = sanitize_email( $_REQUEST['news_letter_email'] );

				// trigger exception in a "try" block.
				try {
					$mailchimp = new Mailchimp( $apikey );

					$post_params = array(
						'email'  => $email,
						'status' => 'subscribed',
					);

					// Trigger exception in a "try" block.
					try {
						$result = $mailchimp->lists->subscribe( $list_id, $post_params );
						if ( is_array( $result ) && ( isset( $result['email'] ) && ! empty( $result['email'] ) ) && ( isset( $result['euid'] ) && ! empty( $result['euid'] ) ) && ( isset( $result['leid'] ) && ! empty( $result['leid'] ) ) ) {
							$msg = esc_html__( 'Successfully Subscribed. Please check confirmation email.', 'cardealer-helper' );
						}
					} catch ( Exception $e ) {
						$msg = $e->getMessage();
					}
				} catch ( Exception $e ) {
					$msg = $e->getMessage();
				}
			} else {
				$msg = esc_html__( 'Please enter a valid email address.', 'cardealer-helper' );
			}
		} else {
			$msg = esc_html__( 'Please enter a valid email address.', 'cardealer-helper' );
		}
		echo esc_html( $msg );
		wp_die();
	}
}
