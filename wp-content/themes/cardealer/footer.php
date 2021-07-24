<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package CarDealer
 */

global $car_dealer_options;
?>
	</div> <!-- #main .wrapper  -->
	<?php
	get_template_part( 'template-parts/footer/site_footer' );

	$back_to_top_img_url   = CARDEALER_URL . '/images/car.png';
	$back_to_top_img_light = '';
	if ( isset( $car_dealer_options['back_top_light'] ) && ! empty( $car_dealer_options['back_top_light'] ) ) {
		$back_to_top_img_light = 'default';
	} else {
		$back_to_top_img_light = 'custom';
	}

	if ( isset( $car_dealer_options['back_to_top_image'] ) && ! empty( $car_dealer_options['back_to_top_image'] ) && ! empty( $back_to_top_img_url ) ) {
		$back_to_top_img_url = $car_dealer_options['back_to_top_image']['url'];
	}
	if ( wp_is_mobile() ) {
		// Script to disable Top Bar in Mobile if disabled from Admin.
		if ( isset( $car_dealer_options['back_top_mobile'] ) && '1' === (string) $car_dealer_options['back_top_mobile'] ) {
			?>
			<div class="car-top <?php echo esc_attr( $back_to_top_img_light ); ?>">
				<span>
					<?php if ( isset( $car_dealer_options['enable_lazyload'] ) && $car_dealer_options['enable_lazyload'] ) { ?>
						<img class="cardealer-lazy-load" src="<?php echo esc_url( LAZYLOAD_IMG ); ?>" data-src="<?php echo esc_url( $back_to_top_img_url ); ?>" alt="<?php esc_attr_e( 'Top', 'cardealer' ); ?>" title="<?php esc_attr_e( 'Back to top', 'cardealer' ); ?>"/>
					<?php } elseif ( $back_to_top_img_url ) { ?>
						<img src="<?php echo esc_url( $back_to_top_img_url ); ?>" alt="<?php esc_attr_e( 'Top', 'cardealer' ); ?>" title="<?php esc_attr_e( 'Back to top', 'cardealer' ); ?>"/>
					<?php } ?>
				</span>
			</div>
			<?php
		}
	} elseif ( isset( $car_dealer_options['back_to_top'] ) && '1' === (string) $car_dealer_options['back_to_top'] && ! empty( $back_to_top_img_url ) ) {
		?>
			<div class="car-top <?php echo esc_attr( $back_to_top_img_light ); ?>">
				<span>
					<?php if ( isset( $car_dealer_options['enable_lazyload'] ) && $car_dealer_options['enable_lazyload'] ) { ?>
						<img class="cardealer-lazy-load" src="<?php echo esc_url( LAZYLOAD_IMG ); ?>" data-src="<?php echo esc_url( $back_to_top_img_url ); ?>" alt="<?php esc_attr_e( 'Top', 'cardealer' ); ?>" title="<?php esc_attr_e( 'Back to top', 'cardealer' ); ?>"/>
					<?php } elseif ( $back_to_top_img_url ) { ?>
						<img src="<?php echo esc_url( $back_to_top_img_url ); ?>" alt="<?php esc_attr_e( 'Top', 'cardealer' ); ?>" title="<?php esc_attr_e( 'Back to top', 'cardealer' ); ?>"/>
					<?php } ?>
				</span>
			</div>
	<?php } ?>

</div><!-- #page -->
<?php wp_footer(); ?>
</body>
</html>
