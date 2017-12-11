<?php
function cdhl_banner_images($image_path = true){
	$cdhl_banners_path = trailingslashit(CDHL_PATH) . 'images/bg/';
	$cdhl_banners_url  = trailingslashit(CDHL_URL) . 'images/bg/';
	
	$cdhl_banners_new = array();
	
	if ( is_dir( $cdhl_banners_path ) ) {		
		$cdhl_banners_data = cdhl_pgscore_get_file_list( 'jpg,png', $cdhl_banners_path );
		if( !empty($cdhl_banners_data) ){
			foreach( $cdhl_banners_data as $cdhl_banner_path ) {
				if( $image_path ){
					$cdhl_banners_new[$cdhl_banners_url.basename($cdhl_banner_path)] = array(
						'alt'   => basename($cdhl_banner_path),
						'img'   => $cdhl_banners_url.basename($cdhl_banner_path),
						'height'=> 25,
						'width' => 100,
					);
				}else{
					$cdhl_banners_new[] = array(
						'alt'   => basename($cdhl_banner_path),
						'img'   => $cdhl_banners_url.basename($cdhl_banner_path),
						'height'=> 25,
						'width' => 100,
					);
				}
			}
		}
	}
	if( !$image_path ){
		array_unshift($cdhl_banners_new, null);
		unset($cdhl_banners_new[0]);
	}
	return $cdhl_banners_new;
}
function cdhl_banner_images_default(){
	$imgs = cdhl_banner_images();
	foreach($imgs as $img => $img_data){
		return $img;
	}
}

function cdhl_is_plugin_installed($search){
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	$plugins = get_plugins();
	$plugins = array_filter( array_keys($plugins), function($k){
		if( strpos( $k, '/' ) !== false ) return true;
	});
	$plugins_stat = function($plugins, $search){
		$new_plugins = array();
		foreach($plugins as $plugin){
			$new_plugins_data = explode( '/', $plugin );
			$new_plugins[] = $new_plugins_data[0];
		}
		return in_array($search,$new_plugins);
	};
	return $plugins_stat($plugins, $search);
}

function cdhl_is_plugin_active($plugin){
	if( empty($plugin) ){
		return false;
	}
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	return is_plugin_active($plugin);
}

// Filter out hard-coded width, height attributes on all captions (wp-caption class)
add_shortcode( 'wp_caption', 'cdhl_fixed_img_caption_shortcode' );
add_shortcode( 'caption', 'cdhl_fixed_img_caption_shortcode' );
function cdhl_fixed_img_caption_shortcode($attr, $content = null) {
	if ( ! isset( $attr['caption'] ) ) {
		if ( preg_match( '#((?:<a [^>]+>\s*)?<img [^>]+>(?:\s*</a>)?)(.*)#is', $content, $matches ) ) {
			$content = $matches[1];
			$attr['caption'] = trim( $matches[2] );
		}
	}
	$output = apply_filters( 'img_caption_shortcode', '', $attr, $content);
	if ( $output != '' ) return $output;
	extract(shortcode_atts(array(
		'id' => '',
		'align' => 'alignnone',
		'width' => '',
		'caption' => ''
	), $attr));
	if ( 1 > (int) $width || empty($caption) ) return $content;
	if ( $id ) $id = 'id="' . esc_attr($id) . '" ';
	return '<div ' . $id . 'class="wp-caption ' . esc_attr($align) . '" >'
	. do_shortcode( $content ) . '<p class="wp-caption-text">' . $caption . '</p></div>';
}

/**
 * Filter out hard-coded width, height attributes on all captions (wp-caption class)
 *
 * @since Car Dealer 1.0
 */
function cdhl_fix_img_caption_shortcode( $attr, $content = null ) {
	if ( ! isset( $attr['caption'] ) ) {
		if ( preg_match( '#((?:<a [^>]+>\s*)?<img [^>]+>(?:\s*</a>)?)(.*)#is', $content, $matches ) ) {
			$content = $matches[1];
			$attr['caption'] = trim( $matches[2] );
		}
	}
	$output = apply_filters( 'img_caption_shortcode', '', $attr, $content );
	if ( $output != '' ) return $output;
	extract( shortcode_atts( array(
		'id' => '',
		'align' => 'alignnone',
		'width' => '',
		'caption' => ''
	), $attr ) );
	if ( 1 > (int) $width || empty( $caption ) ) return $content;
	if ( $id ) $id = 'id="' . esc_attr( $id ) . '" ';
	return '<figure ' . $id . 'class="wp-caption ' . esc_attr($align) . '" >' . do_shortcode( $content ) . '<figcaption class="wp-caption-text">' . $caption . '</figcaption></figure>';
}
add_shortcode( 'wp_caption', 'cdhl_fix_img_caption_shortcode' );
add_shortcode( 'caption', 'cdhl_fix_img_caption_shortcode' );

function cdhl_get_excerpt_max_charlength($charlength, $excerpt = null) {
	if( empty($excerpt) ){
		$excerpt = get_the_excerpt();
	}
	$charlength++;
	
	if ( mb_strlen( $excerpt ) > $charlength ) {
		$subex = mb_substr( $excerpt, 0, $charlength - 5 );
		$exwords = explode( ' ', $subex );
		$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		
		$new_excerpt = '';
		if ( $excut < 0 ) {
			$new_excerpt = mb_substr( $subex, 0, $excut );
		} else {
			$new_excerpt = $subex;
		}
		$new_excerpt .= '[...]';
		return $new_excerpt;
	} else {
		return $excerpt;
	}
}
function cdhl_the_excerpt_max_charlength($charlength, $excerpt = null) {
	$new_excerpt = cdhl_get_excerpt_max_charlength($charlength, $excerpt);
	echo esc_html($new_excerpt);
}

/**
 * Truncate String with or without ellipsis.
 *
 * @param string $string      String to truncate
 * @param int    $maxLength   Maximum length of string
 * @param bool   $addEllipsis if True, "..." is added in the end of the string, default true
 * @param bool   $wordsafe    if True, Words will not be cut in the middle
 *
 * @return string Shotened Text
 */
function cdhl_shortenString($string = '', $maxLength, $addEllipsis = true, $wordsafe = false){
	if( empty($string) ){
		$string;
	}
	$ellipsis = '';
	$maxLength = max($maxLength, 0);
	if (mb_strlen($string) <= $maxLength) {
		return $string;
	}
	if ($addEllipsis) {
		$ellipsis = mb_substr( '...', 0, $maxLength);
		$maxLength -= mb_strlen($ellipsis);
		$maxLength = max($maxLength, 0);
	}
	if ($wordsafe) {
		$string = preg_replace( '/\s+?(\S+)?$/', '', mb_substr($string, 0, $maxLength));
	} else {
		$string = mb_substr($string, 0, $maxLength);
	}
	if ($addEllipsis) {
		$string .= $ellipsis;
	}

	return $string;
}

function cdhl_array_sort_by_column(&$array, $column, $direction = SORT_ASC) {
    $reference_array = array();	
    foreach($array as $key => $row) {
		if(isset($row[$column])){
			$reference_array[$key] = $row[$column];
		}
    }	
	if(sizeof($reference_array) == sizeof($array) ){
		array_multisort($reference_array, $array, $direction);
	}
}

// Get list of files in directory
function cdhl_pgscore_get_file_list( $extensions = '', $path = '' ){
	
	// Return if any paramater is blank
	if( empty($extensions) || empty($path) ){
		return false;
	}
	
	// Convert to array if string is provided
	if( !is_array($extensions) ){
		$extensions = array_filter( explode(',', $extensions) );
	}
	
	// Fix trailing slash if not provided.
	$path = rtrim( $path, '/\\' ) . '/';
	
	if ( defined( 'GLOB_BRACE' ) ){
		if( count($extensions) == 1 && $extensions[0] == '*' ){
			$files_with_glob = glob( $path."*", GLOB_BRACE );
		}else{
			$extensions_with_glob_brace = "{". implode(',',$extensions)."}"; // file extensions pattern
			$files_with_glob = glob( $path."*.$extensions_with_glob_brace", GLOB_BRACE );
		}
		
		return $files_with_glob;
	}else{
		if( count($extensions) == 1 && $extensions[0] == '*' ){
			$files_without_glob = glob( $path."*" );
		}else{
			$extensions_without_glob = implode('|',$extensions); // file extensions pattern
			
			$files_without_glob_all = glob( $path.'*.*' ); // Get all files
			
			$files_without_glob = array_values( preg_grep("~\.($extensions_without_glob)$~", $files_without_glob_all) ); // Filter files with pattern
		}
		return $files_without_glob;
	}
	
	return $files;
}

// Function to check promocode available or not
function cdhl_check_promocode_exist(){
	$args = array(
        'post_type' => 'cars_promocodes',
        'post_status' => 'publish'
    );
    $query = new WP_Query($args);
    if (count($query->posts)){
        return true;
    }
	return false;
}

function cdhl_currencie_list(){
    return array_unique(
		apply_filters( 'cdhl_currencie_list',
			array(
				'AED' => esc_html__( 'United Arab Emirates dirham', 'cardealer-helper' ),
				'AFN' => esc_html__( 'Afghan afghani', 'cardealer-helper' ),
				'ALL' => esc_html__( 'Albanian lek', 'cardealer-helper' ),
				'AMD' => esc_html__( 'Armenian dram', 'cardealer-helper' ),
				'ANG' => esc_html__( 'Netherlands Antillean guilder', 'cardealer-helper' ),
				'AOA' => esc_html__( 'Angolan kwanza', 'cardealer-helper' ),
				'ARS' => esc_html__( 'Argentine peso', 'cardealer-helper' ),
				'AUD' => esc_html__( 'Australian dollar', 'cardealer-helper' ),
				'AWG' => esc_html__( 'Aruban florin', 'cardealer-helper' ),
				'AZN' => esc_html__( 'Azerbaijani manat', 'cardealer-helper' ),
				'BAM' => esc_html__( 'Bosnia and Herzegovina convertible mark', 'cardealer-helper' ),
				'BBD' => esc_html__( 'Barbadian dollar', 'cardealer-helper' ),
				'BDT' => esc_html__( 'Bangladeshi taka', 'cardealer-helper' ),
				'BGN' => esc_html__( 'Bulgarian lev', 'cardealer-helper' ),
				'BHD' => esc_html__( 'Bahraini dinar', 'cardealer-helper' ),
				'BIF' => esc_html__( 'Burundian franc', 'cardealer-helper' ),
				'BMD' => esc_html__( 'Bermudian dollar', 'cardealer-helper' ),
				'BND' => esc_html__( 'Brunei dollar', 'cardealer-helper' ),
				'BOB' => esc_html__( 'Bolivian boliviano', 'cardealer-helper' ),
				'BRL' => esc_html__( 'Brazilian real', 'cardealer-helper' ),
				'BSD' => esc_html__( 'Bahamian dollar', 'cardealer-helper' ),
				'BTC' => esc_html__( 'Bitcoin', 'cardealer-helper' ),
				'BTN' => esc_html__( 'Bhutanese ngultrum', 'cardealer-helper' ),
				'BWP' => esc_html__( 'Botswana pula', 'cardealer-helper' ),
				'BYR' => esc_html__( 'Belarusian ruble', 'cardealer-helper' ),
				'BZD' => esc_html__( 'Belize dollar', 'cardealer-helper' ),
				'CAD' => esc_html__( 'Canadian dollar', 'cardealer-helper' ),
				'CDF' => esc_html__( 'Congolese franc', 'cardealer-helper' ),
				'CHF' => esc_html__( 'Swiss franc', 'cardealer-helper' ),
				'CLP' => esc_html__( 'Chilean peso', 'cardealer-helper' ),
				'CNY' => esc_html__( 'Chinese yuan', 'cardealer-helper' ),
				'COP' => esc_html__( 'Colombian peso', 'cardealer-helper' ),
				'CRC' => esc_html__( 'Costa Rican col&oacute;n', 'cardealer-helper' ),
				'CUC' => esc_html__( 'Cuban convertible peso', 'cardealer-helper' ),
				'CUP' => esc_html__( 'Cuban peso', 'cardealer-helper' ),
				'CVE' => esc_html__( 'Cape Verdean escudo', 'cardealer-helper' ),
				'CZK' => esc_html__( 'Czech koruna', 'cardealer-helper' ),
				'DJF' => esc_html__( 'Djiboutian franc', 'cardealer-helper' ),
				'DKK' => esc_html__( 'Danish krone', 'cardealer-helper' ),
				'DOP' => esc_html__( 'Dominican peso', 'cardealer-helper' ),
				'DZD' => esc_html__( 'Algerian dinar', 'cardealer-helper' ),
				'EGP' => esc_html__( 'Egyptian pound', 'cardealer-helper' ),
				'ERN' => esc_html__( 'Eritrean nakfa', 'cardealer-helper' ),
				'ETB' => esc_html__( 'Ethiopian birr', 'cardealer-helper' ),
				'EUR' => esc_html__( 'Euro', 'cardealer-helper' ),
				'FJD' => esc_html__( 'Fijian dollar', 'cardealer-helper' ),
				'FKP' => esc_html__( 'Falkland Islands pound', 'cardealer-helper' ),
				'GBP' => esc_html__( 'Pound sterling', 'cardealer-helper' ),
				'GEL' => esc_html__( 'Georgian lari', 'cardealer-helper' ),
				'GGP' => esc_html__( 'Guernsey pound', 'cardealer-helper' ),
				'GHS' => esc_html__( 'Ghana cedi', 'cardealer-helper' ),
				'GIP' => esc_html__( 'Gibraltar pound', 'cardealer-helper' ),
				'GMD' => esc_html__( 'Gambian dalasi', 'cardealer-helper' ),
				'GNF' => esc_html__( 'Guinean franc', 'cardealer-helper' ),
				'GTQ' => esc_html__( 'Guatemalan quetzal', 'cardealer-helper' ),
				'GYD' => esc_html__( 'Guyanese dollar', 'cardealer-helper' ),
				'HKD' => esc_html__( 'Hong Kong dollar', 'cardealer-helper' ),
				'HNL' => esc_html__( 'Honduran lempira', 'cardealer-helper' ),
				'HRK' => esc_html__( 'Croatian kuna', 'cardealer-helper' ),
				'HTG' => esc_html__( 'Haitian gourde', 'cardealer-helper' ),
				'HUF' => esc_html__( 'Hungarian forint', 'cardealer-helper' ),
				'IDR' => esc_html__( 'Indonesian rupiah', 'cardealer-helper' ),
				'ILS' => esc_html__( 'Israeli new shekel', 'cardealer-helper' ),
				'IMP' => esc_html__( 'Manx pound', 'cardealer-helper' ),
				'INR' => esc_html__( 'Indian rupee', 'cardealer-helper' ),
				'IQD' => esc_html__( 'Iraqi dinar', 'cardealer-helper' ),
				'IRR' => esc_html__( 'Iranian rial', 'cardealer-helper' ),
				'IRT' => esc_html__( 'Iranian toman', 'cardealer-helper' ),
				'ISK' => esc_html__( 'Icelandic kr&oacute;na', 'cardealer-helper' ),
				'JEP' => esc_html__( 'Jersey pound', 'cardealer-helper' ),
				'JMD' => esc_html__( 'Jamaican dollar', 'cardealer-helper' ),
				'JOD' => esc_html__( 'Jordanian dinar', 'cardealer-helper' ),
				'JPY' => esc_html__( 'Japanese yen', 'cardealer-helper' ),
				'KES' => esc_html__( 'Kenyan shilling', 'cardealer-helper' ),
				'KGS' => esc_html__( 'Kyrgyzstani som', 'cardealer-helper' ),
				'KHR' => esc_html__( 'Cambodian riel', 'cardealer-helper' ),
				'KMF' => esc_html__( 'Comorian franc', 'cardealer-helper' ),
				'KPW' => esc_html__( 'North Korean won', 'cardealer-helper' ),
				'KRW' => esc_html__( 'South Korean won', 'cardealer-helper' ),
				'KWD' => esc_html__( 'Kuwaiti dinar', 'cardealer-helper' ),
				'KYD' => esc_html__( 'Cayman Islands dollar', 'cardealer-helper' ),
				'KZT' => esc_html__( 'Kazakhstani tenge', 'cardealer-helper' ),
				'LAK' => esc_html__( 'Lao kip', 'cardealer-helper' ),
				'LBP' => esc_html__( 'Lebanese pound', 'cardealer-helper' ),
				'LKR' => esc_html__( 'Sri Lankan rupee', 'cardealer-helper' ),
				'LRD' => esc_html__( 'Liberian dollar', 'cardealer-helper' ),
				'LSL' => esc_html__( 'Lesotho loti', 'cardealer-helper' ),
				'LYD' => esc_html__( 'Libyan dinar', 'cardealer-helper' ),
				'MAD' => esc_html__( 'Moroccan dirham', 'cardealer-helper' ),
				'MDL' => esc_html__( 'Moldovan leu', 'cardealer-helper' ),
				'MGA' => esc_html__( 'Malagasy ariary', 'cardealer-helper' ),
				'MKD' => esc_html__( 'Macedonian denar', 'cardealer-helper' ),
				'MMK' => esc_html__( 'Burmese kyat', 'cardealer-helper' ),
				'MNT' => esc_html__( 'Mongolian t&ouml;gr&ouml;g', 'cardealer-helper' ),
				'MOP' => esc_html__( 'Macanese pataca', 'cardealer-helper' ),
				'MRO' => esc_html__( 'Mauritanian ouguiya', 'cardealer-helper' ),
				'MUR' => esc_html__( 'Mauritian rupee', 'cardealer-helper' ),
				'MVR' => esc_html__( 'Maldivian rufiyaa', 'cardealer-helper' ),
				'MWK' => esc_html__( 'Malawian kwacha', 'cardealer-helper' ),
				'MXN' => esc_html__( 'Mexican peso', 'cardealer-helper' ),
				'MYR' => esc_html__( 'Malaysian ringgit', 'cardealer-helper' ),
				'MZN' => esc_html__( 'Mozambican metical', 'cardealer-helper' ),
				'NAD' => esc_html__( 'Namibian dollar', 'cardealer-helper' ),
				'NGN' => esc_html__( 'Nigerian naira', 'cardealer-helper' ),
				'NIO' => esc_html__( 'Nicaraguan c&oacute;rdoba', 'cardealer-helper' ),
				'NOK' => esc_html__( 'Norwegian krone', 'cardealer-helper' ),
				'NPR' => esc_html__( 'Nepalese rupee', 'cardealer-helper' ),
				'NZD' => esc_html__( 'New Zealand dollar', 'cardealer-helper' ),
				'OMR' => esc_html__( 'Omani rial', 'cardealer-helper' ),
				'PAB' => esc_html__( 'Panamanian balboa', 'cardealer-helper' ),
				'PEN' => esc_html__( 'Peruvian nuevo sol', 'cardealer-helper' ),
				'PGK' => esc_html__( 'Papua New Guinean kina', 'cardealer-helper' ),
				'PHP' => esc_html__( 'Philippine peso', 'cardealer-helper' ),
				'PKR' => esc_html__( 'Pakistani rupee', 'cardealer-helper' ),
				'PLN' => esc_html__( 'Polish z&#x142;oty', 'cardealer-helper' ),
				'PRB' => esc_html__( 'Transnistrian ruble', 'cardealer-helper' ),
				'PYG' => esc_html__( 'Paraguayan guaran&iacute;', 'cardealer-helper' ),
				'QAR' => esc_html__( 'Qatari riyal', 'cardealer-helper' ),
				'RON' => esc_html__( 'Romanian leu', 'cardealer-helper' ),
				'RSD' => esc_html__( 'Serbian dinar', 'cardealer-helper' ),
				'RUB' => esc_html__( 'Russian ruble', 'cardealer-helper' ),
				'RWF' => esc_html__( 'Rwandan franc', 'cardealer-helper' ),
				'SAR' => esc_html__( 'Saudi riyal', 'cardealer-helper' ),
				'SBD' => esc_html__( 'Solomon Islands dollar', 'cardealer-helper' ),
				'SCR' => esc_html__( 'Seychellois rupee', 'cardealer-helper' ),
				'SDG' => esc_html__( 'Sudanese pound', 'cardealer-helper' ),
				'SEK' => esc_html__( 'Swedish krona', 'cardealer-helper' ),
				'SGD' => esc_html__( 'Singapore dollar', 'cardealer-helper' ),
				'SHP' => esc_html__( 'Saint Helena pound', 'cardealer-helper' ),
				'SLL' => esc_html__( 'Sierra Leonean leone', 'cardealer-helper' ),
				'SOS' => esc_html__( 'Somali shilling', 'cardealer-helper' ),
				'SRD' => esc_html__( 'Surinamese dollar', 'cardealer-helper' ),
				'SSP' => esc_html__( 'South Sudanese pound', 'cardealer-helper' ),
				'STD' => esc_html__( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra', 'cardealer-helper' ),
				'SYP' => esc_html__( 'Syrian pound', 'cardealer-helper' ),
				'SZL' => esc_html__( 'Swazi lilangeni', 'cardealer-helper' ),
				'THB' => esc_html__( 'Thai baht', 'cardealer-helper' ),
				'TJS' => esc_html__( 'Tajikistani somoni', 'cardealer-helper' ),
				'TMT' => esc_html__( 'Turkmenistan manat', 'cardealer-helper' ),
				'TND' => esc_html__( 'Tunisian dinar', 'cardealer-helper' ),
				'TOP' => esc_html__( 'Tongan pa&#x2bb;anga', 'cardealer-helper' ),
				'TRY' => esc_html__( 'Turkish lira', 'cardealer-helper' ),
				'TTD' => esc_html__( 'Trinidad and Tobago dollar', 'cardealer-helper' ),
				'TWD' => esc_html__( 'New Taiwan dollar', 'cardealer-helper' ),
				'TZS' => esc_html__( 'Tanzanian shilling', 'cardealer-helper' ),
				'UAH' => esc_html__( 'Ukrainian hryvnia', 'cardealer-helper' ),
				'UGX' => esc_html__( 'Ugandan shilling', 'cardealer-helper' ),
				'USD' => esc_html__( 'United States dollar', 'cardealer-helper' ),
				'UYU' => esc_html__( 'Uruguayan peso', 'cardealer-helper' ),
				'UZS' => esc_html__( 'Uzbekistani som', 'cardealer-helper' ),
				'VEF' => esc_html__( 'Venezuelan bol&iacute;var', 'cardealer-helper' ),
				'VND' => esc_html__( 'Vietnamese &#x111;&#x1ed3;ng', 'cardealer-helper' ),
				'VUV' => esc_html__( 'Vanuatu vatu', 'cardealer-helper' ),
				'WST' => esc_html__( 'Samoan t&#x101;l&#x101;', 'cardealer-helper' ),
				'XAF' => esc_html__( 'Central African CFA franc', 'cardealer-helper' ),
				'XCD' => esc_html__( 'East Caribbean dollar', 'cardealer-helper' ),
				'XOF' => esc_html__( 'West African CFA franc', 'cardealer-helper' ),
				'XPF' => esc_html__( 'CFP franc', 'cardealer-helper' ),
				'YER' => esc_html__( 'Yemeni rial', 'cardealer-helper' ),
				'ZAR' => esc_html__( 'South African rand', 'cardealer-helper' ),
				'ZMW' => esc_html__( 'Zambian kwacha', 'cardealer-helper' ),
			)
		)
	);
}

function cdhl_get_currency_symbols($currency_code){
    $currency_symbols = array(
    	'AED' => '&#x62f;.&#x625;',
		'AFN' => '&#x60b;',
		'ALL' => 'L',
		'AMD' => 'AMD',
		'ANG' => '&fnof;',
		'AOA' => 'Kz',
		'ARS' => '&#36;',
		'AUD' => '&#36;',
		'AWG' => 'Afl.',
		'AZN' => 'AZN',
		'BAM' => 'KM',
		'BBD' => '&#36;',
		'BDT' => '&#2547;&nbsp;',
		'BGN' => '&#1083;&#1074;.',
		'BHD' => '.&#x62f;.&#x628;',
		'BIF' => 'Fr',
		'BMD' => '&#36;',
		'BND' => '&#36;',
		'BOB' => 'Bs.',
		'BRL' => '&#82;&#36;',
		'BSD' => '&#36;',
		'BTC' => '&#3647;',
		'BTN' => 'Nu.',
		'BWP' => 'P',
		'BYR' => 'Br',
		'BZD' => '&#36;',
		'CAD' => '&#36;',
		'CDF' => 'Fr',
		'CHF' => '&#67;&#72;&#70;',
		'CLP' => '&#36;',
		'CNY' => '&yen;',
		'COP' => '&#36;',
		'CRC' => '&#x20a1;',
		'CUC' => '&#36;',
		'CUP' => '&#36;',
		'CVE' => '&#36;',
		'CZK' => '&#75;&#269;',
		'DJF' => 'Fr',
		'DKK' => 'DKK',
		'DOP' => 'RD&#36;',
		'DZD' => '&#x62f;.&#x62c;',
		'EGP' => 'EGP',
		'ERN' => 'Nfk',
		'ETB' => 'Br',
		'EUR' => '&euro;',
		'FJD' => '&#36;',
		'FKP' => '&pound;',
		'GBP' => '&pound;',
		'GEL' => '&#x10da;',
		'GGP' => '&pound;',
		'GHS' => '&#x20b5;',
		'GIP' => '&pound;',
		'GMD' => 'D',
		'GNF' => 'Fr',
		'GTQ' => 'Q',
		'GYD' => '&#36;',
		'HKD' => '&#36;',
		'HNL' => 'L',
		'HRK' => 'Kn',
		'HTG' => 'G',
		'HUF' => '&#70;&#116;',
		'IDR' => 'Rp',
		'ILS' => '&#8362;',
		'IMP' => '&pound;',
		'INR' => '&#8377;',
		'IQD' => '&#x639;.&#x62f;',
		'IRR' => '&#xfdfc;',
		'IRT' => '&#x062A;&#x0648;&#x0645;&#x0627;&#x0646;',
		'ISK' => 'kr.',
		'JEP' => '&pound;',
		'JMD' => '&#36;',
		'JOD' => '&#x62f;.&#x627;',
		'JPY' => '&yen;',
		'KES' => 'KSh',
		'KGS' => '&#x441;&#x43e;&#x43c;',
		'KHR' => '&#x17db;',
		'KMF' => 'Fr',
		'KPW' => '&#x20a9;',
		'KRW' => '&#8361;',
		'KWD' => '&#x62f;.&#x643;',
		'KYD' => '&#36;',
		'KZT' => 'KZT',
		'LAK' => '&#8365;',
		'LBP' => '&#x644;.&#x644;',
		'LKR' => '&#xdbb;&#xdd4;',
		'LRD' => '&#36;',
		'LSL' => 'L',
		'LYD' => '&#x644;.&#x62f;',
		'MAD' => '&#x62f;.&#x645;.',
		'MDL' => 'MDL',
		'MGA' => 'Ar',
		'MKD' => '&#x434;&#x435;&#x43d;',
		'MMK' => 'Ks',
		'MNT' => '&#x20ae;',
		'MOP' => 'P',
		'MRO' => 'UM',
		'MUR' => '&#x20a8;',
		'MVR' => '.&#x783;',
		'MWK' => 'MK',
		'MXN' => '&#36;',
		'MYR' => '&#82;&#77;',
		'MZN' => 'MT',
		'NAD' => '&#36;',
		'NGN' => '&#8358;',
		'NIO' => 'C&#36;',
		'NOK' => '&#107;&#114;',
		'NPR' => '&#8360;',
		'NZD' => '&#36;',
		'OMR' => '&#x631;.&#x639;.',
		'PAB' => 'B/.',
		'PEN' => 'S/.',
		'PGK' => 'K',
		'PHP' => '&#8369;',
		'PKR' => '&#8360;',
		'PLN' => '&#122;&#322;',
		'PRB' => '&#x440;.',
		'PYG' => '&#8370;',
		'QAR' => '&#x631;.&#x642;',
		'RMB' => '&yen;',
		'RON' => 'lei',
		'RSD' => '&#x434;&#x438;&#x43d;.',
		'RUB' => '&#8381;',
		'RWF' => 'Fr',
		'SAR' => '&#x631;.&#x633;',
		'SBD' => '&#36;',
		'SCR' => '&#x20a8;',
		'SDG' => '&#x62c;.&#x633;.',
		'SEK' => '&#107;&#114;',
		'SGD' => '&#36;',
		'SHP' => '&pound;',
		'SLL' => 'Le',
		'SOS' => 'Sh',
		'SRD' => '&#36;',
		'SSP' => '&pound;',
		'STD' => 'Db',
		'SYP' => '&#x644;.&#x633;',
		'SZL' => 'L',
		'THB' => '&#3647;',
		'TJS' => '&#x405;&#x41c;',
		'TMT' => 'm',
		'TND' => '&#x62f;.&#x62a;',
		'TOP' => 'T&#36;',
		'TRY' => '&#8378;',
		'TTD' => '&#36;',
		'TWD' => '&#78;&#84;&#36;',
		'TZS' => 'Sh',
		'UAH' => '&#8372;',
		'UGX' => 'UGX',
		'USD' => '&#36;',
		'UYU' => '&#36;',
		'UZS' => 'UZS',
		'VEF' => 'Bs F',
		'VND' => '&#8363;',
		'VUV' => 'Vt',
		'WST' => 'T',
		'XAF' => 'Fr',
		'XCD' => '&#36;',
		'XOF' => 'Fr',
		'XPF' => 'Fr',
		'YER' => '&#xfdfc;',
		'ZAR' => '&#82;',
		'ZMW' => 'ZK',
    );
    $currencysymbols = apply_filters( 'cdhl_get_currency_symbols',$currency_symbols);    
    
    $currency_symbol = isset( $currency_symbols[ $currency_code ] ) ? $currency_symbols[ $currency_code ] : $currency_code;

	return apply_filters( 'cdhl_get_currency_symbol', $currency_symbol, $currency_code );    
}

function cdhl_currency_option_list(){
    $cdhl_currencie_list = cdhl_currencie_list();
    $currencie_list = array();
	foreach ( $cdhl_currencie_list as $code => $name ) {
		$currencie_list[ $code ] = $name . ' (' . cdhl_get_currency_symbols( $code ) . ')';        
	}
    return $currencie_list;    
}

//Function for exclude the pages for 404 page
function cdhl_exclude_pages(){
	$exclude_pages = array();
	
	// Exclude Home and Blog pages.
	$show_on_front = get_option('show_on_front');
	if( $show_on_front == 'page' ){
		
		$page_on_front = get_option('page_on_front');
		$page_for_posts = get_option('page_for_posts');
		
		if( isset($page_on_front) && $page_on_front != '0' ){
			$exclude_pages[] = $page_on_front;
		}
		
		if( isset($page_for_posts) && $page_for_posts != '0' ){
			$exclude_pages[] = $page_for_posts;
		}
	}

	// Exclude WooCommerce pages
	if ( class_exists( 'WooCommerce' ) ) {
		$woocommerce_pages = array(
			'woocommerce_shop_page_id',
			'woocommerce_cart_page_id',
			'woocommerce_checkout_page_id',
			'woocommerce_pay_page_id',
			'woocommerce_thanks_page_id',
			'woocommerce_myaccount_page_id',
			'woocommerce_edit_address_page_id',
			'woocommerce_view_order_page_id',
			'woocommerce_terms_page_id',
		);
		foreach( $woocommerce_pages as $woocommerce_page ){
			$woocommerce_page_id = get_option($woocommerce_page);
			if( $woocommerce_page_id ){
				$exclude_pages[] = $woocommerce_page_id;
			}
		}
	}
	
	return $exclude_pages;
}

// Function for logging background processes
function cdhl_log( $message, $version = "", $type = "") {
	$file = CDHL_LOG . "cdhl_back_log_". $version . "_" . date_i18n( 'm-d-Y' ) .".txt"; 
	$open = fopen( $file, "a" ); 
	$log_txt = date_i18n( 'm-d-Y @ H:i:s' ). " " . " ". $type . " ". $message ."\n";
	$write = fputs( $open, $log_txt );
	fclose($open);
}