<?php

add_shortcode( 'glossary-terms', 'glossary_terms_list_shortcode' );
/**
 * Shortcode for generate list of glossary terms
 *
 * @param array $atts An array with all the parameters.
 * 
 * @since 1.1.0
 *
 * @return array
 */
function glossary_terms_list_shortcode( $atts )
{
    $atts = shortcode_atts( array(
        'order' => 'asc',
        'num'   => '100',
        'tax'   => '',
    ), $atts );
    return get_glossary_terms_list( $atts['order'], $atts['num'], $atts['tax'] );
}

add_shortcode( 'glossary-cats', 'glossary_cat_list_shortcode' );
/**
 * Shortcode for generate list of glossary cat
 *
 * @param array $atts An array with all the parameters.
 *
 * @since 1.1.0
 *
 * @return array
 */
function glossary_cat_list_shortcode( $atts )
{
    $atts = shortcode_atts( array(
        'order' => 'asc',
        'num'   => '100',
    ), $atts );
    return get_glossary_cats_list( $atts['order'], $atts['num'] );
}