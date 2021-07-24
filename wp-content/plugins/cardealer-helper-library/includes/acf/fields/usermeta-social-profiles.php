<?php
/**
 * Car Usemeta social profiles
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		/**
		 * Filters the arguments of social profiles field group.
		 *
		 * @since 1.0
		 * @param array   $args     Array arguments of social profiles field group.
		 * @visible       true
		 */
		apply_filters(
			'cardealer_acf_usermeta_social_profiles',
			array(
				'key'                   => 'group_57566c039a64d',
				'title'                 => esc_html__( 'User Meta - Social Profiles', 'cardealer-helper' ),
				'fields'                => array(
					array(
						'key'               => 'field_57566c4dd3064',
						'label'             => esc_html__( 'Facebook', 'cardealer-helper' ),
						'name'              => 'facebook',
						'type'              => 'url',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-facebook acf_field_name-facebook',
							'id'    => '',
						),
						'default_value'     => '',
						'placeholder'       => '',
					),
					array(
						'key'               => 'field_5756710a15b69',
						'label'             => esc_html__( 'Twitter', 'cardealer-helper' ),
						'name'              => 'twitter',
						'type'              => 'url',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-twitter acf_field_name-twitter',
							'id'    => '',
						),
						'default_value'     => '',
						'placeholder'       => '',
					),
					array(
						'key'               => 'field_5756711315b6a',
						'label'             => esc_html__( 'LinkedIn', 'cardealer-helper' ),
						'name'              => 'linkedin',
						'type'              => 'url',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-linkedin acf_field_name-linkedin',
							'id'    => '',
						),
						'default_value'     => '',
						'placeholder'       => '',
					),
					array(
						'key'               => 'field_5756712515b6b',
						'label'             => esc_html__( 'Pinterest', 'cardealer-helper' ),
						'name'              => 'pinterest',
						'type'              => 'url',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-pinterest acf_field_name-pinterest',
							'id'    => '',
						),
						'default_value'     => '',
						'placeholder'       => '',
					),
					array(
						'key'               => 'field_5756714015b6c',
						'label'             => esc_html__( 'Instagram', 'cardealer-helper' ),
						'name'              => 'instagram',
						'type'              => 'url',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-instagram acf_field_name-instagram',
							'id'    => '',
						),
						'default_value'     => '',
						'placeholder'       => '',
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'user_form',
							'operator' => '==',
							'value'    => 'edit',
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
				'modified'              => 1465282896,
			)
		)
	);

endif;
