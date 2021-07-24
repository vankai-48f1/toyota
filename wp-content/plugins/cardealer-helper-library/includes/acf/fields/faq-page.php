<?php
/**
 * Car Faq
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		/**
		 * Filters the arguments of the faq page settings field group.
		 *
		 * @since 1.0
		 * @param array    $args    Arguments of the faq page settings field group.
		 * @visible        true
		 */
		apply_filters(
			'cardealer_acf_faq_page',
			array(
				'key'                   => 'group_58a15cba18b50',
				'title'                 => esc_html__( 'Faq Page Settings', 'cardealer-helper' ),
				'fields'                => array(
					array(
						'key'               => 'field_58a16054406f8',
						'label'             => esc_html__( 'Faq Type', 'cardealer-helper' ),
						'name'              => 'faq_type',
						'type'              => 'radio',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-faq_type acf_field_name-faq_type acf_field_name-faq_type',
							'id'    => '',
						),
						'layout'            => 'horizontal',
						'choices'           => array(
							'all'      => 'All',
							'category' => 'Category',
						),
						'default_value'     => 'all',
						'other_choice'      => 0,
						'save_other_choice' => 0,
						'allow_null'        => 0,
						'return_format'     => 'value',
					),
					array(
						'key'               => 'field_58a15f85ac2ba',
						'label'             => esc_html__( 'Select Category', 'cardealer-helper' ),
						'name'              => 'select_category',
						'type'              => 'taxonomy',
						'instructions'      => '',
						'required'          => 1,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_58a16054406f8',
									'operator' => '==',
									'value'    => 'category',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-select_category acf_field_name-select_category acf_field_name-select_category acf_field_name-select_category acf_field_name-select_category',
							'id'    => '',
						),
						'taxonomy'          => 'faq-category',
						'field_type'        => 'multi_select',
						'multiple'          => 0,
						'allow_null'        => 1,
						'return_format'     => 'id',
						'add_term'          => 0,
						'load_terms'        => 0,
						'save_terms'        => 0,
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'page_template',
							'operator' => '==',
							'value'    => 'templates/faq.php',
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
