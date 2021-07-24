<?php
function handle_vehicle_compare()
{
    $vehicle_1 = $_POST['vehicle_1'];
    $vehicle_2 = $_POST['vehicle_2'];

    $vehicles = array(
        'post_type'     => 'cars',
        'post_status'   => 'publish'
    );

    $query_vehicles = new WP_Query($vehicles);

    $data_vehicle = [];

    class Vehicle {
        public $id_vehicle;
        public $image_vehicle;
        public $name_vehicle;
        public $data_vehicle;


        public function __construct($id_vehicle, $image_vehicle, $name_vehicle, $fuel_type, $engine, $data_vehicle)
        {
            $this->id_vehicle = $id_vehicle;
            $this->image_vehicle = $image_vehicle;
            $this->name_vehicle = $name_vehicle;
            $this->fuel_type = $fuel_type;
            $this->engine = $engine;
            $this->data_vehicle = $data_vehicle;
        }
    };
    
    // The Loop
    if ($query_vehicles->have_posts()) :
        while ($query_vehicles->have_posts()) : $query_vehicles->the_post();

            $id_vehicle             = get_the_ID();
            $image_vehicle          = get_field('car_images', $id_vehicle)[0]['url'];
            $name_vehicle           = get_the_title($id_vehicle);

            if ($id_vehicle == $vehicle_1 || $id_vehicle == $vehicle_2) {
                $all_value = get_fields($id_vehicle);
                $tax_fuel = get_the_terms($id_vehicle, 'car_fuel_type');
                $fuel_type = $tax_fuel[0]->name;
                $tax_engine = get_the_terms($id_vehicle, 'car_engine');
                $engine = $tax_engine[0]->name;

                $vehicleObject = new Vehicle ($id_vehicle, $image_vehicle, $name_vehicle, $fuel_type, $engine, $all_value);
                
                array_push(
                    $data_vehicle,
                    $vehicleObject
                );

            }
        endwhile;

        $data = json_encode($data_vehicle);
        echo $data;

    endif;

    // Reset Post Data
    wp_reset_postdata();
    die;
}
add_action('wp_ajax_nopriv_vehicle_compare', 'handle_vehicle_compare');
add_action('wp_ajax_vehicle_compare', 'handle_vehicle_compare');
