<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * FAQ category parameter for Visual Composer
 *
 * @package car-dealer-helper/functions
 */

if ( function_exists( 'vc_add_shortcode_param' ) ) {
	vc_add_shortcode_param( 'faq_cats', 'cdhl_faq_cats_settings_field' );
}

/**
 * Parsing settings field.
 *
 * @param array $settings Settings array.
 * @param array $value    Values array.
 *
 * @return string
 */
function cdhl_faq_cats_settings_field( $settings, $value ) {
	$cats_output = '<div class="faq_categories">'
				. '<select name="' . $settings['param_name']
				. '" class="wpb_vc_param_value wpb-select dropdown '
				. $settings['param_name'] . ' ' . $settings['type'] . '_field">'
				. '<option value="">All Categories</option>';

	/* Get categories */
	$terms = get_terms(
		'faq-category',
		array(
			'orderby'    => 'name',
			'hide_empty' => true,
		)
	);

	foreach ( $terms as $term ) {
		$cats_output .= '<option value="' . $term->term_id . '"';
		if ( (string) $term->term_id === (string) $value ) {
			$cats_output .= 'selected="selected"';
		}
		$cats_output .= '>' . $term->name . '</option>';
	}

	$cats_output .= '</select>'
					. '</div>';

	return $cats_output;
}
