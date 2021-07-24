<?php
add_filter( 'cardealer_acf_car_data', 'cdhl_acf_car_data_add_additional_attributes');
function cdhl_acf_car_data_add_additional_attributes( $args ) {
	$additional_taxes = get_taxonomies( array( 'is_additional_attribute' => true ), 'objects' );

	if ( is_array( $additional_taxes ) && count( $additional_taxes ) > 0 ) {

		$args['fields'][] = array (
			'key'              => 'field_16221e6c69226f8e162b3706', //Must pass unique value here. Do not use blank space and special character just use int or alphabets. Used "http://onlineuuidgenerator.com/time/5/0/0/" to generate unique key
			'label'            => esc_html__('Additional Attributes','cardealer-helper'),
			'name'             => '',
			'type'             => 'tab',
			'instructions'     => '',
			'required'         => 0,
			'conditional_logic'=> 0,
			'wrapper'          => array (
				'width'=> '',
				'class'=> '',
				'id'   => '',
			),
			'placement' => 'left',
			'endpoint'  => 0,
		);

		$args['fields'][] = array(
			"key"          => "field_b211604f6e048",
			"label"        => esc_html__( 'Additional Attributes','cardealer-helper' ),
			"name"         => "",
			"type"         => "message",
			"instructions" => "",
			"required"     => 0,
			"conditional_logic" => 0,
			"wrapper"      => array(
				"width"    => "",
				"class"    => "",
				"id"       => ""
			),
			"message"      => esc_html__( 'These fields are merged with core attributes in the "Attributes" tab.', 'cardealer-helper' ),
			"new_lines"    => "wpautop",
			"esc_html"     => 0
		);
	}

    return $args;
}
