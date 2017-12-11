<?php
// Add Taxonomy
add_action( 'init', 'cdhl_make_offer' );
function cdhl_make_offer() {  
    $args = array(
        'labels' => array(
            'name' => __( 'Make an Offer' ),
            'singular_name' => __( 'Make an Offer' )
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
        'rewrite' => array('slug' => 'make_offer'),
        'supports' => array( 'title'),
		'show_in_menu' => 'edit.php?post_type=pgs_inquiry',
    );    
    register_post_type( 'make_offer',$args);
}

/*
 * Edit columns
 */
function cdhl_cpt_make_offer_edit_columns($columns)
{
	$newcolumns = array(
		"email_id" => esc_html__( 'Email Id', 'cardealer-helper' ),
		"home_phone" => esc_html__( 'Home Phone No', 'cardealer-helper' ),
		"request_price" => esc_html__( 'Request Price', 'cardealer-helper' ),
		"car_info_mno" => esc_html__( 'Car Information', 'cardealer-helper' ),
		"vin_stock_mno" => esc_html__( 'VIN / StockNo', 'cardealer-helper' ),
		"price" => esc_html__( 'Price', 'cardealer-helper' ),
	);
	$columns = array_merge($columns, $newcolumns);		
	return $columns;
}
add_filter("manage_edit-make_offer_columns", "cdhl_cpt_make_offer_edit_columns");  


/*
 * Custom columns
 */
function cdhl_cpt_make_offer_custom_columns($column)
{
	global $post;
	$inq_id = get_the_ID();
	switch ($column){
		case "email_id":
			echo get_post_meta($inq_id, 'email_id', $single = true);
			break;
		case "home_phone":
			echo get_post_meta($inq_id, 'home_phone', $single = true);
			break;
		case "request_price":
			echo get_post_meta($inq_id, 'request_price', $single = true);
			break;
		case "car_info_mno":
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
		case "vin_stock_mno":
			$carvin = get_post_meta($inq_id, 'vin_number', true);
			$car_vin = (isset($carvin))? $carvin : '';
			$carstock = get_post_meta($inq_id, 'stock_number', true);
			$car_stock = (isset($carstock))? $carstock : '';
			echo esc_html($car_vin. ' / '.$car_stock);
			break;
	}
}
add_action("manage_posts_custom_column",  "cdhl_cpt_make_offer_custom_columns");
?>