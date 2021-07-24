<?php
/**
 * CDHL Widget Herlper class.
 *
 * @class CDHL_Vehicle_Make_Logos_Generator
 * @package CarDealer Helper\Widgets Helper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CDHL_Vehicle_Make_Logos_Generator
 *
 * @package CarDealer Helper\Widgets Helper
 * @version  1.9.0
 */
class CDHL_Vehicle_Make_Logos_Generator {

	/**
	 * Curretn car make.
	 *
	 * @var string
	 */
	public $current_make = '';

	/**
	 * Constructor.
	 */
	public function __construct() {
		if ( isset( $_REQUEST['car_make'] ) && ! empty( $_REQUEST['car_make'] ) ) {
			$this->current_make = sanitize_text_field( wp_unslash( $_REQUEST['car_make'] ) );
		}
	}

	/**
	 * Generate make logo content.
	 *
	 * @param string $make Make.
	 */
	public function set_current_make( $make ) {
		if ( isset( $make ) && ! empty( $make ) ) {
			$this->current_make = $make;
		}
	}

	/**
	 * Get list of makes.
	 */
	public function get_makes() {
		$car_makes = cdhl_get_term_data_by_taxonomy( 'car_make' );

		return $car_makes;
	}

	/**
	 * Generate make logo content.
	 *
	 * @param array $instance Saved values from database.
	 * @return void
	 */
	public function generate_makes( $instance ) {
		$show_title    = ( isset( $instance['show_title'] ) && '' !== $instance['show_title'] ) ? filter_var( $instance['show_title'], FILTER_VALIDATE_BOOLEAN ) : false;
		$scrollbar     = ( isset( $instance['scrollbar'] ) && '' !== $instance['scrollbar'] ) ? filter_var( $instance['scrollbar'], FILTER_VALIDATE_BOOLEAN ) : false;
		$max_height    = ( isset( $instance['max_height'] ) && '' !== $instance['max_height'] ) ? $instance['max_height'] : 200;
		$show_count    = ( isset( $instance['show_count'] ) && '' !== $instance['show_count'] ) ? filter_var( $instance['show_count'], FILTER_VALIDATE_BOOLEAN ) : false;
		$include_makes = ( isset( $instance['include_makes'] ) && '' !== $instance['include_makes'] ) ? $instance['include_makes'] : array();

		$car_makes = $this->get_makes();

		if ( is_array( $include_makes ) && ! empty( $include_makes ) ) {
			$car_makes = array_filter( $car_makes, function( $key ) use ( $include_makes ) {
				return in_array( $key, $include_makes );
			}, ARRAY_FILTER_USE_KEY );
		}

		// Get vehicle page link.
		$car_url = $this->get_vehicle_archive_page_url();

		$is_vehicle_archive_page = $this->is_vehicle_archive_page();

		$cardealer_make_logos_class = array( 'cardealer-make-logos' );
		$cardealer_make_logos_style = array();

		if ( $scrollbar ) {
			$cardealer_make_logos_class[] = 'cardealer-make-logos-scrollable';
			$cardealer_make_logos_style[] = "max-height:{$max_height}px;";
		}
		$cardealer_make_logos_style = implode( ' ', $cardealer_make_logos_style );

		if (
			( wp_doing_ajax() && ( isset( $instance['is_vehicle_archive_page'] ) && (bool) $instance['is_vehicle_archive_page'] ) )
			|| ( ! wp_doing_ajax() && $is_vehicle_archive_page )
		) {
			$cardealer_make_logos_class[] = 'vehicle-archive-page';
		}
		?>
		<div class="<?php cdhl_class_builder( $cardealer_make_logos_class ); ?>" style="<?php echo esc_attr( $cardealer_make_logos_style ); ?>">
			<div class="row">
				<?php
				foreach ( $car_makes as $make_slug => $make_data ) {
					$make_url = add_query_arg(
						array(
							'car_make' => $make_slug,
						),
						$car_url
					);

					$cardealer_make_logo_class = array(
						'cardealer-make-logo',
						'col-sm-6',
						'col-xs-6',
					);

					if ( isset( $_GET['car_make'] ) && $make_slug === $_GET['car_make'] ) {
						$cardealer_make_logo_class[] = 'active';
					}
					?>
					<div class="<?php cdhl_class_builder( $cardealer_make_logo_class ); ?>">
						<a href="<?php echo esc_url( $make_url ); ?>" title="<?php echo esc_attr( $make_data['name'] ); ?>" data-tax_name="car_make" data-tax_slug="<?php echo esc_attr( $make_slug ); ?>">
							<div class="search-logo-box">
								<img class="img-responsive center-block" src="<?php echo esc_url( is_array( $make_data['logo_img'] ) ? $make_data['logo_img'][0] : $make_data['logo_img'] ); ?>" alt="" width="150" height="150">
								<?php
								if ( $show_title ) {
									?>
									<strong><?php echo esc_html( $make_data['name'] ); ?></strong>
									<?php
								}
								if ( $show_count ) {
									?>
									<span><?php echo esc_html( $make_data['posts'] ); ?></span>
									<?php
								}
								?>
							</div>
						</a>
					</div>
					<?php
				}
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Get cat archive page.
	 *
	 * @return string
	 */
	public function get_vehicle_archive_page_url() {
		// Get vehicle page link.
		global $car_dealer_options;

		$car_page_url = get_post_type_archive_link( 'cars' );
		$car_page_id  = ( isset( $car_dealer_options['cars_inventory_page'] ) && ! empty( $car_dealer_options['cars_inventory_page'] ) ) ? $car_dealer_options['cars_inventory_page'] : '';

		if ( $car_page_id && get_post( $car_page_id ) ) {
			$car_page_id  = cdhl_get_language_page_id( $car_page_id ); // get translated page id if.
			$car_page_url = get_permalink( $car_page_id );
		}

		return $car_page_url;
	}

	/**
	 * Check whether vehicle archive page.
	 *
	 * @return boolean
	 */
	public function is_vehicle_archive_page() {
		global $wp_query;

		return ( is_post_type_archive( 'cars' ) && ( isset( $wp_query->query ) && isset( $wp_query->query['post_type'] ) && 'cars' === $wp_query->query['post_type'] ) );
	}
}
