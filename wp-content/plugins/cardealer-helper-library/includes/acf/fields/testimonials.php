<?php
/**
 * Car testimonials
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		/**
		 * Filters the arguments of the testimonials field group.
		 *
		 * @since 1.0
		 * @param array    $args    Arguments of the testimonials field group.
		 * @visible        true
		 */
		apply_filters(
			'cardealer_acf_testimonials',
			array(
				'key'                   => 'group_57a46e1723537',
				'title'                 => esc_html__( 'Testimonials', 'cardealer-helper' ),
				'fields'                => array(
					array(
						'key'               => 'field_57a46e3928abf',
						'label'             => esc_html__( 'Content', 'cardealer-helper' ),
						'name'              => 'content',
						'type'              => 'textarea',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-content acf_field_name-content acf_field_name-content',
							'id'    => '',
						),
						'default_value'     => '',
						'placeholder'       => '',
						'maxlength'         => '',
						'rows'              => '',
						'new_lines'         => '',
						'readonly'          => 0,
						'disabled'          => 0,
					),
					array(
						'key'               => 'field_57a46e2a28abe',
						'label'             => esc_html__( 'Designation', 'cardealer-helper' ),
						'name'              => 'designation',
						'type'              => 'text',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-designation acf_field_name-designation acf_field_name-designation',
							'id'    => '',
						),
						'default_value'     => '',
						'placeholder'       => '',
						'prepend'           => '',
						'append'            => '',
						'maxlength'         => '',
						'readonly'          => 0,
						'disabled'          => 0,
					),
					array(
						'key'               => 'field_5874977219004',
						'label'             => esc_html__( 'Profile Image', 'cardealer-helper' ),
						'name'              => 'profile_image',
						'type'              => 'image',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-profile_image acf_field_name-profile_image',
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
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'testimonials',
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => array(
					0  => 'permalink',
					1  => 'the_content',
					2  => 'excerpt',
					3  => 'custom_fields',
					4  => 'discussion',
					5  => 'comments',
					6  => 'revisions',
					7  => 'slug',
					8  => 'author',
					9  => 'format',
					10 => 'page_attributes',
					11 => 'categories',
					12 => 'tags',
					13 => 'send-trackbacks',
				),
				'active'                => 1,
				'description'           => '',
				'modified'              => 1470394021,
			)
		)
	);

endif;
