<?php
/**
 * Car form attributes
 *
 * This template can be overridden by copying it to yourtheme/cardealer-front-submission/cars/cars-templates/car-attributes.php.
 *
 * @author  PotenzaGlobalSolutions
 * @package CDFS
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $car_dealer_options;

$car_fields            = cdfs_get_car_form_fields();
$important_fields      = $args['important_fields'];
$additional_attributes = array();

if ( isset( $car_dealer_options['add_car_additional_attributes'] ) && is_array( $car_dealer_options['add_car_additional_attributes'] ) && ! empty( $car_dealer_options['add_car_additional_attributes'] ) ) {
	foreach ( $car_dealer_options['add_car_additional_attributes'] as $add_car_additional_attributes_temp_k => $add_car_additional_attributes_temp_v ) {
		$additional_attributes[] = $add_car_additional_attributes_temp_k;
		if ( '1' === (string) $add_car_additional_attributes_temp_v ) {
			$important_fields[] = $add_car_additional_attributes_temp_k;
		}
	}
}
?>
<div class="cdfs_add_car_form">
	<div class="row cdfs-form clearfix">
		<?php if ( ! empty( $car_fields ) ) { ?>
			<?php
			$cars_taxonomy_array = cdfs_get_cars_taxonomy();

			foreach ( $car_fields as $field ) {

				if ( in_array( $field['name'], $important_fields, true ) || in_array( $field['name'], $additional_attributes, true ) ) {
					continue;
				}

				$value = '';
				if ( ! empty( $id ) ) {

					$cars_taxonomy = 'car_' . $field['name'];

					if ( in_array( $cars_taxonomy, $cars_taxonomy_array, true ) ) { // For taxonomy.
						$tax_obj = wp_get_post_terms( $id, $cars_taxonomy );
						if ( 'checkbox' === $field['type'] ) {
							$value = array();
							foreach ( $tax_obj as $obj ) {
								if ( isset( $obj->name ) ) {
									$value[] = $obj->name;
								}
							}
						} else {
							$value = ( ! empty( $tax_obj ) ) ? $tax_obj[0]->name : '';
						}
					} elseif ( in_array( $field['name'], $cars_taxonomy_array, true ) ) { // For addtional attributes taxonomy.
						$tax_obj = wp_get_post_terms( $id, $field['name'] );
						if ( 'checkbox' === $field['type'] ) {
							$value = array();
							foreach ( $tax_obj as $obj ) {
								if ( isset( $obj->name ) ) {
									$value[] = $obj->name;
								}
							}
						} else {
							$value = ( ! empty( $tax_obj ) ) ? $tax_obj[0]->name : '';
						}
					} else { // other than taxonomy.
						$value = get_post_meta( $id, $field['name'], true );
					}
				}

				if ( in_array( $field['type'], array( 'text', 'number', 'url' ), true ) ) {

					if ( 'url' === $field['type'] ) {
						$class = 'col-sm-6';
					} else {
						$class = 'col-sm-3';
					}
					?>

					<div class="<?php echo esc_attr( $class ); ?>">
						<div class="form-group">
							<label>
							<?php
							echo esc_html( $field['placeholder'] );
							echo ( strpos( $field['class'], 'cdhl_validate' ) !== false ) ? ' *' : '';
							?>
							</label>
								<input
									id="cdfs-<?php echo esc_attr( $field['name'] ); ?>"
									type="<?php echo esc_attr( $field['type'] ); ?>"
									class="form-control cdfs-<?php echo esc_attr( $field['name'] ); ?> <?php echo esc_attr( $field['class'] ); ?>"
									data-name="<?php echo esc_attr( $field['name'] ); ?>"
									name="car_data[<?php echo esc_attr( $field['name'] ); ?>]"
									value="<?php echo esc_attr( $value ); ?>"
									placeholder="<?php esc_html_e( 'Enter', 'cdfs-addon' ); ?> <?php echo esc_attr( $field['placeholder'] ); ?>"
								/>
						</div>
					</div>
					<?php
				} elseif ( 'radio' === $field['type'] ) {
					?>
					<div class="col-sm-9">
						<div class="form-group">
							<label><?php echo esc_html( $field['placeholder'] ); ?></label>
							<?php
							$first = 0;
							$i     = 1;
							foreach ( $field['options'] as $key => $option ) {
								if ( strtolower( $value ) === strtolower( $option ) ) {
									$checked = 'checked=checked';
								} else {
									$checked = ( 0 === $first ) ? 'checked=checked' : '';
								}
								$first = 1;
								?>
								<div class="col-sm-3">
									<label>
										<input id="cdfs-<?php echo esc_attr( $field['name'] . '-' . $i ); ?>" class="cdfs-radio cdfs-<?php echo esc_attr( $field['name'] ); ?>" name="car_data[<?php echo esc_attr( $field['name'] ); ?>]" value="<?php echo esc_attr( $key ); ?>" type="radio" <?php echo esc_attr( $checked ); ?>>
										<?php echo esc_attr( $option ); ?>
									</label>
								</div>
								<?php
								$i++;
							}
							?>
						</div>
					</div>
					<div class="clearfix"></div>
					<?php
				} if ( in_array( $field['type'], array( 'checkbox' ), true ) ) { // vehicle review stamps.
					if ( 'vehicle_review_stamps' === $field['name'] ) {
						$options = array(
							'taxonomy'   => 'car_vehicle_review_stamps',
							'orderby'    => 'name',
							'order'      => 'ASC',
							'hide_empty' => false, // can be 1, '1' too.
						);
						// search value.
						$vehicle_review_stamps = get_terms( $options );
						if ( ! empty( $vehicle_review_stamps ) ) {
							?>
							<div class="col-sm-12">
								<div class="form-group">
									<label><?php echo esc_html( $field['placeholder'] ); ?></label>
									<?php
									foreach ( $vehicle_review_stamps as $option ) {
										$cheched = '';
										if ( is_array( $value ) ) {
											if ( in_array( $option->name, $value ) ) {
												$cheched = 'checked';
											}
										}
										?>
										<div class="col-sm-6">
											<label>
												<input
													id="cdfs-<?php echo esc_attr( $option->slug ); ?>"
													type="<?php echo esc_attr( $field['type'] ); ?>"
													class="form-control cdfs-<?php echo esc_attr( $option->slug ); ?> cdfs-checkbox"
													name="car_data[<?php echo esc_attr( $field['name'] ); ?>][]"
													value="<?php echo esc_attr( $option->name ); ?>"
												<?php echo esc_attr( $cheched ); ?>
												/>
												<span><?php echo $option->name; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></span>
											</label>
										</div>
										<?php
									}
									?>
								</div>
							</div>
							<?php
						}
					}
				}
			}
			if ( ! empty( $id ) ) {
				$vars = array(
					'id' => $id,
				);
			} else {
				$vars = array();
			}
		}
		?>
	</div>
</div>
