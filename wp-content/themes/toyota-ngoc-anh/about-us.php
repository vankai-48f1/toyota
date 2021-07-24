<?php

/**
 * Template Name: Giới thiệu công ty
 */
?>
<?php
get_header();
?>

<!-- Header -->
<?php get_template_part('template-parts/header'); ?>

<?php if (have_posts()) : ?>
   <?php while (have_posts()) : the_post(); ?>
      <div class="page-content pd-t-3">
         <div class="car-container">
            <div class="row">
               <div class="col-12 col-sm-10 col-md-9 col-lg-8 mg-auto">
                  <?php the_content() ?>
               </div>
            </div>

            <div class="company-location mg-t-3 mg-bt-3">
               <iframe src="https://maps.google.com/maps?q=<?php the_field('coordinate') ?>&output=embed" width="600" height="450" frameborder="0" style="border:0"></iframe>
            </div>
         </div>
      </div>
   <?php endwhile; ?>
<?php endif; ?>

<?php
get_footer();
?>


