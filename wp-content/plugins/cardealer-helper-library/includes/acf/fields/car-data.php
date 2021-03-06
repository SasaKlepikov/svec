<?php
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group( apply_filters('cardealer_acf_car_data', array (
	'key' => 'group_588f1cea78c99',
	'title' => esc_html__('Car Data','cardealer-helper'),
	'fields' => array (
		array (
			'key' => 'field_588f325719c26',
			'label' => esc_html__('Attributes','cardealer-helper'),
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-attributes acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name-',
				'id' => '',
			),
			'placement' => 'left',
			'endpoint' => 0,
		),
		array (
			'key' => 'field_588f3d44b8378',
			'label' => esc_html__('Stock Number','cardealer-helper'),
			'name' => 'stock_number',
			'type' => 'taxonomy',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-stock_number acf_field_name-stock_number acf_field_name-stock_number acf_field_name-stock_number acf_field_name-stock_number acf_field_name-stock_number acf_field_name-stock_number acf_field_name-stock_number acf_field_name-stock_number acf_field_name-stock_number',
				'id' => '',
			),
			'taxonomy' => 'car_stock_number',
			'field_type' => 'select',
			'multiple' => 0,
			'allow_null' => 0,
			'return_format' => 'id',
			'add_term' => 1,
			'load_terms' => 1,
			'save_terms' => 1,
		),
		array (
			'key' => 'field_588f3dbfb8379',
			'label' => esc_html__('VIN Number','cardealer-helper'),
			'name' => 'vin_number',
			'type' => 'taxonomy',
			'instructions' => wp_kses( __('<strong>IMPORTANT: </strong>We recommend that you should provide VIN Number. We considered VIN Number as uniq entity in import process. So if you donot enter VIN Number, then duplicate entry may occur during import process.', 'cardealer-helper'), array('strong'=> array() ) ),
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-vin_number acf_field_name-vin_number acf_field_name-vin_number acf_field_name-vin_number acf_field_name-vin_number acf_field_name-vin_number acf_field_name-vin_number acf_field_name-vin_number acf_field_name-vin_number acf_field_name-vin_number acf_field_name-vin_number',
				'id' => '',
			),
			'taxonomy' => 'car_vin_number',
			'field_type' => 'select',
			'multiple' => 0,
			'allow_null' => 0,
			'return_format' => 'id',
			'add_term' => 1,
			'load_terms' => 1,
			'save_terms' => 1,
		),
		array (
			'key' => 'field_588f336aabaa6',
			'label' => esc_html__('Year','cardealer-helper'),
			'name' => 'year',
			'type' => 'taxonomy',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-year acf_field_name-year acf_field_name-year acf_field_name-year acf_field_name-year acf_field_name-year acf_field_name-year acf_field_name-year acf_field_name-year acf_field_name-year acf_field_name-year acf_field_name-year acf_field_name-year',
				'id' => '',
			),
			'taxonomy' => 'car_year',
			'field_type' => 'select',
			'multiple' => 0,
			'allow_null' => 0,
			'return_format' => 'id',
			'add_term' => 1,
			'load_terms' => 1,
			'save_terms' => 1,
		),
		array (
			'key' => 'field_588f3b69b836f',
			'label' => esc_html__('Make','cardealer-helper'),
			'name' => 'make',
			'type' => 'taxonomy',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-make acf_field_name-make acf_field_name-make acf_field_name-make acf_field_name-make acf_field_name-make acf_field_name-make acf_field_name-make acf_field_name-make acf_field_name-make',
				'id' => '',
			),
			'taxonomy' => 'car_make',
			'field_type' => 'select',
			'multiple' => 0,
			'allow_null' => 0,
			'return_format' => 'id',
			'add_term' => 1,
			'load_terms' => 1,
			'save_terms' => 1,
		),
		array (
			'key' => 'field_588f3478c01ca',
			'label' => esc_html__('Model','cardealer-helper'),
			'name' => 'model',
			'type' => 'taxonomy',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-model acf_field_name-model acf_field_name-model acf_field_name-model acf_field_name-model acf_field_name-model acf_field_name-model acf_field_name-model acf_field_name-model acf_field_name-model acf_field_name-model',
				'id' => '',
			),
			'taxonomy' => 'car_model',
			'field_type' => 'select',
			'multiple' => 0,
			'allow_null' => 0,
			'return_format' => 'id',
			'add_term' => 1,
			'load_terms' => 1,
			'save_terms' => 1,
		),
		array (
			'key' => 'field_59071ef8356ec',
			'label' => esc_html__('Trim','cardealer-helper'),
			'name' => 'trim',
			'type' => 'taxonomy',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-trim acf_field_name-trim acf_field_name-trim acf_field_name-trim acf_field_name-trim acf_field_name-trim',
				'id' => '',
			),
			'taxonomy' => 'car_trim',
			'field_type' => 'select',
			'allow_null' => 1,
			'add_term' => 1,
			'save_terms' => 1,
			'load_terms' => 1,
			'return_format' => 'id',
			'multiple' => 0,
		),
		array (
			'key' => 'field_588f3c34b8372',
			'label' => esc_html__('Condition','cardealer-helper'),
			'name' => 'condition',
			'type' => 'taxonomy',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-condition acf_field_name-condition acf_field_name-condition acf_field_name-condition acf_field_name-condition acf_field_name-condition acf_field_name-condition acf_field_name-condition acf_field_name-condition',
				'id' => '',
			),
			'taxonomy' => 'car_condition',
			'field_type' => 'select',
			'multiple' => 0,
			'allow_null' => 0,
			'return_format' => 'id',
			'add_term' => 1,
			'load_terms' => 1,
			'save_terms' => 1,
		),
		array (
			'key' => 'field_58903183b5d5f',
			'label' => esc_html__('Body Style','cardealer-helper'),
			'name' => 'body_style',
			'type' => 'taxonomy',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-body_style acf_field_name-body_style acf_field_name-body_style acf_field_name-body_style acf_field_name-body_style acf_field_name-body_style acf_field_name-body_style acf_field_name-body_style acf_field_name-body_style',
				'id' => '',
			),
			'taxonomy' => 'car_body_style',
			'field_type' => 'select',
			'multiple' => 0,
			'allow_null' => 0,
			'return_format' => 'id',
			'add_term' => 1,
			'load_terms' => 1,
			'save_terms' => 1,
		),
		array (
			'key' => 'field_588f3bf4b8371',
			'label' => esc_html__('Transmission','cardealer-helper'),
			'name' => 'transmission',
			'type' => 'taxonomy',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-transmission acf_field_name-transmission acf_field_name-transmission acf_field_name-transmission acf_field_name-transmission acf_field_name-transmission acf_field_name-transmission acf_field_name-transmission acf_field_name-transmission acf_field_name-transmission',
				'id' => '',
			),
			'taxonomy' => 'car_transmission',
			'field_type' => 'select',
			'multiple' => 0,
			'allow_null' => 0,
			'return_format' => 'id',
			'add_term' => 1,
			'load_terms' => 1,
			'save_terms' => 1,
		),
		array (
			'key' => 'field_588f3c72b8374',
			'label' => esc_html__('Engine','cardealer-helper'),
			'name' => 'engine',
			'type' => 'taxonomy',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-engine acf_field_name-engine acf_field_name-engine acf_field_name-engine acf_field_name-engine acf_field_name-engine acf_field_name-engine acf_field_name-engine acf_field_name-engine acf_field_name-engine',
				'id' => '',
			),
			'taxonomy' => 'car_engine',
			'field_type' => 'select',
			'multiple' => 0,
			'allow_null' => 0,
			'return_format' => 'id',
			'add_term' => 1,
			'load_terms' => 1,
			'save_terms' => 1,
		),
		array (
			'key' => 'field_588f3c52b8373',
			'label' => esc_html__('Drivetrain','cardealer-helper'),
			'name' => 'drivetrain',
			'type' => 'taxonomy',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-drivetrain acf_field_name-drivetrain acf_field_name-drivetrain acf_field_name-drivetrain acf_field_name-drivetrain acf_field_name-drivetrain acf_field_name-drivetrain acf_field_name-drivetrain acf_field_name-drivetrain acf_field_name-drivetrain',
				'id' => '',
			),
			'taxonomy' => 'car_drivetrain',
			'field_type' => 'select',
			'multiple' => 0,
			'allow_null' => 0,
			'return_format' => 'id',
			'add_term' => 1,
			'load_terms' => 1,
			'save_terms' => 1,
		),
		array (
			'key' => 'field_59071e45356eb',
			'label' => esc_html__('Fuel Type','cardealer-helper'),
			'name' => 'fuel_type',
			'type' => 'taxonomy',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-fuel_type acf_field_name-fuel_type acf_field_name-fuel_type acf_field_name-fuel_type acf_field_name-fuel_type acf_field_name-fuel_type',
				'id' => '',
			),
			'taxonomy' => 'car_fuel_type',
			'field_type' => 'select',
			'allow_null' => 1,
			'add_term' => 1,
			'save_terms' => 1,
			'load_terms' => 1,
			'return_format' => 'id',
			'multiple' => 0,
		),
		array (
			'key' => 'field_588f3c8cb8375',
			'label' => esc_html__('Fuel Economy','cardealer-helper'),
			'name' => 'fuel_economy',
			'type' => 'taxonomy',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-fuel_economy acf_field_name-fuel_economy acf_field_name-fuel_economy acf_field_name-fuel_economy acf_field_name-fuel_economy acf_field_name-fuel_economy acf_field_name-fuel_economy acf_field_name-fuel_economy acf_field_name-fuel_economy acf_field_name-fuel_economy',
				'id' => '',
			),
			'taxonomy' => 'car_fuel_economy',
			'field_type' => 'select',
			'allow_null' => 0,
			'add_term' => 1,
			'save_terms' => 1,
			'load_terms' => 1,
			'return_format' => 'id',
			'multiple' => 0,
		),
		array (
			'key' => 'field_588f3bc5b8370',
			'label' => esc_html__('Mileage','cardealer-helper'),
			'name' => 'mileage',
			'type' => 'taxonomy',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-mileage acf_field_name-mileage acf_field_name-mileage acf_field_name-mileage acf_field_name-mileage acf_field_name-mileage acf_field_name-mileage acf_field_name-mileage acf_field_name-mileage',
				'id' => '',
			),
			'taxonomy' => 'car_mileage',
			'field_type' => 'select',
			'multiple' => 0,
			'allow_null' => 0,
			'return_format' => 'id',
			'add_term' => 1,
			'load_terms' => 1,
			'save_terms' => 1,
		),
		array (
			'key' => 'field_588f3cb6b8376',
			'label' => esc_html__('Exterior Color','cardealer-helper'),
			'name' => 'exterior_color',
			'type' => 'taxonomy',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-exterior_color acf_field_name-exterior_color acf_field_name-exterior_color acf_field_name-exterior_color acf_field_name-exterior_color acf_field_name-exterior_color acf_field_name-exterior_color acf_field_name-exterior_color acf_field_name-exterior_color',
				'id' => '',
			),
			'taxonomy' => 'car_exterior_color',
			'field_type' => 'select',
			'multiple' => 0,
			'allow_null' => 0,
			'return_format' => 'id',
			'add_term' => 1,
			'load_terms' => 1,
			'save_terms' => 1,
		),
		array (
			'key' => 'field_588f3d25b8377',
			'label' => esc_html__('Interior Color','cardealer-helper'),
			'name' => 'interior_color',
			'type' => 'taxonomy',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-interior_color acf_field_name-interior_color acf_field_name-interior_color acf_field_name-interior_color acf_field_name-interior_color acf_field_name-interior_color acf_field_name-interior_color acf_field_name-interior_color acf_field_name-interior_color',
				'id' => '',
			),
			'taxonomy' => 'car_interior_color',
			'field_type' => 'select',
			'multiple' => 0,
			'allow_null' => 0,
			'return_format' => 'id',
			'add_term' => 1,
			'load_terms' => 1,
			'save_terms' => 1,
		),
		array (
			'key' => 'field_588f1d6463719',
			'label' => esc_html__('Car Images','cardealer-helper'),
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name-',
				'id' => '',
			),
			'placement' => 'left',
			'endpoint' => 0,
		),
		array (
			'key' => 'field_588f1cfb63718',
			'label' => esc_html__('Car Images','cardealer-helper'),
			'name' => 'car_images',
			'type' => 'gallery',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-car_images acf_field_name-car_images acf_field_name-car_images acf_field_name-car_images acf_field_name-car_images acf_field_name-car_images acf_field_name-car_images',
				'id' => '',
			),
			'library' => 'all',
			'min' => '',
			'max' => '',
			'min_width' => '',
			'min_height' => '',
			'min_size' => '',
			'max_width' => '',
			'max_height' => '',
			'max_size' => '',
			'mime_types' => '',
			'insert' => 'append',
		),
		array (
			'key' => 'field_588f1fd05c12e',
			'label' => esc_html__('Regular price','cardealer-helper'),
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name-',
				'id' => '',
			),
			'placement' => 'left',
			'endpoint' => 0,
		),
		array (
			'key' => 'field_588f20535c12f',
			'label' => esc_html__('Regular price','cardealer-helper'),
			'name' => 'regular_price',
			'type' => 'number',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-regular_price acf_field_name-regular_price acf_field_name-regular_price acf_field_name-regular_price acf_field_name-regular_price acf_field_name-regular_price acf_field_name-regular_price',
				'id' => '',
			),
			'default_value' => '',
			'min' => '',
			'max' => '',
			'step' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array (
			'key' => 'field_588f205d5c130',
			'label' => esc_html__('Sale price','cardealer-helper'),
			'name' => 'sale_price',
			'type' => 'number',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-sale_price acf_field_name-sale_price acf_field_name-sale_price acf_field_name-sale_price acf_field_name-sale_price acf_field_name-sale_price acf_field_name-sale_price',
				'id' => '',
			),
			'default_value' => '',
			'min' => '',
			'max' => '',
			'step' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array (
			'key' => 'field_5894116f47f97',
			'label' => esc_html__('Tax Label','cardealer-helper'),
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name-',
				'id' => '',
			),
			'placement' => 'left',
			'endpoint' => 0,
		),
		array (
			'key' => 'field_589410fd47f96',
			'label' => esc_html__('Tax Label','cardealer-helper'),
			'name' => 'tax_label',
			'type' => 'text',
			'instructions' => esc_html__('Tax Label (below the price) on Listing Page.', 'cardealer-helper'),
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-tax_label acf_field_name-tax_label acf_field_name-tax_label acf_field_name-tax_label acf_field_name-tax_label acf_field_name-tax_label acf_field_name-tax_label',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array (
			'key' => 'field_588f21725c132',
			'label' => esc_html__('Fuel Efficiency','cardealer-helper'),
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name-',
				'id' => '',
			),
			'placement' => 'left',
			'endpoint' => 0,
		),
		array (
			'key' => 'field_588f217f5c133',
			'label' => esc_html__('City MPG','cardealer-helper'),
			'name' => 'city_mpg',
			'type' => 'number',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-city_mpg acf_field_name-city_mpg acf_field_name-city_mpg acf_field_name-city_mpg acf_field_name-city_mpg acf_field_name-city_mpg acf_field_name-city_mpg',
				'id' => '',
			),
			'default_value' => '',
			'min' => '',
			'max' => '',
			'step' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array (
			'key' => 'field_588f21a75c134',
			'label' => esc_html__('Highway MPG','cardealer-helper'),
			'name' => 'highway_mpg',
			'type' => 'number',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-highway_mpg acf_field_name-highway_mpg acf_field_name-highway_mpg acf_field_name-highway_mpg acf_field_name-highway_mpg acf_field_name-highway_mpg acf_field_name-highway_mpg',
				'id' => '',
			),
			'default_value' => '',
			'min' => '',
			'max' => '',
			'step' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array (
			'key' => 'field_588f23848a25d',
			'label' => esc_html__('Brochure Upload','cardealer-helper'),
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name-',
				'id' => '',
			),
			'placement' => 'left',
			'endpoint' => 0,
		),
		array (
			'key' => 'field_588f23918a25e',
			'label' => esc_html__('Brochure','cardealer-helper'),
			'name' => 'pdf_file',
			'type' => 'file',
			'instructions' => esc_html__('Upload brochure here in PDF format only.', 'cardealer-helper'),
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-pdf_file acf_field_name-pdf_file acf_field_name-pdf_file acf_field_name-pdf_file acf_field_name-pdf_file acf_field_name-pdf_file acf_field_name-pdf_file',
				'id' => '',
			),
			'return_format' => 'array',
			'library' => 'all',
			'min_size' => '',
			'max_size' => '',
			'mime_types' => 'pdf',
		),
		array (
			'key' => 'field_588f245127b5f',
			'label' => esc_html__('Video','cardealer-helper'),
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name-',
				'id' => '',
			),
			'placement' => 'left',
			'endpoint' => 0,
		),
		array (
			'key' => 'field_588f246427b60',
			'label' => esc_html__('Video Link','cardealer-helper'),
			'name' => 'video_link',
			'type' => 'url',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-video_link acf_field_name-video_link acf_field_name-video_link acf_field_name-video_link acf_field_name-video_link acf_field_name-video_link acf_field_name-video_link',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
		),
		array (
			'key' => 'field_590720a5e74bc',
			'label' => esc_html__('Car Status','cardealer-helper'),
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name- acf_field_name-',
				'id' => '',
			),
			'placement' => 'left',
			'endpoint' => 0,
		),
		array (
			'key' => 'field_59071f59356ed',
			'label' => esc_html__('Car Status','cardealer-helper'),
			'name' => 'car_status',
			'type' => 'radio',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-car_status acf_field_name-car_status acf_field_name-car_status acf_field_name-car_status acf_field_name-car_status acf_field_name-car_status',
				'id' => '',
			),
			'choices' => array (
				'sold' => 'Sold',
				'unsold' => 'UnSold',
			),
			'allow_null' => 0,
			'other_choice' => 0,
			'save_other_choice' => 0,
			'default_value' => 'unsold',
			'layout' => 'horizontal',
			'return_format' => 'value',
		),
		array (
			'key' => 'field_5950c15c3bb72',
			'label' => esc_html__('Vehicle Review Stamps','cardealer-helper'),
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-vehicle_review_stamps acf_field_name- acf_field_name-',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
		),
		array (
			'key' => 'field_5950beb2e45fc',
			'label' => esc_html__('Vehicle Review Stamps','cardealer-helper'),
			'name' => 'vehicle_review_stamps',
			'type' => 'taxonomy',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-vehicle_review_stamps acf_field_name-vehicle_review_stamps acf_field_name-vehicle_review_stamps',
				'id' => '',
			),
			'taxonomy' => 'car_vehicle_review_stamps',
			'field_type' => 'checkbox',
			'allow_null' => 1,
			'add_term' => 0,
			'save_terms' => 1,
			'load_terms' => 1,
			'return_format' => 'object',
			'multiple' => 0,
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'cars',
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
	'modified' => 1498466136,
)));

endif;
?>