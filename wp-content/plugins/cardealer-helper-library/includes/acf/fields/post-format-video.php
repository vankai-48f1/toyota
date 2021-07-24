<?php
/**
 * Car post format video
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		/**
		 * Filters the arguments of the post format - video field group.
		 *
		 * @since 1.0
		 * @param array    $args    Arguments of the post format - video field group.
		 * @visible        true
		 */
		apply_filters(
			'cardealer_acf_post_format_video',
			array(
				'key'                   => 'group_5755547943bdc',
				'title'                 => esc_html__( 'Post Format - Video', 'cardealer-helper' ),
				'fields'                => array(
					array(
						'key'               => 'field_5755548e99883',
						'label'             => esc_html__( 'Video Type', 'cardealer-helper' ),
						'name'              => 'video_type',
						'type'              => 'radio',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-video_type acf_field_name-video_type',
							'id'    => '',
						),
						'choices'           => array(
							'html5'   => 'HTML5',
							'youtube' => 'YouTube',
							'vimeo'   => 'Vimeo',
						),
						'allow_null'        => 0,
						'other_choice'      => 0,
						'save_other_choice' => 0,
						'default_value'     => 'youtube',
						'layout'            => 'horizontal',
						'return_format'     => 'value',
					),
					array(
						'key'               => 'field_575554fb99884',
						'label'             => esc_html__( 'YouTube Video', 'cardealer-helper' ),
						'name'              => 'post_format_video_youtube',
						'type'              => 'oembed',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_5755548e99883',
									'operator' => '==',
									'value'    => 'youtube',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-post_format_video_youtube acf_field_name-post_format_video_youtube',
							'id'    => '',
						),
						'width'             => '',
						'height'            => '',
					),
					array(
						'key'               => 'field_5755559f99885',
						'label'             => esc_html__( 'Vimeo Video', 'cardealer-helper' ),
						'name'              => 'post_format_video_vimeo',
						'type'              => 'oembed',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_5755548e99883',
									'operator' => '==',
									'value'    => 'vimeo',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-post_format_video_vimeo acf_field_name-post_format_video_vimeo',
							'id'    => '',
						),
						'width'             => '',
						'height'            => '',
					),
					array(
						'key'               => 'field_575555af99886',
						'label'             => esc_html__( 'HTML5 Video', 'cardealer-helper' ),
						'name'              => 'post_format_video_html5',
						'type'              => 'repeater',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_5755548e99883',
									'operator' => '==',
									'value'    => 'html5',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-post_format_video_html5 acf_field_name-post_format_video_html5',
							'id'    => '',
						),
						'collapsed'         => '',
						'min'               => 1,
						'max'               => 1,
						'layout'            => 'row',
						'button_label'      => 'Add Row',
						'sub_fields'        => array(
							array(
								'key'               => 'field_5755575499887',
								'label'             => esc_html__( 'MP4', 'cardealer-helper' ),
								'name'              => 'mp4',
								'type'              => 'file',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => 'acf_field_name-mp4 acf_field_name-mp4',
									'id'    => '',
								),
								'return_format'     => 'array',
								'library'           => 'all',
								'min_size'          => '',
								'max_size'          => '',
								'mime_types'        => 'mp4',
							),
							array(
								'key'               => 'field_575557b099888',
								'label'             => esc_html__( 'WebM', 'cardealer-helper' ),
								'name'              => 'webm',
								'type'              => 'file',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => 'acf_field_name-webm acf_field_name-webm',
									'id'    => '',
								),
								'return_format'     => 'array',
								'library'           => 'all',
								'min_size'          => '',
								'max_size'          => '',
								'mime_types'        => 'webm',
							),
							array(
								'key'               => 'field_575557ce99889',
								'label'             => esc_html__( 'OGV', 'cardealer-helper' ),
								'name'              => 'ogv',
								'type'              => 'file',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => 'acf_field_name-ogv acf_field_name-ogv',
									'id'    => '',
								),
								'return_format'     => 'array',
								'library'           => 'all',
								'min_size'          => '',
								'max_size'          => '',
								'mime_types'        => 'ogv',
							),
							array(
								'key'               => 'field_575557dd9988a',
								'label'             => esc_html__( 'Cover', 'cardealer-helper' ),
								'name'              => 'cover',
								'type'              => 'image',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => 'acf_field_name-cover acf_field_name-cover',
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
							'value'    => 'video',
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
				'modified'              => 1465210888,
			)
		)
	);

endif;
