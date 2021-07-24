<div class="banner-top">
    <?php $url = wp_get_attachment_url(get_post_thumbnail_id($post->ID), 'full'); ?>
    <div class="banner-ctn" style="background-image: url(<?php echo $url ?>)">
    </div>
</div>