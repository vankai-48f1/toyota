<?php
/**
 * Register widgets.
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper/functions
 * @version 1.0.0
 */

if ( ! class_exists( 'CDHL_Widget' ) && class_exists( 'WP_Widget' ) ) {
	include_once trailingslashit( CDHL_PATH ) . 'includes/widgets/core/class-cdhl-widget.php';
}

include_once trailingslashit( CDHL_PATH ) . 'includes/widgets/helper/class-cdhl-vehicle-make-logos-generator.php';

if ( ! function_exists( 'cdhl_widgets_classes' ) ) {
	/**
	 * Include widgets.
	 *
	 * @return void
	 */
	function cdhl_widgets_classes() {
		require_once trailingslashit( CDHL_PATH ) . 'includes/widgets/about.php';
		require_once trailingslashit( CDHL_PATH ) . 'includes/widgets/archives.php';
		require_once trailingslashit( CDHL_PATH ) . 'includes/widgets/categories.php';
		require_once trailingslashit( CDHL_PATH ) . 'includes/widgets/meta.php';
		require_once trailingslashit( CDHL_PATH ) . 'includes/widgets/newsletter.php';
		require_once trailingslashit( CDHL_PATH ) . 'includes/widgets/recent-posts.php';
		require_once trailingslashit( CDHL_PATH ) . 'includes/widgets/tag-cloud.php';
		require_once trailingslashit( CDHL_PATH ) . 'includes/widgets/financing_calculator.php';
		require_once trailingslashit( CDHL_PATH ) . 'includes/widgets/inquiry.php';
		require_once trailingslashit( CDHL_PATH ) . 'includes/widgets/cars_filters.php';
		require_once trailingslashit( CDHL_PATH ) . 'includes/widgets/fuel_efficiency.php';
		require_once trailingslashit( CDHL_PATH ) . 'includes/widgets/cars_search.php';
		require_once trailingslashit( CDHL_PATH ) . 'includes/widgets/class-cdhl-widget-vehicle-make-logos.php';
		require_once trailingslashit( CDHL_PATH ) . 'includes/widgets/class-cdhl-widget-vehicle-categories.php';
	}
}
add_action( 'plugins_loaded', 'cdhl_widgets_classes', 20 );

if ( ! function_exists( 'cdhl_register_widgets' ) ) {
	/**
	 * Register widgets.
	 *
	 * @return void
	 */
	function cdhl_register_widgets() {
		register_widget( 'CarDealer_Helper_Widget_About' );
		register_widget( 'CarDealer_Helper_Widget_Archives' );
		register_widget( 'CarDealer_Helper_Widget_Categories' );
		register_widget( 'CarDealer_Helper_Widget_Meta' );
		register_widget( 'CarDealer_Helper_Widget_Newsletter' );
		register_widget( 'CarDealer_Helper_Widget_Recent_Posts' );
		register_widget( 'CarDealer_Helper_Widget_Tag_Cloud' );
		register_widget( 'CarDealer_Helper_Widget_Financing_Calculator' );
		register_widget( 'CarDealer_Helper_Widget_Inquiry' );
		register_widget( 'CarDealer_Helper_Widget_Cars_Filters' );
		register_widget( 'CarDealer_Helper_Widget_Fuel_Efficiency' );
		register_widget( 'CarDealer_Helper_Widget_Cars_Search' );
		register_widget( 'CDHL_Widget_Vehicle_Make_Logos' );
		register_widget( 'CDHL_Widget_Vehicle_Categories' );
	}
}
add_action( 'widgets_init', 'cdhl_register_widgets' );
