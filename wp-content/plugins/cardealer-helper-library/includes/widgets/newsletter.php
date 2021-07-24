<?php
/**
 * Adds Cardealer Helpert Widget Newsletter.
 *
 * @package car-dealer-helper/functions
 * @version 1.0.0
 */

/**
 * Cardealer Helpert Widget Newsletter.
 */
class CarDealer_Helper_Widget_Newsletter extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		$widget_ops = array(
			'classname'   => 'mailchimp_newsletter',
			'description' => esc_html__( 'Add Mailchimp Newsletter widget in widget area.', 'cardealer-helper' ),
		);
		parent::__construct( 'newsletter_widget', esc_html__( 'Car Dealer - MailChimp Newsletter', 'cardealer-helper' ), $widget_ops );
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

		$title          = apply_filters( 'widget_title', ( empty( $instance['title'] ) ? esc_html__( 'Subscribe here', 'cardealer-helper' ) : $instance['title'] ), $instance, $this->id_base );
		$description    = apply_filters( 'widget_description', ( empty( $instance['description'] ) ? '' : $instance['description'] ), $instance, $this->id_base );
		$widget_id      = $args['widget_id'];
		$widget_form_id = 'form_' . $widget_id;
		echo $args['before_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		?>
		<div class="news-letter">		
			<?php if ( ! empty( $instance['title'] ) ) { ?>
				<?php echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>			
				<?php
			}
			?>
			<?php
			if ( $description ) {
				echo '<p>' . $description . '</p>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			}
			?>
			<form class="news-letter" id="<?php echo esc_attr( $widget_form_id ); ?>">					
				<input type="hidden" class="news-nonce" name="news_nonce" value="<?php echo wp_create_nonce( 'mailchimp_news' ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>">
				<input type="email" class="form-control newsletter-email placeholder" name="name" placeholder="<?php esc_html_e( 'Enter your Email', 'cardealer-helper' ); ?>">
				<a class="button red newsletter-mailchimp" href="#" data-form-id="<?php echo esc_attr( $widget_form_id ); ?>"><?php echo esc_html__( 'Subscribe', 'cardealer-helper' ); ?></a>
				<span class="spinimg-<?php echo esc_attr( $widget_form_id ); ?>"></span>
				<p class="newsletter-msg" style="display:none;"></p>
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
		$title       = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Newsletter', 'cardealer-helper' );
		$description = ! empty( $instance['description'] ) ? $instance['description'] : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'cardealer-helper' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php esc_html_e( 'description:', 'cardealer-helper' ); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>"><?php echo esc_attr( $description ); ?></textarea>		
		</p>
		<p>
			<?php
			echo sprintf(
				wp_kses(
					/* translators: $s: Enter description */
					__(
						'Enter description. Please ensure to add short content.<br>
                    <strong>Note:</strong> Please set both your MailChimp API key and list id in the API Keys panel. <a href="%1$s" target="_blank">Add API key here</a>',
						'cardealer-helper'
					),
					array(
						'br'     => array(),
						'strong' => array(),
						'a'      => array(
							'href'   => array(),
							'target' => array(),
						),
					)
				),
				esc_url( esc_url( site_url( 'wp-admin/admin.php?page=cardealer' ) ) )
			)
			?>
		</p>        	
		<?php
	}
}
