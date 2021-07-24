<?php
/**
 * Car page sidebar
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		/**
		 * Filters the arguments of the page sidebar field group.
		 *
		 * @since 1.0
		 * @param   array    $args  Arguments of the page sidebar field group.
		 * @visible          true
		 */
		apply_filters(
			'cardealer_acf_page_sidebar',
			array(
				'key'                   => 'group_57593c27830d8',
				'title'                 => esc_html__( 'Page Sidebar', 'cardealer-helper' ),
				'fields'                => array(
					array(
						'key'               => 'field_57593c2796b33',
						'label'             => esc_html__( 'Page Layout Custom', 'cardealer-helper' ),
						'name'              => 'page_layout_custom',
						'type'              => 'true_false',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-page_layout_custom acf_field_name-page_layout_custom acf_field_name-page_layout_custom',
							'id'    => '',
						),
						'message'           => 'Select this field to set custom page sidebar for this page.',
						'default_value'     => 0,
						'ui'                => 0,
						'ui_on_text'        => '',
						'ui_off_text'       => '',
					),
					array(
						'key'               => 'field_57593c2796f1a',
						'label'             => esc_html__( 'Page Sidebar', 'cardealer-helper' ),
						'name'              => 'page_sidebar',
						'type'              => 'radio',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_57593c2796b33',
									'operator' => '==',
									'value'    => '1',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf-image-radio acf_field_name-page_sidebar acf_field_name-page_sidebar acf_field_name-page_sidebar acf_field_name-page_sidebar acf_field_name-page_sidebar',
							'id'    => '',
						),
						'choices'           => array(
							'full_width'    => '<img src="http://192.168.0.187/wordpressProjects/cardealer_theme/cardealer_ver_1/wp-content/plugins/cardealer-helper-library/images/radio-button-imgs/page_sidebar/full_width.png" alt="Full width" /><span class="radio_btn_title">Full width</span>',
							'left_sidebar'  => '<img src="http://192.168.0.187/wordpressProjects/cardealer_theme/cardealer_ver_1/wp-content/plugins/cardealer-helper-library/images/radio-button-imgs/page_sidebar/left_sidebar.png" alt="Left sidebar" /><span class="radio_btn_title">Left sidebar</span>',
							'right_sidebar' => '<img src="http://192.168.0.187/wordpressProjects/cardealer_theme/cardealer_ver_1/wp-content/plugins/cardealer-helper-library/images/radio-button-imgs/page_sidebar/right_sidebar.png" alt="Right sidebar" /><span class="radio_btn_title">Right sidebar</span>',
							'two_sidebar'   => '<img src="http://192.168.0.187/wordpressProjects/cardealer_theme/cardealer_ver_1/wp-content/plugins/cardealer-helper-library/images/radio-button-imgs/page_sidebar/two_sidebar.png" alt="Two sidebar" /><span class="radio_btn_title">Two sidebar</span>',
						),
						'allow_null'        => 0,
						'other_choice'      => 0,
						'save_other_choice' => 0,
						'default_value'     => 'right_sidebar',
						'layout'            => 'horizontal',
						'return_format'     => 'value',
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'page',
						),
						array(
							'param'    => 'page_template',
							'operator' => '!=',
							'value'    => 'templates/page-vc_compatible.php',
						),
						array(
							'param'    => 'page_template',
							'operator' => '!=',
							'value'    => 'templates/team.php',
						),
						array(
							'param'    => 'page_template',
							'operator' => '!=',
							'value'    => 'templates/testimonials.php',
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
				'modified'              => 1465812745,
			)
		)
	);

endif;
