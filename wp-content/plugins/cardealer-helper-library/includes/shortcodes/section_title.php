<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * CarDealer Visual Composer section title Shortcode
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package car-dealer-helper/functions
 */

add_shortcode( 'cd_section_title', 'cdhl_shortcode_section_title' );

/**
 * Shortcode HTML.
 *
 * @param array $atts .
 * @param array $content .
 */
function cdhl_shortcode_section_title( $atts, $content ) {
	$atts = shortcode_atts(
		array(
			'style'              => 'style_1',
			'section_number'     => 1,
			'heading_tag'        => 'h2',
			'section_number_tag' => 'h2',
			'section_title'      => '',
			'section_sub_title'  => '',
			'title_align'        => 'text-center',
			'hide_seperator'     => false,
			'show_content'       => false,
		),
		$atts
	);

	extract( $atts );

	if ( empty( $section_title ) ) {
		return;
	}

	ob_start();
	?>
	<div class="section-title <?php echo esc_attr( $title_align ); ?> <?php echo esc_attr( $style ); ?>">
		<?php
		if ( 'style_2' === (string) $style ) {
			?>
			<<?php echo $section_number_tag; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>><?php echo esc_html( $section_number ); ?></<?php echo $section_number_tag; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>>
			<span><?php echo esc_html( $section_title ); ?></span>
			<?php
			if ( isset( $hide_seperator ) && false === (bool) $hide_seperator ) {
				?>
				<div class="separator"></div>
				<?php
			}
		} else {
			if ( ! empty( $section_title ) ) {
				?>
				<span><?php echo esc_html( $section_sub_title ); ?></span>
				<?php
			}
			?>
			<<?php echo $heading_tag; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>><?php echo esc_html( $section_title ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></<?php echo $heading_tag; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>>
			<?php
			if ( isset( $hide_seperator ) && false === (bool) $hide_seperator ) {
				?>
				<div class="separator"></div>
				<?php
			}
			if ( true === (bool) $show_content && ! empty( $content ) ) {
				?>
				<p><?php echo do_shortcode( $content ); ?></p>
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
function cdhl_section_title_shortcode_vc_map() {
	if ( function_exists( 'vc_map' ) ) {
		vc_map(
			array(
				'name'                    => esc_html__( 'Potenza Section Title', 'cardealer-helper' ),
				'description'             => esc_html__( 'Potenza Section Title', 'cardealer-helper' ),
				'base'                    => 'cd_section_title',
				'class'                   => '_helper_element_wrapper',
				'controls'                => 'full',
				'icon'                    => cardealer_vc_shortcode_icon( 'cd_section_title' ),
				'category'                => esc_html__( 'Potenza', 'cardealer-helper' ),
				'show_settings_on_create' => true,
				'params'                  => array(
					array(
						'type'       => 'cd_radio_image_2',
						'heading'    => esc_html__( 'Style', 'cardealer-helper' ),
						'param_name' => 'style',
						'options'    => array(
							array(
								'value' => 'style_1',
								'title' => 'Style 1',
								'image' => trailingslashit( CDHL_VC_URL ) . 'vc_images/options/cd_section_title/style_1.png',
							),
							array(
								'value' => 'style_2',
								'title' => 'Style 2',
								'image' => trailingslashit( CDHL_VC_URL ) . 'vc_images/options/cd_section_title/style_2.png',
							),
						),
					),
					array(
						'type'        => 'textfield',
						'class'       => '',
						'heading'     => esc_html__( 'Section Title', 'cardealer-helper' ),
						'description' => esc_html__( 'Enter section title.', 'cardealer-helper' ),
						'param_name'  => 'section_title',
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Heading Tag', 'cardealer-helper' ),
						'description' => esc_html__( 'Tag to be used for section title display.', 'cardealer-helper' ),
						'param_name'  => 'heading_tag',
						'value'       => array(
							esc_html__( 'H1', 'cardealer-helper' ) => 'h1',
							esc_html__( 'H2', 'cardealer-helper' ) => 'h2',
							esc_html__( 'H3', 'cardealer-helper' ) => 'h3',
							esc_html__( 'H4', 'cardealer-helper' ) => 'h4',
							esc_html__( 'H5', 'cardealer-helper' ) => 'h5',
							esc_html__( 'H6', 'cardealer-helper' ) => 'h6',
						),
						'save_always' => true,
						'dependency'  => array(
							'element' => 'style',
							'value'   => 'style_1',
						),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Section Number Tag', 'cardealer-helper' ),
						'description' => esc_html__( 'Tag to be used for section number display.', 'cardealer-helper' ),
						'param_name'  => 'section_number_tag',
						'value'       => array(
							esc_html__( 'H1', 'cardealer-helper' ) => 'h1',
							esc_html__( 'H2', 'cardealer-helper' ) => 'h2',
							esc_html__( 'H3', 'cardealer-helper' ) => 'h3',
							esc_html__( 'H4', 'cardealer-helper' ) => 'h4',
							esc_html__( 'H5', 'cardealer-helper' ) => 'h5',
							esc_html__( 'H6', 'cardealer-helper' ) => 'h6',
						),
						'save_always' => true,
						'dependency'  => array(
							'element' => 'style',
							'value'   => 'style_2',
						),
					),
					array(
						'type'        => 'cd_number_min_max',
						'class'       => '',
						'heading'     => esc_html__( 'Section Number', 'cardealer-helper' ),
						'description' => esc_html__( 'Enter section title number.', 'cardealer-helper' ),
						'min'         => 1,
						'max'         => 99999,
						'param_name'  => 'section_number',
						'dependency'  => array(
							'element' => 'style',
							'value'   => 'style_2',
						),
					),
					array(
						'type'        => 'textfield',
						'class'       => '',
						'heading'     => esc_html__( 'Section Subtitle', 'cardealer-helper' ),
						'description' => esc_html__( 'Enter section subtitle.', 'cardealer-helper' ),
						'param_name'  => 'section_sub_title',
						'dependency'  => array(
							'element' => 'style',
							'value'   => 'style_1',
						),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Title Align', 'cardealer-helper' ),
						'param_name'  => 'title_align',
						'value'       => array(
							esc_html__( 'left', 'cardealer-helper' ) => 'text-left',
							esc_html__( 'Center', 'cardealer-helper' ) => 'text-center',
							esc_html__( 'Right', 'cardealer-helper' ) => 'text-right',
						),
						'save_always' => true,
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Hide separator(border)?', 'cardealer-helper' ),
						'description' => esc_html__( 'This will hide separator(border) displayed after section title.', 'cardealer-helper' ),
						'param_name'  => 'hide_seperator',
					),
					array(
						'type'       => 'checkbox',
						'heading'    => esc_html__( 'Show content?', 'cardealer-helper' ),
						'param_name' => 'show_content',
						'dependency' => array(
							'element' => 'style',
							'value'   => 'style_1',
						),
					),
					array(
						'type'        => 'textarea_html',
						'class'       => '',
						'heading'     => esc_html__( 'Section Content', 'cardealer-helper' ),
						'description' => esc_html__( 'Enter content here.', 'cardealer-helper' ),
						'param_name'  => 'content',
						'dependency'  => array(
							'element' => 'show_content',
							'value'   => 'true',
						),
					),

				),
			)
		);
	}
}
add_action( 'vc_before_init', 'cdhl_section_title_shortcode_vc_map' );
