<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

global $car_dealer_options;

$class  = '';
$layout = '';

if ( isset( $car_dealer_options['cars-details-layout'] ) && 3 === (int) $car_dealer_options['cars-details-layout'] ) {
	$class  = '-full';
	$layout = $car_dealer_options['cars-details-layout'];
}
?>
<div class="slider-slick">
	<?php
	if ( function_exists( 'get_field' ) ) {
		?>
		<div id="cars-image-gallery" class="my-gallery">
			<div class="slider slider-for<?php echo esc_attr( $class ); ?> detail-big-car-gallery">
				<?php
				$i      = 0;
				$images = get_field( 'car_images' );
				if ( isset( $images ) && ! empty( $images ) ) {
					foreach ( $images as $image ) {
						$image_url    = $image['url'];
						$image_width  = $image['width'];
						$image_height = $image['height'];
						$imag_alt     = ( '' !== $image['alt'] ) ? $image['alt'] : get_the_title();
						?>
						<figure>
							<img src="<?php echo esc_url( $image['sizes']['car_single_slider'] ); ?>" class="img-responsive ps-car-listing" id="pscar-<?php echo esc_attr( $i++ ); ?>" alt="<?php echo esc_attr( $imag_alt ); ?>"  data-src="<?php echo esc_url( $image_url ); ?>" data-width="<?php echo esc_attr( $image_width ); ?>" data-height="<?php echo esc_attr( $image_height ); ?>"/>
						</figure>
						<?php
					}
				} else {
					echo wp_kses_post( cardealer_get_cars_image( 'large' ) );
				}
				?>
			</div>
		</div>
		<?php
		if ( '' === $layout ) {
			?>
			<div class="slider slider-nav">
				<?php
				$images = get_field( 'car_images' );
				if ( $images ) {
					?>
					<?php
					foreach ( $images as $image ) {
						$imag_alt = ( '' !== $image['alt'] ) ? $image['alt'] : get_the_title();
						?>
						<img class="img-responsive" src="<?php echo esc_url( $image['sizes']['car_thumbnail'] ); ?>" alt="<?php echo esc_attr( $imag_alt ); ?>">
						<?php
					}
				}
				?>
			</div>
			<?php
		}
	}
	?>
</div>
