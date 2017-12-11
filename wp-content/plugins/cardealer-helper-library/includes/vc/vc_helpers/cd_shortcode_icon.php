<?php
function cardealer_vc_shortcode_icon( $shortcode ){    
    $icon = CDHL_URL . '/images/vc-icon.png';
    if( file_exists(CDHL_PATH.'/images/shortcode_icons/'.$shortcode.'.png') ){
        $icon = CDHL_URL.'/images/shortcode_icons/'.$shortcode.'.png';
    }
    return apply_filters( 'pgscore_vc_shortcode_icon', $icon, $shortcode );
}
?>