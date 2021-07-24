<?php
/**
 *
 * Functions for the template functions for car pages.
 *
 * @author   PotenzaGlobalSolutions
 * @category Core
 * @package  CDFS
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


if ( ! function_exists( 'cdfs_action_cars_listing' ) ) {

	/**
	 * User Account > Dashboard
	 */
	function cdfs_action_cars_listing() {
		CDFS_Shortcode_My_Account::process_cars_listing();
	}
}

/*
 * Car add form
 */
if ( ! function_exists( 'cdfs_add_new_car' ) ) {

	/**
	 * User Account > Dashboard > Add new car.
	 */
	function cdfs_add_new_car() {
		wp_enqueue_script( 'cdfs-google-location-picker-api' );
		wp_enqueue_script( 'cdfs-google-location-picker' );
		cdfs_get_template( 'cars/cars-add.php' );
	}
}

/*
 * Car add login form in add car page
 */
if ( ! function_exists( 'cdfs_add_login_form' ) ) {

	/**
	 * User Account > Dashboard > Add new car.
	 */
	function cdfs_add_login_form() {
		if ( ! is_user_logged_in() ) {
			cdfs_get_template( 'my-user-account/form-login.php' );
		}
	}
}


