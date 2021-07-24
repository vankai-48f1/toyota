<?php

get_header();
?>
<?php
if (have_posts()) :
    while (have_posts()) : the_post(); ?>
        <div class="page-booking">
            <div class="page-booking-title">
                <div class="container">
                    <h1><?php the_title() ?></h1>
                </div>
            </div>
            <div class="booking-content mg-t-2">
                <div class="container">
                    <ul class="booking-tabs pd-l-0">
                        <li>
                            <a href="" class="active">
                                <span class="booking-step">01</span>
                                <span class="booking-tabs-text">Chọn dịch vụ</span>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <span class="booking-step">02</span>
                                <span class="booking-tabs-text">Đặt lịch hẹn</span>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <span class="booking-step">03</span>
                                <span class="booking-tabs-text">Thông tin</span>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <span class="booking-step"><i class="fa fa-check" aria-hidden="true"></i></span>
                                <span class="booking-tabs-text">Hoàn tất</span>
                            </a>
                        </li>
                    </ul>
                    <div class="booking-tabs-content">
                            <div id="booking_tab_01">
                                <div class="booking-hd-tabs">
                                    <p class="booking-hd-step">Bước 1</p>
                                    <p class="booking-hd-name">Chọn dịch vụ</p>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>

<?php endwhile;
endif;
?>
<?php get_footer(); ?>