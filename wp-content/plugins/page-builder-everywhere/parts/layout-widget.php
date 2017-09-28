<?php
class Divi_Space_PB_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct('divi_pb_widget', 'Divi PB Widget', array( 'description' => __('Add any Divi Library item as a widget.') ));
	}

	// Front End Display
	public function widget( $args, $instance ) {
		$id = ($instance['page-id']) ? $instance['page-id'] : 0;

		$display_library_item_title = (isset($instance['display_library_item_title'])) ? $instance['display_library_item_title'] : 1;
		$post = get_post( $id );

		echo $args['before_widget'];

		if( ! empty( $id ) && $post ) {

			$content = $post->post_content;

			if( $display_library_item_title ) {
				$title = $post->post_title;
				$title = apply_filters( 'widget_title', $title );
				echo $args['before_title'] . $title . $args['after_title'];
			}

			echo do_shortcode($content); //Convert shortcode to proper output

		} elseif( current_user_can( 'manage_options' ) ) { ?>
				<p>
					<?php if( empty( $id ) ) {
						_e( 'Please add a Divi Library item to your Divi PB Widget' );
					} ?>
				</p>
		<?php 
		}

		echo $args['after_widget'];
		
	}

// Sanitize form values 

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['page-id'] = $new_instance['page-id'];
		$instance['display_library_item_title'] = ( isset($new_instance['display_library_item_title'] ) && $new_instance['display_library_item_title'] == 1 ) ? 1 : 0;
		return $instance;
	}

// Widget Fields

	public function form( $instance ) {
		
		$posts = (array) get_posts(array(
			'post_type' => 'et_pb_layout', //Find Library Items
			'numberposts' => -1
		));

		$display_library_item_title = ( isset( $instance['display_library_item_title'] ) ) ? $instance['display_library_item_title'] : 1;
		$selected_widget_id = ( isset( $instance['page-id'] ) ) ? $instance['page-id'] : 0;
		$title = ($selected_widget_id) ? get_the_title( $selected_widget_id ) : 'No Library Item Selected';
		?>

		<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="hidden" value="<?php echo esc_attr( $title ); ?>" />

		<p class="divi-pb-widget">	
			<label for="<?php echo $this->get_field_id( 'page-id' ); ?>"><?php _e( 'Which Library Item would you like to display?' ); ?></label> 
			<select id="<?php echo $this->get_field_id('page-id'); ?>" name="<?php echo $this->get_field_name( 'page-id' ); ?>" required>
				<option value="0" disabled <?php selected( $selected_widget_id, 0 ); ?>>
					<?php if( empty( $posts ) ) {
						_e( 'No Library Items' );
					} else {
						_e( 'Choose a Library Item' );
					} ?>
				</option>
				<?php foreach( $posts as $p ) { ?>
					<option value="<?php echo $p->ID; ?>" <?php selected( $selected_widget_id, $p->ID ); ?>><?php echo $p->post_title; ?></option>
				<?php } ?>
			</select>
		</p>

		<p class="divi-pb-widget">
			<label><input type="checkbox" id="<?php echo $this->get_field_id( 'display_library_item_title' ); ?>" name="<?php echo $this->get_field_name( 'display_library_item_title' ); ?>" value="1" <?php checked( $display_library_item_title, 1 ); ?> /> <?php _e( "Display Library Item Title?" ); ?></label>
		</p>

		<p class="help"><?php printf( __( 'Add Library Items to be used as widgets %shere%s' ), '<a href="'. admin_url( 'edit.php?post_type=et_pb_layout' ) .'">', '</a>' ); ?></p>
		<?php
	}
}
?>