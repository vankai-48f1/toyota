<?php
/**
 * This function used on admin dashboard to display charts of lead forms
 *
 * @package car-dealer-helper
 */

/**
 * Load autoload file.
 */
require_once CDHL_PATH . 'vendor-lib/autoload.php';

if ( ! function_exists( 'cdhl_register_dashboard_widget_ga' ) ) {
	/**
	 * Register Dashboard widgets
	 */
	function cdhl_register_dashboard_widget_ga() {
		$access_token = cdhl_get_access_token(); // access_token.
		if ( ! function_exists( 'get_field' ) ) {
			return;
		}
		$view_id = get_field( 'view_id', 'option' );
		if ( empty( $view_id ) || empty( $access_token ) ) {
			return;
		}

		// Duration.
		$duration   = cdhl_get_duration();
		$start_date = $duration['start_date'];
		$end_date   = $duration['end_date'];
		wp_localize_script(
			'cdhl-chart-custom',
			'ga_chart',
			wp_json_encode(
				array(
					'start_date'   => $start_date,
					'end_date'     => $end_date,
					'access_token' => $access_token,
					'view_id'      => $view_id,
				)
			)
		);

		add_meta_box( 'cdhl_dashboard_widget_ga', esc_html__( 'Google Analytics', 'cardealer-helper' ), 'cdhl_dashboard_widget_display_report', 'dashboard', 'side', 'core' );// Google Analytics.
		add_meta_box( 'cdhl_dashboard_browser_widget_ga', esc_html__( 'Browser Usage', 'cardealer-helper' ), 'cdhl_dashboard_ga_browser_widget_data', 'dashboard', 'side', 'core' );// Browser Usage.
		add_meta_box( 'cdhl_dashboard_widget_cars_detail', esc_html__( 'Vehicles Detail', 'cardealer-helper' ), 'cdhl_dashboard_widget_cars_detail', 'dashboard', 'normal', 'core' ); // Vehicles Detail.
		add_meta_box( 'cdhl_dashboard_website_statistics', esc_html__( 'Website Statistics', 'cardealer-helper' ), 'cdhl_dashboard_website_statistics', 'dashboard', 'normal', 'core' );// Website Statistics.
		add_meta_box( 'cdhl_dashboard_website_users', esc_html__( 'Website Users', 'cardealer-helper' ), 'cdhl_dashboard_website_users', 'dashboard', 'normal', 'core' );// Website Users.
		add_meta_box( 'cdhl_dashboard_ga_goal_list', esc_html__( 'Google Analytics Goal', 'cardealer-helper' ), 'cdhl_dashboard_ga_goal_list', 'dashboard', 'side', 'core' );// Goal List.
	}
}
add_action( 'admin_init', 'cdhl_register_dashboard_widget_ga', 10 );

if ( ! function_exists( 'cdhl_dashboard_widget_cars_detail' ) ) {
	/**
	 * Dashboard widget cars detail
	 */
	function cdhl_dashboard_widget_cars_detail() {
		global $wpdb;

		// get carid list by inquiries count.
		$inquiry_report = $wpdb->get_results( 'SELECT pm.meta_value as car_id, COUNT(pm.post_id) AS total FROM ' . $wpdb->prefix . "postmeta pm JOIN $wpdb->posts p ON (p.ID = pm.post_id) WHERE pm.meta_key = 'car_id' AND ( p.post_type = 'pgs_inquiry' OR p.post_type = 'make_offer' OR p.post_type = 'schedule_test_drive' OR p.post_type = 'financial_inquiry') GROUP BY meta_value ORDER BY total DESC LIMIT 5", ARRAY_A );

		$car_list = array();
		foreach ( $inquiry_report as $inq ) :
			$car_year     = wp_get_post_terms( $inq['car_id'], 'car_year' );
			$car_make     = wp_get_post_terms( $inq['car_id'], 'car_make' );
			$car_model    = wp_get_post_terms( $inq['car_id'], 'car_model' );
			$vin_number   = wp_get_post_terms( $inq['car_id'], 'car_vin_number' );
			$stock_number = wp_get_post_terms( $inq['car_id'], 'car_stock_number' );

			// map car name with ids.
			$car_list[ $inq['car_id'] ]['name']         = $car_year[0]->name . ' ' . $car_make[0]->name . ' ' . $car_model[0]->name;
			$car_list[ $inq['car_id'] ]['inq']          = $inq['total'];
			$car_list[ $inq['car_id'] ]['vin_number']   = $vin_number[0]->name;
			$car_list[ $inq['car_id'] ]['stock_number'] = $stock_number[0]->name;
		endforeach;
		?>
		<div class="container">
			<p><?php esc_html_e( 'Vehicles Top Inquiries', 'cardealer-helper' ); ?></p>
		<?php
		if ( ! empty( $car_list ) ) {
			?>
			<table class="table" border="0" cellspacing="0" cellpadding="0">
				<thead>
				<tr>
					<th><?php esc_html_e( 'Vehicle Name', 'cardealer-helper' ); ?></th>
					<th><?php esc_html_e( 'VIN Number', 'cardealer-helper' ); ?></th>
					<th><?php esc_html_e( 'Stock Number', 'cardealer-helper' ); ?></th>
					<th><?php esc_html_e( 'Leads', 'cardealer-helper' ); ?></th>
				</tr>
				</thead>
				<tbody>
					<?php
					foreach ( $car_list as $cars ) :
						?>
						<tr>
							<td><?php echo esc_html( $cars['name'] ); ?></td>
							<td><?php echo esc_html( $cars['vin_number'] ); ?></td>
							<td><?php echo esc_html( $cars['stock_number'] ); ?></td>
							<td><?php echo esc_html( $cars['inq'] ); ?></td>
						</tr>
						<?php
					endforeach;
					?>
				</tbody>
			</table>
			<?php
		} else {
			esc_html_e( 'No Inquiries Submitted!', 'cardealer-helper' );
		}
		?>
		</div>
		<?php
		// display cars who don't have images.
		$empty_img_posts = 0;
		$car_query       = new WP_Query(
			array(
				'post_type'      => 'cars',
				'posts_per_page' => -1,
				'meta_key'       => 'car_images',
				'meta_query'     => array(
					'relation' => 'OR',
					array(
						'key'     => 'car_images',
						'compare' => 'NOT EXISTS',
					),
					array(
						'key'     => 'car_images',
						'value'   => '',
						'compare' => '=',
					),
				),

			)
		);
		$empty_img_posts = $car_query->found_posts;

		// Get cars with no comments.
		$emptycomments = 0;
		$comment_query = new WP_Query(
			array(
				'post_type'      => 'cars',
				'posts_per_page' => -1,
				'meta_key'       => 'general_information',
				'meta_query'     => array(
					'relation' => 'OR',
					array(
						'key'     => 'general_information',
						'compare' => 'NOT EXISTS',
					),
					array(
						'key'     => 'general_information',
						'value'   => '',
						'compare' => '=',
					),
				),

			)
		);
		$emptycomments = $comment_query->found_posts;
		?>
		<br>
		<div class="container">
			<p><?php esc_html_e( 'Vehicles Data', 'cardealer-helper' ); ?></p>
			<table class="table" border="0" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Detail', 'cardealer-helper' ); ?></th>
						<th><?php esc_html_e( 'No Of Vehicles', 'cardealer-helper' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php esc_html_e( 'Vehicles without images', 'cardealer-helper' ); ?></td>
						<td><?php echo esc_html( $empty_img_posts ); ?></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Vehicles without dealer comments', 'cardealer-helper' ); ?></td>
						<td><?php echo esc_html( $emptycomments ); ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<?php
	}
}

if ( ! function_exists( 'cdhl_dashboard_website_users' ) ) {
	/**
	 * Dashboard Website Users
	 */
	function cdhl_dashboard_website_users() {
		$access_token = cdhl_get_access_token();

		if ( ! function_exists( 'get_field' ) ) {
			return;
		}
		$view_id = get_field( 'view_id', 'option' );
		if ( empty( $view_id ) || empty( $access_token ) ) {
			return;
		}

		// Duration.
		$duration               = cdhl_get_duration();
		$start_date             = $duration['start_date'];
		$end_date               = $duration['end_date'];
		$get_chart_summary_data = get_transient( 'website_users' );

		if ( false === $get_chart_summary_data ) {
			$get_chart_summary_data = wp_remote_get( 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A' . $view_id . '&start-date=' . $start_date . '&end-date=' . $end_date . '&metrics=ga%3Ausers&dimensions=ga%3Aregion&access_token=' . $access_token );
			set_transient( 'website_users', $get_chart_summary_data, 60 * 60 * 4 ); // set transient for 4 hours.
		} else {
			$get_chart_summary_data = get_transient( 'website_users' );
		}
		if ( ! isset( $get_chart_summary_data-> errors) && 200 === (int) $get_chart_summary_data['response']['code'] ) {
			$rigion_data = json_decode( $get_chart_summary_data['body'], true );
			if ( $rigion_data['totalResults'] > 0 ) {
				?>
				<div id="state-chart-container" class="container">
					<p><?php esc_html_e( 'State Statistics', 'cardealer-helper' ); ?></p>
					<table class="table" border="0" cellspacing="0" cellpadding="0">
						<thead>
						<tr>
							<th><?php esc_html_e( 'State', 'cardealer-helper' ); ?></th>
							<th><?php esc_html_e( 'Users', 'cardealer-helper' ); ?></th>
						</tr>
						</thead>
						<tbody>
							<?php
							foreach ( $rigion_data['rows'] as $state ) :
								?>
								<tr>
									<td><?php echo esc_html( $state[0] ); ?></td>
									<td><?php echo esc_html( $state[1] ); ?></td>
								</tr>
								<?php
							endforeach;
							?>
						</tbody>
					</table>
				</div>
				<?php
			}
		}

		// city-chart-container.
		$get_chart_city_data = get_transient( 'website_cities' );
		if ( false === $get_chart_city_data ) {
			$get_chart_city_data = wp_remote_get( 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A' . $view_id . '&start-date=' . $start_date . '&end-date=' . $end_date . '&metrics=ga%3Ausers&dimensions=ga%3Acity&access_token=' . $access_token );
			set_transient( 'website_cities', $get_chart_city_data, 60 * 60 * 4 ); // set transient for 4 hours.
		} else {
			$get_chart_city_data = get_transient( 'website_cities' );
		}

		if ( ! isset( $get_chart_city_data-> errors) && 200 === (int) $get_chart_city_data['response']['code'] ) {
			$city_data = json_decode( $get_chart_city_data['body'], true );
			if ( $city_data['totalResults'] > 0 ) {
				?>
				<div id="city-chart-container" class="container">
					<p><?php esc_html_e( 'City Statistics', 'cardealer-helper' ); ?></p>
					<table class="table" border="0" cellspacing="0" cellpadding="0">
						<thead>
						<tr>
							<th><?php esc_html_e( 'City', 'cardealer-helper' ); ?></th>
							<th><?php esc_html_e( 'Users', 'cardealer-helper' ); ?></th>
						</tr>
						</thead>
						<tbody>
							<?php
							foreach ( $city_data['rows'] as $city ) :
								?>
								<tr>
									<td><?php echo esc_html( $city[0] ); ?></td>
									<td><?php echo esc_html( $city[1] ); ?></td>
								</tr>
								<?php
							endforeach;
							?>
						</tbody>
					</table>
				</div>
				<?php
			}
		}

		// Sites Searched.
		$get_chart_state_data = get_transient( 'website_states' );
		if ( false === $get_chart_state_data ) {
			$get_chart_state_data = wp_remote_get( 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A' . $view_id . '&start-date=' . $start_date . '&end-date=' . $end_date . '&metrics=ga%3Ausers&dimensions=ga%3AsearchUsed&access_token=' . $access_token );
			set_transient( 'website_states', $get_chart_state_data, 60 * 60 * 4 ); // set transient for 4 hours.
		} else {
			$get_chart_state_data = get_transient( 'website_states' );
		}

		if ( ! isset( $get_chart_state_data-> errors) && 200 === (int) $get_chart_state_data['response']['code'] ) {
			$site_data = json_decode( $get_chart_state_data['body'], true );
			if ( $site_data['totalResults'] > 0 ) {
				?>
				<div id="sites-chart-container" class="container">
					<p><?php esc_html_e( 'Site Referer Statistics', 'cardealer-helper' ); ?></p>
					<table class="table" border="0" cellspacing="0" cellpadding="0">
						<thead>
						<tr>
							<th><?php esc_html_e( 'Site Referer Status', 'cardealer-helper' ); ?></th>
							<th><?php esc_html_e( 'Users', 'cardealer-helper' ); ?></th>
						</tr>
						</thead>
						<tbody>
							<?php
							foreach ( $site_data['rows'] as $site ) :
								?>
								<tr>
									<td><?php echo esc_html( $site[0] ); ?></td>
									<td><?php echo esc_html( $site[1] ); ?></td>
								</tr>
								<?php
							endforeach;
							?>
						</tbody>
					</table>
				</div>
				<?php
			}
		}
	}
}

if ( ! function_exists( 'cdhl_dashboard_website_statistics' ) ) {
	/**
	 * Dashboard website statistics
	 */
	function cdhl_dashboard_website_statistics() {
		?>
		<div class="chart-container"><div><?php esc_html_e( 'Device Categories', 'cardealer-helper' ); ?></div><canvas id="device-chart-container" /></canvas></div>
		<div class="chart-container"><div><?php esc_html_e( 'Mobile Devices', 'cardealer-helper' ); ?></div><canvas id="mobile-device-container" /></canvas></div>
		<div class="chart-container"><div><?php esc_html_e( 'User Age Bracket', 'cardealer-helper' ); ?></div><canvas id="age-chart-container" /></canvas></div>
		<div class="chart-container"><div><?php esc_html_e( 'User Gender', 'cardealer-helper' ); ?></div><canvas id="gender-chart-container" /></canvas></div>
		<?php
	}
}


if ( ! function_exists( 'cdhl_dashboard_ga_browser_widget_data' ) ) {
	/**
	 * Dashboard browser widget data
	 */
	function cdhl_dashboard_ga_browser_widget_data() {
		?>
		<div class="chart-container"><canvas id="browser-pie-chart-container" /></canvas></div>
		<?php
		// Sites Searched.
		$access_token = cdhl_get_access_token();

		if ( ! function_exists( 'get_field' ) ) {
			return;
		}
		$view_id = get_field( 'view_id', 'option' );
		if ( empty( $view_id ) || empty( $access_token ) ) {
			return;
		}

		// Duration.
		$duration               = cdhl_get_duration();
		$start_date             = $duration['start_date'];
		$end_date               = $duration['end_date'];
		$get_browser_usage_data = get_transient( 'website_browser_usage' );
		if ( false === $get_browser_usage_data ) {
			$get_browser_usage_data = wp_remote_get( 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A' . $view_id . '&start-date=' . $start_date . '&end-date=' . $end_date . '&metrics=ga%3Apageviews&dimensions=ga%3Abrowser&access_token=' . $access_token );
			set_transient( 'website_browser_usage', $get_browser_usage_data, 60 * 60 * 4 ); // set transient for 4 hours.
		} else {
			$get_browser_usage_data = get_transient( 'website_browser_usage' );
		}
		if ( ! isset( $get_browser_usage_data-> errors) && 200 === (int) $get_browser_usage_data['response']['code'] ) {
			$browser_data = json_decode( $get_browser_usage_data['body'], true );
			if ( $browser_data['totalResults'] > 0 ) {
				?>
				<div id="browser-list-chart-container" class="container">
					<p><?php esc_html_e( 'Browser Usage Status', 'cardealer-helper' ); ?></p>
					<table class="table" border="0" cellspacing="0" cellpadding="0">
						<thead>
						<tr>
							<th><?php esc_html_e( 'Browser Used', 'cardealer-helper' ); ?></th>
							<th><?php esc_html_e( 'PageViews', 'cardealer-helper' ); ?></th>
						</tr>
						</thead>
						<tbody>
							<?php
							foreach ( $browser_data['rows'] as $browser ) :
								?>
								<tr>
									<td><?php echo esc_html( $browser[0] ); ?></td>
									<td><?php echo esc_html( $browser[1] ); ?></td>
								</tr>
								<?php
							endforeach;
							?>
						</tbody>
					</table>
				</div>
				<?php
			}
		}
	}
}

if ( ! function_exists( 'cdhl_dashboard_widget_display_report' ) ) {
	/**
	 * Dashboard widget display report
	 */
	function cdhl_dashboard_widget_display_report() {
		$access_token = cdhl_get_access_token(); // access_token.

		if ( ! function_exists( 'get_field' ) ) {
			return;
		}
		$view_id = get_field( 'view_id', 'option' );
		if ( empty( $view_id ) || empty( $access_token ) ) {
			return;
		}

		// Duration.
		$duration        = cdhl_get_duration();
		$start_date      = $duration['start_date'];
		$end_date        = $duration['end_date'];
		$get_full_report = get_transient( 'website_report' );
		if ( false === $get_full_report ) {
			$get_full_report = wp_remote_get( 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A' . $view_id . '&start-date=' . $start_date . '&end-date=' . $end_date . '&metrics=ga%3Ausers%2Cga%3Asessions%2Cga%3AbounceRate%2Cga%3AnewUsers%2Cga%3AavgSessionDuration%2Cga%3Apageviews&access_token=' . $access_token );

			set_transient( 'website_report', $get_full_report, 60 * 60 * 4 ); // set transient for 4 hours.
		} else {
			$get_full_report = get_transient( 'website_report' );
		}

		if ( ! is_wp_error( $get_full_report ) && 200 === (int) $get_full_report['response']['code'] ) {
			$summary_data         = json_decode( $get_full_report['body'], true );
			$users                = $summary_data['totalsForAllResults']['ga:users'];
			$sessions             = $summary_data['totalsForAllResults']['ga:sessions'];
			$pageviews            = $summary_data['totalsForAllResults']['ga:pageviews'];
			$bounce_rate          = $summary_data['totalsForAllResults']['ga:bounceRate'];
			$avg_session_duration = $summary_data['totalsForAllResults']['ga:avgSessionDuration'];
			$new_users            = $summary_data['totalsForAllResults']['ga:newUsers'];
		} else {
			return;
		}

		?>
		<div class="ga-users">
			<i><img src="<?php echo esc_url( CDHL_URL . '/images/chart/icon-1.png' ); ?>" /></i>
			<div class="number"><?php echo esc_html( $users ); ?></div>
			<span><?php esc_html_e( 'Total Users ', 'cardealer-helper' ); ?></span>
		</div>
		<div class="ga-pageviews">
			<i><img src="<?php echo esc_url( CDHL_URL . '/images/chart/icon-2.png' ); ?>" /></i>
			<div class="number"><?php echo esc_html( $pageviews ); ?></div>
			<span><?php esc_html_e( 'PageViews ', 'cardealer-helper' ); ?></span>
		</div>
		<div class="ga-bouncerate">
			<i><img src="<?php echo esc_url( CDHL_URL . '/images/chart/icon-3.png' ); ?>" /></i>
			<div class="number"><?php echo number_format( $bounce_rate, 2, '.', '' ); ?></div>
			<span><?php esc_html_e( 'BounceRate ', 'cardealer-helper' ); ?></span>
		</div>
		<div class="ga-avg-sessionduration">
			<i><img src="<?php echo esc_url( CDHL_URL . '/images/chart/icon-4.png' ); ?>" /></i>
			<div class="number"><?php echo number_format( $avg_session_duration, 2, '.', '' ); ?></div>
			<span><?php esc_html_e( 'Avg SessionDuration ', 'cardealer-helper' ); ?></span>
		</div>
		<div class="ga-sessions">
			<i><img src="<?php echo esc_url( CDHL_URL . '/images/chart/icon-5.png' ); ?>" /></i>
			<div class="number"><?php echo esc_html( $sessions ); ?></div>
			<span><?php esc_html_e( 'Sessions ', 'cardealer-helper' ); ?></span>
		</div>
		<div class="ga-newusers">
			<i><img src="<?php echo esc_url( CDHL_URL . '/images/chart/icon-6.png' ); ?>" /></i>
			<div class="number"><?php echo esc_html( $new_users ); ?></div>
			<span><?php esc_html_e( 'NewUsers ', 'cardealer-helper' ); ?></span>
		</div>
		<canvas id="google-analytics"></canvas>
		<?php
	}
}

if ( ! function_exists( 'cdhl_dashboard_ga_goal_list' ) ) {
	/**
	 * Dashboard Goal list
	 */
	function cdhl_dashboard_ga_goal_list() {
		// Sites Searched.
		$access_token = cdhl_get_access_token();
		if ( ! function_exists( 'get_field' ) ) {
			return;
		}
		$view_id     = trim( get_field( 'view_id', 'option' ) );
		$account_id  = trim( get_field( 'account_id', 'option' ) );
		$tracking_id = trim( get_field( 'tracking_id', 'option' ) );
		if ( empty( $view_id ) || empty( $access_token ) || empty( $account_id ) || empty( $tracking_id ) ) {
			return;
		}

		$goal_ids      = array();
		$duration      = cdhl_get_duration(); // Duration.
		$start_date    = $duration['start_date'];
		$end_date      = $duration['end_date'];
		$goal_response = get_transient( 'website_goal_list' );
		if ( false === $goal_response ) {
			$goal_response = wp_remote_get( 'https://www.googleapis.com/analytics/v3/management/accounts/' . $account_id . '/webproperties/' . $tracking_id . '/profiles/' . $view_id . '/goals?&access_token=' . $access_token );
			set_transient( 'website_goal_list', $goal_response, 60 * 60 * 4 ); // set transient for 4 hours.
		} else {
			$goal_response = get_transient( 'website_goal_list' );
		}

		if ( isset( $goal_response-> errors) && 200 !== (int) $goal_response['response']['code'] ) {
			esc_html_e( 'No Goal Data Found!', 'cardealer-helper' );
			return;
		}

		$goalresults = json_decode( $goal_response['body'] );
		?>
		<div class="chart-container">
			<?php
			$cnt = 0;
			if ( isset( $goalresults->items ) ) {
				foreach ( $goalresults->items as $goal ) {
					// Get indevisual goal data in detail.
					$goal_detail_response = get_transient( 'goal_detailed_response_' . $goal->id );
					if ( false === $goal_detail_response ) {
						$goal_detail_response = wp_remote_get( 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga:' . $view_id . '&start-date=' . $start_date . '&end-date=' . $end_date . '&metrics=ga:goal' . $goal->id . 'Completions%2Cga:goal' . $goal->id . 'ConversionRate%2Cga:goal' . $goal->id . 'Starts&dimensions=ga:source&sort=-ga:goal' . $goal->id . 'Starts&max-results=10000&access_token=' . $access_token );
						if( isset( $goal_detail_response-> errors) ){
							continue;
						}
						set_transient( 'goal_detailed_response_' . $goal->id, $goal_detail_response, 60 * 60 * 4 ); // set transient for 4 hours.
					} else {
						$goal_detail_response = get_transient( 'goal_detailed_response_' . $goal->id );
					}

					$goal_response_array = $goal_detail_response;

					$goal_completions     = null;
					$goal_conversion_rate = null;
					if ( isset($goal_response_array['totalsForAllResults']) ) {
						$goal_completions     = $goal_response_array['totalsForAllResults'][ 'ga:goal' . $goal->id . 'Completions' ];
						$goal_conversion_rate = $goal_response_array['totalsForAllResults'][ 'ga:goal' . $goal->id . 'ConversionRate' ];
					}

					$resources = isset( $goal_response_array['rows'] ) ? $goal_response_array['rows'] : array();

					// to be used for meter chart for conversion rate.
					$goal_ids[ $cnt ]['id']          = $goal->id;
					$goal_ids[ $cnt++ ]['goal_rate'] = $goal_conversion_rate;
					?>
					<div class="goals" id="cdhl_dashboard_widget_ga">
						<div class="goal-header">
							<h4>
								<b><?php echo esc_html( $goal->name ); ?></b>
							</h4>
						</div>
						<div class="goal-icons inside">
							<div class="section-left users">
								<i><img src="<?php echo esc_url( CDHL_URL . '/images/chart/icon-7.png' ); ?>"></i>
								<div class="number"><?php echo esc_html( $goal_completions ); ?></div>
								<span><?php echo esc_html__( 'Completions', 'cardealer-helper' ); ?> </span>
								<span >
									<a href="javascript:void(0)" class="cd_dialog" data-id="goal-res-<?php echo esc_attr( $goal->id ); ?>">
										<?php
											echo esc_html__( 'Sources : ', 'cardealer-helper' );
											echo esc_html( $goal_completions );
										?>
									</a>
								</span>
							</div>
							<div class="section-right users">
								<canvas id="goal-<?php echo esc_attr( $goal->id ); ?>" style="width: 150px; float: left; padding: 0px;"></canvas>
								<span><?php echo esc_html__( 'Conversion Rate', 'cardealer-helper' ); ?> </span>
								<span><?php echo esc_html( number_format( (float) $goal_conversion_rate, 2, '.', '' ) ); ?>%</span>
							</div>
							<table id="goal-res-<?php echo esc_attr( $goal->id ); ?>" class="goal-content table" border="0" cellspacing="0" cellpadding="0" >
								<thead>
									<th colspan=2><?php esc_html_e( 'Refering Sources', 'cardealer-helper' ); ?></th>
								</thead>
								<tbody>
								<?php
								if ( ! empty( $resources ) ) {
									foreach ( $resources as $row ) {
										if ( 0 !== (int) $row[1] ) {
											?>
											<tr><td> <?php echo esc_html( $row[0] ) . '<br>'; ?> </td><td> <?php echo esc_html( $row[1] ) . '<br>'; ?> </td></tr>
											<?php
										}
									}
								}
								?>
								</tbody>
							</table>
						</div>
					</div>
					<?php
				}
			}
			?>
		</div>
		<?php
		wp_localize_script( 'cdhl-chart-custom', 'ga_goal', json_encode( array( 'goal_ids' => $goal_ids ) ) );
	}
}

if ( ! function_exists( 'cdhl_get_access_token' ) ) {
	/**
	 * Get access token
	 */
	function cdhl_get_access_token() {
		if ( function_exists( 'get_field' ) ) {
			$key_file = get_field( 'key_file', 'option' );// __DIR__ . '/google-analytics/service-account-credentials.json'; .

			// check key file available.
			if ( empty( $key_file ) || false === cdhl_check_keyfile_exist( $key_file['url'] ) ) {
				return;
			}
			if ( ! empty( $key_file ) ) {
				$filetype = wp_check_filetype( $key_file['filename'] );
				$ext      = strtolower( $filetype['ext'] );
				if ( 'json' !== $ext ) {
					return;
				}
			}
			$key_file_path = get_attached_file( $key_file['id'] ); // Full path.
			try {
				$client = new Google_Client();
				$client->setApplicationName( 'Hello Analytics Reporting' );
				$client->setAuthConfig( $key_file_path );
				$client->setScopes( array( 'https://www.googleapis.com/auth/analytics.readonly' ) );
				$client->refreshTokenWithAssertion();
				$token = $client->getAccessToken();
			} catch ( Exception $e ) {
				return;
			}
			return $token['access_token'];
		}
		return;
	}
}

if ( ! function_exists( 'cdhl_get_duration' ) ) {
	/**
	 * Get duration
	 */
	function cdhl_get_duration() {
		if ( function_exists( 'get_field' ) ) {
			$custom_duration = get_field( 'custom_time', 'option' );
			$cd_start_date   = get_field( 'start_date', 'option' );
			$cd_end_date     = get_field( 'end_date', 'option' );
			$start_date      = ( ! empty( $cd_start_date ) && true === (bool) $custom_duration ) ? $cd_start_date : '30daysAgo';
			$end_date        = ( ! empty( $cd_end_date ) && true === (bool) $custom_duration ) ? $cd_end_date : 'today';
			return array(
				'start_date' => $start_date,
				'end_date'   => $end_date,
			);
		}
		return;
	}
}

if ( ! function_exists( 'cdhl_check_keyfile_exist' ) ) {
	/**
	 * Check keyfile exist
	 *
	 * @param string $keyfile key file.
	 */
	function cdhl_check_keyfile_exist( $keyfile ) {
		$ch = curl_init( $keyfile );
		curl_setopt( $ch, CURLOPT_NOBODY, true );
		curl_exec( $ch );
		$code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );

		if ( 200 === (int) $code ) {
			$status = true;
		} else {
			$status = false;
		}
		curl_close( $ch );
		return $status;
	}
}
?>
