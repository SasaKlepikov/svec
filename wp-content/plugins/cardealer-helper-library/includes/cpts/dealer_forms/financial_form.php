<?php
// Add Taxonomy
add_action( 'init', 'cdhl_financial_inquiry' );
function cdhl_financial_inquiry() {  
    $args = array(
        'labels' => array(
            'name' => __( 'Financial Inquiry' ),
            'singular_name' => __( 'Financial Inquiry' )
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
        'rewrite' => array('slug' => 'financial_inquiry'),
        'supports' => array( 'title'),
		'show_in_menu' => 'edit.php?post_type=pgs_inquiry',
    );    
    register_post_type( 'financial_inquiry',$args);
}

/*
 * Edit columns
 */
function cdhl_cpt_financial_inquiry_edit_columns($columns)
{
	$newcolumns = array(
		"preferred_email_address" => esc_html__( 'Email Id', 'cardealer-helper' ),
		"mobile_phone_number" => esc_html__( 'Mobile No', 'cardealer-helper' ),
		"car_info_fin" => esc_html__( 'Car Information', 'cardealer-helper' ),
		"vin_stock_fin" => esc_html__( 'VIN / StockNo', 'cardealer-helper' ),
		"price" => esc_html__( 'Price', 'cardealer-helper' ),
	);
	$columns = array_merge($columns, $newcolumns);	
	
	return $columns;
}
add_filter("manage_edit-financial_inquiry_columns", "cdhl_cpt_financial_inquiry_edit_columns");  


/*
 * Custom columns
 */
function cdhl_cpt_financial_inquiry_custom_columns($column)
{
	global $post;
	$inq_id = get_the_ID();
	switch ($column){
		case "preferred_email_address":
			echo get_post_meta($inq_id, 'preferred_email_address', $single = true);
			break;
		case "mobile_phone_number":
			echo get_post_meta($inq_id, 'mobile_phone_number', $single = true);
			break;
		case "car_info_fin":
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
		case "vin_stock_fin":
			$carvin = get_post_meta($inq_id, 'vin_number', true);
			$car_vin = (isset($carvin))? $carvin : '';
			$carstock = get_post_meta($inq_id, 'stock_number', true);
			$car_stock = (isset($carstock))? $carstock : '';
			echo esc_html($car_vin. ' / '.$car_stock);
			break;
	}
}
add_action("manage_posts_custom_column",  "cdhl_cpt_financial_inquiry_custom_columns");
?>