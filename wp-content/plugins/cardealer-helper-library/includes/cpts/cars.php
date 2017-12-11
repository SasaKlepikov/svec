<?php
/*
 * Register Post Type for Cars
 */
add_action( 'init', 'cdhl_cars_cpt',1);
function cdhl_cars_cpt() {
	$labels = array(
		'name'               => esc_html_x( 'Cars', 'post type general name', 'cardealer-helper' ),
		'singular_name'      => esc_html_x( 'Cars', 'post type singular name', 'cardealer-helper' ),
		'menu_name'          => esc_html_x( 'Vehicle Inventory', 'admin menu', 'cardealer-helper' ),
		'name_admin_bar'     => esc_html_x( 'Cars', 'add new on admin bar', 'cardealer-helper' ),
		'add_new'            => esc_html_x( 'Add New', 'cars', 'cardealer-helper' ),
		'add_new_item'       => esc_html__( 'Add New Cars', 'cardealer-helper' ),
		'new_item'           => esc_html__( 'New Cars', 'cardealer-helper' ),
		'edit_item'          => esc_html__( 'Edit Cars', 'cardealer-helper' ),
		'view_item'          => esc_html__( 'View Cars', 'cardealer-helper' ),
		'all_items'          => esc_html__( 'All Cars', 'cardealer-helper' ),
		'search_items'       => esc_html__( 'Search Cars', 'cardealer-helper' ),
		'parent_item_colon'  => esc_html__( 'Parent Cars:', 'cardealer-helper' ),
		'not_found'          => esc_html__( 'No cars found.', 'cardealer-helper' ),
		'not_found_in_trash' => esc_html__( 'No cars found in Trash.', 'cardealer-helper' )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => esc_html__( 'Description.', 'cardealer-helper' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'cars' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'author', 'excerpt' ),
		'menu_icon'			 => 'dashicons-pressthis'
	);

	register_post_type( 'cars', apply_filters( 'cdhl_cars_cpt_cars', $args ) );	
    
    
    // Add new taxonomy, NOT hierarchical (like tags)
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
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'year' ),
	);

	register_taxonomy( 'car_year', 'cars', apply_filters( 'cdhl_cars_taxonomy_year', $args ) );
    
    // Add new taxonomy, NOT hierarchical (like tags)
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
    	'update_count_callback' => '_update_post_term_count',
    	'query_var'             => true,
    	'rewrite'               => array( 'slug' => 'make' ),
    );
    
    register_taxonomy( 'car_make', 'cars', apply_filters( 'cdhl_cars_taxonomy_make', $args ) );
    
    // Add new taxonomy, NOT hierarchical (like tags)
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
    	'update_count_callback' => '_update_post_term_count',
    	'query_var'             => true,
    	'rewrite'               => array( 'slug' => 'model' ),
    );
    
    register_taxonomy( 'car_model', 'cars', apply_filters( 'cdhl_cars_taxonomy_model', $args ) );
    
    
    // Add new taxonomy, NOT hierarchical (like tags)
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
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'body-style' ),
	);

	register_taxonomy( 'car_body_style', 'cars', apply_filters( 'cdhl_cars_taxonomy_body_style', $args ) ); 
    
    // Add new taxonomy, NOT hierarchical (like tags)
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
    	'update_count_callback' => '_update_post_term_count',
    	'query_var'             => true,
    	'rewrite'               => array( 'slug' => 'mileage' ),
    );
    
    register_taxonomy( 'car_mileage', 'cars', apply_filters( 'cdhl_cars_taxonomy_mileage', $args ) );
    
    // Add new taxonomy, NOT hierarchical (like tags)
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
    	'update_count_callback' => '_update_post_term_count',
    	'query_var'             => true,
    	'rewrite'               => array( 'slug' => 'transmission' ),
    );
    
    register_taxonomy( 'car_transmission', 'cars', apply_filters( 'cdhl_cars_taxonomy_transmission', $args ) );
    
    
    // Add new taxonomy, NOT hierarchical (like tags)
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
    	'update_count_callback' => '_update_post_term_count',
    	'query_var'             => true,
    	'rewrite'               => array( 'slug' => 'condition' ),
    );
    
    register_taxonomy( 'car_condition', 'cars', apply_filters( 'cdhl_cars_taxonomy_condition', $args ) );
    
    
    // Add new taxonomy, NOT hierarchical (like tags)
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
    	'update_count_callback' => '_update_post_term_count',
    	'query_var'             => true,
    	'rewrite'               => array( 'slug' => 'drivetrain' ),
    );
    
    register_taxonomy( 'car_drivetrain', 'cars', apply_filters( 'cdhl_cars_taxonomy_drivetrain', $args  ) );
    
    // Add new taxonomy, NOT hierarchical (like tags)
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
    	'update_count_callback' => '_update_post_term_count',
    	'query_var'             => true,
    	'rewrite'               => array( 'slug' => 'engine' ),
    );
    
    register_taxonomy( 'car_engine', 'cars', apply_filters( 'cdhl_cars_taxonomy_engine', $args ) );
    
    
    // Add new taxonomy, NOT hierarchical (like tags)
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
    	'update_count_callback' => '_update_post_term_count',
    	'query_var'             => true,
    	'rewrite'               => array( 'slug' => 'fuel-economy' ),
    );
    
    register_taxonomy( 'car_fuel_economy', 'cars', apply_filters( 'cdhl_cars_taxonomy_fuel_economy', $args ) );
    
    
    // Add new taxonomy, NOT hierarchical (like tags)
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
    	'update_count_callback' => '_update_post_term_count',
    	'query_var'             => true,
    	'rewrite'               => array( 'slug' => 'exterior-color' ),
    );
    
    register_taxonomy( 'car_exterior_color', 'cars', apply_filters( 'cdhl_cars_taxonomy_exterior_color', $args ) );
    
    
    // Add new taxonomy, NOT hierarchical (like tags)
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
    	'update_count_callback' => '_update_post_term_count',
    	'query_var'             => true,
    	'rewrite'               => array( 'slug' => 'interior-color' ),
    );
    
    register_taxonomy( 'car_interior_color', 'cars', apply_filters( 'cdhl_cars_taxonomy_interior_color', $args ) );
    
    // Add new taxonomy, NOT hierarchical (like tags)
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
    	'update_count_callback' => '_update_post_term_count',
    	'query_var'             => true,
    	'rewrite'               => array( 'slug' => 'stock-number' ),
    );
    
    register_taxonomy( 'car_stock_number', 'cars', apply_filters( 'cdhl_cars_taxonomy_stock_number', $args ) );
    
    // Add new taxonomy, NOT hierarchical (like tags)
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
    	'update_count_callback' => '_update_post_term_count',
    	'query_var'             => true,
    	'rewrite'               => array( 'slug' => 'vin-number' ),
    );
    
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
    	'update_count_callback' => '_update_post_term_count',
    	'query_var'             => true,
    	'rewrite'               => array( 'slug' => 'fuel-type' ),
    );
    
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
    	'update_count_callback' => '_update_post_term_count',
    	'query_var'             => true,
    	'rewrite'               => array( 'slug' => 'trim' ),
    );
    
    register_taxonomy( 'car_trim', 'cars', apply_filters( 'cdhl_cars_taxonomy_trim', $args ) );   
    
    
    
    // Add new taxonomy, NOT hierarchical (like tags)
    $labels = array(
    	'name'                       => esc_html__( 'Features & Options', 'cardealer-helper' ),
    	'singular_name'              => esc_html__( 'Features & Options', 'cardealer-helper' ),
    	'search_items'               => esc_html__( 'Search Features & Options', 'cardealer-helper' ),
    	'popular_items'              => esc_html__( 'Popular Features & Options', 'cardealer-helper' ),
    	'all_items'                  => esc_html__( 'All Features & Options', 'cardealer-helper' ),
    	'parent_item'                => null,
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
    	'hierarchical'          => false,
    	'labels'                => $labels,
    	'show_ui'               => true,
    	'update_count_callback' => '_update_post_term_count',
    	'query_var'             => true,
    	'rewrite'               => array( 'slug' => 'features-options' ),
    );
    
    register_taxonomy( 'car_features_options', 'cars', apply_filters( 'cdhl_cars_taxonomy_features_options', $args ) );
	
    // Add new taxonomy, NOT hierarchical (like tags)
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
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => false,
		'rewrite'               => false,
	);

	register_taxonomy( 'car_vehicle_review_stamps', 'cars', apply_filters( 'cdhl_cars_taxonomy_vehicle_review_stamps', $args ) );                
}

function theme_columns($theme_columns) {

    $new_columns = array(
        'cb' => '<input type="checkbox" />',
        'name' => __('Name'),
        'image' => __('Image'),
        'url' => __('Url'),
        'slug' => __('Slug'),
        'posts' => __('Count')
        );
    return $new_columns;
    
}
add_filter("manage_edit-car_vehicle_review_stamps_columns", 'theme_columns');


function add_car_vehicle_review_stamps_content($content,$column_name,$term_id){    
    $url = '';$imageurl='';            
    if(!empty($term_id)){
        $image = get_term_meta($term_id,'image');            
        $image_url = wp_get_attachment_url( $image[0] );        
        $imageurl = '<img src="'.$image_url.'" width="60px" height="60px"/>'; 
        $url_arr = get_term_meta($term_id,'url');            
        if(isset($url_arr[0]) && !empty($url_arr[0])){
            $url = $url_arr[0];               
        }            
    }
     
    switch ($column_name) {
        case 'image':
            //do your stuff here with $term or $term_id
            $content = $imageurl;
            break;
        case 'url':
            //do your stuff here with $term or $term_id
            $content = $url;
            break;
        default:
            break;
    }
    return $content;
}
add_filter('manage_car_vehicle_review_stamps_custom_column', 'add_car_vehicle_review_stamps_content',10,3);


/*
 * Edit columns
 */
function cdhl_cpt_cars_edit_columns($columns)
{
	unset($columns['author']);
    $newFields = 
		array_slice(
        $columns, 0, 2, true) +
        array(                       
            "price" => esc_html__( 'Price', 'cardealer-helper' ),
    		"cars_stock_number" => esc_html__( 'Stock Number', 'cardealer-helper' ),
            "car_vin_number" => esc_html__( 'VIN Number', 'cardealer-helper' ),
            "featured" => esc_html__( 'Featured', 'cardealer-helper' ),
			"auto_trader" => esc_html__( 'Auto Trader', 'cardealer-helper' ),
			"car_com" => esc_html__( 'Cars.com', 'cardealer-helper' ),
            "pdf" => esc_html__( 'Brochure generator', 'cardealer-helper' ),			
        )                     +
        array_slice($columns, 2, count($columns) - 1, true) ;
		$image = array( "image" => esc_html__( 'Image', 'cardealer-helper' ) );
		array_splice( $newFields, 1, 0, $image );
	return $newFields;	
}
add_filter("manage_edit-cars_columns", "cdhl_cpt_cars_edit_columns");  
/*
 * Custom columns
 */
function cdhl_cpt_cars_custom_columns($column,$post_id)
{
    $feature = get_post_meta($post_id,'featured',true);
    $class = "dashicons-star-empty";
    if($feature == 1){
        $class = "dashicons-star-filled";
    }
    $args = array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all');
    $terms = wp_get_post_terms( $post_id, 'car_stock_number' ,$args);     
    $car_stock_number = '';
    if(isset($terms[0]->name) && !empty($terms[0]->name)){
        $car_stock_number = $terms[0]->name;
    }
	
    $car_vin_number = '';
    $carvinnumber = wp_get_post_terms( $post_id, 'car_vin_number' ,$args);
    if(isset($carvinnumber[0]->name) && !empty($carvinnumber[0]->name)){
        $car_vin_number = $carvinnumber[0]->name;
    }
    $price = cardealer_get_car_price('', $post_id);    
    switch ($column){
		case "image":
			echo (function_exists('cardealer_get_cars_image'))?cardealer_get_cars_image('cardealer-50x50', $post_id):'';
			break;        
        case "price":
			echo $price;
			break;
        case "cars_stock_number":
			echo $car_stock_number;
			break;
        case "car_vin_number":
			echo $car_vin_number;
			break;
        case "featured":
			?>
			<span style="cursor: pointer;" class="dashicons <?php echo esc_attr($class)?>  do_featured" data-id="<?php echo esc_attr($post_id)?>" data-feature="<?php echo esc_attr($feature)?>"></span>
			<?php
			break;
		case "auto_trader":
			$dealer = get_post_meta($post_id, 'auto_trader', true); 
			if( isset($dealer) && !empty($dealer) && $dealer == 'yes')
				echo date('m/d/Y H:i:s', strtotime(get_post_meta($post_id, 'auto_export_date', true)));
			else
				echo '-';
			break;
		case "car_com":
			$dealer = get_post_meta($post_id, 'cars_com', true); 
			if( isset($dealer) && !empty($dealer) && $dealer == 'yes')
				echo date('m/d/Y H:i:s', strtotime(get_post_meta($post_id, 'cars_com_export_date', true)));
			else
				echo '-';
			break;
        case "pdf":
             echo cdhl_cars_pdf_meta_box($post_id);
             break;
	}
	
}
add_filter("manage_posts_custom_column", "cdhl_cpt_cars_custom_columns", 10, 2);


/****** Include all taxonomies into admin car search starts ******/
// Where condition
function cdhl_search_where($where){
  global $wpdb, $current_screen, $pagenow; 
	if ( is_admin() && $pagenow=='edit.php' && isset($_GET['s']) && $_GET['s'] != '' && isset($current_screen->post_type) && $current_screen->post_type == 'cars' ) {
		if( isset($_GET['post_status']) && $_GET['post_status'] != 'all' ) 
			$where .= "OR (t.name LIKE '%".get_search_query()."%' AND {$wpdb->posts}.post_status = '".$_GET['post_status']."')";
		else 
			$where .= "OR (t.name LIKE '%".get_search_query()."%' AND ( {$wpdb->posts}.post_status = 'publish' OR {$wpdb->posts}.post_status = 'acf-disabled' OR {$wpdb->posts}.post_status = 'future' OR {$wpdb->posts}.post_status = 'draft' OR {$wpdb->posts}.post_status = 'pending' OR {$wpdb->posts}.post_status = 'private' ))";
	}
return $where;
}

// Join
function cdhl_search_join($join){
	global $wpdb, $current_screen, $pagenow;
	if (  is_admin() && $pagenow=='edit.php' && isset($_GET['s']) && $_GET['s'] != '' && isset($current_screen->post_type) && $current_screen->post_type == 'cars' ) {
		$join .= "LEFT JOIN {$wpdb->term_relationships} tr ON {$wpdb->posts}.ID = tr.object_id INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id=tr.term_taxonomy_id INNER JOIN {$wpdb->terms} t ON t.term_id = tt.term_id";
	}
	return $join;
}

// Group by
function cdhl_search_groupby($groupby){
	global $wpdb, $current_screen, $pagenow;
	if ( is_admin() && $pagenow=='edit.php' && isset($_GET['s']) && $_GET['s'] != '' && isset($current_screen->post_type) && $current_screen->post_type == 'cars' ) {
		// we need to group on post ID
		$groupby_id = "{$wpdb->posts}.ID";
		if(!is_search() || strpos($groupby, $groupby_id) !== false) return $groupby;

		// groupby was empty, use ours
		if(!strlen(trim($groupby))) return $groupby_id;

		// wasn't empty, append ours
		return $groupby.", ".$groupby_id;
	}
return $groupby;
}
add_filter('posts_where','cdhl_search_where');
add_filter('posts_join', 'cdhl_search_join');
add_filter('posts_groupby', 'cdhl_search_groupby');
/****** Include all taxonomies into admin car search ends ******/




/**
 * 
 */
add_action( 'before_delete_post', 'cdhl_delete_vin' );
function cdhl_delete_vin( $postid ){    
    global $post_type;   
    
    
    
    if ( $post_type != 'cars' ){
        return;  
    } else {
        $vin = wp_get_post_terms( $postid, 'car_vin_number');  
        if(isset($vin[0]->term_id) && !empty($vin[0]->term_id)){                        
            wp_delete_term($vin[0]->term_id,'car_vin_number');     
        }      
    }
} 


function cdhl_cars_pdf_meta_box($post_id) {
    global $post;
    ?>
    <div class="pdf_section" id="<?php echo "pdf_section-".$post_id;?>">
        <p class="download" id="<?php echo "download-".$post_id;?>">
            <label for="casr_pdf_styles"><?php esc_html_e('Choose Template: ');?></label>
            <?php   
            if(function_exists('have_rows')){
                if( have_rows('html_templates', 'option') ): ?>
                <select class="casr_pdf_styles" data-id="<?php echo esc_attr($post->ID);?>" name='casr_pdf_styles' id='casr_pdf_styles'>
                    <?php while ( have_rows('html_templates', 'option') ) : the_row(); ?>   
                        <?php $templates_title = get_sub_field('templates_title'); ?>
                        <option value="<?php echo esc_attr($templates_title); ?>"><?php echo esc_html($templates_title); ?></option>
                    <?php endwhile; ?>
                </select>
                <br /><br />
                <span id="<?php echo esc_attr($post->ID);?>" class="download-pdf">Generate</span>
                <div id="<?php echo "loader-".$post_id;?>" class="pdf-loader" style="display: none;">
                    <img  src="<?php echo plugin_dir_url('').'cardealer-helper-library/images/loader.gif'; ?>" width="20px" height="20px" />
                </div>            
                <div class="downloadlink" id="<?php echo 'downloadlink-'.$post_id;?>" style="display: none;"></div>            
                <?php endif;  
            }?>
        </p>
    </div>
    <?php  
}
add_action("wp_ajax_doPdf", "cdhl_doPdf"); 
add_action("wp_ajax_nopriv_doPdf", "cdhl_doPdf");
if(!function_exists("cdhl_doPdf")){
    function cdhl_doPdf(){        
        require_once(CDHL_PATH.'includes/pdf_generator/doPdf.php');        
        exit();
    }
}

// Set cars final price based on regualer and sale price
function cdhl_set_final_price( $post_id ){
	if ( get_post_type( $post_id ) == 'cars' ) {
		$final_price = 0;
		$sale_price = get_post_meta( $post_id, 'sale_price', true);
		
		if( $sale_price ) {
			$final_price = $sale_price;
		} else {
			$regular_price = get_post_meta( $post_id, 'regular_price', true);
			if( $regular_price ) $final_price = $regular_price;
		}
		update_post_meta( $post_id, 'final_price', $final_price);
	}
}
add_action('save_post', 'cdhl_set_final_price');
