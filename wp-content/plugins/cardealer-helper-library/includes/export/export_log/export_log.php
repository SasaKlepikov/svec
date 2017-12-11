<?php
/* code to add FTP NOW? checkbox on car post type page*/
add_action('admin_head-edit.php','cdhl_addCustomFtpCheckbox');
function cdhl_addCustomFtpCheckbox() {
	global $current_screen;
	if ( $current_screen->post_type != 'cars' ) {
        return;
    }
	?>
        <script type="text/javascript">
            jQuery(document).ready( function($) {
                jQuery('#doaction').before("<div id='ftp_now' class='alignleft actions' style='display:none'><input type='checkbox' name='ftp_now'>FTP Now?</div>");
            });
        </script>
    <?php
}

add_action('admin_menu', 'cdhl_cardealer_export_log_menu');
function cdhl_cardealer_export_log_menu(){	
	add_menu_page( esc_html('Cardealer Log','cardealer-helper'), esc_html('Inventory Logs','cardealer-helper'), 'manage_options', 'log-list', 'cdhl_get_log_data' ,'dashicons-list-view', 30);
	add_submenu_page( 'log-list', esc_html('Export Log','cardealer-helper'), esc_html('Export Log','cardealer-helper'), 'manage_options', 'log-list', 'cdhl_get_log_data');
}

function cdhl_get_log_data()
{
	// Datatable Style
	wp_register_style( 'export_log_dataTable_css', trailingslashit(CDHL_URL).'/css/export_log/jquery.dataTables.min.css');
	wp_enqueue_style( 'export_log_dataTable_css' );
	
	// Datatable Script
	wp_register_script( 'export_log_dataTable', trailingslashit(CDHL_URL).'js/export_log/jquery.dataTables.min.js', array(),false,true );
	wp_register_script( 'export_log_dataTable_custom', trailingslashit(CDHL_URL).'js/export_log/custom_dataTables.js', array(),false,true );
	wp_register_script( 'jquery-1.12.4', trailingslashit(CDHL_URL).'js/export_log/jquery-1.12.4.js', array(),false,true );
	
	wp_enqueue_script( 'export_log_dataTable' );
	wp_enqueue_script( 'export_log_dataTable_custom' );
	wp_enqueue_script( 'cardealer-cars');
	?>
	<div class="table_area">
		<div class="wrap">
			<div class="msg"></div>
			<h2><?php esc_html_e('Cars Export Log List', 'cardealer-helper');?></h2>
			<table id="export-log" class="display" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th><?php esc_html_e('File Name'); ?></th>
						<th><?php esc_html_e('Export To'); ?></th>
						<th><?php esc_html_e('FTP Done'); ?></th>
						<th><?php esc_html_e('FTP Done On'); ?></th>
						<th><?php esc_html_e('Created'); ?></th>
						<th><?php esc_html_e('Action'); ?></th>						
					</tr>
				</thead>
			</table>
		</div>
	</div>
	<?php
}

add_action( 'wp_ajax_nopriv_get_export_log', 'cdhl_get_export_log');
add_action( 'wp_ajax_get_export_log', 'cdhl_get_export_log');
function cdhl_get_export_log(){
	$result=array();
            $recordsTotal=101;
            //check search 
            $search=$_REQUEST['search'];
            $start=$_REQUEST['start'];
            $length=$_REQUEST['length'];
            $term=$search['value'];
            if(empty($term))
                $term='';
            $order= isset($_REQUEST['order'])? $_REQUEST['order'] : '';
            $column=isset($order[0]['column'])? $order[0]['column'] : '';
            if(empty($column))
                $orderby='created';
            $mode=(!empty($order[0]['dir'])) ? $order[0]['dir'] : 'desc';
			
            if($column==1)
                $orderby='file_name';
            else if($column==2)
                $orderby='export_to';
			else if($column==3)
                $orderby='ftp_done';
			else if($column==4)
                $orderby='ftp_done_on';
			else if($column==5)
                $orderby='created';
				
			$result['recordsTotal'] = cdhl_log_count($term);
            $result['recordsFiltered'] = cdhl_log_count($term);
            $datas=cdhl_search_log($term,$start,$length,$orderby,$mode);
            $data=array();
            $i=0;
			if($datas)
			{
				foreach($datas as $d){ 
				$up_dir_url=wp_upload_dir();
					$data[$i][]= "<a href='". $up_dir_url['baseurl'] . "/cars/exported/" . $d['file_name']. "' download >" . $d['file_name'] . "</a>";
					$data[$i][]= $d['export_to'];
					$data[$i][]= $d['ftp_done'];
					$data[$i][]= $d['ftp_done_on'];
					$data[$i][]= $d['created'];
					$data[$i][]= ($d['ftp_done'] == 'True')? '<a href="?log_id='. $d['log_id'] .'" class="btn btn-info">'.esc_html__('Resend FTP','cardealer-helper').'</a>' : '<a href="?log_id='. $d['log_id'] .'" class="btn btn-primary">'.esc_html__('Send FTP','cardealer-helper').'</a>';
					$i++;
				}
			}
            $result['data']=$data;
			echo json_encode($result);die;
			
}

function cdhl_log_count($term){
	global $wpdb;
	$sql = "SELECT count(DISTINCT pm.post_id) FROM $wpdb->postmeta pm JOIN $wpdb->posts p ON (p.ID = pm.post_id) WHERE p.post_type = 'pgs_export_log'";
	$result['total'] = $wpdb->get_var($sql);	
return $result['total'];
}

function cdhl_search_log($term,$start=0,$length=50,$orderby='created',$mode='asc'){
	if($orderby == 'created') {
		$args = array(
			'post_type' => 'pgs_export_log',
			'orderby' 	=> 'meta_value',
			'meta_key'	=> $orderby,
			'order'		=> $mode,
			'posts_per_page' => $length
		);
	} else {
		$args = array(
			'post_type' => 'pgs_export_log',
			'posts_per_page' => $length
		);
	}
	$log_query = new WP_Query($args);
	$result = array();
	$cnt = 0;
	while ($log_query->have_posts()) : $log_query->the_post();
		$up_dir_url=wp_upload_dir();
		$result[$cnt]['log_id'] = get_the_ID();
		$result[$cnt]['file_name'] = get_post_meta(get_the_ID(), 'file_name', true);
		$result[$cnt]['export_to'] = get_post_meta(get_the_ID(), 'export_to', true);
		$result[$cnt]['ftp_done'] = get_post_meta(get_the_ID(), 'ftp_done', true);
		$result[$cnt]['ftp_done_on'] = get_post_meta(get_the_ID(), 'ftp_done_on', true);
		$result[$cnt]['created'] = get_post_meta(get_the_ID(), 'created', true);
	$cnt++;
	endwhile;
return $result;
}
global $wp_version;
if(isset($_GET['log_id']) && !empty($_GET['log_id'])) {
	if ( $wp_version >= 4.7 )
		add_action('admin_init', 'cdhl_sendFile');
	else
		add_action('admin_init', 'cdhl_sendFileCustom');
}

function cdhl_sendFile() {
	global $car_dealer_options;
	$thirdParty = get_post_meta( $_GET['log_id'], 'export_to', true );
	$filename = get_post_meta( $_GET['log_id'], 'file_name', true );
	$postIds = get_post_meta( $_GET['log_id'], 'posts', true );
	$postIds = explode(",", $postIds);
	
	$redirect_to = wp_get_referer(); 
	$source = ABSPATH.'wp-content/uploads/cars/exported/';
	
	// Check ftp module is enable on server
	if ( ! function_exists( 'ftp_connect' ) ) { 
		wp_safe_redirect( $redirect_to .'&car_notice=7' ); die;
	}
	if( $thirdParty == 'Auto Trader') {
		$connection = ftp_connect($car_dealer_options['ftp_host']);
		$ftp_user_name = $car_dealer_options['username'];
		$ftp_user_pass = $car_dealer_options['password'];
		$login = ftp_login($connection, $ftp_user_name, $ftp_user_pass);
		ftp_pasv($connection, true);
			if (!$connection || !$login) { wp_safe_redirect( $redirect_to .'&car_notice=4' ); die;}
		
		// Code for get location of file to export 
		if( !isset($car_dealer_options['file_location']) || empty($car_dealer_options['file_location']) ) {
			$car_dealer_options['file_location'] = '';
		} else if( cdhl_ftp_is_dir( $connection, $car_dealer_options['file_location'] ) == false ) {
			wp_safe_redirect( $redirect_to .'&car_notice=6' );die;
		}
		
		$file = basename($source.$filename, ".txt");
		$location = $car_dealer_options['file_location']; // imgs file
		if(substr_count($file, "_") > 1){
			$file = substr($file, 0, strpos($file, '_', strpos($file, '_')+1));
		}
		
		// Send file		
		$dest_file = $file.'.txt';
		$upload = ftp_put($connection, $location.'/'.$dest_file, $source.$filename, FTP_BINARY);
		
		ftp_close($connection);
		if( !$upload ) { 
			wp_safe_redirect( $redirect_to .'&car_notice=3' ); die;
		} else {
			$newFile = $file. '_' . time() . '.txt';
			if( update_post_meta($_GET['log_id'], 'file_name', $newFile, $filename) ){
				update_post_meta($_GET['log_id'], 'ftp_done', 'True');
				update_post_meta($_GET['log_id'], 'ftp_done_on', date('y-m-d H:i:s'));
				rename($source . $filename, $source . $newFile);
			}
			
			if(!empty($postIds)){ 
				foreach($postIds as $post){
					update_post_meta($post, 'auto_trader', 'yes');
					update_post_meta($post, 'auto_export_date', date('Y-m-d H:i:s'));
				}
			}
			wp_safe_redirect( $redirect_to .'&car_notice=2' ); die;
		}
	} 
	else 
	{
		$connection = ftp_connect($car_dealer_options['car_ftp_host']);  
		$ftp_user_name = $car_dealer_options['car_username'];
		$ftp_user_pass = $car_dealer_options['car_password'];
		$login = ftp_login($connection, $ftp_user_name, $ftp_user_pass);
		
		ftp_pasv($connection, true);
			if (!$connection || !$login){ wp_safe_redirect( $redirect_to .'&car_notice=4' ); die;}
		
		// Code for get location of file to export 
		if( !isset($car_dealer_options['car_file_location']) || empty($car_dealer_options['car_file_location']) ) {
			ftp_mkdir($connection, $car_dealer_options['car_dealer_id']);
			$car_dealer_options['car_file_location'] = '/'.$car_dealer_options['car_dealer_id'];
		} else if( cdhl_ftp_is_dir( $connection, $car_dealer_options['car_file_location'] ) == false ) {
			wp_safe_redirect( $redirect_to .'&car_notice=6' ); die;
		}
		
		$file = basename($source.$filename, ".txt");
		if (strpos($filename, $car_dealer_options['car_dealer_id']) !== false) {
			// Create directory on third party location for datafile
			if( cdhl_ftp_is_dir( $connection, $car_dealer_options['car_file_location'] . '/' . $car_dealer_options['car_dealer_id'] ) == false )
				ftp_mkdir($connection, $car_dealer_options['car_file_location'] . '/' . $car_dealer_options['car_dealer_id']);
			$location = $car_dealer_options['car_file_location'] . '/' . $car_dealer_options['car_dealer_id']; // data file
			if(substr_count($file, "_") > 0){
				$file = substr($file, 0, strpos($file, '_', strpos($file, '_')));
			}
		} else {
			$location = $car_dealer_options['car_file_location']; // imgs file
			if(substr_count($file, "_") > 1){
				$file = substr($file, 0, strpos($file, '_', strpos($file, '_')+1));
			}
		}	
		// Send file	
		$dest_file = $file.'.txt';
		$upload = ftp_put($connection, $location.'/'.$dest_file, $source.$filename, FTP_BINARY);
		ftp_close($connection);
		if( !$upload ) { 
			wp_safe_redirect( $redirect_to .'&car_notice=3' ); die;
		} else {
			$newFile = $file. '_' . time() . '.txt';
			if( update_post_meta( $_GET['log_id'], 'file_name', $newFile, $filename ) ){
				update_post_meta($_GET['log_id'], 'ftp_done', 'True');
				update_post_meta($_GET['log_id'], 'ftp_done_on', date('y-m-d H:i:s'));
				rename($source . $filename, $source . $newFile);
			}
			if(!empty($postIds)){ 
				foreach($postIds as $post){
					update_post_meta($post, 'cars_com', 'yes');
					update_post_meta($post, 'cars_com_export_date', date('Y-m-d H:i:s'));
				}
			}
			wp_safe_redirect( $redirect_to .'&car_notice=2' ); die;
		}
	}
}

function cdhl_sendFileCustom() {
	global $car_dealer_options;
	$thirdParty = get_post_meta( $_GET['log_id'], 'export_to', true );
	$filename = get_post_meta( $_GET['log_id'], 'file_name', true );
	$postIds = get_post_meta( $_GET['log_id'], 'posts', true );
	$postIds = explode(",", $postIds);
	
	$redirect_to = wp_get_referer(); 
	$source = ABSPATH.'wp-content/uploads/cars/exported/';
	
	// Check ftp module is enable on server
	if ( ! function_exists( 'ftp_connect' ) ) { 
		wp_safe_redirect( $redirect_to .'&car_notice=7' ); die;
	}
	if( $thirdParty == 'Auto Trader') {
		$connection = ftp_connect($car_dealer_options['ftp_host']);  
		
		$ftp_user_name = $car_dealer_options['username'];
		$ftp_user_pass = $car_dealer_options['password'];
		$login = ftp_login($connection, $ftp_user_name, $ftp_user_pass);
		ftp_pasv($connection, true);
			if (!$connection || !$login) { wp_safe_redirect( $redirect_to .'&car_notice=4' ); die; }
		
		// Code for get location of file to export 
		if( !isset($car_dealer_options['file_location']) || empty($car_dealer_options['file_location']) ) {
			$car_dealer_options['file_location'] = '';
		} else if( cdhl_ftp_is_dir( $connection, $car_dealer_options['file_location'] ) == false ) {
			wp_safe_redirect( $redirect_to .'&car_notice=6' ); die;
		}
		
		$file = basename($source.$filename, ".txt");
		$location = $car_dealer_options['file_location']; // imgs file
		if(substr_count($file, "_") > 1){
			$file = substr($file, 0, strpos($file, '_', strpos($file, '_')+1));
		}
		
		// Send file		
		$dest_file = $file.'.txt';
		$upload = ftp_put($connection, $location.'/'.$dest_file, $source.$filename, FTP_BINARY);
		
		ftp_close($connection);
		if( !$upload ) { 
			wp_safe_redirect( $redirect_to .'&car_notice=3' ); die;
		} else {
			$newFile = $file. '_' . time() . '.txt';
			if( update_post_meta($_GET['log_id'], 'file_name', $newFile, $filename) ){
				update_post_meta($_GET['log_id'], 'ftp_done', 'True');
				update_post_meta($_GET['log_id'], 'ftp_done_on', date('y-m-d H:i:s'));
				rename($source . $filename, $source . $newFile);
			}
			
			if(!empty($postIds)){ 
				foreach($postIds as $post){
					update_post_meta($post, 'auto_trader', 'yes');
					update_post_meta($post, 'auto_export_date', date('Y-m-d H:i:s'));
				}
			}
			wp_safe_redirect( $redirect_to .'&car_notice=2' ); die;
		}
	} 
	else 
	{
		$connection = ftp_connect($car_dealer_options['car_ftp_host']);  
		$ftp_user_name = $car_dealer_options['car_username'];
		$ftp_user_pass = $car_dealer_options['car_password'];
		$login = ftp_login($connection, $ftp_user_name, $ftp_user_pass);
		ftp_pasv($connection, true);
			if (!$connection || !$login) { wp_safe_redirect( $redirect_to .'&car_notice=4' ); die; }
		
		// Code for get location of file to export 
		if( !isset($car_dealer_options['car_file_location']) || empty($car_dealer_options['car_file_location']) ) {
			ftp_mkdir($connection, $car_dealer_options['car_dealer_id']);
			$car_dealer_options['car_file_location'] = '/'.$car_dealer_options['car_dealer_id'];
		} else if( cdhl_ftp_is_dir( $connection, $car_dealer_options['car_file_location'] ) == false ) {
			wp_safe_redirect( $redirect_to .'&car_notice=6' ); die;
		}
		
		$file = basename($source.$filename, ".txt");
		if (strpos($filename, $car_dealer_options['car_dealer_id']) !== false) {
			// Create directory on third party location for datafile
			if( cdhl_ftp_is_dir( $connection, $car_dealer_options['car_file_location'] . '/' . $car_dealer_options['car_dealer_id'] ) == false )
				ftp_mkdir($connection, $car_dealer_options['car_file_location'] . '/' . $car_dealer_options['car_dealer_id']);
			$location = $car_dealer_options['car_file_location'] . '/' . $car_dealer_options['car_dealer_id']; // data file
			if(substr_count($file, "_") > 0){
				$file = substr($file, 0, strpos($file, '_', strpos($file, '_')));
			}
		} else {
			$location = $car_dealer_options['car_file_location']; // imgs file
			if(substr_count($file, "_") > 1){
				$file = substr($file, 0, strpos($file, '_', strpos($file, '_')+1));
			}
		}	
		// Send file		
		$dest_file = $file.'.txt';
		$upload = ftp_put($connection, $location.'/'.$dest_file, $source.$filename, FTP_BINARY);
		ftp_close($connection);
		if( !$upload ) { 
			wp_safe_redirect( $redirect_to .'&car_notice=3' ); die;
		} else {
			$newFile = $file. '_' . time() . '.txt';
			if( update_post_meta( $_GET['log_id'], 'file_name', $newFile, $filename ) ){
				update_post_meta($_GET['log_id'], 'ftp_done', 'True');
				update_post_meta($_GET['log_id'], 'ftp_done_on', date('y-m-d H:i:s'));
				rename($source . $filename, $source . $newFile);
			}
			if(!empty($postIds)){ 
				foreach($postIds as $post){
					update_post_meta($post, 'cars_com', 'yes');
					update_post_meta($post, 'cars_com_export_date', date('Y-m-d H:i:s'));
				}
			}
			wp_safe_redirect( $redirect_to .'&car_notice=2' ); die;
		}
	}
}
?>