<?php
/**
 * Add admin manu for cars vin import 
 */ 
function cdhl_admin_vin_menu() {	
    add_submenu_page( 'edit.php?post_type=cars', esc_html__('Cars VIN Import', 'cardealer-helper'), esc_html__('Cars VIN Import', 'cardealer-helper'), "manage_options", 'cars-vin-import', 'cdhl_cars_vin_import' ); 
}
add_action('admin_menu', 'cdhl_admin_vin_menu');

/**
 * Main Call back funcrion 
 */ 
function cdhl_cars_vin_import() { 
    global $car_dealer_options, $cars;

    $vin  = (isset($_GET['vin']) && !empty($_GET['vin']) ? $_GET['vin'] : ""); 

	if(!empty($vin)) {
        $responce_body = cdhl_get_vin_data( $vin );                 
        if(isset($responce_body->errorType) && !empty($responce_body->errorType)){                
            if(isset($responce_body->errorType) && !empty($responce_body->errorType)){
                echo "<div class='notice notice-error cars-vin-error'><span class='cars_text_error'>";
    
                echo $responce_body->message . "<br>";
    
                echo "</span></div>";
            }
            echo "<br /><br />";
            echo "Return to <a href=".admin_url( 'edit.php?post_type=cars&page=cars-vin-import' ).">car import page</a>";
			die;
        } else {
            $body_arr = array();$body_media = array();
            if(isset($responce_body['body_encode']) && !empty($responce_body['body_encode'])){
                $body_data = $responce_body['body_encode'];
                $body_arr = cdhl_object_to_array_conversion( $body_data );
            } 
            if(isset($responce_body['style_media']) && !empty($responce_body['style_media'])){
                $body_media = $responce_body['style_media'];
            }            
            $body = array_merge($body_arr,$body_media);			
        }				
    }                
	?>

    <div class="wrap cdhl_car_vin_import">
        <h2 style="display: inline-block;"><?php esc_html_e("Cars VIN Import", "cardealer-helper"); ?></h2> 
        <br />
        <?php 
        /**
         * When post mapped data for save
         */ 
        if(isset($_POST['vin_import']) && !empty($_POST['vin_import'])){
			
			$post_title = "";
            $vin_import = $_POST['vin_import'];			
			
            if(isset($vin_import['cars_title']) && !empty($vin_import['cars_title'])){
                foreach($vin_import['cars_title'] as $cars_title){
                    $cars_title = cdhl_get_car_vin_value($cars_title, $body);
                    $post_title .= sanitize_text_field($cars_title) . " ";
                }
            }
            $post_title   = trim($post_title);
			

            // insert post, get id
            $insert_info    = array(
                'post_type'     => "cars",
                'post_title'    => $post_title,                
                'post_status'   => "publish"
            );           

            $insert_id = wp_insert_post( $insert_info );
                        
            if($insert_id){
                
                $cdhl_cars_attributes_safe = $cdhl_cars_attributes= cardealer_get_all_taxonomy_with_terms();                
                // add                
                foreach($cdhl_cars_attributes as $key => $attributes){                    
                    
                    $value      = "";
                    $attr_safe_key   = (isset($attributes['slug']) && !empty($attributes['slug']) ? $attributes['slug'] : str_replace(" ", "_", strtolower($key)));//str_replace(" ", "_", strtolower($key));
                    
                    
                    if(isset($vin_import[$attr_safe_key]) && !empty($vin_import[$attr_safe_key]) && is_array($vin_import[$attr_safe_key])){
                        foreach ($vin_import[$attr_safe_key] as $key => $vin_import_value) {
                            $value .= cdhl_get_car_vin_value($vin_import_value, $body) . " ";
                        }
                    } elseif(isset($vin_import[$attr_safe_key]) && !empty($vin_import[$attr_safe_key])) {
                        $value = cdhl_get_car_vin_value($vin_import[$attr_safe_key], $body);
                    }
                    
                    
                        
                    if($key == 'year'){                        
                        $year = (string)$value;
                        if(isset($year) && !empty($year) && $year != 'None'){
                            wp_set_object_terms( $insert_id, $year, 'car_year',false );                                                        
                        }
                        
                    }

                    if($key == 'make'){
                        $make =  $value;
                        if(!empty($value) && $value != 'None'){
                            wp_set_object_terms( $insert_id, $make, 'car_make',false );
                        }
                    }

                    if($key == 'model'){
                        $model =  $value;
                        if(!empty($value) && $value != 'None'){
                            wp_set_object_terms( $insert_id, $model, 'car_model',false );
                        }
                    }

                    if($key == 'body-style'){
                        if(!empty($value)&& $value != 'None'){
                            $body_style =  $value;
                            wp_set_object_terms( $insert_id, $body_style, 'car_body_style',false );
                        }
                    }

                    if($key == 'mileage'){                        
                        if(!empty($value) && $value != 'None'){
                            $mileage = (string)$value;
                            wp_set_object_terms( $insert_id, $mileage, 'car_mileage',false );                           
                        }
                    }

                    if($key == 'fuel-economy'){
                        if(!empty($value) && $value != 'None'){
                            $fuel_economy =  $value;
                            wp_set_object_terms( $insert_id, $fuel_economy, 'car_fuel_economy',false );
                        }
                    }

                    if($key == 'transmission'){
                        $transmission =  $value;
                        if(!empty($value) && $value != 'None'){
                            wp_set_object_terms( $insert_id, $transmission, 'car_transmission',false );
                        }
                    }

                    if($key == 'condition'){
                        $condition =  $value;
                        if(!empty($value) && $value != 'None'){
                            if(preg_match('(new|New|N|n)', $condition) === 1) {
                                wp_set_object_terms( $insert_id, 'New', 'car_condition',false );
                            }elseif(preg_match('(used|Used|U|u)', $condition) === 1) {
                                wp_set_object_terms( $insert_id, 'Used', 'car_condition',false );
                            }else {
                                wp_set_object_terms( $insert_id, 'Certified', 'car_condition',false );
                            }                            
                        }
                    }



                    if($key == 'drivetrain'){
                        $drivetrain =  $value;
                        if(!empty($value) && $value != 'None'){
                            wp_set_object_terms( $insert_id, $drivetrain, 'car_drivetrain',false );
                        }
                    }

                    if($key == 'engine'){
                        $engine = (string)$value;
                        if(!empty($engine) && $engine != 'None'){
                            wp_set_object_terms( $insert_id, $engine, 'car_engine',false );
                        }
                    }



                    if($key == 'exterior-color'){
                        $exterior_color = (string)$value;
                        if(!empty($value) && $value != 'None'){
                            wp_set_object_terms( $insert_id, $exterior_color, 'car_exterior_color',false );
                        }
                    }
                    
                    if($key == 'interior-color'){
                        $interior_color = (string)$value;
                        if(!empty($interior_color) && $interior_color != 'None'){
                            wp_set_object_terms( $insert_id, $interior_color, 'car_interior_color',false );                            
                        }                       
                    }
                    
                    if($key == 'stock-number'){
                        $stock_number = (string)$value;
                        if(!empty($value) && $value != 'None'){
                            wp_set_object_terms( $insert_id, $stock_number, 'car_stock_number',false );
                        }
                    }

                    if($key == 'vin-number'){
                        $vin_number = (string)$value;
                        if(!empty($value) && $value != 'None'){
                            wp_set_object_terms( $insert_id, $vin_number, 'car_vin_number' );
                        }
                    }                        

                    if($key == 'features-options'){
                        $features_options = $value;
                        if(!empty($value) && $value != 'None'){
                            $car_features_options = explode(',',$value);
                            foreach($car_features_options as $option){
                                wp_set_object_terms( $insert_id, $option, 'car_features_options',false );
                            }
                        }
                    }
                                        
                }
                $regular_price = (isset($vin_import['regular_price']) && !empty($vin_import['regular_price']) ? cdhl_get_car_vin_value($vin_import['regular_price'], $body) : "");
                if(isset($regular_price) && !empty($regular_price)){
                    update_field('regular_price', $regular_price, $insert_id);
					$final_price = $regular_price;
                }
                
                $sale_price = (isset($vin_import['sale_price']) && !empty($vin_import['sale_price']) ? cdhl_get_car_vin_value($vin_import['sale_price'], $body) : "");
                if(isset($sale_price) && !empty($sale_price)){
                    update_field('sale_price', $sale_price, $insert_id);
					$final_price = $sale_price;
                }
				// set final price
				if( isset( $final_price ) ) {
					update_post_meta( $insert_id, 'final_price', $final_price );
				}
				
                $tax_label = (isset($vin_import['tax_label']) && !empty($vin_import['tax_label']) ? cdhl_get_car_vin_value($vin_import['tax_label'], $body) : "");
                if(isset($tax_label) && !empty($tax_label)){
                    update_field('tax_label', $tax_label, $insert_id);    
                }                
                
                $post_excerpt = (isset($vin_import['excerpt']) && !empty($vin_import['excerpt']) ? cdhl_get_car_vin_value($vin_import['excerpt'], $body) : "");
                if(isset($post_excerpt) && !empty($post_excerpt)){
                    $my_post = array(
                          'ID'           => $insert_id,
                          'post_excerpt' => $post_excerpt,                                                  
                    );                                            
                    wp_update_post( $my_post );    
                }
                
                $city_mpg = (isset($vin_import['city_mpg']) && !empty($vin_import['city_mpg']) ? cdhl_get_car_vin_value($vin_import['city_mpg'], $body) : "");
                if(isset($city_mpg) && !empty($city_mpg)){
                    update_field('city_mpg', $city_mpg, $insert_id);    
                }                

                $highway_mpg = (isset($vin_import['highway_mpg']) && !empty($vin_import['highway_mpg']) ? cdhl_get_car_vin_value($vin_import['highway_mpg'], $body) : "");
                if(isset($highway_mpg) && !empty($highway_mpg)){
                    update_field('highway_mpg', $highway_mpg, $insert_id);    
                }
                                                   
                $video_link = (isset($vin_import['video_link']) && !empty($vin_import['video_link']) ? cdhl_get_car_vin_value($vin_import['video_link'], $body) : "");
                if(isset($video_link) && !empty($video_link)){
                    update_field('video_link', $video_link, $insert_id);    
                }                                
                
                $technicalspecification = "";                
                if(isset($vin_import['technical_specifications']) && !empty($vin_import['technical_specifications'])){
                    foreach($vin_import['technical_specifications'] as $technical_specification){
                        $technical_specification = cdhl_get_car_vin_value($technical_specification, $body);                         
                        $technicalspecification .= sanitize_text_field($technical_specification) . " ";                        
                    }
                }
                $technicalspecification = trim($technicalspecification);
                if(isset($technicalspecification) && !empty($technicalspecification)){
                    update_field('technical_specifications', $technicalspecification, $insert_id);    
                }
                 
                $generalinformation = "";
                
                if(isset($vin_import['general_information']) && !empty($vin_import['general_information'])){
                    foreach($vin_import['general_information'] as $general_information){
                        $general_information = cdhl_get_car_vin_value($general_information, $body);                         
                        $generalinformation .= sanitize_text_field($general_information) . " ";                        
                    }
                }
                $generalinformation = trim($generalinformation);                
                if(isset($generalinformation) && !empty($generalinformation)){
                    update_field('general_information', $generalinformation, $insert_id);    
                }
                
                
                
                // PDF File
                $pdf_file = (isset($vin_import['pdf_file']) && !empty($vin_import['pdf_file']) ? $vin_import['pdf_file'] : "");
                if(isset($pdf_file) && !empty($pdf_file)){                    
                    $val = cdhl_get_car_vin_value($pdf_file, $body);
                    if(filter_var($val, FILTER_VALIDATE_URL)){
                        $pdf_file = get_upload_pdf_file($val);
                        update_field('pdf_file', $pdf_file, $insert_id);
                    }
                }                                 
                
                $post_content = "";
                if(isset($vin_import['vehicle_overview']) && !empty($vin_import['vehicle_overview'])){
                    foreach($vin_import['vehicle_overview'] as $overview){
                        $overview = cdhl_get_car_vin_value($overview, $body);                         
                        $post_content .= sanitize_text_field($overview) . " ";                        
                    }
                }
                $post_content = trim($post_content);
                if($post_content != ''){
                    update_field('vehicle_overview', $post_content, $insert_id);                    
                }                
                $location = (isset($vin_import['location_map']) && !empty($vin_import['location_map']) ? cdhl_get_car_vin_value($vin_import['location_map'], $body) : "");
                $latitude = (isset($vin_import['latitude']) && !empty($vin_import['latitude']) ? cdhl_get_car_vin_value($vin_import['latitude'], $body) : "");
                $longitude = (isset($vin_import['longitude']) && !empty($vin_import['longitude']) ? cdhl_get_car_vin_value($vin_import['longitude'], $body) : "");
                if($location != ''){
                    $valuearr = array(
                        'address' => $location,
                        'lng' => $latitude,
                        'lat' => $longitude
                    );
                    $location_map =  $valuearr;
                }

                if(isset($location_map) && !empty($location_map)){
                    update_field('vehicle_location',$location_map,$insert_id);
                } 
                
                
                
                if(isset($vin_import['car_images']) && !empty($vin_import['car_images'])){
                
                    foreach($vin_import['car_images'] as $galleryimages){
                        
                        $gallery_image_url = cdhl_get_car_vin_value($galleryimages, $body);                                                 
                        if(filter_var($gallery_image_url, FILTER_VALIDATE_URL)){
                            $car_images[] = cdhl_image_upload($gallery_image_url);
                        }                        
                    }                    
                }                
                if(isset($car_images) && !empty($car_images)){                    
                    update_field( 'car_images', $car_images , $insert_id );
                }                                
                esc_html_e("Congratulations, you successfully imported this car details: ", "cardealer-helper");
                echo "<a href='" . get_permalink($insert_id) . "'>" . (!empty($post_title) ? $post_title : esc_html__("Untitled", "cardealer-helper")) . "</a>";
            } else {
                esc_html_e("Error importing your car", "cardealer-helper");
            }
        } else {            
            
            /**
             * Get VIN details and mapping area
             */ 
			 unset($_GET['error']);
            if(!empty($vin) && !isset($_GET['error'])){
                    $vin_import_var          = get_option("vin_import_mapping");
					
                    if(!empty($vin_import_var)){
                        $vin_import_mapping = (!empty($vin_import_var) ? $vin_import_var : "");
                        $vin_import_mapping = $vin_import_mapping['vin_import'];
                    } else {
                        $vin_import_mapping = array();
                    }					
					?>
                    <div class="cdhl-import-area-left"> 
                        
                        <div class="cdhl-area-title">                            
                            <?php
                            echo "<p>" . esc_html__("To import your cars simply drag and drop the right column items returned from the API into the Cars atributtes and meta box on the right hand side tabs. We can save current mapping for further use and import data with cars import button.", "cardealer-helper") . "</p><br>";
                            ?>
                            <h3 class="res-msg"></h3>
                            <div class="cdhl-button-group">
                                
                                <button class="cdhl_save_current_mapping current_vin_mapping button cdhl_button-primary"><?php esc_html_e("Save current mapping", "cardealer-helper"); ?></button>
                                <button class="cdhl_submit_vin button button-primary" style="vertical-align: super;"><?php esc_html_e("Import Cars", "cardealer-helper"); ?></button>
                                <span class="cdhl-loader-img"></span>
                            </div>
                            <div class="clr"></div>
                        </div>                    
                        <form method="post" action="" name="cars_vin_import_form" id="cars_vin_import_form" data-nonce="<?php echo wp_create_nonce("cdhl_cars_vin_import"); ?>">
                            
							<div id="tabs">
                                <ul>                                    
                                    <li><a href="#tabs-1"><?php esc_html_e( "Car Title", "cardealer-helper" );?></a></li>
                                    <li><a href="#tabs-2"><?php esc_html_e( "Attributes", "cardealer-helper" );?></a></li>
                                    <li><a href="#tabs-3"><?php esc_html_e( "Car Images", "cardealer-helper" );?></a></li>
                                    <li><a href="#tabs-4"><?php esc_html_e( "Regular price", "cardealer-helper" );?></a></li>
                                    <li><a href="#tabs-5"><?php esc_html_e( "Tax Label", "cardealer-helper" );?></a></li>
                                    <li><a href="#tabs-6"><?php esc_html_e( "Fuel Efficiency", "cardealer-helper" );?></a></li>
                                    <li><a href="#tabs-7"><?php esc_html_e( "PDF Brochure", "cardealer-helper" );?></a></li>
                                    <li><a href="#tabs-8"><?php esc_html_e( "Video", "cardealer-helper" );?></a></li>                            
                                    <li><a href="#tabs-9"><?php esc_html_e( "Car Status", "cardealer-helper" );?></a></li>
        							<li><a href="#tabs-10"><?php esc_html_e( "Vehicle Overview", "cardealer-helper" );?></a></li>
                                    <li><a href="#tabs-11"><?php esc_html_e( "Features & Options", "cardealer-helper" );?></a></li>
                                    <li><a href="#tabs-12"><?php esc_html_e( "Technical Specifications", "cardealer-helper" );?></a></li>
                                    <li><a href="#tabs-13"><?php esc_html_e( "General Information", "cardealer-helper" );?></a></li>
                                    <li><a href="#tabs-14"><?php esc_html_e( "Vehicle Location", "cardealer-helper" );?></a></li>                            
                                    <li><a href="#tabs-15"><?php esc_html_e( "Excerpt(Short content)", "cardealer-helper" );?></a></li>                            
                                </ul>
                                <div class="cdhl-form-group" id="tabs-1"> 
                                    <div class="cdhl_attributes">
            			                <label><?php esc_html_e("Car Titles", "cardealer-helper"); ?></label>
    									<div class="cars_attributes">
            
                			                <ul class="cdhl_cars_attributes cdhl_form_data" data-limit="0" data-name="cars_title">
                				                <?php cdhl_cars_vin_import_item('cars_title', $vin_import_mapping, $body,0);?>
                			                </ul>
    									</div>                            
            		                </div>
                                 </div>   
                                 <div class="cdhl-form-group" id="tabs-2">   
                                    <?php                                    
                                    $cars_attributes = cardealer_get_all_taxonomy_with_terms();                                    
                                    
                                    foreach($cars_attributes as $key => $value){                                        
                                        $attr_safe_name = $value['slug'];
                                        if($key != 'features-options'){                                            
                                            ?>
                                            <div class="cdhl_attributes">
                                                <label><?php echo esc_html($value['label']);?></label>
            									<div class="cars_attributes_area">            
                                                    <ul class="cdhl_cars_attributes cdhl_form_data" data-limit="1" data-name="<?php echo esc_attr($attr_safe_name); ?>">
                                                        <?php cdhl_cars_vin_import_item($key, $vin_import_mapping, $body); ?>
                                                    </ul>                                                    
            									</div>
                                            </div>                                                                        
                    						<?php
                						}
                                    
                                    }?>
                                </div>
                                
                                    <?php
                                    // extra spots                        
                                    $extra_spots = array(                                
                                        "car_images"                => array(esc_html__("Cars Images", "cardealer-helper"), 0),
                                        "regular_price"             => array(esc_html__("Regular price", "cardealer-helper"), 1),                
                                        "tax_label"                 => array(esc_html__("Tax Label", "cardealer-helper"), 1),
                                        "fuel_efficiency"           => array(esc_html__("Fuel Efficiency", "cardealer-helper"), 1),
                        				"pdf_file"                  => array(esc_html__("PDF Brochure", "cardealer-helper"), 1),
                                        "video_link"                => array(esc_html__("Video Link", "cardealer-helper"), 1),		
                                        "car_status"                => array(esc_html__("Car Status( sold/unsold )", "cardealer-helper"), 1),
                        				"vehicle_overview"          => array(esc_html__("Vehicle Overview", "cardealer-helper"), 0),
                        				"features_options"          => array(esc_html__("Features Options", "cardealer-helper"), 0),
                        				"technical_specifications"  => array(esc_html__("Technical Specifications", "cardealer-helper"), 0),
                        				"general_information"       => array(esc_html__("General Information", "cardealer-helper"), 0),				
                                		"vehicle_location"          => array(esc_html__("Vehicle Location", "cardealer-helper"), 1),
                        				"excerpt"                   => array(esc_html__("Excerpt(Short content)", "cardealer-helper"), 1),
                                    
                                    );
                                    $ef=3;                        
                                    foreach($extra_spots as $key => $option){                                    
                                                                    
                                        if($key == 'features_options'){
                							$taxonomy_name = get_taxonomy( 'car_features_options');        
                							$slug= $taxonomy_name->rewrite['slug'];  
                							$label = $taxonomy_name->labels->menu_name;
                							?>
                							<div id="tabs-<?php echo esc_attr($ef);?>">
                								<div class="cdhl_attributes">
                									<label><?php echo esc_html($label);?></label>
                									<div class="cars_attributes_area">            
                                                        <ul class="cdhl_cars_attributes cdhl_form_data" data-limit="<?php echo esc_attr($option[1]); ?>" data-name="<?php echo esc_attr($key); ?>">
                                                            <?php cdhl_cars_vin_import_item($key, $vin_import_mapping, $body, $option[1]); ?>
                                                        </ul>                                                    
                									</div>
                								</div>                            
                							</div>
                							<?php
                						}
                                        
                                        
                                        if($key == 'regular_price'){            							
                							?>
                							<div id="tabs-<?php echo esc_attr($ef);?>">
                								<div class="cdhl_attributes">
                									<label><?php esc_html_e('Regular Price','cardealer-helper');?></label>
                									<div class="cars_attributes_area">            
                                                        <ul class="cdhl_cars_attributes cdhl_form_data" data-limit="<?php echo esc_attr($option[1]); ?>" data-name="regular_price">
                                                            <?php cdhl_cars_vin_import_item('regular_price', $vin_import_mapping, $body, $option[1]); ?>
                                                        </ul>                                                    
                									</div>
                								</div>
                                                
                                                
                                                <div class="cdhl_attributes">
                									<label><?php esc_html_e('Sale Price','cardealer-helper');?></label>
                									<div class="cars_attributes_area">            
                                                        <ul class="cdhl_cars_attributes cdhl_form_data" data-limit="<?php echo esc_attr($option[1]); ?>" data-name="sale_price">
                                                            <?php cdhl_cars_vin_import_item('sale_price', $vin_import_mapping, $body, $option[1]); ?>
                                                        </ul>                                                    
                									</div>
                								</div>                            
    							
                							</div>
                							<?php
                						}
                                        
                                        if($key == 'fuel_efficiency'){							
                							?>
                							<div id="tabs-<?php echo esc_attr($ef);?>">
                								
                                                <div class="cdhl_attributes">
                									<label><?php esc_html_e('City MPG','cardealer-helper');?></label>
                									<div class="cars_attributes_area">            
                                                        <ul class="cdhl_cars_attributes cdhl_form_data" data-limit="<?php echo esc_attr($option[1]); ?>" data-name="city_mpg">
                                                            <?php cdhl_cars_vin_import_item('city_mpg', $vin_import_mapping, $body, $option[1]); ?>
                                                        </ul>                                                    
                									</div>
                								</div> 
                                                
                                                <div class="cdhl_attributes">
                									<label><?php esc_html_e('Highway MPG','cardealer-helper');?></label>
                									<div class="cars_attributes_area">            
                                                        <ul class="cdhl_cars_attributes cdhl_form_data" data-limit="<?php echo esc_attr($option[1]); ?>" data-name="highway_mpg">
                                                            <?php cdhl_cars_vin_import_item('city_mpg', $vin_import_mapping, $body, $option[1]); ?>
                                                        </ul>                                                    
                									</div>
                								</div>            																
                							</div>
                							<?php
                						}
                                        
                                        if($key != 'features_options' && $key != 'regular_price' && $key != 'fuel_efficiency'){
                                            ?>
                                            <div id="tabs-<?php echo esc_attr($ef);?>">
                								<div class="cdhl_attributes">
                									<label><?php echo esc_html($option[0]);?></label>
                									<div class="cars_attributes_area">            
                                                        <ul class="cdhl_cars_attributes cdhl_form_data" data-limit="<?php echo esc_attr($option[1]); ?>" data-name="<?php echo esc_attr($key); ?>">
                                                            <?php cdhl_cars_vin_import_item($key, $vin_import_mapping, $body, $option[1]); ?>
                                                        </ul>                                                    
                									</div>
                								</div>                            
                							</div>
                                            <?php    
                                        }
                                        $ef++;
                                    } ?>
                            
                            </div>
                        </form>
                    
                        <div class="cdhl-area-title cdhl-footer">                    
                            <div class="cdhl-button-group">
                                <button class="cdhl_save_current_mapping current_vin_mapping button cdhl_button-primary"><?php esc_html_e("Save current mapping", "cardealer-helper"); ?></button>
                                <button class="cdhl_submit_csv button button-primary" style="vertical-align: super;"><?php esc_html_e("Import Cars", "cardealer-helper"); ?></button>
                                <span class="cdhl-loader-img"></span>
                            </div>
                            <div class="clr"></div>
                        </div> 
                    </div>
                    <div class="cdhl-import-area-right">
                        
                        <h3><?php esc_html_e('API Result','cardealer-helper')?></h3>
                        <?php
                        
                        
                        echo '<ul id="cdhl_vin_items" class="cdhl_form_data ui-sortable">';
                        
                        if(!empty($body)){

                            $common_type_option = array("make", "model", "engine", "transmission", "categories", "MPG", "price","car_images");
                            $single_type_option = array("drivenWheels", "numOfDoors", "manufacturer", "vin", "squishVin", "matchingType", "manufacturerCode");
                            $nested_type_option = array("options", "colors", "years");
    
                            foreach($body as $key => $datainfo){
    
                                if(!empty($datainfo)){
                                    if($key == "car_images"){
                                        echo "<h2>Car images</h2>\n";
                                    }else{
                                        echo "<h2>" . ucwords($key) . "</h2>\n";
                                    }
                                    
    
                                    // normal option display
                                    if(in_array($key, $common_type_option)){
                                        if(!empty($datainfo)){
                                            foreach($datainfo as $datainfo_key => $datainfo_value){
    
                                                if($key=='car_images'){												
													echo "<li class='ui-state-default'> <img src='".$datainfo_value . "' width='114' height='70'/> <input type='hidden' name='' value='" . $key . "|" . $datainfo_key . "' /></li>\n";
												}else {
                                                    if(is_array($datainfo_value) && !empty($datainfo_value)){
                                                        foreach ($datainfo_value as $datainfo_deep_key => $datainfo_nested_value) {
                                                            echo "<li class='ui-state-default'>" . $datainfo_deep_key . ": " . $datainfo_nested_value . " <input type='hidden' name='' value='" . $key . "|" . $datainfo_key . "|" . $datainfo_deep_key. "' /></li>\n";
                                                        }
                                                    } else {
                                                        echo "<li class='ui-state-default'>" . $datainfo_key . ": " . $datainfo_value . " <input type='hidden' name='' value='" . $key . "|" . $datainfo_key . "' /></li>\n";
                                                    }
                                                }
                                            }
                                        }
                                    
                                    
                                    } elseif(in_array($key, $single_type_option)){
                                        echo "<li class='ui-state-default'>" . $key . ": " . $datainfo . " <input type='hidden' name='' value='" . $key . "' /></li>\n";
                                    
                                    
                                    } elseif(in_array($key, $nested_type_option)){
                                        if(!empty($datainfo)){
                                            foreach($datainfo[0] as $datainfo_key => $datainfo_value){
                                                
                                                if(!is_array($datainfo_value)){
                                                    echo "<li class='ui-state-default'>" . $datainfo_key . ": " . $datainfo_value . " <input type='hidden' name='' value='" . $key . "|0|" . $datainfo_key . "' /></li>\n";                                            
                                                
                                                
                                                } else {
                                                    $i = 0;
                                                    foreach($datainfo_value as $deep_key => $nested_value){
                                                        if($key == "options" || $key == "colors"){
                                                            
                                                            if(!empty($nested_value)){
                                                                echo "<h5>" . ucwords($key) . " " . esc_html__("option set", 'cardealer-helper') . " " . $i . "</h5>\n";
    
                                                                if(!empty($nested_value)){
                                                                    foreach($nested_value as $deepest_key => $deepest_value){
                                                                        echo "<li class='ui-state-default'>" . $deepest_key . ": " . $deepest_value . " <input type='hidden' name='' value='" . $key . "|" . $deep_key . "|" . $datainfo_key . "|" . $i . "|" . $deepest_key . "' /></li>\n";
                                                                    }
                                                                }
                                                                
                                                                echo "<hr>";
                                                            }
                                                            
                                                        } elseif($key == "years"){
    
                                                            
                                                            $carsubmodels = $nested_value['submodel'];
                                                            unset($nested_value['submodel']);
    
                                                            $nested_value['submodel'] = $carsubmodels;
    
                                                            echo "<h5>" . ucwords($key) . " " . esc_html__("submodel", "cardealer-helper") . " " . $i . "</h5>\n";
    
                                                            if(!empty($nested_value)){
                                                                foreach($nested_value as $deeper_key => $deeper_value){
                                                                    echo (!is_array($deeper_value) && !is_object($deeper_value) ? "<li class='ui-state-default'>" . $deeper_key . ": " . $deeper_value . " <input type='hidden' name='' value='" . $key . "|" . $datainfo_key . "|" . $i . "|" . $deeper_key . "' /></li>\n" : "");
                                                                }
                                                            }
    
                                                            if(!empty($nested_value['submodel'])){
                                                                foreach($nested_value['submodel'] as $submodel_key => $submodel_value){
                                                                    echo "<li class='ui-state-default'>" . $submodel_key . ": " . $submodel_value . " <input type='hidden' name='' value='" . $key . "|" . $datainfo_key . "|" . $i . "|submodel|" . $submodel_key . "' /></li>\n";
                                                                }
                                                            }
    
    	                                                    if(isset($nested_value['equipment']) && !empty($nested_value['equipment'])){
    	                                                       echo "<h2>" . esc_html__("Equipment Options", "cardealer-helper") . "</h2>";
    		                                                    echo "<div class='accordion accordion_parent'>";    		                                                    
                                                                echo "<h3>" . esc_html__("Equipment Options", "cardealer-helper") . "</h3>";
    
    		                                                    echo "<div class='accordion accordion_child'>";
    		                                                    foreach($nested_value['equipment'] as $carequipment_key => $carequipment_option_value){
    
    			                                                    if(!empty($carequipment_option_value['attributes'])){
    				                                                    echo "<h3>" . $carequipment_option_value['name']."</h3>";
    
    				                                                    echo "<div>";
    				                                                    foreach($carequipment_option_value['attributes'] as $carequipment_value_id => $carequipment_values){
    					                                                    echo "<li class='ui-state-default'>name: " . $carequipment_values['name'] . " <input type='hidden' name='' value='" . $key . "|" . $i . "|" . $datainfo_key . "|" . $i . "|equipment|" . $carequipment_key . "|attributes|" . $carequipment_value_id . "|name' /></li>\n";
    					                                                    echo "<li class='ui-state-default'>value: " . $carequipment_values['value'] . " <input type='hidden' name='' value='" . $key . "|" . $i . "|" . $datainfo_key . "|" . $i . "|equipment|" . $carequipment_key . "|attributes|" . $carequipment_value_id . "|value' /></li>\n";
    				                                                    }
    				                                                    echo "</div>";
    			                                                    }
    		                                                    }
    		                                                    echo "</div>";
    		                                                    echo "</div>";
    	                                                    }
    
                                                            echo "<hr>\n";
                                                        }
    
                                                        $i++;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                                                
                        echo '</ul>';
                        ?>
                    </div>                    
                <?php } else { 
                    
                    /**
                     * Pass VIN number here ( Enter vin number here ) 
                     */ 
                    
                    $cars_api_key     = (isset($car_dealer_options['edmunds_api_key']) && !empty($car_dealer_options['edmunds_api_key']) ? $car_dealer_options['edmunds_api_key'] : "");
                    $cars_api_secret  = (isset($car_dealer_options['edmunds_api_secret']) && !empty($car_dealer_options['edmunds_api_secret']) ? $car_dealer_options['edmunds_api_secret'] : "");

                    if(!empty($cars_api_key) && !empty($cars_api_secret)){ ?>

                        <div class="upload-plugin-pgs">
                            <form method="GET" class="wp-upload-form" action="" name="import_url">
                                <input type="hidden" name="post_type" value="cars">
                                <input type="hidden" name="page" value="cars-vin-import">

                                <label class="screen-reader-text" for="pluginzip"><?php esc_html_e("car file", "cardealer-helper"); ?></label>
                                <input type="text" name="vin" placeholder="<?php esc_html_e("VIN #", "cardealer-helper"); ?>" style="width: 60%;">
                                <button onclick="jQuery(this).closest('form').submit()" class="button"><?php esc_html_e("Get vehicle details", "cardealer-helper"); ?></button>                
                            </form>
                        </div>

                    <?php } else { ?>

                        <a href="<?php echo admin_url("admin.php?page=cardealer"); ?>"><?php esc_html_e("Please set both your edmunds API keys in the API Keys panel.", "cardealer-helper"); ?></a>

                    <?php } ?>


                <?php } ?>

            <?php } ?>
    </div>       
<?php }



function cdhl_get_vin_data($vin){
	global $car_dealer_options;

	$cars_api_key     = (isset($car_dealer_options['edmunds_api_key']) && !empty($car_dealer_options['edmunds_api_key']) ? $car_dealer_options['edmunds_api_key'] : "");
    if(!empty($cars_api_key)){
    	$response    = wp_remote_get("https://api.edmunds.com/api/vehicle/v2/vins/" . $vin . "?fmt=json&api_key=" . $cars_api_key);        
        
        $style_media = array();    	
        if(!is_wp_error($response)) {
    		$body_encode = json_decode( $response['body'] );    		
            if ( isset( $body_encode->years[0]->styles ) && ! empty( $body_encode->years[0]->styles ) ) {
    			foreach ( $body_encode->years[0]->styles as $year_key => $year_details ) {
    				$style_id        = $year_details->id;                    
                    $style_equipment = cdhl_get_vin_feature_options( $style_id );                    
                    $body_encode->years[0]->styles[ $year_key ]->{"equipment"} = "";
    				if(isset($style_equipment) && !empty($style_equipment)){
    				    $body_encode->years[0]->styles[ $year_key ]->{"equipment"} = $style_equipment->equipment; 
                    }                    
                    $style_media = cdhl_get_vin_media( $style_id );                     
                    $body_encode = array(
                        'body_encode' => $body_encode,
                        'style_media' => $style_media
                    );
                    
    			} 
    		}
    	} else {
    		$body_encode = array();
    	}
        return $body_encode;
    } else {
        header("Location: " . admin_url( 'edit.php?post_type=cars&page=cars-vin-import' ));
        die;    
    }
    
}

function cdhl_get_vin_feature_options($style_id){
	global $car_dealer_options;
    $body_encode = array();
	$cars_api_key     = (isset($car_dealer_options['edmunds_api_key']) && !empty($car_dealer_options['edmunds_api_key']) ? $car_dealer_options['edmunds_api_key'] : "");
    $response    = wp_remote_get("https://api.edmunds.com/api/vehicle/v2/styles/" . $style_id . "/equipment?availability=standard&fmt=json&api_key=" . $cars_api_key);
	if(!is_wp_error($response)) {
    	$body_encode = $response['body'];        
    }
	return json_decode($body_encode);
}

function cdhl_get_vin_media($style_id=200467056){
	global $car_dealer_options;

	$cars_api_key     = (isset($car_dealer_options['edmunds_api_key']) && !empty($car_dealer_options['edmunds_api_key']) ? $car_dealer_options['edmunds_api_key'] : "");
	
    $url = "https://api.edmunds.com/v1/api/vehiclephoto/service/findphotosbystyleid?styleId=" . $style_id . "&fmt=json&api_key=" . $cars_api_key;
	$response    = wp_remote_get($url);    
    $body_encode = $response['body'];
    $body = json_decode($body_encode);    
    $car_images = array();$carimages = array();$image_data=array();
    if(isset($body) && !empty($body)){
        foreach($body as $data){
            
            $bodyencode[] = array(
                'shotTypeAbbreviation' => $data->shotTypeAbbreviation,
                'photoSrcs' => $data->photoSrcs         
            );    
        }
        
        foreach($bodyencode as $bdata){
            foreach($bdata['photoSrcs'] as $photoSrcs){
                preg_match('/1600.jpg/',$photoSrcs,$matches);
                if(!empty($matches)){
                    $car_images[] = array( 
                        'shotTypeAbbreviation' => $bdata['shotTypeAbbreviation'],
                        'car_image' => 'http://media.ed.edmunds-media.com'.$photoSrcs
                    );         
                }            
            }
        }
        if(!empty($car_images)){
            foreach($car_images as $carimage){
                if($carimage['shotTypeAbbreviation'] == "FQ"){
                    $carimages[] = $carimage['car_image'];                 
                }else {
                    $carimages_2[] = $carimage['car_image'];
                }    
            }
        }
        $image_data['car_images'] = array_merge($carimages,$carimages_2);
    }    
    return $image_data;
}

function cdhl_object_to_array_conversion($obj) {
    if(is_object($obj)) $obj = (array) $obj;
    if(is_array($obj)) {
        $new = array();
        foreach($obj as $key => $val) {
            $new[$key] = cdhl_object_to_array_conversion($val);
        }
    }
    else $new = $obj;
    return $new;       
}

/**
* Get all errors
*/
function cdhl_all_vin_errors(){
    $vin = (isset($_GET['vin']) && !empty($_GET['vin']) ? $_GET['vin'] : "");

    if(!empty($vin) && isset($_GET['error'])){
        $body = cdhl_get_vin_data($vin);

        if(isset($body->errorType) && !empty($body->errorType)){
            echo "<div class='notice notice-error cars-vin-error'><span class='cars_text_error'>";

            echo "Error: " . $body->message . "<br>";

            echo "</span></div>";
        }
    }
}
add_action( 'admin_notices', 'cdhl_all_vin_errors' );

function cdhl_get_car_vin_value($location, $body){
    
    $return_value = "";    
    if(strpos($location, "|") !== false){
        $search_array = explode("|", $location);
    } else {
        $search_array = array($location);
    }
    
    if(!empty($search_array)){
        $return_value = $body;

        foreach($search_array as $value){
            
            
            if(isset($return_value[$value]) && !empty($return_value[$value])){
                $return_value = $return_value[$value];
            } else {
                $return_value = "";
                break;
            }
        }
    }     
    return $return_value;
}

function cdhl_image_upload($image_url){
    $image = $image_use = str_replace("\"", "", $image_url);
    $get   = wp_remote_get( $image );
    
    if(!is_wp_error($get) && $get['response']['code'] == 200) {
        $type = wp_remote_retrieve_header( $get, 'content-type' );

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

        $mirror = wp_upload_bits( basename( $image_use ), '', wp_remote_retrieve_body( $get ) );

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


 

function cdhl_vin_atributte_value($vin_mapp, $attr_safe_name, $body, $option){
    
	$check_exists_value  = true;
    $data_current_value = $body;
    $manage_data      = explode("|", $vin_mapp);
	
    foreach ($manage_data as $exp_value) {
        
		if(isset($data_current_value[$exp_value])){			
			$data_current_value = $data_current_value[$exp_value];
        } else {
            $check_exists_value = false;			
            break;
        }
    }	
	if($check_exists_value){		
        $attr_safe_name = trim($attr_safe_name);
        if($attr_safe_name == "car_images"){            
            echo "<li class='ui-state-default'> <img src='".$data_current_value . "' width='135' height='70'/> <input type='hidden' name='vin_import[" . $attr_safe_name . "]" . ($option == 0 ? "[]" : "") . "' value='" . $vin_mapp . "' /></li>";
        } else {
            echo "<li class='ui-state-default'>" . end($manage_data) . ": " . $data_current_value . " <input type='hidden' name='vin_import[" . $attr_safe_name . "]" . ($option == 0 ? "[]" : "") . "' value='" . $vin_mapp . "' /></li>";    
        }        
         
	}		
}

function cdhl_cars_vin_import_item($attr_safe_name, $vin_import_mapping, $body, $option = 1){
    
    if(!empty($vin_import_mapping) && isset($vin_import_mapping[$attr_safe_name]) && !empty($vin_import_mapping[$attr_safe_name])){
        $vin_mapp = $vin_import_mapping[$attr_safe_name];		
        if(is_array($vin_mapp)){
            foreach ($vin_mapp as $single_vin_mapp) {
                cdhl_vin_atributte_value($single_vin_mapp, $attr_safe_name, $body, $option);     
            }
        } elseif(strpos($vin_mapp, "|") === false){
            echo "<li class='ui-state-default'>" . $vin_mapp . ": " . $body[$vin_mapp] . " <input type='hidden' name='vin_import[" . $attr_safe_name . "]" . ($option == 0 ? "[]" : "") . "' value='" . $vin_mapp . "' /></li>";			
        } else {            
			cdhl_vin_atributte_value($vin_mapp, $attr_safe_name, $body, $option);                                 
        }
    }
}

function cdhl_save_vin_current_mapping(){    
    if(isset($_POST['form']) && !empty($_POST['form'])){

        $nonce = (isset($_POST['nonce']) && !empty($_POST['nonce']) ? $_POST['nonce'] : "");

        if(wp_verify_nonce($nonce, "cdhl_cars_vin_import")) {
	        parse_str( $_POST['form'], $form );

	        update_option( "vin_import_mapping", $form );

	        esc_html_e("Done", "cardealer-helper");
        } else {
	        esc_html_e("Nonce not valid, clear your cache and try again", "cardealer-helper");
        }
    }
    die;
}
add_action("wp_ajax_cdhl_save_vin_current_mapping", "cdhl_save_vin_current_mapping");
add_action("wp_ajax_nopriv_cdhl_save_vin_current_mapping", "cdhl_save_vin_current_mapping");
?>