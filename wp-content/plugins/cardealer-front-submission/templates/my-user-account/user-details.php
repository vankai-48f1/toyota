<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/cardealer-front-submission/my-user-account/user-details.php.
 *
 * @author  PotenzaGlobalSolutions
 * @package CDFS
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
do_action( 'cdfs_before_user_profile' );

if ( ! is_user_logged_in() ) {
	return;
}
$user = wp_get_current_user();

$cdfs_user_avatar = get_user_meta( $user->ID, 'cdfs_user_avatar', true );

if ( empty( $cdfs_user_avatar ) ) {
	$cdfs_user_avatar = CDFS_URL . 'images/profile_placeholder.jpg';
}

$userdata                                 = array(); // phpcs:ignore WordPress.WP.GlobalVariablesOverride
$userdata['personal_detail']['user_name'] = ! empty( $user->data->display_name ) ? $user->data->display_name : $user->data->user_login; // phpcs:ignore WordPress.WP.GlobalVariablesOverride
$userdata['personal_detail']['user_mail'] = $user->data->user_email; // phpcs:ignore WordPress.WP.GlobalVariablesOverride
$userdata['social_detail']['facebook']    = get_user_meta( $user->ID, 'facebook', true ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride
$userdata['social_detail']['twitter']     = get_user_meta( $user->ID, 'twitter', true ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride
$userdata['social_detail']['linkedin']    = get_user_meta( $user->ID, 'linkedin', true ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride
$userdata['social_detail']['pinterest']   = get_user_meta( $user->ID, 'pinterest', true ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride
$userdata['social_detail']['instagram']   = get_user_meta( $user->ID, 'instagram', true ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride

?>
<div class="cdfs-user-info">
	<div class="col-sm-12">
		<div class="section-left">
			<div class="profile-img">
				<img src="<?php echo esc_url( $cdfs_user_avatar ); ?>" />
			</div>
			<div class="profile-details">
				<ul class="personal-detail">
					<?php foreach ( $userdata['personal_detail'] as $title => $data ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride ?>
						<li> <strong><?php echo esc_html( ucwords( str_replace( '_', ' ', $title ) ) ); ?></strong> <span><?php echo esc_html( $data ); ?></span></li>
						<?php
					}
					?>
				</ul>
				<ul class="social-details">
					<?php
					foreach ( $userdata['social_detail'] as $title => $data ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride
						if ( empty( $data ) ) {
							continue;
						}
						echo '<li><a href="' . esc_url( $data ) . '" target="_blank"><i class="fa fa-' . $title . '"></i></a></li>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
					}
					?>
				</ul>
			</div>
		</div>
		<div class="section-right">
			<div class="pull-right">
				<?php
				if ( is_user_logged_in() ) {
					global $car_dealer_options;
					$pricing_page = ( isset( $car_dealer_options['cdfs_plan_pricing_page'] ) && ! empty( $car_dealer_options['cdfs_plan_pricing_page'] ) ) ? get_permalink( $car_dealer_options['cdfs_plan_pricing_page'] ) : '';

					if ( class_exists( 'Subscriptio' ) || class_exists( 'RP_SUB' ) ) {
						$user               = wp_get_current_user();
						$user_id            = intval( $user->ID );
						$user_subscriptions = array();

						if ( function_exists( 'subscriptio_get_customer_subscriptions' ) ) {
							$user_subscriptions = subscriptio_get_customer_subscriptions( $user_id );
						}

						$_product_id        = '';
						$status             = false; // phpcs:ignore WordPress.WP.GlobalVariablesOverride

						if ( ! empty( $user_subscriptions ) ) {

							$user_subscription = reset( $user_subscriptions );

							$status   = $user_subscription->get_status(); // phpcs:ignore WordPress.WP.GlobalVariablesOverride
							$id       = $user_subscription->get_id(); // phpcs:ignore WordPress.WP.GlobalVariablesOverride
							$items    = $user_subscription->get_items();
							$datetime = $user_subscription->calculate_next_renewal_payment_datetime();

							$datetime = gmdate( 'd-m-Y', strtotime( $datetime ) );

							foreach ( $items as $item ) {
								$_product_id = $item->get_product_id();
							}
							$_product_title = get_the_title( $_product_id );
						}

						if ( 'active' === $status ) {
							echo '<div><strong> ' . esc_html__( 'Current Plan', 'cdfs-addon' ) . '</strong> <span> ' . $_product_title . ' </span></div>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
							echo '<div><strong> ' . esc_html__( 'Subscription Renewal', 'cdfs-addon' ) . '</strong> <span> ' . $datetime . ' </span></div>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE

							if ( ! empty( $pricing_page ) ) {
								echo '<div><a class="heading-font button" href="' . esc_url( $pricing_page ) . '">' . esc_html__( 'Get New Plan', 'cdfs-addon' ) . '</a></div>';
							}
						} else {
							echo '<div><strong> ' . esc_html__( 'Current Plan', 'cdfs-addon' ) . '</strong> <span> ' . esc_html__( 'Free Plan', 'cdfs-addon' ) . ' </span></div>';
							if ( ! empty( $pricing_page ) ) {
								echo '<div><a class="heading-font button" href="' . esc_url( $pricing_page ) . '">' . esc_html__( 'Update Plan', 'cdfs-addon' ) . '</a></div>';
							}
						}
					}
				}
				?>
			</div>
		</div>
	</div>
</div>

<?php do_action( 'cdfs_after_user_profile' ); ?>
