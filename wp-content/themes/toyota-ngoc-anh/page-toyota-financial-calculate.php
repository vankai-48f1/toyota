<?php

/**
 * Template Name: Cách Tính Vay Từ Tài Chính Toyota
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
                              <label for="">Giá phụ kiện&nbsp;<span class="cl-red">*</span></label>
                           </div>
                           <div class="col-car-8">
                              <input type="number" class="accessory-price cl-prm" placeholder="<?php echo number_format(100000000, 0, ',', '.') ?>">
                           </div>
                        </div>

                        <div class="car-form-row form-car pd-lr-20">
                           <div class="col-car-4">
                              <label for="">Sản phẩm tài chính&nbsp;<span class="cl-red">*</span></label>
                           </div>
                           <div class="col-car-8">
                              <div class="cus-drop">
                                 <select name="" id="type-financial-product">
                                    <option value="0"></option>
                                    <option value="1">Sản phẩm truyền thống</option>
                                    <option value="2">Sản phẩm 50/50</option>
                                 </select>
                                 <span class="icon-select"><i class="fa fa-caret-down" aria-hidden="true"></i></span>
                              </div>
                           </div>
                        </div>

                        <div class="financial-payment pd-lr-20">
                           <div class="car-form-row form-car">
                              <div class="col-car-4">
                                 <label for="">Hình thức thanh toán<span class="cl-red">*</span></label>
                              </div>
                              <div class="col-car-8">
                                 <div class="car-form-row">
                                    <div class="col-car-12">
                                       <div class="feng-shui-gender">
                                          <label for="financial-month">
                                             <input type="radio" id="financial-month" name="financial-payment" value="month">
                                             <span class="cus-icon-check"></span>
                                             Theo tháng
                                          </label>
                                       </div>
                                    </div>
                                    <div class="col-car-12">
                                       <div class="feng-shui-gender">
                                          <label for="financial-year">
                                             <input type="radio" id="financial-year" name="financial-payment" value="year">
                                             <span class="cus-icon-check"></span>
                                             Theo năm
                                          </label>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>

                        <div class="car-form-row form-car pd-lr-20">
                           <div class="col-car-4">
                              <label for="">Thời hạn vay vốn&nbsp;<span class="cl-red">*</span></label>
                           </div>
                           <div class="col-car-8">
                              <div class="cus-drop">
                                 <select name="" id="toyota-financial-year">
                                    <option value="0"></option>
                                    <option value="6">6 tháng</option>
                                    <option value="12">12 tháng</option>
                                    <option value="24">24 tháng</option>
                                 </select>
                                 <span class="icon-select"><i class="fa fa-caret-down" aria-hidden="true"></i></span>
                              </div>
                           </div>
                        </div>

                        <div class="car-form-row form-car pd-lr-20">
                           <div class="col-car-4">
                              <label for="">Số tiền trả trước&nbsp;<span class="cl-red">*</span></label>
                           </div>
                           <div class="col-car-8">
                              <input type="number" class="prepayment-amount cl-prm" placeholder="<?php echo number_format(100000000, 0, ',', '.') ?>">
                           </div>
                        </div>

                        <div class="center-ct pd-t-1">
                           <button  id="loan-conditions-btn" class="bd-none button-red-second cl-white">
                              Điều kiện vay
                           </button>
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
                                    <a href="" class="recalculate-cost button-red-second wd-100 cl-white center-ct">Tính lại</a>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="pd-lr-20">
                           <div class="total-cost">
                              <span class="total-name">Tổng cộng:</span>
                              <span id="result-cost-toyota-financial">98796968</span>
                           </div>
                        </div>
                        <div class="calculate-description cl-prm pd-lr-20">
                           <span class="line-boder mg-bt-1"></span>
                           <?php echo get_field('message_form') ?>
                        </div>
                     </div>

                     <div class="loan-conditions-popup">
                        <?php echo get_field('dieu_kien_vay') ?>
                        <button class="loan-conditions-close">Đóng</button>
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