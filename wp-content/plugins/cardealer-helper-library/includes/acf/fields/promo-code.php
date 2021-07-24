<?php
/**
 * Car promo code
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		/**
		 * Filters the arguments of the promo code field group.
		 *
		 * @since 1.0
		 * @param array    $args    Arguments of the promo code field group.
		 * @visible        true
		 */
		apply_filters(
			'cardealer_acf_promo_code',
			array(
				'key'                   => 'group_595cd62377b37',
				'title'                 => esc_html__( 'Promo Code', 'cardealer-helper' ),
				'fields'                => array(
					array(
						'key'               => 'field_59633307788a7',
						'label'             => esc_html__( 'Promo Code Page', 'cardealer-helper' ),
						'name'              => 'promo_code_page',
						'type'              => 'select',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-promo_code_page acf_field_name-promo_code_page acf_field_name-promo_code_page',
							'id'    => '',
						),
						'choices'           => array(),
						'default_value'     => array(),
						'allow_null'        => 0,
						'multiple'          => 0,
						'ui'                => 0,
						'ajax'              => 0,
						'return_format'     => 'value',
						'placeholder'       => '',
					),
					array(
						'key'               => 'field_5963334a788a8',
						'label'             => esc_html__( 'Promo Code', 'cardealer-helper' ),
						'name'              => 'promo_code',
						'type'              => 'text',
						'instructions'      => '',
						'required'          => 1,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-promo_code acf_field_name-promo_code acf_field_name-promo_code acf_field_name-promo_code',
							'id'    => '',
						),
						'default_value'     => '',
						'placeholder'       => '',
						'prepend'           => '',
						'append'            => '',
						'maxlength'         => 150,
					),
					array(
						'key'               => 'field_59633365788a9',
						'label'             => esc_html__( 'Promo Code URL', 'cardealer-helper' ),
						'name'              => 'promo_code_url',
						'type'              => 'url',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_59633307788a7',
									'operator' => '==',
									'value'    => 'custom',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-promo_code_url acf_field_name-promo_code_url acf_field_name-promo_code_url acf_field_name-promo_code_url acf_field_name-promo_code_url',
							'id'    => '',
						),
						'default_value'     => '',
						'placeholder'       => '',
					),
					array(
						'key'               => 'field_5963348799c76',
						'label'             => esc_html__( 'Status', 'cardealer-helper' ),
						'name'              => 'status',
						'type'              => 'radio',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-status acf_field_name-status acf_field_name-status acf_field_name-status',
							'id'    => '',
						),
						'choices'           => array(
							'enable'  => 'Enable',
							'disable' => 'Disable',
						),
						'allow_null'        => 0,
						'other_choice'      => 0,
						'save_other_choice' => 0,
						'default_value'     => 'enable',
						'layout'            => 'horizontal',
						'return_format'     => 'value',
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'cars_promocodes',
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => array(
					0 => 'the_content',
					1 => 'author',
					2 => 'featured_image',
					3 => 'categories',
					4 => 'tags',
				),
				'active'                => 1,
				'description'           => '',
				'menu_item_level'       => 'all',
			)
		)
	);

endif;
