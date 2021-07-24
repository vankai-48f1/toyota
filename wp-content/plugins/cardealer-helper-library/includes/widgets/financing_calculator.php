<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Adds Cardealer Helpert Widget Financing Calculator.
 *
 * @package car-dealer-helper/functions
 * @version 1.0.0
 */

/**
 * Cardealer Helpert Widget Financing Calculator.
 */
class CarDealer_Helper_Widget_Financing_Calculator extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		$widget_ops = array(
			'classname'   => 'financing_calculator ',
			'description' => esc_html__( 'Add Financing Calculator widget in widget area.', 'cardealer-helper' ),
		);
		parent::__construct( 'financing_calculator', esc_html__( 'Car Dealer - Financing Calculator', 'cardealer-helper' ), $widget_ops );
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
		$title       = apply_filters( 'widget_title', ( empty( $instance['title'] ) ? esc_html__( 'Subscribe here', 'cardealer-helper' ) : $instance['title'] ), $instance, $this->id_base );
		$description = apply_filters( 'widget_description', ( empty( $instance['description'] ) ? '' : $instance['description'] ), $instance, $this->id_base );
		$button_text = apply_filters( 'widget_button_text', ( empty( $instance['button_text'] ) ? esc_html__( 'estimate payment', 'cardealer-helper' ) : $instance['button_text'] ), $instance, $this->id_base );
		$rate        = apply_filters( 'widget_calculator_rate', ( empty( $instance['rate'] ) ? '' : $instance['rate'] ), $instance, $this->id_base );

		$widget_id  = $args['widget_id'];
		$rnd        = wp_rand();
		$widget_id .= '-' . $rnd;
		echo $args['before_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		?>
		<div class="news-letter">		
			<?php
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			}

			if ( $description ) {
				echo '<p>' . $description . '</p>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			}

			?>

			<form class="financing-calculator" id="<?php echo esc_attr( $widget_id ); ?>">                
				<div class="form-group">
					<label><?php esc_html_e( 'Loan Amount', 'cardealer-helper' ); ?>*</label>
					<input type="text" name="loan_amount" id="loan-amount-<?php echo esc_attr( $widget_id ); ?>" value="" class="form-control"/>
				</div>
				<div class="form-group">
					<label><?php esc_html_e( 'Down Payment', 'cardealer-helper' ); ?>*</label>
					<input type="text" name="down_payment" id="down-payment-<?php echo esc_attr( $widget_id ); ?>" class="form-control"/>
				</div>
				<div class="form-group">
					<label><?php esc_html_e( 'Interest rate', 'cardealer-helper' ); ?>(%)*</label>
					<input type="text" name="interest_rate" id="interest-rate-<?php echo esc_attr( $widget_id ); ?>" class="form-control" value="<?php echo esc_attr( $rate ); ?>"/>
				</div>
				<div class="form-group">
					<label><?php esc_html_e( 'Period (Month)', 'cardealer-helper' ); ?>*</label>
					<input type="text" name="period" id="period-<?php echo esc_attr( $widget_id ); ?>" class="form-control" />
				</div>
				<div class="form-group">
					<label><?php esc_html_e( 'Payment', 'cardealer-helper' ); ?></label>                    
					<div class="cal_text payment-box">
						<div id="txtPayment-<?php echo esc_attr( $widget_id ); ?>"></div>
					</div>
				</div>                
				<div class="form-group">
					<a class="button red do_calculator" href="javascript:void(0);" data-form-id="<?php echo esc_attr( $widget_id ); ?>"><?php echo esc_html( $button_text ); ?></a>
					<a class="button red do_calculator_clear" href="javascript:void(0);" data-form-id="<?php echo esc_attr( $widget_id ); ?>"><?php echo esc_html__( 'clear', 'cardealer-helper' ); ?></a>
				</div>
			</form>
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
		$title       = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Financing Calculator', 'cardealer-helper' );
		$description = ! empty( $instance['description'] ) ? $instance['description'] : '';
		$rate        = ! empty( $instance['rate'] ) ? $instance['rate'] : '';
		$button_text = ! empty( $instance['button_text'] ) ? $instance['button_text'] : esc_html__( 'estimate payment', 'cardealer-helper' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'cardealer-helper' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php esc_html_e( 'Description:', 'cardealer-helper' ); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>"><?php echo esc_attr( $description ); ?></textarea>		
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'rate' ) ); ?>"><?php esc_html_e( 'Rate(in %):', 'cardealer-helper' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'rate' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'rate' ) ); ?>" type="number" step="any" value="<?php echo esc_attr( $rate ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>"><?php esc_html_e( 'Button Text:', 'cardealer-helper' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_text' ) ); ?>" type="text" value="<?php echo esc_attr( $button_text ); ?>">
		</p>		        
		<?php
	}
}
