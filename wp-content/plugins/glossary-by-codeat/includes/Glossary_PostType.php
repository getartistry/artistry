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
 * Initialize the post type.
 */
class Glossary_PostType
{
    /**
     * Initialize the plugin by setting localization and loading public scripts
     * and styles.
     *
     * @since 1.0.0
     */
    public function initialize()
    {
        $this->settings = gl_get_settings();
        $this->register_post_type();
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
    }
    
    public function register_post_type()
    {
        $this->settings = gl_get_settings();
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
        $single = __( 'Glossary Term', GT_TEXTDOMAIN );
        $multi = __( 'Glossary', GT_TEXTDOMAIN );
        register_via_cpt_core( array( $single, $multi, 'glossary' ), $glossary_term_cpt );
    }

}
$glossary_posttype = new Glossary_PostType();
$glossary_posttype->initialize();