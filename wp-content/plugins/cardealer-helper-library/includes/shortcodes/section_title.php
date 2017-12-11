<?php
/******************************************************************************
 * 
 * Shortcode : cd_section_title
 * 
 ******************************************************************************/
add_shortcode( 'cd_section_title', 'cdhl_shortcode_section_title' );
function cdhl_shortcode_section_title($atts,$content) {
	$atts = shortcode_atts( array(
		'section_title'     => '',
		'section_sub_title' => '',
		'title_align' => 'text-center',
		'hide_seperator' =>false,
		'show_content'=>false,		
	), $atts );
	
	extract( $atts );
	
	if( empty( $section_title ) ) return;
	
	ob_start();
        ?>
     <div class="section-title <?php esc_attr_e($title_align)?>">
    	<?php
		if( !empty( $section_title ) ){
			?>
			<span><?php echo esc_html($section_sub_title);?></span>
			<?php
		}
		?>
         <h2><?php echo esc_html($section_title);?></h2>
		 <?php 
			if( isset($hide_seperator) && $hide_seperator == false )
			{?>
				<div class="separator"></div>
			<?php
			}
		 ?>
         <?php if ( $show_content == true && !empty($content) ) { ?>
				<p><?php echo $content; ?></p>
			<?php } ?>
      </div>
        <?php
	return ob_get_clean();
}

/******************************************************************************
 * 
 * Visual Composer Integration
 * 
 ******************************************************************************/
function cdhl_section_title_shortcode_vc_map() {
	if ( function_exists( 'vc_map' ) ) {
		vc_map( array(
			"name"                    => esc_html__( "Potenza Section Title", 'cardealer-helper' ),
			"description"             => esc_html__( "Potenza Section Title", 'cardealer-helper'),
			"base"                    => "cd_section_title",
			"class"                   => "_helper_element_wrapper",
			"controls"                => "full",
			"icon"                    => cardealer_vc_shortcode_icon( 'cd_section_title' ),
			"category"                => esc_html__('Potenza', 'cardealer-helper'),
			"show_settings_on_create" => true,
			"params"                  => array(
				array(
					"type"       => "textfield",
					"class"      => "",
					"heading"    => esc_html__("Section Title", 'cardealer-helper'),
					"description"=> esc_html__("Enter section title.", 'cardealer-helper'),
					"param_name" => "section_title",
				),
				array(
					"type"       => "textfield",
					"class"      => "",
					"heading"    => esc_html__("Section Subtitle", 'cardealer-helper'),
					"description"=> esc_html__("Enter section subtitle.", 'cardealer-helper'),
					"param_name" => "section_sub_title",
				),
				array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Title Align', 'cardealer-helper' ),
				'param_name' => 'title_align',
				'value' => array( 
                    esc_html__('left','cardealer-helper')=>'text-left',
					esc_html__('Center','cardealer-helper')=>'text-center',
				    esc_html__('Right','cardealer-helper')=>'text-right',				    
				),
				'save_always' => true,
				),
				array(
						'type'       => 'checkbox',
						'heading'    => esc_html__( 'Hide separator(border)?', 'cardealer-helper' ),
						'description'=> esc_html__( 'This will hide separator(border) displayed after section title.', 'cardealer-helper' ),
						'param_name' => 'hide_seperator',
				),
				array(
						'type'      => 'checkbox',
						'heading'   => esc_html__( 'Show content?', 'cardealer-helper' ),
						'param_name'=> 'show_content',
				),
				array(
					"type"       => "textarea_html",
					"class"      => "",
					"heading"    => esc_html__("Section Content", 'cardealer-helper'),
					"description"=> esc_html__("Enter content here.", 'cardealer-helper'),
					"param_name" => "content",
					'dependency'  => array(
						'element' => 'show_content',
						 'value'       => 'true',
					),
				),
				
			)
		) );
	}
}
add_action( 'vc_before_init', 'cdhl_section_title_shortcode_vc_map' );