<?php

/**
 * Template Name: Tư vấn phong thủy
 */
?>
<?php
get_header();
?>

<!-- Header -->
<?php get_template_part('template-parts/banner'); ?>

<div class="page-feng-shui bg-white-light">
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-xs-12 mg-auto">
                <div class="title-form-top">
                    <h2><?php echo get_the_title() ?></h2>
                </div>
                <div class="feng-shui-content pd-20 mg-bt-4 bg-white">
                    <p class="f-bold font-15">Quý Khách hàng vui lòng chọn thông tin dưới đây:</p>
                    <div class="car-form-row form-car">
                        <div class="col-car-4">
                            <label for="">Ngày sinh <span class="cl-red">*</span></label>
                        </div>
                        <div class="col-car-8">
                            <input type="date" class="cl-prm" id="feng-shui-date">
                            <input type="hidden" name="date_formatted">
                        </div>
                    </div>
                    <div class="car-form-row form-car">
                        <div class="col-car-4">
                            <label for="">Giới tính <span class="cl-red">*</span></label>
                        </div>
                        <div class="col-car-8">
                            <div class="car-form-row">
                                <div class="col-car-6">
                                    <div class="feng-shui-gender">
                                        <label for="male">
                                            <input type="radio" id="male" name="gender" value="male">
                                            <span class="cus-icon-check"></span>
                                            Nam
                                        </label>
                                    </div>
                                </div>
                                <div class="col-car-6">
                                    <div class="feng-shui-gender">
                                        <label for="female">
                                            <input type="radio" id="female" name="gender" value="female">
                                            <span class="cus-icon-check"></span>
                                            Nữ
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="feng-shui-error"></div>
                    <div class="center-ct mg-t-2">
                        <button id="btn-feng-shui" class="bd-none button-red-second cl-white">
                            <span class="load-animate">
                                <span class="icon-load-anm">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </span>
                            </span>
                            Xem
                        </button>
                    </div>

                    <div class="feng-shui-result">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer() ?>