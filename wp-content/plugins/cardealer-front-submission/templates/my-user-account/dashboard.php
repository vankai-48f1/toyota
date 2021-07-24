<?php
/**
 * User Account Dashboard
 *
 * Shows the Cars Listing of user uploaded cars.
 *
 * This template can be overridden by copying it to yourtheme/cardealer-front-submission/my-user-account/dashboard.php.
 *
 * @author  PotenzaGlobalSolutions
 * @package CDFS
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * User Account dashboard.
 */
echo '<div class="row">';
do_action( 'cdfs_action_before_cars_listing' );

do_action( 'cdfs_action_cars_listing' );

do_action( 'cdfs_action_after_cars_listing' );
echo '</div>';

