<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * CarDealer Visual Composer quick links Shortcode
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package car-dealer-helper/functions
 */

add_shortcode( 'cd_quick_links', 'cdhl_shortcode_quick_links' );

/**
 * Shortcode HTML.
 *
 * @param array $atts .
 */
function cdhl_shortcode_quick_links( $atts ) {
	$atts = shortcode_atts(
		array(
			'icon_type'        => 'fontawesome',
			'icon_fontawesome' => 'fas fa-info-circle',
			'icon_openiconic'  => 'vc-oi vc-oi-dial',
			'icon_typicons'    => 'typcn typcn-adjust-brightness',
			'icon_entypo'      => 'entypo-icon entypo-icon-note',
			'icon_linecons'    => 'vc_li vc_li-heart',
			'icon_monosocial'  => 'vc-mono vc-mono-fivehundredpx',
			'icon_flaticon'    => 'glyph-icon flaticon-air-conditioning',
			'hover_style'      => false,
			'style'            => 'Layout',
			'url'              => '#',
			'title'            => 'Title',
			'css'              => '',
			'element_id'       => uniqid( 'cd_quick_links_' ),
		),
		$atts
	);

	extract( $atts );
	$extra_classes = array();
	if ( true === (bool) $atts['hover_style'] ) {
		$extra_classes[] = 'box-hover';
	}

	if ( empty( $atts['title'] ) || empty( $atts['url'] ) ) {
		return;
	}

	$url_vars = vc_build_link( $atts['url'] );
	$url_attr = cdhl_vc_link_attr( $url_vars );

	$icon_type = $atts['icon_type'];
	$icon      = $atts[ 'icon_' . $icon_type ];
	vc_icon_element_fonts_enqueue( $icon_type );

	$css          = $atts['css'];
	$custom_class = vc_shortcode_custom_css_class( $css, ' ' );

	$element_classes = array(
		'q-link',
		$custom_class,
	);

	$element_classes = implode( ' ', array_filter( array_unique( $element_classes ) ) );
	if ( ! empty( $extra_classes ) ) {
		$element_classes .= ' ' . implode( ' ', array_filter( array_unique( $extra_classes ) ) );
	}

	ob_start();
	if ( 'Layout' === (string) $atts['style'] ) {
		?>
	<a <?php echo $url_attr; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?> id="<?php echo esc_attr( $element_id ); ?>" class="<?php echo esc_attr( $element_classes ); ?>">
		<i class="<?php echo esc_attr( $icon ); ?>"></i>
		<?php
		if ( ! empty( $atts['title'] ) ) {
			echo '<h6>' . esc_html( $atts['title'] ) . '</h6>';
		}
		?>
	</a>
		<?php
	}
	return ob_get_clean();
}

/**
 * Shortcode mapping.
 *
 * @return void
 */
function cdhl_quick_links_shortcode_vc_map() {
	if ( function_exists( 'vc_map' ) ) {
		$params = array(
			array(
				'type'       => 'cd_radio_image',
				'heading'    => esc_html__( 'Style', 'cardealer-helper' ),
				'param_name' => 'style',
				'options'    => cdhl_get_shortcode_param_data( 'cd_quick_links' ),
			),
			array(
				'type'        => 'textfield',
				'class'       => 'pgs_quick_links_title',
				'heading'     => esc_html__( 'Title', 'cardealer-helper' ),
				'description' => esc_html__( 'Enter title here', 'cardealer-helper' ),
				'param_name'  => 'title',
				'value'       => esc_html__( 'Title', 'cardealer-helper' ),
			),
			array(
				'type'        => 'vc_link',
				'heading'     => esc_html__( 'URL (Link)', 'cardealer-helper' ),
				'param_name'  => 'url',
				'description' => esc_html__( 'Add custom link.', 'cardealer-helper' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Add Hover Style?', 'cardealer-helper' ),
				'description' => esc_html__( 'Click checkbox to add hover style to element', 'cardealer-helper' ),
				'param_name'  => 'hover_style',
			),
		);
		$params = array_merge(
			$params,
			cdhl_iconpicker(),
			array(
				array(
					'type'       => 'css_editor',
					'heading'    => esc_html__( 'CSS box', 'cardealer-helper' ),
					'param_name' => 'css',
					'group'      => esc_html__( 'Design Options', 'cardealer-helper' ),
				),
			)
		);
		vc_map(
			array(
				'name'                    => esc_html__( 'Potenza Quick Links', 'cardealer-helper' ),
				'description'             => esc_html__( 'Potenza Quick Links', 'cardealer-helper' ),
				'base'                    => 'cd_quick_links',
				'class'                   => 'cardealer_helper_element_wrapper',
				'controls'                => 'full',
				'icon'                    => cardealer_vc_shortcode_icon( 'cd_quick_links' ),
				'category'                => esc_html__( 'Potenza', 'cardealer-helper' ),
				'show_settings_on_create' => true,
				'params'                  => $params,
			)
		);
	}
}
add_action( 'vc_before_init', 'cdhl_quick_links_shortcode_vc_map' );
