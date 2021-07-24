<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Adds Cardealer Helpert Widget Cars Search.
 *
 * @package car-dealer-helper/functions
 * @version 1.0.0
 */

/**
 * Cardealer Helpert Widget Cars Search.
 */
class CarDealer_Helper_Widget_Cars_Search extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		$widget_ops = array(
			'classname'   => 'cars_search ',
			'description' => esc_html__( 'Add Vehicles Search in widget area.', 'cardealer-helper' ),
		);
		parent::__construct( 'cars_search', esc_html__( 'Car Dealer - Vehicles Search', 'cardealer-helper' ), $widget_ops );
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

		$title = apply_filters( 'widget_title', ( empty( $instance['title'] ) ? esc_html__( 'Subscribe here', 'cardealer-helper' ) : $instance['title'] ), $instance, $this->id_base );

		$widget_id = $args['widget_id'];
		echo $args['before_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		?>

		<div class="price-search">                                                        
			<input type="hidden" name="post_type" value="cars" />
			<?php
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			}
			?>
			<div class="search">                    
				<input type="search" id="pgs_cars_search" class="form-control search-form placeholder" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" placeholder="<?php esc_attr_e( 'Search...', 'cardealer-helper' ); ?>" />                    
				<button class="search-button" id="pgs_cars_search_btn" value="Search" type="submit"><i class="fas fa-search"></i></button>
				<div class="auto-compalte-list"><ul></ul></div>
			</div>
		</div>		
		<?php
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
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Vehicles Search', 'cardealer-helper' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'cardealer-helper' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>				        
		<?php
	}
}
