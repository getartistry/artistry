<?php
/**
 * Modified Press This to grab RSS Feed sources from web pages
 */

define('IFRAME_REQUEST' , true);

/** WordPress Administration Bootstrap */
require_once('../../../wp-load.php');
//Load files needed for admin scripts/styles
if (defined('ABSPATH')) {
    require_once(ABSPATH . 'wp-admin/includes/post.php');
    require_once(ABSPATH . 'wp-admin/includes/bookmark.php');
    require_once(ABSPATH . 'wp-admin/includes/template.php');
    require_once(ABSPATH . 'wp-includes/functions.wp-scripts.php'); 
} else {
    require_once('../../../wp-admin/includes/post.php');
    require_once('../../../wp-admin/includes/bookmark.php');
    require_once('../../../wp-admin/includes/template.php');
    require_once('../../../wp-includes/functions.wp-scripts.php');
}

//header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));
global $current_user;
$cu = wp_get_current_user();
if ($current_user->ID == ''){
    wp_login_form();
    exit();
}
if ( ! current_user_can('edit_posts') )
	wp_die( __( 'User not authorized to publish posts' ) );

/**
 * find rss using YQL call
 *
 
 * @return string url
 */
function mct_ai_findrss($url) {
    if (empty($url)) return "";
    //Check if an alert feed
    if (stripos($url, "google.com/alerts") !== false) return $url;
    if (stripos($url, "talkwalker.com/alerts") !== false) return $url;
    //On a feed page?
    if (stripos($url, "feeds.feedburner") !== false) return $url;
    if (stripos($url, "/feed/") !== false) return $url;
    if (stripos($url, "/rss/") !== false) return $url;
    //Get page if using url
    try {
        $opts = array('http' => array('user_agent'=> $_SERVER['HTTP_USER_AGENT']));
        $context = stream_context_create($opts);
        $webpage = @file_get_contents($url, false, $context);
        if ($webpage === false || $webpage == "") {
            return "";
        }
    } catch (exception $e) {
        return '';
    }
    //Check for link tags type alternate for rss or atom
    $dom = new DOMDocument;
    libxml_use_internal_errors(true);
    $dom->loadHTML($webpage);
    foreach ($dom->getElementsByTagName('link') as $linknode) {
        if($linknode->getAttribute('rel') == 'alternate') {
            $typeAttr = $linknode->getAttribute('type');
            if ($typeAttr != "" && (stripos($typeAttr,'rss+xml') !== false || stripos($typeAttr,'atom+xml') !== false)){
                return $linknode->getAttribute( 'href' );
            }
        }    
    }   
    //No link alternate found, check a tags
    foreach ($dom->getElementsByTagName('a') as $linknode) {
        $link = $linknode->getAttribute( 'href' );
        if (stripos($link, "feeds.feedburner") !== false) return $link;
        if (stripos($link, "/feed/") !== false) return $link;
        if (stripos($link, "/rss/") !== false) return $link;
        if (stripos($link, "/atom/") !== false) return $link;
    }
    return "";  //nothing found
}

$args = array();
$msg = '';
$posted = false;
$url = '';
$nofeed = false;
// For submitted posts.
if ( isset($_REQUEST['action']) && 'post' == $_REQUEST['action'] ) {
	check_admin_referer('MyCurator_sourceit');//MOD - use correct admin referer
        $posted = true;
        $args['link_category'] = strval(absint($_POST['link_category']));
        $args['newlinkcat'] = trim(sanitize_text_field($_POST['newlinkcat']));
        $args['feed_name'] = trim(sanitize_text_field($_POST['feed_name']));
        $args['rss-url'] =  esc_url($_POST['rss-url']);
        $args['save-url'] = esc_url($_POST['save-url']);
        //Validate args
        if (strlen($args['feed_name']) == 0) $msg .= 'Must have a Feed Name. ';
        //if ok, post it
        if ($msg == '') $msg = mct_ai_postlink($args);
} else {
    // Set Variables
    $args['feed_name'] = isset( $_GET['t'] ) ? trim( strip_tags( html_entity_decode( stripslashes( $_GET['t'] ) , ENT_QUOTES) ) ) : '';
    $args['rss-url'] = isset($_GET['u']) ? esc_url($_GET['u']) : ''; //this is the site url at this point
    $args['newlinkcat'] = '';
    $url = $args['rss-url'];
    //Look for an RSS Url
    $url = mct_ai_findrss($url);
    if ($url == '') {
            $nofeed = true;
    } else if (parse_url($url, PHP_URL_HOST) == '') {
        //Relative url, so add domain
        $domain = parse_url($args['rss-url'],PHP_URL_SCHEME)."://".parse_url($args['rss-url'],PHP_URL_HOST);
        $url = $domain.$url;
    }
    $args['rss-url'] = $url;
    $args['save-url'] = parse_url($url, PHP_URL_HOST);
}

//Get Link Categories for dropdown
$cats = array (
    'orderby' => 'name',
    'hide_empty' => FALSE,
    'name' => 'link_category',
    'taxonomy' => 'link_category'
);
if (isset($args['link_category'])) $cats['selected'] = $args['link_category'];
//Check if sources over max
mct_ai_getplan();
$src = mct_ai_sourcemax();

wp_enqueue_style( 'colors' );
wp_enqueue_script( 'post' );
_wp_admin_html_begin();
?>
<title><?php _e('Source It') ?></title> 
<script type="text/javascript">
//<![CDATA[
addLoadEvent = function(func){if(typeof jQuery!="undefined")jQuery(document).ready(func);else if(typeof wpOnload!='function'){wpOnload=func;}else{var oldonload=wpOnload;wpOnload=function(){oldonload();func();}}};
var userSettings = {'url':'<?php echo SITECOOKIEPATH; ?>','uid':'<?php if ( ! isset($current_user) ) $current_user = wp_get_current_user(); echo $current_user->ID; ?>','time':'<?php echo time() ?>'};
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>', pagenow = 'press-this', isRtl = <?php echo (int) is_rtl(); ?>;
var photostorage = false;
//]]>
</script>

<?php
	//Set up global colors for admin styles
        register_admin_color_schemes();
        do_action('admin_print_styles');
	do_action('admin_print_scripts');
	//do_action('admin_head');
?>
	<style type="text/css">
	#message {
		margin: 10px 0;
	}
	#title,
	.press-this #wphead {
		margin-left: 0;
		margin-right: 0;
	}
	.rtl.press-this #header-logo,
	.rtl.press-this #wphead h1 {
		float: right;
	}
        .posting {
            margin-right: 50px;
        }
        body.press-this {
            min-width: 275px;
            min-height: 200px;
        }
        #titlediv {
            font-size: 1.3em;
        }
</style>

<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function($) {
   jQuery('#submit').click(function() { jQuery('#saving').css('display', 'inline'); });
    
});
//]]>
</script>

</head>
<body class="press-this wp-admin<?php if ( is_rtl() ) echo ' rtl'; ?>">
<form action="MyCurator_sourceit.php?action=post" method="post">   
<div id="poststuff" class="metabox-holder">
        <?php wp_nonce_field('MyCurator_sourceit') ?>  
        <!--  Create hidden fields for feed url and link url so they can be added to feed entry, these are set by jQuery if not a google alert page -->
        <input type="hidden" id="save-url" name="save-url" value="<?php echo  $args['save-url'] ; ?>" />
        <input type="hidden" id="rss-url" name="rss-url" value="<?php echo  $args['rss-url'] ; ?>" />
        
	<div class="mct-posting">

		<div id="wphead">
			<h1>
				<a href="<?php echo get_option('home'); ?>/" target="_blank"><?php bloginfo('name'); ?></a>
			</h1>
                    
		</div>

		<?php
		if ( empty($msg) && $posted) {?>
			<div id="message" class="updated">
			<p><strong><?php _e('Your Feed URL has been saved.'); ?></strong>
			<a href="#" onclick="window.close();"><?php _e('Close Window'); ?></a></p>
                        <script type="text/javascript">setTimeout('self.close();',2000);</script>
			</div>
		<?php exit(); } ?>
                <?php if ($src !== false && $src <= 0) {
                    mct_ai_showsrc();
                    exit;
                } ?>
                <?php
                if ($nofeed) {  // Show Error Div  ?>
                <div id="jquery-message" color: red; font-size: 18px;">
			<h2><span id="feed-error"><?php _e('No Feed Found on this Page - '); ?></span>
			<a href="#" onclick="window.close();"><?php _e('Click to Close Window'); ?></a></h2>
                </div>
                <?php 
                } else { ?> 
		<div id="titlediv">
			<div class="titlewrap">
				<p>Feed Title: <span id="feed-title" style="font-size:12px;"><?php echo $args['feed_name']; ?></span></p>
                                <p>Feed URL: <span id="feed-url" style="font-size:12px;"><?php echo $args['rss-url']; ?></span></p>
			</div>
		</div>

            <br /> 
            <?php if ($msg != '' && $posted) { ?>
                    <div id="message" class="error" ><p><strong><?php echo "FEED NOT CREATED: ".$msg ; ?></strong></p></div>
            <?php } ?>
            <div id="categorydiv" class="postbox">
                <h3 class="hndle"><?php _e('Add Source Link') ?></h3>
                <div class="inside">
                <div id="taxonomy-category" class="categorydiv">
                    <p>Feed Name: <input name="feed_name" id="feed-name" type="input" size="50" maxlength="200" value="<?php echo  $args['feed_name'] ; ?>" /> </p>
                    <p>Choose Link Category: <?php wp_dropdown_categories($cats); ?></p>
                    <p>or Add New Link Category: <input name="newlinkcat" type="input" size="50" maxlength="200" value="<?php echo  $args['newlinkcat'] ; ?>" /> </p>
                    <p><em>Create a new category rather than use BlogRoll</em></p>
                </div>
                </div>
             </div>
            <div id="submit-button">
            <input name="submit" id="submit" value="Save New Source" type="submit" class="button-primary">
            <img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" alt="" id="saving" style="display:none;" />
            </div>
            <?php } //end $nofeed = false ?>
        </div>
</div>
</form>
<?php
//Use Admin Footers from source
//do_action('admin_footer');
do_action('admin_print_footer_scripts');
?>
<div class="clear"></div></div><!-- wpbody-content -->
<div class="clear"></div></div><!-- wpbody -->
<div class="clear"></div></div><!-- wpcontent -->

<div id="wpfooter" role="contentinfo"></div>
<div class="clear"></div></div><!-- wpwrap -->
<script type="text/javascript">if(typeof wpOnload=='function')wpOnload();</script>

</body>
</html>
