<?php
/**
 * Car Dealer Helper Library Updates
 *
 * Functions for updating data, used by the background updater.
 * @author   PotenzaGlobalSolutions
 * 
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Execute new updates.
 */
function cdhl_update_103_version() {
	$args = array(
			'post_type' => 'cars',
			'posts_per_page' => '-1'
		);
	
	$log_query = new WP_Query($args);
	$result = array();
	$cnt = 0;
	while ($log_query->have_posts()) : $log_query->the_post();
		$final_price = 0;
		$sale_price = get_post_meta( get_the_ID(), 'sale_price', true);
		if( $sale_price ) {
			$final_price = $sale_price;
		} else {
			$regular_price = get_post_meta( get_the_ID(), 'regular_price', true);
			if( $regular_price ) $final_price = $regular_price;
		}
		update_post_meta( get_the_ID(), 'final_price', $final_price);
	endwhile;
}

/**
 * Update DB Version.
 */
function cdhl_update_103_db_version() {
	CDHL_Version::cdhl_update_db_version( '1.0.3' );
}