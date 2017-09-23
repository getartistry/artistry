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
            'taxonomies'      => array( 'glossary-cat' ),
            'map_meta_cap'    => false,
            'yarpp_support'   => true,
            'menu_icon'       => 'dashicons-book-alt',
            'capability_type' => array( 'glossary', 'glossaries' ),
            'supports'        => array(
            'thumbnail',
            'editor',
            'title',
            'genesis-seo',
            'genesis-layouts',
            'genesis-cpt-archive-settings',
            'revisions'
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
            'manage_terms' => 'manage_glossaries',
            'edit_terms'   => 'manage_glossaries',
            'delete_terms' => 'manage_glossaries',
            'assign_terms' => 'read_glossary',
        ),
        );
        if ( !empty($this->settings['slug-cat']) ) {
            $glossary_term_tax['rewrite']['slug'] = $this->settings['slug-cat'];
        }
        register_via_taxonomy_core( array( __( 'Term Category', GT_TEXTDOMAIN ), __( 'Terms Categories', GT_TEXTDOMAIN ), 'glossary-cat' ), $glossary_term_tax, array( 'glossary' ) );
        require_once plugin_dir_path( __FILE__ ) . '/includes/Glossary_Is_Methods.php';
        require_once plugin_dir_path( __FILE__ ) . '/includes/Glossary_Frontend.php';
        // The support for the a2x archive
        require_once plugin_dir_path( __FILE__ ) . '/includes/Glossary_a2z_Archive.php';
        // The tooltip system
        require_once plugin_dir_path( __FILE__ ) . '/includes/Glossary_Tooltip_Engine.php';
        require_once plugin_dir_path( __FILE__ ) . '/includes/Glossary_Search_Engine.php';
        require_once plugin_dir_path( __FILE__ ) . '/includes/Glossary_Genesis.php';
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

}