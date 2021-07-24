<?php
/**
 * Plugin Name: Car Dealer - VIN Import
 * Plugin URI:  http://www.potenzaglobalsolutions.com/
 * Description: This plugin provide vehicle import  functionality using edmunds API.
 * Version:     1.0.3
 * Author:      Potenza Global Solutions
 * Author URI:  https://themeforest.net/item/car-dealer-automotive-responsive-wordpress-theme/20213334
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: cdvi-addon
 *
 * @package Cardealer Vin Import
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Define PLUGIN_FILE.
if ( ! defined( 'CDVI_PLUGIN_FILE' ) ) {
	define( 'CDVI_PLUGIN_FILE', __FILE__ );
}

define( 'CDVI_PATH', plugin_dir_path( __FILE__ ) );
define( 'CDVI_URL', plugin_dir_url( __FILE__ ) );

if ( is_admin() ) {
	require plugin_dir_path( __FILE__ ) . 'init.php';
	require plugin_dir_path( __FILE__ ) . 'admin-ajax.php';

	include_once dirname( __FILE__ ) . '/classes/class-cdvi.php';
	include_once dirname( __FILE__ ) . '/classes/class-cardealer-vin-import.php';
}
