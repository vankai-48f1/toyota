<?php
/**
 * Adds Vehicle Make Widget.
 *
 * @package car-dealer-helper/widgets
 * @version 1.9.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * CDHL_Widget_Vehicle_Make_Logos class.
 *
 * @extends CDHL_Widget
 */
class CDHL_Widget_Vehicle_Make_Logos extends CDHL_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		$this->widget_id          = 'vehicle_make';
		$this->widget_name        = esc_html__( 'Car Dealer - Vehicle Make Logos', 'cardealer-helper' );
		$this->widget_description = esc_html__( 'Display vehicle make logos.', 'cardealer-helper' );
		$this->widget_cssclass    = 'widget-vehicle-make-logos';
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'label' => esc_html__( 'Title:', 'cardealer-helper' ),
				'std'   => esc_html__( 'Vehicle Makes', 'cardealer-helper' ),
			),
			'show_title' => array(
				'type'        => 'checkbox',
				'std'         => 0,
				'label'       => esc_html__( 'Show Make Title?', 'cardealer-helper' ),
				'is_disabled' => false,
			),
			'scrollbar'  => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => esc_html__( 'Show Scrollbar?', 'cardealer-helper' ),
			),
			'max_height' => array(
				'type'  => 'number',
				'min'   => '100',
				'max'   => '5000',
				'step'  => '1',
				'std'   => '200',
				'label' => esc_html__( 'Maximum height:', 'cardealer-helper' ),
				'desc'  => esc_html__( 'Set maximum height to showing scrollbar. Min: 100', 'cardealer-helper' ),
			),
		);

		parent::__construct();
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
		$instance_new = $instance;
		unset( $instance_new['title'] );

		$filtered_makes = $this->get_filtered_makes();
		$instance_new['include_makes'] = $filtered_makes;

		$make_generator = new CDHL_Vehicle_Make_Logos_Generator();

		// Set whether archive page.
		$instance_new['is_vehicle_archive_page'] = $make_generator->is_vehicle_archive_page();

		$car_makes = $make_generator->get_makes();

		if ( empty( $car_makes ) || ! is_array( $car_makes ) ) {
			return;
		}

		echo $args['before_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $args['after_title'];
		}
		?>
		<div class="<?php cdhl_class_builder( 'cardealer-make-logos-wrap' ); ?>" data-widget_params="<?php echo esc_attr( wp_json_encode( $instance_new ) ); ?>">
			<?php $make_generator->generate_makes( $instance_new ); ?>
		</div>
		<?php

		echo $args['after_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
	}

	private function get_filtered_makes() {
		$taxonomys      = cardealer_get_filters_taxonomy();
		$get_arg        = array();
		$filtered_makes = array();

		foreach ( $taxonomys as $tax ) {
			/** Check from url if there any filter*/
			if ( isset( $_GET[ $tax ] ) && '' !== $_GET[ $tax ] ) {
				if ( isset( $_GET['car_mileage'] ) && ! empty( $_GET['car_mileage'] ) ) {
					$get_arg[] = array(
						'taxonomy' => $tax,
						'field'    => 'slug',
						'terms'    => array( $_GET[ $tax ] ),
						'compare'  => '<',
						'type'     => 'NUMERIC',
					);
				} else {
					$get_arg[] = array(
						'taxonomy' => $tax,
						'field'    => 'slug',
						'terms'    => array( $_GET[ $tax ] ),
					);
				}
			}
		}

		/**
		 * Filters the search arguments used in filtering vehicle inventory on inventory page.
		 *
		 * @since 1.0
		 *
		 * @param array      $get_arg   Array arguments for vehicle filter query.
		 * @visible          true
		 */
		$get_arg = apply_filters( 'cardealer_get_all_filters', $get_arg );

		$filter_tax = 'car_make';

		$result_filter = array();

		$filter_query_args                  = cardealer_make_filter_wp_query( $_GET );
		$filter_query_args['fields']        = 'ids';
		$filter_query_args['no_found_rows'] = true;
		$filter_query_args                  = array_replace( $filter_query_args, array( 'posts_per_page'=> -1 ) );

		$filter_query = new WP_Query( $filter_query_args );
		if ( $filter_query->have_posts() ) {
			if ( isset( $get_arg ) && ! empty( $get_arg ) && $filter_query->post_count > 0 ) {
				$filtered_makes = wp_get_object_terms( $filter_query->posts, $filter_tax, array(
					'orderby' => 'name',
					'order'   => 'ASC',
					'fields'  => 'slugs',
				) );
			}
			wp_reset_postdata();
		}
		return $filtered_makes;
	}
}
