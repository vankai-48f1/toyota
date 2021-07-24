<?php
/**
 * Adds Cardealer Helpert Widget Tag Cloud.
 *
 * @package car-dealer-helper/functions
 * @version 1.0.0
 */

/**
 * Cardealer Helpert Widget Tag Cloud.
 */
class CarDealer_Helper_Widget_Tag_Cloud extends WP_Widget_Tag_Cloud {

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$current_taxonomy = $this->_get_current_taxonomy( $instance );
		if ( ! empty( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			if ( 'post_tag' === $current_taxonomy ) {
				$title = esc_html__( 'Tags', 'cardealer-helper' );
			} else {
				$tax   = get_taxonomy( $current_taxonomy );
				$title = $tax->labels->name;
			}
		}

		/**
		 * Filter the taxonomy used in the Tag Cloud widget.
		 *
		 * @since 2.8.0
		 * @since 3.0.0 Added taxonomy drop-down.
		 *
		 * @see wp_tag_cloud()
		 *
		 * @param array $current_taxonomy The taxonomy to use in the tag cloud. Default 'tags'.
		 */
		$tag_cloud = wp_tag_cloud(
			apply_filters(
				'widget_tag_cloud_args',
				array(
					'taxonomy' => $current_taxonomy,
					'echo'     => false,
					'format'   => 'array',
				)
			)
		);

		if ( empty( $tag_cloud ) ) {
			return;
		}

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		echo $args['before_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		}

		echo '<div class="tagcloud tags">';

		echo '<ul>';
		foreach ( $tag_cloud as $tag ) {
			echo '<li>' . $tag . '</li>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE

		}
		echo '</ul>';
		echo "</div>\n";
		echo $args['after_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
	}
}
