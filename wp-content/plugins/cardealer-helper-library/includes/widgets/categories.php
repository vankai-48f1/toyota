<?php
/**
 * Adds Cardealer Helpert Widget Categories.
 *
 * @package car-dealer-helper/functions
 * @version 1.0.0
 */

/**
 * Cardealer Helpert Widget Categories.
 */
class CarDealer_Helper_Widget_Categories extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'                   => 'widget_categories',
			'description'                 => esc_html__( 'A list or dropdown of categories.', 'cardealer-helper' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'cardealer_categories', esc_html__( 'Car Dealer - Categories', 'cardealer-helper' ), $widget_ops );
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		static $first_dropdown = true;

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? esc_html__( 'Categories', 'cardealer-helper' ) : $instance['title'], $instance, $this->id_base );

		echo $args['before_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		}

		$cat_args = array(
			'orderby'      => 'name',
			'show_count'   => false,
			'hierarchical' => true,
			'walker'       => new CarDealer_Helper_Widget_Categories_Walker,
		);

		?>
		<div class="widget-menu cat-menu">
			<ul>
			<?php
			$cat_args['title_li'] = '';

			/**
			 * Filter the arguments for the Categories widget.
			 *
			 * @since 2.8.0
			 *
			 * @param array $cat_args An array of Categories widget options.
			 */
			wp_list_categories( apply_filters( 'widget_categories_args', $cat_args ) );

			?>
			</ul>
		</div>
		<?php

		echo $args['after_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );

		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		// Defaults.
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title    = sanitize_text_field( $instance['title'] );
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>"><?php esc_html_e( 'Title:', 'cardealer-helper' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
		<?php
	}
}

/**
 * Cardealer Helpert Widget Categories Walker.
 */
class CarDealer_Helper_Widget_Categories_Walker extends Walker_Category {
	/**
	 * Start Level.
	 *
	 * @param int   $output .
	 * @param int   $depth .
	 * @param array $args .
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( 'list' !== (string) $args['style'] ) {
			return;
		}

		$indent  = str_repeat( "\t", $depth );
		$output .= "$indent<ul class='cat-sub-menu'>\n";
	}

	/**
	 * End Level.
	 *
	 * @param string $output .
	 * @param string $category .
	 * @param int    $depth .
	 * @param array  $args .
	 * @param int    $id .
	 */
	function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
		extract( $args );

		/** This filter is documented in wp-includes/category-template.php */
		$cat_name = apply_filters(
			'list_cats',
			esc_attr( $category->name ),
			$category
		);

		// Don't generate an element if the category name is empty.
		if ( ! $cat_name ) {
			return;
		}

		// Check if category has children.
		$term_children = get_term_children( $category->term_id, $category->taxonomy );
		$aclass        = '';
		if ( count( $term_children ) > 0 ) {
			$aclass .= ' parent ';
		} else {
			$aclass = '';
		}

		$link = '<a class="' . $aclass . '" href="' . esc_url( get_term_link( $category ) ) . '" ';

		if ( $args['use_desc_for_title'] && ! empty( $category->description ) ) {
			/**
			 * Filter the category description for display.
			 *
			 * @since 1.2.0
			 *
			 * @param string $description Category description.
			 * @param object $category    Category object.
			 */
			$link .= 'title="' . esc_attr( strip_tags( apply_filters( 'category_description', $category->description, $category ) ) ) . '"';
		}

		$link .= '>';
		$link .= '<span class="category-title">';
		$link .= $cat_name;

		if ( count( $term_children ) > 0 ) {
			$link .= '<i class="fas fa-angle-right category-open-close-icon"></i>';
		}
		$link .= '</span>';
		$link .= '</a>';

		if ( ! empty( $args['feed_image'] ) || ! empty( $args['feed'] ) ) {
			$link .= ' ';

			if ( empty( $args['feed_image'] ) ) {
				$link .= '(';
			}

			$link .= '<a href="' . esc_url( get_term_feed_link( $category->term_id, $category->taxonomy, $args['feed_type'] ) ) . '"';

			if ( empty( $args['feed'] ) ) {
				/* translators: $s: Feed for all posts filed under */
				$alt = ' alt="' . sprintf( esc_html__( 'Feed for all posts filed under %s', 'cardealer-helper' ), $cat_name ) . '"';
			} else {
				$alt   = ' alt="' . $args['feed'] . '"';
				$name  = $args['feed'];
				$link .= empty( $args['title'] ) ? '' : $args['title'];
			}

			$link .= '>';

			if ( empty( $args['feed_image'] ) ) {
				$link .= $name;
			} else {
				$link .= "<img src='" . esc_url( $args['feed_image'] ) . "'$alt" . ' />';
			}
			$link .= '</a>';

			if ( empty( $args['feed_image'] ) ) {
				$link .= ')';
			}
		}

		if ( ! empty( $args['show_count'] ) ) {
			$link .= ' (' . number_format_i18n( $category->count ) . ')';
		}
		if ( 'list' === (string) $args['style'] ) {
			$output     .= "\t<li";
			$css_classes = array(
				'cat-item',
				'cat-item-' . $category->term_id,
			);

			if ( ! empty( $args['current_category'] ) ) {
				// 'current_category' can be an array, so we use `get_terms()`.
				$_current_terms = get_terms(
					$category->taxonomy,
					array(
						'include'    => $args['current_category'],
						'hide_empty' => false,
					)
				);

				foreach ( $_current_terms as $_current_term ) {
					if ( (string) $category->term_id === (string) $_current_term->term_id ) {
						$css_classes[] = 'current-cat';
					} elseif ( (string) $category->term_id === (string) $_current_term->parent ) {
						$css_classes[] = 'current-cat-parent';
					}
					while ( $_current_term->parent ) {
						if ( (string) $category->term_id === (string) $_current_term->parent ) {
							$css_classes[] = 'current-cat-ancestor';
							break;
						}
						$_current_term = get_term( $_current_term->parent, $category->taxonomy );
					}
				}
			}

			/**
			 * Filter the list of CSS classes to include with each category in the list.
			 *
			 * @since 4.2.0
			 *
			 * @see wp_list_categories()
			 *
			 * @param array  $css_classes An array of CSS classes to be applied to each list item.
			 * @param object $category    Category data object.
			 * @param int    $depth       Depth of page, used for padding.
			 * @param array  $args        An array of wp_list_categories() arguments.
			 */
			$css_classes = implode( ' ', apply_filters( 'category_css_class', $css_classes, $category, $depth, $args ) );

			$output .= ' class="' . $css_classes . '"';
			$output .= ">$link\n";
		} elseif ( isset( $args['separator'] ) ) {
			$output .= "\t$link" . $args['separator'] . "\n";
		} else {
			$output .= "\t$link<br />\n";
		}
	}
}
