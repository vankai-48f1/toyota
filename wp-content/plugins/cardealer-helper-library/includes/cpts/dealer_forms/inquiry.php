<?php
/**
 * Add taxonomy
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

add_action( 'init', 'cdhl_inquiry' );
if ( ! function_exists( 'cdhl_inquiry' ) ) {
	/**
	 * Inquiry
	 */
	function cdhl_inquiry() {
		$args = array(
			'labels'              => array(
				'name'          => esc_html__( 'Inquiries', 'cardealer-helper' ),
				'singular_name' => esc_html__( 'Inquiries', 'cardealer-helper' ),
			),
			'capability_type'     => 'post',
			'capabilities'        => array(
				'create_posts' => 'do_not_allow', // false < WP 4.5, credit @Ewout.
			),
			'map_meta_cap'        => true,
			'public'              => true,
			'show_in_nav_menus'   => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'has_archive'         => false,
			'rewrite'             => array( 'slug' => 'inquiry' ),
			'supports'            => array(),
			'menu_icon'           => 'dashicons-editor-help',
		);
		register_post_type( 'pgs_inquiry', $args );
	}
}

if ( ! function_exists( 'cdhl_cpt_inquiry_edit_columns' ) ) {
	/**
	 * Edit columns
	 *
	 * @param string $columns .
	 */
	function cdhl_cpt_inquiry_edit_columns( $columns = array() ) {
		$newcolumns = array();
		$newcolumns = array(
			'email'         => esc_html__( 'Email Id', 'cardealer-helper' ),
			'mobile'        => esc_html__( 'Phone No', 'cardealer-helper' ),
			'inq_car_info'  => esc_html__( 'Vehicle Information', 'cardealer-helper' ),
			'inq_vin_stock' => esc_html__( 'VIN / StockNo', 'cardealer-helper' ),
			'price'         => esc_html__( 'Price', 'cardealer-helper' ),
		);
		$columns    = array_merge( $columns, $newcolumns );
		return $columns;
	}
	add_filter( 'manage_edit-pgs_inquiry_columns', 'cdhl_cpt_inquiry_edit_columns' );
}

if ( ! function_exists( 'cdhl_cpt_inquiry_custom_columns' ) ) {
	/**
	 * Custom columns
	 *
	 * @param string $column .
	 */
	function cdhl_cpt_inquiry_custom_columns( $column ) {
		global $post;

		$inq_id = get_the_ID();

		switch ( $column ) {

			case 'email':
				$email = get_post_meta( $inq_id, 'email', $single = true );
				if ( $email ) {
					echo esc_html( $email );
				}
				break;

			case 'mobile':
				$mobile = get_post_meta( $inq_id, 'mobile', $single = true );
				if ( $mobile ) {
					echo esc_html( $mobile );
				}
				break;

			case 'inq_car_info':
				$caryear   = get_post_meta( $inq_id, 'car_year_inq', $single = true );
				$car_year  = ( isset( $caryear ) ) ? $caryear : '';
				$carmake   = get_post_meta( $inq_id, 'car_make_inq', $single = true );
				$car_make  = ( isset( $carmake ) ) ? $carmake : '';
				$carmodel  = get_post_meta( $inq_id, 'car_model_inq', $single = true );
				$car_model = ( isset( $carmodel ) ) ? $carmodel : '';
				$cartrim   = get_post_meta( $inq_id, 'car_trim_inq', $single = true );
				$car_trim  = ( isset( $cartrim ) ) ? $cartrim : '';
				echo esc_html( $car_year . ' ' . $car_make . ' ' . $car_model . ' ' . $car_trim );
				break;

			case 'inq_vin_stock':
				$carvin    = get_post_meta( $inq_id, 'vin_number', true );
				$car_vin   = ( isset( $carvin ) ) ? $carvin : '';
				$carstock  = get_post_meta( $inq_id, 'stock_number', true );
				$car_stock = ( isset( $carstock ) ) ? $carstock : '';
				echo esc_html( $car_vin . ' / ' . $car_stock );
				break;
		}
	}
	add_filter( 'manage_posts_custom_column', 'cdhl_cpt_inquiry_custom_columns' );
}
