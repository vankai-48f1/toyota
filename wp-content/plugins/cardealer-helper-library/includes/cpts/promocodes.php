<?php
/**
 * Register Post Type for promocodes
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

add_action( 'init', 'cdhl_promocodes_cpt', 1 );
if ( ! function_exists( 'cdhl_promocodes_cpt' ) ) {
	/**
	 * Register promocodes cpt
	 */
	function cdhl_promocodes_cpt() {
		$labels = array(
			'name'               => esc_html__( 'Promo Codes', 'cardealer-helper' ),
			'singular_name'      => esc_html__( 'Promo Codes', 'cardealer-helper' ),
			'menu_name'          => esc_html__( 'Promo Codes', 'cardealer-helper' ),
			'name_admin_bar'     => esc_html__( 'Promo Codes', 'cardealer-helper' ),
			'add_new'            => esc_html__( 'Add New', 'cardealer-helper' ),
			'add_new_item'       => esc_html__( 'Add New Promo Codes', 'cardealer-helper' ),
			'new_item'           => esc_html__( 'New Promo Codes', 'cardealer-helper' ),
			'edit_item'          => esc_html__( 'Edit Promo Codes', 'cardealer-helper' ),
			'view_item'          => esc_html__( 'View Promo Codes', 'cardealer-helper' ),
			'all_items'          => esc_html__( 'All Promo Codes', 'cardealer-helper' ),
			'search_items'       => esc_html__( 'Search Promo Codes', 'cardealer-helper' ),
			'parent_item_colon'  => esc_html__( 'Parent promo Codes:', 'cardealer-helper' ),
			'not_found'          => esc_html__( 'No promo Codes found.', 'cardealer-helper' ),
			'not_found_in_trash' => esc_html__( 'No promo Codes found in Trash.', 'cardealer-helper' ),
		);

		$args = array(
			'labels'              => $labels,
			'description'         => esc_html__( 'Description.', 'cardealer-helper' ),
			'public'              => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'query_var'           => true,
			'rewrite'             => array( 'slug' => 'promocodes' ),
			'capability_type'     => 'post',
			'exclude_from_search' => true,
			'has_archive'         => false,
			'hierarchical'        => false,
			'menu_position'       => null,
			'supports'            => array( 'title', 'editor' ),
		);

		register_post_type( 'cars_promocodes', $args );
	}
}

if ( ! function_exists( 'cdhl_cpt_promocode_edit_columns' ) ) {
	/**
	 * Edit columns
	 *
	 * @param string $columns .
	 */
	function cdhl_cpt_promocode_edit_columns( $columns = array() ) {

		$new_fields =
			array_slice(
				$columns,
				0,
				2,
				true
			) +
			array(
				'promocode' => esc_html__( 'Promo Code', 'cardealer-helper' ),
				'status'    => esc_html__( 'Status', 'cardealer-helper' ),
			) +
			array_slice( $columns, 2, count( $columns ) - 1, true );
		return $new_fields;
	}
	add_filter( 'manage_edit-cars_promocodes_columns', 'cdhl_cpt_promocode_edit_columns' );
}

if ( ! function_exists( 'cdhl_cpt_promocodes_custom_columns' ) ) {
	/**
	 * Custom columns
	 *
	 * @param string $column .
	 * @param string $post_id .
	 */
	function cdhl_cpt_promocodes_custom_columns( $column, $post_id ) {

		switch ( $column ) {
			case 'promocode':
				$promo_code = get_post_meta( $post_id, 'promo_code', true );
				if ( isset( $promo_code ) ) {
					echo esc_html( $promo_code );
				}
				break;
			case 'status':
				$status = get_post_meta( $post_id, 'status', true );
				if ( isset( $status ) ) {
					echo esc_html( ucfirst( $status ) );
				}
				break;
		}

	}
	add_filter( 'manage_posts_custom_column', 'cdhl_cpt_promocodes_custom_columns', 10, 2 );
}

/**
 * Check before post save if promocode already exist.
 */
add_filter( 'acf/validate_value/name=promo_code', 'acf_unique_value_field', 10, 4 );
if ( ! function_exists( 'acf_unique_value_field' ) ) {
	/**
	 * Acf unique value
	 *
	 * @param string $valid .
	 * @param string $value .
	 * @param string $field .
	 * @param string $input .
	 */
	function acf_unique_value_field( $valid, $value, $field, $input ) {

		if ( ! $valid || ( ! isset( $_POST['post_ID'] ) && ! isset( $_POST['post_id'] ) ) ) {
			return $valid;
		}

		if ( isset( $_POST['post_ID'] ) ) {
			$post_id = intval( $_POST['post_ID'] );
		} else {
			$post_id = intval( $_POST['post_id'] );
		}

		if ( ! $post_id ) {
			return $valid;
		}

		$post_type  = get_post_type( $post_id );
		$field_name = $field['name'];

		$args = array(
			'post_type'    => $post_type,
			'post_status'  => 'publish, draft, trash',
			'post__not_in' => array( $post_id ),
			'meta_query'   => array(
				array(
					'key'   => $field_name,
					'value' => $value,
				),
			),
		);

		$query = new WP_Query( $args );

		if ( count( $query->posts ) ) {
			return 'Promo code already exist. Please enter a unique ' . $field['label'];
		}

		return true;
	}
}

/**
 * Check and redirect user to promocode offer page
 */
add_action( 'wp_ajax_validate_promocode', 'cardealer_validate_promocode' );
add_action( 'wp_ajax_nopriv_validate_promocode', 'cardealer_validate_promocode' );
if ( ! function_exists( 'cardealer_validate_promocode' ) ) {
	/**
	 * Validate promocode
	 */
	function cardealer_validate_promocode() {

		$result = array(
			'status' => 'error',
			'msg'    => 'Invalid promo code!',
		);

		$errors           = '';
		$promocode_status = 'disable';

		if ( ! isset( $_POST['promocode_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['promocode_nonce'] ), 'cdhl-promocode-form' ) ) {
			$errors = esc_html__( 'Sorry, your nonce did not verify. Refresh Page and try again.', 'cardealer-helper' ) . "\r\n";

		} else {
			$promocode = ( isset( $_POST['promocode'] ) && ! empty( $_POST['promocode'] ) ) ? sanitize_text_field( wp_unslash( $_POST['promocode'] ) ) : '';
			if ( empty( $promocode ) ) {
				$errors = esc_html__( 'Please enter valid promo code', 'cardealer-helper' ) . "\r\n";
			} else {
					$args  = array(
						'post_type'   => 'cars_promocodes',
						'post_status' => 'publish, draft, trash',
						'meta_query'  => array(
							'relation' => 'AND',
							array(
								'key'   => 'promo_code',
								'value' => $promocode,
							),
							array(
								'key'   => 'status',
								'value' => 'enable',
							),
						),
					);
					$query = new WP_Query( $args );
					while ( $query->have_posts() ) :
						$query->the_post();
						$pid              = get_the_ID();
						$promo_code_page  = get_post_meta( $pid, 'promo_code_page', true );
						$promocode_is     = get_post_meta( $pid, 'promo_code', true );
						$promocode_status = get_post_meta( $pid, 'status', true );
						if ( 'custom' !== $promo_code_page ) {
							$reditect_url = $promo_code_page;
						} else {
							$reditect_url = get_post_meta( $pid, 'promo_code_url', true );

						}

						if ( $promocode === $promocode_is && 'enable' === (string) $promocode_status ) {
							$result = array(
								'status' => 'success',
								'url'    => $reditect_url,
							);
							break;
						}
					endwhile;
					wp_reset_postdata();

			}
		}

		if ( '' !== $errors ) {
			$result = array(
				'status' => 'error',
				'msg'    => $errors,
			);
		}
		echo wp_json_encode( $result );
		exit();
	}
}

if ( ! function_exists( 'cdhl_validate_promocode_template' ) ) {
	/**
	 * Validate promocode template
	 */
	function cdhl_validate_promocode_template() {

		if ( is_page_template( 'templates/promocode.php' ) && isset( $_POST['promocode'] ) && ! empty( $_POST['promocode'] ) ) {
			$promocode        = sanitize_text_field( wp_unslash( $_POST['promocode'] ) );
			$promocode_status = 'disable';
			$result           = false;

			$args = array(
				'post_type'   => 'cars_promocodes',
				'post_status' => 'publish, draft, trash',
				'meta_query'  => array(
					'relation' => 'AND',
					array(
						'key'   => 'promo_code',
						'value' => $promocode,
					),
					array(
						'key'   => 'status',
						'value' => 'enable',
					),
				),
			);

			$query = new WP_Query( $args );

			while ( $query->have_posts() ) :
				$query->the_post();
				$pid              = get_the_ID();
				$promocode_is     = get_post_meta( $pid, 'promo_code', true );
				$promocode_status = get_post_meta( $pid, 'status', true );
				if ( $promocode === $promocode_is && 'enable' === (string) $promocode_status ) {
					$result = true;
					break;
				}
			endwhile;
			wp_reset_postdata();

			if ( ! isset( $_POST['promocode'] ) && empty( $_POST['promocode'] ) && ! $result ) {
				wp_safe_redirect( site_url() );
				exit();
			}
		} elseif ( is_page_template( 'templates/promocode.php' ) && ! isset( $_POST['promocode'] ) ) {
			wp_safe_redirect( site_url() );
			exit();
		}
	}
	add_action( 'cardealer_head_before', 'cdhl_validate_promocode_template' );
}
