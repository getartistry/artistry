<?php

/**
 * Glossary
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2016 GPL 2.0
 * @license   GPL-2.0
 * @link      http://codeat.co
 */
/**
 * A2Z widget
 */
class A2Z_Glossary_Widget extends WPH_Widget
{
    /**
     * Initialize the class
     */
    function __construct()
    {
        $args = array(
            'label'       => __( 'Glossary Alphabetical Index', GT_TEXTDOMAIN ),
            'description' => __( 'Alphabetical ordered list of Glossary terms', GT_TEXTDOMAIN ),
        );
        $args['fields'] = array( array(
            'name'     => __( 'Title', GT_TEXTDOMAIN ),
            'desc'     => __( 'Enter the widget title.', GT_TEXTDOMAIN ),
            'id'       => 'title',
            'type'     => 'text',
            'class'    => 'widefat',
            'validate' => 'alpha_dash',
            'filter'   => 'strip_tags|esc_attr',
        ), array(
            'name' => __( 'Show Counts', GT_TEXTDOMAIN ),
            'id'   => 'show_counts',
            'type' => 'checkbox',
        ) );
        $this->create_widget( $args );
    }
    
    /**
     * Main Glossary_a2z_Archive.
     *
     * Ensure only one instance of Glossary_a2z_Archive is loaded.
     *
     * @return Glossary_a2z_Archive - Main instance.
     */
    public static function get_instance()
    {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Output the widget
     *
     * @param array $args     Arguments.
     * @param array $instance Fields of the widget.
     *
     * @global object $wpdb Object.
     *
     * @return void
     */
    public function widget( $args, $instance )
    {
        $key = 'glossary-a2z-transient-' . get_locale() . '-' . md5( serialize( $instance ) );
        $html = get_transient( $key );
        $out = $args['before_widget'];
        $out .= '<div class="theme-' . $instance['theme'] . '">';
        $out .= $args['before_title'];
        $out .= $instance['title'];
        $out .= $args['after_title'];
        
        if ( $html === false || empty($html) ) {
            $count_pages = wp_count_posts( 'glossary' );
            if ( $count_pages->publish > 0 ) {
                $html = '<ul>' . implode( '', $this->generate_list( $instance ) ) . '</ul>';
            }
            set_transient( $key, $html, DAY_IN_SECONDS );
        }
        
        $out .= $html;
        $out .= '</div>' . $args['after_widget'];
        echo  $out ;
    }
    
    public function generate_list( $instance )
    {
        $pt_initials = gl_get_a2z_initial( array(
            'show_counts' => (bool) $instance['show_counts'],
        ) );
        $initial_arr = array();
        $base_url = get_post_type_archive_link( 'glossary' );
        
        if ( !$base_url ) {
            $base_url = esc_url( home_url( '/' ) );
            if ( get_option( 'show_on_front' ) === 'page' ) {
                $base_url = esc_url( get_permalink( get_option( 'page_for_posts' ) ) );
            }
        }
        
        foreach ( $pt_initials as $pt_rec ) {
            $link = add_query_arg( 'az', $pt_rec['initial'], $base_url );
            $item = '<li><a href="' . $link . '">' . $pt_rec['initial'] . '</a></li>';
            if ( (bool) $instance['show_counts'] ) {
                $item = '<li class="count"><a href="' . $link . '">' . $pt_rec['initial'] . ' <span>(' . $pt_rec['counts'] . ')</span></a></li>';
            }
            $initial_arr[] = $item;
        }
        return $initial_arr;
    }
    
    /**
     * After Validate Fields
     *
     * Allows to modify the output after validating the fields.
     *
     * @param array $instance Settings.
     *
     * @return array
     */
    public function after_validate_fields( $instance = '' )
    {
        $key = 'glossary-a2z-transient-' . get_locale() . '-' . md5( serialize( $instance ) );
        delete_transient( $key );
        return $instance;
    }

}
// Register widget

if ( !function_exists( 'glossary_a2z_register_widget' ) ) {
    /**
     * Register the widget
     *
     * @return void
     */
    function glossary_a2z_register_widget()
    {
        register_widget( 'A2Z_Glossary_Widget' );
    }
    
    add_action( 'widgets_init', 'glossary_a2z_register_widget', 1 );
}
