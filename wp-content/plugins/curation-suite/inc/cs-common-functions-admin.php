<?php
/**
 * Created by PhpStorm.
 * User: Scott-CM
 * Date: 8/8/2017
 * Time: 4:35 PM
 */

/**
 * this function is a shared function for the LE sort values. Requires font awesome which should be present in all our pages.
 *
 * @param bool $unicode return font awesome unicode values
 *
 * @return array key => value array with social sort and display elements
 */
function cs_le_get_video_sort_values($unicode = true)
{
    $return_arr = cs_le_get_sort_values($unicode);
    // fa-eye [&#xf06e;]
    if ($unicode) {
        return array_merge(array(
            'view_count' => '&#xf06e; Views',
            'like_count' => '&#xf164; Likes',
            'dislike_count' => '&#xf165; Dislikes',
            'comment_count' => '&#xf0e6; Comments'
        ), $return_arr);
    } else {
        return array_merge(array(
            'view_count' => '<i class="fa fa-eye total_share"></i> Views',
            'like_count' => '<i class="fa fa-thumbs-up share_gravity"></i> Likes',
            'dislike_count' => '<i class="fa fa-thumbs-down most_recent"></i> Dislikes',
            'comment_count' => '<i class="fa fa-comments-o facebook"></i> Comments',
        ), $return_arr);
    }
}

function cs_get_thickbox_link_video($video_id, $wrap = 'span', $class = '', $title = 'Video Tutorial', $link_text = '<i class="fa fa-video-camera" aria-hidden="true"></i> View Tutorial', $autoplay = true, $width = 560, $height = 315)
{
    $autoplay_text = '&autoplay=1';
    if (!$autoplay)
        $autoplay_text = '';

    return '<' . $wrap . ' class="cs_tutorial_link_wrap ' . $class . '"><a href="https://youtube.com/embed/' . $video_id . '?rel=0' . $autoplay_text . '&TB_iframe=true&autoplay=1&width=' . $width . '&height=' . $height . '" class="thickbox cs_tutorial_link" title="' . $title . '">' . $link_text . '</a></' . $wrap . '>';
}

function cs_html_get_tutorial_link($video_id, $text, $id = '', $show_icon = true)
{
    $id_html = '';
    if ($id != '')
        $id_html = ' id="' . $id . '"';

    $html = '<span class="cs_tutorial_link"' . $id_html . '><a class="cs_tutorial_popup" id="' . $video_id . '" href="javascript:;" title="Video Tutorial"> ';
    if ($show_icon)
        $html .= '<i class="fa fa-info-circle"></i> ';
    $html .= $text . '</a></span>';

    return $html;
}


function ybi_cu_get_post_type_feature()
{
    $options = get_option('curation_suite_data');
    $post_types = '';
    if (isset($options) && is_array($options)) {
        if (array_key_exists('curation_suite_custom_post_type', $options)){
            $post_types = $options['curation_suite_custom_post_type'];
        }
    }

    if ($post_types != '')
        $post_type_arr = array_map('trim', explode(',', $post_types));
    else
        $post_type_arr = array('post', 'page');

    return $post_type_arr;
}