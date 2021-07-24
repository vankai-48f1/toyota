<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

$cars_grid = '';

if ( isset( $_REQUEST['cars_grid'] ) && in_array( $_REQUEST['cars_grid'], array( 'yes', 'no' ), true ) ) {
	$cars_grid = sanitize_text_field( wp_unslash( $_REQUEST['cars_grid'] ) );
} else {
	$cars_grid = cardealer_get_cars_catlog_style();
}

if ( 'yes' === $cars_grid ) {
	// Grid view layout.
	get_template_part( 'template-parts/cars/layout/grid-view' );
} else {
	get_template_part( 'template-parts/cars/layout/list-view' );
}
