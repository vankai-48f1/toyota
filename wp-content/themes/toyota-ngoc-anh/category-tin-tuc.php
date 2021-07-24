<?php get_header() ?>

<div class="categories-news">
    <?php
    $category = get_queried_object();
    $cate_image = get_field('category_image', $category);
    ?>
    <div class="latest-news-ctn" style="background-image: url(<?php echo $cate_image['url']; ?>);">
        <div class="container">
            <div class="title-cate-news">
                <h1><?php single_cat_title() ?></h1>
            </div>

            <article class="latest-item-news">
                <div class="row">

                    <?php
                    $args_latest_news = array(
                        'post_status' => 'publish',
                        'post_type'     => 'post',
                        'orderby'       => 'date',
                        'order'         => 'DESC',
                        'cat'           => $category->term_id,
                        'showposts'     => 1
                    );
                    $query_news_lates = new WP_Query($args_latest_news);

                    // The Loop
                    if ($query_news_lates->have_posts()) :
                        while ($query_news_lates->have_posts()) : $query_news_lates->the_post(); ?>

                            <div class="col-sm-6">
                                <div class="latest-item-news-thumbnail">
                                    <?php the_post_thumbnail() ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <h2><?php the_title() ?></h2>
                                <p class="cl-red font-13"><?php echo get_the_date('d-m-Y') . ' ' . get_the_time('H:i') ?></p>
                                <div class="cl-white latest-item-excerpt">
                                    <?php the_excerpt() ?>
                                </div>
                                <a href="<?php the_permalink() ?>" class="button-red cl-white">Đọc tiếp</a>
                            </div>
                    <?php
                        endwhile;
                    endif;

                    // Reset Post Data
                    wp_reset_postdata();

                    ?>
                </div>
            </article>
        </div>
    </div>
    <div class="container pd-t-3 pd-bt-5">
        <div class="list-post-news">
            <?php
            $args_news = array(
                'post_status'   => 'publish',
                'post_type'     => 'post',
                'cat'           => $category->term_id,
                'orderby'       => 'date',
                'order'         => 'DESC',
                'posts_per_page'    => -1,
            );
            $query_news = new WP_Query($args_news);

            // The Loop
            if ($query_news->have_posts()) :
                while ($query_news->have_posts()) : $query_news->the_post(); ?>

                    <article class="item-news">
                        <div class="thumbnail-news-item mg-bt-1">
                            <?php the_post_thumbnail() ?>
                        </div>
                        <h3 class="title-news-item"><?php the_title() ?></h3>
                        <p class="date-news-item cl-red font-13"><?php echo get_the_date('d-m-Y') . ' ' . get_the_time('H:i') ?></p>
                    </article>
            <?php
                endwhile;
            endif;

            // Reset Post Data
            wp_reset_postdata();

            ?>

        </div>
        <?php if (paginate_links() != '') { ?>
            <div class="pagination-post">
                <?php
                global $wp_query;
                $big = 999999999;
                echo paginate_links(array(
                    'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                    'format' => '?paged=%#%',
                    'prev_text'    => __('🠔 Trang trước'),
                    'next_text'    => __('Trang tiếp theo 🠖'),
                    'current' => max(1, get_query_var('paged')),
                    'total' => $wp_query->max_num_pages
                ));
                ?>
            </div>
        <?php } ?>
    </div>
</div>

<?php get_footer() ?>