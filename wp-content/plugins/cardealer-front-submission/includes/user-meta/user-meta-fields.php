<?php
/**
 *
 * Functions for the template functions for car pages.
 *
 * @author   PotenzaGlobalSolutions
 * @package  CDFS
 */

if ( ! function_exists( 'cdfs_usermeta_form_field_cdfs_user_status' ) ) {
	/**
	 * The field on the editing screens.
	 *
	 * @param stdClass $user WP_User user object.
	 */
	function cdfs_usermeta_form_field_cdfs_user_status( $user ) {
		?>
		<h3><?php esc_html_e( 'Acount status', 'cdfs-addon' ); ?></h3>
		<table class="form-table">
			<tr>
				<th>
					<label for="cdfs_user_status"><?php esc_html_e( 'Status', 'cdfs-addon' ); ?></label>
				</th>
				<td>
					<?php $status = esc_attr( get_user_meta( $user->ID, 'cdfs_user_status', true ) ); ?>
					<select id="cdfs_user_status" name="cdfs_user_status">
						<option value="active" <?php selected( $status, 'active' ); ?>><?php esc_html_e( 'Active', 'cdfs-addon' ); ?></option>
						<option value="pending" <?php selected( $status, 'pending' ); ?>><?php esc_html_e( 'Pending', 'cdfs-addon' ); ?></option>
					</select>
					<p class="description"></p>
				</td>
			</tr>
		</table>
		<?php
	}
}

if ( ! function_exists( 'cdfs_usermeta_form_field_cdfs_user_status_update' ) ) {
	/**
	 * The save action.
	 *
	 * @param int $user_id int the ID of the current user.
	 *
	 * @return bool Meta ID if the key didn't exist, true on successful update, false on failure.
	 */
	function cdfs_usermeta_form_field_cdfs_user_status_update( $user_id ) {
		// check that the current user have the capability to edit the $user_id.
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}
		$status = ( isset( $_POST['cdfs_user_status'] ) ) ? wp_unslash( $_POST['cdfs_user_status'] ) : '';
		if ( 'active' === $status ) {
			$userinfo  = get_userdata( $user_id );
			$mail_sent = cdfs_send_user_account_status_change_mail( $userinfo );
			if ( false === (bool) $mail_sent ) {
				return false;
			}
		}
		// create/update user meta for the $user_id.
		return update_user_meta(
			$user_id,
			'cdfs_user_status',
			$status
		);
	}
}

// add the field to user's own profile editing screen.
add_action(
	'edit_user_profile',
	'cdfs_usermeta_form_field_cdfs_user_status'
);

// add the field to user profile editing screen.
add_action(
	'show_user_profile',
	'cdfs_usermeta_form_field_cdfs_user_status'
);

// add the save action to user's own profile editing screen update.
add_action(
	'personal_options_update',
	'cdfs_usermeta_form_field_cdfs_user_status_update'
);

// add the save action to user profile editing screen update.
add_action(
	'edit_user_profile_update',
	'cdfs_usermeta_form_field_cdfs_user_status_update'
);
