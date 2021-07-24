<?php
/**
 * CarDealer logger class.
 *
 * @package car-dealer-helper/functions
 * @version 1.0.0
 */

/**
 * CarDealer logger class.
 */
trait CDHL_Logger {

	/**
	 * Log.
	 *
	 * @param string $message .
	 * @param string $version .
	 * @param string $type .
	 */
	public static function log( $message, $version = '', $type = '' ) {
		$file    = CDHL_VER_LOG . 'cardealer_helper_' . date_i18n( 'm-d-Y' ) . '.txt';
		$open    = fopen( $file, 'a' );
		$log_txt = date_i18n( 'm-d-Y @ H:i:s' ) . '  ' . $type . ' ' . $message . "\n";
		$write   = fputs( $open, $log_txt );
		fclose( $open );
	}
}
