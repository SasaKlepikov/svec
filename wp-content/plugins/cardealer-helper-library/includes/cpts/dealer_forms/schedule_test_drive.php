<?php
// Add Taxonomy
add_action( 'init', 'cdhl_schedule_test_drive' );
function cdhl_schedule_test_drive() {  
    $args = array(
        'labels' => array(
            'name' => __( 'Schedule Test Drive' ),
            'singular_name' => __( 'Schedule Test Drive' )
        ),
        'public' => true,
		'capability_type' => 'post',
			'capabilities' => array(
			'create_posts' => 'do_not_allow', // false < WP 4.5, credit @Ewout
		),
		'map_meta_cap' => true,
		'show_in_nav_menus' => false,
		'publicly_queryable' => false,
		'exclude_from_search' => true,
		'has_archive' => false,
        'rewrite' => array('slug' => 'schedule_test_drive'),
        'supports' => array( 'title'),
		'show_in_menu' => 'edit.php?post_type=pgs_inquiry',
    );    
    register_post_type( 'schedule_test_drive',$args);
}

/*
 * Edit columns
 */
function cdhl_cpt_schedule_test_drive_edit_columns($columns)
{
	$newcolumns = array(
		"date" => esc_html__( 'Scheduled Date', 'cardealer-helper' ),
		"time_sched" => esc_html__( 'Scheduled Time', 'cardealer-helper' ),
		"car_info_sched" => esc_html__( 'Car Information', 'cardealer-helper' ),
		"vin_stock_sched" => esc_html__( 'VIN / StockNo', 'cardealer-helper' ),
		"price" => esc_html__( 'Price', 'cardealer-helper' ),
	);
	$columns = array_merge($columns, $newcolumns);	
	
	return $columns;
}
add_filter("manage_edit-schedule_test_drive_columns", "cdhl_cpt_schedule_test_drive_edit_columns");  


/*
 * Custom columns
 */
function cdhl_cpt_schedule_test_drive_custom_columns($column)
{
	global $post;
	$inq_id = get_the_ID();
	switch ($column){
		case "date":
			echo get_post_meta($inq_id, 'date', $single = true);
			break;
		case "time_sched":
			echo get_post_meta($inq_id, 'time', $single = true);
			break;
		case "car_info_sched":
			$caryear = get_post_meta($inq_id, 'car_year_inq', true);
			$car_year = (isset($caryear))? $caryear : '';
			$carmake = get_post_meta($inq_id, 'car_make_inq', true);
			$car_make = (isset($carmake))? $carmake : '';
			$carmodel = get_post_meta($inq_id, 'car_model_inq', true);
			$car_model = (isset($carmodel))? $carmodel : '';
			$cartrim = get_post_meta($inq_id, 'car_trim_inq', true);
			$car_trim = (isset($cartrim))? $cartrim : '';
			echo esc_html($car_year. ' '.$car_make. ' '.$car_model. ' '.$car_trim);
			break;
		case "vin_stock_sched":
			$carvin = get_post_meta($inq_id, 'vin_number', true);
			$car_vin = (isset($carvin))? $carvin : '';
			$carstock = get_post_meta($inq_id, 'stock_number', true);
			$car_stock = (isset($carstock))? $carstock : '';
			echo esc_html($car_vin. ' / '.$car_stock);
			break;
	}
}
add_action("manage_posts_custom_column",  "cdhl_cpt_schedule_test_drive_custom_columns");
?>