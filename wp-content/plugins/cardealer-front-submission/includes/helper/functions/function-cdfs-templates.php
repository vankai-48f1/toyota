<?php
/**
 *
 * Functions for the template hooks.
 *
 * @author   PotenzaGlobalSolutions
 * @package  CDFS
 * @category Core
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


if ( ! function_exists( 'cdfs_content' ) ) {

	/**
	 * User Account content output.
	 */
	function cdfs_content() {
		global $wp;
		if ( ! empty( $wp->query_vars ) ) {
			foreach ( $wp->query_vars as $key => $value ) {
				// Ignore pagename param.
				if ( 'pagename' === $key ) {
					continue;
				}

				if ( has_action( 'cdfs_' . $key . '_endpoint' ) ) {
					do_action( 'cdfs_' . $key . '_endpoint', $value );
					return;
				}
			}
		}

		// No endpoint found? Default to dashboard.
		cdfs_get_template(
			'my-user-account/dashboard.php',
			array(
				'current_user' => get_user_by( 'id', get_current_user_id() ),
			)
		);
	}
}

if ( ! function_exists( 'cdfs_user_navigation' ) ) {
	/**
	 * My Account navigation template.
	 */
	function cdfs_user_navigation() {
		cdfs_get_template( 'my-user-account/navigation.php' );
	}
}

if ( ! function_exists( 'cdfs_user_info' ) ) {
	/**
	 * My Account - User Details.
	 */
	function cdfs_user_info() {
		cdfs_get_template( 'my-user-account/user-details.php' );
	}
}


if ( ! function_exists( 'cdfs_user_edit_account' ) ) {

	/**
	 * My Account > Edit account template.
	 */
	function cdfs_user_edit_account() {
		CDFS_Shortcode_My_Account::user_account_edit();
	}
}

if ( ! function_exists( 'cdfs_action_cars_listing' ) ) {

	/**
	 * My Account > Edit account template.
	 */
	function cdfs_action_cars_listing() {
		CDFS_Shortcode_My_Account::process_cars_listing();
	}
}

