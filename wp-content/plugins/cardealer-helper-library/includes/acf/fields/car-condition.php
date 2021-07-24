<?php
/**
 * Car tab
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		/**
		 * Filters the arguments of the vehicle condition taxonomy field group.
		 *
		 * @since 1.0
		 * @param array    $args Arguments of the vehicle condition taxonomy field group.
		 * @visible        true
		 */
		apply_filters(
			'cardealer_acf_car_condition',
			array(
				'key'                   => 'group_606d5802c2709',
				'title'                 => esc_html__( 'Vehicle Condition', 'cardealer-helper' ),
				'fields'                => array(
					array(
						'key'               => 'field_606d5813c6b22',
						'label'             => esc_html__( 'Label Color', 'cardealer-helper' ),
						'name'              => 'label_color',
						'type'              => 'color_picker',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'taxonomy',
							'operator' => '==',
							'value'    => 'car_condition',
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => true,
				'description'           => '',
			)
		)
	);

endif;
