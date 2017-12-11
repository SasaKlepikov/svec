<?php

// Function For Text Mail Body
function cdhl_get_text_mail_body( $car_id ) {
	$product_plain= '';
	$sale_price = (get_field('sale_price',$car_id))? get_field('sale_price',$car_id) : '&nbsp;';
	$product_plain .= esc_html__('Sale Price: ','cardealer-helper') . $sale_price;
	
	$regular_price =(get_field('regular_price',$car_id))? get_field('regular_price',$car_id) : '&nbsp;';
	$product_plain .= PHP_EOL .esc_html__('Regular Price  : ','cardealer-helper') . $regular_price;
	
	$car_year = wp_get_post_terms($car_id, 'car_year');
	$car_year = (!empty($car_year))? $car_year[0]->name : '&nbsp;';
	$product_plain .= PHP_EOL .esc_html__('Car Year : ','cardealer-helper') . $car_year;
	
	$car_make = wp_get_post_terms($car_id, 'car_make');
	$car_make = (!empty($car_make))? $car_make[0]->name : '&nbsp;';
	$product_plain .= PHP_EOL .esc_html__('Car Make : ','cardealer-helper') . $car_make;
	
	$car_model = wp_get_post_terms($car_id, 'car_model'); 
	$car_model = (!empty($car_model))? $car_model[0]->name : '&nbsp;';
	$product_plain .= PHP_EOL .esc_html__('Car Model : ','cardealer-helper') . $car_model;
	
	$car_body_style = wp_get_post_terms($car_id, 'car_body_style');
	$car_body_style = (!empty($car_body_style))? $car_body_style[0]->name : '&nbsp;';
	$product_plain .= PHP_EOL .esc_html__('Car BodyStyle : ','cardealer-helper') . $car_body_style;
	
	$car_mileage = wp_get_post_terms($car_id, 'car_mileage'); 
	$car_make = (!empty($car_make))? $car_make[0]->name : '&nbsp;';
	$product_plain .= PHP_EOL .esc_html__('Car Mileage : ','cardealer-helper') . (!isset($car_mileage))? '&nbsp;' :$car_mileage[0]-> name; 
	
	$car_fuel_economy = wp_get_post_terms($car_id, 'car_fuel_economy'); 
	$car_fuel_economy = (!empty($car_fuel_economy))? $car_fuel_economy[0]->name : '&nbsp;';
	$product_plain .= esc_html__('Car Fuel Economy : ','cardealer-helper') . $car_fuel_economy;
	
	$car_transmission = wp_get_post_terms($car_id, 'car_transmission'); 
	$car_transmission = (!empty($car_transmission))? $car_transmission[0]->name : '&nbsp;';
	$product_plain .= PHP_EOL .esc_html__('Car Transmission : ','cardealer-helper') . $car_transmission;
	
	$car_condition = wp_get_post_terms($car_id, 'car_condition'); 
	$car_condition = (!empty($car_condition))? $car_condition[0]->name : '&nbsp;';
	$product_plain .= PHP_EOL .esc_html__('Car Condition : ','cardealer-helper') . $car_condition;
	
	$car_drivetrain = wp_get_post_terms($car_id, 'car_drivetrain'); 
	$car_drivetrain = (!empty($car_drivetrain))? $car_drivetrain[0]->name : '&nbsp;';
	$product_plain .= PHP_EOL .esc_html__('Car Drivetrain : ','cardealer-helper') . $car_drivetrain;
	
	$car_engine = wp_get_post_terms($car_id, 'car_engine'); 
	$car_engine = (!empty($car_engine))? $car_engine[0]->name : '&nbsp;';
	$product_plain .= PHP_EOL .esc_html__('Car Engine : ','cardealer-helper') . $car_engine;
	
	$car_exterior_color = wp_get_post_terms($car_id, 'car_exterior_color'); 
	$car_exterior_color = (!empty($car_exterior_color))? $car_exterior_color[0]->name : '&nbsp;';
	$product_plain .= PHP_EOL .esc_html__('Car Exterior Color : ','cardealer-helper') . $car_exterior_color;
	
	$car_interior_color = wp_get_post_terms($car_id, 'car_interior_color'); 
	$car_interior_color = (!empty($car_interior_color))? $car_interior_color[0]->name : '&nbsp;';
	$product_plain .= PHP_EOL .esc_html__('Car Interior Color : ','cardealer-helper') . $car_interior_color;
	
	$car_stock_number = wp_get_post_terms($car_id, 'car_stock_number'); 
	$car_stock_number = (!empty($car_stock_number))? $car_stock_number[0]->name : '&nbsp;';
	$product_plain .= PHP_EOL .esc_html__('Car Stock Number : ','cardealer-helper') . $car_stock_number;
	
	$car_vin_number = wp_get_post_terms($car_id, 'car_vin_number'); 
	$car_vin_number = (!empty($car_vin_number))? $car_vin_number[0]->name : '&nbsp;';
	$product_plain .= PHP_EOL .esc_html__('Car Vin Number : ','cardealer-helper') . $car_vin_number;
	
return $product_plain;
}

// Function For HTML Mail Body
function cdhl_get_html_mail_body( $car_id ) {	
	$product ='';
	$product = '<table class="compare-list compare-datatable" width="100%" border="1" cellspacing="0" cellpadding="0">';
			$product .= '<tbody>';
					$product .= '<tr class="image">';
						$product .= '<td colspan=2 style="text-align:center">';	
						$product .= cardealer_get_cars_image('car_thumbnail', $car_id);
						$product .= '</td>';
					$product .= '</tr>';
					$product .= '<tr class="price">';
						$product .= '<td>';
							$product .= esc_html__('Car Price','cardealer-helper');
						$product .= '</td>';
						$product .= '<td>';
							$sale_price = (get_field('sale_price',$car_id))? get_field('sale_price',$car_id) : '&nbsp;';
							$regular_price = (get_field('regular_price',$car_id))? get_field('regular_price',$car_id) : '&nbsp;';
							$product .= '<span>'.esc_html__('Sale Price: ','cardealer-helper') . $sale_price .'</span><span>&nbsp;&nbsp;'.esc_html__('Regular Price : ','cardealer-helper'). $regular_price .'</span>';
						$product .= '</td>';
					$product .= '</tr>';
					$product .= '<tr class="year">';
						$product .= '<td>';  
							$product .= esc_html__('Car Year','cardealer-helper');
						$product .= '</td>';                            
						$product .= '<td>';                            
						$caryear = wp_get_post_terms($car_id, 'car_year');                            
						$product .= (!isset($caryear))? '&nbsp;' : $caryear[0]->name;
						$product .= '</td>';
					$product .= '</tr>';
					$product .= '<tr class="make">';
						$product .= '<td >';
							 $product .= esc_html__('Car Make','cardealer-helper');
						$product .= '</td >';
						$product .= '<td >';
						$carmake = wp_get_post_terms($car_id, 'car_make');
						$product .= (!isset($carmake))? '&nbsp;' : $carmake[0]->name;
						$product .= '</td>';
					$product .= '</tr>';
					$product .= '<tr class="model">';
						$product .= '<td>';
							$product .= esc_html__('Car Model','cardealer-helper');
						$product .= '</td>';
						$product .= '<td>';
							$carmodel = wp_get_post_terms($car_id, 'car_model'); 
							$product .= (!isset($carmodel))? '&nbsp;' : $carmodel[0]-> name;
							
						$product .= '</td>';
					$product .= '</tr>';
					$product .= '<tr class="body_style">';
						$product .= '<td>';
							$product .= esc_html__('Car BodyStyle','cardealer-helper');
						$product .= '</td>';
						$product .= '<td>';
							$carbodystyle = wp_get_post_terms($car_id, 'car_body_style');  
							$product .= (!isset($carmodel))? '&nbsp;' : $carbodystyle[0]-> name;								
						$product .= '</td>';
					$product .= '</tr>';
					$product .= '<tr class="mileage">';
						$product .= '<td>';
							$product .= esc_html__('Car Mileage','cardealer-helper');
						$product .= '</td>';
						$product .= '<td>';
							$carmileage = wp_get_post_terms($car_id, 'car_mileage'); 
							$product .= (!isset($carmileage))? '&nbsp;' : $carmileage[0]-> name;								
						$product .= '</td>';
					$product .= '</tr>';
					$product .= '<tr class="fuel_economy">';	
						$product .= '<td>';
							$product .= esc_html__('Car Fuel Economy','cardealer-helper');
						$product .= '</td>';
						$product .= '<td>';
							$carfueleconomy = wp_get_post_terms($car_id, 'car_fuel_economy');
							$product .= (!isset($carfueleconomy))? '&nbsp;' : $carfueleconomy[0]-> name;
						$product .= '</td>';
					$product .= '</tr>';
					$product .= '<tr class="transmission">';
						$product .= '<td>';	
							$product .= esc_html__('Car Transmission','cardealer-helper');
						$product .= '</td>';
						$product .= '<td>';
							$cartransmission = wp_get_post_terms($car_id, 'car_transmission');  
							$product .= (!isset($cartransmission))? '&nbsp;' : $cartransmission[0]-> name;
						$product .= '</td>';
					$product .= '</tr>';
					$product .= '<tr class="condition">';
						$product .= '<td>';
							$product .= esc_html__('Car Condition','cardealer-helper');
						$product .= '</td>';
						$product .= '<td>';
							$carcondition = wp_get_post_terms($car_id, 'car_condition');
							$product .= (!isset($carcondition))? '&nbsp;' : $carcondition[0]-> name;
						$product .= '</td>';
					$product .= '</tr>';
					$product .= '<tr class="drivetrain">';
						$product .= '<td>';
							$product .= esc_html__('Car Drivetrain','cardealer-helper');
						$product .= '</td>';
						$product .= '<td>';
							$cardrivetrain = wp_get_post_terms($car_id, 'car_drivetrain');
							$product .= (!isset($cardrivetrain))? '&nbsp;' : $cardrivetrain[0]-> name;
						$product .= '</td>';
					$product .= '</tr>';
					$product .= '<tr class="engine">';
						$product .= '<td>';
							$product .= esc_html__('Car Engine','cardealer-helper');
						$product .= '</td>';
						$product .= '<td>';
							$carengine = wp_get_post_terms($car_id, 'car_engine');
							$car_engine = (!empty($carengine))? $carengine[0]->name : '&nbsp;';
							$product .= esc_html__('Car Engine','cardealer-helper') . $car_engine;                                    
						$product .= '</td>';
					$product .= '</tr>';
					$product .= '<tr class="exterior_color">';
						$product .= '<td>';
							$product .= esc_html__('Car Exterior Color','cardealer-helper');
						$product .= '</td>';
						$product .= '<td>';
							$carexteriorcolor = wp_get_post_terms($car_id, 'car_exterior_color');
							$product .= (!isset($carexteriorcolor))? '&nbsp;' : $carexteriorcolor[0]-> name;
						$product .= '</td>';
					$product .= '</tr>';
					$product .= '<tr class="interior_color">';
						$product .= '<td>';
							$product .= esc_html__('Car Interior Color','cardealer-helper');
						$product .= '</td>';
						$product .= '<td>';
							$carinteriorcolor= wp_get_post_terms($car_id, 'car_interior_color');
							$product .= (!isset($carinteriorcolor))? '&nbsp;' : $carinteriorcolor[0]-> name;
						$product .= '</td>';
					$product .= '</tr>';
					$product .= '<tr class="stock_number">';
						$product .= '<td>';
							$product .= esc_html__('Car Stock Number','cardealer-helper');
						$product .= '</td>';
						$product .= '<td>';
							$carstocknumber = wp_get_post_terms($car_id, 'car_stock_number');
							$product .=  (!isset($carstocknumber))? '&nbsp;' : $carstocknumber[0]-> name;                               
						$product .= '</td>';
					$product .= '</tr>';
					$product .= '<tr class="vin_number">';
						$product .= '<td>';
							$product .= esc_html__('Car Vin Number','cardealer-helper');
						$product .= '</td>';
						$product .= '<td>';
							$carvinnumber = wp_get_post_terms($car_id, 'car_vin_number');
							$product .= (!isset($carvinnumber))? '&nbsp;' : $carvinnumber[0]-> name;                                    
						$product .= '</td>';
					$product .= '</tr>';
				  $product .= '</tbody>';
			$product .= '</table>';
	return $product;
}
?>