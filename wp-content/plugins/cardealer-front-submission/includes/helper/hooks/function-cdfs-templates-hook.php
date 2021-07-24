<?php
/**
 * Car Dealer Front End Submission Template Hooks
 *
 * Action/filter hooks used for CDFS functions/templates.
 *
 * @author      PotenzaGlobalSolutions
 * @package     CDFS
 * @category    Core
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * User Account.
 */
add_action( 'cdfs_navigation', 'cdfs_user_navigation' );
add_action( 'cdfs_cars_navigation', 'cdfs_cars_navigation' );
add_action( 'cdfs_action_before_cars_listing', 'cdfs_user_info' );
add_action( 'cdfs_action_cars_listing', 'cdfs_action_cars_listing' );
add_action( 'cdfs_content', 'cdfs_content' );
add_action( 'cdfs_user-edit-account_endpoint', 'cdfs_user_edit_account' );
add_action( 'cdfs_add-car_endpoint', 'cdfs_add_new_car' );
add_action( 'cdfs_after_add_car_form', 'cdfs_add_login_form' );

