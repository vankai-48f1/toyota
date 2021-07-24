<?php
/**
 * Theme base functions.
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package CarDealer/Functions
 * @version 1.0.0
 */

if ( ! function_exists( 'cardealer_get_next_posts_link_attributes' ) ) {
	/**
	 * Acf get attachment
	 *
	 * Add rel and title attribute to next pagination link.

	 * @param array $attr (array) the array to look within.
	 */
	function cardealer_get_next_posts_link_attributes( $attr ) {
		$attr = 'rel="next" title="' . esc_attr__( 'View the Next Page', 'cardealer' ) . '"';
		return $attr;
	}
	add_filter( 'next_posts_link_attributes', 'cardealer_get_next_posts_link_attributes' );
}

if ( ! function_exists( 'cardealer_get_previous_posts_link_attributes' ) ) {
	/**
	 * Acf get attachment
	 *
	 * Add rel and title attribute to next pagination link.

	 * @param array $attr (array) the array to look within.
	 */
	function cardealer_get_previous_posts_link_attributes( $attr ) {
		$attr = 'rel="prev" title="' . esc_attr__( 'View the Previous Page', 'cardealer' ) . '"';
		return $attr;
	}
	add_filter( 'previous_posts_link_attributes', 'cardealer_get_previous_posts_link_attributes' );
}
if ( ! function_exists( 'cardealer_custom_admin_footer' ) ) {
	/**
	 * Acf get attachment
	 *
	 * Custom Backend Footer.
	 */
	function cardealer_custom_admin_footer() {
		sprintf(
			wp_kses(
				__( '<span id="footer-thankyou">Developed by <a href="$1" target="_blank">TeamWP @Potenza Global Solutions</a></span>.', 'cardealer' ),
				array(
					'span' => array(),
					'a'    => array(
						'href'   => array(),
						'target' => array(),
					),
				)
			),
			esc_url( 'http://www.potenzaglobalsolutions.com/' )
		);
	}
	add_filter( 'admin_footer_text', 'cardealer_custom_admin_footer' );
}
if ( ! function_exists( 'cardealer_wp_list_pages_filter' ) ) {
	/**
	 * Add page title attribute to wp_list_pages link tags
	 *
	 * @param array $output (array) the array to look within.
	 * @since Car Dealer 1.0
	 */
	function cardealer_wp_list_pages_filter( $output ) {
		$output = preg_replace( '/<a(.*)href="([^"]*)"(.*)>(.*)<\/a>/', '<a$1 title="$4" href="$2"$3>$4</a>', $output );
		return $output;
	}
	add_filter( 'wp_list_pages', 'cardealer_wp_list_pages_filter' );
}
/************************************
 * ADMIN CUSTOMIZATION
 * - Set content width
 * - Set image attachment width
 * - Disable default dashboard widgets
 * - Change name of "Posts" in admin menu
 *********************************** */

if ( ! function_exists( 'cardealer_content_width' ) ) {
	/**
	 * Adjust content_width value for image attachment template
	 *
	 * @since Car Dealer 1.0
	 */
	function cardealer_content_width() {
		if ( is_attachment() && wp_attachment_is_image() ) {
			$GLOBALS['content_width'] = 810;
		}
	}
	add_action( 'template_redirect', 'cardealer_content_width' );
}
if ( ! function_exists( 'cardealer_body_classes' ) ) {
	/**
	 * Adjust content_width value for image attachment template
	 *
	 * @param array $classes (array) the array to look within.
	 */
	function cardealer_body_classes( $classes ) {
		global $post, $car_dealer_options;

		/* Sidebar Classes */
		if ( is_front_page() || is_single() ) {
			$cardealer_blog_sidebar = isset( $car_dealer_options['blog_sidebar'] ) ? $car_dealer_options['blog_sidebar'] : '';
			$classes[]              = "sidebar-$cardealer_blog_sidebar";
		} elseif ( is_page() ) {
			$cardealer_page_sidebar = isset( $car_dealer_options['page_sidebar'] ) ? $car_dealer_options['page_sidebar'] : '';

			/* Page sidebar set inside page */
			$page_layout_custom = get_post_meta( $post->ID, 'page_layout_custom', true );
			if ( $page_layout_custom ) {
				$page_sidebar = get_post_meta( $post->ID, 'page_sidebar', true );
				if ( $page_sidebar ) {
					$cardealer_page_sidebar = $page_sidebar;
				}
			}

			$classes[] = "sidebar-$cardealer_page_sidebar";
		}
		if ( cardealer_is_vc_enabled() ) {
			$classes[] = 'is_vc_enabled';
		} else {
			$classes[] = 'is_vc_disabled';
		}

		return $classes;
	}
	add_filter( 'body_class', 'cardealer_body_classes' );
}
if ( ! function_exists( 'cardealer_get_site_logo' ) ) {
	/**
	 * Site logo settings.
	 */
	function cardealer_get_site_logo() {
		global $car_dealer_options;
		if ( isset( $car_dealer_options['site-logo'] ) && isset( $car_dealer_options['site-logo']['url'] ) ) {
			return $car_dealer_options['site-logo']['url'];
		} else {
			return false;
		}
	}
}

add_action( 'wp_ajax_cardealer_debug_send_mail', 'cardealer_debug_send_mail' );
add_action( 'wp_ajax_nopriv_cardealer_debug_send_mail', 'cardealer_debug_send_mail' );
/**
 * Debug Send mail
 */
function cardealer_debug_send_mail() {
	$response_array = array();
	$nonce          = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';
	if ( wp_verify_nonce( $nonce, 'pgs_mail_debug_nonce' ) ) {
		$sitename = wp_parse_url( network_home_url(), PHP_URL_HOST );
		if ( 'www.' === substr( $sitename, 0, 4 ) ) {
			$sitename = substr( $sitename, 4 );
		}
		$from_email = 'wordpress@' . $sitename;

		$from_email = isset( $_POST['from_email'] ) ? sanitize_email( wp_unslash( $_POST['from_email'] ) ) : $from_email;
		$to_mail    = isset( $_POST['to_email'] ) ? sanitize_email( wp_unslash( $_POST['to_email'] ) ) : get_option( 'admin_email' );
		$subject    = esc_html__( 'Testing Mail For Cardealer', 'cardealer' );
		$message    = esc_html__( 'Theme mail is working properly.', 'cardealer' );

		$headers[] = 'From: ' . $from_email . ' <' . $from_email . '>';
		$headers[] = 'Content-Type: text/html; charset=UTF-8';
		$headers[] = 'Reply-To: ' . wp_unslash( $from_email ) . '\r\n';

		$result = wp_mail( $to_mail, $subject, $message, $headers );
		if ( ! $result ) {
			global $ts_mail_errors;
			global $phpmailer;
			if ( ! isset( $ts_mail_errors ) ) $ts_mail_errors = array();
			if ( isset( $phpmailer ) ) {
				$response_array['status'] = false;
				$response_array['msg']    = sprintf(
					wp_kses(
						/* translators: 1: URL */
						__( 'There is an error while sending the email. Here is the <strong>PHPMailer Debug error:</strong><br><p>%s</p>', 'cardealer' ),
						array(
							'p'      => array(),
							'strong' => array(),
							'br'     => array(),
						)
					),
					$phpmailer->ErrorInfo
				);
			}
		} else {
			$response_array['status'] = true;
			$response_array['msg']  = esc_html__( 'Email sent successfully.', 'cardealer' );
		}
	} else {
		$response_array['status'] = false;
		$response_array['msg']    = esc_html__( 'You are not allowed to access this section.', 'cardealer' );
	}

	echo wp_json_encode( $response_array );

	exit();
}

add_action( 'wp_ajax_cardealer_debug_vinquery', 'cardealer_debug_vinquery' );
add_action( 'wp_ajax_nopriv_cardealer_debug_vinquery', 'cardealer_debug_vinquery' );
/**
 * Debug Vinquery
 */
function cardealer_debug_vinquery() {
	global $car_dealer_options;

	$response_array = array();
	$nonce          = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';
	$vinnumber      = isset( $_POST['vinnumber'] ) ? sanitize_text_field( wp_unslash( $_POST['vinnumber'] ) ) : '3GCPCREC7FG000000';

	if ( wp_verify_nonce( $nonce, 'pgs_vinquery_debug_nonce' ) ) {
		$vinquery_api_key = isset( $car_dealer_options['vinquery_api_key'] ) ? $car_dealer_options['vinquery_api_key'] : '';
		if ( ! $vinquery_api_key ) {
			$response_array['status'] = false;
			$response_array['msg']  = sprintf(
				wp_kses(
					__( 'The <strong>VINquery API Key</strong> field is empty. Please add <strong>VINquery API Key</strong> in <a href="%s" target="_blank">Theme Options</a>.', 'cardealer' ),
					array(
						'strong' => array(),
						'a'      => array(
							'href'   => true,
							'target' => true,
						),
					)
				),
				admin_url( 'themes.php?page=cardealer&tab=' . cardealer_get_redux_tab_id( 'vinquery_vin_settings' ) )
			);
		} else {
			if ( $vinnumber ) {
				if ( class_exists( 'CDVQI' ) ) {
					$cdvqi         = new CDVQI;
					$responce_body = $cdvqi->cdvi_get_vinquery_data( $vinnumber );
					if ( isset( $responce_body['Status'] ) ) {
						if ( 'FAILED' === $responce_body['Status'] ) {
							$response_array['status'] = false;
							$response_array['msg']    = wp_kses_post( $responce_body['Message'] )
							. '<br>'
							. sprintf(
								wp_kses(
									__( 'Please refer to this <a href="%s" target="_blank">documentation</a> for more details.', 'cardealer' ),
									array(
										'strong' => array(),
										'a'      => array(
											'href'   => true,
											'target' => true,
										),
									)
								),
								'https://vinquery.com/docs-vindecoding'
							);
						} else if ( 'SUCCESS' === $responce_body['Status'] ) {
							$response_array['status'] = true;
							$response_array['msg']    = wp_kses(
								__( '<strong>VINquery VIN Import</strong> is working fine.', 'cardealer' ),
								array(
									'strong' => array(),
								)
							);
						}
					} else {
						$response_array['status'] = false;
						$response_array['msg']  = esc_html__( 'Something went wrong. Unable to get request response.', 'cardealer' );
					}
				} else {
					$response_array['status'] = false;
					$response_array['msg']    = wp_kses(
						__( 'Make sure that the <strong>Car Dealer - VINquery Import</strong> plugin is activated.', 'cardealer' ),
						array(
							'strong' => array(),
						)
					);
				}
			} else {
				$response_array['status'] = false;
				$response_array['msg']    = esc_html__( 'VIN is misssing.', 'cardealer' );
			}
		}
	} else {
		$response_array['status'] = false;
		$response_array['msg']    = esc_html__( 'You are not allowed to access this section.', 'cardealer' );
	}

	echo wp_json_encode( $response_array );

	exit();
}


add_action( 'wp_ajax_cardealer_debug_mailchimp', 'cardealer_debug_mailchimp' );
add_action( 'wp_ajax_nopriv_cardealer_debug_mailchimp', 'cardealer_debug_mailchimp' );
/**
 * Debug Mailchimp
 */
function cardealer_debug_mailchimp() {
	global $car_dealer_options;

	$response_array    = array();
	$mailchimp_api_key = isset( $car_dealer_options['mailchimp_api_key'] ) ? $car_dealer_options['mailchimp_api_key'] : '';
	$mailchimp_list_id = isset( $car_dealer_options['mailchimp_list_id'] ) ? $car_dealer_options['mailchimp_list_id'] : '';

	$nonce = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';
	if ( wp_verify_nonce( $nonce, 'pgs_mailchimp_debug_nonce' ) ) {
		if ( ! $mailchimp_list_id || ! $mailchimp_api_key ) {
			$response_array['status'] = false;
			$response_array['msg']    = sprintf(
											wp_kses(
												__( 'There is an error. It seems like the Mailchimp API key or List-ID is empty, please add the value in <a href="%s" target="_blank">Theme Options</a>.', 'cardealer' ),
												array(
													'strong' => array(),
													'a'      => array(
														'href'   => true,
														'target' => true,
													),
												)
											),
											admin_url( 'themes.php?page=cardealer&tab=' . cardealer_get_redux_tab_id( 'mailchimp_settings_section' ) )
										);
		} else {
			$dc = 'us1';
			if ( strstr( $mailchimp_api_key, '-' ) ) {
				list( $key, $dc ) = explode( '-', $mailchimp_api_key, 2 );
				if ( ! $dc ) {
					$dc = 'us1';
				}
			}

			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, 'https://' . $dc . '.api.mailchimp.com/3.0/lists/' . $mailchimp_list_id );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET');
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt( $ch, CURLOPT_USERPWD, 'anystring' . ':' . $mailchimp_api_key );
			$response = curl_exec( $ch );
			if ( curl_errno( $ch ) ) {
				$response_array['status'] = false;
				$response_array['msg']    = sprintf(
					wp_kses(
						__( 'There is an error, Please check the response for more details.<br><strong>Respose error:</strong><p>%s</p>', 'cardealer' ),
						array(
							'p'      => array(),
							'strong' => array(),
							'br'     => array(),
						)
					),
					curl_error( $ch )
				);
			} else {
				$data = json_decode( $response, true );
				if ( isset( $data['id'] ) && $mailchimp_list_id === $data['id'] ) {
					$response_array['status'] = true;
					$response_array['msg']    = esc_html__( 'Theme Mailchimp API is working perfectly fine.', 'cardealer' );
				} else {
					$response_array['status'] = false;
					if ( isset( $data['status'] ) && isset( $data['detail'] ) ) {
						$response_array['msg']    = sprintf(
							wp_kses(
								__( 'There is an error, Please check the response for more details.<br><strong>Respose error:</strong><p>%1$s : %2$s</p>', 'cardealer' ),
								array(
									'p'      => array(),
									'strong' => array(),
									'br'     => array(),
								)
							),
							$data['status'],
							$data['detail']
						);
					} else {
						$response_array['msg']    = esc_html__( 'Something went wrong, API request not getting proper response.', 'cardealer' );
					}
				}
			}
			curl_close( $ch );
		}
	} else {
		$response_array['status'] = false;
		$response_array['msg']    = esc_html__( 'You are not allowed to access this section.', 'cardealer' );
	}

	echo wp_json_encode( $response_array );
	exit();
}


add_action( 'wp_ajax_cardealer_debug_google_analytics', 'cardealer_debug_google_analytics' );
add_action( 'wp_ajax_nopriv_cardealer_debug_google_analytics', 'cardealer_debug_google_analytics' );
/**
 * Debug for Google Analytics
 */
function cardealer_debug_google_analytics() {
	global $car_dealer_options;

	$response_array = array();
	$view_id        = get_option( 'options_view_id' );
	$tracking_id    = get_option( 'options_tracking_id' );
	$account_id     = get_option( 'options_account_id' );
	$ga_id          = isset( $_POST['ga_id'] ) ? sanitize_text_field( wp_unslash( $_POST['ga_id'] ) ) : '';
	$access_token   = function_exists( 'cdhl_get_access_token' ) ? cdhl_get_access_token() : '';
	$duration       = function_exists( 'cdhl_get_duration' ) ? cdhl_get_duration() : '';

	if ( $duration ) {
		$start_date = isset( $duration['start_date'] ) ? $duration['start_date'] : '';
		$end_date   = isset( $duration['end_date'] ) ? $duration['end_date'] : '';
	}

	if ( ! $view_id || ! $access_token || ! $end_date || ! $start_date || ! $tracking_id || ! $account_id ) {
		$response_array['status'] = false;
		$response_array['msg']    = sprintf(
			wp_kses(
				__( 'Required fields are missing. Make sure you have added all the required values in the <a href="%s" target="_blank">Google Analytics Settings</a>.', 'cardealer' ),
				array(
					'strong' => array(),
					'a'      => array(
						'href'   => true,
						'target' => true,
					),
				)
			),
			admin_url( 'admin.php?page=google-analytics-settings' )
		);

		echo wp_json_encode( $response_array );
		exit();
	}

	$nonce = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';
	if ( wp_verify_nonce( $nonce, 'pgs_google_analytics_debug_nonce' ) ) {
		if ( 'google-analytics' === $ga_id ) {
			$get_data = wp_remote_get( 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A' . $view_id . '&start-date=' . $start_date . '&end-date=' . $end_date . '&metrics=ga%3Ausers%2Cga%3Asessions%2Cga%3AbounceRate%2Cga%3AnewUsers%2Cga%3AavgSessionDuration%2Cga%3Apageviews&access_token=' . $access_token );
			$message = esc_html__( 'The Google Analytics is working fine.', 'cardealer' );
		} else if ( 'browser-usage' === $ga_id ) {
			$get_data = wp_remote_get( 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A' . $view_id . '&start-date=' . $start_date . '&end-date=' . $end_date . '&metrics=ga%3Apageviews&dimensions=ga%3Abrowser&access_token=' . $access_token );
			$message = esc_html__( 'The request made for browser usage is working fine.', 'cardealer' );
		} else if ( 'website-statistics' === $ga_id ) {
			$get_data = wp_remote_get( 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A' . $view_id . '&start-date=' . $start_date . '&end-date=' . $end_date . '&metrics=ga%3Apageviews&dimensions=ga%3AdeviceCategory&access_token=' . $access_token );
			$message = esc_html__( 'The request made for website statistics is working fine.', 'cardealer' );
		} else if ( 'website-users' === $ga_id ) {
			$get_data = wp_remote_get( 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A' . $view_id . '&start-date=' . $start_date . '&end-date=' . $end_date . '&metrics=ga%3Ausers&dimensions=ga%3Aregion&access_token=' . $access_token );
			$message = esc_html__( 'The request made for website users is working fine.', 'cardealer' );
		} else if ( 'google-analytics-goal' === $ga_id ) {
			$get_data = wp_remote_get( 'https://www.googleapis.com/analytics/v3/management/accounts/' . $account_id . '/webproperties/' . $tracking_id . '/profiles/' . $view_id . '/goals?&access_token=' . $access_token );
			$message = esc_html__( 'The Google Analytics Goal is working fine.', 'cardealer' );
		} else {
			$response_array['status'] = false;
			$response_array['msg']    = esc_html__( 'Something went wrong.', 'cardealer' );
		}

		if ( ! is_wp_error( $get_data ) && 200 === (int) $get_data['response']['code'] ) {
			$response_array['status'] = true;
			$response_array['msg']    = $message;
		} else {

			$data = json_decode( $get_data['body'], true );
			if ( isset( $data['error'] ) ) {
				$code    = isset( $data['error']['code'] ) ? $data['error']['code'] : 'N/A';
				$message = isset( $data['error']['message'] ) ? $data['error']['message'] : 'N/A';
			}

			$response_array['status'] = false;
			$response_array['msg']    = esc_html__( 'There is an error in the Google Analytics request. Please check the response for more details.', 'cardealer' )
			. '<br>'
			. sprintf(
				wp_kses(
					__( '<strong>Respose error:</strong><p>%1$s</p><p>%2$s</p>', 'cardealer' ),
					array(
						'p'      => array(),
						'strong' => array(),
						'br'     => array(),
					)
				),
				$code,
				$message
			);
		}

	} else {
		$response_array['status'] = false;
		$response_array['msg']    = esc_html__( 'You are not allowed to access this section.', 'cardealer' );
	}

	echo wp_json_encode( $response_array );
	exit();
}

if ( ! function_exists( 'cardealer_get_redux_tab_id' ) ) {
	/**
	 * Get the redux tab id.
	 */
	function cardealer_get_redux_tab_id( $tab_key ) {
		global $opt_name;

		if ( class_exists( 'Redux' ) ) {
			$all_fields = Redux::getSections( $opt_name );
			if ( is_array( $all_fields ) ) {
				foreach ( $all_fields as $option_key => $option_value ) {
					if ( isset( $option_value['id'] ) && $option_value['id'] === $tab_key ) {
						return $option_value['priority'];
					}
				}
			} else {
				return false;
			}
		}

		return false;
	}
}

if ( ! function_exists( 'cardealer_get_site_sticky_logo' ) ) {
	/**
	 * Site sticky logo settings.
	 */
	function cardealer_get_site_sticky_logo() {
		global $car_dealer_options;
		if ( isset( $car_dealer_options['site-sticky-logo'] ) && isset( $car_dealer_options['site-sticky-logo']['url'] ) ) {
			return $car_dealer_options['site-sticky-logo']['url'];
		} else {
			return false;
		}
	}
}
if ( ! function_exists( 'cardealer_display_loader' ) ) {
	/**
	 * Display loader.
	 */
	function cardealer_display_loader() {
		global $car_dealer_options;
		/* get the status of the side bar */
		$preloader = isset( $car_dealer_options['preloader'] ) ? $car_dealer_options['preloader'] : '';
		if ( $preloader ) {
			$preloader_img = $car_dealer_options['preloader_img'];
			if ( isset( $car_dealer_options['preloader_html'] ) && 'code' === $preloader_img ) {
				if ( ! empty( $car_dealer_options['preloader_html'] ) ) {
					echo do_shortcode( $car_dealer_options['preloader_html'] );
				}
			} else {
				if ( 'pre_loader' === $preloader_img ) {
					$img_url = CARDEALER_URL . '/images/preloader_img/' . $car_dealer_options['predefined_loader_img'] . '.gif';
				} else {
					$img_url = $car_dealer_options['preloader_image']['url'];
				}
				?>
					<div id="loading">
						<div id="loading-center">
							<img src="<?php echo esc_url( $img_url ); ?>" alt="Loader" title="loading...">
						</div>
					</div>
				<?php
			}
		}
	}
}
if ( ! function_exists( 'cardealer_intro_class' ) ) {
	/**
	 * Intro Class.
	 */
	function cardealer_intro_class() {
		global $post, $car_dealer_options;

		$header_intro_class = array();

		/* Set classes from Options */
		$banner_type = isset( $car_dealer_options['banner_type'] ) ? $car_dealer_options['banner_type'] : '';
		if ( empty( $banner_type ) ) {
			$banner_type = 'image';
		}

		$header_intro_class['header_intro_bg'] = 'header_intro_bg-' . $banner_type;

		if ( 'image' === $banner_type ) {
			if ( ! empty( $car_dealer_options['banner_image_opacity'] ) ) {
				$header_intro_class['header_intro_opacity']      = 'header_intro_opacity';
				$header_intro_class['header_intro_opacity_type'] = 'header_intro_opacity-' . $car_dealer_options['banner_image_opacity'];
			}
		} elseif ( 'video' === $banner_type ) {
			if ( ! empty( $car_dealer_options['banner_video_opacity'] ) ) {
				$header_intro_class['header_intro_opacity']      = 'header_intro_opacity';
				$header_intro_class['header_intro_opacity_type'] = 'header_intro_opacity-' . $car_dealer_options['banner_video_opacity'];
			}
		}

		if ( is_page() || is_home() || is_single() || is_archive() ) {
			$post_id = ( ( is_home() ) ? get_option( 'page_for_posts' ) : ( isset( $post->ID ) ? $post->ID : null ) );
			if ( is_archive() ) {
				$post_id = cardealer_get_current_post_id();
			}

			$enable_custom_banner = get_post_meta( $post_id, 'enable_custom_banner', true );
			if ( $enable_custom_banner ) {
				unset( $header_intro_class['header_intro_bg'] );
				unset( $header_intro_class['header_intro_opacity'] );
				unset( $header_intro_class['header_intro_opacity_type'] );
				$banner_type = get_post_meta( $post_id, 'banner_type', true );
				if ( empty( $banner_type ) ) {
					$banner_type = 'image';
				}
				$header_intro_class['header_intro_bg'] = 'header_intro_bg-' . $banner_type;

				if ( $banner_type && 'image' === $banner_type ) {
					$header_intro_class['header_intro_opacity'] = 'header_intro_opacity';
					$background_opacity_color                   = get_post_meta( $post_id, 'background_opacity_color', true );
					if ( $background_opacity_color ) {
						$header_intro_class['header_intro_opacity_type'] = 'header_intro_opacity-' . $background_opacity_color;
					}
				} elseif ( $banner_type && 'video' === $banner_type ) {
					$header_intro_class['header_intro_opacity'] = 'header_intro_opacity';
					$video_background_opacity_color             = get_post_meta( $post_id, 'video_background_opacity_color', true );
					if ( $video_background_opacity_color ) {
						$header_intro_class['header_intro_opacity_type'] = 'header_intro_opacity-' . $video_background_opacity_color;
					}
				}
			}
		}

		$header_intro_class = implode( ' ', $header_intro_class );
		echo esc_attr( $header_intro_class );
	}
}
if ( ! function_exists( 'cardealer_inventory_page_title' ) ) {
	/**
	 * Vehicle Archieve page title.
	 *
	 * @param array $title to look within.
	 */
	function cardealer_inventory_page_title( $title ) {
		global $car_dealer_options;
		$page_id = cardealer_get_current_post_id();
		if ( $page_id && ! empty( $car_dealer_options['cars_inventory_page'] ) && $page_id == $car_dealer_options['cars_inventory_page'] ) {
			$title = get_the_title( $page_id );
		} elseif ( isset( $car_dealer_options['cars-listing-title'] ) && is_post_type_archive( 'cars' ) ) {
			$title = $car_dealer_options['cars-listing-title'];
		}
		/* if WordPress can't find the title return the default */
		return $title;
	}
	add_filter( 'pre_get_document_title', 'cardealer_inventory_page_title' );
}
if ( ! function_exists( 'cardealer_footer_class' ) ) {
	/**
	 * Footer class Intro Class.
	 */
	function cardealer_footer_class() {
		global $post, $car_dealer_options;

		$footer_class = array();

		/* Set classes from Options */
		$banner_type_footer = isset( $car_dealer_options['banner_type_footer'] ) ? $car_dealer_options['banner_type_footer'] : '';
		if ( empty( $banner_type_footer ) ) {
			$banner_type_footer = 'color';
		}

		$footer_class['footer_bg'] = 'footer_bg-' . $banner_type_footer;
		if ( 'image' === $banner_type_footer ) {
			if ( ! empty( $car_dealer_options['banner_image_opacity_footer'] ) ) {
				$footer_class['header_intro_opacity_footer']      = 'footer_opacity';
				$footer_class['header_intro_opacity_type_footer'] = 'footer_opacity-' . $car_dealer_options['banner_image_opacity_footer'];
			}
		}
		return $footer_class;
	}
}
if ( ! function_exists( 'cardealer_excerpt_more' ) ) {
	/**
	 * Vehicle Archieve page title.
	 *
	 * @param array $more to look within.
	 */
	function cardealer_excerpt_more( $more ) {
		global $post;
		return '&hellip; <a class="read-more" href="' . esc_url( get_permalink( $post->ID ) ) . '" title="' . esc_html__( 'Read', 'cardealer' ) . get_the_title( $post->ID ) . '">' . esc_html__( 'Read more &raquo;', 'cardealer' ) . '</a>';
	} // end cardealer excerpt more function
}
if ( ! function_exists( 'cardealer_remove_img_dimensions' ) ) {
	/**
	 * Vehicle Archieve page title.
	 *
	 * @param array $html to look within.
	 * @link https://gist.github.com/4557917
	 */
	function cardealer_remove_img_dimensions( $html ) {
		/* Loop through all <img> tags */
		if ( preg_match( '/<img[^>]+>/ims', $html, $matches ) ) {
			foreach ( $matches as $match ) {
				/* Replace all occurences of width/height */
				$clean = preg_replace( '/(width|height)=["\'\d%\s]+/ims', '', $match );
				/* Replace with result within html */
				$html = str_replace( $match, $clean, $html );
			}
		}
		return $html;
	}
	add_filter( 'get_avatar', 'cardealer_remove_img_dimensions', 10 );
}

if ( ! function_exists( 'cardealer_get_excerpt_max_charlength' ) ) {
	/**
	 * Truncate String with or without ellipsis.
	 *
	 * @param int    $charlength Maximum length of char.
	 * @param string $excerpt .
	 *
	 * @return string Shotened Text
	 */
	function cardealer_get_excerpt_max_charlength( $charlength, $excerpt = null ) {
		if ( empty( $excerpt ) ) {
			$excerpt = get_the_excerpt();
		}
		$charlength++;

		if ( mb_strlen( $excerpt ) > $charlength ) {
			$subex   = mb_substr( $excerpt, 0, $charlength - 5 );
			$exwords = explode( ' ', $subex );
			$excut   = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );

			$new_excerpt = '';
			if ( $excut < 0 ) {
				$new_excerpt = mb_substr( $subex, 0, $excut );
			} else {
				$new_excerpt = $subex;
			}
			$new_excerpt .= '[...]';
			return $new_excerpt;
		} else {
			return $excerpt;
		}
	}
}
if ( ! function_exists( 'cardealer_the_excerpt_max_charlength' ) ) {
	/**
	 * Truncate String with or without ellipsis.
	 *
	 * @param int    $charlength Maximum length of char.
	 * @param string $excerpt .
	 */
	function cardealer_the_excerpt_max_charlength( $charlength, $excerpt = null ) {
		$new_excerpt = cardealer_get_excerpt_max_charlength( $charlength, $excerpt );
		echo esc_html( $new_excerpt );
	}
}
if ( ! function_exists( 'cardealer_is_login_page' ) ) {
	/**
	 * Check if on login or register page.
	 */
	function cardealer_is_login_page() {
		return in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) ); // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
	}
}
if ( ! function_exists( 'cardealer_layout_content' ) ) {
	/**
	 * Truncate String with or without ellipsis.
	 *
	 * @param string $field .
	 * @param string $context .
	 */
	function cardealer_layout_content( $field = '', $context = '' ) {
		global $car_dealer_options;

		if ( empty( $field ) ) {
			return;
		}

		switch ( $field ) {
			case 'email':
				if ( isset( $car_dealer_options['site_email'] ) && ! empty( $car_dealer_options['site_email'] ) ) {
					if ( 'topbar' === $context ) {
						return '<i class="far fa-envelope"></i> <a href="mailto:' . sanitize_email( $car_dealer_options['site_email'] ) . '">' . sanitize_email( $car_dealer_options['site_email'] ) . '</a>';
					} else {
						return sanitize_email( $car_dealer_options['site_email'] );
					}
				} else {
					return;
				}
				break;
			case 'address':
				if ( isset( $car_dealer_options['site_address'] ) && ! empty( $car_dealer_options['site_address'] ) ) {
					if ( 'topbar' === $context ) {
						return '<i class="fas fa-map-marker-alt"></i> ' . esc_html( $car_dealer_options['site_address'] ) . '</a>';
					} else {
						return esc_html( $car_dealer_options['site_address'] );
					}
				} else {
					return;
				}
				break;
			case 'promocode':
				$element_id = uniqid( 'cdhl-promo-' );
				ob_start();
				?>
				<div class="top-promocode-box">
					<div class="promocode-form form-inline" id="<?php echo esc_attr( $element_id ); ?>">
						<input type="hidden" name="action" class="promocode_action" value="validate_promocode"/>
						<input type="hidden" name="promocode_nonce" class="promocode_nonce" value="<?php echo esc_html( wp_create_nonce( 'cdhl-promocode-form' ) ); ?>">
						<div class="form-group">
							<label for="promocode" class="sr-only"><?php esc_html_e( 'Promocode', 'cardealer' ); ?></label>
							<input type="text" name="promocode" class="form-control promocode" placeholder="<?php echo esc_attr__( 'Promocode', 'cardealer' ); ?>">
						</div>
						<button type="button" class="button promocode-btn" data-fid="<?php echo esc_attr( $element_id ); ?>"><?php echo esc_html__( 'Go', 'cardealer' ); ?></button>
						<span class="spinimg"></span>
						<p class="promocode-msg" style="display:none;"></p>
					</div>
				</div>
				<?php
				return ob_get_clean();
			break;
			case 'whatsapp_number':
				if ( isset( $car_dealer_options['site_whatsapp_num'] ) && ! empty( $car_dealer_options['site_whatsapp_num'] ) ) {
					if ( 'topbar' === $context ) {
						return '<i class="fab fa-whatsapp"></i> ' . esc_html( $car_dealer_options['site_whatsapp_num'] );
					} else {
						return esc_html( $car_dealer_options['site_whatsapp_num'] );
					}
				} else {
					return;
				}
				break;
			case 'phone_number':
				if ( isset( $car_dealer_options['site_phone'] ) && ! empty( $car_dealer_options['site_phone'] ) ) {
					if ( 'topbar' === $context ) {
						return '<a href="' . esc_url( 'tel:' . $car_dealer_options['site_phone'] ) . '"><i class="fas fa-phone-alt"></i> ' . esc_html( $car_dealer_options['site_phone'] ) . '</a>';
					} else {
						return esc_html( $car_dealer_options['site_phone'] );
					}
				} else {
					return;
				}
				break;
			case 'phone_number2':
				if ( isset( $car_dealer_options['site_phone2'] ) && ! empty( $car_dealer_options['site_phone2'] ) ) {
					if ( 'topbar' === $context ) {
						return '<a href="' . esc_url( 'tel:' . $car_dealer_options['site_phone2'] ) . '"><i class="fas fa-mobile-alt"></i> ' . esc_html( $car_dealer_options['site_phone2'] ) . '</a>';
					} else {
						return esc_html( $car_dealer_options['site_phone2'] );
					}
				} else {
					return;
				}
				break;
			case 'contact_timing':
				if ( isset( $car_dealer_options['site_contact_timing'] ) && ! empty( $car_dealer_options['site_contact_timing'] ) ) {
					if ( 'topbar' === $context ) {
						return '<i class="far fa-clock"></i> ' . esc_html( $car_dealer_options['site_contact_timing'] );
					} else {
						return esc_html( $car_dealer_options['site_contact_timing'] );
					}
				} else {
					return;
				}
				break;
			case 'top-bar-menu':
				if ( has_nav_menu( 'topbar-menu' ) ) {
					return wp_nav_menu(
						array(
							'theme_location' => 'topbar-menu',
							'menu_class'     => 'top-bar-menu list-inline',
							'menu_id'        => 'top-bar-menu',
							'echo'           => false,
						)
					);
				}
				break;
			case 'login':
				$icon_class       = 'fas fa-lock';
				$url_label        = esc_html__( 'Login', 'cardealer' );
				$topbar_login_url = wp_login_url( add_query_arg( array(), remove_query_arg( array() ) ) );

				/* WooCommerce */
				if ( class_exists( 'WooCommerce' ) ) {
					if ( 'topbar' === $context ) {
						if ( is_user_logged_in() ) {
							$topbar_login_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
							$icon_class       = 'far fa-user';
							$url_label        = esc_html__( 'My Account', 'cardealer' );
						} else {
							$topbar_login_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
						}
					}
				}

				$url_label        = apply_filters( 'topbar_login_url_label', $url_label );
				$icon_class       = apply_filters( 'topbar_login_url_icon', $icon_class );
				$topbar_login_url = apply_filters( 'topbar_login_url', $topbar_login_url );

				/* Theme Options */
				if ( isset( $car_dealer_options['topbar_custom_login_url'] ) && ! empty( $car_dealer_options['topbar_custom_login_url'] ) ) {
					$topbar_login_url = $car_dealer_options['topbar_custom_login_url'];
				}

				/**
				 * Filters the Label of the Top Bar login URL.
				 *
				 * @since 1.0
				 * @param string        $url_label Label of the top bar login url.
				 * @visible             true
				 */
				$url_label = apply_filters( 'cd_topbar_login_url_label_final', $url_label );
				/**
				 * Filters the Icon class of the Top Bar login URL Use this filter to change top bar login icon.
				 *
				 * @since 1.0
				 * @param string        $icon_class Icon class of the top bar login url.
				 * @visible             true
				 */
				$icon_class = apply_filters( 'cd_topbar_login_url_icon_final', $icon_class );
				/**
				 * Filters the Top Bar login URL.
				 *
				 * @since 1.0
				 * @param string        $topbar_login_url top bar login url.
				 * @visible             true
				 */
				$topbar_login_url = apply_filters( 'cd_topbar_login_url_final', $topbar_login_url );
				ob_start();
				?>
				<a href="<?php echo esc_url( $topbar_login_url ); ?>" >
					<i class="<?php echo esc_attr( $icon_class ); ?>"></i> <?php echo esc_attr( $url_label ); ?>
				</a>
				<?php
				$login_url = ob_get_contents();
				ob_end_clean();
				return $login_url;
			break;
			case 'language-switcher':
				ob_start();
				cardealer_get_multi_lang();
				$language_switcher = ob_get_contents();
				ob_end_clean();
				return $language_switcher;
			break;
			default:
				$social_profiles      = array(
					'facebook_url'    => array(
						'key'  => 'facebook',
						'name' => esc_html__( 'Facebook', 'cardealer' ),
						'icon' => '<i class="fab fa-facebook-f"></i>',
					),
					'twitter_url'     => array(
						'key'  => 'twitter',
						'name' => esc_html__( 'Twitter', 'cardealer' ),
						'icon' => '<i class="fab fa-twitter"></i>',
					),
					'dribbble_url'    => array(
						'key'  => 'dribbble',
						'name' => esc_html__( 'Dribbble', 'cardealer' ),
						'icon' => '<i class="fab fa-dribbble"></i>',
					),
					'google_plus_url' => array(
						'key'  => 'google_plus',
						'name' => esc_html__( 'Google Plus', 'cardealer' ),
						'icon' => '<i class="fab fa-google-plus-g"></i>',
					),
					'vimeo_url'       => array(
						'key'  => 'vimeo',
						'name' => esc_html__( 'Vimeo', 'cardealer' ),
						'icon' => '<i class="fab fa-vimeo-v"></i>',
					),
					'pinterest_url'   => array(
						'key'  => 'pinterest',
						'name' => esc_html__( 'Pinterest', 'cardealer' ),
						'icon' => '<i class="fab fa-pinterest"></i>',
					),
					'behance_url'     => array(
						'key'  => 'behance',
						'name' => esc_html__( 'Behance', 'cardealer' ),
						'icon' => '<i class="fab fa-behance"></i>',
					),
					'linkedin_url'    => array(
						'key'  => 'linkedin',
						'name' => esc_html__( 'Linkedin', 'cardealer' ),
						'icon' => '<i class="fab fa-linkedin-in"></i>',
					),
					'instagram_url'   => array(
						'key'  => 'instagram',
						'name' => esc_html__( 'Instagram', 'cardealer' ),
						'icon' => '<i class="fab fa-instagram"></i>',
					),
					'youtube_url'     => array(
						'key'  => 'youtube',
						'name' => esc_html__( 'Youtube', 'cardealer' ),
						'icon' => '<i class="fab fa-youtube"></i>',
					),
					'medium_url'      => array(
						'key'  => 'medium',
						'name' => esc_html__( 'Medium', 'cardealer' ),
						'icon' => '<i class="fab fa-medium-m"></i>',
					),
					'flickr_url'      => array(
						'key'  => 'flickr',
						'name' => esc_html__( 'Flickr', 'cardealer' ),
						'icon' => '<i class="fab fa-flickr"></i>',
					),
					'rss_url'         => array(
						'key'  => 'rss',
						'name' => esc_html__( 'RSS', 'cardealer' ),
						'icon' => '<i class="fas fa-rss"></i>',
					),
				);
				$social_profiles_temp = $social_profiles;

				foreach ( $social_profiles as $social_profile_k => $social_profile_data ) {
					if ( isset( $car_dealer_options[ $social_profile_k ] ) && ! empty( $car_dealer_options[ $social_profile_k ] ) ) {
						$social_profiles_temp[ $social_profile_k ]['url'] = $car_dealer_options[ $social_profile_k ];
					} else {
						unset( $social_profiles_temp[ $social_profile_k ] );
					}
				}
				if ( empty( $social_profiles_temp ) ) {
					return;
				}
				if ( 'topbar' === $context ) {
					$social_content = '';
					foreach ( $social_profiles_temp as $social_profile ) {
						$social_content .= '<li class="topbar_item topbar_item_type-social_profiles"><a href="' . esc_url( $social_profile['url'] ) . '" target="_blank">' . $social_profile['icon'] . '</a></li>';
					}
					/**
					 * Filters the social profile links displayed in site top bar.
					 *
					 * @since 1.0
					 * @param string        $social_content Contents of the social profile in site top bar.
					 * @visible             true
					 */
					return apply_filters( 'cardealer_social_profiles', $social_content );
				} else {
					return $social_profiles_temp;
				}
		}
	}
}

if ( ! function_exists( 'cardealer_generate_css_properties' ) ) {
	/**
	 * Converts a multidimensional array of CSS rules into a CSS string.
	 *
	 * @param array $rules array of CSS rules.
	 * @param int   $indent is count variable.
	 *
	 * An array of CSS rules in the form of:
	 * array('selector'=>array('property' => 'value')). Also supports selector
	 *   nesting, e.g.,
	 *   array('selector' => array('selector'=>array('property' => 'value'))).
	 *
	 * @return string
	 *   A CSS string of rules. This is not wrapped in style tags.
	 *
	 * @link source : http://www.grasmash.com/article/convert-nested-php-array-css-string
	 */
	function cardealer_generate_css_properties( $rules, $indent = 0 ) {
		$css    = '';
		$prefix = str_repeat( '  ', $indent );
		foreach ( $rules as $key => $value ) {
			if ( is_array( $value ) ) {
				$selector   = $key;
				$properties = $value;

				$css .= $prefix . "$selector {\n";
				$css .= $prefix . cardealer_generate_css_properties( $properties, $indent + 1 );
				$css .= $prefix . "}\n";
			} else {
				$property = $key;
				$css     .= $prefix . "$property: $value;\n";
			}
		}
		return $css;
	}
}
if ( ! function_exists( 'cardealer_hex2rgba' ) ) {
	/**
	 * Convert hexdec color string to rgb(a) string.
	 *
	 * @param string $color .
	 * @param string $opacity .
	 * @link Source : https://support.advancedcustomfields.com/forums/topic/color-picker-values/
	 */
	function cardealer_hex2rgba( $color = '', $opacity = false ) {

		$default = 'rgb(0,0,0)';

		/* Return default if no color provided */
		if ( empty( $color ) ) {
			return $default;
		}

		/* Sanitize $color if "#" is provided */
		if ( '#' === $color[0] ) {
			$color = substr( $color, 1 );
		}

		/* Check if color has 6 or 3 characters and get values */
		if ( strlen( $color ) == 6 ) {
			$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} elseif ( strlen( $color ) == 3 ) {
			$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return $default;
		}

		/* Convert hexadec to rgb */
		$rgb = array_map( 'hexdec', $hex );

		/* Check if opacity is set(rgba or rgb) */
		if ( $opacity ) {
			if ( abs( $opacity ) > 1 ) {
				$opacity = 1.0;
			}
			$output = 'rgba(' . implode( ',', $rgb ) . ',' . $opacity . ')';
		} else {
			$output = 'rgb(' . implode( ',', $rgb ) . ')';
		}

		/* Return rgb(a) color string */
		return $output;
	}
}
if ( ! function_exists( 'cardealer_array_sort_by_column' ) ) {
	/**
	 * Cardealer array sort by column.
	 *
	 * @param array $array refference variable.
	 *
	 * @param int   $column assign the column.
	 * @param array $direction .
	 */
	function cardealer_array_sort_by_column( &$array, $column, $direction = SORT_ASC ) {
		$reference_array = array();
		foreach ( $array as $key => $row ) {
			if ( isset( $row[ $column ] ) ) {
				$reference_array[ $key ] = $row[ $column ];
			}
		}
		if ( count( $reference_array ) == count( $array ) ) {
			array_multisort( $reference_array, $array, $direction );
		}
	}
}

if ( ! function_exists( 'cardealer_is_vc_enabled' ) ) {
	/**
	 * Return whether Visual Composer is enabled on a page/post or not.
	 *
	 * @param string $post_id = numeric post_id .
	 * return true/false .
	 */
	function cardealer_is_vc_enabled( $post_id = '' ) {
		global $post;

		if ( is_search() || is_404() || empty( $post ) ) {
			return;
		}

		if ( empty( $post_id ) ) {
			$post_id = $post->ID;
		}
		$vc_enabled = get_post_meta( $post_id, '_wpb_vc_js_status', true );
		return ( 'true' === $vc_enabled ) ? true : false;
	}
}
if ( ! function_exists( 'cardealer_hide_page_templates' ) ) {
	/**
	 * Hide page template if Car Dealer helper plugin not activate.
	 *
	 * @param string $page_templates .
	 */
	function cardealer_hide_page_templates( $page_templates ) {

		if ( ! cardealer_check_plugin_active( 'cardealer-helper-library/cardealer-helper-library.php' ) ) {
			unset( $page_templates['templates/faq.php'] );
			unset( $page_templates['templates/team.php'] );
			unset( $page_templates['templates/promocode.php'] );
		}

		if ( ! cardealer_check_plugin_active( 'js_composer/js_composer.php' ) ) {
			unset( $page_templates['templates/page-vc_compatible.php'] );
		}

		return $page_templates;
	}
	add_filter( 'theme_page_templates', 'cardealer_hide_page_templates', 10, 1 );
}
if ( ! function_exists( 'cardealer_get_attachment_detail' ) ) {
	/**
	 * FUNCTION TO GET IMAGE DATA.
	 *
	 * @param string $attachment_id .
	 */
	function cardealer_get_attachment_detail( $attachment_id ) {
		$attachment = get_post( $attachment_id );
		if ( empty( $attachment ) ) {
			return;
		}
		return array(
			'alt'         => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
			'caption'     => $attachment->post_excerpt,
			'description' => $attachment->post_content,
			'href'        => get_permalink( $attachment->ID ),
			'src'         => $attachment->guid,
			'title'       => $attachment->post_title,
		);
	}
}
if ( ! function_exists( 'cardealer_na_tag_cloud' ) ) {
	/**
	 * This function is used to remove size style from tags.
	 *
	 * @param string $string .
	 */
	function cardealer_na_tag_cloud( $string ) {
		return preg_replace( "/style='font-size:.+pt;'/", '', $string );
	}
	add_filter( 'wp_generate_tag_cloud', 'cardealer_na_tag_cloud', 10, 1 );
}
if ( ! function_exists( 'cardealer_site_opened_in_mobile' ) ) {
	/**
	 * Function for theme option to check SITE IS OPENED IN MOBILE OR NOT.
	 */
	function cardealer_site_opened_in_mobile() {
		$useragent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : ''; // the context is safe and reliable.
		if ( preg_match( '/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $useragent ) || preg_match( '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr( $useragent, 0, 4 ) ) ) {
			return true;
		} else {
			return false;
		}
	}
}
if ( ! function_exists( 'cardealer_comming_soon_newsletter' ) ) {
	/**
	 * Function to add NewsLetter on Comming Soon page.
	 */
	function cardealer_comming_soon_newsletter() {
		global $car_dealer_options;
		if ( cardealer_check_plugin_active( 'mailchimp-for-wp/mailchimp-for-wp.php' ) ) {
			if ( isset( $car_dealer_options['comming_page_newsletter_shortcode'] ) && ! empty( $car_dealer_options['comming_page_newsletter_shortcode'] ) ) {
				$mailchimp_id = $car_dealer_options['comming_page_newsletter_shortcode'];
			} else {
				return;
			}
			if ( ! empty( $car_dealer_options['newsletter_description'] ) ) {
				?>
				<div class="row text-center">
					<div class="col-lg-12 col-md-12">
						<p><?php echo do_shortcode( $car_dealer_options['newsletter_description'] ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></p>
					</div>
				</div>
				<?php
			}
			?>
			<div class="row gray-form no-gutter">
				<div class="col-sm-12">
					<form id="mc4wp-form-1" class="mc4wp-form mc4wp-form-<?php echo esc_attr( $mailchimp_id ); ?> mc4wp-form-submitted mc4wp-form-success" method="post" data-id="<?php echo esc_attr( $mailchimp_id ); ?>" data-name="Comming Soon Newsletter">
						<div class="col-md-offset-3 col-md-6 col-sm-offset-1 col-sm-10 col-xs-12">
						<div class="col-md-9 col-sm-8 col-xs-12 mc4wp-form-fields" style="padding:0; margin-bottom:5px;">
							<input name="EMAIL" placeholder="Your email address" required="" class="placeholder form-control" type="email">
							<div style="display: none;">
								<input name="_mc4wp_honeypot" value="" tabindex="-1" autocomplete="off" type="text">
							</div>
							<input name="_mc4wp_timestamp" value="<?php echo esc_html( time() ); ?>" type="hidden">
							<input name="_mc4wp_form_id" value="<?php echo esc_attr( $mailchimp_id ); ?>" type="hidden">
							<input name="_mc4wp_form_element_id" value="mc4wp-form-1" type="hidden">
						</div>
						<div class="col-md-3 col-sm-4 col-xs-12" style="padding:0;">
							<input class="button red btn-block" style="height:44px;" value="<?php esc_attr_e( 'Notify Me', 'cardealer' ); ?>" type="submit">
						</div></div>
						<div class="col-sm-12 text-center" style="padding:0;"><div class="mc4wp-response"><?php echo mc4wp_form_get_response_html( $mailchimp_id ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></div></div>
					</form>
				</div>
			</div>
			<?php
		}
	}
}
if ( ! function_exists( 'cardealer_helper_get_file_list' ) ) {
	/**
	 * Function for adding given file list
	 * It accepts two parameters
	 * extensions: mixed (either array or string - comma separated)
	 * NOTE : Use this instead of GLOB() ( As glob() is having PHP version issue )
	 *
	 * @param string $extensions .
	 * @param string $path .
	 */
	function cardealer_helper_get_file_list( $extensions = '', $path = '' ) {

		/* Return if any paramater is blank */
		if ( empty( $extensions ) || empty( $path ) ) {
			return false;
		}

		/* Convert to array if string is provided */
		if ( ! is_array( $extensions ) ) {
			$extensions = array_filter( explode( ',', $extensions ) );
		}

		/* Fix trailing slash if not provided. */
		$path = rtrim( $path, '/\\' ) . '/';

		if ( defined( 'GLOB_BRACE' ) ) {
			$extensions_with_glob_brace = '{' . implode( ',', $extensions ) . '}'; /* file extensions pattern */
			$files_with_glob            = glob( $path . "*.{$extensions_with_glob_brace}", GLOB_BRACE );

			return $files_with_glob;
		} else {
			$extensions_without_glob = implode( '|', $extensions ); /* file extensions pattern */

			/* Get all files */
			$files_without_glob_all = glob( $path . '*.*' );

			/* Filter files with pattern */
			$files_without_glob = array_values( preg_grep( "~\.($extensions_without_glob)$~", $files_without_glob_all ) );
			return $files_without_glob;
		}

		return $files;
	}
}
if ( ! function_exists( 'cardealer_get_current_post_id' ) ) {
	/**
	 * Cardealer get current post id
	 */
	function cardealer_get_current_post_id() {
		global $car_dealer_options;
		$post_id = get_the_ID();

		/* avoid confliction of same name between post type and page name */
		if ( is_archive() ) {
			$post_type = get_queried_object();
			/**
			 * Check for Vehicle category archieve page and return page id if page is set from theme options.
			 * Get post type from category archieve page.
			 */
			$is_cat_archive = false;
			if ( is_tax() ) {
				$tax_post_type = get_taxonomy( $post_type->taxonomy )->object_type;
				if ( ! is_wp_error( $tax_post_type ) && isset( $tax_post_type[0] ) && 'cars' === $tax_post_type[0] && isset( $car_dealer_options['cars_inventory_page'] ) && ! empty( $car_dealer_options['cars_inventory_page'] ) ) {
					return apply_filters( 'cardealer_get_current_page_post_id', $car_dealer_options['cars_inventory_page'] );
				}
			}

			/*
			Return if no WooCommerce or Vehicle listing page called.
			*/
			$inventory_slug = ( isset( $car_dealer_options['cars-details-slug'] ) && ! empty( $car_dealer_options['cars-details-slug'] ) ) ? $car_dealer_options['cars-details-slug'] : 'cars';
			if ( ! isset( $post_type->rewrite['slug'] ) || ( $post_type->rewrite['slug'] != $inventory_slug && 'product' !== $post_type->rewrite['slug'] ) || ( $post_type->rewrite['slug'] == $inventory_slug && empty( $car_dealer_options['cars_inventory_page'] ) ) ) {
				return apply_filters( 'cardealer_get_current_page_post_id', null );
			}

			$page = get_page_by_path( $post_type->has_archive ); // get slug.
			if ( isset( $page->ID ) ) {
				$post_id = $page->ID;
			}

			/* check for WPML */
			if ( cardealer_is_wpml_active() ) {
				$wpml_page = icl_object_id( get_page_by_path( $post_type->has_archive )->ID, 'page', true );
				if ( $wpml_page ) {
					$post_id = $wpml_page;
				}
			}
		}
		return apply_filters( 'cardealer_get_current_page_post_id', $post_id );
	}
}
if ( ! function_exists( 'cardealer_get_lat_lnt' ) ) {
	/**
	 * Cardealer getLatLnt
	 *
	 * @param string $address .
	 */
	function cardealer_get_lat_lnt( $address ) {
		global $car_dealer_options;
		$gapi             = isset( $car_dealer_options['google_maps_api'] ) ? $car_dealer_options['google_maps_api'] : '';
		$vehicle_location = rawurlencode( $address );
		$url              = esc_url( 'https://maps.googleapis.com/maps/api/geocode/json?address=' . rawurlencode( $vehicle_location ) . '&sensor=false&key=' . $gapi );

		$api_args = array( 'timeout' => 600 );
		$response = wp_remote_get( $url, $api_args );

		if ( ! is_wp_error( $response ) ) {
			$results = json_decode( $response['body'], true );
			if ( isset( $response['body'] ) && isset( $results['results'][0] ) ) {
				$lat  = $results['results'][0]['geometry']['location']['lat'];
				$long = $results['results'][0]['geometry']['location']['lng'];
			} else {
				$lat  = '';
				$long = '';
			}
		} else {
			$lat  = '';
			$long = '';
		}

		$data = array(
			'lat' => $lat,
			'lng' => $long,
		);

		if ( empty( $lat ) || empty( $long ) ) {
			$data['addr_found'] = '0';
		} else {
			$data['addr_found'] = '1';
		}
		return $data;
	}
}

if ( ! function_exists( 'cardealer_wp_body_classes' ) ) {
	/**
	 * Filter code to add options for Page Layout
	 *
	 * @param string $classes .
	 */
	function cardealer_wp_body_classes( $classes ) {
		global $car_dealer_options;
		if ( wp_is_mobile() ) {
			$classes[] = 'device-type-mobile';
		}

		$post_id = cardealer_get_current_post_id();

		$enable_custom_layout = get_post_meta( $post_id, 'enable_custom_layout', true );
		if ( 1 == $enable_custom_layout ) {
			$page_layout = get_post_meta( $post_id, 'page_layout', true );
			$classes[]   = 'site-layout-' . $page_layout;
		} else {
			if ( ! empty( $car_dealer_options['page_layout'] ) ) {
				$classes[] = 'site-layout-' . $car_dealer_options['page_layout'];
			}
		}
		return $classes;
	}
	add_filter( 'body_class', 'cardealer_wp_body_classes' );
}

if ( ! function_exists( 'cardealer_get_google_api_key' ) ) {
	/**
	 * Cardealer get google api key
	 */
	function cardealer_get_google_api_key() {
		global $car_dealer_options;
		if ( isset( $car_dealer_options['google_maps_api'] ) && ! empty( $car_dealer_options['google_maps_api'] ) ) {
			return $car_dealer_options['google_maps_api'];
		} else {
			return;
		}
	}
}
if ( ! function_exists( 'cardealer_acf_init' ) ) {
	/**
	 * ACF map Key
	 */
	function cardealer_acf_init() {

		$car_dealer_options = get_option( 'car_dealer_options' );
		$google_key         = ( isset( $car_dealer_options['google_maps_api'] ) ) ? $car_dealer_options['google_maps_api'] : '';
		if ( isset( $google_key ) && ! empty( $google_key ) ) {
			acf_update_setting( 'google_api_key', $google_key );
		}
	}
	add_action( 'acf/init', 'cardealer_acf_init' );
}
if ( ! function_exists( 'cardealer_reset_mega_menu' ) ) {
	/**
	 * Cardealer wp body classes
	 *
	 * @param string $args .
	 * @param string $menu_id .
	 * @param string $current_theme_location .
	 */
	function cardealer_reset_mega_menu( $args, $menu_id, $current_theme_location ) {

		/* Reset menu arguments */
		if ( isset( $_GET['disable-mega'] ) && 1 == $_GET['disable-mega'] ) {

			/* Reset Primary Menu */
			if ( 'primary-menu' === $current_theme_location ) {
				$args['theme_location']  = $current_theme_location;
				$args['container']       = 'ul';
				$args['container_id']    = 'menu-wrap-primary';
				$args['container_class'] = 'menu-wrap';
				$args['menu_id']         = 'primary-menu';
				$args['menu_class']      = 'menu-links';
				unset( $args['walker'] );

			}
		}

		return $args;
	}
	add_filter( 'megamenu_nav_menu_args', 'cardealer_reset_mega_menu', 10, 3 );
}
if ( ! function_exists( 'cardealer_custom_excerpt_length' ) ) {
	/**
	 * For excerpt data limit.
	 *
	 * @param string $length length of the excerpt.
	 */
	function cardealer_custom_excerpt_length( $length ) {
		global $post;
		if ( isset( $post->post_type ) && 'teams' === $post->post_type ) {
			return 15;
		} else {
			return $length;
		}

	}
	add_filter( 'excerpt_length', 'cardealer_custom_excerpt_length', 999 );
}
if ( ! function_exists( 'cardealer_add_analytics_script' ) ) {
	/**
	 * Add GA tracking code in site footer
	 */
	function cardealer_add_analytics_script() {

		if ( ! function_exists( 'get_field' ) ) {
			return;
		}

		$tracking_code = get_field( 'tracking_code', 'option' );
		if ( ! empty( $tracking_code ) ) {
			?>
			<?php echo wp_unslash( $tracking_code ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>
			<?php
		}
	}
	add_action( 'wp_footer', 'cardealer_add_analytics_script' );
}

if ( ! function_exists( 'cardealer_check_plugin_active' ) ) {
	/**
	 * Check plugin is active or not .
	 *
	 * @param string $plugin check string .
	 * @return Bool
	 */
	function cardealer_check_plugin_active( $plugin = '' ) {

		if ( empty( $plugin ) ) {
			return false;
		}

			return ( in_array( $plugin, (array) get_option( 'active_plugins', array() ) ) || ( function_exists( 'is_plugin_active_for_network' ) && is_plugin_active_for_network( $plugin ) ) );
	}
}

if ( ! function_exists( 'cardealer_check_plugin_installed' ) ) {
	/**
	 * Check plugin is active or not .
	 *
	 * @param string $plugin check string .
	 * @return Bool
	 */
	function cardealer_check_plugin_installed( $plugin = '' ) {
		$installed         = false;
		$installed_plugins = array();

		if ( ! empty( $plugin ) ) {
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			}

			if ( function_exists( 'get_plugins' ) ) {
				$installed_plugins = get_plugins();
			}

			$installed = ( ! empty( $installed_plugins[ $plugin ] ) );
		}

		$installed = apply_filters( 'cardealer_check_plugin_installed', $installed, $plugin );

		return $installed;
	}
}

if ( ! function_exists( 'cardealer_is_activated' ) ) {
	/**
	 * Cardealer check plugin active
	 */
	function cardealer_is_activated() {
		$purchase_token = get_option( 'cardealer_pgs_token' );
		if ( $purchase_token && ! empty( $purchase_token ) ) {
			return $purchase_token;
		}
		return false;
	}
}
if ( ! function_exists( 'cardealer_allowed_html' ) ) {
	/**
	 * Check plugin is active or not .
	 *
	 * @param string $allowed_els .
	 */
	function cardealer_allowed_html( $allowed_els = '' ) {
		/* bail early if parameter is empty */
		if ( empty( $allowed_els ) ) {
			return array();
		}

		if ( is_string( $allowed_els ) ) {
			$allowed_els = explode( ',', $allowed_els );
		}

		$allowed_html = array();
		$allowed_tags = wp_kses_allowed_html( 'post' );
		foreach ( $allowed_els as $el ) {
			$el = trim( $el );
			if ( array_key_exists( $el, $allowed_tags ) ) {
				$allowed_html[ $el ] = $allowed_tags[ $el ];
			}
		}
		return $allowed_html;
	}
}

if ( ! function_exists( 'cardealer_welcome_logo' ) ) {
	/**
	 * Cardealer welcome logo
	 */
	function cardealer_welcome_logo() {
		$welcome_logo      = CARDEALER_URL . '/images/admin/logo.png';
		$welcome_logo_path = CARDEALER_PATH . '/images/admin/logo.png';
		if ( file_exists( $welcome_logo_path ) && getimagesize( $welcome_logo ) != false ) {
			return $welcome_logo;
		} else {
			return false;
		}
	}
}

if ( ! function_exists( 'cardealer_get_multi_lang' ) ) {
	/**
	 * Cardealer get multi lang
	 */
	function cardealer_get_multi_lang() {
		global $car_dealer_options;

		/*Checl WPML sitepress multilingual plugin activate */
		if ( cardealer_is_wpml_active() && function_exists( 'icl_get_languages' ) ) {
			$languages = icl_get_languages();
			/* Display Current language */
			$lan_switcher_style = ( isset( $car_dealer_options['language-switcher-style'] ) && ! empty( $car_dealer_options['language-switcher-style'] ) ) ? $car_dealer_options['language-switcher-style'] : 'dropdown';
			$lan_item_style     = ( isset( $car_dealer_options['language-items-style'] ) && ! empty( $car_dealer_options['language-items-style'] ) ) ? $car_dealer_options['language-items-style'] : 'default';
			$label_style        = 'non-translated';
			if ( isset( $car_dealer_options['show-translated-label'] ) && 'true' === $car_dealer_options['show-translated-label'] ) {
				$label_style = 'translated';
			}

			if ( ! empty( $languages ) ) {
				?>
				<div class="language style-<?php echo esc_attr( $lan_switcher_style . ' ' . $label_style ); ?>" id="cardealer-lang-drop-down">
					<?php
					if ( 'horizontal' === $lan_switcher_style ) {
						?>
						<ul id="cardealer-lang-drop-content" class="drop-content">
							<?php
							foreach ( $languages as $l ) {
								?>
								<li>
									<?php
									if ( 1 == $l['active'] ) {
										?>
										<a href="javascript:void(0)" class="cardealer-current-lang active">
										<?php
									} else {
										?>
										<a href="<?php echo esc_url( $l['url'] ); ?>">
										<?php
									}
									if ( isset( $l['country_flag_url'] ) && ! empty( $l['country_flag_url'] ) && ( 'default' === $lan_item_style || 'flag_only' === $lan_item_style ) ) {
										?>
										<img src="<?php echo esc_url( $l['country_flag_url'] ); ?>" height="12" alt="<?php echo esc_attr( $l['language_code'] ); ?>" width="18" />
										<?php
									}
									if ( 'default' === $lan_item_style || 'label_only' === $lan_item_style ) {
										?>
										<span class="lang-label"><?php echo icl_disp_language( $l['native_name'], $l['translated_name'] ); ?></span>
										<?php
									}
									?>
									</a>
								</li>
								<?php
							}
							?>
						</ul>
						<?php
					} else {
						foreach ( $languages as $k => $al ) {
							if ( 1 == $al['active'] ) {
								?>
								<a href="javascript:void(0)" class="cardealer-current-lang" data-toggle="collapse" data-target="#cardealer-lang-drop-content">
									<?php
									if ( isset( $al['country_flag_url'] ) && ! empty( $al['country_flag_url'] ) && ( 'default' === $lan_item_style || 'flag_only' === $lan_item_style ) ) {
										?>
										<img src="<?php echo esc_url( $al['country_flag_url'] ); ?>" height="12" alt="<?php echo esc_attr( $al['language_code'] ); ?>" width="18" />
										<?php
									}

									if ( 'default' === $lan_item_style || 'label_only' === $lan_item_style ) {
										echo icl_disp_language( $al['native_name'], $al['translated_name'] );
									}
									?>
									&nbsp;<i class="fas fa-angle-down">&nbsp;</i>
								</a>
								<?php
								unset( $languages[ $k ] );
								break;
							}
						}
						?>
						<ul id="cardealer-lang-drop-content" class="drop-content collapse">
							<?php
							foreach ( $languages as $l ) {
								if ( ! $l['active'] ) {
									?>
									<li>
										<a href="<?php echo esc_url( $l['url'] ); ?>">
											<?php
											if ( isset( $l['country_flag_url'] ) && ! empty( $l['country_flag_url'] ) && ( 'default' === $lan_item_style || 'flag_only' === $lan_item_style ) ) {
												?>
												<img src="<?php echo esc_url( $l['country_flag_url'] ); ?>" height="12" alt="<?php echo esc_attr( $l['language_code'] ); ?>" width="18" />
												<?php
											}
											if ( 'default' === $lan_item_style || 'label_only' === $lan_item_style ) {
												?>
												<span class="lang-label"><?php echo icl_disp_language( $l['native_name'], $l['translated_name'] ); ?></span>
												<?php
											}
											?>
										</a>
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
			}
		}
	}
}
if ( ! function_exists( 'cardealer_lazyload_enabled' ) ) {
	/**
	 * Check lazyload enabled
	 */
	function cardealer_lazyload_enabled() {
		global $car_dealer_options;
		if ( isset( $car_dealer_options['enable_lazyload'] ) && $car_dealer_options['enable_lazyload'] && ! is_admin() ) {
			return true;
		} else {
			return false;
		}
	}
}

if ( ! function_exists( 'car_dealer_get_car_compare_ids' ) ) {
	/**
	 * Gets the car compare ids.
	 *
	 * @return mixed False or array of car ids for compare.
	 */
	function car_dealer_get_car_compare_ids() {

		$car_ids = false;

		if( isset( $_COOKIE['cars'] ) && ! empty( $_COOKIE['cars'] ) ) {
			$cars = sanitize_text_field( wp_unslash( $_COOKIE['cars'] ) );
			$cars = json_decode( $cars );
			if ( is_array( $cars ) && ! empty( $cars ) ) {
				$car_ids = $cars;
			}
		}

		return $car_ids;
	}
}

if ( ! function_exists( 'car_dealer_get_options_tab_number' ) ) {
	/**
	 * Gets redux options tab number by field_id.
	 *
	 * @return int|string
	 */
	function car_dealer_get_options_tab_number( $field_id = '' ) {
		$tab_number = '';

		if ( ! empty( $field_id ) && class_exists( 'Redux_Instances' ) ) {
			$redux_instance = Redux_Instances::get_instance( CARDEALER_THEME_OPTIONS_NAME );
			$tab_number = Redux_Helpers::tab_from_field( $redux_instance, $field_id );
		}

		return $tab_number;
	}
}

if ( ! function_exists( 'car_dealer_get_options_tab_url' ) ) {
	/**
	 * Gets redux options tab url by field_id.
	 *
	 * @return string
	 */
	function car_dealer_get_options_tab_url( $field_id = '' ) {
		$tab_url      = '';
		$tab_url_args = array(
			'page' => 'cardealer',
		);

		if ( ! empty( $field_id ) ) {
			$option_tab_number = car_dealer_get_options_tab_number( $field_id );
			if ( ! empty( $option_tab_number ) ) {
				$tab_url_args['tab'] = $option_tab_number;
			}
		}

		$tab_url = add_query_arg( $tab_url_args, admin_url( 'themes.php' ) );

		return $tab_url;
	}
}

if ( ! function_exists( 'cardealer_get_theme_option' ) ) {
	function cardealer_get_theme_option( $option_id, $fallback = '', $param = false ) {
		global $car_dealer_options;

		if ( empty( $car_dealer_options ) ) {
			$car_dealer_options = get_option( 'car_dealer_options' );
		}

		$output = ( isset( $car_dealer_options[ $option_id ] ) && $car_dealer_options[ $option_id ] !== '' ) ? $car_dealer_options[ $option_id ] : $fallback;

		if (
			( isset( $car_dealer_options[ $option_id ] ) && $car_dealer_options[ $option_id ] !== '' )
			&& is_array( $car_dealer_options[ $option_id ] )
			&& ! empty( $car_dealer_options[ $option_id ] )
			&& $param && isset( $car_dealer_options[ $option_id ][ $param ] )
		) {
			$output = $car_dealer_options[ $option_id ][ $param ];
		}

		return $output;
	}
}

if ( ! function_exists( 'cardealer_get_mileage_max' ) ) {
	function cardealer_get_mileage_max() {
		$mileage_max = 0;

		$mileages = get_terms( array(
			'taxonomy'   => 'car_mileage',
			'hide_empty' => true,
			'fields'     => 'names',
		) );

		if ( ! empty( $mileages ) && ! is_wp_error( $mileages ) ) {
			$mileages = array_filter( $mileages, 'is_numeric' );
			if ( ! empty( $mileages ) ) {
				$mileage_max = max( $mileages );
			}
		}

		return $mileage_max;
	}
}

if ( ! function_exists( 'cardealer_get_mileage_min' ) ) {
	function cardealer_get_mileage_min() {
		$mileage_min = 0;

		$mileages = get_terms( array(
			'taxonomy'   => 'car_mileage',
			'hide_empty' => false,
			'fields'     => 'names',
		) );

		if ( ! empty( $mileages ) && ! is_wp_error( $mileages ) ) {
			$mileages = array_filter( $mileages, 'is_numeric' );
			if ( ! empty( $mileages ) ) {
				$mileage_min = min( $mileages );
			}
		}

		return $mileage_min;
	}
}

if ( ! function_exists( 'cardealer_roundup_to_nearest_multiple' ) ) {
	function cardealer_roundup_to_nearest_multiple( $n, $increment = 1000 ){
		return (int) ( $increment * ceil( $n / $increment ) );
	}
}

function cardealer_is_wpbakery_active() {

	$wpbakery_active = class_exists( 'WPBakeryVisualComposerAbstract' );
	$wpbakery_active = apply_filters( 'cardealer_is_wpbakery_active', $wpbakery_active );

	return $wpbakery_active;
}

function cardealer_is_elementor_active() {

	$elementor_active = did_action( 'elementor/loaded' );
	$elementor_active = apply_filters( 'cardealer_is_elementor_active', $elementor_active );

	return $elementor_active;

}
