<?php
/**
 * Handle frontend scripts.
 *
 * @author  PotenzaGlobalSolutions
 * @package CDFS
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CDFS_Frontend_Scripts Class.
 */
class CDFS_Frontend_Scripts {

	/**
	 * Static
	 *
	 * @var $scripts .
	 */
	private static $scripts = array();
	/**
	 * Static
	 *
	 * @var $styles .
	 */
	private static $styles = array();
	/**
	 * Static
	 *
	 * @var $wp_localize_scripts .
	 */
	private static $wp_localize_scripts = array();

	/**
	 * Hook in methods.
	 */
	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'load_scripts' ) );
	}

	/**
	 * Register/queue frontend scripts.
	 */
	public static function load_scripts() {
		global $post,$wp;

		if ( ! did_action( 'cdfs_init_action' ) ) {
			return;
		}

		// Apply only plugin pages.
		if ( cdfs_is_user_account_page() || ( isset( $wp->query_vars['add-car'] ) && 'add-car' === $wp->query_vars['add-car'] ) ) {
			self::add_scripts();
			self::add_styles();
		}
	}

	/**
	 * Add styles for use.
	 */
	private static function add_styles() {
		wp_enqueue_style( 'cdhl-css-helper-admin', trailingslashit( CDFS_URL ) . 'css/cdfs-helper.css', false, true );
		wp_enqueue_style( 'cdhl-css-forms-admin', trailingslashit( CDFS_URL ) . 'css/cdfs-forms.css', false, true );
		wp_register_style( 'cdhl-jquery-confirm', trailingslashit( CDFS_URL ) . 'css/jquery-confirm/jquery-confirm.min.css', false, true );
		// jquery-confirm.
		if ( cdfs_is_user_account_page() ) {
			wp_enqueue_style( 'cdhl-jquery-confirm' );
		}

	}

	/**
	 * Add scripts.
	 */
	private static function add_scripts() {

		global $car_dealer_options;
		$img_up_limit = 20;
		if ( isset( $car_dealer_options['cdfs_cars_img_limit'] ) && ! empty( $car_dealer_options['cdfs_cars_img_limit'] ) ) {
				$img_up_limit = $car_dealer_options['cdfs_cars_img_limit'];
		}
		if ( class_exists( 'Subscriptio' ) || class_exists( 'RP_SUB' ) ) {
			if ( is_user_logged_in() ) {
				$user              = wp_get_current_user();
				$user_id           = $user->ID;
				$img_up_limit_temp = get_user_meta( $user_id, 'cdfs_img_limt', true );
				if ( $img_up_limit_temp ) {
					$img_up_limit = $img_up_limit_temp;
				}
			}
		}

		wp_register_script( 'cdfs-helper-js', trailingslashit( CDFS_URL ) . 'js/cdfs-helper.js', array( 'jquery' ), '1.2.8.1', true );
		wp_register_script( 'cdfs-form_validation', trailingslashit( CDFS_URL ) . 'js/cdfs-form_validation.js', array( 'jquery' ), '1.2.8.1', true );

		wp_register_script( 'cdfs-google-recaptcha-apis', 'https://www.google.com/recaptcha/api.js?&render=explicit', array(), '1.2.8.1', true );
		wp_register_script( 'cdfs-google-location-picker', trailingslashit( CDFS_URL ) . 'js/google-map/locationpicker/locationpicker.jquery.js', array( 'jquery' ), '0.1.16', true );
		wp_register_script( 'cdfs-google-location-picker-api', '//maps.google.com/maps/api/js?sensor=false&libraries=places&key=' . $car_dealer_options['google_maps_api'], array(), '1.2.8.1', true );

		wp_register_script( 'cdfs-jquery-confirm', trailingslashit( CDFS_URL ) . 'js/jquery-confirm/jquery-confirm.min.js', array( 'jquery' ), '3.3.0', true );

		wp_localize_script(
			'cdfs-helper-js',
			'cdfs_obj',
			array(
				'alerttxt'    => esc_html__( 'Alert', 'cdfs-addon' ),
				'errortxt'    => esc_html__( 'Error!', 'cdfs-addon' ),
				'delalerttex' => esc_html__( 'Are you sure want to delete?', 'cdfs-addon' ),
				/* translators: %s: img limit */
				'imglimittxt' => sprintf( esc_html__( 'Sorry! You can upload at most %s images.', 'cdfs-addon' ), esc_html( $img_up_limit ) ),
				'imgtypetxt'  => esc_html__( 'The file(s) [file] is not an image.', 'cdfs-addon' ),
				'pdftypetxt'  => esc_html__( 'The file [file] is not a PDF file, Please upload PDF file only.', 'cdfs-addon' ),
				'exceededtxt' => esc_html__( 'File size exceeded than 4 MB.', 'cdfs-addon' ),
			)
		);

		wp_enqueue_script( 'cdfs-helper-js' );
		wp_enqueue_script( 'cdfs-form_validation' );

		wp_localize_script(
			'cdfs-helper-js',
			'front_js',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			)
		);

		// jquery-confirm.
		wp_enqueue_script( 'cdfs-jquery-confirm' );

		// Captcha js script.
		$captcha_sitekey    = cdfs_get_goole_api_keys( 'site_key' );
		$captcha_secret_key = cdfs_get_goole_api_keys( 'secret_key' );
		if ( isset( $captcha_secret_key ) && ! empty( $captcha_secret_key ) && isset( $captcha_sitekey ) && ! empty( $captcha_sitekey ) ) {
			wp_enqueue_script( 'cdfs-google-recaptcha-apis' );
		}
	}

}

CDFS_Frontend_Scripts::init();
