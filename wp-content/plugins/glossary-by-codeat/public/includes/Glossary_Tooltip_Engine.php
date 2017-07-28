<?php

/**
 * Glossary_Tooltip_Engine
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2015 GPL
 * @license   GPL-2.0+
 * @link      http://codeat.co
 */
/**
 * Engine system that add the tooltips
 */
class Glossary_Tooltip_Engine
{
    /**
     * Instance of this class.
     *
     * @var object
     */
    protected static  $instance = null ;
    /**
     * Initialize the class with all the hooks
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->settings = get_option( GT_SETTINGS . '-settings' );
        add_filter(
            'posts_orderby',
            array( $this, 'orderby_whitespace' ),
            9999,
            2
        );
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
     * Change the orderby for the glossary auto link system to add priority based on number of the spaces
     *
     * @param string $orderby How to oder the query.
     * @param object $object  The object.
     *
     * @global object $wpdb
     *
     * @return string
     */
    public function orderby_whitespace( $orderby, $object )
    {
        
        if ( isset( $object->query['glossary_auto_link'] ) ) {
            global  $wpdb ;
            $orderby = '(LENGTH(' . $wpdb->prefix . 'posts.post_title) - LENGTH(REPLACE(' . $wpdb->prefix . 'posts.post_title, \' \', \'\'))+1) DESC';
        }
        
        return $orderby;
    }
    
    /**
     * Get the excerpt by our limit
     *
     * @param object  $ID           The ID.
     * @param boolean $wantreadmore This link it's internal?.
     * @param boolean $strip        Strip HTML.
     *
     * @return string
     */
    public function get_the_excerpt( $ID, $wantreadmore = false, $strip = false )
    {
        $readmore = '';
        
        if ( is_numeric( $ID ) ) {
            $term = get_post( $ID );
            $excerpt = $term->post_excerpt;
            if ( empty($excerpt) ) {
                $excerpt = $term->post_content;
            }
            if ( $strip ) {
                $excerpt = wp_strip_all_tags( $excerpt );
            }
            /**
             * Filter the excerpt before printing
             *
             * @param string $excerpt The excerpt.
             * @param string $ID      The ID.
             *
             * @since 1.2.0
             *
             * @return string $excerpt The excerpt filtered.
             */
            $excerpt = apply_filters( 'glossary_excerpt', $excerpt, $ID );
        } else {
            $excerpt = $ID;
        }
        
        if ( $wantreadmore ) {
            $readmore = ' <a href="' . get_the_permalink( $ID ) . '">' . __( 'More' ) . '</a>';
        }
        // Strip the excerpt based on the words or char limit
        
        if ( isset( $this->settings['excerpt_words'] ) && $this->settings['excerpt_words'] ) {
            $char_limit = absint( $this->settings['excerpt_limit'] );
            if ( strlen( $excerpt ) >= $char_limit ) {
                $excerpt = wp_trim_words( $excerpt, $char_limit, '' ) . '...' . $readmore;
            }
        } else {
            if ( strlen( $excerpt ) >= absint( $this->settings['excerpt_limit'] ) ) {
                $excerpt = substr( $excerpt, 0, absint( $this->settings['excerpt_limit'] ) ) . '...' . $readmore;
            }
        }
        
        return trim( $excerpt );
    }
    
    /**
     * Add a tooltip for your terms
     *
     * @param string $html_link    The HTML link.
     * @param object $ID           The ID.
     * @param string $wantreadmore It is internal the link?.
     * @param string $link         The link itself.
     *
     * @return string
     */
    public function tooltip_html(
        $html_link,
        $ID,
        $wantreadmore,
        $link
    )
    {
        $class = 'glossary-link';
        $media = '';
        $excerpt = $this->get_the_excerpt( $ID, $wantreadmore, true );
        if ( !empty($this->settings['external_icon']) ) {
            if ( strpos( $link, get_site_url() ) !== 0 ) {
                $class .= ' glossary-external-link';
            }
        }
        $tooltip = '<span class="glossary-tooltip' . $media . '">' . '<span class="' . $class . '">' . $html_link . '</span>';
        
        if ( empty($media) ) {
            $tooltip .= '<span class="glossary-tooltip-content clearfix">';
        } else {
            $tooltip .= '<span class="glossary-video clearfix">';
        }
        
        $theme = get_option( GT_SETTINGS . '-settings' );
        $photo = '';
        
        if ( $theme['tooltip_style'] !== 'box' && $theme['tooltip_style'] !== 'line' ) {
            $photo = get_the_post_thumbnail( $ID, 'thumbnail' );
            if ( !empty($photo) && !empty($this->settings['t_image']) ) {
                $tooltip .= $photo;
            }
        }
        
        $tooltip .= '<span class="glossary-tooltip-text">' . $excerpt . '</span>' . '</span>' . '</span>';
        /**
         * Filter the HTML generated
         *
         * @param string $tooltip The tooltip.
         * @param string $title   The title of the term.
         * @param string $excerpt The excerpt.
         * @param string $photo   Photo.
         * @param string $post    The post object.
         * @param string $wantreadmore The internal html link.
         *
         * @since 1.2.0
         *
         * @return string $html The tooltip filtered.
         */
        return apply_filters(
            'glossary_tooltip_html',
            $tooltip,
            $excerpt,
            $photo,
            $ID,
            $wantreadmore
        );
    }
    
    /**
     * Generate a link or the tooltip
     *
     * @param string $atts Parameters.
     *
     * @global object $post The post object.
     *
     * @return string
     */
    public function link_or_tooltip( $atts )
    {
        
        if ( !empty($atts['link']) ) {
            if ( !empty($atts['target']) ) {
                $atts['target'] = ' target="_blank"';
            }
            if ( !empty($atts['nofollow']) ) {
                $atts['nofollow'] = ' rel="nofollow"';
            }
        }
        
        $class = '';
        if ( !empty($this->settings['external_icon']) ) {
            if ( strpos( $atts['link'], get_site_url() ) !== 0 ) {
                $class = 'glossary-external-link ';
            }
        }
        if ( !empty($class) ) {
            $class = ' class="' . $class . '"';
        }
        $html = '<a href="' . $atts['link'] . '"' . $atts['target'] . $atts['nofollow'] . $class . '>' . $atts['replace'] . '</a>';
        if ( isset( $this->settings['tooltip'] ) && $this->settings['tooltip'] !== 'link' ) {
            $html = $this->tooltip_html(
                $html,
                $atts['term_ID'],
                $atts['readmore'],
                $atts['link']
            );
        }
        return $html;
    }

}
new Glossary_Tooltip_Engine();