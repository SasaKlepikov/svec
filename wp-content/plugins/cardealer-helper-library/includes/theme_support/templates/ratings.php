<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>

<div class="wrap cdhl-admin-theme-page cdhl-admin-wrap cdhl-system-status cdhl-admin-status-screen">
	<?php cdhl_get_cardealer_tabs('ratings'); ?>
	  <div class="ratings-content">
	  	<p><?php esc_html_e('Please don’t forget to rate Car Dealer and leave a nice review, it means a lot to us and our theme.', 'cardealer-helper');?></p>
		<p><?php printf( wp_kses( __('Simply login into your ThemeForest account, go to the <a target="_blank" href="$1%s">Downloads section </a>  and click 5 stars next to the Car Dealer WordPress theme as shown in the screenshot below:', 'cardealer-helper'), array('a'=> array('href'=> array(), 'target'=> array()))), esc_url('https://themeforest.net/downloads'));?></p>
	  	<img src="<?php echo esc_url(CDHL_URL. 'images/theme-support/rate.png');?>" />
	  </div>
</div>