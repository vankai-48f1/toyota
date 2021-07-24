<?php
/**
 * Cardealer Visual Composer inti
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package car-dealer-helper/functions
 */

if ( ! defined( 'CDHL_VC_DIR' ) ) {
	define( 'CDHL_VC_DIR', trailingslashit( CDHL_PATH ) . 'includes/vc' );
}
if ( ! defined( 'CDHL_VC_URL' ) ) {
	define( 'CDHL_VC_URL', trailingslashit( CDHL_URL ) . 'includes/vc' );
}

if ( function_exists( 'vc_set_shortcodes_templates_dir' ) ) {
	$templates_path = CDHL_VC_DIR . '/vc_templates';
	vc_set_shortcodes_templates_dir( $templates_path );
}

cdhl_vc_helpers_loader();

/**
 * VC helper loader.
 *
 * @return void
 */
function cdhl_vc_helpers_loader() {
	$dir = CDHL_VC_DIR . '/vc_helpers/';
	if ( is_dir( $dir ) ) {
		$helpers = cdhl_pgscore_get_file_list( 'php', $dir );
		if ( ! empty( $helpers ) ) {
			foreach ( $helpers as $helper ) {
				include( $helper );
			}
		}
	}
}

add_action( 'init', 'cdhl_vc_param_loader', 20 );

/**
 * VC param loader.
 *
 * @return void
 */
function cdhl_vc_param_loader() {
	$dir = CDHL_VC_DIR . '/vc_params/';
	if ( is_dir( $dir ) ) {
		$params = cdhl_pgscore_get_file_list( 'php', $dir );
		if ( ! empty( $params ) ) {
			foreach ( $params as $param ) {
				include( $param );
			}
		}
	}
}

cdhl_vc_fieldsets_loader();

/**
 * VC fields loader.
 *
 * @return void
 */
function cdhl_vc_fieldsets_loader() {
	$dir = CDHL_VC_DIR . '/vc_fieldsets/';
	if ( is_dir( $dir ) ) {
		$fieldsets = cdhl_pgscore_get_file_list( 'php', $dir );
		if ( ! empty( $fieldsets ) ) {
			foreach ( $fieldsets as $fieldset ) {
				include( $fieldset );
			}
		}
	}
}

add_action( 'admin_enqueue_scripts', 'cdhl_admin_enqueue_scripts_vc' );
/**
 * Enqueue admin scripts.
 *
 * @param string $hook .
 *
 * @return void
 */
function cdhl_admin_enqueue_scripts_vc( $hook ) {
	if ( 'post.php' === (string) $hook || 'post-new.php' === (string) $hook || 'edit.php' === (string) $hook ) {
		wp_enqueue_style( 'cardealer-vc-admin-style', CDHL_VC_URL . '/assets/css/vc-admin.css' );

		wp_enqueue_script( 'cardealer-vc-imagepicker', CDHL_VC_URL . '/assets/js/image-picker.jquery.min.js', array( 'jquery' ), false, true );
	}
}
