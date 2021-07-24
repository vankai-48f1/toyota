<?php
/**
 * Register Post Type for Faq
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

add_action( 'init', 'cdhl_cpt_faq', 1 );
if ( ! function_exists( 'cdhl_cpt_faq' ) ) {
	/**
	 * Faq CPT
	 */
	function cdhl_cpt_faq() {
		$labels = array(
			'name'               => esc_html__( 'Faqs', 'cardealer-helper' ),
			'singular_name'      => esc_html__( 'Faq', 'cardealer-helper' ),
			'menu_name'          => esc_html__( 'Faqs', 'cardealer-helper' ),
			'name_admin_bar'     => esc_html__( 'Faq', 'cardealer-helper' ),
			'add_new'            => esc_html__( 'Add New', 'cardealer-helper' ),
			'add_new_item'       => esc_html__( 'Add New Faq', 'cardealer-helper' ),
			'new_item'           => esc_html__( 'New Faq', 'cardealer-helper' ),
			'edit_item'          => esc_html__( 'Edit Faq', 'cardealer-helper' ),
			'view_item'          => esc_html__( 'View Faq', 'cardealer-helper' ),
			'all_items'          => esc_html__( 'All Faqs', 'cardealer-helper' ),
			'search_items'       => esc_html__( 'Search Faqs', 'cardealer-helper' ),
			'parent_item_colon'  => esc_html__( 'Parent Faqs:', 'cardealer-helper' ),
			'not_found'          => esc_html__( 'No faqs found.', 'cardealer-helper' ),
			'not_found_in_trash' => esc_html__( 'No faqs found in Trash.', 'cardealer-helper' ),
		);

		$args = array(
			'labels'              => $labels,
			'description'         => esc_html__( 'Description.', 'cardealer-helper' ),
			'public'              => true,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'query_var'           => true,
			'rewrite'             => array( 'slug' => 'faqs' ),
			'capability_type'     => 'post',
			'has_archive'         => false,
			'exclude_from_search' => true,
			'hierarchical'        => false,
			'menu_position'       => null,
			'supports'            => array( 'title', 'editor', 'author', 'thumbnail' ),
			'menu_icon'           => 'dashicons-info',
		);

		register_post_type( 'faqs', $args );

			// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Faq Categories', 'cardealer-helper' ),
			'singular_name'              => esc_html__( 'Faq Category', 'cardealer-helper' ),
			'search_items'               => esc_html__( 'Search Faq Categories', 'cardealer-helper' ),
			'popular_items'              => esc_html__( 'Popular Faq Categories', 'cardealer-helper' ),
			'all_items'                  => esc_html__( 'All Faq Categories', 'cardealer-helper' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Category', 'cardealer-helper' ),
			'update_item'                => esc_html__( 'Update Category', 'cardealer-helper' ),
			'add_new_item'               => esc_html__( 'Add New Category', 'cardealer-helper' ),
			'new_item_name'              => esc_html__( 'New Category Name', 'cardealer-helper' ),
			'separate_items_with_commas' => esc_html__( 'Separate categories with commas', 'cardealer-helper' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Categories', 'cardealer-helper' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Categories', 'cardealer-helper' ),
			'not_found'                  => esc_html__( 'No categories found.', 'cardealer-helper' ),
			'menu_name'                  => esc_html__( 'Categories', 'cardealer-helper' ),
		);

		$args = array(
			'hierarchical'          => true,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'faq-category' ),
		);
		register_taxonomy( 'faq-category', 'faqs', $args );
	}
}
