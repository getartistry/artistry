<?php
/**
 * WIDGET SECTION
 * ----------------------------------------------------------------------------
 */
// use widgets_init action hook to execute custom function
add_action( 'widgets_init', 'bos_searchbox_register_widgets' );
function bos_searchbox_register_widgets( ) {
                register_widget( 'bos_searchbox_widget' );
}
//Since WP version 4.3 use construct for widget
if ( version_compare( BOS_WP_VERSION, '4.3', '<' ) ) {
                class bos_searchbox_widget extends WP_Widget {
                                //process the new widget
                                function bos_searchbox_widget( ) {
                                                $widget_ops = array(
                                                                 'classname' => 'bos_searchbox_widget_class',
                                                                'description' => __( 'Display an accomodation search box', 'bookingcom-official-searchbox' ) 
                                                );
                                                $this->WP_Widget( 'bos_searchbox_widget', BOS_PLUGIN_NAME, $widget_ops );
                                }
                                // build widget settings form : this is only to display a save button on widget area. 
                                // This is needed for plugins ( i.e. "Fixed Widget" ) using the save button for extra-option
                                function form( $instance ) {
                                                echo '<p></p>';
                                }
                                //display the widget
                                function widget( $args, $instance ) {
                                                extract( $args );
                                                echo $before_widget;
                                                //retrieve all options stored in DB
                                                $options = bos_searchbox_retrieve_all_user_options();
                                                $preview = false; //This is the front-end searchbox
                                                bos_create_searchbox( $options, $preview );
                                                echo $after_widget;
                                }
                }
} //version_compare( get_bloginfo( 'version' ), '4.3', '<' )
else {
                class bos_searchbox_widget extends WP_Widget {
                                //process the new widget       
                                function __construct( ) {
                                                parent::__construct( 'bos_searchbox_widget_class', // Base ID
                                                                BOS_PLUGIN_NAME, // Name
                                                                array(
                                                                 'description' => __( 'Display an accomodation search box', 'bookingcom-official-searchbox' ),
                                                                'classname' => 'bos_searchbox_widget_class' 
                                                ) // Args
                                                                );
                                }
                                // build widget settings form : this is only to display a save button on widget area. 
                                // This is needed for plugins ( i.e. "Fixed Widget" ) using the save button for extra-option
                                function form( $instance ) {
                                                echo '<p></p>';
                                }
                                //display the widget
                                function widget( $args, $instance ) {
                                                extract( $args );
                                                echo $before_widget;
                                                //retrieve all options stored in DB
                                                $options = bos_searchbox_retrieve_all_user_options();
                                                $preview = false; //This is the front-end searchbox
                                                bos_create_searchbox( $options, $preview );
                                                echo $after_widget;
                                }
                }
}
?>