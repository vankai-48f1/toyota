<?php

/**
 * Template Name: Liên Hệ
 */
?>
<?php
get_header();
?>

<!-- Header -->
<div class="head-contact-us">
   <div class="map-branch">
      <div class="map-branch-view">
         <iframe src="" frameborder="0" style="border:0"></iframe>
      </div>

      <div class="wrap-branch-list">
         <div class="container">
            <?php if (have_rows('brand_contact_us')) : ?>
               <?php $i = 0; ?>
               <ul class="map-branch-list">
                  <?php while (have_rows('brand_contact_us')) : the_row();
                     ++$i;

                     $name_branch      = get_sub_field('name_branch');
                     $address          = get_sub_field('address');
                     $coordinate       = get_sub_field('coordinates_map');
                     $direct           = get_sub_field('direct');
                     $telephone        = get_sub_field('telephone'); ?>

                     <li class="<?php echo $i == 1 ? 'active' : ''; ?>">
                        <p class="map-name-branch" data-iframe-map="<?php echo $coordinate ?>">
                           <span class="icon-map">
                              <i class="fa fa-map" aria-hidden="true"></i>
                           </span>
                           <?php echo $name_branch; ?>
                        </p>
                        <div class="map-address-branch <?php echo $i == 1 ? 'show' : ''; ?>">
                           <?php echo $address ?>
                           <p class="direct">
                              <a href="<?php echo $direct ?>" target="_blank">Chỉ đường</a>
                              <a href="tel:<?php echo $telephone ?>" target="_blank">Gọi điện</a>
                           </p>
                        </div>
                     </li>
                  <?php endwhile; ?>
               </ul>
            <?php else :
            // Do something...
            endif; ?>
         </div>
      </div>
   </div>
</div>

<?php if (have_posts()) : ?>
   <?php while (have_posts()) : the_post(); ?>
      <div class="page-content pd-t-2">
         <div class="container">
            <div class="form-register form-contact-us mg-t-2 mg-bt-5">
               <?php the_content() ?>

               <?php
               // Branch list 
               if (have_rows('branch_group', 'option')) : ?>

                  <ul class="na-branch-list">
                     <?php while (have_rows('branch_group', 'option')) : the_row();

                        // Load sub field value.
                        $name_branch = get_sub_field('name_branch');
                        $location_branch = get_sub_field('location_branch'); ?>

                        <li>
                           <span class="name_branch"><?php echo $name_branch; ?></span>
                           <span class="location_branch">
                              <?php echo $location_branch ?>
                           </span>
                        </li>
                     <?php endwhile; ?>
                  </ul>
               <?php else :
               // Do something...
               endif; ?>
            </div>
         </div>
      </div>
   <?php endwhile; ?>
<?php endif; ?>

<?php
get_footer();
?>