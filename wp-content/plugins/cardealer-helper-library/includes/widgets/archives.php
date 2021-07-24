<?php
/**
 * Adds Cardealer Helpert Widget Archives.
 *
 * @package car-dealer-helper/functions
 * @version 1.0.0
 */

/**
 * Cardealer Helpert Widget Archives.
 */
class CarDealer_Helper_Widget_Archives extends WP_Widget_Archives {

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$c = ! empty( $instance['count'] ) ? '1' : '0';
		$d = ! empty( $instance['dropdown'] ) ? '1' : '0';

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? esc_html__( 'Archives', 'cardealer-helper' ) : $instance['title'], $instance, $this->id_base );

		echo $args['before_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		}

		if ( $d ) {
			$dropdown_id = "{$this->id_base}-dropdown-{$this->number}";
			?>
			<label class="screen-reader-text" for="<?php echo esc_attr( $dropdown_id ); ?>"><?php echo esc_html( $title ); ?></label>
			<select id="<?php echo esc_attr( $dropdown_id ); ?>" name="archive-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'>
				<?php
				/**
				 * Filter the arguments for the Archives widget drop-down.
				 *
				 * @since 2.8.0
				 *
				 * @see wp_get_archives()
				 *
				 * @param array $args An array of Archives widget drop-down arguments.
				 */
				$dropdown_args = apply_filters(
					'widget_archives_dropdown_args',
					array(
						'type'            => 'monthly',
						'format'          => 'option',
						'show_post_count' => $c,
					)
				);

				switch ( $dropdown_args['type'] ) {
					case 'yearly':
						$label = esc_html__( 'Select Year', 'cardealer-helper' );
						break;
					case 'monthly':
						$label = esc_html__( 'Select Month', 'cardealer-helper' );
						break;
					case 'daily':
						$label = esc_html__( 'Select Day', 'cardealer-helper' );
						break;
					case 'weekly':
						$label = esc_html__( 'Select Week', 'cardealer-helper' );
						break;
					default:
						$label = esc_html__( 'Select Post', 'cardealer-helper' );
						break;
				}
				?>

				<option value=""><?php echo esc_attr( $label ); ?></option>
				<?php wp_get_archives( $dropdown_args ); ?>

			</select>
			<?php
		} else {
			?>
			<ul class="widget-archives">
				<?php
				/**
				 * Filter the arguments for the Archives widget.
				 *
				 * @since 2.8.0
				 *
				 * @see wp_get_archives()
				 *
				 * @param array $args An array of Archives option arguments.
				 */
				wp_get_archives(
					apply_filters(
						'widget_archives_args',
						array(
							'type'            => 'monthly',
							'show_post_count' => $c,
							'before'          => '<i class="fas fa-angle-right"></i>',
						)
					)
				);

				?>
			</ul>
			<?php
		}

		echo $args['after_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
	}
}
