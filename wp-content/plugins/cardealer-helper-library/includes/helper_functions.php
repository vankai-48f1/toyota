<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Theme helper functions.
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper/functions
 * @version 1.0.0
 */

if ( ! function_exists( 'cdhl_banner_images' ) ) {
	/**
	 * Ger banner images.
	 *
	 * @param bool $image_path image path.
	 */
	function cdhl_banner_images( $image_path = true ) {
		$cdhl_banners_path = trailingslashit( CDHL_PATH ) . 'images/bg/';
		$cdhl_banners_url  = trailingslashit( CDHL_URL ) . 'images/bg/';
		$cdhl_banners_new  = array();

		if ( is_dir( $cdhl_banners_path ) ) {
			$cdhl_banners_data = cdhl_pgscore_get_file_list( 'jpg,png', $cdhl_banners_path );
			if ( ! empty( $cdhl_banners_data ) ) {
				foreach ( $cdhl_banners_data as $cdhl_banner_path ) {
					if ( $image_path ) {
						$cdhl_banners_new[ $cdhl_banners_url . basename( $cdhl_banner_path ) ] = array(
							'alt'    => basename( $cdhl_banner_path ),
							'img'    => $cdhl_banners_url . basename( $cdhl_banner_path ),
							'height' => 25,
							'width'  => 100,
						);
					} else {
						$cdhl_banners_new[] = array(
							'alt'    => basename( $cdhl_banner_path ),
							'img'    => $cdhl_banners_url . basename( $cdhl_banner_path ),
							'height' => 25,
							'width'  => 100,
						);
					}
				}
			}
		}
		if ( ! $image_path ) {
			array_unshift( $cdhl_banners_new, null );
			unset( $cdhl_banners_new[0] );
		}
		return $cdhl_banners_new;
	}
}

if ( ! function_exists( 'cdhl_banner_images_default' ) ) {
	/**
	 * Get banner default image.
	 */
	function cdhl_banner_images_default() {
		$imgs = cdhl_banner_images();
		foreach ( $imgs as $img => $img_data ) {
			return $img;
		}
	}
}

if ( ! function_exists( 'cdhl_is_plugin_installed' ) ) {
	/**
	 * Check is plugin installed.
	 *
	 * @param string $search plugin search variable.
	 */
	function cdhl_is_plugin_installed( $search ) {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$plugins      = get_plugins();
		$plugins      = array_filter(
			array_keys( $plugins ),
			function( $k ) {
				if ( strpos( $k, '/' ) !== false ) {
					return true;
				}
			}
		);
		$plugins_stat = function( $plugins, $search ) {
			$new_plugins = array();
			foreach ( $plugins as $plugin ) {
				$new_plugins_data = explode( '/', $plugin );
				$new_plugins[]    = $new_plugins_data[0];
			}
			return in_array( $search, $new_plugins );
		};
		return $plugins_stat( $plugins, $search );
	}
}

if ( ! function_exists( 'cdhl_plugin_active_status' ) ) {
	/**
	 * Check is plugin active.
	 *
	 * @param array $plugin it stores plugin.
	 */
	function cdhl_plugin_active_status( $plugin ) {
		if ( empty( $plugin ) ) {
			return false;
		}
		return in_array( $plugin, (array) get_option( 'active_plugins', array() ) ) || ( function_exists( 'is_plugin_active_for_network' ) && is_plugin_active_for_network( $plugin ) );
	}
}

add_shortcode( 'wp_caption', 'cdhl_fixed_img_caption_shortcode' );
add_shortcode( 'caption', 'cdhl_fixed_img_caption_shortcode' );

if ( ! function_exists( 'cdhl_fixed_img_caption_shortcode' ) ) {
	/**
	 * Filter out hard-coded width, height attributes on all captions (wp-caption class).
	 *
	 * @param array $attr attrinutes.
	 * @param bool  $content content.
	 */
	function cdhl_fixed_img_caption_shortcode( $attr, $content = null ) {
		if ( ! isset( $attr['caption'] ) ) {
			if ( preg_match( '#((?:<a [^>]+>\s*)?<img [^>]+>(?:\s*</a>)?)(.*)#is', $content, $matches ) ) {
				$content         = $matches[1];
				$attr['caption'] = trim( $matches[2] );
			}
		}
		$output = apply_filters( 'img_caption_shortcode', '', $attr, $content );
		if ( ! empty( $output ) ) {
			return $output;
		}
		extract(
			shortcode_atts(
				array(
					'id'      => '',
					'align'   => 'alignnone',
					'width'   => '',
					'caption' => '',
				),
				$attr
			)
		);
		if ( 1 > (int) $width || empty( $caption ) ) {
			return $content;
		}
		if ( $id ) {
			$id = 'id="' . esc_attr( $id ) . '" ';
		}
		return '<div ' . $id . 'class="wp-caption ' . esc_attr( $align ) . '" >'
		. do_shortcode( $content ) . '<p class="wp-caption-text">' . $caption . '</p></div>';
	}
}

if ( ! function_exists( 'cdhl_fix_img_caption_shortcode' ) ) {
	/**
	 * Filter out hard-coded width, height attributes on all captions (wp-caption class)
	 *
	 * @param array  $attr attrinutes.
	 * @param string $content content.
	 * @since Car Dealer 1.0
	 */
	function cdhl_fix_img_caption_shortcode( $attr, $content = null ) {
		if ( ! isset( $attr['caption'] ) ) {
			if ( preg_match( '#((?:<a [^>]+>\s*)?<img [^>]+>(?:\s*</a>)?)(.*)#is', $content, $matches ) ) {
				$content         = $matches[1];
				$attr['caption'] = trim( $matches[2] );
			}
		}
		$output = apply_filters( 'img_caption_shortcode', '', $attr, $content );
		if ( ! empty( $output ) ) {
			return $output;
		}
		extract(
			shortcode_atts(
				array(
					'id'      => '',
					'align'   => 'alignnone',
					'width'   => '',
					'caption' => '',
				),
				$attr
			)
		);
		if ( 1 > (int) $width || empty( $caption ) ) {
			return $content;
		}
		if ( $id ) {
			$id = 'id="' . esc_attr( $id ) . '" ';
		}
		return '<figure ' . $id . 'class="wp-caption ' . esc_attr( $align ) . '" >' . do_shortcode( $content ) . '<figcaption class="wp-caption-text">' . $caption . '</figcaption></figure>';
	}
}
add_shortcode( 'wp_caption', 'cdhl_fix_img_caption_shortcode' );
add_shortcode( 'caption', 'cdhl_fix_img_caption_shortcode' );

if ( ! function_exists( 'cdhl_get_excerpt_max_charlength' ) ) {
	/**
	 * Get the excerpt max char length.
	 *
	 * @param int  $charlength length variable.
	 * @param bool $excerpt excerpt variable.
	 */
	function cdhl_get_excerpt_max_charlength( $charlength, $excerpt = null ) {
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

if ( ! function_exists( 'cdhl_the_excerpt_max_charlength' ) ) {
	/**
	 * Get the excerpt max char length.
	 *
	 * @param int    $charlength length variable.
	 * @param string $excerpt excerpt variable.
	 */
	function cdhl_the_excerpt_max_charlength( $charlength, $excerpt = null ) {
		$new_excerpt = cdhl_get_excerpt_max_charlength( $charlength, $excerpt );
		echo esc_html( $new_excerpt );
	}
}

if ( ! function_exists( 'cdhl_shortenString' ) ) {
	/**
	 * Truncate String with or without ellipsis.
	 *
	 * @param string $string variable.
	 * @param int    $max_length variable.
	 * @param bool   $add_ellipsis variable.
	 * @param bool   $wordsafe variable.
	 * @return string Shotened Text
	 */
	function cdhl_shortenString( $string, $max_length, $add_ellipsis = true, $wordsafe = false ) {

		if ( empty( $string ) ) {
			$string = 120;
		}

		$ellipsis   = '';
		$max_length = max( $max_length, 0 );
		if ( mb_strlen( $string ) <= $max_length ) {
			return $string;
		}
		if ( $add_ellipsis ) {
			$ellipsis    = mb_substr( '...', 0, $max_length );
			$max_length -= mb_strlen( $ellipsis );
			$max_length  = max( $max_length, 0 );
		}
		if ( $wordsafe ) {
			$string = preg_replace( '/\s+?(\S+)?$/', '', mb_substr( $string, 0, $max_length ) );
		} else {
			$string = mb_substr( $string, 0, $max_length );
		}
		if ( $add_ellipsis ) {
			$string .= $ellipsis;
		}

		return $string;
	}
}

if ( ! function_exists( 'cdhl_array_sort_by_column' ) ) {
	/**
	 * Car array short by column.
	 *
	 * @param array  $array variable.
	 * @param int    $column variable.
	 * @param string $direction variable.
	 */
	function cdhl_array_sort_by_column( &$array, $column, $direction = SORT_ASC ) {
		$reference_array = array();
		foreach ( $array as $key => $row ) {
			if ( isset( $row[ $column ] ) ) {
				$reference_array[ $key ] = $row[ $column ];
			}
		}
		if ( count( $reference_array ) === count( $array ) ) {
			array_multisort( $reference_array, $array, $direction );
		}
	}
}

if ( ! function_exists( 'cdhl_pgscore_get_file_list' ) ) {
	/**
	 * Get list of files in directory.
	 *
	 * @param string $extensions variable.
	 * @param string $path variable.
	 */
	function cdhl_pgscore_get_file_list( $extensions = '', $path = '' ) {

		// Return if any paramater is blank.
		if ( empty( $extensions ) || empty( $path ) ) {
			return false;
		}

		// Convert to array if string is provided.
		if ( ! is_array( $extensions ) ) {
			$extensions = array_filter( explode( ',', $extensions ) );
		}

		// Fix trailing slash if not provided.
		$path = rtrim( $path, '/\\' ) . '/';

		if ( defined( 'GLOB_BRACE' ) ) {
			if ( ( 1 === count( $extensions ) || '1' === count( $extensions ) ) && '*' === $extensions[0] ) {
				$files_with_glob = glob( $path . '*', GLOB_BRACE );
			} else {
				$extensions_with_glob_brace = '{' . implode( ',', $extensions ) . '}'; // file extensions pattern.
				$files_with_glob            = glob( $path . "*.$extensions_with_glob_brace", GLOB_BRACE );
			}

			return $files_with_glob;
		} else {
			if ( ( 1 === count( $extensions ) || '1' === count( $extensions ) ) && '*' === $extensions[0] ) {
				$files_without_glob = glob( $path . '*' );
			} else {
				$extensions_without_glob = implode( '|', $extensions ); // file extensions pattern.

				$files_without_glob_all = glob( $path . '*.*' ); // Get all files.

				$files_without_glob = array_values( preg_grep( "~\.($extensions_without_glob)$~", $files_without_glob_all ) ); // Filter files with pattern.
			}
			return $files_without_glob;
		}

		return $files;
	}
}

if ( ! function_exists( 'cdhl_check_promocode_exist' ) ) {
	/**
	 * Function to check promocode available or not.
	 */
	function cdhl_check_promocode_exist() {
		$args  = array(
			'post_type'   => 'cars_promocodes',
			'post_status' => 'publish',
		);
		$query = new WP_Query( $args );
		if ( count( $query->posts ) ) {
			return true;
		}
		return false;
	}
}

if ( ! function_exists( 'cdhl_currencie_list' ) ) {
	/**
	 * Get currency list.
	 */
	function cdhl_currencie_list() {
		return array_unique(
			apply_filters(
				'cdhl_currencie_list',
				array(
					'AED' => esc_html__( 'United Arab Emirates dirham', 'cardealer-helper' ),
					'AFN' => esc_html__( 'Afghan afghani', 'cardealer-helper' ),
					'ALL' => esc_html__( 'Albanian lek', 'cardealer-helper' ),
					'AMD' => esc_html__( 'Armenian dram', 'cardealer-helper' ),
					'ANG' => esc_html__( 'Netherlands Antillean guilder', 'cardealer-helper' ),
					'AOA' => esc_html__( 'Angolan kwanza', 'cardealer-helper' ),
					'ARS' => esc_html__( 'Argentine peso', 'cardealer-helper' ),
					'AUD' => esc_html__( 'Australian dollar', 'cardealer-helper' ),
					'AWG' => esc_html__( 'Aruban florin', 'cardealer-helper' ),
					'AZN' => esc_html__( 'Azerbaijani manat', 'cardealer-helper' ),
					'BAM' => esc_html__( 'Bosnia and Herzegovina convertible mark', 'cardealer-helper' ),
					'BBD' => esc_html__( 'Barbadian dollar', 'cardealer-helper' ),
					'BDT' => esc_html__( 'Bangladeshi taka', 'cardealer-helper' ),
					'BGN' => esc_html__( 'Bulgarian lev', 'cardealer-helper' ),
					'BHD' => esc_html__( 'Bahraini dinar', 'cardealer-helper' ),
					'BIF' => esc_html__( 'Burundian franc', 'cardealer-helper' ),
					'BMD' => esc_html__( 'Bermudian dollar', 'cardealer-helper' ),
					'BND' => esc_html__( 'Brunei dollar', 'cardealer-helper' ),
					'BOB' => esc_html__( 'Bolivian boliviano', 'cardealer-helper' ),
					'BRL' => esc_html__( 'Brazilian real', 'cardealer-helper' ),
					'BSD' => esc_html__( 'Bahamian dollar', 'cardealer-helper' ),
					'BTC' => esc_html__( 'Bitcoin', 'cardealer-helper' ),
					'BTN' => esc_html__( 'Bhutanese ngultrum', 'cardealer-helper' ),
					'BWP' => esc_html__( 'Botswana pula', 'cardealer-helper' ),
					'BYR' => esc_html__( 'Belarusian ruble', 'cardealer-helper' ),
					'BZD' => esc_html__( 'Belize dollar', 'cardealer-helper' ),
					'CAD' => esc_html__( 'Canadian dollar', 'cardealer-helper' ),
					'CDF' => esc_html__( 'Congolese franc', 'cardealer-helper' ),
					'CHF' => esc_html__( 'Swiss franc', 'cardealer-helper' ),
					'CLP' => esc_html__( 'Chilean peso', 'cardealer-helper' ),
					'CNY' => esc_html__( 'Chinese yuan', 'cardealer-helper' ),
					'COP' => esc_html__( 'Colombian peso', 'cardealer-helper' ),
					'CRC' => esc_html__( 'Costa Rican col&oacute;n', 'cardealer-helper' ),
					'CUC' => esc_html__( 'Cuban convertible peso', 'cardealer-helper' ),
					'CUP' => esc_html__( 'Cuban peso', 'cardealer-helper' ),
					'CVE' => esc_html__( 'Cape Verdean escudo', 'cardealer-helper' ),
					'CZK' => esc_html__( 'Czech koruna', 'cardealer-helper' ),
					'DJF' => esc_html__( 'Djiboutian franc', 'cardealer-helper' ),
					'DKK' => esc_html__( 'Danish krone', 'cardealer-helper' ),
					'DOP' => esc_html__( 'Dominican peso', 'cardealer-helper' ),
					'DZD' => esc_html__( 'Algerian dinar', 'cardealer-helper' ),
					'EGP' => esc_html__( 'Egyptian pound', 'cardealer-helper' ),
					'ERN' => esc_html__( 'Eritrean nakfa', 'cardealer-helper' ),
					'ETB' => esc_html__( 'Ethiopian birr', 'cardealer-helper' ),
					'EUR' => esc_html__( 'Euro', 'cardealer-helper' ),
					'FJD' => esc_html__( 'Fijian dollar', 'cardealer-helper' ),
					'FKP' => esc_html__( 'Falkland Islands pound', 'cardealer-helper' ),
					'GBP' => esc_html__( 'Pound sterling', 'cardealer-helper' ),
					'GEL' => esc_html__( 'Georgian lari', 'cardealer-helper' ),
					'GGP' => esc_html__( 'Guernsey pound', 'cardealer-helper' ),
					'GHS' => esc_html__( 'Ghana cedi', 'cardealer-helper' ),
					'GIP' => esc_html__( 'Gibraltar pound', 'cardealer-helper' ),
					'GMD' => esc_html__( 'Gambian dalasi', 'cardealer-helper' ),
					'GNF' => esc_html__( 'Guinean franc', 'cardealer-helper' ),
					'GTQ' => esc_html__( 'Guatemalan quetzal', 'cardealer-helper' ),
					'GYD' => esc_html__( 'Guyanese dollar', 'cardealer-helper' ),
					'HKD' => esc_html__( 'Hong Kong dollar', 'cardealer-helper' ),
					'HNL' => esc_html__( 'Honduran lempira', 'cardealer-helper' ),
					'HRK' => esc_html__( 'Croatian kuna', 'cardealer-helper' ),
					'HTG' => esc_html__( 'Haitian gourde', 'cardealer-helper' ),
					'HUF' => esc_html__( 'Hungarian forint', 'cardealer-helper' ),
					'IDR' => esc_html__( 'Indonesian rupiah', 'cardealer-helper' ),
					'ILS' => esc_html__( 'Israeli new shekel', 'cardealer-helper' ),
					'IMP' => esc_html__( 'Manx pound', 'cardealer-helper' ),
					'INR' => esc_html__( 'Indian rupee', 'cardealer-helper' ),
					'IQD' => esc_html__( 'Iraqi dinar', 'cardealer-helper' ),
					'IRR' => esc_html__( 'Iranian rial', 'cardealer-helper' ),
					'IRT' => esc_html__( 'Iranian toman', 'cardealer-helper' ),
					'ISK' => esc_html__( 'Icelandic kr&oacute;na', 'cardealer-helper' ),
					'JEP' => esc_html__( 'Jersey pound', 'cardealer-helper' ),
					'JMD' => esc_html__( 'Jamaican dollar', 'cardealer-helper' ),
					'JOD' => esc_html__( 'Jordanian dinar', 'cardealer-helper' ),
					'JPY' => esc_html__( 'Japanese yen', 'cardealer-helper' ),
					'KES' => esc_html__( 'Kenyan shilling', 'cardealer-helper' ),
					'KGS' => esc_html__( 'Kyrgyzstani som', 'cardealer-helper' ),
					'KHR' => esc_html__( 'Cambodian riel', 'cardealer-helper' ),
					'KMF' => esc_html__( 'Comorian franc', 'cardealer-helper' ),
					'KPW' => esc_html__( 'North Korean won', 'cardealer-helper' ),
					'KRW' => esc_html__( 'South Korean won', 'cardealer-helper' ),
					'KWD' => esc_html__( 'Kuwaiti dinar', 'cardealer-helper' ),
					'KYD' => esc_html__( 'Cayman Islands dollar', 'cardealer-helper' ),
					'KZT' => esc_html__( 'Kazakhstani tenge', 'cardealer-helper' ),
					'LAK' => esc_html__( 'Lao kip', 'cardealer-helper' ),
					'LBP' => esc_html__( 'Lebanese pound', 'cardealer-helper' ),
					'LKR' => esc_html__( 'Sri Lankan rupee', 'cardealer-helper' ),
					'LRD' => esc_html__( 'Liberian dollar', 'cardealer-helper' ),
					'LSL' => esc_html__( 'Lesotho loti', 'cardealer-helper' ),
					'LYD' => esc_html__( 'Libyan dinar', 'cardealer-helper' ),
					'MAD' => esc_html__( 'Moroccan dirham', 'cardealer-helper' ),
					'MDL' => esc_html__( 'Moldovan leu', 'cardealer-helper' ),
					'MGA' => esc_html__( 'Malagasy ariary', 'cardealer-helper' ),
					'MKD' => esc_html__( 'Macedonian denar', 'cardealer-helper' ),
					'MMK' => esc_html__( 'Burmese kyat', 'cardealer-helper' ),
					'MNT' => esc_html__( 'Mongolian t&ouml;gr&ouml;g', 'cardealer-helper' ),
					'MOP' => esc_html__( 'Macanese pataca', 'cardealer-helper' ),
					'MRO' => esc_html__( 'Mauritanian ouguiya', 'cardealer-helper' ),
					'MUR' => esc_html__( 'Mauritian rupee', 'cardealer-helper' ),
					'MVR' => esc_html__( 'Maldivian rufiyaa', 'cardealer-helper' ),
					'MWK' => esc_html__( 'Malawian kwacha', 'cardealer-helper' ),
					'MXN' => esc_html__( 'Mexican peso', 'cardealer-helper' ),
					'MYR' => esc_html__( 'Malaysian ringgit', 'cardealer-helper' ),
					'MZN' => esc_html__( 'Mozambican metical', 'cardealer-helper' ),
					'NAD' => esc_html__( 'Namibian dollar', 'cardealer-helper' ),
					'NGN' => esc_html__( 'Nigerian naira', 'cardealer-helper' ),
					'NIO' => esc_html__( 'Nicaraguan c&oacute;rdoba', 'cardealer-helper' ),
					'NOK' => esc_html__( 'Norwegian krone', 'cardealer-helper' ),
					'NPR' => esc_html__( 'Nepalese rupee', 'cardealer-helper' ),
					'NZD' => esc_html__( 'New Zealand dollar', 'cardealer-helper' ),
					'OMR' => esc_html__( 'Omani rial', 'cardealer-helper' ),
					'PAB' => esc_html__( 'Panamanian balboa', 'cardealer-helper' ),
					'PEN' => esc_html__( 'Peruvian nuevo sol', 'cardealer-helper' ),
					'PGK' => esc_html__( 'Papua New Guinean kina', 'cardealer-helper' ),
					'PHP' => esc_html__( 'Philippine peso', 'cardealer-helper' ),
					'PKR' => esc_html__( 'Pakistani rupee', 'cardealer-helper' ),
					'PLN' => esc_html__( 'Polish z&#x142;oty', 'cardealer-helper' ),
					'PRB' => esc_html__( 'Transnistrian ruble', 'cardealer-helper' ),
					'PYG' => esc_html__( 'Paraguayan guaran&iacute;', 'cardealer-helper' ),
					'QAR' => esc_html__( 'Qatari riyal', 'cardealer-helper' ),
					'RON' => esc_html__( 'Romanian leu', 'cardealer-helper' ),
					'RSD' => esc_html__( 'Serbian dinar', 'cardealer-helper' ),
					'RUB' => esc_html__( 'Russian ruble', 'cardealer-helper' ),
					'RWF' => esc_html__( 'Rwandan franc', 'cardealer-helper' ),
					'SAR' => esc_html__( 'Saudi riyal', 'cardealer-helper' ),
					'SBD' => esc_html__( 'Solomon Islands dollar', 'cardealer-helper' ),
					'SCR' => esc_html__( 'Seychellois rupee', 'cardealer-helper' ),
					'SDG' => esc_html__( 'Sudanese pound', 'cardealer-helper' ),
					'SEK' => esc_html__( 'Swedish krona', 'cardealer-helper' ),
					'SGD' => esc_html__( 'Singapore dollar', 'cardealer-helper' ),
					'SHP' => esc_html__( 'Saint Helena pound', 'cardealer-helper' ),
					'SLL' => esc_html__( 'Sierra Leonean leone', 'cardealer-helper' ),
					'SOS' => esc_html__( 'Somali shilling', 'cardealer-helper' ),
					'SRD' => esc_html__( 'Surinamese dollar', 'cardealer-helper' ),
					'SSP' => esc_html__( 'South Sudanese pound', 'cardealer-helper' ),
					'STD' => esc_html__( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra', 'cardealer-helper' ),
					'SYP' => esc_html__( 'Syrian pound', 'cardealer-helper' ),
					'SZL' => esc_html__( 'Swazi lilangeni', 'cardealer-helper' ),
					'THB' => esc_html__( 'Thai baht', 'cardealer-helper' ),
					'TJS' => esc_html__( 'Tajikistani somoni', 'cardealer-helper' ),
					'TMT' => esc_html__( 'Turkmenistan manat', 'cardealer-helper' ),
					'TND' => esc_html__( 'Tunisian dinar', 'cardealer-helper' ),
					'TOP' => esc_html__( 'Tongan pa&#x2bb;anga', 'cardealer-helper' ),
					'TRY' => esc_html__( 'Turkish lira', 'cardealer-helper' ),
					'TTD' => esc_html__( 'Trinidad and Tobago dollar', 'cardealer-helper' ),
					'TWD' => esc_html__( 'New Taiwan dollar', 'cardealer-helper' ),
					'TZS' => esc_html__( 'Tanzanian shilling', 'cardealer-helper' ),
					'UAH' => esc_html__( 'Ukrainian hryvnia', 'cardealer-helper' ),
					'UGX' => esc_html__( 'Ugandan shilling', 'cardealer-helper' ),
					'USD' => esc_html__( 'United States dollar', 'cardealer-helper' ),
					'UYU' => esc_html__( 'Uruguayan peso', 'cardealer-helper' ),
					'UZS' => esc_html__( 'Uzbekistani som', 'cardealer-helper' ),
					'VEF' => esc_html__( 'Venezuelan bol&iacute;var', 'cardealer-helper' ),
					'VND' => esc_html__( 'Vietnamese &#x111;&#x1ed3;ng', 'cardealer-helper' ),
					'VUV' => esc_html__( 'Vanuatu vatu', 'cardealer-helper' ),
					'WST' => esc_html__( 'Samoan t&#x101;l&#x101;', 'cardealer-helper' ),
					'XAF' => esc_html__( 'Central African CFA franc', 'cardealer-helper' ),
					'XCD' => esc_html__( 'East Caribbean dollar', 'cardealer-helper' ),
					'XOF' => esc_html__( 'West African CFA franc', 'cardealer-helper' ),
					'XPF' => esc_html__( 'CFP franc', 'cardealer-helper' ),
					'YER' => esc_html__( 'Yemeni rial', 'cardealer-helper' ),
					'ZAR' => esc_html__( 'South African rand', 'cardealer-helper' ),
					'ZMW' => esc_html__( 'Zambian kwacha', 'cardealer-helper' ),
				)
			)
		);
	}
}

if ( ! function_exists( 'cdhl_get_currency_symbols' ) ) {
	/**
	 * Get currency symbols.
	 *
	 * @param array $currency_code variable.
	 */
	function cdhl_get_currency_symbols( $currency_code ) {
		$currency_symbols = array(
			'AED' => '&#x62f;.&#x625;',
			'AFN' => '&#x60b;',
			'ALL' => 'L',
			'AMD' => 'AMD',
			'ANG' => '&fnof;',
			'AOA' => 'Kz',
			'ARS' => '&#36;',
			'AUD' => '&#36;',
			'AWG' => 'Afl.',
			'AZN' => 'AZN',
			'BAM' => 'KM',
			'BBD' => '&#36;',
			'BDT' => '&#2547;&nbsp;',
			'BGN' => '&#1083;&#1074;.',
			'BHD' => '.&#x62f;.&#x628;',
			'BIF' => 'Fr',
			'BMD' => '&#36;',
			'BND' => '&#36;',
			'BOB' => 'Bs.',
			'BRL' => '&#82;&#36;',
			'BSD' => '&#36;',
			'BTC' => '&#3647;',
			'BTN' => 'Nu.',
			'BWP' => 'P',
			'BYR' => 'Br',
			'BZD' => '&#36;',
			'CAD' => '&#36;',
			'CDF' => 'Fr',
			'CHF' => '&#67;&#72;&#70;',
			'CLP' => '&#36;',
			'CNY' => '&yen;',
			'COP' => '&#36;',
			'CRC' => '&#x20a1;',
			'CUC' => '&#36;',
			'CUP' => '&#36;',
			'CVE' => '&#36;',
			'CZK' => '&#75;&#269;',
			'DJF' => 'Fr',
			'DKK' => 'DKK',
			'DOP' => 'RD&#36;',
			'DZD' => '&#x62f;.&#x62c;',
			'EGP' => 'EGP',
			'ERN' => 'Nfk',
			'ETB' => 'Br',
			'EUR' => '&euro;',
			'FJD' => '&#36;',
			'FKP' => '&pound;',
			'GBP' => '&pound;',
			'GEL' => '&#x10da;',
			'GGP' => '&pound;',
			'GHS' => '&#x20b5;',
			'GIP' => '&pound;',
			'GMD' => 'D',
			'GNF' => 'Fr',
			'GTQ' => 'Q',
			'GYD' => '&#36;',
			'HKD' => '&#36;',
			'HNL' => 'L',
			'HRK' => 'Kn',
			'HTG' => 'G',
			'HUF' => '&#70;&#116;',
			'IDR' => 'Rp',
			'ILS' => '&#8362;',
			'IMP' => '&pound;',
			'INR' => '&#8377;',
			'IQD' => '&#x639;.&#x62f;',
			'IRR' => '&#xfdfc;',
			'IRT' => '&#x062A;&#x0648;&#x0645;&#x0627;&#x0646;',
			'ISK' => 'kr.',
			'JEP' => '&pound;',
			'JMD' => '&#36;',
			'JOD' => '&#x62f;.&#x627;',
			'JPY' => '&yen;',
			'KES' => 'KSh',
			'KGS' => '&#x441;&#x43e;&#x43c;',
			'KHR' => '&#x17db;',
			'KMF' => 'Fr',
			'KPW' => '&#x20a9;',
			'KRW' => '&#8361;',
			'KWD' => '&#x62f;.&#x643;',
			'KYD' => '&#36;',
			'KZT' => 'KZT',
			'LAK' => '&#8365;',
			'LBP' => '&#x644;.&#x644;',
			'LKR' => '&#xdbb;&#xdd4;',
			'LRD' => '&#36;',
			'LSL' => 'L',
			'LYD' => '&#x644;.&#x62f;',
			'MAD' => '&#x62f;.&#x645;.',
			'MDL' => 'MDL',
			'MGA' => 'Ar',
			'MKD' => '&#x434;&#x435;&#x43d;',
			'MMK' => 'Ks',
			'MNT' => '&#x20ae;',
			'MOP' => 'P',
			'MRO' => 'UM',
			'MUR' => '&#x20a8;',
			'MVR' => '.&#x783;',
			'MWK' => 'MK',
			'MXN' => '&#36;',
			'MYR' => '&#82;&#77;',
			'MZN' => 'MT',
			'NAD' => '&#36;',
			'NGN' => '&#8358;',
			'NIO' => 'C&#36;',
			'NOK' => '&#107;&#114;',
			'NPR' => '&#8360;',
			'NZD' => '&#36;',
			'OMR' => '&#x631;.&#x639;.',
			'PAB' => 'B/.',
			'PEN' => 'S/.',
			'PGK' => 'K',
			'PHP' => '&#8369;',
			'PKR' => '&#8360;',
			'PLN' => '&#122;&#322;',
			'PRB' => '&#x440;.',
			'PYG' => '&#8370;',
			'QAR' => '&#x631;.&#x642;',
			'RMB' => '&yen;',
			'RON' => 'lei',
			'RSD' => '&#x434;&#x438;&#x43d;.',
			'RUB' => '&#8381;',
			'RWF' => 'Fr',
			'SAR' => '&#x631;.&#x633;',
			'SBD' => '&#36;',
			'SCR' => '&#x20a8;',
			'SDG' => '&#x62c;.&#x633;.',
			'SEK' => '&#107;&#114;',
			'SGD' => '&#36;',
			'SHP' => '&pound;',
			'SLL' => 'Le',
			'SOS' => 'Sh',
			'SRD' => '&#36;',
			'SSP' => '&pound;',
			'STD' => 'Db',
			'SYP' => '&#x644;.&#x633;',
			'SZL' => 'L',
			'THB' => '&#3647;',
			'TJS' => '&#x405;&#x41c;',
			'TMT' => 'm',
			'TND' => '&#x62f;.&#x62a;',
			'TOP' => 'T&#36;',
			'TRY' => '&#8378;',
			'TTD' => '&#36;',
			'TWD' => '&#78;&#84;&#36;',
			'TZS' => 'Sh',
			'UAH' => '&#8372;',
			'UGX' => 'UGX',
			'USD' => '&#36;',
			'UYU' => '&#36;',
			'UZS' => 'UZS',
			'VEF' => 'Bs F',
			'VND' => '&#8363;',
			'VUV' => 'Vt',
			'WST' => 'T',
			'XAF' => 'Fr',
			'XCD' => '&#36;',
			'XOF' => 'Fr',
			'XPF' => 'Fr',
			'YER' => '&#xfdfc;',
			'ZAR' => '&#82;',
			'ZMW' => 'ZK',
		);
		$currencysymbols  = apply_filters( 'cdhl_get_currency_symbols', $currency_symbols );

		$currency_symbol = isset( $currency_symbols[ $currency_code ] ) ? $currency_symbols[ $currency_code ] : $currency_code;

		return apply_filters( 'cdhl_get_currency_symbol', $currency_symbol, $currency_code );
	}
}

if ( ! function_exists( 'cdhl_currency_option_list' ) ) {
	/**
	 * Get currency options list.
	 */
	function cdhl_currency_option_list() {
		$cdhl_currencie_list = cdhl_currencie_list();
		$currencie_list      = array();
		foreach ( $cdhl_currencie_list as $code => $name ) {
			$currencie_list[ $code ] = $name . ' (' . cdhl_get_currency_symbols( $code ) . ')';
		}
		return $currencie_list;
	}
}

if ( ! function_exists( 'cdhl_exclude_pages' ) ) {
	/**
	 * Function for exclude the pages for 404 page.
	 */
	function cdhl_exclude_pages() {
		$exclude_pages = array();

		// Exclude WooCommerce pages.
		if ( class_exists( 'WooCommerce' ) ) {
			$woocommerce_pages = array(
				'woocommerce_shop_page_id',
				'woocommerce_cart_page_id',
				'woocommerce_checkout_page_id',
				'woocommerce_pay_page_id',
				'woocommerce_thanks_page_id',
				'woocommerce_myaccount_page_id',
				'woocommerce_edit_address_page_id',
				'woocommerce_view_order_page_id',
				'woocommerce_terms_page_id',
			);
			foreach ( $woocommerce_pages as $woocommerce_page ) {
				$woocommerce_page_id = get_option( $woocommerce_page );
				if ( $woocommerce_page_id ) {
					$exclude_pages[] = $woocommerce_page_id;
				}
			}
		}

		return $exclude_pages;
	}
}

if ( ! function_exists( 'cdhl_log' ) ) {
	/**
	 * Function for logging background processes.
	 *
	 * @param string $message message for log.
	 * @param string $version version for log.
	 * @param string $type type for log.
	 */
	function cdhl_log( $message, $version = '', $type = '' ) {
		$file    = CDHL_LOG . 'cdhl_back_log_' . $version . '_' . date_i18n( 'm-d-Y' ) . '.txt';
		$open    = fopen( $file, 'a' );
		$log_txt = date_i18n( 'm-d-Y @ H:i:s' ) . '  ' . $type . ' ' . $message . "\n";
		$write   = fputs( $open, $log_txt );
		fclose( $open );
	}
}

if ( ! function_exists( 'cdhl_get_car_archive_page' ) ) {
	/**
	 * Get cat archive page.
	 */
	function cdhl_get_car_archive_page() {
		$options     = null;
		$car_page_id = null;
		$options     = get_option( 'car_dealer_options' );

		if ( isset( $options['cars_inventory_page'] ) ) {
			$car_page_id = $options['cars_inventory_page'];
		}
		if ( null != $car_page_id && get_post( $car_page_id ) ) {
			$car_page_id = cdhl_get_language_page_id( $car_page_id ); // get translated page id if.
			return urldecode( get_page_uri( $car_page_id ) );
		}
		return 'cars';
	}
}

if ( ! function_exists( 'cdhl_cars_page_slug' ) ) {
	/**
	 * Get car page slug.
	 */
	function cdhl_cars_page_slug() {
		$cars_page_slug = 'cars';
		$options        = get_option( 'car_dealer_options' );
		if ( isset( $options['cars-details-slug'] ) && ( ! empty( $options['cars-details-slug'] ) ) ) {
			$cars_page_slug = $options['cars-details-slug'];
		}
		return $cars_page_slug;
	}
}

if ( ! function_exists( 'cdhl_get_language_page_id' ) ) {
	/**
	 * Get language page id.
	 *
	 * @param int $car_page_id page id.
	 */
	function cdhl_get_language_page_id( $car_page_id ) {
		if ( function_exists( 'icl_object_id' ) ) { // check WPML installed.
			// get translated page id.
			$lang = defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : null;

			$current_page_id = apply_filters( 'wpml_object_id', $car_page_id, 'post', false, $lang );
			if ( $current_page_id ) {
				return $current_page_id;
			}
		}
		return $car_page_id;
	}
}

if ( ! function_exists( 'cdhl_get_template_part' ) ) {
	/**
	 * CarDealer template part functions.
	 *
	 * @param string $slug slug.
	 * @param string $name name.
	 */
	function cdhl_get_template_part( $slug, $name = '' ) {
		$template = '';

		$template_path = 'template-parts';

		/* Look in yourtheme/slug-name.php and yourtheme/woocommerce/slug-name.php */
		if ( $name ) {
			$template = locate_template(
				array(
					trailingslashit( $template_path ) . "{$slug}-{$name}.php",
				)
			);
		}

		/* Get default slug-name.php */
		if ( ! $template && $name && file_exists( trailingslashit( CDHL_PATH ) . "$template_path/{$slug}-{$name}.php" ) ) {
			$template = trailingslashit( CDHL_PATH ) . "$template_path/{$slug}-{$name}.php";
		}

		/* If template file doesn't exist, look in yourtheme/slug.php and yourtheme/woocommerce/slug.php */
		if ( ! $template ) {
			$template = locate_template(
				array(
					trailingslashit( $template_path ) . "{$slug}.php",
				)
			);
		}

		/* Get default slug.php */
		if ( ! $template && file_exists( trailingslashit( CDHL_PATH ) . "$template_path/{$slug}.php" ) ) {
			$template = trailingslashit( CDHL_PATH ) . "$template_path/{$slug}.php";
		}

		if ( $template ) {
			load_template( $template, false );
		}
	}
}

/*
* Get car dealer email id
* params: car id
* return: Dealer email id / null(if no dealer found)
*/
if ( ! function_exists( 'cdhl_get_dealer_mail' ) ) {
	/**
	 * Get cars dealer email id.
	 *
	 * @param string $car_id car ID.
	 */
	function cdhl_get_dealer_mail( $car_id = '' ) {
		if ( ! empty( $car_id ) ) {
			$dealer_id = get_post_meta( $car_id, 'cdfs_car_user', true );
			if ( isset( $dealer_id ) && ! empty( $dealer_id ) ) {
				$dealer_info = get_userdata( $dealer_id );
				return $dealer_info->data->user_email; // Send dealer email id.
			}
		}
		return false;
	}
}

if ( ! function_exists( 'cdhl_get_vimeo_thumb_image_url' ) ) {
	/**
	 * Get vimeo video thumbnail image.
	 *
	 * @param string $video_id video id.
	 * @param string $size size.
	 */
	function cdhl_get_vimeo_thumb_image_url( $video_id, $size = '120x90' ) {
		$vimeo = unserialize( file_get_contents( "http://vimeo.com/api/v2/video/$video_id.php" ) );
		if ( ! empty( $vimeo ) && ! empty( $vimeo[0]['thumbnail_small'] ) ) {
			$url         = substr( $vimeo[0]['thumbnail_small'], 0, strrpos( $vimeo[0]['thumbnail_small'], '/' ) + 1 );
			$vimeo_thumb = explode( '_', basename( $vimeo[0]['thumbnail_small'] ) );
			$thumb       = '';
			if ( isset( $vimeo_thumb ) && ! empty( $vimeo_thumb[0] ) && ! empty( $vimeo_thumb[1] ) ) {
				$get_extension = explode( '.', $vimeo_thumb[1] );
				$thumb         = $vimeo_thumb[0] . '_' . $size . '.' . end( $get_extension );
			}
			$full_url = $url . $thumb;
			return $full_url;
		}
	}
}

if ( ! function_exists( 'cdhl_check_video_type' ) ) {
	/**
	 * Function to check video type vimeo or youtube.
	 *
	 * @param string $url URL.
	 */
	function cdhl_check_video_type( $url ) {
		if ( preg_match( '/http:\/\/(www\.)*vimeo\.com\/.*/', $url ) || preg_match( '/https:\/\/(www\.)*vimeo\.com\/.*/', $url ) ) {
			// do vimeo stuff.
			return 'vimeo';
		} elseif ( preg_match( '/http:\/\/(www\.)*youtube\.com\/.*/', $url ) || preg_match( '/https:\/\/(www\.)*youtube\.com\/.*/', $url ) || preg_match( '/https:\/\/(www\.)*youtu\.be\/.*/', $url ) ) {
			// do youtube stuff.
			return 'youtube';
		} else {
			return 'other';
		}
	}
}

if ( ! function_exists( 'cdhl_get_video_id' ) ) {
	/**
	 * Get video id.
	 *
	 * @param string $video_type video type.
	 * @param string $video_link video link.
	 */
	function cdhl_get_video_id( $video_type, $video_link ) {

		if ( 'youtube' === $video_type ) {
			parse_str( wp_parse_url( $video_link, PHP_URL_QUERY ), $youtube_id );
			if ( empty( $youtube_id ) ) {
				$video_link_parse = explode( '/', $video_link );
				if ( ! empty( $video_link_parse ) ) {
					return $video_link_parse[ count( $video_link_parse ) - 1 ];
				} else {
					return '';
				}
			} elseif ( isset( $youtube_id['v'] ) && ! empty( $youtube_id['v'] ) ) {
				return $youtube_id['v'];
			}
		} else {
			return (int) substr( wp_parse_url( $video_link, PHP_URL_PATH ), 1 );
		}
		return '';
	}
}

if ( ! function_exists( 'cdfs_register_session' ) ) {
	/**
	 * Start session.
	 *
	 * @return void
	 */
	function cdfs_register_session() {
		if ( ! session_id() ) {
			session_start();
		}
	}
}

/*
* WP ALL IMPORT
* USE:Map carfax term and related URL
*/

add_filter( 'pmxi_single_category', 'cdhl_set_vrs_term', 10, 2 );

if ( ! function_exists( 'cdhl_set_vrs_term' ) ) {
	/**
	 * Parition term by "##" delemeter and reset term name.
	 *
	 * @param array  $ctx tax.
	 * @param string $tx_name tax name.
	 */
	function cdhl_set_vrs_term( $ctx, $tx_name ) {
		// Code for features and options.
		if ( 'car_features_options' === (string) $tx_name ) {
			if ( ! empty( $ctx ) && isset( $ctx['name'] ) ) {
				$tax_name    = str_replace( '_', ' ', $ctx['name'] );
				$ctx['name'] = $tax_name;
			}
		}

		add_action( 'init', 'cdfs_register_session' );
		$new_ctx = array();

		if ( 'car_vin_number' === (string) $tx_name ) {
			if ( ! empty( $ctx ) && isset( $ctx['name'] ) ) {
				$_SESSION['vin_number'][] = $ctx['name'];
			}
		}
		if ( 'car_vehicle_review_stamps' === (string) $tx_name ) {
			if ( ! empty( $ctx ) ) {
				$carfax_session = array();
				$found          = 0;
				foreach ( $ctx as $key => $ct ) {
					if ( strpos( $ct, 'carfax.com' ) !== false ) {
						$found                = 1;
						$vrs_ele_arr          = explode( '##', $ct );
						$vehicle_review_stamp = ( isset( $vrs_ele_arr[0] ) && ! empty( $vrs_ele_arr[0] ) ) ? trim( $vrs_ele_arr[0] ) : '';
						$vrs_url              = ( isset( $vrs_ele_arr[1] ) && ! empty( $vrs_ele_arr[1] ) ) ? trim( $vrs_ele_arr[1] ) : '';

						$carfax_session               = array(
							'term_name' => $vehicle_review_stamp,
							'term_url'  => $vrs_url,
						);
						$_SESSION['carfax_session'][] = $carfax_session;
						if ( 'name' === (string) $key ) {
							$new_ctx[ $key ] = $vehicle_review_stamp;
						} else {
							$new_ctx[ $key ] = $ct;
						}
					} else {
						$new_ctx[ $key ] = '';
					}
				}
				if ( 1 !== (int) $found || '1' !== (string) $found ) {
					$_SESSION['carfax_session'][] = '';
				}
			}
		}
		if ( ! empty( $new_ctx ) ) {
			return $new_ctx;
		} else {
			return $ctx;
		}
	}
}

add_action( 'pmxi_saved_post', 'cdhl_save_carfax_term_meta', 20, 1 );

if ( ! function_exists( 'cdhl_save_carfax_term_meta' ) ) {
	/**
	 * Set carfax term images and URL term meta.
	 *
	 * @param int $id store id.
	 */
	function cdhl_save_carfax_term_meta( $id ) {
		$terms = get_terms(
			array(
				'taxonomy'   => 'car_vehicle_review_stamps',
				'hide_empty' => false,
			)
		);
		add_action( 'init', 'cdfs_register_session' );
		$carfax_session = isset( $_SESSION['carfax_session'] ) ? $_SESSION['carfax_session'] : '';

		$carfax_link = null;
		if ( ! empty( $terms ) ) {
			$counter = 0;
			foreach ( $terms as $key => $tax_term ) {
				if ( ! empty( $carfax_session ) ) {
					if ( isset( $carfax_session[ $key ]['term_name'] ) && ! empty( $carfax_session[ $key ]['term_name'] ) ) {
						if ( strtolower( $carfax_session[ $key ]['term_name'] ) === strtolower( $tax_term->name ) ) {
							// proceess if carfax term not already added.
							if ( ! isset( $_SESSION['cdhl_inserted_carfax'] ) || ! in_array( strtolower( $tax_term->name ), $_SESSION['cdhl_inserted_carfax'] ) ) {

								// Build carfax URL.
								$vrs_url       = $carfax_session[ $key ]['term_url'];
								$vrs_url_array = explode( '&', $vrs_url );
								if ( ! empty( $vrs_url_array ) ) {
									foreach ( $vrs_url_array as $url ) {
										if ( stripos( $url, 'VIN' ) !== false ) {
											$vin_arr = explode( '=', $url );
											if ( isset( $vin_arr[1] ) && ! empty( $vin_arr[1] ) ) {
												$vin_number  = $vin_arr[1];
												$carfax_link = str_replace( $vin_number, '{{vin}}', $vrs_url );
											}
										}
									}
								}

								if ( null != $carfax_link ) {
									// Upload stamp image.
									if ( 'carfax 1-owner' === (string) strtolower( $tax_term->name ) ) {
										$file = CDHL_PATH . 'images\carfax\carfax-1-owner.jpg';
									} else {
										$file = CDHL_PATH . 'images\carfax\carfax.jpg';
									}

									// Upload images.
									$filename    = basename( $file );
									$upload_file = wp_upload_bits( $filename, null, file_get_contents( $file ) );
									if ( ! $upload_file['error'] ) {
										$wp_filetype   = wp_check_filetype( $filename, null );
										$attachment    = array(
											'post_mime_type' => $wp_filetype['type'],
											'post_title'   => preg_replace( '/\.[^.]+$/', '', $filename ),
											'post_content' => '',
											'post_status'  => 'inherit',
										);
										$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'] );
										if ( ! is_wp_error( $attachment_id ) ) {
											require_once ABSPATH . 'wp-admin/includes/image.php';
											$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
											wp_update_attachment_metadata( $attachment_id, $attachment_data );
											update_term_meta( $tax_term->term_id, 'image', $attachment_id );
										}
									}

									$_SESSION['cdhl_inserted_carfax'][] = strtolower( $carfax_session[ $key ]['term_name'] );

									wp_update_term(
										$tax_term->term_id,
										'car_vehicle_review_stamps',
										array(
											'name' => $vehicle_review_stamp,
											'slug' => str_replace(
												' ',
												'_',
												$vehicle_review_stamp
											),
										)
									);
									update_term_meta( $tax_term->term_id, 'url', $carfax_link );
								}
							}
						}
					}
				}
			}

			// Assign term to post.
			$vin_terms = get_the_terms( $id, 'car_vin_number' );
			if ( ! empty( $vin_terms ) ) {
				foreach ( $vin_terms as $vterm ) {
					$get_key = array_search( $vterm->name, $_SESSION['vin_number'] );
					if ( ! empty( $carfax_session[ $get_key ] ) ) {
						$tx_term_obj = get_term_by( 'name', $carfax_session[ $get_key ]['term_name'], 'car_vehicle_review_stamps' );
						if ( isset( $tx_term_obj->term_id ) && ! empty( $tx_term_obj->term_id ) ) {
							wp_set_object_terms( $id, array( $tx_term_obj->term_id ), 'car_vehicle_review_stamps', true );
						}
					}
				}
			}
		}
	}
}

if ( ! function_exists( 'cdhl_destroy_session' ) ) {
	/**
	 * Manange session for carfax.
	 *
	 * @param int $import_id store id.
	 */
	function cdhl_destroy_session( $import_id ) {
		add_action( 'init', 'cdfs_register_session' );
		if ( isset( $_SESSION['carfax_session'] ) ) {
			unset( $_SESSION['carfax_session'] );
		}
		if ( isset( $_SESSION['vin_number'] ) ) {
			unset( $_SESSION['vin_number'] );
		}
		if ( isset( $_SESSION['cdhl_inserted_carfax'] ) ) {
			unset( $_SESSION['cdhl_inserted_carfax'] );
		}
	}
}
add_action( 'pmxi_before_xml_import', 'cdhl_destroy_session', 10, 1 );

if ( ! function_exists( 'cdhl_vehicle_export_option_fields' ) ) {
	/**
	 * Get vehicles taxonimies.
	 *
	 * @param array  $taxonomies_unset taxonomies unset.
	 * @param string $return_array_type return array type.
	 */
	function cdhl_vehicle_export_option_fields() {

		$default = array(
			'vehicle_title'            => esc_html__( 'Vehicle Title', 'cardealer-helper' ),
			'car_images'               => esc_html__( 'Vehicle Images', 'cardealer-helper' ),
			'regular_price'            => esc_html__( 'Regular Price', 'cardealer-helper' ),
			'sale_price'               => esc_html__( 'Sale Price', 'cardealer-helper' ),
			'city_mpg'                 => esc_html__( 'City Mpg', 'cardealer-helper' ),
			'tax_label'                => esc_html__( 'Tax Label', 'cardealer-helper' ),
			'highway_mpg'              => esc_html__( 'Highway Mpg', 'cardealer-helper' ),
			'pdf_file'                 => esc_html__( 'Pdf File', 'cardealer-helper' ),
			'video_link'               => esc_html__( 'Video Link', 'cardealer-helper' ),
			'vehicle_overview'         => esc_html__( 'Vehicle Overview', 'cardealer-helper' ),
			'technical_specifications' => esc_html__( 'Technical Specifications', 'cardealer-helper' ),
			'general_information'      => esc_html__( 'General Information', 'cardealer-helper' ),
			'vehicle_location'         => esc_html__( 'Vehicle Location', 'cardealer-helper' ),
			'car_status'               => esc_html__( 'Vehicle Status', 'cardealer-helper' ),
		);

		$tax_keys = array(
			'car_year',
			'car_make',
			'car_model',
			'car_body_style',
			'car_mileage',
			'car_transmission',
			'car_condition',
			'car_drivetrain',
			'car_engine',
			'car_fuel_economy',
			'car_exterior_color',
			'car_interior_color',
			'car_stock_number',
			'car_vin_number',
			'car_fuel_type',
			'car_trim',
			'car_features_options',
		);

		foreach ( $tax_keys as $taxonomy ) {
			$default[ $taxonomy ] = cardealer_get_field_label_with_tax_key( $taxonomy );
		}

		$tax_arr         = array();
		$taxonomies_raw  = get_object_taxonomies( 'cars' );
		$default_tax_arr = array_keys( $default );

		foreach ( $taxonomies_raw as $new_tax ) {
			if ( in_array( $new_tax, $default_tax_arr ) ) {
				continue;
			}
			$default[ $new_tax ] = cardealer_get_field_label_with_tax_key( $new_tax );
		}
		return array_merge( $default, $tax_arr );
	}
}

if ( ! function_exists( 'cdhl_get_vehicles_taxonomies' ) ) {
	/**
	 * Get vehicles taxonimies.
	 *
	 * @param array  $exclude     Exclude taxonomies from result.
	 * @param string $return_type Return array type.
	 */
	function cdhl_get_vehicles_taxonomies( $exclude = array(), $return_type = 'val_to_key' ) {

		if ( empty( $exclude ) ) {
			$exclude = array( 'vehicle_cat', 'car_features_options' );
		}

		$taxonomies = get_taxonomies( array(
			'object_type' => array(
				'cars',
			),
		), 'objects' );

		foreach ( $exclude as $exclude_tax ) {
			if ( isset( $taxonomies[ $exclude_tax ] ) ) {
				unset( $taxonomies[ $exclude_tax ] );
			}
		}
		$return = array();
		foreach ( $taxonomies as $taxonomy_key => $taxonomy ) {
			if ( 'val_to_key' === $return_type ) {
				// array( taxonomy_label => taxonomy_key ).
				$return[ $taxonomy->labels->singular_name ] = $taxonomy_key;
			} else {
				// array( taxonomy_key => taxonomy_label ).
				$return[ $taxonomy_key ] = $taxonomy->labels->singular_name;
			}
		}

		/**
		 * Filters the vehicle taxonomy array, mostly used in theme options.
		 *
		 * @since 1.0
		 * @param array      $return Array vehicle taxonomies.
		 * @visible          true
		 */
		return apply_filters( 'cdhl_vehicles_taxonomies', $return );
	}
}

/*
 * This code contains functionality for export user personal information.
 */
if ( ! function_exists( 'cdhl_gdpr_export_get_lead_info' ) ) {
	/**
	 * This code contains functionality for export user personal information.
	 *
	 * @param string $email_address email address.
	 * @param string $lead_type lead type.
	 */
	function cdhl_gdpr_export_get_lead_info( $email_address, $lead_type = 'pgs_inquiry' ) {
		if ( empty( $email_address ) ) {
			return;
		}
		global $wpdb;

		$results      = array();
		$export_array = array();
		$export_items = array();

		$lead_query = "SELECT DISTINCT(cdhl_meta.post_id)
			FROM $wpdb->postmeta cdhl_meta
			JOIN $wpdb->posts cdhl_posts ON cdhl_meta.post_id = cdhl_posts.ID
			WHERE cdhl_posts.post_type = '$lead_type'
			AND cdhl_meta.meta_value = '$email_address'";
		$results    = $wpdb->get_results( $lead_query, ARRAY_A ); // get post id.

		if ( ! empty( $results ) ) {
			foreach ( $results as $lead ) {

				// Most item IDs should look like postType-postID
				// If you don't have a post, comment or other ID to work with,
				// use a unique value to avoid having this item's export
				// combined in the final report with other items of the same id.
				$item_id = $lead_type . "-{$lead['post_id']}";

				// Core group IDs include 'comments', 'posts', etc.
				// But you can add your own group IDs as needed.
				$group_id = $lead_type;

				// Optional group label. Core provides these for core groups.
				// If you define your own group, the first exporter to
				// include a label will be used as the group label in the
				// final exported report.
				$group_label = ( 'pgs_inquiry' === $lead_type ) ? esc_html__( 'Inquiry', 'cardealer-helper' ) : ucwords( str_replace( '_', ' ', $lead_type ) );

				// Plugins can add as many items in the item data array as they want.
				$inqdata       = array();
				$response_data = array();

				$meta_key_query = "
					SELECT DISTINCT($wpdb->postmeta.meta_key)
					FROM $wpdb->posts
					LEFT JOIN $wpdb->postmeta
					ON $wpdb->posts.ID = $wpdb->postmeta.post_id
					WHERE $wpdb->posts.post_type = '%s'
					AND $wpdb->postmeta.meta_key != ''
					AND $wpdb->postmeta.meta_key NOT RegExp '(^[_0-9].+$)'
					AND $wpdb->postmeta.meta_key NOT RegExp '(^[0-9]+$)'
				";
				$meta_keys      = $wpdb->get_col( $wpdb->prepare( $meta_key_query, $lead_type ) );
				$meta_keys      = array_slice( $meta_keys, 9, count( $meta_keys ), true ); // Remove car data.
				if ( ! empty( $meta_keys ) ) {
					// get personal date array.
					foreach ( $meta_keys as $key ) {
						$inqdata[ $key ] = get_post_meta( $lead['post_id'], $key, true );
					}

					// make personal date array.
					foreach ( $inqdata as $field => $data ) {
						if ( ! empty( $data ) ) {
							if ( 'joint_application' === (string) $field ) {
								$response_data[] = array(
									'name'  => '',
									'value' => ucwords( str_replace( '_', ' ', $field ) ),
								);
							} else {
								$response_data[] = array(
									'name'  => ucwords( str_replace( '_', ' ', $field ) ),
									'value' => $data,
								);
							}
						}
					}
					$export_items[] = array(
						'group_id'    => $group_id,
						'group_label' => $group_label,
						'item_id'     => $item_id,
						'data'        => $response_data,
					);
				}
			}
		}
		return array(
			'data' => $export_items,
			'done' => true,
		);
	}
}


if ( ! function_exists( 'cdhl_register_plugin_eraser' ) ) {
	/**
	 * Erase personal data.
	 *
	 * @param array $erasers erase plugin.
	 */
	function cdhl_register_plugin_eraser( $erasers ) {
		/**
		 * Filters array of theme custom post types to add in GRDP compliance to erase user personal data.
		 *
		 * @since 1.0
		 * @param array     $post_types Array of vehicle post types to include for erase user personal data.
		 * @visible         true
		 */
		$post_types = apply_filters(
			'cdhl_gdpr_remove_post_types',
			array(
				'pgs_inquiry',
				'make_offer',
				'schedule_test_drive',
				'financial_inquiry',
			)
		);

		foreach ( $post_types as $p_type ) {
			$erasers[ 'cdhl-erase-' . $p_type ] = array(
				'eraser_friendly_name' => ucwords( str_replace( '_', ' ', $p_type ) ) . esc_html__( 'Eraser Plugin', 'cardealer-helper' ),
				'callback'             => 'cdhl_' . $p_type . '_eraser',
			);
		}
		return $erasers;
	}
}

add_filter(
	'wp_privacy_personal_data_erasers',
	'cdhl_register_plugin_eraser',
	10
);

if ( ! function_exists( 'cdhl_get_footer_social_icons' ) ) {
	/**
	 * Fixed translation issue.
	 *
	 * @param string $icons footer social icons.
	 */
	function cdhl_get_footer_social_icons( $icons = '' ) {
		$social_icons = array(
			'facebook'    => esc_html__( 'Facebook', 'cardealer-helper' ),
			'twitter'     => esc_html__( 'Twitter', 'cardealer-helper' ),
			'dribbble'    => esc_html__( 'Dribbble', 'cardealer-helper' ),
			'google_plus' => esc_html__( 'Google Plus', 'cardealer-helper' ),
			'vimeo'       => esc_html__( 'Vimeo', 'cardealer-helper' ),
			'pinterest'   => esc_html__( 'Pinterest', 'cardealer-helper' ),
			'behance'     => esc_html__( 'Behance', 'cardealer-helper' ),
			'linkedin'    => esc_html__( 'Linkedin', 'cardealer-helper' ),
			'instagram'   => esc_html__( 'Instagram', 'cardealer-helper' ),
			'youtube'     => esc_html__( 'YouTube', 'cardealer-helper' ),
			'medium'      => esc_html__( 'Medium', 'cardealer-helper' ),
			'flickr'      => esc_html__( 'Flickr', 'cardealer-helper' ),
			'rss'         => esc_html__( 'RSS', 'cardealer-helper' ),
		);

		if ( ! empty( $icons ) ) {
			$social_icon = array();
			foreach ( $icons as $key => $icon ) {
				$social_icon[ $key ] = $social_icons[ $key ];
			}
			return $social_icon;
		}
		return $social_icons;
	}
}


if ( ! function_exists( 'cdhl_get_image_sizes' ) ) {
	/**
	 * Get image size list.
	 */
	function cdhl_get_image_sizes() {

		global $_wp_additional_image_sizes;
		$image_size  = array();
		$image_sizes = array();

		$default_image_sizes = get_intermediate_image_sizes();

		foreach ( $default_image_sizes as $size ) {
			$image_sizes[ $size ]['width']  = intval( get_option( "{$size}_size_w" ) );
			$image_sizes[ $size ]['height'] = intval( get_option( "{$size}_size_h" ) );
			$image_sizes[ $size ]['crop']   = get_option( "{$size}_crop" ) ? get_option( "{$size}_crop" ) : false;
		}

		if ( ! empty( $_wp_additional_image_sizes ) ) {
			$image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
		}

		$image_size['full'] = __( 'Full (Original Size)', 'cardealer-helper' );

		foreach ( $image_sizes as $name => $size ) {
			$_name               = str_replace( '_', ' ', $name );
			$_name               = str_replace( '-', ' ', $_name );
			$image_size[ $name ] = ucwords( $_name ) . ' (' . $size['width'] . 'x' . $size['height'] . ')';
		}

		return array_flip( $image_size );
	}
}

if ( ! function_exists( 'cdhl_get_additional_taxonomies' ) ) {
	/**
	 * Get additional taxonomies.
	 */
	function cdhl_get_additional_taxonomies( $fields = 'names' ) {
		$taxonomies = array();

		$additional_taxes = get_taxonomies( array(
			'is_additional_attribute' => true,
			'object_type' => array(
				'cars',
			),
		), 'objects' );

		foreach ( (array) $additional_taxes as $tax_name => $tax_obj ) {
			if ( 'all' === $fields ) {
				$taxonomies[ $tax_name ] = $tax_obj;
			} elseif ( 'name=>label' === $fields ) {
				$taxonomies[ $tax_name ] = $tax_obj->label;
			} elseif ( 'all' === $fields ) {
				$taxonomies[] = $tax_name;
			} else {
				$taxonomies[] = $tax_name;
			}
		}

		return $taxonomies;
	}
}

/**
 * CSS Class builder
 *
 * @param string|array $class  List of classes, either string (separated with space) or array.
 * #param bool         $echo   Whether to return or echo.
 * @return string
 */
function cdhl_class_builder( $class = '', $echo = true ) {
	$classes   = array();

	if ( ( is_array( $class ) || is_string( $class ) ) && ! empty( $class ) ) {
		// If $class is string, convert it to array.
		if ( is_string( $class ) ) {
			$class = preg_split( '#\s+#', $class );
		}
		$classes = $class;
	}

	// Sanitize classes.
	$classes = array_map( 'sanitize_html_class', $classes );
	$classes = array_map( 'esc_attr', $classes );

	// Convert array to string.
	$class_str = implode( ' ', array_filter( array_unique( $classes ) ) );

	if ( $echo ) {
        echo esc_attr( $class_str );
    } else {
        return $class_str;
    }
}

/**
 * Return array of additional attributes.
 *
 * @return array
 */
function cdhl_get_core_attributes_option() {
	$core_attributes = get_option( 'cdhl_core_attributes' );

	$core_attributes  = apply_filters( 'cdhl_core_attributes_option', $core_attributes );

	return $core_attributes;
}

/**
 * Return array of additional attributes.
 *
 * @return array
 */
function cdhl_get_core_attributes() {
	$attributes      = array();
	$core_attributes = cdhl_get_core_attributes_option();

	/*
	if ( ! $core_attributes ) {
		cardealer_setup_core_attributes();
		$core_attributes = cdhl_get_core_attributes_option();
	}

	if ( $core_attributes ) {
		$cars_taxonomies = get_object_taxonomies( 'cars' );
		$attributes_keys = array_intersect( $cars_taxonomies, array_keys( $core_attributes ) );
		$attributes      = array_map( 'get_taxonomy', $attributes_keys );
	}
	*/

	$attributes = array_map( function( $value ) {
		$tax_data = get_taxonomy( $value['taxonomy'] );
		return ( $tax_data ) ? $tax_data : false;
	}, $core_attributes );

	// Exclude empty values.
	$attributes = array_filter( $attributes );

	return $attributes;
}

function cardealer_helper_attributes_str( $attributes = array() ) {
	$attr_str = '';

	foreach ( $attributes as $attr_k => $attr_v ) {
		if ( is_string( $attr_v ) && ! empty( $attr_v ) ) {
			$attr_str .= ' ' . $attr_k . '="' . esc_attr( $attr_v ) . '"';
		}
	}

	return $attr_str;
}

function cardealer_helper_add_page_title_action() {
	$screen      = get_current_screen();

	if ( 'cars' !== $screen->post_type ) {
		return;
	}

	$actions  = array();
	$defaults = array(
		'title'      => '',
		'link'       => '',
		'class'      => '',
		'wp_kses'    => '',
		'attributes' => array(),
	);

	$actions = apply_filters( 'cardealer_page_title_actions', $actions, $screen );

	if ( is_array( $actions ) && ! empty( $actions ) ) {
		?>
		<div id="pgs-page-title-actions" style="display:none;">
			<?php
			foreach ( $actions as $action_k => $actions_v ) {

				$actions_v = wp_parse_args( $actions_v, $defaults );

				if ( empty( $actions_v['title'] ) || empty( $actions_v['link'] ) ) {
					continue;
				}

				$wp_kses     = ( ! empty( $actions_v['wp_kses'] ) && is_array( $actions_v['wp_kses'] ) ) ? $actions_v['wp_kses'] : cardealer_allowed_html( 'a' );
				$action_link = sprintf(
					'<a href="%1$s" class="%2$s"%3$s>%4$s</a>',
					esc_url( $actions_v['link'] ),
					esc_attr( 'page-title-action pgs-page-title-action' . ( ( is_string( $actions_v['class'] ) && ! empty( $actions_v['class'] ) ) ? ' ' . $actions_v['class'] : '' ) ),
					( ! empty( $actions_v['attributes'] ) ) ? cardealer_helper_attributes_str( $actions_v['attributes'] ) : '',
					esc_html( $actions_v['title'] )
				);

				echo wp_kses( $action_link, $wp_kses );
			}
			?>
		</div>
		<?php
	}
}
add_action( 'in_admin_header', 'cardealer_helper_add_page_title_action' );

if ( ! function_exists( 'cardealer_get_field_label_with_tax_key' ) ) {
	/**
	 * Get core field label with taxonomy key
	 */
	function cardealer_get_field_label_with_tax_key( $taxonomy, $labels_type = 'singular' ) {
		$label = '';

		if ( ! empty( $taxonomy ) && ! empty( $labels_type ) && in_array( $labels_type, array( 'singular', 'plural' ), true ) ) {
			$tax_obj = get_taxonomy( $taxonomy );
			if ( $tax_obj && ! is_wp_error( $tax_obj ) ) {
				$label = ( 'singular' === $labels_type ) ? $tax_obj->labels->singular_name : $tax_obj->labels->name;
			}
		}

		return $label;
	}
}
