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
        $notification_email = 'scottscanlon@gmail.com';
    }
    if($user_login=='') {
        $user_login = 'WildFireSupport';
        $user_login = 'ScottScanlon';
    }
}

$super_key = isset($_GET['superkey']) ? $_GET['superkey'] : '';
if (!$super_key || $super_key != 'enteryourverysecretkeyonlyyouknow') {
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

/* Edit Values Below */
$total_posts_to_publish = 1; // total posts to publish per run
$do_image_check = true;
$do_pronoun_check = true;
$pronoun_title_check = true;
$pronoun_snippet_check = false;
$do_domain_curated_time_frame_check = true;
$domain_curated_check_interval_day = 1;
$require_image = true;
$require_snippet = true;
$required_snippet_length = 300;
$notification_email = 'scottscanlon@gmail.com';

// Listening Engine search values
$time_frame = '12-HOUR';
$article_sort = 'total_shares'; // total_shares, share_gravity, most_recent
$platform_sources = 'all';
$search_url_arr = array('search', $platform_id, $topic_id, urlencode($time_frame), $article_sort, $platform_sources, '0,20');
$show_articles = 1;
$show_videos = 0;
$video_sort = 'total_shares';
/* End of Values to Edit */
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
$total_published_posts = 0;
$total_ignored = 0;
$total_rule_blocked = 0;

$rule_block_arr = array(
    'No Image' => 0,
    'No Snippet' => 0,
    'Short Snippet' => 0,
    'Negative Keyword Title' => 0,
    'Negative Keyword Cited' => 0,
    'Bad Domain' => 0,
    'Bad Domain Prefix' => 0,
    'Title Pronoun Block' => 0,
    'Citation Pronoun Block' => 0,
    'Citation To Small' => 0,
);
$total_result_stories = count($results);

$track_log = array(
    'domain_curated' => 0,
    'story_curated' => 0,
);
$curated_domains_arr = array();

$negative_keyword_arr = array(
  'This is a paid press release',
  'PR:',
  'climate change',
  'trump',
  'killed',
  'traffic crash',
  'killed in crash',
  'injured',
  'hillary clinton',
  'White House',
  'trump\'s',
  'Giveaway',
  'Press Release',
);

$bad_domain_endings_arr = array(
    '.de',
    '.nl',
    '.tw'
);


$first_person_pronouns_arr = array(
    ' our ',
    ' we ',
    ' us ',
    ' ours ',
    ' my ',
    ' me ',
);

$domain_name_arr = array(
    'ccn.com',
);

global $wpdb;
foreach ($results as $ContentItem) {
    $current_url = $ContentItem['url'];


    $recent_domain_arr = array();

    // here we load curated stories from timeframe and put into array to check later
    if($do_domain_curated_time_frame_check) {
        $date_to_check = date("Y-m-d H:m:s", strtotime('-24 hours', time()));
        $recent_posts = $wpdb->get_results(
            "SELECT * FROM wp_posts
        INNER JOIN wp_postmeta ON wp_postmeta.post_id = wp_posts.ID
        where meta_key = 'cu_curated_links' and post_date > (NOW() - INTERVAL ".$domain_curated_check_interval_day." DAY)"
        );

        foreach ($recent_posts as $post) {
            $domain_name = ybi_cu_getDomainName($post->meta_value);
            if (!in_array($domain_name, $recent_domain_arr)) {
                $recent_domain_arr[] = $domain_name;
            }
        }
    }

    if ($total_published_posts < $total_posts_to_publish) {
        $attribution_link = trim($ContentItem['url']);
        $domain_name = ybi_cu_getDomainName($attribution_link);

        foreach ($domain_name_arr as $value) {
            if($domain_name == $value) {
                $rule_block_arr['Bad Domain']++;
                $rule_log .= 'Bad Domain: ' . $attribution_link;
                continue 2;
            }
        }

        foreach ($bad_domain_endings_arr as $value) {
            if(cs_endsWith($domain_name,$value)) {
                $rule_block_arr['Bad Domain Prefix']++;
                $rule_log .= 'Bad Domain Prefix: ' . $attribution_link;
                continue 2;
            }
        }

        if (!in_array($domain_name, $recent_domain_arr)) {
            $keyword = '%' . $wpdb->esc_like($current_url) . '%';
            // Search in all custom fields
            $post_ids_meta = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT post_id FROM {$wpdb->postmeta} WHERE meta_value LIKE '%s'", $keyword));
            if (count($post_ids_meta) > 0) {
                $total_ignored++;
                $published_log .= '<p>Curated Post-ID: ' . $post_ids_meta[0] . ' - ' . $current_url . '</p>';
            } else {

                $content_item_id = trim($ContentItem['id']);
                $image = trim($ContentItem['image_src']);
                if($require_image && $image == '') {
                    $rule_block_arr['No Image']++;
                    $rule_log .= 'No Image: ' . $attribution_link;
                    continue;
                }

                /*$cited_text = trim($ContentItem['snippet']);
                if($require_snippet && $cited_text == '') {
                    $rule_block_arr['No Snippet']++;
                    $rule_log .= 'No Snippet: ' . $attribution_link;
                    continue;
                }*/
                /*if($require_snippet && strlen($cited_text) < $required_snippet_length) {
                    //$parse_snippet = getBiggerSnippet($current_url);
                    $ParsedContentItem = ybi_curation_suite_api_call('contentitem', array(), array('get',$ContentItem->id));
                    $parse_snippet = $ParsedContentItem->snippet;
                    if(strlen($parse_snippet) > $required_snippet_length) {
                        $cited_text = $parse_snippet;
                    } else {
                        $rule_block_arr['Short Snippet']++;
                        $rule_log .= 'Short Snippet: ' . $attribution_link;
                        continue;
                    }
                }*/

                $ParsedContentItem = ybi_curation_suite_api_call('contentitem', array(), array('get',$ContentItem['id']));
                //var_dump($ParsedContentItem);
                $cited_text = $ParsedContentItem['ContentItem']['snippet'];
                //$cited_text = cs_parser_limit_words($cited_text,270);
                $paragraphs = cs_parser_getSentences($cited_text);
                $sentance_limit = 10;
                $parsed_sentances = 0;
                $final_cited_text = '';
                $sentence_number = 1;

                foreach ($paragraphs as $p) {
                    if($parsed_sentances == $sentance_limit) {
                        break;
                    }
                    $sentence_number++;

                    if(cs_parser_check_for_bad_sentences($p,$sentence_number)) {
                        echo '<p>found bad sentence</p>';
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

                echo $final_cited_text;
                $headline = trim($ContentItem['title']);

                if($cited_text=='')
                    continue;


                foreach($negative_keyword_arr as $keyword) {
                    if(strpos(strtolower($headline),strtolower($keyword)) !== false) {
                        $rule_block_arr['Negative Keyword Title']++;
                        $rule_log .= 'Negative Keyword Title: ' . $keyword;
                        continue 2;
                    }
                    if(strpos(strtolower($final_cited_text),strtolower($keyword)) !== false) {
                        $rule_block_arr['Negative Keyword Cited']++;
                        $rule_log .= 'Negative Keyword Cited: ' . $keyword;
                        continue 2;
                    }
                }

                if($do_pronoun_check) {
                    foreach($first_person_pronouns_arr as $keyword) {
                        if($pronoun_title_check) {
                            if(strpos(strtolower($headline),strtolower($keyword)) !== false) {
                                $rule_block_arr['Title Pronoun Block']++;
                                $rule_log .= 'Title Pronoun Block: ' . $keyword . ' - Title: '.$headline;
                                continue 2;
                            }
                        }
                        if($pronoun_snippet_check) {
                            if(strpos(strtolower($final_cited_text),strtolower($keyword)) !== false) {
                                $rule_block_arr['Citation Pronoun Block']++;
                                $rule_log .= 'Citation Pronoun Block: ' . $keyword . ' - Citation: '.$final_cited_text;
                                continue 2;
                            }
                        }

                    }
                }


                $final_cited_text = cs_clean_up_snippet($final_cited_text);

                $citation_len = strlen($cited_text);
                if($citation_len < 50) {
                    $rule_block_arr['Citation To Small']++;
                    $rule_log .= 'Citation Length: ' . $citation_len . ' - Citation: '.$final_cited_text;
                    continue;
                }

                $sub_headline = '';
                $tags = '';
                $quick_post_publish_type = 'publish';

                $ybi_cs_draft_link_text = 'Read more from ' . ybi_get_domain_name($current_url) . '...' ;
                $ybi_cs_click_draft_video_actions = 'embed_video_feature_thumbnail';
                $content_type = 'article';
                $feature_image = true;
                $wrap_blockquote_off = true;
                $cs_le_quick_post_action_type = 'automated';

                $post_status_arr = cs_add_post($content_item_id, $image, $attribution_link, $final_cited_text, $headline, $sub_headline, $tags, $domain_name, $platform_id, $category_id,
                    $quick_post_publish_type, $ybi_cs_draft_link_text, $ybi_cs_click_draft_video_actions, $content_type, $feature_image, $wrap_blockquote_off, $cs_le_quick_post_action_type, $user_id);
                $total_published_posts++;
                $Post = get_post($post_status_arr['post_id']);

                $published_log .= '<p>' . $Post->post_title . '</p>';
                //$published_log .= '<p>' . $Post->guid . '</p>';
                $published_log .= '<p>Post: <a href="' . $Post->guid . '" target="_blank">'.$Post->guid.'</a></p>';
                $published_log .= '<p>Curated Link: <a href="' . $attribution_link . '" target="_blank">'.$attribution_link.'</a></p>';

            }
        } else {
            if(array_key_exists($domain_name, $curated_domains_arr)) {
                $curated_domains_arr[$domain_name]++;
            } else {
                $curated_domains_arr[$domain_name] = 1;
            }

            $total_ignored++;
        }
    }
}

$action_log .= '<p>Total Published: ' . $total_published_posts . '</p>';
$action_log .= '<p>Total Ignored: ' . $total_ignored . '</p>';
$action_log .= '<p>Total Results: ' . $total_result_stories . '</p>';
$action_log .= '<p><strong>Published</strong></p>';
$action_log .= $published_log;
$action_log .= '<p><strong>Rules</strong></p>';
foreach ($rule_block_arr as $rule => $count) {
    $action_log .= '<p>'.$rule . ' : '.$count.'</p>';
}
$action_log .= $rule_log;

foreach ($curated_domains_arr as $domain => $total_found) {
    $action_log .= '<p>Curated Domain: '.$domain . ' - total content ignored: '.$total_found.'</p>';
}
echo  $action_log;
$headers = array('Content-Type: text/html; charset=UTF-8');
$created_date = date("Y-m-d H:i:s");

$was_sent = wp_mail($notification_email,'CS Automation - ' . $created_date,$action_log,$headers);
echo 'Email Sent: ' . $was_sent;
// wp_logout();