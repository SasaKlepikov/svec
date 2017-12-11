<?php
/**
* Import CSV data functions
*/

require_once( trailingslashit(CDHL_PATH) . 'includes/import_back_process/helper-classes/class-cdhl-back-logger.php' ); // LOGGER TRAIT

function check_import_rules(){
	if( !isset( $_SESSION ) ) {
		session_start();
	}
	$logfile = CDHL_LOG . "import_logs/cdhl_imported_cars_". str_replace( '.', '_', CDHL_VERSION ) . "_" . time() . ".txt";
	cdhl_log_imported_cars('CREATE', '', $logfile);
	
	$csv_rows = $_SESSION['cars_csv']['file_row'];
	set_time_limit(0);
	global $cars,$car_dealer_options;
	$insert_rules = array();
	
	if( !isset( $_SESSION['import_post_data'] ) ){
		return;
	}
	
	//First condition
    $new_vin         = (isset($_SESSION['import_post_data']['new_vin']) && !empty($_SESSION['import_post_data']['new_vin']) ? $_SESSION['import_post_data']['new_vin'] : "");
    
    //Second condition
    $vin_not_in_csv  = (isset($_SESSION['import_post_data']['vin_not_in_csv']) && !empty($_SESSION['import_post_data']['vin_not_in_csv']) ? $_SESSION['import_post_data']['vin_not_in_csv'] : "");                
    $vin_not_in_csv_in_db = (isset($_SESSION['import_post_data']['vin_not_in_csv_in_db']) && !empty($_SESSION['import_post_data']['vin_not_in_csv_in_db']) ? $_SESSION['import_post_data']['vin_not_in_csv_in_db'] : "");
    
    //last condition
    $cdhl_duplicate_check_vin = (isset($_SESSION['import_post_data']['duplicate_check_vin']) && !empty($_SESSION['import_post_data']['duplicate_check_vin']) ? $_SESSION['import_post_data']['duplicate_check_vin'] : "");
    
    $overwrite_car_price = (isset($_SESSION['import_post_data']['overwrite_existing_car_price']) && !empty($_SESSION['import_post_data']['overwrite_existing_car_price']) ? $_SESSION['import_post_data']['overwrite_existing_car_price'] : "");
    $overwrite_car_images  = (isset($_SESSION['import_post_data']['overwrite_existing_car_images']) && !empty($_SESSION['import_post_data']['overwrite_existing_car_images']) ? $_SESSION['import_post_data']['overwrite_existing_car_images'] : "");
    
    // if no selection done for overrite, then reset duplicate_check_vin to null
	if( $overwrite_car_price == "" && $overwrite_car_images == "" && $_SESSION['import_post_data']['overwrite_existing'] != "on" ){
		$cdhl_duplicate_check_vin = "";
	}
	
	$csv_post_data = (isset($_SESSION['import_post_data']['csv']) && !empty($_SESSION['import_post_data']['csv']) ? $_SESSION['import_post_data']['csv'] : "");
    $cars_attributes = $cars_attributes_safe = cardealer_get_all_taxonomy_with_terms();
    $imported_cars_data = array();
    
    if(!empty($csv_post_data)){
        $current_cars = get_posts(
            array(
                "post_type" => "cars",
                "posts_per_page" => -1,
                'post_status' => 'any'
            )
        );

        $all_cars  = array();
        $current_check = array();        
        $terms_array['vin-number'] = array();
        $all_db_vin_array = array();
		$args = array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all');
        foreach( $current_cars as $cars ){                
            $terms = wp_get_post_terms( $cars->ID, 'car_vin_number' ,$args);
			if(isset($terms) && !empty($terms)){
                /**
                * Get all vin number from database
                */                    
                $terms_array['vin-number'] = (array) $terms[0];
                $all_db_vin_array[] = $terms[0]->name;
            }
            if(isset($terms_array[$cdhl_duplicate_check_vin]) && is_array($terms_array[$cdhl_duplicate_check_vin]) && !empty($terms_array[$cdhl_duplicate_check_vin])){
                $check_label = (isset($terms_array[$cdhl_duplicate_check_vin]['name']) && !empty($terms_array[$cdhl_duplicate_check_vin]['name']) ? $terms_array[$cdhl_duplicate_check_vin]['name'] : "");
                if(isset($check_label)) {
                    $current_check[ $cars->ID ] = $check_label;
                    $all_cars[ $cars->ID ]  = $check_label;
                }
            }                                    
        }
        
        $all_vin_from_csv_arr = array();
        foreach($csv_rows as $key => $row){
            $vin_number   = cdhl_search_array_keys($row, "vin-number", $csv_post_data);
            $all_vin_from_csv_arr[] = trim($vin_number);     
        }                    
        
        $diff_arr = array_diff($all_db_vin_array,$all_vin_from_csv_arr);
        $updated_cars_status = array();
        $updated_post_status = array();
        $delete_post = array();        
        
		 /**
		* Rule II
		* If Vehicle exist in Database and not in CSV
		*/
		$vin_not_in_csv  = (isset($_SESSION['import_post_data']['vin_not_in_csv']) && !empty($_SESSION['import_post_data']['vin_not_in_csv']) ? $_SESSION['import_post_data']['vin_not_in_csv'] : "");                
		$vin_not_in_csv_in_db = (isset($_SESSION['import_post_data']['vin_not_in_csv_in_db']) && !empty($_SESSION['import_post_data']['vin_not_in_csv_in_db']) ? $_SESSION['import_post_data']['vin_not_in_csv_in_db'] : "");
        
		if( !empty( $diff_arr ) ) {
			foreach($diff_arr as $nkey){
				if($vin_not_in_csv != ''){                        
					$args=array(
						'post_type' => 'cars',
						'post_status' => 'any',
						'posts_per_page' => -1,                                        
						'tax_query' => array(
							array(
								'taxonomy' => 'car_vin_number',
								'field'    => 'name',
								'terms'    => $nkey,
							),
						),                    
					);
					$query = new WP_Query( $args );
					if($query->have_posts()){
						while ( $query->have_posts() ) : $query->the_post();
							$theid = get_the_ID();
							// Perform action based on action given in drop down
							switch( $vin_not_in_csv_in_db ){
								case 'sold':
									update_field('car_status', $vin_not_in_csv_in_db, $theid);                                                        
									$updated_cars_status[] = array(
										'id' => $theid,
										'title' => get_the_title()
									); 
									cdhl_log_imported_cars( esc_html__('UPDATED' ,'cardealer-helper'), get_the_title() . esc_html__(' Successfully updated.' ,'cardealer-helper'), $logfile );
								break;
								case 'unpublished':
									$my_post = array(
									  'ID'           => $theid,
									  'post_status' => 'draft',                                                  
									);                                            
									wp_update_post( $my_post );
									$updated_post_status[] = array(
										'id' => $theid,
										'title' => get_the_title()
									);
									cdhl_log_imported_cars( esc_html__('UPDATED POST STATUS' ,'cardealer-helper'), get_the_title() . esc_html__(' Successfully updated post status.' ,'cardealer-helper'), $logfile );
								break;
								case 'delete':
									$delete_post[] = array(
										'id' => $theid,
										'title' => get_the_title()
									);
									wp_trash_post( $theid );
									cdhl_log_imported_cars( esc_html__('DELETED' ,'cardealer-helper'), get_the_title() . esc_html__(' Successfully deleted.' ,'cardealer-helper'), $logfile );
								break;
							}                        
						endwhile;    
						wp_reset_postdata();
					}						
				}
			}        
        }
          
        /**
		* Rule I
        * CSV rows array
        * If Vehicle exist in CSV not in Database 
        */
        $new_vin_number_array = array();  
		
		// Post Status
		$post_status = 'publish';
		if(isset($car_dealer_options['import_post_status']) && !empty($car_dealer_options['import_post_status'])){
			$post_status = $car_dealer_options['import_post_status'];
		}
 		
		// import post status
		$imp_post_status = (isset($_SESSION['import_post_data']['new_vin_imp_post_status']) && !empty($_SESSION['import_post_data']['new_vin_imp_post_status']) ? $_SESSION['import_post_data']['new_vin_imp_post_status'] : "");
        foreach($csv_rows as $key => $row){            
            if(isset($_SESSION['import_post_data']['car_titles']) && !empty($_SESSION['import_post_data']['car_titles'])){
                $post_title = cdhl_get_multiple_values($_SESSION['import_post_data']['car_titles'], $row, $csv_post_data);
            }
            if(empty($post_title)){
                $post_title = "N/A";
            }    
            $vin_number = cdhl_search_array_keys($row, "vin-number", $csv_post_data);            

            /**
            * Rules one if Vehicle exist in CSV not in Database
            */
            if($new_vin != ''){ 
                $insertinfo = cdhl_get_car_meta($cars_attributes, $cars_attributes_safe, $row, $csv_post_data);
				if(!empty($vin_number)){
                    if(!in_array($vin_number,$all_db_vin_array)){
                        
                        $new_vin_number_array[] = $vin_number;
                        if($imp_post_status != ''){
                            $post_status = $imp_post_status;
                            
                            $insert_info    = array(
                                'post_type'     => "cars",
                                'post_title'    => ($post_title),
                                'post_status'   => $post_status
                            );  
                            $insert_id = wp_insert_post( $insert_info );    //echo '<br> Last : '.date('Y-m-d H:i:s').'<pre>';print_r($insertinfo);die;                        
                            foreach( $insertinfo as $k => $val){                                        
                                insert_update_cars_attributs_and_meta($k,$val,$insert_id);
                            }
                            							
                            $car_status = cdhl_search_array_keys($row, "car_status", $csv_post_data);
                            if(isset($car_status) && !empty($car_status)){
                                update_field('car_status', $car_status, $insert_id);
                            }
                                                
                            $attach_id = cdhl_import_pdf_file($row, $csv_post_data);									                                        
                            if(!empty($attach_id) && $attach_id != 'None'){
                                update_field('pdf_file', $attach_id, $insert_id);
                            }
                            
                            $post_content   = cdhl_search_array_keys($row, "vehicle_overview", $csv_post_data);
                            if($post_content != ''){
                                update_field('vehicle_overview', $post_content, $insert_id);
                            }
                            $imported_cars[$insert_id] = ($post_title); 
							cdhl_log_imported_cars( esc_html__('INSERTED' ,'cardealer-helper'), $post_title . esc_html__(' Successfully inserted.' ,'cardealer-helper'), $logfile );
                        }                            
                    }
                }
            }            
            
            if($cdhl_duplicate_check_vin != ''){
                
                /**
				* Rule III
                * IF Check for duplicate cars using: VIN Number is checked
                */
                $search_value  = cdhl_search_array_keys($row, $cdhl_duplicate_check_vin, $csv_post_data);
                $duplicate_ids = array_keys($current_check, $search_value);
                
                if(!empty($duplicate_ids)){
                    foreach($duplicate_ids as $duplicate_id){
                        $update_post = get_post($duplicate_id, ARRAY_A);
                        $insert_id = $update_post['ID'];
                        $imported_cars['duplicate_vin'][$duplicate_id] = $update_post['post_title'];

                        if(isset($all_cars[$duplicate_id])){
                            unset($all_cars[$duplicate_id]);
                        }

						if(!empty($update_post) && isset($_SESSION['import_post_data']['overwrite_existing']) && $_SESSION['import_post_data']['overwrite_existing'] == "on"){
							$update_log = 1;	// marking record update
                            $post_title   = cdhl_get_multiple_values($_SESSION['import_post_data']['car_titles'], $row, $csv_post_data);
                            $dependancy_categories = array();
                            $update_post['post_title']   = ($post_title);
                            
                            wp_update_post( $update_post );
                            $post_content = cdhl_search_array_keys($row, "vehicle_overview", $csv_post_data);
                            if(isset($post_content) && !empty($post_content)){
                                update_field('vehicle_overview', $post_content, $insert_id);
                            }                            

                            foreach($cars_attributes as $key => $option){
                                $key = (isset($option['slug']) && !empty($option['slug']) ? $option['slug'] : str_replace(" ", "_", strtolower($key)));
                                $value = cdhl_search_array_keys($row, $key, $csv_post_data);

                                if(isset($option['multiple'])){
                                    $value = cdhl_search_array_keys($row, $key, $csv_post_data);
                                } else {
                                    $value = cdhl_search_array_keys($row, $key, $csv_post_data);
                                    insert_update_cars_attributs_and_meta($key,$value,$insert_id);
                                }
                                

                                $tax_label = cdhl_search_array_keys($row, "tax_label", $csv_post_data);
                                if(isset($tax_label) && !empty($tax_label)){
                                    update_field('tax_label', $tax_label, $insert_id);
                                }
                                
                                $car_status = cdhl_search_array_keys($row, "car_status", $csv_post_data);
                                if(isset($car_status) && !empty($car_status)){
                                    update_field('car_status', $car_status, $insert_id);
                                }

                                $city_mpg = cdhl_search_array_keys($row, "city_mpg", $csv_post_data);
                                if(isset($city_mpg) && !empty($city_mpg)){
                                    update_field('city_mpg', $city_mpg, $insert_id);
                                }

                                $highway_mpg = cdhl_search_array_keys($row, "highway_mpg", $csv_post_data);
                                if(isset($highway_mpg) && !empty($highway_mpg)){
                                    update_field('highway_mpg', $highway_mpg, $insert_id);
                                }

                                $pdf_file = cdhl_search_array_keys($row, "pdf_file", $csv_post_data);
                                if(isset($pdf_file) && !empty($pdf_file)){
                                    update_field('pdf_file', $pdf_file, $insert_id);
                                }

                                $video_link = cdhl_search_array_keys($row, "video_link", $csv_post_data);
                                if(isset($video_link) && !empty($video_link)){
                                    update_field('video_link', $video_link, $insert_id);
                                }

                                $technical_specifications = cdhl_search_array_keys($row, "technical_specifications", $csv_post_data);
                                if(isset($technical_specifications) && !empty($technical_specifications)){
                                    update_field('technical_specifications', $technical_specifications, $insert_id);
                                }

                                $general_information = cdhl_search_array_keys($row, "general_information", $csv_post_data);
                                if(isset($general_information) && !empty($general_information)){
                                    update_field('general_information', $general_information, $insert_id);
                                }
                                
                                $post_excerpt = cdhl_search_array_keys($row, "excerpt", $csv_post_data);
                                if(isset($post_excerpt) && !empty($post_excerpt)){
                                    $my_post = array(
                                          'ID'           => $insert_id,
                                          'post_excerpt' => $post_excerpt,                                                  
                                    );                                            
                                    wp_update_post( $my_post );
                                }
                                

                                // Features & Options
                    		    $values = cdhl_search_array_keys($row, "features_and_options", $csv_post_data);
                    		    $features_and_options = array();
                    		    $dynamite =  "";

                    		    if(!empty($values)){
                    			    if(empty($dynamite)) {
                    				    if ( strstr( $values, "," ) ) {
                    					    $dynamite = ",";
                    				    } elseif ( strstr( $values, "<br>" ) ) {
                    					    $dynamite = "<br>";
                    				    } elseif ( strstr( $values, "|" ) ) {
                    					    $dynamite = "|";
                    				    } elseif ( strstr( $values, ";" ) ) {
                    					    $dynamite = ";";
                    				    }
                    			    }

                    			    if(isset($dynamite) && !empty($dynamite)){
                    				    $values   = explode($dynamite, $values);

                    				    foreach($values as $val){
                    					    $features_and_options[] = $val;
                    				    }
                    			    } else {
                    				    $features_and_options[] = $values;
                    			    }
                    		    }

                    		    if(!empty($features_and_options)){

                    			    $options = $car_categories_safe['features-options']['terms'];

                    			    foreach($features_and_options as $option){
                    				    $option = trim($option);                    				    
                                        $option = preg_replace('/\x{EF}\x{BF}\x{BD}/u', '', @iconv(mb_detect_encoding($option), 'UTF-8', $option));
                                        
                    				    if(is_array($options) && !in_array($option, $options)){
                                            if(!empty($option) && $option != 'None'){
                                                wp_set_object_terms( $insert_id, $option, 'car_features_options',false );
                                            }
                    				    }
                    			    }
                    		    }

                    		    /* Default Latitude & Longitude */
                    		    $default_latitude  = (isset($car_dealer_options['default_value_lat']) && !empty($car_dealer_options['default_value_lat']) ? $car_dealer_options['default_value_lat'] : "");
                    		    $default_longitude = (isset($car_dealer_options['default_value_long']) && !empty($car_dealer_options['default_value_long']) ? $car_dealer_options['default_value_long'] : "");

                                // map location
                                $vehicle_location='';
                    		    $vehicle_location = cdhl_search_array_keys($row, "vehicle_location", $csv_post_data);
								$getLatLnt = cdhl_getLatLnt( $vehicle_location );
                                $latitude  = $getLatLnt['lat'];
                    		    $longitude = $getLatLnt['lng'];                    		    

                    		    // map location
                    		    $location = array(
                    			    "vehicle_location"=>$vehicle_location,
                                    "latitude"  => $latitude,
                    			    "longitude" => $longitude,
                    			    "zoom"      => "10"
                    		    );
                                if( isset($location) && $getLatLnt['addr_found'] != '0' ){
                                    update_field('vehicle_location',$location,$insert_id);
                                }
                            }
                        }                        
                        
                        if(isset($overwrite_car_images) && ($overwrite_car_images == "true" || $overwrite_car_images == "on")){
                        	$update_log = 1;	// marking record update
                            $car_images = get_post_meta($duplicate_id, "car_images", true);
                            if(!empty($car_images)) {
                                foreach($car_images as $image_id) {
	                                wp_delete_attachment($image_id, true);
                                }
                            }		
                            $new_car_images = cdhl_import_car_images($row, $csv_post_data);
                            update_field('car_images',$new_car_images,$duplicate_id);		
                        }                        
                        
                        if(isset($overwrite_car_price) && ($overwrite_car_price == "true" || $overwrite_car_price == "on")){
							$update_log = 1;	// marking record update						
                            $regular_price = cdhl_search_array_keys($row, "regular_price", $csv_post_data);
							if(isset($regular_price) && !empty($regular_price)){
                                update_field('regular_price', $regular_price, $insert_id);
								$final_price = $regular_price;
                            }
							
							$sale_price = cdhl_search_array_keys($row, "sale_price", $csv_post_data);
                            if(isset($sale_price) && !empty($sale_price)){
                                update_field('sale_price', $sale_price, $insert_id);
								$final_price = $sale_price;
                            }
							// set final price
							if( isset( $final_price ) ) {
								update_post_meta( $insert_id, 'final_price', $final_price );
							}
                        }
						if(isset($_SESSION['import_post_data']['overwrite_existing']) && $_SESSION['import_post_data']['overwrite_existing'] == "on"){
							cdhl_log_imported_cars( "UPDATED", get_the_title($insert_id) . " is updated.", $logfile );
						} else {
							cdhl_log_imported_cars( "DUPLICATE", get_the_title($insert_id) . " is duplicate so not imported.", $logfile );
						}
                    }
                }
            }            
        }
        
		$duplicates = (isset($imported_cars['duplicate_vin']) && !empty($imported_cars['duplicate_vin']) ? $imported_cars['duplicate_vin'] : "");
        if(isset($imported_cars['duplicate_vin'])){
            unset($imported_cars['duplicate_vin']);    
        }
        
		if(!empty($imported_cars)){
           		$ins_post = array(
                    'post_type' => 'pgs_import_log',
                    'post_title' => esc_html__('CSV Imported ( '.date('Y-m-d') .')', 'cardealer-helper'),
                    'post_content' => ''
                );                            
                $post_id = wp_insert_post( $ins_post ); 
                $post_status = (isset($car_dealer_options['import_post_status']) && !empty($car_dealer_options['import_post_status']) ? $car_dealer_options['import_post_status'] : "publish");
                update_field( 'records_imported', count($imported_cars), $post_id );
                update_field( 'post_status', $post_status, $post_id );
        }
    }        
}

function insert_update_cars_attributs_and_meta($k,$val,$insert_id){
    switch( $k ){
		case 'car_images':
			if( !empty($val) ){
				update_field( 'car_images', $val , $insert_id );
			}
		break;
		case 'regular_price':
		case 'sale_price':
			if($k == 'regular_price'){
				$regular_price =  $val;
				$final_price = $regular_price;
				update_field('regular_price', $regular_price, $insert_id);
			}

			if($k == 'sale_price'){
				$sale_price =  $val;
				$final_price = $sale_price;
				update_field('sale_price', $sale_price, $insert_id);
			}
			
			// set final price
			if( isset( $final_price ) ) {
				update_post_meta( $insert_id, 'final_price', $final_price );
			}
		break;
		case 'tax_label':
			if(!empty($val) && $val != 'None'){
				update_field('tax_label', $val, $insert_id);
			}
		break;
		case 'city_mpg':
			if(!empty($val) && $val != 'None'){
				update_field('city_mpg', $val, $insert_id);
			}
		break;
		case 'highway_mpg':
			update_field('highway_mpg', $val, $insert_id);
		break;
		case 'video_link':
			update_field('video_link', $val, $insert_id);
		break;
		case 'year':
			$year = (string)$val;
			if(!empty($year) && $year != 'None'){
				wp_set_object_terms( $insert_id, $year, 'car_year',false );
			}
		break;
		case 'make':
			$make =  $val;
			if(!empty($val) && $val != 'None'){
				wp_set_object_terms( $insert_id, $make, 'car_make',false );
			}
		break;
		case 'model':
			$model =  $val;
			if(!empty($val) && $val != 'None'){
				wp_set_object_terms( $insert_id, $model, 'car_model',false );
			}
		break;
		case 'body-style':
			if(!empty($val)&& $val != 'None'){
				$body_style =  $val;
				wp_set_object_terms( $insert_id, $body_style, 'car_body_style',false );
			}
		break;
		case 'mileage':
			if(!empty($val) && $val != 'None'){
				$mileage =  $val;
				wp_set_object_terms( $insert_id, $mileage, 'car_mileage',false );
			}
		break;
		case 'fuel-type':
			if(!empty($val) && $val != 'None'){                                            
				wp_set_object_terms( $insert_id, $val, 'car_fuel_type',false );
			}
		break;
		case 'fuel-economy':
			if(!empty($val) && $val != 'None'){
				$fuel_economy =  $val;
				wp_set_object_terms( $insert_id, $fuel_economy, 'car_fuel_economy',false );
			}
		break;
		case 'trim':
			if(!empty($val) && $val != 'None'){                                            
				wp_set_object_terms( $insert_id, $val, 'car_trim',false );
			}
		break;
		case 'transmission':
			$transmission =  $val;
			if(!empty($val) && $val != 'None'){
				wp_set_object_terms( $insert_id, $transmission, 'car_transmission',false );
			}
		break;
		case 'condition':
			if(!empty($val) && $val != 'None'){
				if(preg_match('(new|New|N|n)', $val) === 1) {
					wp_set_object_terms( $insert_id, 'New', 'car_condition',false );
				}elseif(preg_match('(used|Used|U|u)', $val) === 1) {
					wp_set_object_terms( $insert_id, 'Used', 'car_condition',false );
				}else {
					wp_set_object_terms( $insert_id, 'Certified', 'car_condition',false );
				}
			}
		break;
		case 'drivetrain':
			$drivetrain =  $val;
			if(!empty($val) && $val != 'None'){
				wp_set_object_terms( $insert_id, $drivetrain, 'car_drivetrain',false );
			}
		break;
		case 'engine':
			$engine =  $val;
			if(!empty($val) && $val != 'None'){
				wp_set_object_terms( $insert_id, $engine, 'car_engine',false );
			}
		break;
		case 'exterior-color':
			$exterior_color =  $val;
			if(!empty($val) && $val != 'None'){
				wp_set_object_terms( $insert_id, $exterior_color, 'car_exterior_color',false );
			}
		break;
		case 'interior-color':
			$interior_color =  $val;
			if(!empty($val) && $val != 'None'){
				wp_set_object_terms( $insert_id, $interior_color, 'car_interior_color',false );
			}
		break;
		case 'stock-number':
			$stock_number =  $val;
			if(!empty($val) && $val != 'None'){
				wp_set_object_terms( $insert_id, $stock_number, 'car_stock_number',false );
			}
		break;
		case 'vin-number':
			$vin_number =  $val;
			if(!empty($val) && $val != 'None'){
				wp_set_object_terms( $insert_id, $vin_number, 'car_vin_number' );
			}
		break;
		case 'features-options':
			$features_options =  $val;
			if(!empty($val) && $val != 'None'){
				$car_features_options = explode(',',$val);
				foreach($car_features_options as $option){
					wp_set_object_terms( $insert_id, $option, 'car_features_options',true );
				}
			}
		break;
		case 'technical_specifications':
			if(!empty($val) && $val != 'None'){
				update_field('technical_specifications', $val, $insert_id);
			}
		break;
		case 'general_information':
			if(!empty($val) && $val != 'None'){
				update_field('general_information', $val, $insert_id);
			}
		break;
		case 'post_excerpt':
			if(!empty($val) && $val != 'None'){
				//update_field('post_excerpt', $val, $insert_id);
				$my_post = array(
					  'ID'           => $insert_id,
					  'post_excerpt' => $val,                                                  
				);                                            
				wp_update_post( $my_post );
			}
		break;
		case 'location_map':
			$getLatLnt = cdhl_getLatLnt( $val['vehicle_location'] );
			$latitude  = $getLatLnt['lat'];
			$longitude = $getLatLnt['lng'];
			$mapvalue = array(
				'address' => $val['vehicle_location'],
				'lng' => $longitude,
				'lat' => $latitude
			);
			$location_map =  $mapvalue;
			if( isset($location_map) && $getLatLnt['addr_found'] != '0' ){
				update_field('vehicle_location',$location_map,$insert_id);
			}
		break;
		case 'engine':
		break;
	}	
}


function cdhl_get_file_data($type, $file){
    $data = array(
        'status' => 'error',
        'msg' => 'Somthing went wrong'        
    );
    if($type == "from_url"){
        /**
         * Check is valid url
         */ 
        if(filter_var($file, FILTER_VALIDATE_URL)){
            /**
             * Get file content from url
             */
            $wp_file_get = wp_remote_get( $file, array("timeout" => 30) );
    
            /**
             * Check for error
             */
            if(!is_wp_error($wp_file_get)){    
                
                $import_file_conten = $wp_file_get['body'];    
                $data = array(
                    'status' => 'success',
                    'msg' => '',
                    'import_data' => $import_file_conten,
                );
                
                $_SESSION['cars_csv']['import_conten'] = $import_file_conten;
            } else {               
               $data[] = array(
                    'status' => 'error',
                    'msg' => $wp_file_get->get_error_message(),                    
                );
            }
        } else {             
            $data[] = array(
                'status' => 'error',
                'msg' => esc_html__("Not a valid URL", "cardealer-helper"),                    
            );
        }
    
    
    } elseif($type == "from_file") {
        
        $ext=pathinfo($file["name"],PATHINFO_EXTENSION);
        if($ext == "csv"){
            $empty_file_string = esc_html__("There was no file uploaded", "cardealer-helper");
    
            if(empty($file)){
                $data[] = array(
                    'status' => 'error',                        
                    'msg' => $empty_file_string    
                );
            }
           
            switch($file['error']){
                case 0:
                    $import_file_conten = file_get_contents($file['tmp_name']);
					$data = array(
                        'status' => 'success',                        
                        'import_data' => $import_file_conten
                    );
                    $_SESSION['cars_csv']['import_conten'] = $import_file_conten;
                    break;
                case 1:                    
                    $data[] = array(
                        'status' => 'error',
                        'msg' => esc_html__("Your file exceeded your servers maximum upload size", "cardealer-helper")        
                    );
                    break;
                case 2:                    
                    $data[] = array(
                        'status' => 'error',
                        'msg' => esc_html__("Your file exceeded the form maximum upload size", "cardealer-helper")        
                    );
                    break;
                case 3:                    
                    $data[] = array(
                        'status' => 'error',
                        'msg' => esc_html__("The file was only partially uploaded", "cardealer-helper")        
                    );
                    break;
                case 4:                    
                    $data[] = array(
                        'status' => 'error',
                        'msg' => $empty_file_string        
                    );
                    break;
                case 6:                    
                    $data[] = array(
                        'status' => 'error',
                        'msg' => esc_html__("Your server is missing a temporary folder", "cardealer-helper")        
                    );
                    break;
                case 7:                    
                    $data[] = array(
                        'status' => 'error',
                        'msg' => esc_html__("Your server cannot write the file to the disk", "cardealer-helper")        
                    );
                    break;
                case 8:                    
                    $data[] = array(
                        'status' => 'error',
                        'msg' => esc_html__("A PHP extension installed on your server has stopped the upload", "cardealer-helper")        
                    );
                    break;
            }                
        } else {
            
            $data[] = array(
                'status' => 'error',
                'msg' => esc_html__("Invalid file type", "cardealer-helper")        
            );
        }    
        
        if($data['status'] != "success"){
            $_SESSION['cars_csv']['error'] = $data;
        }
    }
    return $data;
}



/**
 * Add CSV file content into a array
 */
function cdhl_add_file_content_to_array( $import_file_content, $xml_parent = ""){
    global $car_dealer_options;
    $return = array();

    /**
     * Check CSV parseCSV library is exists
     */
    if(!class_exists("parseCSV")){
        include(CDHL_PATH ."includes/import_csv_xml/parsecsv.lib.php");
    }

    $csv  = new parseCSV();
    $csv->delimiter = (isset($car_dealer_options['csv_delimiter']) && !empty($car_dealer_options['csv_delimiter']) ? $car_dealer_options['csv_delimiter'] : ",");
    $csv->parse($import_file_content);
    $arr_data = cdhl_check_empty_array($csv->data);
    $csv_rows   = array_values($arr_data);
    $titles = $csv->titles;
    $is_arr = cdhl_array_validate($csv_rows);    
    if($is_arr) {
        $_SESSION['cars_csv']['file_row'] = $csv_rows;
        $_SESSION['cars_csv']['titles']   = $titles;                    
        $return = array($csv_rows, $titles);
    } else {
        $return = array("error", esc_html__("The file must not contain numbers as the column title", "cardealer-helper"));
    }
	return $return;
}


function cdhl_check_empty_array($arr_data) {
    foreach ($arr_data as $key => $value) {
        if (is_array($value)) {
            $arr_data[$key] = cdhl_check_empty_array($arr_data[$key]);
        }

        if (empty($arr_data[$key])) {
            unset($arr_data[$key]);
        }
    }
    return $arr_data;
}

function cdhl_array_validate($array_data){
    $array_valid = true;

    if(!empty($array_data)){
        foreach($array_data[0] as $key => $value){
            if(is_int($key)){
                $array_valid = false;
                break;
            }
        }
    } else {
        $array_valid = false;
    }
	return $array_valid;
}


function cdhl_get_feature_and_option(){
	$attributs=array();
	$terms = get_terms(array(
		'taxonomy' => 'car_features_options',
		'hide_empty' => false,
	));        
	
	$taxonomy_name = get_taxonomy( 'car_features_options' );        
	$slug= $taxonomy_name->rewrite['slug'];  
	$label = $taxonomy_name->labels->menu_name;
	
	foreach($terms as $tdata){                
		$attributs[$slug]['terms'][] = $tdata->slug;
		$attributs[$slug]['label'] = $label;
		$attributs[$slug]['slug'] = $slug;
	}
	return $attributs;
}


function cdhl_get_multiple_values($value, $row, $csv){
    $return = "";
	if(!empty($value)){ 
	    foreach($value as $title_value){
		    $return .= cdhl_search_array_keys($row, $title_value, $csv, true) . " ";
	    }
		$return = rtrim($return, " ");
    }
    return $return;
}

   
function cdhl_search_array_keys($array, $term, $references, $title_val = ""){    
    $count  = array_count_values($references);   
    $return = "";
    if(isset($count[$term]) && $count[$term] >= 2){
        $keys = array_keys($references, $term);
        foreach($keys as $key){
            if(strpos($key, "|") !== false){
                $paths = explode("|", $key);
                $items = $array;
                foreach($paths as $ndx){
                    if(isset($items[$ndx])) {
                        $items = $items[ $ndx ];
                    }
                }

                if(!is_array( $items )) {
                    $return .= $items . "<br>";
                }
            } else {
                $return .= (isset($array[$key]) && !empty($array[$key]) ? $array[$key] : "") . "<br>";
            }
        }
    } else {
        $key = array_search($term, $references);
		if(strpos($key, "|") !== false){
            $paths = explode("|", $key);
            $items = $array;
            foreach($paths as $ndx){
                $items = $items[$ndx];
            }

            $return .= $items;
        } else {
            if(!empty($title_val)){
                if(strpos($term, "|") !== false) {
                    $paths = explode( "|", $term );
                    $items = $array;

                    foreach ( $paths as $ndx ) {
	                    $items = $items[ $ndx ];
                    }

                    $value = (isset($items) && !empty($items) ? $items : "");
                } else {
                    $value = (isset($array[$term]) && !empty($array[$term]) ? $array[$term] : "");
                }

                $return .= $value;
            } else {
                $return .= (isset($array[array_search($term, $references)]) && !empty($array[array_search($term, $references)]) ? $array[array_search($term, $references)] : "");
            }
        }
    }    
    return $return;
}


function cdhl_get_car_meta(&$cars_attributes, &$cdhl_cars_attributes_safe, $row, $csv){
	global $cars, $car_dealer_options;
	$post_meta = array();

    /* Default Latitude & Longitude */
    $default_latitude  = (isset($car_dealer_options['default_value_lat']) && !empty($car_dealer_options['default_value_lat']) ? $car_dealer_options['default_value_lat'] : "");
    $default_longitude = (isset($car_dealer_options['default_value_long']) && !empty($car_dealer_options['default_value_long']) ? $car_dealer_options['default_value_long'] : "");

    foreach($cars_attributes as $key => $option){
	    if(isset($option['multiple'])){
			$key   = (isset($option['slug']) && !empty($option['slug']) ? $option['slug'] : str_replace(" ", "_", strtolower($key)));
		    $value = cdhl_search_array_keys($row, $key, $csv);
	    } else {
		    $value = cdhl_search_array_keys($row, $key, $csv);            
		    if(empty($value)){
			    $value = esc_html__("None", "cardealer-helper");
		    }

		    $terms = (isset($cdhl_cars_attributes_safe[$key]['terms']) && !empty($cdhl_cars_attributes_safe[$key]['terms']) ? $cdhl_cars_attributes_safe[$key]['terms'] : array());
            if(is_array($terms) && !in_array($value, $terms) && !empty($value) && isset($option['compare_value']) && $option['compare_value'] == "="){
			    $slug_decode = slug_decode($value);
                $cdhl_cars_attributes_safe[$key]['terms'][$slug_decode] = $value;
		    }                    
	    }

	    if(!empty($value) && $value != "n-a") {
		    $post_meta[$key] = $value;
		    if($value != esc_html__("None", "cardealer-helper")) {					    
                $slug_decode = slug_decode($value);
		    }
	    }
    }

    $car_images = cdhl_import_car_images($row, $csv);            
    if(isset($car_images) && !empty($car_images)){
	    $post_meta['car_images'] = $car_images;
    }
    
    $pdf_file = cdhl_import_pdf_file($row, $csv);
    if(isset($pdf_file) && !empty($pdf_file)){
	    $post_meta['pdf_file'] = $pdf_file;
    }
    
    $post_meta["sale_price"]    = cdhl_search_array_keys($row, "sale_price", $csv);
    $post_meta["regular_price"] = cdhl_search_array_keys($row, "regular_price", $csv);
    $post_meta["tax_label"]     = cdhl_search_array_keys($row, "tax_label", $csv);
    $post_meta["car_status"]    = cdhl_search_array_keys($row, "car_status", $csv);            
    $post_meta["city_mpg"]      = cdhl_search_array_keys($row, "city_mpg", $csv);
    $post_meta["highway_mpg"]   = cdhl_search_array_keys($row, "highway_mpg", $csv);
    $post_meta["technical_specifications"]   = cdhl_search_array_keys($row, "technical_specifications", $csv);
    $post_meta["general_information"]   = cdhl_search_array_keys($row, "general_information", $csv);
    $post_meta["post_excerpt"]   = cdhl_search_array_keys($row, "excerpt", $csv);
    
    // Features & Options
    $values = cdhl_search_array_keys($row, "features_and_options", $csv);
    $features_and_options = array();
    $dynamite =  "";
	if(!empty($values)){
	    if(empty($dynamite)) {
		    if ( strstr( $values, "," ) ) {
			    $dynamite = ",";
		    } elseif ( strstr( $values, "<br>" ) ) {
			    $dynamite = "<br>";
		    } elseif ( strstr( $values, "|" ) ) {
			    $dynamite = "|";
		    } elseif ( strstr( $values, ";" ) ) {
			    $dynamite = ";";
		    }
	    }

	    if(isset($dynamite) && !empty($dynamite)){
		    $values   = explode($dynamite, $values);

		    foreach($values as $val){
			    $features_and_options[] = $val;
		    }
	    } else {
		    $features_and_options[] = $values;
	    }
    }

    if(!empty($features_and_options)){
	    $options = $cdhl_cars_attributes_safe['features-options']['terms'];
		foreach($features_and_options as $option){
		    $option = trim($option);
		    $option = preg_replace('/\x{EF}\x{BF}\x{BD}/u', '', @iconv(mb_detect_encoding($option), 'UTF-8', $option));
            //features-options
		    if(is_array($options) && !in_array($option, $options)){
			    $cdhl_cars_attributes_safe['features-options']['terms'][] = $option;
		    }
	    }
    }

    // map location
    $vehicle_location='';
    $vehicle_location = cdhl_search_array_keys($row, "vehicle_location", $csv);
    $getLatLnt = cdhl_getLatLnt( $vehicle_location );
	$latitude  = $getLatLnt['lat'];
	$longitude = $getLatLnt['lng'];
	
    // map location
    $location = array(
	    "vehicle_location"=>$vehicle_location,
        "latitude"  => $latitude,
	    "longitude" => $longitude,
	    "zoom"      => "10"
    );
	if( $getLatLnt['addr_found'] != '0' ){
		$post_meta["location_map"] = $location;
	}
    $post_meta["video_link"] = cdhl_search_array_keys($row, "video_link", $csv);           
    return $post_meta;
}

function cdhl_import_pdf_file($row, $csv){
    $values = cdhl_search_array_keys($row, "pdf_file", $csv);
    $pdf_file = array();
    $dynamite       = '';

    if(!empty($values)){
	    if(empty($dynamite)) {
		    if ( strstr( $values, "," ) ) {
			    $dynamite = ",";
		    } elseif ( strstr( $values, "<br>" ) ) {
			    $dynamite = "<br>";
		    } elseif ( strstr( $values, "|" ) ) {
			    $dynamite = "|";
		    } elseif ( strstr( $values, ";" ) ) {
			    $dynamite = ";";
		    }
	    }

	    if(isset($dynamite) && !empty($dynamite)){
		    $values   = explode($dynamite, $values);

		    foreach($values as $val){
			    if(!empty($val)){
				    $val = cdhl_auto_add_http(trim(urldecode($val)));

				    if($val != "http://Array" && filter_var($val, FILTER_VALIDATE_URL) !== FALSE){					    
                        $upload_image = cdhl_get_upload_pdf_file($val);
				    }
			    }
		    }
	    } else {
		    $values = cdhl_auto_add_http(trim($values));

		    if($values != "http://Array" && filter_var($values, FILTER_VALIDATE_URL) !== FALSE){			    
				$upload_image = cdhl_get_upload_pdf_file($values);
		    }
	    }
    }            
    return (!empty($upload_image) ? $upload_image : "");
}


function cdhl_auto_add_http($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}

function cdhl_get_upload_pdf_file($file_url){
    $file = $file_use = str_replace("\"", "", $file_url);
    $pdf_args = array(
					'timeout'   => 600, 
					'redirection' => 5,
					'headers'   => array('content-type'), 
					'cookies'   => null,
					'compress' 	=> true,
					'decompress'=> false,
					'sslverify' => true,
					'stream'	=> false,
					'filename'	=> null,
					'body'  	=> null,
					'blocking'	=> true
				);
    $get = wp_remote_get( $file, $pdf_args);
    if(!is_wp_error($get) && $get['response']['code'] == 200) {
		// Access protected property of obj
		$getDataArray = Closure::bind(  function($prop){return $this->$prop;}, $get['headers'], $get['headers'] ); 
		$type = $getDataArray('data')['content-type'];
		
		$allowed_images = array( "application/pdf" );
        $extension      = pathinfo( $file, PATHINFO_EXTENSION );
		if ( empty( $type ) ) {
	        if ( $extension == "pdf" ) {
		        $type = "application/pdf";
	        }                    
        }
		if ( ! $type && in_array( $type, $allowed_images ) ) {
	        return false;
        }
		if ( empty( $extension ) ) {
	        $content_type = $type;
			if ( strstr( $content_type, "application/pdf" ) ) {
		        $file_use = $file . ".pdf";
		        $type     = "application/pdf";
	        }
        }
        $mirror = wp_upload_bits( basename( $file_use ), '', $get['body'] );
        $attachment = array(
	        'post_title'     => basename( $file ),
	        'post_mime_type' => $type
        );

        if ( isset( $mirror ) && ! empty( $mirror ) ) {
	        $attach_id = wp_insert_attachment( $attachment, $mirror['file'] );
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			$attach_data = wp_generate_attachment_metadata( $attach_id, $mirror['file'] );
			wp_update_attachment_metadata( $attach_id, $attach_data );
        } else {
	        $attach_id = "";
        }
        return $attach_id;
    } else {
    	return "";
    }
}

function cdhl_import_car_images($row, $csv){ 
    global $cars, $car_dealer_options;    
    $values = cdhl_search_array_keys($row, "car_images", $csv);
    
    $car_images = array();
    $dynamite       = '';

    if(!empty($values)){
	    if(empty($dynamite)) {
		    if ( strstr( $values, "," ) ) {
			    $dynamite = ",";
		    } elseif ( strstr( $values, "<br>" ) ) {
			    $dynamite = "<br>";
		    } elseif ( strstr( $values, "|" ) ) {
			    $dynamite = "|";
		    } elseif ( strstr( $values, ";" ) ) {
			    $dynamite = ";";
		    }
	    }

	    if(isset($dynamite) && !empty($dynamite)){
		    $values   = explode($dynamite, $values);
		    foreach($values as $val){ 
			    if(!empty($val)){
				    $val = cdhl_auto_add_http(trim(urldecode($val)));
					if($val != "http://Array" && filter_var($val, FILTER_VALIDATE_URL) !== FALSE){
						$upload_image = cdhl_get_upload_image($val);
						if(!empty($upload_image)) {
						    $car_images[] = $upload_image;
					    }					    
				    }
			    }
		    }
	    } else {
		    $values = cdhl_auto_add_http(trim($values));
			if($values != "http://Array" && filter_var($values, FILTER_VALIDATE_URL) !== FALSE){
			    
			    $upload_image = cdhl_get_upload_image($values);

			    if(!empty($upload_image)) {
				    $car_images[] = $upload_image;
			    }
			    
		    }
	    }
    }            
    return (!empty($car_images) ? $car_images : "");
}

function cdhl_get_upload_image($image_url){
    $image = $image_use = str_replace("\"", "", $image_url);
	$img_args = array(
					'timeout'   => 5, 
					'headers'   => array('content-type'), 
					'cookies'   => null,
					'compress' 	=> true,
					'decompress'=> false,
					'sslverify' => true,
					'stream'	=> false,
					'filename'	=> null,
					'body'  	=> null,
					'blocking'	=> true
				);
    $get   = wp_remote_get( $image , $img_args );
	if(!is_wp_error($get) && $get['response']['code'] == 200) {
		// Access protected property of obj
		$getDataArray = Closure::bind(  function($prop){return $this->$prop;}, $get['headers'], $get['headers'] ); 
		$type = $getDataArray('data')['content-type'];

        $allowed_images = array( "image/jpg", "image/jpeg", "image/png", "image/gif" );
        $extension      = pathinfo( $image, PATHINFO_EXTENSION );
        
        if ( empty( $type ) ) {
	        if ( $extension == "jpg" || $extension == "jpeg" ) {
		        $type = "image/jpg";
	        } elseif ( $extension == "png" ) {
		        $type = "image/png";
	        } elseif ( $extension == "gif" ) {
		        $type = "image/gif";
	        }
        }

        if ( ! $type && in_array( $type, $allowed_images ) ) {
	        return false;
        }

        if ( empty( $extension ) ) {
	        $content_type = $type;
			if ( strstr( $content_type, "image/jpg" ) || strstr( $content_type, "image/jpeg" ) ) {
		        $image_use = $image . ".jpg";
		        $type      = "image/jpg";
	        } elseif ( strstr( $content_type, "image/png" ) ) {
		        $image_use = $image . ".png";
		        $type      = "image/png";
	        } elseif ( strstr( $content_type, "image/gif" ) ) {
		        $image_use = $image . ".gif";
		        $type      = "image/gif";
	        }
        }

        $mirror = wp_upload_bits( basename( $image_use ), '', $get['body'] );
		$attachment = array(
	        'post_title'     => basename( $image ),
	        'post_mime_type' => $type
        );

        if ( isset( $mirror ) && ! empty( $mirror ) ) {
	        $attach_id = wp_insert_attachment( $attachment, $mirror['file'] );
	        require_once( ABSPATH . 'wp-admin/includes/image.php' );
	        $attach_data = wp_generate_attachment_metadata( $attach_id, $mirror['file'] );
	        wp_update_attachment_metadata( $attach_id, $attach_data );
        } else {
	        $attach_id = "";
        }
        return $attach_id;
    } else {
    	return "";
    }
}

function slug_decode( $text ) {
	if ( ! empty( $text ) && is_string( $text ) ) {
		$char_map = array(			
			'©'  => 'c',
			'®'  => 'r',
		);
		$text = str_replace( array_keys( $char_map ), $char_map, $text );
		$text = preg_replace( '~[^\\pL\d]+~u', '-', $text );		
		$text = trim( $text, '-' );		
		$text = iconv( 'UTF-8', 'ASCII//TRANSLIT', utf8_encode( $text ) );		
		$text = strtolower( $text );		
		$text = preg_replace( '~[^-\w]+~', '', $text );

		if ( empty( $text ) ) {
			return 'n-a';
		}
	}
	return $text;
}

function cdhl_log_imported_cars( $type, $message, $file ){
	// UPDATE DB FIELD TO LOG THAT LOGG FILE IS GENERATED, WILL BE USED TO KNOW THAT IT HAS BEEN READ OR NOT.
	if( ! get_option( 'cdhl_import_log' ) ) {
		update_option('cdhl_import_log', 'generated');
	}
	$open = fopen( $file, "a" );
	if( $type !== 'CREATE' ){
		$log_txt = date_i18n( 'm-d-Y @ H:i:s' ). " " . " ". $type . " ". $message ."\n";
		$write = fputs( $open, $log_txt );
	} else {
		$write = fputs( $open, '' );
	}
	fclose($open);
}

function cdhl_getLatLnt($address){
    global $car_dealer_options;
	$gapi = isset($car_dealer_options['google_maps_api'])? $car_dealer_options['google_maps_api'] : "";
    $vehicle_location = urlencode($address);
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=". $vehicle_location ."&sensor=false&key=" . $gapi;  
    try {
		$response = file_get_contents($url);        
		$response = json_decode($response, true);                 
		$lat = $response['results'][0]['geometry']['location']['lat'];
		$long = $response['results'][0]['geometry']['location']['lng'];
	} catch( Exception $e ) {
		$lat = $long = '';
	}
	
	$data = array(
        'lat' => $lat,
        'lng' => $long
    );  
	if( empty($lat) || empty($long) ){
		$data['addr_found'] = '0';
	} else {
		$data['addr_found'] = '1';
	}
 return $data;
}
?>