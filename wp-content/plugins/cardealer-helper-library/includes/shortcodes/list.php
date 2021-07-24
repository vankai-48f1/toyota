<?php
/**
 * CarDealer Visual Composer list Shortcode
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package car-dealer-helper/functions
 */

add_shortcode( 'cd_list', 'cdhl_shortcode_list' );

/**
 * Shortcode HTML.
 *
 * @param array $atts .
 */
function cdhl_shortcode_list( $atts ) {
	$atts = shortcode_atts(
		array(
			'add_icon'         => false,
			'icon_type'        => 'fontawesome',
			'icon_fontawesome' => 'fas fa-info-circle',
			'icon_openiconic'  => 'vc-oi vc-oi-dial',
			'icon_typicons'    => 'typcn typcn-adjust-brightness',
			'icon_entypo'      => 'entypo-icon entypo-icon-note',
			'icon_linecons'    => 'vc_li vc_li-heart',
			'icon_monosocial'  => 'vc-mono vc-mono-fivehundredpx',
			'icon_flaticon'    => 'glyph-icon flaticon-air-conditioning',
			'list'             => '',
			'css'              => '',
		),
		$atts
	);
	extract( $atts );

	$list_items = vc_param_group_parse_atts( $list );

	if ( ! is_array( $list_items ) || empty( $list_items ) || ( ( 1 === (int) count( $list_items ) ) && empty( $list_items[0] ) ) ) {
		return;
	}

	if ( ! empty( $atts['add_icon'] ) ) {
		$icon_type = $atts['icon_type'];
		$icon      = $atts[ 'icon_' . $icon_type ];
		vc_icon_element_fonts_enqueue( $icon_type );
	}

	$css = $atts['css'];

	$custom_class = vc_shortcode_custom_css_class( $css, ' ' );

	$element_classes = array(
		'list',
		$custom_class,
	);
	$element_classes = implode( ' ', array_filter( array_unique( $element_classes ) ) );
	ob_start();
	?>
	<ul class="<?php echo esc_attr( $element_classes ); ?>">
		<?php
		foreach ( $list_items as $list_item ) {
			if ( ! empty( $list_item['title'] ) ) {
				?>
				<li>
				<?php
				if ( ! empty( $atts['add_icon'] ) ) {
					?>
					<i class="<?php echo esc_attr( $icon ); ?>"></i> <?php } ?><span><?php echo esc_html( $list_item['title'] ); ?></span></li>
				<?php
			}
		}
		?>
	</ul>
	<?php

	return ob_get_clean();
}

/**
 * Shortcode mapping.
 *
 * @return void
 */
function cdhl_list_shortcode_vc_map() {
	if ( function_exists( 'vc_map' ) ) {
		global $vc_gitem_add_link_param;
		// apply icon library only if checkbox is checked.
		$dependency = array(
			'element' => 'add_icon',
			'value'   => 'true',
		);

		$params = array(
			array(
				'type'       => 'checkbox',
				'heading'    => esc_html__( 'Add icon?', 'cardealer-helper' ),
				'param_name' => 'add_icon',
			),
		);
		$params = array_merge(
			$params,
			cdhl_iconpicker( $dependency ), // apply icon library only if checkbox is checked.
			array(
				array(
					'type'       => 'cd_divider',
					'title'      => esc_html__( 'List items', 'cardealer-helper' ),
					'param_name' => 'list_divider',
					'group'      => esc_html__( 'List', 'cardealer-helper' ),
				),
				array(
					'type'       => 'param_group',
					'param_name' => 'list',
					'group'      => esc_html__( 'List', 'cardealer-helper' ),
					'params'     => array(
						array(
							'type'             => 'textfield',
							'heading'          => esc_html__( 'Content', 'cardealer-helper' ),
							'param_name'       => 'title',
							'tooltip'          => esc_html__( 'Define item content. <br/>HTML markup is supported.', 'cardealer-helper' ),
							'edit_field_class' => 'vc_col-sm-12 vc_column',
						),
					),
					'callbacks'  => array(
						'after_add' => 'vcChartParamAfterAddCallback',
					),
				),
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
				'name'                    => esc_html__( 'Potenza List', 'cardealer-helper' ),
				'description'             => esc_html__( 'Potenza List', 'cardealer-helper' ),
				'base'                    => 'cd_list',
				'class'                   => 'cardealer_helper_element_wrapper',
				'controls'                => 'full',
				'icon'                    => cardealer_vc_shortcode_icon( 'cd_list' ),
				'category'                => esc_html__( 'Potenza', 'cardealer-helper' ),
				'show_settings_on_create' => true,
				'params'                  => $params,
			)
		);
	}
}
add_action( 'vc_before_init', 'cdhl_list_shortcode_vc_map' );
