<?php
/**
 * Adds Cardealer Helpert Widget Meta.
 *
 * @package car-dealer-helper/functions
 * @version 1.0.0
 */

/**
 * Cardealer Helpert Widget Meta.
 */
class CarDealer_Helper_Widget_Meta extends WP_Widget_Meta {

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? esc_html__( 'Meta', 'cardealer-helper' ) : $instance['title'], $instance, $this->id_base );

		echo $args['before_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE

		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		}
		?>
		<ul class="widget-archives">
			<?php wp_register( '<li><i class="fas fa-angle-right"></i> ', '</li>', false ); ?>
			<li><i class="fas fa-angle-right"></i> <?php wp_loginout(); ?></li>
			<li><i class="fas fa-angle-right"></i> <a href="<?php echo esc_url( get_bloginfo( 'rss2_url' ) ); ?>"><?php echo wp_kses( __( 'Entries <abbr title="Really Simple Syndication">RSS</abbr>', 'cardealer-helper' ), array( 'abbr' => array( 'title' => array() ) ) ); ?></a></li>
			<li><i class="fas fa-angle-right"></i> <a href="<?php echo esc_url( get_bloginfo( 'comments_rss2_url' ) ); ?>"><?php echo wp_kses( __( 'Comments <abbr title="Really Simple Syndication">RSS</abbr>', 'cardealer-helper' ), array( 'abbr' => array( 'title' => array() ) ) ); ?></a></li>
			<?php
			/**
			 * Filter the "Powered by WordPress" text in the Meta widget.
			 *
			 * @since 3.6.0
			 *
			 * @param string $title_text Default title text for the WordPress.org link.
			 */
			echo apply_filters(
				'widget_meta_poweredby',
				sprintf(
					'<li><i class="fas fa-angle-right"></i> <a href="%s" title="%s">%s</a></li>',
					esc_url( 'https://wordpress.org/' ),
					esc_attr__( 'Powered by WordPress, state-of-the-art semantic personal publishing platform.', 'cardealer-helper' ),
					_x( 'WordPress.org', 'meta widget link text', 'cardealer-helper' )
				)
			); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE

			wp_meta();
			?>
		</ul>
		<?php
		echo $args['after_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
	}
}
