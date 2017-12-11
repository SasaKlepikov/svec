<?php
// Add Taxonomy
add_action( 'init', 'cdhl_inquiry' );
function cdhl_inquiry() {  
    $args = array(
        'labels' => array(
            'name' => __( 'Inquiries' ),
            'singular_name' => __( 'Inquiries' )
        ),
		'capability_type' => 'post',
			'capabilities' => array(
			'create_posts' => 'do_not_allow', // false < WP 4.5, credit @Ewout
		),
		'map_meta_cap' => true,
        'public' => true,
		'show_in_nav_menus' => false,
		'publicly_queryable' => false,
		'exclude_from_search' => true,
		'has_archive' => false,
        'rewrite' => array('slug' => 'inquiry'),
        'supports' => array( ),
		'menu_icon' => 'dashicons-editor-help'
    );    
    register_post_type( 'pgs_inquiry',$args);
}

/*
 * Edit columns
 */
function cdhl_cpt_inquiry_edit_columns($columns)
{
	$newcolumns = array();
	$newcolumns = array(
		"email" => esc_html__( 'Email Id', 'cardealer-helper' ),
		"mobile" => esc_html__( 'Phone No', 'cardealer-helper' ),
		"inq_car_info" => esc_html__( 'Car Information', 'cardealer-helper' ),
		"inq_vin_stock" => esc_html__( 'VIN / StockNo', 'cardealer-helper' ),
		"price" => esc_html__( 'Price', 'cardealer-helper' ),
	);
	$columns = array_merge($columns, $newcolumns);	
	return $columns;
}
add_filter("manage_edit-pgs_inquiry_columns", "cdhl_cpt_inquiry_edit_columns");  


/*
 * Custom columns
 */
function cdhl_cpt_inquiry_custom_columns($column)
{
	global $post;
	$inq_id = get_the_ID();
	switch ($column){
		case "email":
			echo get_post_meta($inq_id, 'email', $single = true);
			break;
		case "mobile":
			echo get_post_meta($inq_id, 'mobile', $single = true);
			break;
		case "inq_car_info":
			$caryear = get_post_meta($inq_id, 'car_year_inq', $single = true);
			$car_year = (isset($caryear))? $caryear : '';
			$carmake = get_post_meta($inq_id, 'car_make_inq', $single = true);
			$car_make = (isset($carmake))? $carmake : '';
			$carmodel = get_post_meta($inq_id, 'car_model_inq', $single = true);
			$car_model = (isset($carmodel))? $carmodel : '';
			$cartrim = get_post_meta($inq_id, 'car_trim_inq', $single = true);
			$car_trim = (isset($cartrim))? $cartrim : '';
			echo esc_html($car_year. ' '.$car_make. ' '.$car_model. ' '.$car_trim);
			break;
		case "inq_vin_stock":
			$carvin = get_post_meta($inq_id, 'vin_number', true);
			$car_vin = (isset($carvin))? $carvin : '';
			$carstock = get_post_meta($inq_id, 'stock_number', true);
			$car_stock = (isset($carstock))? $carstock : '';
			echo esc_html($car_vin. ' / '.$car_stock);
			break;
	}
}
add_filter("manage_posts_custom_column", "cdhl_cpt_inquiry_custom_columns");  
?>