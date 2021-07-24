<?php
/**
 * CarDealer functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package CarDealer
 */

cardealer_globals_n_constants();

/**
 * Globals and constants.
 */
function cardealer_globals_n_constants() {

	// Globals.
	global $cardealer_globals, $cardealer_theme_data, $cardealer_links;

	$cardealer_theme_data = wp_get_theme( get_template() );

	$cardealer_globals = array(
		'theme_title' => $cardealer_theme_data->get( 'Name' ),
		'theme_slug'  => sanitize_title( $cardealer_theme_data->get( 'Name' ) ),
		'theme_name'  => str_replace( '-', '_', sanitize_title( $cardealer_theme_data->get( 'Name' ) ) ),
		'version'     => $cardealer_theme_data['Version'],
	);

	define( 'CARDEALER_VERSION', $cardealer_theme_data['Version'] );

	// Backwards compatibility for __DIR__.
	if ( ! defined( '__DIR__' ) ) { // phpcs:ignore PHPCompatibility.Keywords.ForbiddenNames.__dir__Found
		define( '__DIR__', dirname( __FILE__ ) ); // phpcs:ignore PHPCompatibility.Keywords.ForbiddenNames.__dir__Found
	}

	define( 'PGS_PRODUCT_KEY', 'aa6d8e890d5171e3960185f89ddcb720' );

	if ( ! defined( 'PGS_ENVATO_API' ) ) {
		define( 'PGS_ENVATO_API', 'https://envatoapi.potenzaglobalsolutions.com/' );
	}

	$GLOBALS['cardealer_links'] = array(
		'pgsdotcom' => array(
			'title' => esc_html__( 'Potenza', 'cardealer' ),
			'link'  => 'https://www.potenzaglobalsolutions.com/',
		),
		'pgsforum_www' => array(
			'title' => esc_html__( 'PGS Forum', 'cardealer' ),
			'link'  => 'https://www.potenzaglobalsolutions.com/',
		),
		'ticksy' => array(
			'title' => esc_html__( 'Support', 'cardealer' ),
			'link'  => 'https://potezasupport.ticksy.com/',
		),
		'ticksy_submit_ticket' => array(
			'title' => esc_html__( 'Submit Ticket', 'cardealer' ),
			'link'  => 'https://potezasupport.ticksy.com/submit/#100010500',
		),
		'themeforest' => array(
			'title' => esc_html__( 'Themeforest', 'cardealer' ),
			'link'  => 'https://themeforest.net/user/potenzaglobalsolutions/',
		),
		'pgsdocs' => array(
			'title' => esc_html__( 'Documentation', 'cardealer' ),
			'link'  => 'https://docs.potenzaglobalsolutions.com/docs/cardealer/',
		),
		'pgsdocs_google_api_settings' => array(
			'title' => esc_html__( 'Google API Settings', 'cardealer' ),
			'link'  => 'https://docs.potenzaglobalsolutions.com/docs/cardealer/#google-api-settings',
		),
		'pgsdocs_attributes_guide' => array(
			'title' => esc_html__( 'Attributes Guide', 'cardealer' ),
			'link'  => 'https://docs.potenzaglobalsolutions.com/docs/cardealer/#attributes-guide',
		),
	);

	if ( defined( 'CDHL_VERSION' ) && version_compare( CDHL_VERSION, '1.8.1', '<=' ) ) {
		$GLOBALS['cardealer_links'] = array(
			'pgsdocs_google_api_settings' => 'https://docs.potenzaglobalsolutions.com/docs/cardealer/#google-api-settings',
		);
	}

	$GLOBALS['cardealer_tgmpa_menu'] = 'theme-plugins';
}

/*
 * CONSTANTS & VARIABLES
 */
// Base Paths.
if ( ! defined( 'CARDEALER_PATH' ) ) {
	if ( version_compare( get_bloginfo( 'version' ), '4.7', '>=' ) ) {
		define( 'CARDEALER_PATH', get_parent_theme_file_path() );
		define( 'CARDEALER_URL', get_parent_theme_file_uri() );
	} else {
		define( 'CARDEALER_PATH', get_template_directory() );
		define( 'CARDEALER_URL', get_template_directory_uri() );
	}
}

// Includes Paths.
if ( ! defined( 'CARDEALER_INC_PATH' ) ) {
	define( 'CARDEALER_INC_PATH', CARDEALER_PATH . '/includes' );
}

if ( ! defined( 'CARDEALER_THEME_OPTIONS_NAME' ) ) {
	define( 'CARDEALER_THEME_OPTIONS_NAME', 'car_dealer_options' );
}

$lazyload_image = apply_filters( 'cardealer_lazyload_image', plugin_dir_url( '' ) . '/cardealer-helper-library/images/loader.gif' );
if ( ! defined( 'LAZYLOAD_IMG' ) ) {
	define( 'LAZYLOAD_IMG', $lazyload_image );
}

/**
 * Includes
 */
require_once CARDEALER_PATH . '/includes/init.php';                          // Initialization.
require_once CARDEALER_PATH . '/includes/scripts_and_styles.php';            // Scripts & Styles.
require_once CARDEALER_PATH . '/includes/theme_support.php';                 // Theme Support.
require_once CARDEALER_PATH . '/includes/attributes.php';                    // Attribues.
require_once CARDEALER_PATH . '/includes/template_tags.php';                 // Template Tags.
require_once CARDEALER_PATH . '/includes/sidebars.php';                      // Sidebars.
require_once CARDEALER_PATH . '/includes/comments.php';                      // Comments.
require_once CARDEALER_PATH . '/includes/menus/menus.php';                   // Menus.
require_once CARDEALER_PATH . '/includes/base_functions.php';                // Basic/Required Functions.
require_once CARDEALER_PATH . '/includes/cars_functions.php';                // Basic/Required Functions.
require_once CARDEALER_PATH . '/includes/sold_cars_functions.php';           // Basic/Required Functions.

require_once CARDEALER_PATH . '/includes/maintenance.php';                   // Maintenance Mode.
require_once CARDEALER_PATH . '/includes/dynamic_css.php';                   // Dynamic CSS.
require_once CARDEALER_PATH . '/includes/acf_ported_functions.php';          // ACF Ported Functions.
require_once CARDEALER_PATH . '/includes/login.php';                         // Login Page Settings.
require_once CARDEALER_PATH . '/includes/external-lib-fix.php';              // External Library Fixes.

if ( is_admin() ) {
	require_once CARDEALER_PATH . '/includes/tgm-plugin-activation/tgm-init.php'; // TGM Plugin Activation.
	require_once CARDEALER_PATH . '/includes/admin/admin-init.php';               // Theme Panel.
	require_once CARDEALER_PATH . '/includes/sample-data.php';                    // Sample data.
	require_once CARDEALER_PATH . '/includes/theme-setup-wizard/wizard.php';      // Setup Wizard.
}

if ( class_exists( 'WooCommerce' ) ) {
	require_once CARDEALER_PATH . '/includes/woo_functions.php';                 // Woocommerce/Customs Functions.
	require_once CARDEALER_PATH . '/includes/woocommerce-hook.php';              // Woocommerce custome hooks.
}

/*
 * Redux issue fix
 */
if ( get_option( 'car-dealer-options' ) && ! get_option( 'cd_options_updated' ) ) {
	if ( class_exists( 'Redux' ) ){
		if ( cardealer_check_plugin_active( 'cardealer-helper-library/cardealer-helper-library.php' ) ) {
			if ( version_compare( CDHL_VERSION, '1.3.5', '>=' ) ) {
				$old_options = get_option( 'car-dealer-options' );
				update_option( 'car_dealer_options', $old_options );
				update_option( 'cd_options_updated', true );
			}
		}
	}
}
