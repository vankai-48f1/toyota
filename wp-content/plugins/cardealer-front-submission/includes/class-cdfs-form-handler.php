<?php
/**
 * CDFS form haneler.
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
class CDFS_Form_Handler {

	/**
	 * Hook in methods.
	 */
	public static function init() {
		add_action( 'template_redirect', array( __CLASS__, 'update_account_details' ) );
		add_action( 'template_redirect', array( __CLASS__, 'redirect_reset_password_link' ) );

		add_action( 'wp_loaded', array( __CLASS__, 'process_user_login' ), 20 );
		add_action( 'wp_loaded', array( __CLASS__, 'process_user_registration' ), 20 );
		add_action( 'wp_loaded', array( __CLASS__, 'process_user_forgot_password' ), 20 );
		add_action( 'wp_loaded', array( __CLASS__, 'process_user_password_reset' ), 20 );

	}

	/**
	 * Remove key and cdfs_login from query string, set cookie, and redirect to account page to show the form.
	 */
	public static function redirect_reset_password_link() {
		if ( cdfs_is_user_account_page() && ! empty( $_GET['key'] ) && ! empty( $_GET['cdfs_login'] ) ) {
			$value = sprintf( '%s:%s', wp_unslash( $_GET['cdfs_login'] ), wp_unslash( $_GET['key'] ) );

			CDFS_Shortcode_My_Account::set_reset_password_cookie( $value );
			wp_safe_redirect( add_query_arg( 'show-reset-psw-form', 'true', cdfs_get_endpoint_url( 'user-lost-password' ) ) );
			exit;
		}
	}

	/**
	 * Save the password/account details and redirect back to the my account page.
	 */
	public static function update_account_details() {

		if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) ) {
			return;
		}

		if ( empty( $_POST['action'] ) || 'update_account_details' !== $_POST['action'] || empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update_account_details' ) ) {
			return;
		}

		if ( ! cdfs_validate_captcha() ) { // captcha serverd side validation.
			return;
		}

		nocache_headers();

		$errors = new WP_Error();
		$user   = new stdClass();

		$user->ID     = (int) get_current_user_id();
		$current_user = get_user_by( 'id', $user->ID );

		if ( $user->ID <= 0 ) {
			return;
		}

		// Personal Details.
		$account_first_name = ! empty( $_POST['account_first_name'] ) ? cdfs_clean( $_POST['account_first_name'] ) : '';
		$account_last_name  = ! empty( $_POST['account_last_name'] ) ? cdfs_clean( $_POST['account_last_name'] ) : '';
		$account_email      = ! empty( $_POST['account_email'] ) ? cdfs_clean( $_POST['account_email'] ) : '';

		// Social Profiles.
		$user_facebook  = ! empty( $_POST['user_facebook'] ) ? cdfs_clean( $_POST['user_facebook'] ) : '';
		$user_twitter   = ! empty( $_POST['user_twitter'] ) ? cdfs_clean( $_POST['user_twitter'] ) : '';
		$user_linkedIn  = ! empty( $_POST['user_linkedIn'] ) ? cdfs_clean( $_POST['user_linkedIn'] ) : '';
		$user_pinterest = ! empty( $_POST['user_pinterest'] ) ? cdfs_clean( $_POST['user_pinterest'] ) : '';
		$user_instagram = ! empty( $_POST['user_instagram'] ) ? cdfs_clean( $_POST['user_instagram'] ) : '';

		// Login Details.
		$pass_cur  = ! empty( $_POST['password_current'] ) ? $_POST['password_current'] : '';
		$pass1     = ! empty( $_POST['password_1'] ) ? $_POST['password_1'] : '';
		$pass2     = ! empty( $_POST['password_2'] ) ? $_POST['password_2'] : '';
		$save_pass = true;

		$user->first_name = $account_first_name;
		$user->last_name  = $account_last_name;

		// Prevent emails being displayed, or leave alone.
		$user->display_name = is_email( $current_user->display_name ) ? $user->first_name : $current_user->display_name;

		// Handle required fields.
		$required_fields = apply_filters(
			'cdfs_update_account_details_required_fields',
			array(
				'account_first_name' => esc_html__( 'First name', 'cdfs-addon' ),
				'account_last_name'  => esc_html__( 'Last name', 'cdfs-addon' ),
				'account_email'      => esc_html__( 'Email address', 'cdfs-addon' ),
			)
		);

		foreach ( $required_fields as $field_key => $field_name ) {
			if ( empty( $_POST[ $field_key ] ) ) {
				/* translators: %s: field name */
				cdfs_add_notice( sprintf( __( '%s is a required field.', 'cdfs-addon' ), '<strong>' . esc_html( $field_name ) . '</strong>' ), 'error' );
			}
		}

		if ( $account_email ) {
			$account_email = sanitize_email( $account_email );
			if ( ! is_email( $account_email ) ) {
				cdfs_add_notice( esc_html__( 'Please provide a valid email address.', 'cdfs-addon' ), 'error' );
			} elseif ( email_exists( $account_email ) && $account_email !== $current_user->user_email ) {
				cdfs_add_notice( esc_html__( 'This email address is already registered.', 'cdfs-addon' ), 'error' );
			}
			$user->user_email = $account_email;
		}

		if ( ! empty( $pass_cur ) && empty( $pass1 ) && empty( $pass2 ) ) {
			cdfs_add_notice( esc_html__( 'Please fill out all password fields.', 'cdfs-addon' ), 'error' );
			$save_pass = false;
		} elseif ( ! empty( $pass1 ) && empty( $pass_cur ) ) {
			cdfs_add_notice( esc_html__( 'Please enter your current password.', 'cdfs-addon' ), 'error' );
			$save_pass = false;
		} elseif ( ! empty( $pass1 ) && empty( $pass2 ) ) {
			cdfs_add_notice( esc_html__( 'Please re-enter your password.', 'cdfs-addon' ), 'error' );
			$save_pass = false;
		} elseif ( ( ! empty( $pass1 ) || ! empty( $pass2 ) ) && $pass1 !== $pass2 ) {
			cdfs_add_notice( esc_html__( 'New passwords do not match.', 'cdfs-addon' ), 'error' );
			$save_pass = false;
		} elseif ( ! empty( $pass1 ) && ! wp_check_password( $pass_cur, $current_user->user_pass, $current_user->ID ) ) {
			cdfs_add_notice( esc_html__( 'Your current password is incorrect.', 'cdfs-addon' ), 'error' );
			$save_pass = false;
		}

		if ( $pass1 && $save_pass ) {
			$user->user_pass = $pass1;
		}

		if ( $errors->get_error_messages() ) {
			foreach ( $errors->get_error_messages() as $error ) {
				cdfs_add_notice( $error, 'error' );
			}
		}

		/*Profile img*/
		$allowed = array( 'jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF' );
		if ( ! empty( $_FILES['profile_img'] ) ) {
			$file = $_FILES['profile_img'];
			if ( is_array( $file ) && ! empty( $file['name'] ) ) {
				$ext = pathinfo( $file['name'] );
				$ext = $ext['extension'];
				if ( in_array( $ext, $allowed ) ) {

					$upload_dir  = wp_upload_dir();
					$upload_url  = $upload_dir['url'];
					$upload_path = $upload_dir['path'];

					/*Upload full image*/
					if ( ! function_exists( 'wp_handle_upload' ) ) {
						require_once ABSPATH . 'wp-admin/includes/file.php';
					}
					$original_file = wp_handle_upload( $file, array( 'test_form' => false ) );

					if ( ! is_wp_error( $original_file ) ) {
						$image_user = $original_file['file'];
						/*Crop image to square from full image*/
						$image_cropped = image_make_intermediate_size( $image_user, 160, 160, false );

						/*Delete full image*/
						if ( file_exists( $image_user ) ) {
							unlink( $image_user );
						}

						if ( ! $image_cropped ) {
							$got_error_validation = true;
							cdfs_add_notice( esc_html__( 'Error, please try again.', 'cdfs-addon' ), 'error' );
						} else {

							/*Get path and url of cropped image*/
							$user_new_image_url  = $upload_url . '/' . $image_cropped['file'];
							$user_new_image_path = $upload_path . '/' . $image_cropped['file'];

							/*Delete from site old avatar*/

							$user_old_avatar = get_the_author_meta( 'cdfs_dealer_avatar_path', $user->ID );
							if ( ! empty( $user_old_avatar ) && $user_new_image_path != $user_old_avatar && file_exists( $user_old_avatar ) ) {

								/*Check if prev avatar exists in another users except current user*/
								$args     = array(
									'meta_key'     => 'cdfs_dealer_avatar_path',
									'meta_value'   => $user_old_avatar,
									'meta_compare' => '=',
									'exclude'      => array( $user->ID ),
								);
								$users_db = get_users( $args );
								if ( empty( $users_db ) ) {
									unlink( $user_old_avatar );
								}
							}
						}
					}
				} else {
					cdfs_add_notice( esc_html__( 'Please load image with right extension (jpg, jpeg, png and gif)', 'cdfs-addon' ), 'error' );
				}
			}
		}

		if ( cdfs_notice_count( 'error' ) === 0 ) {

			wp_update_user( $user );

			/*Update user meta path and url image*/
			if ( is_array( $file ) && ! empty( $file['name'] ) ) {
				update_user_meta( $user->ID, 'cdfs_user_avatar', $user_new_image_url );
				update_user_meta( $user->ID, 'cdfs_dealer_avatar_path', $user_new_image_path );
			}
			// Save social profiles.
			update_user_meta( $user->ID, 'facebook', $user_facebook );
			update_user_meta( $user->ID, 'twitter', $user_twitter );
			update_user_meta( $user->ID, 'linkedin', $user_linkedIn );
			update_user_meta( $user->ID, 'pinterest', $user_pinterest );
			update_user_meta( $user->ID, 'instagram', $user_instagram );

			cdfs_add_notice( esc_html__( 'Account details changed successfully.', 'cdfs-addon' ) );

			do_action( 'cdfs_update_account_details', $user->ID );

			wp_safe_redirect( cdfs_get_page_permalink( 'myuseraccount' ) );
			exit;
		}
	}

	/**
	 * Process the login form.
	 */
	public static function process_user_login() {
		$nonce_value = isset( $_POST['cdfs-login-nonce'] ) ? $_POST['cdfs-login-nonce'] : '';

		if ( ( ! empty( $_POST['login'] ) ) && wp_verify_nonce( $nonce_value, 'cdfs-login' ) ) {
			if ( ! cdfs_validate_captcha() ) { // captcha server side validation.
				return;
			}
			try {
				$creds = array(
					'user_password' => $_POST['password'],
					'remember'      => isset( $_POST['rememberme'] ),
				);

				$username         = trim( $_POST['username'] );
				$validation_error = new WP_Error();
				$validation_error = apply_filters( 'cdfs_process_user_login_errors', $validation_error, $_POST['username'], $_POST['password'] );

				if ( $validation_error->get_error_code() ) {
					throw new Exception( '<strong>' . esc_html__( 'Error:', 'cdfs-addon' ) . '</strong> ' . $validation_error->get_error_message() );
				}

				if ( empty( $username ) ) {
					throw new Exception( '<strong>' . esc_html__( 'Error:', 'cdfs-addon' ) . '</strong> ' . esc_html__( 'Username is required.', 'cdfs-addon' ) );
				}

				if ( is_email( $username ) && apply_filters( 'cdfs_get_username_from_email', true ) ) {
					$user = get_user_by( 'email', $username );

					if ( ! $user ) {
						$user = get_user_by( 'login', $username );
					}

					if ( isset( $user->user_login ) ) {
						$creds['user_login'] = $user->user_login;
					} else {
						throw new Exception( '<strong>' . esc_html__( 'Error:', 'cdfs-addon' ) . '</strong> ' . esc_html__( 'A user could not be found with this email address.', 'cdfs-addon' ) );
					}
				} else {
					$creds['user_login'] = $username;
				}

				// On multisite, ensure user exists on current site, if not add them before allowing login.
				if ( is_multisite() ) {
					$user_data = get_user_by( 'login', $username );

					if ( $user_data && ! is_user_member_of_blog( $user_data->ID, get_current_blog_id() ) ) {
						add_user_to_blog( get_current_blog_id(), $user_data->ID, 'car_dealer' );
					}
				}

				// Perform the login.
				$user     = wp_signon( apply_filters( 'cdfs_login_credentials', $creds ), is_ssl() );
				$redirect = '';

				if ( is_wp_error( $user ) ) {
					$message = $user->get_error_message();
					$message = str_replace( '<strong>' . esc_html( $creds['user_login'] ) . '</strong>', '<strong>' . esc_html( $username ) . '</strong>', $message );
					throw new Exception( $message );
				} else {
					// get user info.
					$activation_error = '';
					$userinfo         = get_user_meta( $user->ID, 'cdfs_user_status', true );
					if ( ! in_array( 'car_dealer', $user->roles ) && ! in_array( 'customer', $user->roles ) ) { // If user login with roles other than car_dealer.
						wp_logout();
						$redirect = cdfs_get_page_permalink( 'myuseraccount' ) . '?invalid-role=1';
					} elseif ( ! empty( $userinfo ) && 'pending' === $userinfo ) { // Check user status.
						wp_logout();

						$activate_method = cdfs_user_activation_method();
						if ( 'mail' === $activate_method ) {
							$activation_error = '<strong>' . esc_html__( 'Error:', 'cdfs-addon' ) . '</strong> ' . esc_html__( 'Please activate your account with the activation link sent to your registered mail account.', 'cdfs-addon' );
						} elseif ( 'admin' === $activate_method ) {
							$activation_error = '<strong>' . esc_html__( 'Error:', 'cdfs-addon' ) . '</strong> ' . esc_html__( 'Your account is waiting for admin approval. Once your account is approved by the admin, you will be able to manage vehicles.', 'cdfs-addon' );
						}

						if ( $activation_error ) {
							throw new Exception( apply_filters( 'cdfs_user_activation_err_msg', $activation_error ) );
						}

					} elseif ( cdfs_get_reference_link() ) {
						$redirect = cdfs_get_reference_link();
					} else {
						$redirect = cdfs_get_page_permalink( 'myuseraccount' );
					}

					if ( $redirect ) {
						wp_redirect( wp_validate_redirect( apply_filters( 'cdfs_login_redirect', $redirect, $user ), cdfs_get_page_permalink( 'myuseraccount' ) ) );
						exit;
					}
				}
			} catch ( Exception $e ) {
				cdfs_add_notice( $e->getMessage(), 'error' );
			}
		}
	}

	/**
	 * Handle lost password form.
	 */
	public static function process_user_forgot_password() {
		if ( isset( $_POST['cdfs_action'] ) && isset( $_POST['user_login'] ) && isset( $_POST['cdhl_nonce'] ) && wp_verify_nonce( $_POST['cdhl_nonce'], 'cdhl-lost-psw' ) ) {

			// generate password reset link.
			$success = CDFS_Shortcode_My_Account::send_password_reset_link();

			// If successful, redirect to user account login page with query arg set.
			if ( $success ) {
				cdfs_add_notice( esc_html__( 'Password reset link is successfully sent to your email address, please check.', 'cdfs-addon' ), 'success' );
				wp_redirect( add_query_arg( 'psw-reset-link-sent', 'true', cdfs_get_page_permalink( 'myuseraccount' ) ) );
				exit;
			}
		}
	}

	/**
	 * Handle reset password form.
	 */
	public static function process_user_password_reset() {
		$reset_post_fields = array( 'password_1', 'password_2', 'reset_psw_key', 'reset_psw_login', 'cdfs_nonce' );

		foreach ( $reset_post_fields as $field ) {
			if ( ! isset( $_POST[ $field ] ) ) {
				return;
			}
			$reset_post_fields[ $field ] = $_POST[ $field ];
		}

		if ( ! wp_verify_nonce( $reset_post_fields['cdfs_nonce'], 'cdfs-reset-psw' ) ) {
			return;
		}

		if ( ! cdfs_validate_captcha() ) { // captcha serverd side validation.
			return;
		}

		$user = CDFS_Shortcode_My_Account::check_password_reset_key( $reset_post_fields['reset_psw_key'], $reset_post_fields['reset_psw_login'] );

		if ( $user instanceof WP_User ) {
			if ( empty( $reset_post_fields['password_1'] ) ) {
				cdfs_add_notice( esc_html__( 'Please enter your password.', 'cdfs-addon' ), 'error' );
			}

			if ( $reset_post_fields['password_1'] !== $reset_post_fields['password_2'] ) {
				cdfs_add_notice( esc_html__( 'Passwords do not match.', 'cdfs-addon' ), 'error' );
			}

			$errors = new WP_Error();

			do_action( 'validate_password_reset', $errors, $user );

			if ( is_wp_error( $errors ) && $errors->get_error_messages() ) {
				foreach ( $errors->get_error_messages() as $error ) {
					cdfs_add_notice( $error, 'error' );
				}
			}

			if ( 0 === cdfs_notice_count( 'error' ) ) {
				CDFS_Shortcode_My_Account::set_user_password( $user, $reset_post_fields['password_1'] );

				wp_redirect( add_query_arg( 'password-reset-done', 'true', cdfs_get_page_permalink( 'myuseraccount' ) ) );
				exit;
			}
		}
	}

	/**
	 * Process the registration form.
	 */
	public static function process_user_registration() {
		$nonce_value = isset( $_POST['cdfs-register-nonce'] ) ? $_POST['cdfs-register-nonce'] : '';

		if ( ! empty( $_POST['register'] ) && wp_verify_nonce( $nonce_value, 'cdfs-register' ) ) {
			$username = $_POST['username'];
			$password = $_POST['password'];
			$email    = $_POST['email'];

			if ( ! cdfs_validate_captcha() ) { // captcha serverd side validation.
				return;
			}

			try {
				$validation_error = new WP_Error();
				$validation_error = apply_filters( 'cdfs_process_user_registration_errors', $validation_error, $username, $password, $email );

				if ( $validation_error->get_error_code() ) {
					throw new Exception( $validation_error->get_error_message() );
				}

				$new_user = cdfs_create_user( sanitize_email( $email ), cdfs_clean( $username ), $password );

				if ( is_wp_error( $new_user ) ) {
					throw new Exception( $new_user->get_error_message() );
				} else {
					$activate_method = cdfs_user_activation_method();
					if ( 'mail' === $activate_method ) {
						cdfs_add_notice( esc_html__( 'Account successfully created. Please activate your account with the activation link sent to your registered mail account.', 'cdfs-addon' ), 'success' );
					} elseif ( 'admin' === $activate_method ) {
						cdfs_add_notice( esc_html__( 'Account successfully created. Your account is waiting for admin approval.', 'cdfs-addon' ), 'success' );
					} else {
						cdfs_add_notice( esc_html__( 'Account successfully created.', 'cdfs-addon' ), 'success' );
					}
				}
			} catch ( Exception $e ) {
				cdfs_add_notice( '<strong>' . esc_html__( 'Error:', 'cdfs-addon' ) . '</strong> ' . $e->getMessage(), 'error' );
			}
		}
	}

}

CDFS_Form_Handler::init();
