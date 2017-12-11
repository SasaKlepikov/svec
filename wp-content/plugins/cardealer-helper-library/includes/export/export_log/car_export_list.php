<?php
add_action('admin_menu', 'cdhl_export_car_list');
function cdhl_export_car_list(){	
    add_menu_page   ( esc_html('Car export list','cardealer-helper'),  esc_html('Car export list','cardealer-helper'), 'manage_options', 'car-export-list', 'cdhl_get_export_list','dashicons-list-view', 30);
    //add_submenu_page( 'edit.php?post_type=cars', esc_html('Car export list','cardealer-helper'), esc_html('Car export list','cardealer-helper'), 'manage_options', 'car-export-list', 'cdhl_get_export_list');
}

function cdhl_get_export_list()
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
			<h2><?php esc_html_e('Cars Export List', 'cardealer-helper');?></h2>
			<table id="export-cars-list" class="display" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th></th>
						<th><?php esc_html_e('Vehicle', 'cardealer-helper'); ?></th>
						<th class="dealer-export"><?php esc_html_e('AutoTrader', 'cardealer-helper'); ?></th>
						<th class="dealer-export"><?php esc_html_e('Cars.com', 'cardealer-helper'); ?></th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
	<?php
}

add_action( 'wp_ajax_nopriv_get_export_car_list', 'cdhl_get_export_car_list');
add_action( 'wp_ajax_get_export_car_list', 'cdhl_get_export_car_list');
function cdhl_get_export_car_list(){
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
	$mode=(!empty($order[0]['dir'])) ? $order[0]['dir'] : 'desc';
	
	$result['recordsTotal'] = cdhl_export_cars_count($term);
    $result['recordsFiltered'] = cdhl_export_cars_count($term);
    $datas=cdhl_search_export_list($term,$start,$length,'id',$mode);
    $data=array();
    $i=0;
	if($datas)
	{
		foreach($datas as $d){
			$data[$i][]= $d['cars_image'];
			$data[$i][]= $d['car_year'].' '.$d['car_make'].' '.$d['car_model'].'<br><br>'.$d['car_vin_number'].'<br>'.$d['car_price'];
			$data[$i][]= $d['auto_trader'];
			$data[$i][]= $d['cars_com'];
			$i++;
		}
	}
    $result['data']=$data;
	echo json_encode($result);die;
			
}

function cdhl_export_cars_count($term){
	global $wpdb;
	$sql = "SELECT count(DISTINCT pm.post_id) FROM $wpdb->postmeta pm JOIN $wpdb->posts p ON (p.ID = pm.post_id) WHERE p.post_type = 'cars' and p.post_status = 'publish'";
	$result['total'] = $wpdb->get_var($sql);	
return $result['total'];
}

function cdhl_search_export_list($term,$start=0,$length=50,$orderby='created',$mode='asc'){
	$args = array(
		'post_type' => 'cars',
		'post_status' => 'publish',
		'posts_per_page' => $length,
		'orderby' => 'title',
		'order' => 'DESC'
	);
	$car_list = new WP_Query( $args );

	if ( $car_list -> have_posts() ) {
		$cnt= 0;
		while ($car_list->have_posts()) : $car_list->the_post();
			$carID = get_the_ID();
			$car_vin_number = wp_get_post_terms( get_the_ID(), 'car_vin_number');
			$car_year = wp_get_post_terms( get_the_ID(), 'car_year');
			$car_make = wp_get_post_terms( get_the_ID(), 'car_make');
			$car_model = wp_get_post_terms( get_the_ID(), 'car_model');
			
			$result[$cnt]['cars_image'] = (function_exists('cardealer_get_cars_image'))?cardealer_get_cars_image('car_thumbnail', get_the_ID()):'';
			$result[$cnt]['car_price'] = cardealer_get_car_price('', get_the_ID());
			$result[$cnt]['car_vin_number'] = $car_vin_number[0]-> name;
			$result[$cnt]['car_year'] = $car_year[0]-> name;
			$result[$cnt]['car_make'] = $car_make[0]-> name;
			$result[$cnt]['car_model'] = $car_model[0]-> name;
			/* Auto Trader */
			$dealer = get_post_meta($carID, 'auto_trader', true); 
			if( isset($dealer) && !empty($dealer) && $dealer == 'yes') {
				$auto_trader =  date('m/d/Y', strtotime(get_post_meta($carID, 'auto_export_date', true)));
				$result[$cnt]['auto_trader'] =  sprintf('<div class="dealer-export"><i class="fa fa-check-circle-o" aria-hidden="true"></i>%1$s</div>', $auto_trader );
			}
			else
				$result[$cnt]['auto_trader'] = sprintf('<div class="dealer-export">-</div>');
			
			/* Cars.Com */
			$dealer = get_post_meta($carID, 'cars_com', true); 
			if( isset($dealer) && !empty($dealer) && $dealer == 'yes') {
				$cars_com =  date('m/d/Y', strtotime(get_post_meta($carID, 'cars_com_export_date', true)));
				$result[$cnt]['cars_com'] =  sprintf('<div class="dealer-export"><i class="fa fa-check-circle-o" aria-hidden="true"></i>%1$s</div>', $cars_com);
			}
			else
				$result[$cnt]['cars_com'] = sprintf( '<div class="dealer-export">-</div>');
			
		$cnt++;
		endwhile;
	}
return $result;
}

?>