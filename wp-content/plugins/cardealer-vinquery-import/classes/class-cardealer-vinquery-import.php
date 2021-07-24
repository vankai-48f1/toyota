<?php
/**
 * Sub menu class
 *
 * @author Potenza
 * @package Vinquery Import
 */

if ( ! class_exists( 'Cardealer_Vinquery_Import' ) ) {
	/**
	 * Vinquery import
	 */
	class Cardealer_Vinquery_Import extends CDVQI {

		/**
		 * Autoload method.
		 *
		 * @return void
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'cdvi_admin_vinquery_menu' ) );
			add_action( 'admin_notices', array( $this, 'cdvi_all_vinquery_errors' ) );
		}

		/**
		 * Register submenu.
		 *
		 * @return void
		 */
		public function cdvi_admin_vinquery_menu() {
			add_submenu_page( 'edit.php?post_type=cars', esc_html__( 'Vehicle VINquery Import', 'cdvqi-addon' ), esc_html__( 'Vehicle VINquery Import', 'cdvqi-addon' ), 'manage_options', 'cars-vinquery-import', array( &$this, 'cdvqi_cars_vinquery_import' ) );
		}

		/**
		 * Render submenu.
		 *
		 * @return void
		 */
		public function cdvqi_cars_vinquery_import() {
			global $car_dealer_options, $cars;

			$vin = ( isset( $_GET['vin'] ) && ! empty( $_GET['vin'] ) ? sanitize_text_field( wp_unslash( $_GET['vin'] ) ) : '' );
			if ( ! empty( $vin ) ) {
				$responce_body = $this->cdvi_get_vinquery_data( $vin );

				if ( 'FAILED' === $responce_body['Status'] ) {
					?>
					<div class="notice notice-error cars-vin-error">
						<p class="cars_text_error">
							<?php echo wp_kses_post( $responce_body['Message'] ); ?>
						</p>
						<p>
							<?php
							printf(
								wp_kses(
									/* translators: 1: Vehicle VINquery Import page link. */
									__( 'Return to <a href="%1$s">Vehicle VINquery Import</a> page.', 'cdvqi-addon' ),
									array(
										'a' => array(
											'href' => true,
										),
									)
								),
								esc_url( admin_url( 'edit.php?post_type=cars&page=cars-vinquery-import' ) )
							);
							?>
						</p>
					</div>
					<?php
					die;
				}
			}
			?>
			<div class="wrap cdhl_car_vin_import">
				<h2 style="display: inline-block;"><?php esc_html_e( 'Vehicles VIN Import', 'cdvqi-addon' ); ?></h2>
				<br />
				<?php
				/**
				 * When post mapped data for save
				 */
				if ( isset( $_POST['vin_import'] ) && ! empty( $_POST['vin_import'] ) ) {
					$msg = $this->cdvi_insert_vehicle_data( $responce_body );
					echo wp_kses_post( $msg );
				} else {
					/**
					 * Get VIN details and mapping area
					 */
					unset( $_GET['error'] );
					if ( ! empty( $vin ) && ! isset( $_GET['error'] ) ) {
						$vin_import_var = get_option( 'vin_query_import_mapping' );

						if ( ! empty( $vin_import_var ) ) {
							$vin_import_mapping = ( ! empty( $vin_import_var ) ? $vin_import_var : '' );
							$vin_import_mapping = $vin_import_mapping['vin_import'];
						} else {
							$vin_import_mapping = array();
						}
						?>
						<div class="cdhl-import-area-left">
							<div class="cdhl-area-title">
								<p><?php echo esc_html__( 'To import vehicles, drag and drop fields from the right column into the left column attributes and meta boxes. You can save current field mapping for future use. You can use saved field mapping to import vehicles again.', 'cdvqi-addon' ); ?></p><br>
								<h3 class="res-msg"></h3>
								<div class="cdhl-button-group">
									<button class="cdhl_save_current_mapping current_vin_mapping button cdhl_button-primary"><?php esc_html_e( 'Save current mapping', 'cdvqi-addon' ); ?></button>
									<button class="cdhl_submit_vin button button-primary" style="vertical-align: super;"><?php esc_html_e( 'Import Vehicles', 'cdvqi-addon' ); ?></button>
									<span class="cdhl-loader-img"></span>
								</div>
								<div class="clr"></div>
							</div>

							<form method="post" action="" name="cars_vin_import_form" id="cars_vin_import_form">
								<?php wp_nonce_field( 'cdvi_cars_vin_import', 'cdvqi_vinquery_import_nonce' ); ?>
								<div id="tabs">
									<ul>
										<li><a href="#tabs-1"><?php esc_html_e( 'Vehicle Title', 'cdvqi-addon' ); ?></a></li>
										<li><a href="#tabs-2"><?php esc_html_e( 'Attributes', 'cdvqi-addon' ); ?></a></li>
										<li><a href="#tabs-3"><?php esc_html_e( 'Vehicle Images', 'cdvqi-addon' ); ?></a></li>
										<li><a href="#tabs-4"><?php esc_html_e( 'Regular price', 'cdvqi-addon' ); ?></a></li>
										<li><a href="#tabs-5"><?php esc_html_e( 'Tax Label', 'cdvqi-addon' ); ?></a></li>
										<li><a href="#tabs-6"><?php esc_html_e( 'Fuel Efficiency', 'cdvqi-addon' ); ?></a></li>
										<li><a href="#tabs-7"><?php esc_html_e( 'PDF Brochure', 'cdvqi-addon' ); ?></a></li>
										<li><a href="#tabs-8"><?php esc_html_e( 'Video', 'cdvqi-addon' ); ?></a></li>
										<li><a href="#tabs-9"><?php esc_html_e( 'Vehicle Status', 'cdvqi-addon' ); ?></a></li>
										<li><a href="#tabs-10"><?php esc_html_e( 'Vehicle Overview', 'cdvqi-addon' ); ?></a></li>
										<li><a href="#tabs-11"><?php esc_html_e( 'Features & Options', 'cdvqi-addon' ); ?></a></li>
										<li><a href="#tabs-12"><?php esc_html_e( 'Technical Specifications', 'cdvqi-addon' ); ?></a></li>
										<li><a href="#tabs-13"><?php esc_html_e( 'General Information', 'cdvqi-addon' ); ?></a></li>
										<li><a href="#tabs-14"><?php esc_html_e( 'Vehicle Location', 'cdvqi-addon' ); ?></a></li>
										<li><a href="#tabs-15"><?php esc_html_e( 'Excerpt(Short content)', 'cdvqi-addon' ); ?></a></li>
									</ul>
									<div class="cdhl-form-group" id="tabs-1">
										<div class="cdhl_attributes">
											<label><?php esc_html_e( 'Vehicle Titles', 'cdvqi-addon' ); ?></label>
											<div class="cars_attributes">
												<ul class="cdhl_cars_attributes cdhl_form_data" data-limit="0" data-name="cars_title">
													<?php $this->cdvi_cars_vinquery_import_item( 'cars_title', $vin_import_mapping, $responce_body, 0 ); ?>
												</ul>
											</div>
										</div>
									</div>
									<div class="cdhl-form-group" id="tabs-2">
										<?php
										$cars_attributes = cardealer_get_all_taxonomy_with_terms();
										foreach ( $cars_attributes as $key => $value ) {
											$attr_safe_name = $value['slug'];
											if ( 'features-options' !== $key ) {
												?>
												<div class="cdhl_attributes">
													<label><?php echo esc_html( $value['label'] ); ?></label>
													<div class="cars_attributes_area">
														<ul class="cdhl_cars_attributes cdhl_form_data" data-limit="1" data-name="<?php echo esc_attr( $attr_safe_name ); ?>">
															<?php
															$this->cdvi_cars_vinquery_import_item( $key, $vin_import_mapping, $responce_body );
															?>
														</ul>
													</div>
												</div>
												<?php
											}
										}
										?>
									</div>

									<?php
									// extra spots.
									$extra_spots = array(
										'car_images'       => array( esc_html__( 'Vehicles Images', 'cdvqi-addon' ), 0 ),
										'regular_price'    => array( esc_html__( 'Regular price', 'cdvqi-addon' ), 1 ),
										'tax_label'        => array( esc_html__( 'Tax Label', 'cdvqi-addon' ), 1 ),
										'fuel_efficiency'  => array( esc_html__( 'Fuel Efficiency', 'cdvqi-addon' ), 1 ),
										'pdf_file'         => array( esc_html__( 'PDF Brochure', 'cdvqi-addon' ), 1 ),
										'video_link'       => array( esc_html__( 'Video Link', 'cdvqi-addon' ), 1 ),
										'car_status'       => array( esc_html__( 'Vehicle Status( sold/unsold )', 'cdvqi-addon' ), 1 ),
										'vehicle_overview' => array( esc_html__( 'Vehicle Overview', 'cdvqi-addon' ), 0 ),
										'features_options' => array( esc_html__( 'Features Options', 'cdvqi-addon' ), 0 ),
										'technical_specifications' => array( esc_html__( 'Technical Specifications', 'cdvqi-addon' ), 0 ),
										'general_information' => array( esc_html__( 'General Information', 'cdvqi-addon' ), 0 ),
										'vehicle_location' => array( esc_html__( 'Vehicle Location', 'cdvqi-addon' ), 1 ),
										'excerpt'          => array( esc_html__( 'Excerpt(Short content)', 'cdvqi-addon' ), 1 ),
									);
									$ef          = 3;
									foreach ( $extra_spots as $key => $option ) {

										if ( 'features_options' === $key ) {
											$taxonomy_name = get_taxonomy( 'car_features_options' );
											$slug          = $taxonomy_name->rewrite['slug'];
											$label         = $taxonomy_name->labels->menu_name;
											?>
											<div id="tabs-<?php echo esc_attr( $ef ); ?>">
												<div class="cdhl_attributes">
													<label><?php echo esc_html( $label ); ?></label>
													<div class="cars_attributes_area">
														<ul class="cdhl_cars_attributes cdhl_form_data" data-limit="<?php echo esc_attr( $option[1] ); ?>" data-name="<?php echo esc_attr( $key ); ?>">
															<?php $this->cdvi_cars_vinquery_import_item( $key, $vin_import_mapping, $responce_body, $option[1] ); ?>
														</ul>
													</div>
												</div>
											</div>
											<?php
										}

										if ( 'regular_price' === $key ) {
											?>
											<div id="tabs-<?php echo esc_attr( $ef ); ?>">
												<div class="cdhl_attributes">
													<label><?php esc_html_e( 'Regular Price', 'cdvqi-addon' ); ?></label>
													<div class="cars_attributes_area">
														<ul class="cdhl_cars_attributes cdhl_form_data" data-limit="<?php echo esc_attr( $option[1] ); ?>" data-name="regular_price">
															<?php $this->cdvi_cars_vinquery_import_item( 'regular_price', $vin_import_mapping, $responce_body, $option[1] ); ?>
														</ul>
													</div>
												</div>


												<div class="cdhl_attributes">
													<label><?php esc_html_e( 'Sale Price', 'cdvqi-addon' ); ?></label>
													<div class="cars_attributes_area">
														<ul class="cdhl_cars_attributes cdhl_form_data" data-limit="<?php echo esc_attr( $option[1] ); ?>" data-name="sale_price">
															<?php $this->cdvi_cars_vinquery_import_item( 'sale_price', $vin_import_mapping, $responce_body, $option[1] ); ?>
														</ul>
													</div>
												</div>

											</div>
											<?php
										}

										if ( 'fuel_efficiency' === $key ) {
											?>
											<div id="tabs-<?php echo esc_attr( $ef ); ?>">

												<div class="cdhl_attributes">
													<label><?php esc_html_e( 'City MPG', 'cdvqi-addon' ); ?></label>
													<div class="cars_attributes_area">
														<ul class="cdhl_cars_attributes cdhl_form_data" data-limit="<?php echo esc_attr( $option[1] ); ?>" data-name="city_mpg">
															<?php $this->cdvi_cars_vinquery_import_item( 'city_mpg', $vin_import_mapping, $responce_body, $option[1] ); ?>
														</ul>
													</div>
												</div>

												<div class="cdhl_attributes">
													<label><?php esc_html_e( 'Highway MPG', 'cdvqi-addon' ); ?></label>
													<div class="cars_attributes_area">
														<ul class="cdhl_cars_attributes cdhl_form_data" data-limit="<?php echo esc_attr( $option[1] ); ?>" data-name="highway_mpg">
															<?php $this->cdvi_cars_vinquery_import_item( 'highway_mpg', $vin_import_mapping, $responce_body, $option[1] ); ?>
														</ul>
													</div>
												</div>
											</div>
											<?php
										}

										if ( 'features_options' !== $key && 'regular_price' !== $key && 'fuel_efficiency' !== $key ) {
											?>
											<div id="tabs-<?php echo esc_attr( $ef ); ?>">
												<div class="cdhl_attributes">
													<label><?php echo esc_html( $option[0] ); ?></label>
													<div class="cars_attributes_area">
														<ul class="cdhl_cars_attributes cdhl_form_data" data-limit="<?php echo esc_attr( $option[1] ); ?>" data-name="<?php echo esc_attr( $key ); ?>">
															<?php $this->cdvi_cars_vinquery_import_item( $key, $vin_import_mapping, $responce_body, $option[1] ); ?>
														</ul>
													</div>
												</div>
											</div>
											<?php
										}
										$ef++;
									}
									?>
								</div>
							</form>

							<div class="cdhl-area-title cdvi-footer">
								<div class="cdhl-button-group">
									<button class="cdhl_save_current_mapping current_vin_mapping button cdhl_button-primary"><?php esc_html_e( 'Save current mapping', 'cdvqi-addon' ); ?></button>
									<button class="cdhl_submit_vin button button-primary" style="vertical-align: super;"><?php esc_html_e( 'Import Vehicles', 'cdvqi-addon' ); ?></button>
									<span class="cdhl-loader-img"></span>
								</div>
								<div class="clr"></div>
							</div>
						</div>
						<div class="cdhl-import-area-right">
							<h3><?php esc_html_e( 'API Result', 'cdvqi-addon' ); ?></h3>
							<ul id="cdhl_vin_items" class="cdhl_form_data ui-sortable">
								<?php
								if ( 'SUCCESS' === $responce_body['Status'] ) {
									foreach ( $responce_body as $key => $value ) {
										?>
										<li class='ui-state-default'>
											<?php echo esc_html( $key . ': ' . $value ); ?>
											<input type="hidden" name="" value="<?php echo esc_attr( $key . ':' . $value ); ?>" />
										</li>
										<?php
									}
								}
								?>
							</ul>
						</div>
						<?php
					} else {
						/**
						 * Pass VIN number here ( Enter vin number here )
						 */
						$cars_api_key = ( isset( $car_dealer_options['vinquery_api_key'] ) && ! empty( $car_dealer_options['vinquery_api_key'] ) ? $car_dealer_options['vinquery_api_key'] : '' );

						if ( ! empty( $cars_api_key ) ) {
							?>
							<div class="upload-plugin-pgs">
								<form method="GET" class="wp-upload-form" action="" name="import_url">
									<input type="hidden" name="post_type" value="cars">
									<input type="hidden" name="page" value="cars-vinquery-import">

									<label class="screen-reader-text" for="pluginzip"><?php esc_html_e( 'vehicle file', 'cdvqi-addon' ); ?></label>
									<input type="text" name="vin" placeholder="<?php esc_html_e( 'VIN #', 'cdvqi-addon' ); ?>" style="width: 60%;">
									<button onclick="jQuery(this).closest('form').submit()" class="button"><?php esc_html_e( 'Get vehicle details', 'cdvqi-addon' ); ?></button>
								</form>
							</div>
							<?php
						} else {
							$option_tab_url = car_dealer_get_options_tab_url( 'vinquery_api_key' );
							printf(
								wp_kses(
									__( 'The VINquery API key is not set. Click <a href="%s" target="_blank">here</a> to set the VINquery API key.', 'cdvqi-addon' ),
									array(
										'a' => array(
											'href'   => true,
											'target' => true,
										),
									)
								),
								esc_url( $option_tab_url )
							);
						}
					}
				}
				?>
			</div>
			<?php
		}

	}

}
new Cardealer_Vinquery_Import();
