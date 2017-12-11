<?php
if (!class_exists('Redux')) {
	return;
}

/**
 * Removes the demo link and the notice of integrated demo from the redux-framework plugin
 */
if ( ! function_exists( 'cdhl_remove_redux_demo' ) ) {
	function cdhl_remove_redux_demo() {
		// Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
		if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
			remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::instance(), 'plugin_metalinks' ), null, 2 );

			// Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
			remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
		}
	}
}
add_action( 'redux/loaded', 'cdhl_remove_redux_demo' );

require_once trailingslashit(CDHL_PATH) . 'includes/redux/redux-options.php';           // Redux Core & Options
require_once trailingslashit(CDHL_PATH) . 'includes/redux/extensions.php';// Load Redux modified fields

add_action( 'admin_bar_menu', 'cdhl_toolbar_theme_options_link', 999 );
function cdhl_toolbar_theme_options_link( $wp_admin_bar ) {
	$args = array(
		'id'    => 'cardealer',
		'title' => '<span class="cd_toolbar_btn"><img alt="cd_toolbar" src="'.esc_url(CDHL_URL).'/images/menu-icon.png"></span>Theme Options',
		'meta'  => array(
			'class' => 'wp-admin-bar-cardealer-link',
		),
	);
	$wp_admin_bar->add_node( $args );
}

/** remove redux menu under the tools **/
add_action( 'admin_menu', 'cdhl_remove_redux_menu',12 );
function cdhl_remove_redux_menu() {
	remove_submenu_page('tools.php','redux-about');
}
// Hide advertisement in Redux Options.
add_filter( "redux/".'car-dealer-options'."/aURL_filter", '__return_true' );