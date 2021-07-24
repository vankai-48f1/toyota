<?php
/**
 * Register Post Type for Geo Fencing
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

add_action( 'init', 'cdhl_geo_fencing_cpt', 1 );
if ( ! function_exists( 'cdhl_geo_fencing_cpt' ) ) {
	/**
	 * Register geo fencing
	 */
	function cdhl_geo_fencing_cpt() {
		$labels = array(
			'name'               => esc_html__( 'Geo Fencing', 'cardealer-helper' ),
			'singular_name'      => esc_html__( 'Geo Fencing', 'cardealer-helper' ),
			'menu_name'          => esc_html__( 'Geo Fencing', 'cardealer-helper' ),
			'name_admin_bar'     => esc_html__( 'Geo Fencing', 'cardealer-helper' ),
			'add_new'            => esc_html__( 'Add New', 'cardealer-helper' ),
			'add_new_item'       => esc_html__( 'Add New Geo Fencing', 'cardealer-helper' ),
			'new_item'           => esc_html__( 'New Geo Fencing', 'cardealer-helper' ),
			'edit_item'          => esc_html__( 'Edit Geo Fencing', 'cardealer-helper' ),
			'view_item'          => esc_html__( 'View Geo Fencing', 'cardealer-helper' ),
			'all_items'          => esc_html__( 'All Geo Fencing', 'cardealer-helper' ),
			'search_items'       => esc_html__( 'Search Geo Fencing', 'cardealer-helper' ),
			'parent_item_colon'  => esc_html__( 'Parent Geo Fencing:', 'cardealer-helper' ),
			'not_found'          => esc_html__( 'No geo fencing found.', 'cardealer-helper' ),
			'not_found_in_trash' => esc_html__( 'No geo fencing found in Trash.', 'cardealer-helper' ),
		);

		$args = array(
			'labels'              => $labels,
			'description'         => esc_html__( 'Description.', 'cardealer-helper' ),
			'public'              => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'query_var'           => true,
			'rewrite'             => array( 'slug' => 'geo_fencing' ),
			'capability_type'     => 'post',
			'exclude_from_search' => true,
			'has_archive'         => false,
			'hierarchical'        => false,
			'menu_position'       => null,
			'supports'            => array( 'title', 'author', 'editor' ),
			'menu_icon'           => 'dashicons-location',
		);
		register_post_type( 'cars_geofencing', $args );
	}
}

if ( ! function_exists( 'cdhl_geo_meta_boxes' ) ) {
	/**
	 * Register meta box(es).
	 */
	function cdhl_geo_meta_boxes() {
		add_meta_box( 'geo_fencing', esc_html__( 'Geo Fencing', 'cardealer-helper' ), 'cdhl_geofencing_callback', 'cars_geofencing' );
	}
	add_action( 'add_meta_boxes', 'cdhl_geo_meta_boxes' );
}

if ( ! function_exists( 'cdhl_geofencing_callback' ) ) {
	/**
	 * Meta box display callback.
	 *
	 * @param string $post .
	 */
	function cdhl_geofencing_callback( $post ) {

		$radius = 0;
		global $car_dealer_options;

		$radius = get_post_meta( $post->ID, 'radius', true );
		$lat    = get_post_meta( $post->ID, 'lat', true );
		$lng    = get_post_meta( $post->ID, 'lng', true );
		$zoom   = get_post_meta( $post->ID, 'zoom', true );

		if ( ! isset( $lat ) || empty( $lat ) && ! isset( $lng ) || empty( $lng ) ) {
			$lat  = ( isset( $car_dealer_options['default_value_lat'] ) && ! empty( $car_dealer_options['default_value_lat'] ) ? $car_dealer_options['default_value_lat'] : 0 );
			$lng  = ( isset( $car_dealer_options['default_value_long'] ) && ! empty( $car_dealer_options['default_value_long'] ) ? $car_dealer_options['default_value_long'] : 0 );
			$zoom = ( isset( $car_dealer_options['default_value_zoom'] ) && ! empty( $car_dealer_options['default_value_zoom'] ) ? $car_dealer_options['default_value_zoom'] : 12 );
		}
		wp_nonce_field( basename( __FILE__ ), 'meta-box-nonce' );

		$geo_obj = array(
			'radius' => (float) $radius,
			'lat'    => (float) $lat,
			'lng'    => (float) $lng,
			'zoom'   => $zoom,

		);
		wp_localize_script( 'cdhl-google-maps-apis', 'cdhl_map', $geo_obj );
		wp_enqueue_script( 'cdhl-geofance' );
		wp_enqueue_script( 'cdhl-google-maps-apis' );
		?>
		<input type="text" id="pac-input" name="search" class="widefat"/>
		<input type="hidden" id="radius" name="radius" value="<?php echo esc_attr( $radius ); ?>" class="widefat" />
		<input type="hidden" id="lat" name="lat" value="<?php echo esc_attr( $lat ); ?>" class="widefat" />
		<input type="hidden" id="lng" name="lng" value="<?php echo esc_attr( $lng ); ?>" class="widefat" />
		<input type="hidden" id="zoom" name="zoom" value="<?php echo esc_attr( $zoom ); ?>"/>
		<div id="map" class="geo-map"></div>
		<?php
	}
}

if ( ! function_exists( 'cdhl_geofenc_save_meta_box' ) ) {
	/**
	 * Save meta box content.
	 *
	 * @param string $post_id .
	 */
	function cdhl_geofenc_save_meta_box( $post_id ) {

		if ( ! isset( $_POST['meta-box-nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['meta-box-nonce'] ), basename( __FILE__ ) ) ) {
			return $post_id;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		$radius = 0;
		$lat    = 0;
		$lng    = 0;
		$zoom   = 12;
		if ( isset( $_POST['radius'] ) ) {
			$radius = sanitize_text_field( wp_unslash( $_POST['radius'] ) );
		}
		update_post_meta( $post_id, 'radius', $radius );

		if ( isset( $_POST['lat'] ) ) {
			$lat = sanitize_text_field( wp_unslash( $_POST['lat'] ) );
		}
		update_post_meta( $post_id, 'lat', $lat );

		if ( isset( $_POST['lng'] ) ) {
			$lng = sanitize_text_field( wp_unslash( $_POST['lng'] ) );
		}
		update_post_meta( $post_id, 'lng', $lng );

		if ( isset( $_POST['zoom'] ) ) {
			$zoom = sanitize_text_field( wp_unslash( $_POST['zoom'] ) );
		}
		update_post_meta( $post_id, 'zoom', $zoom );

		global $wpdb;
		$table_name = $wpdb->prefix . 'geo_fencing';
		$results    = $wpdb->get_results( "SELECT * FROM $table_name WHERE post_id = $post_id", OBJECT );

		$data = array(
			'post_id' => $post_id,
			'radius'  => $radius,
			'lat'     => $lat,
			'lng'     => $lng,
		);
		if ( ! empty( $results ) ) {
			$wpdb->update( $table_name, $data, array( 'post_id' => $post_id ) );
		} else {
			$wpdb->insert( $table_name, $data );
		}
	}
	add_action( 'save_post', 'cdhl_geofenc_save_meta_box' );
}

add_action( 'before_delete_post', 'cdhl_geofenc_before_delete_entry' );
if ( ! function_exists( 'cdhl_geofenc_before_delete_entry' ) ) {
	/**
	 * Geofenc before delete entry
	 *
	 * @param string $postid .
	 */
	function cdhl_geofenc_before_delete_entry( $postid ) {
		global $post_type,$post;
		if ( 'cars_geofencing' !== $post_type ) {
			return;
		}
		global $wpdb;
		$geo_fencing = $wpdb->prefix . 'geo_fencing';
		$wpdb->delete( $geo_fencing, array( 'post_id' => $post->ID ) );
	}
}

if ( ! function_exists( 'cdhl_geofenc_geo_bar' ) ) {
	/**
	 * Geofenc bar
	 */
	function cdhl_geofenc_geo_bar() {
		// Display Geo Fencing message in top bar.
		$geo_lat          = isset( $_COOKIE['geo_lat'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['geo_lat'] ) ) : '';
		$geo_lng          = isset( $_COOKIE['geo_lng'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['geo_lng'] ) ) : '';
		$geo_msg          = isset( $_COOKIE['geo_msg'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['geo_msg'] ) ) : '';
		$geo_closed       = isset( $_COOKIE['geo_closed'] ) ? (bool) sanitize_text_field( wp_unslash( $_COOKIE['geo_closed'] ) ) : false;
		$cars_geo_fencing = cdhl_get_geo_count();
		if ( ! empty( $geo_lat ) && ! empty( $geo_lng ) && false === $geo_closed && $cars_geo_fencing > 0 ) {
			?>
			<div class="geo-bar">
				<span class="geo-closed">&times;</span>
				<div class="container">
					<div class="row">
						<div class="col-lg-12 col-sm-12">
							<marquee class="geo-fencing" behavior="scroll" direction="left" scrollamount="5" style="width:100%; height:100%; vertical-align:middle; cursor:pointer;" onmouseover="javascript:this.setAttribute('scrollamount','0');" onmouseout="javascript:this.setAttribute('scrollamount','5');">
								<div class="geo-content"><?php echo esc_html( $geo_msg ); ?></div>
							</marquee>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}
	add_action( 'cardealer_before_header', 'cdhl_geofenc_geo_bar' );
}

/**
 * Check and redirect user to promocode offer page
 */
add_action( 'wp_ajax_findGeolocation', 'cdhl_find_geo_location' );
add_action( 'wp_ajax_nopriv_findGeolocation', 'cdhl_find_geo_location' );
if ( ! function_exists( 'cdhl_find_geo_location' ) ) {
	/**
	 * Find Geo location
	 */
	function cdhl_find_geo_location() {
		$lat = ( isset( $_POST['lat'] ) && ! empty( $_POST['lat'] ) ) ? sanitize_text_field( wp_unslash( $_POST['lat'] ) ) : '';
		$lng = ( isset( $_POST['lng'] ) && ! empty( $_POST['lng'] ) ) ? sanitize_text_field( wp_unslash( $_POST['lng'] ) ) : '';

		$result = array(
			'status' => 'error',
			'msg'    => 'Somthing went wrong!',
		);

		if ( empty( $lat ) || empty( $lng ) ) {
			echo wp_json_encode( $result );
			exit();
		}

		global $wpdb;
		$geo_fencinge  = $wpdb->prefix . 'geo_fencing';
		$posts_tbl     = $wpdb->prefix . 'posts';
		$query_results = $wpdb->get_results( "SELECT * FROM $geo_fencinge", OBJECT );

		$data    = array();
		$content = '';
		$query   = "SELECT $geo_fencinge.id,$geo_fencinge.post_id,
        ( 3959 * acos( cos( radians($lat) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( lat ) ) ) ) * 1609.34 AS distance
        FROM $geo_fencinge
        INNER JOIN $posts_tbl ON $posts_tbl.ID = $geo_fencinge.post_id
        WHERE $posts_tbl.post_status = 'publish'
        AND (( 3959 * acos( cos( radians($lat) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( lat ) ) ) ) * 1609.34 ) <= $geo_fencinge.radius ORDER BY distance LIMIT 1";

		$data = $wpdb->get_results( $query, OBJECT );

		if ( ! empty( $data ) ) {
			$content_post = get_post( $data[0]->post_id );
			$content      = $content_post->post_content;
			$result       = array(
				'status'  => 'success',
				'content' => $content,
			);
		}
		echo wp_json_encode( $result );
		exit();
	}
}

if ( ! function_exists( 'cdhl_get_geo_count' ) ) {
	/**
	 * Get geo count
	 */
	function cdhl_get_geo_count() {
		global $car_dealer_options;
		$geo_fencin       = 0;
		$cars_geo_fencing = ( isset( $car_dealer_options['cars-geo-fencing'] ) && ! empty( $car_dealer_options['cars-geo-fencing'] ) ? $car_dealer_options['cars-geo-fencing'] : 0 );

		if ( $cars_geo_fencing > 0 ) {
			$args            = array(
				'post_type'      => 'cars_geofencing',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
			);
			$cars_geofencing = new WP_Query( $args );
			if ( $cars_geofencing->have_posts() ) {
				$tot_result = $cars_geofencing->post_count;
				if ( $tot_result > 0 ) {
					$geo_fencin = 1;
				}
				wp_reset_postdata();
			} else {
				$geo_fencin = 0;
			}
		}
		return $geo_fencin;
	}
}
