<?php
/**
 * Car form image gallery upload
 *
 * This template can be overridden by copying it to yourtheme/cardealer-front-submission/cars/cars-templates/cars-image-gallery.php.
 *
 * @author  PotenzaGlobalSolutions
 * @package CDFS
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'action_before_cars_images' );

?>
<div class="row">
	<div class="col-sm-12">
		<div class="form-group">
			<?php
			global $car_dealer_options;
			$img_up_limit = 20;
			if ( isset( $car_dealer_options['cdfs_cars_img_limit'] ) && ! empty( $car_dealer_options['cdfs_cars_img_limit'] ) ) {
				$img_up_limit = $car_dealer_options['cdfs_cars_img_limit'];
			}
			if ( class_exists( 'Subscriptio' ) || class_exists( 'RP_SUB' ) ) {
				if ( is_user_logged_in() ) {
					$user              = wp_get_current_user();
					$user_id           = $user->ID;
					$img_up_limit_temp = get_user_meta( $user_id, 'cdfs_img_limt', true );
					if ( $img_up_limit_temp ) {
						$img_up_limit = $img_up_limit_temp;
					}
				}
			}
			?>
			<label><?php echo esc_html__( 'Upload Images', 'cdfs-addon' ); ?></label>
			<input type="file" id="car-imgs" name="car_images[]" data-img_up_limit="<?php echo esc_attr( $img_up_limit ); ?>" class="form-control user_picked_files" multiple />
		</div>
		<div class = "form-group cdfs_order">
			<input id="file_attachments" name="file_attachments" type="hidden" class="form-control file_attachments"/>
		</div>
		<ul class="cdfs_uploaded_files">
			<?php
			if ( function_exists( 'get_field' ) && isset( $id ) ) {
				$images = get_field( 'car_images', $id );
				if ( $images ) {
					foreach ( $images as $image ) {
						?>
						<li file="<?php echo esc_attr( $image['id'] ); ?>" class="cdfs-item">
							<img class="img-thumb" src="<?php echo esc_url( $image['sizes']['car_thumbnail'] ); ?>"/>
							<a href="javascript:void(0)" data-field="<?php esc_attr_e( 'car_images' ); ?>" data-parent_id="<?php echo esc_attr( $id ); ?>" data-attach_id="<?php echo esc_attr( $image['id'] ); ?>" class="drop_img_item" title="Delete Image"><span class="remove">x</span></a>
						</li>
						<?php
					}
				}
			}
			?>
		</ul>
	</div>
</div>
<?php
do_action( 'action_after_cars_images' );
