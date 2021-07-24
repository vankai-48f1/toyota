<?php
/**
 * Background Updater
 *
 * Uses https://github.com/A5hleyRich/wp-background-processing to handle DB
 * updates in the background.
 *
 * @class    CDHL_Background_Updater
 * @version  1.0.3
 * @package  CDHL/Classes
 * @category Class
 * @author   PotenzaGlobalSolutions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_CDHL_Async_Request', false ) ) {
	include_once trailingslashit( CDHL_PATH ) . 'includes/version_update/libraries/wp-cdhl-async-request.php';
}

if ( ! class_exists( 'WP_CDHL_Background_Process', false ) ) {
	include_once trailingslashit( CDHL_PATH ) . 'includes/version_update/libraries/wp-cdhl-background-process.php';
}

require_once 'class-cdhl-logger.php'; // LOGGER TRAIT.

/**
 * WC_Background_Updater Class.
 */
class CDHL_Background_Updater extends WP_CDHL_Background_Process {

	/**
	 * Updater.
	 *
	 * @var string
	 */
	protected $action = 'cdhl_updater';

	// USING LOGGER TRAIT FOR LOGGIN PROCESS.
	use CDHL_Logger;
	/**
	 * Dispatch updater.
	 *
	 * Updater will still run via cron job if this fails for any reason.
	 */
	public function dispatch() {
		$dispatched = parent::dispatch();
		if ( is_wp_error( $dispatched ) ) {
			CDHL_Version::log(
				/* translators: $s: Unable to dispatch Car Dealer Helper Library updater */
				sprintf( esc_html__( 'Unable to dispatch Car Dealer Helper Library updater: %s', 'cardealer-helper' ), $dispatched->get_error_message() ),
				CDHL_VERSION,
				'ERROR'
			);
		}
	}

	/**
	 * Handle cron healthcheck
	 *
	 * Restart the background process if not already running
	 * and data exists in the queue.
	 */
	public function handle_cron_healthcheck() {
		if ( $this->is_process_running() ) {
			// Background process already running.
			return;
		}

		if ( $this->is_queue_empty() ) {
			// No data to process.
			$this->clear_scheduled_event();
			return;
		}

		$this->handle();
	}

	/**
	 * Schedule fallback event.
	 */
	protected function schedule_event() {
		if ( ! wp_next_scheduled( $this->cron_hook_identifier ) ) {
			wp_schedule_event( time() + 10, $this->cron_interval_identifier, $this->cron_hook_identifier );
		}
	}

	/**
	 * Is the updater running?
	 *
	 * @return boolean
	 */
	public function is_updating() {
		return false === $this->is_queue_empty();
	}

	/**
	 * Task
	 *
	 * Override this method to perform any actions required on each
	 * queue item. Return the modified item for further processing
	 * in the next pass through. Or, return false to remove the
	 * item from the queue.
	 *
	 * @param string $callback .
	 *
	 * @return mixed
	 */
	protected function task( $callback ) {
		if ( ! defined( 'CDHL_UPDATING' ) ) {
			define( 'CDHL_UPDATING', true );
		}
		include_once dirname( __FILE__ ) . '/cdhl-update-functions.php';

		if ( is_callable( $callback ) ) {
			CDHL_Version::log(
				/* translators: $s: Running */
				sprintf( esc_html__( 'Running %s callback', 'cardealer-helper' ), $callback ),
				CDHL_VERSION,
				'INFO'
			);
			call_user_func( $callback );
			CDHL_Version::log(
				/* translators: $s: Finished */
				sprintf( esc_html__( 'Finished %s callback', 'cardealer-helper' ), $callback ),
				CDHL_VERSION,
				'INFO'
			);
		} else {
			CDHL_Version::log(
				/* translators: $s: Could not find */
				sprintf( esc_html__( 'Could not find %s callback', 'cardealer-helper' ), $callback ),
				CDHL_VERSION,
				'NOTICE'
			);
		}
		return false;
	}

	/**
	 * Complete
	 *
	 * Override if applicable, but ensure that the below actions are
	 * performed, or, call parent::complete().
	 */
	protected function complete() {
		CDHL_Version::log(
			esc_html__( 'Data update complete', 'cardealer-helper' ),
			CDHL_VERSION,
			'INFO'
		);
		CDHL_Version::cdhl_update_db_version();
		update_option( 'cdhl_version_status', 'updated' );
		parent::complete();
	}
}
