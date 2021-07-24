<div class="car-form-row form-car">
    <div class="col-car-4">
        <label class="font-15">Mẫu xe&nbsp;<span class="cl-red">*</span></label>
    </div>
    <div class="col-car-8">
        <div class="cus-drop vehicle-model-wrap">
            <input type="text" value="" class="vehicle-model-field wt-mouse font-15" onkeydown="return false;">
            <input type="hidden" value="" class="model-id">
            <!-- PREVIEW CATE CAR -->
            <ul class="list-vehicle-model">
                <?php
                $cate_cars = get_terms(
                    array(
                        'taxonomy'   => 'car_model',
                        'hide_empty' => false,
                        'orderby'    => 'id',
                        'order'      =>  'ASC'
                    )
                );

                $modelCarId = [];

                if (!empty($cate_cars) && is_array($cate_cars)) {

                    foreach ($cate_cars as $cate_car) {
                        $image = get_field('category_image', 'vehicle_cat_' . $cate_car->term_id);
                        array_push($modelCarId, $cate_car->term_id)
                ?>
                        <li class="data-cate-car font-15" data-name="<?php echo $cate_car->name ?>" data-model-id="<?php echo $cate_car->term_id ?>">
                            <div class="thumbnail-vehicle-model">
                                <img src="<?php echo $image['url'] ?>" alt="">
                            </div>
                            <div class="vehicle-model-name">
                                <p><?php echo $cate_car->name ?></p>
                            </div>
                        </li>


                        <?php

                        ?>
                <?php }
                }
                ?>
            </ul>
            <span class="icon-select"><i class="fa fa-caret-down" aria-hidden="true"></i></span>
        </div>
    </div>
    <div class="col-car-12 cus-error-field">
        <span class="cl-red font-13"></span>
    </div>
</div>
<div class="vehicle-grade-ctn form-car">
    <div class="car-form-row">
        <div class="col-car-4">
            <label class="font-15">Dòng xe&nbsp;<span class="cl-red">*</span></label>
        </div>
        <div class="col-car-8">
            <div class="cus-drop vehicle-grade-wrap">
                <input type="text" value="" class="vehicle-grade-field wt-mouse font-15">
                <input type="number" value="" class="vehicle-price-field" style="display: none">
                <!-- PREVIEW VEHICLE GRADE -->
                <ul class="list-vehicle-grade">
                </ul>
                <span class="icon-select"><i class="fa fa-caret-down" aria-hidden="true"></i></span>
            </div>
        </div>
        <div class="col-car-12 cus-error-field">
            <span class="cl-red font-13"></span>
        </div>
    </div>
</div>

<div class="preview-vehicle">
    <?php
    $args_model = get_terms(
        array(
            'taxonomy'   => 'car_model',
            'hide_empty' => false,
            'orderby'    => 'id',
            'order'      =>  'ASC'
        )
    );

    $modelCarId = [];

    if (!empty($args_model) && is_array($args_model)) {

        foreach ($args_model as $model) {

            $args_vehicle = array(
                'post_type' => 'cars',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'car_model',
                        'field' => 'term_id',
                        'terms' => $model->term_id
                    )
                )
            );

            $queryVehicle = new WP_Query($args_vehicle);

            // The Loop
            if ($queryVehicle->have_posts()) :
                while ($queryVehicle->have_posts()) : $queryVehicle->the_post();

                    $id_vehicle = get_the_ID();
                    $price_vehicle = function_exists('get_field') ? get_field('regular_price', $id_vehicle) : get_post_meta($id_vehicle, 'regular_price');
                    $name_vehicle = get_the_title($id_vehicle);
                    $phi_bao_hiem_tnds = get_field('phi_bao_hiem_tnds', $id_vehicle);

                    echo '<div class="preview-vihicle-grade" data-name="' . $name_vehicle . '" data-price="' . $price_vehicle . '" data-model-id="' . $model->term_id . '" data-insurance="' . $phi_bao_hiem_tnds . '">
                                         </div>';
                endwhile;
            endif;
            wp_reset_postdata();
    ?>
    <?php }
    }
    ?>
</div>