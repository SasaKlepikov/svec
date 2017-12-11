<?php
/**
 * Plugin Name:       Car Dealer - Helper Library
 * Plugin URI:        http://www.potenzaglobalsolutions.com/
 * Description:       This plugin contains important functions and features for "Car Dealer" theme.
 * Version:           1.0.4
 * Author:            Potenza Global Solutions
 * Author URI:        http://www.potenzaglobalsolutions.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cardealer-helper
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if( ! defined( 'CDHL_PATH' ) ) define( 'CDHL_PATH', plugin_dir_path( __FILE__ ) );
if( ! defined( 'CDHL_URL' ) ) define( 'CDHL_URL', plugin_dir_url( __FILE__ ) );
if( ! defined( 'CDHL_VER_LOG' ) ) define( 'CDHL_VER_LOG', str_replace('/', '\\', WP_CONTENT_DIR).'\uploads\cardealer-helper\update-logs\\' );
if( ! defined( 'CDHL_LOG' ) ) define( 'CDHL_LOG', str_replace('/', '\\', WP_CONTENT_DIR).'\uploads\cardealer-helper\back-process-logs\\' );

if( ! defined( 'CDHL_THEME_OPTIONS_NAME' ) ) define( 'CDHL_THEME_OPTIONS_NAME', 'car-dealer-options' );
if( ! defined( 'CDHL_VERSION' ) ) define( 'CDHL_VERSION', '1.0.4' );

global $cdhl_globals;
$cdhl_globals = array();


// Plugin activation/deactivation hooks
register_activation_hook( __FILE__, 'cdhl_activate' );
register_deactivation_hook( __FILE__, 'cdhl_deactivate' );
add_action( 'init', 'cdhl_helper_theme_functions_load_textdomain',0 );
/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
function cdhl_helper_theme_functions_load_textdomain() {
	load_plugin_textdomain( 'cardealer-helper', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}
/**
 * The code that runs during plugin activation.
 */
function cdhl_activate() { 
	
	// Display admin notice if Visual Composer is not activated.
	add_action( 'admin_notices', 'cdhl_is_vc_active' );
	add_action( 'admin_notices', 'cdhl_plugin_active_notices' );
    
    /**
    * Add custom table when plugin active for geo fencing. 
    */ 
    global $wpdb;
    $table_name = $wpdb->prefix.'geo_fencing';
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
         //table not in database. Create new table
        $charset_collate = $wpdb->get_charset_collate();
         $sql = "CREATE TABLE $table_name ( 
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY , 
                post_id INT NOT NULL ,             
                radius DOUBLE NOT NULL , 
                lat DOUBLE NOT NULL , 
                lng DOUBLE NOT NULL 
            ) $charset_collate ENGINE = MYISAM";     
         require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
         dbDelta( $sql );
    }
	
	// For Version From 1.0.3
	$default_version = get_option( 'cdhl_version');
	if( ( $default_version !== false ) && ( version_compare( '0.0.0', $default_version, '=' ) === true ) ){
		update_option( 'cdhl_version', CDHL_VERSION );
		update_option('cdhl_version_status', 'up-to-date');
	}

	// Create log files
	$files = cdhl_get_log_files();
	foreach ( $files as $file ) {
		if ( wp_mkdir_p( $file['base'] ) && ! file_exists( trailingslashit( $file['base'] ) . $file['file'] ) ) {
			if ( $file_handle = @fopen( trailingslashit( $file['base'] ) . $file['file'], 'w' ) ) {
				fwrite( $file_handle, $file['content'] );
				fclose( $file_handle );
			}	
		}
	}
}

function cdhl_get_log_files(){
	$upload_dir      = wp_upload_dir();
	return array(
			array(
				'base' 		=> $upload_dir['basedir'] . '/cardealer-helper/back-process-logs',
				'file' 		=> 'index.html',
				'content' 	=> '',
			),
			array(
				'base' 		=> $upload_dir['basedir'] . '/cardealer-helper/back-process-logs',
				'file' 		=> '.htaccess',
				'content' 	=> 'deny from all',
			),
			array(
				'base' 		=> $upload_dir['basedir'] . '/cardealer-helper/back-process-logs/import_logs',
				'file' 		=> 'index.html',
				'content' 	=> '',
			),
			array(
				'base' 		=> $upload_dir['basedir'] . '/cardealer-helper/back-process-logs/import_logs',
				'file' 		=> '.htaccess',
				'content' 	=> 'deny from all',
			)
		);
}

/**
 * The code that runs during plugin deactivation.
 */
function cdhl_deactivate() {
	// TODO: Add settings for plugin deactivation
}

// Display admin notice if Visual Composer is not activated.
add_action( 'admin_notices', 'cdhl_is_vc_active' );
add_action( 'admin_notices', 'cdhl_plugin_active_notices' );

// Display admin notice if Visual Composer is not activated.
function cdhl_is_vc_active() {
}

// Display admin notice if required plugins are not active
function cdhl_plugin_active_notices() {
	
	$plugins_requried = array(
		'advanced-custom-fields-pro/acf.php' => esc_html__('Advanced Custom Fields PRO', 'cardealer-helper'),
		'js_composer/js_composer.php'        => esc_html__('WPBakery Visual Composer', 'cardealer-helper'),
	);
	
	$plugins_inactive = array();
	
	// Check required plugin active status
	foreach( $plugins_requried as $plugin_requried => $plugin_requried_name ) {
		
		if ( ! is_plugin_active( $plugin_requried ) ) {
			$plugins_inactive[] = $plugin_requried_name;
		}
	}
	
	if( !empty( $plugins_inactive ) && is_array( $plugins_inactive ) ){
		
		$plugins_inactive_str = implode(', ', $plugins_inactive);
		
		if( count($plugins_inactive) > 1 ){
			$message = esc_html__( 'Below required plugins are not installed or activated. Please install/activate to enable feature/functionality.', 'cardealer-helper' );
		}else{
			$message = esc_html__( 'Below required plugin is not installed or activated. Please install/activate to enable feature/functionality.', 'cardealer-helper' );
		}
		?>
		<div class="notice notice-error">
			<p><?php echo $message.'<br><strong>'.$plugins_inactive_str.'</strong>';?></p>
		</div>
		<?php
	}
}

require_once trailingslashit(CDHL_PATH) . 'includes/helper_functions.php';   // Helper Functions
require_once trailingslashit(CDHL_PATH) . 'includes/cpt.php';                // CPTs
require_once trailingslashit(CDHL_PATH) . 'includes/cpts/functions/cpt_functions.php'; // CPTs Functions
require_once trailingslashit(CDHL_PATH) . 'includes/acf/acf-init.php';       // ACF
require_once trailingslashit(CDHL_PATH) . 'includes/sample_data/sample_data.php'; // Sample Data
require_once trailingslashit(CDHL_PATH) . 'includes/widgets.php';            // Widgets
require_once trailingslashit(CDHL_PATH) . 'includes/mailchimp.php';          // mailchimp

/* Only car details pages */
require_once trailingslashit(CDHL_PATH) . 'includes/dealer_forms/common/cardealer_mail_functions.php';
require_once trailingslashit(CDHL_PATH) . 'includes/dealer_forms/inquiry.php';          // inquiry post type [ CarDetail Page ]
require_once trailingslashit(CDHL_PATH) . 'includes/dealer_forms/schedule_test_drive.php'; // Schedule Test Drive Form [ CarDetail Page ]
require_once trailingslashit(CDHL_PATH) . 'includes/dealer_forms/email_to_friend.php'; // Email to Friend Form [ CarDetail Page ]
require_once trailingslashit(CDHL_PATH) . 'includes/dealer_forms/financial_form.php'; // Financial Form  [ CarDetail Page ]
require_once trailingslashit(CDHL_PATH) . 'includes/dealer_forms/make_an_offer.php'; // Make An Offer Form  [ CarDetail Page ]

require_once trailingslashit(CDHL_PATH) . 'includes/coming_soon.php'; // Notify Mail Form  [ Comming Soon Page ]
require_once trailingslashit(CDHL_PATH) . 'includes/compare/compare.php'; // Car Compare Functionalities
require_once trailingslashit(CDHL_PATH) . 'includes/scripts_and_styles.php'; // CSS & Javascript
require_once trailingslashit(CDHL_PATH) . 'includes/version_update/version.php'; // Version Update

add_action( 'init', 'cdhl_include_admin_files', 9 );
function cdhl_include_admin_files(){
	global $pagenow;
	if( ( is_admin() && $pagenow == 'index.php' ) || $pagenow == 'admin-ajax.php' ){ 
		require_once trailingslashit(CDHL_PATH) . 'includes/dashboard-widgets/lead_forms.php'; // Dashboard widgets Lead Form
		require_once trailingslashit(CDHL_PATH) . 'includes/dashboard-widgets/google_analytics.php'; // Dashboard widgets Google Analytics
	}
	
	if( is_admin()){
		require_once trailingslashit(CDHL_PATH) . 'includes/importer/importer.php';  // Importer
		require_once trailingslashit(CDHL_PATH) . 'includes/option_page/cardealer_option_pages.php'; // Car Dealer Option Pages		
		require_once trailingslashit(CDHL_PATH) . 'includes/import_csv_xml/cars_csv_import.php'; //
		require_once trailingslashit(CDHL_PATH) . 'includes/import_form_vin/cars_vin_import.php'; //
		require_once trailingslashit(CDHL_PATH) . 'includes/theme_support/theme_includes.php'; // Car Dealer Theme Page
		require_once trailingslashit(CDHL_PATH) . 'includes/export/export.php'; // Export Cars
		require_once trailingslashit(CDHL_PATH) . 'includes/export/export_log/export_log.php'; // Export Log List
		require_once trailingslashit(CDHL_PATH) . 'includes/export/export_log/car_export_list.php'; // Cars Export Log List
		require_once trailingslashit(CDHL_PATH) . 'includes/import_log/import_log.php'; // Import Log List	
	}
	
	require_once trailingslashit(CDHL_PATH) . 'includes/redux/redux-init.php';   // Redux
}

 
add_action( 'init', 'cdhl_inc_files', 9 );
function cdhl_inc_files(){
	require_once trailingslashit(CDHL_PATH) . 'includes/vc/vc.php';  // Visual Composer
	require_once trailingslashit(CDHL_PATH) . 'includes/shortcode.php'; // Shortcodes*/
	require_once trailingslashit(CDHL_PATH) . 'includes/custom_shortcodes.php'; // Shortcodes*/
}

function cdhl_do_featured(){
    $post_id = $_POST['post_id'];
    $feature = $_POST['feature'];
    if($feature == 1){
        update_post_meta($post_id,'featured',0);
    } else {
        update_post_meta($post_id,'featured',1);
    }
}
add_action("wp_ajax_cdhl_do_featured", "cdhl_do_featured");
add_action("wp_ajax_nopriv_cdhl_do_featured", "cdhl_do_featured");


add_action( 'admin_init', 'cdhl_pdf_sample_templates', 21 );
function cdhl_pdf_sample_templates(){
	global $pagenow;
	
	if($pagenow == 'plugins.php' || $pagenow == 'themes.php'){
	
		require_once trailingslashit(CDHL_PATH) . 'includes/pdf_generator/samples/samples.php';	
		/* Checks to see if the acf pro plugin is activated  */	
		$is_pdf_sample_templates = get_option( 'is_pdf_sample_templates' ); 
		if ( cdhl_is_plugin_active('advanced-custom-fields-pro/acf.php') && $is_pdf_sample_templates != 'yes' )  {
			
			$field = get_field_object('field_589ac266afdf9', 'options');
			if( !have_rows('html_templates') ) {
				
				for( $i=1; $i<=3; $i++ ) {
					if( isset($field['sub_fields']) && is_array($field['sub_fields']) ){
						foreach( $field['sub_fields'] as $sub_field ){
							if($i == 1){                            
								$pdf_sample = cardealer_pdf_sample_1();                            
						   }elseif($i == 2){                            
								$pdf_sample = cardealer_pdf_sample_2();                            
							}elseif($i == 3){                            
								$pdf_sample = cardealer_pdf_sample_3();                            
							}
							if( $sub_field['name'] = 'template_title' ){
								$stat_title   = update_sub_field( array($field['key'], $i, $sub_field['key']), 'PDF Template ' . $i, 'options' );
							}
							if( $sub_field['name'] = 'template_content' ){
								$stat_content = update_sub_field( array($field['key'], $i, 'template_content'), $pdf_sample, 'options' );
							}
						}					
					}
				}
				update_option('options_html_templates',3);
				update_option('_options_html_templates','field_589ac266afdf9');
				update_option( 'is_pdf_sample_templates', 'yes' );         
			}		
		}
	}
}

// Allow Json key file to upload
function cdhl_mime_type($mime_types){
	$mime_types['json'] = 'application/json'; //Adding svg extension
	return $mime_types;
}
add_filter('upload_mimes', 'cdhl_mime_type', 1, 1);