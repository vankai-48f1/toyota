<?php
/**
 * User home page after login
 *
 * This template can be overridden by copying it to yourtheme/cardealer-front-submission/my-user-account/content.php
 *
 * @author  PotenzaGlobalSolutions
 * @package CDFS
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Notices.
cdfs_print_notices();

/**
 * User Account navigation.
 */
do_action( 'cdfs_navigation' ); ?>

<div class="cdfs-MyAccount-content">
	<?php
		/**
		 * User Account content.
		 */
		do_action( 'cdfs_content' );
	?>
</div>
