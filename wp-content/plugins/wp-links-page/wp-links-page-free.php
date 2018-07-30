<?php
/**
 * @package WP Links Page
 * @version 4.3  */
/*
Plugin Name: WP Links Page
Plugin URI:  http://www.wplinkspage.com/
Description: This plugin provides an easy way to add links to your site.
Version: 4.3
Author: Allyson Rico, Robert Macchi
*/

include_once(ABSPATH.'wp-admin/includes/plugin.php');

function wplpf_is_requirements_met() {

    // Check if WP Links Page Pro is active
    if ( is_plugin_active('wp-links-page-pro/wp-links-page-pro.php') && get_option('wplp_free_passes_req') != 'true' ) {
		
        return false;
    }
	
    return true;
}

function wplpf_disable_plugin() {
	
    if ( current_user_can('activate_plugins') && is_plugin_active( plugin_basename( __FILE__ )) ) {
        deactivate_plugins( plugin_basename( __FILE__ ) );
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
    }
}

function wplpf_show_notice() {
    echo '<div class="notice notice-error"><p><strong>WP Links Page</strong> shouldn\'t be activated while WP Links Page Pro is active. Use WP Links Page Pro instead.</p></div>';
}

if ( !wplpf_is_requirements_met() ) {
include_once(ABSPATH.'wp-admin/includes/plugin.php');
	add_action( 'admin_init', 'wplpf_disable_plugin' );
	add_action( 'admin_notices', 'wplpf_show_notice' );
} else {

add_option( 'wplp_free_passes_req', 'true', '', 'yes' );


$upload_dir = wp_upload_dir();
$wplp_upload = $upload_dir['basedir'].'/wp-links-page/';
if( ! file_exists( $wplp_upload ) )
    wp_mkdir_p( $wplp_upload );

if (!defined('WPLP_UPLOAD_DIR')) {
    define('WPLP_UPLOAD_DIR', $wplp_upload);
}

if (!defined('WPLP_UPLOAD_URL')) {
    define('WPLP_UPLOAD_URL', $upload_dir['baseurl'].'/'.'wp-links-page/');
}

/** Require dependencies */
require_once( ABSPATH . 'wp-admin/includes/image.php' );
require_once( ABSPATH . 'wp-admin/includes/file.php' );
require_once( ABSPATH . 'wp-admin/includes/media.php' );  
require_once( ABSPATH . 'wp-includes/media-template.php' );     

add_filter( 'cron_schedules', 'wp_links_page_add_intervals');

	add_option( 'wplp_screenshot_size', 'small', '', 'yes' );
	add_option( 'wplp_screenshot_refresh', 'monthly', '', 'yes' );
	add_option( 'wplp_migrate', 'false', '', 'yes' );
	
	
	
	add_action( 'wpmu_new_blog', 'wplp_new_blog', 10, 6);   
	add_action( 'wp_links_page_event', 'wp_links_page_event_hook');
	
	register_activation_hook( __FILE__, 'wp_links_page_setup_schedule');
	register_deactivation_hook( __FILE__, 'wp_links_page_deactivation');
	


/** Admin Init **/
if ( is_admin() ) {
	add_action( 'init', 'wplp_create_link_post_type' );
	add_action( 'admin_init', 'wp_links_page_settings');
	add_action( 'add_meta_boxes_wplp_link', 'wplp_links_metaboxes' );
	add_action( 'admin_menu', 'wplp_edit_admin_menus' );
	add_action( 'admin_menu', 'wplp_menu');
	add_action( 'admin_enqueue_scripts', 'wplp_admin_enqueue_scripts' );
}


function wplp_menu() {
		$wplp_page = add_menu_page(
			'WP Links Page',
			'WP Links Page',
			'manage_options',
			'wplp-menu',
			'',
			'dashicons-admin-links',
			'5.8257894758322002900858');
		$wplp_subpage4 = add_submenu_page(
			'wplp-menu',
			'WP Links Page | Add New Link',
			'Add New Link',
			'manage_options',
			'post-new.php?post_type=wplp_link');
		$wplp_subpage3 = add_submenu_page(
			'wplp-menu',
			'WP Links Page | Shortcode',
			'Shortcode',
			'manage_options',
			'wplp_subpage3-menu',
			'wplp_shortcode_page');
		$wplp_subpage = add_submenu_page(
			'wplp-menu',
			'WP Links Page | Settings',
			'Settings',
			'manage_options',
			'wplp_subpage-menu',
			'wplp_subpage_options');
		$wplp_subpage2 = add_submenu_page(
			'wplp-menu',
			'WP Links Page | Help',
			'Help',
			'manage_options',
			'wplp_subpage2-menu',
			'wplp_help_page');
		
	}
	
	function wplp_enqueue_shortcode_scripts($posts) {
		wp_register_style( 'wplp-display-style',  plugins_url( 'wp-links-page/css/wp-links-display.css', 'wp-links-page' ), array(), false, 'all' );
	}
	
	add_action( 'wp_enqueue_scripts', 'wplp_enqueue_shortcode_scripts' );
	
	function wplp_admin_enqueue_scripts( $hook ) {
		global $typenow;
		if (($hook == 'post-new.php' || $hook == 'edit.php' || $hook == 'post.php')  && $typenow == 'wplp_link') {
		wp_enqueue_script('jquery-ui-progressbar');
		wp_enqueue_script( 'wplp-js', plugins_url( 'wp-links-page/js/wp-links-page.js', 'wp-links-page' ), array( 'jquery', 'jquery-ui-progressbar' ), null, true );
		wp_enqueue_script( 'wplp-qe-js', plugins_url( 'wp-links-page/js/wp-links-page-quick-edit.js', 'wp-links-page' ), array( 'jquery', 'inline-edit-post' ), '', true );
		wp_localize_script( 'wplp-js', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		$translation_array = array( 'pluginUrl' => plugins_url( 'wp-links-page' ) );
		//after wp_enqueue_script
		wp_localize_script( 'wplp-js', 'wplp', $translation_array );
		wp_enqueue_style('wplp-admin-ui-css', plugins_url( 'wp-links-page/css/jquery-ui.css', 'wp-links-page' ),false, '', false);
		wp_enqueue_style( 'wplp-style',  plugins_url( 'wp-links-page/css/wp-links-page.css', 'wp-links-page' ), null, null, false );
		} else if ($hook == 'wp-links-page_page_wplp_subpage-menu') {
			wp_enqueue_script('jquery-ui-progressbar');
			wp_enqueue_script( 'wplp-js', plugins_url( 'wp-links-page/js/wp-links-page.js', 'wp-links-page' ), array( 'jquery', 'jquery-ui-progressbar' ), null, true );
			wp_localize_script( 'wplp-js', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
			wp_enqueue_media();
			wp_enqueue_style('wplp-admin-ui-css', plugins_url( 'wp-links-page/css/jquery-ui.css', 'wp-links-page' ),false, '', false);
			wp_enqueue_style( 'wplp-style',  plugins_url( 'wp-links-page/css/wp-links-page.css', 'wp-links-page' ), null, null, false );
		} else if ($hook == 'wp-links-page_page_wplp_subpage3-menu') {
			wp_enqueue_script( 'wplp-shortcode-js', plugins_url( 'wp-links-page/js/wp-links-shortcode.js', 'wp-links-page' ), array( 'jquery', 'jquery-ui-tabs' ), null, true );
			wp_enqueue_style( 'wplp-style',  plugins_url( 'wp-links-page/css/wp-links-page.css', 'wp-links-page' ), null, null, false );
			wp_enqueue_style( 'ti-style',  plugins_url( 'wp-links-page/css/themify-icons.css', 'wp-links-page' ), null, null, false );
		} else if ($hook = 'wp-links-page_page_wplp_subpage2-menu') {
			wp_enqueue_style( 'wplp-style',  plugins_url( 'wp-links-page/css/wp-links-page.css', 'wp-links-page' ), null, null, false );
		}
	
	}

function wplp_edit_admin_menus() {
	global $submenu;

	if ( current_user_can( 'activate_plugins' ) ) {
		$submenu['wplp-menu'][0][0] = 'All Links';
	}
}

function wp_links_page_add_intervals($schedules) {
		$schedules['twodays'] = array(
			'interval' => 172800,
			'display' => __('Every Other Day')
		);
		$schedules['threedays'] = array(
			'interval' => 259200,
			'display' => __('Every Three Days')
		);
		$schedules['weekly'] = array(
			'interval' => 604800,
			'display' => __('Weekly')
		);
		$schedules['biweekly'] = array(
			'interval' => 1209600,
			'display' => __('Every Two Weeks')
		);
		$schedules['monthly'] = array(
			'interval' => 2635200,
			'display' => __('Monthly')
		);
		return $schedules;
	}
	function wp_links_page_setup_schedule() {
		$screenshot_refresh = esc_attr( get_option('wplp_screenshot_refresh') );
		wp_clear_scheduled_hook( 'wp_links_page_event' );
		wp_clear_scheduled_hook( 'wp_links_page_free_event' );
		wp_schedule_event( time(), $screenshot_refresh, 'wp_links_page_event');
	}
	
	function wp_links_page_deactivation() {
		wp_clear_scheduled_hook( 'wp_links_page_event' );
		delete_option( 'wplp_free_passes_req' );
	}
	
	function wp_links_page_event_hook() {
		global $wpdb;
		$custom_post_type = 'wplp_link'; 
		$results = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish'", $custom_post_type ), ARRAY_A );
		$total = '';
		
		foreach ($results as $index => $post) {
			$arg = array($post['ID'],false);
			wp_schedule_single_event( time(), 'wp_ajax_wplp_ajax_update_screenshots', $arg );
		}
	}
	
	
	function wplp_ajax_update_screenshots($id = '', $override = false) {
		if (isset($_REQUEST['id'])) {
			$id = $_REQUEST['id'];
			$id = sanitize_text_field($id);
		} elseif (empty($id)) {
		 die(json_encode(array('message' => 'ERROR', 'code' => 1336)));
		}
		
		$post = get_post($id);
		$mk = wplp_filter_metadata( get_post_meta( $id ) );
		
		$ss_size = get_option('wplp_screenshot_size');	
		
		if (!empty($mk['wplp_screenshot_url'])) {
			$url = $mk['wplp_screenshot_url'];	
		} else { $url = $post->post_title; }
		
		if (!empty($mk['wplp_display'])) {
			$display = $mk['wplp_display'];	
		} else { $display = $post->post_title; }
		
		if (isset($url)) {
			if (!(substr($url, 0, 4) == 'http')) {
				$url = 'https://' . $url;
			}
		}else {die();}
		
				
		if ($mk['wplp_no_update'] != 'no' && $mk['wplp_media_image'] != 'true') {
		
			if ($ss_size == 'large') {
				
					$wplp_featured_image = "http://s.wordpress.com/mshots/v1/".$url."?w=1280";
					
					
					// Add Featured Image to Post
					$image_url        = $wplp_featured_image; // Define the image URL here
					$image_name       = preg_replace("/[^a-zA-Z0-9]+/", "", $display);
					wplp_large_screenshot($image_url, $image_name, $id);
					
				} elseif ($ss_size == 'small') {
		
					$image_name       = preg_replace("/[^a-zA-Z0-9]+/", "", $display);
					wplp_small_screenshot_url($image_name, $url, $id);
				}
			}
	}
	
	add_action( 'wp_ajax_wplp_ajax_update_screenshots', 'wplp_ajax_update_screenshots');
	

function wplp_create_link_post_type() {
    register_post_type( 'wplp_link',
        array(
            'labels' => array(
                'name' => 'Links',
                'singular_name' => 'Link',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Link',
                'edit' => 'Edit',
                'edit_item' => 'Edit Link',
                'new_item' => 'New Link',
                'view' => 'View',
                'view_item' => 'View Link',
                'search_items' => 'Search Links',
                'not_found' => 'No Links found',
                'not_found_in_trash' => 'No Links found in Trash',
                'parent' => 'Parent Link',
				'menu_name' => 'WP Links Page'
            ),
 
            'public' => false,
            'menu_position' => 5.8257894758322002900858,
            'supports' => array( 'title', 'editor', 'page-attributes'),
            'taxonomies' => array(),
			'hierarchical' => true,
            'menu_icon' => 'dashicons-admin-links',
            'has_archive' => true,
			'show_in_menu' => 'wplp-menu',
			'show_ui' => true,
        )
    );
}

/**
 * Query Filter for Custom Post Types
 */
 function wplp_query_filter($query) {
  if ( is_admin() && $query->query['post_type'] == 'wplp_link' ) {
	$query->set( 'orderby', 'ID' );
    $query->set( 'order', 'DESC' );
  }
}


add_action('pre_get_posts','wplp_query_filter');

/**
 * Change edit.php page
*/

add_action( 'load-edit.php', function() {
  add_filter( 'views_edit-wplp_link', 'wplp_link_edit' );
});

function wplp_link_edit($views) {
	global $wpdb;
	$custom_post_type = 'wplp_link'; 
    $results = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish'", $custom_post_type ), ARRAY_A );
	$total = '';
	$update = '';

    foreach( $results as $index => $post ) {
		if ($total == '') {
			$total = $post['ID'];
		} else {
        	$total .= ','.$post['ID'];
		}
    }
	
if ($total == '') {
	$migrate = esc_attr( get_option('wplp_migrate'));
	global $wpdb;
	$table_name = $wpdb->prefix.'wp_links_page_free_table';
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name || $migrate == 'yes') {
		$update = '';
	} else {
		$update = '<div class="notice notice-error"><p>Please visit the <a href="/wp-admin/admin.php?page=wplp_subpage-menu" >Settings Page</a> to update your links from the previous version. You may also need to adjust your shortcode using the <a href="/wp-admin/admin.php?page=wplp_subpage3-menu" >Shortcode Builder</a>.</p></div>';
	}
	}

 echo $update.'
  <button id="update-screenshots" class="button button-primary button-large" style="float:left; margin-right: 20px;" data-total="'.$total.'">Update Screenshots</button>
	<div id="progressbar">
              <div class="progress-label"></div>
        </div><div class="clearfix" style="clear:both"></div>
 ';
 return $views;
}

add_action( 'admin_head-edit.php', 'wplp_quick_edit_remove' );

function wplp_quick_edit_remove() 
{    
    /**
       /wp-admin/edit.php?post_type=post
       /wp-admin/edit.php?post_type=page
       /wp-admin/edit.php?post_type=cpt  == gallery in this example
     */

    global $current_screen;
    if( 'edit-wplp_link' != $current_screen->id )
        return;
    ?>
    <script type="text/javascript">         
        jQuery(document).ready( function($) {
			$('span.title:contains("Title")').each(function (i) {
                $(this).html('Link Url');
				$(this).parent().parent().append('<label><span class="title">Link Display</span><span class="input-text-wrap"><input type="text" name="wplp_display" value="" /></span></label><label><span class="title">Description</span><textarea name="wplp_description"></textarea></label><br class="clear">');
            });
			$('span:contains("Slug")').each(function (i) {
                $(this).parent().remove();
            });
            $('span:contains("Password")').each(function (i) {
                $(this).parent().parent().remove();
            });
            $('span:contains("Date")').each(function (i) {
                $(this).parent().remove();
            });
            $('.inline-edit-date').each(function (i) {
                $(this).remove();
            });
			$('#wplp-custom.inline-edit-col-left').each(function (i) {
				$(this).css('font-weight:bold;');
			});
        });    
    </script>
    <?php
}
/**
 * Edit Custom Post Type List
 */
 
 add_filter( 'manage_wplp_link_posts_columns', 'set_custom_edit_wplp_link_columns' );
 add_action( 'manage_wplp_link_posts_custom_column' , 'wplp_custom_columns', 10, 2 );

function set_custom_edit_wplp_link_columns($columns) {
    unset( $columns['author'] );
    unset( $columns['date'] );
    $columns['screenshot'] = 'Screenshot';
    $columns['description'] = 'Description';
	$columns['title'] = 'Link Url';
    $columns['id'] = 'ID';
	
	$a = $columns;
	$b = array('cb', 'screenshot', 'title', 'description', 'id'); // rule indicating new key order
	$c = array();
	foreach($b as $index) {
		$c[$index] = $a[$index];
	}
	$columns = $c;
	
    return $columns;
}

function wplp_custom_columns( $column, $post_id ) {
	switch ( $column ) {
		case 'id':
			echo $post_id;
			break;
		case 'screenshot':
			$display = get_post_meta( $post_id, 'wplp_display', true );
			$image = get_the_post_thumbnail( $post_id, 'thumbnail' );
			echo $image.'<p id="wplp_display_'.$post_id.'" class="hidden">'.$display.'</p>';
			break;
			
		case 'description':
			$content_post = get_post($post_id);
			$content = $content_post->post_content;
			$content = apply_filters('the_content', $content);
			$content = str_replace(']]>', ']]&gt;', $content);
			echo '<div id="wplp_description_'.$post_id.'">'.$content.'</div>';
			break;
			
	}
}


function wplp_link_display_title( $title, $id = null ) {
	if (is_admin()) {
		if (get_post_type($id) == 'wplp_link') {
			$display = get_post_meta( $id, 'wplp_display', true );
			if ($display == '') {
				$display = $title;	
			}
			return $display;
		} else { return $title; }
	} else { return $title; }
}
add_filter( 'the_title', 'wplp_link_display_title', 10, 2 );


/**
 *   Adds a metabox
 */
function wplp_links_metaboxes() {
		
	add_meta_box(
		'wplp_display',
		'Link Display',
		'wplp_display_func',
		'wplp_link',
		'normal',
		'default'
	);
	
	add_meta_box( 
		'wplp_screenshot', 
		'Screenshot', 
		'wplp_post_thumbnail_meta_box', 
		'wplp_link', 
		'normal', 
		'default' );
		
}

function wplp_display_func() {
	
	global $post;
	// Nonce field to validate form request came from current site
	wp_nonce_field( basename( __FILE__ ), 'wplp_fields' );
	
	// Get the display data if it's already been entered
	$display = get_post_meta( $post->ID, 'wplp_display', true );
	if ($display == "Auto Draft") { $display = ''; }
    
    echo '<label for="display">Link Display</label>
    <p class="description">This field defaults to the link domain.</p>
    <input id="wplp_display" name="wplp_display" maxlength="255" type="text" value="'.$display.'">';
    
}

function wplp_post_thumbnail_meta_box( $post ) {
	$mk = wplp_filter_metadata( get_post_meta( $post->ID ) );
	$thumb_id = get_post_thumbnail_id( $post->ID );
	$thumb = wp_get_attachment_url($thumb_id);
	if ($thumb != '' ) {
		$display = '';	
	} else $display = 'display:none;';
	$screenshot_size = get_option( 'wplp_screenshot_size');
	if (isset($mk['wplp_media_image'])) { $media = $mk['wplp_media_image'];} else {$media = '';}
	if (isset($mk['wplp_screenshot_url'])) { $screenshot_url = $mk['wplp_screenshot_url'];} else {$screenshot_url = '';}
	if (isset($mk['wplp_no_update'])) { $no_update = $mk['wplp_no_update'];} else {$no_update = '';}
	if (empty($media)) $media = 'false';
	
		echo '<img class="wplp_featured" src="'.$thumb.'" style="'.$display.' width:300px;" />
		<p class="description">Screenshot will automatically generate once you enter the link url or you can provide a different url to retrieve a screenshot from in this field. Click "Generate Screenshot" below to retrieve the new screenshot.</p>
		<label for"wplp_screenshot_url"><b>Screenshot URL: &nbsp;&nbsp;<b></label><input id="wplp_screenshot_url" type="text" name="wplp_screenshot_url" value="'.$screenshot_url.'" style="width: 80%;"/>
		<input id="wplp_media_image" type="hidden" name="wplp_media_image" value="'.$media.'" />
		<input id="wplp_featured_image" type="hidden" name="wplp_featured_image" value="'.$thumb_id.'" />
		<input id="wplp_screenshot_size" type="hidden" name="wplp_screenshot_size" value="'.$screenshot_size.'" />
		<br><br><p class="description">If the screenshot not generating properly try using the full url including the "http://" or "https://".</p>
		<p class="hide-if-no-js"> 
		<a class="set-featured-thumbnail setfeatured button" href="#" title="Choose Image">Choose Image</a>
		&nbsp;<a class="set-featured-screenshot generate button button-primary" href="#" title="Generate New Screenshot">Generate New Screenshot</a><br>
		<br><label for="wplp_no_update"><input id="wplp_no_update" type="checkbox" name="wplp_no_update" value="no"';
		if ($no_update == 'no') {
			echo 'checked="checked"';	
		} else echo 'data="not checked"';
		echo ' />Don\'t update this screenshot.</label><br><br>';
} 
	
	function wplp_update_from_previous() {
		if (isset($_REQUEST['id'])) {
			$id = $_REQUEST['id'];
			$id = sanitize_text_field($id);
		} else die(json_encode(array('message' => 'ERROR', 'code' => 'no id')));
		$ss_size = get_option('wplp_screenshot_size');	
		global $wpdb;
		$table = $wpdb->prefix.'wp_links_page_free_table';
		$links = $wpdb->get_results("SELECT * FROM $table WHERE id = $id ORDER BY weight");
			foreach ($links as $link) {
				
				if (!empty($link->display)) {
					$display = $link->display;	
				} else { $display = $link->url; }
				
				$new_link = array(
				  'post_title'    => sanitize_text_field( $link->url ),
				  'post_content'  => wp_kses_post($link->description),
				  'post_status'   => 'publish',
				  'post_type'	  => 'wplp_link',
				  'meta_input' => array(
									'wplp_display' => sanitize_text_field($display),
									'wplp_media_image' => 'false',
								),
				);
				$new = wp_insert_post( $new_link );
				
				$url = $link->url;
				
				if (isset($url)) {
					if (!(substr($url, 0, 4) == 'http')) {
						$url = 'https://' . $url;
					}
				}else {die();}
				
				
					if ($ss_size == 'large') {
						
							$wplp_featured_image = "http://s.wordpress.com/mshots/v1/".$url."?w=1280";
							
							
							// Add Featured Image to Post
							$image_url        = $wplp_featured_image; // Define the image URL here
							$image_name       = preg_replace("/[^a-zA-Z0-9]+/", "", $display);
							wplp_large_screenshot($image_url, $image_name, $new);
							
						} elseif ($ss_size == 'small') {
				
								$image_name       = preg_replace("/[^a-zA-Z0-9]+/", "", $display);
								wplp_small_screenshot_url($image_name, $url, $new);
						}
					}
	}
	
	add_action( 'wp_ajax_wplp_update_from_previous', 'wplp_update_from_previous');

/**
 * Save the metabox data
 */
 
 add_filter( 'wp_insert_post_data' , 'wplp_filter_post_data' , '99', 2 );

function wplp_filter_post_data( $data , $postarr ) {
    // Change post content on quick edit
	if (isset($postarr['action'])) {$action = $postarr['action'];} else {$action = '';}
	if ($postarr['post_type'] == 'wplp_link' && $action == 'inline-save') {
		if (isset($postarr['wplp_description'])) {
			$data['post_content'] = wp_kses_post($postarr['wplp_description']);
			$postarr['post_content'] = wp_kses_post($postarr['wplp_description']);
			$postarr['content'] = wp_kses_post($postarr['wplp_description']);
		}
	}
    return $data;
}
 
function wplp_display_save( $post_id, $post ) {
    /*
     * In production code, $slug should be set only once in the plugin,
     * preferably as a class property, rather than in each function that needs it.
     */
	$post_type = get_post_type($post_id);
	$post_status = get_post_status($post_id);
	 // If this isn't a 'book' post, don't update it.
    if ( "wplp_link" != $post_type || $post_status == 'auto-draft' ) return;
	if (isset($_POST['action'])) {
	if ( $_POST['action'] == 'wplp_update_from_previous' || $_POST['action'] == 'wplp_import_list') return;
	}
	$mk = wplp_filter_metadata( get_post_meta( $post_id ) );
	
	$ss_size = get_option('wplp_screenshot_size');	
	
    // - Update the post's metadata.
	if ( isset( $_POST['wplp_display'] ) ) {
		update_post_meta( $post_id, 'wplp_display', sanitize_text_field( $_POST['wplp_display'] ) );
	} elseif (!isset($mk['wplp_display'])) { 
		update_post_meta( $post_id, 'wplp_display', sanitize_text_field( $_POST['post_title'] ) );
		$_POST['wplp_display'] = $_POST->post_title;
	}
	
	if( isset( $_POST[ 'wplp_no_update' ] ) ) {
		update_post_meta( $post_id, 'wplp_no_update', 'no' );
		$no_update = true;
	} else {
		update_post_meta( $post_id, 'wplp_no_update', 'false' );
		$no_update = false;
	}
	
	if ( isset( $_POST['wplp_screenshot_url'] ) ) {
		update_post_meta( $post_id, 'wplp_screenshot_url', sanitize_text_field( $_POST['wplp_screenshot_url'] ) );
	}
	
	if ( isset( $_POST['wplp_featured_image']) && $_POST['wplp_featured_image'] != '' && !is_numeric($_POST['wplp_featured_image']) ) {
		if ($no_update == true || $_POST['wplp_media_image'] == 'true') {
			update_post_meta( $post_id, 'wplp_no_update', 'no' );
			
		} else {
			if ($ss_size == 'large' ) {
				if (!empty($_POST['wplp_featured_image'])) {
					$image_url        = $_POST['wplp_featured_image']; // Define the image URL here
					$image_name       = preg_replace("/[^a-zA-Z0-9]+/", "", $_POST['wplp_display']);
					wplp_large_screenshot($image_url, $image_name, $post_id);
				}
			} elseif ($ss_size == 'small') {
				
				if (!empty($_POST['wplp_featured_image'])) {
					
					// Add Featured Image to Post
					$image_name       = preg_replace("/[^a-zA-Z0-9]+/", "", $_POST['wplp_display']);
					wplp_small_screenshot($image_name, $_POST['wplp_featured_image'], $post_id);
				}
			
			}
		}
		
    } else if (is_numeric($_POST['wplp_featured_image']) ) {
		
			$post_thumbnail_id = get_post_thumbnail_id( $post_id );
		
		if ($mk['wplp_media_image'] != $_POST['wplp_media_image'] && $_POST['wplp_media_image'] == 'true') {
			$post_thumbnail_id = get_post_thumbnail_id( $post_id );
			if ($post_thumbnail_id != $_POST['wplp_featured_image'] && $mk['wplp_media_image'] == 'false' && !empty($post_thumbnail_id)) {
				wp_delete_attachment( $post_thumbnail_id, true );
			}
		}
		set_post_thumbnail( $post_id, $_POST['wplp_featured_image'] );
		update_post_meta( $post_id, 'wplp_media_image', sanitize_text_field( $_POST['wplp_media_image'] ) );
	} else {
		set_post_thumbnail( $post_id, '' );
	} 
	
	if ( !isset( $mk['wplp_media_image']) && isset($_POST['wplp_media_image'] ) ) {
		update_post_meta( $post_id, 'wplp_media_image', sanitize_text_field( $_POST['wplp_media_image'] ) );
	}

}
add_action( 'save_post', 'wplp_display_save', 10, 3 );

function wplp_delete_func( $postid ){

    global $post_type;   
    if ( $post_type != 'wplp_link' ) return;

	$mk = wplp_filter_metadata( get_post_meta( $postid ) );
	
    $post_thumbnail_id = get_post_thumbnail_id( $postid );
		
	if (!empty($post_thumbnail_id) && $mk['wplp_media_image'] == 'false') {
		wp_delete_attachment( $post_thumbnail_id, true );
	}
	
}
add_action( 'before_delete_post', 'wplp_delete_func' );

add_filter('gettext', 'wplp_text_filter', 20, 3);
/*
 * Change the text in the admin for my custom post type
 * 
**/
function wplp_text_filter( $translated_text, $untranslated_text, $domain ) {

  global $typenow;

  if( is_admin() && 'wplp_link' == $typenow )  {

    //make the changes to the text
    switch( $untranslated_text ) {

        case 'Enter title here':
          $translated_text = __( 'Enter Link Url','text_domain' );
        break;
		
     }
   }
   return $translated_text;
}

function wplp_array_push_assoc($array, $key, $value){
$array[$key] = $value;
return $array;
}

function wplp_filter_metadata($array){
$mk = array();
foreach($array as $k => $v){
if(is_array($v) && count($v) == 1){
$mk = wplp_array_push_assoc($mk, $k, $v[0]);
} else {
$mk = wplp_array_push_assoc($mk, $k, $v);
}
}
return $mk;
}

function wplp_help_page() {
	?>
    <h1>Documentation</h1>
	<div class="fusion-one-half fusion-layout-column fusion-spacing-yes" style="margin-top:0px;margin-bottom:20px;"><div class="fusion-column-wrapper"><h3><strong>Installation</strong></h3>
	<h4>Uploading via WordPress Dashboard</h4>
	<ol>
	<li>Navigate to the &#8216;Add New&#8217; in the plugins dashboard</li>
	<li>Navigate to the &#8216;Upload&#8217; area</li>
	<li>Select wp-links-page.zip from your computer</li>
	<li>Click &#8216;Install Now&#8217;</li>
	<li>Activate the plugin in the Plugin dashboard</li>
	</ol>
	<h4>Using FTP</h4>
	<ol>
	<li>Download wp-links-page.zip</li>
	<li>Extract the wp-links-page.zip directory to your computer</li>
	<li>Upload the wp-links-page.zip directory to the <code>/wp-content/plugins/</code> directory</li>
	<li>Activate the plugin in the Plugin dashboard</li>
	</ol>
	</div></div><div class="fusion-one-half fusion-layout-column fusion-column-last fusion-spacing-yes" style="margin-top:0px;margin-bottom:20px;"><div class="fusion-column-wrapper"><p><img class="alignnone size-full wp-image-126" src="<?php echo plugins_url( "images/Install-Plugin.jpg", __FILE__ ); ?>" alt="Install Plugin" /></p>
	</div></div><div class="fusion-clearfix"></div><div class="fusion-sep-clear"></div><div class="fusion-separator fusion-full-width-sep sep-single" style="border-color:#e0dede;border-top-width:1px;margin-left: auto;margin-right: auto;margin-top:px;margin-bottom:30px;"></div>
	<div class="fusion-one-half fusion-layout-column fusion-spacing-yes" style="margin-top:0px;margin-bottom:20px;"><div class="fusion-column-wrapper"><h3><strong>Adding and Editing Links</strong></h3>
	<p >Visit the WP Links Page section of the dashboard to add and edit the links.</p>
	<p >Click the Add New Link button or menu item to get started.</p>
	<p >Adding a link is much like adding a Post.</p>
    <p >When adding a link, as soon as you finish entering the Link Url and then move out of that field, the screenshot will populate automatically for you. You can enter the description here as well.</p>
    <p>If you wish to pull the screenshot from one url but have the link go to a different address, as is the case with most affiliate links, enter the url you wish the screenshot to come from in the 'Screenshot Url' field and click 'Generate Screenshot'.</p>
    <p>Sometimes WP Links Page cannot retrieve a screenshot because a website is built with flash, has a slow loading time, or for other reasons. If you should need to use your own image instead of the automatic screenshot WP Links Page generates, simply click 'Choose Image' in the Screenshot box on the add/edit link screen and choose a new image from the media library. Should you wish to return to using a screenshot simply click 'Generate New Screenshot' which is next to 'Choose Image'.</p>
    <p >To edit your links simply click the edit link inside the "All Links" page as you would with regular posts. You will be brought to the same form you used when adding your link originally. Make any changes and then click Update to save them.</p>
   </div></div><div class="fusion-one-half fusion-layout-column fusion-column-last fusion-spacing-yes" style="margin-top:0px;margin-bottom:20px;"><div class="fusion-column-wrapper"><p><img class="alignnone size-full wp-image-205" src="<?php echo plugins_url( "images/Add-New-Link.jpg", __FILE__ ); ?>" alt="add edit links" /></p>
	</div></div><div class="fusion-clearfix"></div><div class="fusion-sep-clear"></div><div class="fusion-separator fusion-full-width-sep sep-single" style="border-color:#e0dede;border-top-width:1px;margin-left: auto;margin-right: auto;margin-top:px;margin-bottom:30px;"></div>
    <div class="fusion-one-half fusion-layout-column fusion-spacing-yes" style="margin-top:0px;margin-bottom:20px;"><div class="fusion-column-wrapper"><h3><strong>All Links</strong></h3>
    <p>On the 'All Links' page you can view all of the links you have on your site.</p>
    <p>The links are sorted by their ID and should appear with the newest links first going to the oldest links.</p>
    <p>You can change the sort with filters at the top of this page, and even search for a particlular link that you need to edit.</p>
	<p>With the quick edit you can change the link url, link display, description, order, and status or each link easily.</p>
    <p >Clicking the ‘Update Screenshots’ button on the 'All Links' page can take several minutes depending on your connection and the amount of links you have. Please be patient while it retrieves new images. A progress bar will display to show you how much longer you will need to wait.</p>
    </div></div><div class="fusion-one-half fusion-layout-column fusion-column-last fusion-spacing-yes" style="margin-top:0px;margin-bottom:20px;"><div class="fusion-column-wrapper"><p><img class="alignnone size-full wp-image-205" src="<?php echo plugins_url( "images/Links List.jpg", __FILE__ ); ?>" alt="add edit links" /></p>
	</div></div><div class="fusion-clearfix"></div><div class="fusion-sep-clear"></div><div class="fusion-separator fusion-full-width-sep sep-single" style="border-color:#e0dede;border-top-width:1px;margin-left: auto;margin-right: auto;margin-top:px;margin-bottom:30px;"></div>
	<div class="fusion-one-half fusion-layout-column fusion-spacing-yes" style="margin-top:0px;margin-bottom:20px;"><div class="fusion-column-wrapper"><h3><strong>Shortcode Builder</strong></h3>
	<p >Visit the 'Shortcode' page in the WP Links Page section to create the right shortcode for your desired display.</p>
    <p>You do not need to select all options, only the options you desire. The shortcode will display using default options for all the choices you do not select.</p>
	<p>In the Display section you will choose which kind of display your links should have: Grid or List. Compact List and Carousel are only available in WP Links Page Pro.</p>
    <p>The Display Settings section will give you options specific to the display you choose, since each display has its own set of options such as the number of columns for the grid.<p>
    <p>Certain Features are only available in the pro, but those options are disabled so that you do not accidentally select one.</p>
    <br />
    <p style="font-weight:bold">As you choose options on this page your shortcode will appear at the bottom of the page. This shortcode will not remain after you leave the page. Please copy and paste your shortcode before you exit the page to avoid having to build your display again.</p>
    <br />
    <p>These are the available options for the WP Links Page shortcode:</p>
    <ul>
	<li><b>'display'</b> - This is used for each display type. The options are 'grid' or 'list'. The default for this field is 'grid'. Ex: display="grid"</li>
	<li><b>'cols'</b> - This is the number of columns your grid should have. You can enter any number into this field. The default for this field is '3'. Ex: cols="3"</li>
	<li><b>'img_size'</b> - This is what size of image to use in your display. The options vary from site to site. This field will accept any registered Wordpress image size. The default, 'medium', is one of the standard wordpress sizes, and the Shortcode page lists other standard sizes you can use. Ex: img_size="medium"</li>
	<li><b>'desc'</b> - This lets you choose which description to use in your display. Your options are either 'content' which is the Description, or 'none' which will leave out the description. You may also leave this blank for no description to show, which is the default. Ex: desc="content"</li>
    </ul>
	</div></div><div class="fusion-one-half fusion-layout-column fusion-column-last fusion-spacing-yes" style="margin-top:0px;margin-bottom:20px;"><div class="fusion-column-wrapper"><p><img class="alignnone size-full wp-image-110" src="<?php echo plugins_url( "images/Shortcodes.jpg", __FILE__ ); ?>" alt="settings page" /></p>
	</div></div><div class="fusion-clearfix"></div><div class="fusion-sep-clear"></div><div class="fusion-separator fusion-full-width-sep sep-single" style="border-color:#e0dede;border-top-width:1px;margin-left: auto;margin-right: auto;margin-top:px;margin-bottom:30px;"></div>
	<div class="fusion-one-half fusion-layout-column fusion-spacing-yes" style="margin-top:0px;margin-bottom:20px;"><div class="fusion-column-wrapper"><h3><strong>Settings</strong></h3>
	<p >On the 'Settings' page you have various options for your Links.</p>
    <p>For anyone who used an older version of WP Links Page you will see the 'Update Your Links' section. By clicking 'Update Links' here WP Links Page will automatically import your links from the older version. A progressbar will be displayed while the update is happening. Once finished it will notify you of any errors or if all links were updated successfully. It will not delete them from the old version, should you choose to revert to that version at a later date.</p>
    <p>The Screenshot size option allows you to choose which size of screenshot to retrieve. This is actually a choice between two different screenshot API's to use. The 320px Width uses a Google API and is in our opinion very reliable. However, the images are small. The 1200px Width uses a Wordpress API that is a little less reliable, but most sites work very well with this API.</p>
    <p>The screenshot refresh rate is how often to generate new screenshots. The options are: Never, Twice Daily, Daily, Every two days, Weekly, Every two Weeks, Monthly.</p>
	</div></div><div class="fusion-one-half fusion-layout-column fusion-column-last fusion-spacing-yes" style="margin-top:0px;margin-bottom:20px;"><div class="fusion-column-wrapper"><p><img class="alignnone wp-image-184 size-full" src="<?php echo plugins_url( "images/Settings.jpg", __FILE__ ); ?>" alt="Shortcode Example" /></p>
	</div></div><div class="fusion-clearfix"></div><div class="fusion-sep-clear"></div><div class="fusion-separator fusion-full-width-sep sep-single" style="border-color:#e0dede;border-top-width:1px;margin-left: auto;margin-right: auto;margin-top:px;margin-bottom:30px;"></div>
	
	<a href="http://www.wplinkspage.com/" target="_blank">To Upgrade to WP Links Page Pro, or for further assistance please visit us at wplinkspage.com</a>
    
	<?php
}

function wplp_shortcode_page() {
	?>
    <style>#wpfooter {bottom: 100%;}</style>
		<h2>Shortcodes</h2>
		<p class="description">Here you can generate shortcode based on the options you choose.</p>
          
        <div id="wplp-sb">
  <div id="tabs-1" aria-labelledby="ui-id-1">
    <h3>Display</h3>
    <hr style="border: 1px solid; width: 50%;" align="left">
    <div class="radio-i">
  	<p>Which display would you like to use?</p>
    <label><input name="wplp-display" value="grid" type="radio"><i class="ti-size-xxl ti-layout-grid3-alt"></i><br><span>Grid</span></label>
  	<label><input name="wplp-display" value="list" type="radio"><i class="ti-size-xxl ti-layout-list-thumb-alt"></i><br><span>List</span></label>
  	<label class="pro"><input name="wplp-display" value="compactlist" type="radio" disabled="disabled"><i class="ti-size-xxl ti-list"></i><br><span>Compact List</span><br><span class="tooltiptext">Only Available in WP Links Page Pro</span></label>
  	<label class="pro"><input name="wplp-display" value="carousel" type="radio" disabled="disabled"><i class="ti-size-xxl ti-layout-slider"></i><br><span>Carousel</span><br><span class="tooltiptext">Only Available in WP Links Page Pro</span></label>
    </div>
  </div>
  <br><br>
  <div id="tabs-2">
  <h3>Display Settings</h3>
  <hr style="border: 1px solid; width: 50%;" align="left">
  <p class="description">Choose a display above to see the settings available for that display.</p>
  <div class="grid radio-no-i">
  	<p>How many columns should your grid have?</p>
    <label><input type="radio" name="wplp-columns" value="2"><br><span>2 Columns</span></label>
  	<label><input type="radio" name="wplp-columns" value="3"><br><span>3 Columns</span></label>
  	<label><input type="radio" name="wplp-columns" value="4"><br><span>4 Columns</span></label>
  	<label><input type="radio" name="wplp-columns" value="5"><br><span>5 Columns</span></label>
  	<label><input type="radio" name="wplp-columns" value="6"><br><span>6 Columns</span></label>
    <br>
 </div>
 <div class="radio-no-i">
    <div>
    <p>What size of image should this display use?</p>
    <label><input type="radio" name="wplp-image-size" value="thumbnail"><br><span>Thumbnail</span></label>
    <label><input type="radio" name="wplp-image-size" value="medium"><br><span>Medium</span></label>
    <label><input type="radio" name="wplp-image-size" value="large"><br><span>Large</span></label>
    <label><input type="radio" name="wplp-image-size" value="full"><br><span>Original</span></label>
    <br>
    </div>
    <div class="radio-no-i">
    <p>Should it show the description?</p>
    <label><input type="radio" name="wplp-desc" value="content"><br><span>Yes</span></label>
    <label><input type="radio" name="wplp-desc" value="none"><br><span>No</span></label><br>
    </div></div>
  
  </div>
  <br><br>
  <p class="description"><a href="http://wplinkspage.com/">More Shortcode Options are available in WP Links Page Pro</a></p>
<div class="wplp-shortcode">
<p>Your Shortcode</p>
	<textarea id="final-shortcode">[wp_links_page]</textarea>
</div>
</div>
    <?php
}

function wplp_subpage_options() {
	
		if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
			$sr = get_option('wplp_screenshot_refresh');
			$timestamp = time();
			if ($sr == 'twicedaily') {$rate = '+12 hours';}
			if ($sr == 'daily') {$rate = '+1 day';}
			if ($sr == 'twodays') {$rate = '+2 days';}
			if ($sr == 'threedays') {$rate = '+3 days';}
			if ($sr == 'weekly') {$rate = '+1 week';}
			if ($sr == 'biweekly') {$rate = '+2 weeks';}
			if ($sr == 'monthly') {$rate = '+1 month';}
			if ($sr == 'never') {
				wp_clear_scheduled_hook( 'wp_links_page_event' );
			} else {
				$exists = wp_get_schedule( 'wp_links_page_event' );
				if ($exists == false) {
					wp_schedule_event(time(), $sr, 'wp_links_page_event');
				} else {
				$next_event = strtotime($rate, $timestamp);
				$time = wp_next_scheduled( 'wp_links_page_event' );
				wp_clear_scheduled_hook( 'wp_links_page_event' );
				wp_schedule_event( $next_event, $sr, 'wp_links_page_event' );
				}
			}
		}
		echo '<div class="wrap wplp-settings">
		<h1>WP Links Page Settings</h1>';
		echo '<form method="post" action="options.php">';
		settings_fields( 'wp-links-page-option-group' );
		do_settings_sections( 'wp-links-page-option-group' );
		
		
		$migrate = esc_attr( get_option('wplp_migrate'));
		global $wpdb;
		$table_name = $wpdb->prefix.'wp_links_page_free_table';
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name || $migrate == 'no') {
			 $update = '';
		} else {
		global $wpdb;
		$table = $wpdb->prefix.'wp_links_page_free_table';
		$links = $wpdb->get_results("SELECT * FROM $table ORDER BY weight");
		$ids = '';
		foreach ($links as $link) {
			if ($ids == '') {
				$ids = $link->id; 
			} else {
			$ids .= ','.$link->id;	
			}
		}
		$update = '<tr>
			<th scope="row"><label class="label" for="wplp_migrate" >Update Your Links</label></th>
	        <td class="update">
			<a id="update_wplp" class="update_wplp button button-large" style="float:left; margin-right: 15px;" href="#" data-total="'.$ids.'">Update Links</a>
		<div id="progressbar"><div class="progress-label"></div></div>
		<p class="error update" data-total=""></p>
		<p class="success update" data-total=""></p>
		<p class="description" style="clear:both;">We noticed that you\'ve used an earlier version of WP Links Page on this site before. Would you like to import the links from that version into this one?<br>If you have a large number of links this may take several minutes.</p>
		<label><input id="wplp_migrate" class="regular-text ltr" type="checkbox" name="wplp_migrate" value="yes"> Don\'t show this option again.</label>
		</div>
		</td></tr>';
		}
		
		$screenshot_size = esc_attr( get_option('wplp_screenshot_size') );
		$screenshot_refresh = esc_attr( get_option('wplp_screenshot_refresh') );
		echo '<table class="form-table"><tbody>'.$update.'
		<tr>
			<th scope="row" class="screenshot"><label class="label" for="wplp_screenshot_size" >Screenshot Size</label></th>
	        <td class="screenshot">
			<label><input type="radio" name="wplp_screenshot_size" value="small" ';
		echo ($screenshot_size=='small')?'checked':'';
		echo ' >320px Width</label><br>
			<label><input type="radio" name="wplp_screenshot_size" value="large" ';
		echo ($screenshot_size=='large')?'checked':'';
		echo ' >1200px Width</label><br/>';
		if ($screenshot_size == 'small') {$screenshot_size = '320px Width';}
		if ($screenshot_size == 'large') {$screenshot_size = '1200px Width';}
		echo '<p class="description">What size of screenshots should WP Links Page retrieve?<br/>The screenshot size is currently set to '.$screenshot_size.'.</p></td></tr>';
		echo '<tr>
			<th scope="row" class="screenshot" ><label class="label" for="wplp_screenshot_refresh" >Screenshot Refresh Rate</label></th>
	        <td class="screenshot" >
			<label><input type="radio" name="wplp_screenshot_refresh" value="never" data-current="'.$screenshot_refresh.'" ';
		echo ($screenshot_refresh=='never')?'checked':'';
		echo ' >Never</label><br/>
			<label><input type="radio" name="wplp_screenshot_refresh" value="twicedaily" ';
		echo ($screenshot_refresh=='twicedaily')?'checked':'';
		echo ' >Twice Daily</label><br/>
			<label><input type="radio" name="wplp_screenshot_refresh" value="daily" ';
		echo ($screenshot_refresh=='daily')?'checked':'';
		echo ' >Daily</label><br/>
			<label><input type="radio" name="wplp_screenshot_refresh" value="twodays" ';
		echo ($screenshot_refresh=='twodays')?'checked':'';
		echo ' >Every Two Days</label><br/>
			<label><input type="radio" name="wplp_screenshot_refresh" value="threedays" ';
		echo ($screenshot_refresh=='threedays')?'checked':'';
		echo ' >Every Three Days</label><br/>
			<label><input type="radio" name="wplp_screenshot_refresh" value="weekly" ';
		echo ($screenshot_refresh=='weekly')?'checked':'';
		echo ' >Weekly</label><br/>
			<label><input type="radio" name="wplp_screenshot_refresh" value="biweekly" ';
		echo ($screenshot_refresh=='biweekly')?'checked':'';
		echo ' >Every Two Weeks</label><br/>
			<label><input type="radio" name="wplp_screenshot_refresh" value="monthly" ';
		echo ($screenshot_refresh=='monthly')?'checked':'';
		echo ' >Monthly</label><br/>';
		if ($screenshot_refresh == 'never') {$screenshot_refresh = 'Never';}
		if ($screenshot_refresh == 'twicedaily') {$screenshot_refresh = 'Twice Daily';}
		if ($screenshot_refresh == 'daily') {$screenshot_refresh = 'Daily';}
		if ($screenshot_refresh == 'twodays') {$screenshot_refresh = 'Two Days';}
		if ($screenshot_refresh == 'threedays') {$screenshot_refresh = 'Every Three Days';}
		if ($screenshot_refresh == 'weekly') {$screenshot_refresh = 'Weekly';}
		if ($screenshot_refresh == 'biweekly') {$screenshot_refresh = 'Every Two Weeks';}
		if ($screenshot_refresh == 'monthly') {$screenshot_refresh = 'Monthly';}
		echo '<p class="description">How often should WP Links Page get new screenshots for your links?<br/>The refresh rate is currently set to '.$screenshot_refresh.'.</p></td></tr>';
			echo '</td></tr></tbody></table>';
		submit_button();
	}
	
	function wp_links_page_settings() { // whitelist options 
		register_setting( 'wp-links-page-option-group', 'wplp_screenshot_size' );
		register_setting( 'wp-links-page-option-group', 'wplp_screenshot_refresh' );
		register_setting( 'wp-links-page-option-group', 'wplp_migrate' );
	}
	
	function wplp_large_screenshot($image_url, $image_name, $post_id) {
		// Add Featured Image to Post
		$upload_dir       = WPLP_UPLOAD_DIR; // Set upload folder
		$unique_file_name = wp_unique_filename( $upload_dir, $image_name ); // Generate unique name
		$filename         = basename( $unique_file_name ); // Create image file name
		
		
		// Save as a temporary file
		$down_url = $image_url . '.jpg';
		$tmp = download_url( $down_url );
	
		// Check for download errors
		if ( is_wp_error( $tmp ) ) 
		{
			@unlink( $file_array[ 'tmp_name' ] );
			return $tmp;
		}
		
		$img_url = WPLP_UPLOAD_URL.$image_name.".jpg";
		
		$file = WPLP_UPLOAD_DIR . $image_name . '.jpg';
		
		
		// Take care of image files without extension:
		$path = pathinfo( $tmp );
		if( ! isset( $path['extension'] ) ):
			$tmpnew = $tmp . '.jpg';
			if( ! rename( $tmp, $tmpnew ) ):
				return '';
			else:
				$name = $filename.'.jpg';
				$tmp = $tmpnew;
			endif;
		endif;
		if( $path['extension'] == 'tmp' ):
			$tmpnew = $path['dirname'].'/'.$path['filename'] . '.jpg';
			if( ! rename( $tmp, $tmpnew ) ):
				return '';
			else:
				$name = $filename.'.jpg';
				$tmp = $tmpnew;
			endif;
		endif;
		
		
		$exists = file_exists($file);
		if ($exists == true) {
			$file = WPLP_UPLOAD_DIR . $filename . time() . '.jpg';
		}
		$move = rename($tmp, $file);
		
		
		// Check image file type
		$wp_filetype = wp_check_filetype( $file, null );
		
		// Set attachment data
		$attachment = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title'     => sanitize_file_name( $filename ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);
		
		// Create the attachment
		$attach_id = wp_insert_attachment( $attachment, $file, $post_id );
		
		// Include image.php
		require_once(ABSPATH . 'wp-admin/includes/image.php');
		
		// Define attachment metadata
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
		
		// Assign metadata to attachment
		wp_update_attachment_metadata( $attach_id, $attach_data );
		
		// Get current screenshot ID and delete if it exists
		$post_thumbnail_id = get_post_thumbnail_id( $post_id );
		$mk = wplp_filter_metadata( get_post_meta( $post_id ) );
		if (!empty($post_thumbnail_id) && $mk['wplp_media_image'] != 'true') {
			wp_delete_attachment( $post_thumbnail_id, true );
		}
		
		// And finally assign featured image to post
		set_post_thumbnail( $post_id, $attach_id );
		update_post_meta( $post_id, 'wplp_media_image', 'false');
		
		return 'success';
	}
	
	function wplp_small_screenshot_url($image_name, $url, $post_id) {
		$screenshot = file_get_contents('https://www.googleapis.com/pagespeedonline/v1/runPagespeed?url='.$url.'&screenshot=true');
		$data_whole = json_decode($screenshot);
		
		if (isset($data_whole->error) || empty($screenshot)) {
			if (!(substr($url, 0, 4) == 'http')) {
				$url2 = 'https%3A%2F%2F' . $url;
				$screenshot = file_get_contents('https://www.googleapis.com/pagespeedonline/v1/runPagespeed?url='.$url2.'&screenshot=true');
				$data_whole = json_decode($screenshot);
			}
		}
		if (isset($data_whole->error) || empty($screenshot)) {
			if (!(substr($url, 0, 3) == 'www')) {
				$url3 = 'https%3A%2F%2F' . 'www.' . $url;
				$screenshot = file_get_contents('https://www.googleapis.com/pagespeedonline/v1/runPagespeed?url='.$url3.'&screenshot=true');
				$data_whole = json_decode($screenshot);
			}
		}
		if (isset($data_whole->error)) {
				die(json_encode(array('message' => 'ERROR', 'code' => 'data returned error')));
		}
		if (isset($data_whole->screenshot->data)) {
			$data = $data_whole->screenshot->data;
		} else { 
		die(json_encode(array('message' => 'ERROR', 'code' => 'no screenshot')));}
		$data = str_replace('_', '/', $data);
		$data = str_replace('-', '+', $data);
		$base64img = str_replace('data:image/jpeg;base64,', '', $data);
			
		$data   		  = base64_decode($data);
		$upload_dir       = WPLP_UPLOAD_DIR; // Set upload folder
		$image_data       = $data; // img data
		$unique_file_name = wp_unique_filename( $upload_dir, $image_name ); // Generate unique name
		$filename         = basename( $unique_file_name ); // Create image file name
		
	
		$tmp = WPLP_UPLOAD_DIR . $image_name . '.jpg';
		// Create the image  file on the server
		file_put_contents( $tmp, $image_data );
		
		$exists = file_exists($tmp);
		if ($exists == true) {
			$file = WPLP_UPLOAD_DIR . $filename . time() . '.jpg';
		}
		$move = rename($tmp, $file);
		
		
		// Check image file type
		$wp_filetype = wp_check_filetype( $file, null );
		
		// Set attachment data
		$attachment = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title'     => sanitize_file_name( $filename ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);
		
		// Create the attachment
		$attach_id = wp_insert_attachment( $attachment, $file, $post_id );
		
		// Include image.php
		require_once(ABSPATH . 'wp-admin/includes/image.php');
		
		// Define attachment metadata
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
		
		// Assign metadata to attachment
		wp_update_attachment_metadata( $attach_id, $attach_data );
		
		// Get current screenshot ID and delete if it exists
		$post_thumbnail_id = get_post_thumbnail_id( $post_id );
		$mk = wplp_filter_metadata( get_post_meta( $post_id ) );
		if (!empty($post_thumbnail_id) && $mk['wplp_media_image'] != 'true') {
			wp_delete_attachment( $post_thumbnail_id, true );
		}
		
		// And finally assign featured image to post
		set_post_thumbnail( $post_id, $attach_id );	
		update_post_meta( $post_id, 'wplp_media_image', 'false');
		
		return 'success';
	}
	
	function wplp_small_screenshot($image_name, $data, $post_id) {
		$data = str_replace('_', '/', $data);
		$data = str_replace('-', '+', $data);
		$base64img = str_replace('data:image/jpeg;base64,', '', $data);
			
		$data   		  = base64_decode($data);
		$upload_dir       = WPLP_UPLOAD_DIR; // Set upload folder
		$image_data       = $data; // img data
		$unique_file_name = wp_unique_filename( $upload_dir, $image_name ); // Generate unique name
		$filename         = basename( $unique_file_name ); // Create image file name
		
	
		$tmp = WPLP_UPLOAD_DIR . $image_name . '.jpg';
		// Create the image  file on the server
		$filep = file_put_contents( $tmp, $image_data );
		
		$exists = file_exists($tmp);
		if ($exists == true) {
			$file = WPLP_UPLOAD_DIR . $filename . time() . '.jpg';
		}
		$move = rename($tmp, $file);
		
		// Check image file type
		$wp_filetype = wp_check_filetype( $file, null );
		
		// Set attachment data
		$attachment = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title'     => sanitize_file_name( $filename ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);
		
		// Create the attachment
		$attach_id = wp_insert_attachment( $attachment, $file, $post_id );
		
		// Include image.php
		require_once(ABSPATH . 'wp-admin/includes/image.php');
		
		// Define attachment metadata
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
		
		// Assign metadata to attachment
		wp_update_attachment_metadata( $attach_id, $attach_data );
		$post_thumbnail_id = get_post_thumbnail_id( $post_id );
		
		$mk = wplp_filter_metadata( get_post_meta( $post_id ) );
		if (!empty($post_thumbnail_id) && $mk['wplp_media_image'] != 'true') {
			wp_delete_attachment( $post_thumbnail_id, true );
		}
		
		// And finally assign featured image to post
		set_post_thumbnail( $post_id, $attach_id );	
		update_post_meta( $post_id, 'wplp_media_image', 'false');
		
		return 'success';
	}
	
	
	/* Shortcode */
	
	add_filter( 'the_content', 'wplp_remove_autop', 0 );
	function wplp_remove_autop( $content )
	{
		 global $post;
	
		 // Check for single page and image post type and remove
		 if ( $post->post_type == 'wplp_link' )
			  remove_filter('the_content', 'wpautop');
	
		 return $content;
	}
	
	
	function wplp_shortcode($atts){	
		
		if (get_option('wplpf_grid') != false) {
			$dis = get_option('wplpf_grid');
		} else { $dis = 'grid'; }
		if (get_option('wplpf_width') != false) {
			$col = get_option('wplpf_width');
		} else { $col = '3'; }
		
		$vars = shortcode_atts( array(
			'display' => $dis,
			'cols' => $col,
			'img_size' => 'medium',
			'desc' => '',
			), $atts );
		$display = $vars['display'];
		$cols = $vars['cols'];
		$img_size = $vars['img_size'];
		$desc = $vars['desc'];
		
		wp_enqueue_style('wplp-display-style');
		
		global $wpdb;
		$grid = '';
		$list = '';
		$i = 0;
		$query_args = array('post_type' => 'wplp_link', 'posts_per_page' => -1, 'order' => 'ID', 'orderby' => 'ASC');
		$gallery = '';
		$list = '';
		
		$custom_query = new WP_Query( $query_args );
		while($custom_query->have_posts()) : $custom_query->the_post();
			$post_id = get_the_ID();
			$mk = wplp_filter_metadata( get_post_meta( $post_id ) );
			
			// Image
			$img = '';
			$thumb = get_post_thumbnail_id($post_id);
			$img = wp_get_attachment_image($thumb, $img_size, false);
			
			// Title
			$title_display = $mk['wplp_display'];
			
			// Description
			$description = '';
			if ($desc == 'content') {
				$description = apply_filters('the_content',get_the_content());
			} elseif ($desc == 'none') {
				$description = '';	
			}
			if (!empty($description)) {
				$description = '<p class="wplp_desc">'.$description.'</p>';
			}
			
			$url = the_title("","",false);
				
			if ($display == 'grid') {
				$gallery .= '<figure id="gallery-item-'.$i.'" class="gallery-item wplp-item">
				<div class="gallery-icon landscape">
				<a class="wplp_link" href="'.$url.'" target="_blank">
				'.$img.'
				<p class="wplp_display">'.$title_display.'</p>
				'.$description.'
				</a>
				</div>
				</figure>';	
			} elseif ($display == 'list') {
				$list .= '<div id="wplp_list-item-'.$i.'" class="list-item wplp-item">
				<a class="wplp_link" href="'.$url.'" target="_blank">
				<div class="list-img">'.$img.'</div>
				<p class="wplp_display" >'.$title_display.'</p>
				'.$description.'
				</a>
				</div>
				<hr>';	
			}
		$i++;
		
		endwhile;
		
		if ($display == 'grid') {
			$output = '<div style="clear:both;"></div><div id="gallery-wplp" class="galleryid-wplp gallery-columns-'.$cols.' wplp-display">'.$gallery.'</div><div style="clear:both;"></div>';	
		} elseif ($display == 'list') {
			$output = '<div style="clear:both;"></div><div id="list-wplp" class="listid-wplp wplp-display">'.$list.'</div><div style="clear:both;"></div>';	
		}
		wp_reset_postdata();
		return $output;
	}
	add_shortcode('wp_links_page', 'wplp_shortcode');
	
	add_shortcode('wp_links_page_free', 'wplp_shortcode');
}
?>