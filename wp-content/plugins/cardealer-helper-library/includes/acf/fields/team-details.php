<?php 
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(apply_filters('cardealer_acf_team_details', array (
	'key' => 'group_575eac21bbfd0',
	'title' => esc_html__('Team Details', 'cardealer-helper'),
	'fields' => array (
		array (
			'key' => 'field_575eac8624994',
			'label' => esc_html__('General Details', 'cardealer-helper'),
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
			'key' => 'field_575eac3024992',
			'label' => esc_html__('Designation', 'cardealer-helper'),
			'name' => 'designation',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-designation acf_field_name-designation acf_field_name-designation',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_575eac3e24993',
			'label' => esc_html__('Social Profiles', 'cardealer-helper'),
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
			'key' => 'field_575eac9a24995',
			'label' => esc_html__('Facebook', 'cardealer-helper'),
			'name' => 'facebook',
			'type' => 'url',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-facebook acf_field_name-facebook acf_field_name-facebook',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
		),
		array (
			'key' => 'field_575eacbb24996',
			'label' => esc_html__('Twitter', 'cardealer-helper'),
			'name' => 'twitter',
			'type' => 'url',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-twitter acf_field_name-twitter acf_field_name-twitter',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
		),
		array (
			'key' => 'field_575eace224999',
			'label' => esc_html__('Pinterest', 'cardealer-helper'),
			'name' => 'pinterest',
			'type' => 'url',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-pinterest acf_field_name-pinterest acf_field_name-pinterest',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
		),
		array (
			'key' => 'field_575eacf32499a',
			'label' => esc_html__('Behance', 'cardealer-helper'),
			'name' => 'behance',
			'type' => 'url',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'acf_field_name-behance acf_field_name-behance acf_field_name-behance',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'teams',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
	'menu_item_level' => 'all',
	'modified' => 1475219820,
)));

endif;
?>