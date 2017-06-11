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
 * public-facing side of the WordPress site.
 */
class Glossary
{
    /**
     * Instance of this class.
     *
     * @var object
     */
    protected static  $instance = null ;
    /**
     * Array of cpts of the plugin
     *
     * @var object
     */
    protected  $cpts = array( 'glossary' ) ;
    /**
     * Array of settings
     *
     * @var object
     */
    protected  $settings = null ;
    /**
     * Initialize the plugin by setting localization and loading public scripts
     * and styles.
     *
     * @since 1.0.0
     */
    private function __construct()
    {
        $this->settings = get_option( GT_SETTINGS . '-settings' );
        
        if ( $this->settings['tooltip'] === 'on' ) {
            $this->settings['tooltip'] = 'link-tooltip';
            update_option( GT_SETTINGS . '-settings', $this->settings );
        }
        
        // Create the args for the `glossary` post type
        $glossary_term_cpt = array(
            'taxonomies'    => array( 'glossary-cat' ),
            'map_meta_cap'  => true,
            'yarpp_support' => true,
            'menu_icon'     => 'dashicons-book-alt',
            'supports'      => array(
            'thumbnail',
            'editor',
            'title',
            'genesis-seo',
            'genesis-layouts',
            'genesis-cpt-archive-settings'
        ),
        );
        if ( !empty($this->settings['slug']) ) {
            $glossary_term_cpt['rewrite']['slug'] = $this->settings['slug'];
        }
        if ( isset( $this->settings['archive'] ) ) {
            $glossary_term_cpt['has_archive'] = false;
        }
        register_via_cpt_core( array( __( 'Glossary Term', GT_TEXTDOMAIN ), __( 'Glossary Terms', GT_TEXTDOMAIN ), 'glossary' ), $glossary_term_cpt );
        // Create the args for the `glossary-cat` taxonomy
        $glossary_term_tax = array(
            'public'       => true,
            'capabilities' => array(
            'assign_terms' => 'edit_posts',
        ),
        );
        if ( !empty($this->settings['slug-cat']) ) {
            $glossary_term_tax['rewrite']['slug'] = $this->settings['slug-cat'];
        }
        register_via_taxonomy_core( array( __( 'Term Category', GT_TEXTDOMAIN ), __( 'Terms Categories', GT_TEXTDOMAIN ), 'glossary-cat' ), $glossary_term_tax, array( 'glossary' ) );
        // The support for glossary in the search system
        if ( isset( $this->settings['search'] ) ) {
            add_filter( 'pre_get_posts', array( $this, 'filter_search' ) );
        }
        // Add the url of the themes in the plugin
        add_filter( 'glossary_themes_url', array( $this, 'add_glossary_url' ) );
        require_once plugin_dir_path( __FILE__ ) . '/includes/Glossary_Is_Methods.php';
        // The support for the a2x archive
        require_once plugin_dir_path( __FILE__ ) . '/includes/Glossary_a2z_Archive.php';
        // The tooltip system
        require_once plugin_dir_path( __FILE__ ) . '/includes/Glossary_Tooltip_Engine.php';
        require_once plugin_dir_path( __FILE__ ) . '/includes/Glossary_Search_Engine.php';
        require_once plugin_dir_path( __FILE__ ) . '/includes/Glossary_Genesis.php';
        if ( isset( $this->settings['tooltip'] ) ) {
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 9999 );
        }
        if ( isset( $this->settings['order_terms'] ) ) {
            add_action( 'pre_get_posts', array( $this, 'order_glossary' ), 9999 );
        }
        if ( isset( $this->settings['tax_archive'] ) ) {
            add_action( 'pre_get_posts', array( $this, 'hide_taxonomy_frontend' ) );
        }
    }
    
    /**
     * Return the cpts
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function get_cpts()
    {
        return $this->cpts;
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
     * Add support for custom CPT on the search box
     *
     * @param object $query The query.
     *
     * @since 1.0.0
     * 
     * @return object The query with changes.
     */
    public function filter_search( $query )
    {
        
        if ( $query->is_search && !is_admin() ) {
            $post_types = $query->get( 'post_type' );
            
            if ( $post_types === 'post' ) {
                $post_types = array();
                $query->set( 'post_type', array_push( $post_types, $this->cpts ) );
            }
        
        }
        
        return $query;
    }
    
    /**
     * Order the glossary terms alphabetically
     *
     * @param object $query The query.
     *
     * @since 1.0.0
     * 
     * @return object The query with changes.
     */
    public function order_glossary( $query )
    {
        if ( is_admin() ) {
            return $query;
        }
        
        if ( ($query->is_tax( 'glossary-cat' ) || $query->is_post_type_archive( 'glossary' )) && $query->is_main_query() ) {
            $query->set( 'orderby', 'title' );
            $query->set( 'order', 'ASC' );
        }
    
    }
    
    /**
     * Register and enqueue public-facing style sheet.
     *
     * @since 1.0.0
     * 
     * @return void
     */
    public function enqueue_styles()
    {
        /*
         * Array with all the url of themes
         * 
         * @since 1.2.0
         * 
         * @param array $urls The list.
         * 
         * @return array $urls The list filtered.
         */
        $url_themes = apply_filters( 'glossary_themes_url', array() );
        wp_enqueue_style(
            GT_SETTINGS . '-hint',
            $url_themes[$this->settings['tooltip_style']],
            array(),
            GT_VERSION
        );
    }
    
    /**
     * Add the path to the themes
     * 
     * @param array $themes List of themes.
     * 
     * @return array
     */
    public function add_glossary_url( $themes )
    {
        $themes['classic'] = plugins_url( 'assets/css/tooltip-classic.css', __FILE__ );
        $themes['box'] = plugins_url( 'assets/css/tooltip-box.css', __FILE__ );
        $themes['line'] = plugins_url( 'assets/css/tooltip-line.css', __FILE__ );
        return $themes;
    }
    
    /**
     * Hide the taxonomy on the frontend
     * 
     * @param object $query The query.
     * 
     * @return void
     */
    public function hide_taxonomy_frontend( $query )
    {
        if ( is_admin() ) {
            return;
        }
        if ( is_tax( 'glossary-cat' ) ) {
            $query->set_404();
        }
    }
    
    /**
     * Hide terms from taxonomy if content is empty
     * 
     * @param string $where The SQL query.
     * 
     * @return string
     */
    public function hide_empty_terms( $where )
    {
        if ( is_archive() ) {
            $where .= ' AND trim(coalesce(post_content, \'\')) <>\'\'';
        }
        return $where;
    }

}