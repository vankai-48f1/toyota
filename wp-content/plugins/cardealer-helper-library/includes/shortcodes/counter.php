<?php
/**
 * CarDealer Visual Composer counter Shortcode
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package car-dealer-helper/functions
 */

add_shortcode( 'cd_counter', 'cdhl_shortcode_counter' );

/**
 * Shortcode HTML.
 *
 * @param array $atts .
 */
function cdhl_shortcode_counter( $atts ) {
	$atts = shortcode_atts(
		array(
			'label'            => '',
			'counter'          => '',
			'style'            => 'style-1',
			'icon_type'        => 'fontawesome',
			'icon_fontawesome' => 'fas fa-info-circle',
			'icon_openiconic'  => 'vc-oi vc-oi-dial',
			'icon_typicons'    => 'typcn typcn-adjust-brightness',
			'icon_entypo'      => 'entypo-icon entypo-icon-note',
			'icon_linecons'    => 'vc_li vc_li-heart',
			'icon_monosocial'  => 'vc-mono vc-mono-fivehundredpx',
			'icon_flaticon'    => 'glyph-icon flaticon-air-conditioning',
			'css'              => '',
			'element_id'       => uniqid( 'cd_counter_' ),
		),
		$atts
	);
	extract( $atts );
	if ( empty( $label ) || empty( $counter ) ) {
		return null;
	}

	$css          = $atts['css'];
	$custom_class = vc_shortcode_custom_css_class( $css, ' ' );
	// Additional class.
	$extra_class = array();
	if ( 'style-1' === (string) $atts['style'] ) {
		$extra_class[] = '';
	} elseif ( 'style-2' === (string) $atts['style'] ) {
		$extra_class[] = 'icon';
		$extra_class[] = 'left';
	} elseif ( 'style-3' === (string) $atts['style'] ) {
		$extra_class[] = 'icon';
		$extra_class[] = 'right';
	} elseif ( 'style-4' === (string) $atts['style'] ) {
		$extra_class[] = 'left-separator';
	}

	// Icon.
	$icon_type = $atts['icon_type'];
	$icon      = $atts[ 'icon_' . $icon_type ];

	vc_icon_element_fonts_enqueue( $icon_type );

	$element_classes  = array(
		'counter',
		'clearfix',
		'counter-' . $style,
		$custom_class,
	);
	$element_classes  = implode( ' ', array_filter( array_unique( $element_classes ) ) );
	$element_classes .= ' ' . implode( ' ', array_filter( array_unique( $extra_class ) ) );
	ob_start();

	if ( 'style-4' === (string) $atts['style'] ) {
		?>
		<div class="<?php echo esc_attr( $element_classes ); ?>"> 
			<div class="separator"></div>
			<div class="info">
				<h6><?php echo esc_html( $atts['label'] ); ?></h6>
				<i class="<?php echo esc_attr( $icon ); ?>"></i>
				<b class="timer" data-to="<?php echo esc_attr( $atts['counter'] ); ?>" data-speed="10000"></b>
			</div>
		</div>
		<?php
	} else {
		?>
		<div class="<?php echo esc_attr( $element_classes ); ?>">
			<div class="icon">
				<i class="<?php echo esc_attr( $icon ); ?>"></i>
			</div> 
			<div class="content">
				<h6><?php echo esc_html( $atts['label'] ); ?></h6>
				<b class="timer" data-to="<?php echo esc_attr( $atts['counter'] ); ?>" data-speed="10000"></b>
			</div> 
		</div>
		<?php
	}
	return ob_get_clean();
}

/**
 * Shortcode mapping.
 *
 * @return void
 */
function cdhl_counter_shortcode_vc_map() {
	if ( function_exists( 'vc_map' ) ) {
		$params     = array(
			array(
				'type'       => 'cd_radio_image',
				'heading'    => esc_html__( 'Style', 'cardealer-helper' ),
				'param_name' => 'style',
				'options'    => cdhl_get_shortcode_param_data( 'cd_counter' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Label', 'cardealer-helper' ),
				'description' => esc_html__( 'Enter counter label.', 'cardealer-helper' ),
				'param_name'  => 'label',
			),
			array(
				'type'        => 'cd_number_min_max',
				'heading'     => esc_html__( 'Counter', 'cardealer-helper' ),
				'param_name'  => 'counter',
				'min'         => '1',
				'max'         => '9999999',
				'description' => esc_html__( 'Enter counter count.', 'cardealer-helper' ),
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
					array(
						'type'             => 'tab_id',
						'heading'          => esc_html__( 'Tab ID', 'cardealer-helper' ),
						'param_name'       => 'element_id',
						'edit_field_class' => 'hidden',
					),
				)
			);
		vc_map(
			array(
				'name'                    => esc_html__( 'Potenza Counter', 'cardealer-helper' ),
				'description'             => esc_html__( 'Potenza Counter', 'cardealer-helper' ),
				'base'                    => 'cd_counter',
				'class'                   => 'cardealer_helper_element_wrapper',
				'controls'                => 'full',
				'icon'                    => cardealer_vc_shortcode_icon( 'cd_counter' ),
				'category'                => esc_html__( 'Potenza', 'cardealer-helper' ),
				'show_settings_on_create' => true,
				'params'                  => $params,
			)
		);
	}
}
add_action( 'vc_before_init', 'cdhl_counter_shortcode_vc_map' );
