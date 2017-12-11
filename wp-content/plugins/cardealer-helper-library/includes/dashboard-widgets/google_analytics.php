<?php
/*
 * This function used on admin dashboard to display charts of lead forms
 */
 
 
require_once __DIR__ . '/google-analytics/vendor/autoload.php';

if(!function_exists("cdhl_register_dashboard_widget_ga")){
	function cdhl_register_dashboard_widget_ga() {
		$accessToken = cdhl_get_access_token(); // accessToken
		if( !function_exists('get_field') ) return;
		$viewId = get_field('view_id', 'option');
		if( empty($viewId) || empty($accessToken)) return;

		// Duration
		$duration = cdhl_get_duration();
		$start_date = $duration['start_date'];
		$end_date = $duration['end_date'];
		wp_localize_script( 'cdhl-chart-custom', 'ga_chart', json_encode( array( 'start_date'=> $start_date, 'end_date'=> $end_date, 'access_token'=> $accessToken, 'view_id'=> $viewId ) ) );
		
		add_meta_box('cdhl_dashboard_widget_ga', esc_html('Google Analytics', 'cardealer-helper'), 'cdhl_dashboard_widget_display_report', 'dashboard', 'side', 'core');// Google Analytics
		add_meta_box('cdhl_dashboard_browser_widget_ga', esc_html('Browser Usage', 'cardealer-helper'), 'cdhl_dashboard_ga_browser_widget_data', 'dashboard', 'side', 'core');// Browser Usage
		add_meta_box('cdhl_dashboard_widget_cars_detail', esc_html('Cars Detail', 'cardealer-helper'), 'cdhl_dashboard_widget_cars_detail', 'dashboard', 'normal', 'core'); // Cars Detail
		add_meta_box('cdhl_dashboard_website_statistics', esc_html('Website Statistics', 'cardealer-helper'), 'cdhl_dashboard_website_statistics', 'dashboard', 'normal', 'core');// Website Statistics
		add_meta_box('cdhl_dashboard_website_users', esc_html('Website Users', 'cardealer-helper'), 'cdhl_dashboard_website_users', 'dashboard', 'normal', 'core');// Website Users
		add_meta_box('cdhl_dashboard_ga_goal_list', esc_html('Google Analytics Goal', 'cardealer-helper'), 'cdhl_dashboard_ga_goal_list', 'dashboard', 'side', 'core');// Goal List
	}
}
add_action( 'admin_init', 'cdhl_register_dashboard_widget_ga', 0 );

if(!function_exists("cdhl_dashboard_widget_cars_detail")){	
	function cdhl_dashboard_widget_cars_detail() {
	global $wpdb;
	
		// get carid list by inquiries count
		$inquiry_report = $wpdb->get_results( "SELECT pm.meta_value as car_id, COUNT(pm.post_id) AS total FROM ".$wpdb->prefix."postmeta pm JOIN $wpdb->posts p ON (p.ID = pm.post_id) WHERE pm.meta_key = 'car_id' AND ( p.post_type = 'pgs_inquiry' OR p.post_type = 'make_offer' OR p.post_type = 'schedule_test_drive' OR p.post_type = 'financial_inquiry') GROUP BY meta_value ORDER BY total DESC LIMIT 5", ARRAY_A );

		$car_list = array();
		foreach( $inquiry_report as $inq ):
			$car_year = wp_get_post_terms( $inq['car_id'], 'car_year' );
			$car_make = wp_get_post_terms( $inq['car_id'], 'car_make' );
			$car_model = wp_get_post_terms( $inq['car_id'], 'car_model' );
			$vin_number = wp_get_post_terms( $inq['car_id'], 'car_vin_number' );
			$stock_number = wp_get_post_terms( $inq['car_id'], 'car_stock_number' );
						
			// map car name with ids
			$car_list[ $inq['car_id'] ]['name'] = $car_year[0]-> name . ' ' . $car_make[0]-> name . ' ' . $car_model[0]-> name;
			$car_list[ $inq['car_id'] ]['inq'] = $inq[ 'total' ];
			$car_list[ $inq['car_id'] ]['vin_number'] = $vin_number[0]-> name;
			$car_list[ $inq['car_id'] ]['stock_number'] = $stock_number[0]-> name;
		endforeach;
		?>
		<div class="container">
			<p><?php esc_html_e('Cars Top Inquiries', 'cardealer-helper');?></p>
		<?php
		if( !empty($car_list) ){ 
		?>
			<table class="table" border="0" cellspacing="0" cellpadding="0">
				<thead>
				  <tr>
					<th><?php esc_html_e('Car Name', 'cardealer-helper');?></th>
					<th><?php esc_html_e('VIN Number', 'cardealer-helper');?></th>
					<th><?php esc_html_e('Stock Number', 'cardealer-helper');?></th>
					<th><?php esc_html_e('Leads', 'cardealer-helper');?></th>
				  </tr>
				</thead>
				<tbody>
					<?php
					foreach( $car_list as $cars):
					?>
						<tr>
							<td><?php esc_html_e($cars['name']);?></td>
							<td><?php esc_html_e($cars['vin_number']);?></td>
							<td><?php esc_html_e($cars['stock_number']);?></td>
							<td><?php esc_html_e($cars['inq']);?></td>
						  </tr>
					<?php
					endforeach;
					?>
				</tbody>
		  </table>
		<?php
		} else {
			esc_html_e('No Inquiries Submitted!', 'cardealer-helper');
		}		
		?>
		</div>
		<?php
		// display cars who don't have images
		$emptyImgPosts = 0;
		$car_query = new WP_Query( array(
			'post_type'      => 'cars',
			'posts_per_page' => -1,
			'meta_key' => 'car_images',
			'meta_query' => array(
				'relation' => 'OR',
				array(
					'key' => 'car_images',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key' => 'car_images',
					'value' => '',
					'compare' => '=',
				),
			),

		) );
		$emptyImgPosts = $car_query->found_posts;
		
		// Get cars with no comments
		$emptycomments = 0;
		$comment_query = new WP_Query( array(
			'post_type'      => 'cars',
			'posts_per_page' => -1,
			'meta_key' => 'general_information',
			'meta_query' => array(
				'relation' => 'OR',
				array(
					'key' => 'general_information',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key' => 'general_information',
					'value' => '',
					'compare' => '=',
				),
			),

		) );
		$emptycomments = $comment_query->found_posts;
		?>
		<br>
		<div class="container">
			<p><?php esc_html_e('Cars Data', 'cardealer-helper');?></p>
			<table class="table" border="0" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<th><?php esc_html_e('Detail', 'cardealer-helper');?></th>
						<th><?php esc_html_e('No Of Vehicles', 'cardealer-helper');?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php esc_html_e('Cars without images', 'cardealer-helper');?></td>
						<td><?php echo esc_html($emptyImgPosts);?></td>
					</tr>
					<tr>
						<td><?php esc_html_e('Cars without dealer comments', 'cardealer-helper');?></td>
						<td><?php echo esc_html($emptycomments);?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<?php
	}
}

if(!function_exists("cdhl_dashboard_website_users")){
	function cdhl_dashboard_website_users() {
		$accessToken = cdhl_get_access_token();
		
		if( !function_exists('get_field') ) return;
		$viewId = get_field('view_id', 'option');
		if( empty($viewId) || empty($accessToken)) return;
		
		// Duration
		$duration = cdhl_get_duration();
		$start_date = $duration['start_date'];
		$end_date = $duration['end_date'];
		
		if ( false === ( $getChartSummaryData = get_transient( 'website_users' ) ) ) {
			$getChartSummaryData = wp_remote_get( 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A'. $viewId .'&start-date='. $start_date .'&end-date='. $end_date .'&metrics=ga%3Ausers&dimensions=ga%3Aregion&access_token='. $accessToken );
			set_transient( 'website_users', $getChartSummaryData, 60*60*4); // ser transient for 4 hours
		} else {
			$getChartSummaryData = get_transient( 'website_users' );
		}
		if( $getChartSummaryData['response']['code'] == 200 ){
			$rigionData = json_decode($getChartSummaryData['body'], true);
			if( $rigionData['totalResults'] > 0) {
				?>
				<div id="state-chart-container" class="container">
					<p><?php esc_html_e('State Statistics', 'cardealer-helper');?></p>
					<table class="table" border="0" cellspacing="0" cellpadding="0">
						<thead>
						  <tr>
							<th><?php esc_html_e('State', 'cardealer-helper');?></th>
							<th><?php esc_html_e('Users', 'cardealer-helper');?></th>
						  </tr>
						</thead>
						<tbody>
							<?php
							foreach( $rigionData['rows'] as $state ):
							?>
								<tr>
									<td><?php esc_html_e($state[0]);?></td>
									<td><?php esc_html_e($state[1]);?></td>
								  </tr>
							<?php
							endforeach;
							?>
						</tbody>
					</table>
				</div>
				<?php
			}
		}
		
		// city-chart-container
		if ( false === ( $getChartCityData = get_transient( 'website_cities' ) ) ) {
			$getChartCityData = wp_remote_get( 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A'. $viewId .'&start-date='. $start_date .'&end-date='. $end_date .'&metrics=ga%3Ausers&dimensions=ga%3Acity&access_token='. $accessToken );
			set_transient( 'website_cities', $getChartCityData, 60*60*4); // ser transient for 4 hours
		} else {
			$getChartCityData = get_transient( 'website_cities' );
		}
		
		if( $getChartCityData['response']['code'] == 200 ){
			$cityData = json_decode($getChartCityData['body'], true);
			if( $cityData['totalResults'] > 0) {
				?>
				<div id="city-chart-container" class="container">
					<p><?php esc_html_e('City Statistics', 'cardealer-helper');?></p>
					<table class="table" border="0" cellspacing="0" cellpadding="0">
						<thead>
						  <tr>
							<th><?php esc_html_e('City', 'cardealer-helper');?></th>
							<th><?php esc_html_e('Users', 'cardealer-helper');?></th>
						  </tr>
						</thead>
						<tbody>
							<?php
							foreach( $cityData['rows'] as $city ):
							?>
								<tr>
									<td><?php esc_html_e($city[0]);?></td>
									<td><?php esc_html_e($city[1]);?></td>
								  </tr>
							<?php
							endforeach;
							?>
						</tbody>
					</table>
				</div>
				<?php
			}
		}
		// Sites Searched
		
		if ( false === ( $getChartStateData = get_transient( 'website_states' ) ) ) {
			$getChartStateData = wp_remote_get( 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A'. $viewId .'&start-date='. $start_date .'&end-date='. $end_date .'&metrics=ga%3Ausers&dimensions=ga%3AsearchUsed&access_token='. $accessToken );
			set_transient( 'website_states', $getChartStateData, 60*60*4); // ser transient for 4 hours
		} else {
			$getChartStateData = get_transient( 'website_states' );
		}
		
		if( $getChartStateData['response']['code'] == 200 ){
			$siteData = json_decode($getChartStateData['body'], true);
			if( $siteData['totalResults'] > 0) {
				?>
				<div id="sites-chart-container" class="container">
					<p><?php esc_html_e('Site Referer Statistics', 'cardealer-helper');?></p>
					<table class="table" border="0" cellspacing="0" cellpadding="0">
						<thead>
						  <tr>
							<th><?php esc_html_e('Site Referer Status', 'cardealer-helper');?></th>
							<th><?php esc_html_e('Users', 'cardealer-helper');?></th>
						  </tr>
						</thead>
						<tbody>
							<?php
							foreach( $siteData['rows'] as $site ):
							?>
								<tr>
									<td><?php esc_html_e($site[0]);?></td>
									<td><?php esc_html_e($site[1]);?></td>
								  </tr>
							<?php
							endforeach;
							?>
						</tbody>
					</table>
				</div>
				<?php
			}
		}
	}
}

if(!function_exists("cdhl_dashboard_website_statistics")){
	function cdhl_dashboard_website_statistics() {
		?>
		<div class="chart-container"><div><?php esc_html_e('Device Categories','cardealer-helper');?></div><canvas id="device-chart-container" /></canvas></div>
		<div class="chart-container"><div><?php esc_html_e('Mobile Devices','cardealer-helper');?></div><canvas id="mobile-device-container" /></canvas></div>
		<div class="chart-container"><div><?php esc_html_e('User Age Bracket','cardealer-helper');?></div><canvas id="age-chart-container" /></canvas></div>
		<div class="chart-container"><div><?php esc_html_e('User Gender','cardealer-helper');?></div><canvas id="gender-chart-container" /></canvas></div>
		<?php
	}
}


if(!function_exists("cdhl_dashboard_ga_browser_widget_data")){
	function cdhl_dashboard_ga_browser_widget_data() {
		?>
		<div class="chart-container"><canvas id="browser-pie-chart-container" /></canvas></div>
		<?php
		// Sites Searched
		$accessToken = cdhl_get_access_token();
		
		if( !function_exists('get_field') ) return;
		$viewId = get_field('view_id', 'option');
		if( empty($viewId) || empty($accessToken)) return;
		
		// Duration
		$duration = cdhl_get_duration();
		$start_date = $duration['start_date'];
		$end_date = $duration['end_date'];
		if ( false === ( $getBrowserUsageData = get_transient( 'website_browser_usage' ) ) ) {
			$getBrowserUsageData = wp_remote_get( 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A'. $viewId .'&start-date='. $start_date .'&end-date='. $end_date .'&metrics=ga%3Apageviews&dimensions=ga%3Abrowser&access_token='. $accessToken );
			set_transient( 'website_browser_usage', $getBrowserUsageData, 60*60*4); // ser transient for 4 hours
		} else {
			$getBrowserUsageData = get_transient( 'website_browser_usage' );
		}
		if( $getBrowserUsageData['response']['code'] == 200 ){
			$browserData = json_decode($getBrowserUsageData['body'], true);
			if( $browserData['totalResults'] > 0) {
				?>
				<div id="browser-list-chart-container" class="container">
					<p><?php esc_html_e('Browser Usage Status', 'cardealer-helper');?></p>
					<table class="table" border="0" cellspacing="0" cellpadding="0">
						<thead>
						  <tr>
							<th><?php esc_html_e('Browser Used', 'cardealer-helper');?></th>
							<th><?php esc_html_e('PageViews', 'cardealer-helper');?></th>
						  </tr>
						</thead>
						<tbody>
							<?php
							foreach( $browserData['rows'] as $browser ):
							?>
								<tr>
									<td><?php esc_html_e($browser[0]);?></td>
									<td><?php esc_html_e($browser[1]);?></td>
								  </tr>
							<?php
							endforeach;
							?>
						</tbody>
					</table>
				</div>
				<?php
			}
		}
	}
}

if(!function_exists("cdhl_dashboard_widget_display_report")){
	function cdhl_dashboard_widget_display_report() {
		$accessToken = cdhl_get_access_token(); // accessToken
		
		if( !function_exists('get_field') ) return;
		$viewId = get_field('view_id', 'option');
		if( empty($viewId) || empty($accessToken)) return;
		
		// Duration
		$duration = cdhl_get_duration();
		$start_date = $duration['start_date'];
		$end_date = $duration['end_date'];
		if ( false === ( $getFullReport = get_transient( 'website_report' ) ) ) {
			$getFullReport = wp_remote_get( 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A'. $viewId .'&start-date='. $start_date .'&end-date='. $end_date .'&metrics=ga%3Ausers%2Cga%3Asessions%2Cga%3AbounceRate%2Cga%3AnewUsers%2Cga%3AavgSessionDuration%2Cga%3Apageviews&access_token='. $accessToken );
			set_transient( 'website_report', $getFullReport, 60*60*4); // ser transient for 4 hours
		} else {
			$getFullReport = get_transient( 'website_report' );
		}
		
		if( $getFullReport['response']['code'] == 200 ){
			$SummaryData = json_decode($getFullReport['body'], true);
			$users = $SummaryData['totalsForAllResults']['ga:users'];
			$sessions = $SummaryData['totalsForAllResults']['ga:sessions'];
			$pageviews = $SummaryData['totalsForAllResults']['ga:pageviews'];
			$bounceRate = $SummaryData['totalsForAllResults']['ga:bounceRate'];
			$avgSessionDuration = $SummaryData['totalsForAllResults']['ga:avgSessionDuration'];
			$newUsers = $SummaryData['totalsForAllResults']['ga:newUsers'];
		} else{
			return;
		}
				
		?>
		<div class="ga-users">
			<i><img src="<?php echo esc_url( CDHL_URL.'/images/chart/icon-1.png' )?>" /></i>
			<div class="number"><?php echo esc_html($users);?></div>
			<span><?php esc_html_e('Total Users ', 'cardealer-helper');?></span>
		</div>
		<div class="ga-pageviews">
			<i><img src="<?php echo esc_url( CDHL_URL.'/images/chart/icon-2.png' )?>" /></i>
			<div class="number"><?php echo esc_html($pageviews);?></div>
			<span><?php esc_html_e('PageViews ', 'cardealer-helper'); ?></span>
		</div>
		<div class="ga-bouncerate">
			<i><img src="<?php echo esc_url( CDHL_URL.'/images/chart/icon-3.png' )?>" /></i>
			<div class="number"><?php echo number_format($bounceRate, 2, '.', '');?></div>
			<span><?php esc_html_e('BounceRate ', 'cardealer-helper');?></span>
		</div>
		<div class="ga-avg-sessionduration">
			<i><img src="<?php echo esc_url( CDHL_URL.'/images/chart/icon-4.png' )?>" /></i>
			<div class="number"><?php echo number_format($avgSessionDuration, 2, '.', '');?></div>
			<span><?php esc_html_e('Avg SessionDuration ', 'cardealer-helper');?></span>
		</div>
		<div class="ga-sessions">
			<i><img src="<?php echo esc_url( CDHL_URL.'/images/chart/icon-5.png' )?>" /></i>
			<div class="number"><?php echo esc_html($sessions);?></div>
			<span><?php esc_html_e('Sessions ', 'cardealer-helper'); ?></span>
		</div>
		<div class="ga-newusers">
			<i><img src="<?php echo esc_url( CDHL_URL.'/images/chart/icon-6.png' )?>" /></i>
			<div class="number"><?php echo esc_html($newUsers);?></div>
			<span><?php esc_html_e('NewUsers ', 'cardealer-helper');?></span>
		</div>
		<canvas id="google-analytics"></canvas>
		<?php
	}
}

if(!function_exists("cdhl_dashboard_ga_goal_list")){
	function cdhl_dashboard_ga_goal_list() { 
		// Sites Searched
		$accessToken = cdhl_get_access_token();
		if( !function_exists('get_field') ) return;
		$viewId = get_field('view_id', 'option');
		$account_id = get_field('account_id', 'option');
		$tracking_id = get_field('tracking_id', 'option');
		if( empty($viewId) || empty($accessToken) || empty($account_id) || empty($tracking_id)) return;
		
		$goalIds = array();
		$duration = cdhl_get_duration(); // Duration
		$start_date = $duration['start_date'];
		$end_date = $duration['end_date'];
		if ( false === ( $goalResponse = get_transient( 'website_goal_list' ) ) ) {
			$goalResponse = wp_remote_get( 'https://www.googleapis.com/analytics/v3/management/accounts/'. $account_id .'/webproperties/'. $tracking_id . '/profiles/'.$viewId.'/goals?&access_token='.$accessToken );
			set_transient( 'website_goal_list', $goalResponse, 60*60*4); // ser transient for 4 hours
		} else {
			$goalResponse = get_transient( 'website_goal_list' );
		}
		
		if( $goalResponse['response']['code'] != 200 ) { 
			esc_html_e('No Goal Data Found!', 'cardealer-helper'); 
			return; 
		}
		
		$goalresults = json_decode($goalResponse['body']);
		?>
		<div class="chart-container">
			<?php
			$cnt = 0;
			if( isset( $goalresults-> items ) ) {
				foreach( $goalresults-> items as $goal ){
					// Get indevisual goal data in detail
					if ( false === ( $goal_detail_response = get_transient( 'goal_detailed_response_'.$goal-> id ) ) ) {
						$goal_detail_response = wp_remote_get( 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga:'.$viewId.'&start-date='. $start_date .'&end-date='. $end_date .'&metrics=ga:goal'. $goal-> id.'Completions%2Cga:goal'. $goal-> id.'ConversionRate%2Cga:goal'. $goal-> id .'Starts&dimensions=ga:source&sort=-ga:goal'. $goal-> id .'Starts&max-results=10000&access_token='.$accessToken);
						set_transient( 'goal_detailed_response_'.$goal-> id, $goal_detail_response, 60*60*4); // ser transient for 4 hours
					} else {
						$goal_detail_response = get_transient( 'goal_detailed_response_'.$goal-> id );
					}
					
					$goal_response_array = json_decode($goal_detail_response['body'], true);
					$goalCompletions = $goal_response_array['totalsForAllResults']['ga:goal'.$goal-> id.'Completions'];
					$goalConversionRate = $goal_response_array['totalsForAllResults']['ga:goal'.$goal-> id.'ConversionRate'];
					$resources = $goal_response_array['rows'];
					
					// to be used for meter chart for conversion rate
					$goalIds[$cnt]['id'] = $goal-> id;
					$goalIds[$cnt++]['goal_rate'] = $goalConversionRate;
				?>
					<div class="goals" id="cdhl_dashboard_widget_ga">
						<div class="goal-header">
							<h4>
								<b><?php echo esc_html($goal-> name); ?></b>
							</h4>
						</div>
						<div class="goal-icons inside">
							<div class="section-left users">
								<i><img src="<?php echo esc_url( CDHL_URL.'/images/chart/icon-7.png' )?>"></i>
								<div class="number"><?php echo esc_html($goalCompletions);?></div>
								<span><?php echo esc_html__('Completions', 'cardealer-helper'); ?> </span>
								<span ><a href="javascript:void(0)" class="cd_dialog" data-id="goal-res-<?php esc_attr_e($goal-> id);?>"><?php echo esc_html__('Sources : ', 'cardealer-helper'); esc_html_e($goalCompletions);?></a> </span>
							</div>
							<div class="section-right users">
								<canvas id="goal-<?php esc_attr_e($goal-> id);?>" style="width: 150px; float: left; padding: 0px;"></canvas>
								<span><?php echo esc_html__('Conversion Rate', 'cardealer-helper'); ?> </span>
								<span><?php echo esc_html(number_format((float)$goalConversionRate, 2, '.', '')); ?>%</span>
							</div>
							<table id="goal-res-<?php esc_attr_e($goal-> id);?>" class="goal-content table" border="0" cellspacing="0" cellpadding="0" >
								<thead>
									<th colspan=2><?php esc_html_e('Refering Sources', 'cardealer-helper');?></th>
								</thead>
								<tbody>
								<?php 
									foreach($resources as $row){
										if( $row[1] != 0 ) {
										?>
										<tr><td> <?php echo esc_html($row[0]).'<br>'; ?> </td><td> <?php echo esc_html($row[1]).'<br>'; ?> </td></tr>
										<?php
										}
									}
								?>
								</tbody>
							</table>							
						</div>						
					</div>
				<?php
				}
			}
			?>			
		</div>
		<?php
		wp_localize_script( 'cdhl-chart-custom', 'ga_goal', json_encode( array( 'goal_ids'=> $goalIds ) ) );
	}
}

if(!function_exists("cdhl_get_access_token")){
	function cdhl_get_access_token() {
		if( function_exists('get_field') ){
			$key_file = get_field('key_file', 'option');//__DIR__ . '/google-analytics/service-account-credentials.json';
			// check key file available
			$test = cdhl_check_keyfile_exist($key_file['url']);
			if( empty($key_file) || cdhl_check_keyfile_exist($key_file['url']) == false) { 
				return;
			}
			$key_file_path = get_attached_file( $key_file['id'] ); // Full path
			
			$client = new Google_Client();
			$client->setApplicationName("Hello Analytics Reporting");
			$client->setAuthConfig($key_file_path);
			$client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
			$client->refreshTokenWithAssertion();
			$token = $client->getAccessToken();
		return $token['access_token'];
		}
		return;
	}
}

if(!function_exists("cdhl_get_duration")){
	function cdhl_get_duration() {
		if( function_exists('get_field') ){
			$custom_duration = get_field('custom_time', 'option');
			$start_date = ( !empty(get_field('start_date', 'option') && $custom_duration == true )? get_field('start_date', 'option') : '30daysAgo' );
			$end_date = ( !empty(get_field('end_date', 'option') && $custom_duration == true )? get_field('end_date', 'option') : 'today' );
		return array('start_date' => $start_date, 'end_date' => $end_date);
		}
		return;
	}
}

if(!function_exists("cdhl_check_keyfile_exist")){
	function cdhl_check_keyfile_exist($keyfile){
		$ch = curl_init($keyfile);    
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_exec($ch);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if($code == 200){
			$status = true;
		}else{
			$status = false;
		}
		curl_close($ch);
	return $status;
	}
}
?>