<?php
/**
 * Add Convertplug V2 Widget.
 *
 * @package convertpro
 */

if ( ! class_exists( 'Add_Convertplug_V2_Widget' ) ) {
	/**
	 * Class Add_Convertplug_V2_Widget.
	 */
	class Add_Convertplug_V2_Widget extends WP_Widget {

		/**
		 * Constructor
		 */
		public function __construct() {
			parent::__construct(
				'convertplug_V2_widget', sprintf( esc_attr( '%s Widget', 'convertpro' ), CPRO_BRANDING_NAME ),
				array(
					'classname'   => 'convertplug_V2_widget',
					'description' => __( 'A widget box displays an opt-in form inline as a part of your sidebar area.', 'convertpro' ),
				)
			);
		}

		/**
		 * Function Name: widget.
		 * Function Description: widget.
		 *
		 * @param string $args string parameter.
		 * @param string $instance string parameter.
		 */
		public function widget( $args, $instance ) {

			if ( $instance ) {
				$style_id = ( isset( $instance['style_id'] ) ) ? (int) $instance['style_id'] : -1;

				$display = cp_v2_is_style_visible( $style_id );

				if ( $style_id > 0 && $display ) {

					cp_v2_enqueue_google_fonts( $style_id );

					echo do_shortcode( '[cp_popup display="inline" style_id="' . $style_id . '" step_id = 1][/cp_popup]' );
				}
			}
		}

		/**
		 * Function Name: form.
		 * Function Description: form.
		 *
		 * @param string $instance string parameter.
		 */
		public function form( $instance ) {

			$defaults = array(
				'style_id' => -1,
			);

			if ( $instance ) {
				$defaults['style_id'] = isset( $instance['style_id'] ) && '' != $instance['style_id'] ? esc_attr( $instance['style_id'] ) : '';
			}

			$obj = new CP_V2_Popups();

			if ( $obj ) {

				$list = $obj->get( 'widget' );

				if ( ! empty( $list ) ) { ?>
					<p><label for="<?php echo $this->get_field_id( 'style_id' ); ?>"><?php _e( 'Select Style', 'convertpro' ); ?></label></p>
					<p>
						<select class="cp-v2-widget-select" id="<?php echo $this->get_field_id( 'style_id' ); ?>" name="<?php echo $this->get_field_name( 'style_id' ); ?>">
							<option value="-1"><?php _e( '--- Widgets ---', 'convertpro' ); ?></option>
							<?php
							foreach ( $list as $key => $l ) {
								$selected     = ( $l == $defaults['style_id'] ) ? 'selected="selected"' : '';
								$widget_title = get_the_title( $l );
								echo '<option value="' . esc_attr( $l ) . '" ' . $selected . '>' . sanitize_text_field( $widget_title ) . '</option>';
							}
							?>
						</select>
					</p>
				<?php
				} else {
					sprintf( esc_attr( '<p>No widgets added yet. Please add widgets from %s -> Create New -> Widget</p>', 'convertpro' ), CPRO_BRANDING_NAME );
				}
			} else {
				sprintf( esc_attr( '<p>No widgets added yet. Please add widgets from %s -> Create New -> Widget</p>', 'convertpro' ), CPRO_BRANDING_NAME );
			}
		}

		/**
		 * Function Name: update.
		 * Function Description: Updating widget replacing old instances with new.
		 *
		 * @param string $new_instance string parameter.
		 * @param string $old_instance string parameter.
		 */
		public function update( $new_instance, $old_instance ) {
			// processes widget options on save.
			$instance             = $old_instance;
			$instance['style_id'] = (int) ( $new_instance['style_id'] );
			return $instance;
		}
	}
}

// Register and load the widget.
if ( ! function_exists( 'load_convertplug_v2_widget' ) ) {
	/**
	 * Function Name: load_convertplug_v2_widget.
	 * Function Description: Load Convertplug V2 Widget.
	 */
	function load_convertplug_v2_widget() {
		register_widget( 'Add_Convertplug_V2_Widget' );
	}
}
