<?php
/*
Plugin Name: Yellow Pencil Lite
Plugin URI: http://waspthemes.com/yellow-pencil
Description: The most advanced visual CSS editor. Customize any theme and any page in real-time without coding.
Version: 6.0.9
Author: WaspThemes
Author URI: http://www.waspthemes.com
*/


/* ---------------------------------------------------- */
/* Basic 												*/
/* ---------------------------------------------------- */
if (!defined('ABSPATH')) {
    die('-1');
}


/* ---------------------------------------------------- */
/* Check if lite version or not. 						*/
/* ---------------------------------------------------- */
if (strstr(__FILE__, "yellow-pencil-visual-theme-customizer")) {
    $lite_dir       = __FILE__;
    $pro_dir        = str_replace("yellow-pencil-visual-theme-customizer", "waspthemes-yellow-pencil", __FILE__);
} else {
    $pro_dir        = __FILE__;
    $lite_dir       = str_replace("waspthemes-yellow-pencil", "yellow-pencil-visual-theme-customizer", __FILE__);
}

// Checking if files exists
$pro_exists  = file_exists($pro_dir);
$lite_exists = file_exists($lite_dir);

// Define it if this is Pro installation
if($pro_exists){
    define("YP_PRO_DIRECTORY", TRUE);
}

// If pro version is there?
if ($pro_exists == true && $lite_exists == true) {
    
    // Be sure deactivate_plugins function is exists
    if (!function_exists("deactivate_plugins")) {
        require_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }
    
    // deactivate Lite Version.
    deactivate_plugins(plugin_basename($lite_dir));
    
}

// Unlock all features
function yp_define_pro(){

    // Get purchase code from database
    $purchase_code = get_option("yp_purchase_code");

    // Has?
    if($purchase_code){

        if(!defined('WTFV')){

            define('WTFV',TRUE);
            
        }

    }

}
add_action("init","yp_define_pro");

// Generate Base Editor URL.
function yp_get_uri() {

    if (current_user_can("edit_theme_options") == true) {

        return admin_url('admin.php?page=yellow-pencil-editor');

    } elseif (defined('YP_DEMO_MODE')) {

        return add_query_arg(array(
            'yellow_pencil' => 'true'
        ), get_home_url() . '/');

    }

}


/* ---------------------------------------------------- */
/* Define 												*/
/* ---------------------------------------------------- */
define('WT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WT_PLUGIN_URL', plugin_dir_url(__FILE__));

define('YP_MODE', "min"); // min & dev.
define('YP_VERSION', "6.0.9");

// Admin Settings Page
include(WT_PLUGIN_DIR . 'library/php/admin.php');

// Editor Right Panel
include(WT_PLUGIN_DIR . 'library/php/panel.php');

// Check if it is demo mode
function yp_check_demo_mode() {
    
    // Demo mode avaiable for just non-logout users.
    if (defined('WT_DEMO_MODE') && is_user_logged_in() == false) {
        define('YP_DEMO_MODE', TRUE);
    }
    
}
add_action("init", "yp_check_demo_mode");



/* ---------------------------------------------------- */
/* Add animation ajax callback							*/
/* ---------------------------------------------------- */
function yp_add_animation() {
    
    if (current_user_can("edit_theme_options") == true) {
        
        $css  = wp_strip_all_tags($_POST['yp_anim_data']);
        $name = wp_strip_all_tags($_POST['yp_anim_name']);
        
        if (!update_option("yp_anim_" . $name, $css)) {
            add_option("yp_anim_" . $name, $css);
        }
        
    }
    
    wp_die();
    
}

add_action('wp_ajax_yp_add_animation', 'yp_add_animation');



/* ---------------------------------------------------- */
/* Get Translation Text Domain							*/
/* ---------------------------------------------------- */
function yp_plugin_lang() {
    load_plugin_textdomain('yp', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'yp_plugin_lang');



/* ---------------------------------------------------- */
/* GET UPDATE API                                       */
/* ---------------------------------------------------- */
// This is CodeCanyon update API and this requires just in Pro version.
// update-api.php file not available in lite version.
if(defined('WTFV')){
    require_once(WT_PLUGIN_DIR.'/library/php/update-api.php');
}



/* ---------------------------------------------------- */
/* Add a customize link in wp plugins page				*/
/* ---------------------------------------------------- */
function yp_customize_link($links, $file) {
    
    if ($file == plugin_basename(dirname(__FILE__) . '/yellow-pencil.php')) {

        $in = '<a href="' . admin_url('themes.php?page=yellow-pencil') . '">' . __('Customize', 'yp') . '</a>';
        array_unshift($links, $in);

        // Show GO PRO link if lite version
        if(!defined("WTFV")){
            $links["go_pro"] = '<a style="color: #39b54a;font-weight: 700;" href="' . esc_url('http://waspthemes.com/yellow-pencil/buy/') . '">' . __('Go Pro', 'yp') . '</a>';
        }

    }


    return $links;

}

add_filter('plugin_action_links', 'yp_customize_link', 10, 2);




/* ---------------------------------------------------- */
/* Get Font Families   									*/
/* ---------------------------------------------------- */
function yp_load_fonts() {
    $css = yp_get_css(true);
    yp_get_font_families($css, null);
}



/* ---------------------------------------------------- */
/* Getting font Families By CSS OUTPUT					*/
/* ---------------------------------------------------- */
// Type null = 'wp_enqueue_style'
// Type import = 'import'
// Type = wp_enqueue_style OR return @import CSS
function yp_get_font_families($css, $type) {
    
    $protocol = is_ssl() ? 'https' : 'http';
    
    preg_match_all('/font-family:(.*?);/', $css, $r);
    
    foreach ($r['1'] as &$k) {
        $k = yp_font_name($k);
    }
    
    $importArray = array();
    
    foreach (array_unique($r['1']) as $family) {
        
        $id = str_replace("+", "-", strtolower($family));
        
        $id = str_replace("\\", "", $id);
        
        if ($id == 'arial' || $id == 'helvetica' || $id == 'georgia' || $id == 'serif' || $id == 'helvetica-neue' || $id == 'times-new-roman' || $id == 'times' || $id == 'sans-serif' || $id == 'arial-black' || $id == 'gadget' || $id == 'impact' || $id == 'charcoal' || $id == 'tahoma' || $id == 'geneva' || $id == 'verdana' || $id == 'inherit') {
            return false;
        }
        
        if ($id == '' || $id == ' ') {
            return false;
        }
        
        // Getting fonts from google api.
        if ($type == null) {
            wp_enqueue_style($id, esc_url('' . $protocol . '://fonts.googleapis.com/css?family=' . $family . ':300,300italic,400,400italic,500,500italic,600,600italic,700,700italic'));
        } else {
            array_push($importArray, esc_url('' . $protocol . '://fonts.googleapis.com/css?family=' . $family . ':300,300italic,400,400italic,500,500italic,600,600italic,700,700italic'));
        }
        
    }
    
    if ($type != null) {
        return $importArray;
    }
    
}



/* ---------------------------------------------------- */
/* Finding Font Names From CSS data     				*/
/* ---------------------------------------------------- */
function yp_font_name($a) {
    
    $a = str_replace(array(
        
        "font-family:",
        '"',
        "'",
        " ",
        "+!important",
        "!important"
        
    ), array(
        
        "",
        "",
        "",
        "+",
        "",
        ""
        
    ), $a);
    
    if (strstr($a, ",")) {
        $array = explode(",", $a);
        return $array[0];
    } else {
        return $a;
    }
    
}



/* ---------------------------------------------------- */
/* Checking current user can or not						*/
/* ---------------------------------------------------- */
function yp_check_let() {
    
    // If Demo Mode
    if (defined('YP_DEMO_MODE') == true && isset($_GET['yellow_pencil_frame']) == true) {
        return true;
    }
    
    // If user can.
    if (current_user_can("edit_theme_options") == true) {
        return true;
    } else {
        return false;
    }
    
}



/* ---------------------------------------------------- */
/* Checking current user can or not (FOR FRAME)			*/
/* ---------------------------------------------------- */
function yp_check_let_frame() {
    
    // If Demo Mode
    if (defined('YP_DEMO_MODE') == true && isset($_GET['yellow_pencil_frame']) == true) {
        return true;
    }
    
    // Be sure, user can.
    if (current_user_can("edit_theme_options") == true && isset($_GET['yellow_pencil_frame']) == true) {
        return true;
    } else {
        return false;
    }
    
}



/* ---------------------------------------------------- */
/* Getting Last Post Title 								*/
/* ---------------------------------------------------- */
function yp_getting_last_post_title() {
    $last = wp_get_recent_posts(array(
        "numberposts" => 1,
        "post_status" => "publish"
    ));
    
    if (isset($last['0']['ID'])) {
        $last_id = $last['0']['ID'];
    } else {
        return false;
    }
    
    $title = get_the_title($last_id);
    
    if (strstr($title, " ")) {
        $words = explode(" ", $title);
        return $words[0];
    } else {
        return $title;
    }
    
}



/* ---------------------------------------------------- */
/* Clean protocol from URL 								*/
/* ---------------------------------------------------- */
function yp_urlencode($v) {
    $v = explode("://", urldecode($v));
    return urlencode($v[1]);
}



/* ---------------------------------------------------- */
/* Register Admin Script								*/
/* ---------------------------------------------------- */
function yp_enqueue_admin_pages($hook) {
    
    // Post pages.
    if ('post.php' == $hook) {
        wp_enqueue_script('yellow-pencil-admin', plugins_url('js/admin.js', __FILE__), 'jquery', '1.0', TRUE);
    }
    
    // Admin css
    wp_enqueue_style('yellow-pencil-admin', plugins_url('css/admin.css', __FILE__));
    
}

add_action('admin_enqueue_scripts', 'yp_enqueue_admin_pages');



/* ---------------------------------------------------- */
/* Register Plugin Styles For Iframe					*/
/* ---------------------------------------------------- */
function yp_styles_frame() {
    
    $protocol = is_ssl() ? 'https' : 'http';
    
    // Google web fonts.
    wp_enqueue_style('yellow-pencil-font', '' . $protocol . '://fonts.googleapis.com/css?family=Open+Sans:400,600,800');
    
    // Frame styles
    wp_enqueue_style('yellow-pencil-frame', plugins_url('css/frame.css', __FILE__));
    
    // animate library.
    wp_enqueue_style('yellow-pencil-animate', plugins_url('library/css/animate.css', __FILE__));
    
}



/* ---------------------------------------------------- */
/* Adding Link To Admin Appearance Menu					*/
/* ---------------------------------------------------- */
function yp_menu() {
    add_theme_page('Yellow Pencil Editor', 'Yellow Pencil Editor', 'edit_theme_options', 'yellow-pencil', 'yp_menu_function', 999);
}



/* ---------------------------------------------------- */
/* Appearance page Loading And Location					*/
/* ---------------------------------------------------- */
function yp_menu_function() {
    
    $yellow_pencil_uri = yp_get_uri();
    
    // Background
    echo '<div class="yp-bg"></div>';
    
    // Loader
    echo '';
    
    // Background and loader CSS
    echo '<style>html,body{display:none;}</style>';
    
    // Location..
    echo '<script type="text/javascript">window.location = "' . add_query_arg(array(
        'href' => yp_urlencode(get_home_url() . '/')
    ), $yellow_pencil_uri) . '";</script>';
    
    // Die
    exit;
    
}

add_action('admin_menu', 'yp_menu');



/* ---------------------------------------------------- */
/* Sub string after 18chars								*/
/* ---------------------------------------------------- */
function yp_get_short_title($title) {
    
    $title = ucfirst(strip_tags($title));
    
    if ($title == '') {
        $title = 'Untitled';
    }
    
    if (strlen($title) > 14) {
        return mb_substr($title, 0, 14, 'UTF-8') . '..';
    } else {
        return $title;
    }
    
}



/* ---------------------------------------------------- */
/* Getting All Title For Tooltip						*/
/* ---------------------------------------------------- */
function yp_get_long_tooltip_title($title) {
    
    $title = ucfirst(strip_tags($title));
    
    if ($title == '' || strlen($title) < 14) {
        return false;
    }
    
    if (strlen($title) > 14) {
        return $title;
    }
    
}



/* ---------------------------------------------------- */
/* Getting Custom Animations Codes						*/
/* ---------------------------------------------------- */
function yp_get_custom_animations() {
    
    $all_options = wp_load_alloptions();
    foreach ($all_options as $name => $value) {
        if (stristr($name, 'yp_anim')) {
            
            // Get animations
            $value = stripslashes(yp_css_prefix($value));
            $value = preg_replace('/\s+|\t/', ' ', $value);
            
            echo "\n" . '<style id="yp-animate-' . strtolower(str_replace("yp_anim_", "", $name)) . '">' . "\n" . '' . $value . "\n" . str_replace("keyframes", "-webkit-keyframes", $value) . '' . "\n" . '</style>';
            
        }
    }
    
}



/* ---------------------------------------------------- */
/* Getting CSS Codes									*/
/* ---------------------------------------------------- */
/*
yp get css(false) : echo output CSS
yp get css(true) : return just CSS Codes.
*/
function yp_get_css($r = false) {
    
    global $post;
    
    $onlyCSS         = '';
    $get_type_option = '';
    $get_post_meta   = '';
    
    global $wp_query;
    if (isset($wp_query->queried_object)) {
        $id = @$wp_query->queried_object->ID;
    } else {
        $id = null;
    }
    
    if (class_exists('WooCommerce')) {
        if (is_shop()) {
            $id = wc_get_page_id('shop');
        }
    }
    
    $get_option = get_option("wt_css");
    if ($id != null) {
        $get_type_option = get_option("wt_" . get_post_type($id) . "_css");
        $get_post_meta   = get_post_meta($id, '_wt_css', true);
    }
    
    if ($get_option == 'false') {
        $get_option = false;
    }
    
    if ($get_type_option == 'false') {
        $get_type_option = false;
    }
    
    if ($get_post_meta == 'false') {
        $get_post_meta = false;
    }
    
    if (empty($get_option) == false) {
        $onlyCSS .= $get_option;
    }
    
    if (empty($get_type_option) == false) {
        $onlyCSS .= $get_type_option;
    }
    
    if (empty($get_post_meta) == false) {
        $onlyCSS .= $get_post_meta;
    }
    
    if (is_author()) {
        $onlyCSS .= get_option("wt_author_css");
    } elseif (is_tag()) {
        $onlyCSS .= get_option("wt_tag_css");
    } elseif (is_category()) {
        $onlyCSS .= get_option("wt_category_css");
    } elseif (is_404()) {
        $onlyCSS .= get_option("wt_404_css");
    } elseif (is_search()) {
        $onlyCSS .= get_option("wt_search_css");
    }
    
    // home.
    if (is_front_page() && is_home()) {
        $onlyCSS .= get_option("wt_home_css");
    }
    
    
    if ($onlyCSS != '' && $r == false) {
        
        $return = '<style id="yellow-pencil">';
        $return .= "\r\n/*\r\n\tThe following CSS generated by Yellow Pencil Plugin.\r\n\thttp://waspthemes.com/yellow-pencil\r\n*/\r\n";
        
        // process
        $onlyCSS = stripslashes(yp_css_prefix(yp_animation_prefix(yp_hover_focus_match($onlyCSS))));
        
        // min and add
        $return .= str_replace(array(
            "\n",
            "\r",
            "\t"
        ), '', $onlyCSS);
        
        $return .= "\n" . '</style>';
        
        echo $return;
        
    }
    
    if ($r == true) {
        return $onlyCSS;
    }
    
}


// If is dynamic inline.
if (get_option('yp-output-option') != 'external') {
    
    // Adding all CSS codes to WP Head if not live preview and editor page.
    if (isset($_GET['yellow_pencil_frame']) == false && isset($_GET['yp_live_preview']) == false) {
        add_action('wp_head', 'yp_get_css', 9999);
    }
    
    // Adding all CSS animations to WP Head.
    if (isset($_GET['yellow_pencil_frame']) == false) {
        add_action('wp_head', 'yp_get_custom_animations', 9999);
    }
    
}


// Adding all CSS animations to WP Head.
if (isset($_GET['yp_live_preview']) == true && get_option('yp-output-option') == 'external') {
    add_action('wp_head', 'yp_get_custom_animations', 9999);
}



/* ---------------------------------------------------- */
/* Getting Live Preview CSS								*/
/* ---------------------------------------------------- */
function yp_get_live_css() {
    
    // Get recent generated CSS codes.
    $css = get_option('yp_live_view_css_data');
    
    if (empty($css)) {
        return $css;
    }
    
    return stripslashes(yp_css_prefix(yp_animation_prefix(yp_hover_focus_match($css))));
    
}



/* ---------------------------------------------------- */
/* Getting fonts for live preview						*/
/* ---------------------------------------------------- */
function yp_load_fonts_for_live() {
    $css = yp_get_live_css();
    yp_get_font_families($css, null);
}



/* ---------------------------------------------------- */
/* Generating Live Preview data 						*/
/* ---------------------------------------------------- */
function yp_get_live_preview() {
    
    $css = yp_get_live_css();
    
    if (empty($css) == false) {
        
        $css = '<style id="yp-live-preview">' . $css . '</style>';
        
        if ($css != '<style id="yp-live-preview"></style>') {
            echo $css;
        }
        
    }
    
}



/* ---------------------------------------------------- */
/* Adding generated live preview CSS data To WP HEAD	*/
/* ---------------------------------------------------- */
if (isset($_GET['yp_live_preview']) == true) {
    
    add_action('wp_head', 'yp_get_css_backend', 9999);
    add_action('wp_head', 'yp_get_live_preview', 9999);
    add_action('init', 'yp_out_mode', 9999);
    
}



/* ---------------------------------------------------- */
/* Hover/Focus System									*/
/* ---------------------------------------------------- */
/*
Replace 'body.yp-selector-hover' to hover.
replace 'body.yp-selector-focus' to focus.
replace 'body.yp-selector-link' to link.
replace 'body.yp-selector-active' to active.
replace 'body.yp-selector-visited' to visited.
*/
function yp_hover_focus_match($data) {
    
    preg_match_all('@body.yp-selector-(.*?){@si', $data, $keys);
    
    foreach ($keys[1] as $key) {
        
        $keyGet = substr($key, 0, 7);
        if ($keyGet == 'visited') {
            $keyQ = $keyGet;
        }
        
        $keyGet = substr($key, 0, 6);
        if ($keyGet == 'active') {
            $keyQ = $keyGet;
        }
        
        $keyGet = substr($key, 0, 4);
        if ($keyGet == 'link') {
            $keyQ = $keyGet;
        }
        
        $keyGet = substr($key, 0, 5);
        if ($keyGet == 'hover' || $keyGet == 'focus') {
            $keyQ = $keyGet;
        }
        
        $dir  = 'body.yp-selector-' . $key;
        $dirt = 'body.yp-selector-' . $key . ':' . $keyQ;
        
        $dirt = str_replace(array(
            'body.yp-selector-hover',
            'body.yp-selector-focus',
            'body.yp-selector-visited',
            'body.yp-selector-active',
            'yp-selector-link'
        ), array(
            'body',
            'body'
        ), $dirt);
        $data = (str_replace($dir, $dirt, $data));
    }
    
    $data = str_replace('.yp-selected', '', $data);
    
    return $data;
    
}



/* ---------------------------------------------------- */
/* Adding Prefix To Some CSS Rules						*/
/* ---------------------------------------------------- */
function yp_css_prefix($outputCSS) {
    
    $outputCSS = preg_replace('@\t-webkit-(.*?):(.*?);@si', "", $outputCSS);
    
    // Adding automatic prefix to output CSS.
    $CSSPrefix = array(
        "animation-name",
        "animation-fill-mode",
        "animation-iteration-count",
        "animation-delay",
        "animation-duration",
        "filter",
        "box-shadow",
        "box-sizing",
        "transform",
        "transition"
    );
    
    
    // CSS rules
    foreach ($CSSPrefix as $prefix) {
        
        // Webkit and o
        if ($prefix != 'filter' && $prefix != 'transform') {
            
            $outputCSS = preg_replace('@' . $prefix . ':([^\{]+);@U', "" . $prefix . ":$1;\r	-o-" . $prefix . ":$1;\r	-webkit-" . $prefix . ":$1;", $outputCSS);
            
        } else { // webkit ms moz and o
            
            $outputCSS = preg_replace('@' . $prefix . ':([^\{]+);@U', "" . $prefix . ":$1;\r	-o-" . $prefix . ":$1;\r	-moz-" . $prefix . ":$1;\r	-webkit-" . $prefix . ":$1;", $outputCSS);
            
        }
        
    }
    
    return $outputCSS;
    
}



/* ---------------------------------------------------- */
/* Prefix for Animations								*/
/* ---------------------------------------------------- */
function yp_animation_prefix($outputCSS) {
    
    return str_replace(array(
        
        ".yp_focus:focus",
        ".yp_focus:hover",
        ".yp_hover:hover",
        ".yp_hover:focus",
        ".yp_onscreen:hover",
        ".yp_onscreen:focus",
        ".yp_click:hover",
        ".yp_click:focus",
        ".yp_hover",
        ".yp_focus"
        
    ), array(
        
        ":focus",
        ":focus",
        ":hover",
        ":hover",
        ".yp_onscreen",
        ".yp_onscreen",
        ".yp_click",
        ".yp_click",
        ":hover",
        ":focus"
        
    ), $outputCSS);
    
}



/* ---------------------------------------------------- */
/* Prefix for Animations EXPORT							*/
/* ---------------------------------------------------- */
function yp_export_animation_prefix($outputCSS) {
    
    return str_replace(array(
        
        ".yp_onscreen",
        ".yp_click",
        ".yp_hover",
        ".yp_focus"
        
    ), array(
        
        "",
        ".yp_click",
        ":hover",
        ":focus"
        
    ), $outputCSS);
    
}



/* ---------------------------------------------------- */
/* Adding no-index meta to head for demo mode YP Links!	*/
/* ---------------------------------------------------- */
function yp_head_meta() {
    echo '<meta name="robots" content="noindex, follow">' . "\n";
}



/* ---------------------------------------------------- */
/* Getting CSS data	for Backend							*/
/* ---------------------------------------------------- */
function yp_get_css_backend() {
    
    global $post;
    
    $get_type_option = '';
    $get_post_meta   = '';
    
    global $wp_query;
    if (isset($wp_query->queried_object)) {
        $id = @$wp_query->queried_object->ID;
    } else {
        $id = null;
    }
    
    $id_is   = isset($_GET['yp_id']);
    $type_is = isset($_GET['yp_type']);
    
    $return = '<style id="yellow-pencil-backend">';
    
    $get_option = get_option("wt_css");
    if ($id != null) {
        $get_type_option = get_option("wt_" . get_post_type($id) . "_css");
        $get_post_meta   = get_post_meta($id, '_wt_css', true);
    }
    
    if ($get_option == 'false') {
        $get_option = false;
    }
    
    if ($get_type_option == 'false') {
        $get_type_option = false;
    }
    
    if ($get_post_meta == 'false') {
        $get_post_meta = false;
    }
    
    if (empty($get_option) == false) {
        
        if ($id_is == true || $type_is == true) {
            $return .= $get_option;
        }
        
    }
    
    if (empty($get_type_option) == false) {
        
        if ($type_is == false) {
            $return .= $get_type_option;
        }
        
    }
    
    if (empty($get_post_meta) == false) {
        
        if ($id_is == false) {
            $return .= $get_post_meta;
        }
        
    }
    
    if ($type_is == false) {
        
        if (is_author()) {
            $return .= get_option("wt_author_css");
        } elseif (is_tag()) {
            $return .= get_option("wt_tag_css");
        } elseif (is_category()) {
            $return .= get_option("wt_category_css");
        } elseif (is_404()) {
            $return .= get_option("wt_404_css");
        } elseif (is_search()) {
            $return .= get_option("wt_search_css");
        }
        
        // home.
        if (is_front_page() && is_home()) {
            $return .= get_option("wt_home_css");
        }
        
    }
    
    $return .= '</style>';
    
    
    if ($return != '<style id="yellow-pencil-backend"></style>') {
        echo stripslashes($return);
    }
    
}


// Shows the frame as visitor to logged user.
function yp_out_mode() {
    
    if (isset($_GET['yp_out']) && current_user_can("edit_theme_options")) {
        wp_set_current_user(-1);
    }
    
}


/* ---------------------------------------------------- */
/* Adding other CSS Data to Editor frame				*/
/* ---------------------------------------------------- */
if (isset($_GET['yellow_pencil_frame']) == true) {
    add_action('wp_head', 'yp_get_css_backend', 9998);
    add_action('wp_head', 'yp_head_meta', 9997);
    add_action('init', 'yp_out_mode', 9996);
}



/* ------------------------------------------------------------------- */
/* Other CSS Codes (All CSS Codes excluding current editing type CSS)  */
/* ------------------------------------------------------------------- */
function yp_editor_styles() {
    
    global $post;
    
    $get_type_option = '';
    $get_post_meta   = '';
    
    global $wp_query;
    if (isset($wp_query->queried_object)) {
        $id = @$wp_query->queried_object->ID;
    } else {
        $id = null;
    }
    
    if (class_exists('WooCommerce')) {
        if (is_shop()) {
            $id = wc_get_page_id('shop');
        }
    }
    
    $id_is   = isset($_GET['yp_id']);
    $type_is = isset($_GET['yp_type']);
    
    $return = '<div class="yp-styles-area">';
    
    $get_option = get_option("wt_styles");
    
    if ($id != null) {
        $get_type_option = get_option("wt_" . get_post_type($id) . "_styles");
        $get_post_meta   = get_post_meta($id, '_wt_styles', true);
    }
    
    if (empty($get_option) == false) {
        
        if ($id_is == false && $type_is == false) {
            $return .= $get_option;
        }
        
    }
    
    if (empty($get_type_option) == false) {
        
        if ($type_is == true) {
            $return .= $get_type_option;
        }
        
    }
    
    if (empty($get_post_meta) == false) {
        
        if ($id_is == true) {
            $return .= $get_post_meta;
        }
        
    }
    
    if ($type_is == true) {
        
        $type = trim(strip_tags($_GET['yp_type']));
        
        if ($type == 'author') {
            $return .= get_option("wt_author_styles");
        }
        
        if ($type == 'tag') {
            $return .= get_option("wt_tag_styles");
        }
        
        if ($type == 'category') {
            $return .= get_option("wt_category_styles");
        }
        
        if ($type == '404') {
            $return .= get_option("wt_404_styles");
        }
        
        if ($type == 'search') {
            $return .= get_option("wt_search_styles");
        }
        
        if ($type == 'home') {
            $return .= get_option("wt_home_styles");
        }
        
        
    }
    
    $return .= '</div>';
    
    $animations = '';
    
    $all_options = wp_load_alloptions();
    foreach ($all_options as $name => $value) {
        if (stristr($name, 'yp_anim')) {
            $animations .= $value;
        }
    }
    
    $return .= '<div class="yp-animate-data"><style>' . $animations . '</style></div>';
    
    echo stripslashes($return);
    
}




/* ---------------------------------------------------- */
/* Adding styles to Editor 								*/
/* ---------------------------------------------------- */
if (isset($_GET['yellow_pencil_frame']) == true) {
    add_action('wp_footer', 'yp_editor_styles');
}




/* ---------------------------------------------------- */
/* Include options Library								*/
/* ---------------------------------------------------- */
include_once(WT_PLUGIN_DIR . 'base.php');




/*-------------------------------------------------------*/
/*	Ajax Preview Save CallBack							 */
/*-------------------------------------------------------*/
function yp_preview_data_save() {
    
    if (current_user_can("edit_theme_options") == true) {
        
        $css = wp_strip_all_tags($_POST['yp_data']);
        
        if (!update_option('yp_live_view_css_data', $css)) {
            add_option('yp_live_view_css_data', $css);
        }
        
    }
    
    wp_die();
    
}

add_action('wp_ajax_yp_preview_data_save', 'yp_preview_data_save');



/*-------------------------------------------------------*/
/*	Creating an Custom.css file (Static)				 */
/*-------------------------------------------------------*/
function yp_create_custom_css($data) {
    
    // Revisions
    $rev = get_option('yp_revisions');
    
    if ($rev == false) {
        $rev = 700;
    }
    
    // Delete old revision if exists.
    if (file_exists(WT_PLUGIN_DIR . 'custom-' . ($rev - 1) . '.css')) {
        wp_delete_file(WT_PLUGIN_DIR . 'custom-' . ($rev - 1) . '.css');
    }
    
    // get the upload directory and make a test.txt file
    $filename = WT_PLUGIN_DIR . 'custom-' . $rev . '.css';
    
    // by this point, the $wp_filesystem global should be working, so let's use it to create a file
    global $wp_filesystem;
    
    // Initialize the WP filesystem, no more using 'file-put-contents' function
    if (empty($wp_filesystem)) {
        require_once(ABSPATH . '/wp-admin/includes/file.php');
        WP_Filesystem();
    }
    
    if (!$wp_filesystem->put_contents($filename, $data, FS_CHMOD_FILE)) {
        echo 'error saving file!';
    }
    
}


/*-------------------------------------------------------*/
/*	Ajax Real Save Callback								 */
/*-------------------------------------------------------*/
function yp_ajax_save() {
    
    if (current_user_can("edit_theme_options") == true) {
        
        // Revisions
        $currentRevision = get_option('yp_revisions');
        
        // Update revision.
        if ($currentRevision != false) {
            update_option('yp_revisions', $currentRevision + 1);
        } else {
            add_option('yp_revisions', "1");
        }
        
        $css = wp_strip_all_tags($_POST['yp_data']);
        
        $styles = trim(wp_kses_post($_POST['yp_editor_data']));

        $styles = str_replace("YP|@", "<", $styles);
        $styles = str_replace("YP@|", ">", $styles);
        
        $id   = '';
        $type = '';
        
        if (isset($_POST['yp_id'])) {
            $id = intval($_POST['yp_id']);
        }
        
        if (isset($_POST['yp_stype'])) {
            $type = trim(strip_tags($_POST['yp_stype']));
            if (count(explode("#", $type)) == 2) {
                $type = explode("#", $type);
                $type = $type[0];
            }
        }
        
        if ($id == 'undefined') {
            $id = '';
        }
        if ($type == 'undefined') {
            $type = '';
        }
        if ($css == 'undefined') {
            $css = '';
        }
        
        if ($id == '' && $type == '') {
            
            // CSS Data
            if (empty($css) == false) {
                if (!update_option('wt_css', $css)) {
                    add_option('wt_css', $css);
                }
            } else {
                delete_option('wt_css');
            }
            
            // Styles
            if (empty($css) == false) {
                if (!update_option('wt_styles', $styles)) {
                    add_option('wt_styles', $styles);
                }
            } else {
                delete_option('wt_styles');
            }
            
        } elseif ($type == '') {
            
            // CSS Data
            if (empty($css) == false) {
                if (!update_post_meta($id, '_wt_css', $css)) {
                    add_post_meta($id, '_wt_css', $css, true);
                }
            } else {
                delete_post_meta($id, '_wt_css');
            }
            
            // Styles
            if (empty($css) == false) {
                if (!update_post_meta($id, '_wt_styles', $styles)) {
                    add_post_meta($id, '_wt_styles', $styles, true);
                }
            } else {
                delete_post_meta($id, '_wt_styles');
            }
            
        } else {
            
            // CSS Data
            if (empty($css) == false) {
                if (!update_option('wt_' . $type . '_css', $css)) {
                    add_option('wt_' . $type . '_css', $css);
                }
            } else {
                delete_option('wt_' . $type . '_css');
            }
            
            // Styles
            if (empty($css) == false) {
                if (!update_option('wt_' . $type . '_styles', $styles)) {
                    add_option('wt_' . $type . '_styles', $styles);
                }
            } else {
                delete_option('wt_' . $type . '_styles');
            }
            
        }
        
        // Get All CSS data as ready-to-use
        $output = yp_get_export_css("create");
        
        // Update custom.css file
        yp_create_custom_css($output);
        
    }
    
    wp_die();
    
}

add_action('wp_ajax_yp_ajax_save', 'yp_ajax_save');



/* ---------------------------------------------------- */
/* Arrow icon Markup        							*/
/* ---------------------------------------------------- */
function yp_arrow_icon() {
    return "<span class='dashicons yp-arrow-icon dashicons-arrow-up'></span><span class='dashicons yp-arrow-icon dashicons-arrow-down'></span>";
}



/* ---------------------------------------------------- */
/* Getting current theme/page name                      */
/* ---------------------------------------------------- */
function yp_customizer_name() {
    
    if (isset($_GET['yp_id']) == true) {
        
        // The id.
        $id = intval($_GET['yp_id']);
        
        $title = get_the_title($id);
        $slug  = ucfirst(get_post_type($id));
        
        if (strlen($title) > 14) {
            
            return '"' . mb_substr($title, 0, 14, 'UTF-8') . '..' . '" ' . $slug . '';
        } else {
            if ($title == '') {
                $title = 'Untitled';
            }
            return '"' . $title . '" ' . $slug . '';
        }
        
    } elseif (isset($_GET['yp_type']) == true) {
        
        // The id.
        $type = ucfirst(trim(strip_tags($_GET['yp_type'])));
        
        if ($type == 'Page' || $type == 'Author' || $type == 'Search' || $type == '404' || $type == 'Category') {
            $title = '' . $type . ' ' . __("Template", "yp") . '';
        } else {
            $title = '' . __("Single", "yp") . ' ' . $type . ' ' . __("Template", "yp") . '';
        }
        
        if ($type == 'Home') {
            $title = __('Home Page', 'yp');
        }
        
        if ($type == 'Page') {
            $title = __('Default Page Template', 'yp');
        }
        
        return $title;
        
    } else {
        
        $yp_theme = wp_get_theme();
        
        // Replace 'theme' word from theme name.
        $name = str_replace(' theme', '', $yp_theme->get('Name'));
        $name = str_replace(' Theme', '', $name);
        $name = str_replace('theme', '', $name);
        $name = str_replace('Theme', '', $name);
        
        // Keep it short.
        if (strlen($name) > 10) {
            return '"' . mb_substr($name, 0, 10, 'UTF-8') . '.." ' . __("Theme", 'yp') . ' (Global)';
        } else {
            if ($name == '') {
                $name = __('Untitled', 'yp');
            }
            return '"' . $name . '" ' . __("Theme", 'yp') . '  (Global)';
        }
        
    }
    
}



/* ---------------------------------------------------- */
/* Adding helper style for wp-admin-bar					*/
/* ---------------------------------------------------- */
function yp_yellow_pencil_style() {
    echo '<style>#wp-admin-bar-yellow-pencil > .ab-item:before{content: "\f309";top:2px;}#wp-admin-bar-yp-update .ab-item:before{content: "\f316";top:3px;}</style>';
}


/* ---------------------------------------------------- */
/* Adding menu to wp-admin-bar							*/
/* ---------------------------------------------------- */
function yp_yellow_pencil_edit_admin_bar($wp_admin_bar) {
    
    $id = null;
    global $wp_query;
    global $wp;
    $yellow_pencil_uri = yp_get_uri();
    
    if (isset($_GET['page_id'])) {
        $id = intval($_GET['page_id']);
    } elseif (isset($_GET['post']) && is_admin() == true) {
        $id = intval($_GET['post']);
    } elseif (isset($wp_query->queried_object) == true) {
        $id = @$wp_query->queried_object->ID;
    }
    
    $args = array(
        'id' => 'yellow-pencil',
        'title' => __('Edit With Yellow Pencil', 'yp'),
        'href' => '',
        'meta' => array(
            'class' => 'yellow-pencil'
        )
    );
    $wp_admin_bar->add_node($args);
    
    $args = array();
    
    // Since 4.5.2
    // category,author,tag, 404 and archive page support.
    $status  = get_post_type($id);
    $key     = get_post_type($id);
    $go_link = get_permalink($id);
    
    if (is_author()) {
        $status  = __('Author', 'yp');
        $key     = 'author';
        $id      = $wp_query->query_vars['author'];
        $go_link = get_author_posts_url($id);
    } elseif (is_tag()) {
        $status  = __('Tag', 'yp');
        $key     = 'tag';
        $id      = $wp_query->query_vars['tag_id'];
        $go_link = get_tag_link($id);
    } elseif (is_category()) {
        $status  = __('Category', 'yp');
        $key     = 'category';
        $id      = $wp_query->query_vars['cat'];
        $go_link = get_category_link($id);
    } elseif (is_404()) {
        $status  = '404';
        $key     = '404';
        $go_link = esc_url(get_home_url() . '/?p=987654321');
    } elseif (is_archive()) {
        $status = __('Archive', 'yp');
        $key    = 'archive';
    } elseif (is_search()) {
        $status  = __('Search', 'yp');
        $key     = 'search';
        $go_link = esc_url(get_home_url() . '/?s=' . yp_getting_last_post_title() . '');
    }
    
    // Blog
    if (is_front_page() && is_home()) {
        $status  = __('Home Page', 'yp');
        $key     = 'home';
        $go_link = esc_url(get_home_url() . '/');
    } elseif (is_front_page() == false && is_home() == true) {
        $status = __('Page', 'yp');
    }
    
    if (class_exists('WooCommerce')) {
        
        if (is_shop()) {
            $id      = wc_get_page_id('shop');
            $status  = __('Page', 'yp');
            $key     = 'shop';
            $go_link = esc_url(get_permalink($id));
        }
        
        if (is_product_category() || is_product_tag()) {
            $id      = null;
            $go_link = add_query_arg($_SERVER['QUERY_STRING'], '', home_url($wp->request));
        }
        
    }
    
    if ($go_link == '') {
        $key     = '';
        $go_link = add_query_arg($wp->query_string, '', home_url($wp->request));
    }
    
    // null if zero.
    if ($id == 0) {
        $id = null;
    }
    
    // Edit theme
    array_push($args, array(
        'id' => 'yp-edit-theme',
        'title' => __('Global Customize', 'yp'),
        'href' => add_query_arg(array(
            'href' => yp_urlencode($go_link)
        ), $yellow_pencil_uri),
        'parent' => 'yellow-pencil'
    ));
    
    // Edit All similar
    if ($key != 'home' && $key != 'archive' && $key != '' && $key != 'shop') {
        
        if ($key != '404' && $key != 'search') {
            $s   = '\'s';
            $all = 'All ';
        } else {
            $s   = '';
            $all = '';
        }
        
        array_push($args, array(
            'id' => 'yp-edit-all',
            'title' => '' . __("Edit", 'yp') . ' ' . ucfirst($status) . ' ' . __("Template", 'yp') . '',
            'href' => add_query_arg(array(
                'href' => yp_urlencode($go_link),
                'yp_type' => $key
            ), $yellow_pencil_uri),
            'parent' => 'yellow-pencil',
            'meta' => array(
                'class' => 'first-toolbar-group'
            )
        ));
        
    }
    
    // Edit it.
    if ($key != 'search' && $key != 'archive' && $key != 'tag' && $key != 'category' && $key != 'author' && $key != '404' && $key != '') {
        
        if ($key == 'home') {
            
            array_push($args, array(
                'id' => 'yp-edit-it',
                'title' => '' . __("Edit", "yp") . ' ' . ucfirst($status) . ' only',
                'href' => add_query_arg(array(
                    'href' => yp_urlencode($go_link),
                    'yp_type' => $key
                ), $yellow_pencil_uri),
                'parent' => 'yellow-pencil'
            ));
        } else {
            
            array_push($args, array(
                'id' => 'yp-edit-it',
                'title' => '' . __("Edit This", 'yp') . ' ' . ucfirst($status) . '',
                'href' => add_query_arg(array(
                    'href' => yp_urlencode($go_link),
                    'yp_id' => $id
                ), $yellow_pencil_uri),
                'parent' => 'yellow-pencil'
            ));
            
        }
        
        
    }
    
    // Add to Wp Admin Bar
    for ($a = 0; $a < sizeOf($args); $a++) {
        $wp_admin_bar->add_node($args[$a]);
    }
    
    
}


/* ---------------------------------------------------- */
/* Adding Body Classes									*/
/* ---------------------------------------------------- */
function yp_body_class($classes) {
    
    $classes[] = 'yp-yellow-pencil wt-yellow-pencil';
    
    if (current_user_can("edit_theme_options") == false) {
        if (defined('YP_DEMO_MODE')) {
            $classes[] = 'yp-yellow-pencil-demo-mode';
        }
    }
    
    if (defined("WT_DISABLE_LINKS")) {
        $classes[] = 'yp-yellow-pencil-disable-links';
    }
    
    if (!defined('WTFV')) {
        $classes[] = 'wtfv';
    }
    
    return $classes;
    
}



/* ---------------------------------------------------- */
/* Install the plugin									*/
/* ---------------------------------------------------- */
function yp_init() {
    
    
    // See Developer Documentation for more info.
    if (defined('YP_DEMO_MODE')) {
        include(WT_PLUGIN_DIR . 'library/php/demo-mode.php');
    }
    
    
    // Iframe Settings.
    // Disable admin bar in iframe
    // Add Classes to iframe body.
    // Add Styles for iframe.
    if (yp_check_let_frame()) {
        show_admin_bar(false);
        add_filter('body_class', 'yp_body_class');
        add_action('wp_enqueue_scripts', 'yp_styles_frame');
    }
    
    
    // If yellow pencil is active and theme support;
    // Adding Link to #wpadminbar.
    if (yp_check_let()) {
        
        // If not admin page, Add Customizer link.
        if (is_admin() === false) {
            
            add_action('admin_bar_menu', 'yp_yellow_pencil_edit_admin_bar', 999);
            
            // Adding CSS helper for admin bar link.
            add_action('wp_head', 'yp_yellow_pencil_style');
            
        }
        
    }
    
    
    // Getting Current font families.
    add_action('wp_enqueue_scripts', 'yp_load_fonts');
    
    
    // Live preview
    if (isset($_GET['yp_live_preview']) == true) {
        add_action('wp_enqueue_scripts', 'yp_load_fonts_for_live');
    }
    
    
}

add_action("init", "yp_init");




/* ---------------------------------------------------- */
/* Uploader Style 										*/
/* ---------------------------------------------------- */
function yp_uploader_style() {
    
    if (isset($_GET['yp_uploader'])) {
        
        if ($_GET['yp_uploader'] == 1) {
            
            echo '<style>
				tr.url,tr.post_content,tr.post_excerpt,tr.field,tr.label,tr.align,tr.image-size,tr.post_title,tr.image_alt,.del-link,#tab-type_url{display:none !important;}
				.media-item-info > tr > td > p:last-child,.savebutton,.ml-submit{display:none !important;}
				#filter{display:none !important;}
				.media-item .describe input[type="text"], .media-item .describe textarea{width:334px;}
				div#media-upload-header{
				}
			</style>';
            
        }
        
    }
    
}



/* ---------------------------------------------------- */
/* Add action to Admin Head for Uploader Style			*/
/* ---------------------------------------------------- */
add_action('admin_head', 'yp_uploader_style');



/* ---------------------------------------------------- */
/* CSS library for Yellow Pencil						*/
/* ---------------------------------------------------- */
function yp_register_styles() {
    
    $css = yp_get_css(true);
    
    // Animate library.
    if (strstr($css, "animation-name:")) {
        wp_enqueue_style('yellow-pencil-animate', plugins_url('library/css/animate.css', __FILE__));
    }
    
    // Animate library for live preview
    if (isset($_GET['yp_live_preview']) == true) {
        
        $css = yp_get_live_css();
        
        if (strstr($css, "animation-name:")) {
            wp_enqueue_style('yellow-pencil-animate', plugins_url('library/css/animate.css', __FILE__));
        }
        
    }
    
    // Add Custom.css to website.
    if (isset($_GET['yellow_pencil_frame']) == false && isset($_GET['yp_live_preview']) == false && get_option('yp-output-option') == 'external') {
        
        // New ref URL parameters on every new update.
        $rev = get_option('yp_revisions');
        
        if ($rev == false) {
            $rev = 700;
        }
        
        // Custom CSS Href
        $href = add_query_arg('revision', $rev, plugins_url('custom-' . $rev . '.css', __FILE__));
        
        // Add
        wp_enqueue_style('yp-custom', $href);
        
    }
    
}



/* ---------------------------------------------------- */
/* Jquery plugins for CSS Engine						*/
/* ---------------------------------------------------- */
function yp_register_scripts() {
    
    $css        = yp_get_css(true);
    $needjQuery = false;
    
    if (strstr($css, "animation-name:") == true || strstr($css, "background-parallax:") == true || strstr($css, "jquery-") == true || strstr($css, "animation-duration:") == true || strstr($css, "animation-delay:") == true || isset($_GET['yellow_pencil_frame']) == true || isset($_GET['yp_live_preview']) == true) {
        
        // Yellow Pencil Library Helper.
        wp_enqueue_script('yellow-pencil-library', plugins_url('library/js/library.' . YP_MODE . '.js', __FILE__), 'jquery', '1.0', TRUE);
        
        $needjQuery = true;
        
    }
    
    // Jquery
    if ($needjQuery == true) {
        wp_enqueue_script('jquery');
    }
    
    
}

add_action('wp_enqueue_scripts', 'yp_register_styles', 9999);
add_action('wp_enqueue_scripts', 'yp_register_scripts');



/* ---------------------------------------------------- */
/* Iframe Admin Page									*/
/* ---------------------------------------------------- */
function yp_yellow_pencil_editor() {
    
    $hook = add_submenu_page(null, __('Yellow Pencil Editor', 'yp'), __('Yellow Pencil Editor', 'yp'), 'edit_theme_options', 'yellow-pencil-editor', 'yp_editor_func');
    
}

add_action('admin_menu', 'yp_yellow_pencil_editor');



/* ---------------------------------------------------- */
/*  We need an blank page (hack)						*/
/* ---------------------------------------------------- */
function yp_editor_func() {
    
}

add_action('load-admin_page_yellow-pencil-editor', 'yp_frame_output');



/* ---------------------------------------------------- */
/* Custom Action yp_head 								*/
/* ---------------------------------------------------- */
function yp_head() {
    do_action('yp_head');
}



/* ---------------------------------------------------- */
/* Custom Action yp_footer 								*/
/* ---------------------------------------------------- */
function yp_footer() {
    do_action('yp_footer');
}


/* ---------------------------------------------------- */
/* Editor Page Markup 									*/
/* ---------------------------------------------------- */
function yp_frame_output() {
    
    $protocol = is_ssl() ? 'https' : 'http';
    
    $protocol = $protocol . '://';
    
    // Fix WooCommerce shop page bug
    if (class_exists('WooCommerce')) {
        
        $currentID = 0;
        $href      = '';
        
        // ID
        if (isset($_GET['yp_id'])) { // ID
            $currentID = intval($_GET['yp_id']);
        }
        
        // href
        if (isset($_GET['href'])) { // ID
            $href = $_GET['href'];
        }
        
        // get shop id
        $shopID = wc_get_page_id('shop');
        
        // If current id is shop && and href has "page_id"
        if ($currentID == $shopID && strstr($href, "page_id") == true && strstr($href, "post_type") == false) {
            
            // Redirect
            wp_safe_redirect(admin_url('admin.php?page=yellow-pencil-editor&href=' . yp_urlencode(get_post_type_archive_link("product")) . '&yp_id=' . $shopID));
            
        }
        
    }
    
    // Editor Markup
    include(WT_PLUGIN_DIR . 'editor.php');
    
    exit;
    
}



/* ---------------------------------------------------- */
/* Adding link to plugins page 							*/
/* ---------------------------------------------------- */
if (!defined('WTFV')){
    
    add_filter('plugin_row_meta', 'yp_plugin_links', 10, 2);
    
    function yp_plugin_links($links, $file) {
        
        if ($file == plugin_basename(dirname(__FILE__) . '/yellow-pencil.php')) {
            $links[] = '<a href="http://waspthemes.com/yellow-pencil/documentation/">' . __('Documentation', 'yp') . '</a>';
        }
        
        return $links;
        
    }
    
}


/* ---------------------------------------------------- */
/* Ading Prefix to CSS selectors for global export		*/
/* ---------------------------------------------------- */
function yp_add_prefix_to_css_selectors($css, $prefix) {
    
    # Wipe all block comments
    $css = preg_replace('!/\*.*?\*/!s', '', $css);
    
    $parts             = explode('}', $css);
    $mediaQueryStarted = false;
    
    foreach ($parts as &$part) {
        $part = trim($part); # Wht not trim immediately .. ?
        
        if (empty($part)) {
            continue;
        } else { # This else is also required
            
            $partDetails = explode('{', $part);
            
            if (substr_count($part, "{") == 2) {
                $mediaQuery        = $partDetails[0] . "{";
                $partDetails[0]    = $partDetails[1];
                $mediaQueryStarted = true;
            }
            
            $subParts = explode(',', $partDetails[0]);
            
            foreach ($subParts as &$subPart) {
                if (strstr(trim($subPart), "@") || strstr(trim($subPart), "%")) {
                    continue;
                } else {
                    
                    // Selector
                    $subPart = trim($subPart);
                    
                    // Array
                    $subPartArray = explode(" ", $subPart);
                    $lov          = strtolower($subPart);
                    
                    $lovMach = str_replace("-", "US7XZX", $lov);
                    $lovMach = str_replace("_", "TN9YTX", $lovMach);
                    
                    preg_match_all("/\bbody\b/i", $lovMach, $bodyAll);
                    preg_match_all("/#body\b/i", $lovMach, $bodySlash);
                    preg_match_all("/\.body\b/i", $lovMach, $bodyDot);
                    
                    preg_match_all("/\bhtml\b/i", $lovMach, $htmlAll);
                    preg_match_all("/#html\b/i", $lovMach, $htmlSlash);
                    preg_match_all("/\.html\b/i", $lovMach, $htmlDot);
                    
                    // Get index of "body" term.
                    if (preg_match("/\bbody\b/i", $lovMach) && count($bodyAll[0]) > (count($bodyDot[0]) + count($bodySlash[0]))) {
                        
                        $i     = 0;
                        $index = 0;
                        foreach ($subPartArray as $term) {
                            $term = trim(strtolower($term));
                            if ($term == 'body' || preg_match("/^body\./i", $term) || preg_match("/^body\#/i", $term) || preg_match("/^body\[/i", $term)) {
                                $index = $i;
                                break;
                            }
                            $i++;
                        }
                        
                        // Adding prefix class to Body
                        $subPartArray[$index] = $subPartArray[$index] . $prefix;
                        
                        // Update Selector
                        $subPart = implode(" ", $subPartArray);
                        
                    } else if (preg_match("/\bhtml\b/i", $lovMach) && count($HtmlAll[0]) > (count($htmlDot[0]) + count($htmlSlash[0]))) {
                        
                        $i     = 0;
                        $index = 0;
                        foreach ($subPartArray as $term) {
                            $term = trim(strtolower($term));
                            if ($term == 'html' || preg_match("/^html\./i", $term) || preg_match("/^html\#/i", $term) || preg_match("/^html\[/i", $term)) {
                                $index = $i;
                                break;
                            }
                            $i++;
                        }
                        
                        // Adding prefix class to Body
                        if (count($subPartArray) <= 1) {
                            if ($subPart != 'html' && preg_match("/^html\./i", $subPart) && preg_match("/^html\#/i", $subPart) && preg_match("/^html\[/i", $subPart)) {
                                $subPartArray[$index] = $subPartArray[$index] . " body" . $prefix;
                            }
                        } else {
                            $subPartArray[$index] = $subPartArray[$index] . " body" . $prefix;
                        }
                        
                        // Update Selector
                        $subPart = implode(" ", $subPartArray);
                        
                    } else {
                        
                        // Adding prefix class to Body
                        $subPartArray[0] = "body" . $prefix . " " . $subPartArray[0];
                        
                        // Update Selector
                        $subPart = implode(" ", $subPartArray);
                        
                    }
                    
                }
            }
            
            if (substr_count($part, "{") == 2) {
                $part = $mediaQuery . "\n" . implode(', ', $subParts) . "{" . $partDetails[2];
            } elseif (empty($part[0]) && $mediaQueryStarted) {
                $mediaQueryStarted = false;
                $part              = implode(', ', $subParts) . "{" . $partDetails[2] . "}\n"; //finish media query
            } else {
                if (isset($partDetails[1])) {
                    # Sometimes, without this check,
                    # there is an error-notice, we don't need that..
                    $part = implode(', ', $subParts) . "{" . $partDetails[1];
                }
            }
            
            unset($partDetails, $mediaQuery, $subParts); # Kill those three..
            
        }
        unset($part); # Kill this one as well
    }
    
    // Delete spaces
    $output = preg_replace('/\s+/', ' ', implode("} ", $parts));
    
    // Delete all other spaces
    $output = str_replace("{ ", "{", $output);
    $output = str_replace(" {", "{", $output);
    $output = str_replace("} ", "}", $output);
    $output = str_replace("; ", ";", $output);
    
    // Beatifull >
    $output = str_replace("{", "{\n\t", $output);
    $output = str_replace("}", "\n}\n\n", $output);
    $output = str_replace("}\n\n\n", "}\n\n", $output);
    $output = str_replace("){", "){\n", $output);
    $output = str_replace(";", ";\n\t", $output);
    $output = str_replace("\t\n}", "}", $output);
    $output = str_replace("}\n\n}", "\t}\n\n}\n\n", $output);
    
    
    # Finish with the whole new prefixed string/file in one line
    return (trim($output));
    
}



/* --------------------------------------------------------- */
/* Encoding & Decoding the data; Used for import and export  */
/* --------------------------------------------------------- */
function yp_encode($value) {
    $func = 'base64' . '_encode';
    return $func($value);
}

function yp_decode($value) {
    $func = 'base64' . '_decode';
    return $func($value);
}



/* ---------------------------------------------------- */
/* Getting All plugin options by prefix					*/
/* ---------------------------------------------------- */
function yp_get_all_options($prefix = '', $en = false) {
    
    global $wpdb;
    $ret     = array();
    $options = $wpdb->get_results($wpdb->prepare("SELECT option_name,option_value FROM {$wpdb->options} WHERE option_name LIKE %s", $prefix . '%'), ARRAY_A);
    
    if (!empty($options)) {
        foreach ($options as $v) {
            if (strstr($v['option_name'], 'wt_theme') == false && strstr($v['option_name'], 'wt_available_version') == false && strstr($v['option_name'], 'wt_last_check_version') == false) {
                if ($en == true) {
                    $ret[$v['option_name']] = yp_encode(stripslashes($v['option_value']));
                } else {
                    $ret[$v['option_name']] = stripslashes($v['option_value']);
                }
            }
        }
    }
    
    return (!empty($ret)) ? $ret : false;
    
}



/* ---------------------------------------------------- */
/* Getting All post meta data by prefix					*/
/* ---------------------------------------------------- */
function yp_get_all_post_options($prefix = '', $en = false) {
    
    global $wpdb;
    $ret     = array();
    $options = $wpdb->get_results($wpdb->prepare("SELECT post_id,meta_key,meta_value FROM {$wpdb->postmeta} WHERE meta_key LIKE %s", $prefix . '%'), ARRAY_A);
    
    if (!empty($options)) {
        foreach ($options as $v) {
            if ($en == true) {
                $ret[$v['post_id'] . "." . $v['meta_key']] = yp_encode(stripslashes($v['meta_value']));
            } else {
                $ret[$v['post_id'] . "." . $v['meta_key']] = stripslashes($v['meta_value']);
            }
        }
    }
    
    return (!empty($ret)) ? $ret : false;
    
}



/* ---------------------------------------------------- */
/* Creating a json data for export data					*/
/* ---------------------------------------------------- */
function yp_get_export_data() {
    
    $allData       = array();
    $postmeta_CSS  = yp_get_all_post_options('_wt_css', true);
    $postmeta_HTML = yp_get_all_post_options('_wt_styles', true);
    $option_Data   = yp_get_all_options('wt_', true);
    $option_Anims  = yp_get_all_options('yp_anim', true);
    
    if (is_array($postmeta_CSS)) {
        array_push($allData, $postmeta_CSS);
    }
    
    if (is_array($postmeta_HTML)) {
        array_push($allData, $postmeta_HTML);
    }
    
    if (is_array($option_Data)) {
        array_push($allData, $option_Data);
    }
    
    if (is_array($option_Anims)) {
        array_push($allData, $option_Anims);
    }
    
    if (empty($allData) == false) {
        $data     = array_values($allData);
        $jsonData = json_encode($data);
        return $jsonData;
    }
    
    return false;
    
}



/* ---------------------------------------------------- */
/* Generate All CSS styles as ready-to-use				*/
/* ---------------------------------------------------- */
/* $method = 'export' / 'create' (string)				*/
/* ---------------------------------------------------- */
function yp_get_export_css($method) {
    
    // Array
    $allData = array();
    
    // Getting all from database
    $postmeta_CSS = yp_get_all_post_options('_wt_css', false);
    $option_Data  = yp_get_all_options('wt_', false);
    $option_Anims = yp_get_all_options('yp_anim', false);
    
    // Push option data to Array
    if (is_array($option_Data)) {
        array_push($allData, $option_Data);
    }
    
    // Push postmeta data to Array
    if (is_array($postmeta_CSS)) {
        array_push($allData, $postmeta_CSS);
    }
    
    // Check if there have animations
    if (is_array($option_Anims)) {
        
        // Push custom animations to Array
        array_push($allData, $option_Anims);
        
        // New Array for webkit prefix
        $option_AnimWebkit = array();
        
        // Copy animations as webkit
        foreach ($option_Anims as $key => $animate) {
            $option_AnimWebkit["Webkit " . $key] = str_replace("@keyframes", "@-webkit-keyframes", $animate);
        }
        
        // Push Animations
        array_push($allData, $option_AnimWebkit);
        
    }
    
    
    // Be sure The data not empty
    if (empty($allData) == false) {
        
        // Clean array
        $data = array_values($allData);
        
        // Variables
        $output     = null;
        $table      = array();
        $tableIndex = 0;
        $prefix     = '';
        
        // Adding WordPress Page, category etc classes to all CSS Selectors.
        foreach ($data as $nodes) {
            
            foreach ($nodes as $key => $css) {
                $tableIndex++;
                
                // If post meta
                if (strstr($key, '._')) {
                    
                    $keyArray = explode(".", $key);
                    $postID   = $keyArray[0];
                    $type     = get_post_type($postID);
                    $title    = '"' . ucfirst(get_the_title($postID)) . '" ' . ucfirst($type) . '';
                    
                    $page_for_posts = get_option('page_for_posts');
                    
                    if ($page_for_posts == $postID) {
                        $prefix = '.blog';
                    } elseif ($type == 'page') {
                        $prefix = '.page-id-' . $postID . '';
                    } else {
                        $prefix = '.postid-' . $postID . '';
                    }
                    
                    // not have page-id class in WooCommerce shop page.
                    if (class_exists('WooCommerce')) {
                        $shopID = wc_get_page_id('shop');
                        if ($postID == $shopID) {
                            $prefix = '.post-type-archive-product';
                        }
                    }
                    
                } else {
                    
                    if ($key == 'wt_css') {
                        $title  = 'Global Styles';
                        $prefix = '';
                    } else if ($key == 'wt_author_css') {
                        $title  = 'Author Page';
                        $prefix = '.author';
                    } else if ($key == 'wt_category_css') {
                        $title  = 'Category Page';
                        $prefix = '.category';
                    } else if ($key == 'wt_tag_css') {
                        $title  = 'Tag Page';
                        $prefix = '.tag';
                    } else if ($key == 'wt_404_css') {
                        $title  = '404 Error Page';
                        $prefix = '.error404';
                    } else if ($key == 'wt_search_css') {
                        $title  = 'Search Page';
                        $prefix = '.search';
                    } else if ($key == 'wt_home_css') {
                        $title  = 'Home Page';
                        $prefix = '.home';
                    }
                    
                    else if (strstr($key, 'yp_anim')) {
                        $title = str_replace("yp_anim_", "", $key);
                        $title = $title . " Animate";
                    } else if (strstr($key, 'wt_') && strstr($key, '_css')) {
                        $title = str_replace("wt_", "", $key);
                        $title = str_replace("_css", "", $title);
                        
                        if (strtolower($title) == 'page') {
                            $prefix = '.page';
                        } else {
                            $prefix = '.single-' . strtolower($title) . '';
                        }
                        
                        $title = $title . " Template";
                    }
                    
                }
                
                if (!strstr($key, '_styles')) {
                    $len   = 48 - (strlen($title) + 2);
                    $extra = null;
                    
                    for ($i = 1; $i < $len; $i++) {
                        $extra .= ' ';
                    }
                    
                    array_push($table, ucfirst($title));
                    $output .= "/*-----------------------------------------------*/\r\n";
                    $output .= "/*  " . ucfirst($title) . "" . $extra . "*/\r\n";
                    $output .= "/*-----------------------------------------------*/\r\n";
                    $output .= yp_add_prefix_to_css_selectors($css, $prefix) . "\r\n\r\n\r\n\r\n";
                    
                }
                
            }
            
        }
        // Foreach end.
        
        
        // Create a table list for CSS codes
        $tableList   = null;
        $plusNumber  = 1;
        $googleFonts = array();
        
        // Get fonts from CSS output
        if ($method == 'export') {
            $googleFonts = yp_get_font_families($output, 'import');
        }
        
        // If has any Google Font; Add Font familes to first table list.
        if (count($googleFonts) > 0) {
            $tableList  = "    01. Font Families\r\n";
            $plusNumber = 2;
        }
        
        // Creating a table list.
        foreach ($table as $key => $value) {
            $tableList .= "    " . sprintf("%02d", $key + $plusNumber) . ". " . $value . "\r\n";
        }
        
        
        // Google Fonts
        if (count($googleFonts) > 0 && is_array($googleFonts)) {
            $FontsCSS = "/*-----------------------------------------------*/\r\n";
            $FontsCSS .= "/* Font Families                                 */\r\n";
            $FontsCSS .= "/*-----------------------------------------------*/\r\n";
            
            foreach ($googleFonts as $fontURL) {
                $FontsCSS .= "@import url('" . $fontURL . "');\r\n";
            }
            
            $FontsCSS .= "\r\n\r\n\r\n";
        }
        
        
        // All in.
        $allOutPut = "/*\r\n\r\n    These CSS codes generated by Yellow Pencil Editor.\r\n";
        $allOutPut .= "    http://waspthemes.com/yellow-pencil\r\n\r\n\r\n";
        $allOutPut .= "    T A B L E   O F   C O N T E N T S\r\n";
        $allOutPut .= "    ........................................................................\r\n\r\n";
        $allOutPut .= $tableList;
        $allOutPut .= "\r\n*/\r\n\r\n\r\n\r\n";
        
        // Adding Google Fonts to OutPut.
        if (count($googleFonts) > 0) {
            $allOutPut .= $FontsCSS;
        }
        
        // Adding all CSS codues
        $allOutPut .= $output;
        
        // Process with some PHP functions and return Output CSS code.
        if ($method == 'export') {
            return yp_css_prefix(yp_export_animation_prefix(yp_hover_focus_match(trim($allOutPut))));
        } else {
            return yp_css_prefix(yp_animation_prefix(yp_hover_focus_match(trim($allOutPut))));
        }
        
    }
    
}



/* ---------------------------------------------------- */
/* Import Plugin data                					*/
/* ---------------------------------------------------- */
function yp_import_data($json) {
    
    $json = stripslashes($json);
    
    if (empty($json)) {
        return false;
    }
    
    $array = json_decode($json, true);
    
    foreach ($array as $nodes) {
        
        foreach ($nodes as $key => $value) {
            
            $value = yp_decode($value);
            
            // If post meta
            if (strstr($key, '._')) {
                
                $keyArray = explode(".", $key);
                $postID   = $keyArray[0];
                $metaKey  = $keyArray[1];
                
                if (!add_post_meta($postID, $metaKey, $value, true)) {
                    update_post_meta($postID, $metaKey, $value);
                }
                
            } else { // else option
                if (!update_option($key, $value)) {
                    add_option($key, $value);
                }
            }
            
        }
        
    }
    
}



/* ---------------------------------------------------- */
/* Export CSS as style.css 	 							*/
/* ---------------------------------------------------- */
function yp_exportCSS_admin_header() {
    
    if (isset($_GET['yp_exportCSS'])) {
        
        if ($_GET['yp_exportCSS'] == 'true') {
            
            $data = yp_get_export_css("export");
            
            header('Content-Disposition: attachment; filename="style-' . strtolower(date("M-d")) . '.css"');
            header("Content-type: text/css; charset: UTF-8");
            header('Content-Length: ' . strlen($data));
            header('Connection: close');
            
            echo $data;
            
            die();
            
        }
        
    }
    
}

add_action("admin_init", "yp_exportCSS_admin_header", 9999);


// @WaspThemes.
// Coded With Love..