<?php
/**
 * Adds Cardealer Helpert Widget Recent Posts.
 *
 * @package car-dealer-helper/functions
 * @version 1.0.0
 */

/**
 * Cardealer Helpert Widget Recent Posts.
 */
class CarDealer_Helper_Widget_Recent_Posts extends WP_Widget_Recent_Posts {

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : esc_html__( 'Recent Posts', 'cardealer-helper' );

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number ) {
			$number = 5;
		}
		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

		/**
		 * Filter the arguments for the Recent Posts widget.
		 *
		 * @since 3.4.0
		 *
		 * @see WP_Query::get_posts()
		 *
		 * @param array $args An array of arguments used to retrieve the recent posts.
		 */
		$r = new WP_Query(
			apply_filters(
				'widget_posts_args',
				array(
					'posts_per_page'      => $number,
					'no_found_rows'       => true,
					'post_status'         => 'publish',
					'ignore_sticky_posts' => true,
				)
			)
		);

		if ( $r->have_posts() ) :
			?>
			<?php

			echo $args['before_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE

			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			}

			while ( $r->have_posts() ) :
				$r->the_post();
				?>
				<div class="recent-post">
					<?php

					if ( has_post_thumbnail() ) {
						?>
						<div class="recent-post-image">
							<?php
							the_post_thumbnail( 'cardealer-50x50', array( 'class' => 'img-responsive' ) );
							?>
						</div>
						<?php
					}
					?>
					<div class="recent-post-info">
						<a href="<?php esc_url( the_permalink() ); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a>
						<?php if ( $show_date ) : ?>
							<span class="post-date"><i class="fas fa-calendar-alt"></i><?php echo get_the_date(); ?></span>
						<?php endif; ?>
					</div>
				</div>
				<?php

			endwhile;

			echo $args['after_widget'];// phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE

			// Reset the global $the_post as this query will have stomped on it.
			wp_reset_postdata();

		endif;
	}
}
