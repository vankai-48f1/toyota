<?php
/**
 * CarDealer Redux Freamwork init.
 *
 * @package car-dealer-helper/functions
 */

if ( ! class_exists( 'Redux' ) ) {
	return;
}


if ( ! function_exists( 'cdhl_remove_redux_demo' ) ) {
	/**
	 * Removes the demo link and the notice of integrated demo from the redux-framework plugin
	 */
	function cdhl_remove_redux_demo() {
		// Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
		if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
			remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::instance(), 'plugin_metalinks' ), null, 2 );

			// Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
			remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
		}
	}
}
add_action( 'redux/loaded', 'cdhl_remove_redux_demo' );

require_once trailingslashit( CDHL_PATH ) . 'includes/redux/redux-options.php';           // Redux Core & Options
require_once trailingslashit( CDHL_PATH ) . 'includes/redux/extensions.php';// Load Redux modified fields

add_action( 'admin_bar_menu', 'cdhl_toolbar_theme_options_link', 999 );

/**
 * Set theme options link in toolbar.
 *
 * @return void
 */
function cdhl_toolbar_theme_options_link( $wp_admin_bar ) {
	$args = array(
		'id'    => 'cardealer',
		'title' => '<span class="cd_toolbar_btn"><img alt="cd_toolbar" src="' . esc_url( CDHL_URL ) . '/images/menu-icon.png"></span><a href="' . esc_url_raw( admin_url( "admin.php?page=cardealer" ) ) . '">Theme Options</a>',
		'meta'  => array(
			'class' => 'wp-admin-bar-cardealer-link',
		),
	);
	$wp_admin_bar->add_node( $args );
}

add_action( 'admin_menu', 'cdhl_remove_redux_menu', 12 );

/**
 * Remove redux menu under the tools.
 */
function cdhl_remove_redux_menu() {
	remove_submenu_page( 'tools.php', 'redux-about' );
}

// Hide advertisement in Redux Options.
add_filter( 'redux/' . 'car_dealer_options' . '/aURL_filter', '__return_true' );


if ( ! function_exists( 'cdhl_redux_search_options' ) ) {

	/**
	 * Redux Search Option.
	 */
	function cdhl_redux_search_options() {
		if ( class_exists( 'Redux' ) ) {
			global $opt_name;

			$redux_sections = Redux::getSections( $opt_name );

			$options_object = 0;
			$import_export  = 0;
			$section_id     = 0;
			$index          = 0;

			$option_fields = array();

			foreach ( $redux_sections as $key => $section ) {
				if ( isset( $section['title'] ) && ( isset( $section['type'] ) && 'section' !== (string) $section['type'] ) || isset( $section['icon'] ) ) {
					$option_fields[ $index ]['title']      = $section['title'];
					$option_fields[ $index ]['id']         = $section['id'];
					$option_fields[ $index ]['path']       = $section['title'];
					$option_fields[ $index ]['section_id'] = $section_id;

					if ( isset( $section['icon'] ) ) {
						$option_fields[ $index ]['icon'] = $section['icon'];
					}
				}
				if ( 'options-object' === (string) $key ) {
					$options_object = 1;
				}
				if ( 'import/export' === (string) $key ) {
					$import_export = 1;
				}

				if ( isset( $section['fields'] ) ) {
					foreach ( $section['fields'] as $field ) {
						if ( isset( $field['title'] ) && ( ( isset( $field['type'] ) && 'section' !== (string) $field['type'] ) ) ) {
							$index++;
							$option_fields[ $index ]['id']         = $field['id'];
							$option_fields[ $index ]['title']      = $field['title'];
							$option_fields[ $index ]['path']       = $section['title'] . ' -> ' . $field['title'];
							$option_fields[ $index ]['section_id'] = $section_id;

							if ( isset( $section['icon'] ) ) {
								$option_fields[ $index ]['icon'] = $section['icon'];
							}
						}
					}
				} else {
					$index++;
				}
				$section_id++;
			}

			if ( 0 === (int) $import_export ) {
				$option_fields[] = array(
					'title'      => esc_html( 'Import / Export', 'cardealer-helper' ),
					'id'         => 'redux_import_export',
					'path'       => esc_html( 'Import / Export', 'cardealer-helper' ),
					'section_id' => 42,
					'icon'       => 'el el-refresh',
				);
			}

			$localize_data['search_option_placeholder_text'] = esc_js( __( 'Search for Theme options', 'cardealer-helper' ) );
			$localize_data['reduxThemeOptions']              = $option_fields;

			return apply_filters( 'cardealer_admin_search_options_localize_data', $localize_data );
		}
	}
}

/**
 * Update Car Dealer Front Submission page ID.
 *
 * @param string $response .
 */
function cdhl_option_save( $response ) {

	$option_save_actions = array(
		'cdfs_myuseraccount_page_id' => array(
			'action'        => 'update_option',
			'update_option' => 'cdfs_myuseraccount_page_id',
		),
	);

	foreach ( $option_save_actions as $option_save_action_k => $option_save_action_data ) {
		if ( isset( $response['options'][ $option_save_action_k ] ) && ! empty( $response['options'][ $option_save_action_k ] ) ) {
			$option_save_action_v = $response['options'][ $option_save_action_k ];

			if ( 'update_option' === $option_save_action_data['action'] && isset( $option_save_action_data['update_option'] ) && ! empty( $option_save_action_data['update_option'] ) ) {
				update_option( $option_save_action_data['update_option'], $option_save_action_v );
			}
		}
	}

	return $response;
}
add_filter( 'redux/options/car_dealer_options/ajax_save/response', 'cdhl_option_save' );

function cdhl_sample_data_import_update_theme_options( $redux_options, $opt_name ) {
	$options = array(
		'site_email'                  => get_bloginfo( 'admin_email' ),
		'inq_mail_from_name'          => get_bloginfo( 'name' ),
		'inq_mail_id_from'            => get_bloginfo( 'admin_email' ),
		/* translators: %s site title */
		'inq_subject'                 => sprintf( esc_html__( '%s - Inquiry Received', 'cardealer-helper' ), get_bloginfo( 'name' ) ),
		'inq_adf_mail_to'             => get_bloginfo( 'admin_email' ),
		'inq_html_mail_to'            => get_bloginfo( 'admin_email' ),
		'inq_text_mail_to'            => get_bloginfo( 'admin_email' ),
		'mao_from_name'               => get_bloginfo( 'name' ),
		'mao_mail_id_from'            => get_bloginfo( 'admin_email' ),
		/* translators: %s site title */
		'mao_subject'                 => sprintf( esc_html__( '%s - Make an Offer Inquiry Received', 'cardealer-helper' ), get_bloginfo( 'name' ) ),
		'mao_adf_mail_to'             => get_bloginfo( 'admin_email' ),
		'mao_html_mail_to'            => get_bloginfo( 'admin_email' ),
		'mao_text_mail_to'            => get_bloginfo( 'admin_email' ),
		'std_mail_from_name'          => get_bloginfo( 'name' ),
		'std_mail_id_from'            => get_bloginfo( 'admin_email' ),
		/* translators: %s site title */
		'std_subject'                 => sprintf( esc_html__( '%s - Test Drive Inquiry Received', 'cardealer-helper' ), get_bloginfo( 'name' ) ),
		'std_adf_mail_to'             => get_bloginfo( 'admin_email' ),
		'std_html_mail_to'            => get_bloginfo( 'admin_email' ),
		'std_txt_mail_to'             => get_bloginfo( 'admin_email' ),
		'email_friend_from_name'      => get_bloginfo( 'name' ),
		'email_friend_from_email'     => get_bloginfo( 'admin_email' ),
		/* translators: %s site title */
		'email_friend_subject'        => sprintf( esc_html__( '%s - Mail to Friend', 'cardealer-helper' ), get_bloginfo( 'name' ) ),
		'financial_form_from_name'    => get_bloginfo( 'name' ),
		'financial_form_mail_id_from' => get_bloginfo( 'admin_email' ),
		/* translators: %s site title */
		'financial_form_subject'      => sprintf( esc_html__( '%s - Financial inquiry', 'cardealer-helper' ), get_bloginfo( 'name' ) ),
		'financial_form_adf_mail_to'  => get_bloginfo( 'admin_email' ),
		'financial_form_html_mail_to' => get_bloginfo( 'admin_email' ),
		'financial_form_text_mail_to' => get_bloginfo( 'admin_email' ),
	);

	foreach ( $options as $option_k => $option_v ) {
		$redux_options[ $option_k ] = $option_v;
	}

	return $redux_options;
}
add_filter( 'cdhl_sample_data_import_theme_options_data', 'cdhl_sample_data_import_update_theme_options', 10, 2 );
