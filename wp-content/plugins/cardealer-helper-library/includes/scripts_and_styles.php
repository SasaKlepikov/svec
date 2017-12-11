<?php
add_action( 'wp_enqueue_scripts', 'cdhl_load_style_script' );
function cdhl_load_style_script(){
	$cars_geo_fencing = cdhl_get_geo_count();
    $carsfilterwith = 'ajax';
    if(function_exists('cardealer_cars_filter_methods')){
        $carsfilterwith = cardealer_cars_filter_methods();
    }
    wp_register_script( 'cardealer-helper-js', trailingslashit(CDHL_URL).'js/cardealer-helper.js', array('jquery','cardealer-cookie'), false, true );
    wp_localize_script( 'cardealer-helper-js', 'cars_filter_methods', array(
    	'cars_filter_with' 	=> $carsfilterwith,
        'geofenc'   => $cars_geo_fencing    	
    ) );
    wp_localize_script( 'cardealer-helper-js', 'cdhl_obj', array(    	
        'geofenc'   => $cars_geo_fencing    	
    ) );    
    wp_enqueue_script('cardealer-helper-js');
}

/*
 * Add script and style in wp-admin side
 */ 
add_action( 'admin_enqueue_scripts', 'cdhl_admin_enqueue_scripts' );
function cdhl_admin_enqueue_scripts($hook){
	global $car_dealer_options;     
	$google_key = (function_exists('cardealer_get_google_api_key'))?cardealer_get_google_api_key():'';
    // Javascript	
    
	wp_register_script( 'chosen', trailingslashit(CDHL_URL).'js/chosen/chosen.jquery.min.js', array("jquery-ui-widget"),false,true);
	wp_register_script( 'jquery-confirm'  , trailingslashit(CDHL_URL) . 'js/jquery-confirm/jquery-confirm.min.js', array('jquery') );
    wp_register_script( 'cdhl-jquery-cars', trailingslashit(CDHL_URL).'js/cars.js', array(),false,true );
    wp_register_script( 'cdhl-jquery-helper-admin', trailingslashit(CDHL_URL).'js/cardealer-helper-admin.js', array(),false,true );
	wp_register_script( 'cdhl-jquery-import', trailingslashit(CDHL_URL).'js/cardealer_import.js' ,array(),false,true);
    wp_register_script( 'cdhl-jquery-vin-import', trailingslashit(CDHL_URL).'js/cardealer_vin_import.js' ,array(),false,true);
    wp_register_script( 'cdhl-google-maps-apis' , 'https://maps.googleapis.com/maps/api/js?key='.$google_key.'&libraries=drawing,places&callback=geoFenc', array(), false, true );
    wp_register_script( 'cdhl-geofance' , trailingslashit(CDHL_URL).'js/geofance.js', array(),false,true );
    
    $ajaxurl = array(
		'ajaxurl' => admin_url('admin-ajax.php'),
        'cdhl_url' => CDHL_URL,
	);    
	wp_localize_script( 'cdhl-jquery-helper-admin', 'cdhl', $ajaxurl );
	//add message for pdf brochare
	$pdf_message = '<div class="notice notice-success" id = "pdf-notice"><p>'.esc_html__("Generated PDF is assigned to <b>PDF Brochure</b>, you can also change this PDF from <b>PDF Brochure</b> setting by editing car.","cardealer-helper").'</p></div>';	
	wp_localize_script( 'cdhl-jquery-cars', 'cars_pdf_message', array(
    	'pdf_message' 	=> $pdf_message,  	
    ) );
    
    wp_enqueue_script( 'jquery-ui-core' );
    wp_enqueue_script( 'jquery-ui-widget' );
    wp_enqueue_script( 'jquery-ui-droppable' );
    wp_enqueue_script( 'jquery-ui-accordion' );
    wp_enqueue_script( 'jquery-ui-sortable' );    
    wp_enqueue_script( 'jquery-ui-tooltip' );
    wp_enqueue_script( 'jquery-ui-tabs' );
    wp_enqueue_script( 'chosen' );
	wp_enqueue_script( 'jquery-confirm' );    
    
    wp_enqueue_script( 'cdhl-jquery-helper-admin'); 	
    
    global $post_type;    
    if( 'cars' == $post_type || ( isset($_GET['page']) && ($_GET['page'] == 'log-list'|| $_GET['page'] == 'import-log'|| $_GET['page'] == 'car-export-list')) ){		
        wp_enqueue_script( 'cdhl-jquery-cars');
    }
    
    if( 'cars' == $post_type || ( isset($_GET['page']) && $_GET['page'] == 'cars-vin-import') ){        
        wp_enqueue_script( 'cdhl-jquery-vin-import' );        
    }
    
    if( 'cars' == $post_type || ( isset($_GET['page']) && $_GET['page'] == 'cars-import') ){
        wp_enqueue_script( 'cdhl-jquery-import');
    }
    
    // CSS
	wp_enqueue_style( 'cdhl-css-helper-admin', trailingslashit(CDHL_URL).'css/cardealer-helper-admin.css' );
    wp_enqueue_style( 'jquery-ui',trailingslashit(CDHL_URL).'css/jquery-ui/jquery-ui.min.css');
    wp_enqueue_style( 'chosen',trailingslashit(CDHL_URL).'css/chosen/chosen.min.css');    
	wp_enqueue_style( 'jquery-confirm-bootstrap' , trailingslashit(CDHL_URL) . 'css/jquery-confirm/jquery-confirm-bootstrap.css' );
	wp_enqueue_style( 'jquery-confirm'           , trailingslashit(CDHL_URL) . 'css/jquery-confirm/jquery-confirm.css' );
    wp_enqueue_style( 'cdhl-css-redux_admin', trailingslashit(CDHL_URL).'css/cardealer_redux.css' );
    
}