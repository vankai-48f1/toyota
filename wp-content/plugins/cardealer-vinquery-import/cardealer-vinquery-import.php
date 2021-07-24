<?php
/**
 * Plugin Name: Car Dealer - VINquery Import
 * Plugin URI:  http://www.potenzaglobalsolutions.com/
 * Description: This plugin provide vehicle import  functionality using VINquery API.
 * Version:     1.4.0
 * Author:      Potenza Global Solutions
 * Author URI:  https://themeforest.net/item/car-dealer-automotive-responsive-wordpress-theme/20213334
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: cdvqi-addon
 *
 * @package Vinquery Import
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Define PLUGIN_FILE.
if ( ! defined( 'CDVQI_PLUGIN_FILE' ) ) {
	define( 'CDVQI_PLUGIN_FILE', __FILE__ );
}

define( 'CDVQI_PATH', plugin_dir_path( __FILE__ ) );
define( 'CDVQI_URL', plugin_dir_url( __FILE__ ) );

if ( is_admin() ) {
	require plugin_dir_path( __FILE__ ) . 'init.php';
	require plugin_dir_path( __FILE__ ) . 'admin-ajax.php';

	include_once dirname( __FILE__ ) . '/classes/class-cdvqi.php';
	include_once dirname( __FILE__ ) . '/classes/class-cardealer-vinquery-import.php';
}
