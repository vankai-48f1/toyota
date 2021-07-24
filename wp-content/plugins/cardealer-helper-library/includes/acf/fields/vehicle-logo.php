<?php
/**
 * Car vehicle logo
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

if ( function_exists( 'acf_add_local_field_group' ) ) {
	acf_add_local_field_group(
		array(
			'key'                   => 'group_5ab3959a87896',
			'title'                 => 'Vehicle Logos',
			'fields'                => array(
				array(
					'key'               => 'field_5ab39d8ecd6aa',
					'label'             => 'Logo Image',
					'name'              => 'vehicle_logo',
					'type'              => 'image',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
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
			),
			'location'              => array(
				array(
					array(
						'param'    => 'taxonomy',
						'operator' => '==',
						'value'    => 'car_make',
					),
				),
				array(
					array(
						'param'    => 'taxonomy',
						'operator' => '==',
						'value'    => 'car_body_style',
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
		)
	);
}
