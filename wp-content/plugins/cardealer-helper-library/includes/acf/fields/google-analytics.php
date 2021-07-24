<?php
/**
 * Car Google analytics
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		array(
			'key'                   => 'group_595c9f1f2fbaa',
			'title'                 => 'Google Analytics',
			'fields'                => array(
				array(
					'key'               => 'field_595c9f871f8e8',
					'label'             => 'View Id',
					'name'              => 'view_id',
					'type'              => 'text',
					'instructions'      => esc_html__( 'View ID from your google analytics account', 'cardealer-helper' ),
					'required'          => 1,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => 'acf_field_name-view_id acf_field_name-view_id acf_field_name-view_id acf_field_name-view_id acf_field_name-view_id acf_field_name-view_id acf_field_name-view_id acf_field_name-view_id',
						'id'    => '',
					),
					'default_value'     => '',
					'placeholder'       => '',
					'prepend'           => '',
					'append'            => '',
					'maxlength'         => '',
				),
				array(
					'key'               => 'field_59ca29366c204',
					'label'             => 'Account ID',
					'name'              => 'account_id',
					'type'              => 'text',
					'instructions'      => esc_html__( 'Google Analytics account id.', 'cardealer-helper' ),
					'required'          => 1,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => 'acf_field_name-account_id',
						'id'    => '',
					),
					'default_value'     => '',
					'placeholder'       => '',
					'prepend'           => '',
					'append'            => '',
					'maxlength'         => '',
				),
				array(
					'key'               => 'field_59ca29d66c205',
					'label'             => 'Tracking ID',
					'name'              => 'tracking_id',
					'type'              => 'text',
					'instructions'      => esc_html__( 'Tracking Id from "Property Settings" page in property tab of your google analytics account.', 'cardealer-helper' ),
					'required'          => 1,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => 'acf_field_name-tracking_id acf_field_name-tracking_id',
						'id'    => '',
					),
					'default_value'     => '',
					'placeholder'       => '',
					'prepend'           => '',
					'append'            => '',
					'maxlength'         => '',
				),
				array(
					'key'               => 'field_595ca30b1f8e9',
					'label'             => 'Tracking Code',
					'name'              => 'tracking_code',
					'type'              => 'textarea',
					'instructions'      => esc_html__( 'Google Analytics Tracking code', 'cardealer-helper' ),
					'required'          => 1,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => 'acf_field_name-tracking_code acf_field_name-tracking_code acf_field_name-tracking_code acf_field_name-tracking_code acf_field_name-tracking_code acf_field_name-tracking_code acf_field_name-tracking_code acf_field_name-tracking_code acf_field_name-tracking_code',
						'id'    => '',
					),
					'default_value'     => '',
					'placeholder'       => '',
					'maxlength'         => '',
					'rows'              => '',
					'new_lines'         => '',
				),
				array(
					'key'               => 'field_595cb5a5dcd21',
					'label'             => 'Use custom time period',
					'name'              => 'custom_time',
					'type'              => 'true_false',
					'instructions'      => esc_html__( 'By default last month analytic data will be displayed. If you want to customize, then check following check box.', 'cardealer-helper' ),
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => 'acf_field_name-custiom_time acf_field_name-custom_time acf_field_name-custom_time acf_field_name-custom_time acf_field_name-custom_time acf_field_name-custom_time acf_field_name-custom_time acf_field_name-custom_time acf_field_name-custom_time acf_field_name-custom_time acf_field_name-custom_time',
						'id'    => '',
					),
					'message'           => '',
					'default_value'     => 0,
					'ui'                => 0,
					'ui_on_text'        => '',
					'ui_off_text'       => '',
				),
				array(
					'key'               => 'field_595cb7c7f170c',
					'label'             => 'Start Date',
					'name'              => 'start_date',
					'type'              => 'date_picker',
					'instructions'      => esc_html__( 'Start date from which google analytics data will be displayed.', 'cardealer-helper' ),
					'required'          => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_595cb5a5dcd21',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'wrapper'           => array(
						'width' => '',
						'class' => 'acf_field_name-start_date acf_field_name-start_date acf_field_name-start_date acf_field_name-start_date acf_field_name-start_date acf_field_name-start_date acf_field_name-start_date acf_field_name-start_date acf_field_name-start_date',
						'id'    => '',
					),
					'display_format'    => 'd/m/Y',
					'return_format'     => 'Y-m-d',
					'first_day'         => 1,
				),
				array(
					'key'               => 'field_595cb808a877c',
					'label'             => 'End Date',
					'name'              => 'end_date',
					'type'              => 'date_picker',
					'instructions'      => esc_html__( 'End date till which google analytics data will be displayed.', 'cardealer-helper' ),
					'required'          => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_595cb5a5dcd21',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'wrapper'           => array(
						'width' => '',
						'class' => 'acf_field_name-start_date acf_field_name-start_date acf_field_name-end_date acf_field_name-end_date acf_field_name-end_date acf_field_name-end_date acf_field_name-end_date acf_field_name-end_date acf_field_name-end_date acf_field_name-end_date',
						'id'    => '',
					),
					'display_format'    => 'd/m/Y',
					'return_format'     => 'Y-m-d',
					'first_day'         => 1,
				),
				array(
					'key'               => 'field_595f1b6d7559b',
					'label'             => 'Key File',
					'name'              => 'key_file',
					'type'              => 'file',
					'instructions'      => sprintf(
						wp_kses(
							/* translators: %s: links */
							__( '<strong>Key file</strong> generated from <strong><a href="%1$s" target="_blank">service account</a></strong> with <strong>.json</strong> extension. Make sure you upload file in <strong>JSON</strong> format only. For more information, please check <strong>Car Dealer <a href="%2$s" target="_blank">dashboard documentation</a></strong> google analytics section.', 'cardealer-helper' ),
							array(
								'strong' => array(),
								'a'      => array(
									'href'   => array(),
									'target' => array(),
								),
							)
						),
						esc_url( 'https://console.developers.google.com/permissions/serviceaccounts' ),
						esc_url( 'http://docs.potenzaglobalsolutions.com/docs/cardealer/#dashboard' )
					),
					'required'          => 1,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => 'acf_field_name-key_file acf_field_name-key_file acf_field_name-key_file acf_field_name-key_file acf_field_name-key_file acf_field_name-key_file acf_field_name-key_file acf_field_name-key_file acf_field_name-key_file',
						'id'    => '',
					),
					'return_format'     => 'array',
					'library'           => 'all',
					'min_size'          => '',
					'max_size'          => '',
					'mime_types'        => '',
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'google-analytics-settings',
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
		)
	);

endif;
