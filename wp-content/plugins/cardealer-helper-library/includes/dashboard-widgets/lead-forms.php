<?php
/**
 * This function used on admin dashboard to display charts of lead forms
 *
 * @package car-dealer-helper
 */

if ( ! function_exists( 'cdhl_register_dashboard_widget' ) ) {
	/**
	 * Register Dashboard
	 */
	function cdhl_register_dashboard_widget() {
		global $wp_meta_boxes;

		add_meta_box( 'cdhl_dashboard_widget', 'Lead Chart', 'cdhl_dashboard_widget_display', 'dashboard', 'normal', 'core' );// Lead Chart.
		wp_enqueue_script( 'cdhl-chart-bundle', trailingslashit( CDHL_URL ) . 'js/chart/Chart.bundle.min.js', array(), CDHL_VERSION, true );
		wp_enqueue_script( 'cdhl-chart-utils', trailingslashit( CDHL_URL ) . 'js/chart/utils.js', array(), CDHL_VERSION, true );
		wp_enqueue_script( 'cdhl-jquery-goal-gauge', trailingslashit( CDHL_URL ) . 'js/chart/gauge.min.js', array(), CDHL_VERSION, true ); // gauge js for % chart.
		wp_enqueue_script( 'cdhl-chart-custom', trailingslashit( CDHL_URL ) . 'js/chart/chart_custom.js', array(), CDHL_VERSION, true );
	}
}
add_action( 'admin_init', 'cdhl_register_dashboard_widget', 0 );

if ( ! function_exists( 'cdhl_dashboard_widget_display' ) ) {
	/**
	 * Dashboard Widger display
	 */
	function cdhl_dashboard_widget_display() {
		$args = array(
			'post_type'      => 'pgs_inquiry',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'date_query'     => array(
				array(
					'after' => '1 week ago',
				),
			),
		);
		global $wpdb;
		// Weekly data.
		$rmi_sql              = "SELECT 'Request More Info' as lead_type, count(p.ID) AS total FROM $wpdb->posts p WHERE p.post_type = 'pgs_inquiry' and p.post_status = 'publish' and post_date between date_sub(now(),INTERVAL 1 WEEK) and now() ORDER BY post_date";
		$result['rmi_result'] = $wpdb->get_results( $rmi_sql );
		$mno_sql              = "SELECT 'Make an Offer' as lead_type, count(p.ID) AS total FROM $wpdb->posts p WHERE p.post_type = 'make_offer' and p.post_status = 'publish' and post_date between date_sub(now(),INTERVAL 1 WEEK) and now() ORDER BY post_date";
		$result['mno_result'] = $wpdb->get_results( $mno_sql );
		$std_sql              = "SELECT 'Schedule Test Drive' as lead_type, count(p.ID) AS total FROM $wpdb->posts p WHERE p.post_type = 'schedule_test_drive' and p.post_status = 'publish' and post_date between date_sub(now(),INTERVAL 1 WEEK) and now() ORDER BY post_date";
		$result['std_result'] = $wpdb->get_results( $std_sql );
		$fni_sql              = "SELECT 'Financial Information' as lead_type, count(p.ID) AS total FROM $wpdb->posts p WHERE p.post_type = 'financial_inquiry' and p.post_status = 'publish' and post_date between date_sub(now(),INTERVAL 1 WEEK) and now() ORDER BY post_date";
		$result['fni_result'] = $wpdb->get_results( $fni_sql );
		$result['chart_type'] = esc_html__( 'WEEKLY', 'cardealer-helper' );

		wp_localize_script( 'cdhl-chart-custom', 'lead_chart', json_encode( $result ) );
		?>
		<select id="display_by">
			<option value="weekly"><?php esc_html_e( 'Weekly', 'cardealer-helper' ); ?></option>
			<option value="monthly"><?php esc_html_e( 'Monthly', 'cardealer-helper' ); ?></option>
			<option value="yearly"><?php esc_html_e( 'Yearly', 'cardealer-helper' ); ?></option>
		</select>
		<canvas id="lead_canvas"></canvas>
		<?php
	}
}

add_action( 'wp_ajax_get_chart_data', 'cdhl_get_chart_data_feed' );
add_action( 'wp_ajax_nopriv_get_chart_data', 'cdhl_get_chart_data_feed' );
if ( ! function_exists( 'cdhl_get_chart_data_feed' ) ) {
	/**
	 * Get chart data
	 */
	function cdhl_get_chart_data_feed() {

		$action = ( isset( $_POST['action'] ) && ! empty( $_POST['action'] ) ) ? sanitize_text_field( wp_unslash( $_POST['action'] ) ) : '';

		if ( ! empty( $action ) && 'get_chart_data' === (string) $action ) {

			// Display type.
			$display_by = ( isset( $_POST['display_by'] ) && ! empty( $_POST['display_by'] ) ) ? sanitize_text_field( wp_unslash( $_POST['display_by'] ) ) : '';

			switch ( $display_by ) {

				case 'weekly':
					$date_function = 'WEEK';
					break;

				case 'monthly':
					$date_function = 'MONTH';
					break;

				default:
					$date_function = 'YEAR';
					break;
			}

			global $wpdb;

			$rmi_sql                  = "SELECT 'Request More Info' as lead_type, count(p.ID) AS total FROM $wpdb->posts p WHERE p.post_type = 'pgs_inquiry' and p.post_status = 'publish' and post_date between date_sub(now(),INTERVAL 1 $date_function) and now() ORDER BY post_date";
			$chart_feed['rmi_result'] = $wpdb->get_results( $rmi_sql );
			$mno_sql                  = "SELECT 'Make an Offer' as lead_type, count(p.ID) AS total FROM $wpdb->posts p WHERE p.post_type = 'make_offer' and p.post_status = 'publish' and post_date between date_sub(now(),INTERVAL 1 $date_function) and now() ORDER BY post_date";
			$chart_feed['mno_result'] = $wpdb->get_results( $mno_sql );
			$std_sql                  = "SELECT 'Schedule Test Drive' as lead_type, count(p.ID) AS total FROM $wpdb->posts p WHERE p.post_type = 'schedule_test_drive' and p.post_status = 'publish' and post_date between date_sub(now(),INTERVAL 1 $date_function) and now() ORDER BY post_date";
			$chart_feed['std_result'] = $wpdb->get_results( $std_sql );
			$fni_sql                  = "SELECT 'Financial Information' as lead_type, count(p.ID) AS total FROM $wpdb->posts p WHERE p.post_type = 'financial_inquiry' and p.post_status = 'publish' and post_date between date_sub(now(),INTERVAL 1 $date_function) and now() ORDER BY post_date";
			$chart_feed['fni_result'] = $wpdb->get_results( $fni_sql );
			$chart_feed['chart_type'] = esc_html( strtoupper( $display_by ) );

			if ( ! empty( $chart_feed ) ) {
				$result['chart_feed'] = wp_json_encode( $chart_feed );
				$result['status']     = 1;
			} else {
				$result['status'] = 0;
				$result['msg']    = sprintf(
					wp_kses(
						/* translators: %s: text */
						__( '<div class="alert alert-info">%1$s</div>', 'cardealer-helper' ),
						array(
							'div' => array(
								'class' => array(),
							),
						)
					),
					'No data found!'
				);
			}
		} else {
			$result['status'] = 0;
			$result['msg']    = sprintf(
				wp_kses(
					/* translators: %s: text */
					__( '<div class="alert alert-danger">%1$s</div>', 'cardealer-helper' ),
					array(
						'div' => array(
							'class' => array(),
						),
					)
				),
				'Something went wrong, Please try again later!'
			);
		}
		echo wp_json_encode( $result );
		die;
	}
}
