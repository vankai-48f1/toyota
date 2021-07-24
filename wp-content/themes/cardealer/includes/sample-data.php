<?php
/**
 * Sample data files
 *
 * @package cardealer
 */

add_filter( 'cdhl_theme_sample_datas', 'cardealer_sample_data_items' );
if ( ! function_exists( 'cardealer_sample_data_items' ) ) {
	/**
	 * Sample data function
	 *
	 * @see cardealer_sample_data_items()
	 *
	 * @param array $sample_data sample data.
	 */
	function cardealer_sample_data_items( $sample_data = array() ) {
		$sample_data_new = array(
			'default' => array(
				'id'          => 'default',
				'name'        => esc_html__( 'Default', 'cardealer' ),
				'demo_url'    => 'http://192.168.0.187/wordpressProjects/cardealer_theme/sample_data',
				'home_page'   => esc_html__( 'Home', 'cardealer' ),
				'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
				'message'     => esc_html__( 'Importing demo content will import Pages, Posts, Testimonials, Teams, FAQs, Menus, Widgets and Theme Options. Importing sample data will override current widgets and theme options. It can take some time to complete the import process.', 'cardealer' ),
				'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/',
				'menus'       => array(
					'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
					'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
					'top_menu'     => esc_html__( 'Top Bar Menu', 'cardealer' ),
				),
				'revsliders'  => array(
					'cardealer-slider-1.zip',
				),
			),
		);

		// check for imported demos.
		$imported_samples       = array();
		$sample_data_arr        = get_option( 'pgs_default_sample_data' );
		$default_demo_installed = false;
		if ( isset( $sample_data_arr ) && ! empty( $sample_data_arr ) ) {
			$imported_samples = json_decode( $sample_data_arr );
			// if default is imported, then only display other no default demos(sub demos).
			if ( in_array( 'default', $imported_samples, true ) ) {
				$default_demo_installed = true;
				$sub_demos              = array(
					'home-02'        => array(
						'id'          => 'home-02',
						'name'        => esc_html__( 'Home 2', 'cardealer' ),
						'demo_url'    => 'http://192.168.0.187/wordpressProjects/cardealer_theme/sample_data/home-2',
						'home_page'   => esc_html__( 'Home 2', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( 'This sample will import Home Page 2 contents only', 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/home-2/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(
							'cardealer-slider-2.zip',
						),
					),
					'home-03'        => array(
						'id'          => 'home-03',
						'name'        => esc_html__( 'Home 3', 'cardealer' ),
						'demo_url'    => 'http://192.168.0.187/wordpressProjects/cardealer_theme/sample_data/home-3',
						'home_page'   => esc_html__( 'Home 3', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( 'This sample will import Home Page 3 contents only', 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/home-3/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(
							'cardealer-slider-3.zip',
						),
					),
					'home-04'        => array(
						'id'          => 'home-04',
						'name'        => esc_html__( 'Home 4', 'cardealer' ),
						'demo_url'    => 'http://192.168.0.187/wordpressProjects/cardealer_theme/sample_data/home-4',
						'home_page'   => esc_html__( 'Home 4', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( 'This sample will import Home Page 4 contents only', 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/home-4/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(
							'cardealer-slider-4.zip',
						),
					),
					'home-05'        => array(
						'id'          => 'home-05',
						'name'        => esc_html__( 'Home 5', 'cardealer' ),
						'demo_url'    => 'http://192.168.0.187/wordpressProjects/cardealer_theme/sample_data/home-5',
						'home_page'   => esc_html__( 'Home 5', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( 'This sample will import Home Page 5 contents only', 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/home-5/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(
							'cardealer-slider-5.zip',
						),
					),
					'home-06'        => array(
						'id'          => 'home-06',
						'name'        => esc_html__( 'Home 6', 'cardealer' ),
						'demo_url'    => 'http://192.168.0.187/wordpressProjects/cardealer_theme/sample_data/home-6',
						'home_page'   => esc_html__( 'Home 6', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( 'This sample will import Home Page 6 contents only', 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/home-6/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(
							'cardealer-slider-6.zip',
						),
					),
					'home-07'        => array(
						'id'          => 'home-07',
						'name'        => esc_html__( 'Home 7', 'cardealer' ),
						'demo_url'    => 'http://192.168.0.187/wordpressProjects/cardealer_theme/sample_data/home-7',
						'home_page'   => esc_html__( 'Home 7', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( 'This sample will import Home Page 7 contents only', 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/home-7/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(
							'cardealer-slider-7.zip',
						),
					),
					'home-08'        => array(
						'id'          => 'home-08',
						'name'        => esc_html__( 'Home 8', 'cardealer' ),
						'demo_url'    => 'http://192.168.0.187/wordpressProjects/cardealer_theme/sample_data/home-8',
						'home_page'   => esc_html__( 'Home 8', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( 'This sample will import Home Page 8 contents only', 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/home-8/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(
							'cardealer-slider-8.zip',
						),
					),
					'home-09'        => array(
						'id'          => 'home-09',
						'name'        => esc_html__( 'Home 9', 'cardealer' ),
						'demo_url'    => 'http://192.168.0.187/wordpressProjects/cardealer_theme/sample_data/home-9',
						'home_page'   => esc_html__( 'Home 9', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( 'This sample will import Home Page 9 contents only', 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/home-9/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(
							'cardealer-slider-9.zip',
						),
					),
					'home-10'        => array(
						'id'          => 'home-10',
						'name'        => esc_html__( 'Home 10', 'cardealer' ),
						'demo_url'    => 'http://192.168.0.187/wordpressProjects/cardealer_theme/sample_data/home-10',
						'home_page'   => esc_html__( 'Home 10', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( 'This sample will import Home Page 10 contents only', 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/home-10/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(
							'cardealer-slider-10.zip',
						),
					),
					'home-11'        => array(
						'id'          => 'home-11',
						'name'        => esc_html__( 'Home Directory', 'cardealer' ),
						'demo_url'    => 'http://192.168.0.187/wordpressProjects/cardealer_theme/sample_data/home-11',
						'home_page'   => esc_html__( 'Home Directory', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( 'This sample will import Home 11 page contents only', 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/home-11/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(),
					),
					'home-12'        => array(
						'id'          => 'home-12',
						'name'        => esc_html__( 'Car Landing', 'cardealer' ),
						'demo_url'    => 'http://192.168.0.187/wordpressProjects/cardealer_theme/sample_data/home-12',
						'home_page'   => esc_html__( 'Car Landing', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( 'This sample will import Home Page 12 contents only', 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/home-12/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(),
					),
					'home-inventory' => array(
						'id'          => 'home-inventory',
						'name'        => esc_html__( 'Home - Inventory', 'cardealer' ),
						'demo_url'    => 'http://192.168.0.187/wordpressProjects/cardealer_theme/sample_data/home-inventory',
						'home_page'   => esc_html__( 'Home - Inventory', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( 'This sample will import Home - Inventory page contents only', 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/cars/?layout-style=lazyload',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(),
					),
				);
				$sample_data_new        = array_merge( $sample_data_new, $sub_demos );
			}
		}

		// add service demo.
		if ( ! $default_demo_installed ) {
			$services_sample_data = array(
				'services' => array(
					'id'          => 'services',
					'name'        => esc_html__( 'Services', 'cardealer' ),
					'demo_url'    => 'http://192.168.0.154/cardealer/services/',
					'home_page'   => esc_html__( 'Home', 'cardealer' ),
					'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
					'message'     => esc_html__( 'Importing demo content will import Pages, Posts, Testimonials, Teams, Menus, Widgets and Theme Options. Importing sample data will override current widgets and theme options. It can take some time to complete the import process.', 'cardealer' ),
					'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/service/',
					'menus'       => array(
						'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
						'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
					),
					'revsliders'  => array(
						'car-service.zip',
					),
				),
			);
			$sample_data_new      = array_merge( $sample_data_new, $services_sample_data );
		}

		// $sample_data_new.
		array_walk( $sample_data_new, 'cardealer_old_sample_data_fix' );

		$sample_data = array_merge( $sample_data, $sample_data_new );

		return $sample_data;
	}
}

if ( ! function_exists( 'cardealer_old_sample_data_fix' ) ) {
	/**
	 * Old Sample data function
	 *
	 * @see cardealer_old_sample_data_fix()
	 *
	 * @param array  $item1 Item variable.
	 * @param string $key store key.
	 */
	function cardealer_old_sample_data_fix( &$item1, $key ) {
		$sample_data_path  = get_parent_theme_file_path( 'includes/sample_data' );
		$sample_data_url   = get_parent_theme_file_uri( 'includes/sample_data' );
		$item1['data_dir'] = trailingslashit( trailingslashit( $sample_data_path ) . $key );
		$item1['data_url'] = trailingslashit( trailingslashit( $sample_data_url ) . $key );
	}
}
