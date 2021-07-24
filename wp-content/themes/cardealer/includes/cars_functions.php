<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Theme vehicle functions
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package CarDealer/Functions
 * @version 1.0.0
 */

if ( ! function_exists( 'cardealet_taxonomy_template_chooser' ) ) {
	/**
	 * Template redirection to archive-cars.php for taxonomy page template.
	 *
	 * @param string $template .
	 */
	function cardealet_taxonomy_template_chooser( $template ) {
		global $wp_query;

		if ( is_tax() ) {
			$current_taxonomy = get_query_var( 'taxonomy' );
			if ( $current_taxonomy ) {
				$current_taxonomy_object = get_taxonomy( $current_taxonomy );
				$current_post_types      = $current_taxonomy_object->object_type;

				if ( in_array( 'cars', $current_post_types ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
					$new_template = locate_template( array( 'archive-cars.php' ) );
					if ( '' !== $new_template ) {
						$template = $new_template;
					}
				}
			}
		}
		return $template;
	}
	add_filter( 'template_include', 'cardealet_taxonomy_template_chooser', 99 );

}

add_action( 'cardealer_car_loop_link_open', 'cardealer_car_link_open', 10, 2 );
add_action( 'cardealer_car_loop_link_close', 'cardealer_car_link_close', 10, 2 );
if ( ! function_exists( 'cardealer_car_link_open' ) ) {
	/**
	 * Car link open.
	 *
	 * @param string $id .
	 * @param string $is_hover_overlay .
	 */
	function cardealer_car_link_open( $id, $is_hover_overlay ) {
		if ( 'no' === $is_hover_overlay ) {
			echo '<a href="' . esc_url( get_the_permalink( $id ) ) . '">';
		}
	}
}
if ( ! function_exists( 'cardealer_car_link_close' ) ) {
	/**
	 * Link close
	 *
	 * @param string $id .
	 * @param string $is_hover_overlay .
	 */
	function cardealer_car_link_close( $id, $is_hover_overlay ) {
		if ( 'no' === $is_hover_overlay ) {
			echo '</a>';
		}
	}
}
if ( ! function_exists( 'cardealer_is_hover_overlay' ) ) {
	/**
	 * Hover overlay
	 */
	function cardealer_is_hover_overlay() {
		global $car_dealer_options;
		$is_hover_overlay = 'yes';
		if ( isset( $car_dealer_options['cars-is-hover-overlay-on'] ) && ! empty( $car_dealer_options['cars-is-hover-overlay-on'] ) ) {
			$is_hover_overlay = $car_dealer_options['cars-is-hover-overlay-on'];
		}
		return $is_hover_overlay;
	}
}
add_action( 'car_before_overlay_banner', 'cardealer_get_cars_condition', 10, 2 );
add_action( 'car_before_overlay_banner', 'cardealer_get_cars_status', 20, 2 );
/**
 * Actions to used in car listing loop items overlay : Default Listing Style
 */
add_action( 'car_overlay_banner', 'cardealer_view_cars_overlay_link', 10, 1 );
add_action( 'car_overlay_banner', 'cardealer_compare_cars_overlay_link', 20, 1 );
add_action( 'car_overlay_banner', 'cardealer_images_cars_overlay_link', 30, 1 );

if ( ! function_exists( 'cardealer_view_cars_overlay_link' ) ) {
	/**
	 * Get overlay link for view car details page
	 *
	 * @param string $id .
	 */
	function cardealer_view_cars_overlay_link( $id ) {
		$html = '<li><a href="' . get_the_permalink( $id ) . '" data-toggle="tooltip" title="' . esc_attr__( 'View', 'cardealer' ) . '"><i class="fas fa-link"></i></a></li>';
		/**
		 * Filters the HTML content of the vehicle detail page link in vehicle listing.
		 *
		 * @since 1.0
		 * @param string      $html Vehicle detail page link HTML content.
		 * @visible           true
		 */
		echo apply_filters( 'cardealer_view_cars_overlay_link', $html ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
	}
}

if ( ! function_exists( 'cardealer_compare_cars_overlay_link' ) ) {

	/**
	 * Compare cars overlay link
	 *
	 * @param string $id .
	 */
	function cardealer_compare_cars_overlay_link( $id ) {
		global $car_dealer_options;
		$compare_status = ( isset( $car_dealer_options['cars-is-compare-on'] ) ) ? $car_dealer_options['cars-is-compare-on'] : 'yes';
		if ( 'no' === $compare_status ) {
			return;
		}

		$car_in_compare = car_dealer_get_car_compare_ids();
		$compared_pgs   = '';
		$icon           = 'exchange-alt';
		if ( $car_in_compare && in_array( $id, $car_in_compare, true ) ) {
			$icon         = 'check';
			$compared_pgs = 'compared_pgs';
		}
		$html = '<li><a href="javascript:void(0)" data-toggle="tooltip" title="' . esc_attr__( 'Compare', 'cardealer' ) . '" class="compare_pgs ' . $compared_pgs . '" data-id="' . esc_attr( $id ) . '"><i class="fas fa-' . esc_attr( $icon ) . '"></i></a></li>';
		/**
		 * Filters the HTML content of the vehicle gallery link in vehicle listing.
		 *
		 * @since 1.0
		 * @param string      $html Vehicle gallery link HTML content.
		 * @visible           true
		 */
		echo apply_filters( 'cardealer_compare_cars_overlay_link', $html ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
	}
}

if ( ! function_exists( 'cardealer_images_cars_overlay_link' ) ) {
	/**
	 * Get overlay link for image gallery popup
	 *
	 * @param string $id .
	 */
	function cardealer_images_cars_overlay_link( $id ) {
		$images = cardealer_get_images_url( 'car_catalog_image', $id );
		$html   = '';
		if ( ! empty( $images ) ) {
			$html = '<li class="pssrcset"><a href="javascript:void(0)" data-toggle="tooltip" title="' . esc_attr__( 'Gallery', 'cardealer' ) . '" class="psimages" data-image="' . implode( ', ', $images ) . '"><i class="fas fa-expand"></i></a></li>';
		}
		/**
		 * Filters the HTML content of the vehicle gallery link in vehicle listing.
		 *
		 * @since 1.0
		 * @param string      $html Vehicle gallery link HTML content.
		 * @visible           true
		 */
		echo apply_filters( 'cardealer_images_cars_overlay_link', $html ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
	}
}


/**
 * Actions to used in car CLASSIC grid loop items overlay
 */
add_action( 'vehicle_classic_grid_overlay', 'cardealer_compare_cars_overlay_link', 10, 1 );
add_action( 'vehicle_classic_grid_overlay', 'cardealer_images_cars_overlay_link', 20, 1 );


/**
 * Actions to used in car CLASSIC listing loop items overlay
 */
add_action( 'vehicle_classic_list_overlay_banner', 'cardealer_classic_view_cars_overlay_link', 10, 1 );
add_action( 'vehicle_classic_list_overlay_banner', 'cardealer_classic_compare_cars_overlay_link', 20, 1 );
add_action( 'vehicle_classic_list_overlay_gallery', 'cardealer_classic_images_cars_overlay_link', 10, 1 );
add_action( 'vehicle_classic_list_overlay_banner', 'cardealer_classic_vehicle_video_link', 30, 1 );

if ( ! function_exists( 'cardealer_classic_view_cars_overlay_link' ) ) {

	/**
	 * Get overlay link for view car details page
	 *
	 * @param string $id .
	 */
	function cardealer_classic_view_cars_overlay_link( $id ) {
		$html = '<li><a href="' . get_the_permalink( $id ) . '" data-toggle="tooltip" title="' . esc_attr__( 'View', 'cardealer' ) . '"><i class="fas fa-link"></i>' . esc_html__( 'Detail', 'cardealer' ) . '</a></li>';
		/**
		 * Filters the HTML content of the vehicle detail page link in vehicle listing.
		 *
		 * @since 1.0
		 * @param string      $html Vehicle compare detail page link HTML content.
		 * @visible           true
		 */
		echo apply_filters( 'cardealer_classic_view_cars_overlay_link', $html ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
	}
}

if ( ! function_exists( 'cardealer_classic_compare_cars_overlay_link' ) ) {
	/**
	 * Get overlay link for compare cars popup
	 *
	 * @param string $id .
	 */
	function cardealer_classic_compare_cars_overlay_link( $id ) {
		global $car_dealer_options;
		$compare_status = ( isset( $car_dealer_options['cars-is-compare-on'] ) ) ? $car_dealer_options['cars-is-compare-on'] : 'yes';
		if ( 'no' === $compare_status ) {
			return;
		}

		// @codingStandardsIgnoreStart
		if ( isset( $_COOKIE['cars'] ) && ! empty( $_COOKIE['cars'] ) ) {
			$car_in_compare = json_decode( $_COOKIE['cars'] );
		}
		$compared_pgs = '';
		$icon         = 'exchange-alt';
		if ( isset( $car_in_compare ) && ! empty( $car_in_compare ) && in_array( $id, $car_in_compare ) ) {
			$cars = json_decode( $_COOKIE['cars'] );
			if ( $cars ) {
				$icon         = 'check';
				$compared_pgs = 'compared_pgs';
			}
		}
		// @codingStandardsIgnoreEnd

		$html = '<li><a href="javascript:void(0)" data-toggle="tooltip" title="' . esc_attr__( 'Compare', 'cardealer' ) . '" class="compare_pgs ' . $compared_pgs . '" data-id="' . esc_attr( $id ) . '"><i class="fas fa-' . $icon . '"></i>' . esc_html__( 'Compare', 'cardealer' ) . '</a></li>';
		/**
		 * Filters the HTML content of the vehicle compare link in vehicle listing.
		 *
		 * @since 1.0
		 * @param string      $html Vehicle compare link HTML content.
		 * @visible           true
		 */
		echo apply_filters( 'cardealer_classic_compare_cars_overlay_link', $html ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
	}
}

if ( ! function_exists( 'cardealer_classic_images_cars_overlay_link' ) ) {
	/**
	 * Get overlay link for image gallery popup
	 *
	 * @param string $id .
	 */
	function cardealer_classic_images_cars_overlay_link( $id ) {
		$images = cardealer_get_images_url( 'car_catalog_image', $id );
		$html   = '';
		if ( ! empty( $images ) ) {
			$html = '<li class="pssrcset"><a href="javascript:void(0)" data-toggle="tooltip" title="' . esc_attr__( 'Gallery', 'cardealer' ) . '" class="psimages" data-image="' . implode( ', ', $images ) . '"><i class="fas fa-expand"></i></a></li>';
		}
		/**
		 * Filters the HTML content of the vehicle gallery link in vehicle listing.
		 *
		 * @since 1.0
		 * @param string      $html Vehicle gallery link HTML content.
		 * @visible           true
		 */
		echo apply_filters( 'cardealer_classic_images_cars_overlay_link', $html ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
	}
}

if ( ! function_exists( 'cardealer_classic_vehicle_video_link' ) ) {
	/**
	 * Get overlay link for video popup
	 *
	 * @param string $id .
	 */
	function cardealer_classic_vehicle_video_link( $id ) {
		$video_link = get_post_meta( $id, 'video_link', true );
		$html       = '';
		if ( ! empty( $video_link ) ) {
			$html = '<li><a class="popup-youtube" href="' . esc_attr( $video_link ) . '" data-toggle="tooltip" title="' . esc_attr__( 'Video', 'cardealer' ) . '"> <i class="fas fa-play"></i>' . esc_html__( 'Video', 'cardealer' ) . '</a></li>';
		}
		/**
		 * Filters the HTML content of the vehicle video link in vehicle listing.
		 *
		 * @since 1.0
		 * @param string      $html Vehicle video link HTML content.
		 * @visible           true
		 */
		echo apply_filters( 'cardealer_classic_vehicle_video_link', $html ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
	}
}

/**
 * Actions to used in car listing loop items title : Classic View
 */
add_action( 'cardealer_classic_list_car_title', 'cardealer_list_car_link_title', 10 );


/**
 * Actions to used in car listing loop items title : Default View
 */
add_action( 'cardealer_list_car_title', 'cardealer_list_car_link_title', 5 );
add_action( 'cardealer_list_car_title', 'cardealer_list_car_title_separator', 10 );

if ( ! function_exists( 'cardealer_list_car_link_title' ) ) {
	/**
	 * Get loop items title and link
	 */
	function cardealer_list_car_link_title() {
		echo '<a href="' . esc_url( get_the_permalink() ) . '">' . esc_attr( get_the_title() ) . '</a>';
	}
}

if ( ! function_exists( 'cardealer_list_car_title_separator' ) ) {
	/**
	 * Get loop items title after separator
	 */
	function cardealer_list_car_title_separator() {
		echo '<div class="separator"></div>';
	}
}

if ( ! function_exists( 'cardealer_link_feature_image_to_header' ) ) {
	/**
	 * Add Facebook meta tags for sharing
	 */
	function cardealer_link_feature_image_to_header() {
		$post_featured_image = cardealer_get_single_image_url();
		if ( ( is_single() && $post_featured_image ) && 'cars' === get_post_type() ) {
			echo '<meta property="og:type" content="article" />';
			echo '<meta property="og:title" content="' . esc_attr( get_the_title() ) . '" />';
			echo '<meta property="og:url" content="' . esc_attr( get_permalink() ) . '" />';
			echo '<meta property="og:description" content="' . cardealer_car_short_content( get_the_ID() ) . '" />'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			echo '<meta property="og:image" content="' . esc_attr( $post_featured_image ) . '" />';
			echo '<link rel="image_src" href="' . esc_attr( $post_featured_image ) . '" />';
		}
	}
	add_action( 'wp_head', 'cardealer_link_feature_image_to_header', 10 );
}

if ( ! function_exists( 'cardealer_car_short_content' ) ) {
	/**
	 * Get cars short content
	 *
	 * @param string $id .
	 */
	function cardealer_car_short_content( $id ) {
		$excerpt          = get_post_field( 'post_excerpt', $id );
		$vehicle_overview = get_post_meta( $id, 'vehicle_overview', true );
		$summary          = '&nbsp;';
		if ( isset( $excerpt ) && ! empty( $excerpt ) ) {
			$summary = $excerpt;
		} elseif ( isset( $vehicle_overview ) && ! empty( $vehicle_overview ) ) {
			$summary = wp_trim_words( $vehicle_overview, 30, '...' );
		}

		/**
		 * Filters the vehicle summary(short) contents.
		 *
		 * @since 1.0
		 * @param string      $summary Vehicle short contents.
		 * @visible           true
		 */
		return apply_filters( 'cardealer_car_short_content', esc_attr( $summary ) );
	}
}

if ( ! function_exists( 'cardealer_get_cars_attributes' ) ) {
	/**
	 * Display cars features list in cars details page
	 *
	 * @param string $post_id .
	 */
	function cardealer_get_cars_attributes( $post_id = null ) {
		global $car_dealer_options;
		// bail early if $post_id is not provided.
		if ( ! $post_id ) {
			return;
		}

		if ( isset( $car_dealer_options['vehicle-detail-attributes'] ) && ! empty( $car_dealer_options['vehicle-detail-attributes'] ) ) {
			$taxonomys = apply_filters( 'cardealer_taxonomys_array', $car_dealer_options['vehicle-detail-attributes'] );
		} else {
			$tax_arr = array( 'car_year', 'car_make', 'car_model', 'car_body_style', 'car_condition', 'car_mileage', 'car_transmission', 'car_drivetrain', 'car_engine', 'car_fuel_type', 'car_fuel_economy', 'car_trim', 'car_exterior_color', 'car_interior_color', 'car_stock_number', 'car_vin_number' );

			$taxonomies_raw = get_object_taxonomies( 'cars' );

			foreach ( $taxonomies_raw as $new_tax ) {
				if ( in_array( $new_tax, $tax_arr ) ) {
					continue;
				}

				$new_tax_obj = get_taxonomy( $new_tax );
				if( isset($new_tax_obj->include_in_filters) && $new_tax_obj->include_in_filters == true ) {
					$tax_arr[] = $new_tax;
				}
			}

			$taxonomys = apply_filters(
				'cardealer_taxonomys_array',
				$tax_arr
			);

		}


		/**
		 * Filters the Array of the vehicle taxonomies used to display on the vehicle details page.
		 *
		 * @since 1.0
		 * @param array        $taxonomys  Array of the vehicle taxonomies selected to be display on vehicle detail page.
		 * @param int          $post_id    Vehicle ID.
		 * @visible            true
		 */
		$taxonomys      = apply_filters( 'cardealer_cars_details_attributes_array', $taxonomys, $post_id );
		$attributes     = array();
		$taxonomies_obj = get_object_taxonomies( 'cars', 'object' );

		foreach ( $taxonomys as $tax ) {
			$term = wp_get_post_terms( $post_id, $tax );
			if ( ! is_wp_error( $term ) && ! empty( $term ) ) {
				$label              = $taxonomies_obj[ $tax ]->labels->singular_name;
				$attributes[ $tax ] = array(
					'attr'  => $label,
					'value' => $term[0]->name,
				);
			}
		}

		/**
		 * Filters the Array of the vehicle attributes used to display on the vehicle details page.
		 *
		 * @since 1.0
		 * @param array        $attributes Array of the vehicle attributes selected to be display on vehicle detail page.
		 * @param int          $post_id    Vehicle ID.
		 * @visible            true
		 */
		$attributes     = apply_filters( 'cardealer_car_attributes', $attributes, $post_id );
		$attributs_html = '';
		if ( is_array( $attributes ) && ! empty( $attributes ) ) {

			$attributs_html = '<ul class="car-attributes">';
			foreach ( $attributes as $attribute_k => $attribute ) {

				// skip if attribute or value is not set.
				if ( ! isset( $attribute_k ) || ( ! isset( $attribute['attr'] ) || '' === $attribute['attr'] ) || ( ! isset( $attribute['value'] ) || '' === $attribute['value'] ) ) {
					continue;
				}

				$attributs_html .= '<li class="' . esc_attr( $attribute_k ) . '"><span>' . $attribute['attr'] . '</span> <strong class="text-right">' . $attribute['value'] . '</strong></li>';
			}
			$attributs_html .= '</ul>';

		}

		// Deprecated.
		$attributs_html = apply_filters_deprecated( 'cardealer_get_cars_attributes', array( $attributs_html ), '1.1.1', 'cardealer_car_attributes_html' );

		/**
		 * Filters the HTML of the vehicle attributes to display on the vehicle details page.
		 *
		 * @since 1.0
		 * @param   string       $attributs_html HTML structure of the vehicle attributes to be display on the vehicle details page.
		 * @param   array        $attributes    Array of the vehicle attributes selected to be display on vehicle detail page.
		 * @param   int          $post_id       Vehicle ID.
		 * @visible              true
		 */
		$attributs_html = apply_filters( 'cardealer_car_attributes_html', $attributs_html, $attributes, $post_id );

		$attributs_allowed_html = apply_filters( 'cardealer_car_attributes_allowed_html', array( 'ul', 'li', 'span', 'strong' ), $post_id );

		echo wp_kses( $attributs_html, cardealer_allowed_html( $attributs_allowed_html ) );
	}
}

if ( ! function_exists( 'cardealer_add_vehicale_to_cart' ) ) {
	/**
	 * Display add vehicale to cart details page
	 *
	 * @param string $vehicle_id .
	 */
	function cardealer_add_vehicale_to_cart( $vehicle_id ) {
		global $car_dealer_options;
		$car_to_pro_map_option = ( isset( $car_dealer_options['car_to_pro_map_option'] ) ) ? $car_dealer_options['car_to_pro_map_option'] : 0;
		if ( $car_to_pro_map_option && class_exists( 'WooCommerce' ) ) {
			$woocommerce_product_id = get_post_meta( $vehicle_id, 'car_to_woo_product_id', true );
			echo '<div class="add-vehicale-to-cart-btn">';
			echo do_shortcode( '[add_to_cart id="' . $woocommerce_product_id . '" style="border:none; padding: 5px;" show_price="false"]' );
			echo '</div>';
		}
	}
}

if ( ! function_exists( 'cardealer_get_vehicle_review_stamps' ) ) {
	/**
	 * Display vehicle review stamps
	 *
	 * @param string $id .
	 * @param string $echo .
	 */
	function cardealer_get_vehicle_review_stamps( $id = null, $echo = false ) {
		if ( null !== $id ) {
			$args            = array(
				'orderby' => 'name',
				'order'   => 'ASC',
				'fields'  => 'all',
			);
			$terms           = wp_get_post_terms( $id, 'car_vehicle_review_stamps', $args );
			$url             = '';
			$image_url       = '';
			$link            = '';
			$html            = '';
			$stamp_img_array = array();
			foreach ( $terms as $term ) {
				if ( ! empty( $term->term_id ) ) {
					$image = get_term_meta( $term->term_id, 'image' );
					if ( isset( $image ) && ! empty( $image ) ) {
						$image_url = wp_get_attachment_url( $image[0] );
						$url_arr   = get_term_meta( $term->term_id, 'url' );

						if ( isset( $url_arr[0] ) && ! empty( $url_arr[0] ) ) {
							$url = $url_arr[0];
							$vin = wp_get_post_terms( $id, 'car_vin_number', $args );
							if ( isset( $vin[0]->name ) && ! empty( $vin[0]->name ) ) {
								$return = str_replace( '{{vin}}', $vin[0]->name, $url );
								$url    = $return;
							}
						}
						if ( ! empty( $image_url ) ) {

							if ( $echo ) {
								$stamp_img_array[] = array(
									'img_url' => $image_url,
									'link'    => $url,
								);
							} else {
								if ( '' !== $url ) {
									$link .= '<a href="' . esc_url( $url ) . '" target="_blank"><img src="' . esc_url( $image_url ) . '" alt="carfax"/></a>';

								} else {
									$link .= '<img src="' . esc_url( $image_url ) . '" alt="carfax"/>';

								}
							}
						}
					}
				}
			}
			if ( $echo ) {
				return $stamp_img_array;
			} else {
				/**
				 * Filters the URL of the vehicle review stamps of the vehicle.
				 *
				 * @since 1.0
				 * @param string    $link  URL of the vehicle review stamps.
				 * @param int       $id    Vehicle ID.
				 * @Hooked          cardealer_update_stamp_html 10
				 * @visible         true
				 */
				$link = apply_filters( 'cardealer_vrs_link_html', $link, $id );
				if ( '' !== $link ) {
					$html  = '<div class="car-vehicle-review-stamps">';
					$html .= $link;
					$html .= '</div>';
				}
				/**
				 * Filters the HTML structure of vehicle review stamps of vehicle.
				 *
				 * @since 1.0
				 * @param string      $html HTML structure of Vehicle Review Stamps.
				 * @visible           true
				 */
				echo apply_filters( 'cardealer_get_vehicle_review_stamps', $html ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			}
		}
	}
}

if ( ! function_exists( 'cardealer_get_cars_list_attribute' ) ) {
	/**
	 * Display cars few features list in card catalog view on hover overlay
	 */
	function cardealer_get_cars_list_attribute() {
		global $post, $car_dealer_options;
		$list_style = cardealer_get_inv_list_style();
		if ( 'classic' === $list_style ) {
			if ( isset( $car_dealer_options['inv-list-attributes'] ) && ! empty( $car_dealer_options['inv-list-attributes'] ) ) {
				$car_taxonomys = $car_dealer_options['inv-list-attributes'];
			} else {
				$car_taxonomys = array( 'car_year', 'car_make', 'car_model', 'car_body_style', 'car_transmission' );
			}
			/**
			 * Filters the list of attributes to be displayed in list view layout of inventory page.
			 *
			 * @since 1.0
			 * @param array     $car_taxonomys  Array of attributes to be displayed in list view layout.
			 * @visible         true
			 */
			$taxonomys = apply_filters( 'cardealer_vehicle_list_attr_contents', $car_taxonomys );
			if ( empty( $taxonomys ) ) {
				return;
			}
			$taxonomies_obj = get_object_taxonomies( 'cars', 'object' );
			$getlayout      = cardealer_get_cars_list_layout_style();
			ob_start();?>
			<div class="vehicle-attributes-list">
				<?php
				if ( 'view-list-full' === $getlayout ) {
					$size         = ceil( count( $taxonomys ) / 2 );
					$parsed_attrs = array_chunk( $taxonomys, $size, true );
					foreach ( $parsed_attrs as $taxonomys ) {
						if ( empty( $taxonomys ) ) {
							continue;
						}
						?>
						<ul class="vehicle-attributes">
						<?php
						foreach ( $taxonomys as $tax ) {
							$attr = get_the_terms( $post->ID, $tax );
							if ( ! is_wp_error( $attr ) && ! empty( $attr ) && isset( $attr[0]->name ) ) {
								?>
									<li class="row">
										<span class="col-xs-6"><?php echo ucwords( esc_html( $taxonomies_obj[ $tax ]->labels->name ) ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></span>
										<strong class="col-xs-6"><?php echo ucwords( esc_html( $attr[0]->name ) ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></strong>
									</li>
								<?php
							}
						}
						?>
						</ul>
						<?php
					}
				} else {
					?>
					<ul class="list-inline">
					<?php

					foreach ( $taxonomys as $tax ) {
						$attr = get_the_terms( $post->ID, $tax );
						if ( ! is_wp_error( $attr ) && ! empty( $attr ) && isset( $attr[0]->name ) ) {
							?>
							<li class="row">
								<span class="col-xs-6"><?php echo ucwords( esc_html( $taxonomies_obj[ $tax ]->labels->singular_name ) ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></span>
								<strong class="col-xs-6"><?php echo ucwords( esc_html( $attr[0]->name ) ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></strong>
							</li>
							<?php
						}
					}
					?>
					</ul>
					<?php
				}
				?>
			</div>
			<?php
			$attributs = ob_get_clean();
			echo apply_filters( 'cardealer_get_cars_list_attribute', $attributs ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		} else {
			$year         = get_the_terms( $post->ID, 'car_year' );
			$transmission = get_the_terms( $post->ID, 'car_transmission' );
			$mileage      = get_the_terms( $post->ID, 'car_mileage' );
			if ( empty( $year ) && empty( $transmission ) && empty( $mileage ) ) {
				return;
			}
			$car_year         = '';
			$car_transmission = '';
			$car_mileage      = '';
			if ( ! is_wp_error( $year ) && isset( $year[0]->name ) ) {
				$car_year = $year[0]->name;
			}
			if ( ! is_wp_error( $year ) && isset( $transmission[0]->name ) ) {
				$car_transmission = $transmission[0]->name;
			}
			if ( ! is_wp_error( $year ) && isset( $mileage[0]->name ) ) {
				$car_mileage = $mileage[0]->name;
			}

			// @codingStandardsIgnoreStart
			$cars_grid = isset( $_COOKIE['cars_grid'] ) ? $_COOKIE['cars_grid'] : '';
			$cars_grid = isset( $_REQUEST['cars_grid'] ) ? $_REQUEST['cars_grid'] : $cars_grid;
			// @codingStandardsIgnoreEnd

			if ( '' === $cars_grid ) {
				$cars_grid = cardealer_get_cars_catlog_style();
			}

			$type    = '';
			$trn_cls = ' class="car-transmission-dots" ';
			if ( '' !== $cars_grid && 'yes' !== $cars_grid ) {
				$trn_cls = ' ';
			}

			$attributs = '<div class="car-list"><ul class="list-inline">';
			if ( ! empty( $car_year ) ) {
				$attributs .= '<li><i class="fas fa-calendar-alt"></i> ' . esc_html( $car_year ) . '</li>';
			}
			if ( ! empty( $car_transmission ) ) {
				$attributs .= '<li' . $trn_cls . 'title="' . esc_html( $car_transmission ) . '"><i class="fas fa-cog"></i> ' . esc_html( $car_transmission ) . '</li>';
			}
			if ( $car_mileage ) {
				$attributs .= '<li><i class="glyph-icon flaticon-gas-station"></i> ' . esc_html( $car_mileage ) . '</li>';
			}
			$attributs .= '</ul></div>';

			/**
			 * Filters the HTML contents which displays vehicle attributes in inventory page.
			 *
			 * @since 1.0
			 * @param string      $attributs    HTML contents which displays vehicle attributes in inventory page.
			 * @visible           true
			 */
			echo apply_filters( 'cardealer_get_cars_list_attribute', $attributs ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		}
	}
}

if ( ! function_exists( 'cardealer_cars_filter_methods' ) ) {
	/**
	 * Check filter with ajax or get method
	 */
	function cardealer_cars_filter_methods() {
		global $car_dealer_options;
		$cars_filter_with = '';
		if ( isset( $car_dealer_options['cars-filter-with'] ) ) {
			$cars_filter_with = $car_dealer_options['cars-filter-with'];
		}
		return $cars_filter_with;
	}
}

if ( ! function_exists( 'cardealer_get_cars_currency_symbol' ) ) {
	/**
	 * Get currenc currency symbol
	 */
	function cardealer_get_cars_currency_symbol() {
		global $car_dealer_options;
		$currency_symbol = '';
		if ( function_exists( 'cdhl_get_currency_symbols' ) ) {
			$currency_code   = isset( $car_dealer_options['cars-currency-symbol'] ) ? $car_dealer_options['cars-currency-symbol'] : '';
			$currency_symbol = cdhl_get_currency_symbols( $currency_code );
		} else {
			$currency_code   = isset( $car_dealer_options['cars-currency-symbol'] ) ? $car_dealer_options['cars-currency-symbol'] : '';
			$currency_symbol = $currency_code;
		}
		return $currency_symbol;
	}
}

if ( ! function_exists( 'cardealer_get_cars_currency_placement' ) ) {
	/**
	 * Get currenc currency placement
	 */
	function cardealer_get_cars_currency_placement() {
		global $car_dealer_options;

		$currency_placement = isset( $car_dealer_options['cars-currency-symbol-placement'] ) ? $car_dealer_options['cars-currency-symbol-placement'] : '';
		$placement          = 'right';
		switch ( $currency_placement ) {
			case 1:
				$placement = 'left';
				break;
			case 3:
				$placement = 'left-with-space';
				break;
			case 4:
				$placement = 'right-with-space';
				break;
			default:
				$placement = 'right';
		}
		return $placement;
	}
}

if ( ! function_exists( 'cardealer_car_price_html' ) ) {
	/**
	 * CAR Price formating
	 *
	 * @param string $class .
	 * @param string $id .
	 * @param string $tax_label .
	 * @param string $echo .
	 */
	function cardealer_car_price_html( $class = '', $id = null, $tax_label = true, $echo = true ) {
		global $car_dealer_options, $post;
		$currency_code = ( isset( $car_dealer_options['cars-currency-symbol'] ) && ! empty( $car_dealer_options['cars-currency-symbol'] ) ) ? $car_dealer_options['cars-currency-symbol'] : '';
		if ( function_exists( 'cdhl_get_currency_symbols' ) ) {
			$currency_symbol = cdhl_get_currency_symbols( $currency_code );
		} else {
			$currency_symbol = '$';
		}

		$symbol_position          = ( isset( $car_dealer_options['cars-currency-symbol-placement'] ) && ! empty( $car_dealer_options['cars-currency-symbol-placement'] ) ) ? $car_dealer_options['cars-currency-symbol-placement'] : '';
		$seperator                = ( isset( $car_dealer_options['cars-disable-currency-separators'] ) && ! empty( $car_dealer_options['cars-disable-currency-separators'] ) ) ? $car_dealer_options['cars-disable-currency-separators'] : '';
		$seperator_symbol         = ( isset( $car_dealer_options['cars-thousand-separator'] ) && ! empty( $car_dealer_options['cars-thousand-separator'] ) ) ? $car_dealer_options['cars-thousand-separator'] : '';
		$decimal_separator_symbol = ( isset( $car_dealer_options['cars-decimal-separator'] ) && ! empty( $car_dealer_options['cars-decimal-separator'] ) ) ? $car_dealer_options['cars-decimal-separator'] : '.';

		$decimal_places = ( ! empty( $car_dealer_options['cars-number-decimals'] ) && is_numeric( $car_dealer_options['cars-number-decimals'] ) ) ? $car_dealer_options['cars-number-decimals'] : 0;

		$price_html    = '';
		$regular_price = 0;
		$sale_price    = 0;
		$car_id        = ( isset( $id ) && null !== $id ) ? $id : $post->ID;
		$regular_price = function_exists( 'get_field' ) ? get_field( 'regular_price', $car_id ) : get_post_meta( $car_id, 'regular_price', $single = true );
		$sale_price    = function_exists( 'get_field' ) ? get_field( 'sale_price', $car_id ) : get_post_meta( $car_id, 'sale_price', $single = true );

		if ( ( $regular_price > 0 ) || ( $sale_price > 0 ) ) {
			$price_html = '<div class="price car-price ' . esc_attr( $class ) . '">';
			if ( 3 === (int) $symbol_position ) {
				$currency_symbol = $currency_symbol . ' ';
			} elseif ( 4 === (int) $symbol_position ) {
				$currency_symbol = ' ' . $currency_symbol;
			}

			if ( ! empty( $regular_price ) && ( $regular_price > 0 ) ) {
				$regular_price = ( isset( $seperator ) && 1 === (int) $seperator ) ? number_format( $regular_price, $decimal_places, $decimal_separator_symbol, $seperator_symbol ) : get_post_meta( $car_id, 'regular_price', $single = true );
				if ( $sale_price > 0 ) {
					$price_html .= ( 1 === (int) $symbol_position || 3 === (int) $symbol_position ) ? '<span class="old-price"> ' . esc_html( $currency_symbol . $regular_price ) . '</span>' : '<span class="old-price"> ' . esc_html( $regular_price . $currency_symbol ) . '</span>';
				} else {
					$price_html .= ( 1 === (int) $symbol_position || 3 === (int) $symbol_position ) ? '<span class="new-price"> ' . esc_html( $currency_symbol . $regular_price ) . '</span>' : '<span class="new-price"> ' . esc_html( $regular_price . $currency_symbol ) . '</span>';
				}
			}
			if ( $sale_price > 0 ) {
				$sale_price  = ( isset( $seperator ) && 1 === (int) $seperator ) ? number_format( $sale_price, $decimal_places, $decimal_separator_symbol, $seperator_symbol ) : get_post_meta( $car_id, 'sale_price', $single = true );
				$price_html .= ( 1 === (int) $symbol_position || 3 === (int) $symbol_position ) ? '<span class="new-price"> ' . esc_html( $currency_symbol . $sale_price ) . '</span>' : '<span class="new-price"> ' . esc_html( $sale_price . $currency_symbol ) . '</span>';
			}
			if ( is_single() ) {
				if ( 'related-slider' !== $class ) {
					$price_html .= cardealer_get_cars_status( $car_id );
				}
			}

			if ( is_single() && true === $tax_label ) {
				$tax_label_content = get_post_meta( $car_id, 'tax_label', $single = true );
				if ( ! empty( $tax_label_content ) ) {
					$price_html .= '<p>' . get_post_meta( $car_id, 'tax_label', $single = true ) . '<p>';
				}
			}

			$price_html .= '</div>';
		}

		// options to add in filter.
		$options = array(
			'class'                     => $class,
			'id'                        => $car_id,
			'tax_label'                 => $tax_label,
			'currency_symbol'           => $currency_symbol,
			'symbol_position'           => $symbol_position,
			'seperator'                 => $seperator,
			'thousand_seperator_symbol' => $seperator_symbol,
			'decimal_separator_symbol'  => $decimal_separator_symbol,
			'decimal_places'            => $decimal_places,
			'currency_code'             => $currency_code,
		);
		/**
		 * Filters the HTML layout of the vehicle price.
		 *
		 * @since 1.0
		 *
		 * @param string    $price_html HTML layout of the vehicle price.
		 * @param array     $options    Array of price elements used to build price HTML.
		 * @visible         true
		 */
		$price_html = apply_filters( 'cardealer_vehicle_price_html_body', $price_html, $options );

		/**
		 * Filters the HTML layout of the vehicle price.
		 *
		 * @since 1.0
		 *
		 * @param string      $price_html HTML layout of the vehicle price.
		 * @param int         $car_id      Vehicle ID.
		 * @visible           true
		 */
		if ( $echo ) {
			echo wp_kses(
				apply_filters( 'cardealer_car_price_html', $price_html, $car_id ),
				array(
					'div'  => array(
						'class' => true,
					),
					'p'    => array(),
					'span' => array(
						'class' => true,
					),
				)
			);
		} else {
			return apply_filters( 'cardealer_car_price_html', $price_html, $car_id );
		}
	}
}

if ( ! function_exists( 'cardealer_get_car_price' ) ) {
	/**
	 * CAR Price formating with retur value
	 *
	 * @param string $class .
	 * @param string $id .
	 */
	function cardealer_get_car_price( $class = '', $id = null ) {
		global $car_dealer_options,$post;
		$currency_code     = isset( $car_dealer_options['cars-currency-symbol'] ) ? $car_dealer_options['cars-currency-symbol'] : '';
		$currency_symbol   = cdhl_get_currency_symbols( $currency_code );
		$price_html        = '<div class="price car-price ' . $class . '">';
			$regular_price = 0;
		$sale_price        = 0;
			$car_id        = ( isset( $id ) && null !== $id ) ? $id : $post->ID;
			$regular_price = get_post_meta( $car_id, 'regular_price', $single = true );
			$regular_price = floatval( $regular_price );
			$sale_price    = get_post_meta( $car_id, 'sale_price', $single = true );
			$sale_price    = floatval( $sale_price );
		if ( $regular_price > 0 && $sale_price > 0 ) {
			$price_html .= '<span class="old-price"> ' . esc_html( $currency_symbol . $regular_price ) . '</span>';
			$price_html .= '<span class="new-price"> ' . esc_html( $currency_symbol . $sale_price ) . '</span>';
		} elseif ( 0 === $regular_price || empty( $regular_price ) && $sale_price > 0 ) {
			$price_html .= '<span class="new-price"> ' . esc_html( $currency_symbol . $sale_price ) . '</span>';
		} elseif ( 0 === $sale_price || empty( $sale_price ) && $regular_price > 0 ) {
			$price_html .= '<span class="new-price"> ' . esc_html( $currency_symbol . $regular_price ) . '</span>';
		} else {
			$price_html .= '<span class="new-price"> ' . esc_html( $currency_symbol ) . '0.00</span>';
		}
		$price_html .= '</div>';
		return $price_html;
	}
}

if ( ! function_exists( 'cardealer_get_car_price_array' ) ) {
	/**
	 * CAR Price array
	 *
	 * @param string $id .
	 */
	function cardealer_get_car_price_array( $id = null ) {
		global $car_dealer_options,$post;
		$currency_code   = $car_dealer_options['cars-currency-symbol'];
		$currency_symbol = cdhl_get_currency_symbols( $currency_code );
		$price_arr       = array();
		$regular_price   = 0;
		$sale_price      = 0;
		$car_id          = ( isset( $id ) && null !== $id ) ? $id : $post->ID;
		$regular_price   = get_post_meta( $car_id, 'regular_price', $single = true );
		$regular_price   = (int) $regular_price;
		$sale_price      = get_post_meta( $car_id, 'sale_price', $single = true );
		$sale_price      = (int) $sale_price;
		if ( $regular_price > 0 && $sale_price > 0 ) {
			$price_arr = array(
				'currency_symbol' => $currency_symbol,
				'regular_price'   => $regular_price,
				'sale_price'      => $sale_price,
			);
		} elseif ( 0 === $regular_price || empty( $regular_price ) && $sale_price > 0 ) {
			$price_arr = array(
				'currency_symbol' => $currency_symbol,
				'regular_price'   => 0,
				'sale_price'      => $sale_price,
			);
		} elseif ( 0 === $sale_price || empty( $sale_price ) && $regular_price > 0 ) {
			$price_arr = array(
				'currency_symbol' => $currency_symbol,
				'regular_price'   => $regular_price,
				'sale_price'      => 0,
			);
		} else {
			$price_arr = array(
				'currency_symbol' => $currency_symbol,
				'regular_price'   => 0,
				'sale_price'      => 0,
			);
		}
		return $price_arr;
	}
}

if ( ! function_exists( 'cardealer_get_car_price' ) ) {
	/**
	 * Set template on search cars in cars catalog page
	 *
	 * @param string $template .
	 */
	function cardealer_template_chooser( $template ) {
		global $wp_query, $car_dealer_options;
		if ( $wp_query->is_search && is_post_type_archive( 'cars' ) ) {
			return locate_template( 'archive-cars.php' );  // redirect to archive-search.php .
		} elseif ( is_post_type_archive( 'cars' ) || ( isset( $car_dealer_options['cars_inventory_page'] ) && '' !== $car_dealer_options['cars_inventory_page'] && is_page( $car_dealer_options['cars_inventory_page'] ) ) ) { // if cars post type and archive page.
			return locate_template( 'archive-cars.php' );  // redirect to archive-search.php .
		}
		return $template;
	}
	add_filter( 'template_include', 'cardealer_template_chooser' );
}

if ( ! function_exists( 'cardealer_get_carplaceholder' ) ) {
	/**
	 * Default cars placeholder image
	 *
	 * @param string $size .
	 * @param string $return_type .
	 */
	function cardealer_get_carplaceholder( $size = '', $return_type = 'image' ) {
		$url  = CDHL_URL;
		$url .= 'images/carplaceholder.jpg';
		if ( '' !== $size ) {
			if ( 'car_thumbnail' === $size ) {
				$meta = 'width="190" height="138"';
			} elseif ( 'car_catalog_image' === $size ) {
				if ( ( is_post_type_archive( 'cars' ) && ! wp_is_mobile() ) || ( isset( $_POST['action'] ) && 'cardealer_cars_filter_query' === $_POST['action'] ) ) {
					$getlayout = cardealer_get_cars_list_layout_style();
					$col       = cardealer_get_grid_column();
					if ( isset( $getlayout ) && ! empty( $getlayout ) ) {
						if ( in_array( $getlayout, array( 'view-grid-full', 'view-grid-masonry-full', 'view-list-left', 'view-list-right', 'view-list-full' ) ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
							$col = 3;
						}
					}
					if ( 4 === $col ) {
						$meta = 'width="187" height="134"';
					} else {
						$meta = 'width="265" height="190"';
					}
				} else {
					$meta = 'width="265" height="190"';
				}
			} elseif ( 'car_list_thumbnail' === $size ) {
				$meta = 'width="110" height="79"';
			} elseif ( 'large' === $size || 'car_single_image' === $size ) {
				$meta = 'class="img-responsive"';
			} elseif ( 'car_tabs_image' === $size ) {
				$meta = 'class="img-responsive"';
			} elseif ( 'cardealer-50x50' === $size ) {
				$meta = 'width="50" height="50"';
			} else {
				$meta = 'width="265" height="190"';
			}
		}
		if ( isset( $_POST['action'] ) && 'cardealer_load_more_vehicles' === $_POST['action'] && 'image' === $return_type ) {
			return '<img class="lazyload img-responsive" ' . $meta . ' alt="carplaceholder" data-src="' . $url . '"/>';
		}
		return ( ! empty( $return_type ) && 'image' === $return_type ) ? '<img src="' . esc_url( $url ) . '" class="img-responsive" ' . $meta . ' alt="carplaceholder"/>' : $url;
	}
}

if ( ! function_exists( 'cardealer_get_cars_status' ) ) {
	/**
	 * Get cars status
	 *
	 * @param string $car_id .
	 * @param string $echo .
	 */
	function cardealer_get_cars_status( $car_id = null, $echo = false ) {

		if ( ! $car_id ) {
			return false;
		}

		$html       = '';
		$car_status = '';

		if ( function_exists( 'get_field' ) ) {
			$car_status = get_field( 'car_status', $car_id );
			if ( isset( $car_status ) && ! empty( $car_status ) ) {
				if ( 'sold' === $car_status ) {
					$html = '<span class="label car-status ' . $car_status . '">' . esc_html__( 'SOLD', 'cardealer' ) . '</span>';
				}
			}
		}

		/**
		 * Filters the HTML of the vehicle status(SOLD/UNSOLD).
		 *
		 * @since 1.0
		 *
		 * @param string      $html  HTML of the vehicle status badge.
		 * @param int         $car_id Vehicle ID.
		 * @visible           true
		 */

		$html = apply_filters( 'cardealer_get_cars_status', $html, $car_id );
		if ( $echo ) {
			echo wp_kses(
				$html,
				array(
					'span' => array(
						'class' => true,
					),
				)
			);
		} else {
			return $html;
		}
	}
}

if ( ! function_exists( 'cardealer_get_cars_condition' ) ) {
	/**
	 * Get cars condition
	 *
	 * @param string $id .
	 * @param string $echo .
	 */
	function cardealer_get_cars_condition( $id = null, $echo = false ) {
		global $car_dealer_options;
		if ( ! $id || ( isset( $car_dealer_options['display-condition-tags'] ) && 'no' === $car_dealer_options['display-condition-tags'] ) ) {
			return false;
		}

		$html  = '';
		$args  = array(
			'orderby' => 'name',
			'order'   => 'ASC',
			'fields'  => 'all',
		);
		$terms = wp_get_post_terms( $id, 'car_condition', $args );

		if ( ! is_wp_error( $terms ) && isset( $terms ) && ! empty( $terms ) ) {
			$is_wpml   = cardealer_is_wpml_active();
			$term_name = $terms[0]->name;
			if ( $is_wpml ) {
				$lang_term_name = cardealer_get_term_for_default_lang( $terms[0]->term_id, 'car_condition' );
				$term_name      = $lang_term_name->name;
			}

			if ( preg_match( '(new|New)', $term_name ) === 1 ) {
				$class = 'new';
			} elseif ( preg_match( '(used|Used)', $term_name ) === 1 ) {
				$class = 'used';
			} elseif ( preg_match( '(certified|Certified)', $term_name ) === 1 ) {
				$class = 'certified';
			} else {
				$class = $terms[0]->slug;
			}

			$color = get_term_meta( $terms[0]->term_id, 'label_color', true );
			$html  = '<span class="label car-condition ' . esc_attr( $class ) . '"';
			if ( $color ) {
				$html .= ' style="background:' . esc_attr( $color ) . '"';
			}
			$html .= ' >' . esc_html( $terms[0]->name ) . '</span>';
		}
		/**
		 * Filters the HTML of the vehicle condition tag.
		 *
		 * @since 1.0
		 *
		 * @param string       $html HTML of the vehicle condition tag.
		 * @param int          $id   Vehicle ID.
		 * @visible            true
		 */

		$html = apply_filters( 'cardealer_get_cars_condition', $html, $id );
		if ( $echo ) {
			echo wp_kses(
				$html,
				array(
					'span' => array(
						'class' => true,
						'style' => true,
					),
				)
			);
		} else {
			return $html;
		}
	}
}

if ( ! function_exists( 'cardealer_get_term_for_default_lang' ) ) {
	/**
	 * Get default language terms object
	 *
	 * @param string $term .
	 * @param string $taxonomy .
	 */
	function cardealer_get_term_for_default_lang( $term, $taxonomy ) {
			global $sitepress;
			global $icl_adjust_id_url_filter_off;
			$term_id                      = is_int( $term ) ? $term : $term->term_id;
			$default_term_id              = (int) icl_object_id( $term_id, $taxonomy, true, $sitepress->get_default_language() );
			$orig_flag_value              = $icl_adjust_id_url_filter_off;
			$icl_adjust_id_url_filter_off = true;
			$term                         = get_term( $default_term_id, $taxonomy );
			$icl_adjust_id_url_filter_off = $orig_flag_value;
			return $term;
	}
}
if ( ! function_exists( 'cardealer_is_wpml_active' ) ) {
	/**
	 * Check if WPML is active
	 *
	 * @return bool
	 */
	function cardealer_is_wpml_active() {
		return ( class_exists( 'SitePress' ) ? true : false );
	}
}

if ( ! function_exists( 'cardealer_get_cars_image' ) ) {
	/**
	 * Get cars images
	 *
	 * @param string $car_size .
	 * @param string $id .
	 */
	function cardealer_get_cars_image( $car_size = 'car_catalog_image', $id = null ) {
		if ( empty( $car_size ) ) {
			$car_size = 'car_catalog_image';
		}
		global $post;
		$car_id = ( isset( $id ) && null !== $id ) ? $id : $post->ID;
		if ( function_exists( 'get_field' ) ) {
			$images = get_field( 'car_images', $car_id );

			if ( isset( $images ) && ! empty( $images ) ) {
				if ( ( isset( $_POST['action'] ) && 'cardealer_load_more_vehicles' === $_POST['action'] ) ) {
					$img = '<img class="img-responsive" alt="' . esc_attr( $images[0]['alt'] ) . '" width="' . esc_attr( $images[0]['sizes'][ $car_size . '-width' ] ) . '" height="' . esc_attr( $images[0]['sizes'][ $car_size . '-height' ] ) . '" src="' . esc_url( $images[0]['sizes'][ $car_size ] ) . '"/>';
				} elseif ( cardealer_lazyload_enabled() ) {
					$img = '<img class="img-responsive cardealer-lazy-load" alt="' . esc_attr( $images[0]['alt'] ) . '" width="' . esc_attr( $images[0]['sizes'][ $car_size . '-width' ] ) . '" height="' . esc_attr( $images[0]['sizes'][ $car_size . '-height' ] ) . '" src="' . esc_url( LAZYLOAD_IMG ) . '" data-src="' . esc_url( $images[0]['sizes'][ $car_size ] ) . '"/>';
				} else {
					if( isset($images[0]['sizes'][ $car_size ]) ) {
						$img = '<img class="img-responsive" src="' . esc_url( $images[0]['sizes'][ $car_size ] ) . '" alt="' . esc_attr( $images[0]['alt'] ) . '" width="' . esc_attr( $images[0]['sizes'][ $car_size . '-width' ] ) . '" height="' . esc_attr( $images[0]['sizes'][ $car_size . '-height' ] ) . '"/>';
					} else {
						$img = cardealer_get_carplaceholder( $car_size );
					}
				}
			} else {
				$img = cardealer_get_carplaceholder( $car_size );
			}
		} else {
			$img = cardealer_get_carplaceholder( $car_size );
		}
		return $img;
	}
}

if ( ! function_exists( 'cardealer_get_cars_owl_image' ) ) {

	/**
	 * Get cars images for owl carousal
	 *
	 * @param string $car_size .
	 * @param string $id .
	 */
	function cardealer_get_cars_owl_image( $car_size = 'car_catalog_image', $id = null ) {
		if ( empty( $car_size ) ) {
			$car_size = 'car_catalog_image';
		}
		global $post;
		$car_id = ( isset( $id ) && null !== $id ) ? $id : $post->ID;
		if ( function_exists( 'get_field' ) ) {
			$images = get_field( 'car_images', $car_id );
			if ( isset( $images ) && ! empty( $images ) ) {
				if ( ( isset( $_POST['action'] ) && 'cardealer_load_more_vehicles' === $_POST['action'] ) || cardealer_lazyload_enabled() ) {
					$img = '<img class="img-responsive owl-lazy" alt="' . esc_attr( $images[0]['alt'] ) . '" width="' . esc_attr( $images[0]['sizes'][ $car_size . '-width' ] ) . '" height="' . esc_attr( $images[0]['sizes'][ $car_size . '-height' ] ) . '" src="' . esc_url( LAZYLOAD_IMG ) . '" data-src="' . esc_url( $images[0]['sizes'][ $car_size ] ) . '"/>';
				} else {
					$img = '<img class="img-responsive" src="' . esc_url( $images[0]['sizes'][ $car_size ] ) . '" alt="' . esc_attr( $images[0]['alt'] ) . '" width="' . esc_attr( $images[0]['sizes'][ $car_size . '-width' ] ) . '" height="' . esc_attr( $images[0]['sizes'][ $car_size . '-height' ] ) . '"/>';
				}
			} else {
				$img = cardealer_get_carplaceholder( $car_size );
			}
		} else {
			$img = cardealer_get_carplaceholder( $car_size );
		}
		return $img;
	}
}
if ( ! function_exists( 'cardealer_get_single_image_url' ) ) {
	/**
	 * Single image url
	 *
	 * @param string $car_size .
	 * @param string $id .
	 */
	function cardealer_get_single_image_url( $car_size = 'car_catalog_image', $id = null ) {
		$car_id = '';
		$url    = '';
		global $post;
		if ( function_exists( 'get_field' ) ) {
			if ( isset( $id ) && ! empty( $id ) ) {
				$car_id = $id;
			} elseif ( isset( $post ) ) {
				$car_id = $post->ID;
			}
			$car_images = get_field( 'car_images', $car_id );
			if ( ! empty( $car_images ) && isset($car_images[0]['url']) ) {
				$url = $car_images[0]['url'];
			}
		} elseif ( defined( 'CDHL_URL' ) ) {
			$url  = CDHL_URL;
			$url .= 'images/carplaceholder.jpg';
		}
		return $url;
	}
}

if ( ! function_exists( 'cardealer_get_images_url' ) ) {
	/**
	 * Image url
	 *
	 * @param string $car_size .
	 * @param string $id .
	 */
	function cardealer_get_images_url( $car_size = 'car_catalog_image', $id = null ) {
		global $post;
		$url = null;
		if ( function_exists( 'get_field' ) ) {
			if ( isset( $id ) && ! empty( $id ) ) {
				$car_id = $id;
			} elseif ( isset( $post ) ) {
				$car_id = $post->ID;
			}
			$car_images = get_field( 'car_images', $car_id );
			$url        = array();
			if ( ! empty( $car_images ) ) {
				foreach ( $car_images as $car_image ) {
					$url[] = $car_image['url'];
				}
			}
		}
		return $url;
	}
}
if ( ! function_exists( 'cardealer_get_price_filters' ) ) :
	/**
	 * Price filter
	 */
	function cardealer_get_price_filters() {
		global $car_dealer_options;
		$pgs_min_price = isset( $_GET['min_price'] ) ? sanitize_text_field( wp_unslash( $_GET['min_price'] ) ) : '';
		$pgs_max_price = isset( $_GET['max_price'] ) ? sanitize_text_field( wp_unslash( $_GET['max_price'] ) ) : '';

		// Find min and max price in current result set.
		$prices = cardealer_get_car_filtered_price();
		$min    = floor( $prices->min_price );
		$max    = ceil( $prices->max_price );

		if ( $min === $max ) {
			return;
		}

		// Range Slider Step.
		$step = 100;
		if ( isset( $car_dealer_options['price_range_step'] ) && ! empty( $car_dealer_options['price_range_step'] ) ) {
			$step = $car_dealer_options['price_range_step'];
		}

		$html          = '';
		$html         .= '<div class="price_slider_wrapper">';
		$html         .= '<div class="price-slide">';
			$html     .= '<div class="price">';
				$html .= '<input type="hidden" id="pgs_min_price" name="min_price" value="' . esc_attr( $pgs_min_price ) . '" data-min="' . esc_attr( $min ) . '" />';
				$html .= '<input type="hidden" id="pgs_max_price" name="max_price" value="' . esc_attr( $pgs_max_price ) . '" data-max="' . esc_attr( $max ) . '" data-step="' . esc_attr( $step ) . '"/>';
				$html .= '<label for="dealer-slider-amount">' . esc_html__( 'Price Range', 'cardealer' ) . '</label>';
				$html .= '<input type="text" id="dealer-slider-amount" class="dealer-slider-amount" readonly="" class="amount" value="" />';
				$html .= '<div id="slider-range" class="slider-range"></div>';
			$html     .= '</div>';
		$html         .= '</div>';
		$html         .= '</div>';

		/**
		 * Filters the vehicle price slider HTML layout.
		 *
		 * @since 1.0
		 * @param string     $html HTML string of the vehicle price slider HTML layout.
		 * @visible          true
		 */
		$html = apply_filters( 'car_dealer_price_slider_html', $html );
		echo wp_kses(
			$html,
			array(
				'div'    => array(
					'class' => true,
					'id'    => true,
				),
				'input'  => array(
					'class'     => true,
					'type'      => true,
					'id'        => true,
					'name'      => true,
					'value'     => true,
					'data-min'  => true,
					'data-max'  => true,
					'data-step' => true,
					'readonly'  => true,
				),
				'label'  => array(
					'class' => true,
					'for'   => true,
				),
				'button' => array(
					'class' => true,
					'id'    => true,
				),
			)
		);
	}
endif;


if ( ! function_exists( 'cardealer_get_year_range_filters' ) ) :
	/**
	 * Year rang filter
	 *
	 * @param string $cfb .
	 */
	function cardealer_get_year_range_filters( $cfb = '' ) {
		$pgs_year_range_min = isset( $_GET['min_year'] ) ? sanitize_text_field( wp_unslash( $_GET['min_year'] ) ) : '';
		$pgs_year_range_max = isset( $_GET['max_year'] ) ? sanitize_text_field( wp_unslash( $_GET['max_year'] ) ) : '';

		// Find min and max price in current result set.
		$year_range = ( function_exists( 'cardealer_get_year_range' ) ) ? cardealer_get_year_range() : '';

		if ( empty( $year_range ) ) {
			return;
		}

		$yearmin = floor( $year_range['min_year'] );
		$yearmax = ceil( $year_range['max_year'] );

		if ( $yearmin === $yearmax ) {
			return;
		}
		$html          = '';
		$html         .= '<div class="year_range_slider_wrapper">';
		$html         .= '<div class="year-range-slide">';
			$html     .= '<div class="year_range">';
				$html .= '<input type="hidden" id="pgs_year_range_min" name="min_year" value="' . esc_attr( $pgs_year_range_min ) . '" data-yearmin="' . esc_attr( $yearmin ) . '" />';
				$html .= '<input type="hidden" id="pgs_year_range_max" name="max_year" value="' . esc_attr( $pgs_year_range_max ) . '" data-yearmax="' . esc_attr( $yearmax ) . '" />';

				$html .= '<label for="dealer-slider-year-range">' . esc_html__( 'Year Range', 'cardealer' ) . '</label>';
				$html .= '<input type="text" id="dealer-slider-year-range" class="dealer-slider-year-range" readonly="" class="amount" value="" />';
				$html .= '<div id="slider-year-range" class="slider-year-range" data-cfb="' . $cfb . '"></div>';
			$html     .= '</div>';
		$html         .= '</div>';
		$html         .= '</div>';
		return apply_filters( 'cardealer_year_range_filters', $html );
	}
endif;

if ( ! function_exists( 'cardealer_is_year_range_active' ) ) :
	/**
	 * Check year rang slider is active for listing page
	 */
	function cardealer_is_year_range_active() {
		$year_rang_slider = true;
		global $car_dealer_options;
		$cars_year_rang = ( isset( $car_dealer_options['cars-year-range-slider'] ) ) ? $car_dealer_options['cars-year-range-slider'] : 'no';
		if ( 'no' === (string) $cars_year_rang ) {
			$year_rang_slider = false;
		}
		return $year_rang_slider;
	}
endif;

if ( ! function_exists( 'cardealer_get_car_filtered_price' ) ) {
	/**
	 * Get filtered min price for current list.
	 *
	 * @return int
	 */
	function cardealer_get_car_filtered_price() {
		global $wpdb,$car_dealer_options;

		// @codingStandardsIgnoreStart

		// Current site prefix.
		$end_condition = '';
		$tbprefix      = $wpdb->prefix;
		$sql           = 'SELECT ';
		$sql          .= ' min( FLOOR( price_meta.meta_value ) ) as min_price,';
		$sql          .= ' max( CEILING( price_meta.meta_value ) ) as max_price';
		$sql          .= ' FROM ' . $tbprefix . 'posts';

		$sql .= ' LEFT JOIN ' . $tbprefix . 'postmeta as price_meta ON ' . $tbprefix . 'posts.ID = price_meta.post_id';
		if ( is_tax( 'vehicle_cat' ) ) {
			global $wp_query;
			$term_id       = get_term_by( 'slug', $wp_query->query_vars['vehicle_cat'], 'vehicle_cat' );
			$end_condition = ' AND ' . $tbprefix . 'term_relationships.term_taxonomy_id=' . $term_id->term_taxonomy_id;
			$sql          .= ' LEFT JOIN ' . $tbprefix . 'term_relationships ON (' . $tbprefix . 'posts.ID = ' . $tbprefix . 'term_relationships.object_id)';
		}
		$sql .= ' INNER JOIN ' . $tbprefix . 'postmeta ON (' . $tbprefix . 'posts.ID = ' . $tbprefix . 'postmeta.post_id )';
		$sql .= ' WHERE ' . $tbprefix . "posts.post_type IN ('cars')";
		$sql .= ' AND ' . $tbprefix . "posts.post_status = 'publish'";
		$sql .= " AND price_meta.meta_key IN ('final_price')$end_condition";
		if ( isset( $car_dealer_options['car_no_sold'] ) && 0 === (int) $car_dealer_options['car_no_sold'] ) {
			$sql .= ' AND ( ( ' . $tbprefix . "postmeta.meta_key = 'car_status' AND " . $tbprefix . "postmeta.meta_value != 'sold' ) )";
		}

		$price_arr = $wpdb->get_row( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		// code for price step theme option.
		if ( isset( $car_dealer_options['price_range_step'] ) && ! empty( $car_dealer_options['price_range_step'] ) ) {
			$min_difference        = $car_dealer_options['price_range_step'] - ( $price_arr->min_price % $car_dealer_options['price_range_step'] );
			$price_arr->min_price += $min_difference - $car_dealer_options['price_range_step']; // Round up min price.
			$max_difference        = $car_dealer_options['price_range_step'] - ( $price_arr->max_price % $car_dealer_options['price_range_step'] );
			$price_arr->max_price += $max_difference; // Round up max price.
		}

		return apply_filters( 'cd_vehicle_filtered_price', $price_arr );

		// @codingStandardsIgnoreEnd
	}
}
if ( ! function_exists( 'cardealer_get_year_range' ) ) {
	/**
	 * Get filtered year range.
	 *
	 * @return int
	 */
	function cardealer_get_year_range() {
		global $wpdb;
		$terms = get_terms(
			array(
				'taxonomy'   => 'car_year',
				'hide_empty' => true,
				'order'      => 'ASC',
			)
		);

		$taxonomy_name = get_taxonomy( 'car_year' );
		$data          = array();
		if ( ! empty( $taxonomy_name ) ) {
			$slug  = $taxonomy_name->rewrite['slug'];
			$label = $taxonomy_name->labels->menu_name;

			if ( ! empty( $terms ) ) {
				$year_arr = array();
				foreach ( $terms as $tdata ) {
					$year_arr[] = $tdata->slug;
				}
				$first = reset( $year_arr );
				$last  = end( $year_arr );
				$data  = array(
					'min_year' => $first,
					'max_year' => $last,
				);
			}
		}
		/**
		 * Filters the year range to be used in inventory filter.
		 *
		 * @since 1.0
		 * @param array     $data Year range array - minimum year and maximum year.
		 * @hooked cardealer_list_layout_style_lazyload - 10
		 * @visible          true
		 */
		return apply_filters( 'cardealer_year_range', $data );
	}
}

if ( ! function_exists( 'cardealer_get_cars_list_layout_style' ) ) {
	/**
	 * Add layout style in cookie
	 */
	function cardealer_get_cars_list_layout_style() {
		global $car_dealer_options;

		// @codingStandardsIgnoreStart
		$getlayout = '';
		$getlayout = ( isset( $car_dealer_options['cars-lay-style'] ) && ! empty( $car_dealer_options['cars-lay-style'] ) ) ? $car_dealer_options['cars-lay-style'] : 'view-grid-left';
		if ( isset( $_REQUEST['lay_style'] ) && ! empty( $_REQUEST['lay_style'] ) ) {
			$getlayout = $_REQUEST['lay_style']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		} elseif ( isset( $_COOKIE['lay_style'] ) && ! empty( $_COOKIE['lay_style'] ) ) {
			$getlayout = $_COOKIE['lay_style']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		}
		// @codingStandardsIgnoreEnd

		/**
		 * Filters the layout style option for inventory listing(grid/list).
		 *
		 * @since 1.0
		 * @param  string    $getlayout Layout style selected for vehicle listing.
		 * @hooked cardealer_list_layout_style_lazyload - 10
		 * @visible          true
		 */
		return apply_filters( 'cardealer_list_layout_style', $getlayout );
	}
}

if ( ! function_exists( 'cardealer_get_cars_catlog_style' ) ) {
	/**
	 * Catalog style
	 */
	function cardealer_get_cars_catlog_style() {
		$getlayout = cardealer_get_cars_list_layout_style();
		if ( isset( $getlayout ) ) {
			switch ( $getlayout ) {
				case 'view-grid-left':
				case 'view-grid-full':
				case 'view-grid-right':
				case 'view-grid-masonry-left':
				case 'view-grid-masonry-full':
				case 'view-grid-masonry-right':
					return 'yes';
				break;
				case 'view-list-left':
				case 'view-list-full':
				case 'view-list-right':
					return 'no';
				break;
			}
		}
	}
}

if ( ! function_exists( 'cardealer_get_default_sort_by' ) ) {
	/**
	 * Get default listing sort by dropdown option value
	 */
	function cardealer_get_default_sort_by() {
		global $car_dealer_options;
		$cars_orderby = '';
		if ( isset( $car_dealer_options['cars-default-sort-by'] ) ) {
			$cars_orderby = $car_dealer_options['cars-default-sort-by'];
		}
		return $cars_orderby;
	}
}

if ( ! function_exists( 'cardealer_get_default_sort_by_order' ) ) {
	/**
	 * Get default listing order by value
	 */
	function cardealer_get_default_sort_by_order() {
		global $car_dealer_options;
		$cars_order = 'desc';
		if ( isset( $car_dealer_options['cars-default-sort-by-order'] ) ) {
			$cars_order = $car_dealer_options['cars-default-sort-by-order'];
		}
		return $cars_order;
	}
}
if ( ! function_exists( 'cardealer_cars_content_class' ) ) {
	/**
	 * Cars content class
	 *
	 * @param string $extra_classes .
	 */
	function cardealer_cars_content_class( $extra_classes = '' ) {
		$left_class  = 3;
		$right_class = 3;
		global $car_dealer_options;
		$getlayout = cardealer_get_cars_list_layout_style();
		if ( isset( $getlayout ) && ! empty( $getlayout ) ) {
			if ( in_array( $getlayout, array( 'view-grid-left', 'view-grid-masonry-left', 'view-grid-right', 'view-grid-masonry-right' ) ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
				$content_class = 12 - $right_class;
			} elseif ( ( 'view-list-left' === (string) $getlayout ) || ( 'view-list-right' === (string) $getlayout ) ) {
				$content_class = 12 - $left_class;
			} else {
				$content_class = 12;
			}
			$classes   = array( 'content' );
			$classes[] = 'col-lg-' . $content_class . ' col-md-' . $content_class . ' col-sm-' . $content_class;
			if ( ! empty( $extra_classes ) ) {
				$classes[] = $extra_classes;
			}
			echo 'class="' . join( ' ', $classes ) . '"'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		}
	}
}
if ( ! function_exists( 'cardealer_get_grid_column' ) ) {
	/**
	 * Grid column
	 */
	function cardealer_get_grid_column() {
		global $car_dealer_options, $sold_vehicle_pg;
		$col       = 3;
		$classes   = array();
		$getlayout = '';
		if ( isset( $car_dealer_options['vehicle-listing-layout'] ) && ( 'lazyload' === $car_dealer_options['vehicle-listing-layout'] ) && ( true !== $sold_vehicle_pg ) ) {
			$col = 5;
		} else {
			if ( isset( $car_dealer_options['cars-col-sel'] ) && ! empty( $car_dealer_options['cars-col-sel'] ) ) {
				$col = $car_dealer_options['cars-col-sel'];
			}

			$getlayout = cardealer_get_cars_list_layout_style();
			if ( isset( $getlayout ) && ! empty( $getlayout ) ) {
				if ( 'view-grid-full' === $getlayout ) {
					$col = 4;
				}
				if ( 'view-list-left' === $getlayout ) {
					$col = 4;
				} elseif ( 'view-list-right' === $getlayout ) {
					$col = 4;
				} elseif ( 'view-list-full' === $getlayout ) {
					$col = 4;
				}
			}
		}
		return $col;
	}
}
if ( ! function_exists( 'cardealer_grid_view_class' ) ) {
	/**
	 * Grid view class
	 */
	function cardealer_grid_view_class() {
		global $car_dealer_options,$cars_loop;
		$classes = array();
		$columns = cardealer_get_grid_column();
		if ( 3 === $columns || '3' === $columns ) {
			$col = 4;
		}
		if ( 4 === $columns || '4' === $columns ) {
			$col = 3;
		}
		$getlayout = cardealer_get_cars_list_layout_style();
		if ( isset( $getlayout ) && ! empty( $getlayout ) && 5 !== $columns ) {
			if ( ( 'view-grid-left' === $getlayout ) || ( 'view-grid-masonry-left' === $getlayout ) ) {
				$classes[] = 'col-lg-' . $col . ' col-md-' . $col . ' col-sm-' . $col . ' col-xs-6';
			} elseif ( ( 'view-grid-right' === $getlayout ) || ( 'view-grid-masonry-right' === $getlayout ) ) {
				$classes[] = 'col-lg-' . $col . ' col-md-' . $col . ' col-sm-' . $col . ' col-xs-6';
			} elseif ( ( 'view-grid-full' === $getlayout ) || ( 'view-grid-masonry-full' === $getlayout ) ) {
				$classes[] = 'col-lg-3 col-md-3 col-sm-3 col-xs-6';
			}
			if ( in_array( $getlayout, array( 'view-grid-masonry-left', 'view-grid-masonry-right', 'view-grid-masonry-full' ) ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
				$classes[] = 'masonry-item';
			}
			$grid_view_class = 'class="' . join( ' ', $classes ) . '"';
			/**
			 * Filters the classes of grid view style for the inventory listing.
			 *
			 * @since 1.0
			 * @param string     $grid_view_class Class for grid listing of vehicles.
			 * @visible          true
			 */
			echo apply_filters( 'cardealer_grid_view_classes', $grid_view_class ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		} elseif ( 5 === $columns ) {
			$classes[]       = 'cd-lazy-load-item masonry-item';
			$grid_view_class = 'class="' . join( ' ', $classes ) . '"';
			/**
			 * Filters the classes of grid view style for the inventory listing.
			 *
			 * @since 1.0
			 *
			 * @param string     $grid_view_class Class for grid listing of vehicles.
			 * @visible          true
			 */
			echo apply_filters( 'cardealer_grid_view_classes', $grid_view_class ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		}
	}
}

if ( ! function_exists( 'cardealer_cars_loop' ) ) {
	/**
	 * Cars loop
	 */
	function cardealer_cars_loop() {
		global $cars_loop;
		$cars_loop['loop']    = ! empty( $cars_loop['loop'] ) ? $cars_loop['loop'] + 1 : 1;
		$col                  = cardealer_get_grid_column();
		$cars_loop['columns'] = max( 1, ! empty( $cars_loop['columns'] ) ? $cars_loop['columns'] : $col );
		if ( 0 === ( $cars_loop['loop'] - 1 ) % $cars_loop['columns'] || 1 === $cars_loop['columns'] ) {
			return 'first';
		} elseif ( 0 === $cars_loop['loop'] % $cars_loop['columns'] ) {
			return 'last';
		} else {
			return '';
		}
	}
}
if ( ! function_exists( 'cardealer_list_view_class_1' ) ) {
	/**
	 * Function to get list layout class for first section
	 */
	function cardealer_list_view_class_1() {
		global $car_dealer_options;
		$classes    = array();
		$getlayout  = cardealer_get_cars_list_layout_style();
		$list_style = cardealer_get_inv_list_style();
		if ( isset( $getlayout ) && ! empty( $getlayout ) ) {
			if ( ( 'view-list-left' === $getlayout ) || ( 'view-list-right' === $getlayout ) ) {
				if ( 'classic' === $list_style ) {
					$classes[] = 'col-lg-4 col-md-5 col-sm-4';
				} else {
					$classes[] = 'col-lg-4 col-md-4 col-sm-4';
				}
			} elseif ( 'view-list-full' === $getlayout ) {
				if ( 'classic' === $list_style ) {
					$classes[] = 'col-lg-3 col-md-3 col-sm-4';
				} else {
					$classes[] = 'col-lg-3 col-md-4 col-sm-6';
				}
			}
			echo 'class="' . join( ' ', $classes ) . '"'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		}
	}
}

if ( ! function_exists( 'cardealer_list_view_class_2' ) ) {
	/**
	 * List view class 2
	 */
	function cardealer_list_view_class_2() {
		global $car_dealer_options;
		$classes    = array();
		$getlayout  = cardealer_get_cars_list_layout_style();
		$list_style = cardealer_get_inv_list_style();
		if ( isset( $getlayout ) && ! empty( $getlayout ) ) {
			if ( ( 'view-list-left' === $getlayout ) || ( 'view-list-right' === $getlayout ) ) {
				if ( 'classic' === $list_style ) {
					$classes[] = 'col-lg-8 col-md-7 col-sm-8';
				} else {
					$classes[] = 'col-lg-8 col-md-8 col-sm-8';
				}
			} elseif ( 'view-list-full' === $getlayout ) {
				if ( 'classic' === $list_style ) {
					$classes[] = 'col-lg-9 col-md-9 col-sm-8';
				} else {
					$classes[] = 'col-lg-9 col-md-8 col-sm-6';
				}
			}
			echo 'class="' . join( ' ', $classes ) . '"'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		}
	}
}
if ( ! function_exists( 'cardealer_get_cars_details_page_sidebar_position' ) ) {
	/**
	 * Cars details page sidebar position
	 */
	function cardealer_get_cars_details_page_sidebar_position() {
		global $car_dealer_options;
		$details_page_sidebar = 'left';
		if ( isset( $car_dealer_options['cars-details-page-sidebar'] ) && ! empty( $car_dealer_options['cars-details-page-sidebar'] ) ) {
			$details_page_sidebar = $car_dealer_options['cars-details-page-sidebar'];
		}
		return $details_page_sidebar;
	}
}
if ( ! function_exists( 'cardealer_get_widget_fuel_efficiency' ) ) {
	/**
	 * Widget fuel efficiency
	 */
	function cardealer_get_widget_fuel_efficiency() {
		global $car_dealer_options;
		if ( isset( $car_dealer_options['cars-details-page-sidebar'] ) && 'no' === $car_dealer_options['cars-details-page-sidebar'] ) {
			$cars_fuel_efficiency_option = ( isset( $car_dealer_options['cars-fuel-efficiency-option'] ) ) ? $car_dealer_options['cars-fuel-efficiency-option'] : 1;
			if ( 1 === (int) $cars_fuel_efficiency_option ) {
				the_widget( 'CarDealer_Helper_Widget_Fuel_Efficiency' );
			}
		}
	}
}

if ( ! function_exists( 'cardealer_cars_sidebar_class' ) ) {
	/**
	 * Cars sidebar class
	 *
	 * @param string $custom_class add custom class.
	 */
	function cardealer_cars_sidebar_class( $custom_class = 'sidebar' ) {
		global $car_dealer_options;
		$left_class  = 9;
		$right_class = 9;

		$getlayout = cardealer_get_cars_list_layout_style();
		if ( isset( $getlayout ) && ! empty( $getlayout ) ) {
			if ( 'view-grid-left' === $getlayout || 'view-grid-masonry-left' === $getlayout ) {
				$content_class = 12 - $right_class;
			} elseif ( 'view-grid-right' === $getlayout || 'view-grid-masonry-right' === $getlayout ) {
				$content_class = 12 - $right_class;
			} elseif ( 'view-grid-full' === $getlayout ) {
				$content_class = 12;
			} elseif ( 'view-list-left' === $getlayout ) {
				$content_class = 12 - $left_class;
			} elseif ( 'view-list-right' === $getlayout ) {
				$content_class = 12 - $left_class;
			} elseif ( 'view-list-full' === $getlayout ) {
				$content_class = 12;
			} else {
				$content_class = 9;
			}
			$classes   = array( $custom_class );
			$classes[] = 'col-lg-' . $content_class . ' col-md-' . $content_class . ' col-sm-' . $content_class;
			echo 'class="' . join( ' ', $classes ) . '"'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		}
	}
}
if ( ! function_exists( 'cardealer_get_car_catlog_sidebar_left' ) ) {
	/**
	 * Catlog sidebar left
	 */
	function cardealer_get_car_catlog_sidebar_left() {
		global $car_dealer_options;
		$getlayout = cardealer_get_cars_list_layout_style();

		$layout = 'default';
		if ( isset( $car_dealer_options['vehicle-listing-layout'] ) && ! empty( $car_dealer_options['vehicle-listing-layout'] ) ) {
			$layout = $car_dealer_options['vehicle-listing-layout'];
		}
		if ( 'lazyload' === $layout ) {
			$getlayout = 'view-grid-left';
		}

		if ( isset( $getlayout ) && ! empty( $getlayout ) ) {
			if ( 'view-grid-left' === $getlayout || 'view-grid-masonry-left' === $getlayout ) {
				if ( is_active_sidebar( 'listing-cars' ) ) {
					?>
					<aside id="sleft" <?php cardealer_cars_sidebar_class(); ?>>
						<div class="listing-sidebar">
							<?php dynamic_sidebar( 'listing-cars' ); ?>
						</div>
					</aside>
					<?php
				}
			} elseif ( 'view-grid-full' === $getlayout ) {
				return;
			} elseif ( 'view-list-left' === $getlayout ) {
				if ( is_active_sidebar( 'listing-cars' ) ) {
					?>
					<aside id="sleft" <?php cardealer_cars_sidebar_class(); ?>>
						<div class="listing-sidebar">
							<?php dynamic_sidebar( 'listing-cars' ); ?>
						</div>
					</aside>
					<?php
				}
			} elseif ( 'view-list-full' === $getlayout ) {
				return;
			}
		}
	}
}

if ( ! function_exists( 'cardealer_get_car_catlog_sidebar_right' ) ) {
	/**
	 * Catlog sidebar right
	 */
	function cardealer_get_car_catlog_sidebar_right() {
		global $car_dealer_options;
		$getlayout = cardealer_get_cars_list_layout_style();
		if ( isset( $getlayout ) && ! empty( $getlayout ) ) {
			if ( 'view-list-right' === $getlayout ) {
				if ( is_active_sidebar( 'listing-cars' ) ) {
					?>
					<aside id="sleft" <?php cardealer_cars_sidebar_class(); ?>>
						<div class="listing-sidebar">
							<?php dynamic_sidebar( 'listing-cars' ); ?>
						</div>
					</aside>
					<?php
				}
			} elseif ( 'view-grid-full' === $getlayout ) {
				return;
			} elseif ( 'view-grid-right' === $getlayout || 'view-grid-masonry-right' === $getlayout ) {
				if ( is_active_sidebar( 'listing-cars' ) ) {
					?>
					<aside id="sleft" <?php cardealer_cars_sidebar_class(); ?>>
						<div class="listing-sidebar">
							<?php dynamic_sidebar( 'listing-cars' ); ?>
						</div>
					</aside>
					<?php
				}
			} elseif ( 'view-list-full' === $getlayout ) {
				return;
			}
		}
	}
}
if ( ! function_exists( 'cardealer_get_catlog_view' ) ) {
	/**
	 * Catlog view
	 */
	function cardealer_get_catlog_view() {
		$cars_grid = isset( $_COOKIE['cars_grid'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['cars_grid'] ) ) : 1;
		$grid_sel  = ( 1 === $cars_grid ) ? 'sel-active' : '';
		$list_sel  = ( 0 === $cars_grid ) ? 'sel-active' : '';
		global $car_dealer_options;
		$theme_color = isset( $car_dealer_options['site_color_scheme_custom']['color'] ) ? $car_dealer_options['site_color_scheme_custom']['color'] : '';

		$getlayout        = cardealer_get_cars_list_layout_style();
		$list_view_layout = array(
			'view-grid-left',
			'view-grid-full',
			'view-grid-right',
			'view-grid-masonry-left',
			'view-grid-masonry-full',
			'view-grid-masonry-right',
			'view-list-left',
			'view-list-full',
			'view-list-right',
		);
		$layout_css_style = array();
		foreach ( $list_view_layout as $key => $value ) {
			$layout_css_style[ $key ] = ( $getlayout === $value ) ? "background-color:$theme_color;" : '';
		}
		?>
		<div class="grid-view change-view-button">
			<div class="view-icon">
				<a class="catlog-layout view-grid" data-id="view-grid-left" href="javascript:void(0)"><span style="<?php echo esc_attr( $layout_css_style[0] ); ?>"><i class="view-grid-left"></i></span></a><a class="catlog-layout view-grid" data-id="view-grid-full" href="javascript:void(0)"><span style="<?php echo esc_attr( $layout_css_style[1] ); ?>"><i class="view-grid-full"></i></span></a><a class="catlog-layout view-grid" data-id="view-grid-right" href="javascript:void(0)"><span style="<?php echo esc_attr( $layout_css_style[2] ); ?>"><i class="view-grid-right"></i></span></a><a class="catlog-layout view-grid" data-id="view-grid-masonry-left" href="javascript:void(0)"><span style="<?php echo esc_attr( $layout_css_style[3] ); ?>"><i class="view-grid-masonry-left"></i></span></a><a class="catlog-layout view-grid" data-id="view-grid-masonry-full" href="javascript:void(0)"><span style="<?php echo esc_attr( $layout_css_style[4] ); ?>"><i class="view-grid-masonry-full"></i></span></a><a class="catlog-layout view-grid" data-id="view-grid-masonry-right" href="javascript:void(0)"><span style="<?php echo esc_attr( $layout_css_style[5] ); ?>"><i class="view-grid-masonry-right"></i></span></a><a class="catlog-layout view-list" data-id="view-list-left" href="javascript:void(0)"><span style="<?php echo esc_attr( $layout_css_style[6] ); ?>"><i class="view-list-left"></i></span></a><a class="catlog-layout view-list" data-id="view-list-full" href="javascript:void(0)"><span style="<?php echo esc_attr( $layout_css_style[7] ); ?>"><i class="view-list-full"></i></span></a><a class="catlog-layout view-list" data-id="view-list-right" href="javascript:void(0)"><span style="<?php echo esc_attr( $layout_css_style[8] ); ?>"><i class="view-list-right"></i></span></a>
			</div>
		</div><!--.grid-view-->
		<?php
	}
}

if ( ! function_exists( 'cardealer_cars_catalog_ordering' ) ) :
	/**
	 * Catalog ordering
	 */
	function cardealer_cars_catalog_ordering() {
		global $wp,$car_dealer_options;

		// @codingStandardsIgnoreStart
		parse_str( $_SERVER['QUERY_STRING'], $params ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$query_string = '?' . $_SERVER['QUERY_STRING'];  // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		// @codingStandardsIgnoreEnd

		// Vehicle listing layout.
		$layout = 'default';
		if ( isset( $car_dealer_options['vehicle-listing-layout'] ) && ! empty( $car_dealer_options['vehicle-listing-layout'] ) ) {
			$layout = $car_dealer_options['vehicle-listing-layout'];
		}

		// replace it with theme option.
		if ( isset( $car_dealer_options['cars-per-page'] ) ) {
			$per_page = $car_dealer_options['cars-per-page'];
		} else {
			$per_page = 12;
		}

		$cars_orderby_selected = cardealer_get_default_sort_by(); // get default option value.
		if ( isset( $params['cars_orderby'] ) && ! empty( $params['cars_orderby'] ) ) {
			$cars_orderby_selected = $params['cars_orderby'];
		}

		$cars_order_selected = cardealer_get_default_sort_by_order();// get default option value.
		if ( isset( $params['cars_order'] ) && ! empty( $params['cars_order'] ) && in_array( $params['cars_order'], array( 'desc', 'asc' ), true ) ) {
			$cars_order_selected = $params['cars_order'];
		}

		$cars_pp_selected = ( isset( $params['cars_pp'] ) && ! empty( $params['cars_pp'] ) ) ? $params['cars_pp'] : $per_page;
		?>
		<div class="selected-box">
			<select name="cars_pp" id="pgs_cars_pp" class="cd-select-box">
				<?php
				for ( $i = 1; $i <= 5; $i++ ) {
					$per_page_value = $per_page * $i;
					?>
					<option value="<?php echo esc_html( $per_page_value ); ?>" <?php selected( $cars_pp_selected, $per_page_value ); ?>><?php echo esc_html( $per_page_value ); ?></option>
					<?php
				}
				?>
			</select>
		</div>
		<?php
		$cardealer_orderby_types = array(
			'name'       => esc_html__( 'Sort by Name', 'cardealer' ),
			'sale_price' => esc_html__( 'Sort by Price', 'cardealer' ),
			'date'       => esc_html__( 'Sort by Date', 'cardealer' ),
			'year'       => esc_html__( 'Sort by Year', 'cardealer' ),
		);
		?>
		<div class="selected-box">
			<div class="select">
				<select class="select-box cd-select-box" name="cars_orderby" id="pgs_cars_orderby">
					<option value=""><?php esc_html_e( 'Sort by Default', 'cardealer' ); ?></option>
					<?php
					foreach ( $cardealer_orderby_types as $cardealer_orderby_v => $cardealer_orderby_label ) {
						?>
						<option value="<?php echo esc_attr( $cardealer_orderby_v ); ?>" <?php selected( $cars_orderby_selected, $cardealer_orderby_v ); ?>><?php echo esc_html( $cardealer_orderby_label ); ?></option>
						<?php
					}
					?>
				</select>
			</div>
		</div>
		<?php
		if ( 'asc' === $cars_order_selected ) {
			?>
			<div class="cars-order text-right"><a id="pgs_cars_order" data-order="desc" data-current_order="asc" href="javascript:void(0)"><i class="fas fa-arrow-up"></i></a></div>
			<?php
		} else {
			?>
			<div class="cars-order text-right"><a id="pgs_cars_order" data-order="asc" data-current_order="desc" href="javascript:void(0)"><i class="fas fa-arrow-down"></i></a></div>
			<?php
		}
		$getlayout = '';
		$getlayout = cardealer_get_cars_list_layout_style();

		if ( isset( $getlayout ) && 'view-list-full' === $getlayout || isset( $getlayout ) && 'view-grid-full' === $getlayout || 'lazyload' === $layout ) {
			?>
			<div class="pgs_cars_search_box"><button class="pgs_cars_search_btn not_click"><i class="fas fa-search"></i></button>
				<div class="pgs_cars_search search" style="display:none;">
					<input type="search" id="pgs_cars_search" class="form-control search-form placeholder" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" placeholder="<?php echo esc_attr__( 'Search...', 'cardealer' ); ?>" />
					<button class="search-button" id="pgs_cars_search_btn" value="Search" type="submit"><i class="fas fa-search"></i></button>
					<div class="auto-compalte-list"><ul></ul></div>
				</div>
			</div>
			<?php
		}
	}
endif;

if ( ! function_exists( 'cardealer_get_taxonomys_array' ) ) {
	/**
	 * Taxonomys array
	 */
	function cardealer_get_taxonomys_array() {
		$taxonomies = array( 'car_year', 'car_make', 'car_model', 'car_body_style', 'car_mileage', 'car_fuel_type', 'car_fuel_economy', 'car_trim', 'car_transmission', 'car_condition', 'car_drivetrain', 'car_engine', 'car_exterior_color', 'car_interior_color', 'car_stock_number', 'car_vin_number', 'car_features_options' );

		$taxonomies_raw = get_object_taxonomies( 'cars' );

		foreach ( $taxonomies_raw as $new_tax ) {
			if ( in_array( $new_tax, $taxonomies ) ) {
				continue;
			}

			$new_tax_obj = get_taxonomy( $new_tax );
			if( isset($new_tax_obj->include_in_filters) && $new_tax_obj->include_in_filters == true ) {
				$taxonomies[] = $new_tax;
			}
		}

		return apply_filters( 'cardealer_taxonomys_array', $taxonomies );
	}
}

if ( ! function_exists( 'cardealer_get_all_taxonomy_with_terms' ) ) {
	/**
	 * Taxonomy with terms
	 */
	function cardealer_get_all_taxonomy_with_terms() {
		$attributs = array();
		$taxonomys = cardealer_get_taxonomys_array();

		foreach ( $taxonomys as $tax ) {
			$terms = get_terms(
				array(
					'taxonomy'   => $tax,
					'hide_empty' => true,
				)
			);

			$taxonomy_name = get_taxonomy( $tax );
			$slug          = $taxonomy_name->rewrite['slug'];
			$label         = $taxonomy_name->labels->singular_name;
			if ( ! empty( $terms ) ) {
				foreach ( $terms as $tdata ) {
					$attributs[ $slug ]['terms'][] = $tdata->slug;
					$attributs[ $slug ]['label']   = $label;
					$attributs[ $slug ]['slug']    = $slug;
				}
			} else {
				$attributs[ $slug ]['label'] = $label;
				$attributs[ $slug ]['slug']  = $slug;
			}
		}
		return $attributs;
	}
}

if ( ! function_exists( 'cardealer_cars_get_catalog_ordering_args' ) ) {
	/**
	 * Pass arguments on cars listing page
	 *
	 * @param array $wp_query get the value.
	 */
	function cardealer_cars_get_catalog_ordering_args( $wp_query ) {

		global $wp_query,$car_dealer_options;
		$taxonomies   = cardealer_get_vehicles_taxonomies();
		$taxonomies   = array_values( $taxonomies );
		$current_term = $wp_query->get_queried_object();

		$cars_inventory_page = ( isset( $car_dealer_options['cars_inventory_page'] ) ) ? $car_dealer_options['cars_inventory_page'] : '';

		if ( ( ! is_admin() && $wp_query->is_main_query() && is_post_type_archive( 'cars' ) ) || ( $wp_query->is_page() && 'page' === get_option( 'show_on_front' ) && '' !== $cars_inventory_page && absint( $wp_query->get( 'page_id' ) ) === absint( $cars_inventory_page ) ) || ( is_tax() && ( isset( $current_term->taxonomy ) && in_array( $current_term->taxonomy, $taxonomies ) ) ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
			$wp_query->set( 'page_id', '' );
			$wp_query->is_singular          = false;
			$wp_query->is_post_type_archive = true;
			$wp_query->bis_archive          = true;
			$wp_query->is_page              = false;

			// @codingStandardsIgnoreStart
			parse_str( $_SERVER['QUERY_STRING'], $params );
			// @codingStandardsIgnoreEnd

			$pgs_min_price = isset( $params['min_price'] ) ? esc_attr( $params['min_price'] ) : 0;
			$pgs_max_price = isset( $params['max_price'] ) ? esc_attr( $params['max_price'] ) : 0;
			if ( $pgs_min_price > 0 || $pgs_max_price > 0 ) {
				$prices = cardealer_get_car_filtered_price();
				$min    = floor( $prices->min_price );
				$max    = ceil( $prices->max_price );

				if ( $min !== $pgs_min_price || $max !== $pgs_max_price ) {
					$args['meta_query'][] = array(
						'key'     => 'final_price',
						'value'   => array( $pgs_min_price, $pgs_max_price ),
						'compare' => 'BETWEEN',
						'type'    => 'NUMERIC',
					);
				}
			}
			/* Don't want to show sold car on car listing page */
			if ( isset( $car_dealer_options['car_no_sold'] ) && 0 === (int) $car_dealer_options['car_no_sold'] ) {
				$args['meta_query'][] =
					array(
						'key'     => 'car_status',
						'value'   => 'sold',
						'compare' => '!=',
					);

			}

			if ( isset( $params['vehicle_location'] ) && ! empty( $params['vehicle_location'] ) ) {
				$args['meta_query'][] = array(
					'key'     => 'vehicle_location',
					'value'   => $params['vehicle_location'],
					'compare' => 'LIKE',
				);
			}

			/* Set meta query*/
			if ( ! empty( $args['meta_query'] ) ) {
				$wp_query->set( 'meta_query', $args['meta_query'] );
			}

			/* Check Year range option enable from backend */
			if ( isset( $car_dealer_options['cars-year-range-slider'] ) && 'yes' === (string) $car_dealer_options['cars-year-range-slider'] ) {
				$year_range    = cardealer_get_year_range();
				$yearmin       = isset( $year_range['min_year'] ) ? $year_range['min_year'] : '';
				$yearmax       = isset( $year_range['max_year'] ) ? $year_range['max_year'] : '';
				$pgs_min_year  = isset( $params['min_year'] ) ? esc_attr( $params['min_year'] ) : 0;
				$pgs_max_year  = isset( $params['max_year'] ) ? esc_attr( $params['max_year'] ) : 0;
				$year_rang_qur = array();
				if ( ! empty( $year_range ) && ( $pgs_min_year > 0 || $pgs_max_year > 0 ) ) {
					if ( $yearmin !== $pgs_min_year || $yearmax !== $pgs_max_year ) {
						$terms   = get_terms(
							array(
								'taxonomy'   => 'car_year',
								'hide_empty' => true,
							)
						);
						$quryear = array();
						if ( ! empty( $terms ) ) {
							foreach ( $terms as $tdata ) {
								if ( ( $tdata->slug >= $pgs_min_year ) && ( $tdata->slug <= $pgs_max_year ) ) {
									$quryear[] = $tdata->slug;
								}
							}
						}
						$year_rang_qur                = array( 'relation' => 'AND' );
						$year_rang_qur['tax_query'][] = array(
							'taxonomy' => 'car_year',
							'field'    => 'slug',
							'terms'    => $quryear,
						);
						$wp_query->set( 'tax_query', $year_rang_qur );

					}
				}
			}

			if ( isset( $params['car_mileage'] ) && ! empty( $params['car_mileage'] ) ) {

				$mileage_terms   = array();
				$get_car_mileage = $params['car_mileage'];
				$terms           = get_terms(
					array(
						'taxonomy'   => 'car_mileage',
						'hide_empty' => true,
					)
				);
				foreach ( $terms as $tdata ) {
					$mileage = $tdata->slug;
					if ( is_numeric( $mileage ) && is_numeric( $get_car_mileage ) ) {
						if ( $mileage < $get_car_mileage ) {
							$mileage_terms[] = $tdata->slug;
						}
					}
				}
				if ( ! empty( $mileage_terms ) ) {
					$car_mileage_args = array(
						array(
							'taxonomy' => 'car_mileage',
							'field'    => 'slug',
							'terms'    => $mileage_terms,
						),
					);
					unset( $wp_query->query_vars['car_mileage'] );
					$wp_query->set( 'tax_query', $car_mileage_args );
				}
			}
			$wp_query->set( 'post_type', array( 'cars' ) );

			$pob = cardealer_get_default_sort_by();// get default option value.
			if ( isset( $params['cars_orderby'] ) && ! empty( $params['cars_orderby'] ) ) {
				$pob = $params['cars_orderby'];
			}

			$order = cardealer_get_default_sort_by_order();// get default option value.
			if ( isset( $params['cars_order'] ) && ! empty( $params['cars_order'] ) && in_array( $params['cars_order'], array( 'desc', 'asc' ), true ) ) {
				$order = $params['cars_order'];
			}
			switch ( $pob ) {
				case 'name':
					$orderby = 'title';
					break;
				case 'sale_price':
					$orderby = 'meta_value_num';
					$wp_query->set( 'meta_key', 'final_price' );
					$wp_query->set( 'type', 'NUMERIC' );
					break;
				case 'year':
					$orderby = 'year';
					break;
				case 'date':
					$orderby = 'date (post_date)';
					break;
				default:
					$orderby = 'date (post_date)';
					break;
			}
			$wp_query->set( 'orderby', $orderby );
			$wp_query->set( 'order', $order );

			/* set number of car on car listing page */
			if ( isset( $params['cars_pp'] ) && ! empty( $params['cars_pp'] ) ) {
				$per_page = $params['cars_pp'];
			} elseif ( isset( $car_dealer_options['cars-per-page'] ) && ! empty( $car_dealer_options['cars-per-page'] ) ) {
				$per_page = $car_dealer_options['cars-per-page'];
			} else {
				$per_page = 12;
			}

			$wp_query->set( 'posts_per_page', $per_page );

		}
	}
	add_action( 'pre_get_posts', 'cardealer_cars_get_catalog_ordering_args' );
}

if ( ! function_exists( 'orderby_car_year_qur' ) ) {
	/**
	 * Year qur
	 *
	 * @param string $orderby set orderby.
	 * @param array  $wp_query get the value.
	 */
	function orderby_car_year_qur( $orderby, $wp_query ) {
		global $wpdb;
		if ( isset( $wp_query->query_vars['orderby'] ) && 'year' === $wp_query->query_vars['orderby'] ) {
			$orderby  = "(
				SELECT GROUP_CONCAT(name ORDER BY name ASC)
				FROM $wpdb->term_relationships
				INNER JOIN $wpdb->term_taxonomy USING (term_taxonomy_id)
				INNER JOIN $wpdb->terms USING (term_id)
				WHERE $wpdb->posts.ID = object_id
				AND taxonomy = 'car_year'
				GROUP BY object_id
			) ";
			$orderby .= ( 'ASC' === strtoupper( $wp_query->get( 'order' ) ) ) ? 'ASC' : 'DESC';
		}
		return $orderby;
	}
	/**
	 * Filter for add custom subquery for year wise sorting order in car listing page
	 */
	add_filter( 'posts_orderby', 'orderby_car_year_qur', 10, 2 );
}

if ( ! function_exists( 'cardealer_set_tex_query_array' ) ) {
	/**
	 * Tax query
	 *
	 * @param array $taxonomys .
	 * @param array $post .
	 */
	function cardealer_set_tex_query_array( $taxonomys, $post ) {
		$mileage_terms = array();
		$arg           = array();
		if ( isset( $_GET ) && function_exists( 'cdhl_get_cars_taxonomy' ) ) {
			$cars_taxonomy = cdhl_get_cars_taxonomy();

			$cfb = array();
			foreach ( $_GET as $key => $val ) {
				if ( in_array( $key, $cars_taxonomy ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
					$cfb[] = $key;
				}
			}
			if ( ! empty( $cfb ) ) {
				$taxonomys = array_unique( array_merge( $taxonomys, $cfb ) );
			}
		}
		foreach ( $taxonomys as $tax ) {
			if ( isset( $post[ $tax ] ) && '' !== $post[ $tax ] ) {
				foreach ( $post as $key => $val ) {
					if ( $key === $tax ) {
						if ( 'car_mileage' === $key ) {
							$terms = get_terms(
								array(
									'taxonomy'   => 'car_mileage',
									'hide_empty' => true,
								)
							);
							foreach ( $terms as $tdata ) {
								$mileage      = $tdata->slug;
								$post_mileage = $post[ $tax ];
								if ( is_numeric( $mileage ) && is_numeric( $post_mileage ) ) {
									if ( $mileage < $post[ $tax ] ) {
										$mileage_terms[] = $tdata->slug;
									}
								}
							}

							$arg[] = array(
								'taxonomy' => $tax,
								'field'    => 'slug',
								'terms'    => $mileage_terms,
							);
						} else {
							$arg[] = array(
								'taxonomy' => $tax,
								'field'    => 'slug',
								'terms'    => array( $post[ $tax ] ),
							);
						}
					}
				}
			}
		}

		if ( is_tax( 'vehicle_cat' ) || ( isset( $post['is_vehicle_cat'] ) && 'yes' === $post['is_vehicle_cat'] ) ) {
			global $wp_query;
			if ( isset( $wp_query->query_vars['vehicle_cat'] ) && ! empty( $wp_query->query_vars['vehicle_cat'] ) ) {
				$vehicle_cat = $wp_query->query_vars['vehicle_cat'];
			} elseif ( isset( $post['vehicle_cat'] ) && ! empty( $post['vehicle_cat'] ) ) {
				$vehicle_cat = $post['vehicle_cat'];
			}
			$arg[] = array(
				'taxonomy' => 'vehicle_cat',
				'field'    => 'slug',
				'terms'    => array( $vehicle_cat ),
			);
		}

		$year_rang_slider = cardealer_is_year_range_active();
		if ( $year_rang_slider ) {

			$year_range    = cardealer_get_year_range();
			$yearmin       = isset( $year_range['min_year'] ) ? $year_range['min_year'] : '';
			$yearmax       = isset( $year_range['max_year'] ) ? $year_range['max_year'] : '';
			$pgs_min_year  = isset( $post['min_year'] ) ? esc_attr( $post['min_year'] ) : 0;
			$pgs_max_year  = isset( $post['max_year'] ) ? esc_attr( $post['max_year'] ) : 0;
			$year_rang_qur = array();
			if ( $pgs_min_year > 0 || $pgs_max_year > 0 ) {

				if ( $yearmin !== $pgs_min_year || $yearmax !== $pgs_max_year ) {

					$terms         = get_terms(
						array(
							'taxonomy'   => 'car_year',
							'hide_empty' => true,
						)
					);
					$quryear       = array();
					$taxonomy_name = get_taxonomy( 'car_year' );
					$slug          = $taxonomy_name->rewrite['slug'];
					$label         = $taxonomy_name->labels->menu_name;
					if ( ! empty( $terms ) ) {
						foreach ( $terms as $tdata ) {
							if ( ( $tdata->slug >= $pgs_min_year ) && ( $tdata->slug <= $pgs_max_year ) ) {
								$quryear[] = $tdata->slug;
							}
						}
					}
					$arg['tax_query'][] = array(
						'taxonomy' => 'car_year',
						'field'    => 'slug',
						'terms'    => $quryear,
						'operator' => 'IN',
					);

				}
			}
		}
		/**
		 * Filters vehicle taxonomy query.
		 *
		 * @since 1.0
		 * @param array      $arg   Vehicle taxonomy query arguments.
		 * @visible          true
		 */
		return apply_filters( 'cardealer_set_tax_query', $arg );
	}
}
if ( ! function_exists( 'cardealer_get_all_filters' ) ) {
	/**
	 * Get all filter select box
	 */
	function cardealer_get_all_filters() {
		$taxonomys     = cardealer_get_filters_taxonomy();
		$get_arg       = array();
		$get_url_terms = array();

		if ( is_tax() ) {
			if ( 'car_mileage' !== get_query_var( 'taxonomy' ) ) {
				$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
				if ( isset( $term ) && ! empty( $term ) ) {
					$_GET[ $term->taxonomy ] = $term->slug;
				}
			}
		}

		// @codingStandardsIgnoreStart
		foreach ( $taxonomys as $tax ) {
			/** Check from url if there any filter*/
			if ( isset( $_GET[ $tax ] ) && '' !== $_GET[ $tax ] ) {
				if ( isset( $_GET['car_mileage'] ) && ! empty( $_GET['car_mileage'] ) ) {
					$get_arg[] = array(
						'taxonomy' => $tax,
						'field'    => 'slug',
						'terms'    => array( $_GET[ $tax ] ),
						'compare'  => '<',
						'type'     => 'NUMERIC',
					);
				} else {
					$get_arg[] = array(
						'taxonomy' => $tax,
						'field'    => 'slug',
						'terms'    => array( $_GET[ $tax ] ),
					);
				}
			}
		}
		// @codingStandardsIgnoreEnd

		/** Check year_range filter is active then add in query*/
		$year_range    = cardealer_get_year_range();
		$yearmin       = isset( $year_range['min_year'] ) ? $year_range['min_year'] : 0;
		$yearmax       = isset( $year_range['max_year'] ) ? $year_range['max_year'] : 0;
		$pgs_min_year  = isset( $_GET['min_year'] ) ? sanitize_text_field( wp_unslash( $_GET['min_year'] ) ) : 0;
		$pgs_max_year  = isset( $_GET['max_year'] ) ? sanitize_text_field( wp_unslash( $_GET['max_year'] ) ) : 0;
		$year_rang_qur = array();
		if ( $pgs_min_year > 0 || $pgs_max_year > 0 ) {

			if ( $yearmin !== $pgs_min_year || $yearmax !== $pgs_max_year ) {

				$terms         = get_terms(
					array(
						'taxonomy'   => 'car_year',
						'hide_empty' => true,
					)
				);
				$quryear       = array();
				$taxonomy_name = get_taxonomy( 'car_year' );
				$slug          = $taxonomy_name->rewrite['slug'];
				$label         = $taxonomy_name->labels->menu_name;
				if ( ! empty( $terms ) ) {
					foreach ( $terms as $tdata ) {
						if ( ( $tdata->slug >= $pgs_min_year ) && ( $tdata->slug <= $pgs_max_year ) ) {
							$quryear[] = $tdata->slug;
						}
					}
				}
				$get_arg['tax_query'][] = array(
					'taxonomy' => 'car_year',
					'field'    => 'slug',
					'terms'    => $quryear,
					'operator' => 'IN',
				);

			}
		}

		/**
		 * Filters the search arguments used in filtering vehicle inventory on inventory page.
		 *
		 * @since 1.0
		 *
		 * @param array      $get_arg   Array arguments for vehicle filter query.
		 * @visible          true
		 */
		$get_arg = apply_filters( 'cardealer_get_all_filters', $get_arg );

		/**
		 * Pass query var
		 *
		 * @param array $get_arg pass query var if any in url else it blank
		 */
		$attributs = cardealer_new_get_all_filters( $get_arg );
		echo wp_kses(
			$attributs,
			array(
				'div'    => array(
					'id'       => true,
					'class'    => true,
					'tabindex' => true,
				),
				'span'   => array(
					'id'    => true,
					'class' => true,
				),
				'strong' => array(
					'id'    => true,
					'class' => true,
				),
				'ul'     => array(
					'id'                => true,
					'class'             => true,
					'data-all-listings' => true,
				),
				'li'     => array(
					'id'        => true,
					'class'     => true,
					'style'     => true,
					'data-type' => true,
				),
				'select' => array(
					'id'       => true,
					'class'    => true,
					'data-tax' => true,
					'data-id'  => true,
					'name'     => true,
					'style'    => true,
				),
				'option' => array(
					'id'       => true,
					'class'    => true,
					'value'    => true,
					'selected' => true,
				),
				'a'      => array(
					'id'    => true,
					'class' => true,
					'href'  => true,
				),
				'i'      => array(
					'class' => true,
				),
				'input'  => array(
					'type'         => true,
					'id'           => true,
					'name'         => true,
					'value'        => true,
					'data-yearmin' => true,
					'data-yearmax' => true,
					'readonly'     => true,
					'data-cfb'     => true,
				),
			)
		);
	}
}

if ( ! function_exists( 'cardealer_new_get_all_filters' ) ) {
	/**
	 * Get all filters
	 *
	 * @param array $get_arg .
	 */
	function cardealer_new_get_all_filters( $get_arg ) {
		$is_vehicle_cat = false;
		if ( is_tax( 'vehicle_cat' ) ) {
			$is_vehicle_cat = false;
			global $wp_query;
			$get_arg[] = array(
				'taxonomy' => 'vehicle_cat',
				'field'    => 'slug',
				'terms'    => array( $wp_query->query_vars['vehicle_cat'] ),

			);
		}

		$taxonomys     = cardealer_get_filters_taxonomy();
		$args          = cardealer_make_filter_wp_query( $_GET );
		$result_filter = array();

		$args_new                  = $args;
		$args_new['fields']        = 'ids';
		$args_new['no_found_rows'] = true;
		$filter_query_args         = array_replace( $args_new, array( 'posts_per_page' => -1 ) );

		$filter_query = new WP_Query( $filter_query_args );
		$tot_result   = $filter_query->post_count;
		if ( $filter_query->have_posts() ) {
			if ( isset( $get_arg ) && ! empty( $get_arg ) && $tot_result > 0 ) {
				foreach ( $taxonomys as $tax ) {
					$tax_args = array(
						'orderby' => 'name',
						'order'   => 'ASC',
						'fields'  => 'all',
					);
					$terms    = wp_get_object_terms( $filter_query->posts, $tax, $tax_args );
					foreach ( $terms as $tdata ) {
						if ( $tdata->taxonomy === $tax ) {
							$result_filter[ $tax ][] = array(
								'term_id'  => $tdata->term_id,
								'slug'     => $tdata->slug,
								'name'     => $tdata->name,
								'taxonomy' => $tdata->taxonomy,
							);
						}
					}
				}
			}
			if ( $is_vehicle_cat ) {
				$args  = array(
					'orderby' => 'name',
					'order'   => 'ASC',
					'fields'  => 'all',
				);
				$terms = wp_get_object_terms( $filter_query->posts, 'vehicle_cat', $tax_args );
				foreach ( $terms as $tdata ) {
					if ( 'vehicle_cat' === $tdata->taxonomy ) {
						$result_filter[ $tax ][] = array(
							'term_id'  => $tdata->term_id,
							'slug'     => $tdata->slug,
							'name'     => $tdata->name,
							'taxonomy' => $tdata->taxonomy,
						);
					}
				}
			}
			wp_reset_postdata();
		}
		$attributs      = '<div class="cars-total-vehicles">';
			$attributs .= '<span class="stripe"><strong><span class="number_of_listings">' . esc_html( $tot_result ) . '</span> ';
			$attributs .= '<span class="listings_grammar">' . esc_html__( 'Vehicles Matching', 'cardealer' ) . '</span></strong></span>';
			$attributs .= '<ul class="stripe-item filter margin-bottom-none" data-all-listings="All Listings">';

		foreach ( $_GET as $gkey => $gval ) {
			if ( in_array( $gkey, $taxonomys ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
				$taxonomy_name = get_taxonomy( $gkey );
				$label         = $taxonomy_name->labels->singular_name;
				if ( 'car_mileage' === $gkey ) {
					$attributs    .= '<li id="stripe-item-' . esc_attr( $gkey ) . '" data-type="' . esc_attr( $gkey ) . '" ><a href="javascript:void(0)"><i class="far fa-times-circle"></i> ' . esc_html( $label ) . ' :  <span data-key="' . esc_attr( $_GET[ $gkey ] ) . '">' . esc_html( $_GET[ $gkey ] ) . '</span></a></li>';
				} else {
					$term          = get_term_by( 'slug', $gval, $gkey );
					$term_name     = isset( $term->name ) ? $term->name : '';
					$attributs    .= '<li id="stripe-item-' . esc_attr( $gkey ) . '" data-type="' . esc_attr( $gkey ) . '" ><a href="javascript:void(0)"><i class="far fa-times-circle"></i> ' . esc_html( $label ) . ' :  <span data-key="' . esc_attr( $gval ) . '">' . esc_html( $term_name ) . '</span></a></li>';
				}
			}
		}
			$attributs .= '</ul>';
		$attributs     .= '</div>';
		$attributs     .= '<div class="listing_sort">';

		$attributs .= '<div class="sort-filters">';
		$t          = 1;

		$is_year_range_active = cardealer_is_year_range_active();
		if ( $is_year_range_active ) {
			$year_range_filters = cardealer_get_year_range_filters( '' );
			$attributs         .= $year_range_filters;
			if ( array_search( 'car_year', $taxonomys ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
				$key = array_search( 'car_year', $taxonomys ); // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict

				unset( $taxonomys[ $key ] );}
		}

		/** Here we create selectbox as per query or default*/
		foreach ( $taxonomys as $tax ) {

			$taxonomy_name = get_taxonomy( $tax );
			$label         = $taxonomy_name->labels->singular_name;
			$attributs    .= '<select data-tax="' . esc_attr( $label ) . '" data-id="' . esc_attr( $tax ) . '" id="sort_' . esc_attr( $tax ) . '" name="' . esc_attr( $tax ) . '" class="select-sort-filters cd-select-box">';
			$attributs    .= '<option value="">' . esc_html( $label ) . '</option>';
			/** Cehck is there any argumet for filter term */
			if ( isset( $get_arg ) && ! empty( $get_arg ) ) {
				$newarr = array();

				if ( ! empty( $result_filter[ $tax ] ) ) {
					foreach ( $result_filter[ $tax ] as $term_data ) {
							$selected = '';
						if ( 'car_mileage' !== $tax ) {
							if ( isset( $_GET[ $tax ] ) && '' !== $_GET[ $tax ] ) {
								if ( $_GET[ $tax ] === $term_data['slug'] ) {
									$selected = "selected='selected'";
								}
							}

							if ( ! in_array( $term_data['slug'], $newarr ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
								$attributs .= '<option value="' . $term_data['slug'] . '" ' . $selected . '>' . $term_data['name'] . '</option>';
								$newarr[]   = $term_data['slug'];
							}
						} else {

							$mileage_array = cardealer_get_mileage_array();
							if ( 'car_mileage' === $tax && 1 === $t ) {
								foreach ( $mileage_array as $mileage ) {
									$selected = '';
									if ( isset( $_GET['car_mileage'] ) && $_GET['car_mileage'] === $mileage ) {
										$selected = "selected=''";
									}
									$attributs .= '<option value="' . esc_attr( $mileage ) . '" ' . esc_attr( $selected ) . '>&leq; ' . esc_html( $mileage ) . '</option>';
								}
								$t++;
							}
						}
					}
				}
			} else {
				/** Here we set default terms list */
				$terms = get_terms(
					array(
						'taxonomy'   => $tax,
						'hide_empty' => true,
					)
				);

				foreach ( $terms as $tdata ) {
					if ( 'car_mileage' !== $tax ) {
						$selected = '';
						if ( isset( $_GET[ $tax ] ) && '' !== $_GET[ $tax ] ) {
							if ( $_GET[ $tax ] === $tdata->slug ) {
								$selected = "selected=''";
							}
						}
						$attributs .= '<option value="' . esc_attr( $tdata->slug ) . '" ' . esc_attr( $selected ) . '>' . esc_html( $tdata->name ) . '</option>';
					} else {

						$mileage_array = cardealer_get_mileage_array();
						if ( 'car_mileage' === $tax && 1 === $t ) {
							foreach ( $mileage_array as $mileage ) {
								$selected = '';
								if ( isset( $_GET['car_mileage'] ) && $_GET['car_mileage'] === $mileage ) {
									$selected = "selected=''";
								}
								$attributs .= '<option value="' . esc_attr( $mileage ) . '" ' . esc_attr( $selected ) . '>&leq; ' . esc_html( $mileage ) . '</option>';
							}
							$t++;
						}
					}
				}
			}
			$attributs .= '</select>';
		}
		$attributs .= '<div class=""><a class="button" href="" id="reset_filters">' . esc_html__( 'Reset', 'cardealer' ) . '</a></div>';
		$attributs .= '</div>';
		$attributs .= '<span class="filter-loader"></span></div>';
		return $attributs;
	}
}

if ( ! function_exists( 'cardealer_get_filters_taxonomy' ) ) {
	/**
	 * Filters taxonomy
	 */
	function cardealer_get_filters_taxonomy() {
		global $car_dealer_options;
		$taxonomies = array( 'car_year', 'car_make', 'car_model', 'car_body_style', 'car_condition', 'car_mileage', 'car_transmission', 'car_drivetrain', 'car_engine', 'car_fuel_economy', 'car_exterior_color' );
		$taxonomies_raw = get_object_taxonomies( 'cars' );

		foreach ( $taxonomies_raw as $new_tax ) {
			if ( in_array( $new_tax, $taxonomies ) ) {
				continue;
			}

			$new_tax_obj = get_taxonomy( $new_tax );
			if( isset($new_tax_obj->include_in_filters) && $new_tax_obj->include_in_filters == true ) {
				$taxonomies[] = $new_tax;
			}
		}

		/**
		* Filters the elements of vehicle taxonomy used on vehicle inventory page filters.
		*
		* @since 1.0
		* @return array     Array of vehicle taxonomy slugs.
		* $visible          true
		*/
		$taxonomys = apply_filters( 'cardealer_filters_taxonomy_array', $taxonomies );
		if ( isset( $car_dealer_options['cars_listing_filters']['Added Filters'] ) ) {
			unset( $car_dealer_options['cars_listing_filters']['Added Filters']['placebo'] );
			$car_attributes = $car_dealer_options['cars_listing_filters']['Added Filters'];
			if ( ! empty( $car_attributes ) ) {
				$taxonomys = array();
				foreach ( $car_attributes as $key => $car_att ) {
					$taxonomys[] = $key;
				}
			}
		}
		// Check year range slider enabled?
		if ( isset( $car_dealer_options['cars-year-range-slider'] ) && 'yes' === (string) $car_dealer_options['cars-year-range-slider'] ) {
			if ( ! array_search( 'car_year', $taxonomys ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
				$key = array_search( 'car_year', $taxonomys ); // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict

				unset( $taxonomys[ $key ] );
			}
		}
		/**
		 * Filters the array of vehicle filter attributes displayed on vehicle inventory page.
		 *
		 * @since 1.0
		 *
		 * @param array        $taxonomys   Array of vehicle filter attributes.
		 * @visible            true
		 */
		return apply_filters( 'cardealer_get_filters_taxonomy', $taxonomys );
	}
}

if ( ! function_exists( 'cardealer_get_mileage_array' ) ) {
	/**
	 * Get array
	 */
	function cardealer_get_mileage_array() {
		global $car_dealer_options;

		$min_mileage     = ( isset( $car_dealer_options['min_mileage'] ) && $car_dealer_options['min_mileage'] ) ? (int) $car_dealer_options['min_mileage'] : 10000;
		$add_per_mileage = ( isset( $car_dealer_options['add_per_mileage'] ) && $car_dealer_options['add_per_mileage'] ) ? (int) $car_dealer_options['add_per_mileage'] : 10000;
		$mileage_step    = ( isset( $car_dealer_options['mileage_step'] ) && $car_dealer_options['mileage_step'] ) ? (int) $car_dealer_options['mileage_step'] : 10;

		if ( $min_mileage && $add_per_mileage && $mileage_step ) {
			$mileage_array = array( $min_mileage );

			$new_mileage = $min_mileage;
			for ( $i = 1; $i < $mileage_step; $i++ ){
				$new_mileage = $new_mileage + $add_per_mileage;
				$mileage_array[] = $new_mileage;
			}

			$mileage_max = cardealer_get_mileage_max();
			if ( $new_mileage < $mileage_max ) {
				$mileage_array[] = cardealer_roundup_to_nearest_multiple( $mileage_max, apply_filters( 'cardealer_roundup_to_nearest_multiple_increment', 1000 ) );
			}
		} else {
			$mileage_array = array( '10000', '20000', '30000', '40000', '50000', '60000', '70000', '80000', '90000', '100000' );
		}
		/**
		 * Filters the vehicle mileage array - mostly used in vehicle filters on "vehicle inventory page" and "potenza custom filters" shortcode.
		 *
		 * @since 1.0
		 * @param array         $mileage_array  Mileage array elements.
		 * @visible             true
		 */
		return apply_filters( 'cardealer_get_mileage_array', $mileage_array );
	}
}

if ( ! function_exists( 'cardealer_make_filter_wp_query' ) ) {
	/**
	 * Make filter wp query
	 *
	 * @param array $request_method .
	 */
	function cardealer_make_filter_wp_query( $request_method ) {

		$tax_query_arry = array();
		$taxonomys      = cardealer_get_filters_taxonomy();
		if ( isset( $request_method['selected_attr'] ) && ! empty( $request_method['selected_attr'] ) ) {
			$taxonomys = explode( ',', $request_method['selected_attr'] );
		}

		$tax_query_arry  = cardealer_set_tex_query_array( $taxonomys, $request_method );
		$data_html       = '';
		$pagination_html = '';
		$cars_orderby    = 'date (post_date)';
		$data_order      = 'asc';

		global $car_dealer_options;

		$params = '';

		// @codingStandardsIgnoreStart
		if( isset( $_SERVER['QUERY_STRING'] ) ) {
			parse_str( $_SERVER['QUERY_STRING'], $params ); // the context is safe and reliable.
		}
		// @codingStandardsIgnoreEnd

		$per_page   = 12;
		$cars_order = 'date (post_date)';
		if ( isset( $car_dealer_options['cars-per-page'] ) ) {
			$per_page = $car_dealer_options['cars-per-page'];
		}
		if ( isset( $request_method['cars_pp'] ) && ! empty( $request_method['cars_pp'] ) ) {
			$per_page = $request_method['cars_pp'];
		}

		if ( isset( $request_method['cars_order'] ) && ! empty( $request_method['cars_order'] ) ) {
			$data_order = $request_method['cars_order'];
		}
		$paged = isset( $request_method['paged'] ) ? (int) $request_method['paged'] : 1;
		$args  = array(
			'post_type'      => 'cars',
			'post_status'    => array( 'publish', 'acf-disabled' ),
			'posts_per_page' => $per_page,
			'order'          => $data_order,
			'paged'          => $paged,
		);

		if ( isset( $request_method['cars_orderby'] ) && ! empty( $request_method['cars_orderby'] ) ) {
			$cars_orderby = $request_method['cars_orderby'];
		}

		if ( 'sale_price' === $cars_orderby ) {
			$args['orderby']  = 'meta_value_num';
			$args['meta_key'] = 'final_price';
		} elseif ( 'featured' === $cars_orderby ) {
			$args['orderby']  = 'meta_value';
			$args['meta_key'] = 'featured';
		} else {
			$args['orderby'] = $cars_orderby;
		}

		if ( isset( $request_method['s'] ) && ! empty( $request_method['s'] ) ) {
			$args['s'] = $request_method['s'];
		}

		if ( isset( $tax_query_arry ) && ! empty( $tax_query_arry ) ) {

			$args['tax_query'] = array( 'relation' => 'AND' );

			foreach ( $tax_query_arry as $k => $val ) {
				$args['tax_query'][ $k ] = $val;
			}
		}

		/* Set Price meta query  */
		$pgs_min_price = isset( $request_method['min_price'] ) ? esc_attr( $request_method['min_price'] ) : 0;
		$pgs_max_price = isset( $request_method['max_price'] ) ? esc_attr( $request_method['max_price'] ) : 0;
		if ( $pgs_min_price > 0 || $pgs_max_price > 0 ) {
			$prices = cardealer_get_car_filtered_price();
			$min    = floor( $prices->min_price );
			$max    = ceil( $prices->max_price );
			if ( $min !== $pgs_min_price || $max !== $pgs_max_price ) {
				$args['meta_query'][] = array(
					'key'     => 'final_price',
					'value'   => array( $pgs_min_price, $pgs_max_price ),
					'compare' => 'BETWEEN',
					'type'    => 'NUMERIC',
				);
			}
		}

		/* Don't want to show sold car on car listing page */
		if ( isset( $car_dealer_options['car_no_sold'] ) && 0 === (int) $car_dealer_options['car_no_sold'] ) {
			$args['meta_query'][] = array(
				'key'     => 'car_status',
				'value'   => 'sold',
				'compare' => '!=',
			);

		}

		if ( isset( $request_method['vehicle_location'] ) && ! empty( $request_method['vehicle_location'] ) ) {
			$args['meta_query'][] = array(
				'key'     => 'vehicle_location',
				'value'   => $request_method['vehicle_location'],
				'compare' => 'LIKE',
			);
		}
		return $args;
	}
}

add_action( 'wp_ajax_cardealer_cars_filter_query', 'cardealer_cars_filter_query' );
add_action( 'wp_ajax_nopriv_cardealer_cars_filter_query', 'cardealer_cars_filter_query' );
if ( ! function_exists( 'cardealer_cars_filter_query' ) ) {
	/**
	 * Filter query
	 */
	function cardealer_cars_filter_query() {

		$nonce = isset( $_REQUEST['query_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['query_nonce'] ) ) : '';
		if ( wp_verify_nonce( $nonce, 'cardealer_cars_filter_query_nonce' ) ) {
			$attributs = cardealer_get_all_filters_with_ajax();
			echo wp_json_encode( $attributs );
		}

		exit();
	}
}

add_action( 'wp_ajax_cardealer_load_more_vehicles', 'cardealer_load_more_vehicles' );
add_action( 'wp_ajax_nopriv_cardealer_load_more_vehicles', 'cardealer_load_more_vehicles' );
if ( ! function_exists( 'cardealer_load_more_vehicles' ) ) {
	/**
	 * Load more vehicles
	 */
	function cardealer_load_more_vehicles() {
		global $car_dealer_options;

		// @codingStandardsIgnoreStart
		$data_html            = '';
		$status               = 0;
		$filter_vars          = json_decode( stripslashes( $_POST['filter_vars'] ), true );
		$paged                = ( isset( $_POST['paged'] ) && ! empty( $_POST['paged'] ) ) ? sanitize_text_field( wp_unslash( $_POST['paged'] ) ) : 2;
		$filter_vars['paged'] = $paged;

		$first_page_records = 12;
		if ( isset( $car_dealer_options['cars-per-page'] ) ) {
			$first_page_records = $car_dealer_options['cars-per-page']; // records displayed on page load.
		}
		$records_processed = ( isset( $_POST['records_processed'] ) && ( 0 !== $_POST['records_processed'] ) ) ? sanitize_text_field( wp_unslash( $_POST['records_processed'] ) ) : $first_page_records;
		// @codingStandardsIgnoreEnd

		/**
		 * Filters the value of number of records to load per ajax call for lazyload vehicle listing.
		 *
		 * @since 1.0
		 *
		 * @param number         $first_page_records    Number of records to show on ajax call.
		 * @visible              true
		 */
		$filter_vars['cars_pp'] = apply_filters( 'cd_ajax_inventory_load_per_call', $first_page_records );

		$args    = cardealer_make_filter_wp_query( $filter_vars );
		$query   = new WP_Query( $args );
		$imgurls = array();

		// Check for nonce security.
		$nonce = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'load_more_vehicles_nonce' ) ) {
			$status    = 2;
			$data_html = '<div class="col-sm-12 load-status"><div class="alert alert-warning">' . esc_html__( 'Something is wrong please try again..!', 'cardealer' ) . '</div></div>';
		} else {
			if ( $query->have_posts() ) {
				$status = 1;
				while ( $query->have_posts() ) :
					$query->the_post();
					ob_start();
					$imgurls[] = cardealer_get_cars_image_src( 'car_catalog_image', get_the_ID() );
					get_template_part( 'template-parts/cars/content', 'cars' );
					$datahtml   = ob_get_clean();
					$data_html .= $datahtml;
				endwhile;
				$records_processed += $query->post_count;
				wp_reset_postdata();
			} else {
				$status    = 2;
				$data_html = '<div class="col-sm-12 load-status"><div class="alert alert-warning">' . esc_html__( 'All vehicles loaded..!', 'cardealer' ) . '</div></div>';
			}
		}

		echo wp_json_encode(
			array(
				'status'            => $status,
				'imgURLs'           => $imgurls,
				'data_html'         => $data_html,
				'paged'             => $paged + 1,
				'records_processed' => $records_processed,
			)
		);
		wp_die();
	}
}

if ( ! function_exists( 'cardealer_get_all_filters_with_ajax' ) ) {
	/**
	 * Filter with ajax
	 */
	function cardealer_get_all_filters_with_ajax() {
		$taxonomys = cardealer_get_filters_taxonomy();

		// @codingStandardsIgnoreStart
		if ( isset( $_REQUEST['selected_attr'] ) && ! empty( $_REQUEST['selected_attr'] ) ) {
			$taxonomys = explode( ',', $_REQUEST['selected_attr'] );
		}
		// @codingStandardsIgnoreEnd

		$result_filter   = array();
		$pagination_html = '';
		$data_html       = '';
		$data_order      = 'asc';
		$args            = cardealer_make_filter_wp_query( $_POST );

		if ( isset( $_POST['cars_order'] ) && ! empty( $_POST['cars_order'] ) ) {
			$data_order = sanitize_text_field( wp_unslash( $_POST['cars_order'] ) );
		}
		$paged = isset( $_POST['paged'] ) ? (int) $_POST['paged'] : 1;

		$query = new WP_Query( $args );
		/**
		 * Get data html
		 * */
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) :
				$query->the_post();
				ob_start();
				get_template_part( 'template-parts/cars/content', 'cars' );
				$datahtml   = ob_get_clean();
				$data_html .= $datahtml;
			endwhile;
			wp_reset_postdata();

			$pagination_html = cardealer_cars_pagination( false, $query, $paged );
		} else {
			$data_html = '<div class="col-sm-12"><div class="alert alert-warning">' . esc_html__( 'No result were found matching your selection.', 'cardealer' ) . '</div></<div>';
		}

		if ( ! isset( $_POST['cfb'] ) ) {
			$attributs = '<div class="listing_sort">';
		}
		if ( isset( $_POST['cfb'] ) && 'yes' === $_POST['cfb'] ) {
			$attributs = '';
		}

		$args['fields']        = 'ids';
		$args['no_found_rows'] = true;
		$filter_query_args     = array_replace( $args, array( 'posts_per_page' => -1 ) );
		$tax_query_arry        = cardealer_set_tex_query_array( $taxonomys, $_POST );
		$filter_query          = new WP_Query( $filter_query_args );
		$tot_result            = $filter_query->post_count;
		$filtered_makes        = array();

		if ( $filter_query->have_posts() ) {
			$filtered_makes = wp_get_object_terms( $filter_query->posts, 'car_make', array(
				'orderby' => 'name',
				'order'   => 'ASC',
				'fields'  => 'slugs',
			) );

			foreach ( $taxonomys as $tax ) {
				if ( isset( $tax_query_arry ) && ! empty( $tax_query_arry ) ) {
					$tax_args = array(
						'orderby' => 'name',
						'order'   => 'ASC',
						'fields'  => 'all',
					);
					$terms    = wp_get_object_terms( $filter_query->posts, $tax, $tax_args );

					foreach ( $terms as $tdata ) {

						if ( $tdata->taxonomy === $tax ) {
							$result_filter[ $tax ][] = array(
								'post_id'  => get_the_ID(),
								'term_id'  => $tdata->term_id,
								'slug'     => $tdata->slug,
								'name'     => $tdata->name,
								'taxonomy' => $tdata->taxonomy,
							);
						}
					}
				}
			}

			wp_reset_postdata();
		}
		$cardealer_ganerate_filter_box = cardealer_ganerate_filter_box( $taxonomys, $tax_query_arry, $result_filter );

		$html = '';
		if ( 'asc' === $data_order ) :
			$html .= '<a id="pgs_cars_order" data-order="desc" data-current_order="asc" href="javascript:void(0)"><i class="fas fa-arrow-up"></i></a>';
		endif;
		if ( 'desc' === $data_order ) :
			$html .= '<a id="pgs_cars_order" data-order="asc" data-current_order="desc" href="javascript:void(0)"><i class="fas fa-arrow-down"></i></a>';
		endif;
		if ( ! isset( $_POST['cfb'] ) ) {

			$vehicle_make = cdhl_vehicle_make_logos_html( $filtered_makes );

			$attributs .= '<div class="submit-filters-btn"><a class="button" href="javascript:void(0);" id="submit_all_filters">' . esc_html__( 'Submit', 'cardealer' ) . '</a></div>';
			$attributs .= '<div class=""><a class="button" href="javascript:void(0);" id="reset_filters">' . esc_html__( 'Reset All Filters', 'cardealer' ) . '</a></div>';
			$attributs .= '<span class="filter-loader"></span></div>';
			$data       = array(
				'status'          => 'success',
				'all_filters'     => $cardealer_ganerate_filter_box,
				'data_html'       => $data_html,
				'pagination_html' => $pagination_html,
				'order_html'      => $html,
				'tot_result'      => $tot_result,
				'vehicle_make'   => $vehicle_make,
			);
		} else {
			$data = array(
				'status'      => 'success',
				'all_filters' => $cardealer_ganerate_filter_box,
			);
		}
		return $data;
	}
}
if ( ! function_exists( 'cardealer_ganerate_filter_box' ) ) {
	/**
	 * Filter box
	 *
	 * @param string $taxonomys get the taxonomys.
	 * @param array  $tax_query_arry get tex query array.
	 * @param array  $result_filter .
	 */
	function cardealer_ganerate_filter_box( $taxonomys, $tax_query_arry = array(), $result_filter = array() ) {
		/**
		 * IF Request from custom search box Widgets
		 */
		$result_data = array();

		/**
		 * CFB used for custom filter box
		 * */
		if ( isset( $_POST['cfb'] ) && 'yes' === $_POST['cfb'] ) {
			$attributs = '';
			foreach ( $taxonomys as $tax ) {
				$taxonomy_name = get_taxonomy( $tax );
				$label         = $taxonomy_name->labels->menu_name;
				// Check filter array set.
				if ( isset( $tax_query_arry ) && ! empty( $tax_query_arry ) ) {
					$newarr = array();
					if ( isset( $result_filter[ $tax ] ) ) {
						foreach ( $result_filter[ $tax ] as $term_data ) {
							if ( 'car_mileage' === $tax ) {

								$mileage_array = cardealer_get_mileage_array();
								if ( 'car_mileage' === $tax && isset( $t ) && 1 === $t ) {
									foreach ( $mileage_array as $mileage ) {
										$result_data[ $tax ][] = array(
											$mileage => '&leq; ' . $mileage,
										);
									}
									$t++;
								}
							} else {
								$selected = '';
								if ( isset( $_POST[ $tax ] ) && '' !== $_POST[ $tax ] ) {
									if ( $_POST[ $tax ] === $term_data['slug'] ) {
										$selected = "selected='selected'";

									}
								}
								if ( ! in_array( $term_data['slug'], $newarr ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
									$newarr[]              = $term_data['slug'];
									$result_data[ $tax ][] = array(
										$term_data['slug'] => $term_data['name'],
									);
								}
							}
						}
					}
				} else {
					// When not set any filter.
					$terms = get_terms(
						array(
							'taxonomy'   => $tax,
							'hide_empty' => true,
						)
					);
					foreach ( $terms as $tdata ) {
						$selected = '';
						if ( isset( $_POST[ $tax ] ) && '' !== $_POST[ $tax ] ) {
							if ( $_POST[ $tax ] === $tdata->slug ) {
								$selected = "selected=''";
							}
						}

						$result_data[ $tax ][] = array(
							$tdata->slug => $tdata->name,
						);
					}
				}
			}
		} else {

			/**
			 * Without CFB
			 * */
			$tot_count = count( $taxonomys );
			$i         = 0;
			$t         = 1;
			foreach ( $taxonomys as $tax ) {
				$flg = 0;
				if ( isset( $tax_query_arry ) && ! empty( $tax_query_arry ) ) {
					$newarr = array();
					if ( isset( $result_filter[ $tax ] ) ) {
						foreach ( $result_filter[ $tax ] as $term_data ) {
							if ( 'car_mileage' === $tax ) {

								$mileage_array = cardealer_get_mileage_array();
								if ( 'car_mileage' === $tax && 1 === $t ) {
									foreach ( $mileage_array as $mileage ) {
										$result_data[ $tax ][] = array(
											$mileage => '&leq; ' . $mileage,
										);
									}
									$t++;
								}
							} else {
								$selected = '';
								if ( isset( $_POST[ $tax ] ) && '' !== $_POST[ $tax ] ) {
									if ( $_POST[ $tax ] === $term_data['slug'] ) {
										$selected = "selected='selected'";

									}
								}
								if ( ! in_array( $term_data['slug'], $newarr ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
									$newarr[]              = $term_data['slug'];
									$result_data[ $tax ][] = array(
										$term_data['slug'] => $term_data['name'],
									);
								}
							}
						}
					}
				} else {
					$terms = get_terms(
						array(
							'taxonomy'   => $tax,
							'hide_empty' => true,
						)
					);
					foreach ( $terms as $tdata ) {
						$selected = '';
						if ( isset( $_POST[ $tax ] ) && '' !== $_POST[ $tax ] ) {
							if ( $_POST[ $tax ] === $tdata->slug ) {
								$selected = "selected=''";
							}
						}

						$result_data[ $tax ][] = array(
							$tdata->slug => $tdata->name,
						);
					}
				}
			}
		}
		if ( isset( $_POST['current_attr'] ) && isset( $result_data[ $_POST['current_attr'] ] ) ) {
			unset( $result_data[ $_POST['current_attr'] ] );
		}
		return $result_data;
	}
}
if ( ! function_exists( 'cardealer_cars_pagination' ) ) {
	/**
	 * Cars pagination
	 *
	 * @param bool $echo .
	 * @param bool $query .
	 * @param bool $paged .
	 */
	function cardealer_cars_pagination( $echo = true, $query = null, $paged = null ) {
		if ( null !== $query || ! empty( $query ) ) {
			$wp_query = $query;
			if ( null !== $paged ) {
				$paged = ( 0 === $paged ) ? 1 : $paged;
			} else {
				$paged = ( 0 === get_query_var( 'paged' ) ) ? 1 : get_query_var( 'paged' );
			}
		} else {
			global $wp_query;
			$paged = ( 0 === get_query_var( 'paged' ) ) ? 1 : get_query_var( 'paged' );
		}

		$big   = 999999999; // need an unlikely integer.
		$pages = paginate_links(
			array(
				'base'      => str_replace( $big, '%#%', wp_specialchars_decode( esc_url( get_pagenum_link( $big ) ) ) ),
				'format'    => '?paged=%#%',
				'current'   => max( 1, $paged ),
				'total'     => $wp_query->max_num_pages,
				'type'      => 'array',
				'prev_next' => true,
				'prev_text' => esc_html__( '&larr; Prev', 'cardealer' ),
				'next_text' => esc_html__( 'Next &rarr;', 'cardealer' ),
			)
		);
		if ( is_array( $pages ) ) {
			$pagination = '<ul class="pagination">';
			foreach ( $pages as $page ) {
				$pagination .= "<li>$page</li>";
			}
			$pagination .= '</ul>';
			$pagination .= '<span class="pagination-loader"></span>';

			$pagination_escaped = wp_kses(
				$pagination,
				array(
					'ul'   => array(
						'class' => true,
					),
					'li'   => array(
						'class' => true,
					),
					'span' => array(
						'class'        => true,
						'aria-current' => true,
					),
					'a'    => array(
						'class' => true,
						'href'  => true,
					),
				)
			);

			if ( $echo ) {
				// This variable has been safely escaped in the following file: cardealer/includes/cars_functions.php Line: 3277.
				echo $pagination_escaped; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped.
			} else {
				// This variable has been safely escaped in the following file: cardealer/includes/cars_functions.php Line: 3277.
				return $pagination_escaped; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped.
			}
		}
	}
}

/**
* Menu search box auto complate search
*/
add_action( 'wp_ajax_pgs_auto_complate_search', 'cardealer_auto_complate_search' );
add_action( 'wp_ajax_nopriv_pgs_auto_complate_search', 'cardealer_auto_complate_search' );
if ( ! function_exists( 'cardealer_auto_complate_search' ) ) {
	/**
	 * Auto complate search
	 */
	function cardealer_auto_complate_search() {
		global $car_dealer_options;

		$nonce = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'cardealer_auto_complate_search_nonce' ) ) {
			$data[] = array(
				'status'   => false,
				'image'    => '',
				'link_url' => '',
				'title'    => '',
				'msg'      => '<div class="search-result-name">' . esc_html__( 'No Results', 'cardealer' ) . '</div>',
			);
			echo wp_json_encode( $data );
			exit();
		}

		$posttype = 'cars';
		if ( isset( $car_dealer_options['search_content_type'] ) ) {
			if ( 'all' === $car_dealer_options['search_content_type'] ) {
				$posttype = 'any';
			} else {
				$posttype = $car_dealer_options['search_content_type'];
			}
		}

		$args = array(
			'post_type'      => esc_attr( $posttype ),
			'post_status'    => 'publish',
			'posts_per_page' => defined( 'PHP_INT_MAX' ) ? PHP_INT_MAX : -1,
		);

		if ( isset( $_POST['search'] ) && ! empty( $_POST['search'] ) ) {
			$args['s'] = sanitize_text_field( wp_unslash( $_POST['search'] ) );
		}

		$query = new WP_Query( $args );
		$data  = array();
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) :
				$query->the_post();
				$pid     = get_the_ID();
				$car_img = '';
				$class   = 'no-image';
				$ptype   = get_post_type( $pid );
				if ( 'cars' === $ptype ) {
					$image   = cardealer_get_cars_image( 'cardealer-50x50', $pid );
					$car_img = '<div class="search-result-image">' . $image . '</div>';
					$class   = '';
				} else {
					if ( has_post_thumbnail( $pid ) ) {
						$thmb    = get_the_post_thumbnail( $pid, 'cardealer-50x50' );
						$car_img = '<div class="search-result-image">' . $thmb . '</div>';
						$class   = '';
					}
				}
				$data[] = array(
					'status'   => true,
					'image'    => $car_img,
					'link_url' => get_the_permalink(),
					'title'    => '<div class="search-result-name ' . $class . '">' . get_the_title() . '</div>',
					'msg'      => '',
				);
			endwhile;
			wp_reset_postdata();
		} else {
			$data[] = array(
				'status'   => false,
				'image'    => '',
				'link_url' => '',
				'title'    => '',
				'msg'      => '<div class="search-result-name">' . esc_html__( 'No Results', 'cardealer' ) . '</div>',
			);
		}
		echo wp_json_encode( $data );
		exit();
	}
}

/**
* List page search box auto complate search filters and sidebar area
*/
add_action( 'wp_ajax_pgs_cars_list_search_auto_compalte', 'cars_list_search_auto_compalte' );
add_action( 'wp_ajax_nopriv_pgs_cars_list_search_auto_compalte', 'cars_list_search_auto_compalte' );
if ( ! function_exists( 'cars_list_search_auto_compalte' ) ) {
	/**
	 * Search auto complate
	 */
	function cars_list_search_auto_compalte() {

		// Check for nonce security.
		$nonce = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'pgs_cars_list_search_auto_compalte_nonce' ) ) {
			$data[] = array(
				'status'   => false,
				'image'    => '',
				'link_url' => '',
				'title'    => '',
				'msg'      => '<div class="search-result-name">' . esc_html__( 'Something is wrong please try again..!', 'cardealer' ) . '</div>',
			);

		} else {

			if ( isset( $_POST['search'] ) && ! empty( $_POST['search'] ) ) {
				$data      = array();
				$search    = trim( sanitize_text_field( wp_unslash( $_POST['search'] ) ) );
				$_GET['s'] = $search;
				$args      = cardealer_make_filter_wp_query( $_GET );

				$query = new WP_Query( $args );
				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) :
						$query->the_post();
						$pid     = get_the_ID();
						$car_img = '';
						$class   = 'no-image';
						$ptype   = get_post_type( $pid );
						if ( 'cars' === $ptype ) {
							$image   = cardealer_get_cars_image( 'cardealer-50x50', $pid );
							$car_img = '<div class="search-result-image">' . $image . '</div>';
							$class   = '';
						} else {
							if ( has_post_thumbnail( $pid ) ) {
								$thmb    = get_the_post_thumbnail( $pid, 'cardealer-50x50' );
								$car_img = '<div class="search-result-image">' . $thmb . '</div>';
								$class   = '';
							}
						}

						$data[] = array(
							'status'   => true,
							'image'    => $car_img,
							'link_url' => get_the_permalink(),
							'title'    => '<div class="search-result-name ' . $class . '">' . get_the_title() . '</div>',
							'msg'      => '',
						);
					endwhile;
					wp_reset_postdata();
				} else {
					$data[] = array(
						'status'   => false,
						'image'    => '',
						'link_url' => '',
						'title'    => '',
						'msg'      => '<div class="search-result-name">' . esc_html__( 'No Results', 'cardealer' ) . '</div>',
					);
				}
			} else {
				$data[] = array(
					'status'   => false,
					'image'    => '',
					'link_url' => '',
					'title'    => '',
					'msg'      => '<div class="search-result-name">' . esc_html__( 'No Results', 'cardealer' ) . '</div>',
				);
			}
		}
		echo wp_json_encode( $data );

		exit();
	}
}
if ( ! function_exists( 'cardealer_validate_google_captch' ) ) {
	/**
	 * Validate google captch.
	 *
	 * @param string $captcha .
	 */
	function cardealer_validate_google_captch( $captcha ) {
		$secret_key = cardealer_get_goole_api_keys( 'secret_key' );
		if ( empty( $secret_key ) ) {
			return array( 'success' => true );
		}
		$response = array();

		if ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
			$response = wp_remote_get( 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $captcha . '&remoteip=' . sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ), array( 'timeout' => 30 ) );
		}

		if ( ! empty( $response ) && isset( $response['body'] ) ) {
			return json_decode( $response['body'], true );
		} else {
			return array( 'success' => false );
		}
	}
}
if ( ! function_exists( 'cardealer_get_goole_api_keys' ) ) {
	/**
	 * Get google api
	 *
	 * @param string $key_type .
	 */
	function cardealer_get_goole_api_keys( $key_type = '' ) {
		global $car_dealer_options;

		$key = '';

		$site_key   = ( isset( $car_dealer_options['google_captcha_site_key'] ) && ! empty( $car_dealer_options['google_captcha_site_key'] ) ) ? $car_dealer_options['google_captcha_site_key'] : '';
		$secret_key = ( isset( $car_dealer_options['google_captcha_secret_key'] ) && ! empty( $car_dealer_options['google_captcha_secret_key'] ) ) ? $car_dealer_options['google_captcha_secret_key'] : '';

		if ( ( ! empty( $site_key ) && ! empty( $secret_key ) ) && 'site_key' === $key_type ) {
			$key = $site_key;
		}

		if ( ( ! empty( $site_key ) && ! empty( $secret_key ) ) && 'secret_key' === $key_type ) {
			$key = $secret_key;
		}

		return $key;
	}
}
if ( ! function_exists( 'cardealer_photoswipe' ) ) {
	/**
	 *   Photo swipe popup for cars
	 */
	function cardealer_photoswipe() {
		?>
		<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="pswp__bg"></div>
			<div class="pswp__scroll-wrap">
				<div class="pswp__container">
					<div class="pswp__item"></div>
					<div class="pswp__item"></div>
					<div class="pswp__item"></div>
				</div>
				<div class="pswp__ui pswp__ui--hidden">
					<div class="pswp__top-bar">
						<div class="pswp__counter"></div>
						<button class="pswp__button pswp__button--close" title="<?php esc_attr_e( 'Close (Esc)', 'cardealer' ); ?>"></button>
						<button class="pswp__button pswp__button--fs" title="<?php esc_attr_e( 'Toggle fullscreen', 'cardealer' ); ?>"></button>
						<button class="pswp__button pswp__button--zoom" title="<?php esc_attr_e( 'Zoom in/out', 'cardealer' ); ?>"></button>

						<div class="pswp__preloader">
							<div class="pswp__preloader__icn">
							<div class="pswp__preloader__cut">
								<div class="pswp__preloader__donut"></div>
							</div>
							</div>
						</div>
					</div>
					<div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
						<div class="pswp__share-tooltip"></div>
					</div>
					<button class="pswp__button pswp__button--arrow--left" title="<?php esc_attr_e( 'Previous (arrow left)', 'cardealer' ); ?>">
					</button>
					<button class="pswp__button pswp__button--arrow--right" title="<?php esc_attr_e( 'Next (arrow right)', 'cardealer' ); ?>">
					</button>
					<div class="pswp__caption">
						<div class="pswp__caption__center"></div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	add_action( 'wp_footer', 'cardealer_photoswipe' );
}
add_action( 'admin_init', 'cardealer_remove_metabox' );
if ( ! function_exists( 'cardealer_remove_metabox' ) ) {
	/**
	 * Remove metabox.
	 */
	function cardealer_remove_metabox() {
		remove_meta_box( 'tagsdiv-car_year', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_make', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_model', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_body_style', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_condition', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_mileage', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_transmission', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_drivetrain', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_engine', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_fuel_economy', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_exterior_color', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_interior_color', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_stock_number', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_vin_number', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_fuel_type', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_trim', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_features_options', 'cars', 'side' );
		remove_meta_box( 'car_features_optionsdiv', 'cars', 'side' );
		remove_meta_box( 'car_vehicle_review_stampsdiv', 'cars', 'side' );

		// Get all taxnomies for cars post type.
		$taxonomies_raw = get_object_taxonomies( 'cars' );

		foreach ( $taxonomies_raw as $new_tax ) {
			$new_tax_obj = get_taxonomy( $new_tax );
			if( isset( $new_tax_obj->include_in_filters ) && $new_tax_obj->include_in_filters == true ) {
				if ( false == $new_tax_obj->hierarchical ) {
					remove_meta_box( "tagsdiv-{$new_tax}", 'cars', 'side' );
				} else {
					remove_meta_box( "{$new_tax}div", 'cars', 'side' );
				}
			}
		}

	}
}
if ( ! function_exists( 'cardealer_get_vehicles_taxonomies' ) ) {
	/**
	 * Get car gurubad
	 *
	 * @param array $taxonomies_unset .
	 * @param array $return_array_type .
	 */
	function cardealer_get_vehicles_taxonomies( $taxonomies_unset = array(), $return_array_type = 'val_to_key' ) {
		if ( ! empty( $taxonomies_unset ) ) {
			$unset_taxonomies = $taxonomies_unset;
		} else {
			$unset_taxonomies = array( 'car_features_options' );
		}
		$taxonomies = get_object_taxonomies( 'cars' );
		foreach ( $unset_taxonomies as $taxo ) {
			if ( array_search( $taxo, $taxonomies ) !== false ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
				$key = array_search( $taxo, $taxonomies ); // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
				unset( $taxonomies[ $key ] );
			}
		}
		$taxonomy_array = array();
		if ( 'val_to_key' === $return_array_type ) { // array( taxonomy_label => taxonomy_key ).
			foreach ( $taxonomies as $taxonomy ) {
				$tax_obj                           = get_taxonomy( $taxonomy );
				$taxonomy_array[ $tax_obj->label ] = $taxonomy;
			}
		} else { // array( taxonomy_key => taxonomy_label ).
			foreach ( $taxonomies as $taxonomy ) {
				$tax_obj                     = get_taxonomy( $taxonomy );
				$taxonomy_array[ $taxonomy ] = $tax_obj->label;
			}
		}
		/**
		 * Filters the vehicle taxonomy array .
		 *
		 * @since 1.0
		 *
		 * @param array    $taxonomy_array   A list of taxonomies with taxonomy slug to taxonomy label pair.
		 * @visible        true
		 */
		return apply_filters( 'cardealer_vehicles_taxonomies', $taxonomy_array );
	}
}

if ( ! function_exists( 'cardealer_get_car_guru_badge_html' ) ) {
	/**
	 * Get car gurubad
	 *
	 * @param string $id .
	 */
	function cardealer_get_car_guru_badge_html( $id ) {
		global $car_dealer_options;
		if ( empty( $id ) || ! isset( $car_dealer_options['enable_carguru'] ) || ( '0' === $car_dealer_options['enable_carguru'] ) ) {
			return;
		}
		$vin = wp_get_post_terms( $id, 'car_vin_number' );
		if ( isset( $vin[0]->name ) && ! empty( $vin[0]->name ) ) {
			$price_arr = cardealer_get_car_price_array( $id );
			if ( ! empty( $price_arr ) ) {

				$final_price = $price_arr['regular_price'];
				if ( 0 < $price_arr['sale_price'] ) {
					$final_price = $price_arr['sale_price'];
				}
				return '<span class="cd-vehicle-gurus" data-cg-vin="' . esc_attr( $vin[0]->name ) . '" data-cg-price="' . esc_attr( $final_price ) . '"></span>';
			}
		}
	}
}

add_filter( 'cardealer_vrs_link_html', 'cardealer_update_stamp_html', 10, 2 );
if ( ! function_exists( 'cardealer_update_stamp_html' ) ) {
	/**
	 * Update stamp html
	 *
	 * @param string $html .
	 * @param string $id .
	 */
	function cardealer_update_stamp_html( $html, $id ) {
		$carguru_html = cardealer_get_car_guru_badge_html( $id );
		if ( ! empty( $carguru_html ) ) {
			$html .= $carguru_html;
		}
		return $html;
	}
}
if ( ! function_exists( 'cardealer_term_redirect' ) ) {
	/**
	 * TAXONOMY REDIRECT CODE START
	 * Function used to redirect page from 404 to car archive when term of the taxonomy is called in URL, But not available in taxonomy
	 * If this is not used page will redirect to 404 when term(which is not available for taxonomy) called in URL.
	 */
	function cardealer_term_redirect() {
		global $wp_query;

		if ( $wp_query->is_404() ) {
			if ( isset( $wp_query->query['post_type'] ) && 'cars' === $wp_query->query['post_type'] ) {
				if ( isset( $wp_query->query['car_condition'] ) ) {
					$car_condition = strtolower( $wp_query->query['car_condition'] );
					if ( in_array( $car_condition, array( 'used', 'certified', 'new', 'n', 'u', 'c' ) ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
						add_filter( 'cardealer_page_title', 'cardealer_term_page_title' ); // Page Title .
						add_filter( 'cardealer_subtitle_title', 'cardealer_term_subtitle_title' ); // Sub Title .
						add_filter( 'document_title_parts', 'cardealer_vehicle_browser_title', 99 ); // Browser Title .
						add_filter( 'template_include', 'cardealer_term_template_redirect' ); // Redirect to archive-cars.php .
					}
				}
			}
		}
	}
	add_action( 'wp', 'cardealer_term_redirect' );
}
if ( ! function_exists( 'cardealer_vehicle_browser_title' ) ) {
	/**
	 * Vehicle browser title
	 *
	 * @param string $title .
	 */
	function cardealer_vehicle_browser_title( $title ) {
		$title_array = cardealer_get_vehicle_page_titles();
		if ( ! empty( $title_array['title'] ) ) {
			$title['title'] = $title_array['title'];
		}
		return $title;
	}
}
if ( ! function_exists( 'cardealer_term_template_redirect' ) ) {
	/**
	 * Term template redirect
	 *
	 * @param string $template .
	 */
	function cardealer_term_template_redirect( $template ) {
		$template = get_query_template( 'archive-cars' );
		return $template;
	}
}
if ( ! function_exists( 'cardealer_term_page_title' ) ) {
	/**
	 * Term page title
	 *
	 * @param string $title .
	 */
	function cardealer_term_page_title( $title ) {
		$title_array = cardealer_get_vehicle_page_titles();
		if ( ! empty( $title_array['title'] ) ) {
			return $title_array['title'];
		}
		return $title;
	}
}
if ( ! function_exists( 'cardealer_term_subtitle_title' ) ) {
	/**
	 * Term subtitle
	 *
	 * @param string $subtitle .
	 */
	function cardealer_term_subtitle_title( $subtitle ) {
		$subtitle_array = cardealer_get_vehicle_page_titles();
		if ( ! empty( $subtitle_array['subtitle'] ) ) {
			return $subtitle_array['subtitle'];
		}
		return $subtitle;
	}
}
if ( ! function_exists( 'cardealer_get_vehicle_page_titles' ) ) {
	/**
	 * Vehicle page titles
	 */
	function cardealer_get_vehicle_page_titles() {
		global $car_dealer_options;
		$titles = array(
			'title'    => '',
			'subtitle' => '',
		);
		// Theme option vehicle inventory page title.
		$cars_listing_title = ( isset( $car_dealer_options['cars-listing-title'] ) ) ? $car_dealer_options['cars-listing-title'] : '';
		$page_title         = '';
		if ( isset( $car_dealer_options['cars_inventory_page'] ) && ! empty( $car_dealer_options['cars_inventory_page'] ) ) {
			$car_page  = get_post( $car_dealer_options['cars_inventory_page'] );
			$page_path = isset( $car_page->post_name ) ? $car_page->post_name : 'cars';
			$page      = get_page_by_path( $page_path );
			if ( $page ) {
				$page_title         = get_the_title( $page );
				$titles['subtitle'] = get_post_meta( $page->ID, 'subtitle', true );
			}
		} else {
			$page_title = $cars_listing_title;
		}

		if ( isset( $page_title ) && ! empty( $page_title ) ) {
			$title = $page_title;
		} else {
			$title = post_type_archive_title( '', false );
		}
		$titles['title'] = $title;
		return $titles;
	}
}
if ( ! function_exists( 'cardealer_get_inv_list_style' ) ) {
	/**
	 * Get inv list style
	 */
	function cardealer_get_inv_list_style() {
		global $car_dealer_options;
		$list_style = 'default';
		if ( isset( $car_dealer_options['inv-list-style'] ) && ! empty( $car_dealer_options['inv-list-style'] ) ) {
			$list_style = $car_dealer_options['inv-list-style'];
		}
		return $list_style;
	}
}
if ( ! function_exists( 'cardealer_get_cars_image_src' ) ) {
	/**
	 * TAXONOMY REDIRECT CODE END
	 *
	 * @param string $car_size .
	 * @param bool   $id .
	 */
	function cardealer_get_cars_image_src( $car_size = 'car_catalog_image', $id = null ) {
		if ( empty( $car_size ) ) {
			$car_size = 'car_catalog_image';
		}
		global $post;
		$car_id = ( isset( $id ) && null !== $id ) ? $id : $post->ID;
		if ( function_exists( 'get_field' ) ) {
			$images = get_field( 'car_images', $car_id );
			if ( isset( $images ) && ! empty( $images ) ) {
				$img_url = esc_url( $images[0]['sizes'][ $car_size ] );
			} else {
				$img_url = cardealer_get_carplaceholder( $car_size, 'url' );
			}
		} else {
			$img_url = cardealer_get_carplaceholder( $car_size, 'url' );
		}
		return $img_url;
	}
}
if ( ! function_exists( 'cardealer_invenory_pg_vc_css' ) ) {
	/**
	 * VC Style Sheets
	 */
	function cardealer_invenory_pg_vc_css() {
		global $car_dealer_options;
		$inventory_pg_id = (int) cardealer_get_current_post_id();
		$front_page      = (int) get_option( 'page_on_front' );

		if ( isset( $car_dealer_options['cars_inventory_page'] ) && ! empty( $car_dealer_options['cars_inventory_page'] ) && (int) $car_dealer_options['cars_inventory_page'] === $front_page ) {
			$inventory_pg_id = $front_page;
		}

		if ( isset( $car_dealer_options['cars_inventory_page'] ) ) {
			if ( ! is_wp_error( $inventory_pg_id ) && (int) $car_dealer_options['cars_inventory_page'] === $inventory_pg_id ) {
				$shortcodes_custom_css = get_post_meta( $inventory_pg_id, '_wpb_shortcodes_custom_css', true );
				if ( ! empty( $shortcodes_custom_css ) ) {
					$shortcodes_custom_css = strip_tags( $shortcodes_custom_css );
					wp_add_inline_style( 'cardealer-main', $shortcodes_custom_css );
				}
			}
		}
	}
	add_action( 'wp_enqueue_scripts', 'cardealer_invenory_pg_vc_css', 160 );

}
if ( ! function_exists( 'cardealer_list_layout_style_lazyload' ) ) {
	/**
	 * List layout style lazyload
	 *
	 * @param string $layout .
	 */
	function cardealer_list_layout_style_lazyload( $layout ) {
		global $car_dealer_options;
		if ( isset( $car_dealer_options['vehicle-listing-layout'] ) && ! empty( $car_dealer_options['vehicle-listing-layout'] ) ) {
			$listing_layout = $car_dealer_options['vehicle-listing-layout'];
			if ( 'lazyload' === $listing_layout ) {
				$layout = 'view-grid-left';
			}
		}
		return $layout;
	}
	add_filter( 'cardealer_list_layout_style', 'cardealer_list_layout_style_lazyload' );

}
add_action(
	'init',
	function() {
		global $car_dealer_options;
		if ( isset( $car_dealer_options['vehicle-listing-layout'] ) && ! empty( $car_dealer_options['vehicle-listing-layout'] ) ) {
			$listing_layout = $car_dealer_options['vehicle-listing-layout'];
			if ( 'lazyload' === $listing_layout ) {
				if ( isset( $_COOKIE['lay_style'] ) ) {
					setcookie( 'lay_style', 'view-grid-left', time() + ( 10 * 365 * 24 * 60 * 60 ) );
				}
				if ( isset( $_COOKIE['cars_grid'] ) ) {
					setcookie( 'cars_grid', 'yes', time() + ( 10 * 365 * 24 * 60 * 60 ) );
				}
			}
		}
	}
);
if ( ! function_exists( 'cardealer_set_vehicle_list_view_type' ) ) {
	/**
	 * Vehicle list view type
	 *
	 * @param string $cars_grid .
	 */
	function cardealer_set_vehicle_list_view_type( $cars_grid ) {
		global $car_dealer_options;
		if ( isset( $car_dealer_options['vehicle-listing-layout'] ) && ! empty( $car_dealer_options['vehicle-listing-layout'] ) ) {
			$listing_layout = $car_dealer_options['vehicle-listing-layout'];
			if ( 'lazyload' === $listing_layout ) {
				return 'yes';
			}
		}
		return $cars_grid;
	}
	add_filter( 'cardealer_vehicle_list_view_type', 'cardealer_set_vehicle_list_view_type' );

}

function cdhl_vehicle_make_logos_html( $filtered_makes ) {
	$make_widgets_html = array();
	$make_widgets      = ( isset( $_REQUEST['make_widgets'] ) && ! empty( $_REQUEST['make_widgets'] ) ) ? $_REQUEST['make_widgets'] : array();
	$make_generator    = new CDHL_Vehicle_Make_Logos_Generator();

	// $make_generator->set_current_make();

	$car_makes = $make_generator->get_makes();

	foreach ( $make_widgets as $widget => $widget_args ) {
		$make_widgets_html[ $widget ] = '';

		if ( ! is_array( $car_makes ) || empty( $car_makes ) ) {
			continue;
		}

		$widget_args['include_makes'] = $filtered_makes;

		ob_start();
		$make_generator->generate_makes( $widget_args );
		$make_widgets_html[ $widget ] = ob_get_clean();;
	}

	return $make_widgets_html;
}
