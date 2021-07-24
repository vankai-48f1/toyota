<?php
/**
 * User add new car
 * This template can be overridden by copying it to yourtheme/cardealer-front-submission/cars/cars-add.php.
 *
 * @author  PotenzaGlobalSolutions
 * @package CDFS
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'cdfs_add_car_page_start' );

$car_edit = false;

if ( ! empty( $_GET['edit-car'] ) && wp_unslash( $_GET['edit-car'] ) ) {
	$car_edit = true;
}

$restricted = false;

if ( is_user_logged_in() ) {
	$user    = wp_get_current_user();
	$user_id = $user->ID;
}

$vars = array();

if ( $car_edit ) {
	if ( ! is_user_logged_in() ) {
		echo '<h4>' . esc_html__( 'Please login.', 'cdfs-addon' ) . '</h4>';
		return false;
	}
	if ( ! isset( $_GET['cdfs_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_GET['cdfs_nonce'] ), 'cdhl-action' ) ) {
		echo '<h4>' . esc_html__( 'Invalid action, try again later.', 'cdfs-addon' ) . '</h4>';
		return false;
	}

	if ( ! empty( $_GET['car-id'] ) ) {
		$car_id = intval( $_GET['car-id'] );

		$car_user = get_post_meta( $car_id, 'cdfs_car_user', true );

		if ( intval( $user_id ) !== intval( $car_user ) ) {
			echo '<h4>' . esc_html__( 'You are not the owner of this vehicle.', 'cdfs-addon' ) . '</h4>';
			return false;
		}
	} else {
		echo '<h4>' . esc_html__( 'No vehicle to edit.', 'cdfs-addon' ) . '</h4>';
		return false;
	}

	$vars = array(
		'id' => $car_id,
	);
} ?>
<div class="cdfs_add_car_page">
	<div class="cdfs_add_car_form cdfs_add_car_form_<?php echo $car_edit; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>">

		<?php do_action( 'cdfs_before_add_car_form' ); ?>

		<form method="POST" action="" enctype="multipart/form-data" id="cdfs_car_form">
			<?php
			if ( $car_edit ) {
				?>
				<input name="cdfs_action_car_id" value="<?php echo esc_attr( $car_id ); ?>" type="hidden">
				<?php
			} else {
				$action = 'cdfs_add_car'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride
			}
			?>
			<input name="cdfs_car_form_action" class="form-control" value="cdfs_add_car" type="hidden">
			<?php
			if ( isset( $user_id ) ) {
				wp_nonce_field( 'cdfs-car-form', 'cdfs-car-form-nonce-field' );
			}

			global $car_dealer_options;

			$important_fields         = array( 'year', 'make', 'model', 'stock_number', 'vin_number', 'regular_price', 'sale_price' );
			$vars['important_fields'] = $important_fields;
			$form_sections_default    = array(
				'cars-image-gallery'  => '1',
				'cars-location'       => '1',
				'car-attributes'      => '1',
				'cars-pdf-brochure'   => '1',
				'car-additional-info' => '1',
				'cars-excerpt'        => '1',
			);
			$form_sections_option     = ( isset( $car_dealer_options['cdfs_form_sections'] ) && ! empty( $car_dealer_options['cdfs_form_sections'] ) ) ? $car_dealer_options['cdfs_form_sections'] : $form_sections_default;

			// Important Fields
			cdfs_get_template( 'cars/cars-templates/car-attributes-important.php', $vars );

			$add_vehicle_sections = array(
				'cars-image-gallery' => array(
					'label'    => esc_html__( 'Vehicle Images', 'cdfs-addon' ),
					'template' => 'cars/cars-templates/cars-image-gallery.php',
				),
				'cars-location' => array(
					'label'    => esc_html__( 'Vehicle Location', 'cdfs-addon' ),
					'template' => 'cars/cars-templates/cars-location.php',
				),
				'car-attributes' => array(
					'label'    => esc_html__( 'Vehicle Attributes', 'cdfs-addon' ),
					'template' => 'cars/cars-templates/car-attributes.php',
				),
				'cars-pdf-brochure' => array(
					'label'    => esc_html__( 'Vehicle PDF Brochure', 'cdfs-addon' ),
					'template' => 'cars/cars-templates/cars-pdf-brochure.php',
				),
				'car-additional-info' => array(
					'label'    => esc_html__( 'Additional Information', 'cdfs-addon' ),
					'template' => 'cars/cars-templates/car-additional-info.php',
				),
				'cars-excerpt' => array(
					'label'    => esc_html__( 'Vehicle Excerpt', 'cdfs-addon' ),
					'template' => 'cars/cars-templates/cars-excerpt.php',
				),
			);
			?>
			<div id="cdfs-add-vehicle-sections" class="cdfs-add-vehicle-sections panel-group" role="tablist" aria-multiselectable="true">
				<?php
				foreach ( $add_vehicle_sections as $add_vehicle_section_k => $add_vehicle_section ) {

					if (
						! isset( $form_sections_option[ $add_vehicle_section_k ] )
						|| ( isset( $form_sections_option[ $add_vehicle_section_k ] ) && '1' !== (string) $form_sections_option[ $add_vehicle_section_k ] )
					) {
						continue;
					}

					$section_classes = array(
						'panel',
						'panel-default',
						'cdfs-add-vehicle-section',
						"cdfs-add-vehicle-section-$add_vehicle_section_k",
					);
					$section_classes = cdfs_class_builder( $section_classes );

					$tab_id     = "tab-$add_vehicle_section_k";
					$content_id = "content-$add_vehicle_section_k";
					?>
					<div class="<?php echo esc_attr( $section_classes ); ?>">
						<div class="panel-heading cdfs-add-vehicle-panel-heading" role="tab" id="<?php echo esc_attr( $tab_id ); ?>">
							<a class="cdfs-add-vehicle-toggle collapsed" role="button" data-toggle="collapse" data-parent="#cdfs-add-vehicle-sections" href="#<?php echo esc_attr( $content_id ); ?>" aria-expanded="true" aria-controls="<?php echo esc_attr( $content_id ); ?>">
								<h4 class="panel-title cdfs-add-vehicle-title"><?php echo esc_html( $add_vehicle_section['label'] ); ?></h4>
							</a>
						</div>
						<div id="<?php echo esc_attr( $content_id ); ?>" class="panel-collapse collapse cdfs-add-vehicle-panel" role="tabpanel" aria-labelledby="<?php echo esc_attr( $tab_id ); ?>">
							<div class="panel-body cdfs-add-vehicle-panel-body"><div class="cdfs-add-vehicle-panel-body-inner"><?php cdfs_get_template( $add_vehicle_section['template'], $vars ); ?></div></div>
						</div>
					</div>
					<?php
				}
				?>

			</div>
			<?php
			cdfs_get_template( 'my-user-account/user-details-car-page.php' ); // User details on ajax login.
			if ( cdfs_check_captcha_exists() ) {
				?>
				<p class="cdfs-form-row">
					<div class="form-group">
						<div id="car_form_captcha" class="g-recaptcha" data-sitekey="<?php echo esc_attr( cdfs_get_goole_api_keys( 'site_key' ) ); ?>" style="<?php echo ( ( ! isset( $user_id ) ) ? 'display:none' : '' ); ?>"></div>
					</div>
				</p>
				<?php
			}
			?>
		</form>

		<?php do_action( 'cdfs_after_add_car_form' ); ?>

	</div>

	<?php
	if ( ! isset( $user_id ) ) {
		$class    = 'disabled';
		$disabled = 'disabled=disabled';
	} else {
		$class    = '';
		$disabled = '';
	}
	$label = ( $car_edit ) ? esc_html__( 'Update Details', 'cdfs-addon' ) : esc_html__( 'Submit Details', 'cdfs-addon' );
	?>
	<p class="cdfs-form-row">
		<div class="form-group">
			<button id="cdfs-submit-car" class="button btn cdfs-submit-car <?php echo esc_html( $class ); ?>" <?php echo esc_html( $disabled ); ?>><?php echo esc_html( $label ); ?></button>
		</div>
	</p>

</div>
<?php
do_action( 'cdfs_add_car_page_end' );
