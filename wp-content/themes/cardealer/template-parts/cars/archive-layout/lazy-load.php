<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

?>
<div class="row">
	<?php
	global $car_dealer_options;
	$cd_inv_content = 'cd-no-content';
	if ( isset( $car_dealer_options['cars_inventory_page'] ) && ! empty( $car_dealer_options['cars_inventory_page'] ) ) {
		?>
		<div class="col-sm-12">
			<?php
			$content_post = get_post( $car_dealer_options['cars_inventory_page'] );
			if ( isset( $content_post->post_content ) && ! empty( $content_post->post_content ) ) {
				$page_content = apply_filters( 'cd_lazyload_inv_style_content', $content_post->post_content, $car_dealer_options['cars_inventory_page'] );
				if ( ! empty( $page_content ) ) {
					echo do_shortcode( $page_content );
					$cd_inv_content = 'cd-content';
				} else {
					$cd_inv_content = 'cd-no-content';
				}
			}
			?>
		</div>
		<?php
	}
	?>
	<div class="col-sm-12 <?php echo esc_attr( $cd_inv_content ); ?>">
		<?php
		$cars_term = get_queried_object();
		if ( is_tax() && $cars_term && ! empty( $cars_term->description ) ) {
			?>
			<div class="term-description"><?php echo do_shortcode( $cars_term->description ); ?></div>
			<?php
		}
		?>
		<div class="cars-top-filters-box">
			<div class="cars-top-filters-box-left">
				<?php cardealer_get_price_filters(); ?>
				<div class="price">
					<button id="pgs_price_filter_btn" class="button"><?php esc_html_e( 'Filter', 'cardealer' ); ?></button>
				</div>
			</div>
			<div class="cars-top-filters-box-right">
				<?php cardealer_cars_catalog_ordering(); ?>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>
<div class="row" data-sticky_parent>
	<!-- Filter for lazyload -->
	<aside class="sidebar col-lg-3 col-md-3 col-sm-3">
		<div class="listing-sidebar listing-sidebar-lazyload listing-sidebar-sticky" data-sticky_column>
			<div class="widget cars_filters">
				<h6 class="widgettitle">
					<?php echo esc_html( apply_filters( 'cardealer_lazyload_filter_title', esc_html__( 'Cars Filters', 'cardealer' ) ) ); ?>
					<a href="#cdhl-vehicle-filters-lazyload" data-toggle="collapse">
						<i class="fas fa-plus"></i>
					</a>
				</h6>
				<div id="cdhl-vehicle-filters-lazyload" class="cdhl-vehicle-filters">
					<?php the_widget( 'CarDealer_Helper_Widget_Cars_Filters' ); ?>
				</div>
			</div>
	</aside>
	<!-- Filter for lazyload end -->
	<div <?php cardealer_cars_content_class( 'masonry-main' ); ?>>
		<?php
		$getlayout = cardealer_get_cars_list_layout_style();
		$flag      = false;
		if ( isset( $getlayout ) && 'view-grid-full' === $getlayout ) {
			$flag = true;
		} elseif ( isset( $getlayout ) && 'view-list-full' === $getlayout ) {
			$flag = true;
		} else {
			if ( is_active_sidebar( 'listing-cars' ) ) {
				global $wp_registered_widgets;
				$widgets = get_option( 'sidebars_widgets', array() );
				$widgets = $widgets['listing-cars'];
				foreach ( $widgets as $widget ) {
					if ( isset( $wp_registered_widgets[ $widget ]['classname'] ) && 'cars_filters' === $wp_registered_widgets[ $widget ]['classname'] ) {
						$flag = false;
						break;
					} else {
						$flag = true;
					}
				}
			} else {
				$flag = true;
			}
		}

		global $cars_grid, $car_in_compare;

		$cars_grid = '';
		if ( isset( $_COOKIE['cars_grid'] ) && in_array( $_COOKIE['cars_grid'], array( 'yes', 'no' ), true ) ) {
			$cars_grid = sanitize_text_field( wp_unslash( $_COOKIE['cars_grid'] ) );
		}

		if ( isset( $_REQUEST['cars_grid'] ) && in_array( $_REQUEST['cars_grid'], array( 'yes', 'no' ), true ) ) {
			$cars_grid = sanitize_text_field( wp_unslash( $_REQUEST['cars_grid'] ) );
		}

		if ( '' === $cars_grid ) {
			$cars_grid = cardealer_get_cars_catlog_style();
		}

		if ( have_posts() ) {
			$count_posts  = wp_count_posts( 'cars' );
			$data_records = '';
			if ( ! is_wp_error( $count_posts ) && isset( $count_posts->publish ) && isset( $_GET['cars_pp'] ) && $_GET['cars_pp'] === $count_posts->publish ) {
				$data_records = -2;
			}
			?>
			<div class="all-cars-list-arch isotope-2 masonry filter-container" <?php echo ( ! empty( $data_records ) ) ? 'data-records=' . esc_attr( $data_records ) : ''; ?>>
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/cars/content', 'cars' );
				endwhile; // end of the loop.
				?>
			</div>
			<?php
		} else {
			echo '<div class="all-cars-list-arch" data-records=0><div class="col-sm-12"><div class="alert alert-warning">' . esc_html__( 'No result were found matching your selection.', 'cardealer' ) . '</div></div></div>';
		}
		?>
		<span id="cd-scroll-to"></span>
	</div>
	<?php
	/**
	 * Custome right-sidebar
	 * */
	cardealer_get_car_catlog_sidebar_right();
	?>
</div>
