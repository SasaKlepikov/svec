<?php
// Support page
function cdhl_get_support_data(){
	cdhl_load_theme_template('support');
}

// Plugin Page
function cdhl_get_plugin_data(){
	cdhl_load_theme_template('plugins');
}

// System Status
function cdhl_get_system_status(){
	cdhl_load_theme_template('system_status');
}

// Cardealer Ratings
function cdhl_get_ratings_page(){
	cdhl_load_theme_template('ratings');
}


/*Theme info*/
function cdhl_get_theme_info()
{
    $theme = wp_get_theme();
    $theme_name = $theme->get('Name');
    $theme_v = $theme->get('Version');

    $theme_info = array(
        'name' => $theme_name,
        'slug' => sanitize_file_name(strtolower($theme_name)),
        'v' => $theme_v,
    );
    return $theme_info;
}

function cdhl_load_theme_template( $path ) {
	$located = locate_template('templates/'. $path .'.php');
	if ($located) {
        load_template($located);
    } else {
		load_template( dirname( __FILE__ ) . '/templates/'. $path .'.php' );
	}
}

function cdhl_get_theme_support_url() {
	return esc_url('https://potezasupport.ticksy.com/submit/#100010500');
}

function cdhl_get_theme_doc_url() {
	return esc_url('http://docs.potenzaglobalsolutions.com/docs/cardealer');
}

function cdhl_get_theme_video_url() {
	return esc_url('http://docs.potenzaglobalsolutions.com/docs/cardealer/video/');
}

function cdhl_get_cardealer_tabs($screen = 'support')
{
    $theme = cdhl_get_theme_info();
    $theme_name = $theme['name']; 
	
	// Sample data(Demo) link
	if ( class_exists( 'WooCommerce' ) ) {
		$demo_link = esc_url_raw( admin_url('admin.php?page=cardealer&tab=37') );
	} else {
		$demo_link = esc_url_raw( admin_url('admin.php?page=cardealer&tab=36') );
	}
    ?>
    <div class="clearfix">
		<div class="cdhl-about-text-wrap">
            <h1><?php printf( esc_html__('Welcome to %s', 'cardealer-helper'), $theme_name ); ?></h1>
            <div class="cdhl-about-text about-text">
                <?php add_thickbox(); ?>
				<div class="cdhl_theme_info cardealer-welcome">
					<div class="welcome-left cardealer-welcome-badge <?php echo esc_attr( cardealer_welcome_logo() ? 'cardealer-welcome-badge-with-logo' : 'cardealer-welcome-badge-without-logo' );?>">
						<div class="wp-badge">
							<img src="<?php echo esc_url( CDHL_URL . 'images/theme-support/admin-welcome-logo.png' );?>" height="100" width="100" />
						</div>
						<div class="cardealer-welcome-badge-version">
							<?php echo sprintf( esc_html__('Version %s','cardealer-helper'), $theme['v']);?>
						</div>
					</div>
					<div class="welcome-right">
						<?php printf( wp_kses( __( '<strong>Car Dealer</strong> is now active and ready to use! <strong>Car Dealer</strong> is an elegant, clean, beautiful and best responsive WordPress theme. <strong>Car Dealer</strong> contains many usefull features and functionalities. And, it requires some plugins to be pre-installed to enable all inbuilt features and functionalities.', 'cardealer' ), array( 'strong' => array() ) ), $theme_name, $theme_name ); ?>
					</div>
				</div>
            </div>
        </div>
    </div>
    <h2 class="nav-tab-wrapper">
        <a href="<?php echo esc_url_raw(admin_url('admin.php?page=cardealer-support')); ?>"
           class="<?php echo ('support' === $screen) ? 'nav-tab-active' : ''; ?> nav-tab"><?php esc_attr_e('Support', 'cardealer-helper'); ?></a>
        <a href="<?php echo esc_url_raw(admin_url('admin.php?page=cardealer-plugins')); ?>"
           class="<?php echo ('plugins' === $screen) ? 'nav-tab-active' : ''; ?> nav-tab"><?php esc_attr_e('Plugins', 'cardealer-helper'); ?></a>
        <a href="<?php echo esc_url_raw($demo_link); ?>"
           class="<?php echo ('demos' === $screen) ? 'nav-tab-active' : ''; ?> nav-tab"><?php esc_attr_e('Install Demos', 'cardealer-helper'); ?></a>
        <a href="<?php echo esc_url_raw(admin_url('admin.php?page=cardealer')); ?>" class="nav-tab"><?php esc_attr_e('Theme Options', 'cardealer-helper'); ?></a>
        <a href="<?php echo esc_url_raw(admin_url('admin.php?page=cardealer-system-status')); ?>"
           class="<?php echo ('system-status' === $screen) ? 'nav-tab-active' : ''; ?> nav-tab"><?php esc_attr_e('System Status', 'cardealer-helper'); ?></a>
		<a href="<?php echo esc_url_raw(admin_url('admin.php?page=cardealer-ratings')); ?>"
           class="<?php echo ('ratings' === $screen) ? 'nav-tab-active' : ''; ?> nav-tab"><?php esc_attr_e('Ratings', 'cardealer-helper'); ?></a>
    </h2>
    <?php
}

function cdhl_convert_memory($size)
{
    $l = substr($size, -1);
    $ret = substr($size, 0, -1);
    switch (strtoupper($l)) {
        case 'P':
            $ret *= 1024;
        case 'T':
            $ret *= 1024;
        case 'G':
            $ret *= 1024;
        case 'M':
            $ret *= 1024;
        case 'K':
            $ret *= 1024;
    }
    return $ret;
}

?>