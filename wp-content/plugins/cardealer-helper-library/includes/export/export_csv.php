<?php 
// Function to export car data to csv file
function cdhl_export_cars_to_csv( $redirect_to, $doaction, $post_ids ) {
	if( $doaction != 'export_cars' ){
		return $redirect_to;
	}
	if ( empty($post_ids) ) {
		return $redirect_to;
	}
	
	// get CarsDetail Array
	$csvData = cdhl_get_cars_detail( $post_ids, $redirect_to, $doaction );
	
    // Write data into csv file
	header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=cardealer.csv');
	$fp = fopen('php://output', "wb");
	end($csvData);
	$lastline = key($csvData);
	foreach ($csvData as $key=> $fields) { 
		end($fields); 
		$lastField = key($fields);
		foreach( $fields as $k=> $field ){
			$field = str_replace("\n", "", $field); // Remove new line
			$field = str_replace("\r", "", $field); // Remove new line
			$field = str_replace("\"", "'", $field); // Remove Quotes
			if(( $k == $lastField )){
				if (strpos($field, ',') !== false) {
					fwrite($fp, '"' . $field . '"');
				}else{
					fwrite($fp, $field );
				}
			}
			else{
				if (strpos($field, ',') !== false) {
					fwrite($fp, '"' . $field . '",');
				}else{
					fwrite($fp, $field . ',');
				}
			}
		}
		if( $key != $lastline )
			fwrite($fp, PHP_EOL);
	}
	fclose($fp);
die;	
}

/**
 * Step 2: handle the custom Bulk Action
 * Added to solved version compatibility issue for versions < 4.7
 * Added without using csv library
 * Based on the post http://wordpress.stackexchange.com/questions/29822/custom-bulk-action
 */
function cdhl_custom_export_cars_to_csv() {
	global $typenow;
	$post_type = $typenow;
	if($post_type == 'cars') {
		// get the action
		$wp_list_table = _get_list_table('WP_Posts_List_Table');  // depending on your resource type this could be WP_Users_List_Table, WP_Comments_List_Table, etc
		$action = $wp_list_table->current_action();
		$allowed_actions = array("export_cars");
		if(!in_array($action, $allowed_actions)) return;
		// security check
		check_admin_referer('bulk-posts');
		// make sure ids are submitted.  depending on the resource type, this may be 'media' or 'ids'
		if(isset($_REQUEST['post'])) {
			$post_ids = array_map('intval', $_REQUEST['post']);
		}
		if(empty($post_ids)) return;
       
		// get CarsDetail Array
		$redirect_to = wp_get_referer();
		$csvData = cdhl_get_cars_detail( $post_ids , $redirect_to, $allowed_actions[0] );
	
     	header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=cardealer.csv');
        
		$fp = fopen('php://output', "wb");
		end($csvData);
		$lastline = key($csvData);
		foreach ($csvData as $key=> $fields) { 
			end($fields); 
			$lastField = key($fields);
			foreach( $fields as $k=> $field ){
				$field = str_replace("\n", "", $field); // Remove new line
				$field = str_replace("\r", "", $field); // Remove new line
				$field = str_replace("\"", "'", $field); // Remove Quotes
				if(( $k == $lastField )){
					if (strpos($field, ',') !== false) {
						fwrite($fp, '"' . $field . '"');
					}else{
						fwrite($fp, $field );
					}
				}
				else{
					if (strpos($field, ',') !== false) {
						fwrite($fp, '"' . $field . '",');
					}else{
						fwrite($fp, $field . ',');
					}
				}
			}
			if( $key != $lastline )
				fwrite($fp, PHP_EOL); // Add new line after one record
		}
		fclose($fp);
        die;  
	}
}
?>