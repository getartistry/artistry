<?php

use LivemeshAddons\Blocks\LAE_Blocks_Manager;

add_action('wp_ajax_lae_load_posts_block', 'lae_load_posts_block_callback');

add_action('wp_ajax_nopriv_lae_load_posts_block', 'lae_load_posts_block_callback');


function lae_load_posts_block_callback() {

    $ajax_parameters = array(
        'query' => '',            // original block atts
        'currentPage' => '',    // the current page of the block
        'blockId' => '',        // block uid
        'blockType' => '',         // the type of the block / block class
        'filterTerm' => '',     // the id for this specific filter type. The filter type is in the query
        'filterTaxonomy' => ''     // the id for this specific filter type. The filter type is in the query
    );


    if (!empty($_POST['blockId'])) {
        $ajax_parameters['blockId'] = $_POST['blockId'];
    }
    if (!empty($_POST['query'])) {
        $ajax_parameters['query'] = $_POST['query']; //current block args
    }
    if (!empty($_POST['settings'])) {
        $ajax_parameters['settings'] = $_POST['settings']; //current block args
    }
    if (!empty($_POST['taxonomies'])) {
        $ajax_parameters['taxonomies'] = $_POST['taxonomies']; //current block args
    }

    if (!empty($_POST['blockType'])) {
        $ajax_parameters['blockType'] = $_POST['blockType'];
    }
    if (!empty($_POST['currentPage'])) {
        $ajax_parameters['currentPage'] = intval($_POST['currentPage']);
    }
    //read the id for this specific filter type
    if (!empty($_POST['filterTerm'])) {

        //this removes the block offset for blocks pull down filter items
        //..it excepts the "All" filter tab which will load posts with the set offset
        if (!empty($ajax_parameters['query']['offset'])) {
            unset($ajax_parameters['query']['offset']);
        }
        $ajax_parameters['filterTerm'] = $_POST['filterTerm']; //the new id filter
    }

    if (!empty($_POST['filterTaxonomy'])) {

        $ajax_parameters['filterTaxonomy'] = $_POST['filterTaxonomy']; //the new id filter
    }

    if (!empty($ajax_parameters['query']))
        $query_params = lae_parse_block_query($ajax_parameters);


    if (!empty($_POST['settings']))
        $settings = lae_parse_block_settings($ajax_parameters['settings']);

    $loop = new \WP_Query($query_params);

    $block = LAE_Blocks_Manager::get_instance($ajax_parameters['blockType']);

    $output = $block->inner($loop->posts, $settings);

    //pagination
    $hidePrev = false;
    $hideNext = false;
    $remaining_posts = 0;

    if ($ajax_parameters['currentPage'] == 1) {
        $hidePrev = true; //hide link on page 1
    }

    if ($ajax_parameters['currentPage'] >= $loop->max_num_pages) {
        $hideNext = true; //hide link on last page
    }
    else {
        $remaining_posts = $loop->found_posts - ($query_params['paged'] * $query_params['posts_per_page']);
    }

    $outputArray = array(
        'data' => $output,
        'blockId' => $ajax_parameters['blockId'],
        'filterTerm' => $ajax_parameters['filterTerm'],
        'filterTaxonomy' => $ajax_parameters['filterTaxonomy'],
        'paged' => $query_params['paged'],
        'maxpages' => $loop->max_num_pages,
        'remaining' => $remaining_posts,
        'hidePrev' => $hidePrev,
        'hideNext' => $hideNext
    );

    echo json_encode($outputArray);

    wp_die();

}


function lae_parse_block_query($params) {

    $q = $params['query'];

    $q['ignore_sticky_posts'] = filter_var($q['ignore_sticky_posts'], FILTER_VALIDATE_INT);

    $q['posts_per_page'] = filter_var($q['posts_per_page'], FILTER_VALIDATE_INT);

    // go for the page requested by the user
    $q['paged'] = filter_var($params['currentPage'], FILTER_VALIDATE_INT);

    // Replace existing tax_query with filter term, if any
    if (!empty($params['filterTerm'])) {
        $q['tax_query'] = array(
            array(
                'field' => 'term_id',
                'taxonomy' => filter_var($params['filterTaxonomy'], FILTER_SANITIZE_STRING),
                'terms' => filter_var($params['filterTerm'], FILTER_VALIDATE_INT),
                'operator' => 'IN',
            )
        );
    }

    return $q;
}

function lae_parse_block_settings($settings) {

    $s = (array)$settings;

    $s['block_class'] = isset($s['block_class']) ? $s['block_class'] : '';

    $s['per_line'] = isset($s['per_line']) ? filter_var($s['per_line'], FILTER_VALIDATE_INT) : 3;

    $s['per_line1'] = isset($s['per_line1']) ? filter_var($s['per_line1'], FILTER_VALIDATE_INT) : 2;

    $s['per_line2'] = isset($s['per_line2']) ? filter_var($s['per_line2'], FILTER_VALIDATE_INT) : 4;

    $s['excerpt_length'] = isset($s['excerpt_length']) ? filter_var($s['excerpt_length'], FILTER_VALIDATE_INT) : 25;

    $s['layout_mode'] = isset($s['layout_mode']) ? $s['layout_mode'] : 'fitRows';

    $s['taxonomy_chosen'] = isset($s['taxonomy_chosen']) ? $s['taxonomy_chosen'] : $s['taxonomy_filter']; // backwards compatible

    $s['thumbnail_size_size'] = isset($s['thumbnail_size_size']) ? $s['thumbnail_size_size'] : 'large';

    $s['thumbnail_size_custom_dimension'] = isset($s['thumbnail_size_custom_dimension']) ? $s['thumbnail_size_custom_dimension'] : array();

    $s['filterable'] = isset($s['filterable']) ? filter_var($s['filterable'], FILTER_VALIDATE_BOOLEAN) : true;

    $s['show_remaining'] = isset($s['show_remaining']) ? filter_var($s['show_remaining'], FILTER_VALIDATE_BOOLEAN) : true;

    $s['image_linkable'] = isset($s['image_linkable']) ? filter_var($s['image_linkable'], FILTER_VALIDATE_BOOLEAN) : true;

    $s['post_link_new_window'] = isset($s['post_link_new_window']) ? filter_var($s['post_link_new_window'], FILTER_VALIDATE_BOOLEAN) : true;

    $s['enable_lightbox'] = isset($s['enable_lightbox']) ? filter_var($s['enable_lightbox'], FILTER_VALIDATE_BOOLEAN) : true;

    $s['display_title_on_thumbnail'] = isset($s['display_title_on_thumbnail']) ? filter_var($s['display_title_on_thumbnail'], FILTER_VALIDATE_BOOLEAN) : true;

    $s['display_taxonomy_on_thumbnail'] = isset($s['display_taxonomy_on_thumbnail']) ? filter_var($s['display_taxonomy_on_thumbnail'], FILTER_VALIDATE_BOOLEAN) : true;

    $s['display_title'] = isset($s['display_title']) ? filter_var($s['display_title'], FILTER_VALIDATE_BOOLEAN) : true;

    $s['display_summary'] = isset($s['display_summary']) ? filter_var($s['display_summary'], FILTER_VALIDATE_BOOLEAN) : true;

    $s['display_excerpt_lightbox'] = isset($s['display_excerpt_lightbox']) ? filter_var($s['display_excerpt_lightbox'], FILTER_VALIDATE_BOOLEAN) : true;

    $s['display_author'] = isset($s['display_author']) ? filter_var($s['display_author'], FILTER_VALIDATE_BOOLEAN) : true;

    $s['display_post_date'] = isset($s['display_post_date']) ? filter_var($s['display_post_date'], FILTER_VALIDATE_BOOLEAN) : true;

    $s['display_comments'] = isset($s['display_comments']) ? filter_var($s['display_comments'], FILTER_VALIDATE_BOOLEAN) : true;

    $s['display_read_more'] = isset($s['display_read_more']) ? filter_var($s['display_read_more'], FILTER_VALIDATE_BOOLEAN) : true;

    $s['display_taxonomy'] = isset($s['display_taxonomy']) ? filter_var($s['display_taxonomy'], FILTER_VALIDATE_BOOLEAN) : true;

    $s['heading_tag'] = isset($s['heading_tag']) ? $s['heading_tag'] : 'h3';

    $s['title_tag'] = isset($s['title_tag']) ? $s['title_tag'] : 'h3';

    $s['entry_title_tag'] = isset($s['entry_title_tag']) ? $s['entry_title_tag'] : 'h3';

    return $s;
}