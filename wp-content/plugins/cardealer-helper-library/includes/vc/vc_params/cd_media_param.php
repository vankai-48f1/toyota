<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Media parameter for Visual Composer
 *
 * @package car-dealer-helper/functions
 */

if ( function_exists( 'vc_add_shortcode_param' ) ) {
	vc_add_shortcode_param( 'cd_media_upload', 'cdhl_media_upload_field', CDHL_VC_URL . '/assets/js/cd_media_upload.js' );
}

/**
 * Parsing settings field.
 *
 * @param array $settings Settings array.
 * @param array $value    Values array.
 *
 * @return string
 */
function cdhl_media_upload_field( $settings, $value ) {
	$output            = '';
	$select_file_class = '';
	$remove_file_class = ' hidden';
	$attachment_url    = wp_get_attachment_url( $value );
	if ( $attachment_url ) {
		$select_file_class = ' hidden';
		$remove_file_class = '';
	}
	$output .= '<div class="file_picker_block">
                <div class="' . esc_attr( $settings['type'] ) . '_display">' .
				basename( $attachment_url ) .
				'</div>
                <input type="hidden" name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .
				esc_attr( $settings['param_name'] ) . ' ' .
				esc_attr( $settings['type'] ) . '_field" value="' . esc_attr( $value ) . '" />
                <button class="button file-picker-button' . $select_file_class . '">Select File</button>
                <button class="button file-remover-button' . $remove_file_class . '">Remove File</button>
              </div>
              ';
	return $output;
}
