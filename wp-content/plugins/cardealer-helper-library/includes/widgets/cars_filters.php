<?php
class CarDealer_Helper_Widget_Cars_Filters extends WP_Widget {

	var $taxonomys = array(
        'car_year',
        'car_make',
        'car_model',
        'car_body_style',
        'car_condition',
        'car_mileage',
        'car_transmission',        
        'car_drivetrain',
        'car_engine',
        'car_fuel_economy',
        'car_exterior_color'
    );
    function __construct() {
		$widget_ops = array(
			'classname' => 'cars_filters',
			'description' => esc_html__( 'Add Cars Filters widget in car listing widget area.','cardealer-helper')
		);
		parent::__construct('cars_filters', esc_html__('Car Dealer - Cars Filters','cardealer-helper'), $widget_ops);
	    
    }
	
	
	public function widget( $args, $instance ) {
		
		$widget_id=$args['widget_id'];
		echo $args['before_widget'];
    			
		if ( ! empty( $instance['title'] ) ) {			
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];			
		}
		if(function_exists('cardealer_get_all_filters')){
			cardealer_get_all_filters();
		}
               		
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		return $new_instance;
	}
	
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Cars Filters','cardealer-helper');		 
        ?>
        <p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e('Title:','cardealer-helper' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php  
	}
}
?>