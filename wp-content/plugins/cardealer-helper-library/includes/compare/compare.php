<?php   
/*
 * This function used to compare cars.
 */
add_action('wp_ajax_car_compare_action','cdhl_car_compare_action');
add_action('wp_ajax_nopriv_car_compare_action','cdhl_car_compare_action');
function cdhl_car_compare_action(){ 
global $car_dealer_options;
$carIds = array();
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'car_compare_action') {
		$carIds = json_decode($_REQUEST['car_ids'], true);
		if(empty($carIds)) return;
		$num_of_cars = count($carIds);
		?>
		<div class="modal-header">
			<button type="button" class="close_model" data-dismiss="modal" aria-hidden="true">×</button>
			<h1><?php echo esc_html__('Compare Cars', 'cardealer-helper'); ?></h1>
		</div>
		<div class="modal-content">
			<div class="table-Wrapper">
				<div class="heading-Wrapper">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr><td class="remove"></td></tr>
						<tr><td class="car_image"></td></tr>
						<tr><td class="price"><?php echo esc_html__('Price', 'cardealer-helper');?></td></tr>
						<tr><td class="year"><?php echo esc_html__('Year', 'cardealer-helper');?></td></tr>
						<tr><td class="make"><?php echo esc_html__('Make', 'cardealer-helper');?></td></tr>
						<tr><td class="model"><?php echo esc_html__('Model', 'cardealer-helper');?></td></tr>
						<tr><td class="body_style"><?php echo esc_html__('Body Style', 'cardealer-helper');?></td></tr>
						<tr><td class="mileage"><?php echo esc_html__('Mileage', 'cardealer-helper');?></td></tr>
						<tr><td class="fuel_economy"><?php echo esc_html__('Fuel Economy', 'cardealer-helper');?></td></tr>
						<tr><td class="transmission"><?php echo esc_html__('Transmission', 'cardealer-helper');?></td></tr>
						<tr><td class="condition"><?php echo esc_html__('Condition', 'cardealer-helper');?></td></tr>
						<tr><td class="drivetrain"><?php echo esc_html__('Drivetrain', 'cardealer-helper');?></td></tr>
						<tr><td class="engine"><?php echo esc_html__('Engine', 'cardealer-helper');?></td></tr>
						<tr><td class="exterior_color"><?php echo esc_html__('Exterior Color', 'cardealer-helper');?></td></tr>
						<tr><td class="interior_color"><?php echo esc_html__('Interior Color', 'cardealer-helper');?></td></tr>
						<tr><td class="stock_number"><?php echo esc_html__('Stock Number', 'cardealer-helper');?></td></tr>
						<tr><td class="vin_number"><?php echo esc_html__('Vin Number', 'cardealer-helper');?></td></tr>
						<tr><td class="features_options"><div><?php echo esc_html__('Features & Options', 'cardealer-helper');?></div></td></tr>
					</table>
				</div>
				<div class="table-scroll modal-body" id="getCode">
					<div id="sortable" style="width:<?php echo esc_attr($num_of_cars * 258);?>px;">
						<?php
							for( $cols=1; $cols <= $num_of_cars; $cols++ ) {
							$car_id = $carIds[ $cols - 1 ];
							$carlink = get_permalink($car_id);
							?>
							<div class="compare-list compare-datatable" data-id="<?php echo esc_attr($car_id);?>">	
								<table class="compare-list compare-datatable" width="100%" border="0" cellspacing="0" cellpadding="0">
									<tbody>
										<tr class="delete">
											<td class="<?php echo ($cols%2 == 0)? 'even': 'odd';?>" data-id="<?php echo esc_attr($car_id);?>">	
												<a href="javascript:void(0)" data-car_id="<?php echo esc_attr($car_id);?>" class="drop_item"><span class="remove">x</span></a>
											</td>
										</tr>
										<tr class="image">
											<td class="<?php echo ($cols%2 == 0)? 'even': 'odd';?>" data-id="<?php echo esc_attr($car_id);?>">
												<a href="<?php echo esc_url($carlink);?>"> 
													<?php echo (function_exists('cardealer_get_cars_image'))?cardealer_get_cars_image('car_thumbnail', $car_id):''; ?>
												</a>
											</td>
										</tr>
										<tr class="price car-item">
											<td class="<?php echo ($cols%2 == 0)? 'even': 'odd';?>" data-id="<?php echo esc_attr($car_id);?>">
												<?php cardealer_car_price_html('', $car_id);?>
											</td>
										</tr>
										<tr class="year">
											<td class="<?php echo ($cols%2 == 0)? 'even': 'odd';?>" data-id="<?php echo esc_attr($car_id);?>">
											<?php
												$car_year = wp_get_post_terms($car_id, 'car_year');
												echo (!isset($car_year) || empty($car_year))? '&nbsp;' : $car_year[0]-> name;
											?>
											</td>
										</tr>
										<tr class="make">
											<td class="<?php echo ($cols%2 == 0)? 'even': 'odd';?>" data-id="<?php echo esc_attr($car_id);?>">
												<?php 
												$car_make = wp_get_post_terms($car_id, 'car_make');
												echo (!isset($car_make) || empty($car_make))? '&nbsp;' : $car_make[0]-> name;
												?>
											</td>
										</tr>
										<tr class="model">
											<td class="<?php echo ($cols%2 == 0)? 'even': 'odd';?>" data-id="<?php echo esc_attr($car_id);?>">
												<?php 
													$car_model = wp_get_post_terms($car_id, 'car_model');
													echo (!isset($car_model) || empty($car_model))? '&nbsp;' : $car_model[0]-> name;
												?>
											</td>
										</tr>
										<tr class="body_style">
											<td class="<?php echo ($cols%2 == 0)? 'even': 'odd';?>" data-id="<?php echo esc_attr($car_id);?>">
												<?php 
													$car_body_style = wp_get_post_terms($car_id, 'car_body_style');
													echo (!isset($car_body_style) || empty($car_body_style))? '&nbsp;' : $car_body_style[0]-> name;
												?>
											</td>
										</tr>
										<tr class="mileage">
											<td class="<?php echo ($cols%2 == 0)? 'even': 'odd';?>" data-id="<?php echo esc_attr($car_id);?>">
												<?php 
													$car_mileage = wp_get_post_terms($car_id, 'car_mileage');
													echo (!isset($car_mileage) || empty($car_mileage))? '&nbsp;' : $car_mileage[0]-> name;
												?>
											</td>
										</tr>
										<tr class="fuel_economy">	
											<td class="<?php echo ($cols%2 == 0)? 'even': 'odd';?>" data-id="<?php echo esc_attr($car_id);?>">
												<?php
													$car_fuel_economy = wp_get_post_terms($car_id, 'car_fuel_economy');
													echo (!isset($car_fuel_economy) || empty($car_fuel_economy))? '&nbsp;' : $car_fuel_economy[0]-> name;
												?>
											</td>
										</tr>
										<tr class="transmission">
											<td class="<?php echo ($cols%2 == 0)? 'even': 'odd';?>" data-id="<?php echo esc_attr($car_id);?>">
												<?php 
													$car_transmission = wp_get_post_terms($car_id, 'car_transmission');
													echo (!isset($car_transmission) || empty($car_transmission))? '&nbsp;' : $car_transmission[0]-> name;
												?>
											</td>
										</tr>
										<tr class="condition">
											<td class="<?php echo ($cols%2 == 0)? 'even': 'odd';?>" data-id="<?php echo esc_attr($car_id);?>">
												<?php 
													$car_condition = wp_get_post_terms($car_id, 'car_condition');
													echo (!isset($car_condition) || empty($car_condition))? '&nbsp;' : $car_condition[0]-> name;
												?>
											</td>
										</tr>
										<tr class="drivetrain">
											<td class="<?php echo ($cols%2 == 0)? 'even': 'odd';?>" data-id="<?php echo esc_attr($car_id);?>">
												<?php 
													$car_drivetrain = wp_get_post_terms($car_id, 'car_drivetrain');
													echo (!isset($car_drivetrain) || empty($car_drivetrain))? '&nbsp;' : $car_drivetrain[0]-> name;
												?>
											</td>
										</tr>
										<tr class="engine">
											<td class="<?php echo ($cols%2 == 0)? 'even': 'odd';?>" data-id="<?php echo esc_attr($car_id);?>">
												<?php
													$car_engine = wp_get_post_terms($car_id, 'car_engine');
													echo (!isset($car_engine) || empty($car_engine))? '&nbsp;' : $car_engine[0]-> name;
												?>
											</td>
										</tr>
										<tr class="exterior_color">
											<td class="<?php echo ($cols%2 == 0)? 'even': 'odd';?>" data-id="<?php echo esc_attr($car_id);?>">
												<?php	
													$car_exterior_color = wp_get_post_terms($car_id, 'car_exterior_color');
													echo (!isset($car_exterior_color) || empty($car_exterior_color))? '&nbsp;' : $car_exterior_color[0]-> name;
												?>
											</td>
										</tr>
										<tr class="interior_color">
											<td class="<?php echo ($cols%2 == 0)? 'even': 'odd';?>" data-id="<?php echo esc_attr($car_id);?>">
												<?php 
													$car_interior_color = wp_get_post_terms($car_id, 'car_interior_color');
													echo (!isset($car_interior_color) || empty($car_interior_color))? '&nbsp;' : $car_interior_color[0]-> name;
												?>
											</td>
										</tr>
										<tr class="stock_number">
											<td class="<?php echo ($cols%2 == 0)? 'even': 'odd';?>" data-id="<?php echo esc_attr($car_id);?>">
												<?php 
													$car_stock_number = wp_get_post_terms($car_id, 'car_stock_number');
													echo (!isset($car_stock_number) || empty($car_stock_number))? '&nbsp;' : $car_stock_number[0]-> name;
												?>
											</td>
										</tr>
										<tr class="vin_number">
											<td class="<?php echo ($cols%2 == 0)? 'even': 'odd';?>" data-id="<?php echo esc_attr($car_id);?>">
												<?php 
													$car_vin_number = wp_get_post_terms($car_id, 'car_vin_number');
													echo (!isset($car_vin_number) || empty($car_vin_number))? '&nbsp;' : $car_vin_number[0]-> name;
												?>
											</td>
										</tr>
										<tr class="features_options">
											<td class="<?php echo ($cols%2 == 0)? 'even': 'odd';?>" data-id="<?php echo esc_attr($car_id);?>">
												<div>
													<?php 
														$car_features_options = wp_get_post_terms($car_id, 'car_features_options');
														$json  = json_encode($car_features_options); // Conver Obj to Array
														$car_features_options = json_decode($json, true); // Conver Obj to Array
														$name_array = array_map(function ($options) {return $options['name'];}, (array) $car_features_options); // get all name term array
														$options = implode(',', $name_array);
														echo (!isset($options) || empty($options))? '&nbsp;' : $options;
													?>
												</div>
											</td>
										</tr>
									  </tbody>
								</table>
							</div>
					<?php 	}?>
					</div>
				</div>
			</div>	
		</div>
	<?php
	}
	die;
}
?>