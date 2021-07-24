<?php
/**
 * CarDealer Visual Composer our team Shortcode
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package car-dealer-helper/functions
 */

add_shortcode( 'cd_ourteam', 'cdhl_shortcode_ourteam' );

/**
 * Shortcode HTML.
 *
 * @param array $atts .
 */
function cdhl_shortcode_ourteam( $atts ) {
	global $car_dealer_options;
	$atts = shortcode_atts(
		array(
			'style'            => 'style-1',
			'posts_per_page'   => 10,
			'css'              => '',
			'data_md_items'    => 4,
			'data_sm_items'    => 2,
			'data_xs_items'    => 1,
			'data_xx_items'    => 1,
			'data_space'       => 20,
			'dots'             => 'true',
			'arrow'            => 'true',
			'autoplay'         => 'true',
			'data_loop'        => 'true',
			'silder_type'      => 'with_silder',
			'number_of_column' => 1,
			'element_id'       => uniqid( 'cd_ourteam_' ),
		),
		$atts
	);
	extract( $atts );

	$args      = array(
		'post_type'      => 'teams',
		'posts_per_page' => $atts['posts_per_page'],
	);
	$lazyload  = cardealer_lazyload_enabled();
	$the_query = new WP_Query( $args );
	$cnt       = $the_query->post_count;
	if ( ! $the_query->have_posts() || ( 0 === (int) $atts['posts_per_page'] ) ) {
		return null;
	}

	$css          = $atts['css'];
	$custom_class = vc_shortcode_custom_css_class( $css, ' ' );

	$element_classes = array(
		'pgs_team',
		$atts['style'],
		$custom_class,
	);

	$element_classes = implode( ' ', array_filter( array_unique( $element_classes ) ) );

	if ( 'true' === (string) $arrow && $cnt >= $data_md_items ) {
		$arrow = 'true';
	} else {
		$arrow = 'false';
	}

	if ( 'true' === (string) $dots && $cnt >= $data_md_items ) {
		$dots = 'true';
	} else {
		$dots = 'false';
	}

	if ( 'true' === (string) $autoplay ) {
		$autoplay = 'true';
	} else {
		$autoplay = 'false';
	}

	if ( 'true' === (string) $data_loop ) {
		$data_loop = 'true';
	} else {
		$data_loop = 'false';
	}

	ob_start();
	$data_attr = '';
	if ( 'with_silder' === (string) $silder_type ) {
		$data_attr .= ' data-nav-arrow=' . esc_attr( $arrow );
		$data_attr .= ' data-nav-dots=' . esc_attr( $dots );
		$data_attr .= ' data-items=' . esc_attr( $data_md_items );
		$data_attr .= ' data-md-items=' . esc_attr( $data_md_items );
		$data_attr .= ' data-sm-items=' . esc_attr( $data_sm_items );
		$data_attr .= ' data-xs-items=' . esc_attr( $data_xs_items );
		$data_attr .= ' data-xx-items=' . esc_attr( $data_xx_items );
		$data_attr .= ' data-space=' . esc_attr( $data_space );
		$data_attr .= ' data-autoplay=' . esc_attr( $autoplay );
		$data_attr .= ' data-loop=' . esc_attr( $data_loop );
		$data_attr .= ' data-lazyload=' . esc_attr( $lazyload );

		if ( $atts['posts_per_page'] > 1 ) {
			$element_classes .= ' owl-carousel';
		}
	}?>
	<div class="<?php echo esc_attr( $element_classes ); ?>" id="<?php echo esc_attr( $element_id ); ?>" <?php echo $data_attr; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>>
	<?php
	$k = 0;

	$lazyload_class = ( 'true' === (string) $data_loop ) ? 'cardealer-lazy-load owl-lazy' : 'cardealer-lazy-load';

	if ( $the_query->post_count < $data_md_items ) {
		$lazyload_class = 'cardealer-lazy-load';
	}

	while ( $the_query->have_posts() ) {

		$the_query->the_post();
		global $post;

		$designation   = get_post_meta( get_the_ID(), 'designation', $single = true );
		$facebook      = get_post_meta( get_the_ID(), 'facebook', $single = true );
		$twitter       = get_post_meta( get_the_ID(), 'twitter', $single = true );
		$pinterest     = get_post_meta( get_the_ID(), 'pinterest', $single = true );
		$behance       = get_post_meta( get_the_ID(), 'behance', $single = true );
		$default_image = CDHL_VC_URL . '/vc_images/team-member.png';

		if ( 'with_silder' === (string) $silder_type ) {
			if ( 'style-1' === (string) $atts['style'] || 'style-3' === (string) $atts['style'] ) {
				?>
				<div class="item">
					<div class="team text-center">
						<div class="team-image">
							<?php
							if ( has_post_thumbnail() ) {
								if ( isset( $car_dealer_options['enable_lazyload'] ) && $car_dealer_options['enable_lazyload'] ) {
									$img_size = ( 'style-3' === (string) $atts['style'] ) ? 'cardealer-homepage-thumb' : 'cardealer-team-thumb';
									$img_url  = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ), $img_size );
									echo '<img src="' . esc_url( LAZYLOAD_IMG ) . '" data-src="' . esc_url( $img_url ) . '" class="img-responsive icon ' . esc_attr( $lazyload_class ) . '" >';

								} else {
									$img_size = ( 'style-3' === (string) $atts['style'] ) ? 'cardealer-homepage-thumb' : 'cardealer-team-thumb';
									$img_url  = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ), $img_size );
									echo '<img src="' . esc_url( $img_url ) . '" class="img-responsive icon" >';
								}
							} else {
								if ( isset( $car_dealer_options['enable_lazyload'] ) && $car_dealer_options['enable_lazyload'] ) {
									echo '<img src="' . esc_url( LAZYLOAD_IMG ) . '" data-src="' . esc_url( $default_image ) . '" class="img-responsive icon ' . esc_attr( $lazyload_class ) . '" >';
								} else {
									echo '<img src="' . esc_url( $default_image ) . '" class="img-responsive icon" >';
								}
							}

							if ( $facebook || $twitter || $pinterest || $behance ) {
								?>
								<div class="team-social">
									<ul>
										<?php
										if ( $facebook ) {
											echo '<li><a class="icon-1" href="' . esc_url( $facebook ) . '"><i class="fab fa-facebook-f"></i></a></li>'; }
										if ( $twitter ) {
											echo '<li><a class="icon-2" href="' . esc_url( $twitter ) . '"><i class="fab fa-twitter"></i></a></li>'; }
										if ( $pinterest ) {
											echo '<li><a class="icon-3" href="' . esc_url( $pinterest ) . '"><i class="fab fa-pinterest"></i></a></li>'; }
										if ( $behance ) {
											echo '<li><a class="icon-4" href="' . esc_url( $behance ) . '"><i class="fab fa-behance"></i></a></li>'; }
										?>
									</ul>
								</div>
								<?php
							}
							?>
						</div>
						<div class="team-name">
							<h5 class="text-black">
								<a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a>
							</h5>
							<?php
							if ( $designation ) {
								echo "<span class='text-black'>" . esc_html( $designation ) . '</span>';
							}
							?>
						</div>
					</div>
				</div>
				<?php
			} elseif ( 'style-2' === (string) $atts['style'] ) {
				?>
				<div class="team-2">
					<div class="team-image">
						<?php
						if ( has_post_thumbnail() ) {
							if ( isset( $car_dealer_options['enable_lazyload'] ) && $car_dealer_options['enable_lazyload'] ) {
								$img_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ), 'cardealer-team-thumb' );
								echo '<img src="' . esc_url( LAZYLOAD_IMG ) . '" data-src="' . esc_url( $img_url ) . '" class="img-responsive icon ' . esc_attr( $lazyload_class ) . '" >';
							} else {
								$img_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ), 'cardealer-team-thumb' );
								echo '<img src="' . esc_url( $img_url ) . '" class="img-responsive icon" >';
							}
						} else {
							if ( isset( $car_dealer_options['enable_lazyload'] ) && $car_dealer_options['enable_lazyload'] ) {
								echo '<img src="' . esc_url( LAZYLOAD_IMG ) . '" data-src="' . esc_url( $default_image ) . '" class="img-responsive icon ' . esc_attr( $lazyload_class ) . '" >';
							} else {
								echo '<img src="' . esc_url( $default_image ) . '" class="img-responsive icon" >';
							}
						}
						?>
					</div>
					<div class="team-info">
						<div class="team-name">
							<?php
							if ( $designation ) {
								echo '<span>' . esc_html( $designation ) . '</span>';
							}
							?>
							<h5>
								<a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a>
							</h5>
						</div>
						<?php
						if ( $facebook || $twitter || $pinterest || $behance ) {
							?>
							<div class="team-social">
								<ul>
									<?php
									if ( $facebook ) {
										echo '<li><a class="icon-1" href="' . esc_url( $facebook ) . '"><i class="fab fa-facebook-f"></i></a></li>'; }
									if ( $twitter ) {
										echo '<li><a class="icon-2" href="' . esc_url( $twitter ) . '"><i class="fab fa-twitter"></i></a></li>'; }
									if ( $pinterest ) {
										echo '<li><a class="icon-3" href="' . esc_url( $pinterest ) . '"><i class="fab fa-pinterest"></i></a></li>'; }
									if ( $behance ) {
										echo '<li><a class="icon-4" href="' . esc_url( $behance ) . '"><i class="fab fa-behance"></i></a></li>'; }
									?>
								</ul>
							</div>
							<?php
						}
						?>
					</div>
				</div>
				<?php
			}
		} else {
			$i = 12 / $number_of_column;
			if ( 0 === (int) $k % $number_of_column ) {
				?>
				<div class="row">
				<?php
			}
			?>

			<div class='col-sm-<?php echo esc_attr( $i ); ?>'>
				<?php
				if ( 'style-1' === (string) $atts['style'] || 'style-3' === (string) $atts['style'] ) {
					?>
					<div class="team text-center">
						<div class="team-image">
							<?php
							if ( has_post_thumbnail() ) {
								if ( isset( $car_dealer_options['enable_lazyload'] ) && $car_dealer_options['enable_lazyload'] ) {
									$img_size = ( 'style-3' === (string) $atts['style'] ) ? 'cardealer-homepage-thumb' : 'cardealer-team-thumb';
									$img_url  = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ), $img_size );
									echo '<img src="' . esc_url( LAZYLOAD_IMG ) . '" data-src="' . esc_url( $img_url ) . '" class="img-responsive icon cardealer-lazy-load" >';

								} else {
									$img_size = ( 'style-3' === (string) $atts['style'] ) ? 'cardealer-homepage-thumb' : 'cardealer-team-thumb';
									$img_url  = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ), $img_size );
									echo '<img src="' . esc_url( $img_url ) . '" class="img-responsive icon" >';
								}
							} else {
								if ( isset( $car_dealer_options['enable_lazyload'] ) && $car_dealer_options['enable_lazyload'] ) {
									echo '<img src="' . esc_url( LAZYLOAD_IMG ) . '" data-src="' . esc_url( $default_image ) . '" class="img-responsive icon cardealer-lazy-load" >';
								} else {
									echo '<img src="' . esc_url( $default_image ) . '" class="img-responsive icon" >';
								}
							}

							if ( $facebook || $twitter || $pinterest || $behance ) {
								?>
									<div class="team-social">
										<ul>
										<?php
										if ( $facebook ) {
											echo '<li><a class="icon-1" href="' . esc_url( $facebook ) . '"><i class="fab fa-facebook-f"></i></a></li>'; }
										if ( $twitter ) {
											echo '<li><a class="icon-2" href="' . esc_url( $twitter ) . '"><i class="fab fa-twitter"></i></a></li>'; }
										if ( $pinterest ) {
											echo '<li><a class="icon-3" href="' . esc_url( $pinterest ) . '"><i class="fab fa-pinterest"></i></a></li>'; }
										if ( $behance ) {
											echo '<li><a class="icon-4" href="' . esc_url( $behance ) . '"><i class="fab fa-behance"></i></a></li>'; }
										?>
										</ul>
									</div>
									<?php
							}
							?>
						</div>
						<div class="team-name">
							<h5 class="text-black">
								<a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a>
							</h5>
						<?php
						if ( $designation ) {
							echo "<span class='text-black'>" . esc_html( $designation ) . '</span>';
						}
						?>
						</div>
					</div>
					<?php
				} elseif ( 'style-2' === (string) $atts['style'] ) {
					?>
					<div class="team-2">
						<div class="team-image">
							<?php
							if ( has_post_thumbnail() ) {
								if ( isset( $car_dealer_options['enable_lazyload'] ) && $car_dealer_options['enable_lazyload'] ) {
									$img_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ), 'cardealer-team-thumb' );
									echo '<img src="' . esc_url( LAZYLOAD_IMG ) . '" data-src="' . esc_url( $img_url ) . '" class="img-responsive icon cardealer-lazy-load" >';
								} else {
									$img_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ), 'cardealer-team-thumb' );
									echo '<img src="' . esc_url( $img_url ) . '" class="img-responsive icon cardealer-lazy-load" >';
								}
							} else {
								if ( isset( $car_dealer_options['enable_lazyload'] ) && $car_dealer_options['enable_lazyload'] ) {
										echo '<img src="' . esc_url( $default_image ) . '" class="img-responsive icon cardealer-lazy-load" >';
								} else {
									echo '<img src="' . esc_url( $default_image ) . '" class="img-responsive icon" >';
								}
							}
							?>
						</div>
						<div class="team-info">
							<div class="team-name">
								<?php
								if ( $designation ) {
									echo '<span>' . esc_html( $designation ) . '</span>';
								}
								?>
								<h5>
									<a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a>
								</h5>
							</div>
							<?php
							if ( $facebook || $twitter || $pinterest || $behance ) {
								?>
								<div class="team-social">
									<ul>
										<?php
										if ( $facebook ) {
											echo '<li><a class="icon-1" href="' . esc_url( $facebook ) . '"><i class="fab fa-facebook-f"></i></a></li>'; }
										if ( $twitter ) {
												echo '<li><a class="icon-2" href="' . esc_url( $twitter ) . '"><i class="fab fa-twitter"></i></a></li>'; }
										if ( $pinterest ) {
											echo '<li><a class="icon-3" href="' . esc_url( $pinterest ) . '"><i class="fab fa-pinterest"></i></a></li>'; }
										if ( $behance ) {
											echo '<li><a class="icon-4" href="' . esc_url( $behance ) . '"><i class="fab fa-behance"></i></a></li>'; }
										?>
									</ul>
								</div>
								<?php
							}
							?>
						</div>
					</div>
					<?php
				}
				?>
			</div>
			<?php
			$k++;
			if ( 0 === (int) $k % $number_of_column || (string) $k === (string) $posts_per_page ) {
				?>
				</div> 
				<?php
			}
		}
	}
	echo '</div>';
	/* Restore original Post Data */
	wp_reset_postdata();
	return ob_get_clean();
}

/**
 * Shortcode mapping.
 *
 * @return void
 */
function cdhl_shortcode_ourteam_vc_map() {
	if ( function_exists( 'vc_map' ) ) {

		vc_map(
			array(
				'name'                    => esc_html__( 'Potenza Our Team', 'cardealer-helper' ),
				'description'             => esc_html__( 'Potenza Our Team', 'cardealer-helper' ),
				'base'                    => 'cd_ourteam',
				'class'                   => 'cardealer_helper_element_wrapper',
				'controls'                => 'full',
				'icon'                    => cardealer_vc_shortcode_icon( 'cd_ourteam' ),
				'category'                => esc_html__( 'Potenza', 'cardealer-helper' ),
				'show_settings_on_create' => true,
				'params'                  => array(
					array(
						'type'       => 'cd_radio_image',
						'heading'    => esc_html__( 'Style', 'cardealer-helper' ),
						'param_name' => 'style',
						'options'    => cdhl_get_shortcode_param_data( 'cd_our_team' ),
					),
					array(
						'type'        => 'cd_number_min_max',
						'heading'     => esc_html__( 'No. of Members', 'cardealer-helper' ),
						'param_name'  => 'posts_per_page',
						'value'       => '10',
						'min'         => '1',
						'max'         => '999999',
						'description' => esc_html__( 'Select count of team members to display.', 'cardealer-helper' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'List Style', 'cardealer-helper' ),
						'param_name'  => 'silder_type',
						'value'       => array(
							esc_html__( 'Carousel', 'cardealer-helper' ) => 'with_silder',
							esc_html__( 'Grid', 'cardealer-helper' ) => 'without_silder',
						),
						'save_always' => true,
						'description' => esc_html__( 'Select list style for team members', 'cardealer-helper' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Number of column', 'cardealer-helper' ),
						'param_name'  => 'number_of_column',
						'value'       => array(
							esc_html__( '1', 'cardealer-helper' )  => '1',
							esc_html__( '2', 'cardealer-helper' )  => '2',
							esc_html__( '3', 'cardealer-helper' )  => '3',
							esc_html__( '4', 'cardealer-helper' )  => '4',

						),
						'dependency'  => array(
							'element' => 'silder_type',
							'value'   => array( 'without_silder' ),
						),
						'save_always' => true,
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Number of slide desktops per rows', 'cardealer-helper' ),
						'param_name'       => 'data_md_items',
						'value'            => array(
							esc_html__( '3', 'cardealer-helper' )  => '3',
							esc_html__( '4', 'cardealer-helper' )  => '4',
						),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Slider Settings', 'cardealer-helper' ),
						'save_always'      => true,
						'dependency'       => array(
							'element' => 'silder_type',
							'value'   => 'with_silder',
						),
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Number of slide tablets', 'cardealer-helper' ),
						'param_name'       => 'data_sm_items',
						'value'            => array(
							esc_html__( '2', 'cardealer-helper' )  => '2',
							esc_html__( '3', 'cardealer-helper' )  => '3',
							esc_html__( '4', 'cardealer-helper' )  => '4',

						),
						'save_always'      => true,
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Slider Settings', 'cardealer-helper' ),
						'dependency'       => array(
							'element' => 'silder_type',
							'value'   => 'with_silder',
						),
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Number of slide mobile landscape', 'cardealer-helper' ),
						'param_name'       => 'data_xs_items',
						'value'            => array(
							esc_html__( '1', 'cardealer-helper' )  => '1',
							esc_html__( '2', 'cardealer-helper' )  => '2',
							esc_html__( '3', 'cardealer-helper' )  => '3',

						),
						'save_always'      => true,
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Slider Settings', 'cardealer-helper' ),
						'dependency'       => array(
							'element' => 'silder_type',
							'value'   => 'with_silder',
						),
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Number of slide mobile portrait', 'cardealer-helper' ),
						'param_name'       => 'data_xx_items',
						'value'            => array(
							esc_html__( '1', 'cardealer-helper' )  => '1',
							esc_html__( '2', 'cardealer-helper' )  => '2',

						),
						'save_always'      => true,
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Slider Settings', 'cardealer-helper' ),
						'dependency'       => array(
							'element' => 'silder_type',
							'value'   => 'with_silder',
						),
					),

					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Navigation Arrow', 'cardealer-helper' ),
						'param_name'       => 'arrow',
						'value'            => array(
							esc_html__( 'Yes', 'cardealer-helper' ) => 'true',
							esc_html__( 'No', 'cardealer-helper' ) => 'false',
						),
						'save_always'      => true,
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Slider Settings', 'cardealer-helper' ),
						'dependency'       => array(
							'element' => 'silder_type',
							'value'   => 'with_silder',
						),
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Navigation Dots', 'cardealer-helper' ),
						'param_name'       => 'dots',
						'value'            => array(
							esc_html__( 'Yes', 'cardealer-helper' ) => 'true',
							esc_html__( 'No', 'cardealer-helper' ) => 'false',
						),
						'save_always'      => true,
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Slider Settings', 'cardealer-helper' ),
						'dependency'       => array(
							'element' => 'silder_type',
							'value'   => 'with_silder',
						),
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Autoplay', 'cardealer-helper' ),
						'param_name'       => 'autoplay',
						'value'            => array(
							esc_html__( 'Yes', 'cardealer-helper' ) => 'true',
							esc_html__( 'No', 'cardealer-helper' ) => 'false',
						),
						'save_always'      => true,
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Slider Settings', 'cardealer-helper' ),
						'dependency'       => array(
							'element' => 'silder_type',
							'value'   => 'with_silder',
						),
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Loop', 'cardealer-helper' ),
						'param_name'       => 'data_loop',
						'value'            => array(
							esc_html__( 'Yes', 'cardealer-helper' ) => 'true',
							esc_html__( 'No', 'cardealer-helper' ) => 'false',
						),
						'save_always'      => true,
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Slider Settings', 'cardealer-helper' ),
						'dependency'       => array(
							'element' => 'silder_type',
							'value'   => 'with_silder',
						),
					),
					array(
						'type'             => 'cd_number_min_max',
						'heading'          => esc_html__( 'Space between two slide', 'cardealer-helper' ),
						'param_name'       => 'data_space',
						'min'              => '1',
						'max'              => '9999',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Slider Settings', 'cardealer-helper' ),
						'dependency'       => array(
							'element' => 'silder_type',
							'value'   => 'with_silder',
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
add_action( 'vc_before_init', 'cdhl_shortcode_ourteam_vc_map' );
