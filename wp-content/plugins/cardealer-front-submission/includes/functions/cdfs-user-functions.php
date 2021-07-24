<?php
/**
 * CDFS User Functions
 *
 * @author   PotenzaGlobalSolutions
 * @category Class
 * @package  CDFS/Classes
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_setup_theme', 'cdfs_hide_top_admin_menu_bar', 100 );
if ( ! function_exists( 'cdfs_hide_top_admin_menu_bar' ) ) {
	/**
	 * Disable admin bar for users with "Car Dealer" role.
	 */
	function cdfs_hide_top_admin_menu_bar() {
		if ( ! defined( 'DOING_AJAX' ) && current_user_can( 'car_dealer' ) ) {
			show_admin_bar( false );
		}
	}
}

add_action( 'admin_init', 'cdfs_redirect_car_dealer_user' );
if ( ! function_exists( 'cdfs_redirect_car_dealer_user' ) ) {
	/**
	 * Redirect Car Dealer user to car dealer account page it try to login in admin panel
	 */
	function cdfs_redirect_car_dealer_user() {
		if ( is_user_logged_in() ) {
			$current_user = wp_get_current_user();
			if ( isset( $current_user ) && ! empty( $current_user ) ) {
				$roles = $current_user->roles;
				if ( ! empty( $roles ) && in_array( 'car_dealer', $roles ) && ! defined( 'DOING_AJAX' ) ) {
					wp_safe_redirect( cdfs_get_page_permalink( 'myuseraccount' ) );
					exit;
				}
			}
		}
	}
}

add_filter( 'topbar_login_url', 'cdfs_add_dealer_menu' );
if ( ! function_exists( 'cdfs_add_dealer_menu' ) ) {
	/**
	 * Add Dealer menu in header
	 *
	 * @param string $topbar_login_url .
	 */
	function cdfs_add_dealer_menu( $topbar_login_url ) {
		$pages = get_pages(
			array(
				'meta_key'   => '_wp_page_template',
				'meta_value' => 'templates/cardealer-front-submission.php',
			)
		);

		if ( ! empty( $pages ) && isset( $pages[0] ) ) {
			$dashboard_link = get_permalink( $pages[0]->ID );
			return $dashboard_link;
		} else {
			$dashboard_link = cdfs_get_page_permalink( 'myuseraccount' );
			return $dashboard_link;
		}
	}
}

add_filter( 'topbar_login_url_label', 'cdfs_add_dealer_menu_label' );
if ( ! function_exists( 'cdfs_add_dealer_menu_label' ) ) {
	/**
	 * Add dealer menu label
	 *
	 * @param string $topbar_login_url_label .
	 */
	function cdfs_add_dealer_menu_label( $topbar_login_url_label ) {
		if ( is_user_logged_in() ) {
			return esc_html__( 'My Account', 'cdfs-addon' );
		} else {
			return esc_html__( 'Login', 'cdfs-addon' );
		}
	}
}

add_filter( 'topbar_login_url_icon', 'cdfs_add_dealer_menu_icon' );
if ( ! function_exists( 'cdfs_add_dealer_menu_icon' ) ) {
	/**
	 * Add dealer menu icon
	 *
	 * @param string $topbar_login_url_icon .
	 */
	function cdfs_add_dealer_menu_icon( $topbar_login_url_icon ) {
		if ( is_user_logged_in() ) {
			return 'fa fa-user';
		} else {
			return 'fa fa-lock';
		}
	}
}

if ( ! function_exists( 'cdfs_create_user' ) ) {

	/**
	 * Create a new user.
	 *
	 * @param  string $email : User email.
	 * @param  string $username : User username.
	 * @param  string $password : User password.
	 * @return int|WP_Error Returns WP_Error on failure, Int (user ID) on success.
	 */
	function cdfs_create_user( $email, $username = '', $password = '' ) {
		// Check the email address.
		if ( empty( $email ) || ! is_email( $email ) ) {
			return new WP_Error( 'registration-error-invalid-email', esc_html__( 'Please provide a valid email address.', 'cdfs-addon' ) );
		}

		if ( email_exists( $email ) ) {
			return new WP_Error( 'registration-error-email-exists', esc_html__( 'An account is already registered with your email address. Please log in.', 'cdfs-addon' ) );
		}

		// Handle username creation.
		if ( ! empty( $username ) ) {
			$username = sanitize_user( $username );

			if ( ! validate_username( $username ) ) {
				return new WP_Error( 'registration-error-invalid-username', esc_html__( 'Please enter a valid account username.', 'cdfs-addon' ) );
			}

			if ( username_exists( $username ) ) {
				return new WP_Error( 'registration-error-username-exists', esc_html__( 'An account is already registered with that username. Please choose another.', 'cdfs-addon' ) );
			}
		} else {
			return new WP_Error( 'registration-error-username-empty', esc_html__( 'Please enter username.', 'cdfs-addon' ) );
		}

		// Handle password creation.
		if ( empty( $password ) ) {
			return new WP_Error( 'registration-error-missing-password', esc_html__( 'Please enter an account password.', 'cdfs-addon' ) );
		}

		// Use WP_Error to handle registration errors.
		$errors = new WP_Error();
		do_action( 'cdfs_register_post', $username, $email, $errors );
		$errors = apply_filters( 'cdfs_registration_errors', $errors, $username, $email );

		if ( $errors->get_error_code() ) {
			return $errors;
		}

		$new_user_data = apply_filters(
			'cdfs_new_customer_data',
			array(
				'user_login' => $username,
				'user_pass'  => $password,
				'user_email' => $email,
				'role'       => 'car_dealer',
			)
		);

		$user_id = wp_insert_user( $new_user_data );
		if ( is_wp_error( $user_id ) ) {
			return new WP_Error( 'registration-error', '<strong>' . esc_html__( 'Error:', 'cdfs-addon' ) . '</strong> ' . esc_html__( 'Couldn\'t register you. Please contact us if you continue to have problems.', 'cdfs-addon' ) );
		}
		// Set status of new user.
		$userinfo        = array(
			'email'     => $email,
			'user_data' => $new_user_data,
			'user_id'   => $user_id,
		);
		$activate_method = cdfs_user_activation_method();
		if ( 'mail' === $activate_method ) {
			add_user_meta( $user_id, 'cdfs_user_status', 'pending', true );
			$mail_sent = cdfs_send_activation_link_mail( $userinfo ); // send activation link.
			if ( false === $mail_sent ) {
				return false;
			}
		} elseif ( 'admin' === $activate_method ) {
			add_user_meta( $user_id, 'cdfs_user_status', 'pending', true );
			$mail_sent = cdfs_send_registration_pending_for_admin_approval_mail( $userinfo );
			if ( false === $mail_sent ) {
				return false;
			}
		} else {
			$mail_sent = cdfs_send_registration_mail( $userinfo );
			if ( false === $mail_sent ) {
				return false;
			}
		}
		return $user_id;
	}
}

if ( ! function_exists( 'cdfs_clean' ) ) {
	/**
	 * Cdfs clean
	 *
	 * @param string $var .
	 */
	function cdfs_clean( $var ) {
		if ( is_array( $var ) ) {
			return array_map( 'cdfs_clean', $var );
		} else {
			return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		}
	}
}

if ( ! function_exists( 'cdfs_is_user_account_page' ) ) {
	/**
	 * User account page
	 */
	function cdfs_is_user_account_page() {
		global $post;
		return is_singular() && is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'cardealer_my_account' );
	}
}

if ( ! function_exists( 'cdfs_reset_forgot_psw_url' ) ) {
	/**
	 * Reset forgot password link displayed when wrong login details are added
	 *
	 * @param string $lostpassword_url .
	 * @param string $redirect .
	 */
	function cdfs_reset_forgot_psw_url( $lostpassword_url, $redirect ) {
		if ( isset( $_POST['cdfs-login-nonce'] ) ) {
			return cdfs_get_page_permalink( 'myuseraccount' ) . 'user-lost-password';
		}
		return $lostpassword_url;
	}
	add_filter( 'lostpassword_url', 'cdfs_reset_forgot_psw_url', 10, 2 );
}


add_action( 'wp_ajax_cdfs_do_ajax_user_login', 'cdfs_do_user_login' );
add_action( 'wp_ajax_nopriv_cdfs_do_ajax_user_login', 'cdfs_do_user_login' );
if ( ! function_exists( 'cdfs_do_user_login' ) ) {
	/**
	 * User login with ajax
	 *
	 * @throws Exception In case of failures, an exception is thrown.
	 */
	function cdfs_do_user_login() {
		$responsearray = array(
			'status'  => false,
			'message' => esc_html__( 'Something went wrong!', 'cdfs-addon' ),
		);
		$nonce_value   = isset( $_POST['cdfs-login-nonce'] ) ? cdfs_clean( $_POST['cdfs-login-nonce'] ) : '';

		if ( isset( $_POST['action'] ) && 'cdfs_do_ajax_user_login' === $_POST['action'] && wp_verify_nonce( $nonce_value, 'cdfs-login' ) ) {

			if ( ! cdfs_validate_captcha() ) { // captcha serverd side validation.
				echo wp_json_encode(
					array(
						'status'  => false,
						'message' => '<strong>' . esc_html__(
							'Error:',
							'cdfs-addon'
						) . '</strong> ' . esc_html__(
							'Please check captcha form!',
							'cdfs-addon'
						),
					)
				);
				die;
			}
			try {
				$creds = array(
					'user_password' => cdfs_clean( wp_unslash( $_POST['password'] ) ),
					'remember'      => isset( $_POST['rememberme'] ),
				);

				if ( empty( $_POST['username'] ) || empty( $_POST['password'] ) ) {
					throw new Exception( '<strong>' . esc_html__( 'Error:', 'cdfs-addon' ) . '</strong> ' . esc_html__( 'Please fill the required fields.', 'cdfs-addon' ) );
				}

				$username         = trim( cdfs_clean( wp_unslash( $_POST['username'] ) ) );
				$validation_error = new WP_Error();
				$validation_error = apply_filters( 'cdfs_process_user_login_errors', $validation_error, $username, wp_unslash( $_POST['password'] ) );

				if ( $validation_error->get_error_code() ) {
					throw new Exception( '<strong>' . esc_html__( 'Error:', 'cdfs-addon' ) . '</strong> ' . $validation_error->get_error_message() );
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
				$user = wp_signon( apply_filters( 'cdfs_login_credentials', $creds ), is_ssl() );

				if ( is_wp_error( $user ) ) {
					$message = $user->get_error_message();
					$message = str_replace( '<strong>' . esc_html( $creds['user_login'] ) . '</strong>', '<strong>' . esc_html( $username ) . '</strong>', $message );
					throw new Exception( $message );
				} else {
					// get user info.
					$userinfo = get_user_meta( $user->ID, 'cdfs_user_status', true );
					// If user login with roles other than car_dealer.
					if ( ! in_array( 'car_dealer', $user->roles ) && ! in_array( 'customer', $user->roles ) ) {
						wp_logout();
						$responsearray = array(
							'status'  => false,
							'message' => esc_html__( 'Please login with "Dealer Or Customer" account.', 'cdfs-addon' ),
						);
					} elseif ( ! empty( $userinfo ) && 'pending' === $userinfo ) { // check user status.
						wp_logout();

						$activate_method = cdfs_user_activation_method();
						if ( 'mail' === $activate_method ) {
							$responsearray = array(
								'status'  => false,
								'message' => esc_html__( 'Please activate your account with the activation link sent to your registered mail account.', 'cdfs-addon' ),
							);
						} elseif ( 'admin' === $activate_method ) {
							$responsearray = array(
								'status'  => false,
								'message' => esc_html__( 'Your account is waiting for admin approval. Once your account is approved by the admin, you will be able to manage vehicles.', 'cdfs-addon' ),
							);
						}
					} else {
						$logined_user  = explode( '@', $username, 2 );
						$logined_user  = $logined_user[0];
						$responsearray = array(
							'status'         => true,
							'cdfs_user_name' => $logined_user,
							'message'        => esc_html__( 'Successfully logged in!', 'cdfs-addon' ),
						);
					}
				}
			} catch ( Exception $e ) {
				$responsearray = array(
					'status'  => false,
					'message' => $e->getMessage(),
				);
			}
		}
		echo wp_json_encode( $responsearray );
		die;
	}
}


add_action( 'wp_ajax_cdfs_do_ajax_user_register', 'cdfs_do_user_registration' );
add_action( 'wp_ajax_nopriv_cdfs_do_ajax_user_register', 'cdfs_do_user_registration' );
if ( ! function_exists( 'cdfs_do_user_registration' ) ) {
	/**
	 * User login with ajax
	 *
	 * @throws Exception In case of failures, an exception is thrown.
	 */
	function cdfs_do_user_registration() {
		$responsearray = array(
			'status'  => false,
			'message' => esc_html__( 'Something went wrong!', 'cdfs-addon' ),
		);
		$nonce_value   = isset( $_POST['cdfs-register-nonce'] ) ? wp_unslash( $_POST['cdfs-register-nonce'] ) : '';

		if ( isset( $_POST['action'] ) && 'cdfs_do_ajax_user_register' === $_POST['action'] && wp_verify_nonce( $nonce_value, 'cdfs-register' ) ) {
			$username = wp_unslash( $_POST['username'] );
			$password = wp_unslash( $_POST['password'] );
			$email    = wp_unslash( $_POST['email'] );

			if ( ! cdfs_validate_captcha() ) { // captcha server side validation.
				echo wp_json_encode(
					array(
						'status'  => false,
						'message' => '<strong>' . esc_html__(
							'Error:',
							'cdfs-addon'
						) . '</strong> ' . esc_html__(
							'Please check captcha form!',
							'cdfs-addon'
						),
					)
				);
				die;
			}

			try {
				if ( empty( $_POST['username'] ) || empty( $_POST['password'] ) || empty( $_POST['email'] ) ) {
					throw new Exception( esc_html__( 'Please fill the required fields.', 'cdfs-addon' ) );
				}

				$validation_error = new WP_Error();
				$validation_error = apply_filters( 'cdfs_process_user_registration_errors', $validation_error, $username, $password, $email );

				if ( $validation_error->get_error_code() ) {
					throw new Exception( $validation_error->get_error_message() );
				}

				$new_user = cdfs_create_user( sanitize_email( $email ), cdfs_clean( $username ), $password );

				if ( is_wp_error( $new_user ) ) {
					throw new Exception( $new_user->get_error_message() );
				} else {
					// if success, then login automatically.
					$user = wp_signon(
						apply_filters(
							'cdfs_login_credentials',
							array(
								'user_login'    => cdfs_clean( $username ),
								'user_password' => cdfs_clean( $password ),
								'remember'      => false,
							)
						),
						is_ssl()
					);

					if ( is_wp_error( $user ) ) {
						$message = $user->get_error_message();
						$message = str_replace( '<strong>' . esc_html( $creds['user_login'] ) . '</strong>', '<strong>' . esc_html( $username ) . '</strong>', $message );
						throw new Exception( $message );
					}
					$logined_user  = explode( '@', $username, 2 );
					$logined_user  = $logined_user[0];
					$responsearray = array(
						'status'         => true,
						'cdfs_user_name' => $logined_user,
						'message'        => esc_html__( 'You are successfully registered!', 'cdfs-addon' ),
					);
				}
			} catch ( Exception $e ) {
				$responsearray = array(
					'status'  => false,
					'message' => '<strong>' . esc_html__( 'Error:', 'cdfs-addon' ) . '</strong> ' . $e->getMessage(),
				);
			}
		}
		echo wp_json_encode( $responsearray );
		die;
	}
}


if ( ! function_exists( 'cdfs_send_activation_link_mail' ) ) {
	/**
	 * Send user/dealer registration mail
	 *
	 * @param string $userinfo .
	 */
	function cdfs_send_activation_link_mail( $userinfo = array() ) {
		if ( empty( $userinfo ) ) {
			return false;
		}
		// get site details.
		$site_title = get_bloginfo( 'name' );
		$site_email = get_bloginfo( 'admin_email' );
		$site_url   = site_url();

		// Send email notification.
		$to       = $userinfo['email'];
		$subject  = esc_html__( 'New Registration', 'cdfs-addon' );
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type:text/html;charset=UTF-8' . "\r\n";
		$headers .= 'From: ' . $site_title . ' <' . $site_email . '>' . "\r\n";

		// Mail to user.
		ob_start();
		// make activation_token.

		// generate random string.
		$str              = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$random_string    = substr( str_shuffle( str_repeat( $str, ceil( 10 / strlen( $str ) ) ) ), 0, 10 );
		$activation_token = md5( uniqid( $random_string . $userinfo['email'] . time(), true ) );
		cdfs_get_template(
			'mails/mail-register-user.php',
			array(
				'user_data'        => $userinfo['user_data'],
				'mail_to'          => 'user',
				'site_data'        => array(
					'site_title' => $site_title,
					'site_url'   => $site_url,
				),
				'activation_token' => $activation_token,
				'action'           => 'activation_link_mail',
			)
		);
		$user_message = ob_get_contents();
		ob_end_clean();

		// send mail.
		try {
			wp_mail( $to, $subject, $user_message, $headers ); // Mail to user.
			add_user_meta( $userinfo['user_id'], 'cdfs_user_activation_tkn', $activation_token, true ); // set activation token.
			return true;
		} catch ( Exception $e ) {
			cdfs_add_notice( $e->getMessage(), 'error' );
			return false;
		}
		return false;
	}
}

if ( ! function_exists( 'cdfs_send_registration_mail' ) ) {
	/**
	 * Send user/dealer registration mail
	 *
	 * @param array $userinfo .
	 */
	function cdfs_send_registration_mail( $userinfo = array() ) {
		if ( empty( $userinfo ) ) {
			return false;
		}
		// Get site details.
		$site_title = get_bloginfo( 'name' );
		$site_email = get_bloginfo( 'admin_email' );
		$site_url   = site_url();
		// Send email notification.
		$to            = $userinfo['email'];
		$admin_subject = esc_html__( 'New Dealer Registration', 'cdfs-addon' );
		$user_subject  = esc_html__( 'Registration Success', 'cdfs-addon' );
		$headers       = 'MIME-Version: 1.0' . "\r\n";
		$headers      .= 'Content-type:text/html;charset=UTF-8' . "\r\n";
		$headers      .= 'From: ' . $site_title . ' <' . $site_email . '>' . "\r\n";

		// Mail to user.
		ob_start();
		cdfs_get_template(
			'mails/mail-register-user.php',
			array(
				'user_data' => $userinfo['user_data'],
				'mail_to'   => 'user',
				'site_data' => array(
					'site_title' => $site_title,
					'site_url'   => $site_url,
				),
				'action'    => 'registration_mail',
			)
		);
		$user_message = ob_get_contents();
		ob_end_clean();

		// Mail to admin.
		ob_start();
		cdfs_get_template(
			'mails/mail-register-user.php',
			array(
				'user_data' => $userinfo['user_data'],
				'mail_to'   => 'admin',
				'site_data' => array(
					'site_title' => $site_title,
					'site_url'   => $site_url,
				),
				'action'    => 'admin_register_user',
			)
		);
		$admin_message = ob_get_contents();
		ob_end_clean();

		// send mail.
		try {
			wp_mail( $to, $user_subject, $user_message, $headers ); // mail to user.
			wp_mail( $site_email, $admin_subject, $admin_message, $headers ); // Mail to admin.
			return true;
		} catch ( Exception $e ) {
			cdfs_add_notice( $e->getMessage(), 'error' );
			return false;
		}
		return false;
	}
}

if ( ! function_exists( 'cdfs_send_registration_pending_for_admin_approval_mail' ) ) {
	/**
	 * Send user/dealer registration mail
	 *
	 * @param array $userinfo .
	 */
	function cdfs_send_registration_pending_for_admin_approval_mail( $userinfo = array() ) {
		if ( empty( $userinfo ) ) {
			return false;
		}
		// get site details.
		$site_title = get_bloginfo( 'name' );
		$site_email = get_bloginfo( 'admin_email' );
		$site_url   = site_url();
		// Send email notification.
		$to            = $userinfo['email'];
		$admin_subject = esc_html__( 'New Dealer Registration', 'cdfs-addon' );
		$user_subject  = esc_html__( 'Registration Success.', 'cdfs-addon' );
		$headers       = 'MIME-Version: 1.0' . "\r\n";
		$headers      .= 'Content-type:text/html;charset=UTF-8' . "\r\n";
		$headers      .= 'From: ' . $site_title . ' <' . $site_email . '>' . "\r\n";

		// Mail to user.
		ob_start();
		cdfs_get_template(
			'mails/mail-register-user.php',
			array(
				'user_data' => $userinfo['user_data'],
				'mail_to'   => 'user',
				'site_data' => array(
					'site_title' => $site_title,
					'site_url'   => $site_url,
				),
				'action'    => 'registration_pending_for_admin_approval_mail',
			)
		);
		$user_message = ob_get_contents();
		ob_end_clean();

		// Mail to admin.
		ob_start();
		cdfs_get_template(
			'mails/mail-register-user.php',
			array(
				'user_data' => $userinfo['user_data'],
				'mail_to'   => 'admin',
				'site_data' => array(
					'site_title' => $site_title,
					'site_url'   => $site_url,
				),
				'action'    => 'admin_register_user',
			)
		);
		$admin_message = ob_get_contents();
		ob_end_clean();

		// send mail.
		try {
			wp_mail( $to, $user_subject, $user_message, $headers ); // mail to user.
			wp_mail( $site_email, $admin_subject, $admin_message, $headers ); // Mail to admin.
			return true;
		} catch ( Exception $e ) {
			cdfs_add_notice( $e->getMessage(), 'error' );
			return false;
		}
		return false;
	}
}

if ( ! function_exists( 'cdfs_send_user_account_status_change_mail' ) ) {
	/**
	 * Send user/dealer registration mail
	 *
	 * @param array $userinfo .
	 */
	function cdfs_send_user_account_status_change_mail( $userinfo = array() ) {
		if ( empty( $userinfo ) ) {
			return false;
		}

		// get site details.
		$site_title = get_bloginfo( 'name' );
		$site_email = get_bloginfo( 'admin_email' );
		$site_url   = site_url();
		// Send email notification.
		$to           = $userinfo->data->user_email;
		$user_subject = esc_html__( 'Account activation alert.', 'cdfs-addon' );
		$headers      = 'MIME-Version: 1.0' . "\r\n";
		$headers     .= 'Content-type:text/html;charset=UTF-8' . "\r\n";
		$headers     .= 'From: ' . $site_title . ' <' . $site_email . '>' . "\r\n";
		$user_data    = array(
			'user_login' => $userinfo->data->user_login,
			'user_email' => $userinfo->data->user_email,
		);
		// Mail to user.
		ob_start();
		cdfs_get_template(
			'mails/mail-register-user.php',
			array(
				'user_data' => $user_data,
				'mail_to'   => 'user',
				'site_data' => array(
					'site_title' => $site_title,
					'site_url'   => $site_url,
				),
				'action'    => 'send_user_account_status_change_mail',
			)
		);
		$user_message = ob_get_contents();
		ob_end_clean();

		// send mail.
		try {
			wp_mail( $to, $user_subject, $user_message, $headers ); // mail to user.
			return true;
		} catch ( Exception $e ) {
			cdfs_add_notice( $e->getMessage(), 'error' );
			return false;
		}
		return false;
	}
}

if ( ! function_exists( 'cdfs_send_user_activation_mail' ) ) {
	/**
	 * Send user/dealer registration mail
	 *
	 * @param array $userinfo .
	 */
	function cdfs_send_user_activation_mail( $userinfo = array() ) {
		if ( empty( $userinfo ) ) {
			return false;
		}
		// get site details.
		$site_title = get_bloginfo( 'name' );
		$site_email = get_bloginfo( 'admin_email' );
		$site_url   = site_url();
		// Send email notification.
		$to       = $userinfo['user_email'];
		$subject  = esc_html__( 'Acccount Activated - DO NOT REPLY', 'cdfs-addon' );
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type:text/html;charset=UTF-8' . "\r\n";
		$headers .= 'From: ' . $site_title . ' <' . $site_email . '>' . "\r\n";

		// Mail to user.
		ob_start();
		cdfs_get_template(
			'mails/mail-register-user.php',
			array(
				'user_data' => $userinfo,
				'mail_to'   => 'user',
				'site_data' => array(
					'site_title' => $site_title,
					'site_url'   => $site_url,
				),
				'action'    => 'activation_mail',
			)
		);
		$user_message = ob_get_contents();
		ob_end_clean();

		// Mail to admin.
		ob_start();
		cdfs_get_template(
			'mails/mail-register-user.php',
			array(
				'user_data' => $userinfo,
				'mail_to'   => 'admin',
				'site_data' => array(
					'site_title' => $site_title,
					'site_url'   => $site_url,
				),
				'action'    => 'admin_user_activated',
			)
		);
		$admin_message = ob_get_contents();
		ob_end_clean();

		// send mail.
		try {
			wp_mail( $to, $subject, $user_message, $headers ); // Mail to user.
			wp_mail( $site_email, $subject, $admin_message, $headers ); // Mail to admin.
			return true;
		} catch ( Exception $e ) {
			cdfs_add_notice( $e->getMessage(), 'error' );
			return false;
		}
		return false;
	}
}

if ( ! function_exists( 'cdfs_activate_user_account_by_token' ) ) {
	/**
	 * Function which activates user account
	 *
	 * @param string $token .
	 */
	function cdfs_activate_user_account_by_token( $token ) {
		if ( ! empty( $token ) ) {
			$user_args = array(
				'role'       => 'car_dealer',
				'meta_key'   => 'cdfs_user_activation_tkn',
				'meta_value' => $token,
			);
			$user      = get_users( $user_args );
			if ( ! empty( $user ) && isset( $user[0]->ID ) ) {
				$user_status = get_user_meta( $user[0]->ID, 'cdfs_user_status', true );
				if ( ! empty( $user_status ) && ( 'active' === $user_status ) ) {
					cdfs_add_notice( esc_html__( 'Your account is already active.', 'cdfs-addon' ), 'success' );
					return true;
				}
				update_user_meta( $user[0]->ID, 'cdfs_user_status', 'active' );
				$user_data = array(
					'user_name'  => $user[0]->data->user_login,
					'user_email' => $user[0]->data->user_email,
				);
				cdfs_send_user_activation_mail( $user_data );
				cdfs_add_notice( esc_html__( 'Congratulations! Your account is activated successfully.', 'cdfs-addon' ), 'success' );
				return true;
			}
		}
		cdfs_add_notice( esc_html__( 'Error! Invalid activation link.', 'cdfs-addon' ), 'error' );
		return false;
	}
}

if ( ! function_exists( 'cdfs_get_post_limits' ) ) {
	/**
	 * Get post limits
	 *
	 * @param string $user_id .
	 */
	function cdfs_get_post_limits( $user_id ) {
		global $car_dealer_options;
		$user_id       = intval( $user_id );
		$created_posts = 0;
		$allow_posts   = 0;
		$allow_images  = 0;
		$product_id    = '';
		$post_limit    = '';

		$post_limit = isset( $car_dealer_options['cdfs_cars_limit'] ) ? $car_dealer_options['cdfs_cars_limit'] : 0;
		$img_limit  = isset( $car_dealer_options['cdfs_cars_img_limit'] ) ? $car_dealer_options['cdfs_cars_img_limit'] : 0;

		if ( ! empty( $user_id ) ) {
			$post_status = array( 'publish', 'pending', 'draft' );
			$args        = array(
				'post_type'      => 'cars',
				'post_status'    => $post_status,
				'posts_per_page' => -1,
				'author'         => $user_id,
			);

			$query = new WP_Query( $args );
			if ( ! empty( $query->found_posts ) ) {
				$created_posts = intval( $query->found_posts );
			}

			if ( class_exists( 'Subscriptio' ) || class_exists( 'RP_SUB' ) ) {
				$user_subscriptions = array();

				if ( function_exists( 'subscriptio_get_customer_subscriptions' ) ) {
					$user_subscriptions = subscriptio_get_customer_subscriptions( $user_id );
				}

				if ( ! empty( $user_subscriptions ) ) {

					$user_subscription = reset( $user_subscriptions );

					$status = $user_subscription->get_status();

					$_product_id = '';

					if ( empty( $user_subscriptions ) ) {
						update_user_meta( $user_id, 'cdfs_img_limt', $img_limit );
					}

					if ( 'active' === $status ) {
						$post_limit = intval( get_user_meta( $user_id, 'cdfs_car_limt', true ) );
					}
				}
			}
		}

		$post_limit  = ( ! empty( $post_limit ) ) ? $post_limit : $car_dealer_options['cdfs_cars_limit'];
		$allow_posts = intval( $post_limit ) - intval( $created_posts );
		$allow_posts = ( $allow_posts < 1 ) ? 0 : $allow_posts;

		return $allow_posts;
	}
}

add_action( 'subscriptio_subscription_status_changing', 'cdfs_subscription_status_changing', 10, 3 );
/**
 * Subscription status changing
 *
 * @param string $subscription .
 * @param string $old_status .
 * @param string $new_status .
 */
function cdfs_subscription_status_changing( $subscription, $old_status, $new_status ) {

	$temp_car_limt = 0;

	$subscription_id = $subscription->get_id();
	$customer_id     = $subscription->get_customer_id();

	$user_car_limt = get_user_meta( $customer_id, 'cdfs_car_limt', true );
	$user_img_limt = get_user_meta( $customer_id, 'cdfs_img_limt', true );

	$user_car_limt = ( $user_car_limt < 1 ) ? 0 : intval( $user_car_limt );
	$user_img_limt = ( $user_img_limt < 1 ) ? 0 : intval( $user_img_limt );

	$subscription_car_limt = get_post_meta( $subscription_id, 'cdfs_car_limt', true );
	$subscription_img_limt = get_post_meta( $subscription_id, 'cdfs_img_limt', true );

	$subscription_car_limt = intval( $subscription_car_limt );

	if ( 'pending' !== $old_status ) {
		if ( 'active' === $new_status ) {
			$temp_car_limt = $user_car_limt + $subscription_car_limt;
			$temp_car_limt = ( $temp_car_limt < 1 ) ? 0 : intval( $temp_car_limt );

			update_user_meta( $customer_id, 'cdfs_car_limt', $temp_car_limt );
		}

		if ( 'paused' === $new_status || 'set-to-cancel' === $new_status || 'cancelled' === $new_status ) {
			$temp_car_limt = $user_car_limt - $subscription_car_limt;
			$temp_car_limt = ( $temp_car_limt < 1 ) ? 0 : intval( $temp_car_limt );

			update_user_meta( $customer_id, 'cdfs_car_limt', $temp_car_limt );
		}
	} else {
		if ( 'active' === $new_status ) {
			$items = $subscription->get_items();
			foreach ( $items as $item ) {
				$_product_id = $item->get_product_id();
				if ( subscriptio_is_subscription_product( $_product_id ) ) {

					$post_limit  = intval( get_post_meta( $_product_id, 'cdfs_car_quota', true ) );
					$image_limit = intval( get_post_meta( $_product_id, 'cdfs_car_images_quota', true ) );

					$cdfs_img_limit = intval( $image_limit );
					$cdfs_car_limit = intval( $user_car_limt ) + intval( $post_limit );

					$subscription_img_limt = intval( $image_limit );
					$subscription_car_limt = intval( $subscription_car_limt ) + intval( $post_limit );

					update_post_meta( $subscription_id, 'cdfs_car_limt', $subscription_car_limt );
					update_post_meta( $subscription_id, 'cdfs_img_limt', $subscription_img_limt );

					update_user_meta( $customer_id, 'cdfs_car_limt', $cdfs_car_limit );
					update_user_meta( $customer_id, 'cdfs_img_limt', $cdfs_img_limit );
				}
			}
		}
	}

}
