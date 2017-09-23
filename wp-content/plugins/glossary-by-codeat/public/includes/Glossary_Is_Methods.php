<?php

/**
 * Glossary_Is_Methods
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2017 GPL
 * @license   GPL-2.0+
 * @link      http://codeat.co
 */
/**
 * Support for the Genesis framework
 */
class Glossary_Is_Methods
{
    /**
     * Initialize the class with all the hooks
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->settings = get_option( GT_SETTINGS . '-settings' );
    }
    
    /**
     * Check the settings and if is a single page
     *
     * @return boolean
     */
    public function is_singular()
    {
        
        if ( isset( $this->settings['posttypes'] ) && is_singular( $this->settings['posttypes'] ) ) {
            if ( get_post_meta( get_queried_object_id(), GT_SETTINGS . '_disable', true ) === 'on' ) {
                return false;
            }
            return true;
        }
        
        return false;
    }
    
    /**
     * Check the settings and if is the home page
     *
     * @return boolean
     */
    public function is_home()
    {
        if ( isset( $this->settings['is'] ) && in_array( 'home', $this->settings['is'], true ) && is_home() ) {
            return true;
        }
        return false;
    }
    
    /**
     * Check the settings and if is a category page
     *
     * @return boolean
     */
    public function is_category()
    {
        if ( isset( $this->settings['is'] ) && in_array( 'category', $this->settings['is'], true ) && is_category() ) {
            return true;
        }
        return false;
    }
    
    /**
     * Check the settings and if is tag
     *
     * @return boolean
     */
    public function is_tag()
    {
        if ( isset( $this->settings['is'] ) && in_array( 'tag', $this->settings['is'], true ) && is_tag() ) {
            return true;
        }
        return false;
    }
    
    /**
     * Check the settings and if is an archive glossary
     *
     * @return boolean
     */
    public function is_arc_glossary()
    {
        if ( isset( $this->settings['is'] ) && in_array( 'arc_glossary', $this->settings['is'], true ) && is_post_type_archive( 'glossary' ) ) {
            return true;
        }
        return false;
    }
    
    /**
     * Check the settings and if is a tax glossary page
     *
     * @return boolean
     */
    public function is_tax_glossary()
    {
        if ( isset( $this->settings['is'] ) && in_array( 'tax_glossary', $this->settings['is'], true ) && is_tax( 'glossary-cat' ) ) {
            return true;
        }
        return false;
    }
    
    /**
     * Check the settings and if is a feed page
     *
     * @return boolean
     */
    public function is_feed()
    {
        return false;
    }
    
    /**
     * Check if it is Yoast link watcher
     *
     * @return boolean
     */
    public function is_yoast()
    {
        if ( is_admin() && defined( 'WPSEO_FILE' ) && get_the_ID() !== false ) {
            return true;
        }
        return false;
    }

}