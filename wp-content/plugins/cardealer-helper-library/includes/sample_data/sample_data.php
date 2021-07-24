<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Sample Data Functions.
 *
 * @package car-dealer-helper
 */

if ( file_exists( get_parent_theme_file_path( '/includes/admin/class-cardealer-theme-activation.php' ) ) ) {
	require_once get_parent_theme_file_path( '/includes/admin/class-cardealer-theme-activation.php' );// Car Dealer theme verification.
}

if ( ! function_exists( 'cdhl_sample_data_section' ) ) {
	/**
	 * Dynamically add import section.
	 */
	function cdhl_sample_data_section() {
		global $opt_name;
		$auth_token = false;
		if ( class_exists( 'Cardealer_Theme_Activation' ) ) {
			$auth_token = Cardealer_Theme_Activation::cardealer_verify_theme(); // Theme Token.
		}
		if ( ! $auth_token || empty( $auth_token ) ) {
			$sample_data_section = array(
				'title'  => esc_html__( 'Sample Data sample', 'cardealer-helper' ),
				'desc'   => wp_kses(
					__( '<p class="cdhl-admin-important-notice"><strong>ALERT: </strong>In order to install theme sample data, Please verify your theme purchase by submitting purchase code <a href="' . esc_url( admin_url( 'admin.php?page=cardealer-panel' ) ) . '">here</a>.</p>', 'cardealer-helper' ),
					array(
						'a'      => array(
							'href' => array(),
						),
						'p'      => array(
							'class' => array(),
						),
						'strong' => array(),
					)
				),
				'id'     => 'sample_data_2',
				'class'  => 'cd_sample_data',
				'icon'   => 'fas fa-database',
				'fields' => array(),
			);
		} else {
			$sample_data_desc           = '';
			$sample_data_desc_mandetory = '<p><span style="color:#FF0000;">' . wp_kses(
				esc_html__( 'Please take a backup before importing any sample data to prevent any data loss during installation.', 'cardealer-helper' ),
				array(
					'br'     => array(),
					'strong' => array(
						'style' => array(),
					),
				)
			) . '</span></p>';

			$sample_data_section = array(
				'title'  => esc_html__( 'Sample Data', 'cardealer-helper' ),
				'desc'   => wp_kses(
					__( 'You can import pre-defined sample data, as show on our demo site, from here.', 'cardealer-helper' ),
					array(
						'br'     => array(),
						'strong' => array(
							'style' => array(),
						),
					)
				)
				. '<br><br>' . wp_kses(
					__( '"<strong style="color:#000">Default</strong>" data contains all basic sample contents with "<strong style="color:#000">Home Page 1</strong>" and individual <strong style="color:#000">Home Pages</strong> contains <strong style="color:#000">only home page</strong> content. <strong style="color:#000">Before importing individual home pages, first import "Default" data.</strong>', 'cardealer-helper' ),
					array(
						'br'     => array(),
						'strong' => array(
							'style' => array(),
						),
						'span'   => array(
							'style' => array(),
						),
					)
				)
				. '<br><br>' . wp_kses(
					__( '<strong style="color:#000">Note</strong>: All pre-defined sample pages are configured using existing data. So, some of the shortcodes will not work if relative data is missing. For example, if testimonials data is not imported/added, testimonials shortcode will not work. So, please ensure all required data is imported/added successfully.', 'cardealer-helper' ),
					array(
						'br'     => array(),
						'strong' => array(
							'style' => array(),
						),
					)
				)
				. '<br><br><span style="color:#FF0000;">' . wp_kses(
					esc_html__( 'Please take backup before importing any sample data to prevent any data-loss during installation.', 'cardealer-helper' ),
					array(
						'br'     => array(),
						'strong' => array(
							'style' => array(),
						),
					)
				) . '</span><br><br><span style="color:green;">' . wp_kses(
					esc_html__( 'NOTE: You can install either Service demo OR Default demo because both belong to different categories.', 'cardealer-helper' ),
					array(
						'br'     => array(),
						'strong' => array(
							'style' => array(),
						),
					)
				) . '</span>',
				'id'     => 'sample_data',
				'class'  => 'cd_sample_data',
				'icon'   => 'fas fa-database',
				'fields' => array(
					array(
						'id'         => 'cd_sample_data_import',
						'type'       => 'cd_sample_import',
						'full_width' => true,
					),
				),
			);
		}
		return $sample_data_section;
	}
}

if ( ! function_exists( 'cdhl_theme_sample_import_field_completed' ) ) {
	/**
	 * Sucess message after import.
	 *
	 * @return void
	 */
	function cdhl_theme_sample_import_field_completed() {
		flush_rewrite_rules( true );
		echo '<div class="admin-demo-data-notice notice-green" style="display:none;"><strong>' . esc_html__( 'Successfully installed demo data.', 'cardealer-helper' ) . '</strong></div>';
		echo '<div class="admin-demo-data-reload notice-red" style="display: none;"><strong>' . esc_html__( 'Please wait... reloading page to load changes.', 'cardealer-helper' ) . '</strong></div>';
	}
}
add_action( 'redux/options/car_dealer_options/settings/change', 'cdhl_theme_sample_import_field_completed' );

if ( ! function_exists( 'cdhl_update_vehicle_final_price' ) ) {

	add_action( 'import_post_meta', 'cdhl_update_vehicle_final_price', 10, 3 );

	/**
	 * Save vehicle final price once post created for vehicle
	 *
	 * @param int    $post_id post id.
	 * @param int    $key key.
	 * @param string $value value.
	 * @return void
	 */
	function cdhl_update_vehicle_final_price( $post_id, $key, $value ) {
		$postype = get_post_type( $post_id );
		if ( ! is_wp_error( $postype ) && 'cars' === (string) $postype && function_exists( 'update_field' ) ) {
			$final_price = 0;
			$sale_price  = get_post_meta( $post_id, 'sale_price', true );
			if ( $sale_price ) {
				$final_price = $sale_price;
			} else {
				$regular_price = get_post_meta( $post_id, 'regular_price', true );
				if ( $regular_price ) {
					$final_price = $regular_price;
				}
			}
			update_field( 'final_price', $final_price, $post_id );
		}
	}
}

if ( ! function_exists( 'cdhl_set_permalink_after_import' ) ) {

	add_action( 'import_end', 'cdhl_set_permalink_after_import' );

	/**
	 * Set permalink once import done
	 *
	 * @return void
	 */
	function cdhl_set_permalink_after_import() {
		flush_rewrite_rules( true );
	}
}

if ( ! function_exists( 'cdhl_theme_sample_datas' ) ) {
	/**
	 * Prepapre Sample Data folder details
	 */
	function cdhl_theme_sample_datas() {
		return apply_filters( 'cdhl_theme_sample_datas', array() );
	}
}

add_action( 'wp_ajax_theme_import_sample', 'cdhl_theme_import_sample' );

if ( ! function_exists( 'cdhl_theme_import_sample' ) ) {
	/**
	 * Import sample data.
	 *
	 * @return void
	 */
	function cdhl_theme_import_sample() {
		sleep( 0 );

		// First check the nonce, if it fails the function will break.
		if ( ! isset( $_REQUEST['sample_import_nonce'] ) || ! wp_verify_nonce( $_REQUEST['sample_import_nonce'], 'sample_import_security_check' ) ) {
			$import_status_data = array(
				'success' => false,
				'message' => esc_html__( 'Unable to validate security check. Please reload the page and try again.', 'cardealer-helper' ),
				'action'  => '',
			);
		}

		// Nonce is checked, get the posted data and process further.
		$sample_id = isset( $_REQUEST['sample_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['sample_id'] ) ) : '';

		if ( empty( $sample_id ) ) {
			$import_status_data = array(
				'success' => false,
				'message' => esc_html__( 'Something went wrong or invalid sample selected.', 'cardealer-helper' ),
			);
		} else {
			global $wpdb;
			if ( ! current_user_can( 'manage_options' ) ) {
					$import_status_data = array(
						'success' => false,
						'message' => esc_html__( 'You are not allowed to perform this action.', 'cardealer-helper' ),
					);
			} else {

				if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
					define( 'WP_LOAD_IMPORTERS', true ); // we are loading importers.
				}

				if ( ! class_exists( 'WP_Importer' ) ) { // if main importer class doesn't exist.
					$wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
					include $wp_importer;
				}

				$importer_path = trailingslashit( CDHL_PATH ) . 'includes/importer/importer.php';
				if ( file_exists( $importer_path ) ) {
					require_once $importer_path;
				}

				// check for main import class and wp import class.
				if ( ! class_exists( 'WP_Importer' ) || ! class_exists( 'Cardealer_Helper_WP_Import' ) ) {
					$import_status_data = array(
						'success' => false,
						'message' => esc_html__( 'WordPress importer class not found.', 'cardealer-helper' ),
					);
				} else {
					$cardealer_helper_sample_datas = cdhl_theme_sample_datas();
					$sample_data                   = $cardealer_helper_sample_datas[ $sample_id ];

					// Import Data.
					$importer = new Cardealer_Helper_WP_Import();

					// Import Posts, Pages, Portfolio Content, FAQ, Images, Menus.
					$importer->fetch_attachments = true;

					require_once ABSPATH . 'wp-admin/includes/file.php';

					$sample_data_url = cdhl_sample_data_url( $sample_id, 'sample_data.xml' );
					$download_sample = download_url( $sample_data_url );

					if ( is_wp_error( $download_sample ) ) {
						$import_status_data = array(
							'success' => false,
							'message' => esc_html__( 'Unable to download sample file.', 'cardealer-helper' ) . ' Error: ' . $download_sample->get_error_message(),
						);
						if ( defined( 'PGS_DEV_DEBUG' ) && PGS_DEV_DEBUG ) {
							$import_status_data['message'] = $import_status_data['message'] . "\r\n" . $sample_data_url;
						}
					} else {
						if ( ! file_exists( $download_sample ) ) {
							$import_status_data = array(
								'success' => false,
								'message' => esc_html__( "Sample file doesn't exist.", 'cardealer-helper' ),
							);
						} else {
							require_once ABSPATH . 'wp-load.php';
							if ( ! function_exists( 'post_exists' ) ) {
								require_once ABSPATH . 'wp-admin/includes/post.php';
							}
							if ( ! function_exists( 'comment_exists' ) ) {
								require_once ABSPATH . 'wp-admin/includes/comment.php';
							}

							require_once ABSPATH . 'wp-admin/includes/image.php';
							require_once ABSPATH . 'wp-admin/includes/media.php';
							require_once ABSPATH . 'wp-admin/includes/taxonomy.php';

							// Prepapre Data Files.
							$sample_data_path = get_parent_theme_file_path( 'includes/sample_data' );
							$sample_data_url  = get_parent_theme_file_uri( 'includes/sample_data' );

							// Import sample data.
							ob_start();
							$import_stattus = $importer->import( $download_sample );
							ob_clean();
							ob_end_clean();

							flush_rewrite_rules( true );

							// Set imported menus to registered theme locations.
							$locations        = get_theme_mod( 'nav_menu_locations' ); // registered menu locations in theme.
							$registered_menus = wp_get_nav_menus(); // registered menus.

							// Assign Menu Name to Registered menus as array keys.
							$registered_menus_new = array();
							foreach ( $registered_menus as $registered_menu ) {
								$registered_menus_new[ strtolower( $registered_menu->name ) ] = $registered_menu;
							}

							// Assign Menus to provided locations.
							if ( ! empty( $sample_data['menus'] ) && is_array( $sample_data['menus'] ) ) {
								foreach ( $sample_data['menus'] as $menu_loc => $menu_nm ) {
									if ( isset( $registered_menus_new[ strtolower( $menu_nm ) ] ) ) {
										$reg_menu_data          = $registered_menus_new[ strtolower( $menu_nm ) ];
										$locations[ $menu_loc ] = $reg_menu_data->term_id;
									}
								}
							}

							set_theme_mod( 'nav_menu_locations', $locations ); // set menus to locations.

							WP_Filesystem();
							global $wp_filesystem;

							// Import Theme Options.
							$theme_options_data_url = cdhl_sample_data_url( $sample_id, 'theme_options.json' );
							$theme_options_data     = download_url( $theme_options_data_url );
							if ( ! is_wp_error( $theme_options_data ) && file_exists( $theme_options_data ) ) {
								$redux_options_json = $wp_filesystem->get_contents( $theme_options_data );
								$redux_options      = json_decode( $redux_options_json, true );

								global $cdhl_array_replace_data;
								$cdhl_array_replace_data['old'] = $sample_data['demo_url'];
								$cdhl_array_replace_data['new'] = home_url( '/' );
								$redux_options                  = array_map( 'cdhl_replace_array', $redux_options );

								$redux_options = apply_filters( 'cdhl_sample_data_import_theme_options_data', $redux_options, 'car_dealer_options' );

								do_action( 'cdhl_sample_data_before_import_theme_options', $redux_options );
								update_option( 'car_dealer_options', $redux_options );
								do_action( 'cdhl_sample_data_import_theme_options', $redux_options );
							} else {
								if ( defined( 'PGS_DEV_DEBUG' ) && PGS_DEV_DEBUG ) {
									$import_status_data = array(
										'success' => false,
										'message' => esc_html__( 'Unable to options file.', 'cardealer-helper' ) . ' Error: ' . $theme_options_data_url->get_error_message() . "\r\n" . $sample_data_url,
									);
								}
							}

							// Import Widget Data.
							$widget_data_url = cdhl_sample_data_url( $sample_id, 'widget_data.json' );
							$widget_data     = download_url( $widget_data_url );
							if ( ! is_wp_error( $widget_data ) && file_exists( $widget_data ) ) {
								if ( ! function_exists( 'cdhl_import_widget_data' ) ) {
									$widget_import = trailingslashit( CDHL_PATH ) . 'includes/lib/widget-importer-exporter/widget-import.php';
									if ( file_exists( $importer_path ) ) {
										include $widget_import;
									}
								}
								$widget_data_json    = $wp_filesystem->get_contents( $widget_data );
								$widget_data_decoded = json_decode( $widget_data_json );

								$cdhl_widget_import_results = cdhl_import_widget_data( $widget_data_decoded );
							} else {
								if ( defined( 'PGS_DEV_DEBUG' ) && PGS_DEV_DEBUG ) {
									$import_status_data = array(
										'success' => false,
										'message' => esc_html__( 'Unable to widgets file.', 'cardealer-helper' ) . ' Error: ' . $widget_data_url->get_error_message() . "\r\n" . $sample_data_url,
									);
								}
							}

							// Check if "revsliders" folder exists.
							if ( isset( $sample_data['revsliders'] ) && is_array( $sample_data['revsliders'] ) && ! empty( $sample_data['revsliders'] ) ) {
								$cdhl_revslider = new RevSlider();

								foreach ( $sample_data['revsliders'] as $revslider ) {
									$revslider_url  = cdhl_sample_data_url( $sample_id, 'revsliders/' . $revslider );
									$revslider_file = download_url( $revslider_url );
									if ( ! is_wp_error( $revslider_file ) && file_exists( $revslider_file ) && class_exists( 'UniteFunctionsRev' ) ) {
										ob_start();
										$cdhl_revslider->importSliderFromPost( true, false, $revslider_file );
										ob_clean();
										ob_end_clean();
									}
								}
							}

							// Home Page.
							update_option( 'show_on_front', 'page' );
							if ( isset( $sample_data['home_page'] ) ) {
								$sample_param_home_page = trim( $sample_data['home_page'] );
								if ( ! empty( $sample_param_home_page ) ) {
									$home_page = get_page_by_title( $sample_param_home_page );
									if ( isset( $home_page ) && $home_page->ID ) {
										update_option( 'page_on_front', $home_page->ID ); // Front Page.
										if ( 'Home - Inventory' === (string) $sample_param_home_page ) {
											global $car_dealer_options;
											$redux_options                           = $car_dealer_options;
											$redux_options['vehicle-listing-layout'] = 'lazyload';
											$redux_options['header_type']            = 'transparent-fullwidth';
											$redux_options['cars_inventory_page']    = $home_page->ID;
											update_option( 'car_dealer_options', $redux_options );
										}
									}
								}
							}

							// Blog Page.
							if ( isset( $sample_data['blog_page'] ) ) {
								$sample_param_blog_page = trim( $sample_data['blog_page'] );
								if ( ! empty( $sample_param_blog_page ) ) {
									$blog_page = get_page_by_title( $sample_data['blog_page'] );
									if ( isset( $blog_page ) && $blog_page->ID ) {
										update_option( 'page_for_posts', $blog_page->ID ); // Posts Page.
									}
								}
							}

							// save installed demo in DB.
							$default_sample_data = array();
							$sample_data_arr     = get_option( 'pgs_default_sample_data' );
							if ( ( isset( $sample_data_arr ) && ! empty( $sample_data_arr ) ) ) {
								$default_sample_data = json_decode( $sample_data_arr );
								if ( ! in_array( $sample_id, $default_sample_data, true ) ) {
									$default_sample_data[] = $sample_id;
								}
							} else {
								$default_sample_data[] = $sample_id;
							}
							update_option( 'pgs_default_sample_data', json_encode( $default_sample_data ) );

							// Send response.
							$import_status_data = array(
								'success' => true,
								'message' => esc_html__( 'All done. Remember to update the passwords and roles of imported users.', 'cardealer-helper' ),
							);
						}
					}
				}
			}
		}
		wp_send_json( $import_status_data );
		die();
	}
}

if ( ! function_exists( 'cdhl_replace_array' ) ) {
	/**
	 * Sample data import array functions.
	 *
	 * @param array $n varible.
	 */
	function cdhl_replace_array( $n ) {
		global $cardealer_helper_array_replace_data;

		if ( is_array( $n ) ) {
			return array_map( 'cdhl_replace_array', $n );
		} else {
			if ( ! empty( $cardealer_helper_array_replace_data ) && is_array( $cardealer_helper_array_replace_data ) && isset( $cardealer_helper_array_replace_data['old'] ) && isset( $cardealer_helper_array_replace_data['new'] ) ) {
				if ( strpos( $n, $cardealer_helper_array_replace_data['old'] ) !== false ) {
					return str_replace( $cardealer_helper_array_replace_data['old'], $cardealer_helper_array_replace_data['new'], $n );
				} else {
					return $n;
				}
			} else {
				return $n;
			}
		}
		return $n;
	}
}

add_action( 'wp_update_nav_menu_item', 'cdhl_import_custom_menu_metafields', 10, 3 );

if ( ! function_exists( 'cdhl_import_custom_menu_metafields' ) ) {
	/**
	 * Import menu metafields.
	 *
	 * @param int   $menu_id menu id.
	 * @param int   $menu_item_db_id database menu id.
	 * @param array $args menu id.
	 * @return void
	 */
	function cdhl_import_custom_menu_metafields( $menu_id, $menu_item_db_id, $args ) {
		$cardealer_helper_megamenu_data['cd_megamenu_enable'] = 1;
		update_term_meta( $menu_id, '_cd_megamenu_settings', $cardealer_helper_megamenu_data );

		$custom_fields = array(
			'disable_link',
			'mega_menu',
			'content_type',
			'menu_width',
			'column_count',
			'menu_alignment',
		);

		foreach ( $custom_fields as $custom_field ) {
			if ( ! empty( $args[ $custom_field ] ) ) {
				update_post_meta( $menu_item_db_id, $custom_field, $args[ $custom_field ] );
			}
		}
	}
}

if ( ! function_exists( 'cdhl_sample_import_templates' ) ) {
	/**
	 * Sample data templates.
	 *
	 * @return void
	 */
	function cdhl_sample_import_templates() {
		include_once trailingslashit( CDHL_PATH ) . 'includes/sample_data/templates/sample-import-alert.php';
	}
}
add_action( 'admin_footer', 'cdhl_sample_import_templates' );

if ( ! function_exists( 'cdhl_sample_data_requirements' ) ) {
	/**
	 * Sample data requirements.
	 */
	function cdhl_sample_data_requirements() {
		return apply_filters(
			'cdhl_sample_data_requirements',
			array(
				'memory-limit'       => esc_html__( 'Memory Limit: 128M or Higher', 'cardealer-helper' ),
				'max-execution-time' => esc_html__( 'Max Execution Time: 180 or Higher', 'cardealer-helper' ),
			)
		);
	}
}

if ( ! function_exists( 'cdhl_sample_data_required_plugins_list' ) ) {
	/**
	 * Required plugin list.
	 */
	function cdhl_sample_data_required_plugins_list() {

		$cardealer_helper_tgmpa_plugins_data_func = 'cardealer_tgmpa_plugins_data';
		$required_plugins_list                    = array();

		if ( function_exists( $cardealer_helper_tgmpa_plugins_data_func ) ) {
			$cardealer_helper_tgmpa_plugins_data = $cardealer_helper_tgmpa_plugins_data_func();

			$cardealer_helper_tgmpa_plugins_data_all = $cardealer_helper_tgmpa_plugins_data['all'];
			foreach ( $cardealer_helper_tgmpa_plugins_data_all as $cardealer_helper_tgmpa_plugins_data_k => $cardealer_helper_tgmpa_plugins_data_v ) {
				if ( ! $cardealer_helper_tgmpa_plugins_data_v['required'] ) {
					unset( $cardealer_helper_tgmpa_plugins_data_all[ $cardealer_helper_tgmpa_plugins_data_k ] );
				}
			}

			if ( ! empty( $cardealer_helper_tgmpa_plugins_data_all ) && is_array( $cardealer_helper_tgmpa_plugins_data_all ) ) {
				foreach ( $cardealer_helper_tgmpa_plugins_data_all as $cardealer_helper_tgmpa_plugin ) {
					$required_plugins_list[] = $cardealer_helper_tgmpa_plugin['name'];
				}
			}
		}

		return $required_plugins_list;
	}
}

if ( ! function_exists( 'cdhl_import_output_notices' ) ) {
	/**
	 * Import nitices.
	 *
	 * @param string $notice notice.
	 * @return void
	 */
	function cdhl_import_output_notices( $notice = '' ) {

		if ( empty( $notice ) ) {
			return;
		}

		if ( is_string( $notice ) ) {
			return;
		}

		// Outout notice.
		header( 'Content-type: text/html; charset=utf-8' );
		echo $notice; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		flush();
		ob_flush();
	}
}

add_action( 'init', 'cdhl_manual_sample_import', 99 );

if ( ! function_exists( 'cdhl_manual_sample_import' ) ) {
	/**
	 * Sample import manual.
	 *
	 * @return void
	 */
	function cdhl_manual_sample_import() {
		if ( isset( $_REQUEST['action'] ) && 'theme_import_sample' === (string) $_REQUEST['action'] ) {
			cdhl_theme_import_sample();
		}
	}
}

if ( ! function_exists( 'cdhl_sample_data_url' ) ) {
	/**
	 * Sample data url.
	 *
	 * @param string $sample_id sample data name.
	 * @param string $resource resource name.
	 */
	function cdhl_sample_data_url( $sample_id = '', $resource = '' ) {

		// bail early if sample_id or resource not provided.
		if ( empty( $sample_id ) || empty( $resource ) ) {
			return '';
		}

		$purchase_token = cardealer_is_activated();

		return add_query_arg(
			array(
				'sample_id'   => $sample_id, // default.
				'content'     => $resource,  // sample_data.xml.
				'token'       => $purchase_token,
				'site_url'    => get_site_url(),
				'product_key' => PGS_PRODUCT_KEY,
			),
			trailingslashit( PGS_ENVATO_API ) . 'sample-data'
		);
	}
}
