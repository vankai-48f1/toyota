<?php

/**
 * Template Name: Chương Trình Sửa Chữa
 */

get_header();
?>
<?php
if (have_posts()) :
    while (have_posts()) :;
        the_post(); ?>

        <div class="page-service-repair">

            <?php $urlImage = wp_get_attachment_url(get_post_thumbnail_id($post->ID), 'full'); ?>
            <div class="service-repair-cover bg-style " style="background-image: url(<?php echo $urlImage; ?>);">
                <div class="container">
                    <div class="service-repair-header">
                        <p class="service-repair-subtitle f-bold font-23 cl-black"><?php echo get_field('subtitle_service_repair') ?></p>
                        <h2><?php echo get_field('title_service_repair') ?></h2>
                        <p class="link-booking">
                            <a class="button-red-third bd-radius-3" href="<?php echo get_field('booking_link')['url'] ?>"><?php echo get_field('booking_link')['title'] ?></a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="page-ct">
                <div class="service-repair-target">
                    <div class="container">
                        <?php if (have_rows('tab_target')) : ?>
                            <ul class="tab-target">
                                <?php
                                $i = 0;
                                while (have_rows('tab_target')) : the_row();
                                    $icon_target = get_sub_field('icon_target');
                                    $name_target = get_sub_field('name_target');
                                    ++$i;
                                ?>
                                    <li>
                                        <a href="<?php echo '#section_0'. $i ?>" class="<?php echo $i == 1 ? 'active' : '' ?>">
                                            <img src="<?php echo $icon_target['url']; ?>" alt="">
                                            <p><?php echo $name_target; ?></p>
                                        </a>
                                    </li>
                                <?php endwhile; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Warranty Policy -->
                <div id="section_01">
                    <div class="container">
                        <div class="content-service-tab">
                            <div class="service-tab-head">
                                <p><?php echo get_field('title_warranty_policy') ?></p>
                            </div>
                            <div class="service-tab-content mg-t-3">
                                <div class="car-form-row ">
                                    <div class="col-car-3">
                                        <?php if (have_rows('list_warranty_policy')) : ?>
                                            <ul class="list-tab-name none-list-style pd-l-0">
                                                <?php
                                                $i = 0;
                                                while (have_rows('list_warranty_policy')) : the_row();
                                                    $name = get_sub_field('name');
                                                    ++$i;
                                                ?>
                                                    <li>
                                                        <a href="<?php echo '#warranty_policy_0' . $i; ?>" data-id-section="<?php echo 'warranty_policy_0'. $i ?>" class="<?php echo $i == 1 ? 'active' : '' ?>">
                                                            <?php echo $name; ?>
                                                        </a>
                                                    </li>
                                                <?php endwhile; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </div>

                                    <div class="col-car-9">
                                        <?php if (have_rows('list_warranty_policy')) : ?>
                                            <div class="list-tab-content">
                                                <?php
                                                $i = 0;
                                                while (have_rows('list_warranty_policy')) : the_row();
                                                    $content = get_sub_field('content');
                                                    ++$i;
                                                ?>
                                                    <section id="<?php echo 'warranty_policy_0' . $i ?>">
                                                        <?php echo $content ?>
                                                    </section>
                                                <?php endwhile; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--Maintenance -->
                <div id="section_02">
                    <div class="container">
                        <div class="content-service-tab">
                            <div class="service-tab-head">
                                <p><?php echo get_field('title_mainten') ?></p>
                            </div>
                            <div class="service-tab-content mg-t-3">
                                <div class="car-form-row ">
                                    <div class="col-car-3">
                                        <?php if (have_rows('list_mainten')) : ?>
                                            <ul class="list-tab-name none-list-style pd-l-0">
                                                <?php
                                                $i = 0;
                                                while (have_rows('list_mainten')) : the_row();
                                                    $name = get_sub_field('name');
                                                    ++$i;
                                                ?>
                                                    <li>
                                                        <a href="<?php echo '#list_mainten_0' . $i; ?>" data-id-section="<?php echo 'list_mainten_0'. $i ?>" class="<?php echo $i == 1 ? 'active' : '' ?>">
                                                            <?php echo $name; ?>
                                                        </a>
                                                    </li>
                                                <?php endwhile; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </div>

                                    <div class="col-car-9">
                                        <?php if (have_rows('list_mainten')) : ?>
                                            <div class="list-tab-content">
                                                <?php
                                                $i = 0;
                                                while (have_rows('list_mainten')) : the_row();
                                                    $content = get_sub_field('content');
                                                    ++$i;
                                                ?>
                                                    <section id="<?php echo 'list_mainten_0' . $i ?>">
                                                        <?php echo $content ?>
                                                    </section>
                                                <?php endwhile; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Repair -->
                <div id="section_03">
                    <div class="container">
                        <div class="content-service-tab">
                            <div class="service-tab-head">
                                <p><?php echo get_field('title_repair') ?></p>
                            </div>
                            <div class="service-tab-content mg-t-3">
                                <div class="car-form-row">
                                    <div class="col-car-3">
                                        <?php if (have_rows('list_repair')) : ?>
                                            <ul class="list-tab-name none-list-style pd-l-0">
                                                <?php
                                                $i = 0;
                                                while (have_rows('list_repair')) : the_row();
                                                    $name = get_sub_field('name');
                                                    ++$i;
                                                ?>
                                                    <li>
                                                        <a href="<?php echo '#list_repair_0' . $i; ?>" data-id-section="<?php echo 'list_repair_0'. $i ?>" class="<?php echo $i == 1 ? 'active' : '' ?>">
                                                            <?php echo $name; ?>
                                                        </a>
                                                    </li>
                                                <?php endwhile; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </div>

                                    <div class="col-car-9">
                                        <?php if (have_rows('list_repair')) : ?>
                                            <div class="list-tab-content">
                                                <?php
                                                $i = 0;
                                                while (have_rows('list_repair')) : the_row();
                                                    $content = get_sub_field('content');
                                                    ++$i;
                                                ?>
                                                    <section id="<?php echo 'list_repair_0' . $i ?>">
                                                        <?php echo $content ?>
                                                    </section>
                                                <?php endwhile; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
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