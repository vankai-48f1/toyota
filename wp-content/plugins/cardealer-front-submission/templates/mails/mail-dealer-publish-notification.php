<?php
/**
 * Delaer notification on Vehicle published by Admin
 *
 * This template can be overridden by copying it to yourtheme/cardealer-front-submission/mails/mail-dealer-publish-notification.php
 *
 * @author  PotenzaGlobalSolutions
 * @package CDFS
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php do_action( 'cdfs_dealer_publish_notification_header' ); ?>
	<p>
		<?php
		/* translators: %s: author name */
		printf( esc_html__( 'Hello %1$s, Your vehicle has been successfully approved and published by Admin(%2$s)!', 'cdfs-addon' ), $car_data['dealer_name'], $car_data['admin_name'] ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		?>
	</p>
	<p>
		<u><?php esc_html_e( 'Following is the vehicle link:', 'cdfs-addon' ); ?></u>
	</p>
	<p>
		<a href="<?php echo $car_data['vehicle_link']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>"><?php echo esc_html( $car_data['vehicle_title'] ); ?></a>
	</p>
	<p>
		<u><?php printf( esc_html__( 'Vehicle Details', 'cdfs-addon' ) ); ?></u>
	</p>
	<?php echo $car_data['mail_html']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>

	<br><br>
	<p>
		<?php esc_html_e( 'Regards,', 'cdfs-addon' ); ?><br>
		<?php echo esc_html( $site_data['site_title'] ); ?>
	</p>
<?php do_action( 'cdfs_dealer_publish_notification_footer' ); ?>
