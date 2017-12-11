<?php
function cdhl_get_terms($args = array(), $args2 = ''){
	$return_data = array();
	
	if( !empty($args2) ){
		$result = get_terms( $args, $args2 );
	}else{
		$args['hide_empty'] = false;
		$result = get_terms( $args );
	}
	
    if ( is_wp_error( $result ) ) {
		return $return_data;
	}
	
	if ( !is_array( $result ) || empty( $result ) ) {
		return $return_data;
	}
	
	foreach ( $result as $term_data ) {
		if ( is_object( $term_data ) && isset( $term_data->name, $term_data->term_id ) ) {
			$return_data[ $term_data->name ] = $term_data->slug;
		}
	}    
	return $return_data;
}
// Get Taxomony of Cars Posttype
function cdhl_get_cars_taxonomy() {
	$taxonomies = get_object_taxonomies('cars');
	$taxonomyArray = array();
	foreach ( $taxonomies as $taxonomy ) {		
		$tax_obj = get_taxonomy( $taxonomy );
		if($taxonomy != "car_features_options")
        $taxonomyArray[$tax_obj-> label] = $taxonomy;
	}
	return $taxonomyArray;
}