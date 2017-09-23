<?php

/**
 * The Frontend code
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2016 GPL 2.0+
 * @license   GPL-2.0+
 * @link      http://codeat.co
 */
/**
 * Provide support for Frontend stuff
 */
class Glossary_Frontend
{
    /**
     * Initialize the class
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->settings = get_option( GT_SETTINGS . '-settings' );
        // Add the url of the themes in the plugin
        add_filter( 'glossary_themes_url', array( $this, 'add_glossary_url' ) );
        if ( isset( $this->settings['tooltip'] ) ) {
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 9999 );
        }
        if ( isset( $this->settings['order_terms'] ) ) {
            add_action( 'pre_get_posts', array( $this, 'order_glossary' ), 9999 );
        }
        if ( isset( $this->settings['tax_archive'] ) ) {
            add_action( 'pre_get_posts', array( $this, 'hide_taxonomy_frontend' ) );
        }
        // The support for glossary in the search system
        if ( isset( $this->settings['search'] ) ) {
            add_filter( 'pre_get_posts', array( $this, 'filter_search' ) );
        }

        if ( isset( $this->settings['remove_archive_label'] ) ) {
            add_filter( 'get_the_archive_title', array( $this, 'remove_archive_label' ) );
            add_filter( 'pre_get_document_title', array( $this, 'remove_archive_label' ), 99999 );
        }

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

    /**
     * Cleanup the Archive/Tax page from terms
     *
     * @param string $title The archive title.
     *
     * @return string
     */
    public function remove_archive_label( $title )
    {
        $object = get_queried_object();
        $glossary = Glossary::get_instance();

        if ( isset( $object->taxonomy ) ) {
            $tax = get_queried_object()->taxonomy;
            if ( $tax === 'glossary-cat' ) {
                $title = single_term_title( '', false );
            }
        }

        if ( isset( $object->name ) ) {

			$cpts = $glossary->get_cpts();
			if ( $object->name === $cpts[ 0 ] ) {
                $title = str_replace( __( 'Archives' ) . ':', '', $title );
                $title = str_replace( __( 'Archives' ), '', $title );
            }

        }
        if ( empty($title) ) {
            $title = post_type_archive_title( '', false );
        }
        return trim( $title );
    }

}
new Glossary_Frontend();
