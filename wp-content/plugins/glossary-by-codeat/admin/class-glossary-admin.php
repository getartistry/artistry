<?php

/**
 * Glossary
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2016 GPL 2.0+
 * @license   GPL-2.0+
 * @link      http://codeat.co
 */
/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 */
class Glossary_Admin
{
    /**
     * Instance of this class.
     *
     * @var object
     */
    protected static  $instance = null ;
    /**
     * Initialize the plugin by loading admin scripts & styles and adding a
     * settings page and menu.
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function __construct()
    {
        $plugin = Glossary::get_instance();
        $this->cpts = $plugin->get_cpts();
        // At Glance Dashboard widget for your cpts
        add_filter(
            'dashboard_glance_items',
            array( $this, 'cpt_glance_dashboard_support' ),
            10,
            1
        );
        // Activity Dashboard widget for your cpts
        add_filter(
            'dashboard_recent_posts_query_args',
            array( $this, 'cpt_activity_dashboard_support' ),
            10,
            1
        );
        // Add an action link pointing to the options page.
        $plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . GT_SETTINGS . '.php' );
        add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
        /*
         * Enqueue files on admin
         */
        require_once plugin_dir_path( __FILE__ ) . 'includes/Glossary_Admin_Enqueue.php';
        /*
         * Import Export settings
         */
        require_once plugin_dir_path( __FILE__ ) . 'includes/Glossary_ImpExp.php';
        /**
         * CMB support
         */
        require_once plugin_dir_path( __FILE__ ) . 'includes/Glossary_CMB.php';
        require_once plugin_dir_path( __FILE__ ) . 'includes/WP_Admin_Notice.php';
        require_once plugin_dir_path( __FILE__ ) . 'includes/i18n-module/i18n-v3.php';
        require_once plugin_dir_path( __FILE__ ) . 'includes/i18n-module/i18n-wordpressorg-v3.php';
        new Yoast_I18n_WordPressOrg_V3( array(
            'textdomain'  => GT_TEXTDOMAIN,
            'plugin_name' => GT_NAME,
            'hook'        => 'admin_notices',
        ), true );
    }
    
    /**
     * Return an instance of this class.
     *
     * @since 1.0.0
     *
     * @return object A single instance of this class.
     */
    public static function get_instance()
    {
        // If the single instance hasn't been set, set it now.
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Render the settings page for this plugin.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public static function display_plugin_admin_page()
    {
        include_once 'views/admin.php';
    }
    
    /**
     * Add settings action link to the plugins page.
     *
     * @param array $links The list of links.
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function add_action_links( $links )
    {
        return array_merge( array(
            'settings' => '<a href="' . admin_url( 'edit.php?post_type=glossary&page=' . GT_SETTINGS ) . '">' . __( 'Settings', GT_TEXTDOMAIN ) . '</a>',
        ), $links );
    }
    
    /**
     * Add the counter of your CPTs in At Glance widget in the dashboard
     *        Reference:  http://wpsnipp.com/index.php/functions-php/wordpress-post-types-dashboard-at-glance-widget/
     *
     * @param array $items The list of post types.
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function cpt_glance_dashboard_support( $items = array() )
    {
        $post_types = $this->cpts;
        foreach ( $post_types as $type ) {
            if ( !post_type_exists( $type ) ) {
                continue;
            }
            $num_posts = wp_count_posts( $type );
            
            if ( $num_posts ) {
                $published = intval( $num_posts->publish );
                $post_type = get_post_type_object( $type );
                // Translators: Used to show the numbers for the post type in the dashboard
                $text = _n(
                    '%s ' . $post_type->labels->singular_name,
                    '%s ' . $post_type->labels->name,
                    $published,
                    GT_TEXTDOMAIN
                );
                $text = sprintf( $text, number_format_i18n( $published ) );
                $temp = sprintf( '%2$s', $type, $text ) . "\n";
                if ( current_user_can( $post_type->cap->edit_posts ) ) {
                    $temp = '<a class="' . $post_type->name . '-count" href="edit.php?post_type=' . $post_type->name . '">' . sprintf( '%2$s', $type, $text ) . "</a>\n";
                }
                $items[] = $temp;
            }
            
            return $items;
        }
    }
    
    /**
     * Add the recents post type in the activity widget
     *
     * @param array $query_args All the parameters.
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function cpt_activity_dashboard_support( $query_args )
    {
        if ( !is_array( $query_args['post_type'] ) ) {
            // Set default post type
            $query_args['post_type'] = array( 'page' );
        }
        $query_args['post_type'] = array_merge( $query_args['post_type'], $this->cpts );
        return $query_args;
    }

}