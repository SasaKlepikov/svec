<?php
/******************************************************************************
 * 
 * Shortcode : cd_feature_box
 * 
 ******************************************************************************/

add_shortcode( 'cd_feature_box', 'cdhl_shortcode_feature_box' );
function cdhl_shortcode_feature_box($atts) {
	$atts = shortcode_atts( array(
		'icon_type'       => 'fontawesome',
		'icon_fontawesome'=> 'fa fa-info-circle',
		'icon_openiconic' => 'vc-oi vc-oi-dial',
		'icon_typicons'   => 'typcn typcn-adjust-brightness',
		'icon_entypo'     => 'entypo-icon entypo-icon-note',
		'icon_linecons'   => 'vc_li vc_li-heart',
		'icon_monosocial' => 'vc-mono vc-mono-fivehundredpx',
        'icon_flaticon'   => 'glyph-icon flaticon-air-conditioning',
		'hover_style'	  => false,
		'style'           => 'style-1',
		'title'           => '',
        'url'			  => '#',
		'description'     => '',
		'css'             => '',
        'border'          => 'yes',
		'element_id'      => uniqid('cd_feature_box_'),
	), $atts );
	extract( $atts );
	$extra_classes = [];
	if( $atts['style'] == 'style-1' ){
		$extra_classes[] = "round-icon";
	}
	else if($atts['style'] == 'style-2'){
		$extra_classes[] = "round-icon";
		$extra_classes[] = "left";
	}
	else if($atts['style'] == 'style-3'){
		$extra_classes[] = "round-icon";
		$extra_classes[] = "right";
	}
	else if($atts['style'] == 'style-4'){
		$extra_classes[] = "default-feature";
	}
	else if($atts['style'] == 'style-5'){
		$extra_classes[] = "left-icon";
	}
	else if($atts['style'] == 'style-6'){
		$extra_classes[] = "right-icon";
	}
	else if($atts['style'] == 'style-6'){
		$extra_classes[] = "text-right";
	}
	else if($atts['style'] == 'style-7'){
		$extra_classes[] = "round-border";
	}
	else if($atts['style'] == 'style-8'){
		$extra_classes[] = "left-align";
	}
	else if($atts['style'] == 'style-9'){
		$extra_classes[] = "right-align";
	}
	
	if($atts['hover_style'] == true)
		$extra_classes[] = 'box-hover';
	
	$icon_type = $atts['icon_type'];
	$icon = $atts['icon_'.$icon_type];
	
	vc_icon_element_fonts_enqueue( $icon_type );
	
    $url_vars = vc_build_link( $atts['url'] );
	$url_attr = cdhl_vc_link_attr($url_vars);
    
    
	$css = $atts['css'];
	$border=$atts['border'];
	$custom_class = vc_shortcode_custom_css_class( $css, ' ' );
	
	$element_classes = array(
		'feature-box',
		$custom_class
	);
	$element_classes = implode( ' ', array_filter( array_unique( $element_classes ) ) ); 
	if( !empty( $extra_classes) )
		$element_classes .= ' ' . implode( ' ', array_filter( array_unique( $extra_classes ) ) );
		
	if( empty($atts['title']) || empty($atts['description']) ){
		return;
	}
	
	ob_start();
   	?>
	<div id="<?php echo esc_attr($element_id);?>" class="<?php echo esc_attr($element_classes);?><?php if(empty($atts['style'])) { ?> text-center<?php } ?>">
        <div class="icon">
            <i class="<?php echo esc_attr($icon);?>"></i>
        </div>
        <div class="content">
            <a <?php echo $url_attr;?> id="<?php echo esc_attr($element_id);?>">        		
        		<?php
        		if( !empty($atts['title']) ){
        			echo '<h6>'.esc_html($atts['title']).'</h6>';
        		}
        		?>
        	</a>            
            <?php    			
    			if( !empty($atts['description']) ){
    				echo '<p>'.$atts['description'].'</p>';
    			}
			?>
        </div>
    </div>
	<?php
    return ob_get_clean();	
}
/******************************************************************************
 * 
 * Visual Composer Integration
 * 
 ******************************************************************************/

 function cdhl_feature_box_shortcode_vc_map() { 
	if ( function_exists( 'vc_map' ) ) {
		$params=array(
				array(
					'type'       => 'cd_radio_image',
					"heading"    => esc_html__("Style", 'cardealer-helper'),
					'param_name' => 'style',
					'options'    => cdhl_get_shortcode_param_data('cd_feature_box'),
				),
				array(
					'type'      => 'dropdown',
					'heading'   => esc_html__( 'Title Border', 'cardealer-helper' ),
					'param_name'=> 'border',
					'value'     => array(
						esc_html__( 'Yes', 'cardealer-helper' ) => 'yes',
						esc_html__( 'No', 'cardealer-helper' ) => 'no',
					),
					'description' => esc_html__( 'Set Title Border.', 'cardealer-helper' ),
					'dependency'  => array(
							'element' => 'style',
							'value'  => array( 
								'style-1', 
								'style-2', 
								'style-3' 
							),
					),
				),
				array(
					'type'      => 'checkbox',
					'heading'   => esc_html__( 'Add Hover Style?', 'cardealer-helper' ),
					'description'=> esc_html__('Click checkbox to add hover style to element', 'cardealer-helper'),
					'param_name'=> 'hover_style',
				),
				array(
					"type"       => "textfield",
					"class"      => "pgs_feature_box_title",
					"heading"    => esc_html__("Title", 'cardealer-helper'),
					"description"=> esc_html__("Enter title here", 'cardealer-helper'),
					"param_name" => "title",
				),
                array(
        			"type"        => "vc_link",
        			"heading"     => esc_html__("Link", 'cardealer-helper'),
        			"param_name"  => "url",
        		),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Description', 'cardealer-helper' ),
					'param_name' => 'description',
					"description"=> esc_html__("Enter description. Please ensure to add short content.", 'cardealer-helper'),
				),
                
			);
		$params = array_merge(
			$params,
			cdhl_iconpicker(),
			array(
				array(
					'type'      => 'css_editor',
					'heading'   => esc_html__( 'CSS box', 'cardealer-helper' ),
					'param_name'=> 'css',
					'group'     => esc_html__( 'Design Options', 'cardealer-helper' ),
				)
			)
		);
		vc_map( array(
			"name"                    => esc_html__( "Potenza Feature Box", 'cardealer-helper' ),
			"description"             => esc_html__( "Potenza Feature Box", 'cardealer-helper'),
			"base"                    => "cd_feature_box",
			"class"                   => "cardealer_helper_element_wrapper",
			"controls"                => "full",
			"icon"                    => cardealer_vc_shortcode_icon( 'cd_feature_box' ),
			"category"                => esc_html__('Potenza', 'cardealer-helper'),
			"show_settings_on_create" => true,
			"params"                  => $params,
		) );
	}
}
add_action( 'vc_before_init', 'cdhl_feature_box_shortcode_vc_map' );