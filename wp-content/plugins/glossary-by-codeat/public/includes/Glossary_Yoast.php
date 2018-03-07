<?php

/**
 * Glossary_Yoast
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2015 GPL
 * @link      http://codeat.co
 * @license   GPL-2.0+
 */

/**
 * Support for Yoast SEO plugin
 */
class Glossary_Yoast {

    /**
     * Initialize the class with all the hooks
     *
     * @since 1.0.0
     */
    public function initialize() {
        // Support for Yoast to avoid the execution of Glossary on opengraph
        add_filter( 'wpseo_metadesc', array( $this, 'wpseo_metadesc_excerpt' ), 10, 1 );
    }

    /**
     * Avoid execution of Glossary on Yoast
     *
     * @param string $wpseo_desc The original text.
     *
     * @global object $post
     *
     * @return string
     */
    public function wpseo_metadesc_excerpt( $wpseo_desc ) {
        if ( empty( $wpseo_desc ) ) {
            global $post;
            if ( empty( $post->post_excerpt ) ) {
                if ( isset( $post->post_content ) ) {
                    return wp_trim_words( $post->post_content );
                }
            }

            if ( is_object( $post ) ) {
                return $post->post_excerpt;
            }
        }

        return $wpseo_desc;
    }

}

$gt_yoast = new Glossary_Yoast();
$gt_yoast->initialize();

