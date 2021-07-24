<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * CarDealer Visual Composer social icons Shortcode
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package car-dealer-helper/functions
 */

add_shortcode( 'cd_social_icons', 'cdhl_shortcode_social_icons' );

/**
 * Shortcode HTML.
 *
 * @param array $atts .
 */
function cdhl_shortcode_social_icons( $atts ) {
	$atts = shortcode_atts(
		array(
			'style'      => 'style-1',
			'list'       => '',
			'css'        => '',
			'element_id' => uniqid( 'cd_social_icons_' ),
		),
		$atts
	);
	extract( $atts );

	$list_items = vc_param_group_parse_atts( $list );

	if ( ! is_array( $list_items ) || empty( $list_items ) || ( 1 === (int) ( count( $list_items ) ) && empty( $list_items[0] ) ) ) {
		return;
	}

	$css = $atts['css'];

	$custom_class = vc_shortcode_custom_css_class( $css, ' ' );

	$element_classes = array(
		'social',
		$atts['style'],
		$custom_class,
	);
	$element_classes = implode( ' ', array_filter( array_unique( $element_classes ) ) );
	ob_start();
	?>
	<div id="<?php echo esc_attr( $element_id ); ?>" class="<?php echo esc_attr( $element_classes ); ?>">
		<?php
		foreach ( $list_items as $list_item ) {
			if ( ! empty( $list_item['title'] ) && ! empty( $list_item['icon_class'] ) && ! empty( $list_item['link_url'] ) ) {
				?>
				<a href="<?php echo esc_attr( $list_item['link_url'] ); ?>" title="<?php echo esc_attr( $list_item['title'] ); ?>">
					<i class="fa <?php echo esc_html( $list_item['icon_class'] ); ?>"></i>
				</a>
				<?php
			}
		}
		?>
	</div>
	<?php

	return ob_get_clean();
}

/**
 * Shortcode mapping.
 *
 * @return void
 */
function cdhl_social_icons_shortcode_vc_map() {
	if ( function_exists( 'vc_map' ) ) {
		global $vc_gitem_add_link_param;

		// Apply icon library only if checkbox is checked.
		$params = array(
			array(
				'type'       => 'cd_radio_image',
				'heading'    => esc_html__( 'Style', 'cardealer-helper' ),
				'param_name' => 'style',
				'options'    => cdhl_get_shortcode_param_data( 'cd_social_icons' ),
			),
		);

		$params = array_merge(
			$params,
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
							'heading'          => esc_html__( 'Title', 'cardealer-helper' ),
							'param_name'       => 'title',
							'tooltip'          => esc_html__( 'Social Title', 'cardealer-helper' ),
							'edit_field_class' => 'vc_col-sm-12 vc_column',
						),
						array(
							'type'             => 'textfield',
							'heading'          => esc_html__( 'Icon Class', 'cardealer-helper' ),
							'description'      => sprintf(
								wp_kses(
									/* translators: $s: You can get icons classes from */
									__( 'You can get icons classes from <strong><a target="blank" href="%1$s">here</a></strong>.', 'cardealer-helper' ),
									array(
										'a'      => array(
											'href'   => array(),
											'target' => array(),
										),
										'strong' => array(),
									)
								),
								esc_url( 'http://fontawesome.io/icons/' )
							),
							'param_name'       => 'icon_class',
							'tooltip'          => esc_html__( 'Social Icon.', 'cardealer-helper' ),
							'edit_field_class' => 'vc_col-sm-12 vc_column',
						),
						array(
							'type'             => 'textfield',
							'heading'          => esc_html__( 'Link URL', 'cardealer-helper' ),
							'param_name'       => 'link_url',
							'tooltip'          => esc_html__( 'Social Link.', 'cardealer-helper' ),
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
				'name'                    => esc_html__( 'Potenza Social Icons', 'cardealer-helper' ),
				'description'             => esc_html__( 'Potenza Social Icons', 'cardealer-helper' ),
				'base'                    => 'cd_social_icons',
				'class'                   => 'cardealer_helper_element_wrapper',
				'controls'                => 'full',
				'icon'                    => cardealer_vc_shortcode_icon( 'cd_social_icons' ),
				'category'                => esc_html__( 'Potenza', 'cardealer-helper' ),
				'show_settings_on_create' => true,
				'params'                  => $params,
			)
		);
	}
}
add_action( 'vc_before_init', 'cdhl_social_icons_shortcode_vc_map' );
