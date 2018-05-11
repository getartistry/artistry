<?php
/**
 * Created by PhpStorm.
 * User: Scott-CM
 * Date: 10/3/2017
 * Time: 11:23 AM
 */

/**
 *  Adds an edit box to the post screen for the sub headline. Called by admin_footer hook
 *
 * @param $url
 * @param null $post
 * @return string $url
 */
function cs_sub_headlines_post_post_display( $url='', $post=null) {
    global $post;
    $do_action = false;
    $options = get_option('curation_suite_data');
    if (is_array($options)) {
        if (array_key_exists('curation_suite_sub_headlines', $options)) {
            if ($options['curation_suite_sub_headlines'] == 1) {
                $do_action = true;
            }
        }
    }

    if($do_action) {
        if($post) {
            if ( $post->post_type == 'post' ) {
                if(!is_singular( 'post' )) { // && in_the_loop()
                    $cs_sub_headline = get_post_meta($post->ID, 'cs_sub_headline', true);
                    // if it's curated video from youtube we display the post to keep user on site
                    if(is_admin()) {
                        echo "<script type='text/javascript'>
jQuery('#edit-slug-box').append('<div><strong>Sub Headline:</strong> <input id=\"cs_sub_headline_input\" type=\"text\" rel=\"cs_sub_headline\" value=\"".$cs_sub_headline."\" style=\"width: 70%;\" /><i> ' +
 '<i>* You have sub headlines turned on </i>' + ' </div>');</script>";
                    }
                }
            }
        }
    }
    return $url;
}

global $modified_headlines_arr;
$modified_headlines_arr= array();
/**
 * @param $title
 * @param null $id
 * @return string
 */
function ybi_css_add_sub_to_title($title, $id = null ) {

    global $post,$modified_headlines_arr,$wp;
    $do_action = false;
    $options = get_option('curation_suite_data');
    if (is_array($options)) {
        if (array_key_exists('curation_suite_sub_headlines', $options)) {
            if ($options['curation_suite_sub_headlines'] == 1) {
                $do_action = true;
            }
        }
    }
    if($do_action && in_the_loop() && (is_single() || is_front_page())) {
        $cs_sub_headline = get_post_meta($post->ID, 'cs_sub_headline', true);
        $sub_headline_wrap = 'h2';
        if ($cs_sub_headline && $cs_sub_headline != '') {
            if (array_key_exists('curation_suite_sub_headline_wrap_default', $options)) {
                $sub_headline_wrap = $options['curation_suite_sub_headline_wrap_default'];
            }
        }
        $modify_title = false;
        if(in_the_loop() && is_front_page()) {
            //curation_suite_sub_headline_position
            $sift = 0;
            if (array_key_exists('curation_suite_sub_headline_position', $options)) {
                $sift = $options['curation_suite_sub_headline_position'];
            }
            if($cs_sub_headline != '') {
                $query_vars = $wp->query_vars;
                if(array_key_exists('paged',$query_vars)) {
                    if($query_vars['paged'] > 1) {
                        //  $modify_title = true;
                    }
                }

                if(array_key_exists ($title,$modified_headlines_arr)) {
                    if(!$modify_title) {
                        $total = $modified_headlines_arr[$title];
                        $modified_headlines_arr[$title] = $total++;
                        if($total < $sift) {
                            $modified_headlines_arr[$title] = $total++;
                        }
                        if($total==$sift) {
                            $modified_headlines_arr[$title] = $total++;
                            $modify_title = true;
                        }
                    }
                } else {
                    $modified_headlines_arr[$title] = 0;
                    if($sift==0)
                        $modify_title = true;
                }
            }
        }
        if(is_single()) {
            if(!in_array($title,$modified_headlines_arr)) {
                $modify_title = true;
                $modified_headlines_arr[] = $title;
            }
        }
        if($modify_title) {
            $title = $title . '<'.$sub_headline_wrap.' class="cs_sub_headline">'.$cs_sub_headline.'</'.$sub_headline_wrap.'>';
        }
    }
    return $title;
}