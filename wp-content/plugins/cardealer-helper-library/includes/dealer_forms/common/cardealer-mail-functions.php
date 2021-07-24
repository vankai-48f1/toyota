<?php
/**
 * Mail functions
 *
 * @package car-dealer-helper
 */

if ( ! function_exists( 'cdhl_get_text_mail_body' ) ) {
	/**
	 * Function For Text Mail Body
	 *
	 * @param int $car_id car id.
	 */
	function cdhl_get_text_mail_body( $car_id ) {
		$product_plain       = '';
		$wpml_lang_sanitized = '';
		$sale_price          = ( get_field( 'sale_price', $car_id ) ) ? get_field( 'sale_price', $car_id ) : '—';
		$product_plain       .= esc_html__( 'Sale Price: ', 'cardealer-helper' ) . $sale_price;
		$regular_price       = ( get_field( 'regular_price', $car_id ) ) ? get_field( 'regular_price', $car_id ) : '—';
		$product_plain       .= PHP_EOL . esc_html__( 'Regular Price : ', 'cardealer-helper' ) . $regular_price;

		$taxos = array(
			'car_year',
			'car_make',
			'car_model',
			'car_body_style',
			'car_mileage',
			'car_fuel_economy',
			'car_transmission',
			'car_condition',
			'car_drivetrain',
			'car_engine',
			'car_exterior_color',
			'car_interior_color',
			'car_stock_number',
			'car_vin_number',
		);

		if ( class_exists( 'WPCF7_Submission' ) ) {
			$submission = WPCF7_Submission::get_instance();
			if ( is_a( $submission, 'WPCF7_Submission' ) ) {
				$posted_data = $submission->get_posted_data();
				if ( ( wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) && isset( $posted_data['cf7_wpml_lang'] ) && '' !== $posted_data['cf7_wpml_lang'] ) {
					$wpml_lang_sanitized = sanitize_text_field( wp_unslash( $posted_data['cf7_wpml_lang'] ) );
				}
			}
		}

		foreach ( $taxos as $tax ) {
			$tax_terms  = wp_get_post_terms( $car_id, $tax );
			$tax_term_v = ( ! is_wp_error( $tax_terms ) && ! empty( $tax_terms ) ) ? $tax_terms[0]->name : '—';
			$label      = cardealer_get_field_label_with_tax_key( $tax );

			if ( class_exists( 'SitePress' ) && $wpml_lang_sanitized ) {
				$label = apply_filters( 'wpml_translate_single_string', $label, "WordPress", "taxonomy singular name: " . $label , $wpml_lang_sanitized );
			}

			$product_plain .= PHP_EOL . $label . ': ' . $tax_term_v;
		}

		$taxonomies_raw = get_object_taxonomies( 'cars' );
		foreach ( $taxonomies_raw as $new_tax ) {
			$new_tax_obj = get_taxonomy( $new_tax );
			if( isset($new_tax_obj->include_in_filters) && $new_tax_obj->include_in_filters == true ) {
				$addition_attr_tax  = wp_get_post_terms( $car_id, $new_tax_obj->name );
				$addition_attr      = ( ! is_wp_error( $addition_attr_tax ) && ! empty( $addition_attr_tax ) ) ? $addition_attr_tax[0]->name : '&mdash;';

				$new_tax_label = $new_tax_obj->labels->singular_name;
				if ( class_exists( 'SitePress' ) && $wpml_lang_sanitized ) {
					$new_tax_label = apply_filters( 'wpml_translate_single_string', $new_tax_label, "WordPress", "taxonomy singular name: " . $new_tax_label , $wpml_lang_sanitized );
				}

				$product_plain .= PHP_EOL . esc_html( $new_tax_label ) . ': ' . $addition_attr;
			}
		}

		return $product_plain;
	}
}

if ( ! function_exists( 'cdhl_get_html_mail_body' ) ) {
	/**
	 * Function For HTML Mail Body
	 *
	 * @param int $car_id car id.
	 */
	function cdhl_get_html_mail_body( $car_id ) {
		$product          = '<table class="compare-list compare-datatable" width="100%" border="1" cellspacing="0" cellpadding="5">';
		$product         .= '<tbody>';
		$product         .= '<tr class="image">';
		$product         .= '<td colspan=2 style="text-align:center">';
		$product         .= cardealer_get_cars_image( 'car_thumbnail', $car_id );
		$product         .= '</td>';
		$product         .= '</tr>';
		$product         .= '<tr class="price">';
		$product         .= '<td>';
		$product         .= esc_html__( 'Vehicle Price', 'cardealer-helper' );
		$product         .= '</td>';
		$product         .= '<td>';
		$sale_price       = ( get_field( 'sale_price', $car_id ) ) ? get_field( 'sale_price', $car_id ) : '&nbsp;';
		$regular_price    = ( get_field( 'regular_price', $car_id ) ) ? get_field( 'regular_price', $car_id ) : '&nbsp;';
		$product         .= '<span>' . esc_html__( 'Sale Price: ', 'cardealer-helper' ) . $sale_price . '</span><span>&nbsp;&nbsp;' . esc_html__( 'Regular Price : ', 'cardealer-helper' ) . $regular_price . '</span>';
		$product         .= '</td>';
		$product         .= '</tr>';

		$taxos = array(
			'car_year',
			'car_make',
			'car_model',
			'car_body_style',
			'car_mileage',
			'car_fuel_economy',
			'car_transmission',
			'car_condition',
			'car_drivetrain',
			'car_engine',
			'car_exterior_color',
			'car_interior_color',
			'car_stock_number',
			'car_vin_number',
		);

		if ( class_exists( 'WPCF7_Submission' ) ) {
			$submission = WPCF7_Submission::get_instance();
			if ( is_a( $submission, 'WPCF7_Submission' ) ) {
				$posted_data = $submission->get_posted_data();
				if ( ( wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) && isset( $posted_data['cf7_wpml_lang'] ) && '' !== $posted_data['cf7_wpml_lang'] ) {
					$wpml_lang_sanitized = sanitize_text_field( wp_unslash( $posted_data['cf7_wpml_lang'] ) );
				}
			}
		}

		foreach ( $taxos as $tax ) {
			$tax_terms = wp_get_post_terms( $car_id, $tax );
			$tax_term_v  = ( ! is_wp_error( $tax_terms ) && ! empty( $tax_terms ) ) ? $tax_terms[0]->name : '—';

			$tax_label = cardealer_get_field_label_with_tax_key( $tax );
			if ( class_exists( 'SitePress' ) && $wpml_lang_sanitized ) {
				$tax_label = apply_filters( 'wpml_translate_single_string', $tax_label, "WordPress", "taxonomy singular name: " . $tax_label , $wpml_lang_sanitized );
			}

			$product .= '<tr class="' . $tax . '">';
			$product .= '<td>';
			$product .= $tax_label;
			$product .= '</td>';
			$product .= '<td>';
			$product .= $tax_term_v;
			$product .= '</td>';
			$product .= '</tr>';
		}

		$taxonomies_raw = get_object_taxonomies( 'cars' );
		foreach ( $taxonomies_raw as $new_tax ) {
			$new_tax_obj = get_taxonomy( $new_tax );
			if( isset( $new_tax_obj->include_in_filters ) && $new_tax_obj->include_in_filters == true ) {
				$addition_attr    = wp_get_post_terms( $car_id, $new_tax_obj->name );

				$new_tax_label = $new_tax_obj->labels->singular_name;
				if ( class_exists( 'SitePress' ) && $wpml_lang_sanitized ) {
					$new_tax_label = apply_filters( 'wpml_translate_single_string', $new_tax_label, "WordPress", "taxonomy singular name: " . $new_tax_label , $wpml_lang_sanitized );
				}

				$product         .= '<tr class="' . esc_attr($new_tax_obj->name) . '">';
				$product         .= '<td>';
				$product         .= $new_tax_label;
				$product         .= '</td>';
				$product         .= '<td>';
				$product         .= ( ! is_wp_error( $addition_attr ) && empty( $addition_attr ) ) ? '&nbsp;' : $addition_attr[0]->name;
				$product         .= '</td>';
				$product         .= '</tr>';
			}
		}

		$product         .= '</tbody>';
		$product         .= '</table>';
		/**
		 * Filters the mail body for vehicle details contents for dealer forms.
		 *
		 * @since 1.0
		 * @param string     $product   HTML string of the mail body.
		 * $param int        $car_id    Vehicle ID.
		 * @visible          true
		 */
		return apply_filters( 'cdhl_get_html_mail_body', $product, $car_id );
	}
}
