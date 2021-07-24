<?php
/**
 * CarDealer Visual Composer testimonials Shortcode
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package car-dealer-helper/functions
 */

add_shortcode( 'cd_testimonials', 'cdhl_shortcode_testimonials' );

/**
 * Shortcode HTML.
 *
 * @param array $atts .
 */
function cdhl_shortcode_testimonials( $atts ) {
	$atts = shortcode_atts(
		array(
			'style'                   => 'style-1',
			'no_of_testimonials'      => 10,
			'css'                     => '',
			'data_md_items'           => 4,
			'data_sm_items'           => 2,
			'data_xs_items'           => 1,
			'data_xx_items'           => 1,
			'data_space'              => 20,
			'testimonials_slider_opt' => '',
			'element_id'              => uniqid( 'cd_testimonials_' ),
		),
		$atts
	);
	extract( $atts );

	$args      = array(
		'post_type'      => 'testimonials',
		'posts_per_page' => $no_of_testimonials,
	);
	$the_query = new WP_Query( $args );
	if ( ! $the_query->have_posts() || 0 === (int) $no_of_testimonials ) {
		return null;
	}
	$cnt = $the_query->post_count;
	$css = $atts['css'];

	$custom_class = vc_shortcode_custom_css_class( $css, ' ' );

	if ( 'style-1' === (string) $atts['style'] ) {
		$class_style = 'testimonial-1';
		$extra_class = 'white-bg page';
	} elseif ( 'style-2' === (string) $atts['style'] ) {
		$class_style = 'testimonial-3';
		$extra_class = 'white-bg';
	} elseif ( 'style-3' === (string) $atts['style'] ) {
		$class_style = 'testimonial-2';
		$extra_class = 'bg-1 bg-overlay-black-70';
	} elseif ( 'style-4' === (string) $atts['style'] ) {
		$class_style = 'testimonial-4';
		$extra_class = 'white-bg';
	}if ( 'style-5' === (string) $atts['style'] ) {
		$class_style = 'testimonial-5';
		$extra_class = 'white-bg page';
	}

	if ( $no_of_testimonials > 1 ) {
		$parent_class = 'owl-carousel';

		$testimonials_slider_opt = explode( ',', $testimonials_slider_opt );
		$arrow                   = 'false';
		$autoplay                = 'false';
		$dots                    = 'false';
		$data_loop               = 'false';
		foreach ( $testimonials_slider_opt as $option ) {
			if ( 'Autoplay' === (string) $option ) {
				$autoplay = 'true';
			} elseif ( 'Loop' === (string) $option ) {
				$data_loop = 'true';
			} elseif ( 'Navigation Dots' === (string) $option && $cnt >= $data_md_items ) {
				$dots = 'true';
			} elseif ( 'Navigation Arrow' === (string) $option && $cnt >= $data_md_items ) {
				$arrow = 'true';
			}
		}
		$data_attr  = ' data-nav-arrow=' . esc_attr( $arrow );
		$data_attr .= ' data-nav-dots=' . esc_attr( $dots );
		$data_attr .= ' data-items=' . esc_attr( $data_md_items );
		$data_attr .= ' data-md-items=' . esc_attr( $data_md_items );
		$data_attr .= ' data-sm-items=' . esc_attr( $data_sm_items );
		$data_attr .= ' data-xs-items=' . esc_attr( $data_xs_items );
		$data_attr .= ' data-xx-items=' . esc_attr( $data_xx_items );
		$data_attr .= ' data-space=' . esc_attr( $data_space );
		$data_attr .= ' data-autoplay=' . esc_attr( $autoplay );
		$data_attr .= ' data-loop=' . esc_attr( $data_loop );
		$data_attr .= ' data-lazyload=' . esc_attr( cardealer_lazyload_enabled() );
	} else {
		$parent_class = 'carousel';
		$data_attr    = '';
	}

	$element_classes = array(
		'testimonial',
		$class_style,
		'page',
		$custom_class,
		$extra_class,
	);
	$element_classes = implode( ' ', array_filter( array_unique( $element_classes ) ) );
	$lazyload_class  = ( 'true' === (string) $data_loop ) ? 'cardealer-lazy-load owl-lazy' : 'cardealer-lazy-load';
	if ( $the_query->post_count < $data_md_items ) {
		$lazyload_class = 'cardealer-lazy-load';}
	ob_start();
	?>
	<div id="cd_testimonials_<?php echo esc_attr( $element_id ); ?>" class="<?php echo esc_attr( $element_classes ); ?>">
		<?php
		if ( 'style-1' === (string) $atts['style'] || 'style-5' === (string) $atts['style'] ) {
			?>
			<div class="<?php echo esc_attr( $parent_class ); ?>" <?php echo $data_attr; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>>
				<?php
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					global $post;
					$content        = get_post_meta( get_the_ID(), 'content', $single = true );
					$designation    = get_post_meta( get_the_ID(), 'designation', $single = true );
					$profile_img_id = get_post_meta( get_the_ID(), 'profile_image', $single = true );
					$profile_img    = wp_get_attachment_image_src( $profile_img_id, 'thumbnail' );

					if ( $content ) {
						?>
						<div class="item">
							<div class="testimonial-block text-center">
								<div class="testimonial-image">
									<?php
									if ( has_post_thumbnail() ) {
										$img_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ), 'cardealer-blog-thumb' );
										if ( cardealer_lazyload_enabled() ) {
											?>
											<img class="img-responsive <?php echo esc_attr( $lazyload_class ); ?>" data-src="<?php echo esc_html( $img_url ); ?>" src="<?php echo esc_url( LAZYLOAD_IMG ); ?>">
											<?php
										} else {
											the_post_thumbnail( 'cardealer-blog-thumb' );
										}
									}
									?>
								</div> 
								<div class="testimonial-box">
									<div class="testimonial-avtar">
										<?php
										$img_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ), 'cardealer-blog-thumb' );
										if ( cardealer_lazyload_enabled() ) {
											?>
											<img class="img-responsive <?php echo esc_attr( $lazyload_class ); ?>" src="<?php echo esc_url( LAZYLOAD_IMG ); ?>" data-src="<?php echo esc_url( $profile_img[0] ); ?>" alt="" width="<?php echo esc_attr( $profile_img[1] ); ?>" height="<?php echo esc_attr( $profile_img[2] ); ?>">
										<?php } else { ?>
											<img class="img-responsive" src="<?php echo esc_url( $profile_img[0] ); ?>" alt="" width="<?php echo esc_attr( $profile_img[1] ); ?>" height="<?php echo esc_attr( $profile_img[2] ); ?>">
										<?php } ?>
										<h6><?php the_title(); ?></h6>
										<?php echo ( $designation ) ? ' <span>' . esc_html( $designation ) . '</span>' : ''; ?>
									</div>
									<div class="testimonial-content">               
										<p><?php echo $content; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></p>
										<i class="fas fa-quote-right"></i>
									</div>
								</div>
							</div>
						</div>
						<?php
					}
				}
				?>
			</div>
			<?php
		} elseif ( 'style-2' === (string) $atts['style'] ) {
			?>
			<div class="<?php echo esc_attr( $parent_class ); ?>" <?php echo $data_attr; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>>
				<?php
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					global $post;
					$content        = get_post_meta( get_the_ID(), 'content', $single = true );
					$designation    = get_post_meta( get_the_ID(), 'designation', $single = true );
					$profile_img_id = get_post_meta( get_the_ID(), 'profile_image', $single = true );
					$profile_img    = wp_get_attachment_image_src( $profile_img_id, 'medium' );
					if ( $content ) {
						?>
						<div class="item">
							<div class="testimonial-block">
								<div class="row">
									<div class="col-lg-3 col-md-3 col-sm-3">
										<div class="testimonial-avtar">
											<?php
											$img_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ), 'cardealer-blog-thumb' );
											if ( cardealer_lazyload_enabled() ) {
												?>
												<img class="img-responsive <?php echo esc_attr( $lazyload_class ); ?>" data-src="<?php echo esc_url( $profile_img[0] ); ?>" src="<?php echo esc_url( LAZYLOAD_IMG ); ?>" alt="" width="<?php echo esc_attr( $profile_img[1] ); ?>" height="<?php echo esc_attr( $profile_img[2] ); ?>">
											<?php } else { ?>
												<img class="img-responsive" src="<?php echo esc_url( $profile_img[0] ); ?>" alt="" width="<?php echo esc_attr( $profile_img[1] ); ?>" height="<?php echo esc_attr( $profile_img[2] ); ?>">
											<?php } ?>
										</div>   
									</div>  
									<div class="col-lg-9 col-md-9 col-sm-9">
										<div class="testimonial-content">
											<p><i class="fas fa-quote-left"></i> <span><?php echo $content; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></span> <i class="fas fa-quote-right pull-right"></i></p>
										</div> 
										<div class="testimonial-info">
											<h6><?php the_title(); ?></h6>
											<?php echo ( $designation ) ? ' <span>' . esc_html( $designation ) . '</span>' : ''; ?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php
					}
				}
				?>
			</div>
			<?php
		} elseif ( 'style-3' === (string) $atts['style'] ) {
			?>
			<div class="testimonial-center">
				<div class="<?php echo esc_attr( $parent_class ); ?>" <?php echo $data_attr; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>>
						<?php
						while ( $the_query->have_posts() ) {
							$the_query->the_post();
							global $post;
							$content        = get_post_meta( get_the_ID(), 'content', $single = true );
							$designation    = get_post_meta( get_the_ID(), 'designation', $single = true );
							$profile_img_id = get_post_meta( get_the_ID(), 'profile_image', $single = true );
							$profile_img    = wp_get_attachment_image_src( $profile_img_id, 'thumbnail' );
							if ( $content ) {
								?>
								<div class="item">
									<div class="testimonial-block">
										<div class="testimonial-content">
											<i class="fas fa-quote-left"></i>
											<p> <?php echo $content; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></p>
										</div>
										<div class="testimonial-info">
											<div class="testimonial-avatar">
											<?php if ( cardealer_lazyload_enabled() ) { ?>
												<img class="img-responsive <?php echo esc_attr( $lazyload_class ); ?>" data-src="<?php echo esc_url( $profile_img[0] ); ?>" src="<?php echo esc_url( LAZYLOAD_IMG ); ?>" alt="" width="<?php echo esc_attr( $profile_img[1] ); ?>" height="<?php echo esc_attr( $profile_img[2] ); ?>">
											<?php } else { ?>
												<img class="img-responsive" src="<?php echo esc_url( $profile_img[0] ); ?>" alt="" width="<?php echo esc_attr( $profile_img[1] ); ?>" height="<?php echo esc_attr( $profile_img[2] ); ?>">
											<?php } ?>	
											</div>
											<div class="testimonial-name">
												<h6 class="text-white"><?php the_title(); ?></h6>
												<?php echo ( $designation ) ? ' <span> |' . esc_html( $designation ) . '</span>' : ''; ?>
											</div>
										</div>
									</div>
								</div>
								<?php
							}
						}
						?>
				</div>
			</div>
			<?php
		} elseif ( 'style-4' === (string) $atts['style'] ) {
			?>
			<div class="<?php echo esc_attr( $parent_class ); ?>" <?php echo $data_attr; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>>
				<?php
				while ( $the_query->have_posts() ) {
					$the_query->the_post();

					global $post;
					$content     = get_post_meta( get_the_ID(), 'content', $single = true );
					$designation = get_post_meta( get_the_ID(), 'designation', $single = true );
					if ( $content ) {
						?>
						<div class="item">
							<div class="testimonial-block text-center">
								<i class="fas fa-quote-left"></i>
								<p><?php echo $content; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></p>
								<h6 class="text-red"><?php the_title(); ?></h6>
							</div>
						</div>
						<?php
					}
				}
				?>
			</div>
			<?php
		}
		?>
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
function cdhl_testimonials_shortcode_vc_map() {
	if ( function_exists( 'vc_map' ) ) {
		vc_map(
			array(
				'name'                    => esc_html__( 'Potenza Testimonials', 'cardealer-helper' ),
				'description'             => esc_html__( 'Potenza Testimonials', 'cardealer-helper' ),
				'base'                    => 'cd_testimonials',
				'class'                   => 'cardealer_helper_element_wrapper',
				'controls'                => 'full',
				'icon'                    => cardealer_vc_shortcode_icon( 'cd_testimonials' ),
				'category'                => esc_html__( 'Potenza', 'cardealer-helper' ),
				'show_settings_on_create' => true,
				'params'                  => array(
					array(
						'type'        => 'cd_radio_image',
						'heading'     => esc_html__( 'Style', 'cardealer-helper' ),
						'description' => esc_html__( 'Select Testimonials style.', 'cardealer-helper' ),
						'param_name'  => 'style',
						'options'     => cdhl_get_shortcode_param_data( 'cd_testimonials' ),
					),
					array(
						'type'        => 'cd_number_min_max',
						'heading'     => esc_html__( 'No. of Testimonials', 'cardealer-helper' ),
						'param_name'  => 'no_of_testimonials',
						'value'       => '',
						'min'         => '1',
						'max'         => '9999',
						'description' => esc_html__( 'Select count of testimonials to display.', 'cardealer-helper' ),
					),
					array(
						'type'       => 'cd_number_min_max',
						'class'      => '',
						'heading'    => esc_html__( 'Number of slide desktops', 'cardealer-helper' ),
						'param_name' => 'data_md_items',
						'min'        => '1',
						'max'        => '9999',
					),
					array(
						'type'       => 'cd_number_min_max',
						'class'      => '',
						'heading'    => esc_html__( 'Number of slide tablets', 'cardealer-helper' ),
						'param_name' => 'data_sm_items',
						'min'        => '1',
						'max'        => '9999',
					),
					array(
						'type'       => 'cd_number_min_max',
						'class'      => '',
						'heading'    => esc_html__( 'Number of slide mobile landscape', 'cardealer-helper' ),
						'param_name' => 'data_xs_items',
						'min'        => '1',
						'max'        => '9999',
					),
					array(
						'type'       => 'cd_number_min_max',
						'class'      => '',
						'heading'    => esc_html__( 'Number of slide mobile portrait', 'cardealer-helper' ),
						'param_name' => 'data_xx_items',
						'min'        => '1',
						'max'        => '9999',

					),
					array(
						'type'       => 'cd_number_min_max',
						'class'      => '',
						'heading'    => esc_html__( 'Space between two slide', 'cardealer-helper' ),
						'param_name' => 'data_space',
						'min'        => '1',
						'max'        => '9999',
					),
					array(
						'type'       => 'checkbox',
						'heading'    => esc_html__( 'Slider style option', 'cardealer-helper' ),
						'param_name' => 'testimonials_slider_opt',
						'value'      => array(
							esc_html__( 'Navigation Arrow', 'cardealer-helper' ) => 'Navigation Arrow',
							esc_html__( 'Navigation Dots', 'cardealer-helper' ) => 'Navigation Dots',
							esc_html__( 'Autoplay', 'cardealer-helper' )        => 'Autoplay',
							esc_html__( 'Loop', 'cardealer-helper' )            => 'Loop',
						),
					),
					array(
						'type'       => 'css_editor',
						'heading'    => esc_html__( 'CSS box', 'cardealer-helper' ),
						'param_name' => 'css',
						'group'      => esc_html__( 'Design Options', 'cardealer-helper' ),
					),
				),
			)
		);
	}
}
add_action( 'vc_before_init', 'cdhl_testimonials_shortcode_vc_map' );
