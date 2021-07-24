<?php
/**
 * Car Dealer Helper Library Updates
 *
 * Functions for updating data, used by the background updater.
 *
 * @package car-dealer-helper/functions
 * @author  PotenzaGlobalSolutions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Execute new updates.
 */
function cdhl_update_103_version() {
	if ( function_exists( 'update_field' ) ) {
		$args = array(
			'post_type'      => 'cars',
			'posts_per_page' => '-1',
		);

		$log_query = new WP_Query( $args );
		$result    = array();
		$cnt       = 0;
		while ( $log_query->have_posts() ) :
			$log_query->the_post();
			$final_price = 0;
			$sale_price  = get_post_meta( get_the_ID(), 'sale_price', true );
			if ( $sale_price ) {
				$final_price = $sale_price;
			} else {
				$regular_price = get_post_meta( get_the_ID(), 'regular_price', true );
				if ( $regular_price ) {
					$final_price = $regular_price;
				}
			}
			update_field( 'final_price', $final_price, get_the_ID() );
		endwhile;
	}
}

/**
 * Update DB Version.
 */
function cdhl_update_103_db_version() {
	CDHL_Version::cdhl_update_db_version( '1.0.3' );
}


/**
 * Version 1.0.5 code.
 *
 * @return void
 */
function cdhl_update_105_version() {
	// Create log files for import process.
	$upload_dir = wp_upload_dir();
	$files      = array(
		array(
			'base'    => $upload_dir['basedir'] . '/cardealer-helper/back-process-logs',
			'file'    => 'index.html',
			'content' => '',
		),
		array(
			'base'    => $upload_dir['basedir'] . '/cardealer-helper/back-process-logs',
			'file'    => '.htaccess',
			'content' => 'deny from all',
		),
		array(
			'base'    => $upload_dir['basedir'] . '/cardealer-helper/back-process-logs/import_logs',
			'file'    => 'index.html',
			'content' => '',
		),
		array(
			'base'    => $upload_dir['basedir'] . '/cardealer-helper/back-process-logs/import_logs',
			'file'    => '.htaccess',
			'content' => 'deny from all',
		),
	);
	foreach ( $files as $file ) {
		if ( wp_mkdir_p( $file['base'] ) && ! file_exists( trailingslashit( $file['base'] ) . $file['file'] ) ) {
			if ( $file_handle = @fopen( trailingslashit( $file['base'] ) . $file['file'], 'w' ) ) {
				fwrite( $file_handle, $file['content'] );
				fclose( $file_handle );
			}
		}
	}
}

/**
 * Update DB Version.
 *
 * @return void
 */
function cdhl_update_105_db_version() {
	CDHL_Version::cdhl_update_db_version( '1.0.5' );
}
