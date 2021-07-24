<?php

/**
 * Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 * @package CarDealer
 */

/*
 * If your child theme has more than one .css file (eg. ie.css, style.css, main.css) then
 * you will have to make sure to maintain all of the parent theme dependencies.
 *
 * Make sure you're using the correct handle for loading the parent theme's styles.
 * Failure to use the proper tag will result in a CSS file needlessly being loaded twice.
 * This will usually not affect the site appearance, but it's inefficient and extends your page's loading time.
 *
 * @link https://codex.wordpress.org/Child_Themes
 */
function toyota_ngoc_anh_enqueue_styles()
{ // phpcs:ignore WordPress.WhiteSpace.ControlStructureSpacing.NoSpaceAfterOpenParenthesis

	wp_enqueue_style('cardealer-main', get_parent_theme_file_uri('/css/style.css'));

	if (is_rtl()) {
		wp_enqueue_style('rtl-style', get_parent_theme_file_uri('/rtl.css'));
	}

	wp_enqueue_style(
		'toyota-ngoc-anh-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array('cardealer-main'),
		wp_get_theme()->get('Version')
	);
}
add_action('wp_enqueue_scripts', 'toyota_ngoc_anh_enqueue_styles', 11);


// Import Styles Css And Scripts Child Theme To Parent Theme
function toyota_ngoc_anh_enqueue_styles_and_scripts()
{

	wp_enqueue_style('child_theme_style_css', get_stylesheet_directory_uri() . '/css/style.css', false);
	wp_enqueue_style('child_theme_responsive_css', get_stylesheet_directory_uri() . '/css/responsive.css', false);
	wp_enqueue_style('child_theme_style_custom_css', get_stylesheet_directory_uri() . '/css/style-custom.css', false);

	wp_enqueue_script('child_theme_main_script', get_stylesheet_directory_uri() . '/js/main.js', false, true, true);
	wp_enqueue_script('child_theme_model_vehicle_script', get_stylesheet_directory_uri() . '/js/mode-vehicle.js', false, true, true);
	wp_enqueue_script('child_theme_test_driver_script', get_stylesheet_directory_uri() . '/js/test-driver.js', false, true, true);
	wp_enqueue_script('child_theme_city_​​province_script', get_stylesheet_directory_uri() . '/js/city-​​province.js', false, true, true);
	wp_enqueue_script('child_theme_calculate_insurance_cost_script', get_stylesheet_directory_uri() . '/js/calculate-insurance-cost.js', false, true, true);
	wp_enqueue_script('child_theme_tabs_script', get_stylesheet_directory_uri() . '/js/tabs.js', false, true, true);
	wp_enqueue_script('child_cost_estimate_script', get_stylesheet_directory_uri() . '/js/cost-estimate.js', false, true, true);
	wp_enqueue_script('child_calculate_loan_bank_script', get_stylesheet_directory_uri() . '/js/calculate-loan-bank.js', false, true, true);
	wp_enqueue_script('child_vehicle_compare_script', get_stylesheet_directory_uri() . '/js/vehicle-compare.js', false, true, true);
	wp_enqueue_script('child_vehicle_feng_shui_script', get_stylesheet_directory_uri() . '/js/feng-shui.js', false, true, true);





	// AJAX
	wp_enqueue_script('child_theme_compare_ajax', get_stylesheet_directory_uri() . '/js/AJAX/compare.js', false, true, true);
}

add_action('wp_enqueue_scripts', 'toyota_ngoc_anh_enqueue_styles_and_scripts', 12);


// add type script
add_filter('script_loader_tag', 'm_add_type_script', 10, 3);

function m_add_type_script($tag, $handle, $src)
{
	if ('child_theme_compare_ajax' === $handle) {
		$tag = '<script type="module" src="' . get_stylesheet_directory_uri() . '/js/AJAX/compare.js' . '" id="child_theme_compare_ajax"></script>';
	}

	return $tag;
}

// Create Page Option ACF
if (function_exists('acf_add_options_page')) {

	acf_add_options_page(array(
		'page_title' 	=> 'Theme Extension',
		'menu_title'	=> 'Theme Extension',
		'menu_slug' 	=> 'theme-extension'
	));
}

// ADMIN AJAX
function m_add_admin_ajax()
{
	echo '<input type="hidden" value="' . admin_url('admin-ajax.php') . '" id="url-admin-ajax" />';
}
add_action('wp_footer', 'm_add_admin_ajax');


// FILE HANDLE VEHICLE AJAX
require_once('handle-ajax/compare.php');

