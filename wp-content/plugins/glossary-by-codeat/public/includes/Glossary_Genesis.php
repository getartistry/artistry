<?php

/**
 * Glossary_Genesis
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
class Glossary_Genesis {

	/**
	 * Initialize the class with all the hooks
     *
     * @param object $gt_search_engine The Glossary Search Engine class.
     *
     * @since 1.0.0
     */
    public function __construct( $gt_search_engine ) {
        $this->search_engine = $gt_search_engine;
        add_action( 'genesis_entry_content', array( $this, 'genesis_content' ), 9 );
    }

    /**
     * Remove the code for links support for excerpt in Genesis
     *
     * @param string $regex The regex that we need to fix.
     *
     * @return string
     */
    public function fix_for_anchor( $regex ) {
        return str_replace( '<a|', '', $regex );
    }

    /**
     * Genesis hack to add the support for the archive content page
     * Based on genesis_do_post_content
     *
     * @return void
     */
    public function genesis_content() {
        // Only display excerpt if not a teaser.
        if ( !in_array( 'teaser', get_post_class(), true ) ) {
            if ( is_archive() ) {
                if ( genesis_get_option( 'content_archive' ) === 'full' ) {
                    $content = str_replace( ']]>', ']]&gt;', apply_filters( 'the_content', get_the_content( get_the_ID() ) ) );
                } else {
                    global $post;
                    $content = $post->post_excerpt;
                    if ( empty( $content ) ) {
                        if ( genesis_get_option( 'content_archive_limit' ) ) {
                            $content = get_the_content_limit( (int) genesis_get_option( 'content_archive_limit' ), genesis_a11y_more_link( __( '[Read more...]', 'genesis' ) ) );
                        }
                    } else {
                        $content .= ' <a href="' . get_the_permalink() . '">' . genesis_a11y_more_link( __( '[Read more...]', 'genesis' ) ) . '</a>';
                    }

                    add_filter( 'glossary-regex', array( $this, 'fix_for_anchor' ), 9 );
                }

                $content = wpautop( do_shortcode( $content ) );
                if ( genesis_get_option( 'content_archive' ) !== 'full' ) {
                    $content = $this->search_engine->check_auto_link( $content );
                    remove_filter( 'glossary-regex', array( $this, 'fix_for_anchor' ) );
                }

                echo $content;

                remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
            }
        }
    }

}

new Glossary_Genesis( $gt_search_engine );

