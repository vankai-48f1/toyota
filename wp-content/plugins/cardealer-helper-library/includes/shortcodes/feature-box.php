<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * CarDealer Visual Composer feature box Shortcode
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package car-dealer-helper/functions
 */

if ( ! function_exists( 'cd_feature_box_container' ) ) {
	/**
	 * Feature box container.
	 *
	 * @param array $atts .
	 * @param array $content .
	 */
	function cd_feature_box_container( $atts, $content = null ) {
		return '<div class="owl-carousel cd-featured-carousel" data-loop="false" data-nav-dots="true" data-items="3" data-md-items="2" data-sm-items="1" data-xs-items="1" data-space="20">' . do_shortcode( $content ) . '</div>';
	}
	add_shortcode( 'cd_feature_box_container', 'cd_feature_box_container' );
}

add_shortcode( 'cd_feature_box', 'cdhl_shortcode_feature_box' );

/**
 * Shortcode HTML.
 *
 * @param array $atts .
 */
function cdhl_shortcode_feature_box( $atts ) {
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
			'back_image'       => 'false',
			'back_image_url'   => '',
			'hover_style'      => false,
			'style'            => 'style-1',
			'title'            => '',
			'url'              => '#',
			'description'      => '',
			'css'              => '',
			'border'           => 'yes',
			'element_id'       => uniqid( 'cd_feature_box_' ),
		),
		$atts
	);
	extract( $atts );

	$extra_classes = array();
	if ( 'style-1' === (string) $atts['style'] ) {
		$extra_classes[] = 'round-icon';
	} elseif ( 'style-2' === (string) $atts['style'] ) {
		$extra_classes[] = 'round-icon';
		$extra_classes[] = 'left';
	} elseif ( 'style-3' === (string) $atts['style'] ) {
		$extra_classes[] = 'round-icon';
		$extra_classes[] = 'right';
	} elseif ( 'style-4' === (string) $atts['style'] ) {
		$extra_classes[] = 'default-feature';
	} elseif ( 'style-5' === (string) $atts['style'] ) {
		$extra_classes[] = 'left-icon';
	} elseif ( 'style-6' === (string) $atts['style'] ) {
		$extra_classes[] = 'right-icon';
	} elseif ( 'style-6' === (string) $atts['style'] ) {
		$extra_classes[] = 'text-right';
	} elseif ( 'style-7' === (string) $atts['style'] ) {
		$extra_classes[] = 'round-border';
	} elseif ( 'style-8' === (string) $atts['style'] || 'style-10' === (string) $atts['style'] ) {
		$extra_classes[] = 'left-align';
	} elseif ( 'style-9' === (string) $atts['style'] ) {
		$extra_classes[] = 'right-align';
	}

	if ( true === (bool) $atts['hover_style'] ) {
		$extra_classes[] = 'box-hover';
	}

	$icon_type = $atts['icon_type'];
	$icon      = $atts[ 'icon_' . $icon_type ];

	vc_icon_element_fonts_enqueue( $icon_type );

	$url_vars = vc_build_link( $atts['url'] );
	$url_attr = cdhl_vc_link_attr( $url_vars );

	$css          = $atts['css'];
	$border_class = ( 'yes' === (string) $atts['border'] ) ? 'feature-border' : '';
	$custom_class = vc_shortcode_custom_css_class( $css, ' ' );

	$element_classes = array(
		'feature-box',
		$border_class,
		$atts['style'],
		$custom_class,
	);
	$element_classes = implode( ' ', array_filter( array_unique( $element_classes ) ) );
	if ( ! empty( $extra_classes ) ) {
		$element_classes .= ' ' . implode( ' ', array_filter( array_unique( $extra_classes ) ) );
	}

	if ( empty( $atts['title'] ) ) {
		return;
	}

	ob_start();
	$feature_bx_img = '';
	if ( 'true' === (string) $back_image && ! empty( $back_image_url ) ) {
		$img_obj = wp_get_attachment_image_src( $back_image_url, 'full' );
		if ( isset( $img_obj[0] ) && ! empty( $img_obj[0] ) ) {
			$feature_bx_img = $img_obj[0];
		}
	} else {
		$element_classes .= ' no-image';
	}
	?>
	<div id="<?php echo esc_attr( 'cd_feature_box_' . $element_id ); ?>" class="<?php echo esc_attr( $element_classes ); ?>
						<?php
						if ( empty( $atts['style'] ) ) {
							?>
			text-center<?php } ?>">
		<?php
		if ( ! empty( $feature_bx_img ) ) {
			?>
				<img class="img-responsive center-block" src="<?php echo esc_url( $feature_bx_img ); ?>" alt="">
			<?php
		}
		?>
		<div class="icon">
			<i class="<?php echo esc_attr( $icon ); ?>"></i>
		</div>
		<div class="content">
			<a <?php echo $url_attr; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?> id="<?php echo esc_attr( $element_id ); ?>">        		
				<?php
				if ( ! empty( $atts['title'] ) ) {
					echo '<h6>' . esc_html( $atts['title'] ) . '</h6>';
				}
				?>
			</a>
			<?php
			if ( ! empty( $atts['description'] ) ) {
				echo '<p>' . $atts['description'] . '</p>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			}
			?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Shortcode mapping.
 *
 * @return void
 */
function cdhl_feature_box_shortcode_vc_map() {
	if ( function_exists( 'vc_map' ) ) {
		$params = array(
			array(
				'type'        => 'cd_radio_image',
				'heading'     => esc_html__( 'Style', 'cardealer-helper' ),
				'param_name'  => 'style',
				'options'     => cdhl_get_shortcode_param_data( 'cd_feature_box' ),
				'description' => esc_html__( 'In style-10, if the image was not select, then icon and title will appear at the top.', 'cardealer-helper' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Title Border', 'cardealer-helper' ),
				'param_name'  => 'border',
				'value'       => array(
					esc_html__( 'Yes', 'cardealer-helper' ) => 'yes',
					esc_html__( 'No', 'cardealer-helper' ) => 'no',
				),
				'description' => esc_html__( 'Set Title Border.', 'cardealer-helper' ),
				'dependency'  => array(
					'element' => 'style',
					'value'   => array( 'style-1', 'style-2', 'style-3' ),
				),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Add Background Image?', 'cardealer-helper' ),
				'description' => esc_html__( 'Click checkbox to add backgound image to feature box', 'cardealer-helper' ),
				'param_name'  => 'back_image',
				'default'     => 'false',
				'dependency'  => array(
					'element' => 'style',
					'value'   => 'style-10',
				),
			),
			array(
				'type'        => 'attach_image',
				'heading'     => esc_html__( 'Background Image', 'cardealer-helper' ),
				'param_name'  => 'back_image_url',
				'description' => esc_html__( 'Select background image.', 'cardealer-helper' ),
				'dependency'  => array(
					'element' => 'back_image',
					'value'   => 'true',
				),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Add Hover Style?', 'cardealer-helper' ),
				'description' => esc_html__( 'Click checkbox to add hover style to element', 'cardealer-helper' ),
				'param_name'  => 'hover_style',
			),
			array(
				'type'        => 'textfield',
				'class'       => 'pgs_feature_box_title',
				'heading'     => esc_html__( 'Title', 'cardealer-helper' ),
				'description' => esc_html__( 'Enter title here', 'cardealer-helper' ),
				'param_name'  => 'title',
			),
			array(
				'type'       => 'vc_link',
				'heading'    => esc_html__( 'Link', 'cardealer-helper' ),
				'param_name' => 'url',
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Description', 'cardealer-helper' ),
				'param_name'  => 'description',
				'description' => esc_html__( 'Enter description. Please ensure to add short content.', 'cardealer-helper' ),
				'dependency'  => array(
					'element'            => 'style',
					'value_not_equal_to' => 'style-10',
				),
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
				'name'                    => esc_html__( 'Potenza Feature Box', 'cardealer-helper' ),
				'description'             => esc_html__( 'Potenza Feature Box', 'cardealer-helper' ),
				'base'                    => 'cd_feature_box',
				// "as_child" 			  => array('only' => 'cd_feature_box_container'),
				'class'                   => 'cardealer_helper_element_wrapper',
				'controls'                => 'full',
				'icon'                    => cardealer_vc_shortcode_icon( 'cd_feature_box' ),
				'category'                => esc_html__( 'Potenza', 'cardealer-helper' ),
				'show_settings_on_create' => true,
				'params'                  => $params,
			)
		);

		vc_map(
			array(
				'name'                    => esc_html__( 'Feature Box Container', 'cardealer-helper' ),
				'base'                    => 'cd_feature_box_container',
				'as_parent'               => array( 'only' => 'cd_feature_box' ), // Use only|except attributes to limit child shortcodes (separate multiple values with comma).
				'content_element'         => true,
				'class'                   => 'cardealer_helper_element_wrapper',
				'icon'                    => cardealer_vc_shortcode_icon( 'cd_feature_box' ),
				'show_settings_on_create' => false,
				'category'                => esc_html__( 'Potenza', 'cardealer-helper' ),
				'is_container'            => true,
				'params'                  => array(
					// add params same as with any other content element.
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'cardealer-helper' ),
						'param_name'  => 'cd_feature_box_container_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'cardealer-helper' ),
					),
				),
				'js_view'                 => 'VcColumnView',
			)
		);
	}
}
add_action( 'vc_before_init', 'cdhl_feature_box_shortcode_vc_map' );


if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	class WPBakeryShortCode_Cd_Feature_Box_Container extends WPBakeryShortCodesContainer {
	}
}
if ( class_exists( 'WPBakeryShortCode' ) ) {
	class WPBakeryShortCode_Cd_Feature_Box extends WPBakeryShortCode {
	}
}
