<?php

trait CDHL_Back_Logger {

	/**
	 * Log
	 *
	 * @param string $message
	 */
	public static function log( $message, $version = "", $type = "", $cars = false) {
		$file = CDHL_LOG . "cdhl_importer_". $version . "_" . date_i18n( 'm-d-Y' ) .".txt";
		$open = fopen( $file, "a" ); 
		$log_txt = date_i18n( 'm-d-Y @ H:i:s' ). " " . " ". $type . " ". $message ."\n";
		$write = fputs( $open, $log_txt );
		fclose($open);
	}
}