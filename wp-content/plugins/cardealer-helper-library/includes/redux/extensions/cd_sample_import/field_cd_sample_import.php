<?php
	/**
	 * Redux Framework is free software: you can redistribute it and/or modify
	 * it under the terms of the GNU General Public License as published by
	 * the Free Software Foundation, either version 2 of the License, or
	 * any later version.
	 * Redux Framework is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	 * GNU General Public License for more details.
	 * You should have received a copy of the GNU General Public License
	 * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
	 *
	 * @package     ReduxFramework
	 * @author      Dovy Paukstys
	 * @version     3.1.5
	 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Don't duplicate me!
if ( ! class_exists( 'ReduxFramework_cd_sample_import' ) ) {

	/**
	 * Main ReduxFramework_cd_sample_import class
	 *
	 * @since       1.0.0
	 */
	class ReduxFramework_cd_sample_import{

		/**
		 * Field Constructor.
		 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
		 *
		 * @since       1.0.0
		 * @access      public
		 * @return      void
		 */
		function __construct( $field = array(), $value = '', $parent ) {

			$this->parent   = $parent;
			$this->field    = $field;
			$this->value    = $value;
			
			if ( empty( $this->extension_dir ) ) {
				$this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
				$this->extension_url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', $this->extension_dir ) );
			}
			
			// Set default args for this field to avoid bad indexes. Change this to anything you use.
			$defaults    = array(
				'options'          => array(),
				'stylesheet'       => '',
				'output'           => true,
				'enqueue'          => true,
				'enqueue_frontend' => true
			);
			$this->field = wp_parse_args( $this->field, $defaults );
			
		}
		
		/**
		 * Field Render Function.
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @since       1.0.0
		 * @access      public
		 * @return      void
		 */
		public function render() {

			$secret = md5( md5( AUTH_KEY . SECURE_AUTH_KEY ) . '-' . $this->parent->args['opt_name'] );

			// No errors please
			$defaults = array(
				'full_width' => true,
				'overflow'   => 'inherit',
			);

			$this->field = wp_parse_args( $this->field, $defaults );
			$bDoClose = false;
			$id = $this->parent->args['opt_name'] . '-' . $this->field['id'];
			$cardealer_helper_sample_datas = cdhl_theme_sample_datas();
			$nonce    = wp_create_nonce( "sample_data_security");
			if( !empty($cardealer_helper_sample_datas) && is_array($cardealer_helper_sample_datas) ){
				?>
				<div class="sample-data-items">
					<?php
					foreach( $cardealer_helper_sample_datas as $sample_data ){
						?>
						<div class="sample-data-item sample-data-item-<?php echo esc_attr($sample_data['id']);?>">
							<?php
							if( file_exists($sample_data['data_dir'].'preview.png') ){
								?>
								<div class="sample-data-item-screenshot">
									<img src="<?php echo esc_url($sample_data['data_url']);?>/preview.png" alt="<?php echo esc_attr($sample_data['name']);?>"/>
								</div>
								<?php
							}else{
								?>
								<div class="sample-data-item-screenshot blank"></div>
								<?php
							}
							?>
							<span class="sample-data-item-details"><?php echo esc_html($sample_data['name']);?></span>
							<h2 class="sample-data-item-name"><?php echo esc_html($sample_data['name']);?></h2>
							<div class="sample-data-item-actions">
								<?php $required_plugins_list = cdhl_sample_data_required_plugins_list();?>
								<a href="#" class="button button-primary import-this-sample hide-if-no-customize"
									data-id="<?php echo esc_attr($sample_data['id']);?>"
									data-nonce="<?php echo esc_attr($nonce);?>"
									data-title="<?php echo esc_attr($sample_data['name']);?>"
									data-title="<?php echo esc_attr($sample_data['name']);?>"
									data-message="<?php echo esc_attr($sample_data['message']); ?>"
									<?php echo ( !empty($required_plugins_list) ) ? 'data-required-plugins="'.esc_attr(count($required_plugins_list)).'"' : '';?>>
									<?php echo esc_html__('Install', 'cardealer-helper');?>
								</a>
							</div>
						</div>
						<?php
					}
					?>
				</div>
				<?php
			}
		}

		/**
		 * Enqueue Function.
		 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
		 *
		 * @since       1.0.0
		 * @access      public
		 * @return      void
		 */
		public function enqueue() { 
			wp_enqueue_script(
				'redux-cd-import-export',
				$this->extension_url . 'field_cd_sample_import' . Redux_Functions::isMin() . '.js',
				array( 'jquery', 'jquery-confirm' ),
				time(),
				true
			);
			
			wp_localize_script( 'redux-cd-import-export', 'sample_data_import_object', array(
				'ajaxurl'                          => admin_url( 'admin-ajax.php' ),
				'alert_title'                      => esc_html__( 'Warning', 'cardealer-helper' ),
				'alert_proceed'                    => esc_html__( 'Proceed', 'cardealer-helper' ),
				'alert_cancel'                     => esc_html__( 'Cancel', 'cardealer-helper' ),
				'alert_install_plugins'            => esc_html__( 'Install Plugins', 'cardealer-helper' ),
				'alert_default_message'            => esc_html__( 'Importing demo content will import contents, widgets and theme options. Importing sample data will override current widgets and theme options. It can take some time to complete the import process.', 'cardealer-helper' ),
				'tgmpa_url'                        => admin_url( 'themes.php?page=theme-plugins' ),
				'sample_data_requirements'         => ( !empty(cdhl_sample_data_requirements()) ) ? array_values(cdhl_sample_data_requirements()) : false,
				'sample_data_required_plugins_list'=> ( !empty(cdhl_sample_data_required_plugins_list()) ) ? array_values(cdhl_sample_data_required_plugins_list()) : false,
			));
			
			wp_enqueue_style(
				'redux-cd-import-export-css',
				$this->extension_url . 'field_cd_sample_import.css',
				time(),
				true
			);
		}
	}
}