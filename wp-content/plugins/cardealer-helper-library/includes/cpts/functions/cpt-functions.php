<?php
/**
 * Add CPT function
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

global $form_cpts, $wp_version;

add_filter( 'post_row_actions', 'cdhl_forms_row_actions', 10, 2 );
if ( ! function_exists( 'cdhl_forms_row_actions' ) ) {
	/**
	 * REMOVE ROW ACTIONS FOR DEALER FORMS POST TYPES [ 'ADD' ACTION ]
	 *
	 * @param string  $actions .
	 * @param WP_Post $post .
	 */
	function cdhl_forms_row_actions( $actions, WP_Post $post ) {
		if ( 'pgs_inquiry' !== $post->post_type && 'make_offer' !== $post->post_type && 'schedule_test_drive' !== $post->post_type && 'financial_inquiry' !== $post->post_type ) {
			return $actions;
		}
		unset( $actions['inline hide-if-no-js'] );
		if ( isset( $actions['edit'] ) ) {
			$actions['edit'] = str_replace( 'Edit', 'View', $actions['edit'] );
		}
		return $actions;
	}
}

add_action( 'add_meta_boxes', 'cdhl_my_remove_publish_metabox' );
if ( ! function_exists( 'cdhl_my_remove_publish_metabox' ) ) {
	/**
	 * CODE TO REMOVE METABOX FROM POST TYPE
	 */
	function cdhl_my_remove_publish_metabox() {
		// Remove right sidebar metabox.
		remove_meta_box( 'submitdiv', 'pgs_inquiry', 'side' );
		remove_meta_box( 'submitdiv', 'schedule_test_drive', 'side' );
		remove_meta_box( 'submitdiv', 'make_offer', 'side' );
		remove_meta_box( 'submitdiv', 'financial_inquiry', 'side' );

		// Remove Revolution Slider Metabox.
		remove_meta_box( 'mymetabox_revslider_0', 'pgs_inquiry', 999 );
		remove_meta_box( 'mymetabox_revslider_0', 'schedule_test_drive', 999 );
		remove_meta_box( 'mymetabox_revslider_0', 'make_offer', 999 );
		remove_meta_box( 'mymetabox_revslider_0', 'financial_inquiry', 999 );
	}
}

$form_cpts = array( 'pgs_inquiry', 'schedule_test_drive', 'make_offer', 'financial_inquiry' ); // DEALER CPTS.

// CODE TO REMOVE EDIT ACTION.
if ( $wp_version >= 4.7 ) {
	foreach ( $form_cpts as $cpt ) {
		add_filter( 'bulk_actions-edit-' . $cpt, 'cdhl_remove_edit_action' );
	}
} else { // LOWER VERSION.
	add_action( 'admin_footer', 'cdhl_dealer_form_admin_footer' ); // REMOVE EDIT ACTION.
}

if ( ! function_exists( 'cdhl_remove_edit_action' ) ) {
	/**
	 * FUNCTION TO REMOVE EDIT ACTION [ FOR WP VERSION >= 4.7 ]
	 *
	 * @param string $bulk_actions .
	 */
	function cdhl_remove_edit_action( $bulk_actions ) {
		global $post_type, $form_cpts;
		if ( in_array( $post_type, $form_cpts, true ) ) {
			unset( $bulk_actions['edit'] );
		}
		return $bulk_actions;
	}
}

if ( ! function_exists( 'cdhl_dealer_form_admin_footer' ) ) {
	/**
	 * FUNCTION TO REMOVE EDIT ACTION FROM BULK ACTIONS.
	 */
	function cdhl_dealer_form_admin_footer() {
		global $post_type, $form_cpts;
		if ( in_array( $post_type, $form_cpts, true ) ) {
			?>
				<script type="text/javascript">
					jQuery(document).ready(function() {
						jQuery("select[name='action'] option:contains('Edit')").remove();
					});
				</script>
			<?php
		}
	}
}

if ( ! function_exists( 'cdhl_add_back_button' ) ) {
	/**
	 * FUNCTION TO ADD BACK TO POST TYPE LISTING PAGE
	 *
	 * @param string $post .
	 */
	function cdhl_add_back_button( $post ) {
		global $post, $form_cpts;

		if ( ! in_array( $post->post_type, $form_cpts, true ) ) {
			return;
		}

		$link      = admin_url( 'edit.php?post_type=' . $post->post_type );
		$back_text = esc_html__( '&laquo; Back', 'cardealer-helper' );
		?>
		<br><br><a href="<?php echo esc_url( $link ); ?>" class="page-title-action"><?php echo esc_html( $back_text ); ?></a><br>
		<?php
	}
	add_action( 'edit_form_top', 'cdhl_add_back_button' );
}

/**
 * Create new taxonomy for the post type "cars".
 *
 * @see register_post_type() for registering custom post types.
 */
function cdhl_register_additional_attributes_taxonomy() {

	$additional_attributes = get_option( 'cdhl_additional_attributes' );

	$additional_attributes = apply_filters( 'cdhl_register_additional_attributes', $additional_attributes );

	if ( ! empty( $additional_attributes ) ) {

		foreach ( $additional_attributes as $key => $attr ) {

			$attribute_slug = ( isset( $attr['slug'] ) && ! empty( $attr['slug'] ) ) ? $attr['slug'] : sanitize_title( $attr['singular_name'] );

			// Add new taxonomy, make it hierarchical (like categories).

			$plural_name   = $attr['plural_name'];
			$singular_name = $attr['singular_name'];

			$labels = array(
				'name'                       => $plural_name,
				'singular_name'              => $singular_name,
				'menu_name'                  => $plural_name,
				'all_items'                  => sprintf(
					/* translators: %s: Plural taxonomy name */
					esc_html__( 'All %s', 'cardealer-helper' ),
					$plural_name
				),
				'edit_item'                  => sprintf(
					/* translators: %s: Singular taxonomy name */
					esc_html__( 'Edit %s', 'cardealer-helper' ),
					$singular_name
				),
				'view_item'                  => sprintf(
					/* translators: %s: Singular taxonomy name */
					__( 'View %s', 'cardealer-helper' ),
					$singular_name
				),
				'update_item'                => sprintf(
					/* translators: %s: Singular taxonomy name */
					esc_html__( 'Update', 'cardealer-helper' ),
					$singular_name
				),
				'add_new_item'               => sprintf(
					/* translators: %s: Singular taxonomy name */
					esc_html__( 'Add New %s', 'cardealer-helper' ),
					$singular_name
				),
				'new_item_name'              => sprintf(
					/* translators: %s: Singular taxonomy name */
					esc_html__( 'New %s Name', 'cardealer-helper' ),
					$singular_name
				),
				'parent_item'                => sprintf(
					/* translators: %s: Singular taxonomy name */
					esc_html__( 'Parent %s', 'cardealer-helper' ),
					$singular_name
				),
				'parent_item_colon'          => sprintf(
					/* translators: %s: Singular taxonomy name */
					esc_html__( 'Parent %s', 'cardealer-helper' ),
					$singular_name
				),
				'search_items'               => sprintf(
					/* translators: %s: Plural taxonomy name */
					esc_html__( 'Search %s', 'cardealer-helper' ),
					$plural_name
				),
				'popular_items'              => sprintf(
					/* translators: %s: Plural taxonomy name */
					esc_html__( 'Popular %s', 'cardealer-helper' ),
					$plural_name
				),
				'separate_items_with_commas' => sprintf(
					/* translators: %s: Plural taxonomy name */
					esc_html__( 'Separate %s with commas', 'cardealer-helper' ),
					strtolower( $plural_name )
				),
				'add_or_remove_items'        => sprintf(
					/* translators: %s: Plural taxonomy name */
					esc_html__( 'Add or remove %s', 'cardealer-helper' ),
					$plural_name
				),
				'choose_from_most_used'      => sprintf(
					/* translators: %s: Plural taxonomy name */
					esc_html__( 'Choose from the most used %s', 'cardealer-helper' ),
					strtolower( $plural_name )
				),
				'not_found'                  => sprintf(
					/* translators: %s: Plural taxonomy name */
					esc_html__( 'No %s found.', 'cardealer-helper' ),
					strtolower( $plural_name )
				),
				'back_to_items'              => sprintf(
					/* translators: %s: Plural taxonomy name */
					esc_html__( '&larr; Back to %s', 'cardealer-helper' ),
					strtolower( $plural_name )
				),
			);

			$args = array(
				'labels'             => $labels,
				'public'             => false,
				'publicly_queryable' => true,
				'hierarchical'       => false,
				'show_ui'            => true,
				'show_in_menu'       => false,
				'show_in_nav_menus'  => false,
				'show_in_rest'       => true,
				'show_tagcloud'      => false,
				'show_in_quick_edit' => false,
				'show_admin_column'  => false,
				'query_var'          => true,
				'rewrite'            => array(
					'slug' => $attribute_slug,
				),
			);

			/**
			 *  Filters the arguements for registering additional attributes.
			 *
			 *  @param array $args     An array of arguments.
			 */
			$args = apply_filters( "cdhl_register_additional_attribute_{$attribute_slug}", $args );

			$args['is_cardealer_attribute']  = true;
			$args['is_additional_attribute'] = true;
			$args['include_in_filters']      = true;

			register_taxonomy( $attribute_slug, array( 'cars' ), $args );

		}
	}
}
// hook into the init action and call create_book_taxonomies when it fires.
add_action( 'init', 'cdhl_register_additional_attributes_taxonomy', 1 );

/**
 * Fires after a taxonomy is registered.
 *
 * @param string       $taxonomy Taxonomy slug.
 * @param array|string $obj_type Object type or array of object types.
 * @param array        $args     Array of taxonomy registration arguments.
 */
function cdhl_translate_additional_attributes_label_in_wp_taxonomies( $taxonomy, $obj_type, $args ) {
	global $wp_taxonomies, $sitepress;
	$obj_type = array_unique( (array) $obj_type );

	if ( $sitepress ) {
		$current_language      = $sitepress->get_current_language();
		$core_attributes       = cdhl_get_core_attributes_option();
		$core_attributes       = array_column( $core_attributes, 'taxonomy' );

		$additional_attributes = get_option( 'cdhl_additional_attributes' );
		$additional_attributes = array_column( $additional_attributes, 'slug' );

		$attributes            = array_merge( $core_attributes, $additional_attributes );

		if (
			'all' !== $current_language
			&& in_array( 'cars', $obj_type, true )
			&& in_array( $taxonomy, $attributes, true )
		) {
			$plural_name   = $args['labels']->name;
			$singular_name = $args['labels']->singular_name;

			$wp_taxonomies[ $taxonomy ]->labels->name          = apply_filters( 'wpml_translate_single_string', $plural_name, 'WordPress', 'taxonomy general name: ' . $plural_name, $current_language );
			$wp_taxonomies[ $taxonomy ]->labels->menu_name     = apply_filters( 'wpml_translate_single_string', $plural_name, 'WordPress', 'taxonomy general name: ' . $plural_name, $current_language );
			$wp_taxonomies[ $taxonomy ]->labels->singular_name = apply_filters( 'wpml_translate_single_string', $singular_name, 'WordPress', 'taxonomy singular name: ' . $singular_name, $current_language );

		}
	}
}
add_action( 'registered_taxonomy', 'cdhl_translate_additional_attributes_label_in_wp_taxonomies', 9999, 3 );
