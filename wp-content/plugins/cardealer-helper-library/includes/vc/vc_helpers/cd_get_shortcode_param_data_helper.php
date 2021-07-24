<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Cardealer Visual Composer Param Data
 *
 * @package car-dealer-helper/functions
 */

/**
 * Get shortcode param data.
 *
 * @param string $shortcode .
 */
function cdhl_get_shortcode_param_data( $shortcode = '' ) {
	$options = array();
	if ( empty( $shortcode ) ) {
		return $options;
	}
	$images_dir = CDHL_VC_DIR . '/vc_images/options/' . $shortcode . '/';
	$images_url = CDHL_VC_URL . '/vc_images/options/' . $shortcode . '/';

	if ( is_dir( $images_dir ) ) {
		$images = cdhl_pgscore_get_file_list( 'png', $images_dir );
		natcasesort( $images );
		if ( ! empty( $images ) ) {
			foreach ( $images as $image ) {
				$image_data                         = pathinfo( $image );
				$options[ $image_data['filename'] ] = $images_url . '/' . $image_data['basename'];
			}
		}
	}
	return $options;
}
