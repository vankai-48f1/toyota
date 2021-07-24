<?php // phpcs:ignore WPThemeReview.Templates.ReservedFileNamePrefix.ReservedTemplatePrefixFound
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

?>
<!-- Author Info -->
<div class="author-info port-post clearfix">
	<div class="author-avatar port-post-photo">
		<?php
		$author_bio_avatar_size = apply_filters( 'cardealer_author_bio_avatar_size', 170 );
		echo get_avatar( get_the_author_meta( 'user_email' ), $author_bio_avatar_size );
		?>
	</div> <!-- .author-avatar -->
	<div class="author-details port-post-info">
		<?php
		$author_link_title = sprintf(
			/* translators: 1: Author Name */
			esc_html__( 'View all posts by %s', 'cardealer' ),
			esc_html( get_the_author() )
		);
		?>
		<h3 class="text-blue"><span><?php esc_html_e( 'Posted by:', 'cardealer' ); ?> </span><a title="<?php echo esc_attr( $author_link_title ); ?>" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author"><?php echo get_the_author(); ?></a></h3>
		<?php
		$author_id  = get_the_author_meta( 'ID' );
		$user_links = array(
			array(
				'profile' => 'facebook',
				'icon'    => 'fab fa-facebook-f',
			),
			array(
				'profile' => 'twitter',
				'icon'    => 'fab fa-twitter',
			),
			array(
				'profile' => 'linkedin',
				'icon'    => 'fab fa-linkedin-in',
			),
			array(
				'profile' => 'pinterest',
				'icon'    => 'fab fa-pinterest-p',
			),
			array(
				'profile' => 'instagram',
				'icon'    => 'fab fa-instagram',
			),
		);
		?>
		<div class="author-links port-post-social pull-right">

			<?php
			$user_links_count   = 0;
			$user_links_content = '';
			foreach ( $user_links as $user_link ) {
				$profile     = $user_link['profile'];
				$icon        = $user_link['icon'];
				$profile_url = get_user_meta( $author_id, $profile, true );
				if ( $profile_url ) {
					$user_links_count++;
					$user_links_content .= '<a href="' . esc_url( $profile_url ) . '"><i class="' . esc_attr( $icon ) . '"></i></a>';
				}
			}
			if ( $user_links_count > 0 ) {
				?>
				<strong><?php esc_html_e( 'Follow on', 'cardealer' ); ?>:</strong>
				<?php
				if ( ! empty( $user_links_content ) ) {
					echo sprintf(
						wp_kses(
							$user_links_content,
							array(
								'a' => array(
									'href' => array(),
								),
								'i' => array(
									'class' => array(),
								),
							)
						)
					);
				}
			}
			?>

		</div><!-- .author-links -->
		<div class="author-description">
			<p><?php the_author_meta( 'description' ); ?></p>
		</div><!-- .author-description -->
	</div><!-- .author-details -->
</div><!-- .author-info    -->
