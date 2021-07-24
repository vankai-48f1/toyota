<?php

/**
 * Template Name: So sánh xe
 */
?>
<?php
get_header();
?>

<div class="page-vehicle-compare">
   <!-- Header -->
   <?php get_template_part('template-parts/header'); ?>

   <?php if (have_posts()) : ?>
      <?php while (have_posts()) : the_post(); ?>
         <div class="pd-t-5">
            <div class="car-container">
               <div class="row">
                  <div class="col-12 col-sm-10 col-md-9 col-lg-8 mg-auto">
                     <div class="vehicle-compare-content mg-bt-3">
                        <div class="choose-vehicle-compare">
                           <div class="box-vehicle" id="vehicle-blank-1">
                              <a href="#" class="vehicle-compare-blank"></a>
                              <div class="vehicle-compare-blank-show"></div>

                              <p class="vehicle-compare-name center-ct">Chọn</p>
                              <p class="vehicle-compare-name-show center-ct"></p>
                              <input type="hidden" name="id-vehicle">
                              <span class="cancel-vehicle"></span>
                           </div>
                           <div class="box-vehicle" id="vehicle-blank-2">
                              <a href="#" class="vehicle-compare-blank"></a>
                              <div class="vehicle-compare-blank-show"></div>

                              <p class="vehicle-compare-name center-ct">Chọn</p>
                              <p class="vehicle-compare-name-show center-ct"></p>
                              <input type="hidden" name="id-vehicle">
                              <span class="cancel-vehicle"></span>
                           </div>
                        </div>
                        <div class="btn-vehicle-compare center-ct mg-t-4">
                           <button id="button-vehicle-compare" disabled>
                              <span class="load-animate">
                                 <span class="icon-load-anm">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                 </span>
                              </span>
                              So sánh xe
                           </button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      <?php endwhile; ?>
   <?php endif; ?>
</div>
<div class="vehicle-compare-list" data-id-box="">
   <div class="car-container">
      <h3 class="compare-title">SO SÁNH XE</h3>
      <?php get_template_part('template-parts/popup', 'list-vehicle'); ?>
   </div>
</div>

<div class="result-compare">
   <div class="car-container">
      <h3 class="compare-title align-center">KẾT QUẢ SO SÁNH XE</h3>
      <div class="car-form-row">
         <div class="col-car-3">
            <ul class="nav-result-compare">
               <li class="active" data-target="engine-and-chassis">ĐỘNG CƠ & KHUNG XE</li>
               <li data-target="exterior">NGOẠI THẤT</li>
               <li data-target="furniture">NỘI THẤT</li>
               <li data-target="convenient">TIỆN NGHI</li>
               <li data-target="active-safety">AN TOÀN CHỦ ĐỘNG</li>
               <li data-target="passive-safety">AN TOÀN BỊ ĐỘNG</li>
               <li data-target="security">AN NINH</li>
            </ul>
         </div>
         <div class="col-car-9">
            <div class="result-compare-content">
               <table>
                  <thead>
                     <tr></tr>
                  </thead>
                  <tbody data-vehicle="engine-and-chassis">
                  </tbody>
                  <tbody data-vehicle="exterior">
                  </tbody>
                  <tbody data-vehicle="furniture">
                  </tbody>
                  <tbody data-vehicle="convenient">
                  </tbody>
                  <tbody data-vehicle="active-safety">
                  </tbody>
                  <tbody data-vehicle="passive-safety">
                  </tbody>
                  <tbody data-vehicle="security">
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>
<?php
get_footer();
?>