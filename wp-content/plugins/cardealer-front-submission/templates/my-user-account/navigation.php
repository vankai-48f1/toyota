<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/cardealer-front-submission/my-user-account/navigation.php.
 *
 * @author  PotenzaGlobalSolutions
 * @package CDFS
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $car_dealer_options;

$user                 = wp_get_current_user();
$cdfs_get_post_limits = cdfs_get_post_limits( $user->ID );

do_action( 'cdfs_before_navigation' );
?>

<nav class="cdfs-my-user-account-navigation">
	<ul>
		<?php
		$items = cdfs_get_account_menu_items();
		foreach ( cdfs_get_account_menu_items() as $endpoint => $label ) :
			?>
			<li class="<?php echo cdfs_get_menu_item_classes( $endpoint ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>">
				<a href="<?php echo esc_url( cdfs_get_my_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
			</li>
		<?php endforeach; ?>
		<li class="cars-available">
			<span>
			<?php echo $car_dealer_options['cdfs_cars_available_lbl'] . ': ' . $cdfs_get_post_limits; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>
			</span>
		</li>
	</ul>
</nav>

<?php do_action( 'cdfs_after_navigation' ); ?>
