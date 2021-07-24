<?php
/**
 * Car pdf generator
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */
if ( ! function_exists( 'cdhl_get_pdf_acf_info_fields' ) ) {
	/**
	 * PDF  acf info fields
	 */
	function cdhl_get_pdf_acf_info_fields() {
		$info_fields = 'Vehicle Image : {{image}} <br>
		Vehicle Year : {{year}} <br>
		Vehicle Make : {{make}}<br>
		Vehicle Model : {{model}} <br>
		Regular Price : {{regular_price}} <br>
		Currency Symbol : {{currency_symbol}} <br>
		Sale Price : {{sale_price}} <br>
		Body Style : {{body_style}} <br>
		Condition : {{condition}} <br>
		Mileage : {{mileage}} <br>
		Transmission : {{transmission}} <br>
		Drivetrain : {{drivetrain}} <br>
		Engine : {{engine}}<br>
		Fuel Type : {{fuel_type}} <br>
		Fuel Economy : {{fuel_economy}}<br>
		Trim : {{trim}} <br>
		Exterior Color : {{exterior_color}}<br>
		Interior Color : {{interior_color}} <br>
		Stock : {{stock_number}}<br>
		VIN : {{vin_number}} <br>
		Features And Options : {{features_options}}<br>
		Highway MPG : {{high_waympg}} <br>
		City MPG : {{city_mpg}}<br>
		Vehicle Overview : {{vehicle_overview}}<br>
		Tax Label : {{tax_label}}<br>
		Vehicle Status : {{vehicle_status}}<br>
		Vehicle Review Stamps : {{vehicle_review_stamps}}<br>';

		$info = '';
		$additional_taxes = get_taxonomies( array(
			'is_additional_attribute' => true,
			'object_type' => array(
				'cars',
			),
		), 'objects' );

		foreach ( $additional_taxes as $tax_name => $tax_obj ) {
			$info .= $tax_obj->label . ' : {{' . $tax_obj->name . '}}<br>';
		}

		return $info_fields . $info;
	}
}

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		/**
		 * Filters the arguments of the vehicle brochure generator field group.
		 *
		 * @since 1.0
		 * @param array    $args    Arguments of the vehicle brochure generator field group.
		 * @visible        true
		 */
		apply_filters(
			'cardealer_acf_pdf_generator',
			array(
				'key'                   => 'group_589ac22982773',
				'title'                 => esc_html__( 'Vehicle Brochure Generator', 'cardealer-helper' ),
				'fields'                => array(
					array(
						'key'               => 'field_589ac266afdf9',
						'label'             => esc_html__( 'HTML Templates', 'cardealer-helper' ),
						'name'              => 'html_templates',
						'type'              => 'repeater',
						'instructions'      => sprintf(
							wp_kses(
								/* translators: %s: string */
								__( 'Click here for <a href="#" id="reset-pdf-sample" data-nonce="%2$s">reset to default <i class="spinner"></i></a> all templates.<br/><br/><b>Use <a href="#" class="cd_dialog" data-id="pdf-fields">this</a> fields association in order to make or update vehicle brochure template.</b><div id="pdf-fields" class="variable-content" title="Vehicle Fields Association"><p>%1$s</p></div>', 'cardealer-helper' ),
								array(
									'a'   => array(
										'href'    => array(),
										'class'   => array(),
										'data-id' => array(),
										'id' => array(),
										'data-nonce' => array(),
									),
									'div' => array(
										'title' => array(),
										'id'    => array(),
										'class' => array(),
									),
									'p'   => array(),
									'br'  => array(),
									'b'   => array(),
									'i'   => array(
										'class' => array(),
									),
								)
							),
							cdhl_get_pdf_acf_info_fields(),
							wp_create_nonce('pdf_reset_nonce')
						),
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name- acf_field_name-html_templates',
							'id'    => '',
						),
						'min'               => 0,
						'max'               => 0,
						'layout'            => 'block',
						'button_label'      => '',
						'collapsed'         => '',
						'sub_fields'        => array(
							array(
								'key'               => 'field_589ac54dafdfa',
								'label'             => esc_html__( 'Templates Title', 'cardealer-helper' ),
								'name'              => 'templates_title',
								'type'              => 'text',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => 'acf_field_name-templates_title',
									'id'    => '',
								),
								'default_value'     => '',
								'maxlength'         => '',
								'placeholder'       => '',
								'prepend'           => '',
								'append'            => '',
							),
							array(
								'key'               => 'field_589ac571afdfb',
								'label'             => esc_html__( 'Template Content', 'cardealer-helper' ),
								'name'              => 'template_content',
								'type'              => 'wysiwyg',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => 'acf_field_name-template_content',
									'id'    => '',
								),
								'tabs'              => 'all',
								'toolbar'           => 'full',
								'media_upload'      => 1,
								'default_value'     => '',
								'delay'             => 0,
							),
							array(
								'key'               => 'field_5c2b0cb780efb',
								'label'             => esc_html__( 'Set Custom Margin ?', 'cardealer-helper' ),
								'name'              => 'custom_margin_set',
								'type'              => 'checkbox',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => 'acf_field_name-custom_margin_set',
									'id'    => '',
								),
								'choices'           => array(
									'yes' => 'Yes',
								),
								'allow_custom'      => 0,
								'default_value'     => array(),
								'layout'            => 'vertical',
								'toggle'            => 0,
								'return_format'     => 'value',
								'save_custom'       => 0,
							),
							array(
								'key'               => 'field_5c29f4760055c',
								'label'             => esc_html__( 'Template Margin Top', 'cardealer-helper' ),
								'name'              => 'templates_margin_top',
								'type'              => 'range',
								'instructions'      => esc_html__( 'If Margin Top Is Not Set Then Default Value Set', 'cardealer-helper' ),
								'required'          => 0,
								'conditional_logic' => array(
									array(
										array(
											'field'    => 'field_5c2b0cb780efb',
											'operator' => '==',
											'value'    => 'yes',
										),
									),
								),
								'wrapper'           => array(
									'width' => '',
									'class' => 'acf_field_name-templates_margin_top',
									'id'    => '',
								),
								'default_value'     => 0,
								'min'               => '',
								'max'               => '',
								'step'              => '',
								'prepend'           => '',
								'append'            => '',
							),
							array(
								'key'               => 'field_5c29f59195d8c',
								'label'             => esc_html__( 'Template Margin Bottom', 'cardealer-helper' ),
								'name'              => 'templates_margin_bottom',
								'type'              => 'range',
								'instructions'      => esc_html__( 'If Margin Bottom Is Not Set Then Default Value Set', 'cardealer-helper' ),
								'required'          => 0,
								'conditional_logic' => array(
									array(
										array(
											'field'    => 'field_5c2b0cb780efb',
											'operator' => '==',
											'value'    => 'yes',
										),
									),
								),
								'wrapper'           => array(
									'width' => '',
									'class' => 'acf_field_name-templates_margin_bottom',
									'id'    => '',
								),
								'default_value'     => 0,
								'min'               => '',
								'max'               => '',
								'step'              => '',
								'prepend'           => '',
								'append'            => '',
							),
							array(
								'key'               => 'field_5c29f59b95d8d',
								'label'             => esc_html__( 'Template Margin Left', 'cardealer-helper' ),
								'name'              => 'templates_margin_left',
								'type'              => 'range',
								'instructions'      => esc_html__( 'If Margin Left Is Not Set Then Default Value Set', 'cardealer-helper' ),
								'required'          => 0,
								'conditional_logic' => array(
									array(
										array(
											'field'    => 'field_5c2b0cb780efb',
											'operator' => '==',
											'value'    => 'yes',
										),
									),
								),
								'wrapper'           => array(
									'width' => '',
									'class' => 'acf_field_name-templates_margin_left',
									'id'    => '',
								),
								'default_value'     => 0,
								'min'               => '',
								'max'               => '15',
								'step'              => '',
								'prepend'           => '',
								'append'            => '',
							),
							array(
								'key'               => 'field_5c29f59e95d8e',
								'label'             => esc_html__( 'Template Margin Right', 'cardealer-helper' ),
								'name'              => 'templates_margin_right',
								'type'              => 'range',
								'instructions'      => esc_html__( 'If Margin Right Is Not Set Then Default Value Set', 'cardealer-helper' ),
								'required'          => 0,
								'conditional_logic' => array(
									array(
										array(
											'field'    => 'field_5c2b0cb780efb',
											'operator' => '==',
											'value'    => 'yes',
										),
									),
								),
								'wrapper'           => array(
									'width' => '',
									'class' => 'acf_field_name-templates_margin_right',
									'id'    => '',
								),
								'default_value'     => 0,
								'min'               => '',
								'max'               => '15',
								'step'              => '',
								'prepend'           => '',
								'append'            => '',
							),

						),
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'pdf_generator',
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'field',
				'hide_on_screen'        => '',
				'active'                => 1,
				'description'           => '',
				'menu_item_level'       => 'all',
			)
		)
	);

endif;
