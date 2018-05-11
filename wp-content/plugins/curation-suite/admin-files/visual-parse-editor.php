<?php
/**
 * Created by PhpStorm.
 * User: Scott-CM
 * Date: 10/7/2015
 * Time: 10:08 AM
 */
define('IFRAME_REQUEST' , true);

// for certain installs we can't put the parsing files in the wp-admin folder, in that case we have a setting that calls it in the plugin dir
// since this file isn't included it needs to know where the admin.php file is so we pass it the homepath as a url parameter
$homepath = (isset($_GET['homepath']) ? $_GET['homepath'] : '');
echo $homepath;
if($homepath != '')
    require_once($homepath . '/wp-admin/admin.php' );
else
    require_once('./admin.php'); // files are in wp-admin folder so go up one dir and require the file

// check if we've turned on error reporting
$isErrorOn = get_option('ybi_turn_on_error_reporting') == 'on';
if($isErrorOn)
{
    ini_set('display_startup_errors',1);
    ini_set('display_errors',1);
    error_reporting(-1);
}

if ( ! current_user_can('edit_posts') )
    wp_die( __( 'Cheatin&#8217; uh, can you edit posts?' ) );



wp_enqueue_script( 'post' );
_wp_admin_html_begin();
do_action('admin_print_styles');
do_action('admin_print_scripts');
do_action('admin_head');
$url = 'http://www.adweek.com/socialtimes/5-tips-for-effective-integration-of-sales-and-content-marketing-infographic/626968';
$url = 'http://www.entrepreneur.com/article/251309';
require_once(YBI_CURATION_SUITE_PATH ."lib/web/simple_html_dom.php");

$html = new curationsuite\simple_html_dom();

require_once(YBI_CURATION_SUITE_PATH ."lib/web/http.php");
require_once(YBI_CURATION_SUITE_PATH ."lib/web/web_browser.php");
$web = new WebBrowser();
$result = $web->Process($url);
//var_dump($result);
// here we check to make sure we could access the page, if the result is success then we load this page
if($result['success'])
    $html->load($result["body"]);

foreach($html->find('p') as $a) {
    $a->setAttribute('draggable','true');
}
foreach($html->find('span') as $a) {
    $a->setAttribute('draggable','true');
}
foreach($html->find('div') as $a) {
    $a->setAttribute('draggable','true');
}

echo $html;


$html->clear();
unset($html);