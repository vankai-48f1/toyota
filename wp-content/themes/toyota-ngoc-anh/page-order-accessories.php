<?php

/**
 * Template Name: Đặt hàng phụ kiện
 */
?>
<?php
get_header();
?>

<!-- Header -->
<?php get_template_part('template-parts/banner'); ?>

<?php if (have_posts()) : ?>
   <?php while (have_posts()) : the_post(); ?>
      <div class="page-content bg-white-light">
         <div class="car-container">
            <div class="row">
               <div class="col-lg-7 col-md-8 mg-auto">
                  <div class="form-register form-service mg-bt-4">
                     <div class="title-form-top">
                        <h2><?php the_title() ?></h2>
                     </div>
                     <?php the_content() ?>
                     <!-- <div class="message-form">
                        < ?php echo get_field('message_form') ?>
                     </div> -->
                  </div>
               </div>
            </div>
         </div>
      </div>
   <?php endwhile; ?>
<?php endif; ?>

<?php
get_footer();
?>


