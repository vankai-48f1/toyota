<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/cardealer-front-submission/my-user-account/form-login.php.
 *
 * @author  PotenzaGlobalSolutions
 * @package CDFS
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Notices.
cdfs_print_notices();

do_action( 'cdfs_before_user_login_form' ); ?>
<div class="row" id="cdfs_user_login">
	<div class="col-sm-6 cdfs_login">
		<h2><?php esc_html_e( 'Login', 'cdfs-addon' ); ?></h2>
		<form class="cdfs-form cdfs-form-login cdfs-user-form login" method="post" id="cdfs-form-user-login">
			<?php do_action( 'cdfs_login_form_start' ); ?>
			<p class="cdfs-form-row cdfs-msg" style="display:none"></p>
			<p class="cdfs-form-row">
				<label for="username"><?php esc_html_e( 'Username or email address', 'cdfs-addon' ); ?> <span class="required">*</span></label>
				<input type="text" class="cdfs-Input cdhl_validate" name="username" id="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" />
			</p>
			<p class="cdfs-form-row">
				<label for="password"><?php esc_html_e( 'Password', 'cdfs-addon' ); ?> <span class="required">*</span></label>
				<input class="cdfs-Input cdhl_validate" type="password" name="password" id="password" />
			</p>
			<?php if ( cdfs_check_captcha_exists() ) { ?>
			<p class="cdfs-form-row">
				<div class="form-group">
					<div id="login_captcha" class="g-recaptcha" data-sitekey="<?php echo esc_attr( cdfs_get_goole_api_keys( 'site_key' ) ); ?>"></div>
				</div>  
			</p>
			<?php } ?>  
			<?php do_action( 'cdfs_login_form' ); ?>
			<p class="form-row">
				<?php wp_nonce_field( 'cdfs-login', 'cdfs-login-nonce' ); ?>				
				<button type="submit" class="cdfs-Button button" name="login" value="<?php esc_attr_e( 'Login', 'cdfs-addon' ); ?>" id="form-user-login"><?php esc_attr_e( 'Login', 'cdfs-addon' ); ?></button>
				<label class="cdfs-form__label cdfs-form__label-for-checkbox inline">
					<input class="cdfs-form_input cdfs-form_input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'cdfs-addon' ); ?></span>
				</label>
			</p>
			<?php
			$pages = get_pages( // phpcs:ignore WordPress.WP.GlobalVariablesOverride
				array(
					'meta_key'   => '_wp_page_template',
					'meta_value' => 'templates/cardealer-front-submission.php',
				)
			);

			if ( ! empty( $pages ) && isset( $pages[0] ) ) {
				$dashboard_link = get_permalink( $pages[0]->ID );
			} else {
				$dashboard_link = cdfs_get_page_permalink( 'myuseraccount' );
			}
			?>
			<p class="cdfs-LostPassword lost_user_password">
				<a href="<?php echo esc_url( $dashboard_link . 'user-lost-password' ); ?>"><?php esc_html_e( 'Lost your password?', 'cdfs-addon' ); ?></a>
			</p>
			<?php
			do_action( 'cdfs_login_form_end' );
			?>
		</form>
	</div>
	<div class="col-sm-6 cdfs_register">
		<h2><?php esc_html_e( 'Register', 'cdfs-addon' ); ?></h2>
		<form method="post" class="register cdfs-user-form" id="cdfs-form-register">
			<?php do_action( 'cdfs_register_form_start' ); ?>
			<p class="cdfs-form-row cdfs-msg" style="display:none"></p>
			<p class="cdfs-form-row">
				<label for="reg_username"><?php esc_html_e( 'Username', 'cdfs-addon' ); ?> <span class="required">*</span></label>
				<input type="text" class="cdfs-Input cdhl_validate" name="username" id="reg_username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" />
			</p>
			<p class="cdfs-form-row">
				<label for="reg_email"><?php esc_html_e( 'Email address', 'cdfs-addon' ); ?> <span class="required">*</span></label>
				<input type="email" class="cdfs-Input cdhl_validate cardealer_mail" name="email" id="reg_email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" />
			</p>
			<p class="cdfs-form-row">
				<label for="reg_password"><?php esc_html_e( 'Password', 'cdfs-addon' ); ?> <span class="required">*</span></label>
				<input type="password" class="cdfs-Input cdhl_validate" name="password" id="reg_password" />
			</p>
			<?php if ( cdfs_check_captcha_exists() ) { ?>
			<p class="cdfs-form-row">
				<div class="form-group">
					<div id="register_captcha" class="g-recaptcha" data-sitekey="<?php echo esc_attr( cdfs_get_goole_api_keys( 'site_key' ) ); ?>"></div>
				</div>  
			</p>
			<?php } ?>
			<p class="cdfs-form-row">
				<?php
				echo apply_filters(
					'cdfs_register_user_privacy_msg',
					sprintf(
						wp_kses(
							/* translators: %s: url */
							__( 'Your personal data will be used in mapping with the vehicles you added to the website, to manage access to your account, and for other purposes described in our <a href="%1$s" class="cd-policy-terms" target="_blank">privacy policy</a>.', 'cdfs-addon' ),
							array(
								'a' => array(
									'href'   => array(),
									'target' => array(),
									'class'  => array(),
								),
							)
						),
						get_privacy_policy_url()
					)
				); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
				?>
			</p>
			<?php
			do_action( 'cdfs_register_form' );
			?>
			<p class="cdfs-FormRow form-row">
				<?php wp_nonce_field( 'cdfs-register', 'cdfs-register-nonce' ); ?>				
				<button type="submit" id="cdfs-form-register-btn" class="cdfs-Button button" name="register" value="<?php esc_attr_e( 'Register', 'cdfs-addon' ); ?>"><?php esc_attr_e( 'Register', 'cdfs-addon' ); ?></button>
			</p>
			<?php do_action( 'cdfs_register_form_end' ); ?>
		</form>
	</div>
</div>
<?php do_action( 'cdfs_after_user_login_form' ); ?>
