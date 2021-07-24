<?php
/**
 * CarDealer Visual Composer Call to action Shortcode
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package car-dealer-helper/functions
 */

add_shortcode( 'cd_call_to_action', 'cdhl_call_to_action_shortcode' );

/**
 * Shortcode HTML.
 *
 * @param array $atts .
 */
function cdhl_call_to_action_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'style'            => 'Default-Layout',
			'title'            => 'Default Title',
			'description'      => 'Default Description',
			'readmore'         => '#',
			'box_bg_image'     => '',
			'icon_type'        => 'fontawesome',
			'icon_fontawesome' => 'fas fa-info-circle',
			'icon_openiconic'  => 'vc-oi vc-oi-dial',
			'icon_typicons'    => 'typcn typcn-adjust-brightness',
			'icon_entypo'      => 'entypo-icon entypo-icon-note',
			'icon_linecons'    => 'vc_li vc_li-heart',
			'icon_monosocial'  => 'vc-mono vc-mono-fivehundredpx',
			'icon_flaticon'    => 'glyph-icon flaticon-air-conditioning',
			'element_id'       => uniqid( 'cd_call_to_action_' ),
		),
		$atts
	);
	extract( $atts );

	if ( empty( $title ) || empty( $description ) ) {
		return null;
	}

	// Enqueue CSS for icon type.
	$icon_type = $atts['icon_type'];
	$icon      = $atts[ 'icon_' . $icon_type ];
	vc_icon_element_fonts_enqueue( $icon_type );

	// Read More Link.
	if ( ! empty( $readmore ) && '#' !== (string) $readmore ) {
		$url_vars = vc_build_link( $readmore );
	} else {
		$url_vars = array(
			'url' => '#',
		);
	}
	$btn_attr = cdhl_vc_link_attr( $url_vars );

	$element_classes = array(
		'call-to-action',
		'text-center',
	);
	$element_classes = implode( ' ', array_filter( array_unique( $element_classes ) ) );

	ob_start();
	?>
	<div id="<?php echo esc_attr( $element_id ); ?>" class="<?php echo esc_attr( $element_classes ); ?>">
		<?php
		if ( 'Default-Layout' === (string) $style ) {
			?>
			<div class="action-info">
				<i class="<?php echo esc_attr( $icon ); ?>"></i>
				<h5><?php echo esc_html( $title ); ?></h5>
				<p><?php echo esc_html( $description ); ?></p>
			</div>
			<a <?php echo $btn_attr; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>><?php echo esc_html__( 'Read more', 'cardealer-helper' ); ?></a>
			<?php
			if ( ! empty( $box_bg_image ) ) {
				// Get the image src.
				$box_bg_data = wp_get_attachment_image_src( $box_bg_image, 'full' );
				if ( ! empty( $box_bg_data[0] ) ) {
					?>
					<div style="background-image: url('<?php echo esc_url( $box_bg_data[0] ); ?>');" class="action-img"></div>
						<?php
				}
			}
			?>

			<span class="border"></span>
			<?php
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
function cdhl_call_to_action_shortcode_vc_map() {
	if ( function_exists( 'vc_map' ) ) {
		$params = array(
			array(
				'type'       => 'cd_radio_image',
				'heading'    => esc_html__( 'Layout', 'cardealer-helper' ),
				'param_name' => 'style',
				'options'    => cdhl_get_shortcode_param_data( 'cd_call_to_action' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Title', 'cardealer-helper' ),
				'description' => esc_html__( 'Enter action title.', 'cardealer-helper' ),
				'param_name'  => 'title',
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Description', 'cardealer-helper' ),
				'description' => esc_html__( 'Enter description. Please ensure to add short content.', 'cardealer-helper' ),
				'param_name'  => 'description',
			),
			array(
				'type'       => 'vc_link',
				'heading'    => esc_html__( 'Read More Url', 'cardealer-helper' ),
				'param_name' => 'readmore',
			),
		);

		$params = array_merge(
			$params,
			cdhl_iconpicker(),
			array(
				array(
					'type'       => 'attach_image',
					'heading'    => esc_html__( 'Background Image', 'cardealer-helper' ),
					'param_name' => 'box_bg_image',
				),
			)
		);

		vc_map(
			array(
				'name'                    => esc_html__( 'Potenza Call To Action', 'cardealer-helper' ),
				'description'             => esc_html__( 'Potenza Call To Action', 'cardealer-helper' ),
				'base'                    => 'cd_call_to_action',
				'class'                   => 'cardealer_helper_element_wrapper',
				'controls'                => 'full',
				'icon'                    => cardealer_vc_shortcode_icon( 'cd_call_to_action' ),
				'category'                => esc_html__( 'Potenza', 'cardealer-helper' ),
				'show_settings_on_create' => true,
				'params'                  => $params,
			)
		);
	}
}
add_action( 'vc_before_init', 'cdhl_call_to_action_shortcode_vc_map' );
