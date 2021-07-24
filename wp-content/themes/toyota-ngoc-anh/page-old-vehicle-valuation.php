<?php

/**
 * Template Name: Định giá xe cũ
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
                     <div class="wrap-field-toyota-financial bg-white pd-bt-1">
                        <div class="pd-t-1 pd-lr-20">
                           <p class="f-bold font-15"></p>
                        </div>
                        <div class="pd-lr-20">
                           <?php get_template_part('template-parts/model', 'vehicle'); ?>
                        </div>
                        <div class="row-price-vehicle">
                           <div class="car-form-row form-car pd-lr-20">
                              <div class="col-car-4">
                                 <label for="">Giá xe</label>
                              </div>
                              <div class="col-car-8">
                                 <input type="text" disabled="disabled" name="price_vehicle" class="toyota-financial-price-vehicle cl-prm">
                              </div>
                           </div>
                        </div>
                        <div class="car-form-row form-car pd-lr-20">
                           <div class="col-car-4">
                              <label for="">Thời điểm mua xe&nbsp;<span class="cl-red">*</span></label>
                           </div>
                           <div class="col-car-8">
                              <input type="date" class="cl-prm">
                           </div>
                        </div>

                        <div class="car-form-row form-car pd-lr-20">
                           <div class="col-car-4">
                              <label for="">Số KM&nbsp;<span class="cl-red">*</span></label>
                           </div>
                           <div class="col-car-8">
                              <input type="number" class="cl-prm">
                           </div>
                        </div>

                        <div class="car-form-row form-car pd-lr-20">
                           <div class="col-car-4">
                              <label for="">Biển số xe tại&nbsp;<span class="cl-red">*</span></label>
                           </div>
                           <div class="col-car-8">
                              <div class="cus-drop">
                                 <select name="" id="">
                                    <option value="0">Không</option>
                                    <option value="1">Thành phố</option>
                                    <option value="2">Tỉnh</option>
                                 </select>
                                 <span class="icon-select"><i class="fa fa-caret-down" aria-hidden="true"></i></span>
                              </div>
                           </div>
                        </div>

                        <div class="car-form-row form-car pd-lr-20">
                           <div class="col-car-4">
                              <label for="">Màu sắc</label>
                           </div>
                           <div class="col-car-8">
                              <input type="text" class="cl-prm">
                           </div>
                        </div>

                        <div class="car-form-row form-car pd-lr-20">
                           <div class="col-car-4">
                              <label for="">Tình trạng xe&nbsp;<span class="cl-red">*</span></label>
                           </div>
                           <div class="col-car-8">
                              <div class="cus-drop">
                                 <select name="" id="">
                                    <option value="0">Rất tốt</option>
                                    <option value="1">Khá tốt</option>
                                    <option value="2">Tốt</option>    
                                    <option value="3">Khá</option>
                                 </select>
                                 <span class="icon-select"><i class="fa fa-caret-down" aria-hidden="true"></i></span>
                              </div>
                           </div>
                        </div>

                        <div class="center-ct pd-t-1">
                           <button id="btn-toyota-financial" class="bd-none button-red-second cl-white">
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
