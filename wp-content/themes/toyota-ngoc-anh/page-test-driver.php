<?php

/**
 * Template Name: Đăng Ký Lái Thử
 */
?>
<?php
get_header();
?>

<!-- Header -->
<?php get_template_part('template-parts/header'); ?>

<?php if (have_posts()) : ?>
   <?php while (have_posts()) : the_post(); ?>
      <div class="pd-t-3">
         <div class="car-container">
            <div class="row">
               <div class="col-xs-12 col-sm-12 col-md-10 col-lg-7 mg-auto">
                  <div class="form-register form-test-driver mg-t-2 mg-bt-3">
                     <?php the_content() ?>

                     <!-- PREVIEW CATE CAR -->
                     <button type="button" class="btn btn-info btn-lg btn-agree-rules" data-toggle="modal" data-target="#myPopupAgreeRules" style="display: none;">Tôi đồng ý với điều kiện và điều khoản của chương trình.</button>

                     <!-- POPUP ARGREE RULES -->
                     <div class="modal fade" id="myPopupAgreeRules" role="dialog">
                        <div class="modal-dialog">

                           <!-- POPUP ARGREE RULES CONTENT -->
                           <div class="modal-content">
                              <div class="modal-header">
                                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>
                              <div class="modal-body">
                                 <?php echo get_field('message_form') ?>
                              </div>
                              <div class="modal-footer">
                                 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              </div>
                           </div>

                        </div>
                     </div>

                     <?php if (have_rows('list_car_test_driver', 'option')) : ?>
                        <div class="pre-cate-car">
                           <?php while (have_rows('list_car_test_driver', 'option')) : the_row();
                              $name = get_sub_field('name');
                              $image = get_sub_field('image');
                           ?>
                              <div class="data-cate-car" data-name="<?php echo $name ?>" data-image="<?php echo $image['url'] ?>"></div>
                           <?php endwhile; ?>
                        </div>
                     <?php endif; ?>

                     <div class="insert-button"></div>
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