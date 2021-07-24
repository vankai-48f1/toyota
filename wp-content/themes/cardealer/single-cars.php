<?php
/**
 * The template for displaying single car posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package CarDealer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();

get_template_part( 'template-parts/content', 'intro' );

global $car_dealer_options;
$class = 'container';
if ( isset( $car_dealer_options['cars-details-layout'] ) && 3 === (int) $car_dealer_options['cars-details-layout'] ) {
	$class = 'container-fluid';
}
?>
<section class="car-details page-section-ptb">
	<div class="<?php echo esc_attr( $class ); ?>">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<?php get_template_part( 'template-parts/cars/content', 'single-cars' ); ?>
		<?php endwhile; // end of the loop. ?>
	</div>
</section>
<?php
get_footer();
