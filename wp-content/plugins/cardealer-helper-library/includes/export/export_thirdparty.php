<?php
// Function to export car data to autotrader
function cdhl_export_cars_to_autotrader( $redirect_to, $doaction, $post_ids ) { 
	global $car_dealer_options;
	$send_ftp = (isset($_GET['ftp_now']))? $_GET['ftp_now'] : NULL; // Sent to third party or not
	if( $doaction != 'export_autotrader' ){
		return $redirect_to;
	}
	if ( empty($post_ids) ) {
		return $redirect_to;
	}	
	if ( empty($car_dealer_options['ftp_host']) || empty($car_dealer_options['username']) || empty($car_dealer_options['password']) || empty($car_dealer_options['dealer_id']) ) {
		return $redirect_to.'&car_notice=5';
	}
	
	// create source directory
	if (!file_exists(ABSPATH.'wp-content/uploads/cars/exported'))
		mkdir(ABSPATH.'wp-content/uploads/cars/exported', 0777, true);
	$source = ABSPATH.'wp-content/uploads/cars/exported/';
	
	$file_name = ['USED'.$car_dealer_options['dealer_id'].'_'.date('mdy').'.txt', 'NEW'.$car_dealer_options['dealer_id'].'_'.date('mdy').'.txt'];
	
	if( $send_ftp ) {
		// Check ftp module is enable on server
		if ( ! function_exists( 'ftp_connect' ) ) { 
			return $redirect_to.'&car_notice=7';
		}
		$connection = ftp_connect($car_dealer_options['ftp_host']);
		$ftp_user_name = $car_dealer_options['username'];
		$ftp_user_pass = $car_dealer_options['password'];
		$login = ftp_login($connection, $ftp_user_name, $ftp_user_pass);
		ftp_pasv($connection, true);
			if (!$connection || !$login) return $redirect_to.'&car_notice=4'; 
			
		// Code for get location of file to export 
		if( !isset($car_dealer_options['file_location']) || empty($car_dealer_options['file_location']) ) {
			$car_dealer_options['file_location'] = '';
		} else if( cdhl_ftp_is_dir( $connection, $car_dealer_options['file_location'] ) == false ) {
			return $redirect_to.'&car_notice=6';
		}
	}
	
	// get CarsDetail Array
	$carsData = cdhl_get_autrotrade_cars_detail( $post_ids, $redirect_to, $doaction ); 
	$cnt = 0;
	$carsId = [];
	$numericFields = ['car_year', 'car_mileage', 'regular_price','sale_price'];
	foreach( $carsData as $car_type=> $data ) {
		if( empty($data) ) continue;
		$fp = fopen($source.$file_name[$cnt], "wb");
		end($data); 
		$lastline = key($data);
		$file_written = 0;
		foreach ($data as $key=> $fields) { 
			end($fields); 
			$lastField = key($fields);
			
			// Discard record if required fields are blank
			$req_field = array('car_vin_number', 'dealer_id', 'car_mileage', 'car_exterior_color');
			$valid_record = 1;
			foreach( $req_field as $r_field ){
				if( !isset($fields[$r_field]) || empty($fields[$r_field]) )
					$valid_record = 0;
			}
			if( $valid_record !== 1) continue;
			if($car_type == 'old_car' && empty($fields['sale_price']) && empty($fields['regular_price']) ) continue;
						
			foreach( $fields as $k=> $field ){
				if( $k == 'carId' ){
					$carsId[0][] = $field; // get car Id
				continue; // Donot include carid in export file
				}
				// Check number fields
				if( in_array($k, $numericFields) ) {
					if( !is_numeric($field)) $field = '';
				}
				
				/* Code for final price */
				if($car_type == 'old_car'){
					if( $k == 'regular_price' ) {
						$field = ( !isset($fields['sale_price']) || $fields['sale_price'] < 1 )? $fields['regular_price'] : $fields['sale_price']; // Values
					}
					if($k == 'sale_price') continue;
				}
				
				$field = str_replace("\n", "", $field); // Remove new line
				$field = str_replace("\r", "", $field); // Remove new line
				$field = str_replace("\"", "'", $field); // Remove Quotes
				
				// will be used to set flag that file contains records, if no records, then file will not be generated.
				$file_written = 1;
				
				if(( $k == $lastField )) {
					fwrite($fp,'"'. $field .'"');
				} else {
					fwrite($fp,'"'. $field .'",');
				}
			}
			if( $key != $lastline )
				fwrite($fp,"\n");
			//fputcsv($fp, $fields);
		}
		fclose($fp);
		
		// Remove generated file if no records found / validated
		if( $file_written == 0 ) { 
			unlink($source.$file_name[$cnt]);
			$cnt++; // increment counter to get and generate next file.
			continue;
		}
		
		if($send_ftp)
			$upload[$cnt] = ftp_put($connection, $car_dealer_options['file_location'].'/'.$file_name[$cnt], $source.$file_name[$cnt], FTP_BINARY);
		//unlink($source.$file_name[$cnt]);
		// Create log entry
		$ftp_status = ( isset($upload[$cnt]) && $upload[$cnt] == 1)? esc_html__('True', 'cardealer-helper') : esc_html__('False', 'cardealer-helper');
		
		// Code to check file already exist
		$args = array(
		'post_type'   => 'pgs_export_log',
		'meta_query'  => array(
				array( 'value' => $file_name[$cnt])
			)
		);
		$my_query = new WP_Query( $args );
		if( $my_query->have_posts() ) {
		  while( $my_query->have_posts() ) {
			$my_query->the_post();
			$post_id = get_the_ID();
		  } // end while
		} // end if
		else // If file does not exist then insert
		{
			$post_id = wp_insert_post( array(
				'post_type' => 'pgs_export_log',
				'post_title' => esc_html__('Auto Trader', 'cardealer-helper'),
				'post_content' => ''
			) );
		}
		wp_reset_postdata();
		$cars_exported ='';
		if(isset($carsId[0])) {
			$cars_exported = ( !empty($carsId[0]) )? implode(",",$carsId[0]) : '';
		}

		if($post_id) { 
			update_field( 'file_name', $file_name[$cnt], $post_id );
			update_field( 'export_to', esc_html__('Auto Trader', 'cardealer-helper'), $post_id );
			update_field( 'ftp_done', $ftp_status, $post_id );
			($send_ftp)? update_field( 'ftp_done_on', date('y-m-d H:i:s') , $post_id ) : update_field( 'ftp_done_on', '-' , $post_id );
			update_field( 'created', date('y-m-d H:i:s') , $post_id );
			update_field( 'posts', $cars_exported , $post_id ); // insert car ids into log
		}
	$cnt++;
	}
	
	// If no records exported
	if( !isset( $file_written ) || $file_written == 0 ){
		return $redirect_to.'&car_notice=8';
	}
	
	if( $send_ftp ) { 
		// add entry into cars post type
		if( !empty($carsId[0]) ) {
			foreach($carsId[0] as $car){ 
				update_field( 'auto_trader', 'yes' , $car );
				update_field( 'auto_export_date', date('Y/m/d H:i:s') , $car );
			}
		}
		// Close ftp connection
		ftp_close($connection);
	}
			
	if( ( (isset($upload[0]) && !$upload[0]) || (isset($upload[1]) && !$upload[1])) && $send_ftp ) { 
		return $redirect_to.'&car_notice=3';
	} else {
		return 'admin.php?page=log-list&car_notice=2';
	}
die;	
}


/**
 * Step 2: handle the custom autotrader
 * Added to solved version compatibility issue for versions < 4.7
 * Added without using csv library
 * Based on the post http://wordpress.stackexchange.com/questions/29822/custom-bulk-action
 */
function cdhl_custom_export_cars_to_autotrader() {
	global $typenow, $car_dealer_options;
	$redirect_to = wp_get_referer();
	$post_type = $typenow;
	
	if($post_type == 'cars') {
		// get the action
		$wp_list_table = _get_list_table('WP_Posts_List_Table');  // depending on your resource type this could be WP_Users_List_Table, WP_Comments_List_Table, etc
		$action = $wp_list_table->current_action();		
		$allowed_actions = array("export_autotrader");
		if(!in_array($action, $allowed_actions)) return;
		
		$send_ftp = (isset($_GET['ftp_now']))? $_GET['ftp_now'] : NULL; // Sent to third party or not
		if ( empty($car_dealer_options['ftp_host']) || empty($car_dealer_options['username']) || empty($car_dealer_options['password']) || empty($car_dealer_options['dealer_id']) ) {
			wp_safe_redirect( $redirect_to.'&car_notice=5' ); die;
		}
		
		// create source directory
		if (!file_exists(ABSPATH.'wp-content/uploads/cars/exported'))
			mkdir(ABSPATH.'wp-content/uploads/cars/exported', 0777, true);
		$source = ABSPATH.'wp-content/uploads/cars/exported/';
		
		$file_name = ['USED'.$car_dealer_options['dealer_id'].'_'.date('mdy').'.txt', 'NEW'.$car_dealer_options['dealer_id'].'_'.date('mdy').'.txt'];
		
		if( $send_ftp ) {
			// Check ftp module is enable on server
			if ( ! function_exists( 'ftp_connect' ) ) { 
				wp_safe_redirect($redirect_to.'&car_notice=7'); die;
			}			
			$connection = ftp_connect($car_dealer_options['ftp_host']);
			$ftp_user_name = $car_dealer_options['username'];
			$ftp_user_pass = $car_dealer_options['password'];
			$login = ftp_login($connection, $ftp_user_name, $ftp_user_pass);
			ftp_pasv($connection, true);
				if (!$connection || !$login) { wp_safe_redirect($redirect_to.'&car_notice=4'); die; }
			
			// Code for get location of file to export 
			if( !isset($car_dealer_options['file_location']) || empty($car_dealer_options['file_location']) ) {
				$car_dealer_options['file_location'] = '/';
			} else if( cdhl_ftp_is_dir( $connection, $car_dealer_options['file_location'] ) == false ) {
				wp_safe_redirect( $redirect_to.'&car_notice=6' ); die;
			}
		}
	
		// security check
		check_admin_referer('bulk-posts');
		// make sure ids are submitted.  depending on the resource type, this may be 'media' or 'ids'
		if(isset($_REQUEST['post'])) {
			$post_ids = array_map('intval', $_REQUEST['post']);
		}
		if(empty($post_ids)) return;
       
		// get CarsDetail Array
		$redirect_to = wp_get_referer();
		$carsData = cdhl_get_autrotrade_cars_detail( $post_ids, $redirect_to, $allowed_actions[0] );
	
     	$cnt = 0;
		$carsId = [];
		$numericFields = ['car_year', 'car_mileage', 'regular_price','sale_price'];
		foreach( $carsData as $car_type=> $data ) {
			if( empty($data) ) continue;
			$fp = fopen($source.$file_name[$cnt], "wb");
			end($data); 
			$lastline = key($data);
			$file_written = 0;
			foreach ($data as $key=> $fields) { 
				end($fields); 
				$lastField = key($fields);
				
				// Discard record if required fields are blank
				$req_field = array('car_vin_number', 'dealer_id', 'car_mileage', 'car_exterior_color');
				$valid_record = 1;
				foreach( $req_field as $r_field ){
					if( !isset($fields[$r_field]) || empty($fields[$r_field]) )
						$valid_record = 0;
				}
				if( $valid_record !== 1) continue;
				if($car_type == 'old_car' && empty($fields['sale_price']) && empty($fields['regular_price']) ) continue;
							
				foreach( $fields as $k=> $field ){
					if( $k == 'carId' ){
						$carsId[0][] = $field; // get car Id
					continue; // Donot include carid in export file
					}
					// Check number fields
					if( in_array($k, $numericFields) ) {
						if( !is_numeric($field)) $field = '';
					}
					
					/* Code for final price */
					if($car_type == 'old_car'){
						if( $k == 'regular_price' ) {
							$field = ( !isset($fields['sale_price']) || $fields['sale_price'] < 1 )? $fields['regular_price'] : $fields['sale_price']; // Values
						}
						if($k == 'sale_price') continue;
					}
					
					$field = str_replace("\n", "", $field); // Remove new line
					$field = str_replace("\r", "", $field); // Remove new line
					$field = str_replace("\"", "'", $field); // Remove Quotes
					// will be used to set flag that file contains records, if no records, then file will not be generated.
					$file_written = 1;
				
					if(( $k == $lastField )) 
						fwrite($fp,'"'. $field .'"');
					else
						fwrite($fp,'"'. $field .'",');
				}
				if( $key != $lastline )
					fwrite($fp,"\n");
			}
			fclose($fp);
			
			// Remove generated file if no records found / validated
			if( $file_written == 0 ) { 
				unlink($source.$file_name[$cnt]);
				$cnt++; // increment counter to get and generate next file.
				continue;
			}
			
			if($send_ftp)
				$upload[$cnt] = ftp_put($connection, $car_dealer_options['file_location'].'/'.$file_name[$cnt], $source.$file_name[$cnt], FTP_BINARY);
			//unlink($source.$file_name[$cnt]);
			// Create log entry
			$ftp_status = ( isset($upload[$cnt]) && $upload[$cnt] == 1)? esc_html__('True', 'cardealer-helper') : esc_html__('False', 'cardealer-helper');
			
			// Code to check file already exist
			$args = array(
			'post_type'   => 'pgs_export_log',
			'meta_query'  => array(
					array( 'value' => $file_name[$cnt])
				)
			);
			$my_query = new WP_Query( $args );
			if( $my_query->have_posts() ) {
			  while( $my_query->have_posts() ) {
				$my_query->the_post();
				$post_id = get_the_ID();
			  } // end while
			} // end if
			else // If file does not exist then insert
			{
				$post_id = wp_insert_post( array(
					'post_type' => 'pgs_export_log',
					'post_title' => esc_html__('Auto Trader', 'cardealer-helper'),
					'post_content' => ''
				) );
			}
			wp_reset_postdata();
			$cars_exported ='';
			if(isset($carsId[0])) {
				$cars_exported = ( !empty($carsId[0]) )? implode(",",$carsId[0]) : '';
			}

			if($post_id) { 
				update_field( 'file_name', $file_name[$cnt], $post_id );
				update_field( 'export_to', esc_html__('Auto Trader', 'cardealer-helper'), $post_id );
				update_field( 'ftp_done', $ftp_status, $post_id );
				($send_ftp)? update_field( 'ftp_done_on', date('y-m-d H:i:s') , $post_id ) : update_field( 'ftp_done_on', '-' , $post_id );
				update_field( 'created', date('y-m-d H:i:s') , $post_id );
				update_field( 'posts', $cars_exported , $post_id ); // insert car ids into log
			}
		$cnt++;
		}
		
		// If no records exported
		if( !isset( $file_written ) || $file_written == 0 ){
			wp_safe_redirect( $redirect_to.'&car_notice=8' ); die;
		}
		
		if( $send_ftp ) { 
			// add entry into cars post type
			if( !empty($carsId[0]) ) {
				foreach($carsId[0] as $car){ 
					update_field( 'auto_trader', 'yes' , $car );
					update_field( 'auto_export_date', date('Y/m/d H:i:s') , $car );
				}
			}
			// Close ftp connection
			ftp_close($connection);
		}
				
		if( ( (isset($upload[0]) && !$upload[0]) || (isset($upload[1]) && !$upload[1])) && $send_ftp ) { 
			wp_safe_redirect( $redirect_to.'&car_notice=3' ); die;
		} else {
			wp_safe_redirect('admin.php?page=log-list&car_notice=2'); die;
		}
	die;	  
	}
}

function cdhl_get_autrotrade_cars_detail( $post_ids, $redirect_to, $action ) { 
	global $car_dealer_options;
	$propertiy_data = cdhl_get_car_properties( $post_ids[0], $action );
	if(empty($propertiy_data)) { 
		wp_redirect( $redirect_to.'&car_notice=1' );
	}
	
	$car_labels = [ 'old_car' => cdhl_autotrader_car_properties(), 'new_car' => cdhl_autotrader_car_properties('new') ];
	foreach( $propertiy_data as $carKey =>$data) {
	$attr_heading = array();
	$counter = 0;	
	$carData = array();
		foreach( $post_ids as $carId ) {
			$carProperties = $data;
			// check car is new or second hand
			if(taxonomy_exists( 'car_condition' )){ 
				$term = wp_get_post_terms( $carId, 'car_condition');
				if( !empty($term) ) {
					if(( $carKey == 'new_car' ) && ( strpos(strtolower($term[0]-> slug), 'new') === false )) continue;
					else if( $carKey == 'old_car' ) {
						$slug = $term[0]-> slug;
						if( strpos(strtolower($slug), 'used') !== false || strpos(strtolower($slug), 'certified') !== false ){}
						else
							continue;
					}
				}
				else
					continue;
			}
			foreach( $carProperties as $key => $car ) {
				$row = array();
				$row['carId'] = $carId;
				foreach( $car as $k =>  $carAttr) {
					if(taxonomy_exists($k)) { // Texonomy fields
						$term = wp_get_post_terms( $carId, $k);	 
						if(!empty($term))
						{ 
							$json  = json_encode($term); // Convert Json to Obj
							$term = json_decode($json, true); // Convert Obj to Array
							$name_array = array_map(function ($options) {return $options['name'];}, (array) $term); // get all name term array
							$row[$k] = ( cdhl_check_field_length(implode(',', $name_array), $k, 'auto_trader') == true )?  implode(',', $name_array) : '';
						}
						else
							$row[$k] = '';
					} else {
						$attr = get_field($k, $carId);
						if( !empty($attr) ) {
							if( $k == 'car_images' ) { //Car Image URLs
								$img = array(); 
								foreach($attr as $imgs)
									$img[] = $imgs['url'];
								$row[$k] = ( cdhl_check_field_length(implode(',', $img), $k, 'auto_trader') == true )?  implode(',', $img) : '';
							}
							else if( $k == 'video_link' || $k == 'msrp' ) {
								$row[$k] = '';	
							}
							else
							{ // other fields
								$fieldData = str_replace('"', '\'', $attr);
								$row[$k] = ( cdhl_check_field_length($fieldData, $k, 'auto_trader') == true )?  $fieldData : '';
							}
						}
						else if( $k == 'dealer_id' ) { 
							$row[$k] = ( cdhl_check_field_length($car_dealer_options['dealer_id'], $k, 'auto_trader') == true )?  $car_dealer_options['dealer_id'] : '';
						}
						else if( $k == 'images' ) { //Physical Images
							$attr = get_field('car_images', $carId);
							$row[$k] = ''; // Set physical imgs as blank
						}
						else
							$row[$k] = '';
					}
				}
				if($counter < 1) {					
					//$carData[] = $car_labels[$carKey];	// Title of each field
				}
				$carData[] = $row;
				$counter++;
			}
		}
		$carsData[$carKey] = $carData;
	}
	return $carsData;
}

function cdhl_check_field_length( $field, $key, $export_to = null ) {	$exceed = array();
	$length = ($export_to == 'auto_trader')? cdhl_autotrader_car_field_length($key) : cdhl_car_com_field_length($key);
	if( $length == '*' || (strlen( $field ) <= $length) )
		return true;
	else
		return false;
}


// Function to export car data to cars.com
function cdhl_export_cars_to_car_com( $redirect_to, $doaction, $post_ids ) {  
	global $car_dealer_options;
	$send_ftp = (isset($_GET['ftp_now']))? $_GET['ftp_now'] : NULL; // Sent to third party or not
	
	if( $doaction != 'export_car_com' ){
		return $redirect_to;
	}
	if ( empty($post_ids) ) {
		return $redirect_to;
	}	
	if ( empty($car_dealer_options['car_ftp_host']) || empty($car_dealer_options['car_username']) || empty($car_dealer_options['car_password']) || empty($car_dealer_options['car_dealer_id']) ) {
		return $redirect_to.'&car_notice=5';
	}
	
	// create source directory
	if (!file_exists(ABSPATH.'wp-content/uploads/cars/exported'))
		mkdir(ABSPATH.'wp-content/uploads/cars/exported', 0777, true);
	$source = ABSPATH.'wp-content/uploads/cars/exported/';
	
	$file_name = [$car_dealer_options['car_dealer_id'].'.txt', 'imagelist'.'_'.date('Ymdhms').'.txt'];
	
	if( $send_ftp ){ // If checkbox is checked for sent file to third party
		// Check ftp module is enable on server
		if ( ! function_exists( 'ftp_connect' ) ) { 
			return $redirect_to.'&car_notice=7';
		}
		// Ftp connection
		$connection = ftp_connect($car_dealer_options['car_ftp_host']);
		$ftp_user_name = $car_dealer_options['car_username'];
		$ftp_user_pass = $car_dealer_options['car_password'];
		$login = ftp_login($connection, $ftp_user_name, $ftp_user_pass);
		ftp_pasv($connection, true);
			if (!$connection || !$login) return $redirect_to.'&car_notice=4';
		
		// Code for get location of file to export 
		if( !isset($car_dealer_options['car_file_location']) || empty($car_dealer_options['car_file_location']) ) {
			ftp_mkdir($connection, $car_dealer_options['car_dealer_id']);
			$car_dealer_options['car_file_location'] = '/'.$car_dealer_options['car_dealer_id'];
		} else if( cdhl_ftp_is_dir( $connection, $car_dealer_options['car_file_location'] ) == false ) {
			return $redirect_to.'&car_notice=6';
		}
		
		// Create directory on third party location for datafile
		if( cdhl_ftp_is_dir( $connection, $car_dealer_options['car_file_location'] . '/' . $car_dealer_options['car_dealer_id'] ) == false )
			ftp_mkdir($connection, $car_dealer_options['car_file_location'] . '/' . $car_dealer_options['car_dealer_id']);
		$location[] = $car_dealer_options['car_file_location'] . '/' . $car_dealer_options['car_dealer_id']; // car detail file
		$location[] = $car_dealer_options['car_file_location']; // imgs file
	}
	
	// get CarsDetail Array
	$carsData = cdhl_get_car_com_detail( $post_ids, $redirect_to, $doaction );
	$cnt = 0;
	$carsId = [];
	$numericFields = ['car_year', 'sale_price', 'car_mileage'];
	foreach( $carsData as $c_key =>$data ) { 
		$delemeter = ($c_key == 'car_detail')? '|' : ',';
		$fp = fopen($source.$file_name[$cnt], "wb");
		end($data);
		$lastline = key($data);
		foreach ($data as $key=> $fields) { 
			end($fields); 
			$lastField = key($fields);
			// Discard record if required fields are blank
			if($c_key == 'car_detail') {
				$req_field = array('car_vin_number', 'car_stock_number', 'car_year', 'car_make', 'car_model', 'car_condition');
				$valid_record = 1;
				foreach( $req_field as $r_field ){
					if( !isset($fields[$r_field]) || empty($fields[$r_field]) )
						$valid_record = 0;
				}
				if( $valid_record !== 1) continue;
			}
			
			foreach( $fields as $k=> $field ){
				if( $k == 'carId' ){
					$carsId[0][] = $field; // get car Id
				continue; // Donot include carid in export file
				}
					
				// Check number fields
				if( in_array($k, $numericFields) && $c_key == 'car_detail') {
					if( !is_numeric($field) && $key != 0) $field = '';
				}
				if( $k== 'car_condition' && $field == 'Certified' ){ // set Certified as Used
					$field = 'Used';
				}
				if(( $k == $lastField )) 
					fwrite($fp, $field);
				else
					fwrite($fp, $field.$delemeter);
			}
			if( $key != $lastline )
				fwrite($fp,"\n");
		}
		fclose($fp);
		
		if( $send_ftp )
			$upload[$cnt] = ftp_put($connection, $location[$cnt].'/'.$file_name[$cnt], $source.$file_name[$cnt], FTP_BINARY);
		//unlink($source.$file_name[$cnt]);
		// Create log entry
		$ftp_status = ( isset($upload[$cnt]) && $upload[$cnt] == 1)? esc_html__('True', 'cardealer-helper') : esc_html__('False', 'cardealer-helper');
		
		// Code to check file already exist
		$args = array(
		'post_type'   => 'pgs_export_log',
		'meta_query'  => array(
				array( 'value' => $file_name[$cnt])
			)
		);
		$my_query = new WP_Query( $args );
		if( $my_query->have_posts() ) {
		  while( $my_query->have_posts() ) {
			$my_query->the_post();
			$post_id = get_the_ID();
		  } // end while
		} // end if
		else // If file does not exist then insert
		{
			$post_id = wp_insert_post( array(
				'post_type' => 'pgs_export_log',
				'post_title' => esc_html__('Car.com', 'cardealer-helper'),
				'post_content' => ''
			) );
		}
		wp_reset_postdata();
		$cars_exported ='';
		if(isset($carsId[0])) {
			$cars_exported = ( !empty($carsId[0]) )? implode(',', $carsId[0]) : '';
		}
		if($post_id) { 
			update_field( 'file_name', $file_name[$cnt], $post_id );
			update_field( 'export_to', esc_html__('Cars.com', 'cardealer-helper'), $post_id );
			update_field( 'ftp_done', $ftp_status, $post_id );
			($send_ftp)? update_field( 'ftp_done_on', date('y-m-d H:i:s') , $post_id ) : update_field( 'ftp_done_on', '-' , $post_id );
			update_field( 'created', date('y-m-d H:i:s') , $post_id );
			update_field( 'posts', $cars_exported , $post_id );
		}
	$cnt++;
	}
	
	if( $send_ftp ) { 
		// add entry into cars post type
		if( !empty($carsId[0]) ) {
			foreach($carsId[0] as $car){ 
				update_field( 'cars_com', 'yes' , $car );
				update_field( 'cars_com_export_date', date('Y/m/d H:i:s') , $car );
			}
		}
		// Close ftp connection
		ftp_close($connection);
	}
		
	if ( ( !isset($upload[0]) || !isset($upload[1]) ) && $send_ftp) { 
		return $redirect_to.'&car_notice=3';
	} else {
		return 'admin.php?page=log-list&car_notice=2';
	}
die;	
}
/**
 * Step 2: handle the custom cars.com
 * Added to solved version compatibility issue for versions < 4.7
 * Added without using csv library
 * Based on the post http://wordpress.stackexchange.com/questions/29822/custom-bulk-action
 */
function cdhl_custom_export_cars_to_car_com() { 
	global $typenow, $car_dealer_options;
	
	$redirect_to = wp_get_referer();
	$post_type = $typenow;
	if($post_type == 'cars') {
		// get the action
		$wp_list_table = _get_list_table('WP_Posts_List_Table');  // depending on your resource type this could be WP_Users_List_Table, WP_Comments_List_Table, etc
		$action = $wp_list_table->current_action();
		$allowed_actions = array("export_car_com");
		if(!in_array($action, $allowed_actions)) return;
		
		$send_ftp = (isset($_GET['ftp_now']))? $_GET['ftp_now'] : NULL; // Sent to third party or not
		if ( empty($car_dealer_options['car_ftp_host']) || empty($car_dealer_options['car_username']) || empty($car_dealer_options['car_password']) || empty($car_dealer_options['car_dealer_id']) ) { 
			wp_safe_redirect('edit.php?post_type=cars&car_notice=5');die;
		}
			
		$file_name = [$car_dealer_options['car_dealer_id'].'.txt', 'imagelist'.'_'.date('ymdhms').'.txt'];
		// create source directory
		if (!file_exists(ABSPATH.'wp-content/uploads/cars/exported'))
			mkdir(ABSPATH.'wp-content/uploads/cars/exported', 0777, true);
		$source = ABSPATH.'wp-content/uploads/cars/exported/';
			
		if( $send_ftp ){ // If checkbox is checked for sent file to third party
			// Check ftp module is enable on server
			if ( ! function_exists( 'ftp_connect' ) ) { 
				wp_safe_redirect($redirect_to.'&car_notice=7'); die;
			}
			// Ftp connection
			$connection = ftp_connect($car_dealer_options['car_ftp_host']);
			$ftp_user_name = $car_dealer_options['car_username'];
			$ftp_user_pass = $car_dealer_options['car_password'];
			$login = ftp_login($connection, $ftp_user_name, $ftp_user_pass);
			ftp_pasv($connection, true);
				if (!$connection || !$login) {wp_safe_redirect($redirect_to.'&car_notice=4');die;}
			
			// Code for get location of file to export 
			if( !isset($car_dealer_options['car_file_location']) || empty($car_dealer_options['car_file_location']) ) {
				ftp_mkdir($connection, $car_dealer_options['car_dealer_id']);
				$car_dealer_options['car_file_location'] = '/'.$car_dealer_options['car_dealer_id'];
			} else if( cdhl_ftp_is_dir( $connection, $car_dealer_options['car_file_location'] ) == false ) {
				wp_safe_redirect($redirect_to.'&car_notice=6');die;
			}
			
			// Create directory on third party location for datafile
			if( cdhl_ftp_is_dir( $connection, $car_dealer_options['car_file_location'] . '/' . $car_dealer_options['car_dealer_id'] ) == false )
				ftp_mkdir($connection, $car_dealer_options['car_file_location'] . '/' . $car_dealer_options['car_dealer_id']);
			$location[] = $car_dealer_options['car_file_location'] . '/' . $car_dealer_options['car_dealer_id']; // car detail file
			$location[] = $car_dealer_options['car_file_location']; // imgs file
		}
	
		// security check
		check_admin_referer('bulk-posts');
		// make sure ids are submitted.  depending on the resource type, this may be 'media' or 'ids'
		if(isset($_REQUEST['post'])) {
			$post_ids = array_map('intval', $_REQUEST['post']);
		}
		if(empty($post_ids)) return;
       
		// get CarsDetail Array
		$redirect_to = wp_get_referer();
		$carsData = cdhl_get_car_com_detail( $post_ids, $redirect_to, $allowed_actions[0] );
		
		$cnt = 0;
		$carsId = [];
		$numericFields = ['car_year', 'sale_price', 'car_mileage'];
		foreach( $carsData as $c_key =>$data ) { 
			$delemeter = ($c_key == 'car_detail')? '|' : ',';
			$fp = fopen($source.$file_name[$cnt], "wb");
			end($data);
			$lastline = key($data);
			foreach ($data as $key=> $fields) { 
				end($fields); 
				$lastField = key($fields);
				// Discard record if required fields are blank
				if($c_key == 'car_detail') {
					$req_field = array('car_vin_number', 'car_stock_number', 'car_year', 'car_make', 'car_model', 'car_condition');
					$valid_record = 1;
					foreach( $req_field as $r_field ){
						if( !isset($fields[$r_field]) || empty($fields[$r_field]) )
							$valid_record = 0;
					}
					if( $valid_record !== 1) continue;
				}
				
				foreach( $fields as $k=> $field ){
					if( $k == 'carId' ){
						$carsId[0][] = $field; // get car Id
					continue; // Donot include carid in export file
					}
						
					// Check number fields
					if( in_array($k, $numericFields) && $c_key == 'car_detail') {
						if( !is_numeric($field) && $key != 0) $field = '';
					}
					if( $k== 'car_condition' && $field == 'Certified' ){ // set Certified as Used
						$field = 'Used';
					}
					if(( $k == $lastField )) 
						fwrite($fp, $field);
					else
						fwrite($fp, $field.$delemeter);
				}
				if( $key != $lastline )
					fwrite($fp,"\n");
			}
			fclose($fp);
			
			if( $send_ftp )
				$upload[$cnt] = ftp_put($connection, $location[$cnt].'/'.$file_name[$cnt], $source.$file_name[$cnt], FTP_BINARY);
			//unlink($source.$file_name[$cnt]);
			// Create log entry
			$ftp_status = ( isset($upload[$cnt]) && $upload[$cnt] == 1)? esc_html__('True', 'cardealer-helper') : esc_html__('False', 'cardealer-helper');
			
			// Code to check file already exist
			$args = array(
			'post_type'   => 'pgs_export_log',
			'meta_query'  => array(
					array( 'value' => $file_name[$cnt])
				)
			);
			$my_query = new WP_Query( $args );
			if( $my_query->have_posts() ) {
			  while( $my_query->have_posts() ) {
				$my_query->the_post();
				$post_id = get_the_ID();
			  } // end while
			} // end if
			else // If file does not exist then insert
			{
				$post_id = wp_insert_post( array(
					'post_type' => 'pgs_export_log',
					'post_title' => esc_html__('Car.com', 'cardealer-helper'),
					'post_content' => ''
				) );
			}
			wp_reset_postdata();
			$cars_exported ='';
			if(isset($carsId[0])) {
				$cars_exported = ( !empty($carsId[0]) )? implode(',', $carsId[0]) : '';
			}
			if($post_id) { 
				update_field( 'file_name', $file_name[$cnt], $post_id );
				update_field( 'export_to', esc_html__('Cars.com', 'cardealer-helper'), $post_id );
				update_field( 'ftp_done', $ftp_status, $post_id );
				($send_ftp)? update_field( 'ftp_done_on', date('y-m-d H:i:s') , $post_id ) : update_field( 'ftp_done_on', '-' , $post_id );
				update_field( 'created', date('y-m-d H:i:s') , $post_id );
				update_field( 'posts', $cars_exported , $post_id );
			}
		$cnt++;
		}
		
		if( $send_ftp ) { 
			// add entry into cars post type
			if( !empty($carsId[0]) ) {
				foreach($carsId[0] as $car){ 
					update_field( 'cars_com', 'yes' , $car );
					update_field( 'cars_com_export_date', date('Y/m/d H:i:s') , $car );
				}
			}
			// Close ftp connection
			ftp_close($connection);
		}
			
		if ( (!$upload[0] || !$upload[1]) && $send_ftp) { 
			wp_safe_redirect($redirect_to.'&car_notice=3');
		} else {
			wp_safe_redirect('admin.php?page=log-list&car_notice=2');
		}
	die;
	}
}
function cdhl_get_car_com_detail( $post_ids, $redirect_to, $action ) { 
	global $car_dealer_options;
	$carProperties = cdhl_get_car_properties( $post_ids[0], $action );
	
	if(empty($carProperties)) { 
		wp_redirect( $redirect_to.'&car_notice=1' );
	}
	
	$title = cdhl_car_com_car_properties();
	$attr_heading = array();
	$counter = 0;
	$row = array();
	$img_array = array();
	$car_com_data = array();
	foreach( $post_ids as $carId ) { 
		foreach( $carProperties as $key => $car ) { 
			$row = array();
			$lengthLimitExceed = 0; // Flag to check field length exceeded
			$row['carId'] = $carId;
			foreach( $car as $k =>  $carAttr) { 
				if(taxonomy_exists($k)) { 
					$term = wp_get_post_terms( $carId, $k);	 
					if(!empty($term)) {
						$json  = json_encode($term); // Conver Obj to Array
						$term = json_decode($json, true); // Conver Obj to Array
						$name_array = array_map(function ($options) {return $options['name'];}, (array) $term); // get all name term array
						if( cdhl_check_field_length(implode(',', $name_array), $k, 'car_com') == true )
							$row[$k] = implode(',', $name_array);
						else
							$lengthLimitExceed = 1;
					}
					else
						$row[$k] = '';
				} else {
					$attr = get_field($k, $carId);
					$car_img = get_field('car_images', $carId);
					$vin_term = wp_get_post_terms( $carId, 'car_vin_number');
					
					if( !empty($car_img) && !empty($vin_term) ) {
						$vin_number = $vin_term[0]-> name;
						foreach($car_img as $imgs){ 
							if( !empty($imgs['url']) ){
								$img_array[$imgs['id']] = array(
									'dealer_id'		=> $car_dealer_options['car_dealer_id'],
									'vehicle_id' 	=> $vin_number,
									'version_date'	=> $imgs['modified'],
									'url'			=> $imgs['url']
								);
							}
						}	
					}					
					if( !empty($attr) ) {
						if( cdhl_check_field_length($attr, $k, 'car_com') == true )
							$row[$k] = $attr;
						else
							$lengthLimitExceed = 1;
					}
					else if( $k == 'dealer_id' ) {
						$dealer_id = $car_dealer_options['car_dealer_id'];
						if( cdhl_check_field_length($dealer_id, $k, 'car_com') == true )
							$row[$k] = $dealer_id;
						else
							$lengthLimitExceed = 1;
					}
					else if( $k == 'inventory_date' ) {
						$inventory_date = get_the_date( 'm/d/Y', $carId );
						if( cdhl_check_field_length($inventory_date, $k, 'car_com') == true )
							$row[$k] = $inventory_date;
						else
							$lengthLimitExceed = 1;
					}
					else
						$row[$k] = '';
				}
			}
			if($counter < 1)
				$car_com_data[] = $title;
			if( $lengthLimitExceed == 1 ) 
				continue;
			else
				$car_com_data[] = $row;
			$counter++;
		}
	}
	$car_data_array['car_detail']	= $car_com_data;
	$car_data_array['car_imgs'] 	= $img_array;
	return $car_data_array;
}

// Function to check directory exist on given path
function cdhl_ftp_is_dir($ftp, $dir)
{
    $pushd = ftp_pwd($ftp);
	if ($pushd !== false && @ftp_chdir($ftp, $dir))
    {
        ftp_chdir($ftp, $pushd);   
        return true;
    }
	return false;
} 
?>