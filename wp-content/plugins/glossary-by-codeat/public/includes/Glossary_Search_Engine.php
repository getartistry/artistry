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
    public function __construct()
    {
        $this->settings = get_option( GT_SETTINGS . '-settings' );
        // Support for Crayon SyntaxHighlighter
        $crayon = defined( 'CRAYON_DOMAIN' );
        $priority = 999;
        if ( $crayon ) {
            $priority = 99;
        }
        add_filter( 'the_content', array( $this, 'check_auto_link' ), $priority );
        add_filter( 'the_excerpt', array( $this, 'check_auto_link' ), $priority );
        // Support for Yoast to avoid the execution of Glossary on opengraph
        add_filter(
            'wpseo_metadesc',
            array( $this, 'wpseo_metadesc_excerpt' ),
            10,
            1
        );
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
     * Check the settings and if is a single page
     *
     * @param string $related Contain the terms related to split with a comma.
     *
     * @return boolean
     */
    public function related_post_meta( $related )
    {
        $value = array_map( 'trim', explode( ',', $related ) );
        if ( empty($value[0]) ) {
            $value = false;
        }
        return $value;
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
        if ( $is_page->is_feed() || $is_page->is_singular() || $is_page->is_home() || $is_page->is_category() || $is_page->is_tag() || $is_page->is_arc_glossary() || $is_page->is_tax_glossary() ) {
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
        $ci = '(?i)' . $term . '(?-i)';
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
        return apply_filters( 'glossary-regex', '/(?<![\\w\\-\\.\\/]|=")(' . $ci . ')(?=[ \\.\\,\\:\\;\\*\\"\\)\\!\\?\\/\\%\\$\\€\\£\\|\\^\\<\\>\\“\\”])(?![^<]*(\\/>|<span|<a|<h|<\\/h|<\\/a|<\\/pre|\\"))/u', $term );
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
        
        if ( empty($this->terms_queue) ) {
            $gl_query_args = array(
                'post_type'              => 'glossary',
                'order'                  => 'ASC',
                'orderby'                => 'title',
                'posts_per_page'         => -1,
                'no_found_rows'          => true,
                'update_post_term_cache' => false,
                'glossary_auto_link'     => true,
            );
            $gl_query = new WP_Query( $gl_query_args );
            while ( $gl_query->have_posts() ) {
                $gl_query->the_post();
                $id_term = get_the_ID();
                $url = get_post_meta( $id_term, GT_SETTINGS . '_url', true );
                $type = get_post_meta( $id_term, GT_SETTINGS . '_link_type', true );
                $link = get_glossary_term_url();
                $target = get_post_meta( $id_term, GT_SETTINGS . '_target', true );
                $nofollow = get_post_meta( $id_term, GT_SETTINGS . '_nofollow', true );
                $wantreadmore = false;
                // Get the post of the glossary loop
                if ( empty($url) && empty($type) || $type === 'internal' ) {
                    $wantreadmore = true;
                }
                $term_value = $this->get_lower( get_the_title() );
                $hash = wp_hash( $term_value, 'nonce' );
                if ( !isset( $this->terms_queue[$term_value] ) ) {
                    // Add tooltip based on the title of the term
                    $this->terms_queue[$term_value] = array(
                        'value'    => $term_value,
                        'regex'    => $this->search_string( get_the_title() ),
                        'link'     => $link,
                        'term_ID'  => $id_term,
                        'target'   => $target,
                        'nofollow' => $nofollow,
                        'readmore' => $wantreadmore,
                        'long'     => $this->get_len( get_the_title() ),
                        'hash'     => $hash,
                    );
                }
                // Add tooltip based on the related post term of the term
                $related = $this->related_post_meta( get_post_meta( $id_term, GT_SETTINGS . '_tag', true ) );
                if ( is_array( $related ) ) {
                    foreach ( $related as $value ) {
                        
                        if ( !empty($value) ) {
                            $term_value = $this->get_lower( $value );
                            if ( !isset( $this->terms_queue[$term_value] ) ) {
                                $this->terms_queue[$term_value] = array(
                                    'value'    => $term_value,
                                    'regex'    => $this->search_string( $value ),
                                    'link'     => $link,
                                    'term_ID'  => $id_term,
                                    'target'   => $target,
                                    'nofollow' => $nofollow,
                                    'readmore' => $wantreadmore,
                                    'long'     => $this->get_len( $value ),
                                    'hash'     => $hash,
                                );
                            }
                        }
                    
                    }
                }
            }
            wp_reset_postdata();
            /**
             * All the terms parsed in array
             * 
             * @param string $term_queue The terms.
             * 
             * @since 1.4.4
             * 
             * @return array $term_queue We need the term.
             */
            $this->terms_queue = apply_filters( 'glossary_terms_results', $this->terms_queue );
            usort( $this->terms_queue, array( $this, 'sort_by_long' ) );
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
    public function do_wrap( $text, $terms )
    {
        
        if ( !empty($text) && !empty($terms) ) {
            $text = trim( $text );
            $matches = $all_terms = $already_find = $already_term_find = array();
            $new_text = $text;
            foreach ( $terms as $term ) {
                // Detect if the string exist
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
                                // 1 is the position, 0 the text find
                                $all_terms[$match[1]] = array( $term['long'], $this->tooltip_engine->link_or_tooltip( $term ), $term['replace'] );
                                $already_find[$match[1]] = $match[1] + $term['long'];
                                if ( isset( $this->settings['first_occurence'] ) ) {
                                    break;
                                }
                            }
                        
                        }
                    }
                } catch ( Exception $e ) {
                    echo  error_log( $e->getMessage() . ', regex:' . $term['regex'] ) ;
                }
            }
            
            if ( !empty($all_terms) ) {
                uksort( $all_terms, 'strnatcmp' );
                $new_pos = -1;
                $old_new_text = $old_text = '';
                $old_pos = 0;
                foreach ( $all_terms as $pos => $term ) {
                    
                    if ( $new_pos > -1 ) {
                        $new_pos = $new_pos + $old_new_text + ($pos - ($old_pos + $old_text));
                    } else {
                        $new_pos = $pos;
                    }
                    
                    $old_new_text = $this->get_len( $term[1] );
                    $old_text = $term[0];
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
            }
            
            // This eventually remove broken UTF-8
            return iconv( 'UTF-8', 'UTF-8//IGNORE', $new_text );
        }
        
        return $text;
    }
    
    /**
     * Length of the string based on encode
     *
     * @param string $string The string to get the length.
     *
     * @return string
     */
    public function get_len( $string )
    {
        if ( gl_text_is_rtl( $string ) ) {
            return mb_strlen( $string );
        }
        return mb_strlen( $string, 'latin1' );
    }
    
    /**
     * Return a lower string using the settings
     * 
     * @param string $term The term.
     * 
     * @return string
     */
    public function get_lower( $term )
    {
        return $term;
    }
    
    /**
     * Method for usort to order all the terms on DESC
     * 
     * @param array $a Previous index.
     * @param array $b Next index.
     * 
     * @return boolean
     */
    public function sort_by_long( $a, $b )
    {
        return $b['long'] - $a['long'];
    }
    
    /**
     * Avoid execution of GLossary on Yoast
     * 
     * @param string $wpseo_desc The original text.
     * 
     * @global object $post
     * 
     * @return string
     */
    public function wpseo_metadesc_excerpt( $wpseo_desc )
    {
        
        if ( empty($wpseo_desc) ) {
            global  $post ;
            if ( empty($post->post_excerpt) ) {
                return $post->post_content;
            }
            return $post->post_excerpt;
        }
        
        return $wpseo_desc;
    }

}
new Glossary_Search_Engine();