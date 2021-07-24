<?php
// Add new taxonomy, NOT hierarchical (like tags).
$labels = array(
	'name'                       => esc_html__( 'Year', 'cardealer-helper' ),
	'singular_name'              => esc_html__( 'Year', 'cardealer-helper' ),
	'search_items'               => esc_html__( 'Search Year', 'cardealer-helper' ),
	'popular_items'              => esc_html__( 'Popular Year', 'cardealer-helper' ),
	'all_items'                  => esc_html__( 'All Year', 'cardealer-helper' ),
	'parent_item'                => null,
	'parent_item_colon'          => null,
	'edit_item'                  => esc_html__( 'Edit Year', 'cardealer-helper' ),
	'update_item'                => esc_html__( 'Update Year', 'cardealer-helper' ),
	'add_new_item'               => esc_html__( 'Add New Year', 'cardealer-helper' ),
	'new_item_name'              => esc_html__( 'New Year Name', 'cardealer-helper' ),
	'separate_items_with_commas' => esc_html__( 'Separate year with commas', 'cardealer-helper' ),
	'add_or_remove_items'        => esc_html__( 'Add or remove Year', 'cardealer-helper' ),
	'choose_from_most_used'      => esc_html__( 'Choose from the most used Year', 'cardealer-helper' ),
	'not_found'                  => esc_html__( 'No year found.', 'cardealer-helper' ),
	'menu_name'                  => esc_html__( 'Year', 'cardealer-helper' ),
);

$args = array(
	'hierarchical'          => false,
	'labels'                => $labels,
	'show_ui'               => true,
	'show_in_menu'          => false,
	'update_count_callback' => '_update_post_term_count',
	'query_var'             => true,
	'rewrite'               => array( 'slug' => 'year' ),
	'show_in_quick_edit'    => false,
);

/**
 * Filters the arguments to be used to register year taxonomy.
 *
 * @since 1.0
 * @param array      $args  Array of arguments used to register year taxonomy.
 * @visible          true
 */
register_taxonomy( 'car_year', 'cars', apply_filters( 'cdhl_cars_taxonomy_year', $args ) );

// Add new taxonomy, NOT hierarchical (like tags).
$labels = array(
	'name'                       => esc_html__( 'Make', 'cardealer-helper' ),
	'singular_name'              => esc_html__( 'Make', 'cardealer-helper' ),
	'search_items'               => esc_html__( 'Search Make', 'cardealer-helper' ),
	'popular_items'              => esc_html__( 'Popular Make', 'cardealer-helper' ),
	'all_items'                  => esc_html__( 'All Make', 'cardealer-helper' ),
	'parent_item'                => null,
	'parent_item_colon'          => null,
	'edit_item'                  => esc_html__( 'Edit Make', 'cardealer-helper' ),
	'update_item'                => esc_html__( 'Update Make', 'cardealer-helper' ),
	'add_new_item'               => esc_html__( 'Add New Make', 'cardealer-helper' ),
	'new_item_name'              => esc_html__( 'New Make Name', 'cardealer-helper' ),
	'separate_items_with_commas' => esc_html__( 'Separate make with commas', 'cardealer-helper' ),
	'add_or_remove_items'        => esc_html__( 'Add or remove Make', 'cardealer-helper' ),
	'choose_from_most_used'      => esc_html__( 'Choose from the most used Make', 'cardealer-helper' ),
	'not_found'                  => esc_html__( 'No make found.', 'cardealer-helper' ),
	'menu_name'                  => esc_html__( 'Make', 'cardealer-helper' ),
);

$args = array(
	'hierarchical'          => false,
	'labels'                => $labels,
	'show_ui'               => true,
	'show_in_menu'          => false,
	'update_count_callback' => '_update_post_term_count',
	'query_var'             => true,
	'rewrite'               => array( 'slug' => 'make' ),
	'show_in_quick_edit'    => false,
);

/**
 * Filters the arguments to be used to register make taxonomy.
 *
 * @since 1.0
 * @param array      $args  Array of arguments used to register make taxonomy.
 * @visible          true
 */
register_taxonomy( 'car_make', 'cars', apply_filters( 'cdhl_cars_taxonomy_make', $args ) );

// Add new taxonomy, NOT hierarchical (like tags).
$labels = array(
	'name'                       => esc_html__( 'Model', 'cardealer-helper' ),
	'singular_name'              => esc_html__( 'Model', 'cardealer-helper' ),
	'search_items'               => esc_html__( 'Search Model', 'cardealer-helper' ),
	'popular_items'              => esc_html__( 'Popular Model', 'cardealer-helper' ),
	'all_items'                  => esc_html__( 'All Model', 'cardealer-helper' ),
	'parent_item'                => null,
	'parent_item_colon'          => null,
	'edit_item'                  => esc_html__( 'Edit Model', 'cardealer-helper' ),
	'update_item'                => esc_html__( 'Update Model', 'cardealer-helper' ),
	'add_new_item'               => esc_html__( 'Add New Model', 'cardealer-helper' ),
	'new_item_name'              => esc_html__( 'New Model Name', 'cardealer-helper' ),
	'separate_items_with_commas' => esc_html__( 'Separate model with commas', 'cardealer-helper' ),
	'add_or_remove_items'        => esc_html__( 'Add or remove Model', 'cardealer-helper' ),
	'choose_from_most_used'      => esc_html__( 'Choose from the most used Model', 'cardealer-helper' ),
	'not_found'                  => esc_html__( 'No model found.', 'cardealer-helper' ),
	'menu_name'                  => esc_html__( 'Model', 'cardealer-helper' ),
);

$args = array(
	'hierarchical'          => false,
	'labels'                => $labels,
	'show_ui'               => true,
	'show_in_menu'          => false,
	'update_count_callback' => '_update_post_term_count',
	'query_var'             => true,
	'rewrite'               => array( 'slug' => 'model' ),
	'show_in_quick_edit'    => false,
);
/**
 * Filters the arguments to be used to register model taxonomy.
 *
 * @since 1.0
 * @param array      $args  Array of arguments used to register model taxonomy.
 * @visible          true
 */
register_taxonomy( 'car_model', 'cars', apply_filters( 'cdhl_cars_taxonomy_model', $args ) );

// Add new taxonomy, NOT hierarchical (like tags).
$labels = array(
	'name'                       => esc_html__( 'Body Style', 'cardealer-helper' ),
	'singular_name'              => esc_html__( 'Body Style', 'cardealer-helper' ),
	'search_items'               => esc_html__( 'Search Body Style', 'cardealer-helper' ),
	'popular_items'              => esc_html__( 'Popular Body Style', 'cardealer-helper' ),
	'all_items'                  => esc_html__( 'All Body Style', 'cardealer-helper' ),
	'parent_item'                => null,
	'parent_item_colon'          => null,
	'edit_item'                  => esc_html__( 'Edit Body Style', 'cardealer-helper' ),
	'update_item'                => esc_html__( 'Update Body Style', 'cardealer-helper' ),
	'add_new_item'               => esc_html__( 'Add New Body Style', 'cardealer-helper' ),
	'new_item_name'              => esc_html__( 'New Body Style Name', 'cardealer-helper' ),
	'separate_items_with_commas' => esc_html__( 'Separate body style with commas', 'cardealer-helper' ),
	'add_or_remove_items'        => esc_html__( 'Add or remove body style', 'cardealer-helper' ),
	'choose_from_most_used'      => esc_html__( 'Choose from the most used body style', 'cardealer-helper' ),
	'not_found'                  => esc_html__( 'No body style found.', 'cardealer-helper' ),
	'menu_name'                  => esc_html__( 'Body Style', 'cardealer-helper' ),
);

$args = array(
	'hierarchical'          => false,
	'labels'                => $labels,
	'show_ui'               => true,
	'show_in_menu'          => false,
	'update_count_callback' => '_update_post_term_count',
	'query_var'             => true,
	'rewrite'               => array( 'slug' => 'body-style' ),
	'show_in_quick_edit'    => false,
);
/**
 * Filters the arguments to be used to register body style taxonomy.
 *
 * @since 1.0
 * @param array      $args  Array of arguments used to register body style taxonomy.
 * @visible          true
 */
register_taxonomy( 'car_body_style', 'cars', apply_filters( 'cdhl_cars_taxonomy_body_style', $args ) );

// Add new taxonomy, NOT hierarchical (like tags).
$labels = array(
	'name'                       => esc_html__( 'Mileage', 'cardealer-helper' ),
	'singular_name'              => esc_html__( 'Mileage', 'cardealer-helper' ),
	'search_items'               => esc_html__( 'Search Mileage', 'cardealer-helper' ),
	'popular_items'              => esc_html__( 'Popular Mileage', 'cardealer-helper' ),
	'all_items'                  => esc_html__( 'All Mileage', 'cardealer-helper' ),
	'parent_item'                => null,
	'parent_item_colon'          => null,
	'edit_item'                  => esc_html__( 'Edit Mileage', 'cardealer-helper' ),
	'update_item'                => esc_html__( 'Update Mileage', 'cardealer-helper' ),
	'add_new_item'               => esc_html__( 'Add New Mileage', 'cardealer-helper' ),
	'new_item_name'              => esc_html__( 'New Mileage Name', 'cardealer-helper' ),
	'separate_items_with_commas' => esc_html__( 'Separate mileage with commas', 'cardealer-helper' ),
	'add_or_remove_items'        => esc_html__( 'Add or remove Mileage', 'cardealer-helper' ),
	'choose_from_most_used'      => esc_html__( 'Choose from the most used Mileage', 'cardealer-helper' ),
	'not_found'                  => esc_html__( 'No mileage found.', 'cardealer-helper' ),
	'menu_name'                  => esc_html__( 'Mileage', 'cardealer-helper' ),
);

$args = array(
	'hierarchical'          => false,
	'labels'                => $labels,
	'show_ui'               => true,
	'show_in_menu'          => false,
	'update_count_callback' => '_update_post_term_count',
	'query_var'             => true,
	'rewrite'               => array( 'slug' => 'mileage' ),
	'show_in_quick_edit'    => false,
);
/**
 * Filters the arguments to be used to register mileage taxonomy.
 *
 * @since 1.0
 * @param array      $args  Array of arguments used to register mileage taxonomy.
 * @visible          true
 */
register_taxonomy( 'car_mileage', 'cars', apply_filters( 'cdhl_cars_taxonomy_mileage', $args ) );

// Add new taxonomy, NOT hierarchical (like tags).
$labels = array(
	'name'                       => esc_html__( 'Transmission', 'cardealer-helper' ),
	'singular_name'              => esc_html__( 'Transmission', 'cardealer-helper' ),
	'search_items'               => esc_html__( 'Search Transmission', 'cardealer-helper' ),
	'popular_items'              => esc_html__( 'Popular Transmission', 'cardealer-helper' ),
	'all_items'                  => esc_html__( 'All Transmission', 'cardealer-helper' ),
	'parent_item'                => null,
	'parent_item_colon'          => null,
	'edit_item'                  => esc_html__( 'Edit Transmission', 'cardealer-helper' ),
	'update_item'                => esc_html__( 'Update Transmission', 'cardealer-helper' ),
	'add_new_item'               => esc_html__( 'Add New Transmission', 'cardealer-helper' ),
	'new_item_name'              => esc_html__( 'New Transmission Name', 'cardealer-helper' ),
	'separate_items_with_commas' => esc_html__( 'Separate transmission with commas', 'cardealer-helper' ),
	'add_or_remove_items'        => esc_html__( 'Add or remove Transmission', 'cardealer-helper' ),
	'choose_from_most_used'      => esc_html__( 'Choose from the most used Transmission', 'cardealer-helper' ),
	'not_found'                  => esc_html__( 'No transmission found.', 'cardealer-helper' ),
	'menu_name'                  => esc_html__( 'Transmission', 'cardealer-helper' ),
);

$args = array(
	'hierarchical'          => false,
	'labels'                => $labels,
	'show_ui'               => true,
	'show_in_menu'          => false,
	'update_count_callback' => '_update_post_term_count',
	'query_var'             => true,
	'rewrite'               => array( 'slug' => 'transmission' ),
	'show_in_quick_edit'    => false,
);
/**
 * Filters the arguments to be used to register transmission taxonomy.
 *
 * @since 1.0
 * @param array      $args  Array of arguments used to register transmission taxonomy.
 * @visible          true
 */
register_taxonomy( 'car_transmission', 'cars', apply_filters( 'cdhl_cars_taxonomy_transmission', $args ) );

// Add new taxonomy, NOT hierarchical (like tags).
$labels = array(
	'name'                       => esc_html__( 'Condition', 'cardealer-helper' ),
	'singular_name'              => esc_html__( 'Condition', 'cardealer-helper' ),
	'search_items'               => esc_html__( 'Search Condition', 'cardealer-helper' ),
	'popular_items'              => esc_html__( 'Popular Condition', 'cardealer-helper' ),
	'all_items'                  => esc_html__( 'All Condition', 'cardealer-helper' ),
	'parent_item'                => null,
	'parent_item_colon'          => null,
	'edit_item'                  => esc_html__( 'Edit Condition', 'cardealer-helper' ),
	'update_item'                => esc_html__( 'Update Condition', 'cardealer-helper' ),
	'add_new_item'               => esc_html__( 'Add New Condition', 'cardealer-helper' ),
	'new_item_name'              => esc_html__( 'New Condition Name', 'cardealer-helper' ),
	'separate_items_with_commas' => esc_html__( 'Separate condition with commas', 'cardealer-helper' ),
	'add_or_remove_items'        => esc_html__( 'Add or remove Condition', 'cardealer-helper' ),
	'choose_from_most_used'      => esc_html__( 'Choose from the most used Condition', 'cardealer-helper' ),
	'not_found'                  => esc_html__( 'No condition found.', 'cardealer-helper' ),
	'menu_name'                  => esc_html__( 'Condition', 'cardealer-helper' ),
);

$args = array(
	'hierarchical'          => false,
	'labels'                => $labels,
	'show_ui'               => true,
	'show_in_menu'          => false,
	'update_count_callback' => '_update_post_term_count',
	'query_var'             => true,
	'rewrite'               => array( 'slug' => 'condition' ),
	'show_in_quick_edit'    => false,
);
/**
 * Filters the arguments to be used to register condition taxonomy.
 *
 * @since 1.0
 * @param array      $args  Array of arguments used to register condition taxonomy.
 * @visible          true
 */
register_taxonomy( 'car_condition', 'cars', apply_filters( 'cdhl_cars_taxonomy_condition', $args ) );

// Add new taxonomy, NOT hierarchical (like tags).
$labels = array(
	'name'                       => esc_html__( 'Drivetrain', 'cardealer-helper' ),
	'singular_name'              => esc_html__( 'Drivetrain', 'cardealer-helper' ),
	'search_items'               => esc_html__( 'Search Drivetrain', 'cardealer-helper' ),
	'popular_items'              => esc_html__( 'Popular Drivetrain', 'cardealer-helper' ),
	'all_items'                  => esc_html__( 'All Drivetrain', 'cardealer-helper' ),
	'parent_item'                => null,
	'parent_item_colon'          => null,
	'edit_item'                  => esc_html__( 'Edit Drivetrain', 'cardealer-helper' ),
	'update_item'                => esc_html__( 'Update Drivetrain', 'cardealer-helper' ),
	'add_new_item'               => esc_html__( 'Add New Drivetrain', 'cardealer-helper' ),
	'new_item_name'              => esc_html__( 'New Drivetrain Name', 'cardealer-helper' ),
	'separate_items_with_commas' => esc_html__( 'Separate drivetrain with commas', 'cardealer-helper' ),
	'add_or_remove_items'        => esc_html__( 'Add or remove Drivetrain', 'cardealer-helper' ),
	'choose_from_most_used'      => esc_html__( 'Choose from the most used Drivetrain', 'cardealer-helper' ),
	'not_found'                  => esc_html__( 'No drivetrain found.', 'cardealer-helper' ),
	'menu_name'                  => esc_html__( 'Drivetrain', 'cardealer-helper' ),
);

$args = array(
	'hierarchical'          => false,
	'labels'                => $labels,
	'show_ui'               => true,
	'show_in_menu'          => false,
	'update_count_callback' => '_update_post_term_count',
	'query_var'             => true,
	'rewrite'               => array( 'slug' => 'drivetrain' ),
	'show_in_quick_edit'    => false,
);
/**
 * Filters the arguments to be used to register drivetrain taxonomy.
 *
 * @since 1.0
 * @param array      $args  Array of arguments used to register drivetrain taxonomy.
 * @visible          true
 */
register_taxonomy( 'car_drivetrain', 'cars', apply_filters( 'cdhl_cars_taxonomy_drivetrain', $args ) );

// Add new taxonomy, NOT hierarchical (like tags).
$labels = array(
	'name'                       => esc_html__( 'Engine', 'cardealer-helper' ),
	'singular_name'              => esc_html__( 'Engine', 'cardealer-helper' ),
	'search_items'               => esc_html__( 'Search Engine', 'cardealer-helper' ),
	'popular_items'              => esc_html__( 'Popular Engine', 'cardealer-helper' ),
	'all_items'                  => esc_html__( 'All Engine', 'cardealer-helper' ),
	'parent_item'                => null,
	'parent_item_colon'          => null,
	'edit_item'                  => esc_html__( 'Edit Engine', 'cardealer-helper' ),
	'update_item'                => esc_html__( 'Update Engine', 'cardealer-helper' ),
	'add_new_item'               => esc_html__( 'Add New Engine', 'cardealer-helper' ),
	'new_item_name'              => esc_html__( 'New Engine Name', 'cardealer-helper' ),
	'separate_items_with_commas' => esc_html__( 'Separate engine with commas', 'cardealer-helper' ),
	'add_or_remove_items'        => esc_html__( 'Add or remove Engine', 'cardealer-helper' ),
	'choose_from_most_used'      => esc_html__( 'Choose from the most used Engine', 'cardealer-helper' ),
	'not_found'                  => esc_html__( 'No engine found.', 'cardealer-helper' ),
	'menu_name'                  => esc_html__( 'Engine', 'cardealer-helper' ),
);

$args = array(
	'hierarchical'          => false,
	'labels'                => $labels,
	'show_ui'               => true,
	'show_in_menu'          => false,
	'update_count_callback' => '_update_post_term_count',
	'query_var'             => true,
	'rewrite'               => array( 'slug' => 'engine' ),
	'show_in_quick_edit'    => false,
);
/**
 * Filters the arguments to be used to register engine taxonomy.
 *
 * @since 1.0
 * @param array      $args  Array of arguments used to register engine taxonomy.
 * @visible          true
 */
register_taxonomy( 'car_engine', 'cars', apply_filters( 'cdhl_cars_taxonomy_engine', $args ) );

// Add new taxonomy, NOT hierarchical (like tags).
$labels = array(
	'name'                       => esc_html__( 'Fuel Economy', 'cardealer-helper' ),
	'singular_name'              => esc_html__( 'Fuel Economy', 'cardealer-helper' ),
	'search_items'               => esc_html__( 'Search Fuel Economy', 'cardealer-helper' ),
	'popular_items'              => esc_html__( 'Popular Fuel Economy', 'cardealer-helper' ),
	'all_items'                  => esc_html__( 'All Fuel Economy', 'cardealer-helper' ),
	'parent_item'                => null,
	'parent_item_colon'          => null,
	'edit_item'                  => esc_html__( 'Edit Fuel Economy', 'cardealer-helper' ),
	'update_item'                => esc_html__( 'Update Fuel Economy', 'cardealer-helper' ),
	'add_new_item'               => esc_html__( 'Add New Fuel Economy', 'cardealer-helper' ),
	'new_item_name'              => esc_html__( 'New Fuel Economy Name', 'cardealer-helper' ),
	'separate_items_with_commas' => esc_html__( 'Separate fuel-economy with commas', 'cardealer-helper' ),
	'add_or_remove_items'        => esc_html__( 'Add or remove Fuel Economy', 'cardealer-helper' ),
	'choose_from_most_used'      => esc_html__( 'Choose from the most used Fuel Economy', 'cardealer-helper' ),
	'not_found'                  => esc_html__( 'No fuel-economy found.', 'cardealer-helper' ),
	'menu_name'                  => esc_html__( 'Fuel Economy', 'cardealer-helper' ),
);

$args = array(
	'hierarchical'          => false,
	'labels'                => $labels,
	'show_ui'               => true,
	'show_in_menu'          => false,
	'update_count_callback' => '_update_post_term_count',
	'query_var'             => true,
	'rewrite'               => array( 'slug' => 'fuel-economy' ),
	'show_in_quick_edit'    => false,
);
/**
 * Filters the arguments to be used to register fuel economy taxonomy.
 *
 * @since 1.0
 * @param array      $args  Array of arguments used to register fuel economy taxonomy.
 * @visible          true
 */
register_taxonomy( 'car_fuel_economy', 'cars', apply_filters( 'cdhl_cars_taxonomy_fuel_economy', $args ) );

// Add new taxonomy, NOT hierarchical (like tags).
$labels = array(
	'name'                       => esc_html__( 'Exterior Color', 'cardealer-helper' ),
	'singular_name'              => esc_html__( 'Exterior Color', 'cardealer-helper' ),
	'search_items'               => esc_html__( 'Search Exterior Color', 'cardealer-helper' ),
	'popular_items'              => esc_html__( 'Popular Exterior Color', 'cardealer-helper' ),
	'all_items'                  => esc_html__( 'All Exterior Color', 'cardealer-helper' ),
	'parent_item'                => null,
	'parent_item_colon'          => null,
	'edit_item'                  => esc_html__( 'Edit Exterior Color', 'cardealer-helper' ),
	'update_item'                => esc_html__( 'Update Exterior Color', 'cardealer-helper' ),
	'add_new_item'               => esc_html__( 'Add New Exterior Color', 'cardealer-helper' ),
	'new_item_name'              => esc_html__( 'New Exterior Color Name', 'cardealer-helper' ),
	'separate_items_with_commas' => esc_html__( 'Separate exterior-color with commas', 'cardealer-helper' ),
	'add_or_remove_items'        => esc_html__( 'Add or remove Exterior Color', 'cardealer-helper' ),
	'choose_from_most_used'      => esc_html__( 'Choose from the most used Exterior Color', 'cardealer-helper' ),
	'not_found'                  => esc_html__( 'No exterior-color found.', 'cardealer-helper' ),
	'menu_name'                  => esc_html__( 'Exterior Color', 'cardealer-helper' ),
);

$args = array(
	'hierarchical'          => false,
	'labels'                => $labels,
	'show_ui'               => true,
	'show_in_menu'          => false,
	'update_count_callback' => '_update_post_term_count',
	'query_var'             => true,
	'rewrite'               => array( 'slug' => 'exterior-color' ),
	'show_in_quick_edit'    => false,
);
/**
 * Filters the arguments to be used to register exterior color taxonomy.
 *
 * @since 1.0
 * @param array      $args  Array of arguments used to register exterior color taxonomy.
 * @visible          true
 */
register_taxonomy( 'car_exterior_color', 'cars', apply_filters( 'cdhl_cars_taxonomy_exterior_color', $args ) );

// Add new taxonomy, NOT hierarchical (like tags).
$labels = array(
	'name'                       => esc_html__( 'Interior Color', 'cardealer-helper' ),
	'singular_name'              => esc_html__( 'Interior Color', 'cardealer-helper' ),
	'search_items'               => esc_html__( 'Search Interior Color', 'cardealer-helper' ),
	'popular_items'              => esc_html__( 'Popular Interior Color', 'cardealer-helper' ),
	'all_items'                  => esc_html__( 'All Interior Color', 'cardealer-helper' ),
	'parent_item'                => null,
	'parent_item_colon'          => null,
	'edit_item'                  => esc_html__( 'Edit Interior Color', 'cardealer-helper' ),
	'update_item'                => esc_html__( 'Update Interior Color', 'cardealer-helper' ),
	'add_new_item'               => esc_html__( 'Add New Interior Color', 'cardealer-helper' ),
	'new_item_name'              => esc_html__( 'New Interior Color Name', 'cardealer-helper' ),
	'separate_items_with_commas' => esc_html__( 'Separate interior-color with commas', 'cardealer-helper' ),
	'add_or_remove_items'        => esc_html__( 'Add or remove Interior Color', 'cardealer-helper' ),
	'choose_from_most_used'      => esc_html__( 'Choose from the most used Interior Color', 'cardealer-helper' ),
	'not_found'                  => esc_html__( 'No interior-color found.', 'cardealer-helper' ),
	'menu_name'                  => esc_html__( 'Interior Color', 'cardealer-helper' ),
);

$args = array(
	'hierarchical'          => false,
	'labels'                => $labels,
	'show_ui'               => true,
	'show_in_menu'          => false,
	'update_count_callback' => '_update_post_term_count',
	'query_var'             => true,
	'rewrite'               => array( 'slug' => 'interior-color' ),
	'show_in_quick_edit'    => false,
);
/**
 * Filters the arguments to be used to register interior color taxonomy.
 *
 * @since 1.0
 * @param array      $args  Array of arguments used to register interior color taxonomy.
 * @visible          true
 */
register_taxonomy( 'car_interior_color', 'cars', apply_filters( 'cdhl_cars_taxonomy_interior_color', $args ) );

// Add new taxonomy, NOT hierarchical (like tags).
$labels = array(
	'name'                       => esc_html__( 'Stock Number', 'cardealer-helper' ),
	'singular_name'              => esc_html__( 'Stock Number', 'cardealer-helper' ),
	'search_items'               => esc_html__( 'Search Stock Number', 'cardealer-helper' ),
	'popular_items'              => esc_html__( 'Popular Stock Number', 'cardealer-helper' ),
	'all_items'                  => esc_html__( 'All Stock Number', 'cardealer-helper' ),
	'parent_item'                => null,
	'parent_item_colon'          => null,
	'edit_item'                  => esc_html__( 'Edit Stock Number', 'cardealer-helper' ),
	'update_item'                => esc_html__( 'Update Stock Number', 'cardealer-helper' ),
	'add_new_item'               => esc_html__( 'Add New Stock Number', 'cardealer-helper' ),
	'new_item_name'              => esc_html__( 'New Stock Number Name', 'cardealer-helper' ),
	'separate_items_with_commas' => esc_html__( 'Separate stock-number with commas', 'cardealer-helper' ),
	'add_or_remove_items'        => esc_html__( 'Add or remove Stock Number', 'cardealer-helper' ),
	'choose_from_most_used'      => esc_html__( 'Choose from the most used Stock Number', 'cardealer-helper' ),
	'not_found'                  => esc_html__( 'No stock-number found.', 'cardealer-helper' ),
	'menu_name'                  => esc_html__( 'Stock Number', 'cardealer-helper' ),
);

$args = array(
	'hierarchical'          => false,
	'labels'                => $labels,
	'show_ui'               => true,
	'show_in_menu'          => false,
	'update_count_callback' => '_update_post_term_count',
	'query_var'             => true,
	'rewrite'               => array( 'slug' => 'stock-number' ),
	'show_in_quick_edit'    => false,
);
/**
 * Filters the arguments to be used to register stock number taxonomy.
 *
 * @since 1.0
 * @param array      $args  Array of arguments used to register stock number taxonomy.
 * @visible          true
 */
register_taxonomy( 'car_stock_number', 'cars', apply_filters( 'cdhl_cars_taxonomy_stock_number', $args ) );

// Add new taxonomy, NOT hierarchical (like tags).
$labels = array(
	'name'                       => esc_html__( 'VIN Number', 'cardealer-helper' ),
	'singular_name'              => esc_html__( 'VIN Number', 'cardealer-helper' ),
	'search_items'               => esc_html__( 'Search VIN Number', 'cardealer-helper' ),
	'popular_items'              => esc_html__( 'Popular VIN Number', 'cardealer-helper' ),
	'all_items'                  => esc_html__( 'All VIN Number', 'cardealer-helper' ),
	'parent_item'                => null,
	'parent_item_colon'          => null,
	'edit_item'                  => esc_html__( 'Edit VIN Number', 'cardealer-helper' ),
	'update_item'                => esc_html__( 'Update VIN Number', 'cardealer-helper' ),
	'add_new_item'               => esc_html__( 'Add New VIN Number', 'cardealer-helper' ),
	'new_item_name'              => esc_html__( 'New VIN Number Name', 'cardealer-helper' ),
	'separate_items_with_commas' => esc_html__( 'Separate vin-number with commas', 'cardealer-helper' ),
	'add_or_remove_items'        => esc_html__( 'Add or remove VIN Number', 'cardealer-helper' ),
	'choose_from_most_used'      => esc_html__( 'Choose from the most used VIN Number', 'cardealer-helper' ),
	'not_found'                  => esc_html__( 'No vin-number found.', 'cardealer-helper' ),
	'menu_name'                  => esc_html__( 'VIN Number', 'cardealer-helper' ),
);

$args = array(
	'hierarchical'          => false,
	'labels'                => $labels,
	'show_ui'               => true,
	'show_in_menu'          => false,
	'update_count_callback' => '_update_post_term_count',
	'query_var'             => true,
	'rewrite'               => array( 'slug' => 'vin-number' ),
	'show_in_quick_edit'    => false,
);
/**
 * Filters the arguments to be used to register VIN number taxonomy.
 *
 * @since 1.0
 * @param array      $args  Array of arguments used to register vin number taxonomy.
 * @visible          true
 */
register_taxonomy( 'car_vin_number', 'cars', apply_filters( 'cdhl_cars_taxonomy_vin_number', $args ) );

$labels = array(
	'name'                       => esc_html__( 'Fuel Type', 'cardealer-helper' ),
	'singular_name'              => esc_html__( 'Fuel Type', 'cardealer-helper' ),
	'search_items'               => esc_html__( 'Search Fuel Type', 'cardealer-helper' ),
	'popular_items'              => esc_html__( 'Popular Fuel Type', 'cardealer-helper' ),
	'all_items'                  => esc_html__( 'All Fuel Type', 'cardealer-helper' ),
	'parent_item'                => null,
	'parent_item_colon'          => null,
	'edit_item'                  => esc_html__( 'Edit Fuel Type', 'cardealer-helper' ),
	'update_item'                => esc_html__( 'Update Fuel Type', 'cardealer-helper' ),
	'add_new_item'               => esc_html__( 'Add New Fuel Type', 'cardealer-helper' ),
	'new_item_name'              => esc_html__( 'New Fuel Type Name', 'cardealer-helper' ),
	'separate_items_with_commas' => esc_html__( 'Separate fuel-type with commas', 'cardealer-helper' ),
	'add_or_remove_items'        => esc_html__( 'Add or remove Fuel Type', 'cardealer-helper' ),
	'choose_from_most_used'      => esc_html__( 'Choose from the most used Fuel Type', 'cardealer-helper' ),
	'not_found'                  => esc_html__( 'No fuel-type found.', 'cardealer-helper' ),
	'menu_name'                  => esc_html__( 'Fuel Type', 'cardealer-helper' ),
);

$args = array(
	'hierarchical'          => false,
	'labels'                => $labels,
	'show_ui'               => true,
	'show_in_menu'          => false,
	'update_count_callback' => '_update_post_term_count',
	'query_var'             => true,
	'rewrite'               => array( 'slug' => 'fuel-type' ),
	'show_in_quick_edit'    => false,
);
/**
 * Filters the arguments to be used to register fuel type taxonomy.
 *
 * @since 1.0
 * @param array      $args  Array of arguments used to register fuel type taxonomy.
 * @visible          true
 */
register_taxonomy( 'car_fuel_type', 'cars', apply_filters( 'cdhl_cars_taxonomy_fuel_type', $args ) );

$labels = array(
	'name'                       => esc_html__( 'Trim', 'cardealer-helper' ),
	'singular_name'              => esc_html__( 'Trim', 'cardealer-helper' ),
	'search_items'               => esc_html__( 'Search Trim', 'cardealer-helper' ),
	'popular_items'              => esc_html__( 'Popular Trim', 'cardealer-helper' ),
	'all_items'                  => esc_html__( 'All Trim', 'cardealer-helper' ),
	'parent_item'                => null,
	'parent_item_colon'          => null,
	'edit_item'                  => esc_html__( 'Edit Trim', 'cardealer-helper' ),
	'update_item'                => esc_html__( 'Update Trim', 'cardealer-helper' ),
	'add_new_item'               => esc_html__( 'Add New Trim', 'cardealer-helper' ),
	'new_item_name'              => esc_html__( 'New Trim Name', 'cardealer-helper' ),
	'separate_items_with_commas' => esc_html__( 'Separate trim-type with commas', 'cardealer-helper' ),
	'add_or_remove_items'        => esc_html__( 'Add or remove Trim', 'cardealer-helper' ),
	'choose_from_most_used'      => esc_html__( 'Choose from the most used Trim', 'cardealer-helper' ),
	'not_found'                  => esc_html__( 'No trim found.', 'cardealer-helper' ),
	'menu_name'                  => esc_html__( 'Trim', 'cardealer-helper' ),
);

$args = array(
	'hierarchical'          => false,
	'labels'                => $labels,
	'show_ui'               => true,
	'show_in_menu'          => false,
	'update_count_callback' => '_update_post_term_count',
	'query_var'             => true,
	'rewrite'               => array( 'slug' => 'trim' ),
	'show_in_quick_edit'    => false,
);
/**
 * Filters the arguments to be used to register trim taxonomy.
 *
 * @since 1.0
 * @param array      $args  Array of arguments used to register trim taxonomy.
 * @visible          true
 */
register_taxonomy( 'car_trim', 'cars', apply_filters( 'cdhl_cars_taxonomy_trim', $args ) );

// Add new taxonomy, NOT hierarchical (like tags).
$labels = array(
	'name'                       => esc_html__( 'Features & Options', 'cardealer-helper' ),
	'singular_name'              => esc_html__( 'Features & Options', 'cardealer-helper' ),
	'search_items'               => esc_html__( 'Search Features & Options', 'cardealer-helper' ),
	'popular_items'              => esc_html__( 'Popular Features & Options', 'cardealer-helper' ),
	'all_items'                  => esc_html__( 'All Features & Options', 'cardealer-helper' ),
	'parent_item'                => esc_html__( 'Parent Feature', 'cardealer-helper' ),
	'parent_item_colon'          => null,
	'edit_item'                  => esc_html__( 'Edit Features & Options', 'cardealer-helper' ),
	'update_item'                => esc_html__( 'Update Features & Options', 'cardealer-helper' ),
	'add_new_item'               => esc_html__( 'Add New Features & Options', 'cardealer-helper' ),
	'new_item_name'              => esc_html__( 'New Features & Options Name', 'cardealer-helper' ),
	'separate_items_with_commas' => esc_html__( 'Separate features-options with commas', 'cardealer-helper' ),
	'add_or_remove_items'        => esc_html__( 'Add or remove Features & Options', 'cardealer-helper' ),
	'choose_from_most_used'      => esc_html__( 'Choose from the most used Features & Options', 'cardealer-helper' ),
	'not_found'                  => esc_html__( 'No features-options found.', 'cardealer-helper' ),
	'menu_name'                  => esc_html__( 'Features & Options', 'cardealer-helper' ),
);

$args = array(
	'hierarchical'          => true,
	'labels'                => $labels,
	'show_ui'               => true,
	'show_in_menu'          => false,
	'update_count_callback' => '_update_post_term_count',
	'query_var'             => true,
	'rewrite'               => array( 'slug' => 'features-options' ),
);

/**
 * Filters the arguments to be used to register feature and options taxonomy.
 *
 * @since 1.0
 * @param array      $args  Array of arguments used to register feature and options taxonomy.
 * @visible          true
 */
register_taxonomy( 'car_features_options', 'cars', apply_filters( 'cdhl_cars_taxonomy_features_options', $args ) );

// Add new taxonomy, NOT hierarchical (like tags).
$labels = array(
	'name'                       => esc_html__( 'Vehicle Review Stamps', 'cardealer-helper' ),
	'singular_name'              => esc_html__( 'Vehicle Review Stamps', 'cardealer-helper' ),
	'search_items'               => esc_html__( 'Search Vehicle Review Stamps', 'cardealer-helper' ),
	'popular_items'              => esc_html__( 'Popular Vehicle Review Stamps', 'cardealer-helper' ),
	'all_items'                  => esc_html__( 'All Vehicle Review Stamps', 'cardealer-helper' ),
	'parent_item'                => null,
	'parent_item_colon'          => null,
	'edit_item'                  => esc_html__( 'Edit Vehicle Review Stamps', 'cardealer-helper' ),
	'update_item'                => esc_html__( 'Update Vehicle Review Stamps', 'cardealer-helper' ),
	'add_new_item'               => esc_html__( 'Add New Vehicle Review Stamps', 'cardealer-helper' ),
	'new_item_name'              => esc_html__( 'New Vehicle Review Stamps Name', 'cardealer-helper' ),
	'separate_items_with_commas' => esc_html__( 'Separate Vehicle Review Stamps with commas', 'cardealer-helper' ),
	'add_or_remove_items'        => esc_html__( 'Add or remove Vehicle Review Stamps', 'cardealer-helper' ),
	'choose_from_most_used'      => esc_html__( 'Choose from the most used Vehicle Review Stamps', 'cardealer-helper' ),
	'not_found'                  => esc_html__( 'No Vehicle Review Stamps found.', 'cardealer-helper' ),
	'menu_name'                  => esc_html__( 'Vehicle Review Stamps', 'cardealer-helper' ),
);

$args = array(
	'hierarchical'          => true,
	'labels'                => $labels,
	'show_ui'               => true,
	'show_in_menu'          => false,
	'update_count_callback' => '_update_post_term_count',
	'query_var'             => false,
	'rewrite'               => false,
);
/**
 * Filters the arguments to be used to register vehicle review stamps taxonomy.
 *
 * @since 1.0
 * @param array      $args  Array of arguments used to register vehicle review stamps taxonomy.
 * @visible          true
 */
register_taxonomy( 'car_vehicle_review_stamps', 'cars', apply_filters( 'cdhl_cars_taxonomy_vehicle_review_stamps', $args ) );
