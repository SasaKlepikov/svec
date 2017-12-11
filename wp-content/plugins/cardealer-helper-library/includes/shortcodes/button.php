<?php
/******************************************************************************
 * 
 * Shortcode : cd_botton
 * 
 ******************************************************************************/
add_shortcode( 'cd_button', 'cdhl_shortcode_button' );
function cdhl_shortcode_button($atts) {
	$atts = shortcode_atts( array(
		'style'           => 'default',
		'size'            => 'medium',
		'title'           => esc_html__('Click Here', 'cardealer-helper'),
		'url'             => 'url:%23|||',
		'css'             => '',
	), $atts );
	$url_vars = vc_build_link( $atts['url'] );
	$btn_attr = cdhl_vc_link_attr($url_vars);
	
	$css = $atts['css'];
	$custom_class = vc_shortcode_custom_css_class( $css, ' ' );
	$element_classes = array(
		'button',
		'pgs_btn',
		$atts['style'],
		$atts['size'],
		$custom_class,
	);
	$element_classes = implode( ' ', array_filter( array_unique( $element_classes ) ) );
	
	if( empty($atts['title']) || empty($atts['url']) ){
		return;
	}
	ob_start();
	?>
	<a <?php echo $btn_attr;?> class="<?php echo esc_attr($element_classes);?>">
		<?php echo esc_html($atts['title']);?>
	</a>
	<?php
	return ob_get_clean();	
}
/******************************************************************************
 * 
 * Visual Composer Integration
 * 
 ******************************************************************************/
 function cdhl_button_shortcode_vc_map() {
	if ( function_exists( 'vc_map' ) ) {
		global $vc_gitem_add_link_param;
		vc_map( array(
			"name"                   => esc_html__( "Potenza Button", 'cardealer-helper' ),
			"description"            => esc_html__( "Potenza Button", 'cardealer-helper'),
			"base"                   => "cd_button",
			"class"                  => "cardealer_helper_element_wrapper",
			"controls"               => "full",
			'icon'                   => cardealer_vc_shortcode_icon( 'cd_button' ),
			"category"               => esc_html__('Potenza', 'cardealer-helper'),
			"show_settings_on_create"=> true,
			"params"                 => array(
				array(
					'type'       => 'cd_radio_image',
					"heading"    => esc_html__("Style", 'cardealer-helper'),
					'param_name' => 'style',
					'options'    => cdhl_get_shortcode_param_data('cd_button'),
				),
				array(
					'type'      => 'vc_link',
					'heading'   => esc_html__( 'URL (Link)', 'cardealer-helper' ),
					'param_name'=> 'url',
					'description' => esc_html__( 'Add custom link.', 'cardealer-helper' ),
				),
				array(
					'type'      => 'dropdown',
					'heading'   => esc_html__( 'Size', 'cardealer-helper' ),
					'param_name'=> 'size',
					'value'     => array(
						esc_html__( 'Small', 'cardealer-helper' )      => 'small',
						esc_html__( 'Medium', 'cardealer-helper' )     => 'medium',
						esc_html__( 'Large', 'cardealer-helper' )      => 'large',
						esc_html__( 'Extra Small', 'cardealer-helper' )=> 'extra-small',
					),
					'save_always'     => true,
					'description' => esc_html__( 'Select size.', 'cardealer-helper' ),
				),
				array(
					"type"        => "textfield",
					"class"       => "pgs_btn_title",
					"heading"     => esc_html__("Title", 'cardealer-helper'),
					"description" => esc_html__("Enter title here", 'cardealer-helper'),
					"param_name"  => "title",
					'value'     => esc_html__( 'Title', 'cardealer-helper' ),
				),
				array(
					'type'      => 'css_editor',
					'heading'   => esc_html__( 'CSS box', 'cardealer-helper' ),
					'param_name'=> 'css',
					'group'     => esc_html__( 'Design Options', 'cardealer-helper' ),
				),
			)
		) );
	}
}
add_action( 'vc_before_init', 'cdhl_button_shortcode_vc_map' );