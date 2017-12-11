<?php
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(apply_filters('cardealer_acf_pdf_generator', array (
	'key' => 'group_589ac22982773',
	'title' => esc_html__('Vehicle Brochure Generator', 'cardealer-helper'),
	'fields' => array (
		array (
			'key' => 'field_589ac266afdf9',
			'label' => esc_html__('HTML Templates', 'cardealer-helper'),
			'name' => 'html_templates',
			'type' => 'repeater',
			'instructions' => sprintf(
								wp_kses( 
									__('<b>Use <a href="#" class="cd_dialog" data-id="pdf-fields">this</a> fields association in order to make or update vehicle brochure template.</b><div id="pdf-fields" class="variable-content" title="Car Fields Association"><p>%1$s</p></div>', 'cardealer-helper'),
									array( 
										'a' => array( 
											'href'=>array(),
											'class'=>array(),
											'data-id'=>array()
										),
										'div' => array( 
											'title'=>array(),
											'id'=>array(),
											'class'=>array()
										),
										'p' => array(),
										'br' => array(),
										'b' => array(),
									)
								),
								'Car Year : {{year}} <br>Car Make : {{make}}<br>Car Model : {{model}} <br>Regular Price : {{regular_price}} <br>Currency Symbol : {{currency_symbol}} <br>Sale Price : {{sale_price}} <br>Body Style : {{body_style}} <br>Condition : {{condition}} <br>Mileage : {{mileage}} <br>Transmission : {{transmission}} <br>Drivetrain : {{drivetrain}} <br>Engine : {{engine}}<br>Fuel Type : {{fuel_type}} <br>Fuel Economy : {{fuel_economy}}<br>Trim : {{trim}} <br>Exterior Color : {{exterior_color}}<br>Interior Color : {{interior_color}} <br>Stock : {{stock_number}}<br>VIN : {{vin_number}} <br>Features And Options : {{features_options}}<br> Highway MPG : {{high_waympg}} <br> City MPG : {{city_mpg}}<br>'),
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name- acf_field_name-html_templates acf_field_name- acf_field_name-html_templates acf_field_name-html_templates acf_field_name-html_templates acf_field_name-html_templates acf_field_name-html_templates',
				'id' => '',
			),
			'min' => 0,
			'max' => 0,
			'layout' => 'block',
			'button_label' => '',
			'collapsed' => '',
			'sub_fields' => array (
				array (
					'key' => 'field_589ac54dafdfa',
					'label' => esc_html__('Templates Title', 'cardealer-helper'),
					'name' => 'templates_title',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => 'acf_field_name-templates_title acf_field_name-templates_title acf_field_name-templates_title acf_field_name-templates_title acf_field_name-templates_title',
						'id' => '',
					),
					'default_value' => '',
					'maxlength' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
				),
				array (
					'key' => 'field_589ac571afdfb',
					'label' => esc_html__('Template Content', 'cardealer-helper'),
					'name' => 'template_content',
					'type' => 'wysiwyg',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => 'acf_field_name-template_content acf_field_name-template_content acf_field_name-template_content acf_field_name-template_content acf_field_name-template_content',
						'id' => '',
					),
					'tabs' => 'all',
					'toolbar' => 'full',
					'media_upload' => 1,
					'default_value' => '',
					'delay' => 0,
				),
			),
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'pdf_generator',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'field',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
	'menu_item_level' => 'all',
)));

endif;
?>