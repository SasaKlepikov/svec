<?php
function cdhl_widgets_classes(){
	require_once trailingslashit(CDHL_PATH) . 'includes/widgets/about.php';
	require_once trailingslashit(CDHL_PATH) . 'includes/widgets/archives.php';
	require_once trailingslashit(CDHL_PATH) . 'includes/widgets/categories.php';
	require_once trailingslashit(CDHL_PATH) . 'includes/widgets/meta.php';
	require_once trailingslashit(CDHL_PATH) . 'includes/widgets/newsletter.php';       
	require_once trailingslashit(CDHL_PATH) . 'includes/widgets/recent-posts.php';
	require_once trailingslashit(CDHL_PATH) . 'includes/widgets/tag-cloud.php';
    require_once trailingslashit(CDHL_PATH) . 'includes/widgets/financing_calculator.php';
	require_once trailingslashit(CDHL_PATH) . 'includes/widgets/inquiry.php';
    require_once trailingslashit(CDHL_PATH) . 'includes/widgets/cars_filters.php';	
	require_once trailingslashit(CDHL_PATH) . 'includes/widgets/fuel_efficiency.php'; 
    require_once trailingslashit(CDHL_PATH) . 'includes/widgets/cars_search.php';
       
}
add_action( 'plugins_loaded', 'cdhl_widgets_classes', 20 );

// register widgets
function cdhl_register_widgets() {
	register_widget( 'CarDealer_Helper_Widget_About' );
	register_widget( 'CarDealer_Helper_Widget_Archives' );
	register_widget( 'CarDealer_Helper_Widget_Categories' );
	register_widget( 'CarDealer_Helper_Widget_Meta' );
	register_widget( 'CarDealer_Helper_Widget_Newsletter' );
	register_widget( 'CarDealer_Helper_Widget_Recent_Posts' );
	register_widget( 'CarDealer_Helper_Widget_Tag_Cloud' );
    register_widget( 'CarDealer_Helper_Widget_Financing_Calculator' );
	register_widget( 'CarDealer_Helper_Widget_Inquiry' );
    register_widget( 'CarDealer_Helper_Widget_Cars_Filters' );	
	register_widget( 'CarDealer_Helper_Widget_Fuel_Efficiency' );
    register_widget( 'CarDealer_Helper_Widget_Cars_Search' );
}
add_action( 'widgets_init', 'cdhl_register_widgets' );