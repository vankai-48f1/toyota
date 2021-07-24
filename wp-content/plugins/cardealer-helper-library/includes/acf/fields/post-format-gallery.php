<?php
/**
 * Car post format gallery
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		/**
		 * Filters the arguments of the post format - gallery field group.
		 *
		 * @since 1.0
		 * @param array    $args    Arguments of the post format - gallery field group.
		 * @visible        true
		 */
		apply_filters(
			'cardealer_acf_post_format_gallery',
			array(
				'key'                   => 'group_57503acdbcf89',
				'title'                 => esc_html__( 'Post Format - Gallery', 'cardealer-helper' ),
				'fields'                => array(
					array(
						'key'               => 'field_57503d1deb752',
						'label'             => esc_html__( 'Gallery Type', 'cardealer-helper' ),
						'name'              => 'gallery_type',
						'type'              => 'radio',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-gallery_type acf_field_name-gallery_type',
							'id'    => '',
						),
						'choices'           => array(
							'slider' => 'Slider',
							'grid'   => 'Grid',
						),
						'allow_null'        => 0,
						'other_choice'      => 0,
						'save_other_choice' => 0,
						'default_value'     => 'slider',
						'layout'            => 'horizontal',
						'return_format'     => 'value',
					),
					array(
						'key'               => 'field_5755486b0ee98',
						'label'             => '',
						'name'              => '',
						'type'              => 'message',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_57503d1deb752',
									'operator' => '==',
									'value'    => 'grid',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name- acf_field_name-',
							'id'    => '',
						),
						'message'           => '<span style="color:#f00;">Add images in count 2 or 4.</span>',
						'new_lines'         => 'wpautop',
						'esc_html'          => 0,
					),
					array(
						'key'               => 'field_57512e726c0c1',
						'label'             => esc_html__( 'Gallery Images', 'cardealer-helper' ),
						'name'              => 'gallery_images',
						'type'              => 'repeater',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-gallery_images acf_field_name-gallery_images',
							'id'    => '',
						),
						'collapsed'         => 'field_57512eb96c0c2',
						'min'               => 0,
						'max'               => 0,
						'layout'            => 'table',
						'button_label'      => 'Add Image',
						'sub_fields'        => array(
							array(
								'key'               => 'field_57512eb96c0c2',
								'label'             => esc_html__( 'Image', 'cardealer-helper' ),
								'name'              => 'image',
								'type'              => 'image',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => 'acf_field_name-image acf_field_name-image',
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
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'post',
						),
						array(
							'param'    => 'post_format',
							'operator' => '==',
							'value'    => 'gallery',
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
				'modified'              => 1465209972,
			)
		)
	);

endif;
