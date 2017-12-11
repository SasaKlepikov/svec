<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
} ?>
<div class="wrap cdhl-admin-theme-page cdhl-admin-wrap  cdhl-admin-plugins-screen">
<?php 
	cdhl_get_cardealer_tabs('plugins'); 
	$tgm_page_plugins = new TGM_Plugin_Activation();
	$tgm_page_plugins->install_plugins_page();
?>
</div>