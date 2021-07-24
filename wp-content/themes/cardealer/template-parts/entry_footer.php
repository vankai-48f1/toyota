<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

global $car_dealer_options;

$facebook_share    = isset( $car_dealer_options['facebook_share'] ) ? $car_dealer_options['facebook_share'] : '';
$twitter_share     = isset( $car_dealer_options['twitter_share'] ) ? $car_dealer_options['twitter_share'] : '';
$linkedin_share    = isset( $car_dealer_options['linkedin_share'] ) ? $car_dealer_options['linkedin_share'] : '';
$google_plus_share = isset( $car_dealer_options['google_plus_share'] ) ? $car_dealer_options['google_plus_share'] : '';
$pinterest_share   = isset( $car_dealer_options['pinterest_share'] ) ? $car_dealer_options['pinterest_share'] : '';
$whatsapp_share    = isset( $car_dealer_options['whatsapp_share'] ) ? $car_dealer_options['whatsapp_share'] : '';

if ( is_single() && ( ! has_tag() && empty( $facebook_share ) && empty( $twitter_share ) && empty( $linkedin_share ) && empty( $google_plus_share ) && empty( $pinterest_share ) && empty( $whatsapp_share ) ) ) {
	return;
}
?>

<div class="entry-share clearfix">
	<?php
	if ( is_single() ) {
		?>
		<div class="tags-2 pull-left clearfix">
			<?php the_tags( '<h5>' . esc_html__( 'Tags', 'cardealer' ) . ':</h5><ul><li>', '</li><li>', '</li></ul>' ); ?>
		</div>
		<?php
	} else {
		?>
		<a href="<?php echo esc_url( get_permalink() ); ?>" class="button pull-left">
			<span><?php esc_html_e( 'Read More', 'cardealer' ); ?></span>
		</a>
		<?php
	}
	if ( ! empty( $facebook_share ) || ! empty( $twitter_share ) || ! empty( $linkedin_share ) || ! empty( $google_plus_share ) || ! empty( $pinterest_share ) ) {
		?>
		<div class="share pull-right">
			<a href="#" class="share-button">
				<i class="fas fa-share-alt"></i>
			</a>
			<ul class="single-share-box mk-box-to-trigger">
				<?php
				if ( $facebook_share ) {
					?>
					<li><a href="#" class="facebook-share" data-title="<?php echo esc_attr( get_the_title() ); ?>" data-url="<?php echo esc_url( get_permalink() ); ?>"><i class="fab fa-facebook-f"></i></a></li>
					<?php
				}
				if ( $twitter_share ) {
					?>
					<li><a href="#"  data-title="<?php echo esc_attr( get_the_title() ); ?>" data-url="<?php echo esc_url( get_permalink() ); ?>" class="twitter-share"><i class="fab fa-twitter"></i></a></li>
					<?php
				}
				if ( $linkedin_share ) {
					?>
					<li><a href="#" data-title="<?php echo esc_attr( get_the_title() ); ?>" data-url="<?php echo esc_url( get_permalink() ); ?>" class="linkedin-share"><i class="fab fa-linkedin-in"></i></a></li>
					<?php
				}
				if ( $google_plus_share ) {
					?>
					<li><a href="#" data-title="<?php echo esc_attr( get_the_title() ); ?>" data-url="<?php echo esc_url( get_permalink() ); ?>" class="googleplus-share"><i class="fab fa-google-plus-g"></i></a></li>
					<?php
				}
				if ( $pinterest_share ) {
					?>
					<li><a href="#" data-title="<?php echo esc_attr( get_the_title() ); ?>" data-url="<?php echo esc_url( get_permalink() ); ?>" data-image="<?php the_post_thumbnail_url( 'full' ); ?>" class="pinterest-share"><i class="fab fa-pinterest"></i></a></li>
					<?php
				}
				if ( $whatsapp_share ) {
					if ( ! wp_is_mobile() ) {
						?>
						<li><a href="#" data-url="<?php echo esc_url( get_permalink() ); ?>"  class="whatsapp-share"><i class="fab fa-whatsapp"></i></a></li>
						<?php
					} else {
						?>
						<li><a target="_blank" href="https://wa.me/?text=<?php echo esc_url( get_permalink() ); ?>"><i class="fab fa-whatsapp"></i></a></li>
						<?php
					}
				}
				?>
			</ul>
		</div>
		<?php
	}
	?>
</div>

<?php
if ( ! is_single() ) {
	?>
	<hr>
	<?php
}

