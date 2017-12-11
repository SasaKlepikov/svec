<?php
// required functions
require_once trailingslashit(CDHL_PATH) . 'includes/theme_support/theme_functions.php'; // Car Dealer dashboard page.

add_action( 'admin_menu', 'cdhl_cardealer_menu' );
function cdhl_cardealer_menu(){	
	// Sample data(Demo) link
	if ( class_exists( 'WooCommerce' ) ) {
		$demo_link = esc_url('admin.php?page=cardealer&tab=37');
	} else {
		$demo_link = esc_url('admin.php?page=cardealer&tab=36');
	}
	add_menu_page( esc_html('Car Dealer','cardealer-helper'), esc_html('Car Dealer','cardealer-helper'), 'manage_options', 'cardealer-support', 'cdhl_get_support_data' ,CDHL_URL.'/images/menu-icon.png', 3);
	add_submenu_page( 'cardealer-support', esc_html('Support','cardealer-helper'), esc_html('Support','cardealer-helper'), 'manage_options', 'cardealer-support', 'cdhl_get_support_data');
	add_submenu_page( 'cardealer-support', esc_html('Plugins','cardealer-helper'), esc_html('Plugins','cardealer-helper'), 'manage_options', 'cardealer-plugins', 'cdhl_get_plugin_data');
	add_submenu_page( 'cardealer-support', esc_html('Install Demos','cardealer-helper'), esc_html('Install Demos','cardealer-helper'), 'manage_options', $demo_link, '');
	add_submenu_page( 'cardealer-support', esc_html('Theme Options','cardealer-helper'), esc_html('Theme Options','cardealer-helper'), 'manage_options', esc_url('admin.php?page=cardealer'), '');
	add_submenu_page( 'cardealer-support', esc_html('System Status','cardealer-helper'), esc_html('System Status','cardealer-helper'), 'manage_options', 'cardealer-system-status', 'cdhl_get_system_status');
	add_submenu_page( 'cardealer-support', esc_html('Ratings','cardealer-helper'), esc_html('Ratings','cardealer-helper'), 'manage_options', 'cardealer-ratings', 'cdhl_get_ratings_page');
}
?>