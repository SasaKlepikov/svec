<?php
/******************************************************************************
 *
 * Shortcode : cd_vertical_vertical_multi_tabs
 *
 ******************************************************************************/
add_shortcode( 'cd_vertical_multi_tabs', 'cdhl_shortcode_vertical_multi_tabs' );
function cdhl_shortcode_vertical_multi_tabs($atts) {
	extract( shortcode_atts(  array(			  
        'number_of_item'=>5,        
        'padding'=>0,		            
		'tab_type' => 'pgs_new_arrivals',
        'car_make_slugs' => '',
		'number_of_column' => '',
        'element_id' => uniqid(),
	   ), $atts ) 
	);
	extract( $atts );
    if(empty($car_make_slugs)){
       return; 
    }    
	// car compare code
	if(isset($_COOKIE['cars']) && !empty($_COOKIE['cars'])){
		$carInCompare = json_decode($_COOKIE['cars']);
	}	
	$car_make_slugs = explode(',',$car_make_slugs);        
    	
	ob_start();?>        
    
    <div class="tab-vertical tabs-left">        
        <div class="left-tabs-block">
            <ul class="nav nav-tabs vartical-tab-nav" id="vertical-tab">                
                <?php
                $i=1;                
                foreach( $car_make_slugs as $tab):?>                	
                    <li class="<?php echo ($i==1)?"active":'';?>"><a href="#<?php echo esc_attr($tab.'_'.$element_id);?>" data-toggle="tab"><?php echo esc_html($tab);?></a></li>                    
                    <?php
                    $i++;                
                endforeach;?>         
            </ul>
            <?php
            $images_url = (!empty($atts['advertise_img']) ? $atts['advertise_img'] : '');
            if(!empty($images_url)){
                $img_url = wp_get_attachment_url($images_url, "full");                
                $img_alt = get_post_meta( $images_url,'_wp_attachment_image_alt',true );    
            }
			if(!empty($img_url)){?>
            <div class="ads-img">
                <img class="" src="<?php echo esc_html($img_url)?>" alt="<?php echo esc_html($img_alt)?>">
            </div>
			<?php }?>
        </div>    
        
        <?php        
    	$args=array(
            'post_type' => 'cars',
            'posts_status' => 'publish',
            'posts_per_page' => $number_of_item,            									
        );
        
        if($tab_type=='pgs_featured'){
			/* Featured cars */
			$args['meta_query']=array(
				array(
                    'key'     => 'featured',
					'value'   => '1',
				    'compare' => '='
				),
			);
		}elseif($tab_type=='pgs_on_sale'){
			/* On Sale cars */
			$args['meta_query']=array(					
                'relation' => 'AND',
        		array(
        			'key'     => 'sale_price',
					'value'   => '',
					'compare' => '!=',
                    'type'    => 'NUMERIC'
        		),
				array(
                    'key'     => 'regular_price',
					'value'   => '',
					'compare' => '!=',
                    'type'    => 'NUMERIC'
				)
			);               
			$args['meta_value_num'] = 'sale_price';
			$args['orderby'] = 'meta_value_num';
			$args['order'] = 'asc';
		}elseif($tab_type=='pgs_cheapest'){
			/* Cheapest Product */			
			unset($args['meta_query']);
			$args['meta_query'] = array(
    			'relation' => 'AND',
                array(
                    'key'     => 'final_price',
					'value'   => '',
					'compare' => '!=',
                    'type'    => 'NUMERIC'
				)
    		);                
			$args['meta_value_num'] = 'final_price';
			$args['orderby'] = 'meta_value_num';
			$args['order'] = 'asc';
		}
        ?>
        <div class="tab-content column-<?php echo esc_attr($number_of_column); ?>">
			<?php
            $t=1;           
            foreach( $car_make_slugs as $tab):                
                $get_id =  $tab.'_'.$element_id;                
                $args['tax_query'] = array(
            		array(
            			'taxonomy' => 'car_make',
            			'field'    => 'slug',
            			'terms'    => $tab,
            		),
            	);               				
    			$loop = new WP_Query( $args );
    			?>
                <div class="tab-pane <?php echo ($t==1)?"active":'';?>" id="<?php echo esc_attr($get_id);?>">
                    <?php                  
                    if($loop->have_posts()){
                        while ( $loop->have_posts() ) : $loop->the_post();                        
                            $cars_cat_slug='';
                            $terms = get_the_terms( get_the_ID(), 'car_make');                        
                            
                            if(empty($terms)){
                                $style='';                        
                                if(isset($padding) && $padding  > 0){
                                    $style = 'style="padding:'.$padding.'px"';
                                }
                                echo '<div class="grid-item no-data '.$get_id.'" '.$style.'><div class="no-data-found">No car found.</div></div>';     
                            } else {                                
                            
                                foreach ($terms  as $term  ) {
                                    $cars_cat_id = $term->term_id;
                                    $cars_cat_slug = $term->slug.'_'.$element_id;                        
                                }
                                $style='';                        
                                if(isset($padding) && $padding  > 0){
                                    $style = 'style="padding:'.$padding.'px"';
                                }
                                ?>                    
                                <div class="grid-item <?php echo esc_attr($cars_cat_slug);?>" <?php echo $style;?>>
                                    <div class='car-item text-center car-item-2'>
                                        <div class='car-image'>
                                            <?php echo cardealer_get_cars_image('car_catalog_image');?>                        
                                            <div class='car-overlay-banner'>
                                                <ul> 
                                                    <li><a href='<?php echo get_the_permalink();?>'><i class='fa fa-link'></i></a></li>
            										 <?php
            											if(isset($carInCompare) && !empty($carInCompare) && in_array(get_the_ID(), $carInCompare))
            											{
            												$cars = json_decode($_COOKIE['cars']);
            											?>
            												<li><a href="javascript:void(0)" class="compare_pgs compared_pgs" data-id="<?php echo get_the_ID();?>"><i class="fa fa-check"></i></a></li> 
            											<?php
            											} else {
            											?>
            												<li><a href="javascript:void(0)" class="compare_pgs" data-id="<?php echo get_the_ID();?>"><i class="fa fa-exchange"></i></a></li>
            											<?php
            											}
														$images = cardealer_get_images_url('car_catalog_image',get_the_ID()); 
														if(!empty($images)){?>	
														<li class="pssrcset"><a href="javascript:void(0)" data-toggle="tooltip" title="Gallery" class="psimages" data-image="<?php echo implode(", ",$images); ?>"><i class="fa fa-expand" ></i></a></li>
														<?php }?>                                                  
                                                </ul>
                                            </div>
                                        </div>                        
                                        <?php cardealer_get_cars_list_attribute();?>                        
                                        <div class='car-content'>                                    
                                            <a href='<?php echo get_the_permalink()?>'><?php the_title()?></a>
                                            <div class='separator'></div>
                                            <?php cardealer_car_price_html();?>                            
                                        </div>                        
                                    </div>                            
                                </div>                    
                                <?php
                            }                      
                		endwhile;
                		wp_reset_postdata();			
                		/*End cars type tab */
                    } else {
                        $style='';                        
                        if(isset($padding) && $padding  > 0){
                            $style = 'style="padding:'.$padding.'px"';
                        }
                        echo '<div class="grid-item no-data '.$get_id.'" '.$style.'><div class="no-data-found">No car found.</div></div>'; 
                        
                    }
                    ?>
                </div>
                <?php
                $t++;                                    
            endforeach;?>                       
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
function cdhl_vertical_multi_tabs_shortcode_vc_map() {
	if ( function_exists( 'vc_map' ) ) {        
        $car_make = array();
        $car_make= cdhl_get_terms( array( // You can pass arguments from get_terms (except hide_empty)
			'taxonomy'  => 'car_make',
		));       
        $params=array(				
				array(
                    'type' => 'dropdown',
                    'heading' => esc_html__( 'Display Cars Type', 'cardealer-helper' ),
                    'param_name' => 'tab_type',
                    'value' => array( 
                        esc_html__('Newest','cardealer-helper')=>'pgs_new_arrivals',
                        esc_html__('Featured','cardealer-helper')=>'pgs_featured',                        
                        esc_html__('On sale','cardealer-helper')=>'pgs_on_sale',
                        esc_html__('Cheapest','cardealer-helper')=>'pgs_cheapest',
    				),
                    'save_always' => true,                
                    'description' => esc_html__( 'Select cars types. which will be display at frontend', 'cardealer-helper' ),
                ),
                array(
                    'type' => 'attach_image',
                    'heading' => esc_html__( 'Advertise banner image', 'cardealer-helper' ),
                    'param_name' => 'advertise_img',                
                    'description' => esc_html__( 'Select image.', 'cardealer-helper' ),
                ),  
                array(
                    'type' => 'checkbox',
                    'heading' => esc_html__( 'Select makes', 'cardealer-helper' ),
                    'param_name' => 'car_make_slugs',
                    'value' => $car_make,                                                                           
                ),
				array(
                    "type"        => "cd_number_min_max",
                    "class"       => "",
                    'edit_field_class'=> 'vc_col-sm-6 vc_column',
                    "heading"     => esc_html__("Number of item", 'cardealer-helper'),                    
                    "param_name"  => "number_of_item",
                ),
				array(
    				'type'        => 'cd_number_min_max',
    				'class'       => '',
                    'edit_field_class'=> 'vc_col-sm-6 vc_column',
    				'heading'     => esc_html__('Padding', 'cardealer-helper'),                
    				'param_name'  => 'padding',
                    'min'             => '1',
                    'max'             => '200',                    
    			)				
			);			
            vc_map( array(
            	"name"                    => esc_html__( "Potenza Verticular Multi Tabs", 'cardealer-helper' ),            	
            	"base"                    => "cd_vertical_multi_tabs",
            	"class"                   => "cardealer_helper_element_wrapper",
                "icon"                    => cardealer_vc_shortcode_icon( 'cd_vertical_multi_tabs' ),
            	"controls"                => "full",            	
            	"category"                => esc_html__('Potenza', 'cardealer-helper'),            	
            	"params"                  => $params
            ) );
	}
}
add_action( 'vc_before_init', 'cdhl_vertical_multi_tabs_shortcode_vc_map' );