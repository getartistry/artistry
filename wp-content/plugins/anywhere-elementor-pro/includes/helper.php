<?php

namespace Aepro;

use WP_Query;
use Elementor\Group_Control_Border;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;

class Helper{

    function get_rule_post_types( $output = 'object'){
        $final_post_types = array();
        $all_post_types = get_post_types(array('public' => true), $output);


        $skip_post_types = array(
            'attachment',
            'ae_global_templates',
            'elementor_library'
        );

        if($output === 'names'){
            return array_diff($all_post_types,$skip_post_types);
        }

        foreach($all_post_types as $name => $post_type){
            if(!in_array($name,$skip_post_types)){
                $final_post_types[$name] = $post_type->label;
            }
        }

        return $final_post_types;
    }

    function get_post_types_with_archive(){
        $ret_post_types = array();
        $post_types = get_post_types(array('has_archive' => true), 'object');

        $ret_post_types['post'] = 'Post Archive';
        foreach($post_types as $name => $post_type){
           $ret_post_types[$name] = $post_type->label.' Archive';
        }
        return $ret_post_types;
    }
    function get_demo_post_data()
    {
        $post_data = array();
        $preview_post_ID = '';
        if($GLOBALS['post']->post_type == 'ae_global_templates'){
            $ae_post_ID = $GLOBALS['post']->ID;
            $preview_post_ID = get_post_meta($ae_post_ID,'ae_preview_post_ID',true);
            if ($preview_post_ID != '' && $preview_post_ID != 0):
                $post_data = get_post($preview_post_ID);
            else:
                $args = array(
                    'post_type' => 'post',
                    'post_status' => 'publish',
                    'posts_per_page' => 1
                );
                $demo_data = get_posts( $args );
                $post_data = $demo_data[0];
            endif;
        }else{
            $post_data = $GLOBALS['post'];
        }

        return $post_data;
    }

    function get_ae_post_templates(){
        $args = array(
            'post_type' => 'ae_global_templates',
            'meta_key'  => 'ae_render_mode',
            'meta_value' => 'post_template',
            'posts_per_page'    => -1
        );

        $ret_array['global'] = esc_html__('Global','ae-pro');
        $ret_array['none'] = esc_html__('None','ae-pro');
        $post_templates = get_posts($args);
        foreach($post_templates as $pt){
            $ret_array[$pt->ID] = $pt->post_title;
        }
        return $ret_array;
    }

    function get_ae_product_templates(){
        $args = array(
            'post_type' => 'ae_global_templates',
            'meta_key'  => 'ae_render_mode',
            'meta_value' => 'wc_product_single'
        );

        $ret_array[''] = esc_html__('Default','ae-pro');
        $post_templates = get_posts($args);
        foreach($post_templates as $pt){
            $ret_array[$pt->ID] = $pt->post_title;
        }
        return $ret_array;
    }

    function get_ae_active_archive_template(){

        if(is_date()){
            $args = array(
                'post_type'   => 'ae_global_templates',
                'meta_query'  => array(
                    array(
                        'key' => 'ae_render_mode',
                        'value'   => 'date_template',
                        'compare' => '='
                    )
                )
            );

            $date_template = new WP_Query($args);

            if($date_template->found_posts){
                $date_template->the_post();
                $date_template = get_the_ID();
            }else{
                wp_reset_postdata();
                return false;
            }
            wp_reset_postdata();
            $date_template = apply_filters('ae_template_filter', $date_template);
            return $date_template;
        }

        $helper = new Helper();
        $is_blog = $helper->is_blog();

        // Check if post type archive
        if(is_post_type_archive() || $is_blog){
            if($is_blog){
                $post_type = 'post';
            }else{
                $query = get_queried_object();
                $post_type = $query->name;
            }

            if($post_type == 'product'){
                //return false;
            }
            // apply template for post type archive
            $args = array(
                'post_type' => 'ae_global_templates',
                'meta_query' => array(
                    array(
                        'key' => 'ae_render_mode',
                        'value'   => 'post_type_archive_template',
                        'compare' => '='
                    ),
                    array(
                        'key' => 'ae_rule_post_type_archive',
                        'value'   => $post_type,
                        'compare' => '='
                    )
                )
            );
            $templates = new WP_Query($args);

            if($templates->found_posts){
                $templates->the_post();
                $ae_tid = get_the_ID();
            }else{
                return false;
            }
            wp_reset_postdata();
            $ae_tid = apply_filters('ae_template_filter', $ae_tid);
            return $ae_tid;
        }


        // Check if it is author archive
        if(is_author()){
            $query = get_queried_object();
            $author_id = $query->ID;

            // check template for author template through author meta
            $author_template = get_the_author_meta('ae_author_template',$author_id);

            if(!$author_template || $author_template == 'global'){
                // check global AE Template for Author Archive
                $args = array(
                    'post_type'   => 'ae_global_templates',
                    'meta_query'  => array(
                        array(
                            'key' => 'ae_render_mode',
                            'value'   => 'author_template',
                            'compare' => '='
                        ),
                        array(
                            'key' => 'ae_apply_global',
                            'value'   => 'true',
                            'compare' => '='
                        )
                    )
                );

                $author_template = new WP_Query($args);

                if($author_template->found_posts){
                    $author_template->the_post();
                    $author_template = get_the_ID();
                }else{
                    wp_reset_postdata();
                    return false;
                }
                wp_reset_postdata();
            }

            $author_template = apply_filters('ae_template_filter', $author_template);
            return $author_template;
        }


        // Not post type archive -- It can be taxonomy archive
        $query = get_queried_object();
        if(is_category()){
            $taxonomy = 'category';
        }elseif(is_tag()){
            $taxonomy = 'post_tag';
        }elseif(is_tax()){
            $query = get_queried_object();
            $taxonomy = $query->taxonomy;
        }

        // Todo:: add term level template implementation
        // get term template
        $term_id = $query->term_id;
        $ae_tid = get_term_meta($term_id,'ae_term_template',true);


        if((empty($ae_tid) || $ae_tid == 'global') && !empty($taxonomy)){
            // apply global template for taxonomy archive
            $args = array(
                'post_type' => 'ae_global_templates',
                'meta_query' => array(
                    array(
                        'key' => 'ae_render_mode',
                        'value'   => 'archive_template',
                        'compare' => '='
                    ),
                    array(
                        'key' => 'ae_apply_global',
                        'value'   => 'true',
                        'compare' => '='
                    ),
                    array(
                        'key' => 'ae_rule_taxonomy',
                        'value'   => $taxonomy,
                        'compare' => '='
                    )
                )
            );
            $templates = new WP_Query($args);

            if($templates->found_posts){
                $templates->the_post();
                $ae_tid = get_the_ID();
            }else{
                return false;
            }
            wp_reset_postdata();
        }

        $ae_tid = apply_filters('ae_template_filter', $ae_tid);

        if($ae_tid == 'none'){
        	return false;
        }

        return $ae_tid;
    }

    function get_ae_active_post_template($post_id,$post_type){
        $ae_post_template = get_post_meta($post_id, 'ae_post_template', true);

        if(isset($ae_post_template) && $ae_post_template == 'none'){
            return false;
        }

        if(!isset($ae_post_template) || empty($ae_post_template) || $ae_post_template == 'global'){
            // apply global template
            $args = array(
                'post_type' => 'ae_global_templates',
                'meta_query' => array(
                    array(
                        'key' => 'ae_render_mode',
                        'value'   => 'post_template',
                        'compare' => '='
                    ),
                    array(
                        'key' => 'ae_apply_global',
                        'value'   => 'true',
                        'compare' => '='
                    ),
                    array(
                        'key' => 'ae_rule_post_type',
                        'value'   => $post_type,
                        'compare' => '='
                    )
                )
            );
            $templates = new WP_Query($args);
            if($templates->found_posts){
                $templates->the_post();
                $ae_tid = get_the_ID();
            }else{
                return false;
            }
            wp_reset_postdata();

        }else{
            // set individual post template
            $ae_tid = $ae_post_template;
        }


        $ae_tid = apply_filters('ae_template_filter', $ae_tid);
        return $ae_tid;
    }

    function get_ae_woo_product_data(){
        if($GLOBALS['post']->post_type == 'ae_global_templates'){
            $ae_woo_ID = $GLOBALS['post']->ID;
            $preview_woo_ID = get_post_meta($ae_woo_ID,'ae_preview_post_ID',true);
            if ($preview_woo_ID != ''):
                $product_data = wc_get_product($preview_woo_ID);
            else:
             // Todo:: Get product from template meta field if available
                $args = array(
                    'post_type' => 'product',
                    'post_status' => 'publish',
                    'posts_per_page' => 1
                );
                $preview_data = get_posts( $args );
                $product_data =  wc_get_product($preview_data[0]->ID);
            endif;
        }else{
            global $product;
            $product_data = $product;
        }

        return $product_data;
    }

    function ae_get_intermediate_image_sizes(){
        global $_wp_additional_image_sizes;

        $default_image_sizes = [ 'thumbnail', 'medium', 'medium_large', 'large', 'full' ];
        $image_options = array();
        foreach ( $default_image_sizes as $size ) {
            $image_sizes[ $size ] = [
                'width' => (int) get_option( $size . '_size_w' ),
                'height' => (int) get_option( $size . '_size_h' ),
                'crop' => (bool) get_option( $size . '_crop' ),
            ];
        }

        if ( $_wp_additional_image_sizes ) {
            $image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
        }

        foreach($image_sizes as $size => $image_size){
            $image_options[$size] = ($size).' '.$image_size['width'].' x '.$image_size['height'];
        }

        return $image_options;
    }
    function get_ae_render_mode_hook(){

        $render_modes = apply_filters('ae_pro_filter_hook_render_mode', array(
            'normal' => 'Normal',
            'post_template' => 'Post Template',
            'archive_template' => 'Taxonomy Archive Template',
            'post_type_archive_template' => 'Post Type Archive Template',
            'block_layout'   => __('Block Layout','ae-pro'),
            '404'            => __('404 Template','ae-pro'),
            'search'         => __('Search Template', 'ae-pro'),
            'author_template' => __('Author Archive', 'ae-pro'),
            'date_template'   => __('Date Archive', 'ae-pro')
        ));
        return $render_modes;
    }

    function ae_get_post_css(){
        if(!is_single() && !is_page()){
            return '';
        }

        $post = $this->get_demo_post_data();
        $css = '';
        $image_sizes = $this->ae_get_intermediate_image_sizes();
        foreach($image_sizes as $image_size => $size_data){
            $img_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),$image_size);
            $css .= '.ae-featured-img-size-'.$image_size.'{ background-image:url('.$img_src[0].'); }';
        }
        return $css;
    }

    function ae_get_custom_taxonomies(){
        $args = array(
            'public'   => true,
            '_builtin' => false

        );
        $tax_array = array();
        $taxonomies = get_taxonomies($args,'objects');
        if(count($taxonomies)){
            foreach($taxonomies as $slug => $taxonomy){
                $tax_array[$slug] = $taxonomy->labels->name;
            }
        }
        return $tax_array;
    }

    function get_rules_taxonomies(){
        $args = array(
            'public'   => true

        );
        $tax_array = array();
        $taxonomies = get_taxonomies($args,'objects');
        if(count($taxonomies)){
            foreach($taxonomies as $slug => $taxonomy){
                $tax_array[$slug] = $taxonomy->labels->name;
            }
        }
        return $tax_array;
    }

    function ae_get_date_format(){
        $date_format = array(
            'F j, Y g:i a' => date('F j, Y g:i a'),
            'F j, Y' => date( 'F j, Y' ),
            'F, Y' => date( 'F, Y' ),
            'g:i a' => date( 'g:i a' ),
            'g:i:s a' => date( 'g:i:s a' ),
            'l, F jS, Y' => date( 'l, F jS, Y' ),
            'M j, Y @ G:i' => date( 'M j, Y @ G:i' ),
            'Y/m/d \a\t g:i A' => date( 'Y/m/d \a\t g:i A' ),
            'Y/m/d \a\t g:ia' => date( 'Y/m/d \a\t g:ia' ),
            'Y/m/d g:i:s A' => date( 'Y/m/d g:i:s A' ),
            'Y/m/d' => date( 'Y/m/d' ),
            'Y-m-d \a\t g:i A' => date( 'Y-m-d \a\t g:i A' ),
            'Y-m-d \a\t g:ia' => date( 'Y-m-d \a\t g:ia' ),
            'Y-m-d g:i:s A' => date( 'Y-m-d g:i:s A' ),
            'Y-m-d' => date( 'Y-m-d' ),
            'custom' => __( 'Custom', 'ae-pro' )
        );
        return $date_format;
    }

    function get_previous_post_id( $post_id ) {
        // Get a global post reference since get_adjacent_post() references it
        global $post;
        // Store the existing post object for later so we don't lose it
        $oldGlobal = $post;
        // Get the post object for the specified post and place it in the global variable
        $post = get_post( $post_id );
        // Get the post object for the previous post
        $previous_post = get_previous_post();
        // Reset our global object
        $post = $oldGlobal;
        if ( '' == $previous_post )
            return false;
        return $previous_post->ID;
    }

    function get_next_post_id( $post_id ) {
        // Get a global post reference since get_adjacent_post() references it
        global $post;
        // Store the existing post object for later so we don't lose it
        $oldGlobal = $post;
        // Get the post object for the specified post and place it in the global variable
        $post = get_post( $post_id );
        // Get the post object for the next post
        $next_post = get_next_post();
        // Reset our global object
        $post = $oldGlobal;
        if ( '' == $next_post )
            return false;
        return $next_post->ID;
    }

    function get_woo_registered_tabs($output = ''){

    	global $product;
	    if(!is_object($product)) {
		    $product = wc_get_product(get_the_ID());
	    }

        $registered_tabs = [];

        $tabs = apply_filters( 'woocommerce_product_tabs', array() );

        if($output == 'full'){
            return $tabs;
        }

        foreach($tabs as $tab_key => $tab){
            $registered_tabs[$tab_key] = $tab['title'];
        }

        return $registered_tabs;
    }

    function get_woo_archive_template(){

        if(function_exists('is_shop')){
            if(is_shop() || is_tax('product_cat')){
                $args = array(
                    'post_type' => 'ae_global_templates',
                    'meta_query' => array(
                        array(
                            'key' => 'ae_render_mode',
                            'value'   => 'post_type_archive_template',
                            'compare' => '='
                        ),
                        array(
                            'key' => 'ae_rule_post_type_archive',
                            'value'   => 'product',
                            'compare' => '='
                        )
                    )
                );
                $templates = new WP_Query($args);

                if($templates->found_posts){
                    $templates->the_post();
                    $ae_tid = get_the_ID();
                }else{
                    return false;
                }
                wp_reset_postdata();
                return $ae_tid;
            }
        }
        return false;
    }

    function ae_block_layouts()
    {
        $block_layouts = [];
        $templates = get_posts([
            'numberposts'	=> -1,
            'post_type'		=> 'ae_global_templates',
            'meta_key'		=> 'ae_render_mode',
            'meta_value'	=> 'block_layout'
        ]);

        if(count($templates)){
            foreach($templates as $template){
                $block_layouts[$template->ID] = $template->post_title;
            }
        }
        return $block_layouts;
    }


    function has_404_template(){
        $args = array(
            'post_type' => 'ae_global_templates',
            'meta_query' => array(
                array(
                    'key' => 'ae_render_mode',
                    'value' => '404',
                    'compare' => '='
                )
            )
        );

        $templates = new WP_Query($args);

        if($templates->found_posts){
            $templates->the_post();
            $ae_tid = get_the_ID();
            wp_reset_postdata();
            return $ae_tid;
        }else{
            return false;
        }
    }

    function has_search_template(){
        $args = array(
            'post_type' => 'ae_global_templates',
            'meta_query' => array(
                array(
                    'key' => 'ae_render_mode',
                    'value' => 'search',
                    'compare' => '='
                )
            )
        );

        $templates = new WP_Query($args);

        if($templates->found_posts){
            $templates->the_post();
            $ae_tid = get_the_ID();
            wp_reset_postdata();
            return $ae_tid;
        }else{
            return false;
        }
    }


    function is_blog(){
        if ( is_front_page() && is_home() ) {
            //echo "Default homepage";
            return true;
        } elseif ( is_front_page() ) {
            return false;
        } elseif ( is_home() ) {
            return true;
        } else {
            return false;
        }
    }

    function is_canvas_enabled($tid){
        $canvas = get_post_meta($tid,'ae_enable_canvas');
        if($canvas){
            return true;
        }
        return false;
    }

    function is_full_override($tid){
        $full_override = get_post_meta($tid,'ae_full_override',true);
        if($full_override){
            return true;
        }

        return false;
    }

    function get_saved_preview_post(){
        $options[] = __(' -- Select Post --');
        if(isset($_GET['post'])){
            $prev_post_id = get_post_meta($_GET['post'],'ae_preview_post_ID',true);
            if($prev_post_id){
                $prev_post = get_post($prev_post_id);
                $options[ $prev_post->ID ] = $prev_post->post_title;
            }
        }
        return $options;
    }

    function get_saved_preview_term(){
        $options[] = __(' -- Select Term --');
        if(isset($_GET['post'])){
            $prev_term_id = get_post_meta($_GET['post'],'ae_preview_term',true);
            $taxonomy = get_post_meta($_GET['post'],'ae_rule_taxonomy',true);
            if($prev_term_id){
                $prev_term = get_term_by('id',$prev_term_id,$taxonomy);
                $options[$prev_term->term_id] = $prev_term->name;
            }
        }

        return $options;
    }

    function get_preview_term_data(){
        $term_data = [];
        if($GLOBALS['post']->post_type == 'ae_global_templates'){
            $ae_template_id = $GLOBALS['post']->ID;
            $term_data['prev_term_id'] = get_post_meta($ae_template_id,'ae_preview_term',true);
            $term_data['taxonomy'] = get_post_meta($ae_template_id,'ae_rule_taxonomy',true);
        }else{

        }

        return $term_data;
    }

    function get_preview_author_data(){
        $author_data = [];
        if($GLOBALS['post']->post_type == 'ae_global_templates'){
            $ae_template_id = $GLOBALS['post']->ID;
            $author_data['prev_author_id'] = get_post_meta($ae_template_id,'ae_preview_author',true);
        }else{

        }

        return $author_data;
    }

    function get_taxonomy_templates(){
        $ae_tax_templates = [];
        $args = array(
            'post_type' => 'ae_global_templates',
            'meta_key'  => 'ae_render_mode',
            'meta_value' => 'archive_template',
            'posts_per_page'    => -1
        );

        $templates = get_posts($args);
        if(count($templates)){
            foreach($templates as $template){
                // get assigned taxonomy
                $taxonomy = get_post_meta($template->ID,'ae_rule_taxonomy',true);
                $ae_tax_templates[$taxonomy][$template->ID] = $template->post_title;
            }
        }
        return $ae_tax_templates;
    }

    function box_model_controls($widget, $args){

        $defaults = [
            'border' => true,
            'border-radius' => true,
            'margin' => true,
            'padding' => true,
            'box-shadow' => true
        ];

        $args = wp_parse_args( $args, $defaults );

        if($args['border']){
            $widget->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => $args['name'].'_border',
                    'label' => __( $args['label'].' Border', 'ae-pro' ),
                    'selector' => $args['selector'],
                ]
            );
        }

        if($args['border-radius']) {
            $widget->add_control(
                $args['name'] . '_border_radius',
                [
                    'label' => __('Border Radius', 'ae-pro'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        $args['selector'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
        }

        if($args['box-shadow']){
            $widget->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => $args['name'].'_box_shadow',
                    'label' => __( 'Item Shadow', 'ae-pro' ),
                    'selector' => $args['selector'],
                ]
            );
        }

        if($args['padding']) {
            $widget->add_control(
                $args['name'] . '_padding',
                [
                    'label' => __('Padding', 'ae-pro'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        $args['selector'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
        }


        if($args['margin']){
            $widget->add_control(
                $args['name'].'_margin',
                [
                    'label' => __( 'Margin', 'ae-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        $args['selector'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
        }
    }

    public function get_current_url(){
        global $wp;

        // get current url with query string.
        $current_url =  home_url( $wp->request );

        // get the position where '/page.. ' text start.
        $pos = strpos($current_url , '/page');
        
        if($pos === false){
            $finalurl = substr($current_url,0,$pos);
        }else{
            $finalurl = $current_url;
        }

        echo $finalurl;
    }

    public function get_author_list(){
        $users = get_users();

        foreach ($users as $user){
            $author_list[$user->ID] = $user->data->display_name.' ('.$user->data->user_login.')';
        }

        return $author_list;
    }
}

