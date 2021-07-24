<?php
/**
 * Vehicle Categories Widget
 *
 * @package car-dealer-helper/widgets
 * @version 1.9.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Vehicle Categories Widget class.
 *
 * @extends CDHL_Widget
 */
class CDHL_Widget_Vehicle_Categories extends CDHL_Widget {

	/**
	 * Category ancestors.
	 *
	 * @var array
	 */
	public $cat_ancestors;

	/**
	 * Current Category.
	 *
	 * @var bool
	 */
	public $current_cat;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->widget_id          = 'cdhl_vehicle_categories';
		$this->widget_name        = esc_html__( 'Car Dealer - Vehicle Categories', 'cardealer-helper' );
		$this->widget_description = esc_html__( 'A list of vehicle categories.', 'cardealer-helper' );
		$this->widget_cssclass    = 'widget-vehicle-categories';
		$this->settings           = array(
			'title'              => array(
				'type'  => 'text',
				'std'   => esc_html__( 'Vehicle categories', 'cardealer-helper' ),
				'label' => esc_html__( 'Title:', 'cardealer-helper' ),
			),
			'orderby'            => array(
				'type'        => 'select',
				'std'         => 'name',
				'label'       => esc_html__( 'Order by', 'cardealer-helper' ),
				'options'     => array(
					'order' => esc_html__( 'Category order', 'cardealer-helper' ),
					'name'  => esc_html__( 'Name', 'cardealer-helper' ),
				),
				'is_disabled' => true,
			),
			'dropdown'           => array(
				'type'        => 'checkbox',
				'std'         => 0,
				'label'       => esc_html__( 'Show as dropdown?', 'cardealer-helper' ),
				'is_disabled' => true,
			),
			'count'              => array(
				'type'        => 'checkbox',
				'std'         => 0,
				'label'       => esc_html__( 'Show product counts?', 'cardealer-helper' ),
				'is_disabled' => false,
			),
			'hierarchical'       => array(
				'type'  => 'checkbox',
				'std'   => 1,
				'label' => esc_html__( 'Show hierarchy?', 'cardealer-helper' ),
			),
			'show_children_only' => array(
				'type'        => 'checkbox',
				'std'         => 0,
				'label'       => esc_html__( 'Only show children of the current category', 'cardealer-helper' ),
				'is_disabled' => true,
			),
			'hide_empty'         => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => esc_html__( 'Hide empty categories?', 'cardealer-helper' ),
			),
			'max_depth'          => array(
				'type'  => 'text',
				'std'   => '',
				'label' => esc_html__( 'Maximum depth:', 'cardealer-helper' ),
			),
			'scrollbar'          => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => esc_html__( 'Show Scrollbar?', 'cardealer-helper' ),
			),
			'max_height'         => array(
				'type'  => 'number',
				'min'   => '100',
				'max'   => '5000',
				'step'  => '1',
				'std'   => '200',
				'label' => esc_html__( 'Maximum height:', 'cardealer-helper' ),
				'desc'  => esc_html__( 'Set maximum height to showing scrollbar. Min: 100', 'cardealer-helper' ),
			),
		);

		parent::__construct();
	}

	/**
	 * Output widget.
	 *
	 * @see WP_Widget
	 * @param array $args     Widget arguments.
	 * @param array $instance Widget instance.
	 */
	public function widget( $args, $instance ) {
		global $wp_query, $post;

		$count              = isset( $instance['count'] ) ? $instance['count'] : $this->settings['count']['std'];
		$hierarchical       = isset( $instance['hierarchical'] ) ? $instance['hierarchical'] : $this->settings['hierarchical']['std'];
		$show_children_only = isset( $instance['show_children_only'] ) ? $instance['show_children_only'] : $this->settings['show_children_only']['std'];
		$dropdown           = isset( $instance['dropdown'] ) ? $instance['dropdown'] : $this->settings['dropdown']['std'];
		$orderby            = isset( $instance['orderby'] ) ? $instance['orderby'] : $this->settings['orderby']['std'];
		$hide_empty         = isset( $instance['hide_empty'] ) ? $instance['hide_empty'] : $this->settings['hide_empty']['std'];
		$max_depth          = absint( isset( $instance['max_depth'] ) ? $instance['max_depth'] : $this->settings['max_depth']['std'] );
		$scrollbar          = isset( $instance['scrollbar'] ) ? $instance['scrollbar'] : $this->settings['scrollbar']['std'];
		$max_height         = absint( isset( $instance['max_height'] ) ? $instance['max_height'] : $this->settings['max_height']['std'] );

		$list_args = array(
			'show_count'   => $count,
			'hierarchical' => $hierarchical,
			'taxonomy'     => 'vehicle_cat',
			'hide_empty'   => $hide_empty,
			'menu_order'   => false,
			'depth'        => $max_depth,
		);

		$dropdown_args = array(
			'hide_empty' => $hide_empty,
			'depth'      => $max_depth,
		);

		if ( 'order' === $orderby ) {
			$list_args['orderby']      = 'meta_value_num';
			$list_args['meta_key']     = 'order';
			$dropdown_args['orderby']  = 'meta_value_num';
			$dropdown_args['meta_key'] = 'order';
		}

		$this->current_cat   = false;
		$this->cat_ancestors = array();

		if ( is_tax( 'vehicle_cat' ) ) {
			$this->current_cat   = $wp_query->queried_object;
			$this->cat_ancestors = get_ancestors( $this->current_cat->term_id, 'vehicle_cat' );

		} elseif ( is_singular( 'product' ) ) {
			$terms = wc_get_product_terms(
				$post->ID,
				'vehicle_cat',
				apply_filters(
					'cdhl_vehicle_categories_widget_product_terms_args',
					array(
						'orderby' => 'parent',
						'order'   => 'DESC',
					)
				)
			);

			if ( $terms ) {
				$main_term           = apply_filters( 'cdhl_vehicle_categories_widget_main_term', $terms[0], $terms );
				$this->current_cat   = $main_term;
				$this->cat_ancestors = get_ancestors( $main_term->term_id, 'vehicle_cat' );
			}
		}

		// Show Siblings and Children Only.
		if ( $show_children_only && $this->current_cat ) {
			if ( $hierarchical ) {
				$include = array_merge(
					$this->cat_ancestors,
					array( $this->current_cat->term_id ),
					get_terms(
						'vehicle_cat',
						array(
							'fields'       => 'ids',
							'parent'       => 0,
							'hierarchical' => true,
							'hide_empty'   => false,
						)
					),
					get_terms(
						'vehicle_cat',
						array(
							'fields'       => 'ids',
							'parent'       => $this->current_cat->term_id,
							'hierarchical' => true,
							'hide_empty'   => false,
						)
					)
				);
				// Gather siblings of ancestors.
				if ( $this->cat_ancestors ) {
					foreach ( $this->cat_ancestors as $ancestor ) {
						$include = array_merge(
							$include,
							get_terms(
								'vehicle_cat',
								array(
									'fields'       => 'ids',
									'parent'       => $ancestor,
									'hierarchical' => false,
									'hide_empty'   => false,
								)
							)
						);
					}
				}
			} else {
				// Direct children.
				$include = get_terms(
					'vehicle_cat',
					array(
						'fields'       => 'ids',
						'parent'       => $this->current_cat->term_id,
						'hierarchical' => true,
						'hide_empty'   => false,
					)
				);
			}

			$list_args['include']     = implode( ',', $include );
			$dropdown_args['include'] = $list_args['include'];

			if ( empty( $include ) ) {
				return;
			}
		} elseif ( $show_children_only ) {
			$dropdown_args['depth']        = 1;
			$dropdown_args['child_of']     = 0;
			$dropdown_args['hierarchical'] = 1;
			$list_args['depth']            = 1;
			$list_args['child_of']         = 0;
			$list_args['hierarchical']     = 1;
		}

		$this->widget_start( $args, $instance );

		if ( $dropdown ) {
			include_once trailingslashit( CDHL_PATH ) . 'includes/walkers/class-cdhl-vehicle-cat-dropdown-walker.php';

			$dropdown_args = wp_parse_args(
				$dropdown_args,
				array(
					'id'                 => "{$this->id_base}-dropdown-{$this->number}",
					'show_count'         => $count,
					'hierarchical'       => $hierarchical,
					'show_uncategorized' => 0,
					'selected'           => $this->current_cat ? $this->current_cat->slug : '',
					'walker'             => new CDHL_Vehicle_Cat_Dropdown_Walker(),
				)
			);

			$this->vehicle_dropdown_categories( apply_filters( 'cdhl_vehicle_categories_widget_dropdown_args', $dropdown_args ) );
		} else {
			include_once trailingslashit( CDHL_PATH ) . 'includes/walkers/class-cdhl-vehicle-cat-list-walker.php';

			$list_args['walker']                     = new CDHL_Vehicle_Cat_List_Walker();
			$list_args['title_li']                   = '';
			$list_args['pad_counts']                 = 1;
			$list_args['show_option_none']           = esc_html__( 'No vehicle categories exist.', 'cardealer-helper' );
			$list_args['current_category']           = ( $this->current_cat ) ? $this->current_cat->term_id : '';
			$list_args['current_category_ancestors'] = $this->cat_ancestors;
			$list_args['max_depth']                  = $max_depth;

			$categories_list_class = array( 'vehicle-categories-list' );
			$categories_list_style = array();
			if ( $scrollbar ) {
				$categories_list_class[] = 'vehicle-categories-list-scrollable';
				$categories_list_style[] = "max-height:{$max_height}px;";
			}
			$categories_list_style = implode( ' ', $categories_list_style );
			?>
			<ul class="<?php cdhl_class_builder( $categories_list_class ); ?>" style="<?php echo esc_attr( $categories_list_style ); ?>">
				<?php wp_list_categories( apply_filters( 'cdhl_vehicle_categories_widget_args', $list_args ) ); ?>
			</ul>
			<?php
		}

		$this->widget_end( $args );
	}
}
