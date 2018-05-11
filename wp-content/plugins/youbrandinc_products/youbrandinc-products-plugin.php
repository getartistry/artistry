<?php
/*
	Plugin Name: You Brand, Inc. Products
	Plugin URI: https://members.youbrandinc.com/
	Description: Plugin for licensing and using You Brand, Inc. products
	Author: You Brand, Inc.
	Version: 1.87
	Author URI: https://www.YouBrandInc.com
*/

// add the menu items( we create a high level You Brand, Inc menu and a license activation menu)
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
define( 'YBI_BASE_PATH', plugin_dir_path(__FILE__) );
define( 'YBI_BASE_URL', plugins_url('youbrandinc_products/') );
define( 'YBI_BASE_OAUTH_URL', plugins_url('youbrandinc_products/youbrandinc-oauth.php') );
define("YBIMULTIPLIER", 1);

if(is_admin()) {
	if(ybi_checkPHPVersionGood()) {
		if(!class_exists('HTMLObject')) {
			include_once(YBI_BASE_PATH . 'vendor/ybi/html/HTMLObject.class.php');
			include_once(YBI_BASE_PATH . 'vendor/ybi/html/Table.class.php');
			include_once(YBI_BASE_PATH . 'vendor/ybi/html/Row.class.php');
			include_once(YBI_BASE_PATH . 'vendor/ybi/html/Column.class.php');
			include_once(YBI_BASE_PATH . 'vendor/ybi/html/Link.class.php');
		}
	} else {
		function showYBIPHPVersionMessage()
		{
			// <div id="message" class="updated fade">
			echo '<div id="message" class="error"><p><strong>To run the curation plugin and the YBI Licensing Plugin you need to have php5.3+. Updating this is simple and easy to do.
		<a href="https://youbrandinc.zendesk.com/hc/en-us/articles/217466867-T-String-Error-PHP5-2-Upgrade" target="_blank">click here to learn more</a>.</strong></p></div>';
		}
		add_action('admin_notices', 'showYBIPHPVersionMessage');
	}
}

function ybi_checkPHPVersionGood()
{
	return (version_compare(phpversion(), '5.3.0', '>='));
}

if(!function_exists('ybi_product_plugins_activation'))
{
	function ybi_product_plugins_activation()
	{
		update_option('ybi_turn_off_ioncube_check','yes');
	}
	register_activation_hook( __FILE__, 'ybi_product_plugins_activation' );
}

function add_youbrandinc_menu_items()
{
    define("YBI_SUPPORT_URL", 'https://members.youbrandinc.com/support/');
    $allowed_group = 'manage_options';

    require_once(ABSPATH . 'wp-includes/pluggable.php'); // this is here so we can call the user level down below
    $options = get_option('curation_suite_data');
    $curation_suite_user_level = 'edit_posts';
    if (isset($options) && is_array($options)) {
        if (array_key_exists('curation_suite_user_level', $options)) {
            $curation_suite_user_level = $options['curation_suite_user_level'];
        }
    }

    add_menu_page('You Brand, Inc.', 'You Brand, Inc.', $curation_suite_user_level, 'youbrandinc', 'youbrandinc_products_page', plugins_url('youbrandinc_products/i/you-brand-guys-16.png'), 76.18);
    // this removes the main menu from being in the submenu
    add_submenu_page('youbrandinc', '', '', $curation_suite_user_level, 'youbrandinc', 'youbrandinc_products_page');
    // this adds the base products page, but we wanted to rename the submenu item support/news
    add_submenu_page('youbrandinc', 'Support & News', 'Support & News', 'activate_plugins', 'youbrandinc-support-news', 'youbrandinc_products_page');
    add_action('admin_bar_menu', 'ybi_custom_toolbar_links', 999);
    if (get_option('ybi_super_admin') == "on") {
        add_submenu_page('youbrandinc', 'Advanced Setup', 'Advanced Setup', 'activate_plugins', 'youbrandinc-adv-setup', 'youbrandinc_adv_setup_page');
        add_action('admin_bar_menu', 'ybi_adv_custom_toolbar_links', 999);
    }

    // check to see if we have any plugins that require ioncube
    //$anyPluginRequireIoncube = isAnyYBIPluginThatRequiresIoncubeActive();
    $showAnalytics = true;
    // is ioncube installed and is there any plugins activated that require it
    //ioncube is activated and a plugin requires licensing, so show the page
    add_submenu_page('youbrandinc', 'License Activation', 'License Activation', 'activate_plugins', 'youbrandinc-license', 'youbrandinc_license_activation_page');
    add_submenu_page('youbrandinc', 'Connected Apps', 'Connected Apps', 'activate_plugins', 'youbrandinc-oauth', 'youbrandinc_oauth_page');
    add_submenu_page('tools.php', 'Recommended WordPress Tools', 'Recommended WordPress Tools', 'activate_plugins', 'recommended-resources', 'youbrandinc_recommended_resources');

    // do we show the analytics menu item? Only if ioncube is not required, see above. This also will show if they have any of our themes installed
    if ($showAnalytics)
        add_submenu_page('youbrandinc', 'Analytics and Shares', 'Analytics and Shares', 'activate_plugins', 'youbrandinc-analytic-share', 'youbrandinc_analytic_share_page');
}

add_action('admin_menu', 'add_youbrandinc_menu_items', 1);


// the products page that is an overview of products and support messages
function youbrandinc_recommended_resources()
{
    // make sure we have the needed function to verify the nonce.
    if (!function_exists('wp_verify_nonce')) { require_once(ABSPATH .'wp-includes/pluggable.php');  }
    yb_products_cs_js();
    include dirname(__FILE__).'/youbrandinc-recommend.php';
    return true;
}
// the products page that is an overview of products and support messages
function youbrandinc_oauth_page()
{
	// make sure we have the needed function to verify the nonce.
	if (!function_exists('wp_verify_nonce')) { require_once(ABSPATH .'wp-includes/pluggable.php');  }
	yb_products_cs_js();
	include dirname(__FILE__).'/youbrandinc-oauth.php';
	return true;
}


function youbrandinc_products_page()
{
	// make sure we have the needed function to verify the nonce.
	if (!function_exists('wp_verify_nonce')) { require_once(ABSPATH .'wp-includes/pluggable.php');  }
	yb_products_cs_js();
	include dirname(__FILE__).'/youbrandinc-products-overview.php';
	return true;
}
function youbrandinc_analytic_share_page()
{
	// make sure we have the needed function to verify the nonce.
	if (!function_exists('wp_verify_nonce')) { require_once(ABSPATH .'wp-includes/pluggable.php');  }
	yb_products_cs_js();

	include dirname(__FILE__).'/youbrandinc-analytic-share.php';
	return true;
}
function youbrandinc_install_ioncube_page()
{
	// make sure we have the needed function to verify the nonce.
	if (!function_exists('wp_verify_nonce')) { require_once(ABSPATH .'wp-includes/pluggable.php');  }
	yb_products_cs_js();
	include dirname(__FILE__).'/youbrandinc-install-ioncube.php';
	return true;
}
function youbrandinc_adv_setup_page()
{
	// make sure we have the needed function to verify the nonce.
	if (!function_exists('wp_verify_nonce')) { require_once(ABSPATH .'wp-includes/pluggable.php');  }
	yb_products_cs_js();
	include dirname(__FILE__).'/server-setup/advanced-ybi-setup.php';
	return true;
}

function yb_products_cs_js()
{
	wp_register_style('yb_products_cs_js', plugins_url('css/ybi-products-style.css',__FILE__ ));
	wp_enqueue_style('yb_products_cs_js');

}
//add_action( 'admin_enqueue_scripts','yb_products_cs_js');
function ybi_font_awesome_cs_js()
{
	wp_register_style('FontAwesome', plugins_url('font-awesome/css/font-awesome.min.css',__FILE__),array(),'4.7');
	//wp_register_style('FontAwesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css','4.4.0' );
	wp_enqueue_style('FontAwesome');
}
add_action( 'admin_enqueue_scripts','ybi_font_awesome_cs_js'); // include in admin
add_action( 'wp_enqueue_scripts','ybi_font_awesome_cs_js'); // include in front end for SQT

if (ybi_checkPHPVersionGood())
	require_once('license-check.php');

$un_encrpted_version ='';
$ybi_license_key = 'nokeyrequired';
require  dirname(__FILE__) . '/plugin-updates/plugin-update-checker.php';
	$YBIProductUpdateChecker = PucFactory::buildUpdateChecker(
	'https://members.youbrandinc.com/wp-update-server/?action=get_metadata&slug=youbrandinc_products'.$un_encrpted_version.'&license_key='.$ybi_license_key, //Metadata URL.
	__FILE__, //Full path to the main plugin file.
	'youbrandinc_products'.$un_encrpted_version //Plugin slug. Usually it's the same as the name of the directory.
	);
$is_set = true;

// checks to see if any plugin is activae that requires ioncube
function isAnyYBIPluginThatRequiresIoncubeActive()
{
	$pluginActive = false;
	// these products require ioncube
	$PluginArr = array("Curation Traffic Plugin","Ultimate Call to Action", "Social Quote Traffic","WP RoundUp","Curation Suite");
	foreach($PluginArr as $val)
	{
		// is the plugin activated
		if(isYBIPluginActive($val))
		{
			// if so return true and break as we found one that requires ioncube
			$pluginActive = true;
			break;
		}
	}
	return $pluginActive;
}

// checks to see if any of the YBI plugins are active
function isYBIPluginActive($inPluginName)
{
	$pluginCheckURL = '';
	switch($inPluginName) {
		case 'Curation Traffic Plugin';
		$pluginCheckURL = 'curation_traffic_plugin/curation_traffic_plugin.php';
		break;
		case 'Ultimate Call to Action';
		$pluginCheckURL = 'ultimate-call-to-action/ultimate_call_to_action_plugin.php';
		break;
		case 'Social Quote Traffic';
		$pluginCheckURL = 'social-quote-traffic/social_quote_traffic.php';
		break;
		case 'WP RoundUp';
		$pluginCheckURL = 'wp-roundup/wp-roundup.php';
		break;
		case 'Curation Suite';
		$pluginCheckURL = 'curation-suite/curation-suite.php';
		break;
		case 'Super Social Engagement';
			$pluginCheckURL = 'super-social-engagement/super-social-engagement.php';
		break;
	}

	return is_plugin_active($pluginCheckURL);
}
// we apply some minor styling to the customizer page
function ybi_theme_customize_style() {
	//we probably should check here to ensure they are only running our themes
    wp_enqueue_style('ybi_custom_customizer', plugins_url('css/admin-style.css',__FILE__ ));
}
add_action( 'customize_controls_enqueue_scripts', 'ybi_theme_customize_style' );

function getFeedYBI($feed_url, $total_posts) {
	include_once( ABSPATH . WPINC . '/feed.php' );
	// create the feed url
	echo "<ul>";
	$feedArr = array($feed_url);
	$rss = fetch_feed( $feedArr );
	if ( ! is_wp_error( $rss ) ) {
		$maxitems = $rss->get_item_quantity( 15 );
		$rss_items = $rss->get_items( 0, $maxitems );
		$i = 1;
		foreach ( $rss_items as $item ) {
			echo "<li><a href='".$item->get_permalink()."?source=ybi_product_tab' title='$item->get_title()' target='_blank'>" . $item->get_title() . "</a></li>";

			if($i == $total_posts)
				break;
			$i++;
		}
	}
	echo "</ul>";


}

// function to display number of post views.
// for the plugin of CT this is located in the CT plugin file
// for the theme the tracking is located in the single.php
function getPostViewsYBI($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0 Views";
    }
	if($count == 1)
		return $count.' View';
	else
    	return $count.' Views';
}

// function to count views.
function setPostViewsYBI($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}
add_filter( 'the_content', 'recordYBIPageView' );
function recordYBIPageView($content)
{
	if ( is_single() )
		if (function_exists('setPostViewsYBI'))
			setPostViewsYBI(get_the_ID());
	return $content;
}

// Add the post views column in WP-Admin
add_filter('manage_posts_columns', 'posts_YBI_column_views');
add_action('manage_posts_custom_column', 'posts_custom_column_views_YBI',5,2);
function posts_YBI_column_views($defaults){
    $defaults['post_views'] = __('Views');
    return $defaults;
}
function posts_custom_column_views_YBI($column_name, $id){
    if($column_name === 'post_views'){
        echo number_format(getPostViewsYBI(get_the_ID())*YBIMULTIPLIER);
    }
}

// these are customizations for the cusomizer for the themes we built
add_action('customize_register', 'ybiproducts_customize_register');
function ybiproducts_customize_register( $wp_customize )
{
	// we should probably check against themes here
	//	$my_theme = wp_get_theme();
	//echo $my_theme->get( 'Name' );

	$wp_customize->add_section(
		'ybi_post_options',
		array(
			'title'		=> __('Advanced Options', 'ybi_advanced_options'),
			'priority'	=> 33,
		)
	);

	// display tags
	$wp_customize->add_setting(
		'ybi_no_display_date',
		array(
			'default'	=> false,
			'transport'	=> 'refresh',
		)
	);

	$wp_customize->add_control(
		'ybi_no_display_date',
		array(
			'label' => __('Don\'t Show Post Dates', 'ybi_advanced_options'),
			'section' => 'ybi_post_options',
			'settings' => 'ybi_no_display_date',
			'type' => 'checkbox',
		)
	);
	$wp_customize->add_setting(
		'ybi_no_display_author',
		array(
			'default'	=> false,
			'transport'	=> 'refresh',
		)
	);

	$wp_customize->add_control(
		'ybi_no_display_author',
		array(
			'label' => __('Don\'t Show Author', 'ybi_advanced_options'),
			'section' => 'ybi_post_options',
			'settings' => 'ybi_no_display_author',
			'type' => 'checkbox',
		)
	);
}
/**
* Creates a .txt file backup located in the /youbrandinc_products/server-setup/backups/ directory of the file passed
* the file is also appended with a timestamp
* the final file would be: $newFileBase_timestamp
*
* @param string $file - the file where file_get_contents is called
* @param string $newFileBase - the filename, is appended with timestamp
*
* @return string $newFilename - returns the new filename that was created ($newFileBase_timestamp)
*/
function ybi_createFileTextDownloadBackup($file, $newFileBase)
{
	$scriptFileName = $_SERVER['SCRIPT_FILENAME'];
	$scriptFileName = str_replace("wp-admin/admin.php","",$scriptFileName);
	$backupDir = $scriptFileName . 'wp-content/plugins/youbrandinc_products/server-setup/backups/';
	$fileContents = file_get_contents($file);
	$ts = date('m-d-Y_H-i-s');
	$newFilename = $newFileBase . '_' . $ts . '.txt';
	$fullPathtoFile = $backupDir . $newFilename;
	file_put_contents($fullPathtoFile, $fileContents);
	return $newFilename;
}
/**
* This function will take a file name and the contents of a file and create a backup in the same directory
* it does this by replacing appending the file name with _ybi_backup_ and a timestamp, it also replaces any dots (.) in the filename for good measure
*
* @param string $file the file that is to be backed up
* @param string $fileContents the contents of the file to be backed up
*
* @return string $newFilename - returns the new filename that was created
*/
function ybi_createFileLocationBackup($file, $fileContents)
{
	$ts = date('m-d-Y_H-i-s');
	// replace dot with underscore
	$file = str_replace(".","_",$file);
	$newFilename = $file.'_ybi_backup_'.$ts.'.txt';
	file_put_contents($newFilename, $fileContents);
	return $newFilename;
}


/**
* If any plugin requires ioncube and if ioncube is not installed then...
* we add the admin_init action that will copy the check-server.php to the wp-admin folder
*/
//if(isAnyYBIPluginThatRequiresIoncubeActive() && !ybi_isIoncubeInstalled())
//	add_action( 'admin_init', 'ybi_copy_server_check_wpadmin' );
/**
* copies the check-server file to the wp-admin folder (used in iframes for checking the status of ioncube & php)
* we found this is the best place to test if ioncube is activated as wordpress runs from this directory
*
* @return void
*/
function ybi_copy_server_check_wpadmin()
{
	$copy_to_directory = str_replace("wp-content","wp-admin/",WP_CONTENT_DIR);
	$path = plugin_dir_path( __FILE__ );
	$file = $path . 'server-setup/check-server.php';
	if(!file_exists($copy_to_directory . 'check-server.php'))
	{
		$newfile = $copy_to_directory . 'check-server.php';
		if ( copy($file, $newfile) ) {

		}
	}

}

function setYBISuperAdmin($inSet)
{
	// this will turn it on or off, send it on or off
	if($inSet != '')
		update_option('ybi_super_admin', $inSet);
}
if (isset($_GET['YBIAdmin']))
	setYBISuperAdmin($_GET["YBIAdmin"]);

function ybi_custom_toolbar_links($wp_admin_bar) {
	if(!current_user_can( 'administrator' ))
		return;

	$args = array(
		'id' => 'ybi_plugin_shortcut',
		'title' => 'Upload Plugin',
		'href' => admin_url('plugin-install.php?tab=upload'),
		'meta' => array(
			'class' => 'ybi_plugin_shortcut',
			'title' => 'Upload Plugin'
		)
	);
	$wp_admin_bar->add_node($args);

}
// add a link to the WP Toolbar
function ybi_adv_custom_toolbar_links($wp_admin_bar) {
	if(!current_user_can( 'administrator' ))
		return;


	$args = array(
		'id' => 'ybi_theme_shortcut',
		'title' => 'Upload Theme',
		'href' => admin_url('theme-install.php?upload'),
		'meta' => array(
			'class' => 'ybi_theme_shortcut',
			'title' => 'Upload Theme'
			)
	);
	$wp_admin_bar->add_node($args);

	$args = array(
		'id' => 'ybi_turn_off_admin',
		'title' => 'Turn Off YBI Admin',
		'href' => admin_url('?YBIAdmin=off'),
		'meta' => array(
			'class' => 'ybi_turn_off_admin',
			'title' => 'Turn Off YBI Admin'
			)
	);
	$wp_admin_bar->add_node($args);
}
function ybi_startsWith($haystack, $needle)
{
	$length = strlen($needle);
	return (substr($haystack, 0, $length) === $needle);
}

function ybi_endsWith($haystack, $needle)
{
	$length = strlen($needle);
	if ($length == 0) {
		return true;
	}

	return (substr($haystack, -$length) === $needle);
}

/**
 * this function takes a url and returns a domain name from that url
 *
 * @param string $domain_name
 *
 * @return string $url - the domain name without http or https
 */
function ybi_get_domain_name($url, $inResolve=false)
{
	//http://stackoverflow.com/questions/5292937/php-function-to-get-the-subdomain-of-a-url
	$host = '';
	preg_match (
		"/^(http:\/\/|https:\/\/)?([^\/]+)/i",
		$url, $matches
	);

	if(	array_key_exists(2, $matches))
		$host = $matches[2];

	if(!$inResolve)
	{
		$host = str_replace("www.","",$host);
		return strtolower($host);
	}
	preg_match (
		"/[^\.\/]+\.[^\.\/]+$/",
		$host, $matches
	);
	//if(isset($matches))
	return strtolower("{$matches[0]}");

}

function ybi_do_external_service_call($url, $data, $type='POST')
{
/*	$url = 'https://getpocket.com/v3/get';
	$data = array(
		'consumer_key' => '33207-251450d0f1846fda36b368f2',
		'access_token' => $pocket_access_key,
		'state' => 'all',
		'detailType' => 'complete',
	);*/
	$JSON = wp_remote_post( $url, array('method'=>$type,'body'=>$data) );

	//var_dump($JSON);
	$is_error = false;
	if(!is_wp_error( $JSON )) {
		if ( 200 == $JSON['response']['code'] ) {
			$data = json_decode($JSON['body'], true);
			//var_dump($data);
		} else {
			$is_error = true;

			$data['status'] = 'failure';
			$message = 'No LE Message';
			if(array_key_exists('message',$data)) {
				$message = $data['message'];
			}
			$data['message'] = 'Connection error to Listening Engine, please contact support.' . $message;
		}
	} else {
		$is_error = true;
		$data['status'] = 'failure';
		$data['message'] = $JSON->get_error_message();
	}
	return $data;
}
function ybi_update_feed_quickly( $seconds ) {
    return 2;
}

function ybi_dashboard_widget_function() {
     add_filter( 'wp_feed_cache_transient_lifetime' , 'ybi_update_feed_quickly' );

    $rss = fetch_feed( "https://curationwp.com/feeds/recommends.php" );
    // remove_filter( 'wp_feed_cache_transient_lifetime' , 'ybi_update_feed_quickly' );

    if ( is_wp_error($rss) ) {
        if ( is_admin() || current_user_can('manage_options') ) {
            echo '<p>';
            printf(__('<strong>RSS Error</strong>: %s'), $rss->get_error_message());
            echo '</p>';
        }
        return;
    }

    if ( !$rss->get_item_quantity() ) {
        echo '<p>Apparently, there are no updates to show!</p>';
        $rss->__destruct();
        unset($rss);
        return;
    }

    echo "<style type=\"text/css\">#rmi353-dashboard-widget {display: none;}</style><ul>\n";
    //echo "<ul>\n";

    if ( !isset($items) )
        $items = 3;

    foreach ( $rss->get_items(0, $items) as $item ) {
        $publisher = '';
        $site_link = '';
        $link = '';
        $content = '';
        $date = '';
        $link = esc_url( strip_tags( $item->get_link() ) );
        $title = esc_html( $item->get_title() );
        $cat = $item->get_item_tags('', 'thumbnail');
        $thumbnail_img = $cat[0]['data'];
        $content = $item->get_content();
        $content = wp_html_excerpt($content, 300, '[...]');
        $content .= "<p style='text-align:right'><a href='$link'  target='_blank'>Learn More</a></p>";

        echo "<li>
	         	<div style='float:left;width:18%;margin-right:4%;display:block;padding-top:4px'><a href='$link' target='_blank'><img src='".$thumbnail_img."' style='width:100%;height:auto' /></a></div>
	         	<div style='float:left;width:78%'><a class='rsswidget' href='$link' target='_blank'>$title</a>\n<div class='rssSummary'>$content</div></div>
	         	<div class='clear'></div>
	         	</li>\n<li><hr/></li>";
    }
    echo "</ul><div style='clear:both'></div><a href=\"".admin_url('tools.php?page=recommended-resources')."\" title='Read more' style='float: right;'>See All Recommended WordPress Tools &raquo;</a><div style='clear:both'></div>\n";
    $rss->__destruct();
    unset($rss);
}

function ybi_add_dashboard_widget() {
    // wp_add_dashboard_widget('rmi353-dashboard-widget', 'Recommended Internet Marketing Tools', 'canvakala_dashboard_widget_function');
    add_meta_box('ybi-dashboard-widget', 'Recommended WordPress Tools', 'ybi_dashboard_widget_function', 'dashboard', 'side', 'high');
}
add_action('wp_dashboard_setup', 'ybi_add_dashboard_widget');
add_action('wp_user_dashboard_setup', 'ybi_add_dashboard_widget');
add_action('wp_newtwork_dashboard_setup', 'ybi_add_dashboard_widget');

function getServerCheckIframe($inName, $inCheckType)
{
	/*
		<p class="advanced_server_check"><a href="<?php echo get_bloginfo('url'); ?>/wp-admin/check-server.php?checkType=<?php echo $inCheckType; ?>" target="_blank">Advanced Server Information <i class="fa fa-mail-forward fa-lg"></i></a></p>
	*/
?>
	<iframe style="clear: both;" class="<?php echo $inName ?>_check_iframe <?php echo $inCheckType ?>_iframe" id="<?php echo $inName ?>_iframe" src="<?php echo get_bloginfo('url'); ?>/wp-admin/check-server.php?checkType=<?php echo $inCheckType; ?>"></iframe>
 <?php
}
?>