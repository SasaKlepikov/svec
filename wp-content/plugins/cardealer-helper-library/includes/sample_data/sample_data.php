<?php
// Dynamically add import section
if (!function_exists('cdhl_sample_data_section')) {
	function cdhl_sample_data_section($sections) {
		
		$sample_data_desc ='';

		$sample_data_desc_mandetory = '<p><span style="color:#FF0000;">' . wp_kses( __( 'Please take a backup before importing any sample data to prevent any data loss during installation.', 'cardealer-helper' ),
			array(
				'br'    => array(),
				'strong'=> array(
					'style' => array(),
				),
			)
		).'</span></p>';
	
		$sections[] = array(
			'title'  => esc_html__( 'Sample Data', 'cardealer-helper' ),
			//'desc'   => $sample_data_desc.$sample_data_desc_mandetory,
			'desc'   => wp_kses( __( 'You can import pre-defined sample data, as show on our demo site, from here.', 'thecorps' ),
				array(
					'br'    => array(),
					'strong'=> array(
						'style' => array(),
					),
				)
			)
			. '<br><br>' . wp_kses( __( '"<strong style="color:#000">Default</strong>" data contains all basic sample contents with "<strong style="color:#000">Home Page 1</strong>" and individual <strong style="color:#000">Home Pages</strong> contains <strong style="color:#000">only home page</strong> content. <strong style="color:#000">Before importing individual home pages, first import "Default" data.</strong>', 'thecorps' ),
				array(
					'br'    => array(),
					'strong'=> array(
						'style' => array(),
					),
					'span'=> array(
						'style' => array(),
					),
				)
			)
			. '<br><br>' . wp_kses( __( '<strong style="color:#000">Note</strong>: All pre-defined sample pages are configured using existing data. So, some of the shortcodes will not work if relative data is missing. For example, if testimonials data is not imported/added, testimonials shortcode will not work. So, please ensure all required data is imported/added successfully.', 'thecorps' ),
				array(
					'br'    => array(),
					'strong'=> array(
						'style' => array(),
					),
				)
			)
			. '<br><br><span style="color:#FF0000;">' . wp_kses( __( 'Please take backup before importing any sample data to prevent any data-loss during installation.', 'thecorps' ),
				array(
					'br'    => array(),
					'strong'=> array(
						'style' => array(),
					),
				)
			).'</span>',
			'id'     => 'sample_data',
			'icon'   => 'fa fa-database',			
			'fields' => array(
				array(
					'id'        => 'cd_sample_data_import',
					'type'      => 'cd_sample_import',
					'full_width'=> true,
				)
			)
		);
		return $sections;
	}
}
add_filter('redux/options/car-dealer-options/sections', 'cdhl_sample_data_section', 11);

function cdhl_theme_sample_import_field_completed(){
	echo '<div class="admin-demo-data-notice notice-green" style="display:none;"><strong>'.esc_html__( 'Successfully installed demo data.', 'cardealer-helper' ).'</strong>' . '</div>';
	echo '<div class="admin-demo-data-reload notice-red" style="display: none;"><strong>'.esc_html__( 'Please wait... reloading page to load changes.', 'cardealer-helper' ).'</strong></div>';
}
add_action( "redux/options/car-dealer-options/settings/change",'cdhl_theme_sample_import_field_completed');

// Prepapre Sample Data folder details
function cdhl_theme_sample_datas(){
	$cardealer_helper_sample_datas = array();
	
	if ( WP_DEBUG || false === ( $cardealer_helper_sample_datas_transient = get_transient( 'cardealer_helper_sample_datas' ) ) ) {
		$data_dir_path = get_template_directory().'/includes/sample_data';
		if ( is_dir( $data_dir_path ) ) {
			$data_dirs = cdhl_pgscore_get_file_list( '*', $data_dir_path );
			if( !empty($data_dirs) && is_array($data_dirs) ){ 
				foreach($data_dirs as $data_dir){ 
					if( !is_dir($data_dir) ){
						continue;
					}
					
					$data_dir = trailingslashit( str_replace( '\\', '/', $data_dir ) );
					$data_url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', $data_dir ) );
					
					$path_parts = pathinfo($data_dir);
					extract($path_parts);
					
					$excluded_dirs = array(
						'nppBackup',
					);
					
					apply_filters('cardealer_helper_sample_data_excluded_dirs', $excluded_dirs);
					
					if( !in_array($basename, $excluded_dirs) ){
						
						$default_headers = array(
							'name'     => 'Name',
							'menus'    => 'Menus',
							'demo_url' => 'Demo URL',
							'home_page'=> 'Home Page',
							'blog_page'=> 'Blog Page',
							'message'  => 'Message',
						);
						
						if( file_exists($data_dir.'sample_data.ini') ){
							$sample_details = get_file_data( $data_dir.'sample_data.ini', $default_headers, 'sample_data' );
						}else{
							$sample_details = array();
						}
						
						// Name
						$sample_name = ( !empty($sample_details['name']) ) ? $sample_details['name'] : ucwords(str_replace('_', ' ', $basename));
						
						// Menus
						$sample_menu = array(); // Define default array
						
						if( !empty($sample_details['menus']) ){
							$menus_raw = array_filter(explode('|',$sample_details['menus']));
							
							$menus_array = array();
							if( !empty($menus_raw) && is_array($menus_raw) ){
								foreach( $menus_raw as $menus_raw_item ){
									
									$menus_raw_item = array_filter(explode(':',$menus_raw_item, 2));
									if( count($menus_raw_item) == 2 ){
										$menus_array[$menus_raw_item[0]] = $menus_raw_item[1];
									}
								}
							}
							if( !empty($menus_array) ){
								$sample_menu = $menus_array;
							}
						}
						
						$cardealer_helper_sample_datas[$basename] = array(
							'id'         => $basename,
							'name'       => $sample_name,
							'menus'      => $sample_menu,
							'demo_url'   => @$sample_details['demo_url'],
							'home_page'  => @$sample_details['home_page'],
							'blog_page'  => @$sample_details['blog_page'],
							'message'    => @$sample_details['message'],
							'data_dir'   => $data_dir,
							'data_url'   => $data_url,
							'parent_dir' => $dirname,
						);
					}
				}
			}
		}
		
		// Set Sample Data Transient
		set_transient( "cardealer_helper_sample_datas", $cardealer_helper_sample_datas, 3600 );
		
		return $cardealer_helper_sample_datas;
	}else{
		return $cardealer_helper_sample_datas_transient;
	}
}

add_action( 'wp_ajax_theme_import_sample', 'cdhl_theme_import_sample' );
function cdhl_theme_import_sample(){
	
	// First check the nonce, if it fails the function will break	
	if ( ! wp_verify_nonce( $_REQUEST['nonce'], "sample_data_security" ) ) {
		echo json_encode( array(
			'status' => esc_html__( 'Invalid sample data security credential. Please reload the page and try again.', 'cardealer-helper' ),
			'action' => ''
		) );

		die();
	}
	ob_start();
	// Nonce is checked, get the posted data and process further
	$sample_id = isset($_REQUEST['sample_id']) ? $_REQUEST['sample_id'] : '';
	
	if( empty($sample_id) ){
		$ajax_data = array(
			'status'      => false,
			'message'     => esc_html__('Invalid Sample.','cardealer-helper'),
			'message_type'=> 'warning',
		);
	}else{
		global $wpdb;
		
		if ( current_user_can( 'manage_options' ) ) {
			
			if ( !defined('WP_LOAD_IMPORTERS') ) define('WP_LOAD_IMPORTERS', true); // we are loading importers

			if ( ! class_exists( 'WP_Importer' ) ) { // if main importer class doesn't exist
				$wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
				include $wp_importer;
			}
			
			$importer_path = trailingslashit(CDHL_PATH) . 'includes/importer/importer.php';
			if( file_exists( $importer_path ) ){
				require_once($importer_path);
			}
			
			if ( class_exists( 'WP_Importer' ) && class_exists( 'Cardealer_Helper_WP_Import' ) ) { // check for main import class and wp import class
				$cardealer_helper_sample_datas = cdhl_theme_sample_datas();
				$sample_params =  $cardealer_helper_sample_datas[$sample_id];
				// Prepapre Data Files
				$sample_data_main_data_file     = $sample_params['data_dir'].'sample_data.xml';
				$sample_data_redux_options_file = $sample_params['data_dir'].'theme_options.json';
				$sample_data_widget_file        = $sample_params['data_dir'].'widget_data.json';
				$sample_data_rev_path           = untrailingslashit($sample_params['data_dir']).'/revsliders/';
				
				/******************************************
				 * Import Main Data
				 ******************************************/
				// Import Data
				if( file_exists($sample_data_main_data_file) ){
					$importer = new Cardealer_Helper_WP_Import();
					
					// Import Posts, Pages, Portfolio Content, FAQ, Images, Menus
					$importer->fetch_attachments = true;
					
					$stat = $importer->import($sample_data_main_data_file);
					
					flush_rewrite_rules();
				
					// Import Menus
					// Set imported menus to registered theme locations
					$locations = get_theme_mod( 'nav_menu_locations' ); // registered menu locations in theme
					$registered_menus = wp_get_nav_menus(); // registered menus
					
					// Assign Menu Name to Registered menus as array keys
					$registered_menus_new = array();
					foreach( $registered_menus as $registered_menu ){
						$registered_menus_new[$registered_menu->name] = $registered_menu;
					}
					
					// Assgin Menus to provided locations
					if( !empty($sample_params['menus']) && is_array($sample_params['menus']) ){
						foreach( $sample_params['menus'] as $menu_loc => $menu_nm ){
							$reg_menu_data = $registered_menus_new[$menu_nm];
							$locations[$menu_loc] = $reg_menu_data->term_id;
						}
					}
					
					set_theme_mod( 'nav_menu_locations', $locations ); // set menus to locations
				}
				WP_Filesystem();
				global $wp_filesystem;
				/******************************************
				 * Import Theme Options
				 ******************************************/
				if( file_exists($sample_data_redux_options_file) ){
					$redux_options_json = $wp_filesystem->get_contents( $sample_data_redux_options_file );
					$redux_options = json_decode( $redux_options_json, true );
					
					global $cardealer_helper_array_replace_data;
					$cardealer_helper_array_replace_data['old'] = $sample_params['demo_url'];
					$cardealer_helper_array_replace_data['new'] = home_url( '/' );
					$redux_options = array_map("cdhl_replace_array", $redux_options);
					
					update_option( 'car-dealer-options', $redux_options );
				}
				
				/******************************************
				 * Import Widget Data
				 ******************************************/
				if( file_exists( $sample_data_widget_file ) ){
					if( !function_exists('cdhl_import_widget_data') ){ 
						$widget_import = trailingslashit(CDHL_PATH) . 'includes/lib/widget-importer-exporter/widget-import.php';
						if( file_exists( $importer_path ) ){
							include($widget_import);
						}
					}
					$widget_data_json = $wp_filesystem->get_contents( $sample_data_widget_file );
					$widget_data = json_decode( $widget_data_json );
					$cardealer_helper_widget_import_results = cdhl_import_widget_data( $widget_data );
				}
				
				/******************************************
				 * Import Revolution Sliders
				******************************************/
				// Check if "revsliders" folder exists
				if( file_exists( $sample_data_rev_path ) && is_dir( $sample_data_rev_path ) ){ 
					$sample_data_rev_sliders_path = cdhl_pgscore_get_file_list( 'zip', $sample_data_rev_path );
					if( is_array($sample_data_rev_sliders_path) && !empty($sample_data_rev_sliders_path) && class_exists('UniteFunctionsRev') ){
						$cardealer_helper_revslider = new RevSlider();
						foreach( $sample_data_rev_sliders_path as $sample_data_rev_slider_path ) {
							ob_start();
							$cardealer_helper_revslider->importSliderFromPost(true, false, $sample_data_rev_slider_path);
							ob_clean();
							ob_end_clean();
						}
					}
				}
				
				/******************************************
				 * Set Default Pages
				 ******************************************/
				// Home Page
				update_option('show_on_front', 'page');
				if( isset($sample_params['home_page']) && !empty(trim($sample_params['home_page'])) ){
					$home_page = get_page_by_title( $sample_params['home_page'] );
					if( isset($home_page) && $home_page->ID ) {
						update_option('page_on_front', $home_page->ID); // Front Page
					}
				}
				// Blog Page
				if( isset($sample_params['blog_page']) && !empty(trim($sample_params['blog_page'])) ){
					$blog_page = get_page_by_title( $sample_params['blog_page'] );
					if( isset($blog_page) && $blog_page->ID ) {
						update_option('page_for_posts', $blog_page->ID); // Posts Page
					}
				}
				$ajax_data = array(
					'status'      => true,			
				);
			}else{
				$ajax_data = array(
					'status'      => false,
					'message'     => esc_html__('Import class not found.', 'cardealer-helper'),
					'message_type'=> 'warning',
				);
			}
		}else{
			$ajax_data = array(
				'status'      => false,
				'message'     => esc_html__('You are not allowed to perform this action.','cardealer-helper'),
				'message_type'=> 'warning',
			);
		}
	}
	ob_get_clean();	
    die();
}

function cdhl_replace_array($n){
	global $cardealer_helper_array_replace_data;
	
	if( is_array($n) ){
		return array_map("cdhl_replace_array", $n);
	}else{
		if( !empty($cardealer_helper_array_replace_data) && is_array($cardealer_helper_array_replace_data) && isset($cardealer_helper_array_replace_data['old'])&& isset($cardealer_helper_array_replace_data['new']) ){
			if (strpos($n, $cardealer_helper_array_replace_data['old']) !== false) {
				return str_replace($cardealer_helper_array_replace_data['old'],$cardealer_helper_array_replace_data['new'],$n);
			}else{
				return $n;
			}
		}else{
			return $n;
		}
	}
    return $n;
}

add_action( 'wp_update_nav_menu_item', 'cdhl_import_custom_menu_metafields', 10, 3 );
function cdhl_import_custom_menu_metafields( $menu_id, $menu_item_db_id, $args ){
	$cardealer_helper_megamenu_data['cd_megamenu_enable'] = 1;
	update_term_meta($menu_id, '_cd_megamenu_settings', $cardealer_helper_megamenu_data);
	
	$custom_fields = array(
		'disable_link',
		'mega_menu',
		'content_type',
		'menu_width',
		'column_count',
		'menu_alignment',
	);
	
	foreach( $custom_fields as $custom_field ){
		if( !empty($args[$custom_field]) ){
			update_post_meta( $menu_item_db_id, $custom_field, $args[$custom_field] );
		}
	}
}

function cdhl_sample_import_templates() {
	include_once trailingslashit(CDHL_PATH) . "includes/sample_data/templates/sample-import-alert.php";
}
add_action( "admin_footer", "cdhl_sample_import_templates" );

function cdhl_sample_data_requirements(){
	return apply_filters( 'cdhl_sample_data_requirements', array(
		'memory-limit'      => esc_html__( 'Memory Limit: 512mb', 'cardealer-helper' ),
		'max-execution-time'=> esc_html__( 'Max Execution Time: 600 Seconds', 'cardealer-helper' ),
	) );
}

function cdhl_sample_data_required_plugins_list(){	
	
	$cardealer_helper_tgmpa_plugins_data_func = "cardealer_tgmpa_plugins_data";
	$required_plugins_list = array();
	
	if( function_exists($cardealer_helper_tgmpa_plugins_data_func) ){
		$cardealer_helper_tgmpa_plugins_data = $cardealer_helper_tgmpa_plugins_data_func();
		
		$cardealer_helper_tgmpa_plugins_data_all = $cardealer_helper_tgmpa_plugins_data['all'];
		foreach( $cardealer_helper_tgmpa_plugins_data_all as $cardealer_helper_tgmpa_plugins_data_k => $cardealer_helper_tgmpa_plugins_data_v ){
			if( !$cardealer_helper_tgmpa_plugins_data_v['required'] ){
				unset($cardealer_helper_tgmpa_plugins_data_all[$cardealer_helper_tgmpa_plugins_data_k]);
			}
		}
		
		if( !empty($cardealer_helper_tgmpa_plugins_data_all) && is_array($cardealer_helper_tgmpa_plugins_data_all) ){
			foreach( $cardealer_helper_tgmpa_plugins_data_all as $cardealer_helper_tgmpa_plugin ){
				$required_plugins_list[] = $cardealer_helper_tgmpa_plugin['name'];
			}
		}
	}
	
	return $required_plugins_list;
}