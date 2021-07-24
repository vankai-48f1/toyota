<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

global $car_dealer_options, $post;

$content_intro_post_id = is_home() ? get_option( 'page_for_posts' ) : get_the_ID();

if ( is_post_type_archive( 'cars' ) ) {
	if ( isset( $car_dealer_options['cars_inventory_page'] ) && $car_dealer_options['cars_inventory_page'] ) {
	$page_on_front       = get_option( 'page_on_front' );
	$cars_inventory_page = $car_dealer_options['cars_inventory_page'];
		if ( $page_on_front == $cars_inventory_page ) {
			$content_intro_post_id = $car_dealer_options['cars_inventory_page'];
		}
	}
}

if ( is_archive() ) {
	$content_intro_post_id = cardealer_get_current_post_id();

	// check for vehicle inventory home page.
	$front_page = get_option( 'page_on_front' );
	if ( isset( $car_dealer_options['cars_inventory_page'] ) && ! empty( $car_dealer_options['cars_inventory_page'] ) && $front_page === $car_dealer_options['cars_inventory_page'] ) {
		$content_intro_post_id = $front_page;
	}
	if ( class_exists( 'WooCommerce' ) && is_shop() ) {
		$shop_pg_id = get_option( 'woocommerce_shop_page_id' );
		if ( ! is_wp_error( $shop_pg_id ) && ! empty( $shop_pg_id ) ) {
			$content_intro_post_id = $shop_pg_id;
		}
	}
}

/*** THEME OPTIONS START */
// breadcrumb full width.
if ( isset( $car_dealer_options['header_type'] ) && in_array( $car_dealer_options['header_type'], array( 'light-fullwidth', 'transparent-fullwidth' ), true ) && isset( $car_dealer_options['breadcrumb_full_width'] ) && 1 === (int) $car_dealer_options['breadcrumb_full_width'] ) {
	$container_class = 'container-fluid';
} else {
	$container_class = 'container';
}

// mobile breadcrumb.
( isset( $car_dealer_options['breadcrumbs_on_mobile'] ) && 1 === (int) $car_dealer_options['breadcrumbs_on_mobile'] ) ? $mobile_breadcrumb_class = '' : $mobile_breadcrumb_class = 'breadcrumbs-hide-mobile';

// Titlebar Alignment.
$titlebar_view = ( isset( $car_dealer_options['titlebar_view'] ) ) ? $car_dealer_options['titlebar_view'] : 'default';
if ( function_exists( 'get_field' ) ) {
	$page_specific_title_alignment = get_field( 'enable_title_alignment', $content_intro_post_id );
	if ( $page_specific_title_alignment ) {
		$titlebar_view = get_field( 'title_alignment', $content_intro_post_id );    // get the title alignment.
		$titlebar_view = ( $titlebar_view ) ? $titlebar_view : 'left';   // set left default.
	}
}
$title_alignment = 'left';
if ( 'center' === (string) $titlebar_view ) {
	$title_alignment = 'center';
} elseif ( 'right' === (string) $titlebar_view ) {
	$title_alignment = 'right';
}


$hide_header_banner = get_post_meta( $content_intro_post_id, 'hide_header_banner', true );

/*Background Video options*/
if ( isset( $post ) && ! is_archive() ) {
	if ( ! is_home() ) {
		$content_intro_post_id = $post->ID;
	}
}

$enable_custom_banner = isset( $content_intro_post_id ) ? get_post_meta( $content_intro_post_id, 'enable_custom_banner', true ) : '';
if ( $enable_custom_banner ) {
	$banner_type = get_post_meta( $content_intro_post_id, 'banner_type', true );
	$video_type  = get_post_meta( $content_intro_post_id, 'banner_video_type_bg_custom', true );
	if ( 'video' === (string) $banner_type ) {
		$video_link = get_post_meta( $content_intro_post_id, 'banner_video_bg_custom', true );
	}
} else {
	$banner_type = isset( $car_dealer_options['banner_type'] ) ? $car_dealer_options['banner_type'] : '';
	$video_type  = isset( $car_dealer_options['video_type'] ) ? $car_dealer_options['video_type'] : '';
	if ( ! empty( $video_type ) ) {
		if ( 'youtube' === $video_type && ! empty( $car_dealer_options['youtube_video'] ) ) {
			$video_link = $car_dealer_options['youtube_video'];
		} elseif ( ! empty( $car_dealer_options['vimeo_video'] ) ) {
			$video_link = $car_dealer_options['vimeo_video'];
		} else {
			$video_link = '';
		}
	} else {
		$video_link = '';
	}
}
/*** THEME OPTIONS END */

if ( ! empty( $hide_header_banner ) && ( is_search() ) ) {
	$hide_header_banner = false;
}

$hide_header_banner = apply_filters( 'cardealer_hide_header_banner', $hide_header_banner );
// Return if page banner is set to hide.

if ( $hide_header_banner ) {
	return;
}

$data_youtube_video_bg = '';
if ( 'video' === (string) $banner_type && 'youtube' === (string) $video_type ) {
	$data_youtube_video_bg = $video_link;
}
?>

<section class="inner-intro header_intro <?php cardealer_intro_class(); ?>" data-youtube-video-bg="<?php echo esc_url( $data_youtube_video_bg ); ?>">
	<?php
	// Only Vimeo Video.
	if ( 'video' === (string) $banner_type && 'vimeo' === (string) $video_type ) {
		?>
		<div class="intro_header_video-bg  vc_video-bg vimeo_video_bg">
			<?php
			// URLs go support oembed providers.
			echo wp_oembed_get( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$video_link,
				array(
					'width'  => '500',
					'height' => '280',
				)
			);
			?>
		</div>
		<?php
	}
	?>
	<div class="<?php echo esc_attr( $container_class ); ?>">
		<?php
		$content_intro_title = get_the_title();
		$subtitle            = '';

		$show_on_front     = get_option( 'show_on_front' );
		$page_on_front     = get_option( 'page_on_front' );
		$page_for_posts_id = get_option( 'page_for_posts' );

		if ( is_singular() ) {
			if ( 'cars' === (string) get_post_type() ) {

				$content_intro_title = esc_html__( 'Vehicle Details', 'cardealer' );
				$cars_details_title  = ( isset( $car_dealer_options['cars-details-title'] ) ) ? $car_dealer_options['cars-details-title'] : '';

				if ( isset( $cars_details_title ) && ! empty( $cars_details_title ) ) {
					$content_intro_title = $cars_details_title;
				}
			}
			$subtitle = get_post_meta( get_the_ID(), 'subtitle', true );
		} elseif ( is_home() ) {
			$content_intro_blog_title = isset( $car_dealer_options['blog_title'] ) ? $car_dealer_options['blog_title'] : '';
			$blog_subtitle            = isset( $car_dealer_options['blog_subtitle'] ) ? $car_dealer_options['blog_subtitle'] : '';

			if ( 'posts' === (string) $show_on_front ) {
				$content_intro_title = esc_html__( 'Blog', 'cardealer' );
				if ( ! empty( $content_intro_blog_title ) ) {
					$content_intro_title = $content_intro_blog_title;
				}
				if ( ! empty( $blog_subtitle ) ) {
					$subtitle = $blog_subtitle;
				}
			} elseif ( 'page' === (string) $show_on_front ) {
				$page_for_posts_data = get_post( $page_for_posts_id );
				$content_intro_title = $page_for_posts_data->post_title;
				if ( ! empty( $content_intro_blog_title ) ) {
					$content_intro_title = $content_intro_blog_title;
				}
				$subtitle_meta = get_post_meta( $page_for_posts_id, 'subtitle', true );
				if ( ! empty( $subtitle_meta ) ) {
					$subtitle = $subtitle_meta;
				} elseif ( empty( $subtitle_meta ) && ! empty( $blog_subtitle ) ) {
					$subtitle = $blog_subtitle;
				}
			}
		} elseif ( is_tax() ) {
			$content_intro_term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
			if ( isset( $content_intro_term ) && ! empty( $content_intro_term ) ) {
				$content_intro_title = $content_intro_term->name;
			}

			/*
			---------------------------------------------------------------------------------------------------
				Check for Vehicle category archieve page and return page id if page is set from theme options.
				Get post type from category archieve page.
			----------------------------------------------------------------------------------------------------
			*/
			$is_cat_archive = false;
			$tax_post_type  = get_taxonomy( get_queried_object()->taxonomy )->object_type;

			if ( ! is_wp_error( $tax_post_type ) && isset( $tax_post_type[0] ) && 'cars' === $tax_post_type[0] && isset( $car_dealer_options['cars_inventory_page'] ) && ! empty( $car_dealer_options['cars_inventory_page'] ) ) {
				$content_intro_title = get_the_title( $car_dealer_options['cars_inventory_page'] );
				$subtitle            = get_post_meta( $car_dealer_options['cars_inventory_page'], 'subtitle', true );
			} elseif ( ! is_wp_error( $tax_post_type ) && isset( $tax_post_type[0] ) && 'cars' === $tax_post_type[0] && isset( $car_dealer_options['cars-listing-title'] ) && ! empty( $car_dealer_options['cars-listing-title'] ) ) {
				$content_intro_title = $car_dealer_options['cars-listing-title'];
			}
		} elseif ( is_archive() || is_post_type_archive() ) {

			if ( is_day() ) {
				$content_intro_title = esc_html__( 'Daily Archives', 'cardealer' );
				/* translators: 1: Post Date */
				$subtitle = sprintf( esc_html__( 'Date: %s', 'cardealer' ), '<span>' . get_the_date() . '</span>' );
			} elseif ( is_month() ) {
				$content_intro_title = esc_html__( 'Monthly Archives', 'cardealer' );
				/* translators: 1: Post Date Month */
				$subtitle = sprintf( esc_html__( 'Month: %s', 'cardealer' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'cardealer' ) ) . '</span>' );
			} elseif ( is_year() ) {
				$content_intro_title = esc_html__( 'Yearly Archives', 'cardealer' );
				/* translators: 1: Post Date Year */
				$subtitle = sprintf( esc_html__( 'Year: %s', 'cardealer' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'cardealer' ) ) . '</span>' );
			} elseif ( is_category() ) {
				$content_intro_title = esc_html__( 'Category Archives', 'cardealer' );
				/* translators: 1: Category Title */
				$subtitle = sprintf( esc_html__( 'Category Name: %s', 'cardealer' ), '<span>' . single_cat_title( '', false ) . '</span>' );
			} elseif ( is_tag() ) {
				$content_intro_title = esc_html__( 'Tag Archives', 'cardealer' );
				/* translators: 1: Tag Name */
				$subtitle = sprintf( esc_html__( 'Tag Name: %s', 'cardealer' ), '<span>' . single_tag_title( '', false ) . '</span>' );
			} elseif ( is_author() ) {
				$content_intro_title = esc_html__( 'Author Archives', 'cardealer' );
				/* translators: 1: Author Name */
				$subtitle = sprintf( wp_kses( 'Author Name: %s', 'cardealer' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' );
			} elseif ( is_archive() && 'post' === (string) get_post_type() ) {
				$content_intro_title = esc_html__( 'Archives', 'cardealer' );
			} else {

				$content_intro_post_id = cardealer_get_current_post_id();
				$subtitle              = get_post_meta( $content_intro_post_id, 'subtitle', true );
				$queried_object        = get_queried_object();

				if ( 'cars' === (string) $queried_object->name ) {
					// Theme option vehicle inventory page title.
					$cars_listing_title = ( isset( $car_dealer_options['cars-listing-title'] ) ) ? $car_dealer_options['cars-listing-title'] : '';
					$page_title         = '';
					if ( isset( $car_dealer_options['cars_inventory_page'] ) && ! empty( $car_dealer_options['cars_inventory_page'] ) ) {
							$car_page           = get_post( $content_intro_post_id );
							$page_path          = isset( $car_page->post_name ) ? $car_page->post_name : 'cars';
							$content_intro_page = get_page_by_path( $page_path );
						if ( $content_intro_page ) {
							$page_title = get_the_title( $content_intro_post_id );
						}
					} else {
						$page_title = $cars_listing_title;
					}

					if ( isset( $page_title ) && ! empty( $page_title ) ) {
						$content_intro_title = $page_title;
					} else {
						$content_intro_title = post_type_archive_title( '', false );
					}
				} else {
					$content_intro_title = post_type_archive_title( '', false );
				}
			}
		} elseif ( is_search() ) {
			$content_intro_title = esc_html__( 'Search', 'cardealer' );
			$subtitle            = '';
		} elseif ( is_404() ) {
			$content_intro_title = esc_html__( '404 error', 'cardealer' );
			$subtitle            = '';
		}

		if ( function_exists( 'is_shop' ) ) {
			if ( is_shop() ) {
				// This filter is originated from WooCommerce.
				if ( apply_filters( 'woocommerce_show_page_title', true ) ) { // phpcs:ignore WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedHooknameFound
					add_filter( 'woocommerce_show_page_title', '__return_false' );
				}
			}
		}

		global $cardealer_title, $cardealer_subtitle;

		$cardealer_title    = apply_filters( 'cardealer_page_title', $content_intro_title );
		$cardealer_subtitle = apply_filters( 'cardealer_subtitle_title', $subtitle );
		do_action( 'cardealer_before_title' );
		?>
		<div class="row intro-title title-<?php echo esc_attr( $title_alignment . ' ' . $titlebar_view ); ?>">
			<?php
			if ( 'title_l_bread_r' === $titlebar_view ) {
				echo '<div class="col-sm-6 col-md-8 text-left">';
			} elseif ( 'bread_l_title_r' === (string) $titlebar_view ) {
				echo '<div class="col-sm-6 text-right col-sm-push-6">';
			}
			?>
			<h1 class="text-orange"><?php echo esc_html( $cardealer_title ); ?></h1>
			<?php
			if ( isset( $cardealer_subtitle ) && ! empty( $cardealer_subtitle ) ) {
				?>
				<p class="text-orange">
					<?php
					printf(
						wp_kses(
							$cardealer_subtitle,
							array(
								'span' => array(
									'style' => array(),
									'class' => array(),
								),
								'a'    => array(
									'href'  => array(),
									'class' => array(),
									'title' => array(),
									'rel'   => array(),
								),
							)
						)
					);
					?>
				</p>
				<?php
			}

			if ( 'title_l_bread_r' === (string) $titlebar_view ) {
				echo '</div><div class="col-sm-6 col-md-4 text-right">';
			} elseif ( 'bread_l_title_r' === (string) $titlebar_view ) {
				echo '</div><div class="col-sm-6 text-left col-sm-pull-6">';
			}

			if ( function_exists( 'bcn_display_list' )
				&& ( ! is_home() || ( is_home() && 'page' === (string) $show_on_front && ( 0 !== (int) $page_on_front || '0' === (string) $page_on_front ) ) )
				&& isset( $car_dealer_options['display_breadcrumb'] )
				&& ! empty( $car_dealer_options['display_breadcrumb'] )
			) {
				?>
				<ul class="page-breadcrumb <?php echo esc_attr( $mobile_breadcrumb_class ); ?>" typeof="BreadcrumbList" vocab="http://schema.org/">
					<?php bcn_display_list(); ?>
				</ul>
				<?php
			}
			if ( 'title_l_bread_r' === (string) $titlebar_view || 'bread_l_title_r' === (string) $titlebar_view ) {
				echo '</div>';
			}
			?>
		</div>
	</div>
</section>
