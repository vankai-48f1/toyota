<?php
/**
 * Edit user account form
 *
 * This template can be overridden by copying it to yourtheme/cardealer-front-submission/my-user-account/form-edit-account.php
 *
 * @author  PotenzaGlobalSolutions
 * @package CDFS
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'cdfs_before_edit_account_form' ); ?>

<form id="cdfs-edit-account-form" class="cdfs-edit-account-form edit-account" action="" method="post" enctype="multipart/form-data">

	<?php do_action( 'cdfs_edit_account_form_start' ); ?>
	<fieldset>
		<legend><?php esc_html_e( 'Personal details', 'cdfs-addon' ); ?></legend>
		<p class="cdfs-form-row cdfs-form-row-first form-row form-row-first">
			<label for="account_first_name"><?php esc_html_e( 'First name', 'cdfs-addon' ); ?> <span class="required">*</span></label>
			<input type="text" class="cdfs-Input cdfs-Input-text input-text cdhl_validate" name="account_first_name" id="account_first_name" value="<?php echo esc_attr( $user->first_name ); ?>" />
		</p>
		<p class="cdfs-form-row cdfs-form-row--last form-row form-row-last">
			<label for="account_last_name"><?php esc_html_e( 'Last name', 'cdfs-addon' ); ?> <span class="required">*</span></label>
			<input type="text" class="cdfs-Input cdfs-Input-text input-text cdhl_validate" name="account_last_name" id="account_last_name" value="<?php echo esc_attr( $user->last_name ); ?>" />
		</p>
		<div class="clear"></div>

		<p class="cdfs-form-row cdfs-form-row--last form-row form-row-last">
			<label for="account_last_name"><?php esc_html_e( 'Profile Img', 'cdfs-addon' ); ?></label>
			<input type="file" id="profile-img" name="profile_img" class="form-control user_picked_files" />

			<div class="row col-sm-12">
				<ul class="cdfs_uploaded_files">
					<?php
						$cdfs_user_avatar = get_user_meta( $user->ID, 'cdfs_user_avatar', true );
					if ( isset( $cdfs_user_avatar ) && ! empty( $cdfs_user_avatar ) ) {
						?>
								<li class="cdfs-item">    
									<img class="img-thumb" src="<?php echo esc_url( $cdfs_user_avatar ); ?>"/>
								</li>
							<?php
					}
					?>
				</ul>
			</div>

		</p>
		<div class="clear"></div>

		<p class="cdfs-form-row cdfs-form-row--wide form-row form-row-wide">
			<label for="account_email"><?php esc_html_e( 'Email address', 'cdfs-addon' ); ?> <span class="required">*</span></label>
			<input type="email" class="cdfs-Input cdfs-Input-email input-text cdhl_validate" name="account_email" id="account_email" value="<?php echo esc_attr( $user->user_email ); ?>" />
		</p>
	</fieldset>

	<fieldset>
		<legend><?php esc_html_e( 'Password change', 'cdfs-addon' ); ?></legend>

		<p class="cdfs-form-row cdfs-form-row-wide form-row form-row-wide">
			<label for="password_current"><?php esc_html_e( 'Current password (leave blank to leave unchanged)', 'cdfs-addon' ); ?></label>
			<input type="password" class="cdfs-Input cdfs-Input-password input-text" name="password_current" id="password_current" />
		</p>
		<p class="cdfs-form-row cdfs-form-row--wide form-row form-row-wide">
			<label for="password_1"><?php esc_html_e( 'New password (leave blank to leave unchanged)', 'cdfs-addon' ); ?></label>
			<input type="password" class="cdfs-Input cdfs-Input-password input-text" name="password_1" id="password_1" />
		</p>
		<p class="cdfs-form-row cdfs-form-row--wide form-row form-row-wide">
			<label for="password_2"><?php esc_html_e( 'Confirm new password', 'cdfs-addon' ); ?></label>
			<input type="password" class="cdfs-Input cdfs-Input-password input-text" name="password_2" id="password_2" />
		</p>
	</fieldset>

	<fieldset>
		<legend><?php esc_html_e( 'Social Profiles', 'cdfs-addon' ); ?></legend>
		<p class="cdfs-form-row cdfs-form-row-wide form-row form-row-wide">
			<label for="user_facebook"><?php esc_html_e( 'Facebook', 'cdfs-addon' ); ?></label>
			<input type="url" class="cdfs-Input cdfs-Input-facebook input-text" name="user_facebook" id="user_facebook" value="<?php echo esc_attr( get_user_meta( $user->ID, 'facebook', true ) ); ?>" />
		</p>
		<p class="cdfs-form-row cdfs-form-row--wide form-row form-row-wide">
			<label for="user_twitter"><?php esc_html_e( 'Twitter', 'cdfs-addon' ); ?></label>
			<input type="url" class="cdfs-Input cdfs-Input-twitter input-text" name="user_twitter" id="user_twitter" value="<?php echo esc_attr( get_user_meta( $user->ID, 'twitter', true ) ); ?>"/>
		</p>
		<p class="cdfs-form-row cdfs-form-row--wide form-row form-row-wide">
			<label for="user_linkedIn"><?php esc_html_e( 'LinkedIn', 'cdfs-addon' ); ?></label>
			<input type="url" class="cdfs-Input cdfs-Input-linkedIn input-text" name="user_linkedIn" id="user_linkedIn" value="<?php echo esc_attr( get_user_meta( $user->ID, 'linkedin', true ) ); ?>"/>
		</p>
		<p class="cdfs-form-row cdfs-form-row--wide form-row form-row-wide">
			<label for="user_pinterest"><?php esc_html_e( 'Pinterest', 'cdfs-addon' ); ?></label>
			<input type="url" class="cdfs-Input cdfs-Input-pinterest input-text" name="user_pinterest" id="user_pinterest" value="<?php echo esc_attr( get_user_meta( $user->ID, 'pinterest', true ) ); ?>"/>
		</p>
		<p class="cdfs-form-row cdfs-form-row--wide form-row form-row-wide">
			<label for="user_instagram"><?php esc_html_e( 'Instagram', 'cdfs-addon' ); ?></label>
			<input type="url" class="cdfs-Input cdfs-Input-instagram input-text" name="user_instagram" id="user_instagram" value="<?php echo esc_attr( get_user_meta( $user->ID, 'instagram', true ) ); ?>"/>
		</p>
	</fieldset>

	<?php if ( cdfs_check_captcha_exists() ) { ?>
	<p class="cdfs-form-row cdfs-form-row--wide form-row form-row-wide">
		<div class="form-group">
			<div id="login_captcha" class="g-recaptcha" data-sitekey="<?php echo esc_attr( cdfs_get_goole_api_keys( 'site_key' ) ); ?>"></div>
		</div>  
	</p>
	<div class="clear"></div>
		<?php
	}
	do_action( 'cdfs_edit_account_form' );
	?>

	<p>
		<?php wp_nonce_field( 'update_account_details' ); ?>
		<input type="submit" class="cdfs-Button button" name="update_account_details" value="<?php esc_attr_e( 'Save changes', 'cdfs-addon' ); ?>" />
		<input type="hidden" name="action" value="update_account_details" />
	</p>

	<?php do_action( 'cdfs_edit_account_form_end' ); ?>
</form>

<?php do_action( 'cdfs_after_edit_account_form' ); ?>
