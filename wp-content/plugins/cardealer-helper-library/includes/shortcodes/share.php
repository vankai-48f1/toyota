<?php
/**
 * CarDealer Visual Composer share Shortcode
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package car-dealer-helper/functions
 */

add_shortcode( 'cardealer_share', 'cdhl_shortcode_share' );

/**
 * Shortcode HTML.
 *
 * @param array $atts .
 */
function cdhl_shortcode_share( $atts ) {
	$atts = shortcode_atts(
		array(
			'add_color'          => 'false',
			'back_color'         => 'none',
			'font_color'         => 'inherit',
			'add_pdf'            => 'true',
			'pdf_label'          => esc_html__( 'See PDF', 'cardealer-helper' ),
			'pdf_file'           => '',
			'add_video'          => 'true',
			'video_label'        => esc_html__( 'See Video', 'cardealer-helper' ),
			'video_url'          => '',
			'social_icons_label' => esc_html__( 'Share', 'cardealer-helper' ),
			'social_icons'       => '',
			'css'                => '',
			'element_id'         => uniqid( 'cardealer_share_' ),
		),
		$atts
	);
	extract( $atts );

	$css             = $atts['css'];
	$custom_class    = vc_shortcode_custom_css_class( $css, ' ' );
	$color_class     = ( ! empty( $back_color ) && 'none' !== (string) $back_color ) ? 'cd-back-color' : '';
	$element_classes = array(
		$custom_class,
		$color_class,
	);
	$element_classes = implode( ' ', array_filter( array_unique( $element_classes ) ) );
	ob_start();
	?>
	<div id="<?php echo esc_attr( $element_id ); ?>" class="overview-share <?php echo esc_attr( $element_classes ); ?>">
		<?php
		if ( 'true' === (string) $add_pdf && ! empty( $pdf_file ) ) {
			?>
			<div class="pdf" style="background:<?php echo esc_attr( $back_color ); ?>;color:<?php echo esc_attr( $font_color ); ?>">
				<div class="icon"> 
					<i class="far fa-file-pdf" style="color:<?php echo esc_attr( $font_color ); ?>"></i>
				</div>
				<div class="info">
					<a href="<?php echo esc_url( wp_get_attachment_url( $pdf_file ) ); ?>" style="color:<?php echo esc_attr( $font_color ); ?>" download>
					<?php echo apply_filters( 'share_shortcode_pdf_label', esc_html( $pdf_label ) ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>
					</a>
				</div>
			</div>
			<?php
		}
		if ( 'true' === (string) $add_video && ! empty( $video_url ) && ( strpos( $video_url, 'youtube' ) > 0 || strpos( $video_url, 'vimeo' ) > 0 || strpos( $video_url, 'youtu.be' ) > 0 ) ) {
			?>
		<div class="see-video" style="background:<?php echo esc_attr( $back_color ); ?>;color:<?php echo esc_attr( $font_color ); ?>">
			<div class="icon"> 
				<i class="fas fa-play" style="color:<?php echo esc_attr( $font_color ); ?>"></i>
			</div>
			<div class="info">
				<a class="popup-youtube" href="<?php echo esc_url( $video_url ); ?>" style="color:<?php echo esc_attr( $font_color ); ?>"> 
				<?php echo apply_filters( 'share_shortcode_video_label', esc_html( $video_label ) ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>
				</a>
			</div>
		</div>
			<?php
		}
		if ( ! empty( $social_icons ) ) {
			?>
			<div class="share" style="background:<?php echo esc_attr( $back_color ); ?>;color:<?php echo esc_attr( $font_color ); ?>">
				<span style="color:<?php echo esc_attr( $font_color ); ?>"><?php echo apply_filters( 'share_shortcode_video_label', esc_html( $social_icons_label ) ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></span>
				<ul class="list-unstyled">
					<?php
					$social_icons = explode( ',', $social_icons );
					foreach ( $social_icons as $icon ) {
						?>
						<li><a href="javascript:void(0)" class="<?php echo esc_attr( str_replace( '-', '', $icon ) ); ?>-share"data-title="<?php echo esc_attr( get_the_title() ); ?>" data-url="<?php echo esc_url( get_permalink() ); ?>"> <i class="fa fa-<?php echo esc_attr( $icon ); ?>" style="color:<?php echo esc_attr( $font_color ); ?>"></i> </a> </li>
						<?php
					}
					?>
				</ul>
			</div>
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
function cdhl_share_shortcode_vc_map() {
	if ( function_exists( 'vc_map' ) ) {
		$params = array(
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Add Color?', 'cardealer-helper' ),
				'description' => esc_html__( 'Check this to add background color to shortcode elements.', 'cardealer-helper' ),
				'param_name'  => 'add_color',
			),
			array(
				'type'        => 'colorpicker',
				'class'       => 'pgs_icon',
				'param_name'  => 'font_color',
				'value'       => '#eee',
				'heading'     => esc_html__( 'Font Color', 'cardealer-helper' ),
				'description' => esc_html__( 'Select font color for shortcode elements.', 'cardealer-helper' ),
				'dependency'  => array(
					'element' => 'add_color',
					'value'   => 'true',
				),
			),
			array(
				'type'        => 'colorpicker',
				'class'       => 'pgs_icon',
				'param_name'  => 'back_color',
				'value'       => '#ffffff',
				'heading'     => esc_html__( 'Background Color', 'cardealer-helper' ),
				'description' => esc_html__( 'Select background color for shortcode elements.', 'cardealer-helper' ),
				'dependency'  => array(
					'element' => 'add_color',
					'value'   => 'true',
				),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Add PDF?', 'cardealer-helper' ),
				'description' => esc_html__( 'Check this to add PDF option.', 'cardealer-helper' ),
				'param_name'  => 'add_pdf',
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'PDF Label', 'cardealer-helper' ),
				'description' => esc_html__( 'Enter label for PDF to be uploaded.', 'cardealer-helper' ),
				'param_name'  => 'pdf_label',
				'default'     => esc_html__( 'See PDF', 'cardealer-helper' ),
				'dependency'  => array(
					'element' => 'add_pdf',
					'value'   => 'true',
				),
			),
			array(
				'type'        => 'cd_media_upload',
				'class'       => '',
				'heading'     => esc_html__( 'Attach PDF File', 'cardealer-helper' ),
				'param_name'  => 'pdf_file',
				'description' => esc_html__( 'Upload only .pdf files.', 'cardealer-helper' ),
				'dependency'  => array(
					'element' => 'add_pdf',
					'value'   => 'true',
				),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Add Video?', 'cardealer-helper' ),
				'description' => esc_html__( 'Check this to add video option.', 'cardealer-helper' ),
				'param_name'  => 'add_video',
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Video Label', 'cardealer-helper' ),
				'description' => esc_html__( 'Enter label for Video to be displayed.', 'cardealer-helper' ),
				'param_name'  => 'video_label',
				'default'     => esc_html__( 'See Video', 'cardealer-helper' ),
				'dependency'  => array(
					'element' => 'add_video',
					'value'   => 'true',
				),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Video URL', 'cardealer-helper' ),
				'description' => esc_html__( 'Enter video URL to display video. Enter only youtube or vimeo video link.', 'cardealer-helper' ),
				'param_name'  => 'video_url',
				'dependency'  => array(
					'element' => 'add_video',
					'value'   => 'true',
				),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Social Icons Label', 'cardealer-helper' ),
				'description' => esc_html__( 'Enter label for social icons to be displayed.', 'cardealer-helper' ),
				'param_name'  => 'social_icons_label',
				'default'     => esc_html__( 'Share', 'cardealer-helper' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Select Social Icons', 'cardealer-helper' ),
				'description' => esc_html__( 'Select social media icons to include within share', 'cardealer-helper' ),
				'param_name'  => 'social_icons',
				'value'       => array(
					esc_html__( 'Facebook', 'cardealer-helper' )    => 'facebook',
					esc_html__( 'Twitter', 'cardealer-helper' )     => 'twitter',
					esc_html__( 'LinkedIn', 'cardealer-helper' )    => 'linkedin',
					esc_html__( 'Google Plus', 'cardealer-helper' ) => 'google-plus',
					esc_html__( 'Pinterest', 'cardealer-helper' )   => 'pinterest',
				),
			),
			array(
				'type'       => 'css_editor',
				'heading'    => esc_html__( 'CSS box', 'cardealer-helper' ),
				'param_name' => 'css',
				'group'      => esc_html__( 'Design Options', 'cardealer-helper' ),
			),
		);

		vc_map(
			array(
				'name'                    => esc_html__( 'Potenza Share', 'cardealer-helper' ),
				'description'             => esc_html__( 'Potenza Share', 'cardealer-helper' ),
				'base'                    => 'cardealer_share',
				'class'                   => 'cardealer_helper_element_wrapper',
				'controls'                => 'full',
				'icon'                    => cardealer_vc_shortcode_icon( 'cardealer_share' ),
				'category'                => esc_html__( 'Potenza', 'cardealer-helper' ),
				'show_settings_on_create' => true,
				'params'                  => $params,
			)
		);
	}
}
add_action( 'vc_before_init', 'cdhl_share_shortcode_vc_map' );
