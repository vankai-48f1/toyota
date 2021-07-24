<?php
/**
 * Template Name: CarDealer Front Submission
 * Description: A page template that display CarDealer Front Submission Plugin pages.
 *
 * @author Potenza Global Solutions
 * @package CDFS
 */

get_header();

// Change the My Accout page title.
wp_enqueue_style( 'cdhl-jquery-confirm' );
wp_enqueue_script( 'cdfs-google-location-picker-api' );
wp_enqueue_script( 'cdfs-google-location-picker' );
?>

<?php get_template_part( 'template-parts/content', 'intro' ); ?>

<section class="content-wrapper-vc-enabled">

	<div class="container">
		<div class="row without-sidebar">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div id="primary" class="site-content">
					<div id="content" role="main">
						<div class="entry-content">
							<div class="cdfs">
								<?php cdfs_get_template( 'cars/cars-add.php' ); ?>
							</div>
						</div>						
					</div>					
				</div>
			</div>
		</div>
	</div>

</section>

<?php get_footer(); ?>
