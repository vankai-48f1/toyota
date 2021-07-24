<?php
/**
 * CF7 integrations.
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper/third-party-support
 * @version 1.9.0
 */

add_filter( 'wpcf7_collect_mail_tags', 'cdhl_cf7_add_custom_mail_tags', 10, 3 );
add_filter( 'wpcf7_mail_components', 'cdhl_cf7_parse_shortcode', 10, 3 );
add_filter( 'wpcf7_form_hidden_fields', 'cdhl_wpcf7_form_add_hidden_fields' );
add_shortcode( 'vehicle_details', 'cdhl_vehicle_details_func' );
add_filter( 'wpcf7_form_tag_data_option', 'cdhl_wpcf7_form_tag_data_options', 10, 3 );

/**
 * Add custom mail-tags CF7 in the Mail tab.
 *
 * @since 1.9.0
 * @param array             $mailtags           Array of mail tags.
 * @param array             $args               Array of arguments.
 * @param WPCF7_ContactForm $wpcf7_contactform  Instance of WPCF7_ContactForm.
 * return array
 */
function cdhl_cf7_add_custom_mail_tags( $mailtags, $args, $wpcf7_contactform ) {

	$mailtags[] = 'vehicle_details';

	return $mailtags;
}

/**
 * Add filter for use shortcode in email body CF7
 *
 * @param array      $components    Array of components.
 * @param array      $current_form  Current form.
 * @param WPCF7_Mail $wpcf7_mail    Instance of WPCF7_Mail.
 * @return array
 */
function cdhl_cf7_parse_shortcode( $components, $current_form, $wpcf7_mail ) {
	$components['body'] = do_shortcode( $components['body'] );
	return $components;
}

/**
 * Add CF7 hidden field.
 *
 * @param array $fields  List of hidden fields.
 * @return array
 */
function cdhl_wpcf7_form_add_hidden_fields( $fields ) {

	if ( function_exists( 'icl_object_id' ) ) {
		// Get current language.
		$current_language = apply_filters( 'wpml_current_language', null );

		// Set 'cf7_wpml_lang' hidden field.
		$fields['cf7_wpml_lang'] = $current_language; // Hidden current language field.
	}

	$fields['cdhl_skip_fields'] = ''; // Hidden current language field.

	return $fields;
}

/**
 * Shortcode for get vehicle details in html for CF7 form
 *
 * @param array $atts  Arra of attributes.
 * @return string
 */
function cdhl_vehicle_details_func( $atts ) {
	$atts = shortcode_atts(
		array(
			'vehicle_id' => '',
		),
		$atts,
		'vehicle_details'
	);

	$vehicle_id      = '';
	$vehicle_details = '';
	$use_html        = false;

	if ( isset( $atts['vehicle_id'] ) && ! empty( $atts['vehicle_id'] ) ) {
		$vehicle_id = $atts['vehicle_id'];
	}

	$submission = WPCF7_Submission::get_instance();
	if ( is_a( $submission, 'WPCF7_Submission' ) ) {
		$posted_data = $submission->get_posted_data();
		$vehicle_id  = $submission->get_meta( 'container_post_id' );

		$wpcf7_mail   = WPCF7_Mail::get_current();
		$current_form = wpcf7_get_current_contact_form();
		$template     = $wpcf7_mail->name();
		$mail_props   = $current_form->prop( $template );
		if ( $mail_props['use_html'] ) {
			$use_html = true;
		}
	}

	if ( ( wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) && isset( $posted_data['cf7_wpml_lang'] ) && '' !== $posted_data['cf7_wpml_lang'] ) {
		$wpml_lang_sanitized = sanitize_text_field( wp_unslash( $posted_data['cf7_wpml_lang'] ) );
		do_action( 'wpml_switch_language', $wpml_lang_sanitized );
	}

	if ( isset( $vehicle_id ) && ! empty( $vehicle_id ) ) {
		$post = get_post( $vehicle_id );
		if ( $post instanceof WP_Post && 'cars' === $post->post_type ) {
			$vehicle_details = ( $use_html ) ? cdhl_get_html_mail_body( $vehicle_id ) : cdhl_get_text_mail_body( $vehicle_id );
		}
	}

	return $vehicle_details;
}

function cdhl_wpcf7_form_tag_data_options( $data, $options, $args ) {
	$args = wp_parse_args( $args, array() );

	$contact_form   = wpcf7_get_current_contact_form();
	$args['locale'] = $contact_form->locale();

	foreach ( (array) $options as $option ) {
		if ( $list = cdhl_get_wpcf7_data_options( $option ) ) {
			$data = array_merge( (array) $data, $list );
		}
	}

	return $data;
}

function cdhl_get_wpcf7_data_options( $option ) {
	$options = array();
	$return  = false;

	$options = apply_filters( 'cdhl_wpcf7_data_options', $options );

	if ( isset( $options[ $option ] ) && ! empty( $option ) ) {
		$return = $options[ $option ];
	}

	return $return;
}
