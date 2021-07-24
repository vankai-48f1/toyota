<?php
/**
 * CDFS WooCommerce Functions
 *
 * @author   PotenzaGlobalSolutions
 * @category Class
 * @package  CDFS/Classes
 * @version  1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Add meta for add car and image limit
 */
add_action( 'woocommerce_product_options_general_product_data', 'cdfs_woocommerce_product_options_general_product_data', 999 );

if ( ! function_exists( 'cdfs_woocommerce_product_options_general_product_data' ) ) {
	/**
	 * Woocommerce product options general product data
	 */
	function cdfs_woocommerce_product_options_general_product_data() {
		global $post;
		$post_id = $post->ID;

		// Load product object.
		$product = wc_get_product( $post_id );

		if ( class_exists( 'Subscriptio' ) || class_exists( 'RP_SUB' ) ) {
			woocommerce_wp_text_input(
				array(
					'id'                => 'cdfs_car_quota',
					'placeholder'       => esc_html__( 'e.g. 100', 'cdfs-addon' ),
					'label'             => esc_html__( 'Cars Quota', 'cdfs-addon' ),
					'desc_tip'          => 'true',
					'type'              => 'number',
					'custom_attributes' => array(
						'step' => 'any',
						'min'  => '0',
					),
				)
			);
			woocommerce_wp_text_input(
				array(
					'id'                => 'cdfs_car_images_quota',
					'placeholder'       => esc_html__( 'e.g. 100', 'cdfs-addon' ),
					'label'             => esc_html__( 'Cars Images Quota', 'cdfs-addon' ),
					'desc_tip'          => 'true',
					'type'              => 'number',
					'custom_attributes' => array(
						'step' => 'any',
						'min'  => '0',
					),
				)
			);
		}
	}
}

add_action( 'woocommerce_process_product_meta', 'cdfs_woocommerce_process_product_meta' );

if ( ! function_exists( 'cdfs_woocommerce_process_product_meta' ) ) {
	/**
	 * Save meta.
	 *
	 * @param string $post_id .
	 */
	function cdfs_woocommerce_process_product_meta( $post_id ) {
		if ( isset( $_POST['cdfs_car_quota'] ) ) {
			$cdfs_car_quota = $_POST['cdfs_car_quota'];
			update_post_meta( $post_id, 'cdfs_car_quota', $cdfs_car_quota );
		}
		if ( isset( $_POST['cdfs_car_images_quota'] ) ) {
			$cdfs_car_images_quota = $_POST['cdfs_car_images_quota'];
			update_post_meta( $post_id, 'cdfs_car_images_quota', $cdfs_car_images_quota );
		}
	}
}

add_action( 'template_redirect', 'cdfs_template_redirect' );

if ( ! function_exists( 'cdfs_template_redirect' ) ) {
	/**
	 * Checkout page redirection.
	 */
	function cdfs_template_redirect() {
		if ( function_exists( 'is_checkout' ) && is_checkout() ) {
			if ( isset( $_GET['add-to-cart'] ) && ! empty( $_GET['add-to-cart'] ) ) {
				$pruduct_id = $_GET['add-to-cart'];
				WC()->cart->empty_cart();
				WC()->cart->add_to_cart( $pruduct_id, 1 );
				wp_redirect( wc_get_checkout_url() );
			}
		}
	}
}

add_action( 'woocommerce_thankyou', 'cdfs_custom_process_order', 10, 1 );

if ( ! function_exists( 'cdfs_custom_process_order' ) ) {
	/**
	 * Add/update meta after order place.
	 *
	 * @param string $order_id .
	 */
	function cdfs_custom_process_order( $order_id ) {
		if ( class_exists( 'Subscriptio' ) || class_exists( 'RP_SUB' ) ) {
			$order   = new WC_Order( $order_id );
			$data    = $order->get_data();
			$items   = $order->get_items();
			$user_id = $order->get_user_id();

			$cdfs_car_limit = 0;
			$cdfs_img_limit = 0;

			foreach ( $items as $item ) {
				$product_id     = $item->get_product_id();
				$post_limit     = get_post_meta( $product_id, 'cdfs_car_quota', true );
				$image_limit    = get_post_meta( $product_id, 'cdfs_car_images_quota', true );
				$is_subscriptio = ( get_post_meta( $product_id, '_subscriptio', true ) );

				if ( 'yes' === $is_subscriptio ) {
					$user_car_limt = get_user_meta( $user_id, 'cdfs_car_limt', true );
					$user_img_limt = get_user_meta( $user_id, 'cdfs_img_limt', true );

					$user_car_limt = ( $user_car_limt < 1 ) ? 0 : intval( $user_car_limt );
					$user_img_limt = ( $user_img_limt < 1 ) ? 0 : intval( $user_img_limt );

					$post_limit  = intval( get_post_meta( $product_id, 'cdfs_car_quota', true ) );
					$image_limit = intval( get_post_meta( $product_id, 'cdfs_car_images_quota', true ) );

					$cdfs_car_limit = intval( $user_car_limt ) + intval( $post_limit );
					$cdfs_img_limit = intval( $image_limit );

					update_user_meta( $user_id, 'cdfs_car_limt', $cdfs_car_limit );
					update_user_meta( $user_id, 'cdfs_img_limt', $cdfs_img_limit );
				}
			}
		}
		return $order_id;
	}
}

add_action( 'woocommerce_order_status_cancelled', 'cdfs_action_woocommerce_cancelled_order', 99, 1 );
add_action( 'woocommerce_order_status_refunded', 'cdfs_action_woocommerce_cancelled_order', 99, 1 );

if ( ! function_exists( 'cdfs_action_woocommerce_cancelled_order' ) ) {
	/**
	 * Remove/add meta on order cancelled or refunded.
	 *
	 * @param string $order_id .
	 */
	function cdfs_action_woocommerce_cancelled_order( $order_id ) {
		if ( class_exists( 'Subscriptio' ) || class_exists( 'RP_SUB' ) ) {
			$subscriptions = Subscriptio_Order_Handler::get_subscriptions_from_order_id( $order_id );
			foreach ( $subscriptions as $subscription ) {
				// Write transaction.
				$transaction = new Subscriptio_Transaction( null, 'order_cancellation' );
				$transaction->add_subscription_id( $subscription->id );
				$transaction->add_order_id( $order_id );
				$transaction->add_product_id( $subscription->product_id );
				$transaction->add_variation_id( $subscription->variation_id );

				try {
					// Cancel subscription.
					$subscription->cancel();

					// Update transaction.
					$transaction->update_result( 'success' );
					$transaction->update_note( __( 'Pending subscription canceled due to canceled order.', 'cdfs-addon' ), true );
				} catch ( Exception $e ) {
					$transaction->update_result( 'error' );
					$transaction->update_note( $e->getMessage() );
				}
			}

			$order          = new WC_Order( $order_id );
			$data           = $order->get_data();
			$items          = $order->get_items();
			$user_id        = $order->get_user_id();
			$cdfs_car_limit = $cdfs_img_limit = 0;

			foreach ( $items as $item ) {
				$product_id     = $item->get_product_id();
				$post_limit     = get_post_meta( $product_id, 'cdfs_car_quota', true );
				$image_limit    = get_post_meta( $product_id, 'cdfs_car_images_quota', true );
				$is_subscriptio = ( get_post_meta( $product_id, '_subscriptio', true ) );

				if ( 'yes' === $is_subscriptio ) {
					$user_car_limt = get_user_meta( $user_id, 'cdfs_car_limt', true );
					$user_img_limt = get_user_meta( $user_id, 'cdfs_img_limt', true );

					$user_car_limt = ( $user_car_limt < 1 ) ? 0 : intval( $user_car_limt );
					$user_img_limt = ( $user_img_limt < 1 ) ? 0 : intval( $user_img_limt );

					$post_limit  = intval( get_post_meta( $product_id, 'cdfs_car_quota', true ) );
					$image_limit = intval( get_post_meta( $product_id, 'cdfs_car_images_quota', true ) );

					$cdfs_car_limit = intval( $user_car_limt ) - intval( $post_limit );
					$cdfs_img_limit = intval( $image_limit );

					update_user_meta( $user_id, 'cdfs_car_limt', $cdfs_car_limit );
					update_user_meta( $user_id, 'cdfs_img_limt', $cdfs_img_limit );
				}
			}
		}
	}
}
