<?php
// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class lffs_widget extends WP_Widget {
	// Constructor 
	public function __construct() {
		$widget_ops = array( 'classname' => 'lffs-sidebar', 'description' => __('Display your contact form in a widget.', 'lf-frontend-submission') );
		parent::__construct( 'lffs-widget', __('Lf Front End Submission', 'lf-frontend-submission'), $widget_ops );
	}

	// Set widget and title in dashboard
	function form( $instance ) {
		$instance = wp_parse_args( $instance, array(
			'title' => '',
			'text' => '',
			'attributes' => ''
		));
		$title = !empty( $instance['title'] ) ? $instance['title'] : __('Lf Front End Submission', 'lf-frontend-submission'); 
		$text = $instance['text'];
		$attributes = $instance['attributes'];
		?> 
		<p> 
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title', 'lf-frontend-submission'); ?>:</label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" maxlength='50' value="<?php echo esc_attr( $title ); ?>">
 		</p> 
		<p>
		<label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Information', 'lf-frontend-submission'); ?>:</label>
		<textarea class="widefat monospace" rows="6" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo wp_kses_post( $text ); ?></textarea>
		</p>
		<p> 
		<label for="<?php echo $this->get_field_id( 'attributes' ); ?>"><?php _e('Attributes', 'lf-frontend-submission'); ?>:</label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'attributes' ); ?>" name="<?php echo $this->get_field_name( 'attributes' ); ?>" type="text" maxlength='200' value="<?php echo esc_attr( $attributes ); ?>">
 		</p> 
		 
		<?php 
	}

	// Update widget 
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		// Sanitize content
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['text'] = wp_kses_post( $new_instance['text'] );
		$instance['attributes'] = strip_tags( $new_instance['attributes'] );

		return $instance;
	}

	// Display widget with form in frontend 
	function widget( $args, $instance ) {
		echo $args['before_widget']; 

		if ( !empty( $instance['title'] ) ) { 
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title']; 
		} 
		if ( !empty( $instance['text'] ) ) { 
			echo '<div class="lffs-sidebar-text">'.wpautop( wp_kses_post($instance['text']).'</div>');
		}

		$content = '[contact-widget ';
		if ( !empty( $instance['attributes'] ) ) { 
			$content .= $instance['attributes'];
		}
		$content .= ']';
		echo do_shortcode( $content );

		echo $args['after_widget']; 
	}
}
