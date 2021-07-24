<?php
/**
 * Include custom post type.
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper/functions
 * @version 1.0.0
 */

// Cores.
require_once trailingslashit( CDHL_PATH ) . 'includes/cpts/cores/class-extended-cpts.php';
require_once trailingslashit( CDHL_PATH ) . 'includes/cpts/cores/class-extended-taxos.php';
// CPTs.
require_once trailingslashit( CDHL_PATH ) . 'includes/cpts/testimonials.php';
require_once trailingslashit( CDHL_PATH ) . 'includes/cpts/teams.php';
require_once trailingslashit( CDHL_PATH ) . 'includes/cpts/faqs.php';
require_once trailingslashit( CDHL_PATH ) . 'includes/cpts/dealer_forms/inquiry.php';
require_once trailingslashit( CDHL_PATH ) . 'includes/cpts/dealer_forms/make-an-offer.php';
require_once trailingslashit( CDHL_PATH ) . 'includes/cpts/dealer_forms/schedule-test-drive.php';
require_once trailingslashit( CDHL_PATH ) . 'includes/cpts/dealer_forms/financial-form.php';
require_once trailingslashit( CDHL_PATH ) . 'includes/cpts/cars.php';
require_once trailingslashit( CDHL_PATH ) . 'includes/cpts/car-meta-box.php'; // Add custom metabox for woo product map with vehicle.
require_once trailingslashit( CDHL_PATH ) . 'includes/cpts/export/export-log.php';
require_once trailingslashit( CDHL_PATH ) . 'includes/cpts/import/import-log.php';
require_once trailingslashit( CDHL_PATH ) . 'includes/cpts/geo-fencing.php';
require_once trailingslashit( CDHL_PATH ) . 'includes/cpts/promocodes.php';
