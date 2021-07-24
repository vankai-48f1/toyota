<?php
/**
 * ACF initialization
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

if ( ! function_exists( 'cdhl_acf_fields_loader_new' ) ) {
	/**
	 * Include Add-ons.
	 */
	function cdhl_acf_fields_loader_new() {
		$acf_fields_path = trailingslashit( CDHL_PATH ) . '/includes/acf/fields/';
		if ( is_dir( $acf_fields_path ) ) {
			require_once $acf_fields_path . 'car-data.php';                 // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $acf_fields_path . 'car-tabs.php';                 // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $acf_fields_path . 'car-condition.php';            // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $acf_fields_path . 'vehicle-logo.php';             // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $acf_fields_path . 'faq-page.php';                 // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $acf_fields_path . 'financial-form.php';           // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $acf_fields_path . 'google-analytics.php';         // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $acf_fields_path . 'make-an-offer.php';            // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $acf_fields_path . 'page-settings.php';            // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $acf_fields_path . 'page-sidebar.php';             // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $acf_fields_path . 'pdf-generator.php';            // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $acf_fields_path . 'post-format-audio.php';        // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $acf_fields_path . 'post-format-gallery.php';      // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $acf_fields_path . 'post-format-quote.php';        // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $acf_fields_path . 'post-format-video.php';        // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $acf_fields_path . 'promo-code.php';               // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $acf_fields_path . 'request-more-info.php';        // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $acf_fields_path . 'schedule-test-drive.php';      // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $acf_fields_path . 'team-details.php';             // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $acf_fields_path . 'team-layout-settings.php';     // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $acf_fields_path . 'testimonials.php';             // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $acf_fields_path . 'usermeta-social-profiles.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once $acf_fields_path . 'vehicle-review-stamps.php';    // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}
	}
}

if ( ! function_exists( 'cdhl_acf_fields_additional_taxonomies' ) ) {
	/**
	 * Include Add-ons.
	 */
	function cdhl_acf_fields_additional_taxonomies() {
		$acf_fields_path = trailingslashit( CDHL_PATH ) . '/includes/acf/fields/';
		require_once $acf_fields_path . 'car-additional-taxonomies.php';    // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}
}

if ( ! defined( 'ACF_DEV' ) || ( defined( 'ACF_DEV' ) && ! ACF_DEV ) ) {

	// 4. Hide ACF field group menu item
	add_filter( 'acf/settings/show_admin', '__return_true' );
	add_action( 'init', 'cdhl_acf_fields_loader_new', 20 );
	add_action( 'init', 'cdhl_acf_fields_additional_taxonomies' );

}

add_filter( 'acf/load_field/type=radio', 'cdhl_acf_load_field_page_layout' );
if ( ! function_exists( 'cdhl_acf_load_field_page_layout' ) ) {
	/**
	 * Load field layout
	 *
	 * @param string $field .
	 */
	function cdhl_acf_load_field_page_layout( $field ) {
		// Return field without save image data in database.
		$field_post = get_post( $field['ID'] );
		if ( isset( $field_post->post_type ) && 'acf-field' === (string) $field_post->post_type ) {
			return $field;
		}
		$name = $field['name'];
		// Populate field with class.
		$class   = $field['wrapper']['class'];
		$classes = explode( ' ', $class );
		if ( is_array( $classes ) && ! in_array( 'acf-image-radio', $classes, true ) ) {
			return $field;
		}
		$acf_radio_imgs    = trailingslashit( CDHL_URL ) . 'images/radio-button-imgs';
		$cdhl_banners_path = trailingslashit( CDHL_PATH ) . 'images/radio-button-imgs/' . $name . '/';
		$cdhl_banners_url  = trailingslashit( CDHL_URL ) . 'images/radio-button-imgs/' . $name . '/';
		$cdhl_banners_new  = array();
		if ( is_dir( $cdhl_banners_path ) ) {
			$cdhl_banners_data = cdhl_pgscore_get_file_list( 'jpg,png', $cdhl_banners_path );
			if ( ! empty( $cdhl_banners_data ) ) {
				foreach ( $cdhl_banners_data as $cdhl_banner_path ) {
					$file_data                                  = pathinfo( $cdhl_banner_path );
					$opt_title                                  = $file_data['filename'];
					$opt_title                                  = ucfirst( str_replace( '_', ' ', $opt_title ) );
					$field['choices'][ $file_data['filename'] ] = '<img src="' . esc_url( $cdhl_banners_url . basename( $cdhl_banner_path ) ) . '" alt="' . esc_attr( $opt_title ) . '" /><span class="radio_btn_title">' . $opt_title . '</span>';
				}
			}
		}
		return $field;
	}
}

add_filter( 'acf/load_field', 'cdhl_acf_load_field_add_field_name_class' );
if ( ! function_exists( 'cdhl_acf_load_field_add_field_name_class' ) ) {
	/**
	 * Acf load field add field class name
	 *
	 * @param string $field .
	 */
	function cdhl_acf_load_field_add_field_name_class( $field ) {
		// Return field if it's field editor.
		$field_post = get_post( $field['ID'] );
		if (
			isset( $field_post->post_type ) &&  $field_post->post_type == 'acf-field'
			|| ( ( isset( $_GET['page'] ) && 'acf-tools' === $_GET['page'] ) && ( isset( $_GET['tool'] ) && 'export' === $_GET['tool'] ) )
		) {
			return $field;
		}

		$name      = $field['_name'];
		$acf_class = 'acf_field_name-' . $name;

		if ( empty( $field['wrapper']['class'] ) ) {
			$field['wrapper']['class'] = $acf_class;
		} else {
			$classes = explode( ' ', $field['wrapper']['class'] );
			$classes = array_filter( array_unique( $classes ) );
			if ( ! in_array( $acf_class, $classes ) ){
				$classes[] = $acf_class;
			}
			$classes = implode( ' ', $classes );

			$field['wrapper']['class'] = $classes;
		}
		return $field;
	}
}

add_filter( 'acf/load_field/name=banner_image_bg', 'cdhl_acf_load_field_banner_image_bg' );
if ( ! function_exists( 'cdhl_acf_load_field_banner_image_bg' ) ) {
	/**
	 * Load banner image background
	 *
	 * @param string $field .
	 */
	function cdhl_acf_load_field_banner_image_bg( $field ) {
		// Return field without save image data in database.
		$field_post = get_post( $field['ID'] );
		if ( 'acf-field' === (string) $field_post->post_type ) {
			return $field;
		}
		if ( empty( $field['wrapper']['class'] ) ) {
			$field['wrapper']['class'] = 'acf_field_name-banner_image_bg';
		}
		$banner_images = cdhl_banner_images();
		foreach ( $banner_images as $banner_image ) {
			$field['choices'][ $banner_image['img'] ] = '<img src="' . esc_url( $banner_image['img'] ) . '" alt="' . esc_attr( $banner_image['alt'] ) . '" height="75" />';
		}
		return $field;
	}
}

if ( ! function_exists( 'cdhl_get_promocode_options' ) ) {
	/**
	 * Get promocode options
	 */
	function cdhl_get_promocode_options() {
		$list        = array();
		$my_args     = array(
			'post_type'      => 'page',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'meta_query'     => array(
				array(
					'key'   => '_wp_page_template',
					'value' => 'templates/promocode.php',
				),
			),
		);
		$promo_pages = get_posts( $my_args );
		if ( $promo_pages ) {
			foreach ( $promo_pages as $promopage ) :
				setup_postdata( $promopage );
				$list[ get_the_permalink( $promopage->ID ) ] = $promopage->post_title;
			endforeach;
		}
		wp_reset_postdata();
		$list['custom'] = 'Custom';
		return $list;
	}
}

add_filter( 'acf/load_field/name=promo_code_page', 'acf_load_promocode_field_choices' );
if ( ! function_exists( 'acf_load_promocode_field_choices' ) ) {
	/**
	 * Load promocode field choices
	 *
	 * @param string $field .
	 */
	function acf_load_promocode_field_choices( $field ) {

		$field['choices'] = array();
		$field_post       = get_post( $field['ID'] );
		if ( is_object( $field_post ) && 'acf-field' === (string) $field_post->post_type ) {
			return $field;
		}
		$field['choices'] = cdhl_get_promocode_options();
		return $field;
	}
}
