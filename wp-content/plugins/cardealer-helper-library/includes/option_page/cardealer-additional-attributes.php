<?php
/**
 * Additional attributes page.
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

/**
 * Register additioonal attributes page.
 */
function register_additional_attributes_page() {
	add_submenu_page(
		'edit.php?post_type=cars',
		esc_html__( 'Additional Attributes', 'cardealer-helper' ),
		esc_html__( 'Add New Attributes', 'cardealer-helper' ),
		'manage_options',
		'additional_attributes_page',
		'additional_attributes_page_callback'
	);
}
add_action( 'admin_menu', 'register_additional_attributes_page' );

/**
 * Display callback for the submenu page.
 */
function additional_attributes_page_callback() {
	$nonce = wp_create_nonce( 'add_edit_additional_attributes' );
	?>
	<div class="wrap additional-attributes-page">
		<h1><?php esc_html_e( 'Additional Attributes', 'cardealer-helper' ); ?></h1>
		<div class="cdhl-admin-notice">
		<?php
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['message'] ) && ! empty( $_GET['message'] ) ) {
			if ( 'attribute_added' === $_GET['message'] ) {
				cdhl_admin_notice( esc_html__( 'Attribute added successfully.', 'cardealer-helper' ), 'success', '', false );
			}
			if ( 'attribute_updated' === $_GET['message'] ) {
				cdhl_admin_notice( esc_html__( 'Attribute updated successfully.', 'cardealer-helper' ), 'success', '', false );
			}
			if ( 'attribute_deleted' === $_GET['message'] ) {
				cdhl_admin_notice( esc_html__( 'Attribute deleted successfully.', 'cardealer-helper' ), 'success', '', false );
			}
		}
		// phpcs:enable
		?>
		</div>
		<div class="inside">
			<form id="edit_additional_attributes" method="post" name="edit_additional_attributes" data-nonce="<?php echo esc_attr( $nonce ); ?>">
				<table class="wp-list-table widefat" width="100%">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Singular name', 'cardealer-helper' ); ?></th>
							<th><?php esc_html_e( 'Plural name', 'cardealer-helper' ); ?></th>
							<th><?php esc_html_e( 'Slug', 'cardealer-helper' ); ?></th>
							<th><?php esc_html_e( 'Action', 'cardealer-helper' ); ?></th>
						</tr>
					</thead>
					<tbody class="additional-attributes-data">
						<?php cdhl_get_additional_attributes_html(); ?>
					</tbody>
				</table>
			</form>
		</div>
		<br class="clear">
		<div class="postbox postbox-add-new-attributes">
			<div class="postbox-header">
				<h2 class="add-new-additional-attributes-title"><?php esc_html_e( 'Add New Attribute', 'cardealer-helper' ); ?></h2>
			</div>
			<div class="inside">
				<div class="col-container wp-clearfix">
				<div id="col-left">
					<div class="col-wrap">
						<form id="new_additional_attributes_form" method="post" name="add_new_additional_attributes" data-nonce="<?php echo esc_attr( $nonce ); ?>">
							<table class="form-table" role="presentation">
								<tr>
									<th scope="row"><label for="singular_name"><?php esc_html_e( 'Singular name', 'cardealer-helper' ); ?></label></th>
									<td><input type="text" name="singular_name" id="singular_name" class="regular-text" value=""></td>
								</tr>
								<tr>
									<th scope="row"><label for="plural_name"><?php esc_html_e( 'Plural name', 'cardealer-helper' ); ?></label></th>
									<td><input type="text" name="plural_name" id="plural_name" class="regular-text" value=""></td>
								</tr>
								<tr>
									<th scope="row"><label for="attribute_slug"><?php esc_html_e( 'Slug', 'cardealer-helper' ); ?></label></th>
									<td>
										<input type="text" name="attribute_slug" id="attribute_slug" class="regular-text" value="" maxlength="32">
										<p class="description" id="attribute_slug-description"><?php esc_html_e( 'The slug must contain alphanumeric characters, underscore (_) and dash (-), and length must not exceed 32 characters.', 'cardealer-helper' ); ?></p>
									</td>
								</tr>
							</table>
							<p class="submit">
								<input type="submit" name="add-additional-attributes-submit" id="add-additional-attributes-submit" class="button button-primary" value="Submit">
								<span class="spinner" style="float: inherit; padding: 4px 0;"></span>
							</p>
						</form>
					</div>
				</div>
				<div id="col-right">
					<div class="col-wrap">
						<p><?php esc_html_e( 'After adding new attributes you can manage them from the below sections/locations.', 'cardealer-helper' ); ?></p>
						<ul style="list-style: inside;">
							<?php /* translators: %1$s Theme options link */ ?>
							<li><?php printf( wp_kses( __( '<a href="%1$s">Add/Edit</a> vehicle inventory.', 'cardealer-helper' ), array( 'a' => array( 'href' => true ) ) ), esc_url( admin_url( 'post-new.php?post_type=cars' ) ) ); ?></li>
							<li><?php esc_html_e( 'All Import processes (including CSV Import/Export, WP All Import, Web Manager, VINQuery Import).', 'cardealer-helper' ); ?></li>
							<?php /* translators: %1$s Theme options link */ ?>
							<li><?php printf( wp_kses( __( 'Theme options → Vehicle settings → Vehicles Listing Filters → <a href="%1$s">Vehicles Listing Filters</a> field.', 'cardealer-helper' ), array( 'a' => array( 'href' => true ) ) ), esc_url( car_dealer_get_options_tab_url( 'cars_listing_filters' ) ) ); ?></li>
							<?php /* translators: %1$s Theme options link */ ?>
							<li><?php printf( wp_kses( __( 'Theme options → Vehicle settings → Vehicle Inventory Settings → <a href="%1$s">List Attributes</a> field. "List Attributes" field is visible when the "List Style" field is set to "Classic".', 'cardealer-helper' ), array( 'a' => array( 'href' => true ) ) ), esc_url( admin_url( 'admin.php?page=cardealer' ) ) ); ?></li>
							<?php /* translators: %1$s Theme options link */ ?>
							<li><?php printf( wp_kses( __( 'Theme options → Vehicle settings → Vehicle Detail Page Settings → <a href="%1$s">Vehicle Detail Attributes</a> field.', 'cardealer-helper' ), array( 'a' => array( 'href' => true ) ) ), esc_url( admin_url( 'admin.php?page=cardealer' ) ) ); ?></li>
							<?php /* translators: %1$s PDF generator page link */ ?>
							<li><?php printf( wp_kses( __( 'PDF Generation, to get these new attributes, you need to reset <a href="%1$s">all template</a>.', 'cardealer-helper' ), array( 'a' => array( 'href' => true ) ) ), esc_url( admin_url( 'edit.php?post_type=cars&page=pdf_generator' ) ) ); ?></li>
							<li><?php esc_html_e( 'Front side vehicle listing filters, vehicle details page, lead forms emails, compare vehicle functionality, etc.', 'cardealer-helper' ); ?></li>
							<li><?php esc_html_e( 'Car Dealer - Fronted Submission (Vendor) plugin.', 'cardealer-helper' ); ?></li>
						</ul>
					</div>
				</div>


			</div>
		</div>
	</div>
	<?php
}
