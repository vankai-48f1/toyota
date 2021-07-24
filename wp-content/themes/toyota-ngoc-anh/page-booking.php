<?php

/**
 * Template Name: Đặt hẹn dịch vụ
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
                     <?php the_content() ?>
                     <div class="message-form">
                        <?php echo get_field('message_form') ?>
                     </div>

                     <?php
                     // Branch list 
                     if (have_rows('branch_group', 'option')) : ?>

                        <ul class="na-branch-list">
                           <?php while (have_rows('branch_group', 'option')) : the_row();

                              // Load sub field value.
                              $name_branch = get_sub_field('name_branch');
                              $location_branch = get_sub_field('location_branch'); ?>

                              <li>
                                 <span class="name_branch"><?php echo $name_branch; ?></span>
                                 <span class="location_branch">
                                    <?php echo $location_branch ?>
                                 </span>
                              </li>
                           <?php endwhile; ?>
                        </ul>
                     <?php else :
                     // Do something...
                     endif; ?>
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


