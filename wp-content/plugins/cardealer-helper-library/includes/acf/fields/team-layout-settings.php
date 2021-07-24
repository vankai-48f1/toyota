<?php
/**
 * Car team layour settings
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		/**
		 * Filters the arguments of the team layout settings field group.
		 *
		 * @since 1.0
		 * @param array    $args    Arguments of the team layout settings field group.
		 * @visible        true
		 */
		apply_filters(
			'cardealer_acf_team_layout_settings',
			array(
				'key'                   => 'group_575e6f8c698eb',
				'title'                 => esc_html__( 'Team Layout Settings', 'cardealer-helper' ),
				'fields'                => array(
					array(
						'key'               => 'field_575e6fe752aed',
						'label'             => esc_html__( 'Enable Custom Team Layout', 'cardealer-helper' ),
						'name'              => 'enable_custom_team_layout',
						'type'              => 'true_false',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-enable_custom_team_layout acf_field_name-enable_custom_team_layout acf_field_name-enable_custom_team_layout acf_field_name-enable_custom_team_layout acf_field_name-enable_custom_team_layout',
							'id'    => '',
						),
						'default_value'     => 0,
						'message'           => '',
						'ui'                => 0,
						'ui_on_text'        => '',
						'ui_off_text'       => '',
					),
					array(
						'key'               => 'field_575e6fa44d937',
						'label'             => esc_html__( 'Team Layout', 'cardealer-helper' ),
						'name'              => 'team_layout',
						'type'              => 'radio',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_575e6fe752aed',
									'operator' => '==',
									'value'    => '1',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf-image-radio acf_field_name-team_layout acf_field_name-team_layout acf_field_name-team_layout acf_field_name-team_layout acf_field_name-team_layout acf_field_name-team_layout',
							'id'    => '',
						),
						'layout'            => 'horizontal',
						'choices'           => array(
							'layout_1' => '<img src="http://192.168.0.187/wordpressProjects/cardealer_theme/cardealer_ver_1/wp-content/plugins/cardealer-helper-library/images/radio-button-imgs/team_layout/layout_1.png" alt="Layout 1" /><span class="radio_btn_title">Layout 1</span>',
							'layout_2' => '<img src="http://192.168.0.187/wordpressProjects/cardealer_theme/cardealer_ver_1/wp-content/plugins/cardealer-helper-library/images/radio-button-imgs/team_layout/layout_2.png" alt="Layout 2" /><span class="radio_btn_title">Layout 2</span>',
						),
						'default_value'     => 'layout_1',
						'other_choice'      => 0,
						'save_other_choice' => 0,
						'allow_null'        => 0,
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
							'operator' => '==',
							'value'    => 'templates/team.php',
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
