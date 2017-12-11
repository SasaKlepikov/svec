<?php
add_action( 'wp_enqueue_scripts', 'cardealer_child_enqueue_styles', 11 );
function cardealer_child_enqueue_styles() {
	wp_enqueue_style( 'cardealer-child', get_stylesheet_directory_uri() . '/style.css' );
}

/* Place Your Code Below Here */