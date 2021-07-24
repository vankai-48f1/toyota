<?php

/**
 * Template Name: Dự Toán Chi Phí
 */
?>
<?php
get_header();
?>

<!-- Header -->
<?php get_template_part('template-parts/banner'); ?>

<?php if (have_posts()) : ?>
   <?php while (have_posts()) : the_post(); ?>
      <div class="bg-white-light">
         <div class="car-container">
            <div class="row">
               <div class="col-lg-7 col-md-8 mg-auto">
                  <div class="mg-bt-4">
                     <div class="title-form-top">
                        <h2><?php echo get_the_title() ?></h2>
                     </div>
                     <div class="wrap-field-cost-estimates bg-white pd-bt-1">
                        <div class="pd-t-1 pd-lr-20">
                           <p class="f-bold font-15">Quý Khách hàng vui lòng chọn thông tin dưới đây:</p>
                        </div>
                        <div class="pd-lr-20">
                           <?php get_template_part('template-parts/model', 'vehicle'); ?>
                        </div>
                        <div class="car-form-row form-car pd-lr-20">
                           <div class="col-car-12">
                              <label for="" class="cl-prm mg-bt-0">Nơi đăng ký trước bạ&nbsp;<span class="cl-red">*</span></label>
                           </div>
                        </div>
                        <div class="pd-lr-20 form-car cost-estimates-city">
                           <?php get_template_part('template-parts/city', 'province'); ?>
                        </div>
                        <div class="vehicle-purchase-cost-field">
                           <input id="value_phi_bao_hiem_tnds" type="hidden">
                           <input id="value_phi_truoc_ba" type="hidden" value="<?php echo get_field('phi_truoc_ba', 'option') ?>">
                           <input id="value_phi_dang_ky_city" type="hidden" value="<?php echo get_field('phi_dang_ky_city', 'option') ?>">
                           <input id="value_phi_dang_ky_province" type="hidden" value="<?php echo get_field('phi_dang_ky_province', 'option') ?>">
                           <input id="value_phi_kiem_dinh" type="hidden" value="<?php echo get_field('phi_kiem_dinh', 'option') ?>">
                           <input id="value_phi_duong_bo" type="hidden" value="<?php echo get_field('phi_duong_bo', 'option') ?>">
                        </div>
                        <div class="center-ct pd-t-1">
                           <button id="btn-cost-estimate" class="bd-none button-red-second cl-white">
                              <span class="load-animate">
                                 <span class="icon-load-anm">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                 </span>
                              </span>
                              Tính chi phí
                           </button>
                        </div>
                     </div>
                     <div class="popup-cost">
                        <div class="pd-lr-20 popup-cost-ct">
                           <div class="car-form-row">
                              <div class="col-car-7">
                                 <div class="mg-bt-1">
                                    <p class="name-car-cost cl-prm font-20 mg-bt-2">Kết quả tạm tính </p>
                                    <span class="line-boder"></span>
                                 </div>
                                 <div class="calculated-cost">
                                    <p class="cl-prm"><span>Giá xe</span> <span class="gia-xe">?</span></p>
                                    <p class="cl-prm"><span>Giá xe tính lệ phí trước bạ</span> <span class="gia-xe">?</span></p>
                                    <p class="cl-prm"><span>Phí trước bạ</span> <span class="phi-truoc-ba">?</span></p>
                                    <p class="cl-prm"><span>Phí đăng ký</span> <span class="phi-dang-ky">?</span></p>
                                    <p class="cl-prm"><span>Phí kiểm định</span> <span class="phi-kiem-dinh">?</span></p>
                                    <p class="cl-prm"><span>Phí đường bộ/Năm</span> <span class="phi-duong-bo">?</span></p>
                                    <p class="cl-prm"><span>Bảo hiểm TNDS/Năm</span> <span class="phi-bao-hiem">?</span></p>
                                 </div>
                              </div>
                              <div class="col-car-5">
                                 <div class="controls-cost pd-l-1 pd-r-1">
                                    <?php
                                    $link_contact = get_field('link_contact_page', 'option');
                                    $link_quy_trinh = get_field('link_quy_trinh_dang_ky');
                                    ?>
                                    <a href="" class="recalculate-cost button-red-second wd-100 cl-white center-ct">Tính lại</a>

                                    <?php if ($link_contact) : ?>
                                       <a href="<?php echo $link_contact['url'] ?>" class="button-red-second wd-100 cl-white center-ct"><?php echo $link_contact['title'] ?></a>
                                    <?php endif;

                                    if ($link_quy_trinh) : ?>
                                       <!-- <a href="" class="button-red-second wd-100 cl-white center-ct">Tìm nơi đăng ký</a> -->
                                       <a href="<?php echo $link_quy_trinh['url'] ?>" class="button-red-second wd-100 cl-white center-ct"><?php echo $link_quy_trinh['title'] ?></a>
                                    <?php endif; ?>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="pd-lr-20">
                           <div class="total-cost">
                              <span class="total-name">Tổng cộng:</span>
                              <span id="result-cost-estimates">98796968</span>
                           </div>
                        </div>
                        <div class="calculate-description cl-prm pd-lr-20">
                           <span class="line-boder mg-bt-1"></span>
                           <?php echo get_field('message_form') ?>
                        </div>
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