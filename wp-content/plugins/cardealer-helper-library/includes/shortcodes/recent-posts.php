<?php
/**
 * CarDealer Visual Composer recent posts Shortcode
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package car-dealer-helper/functions
 */

add_shortcode( 'cd_recent_posts', 'cdhl_recent_posts_shortcode' );

/**
 * Shortcode HTML.
 *
 * @param array $atts .
 */
function cdhl_recent_posts_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'post_type'           => 'post',
			'style'               => 'grid',
			'thumbnail_size' 	  => 'medium_large',
			'no_of_posts'         => 3,
			'ignore_sticky_posts' => true,
			'detail_link_title'   => esc_html__( 'Read more', 'cardealer-helper' ),
			'no_of_columns'       => 3,
			'css'                 => '',
			'element_id'          => uniqid( 'cd_recent_posts_' ),
		),
		$atts
	);
	extract( $atts );

	$args = array(
		'post_type'           => $post_type,
		'post_status'         => array( 'publish' ),
		'posts_per_page'      => $no_of_posts,
		'ignore_sticky_posts' => $ignore_sticky_posts,
	);

	$post_meta_info = new WP_Query( $args );
	if ( ! $post_meta_info->have_posts() ) {
		return null;
	}

	$css = $atts['css'];

	$custom_class = vc_shortcode_custom_css_class( $css, ' ' );

	$element_classes = array(
		'our-blog',
		'our-blog-' . $atts['style'],
		'page-section-pb',
		$custom_class,
	);
	$element_classes = implode( ' ', array_filter( array_unique( $element_classes ) ) );

	ob_start();
	add_filter( 'excerpt_more', 'cdhl_shortcode_excerpt_more', 20 );
	add_filter( 'excerpt_length', 'cdhl_shortcode_excerpt_length', 20 );
	?>

	<div id="<?php echo esc_attr( $element_id ); ?>" class="<?php echo esc_attr( $element_classes ); ?>">
		<div class="row">
			<?php
			while ( $post_meta_info->have_posts() ) {
				$post_meta_info->the_post();
				$excerpt = get_the_excerpt();
				$excerpt = cdhl_shortenString( $excerpt, 120, false, true );
				if ( 'grid' === (string) $style ) {

					if ( 2 === (int) $no_of_columns ) {
						$row_cols = 6;
					} elseif ( 3 === (int) $no_of_columns ) {
						$row_cols = 4;
					} elseif ( 4 >= $no_of_columns ) {
						$row_cols = 3;
					} else {
						$row_cols = 12;
					}

					$col_class = "col-lg-$row_cols col-md-$row_cols col-sm-$row_cols";
					$class     = '';
					if ( ! has_post_thumbnail() ) {
						$class = 'blog-no-image';
					}
					?>
					<div class="<?php echo esc_attr( $col_class ); ?>">
						<div class="blog-2 <?php echo esc_attr( $class ); ?>">
							<div class="blog-image">
							<?php
							$img_url = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ), $thumbnail_size );
							if ( $img_url ) {
								if ( cardealer_lazyload_enabled() ) {
									?>
									<img class="img-responsive cardealer-lazy-load" data-src="<?php echo esc_html( $img_url ); ?>" src="<?php echo esc_url( LAZYLOAD_IMG ); ?>">
									<?php
								} else {
									the_post_thumbnail( $thumbnail_size, array( 'class' => 'img-responsive' ) );
								}
							}
							?>
							<div class="date-box">
								<span><?php echo sprintf( '%1$s', esc_html( get_the_date( 'M Y' ) ) ); ?></span>
							</div>
						</div>
						<div class="blog-content">
							<div class="blog-admin-main">
								<div class="blog-admin">
									<?php echo get_avatar( get_the_author_meta( 'ID' ), 32 ); ?>
									<span><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo esc_html__( 'By', 'cardealer-helper' ) . ' ' . get_the_author(); ?></a></span>
								</div>
								<div class="blog-meta pull-right">
								<ul>
									<li>
										<a href="<?php echo esc_url( get_comments_link( get_the_ID() ) ); ?>"> <i class="fas fa-comment"></i><br /> 
											<?php
											$comments_count = wp_count_comments( get_the_ID() );
											echo esc_html( $comments_count->approved );
											?>
										</a>
									</li>
									<?php
									global $car_dealer_options;
									$facebook_share    = isset( $car_dealer_options['facebook_share'] ) ? $car_dealer_options['facebook_share'] : '';
									$twitter_share     = isset( $car_dealer_options['twitter_share'] ) ? $car_dealer_options['twitter_share'] : '';
									$linkedin_share    = isset( $car_dealer_options['linkedin_share'] ) ? $car_dealer_options['linkedin_share'] : '';
									$google_plus_share = isset( $car_dealer_options['google_plus_share'] ) ? $car_dealer_options['google_plus_share'] : '';
									$pinterest_share   = isset( $car_dealer_options['pinterest_share'] ) ? $car_dealer_options['pinterest_share'] : '';
									$whatsapp_share    = isset( $car_dealer_options['whatsapp_share'] ) ? $car_dealer_options['whatsapp_share'] : '';

									if ( ! empty( $facebook_share ) || ! empty( $twitter_share ) || ! empty( $linkedin_share ) || ! empty( $google_plus_share ) || ! empty( $pinterest_share ) || ! empty( $whatsapp_share ) ) {
										?>
										<li class="share"><a href="#"> <i class="fas fa-share-alt"></i><br /> ...</a>
											<div class="blog-social"> 
												<ul>
												<?php if ( $facebook_share ) { ?>
													<li> 
														<a href="#" class="facebook-share" data-title="<?php echo esc_attr( get_the_title() ); ?>" data-url="<?php echo esc_url( get_permalink() ); ?>"><i class="fab fa-facebook-f"></i></a>
													</li>
													<?php
												}
												if ( $twitter_share ) {
													?>
													<li>
														<a href="#"  data-title="<?php echo esc_attr( get_the_title() ); ?>" data-url="<?php echo esc_url( get_permalink() ); ?>" class="twitter-share"><i class="fab fa-twitter"></i></a>
													</li>
													<?php
												}
												if ( $linkedin_share ) {
													?>
													<li>
														<a href="#"  data-title="<?php echo esc_attr( get_the_title() ); ?>" data-url="<?php echo esc_url( get_permalink() ); ?>" class="linkedin-share"><i class="fab fa-linkedin-in"></i></a>
													</li>
													<?php
												}
												if ( $google_plus_share ) {
													?>
													<li>
														<a href="#"  data-title="<?php echo esc_attr( get_the_title() ); ?>" data-url="<?php echo esc_url( get_permalink() ); ?>" class="googleplus-share"><i class="fab fa-google-plus-g"></i></a>
													</li>
													<?php
												}
												if ( $pinterest_share ) {
													?>
													<li>
														<a href="#"  data-title="<?php echo esc_attr( get_the_title() ); ?>" data-url="<?php echo esc_url( get_permalink() ); ?>" data-image="<?php the_post_thumbnail_url( 'full' ); ?>" class="pinterest-share"><i class="fab fa-pinterest-p"></i></a>
													</li>
													<?php
												}
												if ( $whatsapp_share ) {
													if ( ! wp_is_mobile() ) {
														?>
															<li><a href="#" data-url="<?php echo esc_url( get_permalink() ); ?>"  class="whatsapp-share"><i class="fab fa-whatsapp"></i></a></li>
														<?php } else { ?>
															<li><a target="_blank" href="https://wa.me/?text=<?php echo esc_url( get_permalink() ); ?>"><i class="fab fa-whatsapp"></i></a></li>               
														<?php } ?>
												<?php } ?>
												</ul>
											</div>
										</li>
										<?php
									}
									?>
								</ul>
								</div>
							</div>
							<div class="blog-description text-center">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								<div class="separator"></div>
								<p><?php echo $excerpt; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></p>
							</div>
							</div>
						</div>
					</div>
					<?php
				} else {
					?>
					<div class="blog-1">
						<div class="row">
							<?php
							$class = 'col-lg-12 col-md-12 col-sm-12';
							if ( has_post_thumbnail() ) {
								$class = 'col-lg-6 col-md-6 col-sm-6';
								?>
								<div class="col-lg-6 col-md-6 col-sm-6">
									<div class="blog-image">
										<?php
										$img_url = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ), $thumbnail_size );
										if ( $img_url ) {
											if ( cardealer_lazyload_enabled() ) {
												?>
												<img class="img-responsive cardealer-lazy-load" data-src="<?php echo esc_html( $img_url ); ?>" src="<?php echo esc_url( LAZYLOAD_IMG ); ?>">
												<?php
											} else {
												the_post_thumbnail( $thumbnail_size, array( 'class' => 'img-responsive' ) );
											}
										}
										?>
									</div>
								</div>
							<?php } ?>
							<div class="<?php echo esc_attr( $class ); ?>">
								<div class="blog-content">
									<a href="<?php the_permalink(); ?>" class="link"><?php echo esc_html( get_the_title() ); ?></a>
									<span class="uppercase"><?php echo esc_html( get_the_date( 'F d, Y' ) ); ?> | <strong class="text-red"> <?php echo esc_html__( 'post by', 'cardealer-helper' ) . ' ' . get_the_author(); ?> </strong></span>
									<p><?php echo $excerpt; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></p>
									<a class="button border" href="<?php echo esc_attr( get_the_permalink() ); ?>"><?php echo esc_html( $atts['detail_link_title'] ); ?></a>
								</div>
							</div>
						</div>
					</div>
					<br />
					<?php
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
 * Excerpt more.
 *
 * @param string $more .
 */
function cdhl_shortcode_excerpt_more( $more ) {
	return '';
}

/**
 * Excerpt length.
 */
function cdhl_shortcode_excerpt_length() {
	return '35';
}


/**
 * Shortcode mapping.
 *
 * @return void
 */
function cdhl_recent_posts_shortcode_vc_map() {
	if ( function_exists( 'vc_map' ) ) {
		$thumbnail_sizes = cdhl_get_image_sizes();

		vc_map(
			array(
				'name'                    => esc_html__( 'Potenza Recent Posts', 'cardealer-helper' ),
				'description'             => esc_html__( 'Potenza Recent Posts', 'cardealer-helper' ),
				'base'                    => 'cd_recent_posts',
				'class'                   => 'cardealer_helper_element_wrapper',
				'controls'                => 'full',
				'icon'                    => cardealer_vc_shortcode_icon( 'cd_recent_posts' ),
				'category'                => esc_html__( 'Potenza', 'cardealer-helper' ),
				'show_settings_on_create' => true,
				'params'                  => array(
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Style', 'cardealer-helper' ),
						'param_name'  => 'style',
						'value'       => array(
							esc_html__( 'Grid View', 'cardealer-helper' ) => 'grid',
							esc_html__( 'List View', 'cardealer-helper' ) => 'list',
						),
						'description' => esc_html__( 'Select style.', 'cardealer-helper' ),
					),
					array(
						'type'        => 'dropdown',
						'param_name'  => 'thumbnail_size',
						'heading'     => esc_html__( 'Thumbnail Size', 'cardealer-helper' ),
						'description' => esc_html__( 'Choose thumbnail size.', 'cardealer-helper' ),
						'std'         => 'medium_large',
						'value'       => $thumbnail_sizes,
					),
					array(
						'type'        => 'textfield',
						'class'       => '',
						'heading'     => esc_html__( 'Post Counts', 'cardealer-helper' ),
						'description' => esc_html__( 'Enter number of posts to display.', 'cardealer-helper' ),
						'param_name'  => 'no_of_posts',
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Columns', 'cardealer-helper' ),
						'param_name'  => 'no_of_columns',
						'value'       => array(
							esc_html__( '2', 'cardealer-helper' ) => 2,
							esc_html__( '3', 'cardealer-helper' ) => 3,
							esc_html__( '4', 'cardealer-helper' ) => 4,
						),
						'description' => esc_html__( 'If sidebar is active then number of column can be set to 1 or 2.', 'cardealer-helper' ),
						'dependency'  => array(
							'element' => 'style',
							'value'   => array( 'grid' ),
						),
					),
					array(
						'type'        => 'textfield',
						'class'       => '',
						'heading'     => esc_html__( 'Detail Link Title', 'cardealer-helper' ),
						'description' => esc_html__( 'Enter detail link title.', 'cardealer-helper' ),
						'param_name'  => 'detail_link_title',
						'dependency'  => array(
							'element' => 'style',
							'value'   => array( 'list' ),
						),
					),
				),
			)
		);
	}
}
add_action( 'vc_before_init', 'cdhl_recent_posts_shortcode_vc_map' );
