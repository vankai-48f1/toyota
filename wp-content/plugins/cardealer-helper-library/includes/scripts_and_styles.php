<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Include the scripts and styles.
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper/functions
 * @version 1.0.0
 */

add_action( 'wp_enqueue_scripts', 'cdhl_load_style_script' );
if ( ! function_exists( 'cdhl_load_style_script' ) ) {
	/**
	 * Add script and style in wp-admin side
	 */
	function cdhl_load_style_script() {
		$cars_geo_fencing = cdhl_get_geo_count();
		$carsfilterwith   = 'ajax';
		if ( function_exists( 'cardealer_cars_filter_methods' ) ) {
			$carsfilterwith = cardealer_cars_filter_methods();
		}
		wp_register_script( 'cardealer-helper-js', trailingslashit( CDHL_URL ) . 'js/cardealer-helper.js', array( 'jquery', 'cardealer-cookie' ), CDHL_VERSION, true );
		wp_localize_script(
			'cardealer-helper-js',
			'cars_filter_methods',
			array(
				'cars_filter_with' => $carsfilterwith,
				'geofenc'          => $cars_geo_fencing,
			)
		);
		wp_localize_script(
			'cardealer-helper-js',
			'cdhl_obj',
			array(
				'geofenc' => $cars_geo_fencing,
			)
		);
		wp_enqueue_script( 'cardealer-helper-js' );
	}
}

add_action( 'admin_enqueue_scripts', 'cdhl_admin_enqueue_scripts' );
if ( ! function_exists( 'cdhl_admin_enqueue_scripts' ) ) {
	/**
	 * Add script and style in wp-admin side
	 *
	 * @param string $hook variable.
	 */
	function cdhl_admin_enqueue_scripts( $hook ) {
		global $car_dealer_options;

		$google_key = ( function_exists( 'cardealer_get_google_api_key' ) ) ? cardealer_get_google_api_key() : '';

		// Javascript.
		wp_register_script( 'chosen', trailingslashit( CDHL_URL ) . 'js/chosen/chosen.jquery.min.js', array( 'jquery-ui-widget' ), '1.7.0', true );
		wp_register_script( 'chosen-order', trailingslashit( CDHL_URL ) . 'js/chosen/chosen.order.jquery.min.js', array( 'jquery-ui-widget' ), '1.2.1', true );
		wp_register_script( 'jquery-confirm', trailingslashit( CDHL_URL ) . 'js/jquery-confirm/jquery-confirm.min.js', array( 'jquery' ), '3.2.0', true );
		wp_register_script( 'cdhl-jquery-cars', trailingslashit( CDHL_URL ) . 'js/cars.js', array(), CDHL_VERSION, true );
		wp_register_script( 'cdhl-jquery-helper-admin', trailingslashit( CDHL_URL ) . 'js/cardealer-helper-admin.js', array( 'jquery-ui-autocomplete' ), CDHL_VERSION, true );
		wp_register_script( 'cdhl-jquery-import', trailingslashit( CDHL_URL ) . 'js/cardealer_import.js', array(), CDHL_VERSION, true );

		wp_register_script( 'cdhl-google-maps-apis', 'https://maps.googleapis.com/maps/api/js?key=' . $google_key . '&libraries=drawing,places&callback=geoFenc', array(), CDHL_VERSION, true );
		wp_register_script( 'cdhl-geofance', trailingslashit( CDHL_URL ) . 'js/geofance.js', array(), CDHL_VERSION, true );

		$ajaxurl = array(
			'ajaxurl'  => admin_url( 'admin-ajax.php' ),
			'cdhl_url' => CDHL_URL,
		);

		if ( class_exists( 'ReduxFramework' ) ) {
			if ( isset( $_GET['cd_section'] ) && 'sample_data' === (string) $_GET['cd_section'] ) {
				// Code for redux framework to set sample data tab active.
				$redux_sample_data_tab = "
				var tabID = jQuery('.redux-group-menu li.cd_sample_data').attr('id');
				var cd_tabID = tabID.slice(0, tabID.indexOf('_'));
				jQuery.cookie(
					'redux_current_tab', cd_tabID, {
						expires: 7,
						path: '/'
					}
				);";

				wp_add_inline_script( 'redux-js', $redux_sample_data_tab );
			}

			// Localize script for redux options search.
			wp_localize_script( 'cdhl-jquery-helper-admin', 'cardealer_search_config', cdhl_redux_search_options() );
		}

		wp_localize_script( 'cdhl-jquery-helper-admin', 'cdhl', $ajaxurl );

		// Add message for pdf brochare.
		// $pdf_message = '<div class="notice notice-success" id="pdf-notice"><p>' . esc_html__( 'Generated PDF is assigned to <b>PDF Brochure</b>, you can also change this PDF from <b>PDF Brochure</b> setting by editing car.', 'cardealer-helper' ) . '</p></div>';
		wp_localize_script(
			'cdhl-jquery-cars',
			'cars_pdf_message',
			array(
				'pdf_generated_message' => wp_kses(
					sprintf(
						'<div id="generate-pdf-notice" class="notice notice-success"><p>%1$s</p></div>',
						__( 'PDF generated successfully. Generated PDF is assigned to the <strong>PDF Brochure</strong> field.', 'cardealer-helper' )
					),
					array(
						'div'    => array(
							'id'    => true,
							'class' => true,
						),
						'p'      => array(),
						'strong' => array(),
					)
				),
				'download_pdf_str'      => esc_html__( 'Download PDF', 'cardealer-helper' ),
			)
		);

		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-widget' );
		wp_enqueue_script( 'jquery-ui-droppable' );
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-ui-tooltip' );
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script( 'chosen' );
		wp_enqueue_script( 'chosen-order' );
		wp_enqueue_script( 'jquery-confirm' );
		wp_enqueue_script( 'cdhl-jquery-helper-admin' );

		if ( 'cars' === get_post_type() || ( isset( $_GET['page'] ) && ( 'log-list' === (string) $_GET['page'] || 'import-log' === (string) $_GET['page'] || 'car-export-list' === (string) $_GET['page'] ) ) ) {
			wp_enqueue_script( 'cdhl-jquery-cars' );
		}

		if ( 'cars' === get_post_type() || ( isset( $_GET['page'] ) && 'cars-import' === (string) $_GET['page'] ) ) {
			wp_enqueue_script( 'cdhl-jquery-import' );
		}

		// CSS.
		wp_enqueue_style( 'cdhl-css-helper-admin', trailingslashit( CDHL_URL ) . 'css/cardealer-helper-admin.css', array(), CDHL_VERSION );
		wp_enqueue_style( 'jquery-ui', trailingslashit( CDHL_URL ) . 'css/jquery-ui/jquery-ui.min.css', array(), '1.11.4' );
		wp_enqueue_style( 'chosen', trailingslashit( CDHL_URL ) . 'css/chosen/chosen.min.css', array(), '1.7.0' );
		wp_enqueue_style( 'jquery-confirm-bootstrap', trailingslashit( CDHL_URL ) . 'css/jquery-confirm/jquery-confirm-bootstrap.css', array(), '3.3.7' );
		wp_enqueue_style( 'jquery-confirm', trailingslashit( CDHL_URL ) . 'css/jquery-confirm/jquery-confirm.css', array(), '3.2.0' );
		wp_enqueue_style( 'cdhl-css-redux_admin', trailingslashit( CDHL_URL ) . 'css/cardealer_redux.css', array(), CDHL_VERSION );
	}
}
