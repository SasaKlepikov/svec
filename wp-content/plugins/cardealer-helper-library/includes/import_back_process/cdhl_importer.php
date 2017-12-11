<?php
/**
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CDHL Importer Class.
 */
 
require_once( trailingslashit(CDHL_PATH) . 'includes/import_back_process/helper-classes/class-cdhl-back-logger.php' ); // LOGGER TRAIT
 
class CDHL_Importer{
	public static $db_version = CDHL_VERSION;
	public static $notices = [];
	
	// USING LOGGER TRAIT FOR LOGGIN PROCESS
	use CDHL_Back_Logger;
	/** @var array DB updates and callbacks that need to be run per version */
	private static $processing_functions = array(
		'1.0.3' => array(
			'check_import_rules',
		)
	);
	
	/** @var object Background update class */
	private static $background_importer;
	
	public function __construct() {
		include_once( trailingslashit(CDHL_PATH) . 'includes/import_back_process/helper-classes/class-cdhl-background-processor.php' );
		self::$background_importer = new CDHL_Background_Processor();		
		$update_queued      = false; 
		if(isset($_POST['csv']) && !empty($_POST['csv'])){ 
			foreach ( self::cdhl_get_process_callbacks() as $version => $update_callbacks ) {
				foreach ( $update_callbacks as $update_callback ) { 
					CDHL_Importer::log(
						sprintf( esc_html__('Queuing %s - %s', 'cardealer-helper'), $version, $update_callback ),
						$version,
						'INFO'
					);
					self::$background_importer->push_to_queue( $update_callback );
					$update_queued = true;
				}
			}
			if ( $update_queued ) { 
				self::$background_importer->save()->dispatch();
			}
		}
	}
	
	/**
	 * Get list of DB update callbacks.
	 * @return array
	 */
	public static function cdhl_get_process_callbacks() {
		return self::$processing_functions;
	}
}

new CDHL_Importer;