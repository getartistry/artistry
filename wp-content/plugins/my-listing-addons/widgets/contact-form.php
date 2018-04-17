<?php
/*
 * Contact Form Widget.
 */

class CASE27_Widgets_Contact_Form extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            false,
            esc_html__( '27 > Contact Form', 'my-listing' )
        );
    }

    /**
     * Front-end display of widget.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        if (isset($instance['contact_form_id'])) :
            $contact_form_id = $instance['contact_form_id'];
            ?>
            <div class="contactForm">
                <?php echo do_shortcode("[contact-form-7 id=\"{$contact_form_id}\"]") ?>
            </div>
            <?php
        endif;

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'my-listing' );
        $contact_form_id = ! empty( $instance['contact_form_id'] ) ? $instance['contact_form_id'] : '';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
                <?php esc_attr_e( 'Title:', 'my-listing' ); ?>
            </label>
            <input
                class="widefat"
                id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                type="text"
                value="<?php echo esc_attr( $title ); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'contact_form_id' ) ); ?>">
                <?php esc_attr_e( 'Contact Form ID:', 'my-listing' ); ?>
            </label>
            <input
                class="widefat"
                id="<?php echo esc_attr( $this->get_field_id( 'contact_form_id' ) ); ?>"
                name="<?php echo esc_attr( $this->get_field_name( 'contact_form_id' ) ); ?>"
                type="text"
                value="<?php echo esc_attr( $contact_form_id ); ?>">
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['contact_form_id'] = ( ! empty( $new_instance['contact_form_id'] ) ) ? strip_tags( $new_instance['contact_form_id'] ) : '';

        return $instance;
    }
}

register_widget('CASE27_Widgets_Contact_Form');