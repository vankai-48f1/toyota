<?php
/**
 * Car description
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

if ( function_exists( 'acf_add_local_field_group' ) ) :
	global $car_dealer_options;

	$lat                  = isset( $car_dealer_options['default_value_lat'] ) ? $car_dealer_options['default_value_lat'] : '';
	$long                 = isset( $car_dealer_options['default_value_long'] ) ? $car_dealer_options['default_value_long'] : '';
	$mapzoom              = isset( $car_dealer_options['default_value_zoom'] ) ? $car_dealer_options['default_value_zoom'] : '';
	$features_options_tax = get_taxonomy( 'car_features_options' );

	acf_add_local_field_group(
		/**
		 * Filters the arguments of the vehicle tabs field group.
		 *
		 * @since 1.0
		 * @param array    $args    Arguments of the vehicle tabs field group.
		 * @visible        true
		 */
		apply_filters(
			'cardealer_acf_car_tabs',
			array(
				'key'                   => 'group_588f0eef75bc1',
				'title'                 => esc_html__( 'Vehicle Tabs', 'cardealer-helper' ),
				'fields'                => array(
					array(
						'key'               => 'field_588f0f24bbe3a',
						'label'             => esc_html__( 'Vehicle Overview', 'cardealer-helper' ),
						'name'              => '',
						'type'              => 'tab',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-vehicle_overview acf_field_name- acf_field_name-',
							'id'    => '',
						),
						'placement'         => 'left',
						'endpoint'          => 0,
					),
					array(
						'key'               => 'field_588f10e6bbe3b',
						'label'             => esc_html__( 'Vehicle Overview', 'cardealer-helper' ),
						'name'              => 'vehicle_overview',
						'type'              => 'wysiwyg',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-vehicle_overview acf_field_name-vehicle_overview acf_field_name-vehicle_overview',
							'id'    => '',
						),
						'tabs'              => 'all',
						'toolbar'           => 'full',
						'media_upload'      => 1,
						'default_value'     => '',
						'delay'             => 0,
					),
					array(
						'key'               => 'field_588f17196f58b',
						'label'             => esc_html__( 'Features & Options', 'cardealer-helper' ),
						'name'              => '',
						'type'              => 'tab',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-features_&_options_ acf_field_name- acf_field_name-',
							'id'    => '',
						),
						'placement'         => 'top',
						'endpoint'          => 0,
					),
					array(
						'key'               => 'field_588f17606f58c',
						'label'             => ( isset( $features_options_tax->label ) ) ? $features_options_tax->label : esc_html__( 'Features & Options', 'cardealer-helper' ),
						'name'              => 'features_and_options',
						'type'              => 'taxonomy',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-features_and_options acf_field_name-features_and_options acf_field_name-features_and_options acf_field_name-features_and_options acf_field_name-features_and_options',
							'id'    => '',
						),
						'taxonomy'          => 'car_features_options',
						'field_type'        => 'checkbox',
						'multiple'          => 0,
						'allow_null'        => 0,
						'return_format'     => 'id',
						'add_term'          => 1,
						'load_terms'        => 1,
						'save_terms'        => 1,
					),
					array(
						'key'               => 'field_588f181177747',
						'label'             => esc_html__( 'Technical Specifications', 'cardealer-helper' ),
						'name'              => '',
						'type'              => 'tab',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name- acf_field_name- acf_field_name-',
							'id'    => '',
						),
						'placement'         => 'top',
						'endpoint'          => 0,
					),
					array(
						'key'               => 'field_588f185e77748',
						'label'             => esc_html__( 'Technical Specifications', 'cardealer-helper' ),
						'name'              => 'technical_specifications',
						'type'              => 'wysiwyg',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name- acf_field_name-technical_specifications acf_field_name-technical_specifications acf_field_name-technical_specifications',
							'id'    => '',
						),
						'tabs'              => 'all',
						'toolbar'           => 'full',
						'media_upload'      => 1,
						'default_value'     => '',
						'delay'             => 0,
					),
					array(
						'key'               => 'field_588f18f6df0e8',
						'label'             => esc_html__( 'General Information', 'cardealer-helper' ),
						'name'              => '',
						'type'              => 'tab',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name- acf_field_name- acf_field_name-',
							'id'    => '',
						),
						'placement'         => 'top',
						'endpoint'          => 0,
					),
					array(
						'key'               => 'field_588f1902df0e9',
						'label'             => esc_html__( 'General Information', 'cardealer-helper' ),
						'name'              => 'general_information',
						'type'              => 'wysiwyg',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-general_information acf_field_name-general_information acf_field_name-general_information',
							'id'    => '',
						),
						'tabs'              => 'all',
						'toolbar'           => 'full',
						'media_upload'      => 1,
						'default_value'     => '',
						'delay'             => 0,
					),
					array(
						'key'               => 'field_5890308f94380',
						'label'             => esc_html__( 'Vehicle Location', 'cardealer-helper' ),
						'name'              => '',
						'type'              => 'tab',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name- acf_field_name- acf_field_name-',
							'id'    => '',
						),
						'placement'         => 'top',
						'endpoint'          => 0,
					),
					array(
						'key'               => 'field_589030b794381',
						'label'             => esc_html__( 'Vehicle Location', 'cardealer-helper' ),
						'name'              => 'vehicle_location',
						'type'              => 'google_map',
						'instructions'      => sprintf(
							wp_kses(
								/* Translators: %1$s Link to theme options. */
								__( 'This field requires a <strong>Google Maps API</strong> key to function properly. Please make sure that <strong>Google Maps API</strong> keys have been added to the <strong>Theme Options > Google API Settings > <a href="%1$s">Google Maps API Key</a> field</strong>.', 'cardealer-helper' ),
								array(
									'strong' => array(
										'style' => true,
									),
									'a'      => array(
										'href' => true,
									),
									'br'     => array(),
									'b'      => array(),
								)
							),
							esc_url( car_dealer_get_options_tab_url( 'google_maps_api' ) )
						)
						. '<br><br>' . esc_html__( 'Also, ensure that the API Account and API Keys are configured and working properly.', 'cardealer-helper' ),
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-vehicle_location acf_field_name-vehicle_location acf_field_name-vehicle_location',
							'id'    => '',
						),
						'height'            => '',
						'center_lat'        => $lat,
						'center_lng'        => $long,
						'zoom'              => $mapzoom,
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'cars',
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => 1,
				'description'           => '',
				'menu_item_level'       => 'all',
			)
		)
	);

endif;
