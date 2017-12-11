<?php
/**
 * Class Name : SIB_Widget_Subscribe
 * Feature: Add widget for subscribe form
 *
 * @package SIB_Widget_Subscribe
 */

/**
 * Class SIB_Widget_Subscribe
 */
class SIB_Widget_Subscribe extends WP_Widget {
	/**
	 * SIB_Widget_Subscribe constructor.
	 */
	function __construct() {
		parent::__construct(
			'sib_subscribe_form', 'SendinBlue Widget',
			array(
				'description' =>
					'Display SendinBlue Widget',
			)
		);
	}

	/**
	 * Function Name : form
	 *
	 * @param array $instance - instance.
	 * @return string|void
	 */
	function form( $instance ) {
		// Retrieve previous values from instance
		// or set default values if not present.
		if ( isset( $instance['widget_title'] ) && '' !== $instance['widget_title'] ) {
			$widget_title = esc_attr( $instance['widget_title'] );
		} else {
			$widget_title = __( 'SendinBlue Newsletter', 'sib_lang' );
		}
		if ( isset( $instance['sib_form_list'] ) ) {
			$sib_form_list = esc_attr( $instance['sib_form_list'] );
		} else {
			$sib_form_list = '1';
		}

		$sib_forms = SIB_Forms::getForms();
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'widget_title' ) ); ?>">
				<?php echo esc_attr_e( 'Widget Title', 'sib_lang' ) . ':'; ?>
			</label>
			 <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'widget_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'widget_title' ) ); ?>" value="<?php echo esc_attr( $widget_title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'sib_form_list' ) ); ?>">
				<?php echo esc_attr_e( 'Form to use', 'sib_lang' ) . ':'; ?>
			</label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'sib_form_list' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'sib_form_list' ) ); ?>">
			<?php
			foreach ( $sib_forms as $form ) {
				?>
				<option value="<?php echo esc_attr( $form['id'] ); ?>" <?php selected( $sib_form_list, $form['id'] ); ?>><?php echo esc_attr( $form['title'] ); ?></option>
				<?php
			}
			?>
			</select>
		</p>
		<?php
	}

	/**
	 * Function name: update
	 *
	 * @param array $new_instance - new instance.
	 * @param array $old_instance - old instance.
	 * @return array
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['widget_title'] =
			strip_tags( $new_instance['widget_title'] );

		$instance['sib_form_list'] =
			strip_tags( $new_instance['sib_form_list'] );

		return $instance;
	}

	/**
	 * Function Name : widget
	 *
	 * @param array $args - arguments.
	 * @param array $instance - instance.
	 */
	function widget( $args, $instance ) {

		// Extract members of args array as individual variables.
		extract( $args );
		$widget_title = ( ! empty( $instance['widget_title'] ) ?
			esc_attr( $instance['widget_title'] ) :
			'SendinBlue Newsletter' );
		// Display widget title.
		echo $before_widget ;
		echo $before_title ;
		echo apply_filters( 'widget_title', $widget_title );
		echo $after_title ;
		$frmID = isset( $instance['sib_form_list'] ) ? $instance['sib_form_list'] : 'oldForm';
		$lang = defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : '';
		SIB_Manager::$instance->generate_form_box( $frmID, $lang );
		echo $after_widget ;
	}
}
