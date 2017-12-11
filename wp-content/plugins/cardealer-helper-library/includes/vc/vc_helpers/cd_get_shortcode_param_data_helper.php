<?php
function cdhl_get_shortcode_param_data($shortcode = ''){
	$options = array();
	if( empty($shortcode) ){
		return $options;
	}
	$images_dir = CDHL_VC_DIR.'/vc_images/options/'.$shortcode.'/';
	$images_url = CDHL_VC_URL.'/vc_images/options/'.$shortcode.'/';
	
	if(is_dir($images_dir)) {		
		$images = cdhl_pgscore_get_file_list( 'png', $images_dir );
		if( !empty($images) ){
			foreach( $images as $image ) {
				$image_data = pathinfo($image);
				$options[$image_data['filename']] = $images_url.'/'.$image_data['basename'];
			}
		}
	}
	return $options;
}