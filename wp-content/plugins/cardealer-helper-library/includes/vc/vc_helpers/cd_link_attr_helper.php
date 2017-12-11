<?php
function cdhl_vc_link_attr($url_vars){
	$link_attr = '';
	if( !empty($url_vars) && is_array($url_vars) ){
		foreach($url_vars as $url_var_k => $url_var_v ){
			if( !empty($url_var_v) ){
				if( !empty($link_attr) ){
					$link_attr .= ' ';
				}
				if( $url_var_k == 'url' ){
					$link_attr .= 'href="'.esc_url($url_var_v).'"';
				}else{
					$link_attr .= $url_var_k.'="'.$url_var_v.'"';
				}
			}
		}
	}
	return $link_attr;
}