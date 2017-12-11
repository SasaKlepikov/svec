<?php
/*==================================================*/
/* Sortcodes For Custom Functionalities.
/*==================================================*/


/** ***********************************************************
*       Footer Theme options shortcodes
* ************************************************************/

/** ***********************************************************
*    [1] CURRENT YEAR [ cd-current-year ]
* ************************************************************/
if ( ! function_exists( 'cdhl_get_year' ) ){
	function cdhl_get_year($params = array()) {
		$year = date("Y");
		// default parameters
		extract(shortcode_atts(array(
			'year'   => date("Y"),
		), $params));
		if( !empty($year) ){
			$copyright_year = $year;
		}
		return $copyright_year;
	}
	add_shortcode('cd-year', 'cdhl_get_year');
}

/** ***********************************************************
*    [2] get site name and url [ cd-site-title]
* ************************************************************/
if ( ! function_exists( 'cdhl_get_site_title' ) ){
	function cdhl_get_site_title($params = array()) {
		// default parameters
		extract(shortcode_atts(array(
			'site'   	=> get_bloginfo(),
			'site_url' 	=> get_site_url()
		), $params));
		if( !empty($site) && !empty($site_url) ){
			$site_name = sprintf(
								wp_kses('<a href="%1$s" target="_blank">%2$s</a>',
									array(
										'a' => array(
												'href' => array(),
												'target' => array()
											),
									)
								),
								$site_url,
								$site
							);
		}
		return $site_name;
	}
	add_shortcode('cd-site-title', 'cdhl_get_site_title');
}


/** ***********************************************************
*    [3] display navigation menu created for footer [ cd-footer-menu ]
* ************************************************************/
if ( ! function_exists( 'cdhl_get_footer_menu' ) ){
	function cdhl_get_footer_menu($params = array()) {
		if( has_nav_menu('footer-menu') ) {
			wp_nav_menu(
				array(
					'theme_location' => 'footer-menu',
					'menu_class' => 'list-inline text-right'
				)
			);
		}
	}
	add_shortcode('cd-footer-menu', 'cdhl_get_footer_menu');
}

