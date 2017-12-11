<?php
// Enable shortcodes in widgets
add_filter('widget_text', 'do_shortcode');

/*
 * Shortcodes Loader
 */
cdhl_shortcodes_loader();
function cdhl_shortcodes_loader(){
	if(cdhl_is_plugin_active('js_composer/js_composer.php')){
		$shortcodes_path = trailingslashit(CDHL_PATH) . 'includes/shortcodes/';
		if ( is_dir( $shortcodes_path ) ) {
			$shortcodes = (function_exists('cardealer_helper_get_file_list'))?cardealer_helper_get_file_list("php", $shortcodes_path):'';
			if( !empty($shortcodes) ){
				foreach( $shortcodes as $shortcode ) {
					include($shortcode);
				}
			}
		}
	}
}