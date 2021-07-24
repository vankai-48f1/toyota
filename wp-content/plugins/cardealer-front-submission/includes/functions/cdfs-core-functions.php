<?php
/**
 * CDFS Core Functions
 *
 * @author   PotenzaGlobalSolutions
 * @category Class
 * @package  CDFS/Classes
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once ABSPATH . 'wp-includes/pluggable.php';


if ( ! function_exists( 'cdfs_get_shortcode_templates' ) ) {
	/**
	 * Get shortcode template parts.
	 *
	 * @param string $slug .
	 * @param string $name .
	 * @param string $atts .
	 */
	function cdfs_get_shortcode_templates( $slug, $name = '', $atts = '' ) {
		if ( ! empty( $atts ) && is_array( $atts ) ) {
			extract( $atts );
		}

		$template = '';

		$template_path = 'cardealer-front-submission/templates/';
		$plugin_path   = trailingslashit( CDFS_PATH );
		// Look in yourtheme/template-parts/shortcodes/slug-name.php.
		if ( $name ) {
			$template = locate_template(
				array(
					$template_path . "{$slug}-{$name}.php",
				)
			);
		}

		// Get default slug-name.php.
		if ( ! $template && $name && file_exists( $plugin_path . "templates/{$slug}-{$name}.php" ) ) {
			$template = $plugin_path . "templates/{$slug}-{$name}.php";
		}

		// If template file doesn't exist, look in yourtheme/template-parts/shortcodes/slug.php.
		if ( ! $template ) {
			$template = locate_template(
				array(
					$template_path . "{$slug}.php",
				)
			);
		}

		// Get default slug.php.
		if ( ! $template && file_exists( $plugin_path . "templates/{$slug}.php" ) ) {
			$template = $plugin_path . "templates/{$slug}.php";
		}

		// Allow 3rd party plugins to filter template file from their plugin.
		$template = apply_filters( 'cdfs_get_shortcode_templates', $template, $slug, $name );

		if ( $template ) {
			include $template;
		}
	}
}


if ( ! function_exists( 'cdfs_get_template' ) ) {
	/**
	 * Get other templates.
	 *
	 * @access public
	 * @param string $template_name .
	 * @param array  $args (default: array()) .
	 * @param string $template_path (default: '') .
	 * @param string $default_path (default: '') .
	 */
	function cdfs_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args );
		}

		$located = cdfs_locate_template( $template_name, $template_path, $default_path );

		if ( ! file_exists( $located ) ) {
			return;
		}

		// Allow 3rd party plugin filter template file from their plugin.
		$located = apply_filters( 'cdfs_get_template', $located, $template_name, $args, $template_path, $default_path );

		do_action( 'cdfs_before_template_part', $template_name, $template_path, $located, $args );

		include $located;

		do_action( 'cdfs_after_template_part', $template_name, $template_path, $located, $args );
	}
}

if ( ! function_exists( 'cdfs_locate_template' ) ) {
	/**
	 * Locate a template and return the path for inclusion.
	 *
	 * This is the load order:
	 *
	 *      yourtheme       /   $template_path  /   $template_name
	 *      yourtheme       /   $template_name
	 *      $default_path   /   $template_name
	 *
	 * @access public
	 * @param string $template_name .
	 * @param string $template_path (default: '') .
	 * @param string $default_path (default: '') .
	 * @return string
	 */
	function cdfs_locate_template( $template_name, $template_path = '', $default_path = '' ) {
		if ( ! $template_path ) {
			$template_path = 'cardealer-front-submission/templates';
		}

		if ( ! $default_path ) {
			$default_path = untrailingslashit( plugin_dir_path( CDFS_PLUGIN_FILE ) ) . '/templates/';
		}

		// Look within passed path within the theme - this is priority.
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name,
			)
		);

		// Get default template.
		if ( ! $template ) {
			$template = $default_path . $template_name;
		}

		/**
		 * Filters template path of the requested template file.
		 *
		 * @since 1.0
		 * @param string    $template       Full Path of the template.
		 * @param string    $template_path  Template path in plugin for all template files.
		 * @param string    $template_name  Name of the template to locate.
		 * @visible         true
		 */
		return apply_filters( 'cdfs_locate_template', $template, $template_name, $template_path );
	}
}


if ( ! function_exists( 'cdfs_get_account_menu_items' ) ) {
	/**
	 * Get My Account menu items.
	 *
	 * @return array
	 */
	function cdfs_get_account_menu_items() {
		$endpoints = array(
			'user-edit-account' => 'edit-account',
			'user-logout'       => 'user-logout',
			'add-car'           => 'add-car',
		);

		$items = array(
			'dashboard'         => esc_html__( 'Dashboard', 'cdfs-addon' ),
			'user-edit-account' => esc_html__( 'Account details', 'cdfs-addon' ),
			'add-car'           => esc_html__( 'Add New Vehicle', 'cdfs-addon' ),
			'user-logout'       => esc_html__( 'Logout', 'cdfs-addon' ),
		);

		// Remove missing endpoints.
		foreach ( $endpoints as $endpoint_id => $endpoint ) {
			if ( empty( $endpoint ) ) {
				unset( $items[ $endpoint_id ] );
			}
		}
		return apply_filters( 'cdfs_account_menu_items', $items );
	}
}

if ( ! function_exists( 'cdfs_get_menu_item_classes' ) ) {
	/**
	 * Get account menu item classes.
	 *
	 * @param string $endpoint .
	 * @return string
	 */
	function cdfs_get_menu_item_classes( $endpoint ) {
		global $wp;

		$classes = array(
			'cdfs-my-account-navigation-link',
			'cdfs-my-account-navigation-link-' . $endpoint,
		);

		// Set current item class.
		$current = isset( $wp->query_vars[ $endpoint ] );
		if ( 'dashboard' === $endpoint && ( isset( $wp->query_vars['page'] ) || empty( $wp->query_vars ) ) ) {
			$current = true; // Dashboard is not an endpoint, so needs a custom check.
		}

		if ( $current ) {
			$classes[] = 'is-active';
		}

		$classes = apply_filters( 'cdfs_account_menu_item_classes', $classes, $endpoint );

		return implode( ' ', array_map( 'sanitize_html_class', $classes ) );
	}
}

if ( ! function_exists( 'cdfs_get_my_endpoint_url' ) ) {
	/**
	 * Get account endpoint URL.
	 *
	 * @param string $endpoint .
	 * @return string
	 */
	function cdfs_get_my_endpoint_url( $endpoint ) {

		$pages = get_pages(
			array(
				'meta_key'   => '_wp_page_template',
				'meta_value' => 'templates/cardealer-front-submission.php',
			)
		);

		if ( ! empty( $pages ) && isset( $pages[0] ) ) {
			$dashboard_link = get_permalink( $pages[0]->ID );
		} else {
			$dashboard_link = cdfs_get_page_permalink( 'myuseraccount' );
		}

		if ( 'dashboard' === $endpoint ) {
			return $dashboard_link;
		}

		return cdfs_get_endpoint_url( $endpoint, '', $dashboard_link );
	}
}

if ( ! function_exists( 'cdfs_get_page_permalink' ) ) {
	/**
	 * Retrieve page permalink.
	 *
	 * @param string $page .
	 * @return string
	 */
	function cdfs_get_page_permalink( $page ) {
		$page_id   = cdfs_get_page_id( $page );
		$permalink = 0 < $page_id ? get_permalink( $page_id ) : get_home_url();
		return apply_filters( 'cdfs_get_' . $page . '_page_permalink', $permalink );
	}
}

if ( ! function_exists( 'cdfs_get_page_id' ) ) {
	/**
	 * Retrieve page ids - used for myuseraccount.returns -1 if no page is found.
	 *
	 * @param string $page .
	 * @return int
	 */
	function cdfs_get_page_id( $endpoint ) {
		$page_id = apply_filters( 'cdfs_get_' . $endpoint . '_page_id', get_option( 'cdfs_' . $endpoint . '_page_id' ), $endpoint );

		return $page_id ? absint( $page_id ) : -1;
	}
}


if ( ! function_exists( 'cdfs_create_page' ) ) {
	/**
	 * Create a page and store the ID in an option.
	 *
	 * @param mixed  $slug Slug for the new page .
	 * @param string $option Option name to store the page's ID .
	 * @param string $page_title (default: '') Title for the new page .
	 * @param string $page_content (default: '') Content for the new page .
	 * @param int    $post_parent (default: 0) Parent for the new page .
	 * @return int page ID
	 */
	function cdfs_create_page( $slug, $option = '', $page_title = '', $page_content = '', $post_parent = 0 ) {
		global $wpdb;

		$option_value = get_option( $option );

		if ( $option_value > 0 && ( $page_object = get_post( $option_value ) ) ) {
			if ( 'page' === $page_object->post_type && ! in_array( $page_object->post_status, array( 'pending', 'trash', 'future', 'auto-draft' ) ) ) {
				// Valid page is already in place.
				return $page_object->ID;
			}
		}

		if ( strlen( $page_content ) > 0 ) {
			// Search for an existing page with the specified page content (typically a shortcode).
			$valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' ) AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
		} else {
			// Search for an existing page with the specified page slug.
			$valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' )  AND post_name = %s LIMIT 1;", $slug ) );
		}

		$valid_page_found = apply_filters( 'cdfs_create_page_id', $valid_page_found, $slug, $page_content );

		if ( $valid_page_found ) {
			if ( $option ) {
				update_option( $option, $valid_page_found );
			}
			return $valid_page_found;
		}

		// Search for a matching valid trashed page.
		if ( strlen( $page_content ) > 0 ) {
			// Search for an existing page with the specified page content (typically a shortcode).
			$trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
		} else {
			// Search for an existing page with the specified page slug.
			$trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_name = %s LIMIT 1;", $slug ) );
		}

		if ( $trashed_page_found ) {
			$page_id   = $trashed_page_found;
			$page_data = array(
				'ID'          => $page_id,
				'post_status' => 'publish',
			);
			wp_update_post( $page_data );
		} else {
			$page_data = array(
				'post_status'    => 'publish',
				'post_type'      => 'page',
				'post_author'    => 1,
				'post_name'      => $slug,
				'post_title'     => $page_title,
				'post_content'   => $page_content,
				'post_parent'    => $post_parent,
				'comment_status' => 'closed',
			);
			$page_id   = wp_insert_post( $page_data );
		}

		if ( $option ) {
			update_option( $option, $page_id );
		}
		return $page_id;
	}
}


if ( ! function_exists( 'cdfs_get_endpoint_url' ) ) {
	/**
	 * Get endpoint URL.
	 *
	 * Gets the URL for an endpoint, which varies depending on permalink settings.
	 *
	 * @param  string $endpoint .
	 * @param  string $value .
	 * @param  string $permalink .
	 *
	 * @return string
	 */
	function cdfs_get_endpoint_url( $endpoint, $value = '', $permalink = '' ) {
		if ( ! $permalink ) {
			$permalink = get_permalink();
		}

		if ( get_option( 'permalink_structure' ) ) {
			if ( strstr( $permalink, '?' ) ) {
				$query_string = '?' . parse_url( $permalink, PHP_URL_QUERY );
				$permalink    = current( explode( '?', $permalink ) );
			} else {
				$query_string = '';
			}
			$url = trailingslashit( $permalink ) . $endpoint . '/' . $value . $query_string;
		} else {
			$url = add_query_arg( $endpoint, $value, $permalink );
		}
		return apply_filters( 'cdfs_get_endpoint_url', $url, $endpoint, $value, $permalink );
	}
}


if ( ! function_exists( 'cdfs_add_notice' ) ) {
	/**
	 * Add and store a notice.
	 *
	 * @param string $message .
	 * @param string $notice_type .
	 */
	function cdfs_add_notice( $message, $notice_type = 'success' ) {
		if ( CDFS()->session ) {
			$notices = CDFS()->session->get( 'cdfs_notices', array() );
			// Backward compatibility.
			if ( 'success' === $notice_type ) {
				$message = apply_filters( 'cdfs_add_message', $message );
			}

			$notices[ $notice_type ][] = apply_filters( 'cdfs_add_' . $notice_type, $message );
			CDFS()->session->set( 'cdfs_notices', $notices );
		}
	}
}

if ( ! function_exists( 'cdfs_print_notices' ) ) {
	/**
	 * Print notices
	 */
	function cdfs_print_notices() {
		if ( CDFS()->session ) {
			$all_notices  = CDFS()->session->get( 'cdfs_notices' );
			$notice_types = apply_filters( 'cdfs_notice_types', array( 'error', 'success', 'notice' ) );
			foreach ( $notice_types as $notice_type ) {
				if ( cdfs_notice_count( $notice_type ) > 0 ) {
					cdfs_get_shortcode_templates(
						"notices/{$notice_type}",
						null,
						array(
							'messages' => array_filter( $all_notices[ $notice_type ] ),
						)
					);
				}
			}
			cdfs_clear_notices();
		}
	}
}

if ( ! function_exists( 'cdfs_set_notices' ) ) {
	/**
	 * Set all notices at once.
	 *
	 * @param string $notices .
	 */
	function cdfs_set_notices( $notices ) {
		CDFS()->session->set( 'cdfs_notices', $notices );
	}
}


if ( ! function_exists( 'cdfs_notice_count' ) ) {
	/**
	 * Get the count of notices added, either for all notices (default) or for one.
	 * particular notice type specified by $notice_type.
	 *
	 * @since 2.1
	 * @param string $notice_type The name of the notice type - either error, success or notice. [optional].
	 * @return int
	 */
	function cdfs_notice_count( $notice_type = '' ) {
		if ( ! did_action( 'cdfs_init_action' ) ) {
			return;
		}
		$notice_count = 0;
		$all_notices  = CDFS()->session->get( 'cdfs_notices', array() );

		if ( isset( $all_notices[ $notice_type ] ) ) {
			$notice_count = absint( count( $all_notices[ $notice_type ] ) );
		} elseif ( empty( $notice_type ) ) {
			foreach ( $all_notices as $notices ) {
				$notice_count += absint( count( $all_notices ) );
			}
		}
		return $notice_count;
	}
}


if ( ! function_exists( 'cdfs_setcookie' ) ) {
	/**
	 * Set a cookie - wrapper for setcookie using WP constants.
	 *
	 * @param string $name .
	 * @param string $value .
	 * @param string $expire .
	 * @param string $secure .
	 */
	function cdfs_setcookie( $name, $value, $expire = 0, $secure = false ) {
		if ( ! headers_sent() ) {
			setcookie( $name, $value, $expire, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, $secure );
		} elseif ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			headers_sent( $file, $line );
			trigger_error( "{$name} cookie cannot be set - headers already sent by {$file} on line {$line}", E_USER_NOTICE ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		}
	}
}


if ( ! function_exists( 'is_user_logged_in' ) ) {
	/**
	 * Checks if the current visitor is a logged in user.
	 *
	 * @return bool True if user is logged in, false if not logged in.
	 */
	function is_user_logged_in() {
		$user = wp_get_current_user();
		if ( empty( $user->ID ) ) {
			return false;
		}
		return true;
	}
}

if ( ! function_exists( 'cdfs_clear_notices' ) ) {
	/**
	 * Unset all notices.
	 */
	function cdfs_clear_notices() {
		if ( ! did_action( 'cdfs_init_action' ) ) {
			return;
		}
		CDFS()->session->set( 'cdfs_notices', null );
	}
}

if ( ! function_exists( 'cdfs_get_notices' ) ) {
	/**
	 * Get notices
	 *
	 * @param string $notice_type .
	 */
	function cdfs_get_notices( $notice_type = '' ) {
		if ( ! did_action( 'cdfs_init_action' ) ) {
			return;
		}

		if ( ! CDFS()->session ) {
			return;
		}

		$all_notices = CDFS()->session->get( 'cdfs_notices', array() );
		if ( empty( $notice_type ) ) {
			$notices = $all_notices;
		} elseif ( isset( $all_notices[ $notice_type ] ) ) {
			$notices = $all_notices[ $notice_type ];
		} else {
			$notices = array();
		}
		return $notices;
	}
}

if ( ! function_exists( 'cdfs_get_reference_link' ) ) {
	/**
	 * Get referer link
	 */
	function cdfs_get_reference_link() {
		if ( function_exists( 'wp_get_raw_referer' ) ) {
			return wp_get_raw_referer();
		}

		if ( ! empty( $_REQUEST['_wp_http_referer'] ) ) {
			return wp_unslash( $_REQUEST['_wp_http_referer'] );
		} elseif ( ! empty( $_SERVER['HTTP_REFERER'] ) ) {
			return wp_unslash( $_SERVER['HTTP_REFERER'] );
		}
		return false;
	}
}

if ( ! function_exists( 'cdfs_get_goole_api_keys' ) ) {
	/**
	 * Get google captcha keys
	 *
	 * @param string $key_type .
	 */
	function cdfs_get_goole_api_keys( $key_type = '' ) {
		global $car_dealer_options;
		if ( 'site_key' === $key_type ) {
			$key = ( isset( $car_dealer_options['google_captcha_site_key'] ) && ! empty( $car_dealer_options['google_captcha_site_key'] ) ) ? $car_dealer_options['google_captcha_site_key'] : '';
		}
		if ( 'secret_key' === $key_type ) {
			$key = ( isset( $car_dealer_options['google_captcha_secret_key'] ) && ! empty( $car_dealer_options['google_captcha_secret_key'] ) ) ? $car_dealer_options['google_captcha_secret_key'] : '';
		}
		return $key;
	}
}


if ( ! function_exists( 'cdfs_check_captcha_exists' ) ) {
	/**
	 * Get google captcha keys
	 */
	function cdfs_check_captcha_exists() {
		global $car_dealer_options;
		$key = ( isset( $car_dealer_options['google_captcha_site_key'] ) && ! empty( $car_dealer_options['google_captcha_site_key'] ) ) ? $car_dealer_options['google_captcha_site_key'] : '';
		$key = ( isset( $car_dealer_options['google_captcha_secret_key'] ) && ! empty( $car_dealer_options['google_captcha_secret_key'] ) ) ? $car_dealer_options['google_captcha_secret_key'] : '';
		if ( empty( $key ) ) {
			return false;
		}
		return true;
	}
}

if ( ! function_exists( 'cdfs_validate_google_captcha' ) ) {
	/**
	 * Validate google captcha
	 *
	 * @param string $captcha .
	 */
	function cdfs_validate_google_captcha( $captcha ) {
		$secret_key = cdfs_get_goole_api_keys( 'secret_key' );
		if ( empty( $secret_key ) ) {
			return array( 'success' => true );
		}

		$response = wp_remote_get( 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $captcha . '&remoteip=' . $_SERVER['REMOTE_ADDR'] );
		return json_decode( $response['body'], true );
	}
}

if ( ! function_exists( 'cdfs_captcha_enabled' ) ) {
	/**
	 * Check captcha enabled
	 */
	function cdfs_captcha_enabled() {
		global $car_dealer_options;

		if ( isset( $car_dealer_options['google_captcha_secret_key'] ) && ! empty( $car_dealer_options['google_captcha_secret_key'] ) && isset( $car_dealer_options['google_captcha_site_key'] ) && ! empty( $car_dealer_options['google_captcha_site_key'] ) ) {
			return true;
		}
		return false;
	}
}

if ( ! function_exists( 'cdfs_validate_captcha' ) ) {
	/**
	 * Validate_captcha
	 *
	 * @param string $redirect_url .
	 */
	function cdfs_validate_captcha( $redirect_url = null ) {
		$status      = true;
		$ajax_action = array( 'cdfs_do_login', 'cdfs_do_ajax_user_register', 'cdfs_save_car' );
		if ( cdfs_captcha_enabled() ) { // google captcha.
			$captcha_res             = cdfs_clean( $_POST['g-recaptcha-response'] );
			$is_ajax                 = ( isset( $_POST['action'] ) && in_array( $_POST['action'], $ajax_action ) ) ? 'yes' : 'no';
			$cdfs_recaptcha_response = cdfs_validate_google_captcha( $captcha_res );
			if ( isset( $cdfs_recaptcha_response['success'] ) && 1 !== (int) $cdfs_recaptcha_response['success'] ) {
				if ( 'no' === $is_ajax ) { // if not ajax call.
					cdfs_add_notice( esc_html__( 'Please check the the captcha form', 'cdfs-addon' ), 'error' );
					if ( null !== $redirect_url ) {
						wp_safe_redirect( $redirect_url );
						die;
					}
				}
				$status = false;
			}
		}
		return $status;
	}
}

if ( ! function_exists( 'cdfs_user_activation_method' ) ) {
	/**
	 * Retrieve page ids - used for myuseraccount.returns -1 if no page is found.
	 * since version 1.2.3
	 *
	 * @return int
	 */
	function cdfs_user_activation_method() {
		global $car_dealer_options;
		if ( isset( $car_dealer_options['cdfs_user_activation'] ) && ! empty( $car_dealer_options['cdfs_user_activation'] ) ) {
			return $car_dealer_options['cdfs_user_activation'];
		}
		return 'default';
	}
}

/**
 * Class builder
 *
 * @param string|array $class Single class or list of classes in string or array.
 * @return string
 */
function cdfs_class_builder( $class = '' ) {
	$class_str = '';
	$classes   = array();

	if ( empty( $class ) ) {
		return $class_str;
	}

	// If $class is string, convert it to array.
	if ( is_string( $class ) ) {
		$class = explode( ' ', $class );
	}

	$classes = $class;

	// Sanitize classes.
	$classes = array_map( 'esc_attr', $classes );

	// Convert array to string.
	$class_str = implode( ' ', array_filter( array_unique( $classes ) ) );

	return $class_str;
}
