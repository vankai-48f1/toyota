<?php
/**
 * Register Post Type for Team
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

add_action( 'init', 'cdhl_cpt_testimonial', 1 );
if ( ! function_exists( 'cdhl_cpt_testimonial' ) ) {
	/**
	 * Register testimonial CPT
	 */
	function cdhl_cpt_testimonial() {
		$labels = array(
			'name'               => esc_html__( 'Testimonials', 'cardealer-helper' ),
			'singular_name'      => esc_html__( 'Testimonial', 'cardealer-helper' ),
			'menu_name'          => esc_html__( 'Testimonials', 'cardealer-helper' ),
			'name_admin_bar'     => esc_html__( 'Testimonial', 'cardealer-helper' ),
			'add_new'            => esc_html__( 'Add New', 'cardealer-helper' ),
			'add_new_item'       => esc_html__( 'Add New Testimonial', 'cardealer-helper' ),
			'new_item'           => esc_html__( 'New Testimonial', 'cardealer-helper' ),
			'edit_item'          => esc_html__( 'Edit Testimonial', 'cardealer-helper' ),
			'view_item'          => esc_html__( 'View Testimonial', 'cardealer-helper' ),
			'all_items'          => esc_html__( 'All Testimonials', 'cardealer-helper' ),
			'search_items'       => esc_html__( 'Search Testimonials', 'cardealer-helper' ),
			'parent_item_colon'  => esc_html__( 'Parent Testimonials:', 'cardealer-helper' ),
			'not_found'          => esc_html__( 'No testimonials found.', 'cardealer-helper' ),
			'not_found_in_trash' => esc_html__( 'No testimonials found in Trash.', 'cardealer-helper' ),
		);

		$args = array(
			'labels'              => $labels,
			'description'         => esc_html__( 'Description.', 'cardealer-helper' ),
			'public'              => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'query_var'           => true,
			'rewrite'             => array( 'slug' => 'testimonials' ),
			'capability_type'     => 'post',
			'exclude_from_search' => true,
			'has_archive'         => false,
			'hierarchical'        => false,
			'menu_position'       => null,
			'supports'            => array( 'title', 'editor', 'author', 'thumbnail' ),
			'menu_icon'           => 'dashicons-testimonial',
		);

		register_post_type( 'testimonials', $args );
	}
}

if ( ! function_exists( 'cdhl_cpt_testimonials_edit_columns' ) ) {
	/**
	 * Edit columns
	 *
	 * @param string $columns .
	 */
	function cdhl_cpt_testimonials_edit_columns( $columns = array() ) {
		$newcolumns = array(
			'cb'                    => "<input type='checkbox' />",
			'testimonial_thumbnail' => esc_html__( 'Photo', 'cardealer-helper' ),
			'title'                 => esc_html__( 'Title', 'cardealer-helper' ),
			'testimonial_order'     => esc_html__( 'Order', 'cardealer-helper' ),
		);
		$columns    = array_merge( $newcolumns, $columns );
		return $columns;
	}
	add_filter( 'manage_edit-testimonials_columns', 'cdhl_cpt_testimonials_edit_columns' );
}

if ( ! function_exists( 'cdhl_cpt_testimonials_custom_columns' ) ) {
	/**
	 * Custom columns
	 *
	 * @param string $column .
	 */
	function cdhl_cpt_testimonials_custom_columns( $column ) {
		global $post;
		switch ( $column ) {
			case 'testimonial_thumbnail':
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'cardealer-50x50' );
				}
				break;
			case 'testimonial_order':
				echo esc_html( $post->menu_order );
				break;
		}
	}
	add_action( 'manage_posts_custom_column', 'cdhl_cpt_testimonials_custom_columns' );
}
