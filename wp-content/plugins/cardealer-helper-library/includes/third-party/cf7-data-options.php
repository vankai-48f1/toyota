<?php
add_filter( 'cdhl_wpcf7_data_options', 'cdhl_wpcf7_data_options_add_month' );
add_filter( 'cdhl_wpcf7_data_options', 'cdhl_wpcf7_data_options_add_day' );
add_filter( 'cdhl_wpcf7_data_options', 'cdhl_wpcf7_data_options_add_year' );
add_filter( 'cdhl_wpcf7_data_options', 'cdhl_wpcf7_data_options_add_months' );
add_filter( 'cdhl_wpcf7_data_options', 'cdhl_wpcf7_data_options_add_years' );

function cdhl_wpcf7_data_options_add_month( $options ) {
	$month_data = array(
		array(
			'month'       => '1',
			'month_short' => esc_html__( 'Jan', 'cardealer-helper' ),
			'month_full'  => esc_html__( 'January', 'cardealer-helper' ),
		),
		array(
			'month'       => '2',
			'month_short' => esc_html__( 'Feb', 'cardealer-helper' ),
			'month_full'  => esc_html__( 'February', 'cardealer-helper' ),
		),
		array(
			'month'       => '3',
			'month_short' => esc_html__( 'Mar', 'cardealer-helper' ),
			'month_full'  => esc_html__( 'March', 'cardealer-helper' ),
		),
		array(
			'month'       => '4',
			'month_short' => esc_html__( 'Apr', 'cardealer-helper' ),
			'month_full'  => esc_html__( 'April', 'cardealer-helper' ),
		),
		array(
			'month'       => '5',
			'month_short' => esc_html__( 'May', 'cardealer-helper' ),
			'month_full'  => esc_html__( 'May', 'cardealer-helper' ),
		),
		array(
			'month'       => '6',
			'month_short' => esc_html__( 'Jun', 'cardealer-helper' ),
			'month_full'  => esc_html__( 'June', 'cardealer-helper' ),
		),
		array(
			'month'       => '7',
			'month_short' => esc_html__( 'Jul', 'cardealer-helper' ),
			'month_full'  => esc_html__( 'July', 'cardealer-helper' ),
		),
		array(
			'month'       => '8',
			'month_short' => esc_html__( 'Aug', 'cardealer-helper' ),
			'month_full'  => esc_html__( 'August', 'cardealer-helper' ),
		),
		array(
			'month'       => '9',
			'month_short' => esc_html__( 'Sep', 'cardealer-helper' ),
			'month_full'  => esc_html__( 'September', 'cardealer-helper' ),
		),
		array(
			'month'       => '10',
			'month_short' => esc_html__( 'Oct', 'cardealer-helper' ),
			'month_full'  => esc_html__( 'October', 'cardealer-helper' ),
		),
		array(
			'month'       => '11',
			'month_short' => esc_html__( 'Nov', 'cardealer-helper' ),
			'month_full'  => esc_html__( 'November', 'cardealer-helper' ),
		),
		array(
			'month'       => '12',
			'month_short' => esc_html__( 'Dec', 'cardealer-helper' ),
			'month_full'  => esc_html__( 'December', 'cardealer-helper' ),
		),
	);

	$null = array( '' => esc_html__( 'Month', 'cardealer-helper' ) );

	$options['month']                             = array_column( $month_data, 'month', 'month' );
	$options['month_with_null']                   = array_merge( $null, $options['month'] );
	$options['month_num_num']                     = array_column( $month_data, 'month', 'month' );
	$options['month_num_num_with_null']           = array_merge( $null, $options['month_num_num'] );
	$options['month_num_strshort']                = array_column( $month_data, 'month_short', 'month' );
	$options['month_num_strshort_with_null']      = array_merge( $null, $options['month_num_strshort'] );
	$options['month_num_strfull']                 = array_column( $month_data, 'month_full', 'month' );
	$options['month_num_strfull_with_null']       = array_merge( $null, $options['month_num_strfull'] );
	$options['month_strshort_strshort']           = array_column( $month_data, 'month_short', 'month_short' );
	$options['month_strshort_strshort_with_null'] = array_merge( $null, $options['month_strshort_strshort'] );
	$options['month_strshort_strfull']            = array_column( $month_data, 'month_full', 'month_short' );
	$options['month_strshort_strfull_with_null']  = array_merge( $null, $options['month_strshort_strfull'] );
	$options['month_strfull_strfull']             = array_column( $month_data, 'month_full', 'month_full' );
	$options['month_strfull_strfull_with_null']   = array_merge( $null, $options['month_strfull_strfull'] );

	return $options;
}


function cdhl_wpcf7_data_options_add_day( $options ) {
	$day  = array();
	$null = array( '' => esc_html__( 'Day', 'cardealer-helper' ) );

	for ( $i = 1; $i <= 31; $i++ ) {
		$day[$i] = $i;
	}

	$options['day']           = $day;
	$options['day_with_null'] = array_merge( $null, $options['day'] );

	return $options;
}


function cdhl_wpcf7_data_options_add_year( $options ) {
	$year = array();
	$null = array( '' => esc_html__( 'Year', 'cardealer-helper' ) );

	for ( $i = 1925; $i <= date( 'Y' ); $i++ ) {
		$year[$i] = $i;
	}

	$options['year']           = $year;
	$options['year_with_null'] = array_merge( $null, $options['year'] );

	return $options;
}


function cdhl_wpcf7_data_options_add_months( $options ) {
	$months = array();
	$null   = array( '' => esc_html__( 'Month(s)', 'cardealer-helper' ) );

	for ( $i = 1; $i <= 12; $i++ ) {
		$month_v = sprintf(
			/* translators: %s: Number of months */
			_n( '%s Month', '%s Months', $i, 'cardealer-helper' ),
			esc_html( $i )
		);

		$months[ $month_v ] = $month_v;
	}

	$options['months']           = $months;
	$options['months_with_null'] = array_merge( $null, $options['months'] );

	return $options;
}


function cdhl_wpcf7_data_options_add_years( $options ) {
	$years = array();
	$null  = array( '' => esc_html__( 'Year(s)', 'cardealer-helper' ) );

	for ( $i = 1; $i <= 30; $i++ ) {
		$year_v = sprintf(
			/* translators: %s: Number of years */
			_n( '%s Year', '%s Years', $i, 'cardealer-helper' ),
			esc_html( $i )
		);
		$years[ $year_v ] = $year_v;
	}

	$options['years']           = $years;
	$options['years_with_null'] = array_merge( $null, $options['years'] );

	return $options;
}
