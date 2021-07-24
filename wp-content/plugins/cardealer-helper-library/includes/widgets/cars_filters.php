<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Adds Cardealer Helpert Widget Cars Filters.
 *
 * @package car-dealer-helper/functions
 * @version 1.0.0
 */

/**
 * Cardealer Helpert Widget Cars Filters.
 */
class CarDealer_Helper_Widget_Cars_Filters extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		$widget_ops = array(
			'classname'   => 'cars_filters',
			'description' => esc_html__( 'Add vehicles filters widget in vehicle listing widget area.', 'cardealer-helper' ),
		);
		parent::__construct( 'cars_filters', esc_html__( 'Car Dealer - Vehicles Filters', 'cardealer-helper' ), $widget_ops );

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
		global $car_dealer_options;

		$widget_id = ! isset( $args['widget_id'] ) ? 1 : $args['widget_id'];
		echo $args['before_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . '<a href="#cdhl-vehicle-filters-' . $widget_id . '" data-toggle="collapse"><i class="fas fa-plus"></i></a>' . $args['after_title']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		}

		if ( function_exists( 'cardealer_get_all_filters' ) ) {
			if ( isset( $car_dealer_options['vehicle-listing-layout'] ) && ( 'lazyload' === (string) $car_dealer_options['vehicle-listing-layout'] ) ) {
				?>
				<div id="cdhl-vehicle-filters-<?php esc_attr_e( $widget_id ); ?>" class="cdhl-vehicle-filters">
					<?php cardealer_get_all_filters(); ?>
				</div>
				<?php
			} else {
				cardealer_get_all_filters();
			}
		}

		echo $args['after_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
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
		return $new_instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Vehicles Filters', 'cardealer-helper' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'cardealer-helper' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
	}
}
