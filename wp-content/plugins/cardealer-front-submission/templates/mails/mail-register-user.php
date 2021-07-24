<?php
/**
 * User Registration mail body
 *
 * This template can be overridden by copying it to yourtheme/cardealer-front-submission/mails/mail-register-user.php
 *
 * @author  PotenzaGlobalSolutions
 * @package CDFS
 * @version 1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php
do_action( 'cdfs_register_user_header' );
switch ( $action ) {
	case 'activation_link_mail': // send activation link.
		?>
		<p>
			<?php
			/* translators: %s: user name */
			printf( esc_html__( 'Hello %s, You are successfully registered!', 'cdfs-addon' ), $user_data['user_login'] ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			?>
		</p>
		<p>
			<?php esc_html_e( 'Following are the details:', 'cdfs-addon' ); ?>
		</p>
		<p>
			<?php
			/* translators: %s: username */
			printf( esc_html__( 'Username: %s', 'cdfs-addon' ), $user_data['user_login'] ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			?>
		</p>
		<p>
			<?php
			/* translators: %s: user email*/
			printf( esc_html__( 'Email: %s', 'cdfs-addon' ), $user_data['user_email'] ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			?>
		</p>
		<p>
			<?php echo esc_html__( 'Please click on the below activation link in order to activate your account.', 'cdfs-addon' ); ?>
		</p>
		<p>
		<a href="<?php echo esc_url( add_query_arg( array( 'usr-activate' => $activation_token ), cdfs_get_page_permalink( 'myuseraccount' ) ) ); ?>" target="_blank">
			<?php echo esc_html__( 'Activation Link', 'cdfs-addon' ); ?>
		</a></p>
		<?php
		break;
	case 'registration_mail': // user registration.
		?>
		<p>
			<?php
			/* translators: %s: user name */
			printf( esc_html__( 'Hello %s, Your account is successfully registered and activated!', 'cdfs-addon' ), $user_data['user_login'] ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			?>
		</p>
		<p>
			<?php esc_html_e( 'Following are the details: ', 'cdfs-addon' ); ?>
		</p>
		<p>
			<?php
			/* translators: %s: user name */
			printf( esc_html__( 'Username: %s ', 'cdfs-addon' ), $user_data['user_login'] ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			?>
		</p>
		<p>
			<?php
			/* translators: %s: user email */
			printf( esc_html__( 'Email: %s ', 'cdfs-addon' ), $user_data['user_email'] ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			?>
		</p>
		<?php
		break;
	case 'registration_pending_for_admin_approval_mail': // user registration.
		?>
		<p>
			<?php
			/* translators: %s: user name */
			printf( esc_html__( 'Hello %s,', 'cdfs-addon' ), $user_data['user_login'] ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			?>
		</p>
		<p>
			<?php esc_html_e( 'Your account is successfully registered with us. Your account status is pending for admin approval!', 'cdfs-addon' ); ?>
		</p>
		<p>
			<?php esc_html_e( 'Once admin approves your account, you can able to add vehicle etc.', 'cdfs-addon' ); ?>
		</p>
		<p>
			<?php esc_html_e( 'Following are the details: ', 'cdfs-addon' ); ?>
		</p>
		<p>
			<?php
			/* translators: %s: user name */
			printf( esc_html__( 'Username: %s ', 'cdfs-addon' ), $user_data['user_login'] ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			?>
		</p>
		<p>
			<?php
			/* translators: %s: user email */
			printf( esc_html__( 'Email: %s ', 'cdfs-addon' ), $user_data['user_email'] ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			?>
		</p>
		<?php
		break;
	case 'send_user_account_status_change_mail': // sent to dealer when admin change account status pending to active.
		?>
		<p>
			<?php
			/* translators: %s: user name */
			printf( esc_html__( 'Hello %s,', 'cdfs-addon' ), $user_data['user_login'] ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			?>
		</p>
		<p>
			<?php esc_html_e( 'Your account is now approved and activated by admin.', 'cdfs-addon' ); ?>
		</p>
		<p>
			<?php esc_html_e( 'Now you can able to add a vehicle and manage the profile etc.', 'cdfs-addon' ); ?>
		</p>
		<p>
			<?php esc_html_e( 'Following are the details: ', 'cdfs-addon' ); ?>
		</p>
		<p>
			<?php
			/* translators: %s: user name */
			printf( esc_html__( 'Username: %s ', 'cdfs-addon' ), $user_data['user_login'] ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			?>
		</p>
		<p>
			<?php
			/* translators: %s: user email */
			printf( esc_html__( 'Email: %s ', 'cdfs-addon' ), $user_data['user_email'] ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			?>
		</p>
		<?php
		break;
	case 'admin_register_user': // admin notification on user registration.
		?>
		<p>
			<?php esc_html_e( 'Hello, New user is registered! ', 'cdfs-addon' ); ?>
		</p>
		<p>
			<?php esc_html_e( 'Following are the details: ', 'cdfs-addon' ); ?>
		</p>
		<p>
			<?php
			/* translators: %s: user name */
			printf( esc_html__( 'Username: %s ', 'cdfs-addon' ), $user_data['user_login'] ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			?>
		</p>
		<p>
			<?php
			/* translators: %s: user email */
			printf( esc_html__( 'Email: %s ', 'cdfs-addon' ), $user_data['user_email'] ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			?>
		</p>
		<p>
			<?php echo esc_html__( 'Role: Car Dealer ', 'cdfs-addon' ); ?>
		</p>
		<?php
		break;
	case 'admin_user_activated': // admin notification on user account activation.
		?>
		<p>
			<?php
			/* translators: %s: user name */
			printf( esc_html__( 'Hello, %s\'s dealer account is activated.', 'cdfs-addon' ), $user_data['user_name'] ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			?>
		</p>
		<p>
			<?php esc_html_e( 'Following are the details:', 'cdfs-addon' ); ?>
		</p>
		<p>
			<?php
			/* translators: %s: user name */
			printf( esc_html__( 'Username: %s', 'cdfs-addon' ), $user_data['user_name'] ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			?>
		</p>
		<p>
			<?php
			/* translators: %s: user email */
			printf( esc_html__( 'Email: %s', 'cdfs-addon' ), $user_data['user_email'] ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			?>
		</p>
		<p>
			<?php echo esc_html__( 'Role: Car Dealer', 'cdfs-addon' ); ?>
		</p>
		<?php
		break;
	default: // notification to user when account is activated.
		?>
		<p>
			<?php
			/* translators: %s: user name */
			printf( esc_html__( 'Hello %s, Your dealer account is successfully activated!', 'cdfs-addon' ), $user_data['user_name'] ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			?>
		</p>
		<p>
			<?php esc_html_e( 'Following are the details:', 'cdfs-addon' ); ?>
		</p>
		<p>
			<?php
			/* translators: %s: user name */
			printf( esc_html__( 'Username: %s', 'cdfs-addon' ), $user_data['user_name'] ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			?>
		</p>
		<p>
			<?php
			/* translators: %s: user email */
			printf( esc_html__( 'Email: %s', 'cdfs-addon' ), $user_data['user_email'] ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			?>
		</p>
		<?php
}
?>
<br><br>
<p>
	<?php esc_html_e( 'Regards,', 'cdfs-addon' ); ?><br>
	<a href="<?php echo esc_attr( $site_data['site_url'] ); ?>" target="_blank">
		<?php echo esc_html( $site_data['site_title'] ); ?>
	</a>
</p>

<?php do_action( 'cdfs_register_user_footer' ); ?>
