<?php
class CarDealer_Helper_Widget_Cars_Search extends WP_Widget {

	function __construct() {
		$widget_ops = array(
			'classname' => 'cars_search ',
			'description' => esc_html__( 'Add Cars Search in widget area.','cardealer-helper')
		);
		parent::__construct('cars_search', esc_html__('Car Dealer - Cars Search','cardealer-helper'), $widget_ops);
	}
	
	
	public function widget( $args, $instance ) {
		
		
        $title = apply_filters( 'widget_title', (empty( $instance['title'] ) ? esc_html__( 'Subscribe here','cardealer-helper' ): $instance['title']), $instance, $this->id_base );		
		
		$widget_id=$args['widget_id'];
		echo $args['before_widget'];
		?>
				
		            
		<div class="price-search">                                                        
            <input type="hidden" name="post_type" value="cars" />
            <?php if ( ! empty( $instance['title'] ) ) {?>
		
    		<?php echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];?>
    			
    		<?php
    		}                        
            ?>
            <div class="search">                    
                <input type="search" id="pgs_cars_search" class="form-control search-form placeholder" value="<?php echo esc_attr(get_search_query())?>" name="s" placeholder="<?php esc_attr_e( 'Search...', 'cardealer-helper' ) ?>" />                    
                <button class="search-button" id="pgs_cars_search_btn" value="Search" type="submit"><i class="fa fa-search"></i></button>
                <div class="auto-compalte-list"><ul></ul></div>
            </div>
        </div>		
		<?php		
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		return $new_instance;
	}
	
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Cars Search','cardealer-helper');
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e('Title:','cardealer-helper' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>				        
		<?php 
	}
}
?>