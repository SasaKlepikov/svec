<?php
if(function_exists('acf_add_options_sub_page')){
    acf_add_options_sub_page(array(
        'page_title' 	=> 'Vehicle Brochure Generator',
        'menu_title'	=> 'Vehicle Brochure Generator',
        'parent_slug'	=> 'edit.php?post_type=cars',
        'capability'    => 'manage_options',
        'menu_slug' 	=> 'pdf_generator',
    ));
}

/// Google Analytics Menu
if(function_exists('acf_add_options_page')){
	acf_add_options_page(array(
		'page_title' 	=> 'Google Analytics',
		'menu_title'	=> 'Google Analytics Settings',
		'menu_slug' 	=> 'google-analytics-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
}
?>