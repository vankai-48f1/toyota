<!-- Header page -->
<div class="head-page">
    <?php $url = wp_get_attachment_url(get_post_thumbnail_id($post->ID), 'full'); ?>
    <div class="head-page-banner" style="background-image: url(<?php echo $url ?>)">
        <div class="car-container">
            <div class="row">
                <div class="col-12 col-sm-10 col-md-9 col-lg-8 mg-auto">
                    <div class="head-page-cover">
                        <div class="head-page-title">
                            <h1>
                                <?php
                                if (is_page()) {
                                    echo get_the_title();
                                }
                                ?>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>