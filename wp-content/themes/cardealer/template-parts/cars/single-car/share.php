<?php
/**
 * Template part to display single car share.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $car_dealer_options;

$facebook_share    = isset( $car_dealer_options['facebook_share'] ) ? $car_dealer_options['facebook_share'] : '';
$twitter_share     = isset( $car_dealer_options['twitter_share'] ) ? $car_dealer_options['twitter_share'] : '';
$linkedin_share    = isset( $car_dealer_options['linkedin_share'] ) ? $car_dealer_options['linkedin_share'] : '';
$google_plus_share = isset( $car_dealer_options['google_plus_share'] ) ? $car_dealer_options['google_plus_share'] : '';
$pinterest_share   = isset( $car_dealer_options['pinterest_share'] ) ? $car_dealer_options['pinterest_share'] : '';
$whatsapp_share    = isset( $car_dealer_options['whatsapp_share'] ) ? $car_dealer_options['whatsapp_share'] : '';

if ( empty( $facebook_share ) && empty( $twitter_share ) && empty( $linkedin_share ) && empty( $google_plus_share ) && empty( $pinterest_share ) && empty( $whatsapp_share ) ) {
	return;
}
?>
<div class="details-social details-weight share">
	<h5 class="uppercase"><?php esc_html_e( 'Share :', 'cardealer' ); ?></h5>
	<ul class="single-share-box mk-box-to-trigger">
		<?php
		if ( $facebook_share ) {
			?>
		<li>
			<a href="#" data-title="<?php echo esc_attr( get_the_title() ); ?>" data-url="<?php echo esc_url( get_permalink() ); ?>" class="facebook-share"><i class="fab fa-facebook-f"></i></a>
		</li>
			<?php
		}
		if ( $twitter_share ) {
			?>
			<li><a href="#"  data-title="<?php echo esc_attr( get_the_title() ); ?>" data-url="<?php echo esc_url( get_permalink() ); ?>" class="twitter-share"><i class="fab fa-twitter"></i></a></li>
		<?php } ?>
		<?php
		if ( $linkedin_share ) {
			?>
			<li><a href="#" data-title="<?php echo esc_attr( get_the_title() ); ?>" data-url="<?php echo esc_url( get_permalink() ); ?>" class="linkedin-share"><i class="fab fa-linkedin-in"></i></a></li>
		<?php } ?>
		<?php
		if ( $google_plus_share ) {
			?>
			<li><a href="#" data-title="<?php echo esc_attr( get_the_title() ); ?>" data-url="<?php echo esc_url( get_permalink() ); ?>" class="googleplus-share"><i class="fab fa-google-plus-g"></i></a></li>
		<?php } ?>
		<?php
		if ( $pinterest_share ) {
			?>
			<li><a href="#" data-title="<?php echo esc_attr( get_the_title() ); ?>" data-url="<?php echo esc_url( get_permalink() ); ?>" data-image="<?php echo esc_url( cardealer_get_single_image_url() ); ?>" class="pinterest-share"><i class="fab fa-pinterest"></i></a></li>
		<?php } ?>
		<?php
		if ( $whatsapp_share ) {
			if ( ! wp_is_mobile() ) {
				?>
				<li><a href="#" data-url="<?php echo esc_url( get_permalink() ); ?>"  class="whatsapp-share"><i class="fab fa-whatsapp"></i></a></li>
			<?php } else { ?>
				<li><a target="_blank" href="https://wa.me/?text=<?php echo esc_url( get_permalink() ); ?>"><i class="fab fa-whatsapp"></i></a></li>
			<?php } ?>
		<?php } ?>
	</ul>
</div><!--.share-->
