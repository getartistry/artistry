<?php
/**
 * The next/previous links to go to another portfolio item.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

the_post_navigation( array(
    'prev_text'             => '<span class="title"><i class="fa fa-long-arrow-left"></i>'. esc_html__( 'Previous Post', 'ocean-portfolio' ) .'</span><span class="post-title">%title</span>',
    'next_text'             => '<span class="title"><i class="fa fa-long-arrow-right"></i>'. esc_html__( 'Next Post', 'ocean-portfolio' ) .'</span><span class="post-title">%title</span>',
    'in_same_term'          => true,
    'taxonomy'              => 'ocean_portfolio_tag',
    'screen_reader_text'    => esc_html__( 'Continue Reading', 'ocean-portfolio' ),
) ); ?>