<?php
/******* CODE TO EXPORT CAR POST TYPE STARTS *********/
/* Bulk Action wordpress version compibility starts */
global $wp_version;
function cdhl_add_export_option($bulk_actions) {
  $bulk_actions['export_cars'] = esc_html__( 'Export', 'cardealer-helper');
  $bulk_actions['export_autotrader'] = esc_html__( 'Export To AutoTrader.Com', 'cardealer-helper');
  $bulk_actions['export_car_com'] = esc_html__( 'Export To Cars.Com', 'cardealer-helper');
  return $bulk_actions;
}
function cdhl_custom_bulk_admin_footer() {
   global $post_type;	
	if($post_type == 'cars') {
		?>
			<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery('<option>').val('export_cars').text('<?php esc_html_e('Export', 'cardealer-helper')?>').appendTo("select[name='action']");
					jQuery('<option>').val('export_cars').text('<?php esc_html_e('Export', 'cardealer-helper')?>').appendTo("select[name='action2']");
					jQuery('<option>').val('export_autotrader').text('<?php esc_html_e('Export To AutoTrader.Com', 'cardealer-helper')?>').appendTo("select[name='action']");
					jQuery('<option>').val('export_autotrader').text('<?php esc_html_e('Export To AutoTrader.Com', 'cardealer-helper')?>').appendTo("select[name='action2']");
					jQuery('<option>').val('export_car_com').text('<?php esc_html_e('Export To Cars.Com', 'cardealer-helper')?>').appendTo("select[name='action']");
					jQuery('<option>').val('export_car_com').text('<?php esc_html_e('Export To Cars.Com', 'cardealer-helper')?>').appendTo("select[name='action2']");
				});
			</script>
		<?php
	}
}
if ( $wp_version >= 4.7 ) {
	add_filter( 'bulk_actions-edit-cars', 'cdhl_add_export_option' );
	add_filter( 'handle_bulk_actions-edit-cars', 'cdhl_export_cars_to_csv', 10, 3 );
	add_filter( 'handle_bulk_actions-edit-cars', 'cdhl_export_cars_to_autotrader', 10, 4 );
	add_filter( 'handle_bulk_actions-edit-cars', 'cdhl_export_cars_to_car_com', 10, 4 );
} else {
	add_action('admin_footer', 'cdhl_custom_bulk_admin_footer');
	add_action('load-edit.php','cdhl_custom_export_cars_to_csv');
	add_action('load-edit.php','cdhl_custom_export_cars_to_autotrader');
	add_action('load-edit.php','cdhl_custom_export_cars_to_car_com');
}
/* Bulk Action wordpress version compibility ends */

// AUTOTRADER_CAR_PROPERTIES
function cdhl_autotrader_car_properties( $carType = null ) {
		$properties = array();
		$properties['dealer_id']	 		= 'Dealer ID';
		$properties['car_stock_number']	 	= 'Stock Number';
		$properties['car_year']   	 		= 'Year';
		$properties['car_make']   	 		= 'Make';
		$properties['car_model']  	 		= 'Model';
		$properties['car_trim']			 	= 'Trim';
		$properties['car_vin_number']	 	= 'VIN';
		$properties['car_mileage']  	 	= 'Mileage';
		if( $carType == 'new' ) {
			$properties['regular_price']  		= 'MSRP';
			$properties['sale_price']  			= 'Dealer Price';
		} else {
			$properties['regular_price']  		= 'Regular Price';
			$properties['sale_price']  			= 'Sale Price';
		}
		$properties['car_exterior_color']	= 'Exterior Color';
		$properties['car_interior_color']	= 'Interior Color';
		$properties['car_transmission'] 	= 'Transmission';
		$properties['images']	 			= 'Physical Images';
		$properties['vehicle_overview']	 	= 'Description';
		$properties['car_body_style'] 		= 'Body Type';
		$properties['car_engine']	 		= 'Engine Type';
		$properties['drive_type']			= 'Drive Type';
		$properties['car_fuel_type']		= 'Fuel Type';
		$properties['car_features_options']	= 'Options';
		$properties['car_images']	 		= 'Image URLs';
		$properties['video_link']   	 	= 'Video URL';
		$properties['video_source']   	 	= 'Video Source';
	return $properties;
}
// Function to get length of each field
function cdhl_autotrader_car_field_length( $key ) {
		$length = array();
		$length['dealer_id']	 		= 10;
		$length['car_stock_number']	 	= 30;
		$length['car_year']   	 		= 4;
		$length['car_make']   	 		= 10;
		$length['car_model']  	 		= 10;
		$length['car_trim']			 	= 30;
		$length['car_vin_number']	 	= 18;
		$length['car_mileage']  	 	= 8;
		$length['regular_price']  		= 8;
		$length['sale_price']  			= 8;
		$length['msrp']  				= 8;
		$length['car_exterior_color']	= 30;
		$length['car_interior_color']	= 30;
		$length['car_transmission'] 	= 50;
		$length['images']	 			= 250;
		$length['vehicle_overview']	 	= 2000;
		$length['car_body_style'] 		= 50;
		$length['car_engine']	 		= 50;
		$length['drive_type']			= 50;
		$length['car_fuel_type']		= 50;
		$length['car_features_options']	= 4000;
		$length['car_images']	 		= 4000;
		$length['video_link']   	 	= 250;
		$length['video_source']   	 	= 250;
	return $length[$key];
}

// CAR.COM_CAR_PROPERTIES
function cdhl_car_com_car_properties() {
		$properties = array();
		$properties['dealer_id']	 		= 'Dealer ID';
		$properties['car_condition']		= 'Type';
		$properties['car_stock_number']	 	= 'Stock Number';
		$properties['car_vin_number']	 	= 'VIN';
		$properties['car_year']   	 		= 'Year';
		$properties['car_make']   	 		= 'Make';
		$properties['car_model']  	 		= 'Model';
		$properties['car_body_style']  	 	= 'Body';
		$properties['car_trim']			 	= 'Trim-Style';
		$properties['car_exterior_color']	= 'Ext Color';
		$properties['car_interior_color']	= 'Int Color';
		$properties['engine_cylinders']		= 'Engine Cylinders';
		$properties['engine_discplacement']	= 'Engine Discplacement';
		$properties['car_engine']	 		= 'Engine Description';
		$properties['car_transmission'] 	= 'Transmission';
		$properties['car_mileage'] 			= 'Miles';
		$properties['sale_price'] 			= 'Selling Price';
		$properties['regular_price'] 		= 'MSRP';
		$properties['invoice_price'] 		= 'Invoice Price';
		$properties['car_drivetrain'] 		= 'Drive Train';
		$properties['dealer_tagline'] 		= 'Dealer Tagline';
		$properties['car_features_options']	= 'Vehicle Value Added Options';
		$properties['option_packages_codes']= 'Option Packages Codes';
		$properties['inventory_date']		= 'Inventory Date';
		$properties['model_code']			= 'Model Code';
	return $properties;
}
// Function to get field length
function cdhl_car_com_field_length( $key ) {
		$length = array();
		$length['dealer_id']	 		= 20;
		$length['car_condition']	 	= 20;
		$length['car_stock_number']	 	= 20;
		$length['car_vin_number']	 	= 20;
		$length['car_year']   	 		= 4;
		$length['car_make']   	 		= 35;
		$length['car_model']  	 		= 50;
		$length['car_body_style']  	 	= 50;
		$length['car_trim']			 		= 50;
		$length['car_exterior_color']	= 50;
		$length['car_interior_color']	= 35;
		$length['engine_cylinders']		= '*';
		$length['engine_discplacement']	= '*';
		$length['car_engine']    	 	= 50;
		$length['car_transmission'] 	= 50;
		$length['car_mileage'] 			= 10;
		$length['sale_price'] 			= 10;
		$length['regular_price'] 		= 10;
		$length['invoice_price'] 		= 10;
		$length['car_drivetrain'] 		= 50;
		$length['dealer_tagline'] 		= 2000;
		$length['car_features_options']	= 2000;
		$length['option_packages_codes']= 4000;
		$length['inventory_date']		= 10;
		$length['model_code']			= 20;
	return $length[$key];
}

// get properties of Cars
function cdhl_get_car_properties( $car_id, $action ) { 
	$carAttributes = $carProperty = array();
	global $car_dealer_options;
	
	if( $action == 'export_cars' ) {
		unset($car_dealer_options['export_cars']['Attributes to export']['placebo']);
		$carAttributes = $car_dealer_options['export_cars']['Attributes to export'];
		if( empty($carAttributes) ) return;
		foreach( $carAttributes as $key => $carAtt ) {
			$carProperty[$car_id][$key] = get_field_object($key, $car_id);
		}
	}
	else if( $action == 'export_autotrader' ) {
		// New Car
		$newCarAttributes = cdhl_autotrader_car_properties('new');
		$newCarProperty = array();
		if( empty($newCarAttributes) ) return;
		$blank_attr = ['drive_type' , 'video_link', 'video_source', 'dealer_id', 'images'];
		foreach( $newCarAttributes as $key => $carAtt ) {
			if( in_array( $key, $blank_attr ) )
				$newCarProperty[$car_id][$key] = '';
			else {
				$newCarProperty[$car_id][$key] = get_field_object($key, $car_id);
			}
		}
		
		// Old Car
		$oldCarAttributes = cdhl_autotrader_car_properties();
		$oldCarProperty = array();
		if( empty($oldCarAttributes) ) return;
		$blank_attr = ['drive_type' , 'video_link', 'video_source', 'dealer_id', 'images'];
		foreach( $oldCarAttributes as $key => $carAtt ) {
			if( in_array( $key, $blank_attr ) )
				$oldCarProperty[$car_id][$key] = '';
			else {
				$oldCarProperty[$car_id][$key] = get_field_object($key, $car_id);
			}
		}
		
		//Merge Two CarType Propery
		$carProperty['old_car'] = $oldCarProperty;
		$carProperty['new_car'] = $newCarProperty;
	}
	else if( $action == 'export_car_com' ) {
		// New Car
		$carAttributes = cdhl_car_com_car_properties();
		$carProperty = array();
		if( empty($carAttributes) ) return;
		$blank_attr = ['dealer_id', 'engine_cylinders' ,'engine_discplacement', 'invoice_price', 'option_packages_codes', 'inventory_date', 'model_code', 'dealer_tagline'];
		foreach( $carAttributes as $key => $carAtt ) {
			if( in_array( $key, $blank_attr ) )
				$carProperty[$car_id][$key] = '';
			else {
				$carProperty[$car_id][$key] = get_field_object($key, $car_id);
			}
		}
			
	}
	return $carProperty;
}
// Display Error msg if attributes are not set from admin side theme settings
add_action( 'admin_notices', 'cdhl_empty_properties_notice');
function cdhl_empty_properties_notice() { 
	if( isset( $_GET['car_notice']) ) {
		$class = 'notice is-dismissible';
		
		switch( $_GET['car_notice'] ){
			case 1:
				$class .= ' notice-error';
				$message = esc_html__( 'Error! Please set attributes to export from admin in theme settings.', 'cardealer-helper' );
			break;
			case 2:
				$class .= ' updated notice-success';
				$message = esc_html__( 'Success! Cardata successfully exported!', 'cardealer-helper' );
			break;
			case 3:
				$class .= ' notice-error';
				$message = esc_html__( 'Error! Failed to export file.', 'cardealer-helper' );
			break;
			case 4:
				$class .= ' notice-error';
				$message = esc_html__( 'Error! FTP Connection attempt failed!', 'cardealer-helper' );
			break;
			case 5:
				$class .= ' notice-error';
				$message = esc_html__( 'Error! FTP Details and Dealer Id fields should not be empty in theme option.', 'cardealer-helper' );
			break;
			case 6:
				$class .= ' notice-error';
				$message = esc_html__( 'Error! Please provide correct path to send exported file.', 'cardealer-helper' );
			break;
			case 7:
				$class .= ' notice-error';
				$message = esc_html__( 'Error! Sounds like your PHP was not installed with "--enable-ftp" or that the ftp module is disabled in your php.ini. Please ask your hosting provider to enable ftp module on your server.', 'cardealer-helper' );
			break;
			case 8:
				$class .= ' notice-info';
				$message = esc_html__( 'Alert! No records exported, this is because of no record satisfied desired rules of selected third party export.', 'cardealer-helper' );
			break;
		}
		
		$btn_type = 'button';
		$btn_class = 'notice-dismiss';
		$span_class = 'screen-reader-text';
		$btn_msg = esc_html__( 'Dismiss this notice.', 'cardealer-helper' );
		?>
		<div class="<?php echo esc_attr($class)?>"><p><?php echo esc_html__($message)?></p><button type="<?php echo esc_attr($btn_type)?>" class="<?php echo esc_attr($btn_class)?>"><span class="<?php echo esc_attr($span_class)?>"><?php echo esc_html__($btn_msg)?></span></button></div>
		<?php
	}
}

function cdhl_get_cars_detail( $post_ids, $redirect_to, $action ) { 
	$carProperties = cdhl_get_car_properties( $post_ids[0], $action );
	if(empty($carProperties)) { 
		wp_safe_redirect( $redirect_to.'&car_notice=1' ); die;
	}
	
	$attr_heading = array();
	$counter = 0;
	$row = array();
	$csvData = array();
	foreach( $post_ids as $carId ) { 
		foreach( $carProperties as $key => $car ) {
			$row = array();
			foreach( $car as $k =>  $carAttr) {
				if($counter < 1) {
					if( !empty($carAttr['label']) )
						$attr_heading[$k] = $carAttr['label'];
					else
						$attr_heading[$k] = ucwords(str_replace('_', ' ', $k));
					$title = array();
					foreach( $attr_heading as $mykey => $heading )
						$title[] = $heading;
				}
				if(taxonomy_exists($k)) { 
					$term = wp_get_post_terms( $carId, $k);	 
					if(!empty($term)) {
						$json  = json_encode($term); // Conver Obj to Array
						$term = json_decode($json, true); // Conver Obj to Array
						$name_array = array_map(function ($options) {return $options['name'];}, (array) $term); // get all name term array
						$row[] = implode(',', $name_array);
					}
					else
						$row[] = '';
				} else {
					$attr = get_field($k, $carId);
					if( !empty($attr) ) {
						if( $k == 'car_images' ) {
						$img = array();
							foreach($attr as $imgs)
								$img[] = $imgs['url'];
							$row[] = implode( ',', $img );	
						}
						else if( $k == 'pdf_file' ) 
							$row[] = isset($attr['url']) ? $attr['url'] : wp_get_attachment_url( $attr );
						else if( $k == 'vehicle_location' )
							$row[] = $attr['address'];
						else
							$row[] = $attr;
					}
					else
						$row[] = '';
				}
			}
			if($counter < 1)
				$csvData[] = $title;	
			$csvData[] = $row;
			$counter++;
		}
	}
	return $csvData;
}

require_once trailingslashit(CDHL_PATH) . 'includes/export/export_csv.php'; // csv export
require_once trailingslashit(CDHL_PATH) . 'includes/export/export_thirdparty.php'; // third party export
/******* CODE TO EXPORT CAR POST TYPE END *********/
?>