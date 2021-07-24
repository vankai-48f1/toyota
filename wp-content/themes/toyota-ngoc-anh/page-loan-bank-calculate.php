<?php

/**
 * Template Name: Cách Tính Vay Từ Ngân Hàng
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
                     <div class="wrap-field-loan-bank bg-white pd-bt-1">
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
                                 <input type="text" disabled="disabled" name="price_vehicle" class="loan-bank-price-vehicle cl-prm">
                              </div>
                           </div>
                        </div>
                        <div class="car-form-row form-car pd-lr-20">
                           <div class="col-car-4">
                              <label for="">Số tiền trả trước&nbsp;<span class="cl-red">*</span></label>
                           </div>
                           <div class="col-car-8">
                              <input type="text" class="prepayment-amount cl-prm" placeholder="<?php echo number_format(100000000, 0, ',', '.') ?>">
                           </div>
                        </div>
                        <div class="car-form-row form-car pd-lr-20">
                           <div class="col-car-4">
                              <label for="">Lãi suất (Ví dụ 1.5)&nbsp;<span class="cl-red">*</span></label>
                           </div>
                           <div class="col-car-8">
                              <input type="number" min="0" class="interest-rate cl-prm">
                           </div>
                        </div>
                        <div class="car-form-row form-car pd-lr-20">
                           <div class="col-car-4">
                              <label for="">Thời hạn vay vốn&nbsp;<span class="cl-red">*</span></label>
                           </div>
                           <div class="col-car-8">
                              <select name="" id="loan-bank-term">
                                 <option value="0"></option>
                                 <option value="6">6 tháng</option>
                                 <option value="12">12 tháng</option>
                                 <option value="24">24 tháng</option>
                              </select>
                           </div>
                        </div>

                        <div class="center-ct pd-t-1">
                           <button id="btn-loan-bank" class="bd-none button-red-second cl-white">
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

                     <div class="result-cal-loan-bank pd-lr-20 bg-white pd-bt-2">
                        <table>
                           <caption>
                              Tiền gốc phải trả hàng tháng: <strong class="cl-red"></strong>
                           </caption>
                           <thead>
                              <tr>
                                 <th>Kỳ</th>
                                 <th>Dư nợ</th>
                                 <th>Tiền lãi</th>
                                 <th>Gốc + Lãi</th>
                              </tr>
                           </thead>
                           <tbody></tbody>
                        </table>
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