<?php
if(defined( 'ACF_DEV' ) && ACF_DEV){
	// return;
}
// Include Add-ons
function cdhl_acf_fields_loader_new(){ 
	$acf_fields_path = trailingslashit(CDHL_PATH).'/includes/acf/fields/';
	if ( is_dir( $acf_fields_path ) ) {
		require_once $acf_fields_path . 'car-data.php';
		require_once $acf_fields_path . 'car-tabs.php';
		require_once $acf_fields_path . 'faq-page.php';
		require_once $acf_fields_path . 'financial-form.php';
		require_once $acf_fields_path . 'google-analytics.php';
		require_once $acf_fields_path . 'make-an-offer.php';
		require_once $acf_fields_path . 'page-settings.php';
		require_once $acf_fields_path . 'page-sidebar.php';
		require_once $acf_fields_path . 'pdf-generator.php';
		require_once $acf_fields_path . 'post-format-audio.php';
		require_once $acf_fields_path . 'post-format-gallery.php';
		require_once $acf_fields_path . 'post-format-quote.php';
		require_once $acf_fields_path . 'post-format-video.php';
		require_once $acf_fields_path . 'promo-code.php';
		require_once $acf_fields_path . 'request-more-info.php';
		require_once $acf_fields_path . 'schedule-test-drive.php';
		require_once $acf_fields_path . 'team-details.php';
		require_once $acf_fields_path . 'team-layout-settings.php';
		require_once $acf_fields_path . 'testimonials.php';
		require_once $acf_fields_path . 'usermeta-social-profiles.php';
		require_once $acf_fields_path . 'vehicle-review-stamps.php';
	}
}
if( !defined( 'ACF_DEV' ) || (defined( 'ACF_DEV' ) && !ACF_DEV) ) {
	// 4. Hide ACF field group menu item
	add_filter( 'acf/settings/show_admin', '__return_true' );
	add_action( 'init', 'cdhl_acf_fields_loader_new',20 );
}
add_filter( 'acf/load_field/type=radio', 'cdhl_acf_load_field_page_layout' );
function cdhl_acf_load_field_page_layout( $field ) {
	// Return field without save image data in database
	$field_post = get_post($field['ID']);
	if( isset($field_post->post_type) &&  $field_post->post_type == 'acf-field' ){
		return $field;
	}
	$name = $field['name'];
	// Populate field with class
	$class = $field['wrapper']['class'];
	$classes = explode( ' ', $class);
	if( !in_array( 'acf-image-radio', $classes) ){
		return $field;
	}
	$acf_radio_imgs = trailingslashit(CDHL_URL) . 'images/radio-button-imgs';
	$cdhl_banners_path = trailingslashit(CDHL_PATH) . 'images/radio-button-imgs/'.$name.'/';
	$cdhl_banners_url  = trailingslashit(CDHL_URL) . 'images/radio-button-imgs/'.$name.'/';
	$cdhl_banners_new = array();
	if ( is_dir( $cdhl_banners_path ) ) {		
		$cdhl_banners_data = cdhl_pgscore_get_file_list( 'jpg,png', $cdhl_banners_path );
		if( !empty($cdhl_banners_data) ){
			foreach( $cdhl_banners_data as $cdhl_banner_path ) {
				$file_data = pathinfo($cdhl_banner_path);
				$opt_title = $file_data['filename'];
				$opt_title = ucfirst( str_replace( "_", " ", $opt_title ) );
				$field['choices'][$file_data['filename']] = '<img src="'.esc_url($cdhl_banners_url.basename($cdhl_banner_path)).'" alt="'.esc_attr($opt_title).'" /><span class="radio_btn_title">'.$opt_title.'</span>';
			}
		}
	}
    return $field;
}
add_filter( 'acf/load_field', 'cdhl_acf_load_field_add_field_name_class' );
function cdhl_acf_load_field_add_field_name_class( $field ) {
	$name = $field['name'];
	if( empty($field['wrapper']['class']) ){
		$field['wrapper']['class'] = 'acf_field_name-'.$name;
	}else{
		$field['wrapper']['class'] = $field['wrapper']['class'].' acf_field_name-'.$name;
	}
	return $field;
}
add_filter( 'acf/load_field/name=banner_image_bg', 'cdhl_acf_load_field_banner_image_bg' );
function cdhl_acf_load_field_banner_image_bg( $field ) {
	// Return field without save image data in database
	$field_post = get_post($field['ID']);
	if( $field_post->post_type == 'acf-field' ){
		return $field;
	}
	if( empty($field['wrapper']['class']) ){
		$field['wrapper']['class'] = 'acf_field_name-banner_image_bg';
	}
	$banner_images = cdhl_banner_images();
	foreach( $banner_images as $banner_image ){
		$field['choices'][$banner_image['img']] = '<img src="'.esc_url($banner_image['img']).'" alt="'.esc_attr($banner_image['alt']).'" height="75" />';
	}
	return $field;
}

function cdhl_get_promocode_options(){
    $list = array();
    $my_args = array(
        'post_type' => 'page',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => '_wp_page_template',
                'value' => 'templates/promocode.php'
            )
        )
    );
    $promo_pages = get_posts( $my_args );    
    if ( $promo_pages ) {
        foreach ( $promo_pages as $promopage ) :
            setup_postdata( $promopage ); 
            $list[get_the_permalink($promopage->ID)]=$promopage->post_title;
        endforeach;
    }
    wp_reset_postdata();
    $list['custom']='Custom'; 
    return $list;
}
add_filter('acf/load_field/name=promo_code_page', 'acf_load_promocode_field_choices');
function acf_load_promocode_field_choices( $field ) {

    $field['choices'] = array();
    $field_post = get_post($field['ID']);
	if( $field_post->post_type == 'acf-field' ){
		return $field;
	}    
    $field['choices'] = cdhl_get_promocode_options();    
    return $field;    
}