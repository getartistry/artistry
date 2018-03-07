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
     * Initialize the plugin by loading the frontend
     *
     * @since 1.0.0
     */
    private function __construct()
    {
        $this->settings = gl_get_settings();
        
        if ( $this->settings['tooltip'] === 'on' ) {
            $this->settings['tooltip'] = 'link-tooltip';
            update_option( GT_SETTINGS . '-settings', $this->settings );
        }
        
        require_once plugin_dir_path( __FILE__ ) . '/includes/Glossary_Is_Methods.php';
        require_once plugin_dir_path( __FILE__ ) . '/includes/Glossary_Frontend.php';
        // The support for the a2x archive
        require_once plugin_dir_path( __FILE__ ) . '/includes/Glossary_a2z_Archive.php';
        // The tooltip system
        require_once plugin_dir_path( __FILE__ ) . '/includes/Glossary_Tooltip_Engine.php';
        require_once plugin_dir_path( __FILE__ ) . '/includes/Glossary_Search_Engine.php';
        require_once plugin_dir_path( __FILE__ ) . '/includes/Glossary_Genesis.php';
        require_once plugin_dir_path( __FILE__ ) . '/includes/Glossary_Yoast.php';
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