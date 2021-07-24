<?php
/**
 * Car post format audio
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		/**
		 * Filters the arguments of the post format - audio field group.
		 *
		 * @since 1.0
		 * @param array    $args    Arguments of the post format - audio field group.
		 * @visible        true
		 */
		apply_filters(
			'cardealer_acf_post_format_audio',
			array(
				'key'                   => 'group_57556ed67cd60',
				'title'                 => esc_html__( 'Post Format - Audio', 'cardealer-helper' ),
				'fields'                => array(
					array(
						'key'               => 'field_57556ee290b92',
						'label'             => esc_html__( 'Audio File', 'cardealer-helper' ),
						'name'              => 'audio_file',
						'type'              => 'file',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-audio_file acf_field_name-audio_file',
							'id'    => '',
						),
						'return_format'     => 'array',
						'library'           => 'all',
						'min_size'          => '',
						'max_size'          => '',
						'mime_types'        => 'mp3',
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
							'value'    => 'audio',
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
				'modified'              => 1465216792,
			)
		)
	);

endif;
