<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * CarDealer Visual Composer opening hours Shortcode
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package car-dealer-helper/functions
 */

add_shortcode( 'cd_opening_hours', 'cdhl_shortcode_opening_hours' );

/**
 * Shortcode HTML.
 *
 * @param array $atts .
 */
function cdhl_shortcode_opening_hours( $atts ) {
	$atts = shortcode_atts(
		array(
			'opening_hours_title' => __( 'Opening Hours', 'cardealer-helper' ),
			'day_sunday'          => '',
			'day_monday'          => '',
			'day_tuesday'         => '',
			'day_wednesday'       => '',
			'day_thursday'        => '',
			'day_friday'          => '',
			'day_saturday'        => '',
		),
		$atts
	);

	extract( $atts );

	ob_start();
	?>
	<div class="opening-hours gray-bg">
		<h6><?php echo esc_html( $atts['opening_hours_title'] ); ?></h6>
		<ul class="list-style-none">                
			<li><strong><?php echo esc_html__( 'Monday', 'cardealer-helper' ); ?></strong>
				<?php
				if ( ! $atts['day_monday'] ) {
					?>
					<span class="text-red"><?php echo esc_html__( 'closed', 'cardealer-helper' ); ?></span>
					<?php
				} else {
					?>
					<span><?php echo esc_html( $atts['day_monday'] ); ?></span><?php } ?>
			</li>

			<li><strong><?php echo esc_html__( 'Tuesday', 'cardealer-helper' ); ?></strong>
				<?php
				if ( ! $atts['day_tuesday'] ) {
					?>
					<span class="text-red"><?php echo esc_html__( 'closed', 'cardealer-helper' ); ?></span>
					<?php
				} else {
					?>
					<span><?php echo esc_html( $atts['day_tuesday'] ); ?></span>
				<?php } ?>
			</li>
			<li><strong><?php echo esc_html__( 'Wednesday', 'cardealer-helper' ); ?></strong>
				<?php
				if ( ! $atts['day_wednesday'] ) {
					?>
					<span class="text-red"><?php echo esc_html__( 'closed', 'cardealer-helper' ); ?></span>
					<?php
				} else {
					?>
					<span><?php echo esc_html( $atts['day_wednesday'] ); ?></span>
				<?php } ?>
			</li>
			<li><strong><?php echo esc_html__( 'Thursday', 'cardealer-helper' ); ?></strong>
				<?php
				if ( ! $atts['day_thursday'] ) {
					?>
					<span class="text-red"><?php echo esc_html__( 'closed', 'cardealer-helper' ); ?></span>
					<?php
				} else {
					?>
					<span><?php echo esc_html( $atts['day_thursday'] ); ?></span>
				<?php } ?>
			</li>
			<li><strong><?php echo esc_html__( 'Friday', 'cardealer-helper' ); ?></strong>
				<?php
				if ( ! $atts['day_friday'] ) {
					?>
					<span class="text-red"><?php echo esc_html__( 'closed', 'cardealer-helper' ); ?></span>
					<?php
				} else {
					?>
					<span><?php echo esc_html( $atts['day_friday'] ); ?></span>
				<?php } ?>
			</li>
			<li><strong><?php echo esc_html__( 'Saturday', 'cardealer-helper' ); ?></strong>
				<?php
				if ( ! $atts['day_saturday'] ) {
					?>
					<span class="text-red"><?php echo esc_html__( 'closed', 'cardealer-helper' ); ?></span>
					<?php
				} else {
					?>
						<span><?php echo esc_html( $atts['day_saturday'] ); ?></span>
					<?php } ?>
				</li>
				<li><strong><?php echo esc_html__( 'Sunday', 'cardealer-helper' ); ?></strong>
					<?php
					if ( ! $atts['day_sunday'] ) {
						?>
						<span class="text-red"><?php echo esc_html__( 'closed', 'cardealer-helper' ); ?></span>
						<?php
					} else {
						?>
						<span><?php echo esc_html( $atts['day_sunday'] ); ?></span>
					<?php } ?>
				</li>
			</ul>
		</div>
	<?php
	return ob_get_clean();
}

/**
 * Shortcode mapping.
 *
 * @return void
 */
function cdhl_opening_hours_shortcode_vc_map() {
	if ( function_exists( 'vc_map' ) ) {
		vc_map(
			array(
				'name'                    => esc_html__( 'Potenza Opening Hours', 'cardealer-helper' ),
				'description'             => esc_html__( 'Potenza Opening Hours', 'cardealer-helper' ),
				'base'                    => 'cd_opening_hours',
				'class'                   => '_helper_element_wrapper',
				'controls'                => 'full',
				'icon'                    => cardealer_vc_shortcode_icon( 'cd_opening_hours' ),
				'category'                => esc_html__( 'Potenza', 'cardealer-helper' ),
				'show_settings_on_create' => true,
				'params'                  => array(
					array(
						'type'        => 'textfield',
						'class'       => '',
						'heading'     => esc_html__( 'Title', 'cardealer-helper' ),
						'description' => esc_html__( 'Enter the title', 'cardealer-helper' ),
						'param_name'  => 'opening_hours_title',
						'value'       => __( 'Opening Hours', 'cardealer-helper' ),
					),
					array(
						'type'        => 'textfield',
						'class'       => '',
						'heading'     => esc_html__( 'Monday', 'cardealer-helper' ),
						'description' => esc_html__( 'Enter Monday Time. Leave blank if close', 'cardealer-helper' ),
						'param_name'  => 'day_monday',
					),
					array(
						'type'        => 'textfield',
						'class'       => '',
						'heading'     => esc_html__( 'Tuesday', 'cardealer-helper' ),
						'description' => esc_html__( 'Enter Tuesday Time. Leave blank if close', 'cardealer-helper' ),
						'param_name'  => 'day_tuesday',
					),
					array(
						'type'        => 'textfield',
						'class'       => '',
						'heading'     => esc_html__( 'Wednesday', 'cardealer-helper' ),
						'description' => esc_html__( 'Enter Wednesday Time. Leave blank if close', 'cardealer-helper' ),
						'param_name'  => 'day_wednesday',
					),
					array(
						'type'        => 'textfield',
						'class'       => '',
						'heading'     => esc_html__( 'Thursday', 'cardealer-helper' ),
						'description' => esc_html__( 'Enter Thursday Time. Leave blank if close', 'cardealer-helper' ),
						'param_name'  => 'day_thursday',
					),
					array(
						'type'        => 'textfield',
						'class'       => '',
						'heading'     => esc_html__( 'Friday', 'cardealer-helper' ),
						'description' => esc_html__( 'Enter Friday Time. Leave blank if close', 'cardealer-helper' ),
						'param_name'  => 'day_friday',
					),
					array(
						'type'        => 'textfield',
						'class'       => '',
						'heading'     => esc_html__( 'Saturday', 'cardealer-helper' ),
						'description' => esc_html__( 'Enter Saturday Time. Leave blank if close', 'cardealer-helper' ),
						'param_name'  => 'day_saturday',
					),
					array(
						'type'        => 'textfield',
						'class'       => '',
						'heading'     => esc_html__( 'Sunday', 'cardealer-helper' ),
						'description' => esc_html__( 'Enter Sunday Time. Leave blank if close', 'cardealer-helper' ),
						'param_name'  => 'day_sunday',
					),
				),
			)
		);
	}
}
add_action( 'vc_before_init', 'cdhl_opening_hours_shortcode_vc_map' );
