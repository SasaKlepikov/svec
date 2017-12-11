<?php
function cdhl_car_import_csv_xml_session(){ 
    $ver = 0;
    $ver = phpversion();
    if($ver >= '5.4.0'){    
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }   
    } else {
        if(session_id() == '') {
            session_start();
        }
    }     
}
add_action('init', 'cdhl_car_import_csv_xml_session');


/**
 * Register admin sidebar nav menu
 */
function cdhl_admin_csv_import_menu() {
    add_submenu_page( 'edit.php?post_type=cars', esc_html__('Car Dealer Cars Import', 'cardealer-helper'), esc_html__('Cars Import', 'cardealer-helper'), "manage_options", 'cars-import', 'cdhl_cars_import' );
}
add_action('admin_menu', 'cdhl_admin_csv_import_menu');


/**
 * Catch and display errors
 */
function cdhl_admin_cars_imports_errors(){
    $error = (isset($_GET['error']) && !empty($_GET['error']) ? sanitize_text_field($_GET['error']) : "");

    if(!empty($error) || (isset($_SESSION['cars_csv']['error']) && !empty($_SESSION['cars_csv']['error']))){
        echo "<div class='error'><span class='error_text'>";

        if(isset($_SESSION['cars_csv']['error']) && !empty($_SESSION['cars_csv']['error'])){
            $return = "";
            foreach($_SESSION['cars_csv']['error'] as $key => $message){
                $return .= $message[1] . "<br>";
            }
			echo rtrim($return, "<br>");
            unset($_SESSION['cars_csv']['error']);
        } elseif($error == "file"){
            esc_html_e("The file uploaded is not a valid CSV file, please try again.", "cardealer-helper");
        } elseif($error == "url"){
            esc_html_e("The URL submitted is not a valid URL, please try again.", "cardealer-helper");
        } elseif($error == "int_file"){
            esc_html_e("The file must not contain numbers as the column title.", "cardealer-helper");
        }
		echo "</span></div>";
    }
}
add_action( 'admin_notices', 'cdhl_admin_cars_imports_errors' );

function cdhl_cars_import(){
	include_once( trailingslashit(CDHL_PATH) . 'includes/import_back_process/helper-classes/class-cdhl-background-processor.php' );
	$back_processor = new CDHL_Background_Processor;
	// import functions
    include_once trailingslashit(CDHL_PATH) . 'includes/import_csv_xml/cars_import_functions.php';
	?>
    <div class="wrap cdhl_dealer-cars-import">
        <h2 style="display: inline-block;"><?php esc_html_e("Cars Import", "cardealer-helper"); ?></h2>
        <?php
			if(!isset($_GET['import_file_content'])){
                /**
                 * CSV File Import form
                 */ 
				 if( $back_processor-> cdhl_is_running() || get_option( 'cdhl_import_log' ) == 'generated' ) {
					 update_option( 'cdhl_import_log' , 'read' );
					?>
					<p class="cdhl_import-notice"><?php esc_html_e("Data importing is going on. Please wait untill import process is completed!", "cardealer-helper"); ?></p>
					<div class="import_log_area" data-log_nonce="<?php echo wp_create_nonce("imp_log_nonce");?>">
						<div class="log_data">
						</div>
						<span class="log_loader"></span>
					</div>
				<?php
				 } else {
                ?>            
                <div class="cdhl_upload-cars-file">
                    <p class="cdhl_install-help"><?php esc_html_e("Upload .csv file here.", "cardealer-helper"); ?></p>
                    <form method="post" enctype="multipart/form-data" class="cdhl-cars-upload-form" action="<?php echo remove_query_arg( "error" ); ?>" name="cars_import_upload">
                        <input type="hidden" name="post_type" value="cars">
                        <input type="hidden" name="page" value="cars-import">
                        <input type="hidden" name="file" value="is_uploaded">
                        <input type="file" id="cars_import_upload" name="cars_import_upload">
                        <input type="submit" name="cars_import_submit" id="cars-install-plugin-submit" class="cdhl_button button button-primary" value="<?php esc_html_e("Import Now", "cardealer-helper"); ?>" />
                    </form>
					<div class="cdhl_sample_csv">
						<p> 
							<?php 
								echo sprintf( 
									wp_kses( 
										__('Click <a href="%1$s" download>here</a> to get sample CSV file.', 'cardealer-helper'),
										array(
											'a' => array(
												'href' => array(),
												'download' => array(),
											)
										)
									),
									str_replace( '\\', '/', CDHL_URL . 'includes/import_csv_xml/sample/sample-cardealer-inventory.csv')
								) 
							?> 
						</p>
					</div>
                </div>                
                <?php
				 }
            }
            if(isset($_GET['import_file_content'])){                 
                $cars_attributes = cardealer_get_all_taxonomy_with_terms();
                // Post metas
                $custom_meta_fields = array(                
                    "car_images"                => array(esc_html__("Car Images", "cardealer-helper"), 0),                    
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
                $prev_assoc_data  = get_option("cdhl_csv_mapping_associations");
                $association_data = ($prev_assoc_data ? $prev_assoc_data : array());
                
                $new_vin =  (isset($association_data['new_vin']) && !empty($association_data['new_vin']) ? $association_data['new_vin'] : "");                
                $new_vin_imp_post_status = (isset($association_data['new_vin_imp_post_status']) && !empty($association_data['new_vin_imp_post_status']) ? $association_data['new_vin_imp_post_status'] : "");
                
                $vin_not_in_csv = (isset($association_data['vin_not_in_csv']) && !empty($association_data['vin_not_in_csv']) ? $association_data['vin_not_in_csv'] : ""); 
                $vin_not_in_csv_in_db = (isset($association_data['vin_not_in_csv_in_db']) && !empty($association_data['vin_not_in_csv_in_db']) ? $association_data['vin_not_in_csv_in_db'] : "");
                
                $duplicate_check_vin = (isset($association_data['duplicate_check_vin']) && !empty($association_data['duplicate_check_vin']) ? $association_data['duplicate_check_vin'] : ""); 
                $overwrite_existing = (isset($association_data['overwrite_existing']) && !empty($association_data['overwrite_existing']) ? $association_data['overwrite_existing'] : "");
                $overwrite_existing_car_price = (isset($association_data['overwrite_existing_car_price']) && !empty($association_data['overwrite_existing_car_price']) ? $association_data['overwrite_existing_car_price'] : "");
                $overwrite_existing_car_images = (isset($association_data['overwrite_existing_car_images']) && !empty($association_data['overwrite_existing_car_images']) ? $association_data['overwrite_existing_car_images'] : "");
                
                if(empty($new_vin) && empty($vin_not_in_csv) && empty($duplicate_check_vin)){
                    $new_vin = 'on';                    
                }
                ?>
                
                <form method="post" id="cdhl_csv_import" data-one-per="<?php esc_html_e("Only one per list!", "cardealer-helper"); ?>" data-nonce="<?php echo wp_create_nonce("cardealer_csv_import"); ?>">
                    <h3><?php esc_html_e('Cars import rules','cardealer-helper')?></h3>
                    <div class="cdhl-form-group">
                        <label><input type="checkbox" id="cdhl_new_vin" name="new_vin" value="on" <?php echo (isset($new_vin) && !empty($new_vin) ? "checked='checked'" : ""); ?> />
                        <?php esc_html_e("Vehicle exist in CSV not in Database", "cardealer-helper"); ?>:</label>
                        <div class="cdhl_new_vin cdhl-form-group" style="display: <?php echo ((isset($new_vin) && $new_vin == "on") ? "block" : "none"); ?>;">
                            <select name="new_vin_imp_post_status">	                              
                                <option <?php echo ($new_vin_imp_post_status == 'publish')?"selected='selected'" : ""?> value="publish"><?php esc_html_e("Publish", "cardealer-helper"); ?></option>
                                <option <?php echo ($new_vin_imp_post_status == 'draft')?"selected='selected'" : ""?> value="draft"><?php esc_html_e("Draft", "cardealer-helper"); ?></option>
                                <option <?php echo ($new_vin_imp_post_status == 'pending')?"selected='selected'" : ""?> value="pending"><?php esc_html_e("Pending", "cardealer-helper"); ?></option>
                                <option <?php echo ($new_vin_imp_post_status == 'private')?"selected='selected'" : ""?> value="private"><?php esc_html_e("Private", "cardealer-helper"); ?></option>                                        
            	            </select>                    
                        </div>
                    </div>
    	            <div class="cdhl-form-group">
                       <label><input type="checkbox" id="cdhl_vin_not_in_csv" name="vin_not_in_csv" value="on" <?php echo (isset($vin_not_in_csv)&&!empty($vin_not_in_csv) ? "checked='checked'" : ""); ?> />
                       <?php esc_html_e("Vehicle exist in Database and not in CSV", "cardealer-helper"); ?>:</label>
    	               <div class="cdhl_vin_not_in_csv cdhl-form-group" style="display: <?php echo ((isset($vin_not_in_csv) && $vin_not_in_csv == "on") ? "block" : "none"); ?>;">
                            <select name="vin_not_in_csv_in_db">
                                <option <?php echo ($vin_not_in_csv_in_db == '')?"selected='selected'" : ""?> value=""><?php esc_html_e("--Select--", "cardealer-helper"); ?></option>	                              
                                <option <?php echo ($vin_not_in_csv_in_db == 'sold')?"selected='selected'" : ""?> value="sold"><?php esc_html_e("Sold", "cardealer-helper"); ?></option>
                                <option <?php echo ($vin_not_in_csv_in_db == 'delete')?"selected='selected'" : ""?> value="delete"><?php esc_html_e("Delete", "cardealer-helper"); ?></option>
                                <option <?php echo ($vin_not_in_csv_in_db == 'unpublished')?"selected='selected'" : ""?> value="unpublished"><?php esc_html_e("Unpublished", "cardealer-helper"); ?></option>                                                                            
            	            </select>                    
                        </div>
                    </div>
                    <div class="cdhl-form-group cdhl-duplicate-check-vin">
                        <label><input type="checkbox" id="cdhl_duplicate_check_vin" name="duplicate_check_vin" value="vin-number" <?php echo (!empty($duplicate_check_vin) ? "checked='checked'" : ""); ?> />
                        <?php esc_html_e("Vehicle exist in both CSV and Database", "cardealer-helper"); ?>: <strong><?php esc_html_e("VIN Number", "cardealer-helper"); ?></strong></label> 
                            <div class="cdhl_overwrite_existing_car_images cdhl-form-group" style="display: <?php echo ((isset($duplicate_check_vin) && $duplicate_check_vin == "vin-number") ? "block" : "none"); ?>;">
                                <label><input type="checkbox" name="overwrite_existing" value="on" <?php echo (!empty($overwrite_existing) ? "checked='checked'" : ""); ?>/> 
                                <?php esc_html_e("Overwrite all duplicate cars with new data(without images and price)", "cardealer-helper"); ?></label>                    
            	            </div>
                            
                            <div class="cdhl_overwrite_existing_car_images" style="display: <?php echo ((isset($duplicate_check_vin) && $duplicate_check_vin == "vin-number") ? "block" : "none"); ?>;">
            					<label><input type="checkbox" name="overwrite_existing_car_price" value="on" <?php echo ((isset($overwrite_existing_car_price) && $overwrite_existing_car_price == "on") ? "checked='checked'" : ""); ?>/> 
                                <?php esc_html_e("Overwrite price on existing cars", "cardealer-helper"); ?></label>                    
            	            </div>
            
            	            <div class="cdhl_overwrite_existing_car_images" style="display: <?php echo ((isset($duplicate_check_vin) && $duplicate_check_vin == "vin-number") ? "block" : "none"); ?>;">
            					<label><input type="checkbox" name="overwrite_existing_car_images" value="on" <?php echo ((isset($overwrite_existing_car_images) && $overwrite_existing_car_images == "on") ? "checked='checked'" : ""); ?>/> <?php esc_html_e("Overwrite images on existing cars", "cardealer-helper"); ?></label>                    
            	            </div>
                            <div class="cdhl-form-group res-msg"></div>                    
                    </div>                    
                    <div class="cdhl-import-area-left">                    
                        <div class="cdhl-area-title">
                            <h3><?php esc_html_e('Cars import area','cardealer-helper')?></h3>
                            <div class="cdhl-button-group">
                                <button class="cdhl_save_current_mapping button cdhl_button-primary"><?php esc_html_e("Save current mapping", "cardealer-helper"); ?></button>
                                <button class="cdhl_submit_csv button button-primary" style="vertical-align: super;"><?php esc_html_e("Import Cars", "cardealer-helper"); ?></button>
                                <span class="cdhl-loader-img"></span>
                            </div>
                            <div class="clr"></div>
                        </div>                    
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
                                <label><?php esc_html_e("Car Titles", "cardealer-helper"); ?></label><br />                 
                                <select name="car_titles[]" id="cdhl-car-title" class="cdhl-car-title cdhl_multiselect" multiple="multiple" data-placeholder="<?php esc_attr_e( "Year make model", "cardealer-helper" );?>" tabindex="8">
                		            <?php
                		            if( !isset( $_SESSION ) ) session_start();
            			            if(isset($_SESSION['cars_csv']['titles']) && !empty($_SESSION['cars_csv']['titles'])){
            				            $titles = $_SESSION['cars_csv']['titles'];
                                        $cars_titles = $_SESSION['cars_csv']['titles'];
            			            }
                		            
                		            if(!empty($titles)){
                			            if(!isset($association_data['car_titles']) || empty($association_data['car_titles'])){
                				            $association_data['car_titles'] = array();
                			            }
                			            
                			            if(!empty($association_data['car_titles'])){
                				            foreach($association_data['car_titles'] as $title_value){
												if(!empty($title_value)){
                                                    if(in_array($title_value, $titles)) {
                    						            echo "<option value='" . $title_value . "' selected='selected'>" . $title_value . "</option>";
														$title_index = array_search( $title_value, $titles );
                    						            if ( $title_index !== false ) {
                    							            unset( $titles[ $title_index ] );
                    						            }
                    					            }
                                                }
                				            }
                			            } 
                
                			            foreach($titles as $key => $value){
                				            $option_value = $value;
											echo "<option value='" . $option_value .  "'" . (in_array($option_value, $association_data['car_titles']) ? " selected='selected'" : "") . ">" . $value . "</option>\n";
                			            }
                		            }
                		            ?>
                	            </select>
                                <div class="dec-content">                                                
                                    <div class="cdhl-form-group">
                                        <?php esc_html_e("Select multiple value like Year, Make, Model", "cardealer-helper"); ?>
                                    </div>                                                                                                                                          
                                </div>                                
                            </div>                            
                            <div id="tabs-2">
                            <?php                                                           
                                foreach($cars_attributes as $key => $option) {                    
                                   $needle         = $option['slug'];
            	                   $is_association = ( isset( $association_data['csv'] ) && is_array( $association_data['csv'] ) && array_search( $needle, $association_data['csv'] ) ? true : false );
								   if($key != 'features-options'){
        								?>
                                        <div class="cdhl_attributes">
                			                <label><?php echo esc_html($option['label']);?></label>
        									<div class="cars_attributes">
												<ul class="cdhl_cars_attributes cdhl_connected_sortable" data-limit="1"
                    			                    data-name="<?php echo esc_attr($key); ?>">
                    				                <?php if ( $is_association ) {
                    					                $values = array_keys( $association_data['csv'], $needle );                                                
                                                        if ( is_array( $cars_titles ) && ( array_search( $values[0], $cars_titles ) !== false || isset($cars_titles[$values[0]]) ) ) {
                    						                $label =  $values[0];            
                    						                echo '<li class="ui-state-default ui-sortable-handle"><input type="hidden" name="csv[' . $values[0] . ']" value="' . $needle . '"> ' . $label . '</li>';
                    					                }
                    				                } ?>
                    			                </ul>
        									</div>                     
                		                </div>                            
        								<?php 
        							}                                
                                }?>
                            </div>
                        <?php
                        $fl_2 = 3;
                        foreach($custom_meta_fields as $key => $option){
							$needle         = $key;
                            $is_association = (isset($association_data['csv']) && is_array($association_data['csv']) && array_search($needle, $association_data['csv']) ? true : false); 
                            if($key == 'features_options'){
    							$taxonomy_name = get_taxonomy( 'car_features_options');        
    							$slug= $taxonomy_name->rewrite['slug'];  
    							$label = $taxonomy_name->labels->menu_name;
    							?>
    							<div id="tabs-<?php echo esc_attr($fl_2);?>">
    								<div class="cdhl_attributes">
    									<label><?php echo esc_html($label);?></label>
    									<div class="cars_attributes_area">
        									<ul class="cdhl_cars_attributes cdhl_connected_sortable" data-limit="1"
        										data-name="<?php echo esc_attr($key); ?>">
        										<?php if ( $is_association ) {
        											$values = array_keys( $association_data['csv'], $needle );        		
        											if ( is_array( $titles ) && ( array_search( $values[0], $titles ) !== false || isset($titles[$values[0]]) ) )  {
        												$label =  $values[0];
        												echo '<li class="ui-state-default ui-sortable-handle"><input type="hidden" name="csv[' . $values[0] . ']" value="' . $needle . '"> ' . $label . '</li>';
        											}
        										} ?>
        									</ul>
    									</div>                                        
                                        <div class="dec-content">                                        
                                            <div class="cdhl-form-group">
                                                <?php esc_html_e("We can map multiple values", "cardealer-helper"); ?>
                                            </div>                                                               
                                        </div>
    								</div>                                                                
    							</div>
    							<?php
    						}
    						
    						if($key == 'regular_price'){    							
    							?>
    							<div id="tabs-<?php echo esc_attr($fl_2);?>">
    								<div class="cdhl_attributes">
    									<label><?php esc_html_e('regular price','cardealer-helper');?></label>
    									<div class="cars_attributes_area">
        									<ul class="cdhl_cars_attributes cdhl_connected_sortable" data-limit="1"
        										data-name="regular_price">
        										<?php 
                                                $needle = 'regular_price';
                                                $is_association = ( isset( $association_data['csv'] ) && is_array( $association_data['csv'] ) && array_search( $needle, $association_data['csv'] ) ? true : false );
                                                if ( $is_association ) {											
        											$values = array_keys( $association_data['csv'], $needle );
        											if( is_array( $titles ) && ( array_search( $values[0], $titles ) !== false || isset($titles[$values[0]]) ) ) {
        												$label =  $values[0];        		
        												echo '<li class="ui-state-default ui-sortable-handle"><input type="hidden" name="csv[' . $values[0] . ']" value="' . $needle . '"> ' . $label . '</li>';
        											}
        										} ?>
        									</ul>
    									</div>
    								</div>    								
    								<div class="cdhl_attributes">
    									<label><?php esc_html_e('sale price','cardealer-helper');?></label>
    									<div class="cars_attributes_area">
        									<ul class="cdhl_cars_attributes cdhl_connected_sortable" data-limit="1"
        										data-name="sale_price">
        										<?php 
                                                $needle = 'sale_price';
                                                $is_association = ( isset( $association_data['csv'] ) && is_array( $association_data['csv'] ) && array_search( $needle, $association_data['csv'] ) ? true : false );
                                                if ( $is_association ) {
        											$values = array_keys( $association_data['csv'], $needle );
        											if ( is_array( $titles ) && ( array_search( $values[0], $titles ) !== false || isset($titles[$values[0]]) ) ) {
        												$label = $values[0];
        												echo '<li class="ui-state-default ui-sortable-handle"><input type="hidden" name="csv[' . $values[0] . ']" value="' . $needle . '"> ' . $label . '</li>';
        											}
        										} ?>
        									</ul>
    									</div>
    								</div>								
    							</div>
    							<?php
    						}
    
    						if($key == 'fuel_efficiency'){							
    							?>
    							<div id="tabs-<?php echo esc_attr($fl_2);?>">
    								<div class="cdhl_attributes">
    									<label><?php esc_html_e('City MPG','cardealer-helper');?></label>
    									<div class="cars_attributes_area">
        									<ul class="cdhl_cars_attributes cdhl_connected_sortable" data-limit="1"
        										data-name="city_mpg">
        										<?php 
                                                $is_association = (isset($association_data['csv']) && is_array($association_data['csv']) && array_search('city_mpg', $association_data['csv']) ? true : false);
                                                if ( $is_association ) {
        											$needle = 'city_mpg';
        											$values = array_keys( $association_data['csv'], $needle );                                              
        											if ( is_array( $titles ) && ( array_search( $values[0], $titles ) !== false || isset($titles[$values[0]]) ) ) {
        												$label = $values[0];        		
        												echo '<li class="ui-state-default ui-sortable-handle"><input type="hidden" name="csv[' . $values[0] . ']" value="city_mpg"> ' . $label . '</li>';
        											}
        										} ?>
        									</ul>
    									</div>
    								</div>    								
    								<div class="cdhl_attributes">
    									<label><?php esc_html_e('Highway MPG','cardealer-helper');?></label>
    									<div class="cars_attributes_area">
        									<ul class="cdhl_cars_attributes cdhl_connected_sortable" data-limit="1"
        										data-name="highway_mpg">
        										<?php 
                                                $is_association = (isset($association_data['csv']) && is_array($association_data['csv']) && array_search('highway_mpg', $association_data['csv']) ? true : false);
                                                if ( $is_association ) {
        											$needle = 'highway_mpg';
        											$values = array_keys( $association_data['csv'], $needle );        		
        											if ( is_array( $titles ) && ( array_search( $values[0], $titles ) !== false || isset($titles[$values[0]]) ) ) {
        												$label =    $values[0];
        												echo '<li class="ui-state-default ui-sortable-handle"><input type="hidden" name="csv[' . $values[0] . ']" value="highway_mpg"> ' . $label . '</li>';
        											}
        										} ?>
        									</ul>
    									</div>
    								</div>								
    							</div>
    							<?php
    						}    				
    						if($key != 'features_options' && $key != 'regular_price' && $key != 'fuel_efficiency'){
                            ?>
                            <div id="tabs-<?php echo esc_attr($fl_2);?>">
                                <div class="cdhl_attributes">
									<label><?php echo esc_html($option[0]);?></label>
									<div class="cars_attributes_area">            
										<ul class="cdhl_cars_attributes cdhl_connected_sortable" data-limit="<?php echo esc_attr($option[1]); ?>" data-name="<?php echo str_replace(" ", "_", strtolower($key)); ?>">
											<?php if($is_association){
												$safe_val = $needle;
												$values   = array_keys($association_data['csv'], $safe_val);    				
												foreach($values as $val_key => $val_val){
													if( is_array( $titles ) && ( array_search($val_val, $titles) !== false  || isset($titles[$val_val]) ) ){
														$label = $val_val;
														echo '<li class="ui-state-default ui-sortable-handle"><input type="hidden" name="csv[' . $val_val . ']" value="' . $safe_val . '"> ' . $label . '</li>';
													}
												}
											} ?>
										</ul>
									</div>
									<div class="dec-content">
										<?php
										if($option[1] == 0 ? " <i class='fa fa-bars'></i>" : ""){
											?>
											<div class="cdhl-form-group">
												<?php esc_html_e("We can map multiple values", "cardealer-helper"); ?>
											</div>
											<?php                                                                                            
										}
										?>                                                                                        
									</div>                                        
                                </div>
                            </div>
    						<?php
    						}    						
    						$fl_2++;
                        }
                        ?>                        
                        </div>
                        <div class="cdhl-area-title cdhl-footer">                    
                            <div class="cdhl-button-group">
                                <button class="cdhl_save_current_mapping button cdhl_button-primary"><?php esc_html_e("Save current mapping", "cardealer-helper"); ?></button>
                                <button class="cdhl_submit_csv button button-primary" style="vertical-align: super;"><?php esc_html_e("Import Cars", "cardealer-helper"); ?></button>
                                <span class="cdhl-loader-img"></span>
                            </div>
                            <div class="clr"></div>
                        </div>
					</div>
                </form>
                <div class="cdhl-import-area-right">
                    <ul id="cdhl_csv_items" class="cdhl_connected_sortable">
                        <?php
						if( !isset( $_SESSION ) ) session_start();
                        if(isset($_SESSION['cars_csv']['titles']) && !empty($_SESSION['cars_csv']['titles'])){
                            $titles = $_SESSION['cars_csv']['titles'];
                        }                                            
                        if(!empty($titles)){
                            foreach($titles as $key => $value){
                                if(!isset($association_data['csv'][$key])){
                                    echo "<li class='ui-state-default'><input type='hidden' name='csv[" . $value .  "]' > " . $value . "</li>";
                                }
                            }
                        }?>
                    </ul>
                </div>
                <?php
            }
        ?>        
    </div>
    <?php        
}


/**
 * Call Wp hook when file uploaded
 */ 
function cdhl_check_file_type() { 
    if( !isset( $_SESSION ) ) session_start();
    include_once(CDHL_PATH . "includes/import_csv_xml/cars_import_functions.php");
    require_once trailingslashit(CDHL_PATH) . 'includes/import_back_process/cdhl_importer.php';
	if(isset($_POST['csv']) && !empty($_POST['csv'])){
		// Check if no selection made for import
		if( !isset($_POST['new_vin']) && !isset($_POST['vin_not_in_csv']) && ( !isset($_POST['overwrite_existing']) || !isset($_POST['overwrite_existing_car_price']) || !isset($_POST['overwrite_existing_car_images']) ) ){
			add_action( 'admin_notices', 'cdhl_import_empty_selection', 0 );
		} else {
			$_SESSION['import_post_data'] = $_POST;
			$redirect_uri = add_query_arg( array(
				'post_type' => 'cars',
				'page' => 'cars-import',
				'notice' => 'no-rules',
			), admin_url('edit.php') );
		wp_redirect( $redirect_uri );
		exit;
		}
	}
    
    if(isset($_FILES['cars_import_upload']['name']) && !empty($_FILES['cars_import_upload']['name'])){            
        $ext = '';
        $filetype = wp_check_filetype($_FILES['cars_import_upload']['name']);
        $ext = $filetype['ext'];                
        if($ext == 'csv'){
            $import_file_content = cdhl_get_file_data("from_file", $_FILES['cars_import_upload']);
            if($import_file_content['status'] == "success"){
                /**
                 * Get file content in proper formates
                 */ 
                $file_array = cdhl_add_file_content_to_array($import_file_content['import_data']);
                if($file_array[0] == "error"){
                    header("Location: " . admin_url( 'edit.php?post_type=cars&page=cars-import&error=import_file_content' ));
                } else {
                    header("Location: " . admin_url( 'edit.php?post_type=cars&page=cars-import&import_file_content=yes' ));
                }                            
            } else {
                if($file_array[0] == "error"){
                    header("Location: " . admin_url( 'edit.php?post_type=cars&page=cars-import&error=file' ));
                }
            }
        } else {            
            header("Location: " . admin_url( 'edit.php?post_type=cars&page=cars-import&error=file' ));            
        }                
    }  
}
add_action( 'init', 'cdhl_check_file_type' );

function cdhl_save_current_mapping(){    
    if(isset($_POST['form']) && !empty($_POST['form'])){
        $nonce = (isset($_POST['nonce']) && !empty($_POST['nonce']) ? $_POST['nonce'] : "");
        if(wp_verify_nonce($nonce, "cardealer_csv_import")) {     
	        parse_str( $_POST['form'], $form );
	        $option = "cdhl_csv_mapping_associations";
	        update_option( $option, $form );
	        esc_html_e("Done", "cardealer-helper");
        } else {
            esc_html_e("Nonce not valid, clear your cache and try again", "cardealer-helper");
        }
    }
    die;
}
add_action("wp_ajax_cdhl_save_current_mapping", "cdhl_save_current_mapping");
add_action("wp_ajax_nopriv_cdhl_save_current_mapping", "cdhl_save_current_mapping");


/*
function to show imported cars log
*/
add_action("wp_ajax_show_import_log", "cdhl_show_import_log");
add_action("wp_ajax_nopriv_show_import_log", "cdhl_show_import_log");
function cdhl_show_import_log() {
	
	include_once( trailingslashit(CDHL_PATH) . 'includes/import_back_process/helper-classes/class-cdhl-background-processor.php' );
	$back_processor = new CDHL_Background_Processor;	
	
	if(isset($_POST['action']) && $_POST['action'] == 'show_import_log'){
		if( isset($_POST['nonce']) ) {
			$log_nonce = $_POST['nonce'];
			if( ! wp_verify_nonce($log_nonce, "imp_log_nonce") ) {     
				$result = array( 'log_status'=> '1', 'log_error'=> esc_html('Something went wrong', 'cardealer-helper') );
			}
		}
		if( ! cdhl_is_dir_empty( CDHL_LOG . 'import_logs' ) ) {
			$files = array_values( preg_grep('~\.(txt)$~', scandir( CDHL_LOG . 'import_logs', SCANDIR_SORT_DESCENDING ) ) );
			$log_file = str_replace( '/', '\\', $files[0] );
			if( file_exists( $log_file ) || file_exists( CDHL_LOG . 'import_logs\\'.$log_file ) ) {	
				if( file_exists( CDHL_LOG . 'import_logs\\'.$log_file ) ) {
					$log_file = CDHL_LOG . 'import_logs\\'.$log_file;
				}
				$imported_cars = file_get_contents( $log_file );
				$imported_cars = str_replace("\n", "<br>", $imported_cars);
				$result = array( 'imported_cars'=> $imported_cars, 'log_status'=> '0' ); 
			} else {
				$result = array( 'log_status'=> '1', 'log_error'=> esc_html('No log available.', 'cardealer-helper') );
			}
		} else { 
			$result = array( 'log_status'=> '1', 'log_error'=> esc_html('No log available.', 'cardealer-helper') );
		}		
	} else { 
		$result = array( 'log_status'=> '1', 'log_error'=> esc_html('Something went wrong, Please try again later!', 'cardealer-helper') );
	}
	$status = $back_processor-> cdhl_is_running();
	if($status != 1) {
		$result['import_status'] = 1;
	} else{
		$result['import_status'] = 0;
	}
	echo json_encode( $result );
	die;
}

function cdhl_admin_notice_import() {
	include_once( trailingslashit(CDHL_PATH) . 'includes/import_back_process/helper-classes/class-cdhl-background-processor.php' );
	$back_processor = new CDHL_Background_Processor;
	$status = $back_processor-> cdhl_is_running();
	if( $status != 1 || ( isset( $_GET['page'] ) && $_GET['page'] == 'cars-import' ) ) {
		return;
	}
    ?>
    <div class="notice notice-success is-dismissible">
        <p>
		<?php 
			echo sprintf( 
				wp_kses( 
					__( 'File import process is in progress, click <a href="%1$s"> here</a> to view process log. ', 'cardealer-helper' ),
					array(
						'a' => array(
							'href'=> array()
						)
					)
				),
				esc_url( admin_url( 'edit.php?post_type=cars&page=cars-import&log_nonce='.wp_create_nonce("imp_log_nonce") ) )
			); 
		?>
		</p>
    </div>
    <?php
}
add_action( 'admin_notices', 'cdhl_admin_notice_import' );

// If noe selection is made in import rules on mapping page
function cdhl_import_empty_selection() {
	?>
    <div class="notice notice-success is-dismissible">
        <p><?php esc_html_e( 'Please make selection given in import rules!', 'cardealer-helper' );?></p>
    </div>
    <?php
}

// Function to check directory empty
function cdhl_is_dir_empty($dir) {
  if (!is_readable($dir)) return NULL; 
  return (count(scandir($dir)) == 2);
}
?>