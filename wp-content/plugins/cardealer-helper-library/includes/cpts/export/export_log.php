<?php
// Add CPT
add_action( 'init', 'cdhl_export_log' );
function cdhl_export_log() {  
    $args = array(
        'labels' => array(
            'name' => __( 'Export Log' ),
            'singular_name' => __( 'Export Log' )
        ),
		'capability_type' => 'post',
			'capabilities' => array(
			'create_posts' => 'do_not_allow', // false < WP 4.5, credit @Ewout
		),
		'map_meta_cap' => true,
        'public' => true,
		'show_in_menu' => false,
		'show_in_nav_menus' => false,
		'publicly_queryable' => false,
		'exclude_from_search' => false,
		'has_archive' => true,
        'rewrite' => array('slug' => 'export_log'),
        'supports' => array( ),
    );    
    register_post_type( 'pgs_export_log',$args);
}
?>