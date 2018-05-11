<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
$get_user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
$get_user_name = isset($_GET['username']) ? $_GET['username'] : '';
$server_name = $_SERVER['SERVER_NAME'];
if ($server_name == 'localhost') {
    $user_id = 1;
    $user_login = 'admin';
} else {
    if($user_id=='') {
        $user_id = 21;
        $user_id = 6;
    }
    if($user_login=='') {
        $user_login = 'WildFireSupport';
        $user_login = 'ScottScanlon';
    }
}

$super_key = isset($_GET['superkey']) ? $_GET['superkey'] : '';
if (!$super_key || $super_key != 'thisismysuperkeyknowoneknows') {
    die('No Super key');
}
require_once('automate-functions.php');
$path = realpath(dirname(__FILE__));
$site_root = str_replace('\wp-content\plugins\curation-suite\automation', '', $path);
$site_root = str_replace('/wp-content/plugins/curation-suite/automation', '', $site_root);
require_once($site_root.'/wp-blog-header.php');

$secure_cookie = is_ssl();
wp_set_auth_cookie_for_cron($user_id, 1, $secure_cookie);
require($site_root . '/wp-admin/admin.php');
//echo $site_root;
$ybi_plugin_path = $site_root . '/wp-content/plugins/youbrandinc_products/';
require_once($site_root . '/wp-content/plugins/youbrandinc_products/vendor/autoload.php');
// require_once( $site_root. '/wp-content/plugins/curation-suite/curation-suite.php' );
require_once($site_root . '/wp-content/plugins/curation-suite/inc/admin-ajax.php');
require_once($site_root . '/wp-content/plugins/curation-suite/inc/le-api.php');
require_once($site_root . '/wp-content/plugins/curation-suite/inc/cs-common-functions-admin.php');
require_once($site_root . '/wp-content/plugins/curation-suite/inc/cs-common-functions-public.php');

$platform_id = isset($_GET['platform_id']) ? $_GET['platform_id'] : 23;
$topic_id = isset($_GET['topic_id']) ? $_GET['topic_id'] : 'all';
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : 0;


$time_frame = '12-HOUR';
$article_sort = 'total_shares'; // total_shares, share_gravity
$platform_sources = 'all';
$search_url_arr = array('search', $platform_id, $topic_id, urlencode($time_frame), $article_sort, $platform_sources, '0,20');
$show_articles = 1;
$show_videos = 0;
$video_sort = 'total_shares';
$param_arr = array('show_articles' => $show_articles, 'show_videos' => $show_videos, 'video_sort' => $video_sort, 'strict_date_limit'=> 1);
$data = ybi_curation_suite_api_call('', $param_arr, $search_url_arr);
// $data = json_decode($JSON, true);
// var_dump($data);
$total = 0;
if (array_key_exists('total', $data))
    $total = $data['total'];

$status = 'failure';
$api_status = '';
$api_message = '';
if (array_key_exists('status', $data)) {
    $status = $data['status'];
    $api_status = $data['status'];
    $api_message = $data['message'];
}

if ($status == 'success') {
    $results = $data['results'];
} else {

}
$action_log = '';
$published_log = '';
$rule_log = '';
$total_posts_to_publish = 1;
$total_published_posts = 0;
$total_ignored = 0;
$total_rule_blocked = 0;
$rule_block_arr = array(
    'No Image' => 0,
    'No Snippet' => 0,
    'Short Snippet' => 0,
    'Negative Keyword' => 0,
);
$total_result_stories = count($results);
$require_image = true;
$require_snippet = true;
$required_snippet_length = 300;

$track_log = array(
    'domain_curated' => 0,
    'story_curated' => 0,
);
$curated_domains_arr = array();

$negative_keyword_arr = array(
  'This is a paid press release',
  'PR:',
);

$cited_text = '';
$paragraphs = cs_parser_getSentences($cited_text);
$sentance_limit = 12;
$parsed_sentances = 0;
$final_cited_text = '';



foreach ($paragraphs as $p) {
    if($parsed_sentances == $sentance_limit) {
        break;
    }

    if(cs_parser_check_for_bad_sentences($p)) {
        echo '<p>found bad sentance</p>';
        continue;
    }

    if ($parsed_sentances != 0 && $parsed_sentances%2 == 0) {
        $final_cited_text .= '<p></p>';
    }
    if($parsed_sentances > 0) {
        $final_cited_text .= ' ';
    }
    $final_cited_text .= $p;
    $parsed_sentances++;
}