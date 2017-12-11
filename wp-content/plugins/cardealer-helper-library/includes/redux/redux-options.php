<?php
if (!class_exists('Redux')) {
	return;
}
// This is your option name where all the Redux data is stored.
$opt_name = CDHL_THEME_OPTIONS_NAME;
// This line is only for altering the demo. Can be easily removed.
$opt_name = apply_filters('redux_demo/opt_name', $opt_name);
/*
 *
 * --> Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
 *
 */
// Background Patterns Reader
$cdhl_patterns_path = get_template_directory() . '/images/color-customizer/pattern/';
$cdhl_patterns_url = get_template_directory_uri() . '/images/color-customizer/pattern/';
$cdhl_patterns = array();
if (is_dir($cdhl_patterns_path)) {
	if ($cdhl_patterns_dir = opendir($cdhl_patterns_path)) {
		$cdhl_patterns = array();
		while (( $cdhl_patterns_file = readdir($cdhl_patterns_dir) ) !== false) {
			if (stristr($cdhl_patterns_file, '.png') !== false || stristr($cdhl_patterns_file, '.jpg') !== false) {
				$name = explode('.', $cdhl_patterns_file);
				$name = str_replace('.' . end($name), '', $cdhl_patterns_file);
				$cdhl_patterns[] = array(
					'alt' => $name,
					'img' => $cdhl_patterns_url . $cdhl_patterns_file,
					'height' => 25,
					'width' => 100,
				);
			}
		}
	}
}
/* * **********************************************************
 * Banner Images
 * ********************************************************** */
$cdhl_banners_path = get_template_directory() . '/images/bg/';
$cdhl_banners_url = get_template_directory_uri() . '/images/bg/';
$cdhl_banners_new = array();
if (is_dir($cdhl_banners_path)) {	
	$cdhl_banners_data = cdhl_pgscore_get_file_list( 'jpg,png', $cdhl_banners_path );
	if (!empty($cdhl_banners_data)) {
		foreach ($cdhl_banners_data as $cdhl_banner_path) {
			$cdhl_banners_new[] = array(
				'alt'   => basename($cdhl_banner_path),
				'img'   => $cdhl_banners_url . basename($cdhl_banner_path),
				'height'=> 25,
				'width' => 100,
			);
		}
	}
}
array_unshift($cdhl_banners_new, null);
unset($cdhl_banners_new[0]);
$cdhl_banners = array();
if (is_dir($cdhl_banners_path)) {
	if ($cdhl_banners_dir = opendir($cdhl_banners_path)) {
		$cdhl_banners = array();
		while (( $cdhl_banners_file = readdir($cdhl_banners_dir) ) !== false) {
			if (stristr($cdhl_banners_file, '.png') !== false || stristr($cdhl_banners_file, '.jpg') !== false) {
				$name = explode('.', $cdhl_banners_file);
				$name = str_replace('.' . end($name), '', $cdhl_banners_file);
				$cdhl_banners[] = array(
					'alt' => $name,
					'img' => $cdhl_banners_url . $cdhl_banners_file,
					'height' => 25,
					'width' => 100,
				);
			}
		}
	}
}
$cdhl_backgrounds_path = get_template_directory() . '/images/color-customizer/background/';
$cdhl_backgrounds_url = get_template_directory_uri() . '/images/color-customizer/background/';
$cdhl_backgrounds = array();
if (is_dir($cdhl_backgrounds_path)) {
	if ($cdhl_backgrounds_dir = opendir($cdhl_backgrounds_path)) {
		$cdhl_backgrounds = array();
		while (( $cdhl_backgrounds_file = readdir($cdhl_backgrounds_dir) ) !== false) {
			if (stristr($cdhl_backgrounds_file, '.png') !== false || stristr($cdhl_backgrounds_file, '.jpg') !== false) {
				$name = explode('.', $cdhl_backgrounds_file);
				$name = str_replace('.' . end($name), '', $cdhl_backgrounds_file);
				$cdhl_backgrounds[] = array(
					'alt'   => $name,
					'img'   => $cdhl_backgrounds_url . $cdhl_backgrounds_file,
					'height'=> 25,
					'width' => 100,
				);
			}
		}
	}
}
/* * **********************************************************
 * ***
 * ********************************************************** */
/**
 * ---> SET ARGUMENTS
 * All the possible arguments for Redux.
 * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
 * */
$theme = wp_get_theme(); // For use with some settings. Not necessary.
$args = array(
	// TYPICAL -> Change these values as you need/desire
	'opt_name'                   => $opt_name,                     // This is where your data is stored in the database and also becomes your global variable name.
	'display_name'               => $theme->get('Name'),           // Name that appears at the top of your panel
	'display_version'            => $theme->get('Version'),        // Version that appears at the top of your panel
	'menu_type'                  => 'submenu',                     //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
	'allow_sub_menu'             => false,                         // Show the sections below the admin menu item or not
	'menu_title'                 => esc_html__('Theme Options', 'cardealer-helper'),
	'page_title'                 => esc_html__('Car Dealer Options', 'cardealer-helper'),
	// You will need to generate a Google API key to use this feature.
	// Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
	'google_api_key'             => '',                            // Set it you want google fonts to update weekly. A google_api_key value is required.
	'google_update_weekly'       => false,                         // Must be defined to add google fonts to the typography module
	'async_typography'           => true,                          // Use a asynchronous font on the front end or font string
	'admin_bar'                  => true,                          // Show the panel pages on the admin bar
	'admin_bar_icon'             => 'dashicons-portfolio',         // Choose an icon for the admin bar menu
	'admin_bar_priority'         => 50,                            // Choose an priority for the admin bar menu
	'global_variable'            => '',                            // Set a different name for your global variable other than the opt_name
	'dev_mode'                   => false,                         // Show the time the page took to load, etc
	'allow_tracking'             => false,
	'update_notice'              => true,                          // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
	'customizer'                 => true,                          // Enable basic customizer support
	// OPTIONAL -> Give you extra features
	'page_priority'              => null,                          // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
	'page_parent'                => 'themes.php',//cardealer-support',                  // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
	'page_permissions'           => 'manage_options',              // Permissions needed to access the options panel.
	'menu_icon'                  => CDHL_URL.'/images/menu-icon.png',// Specify a custom URL to an icon
	'last_tab'                   => '',                            // Force your panel to always open to a specific tab (by id)
	'page_icon'                  => 'icon-themes',                 // Icon displayed in the admin panel next to your menu_title
	'page_slug'                  => 'cardealer',                    // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
	'save_defaults'              => true,                          // On load save the defaults to DB before user clicks save or not
	'default_show'               => false,                         // If true, shows the default value next to each field that is not the default value.
	'default_mark'               => '',                            // What to print by the field's title if the value shown is default. Suggested: *
	'show_import_export'         => true,                         // Shows the Import/Export panel when not used as a field.
	// CAREFUL -> These options are for advanced use only
	'transient_time'             => 60 * MINUTE_IN_SECONDS,
	'output'                     => true,                          // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
	'output_tag'                 => true,                          // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
	'footer_credit'              => '',                            // Disable the footer credit of Redux. Please leave if you can help it.
	// FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
	'database'                   => '',                            // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
	'use_cdn'                    => true,                          // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.
	// HINTS
	'hints' => array(
		'icon'         => 'el el-question-sign',
		'icon_position'=> 'right',
		'icon_color'   => 'lightgray',
		'icon_size'    => 'normal',
		'tip_style'    => array(
			'color'  => 'red',
			'shadow' => true,
			'rounded'=> false,
			'style'  => '',
		),
		'tip_position' => array(
			'my' => 'top left',
			'at' => 'bottom right',
		),
		'tip_effect' => array(
			'show' => array(
				'effect'  => 'slide',
				'duration'=> '500',
				'event'   => 'mouseover',
			),
			'hide' => array(
				'effect'  => 'slide',
				'duration'=> '500',
				'event'   => 'click mouseleave',
			),
		),
	)
);
// ADMIN BAR LINKS -> Setup custom links in the admin bar menu as external items.
$args['admin_bar_links'][] = array(
	'id'   => 'potenza-website',
	'href' => 'http://www.potenzaglobalsolutions.com',
	'title'=> esc_html__('Potenza', 'cardealer-helper'),
);
$args['admin_bar_links'][] = array(
	'href' => 'https://potezasupport.ticksy.com/',
	'title'=> esc_html__('Support', 'cardealer-helper'),
);
$args['admin_bar_links'][] = array(
	'id'   => 'potenza-tf-profile',
	'href' => 'https://themeforest.net/user/potenzaglobalsolutions',
	'title'=> esc_html__('Themeforest Profile', 'cardealer-helper'),
);
// SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
$args['share_icons'][] = array(
	'url'  => 'https://www.facebook.com/potenzasolutions',
	'title'=> esc_html__('Like us on Facebook', 'cardealer-helper'),
	'icon' => 'el el-facebook'
);
$args['share_icons'][] = array(
	'url'  => 'https://twitter.com/PotenzaGlobal',
	'title'=> esc_html__('Follow us on Twitter', 'cardealer-helper'),
	'icon' => 'el el-twitter'
);
$args['share_icons'][] = array(
	'url'  => 'https://plus.google.com/+Potenzaglobalsolutions/posts',
	'title'=> esc_html__('Follow us on Google+', 'cardealer-helper'),
	'icon' => 'el el-googleplus'
);
$args['share_icons'][] = array(
	'url'  => 'http://www.linkedin.com/company/potenza-global-solutions-pvt-ltd-',
	'title'=> esc_html__('Find us on LinkedIn', 'cardealer-helper'),
	'icon' => 'el el-linkedin'
);
$args['share_icons'][] = array(
	'url'  => 'http://www.potenzaglobalsolutions.com/blogs/',
	'title'=> esc_html__('Our Blog', 'cardealer-helper'),
	'icon' => 'el el-quotes'
);
Redux::setArgs($opt_name, $args);
/*
 * ---> END ARGUMENTS
 */
/*
 * ---> START HELP TABS
 */
$tabs = array(
	array(
		'id' => 'redux-help-tab-1',
		'title' => esc_html__('Theme Information 1', 'cardealer-helper'),
		'content' => esc_html__('<p>This is the tab content, HTML is allowed.</p>', 'cardealer-helper')
	),
	array(
		'id' => 'redux-help-tab-2',
		'title' => esc_html__('Theme Information 2', 'cardealer-helper'),
		'content' => esc_html__('<p>This is the tab content, HTML is allowed.</p>', 'cardealer-helper')
	)
);
Redux::setHelpTab($opt_name, $tabs);
// Set the help sidebar
$content = esc_html__('<p>This is the sidebar content, HTML is allowed.</p>', 'cardealer-helper');
Redux::setHelpSidebar($opt_name, $content);
/*
 * <--- END HELP TABS
 */
/*
 *
 * ---> START SECTIONS
 *
 */

/* Layout Settings */
Redux::setSection($opt_name, array(
	'title'           => esc_html__('Layout Settings', 'cardealer-helper'),
	'id'              => 'layout_settings',
	'customizer_width'=> '400px',
	'icon'            => 'el el-website icon-large',
	'fields'          => array(
		array(
			'id'       => 'page_layout',
			'type'     => 'image_select',
			'title'    => esc_html__('Pages Layout', 'cardealer-helper'),
			'subtitle' => esc_html__('Specify the layout for the pages', 'cardealer-helper'),
			'options'  => array(
				'boxed' => array(
					'alt' => 'Boxed',
					'img' => CDHL_URL . '/images/radio-button-imgs/page_layout/boxed.png'
				),
				'framed' => array(
					'alt' => 'Framed',
					'img' => CDHL_URL . '/images/radio-button-imgs/page_layout/framed.png'
				),
				'standard' => array(
					'alt' => 'Standard',
					'img' => CDHL_URL . '/images/radio-button-imgs/page_layout/standard.png'
				)
			),
			'default'  => 'standard',
		),
		array(
			'id'     => 'body-background-section-start',
			'type'   => 'section',
			'title'  => esc_html__('Body Background Settings', 'cardealer-helper' ),
			'indent' => true,
			'required' => array(
				array('page_layout', '!=', 'standard')
			)
		),
		array(
			'id' 				=> 'body_background_type',
			'type'				=> 'button_set',
			'title' 			=> esc_html__( 'Body Background Type', 'cardealer-helper' ),
			'subtitle' 			=> esc_html__( 'Select Body Background Type', 'cardealer-helper' ),
			'options' 			=> array(
				'body_color' 	=> esc_html__( 'Color', 'cardealer-helper' ),
				'body_image' 	=> esc_html__( 'Image', 'cardealer-helper' ),
			),
			'required' => array(
				array('page_layout', '!=', 'standard')
			),
			'default' => 'body_image'
		),
		array(
			'id'     => 'body_background_color',
			'type'   => 'color',
			'title'  => esc_html__( 'Body Background Color', 'cardealer-helper' ),
			'subtitle'  => esc_html__( 'Select Body Background Color', 'cardealer-helper' ),
			'transparent' => false,
			'default' => 'white',
			'required' => array(
				array('body_background_type', '=', 'body_color')
			)
		),
		array(
			'id'     => 'body_background_img',
			'type'   => 'background',
			'title'  => esc_html__( 'Body Background Image', 'cardealer-helper' ),
			'subtitle'  => esc_html__( 'Set Body Background Image Settings', 'cardealer-helper' ),
			'transparent' => false,
			'background-color' => false,
			'transparent' => false,
			'default' => array(
				'background-repeat' => 'no-repeat',
				'background-attachment' => 'fixed',
				'background-position'=> 'center center',
				'background-size' => 'cover',
				'background-image' => get_template_directory_uri().'/images/default/body-bg.jpg'
			
			),
			'required' => array('body_background_type', '=', 'body_image')),
		array(
			'id'     => 'body-background-section-end',
			'type'   => 'section',
			'indent' => false,
		),
	)
));
/* Logo Settings */
Redux::setSection($opt_name, array(
	'title'           => esc_html__('Site Logo', 'cardealer-helper'),
	'id'              => 'logo_settings',
	'customizer_width'=> '400px',
	'icon'            => 'el el-flag-alt',
	'fields'          => array(
		array(
			'id'       => 'logo_type',
			'type'     => 'radio',
			'title'    => esc_html__('Logo Type', 'cardealer-helper'),
			'subtitle' => esc_html__('Specify the type of Logo, It can be Text OR Logo', 'cardealer-helper'),
			'options'  => array(
				'text' => esc_html__('Logo as Text', 'cardealer-helper'),
				'image' => esc_html__('Logo as Image', 'cardealer-helper')
			),
			'default' => 'image'
		),
		array(
			'id'         => 'logo_text',
			'type'       => 'text',
			'title'      => esc_html__('Logo Text', 'cardealer-helper'),
			'subtitle'   => esc_html__('Enter the text to be used instead of the logo image.', 'cardealer-helper'),
			'default'    => esc_html__('Car Dealer','cardealer-helper'),
            'required'   => array('logo_type', '=', 'text'),
		),
		array(
			'id'          => 'logo_font',
			'type'        => 'typography',
			'title'       => esc_html__('Logo Font', 'cardealer-helper'),
			'subtitle'    => esc_html__('This will be applied to Logo text only. Select logo font-style and size.', 'cardealer-helper'),
			'google'      => true,
			'output'      => array('h2.site-description'),
			'units'       =>'px',
			'subsets'     => false,
			'color'       => true,
			'text-align'  => false,
			'font-size'   => true,
			'font-style'  => true,
			'font-backup' => true,
			'line-height' => true,
			'text-transform' => true,
			'letter-spacing' => true,
			'font-weight' => true,
			'default'     => array(
				'font-family' => 'Open Sans',
				'google'      => true,
				'font-backup' => 'Arial, Helvetica, sans-serif',
				'font-size'   => '32px',
				'font-style' => '700',
				'line-height' => '32px',
				'text-transform' => 'uppercase',
				'letter-spacing' => 0,
				'color' 		=> 'white'
			),
			'required' => array('logo_type', '=', 'text'),
		),
		array(
			'id'          => 'sticky_logo_font',
			'type'        => 'typography',
			'title'       => esc_html__('Sticky Logo Font', 'cardealer-helper'),
			'subtitle'    => esc_html__('This will be applied to Sticky Logo text only. Select sticky logo font-style and size.', 'cardealer-helper'),
			'google'      => false,
			'output'      => array('h2.site-description'),			
			'subsets'     => false,
			'font-family' => false,
			'color'       => true,
			'text-align'  => false,
			'font-size'   => true,
			'line-height' => false,
			'font-style'  => false,
			'font-weight' => false,
			'units'    => array('px'),
			'default'     => array(
				'font-size' => '32',
				'color' 		=> '#db2d2e',
				'google'      => true,
			),
			'required' => array('logo_type', '=', 'text'),
		),
		array(
			'id'          => 'mobile_logo_font',
			'type'        => 'typography',
			'title'       => esc_html__('Mobile Logo Font', 'cardealer-helper'),
			'subtitle'    => esc_html__('This will be applied to Mobile views text for both simple and sticky header.', 'cardealer-helper'),
			'google'      => false,
			'output'      => array('h2.site-description'),			
			'subsets'     => false,
			'font-family' => false,
			'color'       => false,
			'text-align'  => false,
			'font-size'   => true,
			'line-height' => true,
			'font-style'  => false,
			'font-weight' => false,
			'units'    => array('px'),
			'default'     => array(
				'font-size' => '32',
				'line-height' => '32px',
			),
			'required' => array('logo_type', '=', 'text'),
		),
		array(
			'id'       => 'logo_image',
			'type'     => 'media',
			'url'      => true,
			'title'    => esc_html__('Logo Image', 'cardealer-helper'),
			'desc'     => esc_html__('Basic media uploader with disabled URL input field.', 'cardealer-helper'),
			'subtitle' => esc_html__('NOTE: Upload image that will be used as logo of the site.', 'cardealer-helper'),
			'default'  => array( 'url'=> get_template_directory_uri().'/images/default/logo-light.png' ),
			'required' => array( 'logo_type' , '=', 'image' )
		),
		array(
			'id'         => 'logo_max_height',
			'type'       => 'dimensions',
			'title'      => esc_html__('Logo Max Height', 'cardealer-helper'),
			'subtitle'	 => esc_html__('If you feel your logo looks small then increase this and adjust it.', 'cardealer-helper'),
			'width'		 => false,
			'units'    	 => array('px'),
			'default'    => array( 'height'  => '32px' ),
			'required' => array( 'logo_type' , '=', 'image' )
		),
		array(
			'id'       => 'sticky_logo_img',
			'type'     => 'media',
			'url'      => true,
			'title'    => esc_html__('Sticky Logo', 'cardealer-helper'),
			'subtitle' => esc_html__('NOTE: Upload image that will be used as logo for sticky header.', 'cardealer-helper'),
			'default'  => array( 'url'=> get_template_directory_uri().'/images/default/logo.png' ),
			'required' => array( 'logo_type' , '=', 'image' )
		),	
		array(
			'id'         => 'logo_max_height_sticky_header',
			'type'       => 'dimensions',
			'title'      => esc_html__('Logo Max Height when Sticky Header', 'cardealer-helper'),
			'subtitle'	 => esc_html__('Set Logo Height when the header is sticky', 'cardealer-helper'),
			'width'		 => false,
			'units'    	 => array('px'),
			'default'    => array( 'height'  => '28px' ),
			'required' => array( array( 'sticky_header', '!=', 'none' ), array( 'logo_type' , '=', 'image' ))
		),		
		array(
			'id'         => 'show_mobile_logo',
			'type'       => 'button_set',
			'title'      => esc_html__('Show Mobile logo', 'cardealer-helper'),
			'desc'    	 => esc_html__('Select this option to display different logo for mobile devices'),
			'options'    => array(
				'yes' => esc_html__('Yes','cardealer-helper'),
				'no' => esc_html__('No','cardealer-helper'),
			),
			'default' => 'yes',
			'required' => array( 'logo_type' , '=', 'image' ),
		),
		array(
			'id'       => 'mobile_logo_img',
			'type'     => 'media',
			'url'      => true,
			'title'    => esc_html__('Mobile Logo', 'cardealer-helper'),
			'subtitle' => esc_html__('NOTE: Upload image that will be used as logo for Mobile devices.', 'cardealer-helper'),
			'default'  => array( 'url'=> get_template_directory_uri().'/images/default/logo-light.png' ),
			'required' => array( 'logo_type' , '=', 'image' ),
			'required' => array( 'show_mobile_logo' , '=', 'yes' )
		),
		array(
			'id'         => 'mobile_logo_height',
			'type'       => 'dimensions',
			'title'      => esc_html__('Mobile Logo Max Height', 'cardealer-helper'),
			'subtitle'	 => esc_html__('If you feel your mobile logo looks	small then increase this and adjust it.', 'cardealer-helper'),
			'width'		 => false,
			'units'    	 => array('px'),
			'default'    => array( 'height'  => '25px' ),
			'required' 	 => array( 'logo_type' , '=', 'image' ),
		),
		array(
			'id'       => 'mobile_sticky_logo_img',
			'type'     => 'media',
			'url'      => true,
			'title'    => esc_html__('Mobile Sticky Logo', 'cardealer-helper'),
			'subtitle' => esc_html__('NOTE: Upload image that will be used as mobile logo for sticky header.', 'cardealer-helper'),
			'default'  => array( 'url'=> get_template_directory_uri().'/images/default/logo-light.png' ),
			'required' => array( 'logo_type' , '=', 'image' ),
			'required' => array( 'show_mobile_logo' , '=', 'yes' )
		),
		array(
			'id'         => 'mobile_logo_max_height_sticky_header',
			'type'       => 'dimensions',
			'title'      => esc_html__('Mobile Logo Max Height when Sticky Header', 'cardealer-helper'),
			'subtitle'	 => esc_html__('Set Mobile Logo Height when the header is sticky', 'cardealer-helper'),
			'width'		 => false,
			'units'    => array('px'),
			'default'    => array( 'height'  => '25px' ),
			'required' => array( 'sticky_header', '!=', 'none' ),
			'required' => array( 'logo_type' , '=', 'image' ),
		),
		array(
			'id'      => 'login_logo',
			'type'    => 'media',
			'url'     => true,
			'title'   => esc_html__('Login Logo', 'cardealer-helper'),
			'subtitle'	 => esc_html__('Upload logo for Admin Login Page.', 'cardealer-helper'),
			'compiler'=> 'true',
			'desc'    => esc_html__('Select logo for login page. (64px * 64px)', 'cardealer-helper'),
			'default' => array(
				'url' => get_template_directory_uri() . '/images/default/logo.png'
			),
		),
		array(
			'id'       => 'maintenance_logo_image',
			'type'     => 'media',
			'url'      => true,
			'title'    => esc_html__('Logo Image Maintenance', 'cardealer-helper'),
			'desc'     => esc_html__('Basic media uploader with disabled URL input field.', 'cardealer-helper'),
			'subtitle' => esc_html__('NOTE: Upload image that will be used as logo for Maintenance Page when site is under maintenance.', 'cardealer-helper'),
			'default'  => array( 'url'=> get_template_directory_uri().'/images/default/logo.png' ),
			'required' => array( 'logo_type' , '=', 'image' )
		),
        array(
			'id' => 'site_description',
			'type' => 'switch',
			'title' => esc_html__('Site Description', 'cardealer-helper'),			
            'desc'    => esc_html__('Show/hide description below logo.'),
            'default'  => false
		),
		array(
			'id'       => 'favicon_icon',
			'type'     => 'media',
			'url'      => true,
			'title'    => esc_html__('Favicon icon', 'cardealer-helper'),
			'desc'     => esc_html__('The Site Icon is used as a browser and app icon for your site. Icons must be square, and at least 512 pixels wide and tall.', 'cardealer-helper'),
			'required' => array( 'logo_type' , '=', 'image' )
		),
	)
));

/* Back To Top options */
Redux::setSection($opt_name, array(
    'title' => esc_html__('Back To Top', 'cardealer-helper'),
    'id' => 'back-to-top',
    'customizer_width' => '400px',
    'icon' => 'el el-circle-arrow-up',
    'fields' => array(
		array(
			'id'     => 'back_to_top',
			'type'   => 'switch',
			'title'  => esc_html__('Desktop', 'cardealer-helper'),
			'on' => esc_html__('Show', 'cardealer-helper'),
			'off' => esc_html__('Hide', 'cardealer-helper'),
			'desc'   => esc_html__('Enable/disable back to top button.', 'cardealer-helper'),
			'default'=> true,
		),
		array(
			'id'     => 'back_top_mobile',
			'type'   => 'switch',
			'title'  => esc_html__('Mobile', 'cardealer-helper'),
			'on' => esc_html__('Show', 'cardealer-helper'),
			'off' => esc_html__('Hide', 'cardealer-helper'),
			'desc'   => esc_html__('Enable/disable back to top button.', 'cardealer-helper'),
			'default'=> true,
		)
	)
));

/* Site Preloader options */
Redux::setSection($opt_name, array(
    'title' => esc_html__('Site Preloader option', 'cardealer-helper'),
    'id' => 'site-preloader-option',
    'customizer_width' => '400px',
    'icon' => 'fa fa-spinner',
    'fields' => array(
		array(
			'id'     => 'preloader',
			'type'   => 'switch',
			'title'  => esc_html__('Preloader', 'cardealer-helper'),
			'on' => esc_html__('Show', 'cardealer-helper'),
			'off' => esc_html__('Hide', 'cardealer-helper'),
			'desc'   => esc_html__('Show/Hide preloader animation on page load.', 'cardealer-helper'),
			'default'=> true,
		),
		array(
			'id'     => 'preloader_img',
			'type'   => 'button_set',
			'title'  => esc_html__('Preloader Image Option', 'cardealer-helper'),
			'desc'   => esc_html__('Select preloader options.', 'cardealer-helper'),
			'options'  => array(
				'pre_loader' => esc_html__('Use Preloader Image', 'cardealer-helper'),
				'pre_image' => esc_html__('Change default selected image', 'cardealer-helper'),
				'code' => esc_html__('Use Code', 'cardealer-helper') 				
			),
			'default' => 'pre_loader',
			'required'      => array(
				array('preloader', '=', true)
			)
		),
		array(
		   'id'       => 'predefined_loader_img',
		   'type'     => 'image_select',
		   'title'    => __('Preloader Image', 'cardealer-helper'),
		   'subtitle' => __('Add your default image.', 'cardealer-helper'),
		   'options'  => array(
				'loader' => array (
				  'alt'  => 'loader',
				  'img'  => get_template_directory_uri() . '/images/preloader_img/loader.gif',
				),
				'loader_1' => array (
				  'alt'  => 'loader_1',
				  'img'  => get_template_directory_uri() . '/images/preloader_img/loader_1.gif',
				),
				'loader_2' => array (
				  'alt'  => 'loader_2',
				  'img'  => get_template_directory_uri() . '/images/preloader_img/loader_2.gif'
				),
				'loader_3' => array (
				  'alt'  => 'loader_3',
				  'img'  => get_template_directory_uri() . '/images/preloader_img/loader_3.gif'
				),
				'loader_4' => array (
				  'alt'  => 'loader_4',
				  'img'  => get_template_directory_uri() . '/images/preloader_img/loader_4.gif'
				),
				'loader_5' => array (
				  'alt'  => 'loader_5',
				  'img'  => get_template_directory_uri() . '/images/preloader_img/loader_5.gif'
				),
				'loader_6' => array (
				  'alt'  => 'loader_6',
				  'img'  => get_template_directory_uri() . '/images/preloader_img/loader_6.gif'
				),
				'loader_7' => array (
				  'alt'  => 'loader_7',
				  'img'  => get_template_directory_uri() . '/images/preloader_img/loader_7.gif'
				),
				'loader_8' => array (
				  'alt'  => 'loader_8',
				  'img'  => get_template_directory_uri() . '/images/preloader_img/loader_8.gif'
				),
				'loader_9' => array (
				  'alt'  => 'loader_9',
				  'img'  => get_template_directory_uri() . '/images/preloader_img/loader_9.gif'
				),
				'loader_10' => array (
				  'alt'  => 'loader_10',
				  'img'  => get_template_directory_uri() . '/images/preloader_img/loader_10.gif'
				),
				'loader_11' => array (
				  'alt'  => 'loader_11',
				  'img'  => get_template_directory_uri() . '/images/preloader_img/loader_11.gif'
				),
				'loader_12' => array (
				  'alt'  => 'loader_12',
				  'img'  => get_template_directory_uri() . '/images/preloader_img/loader_12.gif'
				),
				'loader_13' => array (
				  'alt'  => 'loader_13',
				  'img'  => get_template_directory_uri() . '/images/preloader_img/loader_13.gif'
				),
				'loader_14' => array (
				  'alt'  => 'loader_14',
				  'img'  => get_template_directory_uri() . '/images/preloader_img/loader_14.gif'
				),
				'loader_15' => array (
				  'alt'  => 'loader_15',
				  'img'  => get_template_directory_uri() . '/images/preloader_img/loader_15.gif'
				),
				'loader_16' => array (
				  'alt'  => 'loader_16',
				  'img'  => get_template_directory_uri() . '/images/preloader_img/loader_16.gif'
				)
		   ),
		   'default'  => 'loader',
		   'required'      => array(
				array('preloader', '=', true),
				array('preloader_img', '=', 'pre_loader'),
		   )
		  ),
		array(
			'id'            => 'preloader_image',
			'type'          => 'media',
			'url'           => true,
			'title'         => esc_html__('Preloader Image', 'cardealer-helper'),
			'desc'          => esc_html__('Select preloader image.', 'cardealer-helper'),
			'default'       => array(
								'url'	=> get_template_directory_uri() . '/images/preloader_img/loader.gif'
							),
			'library_filter'=> array('gif','jpg','jpeg','png'),			
			'required'      => array(
								array('preloader', '=', true),
								array('preloader_img', '=', 'pre_image')
							)
		),
		array(
			'id'       => 'preloader_html',
			'type'     => 'ace_editor',
			'title'    => esc_html__('Preloader HTML', 'cardealer-helper'),
			'mode'     => 'javascript',
			'theme'    => 'chrome',
			'desc'     => esc_html__('Paste your HTML code here.', 'cardealer-helper'),
			'default'  => "<div id='loading'>\n<div id='loading-center'>\n<img src='". get_template_directory_uri() . "/images/preloader_img/loader.gif' alt='Loader' title='". esc_html__('loading...', 'cardealer-helper'). "'>\n</div>\n</div>",
			'required'      => array(
								array('preloader', '=', true),
								array('preloader_img', '=', 'code')
							)
		),
		array(
			'id'       => 'preloader_css',
			'type'     => 'ace_editor',
			'title'    => esc_html__('Preloader CSS', 'cardealer-helper'),
			'mode'     => 'css',
			'theme'    => 'chrome',
			'desc'     => esc_html__('Paste your CSS code here.', 'cardealer-helper'),
			'default'  => '#loading { background-color: #ffffff; height: 100%; width: 100%; position: fixed; z-index: 1; margin-top: 0px; top: 0px; left: 0px; bottom: 0px; overflow:hidden !important; right: 0px; z-index: 999999; } #loading-center { width: 100%; height: 100%; position: relative; } #loading-center img { text-align: center; left: 0; position: absolute; right: 0; top: 50%; transform: translateY(-50%); -webkit-transform: translateY(-50%); -o-transform: translateY(-50%); -ms-transform: translateY(-50%); -moz-transform: translateY(-50%); z-index: 99; margin: 0 auto; }', 'cardealer-helper',
			'required' => array(
							array('preloader', '=', true),
							array('preloader_img', '=', 'code')
						)
		),
		array(
			'id'       => 'preloader_js',
			'type'     => 'ace_editor',
			'title'    => esc_html__('Preloader JS', 'cardealer-helper'),
			'mode'     => 'javascript',
			'theme'    => 'chrome',
			'desc'     => esc_html__('Paste your JS code here.', 'cardealer-helper'),
			'default'  => "jQuery(window).load(function() {\n jQuery('#load').fadeOut(); \njQuery('#loading').delay(0).fadeOut('slow');\n});",
			'required' => array(
							array('preloader', '=', true),
							array('preloader_img', '=', 'code')
						)
		)
	)
));

/* Site Header Setting */
Redux::setSection($opt_name, array(
	'title'           => esc_html__('Site Header', 'cardealer-helper'),
	'id'              => 'site_header',
	'customizer_width'=> '400px',
	'icon'            => 'el el-website',
	'fields'          => array(
	)
));
Redux::setSection($opt_name, array(
	'title'           => esc_html__('Site Header', 'cardealer-helper'),
	'id'              => 'appearance_subsection_site_header',
	'icon' 			  => 'el el-arrow-up',
	'subsection'      => true,
	'customizer_width'=> '450px',
	'fields'          => array(
		array(
			'id'         => 'header_type',
			'type'       => 'select_image_new',
			'title'      => esc_html__( 'Header Type', 'cardealer-helper' ),
			'placeholder'=> esc_html__( 'Select header type.', 'cardealer-helper' ),
			'select2'    => array(
								'allowClear' => 0,
							),
			'options'   => Array(
				'defualt' => array(
					'alt'  => 'Default (Transparent)',
					'title'=> 'Default (Transparent)',
					'img'  => CDHL_URL . '/images/radio-button-imgs/header_type/defualt.jpg',
				),
				'light' => array(
					'alt'  => 'Light',
					'title'=> 'Light',
					'img'  => CDHL_URL . '/images/radio-button-imgs/header_type/light.jpg',
				),
				'transparent-fullwidth' => array(
					'alt'  => 'Transparent Fullwidth',
					'title'=> 'Transparent Fullwidth',
					'img'  => CDHL_URL . '/images/radio-button-imgs/header_type/transparent-fullwidth.jpg',
				),
				'light-fullwidth' => array(
					'alt'  => 'Light Fullwidth',
					'title'=> 'Light Fullwidth',
					'img'  => CDHL_URL . '/images/radio-button-imgs/header_type/light-fullwidth.jpg',
				),
				'logo-center' => array(
					'alt'  => 'Logo Center',
					'title'=> 'Logo Center',
					'img'  => CDHL_URL . '/images/radio-button-imgs/header_type/logo-center.jpg',
				),
				'logo-right' => array(
					'alt'  => 'Logo Right',
					'title'=> 'Logo Right',
					'img'  => CDHL_URL . '/images/radio-button-imgs/header_type/logo-right.jpg',
				),
				'boxed' => array(
					'alt'  => 'Boxed',
					'title'=> 'Boxed',
					'img'  => CDHL_URL . '/images/radio-button-imgs/header_type/boxed-header.png',
				)				
			),
			'default'  => 'defualt',
		),
		array(
			'id' 		=> 'header_color_settings',
			'type'      => 'button_set',
			'title' 	=> esc_html__('Header Color Settings', 'cardealer-helper'),
			'desc'      => esc_html__('Select Header Color options.', 'cardealer-helper'),
			'options'  => array(
				'default' => esc_html__('Default', 'cardealer-helper'),
				'custom' => esc_html__('Custom', 'cardealer-helper') 				
			),
			'default' => 'default',
		),
		array(
			'id' => 'topbar_color_setting_start',
			'type' => 'section',
			'title'=> esc_html__('Topbar Color Settings', 'cardealer-helper'),
			'indent' => true,
			'required'	=> array('header_color_settings', '=', 'custom'),
		),
		array(
			'id' => 'top_bar_background_color',
			'type' => 'color',
			'title' => esc_html__('Top Bar Background Color', 'cardealer-helper'),
			'subtitle' => esc_html__('Check transparent only if your header is transparent from header setting.', 'cardealer-helper'),
			'transparent' => true,
			'default' => 'transparent',
			'validate' => 'color',
			'mode' => 'background',
			'required'	=> array('header_color_settings', '=', 'custom'),
		),
		array(
			'id' => 'top_bar_text_color',
			'type' => 'color',
			'title' => esc_html__('Top Bar Text Color', 'cardealer-helper'),
			'transparent' => false,
			'default' => '#ffffff',
			'validate' => 'color',
			'required'	=> array('header_color_settings', '=', 'custom'),
		),
		array(
			'id' => 'topbar_color_setting_end',
			'type' => 'section',
			'indent' => false,
			'required'	=> array('header_color_settings', '=', 'custom'),
		),
		array(
			'id' => 'sticky_topbar_color_setting_start',
			'type' => 'section',
			'title'=> esc_html__('Sticky Topbar Color Settings', 'cardealer-helper'),
			'indent' => true,
			'required'	=> array('header_color_settings', '=', 'custom'),
		),
		array(
			'id' => 'sticky_top_bar_background_color',
			'type' => 'color',
			'title' => esc_html__('Sticky Top Bar Background Color', 'cardealer-helper'),
			'transparent' => false,
			'default' => '#000000',
			'validate' => 'color',
			'mode' => 'background',
			'required'	=> array('header_color_settings', '=', 'custom'),
		),
		array(
			'id' => 'sticky_top_bar_text_color',
			'type' => 'color',
			'title' => esc_html__('Sticky Top Bar Text Color', 'cardealer-helper'),
			'transparent' => false,
			'default' => '#ffffff',
			'validate' => 'color',
			'required'	=> array('header_color_settings', '=', 'custom'),
		),
		array(
			'id' => 'sticky_topbar_color_setting_end',
			'type' => 'section',
			'indent' => false,
			'required'	=> array('header_color_settings', '=', 'custom'),
		),
		array(
			'id' => 'header_color_setting_start',
			'type' => 'section',
			'title'=> esc_html__('Header[Main] Color Settings', 'cardealer-helper'),
			'indent' => true,
			'required'	=> array('header_color_settings', '=', 'custom'),
		),
		array(
			'id' 		=> 'header_background_color',
			'type' 		=> 'color',
			'title' 	=> esc_html__('Header Background Color', 'cardealer-helper'),
			'transparent'=> false,
			'default'	=> '#ffffff',
			'validate' 	=> 'color',
			'mode' 		=> 'background',
			'required'      => array(
				array('header_color_settings', '=', 'custom')
			)
		),
		array(
			'id' 		=> 'header_text_color',
			'type' 		=> 'color',
			'icon' 		=> 'el el-arrow-up',
			'title' 	=> esc_html__('Header Text Color', 'cardealer-helper'),
			'transparent' => false,
			'default' 	=> '#ffffff',
			'validate' 	=> 'color',
			'required'      => array(
				array('header_color_settings', '=', 'custom')
			)
		),
		array(
			'id' 		=> 'header_link_color',
			'type' 		=> 'color',
			'icon' 		=> 'el el-arrow-up',
			'title' 	=> esc_html__('Header Link Color', 'cardealer-helper'),
			'transparent' => false,
			'default' 	=> '#db2d2e',
			'validate' 	=> 'color',
			'required'      => array(
				array('header_color_settings', '=', 'custom')
			)
		),
		array(
			'id' => 'header_color_setting_end',
			'type' => 'section',
			'indent' => false,
			'required'	=> array('header_color_settings', '=', 'custom'),
		),
		array(
			'id' => 'sticky_header_settings_start',
			'type' => 'section',
			'title' => esc_html__('Sticky Header Settings', 'cardealer-helper'),
			'indent' => true,
		),
		array(
			'id' 		=> 'sticky_header',
			'type' 		=> 'switch',
			'title' 	=> 'Sticky Header',
			'subtitle'	=> esc_html__('Enable/Disable Sticky Header', 'cardealer-helper'),
			'default' 	=> true
		),
		array(
			'id' 		=> 'sticky_header_mobile',
			'type' 		=> 'switch',
			'title' 	=> 'Sticky Header in Mobile',
			'subtitle'	=> esc_html__('Enable/Disable Sticky Header on Mobile', 'cardealer-helper'),
			'required'  => array( 'sticky_header', '=', true ),
			'default' 	=> true
		),
		array(
			'id' => 'sticky_header_background_color',
			'type' => 'color',
			'title' => esc_html__('Sticky Header Background Color', 'cardealer-helper'),
			'transparent' => false,
			'default' => '#FFF',
			'validate' => 'color',
			'mode' => 'background',
			'required' => array( array( 'sticky_header', '=', true ), array('header_color_settings', '=', 'custom') )
		),
		array(
			'id' => 'sticky_header_text_color',
			'type' => 'color',
			'title' => esc_html__('Sticky Header Text Color', 'cardealer-helper'),
			'transparent' => false,
			'default' => '#000',
			'validate' => 'color',
			'required' => array( array( 'sticky_header', '=', true ), array('header_color_settings', '=', 'custom') )
		),
		array(
			'id' => 'sticky_header_link_color',
			'type' => 'color',
			'title' => esc_html__('Sticky Header Link Color', 'cardealer-helper'),
			'transparent' => false,
			'default' => '#db2d2e',
			'validate' => 'color',
			'required' => array( array( 'sticky_header', '=', true ), array('header_color_settings', '=', 'custom') )
		),
		array(
			'id'        => 'header_height_on_scroll',
			'type'      => 'dimensions',
			'title'     => esc_html__('Sticky Header Height On Scroll', 'cardealer-helper'),
			'desc'		=> esc_html__('Enter header height after scroll in pixels', 'cardealer-helper'),
			'width'		=> false,
			'units'     => array('px'),
			'default'    => array( 'height'  => '68px' ),
			'required'  => array( 'sticky_header', '=', true )			
		),
		array(
			'id' => 'sticky_header_settings_end',
			'type' => 'section',
			'indent' => false,
		),
	)
));

/* Topbar */


Redux::setSection($opt_name, array(
	'title'           => esc_html__('Topbar', 'cardealer-helper'),
	'id'              => 'top_bar',
	'icon' 			  => 'el el-arrow-up',
	'subsection'      => true,
	'customizer_width'=> '450px',
	'fields'          => array(
		array(
			'id'         => 'top_bar',
			'type'       => 'switch',
			'title'      => esc_html__('Top Bar', 'cardealer-helper'),
			'on' => esc_html__('Show', 'cardealer-helper'),
			'off' => esc_html__('Hide', 'cardealer-helper'),
			'subtitle'	=> esc_html__('Enable/Disable Top Bar', 'cardealer-helper'),
			'default' 	=> true
		),
		array(
			'id'         => 'top_bar_mobile',
			'type'       => 'switch',
			'title'      => esc_html__('Top Bar in Mobile', 'cardealer-helper'),
			'on' => esc_html__('Show', 'cardealer-helper'),
			'off' => esc_html__('Hide', 'cardealer-helper'),
			'subtitle'	=> esc_html__('Enable/Disable Top Bar in Mobile', 'cardealer-helper'),
			'default' 	=> true,
			'required'	=> array('top_bar', '=', true)
		),
		array(
			'id'         => 'topbar_layout_data',
			'type'       => 'sorter',
			'title'      => 'Topbar Layout',
			'subtitle'   => 'Select layout contents.',
			'description'=> '<p>' . esc_html__('Note : Login link will be displayed only if woocommerce is installed and enabled/active. Please maintain both side equal contents so it will work perfect in responsive. ', 'cardealer-helper') . '</p>',
			'options'    => array(
				'Left'   => array(
                    'contact_timing' => esc_html__('Contact Timing', 'cardealer-helper'),
					'email'          => esc_html__('Email', 'cardealer-helper'),
				),
				'Right'  => array(
					'phone_number'   => esc_html__('Phone Number', 'cardealer-helper'),
					'social_profiles'=> esc_html__('Social Profiles', 'cardealer-helper'),
					'login'          => esc_html__('Login', 'cardealer-helper'),					
				),
				'Available Items'   => (cdhl_check_promocode_exist())? array( 'promocode'=> esc_html__('Promo Code', 'cardealer-helper'), 'address'   => esc_html__('Address', 'cardealer-helper') ): array('address'   => esc_html__('Address', 'cardealer-helper')),
			),
			'limits'   => array(
			),
			'required'	=> array('top_bar', '=', true),
		),
		array(
			'id'         => 'sticky_topbar',
			'type'       => 'button_set',
			'title'      => esc_html__('Sticky Top Bar', 'cardealer-helper'),
			'options'    => array(
				'on' => esc_html__('On', 'cardealer-helper'),
				'off' => esc_html__('Off', 'cardealer-helper'),
			),
			'default' => 'off',
			'required'	=> array('top_bar', '=', true)
		),
	)
));

/* Search Form Submenu of Header option*/
Redux::setSection($opt_name, array(
	'title'           => esc_html__('Search', 'cardealer-helper'),
	'id'              => 'search_section',
	'subsection'      => true,
	'icon' 			  => 'el el-search',
	'customizer_width'=> '450px',
	'fields'          => array(
		array(
			'id'         => 'show_search',
			'type'       => 'switch',
			'title'      => esc_html__('Show Search Button', 'cardealer-helper'),
			'default' => true,	
		),
		array(
			'id'         => 'search_placeholder_text',
			'type'       => 'text',
			'title'      => esc_html__('Search Input Placeholder Word', 'cardealer-helper'),
			'default' => esc_html__('Search...','cardealer-helper'),	
			'required'=> array(
				array('show_search', '=', 1),
			)
		),
		array(
			'id'         => 'search_content_type',
			'type'       => 'button_set',
			'title'      => esc_html__('Search Content Type', 'cardealer-helper'),			
			'options'    => array(
				'all' => esc_html__('All','cardealer-helper'),
				'post' => esc_html__('post','cardealer-helper'),
				'cars' => esc_html__('Cars','cardealer-helper'),
			),
			'default' => 'all',
			'required'=> array(
				array('show_search', '=', 1),
			)
		),
		
	)
));

/***** Page Header *****/

/* Breadcrumb */
$breadcrumb_fields = array();
$breadcrumb_navxt_installed = cdhl_is_plugin_installed('breadcrumb-navxt');
if( $breadcrumb_navxt_installed ){
	if ( is_plugin_active( 'breadcrumb-navxt/breadcrumb-navxt.php' ) ) {
		$breadcrumb_navxt_settings_link = add_query_arg(
			array(
				'page' => 'breadcrumb-navxt'
			),
			admin_url( 'options-general.php' )
		);
		$breadcrumb_fields[] = array(
			'id'         => 'display_breadcrumb',
			'type'       => 'switch',
			'title'      => esc_html__('Display Breadcrumb', 'cardealer-helper'),
			'default'    => 0,
			'on'         => esc_html__('Show', 'cardealer-helper'),
			'off'        => esc_html__('Hide', 'cardealer-helper'),
		);
		$breadcrumb_fields[] = array(
			'id'         => 'breadcrumb_full_width',
			'type'       => 'switch',
			'title'      => esc_html__('Breadcrumb Full Width', 'cardealer-helper'),
			'default'    => true,
			'on'         => esc_html__('Yes', 'cardealer-helper'),
			'off'        => esc_html__('No', 'cardealer-helper'),
			'required'	 => array( 'display_breadcrumb', '=', true )
		);
		$breadcrumb_fields[] = array(
			'id'         => 'breadcrumbs_on_mobile',
			'type'       => 'switch',
			'title'      => esc_html__('Breadcrumb on Mobile', 'cardealer-helper'),
			'default'    => true,
			'on'         => esc_html__('Show', 'cardealer-helper'),
			'off'        => esc_html__('Hide', 'cardealer-helper'),
			'required'	 => array( 'display_breadcrumb', '=', true )
		);
		$breadcrumb_fields[] = array(
			'id'   => 'breadcrumb_navxt_settings',
			'type' => 'info',
			'style'=> 'info',
			'title'=> esc_html__('Breadcrumb NavXT Settings', 'cardealer-helper'),
			'desc' => sprintf(
				wp_kses( __( 'Click <a href="%1$s">here</a> for more settings.', 'cardealer-helper'),
					array(
						'a' => array(
							'href' => array(),
						)
					)
				),
				$breadcrumb_navxt_settings_link
			),
			'required' => array(
				array('display_breadcrumb', '=', 1)
			)
		);
	}else{
		$breadcrumb_navxt_activate_link = add_query_arg(
			array(
				's' => 'breadcrumb-navxt'
			),
			admin_url( 'plugins.php' )
		);
		$breadcrumb_fields[] = array(
			'id'     => 'breadcrumb_navxt_inactive',
			'type'   => 'info',
			'notice' => false,
			'style'  => 'info',
			'title'  => __('<strong style="color:#F00;">Breadcrumb NavXT</strong> Inactive', 'cardealer-helper'),
			'desc'   => sprintf( __('Please activate Breadcrumb NavXT to enable breadcrumb options. Click <a href="%1$s">here</a> to activate.', 'cardealer-helper'), $breadcrumb_navxt_activate_link ),
		);
	}
}else{
	$breadcrumb_navxt_install_link = add_query_arg(
		array(
			'tab' => 'search',
			's' => 'breadcrumb-navxt'
		),
		admin_url( 'plugin-install.php' )
	);
	$breadcrumb_fields[] = array(
		'id'     => 'breadcrumb_navxt_not_found',
		'type'   => 'info',
		'notice' => false,
		'style'  => 'info',
		'title'  => __('<strong style="color:#F00;">Breadcrumb NavXT</strong> Not Installed', 'cardealer-helper'),
		'desc'   => sprintf(__('Please install Breadcrumb NavXT to enable breadcrumb options. Click <a href="%1$s">here</a> to install.', 'cardealer-helper'), $breadcrumb_navxt_install_link ),
	);
}

Redux::setSection($opt_name, array(
	'title'           => esc_html__('Page Header', 'cardealer-helper'),
	'desc'            => sprintf( wp_kses( __('This page contains all Inner Page Header settings. Here you can define all <b>Inner Page Header</b> settings.', 'cardealer-helper'), array( 'b' => array() ) )),
	'id'              => 'page_header_subsection',
	'icon' 			  => 'el el-arrow-up',
	'customizer_width'=> '450px',
	'fields'          => array_merge(
							array(
								array(
									'id'     => 'header_info',
									'type'   => 'info',
									'style'  => 'info',
									'title'  => esc_html__('Note: ', 'cardealer-helper'),
									'desc'   => esc_html__('By default, theme will take Inner Page Header Settings from here for all pages. If you want different header settings for any specific pages, then you can do it by : Edit that page and set header settings for that page and save the page.', 'cardealer-helper')
								),
								array(
									'id'            => 'pageheader_height',
									'type'          => 'slider',
									'title'         => esc_html__( 'Page Header Height', 'cardealer-helper' ),
									'subtitle'      => esc_html__( 'Set height of the Inner Pages Header.', 'cardealer-helper' ),
									'default'       => 410,
									'min'           => 100,
									'step'          => 0,
									'max'           => 500,
									'display_value' => 'text',
								),
								array(
									'id'            => 'pageheader_height_mobile',
									'type'          => 'slider',
									'title'         => esc_html__( 'Page Header Height ( Mobile )', 'cardealer-helper' ),
									'subtitle'      => esc_html__( 'Set height of the Inner Pages Header in Mobile devices.', 'cardealer-helper' ),
									'default'       => 320,
									'min'           => 100,
									'step'          => 0,
									'max'           => 500,
									'display_value' => 'text',
								),
								array(
									'id'       => 'titlebar_view',
									'type'     => 'select',
									'title'    => esc_html__('Titlebar Text Align', 'cardealer-helper'),
									'subtitle' => esc_html__('Select alignment of Titlebar.', 'cardealer-helper'),
									'options'  => array(
											'default'  => esc_html__('All Left (default)', 'cardealer-helper'),
											'center'  => esc_html__('All Center', 'cardealer-helper'),
											'right' => esc_html__('All Right', 'cardealer-helper'),
											'title_l_bread_r' => esc_html__('Title Left / Breadcrumb Right', 'cardealer-helper'),
											'bread_l_title_r' => esc_html__('Title Right / Breadcrumb Left', 'cardealer-helper'),					
										),
									'default' => 'title_l_bread_r'
								),
								array(
									'id'         => 'banner_type',
									'type'       => 'button_set',
									'title'      => esc_html__('Banner Type', 'cardealer-helper'),
									'options'    => array(
										'image' => esc_html__('Image', 'cardealer-helper'),
										'color' => esc_html__('Color', 'cardealer-helper'),
										'video' => esc_html__('Video', 'cardealer-helper'),
									),
									'default' => 'image'
								),
								 array(
									'id'                => 'banner_image_bg_custom',
									'type'              => 'background',
									'background-color' 	=> false,
									'transparent' 		=> false,
									'title'      		=> esc_html__('Banner Image', 'cardealer-helper'),
									'desc'       		=> esc_html__('Select background image.', 'cardealer-helper'),
									'required'   		=> array(
										array('banner_type', '=', 'image'),
									),
									'default'     => array(
										'background-repeat' => 'no-repeat',
										'background-attachment' => 'inherit',
										'background-position'=> 'center center',
										'background-size' => 'cover',
										'background-image' => get_template_directory_uri().'/images/default/page-header-bg.jpg'

									)
									
								),
								array(
									'id'         => 'banner_image_opacity',
									'type'       => 'button_set',
									'presets'    => true,
									'title'      => esc_html__('Background Opacity Color', 'cardealer-helper'),
									'required'   => array('banner_type', '=', 'image'),
									'options'    => array(
										'none'  => esc_html__('None', 'cardealer-helper'),
										'black' => esc_html__('Black', 'cardealer-helper'),
										'white' => esc_html__('White', 'cardealer-helper'),
										'custom'=> esc_html__('Custom', 'cardealer-helper'),
									),
									'default' => 'custom',
								),
								array(
									'id' => 'banner_image_opacity_custom_color',
									'type' => 'color_rgba',
									'title' => esc_html__('Background Opacity Color (Custom)', 'cardealer-helper'),
									'default' => array(
										'color' => '#000000',
										'alpha' => '.7'
									),
									'mode' => 'background-color',
									'required' => array(
										array('banner_type', '=', 'image'),
										array('banner_image_opacity', '=', 'custom'),
									),
								),
								array(
									'id' => 'banner_image_color',
									'type' => 'color',
									'title' => esc_html__('Banner (Color)', 'cardealer-helper'),
									'transparent' => false,
									'default' => '#191919',
									'validate' => 'color',
									'mode' => 'background',
									'required' => array('banner_type', '=', 'color'),
								),
								array(
									'id'		=> 'video_type',
									'title'		=> 'Video Type',
									'subtitle' 	=> 'Type of video to play in header background',
									'type'		=> 'select',
									'options'	=> array(
										'youtube' 		=> esc_html__('Youtube Video', 'cardealer-helper'),
										'vimeo' 		=> esc_html__('Vimeo Video', 'cardealer-helper'),
									),
									'default'	=> array('youtube'),
									'required' => array('banner_type', '=', 'video')
								),
								array(
									'id'	=> 'youtube_video',
									'title'	=> esc_html('Youtube Video Link', 'cardealer-helper'),
									'subtitle'	=> esc_html('Youtube Video Link of video to play in background', 'cardealer-helper'),
									'type'	=> 'text',
									'default'=> 'https://www.youtube.com/watch?v=nRRSp120gLs',
									'required' => array( array('banner_type', '=', 'video'), array('video_type', '=', 'youtube') )
								),
								array(
									'id'	=> 'vimeo_video',
									'title'	=> esc_html('Vimeo Video Link', 'cardealer-helper'),
									'subtitle'	=> esc_html('Vimeo Video Link of video to play in background', 'cardealer-helper'),
									'type'	=> 'text',
									'default'=> 'https://vimeo.com/22884674',
									'required' => array( array('video_type', '=', 'vimeo'), array('banner_type', '=', 'video') )
								),
								array(
									'id'         => 'banner_video_opacity',
									'type'       => 'button_set',
									'presets'    => true,
									'title'      => esc_html__('Video Background Opacity Color', 'cardealer-helper'),
									'required'   => array('banner_type', '=', 'video'),
									'options'    => array(
										'none'  => esc_html__('None', 'cardealer-helper'),
										'black' => esc_html__('Black', 'cardealer-helper'),
										'white' => esc_html__('White', 'cardealer-helper'),
										'custom'=> esc_html__('Custom', 'cardealer-helper'),
									),
									'default' => 'custom',
								),
								array(
									'id' => 'banner_video_opacity_custom_color',
									'type' => 'color_rgba',
									'title' => esc_html__('Video Background Opacity Color (Custom)', 'cardealer-helper'),
									'default' => array(
										'color' => '#000000',
										'alpha' => '.7'
									),
									'mode' => 'background-color',
									'required' => array(
										array('banner_type', '=', 'video'),
										array('banner_video_opacity', '=', 'custom'),
									),
								)
								
							),
						$breadcrumb_fields )
));

/***** Footer *****/
Redux::setSection($opt_name, array(
	'title'           => esc_html__('Site Footer', 'cardealer-helper'),
	'id'              => 'appearance_subsection_site_footer',
	'icon' 			  => 'el el-arrow-down',
	'customizer_width'=> '450px',
	'fields'          => array(
		/*footer widget options */
		array(
			'id'         => 'footer_column_layout',
			'type'       => 'image_select',
			'title'      => esc_html__('Footer Column Layout', 'cardealer-helper'),
			'options'    => array(
				'one-column'   		=> array( 'alt'=> 'One Column' , 'img'=> CDHL_URL . '/images/radio-button-imgs/footer_layout/one-column.png' ),
				'two-columns'   	=> array( 'alt'=> 'Two Columns' , 'img'=> CDHL_URL . '/images/radio-button-imgs/footer_layout/two-columns.png' ),
				'three-columns'  	=> array( 'alt'=> 'Three Columns', 'img'=> CDHL_URL . '/images/radio-button-imgs/footer_layout/three-columns.png' ),
				'four-columns'  	=> array( 'alt'=> 'Four Columns' , 'img'=> CDHL_URL . '/images/radio-button-imgs/footer_layout/four-columns.png' ),
				'8-4-columns'   	=> array( 'alt'=> '8 + 4 Columns' , 'img'=> CDHL_URL . '/images/radio-button-imgs/footer_layout/8-4-columns.png' ),
				'4-8-columns'   	=> array( 'alt'=> '4 + 8 Columns' , 'img'=> CDHL_URL . '/images/radio-button-imgs/footer_layout/4-8-columns.png' ),
				'6-3-3-columns'   	=> array( 'alt'=> '6 + 3 + 3 Columns' , 'img'=> CDHL_URL . '/images/radio-button-imgs/footer_layout/6-3-3-columns.png' ),
				'3-3-6-columns'   	=> array( 'alt'=> '3 + 3 + 6 Columns' , 'img'=> CDHL_URL . '/images/radio-button-imgs/footer_layout/3-3-6-columns.png' ),
				'8-2-2-columns'  	=> array( 'alt'=> '8 + 2 + 2 Columns' , 'img'=> CDHL_URL . '/images/radio-button-imgs/footer_layout/8-2-2-columns.png' ),
				'2-2-8-columns'   	=> array( 'alt'=> '2 + 2 + 8 Columns' , 'img'=> CDHL_URL . '/images/radio-button-imgs/footer_layout/2-2-8-columns.png' ),
				'6-2-2-2-columns'   => array( 'alt'=> '6 + 2 + 2 + 2 Columns' , 'img'=> CDHL_URL . '/images/radio-button-imgs/footer_layout/6-2-2-2-columns.png' ),
				'2-2-2-6-columns'   => array( 'alt'=> '2 + 2 + 2 + 6 Columns' , 'img'=> CDHL_URL . '/images/radio-button-imgs/footer_layout/2-2-2-6-columns.png' )
			),
			'default' => 'four-columns',
		),
		array(
			'id' => 'footer_title_color',
			'type' => 'color',
			'title' => esc_html__('Footer Title Color', 'cardealer-helper'),
			'subtitle' => esc_html__('Custom color for footer titles', 'cardealer-helper'),
			'transparent' => false,
			'default' => 'white',
			'validate' => 'color'
		),
        array(
			'id' => 'footer_text_color',
			'type' => 'color',
			'title' => esc_html__('Footer Text Color', 'cardealer-helper'),
			'subtitle' => esc_html__('Custom color for footer text', 'cardealer-helper'),
			'transparent' => false,
			'default' => '#909090',
			'validate' => 'color'
		),
		 array(
			'id' => 'footer_link_color',
			'type' => 'color',
			'title' => esc_html__('Footer Link Color', 'cardealer-helper'),
			'subtitle' => esc_html__('Custom color for footer link', 'cardealer-helper'),
			'transparent' => false,
			'default' => 'red',
			'validate' => 'color'
		),
       	/* display option For Footer Color or image */
        array(
			'id'         => 'banner_type_footer',
			'type'       => 'button_set',
			'title'      => esc_html__('Background Type', 'cardealer-helper'),
			'options'    => array(
				'image' => esc_html__('Image', 'cardealer-helper'),
				'color' => esc_html__('Color', 'cardealer-helper'),
			),
			'default' => 'image',
		),
		array(
			'id'       			=> 'footer_background_img',
			'type'     			=> 'background',
			'title'    			=> esc_html__('Footer Background', 'cardealer-helper'),
			'subtitle' 			=> esc_html__('Footer Background Image.', 'cardealer-helper'),
			'background-color' 	=> false,
			'transparent' 		=> false,
			'default'  => array( 'background-repeat'=> 'no-repeat', 'background-image'=> get_template_directory_uri().'/images/default/page-footer-bg.jpg', 'background-size' => 'cover', 'background-attachment' => 'fixed', 'background-position' => 'center center'),
			'required'   => array(
				array('banner_type_footer', '=', 'image')
			)
		),
		array(
			'id'         => 'banner_image_opacity_footer',
			'type'       => 'button_set',
			'presets'    => true,
			'title'      => esc_html__('Background Opacity Color', 'cardealer-helper'),
			'required'   => array('banner_type_footer', '=', 'image'),
			'options'    => array(
				'none'  => esc_html__('None', 'cardealer-helper'),
				'black' => esc_html__('Black', 'cardealer-helper'),
				'white' => esc_html__('White', 'cardealer-helper'),
				'custom'=> esc_html__('Custom', 'cardealer-helper')
			),
			'default' => 'custom',
		),
		array(
			'id' => 'banner_image_opacity_custom_color_footer',
			'type' => 'color_rgba',
			'title' => esc_html__('Background Opacity Color (Custom)', 'cardealer-helper'),
			'default' => array(
				'color' => '#000000',
				'alpha' => '.9'
			),
			'mode' => 'background-color',
			'required' => array(
				array('banner_type_footer', '=', 'image'),
				array('banner_image_opacity_footer', '=', 'custom'),
			),
		),
		array(
			'id' => 'footer_background_footer',
			'type' => 'color',
			'title' => esc_html__('Footer Background Color', 'cardealer-helper'),
			'subtitle' => esc_html__('Custom color for footer background', 'cardealer-helper'),
			'transparent' => false,
			'default' => '#191919',
			'validate' => 'color',
			'mode' => 'background',
			'required' => array('banner_type_footer', '=', 'color'),
		),
        /* option ends for default style background color */
        array(
			'id'         => 'show_footer_top',
			'type'       => 'button_set',
			'title'      => esc_html__('Show Footer Top', 'cardealer-helper'),
			'desc'    => 'This section contains social media icons',
			'options'    => array(
				'yes' => esc_html__('Yes','cardealer-helper'),
				'no' => esc_html__('No','cardealer-helper'),
			),
			'default' => 'yes',
		),
		array(
			'id'      => 'social_icon_list',
			'title'   => 'Add Social Icons',
			'type'    => 'sorter',
			'subtitle'    => 'Select Social Icons to add in Footer Top.',
			'options' => array(
				'Available Social Icons' => array(),
				'Icons to Add'  => array(
					'facebook'		=> 'Facebook',
					'twitter'  		=> 'Twitter',
					'dribbble'  	=> 'Dribbble',
					'google_plus'  	=> 'Google Plus',
					'vimeo'		 	=> 'Vimeo',
					'pinterest'    	=> 'Pinterest',
					'behance'  		=> 'Behance',
					'linkedin'  	=> 'Linkedin',
					'instagram'  	=> 'Instagram'
				)
			),
			'required' => array('show_footer_top', '=', 'yes')
		),
		/* disaply additional footer widget 5 */
		array(
			'id'         => 'show_footer_bottom',
			'type'       => 'button_set',
			'title'      => esc_html__('Show Footer Bottom', 'cardealer-helper'),
			'options'    => array(
				'yes' => esc_html__('Yes','cardealer-helper'),
				'no' => esc_html__('No','cardealer-helper'),
			),
			'default' => 'yes'
		),
		array(
			'id' => 'copyright_section_start',
			'type' => 'section',
			'title' => 'Copyright Section',
			'indent' => true			
		),
        array(
			'id'         => 'enable_copyright_footer',
			'type'       => 'button_set',
			'title'      => esc_html__('Show Copyright Text', 'cardealer-helper'),
			'options'    => array(
				'yes' => esc_html__('Yes','cardealer-helper'),
				'no' => esc_html__('No','cardealer-helper'),
			),
			'default' => 'yes',
		),
		array(
			'id' => 'copyright_back_color',
			'type' => 'color',
			'title' => esc_html__('Copyright Background Color', 'cardealer-helper'),
			'subtitle' => esc_html__('Custom color for copyright section background', 'cardealer-helper'),
			'transparent' => false,
			'default' => '#000000',
			'validate' => 'color',
			'mode' => 'background',
			'required' => array('enable_copyright_footer', '=', 'yes')
		),
		array(
			'id'         => 'copyright_opacity',
			'type'       => 'button_set',
			'presets'    => true,
			'title'      => esc_html__('Background Opacity Color', 'cardealer-helper'),
			'required'   => array('banner_type', '=', 'image'),
			'options'    => array(
				'none'  => esc_html__('None', 'cardealer-helper'),
				'black' => esc_html__('Black', 'cardealer-helper'),
				'white' => esc_html__('White', 'cardealer-helper'),
				'custom'=> esc_html__('Custom', 'cardealer-helper'),
			),
			'default' => 'custom',
			'required' => array('enable_copyright_footer', '=', 'yes')
		),
		array(
			'id' => 'copyright_opacity_custom_color',
			'type' => 'color_rgba',
			'title' => esc_html__('Background Opacity Color (Custom)', 'cardealer-helper'),
			'default' => array(
				'color' => '#000',
				'alpha' => '.8'
			),
			'mode' => 'background-color',
			'required' => array(
				array('copyright_opacity', '=', 'custom'),
				array('enable_copyright_footer', '=', 'yes')
			),
		),
		array(
			'id' => 'copyright_text_color',
			'type' => 'color',
			'title' => esc_html__('Text Color', 'cardealer-helper'),
			'subtitle' => esc_html__('Custom color for copyright section font color', 'cardealer-helper'),
			'transparent' => false,
			'default' => '#626262',
			'validate' => 'color',
			'required' => array('enable_copyright_footer', '=', 'yes')
		),
		array(
			'id'         => 'footer_text_left',
			'type'       => 'editor',
			'title'      => esc_html__('Footer Text Left', 'cardealer-helper'),
			'subtitle'      => sprintf(
									wp_kses(
										__('You can use following shortcodes in your footer text: <br><span class="code">[cd-year]</span> <span class="code">[cd-site-title]</span> <span class="code">[cd-footer-menu]</span>', 'cardealer-helper'),
										array(
											'span' => array(
														'class' => array()
													),
											'br' => array()
										)
									)
								),
			'default' => '&copy;' . esc_html__('Copyright ', 'cardealer-helper') . '[cd-year] [cd-site-title]',
            'required' => array('enable_copyright_footer', '=', 'yes'),
		),
		array(
			'id'         => 'footer_text_right',
			'type'       => 'editor',
			'title'      => esc_html__('Footer Text Right', 'cardealer-helper'),
			'subtitle'      => sprintf(
									wp_kses(
										__('You can use following shortcodes in your footer text: <br><span class="code">[cd-year]</span> <span class="code">[cd-site-title]</span> <span class="code">[cd-footer-menu]</span>', 'cardealer-helper'),
										array(
											'span' => array(
														'class' => array()
													),
											'br' => array()
										)
									)
								),
			'default' => '[cd-footer-menu]',
            'required' => array('enable_copyright_footer', '=', 'yes'),
		),
		array(
			'id' => 'copyright_section_end',
			'type' => 'section',
			'indent' => false,
		)
	)
));
/* Color Scheme Options */
Redux::setSection($opt_name, array(
	'title'           => esc_html__('Color Scheme', 'cardealer-helper'),
	'desc'             => esc_html__( 'In color schemes, you can change the site default color and set as per your site design.','cardealer-helper'),
	'id'              => 'color_scheme',
	'icon' 			  => 'el el-adjust-alt',
	'customizer_width'=> '450px',
	'fields'          => array(
		array(
			'id' => 'site_color_scheme_custom',
			'type' => 'color_rgba',
			'title' => esc_html__('Primary Color', 'cardealer-helper'),
			'desc' => esc_html__('Set color of main theme color, main background color and image overlay (color with opacity on image) color. You can set opacity of main color from bottom side bar in dialog.', 'cardealer-helper'),
			'default' => array(
				'color' => '#DB2E2D',
				'alpha' => '.8'
			),
			'mode' => 'background-color'
		),
		array(
			'id'         => 'site_color_scheme_custom_secondary',
			'type'       => 'color',
			'title'      => esc_html__('Secondary Color', 'cardealer-helper'),
			'desc' => esc_html__('Theme dark title and background.', 'cardealer-helper'),
			'default'    => '#363636',
			'transparent'=> false,
		),
		array(
			'id'         => 'site_color_scheme_custom_tertiary',
			'type'       => 'color',
			'title'      => esc_html__('Tertiary Color', 'cardealer-helper'),
			'desc' => esc_html__('Theme Description color and border colors.', 'cardealer-helper'),
			'default'    => '#999999',
			'transparent'=> false,
		),
		array(
			'id'      => 'other_color_settings',
			'type'    => 'info',
			'style'   => 'info',
			'title'   => esc_html__('Other Color Settings', 'cardealer-helper'),
			'desc'    =>wp_kses( __( 'Apart from these colors, there are some specific section, whose colors can be managed from there only<br><br>
						<strong>Details are as below :</strong><br><strong>Header :</strong> For header color settings, go to Theme Options > Site Header.
						<br><strong>Footer :</strong> For footer color settings, go to Theme Options > Site Footer.<br>
						<strong>Revolution Slider :</strong> For color setting in Revolution Slider go to Slider Revolution.', 'cardealer-helper'),
							array(
								'br' => array(),
								'strong' => array()
						)
					),
		),
	)
));
/* Sidebar Settings options */
Redux::setSection($opt_name, array(
	'title'           => esc_html__('Sidebar Settings', 'cardealer-helper'),
	'id'              => 'sidebar_settings_option',
	'desc'            => esc_html__('Select page sidebar alignment.', 'cardealer-helper'),
	'customizer_width'=> '400px',
	'icon'            => 'el el-pause icon-large',
	'desc'            =>  esc_html__('This setting will be applied to pages, custom post types, buddypress pages and bbpress pages','cardealer-helper'),	
	'fields'          => array(
		array(
			'id'      => 'page_sidebar',
			'type'    => 'image_select',
			'title'   => esc_html__('Page Sidebar', 'cardealer-helper'),
			'options' => array(
				'full_width' => array(
					'alt' => 'Full Width',
					'img' => CDHL_URL . '/images/radio-button-imgs/page_sidebar/full_width.png'
				),
				'left_sidebar' => array(
					'alt' => 'Left Sidebar',
					'img' => CDHL_URL . '/images/radio-button-imgs/page_sidebar/left_sidebar.png'
				),
				'right_sidebar' => array(
					'alt' => 'Right Sidebar',
					'img' => CDHL_URL . '/images/radio-button-imgs/page_sidebar/right_sidebar.png'
				),
				'two_sidebar' => array(
					'alt' => 'Two Sidebar',
					'img' => CDHL_URL . '/images/radio-button-imgs/page_sidebar/two_sidebar.png'
				),
			),
			'default' => 'right_sidebar'
		),
		
	)
));
/* ------------------------------------------------------------------------
 *
 * Car Settings
 *
 * ------------------------------------------------------------------------ */
Redux::setSection($opt_name, array(
	'title'           => esc_html__('Car Settings', 'cardealer-helper'),
	'id'              => 'car_settings',
	'customizer_width'=> '400px',
	'icon'            => 'el el-car',
	'fields'          => array(
	)
));

/*Dealer Cars Currency Settings options */
Redux::setSection($opt_name, array(
    'title' => __('Currency Settings', 'cardealer-helper'),
    'id' => 'currency-settings',
	'subsection'      => true,
    'customizer_width' => '400px',
    'icon' => 'fa fa-usd',
    'fields' => array(
        array(
			'id'     => 'cars-currency-symbol',
			'type'   => 'select',
			'title'  => esc_html__('Currency Symbol', 'cardealer-helper'),
			'options'=> cdhl_currency_option_list(),
			'default' => 'USD'			
		),
        array(
			'id'     => 'cars-currency-symbol-placement',
			'type'   => 'button_set',
			'title'  => esc_html__('Currency Symbol Placement', 'cardealer-helper'),
			'options'=> array(
				'1' => esc_html__('Before Value', 'cardealer-helper'),
				'2' => esc_html__('After Value', 'cardealer-helper')
			),
			'default' => '1'			
		),
        array(
			'id'     => 'cars-disable-currency-separators',
			'type'   => 'button_set',
			'title'  => esc_html__('Display Currency Separators', 'cardealer-helper'),
			'options'=> array(
				'1' => esc_html__('Yes', 'cardealer-helper'),
				'0' => esc_html__('No', 'cardealer-helper'),
			),
			'default' => '1'            		
		),
        array(
			'id'     => 'cars-thousand-separator',
			'type'   => 'text',
			'title'  => esc_html__('Thousand Separator', 'cardealer-helper'),			
			'desc'     => esc_html__('Enter in a separator for large currency amounts', 'cardealer-helper'),
			'default'  => ',',
            'required' => array(
				'cars-disable-currency-separators', '=', '1'
			),				
		),
        array(
			'id'     => 'cars-number-decimals',
			'type'   => 'text',
			'title'  => esc_html__('Number of Decimals', 'cardealer-helper'),			
			'desc'     => esc_html__('Number of decimals to be displayed after the price', 'cardealer-helper'),
            'default'  => '2',
			'required' => array(
				'cars-disable-currency-separators', '=', '1'
			),			
		)		
    )
));

// Car Page Settings options
Redux::setSection($opt_name, array(
	'title'           => esc_html__('Car Page Settings', 'cardealer-helper'),
	'id'              => 'car_page_settings',
	'subsection'      => true,
	'customizer_width'=> '450px',
	'icon'            => 'fa fa-file',
	'fields'          => array(
            array(
            	'id'     => 'cars-listing-title',
            	'type'   => 'text',
            	'title'  => esc_html__('Cars Inventory Page Title', 'cardealer-helper'),			
            	'desc'     => esc_html__('Title for cars inventory listing page. If page is created for car listing, then it will take title from that page.', 'cardealer-helper'),
            	'default'  => 'Cars'                				
            ),
            array(
            	'id'     => 'cars-details-title',
            	'type'   => 'text',
            	'title'  => esc_html__('Cars Details Page Title', 'cardealer-helper'),			
            	'desc'     => esc_html__('Enter title for cars details page', 'cardealer-helper'),
            	'default'  => 'Cars Details'                				
            ),
            array(
                'id'       => 'cars-per-page',
                'type'     => 'select',
                'title'    => esc_html__( 'Cars Per Page', 'cardealer-helper' ),
                'subtitle' => esc_html__( 'Select number of display on cars listing page', 'cardealer-helper' ),
                //Must provide key => value pairs for radio options
                'options'  => array(
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                    '11' => '11',
                    '12' => '12',
                    '13' => '13',
                    '14' => '14',
                    '15' => '15',
                    '16' => '16',
                ),
                'default'  => '12'
            ),
          array(
              'id'       => 'cars-col-sel',
              'type'     => 'select',
              'title'    => esc_html__( 'Cars Listing Column', 'cardealer-helper' ),
              'subtitle' => esc_html__( 'Select number of column on listing page', 'cardealer-helper' ),
              //Must provide key => value pairs for radio options
              'options'  => array(
                  '3' => '3 Column',
                  '4' => '4 Column',
              ),
              'default'  => '3'
          ),

          array(
              'id'       => 'cars-lay-style',
              'type'     => 'select',
              'title'    => esc_html__( 'Default listing layout', 'cardealer-helper' ),
              'subtitle' => esc_html__( 'Select layout style', 'cardealer-helper' ),
              //Must provide key => value pairs for radio options
              'options'  => array(
                  'view-grid-left' => 'With sidebar grid left',
                  'view-grid-right' => 'With sidebar grid right',
                  'view-grid-full' => 'Without sidebar grid full',
                  'view-list-left' => 'With sidebar list left',
                  'view-list-right' => 'With sidebar list right',
                  'view-list-full' => 'Without sidebar list full',
              ),
              'default'  => 'view-grid-left'
          ),

          array(
              'id'       => 'cars-filter-with',
              'type'     => 'button_set',
              'title'    => esc_html__( 'Cars Listing Filter With Ajax', 'cardealer-helper' ),
              'subtitle' => esc_html__( 'Filter with ajax', 'cardealer-helper' ),
              'desc'     => esc_html__( 'Filter cars listing with ajax methods', 'cardealer-helper' ),
              //Must provide key => value pairs for radio options
              'options'=> array(
                'yes' => esc_html__('Enabled', 'cardealer-helper'),
                'no' => esc_html__('Disabled', 'cardealer-helper'),
              ),
              'default' => 'yes'
          ),
          array(
              'id'       => 'cars-year-range-slider',
              'type'     => 'button_set',
              'title'    => esc_html__( 'Year Range Slider', 'cardealer-helper' ),
              'subtitle' => esc_html__( 'Filter with year range slider', 'cardealer-helper' ),
              'desc'     => esc_html__( 'Filter cars listing with year range slider', 'cardealer-helper' ),
              'options'=> array(
                'yes' => esc_html__('Enabled', 'cardealer-helper'),
                'no' => esc_html__('Disabled', 'cardealer-helper'),
              ),
              'default' => 'no'
          ),
          array(
              'id'       => 'cars-details-page-sidebar',
              'type'     => 'select',
              'title'    => esc_html__( 'Cars Details Page Sidebar ', 'cardealer-helper' ),
              'subtitle' => esc_html__( 'Select sidebar on car details page', 'cardealer-helper' ),
              //Must provide key => value pairs for radio options
              'options'  => array(
                  'left' => 'Left Sidebar',
                  'right' => 'Right Sidebar',
                  'no' => 'No Sidebar',
              ),
              'default'  => 'no'
          ),
          array(
        	'id'       => 'cars-details-layout',
        	'type'     => 'image_select',
        	'title'    => esc_html__('Cars Details Page Layout', 'cardealer-helper'),
        	'subtitle' => esc_html__('Select cars details page layout', 'cardealer-helper'),
        	'options'  => array(
        		'1' => array(
        			'alt' => 'Layout 1',
        			'img' => CDHL_URL . '/images/radio-button-imgs/cars-details-layout/border.png'
        		),
        		'2' => array(
        			'alt' => 'Layout 2',
        			'img' => CDHL_URL . '/images/radio-button-imgs/cars-details-layout/default.png'
        		),
        		'3' => array(
        			'alt' => 'Layout 3',
        			'img' => CDHL_URL . '/images/radio-button-imgs/cars-details-layout/black.png'
        		)
        	),
        	'default'  => '1',
        ),
          array(
            'id'    => 'car_no_sold',
            'type'  => 'switch',
            'title' => esc_html__( 'Sold vehicles', 'cardealer-helper' ),
            'desc'  => esc_html__( 'This will hide the vehicles that are sold, sold vehicles can still be shown by adding "&show_sold" to the end of the URL', 'cardealer-helper' ),
            'on'    => esc_html__( "Show", "cardealer-helper" ),
            'off'   => esc_html__( "Hide", "cardealer-helper" ),
            'default' => true
          ),
	)
));

// Car Form Settings Options
Redux::setSection($opt_name, array(
	'id'              => 'form_settings',
	'subsection'      => true,
	'title'           => esc_html__('Custom Forms', 'cardealer-helper'),
	'desc' 			  => sprintf( wp_kses( __( 'You can use <a href="%1$s">Contact Form 7</a> for the following
forms by pasting a shortcode of the form in the corresponding text box. If <a href="%1$s">Contact Form 7</a> not used then default form will be shown.<br><br><b>Note : </b>In order to use Custom Dealer Inquiry Forms, you will have to integrate your site with <b>Google Captcha</b> and provide <b>"Site Key" and "Secret Key"</b> of google captcha in "Google Captcha Settings" within theme options<b> ( Car Dealer > Car Settings > API Keys > Google Captcha Settings )</b>', 'cardealer-helper'),
					array(
						'a' => array(
							'href' => array(),
						),
						'b' => array(),
						'br' => array()
					)
				), esc_url('https://wordpress.org/plugins/contact-form-7/') ),
	'customizer_width'=> '450px',
	'icon'            => 'fa fa-bars',
	'fields'          => array(
		array(
			'id' 	=> 'req_info_start',
			'type' 	=> 'section',
			'title' => 'Request More Info',
			'indent' =>	true
		),
        array(
			'id' => 'req_info_form_status',
			'type' => 'switch',
			'title' => esc_html__('Enable / Disable', 'cardealer-helper'),			
            'desc'      => esc_html__( 'Show/hide request info form for front end.', 'cardealer-helper'),
            'default'  => true
		),
		array(
			'id' => 'req_info_contact_7',
			'type' => 'switch',
			'title' => esc_html__('Use Contact Form 7', 'cardealer-helper'),
			'on'	=> esc_html__('Yes', 'cardealer-helper'),
			'off'	=> esc_html__('No', 'cardealer-helper'),
			'default' => false,
            'required' => array('req_info_form_status', '=', true)
		),
		array(
			'id' => 'req_info_form',
			'type' => 'text',
			'title' => esc_html__('Contact Form 7 Shortcode', 'cardealer-helper'),
			'required' => array('req_info_contact_7', '=', true,'req_info_form_status', '=', true)

		),
		array(
			'id'         => 'inq_mail_from_name',
			'type'       => 'text',
			'title'      => esc_html__('From Mail Name', 'cardealer-helper'),
			'default' => esc_html__('Car Dealer','cardealer-helper'),
			'required' => array('req_info_contact_7', '=', false)
		),
		array(
			'id'         => 'inq_mail_id_from',
			'type'       => 'text',
			'title'      => esc_html__('From Mail ID', 'cardealer-helper'),
			'default' => esc_html__('support@example.com','cardealer-helper'),
			'required' => array('req_info_contact_7', '=', false)
		),
		array(
			'id'         => 'inq_subject',
			'type'       => 'text',
			'title'      => esc_html__('Mail Subject', 'cardealer-helper'),
			'default' => esc_html__('Car Dealer - Inquiry Received','cardealer-helper'),
			'required' => array('req_info_contact_7', '=', false)
		),
		array(
			'id'         => 'inq_adf_mail_to',
			'type'       => 'text',
			'title'      => esc_html__('ADF Mail To', 'cardealer-helper'),
			'desc'       => esc_html__('You can add multiple email IDs separated by "," (comma).', 'cardealer-helper'),
			'default' => esc_html__('support@example.com','cardealer-helper'),
			'required' => array('req_info_contact_7', '=', false)
		),
		array(
			'id'         => 'inq_html_mail_to',
			'type'       => 'text',
			'title'      => esc_html__('HTML Mail To', 'cardealer-helper'),
			'desc'       => esc_html__('You can add multiple email IDs separated by "," (comma).', 'cardealer-helper'),
			'default' => esc_html__('support@example.com','cardealer-helper'),
			'required' => array('req_info_contact_7', '=', false)
		),
		array(
			'id'	=> 'req_info_html_mail_body',
			'type' 	=> 'editor',
			'args'   => array(
				'teeny'       	=> false,
				'wpautop'     	=> false,
				'quicktags'		=> 1,
				'textarea_rows' => 20
			),
			'title'	   => esc_html__('HTML Mail Body', 'cardealer-helper'),
			'subtitle' => esc_html__('Request More Info Form Mail Body', 'cardealer-helper'),
			'desc'	   => sprintf(
								wp_kses(
									__('Use <a href="#" class="cd_dialog" data-id="rmi-1">this</a> variables to build or update mail body.
									<div id="rmi-1" class="variable-content" title="Request More Info"><p>%1$s</p></div>', 'cardealer-helper'),
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
									)
								),
								'From mail name : #CD_FROM_NAME#,<br>First Name : #CD_FIRST_NAME#<br>Last Name : #CD_LAST_NAME#<br>Email : #CD_EMAIL#<br>Mobile : #CD_MOBILE#<br>Address : #CD_ADDRESS#<br>State : #CD_STATE#<br>Zip : #CD_ZIP#<br>Preferred Contact : #CD_PREFERRED_CONTACT#<br>Product Detail : #PRODUCT_DETAIL#'),
			'required' => array('req_info_contact_7', '=', false),
			'default'  => sprintf( wp_kses(
								__('<p>Hello #CD_FROM_NAME#,<br /><br />One Inquiry received.<br />Following are the information of the Inquiry :<br /><br />First Name : #CD_FIRST_NAME#<br />Last Name : #CD_LAST_NAME#<br />Email : #CD_EMAIL#<br />Mobile : #CD_MOBILE#<br />Address : #CD_ADDRESS#<br />State : #CD_STATE#<br />Zip : #CD_ZIP#<br />Preferred Contact : #CD_PREFERRED_CONTACT#<br />Product Detail : #PRODUCT_DETAIL#<br /><br />Thanks and Regards,<br />Car Dealer Team</p>', 'cardealer-helper'),
								array( 'br' => array())
								) )
		),
		array(
			'id'	=> 'inq_wid_html_mail_body',
			'type' 	=> 'editor',
			'args'   => array(
				'teeny'       	=> false,
				'wpautop'     	=> false,
				'quicktags'		=> 1,
				'textarea_rows' => 20
			),
			'title'	=> esc_html__('HTML Body without product detail', 'cardealer-helper'),
			'subtitle' => esc_html__('Request more info form html mail body without product detail to be used in Inquiry Widget', 'cardealer-helper'),
			'desc'	   => sprintf(
								wp_kses(
									__('Use <a href="#" class="cd_dialog" data-id="rmi-2">this</a> variables to build or update mail body.
									<div id="rmi-2" class="variable-content" title="Request More Info"><p>%1$s</p></div>', 'cardealer-helper'),
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
									)
								),
								'From mail name : #CD_FROM_NAME#,<br>First Name : #CD_FIRST_NAME#<br>Last Name : #CD_LAST_NAME#<br>Email : #CD_EMAIL#<br>Mobile : #CD_MOBILE#<br>Address : #CD_ADDRESS#<br>State : #CD_STATE#<br>Zip : #CD_ZIP#<br>Preferred Contact : #CD_PREFERRED_CONTACT#'),
			'required' => array('req_info_contact_7', '=', false),
			'default' => sprintf( wp_kses(
								__('<p>Hello #CD_FROM_NAME#,<br /><br />One Inquiry received.<br />Following are the information of the Inquiry :<br /><br />First Name : #CD_FIRST_NAME#<br />Last Name : #CD_LAST_NAME#<br />Email : #CD_EMAIL#<br />Mobile : #CD_MOBILE#<br />Address : #CD_ADDRESS#<br />State : #CD_STATE#<br />Zip : #CD_ZIP#<br />Preferred Contact : #CD_PREFERRED_CONTACT#<br /><br />Thanks and Regards,<br />Car Dealer Team</p>', 'cardealer-helper'),
								array( 'br' => array())
								) )
		),
		array(
			'id'         => 'inq_text_mail_to',
			'type'       => 'text',
			'title'      => esc_html__('Text Mail To', 'cardealer-helper'),
			'desc'       => esc_html__('You can add multiple email IDs separated by "," (comma).', 'cardealer-helper'),
			'default' => esc_html__('support@example.com','cardealer-helper'),
			'required' => array('req_info_contact_7', '=', false)
		),
		array(
			'id'	=> 'req_info_text_mail_body',
			'type' 	=> 'textarea',
			'args'   => array(
				'teeny'       	=> false,
				'wpautop'     	=> false,
				'quicktags'		=> 1,
				'textarea_rows' => 20
			),
			'title'	=> esc_html__('Text Mail Body', 'cardealer-helper'),
			'subtitle' => esc_html__('Request More Info Form Mail Body', 'cardealer-helper'),
			'desc'	   => sprintf(
								wp_kses(
									__('Use <a href="#" class="cd_dialog" data-id="rmi-3">this</a> variables to build or update mail body.
									<div id="rmi-3" class="variable-content" title="Request More Info"><p>%1$s</p></div>', 'cardealer-helper'),
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
									)
								),
								'From mail name : #CD_FROM_NAME#,<br>First Name : #CD_FIRST_NAME#<br>Last Name : #CD_LAST_NAME#<br>Email : #CD_EMAIL#<br>Mobile : #CD_MOBILE#<br>Address : #CD_ADDRESS#<br>State : #CD_STATE#<br>Zip : #CD_ZIP#<br>Preferred Contact : #CD_PREFERRED_CONTACT#<br>Product Detail : #PRODUCT_DETAIL#'),'required' => array('req_info_contact_7', '=', false),
			'default' => esc_html__('Hello #CD_FROM_NAME#,

One Inquiry received.
Following are the information of the Inquiry :

First Name : #CD_FIRST_NAME#
Last Name : #CD_LAST_NAME#
Email : #CD_EMAIL#
Mobile : #CD_MOBILE#
Address : #CD_ADDRESS#
State : #CD_STATE#
Zip : #CD_ZIP#
Preferred Contact : #CD_PREFERRED_CONTACT#
Product Detail :

#PRODUCT_DETAIL#

Thanks and Regards,
Car Dealer Team', 'cardealer-helper')
		),
		array(
			'id'	=> 'inq_wid_text_mail_body',
			'type' 	=> 'textarea',
			'args'   => array(
				'teeny'       	=> false,
				'wpautop'     	=> false,
				'quicktags'		=> 1,
				'textarea_rows' => 20
			),
			'title'	=> esc_html__('Text Body without product detail', 'cardealer-helper'),
			'subtitle' => esc_html__('Request more info form text mail body without product detail to be used in Inquiry Widget', 'cardealer-helper'),
			'desc'	   => sprintf(
								wp_kses(
									__('Use <a href="#" class="cd_dialog" data-id="rmi-4">this</a> variables to build or update mail body.
									<div id="rmi-4" class="variable-content" title="Request More Info"><p>%1$s</p></div>', 'cardealer-helper'),
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
									)
								),
								'From mail name : #CD_FROM_NAME#,<br>First Name : #CD_FIRST_NAME#<br>Last Name : #CD_LAST_NAME#<br>Email : #CD_EMAIL#<br>Mobile : #CD_MOBILE#<br>Address : #CD_ADDRESS#<br>State : #CD_STATE#<br>Zip : #CD_ZIP#<br>Preferred Contact : #CD_PREFERRED_CONTACT#'),
			'required' => array('req_info_contact_7', '=', false),
			'default' => esc_html__('Hello #CD_FROM_NAME#,

One Inquiry received.
Following are the information of the Inquiry :

First Name : #CD_FIRST_NAME#
Last Name : #CD_LAST_NAME#
Email : #CD_EMAIL#
Mobile : #CD_MOBILE#
Address : #CD_ADDRESS#
State : #CD_STATE#
Zip : #CD_ZIP#
Preferred Contact : #CD_PREFERRED_CONTACT#


Thanks and Regards,
Car Dealer Team', 'cardealer-helper')
		),
		array(
			'id' 	=> 'req_info_end',
			'type' 	=> 'section',
			'indent' =>	false
		),
		array(
			'id' 	=> 'schedule_drive_start',
			'type' 	=> 'section',
			'title' => 'Schedule Test Drive',
			'indent' =>	true
		),
        array(
			'id' => 'schedule_drive_form_status',
			'type' => 'switch',
			'title' => esc_html__('Enable / Disable', 'cardealer-helper'),			
            'desc'      => esc_html__( 'Show/hide schedule drive form for front end.', 'cardealer-helper'),
            'default'  => true
		),
		array(
			'id' => 'schedule_drive_contact_7',
			'type' => 'switch',
			'title' => esc_html__('Use Contact Form 7', 'cardealer-helper'),
			'on'	=> esc_html__('Yes', 'cardealer-helper'),
			'off'	=> esc_html__('No', 'cardealer-helper'),
			'default' => false,
            'required' => array('schedule_drive_form_status', '=', true)
		),
		array(
			'id' => 'schedule_drive_form',
			'type' => 'text',
			'title' => esc_html__('Contact Form 7 Shortcode', 'cardealer-helper'),
			'required' => array('schedule_drive_contact_7', '=', true)
		),
		array(
			'id'         => 'std_mail_from_name',
			'type'       => 'text',
			'title'      => esc_html__('From Mail Name', 'cardealer-helper'),
			'default' => esc_html__('Car Dealer','cardealer-helper'),
			'required' => array('schedule_drive_contact_7', '=', false)
		),
		array(
			'id'         => 'std_mail_id_from',
			'type'       => 'text',
			'title'      => esc_html__('From Mail ID', 'cardealer-helper'),
			'default' => esc_html__('support@example.com','cardealer-helper'),
			'required' => array('schedule_drive_contact_7', '=', false)
		),
		array(
			'id'         => 'std_subject',
			'type'       => 'text',
			'title'      => esc_html__('Mail Subject', 'cardealer-helper'),
			'default' => esc_html__('Car Dealer - Test Drive Inquiry Received','cardealer-helper'),
			'required' => array('schedule_drive_contact_7', '=', false)
		),
		array(
			'id'         => 'std_adf_mail_to',
			'type'       => 'text',
			'title'      => esc_html__('Mail To', 'cardealer-helper'),
			'desc'       => esc_html__('You can add multiple email IDs separated by "," (comma).', 'cardealer-helper'),
			'default' => esc_html__('support@example.com','cardealer-helper'),
			'required' => array('schedule_drive_contact_7', '=', false)
		),
		array(
			'id'         => 'std_html_mail_to',
			'type'       => 'text',
			'title'      => esc_html__('HTML Mail To', 'cardealer-helper'),
			'desc'       => esc_html__('You can add multiple email IDs separated by "," (comma).', 'cardealer-helper'),
			'default' => esc_html__('support@example.com','cardealer-helper'),
			'required' => array('schedule_drive_contact_7', '=', false)
		),
		array(
			'id'	=> 'sstd_html_body',
			'type' 	=> 'editor',
			'title'	=> esc_html__('HTML Mail Body', 'cardealer-helper'),
			'subtitle' => esc_html__('Schedule Test Drive Form HTML Mail Body', 'cardealer-helper'),
			'desc'	   => sprintf(
								wp_kses(
									__('Use <a href="#" class="cd_dialog" data-id="std-1">this</a> variables to build or update mail body.
									<div id="std-1" class="variable-content" title="Schedule Test Drive"><p>%1$s</p></div>', 'cardealer-helper'),
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
									)
								),
								'From mail name :  #CD_FROM_NAME#<br>First Name : #CD_FIRST_NAME#<br>Last Name : #CD_LAST_NAME#<br>Email : #CD_EMAIL#<br>Mobile : #CD_MOBILE#<br>Address : #CD_ADDRESS#<br>State : #CD_STATE#<br>Zip : #CD_ZIP#<br>Preferred Contact : #CD_PREFERRED_CONTACT#<br>Test Drive?: #CD_TEST_DRIVE#<br>Date : #CD_DATE#<br>Time : #CD_TIME#<br>Product Detail : #PRODUCT_DETAIL#'
						),
			'args'   => array(
				'teeny'       	=> false,
				'wpautop'     	=> false,
				'quicktags'		=> 1,
				'textarea_rows' => 20
			),
			'required' => array('schedule_drive_contact_7', '=', false),
			'default' => sprintf( wp_kses(
								__('Hello #CD_FROM_NAME#,<br><br>One Inquiry received for Test Drive.<br>Following are the information of the Inquiry :<br><br>First Name : #CD_FIRST_NAME#<br>Last Name  : #CD_LAST_NAME#<br>Email      : #CD_EMAIL#<br>Mobile       : #CD_MOBILE#<br>Address    : #CD_ADDRESS#<br>State        : #CD_STATE#<br>Zip           : #CD_ZIP#<br>Preferred Contact : #CD_PREFERRED_CONTACT#<br>Test Drive?: #CD_TEST_DRIVE#<br>Date       : #CD_DATE#<br>Time       : #CD_TIME#<br>Product Detail : #PRODUCT_DETAIL#<br><br><br>Thanks and Regards,<br>Car Dealer Team', 'cardealer-helper'),
								array( 'br' => array(),'br' => array())
								) )
		),
		array(
			'id'         => 'std_txt_mail_to',
			'type'       => 'text',
			'title'      => esc_html__('Text Mail To', 'cardealer-helper'),
			'desc'       => esc_html__('You can add multiple email IDs separated by "," (comma).', 'cardealer-helper'),
			'default' => esc_html__('support@example.com','cardealer-helper'),
			'required' => array('schedule_drive_contact_7', '=', false)
		),
		array(
			'id'	=> 'std_txt_body',
			'type' 	=> 'textarea',
			'title'	=> esc_html__('Text Mail Body', 'cardealer-helper'),
			'subtitle' => esc_html__('Schedule Test Drive Form Text Mail Body', 'cardealer-helper'),
			'desc'	   => sprintf(
								wp_kses(
									__('Use <a href="#" class="cd_dialog" data-id="std-2">this</a> variables to build or update mail body.
									<div id="std-2" class="variable-content" title="Schedule Test Drive"><p>%1$s</p></div>', 'cardealer-helper'),
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
									)
								),
								'From mail name :  #CD_FROM_NAME#<br>First Name : #CD_FIRST_NAME#<br>Last Name : #CD_LAST_NAME#<br>Email : #CD_EMAIL#<br>Mobile : #CD_MOBILE#<br>Address : #CD_ADDRESS#<br>State : #CD_STATE#<br>Zip : #CD_ZIP#<br>Preferred Contact : #CD_PREFERRED_CONTACT#<br>Test Drive?: #CD_TEST_DRIVE#<br>Date : #CD_DATE#<br>Time : #CD_TIME#<br>Product Detail : #PRODUCT_DETAIL#'
						),
			'required' => array('schedule_drive_contact_7', '=', false),
			'default' => esc_html__('Hello #CD_FROM_NAME#,

One Inquiry received for Test Drive.
Following are the information of the Inquiry :

First Name : #CD_FIRST_NAME#
Last Name : #CD_LAST_NAME#
Email : #CD_EMAIL#
Mobile : #CD_MOBILE#
Address : #CD_ADDRESS#
State : #CD_STATE#
Zip : #CD_ZIP#
Preferred Contact : #CD_PREFERRED_CONTACT#
Test Drive?: #CD_TEST_DRIVE#
Date : #CD_DATE#
Time : #CD_TIME#

Product Detail :

#PRODUCT_DETAIL#


Thanks and Regards,
Car Dealer Team', 'cardealer-helper')
		),
		array(
			'id' 	=> 'schedule_drive_end',
			'type' 	=> 'section',
			'indent' =>	false
		),
		array(
			'id' 	=> 'make_offer_start',
			'type' 	=> 'section',
			'title' => 'Make an Offer',
			'indent' =>	true
		),
        array(
			'id' => 'make_offer_form_status',
			'type' => 'switch',
			'title' => esc_html__('Enable / Disable', 'cardealer-helper'),			
            'desc'      => esc_html__( 'Show/hide make an offer form for front end.', 'cardealer-helper'),
            'default'  => true
		),
		array(
			'id' => 'make_offer_contact_7',
			'type' => 'switch',
			'title' => esc_html__('Use Contact Form 7', 'cardealer-helper'),
			'on'	=> esc_html__('Yes', 'cardealer-helper'),
			'off'	=> esc_html__('No', 'cardealer-helper'),
			'default' => false,
            'required' => array('make_offer_form_status', '=', true)
		),
		array(
			'id' => 'make_offer_form',
			'type' => 'text',
			'title' => esc_html__('Contact Form 7 Shortcode', 'cardealer-helper'),
			'required' => array('make_offer_contact_7', '=', true)
		),
		array(
			'id'         => 'mao_from_name',
			'type'       => 'text',
			'title'      => esc_html__('From Mail Name', 'cardealer-helper'),
			'default' => esc_html__('Car Dealer','cardealer-helper'),
			'required' => array('make_offer_contact_7', '=', false)
		),
		array(
			'id'         => 'mao_mail_id_from',
			'type'       => 'text',
			'title'      => esc_html__('From Mail ID', 'cardealer-helper'),
			'default' => esc_html__('support@example.com','cardealer-helper'),
			'required' => array('make_offer_contact_7', '=', false)
		),
		array(
			'id'         => 'mao_subject',
			'type'       => 'text',
			'title'      => esc_html__('Mail Subject', 'cardealer-helper'),
			'default' => esc_html__('Car Dealer - Make an Offer Inquiry Received','cardealer-helper'),
			'required' => array('make_offer_contact_7', '=', false)
		),
		array(
			'id'         => 'mao_adf_mail_to',
			'type'       => 'text',
			'title'      => esc_html__('ADF Mail To', 'cardealer-helper'),
			'desc'       => esc_html__('You can add multiple email IDs separated by "," (comma).', 'cardealer-helper'),
			'default' => esc_html__('support@example.com','cardealer-helper'),
			'required' => array('make_offer_contact_7', '=', false)
		),
		array(
			'id'         => 'mao_html_mail_to',
			'type'       => 'text',
			'title'      => esc_html__('HTML Mail To', 'cardealer-helper'),
			'desc'       => esc_html__('You can add multiple email IDs separated by "," (comma).', 'cardealer-helper'),
			'default' => esc_html__('support@example.com','cardealer-helper'),
			'required' => array('make_offer_contact_7', '=', false)
		),
		array(
			'id'	=> 'mmao_html_body',
			'type' 	=> 'editor',
			'args'   => array(
				'teeny'       	=> false,
				'wpautop'     	=> false,
				'quicktags'		=> 1,
				'textarea_rows' => 20
			),
			'title'	=> esc_html__('HTML Mail Body', 'cardealer-helper'),
			'subtitle' => esc_html__('Make an Offer Form Mail Body', 'cardealer-helper'),
			'desc'	   => sprintf(
								wp_kses(
									__('Use <a href="#" class="cd_dialog" data-id="mno-1">this</a> variables to build or update mail body.
									<div id="mno-1" class="variable-content" title="Make an Offer"><p>%1$s</p></div>', 'cardealer-helper'),
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
									)
								),
								'From mail name :  #CD_FROM_NAME#<br>First Name : #CD_FIRST_NAME#<br>Last Name : #CD_LAST_NAME#<br>Email : #CD_EMAIL#<br>Home Phone : #CD_HOME_PHONE#<br>Comment : #CD_COMMENT#<br>Request Price: #CD_REQ_PRICE#<br>Product Detail :#PRODUCT_DETAIL#'
						),
			'required' => array('make_offer_contact_7', '=', false),
			'default' => sprintf( wp_kses(
								__('<p>Hello #CD_FROM_NAME#,</p><br /><br /><p>One Inquiry received for Make an Offer.</p><p><br /> Following are the information of the Inquiry :</p><p><br /><br /><strong>First Name :</strong> #CD_FIRST_NAME#<br /> <strong>Last Name :</strong> #CD_LAST_NAME#<br /> <strong>Email :</strong> #CD_EMAIL#<br/> <strong>Home Phone :</strong> #CD_HOME_PHONE#<br /> <strong>Comment :</strong> #CD_COMMENT#<br /> <strong>Request Price:</strong> #CD_REQ_PRICE#<br /><p><strong>product Detail :</strong></p><p>#PRODUCT_DETAIL#<br /><br /> Thanks and Regards,<br />Car Dealer Team</p>', 'cardealer-helper'),
								array( 'br' => array(),'br' => array())
								) )
		),
		array(
			'id'         => 'mao_text_mail_to',
			'type'       => 'text',
			'title'      => esc_html__('Text Mail To', 'cardealer-helper'),
			'desc'       => esc_html__('You can add multiple email IDs separated by "," (comma).', 'cardealer-helper'),
			'default' => esc_html__('support@example.com','cardealer-helper'),
			'required' => array('make_offer_contact_7', '=', false)
		),
		array(
			'id'	=> 'mmao_text_body',
			'type' 	=> 'textarea',
			'title'	=> esc_html__('Text Mail Body', 'cardealer-helper'),
			'subtitle' => esc_html__('Make an Offer Form Text Mail Body', 'cardealer-helper'),
			'desc'	   => sprintf(
								wp_kses(
									__('Use <a href="#" class="cd_dialog" data-id="mno-2">this</a> variables to build or update mail body.
									<div id="mno-2" class="variable-content" title="Make an Offer"><p>%1$s</p></div>', 'cardealer-helper'),
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
									)
								),
								'From mail name :  #CD_FROM_NAME#<br>First Name : #CD_FIRST_NAME#<br>Last Name : #CD_LAST_NAME#<br>Email : #CD_EMAIL#<br>Home Phone : #CD_HOME_PHONE#<br>Comment : #CD_COMMENT#<br>Request Price: #CD_REQ_PRICE#<br>Product Detail :#PRODUCT_DETAIL#'
						),
			'required' => array('make_offer_contact_7', '=', false),
			'default' => esc_html__('Hello #CD_FROM_NAME#,

One Inquiry received for Make an Offer.
Following are the information of the Inquiry :

First Name : #CD_FIRST_NAME#
Last Name : #CD_LAST_NAME#
Email : #CD_EMAIL#
Home Phone : #CD_HOME_PHONE#
comment : #CD_COMMENT#
Request Price : #CD_REQ_PRICE#

Product Detail :

#PRODUCT_DETAIL#

Thanks and Regards,
Car Dealer Team', 'cardealer-helper')
		),
		array(
			'id' 	=> 'make_offer_end',
			'type' 	=> 'section',
			'indent' =>	false
		),
		array(
			'id' 	=> 'email_friend_start',
			'type' 	=> 'section',
			'title' => 'Email To Friend',
			'indent' =>	true
		),
        array(
			'id' => 'email_friend_form_status',
			'type' => 'switch',
			'title' => esc_html__('Enable / Disable', 'cardealer-helper'),			
            'desc'      => esc_html__( 'Show/hide email to friend form for front end.', 'cardealer-helper'),
            'default'  => true,
		),
		array(
			'id' => 'email_friend_contact_7',
			'type' => 'switch',
			'title' => esc_html__('Use Contact Form 7', 'cardealer-helper'),
			'on'	=> esc_html__('Yes', 'cardealer-helper'),
			'off'	=> esc_html__('No', 'cardealer-helper'),
			'default' => false,
            'required' => array('email_friend_form_status', '=', true)
		),
		array(
			'id' => 'email_friend_form',
			'type' => 'text',
			'title' => esc_html__('Contact Form 7 Shortcode', 'cardealer-helper'),
			'required' => array('email_friend_contact_7', '=', true)
		),
		array(
			'id'         => 'email_friend_from_name',
			'type'       => 'text',
			'title'      => esc_html__('From Mail Name', 'cardealer-helper'),
			'default' => esc_html__('Car Dealer','cardealer-helper'),
			'required' => array('email_friend_contact_7', '=', false)
		),
		array(
			'id'         => 'email_friend_subject',
			'type'       => 'text',
			'title'      => esc_html__('Mail Subject', 'cardealer-helper'),
			'default' => esc_html__('Car Dealer - Mail to Friend','cardealer-helper'),
			'required' => array('email_friend_contact_7', '=', false)
		),
		array(
			'id' 	=> 'email_friend_end',
			'type' 	=> 'section',
			'indent' =>	false
		),
		array(
			'id' 	=> 'financial_form_start',
			'type' 	=> 'section',
			'title' => 'Financial Form',
			'indent' =>	true
		),
        array(
			'id' => 'financial_form_status',
			'type' => 'switch',
			'title' => esc_html__('Enable / Disable', 'cardealer-helper'),			
            'desc'      => esc_html__( 'Show/hide financial form for front end.', 'cardealer-helper'),
            'default'  => true
		),
		array(
			'id' => 'financial_form_contact_7',
			'type' => 'switch',
			'title' => esc_html__('Use Contact Form 7', 'cardealer-helper'),
			'on'	=> esc_html__('Yes', 'cardealer-helper'),
			'off'	=> esc_html__('No', 'cardealer-helper'),
			'default' => false,
            'required' => array('financial_form_status', '=', true)
		),
		array(
			'id' => 'financial_form',
			'type' => 'text',
			'title' => esc_html__('Contact Form 7 Shortcode', 'cardealer-helper'),
			'required' => array('financial_form_contact_7', '=', true)
		),
		array(
			'id'         => 'financial_form_from_name',
			'type'       => 'text',
			'title'      => esc_html__('From Mail Name', 'cardealer-helper'),
			'default' => esc_html__('Car Dealer','cardealer-helper'),
			'required' => array('financial_form_contact_7', '=', false)
		),
		array(
			'id'         => 'financial_form_mail_id_from',
			'type'       => 'text',
			'title'      => esc_html__('From Mail ID', 'cardealer-helper'),
			'default' => esc_html__('support@example.com','cardealer-helper'),
			'required' => array('financial_form_contact_7', '=', false)
		),
		array(
			'id'        => 'financial_form_subject',
			'type'      => 'text',
			'title'     => esc_html__('Mail Subject', 'cardealer-helper'),
			'default' 	=> esc_html__('Car Dealer - Financial inquiry','cardealer-helper'),
			'required' => array('financial_form_contact_7', '=', false)
		),
		array(
			'id'        => 'financial_form_adf_mail_to',
			'type'      => 'text',
			'title'     => esc_html__('ADF Mail To', 'cardealer-helper'),
			'desc'       => esc_html__('You can add multiple email IDs separated by "," (comma).', 'cardealer-helper'),
			'default' 	=> esc_html__('support@example.com','cardealer-helper'),
			'required' => array('financial_form_contact_7', '=', false)
		),
		array(
			'id'        => 'financial_form_html_mail_to',
			'type'      => 'text',
			'title'     => esc_html__('HTML Mail To', 'cardealer-helper'),
			'desc'       => esc_html__('You can add multiple email IDs separated by "," (comma).', 'cardealer-helper'),
			'default' 	=> esc_html__('support@example.com','cardealer-helper'),
			'required' => array('financial_form_contact_7', '=', false)
		),
		array(
			'id'	=> 'financial_form_html_body',
			'type' 	=> 'editor',
			'title'	=> esc_html__('Mail Body', 'cardealer-helper'),
			'subtitle' => esc_html__('Financial Form Mail Body', 'cardealer-helper'),
			'desc'	   => sprintf(
								wp_kses(
									__('Use <a href="#" class="cd_dialog" data-id="financial-1">this</a> variables to build or update mail body.
									<div id="financial-1" class="variable-content" title="Financial Form"><p>%1$s</p></div>', 'cardealer-helper'),
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
									)
								),
								'From mail name : #CD_FROM_NAME#<br>First Name : #CD_FIRST_NAME#<br>Middle Initial : #CD_MIDDLE_INIT#<br>Last Name : #CD_LAST_NAME#<br>Street Address : #CD_STREET_ADD#<br>City : #CD_CITY#<br>State : #CD_STATE#<br>Zip : #CD_ZIP#<br>Preferred Email Address : #CD_PREF_EMAIL_ADD#<br>Daytime Phone Number : #CD_DAYTIME_PHONE_NO#<br>Mobile Phone Number : #CD_MOBILE_PHONE_NO#<br>Date of Birth : #CD_DATE_OF_BIRTH#<br>Living Arrangements : #CD_LIVING_ARRANG#<br>Social Security Number (SSN) : #CD_SSN#<br>Employer Name : #CD_EMPLOYER_NAME#<br>Monthly Rent/Mortgage Payment : #CD_MONTHLY_RENT#<br>Employer Phone : #CD_EMPLOYER_PHONE#<br>Job Title : #CD_JOB_TITLE#<br>Length of Time at Current Address : #CD_LEN_OF_TIME_AT_CUR_ADD#<br>Length of Employment : #CD_LENGTH_OF_EMP#<br>Annual Income : #CD_ANNUAL_INCOME#<br>Other Income Amount(Monthly) : #CD_OTHER_INC_AMT_MONTHLY#<br>Other Income Source : #CD_OTHER_INCOME_SOURCE#<br>Additional Information : #CD_ADD_INFO#<br><br><br>Joint Application:<br><br>First Name :#CD_JOINT_FIRST_NAME#<br>Middle Initial :#CD_JOINT_MIDDLE_INIT#<br>Last Name :#CD_JOINT_LAST_NAME#<br>Relationship To Applicant :#CD_JOINT_REL_TO_APPLICANT#<br>Street-Address :#CD_JOINT_STREET_ADD#<br>City :#CD_JOINT_CITY#<br>State :#CD_JOINT_STATE#<br>Zip :#CD_JOINT_ZIP#<br>Preferred Email Address :#CD_JOINT_PREFERRED_EMAIL_ADD#<br>Daytime Phone Number : #CD_JOINT_DAYTIME_PHONE_NO#<br>Mobile Phone Number : #CD_JOINT_MOBILE_PHONE_NO#<br>Date of Birth : #CD_JOINT_DATE_OF_BIRTH#<br>Social Security Number (SSN) : #CD_JOINT_SSN#<br>Employer Name : #CD_JOINT_EMP_NAME#<br>Employer Phone No :#CD_JOINT_EMP_PHONE#<br>Job Title :#CD_JOINT_JOB_TITLE#<br>Length of Employment : #CD_JOINT_LENGTH_OF_EMP#<br>Length of Time at Current Address : #CD_JOINT_LENGTH_OF_TIME#<br>Annual Income : #CD_JOINT_ANNUAL_INCOME#<br>Living Arrangment :#CD_JOINT_LIVING_ARRANG#<br>Monthly Rent/Mortgage Payment : #CD_JOINT_MONTHLY_RENT#<br>Other Information Amount Monthly :#CD_JOINT_OTHER_INC_AMT_MONTHLY#<br>Other Income Source :#CD_JOINT_OTHER_INC_SOURCE#<br>Additional Information :#CD_JOINT_ADD_INFO#<br>Car Detail: #PRODUCT_DETAIL#'
						),
			'args'   => array(
				'teeny'       	=> false,
				'wpautop'     	=> false,
				'quicktags'		=> 1,
				'textarea_rows' => 20
			),
			'required' => array('financial_form_contact_7', '=', false),
			'default' => sprintf( wp_kses(
								__('<p>Hello #CD_FROM_NAME#,<br /><br />One Financial Inquiry received.<br /><br />Following are the information of the Inquiry :</p>
<p>First Name : #CD_FIRST_NAME#<br />Middle Initial : #CD_MIDDLE_INIT#<br />Last Name : #CD_LAST_NAME#<br />Street Address : #CD_STREET_ADD#<br />City : #CD_CITY#<br />State : #CD_STATE#<br />Zip : #CD_ZIP#<br />Preferred Email Address : #CD_PREF_EMAIL_ADD#<br />Daytime Phone Number : #CD_DAYTIME_PHONE_NO#<br />Mobile Phone Number : #CD_MOBILE_PHONE_NO#<br />Date of Birth : #CD_DATE_OF_BIRTH#<br />Living Arrangements : #CD_LIVING_ARRANG#<br />Social Security Number (SSN) : #CD_SSN#<br />Employer Name : #CD_EMPLOYER_NAME#<br />Monthly Rent/Mortgage Payment : #CD_MONTHLY_RENT#<br />Employer Phone : #CD_EMPLOYER_PHONE#<br />Job Title : #CD_JOB_TITLE#<br />Length of Time at Current Address :  #CD_LEN_OF_TIME_AT_CUR_ADD#<br />Length of Employment : #CD_LENGTH_OF_EMP#<br />Annual Income : #CD_ANNUAL_INCOME#<br />Other Income Amount(Monthly) :  #CD_OTHER_INC_AMT_MONTHLY#<br />Other Income Source : #CD_OTHER_INCOME_SOURCE#<br />Additional Information : #CD_ADD_INFO#</p>
<p><br />Joint Application :</p>
<p>First Name :#CD_JOINT_FIRST_NAME#<br />Middle Initial :#CD_JOINT_MIDDLE_INIT#<br />Last Name :#CD_JOINT_LAST_NAME#<br />Relationship To Applicant :#CD_JOINT_REL_TO_APPLICANT#<br />Street-Address :#CD_JOINT_STREET_ADD#<br />City :#CD_JOINT_CITY#<br />State :#CD_JOINT_STATE#<br />Zip :#CD_JOINT_ZIP#<br />Preferred Email Address :#CD_JOINT_PREFERRED_EMAIL_ADD#<br />Daytime Phone Number : #CD_JOINT_DAYTIME_PHONE_NO#<br />Mobile Phone Number : #CD_JOINT_MOBILE_PHONE_NO#<br />Date of Birth : #CD_JOINT_DATE_OF_BIRTH#<br />Social Security Number (SSN) : #CD_JOINT_SSN#<br />Employer Name : #CD_JOINT_EMP_NAME#<br />Employer Phone No :#CD_JOINT_EMP_PHONE#<br />Job Title :#CD_JOINT_JOB_TITLE#<br />Length of Employment : #CD_JOINT_LENGTH_OF_EMP#<br />Length of Time at Current Address :  #CD_JOINT_LENGTH_OF_TIME#<br />Annual Income : #CD_JOINT_ANNUAL_INCOME#<br />Living Arrangement :#CD_JOINT_LIVING_ARRANG#<br />Monthly Rent/Mortgage Payment : #CD_JOINT_MONTHLY_RENT#<br />Other Information Amount Monthly :#CD_JOINT_OTHER_INC_AMT_MONTHLY#<br />Other Income Source :#CD_JOINT_OTHER_INC_SOURCE#<br />Additional Information :#CD_JOINT_ADD_INFO#</p>
<p>Car Detail:<br />#PRODUCT_DETAIL#</p>
<p>&nbsp;</p>
<p>Thanks and Regards,<br />Car Dealer Team</p>', 'cardealer-helper'),
								array( 'p' => array(),'br' => array())
								) )
		),
		array(
			'id'        => 'financial_form_text_mail_to',
			'type'      => 'text',
			'title'     => esc_html__('Text Mail To', 'cardealer-helper'),
			'desc'       => esc_html__('You can add multiple email IDs separated by "," (comma).', 'cardealer-helper'),
			'default' 	=> esc_html__('support@example.com','cardealer-helper'),
			'required' => array('financial_form_contact_7', '=', false)
		),
		array(
			'id'	=> 'financial_form_text_body',
			'type' 	=> 'textarea',
			'title'	=> esc_html__('Text Mail Body', 'cardealer-helper'),
			'subtitle' => esc_html__('Financial Form Mail Body', 'cardealer-helper'),
			'desc'	   => sprintf(
								wp_kses(
									__('Use <a href="#" class="cd_dialog" data-id="financial-2">this</a> variables to build or update mail body.
									<div id="financial-2" class="variable-content" title="Financial Form"><p>%1$s</p></div>', 'cardealer-helper'),
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
									)
								),
								'From mail name : #CD_FROM_NAME#<br>First Name : #CD_FIRST_NAME#<br>Middle Initial : #CD_MIDDLE_INIT#<br>Last Name : #CD_LAST_NAME#<br>Street Address : #CD_STREET_ADD#<br>City : #CD_CITY#<br>State : #CD_STATE#<br>Zip : #CD_ZIP#<br>Preferred Email Address : #CD_PREF_EMAIL_ADD#<br>Daytime Phone Number : #CD_DAYTIME_PHONE_NO#<br>Mobile Phone Number : #CD_MOBILE_PHONE_NO#<br>Date of Birth : #CD_DATE_OF_BIRTH#<br>Living Arrangements : #CD_LIVING_ARRANG#<br>Social Security Number (SSN) : #CD_SSN#<br>Employer Name : #CD_EMPLOYER_NAME#<br>Monthly Rent/Mortgage Payment : #CD_MONTHLY_RENT#<br>Employer Phone : #CD_EMPLOYER_PHONE#<br>Job Title : #CD_JOB_TITLE#<br>Length of Time at Current Address : #CD_LEN_OF_TIME_AT_CUR_ADD#<br>Length of Employment : #CD_LENGTH_OF_EMP#<br>Annual Income : #CD_ANNUAL_INCOME#<br>Other Income Amount(Monthly) : #CD_OTHER_INC_AMT_MONTHLY#<br>Other Income Source : #CD_OTHER_INCOME_SOURCE#<br>Additional Information : #CD_ADD_INFO#<br><br><br>Joint Application:<br><br>First Name :#CD_JOINT_FIRST_NAME#<br>Middle Initial :#CD_JOINT_MIDDLE_INIT#<br>Last Name :#CD_JOINT_LAST_NAME#<br>Relationship To Applicant :#CD_JOINT_REL_TO_APPLICANT#<br>Street-Address :#CD_JOINT_STREET_ADD#<br>City :#CD_JOINT_CITY#<br>State :#CD_JOINT_STATE#<br>Zip :#CD_JOINT_ZIP#<br>Preferred Email Address :#CD_JOINT_PREFERRED_EMAIL_ADD#<br>Daytime Phone Number : #CD_JOINT_DAYTIME_PHONE_NO#<br>Mobile Phone Number : #CD_JOINT_MOBILE_PHONE_NO#<br>Date of Birth : #CD_JOINT_DATE_OF_BIRTH#<br>Social Security Number (SSN) : #CD_JOINT_SSN#<br>Employer Name : #CD_JOINT_EMP_NAME#<br>Employer Phone No :#CD_JOINT_EMP_PHONE#<br>Job Title :#CD_JOINT_JOB_TITLE#<br>Length of Employment : #CD_JOINT_LENGTH_OF_EMP#<br>Length of Time at Current Address : #CD_JOINT_LENGTH_OF_TIME#<br>Annual Income : #CD_JOINT_ANNUAL_INCOME#<br>Living Arrangment :#CD_JOINT_LIVING_ARRANG#<br>Monthly Rent/Mortgage Payment : #CD_JOINT_MONTHLY_RENT#<br>Other Information Amount Monthly :#CD_JOINT_OTHER_INC_AMT_MONTHLY#<br>Other Income Source :#CD_JOINT_OTHER_INC_SOURCE#<br>Additional Information :#CD_JOINT_ADD_INFO#<br>Car Detail: #PRODUCT_DETAIL#'
						),
			'args'   => array(
				'teeny'       	=> false,
				'wpautop'     	=> false,
				'quicktags'		=> 1,
				'textarea_rows' => 20
			),
			'required' => array('financial_form_contact_7', '=', false),
			'default' => sprintf( wp_kses(
								__('Hello #CD_FROM_NAME#,

One Financial Inquiry received.

Following are the information of the Inquiry :

First Name : #CD_FIRST_NAME#
Middle Initial : #CD_MIDDLE_INIT#
Last Name : #CD_LAST_NAME#
Street Address : #CD_STREET_ADD#
City : #CD_CITY#
State : #CD_STATE#
Zip : #CD_ZIP#
Preferred Email Address : #CD_PREF_EMAIL_ADD#
Daytime Phone Number : #CD_DAYTIME_PHONE_NO#
Mobile Phone Number : #CD_MOBILE_PHONE_NO#
Date of Birth : #CD_DATE_OF_BIRTH#
Living Arrangements : #CD_LIVING_ARRANG#
Social Security Number (SSN) : #CD_SSN#
Employer Name : #CD_EMPLOYER_NAME#
Monthly Rent/Mortgage Payment : #CD_MONTHLY_RENT#
Employer Phone : #CD_EMPLOYER_PHONE#
Job Title : #CD_JOB_TITLE#
Length of Time at Current Address : #CD_LEN_OF_TIME_AT_CUR_ADD#
Length of Employment : #CD_LENGTH_OF_EMP#
Annual Income : #CD_ANNUAL_INCOME#
Other Income Amount(Monthly) : #CD_OTHER_INC_AMT_MONTHLY#
Other Income Source : #CD_OTHER_INCOME_SOURCE#
Additional Information : #CD_ADD_INFO#

Joint Application :

First Name :#CD_JOINT_FIRST_NAME#
Middle Initial :#CD_JOINT_MIDDLE_INIT#
Last Name :#CD_JOINT_LAST_NAME#
Relationship To Applicant :#CD_JOINT_REL_TO_APPLICANT#
Street-Address :#CD_JOINT_STREET_ADD#
City :#CD_JOINT_CITY#
State :#CD_JOINT_STATE#
Zip :#CD_JOINT_ZIP#
Preferred Email Address :#CD_JOINT_PREFERRED_EMAIL_ADD#
Daytime Phone Number : #CD_JOINT_DAYTIME_PHONE_NO#
Mobile Phone Number : #CD_JOINT_MOBILE_PHONE_NO#
Date of Birth : #CD_JOINT_DATE_OF_BIRTH#
Social Security Number (SSN) : #CD_JOINT_SSN#
Employer Name : #CD_JOINT_EMP_NAME#
Employer Phone No :#CD_JOINT_EMP_PHONE#
Job Title :#CD_JOINT_JOB_TITLE#
Length of Employment : #CD_JOINT_LENGTH_OF_EMP#
Length of Time at Current Address : #CD_JOINT_LENGTH_OF_TIME#
Annual Income : #CD_JOINT_ANNUAL_INCOME#
Living Arrangement :#CD_JOINT_LIVING_ARRANG#
Monthly Rent/Mortgage Payment : #CD_JOINT_MONTHLY_RENT#
Other Information Amount Monthly :#CD_JOINT_OTHER_INC_AMT_MONTHLY#
Other Income Source :#CD_JOINT_OTHER_INC_SOURCE#
Additional Information :#CD_JOINT_ADD_INFO#

Product Detail :
#PRODUCT_DETAIL#


Thanks and Regards,
Car Dealer Team', 'cardealer-helper'),
								array( 'p' => array())
								) )
		),
		array(
			'id' 	=> 'schedule_drive_end',
			'type' 	=> 'section',
			'indent' =>	false
		),
	)
));


// Car Form Settings Options
Redux::setSection($opt_name, array(
	'id'              => 'print_pdf_settings',
	'subsection'      => true,
	'title'           => esc_html__('Print and PDF Brochure', 'cardealer-helper'),
	'desc' 			  => esc_html__('Print and PDF Brochure on cars details page settings', 'cardealer-helper'),
	'customizer_width'=> '450px',
	'icon'            => 'fa fa-print',
	'fields'          => array(		
        array(
			'id'       => 'print_start',
			'type'     => 'section',
			'title'    => esc_html__( 'Print settings', 'cardealer-helper' ),
			'indent'   => true
		),
        array(
			'id' => 'print_status',
			'type' => 'switch',
			'title' => esc_html__('Enable / Disable', 'cardealer-helper'),			
            'desc'      => esc_html__( 'We can enable/disable print button for front end car detail page.', 'cardealer-helper'),
            'default'  => true
		),
        array(
			'id'       => 'pdf_brochure_start',
			'type'     => 'section',
			'title'    => esc_html__( 'PDF Brochure settings', 'cardealer-helper' ),
			'indent'   => true
		),
        array(
			'id' => 'pdf_brochure_status',
			'type' => 'switch',
			'title' => esc_html__('Enable / Disable', 'cardealer-helper'),			
            'desc'      => esc_html__( 'We can enable/disable PDF brochure button for front end car detail page.', 'cardealer-helper'),
            'default'  => true
		)
    )
));


// API Keys Settings
Redux::setSection($opt_name, array(
	'title'           => esc_html__('API Keys', 'cardealer-helper'),
	'id'              => 'api_keys',
	'subsection'      => true,
	'customizer_width'=> '450px',
	'icon'            => 'fa fa-key',
	'fields'          => array(
		array(
			'id'       => 'edmunds-section-start',
			'type'     => 'section',
			'title'    => esc_html__( 'Edmunds VIN Import', 'cardealer-helper' ),
			'indent'   => true
		),
		array(
			'id' => 'edmunds_api_key',
			'type' => 'text',
			'title' => esc_html__('API Key', 'cardealer-helper'),
			'desc' => '',
			'default' => ''
		),
		array(
			'id' => 'edmunds_api_secret',
			'type' => 'text',
			'title' => esc_html__('API Secret', 'cardealer-helper'),
			'desc' => sprintf(
						wp_kses( __( 'You can get Edmunds API details from <a href="%1$s" target="_blank">here</a>', 'cardealer-helper'),
							array(
								'a' => array(
									'href' => array(),
									'target' => array()
								)
							)
						), esc_url('http://developer.edmunds.com/')
					),
			'default' => ''
		),
		array(
			'id'     => 'edmunds-section-end',
			'type'   => 'section',
			'indent' => false,
		),
		array(
			'id'     => 'google-section-start',
			'type'   => 'section',
			'title'  => __( 'Google Maps API', 'cardealer-helper' ),
			'indent' => true
		),
		array(
			'id'    => 'google_maps_api',
			'type'  => 'text',
			'title' => __( 'Google Maps API Key', 'cardealer-helper' ),
			'desc'  => sprintf(
						wp_kses( __( 'You can get a Google Maps API from <a href="%1$s" target="_blank">here</a>', 'cardealer-helper'),
							array(
								'a' => array(
									'href' => array(),
									'target' => array()
								)
							)
						), esc_url('https://developers.google.com/maps/documentation/javascript/')
					)
		),
		array(
			'id'     => 'google-section-end',
			'type'   => 'section',
			'indent' => false,
		),
		array(
			'id'     => 'google-captcha-section-start',
			'type'   => 'section',
			'title'  => esc_html__( 'Google Captcha Settings', 'cardealer-helper' ),
			'indent' => true
		),
		array(
			'id'    => 'google_captcha_site_key',
			'type'  => 'text',
			'title' => esc_html__( 'Site Key', 'cardealer-helper' ),
			'subtitle'=> sprintf(
						wp_kses( __( 'You can get more information on Google Captcha at <a href="%1$s" target="_blank">here</a>', 'cardealer-helper'),
							array(
								'a' => array(
									'href' => array(),
									'target' => array()
								)
							)
						), esc_url('https://developers.google.com/recaptcha/')
					),
		),
		array(
			'id'    => 'google_captcha_secret_key',
			'type'  => 'text',
			'title' => __( 'Secret Key', 'cardealer-helper' ),
			'subtitle'  => esc_html( 'Google Captcha Secret Key', 'cardealer-helper' )
		),
		array(
			'id'     => 'google-captcha-section-end',
			'type'   => 'section',
			'indent' => false,
		),
        array(
			'id'       => 'section-start',
			'type'     => 'section',
			'title'    => esc_html__( 'MailChimp', 'cardealer-helper' ),
			'indent'   => true
		),
        array(
			'id' => 'mailchimp_list_id',
			'type' => 'text',
			'title' => esc_html__('MailChimp List Id', 'cardealer-helper'),			
		),
		array(
			'id' => 'mailchimp_api_key',
			'type' => 'text',
			'title' => esc_html__('MailChimp API Key', 'cardealer-helper'),
			'subtitle' => sprintf(
				wp_kses( __( 'The API key for connecting with your MailChimp account. <a href="%1$s" target="_blank">Get your API key here</a>', 'cardealer-helper'),
					array(
						'a' => array(
							'href' => array(),
							'target' => array()
						)
					)
				), esc_url('https://admin.mailchimp.com/account/api')
			),            			
		),
        array(
			'id'      => 'mailchimp_info',
			'type'    => 'info',
			'style'   => 'info',
			'title'   => esc_html__('Note', 'cardealer-helper'),
			'desc'    =>wp_kses( __( 'MailChimp credential will be used for newsletter shortcode and widget<br>', 'cardealer-helper'),
							array(
								'br' => array()
						)
					),
		),
	)
));

/* Import cars detail option */
Redux::setSection($opt_name, array(
    'title' => __('Import Cars Settings', 'cardealer-helper'),
    'id' => 'import-cars-opt',
	'subsection'      => true,
    'customizer_width' => '400px',
    'icon' => 'fa fa-file-excel-o',
    'fields' => array(
        array(
			'title'   => __( 'CSV Delimiter', 'cardealer-helper' ),
			'desc'    => __( 'The delimiter used for the entire file.', 'cardealer-helper' ),
			'id'      => 'csv_delimiter',
			'type'    => 'text',
			'default' => ','
		),
        array(
			'title'   => __( 'Post Status', 'cardealer-helper' ),
			'desc'    => __( 'Choose what the status will be set to for imported posts.', 'cardealer-helper' ),
			'type'    => 'select',
			'id'      => 'import_post_status',
			'options' => array(
				'publish' => __( 'Published', 'cardealer-helper' ),
				'draft'   => __( 'Draft', 'cardealer-helper' ),
				'pending' => __( 'Pending', 'cardealer-helper' ),
				'private' => __( 'Private', 'cardealer-helper' ),
			),
			'default' => 'publish'
		),
    ),
));
/* Export car detail option */
Redux::setSection($opt_name, array(
    'title' => __('Export Car Detail', 'cardealer-helper'),
    'id' => 'export-cars',
	'subsection'      => true,
    'customizer_width' => '400px',
    'icon' => 'fa fa-file-excel-o',
    'fields' => array(
        array(
			'id'      => 'export_cars',
			'type'    => 'sorter',
			'title'   => 'Export Cars',
			'subtitle'   => '(Export to CSV)',
			'desc'    => 'Select attributes to Export. This will be used for CSV export.',
			'options' => array(
				'Available attributes' => array(
					'car_images'	 			=> 'Car Images',
					'regular_price'  			=> 'Regular Price',
					'sale_price'  				=> 'Sale Price',
					'city_mpg'  				=> 'City Mpg',
					'tax_label'		 			=> 'Tax Label',
					'highway_mpg'    			=> 'Highway Mpg',
					'pdf_file'   	 			=> 'Pdf File',
					'video_link'   	 			=> 'Video Link',
					'car_year'   	 			=> 'Car Year',
					'car_make'   	 			=> 'Car Make',
					'car_model'  	 			=> 'Car Model',
					'car_body_style' 			=> 'Car Body Style',
					'car_mileage'  	 			=> 'Car Mileage',
					'car_fuel_economy' 			=> 'Car Fuel Economy',
					'car_transmission' 			=> 'Car Transmission',
					'car_condition'	 			=> 'Car Condition',
					'car_drivetrain' 			=> 'Car Drivetrain',
					'car_engine'	 			=> 'Car Engine',
					'car_exterior_color'		=> 'Car Exterior Color',
					'car_interior_color'	 	=> 'Car Interior Color',
					'car_stock_number'	 		=> 'Car Stock Number',
					'car_vin_number'	 		=> 'Car Vin Number',
					'vehicle_overview'	 		=> 'Vehicle Overview',
					'car_features_options'		=> 'Car Features And Options',
					'technical_specifications'	=> 'Technical Specifications',
					'car_trim'	 				=> 'Car Trim',
					'general_information'	 	=> 'General Information',
					'vehicle_location'	 		=> 'Vehicle Location',
					'car_fuel_type'	 		    => 'Fuel Type',
					'car_status'	 		    => 'Car Status'
				),
				'Attributes to export'  => array()				
			)
		),
		array(
			'id'=> 'auto_trader_starts',
			'type' => 'section',
			'title' => esc_html('AutoTrader.com Export Settings', 'cardealer-helper'),
			'subtitle' => esc_html('AutoTrader.com details to export car data.', 'cardealer-helper'),
			'indent' => true,
		),
		array(
			'id' => 'dealer_id',
			'type' => 'text',
			'title' => esc_html('Dealer Id', 'cardealer-helper'),
			'subtitle' => esc_html('Enter Dealer Id.', 'cardealer-helper'),
		),
		array(
			'id' => 'ftp_host',
			'type' => 'text',
			'title' => esc_html('FTP Host', 'cardealer-helper'),
			'subtitle' => esc_html('Enter FTP HostName.', 'cardealer-helper'),
		),
		array(
			'id' => 'username',
			'type' => 'text',
			'title' => esc_html('FTP Username', 'cardealer-helper'),
			'subtitle' => esc_html('Enter FTP Username.', 'cardealer-helper'),
		),
		array(
			'id' => 'password',
			'type' => 'text',
			'title' => esc_html('FTP Password', 'cardealer-helper'),
			'subtitle' => esc_html('Enter FTP Password.', 'cardealer-helper'),
		),
		array(
			'id' => 'file_location',
			'type' => 'text',
			'title' => esc_html('Location', 'cardealer-helper'),
			'subtitle' => esc_html('Enter Location for exported file to store on server.', 'cardealer-helper'),
		),
		array(
			'id'=> 'auto_trader_end',
			'type' => 'section',
			'indent' => false,
		),
		array(
			'id'=> 'car_starts',
			'type' => 'section',
			'title' => esc_html('Car.com Export Settings', 'cardealer-helper'),
			'subtitle' => esc_html('Car.com details to export car data.', 'cardealer-helper'),
			'indent' => true,
		),
		array(
			'id' => 'car_dealer_id',
			'type' => 'text',
			'title' => esc_html('Dealer Id', 'cardealer-helper'),
			'subtitle' => esc_html('Enter Dealer Id.', 'cardealer-helper'),
		),
		array(
			'id' => 'car_ftp_host',
			'type' => 'text',
			'title' => esc_html('FTP Host', 'cardealer-helper'),
			'subtitle' => esc_html('Enter FTP HostName.', 'cardealer-helper'),
		),
		array(
			'id' => 'car_username',
			'type' => 'text',
			'title' => esc_html('FTP Username', 'cardealer-helper'),
			'subtitle' => esc_html('Enter FTP Username.', 'cardealer-helper'),
		),
		array(
			'id' => 'car_password',
			'type' => 'text',
			'title' => esc_html('FTP Password', 'cardealer-helper'),
			'subtitle' => esc_html('Enter FTP Password.', 'cardealer-helper'),
		),
		array(
			'id' => 'car_file_location',
			'type' => 'text',
			'title' => esc_html('Location', 'cardealer-helper'),
			'subtitle' => esc_html('Enter Location for exported file to store on server.', 'cardealer-helper'),
		),
		array(
			'id'=> 'car_end',
			'type' => 'section',
			'indent' => false,
		)
    )
));

// Default Values Options
Redux::setSection($opt_name, array(
	'title'           => esc_html__('Default Values', 'cardealer-helper'),
	'id'              => 'default_values',
	'subsection'      => true,
	'customizer_width'=> '450px',
	'icon'            => 'fa fa-pencil-square-o',
	'fields'          => array(
		array(
			'id'       => 'location-section-start',
			'type'     => 'section',
			'title'    => esc_html__( 'Default location', 'cardealer-helper' ),
			'subtitle' => esc_html__( 'This location will be the default used while adding new car', 'cardealer-helper' ),
			'indent'   => true
		),
		array(
			'title'   => esc_html__( 'Latitude', 'cardealer-helper' ),
			'desc'    => sprintf(wp_kses(__( 'The default latitude. <a href="%1$s"  target="_blank">Click here</a> to get the latitude', 'cardealer-helper' ),
					array(
							'a' => array(
								'href' => array(),
								'target' => array()
							),					   
						)
					),
				'https://www.latlong.net/'
				),
			'id'      => 'default_value_lat',
			'type'    => 'text',
			'default' => '43.653226'
		),
		array(
			'title'   => esc_html__( 'Longitude', 'cardealer-helper' ),			
			'desc'    => sprintf(wp_kses(__( 'The default longitude. <a href="%1$s"  target="_blank">Click here</a> to get the latitude', 'cardealer-helper' ),
					array(
							'a' => array(
								'href' => array(),
								'target' => array()
							),					   
						)
					),
				'https://www.latlong.net/'
				),
			'id'      => 'default_value_long',
			'type'    => 'text',
			'default' => '-79.3831843'
		),
		array(
			'title'         => esc_html__( 'Zoom', 'cardealer-helper' ),
			'desc'          => esc_html__( 'The default zoom level.', 'cardealer-helper' ),
			'id'            => 'default_value_zoom',
			'type'          => 'slider',
			'default'       => '10',
			'min'           => 0,
			'max'           => 19,
			'step'          => 1,
			'display_value' => 'text'
		),
		array(
			'id'     => 'location-section-end',
			'type'   => 'section',
			'indent' => false,
		)		
	)
));

/*Dealer Cars Geo Fencing Settings options */
Redux::setSection($opt_name, array(
    'title' => __('Geo Fencing Settings', 'cardealer-helper'),
    'id' => 'geo-fencing-settings',
	'subsection'      => true,
    'customizer_width' => '400px',
    'icon' => 'fa fa-map-marker',
    'fields' => array(
        array(
			'id'     => 'cars-geo-fencing',
			'type'   => 'button_set',
			'title'  => esc_html__('Enable Geo Fencing', 'cardealer-helper'),
			'desc'   => esc_html__('Enable / Disable Geo Fencing on front side.', 'cardealer-helper'),
            'options'=> array(
				'1' => esc_html__('Yes', 'cardealer-helper'),
				'0' => esc_html__('No', 'cardealer-helper'),
			),
			'default' => '1'            		
		),
		array(
			'id' => 'geo_fencing_background_color',
			'type' => 'color',
			'title' => esc_html__('Geo Fencing Background Color', 'cardealer-helper'),
			'desc' => esc_html__('Geo Fencing Section Background Color in Top.', 'cardealer-helper'),
			'transparent' => false,
			'default' => '#db2d2e',
			'validate' => 'color',
			'mode' => 'background',
			'required'	=> array('cars-geo-fencing', '=', '1')
		)
    )
));

/* ------------------------------------------------------------------------
 *
 * Blog/Post Settings
 *
 * ------------------------------------------------------------------------ */
Redux::setSection($opt_name, array(
	'title'           => esc_html__('Blog/Post Settings', 'cardealer-helper'),
	'id'              => 'blogpost_settings',
	'desc'            => esc_html__('You can set blog layout, style and other blog related settings.', 'cardealer-helper'),
	'customizer_width'=> '400px',
	'icon'            => 'el el-quotes'
));
Redux::setSection($opt_name, array(
	'title'           => esc_html__('Blog Settings', 'cardealer-helper'),
	'id'              => 'blog_settings',
	'icon'            => 'el el-quote-alt',
	'subsection'      => true,
	'customizer_width'=> '450px',
	'fields'          => array(
		array(
			'id' => 'blog_title',
			'type' => 'text',
			'title' => esc_html__('Blog Title', 'cardealer-helper'),
			'desc' => esc_html__('Enter blog title. Note: This will be effective if "Your latest posts" is selected in "Front page displays" in "Setting > Reading".', 'cardealer-helper'),
			'default' => 'Blog'
		),
		array(
			'id' => 'blog_subtitle',
			'type' => 'text',
			'title' => esc_html__('Blog Subtitle', 'cardealer-helper'),
			'desc' => esc_html__('Enter blog subtitle. Note: This will be effective if "Your latest posts" is selected in "Front page displays" in "Setting > Reading" OR subtitle is blank in Blog page when "Static page" is selected in "Front page displays" in "Setting > Reading".', 'cardealer-helper'),
			'default' => 'Blog Subtitle'
		),
		array(
			'id'      => 'blog_sidebar',
			'type'    => 'image_select',
			'title'   => esc_html__('Blog Sidebar', 'cardealer-helper'),
			'subtitle'=> esc_html__('Select blog sidebar alignment.', 'cardealer-helper'),
			'options' => array(
				'full_width' => array(
					'alt' => 'Full Width',
					'img' => CDHL_URL . '/images/radio-button-imgs/blog_sidebar/full_width.png'
				),
				'left_sidebar' => array(
					'alt' => 'Left Sidebar',
					'img' => CDHL_URL . '/images/radio-button-imgs/blog_sidebar/left_sidebar.png'
				),
				'right_sidebar' => array(
					'alt' => 'Right Sidebar',
					'img' => CDHL_URL . '/images/radio-button-imgs/blog_sidebar/right_sidebar.png'
				),
			),
			'default' => 'right_sidebar'
		),
		array(
			'id'      => 'blog_layout',
			'type'    => 'image_select',
			'title'   => esc_html__('Blog Layout', 'cardealer-helper'),
			'subtitle'=> esc_html__('Select blog style.', 'cardealer-helper'),
			'options' => array(
				'classic' => array(
					'alt' => 'Classic',
					'img' => CDHL_URL . '/images/radio-button-imgs/blog_layout/classic.png'
				),
				'masonry' => array(
					'alt' => 'Masonry',
					'img' => CDHL_URL . '/images/radio-button-imgs/blog_layout/masonry.png'
				),
			),
			'default' => 'classic'
		),
		array(
			'id'     => 'masonry_size',
			'type'   => 'button_set',
			'title'  => esc_html__('Masonry Column Size', 'cardealer-helper'),
			'options'=> array(
				'2' => esc_html__('2 Column', 'cardealer-helper'),
				'3' => esc_html__('3 Column', 'cardealer-helper'),
				'4' => esc_html__('4 Column', 'cardealer-helper')
			),
			'default' => '2',
			'required' => array(
				array('blog_sidebar', 'equals', 'full_width'),
				array('blog_layout', '=', 'masonry')
			)
		),
		array(
			'id'      => 'masonry_size_info',
			'type'    => 'info',
			'title'   => esc_html__('Masonry Size!', 'cardealer-helper'),
			'style'   => 'warning',
			'icon'    => 'el-icon-info-sign',
			'desc'    => esc_html__('If sidebar is active masonry size will be set to 2 columns by default.', 'cardealer-helper'),
			'required'=> array(
				array('blog_sidebar', '!=', 'full_width'),
				array('blog_layout', '=', 'masonry')
			)
		),
		array(
			'id'      => 'blog_metas',
			'type'    => 'sortable',
			'mode'    => 'checkbox', // checkbox or text
			'title'   => esc_html__('Display Meta Items', 'cardealer-helper'),
			'subtitle'=> esc_html__('Select and reorder meta items to display', 'cardealer-helper'),
			'options' => array(
				'date'      => esc_html__('Date', 'cardealer-helper'),
				'author'    => esc_html__('Author', 'cardealer-helper'),
				'categories'=> esc_html__('Categories', 'cardealer-helper'),
				'tags'      => esc_html__('Tags', 'cardealer-helper'),
				'comments'  => esc_html__('Comments', 'cardealer-helper')
			),
			'default' => array(
				'date'      => '1',
				'author'    => '1',
				'categories'=> '1',
				'tags'      => '1',
				'comments'  => '1',
			),
			'required' => array( 'blog_layout', '=', 'classic' )
		),
	)
));
Redux::setSection($opt_name, array(
	'title'           => esc_html__('Archive Settings', 'cardealer-helper'),
	'id'              => 'archive_settings',
	'icon'            => 'el el-envelope-alt',
	'subsection'      => true,
	'customizer_width'=> '450px',
	'fields'          => array(
		array(
			'id'     => 'archive_header',
			'type'   => 'checkbox',
			'title'  => esc_html__('Display Archive Header.', 'cardealer-helper'),
			'desc'   => esc_html__('Select archive header to display on different archive pages.', 'cardealer-helper'),
			'options'=> array(
				'author'  => esc_html__('Author Info', 'cardealer-helper'),
				'category'=> esc_html__('Category Description', 'cardealer-helper'),
				'tag'     => esc_html__('Tag Description', 'cardealer-helper')
			),
			'default'     => array(
				'author'  => '0',
				'category'=> '0',
				'tag'     => '0',
			)
		),
	)
));
Redux::setSection($opt_name, array(
	'title'           => esc_html__('Single Post', 'cardealer-helper'),
	'id'              => 'single_settings',
	'icon'            => 'el el-pencil',
	'subsection'      => true,
	'customizer_width'=> '450px',
	'fields'          => array(
		array(
			'id'      => 'single_metas',
			'type'    => 'sortable',
			'mode'    => 'checkbox',
			'title'   => esc_html__('Display Meta Items', 'cardealer-helper'),
			'subtitle'=> esc_html__('Select and reorder meta items to display', 'cardealer-helper'),
			'options' => array(
				'date'      => esc_html__('Date', 'cardealer-helper'),
				'author'    => esc_html__('Author', 'cardealer-helper'),
				'categories'=> esc_html__('Categories', 'cardealer-helper'),
				'comments'  => esc_html__('Comments', 'cardealer-helper')
			),
			'default' => array(
				'date'      => '1',
				'author'    => '1',
				'categories'=> '1',
				'comments'  => '1',
			)
		),
		array(
			'id'     => 'related_posts',
			'type'   => 'switch',
			'title'  => esc_html__('Related Posts', 'cardealer-helper'),
			'on' => esc_html__('Show', 'cardealer-helper'),
			'off' => esc_html__('Hide', 'cardealer-helper'),
			'desc'   => esc_html__('Show/hide related posts.', 'cardealer-helper'),
			'default'=> true,
		),
		array(
			'id'     => 'author_details',
			'type'   => 'switch',
			'title'  => esc_html__('Author Details', 'cardealer-helper'),
			'on' => esc_html__('Show', 'cardealer-helper'),
			'off' => esc_html__('Hide', 'cardealer-helper'),
			'desc'   => esc_html__('Show/hide author details.', 'cardealer-helper'),
			'default'=> true,
		),
		array(
			'id'     => 'post_nav',
			'type'   => 'switch',
			'title'  => esc_html__('Post Navigation', 'cardealer-helper'),
			'on' => esc_html__('Show', 'cardealer-helper'),
			'off' => esc_html__('Hide', 'cardealer-helper'),
			'desc'   => esc_html__('Show/hide previous-next post links.', 'cardealer-helper'),
			'default'=> true,
		),
	)
));
/* ------------------------------------------------------------------------
 *
 * Team Page Settings
 *
 * ------------------------------------------------------------------------ */
Redux::setSection($opt_name, array(
	'title'           => esc_html__('Team Settings', 'cardealer-helper'),
	'id'              => 'team_settings',
	'customizer_width'=> '450px',
	'icon'            => 'fa fa-users',
	'fields'          => array(
		array(
			'id'      => 'team_layout',
			'type'    => 'image_select',
			'title'   => esc_html__('Team Layout', 'cardealer-helper'),
			'subtitle'=> esc_html__('Select team page layout.', 'cardealer-helper'),
			'options' => array(
				'layout_1' => array(
					'alt' => 'Layout 1',
					'img' => CDHL_URL . '/images/radio-button-imgs/team_layout/layout_1.png'
				),
				'layout_2' => array(
					'alt' => 'Layout 2',
					'img' => CDHL_URL . '/images/radio-button-imgs/team_layout/layout_2.png'
				),
			),
			'default' => 'layout_1'
		),
	)
));
/* ------------------------------------------------------------------------
 *
 * Woocommerce Settings
 *
 * ------------------------------------------------------------------------ */
/* woocommerce plugin is activate then only WooCommerce setting will be appear.  */
if(function_exists('is_plugin_active') && is_plugin_active('woocommerce/woocommerce.php')){
    Redux::setSection($opt_name, array(
    	'title'           		=> esc_html__('Woocommerce Settings', 'cardealer-helper'),
    	'subtitle'           	=> esc_html__('Setup for Woocommerce shop section. Please make sure you have installed Woocommerce Plugin', 'cardealer-helper'),
    	'id'              		=> 'woocommerce_settings',
    	'customizer_width'		=> '450px',
    	'icon'            		=> 'fa fa-shopping-cart',
    	'fields'          		=> array(
    		array(
    			'id'      		=> 'cart_icon',
    			'type'       	=> 'button_set',
    			'title'      	=> esc_html__('Show Cart Icon in menu', 'cardealer-helper'),
    			'subtitle'      => sprintf(
    									wp_kses(
    										__('Select <b>YES</b> to show the cart icon in menu. Select <b>NO</b> to hide the cart icon.'),
    										array(
    											'b'  => array(),
    											'br' => array(),
    										)
    									),
    								'cardealer-helper'	
    								),
    			'options'    	=> array(
    				'yes' 		=> esc_html__('Yes','cardealer-helper'),
    				'no' 		=> esc_html__('No','cardealer-helper'),
    			),
    			'default' => 'yes',
    		),
    		array(
    			'id'         => 'wc_product_list_column',
    			'type'       => 'radio',
    			'title'      => esc_html__('Woocommerce Product List Column', 'cardealer-helper'),
    			'subtitle'	 => esc_html__('Select how many column you want to show product list view.', 'cardealer-helper'),
    			'options'    => array(    				
    				'3' => esc_html__( 'Three Column', 'cardealer-helper' ),
    				'4'  => esc_html__( 'Four Column', 'cardealer-helper' )
    			),
    			'default' => '3'
    		),
    		array(
    			'id'         	=> 'products_per_pages',
    			'type' 			=> 'slider',
    			'title'      	=> esc_html__('Products Per Page', 'cardealer-helper'),
    			'subtitle'	 	=> esc_html__('Select how many product you want to show on SHOP page.', 'cardealer-helper'),
    			"default" 		=> 12,
    			"min" 			=> 4,
    			"step" 			=> 4,
    			"max" 			=> 300,
    			'display_value' => 'text'
    		),
    		array(
    			'id' => 'single_product_page_start',
    			'type' => 'section',
    			'title' => esc_html('Single Product Page Settings', 'cardealer-helper'),
    			'subtitle' => esc_html('Options for Single Product Page.', 'cardealer-helper'),
    			'indent' => true
    		),
    		array(
    			'id' 		=> 'show_related_products',
    			'type' 		=> 'button_set',
    			'title' 	=> esc_html('Show Related Products', 'cardealer-helper'),
    			'subtitle'  => sprintf(
    									wp_kses(
    										__('Select <b>YES</b> to show Related Products below the product description on single page.'),
    										array(
    											'b'  => array(),
    										)
    									),
    								'cardealer-helper'	
    								),
    			'options' 	=> array(
    							'yes'  	=> esc_html('Yes', 'cardealer-helper'),
    							'no' 	=> esc_html('No', 'cardealer-helper')
    						),
    			'default'	=> 'yes'
    		),
    		array(
    			'id' 		=> 'column_related_products',
    			'type' 		=> 'radio',
    			'title' 	=> esc_html('Column for Related Products', 'cardealer-helper'),
    			'subtitle'  => esc_html('Select how many column you want to show for product list of related products.', 'cardealer-helper'),
    			'options' 	=> array(
					'1'  	=> esc_html('One Column', 'cardealer-helper'),
					'2' 	=> esc_html('Two Columns', 'cardealer-helper'),
					'3'     => esc_html('Three Columns', 'cardealer-helper'),
					'4' 	=> esc_html('Four Columns', 'cardealer-helper')
				),
    			'default'	=> '4'
    		),
    		array(
    			'id'         	=> 'related_products_show',
    			'type' 			=> 'slider',
    			'title'      	=> esc_html__('Related Products Show', 'cardealer-helper'),
    			'subtitle'	 	=> esc_html__('Select how many product you want to show in Related Products area on single product page.', 'cardealer-helper'),
    			"default" 		=> 12,
    			"min" 			=> 4,
    			"step" 			=> 4,
    			"max" 			=> 300,
    			'display_value' => 'text'
    		),
    		array(
    			'id' => 'single_product_page_end',
    			'type' => 'section',
    			'indent' => false
    		),
    	)
    ));
}
/* ------------------------------------------------------------------------
 *
 * Social Profiles
 *
 * ------------------------------------------------------------------------ */
Redux::setSection( $opt_name, array(
	'title' => esc_html__('Site Info', 'cardealer-helper'),
	'id'    => 'site_info_section',	
	'icon'  => 'el el-cogs'
) );
Redux::setSection($opt_name, array(
	'title'           => esc_html__('Social Profiles', 'cardealer-helper'),
	'id'              => 'social_profiles_settings',
	'icon'            => 'el el-group-alt',
	'desc'            => esc_html__('Leave fields blank to hide.', 'cardealer-helper'),
	'customizer_width'=> '400px',
	'subsection'      => true,
	'fields'          => array(
		array(
			'id'      => 'facebook_url',
			'type'    => 'text',
			'title'   => esc_html__('Facebook', 'cardealer-helper'),
			'subtitle'=> esc_html__('Enter Facebook url.', 'cardealer-helper'),
			'validate'=> 'url',
			'default' => 'http://www.facebook.com',
		),
		array(
			'id'      => 'twitter_url',
			'type'    => 'text',
			'title'   => esc_html__('Twitter', 'cardealer-helper'),
			'subtitle'=> esc_html__('Enter Twitter url.', 'cardealer-helper'),
			'validate'=> 'url',
			'default' => 'http://www.twitter.com',
		),
		array(
			'id'      => 'dribbble_url',
			'type'    => 'text',
			'title'   => esc_html__('Dribbble', 'cardealer-helper'),
			'subtitle'=> esc_html__('Enter Dribbble url.', 'cardealer-helper'),
			'validate'=> 'url',
			'default' => 'http://www.dribbble.com',
		),
		array(
			'id'      => 'google_plus_url',
			'type'    => 'text',
			'title'   => esc_html__('Google Plus', 'cardealer-helper'),
			'subtitle'=> esc_html__('Enter Google Plus url.', 'cardealer-helper'),
			'validate'=> 'url',
			'default' => 'https://plus.google.com',
		),
		array(
			'id'      => 'vimeo_url',
			'type'    => 'text',
			'title'   => esc_html__('Vimeo', 'cardealer-helper'),
			'subtitle'=> esc_html__('Enter Vimeo url.', 'cardealer-helper'),
			'validate'=> 'url',
			'default' => 'http://www.vimeo.com',
		),
		array(
			'id'      => 'pinterest_url',
			'type'    => 'text',
			'title'   => esc_html__('Pinterest', 'cardealer-helper'),
			'subtitle'=> esc_html__('Enter Pinterest url.', 'cardealer-helper'),
			'validate'=> 'url',
			'default' => 'http://www.pinterest.com',
		),
		array(
			'id'      => 'behance_url',
			'type'    => 'text',
			'title'   => esc_html__('Behance', 'cardealer-helper'),
			'subtitle'=> esc_html__('Enter Behance url.', 'cardealer-helper'),
			'validate'=> 'url',
			'default' => 'http://www.behance.com',
		),
		array(
			'id'      => 'linkedin_url',
			'type'    => 'text',
			'title'   => esc_html__('Linkedin', 'cardealer-helper'),
			'subtitle'=> esc_html__('Enter Linkedin url.', 'cardealer-helper'),
			'validate'=> 'url',
			'default' => 'http://www.linkedin.com',
		),
		array(
			'id'      => 'instagram_url',
			'type'    => 'text',
			'title'   => esc_html__('Instagram', 'cardealer-helper'),
			'subtitle'=> esc_html__('Enter Instagram url.', 'cardealer-helper'),
			'validate'=> 'url',
			'default' => 'http://www.instagram.com',
		),
	)
));
Redux::setSection($opt_name, array(
	'title'           => esc_html__('Site Contacts', 'cardealer-helper'),
	'id'              => 'site_contacts',
	'icon'            => 'el el-th-list',
	'customizer_width'=> '400px',
	'subsection'      => true,
	'fields'          => array(
		array(
			'id'       => 'site_email',
			'type'     => 'text',
			'title'    => esc_html__('Email', 'cardealer-helper'),
			'desc'     => esc_html__('Enter email address.', 'cardealer-helper'),
			'default' => esc_html__('support@example.com','cardealer-helper'),
			'validate' => 'email',
			'msg'      => 'Please enter valid email.',
		),
		array(
			'id'       => 'site_phone',
			'type'     => 'text',
			'title'    => esc_html__('Phone Number', 'cardealer-helper'),
			'default'  => esc_html__('(007) 123 456 7890', 'cardealer-helper'),
			'desc'     => esc_html__('Enter phone number.', 'cardealer-helper'),
		),
        array(
			'id'       => 'site_contact_timing',
			'type'     => 'text',
			'title'    => esc_html__('Contact Timing', 'cardealer-helper'),
			'default'  => esc_html__('10:00 AM To 5:00 PM', 'cardealer-helper'),
			'desc'     => esc_html__('Enter Contact Timing.', 'cardealer-helper'),
		),
        array(
			'id'       => 'site_address',
			'type'     => 'text',
			'title'    => esc_html__('Address', 'cardealer-helper'),			
			'desc'     => esc_html__('Enter address.', 'cardealer-helper'),
		),
	)
));
/* ------------------------------------------------------------------------
 *
 * Social Sharing
 *
 * ------------------------------------------------------------------------ */
Redux::setSection($opt_name, array(
	'title'           => esc_html__('Social Sharing', 'cardealer-helper'),
	'id'              => 'social_sharing_settings',
	'desc'            => esc_html__('Enable disable sharing functionality.', 'cardealer-helper'),
	'customizer_width'=> '400px',
	'icon'            => 'fa fa-share-alt',
	'fields'          => array(
		array(
			'id'      => 'facebook_share',
			'type'    => 'switch',
			'title'   => esc_html__('Facebook Share', 'cardealer-helper'),			
			'desc'    => esc_html__('You can share post with facebook.', 'cardealer-helper'),
			'default' => '1'
		),
		array(
			'id'      => 'twitter_share',
			'type'    => 'switch',
			'title'   => esc_html__('Twitter Share', 'cardealer-helper'),			
			'desc'    => esc_html__('You can share post with twitter', 'cardealer-helper'),
			'default' => '1'
		),
		array(
			'id'      => 'linkedin_share',
			'type'    => 'switch',
			'title'   => esc_html__('Linkedin Share', 'cardealer-helper'),			
			'desc'    => esc_html__('You can share post with linkedin', 'cardealer-helper'),
			'default' => '1'
		),
		array(
			'id'      => 'google_plus_share',
			'type'    => 'switch',
			'title'   => esc_html__('Google Plus Share', 'cardealer-helper'),			
			'desc'    => esc_html__('You can share post with google plus', 'cardealer-helper'),
			'default' => '1'
		),
		array(
			'id'      => 'pinterest_share',
			'type'    => 'switch',
			'title'   => esc_html__('Pinterest Share', 'cardealer-helper'),			
			'desc'    => esc_html__('You can share post with pinterest', 'cardealer-helper'),
			'default' => '1'
		),
	)
));

/* Custom Typography options */
Redux::setSection($opt_name, array(
    'title' => esc_html__('Custom Typography', 'cardealer-helper'),
    'id' => 'custom-typography',
    'customizer_width' => '400px',
    'icon' => 'el el-font',
    'fields' => array(
		array(
			'id'          => 'opt-typography-body',
			'type'        => 'typography',
			'title'       => esc_html__( 'Body Font', 'cardealer-helper' ),
			'google'      => true,
			'units'       =>'px',
			'subtitle'    => esc_html__('Typography option with each property can be called individually.', 'cardealer-helper'),
			'subsets'     => false,
			'color'       => false,
			'text-align'  => false,
			'font-size'   => true,
			'line-height' => true,
			'font-backup' => true,
			'text-transform' => true,
			'letter-spacing' => true,
			'font-style'  => false,
			'font-weight' => true,
			'default'     => array(
				'font-family' => 'Open Sans',
				'font-backup' => 'Arial, Helvetica, sans-serif',
				'google'      => true,
				'font-weight' => 400,
				'text-transform' => 'none',
				'font-size' => '14px',
				'line-height' => '22px',
				'letter-spacing' => '0px'
			)
		),
        array(
			'id'          => 'opt-typography-h1',
			'type'        => 'typography',
			'title'       => esc_html__( 'H1 heading Typography', 'cardealer-helper' ),
			'google'      => true,
			'units'       =>'px',
			'subtitle'    => esc_html__('Typography option with each property can be called individually.', 'cardealer-helper'),
			'subsets'     => false,
			'color'       => false,
			'text-align'  => false,
			'font-size'   => true,
			'line-height' => true,
			'font-backup' => true,
			'text-transform' => true,
			'letter-spacing' => true,
			'font-style'  => false,
			'font-weight' => true,
			'default'     => array(
				'font-family' => 'Roboto',
				'font-backup' => 'Arial, Helvetica, sans-serif',
				'google'      => true,
				'font-weight' => 500,
				'text-transform' => 'uppercase',
				'font-size' => '40px',
				'line-height' => '50px',
				'letter-spacing' => '0px'
			)
		),
		array(
			'id'          => 'opt-typography-h2',
			'type'        => 'typography',
			'title'       => esc_html__( 'H2 heading Typography', 'cardealer-helper' ),
			'google'      => true,
			'units'       =>'px',
			'subtitle'    => esc_html__('Typography option with each property can be called individually.', 'cardealer-helper'),
			'subsets'     => false,
			'color'       => false,
			'text-align'  => false,
			'font-size'   => true,
			'line-height' => true,
			'font-backup' => true,
			'text-transform' => true,
			'letter-spacing' => true,
			'font-style'  => false,
			'font-weight' => true,
			'default'     => array(
				'font-family' => 'Roboto',
				'font-backup' => 'Arial, Helvetica, sans-serif',
				'google'      => true,
				'font-weight' => 500,
				'text-transform' => 'uppercase',
				'font-size' => '36px',
				'line-height' => '46px',
				'letter-spacing' => '0px'
			)
		),
		array(
			'id'          => 'opt-typography-h3',
			'type'        => 'typography',
			'title'       => esc_html__( 'H3 heading Typography', 'cardealer-helper' ),
			'google'      => true,
			'units'       =>'px',
			'subtitle'    => esc_html__('Typography option with each property can be called individually.', 'cardealer-helper'),
			'subsets'     => false,
			'color'       => false,
			'text-align'  => false,
			'font-size'   => true,
			'line-height' => true,
			'font-backup' => true,
			'text-transform' => true,
			'letter-spacing' => true,
			'font-style'  => false,
			'font-weight' => true,
			'default'     => array(
				'font-family' => 'Roboto',
				'font-backup' => 'Arial, Helvetica, sans-serif',
				'google'      => true,
				'font-weight' => 500,
				'text-transform' => 'uppercase',
				'font-size' => '28px',
				'line-height' => '42px',
				'letter-spacing' => '0px'
			)
		),
		array(
			'id'          => 'opt-typography-h4',
			'type'        => 'typography',
			'title'       => esc_html__( 'H4 heading Typography', 'cardealer-helper' ),
			'google'      => true,
			'units'       =>'px',
			'subtitle'    => esc_html__('Typography option with each property can be called individually.', 'cardealer-helper'),
			'subsets'     => false,
			'color'       => false,
			'text-align'  => false,
			'font-size'   => true,
			'line-height' => true,
			'font-backup' => true,
			'text-transform' => true,
			'letter-spacing' => true,
			'font-style'  => false,
			'font-weight' => true,
			'default'     => array(
				'font-family' => 'Roboto',
				'font-backup' => 'Arial, Helvetica, sans-serif',
				'google'      => true,
				'font-weight' => 500,
				'text-transform' => 'uppercase',
				'font-size' => '24px',
				'line-height' => '36px',
				'letter-spacing' => '0px'
			)
		),
		array(
			'id'          => 'opt-typography-h5',
			'type'        => 'typography',
			'title'       => esc_html__( 'H5 heading Typography', 'cardealer-helper' ),
			'google'      => true,
			'units'       =>'px',
			'subtitle'    => esc_html__('Typography option with each property can be called individually.', 'cardealer-helper'),
			'subsets'     => false,
			'color'       => false,
			'text-align'  => false,
			'font-size'   => true,
			'line-height' => true,
			'font-backup' => true,
			'text-transform' => true,
			'letter-spacing' => true,
			'font-style'  => false,
			'font-weight' => true,
			'default'     => array(
				'font-family' => 'Roboto',
				'font-backup' => 'Arial, Helvetica, sans-serif',
				'google'      => true,
				'font-weight' => 500,
				'text-transform' => 'uppercase',
				'font-size' => '18px',
				'line-height' => '30px',
				'letter-spacing' => '0px'
			)
		),
		array(
			'id'          => 'opt-typography-h6',
			'type'        => 'typography',
			'title'       => esc_html__( 'H6 heading Typography', 'cardealer-helper' ),
			'google'      => true,
			'units'       =>'px',
			'subtitle'    => esc_html__('Typography option with each property can be called individually.', 'cardealer-helper'),
			'subsets'     => false,
			'color'       => false,
			'text-align'  => false,
			'font-size'   => true,
			'line-height' => true,
			'font-backup' => true,
			'text-transform' => true,
			'letter-spacing' => true,
			'font-style'  => false,
			'font-weight' => true,
			'default'     => array(
				'font-family' => 'Roboto',
				'font-backup' => 'Arial, Helvetica, sans-serif',
				'google'      => true,
				'font-weight' => 500,
				'text-transform' => 'uppercase',
				'font-size' => '16px',
				'line-height' => '24px',
				'letter-spacing' => '0px'
			)
		)			
    )
));

Redux::setSection( $opt_name, array(
	'title' => esc_html__('Custom CSS/JS', 'cardealer-helper'),
	'id'    => 'editor-ace',
	'icon'  => 'fa fa-code',
	'fields'=> array(
		array(
			'id'       => 'custom_css',
			'type'     => 'ace_editor',
			'title'    => esc_html__('Custom CSS', 'cardealer-helper'),
			'mode'     => 'css',
			'theme'    => 'chrome',
			'desc'     => esc_html__('Paste your CSS code here.', 'cardealer-helper'),
		),
		array(
			'id'       => 'custom_js',
			'type'     => 'ace_editor',
			'title'    => esc_html__('Custom JS', 'cardealer-helper'),
			'mode'     => 'javascript',
			'theme'    => 'chrome',
			'desc'     => esc_html__('Paste your JS code here.', 'cardealer-helper'),
			'default'  => "jQuery(document).ready(function($){\n\n});"
		)
	)
));

//404 Page
Redux::setSection( $opt_name, array(
	'title'           => esc_html__('404 Page', 'cardealer-helper'),
	'id'              => 'fourofour_section',
	'customizer_width'=> '400px',
	'icon'            => 'fa fa-exclamation-triangle',
	'desc'            => esc_html__('Set 404 page title and content.', 'cardealer-helper'),
	'fields'          => array(
		// Page Title
		array(
			'id'      => 'fourofour_title_section-start',
			'type'    => 'section',
			'title'   => esc_html__('Page Title', 'cardealer-helper'),
			'subtitle'=> esc_html__('Here you can manage 404 page title.', 'cardealer-helper'),
			'indent'  => true
		),
		array(
			'id'         => 'fourofour_enable_page_title',
			'type'       => 'switch',
			'title'      => esc_html__('Enable Page Title', 'cardealer-helper'),
			'on'         => esc_html__('Yes', 'cardealer-helper'),
			'off'        => esc_html__('No', 'cardealer-helper'),
			'default'    => '0',
		),
		array(
			'id'       => 'fourofour_page_title_source',
			'type'     => 'button_set',
			'title'    => esc_html__('Page Title Source', 'cardealer-helper'),
			'options'  => array(
				'default'=> esc_html__('Default', 'cardealer-helper'),
				'custom' => esc_html__('Custom', 'cardealer-helper'),
			),
			'default'      => 'default',
			'required'     => array( 'fourofour_enable_page_title', '=', '1' ),
		),
		array(
			'id'           => 'fourofour_page_title',
			'type'         => 'text',
			'title'        => esc_html__('Page Title', 'cardealer-helper'),
			'desc'         => esc_html__('Enter custom 404 page title.', 'cardealer-helper'),
			'default'      => esc_html__('404! Page Not Found!', 'cardealer-helper'),
			'required'     => array( 'fourofour_page_title_source', '=', 'custom' ),
		),
		array(
			'id'      => 'fourofour_title_section-end',
			'type'   => 'section',
			'indent' => false,
		),
		
		// Page Content
		array(
			'id'      => 'fourofour_content_section-start',
			'type'    => 'section',
			'title'   => esc_html__('Page Content', 'cardealer-helper'),
			'subtitle'=> esc_html__('Here you can manage 404 page content.', 'cardealer-helper'),
			'indent'  => true
		),
		array(
			'id'       => 'fourofour_page_content_source',
			'type'     => 'button_set',
			'title'    => esc_html__('Page Content Type', 'cardealer-helper'),
			'options'  => array(
				'default'=> esc_html__('Default', 'cardealer-helper'),
				'page'   => esc_html__('Page', 'cardealer-helper'),
			),
			'default'      => 'default',
		),
		array(
			'id'           => 'fourofour_page_content_title',
			'type'         => 'text',
			'title'        => esc_html__('Content Title', 'cardealer-helper'),
			'desc'         => esc_html__('Enter custom 404 content title.', 'cardealer-helper'),
			'default'      => esc_html__('Ooopps...', 'cardealer-helper'),
			'required'     => array( 'fourofour_page_content_source', '=', 'default' ),
		),
		array(
			'id'           => 'fourofour_page_content_subtitle',
			'type'         => 'text',
			'title'        => esc_html__('Content Subitle', 'cardealer-helper'),
			'desc'         => esc_html__('Enter custom 404 content subtitle.', 'cardealer-helper'),
			'default'      => esc_html__("The Page you were looking for, couldn't be found.", 'cardealer-helper'),
			'required'     => array( 'fourofour_page_content_source', '=', 'default' ),
		),
		array(
			'id'           => 'fourofour_page_content_description',
			'type'         => 'textarea',
			'title'        => esc_html__('Content Description', 'cardealer-helper'),
			'desc'         => esc_html__('Enter custom 404 content description.', 'cardealer-helper'),
			'validate' => 'html_custom',
			'default' => sprintf( wp_kses( __( "Can't find what you looking for? Take a moment and do a search below or start from our <a class='link' href='%s'>Home Page</a>", 'cardealer-helper' ),
					array(
						'a' => array(
							'class' => array(),
							'href'  => array(),
						),
					)
				),
				esc_url( home_url( '/' ) )
			),
			'allowed_html' => array(
				'a' => array(
					'href' => array(),
					'title' => array(),
					'class' => array(),
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
			),
			'required'     => array( 'fourofour_page_content_source', '=', 'default' ),
		),
		array(
			'id'       => 'fourofour_page_image',
			'type'     => 'media',
			'url'      => true,
			'title'    => esc_html__('404 Image', 'cardealer-helper'),
			'desc'     => esc_html__('This image will be visible on 404 page', 'cardealer-helper'),
			'default'  => array( 'url'=> get_template_directory_uri().'/images/404-error.png' ),
			'required' => array( 'fourofour_page_content_source', '=', 'default' ),
		),
		array(
			'id'             => 'fourofour_page_content_padding',
			'type'           => 'spacing',
			'mode'           => 'padding',
			'units'          => array('px', 'em'),
			'units_extended' => 'false',
			'title'          => __('Content Padding', 'cardealer-helper'),
			'desc'           => __('You can enable or disable any piece of this field. Top, Right, Bottom, Left, or Units.', 'cardealer-helper'),
			'select2'            => array(
				'allowClear' => false,
			),
			'required'     => array( 'fourofour_page_content_source', '=', 'default' ),
		),
		array(
			'id'             => 'fourofour_page_content_margin',
			'type'           => 'spacing',
			'mode'           => 'margin',
			'units'          => array('px', 'em'),
			'units_extended' => 'false',
			'title'          => __('Content Margin', 'cardealer-helper'),
			'desc'           => __('You can enable or disable any piece of this field. Top, Right, Bottom, Left, or Units.', 'cardealer-helper'),
			'default'            => array(
				'units'          => 'px',
			),
			'select2'            => array(
				'allowClear' => false,
			),
			'required'     => array( 'fourofour_page_content_source', '=', 'default' ),
		),
		array(
			'id'      => 'fourofour_page_content_page',
			'type'    => 'select',
			'title'   => esc_html__('Page', 'cardealer-helper'),
			'desc'    => esc_html__('Select page to display as 404 page.', 'cardealer-helper'),
			'data'    => 'pages',
			'args' => array(
				'exclude' => cdhl_exclude_pages(),
			),
			'required'=> array( 'fourofour_page_content_source', '=', 'page' ),
		),
		array(
			'id'     => 'fourofour_content_section-end',
			'type'   => 'section',
			'indent' => false,
		),
	)
));

/* Maintenance */
Redux::setSection($opt_name, array(
	'title'           => esc_html__('Maintenance', 'cardealer-helper'),
	'id'              => 'maintenance',
	'customizer_width'=> '400px',
	'icon'            => 'fa fa-toggle-on',
	'desc'            => esc_html__('Enable/disable Maintenance or Coming Soon mode', 'cardealer-helper'),
	'fields'          => array(
		array(
			'id'         => 'enable_maintenance',
			'type'       => 'switch',
			'title'      => esc_html__('Enable Maintenance?', 'cardealer-helper'),
			'on'         => esc_html__('Yes', 'cardealer-helper'),
			'off'        => esc_html__('No', 'cardealer-helper'),
			'default'    => '0',
		),
		array(
			'id'       => 'maintenance_mode',
			'type'     => 'button_set',
			'title'    => esc_html__('Maintenance Mode', 'cardealer-helper'),
			'options'  => array(
				'maintenance' => esc_html__('Maintenance', 'cardealer-helper'),
				'comingsoon'  => esc_html__('Coming Soon', 'cardealer-helper'),
			),
			'default'  => 'maintenance',
			'required' => array(
				'enable_maintenance', '=', '1'
			),
		),
		array(
			'id'         => 'maintenance_title',
			'type'       => 'text',
			'title'      => esc_html__('Maintenance Title', 'cardealer-helper'),
			'default'    => esc_html__('Site is Under Maintenance', 'cardealer-helper'),
			'required' => array(
				array( 'maintenance_mode', '=', 'maintenance' ),
			),
		),
		array(
			'id'         => 'maintenance_subtitle',
			'type'       => 'text',
			'title'      => esc_html__('Maintenance Subtitle', 'cardealer-helper'),
			'default'    => esc_html__('This Site is Currently Under Maintenance. We will be back shortly', 'cardealer-helper'),
			'required' => array(
				array( 'maintenance_mode', '=', 'maintenance' ),
			),
		),
		array(
			'id'         => 'comingsoon_title',
			'type'       => 'text',
			'title'      => esc_html__('Coming Soon Title', 'cardealer-helper'),
			'default'    => esc_html__('Coming soon', 'cardealer-helper'),
			'required' => array(
				array( 'maintenance_mode', '=', 'comingsoon' ),
			),
		),
		array(
			'id'         => 'comingsoon_subtitle',
			'type'       => 'text',
			'title'      => esc_html__('Coming Soon Subtitle', 'cardealer-helper'),
			'default'    => esc_html__('We are working very hard on the new version of our site. It will bring a lot of new features. Stay tuned!', 'cardealer-helper'),
			'required' => array(
				array( 'maintenance_mode', '=', 'comingsoon' ),
			),
		),
		array(
			'id'          => 'comingsoon_date',
			'type'        => 'date',
			'title'       => esc_html__('Coming Soon Date', 'cardealer-helper'),
			'desc'        => esc_html__('Select coming soon date.', 'cardealer-helper'),
			'placeholder' => esc_html__('Click to enter a date', 'cardealer-helper'),
			'required' => array(
				array( 'maintenance_mode', '=', 'comingsoon' ),
			),
		),
		array(
			'id'          => 'comingsoon_back_image',
			'type'        => 'media',
			'title'       => esc_html__('Background Image', 'cardealer-helper'),
			'desc'        => esc_html__('Upload background image for Coming Soon page.', 'cardealer-helper'),
			'required' => array(
				array( 'maintenance_mode', '=', 'comingsoon' ),
			),
		),
		array(
			'id'      => 'comming_show_newsletter',
			'type'    => 'switch',
			'title'   => esc_html__('Display Newsletter', 'cardealer-helper'),
			'desc'    => esc_html__('Show/hide newsletter.', 'cardealer-helper'),
			'default' => false,
			'required' => array(
				'maintenance_mode', '=', 'comingsoon'
			),
		),
		array(
			'id'       => 'comming_page_newsletter_shortcode',
			'type'     => 'select',
			'title'    => esc_html__('Newsletter Form', 'cardealer-helper'), 			
			'desc'   => esc_html__('Select newsletter form for display newsletter box on Coming Soon Page.', 'cardealer-helper'),			
			// Must provide key => value pairs for select options
			'data'  => 'posts',
			'args'  => array(
				'post_type'        => 'mc4wp-form',
			),
			'required' => array(
				array('enable_maintenance', '=', '1'),
				array('comming_show_newsletter', '=', '1')
			),
		),
		array(
			'id'         => 'newsletter_description',
			'type'       => 'editor',
			'title'      => esc_html__('Newsletter Description', 'cardealer-helper'),
			'default'    => esc_html__('Provide your email address & we will notify you when site is ready:', 'cardealer-helper'),
			'required' => array(
				array( 'maintenance_mode', '=', 'comingsoon' ),
				array('comming_show_newsletter', '=', '1')
			),
		),
		array(
			'id' => 'commin_soon_social_icons',
			'title' => esc_html__('Display social Icons' , 'cardealer-helper'),
			'subtitle' => esc_html__('Show/Hide social icons in footer' , 'cardealer-helper'),
			'type' => 'switch',
			'default' => 1,
			'required' => array(
				'enable_maintenance', '=', '1'
			),
		),
		array(
			'id'     => 'newsletter_note',
			'type'   => 'info',
			'style'  => 'info',
			'title'  => esc_html__('Note: ', 'cardealer-helper'),
			'desc'   => esc_html__('In order to use newsletter, you will have to install "MailChimp for Wordpress" plugin. Then you need to configure it by adding API key and create new form then only you will be able to see that forms in form list in "Newsletter form".', 'cardealer-helper'),
			'required' => array(
				'maintenance_mode', '=', 'comingsoon'
			)
		)
	)
));
// Dynamically add documentation section
if (!function_exists('cardealer_helper_theme_documentation_section')) {
	function cardealer_helper_theme_documentation_section($sections) {
		$theme_data = wp_get_theme();
		$sections[] = array(
			'id'     => 'theme_about_section',
			'class'  => 'theme_about_section',
			'icon'   => get_template_directory_uri().'/images/admin/about-icon.png',
            'title'  => esc_html__('About', 'cardealer-helper').' '.$theme_data->get( 'Name' ),
            'fields' => array(
                array(
					'id'          => 'theme_about',
					'type'        => 'raw',
					'markdown'    => true,
					'content_path'=> get_template_directory() . '/README.md',
                ),
            ),
		);
		// Changelog
		$sections[] = array(
			'title'     => esc_html__('Changelog', 'cardealer-helper'),
			'class'  	=> 'theme_changelog_section',
			'icon' 		=> 'el el-file-edit',
			'subsection'=> true,
			'fields'    => array(
                array(
					'id'          => 'theme_changelog ',
					'type'        => 'raw',
					'markdown'    => true,
					'content_path'=> get_template_directory() . '/CHANGELOG.md',
                ),
            ),
		);
		return $sections;
	}
}
if ( file_exists( get_template_directory() . '/README.md' ) ) {
	add_filter('redux/options/' . CDHL_THEME_OPTIONS_NAME . '/sections', 'cardealer_helper_theme_documentation_section', 12);
}

/*
 * Update Theme option favicon icon from customizer site icon
 */

add_action('customize_save_response', 'cardealer_customize_save_response', 10, 2);
function  cardealer_customize_save_response($response, $data){
	$post_values = json_decode( wp_unslash( $_POST['customized'] ), true );
	
	if( isset($post_values['site_icon']) && $post_values['site_icon'] != '' ){
		$opt_name = CDHL_THEME_OPTIONS_NAME;
		$car_dealer_options = get_option( $opt_name );
		$site_icon = $post_values['site_icon'];		
		$img                   = wp_get_attachment_image_src( $site_icon, 'full' );
		$thumbnail             = wp_get_attachment_image_src( $site_icon, 'thumbnail' );
		$car_dealer_options['favicon_icon']['url']    = $img[0];
		$car_dealer_options['favicon_icon']['id']    = $site_icon;
		$car_dealer_options['favicon_icon']['width']  = $img[1];
		$car_dealer_options['favicon_icon']['height'] = $img[2];
		$car_dealer_options['favicon_icon']['thumbnail']    = $thumbnail[0];		
		update_option( $opt_name, $car_dealer_options );
	}
		
	return $response;
}

/*
 * Update customizer site icon from theme option favicon icon
 */
add_filter( "redux/options/{$opt_name}/ajax_save/response", 'cardealer_helper_option_save' );
function cardealer_helper_option_save( $response ){	
	if( isset($response['options']['favicon_icon']) && !empty($response['options']['favicon_icon']) ){
		$site_icon = get_option( 'site_icon' );
		$favicon_icon = $response['options']['favicon_icon']['id'];		
		if( $favicon_icon != $site_icon){
			update_option( 'site_icon', $favicon_icon);
		}
	}
	
	return $response;
}
