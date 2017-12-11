<?php
/******************************************************************************
 * 
 * Shortcode : image_sliderl
 * 
 ******************************************************************************/
add_shortcode( 'image_sliderl', 'cdhl_image_sliderl_shortcode' );
function cdhl_image_sliderl_shortcode($atts) {    
    extract( shortcode_atts(  array(                
        'data_md_items'    => 1,
        'data_sm_items'    => 1,        
        'data_xs_items'    => 1,
        'data_xx_items'    => 1,
        'data_space'       => 0,
        'car_slider_opt'   => '',
	   ), $atts ) 
	);
	extract( $atts );
	
	if(!empty($custom_title)){
		$title = $custom_title;
	}

    ob_start();
    $car_slider_opt = explode(',',$car_slider_opt);
	$arrow = "false"; $autoplay = "false"; $dots = "false"; $data_loop = "false";
	foreach( $car_slider_opt as $option ) {
		if( $option == 'Autoplay' )
			$autoplay = "true";
		else if( $option == 'Loop' )
			$data_loop = "true";
		else if( $option == 'Navigation Dots' )
			$dots = "true";
		else if( $option == 'Navigation Arrow' )
			$arrow = "true";
	} 
    
    
    
    ?>     
    <div class='owl-carousel' 
    data-nav-arrow='<?php esc_attr_e($arrow)?>' 
    data-nav-dots='<?php esc_attr_e($dots)?>'     
    data-md-items='<?php esc_attr_e($data_md_items)?>' 
    data-sm-items='<?php esc_attr_e($data_sm_items)?>' 
    data-xs-items='<?php esc_attr_e($data_xs_items)?>' 
    data-xx-items='<?php esc_attr_e($data_xx_items)?>' 
    data-space='<?php esc_attr_e($data_space)?>'
    data-autoplay='<?php esc_attr_e($autoplay)?>'
    data-loop='<?php esc_attr_e($data_loop)?>'>
        <?php  
        $images_url = (!empty($atts['slider_images']) ? $atts['slider_images'] : '');
        $imagesurl = explode(",", $images_url);
        // foreach in array
        foreach ($imagesurl as $image_id) {
            $img_url = wp_get_attachment_url($image_id, "full");                
            $img_alt = get_post_meta( $image_id,'_wp_attachment_image_alt',true );
            ?>
            <div class="item">
                <img class="center-block" src="<?php echo esc_html($img_url)?>" alt="<?php echo esc_html($img_alt)?>">
            </div>
            <?php
        }        
        ?>      
    </div>      
    <?php
    return ob_get_clean();
}

/******************************************************************************
 * 
 * Visual Composer Integration
 * 
 ******************************************************************************/
function cdhl_image_sliderl_shortcode_integrateWithVC() {
	if ( function_exists( 'vc_map' ) ) {
	   vc_map( array(
		  'name' => esc_html__( 'Potenza Image Slider', 'cardealer-helper' ),
		  'base' => 'image_sliderl',
		  'class' => '',
          'icon'  => cardealer_vc_shortcode_icon( 'image_sliderl' ),          
		  'category' => esc_html__('Potenza', 'cardealer-helper'),
		  'params' => array(			
			
			array(
                'type' => 'attach_images',
                'heading' => esc_html__( 'Images', 'cardealer-helper' ),
                'param_name' => 'slider_images',                
                'description' => esc_html__( 'Select images.', 'cardealer-helper' ),
            ),                     
            array(
                'type' => 'checkbox',
                'heading' => esc_html__( 'Slider style option', 'cardealer-helper' ),
                'param_name' => 'car_slider_opt',
                'value' => array(
								'Navigation Arrow' => 'Navigation Arrow',
								'Navigation Dots'  => 'Navigation Dots',
								'Autoplay'  => 'Autoplay',                                        
								'Loop'  => 'Loop',
                ),                
            ),				
		  )
	   ) );
	}
}
add_action( 'vc_before_init', 'cdhl_image_sliderl_shortcode_integrateWithVC' );
?>