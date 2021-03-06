<?php 
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(apply_filters('cardealer_acf_schedule_test_drive', array (
	'key' => 'group_587f676d12655',
	'title' => esc_html__('Schedule Test Drive', 'cardealer-helper'),
	'fields' => array (
		array (
			'key' => 'field_591ed59208259',
			'label' => esc_html__('User Information', 'cardealer-helper'),
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name- acf_field_name- acf_field_name-',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
		),
		array (
			'key' => 'field_587f6787ca5f4',
			'label' => esc_html__('First Name', 'cardealer-helper'),
			'name' => 'first_name',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-first_name acf_field_name-first_name acf_field_name-first_name acf_field_name-first_name',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'readonly' => 1,
			'disabled' => 0,
		),
		array (
			'key' => 'field_587f679aca5f5',
			'label' => esc_html__('Last Name', 'cardealer-helper'),
			'name' => 'last_name',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-last_name acf_field_name-last_name acf_field_name-last_name acf_field_name-last_name',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'readonly' => 1,
			'disabled' => 0,
		),
		array (
			'key' => 'field_587f679fca5f6',
			'label' => esc_html__('Email', 'cardealer-helper'),
			'name' => 'email',
			'type' => 'email',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-email acf_field_name-email acf_field_name-email acf_field_name-email',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'readonly' => 1,
		),
		array (
			'key' => 'field_587f67acca5f7',
			'label' => esc_html__('Mobile', 'cardealer-helper'),
			'name' => 'mobile',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-mobile acf_field_name-mobile acf_field_name-mobile acf_field_name-mobile',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'readonly' => 1,
			'disabled' => 0,
		),
		array (
			'key' => 'field_587f67b3ca5f8',
			'label' => esc_html__('Address', 'cardealer-helper'),
			'name' => 'address',
			'type' => 'textarea',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-address acf_field_name-address acf_field_name-address acf_field_name-address',
				'id' => '',
			),
			'default_value' => '',
			'new_lines' => 'wpautop',
			'maxlength' => '',
			'placeholder' => '',
			'rows' => 4,
			'readonly' => 1,
			'disabled' => 0,
		),
		array (
			'key' => 'field_587f67c1ca5f9',
			'label' => esc_html__('State', 'cardealer-helper'),
			'name' => 'state',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-state acf_field_name-state acf_field_name-state acf_field_name-state',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'readonly' => 1,
			'disabled' => 0,
		),
		array (
			'key' => 'field_587f67c8ca5fa',
			'label' => esc_html__('Zip', 'cardealer-helper'),
			'name' => 'zip',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-zip acf_field_name-zip acf_field_name-zip acf_field_name-zip',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'readonly' => 1,
			'disabled' => 0,
		),
		array (
			'key' => 'field_587f67d3ca5fb',
			'label' => esc_html__('Preferred Contact', 'cardealer-helper'),
			'name' => 'preferred_contact',
			'type' => 'radio',
			'instructions' => esc_html__('Preferred Contact Email or Phone', 'cardealer-helper'),
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-preferred_contact acf_field_name-preferred_contact acf_field_name-preferred_contact acf_field_name-preferred_contact',
				'id' => '',
			),
			'layout' => 'vertical',
			'choices' => array (
				'email' => 'Email',
				'phone' => 'Phone',
			),
			'default_value' => '',
			'other_choice' => 0,
			'save_other_choice' => 0,
			'allow_null' => 0,
			'return_format' => 'value',
		),
		array (
			'key' => 'field_587f6818ca5fc',
			'label' => esc_html__('Test Drive ?', 'cardealer-helper'),
			'name' => 'test_drive',
			'type' => 'radio',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-test_drive acf_field_name-test_drive acf_field_name-test_drive acf_field_name-test_drive',
				'id' => '',
			),
			'layout' => 'vertical',
			'choices' => array (
				'yes' => 'Yes',
				'no' => 'No',
			),
			'default_value' => 'yes',
			'other_choice' => 0,
			'save_other_choice' => 0,
			'allow_null' => 0,
			'return_format' => 'value',
		),
		array (
			'key' => 'field_587f687eca5fd',
			'label' => esc_html__('Date', 'cardealer-helper'),
			'name' => 'date',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_587f6818ca5fc',
						'operator' => '==',
						'value' => 'yes',
					),
				),
			),
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-date acf_field_name-date acf_field_name-date acf_field_name-date',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'readonly' => 1,
			'disabled' => 0,
		),
		array (
			'key' => 'field_587f6926ca5fe',
			'label' => esc_html__('Time', 'cardealer-helper'),
			'name' => 'time',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_587f6818ca5fc',
						'operator' => '==',
						'value' => 'yes',
					),
				),
			),
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-time acf_field_name-time acf_field_name-time acf_field_name-time',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'readonly' => 1,
			'disabled' => 0,
		),
		array (
			'key' => 'field_591ed5bd0825a',
			'label' => esc_html__('Car Information', 'cardealer-helper'),
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name- acf_field_name- acf_field_name-',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
		),
		array (
			'key' => 'field_591ec871dc7f8',
			'label' => esc_html__('Car Year', 'cardealer-helper'),
			'name' => 'car_year_inq',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-car_year_inq acf_field_name-car_year_inq acf_field_name-car_year_inq',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 1,
		),
		array (
			'key' => 'field_591ec8a4dc7f9',
			'label' => esc_html__('Car Make', 'cardealer-helper'),
			'name' => 'car_make_inq',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-car_make_inq acf_field_name-car_make_inq acf_field_name-car_make_inq',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 1,
		),
		array (
			'key' => 'field_591ec8abdc7fa',
			'label' => esc_html__('Car Model', 'cardealer-helper'),
			'name' => 'car_model_inq',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-car_model_inq acf_field_name-car_model_inq acf_field_name-car_model_inq',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 1,
		),
		array (
			'key' => 'field_591ec8b4dc7fb',
			'label' => esc_html__('Car Trim', 'cardealer-helper'),
			'name' => 'car_trim_inq',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-car_trim_inq acf_field_name-car_trim_inq acf_field_name-car_trim_inq',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 1,
		),
		array (
			'key' => 'field_591ec8bedc7fc',
			'label' => esc_html__('VIN Number', 'cardealer-helper'),
			'name' => 'vin_number',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-vin_number acf_field_name-vin_number acf_field_name-vin_number',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 1,
		),
		array (
			'key' => 'field_591ec8c8dc7fd',
			'label' => esc_html__('Stock number', 'cardealer-helper'),
			'name' => 'stock_number',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-stock_number acf_field_name-stock_number acf_field_name-stock_number',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 1,
		),
		array (
			'key' => 'field_591ec8d1dc7fe',
			'label' => esc_html__('Regular Price', 'cardealer-helper'),
			'name' => 'regular_price',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-regular_price acf_field_name-regular_price acf_field_name-regular_price',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 1,
		),
		array (
			'key' => 'field_591ec8dddc7ff',
			'label' => esc_html__('Sale Price', 'cardealer-helper'),
			'name' => 'sale_price',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-sale_price acf_field_name-sale_price acf_field_name-sale_price',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 1,
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'schedule_test_drive',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => array (
		0 => 'the_content',
		1 => 'featured_image',
	),
	'active' => 1,
	'description' => '',
	'menu_item_level' => 'all',
)));

endif;
?>