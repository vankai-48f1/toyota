<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Filter functions call.
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper/functions
 * @version 1.0.0
 */

if ( ! function_exists( 'cdhl_get_taxonomy_terms_by_condition' ) ) {
	/**
	 * Get taxonomy terns by filter.
	 *
	 * @param array $args aguments.
	 */
	function cdhl_get_taxonomy_terms_by_condition( $args ) {
		global $wpdb;
		$tax_terms     = array();
		$taxonomy      = $args['term_tax']; // Taxonomy term to get.
		$condition     = "'" . implode( "','", $args['condition']['value'] ) . "'"; // convert into string.
		$condition_tax = $args['condition']['tax_name']; // taxonomy condition.

		$term      = $wpdb->prefix . 'terms';
		$relations = $wpdb->prefix . 'term_relationships';
		$taxo      = $wpdb->prefix . 'term_taxonomy';
		$sql       = "SELECT DISTINCT($relations.object_id)
					FROM $relations
					JOIN $taxo
							ON $taxo.term_taxonomy_id = $relations.term_taxonomy_id
					JOIN $term
							ON $term.term_id = $taxo.term_id
					WHERE $term.name IN ($condition) and $taxo.taxonomy = '$condition_tax'";

		$postids    = $wpdb->get_results( $sql, ARRAY_A );
		$term_array = array(); // make array to avoid duplicate values.
		if ( ! empty( $postids ) ) {
			$tax_terms = array();
			foreach ( $postids as $id ) {
				$term_obj = wp_get_post_terms( $id['object_id'], $taxonomy );
				if ( ! empty( $term_obj ) ) {
					if ( ! in_array( $term_obj[0]->name, $term_array ) ) { // check for duplicate.
						$tax_terms[] = array(
							'name' => $term_obj[0]->name,
							'slug' => $term_obj[0]->slug,
						);
					}
					$term_array[] = $term_obj[0]->name;
				}
			}
		}
		return $tax_terms;
	}
}

if ( ! function_exists( 'cdhl_get_related_taxonomy_terms' ) ) {
	/**
	 * Get telated taxonomy.
	 *
	 * @param array $args Arguments.
	 */
	function cdhl_get_related_taxonomy_terms( $args ) {
		global $wpdb;
		$tax_terms    = array();
		$taxonomy     = $args['term_tax']; // Taxonomy term to get.
		$posts        = $wpdb->prefix . 'posts';
		$term         = $wpdb->prefix . 'terms';
		$relations    = $wpdb->prefix . 'term_relationships';
		$taxo         = $wpdb->prefix . 'term_taxonomy';
		$translations = $wpdb->prefix . 'icl_translations'; // WPML.
		$extra_query  = '';

		// Sub query to get related post ids of requested taxonomy terms.
		if ( ! empty( $args['query']['condition'] ) ) {
			$condition     = "'" . implode( "','", $args['query']['condition']['value'] ) . "'"; // convert into string.
			$condition_tax = $args['query']['condition']['tax_name']; // taxonomy condition.
			$extra_query  .= " AND $term.slug IN ($condition) and $taxo.taxonomy = '$condition_tax'";
		}
		if ( isset( $args['query']['related_tax'] ) ) {
			// Make sub queries to add taxonomies in condition.
			$index            = 1;
			$closing_brackets = '';
			foreach ( $args['query']['related_tax'] as $tax_name => $tax_value ) {
				if ( 1 === (int) $index ) {
					$extra_query .= " AND
						$posts.ID IN ";
				}
				if ( count( $args['query']['related_tax'] ) === (int) $index ) {
					$sub_query = "(SELECT $relations.object_id
						FROM $relations
						JOIN $taxo
								ON $taxo.term_taxonomy_id = $relations.term_taxonomy_id
						JOIN $term
								ON $term.term_id = $taxo.term_id
						WHERE $term.slug = '$tax_value' and $taxo.taxonomy = '$tax_name'";
				} else {
					$sub_query = "(SELECT $relations.object_id
						FROM $relations
						JOIN $taxo
								ON $taxo.term_taxonomy_id = $relations.term_taxonomy_id
						JOIN $term
								ON $term.term_id = $taxo.term_id
						WHERE $term.slug = '$tax_value' and $taxo.taxonomy = '$tax_name'
						AND
							$posts.ID IN ";
				}
				$extra_query      .= "$sub_query";
				$closing_brackets .= ')';
				$index++;
			}
			$extra_query .= $closing_brackets;
		}

		// Query to get postids based on taxonomy conditions.
		if ( cardealer_is_wpml_active() ) {
			$get_post_ids_query = "SELECT DISTINCT($relations.object_id)
			FROM $relations
			JOIN $taxo
				ON $taxo.term_taxonomy_id = $relations.term_taxonomy_id
			JOIN $term
				ON $term.term_id = $taxo.term_id
			JOIN $posts
				ON $posts.ID = $relations.object_id
			JOIN $translations
				ON $posts.ID = $translations.element_id
			AND $translations.language_code = '" . ICL_LANGUAGE_CODE . "'
			AND $posts.post_status IN ('publish', 'acf-disabled')
			AND $posts.post_type='cars'" . $extra_query;
		} else {
			$get_post_ids_query = "SELECT DISTINCT($relations.object_id)
			FROM $relations
			JOIN $taxo
					ON $taxo.term_taxonomy_id = $relations.term_taxonomy_id
			JOIN $term
					ON $term.term_id = $taxo.term_id
			JOIN $posts
					ON $posts.ID = $relations.object_id
					AND $posts.post_status IN ('publish', 'acf-disabled')
					AND $posts.post_type='cars'" . $extra_query;
		}

		$vehicles_matched       = $wpdb->get_results( $get_post_ids_query );
		$total_matched_vehicles = ( ! empty( $vehicles_matched ) ) ? count( $vehicles_matched ) : 0;

		// get terms taxonomy id based on post Ids.
		// will be used to get terms.
		$term_taxonomy_id_query = "SELECT DISTINCT($taxo.term_taxonomy_id)
					FROM $taxo
					JOIN $relations
							ON $taxo.term_taxonomy_id = $relations.term_taxonomy_id
					WHERE $relations.object_id IN ($get_post_ids_query)";

		// Query to get terms.
		$get_terms_query = "SELECT $term.name, $term.slug
					FROM $term
					JOIN $taxo
						ON $taxo.term_id = $term.term_id
					WHERE $taxo.term_taxonomy_id IN ($term_taxonomy_id_query)
					AND $taxo.taxonomy = '$taxonomy'
					ORDER BY $term.name";
		$terms           = $wpdb->get_results( $get_terms_query, ARRAY_A );
		$tax_terms       = array();
		if ( ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$tax_terms[] = array(
					'name' => $term['name'],
					'slug' => $term['slug'],
				);
			}
		}
		return array(
			'tax_terms'        => $tax_terms,
			'vehicles_matched' => $total_matched_vehicles,
		);
	}
}

if ( ! function_exists( 'cdhl_get_car_attrs_by_condition' ) ) {
	/**
	 * Get car attribute by filter.
	 *
	 * @param string $conditions Condition.
	 * @param string $attr_to_get attribut to get.
	 */
	function cdhl_get_car_attrs_by_condition( $conditions, $attr_to_get = '' ) {
		$attr_array = array();
		if ( empty( $attr_to_get ) ) {
			/**
			 * Filters the title display on the compare vehicle pop-up.
			 *
			 * @since 1.0
			 * @param           $vehicle_conditions Array of vehicle conditions
			 * @visible         true
			 */
			$vehicle_conditions = array(
				'car_year'  => esc_html__( 'Year', 'cardealer-helper' ),
				'car_make'  => esc_html__( 'Make', 'cardealer-helper' ),
				'car_model' => esc_html__( 'Model', 'cardealer-helper' ),
			);
			$attr_to_get        = apply_filters( 'car_search_attrs', $vehicle_conditions );
		}

		foreach ( $attr_to_get as $tax => $label ) {
			$args          = array(
				'term_tax' => $tax, // Taxonomy whose terms to get.
				'query'    => array(
					'condition' => array(
						'tax_name' => 'car_condition',
						'value'    => $conditions,
					),
				),
			);
			$matched_terms = cdhl_get_related_taxonomy_terms( $args );
			if ( ! empty( $matched_terms ) ) {
				$label = cardealer_get_field_label_with_tax_key( $tax );
				$attr_array[] = array(
					'tax_label'        => $label,
					'taxonomy'         => $tax,
					'tax_terms'        => $matched_terms['tax_terms'],
					'vehicles_matched' => $matched_terms['vehicles_matched'],
				);
			}
		}
		return $attr_array;
	}
}

add_action( 'wp_ajax_cdhl_get_search_attr', 'cdhl_get_car_search_attr' );
add_action( 'wp_ajax_nopriv_cdhl_get_search_attr', 'cdhl_get_car_search_attr' );

if ( ! function_exists( 'cdhl_get_car_search_attr' ) ) {
	/**
	 * Get car seach attribute.
	 */
	function cdhl_get_car_search_attr() {
		$status = false;
		$msg    = esc_html__( 'Something went wrong!', 'cardealer-helper' );
		if ( isset( $_POST['action'] ) && 'cdhl_get_search_attr' === $_POST['action'] ) {
			$attr_array  = array();
			$search_term = sanitize_text_field( wp_unslash( $_POST['term_tax'] ) );
			$term_value  = sanitize_text_field( wp_unslash( $_POST['term_value'] ) );
			$condition   = sanitize_text_field( wp_unslash( $_POST['condition'] ) );
			$term_tax    = array_map( 'strip_tags', wp_unslash( $_POST['tax_data'] ) );

			$car_year  = cardealer_get_field_label_with_tax_key( 'car_year' );
			$car_make  = cardealer_get_field_label_with_tax_key( 'car_make' );
			$car_model = cardealer_get_field_label_with_tax_key( 'car_model' );

			$attr_to_get = array(
				'car_year'  => ( $car_year ) ? $car_year : esc_html__( 'Year', 'cardealer-helper' ),
				'car_make'  => ( $car_make ) ? $car_make : esc_html__( 'Make', 'cardealer-helper' ),
				'car_model' => ( $car_model ) ? $car_model : esc_html__( 'Model', 'cardealer-helper' ),
			);

			// remove triggering attributes if not empty. i.e. if car_year select is triggered and not empty, then remove it from search.
			if ( ! empty( $term_value ) ) {
				unset( $attr_to_get[ $search_term ] );
			}
			// if empty attr got, then remove it from condition.
			foreach ( $term_tax as $tax => $tax_term ) {
				if ( empty( $tax_term ) ) {
					unset( $term_tax[ $tax ] );
				}
			}
			if ( 'all_vehicles' !== $condition ) {
				switch ( $condition ) {
					case 'new':
						$conditions = array( 'New', 'NEW', 'new', 'N', 'n' );
						break;
					case 'used':
						$conditions = array( 'used', 'USED', 'Used', 'U', 'u' );
						break;
					case 'certified':
						$conditions = array( 'certified', 'Certified', 'CERTIFIED', 'C', 'c' );
						break;
					default:
						$conditions = array( $condition );
						break;
				}
				$condition = array(
					'tax_name' => 'car_condition',
					'value'    => $conditions,
				);
			} else {
				$condition = array();
			}
			foreach ( $attr_to_get as $tax => $label ) {
				$args          = array(
					'term_tax' => $tax, // Taxonomy whose terms to get.
					'query'    => array(
						'condition'   => $condition,
						'related_tax' => $term_tax,
					),
				);
				$matched_terms = cdhl_get_related_taxonomy_terms( $args );
				if ( ! empty( $matched_terms ) ) {
					$attr_array[] = array(
						'tax_label'        => $label,
						'taxonomy'         => $tax,
						'tax_terms'        => $matched_terms['tax_terms'],
						'vehicles_matched' => $matched_terms['vehicles_matched'],
					);
				}
			}
			$msg    = esc_html__( 'Successfully received data!', 'cardealer-helper' );
			$status = true;
		} else {
			$msg = esc_html__( 'Invalid action!', 'cardealer-helper' );
		}

		$response_arr = array(
			'status'     => $status,
			'message'    => $msg,
			'attr_array' => $attr_array,
		);
		echo json_encode( $response_arr );
		die;
	}
}

if ( ! function_exists( 'cdhl_get_term_data_by_taxonomy' ) ) {
	/**
	 * Get terms data by taxonomy.
	 *
	 * @param string $taxonomy taxonam of cars.
	 * @param string $return_type return type of cars.
	 */
	function cdhl_get_term_data_by_taxonomy( $taxonomy = '', $return_type = 'term_data' ) {
		if ( empty( $taxonomy ) ) {
			return;
		}
		$taxonomy_terms  = get_terms( $taxonomy );
		$term_data_array = array(); // Detailed info.
		$termarray       = array(); // Texonomy terms array.
		if ( ! empty( $taxonomy_terms ) && ! is_wp_error( $taxonomy_terms ) ) {
			foreach ( $taxonomy_terms as $term ) {
				$model_img_id = get_term_meta( $term->term_id, 'vehicle_logo', true );
				$img_url      = ( ! empty( $model_img_id ) ) ? wp_get_attachment_image_src( $model_img_id, 'thumbnail' ) : cardealer_get_carplaceholder( '', 'url' );
				// Detailed term info.
				$term_data_array[ $term->slug ] = array(
					'id'       => $term->term_id,
					'slug'     => $term->slug,
					'name'     => $term->name,
					'posts'    => $term->count,
					'logo_img' => $img_url,
				);
				// Texonomy terms array.
				$termarray[ $term->name ] = $term->slug;
			}
		}
		if ( 'term_array' === (string) $return_type ) {
			return $termarray;
		}
		return $term_data_array;
	}
}

if ( ! function_exists( 'cdhl_get_condition_tab_vehicles' ) ) {
	/**
	 * Get condition tab vehicles.
	 *
	 * @param string $vehicle_condition vehicle condition.
	 * @param string $makes make of cars.
	 * @param string $number_of_item cars.
	 * @param string $vehicle_category category of cars.
	 * @param bool   $hide_sold_vehicles hide sold of cars.
	 */
	function cdhl_get_condition_tab_vehicles( $vehicle_condition = '', $makes = '', $number_of_item = '-1', $vehicle_category = '', $hide_sold_vehicles = false ) {
		if ( empty( $vehicle_condition ) ) {
			return;
		}
		$makes = trim( $makes );
		$args  = array(
			'post_type'      => 'cars',
			'posts_status'   => 'publish',
			'posts_per_page' => $number_of_item,

		);

		// meta_query for sold/unsold vehicles.
		$car_status_query = array();
		if ( isset( $hide_sold_vehicles ) && ( true === (bool) $hide_sold_vehicles ) ) {
			$args['meta_query'] = array(
				array(
					'key'     => 'car_status',
					'value'   => 'sold',
					'compare' => '!=',
				),
			);
		}
		if ( ! empty( $makes ) ) {
			$makes_array = explode( ',', $makes );
			if ( is_array( $makes_array ) && ! empty( $makes_array ) ) {
				// Make wise filter.
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'car_make',
						'field'    => 'slug',
						'terms'    => $makes_array,
					),
				);
			}
		}

		$vehicle_category = trim( $vehicle_category );
		if ( ! empty( $vehicle_category ) ) {
			$vehicle_cat_array = array(
				'taxonomy' => 'vehicle_cat',
				'field'    => 'slug',
				'terms'    => $vehicle_category,
			);
			if ( isset( $args['tax_query'] ) ) {
				$car_make_array    = $args['tax_query'];
				$args['tax_query'] = array(
					'relation' => 'AND',
					$vehicle_cat_array,
					$car_make_array,
				);
			} else {
				$args['tax_query'] = array(
					$vehicle_cat_array,
				);
			}
		}

		$vehicle_condition = trim( $vehicle_condition );
		if ( ! empty( $vehicle_condition ) ) {
			$vehicle_condition_array = array(
				'taxonomy' => 'car_condition',
				'field'    => 'slug',
				'terms'    => strtolower( $vehicle_condition ),
			);
			if ( isset( $args['tax_query'] ) ) {
				$vehicle_make_cat_array = $args['tax_query'];
				$args['tax_query']      = array(
					'relation' => 'AND',
					$vehicle_make_cat_array,
					$vehicle_condition_array,
				);
			} else {
				$args['tax_query'] = array(
					$vehicle_condition_array,
				);
			}
		}

		$loop = new WP_Query( $args );
		// Bail if no posts found.
		if ( ! $loop->have_posts() ) {
			return;
		} else {
			return $loop;
		}

	}
}
