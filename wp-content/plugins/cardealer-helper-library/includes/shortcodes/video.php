<?php
/******************************************************************************
 * 
 * Shortcode : cd_video
 * 
 ******************************************************************************/
add_shortcode( 'cd_video', 'cdhl_video_shortcode' );
function cdhl_video_shortcode($atts) {
	$atts = shortcode_atts( array(
		'video_link'           => '',
		'video_img'=> '',
		'video_position' => 'Default',
		'video_type'=>'youtube',
		'css'                   => '',
		'element_id'            => uniqid('cd_video_'),
	), $atts );
	extract( $atts );
	$css = $atts['css'];
	$custom_class = vc_shortcode_custom_css_class( $css, ' ' );
	$element_classes = array(
		'play-video',
		'popup-gallery',
		$custom_class,
	);
	$element_classes = implode( ' ', array_filter( array_unique( $element_classes ) ) );
	$video_img_arr = (function_exists('cardealer_get_attachment_detail'))?cardealer_get_attachment_detail($atts['video_img']):'';
	$video_type = $atts['video_type'];
	ob_start();
	?>
	<div id="<?php echo esc_attr($element_id);?>" class="<?php echo esc_attr($element_classes);?> <?php esc_attr_e($video_position)?>">
						<div class="video-info text-center">
							<img class="img-responsive center-block" src="<?php echo esc_url(wp_get_attachment_url($atts['video_img'], 'full'));?>" alt="<?php echo !empty($video_img_arr['alt'])? $video_img_arr['alt']: esc_html__('Video Img', 'cardealer-helper')?>">
							<a class="popup-<?php echo ($video_type == 'youtube')? 'youtube' : esc_attr($video_type); ?>" href="<?php echo esc_url($atts['video_link']);?>"><i class="fa fa-play"></i> </a>
						</div>
	</div>
	<?php
	/* Restore original Post Data */
	wp_reset_postdata();
	return ob_get_clean();
}
/******************************************************************************
 * 
 * Visual Composer Integration
 * 
 ******************************************************************************/
function cdhl_video_shortcode_vc_map() {
	$base = array(
		array(
				'type'      => 'dropdown',
				'heading'   => esc_html__( 'Video Type', 'cardealer-helper' ),
				'param_name'=> 'video_type',
				'value'     => array(
					esc_html__( 'Youtube', 'cardealer-helper' ) => 'youtube',
					esc_html__( 'Vimeo', 'cardealer-helper' ) => 'vimeo',
				),
				'description' => esc_html__( 'Select any one video type from given dropdown.', 'cardealer-helper' ),
			),
		array(
				"type"        => "textfield",
				"class"       => "",
				'heading'    => esc_html__( 'Video URL', 'cardealer-helper' ),
				'param_name' => 'video_link',
				'description'=> esc_html__( 'Add link of video to play.', 'cardealer-helper' )
			),
		array(
			"type"        => "attach_image",
			"class"       => "",
			"heading"     => esc_html__("Video Image", 'cardealer-helper'),
			"description" => esc_html__("Video Background Image", 'cardealer-helper'),
			"param_name"  => "video_img",
		),
		array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Video Position', 'cardealer-helper' ),
				'param_name' => 'video_position',
				'value' => array( 
					esc_html__('Default','cardealer-helper')=>'default',
					esc_html__('Top','cardealer-helper')=>'top',
			) 
	));
	// Params
	$params = array(
		"name"                    => esc_html__( "Potenza Video", 'cardealer-helper' ),
		"description"             => esc_html__( "Potenza Video block", 'cardealer-helper'),
		"base"                    => "cd_video",
		"class"                   => "cardealer_helper_element_wrapper",
		"controls"                => "full",
		"icon"                    => cardealer_vc_shortcode_icon( 'cd_video' ),
		"category"                => esc_html__('Potenza', 'cardealer-helper'),
		"show_settings_on_create" => true,
		"params"                  => array_merge($base)
	);
	if ( function_exists( 'vc_map' ) ) {
		vc_map( $params );
	}
}
add_action( 'vc_before_init', 'cdhl_video_shortcode_vc_map' );