<?php
/**
 * Shortcodes
 *
 * @author   PotenzaGlobalSolutions
 * @category Class
 * @package  CDFS/Classes
 * @version  1.0.0
 */

/**
 * 1. My Account : [cardealer_my_account]
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CDFS Shortcodes class.
 */
class CDFS_Shortcodes {

	/**
	 * Init shortcodes.
	 */
	public static function init() {
		$shortcodes = array(
			'cardealer_my_account' => __CLASS__ . '::user_account',
		);

		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode( apply_filters( "{$shortcode}_shortcode_tag", $shortcode ), $function );
		}

	}

	/**
	 * Shortcode Wrapper.
	 *
	 * @param string[] $function Callback function.
	 * @param array    $atts     Attributes. Default to empty array.
	 * @param array    $wrapper  Customer wrapper data.
	 *
	 * @return string
	 */
	public static function shortcode_wrapper(
		$function,
		$atts = array(),
		$wrapper = array(
			'class'  => 'cdfs',
			'before' => null,
			'after'  => null,
		)
	) {
		ob_start();

		// @codingStandardsIgnoreStart
		echo empty( $wrapper['before'] ) ? '<div class="' . esc_attr( $wrapper['class'] ) . '">' : $wrapper['before'];
		call_user_func( $function, $atts );
		echo empty( $wrapper['after'] ) ? '</div>' : $wrapper['after'];
		// @codingStandardsIgnoreEnd

		return ob_get_clean();
	}

	/**
	 * My account page shortcode.
	 *
	 * @param array $atts Attributes.
	 * @return string
	 */
	public static function user_account( $atts ) {
		return self::shortcode_wrapper( array( 'CDFS_Shortcode_My_Account', 'output' ), $atts );
	}
}
