<?php
/******************************************************************************
 *
 * Shortcode : pgs_cars_carousel
 *
 ******************************************************************************/
add_shortcode( 'pgs_cars_carousel', 'cdhl_pgs_cars_carousel_shortcode' );
function cdhl_pgs_cars_carousel_shortcode($atts) {
	$atts = shortcode_atts( array(
		'categories'       => '',
		'number_of_item'   => 5,
		'carousel_layout'  => 'carousel_1',
		'custom_title'     => esc_html__('New Arrivals','cardealer-helper'),
		'carousel_type'    => 'pgs_new_arrivals',
		'data_md_items'    => 4,
		'data_sm_items'    => 2,
		'data_xs_items'    => 1,
		'data_xx_items'    => 1,
		'data_space'       => 20,
		'dots'             => 'true',
		'arrow'            => 'true',
		'autoplay'         => 'true',
		'data_loop'        => 'true',
		'item_background'  => 'white-bg',
		'silder_type'      => 'with_silder',
		'number_of_column' => 1,
	), $atts );
	
	extract( $atts );

	if(!empty($custom_title)){
		$title = $custom_title;
	}

	$categories = trim($categories);
	$args=array(
		'post_type'     => 'cars',
		'posts_status'  => 'publish',
		'posts_per_page'=> $number_of_item,

	);
	if(!empty($categories)){
		$categories_array = explode(',', $categories);
		if( is_array($categories_array) && !empty($categories_array) ){
		//make wise filter
			$args['tax_query']=array(
				array(
					'taxonomy' => 'car_make',
					'field' => 'slug',
					'terms' => $categories_array,
				)
			);
		}
	}

	if($carousel_type=='pgs_featured'){
		// Featured product
		$args['meta_query']=array(
			array('key'     => 'featured',
				'value'   => '1',
				'compare' => '='
			),
		);
	}elseif($carousel_type=='pgs_on_sale'){
		// On Sale product
		$args['meta_query']=array(
			array('key'     => 'sale_price',
				'value'   => '',
				'compare' => '!='
			),
		);
	}elseif($carousel_type=='pgs_cheapest'){
		// Cheapest Product
		unset($args['meta_query']);
		$args['meta_key'] = 'regular_price';
		$args['meta_value_num'] = 'regular_price';
		$args['orderby'] = 'meta_value_num';
		$args['order'] = 'ASC';
	}

	$loop = new WP_Query( $args );
	
	// Bail if no posts found.
	if ( !$loop->have_posts() ) return;
	
	ob_start();

	if($arrow=='true')
	{
		$arrow="true";
	}
	else{
		$arrow = "false";
	}
	if($dots=='true')
	{
		$dots="true";
	}
	else{
		$dots = "false";
	}
	if($autoplay=='true')
	{
		$autoplay="true";
	}
	else{
		$autoplay = "false";
	}
	if($data_loop=='true')
	{
		$data_loop="true";
	}
	else{
		$data_loop = "false";
	}

	// Compare Cars
	if(isset($_COOKIE['cars']) && !empty($_COOKIE['cars'])){
		$carInCompare = json_decode($_COOKIE['cars']);
	}
	?>
	<div class="pgs_cars_carousel-wrapper">
		<?php
		$item_wrapper_classes = array(
			'pgs_cars_carousel-items',
		);
		$item_wrapper_attr = '';
		if( $silder_type == "with_silder" ){
			$item_wrapper_classes[] = 'owl-carousel';
			$item_wrapper_classes[] = 'pgs-cars-carousel';
		
			$item_wrapper_attrs = array(
				'data-nav-arrow'=> 'data-nav-arrow="'.esc_attr__($arrow).'"',
				'data-nav-dots' => 'data-nav-dots="'.esc_attr__($dots).'"',
				'data-items'    => 'data-items="'.esc_attr__($data_md_items).'"',
				'data-md-items' => 'data-md-items="'.esc_attr__($data_md_items).'"',
				'data-sm-items' => 'data-sm-items="'.esc_attr__($data_sm_items).'"',
				'data-xs-items' => 'data-xs-items="'.esc_attr__($data_xs_items).'"',
				'data-xx-items' => 'data-xx-items="'.esc_attr__($data_xx_items).'"',
				'data-space'    => 'data-space="'.esc_attr__($data_space).'"',
				'data-autoplay' => 'data-autoplay="'.esc_attr__($autoplay).'"',
				'data-loop'     => 'data-loop="'.esc_attr__($data_loop).'"',
			);
			$item_wrapper_attrs = implode( ' ', array_filter( array_unique( $item_wrapper_attrs ) ) );
			if( $item_wrapper_attrs && $item_wrapper_attrs != '' ){
				$item_wrapper_attr = $item_wrapper_attrs;
			}
		}
		$item_wrapper_classes = implode( ' ', array_filter( array_unique( $item_wrapper_classes ) ) );
		?>
		<div class="<?php echo esc_attr($item_wrapper_classes);?>" <?php echo $item_wrapper_attr;?>>
			<?php
			if( $silder_type != "with_silder" ){
				?>
				<div class="row">
				<?php
			}
			$k = 0;
			while ( $loop->have_posts() ) : $loop->the_post();
				
				$item_classes = array(
					'item',
				);
				
				$car_item_classes = array(
					'car-item',
					'text-center',
				);
				
				if($carousel_layout == "carousel_2"){
					$car_item_classes[] = "car-item-2";
				}
				
				if( $silder_type != "with_silder" )
				$item_classes[] = 'col-sm-'. 12/$number_of_column;
				
				$item_classes = implode( ' ', array_filter( array_unique( $item_classes ) ) );
				$car_item_classes = implode( ' ', array_filter( array_unique( $car_item_classes ) ) );
				?>
				<div class='<?php echo esc_attr($item_classes);?>'>
					<div class='<?php esc_attr_e($car_item_classes);?> <?php esc_attr_e($item_background)?>'>
						<div class='car-image'>
							<?php echo (function_exists('cardealer_get_cars_image'))? cardealer_get_cars_image() : '';?>
							<div class='car-overlay-banner'>
								<ul>
									<li><a href='<?php echo get_the_permalink();?>' data-toggle="tooltip" title="View"><i class='fa fa-link'></i></a></li>
									<?php
									if(isset($carInCompare) && !empty($carInCompare) && in_array(get_the_ID(), $carInCompare)) {
										$cars = json_decode($_COOKIE['cars']);
										?>
										<li><a href="javascript:void(0)" class="compare_pgs compared_pgs" data-id="<?php echo get_the_ID();?>" data-toggle="tooltip" title="Compare"><i class="fa fa-check"></i></a></li>
										<?php
									} else {
										?>
										<li><a href="javascript:void(0)" class="compare_pgs" data-id="<?php echo get_the_ID();?>" data-toggle="tooltip" title="Compare"><i class="fa fa-exchange"></i></a></li>
										<?php
									}
									$images = cardealer_get_images_url('car_catalog_image',get_the_ID());
									if(!empty($images)){
										?>
										<li class="pssrcset">
											<a href="javascript:void(0)" data-toggle="tooltip" title="Gallery" class="psimages" data-image="<?php echo implode(", ",$images); ?>"><i class="fa fa-expand" ></i></a>
										</li>
										<?php
									}
									?>
								</ul>
							</div>
							<?php
							if( $carousel_layout != "carousel_2" ){
								if(function_exists('cardealer_get_cars_list_attribute')){cardealer_get_cars_list_attribute();}
							}
							?>
						</div>
						<?php
						if( $carousel_layout == "carousel_2" ){
							if(function_exists('cardealer_get_cars_list_attribute')){cardealer_get_cars_list_attribute();}
						}
						?>
						<div class='car-content'>
							<a href='<?php echo get_the_permalink()?>'><?php the_title()?></a>
							<div class='separator'></div>
							<?php if(function_exists('cardealer_car_price_html')){cardealer_car_price_html();}?>
						</div>
					</div>
				</div>
				<?php
			endwhile;
			wp_reset_postdata();
			if( $silder_type != "with_silder" ){
				?>
				</div>
				<?php
			}
			?>
		</div>
	</div><!-- .pgs_cars_carousel-wrapper -->
	<?php
	return ob_get_clean();
}

/******************************************************************************
 *
 * Visual Composer Integration
 *
 ******************************************************************************/
function cdhl_pgs_cars_carousel_shortcode_integrateWithVC() {
	if ( function_exists( 'vc_map' ) ) {
		$car_categories = cdhl_get_terms( array('taxonomy' => 'car_make') );
		vc_map( array(
			'name'    => esc_html__( 'Potenza Cars Carousel', 'cardealer-helper' ),
			'base'    => 'pgs_cars_carousel',
			'class'   => '',
			'icon'    => cardealer_vc_shortcode_icon( 'pgs_cars_carousel' ),
			'category'=> esc_html__('Potenza', 'cardealer-helper'),
			'params'  => array(
				array(
					'type'       => 'cd_radio_image',
					'heading'    => esc_html__("Tabs type", 'cardealer-helper'),
					'param_name' => 'carousel_layout',
					'options'    => cdhl_get_shortcode_param_data('cd_carousel'),
				),
				array(
					'type'      => 'dropdown',
					'heading'   => esc_html__('List Style', 'cardealer-helper' ),
					'param_name'=> 'silder_type',
					'value'     => array(
						esc_html__('Carousel','cardealer-helper')=>'with_silder',
						esc_html__('Grid','cardealer-helper')=>'without_silder',
					),
					'admin_label'=> true,
					'save_always'=> true,
					'description'=> esc_html__('It will display carousel slider or grid listing based on selection.', 'cardealer-helper' ),
				),
				array(
					'type'      => 'dropdown',
					'heading'   => esc_html__( 'Items Type', 'cardealer-helper' ),
					'param_name'=> 'carousel_type',
					'value'     => array(
						esc_html__('Newest','cardealer-helper')=>'pgs_new_arrivals',
						esc_html__('Featured','cardealer-helper')=>'pgs_featured',
						esc_html__('On sale','cardealer-helper')=>'pgs_on_sale',
						esc_html__('Cheapest','cardealer-helper')=>'pgs_cheapest',
					),
					'admin_label'=> true,
					'save_always' => true,
				),
				
				/*------------------------------------------------ Grid Settings ------------------------------------------------*/
				array(
					'type'      => 'dropdown',
					'heading'   => esc_html__('Number of column', 'cardealer-helper'),
					'param_name'=> 'number_of_column',
					'value'     => array(
						esc_html__('1','cardealer-helper')=>'1',
						esc_html__('2','cardealer-helper')=>'2',
						esc_html__('3','cardealer-helper')=>'3',
						esc_html__('4','cardealer-helper')=>'4',
					),
					'group'     => esc_html__( 'Grid Settings', 'cardealer-helper' ),
					'dependency'=> array(
						'element' => 'silder_type',
						'value'  => array( 'without_silder' )
					),
					'save_always' => true,
				),
				
				/*------------------------------------------------ Carousel Settings ------------------------------------------------*/
				array(
					'type'      => 'dropdown',
					'heading'   => esc_html__('Number of slide desktops per rows', 'cardealer-helper'),
					'param_name'=> 'data_md_items',
					'value'     => array(
						esc_html__('3','cardealer-helper')=>'3',
						esc_html__('4','cardealer-helper')=>'4',
					),
					'edit_field_class'=> 'vc_col-sm-6 vc_column',
					'group'           => esc_html__( 'Slider Settings', 'cardealer-helper' ),
					'save_always'     => true,
					'dependency'      => array(
						'element' => 'silder_type',
						'value'   => 'with_silder',
					)
				),
				array(
					'type'      => 'dropdown',
					'heading'   => esc_html__('Number of slide tablets', 'cardealer-helper'),
					'param_name'=> 'data_sm_items',
					'value'     => array(
						esc_html__('2','cardealer-helper')=>'2',
						esc_html__('3','cardealer-helper')=>'3',
						esc_html__('4','cardealer-helper')=>'4',
					),
					'edit_field_class'=> 'vc_col-sm-6 vc_column',
					'group'           => esc_html__( 'Slider Settings', 'cardealer-helper' ),
					'save_always'     => true,
					'dependency'      => array(
						'element' => 'silder_type',
						'value'   => 'with_silder',
					)
				),
				array(
					'type'            => 'dropdown',
					'heading'     => esc_html__('Number of slide mobile landscape', 'cardealer-helper'),
					'param_name'  => 'data_xs_items',
					'value' => array(
						esc_html__('1','cardealer-helper')=>'1',
						esc_html__('2','cardealer-helper')=>'2',
						esc_html__('3','cardealer-helper')=>'3',
					),
					'edit_field_class'=> 'vc_col-sm-6 vc_column',
					'group'           => esc_html__( 'Slider Settings', 'cardealer-helper' ),
					'save_always' => true,
					'dependency'      => array(
						'element' => 'silder_type',
						'value'   => 'with_silder',
					)
				),
				array(
					'type'            => 'dropdown',
					'heading'     => esc_html__('Number of slide mobile portrait', 'cardealer-helper'),
					'param_name'  => 'data_xx_items',
					'value' => array(
						esc_html__('1','cardealer-helper')=>'1',
						esc_html__('2','cardealer-helper')=>'2',
					),
					'edit_field_class'=> 'vc_col-sm-6 vc_column',
					'group'           => esc_html__( 'Slider Settings', 'cardealer-helper' ),
					'save_always' => true,
					'dependency'      => array(
						'element' => 'silder_type',
						'value'   => 'with_silder',
					)
				),
				array(
					'type'            => 'dropdown',
					'heading' => esc_html__( 'Navigation Arrow', 'cardealer-helper' ),
					'param_name' => 'arrow',
					'value' => array(
						esc_html__('Yes','cardealer-helper')=>'true',
						esc_html__('No','cardealer-helper')=>'false',
					),
					'edit_field_class'=> 'vc_col-sm-6 vc_column',
					'group'           => esc_html__( 'Slider Settings', 'cardealer-helper' ),
					'save_always' => true,
					'dependency'      => array(
						'element' => 'silder_type',
						'value'   => 'with_silder',
					)
				),
				array(
					'type'            => 'dropdown',
					'heading'     => esc_html__('Navigation Dots', 'cardealer-helper'),
					'param_name'  => 'dots',
					'value' => array(
						esc_html__('Yes','cardealer-helper')=>'true',
						esc_html__('No','cardealer-helper')=>'false',
					),
					'edit_field_class'=> 'vc_col-sm-6 vc_column',
					'group'           => esc_html__( 'Slider Settings', 'cardealer-helper' ),
					'save_always' => true,
					'dependency'      => array(
						'element' => 'silder_type',
						'value'   => 'with_silder',
					)
				),
				array(
					'type'            => 'dropdown',
					'heading'     => esc_html__('Autoplay', 'cardealer-helper'),
					'param_name'  => 'autoplay',
					'value' => array(
						esc_html__('Yes','cardealer-helper')=>'true',
						esc_html__('No','cardealer-helper')=>'false',
					),
					'edit_field_class'=> 'vc_col-sm-6 vc_column',
					'group'           => esc_html__( 'Slider Settings', 'cardealer-helper' ),
					'save_always' => true,
					'dependency'      => array(
						'element' => 'silder_type',
						'value'   => 'with_silder',
					)
				),
				array(
					'type'            => 'dropdown',
					'heading'     => esc_html__('Loop', 'cardealer-helper'),
					'param_name'  => 'data_loop',
					'value' => array(
						esc_html__('Yes','cardealer-helper')=>'true',
						esc_html__('No','cardealer-helper')=>'false',
					),
					'edit_field_class'=> 'vc_col-sm-6 vc_column',
					'group'           => esc_html__( 'Slider Settings', 'cardealer-helper' ),
					'save_always' => true,
					'dependency'      => array(
						'element' => 'silder_type',
						'value'   => 'with_silder',
					)
				),
				array(
					'type'            => 'cd_number_min_max',
					'heading'     => esc_html__('Space between two slide', 'cardealer-helper'),
					'param_name'  => 'data_space',
					'min'         => '1',
					'max'         => '9999',
					'edit_field_class'=> 'vc_col-sm-6 vc_column',
					'group'           => esc_html__( 'Slider Settings', 'cardealer-helper' ),
					'dependency'      => array(
						'element' => 'silder_type',
						'value'   => 'with_silder',
					)
				),
				
				/*------------------------------------------------ Posts Settings ------------------------------------------------*/
				array(
					'type'       => 'cd_number_min_max',
					'class'      => '',
					'heading'    => esc_html__('Number of item', 'cardealer-helper'),
					'param_name' => 'number_of_item',
					'min'        => '1',
					'max'        => '9999',
					'description'=> esc_html__('Select Number of items to display.','cardealer-helper'),
					'group'      => esc_html__( 'Posts', 'cardealer-helper' ),
					'admin_label'=> true,
				),
				array(
					'type'       => 'checkbox',
					'heading'    => esc_html__('Categories', 'cardealer-helper'),
					'param_name' => 'categories',
					'description'=> esc_html__('Select categories to limit result from. To display result from all categories leave all categories unselected.', 'cardealer-helper'),
					'value'      => $car_categories,
					'group'      => esc_html__( 'Posts', 'cardealer-helper' ),
					'admin_label'=> true,
				),
				
				/*------------------------------------------------ Design Settings ------------------------------------------------*/
				array(
					'type'      => 'dropdown',
					'heading'   => esc_html__( 'Item Background', 'cardealer-helper' ),
					'param_name'=> 'item_background',
					'value'     => array(
						esc_html__('White','cardealer-helper')=>'white-bg',
						esc_html__('Grey','cardealer-helper')=>'grey-bg',
					),
					'group'     => esc_html__( 'Design Settings', 'cardealer-helper' ),
					'save_always' => true,
				),
			)
		) );
	}
}
add_action( 'vc_before_init', 'cdhl_pgs_cars_carousel_shortcode_integrateWithVC');
?>