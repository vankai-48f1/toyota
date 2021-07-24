<?php
/**
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/cardealer-front-submission/my-user-account/form-forgot-password.php.
 *
 * @author  PotenzaGlobalSolutions
 * @package CDFS
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

cdfs_print_notices(); ?>

<form method="post" class="cdfs_lost_user_password">

	<input id="cdhl_nonce" name="cdhl_nonce" class="form-control" value="<?php echo wp_create_nonce( 'cdhl-lost-psw' ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>" type="hidden">
	<input type="hidden" name="cdfs_action" value="cdfs_password_reset" />

	<p><?php echo apply_filters( 'cdfs_lost_user_password_message', __( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'cdfs-addon' ) ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></p>

	<p class="cdfs-row">
		<label for="user_login"><?php esc_html_e( 'Username or email', 'cdfs-addon' ); ?></label>
		<input class="cdfs-Input" type="text" name="user_login" id="user_login" />
	</p>
	<?php if ( cdfs_check_captcha_exists() ) { ?>
	<p class="cdfs-row">
		<div class="form-group">
			<div id="login_captcha" class="g-recaptcha" data-sitekey="<?php echo esc_attr( cdfs_get_goole_api_keys( 'site_key' ) ); ?>"></div>
		</div>  
	</p>
	<div class="clear"></div>
	<?php } ?>
	<?php do_action( 'cdfs_forgot_password_form' ); ?>

	<p class="cdfs-row">
		<input type="submit" class="cdfs-button button" value="<?php esc_attr_e( 'Send reset password link', 'cdfs-addon' ); ?>" />
	</p>

</form>
