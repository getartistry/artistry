<?php
/**
 * Helpers functions
 *
 * @package OceanWP WordPress theme
 */

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Custom excerpts based on wp_trim_words
 *
 * @since	1.0.0
 * @link	http://codex.wordpress.org/Function_Reference/wp_trim_words
 */
if ( ! function_exists( 'oew_excerpt' ) ) {

	function oew_excerpt( $length = 15 ) {

		// Get global post
		global $post;

		// Get post data
		$id			= $post->ID;
		$excerpt	= $post->post_excerpt;
		$content 	= $post->post_content;

		// Display custom excerpt
		if ( $excerpt ) {
			$output = $excerpt;
		}

		// Check for more tag
		elseif ( strpos( $content, '<!--more-->' ) ) {
			$output = get_the_content( $excerpt );
		}

		// Generate auto excerpt
		else {
			$output = wp_trim_words( strip_shortcodes( get_the_content( $id ) ), $length );
		}

		// Echo output
		echo wp_kses_post( $output );

	}

}

/**
 * Ajax search
 *
 * @since	1.0.7
 */
if ( ! function_exists( 'oew_ajax_search' ) ) {

	function oew_ajax_search() {

		$search 	= sanitize_text_field( $_POST[ 'search' ] );
        $post_type  = 'any';
        $args  		= array(
            's'                => $search,
            'post_type'        => $post_type,
            'post_status'      => 'publish',
            'posts_per_page'   => 5,
        );
		$query 		= new WP_Query( $args );
		$output 	= '';

		// Icons
		if ( is_RTL() ) {
			$icon = 'left';
		} else {
			$icon = 'right';
		}

		if ( $query->have_posts() ) {

			$output .= '<ul>';
			
				while( $query->have_posts() ) : $query->the_post();
					$output .= '<li>';
						$output .= '<a href="'. get_permalink() .'" class="search-result-link clr">';

							if ( has_post_thumbnail() ) {
								$output .= get_the_post_thumbnail( get_the_ID(), 'thumbnail', array( 'alt' => get_the_title(), 'itemprop' => 'image', ) );
							}

							$output .= '<div class="result-title">' . get_the_title() . '</div>';
							$output .= '<i class="icon fa fa-arrow-'. $icon .'" aria-hidden="true"></i>';
						$output .= '</a>';
					$output .= '</li>';
				endwhile;

				if ( $query->found_posts > 1 ) {
	            	$search_link = get_search_link( $search );
	            	
	            	/*if ( strpos( $search_link, '?' ) !== false ) {
	            		$search_link .= '?post_type='. $post_type;
	            	}*/

	                $output .= '<li><a href="' . $search_link . '" class="all-results"><span>' . sprintf( esc_html__( 'View all %d results', 'ocean-elementor-widgets' ), $query->found_posts ) . '<i class="fa fa-long-arrow-'. $icon .'" aria-hidden="true"></i></span></a></li>';
	            }

            $output .= '</ul>';
		
		} else {
			
			$output .= '<div class="oew-no-search-results">';
            $output .= '<h6>' . esc_html__( 'No results', 'ocean-elementor-widgets' ) . '</h6>';
            $output .= '<p>' . esc_html__( 'No search results could be found, please try another search.', 'ocean-elementor-widgets' ) . '</p>';
            $output .= '</div>';
			
		}
		
		wp_reset_query();

		echo $output;
		
		die();

    }

    add_action( 'wp_ajax_oew_ajax_search', 'oew_ajax_search' );
    add_action( 'wp_ajax_nopriv_oew_ajax_search', 'oew_ajax_search' );

}