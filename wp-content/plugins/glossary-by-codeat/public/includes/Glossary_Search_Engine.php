<?php

/**
 * Glossary_Search_Engine
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2015 GPL
 * @link      http://codeat.co
 * @license   GPL-2.0+
 */
/**
 * Engine system that search :-P
 */
class Glossary_Search_Engine
{
    /**
     * Instance of this class.
     *
     * @var object
     */
    protected static  $instance = null ;
    /**
     * The list of terms parsed
     *
     * @var array
     */
    public  $terms_queue = array() ;
    /**
     * Initialize the class with all the hooks
     *
     * @since 1.0.0
     */
    public function initialize()
    {
        // Support for Crayon SyntaxHighlighter
        $crayon = defined( 'CRAYON_DOMAIN' );
        $priority = 999;
        if ( $crayon ) {
            $priority = 99;
        }
        add_filter( 'the_content', array( $this, 'check_auto_link' ), $priority );
        add_filter( 'the_excerpt', array( $this, 'check_auto_link' ), $priority );
        $this->tooltip_engine = Glossary_Tooltip_Engine::get_instance();
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
     * Validate to show the auto link
     *
     * @param string $text The content.
     *
     * @return string
     */
    public function check_auto_link( $text )
    {
        $is_page = new Glossary_Is_Methods();
        if ( $is_page->is_feed() || $is_page->is_singular() || $is_page->is_home() || $is_page->is_category() || $is_page->is_tag() || $is_page->is_arc_glossary() || $is_page->is_tax_glossary() || $is_page->is_yoast() ) {
            return $this->auto_link( $text );
        }
        return $text;
    }
    
    /**
     * That method return the regular expression
     *
     * @param string $term Terms.
     *
     * @return string
     */
    public function search_string( $term )
    {
        $term = preg_quote( $term, '/' );
        $caseinsensitive = '(?i)' . $term . '(?-i)';
        /**
         * The regex that Glossary will use for the first step of scanning
         *
         * @param string $regex The regex.
         * @param string $term  The term of the regex.
         *
         * @since 1.1.0
         *
         * @return array $regex We need the regex.
         */
        $regex = apply_filters( 'glossary_regex', '/(?<![\\w\\-\\.\\/]|=")(' . $caseinsensitive . ')(?=[ \\.\\,\\:\\;\\*\\"\\)\\!\\?\\/\\%\\$\\€\\£\\|\\^\\<\\>\\“\\”])(?![^<]*(\\/>|<span|<a|<h|<\\/button|<\\/h|<\\/a|<\\/pre|\\"))/u', $term );
        return $regex;
    }
    
    /**
     * The magic function that add the glossary terms to your content
     *
     * @param string $text String that wrap with a tooltip/link.
     *
     * @global object $post
     *
     * @return string
     */
    public function auto_link( $text )
    {
        /**
         * Use a different set of terms and avoid the WP_Query
         *
         * @param array $term_queue The terms.
         *
         * @since 1.5.0
         *
         * @return array $term_queue We need the terms.
         */
        $this->terms_queue = apply_filters( 'glossary_custom_terms', $this->terms_queue );
        
        if ( empty($terms_queue) ) {
            $gl_query_args = array(
                'post_type'              => 'glossary',
                'order'                  => 'ASC',
                'orderby'                => 'title',
                'posts_per_page'         => -1,
                'post_status'            => 'publish',
                'no_found_rows'          => true,
                'update_post_term_cache' => false,
                'glossary_auto_link'     => true,
            );
            $gl_query = new WP_Query( $gl_query_args );
            while ( $gl_query->have_posts() ) {
                $gl_query->the_post();
                $id_term = get_the_ID();
                $term_value = $this->get_lower( get_the_title() );
                $this->default_term_parameters( $id_term, $term_value );
                if ( !isset( $this->terms_queue[$term_value] ) ) {
                    // Add tooltip based on the title of the term
                    $this->enqueue_term( $id_term, get_the_title( $id_term ) );
                }
                $this->enqueue_related_post( $id_term );
            }
            wp_reset_postdata();
            /**
             * All the terms parsed in array
             *
             * @param array $term_queue The terms.
             *
             * @since 1.4.4
             *
             * @return array $term_queue We need the terms.
             */
            $this->terms_queue = apply_filters( 'glossary_terms_results', $this->terms_queue );
            // We need to sort by long to inject the long version of terms and not the most short
            usort( $this->terms_queue, 'gl_sort_by_long' );
        }
        
        $text = $this->do_wrap( $text, $this->terms_queue );
        return $text;
    }
    
    /**
     * Wrap the string with a tooltip/link.
     *
     * @param string $text  The string to find.
     * @param array  $terms The list of links.
     *
     * @return string
     */
    function do_wrap( $text, $terms )
    {
        
        if ( !empty($text) && !empty($terms) ) {
            $text = trim( $text );
            $all_terms = $this->regex_match( $text, $terms );
            if ( !empty($all_terms) ) {
                $text = $this->replace_with_utf_8( $text, $all_terms );
            }
            if ( !empty($all_terms) ) {
                // This eventually remove broken UTF-8
                return iconv( 'UTF-8', 'UTF-8//IGNORE', $text );
            }
        }
        
        return $text;
    }
    
    public function default_term_parameters( $id_term, $term_value )
    {
        $this->parameters = array();
        $this->parameters['url'] = get_post_meta( $id_term, GT_SETTINGS . '_url', true );
        $this->parameters['type'] = get_post_meta( $id_term, GT_SETTINGS . '_link_type', true );
        $this->parameters['link'] = get_glossary_term_url( $id_term );
        $this->parameters['target'] = get_post_meta( $id_term, GT_SETTINGS . '_target', true );
        $this->parameters['nofollow'] = get_post_meta( $id_term, GT_SETTINGS . '_nofollow', true );
        $this->parameters['wantreadmore'] = false;
        // Get the post of the glossary loop
        if ( empty($this->parameters['url']) && empty($this->parameters['type']) || $this->parameters['type'] === 'internal' ) {
            $this->parameters['wantreadmore'] = true;
        }
        $this->parameters['hash'] = md5( $term_value );
    }
    
    /**
     * Enqueue the related post
     *
     * @param integer $id_term The term id to search for related.
     */
    public function enqueue_related_post( $id_term )
    {
        // Add tooltip based on the related post term of the term
        $related = gl_related_post_meta( get_post_meta( $id_term, GT_SETTINGS . '_tag', true ) );
        if ( is_array( $related ) ) {
            foreach ( $related as $value ) {
                
                if ( !empty($value) ) {
                    $term_value = $this->get_lower( $value );
                    if ( !isset( $this->terms_queue[$term_value] ) ) {
                        $this->enqueue_term( $id_term, $value );
                    }
                }
            
            }
        }
    }
    
    /**
     * Enqueue the term
     *
     * @param integer $id_term The term id to search for related.
     * @param string  $value   The text.
     */
    public function enqueue_term( $id_term, $value )
    {
        $this->terms_queue[$value] = array(
            'value'    => $value,
            'regex'    => $this->search_string( $value ),
            'link'     => $this->parameters['link'],
            'term_ID'  => $id_term,
            'target'   => $this->parameters['target'],
            'nofollow' => $this->parameters['nofollow'],
            'readmore' => $this->parameters['wantreadmore'],
            'long'     => gl_get_len( $value ),
            'hash'     => $this->parameters['hash'],
        );
    }
    
    /**
     * Find terms with the regex
     *
     * @param string $text The text to analyze.
     * @param array  $terms The list of terms.
     *
     * @return array The list of terms finded in the text.
     */
    public function regex_match( $text, $terms )
    {
        $matches = $all_terms = $already_find = $already_term_find = array();
        foreach ( $terms as $term ) {
            // Detect if the term exist
            try {
                if ( preg_match_all(
                    $term['regex'],
                    $text,
                    $matches,
                    PREG_OFFSET_CAPTURE
                ) ) {
                    foreach ( $matches[0] as $match ) {
                        $break = false;
                        // Avoid annidate detection
                        foreach ( $already_find as $previous_init => $previous_end ) {
                            
                            if ( $previous_init <= $match[1] && $match[1] + $term['long'] <= $previous_end ) {
                                $break = true;
                                break;
                            }
                        
                        }
                        
                        if ( !$break ) {
                            $term['replace'] = $match[0];
                            // 1 is the position, 0 the text found
                            $all_terms[$match[1]] = array( $term['long'], $this->tooltip_engine->link_or_tooltip( $term ), $term['replace'] );
                            $already_find[$match[1]] = $match[1] + $term['long'];
                            if ( gl_get_bool_settings( 'first_occurrence' ) ) {
                                break;
                            }
                        }
                    
                    }
                }
            } catch ( Exception $e ) {
                echo  error_log( $e->getMessage() . ', regex:' . $term['regex'] ) ;
            }
        }
        return $all_terms;
    }
    
    /**
     * Replace the terms with the link or tooltip with UTF-8 support
     *
     * @param string $text The text to analyze.
     * @param array  $all_terms The list of terms.
     *
     * @return string The new text.
     */
    public function replace_with_utf_8( $text, $all_terms )
    {
        uksort( $all_terms, 'strnatcmp' );
        $new_pos = key( $all_terms );
        // Copy of text is required for replace
        $new_text = $text;
        $new_term_length = 0;
        $old_term_length = '';
        $old_pos = 0;
        foreach ( $all_terms as $pos => $term ) {
            // Calculate the cursor position after the first loop
            if ( $old_pos !== 0 ) {
                $new_pos = $new_pos + $new_term_length + ($pos - ($old_pos + $old_term_length));
            }
            $new_term_length = gl_get_len( $term[1] );
            $old_term_length = $term[0];
            $encode = mb_detect_encoding( $term[2] );
            $real_length = $term[0];
            // With utf-8 character with multiple bits this is the workaround for the right value
            if ( $encode !== 'ASCII' ) {
                
                if ( gl_text_is_rtl( $text ) ) {
                    $multiply = 0;
                    // Seems that when there are symbols I need to add 2 for every of them
                    $multiply += mb_substr_count( $text, '-' ) + mb_substr_count( $text, '.' ) + mb_substr_count( $text, ':' );
                    if ( $multiply > 0 ) {
                        $real_length += $multiply * 2;
                    }
                    $real_length += $real_length;
                }
            
            }
            // 0 is the term long, 1 is the new html
            $new_text = substr_replace(
                $new_text,
                $term[1],
                $new_pos,
                $real_length
            );
            $old_pos = $pos;
        }
        return $new_text;
    }
    
    /**
     * Return a lower string using the settings
     *
     * @param string $term The term.
     *
     * @return string
     */
    function get_lower( $term )
    {
        return $term;
    }

}
$gt_search_engine = new Glossary_Search_Engine();
$gt_search_engine->initialize();