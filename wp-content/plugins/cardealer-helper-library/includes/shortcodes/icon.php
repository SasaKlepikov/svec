<?php
/******************************************************************************
 * 
 * Shortcode : cd_potenza_icon
 * 
 ******************************************************************************/
add_shortcode( 'cd_potenza_icon', 'cdhl_shortcode_potenza_icon' );
function cdhl_shortcode_potenza_icon($atts) {
	$atts = shortcode_atts( array(
		'icon_type'       	=> 'fontawesome',
		'icon_fontawesome'	=> 'fa fa-info-circle',
		'icon_openiconic' 	=> 'vc-oi vc-oi-dial',
		'icon_typicons'   	=> 'typcn typcn-adjust-brightness',
		'icon_entypo'     	=> 'entypo-icon entypo-icon-note',
		'icon_linecons'   	=> 'vc_li vc_li-heart',
		'icon_monosocial' 	=> 'vc-mono vc-mono-fivehundredpx',
        'icon_flaticon'   	=> 'glyph-icon flaticon-air-conditioning',
		'icon_color'	  	=> '#ffffff',
		'icon_size'       	=> '',
		'icon_alignment'    => 'left',
		'element_id'      	=> uniqid('cd_feature_box_'),
	), $atts );
	
	extract( $atts );
	$extra_classes = [];
	
	$icon_type = $atts['icon_type'];
	$icon = $atts['icon_'.$icon_type];
	vc_icon_element_fonts_enqueue( $icon_type );
	
	$element_classes = array(
		'potenza-icon',	
		'icon-'.$atts['icon_alignment']
	);
	$element_classes = implode( ' ', array_filter( array_unique( $element_classes ) ) ); 
	if( !empty( $extra_classes) )
		$element_classes .= ' ' . implode( ' ', array_filter( array_unique( $extra_classes ) ) );
	ob_start();
   	?>
	<div id="<?php echo esc_attr($element_id);?>" class="<?php echo esc_attr($element_classes);?>" >
		 <i class="<?php echo esc_attr($icon);?>" style="color:<?php echo esc_attr($icon_color);?>; font-size:<?php echo esc_attr($icon_size);?>px; text-align:<?php echo esc_attr($icon_alignment);?>;"></i>
    </div>
	<?php	
    return ob_get_clean();	
}
/******************************************************************************
 * 
 * Visual Composer Integration
 * 
 ******************************************************************************/
function cdhl_potenza_icon_shortcode_vc_map() {
	if ( function_exists( 'vc_map' ) ) {
		$params=array(
				array(
					"type"       => "colorpicker",
					"class"      => "pgs_icon",
					"param_name" => "icon_color",
					"value"		 => "#ffffff",
					"heading"    => esc_html__("Icon Color", 'cardealer-helper'),
				),
				array(
					"type"       => "cd_number_min_max",
					"param_name" => "icon_size",
					"value"		 => "12",
					"heading"    => esc_html__("Icon Size", 'cardealer-helper'),
					'min'             => '1',
					'max'             => '9999',
				),
				array(
					"type"       => "dropdown",
					"param_name" => "icon_alignment",
					"value"		 => "left",
					"heading"    => esc_html__("Icon Alignment:", 'cardealer-helper'),
					'value' => array(
						'Left' 		=> 'left',
						'Right'  	=> 'right',
						'Center' 	=> 'center',                                        
					),          
				),
			);
		$params = array_merge(
			cdhl_iconpicker(),
			$params
		);
		vc_map( array(
			"name"                    => esc_html__( "Potenza Icon", 'cardealer-helper' ),
			"description"             => esc_html__( "Potenza Icon", 'cardealer-helper'),
			"base"                    => "cd_potenza_icon",
			"class"                   => "cardealer_helper_element_wrapper",
			"controls"                => "full",
			"icon"                    => cardealer_vc_shortcode_icon( 'cd_potenza_icon' ),
			"category"                => esc_html__('Potenza', 'cardealer-helper'),
			"show_settings_on_create" => true,
			"params"                  => $params,
		) );
	}
}
add_action( 'vc_before_init', 'cdhl_potenza_icon_shortcode_vc_map' );