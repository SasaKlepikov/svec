<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
$theme = cdhl_get_theme_info();
$theme_name = $theme['name'];
?>
<div class="wrap cdhl-admin-theme-page cdhl-admin-wrap  cdhl-admin-support-screen">
	<?php cdhl_get_cardealer_tabs('support'); ?>

	<div class="support-bg">
		<div class="cdhl-admin-important-notice">
			<p class="about-description"><?php echo esc_html__( 'Car Dealer comes with 6 months of free support for every license you purchase. Support can be extended through subscriptions via ThemeForest.', 'cardealer-helper' ); ?></p>
		</div>
		<div class="cdhl-admin-row">
			<div class="cdhl-admin-two-third">
				<div class="cdhl-admin-row">
					<div class="cdhl-admin-one-half ticket">
						<div class="cdhl-admin-one-half-inner">
							<h3>
								<span>
									<img src="<?php echo esc_url(CDHL_URL. 'images/theme-support/ticket.png');?>" />
								</span>
								<?php esc_html_e( 'Ticket System', 'cardealer-helper' ); ?>
							</h3>
							<p>
								<?php esc_html_e( 'We offer excellent support through our advanced ticket system. Make sure to register your purchase first to access our support services and other resources.', 'cardealer-helper' ); ?>
							</p>
							<a href="<?php echo cdhl_get_theme_support_url(); ?>" target="_blank">
								<?php esc_html_e( 'Submit a ticket', 'cardealer-helper' ); ?>
							</a>
						</div>
					</div>

					<div class="cdhl-admin-one-half documentation">
						<div class="cdhl-admin-one-half-inner">
							<h3>
								<span>
									<img src="<?php echo esc_url(CDHL_URL. 'images/theme-support/documentation.png');?>" />
								</span>
								<?php esc_html_e( 'Documentation', 'cardealer-helper' ); ?>
							</h3>
							<p>
								<?php printf(__( 'Our online documentaiton is a useful resource for learning the every aspect and features of %s.', 'cardealer-helper' ), $theme_name); ?>
							</p>
							<a href="<?php echo cdhl_get_theme_doc_url(); ?>" target="_blank">
								<?php esc_html_e( 'Learn more', 'cardealer-helper' ); ?>
							</a>
						</div>
					</div>		 

					<div class="cdhl-admin-one-half video">
						<div class="cdhl-admin-one-half-inner">
							<h3>
								<span>
									<img src="<?php echo esc_url(CDHL_URL. 'images/theme-support/video.png');?>" />
								</span>
								<?php esc_html_e( 'Video Tutorials', 'cardealer-helper' ); ?>
							</h3>
							<p>
								<?php printf(__( 'We recommend you to watch video tutorials before you start the theme customization. Our video tutorials can teach you the different aspects of using %s.', 'cardealer-helper' ), $theme_name); ?>
							</p>
							<a href="<?php echo cdhl_get_theme_video_url(); ?>" target="_blank">
								<?php esc_html_e( 'Watch Videos', 'cardealer-helper' ); ?>
							</a>
						</div>
					</div>
				</div>
			</div>
		 </div>
	</div>
</div>