<?php
/**
 * Car page setting
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		/**
		 * Filters the arguments of the page settings field group.
		 *
		 * @since 1.0
		 * @param array    $args    Arguments of the page settings field group.
		 * @visible        true
		 */
		apply_filters(
			'cardealer_acf_page_settings',
			array(
				'key'                   => 'group_57501ad11cf25',
				'title'                 => esc_html__( 'Page Settings', 'cardealer-helper' ),
				'fields'                => array(
					array(
						'key'               => 'field_575b8aa2183cd',
						'label'             => esc_html__( 'General', 'cardealer-helper' ),
						'name'              => '',
						'type'              => 'tab',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name-',
							'id'    => '',
						),
						'placement'         => 'top',
						'endpoint'          => 0,
					),
					array(
						'key'               => 'field_58b669dff5a7b',
						'label'             => esc_html__( 'Sticky Header Height On Scroll', 'cardealer-helper' ),
						'name'              => 'sticky_header_height',
						'type'              => 'number',
						'instructions'      => esc_html__( 'If this will be left empty, then it will use height from theme options.', 'cardealer-helper' ),
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-sticky_header_height acf_field_name-sticky_header_height acf_field_name-sticky_header_height acf_field_name-sticky_header_height acf_field_name-sticky_header_height acf_field_name-sticky_header_height acf_field_name-sticky_header_height acf_field_name-sticky_header_height',
							'id'    => '',
						),
						'default_value'     => '',
						'placeholder'       => '',
						'prepend'           => '',
						'append'            => '',
						'min'               => '',
						'max'               => '',
						'step'              => '',
						'readonly'          => 0,
						'disabled'          => 0,
					),
					array(
						'key'               => 'field_59f30d68fdc25',
						'label'             => 'Enable Custom Layout',
						'name'              => 'enable_custom_layout',
						'type'              => 'true_false',
						'instructions'      => esc_html__( 'Currently, it\'s displaying default layout from theme options. If you want to use different site layout for this page then select this checkbox.', 'cardealer-helper' ),
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-enable_custom_layout',
							'id'    => '',
						),
						'message'           => '',
						'default_value'     => 0,
						'ui'                => 0,
						'ui_on_text'        => '',
						'ui_off_text'       => '',
					),
					array(
						'key'               => 'field_59f30aeea17f0',
						'label'             => 'Page Layout',
						'name'              => 'page_layout',
						'type'              => 'radio',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_59f30d68fdc25',
									'operator' => '==',
									'value'    => '1',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-page_layout acf_field_name-page_layout acf_field_name-page_layout',
							'id'    => '',
						),
						'choices'           => array(
							'standard' => 'Standard',
							'boxed'    => 'Boxed',
							'framed'   => 'Framed',
						),
						'allow_null'        => 0,
						'other_choice'      => 0,
						'save_other_choice' => 0,
						'default_value'     => '',
						'layout'            => 'vertical',
						'return_format'     => 'value',
					),
					array(
						'key'               => 'field_57aeb3da3956e',
						'label'             => esc_html__( 'Hide Page Header', 'cardealer-helper' ),
						'name'              => 'hide_header_banner',
						'type'              => 'true_false',
						'instructions'      => esc_html__( 'Select this check box to hide page header.', 'cardealer-helper' ),
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-hide_header_banner acf_field_name-hide_header_banner acf_field_name-hide_header_banner acf_field_name-hide_header_banner acf_field_name-hide_header_banner acf_field_name-hide_header_banner acf_field_name-hide_header_banner acf_field_name-hide_header_banner acf_field_name-hide_header_banner acf_field_name-hide_header_banner acf_field_name-hide_header_banner',
							'id'    => '',
						),
						'default_value'     => 0,
						'message'           => '',
						'ui'                => 0,
						'ui_on_text'        => '',
						'ui_off_text'       => '',
					),
					array(
						'key'               => 'field_575aac608d83a',
						'label'             => esc_html__( 'Page Header', 'cardealer-helper' ),
						'name'              => '',
						'type'              => 'tab',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_57aeb3da3956e',
									'operator' => '!=',
									'value'    => '1',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name-',
							'id'    => '',
						),
						'placement'         => 'top',
						'endpoint'          => 0,
					),
					array(
						'key'               => 'field_57501afdd09e3',
						'label'             => esc_html__( 'Subtitle', 'cardealer-helper' ),
						'name'              => 'subtitle',
						'type'              => 'text',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_57aeb3da3956e',
									'operator' => '!=',
									'value'    => '1',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-subtitle acf_field_name-subtitle acf_field_name-subtitle acf_field_name-subtitle acf_field_name-subtitle acf_field_name-subtitle acf_field_name-subtitle acf_field_name-subtitle acf_field_name-subtitle acf_field_name-subtitle acf_field_name-subtitle',
							'id'    => '',
						),
						'default_value'     => '',
						'maxlength'         => '',
						'placeholder'       => '',
						'prepend'           => '',
						'append'            => '',
						'readonly'          => 0,
						'disabled'          => 0,
					),
					array(
						'key'               => 'field_58f9a42d72e12',
						'label'             => esc_html__( 'Page Header Height', 'cardealer-helper' ),
						'name'              => 'page_header_height',
						'type'              => 'number',
						'instructions'      => esc_html__( 'If this will be left empty, then it will use height from theme options', 'cardealer-helper' ),
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_57aeb3da3956e',
									'operator' => '!=',
									'value'    => '1',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-page_header_height acf_field_name-page_header_height acf_field_name-page_header_height acf_field_name-page_header_height acf_field_name-page_header_height acf_field_name-page_header_height acf_field_name-page_header_height acf_field_name-page_header_height',
							'id'    => '',
						),
						'default_value'     => '',
						'placeholder'       => '',
						'prepend'           => '',
						'append'            => '',
						'min'               => '',
						'max'               => '',
						'step'              => '',
						'readonly'          => 0,
						'disabled'          => 0,
					),
					array(
						'key'               => 'field_58c112cd50a61',
						'label'             => esc_html__( 'Enable Title Alignment', 'cardealer-helper' ),
						'name'              => 'enable_title_alignment',
						'type'              => 'true_false',
						'instructions'      => esc_html__( 'Currently, it\'s displaying title alignment by setting from theme options. If you want to use different alignment for this page, select this checkbox.', 'cardealer-helper' ),
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_57aeb3da3956e',
									'operator' => '!=',
									'value'    => '1',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-enable_title_alignment acf_field_name-enable_title_alignment acf_field_name-enable_title_alignment acf_field_name-enable_title_alignment acf_field_name-enable_title_alignment acf_field_name-enable_title_alignment acf_field_name-enable_title_alignment acf_field_name-enable_title_alignment',
							'id'    => '',
						),
						'message'           => '',
						'default_value'     => 0,
						'ui'                => 0,
						'ui_on_text'        => '',
						'ui_off_text'       => '',
					),
					array(
						'key'               => 'field_58f9ccc0587a3',
						'label'             => esc_html__( 'Title Alignment', 'cardealer-helper' ),
						'name'              => 'title_alignment',
						'type'              => 'select',
						'instructions'      => esc_html__( 'If this will be left empty, then it will take text alignment settings from theme options', 'cardealer-helper' ),
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_57aeb3da3956e',
									'operator' => '!=',
									'value'    => '1',
								),
								array(
									'field'    => 'field_58c112cd50a61',
									'operator' => '==',
									'value'    => '1',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-titlebar_text_align acf_field_name-titlebar_text_align acf_field_name-title_alignment acf_field_name-title_alignment acf_field_name-title_alignment acf_field_name-title_alignment acf_field_name-title_alignment acf_field_name-title_alignment acf_field_name-title_alignment acf_field_name-title_alignment',
							'id'    => '',
						),
						'multiple'          => 0,
						'allow_null'        => 0,
						'choices'           => array(
							'default'         => 'All Left (default)',
							'center'          => 'All Center',
							'right'           => 'All Right',
							'title_l_bread_r' => 'Title Left / Breadcrumb Right',
							'bread_l_title_r' => 'Title Right / Breadcrumb Left',
						),
						'default_value'     => array(),
						'ui'                => 0,
						'ajax'              => 0,
						'placeholder'       => '',
						'return_format'     => 'value',
					),
					array(
						'key'               => 'field_575b89a55beeb',
						'label'             => esc_html__( 'Enable Custom Banner', 'cardealer-helper' ),
						'name'              => 'enable_custom_banner',
						'type'              => 'true_false',
						'instructions'      => esc_html__( 'Currently, it\'s displaying banner by setting from theme options. If you want to customize banner for this page, select this checkbox.', 'cardealer-helper' ),
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_57aeb3da3956e',
									'operator' => '!=',
									'value'    => '1',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-enable_custom_banner acf_field_name-enable_custom_banner acf_field_name-enable_custom_banner acf_field_name-enable_custom_banner acf_field_name-enable_custom_banner acf_field_name-enable_custom_banner acf_field_name-enable_custom_banner acf_field_name-enable_custom_banner acf_field_name-enable_custom_banner acf_field_name-enable_custom_banner acf_field_name-enable_custom_banner',
							'id'    => '',
						),
						'default_value'     => 0,
						'message'           => '',
						'ui'                => 0,
						'ui_on_text'        => '',
						'ui_off_text'       => '',
					),
					array(
						'key'               => 'field_575aac9c8d83b',
						'label'             => esc_html__( 'Banner Type', 'cardealer-helper' ),
						'name'              => 'banner_type',
						'type'              => 'radio',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_575b89a55beeb',
									'operator' => '==',
									'value'    => '1',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-banner_type acf_field_name-banner_type acf_field_name-banner_type acf_field_name-banner_type acf_field_name-banner_type acf_field_name-banner_type acf_field_name-banner_type acf_field_name-banner_type acf_field_name-banner_type acf_field_name-banner_type acf_field_name-banner_type acf_field_name-banner_type acf_field_name-banner_type',
							'id'    => '',
						),
						'choices'           => array(
							'image' => 'Image',
							'color' => 'Color',
							'video' => 'Video',
						),
						'allow_null'        => 0,
						'other_choice'      => 0,
						'save_other_choice' => 0,
						'default_value'     => '',
						'layout'            => 'horizontal',
						'return_format'     => 'value',
					),
					array(
						'key'               => 'field_57aeb66ddf305',
						'label'             => esc_html__( 'Banner Image', 'cardealer-helper' ),
						'name'              => 'banner_image_bg_custom',
						'type'              => 'image',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_575b89a55beeb',
									'operator' => '==',
									'value'    => '1',
								),
								array(
									'field'    => 'field_575aac9c8d83b',
									'operator' => '==',
									'value'    => 'image',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-banner_image_bg acf_field_name-banner_image_bg acf_field_name-banner_image_bg acf_field_name-banner_image_bg_custom acf_field_name-banner_image_bg_custom acf_field_name-banner_image_bg_custom acf_field_name-banner_image_bg_custom acf_field_name-banner_image_bg_custom acf_field_name-banner_image_bg_custom acf_field_name-banner_image_bg_custom acf_field_name-banner_image_bg_custom acf_field_name-banner_image_bg_custom acf_field_name-banner_image_bg_custom acf_field_name-banner_image_bg_custom acf_field_name-banner_image_bg_custom',
							'id'    => '',
						),
						'return_format'     => 'url',
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
					array(
						'key'               => 'field_58b686559c557',
						'label'             => esc_html__( 'Background Repeat', 'cardealer-helper' ),
						'name'              => 'background_repeat',
						'type'              => 'select',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_575b89a55beeb',
									'operator' => '==',
									'value'    => '1',
								),
								array(
									'field'    => 'field_575aac9c8d83b',
									'operator' => '==',
									'value'    => 'image',
								),
							),
						),
						'wrapper'           => array(
							'width' => '40',
							'class' => 'acf_field_name-background_repeat acf_field_name-background_repeat acf_field_name-background_repeat acf_field_name-background_repeat acf_field_name-background_repeat acf_field_name-background_repeat acf_field_name-background_repeat acf_field_name-background_repeat',
							'id'    => '',
						),
						'choices'           => array(
							'no-repeat' => 'No Repeat',
							'repeat'    => 'Repeat All',
							'repeat-x'  => 'Repeat Horizontally',
							'repeat-y'  => 'Repeat Vertically',
							'inherit'   => 'Inherit',
						),
						'default_value'     => array(),
						'allow_null'        => 0,
						'multiple'          => 0,
						'ui'                => 0,
						'ajax'              => 0,
						'placeholder'       => '',
						'disabled'          => 0,
						'readonly'          => 0,
						'return_format'     => 'value',
					),
					array(
						'key'               => 'field_58b68f4850fb3',
						'label'             => esc_html__( 'Background Size', 'cardealer-helper' ),
						'name'              => 'background_size',
						'type'              => 'select',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_575b89a55beeb',
									'operator' => '==',
									'value'    => '1',
								),
								array(
									'field'    => 'field_575aac9c8d83b',
									'operator' => '==',
									'value'    => 'image',
								),
							),
						),
						'wrapper'           => array(
							'width' => '40',
							'class' => 'acf_field_name-background_size acf_field_name-background_size acf_field_name-background_size acf_field_name-background_size acf_field_name-background_size acf_field_name-background_size acf_field_name-background_size acf_field_name-background_size',
							'id'    => '',
						),
						'choices'           => array(
							'inherit' => 'Inherit',
							'cover'   => 'Cover',
							'contain' => 'Contain',
						),
						'default_value'     => array(),
						'allow_null'        => 0,
						'multiple'          => 0,
						'ui'                => 0,
						'ajax'              => 0,
						'placeholder'       => '',
						'disabled'          => 0,
						'readonly'          => 0,
						'return_format'     => 'value',
					),
					array(
						'key'               => 'field_58b685e69c556',
						'label'             => esc_html__( 'Background Attachment', 'cardealer-helper' ),
						'name'              => 'background_attachment',
						'type'              => 'select',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_575b89a55beeb',
									'operator' => '==',
									'value'    => '1',
								),
								array(
									'field'    => 'field_575aac9c8d83b',
									'operator' => '==',
									'value'    => 'image',
								),
							),
						),
						'wrapper'           => array(
							'width' => '40',
							'class' => 'acf_field_name-background_attachment acf_field_name-background_attachment acf_field_name-background_attachment acf_field_name-background_attachment acf_field_name-background_attachment acf_field_name-background_attachment acf_field_name-background_attachment acf_field_name-background_attachment',
							'id'    => '',
						),
						'choices'           => array(
							'fixed'            => 'Fixed',
							'scroll'           => 'Scroll',
							'inherit: Inherit' => 'inherit: Inherit',
						),
						'default_value'     => array(),
						'allow_null'        => 0,
						'multiple'          => 0,
						'ui'                => 0,
						'ajax'              => 0,
						'placeholder'       => '',
						'disabled'          => 0,
						'readonly'          => 0,
						'return_format'     => 'value',
					),
					array(
						'key'               => 'field_58b6842c9c554',
						'label'             => esc_html__( 'Background Position', 'cardealer-helper' ),
						'name'              => 'background_position',
						'type'              => 'select',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_575b89a55beeb',
									'operator' => '==',
									'value'    => '1',
								),
								array(
									'field'    => 'field_575aac9c8d83b',
									'operator' => '==',
									'value'    => 'image',
								),
							),
						),
						'wrapper'           => array(
							'width' => '40',
							'class' => 'acf_field_name-background_position acf_field_name-background_position acf_field_name-background_position acf_field_name-background_position acf_field_name-background_position acf_field_name-background_position acf_field_name-background_position acf_field_name-background_position',
							'id'    => '',
						),
						'choices'           => array(
							'left top'                   => 'Left Top',
							'left center'                => 'Left Center',
							'left bottom'                => 'Left Bottom',
							'center top'                 => 'Center Top',
							'center center'              => 'Center Center',
							'center bottom'              => 'Center Bottom',
							'right top'                  => 'Right Top',
							'right center'               => 'Right center',
							'right bottom: Right Bottom' => 'right bottom: Right Bottom',
						),
						'default_value'     => array(),
						'allow_null'        => 0,
						'multiple'          => 0,
						'ui'                => 0,
						'ajax'              => 0,
						'placeholder'       => '',
						'disabled'          => 0,
						'readonly'          => 0,
						'return_format'     => 'value',
					),
					array(
						'key'               => 'field_575abc7c96f15',
						'label'             => esc_html__( 'Background Opacity Color', 'cardealer-helper' ),
						'name'              => 'background_opacity_color',
						'type'              => 'radio',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_575aac9c8d83b',
									'operator' => '==',
									'value'    => 'image',
								),
								array(
									'field'    => 'field_575b89a55beeb',
									'operator' => '==',
									'value'    => '1',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-background_opacity_color acf_field_name-background_opacity_color acf_field_name-background_opacity_color acf_field_name-background_opacity_color acf_field_name-background_opacity_color acf_field_name-background_opacity_color acf_field_name-background_opacity_color acf_field_name-background_opacity_color acf_field_name-background_opacity_color acf_field_name-background_opacity_color acf_field_name-background_opacity_color acf_field_name-background_opacity_color',
							'id'    => '',
						),
						'choices'           => array(
							'none'   => 'None',
							'black'  => 'Black',
							'white'  => 'White',
							'custom' => 'Custom',
						),
						'allow_null'        => 0,
						'other_choice'      => 0,
						'save_other_choice' => 0,
						'default_value'     => '',
						'layout'            => 'horizontal',
						'return_format'     => 'value',
					),
					array(
						'key'               => 'field_575abd19e7d39',
						'label'             => esc_html__( 'Background Opacity Color (Custom Color)', 'cardealer-helper' ),
						'name'              => 'banner_image_opacity_custom_color',
						'type'              => 'color_picker',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_575aac9c8d83b',
									'operator' => '==',
									'value'    => 'image',
								),
								array(
									'field'    => 'field_575abc7c96f15',
									'operator' => '==',
									'value'    => 'custom',
								),
								array(
									'field'    => 'field_575b89a55beeb',
									'operator' => '==',
									'value'    => '1',
								),
							),
						),
						'wrapper'           => array(
							'width' => 50,
							'class' => 'acf_field_name-banner_image_opacity_custom_color acf_field_name-banner_image_opacity_custom_color acf_field_name-banner_image_opacity_custom_color acf_field_name-banner_image_opacity_custom_color acf_field_name-banner_image_opacity_custom_color acf_field_name-banner_image_opacity_custom_color acf_field_name-banner_image_opacity_custom_color acf_field_name-banner_image_opacity_custom_color acf_field_name-banner_image_opacity_custom_color acf_field_name-banner_image_opacity_custom_color acf_field_name-banner_image_opacity_custom_color',
							'id'    => '',
						),
						'default_value'     => '#000000',
					),
					array(
						'key'               => 'field_575ac40722619',
						'label'             => esc_html__( 'Background Opacity Color (Custom Opacity)', 'cardealer-helper' ),
						'name'              => 'banner_image_opacity_custom_opacity',
						'type'              => 'number',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_575aac9c8d83b',
									'operator' => '==',
									'value'    => 'image',
								),
								array(
									'field'    => 'field_575abc7c96f15',
									'operator' => '==',
									'value'    => 'custom',
								),
								array(
									'field'    => 'field_575b89a55beeb',
									'operator' => '==',
									'value'    => '1',
								),
							),
						),
						'wrapper'           => array(
							'width' => 50,
							'class' => 'acf_field_name-banner_image_opacity_custom_opacity acf_field_name-banner_image_opacity_custom_opacity acf_field_name-banner_image_opacity_custom_opacity acf_field_name-banner_image_opacity_custom_opacity acf_field_name-banner_image_opacity_custom_opacity acf_field_name-banner_image_opacity_custom_opacity acf_field_name-banner_image_opacity_custom_opacity acf_field_name-banner_image_opacity_custom_opacity acf_field_name-banner_image_opacity_custom_opacity acf_field_name-banner_image_opacity_custom_opacity acf_field_name-banner_image_opacity_custom_opacity',
							'id'    => '',
						),
						'default_value'     => '.8',
						'min'               => 0,
						'max'               => 1,
						'step'              => '0.01',
						'placeholder'       => '',
						'prepend'           => '',
						'append'            => '',
						'readonly'          => 0,
						'disabled'          => 0,
					),
					array(
						'key'               => 'field_575ac52c6e6cd',
						'label'             => esc_html__( 'Banner (Color)', 'cardealer-helper' ),
						'name'              => 'banner_image_color',
						'type'              => 'color_picker',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_575aac9c8d83b',
									'operator' => '==',
									'value'    => 'color',
								),
								array(
									'field'    => 'field_575b89a55beeb',
									'operator' => '==',
									'value'    => '1',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-banner_image_color acf_field_name-banner_image_color acf_field_name-banner_image_color acf_field_name-banner_image_color acf_field_name-banner_image_color acf_field_name-banner_image_color acf_field_name-banner_image_color acf_field_name-banner_image_color acf_field_name-banner_image_color acf_field_name-banner_image_color acf_field_name-banner_image_color',
							'id'    => '',
						),
						'default_value'     => '#191919',
					),
					array(
						'key'               => 'field_5947c8cfc7b02',
						'label'             => esc_html__( 'Banner Video Type', 'cardealer-helper' ),
						'name'              => 'banner_video_type_bg_custom',
						'type'              => 'select',
						'instructions'      => '',
						'required'          => 1,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_575b89a55beeb',
									'operator' => '==',
									'value'    => '1',
								),
								array(
									'field'    => 'field_575aac9c8d83b',
									'operator' => '==',
									'value'    => 'video',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-banner_image_bg acf_field_name-banner_image_bg acf_field_name-banner_image_bg acf_field_name-banner_image_bg_custom acf_field_name-banner_image_bg_custom acf_field_name-banner_image_bg_custom acf_field_name-banner_image_bg_custom acf_field_name-banner_image_bg_custom acf_field_name-banner_image_bg_custom acf_field_name-banner_video_bg_custom_copy acf_field_name-banner_video_bg_custom_copy acf_field_name-banner_video_bg_custom_copy acf_field_name-banner_video_bg_custom_copy acf_field_name-banner_video_bg_custom_copy acf_field_name-banner_video_type_bg_custom acf_field_name-banner_video_type_bg_custom acf_field_name-banner_video_type_bg_custom acf_field_name-banner_video_type_bg_custom acf_field_name-banner_video_type_bg_custom',
							'id'    => '',
						),
						'choices'           => array(
							'youtube' => 'Youtube',
							'vimeo'   => 'Vimeo',
						),
						'default_value'     => array(),
						'allow_null'        => 0,
						'multiple'          => 0,
						'ui'                => 0,
						'ajax'              => 0,
						'return_format'     => 'value',
						'placeholder'       => '',
					),
					array(
						'key'               => 'field_5947c5888af45',
						'label'             => esc_html__( 'Banner Video', 'cardealer-helper' ),
						'name'              => 'banner_video_bg_custom',
						'type'              => 'oembed',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_575b89a55beeb',
									'operator' => '==',
									'value'    => '1',
								),
								array(
									'field'    => 'field_575aac9c8d83b',
									'operator' => '==',
									'value'    => 'video',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-banner_image_bg acf_field_name-banner_image_bg acf_field_name-banner_image_bg acf_field_name-banner_image_bg_custom acf_field_name-banner_image_bg_custom acf_field_name-banner_image_bg_custom acf_field_name-banner_image_bg_custom acf_field_name-banner_image_bg_custom acf_field_name-banner_image_bg_custom acf_field_name-banner_video_bg_custom_copy acf_field_name-banner_video_bg_custom_copy acf_field_name-banner_video_bg_custom_copy acf_field_name-banner_video_bg_custom_copy acf_field_name-banner_video_bg_custom_copy acf_field_name-banner_video_bg_custom acf_field_name-banner_video_bg_custom acf_field_name-banner_video_bg_custom',
							'id'    => '',
						),
						'width'             => '',
						'height'            => '',
					),
					array(
						'key'               => 'field_5948cefba8176',
						'label'             => esc_html__( 'Video Background Opacity Color', 'cardealer-helper' ),
						'name'              => 'video_background_opacity_color',
						'type'              => 'radio',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_575aac9c8d83b',
									'operator' => '==',
									'value'    => 'video',
								),
								array(
									'field'    => 'field_575b89a55beeb',
									'operator' => '==',
									'value'    => '1',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name-background_opacity_color acf_field_name-background_opacity_color acf_field_name-background_opacity_color acf_field_name-background_opacity_color acf_field_name-background_opacity_color acf_field_name-background_opacity_color acf_field_name-background_opacity_color acf_field_name-background_opacity_color acf_field_name-background_opacity_color acf_field_name-video_background_opacity_color acf_field_name-video_background_opacity_color acf_field_name-video_background_opacity_color acf_field_name-video_background_opacity_color',
							'id'    => '',
						),
						'choices'           => array(
							'none'   => 'None',
							'black'  => 'Black',
							'white'  => 'White',
							'custom' => 'Custom',
						),
						'allow_null'        => 0,
						'other_choice'      => 0,
						'save_other_choice' => 0,
						'default_value'     => '',
						'layout'            => 'horizontal',
						'return_format'     => 'value',
					),
					array(
						'key'               => 'field_5948cf63fdb8d',
						'label'             => esc_html__( 'Video Background Opacity Color (Custom Color)', 'cardealer-helper' ),
						'name'              => 'banner_video_opacity_custom_color',
						'type'              => 'color_picker',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_575aac9c8d83b',
									'operator' => '==',
									'value'    => 'video',
								),
								array(
									'field'    => 'field_5948cefba8176',
									'operator' => '==',
									'value'    => 'custom',
								),
								array(
									'field'    => 'field_575b89a55beeb',
									'operator' => '==',
									'value'    => '1',
								),
							),
						),
						'wrapper'           => array(
							'width' => '50',
							'class' => 'acf_field_name-banner_image_opacity_custom_color acf_field_name-banner_image_opacity_custom_color acf_field_name-banner_image_opacity_custom_color acf_field_name-banner_image_opacity_custom_color acf_field_name-banner_image_opacity_custom_color acf_field_name-banner_image_opacity_custom_color acf_field_name-banner_image_opacity_custom_color acf_field_name-banner_image_opacity_custom_color acf_field_name-banner_image_opacity_custom_color acf_field_name-banner_video_opacity_custom_color_copy acf_field_name-banner_video_opacity_custom_color acf_field_name-banner_video_opacity_custom_color acf_field_name-banner_video_opacity_custom_color',
							'id'    => '',
						),
						'default_value'     => '#000000',
					),
					array(
						'key'               => 'field_5948d0baf3295',
						'label'             => esc_html__( 'Video Background Opacity Color (Custom Opacity)', 'cardealer-helper' ),
						'name'              => 'banner_video_opacity_custom_opacity',
						'type'              => 'number',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_575aac9c8d83b',
									'operator' => '==',
									'value'    => 'video',
								),
								array(
									'field'    => 'field_5948cefba8176',
									'operator' => '==',
									'value'    => 'custom',
								),
								array(
									'field'    => 'field_575b89a55beeb',
									'operator' => '==',
									'value'    => '1',
								),
							),
						),
						'wrapper'           => array(
							'width' => '50',
							'class' => 'acf_field_name-banner_image_opacity_custom_opacity acf_field_name-banner_image_opacity_custom_opacity acf_field_name-banner_image_opacity_custom_opacity acf_field_name-banner_image_opacity_custom_opacity acf_field_name-banner_image_opacity_custom_opacity acf_field_name-banner_image_opacity_custom_opacity acf_field_name-banner_image_opacity_custom_opacity acf_field_name-banner_image_opacity_custom_opacity acf_field_name-banner_image_opacity_custom_opacity acf_field_name-banner_video_opacity_custom_opacity_copy acf_field_name-banner_video_opacity_custom_opacity acf_field_name-banner_video_opacity_custom_opacity acf_field_name-banner_video_opacity_custom_opacity',
							'id'    => '',
						),
						'default_value'     => '.8',
						'placeholder'       => '',
						'prepend'           => '',
						'append'            => '',
						'min'               => 0,
						'max'               => 1,
						'step'              => '0.01',
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'page',
						),
					),
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'post',
						),
					),
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'product',
						),
					),
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'cars',
						),
					),
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'forum',
						),
					),
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'topic',
						),
					),
				),
				'menu_order'            => 1,
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
