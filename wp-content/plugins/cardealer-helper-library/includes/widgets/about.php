<?php
/**
 * Adds Cardealer Helpert Widget Inquiry.
 *
 * @package car-dealer-helper/functions
 * @version 1.0.0
 */

/**
 * Cardealer Helpert Widget Inquiry.
 */
class CarDealer_Helper_Widget_About extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'cd_about',
			esc_html__( 'Car Dealer - About', 'cardealer-helper' ),
			array(
				'description' => esc_html__( 'Car Dealer - About Widget.', 'cardealer-helper' ),
			)
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		}
		if ( ! empty( $instance['content'] ) ) {
			echo apply_filters( 'widget_content', $instance['content'] ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		}
		echo $args['after_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title   = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'cardealer-helper' );
		$content = ! empty( $instance['content'] ) ? $instance['content'] : esc_html__( 'Content', 'cardealer-helper' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'cardealer-helper' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			<label for="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>"><?php esc_html_e( 'Content:', 'cardealer-helper' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'content' ) ); ?>" type="text" value="<?php echo esc_attr( $content ); ?>">
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance            = array();
		$instance['title']   = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['content'] = ( ! empty( $new_instance['content'] ) ) ? strip_tags( $new_instance['content'] ) : '';

		return $instance;
	}
}
