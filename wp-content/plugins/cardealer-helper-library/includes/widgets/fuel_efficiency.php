<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Adds Cardealer Helpert Widget Fuel Efficiency.
 *
 * @package car-dealer-helper/functions
 * @version 1.0.0
 */

/**
 * Cardealer Helpert Widget Fuel Efficiency.
 */
class CarDealer_Helper_Widget_Fuel_Efficiency extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		$widget_ops = array(
			'classname'   => 'fuel_efficiency1',
			'description' => esc_html__( 'Add this widget to display fuel economy of vehicles.', 'cardealer-helper' ),
		);
		parent::__construct( 'fuel_efficiency1', esc_html__( 'Car Dealer - Fuel Economy', 'cardealer-helper' ), $widget_ops );
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
		$title       = apply_filters( 'widget_title', ( empty( $instance['title'] ) ? esc_html__( 'Fuel Economy', 'cardealer-helper' ) : $instance['title'] ), $instance, $this->id_base );
		$description = apply_filters( 'widget_description', ( empty( $instance['description'] ) ? '' : $instance['description'] ), $instance, $this->id_base );
		$city_mpg    = get_post_meta( get_the_ID(), 'city_mpg', $single = true );
		$highway_mpg = get_post_meta( get_the_ID(), 'highway_mpg', $single = true );
		if ( ( ! empty( $city_mpg ) ) || ( ! empty( $highway_mpg ) ) ) {
			echo $args['before_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			?>
			<div class="details-form contact-2 details-weight">
				<?php
				if ( ! empty( $instance['title'] ) ) {
					echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
				}
				if ( ( ! empty( $city_mpg ) ) || ( ! empty( $highway_mpg ) ) ) {
					?>
				<div class="fuel-efficiency-detail">	
					<?php
					/**
					 * Filters the Title of the Fuel economy displayed in sidebar.
					 *
					 * @since 1.0
					 * @param string    $title_fe   Title of the Fuel Economy Widget.
					 * @visible         true
					 */
					$title_fe        = esc_html__( 'Fuel Economy Rating', 'cardealer-helper' );
					$fuel_effi_title = apply_filters( 'cdhl_fuel_economy_title', $title_fe );
					?>
					<div class="heading"><h4><?php echo esc_html( $fuel_effi_title ); ?></h4></div>
					<div class="row">
						<div class="col-xs-4">
							<label><?php echo esc_html__( 'City', 'cardealer-helper' ); ?></label>
							<span class="city_mpg"><?php echo ( ! empty( $city_mpg ) ) ? $city_mpg : ''; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></span>
						</div>
						<div class="col-xs-4">
							<i class="glyph-icon flaticon-gas-station fa-3x"></i>
						</div>
						<div class="col-xs-4">
							<label><?php echo esc_html__( 'Highway', 'cardealer-helper' ); ?></label>						
							<span class="highway_mpg"><?php echo ( ! empty( $highway_mpg ) ) ? $highway_mpg : ''; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></span>
						</div>
						<div class="col-sm-12">	
							<?php echo ( $description ) ? '<p>' . $description . '</p>' : ''; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>			
						</div>
					</div>
				</div>
					<?php
				}
				?>
			</div>
			<?php
			echo $args['after_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		}
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
		$title       = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Fuel Economy', 'cardealer-helper' );
		$description = ! empty( $instance['description'] ) ? $instance['description'] : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'cardealer-helper' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php esc_html_e( 'Description:', 'cardealer-helper' ); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>"><?php echo esc_attr( $description ); ?></textarea>		
		</p>				        
		<?php
	}
}
