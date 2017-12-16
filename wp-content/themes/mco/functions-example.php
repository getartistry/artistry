<?php
/**
 * This functions.php script was created by Jake Goldman, 10up
 * www.get10up.com  @jakemgold
 * 
 * You are welcome to use any of the code contained within in your own theme. Please consider attributing
 * the code to me in the comments, particularly if you use a significant portion of the code.
 */   


/**
 * we do our login css hijacking up here, since we'll wrap the rest in "is_admin"
 */

// REMOVE - show template name
  add_action('wp_head', 'show_template');
  function show_template() {
  global $template;
  global $current_user;
  get_currentuserinfo();
  if ($current_user->user_level == 10 ) print_r($template);
  }
// REMOVE - end show template name
  
 
add_action( 'login_head', 'custom_login_css' );

function custom_login_css() {
       echo '<link rel="stylesheet" href="' . get_stylesheet_directory_uri() . '/admin-styles.css" type="text/css" media="all" />'; 
}


// lots of front end functions? consider compartmentalizing admin:
// if ( is_admin() ) require_once('functions_admin.php');

if ( is_admin() ) : // why execute all the code below at all if we're not in admin?

/**************************/
/*** PART ONE: BRANDING ***/
/**************************/
 
/**
 * call in custom admin stylesheet - this will be global for admin and also login
 */
 
add_action( 'admin_print_styles', 'load_custom_admin_css' );

function load_custom_admin_css() {
       wp_enqueue_style( 'custom_admin_css', get_stylesheet_directory_uri() . '/admin-styles.css' );
} 


/**
 * overriding footer "credit" text
 */
 
add_filter( 'admin_footer_text', 'custom_footer_text' );

function custom_footer_text($default_text) {
       return '<span id="footer-thankyou">Powered by <a href="http://www.artistry.ink">Artistry</a>';
}


/**
 * cleaning up and customizing the dashboard
 */
 
add_action('wp_dashboard_setup', 'custom_dashboard_widgets');

function custom_dashboard_widgets() {
       global $wp_meta_boxes;
       
       // remove unnecessary widgets
       // var_dump( $wp_meta_boxes['dashboard'] ); // use to get all the widget IDs
       unset(
              $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'],
              $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'],
              $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'],
              $wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links'],
              $wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']
       );
       
       //custom dashboard widgets
       wp_add_dashboard_widget('dashboard_custom_feed', 'News from 10up', 'dashboard_custom_feed_output'); //add new rss feed output
       wp_add_dashboard_widget('custom_help_widget', 'Help and Support', 'custom_dashboard_help'); // add a new custom widget for help and support
}

function dashboard_custom_feed_output() {
       echo '<div class="rss-widget">';
       wp_widget_rss_output(array(
              'url' => 'http://www.get10up.com/feed',
              'title' => 'What\'s up at 10up',
              'items' => 2,
              'show_summary' => 1,
              'show_author' => 0,
              'show_date' => 1 
       ));
       echo "</div>";       
}

function custom_dashboard_help() {
       echo '
              <p>Need help? That "help" tab up top provides contextual help throughout the administrative panel. If you need additional support, you can contact your web team at <a href="http://www.get10up.com">10up</a>:</p>
              <p><strong>phone:</strong> 401.206.0004</p>
              <p><strong>email:</strong> <a href="mailto:jake@get10up.com">jake@get10up.com</a><p> 
       ';
}


/**
 * custom contextual help - tack on our support information to the end of the contextual help
 */
 
add_filter( 'contextual_help', 'custom_help_support', 100 ); //giving a very late priority (100) to make sure it's always at the end (10 is default)

function custom_help_support($help) {
       $help .= '
              <p><strong>Additional support</strong> - Contact the web team at <a href="http://www.get10up.com">10up</a> 
              by phone at 401.206.0004 or by email at <a href="mailto:jake@get10up.com">jake@get10up.com</a>.<p>
       ';
       return $help;
}


/***********************************/
/*** PART TWO: CLEANING UP ADMIN ***/
/***********************************/

/**
 * custom "admin lite" role
 * we want an editor that can also: manage users, manage plugins, unfiltered upload, manage options
 */

add_action( 'admin_init', 'setup_admin_lite_role' );

function setup_admin_lite_role() {
       // remove_role( 'adminlite' ); // for testing - once you add the role, it sticks!

       if ( !get_role('adminlite') ) {
              $caps = get_role('editor')->capabilities; //let's use the editor as the base  capabilities
              $caps = array_merge( $caps, array(
                     'install_plugins' => true,
                     'activate_plugins' => true,
                     'update_plugins' => true,
                     'delete_plugins' => true,
                     'list_users' => true, //wp3.0
                     'create_users' => true,
                     'edit_users' => true,
                     'delete_users' => true,
                     'unfiltered_upload' => true,
                     'edit_theme_options' => true //wp3.0
              )); //adding new capabilities: reference http://codex.wordpress.org/Roles_and_Capabilities#Capability_vs._Role_Table
              
              add_role( 'adminlite', 'Administrator Lite', $caps );
       }
}

/**
 * let's remove the menu option for a feature we don't use - links - much easier in 3.1!
 */
 
add_action( 'admin_menu', 'custom_admin_menu' );

function custom_admin_menu() {
       remove_menu_page('link-manager.php');
       
       if ( !current_user_can('manage_options') )       // don't remove tools from administrators! 
              remove_menu_page('tools.php');
              remove_menu_page('layers-marketplace.php');
}


/**
 * If you're a CMS use case, maybe your client thinks of "posts" as "articles" (news).
 * Let's hijack the text translation an globally replace "post" with "article" in the admin.
 */
 
add_filter( 'gettext', 'change_post_to_article' );
add_filter( 'ngettext', 'change_post_to_article' );

function change_post_to_article( $translated ) {
       $translated = str_ireplace( 'Post', 'Article', $translated );
       $translated = str_ireplace( 'Widget', 'Block', $translated );
       return $translated;
}

/**
 * let's eliminate some sidebar widgets we know the client will never use
 */
 
add_action( 'widgets_init', 'custom_remove_widgets' );

function custom_remove_widgets() {
       unregister_widget( 'WP_Widget_Pages' );
       unregister_widget( 'WP_Widget_Categories' );
       unregister_widget( 'WP_Widget_Archives' );       
       unregister_widget( 'WP_Widget_Meta' );    
       unregister_widget( 'WP_Widget_Links' );
}


/**
 * meaningful post specific help
 */
 
add_filter( 'contextual_help', 'custom_post_help', 10, 3 );    

function custom_post_help( $help, $screenid, $screen ) {
       if ( $screenid == 'post' ) {
              $help .= '
                     <p><strong>Front Page Posts</strong> - Be sure to assign posts you want highlighted 
                     on the front page of the website to the "Featured" category.<p>
              ';
       }
       
       return $help;
}


/**
 * trim down page and post meta boxes to the basics... except for full admins
 * tip: looking for the ID? inspect the meta box wrapper in the HTML and look for the ID attribute 
 */

if ( !current_user_can('manage_options') )
       add_action('admin_init','customize_page_meta_boxes');

function customize_page_meta_boxes() {
       remove_meta_box('postcustom','page','normal');
       remove_meta_box('postcustom','post','normal');
       remove_meta_box('commentstatusdiv','page','normal');
       remove_meta_box('authordiv','page','normal');
       remove_meta_box('trackbacksdiv','post','normal');
}


/**
 * let's tailor TinyMCE a bit based on what they plan to do
 */
 
add_filter("mce_external_plugins", "add_nonbreaking_tinymce_plugin"); // let's add a new tinymce plugin

function add_nonbreaking_tinymce_plugin($plugins) {
       $plugins['nonbreaking'] = get_stylesheet_directory_uri() . '/tinymce-plugins/nonbreaking.js'; //this was pulled out of original tinymce plugins
       return $plugins;
}
 
add_filter('mce_buttons_2', 'custom_mcetable_buttons'); //let's remove some buttons from the second row, and add this one 

function custom_mcetable_buttons($buttons) {
       // var_dump($buttons); // use this to get the names or keys of all the tinymce buttons... or just count
        
       unset( $buttons[2] ); // full justify
       unset( $buttons[9] ); // embed media
       
       array_splice( $buttons, 9, 0, "nonbreaking" );   // add new nonbreaking button after the special characters buttons
       
       return $buttons;
}


/**
 * customizing editor styles - super easy in WordPress 3.0!
 */

add_action( 'after_setup_theme', 'custom_admin_after_setup' ); 

function custom_admin_after_setup() {
       add_editor_style(); // that's it! by default it looks for 'editor-style.css', but you can pass can alernate file name if desired
}


/**
 * adding the post ID to the posts list
 */

add_filter( 'manage_posts_columns', 'custom_post_id_column', 10, 2 );

function custom_post_id_column( $post_columns, $post_type ) {
       if ( $post_type == 'post' ) {
              $beginning = array_slice( $post_columns, 0, 1 );
              $beginning['postid'] = __('ID');
              $ending = array_slice( $post_columns, 1 );
              $post_columns = array_merge( $beginning, $ending );
       }
       return $post_columns;
}

add_action( 'manage_posts_custom_column', 'custom_post_column_id', 10, 2 );

function custom_post_column_id( $column_name, $postid ) {
       if ( $column_name == "postid" )
              echo $postid;
}


endif; //wrapper for admin functions

function artistry_enqueue_custom_stylesheets() {
  if ( ! is_admin() ) {
    if ( is_child_theme() ) {
      if ( file_exists( get_stylesheet_directory() . "/custom.css" ) ) {
        wp_enqueue_style( 'artistry-theme-custom-css', get_stylesheet_directory() . '/custom.css' );
      }
    }
  }
}

add_action( 'wp_enqueue_scripts', 'artistry_enqueue_custom_stylesheets', 11 );

/**
 * EXAMPLE:
 * Add google fonts, don't forget to add the to the style.css or custom.css file.
 */
function artistry_add_google_fonts() {
  wp_register_style( 'artistry-googleFonts', 'http://fonts.googleapis.com/css?family=Lato' );
  //wp_register_style('artistry-googleFonts', 'http://fonts.googleapis.com/css?family=Montserrat');
  wp_enqueue_style( 'artistry-googleFonts' );
}

add_action( 'wp_print_styles', 'artistry_add_google_fonts' );