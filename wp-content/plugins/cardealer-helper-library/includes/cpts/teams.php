<?php
/**
 * Register Post Type for Team
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

add_action( 'init', 'cdhl_cpt_team', 1 );
if ( ! function_exists( 'cdhl_cpt_team' ) ) {
	/**
	 * Register team CPT
	 */
	function cdhl_cpt_team() {
		$labels = array(
			'name'                  => esc_html__( 'Teams', 'cardealer-helper' ),
			'singular_name'         => esc_html__( 'Team', 'cardealer-helper' ),
			'menu_name'             => esc_html__( 'Teams', 'cardealer-helper' ),
			'name_admin_bar'        => esc_html__( 'Team', 'cardealer-helper' ),
			'add_new'               => esc_html__( 'Add New', 'cardealer-helper' ),
			'add_new_item'          => esc_html__( 'Add New Team', 'cardealer-helper' ),
			'new_item'              => esc_html__( 'New Team', 'cardealer-helper' ),
			'edit_item'             => esc_html__( 'Edit Team', 'cardealer-helper' ),
			'view_item'             => esc_html__( 'View Team', 'cardealer-helper' ),
			'all_items'             => esc_html__( 'All Teams', 'cardealer-helper' ),
			'search_items'          => esc_html__( 'Search Teams', 'cardealer-helper' ),
			'parent_item_colon'     => esc_html__( 'Parent Teams:', 'cardealer-helper' ),
			'not_found'             => esc_html__( 'No teams found.', 'cardealer-helper' ),
			'not_found_in_trash'    => esc_html__( 'No teams found in Trash.', 'cardealer-helper' ),
			'featured_image'        => esc_html__( 'Member Image', 'cardealer-helper' ),
			'set_featured_image'    => esc_html__( 'Set member image', 'cardealer-helper' ),
			'remove_featured_image' => esc_html__( 'Remove member image', 'cardealer-helper' ),
			'use_featured_image'    => esc_html__( 'Use member image', 'cardealer-helper' ),
		);

		$args = array(
			'labels'              => $labels,
			'description'         => esc_html__( 'Description.', 'cardealer-helper' ),
			'public'              => false,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'query_var'           => true,
			'capability_type'     => 'post',
			'has_archive'         => false,
			'exclude_from_search' => false,
			'hierarchical'        => false,
			'menu_position'       => null,
			'supports'            => array(
				'title',
				'editor',
				'thumbnail',
			),
			'menu_icon'           => 'dashicons-groups',
		);

		register_post_type( 'teams', $args );

			$labels = array(
				'name'                       => esc_html__( 'Team Categories', 'cardealer-helper' ),
				'singular_name'              => esc_html__( 'Team Category', 'cardealer-helper' ),
				'search_items'               => esc_html__( 'Search team Categories', 'cardealer-helper' ),
				'popular_items'              => esc_html__( 'Popular team Categories', 'cardealer-helper' ),
				'all_items'                  => esc_html__( 'All team Categories', 'cardealer-helper' ),
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
	}
}

if ( ! function_exists( 'cdhl_cpt_teams_edit_columns' ) ) {
	/**
	 * Edit columns
	 *
	 * @param string $columns .
	 */
	function cdhl_cpt_teams_edit_columns( $columns = array() ) {
		$newcolumns = array(
			'cb'             => "<input type='checkbox' />",
			'team_thumbnail' => esc_html__( 'Photo', 'cardealer-helper' ),
		);
		$columns    = array_merge( $newcolumns, $columns );

		return $columns;
	}
	add_filter( 'manage_edit-teams_columns', 'cdhl_cpt_teams_edit_columns' );
}

if ( ! function_exists( 'cdhl_cpt_teams_custom_columns' ) ) {
	/**
	 * Custom columns
	 *
	 * @param string $column .
	 */
	function cdhl_cpt_teams_custom_columns( $column ) {
		global $post;
		switch ( $column ) {
			case 'team_thumbnail':
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'cardealer-50x50' );
				}
				break;
		}
	}
	add_action( 'manage_posts_custom_column', 'cdhl_cpt_teams_custom_columns' );
}
