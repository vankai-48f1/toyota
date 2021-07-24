<?php
/**
 * The Template for displaying cars listings.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

get_header();

get_template_part( 'template-parts/content', 'intro' );

global $car_dealer_options;

$layout = 'default';

if ( isset( $car_dealer_options['vehicle-listing-layout'] ) && ! empty( $car_dealer_options['vehicle-listing-layout'] ) ) {
	$layout = $car_dealer_options['vehicle-listing-layout'];
}

if ( 'default' === $layout ) {
	$container_class = 'container';
} else {
	$container_class = 'container-fluid';
}
?>
<section <?php post_class( 'product-listing page-section-ptb ' . $layout ); ?>>
	<div class="<?php echo esc_attr( $container_class ); ?>">
		<?php
		if ( 'default' === $layout ) {
			get_template_part( 'template-parts/cars/archive-layout/default' );
		} else {
			get_template_part( 'template-parts/cars/archive-layout/lazy-load' );
		}
		?>
	</div>
</section>
<!--.product-listing-->
<?php
get_footer();
