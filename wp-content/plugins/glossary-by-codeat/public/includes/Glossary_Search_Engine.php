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
     * Initialize the class with all the hooks
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->settings = get_option( GT_SETTINGS . '-settings' );
        add_filter( 'the_content', array( $this, 'check_auto_link' ), 999 );
        add_filter( 'the_excerpt', array( $this, 'check_auto_link' ), 999 );
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
        if ( $is_page->is_singular() || $is_page->is_home() || $is_page->is_category() || $is_page->is_tag() || $is_page->is_arc_glossary() || $is_page->is_tax_glossary() ) {
            return $this->auto_link( $text );
        }
        return $text;
    }
    
    /**
     * That method return the regular expression
     *
     * @param string $title Terms.
     *
     * @return string
     */
    public function search_string( $title )
    {
        $title = preg_quote( $title, '/' );
        $ci = '(?i)' . $title . '(?-i)';
        return apply_filters( 'glossary-regex', '/(?<![\\w\\-\\.\\/]|=")(' . $ci . ')(?=[ \\.\\,\\:\\;\\*\\"\\)\\!\\?\\/\\%\\$\\€\\£\\|\\^\\<\\>\\“\\”])(?![^<]*(\\/>|<span|<a|<h|<\\/h|<\\/a|\\"))/u', $title );
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
        $gl_query_args = array(
            'post_type'              => 'glossary',
            'order'                  => 'ASC',
            'orderby'                => 'title',
            'posts_per_page'         => -1,
            'no_found_rows'          => true,
            'update_post_term_cache' => false,
            'glossary_auto_link'     => true,
        );
        $html_links = $words = array();
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
            // Add tooltip based on the title of the term
            $html_links[] = array(
                'regex'    => $this->search_string( get_the_title() ),
                'link'     => $link,
                'term_ID'  => $id_term,
                'target'   => $target,
                'nofollow' => $nofollow,
                'readmore' => $wantreadmore,
                'long'     => $this->get_len( get_the_title() ),
            );
            // Add tooltip based on the related post term of the term
            $related = $this->related_post_meta( get_post_meta( $id_term, GT_SETTINGS . '_tag', true ) );
            if ( is_array( $related ) ) {
                foreach ( $related as $value ) {
                    if ( !empty($value) ) {
                        $html_links[] = array(
                            'regex' => $this->search_string( $value ),
                            'id'    => count( $html_links ) - 1,
                            'long'  => $this->get_len( $value ),
                        );
                    }
                }
            }
        }
        wp_reset_postdata();
        $text = $this->do_wrap( $text, $html_links );
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
            // Can create problems so remove it is a hope solution!
            $text = trim( str_replace( array( '', '
' ), '', $text ) );
            $matches = $all_terms = $already_find = array();
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
                        // Check if the most previus contain stuff
                        
                        if ( isset( $term['id'] ) ) {
                            $term = array_merge( $terms[$term['id']], $term );
                            if ( !isset( $term['link'] ) ) {
                                for ( $i = $term['id'] ;  $i >= 0 ;  $i-- ) {
                                    $term = array_merge( $terms[$i], $term );
                                    $term['post'] = '';
                                    if ( isset( $term['link'] ) ) {
                                        break;
                                    }
                                }
                            }
                        }
                        
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

}
new Glossary_Search_Engine();