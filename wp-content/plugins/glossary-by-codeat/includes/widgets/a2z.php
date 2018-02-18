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
class a2z_Glossary_Widget extends WPH_Widget
{
    /**
     * Initialize the class
     */
    function __construct()
    {
        $args = array(
            'label'       => __( 'Alphabet taxonomies for glossary terms', GT_TEXTDOMAIN ),
            'description' => __( 'List of alphabet taxonomies for glossary terms', GT_TEXTDOMAIN ),
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
    function widget( $args, $instance )
    {
        $key = 'glossary-a2z-transient-' . get_locale() . '-' . $instance['theme'];
        $html = get_transient( $key );
        
        if ( $html === false || empty($html) ) {
            $out = $args['before_widget'];
            $out .= '<div class="theme-' . $instance['theme'] . '">';
            $out .= $args['before_title'];
            $out .= $instance['title'];
            $out .= $args['after_title'];
            global  $wpdb ;
            $count_pages = wp_count_posts( 'glossary' );
            
            if ( $count_pages->publish > 0 ) {
                $count_col = '';
                if ( (bool) $instance['show_counts'] ) {
                    $count_col = ", count( substring( TRIM( LEADING 'A ' FROM TRIM( LEADING 'AN ' FROM TRIM( LEADING 'THE ' FROM UPPER( {$wpdb->posts}.post_title ) ) ) ), 1, 1) ) as counts";
                }
                $querystr = "SELECT DISTINCT substring( TRIM( LEADING 'A ' FROM TRIM( LEADING 'AN ' FROM TRIM( LEADING 'THE ' FROM UPPER( {$wpdb->posts}.post_title ) ) ) ), 1, 1) as initial" . $count_col . " FROM {$wpdb->posts} WHERE {$wpdb->posts}.post_status = 'publish' AND {$wpdb->posts}.post_type = 'glossary' GROUP BY initial ORDER BY TRIM( LEADING 'A ' FROM TRIM( LEADING 'AN ' FROM TRIM( LEADING 'THE ' FROM UPPER( {$wpdb->posts}.post_title ) ) ) );";
                $pt_initials = $wpdb->get_results( $querystr, ARRAY_A );
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
                $out .= '<ul>' . implode( '', $initial_arr ) . '</ul>';
            }
            
            $out .= '</div>' . $args['after_widget'];
            set_transient( $key, $out, DAY_IN_SECONDS );
            $html = $out;
        }
        
        echo  $html ;
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
        register_widget( 'a2z_Glossary_Widget' );
    }
    
    add_action( 'widgets_init', 'glossary_a2z_register_widget', 1 );
}
