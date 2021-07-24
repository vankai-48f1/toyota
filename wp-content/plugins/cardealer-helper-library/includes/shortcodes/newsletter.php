<?php
/**
 * CarDealer Visual Composer newsletter Shortcode
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package car-dealer-helper/functions
 */

add_shortcode( 'newsletter', 'cdhl_shortcode_newsletter' );

/**
 * Shortcode HTML.
 *
 * @param array $atts .
 */
function cdhl_shortcode_newsletter( $atts ) {
	$atts = shortcode_atts(
		array(
			'nl_back_img' => '',
			'title'       => '',
			'description' => '',
			'color_style' => 'newsletter-color-light',
		),
		$atts
	);

	extract( $atts );
	ob_start();
	$uid = 'pgs-newsletter-widget-' . wp_rand();
	?>
		<div class="news-letter row news-letter-main bg-1 bg-overlay-black-70 <?php echo esc_attr( $color_style ); ?>" style="background:url(<?php echo esc_url( wp_get_attachment_url( $atts['nl_back_img'] ) ); ?>); background-attachment:fixed">
			<div class="col-lg-6 col-md-6 col-sm-6">
			<?php
			if ( ! empty( $atts['title'] ) ) {
				?>
				<h4 class="text-red"><?php echo esc_html( $atts['title'] ); ?></h4>
			<?php } ?>
				<?php if ( ! empty( $atts['description'] ) ) { ?>
					<p class="text-white"><?php echo esc_html( $atts['description'] ); ?></p>        
				<?php } ?>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6">
				<form class="news-letter-form" id="<?php echo esc_attr( $uid ); ?>">
					<div class="row no-gutter">
						<input type="hidden" name="news_nonce" class="news-nonce" value="<?php echo wp_create_nonce( 'mailchimp_news' ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>">
						<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">							
							<input type="email" class="placeholder form-control newsletter-email" name="newsletter_email" placeholder="<?php echo esc_html__( 'Enter your email', 'cardealer-helper' ); ?>">
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<a class="button red newsletter-mailchimp submit" href="#" data-form-id="<?php echo esc_attr( $uid ); ?>"><?php echo esc_html__( 'Subscribe', 'cardealer-helper' ); ?></a>
						</div><br>
						<span class="spinimg-<?php echo esc_attr( $uid ); ?>"></span>
						<p class="newsletter-msg" style="display:none;"></p>
					</div>
				</form>
			</div>
		</div>
	<?php
	return ob_get_clean();
}

/**
 * Shortcode mapping.
 *
 * @return void
 */
function cdhl_newsletter_shortcode_vc_map() {
	if ( function_exists( 'vc_map' ) ) {
		vc_map(
			array(
				'name'                    => esc_html__( 'Potenza Newsletter', 'cardealer-helper' ),
				'description'             => esc_html__( 'Newsletter with mailchimp', 'cardealer-helper' ),
				'base'                    => 'newsletter',
				'class'                   => 'cardealer_helper_element_wrapper',
				'controls'                => 'full',
				'icon'                    => cardealer_vc_shortcode_icon( 'newsletter' ),
				'category'                => esc_html__( 'Potenza', 'cardealer-helper' ),
				'show_settings_on_create' => true,
				'params'                  => array(
					array(
						'type'        => 'attach_image',
						'holder'      => 'div',
						'class'       => '',
						'heading'     => esc_html__( 'Background Image', 'cardealer-helper' ),
						'description' => esc_html__( 'Newsletter Background Image', 'cardealer-helper' ),
						'param_name'  => 'nl_back_img',
					),
					array(
						'type'        => 'textfield',
						'class'       => 'newsletter_title',
						'heading'     => esc_html__( 'Title', 'cardealer-helper' ),
						'description' => esc_html__( 'Enter title here', 'cardealer-helper' ),
						'param_name'  => 'title',
						'value'       => '',
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Description', 'cardealer-helper' ),
						'param_name'  => 'description',
						'description' => sprintf(
							wp_kses(
								/* translators: $s: Enter description */
								__( 'Enter description. Please ensure to add short content.<br> Please set both your MailChimp API key and list id in the API Keys panel. <a href="%1$s" target="_blank">Add API key here</a>', 'cardealer-helper' ),
								array(
									'br' => array(),
									'a'  => array(
										'href'   => array(),
										'target' => array(),
									),
								)
							),
							esc_url( esc_url( site_url( 'wp-admin/admin.php?page=cardealer' ) ) )
						),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Newsletter Layout Color', 'cardealer-helper' ),
						'param_name' => 'color_style',
						'value'      => array(
							esc_html__( 'Light', 'cardealer-helper' ) => 'newsletter-color-light',
							esc_html__( 'Dark', 'cardealer-helper' ) => 'newsletter-color-dark',
						),
					),
				),
			)
		);
	}
}
add_action( 'vc_before_init', 'cdhl_newsletter_shortcode_vc_map' );
