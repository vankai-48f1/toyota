<?php
/**
 * CarDealer Visual Composer video slider Shortcode
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package car-dealer-helper/functions
 */

add_shortcode( 'cd_video_slider', 'cdhl_shortcode_video_slider' );

/**
 * Shortcode HTML.
 *
 * @param array $atts .
 */
function cdhl_shortcode_video_slider( $atts ) {
	$atts = shortcode_atts(
		array(
			'videos'     => '',
			'element_id' => uniqid( 'cd_quick_links_' ),
		),
		$atts
	);
	extract( $atts );
	if ( empty( $atts['videos'] ) ) {
		return;
	}
	$video_links     = vc_param_group_parse_atts( $videos );
	$element_classes = array(
		'video-slider',
	);
	$element_classes = implode( ' ', array_filter( array_unique( $element_classes ) ) );
	ob_start();
	?>
	<div id="cd_video_slider_<?php echo esc_attr( $element_id ); ?>" class="slick_sider <?php echo esc_attr( $element_classes ); ?>">
		<div class="sliderMain">
			<?php
			foreach ( $video_links as $link ) {
				if ( ! empty( $link['video_url'] ) ) {
					$video_type = cdhl_check_video_type( $link['video_url'] );
					if ( ! empty( $video_type ) && 'other' !== (string) $video_type ) {
						if ( 'youtube' === (string) $video_type ) {
							$video_id = cdhl_get_video_id( 'youtube', $link['video_url'] );
							if ( empty( $video_id ) ) {
								continue;
							}
							?>
							<div class="item youtube">
								<iframe id="<?php echo esc_attr( $video_type ); ?>_<?php echo esc_attr( $element_id ); ?>" width="920" height="518" src="//www.youtube.com/embed/<?php echo esc_attr( $video_id ); ?>?rel=0&amp;enablejsapi=1&showinfo=0&loop=1" frameborder="0" allowfullscreen></iframe> <!-- Make sure to enable the API by appending the "&enablejsapi=1" parameter onto the URL. -->
							</div>
							<?php
						} else {
							$video_id = cdhl_get_video_id( 'vimeo', $link['video_url'] );
							if ( empty( $video_id ) ) {
								continue;
							}
							?>
								<div class="item vimeo">
									<iframe id="<?php echo esc_attr( $video_type ); ?>_<?php echo esc_attr( $element_id ); ?>" width="920" height="518" src="https://player.vimeo.com/video/<?php echo esc_attr( $video_id ); ?>?byline=0&amp;portrait=0&amp;api=1&loop=1" frameborder="0" allowfullscreen></iframe> <!-- Make sure to enable the API by appending the "&enablejsapi=1" parameter onto the URL. -->
							</div>
							<?php
						}
					}
				}
			}
			?>

		</div>
		<div class="sliderSidebar">
			<?php
			foreach ( $video_links as $link ) {
				if ( ! empty( $link['video_url'] ) ) {
					$video_type = cdhl_check_video_type( $link['video_url'] );
					if ( ! empty( $video_type ) ) {
						if ( 'vimeo' === (string) $video_type ) {
							$video_id = cdhl_get_video_id( 'vimeo', $link['video_url'] );
							if ( empty( $video_id ) ) {
								continue;
							}
							$url = cdhl_get_vimeo_thumb_image_url( $video_id, '320x180' );
						} elseif ( 'youtube' === (string) $video_type ) {
							$video_id = cdhl_get_video_id( 'youtube', $link['video_url'] );
							if ( empty( $video_id ) ) {
								continue;
							}
							$url = 'http://i3.ytimg.com/vi/' . $video_id . '/mqdefault.jpg';
						} else {
							continue;
						}
						?>
						<div class="item"><img src="<?php echo esc_attr( $url ); ?>" /></div>
						<?php
					}
				}
			}
			?>
		</div>
	</div>
	<?php
	/* Restore original Post Data */
	wp_reset_postdata();

	return ob_get_clean();
}

/**
 * Shortcode mapping.
 *
 * @return void
 */
function cdhl_video_slider_shortcode_vc_map() {
	if ( function_exists( 'vc_map' ) ) {
		vc_map(
			array(
				'name'                    => esc_html__( 'Potenza Video Slider', 'cardealer-helper' ),
				'description'             => esc_html__( 'Potenza Video Slider', 'cardealer-helper' ),
				'base'                    => 'cd_video_slider',
				'class'                   => 'cardealer_helper_element_wrapper',
				'controls'                => 'full',
				'icon'                    => cardealer_vc_shortcode_icon( 'cd_video_slider' ),
				'category'                => esc_html__( 'Potenza', 'cardealer-helper' ),
				'show_settings_on_create' => true,
				'params'                  => array(
					array(
						'type'       => 'param_group',
						'param_name' => 'videos',
						'group'      => esc_html__( 'Video Links', 'cardealer-helper' ),
						'params'     => array(
							array(
								'type'             => 'textfield',
								'heading'          => esc_html__( 'Video URL', 'cardealer-helper' ),
								'param_name'       => 'video_url',
								'tooltip'          => esc_html__( 'Enter Youtube or Vimeo video links only.', 'cardealer-helper' ),
								'edit_field_class' => 'vc_col-sm-12 vc_column',
							),
						),
						'callbacks'  => array(
							'after_add' => 'vcChartParamAfterAddCallback',
						),
					),
				),
			)
		);
	}
}
add_action( 'vc_before_init', 'cdhl_video_slider_shortcode_vc_map' );
