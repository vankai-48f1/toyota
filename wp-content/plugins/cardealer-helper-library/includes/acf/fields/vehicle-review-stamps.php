<?php
/**
 * Car vehicle review stamps
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		/**
		* Filters the array of arguments used to build vehicle review stamps field group.
		*
		* @since 1.0
		* @param    $args   Array of arguments used to build vehicle review stamps field group.
		* @visible  true
		*/
		apply_filters(
			'cardealer_acf_vehicle_review_stamps',
			array(
				'key'                   => 'group_5950c65234716',
				'title'                 => esc_html__( 'Vehicle Review Stamps Details', 'cardealer-helper' ),
				'fields'                => array(
					array(
						'key'               => 'field_5950c68f3bf75',
						'label'             => esc_html__( 'Image', 'cardealer-helper' ),
						'name'              => 'image',
						'type'              => 'image',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-image acf_field_name-image acf_field_name-image',
							'id'    => '',
						),
						'return_format'     => 'array',
						'preview_size'      => 'thumbnail',
						'library'           => 'all',
						'min_width'         => '',
						'min_height'        => '',
						'min_size'          => '',
						'max_width'         => '',
						'max_height'        => '',
						'max_size'          => '',
						'mime_types'        => '',
					),
					array(
						'key'               => 'field_5950c6b83bf76',
						'label'             => esc_html__( 'URL', 'cardealer-helper' ),
						'name'              => 'url',
						'type'              => 'url',
						'instructions'      => sprintf(
							/* translators: %s: url */
							esc_html__( 'Pass {{vin}} in to the link it will replace with real VIN number e.g. %1$s', 'cardealer-helper' ),
							esc_url( 'http://www.carfax.com/Report.cfx?vin=' ) . '{{vin}}'
						),
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-url acf_field_name-url acf_field_name-url',
							'id'    => '',
						),
						'default_value'     => '',
						'placeholder'       => '',
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'taxonomy',
							'operator' => '==',
							'value'    => 'car_vehicle_review_stamps',
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
				'modified'              => 1498465990,
			)
		)
	);

endif;
