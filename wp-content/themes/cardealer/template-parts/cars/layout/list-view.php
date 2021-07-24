<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

$layout_style = cardealer_get_inv_list_style();
?>
<div class="car-grid style-<?php echo esc_attr( $layout_style ); ?>">
	<div class="row">
		<div <?php cardealer_list_view_class_1(); ?>>
			<div class="car-item gray-bg text-center">
				<div class="car-image">
					<?php
					$cardealer_post_id = get_the_ID();
					get_template_part( 'template-parts/cars/layout/img-overlay' );
					?>
				</div>
			</div>
		</div>
		<?php
		if ( 'classic' === $layout_style ) {
			?>
			<div <?php cardealer_list_view_class_2(); ?>>
				<div class="car-details">
					<div class="car-title">
						<a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php the_title(); ?></a>
					</div>
					<div class="car-info">
						<?php cardealer_get_cars_list_attribute(); ?>
						<div class="car-location">
							<?php
								cardealer_car_price_html( $cardealer_post_id );
								$vehicle_loc = get_post_meta( $cardealer_post_id, 'vehicle_location', true );
							if ( ! empty( $vehicle_loc ) && isset( $vehicle_loc['address'] ) ) {
								?>
								<p>
									<strong><?php echo esc_html__( 'Location:', 'cardealer' ); ?></strong>
									<?php echo esc_html( $vehicle_loc['address'] ); ?>
								</p>
								<?php
							}
							?>

						</div>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="car-description">
					<p><?php echo wp_kses_post( cardealer_car_short_content( $cardealer_post_id ) ); ?></p>
				</div>
			</div>
			<div class="col-md-12">
				<div class="car-bottom">
					<ul class="car-bottom-actions classic-list">
						<?php do_action( 'vehicle_classic_list_overlay_banner', $cardealer_post_id ); ?>
					</ul>
					<div class="car-review-stamps">
						<?php cardealer_get_vehicle_review_stamps( $cardealer_post_id ); ?>
					</div>
				</div>
			</div>
			<?php
		} else {
			?>
			<div <?php cardealer_list_view_class_2(); ?>>
				<div class="car-details">
					<div class="car-title">
						<a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php the_title(); ?></a>
						<p><?php echo wp_kses_post( cardealer_car_short_content( $cardealer_post_id ) ); ?></p>
					</div>
					<?php
					cardealer_get_vehicle_review_stamps( $cardealer_post_id );
					cardealer_car_price_html( $cardealer_post_id );
					?>
					<a class="button red pull-right" href="<?php echo esc_url( get_the_permalink() ); ?>"><?php esc_html_e( 'Details', 'cardealer' ); ?></a>
					<?php cardealer_get_cars_list_attribute(); ?>

				</div>
			</div>
			<?php
		}
		?>
	</div>
</div>
