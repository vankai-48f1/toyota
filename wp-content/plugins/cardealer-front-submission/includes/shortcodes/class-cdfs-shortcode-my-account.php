<?php
/**
 * My Account Shortcodes
 *
 * @author      PotenzaGlobalSolutions
 * @category    Shortcodes
 * @package     CDFS
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shows the 'my account' section where the customer can view past orders and update their information.
 *
 * @category Shortcodes
 * @package  CDFS/Shortcodes/My_Account
 * @author   PotenzaGlobalSolutions
 */
class CDFS_Shortcode_My_Account {

	/**
	 * Get the shortcode content.
	 *
	 * @param array $atts attributes.
	 * @return string
	 */
	public static function get( $atts ) {
		return CDFS_Shortcodes::shortcode_wrapper( array( __CLASS__, 'output' ), $atts );
	}

	/**
	 * Output the shortcode.
	 *
	 * @param array $atts sttributes.
	 */
	public static function output( $atts ) {
		global $wp;
		if ( ! is_user_logged_in() ) {
			$message = apply_filters( 'cardealer_my_account_message', '' );

			if ( ! empty( $message ) ) {
				cdfs_add_notice( $message );
			}
			if ( ! empty( $_GET['psw-reset-link-sent'] ) ) {
				cdfs_add_notice( esc_html__( 'Password reset link is successfully sent to your email address, please check.', 'cdfs-addon' ), 'success' );
			}

			if ( ! empty( $_GET['invalid-role'] ) ) {
				cdfs_add_notice( esc_html__( 'Please login with "Dealer Or Customer" account.', 'cdfs-addon' ), 'error' );
			}

			// After password reset, add confirmation message.
			if ( ! empty( $_GET['password-reset-done'] ) ) {
				cdfs_add_notice( esc_html__( 'Your password has been reset successfully.', 'cdfs-addon' ), 'success' );
			}
			// check for user activation token.
			if ( isset( $_GET['usr-activate'] ) && ! empty( $_GET['usr-activate'] ) ) {
				cdfs_activate_user_account_by_token( cdfs_clean( trim( $_GET['usr-activate'] ) ) );
			}

			if ( isset( $wp->query_vars['user-lost-password'] ) ) {
				self::lost_user_password();
			} elseif ( isset( $wp->query_vars['add-car'] ) ) {
				wp_enqueue_script( 'cdfs-google-location-picker-api' );
				wp_enqueue_script( 'cdfs-google-location-picker' );
				cdfs_get_shortcode_templates( 'cars/cars-add' );
			} else {
				cdfs_get_shortcode_templates( 'my-user-account/form-login' );
			}
		} else {
			// Start output buffer since the html may need discarding for BW compatibility.
			ob_start();

			if ( isset( $wp->query_vars['user-logout'] ) ) {
				/* translators: $s: Are you sure you want to log out? */
				cdfs_add_notice( sprintf( __( 'Are you sure you want to log out? <a href="%s">Confirm and log out</a>', 'cdfs-addon' ), wp_logout_url( get_permalink() ) ) );
			}

			// Collect notices before output.
			$notices = cdfs_get_notices();

			// Output the new account page.
			self::user_account( $atts );

			/**
			 * If user account_content did not run
			 * so we need to render the endpoint content again.
			 */
			if ( ! did_action( 'cdfs_content' ) ) {
				foreach ( $wp->query_vars as $key => $value ) {
					if ( 'pagename' === $key ) {
						continue;
					}
					if ( has_action( 'cdfs_' . $key . '_endpoint' ) ) {
						ob_clean(); // Clear previous buffer.
						cdfs_set_notices( $notices );
						cdfs_print_notices();
						do_action( 'cdfs_' . $key . '_endpoint', $value );
						break;
					}
				}
			}

			// Send output buffer.
			ob_end_flush();
		}
	}

	/**
	 * My user account page.
	 *
	 * @param array $atts attributes.
	 */
	private static function user_account( $atts = array() ) {

		// default parameters.
		extract(
			shortcode_atts(
				array(
					'current_user' => get_user_by( 'id', get_current_user_id() ),
				),
				$atts
			)
		);
		cdfs_get_shortcode_templates( 'my-user-account/content', null, $current_user );
	}



	/**
	 * Edit account details page.
	 */
	public static function user_account_edit() {
		cdfs_get_template( 'my-user-account/form-edit-account.php', array( 'user' => get_user_by( 'id', get_current_user_id() ) ) );
	}

	/**
	 * Cars listing page.
	 */
	public static function process_cars_listing() {
		cdfs_get_template( 'cars/cars-listing.php', array( 'user' => get_user_by( 'id', get_current_user_id() ) ) );
	}

	/**
	 * Lost password page handling.
	 */
	public static function lost_user_password() {
		if ( ! empty( $_GET['show-reset-psw-form'] ) ) {
			if ( isset( $_COOKIE[ 'cdfs-resetpass-' . COOKIEHASH ] ) && 0 < strpos( $_COOKIE[ 'cdfs-resetpass-' . COOKIEHASH ], ':' ) ) {
				list( $rp_login, $rp_key ) = array_map( 'cdfs_clean', explode( ':', wp_unslash( $_COOKIE[ 'cdfs-resetpass-' . COOKIEHASH ] ), 2 ) );
				$user                      = self::check_password_reset_key( $rp_key, $rp_login );

				// reset key / login is correct, display reset password form with hidden key / login values.
				if ( is_object( $user ) ) {
					return cdfs_get_template(
						'my-user-account/form-reset-user-password.php',
						array(
							'key'   => $rp_key,
							'login' => $rp_login,
						)
					);
				} else {
					self::set_reset_password_cookie();
				}
			}
		}

		// Show lost password form by default.
		cdfs_get_template( 'my-user-account/form-forgot-password.php' );
	}

	/**
	 * Handles sending password retrieval email to user.
	 *
	 * Based on retrieve_password() in core wp-login.php.
	 *
	 * @uses $wpdb WordPress Database object
	 * @return bool True: when finish. False: on error
	 */
	public static function send_password_reset_link() {
		$login = cdfs_clean( trim( $_POST['user_login'] ) );

		if ( ! cdfs_validate_captcha() ) { // captcha serverd side validation.
			return;
		}

		if ( empty( $login ) ) {
			cdfs_add_notice( __( 'Enter a username or email address.', 'cdfs-addon' ), 'error' );
			return false;
		} else {
			// Check on username first, may be emails is used as usernames.
			$user_data = get_user_by( 'login', $login );
		}

		// If no user found, check if emailid id provided in input, if yes then retrive details based on email.
		if ( ! $user_data && is_email( $login ) && apply_filters( 'cdfs_get_username_from_email', true ) ) {
			$user_data = get_user_by( 'email', $login );
		}
		$errors = new WP_Error();
		do_action( 'cdfs_lostpassword_error', $errors );

		if ( $errors->get_error_code() ) {
			cdfs_add_notice( $errors->get_error_message(), 'error' );
			return false;
		}

		if ( ! $user_data ) {
			cdfs_add_notice( __( 'Invalid username or email.', 'cdfs-addon' ), 'error' );
			return false;
		}

		if ( is_multisite() && ! is_user_member_of_blog( $user_data->ID, get_current_blog_id() ) ) {
			cdfs_add_notice( __( 'Invalid username or email.', 'cdfs-addon' ), 'error' );
			return false;
		}

		// Get password reset key.
		$key = get_password_reset_key( $user_data );

		if ( is_wp_error( $key ) ) {
			cdfs_add_notice( $key->get_error_message(), 'error' );
			return false;
		}

		// get site details.
		$site_title = get_bloginfo( 'name' );
		$site_email = get_bloginfo( 'admin_email' );

		// Send email notification.
		$to       = $user_data->data->user_email;
		$subject  = esc_html__( 'Password Reset', 'cdfs-addon' );
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type:text/html;charset=UTF-8' . "\r\n";
		$headers .= 'From: ' . $site_title . ' <' . $site_email . '>' . "\r\n";

		ob_start();
		cdfs_get_template(
			'mails/mail-reset-psw-link.php',
			array(
				'reset_key'  => $key,
				'user_login' => $user_data->user_login,
			)
		);
		$message = ob_get_contents();
		ob_end_clean();

		// send mail.
		try {
			wp_mail( $to, $subject, $message, $headers );
		} catch ( Exception $e ) {
			cdfs_add_notice( $e->getMessage(), 'error' );
			return false;
		}
		do_action( 'cdfs_reset_password_notification', $user_data->user_login, $key );
		return true;
	}

	/**
	 * Retrieves a user row based on password reset key and login.
	 *
	 * @uses $wpdb WordPress Database object
	 *
	 * @param string $key Hash to validate sending user's password.
	 * @param string $login The user login.
	 * @return WP_User|bool User's database row on success, false for invalid keys
	 */
	public static function check_password_reset_key( $key, $login ) {
		// Check for the password reset key.
		// Get user data or an error message in case of invalid or expired key.
		$user = check_password_reset_key( $key, $login );

		if ( is_wp_error( $user ) ) {
			cdfs_add_notice( $user->get_error_message(), 'error' );
			return false;
		}
		return $user;
	}

	/**
	 * Handles resetting the user's password.
	 *
	 * @param object $user The user.
	 * @param string $new_pass New password for the user in plaintext.
	 */
	public static function set_user_password( $user, $new_pass ) {
		wp_set_password( $new_pass, $user->ID );
		self::set_reset_password_cookie();
		wp_password_change_notification( $user ); // notify admin regarding password change of user.
	}

	/**
	 * Set or unset the cookie.
	 *
	 * @param string $value for reset password.
	 */
	public static function set_reset_password_cookie( $value = '' ) {
		$rp_cookie = 'cdfs-resetpass-' . COOKIEHASH;
		$rp_path   = current( explode( '?', wp_unslash( $_SERVER['REQUEST_URI'] ) ) );

		if ( $value ) {
			setcookie( $rp_cookie, $value, 0, $rp_path, COOKIE_DOMAIN, is_ssl(), true );
		} else {
			setcookie( $rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true );
		}
	}

}
