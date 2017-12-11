<?php
class CarDealer_Helper_Widget_Fuel_Efficiency extends WP_Widget {

	function __construct() {
		$widget_ops = array(
			'classname' => 'fuel_efficiency1',
			'description' => esc_html__( 'Add this widget to display fuel efficiency of cars.', 'cardealer-helper'), 
		);
		parent::__construct('fuel_efficiency1', esc_html__('Car Dealer - Fuel Efficiency', 'cardealer-helper'), $widget_ops);
	}
	public function widget( $args, $instance ) {	
        $title = apply_filters( 'widget_title', (empty( $instance['title'] ) ? esc_html__( 'Fuel Efficiency', 'cardealer-helper' ): $instance['title']), $instance, $this->id_base );
		$description = apply_filters( 'widget_description', (empty( $instance['description'] ) ? '' : $instance['description']), $instance, $this->id_base );
		$widget_id=$args['widget_id'];
		if((!empty(get_post_meta(get_the_ID(), 'city_mpg', $single = true))) || (!empty(get_post_meta(get_the_ID(), 'highway_mpg', $single = true))))
		{
		echo $args['before_widget'];
		?>
			<div class="details-form contact-2 details-weight">
				<?php 
				if ( ! empty( $instance['title'] ) ) {
					echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
				} 
				if((!empty(get_post_meta(get_the_ID(), 'city_mpg', $single = true))) || (!empty(get_post_meta(get_the_ID(), 'highway_mpg', $single = true))))
				{
				?>
				<div class="fuel-efficiency-detail">	
					<div class="heading"><h4><?php echo esc_html__( "Fuel Efficiency Rating", "cardealer-helper");?></h4></div>
					<div class="row">
						<div class="col-xs-4">	
							<label><?php echo esc_html__( "City", "cardealer-helper");?></label>
							<span class="city_mpg"><?php echo (!empty(get_post_meta(get_the_ID(), 'city_mpg', $single = true)))? get_post_meta(get_the_ID(), 'city_mpg', $single = true):""; ?></span>
						</div>
						<div class="col-xs-4">
							<i class="glyph-icon flaticon-gas-station fa-3x"></i>
						</div>
						<div class="col-xs-4">
							<label><?php echo esc_html__( "Highway", "cardealer-helper");?></label>						
							<span class="highway_mpg"><?php echo (!empty(get_post_meta(get_the_ID(), 'highway_mpg', $single = true)))? get_post_meta(get_the_ID(), 'highway_mpg', $single = true):""; ?></span>
						</div>
						<div class="col-sm-12">	
							<?php echo ($description)?'<p>'.$description.'</p>':'';?>			
						</div>
					</div>
				</div>
				<?php
				}
				?>
            </div>
		<?php		
		echo $args['after_widget'];
				}
	}
	public function update( $new_instance, $old_instance ) {
		return $new_instance;
	}
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Fuel Efficiency', 'cardealer-helper' );
		$description = ! empty( $instance['description'] ) ? $instance['description'] : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e('Title:','cardealer-helper' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php esc_html_e('Description:','cardealer-helper' ); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>"><?php echo esc_attr( $description ); ?></textarea>		
		</p>				        
		<?php 
	}
}
?>