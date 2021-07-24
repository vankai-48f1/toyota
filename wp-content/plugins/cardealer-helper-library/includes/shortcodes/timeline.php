<?php
/**
 * CarDealer Visual Composer testimonials Shortcode
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package car-dealer-helper/functions
 */

add_shortcode( 'cd_timeline', 'cdhl_timeline_shortcode' );

/**
 * Shortcode HTML.
 *
 * @param array $atts .
 */
function cdhl_timeline_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'list'       => '',
			'css'        => '',
			'element_id' => uniqid( 'cd_timeline_' ),
		),
		$atts
	);
	extract( $atts );

	$list_items = vc_param_group_parse_atts( $list );

	if ( ! is_array( $list_items ) || empty( $list_items ) || empty( $list_items[0] ) ) {
		return null;
	}

	// Sort List items by date.
	cdhl_array_sort_by_column( $list_items, 'timeline_date' );

	$css = $atts['css'];

	$custom_class = vc_shortcode_custom_css_class( $css, ' ' );

	$element_classes = array(
		'cd_timeline',
		'cd_timeline-vertical',
		'our-history',
		$custom_class,
	);
	$element_classes = implode( ' ', array_filter( array_unique( $element_classes ) ) );
	ob_start();
	?>
	<div id="cd_timeline-<?php echo esc_attr( $element_id ); ?>" class="<?php echo esc_attr( $element_classes ); ?>">
		<ul class="timeline list-style-none">
			<?php
			$timeline_sr = 1;
			foreach ( $list_items as $list_item ) {
				$item_classes   = array();
				$item_classes[] = 'timeline-item';
				$item_classes[] = $timeline_sr % 2 ? 'timeline-item-odd' : 'timeline-item-even timeline-inverted';
				$item_classes   = implode( ' ', array_filter( array_unique( $item_classes ) ) );
				if ( ! empty( $list_item ) ) {
					?>
				<li class="<?php echo esc_attr( $item_classes ); ?>">
					<div class="timeline-badge"><h4><?php echo esc_html( $timeline_sr ); ?></h4></div>
					<div class="timeline-panel">
						<div class="timeline-heading">
							<?php
							if ( isset( $list_item['timeline_title'] ) ) {
								?>
								<h5><?php echo esc_html( $list_item['timeline_title'] ); ?></h5>
								<?php
							}
							?>
						</div>
						<?php
						if ( isset( $list_item['timeline_description'] ) ) {
							?>
							<div class="timeline-body">
								<p><?php echo esc_html( $list_item['timeline_description'] ); ?></p>
							</div>
							<?php
						}
						?>
					</div>
				</li>
					<?php
				}
				$timeline_sr++;
			}
			?>
		</ul>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Shortcode mapping.
 *
 * @return void
 */
function cdhl_timeline_shortcode_vc_map() {
	if ( function_exists( 'vc_map' ) ) {
		global $vc_gitem_add_link_param;
		vc_map(
			array(
				'name'                    => esc_html__( 'Potenza Timeline', 'cardealer-helper' ),
				'description'             => esc_html__( 'Potenza Timeline', 'cardealer-helper' ),
				'base'                    => 'cd_timeline',
				'class'                   => 'cd_timeline',
				'controls'                => 'full',
				'icon'                    => cardealer_vc_shortcode_icon( 'cd_timeline' ),
				'category'                => esc_html__( 'Potenza', 'cardealer-helper' ),
				'show_settings_on_create' => true,
				'params'                  => array(
					array(
						'type'       => 'param_group',
						'value'      => '',
						'param_name' => 'list',
						'heading'    => esc_html__( 'Timeline Data', 'cardealer-helper' ),
						'params'     => array(
							array(
								'type'        => 'textfield',
								'class'       => '',
								'heading'     => esc_html__( 'Title', 'cardealer-helper' ),
								'param_name'  => 'timeline_title',
								'admin_label' => true,
							),
							array(
								'type'       => 'textarea',
								'class'      => '',
								'heading'    => esc_html__( 'Description', 'cardealer-helper' ),
								'param_name' => 'timeline_description',
							),
						),
						'callbacks'  => array(
							'after_add' => 'vcChartParamAfterAddCallback',
						),
					),
				),
			)
		);
	}
}
add_action( 'vc_before_init', 'cdhl_timeline_shortcode_vc_map' );
