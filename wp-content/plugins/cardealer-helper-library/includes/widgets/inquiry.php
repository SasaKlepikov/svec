<?php
class CarDealer_Helper_Widget_Inquiry extends WP_Widget {

	function __construct() {
		$widget_ops = array(
			'classname' => 'inquiry',
			'description' => esc_html__( 'Add inquiry form to get users inquiry.', 'cardealer-helper'), 
		);
		parent::__construct('inquiry', esc_html__('Car Dealer - Inquiry', 'cardealer-helper'), $widget_ops);
	}
	
	
	public function widget( $args, $instance ) {		
        $title = apply_filters( 'widget_title', (empty( $instance['title'] ) ? esc_html__( 'Send Inquiry', 'cardealer-helper' ): $instance['title']), $instance, $this->id_base );
		$description = apply_filters( 'widget_description', (empty( $instance['description'] ) ? '' : $instance['description']), $instance, $this->id_base );
		$button_text = apply_filters( 'widget_button_text', (empty( $instance['button_text'] ) ? esc_html__( 'request a service', 'cardealer-helper' ) : $instance['button_text']), $instance, $this->id_base );
		wp_enqueue_script( 'cardealer-google-recaptcha-apis' );
		$widget_id=$args['widget_id'];
		echo $args['before_widget'];
		?>
			<div class="details-form contact-2 details-weight inquiry-widget">
               <form class="gray-form" method="post" id="inquiry-form-sidebar-<?php echo esc_html($widget_id);?>">
                
				<?php if ( ! empty( $instance['title'] ) ) {
					echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
				}?>
				
				<?php if($description){
					echo '<p>'.$description.'</p>';
				}
				?>
                <div class="form-group">
					<input type="hidden" name="action" class="form-control" value="car_inquiry_action">
					<input type="hidden" name="rmi_nonce" value="<?php echo wp_create_nonce("req-info-form");?>">
                   <label><?php echo esc_html__('First Name', 'cardealer-helper');?>*</label>
                   <input type="text" class="form-control cdhl_validate" name="first_name">
                </div>
                <div class="form-group">
                    <label><?php echo esc_html__('Last Name', 'cardealer-helper');?>*</label>
                    <input type="text" class="form-control cdhl_validate" name="last_name">
                </div>
				<div class="form-group">
                    <label><?php echo esc_html__('Email', 'cardealer-helper');?>*</label>
                    <input type="text" class="form-control cdhl_validate cardealer_mail" name="email">
                </div>
				<div class="form-group">
                    <label><?php echo esc_html__('Mobile', 'cardealer-helper');?></label>
                    <input type="text" class="form-control" name="mobile">
                </div>
				<div class="form-group">
                    <label><?php echo esc_html__('Address', 'cardealer-helper');?></label>
					<textarea class="form-control" name="address" rows="4"></textarea>
                </div>
				<div class="form-group">
                    <label><?php echo esc_html__('State', 'cardealer-helper');?>*</label>
                    <input type="text" class="form-control cdhl_validate" name="state">
                </div>
				<div class="form-group">
                    <label><?php echo esc_html__('Zip', 'cardealer-helper');?>*</label>
                    <input type="number" class="form-control cdhl_validate" name="zip">
                </div>
				<div class="form-group">
					<label><?php echo esc_html__('Preferred Contact', 'cardealer-helper');?>*</label>
					<div class="radio">
						<label><input class="check" type="radio" name="contact" value="email" checked required><?php echo esc_html__('Email', 'cardealer-helper');?></label>
					</div>
					<div class="radio">
						<label><input type="radio" name="contact" value="phone" required><?php echo esc_html__('Phone', 'cardealer-helper');?></label>
					</div>
                </div>
				<div class="form-group">
					<div id="recaptcha6"></div>
				</div>
				<div class="form-group">
					<button id="submit-inquiry" class="button red" ><?php echo esc_html__('Request a service', 'cardealer-helper');?></button>
					<span class="spinimg"></span>
					<div class="inquiry-msg" style="display:none;"></div>
				</div>
              </form>
            </div>
		<?php		
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		return $new_instance;
	}
	
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Inquiry', 'cardealer-helper' );
		$description = ! empty( $instance['description'] ) ? $instance['description'] : '';
		$button_text = ! empty( $instance['button_text'] ) ? $instance['button_text'] : esc_html__( 'Request a service', 'cardealer-helper' );		
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e('Title:','cardealer-helper' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php esc_html_e('Description:','cardealer-helper' ); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>"><?php echo esc_attr( $description ); ?></textarea>		
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>"><?php esc_html_e('Button Text:','cardealer-helper' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_text' ) ); ?>" type="text" value="<?php echo esc_attr( $button_text ); ?>">
		</p>		        
		<?php 
	}
}

?>