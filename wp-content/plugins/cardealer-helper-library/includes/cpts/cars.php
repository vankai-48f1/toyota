<?php
/**
 * Theme functions to register post type for vehicles.
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper/functions
 * @version 1.0.0
 */

add_action( 'init', 'cdhl_cars_cpt', 1 );
if ( ! function_exists( 'cdhl_cars_cpt' ) ) {
	/**
	 * Cars CPT
	 */
	function cdhl_cars_cpt() {
		$labels = array(
			'name'               => esc_html__( 'Inventory', 'cardealer-helper' ),
			'singular_name'      => esc_html__( 'Vehicles', 'cardealer-helper' ),
			'menu_name'          => esc_html__( 'Vehicle Inventory', 'cardealer-helper' ),
			'name_admin_bar'     => esc_html__( 'Vehicles', 'cardealer-helper' ),
			'add_new'            => esc_html__( 'Add New', 'cardealer-helper' ),
			'add_new_item'       => esc_html__( 'Add New Vehicle', 'cardealer-helper' ),
			'new_item'           => esc_html__( 'New Vehicles', 'cardealer-helper' ),
			'edit_item'          => esc_html__( 'Edit Vehicle', 'cardealer-helper' ),
			'view_item'          => esc_html__( 'View Vehicle', 'cardealer-helper' ),
			'all_items'          => esc_html__( 'All Vehicles', 'cardealer-helper' ),
			'search_items'       => esc_html__( 'Search Inventory', 'cardealer-helper' ),
			'parent_item_colon'  => esc_html__( 'Parent Vehicles:', 'cardealer-helper' ),
			'not_found'          => esc_html__( 'No Vehicles found.', 'cardealer-helper' ),
			'not_found_in_trash' => esc_html__( 'No Vehicles found in Trash.', 'cardealer-helper' ),
		);

		$args = array(
			'labels'             => $labels,
			'description'        => esc_html__( 'Description.', 'cardealer-helper' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array(
				'slug'       => cdhl_cars_page_slug(),
				'with_front' => false,
			),
			'capability_type'    => 'post',
			'has_archive'        => cdhl_get_car_archive_page(),
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'author', 'excerpt' ),
			'menu_icon'          => 'dashicons-pressthis',
		);
		/**
		 * Filters the arguments to be used to register inventory post type.
		 *
		 * @since 1.0
		 * @param array      $args  Array of arguments used to register inventory post type.
		 * @visible          true
		 */
		register_post_type( 'cars', apply_filters( 'cdhl_cars_cpt_cars', $args ) );

		$core_attr_static = cardealer_core_attributes_static_data();
		$core_attributes  = cdhl_get_core_attributes_option();

		if ( ! empty( $core_attributes ) ) {
			foreach ( $core_attributes as $core_tax => $core_attr ) {

				if ( ! is_array( $core_attr ) ) {
					continue;
				}

				// Add new taxonomy, NOT hierarchical (like tags).
				$labels_new = array(
					'name'                       => $core_attr['plural_name'],
					'singular_name'              => $core_attr['singular_name'],
					'menu_name'                  => $core_attr['plural_name'],
					/* translators: %s: taxonomy plural name */
					'all_items'                  => sprintf( esc_html__( 'All %s', 'cardealer-helper' ), $core_attr['plural_name'] ),
					/* translators: %s: taxonomy plural name */
					'edit_item'                  => sprintf( esc_html__( 'Edit %s', 'cardealer-helper' ), $core_attr['singular_name'] ),
					/* translators: %s: taxonomy plural name */
					'view_item'                  => sprintf( esc_html__( 'View %s', 'cardealer-helper' ), $core_attr['singular_name'] ),
					/* translators: %s: taxonomy plural name */
					'update_item'                => sprintf( esc_html__( 'Update %s', 'cardealer-helper' ), $core_attr['singular_name'] ),
					/* translators: %s: taxonomy plural name */
					'add_new_item'               => sprintf( esc_html__( 'Add New %s', 'cardealer-helper' ), $core_attr['singular_name'] ),
					/* translators: %s: taxonomy plural name */
					'new_item_name'              => sprintf( esc_html__( 'New %s Name', 'cardealer-helper' ), $core_attr['singular_name'] ),
					/* translators: %s: taxonomy plural name */
					'parent_item'                => sprintf( esc_html__( 'Parent %s', 'cardealer-helper' ), $core_attr['singular_name'] ),
					/* translators: %s: taxonomy plural name */
					'parent_item_colon'          => sprintf( esc_html__( 'Parent %s:', 'cardealer-helper' ), $core_attr['singular_name'] ),
					/* translators: %s: taxonomy plural name */
					'search_items'               => sprintf( esc_html__( 'Search %s', 'cardealer-helper' ), $core_attr['plural_name'] ),
					/* translators: %s: taxonomy plural name */
					'popular_items'              => sprintf( esc_html__( 'Popular %s', 'cardealer-helper' ), $core_attr['plural_name'] ),
					/* translators: %s: taxonomy plural name */
					'separate_items_with_commas' => sprintf( esc_html__( 'Separate %s with commas', 'cardealer-helper' ), strtolower( $core_attr['plural_name'] ) ),
					/* translators: %s: taxonomy plural name */
					'add_or_remove_items'        => sprintf( esc_html__( 'Add or remove %s', 'cardealer-helper' ), strtolower( $core_attr['plural_name'] ) ),
					/* translators: %s: taxonomy plural name */
					'choose_from_most_used'      => sprintf( esc_html__( 'Choose from most used %s', 'cardealer-helper' ), strtolower( $core_attr['plural_name'] ) ),
					/* translators: %s: taxonomy plural name */
					'not_found'                  => sprintf( esc_html__( 'No %s found', 'cardealer-helper' ), strtolower( $core_attr['plural_name'] ) ),
					/* translators: %s: taxonomy plural name */
					'back_to_items'              => sprintf( esc_html__( '&larr; Back to %s', 'cardealer-helper' ), $core_attr['plural_name'] ),
					/* translators: %s: taxonomy plural name */
					'no_terms'                   => sprintf( esc_html__( 'No %s', 'cardealer-helper' ), strtolower( $core_attr['plural_name'] ) ),
					/* translators: %s: taxonomy plural name */
					'filter_by_item'             => sprintf( esc_html__( 'Filter by %s', 'cardealer-helper' ), strtolower( $core_attr['singular_name'] ) ),
					/* translators: %s: taxonomy plural name */
					'items_list_navigation'      => sprintf( esc_html__( '%s list navigation', 'cardealer-helper' ), $core_attr['plural_name'] ),
					/* translators: %s: taxonomy plural name */
					'items_list'                 => sprintf( esc_html__( '%s list', 'cardealer-helper' ), $core_attr['plural_name'] ),
					'most_used'                  => esc_html__( 'Most Used', 'cardealer-helper' ),
					/* translators: %s: taxonomy plural name */
					'item_link'                  => sprintf( esc_html__( '%s Link', 'cardealer-helper' ), $core_attr['singular_name'] ),
					/* translators: %s: taxonomy plural name */
					'item_link_description'      => sprintf( esc_html__( 'A link to a %s.', 'cardealer-helper' ), strtolower( $core_attr['singular_name'] ) ),
					'name_admin_bar'             => $core_attr['singular_name'],
					/* translators: %s: taxonomy plural name */
					'archives'                   => sprintf( esc_html__( '%s Archives', 'cardealer-helper' ), $core_attr['plural_name'] ),
					/* translators: %s: taxonomy plural name */
					'no_item'                    => sprintf( esc_html__( 'No %s', 'cardealer-helper' ), strtolower( $core_attr['singular_name'] ) ), # Custom label
					/* translators: %s: taxonomy plural name */
					'filter_by'                  => sprintf( esc_html__( 'Filter by %s', 'cardealer-helper' ), strtolower( $core_attr['singular_name'] ) ), # Custom label
				);

				$tax_args_defaults = array(
					'hierarchical'          => false,
					'labels'                => $labels_new,
					'show_ui'               => true,
					'show_in_menu'          => false,
					'show_in_nav_menus'     => false,
					'show_admin_column'     => false,
					'query_var'             => true,
					'rewrite'               => false,
					'show_in_quick_edit'    => false,
					'meta_box_cb'           => false,
				);

				$static_tax_args     = ( isset( $core_attr_static[ $core_tax ]['args'] ) && is_array( $core_attr_static[ $core_tax ]['args'] ) && ! empty( $core_attr_static[ $core_tax ]['args'] ) ) ? $core_attr_static[ $core_tax ]['args'] : array();
				$tax_rewrite_defalts = array(
					'slug' => $core_attr['slug'],
				);
				$tax_rewrite         = wp_parse_args(
					$tax_rewrite_defalts,
					( ( isset( $static_tax_args['rewrite'] ) && is_array( $static_tax_args['rewrite'] ) && ! empty( $static_tax_args['rewrite'] ) ) ? $static_tax_args['rewrite'] : array() )
				);
				$tax_args            = wp_parse_args( $static_tax_args, $tax_args_defaults );
				$tax_args['rewrite'] = $tax_rewrite;

				/**
				 * Filters the arguments to be used to register vehicle category taxonomy.
				 *
				 * @since 1.0
				 * @param array      $args  Array of arguments used to register vehicle category taxonomy.
				 * @visible          true
				 */
				$tax_args = apply_filters( 'cdhl_cars_taxonomy_' . $core_attr['taxonomy'], $tax_args );

				$tax_args['is_cardealer_attribute'] = true;
				$tax_args['is_core_attribute']      = true;

				register_taxonomy( $core_attr['taxonomy'], 'cars', $tax_args );
			}
		}

		// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Vehicle Categories', 'cardealer-helper' ),
			'singular_name'              => esc_html__( 'Category', 'cardealer-helper' ),
			'search_items'               => esc_html__( 'Search categories', 'cardealer-helper' ),
			'popular_items'              => esc_html__( 'Popular categories', 'cardealer-helper' ),
			'all_items'                  => esc_html__( 'All categories', 'cardealer-helper' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit category', 'cardealer-helper' ),
			'update_item'                => esc_html__( 'Update category', 'cardealer-helper' ),
			'add_new_item'               => esc_html__( 'Add New Vehicle Category', 'cardealer-helper' ),
			'new_item_name'              => esc_html__( 'New category name', 'cardealer-helper' ),
			'separate_items_with_commas' => esc_html__( 'Separate category with commas', 'cardealer-helper' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove category', 'cardealer-helper' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used category', 'cardealer-helper' ),
			'not_found'                  => esc_html__( 'No category found.', 'cardealer-helper' ),
			'menu_name'                  => esc_html__( 'Vehicle Categories', 'cardealer-helper' ),
		);

		$args = array(
			'hierarchical'          => true,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'show_in_menu'          => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'vehicle-category' ),
		);

		/**
		 * Filters the arguments to be used to register vehicle category taxonomy.
		 *
		 * @since 1.0
		 * @param array      $args  Array of arguments used to register vehicle category taxonomy.
		 * @visible          true
		 */
		register_taxonomy( 'vehicle_cat', 'cars', apply_filters( 'cdhl_cars_taxonomy_vehicle_cat', $args ) );
	}
}

if ( ! function_exists( 'theme_columns' ) ) {
	/**
	 * Theme columns
	 *
	 * @param string $theme_columns .
	 */
	function theme_columns( $theme_columns ) {

		$new_columns = array(
			'cb'    => '<input type="checkbox" />',
			'name'  => __( 'Name', 'cardealer-helper' ),
			'image' => __( 'Image', 'cardealer-helper' ),
			'url'   => __( 'Url', 'cardealer-helper' ),
			'slug'  => __( 'Slug', 'cardealer-helper' ),
			'posts' => __( 'Count', 'cardealer-helper' ),
		);
		return $new_columns;

	}
	add_filter( 'manage_edit-car_vehicle_review_stamps_columns', 'theme_columns' );
}

if ( ! function_exists( 'add_car_vehicle_review_stamps_content' ) ) {
	/**
	 * Add car review stamps content
	 *
	 * @param string $content .
	 * @param string $column_name .
	 * @param string $term_id .
	 */
	function add_car_vehicle_review_stamps_content( $content, $column_name, $term_id ) {
		$url      = '';
		$imageurl = '';
		if ( ! empty( $term_id ) ) {
			$image     = get_term_meta( $term_id, 'image' );
			$image_url = wp_get_attachment_url( $image[0] );
			$imageurl  = '';

			if ( $image_url ) {
				$imageurl = '<img src="' . $image_url . '" width="60px" height="60px"/>';
			}

			$url_arr = get_term_meta( $term_id, 'url' );
			if ( isset( $url_arr[0] ) && ! empty( $url_arr[0] ) ) {
				$url = $url_arr[0];
			}
		}

		switch ( $column_name ) {
			case 'image':
				// do your stuff here with $term or $term_id.
				$content = $imageurl;
				break;
			case 'url':
				// do your stuff here with $term or $term_id.
				$content = $url;
				break;
			default:
				break;
		}
		return $content;
	}
	add_filter( 'manage_car_vehicle_review_stamps_custom_column', 'add_car_vehicle_review_stamps_content', 10, 3 );
}

if ( ! function_exists( 'cdhl_cpt_cars_edit_columns' ) ) {
	/**
	 * Edit colums
	 *
	 * @param string $columns .
	 */
	function cdhl_cpt_cars_edit_columns( $columns ) {
		unset( $columns['author'] );
		$new_fields =
			array_slice(
				$columns,
				0,
				2,
				true
			) +
			array(
				'price'             => esc_html__( 'Price', 'cardealer-helper' ),
				'cars_stock_number' => esc_html__( 'Stock Number', 'cardealer-helper' ),
				'car_vin_number'    => esc_html__( 'VIN Number', 'cardealer-helper' ),
				'featured'          => esc_html__( 'Featured', 'cardealer-helper' ),
				'auto_trader'       => esc_html__( 'Auto Trader', 'cardealer-helper' ),
				'car_com'           => esc_html__( 'Cars.com', 'cardealer-helper' ),
				'pdf'               => esc_html__( 'Brochure generator', 'cardealer-helper' ),
			) +
			array_slice( $columns, 2, count( $columns ) - 1, true );
			$image  = array( 'image' => esc_html__( 'Image', 'cardealer-helper' ) );
			array_splice( $new_fields, 1, 0, $image );
		return $new_fields;
	}
	add_filter( 'manage_edit-cars_columns', 'cdhl_cpt_cars_edit_columns' );
}

if ( ! function_exists( 'cdhl_cpt_cars_custom_columns' ) ) {
	/**
	 * Custom columns
	 *
	 * @param string $column .
	 * @param string $post_id .
	 */
	function cdhl_cpt_cars_custom_columns( $column, $post_id ) {
		switch ( $column ) {

			case 'image':
				if ( function_exists( 'cardealer_get_cars_image' ) ) {
					echo wp_kses(
						cardealer_get_cars_image( 'cardealer-50x50', $post_id ),
						cardealer_allowed_html( array( 'img' ) )
					);
				}
				break;

			case 'price':
				$price = ( function_exists( 'cardealer_get_car_price' ) ) ? cardealer_get_car_price( '', $post_id ) : '';
				echo wp_kses(
					$price,
					array(
						'div'  => array(
							'class' => true,
						),
						'span' => array(
							'class' => true,
						),
					)
				);
				break;

			case 'cars_stock_number':
				$car_stock_number_args = array(
					'orderby' => 'name',
					'order'   => 'ASC',
					'fields'  => 'all',
				);

				$terms            = wp_get_post_terms( $post_id, 'car_stock_number', $car_stock_number_args );
				$car_stock_number = '';
				if ( ! is_wp_error( $terms ) && isset( $terms[0]->name ) && ! empty( $terms[0]->name ) ) {
					$car_stock_number = $terms[0]->name;
				}
				echo esc_html( $car_stock_number );
				break;

			case 'car_vin_number':
				$car_vin_number_args = array(
					'orderby' => 'name',
					'order'   => 'ASC',
					'fields'  => 'all',
				);

				$carvinnumber   = wp_get_post_terms( $post_id, 'car_vin_number', $car_vin_number_args );
				$car_vin_number = '';

				if ( ! is_wp_error( $carvinnumber ) && isset( $carvinnumber[0]->name ) && ! empty( $carvinnumber[0]->name ) ) {
					$car_vin_number = $carvinnumber[0]->name;
				}
				echo esc_html( $car_vin_number );
				break;

			case 'featured':
				if ( 'cars' === (string) get_post_type() ) {

					$feature = get_post_meta( $post_id, 'featured', true );
					$class   = 'dashicons dashicons-star-empty';

					if ( 1 === (int) $feature ) {
						$class = 'dashicons dashicons-star-filled';
					}

					$featured_url = add_query_arg(
						array(
							'action'   => 'cdhl_do_featured',
							'post_id'  => $post_id,
							'featured' => ( $feature ) ? 'yes' : 'no',
						),
						admin_url( 'admin-ajax.php' )
					);
					$featured_url = wp_nonce_url( $featured_url, 'cdhl-feature-car' );

					echo '<a href="' . esc_url( $featured_url ) . '" aria-label="' . esc_attr__( 'Toggle featured', 'cardealer-helper' ) . '">';
					?>
					<span style="cursor: pointer;" class="<?php echo esc_attr( $class ); ?> do_featured" data-id="<?php echo esc_attr( $post_id ); ?>" data-feature="<?php echo esc_attr( $feature ); ?>"></span>
					<?php
					echo '</a>';
				}
				break;

			case 'auto_trader':
				$dealer = get_post_meta( $post_id, 'auto_trader', true );
				if ( isset( $dealer ) && ! empty( $dealer ) && 'yes' === (string) $dealer ) {
					echo esc_html( gmdate( 'm/d/Y H:i:s', strtotime( get_post_meta( $post_id, 'auto_export_date', true ) ) ) );
				} else {
					echo '-';
				}
				break;

			case 'car_com':
				$dealer = get_post_meta( $post_id, 'cars_com', true );
				if ( isset( $dealer ) && ! empty( $dealer ) && 'yes' === (string) $dealer ) {
					echo esc_html( gmdate( 'm/d/Y H:i:s', strtotime( get_post_meta( $post_id, 'cars_com_export_date', true ) ) ) );
				} else {
					echo '-';
				}
				break;

			case 'pdf':
				cdhl_cars_pdf_meta_box( $post_id );
				break;

		}

	}
	add_filter( 'manage_posts_custom_column', 'cdhl_cpt_cars_custom_columns', 10, 2 );
}

/****** Include all taxonomies into admin car search starts */

if ( ! function_exists( 'cdhl_search_where' ) ) {
	/**
	 * Where condition
	 *
	 * @param string $where .
	 */
	function cdhl_search_where( $where ) {
		global $wpdb, $current_screen, $pagenow;
		if ( is_admin() && 'edit.php' === $pagenow && isset( $_GET['s'] ) && '' !== $_GET['s'] && isset( $current_screen->post_type ) && 'cars' === (string) $current_screen->post_type ) {
			if ( isset( $_GET['post_status'] ) && 'all' !== $_GET['post_status'] ) {

				$post_status = sanitize_text_field( wp_unslash( $_GET['post_status'] ) );

				$where .= "OR (t.name LIKE '%" . get_search_query() . "%' AND {$wpdb->posts}.post_status = '" . $post_status . "')";
			} else {
				$where .= "OR (t.name LIKE '%" . get_search_query() . "%' AND ( {$wpdb->posts}.post_status = 'publish' OR {$wpdb->posts}.post_status = 'acf-disabled' OR {$wpdb->posts}.post_status = 'future' OR {$wpdb->posts}.post_status = 'draft' OR {$wpdb->posts}.post_status = 'pending' OR {$wpdb->posts}.post_status = 'private' ))";
			}
		}
		return $where;
	}
}

if ( ! function_exists( 'cdhl_search_join' ) ) {
	/**
	 * Join
	 *
	 * @param string $join .
	 */
	function cdhl_search_join( $join ) {
		global $wpdb, $current_screen, $pagenow;
		if ( is_admin() && 'edit.php' === $pagenow && isset( $_GET['s'] ) && '' !== $_GET['s'] && isset( $current_screen->post_type ) && 'cars' === (string) $current_screen->post_type ) {
			$join .= "LEFT JOIN {$wpdb->term_relationships} tr ON {$wpdb->posts}.ID = tr.object_id INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id=tr.term_taxonomy_id INNER JOIN {$wpdb->terms} t ON t.term_id = tt.term_id";
		}
		return $join;
	}
}

if ( ! function_exists( 'cdhl_search_groupby' ) ) {
	/**
	 * Group by
	 *
	 * @param string $groupby .
	 */
	function cdhl_search_groupby( $groupby ) {
		global $wpdb, $current_screen, $pagenow;
		if ( is_admin() && 'edit.php' === $pagenow && isset( $_GET['s'] ) && '' !== $_GET['s'] && isset( $current_screen->post_type ) && 'cars' === (string) $current_screen->post_type ) {
			// we need to group on post ID.
			$groupby_id = "{$wpdb->posts}.ID";
			if ( ! is_search() || strpos( $groupby, $groupby_id ) !== false ) {
				return $groupby;
			}

			// groupby was empty, use ours.
			if ( ! strlen( trim( $groupby ) ) ) {
				return $groupby_id;
			}

			// wasn't empty, append ours.
			return $groupby . ', ' . $groupby_id;
		}
		return $groupby;
	}
}

add_filter( 'posts_where', 'cdhl_search_where' );
add_filter( 'posts_join', 'cdhl_search_join' );
add_filter( 'posts_groupby', 'cdhl_search_groupby' );
/****** Include all taxonomies into admin car search ends */


add_action( 'before_delete_post', 'cdhl_delete_vin' );
if ( ! function_exists( 'cdhl_delete_vin' ) ) {
	/**
	 * Delete vin
	 *
	 * @param string $postid .
	 */
	function cdhl_delete_vin( $postid ) {
		global $post_type;
		if ( 'cars' !== $post_type ) {
			return;
		} else {
			$vin = wp_get_post_terms( $postid, 'car_vin_number' );
			if ( isset( $vin[0]->term_id ) && ! empty( $vin[0]->term_id ) ) {
				wp_delete_term( $vin[0]->term_id, 'car_vin_number' );
			}
		}
	}
}

if ( ! function_exists( 'cdhl_cars_pdf_meta_box' ) ) {
	/**
	 * Car pdf meta box
	 *
	 * @param string $post_id .
	 */
	function cdhl_cars_pdf_meta_box( $post_id ) {
		global $post;
		?>
		<div id="pdf_section-<?php echo esc_attr( $post_id ); ?>" class="pdf_section admin-column-pdf-actions">
			<div class="download" id="download-<?php echo esc_attr( $post_id ); ?>">
				<label for="casr_pdf_styles"><?php esc_html_e( 'Choose Template', 'cardealer-helper' ); ?>:</label>
				<?php
				if ( function_exists( 'have_rows' ) ) {
					if ( have_rows( 'html_templates', 'option' ) ) {
						?>
						<select class="casr_pdf_styles" data-id="<?php echo esc_attr( $post->ID ); ?>" name='casr_pdf_styles' id='casr_pdf_styles'>
							<?php
							while ( have_rows( 'html_templates', 'option' ) ) :
								the_row();
								$templates_title = get_sub_field( 'templates_title' );
								?>
								<option value="<?php echo esc_attr( $templates_title ); ?>"><?php echo esc_html( $templates_title ); ?></option>
							<?php endwhile; ?>
						</select>
						<br /><br />
						<a id="<?php echo esc_attr( $post->ID ); ?>" data-post_id="<?php echo esc_attr( $post->ID ); ?>" class="download-pdf button button-primary" href="javascript:void(0)"><?php esc_html_e( 'Generate', 'cardealer-helper' ); ?></a><span class="spinner"></span>
						<?php
						$pdf_file     = get_field( 'pdf_file', $post_id );
						$pdf_url      = false;
						$pdf_dl_style = 'display:none;';


						// Fallback for pdf_file returing as attachment ID.
						if ( ! is_array( $pdf_file ) && is_string( $pdf_file ) ) {
							$pdf_file = acf_get_attachment( (int) $pdf_file );
						}

						if ( $pdf_file ) {
							$pdf_file_path = get_attached_file( $pdf_file['ID'] );
							if ( isset( $pdf_file['url'] ) && ! empty( $pdf_file['url'] ) && file_exists( $pdf_file_path ) ) {
								$pdf_url      = $pdf_file['url'];
								$pdf_dl_style = 'display:block;';
							}
						}
						?>
						<div class="downloadlink" id="downloadlink-<?php echo esc_attr( $post_id ); ?>" style="<?php echo esc_attr( $pdf_dl_style ); ?>">
							<?php
							if ( $pdf_url ) {
								?>
								<a class="button button-primary" href="<?php echo esc_url( $pdf_file['url'] ); ?>" target="_blank" download=""><?php echo esc_html__( 'Download PDF', 'cardealer-helper' ); ?></a>
								<?php
							}
							?>
						</div>
						<?php
					}
				}
				?>
			</div><!-- .download -->
		</div>
		<?php
	}
}
add_action( 'wp_ajax_cdhl_generate_pdf', 'cdhl_generate_pdf' );
add_action( 'wp_ajax_nopriv_cdhl_generate_pdf', 'cdhl_generate_pdf' );
if ( ! function_exists( 'cdhl_generate_pdf' ) ) {
	/**
	 * Function cdhl_generate_pdf.
	 */
	function cdhl_generate_pdf() {
		// require_once CDHL_PATH . 'includes/pdf_generator/do-pdf.php';
		try {
			$url = require_once CDHL_PATH . 'includes/pdf_generator/do-pdf.php';
			$result = array(
				'status' => true,
				'msg'    => esc_html__( 'PDF generated successfully.', 'cardealer' ),
				'url'    => $url,
			);
		} catch (Exception $e) {
			$result = array(
				'status' => false,
				'msg'    => sprintf(
					/* translators: exception message */
					esc_html__( 'Something went wrong. Caught exception: %s', 'cardealer' ),
					$e->getMessage()
				)
			);
		}
		wp_send_json( $result );
	}
}

if ( ! function_exists( 'cdhl_set_final_price' ) ) {
	/**
	 * Set cars final price based on regualer and sale price
	 *
	 * @param string $post_id .
	 */
	function cdhl_set_final_price( $post_id ) {

		// Set final price.
		if ( 'cars' === (string) get_post_type( $post_id ) && function_exists( 'update_field' ) ) {
			$final_price = 0;
			$sale_price  = get_post_meta( $post_id, 'sale_price', true );

			if ( $sale_price ) {
				$final_price = $sale_price;
			} else {
				$regular_price = get_post_meta( $post_id, 'regular_price', true );
				if ( $regular_price ) {
					$final_price = $regular_price;
				}
			}
			update_field( 'final_price', $final_price, $post_id );
		}

		// Flush rewrite rules on post save.
		global $post, $car_dealer_options;

		if ( isset( $car_dealer_options['cars_inventory_page'] ) && isset( $post->ID ) ) {
			$lang_page = cdhl_get_language_page_id( $car_dealer_options['cars_inventory_page'] );
			if ( (int) $lang_page === (int) $post->ID ) {
				if ( ! get_transient( 'cardealer_flush_permalink' ) ) {
					set_transient( 'cardealer_flush_permalink', 'yes' );
				}
			}
		}
	}
}
add_action( 'save_post', 'cdhl_set_final_price' );

/**
 * Filters the taxonomy parent drop-down on the Edit "Features & Options" taxonomy page.
 *
 * @param array  $args     An array of taxonomy parent drop-down arguments.
 * @param string $taxonomy The taxonomy slug.
 */
function cdhl_taxonomy_car_features_options_limit_parents( $args, $taxonomy ) {

	if ( 'car_features_options' !== $taxonomy ) {
		return $args; // no change.
	}

	// Set depth.
	$args['depth'] = '1';

	return $args;
}
add_filter( 'taxonomy_parent_dropdown_args', 'cdhl_taxonomy_car_features_options_limit_parents', 10, 2 );

/**
 * Filters ACF taxonomy query arguments.
 *
 * @param array $args    An array of taxonomy parent drop-down arguments.
 * @param array $field   An array of ACF field.
 * @return array
 */
function cdhl_acf_taxonomy_car_features_options_limit_parents( $args, $field ) {

	if ( 'car_features_options' !== $args['taxonomy'] ) {
		return $args; // no change.
	}

	$args['parent'] = '0';

	return $args;
}
add_filter( 'acf/fields/taxonomy/query/key=field_588f17606f58c', 'cdhl_acf_taxonomy_car_features_options_limit_parents', 10, 3 );

/**
 * Car Condition columns
 *
 * @param string[] $columns  List of columns.
 */
function cdhl_edit_column_car_condition( $columns ) {

	$new_columns = array(
		'cb'          => $columns['cb'],
		'name'        => $columns['name'],
		'label_color' => esc_html__( 'Label Color', 'cardealer-helper' ),
	);

	unset( $columns['cb'] );
	unset( $columns['name'] );

	return array_merge( $new_columns, $columns );
}
add_filter( 'manage_edit-car_condition_columns', 'cdhl_edit_column_car_condition' );

function cdhl_mane_custom_column_car_condition( $string, $column_name, $term_id ) {

	switch ( $column_name ) {
		case 'label_color':
			$color = get_term_meta( $term_id, 'label_color', true );
			if ( $color ) {
				$string = '<span style="background-color:' . $color . ';width:30px;height:30px;display:inline-block;border-radius:3px;"></span>';
			}
			break;
		default:
			break;
	}

	return $string;
}
add_filter( 'manage_car_condition_custom_column', 'cdhl_mane_custom_column_car_condition', 10, 3 );

/**
 * Car Make columns
 *
 * @param string[] $columns  List of columns.
 */
function cdhl_edit_column_car_make( $columns ) {

	$new_columns = array(
		'cb'        => $columns['cb'],
		'column-thumb' => esc_html__( 'Logo', 'cardealer-helper' ),
	);

	unset( $columns['cb'] );

	return array_merge( $new_columns, $columns );
}
add_filter( 'manage_edit-car_make_columns', 'cdhl_edit_column_car_make' );

function cdhl_mane_custom_column_car_make( $string, $column_name, $term_id ) {

	switch ( $column_name ) {
		case 'column-thumb':
			$vehicle_logo = get_term_meta( $term_id, 'vehicle_logo', true );
			if ( $vehicle_logo ) {
				$img_url = wp_get_attachment_image_src( $vehicle_logo, 'thumbnail' );
				echo wp_get_attachment_image( $vehicle_logo, 'thumbnail' );
				// $string = '<span style="background-color:' . $color . ';width:30px;height:30px;display:inline-block;border-radius:3px;"></span>';
			}
			break;
		default:
			break;
	}

	return $string;
}
add_filter( 'manage_car_make_custom_column', 'cdhl_mane_custom_column_car_make', 10, 3 );
