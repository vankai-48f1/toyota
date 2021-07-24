<?php
/**
 * Add CPT For Import log
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

add_action( 'init', 'cdhl_import_log' );
if ( ! function_exists( 'cdhl_import_log' ) ) {
	/**
	 * Import log
	 */
	function cdhl_import_log() {
		$args = array(
			'labels'              => array(
				'name'          => esc_html__( 'Import Log', 'cardealer-helper' ),
				'singular_name' => esc_html__( 'Import Log', 'cardealer-helper' ),
			),
			'capability_type'     => 'post',
			'capabilities'        => array(
				'create_posts' => 'do_not_allow', // false < WP 4.5, credit @Ewout.
			),
			'map_meta_cap'        => true,
			'public'              => true,
			'show_in_menu'        => false,
			'show_in_nav_menus'   => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => false,
			'has_archive'         => true,
			'rewrite'             => array( 'slug' => 'import_log' ),
			'supports'            => array(),
		);
		register_post_type( 'pgs_import_log', $args );
	}
}
