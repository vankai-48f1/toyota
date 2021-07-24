<?php
defined( 'ABSPATH' ) || exit;

add_filter( 'wpcf7_validate', 'skip_validation_for_hidden_fields', 2, 2 );

/**
 * Remove validation requirements for fields that are hidden.
 */
function skip_validation_for_hidden_fields( $result, $tags, $args = [] ) {

	$contact_form = WPCF7_ContactForm::get_current();
	$submission   = WPCF7_Submission::get_instance();
	$posted_data  = $submission->get_posted_data();

	$invalid_fields     = $result->get_invalid_fields();
	$return_result      = new WPCF7_Validation();
	$skip_fields        = ( isset( $posted_data['cdhl_skip_fields'] ) && ! empty( $posted_data['cdhl_skip_fields'] ) ) ? json_decode( stripslashes( $posted_data['cdhl_skip_fields'] ) ) : array();

	if ( isset( $posted_data['cardealer_lead_form'] ) && ! empty( $posted_data['cardealer_lead_form'] ) && ! empty( $skip_fields ) ) {
		foreach ( $invalid_fields as $invalid_field_key => $invalid_field_data ) {
			if ( ! in_array( $invalid_field_key, $skip_fields ) ) {
				// the invalid field is not a hidden field, so we'll add it to the final validation result
				foreach ( $tags as $tag ) {
					if ( $tag->name === $invalid_field_key ) {
						$return_result->invalidate( $tag, $invalid_field_data['reason'] );
					}
				}
			}
		}
		$result = $return_result;
	}

	return $result;
}
