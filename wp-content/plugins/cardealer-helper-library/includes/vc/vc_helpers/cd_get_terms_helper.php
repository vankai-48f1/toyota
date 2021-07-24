<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Cardealer terms helper functions.
 *
 * @package car-dealer-helper/functions
 */

/**
 * Get terms.
 *
 * @param array $args .
 * @param array $args2 .
 */
function cdhl_get_terms( $args = array(), $args2 = '' ) {
	$return_data = array();

	if ( ! empty( $args2 ) ) {
		$result = get_terms( $args, $args2 );
	} else {
		$args['hide_empty'] = true;
		$result             = get_terms( $args );
	}

	if ( is_wp_error( $result ) ) {
		return $return_data;
	}

	if ( ! is_array( $result ) || empty( $result ) ) {
		return $return_data;
	}

	foreach ( $result as $term_data ) {
		if ( is_object( $term_data ) && isset( $term_data->name, $term_data->term_id ) ) {
			$return_data[ $term_data->name ] = $term_data->slug;
		}
	}
	return $return_data;
}

/**
 * Get Taxomony of Cars Posttype.
 */
function cdhl_get_cars_taxonomy() {
	$taxonomies = get_object_taxonomies( 'cars' );

	$vehicle_cat_key = array_search( 'vehicle_cat', $taxonomies, true );
	if ( false !== $vehicle_cat_key ) {
		unset( $taxonomies[ $vehicle_cat_key ] );
	}

	$car_features_options_key = array_search( 'car_features_options', $taxonomies, true );
	if ( false !== $car_features_options_key ) {
		unset( $taxonomies[ $car_features_options_key ] );
	}

	$car_vehicle_review_stamps_key = array_search( 'car_vehicle_review_stamps', $taxonomies, true );
	if ( false !== $car_vehicle_review_stamps_key ) {
		unset( $taxonomies[ $car_vehicle_review_stamps_key ] );
	}
	$taxonomy_array = array();
	foreach ( $taxonomies as $taxonomy ) {
		$tax_obj                           = get_taxonomy( $taxonomy );
		$taxonomy_array[ $tax_obj->labels->singular_name ] = $taxonomy;
	}
	return $taxonomy_array;
}
