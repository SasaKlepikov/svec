<?php
/******************************************************************************
 *
 * Shortcode : cd_testimonials
 *
 ******************************************************************************/
add_shortcode( 'cd_testimonials', 'cdhl_shortcode_testimonials' );
function cdhl_shortcode_testimonials($atts) {
	$atts = shortcode_atts( array(
		'style'          => 'style-1',
		'no_of_testimonials' => 10,
		'css'            => '',
		'data_md_items'    => 4,
		'data_sm_items'    => 2,        
		'data_xs_items'    => 1,
		'data_xx_items'    => 1,
		'data_space'       => 20,
		'testimonials_slider_opt' => '',
		'element_id'     => uniqid('cd_testimonials_'),
	), $atts );
	extract( $atts );
	
	$args = array(
		'post_type'     => 'testimonials',
		'posts_per_page'=> $no_of_testimonials,
	);
	
	$the_query = new WP_Query( $args );
	if ( !$the_query->have_posts() ) {
		return null;
	}
	
	$css = $atts['css'];
	
	$custom_class = vc_shortcode_custom_css_class( $css, ' ' );
	
	if($atts['style'] == 'style-1') {
		$class_style = 'testimonial-1';
		$extra_class = 'white-bg page';
	}
	else if($atts['style'] == 'style-2') {
		$class_style = 'testimonial-3';
		$extra_class = 'white-bg';
	}
	else if($atts['style'] == 'style-3') {
		$class_style = 'testimonial-2';
		$extra_class = 'bg-1 bg-overlay-black-70';
	}
	else if($atts['style'] == 'style-4') {
		$class_style = 'testimonial-4';
		$extra_class = 'white-bg';
	}
	
	if($no_of_testimonials > 1) {
		$parent_class = 'owl-carousel';
		
		$testimonials_slider_opt = explode(',',$testimonials_slider_opt);
		$arrow = "false"; $autoplay = "false"; $dots = "false"; $data_loop = "false";
		foreach( $testimonials_slider_opt as $option ) {
			if( $option == 'Autoplay' )
				$autoplay = "true";
			else if( $option == 'Loop' )
				$data_loop = "true";
			else if( $option == 'Navigation Dots' )
				$dots = "true";
			else if( $option == 'Navigation Arrow' )
				$arrow = "true";
		}
		$dataAttr =	' data-nav-arrow='. esc_attr($arrow);
		$dataAttr .= ' data-nav-dots='. esc_attr($dots);
		$dataAttr .= ' data-items='. esc_attr($data_md_items);
		$dataAttr .= ' data-md-items='. esc_attr($data_md_items);
		$dataAttr .= ' data-sm-items='. esc_attr($data_sm_items);
		$dataAttr .= ' data-xs-items='. esc_attr($data_xs_items);
		$dataAttr .= ' data-xx-items='. esc_attr($data_xx_items);
		$dataAttr .= ' data-space='. esc_attr($data_space);
		$dataAttr .= ' data-autoplay='. esc_attr($autoplay);
		$dataAttr .= ' data-loop='. esc_attr($data_loop);
	}
	else
	{
		$parent_class = 'carousel';
		$dataAttr =	'';
	}
	
	$element_classes = array(
		'testimonial',
		$class_style,
		'page',		
		$custom_class,
		$extra_class
	);
	$element_classes = implode( ' ', array_filter( array_unique( $element_classes ) ) );	
		
	ob_start();
	?>
	<div id="cd_testimonials_<?php echo esc_attr($element_id);?>" class="<?php echo esc_attr($element_classes);?>">
		<?php
		if( $atts['style'] == 'style-1' ) {
			?>
			<div class="<?php echo esc_attr($parent_class);?>" <?php echo $dataAttr;?>>
				<?php
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					global $post;
					$content     = get_post_meta(get_the_ID(), 'content', $single = true);
					$designation = get_post_meta(get_the_ID(), 'designation', $single = true);
					$profile_img_id = get_post_meta(get_the_ID(), 'profile_image', $single = true);
					$profile_img = wp_get_attachment_image_src($profile_img_id, 'thumbnail');

					if( $content ){
						?>
						<div class="item">
							<div class="testimonial-block text-center">
								<div class="testimonial-image">
									<?php
									if ( has_post_thumbnail() ) {
										the_post_thumbnail('cardealer-testimonials-thumb');
									}
									?>
								</div> 
								<div class="testimonial-box">
								<div class="testimonial-avtar">
									<img class="img-responsive" src="<?php echo esc_url($profile_img[0]);?>" alt="" width="<?php echo esc_attr($profile_img[1]);?>" height="<?php echo esc_attr($profile_img[2]);?>">
									<h6><?php the_title(); ?></h6>
									<?php echo ( $designation) ? ' <span>' . esc_html($designation) . '</span>' : '';?>
								</div>
								<div class="testimonial-content">               
									<p><?php echo $content;?></p>
									<i class="fa fa-quote-right"></i>
								</div>
								</div>
							</div>
						</div>
						<?php
					}
				}
				?>
			</div>
			<?php
		}elseif( $atts['style'] == 'style-2' ) {
			?>
			<div class="<?php echo esc_attr($parent_class);?>" <?php echo $dataAttr;?>>
				<?php
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					global $post;
					$content     = get_post_meta(get_the_ID(), 'content', $single = true);
					$designation = get_post_meta(get_the_ID(), 'designation', $single = true);
					$profile_img_id = get_post_meta(get_the_ID(), 'profile_image', $single = true);
					$profile_img = wp_get_attachment_image_src($profile_img_id, 'medium');
					if( $content ){
						?>
						<div class="item">
							<div class="testimonial-block">
								<div class="row">
									<div class="col-lg-3 col-md-3 col-sm-3">
										<div class="testimonial-avtar">
											<img class="img-responsive" src="<?php echo esc_url($profile_img[0]);?>" alt="" width="<?php echo esc_attr($profile_img[1]);?>" height="<?php echo esc_attr($profile_img[2]);?>">
										</div>   
									</div>  
									<div class="col-lg-9 col-md-9 col-sm-9">
										<div class="testimonial-content">
											<p><i class="fa fa-quote-left"></i> <span><?php echo $content;?></span> <i class="fa fa-quote-right pull-right"></i></p>
										</div> 
										<div class="testimonial-info">
											<h6><?php the_title(); ?></h6>
											<?php echo ( $designation) ? ' <span>' . esc_html($designation) . '</span>' : '';?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php
					}
				}
				?>
			</div>
			<?php
		}elseif( $atts['style'] == 'style-3' ) {
			?>
			<div class="testimonial-center">
				<div class="<?php echo esc_attr($parent_class);?>" <?php echo $dataAttr;?>>
						<?php
						while ( $the_query->have_posts() ) {
							$the_query->the_post();
							global $post;
							$content     = get_post_meta(get_the_ID(), 'content', $single = true);
							$designation = get_post_meta(get_the_ID(), 'designation', $single = true);
							$profile_img_id = get_post_meta(get_the_ID(), 'profile_image', $single = true);
							$profile_img = wp_get_attachment_image_src($profile_img_id, 'thumbnail');
							if( $content ){
								?>
								<div class="item">
									<div class="testimonial-block">
										<div class="testimonial-content">
											<i class="fa fa-quote-left"></i>
											<p> <?php echo $content;?></p>
										</div>
										<div class="testimonial-info">
											<div class="testimonial-avatar">
												<img class="img-responsive" src="<?php echo esc_url($profile_img[0]);?>" alt="" width="<?php echo esc_attr($profile_img[1]);?>" height="<?php echo esc_attr($profile_img[2]);?>">
											</div>
											<div class="testimonial-name">
												<h6 class="text-white"><?php the_title(); ?></h6>
												<?php echo ( $designation) ? ' <span> |' . esc_html($designation) . '</span>' : '';?>
											</div>
										</div>
									</div>
								</div>
								<?php
							}
						}
						?>
					</div>
					</div>
			<?php
		}elseif( $atts['style'] == 'style-4' ) {
			?>
			<div class="<?php echo esc_attr($parent_class);?>" <?php echo $dataAttr;?>>
				<?php
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					
					global $post;
					$content     = get_post_meta(get_the_ID(), 'content', $single = true);
					$designation = get_post_meta(get_the_ID(), 'designation', $single = true);
					if( $content ){
						?>
						<div class="item">
							<div class="testimonial-block text-center">
								<i class="fa fa-quote-left"></i>
								<p><?php echo $content;?></p>
								<h6 class="text-red"><?php the_title(); ?></h6>
							</div>
						</div>
						<?php
					}
				}
				?>
			</div>
			<?php
		}
		?>
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
function cdhl_testimonials_shortcode_vc_map() {
	if ( function_exists( 'vc_map' ) ) {
		vc_map( array(
			"name"                    => esc_html__( "Potenza Testimonials", 'cardealer-helper' ),
			"description"             => esc_html__( "Potenza Testimonials", 'cardealer-helper' ),
			"base"                    => "cd_testimonials",
			"class"                   => "cardealer_helper_element_wrapper",
			"controls"                => "full",
			"icon"                    => cardealer_vc_shortcode_icon( 'cd_testimonials' ),
			"category"                => esc_html__('Potenza', 'cardealer-helper'),
			"show_settings_on_create" => true,
			"params"                  => array(
				array(
					'type'       => 'cd_radio_image',
					"heading"    => esc_html__("Style", 'cardealer-helper'),
					"description" => esc_html__("Select Testimonials style.", 'cardealer-helper'),
					'param_name' => 'style',
					'options'    => cdhl_get_shortcode_param_data('cd_testimonials'),
				),
				array(
					'type'            => 'cd_number_min_max',
					'heading'         => esc_html__( "No. of Testimonials", 'cardealer-helper' ),
					'param_name'      => 'no_of_testimonials',
					'value'           => '',
					'min'             => '1',
					'max'             => '9999',
					'description'     => esc_html__('Select count of testimonials to display.', 'cardealer-helper'),
				),
				array(
					'type'        => 'cd_number_min_max',
					'class'       => '',
					'heading'     => esc_html__('Number of slide desktops', 'cardealer-helper'),                
					'param_name'  => 'data_md_items',
					'min'             => '1',
					'max'             => '9999',					
				),
				array(
					'type'        => 'cd_number_min_max',
					'class'       => '',
					'heading'     => esc_html__('Number of slide tablets', 'cardealer-helper'),                
					'param_name'  => 'data_sm_items',                
					'min'             => '1',
					'max'             => '9999',					
				),
				array(
					'type'        => 'cd_number_min_max',
					'class'       => '',
					'heading'     => esc_html__('Number of slide mobile landscape', 'cardealer-helper'),                
					'param_name'  => 'data_xs_items',
					'min'             => '1',
					'max'             => '9999',					
				),
				array(
					'type'        => 'cd_number_min_max',
					'class'       => '',
					'heading'     => esc_html__('Number of slide mobile portrait', 'cardealer-helper'),                
					'param_name'  => 'data_xx_items',
					'min'             => '1',
					'max'             => '9999',
					
				),
				array(
					'type'        => 'cd_number_min_max',
					'class'       => '',
					'heading'     => esc_html__('Space between two slide', 'cardealer-helper'),                
					'param_name'  => 'data_space',
					'min'             => '1',
					'max'             => '9999',					
				), 
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Slider style option', 'cardealer-helper' ),
					'param_name' => 'testimonials_slider_opt',
					'value' => array(
						'Navigation Arrow' => 'Navigation Arrow',
						'Navigation Dots'  => 'Navigation Dots',
						'Autoplay'  => 'Autoplay',                                        
						'Loop'  => 'Loop',
					),
				),
				array(
					'type'      => 'css_editor',
					'heading'   => esc_html__( 'CSS box', 'cardealer-helper' ),
					'param_name'=> 'css',
					'group'     => esc_html__( 'Design Options', 'cardealer-helper' ),
				)
			)
		) );
	}
}
add_action( 'vc_before_init', 'cdhl_testimonials_shortcode_vc_map' );