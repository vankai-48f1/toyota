<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Cardealer VC link attributes.
 *
 * @package car-dealer-helper/functions
 */

/**
 * VC link attribute.
 *
 * @param string $url_vars .
 */
function cdhl_vc_link_attr( $url_vars ) {
	$link_attr = '';
	if ( ! empty( $url_vars ) && is_array( $url_vars ) ) {
		foreach ( $url_vars as $url_var_k => $url_var_v ) {
			if ( ! empty( $url_var_v ) ) {
				if ( ! empty( $link_attr ) ) {
					$link_attr .= ' ';
				}
				if ( 'url' === (string) $url_var_k ) {
					$link_attr .= 'href="' . esc_url( $url_var_v ) . '"';
				} else {
					$link_attr .= $url_var_k . '="' . $url_var_v . '"';
				}
			}
		}
	}
	return $link_attr;
}
