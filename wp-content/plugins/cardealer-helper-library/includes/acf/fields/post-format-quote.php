<?php
/**
 * Car post format quote
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		/**
		 * Filters the arguments of the post format - quote field group.
		 *
		 * @since 1.0
		 * @param array    $args    Arguments of the post format - quote field group.
		 * @visible        true
		 */
		apply_filters(
			'cardealer_acf_post_format_quote',
			array(
				'key'                   => 'group_5755766bb9e23',
				'title'                 => esc_html__( 'Post Format - Quote', 'cardealer-helper' ),
				'fields'                => array(
					array(
						'key'               => 'field_57557681e2878',
						'label'             => esc_html__( 'Quote', 'cardealer-helper' ),
						'name'              => 'quote',
						'type'              => 'textarea',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-quote acf_field_name-quote',
							'id'    => '',
						),
						'default_value'     => '',
						'placeholder'       => '',
						'maxlength'         => '',
						'rows'              => '',
						'new_lines'         => 'br',
						'readonly'          => 0,
						'disabled'          => 0,
					),
					array(
						'key'               => 'field_575576a6e2879',
						'label'             => esc_html__( 'Quote Author', 'cardealer-helper' ),
						'name'              => 'quote_author',
						'type'              => 'text',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-quote_author acf_field_name-quote_author',
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
						'key'               => 'field_575576bae287a',
						'label'             => esc_html__( 'Author Link', 'cardealer-helper' ),
						'name'              => 'author_link',
						'type'              => 'url',
						'instructions'      => esc_html__( 'Enter author URL if available.', 'cardealer-helper' ),
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-author_link acf_field_name-author_link',
							'id'    => '',
						),
						'default_value'     => '',
						'placeholder'       => '',
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
							'value'    => 'quote',
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
				'modified'              => 1465219048,
			)
		)
	);

endif;
