<?php
/**
 * Cardealer Visual Composer iconpicker.
 *
 * @package car-dealer-helper/functions
 */

/**
 * Icon Picker.
 *
 * @param string $dependency .
 */
function cdhl_iconpicker( $dependency = null ) {
	$icon_library = array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Icon library', 'cardealer-helper' ),
		'value'       => array(
			esc_html__( 'Font Awesome', 'cardealer-helper' ) => 'fontawesome',
			esc_html__( 'Open Iconic', 'cardealer-helper' ) => 'openiconic',
			esc_html__( 'Typicons', 'cardealer-helper' )   => 'typicons',
			esc_html__( 'Entypo', 'cardealer-helper' )     => 'entypo',
			esc_html__( 'Linecons', 'cardealer-helper' )   => 'linecons',
			esc_html__( 'Mono Social', 'cardealer-helper' ) => 'monosocial',
			esc_html__( 'Flat Icons', 'cardealer-helper' ) => 'flaticon',
		),
		'param_name'  => 'icon_type',
		'description' => esc_html__( 'Select icon library.', 'cardealer-helper' ),
	);
	if ( ! empty( $dependency ) ) {
		$icon_library['dependency'] = $dependency;
	}

	$icon_picker   = array();
	$icon_picker[] = $icon_library;
	$icon_picker[] = array(
		'type'        => 'iconpicker',
		'heading'     => esc_html__( 'Icon', 'cardealer-helper' ),
		'param_name'  => 'icon_fontawesome',
		'value'       => 'fas fa-info-circle',
		'settings'    => array(
			'emptyIcon'    => false,
			'iconsPerPage' => 4000,
		),
		'dependency'  => array(
			'element' => 'icon_type',
			'value'   => 'fontawesome',
		),
		'description' => esc_html__( 'Select icon from library.', 'cardealer-helper' ),
	);
	$icon_picker[] = array(
		'type'        => 'iconpicker',
		'heading'     => esc_html__( 'Icon', 'cardealer-helper' ),
		'param_name'  => 'icon_openiconic',
		'value'       => 'vc-oi vc-oi-dial',
		'settings'    => array(
			'emptyIcon'    => false,
			'type'         => 'openiconic',
			'iconsPerPage' => 4000,
		),
		'dependency'  => array(
			'element' => 'icon_type',
			'value'   => 'openiconic',
		),
		'description' => esc_html__( 'Select icon from library.', 'cardealer-helper' ),
	);
	$icon_picker[] = array(
		'type'        => 'iconpicker',
		'heading'     => esc_html__( 'Icon', 'cardealer-helper' ),
		'param_name'  => 'icon_typicons',
		'value'       => 'typcn typcn-adjust-brightness',
		'settings'    => array(
			'emptyIcon'    => false,
			'type'         => 'typicons',
			'iconsPerPage' => 4000,
		),
		'dependency'  => array(
			'element' => 'icon_type',
			'value'   => 'typicons',
		),
		'description' => esc_html__( 'Select icon from library.', 'cardealer-helper' ),
	);
	$icon_picker[] = array(
		'type'       => 'iconpicker',
		'heading'    => esc_html__( 'Icon', 'cardealer-helper' ),
		'param_name' => 'icon_entypo',
		'value'      => 'entypo-icon entypo-icon-note',
		'settings'   => array(
			'emptyIcon'    => false,
			'type'         => 'entypo',
			'iconsPerPage' => 4000,
		),
		'dependency' => array(
			'element' => 'icon_type',
			'value'   => 'entypo',
		),
	);
	$icon_picker[] = array(
		'type'        => 'iconpicker',
		'heading'     => esc_html__( 'Icon', 'cardealer-helper' ),
		'param_name'  => 'icon_linecons',
		'value'       => 'vc_li vc_li-heart',
		'settings'    => array(
			'emptyIcon'    => false,
			'type'         => 'linecons',
			'iconsPerPage' => 4000,
		),
		'dependency'  => array(
			'element' => 'icon_type',
			'value'   => 'linecons',
		),
		'description' => esc_html__( 'Select icon from library.', 'cardealer-helper' ),
	);
	$icon_picker[] = array(
		'type'        => 'iconpicker',
		'heading'     => esc_html__( 'Icon', 'cardealer-helper' ),
		'param_name'  => 'icon_monosocial',
		'value'       => 'vc-mono vc-mono-fivehundredpx',
		'settings'    => array(
			'emptyIcon'    => false,
			'type'         => 'monosocial',
			'iconsPerPage' => 4000,
		),
		'dependency'  => array(
			'element' => 'icon_type',
			'value'   => 'monosocial',
		),
		'description' => esc_html__( 'Select icon from library.', 'cardealer-helper' ),
	);
	$icon_picker[] = array(
		'type'        => 'iconpicker',
		'heading'     => esc_html__( 'Icon', 'cardealer-helper' ),
		'param_name'  => 'icon_flaticon',
		'value'       => 'glyph-icon flaticon-air-conditioning',
		'settings'    => array(
			'emptyIcon'    => false,
			'type'         => 'flaticon',
			'iconsPerPage' => 4000,
		),
		'dependency'  => array(
			'element' => 'icon_type',
			'value'   => 'flaticon',
		),
		'description' => esc_html__( 'Select icon from library.', 'cardealer-helper' ),
	);
	return $icon_picker;
}
