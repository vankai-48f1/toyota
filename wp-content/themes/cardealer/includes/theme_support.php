<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Add theme support and functions
 *
 * Function called in cd_init_theme() in base-functions.php.
 *
 * @package Cardealer
 */

if ( ! function_exists( 'cardealer_theme_support' ) ) {
	/**
	 * Theme Support Function
	 */
	function cardealer_theme_support() {
		// Make theme available for translation.
		load_theme_textdomain( 'cardealer', CARDEALER_PATH . '/languages' );
		// Support for thumbnails.
		add_theme_support( 'post-thumbnails' );
		// Support for RSS.
		add_theme_support( 'automatic-feed-links' );
		// HTML5.
		add_theme_support(
			'html5',
			array(
				'comment-list',
				'comment-form',
				'search-form',
				'gallery',
				'caption',
				'script',
				'style',
			)
		);
		// Title Tag.
		add_theme_support( 'title-tag' );
		// Support for post formats.
		add_theme_support(
			'post-formats',
			array(
				'aside',  // title less blurb.
				'gallery', // gallery of images.
				'link',   // quick link to other site.
				'image',  // an image.
				'quote',  // a quick quote.
				'status', // a Facebook like status update.
				'video',  // video.
				'audio',  // audio.
				'chat',   // chat transcript.
			)
		);
		// Register WP3+ menus.
		register_nav_menus(
			array(
				'primary-menu' => esc_html__( 'Primary menu', 'cardealer' ), // Primary nav in header.
				'footer-menu'  => esc_html__( 'Footer menu', 'cardealer' ), // Nav in footer.
				'topbar-menu'  => esc_html__( 'Topbar menu', 'cardealer' ), // TopBar Menu.
			)
		);
		add_theme_support( 'widgets' );
		/**
		 * Add Woocommerce theme support
		 */
		add_theme_support( 'woocommerce' );
		// Add styles for use in visual editor.
		add_editor_style( 'css/editor-styles.css' );

		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

	}
}

if ( ! function_exists( 'cardealer_add_image_sizes' ) ) {
	/**
	 * Add additional image sizes
	 *
	 * Function called in cd_init_theme().
	 *
	 * @since Car Dealer 1.0
	 */
	function cardealer_add_image_sizes() {
		/*  add image sizes */
		add_image_size( 'cardealer-blog-thumb', 1170, 500, true );
		add_image_size( 'cardealer-homepage-thumb', 700, 700, true );
		add_image_size( 'cardealer-team-thumb', 430, 450, true );
		add_image_size( 'cardealer-testimonials-thumb', 450, 189, true );
		add_image_size( 'cardealer-50x50', 50, 50, true );
		add_image_size( 'cardealer-post_nav', 124, 74, true );
		add_image_size( 'car_thumbnail', 190, 138, true );
		add_image_size( 'car_catalog_image', 265, 190, true );
		add_image_size( 'car_single_slider', 876, 535, true );
		add_image_size( 'car_tabs_image', 430, 321, true );                 // (cropped) used for multitabs etc.
	}
}
