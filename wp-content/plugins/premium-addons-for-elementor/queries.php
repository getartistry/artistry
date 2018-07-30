<?php 

if (!defined('ABSPATH')) exit;

function premium_blog_get_post_data($args, $paged, $new_offset){
    $defaults = array(
        'category'          => '',
        'posts_per_page'    => 1,
        'paged'             => $paged,
        'offset'            => $new_offset,
    );

    $atts = wp_parse_args($args,$defaults);

    $posts = get_posts($atts);

    return $posts;
}

function premium_blog_get_post_settings($settings){
        $post_args['category'] = $settings['premium_blog_categories'];
		$post_args['posts_per_page'] = $settings['premium_blog_number_of_posts'];
        
        return $post_args;
} 

function premium_addons_get_excerpt_by_id($post_id,$excerpt_length,$excerpt_type,$exceprt_text){
    $the_post = get_post($post_id); //Gets post ID

    $the_excerpt = null;
    if ($the_post)
    {
        $the_excerpt = $the_post->post_excerpt ? $the_post->post_excerpt : $the_post->post_content;
    }

    $the_excerpt = strip_tags(strip_shortcodes($the_excerpt)); //Strips tags and images
    $words = explode(' ', $the_excerpt, $excerpt_length + 1);

     if(count($words) > $excerpt_length) :
         array_pop($words);
         if( 'dots' == $excerpt_type){
            array_push($words, '…');
         } else {
            array_push($words, ' <a href="' . get_permalink($post_id) .'" class="premium-blog-excerpt-link">' . $exceprt_text . '</a>'); 
         }
         
         $the_excerpt = implode(' ', $words);
     endif;

     return $the_excerpt;
}

function premium_addons_post_type_categories(){
    $terms = get_terms( array( 
        'taxonomy' => 'category',
        'hide_empty' => true,
    ));
    
    $options = array();
    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
    foreach ( $terms as $term ) {
        $options[ $term->term_id ] = $term->name;
    }
    }
    
    return $options;
}