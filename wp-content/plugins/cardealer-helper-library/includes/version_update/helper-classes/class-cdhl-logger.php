<?php

trait CDHL_Logger {

	/**
	 * Log
	 *
	 * @param string $message
	 */
	public static function log( $message, $version = "", $type = "") {
		$file = CDHL_VER_LOG . "cardealer_helper_". $version . "_" . date_i18n( 'm-d-Y' ) .".txt"; 
		$open = fopen( $file, "a" ); 
		$log_txt = date_i18n( 'm-d-Y @ H:i:s' ). " " . " ". $type . " ". $message ."\n";
		$write = fputs( $open, $log_txt );
		fclose($open);
	}
}