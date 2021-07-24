<div class="list-vehicle">
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

    if (!empty($args_model) && is_array($args_model)) :

        foreach ($args_model as $model) : ?>
            <div class="model-vehicle-content">
                <h3 class="model-vehicle-name"><?php echo $model->name ?></h3>
                <div class="vehicle-grade-content">
                    <?php
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

                    ?>

                            <section>
                                <div class="vehicle-thumnail">
                                    <!-- <img src="< ?php echo get_field('car_images', $id_vehicle)[0]['url'] ?>" alt=""> -->
                                </div>
                                <div class="vehicle-grade-item">
                                    <p class="cl-prm"><?php echo $name_vehicle ?></p>
                                    <p class="font-13 cl-prm">Giá:&nbsp;<b><?php echo number_format($price_vehicle, 0, ',', '.') ?></b></p>
                                </div>
                                <div class="short-infor">
                                    <div class="meta-icon-two">
                                        <span class="meta-icon-item">
                                            <span class="icon-seat"></span>&nbsp;
                                            <span class="meta-data"><?php echo get_field('so_ghe_ngoi', $id_vehicle) ?></span>
                                        </span>
                                        <span class="meta-icon-item">
                                            <span class="icon-fuel"></span>&nbsp;
                                            <span class="meta-data">
                                            <?php
                                                $tax_fuel = get_the_terms($id_vehicle, 'car_fuel_type');
                                                echo $tax_fuel[0]->name;
                                                ?>
                                            </span>
                                        </span>
                                    </div>
                                    <div class="meta-icon-one">
                                        <span class="meta-icon-item">
                                            <span class="icon-transmission"></span>&ensp;
                                            <span class="meta-data">
                                                <?php
                                                $tax_transmission = get_the_terms($id_vehicle, 'car_transmission');
                                                echo $tax_transmission[0]->name;
                                                ?>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                                <div class="data-vehicle-item" data-id-vehicle="<?php echo $id_vehicle ?>" data-src-img="<?php echo get_field('car_images', $id_vehicle)[0]['url'] ?>"
                                 data-name-vehicle="<?php echo $name_vehicle ?>"></div>
                                <button class="choose-this">Chọn</button>
                            </section>
                    <?php endwhile;
                    endif;
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
    <?php endforeach;
    endif;
    ?>
</div>