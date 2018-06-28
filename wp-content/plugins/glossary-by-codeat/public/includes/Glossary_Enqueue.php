<?php

/**
 * The Frontend Enqueue code
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2016 GPL 2.0+
 * @license   GPL-2.0+
 * @link      http://codeat.co
 */
/**
 * Enqueue stuff the right way
 */
class Glossary_Enqueue
{
    /**
     * Initialize the class
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->settings = gl_get_settings();
        // Add the url of the themes in the plugin
        add_filter( 'glossary_themes_url', array( $this, 'add_glossary_url' ) );
        if ( isset( $this->settings['tooltip'] ) ) {
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 9999 );
        }
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
        $public_folder = dirname( __FILE__ );
        $themes['classic'] = plugins_url( 'assets/css/tooltip-classic.css', $public_folder );
        $themes['box'] = plugins_url( 'assets/css/tooltip-box.css', $public_folder );
        $themes['line'] = plugins_url( 'assets/css/tooltip-line.css', $public_folder );
        return $themes;
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
        /**
         * Array with all the url of themes
         *
         * @since 1.2.0
         *
         * @param array $urls The list.
         *
         * @return array $urls The list filtered.
         */
        $url_themes = apply_filters( 'glossary_themes_url', array() );
        $custom_css = get_option( GT_SETTINGS . '-customizer' );
        wp_enqueue_style(
            GT_SETTINGS . '-hint',
            $url_themes[$this->settings['tooltip_style']],
            array(),
            GT_VERSION
        );
        $public_folder = dirname( __FILE__ );
    }

}
new Glossary_Enqueue();