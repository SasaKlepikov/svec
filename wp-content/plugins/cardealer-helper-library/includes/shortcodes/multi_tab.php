<?php
/******************************************************************************
 *
 * Shortcode : cd_multi_tabs
 *
 ******************************************************************************/
add_shortcode( 'cd_multi_tabs', 'cdhl_shortcode_multi_tabs' );
function cdhl_shortcode_multi_tabs($atts) {
	extract( shortcode_atts(  array(			  
        'number_of_item'=>5,
        'number_of_column'=>4,                             		
		'multi_tabs' => 'multi_tab_1',            
		'tab_type' => 'pgs_new_arrivals',
        'car_make_slugs' => array(),
        'element_id'      => uniqid(),                 
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
    
	ob_start();
        ?>		
        <div data-uid="<?php echo esc_attr($element_id)?>" data-active="<?php echo esc_attr($car_make_slugs[0])?>" class="isotope-filters multi-tab  multi-tab-isotope-filter  multi-tab-isotope-filter-<?php echo esc_attr($element_id)?>">
            <?php            
            foreach( $car_make_slugs as $tab):?>				
            	<button data-uid="<?php echo esc_attr($element_id);?>" <?php if($car_make_slugs[0]==$tab){ echo 'class="active"';} ?> data-filter="<?php echo '.'.$tab.'_'.$element_id?>"><?php echo esc_html($tab);?></button>                    
                <?php            
            endforeach;?>           
       </div>
	   <div class="horizontal-tabs isotope cd-multi-tab-isotope-<?php echo esc_attr($element_id);?> column-<?php echo esc_attr($number_of_column);?>">
			<?php
			$args=array(
                'post_type' => 'cars',
                'posts_status' => 'publish',
                'posts_per_page' => $number_of_item,									
            );
			if($tab_type=='pgs_featured'){
				/* Featured cars */
				$args['meta_query']=array(
							array('key'     => 'featured',
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
            foreach( $car_make_slugs as $tab):
                
                $args['tax_query'] = array(                    
                    array(
                        'taxonomy' => 'car_make',
                        'field'    => 'slug',
                        'terms'    => $tab,
                    ), 
                );
                            
    			$loop = new WP_Query( $args );$data='';
                if($loop->have_posts()){
                    while ( $loop->have_posts() ) : $loop->the_post();                        
                        $cars_cat_slug='';
                        $terms = get_the_terms( get_the_ID(), 'car_make');                        
                        if(empty($terms)){                           
                            $style='';                    
                            if(isset($padding) && $padding  > 0){
                                $style = 'style="padding:'.$padding.'px;"';
                            }
                            echo '<div class="grid-item no-data '.$tab.'_'.$element_id.'" '.$style.'><div class="no-data-found">'.esc_html("No car found","cardealer-helper").'.</div></div>';
                        }else{
                            foreach ($terms  as $term  ) {
                                $cars_cat_id = $term->term_id;
                                $cars_cat_slug .= $term->slug.'_'.$element_id.' ';
                                $carscatslug[] = $term->slug;                            
                            }
                            $style='';                        
                            if(isset($padding) && $padding  > 0){
                                $style = 'style="padding:'.$padding.'px"';
                            }                    
                            ?>
                            <div class="grid-item <?php echo esc_attr($cars_cat_slug)?> grid-item-<?php echo esc_attr($element_id)?>" <?php echo $style;?>>
                                    <?php
                                    if($multi_tabs == "multi_tab_2"){                                    
                                        ?>
                                        <div class='car-item text-center'>
                                            <div class='car-image'>
                                                <?php echo cardealer_get_cars_image('car_tabs_image');?>                        
                                                <div class='car-overlay-banner'>
                                                    <ul> 
                                                        <li><a href='<?php echo esc_url(get_the_permalink());?>'><i class='fa fa-link'></i></a></li>
            											 <?php
            												if(isset($carInCompare) && !empty($carInCompare) && in_array(get_the_ID(), $carInCompare))
            												{
            													$cars = json_decode($_COOKIE['cars']);
            												?>
            													<li><a href="javascript:void(0)" class="compare_pgs compared_pgs" data-id="<?php echo esc_attr(get_the_ID());?>"><i class="fa fa-check"></i></a></li> 
            												<?php
            												} else {
            												?>
            													<li><a href="javascript:void(0)" class="compare_pgs" data-id="<?php echo esc_attr(get_the_ID());?>"><i class="fa fa-exchange"></i></a></li>
            												<?php
            												}
															$images = cardealer_get_images_url('car_catalog_image',get_the_ID()); 
															if(!empty($images)){?>	
															<li class="pssrcset"><a href="javascript:void(0)" data-toggle="tooltip" title="Gallery" class="psimages" data-image="<?php echo implode(", ",$images); ?>"><i class="fa fa-expand" ></i></a></li>
															<?php }?>                                               
                                                    </ul>
                                                </div>
                                                <?php cardealer_get_cars_list_attribute();?>                                                 
											</div>                        
                                            <div class='car-content'>
                                                <a href='<?php echo esc_url(get_the_permalink())?>'><?php the_title()?></a>
                                                <div class='separator'></div>
                                                <?php cardealer_car_price_html();?>                            
                                            </div>                        
                                        </div>
                                        <?php
                                    }else{?>
                                        <div class="car-item-3">                                            
                                            <?php echo cardealer_get_cars_image("car_tabs_image");?>                                       
                                            <div class="car-popup">
                                                <a class="popup-img" href="<?php echo esc_url(get_the_permalink());?>"><i class="fa fa-plus"></i></a>
                                            </div>                                                                
                                            <div class="car-overlay text-center">
											<a class="link" href="<?php echo esc_url(get_the_permalink());?>"><?php the_title()?></a>
                                            </div>                                                                                
                                        </div>
                                        <?php  
                                    }?>                            
                            </div>
                            <?php
                        }                        
        			endwhile;
                    wp_reset_postdata();                                    
                } else {                        
                    $style='style=""';                     
                    if(isset($padding) && $padding  > 0){
                        $style = 'style="padding:'.$padding.'px;"';
                    }
                    echo '<div class="grid-item no-data '.$tab.'_'.$element_id.'" '.$style.'><div class="no-data-found">'.esc_html("No car found","cardealer-helper").'.</div></div>';
                }
            endforeach;
            /*End cars type tab */?>
		</div>
    <?php	
    return ob_get_clean();
}

/******************************************************************************
 *
 * Visual Composer Integration
 *
 ******************************************************************************/
function cdhl_multi_tabs_shortcode_vc_map() {
	if ( function_exists( 'vc_map' ) ) {
		$car_make = array();
        $car_make= cdhl_get_terms( array( // You can pass arguments from get_terms (except hide_empty)
			'taxonomy'  => 'car_make',
		));              
        $params=array(
				array(
					'type'       => 'cd_radio_image',
					'heading'    => esc_html__("Tabs type", 'cardealer-helper'),
					'param_name' => 'multi_tabs',
					'options'    => cdhl_get_shortcode_param_data('cd_multi_tabs'),
				),
				array(
                    "type"        => "cd_number_min_max",
                    "class"       => "",
                    "heading"     => esc_html__("Number of item", 'cardealer-helper'),                    
                    "param_name"  => "number_of_item",
					"admin_label" => false,
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => esc_html__( 'Number of column', 'cardealer-helper' ),
                    'param_name' => 'number_of_column',
                    'edit_field_class'=> 'vc_col-sm-3 vc_column',
                    'value' => array( 
                        esc_html__('3','cardealer-helper')=>'3',
                        esc_html__('4','cardealer-helper')=>'4',
                        esc_html__('5','cardealer-helper')=>'5',                        
    				),
                    'default' => '4',
                    'save_always' => true,                    
                ),
                array(
    				'type'        => 'cd_number_min_max',
    				'heading'     => esc_html__('Padding', 'cardealer-helper'),                
    				'param_name'  => 'padding',
                    'min'             => '1',
                    'max'             => '200',                    
    			),     
				array(
                    'type' => 'checkbox',
                    'heading' => esc_html__( 'Select makes', 'cardealer-helper' ),
                    'param_name' => 'car_make_slugs',
                    'value' => $car_make,                    
                ),	                
    			array(
                    'type' => 'dropdown',
                    'heading' => esc_html__( 'Display Cars Type', 'cardealer-helper' ),
                    'edit_field_class'=> 'vc_col-sm-3 vc_column',
					'param_name' => 'tab_type',
                    'value' => array( 
                        esc_html__('Newest','cardealer-helper')=>'pgs_new_arrivals',
                        esc_html__('Featured','cardealer-helper')=>'pgs_featured',                        
                        esc_html__('On sale','cardealer-helper')=>'pgs_on_sale',
                        esc_html__('Cheapest','cardealer-helper')=>'pgs_cheapest',
    				),
                    'save_always' => true,                
                    'description' => esc_html__( 'Select cars type for tabs. which will be displayed on frontend', 'cardealer-helper' ),
                ), 
			);			
            vc_map( array(
            	"name"                    => esc_html__( "Potenza Multi Tabs", 'cardealer-helper' ),            	
            	"base"                    => "cd_multi_tabs",
            	"class"                   => "cardealer_helper_element_wrapper",
            	"controls"                => "full",
                "icon"                    => cardealer_vc_shortcode_icon( 'cd_multi_tabs' ),
            	"category"                => esc_html__('Potenza', 'cardealer-helper'),            	
            	"params"                  => $params
            ) );
	}
}
add_action( 'vc_before_init', 'cdhl_multi_tabs_shortcode_vc_map' );