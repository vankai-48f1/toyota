<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

?>
<div class="car-details-sidebar">

	<div class="details-block details-weight">
		<h5><?php esc_html_e( 'DESCRIPTION', 'cardealer' ); ?></h5>
		<?php cardealer_get_cars_attributes( get_the_ID() ); ?>
	</div>
	<?php
	cardealer_get_vehicle_review_stamps( get_the_ID() );
	cardealer_add_vehicale_to_cart( get_the_ID() )
	?>

</div>
