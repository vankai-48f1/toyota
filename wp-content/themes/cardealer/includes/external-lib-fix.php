<?php
/**
 *
 * Bundle Plugins Hack
 * Prevent Visual Composer Redirection after plugin activation
 *
 * @package cardealer
 */

remove_action( 'admin_init', 'vc_page_welcome_redirect', 9999 );

/**
 * Lib link
 * http://tgmpluginactivation.com/faq/updating-bundled-visual-composer/
 * https://wpbakery.atlassian.net/wiki/pages/viewpage.action?pageId=524297
 */
add_action( 'vc_before_init', 'cardealer_vc_set_as_theme' );
if ( ! function_exists( 'cardealer_vc_set_as_theme' ) ) {
	/**
	 * VC set as theme
	 */
	function cardealer_vc_set_as_theme() {
		vc_set_as_theme();

		$vc_supported_cpts = array(
			'page',
			'post',
		);
		vc_set_default_editor_post_types( $vc_supported_cpts );
	}
}

/*
 * Remove the blog from the 404 and search breadcrumb trail
 */
if ( ! function_exists( 'bcn_display' ) ) {
	/**
	 * Display bcn
	 *
	 * @param string $trail .
	 */
	function cardealer_wpst_override_breadcrumb_trail( $trail ) {
		if ( is_404() || is_search() ) {
			unset( $trail->trail[1] );
			array_keys( $trail->trail );
		}
	}

	add_action( 'bcn_after_fill', 'cardealer_wpst_override_breadcrumb_trail' );
}

if ( ! function_exists( 'cardealer_remove_acfpro_update_' ) ) {
	/**
	 * Hide upgrade notice for bundled plugin acf pro
	 *
	 * @param string $value .
	 */
	function cardealer_remove_acfpro_update_( $value ) {
		global $pagenow;
		if ( isset( $value->response ) && 'themes.php' !== $pagenow ) {
			unset( $value->response['advanced-custom-fields-pro/acf.php'] );
		}
		return $value;
	}
	add_filter( 'site_transient_update_plugins', 'cardealer_remove_acfpro_update_' );
}

add_filter( 'pre_set_site_transient_update_plugins', 'site_transient_update_subscriptio_plugins' );
if ( ! function_exists( 'site_transient_update_subscriptio_plugins' ) ) {
	/**
	 * Subscriptio plugin upgrade transient change.
	 *
	 * @param object $transient .
	 * @return array
	 */
	function site_transient_update_subscriptio_plugins( $transient ) {
		global $pagenow;

		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		if ( 'themes.php' === $pagenow ) {
			$tgmpa_plugins     = cardealer_tgmpa_plugin_list();
			$subscriptio_index = array_search( 'subscriptio', array_column( $tgmpa_plugins, 'slug' ) ); // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
			$subscriptio_data  = $tgmpa_plugins[ $subscriptio_index ];

			if ( ! isset( $transient->response['subscriptio/subscriptio.php'] ) ) {
				$transient->response['subscriptio/subscriptio.php'] = new stdClass();
			}

			if ( $subscriptio_data['source'] ) {
				$transient->response['subscriptio/subscriptio.php']->package = $subscriptio_data['source'];
			}

			if ( $subscriptio_data['slug'] ) {
				$transient->response['subscriptio/subscriptio.php']->slug = $subscriptio_data['slug'];
			}

			if ( $subscriptio_data['version'] ) {
				$transient->response['subscriptio/subscriptio.php']->new_version = $subscriptio_data['version'];
			}
		}

		return $transient;
	}
}

add_filter( 'acf/updates/plugin_update', 'cardealer_update_acfpro_plugin', 11, 2 );
if ( ! function_exists( 'cardealer_update_acfpro_plugin' ) ) {
	/**
	 * Update acf pro plugin
	 *
	 * @param string $update .
	 * @param string $transient .
	 */
	function cardealer_update_acfpro_plugin( $update, $transient ) {

		if ( function_exists( 'acf_pro_is_license_active' ) && ! acf_pro_is_license_active() && is_object( $update ) ) {
			$update->package = CARDEALER_PATH . '/includes/plugins/advanced-custom-fields-pro.zip';
		}
		return $update;
	}
}

add_filter( 'upgrader_package_options', 'cardealer_update_acfpro_package_options' );
if ( ! function_exists( 'cardealer_update_acfpro_package_options' ) ) {
	/**
	 * Update acf package option
	 *
	 * @param string $options .
	 */
	function cardealer_update_acfpro_package_options( $options ) {
		if ( ! empty( $options ) && isset( $options['hook_extra']['plugin'] ) && 'advanced-custom-fields-pro/acf.php' === $options['hook_extra']['plugin'] ) {
			// update source from tgmpa.
			$tgmpa_plugins      = cardealer_tgmpa_plugin_list();
			$acf_pro_index      = array_search( 'advanced-custom-fields-pro', array_column( $tgmpa_plugins, 'slug' ) ); // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
			$acf_pro_data       = $tgmpa_plugins[ $acf_pro_index ];
			$options['package'] = $acf_pro_data['source'];
		}
		return $options;
	}
}

// For visual-composer.
add_filter( 'site_transient_update_plugins', 'cardealer_remove_update_notifications' );
if ( ! function_exists( 'cardealer_remove_update_notifications' ) ) {
	/**
	 * Remove update notifications
	 *
	 * @param string $value .
	 */
	function cardealer_remove_update_notifications( $value ) {
		global $pagenow;
		if ( isset( $value->response ) && 'themes.php' !== $pagenow ) {
			unset( $value->response['js_composer/js_composer.php'] );
		}
		return $value;
	}
}
/* Contact Form 7 - unload assets */
add_filter( 'wpcf7_load_js', '__return_false' );
add_filter( 'wpcf7_load_css', '__return_false' );

if ( ! function_exists( 'cardealer_cf7_load_assets' ) ) {
	/**
	 * Contact form load
	 */
	function cardealer_cf7_load_assets() {
		global $post;
		if (
			is_a( $post, 'WP_Post' )
			&& (
				( has_shortcode( $post->post_content, 'contact-form-7' ) || has_shortcode( $post->post_content, 'contact-form' ) )
				|| ( 'cars' === $post->post_type )
			)
		) {
			if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
				wpcf7_enqueue_scripts();
			}
			if ( function_exists( 'wpcf7_enqueue_styles' ) ) {
				wpcf7_enqueue_styles();
			}
		}
	}
	add_action( 'wp_enqueue_scripts', 'cardealer_cf7_load_assets' );
}
