<?php
/**
 * Installation related functions and actions.
 *
 * @author   PotenzaGlobalSolutions
 * @category Admin
 * @package  Car Dealer Helper Library
 * @version  1.0.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CDHL_Version Class.
 */
require_once dirname( __FILE__ ) . '/helper-classes/class-cdhl-logger.php'; // LOGGER TRAIT.

/**
 * CarDealer Helper Version Class.
 */
class CDHL_Version {

	/**
	 * DB version.
	 *
	 * @var string DB version
	 */
	public static $db_version = CDHL_VERSION;

	/**
	 * Notice array
	 *
	 * @var array Notice array
	 */
	public static $notices = array();

	// USING LOGGER TRAIT FOR LOGGIN PROCESS.
	use CDHL_Logger;

	/**
	 * DB updates and callbacks that need to be run per version.
	 *
	 * @var array DB updates and callbacks that need to be run per version
	 */
	private static $db_updates = array(
		'1.0.3' => array(
			'cdhl_update_103_version',
			'cdhl_update_103_db_version',
		),
		'1.0.5' => array(
			'cdhl_update_105_version',
			'cdhl_update_105_db_version',
		),
	);

	/**
	 * Background update class.
	 *
	 *  @var object Background update class.
	 */
	private static $background_updater;

	/**
	 * Class init.
	 *
	 * @return void
	 */
	public static function cdhl_init() {
		add_action( 'init', array( __CLASS__, 'cdhl_check_update' ), 5 );
		add_action( 'init', array( __CLASS__, 'cdhl_init_background_updater' ), 5 );
		add_action( 'init', array( __CLASS__, 'cdhl_install_actions' ), 5 );
		add_action( 'init', array( __CLASS__, 'cdhl_status' ), 5 );
		add_action( 'init', array( __CLASS__, 'cdhl_notices' ), 5 );
		add_action( 'init', array( __CLASS__, 'cdhl_hide_notices' ), 5 );
	}


	/**
	 * Init background updates
	 */
	public static function cdhl_init_background_updater() {
		include_once dirname( __FILE__ ) . '/helper-classes/class-cdhl-background-updater.php';
		self::$background_updater = new CDHL_Background_Updater();
	}

	/**
	 * Check update.
	 *
	 * @return void
	 */
	public static function cdhl_check_update() {
		$current_cdhl_version = get_option( 'cdhl_version', null );
		if ( is_null( $current_cdhl_version ) || version_compare( $current_cdhl_version, max( array_keys( self::$db_updates ) ), '<' ) ) {
			self::cdhl_create_log_files(); // create log files.
			array_push( self::$notices, 'cdhl_update_notice' );
		}
	}

	/**
	 * Create log files/directories.
	 */
	public static function cdhl_create_log_files() {
		// Install files and folders for uploading files and prevent hotlinking.
		$upload_dir = wp_upload_dir();
		$files      = array(
			array(
				'base'    => $upload_dir['basedir'] . '/cardealer-helper/update-logs',
				'file'    => 'index.html',
				'content' => '',
			),
			array(
				'base'    => $upload_dir['basedir'] . '/cardealer-helper/update-logs',
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
	 * Update Notice.
	 *
	 * @return void
	 */
	public static function cdhl_update_notice() {
		?>
		<div id="message" class="updated cdhl-message cdhl-connect">
			<p><strong><?php esc_html_e( 'Car Dealer Helper Library data update', 'cardealer-helper' ); ?></strong> &#8211; <?php esc_html_e( 'We need to update your store database to the latest version.', 'cardealer-helper' ); ?></p>
			<p class="submit"><a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'do_update_cdhl', 'true', get_permalink() ), 'cdhl_update_nonce', '_cdhl_update_nonce' ) ); ?>" class="cdhl-update-now button-primary"><?php esc_html_e( 'Run the updater', 'cardealer-helper' ); ?></a></p>
		</div>
		<script type="text/javascript">
			jQuery( '.cdhl-update-now' ).click( 'click', function() {
				return confirm("<?php echo esc_js( esc_html__( 'It is strongly recommended that you backup your database before proceeding. Are you sure you wish to run the updater now?', 'cardealer-helper' ) ); ?>");
			});
		</script>
		<?php
	}

	/**
	 * Install actions when a update button is clicked within the admin area.
	 *
	 * This function is hooked into admin_init to affect admin only.
	 */
	public static function cdhl_install_actions() {
		if ( isset( $_GET['do_update_cdhl'] ) && ! empty( $_GET['do_update_cdhl'] ) && isset( $_GET['_cdhl_update_nonce'] ) ) {
			if ( ! wp_verify_nonce( $_GET['_cdhl_update_nonce'], 'cdhl_update_nonce' ) ) {
				wp_die( esc_html__( 'Action failed. Please refresh the page and retry.', 'cardealer-helper' ) );
			}
			self::cdhl_update();
		}
	}

	/**
	 * Add notice based on update status
	 */
	public static function cdhl_status() {
		$current_cdhl_version = get_option( 'cdhl_version', null );
		if ( is_null( $current_cdhl_version ) ) {
			return;
		}

		if ( version_compare( $current_cdhl_version, max( array_keys( self::$db_updates ) ), '<' ) ) {
			$updater = new CDHL_Background_Updater();
			if ( $updater->is_updating() ) {
				array_push( self::$notices, 'cdhl_updating_notice' );
			}
		} else {
			$get_version_status = get_option( 'cdhl_version_status' );
			if ( ! empty( $get_version_status ) && 'updated' === (string) $get_version_status ) {
				array_push( self::$notices, 'cdhl_updated_notice' );
			}
		}
	}

	/**
	 * Display all alerts and notices
	 */
	public static function cdhl_notices() {
		foreach ( self::$notices as $notice ) {
			add_action( 'admin_notices', array( __CLASS__, $notice ), 9 );
		}
	}

	/**
	 * Hide a notice if the GET variable is set.
	 */
	public static function cdhl_hide_notices() {
		if ( isset( $_GET['cdhl-hide-notice'] ) && isset( $_GET['_cdhl_notice_nonce'] ) ) {
			if ( ! wp_verify_nonce( $_GET['_cdhl_notice_nonce'], 'cdhl_hide_notices_nonce' ) ) {
				wp_die( esc_html__( 'Action failed. Please refresh the page and retry.', 'cardealer-helper' ) );
			}

			$hide_notice = sanitize_text_field( $_GET['cdhl-hide-notice'] );
			self::cdhl_remove_notice( $hide_notice );
		}
	}

	/**
	 * Remove notices.
	 *
	 * @param string $name .
	 * @return void
	 */
	public static function cdhl_remove_notice( $name ) {
		self::$notices = array_diff( self::$notices, array( $name ) );
		if ( 'cdhl_updated_notice' === (string) $name ) {
			update_option( 'cdhl_version_status', 'up-to-date' );
		}
		self::cdhl_redirect();
	}

	/**
	 * Redirect method.
	 *
	 * @return void
	 */
	public static function cdhl_redirect() {
		if ( wp_get_referer() ) {
			wp_safe_redirect( wp_get_referer() );
		} else {
			wp_safe_redirect( admin_url( 'admin.php?page=cardealer' ) );
		}
		exit;
	}

	/**
	 * Notice html when updating process is going on.
	 *
	 * @return void
	 */
	public static function cdhl_updating_notice() {
		?>
		<div class="notice notice-info is-dismissible">
			<p><strong><?php esc_html_e( 'Car Dealer Helper Library data update', 'cardealer-helper' ); ?></strong> &#8211; <?php esc_html_e( 'Your database is being updated in the background.', 'cardealer-helper' ); ?> </p>
			<button type="button" class="notice-dismiss"></button>
		</div>
		<?php
	}

	/**
	 * Notice html when updating process is completed.
	 *
	 * @return void
	 */
	public static function cdhl_updated_notice() {
		?>
		<div class="notice notice-success">
			<p><?php esc_html_e( 'Car Dealer Helper Library data update complete. Thank you for updating to the latest version!', 'cardealer-helper' ); ?></p>
			<a class="cdhl-message-close notice-dismiss" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'cdhl-hide-notice', 'cdhl_updated_notice', remove_query_arg( 'do_update_cdhl' ) ), 'cdhl_hide_notices_nonce', '_cdhl_notice_nonce' ) ); ?>"><?php esc_html_e( 'Dismiss', 'cardealer-helper' ); ?></a>
		</div>
		<?php
	}

	/**
	 * Get list of DB update callbacks.
	 *
	 * @return array
	 */
	public static function cdhl_get_db_update_callbacks() {
		return self::$db_updates;
	}

	/**
	 * Push all needed DB updates to the queue for processing.
	 */
	private static function cdhl_update() {
		$current_db_version = get_option( 'cdhl_version' );
		$update_queued      = false;
		foreach ( self::cdhl_get_db_update_callbacks() as $version => $update_callbacks ) {
			if ( version_compare( $current_db_version, $version, '<' ) ) {
				foreach ( $update_callbacks as $update_callback ) {
					CDHL_Version::log(
						/* translators: %1$s: version %2$s: update callback */
						sprintf( esc_html__( 'Queuing %1$s - %2$s', 'cardealer-helper' ), $version, $update_callback ),
						$version,
						'INFO'
					);
					self::$background_updater->push_to_queue( $update_callback );
					$update_queued = true;
				}
			}
		}
		if ( $update_queued ) {
			self::$background_updater->save()->dispatch();
		}
		self::cdhl_redirect();
	}

	/**
	 * UPDATE PLUGIN VERSION.
	 */
	public static function cdhl_update_db_version( $db_version = null ) {
		delete_option( 'cdhl_version' );
		add_option( 'cdhl_version', is_null( $db_version ) ? CDHL_VERSION : $db_version );
	}
}

CDHL_Version::cdhl_init();
