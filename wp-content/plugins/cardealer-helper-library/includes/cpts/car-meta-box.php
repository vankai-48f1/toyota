<?php
/**
 * Register meta box(es)
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

if ( ! function_exists( 'cdhl_register_meta_boxes' ) ) {
	/**
	 * Register meta boxes
	 */
	function cdhl_register_meta_boxes() {
		global $car_dealer_options;
		$map_option            = ( isset( $car_dealer_options['car_to_pro_map_option'] ) ) ? $car_dealer_options['car_to_pro_map_option'] : 0;
		$car_to_pro_map_option = ( isset( $car_dealer_options['car_to_pro_map_option'] ) ) ? $car_dealer_options['car_to_pro_map_option'] : 0;
		if ( ! cdhl_plugin_active_status( 'woocommerce/woocommerce.php' ) || ! $map_option ) {
			return;
		}
		add_meta_box( 'cdhl-car-to-product-mapping', esc_html__( 'Vehicle Mapping with Woo', 'cardealer-helper' ), 'cdhl_car_to_product_mapping', 'cars', 'side' );
	}
	add_action( 'add_meta_boxes', 'cdhl_register_meta_boxes' );
}
if ( ! function_exists( 'cdhl_car_to_product_mapping' ) ) {
	/**
	 * Meta box display callback.
	 *
	 * @param WP_Post $post Current post object.
	 */
	function cdhl_car_to_product_mapping( $post ) {

		$car_to_woo_product_id = get_post_meta( $post->ID, 'car_to_woo_product_id', true );

		// Query args.
		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => -1,
		);

		$loop = get_posts( $args );
		if ( ! empty( $loop ) ) {
			?>
			<p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="car_to_woo_product_meta_id"><?php esc_html_e( 'WooCommerce Product list', 'cardealer-helper' ); ?></label></p>
			<select name="car_to_woo_product_id" id="car_to_woo_product_meta_id" class="postbox">
				<option value=""><?php esc_html_e( 'Select Product', 'cardealer-helper' ); ?></option>
				<?php
				foreach ( $loop as $product ) {
					$current_id = $product->ID;
					?>
					<option value="<?php echo esc_attr( $current_id ); ?>" <?php selected( $current_id, $car_to_woo_product_id ); ?>><?php echo esc_html( $product->post_title ); ?></option>
					<?php
				}
				?>
			</select>
			<?php
		} else {
			esc_html_e( 'No product found!', 'cardealer-helper' );
		}
		wp_reset_postdata();
	}
}
if ( ! function_exists( 'cdhl_save_meta_box' ) ) {
	/**
	 * Save meta box content.
	 *
	 * @param int $post_id Post ID.
	 */
	function cdhl_save_meta_box( $post_id ) {
		// Set final price.
		if ( 'cars' === (string) get_post_type( $post_id ) && isset( $_POST['car_to_woo_product_id'] ) ) {
			update_post_meta( $post_id, 'car_to_woo_product_id', sanitize_text_field( wp_unslash( $_POST['car_to_woo_product_id'] ) ) );
		}
	}
	add_action( 'save_post', 'cdhl_save_meta_box' );
}
