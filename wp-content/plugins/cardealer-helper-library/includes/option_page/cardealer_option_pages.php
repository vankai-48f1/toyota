<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Option Page
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

if ( function_exists( 'acf_add_options_sub_page' ) ) {
	acf_add_options_sub_page(
		array(
			'page_title'  => esc_html__( 'Vehicle Brochure Generator', 'cardealer-helper' ),
			'menu_title'  => esc_html__( 'PDF Brochure Generator', 'cardealer-helper' ),
			'parent_slug' => 'edit.php?post_type=cars',
			'capability'  => 'manage_options',
			'menu_slug'   => 'pdf_generator',
		)
	);
}

// Google Analytics Menu.
if ( function_exists( 'acf_add_options_page' ) ) {
	acf_add_options_page(
		array(
			'page_title' => esc_html__( 'Google Analytics', 'cardealer-helper' ),
			'menu_title' => esc_html__( 'Google Analytics Settings', 'cardealer-helper' ),
			'menu_slug'  => 'google-analytics-settings',
			'capability' => 'edit_posts',
			'redirect'   => false,
		)
	);
}
