<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Register Scripts and Styles for cardealer.
 *
 * @package cardealer
 */

if ( ! function_exists( 'cardealer_google_fonts_url' ) ) {
	/**
	 * Register Google fonts
	 */
	function cardealer_google_fonts_url() {
		global $car_dealer_options;
		$fonts_url = '';
		$fonts     = array();
		$subsets   = 'latin,latin-ext';

		$font_family = array();
		// body fonts.
		if ( ! empty( $car_dealer_options['opt-typography-body']['font-family'] ) ) {
			$font_family[] = $car_dealer_options['opt-typography-body']['font-family'];
		}
		// heading fonts.
		for ( $h_tag = 1; $h_tag <= 6; $h_tag++ ) {
			if ( isset( $car_dealer_options[ 'opt-typography-h' . $h_tag ] ) && ! empty( $car_dealer_options[ 'opt-typography-h' . $h_tag ] ) ) {
				array_push( $font_family, $car_dealer_options[ 'opt-typography-h' . $h_tag ]['font-family'] );
			}
		}
		$font_family = array_unique( $font_family ); // remove duplicate fonts.

		foreach ( $font_family as $font ) {
			if ( 'off' !== _x( 'on', $font . ' font: on or off', 'cardealer' ) ) { // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralContext
				$fonts[] = $font . ':400,300,400italic,600,600italic,700,700italic,800,800italic,300italic';
			}
		}

		if ( empty( $font_family ) ) {
			/* translators: If there are characters in your language that are not supported by Open+Sans, translate this to 'off'. Do not translate into your own language. */
			if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'cardealer' ) ) {
				$fonts[] = 'Open Sans:400,300,400italic,600,600italic,700,700italic,800,800italic,300italic';
			}
			/* translators: If there are characters in your language that are not supported by Raleway, translate this to 'off'. Do not translate into your own language. */
			if ( 'off' !== _x( 'on', 'Lato font: on or off', 'cardealer' ) ) {
				$fonts[] = 'Lato:400,100,100italic,300,300italic,400italic,700,700italic,900,900italic';
			}
		}

		if ( $fonts ) {
			$fonts_url = add_query_arg(
				array(
					'family' => rawurlencode( implode( '|', $fonts ) ),
					'subset' => rawurlencode( $subsets ),
				),
				'https://fonts.googleapis.com/css'
			);
		}
		return $fonts_url;
	}
}
if ( ! function_exists( 'cardealer_load_style_script' ) ) {
	/**
	 * Add script and style on front side
	 */
	function cardealer_load_style_script() {
		global $car_dealer_options,$post, $cardealer_theme_data;
		$google_key = cardealer_get_google_api_key();
		/* register Theme style */
		wp_enqueue_style( 'cardealer-google-fonts', cardealer_google_fonts_url(), array(), '1.0.0' );// Google Fonts.
		if ( is_rtl() ) {
			wp_register_style( 'bootstrap', CARDEALER_URL . '/css/bootstrap-rtl.min.css', array(), '3.3.7' );
		} else {
			wp_register_style( 'bootstrap', CARDEALER_URL . '/css/bootstrap.min.css', array(), '3.3.5' );
		}

		if ( wp_style_is( 'font-awesome', 'registered' ) ) {
			wp_deregister_style( 'font-awesome' );
		}

		wp_enqueue_style( 'font-awesome-shims', CARDEALER_URL . '/fonts/font-awesome/css/v4-shims.min.css', array(), '5.12.0' );
		wp_enqueue_style( 'font-awesome', CARDEALER_URL . '/fonts/font-awesome/css/all.min.css', array(), '5.12.0' );
		wp_register_style( 'cardealer-flaticon', CARDEALER_URL . '/css/flaticon.min.css', array(), CARDEALER_VERSION );
		wp_register_style( 'jquery-ui', CARDEALER_URL . '/css/jquery-ui/jquery-ui.min.css', array(), '1.11.4' );
		wp_register_style( 'cardealer-external', CARDEALER_URL . '/css/plugins-css.min.css', array(), CARDEALER_VERSION );
		wp_register_style( 'cardealer-mega_menu', CARDEALER_URL . '/css/mega-menu/mega_menu.min.css', array(), CARDEALER_VERSION );
		wp_register_style( 'cardealer-timepicker', CARDEALER_URL . '/css/timepicker/jquery.timepicker.css', array(), CARDEALER_VERSION );
		wp_register_style( 'cardealer-main', CARDEALER_URL . '/css/style.css', array(), CARDEALER_VERSION );
		wp_register_style( 'cardealer-woocommerce', CARDEALER_URL . '/css/woocommerce.css', array( 'woocommerce-general' ), array(), '' );
		wp_register_style( 'cardealer-responsive', CARDEALER_URL . '/css/responsive.css', array(), CARDEALER_VERSION );
		wp_register_style( 'cardealer-slick', CARDEALER_URL . '/css/slick/slick.css', array(), CARDEALER_VERSION );
		wp_register_style( 'cardealer-slick-theme', CARDEALER_URL . '/css/slick/slick-theme.css', array(), CARDEALER_VERSION );
		wp_register_style( 'cardealer-css-nice-select', CARDEALER_URL . '/css/nice-select.min.css', array(), CARDEALER_VERSION );
		wp_register_style( 'photoswipe-css', CARDEALER_URL . '/css/photoswipe/photoswipe.min.css', array(), '4.1.3' );
		wp_register_style( 'default-skin', CARDEALER_URL . '/css/photoswipe/default-skin/default-skin.min.css', array(), '4.1.3' );
		wp_register_style( 'magnific-popup', CARDEALER_URL . '/css/magnific-popup/magnific-popup.min.css', array(), '1.1.0' );

		// WPBakery/Elementor Specific CSS
		wp_register_style( 'cardealer-shortcodes', CARDEALER_URL . '/css/shortcodes.css', array(), $cardealer_theme_data->get('Version') );
		wp_register_style( 'cardealer-shortcodes-responsive', CARDEALER_URL . '/css/shortcodes-responsive.css', array(), $cardealer_theme_data->get('Version') );
		// wp_register_style( 'cardealer-elementor', CARDEALER_URL . '/css/elementor.css', array(), $cardealer_theme_data->get('Version') );
		// wp_register_style( 'cardealer-elementor-responsive', CARDEALER_URL . '/css/elementor-responsive.css', array(), $cardealer_theme_data->get('Version') );

		/*enqueue Theme style */
		wp_enqueue_style( 'cardealer-flaticon' );
		wp_enqueue_style( 'bootstrap' );
		wp_enqueue_style( 'cardealer-mega_menu' );

		wp_enqueue_style( 'cardealer-css-nice-select' );
		wp_enqueue_style( 'cardealer-external' );
		wp_enqueue_style( 'cardealer-timepicker' );
		wp_enqueue_style( 'jquery-ui' );
		wp_enqueue_style( 'magnific-popup' );
		wp_enqueue_style( 'photoswipe-css' );
		wp_enqueue_style( 'default-skin' );
		wp_enqueue_style( 'wp-mediaelement' );

		wp_enqueue_style( 'cardealer-shortcodes' );     // WPBakery Shorcodes
		// wp_enqueue_style( 'cardealer-elementor' );   // Elementor Widgets
		wp_enqueue_style( 'cardealer-main' );
		wp_enqueue_style( 'cardealer-shortcodes-responsive' );    // WPBakery Shorcodes Responsive
		// wp_enqueue_style( 'cardealer-elementor-responsive' );  // Elementor Widgets Responsive
		wp_enqueue_style( 'cardealer-responsive' );

		if ( class_exists( 'WooCommerce' ) ) {
			wp_enqueue_style( 'cardealer-woocommerce' );
		}
		/* Add custom CSS */
		if ( isset( $car_dealer_options['custom_css'] ) ) {
			$custom_css = trim( wp_strip_all_tags( $car_dealer_options['custom_css'] ) );
			if ( ! empty( $custom_css ) ) {
				wp_add_inline_style( 'cardealer-main', $custom_css );
			}
		}

		/*register SCripts */
		wp_register_script( 'bootsrap', CARDEALER_URL . '/js/bootstrap.min.js', array(), '3.3.7', true );
		wp_register_script( 'cardealer-external', CARDEALER_URL . '/js/plugins-jquery.min.js', array(), CARDEALER_VERSION, true );
		wp_register_script( 'cardealer-js-appear', CARDEALER_URL . '/js/jquery.appear.js', array(), CARDEALER_VERSION, true );
		wp_register_script( 'magnific-popup', CARDEALER_URL . '/js/magnific-popup/jquery.magnific-popup.min.js', array(), '1.1.0', true );
		wp_register_script( 'countTo-js', CARDEALER_URL . '/js/counter/jquery.countTo.min.js', array(), CARDEALER_VERSION, true );
		wp_register_script( 'cardealer-countdown', CARDEALER_URL . '/js/countdown/jquery.downCount.min.js', array(), CARDEALER_VERSION, true );
		wp_register_script( 'cardealer-timepicker', CARDEALER_URL . '/js/timepicker/jquery.timepicker.js', array(), CARDEALER_VERSION, true );
		wp_register_script( 'cardealer-mega_menu', CARDEALER_URL . '/js/mega-menu/mega_menu.min.js', array(), CARDEALER_VERSION, true );
		wp_register_script( 'cardealer-shuffle', CARDEALER_URL . '/js/shuffle/shuffle.min.js', array(), CARDEALER_VERSION, true );
		wp_register_script( 'sticky-kit', CARDEALER_URL . '/js/sticky-kit/sticky-kit.js', array(), '1.1.2', true );
		wp_register_script( 'cardealer-nice-select', CARDEALER_URL . '/js/jquery.nice-select.min.js', array(), CARDEALER_VERSION, true );
		wp_register_script( 'jquery-dotdotdot', CARDEALER_URL . '/js/jquery.dotdotdot.min.js', array(), '1.8.3', true );

		wp_register_script( 'cardealer-google-maps-apis', 'https://maps.googleapis.com/maps/api/js?key=' . $google_key, array(), CARDEALER_VERSION, true );
		wp_register_script( 'cardealer-google-recaptcha-apis', 'https://www.google.com/recaptcha/api.js?onload=doCaptcha&render=explicit', array( 'cardealer-js' ), CARDEALER_VERSION, true );
		wp_register_script( 'cardealer-google-maps-script', CARDEALER_URL . '/js/map/map.js', array(), CARDEALER_VERSION, true );
		wp_register_script( 'cars_customs', CARDEALER_URL . '/js/cars_customs.js', array(), CARDEALER_VERSION, true );
		wp_register_script( 'cardealer-js', CARDEALER_URL . '/js/custom.js', array( 'jquery' ), CARDEALER_VERSION, true );

		wp_register_script( 'cardealer-cookie', CARDEALER_URL . '/js/cookie/cookies.min.js', array(), CARDEALER_VERSION, true );
		wp_register_script( 'photoswipe-js', CARDEALER_URL . '/js/photoswipe/photoswipe.min.js', array(), '4.1.2', true );
		wp_register_script( 'photoswipe-ui-default', CARDEALER_URL . '/js/photoswipe/photoswipe-ui-default.min.js', array(), '4.1.2', true );
		wp_register_script( 'inputmask', CARDEALER_URL . '/js/inputmask/jquery.inputmask.bundle.js', array(), '3.3.4', true );

		wp_register_script( 'lazyload', CARDEALER_URL . '/js/lazyload/lazyload.js', array(), '2.0.0', true );

		if ( isset( $car_dealer_options['cars_inventory_page'] ) && ! empty( $car_dealer_options['cars_inventory_page'] ) ) {
			$car_url = get_permalink( $car_dealer_options['cars_inventory_page'] );
			if ( function_exists( 'icl_object_id' ) ) {
				$lang    = defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : null;
				$car_url = apply_filters( 'wpml_permalink', $car_url, $lang );
			}
		} else {
			$car_url = get_post_type_archive_link( 'cars' );
		}
		$is_vehicle_cat = false;
		$vehicle_cat    = '';
		if ( is_tax( 'vehicle_cat' ) ) {
			global $wp,$wp_query;
			$car_url        = home_url( $wp->request );
			$is_vehicle_cat = true;
			$vehicle_cat    = $wp_query->query_vars['vehicle_cat'];
		}

		$decimal_places            = ( ! empty( $car_dealer_options['cars-number-decimals'] ) && is_numeric( $car_dealer_options['cars-number-decimals'] ) ) ? $car_dealer_options['cars-number-decimals'] : 0;
		$decimal_separator_symbol  = ( isset( $car_dealer_options['cars-decimal-separator'] ) && ! empty( $car_dealer_options['cars-decimal-separator'] ) ) ? $car_dealer_options['cars-decimal-separator'] : '.';
		$thousand_seperator_symbol = ( isset( $car_dealer_options['cars-thousand-separator'] ) && ! empty( $car_dealer_options['cars-thousand-separator'] ) ) ? $car_dealer_options['cars-thousand-separator'] : '';
		wp_localize_script(
			'cars_customs',
			'cars_price_slider_params',
			array(
				'currency_symbol'                          => cardealer_get_cars_currency_symbol(),
				'currency_pos'                             => cardealer_get_cars_currency_placement(),
				'decimal_places'                           => $decimal_places,
				'decimal_separator_symbol'                 => $decimal_separator_symbol,
				'thousand_seperator_symbol'                => $thousand_seperator_symbol,
				'min_price'                                => isset( $_GET['min_price'] ) ? sanitize_text_field( wp_unslash( $_GET['min_price'] ) ) : '',
				'max_price'                                => isset( $_GET['max_price'] ) ? sanitize_text_field( wp_unslash( $_GET['max_price'] ) ) : '',
				'cars_form_url'                            => $car_url,
				'load_more_vehicles_nonce'                 => wp_create_nonce( 'load_more_vehicles_nonce' ),
				'pgs_cars_list_search_auto_compalte_nonce' => wp_create_nonce( 'pgs_cars_list_search_auto_compalte_nonce' ),
				'cardealer_cars_filter_query_nonce'        => wp_create_nonce( 'cardealer_cars_filter_query_nonce' ),
			)
		);

		wp_localize_script(
			'cars_customs',
			'cars_year_range_slider_params',
			array(
				'is_year_range_active' => cardealer_is_year_range_active(),
				'min_year'             => isset( $_GET['min_year'] ) ? sanitize_text_field( wp_unslash( $_GET['min_year'] ) ) : '',
				'max_year'             => isset( $_GET['max_year'] ) ? sanitize_text_field( wp_unslash( $_GET['max_year'] ) ) : '',
			)
		);

		$lay_style        = cardealer_get_cars_list_layout_style(); // Get default layout stype for listing page if cookies not set.
		$cars_grid        = cardealer_get_cars_catlog_style(); // Get default cars_grid yes or no if cookies not set.
		$cars_orderby     = cardealer_get_default_sort_by(); // Get default short_by.
		$cars_order       = cardealer_get_default_sort_by_order();
		$vehicle_location = ( isset( $_GET['vehicle_location'] ) && ! empty( $_GET['vehicle_location'] ) ) ? sanitize_text_field( wp_unslash( $_GET['vehicle_location'] ) ) : '';

		wp_localize_script(
			'cardealer-js',
			'cardealer_obj',
			array(
				'site_url'         => site_url(),
				'cars_url'         => $car_url,
				'lay_style'        => $lay_style,
				'cars_grid'        => $cars_grid,
				'default_sort_by'  => $cars_orderby,
				'default_order_by' => $cars_order,
				'is_vehicle_cat'   => $is_vehicle_cat,
				'vehicle_cat'      => $vehicle_cat,
				'vehicle_location' => $vehicle_location, // Add vehicle location param for ajax call in next page etc.
			)
		);
		wp_register_script( 'slick-js', CARDEALER_URL . '/js/slick/slick.min.js', array( 'jquery' ), '1.6.0', true );

		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'cardealer-countdown' );
		wp_enqueue_script( 'bootsrap' );
		wp_enqueue_script( 'cardealer-js-appear' );
		wp_enqueue_script( 'magnific-popup' );
		wp_enqueue_script( 'countTo-js' );
		wp_enqueue_script( 'cardealer-external' );
		wp_enqueue_script( 'cardealer-timepicker' );
		wp_enqueue_script( 'cardealer-nice-select' );
		wp_enqueue_script( 'jquery-dotdotdot' );
		wp_enqueue_script( 'cardealer-cookie' );
		wp_enqueue_script( 'photoswipe-js' );
		wp_enqueue_script( 'photoswipe-ui-default' );
		wp_enqueue_script( 'lazyload' );

		if ( isset( $post->post_type ) && 'cars' === $post->post_type && is_single() ) {
			wp_enqueue_script( 'inputmask' );
		}

		/* enqueue if video is selected from theme options header background settings OR video widget is active */
		if ( isset( $post ) ) {
			$post_id = $post->ID;
			if ( is_archive() ) {
				$post_id = cardealer_get_current_post_id();
			}
		}

		$enable_custom_banner = isset( $post_id ) ? get_post_meta( $post_id, 'enable_custom_banner', true ) : '';
		$banner_type          = isset( $post_id ) ? get_post_meta( $post_id, 'banner_type', true ) : '';

		if ( ( $enable_custom_banner && ! empty( $banner_type ) && 'video' === $banner_type ) || ( ! empty( $car_dealer_options['banner_type'] ) && 'video' === $car_dealer_options['banner_type'] ) ) {
			if ( cardealer_check_plugin_active( 'js_composer/js_composer.php' ) ) {
					wp_register_script( 'youtube_iframe_api_js', '//www.youtube.com/iframe_api', array(), CARDEALER_VERSION );
					wp_enqueue_script( 'youtube_iframe_api_js' );
			} else {
				wp_enqueue_script( 'vc_youtube_iframe_api_js' );
			}
		}

		// Localize the script with new data.
		$translation_array = array(
			'ajaxurl'                        => admin_url( 'admin-ajax.php' ),
			'pgs_auto_complate_search_nonce' => wp_create_nonce( 'cardealer_auto_complate_search_nonce' ),
		);
		/**
		 * *************************************
		 * Code for Sticky Header Options Starts
		 * *************************************
		*/
		// Sticky Top Bar.
		if ( isset( $car_dealer_options['sticky_topbar'] ) && 'on' === $car_dealer_options['sticky_topbar'] && isset( $car_dealer_options['top_bar'] ) && true === (bool) $car_dealer_options['top_bar'] ) {
			$translation_array['sticky_topbar'] = true;
		} else {
			$translation_array['sticky_topbar'] = false;
		}
		// Script for Mobile.
		if ( isset( $car_dealer_options['sticky_header'] ) && ( true === (bool) $car_dealer_options['sticky_header'] ) && isset( $car_dealer_options['sticky_header_mobile'] ) && ( true === (bool) $car_dealer_options['sticky_header_mobile'] ) ) {
			$translation_array['sticky_header_mobile'] = true;
		} else {
			$translation_array['sticky_header_mobile'] = false;
		}
		// Script for Desktop.
		if ( isset( $car_dealer_options['sticky_header'] ) && ( true === (bool) $car_dealer_options['sticky_header'] ) ) {
			$translation_array['sticky_header_desktop'] = true;
		} else {
			$translation_array['sticky_header_desktop'] = false;
		}
		/**
		 * **************************************
		 * Code for Sticky Header Options Ends
		 * **************************************
		 */
		wp_localize_script( 'cardealer-js', 'cardealer_js', $translation_array );
		// Enqueued script with localized data.
		wp_enqueue_script( 'cardealer-js' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-slider' );
		wp_enqueue_script( 'jquery-touch-punch' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-ui-autocomplete' );
		wp_enqueue_script( 'masonry' );

		wp_enqueue_script( 'slick-js' );
		wp_enqueue_script( 'cardealer-mega_menu' );
		global $is_IE;
		if ( $is_IE ) {
			wp_enqueue_script( 'polyfill', 'https://polyfill.io/v3/polyfill.min.js?features=default%2Ces5%2Ces6%2Ces7', array( 'jquery' ), CARDEALER_VERSION, true );
		}
		wp_enqueue_script( 'cardealer-shuffle' );
		// Lazyload sticky sidebar.
		if ( isset( $car_dealer_options['vehicle-listing-layout'] ) && 'lazyload' === $car_dealer_options['vehicle-listing-layout'] ) {
			wp_enqueue_script( 'sticky-kit' );
		}
		wp_enqueue_script( 'wp-mediaelement' );
		wp_enqueue_style( 'cardealer-slick' );
		wp_enqueue_style( 'cardealer-slick-theme' );

		// Captcha js script.
		$captcha_sitekey    = cardealer_get_goole_api_keys( 'site_key' );
		$captcha_secret_key = cardealer_get_goole_api_keys( 'secret_key' );
		if ( isset( $captcha_secret_key ) && ! empty( $captcha_secret_key ) && isset( $captcha_sitekey ) && ! empty( $captcha_sitekey ) ) {
			wp_localize_script(
				'cardealer-js',
				'goole_captcha_api_obj',
				array(
					'google_captcha_site_key' => $captcha_sitekey,
				)
			);
			if ( is_single() && 'cars' === get_post_type() ) {
				wp_enqueue_script( 'cardealer-google-recaptcha-apis' );
			}
		}

		// Add custom Javascript.
		if ( isset( $car_dealer_options['custom_js'] ) && ! empty( $car_dealer_options['custom_js'] ) ) {
			$custom_js = $car_dealer_options['custom_js'];
			$custom_js = trim( $custom_js );
			if ( ! empty( $custom_js ) ) {
				wp_add_inline_script( 'cardealer-js', $custom_js );
			}
		}

		// Add Javascript for CarGuru.
		if ( isset( $car_dealer_options['enable_carguru'] ) && ( 1 === (int) $car_dealer_options['enable_carguru'] ) ) {
			$carguru_rating = $car_dealer_options['carguru_minimum_rating'];
			$carguru_height = $car_dealer_options['carguru_badge_height'];
			wp_localize_script(
				'cardealer-js',
				'cardealer_carguru',
				array(
					'carguru_rating' => $carguru_rating,
					'carguru_height' => $carguru_height,
				)
			);
		}

		/**
		 * Preloader Theme Options
		 * Add custom Css for Preloader Theme Options.
		 */
		if ( isset( $car_dealer_options['preloader'] ) && 1 === $car_dealer_options['preloader'] ) {
			if ( isset( $car_dealer_options['preloader_css'] ) && 'code' === $car_dealer_options['preloader_img'] ) {
				$preloader_css = trim( wp_strip_all_tags( $car_dealer_options['preloader_css'] ) );
				if ( ! empty( $preloader_css ) ) {
					wp_add_inline_style( 'cardealer-main', $preloader_css );
				}
			}
		}

		// Add custom Javascript for Preloader Theme Options.
		if ( isset( $car_dealer_options['preloader'] ) && 1 === $car_dealer_options['preloader'] ) {
			if ( isset( $car_dealer_options['preloader_js'] ) && ! empty( $car_dealer_options['preloader_js'] ) && 'code' === $car_dealer_options['preloader_img'] ) {
				$preloader_js = $car_dealer_options['preloader_js'];
				$preloader_js = trim( wp_strip_all_tags( $preloader_js ) );
				if ( ! empty( $preloader_js ) ) {
					$loader = array(
						'loader_theme_set' => true,
					);
					wp_localize_script( 'cardealer-js', 'cardealer_options_js', $loader );
					wp_enqueue_script( 'cardealer-js' );
					wp_add_inline_script( 'cardealer-js', $preloader_js );
				}
			}
		}
		if ( is_single() && 'cars' === get_post_type() ) {
			$zoom = 10;
			if ( isset( $car_dealer_options['default_value_zoom'] ) && ! empty( $car_dealer_options['default_value_zoom'] ) ) {
				$zoom = $car_dealer_options['default_value_zoom'];
			}
			wp_localize_script(
				'cardealer-google-maps-script',
				'cardealer_map_obj',
				array(
					'zoom' => $zoom,
				)
			);
			wp_enqueue_script( 'cardealer-google-maps-apis' );
			wp_enqueue_script( 'cardealer-google-maps-script' );
			wp_enqueue_script( 'cardealer-print-script' );
		}
		wp_enqueue_script( 'cars_customs' );
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		if ( class_exists( 'WooCommerce' ) && is_product() ) {
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			if ( ! wp_script_is( 'select2', 'enqueued' ) ) {
				wp_enqueue_script( 'select2', WC()->plugin_url() . '/assets/js/select2/select2.full' . $suffix . '.js', array( 'jquery' ), '4.0.0' );
			}
			if ( ! wp_style_is( 'select2', 'enqueued' ) ) {
				wp_enqueue_style( 'select2', WC()->plugin_url() . '/assets/css/select2.css', array(), '4.0.0' );
			}
		}
	}
}
add_action( 'admin_enqueue_scripts', 'cardealer_admin_enqueue_scripts' );
if ( ! function_exists( 'cardealer_admin_enqueue_scripts' ) ) {
	/**
	 * Add script and style in wp-admin side
	 *
	 * @param array $hook hook variable.
	 */
	function cardealer_admin_enqueue_scripts( $hook ) {
		$screen = get_current_screen();

		// Javascript.
		wp_register_script( 'cardealer-cookie', CARDEALER_URL . '/js/cookie/cookies.min.js', array(), CARDEALER_VERSION, true );
		wp_register_script( 'select2', CARDEALER_URL . '/js/select2/select2.full.min.js', array( 'jquery' ), '4.0.13', true );

		$cardealer_admin_js_deps = array( 'jquery', 'cardealer-cookie' );
		if ( 'car-dealer_page_cardealer-third-party-testing' === $screen->id ) {
			$cardealer_admin_js_deps[] = 'select2';
		}

		wp_register_script( 'cardealer_admin_js', CARDEALER_URL . '/js/admin.js', $cardealer_admin_js_deps, CARDEALER_VERSION );

		wp_localize_script(
			'cardealer_admin_js',
			'cardealer_admin_js',
			array(
				'ajaxurl'                          => admin_url( 'admin-ajax.php' ),
				'cardealer_debug_nonce'            => wp_create_nonce( 'cardealer_debug_nonce' ),
				'pgs_mail_debug_nonce'             => wp_create_nonce( 'pgs_mail_debug_nonce' ),
				'pgs_vinquery_debug_nonce'         => wp_create_nonce( 'pgs_vinquery_debug_nonce' ),
				'pgs_mailchimp_debug_nonce'        => wp_create_nonce( 'pgs_mailchimp_debug_nonce' ),
				'pgs_google_analytics_debug_nonce' => wp_create_nonce( 'pgs_google_analytics_debug_nonce' ),
				'search_vehicle'                   => esc_html__( 'Search Vehicle' ),
			)
		);
		wp_enqueue_script( 'cardealer_admin_js' );

		// CSS.
		wp_register_style( 'select2', CARDEALER_URL . '/css/select2/select2.min.css', array(), '4.0.13' );
		wp_register_style( 'cardealer-css-jqueryui', CARDEALER_URL . '/css/jquery-ui/jquery-ui.min.css', array(), '1.11.4' );
		wp_register_style( 'font-awesome-shims', CARDEALER_URL . '/fonts/font-awesome/css/v4-shims.min.css', array(), '5.12.0' );
		wp_register_style( 'font-awesome', CARDEALER_URL . '/fonts/font-awesome/css/all.min.css', array(), '5.12.0' );
		wp_register_style( 'cardealer-flaticon', CARDEALER_URL . '/css/flaticon.css', array(), CARDEALER_VERSION );
		wp_register_style( 'cardealer-admin-style', CARDEALER_URL . '/css/admin_style.css', array(), CARDEALER_VERSION );
		wp_enqueue_style( 'select2' );
		wp_enqueue_style( 'cardealer-css-jqueryui' );
		wp_enqueue_style( 'font-awesome-shims' );
		wp_enqueue_style( 'font-awesome' );
		wp_enqueue_style( 'cardealer-flaticon' );
		wp_enqueue_style( 'cardealer-admin-style' );
	}
}
