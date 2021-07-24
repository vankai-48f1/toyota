<?php

/**
 * Template Name: Bài Viết Tin Tức
 * Template Post Type: post
 */

get_header();
?>

<?php
if (have_posts()) :
    while (have_posts()) : the_post(); ?>
        <div class="page-news">
            <div class="news-header">
                <?php $url = wp_get_attachment_url(get_post_thumbnail_id($post->ID), 'full'); ?>
                <div class="news-cover" style="background-image: url(<?php echo $url ?>);">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-9">
                                <div class="news-header-ctn">
                                    <div class="news-header-content">
                                        <h1 class="news-title"><?php the_title() ?></h1>
                                        <p class="font-13 cl-red text-right"><?php echo get_the_date('d/m/Y') . ' ' . get_the_time('H:i') ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="news-content-block cl-prm pd-bt-4">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-9">
                            <div class="news-content">
                                <?php the_content(); ?>
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