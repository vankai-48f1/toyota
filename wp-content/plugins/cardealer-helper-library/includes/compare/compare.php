<?php
/**
 * Theme vehicle compare functions.
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package cardealer-helper-library/functions
 * @version 1.0.0
 */

if ( ! function_exists( 'cdhl_compare_popup_title' ) ) {
	/**
	 * Compare popup title
	 *
	 * @param bool $echo .
	 */
	function cdhl_compare_popup_title( $echo = true ) {
		$popup_title = esc_html__( 'Compare Vehicles', 'cardealer-helper' );

		/**
		 * Filters the title display on the compare vehicle pop-up.
		 *
		 * @since 1.0
		 * @param string    $popup_title    Title of the compare vehicle pop-up.
		 * @visible         true
		 */
		$popup_title = apply_filters( 'cdhl_compare_popup_title', $popup_title );

		if ( $echo ) {
			echo esc_html( $popup_title );
		} else {
			return $popup_title;
		}
	}
}

if ( ! function_exists( 'cdhl_compare_required_fields' ) ) {
	/**
	 * List of compare required fields.
	 *
	 * @return array
	 */
	function cdhl_compare_required_fields() {

		$required_fields = array(
			'remove'    => '',
			'car_image' => '',
			'price'     => esc_html__( 'Price', 'cardealer-helper' ),
		);

		/**
		 * Filters the array of vehicle compare required fields.
		 *
		 * @param array $required_fields Array of compare fields.
		 * @return array
		 * @visible true
		 */
		$required_fields = apply_filters( 'cdhl_compare_required_fields', $required_fields );

		return $required_fields;
	}
}

if ( ! function_exists( 'cdhl_compare_column_fields' ) ) {
	/**
	 * Compare column field
	 */
	function cdhl_compare_column_fields() {
		// Get option value.
		$vehicle_compare_fields_options = Redux::get_option( 'car_dealer_options', 'vehicle_compare_fields' );

		if ( isset( $vehicle_compare_fields_options['Available']['placebo'] ) ) {
			unset( $vehicle_compare_fields_options['Available']['placebo'] );
		}
		if ( isset( $vehicle_compare_fields_options['Selected']['placebo'] ) ) {
			unset( $vehicle_compare_fields_options['Selected']['placebo'] );
		}

		$required_fields        = cdhl_compare_required_fields();
		$features_options_field = array(
			'features_options'  => cardealer_get_field_label_with_tax_key( 'car_features_options', 'plural' ),
		);

		if ( ! empty( $vehicle_compare_fields_options['Selected'] ) ) {
			$cars_taxes = array_keys( $vehicle_compare_fields_options['Selected'] );
		} else {
			$cars_taxes = get_object_taxonomies( 'cars' );
			unset( $cars_taxes['vehicle_cat'] );
			unset( $cars_taxes['car_features_options'] );
			unset( $cars_taxes['car_vehicle_review_stamps'] );
		}

		foreach ( $cars_taxes as $car_tax ) {
			$compare_field[ $car_tax ] = cardealer_get_field_label_with_tax_key( $car_tax );
		}

		/**
		 * Filters the array of vehicle compare fields.
		 *
		 * @since 1.0
		 * @param array $compare_field Array of compare fields.
		 * @return array
		 * @visible true
		 */
		$compare_field = apply_filters( 'cdhl_compare_column_fields', $compare_field );
		$compare_field = array_merge( $required_fields, $compare_field, $features_options_field );

		return $compare_field;
	}
}


/**
 * Compare column delete, image, price
 *
 * @see cdhl_compare_column_delete()
 * @see cdhl_compare_column_image()
 * @see cdhl_compare_column_price()
 */
add_action( 'cdhl_compare_column_before_attributes', 'cdhl_compare_column_delete', 10, 2 );
add_action( 'cdhl_compare_column_before_attributes', 'cdhl_compare_column_image', 20, 2 );
add_action( 'cdhl_compare_column_before_attributes', 'cdhl_compare_column_price', 30, 2 );
if ( ! function_exists( 'cdhl_compare_column_delete' ) ) {
	/**
	 * Compare column delete
	 *
	 * @param string $class .
	 * @param string $car_id .
	 */
	function cdhl_compare_column_delete( $class, $car_id ) {
		?>
		<tr class="delete">
			<td class="<?php echo esc_attr( $class ); ?>" data-id="<?php echo esc_attr( $car_id ); ?>">
				<a href="javascript:void(0)" data-car_id="<?php echo esc_attr( $car_id ); ?>" class="drop_item"><span class="remove">x</span></a>
			</td>
		</tr>
		<?php
	}
}

if ( ! function_exists( 'cdhl_compare_column_image' ) ) {
	/**
	 * Compare column image
	 *
	 * @param string $class .
	 * @param string $car_id .
	 */
	function cdhl_compare_column_image( $class, $car_id ) {
		$carlink = get_permalink( $car_id );
		?>
		<tr class="image">
			<td class="<?php echo esc_attr( $class ); ?>" data-id="<?php echo esc_attr( $car_id ); ?>">
				<a href="<?php echo esc_url( $carlink ); ?>">
					<?php
					if ( function_exists( 'cardealer_get_cars_image' ) ) {
						echo wp_kses( cardealer_get_cars_image( 'car_thumbnail', $car_id ), cardealer_allowed_html( array( 'img' ) ) );
					}
					?>
				</a>
			</td>
		</tr>
		<?php
	}
}

if ( ! function_exists( 'cdhl_compare_column_price' ) ) {
	/**
	 * Compare column price
	 *
	 * @param string $class .
	 * @param string $car_id .
	 */
	function cdhl_compare_column_price( $class, $car_id ) {
		$price_html = cardealer_car_price_html( '', $car_id, false, false );

		if ( empty( $price_html ) ) {
			$price_html = '<div class="price car-price"><span class="new-price">&mdash;</span></div>';
		}
		?>
		<tr class="price car-item">
			<td class="<?php echo esc_attr( $class ); ?>" data-id="<?php echo esc_attr( $car_id ); ?>">
				<?php
				echo wp_kses(
					apply_filters( 'cardealer_car_price_html', $price_html, $car_id ),
					array(
						'div'  => array(
							'class' => true,
						),
						'p'    => array(),
						'span' => array(
							'class' => true,
						),
					)
				);
				?>
			</td>
		</tr>
		<?php
	}
}

/**
 * Column delete
 *
 * @see cdhl_compare_column_attributes_data()
 */
add_action( 'cdhl_compare_column_attributes', 'cdhl_compare_column_attributes_data', 10, 3 );
if ( ! function_exists( 'cdhl_compare_column_attributes_data' ) ) {
	/**
	 * Compare column delete
	 *
	 * @param string $compare_fields .
	 * @param string $class .
	 * @param string $car_id .
	 */
	function cdhl_compare_column_attributes_data( $compare_fields, $class, $car_id ) {

		$required_fields = cdhl_compare_required_fields();

		foreach ( $compare_fields as $key => $val ) {
			if ( array_key_exists( $key, $required_fields ) ) {
				continue;
			}
			?>
			<tr class="<?php echo esc_attr( $key ); ?>">
				<td class="<?php echo esc_attr( $class ); ?>" data-id="<?php echo esc_attr( $car_id ); ?>">
					<?php
					if ( 'features_options' === (string) $key ) {
						$car_features_options = wp_get_post_terms( $car_id, 'car_features_options' );
						$json                 = wp_json_encode( $car_features_options ); // Conver Obj to Array.
						$car_features_options = json_decode( $json, true ); // Conver Obj to Array.
						$name_array           = array_map(
							function ( $options ) {
								return $options['name'];
							},
							(array) $car_features_options
						); // get all name term array.
						$options              = implode( ',', $name_array );
						$options_data         = ( ! isset( $options ) || empty( $options ) ) ? '&nbsp;' : $options;
						$html                 = $options_data;
						echo esc_html( $html );
					} else {
						$vehicle_terms = wp_get_post_terms( $car_id, $key );
						if ( ! isset( $vehicle_terms ) || empty( $vehicle_terms ) ) {
							echo '&mdash;';
						} else {
							echo esc_html( $vehicle_terms[0]->name );
						}
					}
					?>
				</td>
			</tr>
			<?php
		}
	}
}

