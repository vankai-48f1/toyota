<?php

/**
 * Template Name: Đăng Ký HDSD
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
               <div class="col-xs-12 col-sm-12 col-lg-8 mg-auto">
                  <div class="form-register form-register-course mg-t-2 mg-bt-3">
                     <?php the_content() ?>
                     <div class="message-form">
                        <?php echo get_field('message_form') ?>
                     </div>
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


