<?php

/**
 * Template Name: Cách Tính Chi Phí Bảo Hiểm
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
                        <div class="form-calculate mg-bt-4 bg-white">
                            <div class="title-form-top">
                                <h2><?php echo get_the_title() ?></h2>
                            </div>
                            <div class="calculate-insurance-ctn">
                                <div class="calculate-view-fields">
                                    <div class="mg-t-1 pd-lr-20">
                                        <p class="f-bold font-15">Quý Khách hàng vui lòng chọn thông tin dưới đây:</p>
                                    </div>
                                    <form action="" class="pd-lr-20 pd-bt-1">
                                        <?php get_template_part('template-parts/model', 'vehicle'); ?>
                                        <!-- INSURANCE FEES -->
                                        <input type="hidden" value="<?php the_field('value_one_year_insurance_cost', 'option') ?>" class="val-one-year-insurance-cost">
                                        <input type="hidden" value="<?php the_field('value_two_year_insurance_cost', 'option') ?>" class="val-two-year-insurance-cost">
                                        <input type="hidden" value="<?php the_field('value_three_year_insurance_cost', 'option') ?>" class="val-three-year-insurance-cost">

                                        <div class="center-ct pd-t-1">
                                            <button id="btn-insurance-cost" class="bd-none button-red-second cl-white">
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
                                    </form>
                                </div>
                                <section class="done-calculate-insurance">
                                    <div class="pd-lr-20">
                                        <p class="name-car cl-prm font-20">Kết quả tạm tính chi phí bảo hiểm</p>
                                        <span class="line-boder"></span>
                                        <p class="mg-bt-1 mg-t-1 cl-prm dp-flex justify-between"><span>Ước tính chi phí bảo hiểm 1 năm</span><span class="price-calculated-one-year"></span></p>
                                        <p class="mg-bt-1 cl-prm dp-flex justify-between"><span>Ước tính chi phí bảo hiểm 2 năm</span><span class="price-calculated-two-year"></span></p>
                                        <p class="mg-bt-1 cl-prm dp-flex justify-between"><span>Ước tính chi phí bảo hiểm 3 năm</span><span class="price-calculated-three-year"></span></p>
                                    </div>
                                    <div class="pd-lr-20 pd-bt-1">
                                        <section class="controls mg-bt-1">
                                            <a href="#" class="button-red cl-white recalculate-insurance">Tính lại</a>
                                            <?php
                                            $link_contact = get_field('link_contact_page', 'option');
                                            ?>
                                            <a href="<?php echo $link_contact['url'] ?>" class="button-red cl-white pd-20"><?php echo $link_contact['title'] ?></a>
                                        </section>
                                        <div class="calculate-description cl-prm">
                                            <?php echo get_field('message_form') ?>
                                        </div>
                                    </div>
                                </section>
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