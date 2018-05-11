<?php
/*MyCurator_posttypes
*These functions register the post type, use other hooks for special manipulation, use shortcodes to display the custom post type
* on pages
*
* Version 1.0
*/ 

//Register post type and taxonomies on init
add_action('init','mct_ai_register');
//manage columns on new post type
add_filter( "manage_target_ai_posts_columns", "target_ai_chg_col" );
add_action( "manage_posts_custom_column", "target_ai_custom_col", 10, 2 );
add_action( 'restrict_manage_posts', 'target_ai_restrict_manage_posts' );
add_filter( 'parse_query', 'target_ai_filter_post_type_request' );
add_filter('post_row_actions','mct_ai_remove_quick_edit',10,1);
add_filter('bulk_actions-edit-target_ai','mct_ai_custom_bulk_actions');
add_filter( 'manage_edit-target_ai_sortable_columns', 'target_ai_sortable' );
add_filter('request','target_ai_sort_request');
//Metabox to show link data  for target_ai posttype- 
add_action('add_meta_boxes','mct_ai_linkmeta');
//Create the News/Twitter Feed on the Links menu
add_action('admin_menu', 'bwc_add_link_alerts');
//Metabox to show relevance data
add_action('add_meta_boxes','mct_ai_relmeta');  //for posts
add_action('add_meta_boxes','mct_ai_relmetatarget'); //For targets
add_action('save_post','mct_ai_del_multi');  //Delete old multi posts
//the content filter to add training links
add_filter('the_content', 'mct_ai_traintags', 20);
add_filter('the_excerpt', 'mct_ai_traintags', 20);
//Insert jquery for training page
add_action('wp_enqueue_scripts','mct_ai_insertjs');
//Ajax handler
add_action('wp_ajax_mct_ai_train_ajax','mct_ai_train_ajax');
add_action('wp_ajax_mct_ai_showpg_ajax','mct_ai_showpg_ajax');
//Capability for author to see training page 
add_filter('user_has_cap','mct_ai_hascap',10,3);

function mct_ai_register(){
    //Registers custom post type targets
    //Set up args array
    global $mct_ai_optarray, $ai_logs_tbl, $wpdb;
    
    $target_args = array (
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => 'target',
        'rewrite' => array ('slug' => 'target'),
        'supports' => array( 
            'title', 'author', 'editor'
        ),
        'labels' => array(
            'name' => 'Training Posts',
            'singular_name' => 'Training Post',
            'add_new' => 'Add New Training Post',
            'add_new_item' => 'Add New Training Post',
            'edit_item' => 'Edit Training Post',
            'new_item' => 'New Training Post',
            'view_item' => 'View Training Post',
            'search_items' => 'Search Training Posts',
            'not_found' => 'No Training Posts Found',
            'not_found_in_trash' => 'No Training Posts Found In Trash'
        ),
    );
   
    register_post_type('target_ai',$target_args);
    
    //Set up taxonomy Topics - this will be populated when we create topics
    //Shouldn't be touched by users, so set capability to super admin 
    $topic_args = array (
        'public' => false,
        'show_ui' => true,
        'show_tagcloud' => false,
        'query_var' => true,
        'rewrite' => array ('slug' => 'topic'),
        'labels' => array(
            'name' => 'Topics',
            'singular_name' => 'Topic',
            'add_new_item' => 'Add New Topic',
            'update_item' => 'Update Topic',
            'edit_item' => 'Edit Topic',
            'new_item_name' => 'New Topic',
            'all_items' => 'All Topics',
            'search_items' => 'Search Topics',
            'popular_items' => 'Popular Topics',
            'choose_from_most_used' => 'Choose from most popular Topics',
            'separate_items_with_commas' => 'Separate Topics with commas'
        ),
        'capabilities' => array (
            'manage_terms' => 'manage_network', //by default only super admin - shouldn't be available
            'edit_terms' => 'manage_network',
            'delete_terms' => 'manage_network',
            'assign_terms' => 'publish_posts'  
        ),
    );
    register_taxonomy('topic', array('target_ai'), $topic_args);
    
    //Set up taxonomy ai_class - this will be populated up front
    //Shouldn't be touched by users, so set capability to super admin
    $class_args = array (
        'public' => false,
        'show_ui' => true,
        'show_tagcloud' => false,
        'query_var' => true,
        'rewrite' => array ('slug' => 'ai_class'),
        'labels' => array(
            'name' => 'Relevance',
            'singular_name' => 'Relevance',
            'add_new_item' => 'Add New Relevance',
            'update_item' => 'Update Relevance',
            'edit_item' => 'Edit Relevance',
            'new_item_name' => 'New Relevance',
            'all_items' => 'All Relevance',
            'search_items' => 'Search Relevance',
            'popular_items' => 'Popular Relevance',
            'choose_from_most_used' => 'Choose from most popular Relevance',
            'separate_items_with_commas' => 'Separate Relevance with commas'
        ),
        'capabilities' => array (
            'manage_terms' => 'manage_network', //by default only super admin - shouldn't be available
            'edit_terms' => 'manage_network',
            'delete_terms' => 'manage_network',
            'assign_terms' => 'publish_posts'  
        ),
    );
    register_taxonomy('ai_class', array('target_ai'), $class_args);
    
    //Shortcode for a target_ai posts page
    add_shortcode('MyCurator_training_page','target_ai_shortcode');
    //Set up global options
    $mct_ai_optarray = get_option('mct_ai_options');
    //New install, set options to default
    if (empty($mct_ai_optarray)){ 
        $opt = mct_ai_setoptions(true);
        update_option('mct_ai_options',$opt);
        $mct_ai_optarray = get_option('mct_ai_options');
        //Set up ai_class terms
        wp_insert_term('not sure','ai_class');
        wp_insert_term('good','ai_class');
        wp_insert_term('bad','ai_class');
    }
    //New version, perform any updates
    if ($mct_ai_optarray['MyC_version'] != MCT_AI_VERSION) {
        //Any code to run for this new version
        mct_ai_createdb(); //Always update the db in case they skipped a few updates
        $mct_ai_optarray['MyC_version'] = MCT_AI_VERSION;
        //Need to keep these for those who skip updates until prior versions to "Added Ver" are not supported
        //
        //Version 2.7 Changes
        $sql = "UPDATE $ai_logs_tbl SET logs_proc_id = 1000, logs_proc_cnt = logs_id WHERE logs_proc_id is NULL";
        $wpdb->query($sql);
        $sql = "UPDATE $ai_logs_tbl SET logs_type = 'ARTICLE' WHERE logs_type = 'ACTIVITY'";
        $wpdb->query($sql);
        //
        if (!isset($mct_ai_optarray['ai_no_procpg'])) $mct_ai_optarray['ai_no_procpg'] = 0;// Added 2.1
        if (!isset($mct_ai_optarray['ai_page_rqst'])) $mct_ai_optarray['ai_page_rqst'] = 0;// Added 2.1
        if (!empty($mct_ai_optarray['ai_plan'])) { 
            $plan = unserialize($mct_ai_optarray['ai_plan']);
            if ($plan['max'] == 1){
                //Upgrade single topic customers to full site and request processing
                $mct_ai_optarray['ai_no_procpg'] = 1;// Added 2.1.1 
                $mct_ai_optarray['ai_page_rqst'] = 1;// Added 2.1.1
            }
        }
        if (!isset($mct_ai_optarray['ai_post_this_user'])) $mct_ai_optarray['ai_post_this_user'] = 0; // Added 2.3
        if (!isset($mct_ai_optarray['ai_getit_pub'])) $mct_ai_optarray['ai_getit_pub'] = 0; // Added 2.2
        if (!isset($mct_ai_optarray['ai_dup_title'])) $mct_ai_optarray['ai_dup_title'] = 1; // Added 2.1.4
        if (!isset($mct_ai_optarray['ai_attr_top'])) $mct_ai_optarray['ai_attr_top'] = 0; // Added 2.1.2
        if (!isset($mct_ai_optarray['ai_img_align'])) $mct_ai_optarray['ai_img_align'] = 'left'; // Added 2.1
        if (!isset($mct_ai_optarray['ai_img_size'])) $mct_ai_optarray['ai_img_size'] = 'thumbnail'; // Added 2.1
        if (!isset($mct_ai_optarray['ai_video_thumb'])) $mct_ai_optarray['ai_video_thumb'] = 0; // Added 2.1.1
        if (!isset($mct_ai_optarray['ai_video_align'])) $mct_ai_optarray['ai_video_align'] = 'none'; // Added 2.1.1
        if (!isset($mct_ai_optarray['ai_image_bottom'])) $mct_ai_optarray['ai_image_bottom'] = 0; // Added 2.1.1
        
        wp_insert_term('not sure','ai_class'); // Added 2.1
        wp_insert_term('good','ai_class');  //Added 2.1
        wp_insert_term('bad','ai_class');  //Added 2.1
        //
        update_option('mct_ai_options',$mct_ai_optarray);
        $mct_ai_optarray = get_option('mct_ai_options');
    }
}

function mct_ai_hascap($allcaps, $cap, $args){
    //Let Authors view training page which is private
    //
    if (empty($cap) || empty($cap[0])) return $allcaps;
    // Bail out if we're not asking about private page:
    if ( 'read_private_pages' != $cap[0] )
            return $allcaps;

    // Bail out for users who can already edit others posts:
    if ( !empty($allcaps['edit_others_posts'] ))
            return $allcaps;

    // Bail out for users who can't publish posts:
    if ( !isset( $allcaps['publish_posts'] ) or !$allcaps['publish_posts'] )
            return $allcaps;

    // Load the page data, look for training page shortcode
    $page = get_page( $args[2] );
    if (stripos($page->post_content,"MyCurator_training_page") !== false) {
        $allcaps[$cap[0]] = true; //read_private_pages
    }
    return $allcaps;
}

function mct_ai_insertjs(){
    //get training page name
    if (is_page()) {
        $page =  mct_ai_get_trainpage();
        if (empty($page)) return;
        $trainpage = $page->post_name;
        if (is_page($trainpage)){
            mct_ai_trainscript('training');
            mct_ai_trainstyle();
        }
    }
}

function mct_ai_custom_bulk_actions($actions){
      global $typenow;
      if ($typenow != 'target_ai') {
        //return;
      }
      unset( $actions['edit'] );
      return $actions;
}

function mct_ai_remove_quick_edit( $actions ) {
    global $typenow;
    
    if ($typenow != 'target_ai') return $actions;
    if (isset($_REQUEST['post_status']) && $_REQUEST['post_status'] == 'trash') return $actions;
    //$actions = ''; //unset($actions['inline hide-if-no-js']);
    
    return NULL;  //$actions;
}
    
// Change the columns for the edit CPT screen
function target_ai_chg_col( $cols ) {

    if (isset($_REQUEST['post_status']) && $_REQUEST['post_status'] == 'trash') {
        $cols['image'] = 'Image';
        $cols['expt'] = 'Excerpt';
        $cols['topic'] = 'Topic';
        $cols['origurl'] = 'URL';
        return $cols;
    }    
    unset($cols);
    $cols['cb'] = 'cb';
    if(!wp_is_mobile()) $cols['date'] = 'Date';
    $cols['mytitle'] = 'Title'; 
    if(!wp_is_mobile()) $cols['image'] = 'Image';
    $cols['expt'] = 'Excerpt';
    if(!wp_is_mobile()) $cols['author'] = 'Author';
    if(!wp_is_mobile()) $cols['topic'] = 'Topic';
    if(!wp_is_mobile()) $cols['class'] = 'Class';
    if(!wp_is_mobile()) $cols['origurl'] = 'URL';
    
    

  return $cols;
}

function target_ai_sortable($cols){
    global $mct_ai_optarray;
    
    $cols['mytitle'] = 'mytitle';
    $cols['author'] = 'author';
    
    return $cols;
}

function target_ai_custom_col( $column, $post_id ) {
    //Output custom columns for new post type
    global $wpdb, $mct_ai_optarray;
    
   if (get_post_type($post_id) != 'target_ai') return; 
    
  $origlinks = get_post_meta($post_id,'mct_sl_origurl',true);
  $newlinks = get_post_meta($post_id,'mct_sl_newurl',true);
  switch ( $column ) {
    case "mytitle": //case "mytitle"
        $title = get_the_title($post_id);
        $thepost = get_post($post_id);
        $a_id = $thepost->post_author;
        if (empty($mct_ai_optarray['ai_no_inline_pg'])) {
            echo '<strong><a class="row-title thickbox" href="#TB_inline?&width=550&height=700&inlineId=ai-page-'.$post_id.'" title="'.$title.'">'.$title.'</a></strong>';
        } else {
            $link_redir = mct_ai_getlinkredir($post_id);
            if (!empty($link_redir)) echo '<strong><a href="'.$link_redir.'" target="_blank">'.$title.'</a></strong>'; else echo  '<strong>'.$title.'</strong>';
        }
        if (isset($_REQUEST['post_status']) && $_REQUEST['post_status'] == 'trash') break;
        echo '<img src="'.esc_url( admin_url( "images/wpspin_light.gif" ) ).'" alt="" id="saveimg-'.$post_id.'" style="display:none;" />';
        //Set up row action
        echo '<div class="row-actions">';
        echo mct_ai_addtrain();
        echo '<img src="'.esc_url( admin_url( "images/wpspin_light.gif" ) ).'" alt="" id="saving" style="display:none;" />';
        echo '</div>';
        //Inline title for bulk edit
        echo '<div class="hidden" id="inline_'.$post_id.'">';
        echo '<div class="post_title">'.$title.'</div>';
        echo '<div class="post_author">'.$a_id.'</div>';
        echo '</div>';
        //Inline thickbox
        mct_ai_inlinetb($post_id);
        break;
    case "topic":
      $terms = get_the_terms( $post_id, 'topic');
      if (!empty($terms)) {
          foreach ($terms as $term){
              echo $term->name;
          }
      }
      break;
    case "class":
      $terms = get_the_terms( $post_id, 'ai_class');
      if (!empty($terms)) {
          foreach ($terms as $term){
              echo $term->name;
          }
      }
      break;
    /*case "author":
        echo get_the_author();
        break;  */
    case "origurl":
      if (!empty($origlinks)){
          $domain = parse_url($origlinks[0], PHP_URL_HOST);
          echo '<a href="'.$origlinks[0].'" target="_blank" >'.$domain.'</a>';
      }
      break;
    case "newurl":
      if (!empty($newlinks)){
          echo '<a class="thickbox" href="'.$newlinks[0].'?TB_iframe=true&width=950&height=700">Page</a>';
      }
      break;
    case "image":
        if (has_post_thumbnail( $post_id )) {
            $thumb_id = get_post_meta($post_id, '_thumbnail_id',true);
        } else {
            $ids = $wpdb->get_col("SELECT ID FROM {$wpdb->posts} WHERE post_parent = $post_id AND post_type = 'attachment'");
            $thumb_id = (!empty($ids)) ? $ids[0] : 0; //use only first one
        }
        if (!$thumb_id) {
            echo "No Image";
            break;
        }
        $url = $url = wp_get_attachment_url($thumb_id);
        $src = wp_get_attachment_image_src($thumb_id,'thumbnail');
        $imgstr = '<a class="thickbox" title="Image" href="'.$url.'"><img alt="" src="'.$src[0].'" width="75" height="75" /></a>';
        echo $imgstr;
        break;
    case "expt":
        $content = get_the_content();
        $excerpt = mct_ai_getexcerpt($content);
        if ($excerpt != '') {
            echo $excerpt;
            break;
        }
        //No excerpt, Check for video
        $pos = preg_match('{<iframe title="Video Player"}',$content,$matches);
        if ($pos) {
          //Inline thickbox
          echo '<a class="thickbox" href="#TB_inline?&width=800&height=600&inlineId=ai-video-'.$post_id.'" title="Video Iframe">Click for Video</a>';
          //Get the youtube description
          //First remove any acitve links
          $txtstr = preg_replace('{</?a([^>]*)>}','',$content);
          $pos = preg_match('{<p class="mct_ai_ytdesc">([^<]*)</p>}',$txtstr, $match);
          if ($pos) {
              if (strlen($match[1]) == 0 ) break;  //no excerpt
              $excerpt_length = $mct_ai_optarray['ai_excerpt'];
              if ($excerpt_length == 0) break;  //Don't want an excerpt
              //Get the word count specified
              $excerpt = $match[1];
              $excerpt = preg_replace('/\s+/', ' ', $excerpt);  //get rid of extra spaces
              $words = explode(' ', $excerpt, $excerpt_length + 1); 
              if ( count($words) > $excerpt_length ) {
                    array_pop($words);
                    array_push($words, '[...]');
                    $excerpt = implode(' ', $words);
              }
              echo '&nbsp;&nbsp;&nbsp;'.$excerpt;
          }
          echo '<div id="ai-video-'.$post_id.'" style="max-width: 800px; display: none;">';
          $content = str_replace(array("\r\n", "\n", "\r"),'<br>',$content);
          echo "<p>$content</p>";
          echo '</div>';
          break;
        }
        //No excerpt we can process
        echo "No Excerpt";
      
        break;
  }
}

// Filter the request to just give posts for the given taxonomy, if applicable.
function target_ai_restrict_manage_posts() {
    global $typenow;

    // If you only want this to work for your specific post type,
    // check for that $type here and then return.
    // This function, if unmodified, will add the dropdown for each
    // post type / taxonomy combination.

    if ($typenow != 'target_ai') {
        return;
    }

    $filters = get_object_taxonomies( $typenow );

        foreach ( $filters as $tax_slug ) {
            $tax_obj = get_taxonomy( $tax_slug );
            wp_dropdown_categories( array(
                'show_option_all' => __('Show All '.$tax_obj->label ),
                'taxonomy' 	  => $tax_slug,
                'name' 		  => $tax_obj->name,
                'orderby' 	  => 'name',
                'hierarchical' 	  => $tax_obj->hierarchical,
                'show_count' 	  => false,
                'hide_empty' 	  => true
            ) );
        }
        wp_dropdown_users( array(
                'show_option_all' => __('Show All Authors' ),
                'orderby' 	  => 'display_name'
            ) );
        
}

function target_ai_filter_post_type_request( $query ) {
    //Uses the slug for the filter
  global $pagenow, $typenow;

  if ($typenow != 'target_ai') {
        return $query;
  }
  
  if ( 'edit.php' == $pagenow ) {
    $filters = get_object_taxonomies( $typenow );
    foreach ( $filters as $tax_slug ) {
      $var = &$query->query_vars[$tax_slug];
      if ( isset( $var ) ) {
        $term = get_term_by( 'id', $var, $tax_slug );
        if (!empty($term)) $var = $term->slug;
      }
    }
    //Check for author filter
    if ( isset($_GET['user'])  ) {
        $query->query_vars['author'] = $_GET['user'];
    }
  }
  return $query;
}

function target_ai_sort_request($vars) {
    global $pagenow, $typenow;

  if ($typenow != 'target_ai') {
        return $vars;
  }
  if ( isset( $vars['orderby'] ) && 'mytitle' == $vars['orderby'] ) {
        $vars = array_merge( $vars, array(
            'orderby' => 'name'
        ) );
  }
  
   return $vars;
}

function target_ai_shortcode(){
    //Displays target_ai post types on a page with this shortcode
    //Very little formatting so it will pick up css from current theme
    
    global $post, $user_ID, $ai_topic_tbl, $wpdb, $wp_query, $paged, $blog_id, $mct_ai_optarray;
    $qtopic = '';
    $qaiclass = '';
    $msg = '';
    $last_on = 0;
    $per_page = !empty($mct_ai_optarray['ai_num_posts']) ? $mct_ai_optarray['ai_num_posts'] : 10;
    ob_start();  
    //handle get requests for topic and ai_class, ai_class is nested in previous topic
    if (isset($_GET['topic'])){
        $qtopic = $_GET['topic'];
        set_transient('mct_ai_lasttopic',$qtopic,60*60);
    }
    elseif (isset($_GET['ai_class'])){
        $qaiclass = $_GET['ai_class'];
    }
    else {
        delete_transient('mct_ai_lasttopic','');
    }
    //Set up query with paging
    $q_args = array(
                'post_type' => 'target_ai',
                'orderby' => 'date',
                'order' => 'DESC',
                'posts_per_page' => $per_page,
                'paged' => $paged
            );
    if (!empty($qtopic)){
        $q_args['tax_query'] = array(
		array(
			'taxonomy' => 'topic',
			'field'    => 'slug',
			'terms'    => $qtopic,
		),
	);
        $qterm = get_term_by('slug',$qtopic,'topic');
        $msg = "Topic &raquo; ".$qterm->name;
    }
    if (!empty($qaiclass)){
        $lasttopic=get_transient('mct_ai_lasttopic');
        if (!empty($lasttopic)){
            $qterm = get_term_by('slug',$lasttopic,'topic');
            $q_args['tax_query'] = array(
                'relation' => 'AND',
		array(
			'taxonomy' => 'topic',
			'field'    => 'slug',
			'terms'    => $lasttopic,
		),
                array(
			'taxonomy' => 'ai_class',
			'field'    => 'slug',
			'terms'    => $qaiclass,
		),
            );
            $msg = "Topic &raquo; ".$qterm->name." &middot Relevance &raquo; ".$qaiclass;
        } else {
            $q_args['tax_query'] = array(
		array(
			'taxonomy' => 'ai_class',
			'field'    => 'slug',
			'terms'    => $qaiclass,
		),
            );
            $msg = "<em>Showing Relevance: ".$qaiclass."</em>";
        }
    }
    //Handle author posts
    if (!current_user_can('edit_others_posts')){
        $cu = wp_get_current_user();
        $q_args['author'] = $user_ID;
    }
    //display filter links
    mct_ai_train_nav($msg);
    //Notebook Dialog
    mct_nb_dialog();
    $temp = clone $wp_query;
   
    $wp_query = new WP_Query($q_args);

     //get_header();
     if (have_posts()){
         
         while (have_posts()) {
             the_post();
            
?>
<!-- post title -->
<div <?php post_class('fpost') ?> id="post-<?php the_ID(); ?>">
    
          <?php $title = get_the_title();
          if (empty($mct_ai_optarray['ai_no_inline_pg'])) {
               echo '<h2><a class="row-title thickbox" href="#TB_inline?&width=550&height=700&inlineId=ai-page-'.$post->ID.'" title="'.$title.'">'.$title.'</a></h2>';
          } else {
              $link_redir = mct_ai_getlinkredir($post->ID);
              if (!empty($link_redir)) echo '<h2><a href="'.$link_redir.'" target="_blank">'.$title.'</a></h2>'; else echo  '<h2>'.$title.'</h2>';
          }
          echo '<img src="'.esc_url( admin_url( "images/wpspin_light.gif" ) ).'" alt="" id="saveimg-'.$post->ID.'" style="display:none;" />';
            echo(get_the_date()); echo ('&nbsp;&middot&nbsp;'); 
            edit_post_link( '[Edit]', '', '');

            echo ('&nbsp;&middot&nbsp;');
            echo(get_the_term_target_ai($post->ID,'topic','Topic: ',',',' ')); echo ('&nbsp;&middot&nbsp;'); 
            echo(get_the_term_target_ai($post->ID,'ai_class','Relevance: ',',',' ')); 
            ?><br />
<!-- Content -->
           <br />
           <?php if ( has_post_thumbnail() && empty($mct_ai_optarray['ai_post_img'])) the_post_thumbnail('thumbnail',array('class' => 'alignleft')); ?>
            <?php 
                    the_content();
                    mct_ai_inlinetb($post->ID);
            ?>
</div>

<?php         }  //end while   ?>
    <div class="page-nav">
	    <div class="nav-previous"><?php previous_posts_link(__('&larr; Previous Page')) ?></div>
	    <div class="nav-next"><?php next_posts_link(__('Next Page &rarr;')); ?></div>
    </div>  
 

  <?php   } else {
         echo '<h2>No Training Posts Found</h2>';
     }
     $wp_query = clone $temp;
     return ob_get_clean();
    
}

function get_the_term_target_ai($postid, $taxname, $before, $sep, $after){
    //Get terms for this target post
    $terms = get_the_terms( $postid, $taxname );
    //Get uri, strip out previous gets, any page info
    
    $uri = $_SERVER['REQUEST_URI'];
    $uri = remove_query_arg(array('topic', 'ai_class', 'move', 'good', 'bad'),$uri);
    $uri = preg_replace('{/page/[^/]*/}i','/', $uri);
    if ( $terms && ! is_wp_error( $terms ) ) { 

	$term_links = array();

	foreach ( $terms as $term ) {
		$term_links[] = '<a class="'.$taxname.'-tags" href="'.esc_url($uri.'?'.$taxname.'='.$term->slug).'">'.$term->name.'</a>';
	}
	$full_str = $before.join( $sep, $term_links ).$after;				
	return $full_str;
    }
}

function mct_ai_inlinetb($post_id){
    //Display inline thickbox Div's
    global $mct_ai_optarray;
    
    $title = get_the_title($post_id);
    $content = get_the_content();
    $excerpt = mct_ai_getexcerpt($content);
    $excerpt = str_replace('<br>',PHP_EOL,$excerpt);
   //inline page thickbox div
    $page = mct_ai_getslpage($post_id);
    if (is_admin()) {
        $article = mct_ai_notable_article($page); 
    } else {
        $article = mct_ai_clean_article($page);
    }
    // Get original URL
    $pos = preg_match('{<div id="source-url">([^>]*)>([^<]*)<}',$page,$matches);
    if ($pos) {
        $linktxt = $matches[1].' target="_blank">'.$matches[2].'</a>';
    } else {
        $linktxt = ' ';
    }
    //Get keywords found
    $keywords = get_post_meta($post_id,'mct_ai_tag_search2', true);
    if (!empty($keywords)) $article = '<p>'.$linktxt.'</p>'.'<p>Search 2 keywords found: '.implode(',',$keywords).'</p>'.$article;
    else $article = '<p>'.$linktxt.'</p>'.$article;
    ?>
    <div id="ai-quick-<?php echo $post_id; ?>" style="max-width: 540px; display: none;" >
    <h2 style="text-align: center;">Quickly Publish or Save as Draft</h2>
    <strong>Title:</strong><br><input class="mct-tb-inputs" type="text" id="title-<?php echo $post_id; ?>" value="<?php echo $title; ?>" size="100" /><br>
    <strong>Notes/Comments:</strong><br><textarea class="mct-tb-inputs" id="note-<?php echo $post_id; ?>" rows="5" cols="100"></textarea><br>
    <strong>Excerpt:</strong><br><textarea class="mct-tb-inputs" id="excerpt-<?php echo $post_id; ?>" rows="5" cols="100" ><?php echo $excerpt; ?></textarea><br>
    <p style="text-align:center;">
        <input type="button" id="cancel" value="Cancel" onclick="tb_remove()"/>&nbsp;&nbsp;&nbsp;
        <input type="button" id="draft" value="Draft" onclick="quick_post(<?php echo $post_id; ?>,'draft');" />&nbsp;&nbsp;&nbsp;
        <input type="button" id="publish" value="Publish" onclick="quick_post(<?php echo $post_id; ?>,'publish');" />
    <?php  echo '<img src="'.esc_url( admin_url( "images/wpspin_light.gif" ) ).'" alt="" id="saving-'.$post_id.'" style="display:none;" />'; ?></p>
    <?php if (empty($mct_ai_optarray['ai_no_inline_pg'])) { ?>
        <hr width="90%">
        <h2 style="text-align: center;">Original Article Text</h2>
        <div id="ai-page-<?php echo $post_id; ?>" style="max-width: 540;">
        <p><?php echo $article; ?></p>
        </div>
    <?php } ?>
    </div>
<?php
}

function mct_nb_dialog() {
    //Build the notebook dialog box
    global $user_ID;
    $cu = wp_get_current_user();
    //Get notebooks
    $args = array(
        'numberposts'     => -1,
        'orderby'         => 'post_title',
        'order'           => 'DESC',
        'post_type'       => 'mct_notebk',
        'post_status'     => 'publish'); 
    $notebks = get_posts($args);
    ?>
    <div id="nb-dialog" class="hide-if-no-js" title="Move to Notebook" style="margin-left:5px">
        <form>
            <strong><p id="nb-title"></p></strong>
            
            <?php if (!empty($notebks)) { ?>
            <p><select name="notebk" id="mct-nb-select">
            <?php    foreach ($notebks as $notebk) { 
                    if (! current_user_can('edit_others_posts') && $notebk->post_author != $user_ID) continue;
            ?>
            <option value="<?php echo $notebk->ID; ?>" ><?php echo $notebk->post_title; ?></option>
            <?php  } echo " </select></p>";//foreach
            } //!empty?>
            
            <?php mct_ai_getplan(); if (mct_nb_showlimits(false, false)) { 
                if (!empty($notebks)) echo " OR ";?>
            Add to New Notebook: <input name="newnb" type="text" id="mct-nb-newnb" value="" size="50"> <br>
            <?php } //showlimits ?>
            <br>Notes:<br>
            <textarea name="notes" id="mct-nb-notes" rows="5" cols="50" ></textarea>
        </form>
    </div>
    <?php
}

function mct_ai_train_nav($msg){
    global $mct_ai_optarray;
    //Sets up the navigation at the top of the page
    //Get uri, strip out previous gets
    $uri = $_SERVER['REQUEST_URI'];
    $pos = stripos($uri, '?');
    if ($pos !== false){
        $uri = substr($uri,0,$pos);
    }
    //strip out paging so we are back to page 1
    $uri = preg_replace('{/page/[^/]*/}i','/', $uri);
    //Display title
    echo '<div class="mct_ai_train_nav">';
    echo "<strong>Select Targets to View";
    if (empty($mct_ai_optarray['ai_no_fmthelp'])){
        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="background-color: #e6db55;">'; //
        echo '<a class="thickbox" href="#TB_inline?&width=550&height=450&inlineId=ai-format-help" title="Training Page Formatting">Click if you have Format Problems</a>';
        echo '<span  class="button-primary" onclick="mctaishowvideo()">Need Help?</span>';
        echo mct_ai_helpvideo('curation');
        echo mct_ai_inline_fmthelp();
        echo '</span>';
    }
    echo '</strong><br>';
    echo '<em>TOPICS: </em>';
    // Get the topic terms, set as links and list
    $taxname = 'topic';
    $terms = get_terms($taxname);
    foreach ($terms as $term) {
        echo '&middot;<a href="'.esc_url($uri.'?'.$taxname.'='.$term->slug).'">'.$term->name.'</a>';
    }
    //
    echo '<br />';
    echo '<em>RELEVANCE: </em>';
    //Get the ai-class links and list
    $taxname = 'ai_class';
    $terms = get_terms($taxname);
    foreach ($terms as $term) {
        echo '&middot;<a href="'.esc_url($uri.'?'.$taxname.'='.$term->slug).'">'.$term->name.'</a>';
    }
    if (!empty($msg)){
        echo '<p>'.$msg;
        echo '&nbsp;&middot;&nbsp;<a href="'.esc_url($uri.'?clear=yes').'">[Clear All]</a></p>';
    }
    wp_nonce_field('bulk-posts','_wpnonce',false, true);
    echo '</div>';
}

function mct_ai_inline_fmthelp(){
    ob_start();
    ?>
<div id="ai-format-help" style="display:none;">
    <p>Once in a while you may find that the Training Page or admin Training Posts table all of the sudden has a formatting problem.  This is because we are saving the extracted web page text (but hidden) directly into the page for speed.  Sometimes though there is some badly formed HTML in the extracted text, and this causes the page to lose its format.</p>
    <p>Normally you can identify which post the formatting problem starts with.  If the problem includes the title, 
        the previous article is most likely the problem.  If the bad format starts after the title, that article has the problem.  
        You can make the article live or draft if you like it, or delete it.  Refresh the page and your formatting should return.</p>
    <p>If you can't identify the problem article, or you just want to turn off the use of the readable page popup, you can change an option to turn it off.  Go to the Curation Options tab for MyCurator.  Check the option "Do NOT show readable page in Training Popups".  Now when you return to your Training Page or admin Training Posts table, your formatting should be just fine.  The Quick tag will not have the saved web page text any more.  When you click on a post title, you will get a new browser tab with the readable page extracted from the text rather than a popup.</p>
    <p>Go to our <a href="http://www.target-info.com/documentation-2/">Documentation</a> for Formatting at our site for more information.</p>
    <p>You can turn off this message by checking the "Remove Formatting Help" in the Admin tab in the MyCurator Options menu.</p>
</div>
<?php
    return ob_get_clean();
}

function mct_ai_traintags($content){
    //filter on the_content - add training tags
    // if single, display full text on post
    global $post, $mct_ai_optarray;
    
    if (is_single() || is_feed()) {
        //Get the article link, should only be one if MyCurator posted this - for backward compatibility
        $cnt = preg_match_all('{<a\s(.*)/ailink/[0-9]+"\s*>}',$content,$matches);
        if (!empty($mct_ai_optarray['ai_show_full']) || $cnt == 1) {
            // Display full text in this page if we have it
            $page = mct_ai_getslpage($post->ID);
            if (!empty($page)){
                 $article = mct_ai_getslarticle($page);
                 $rcnt = 0; //replacement count
                 //Get the article link, should only be one 
                 if ($cnt == 1){
                    $linktxt = $matches[0][0]; 
                 } else $linktxt = '';
                 //Put in Source URL
                 $pos = preg_match('{<div id="source-url">([^>]*)>([^<]*)<}',$page,$matches);
                 $content = $matches[1].'> '.$matches[2].'</a>'.$content;
                 //Decide where to put this article
                 $newcontent = mct_ai_resetexcerpt($content,"<br />".$article);
                 if (!empty($newcontent)) {
                     $content = $newcontent;
                 } elseif (!empty($linktxt)) {
                     //Keep the content as it has been changed from simple excerpt
                     //Place the article in front of the link
                     $pos = stripos($content,$linktxt);
                     $content = substr($content,0,$pos)."<br />".$article."<br />".substr($content,$pos);
                 } elseif (($linkpos = stripos($content,'<p id="mct-ai-attriblink">')) !== false){
                     //Keep the content as it has been changed from simple excerpt
                     //Place the article in front of the link
                     $content = substr($content,0,$linkpos)."<br />".$article."<br />".substr($content,$linkpos);
                 } else {
                     //Just put it at the end...
                     $content = $content."<br>".$article;
                 }
            }
        }
    }
    $trainstr =  mct_ai_addtrain();
    if (!empty($trainstr)) {
        //put trainstr next to link, 
        $trainstr .= '<img src="'.esc_url( admin_url( "images/wpspin_light.gif" ) ).'" alt="" id="saving" style="display:none;" />';
        $pos = preg_match('{/ailink/([0-9]+)"\s*>([^<]*)</a>}',$content,$matches);
        if ($pos) {
            if ($spos = strrpos($content, "</a></p>")) {
                $content = substr($content,0,$spos+4)."&nbsp;".$trainstr.substr($content,$spos+4);
            } elseif ($spos = strrpos($content, "</a></span>")) {  //backward compatible with some betas
                $content = substr($content,0,$spos+11)."&nbsp;".$trainstr.substr($content,$spos+11);
            } else { //do the old way
                $pos = stripos($content,$matches[0]);
                $len = strlen($matches[0]);
                $content = substr($content,0,$pos+$len).$trainstr."<br />".substr($content,$pos+$len);
            }
        } else {
            if ($pos = strrpos($content, "</a></p>")) {
                $content = substr($content,0,$pos+4)."&nbsp;".$trainstr.substr($content,$pos+4); 
            } elseif ($pos = strrpos($content, "</a></span>")) { //backward compatible with some betas
                $content = substr($content,0,$pos+11)."&nbsp;".$trainstr.substr($content,$pos+11);
            } else {
                $content .= "&nbsp;".$trainstr;  //if nothing else...
            }
        }
    }
    return $content;
    
}

function is_trainee($postid){
    //Checks if this is a trainable post built by MyCurator
    global $wpdb, $ai_topic_tbl;
    
    // Get the topic name and if relevance type
    $tname = mct_ai_get_tname_ai($postid);
    if ($tname == '') return 'Filter';
     
     // Check whether we have just one link
     if (count(get_post_meta($postid,'mct_sl_newurl',true)) != 1) return 'No';
     // Already trained for this topic?
     $train = get_post_meta($postid,'mct_ai_trained',true);
     if (empty($train)) return 'Yes';
     foreach($train as $tr){
         $pos = stripos($tr, $tname);
         if ($pos !== false) {
             return 'Trained '.substr($tr,0,$pos-1);
         }
     }
     return 'Yes';
}

function mct_ai_addtrain(){
    //This function sets the training keys and trash for training and live posts
    global $post, $mct_ai_optarray;
    
    $ismulti = false;
    //Is this a target post?
    $tgt = false;
    if ($post->post_type == 'target_ai'){
        $tgt = true;
    }
    if (!$tgt && !empty($mct_ai_optarray['ai_no_train_live'])) return '';  //live blog and we don't want tags
    //Does user have edit authority for this post
    $post_type_object = get_post_type_object( $post->post_type );
    if ( !$post_type_object )
            return '';

    if ( !current_user_can( $post_type_object->cap->edit_post, $post->ID ) )
            return '';
    //see if we have a topic taxonomy for this post
    // Get the topic name
    $terms = get_the_terms( $post->ID, 'topic' );
    if ($terms === false ) return '';  //should only be one
    
    //Is this post from MyCurator?
    $istrain = is_trainee($post->ID);
    //Is this a multi post?
    $term = wp_get_object_terms($post->ID,'ai_class',array('fields' => 'names'));
    if (!empty($term) && $term[0] == 'multi') $ismulti = true;
    // set up the training keys
    $retstr = '';
    $train_base = plugins_url('MyCurator_train.php',__FILE__);
    $imggood = plugins_url('thumbs_up.png',__FILE__);
    $imgbad = plugins_url('thumbs_down.png',__FILE__);
    $imgtrash = plugins_url('trash_icon.png', __FILE__);
    $quickstr = '&nbsp; <a class="mct-ai-quick thickbox" id="'.$post->ID.'"href="#TB_inline?&width=550&height=700&inlineId=ai-quick-'.$post->ID.'" title="Quick Post">[Quick]</a>';  
    $notebkstr = '&nbsp; <a class="mct-ai-notebk hide-if-no-js" id="'.$post->ID.'"href="#postId=ai-notebk-'.$post->ID.'" title="'.$post->post_title.'">[NoteBk]</a>';  
    
    if ($istrain == 'No' && $tgt) {  //Came from Getit
        $retstr .= '&nbsp; <a class="mct-ai-link" id="'.$post->ID.'" href="'.get_delete_post_link($post->ID).'" ><img src="'.$imgtrash.'" ></img></a>';
        $move_uri = add_query_arg(array('move' => strval($post->ID)), $train_base);
        $move_uri = wp_nonce_url($move_uri, 'mct_ai_move'.$post->ID);
        $retstr .= '&nbsp; <a class="mct-ai-link" href="'.$move_uri.'" >[Make Live]</a>';
        if (empty($mct_ai_optarray['ai_edit_makelive'])) {
            $draft_uri = add_query_arg(array('draft' => strval($post->ID)), $train_base);
            $draft_uri = wp_nonce_url($draft_uri, 'mct_ai_draft'.$post->ID);
            $retstr .= '&nbsp; <a class="mct-ai-link" href="'.$draft_uri.'" >[Make Draft]</a>';
        }
        if (!$ismulti) {
            $multi_uri = add_query_arg(array('multi' => strval($post->ID)), $train_base);
            $multi_uri = wp_nonce_url($multi_uri, 'mct_ai_multi'.$post->ID);
            $retstr .= '&nbsp; <a class="mct-ai-link" href="'.$multi_uri.'" >[Multi]</a>';
        }
        $retstr .=  $quickstr;
        $retstr .=  $notebkstr;
        return $retstr;
    }
    
    if ($istrain == 'No') return '';
    
    //Filter type, so just put up trash and Make Live and Multi
    if ($istrain == 'Filter' && $tgt) {
        $retstr .= '&nbsp; <a class="mct-ai-link" id="'.$post->ID.'" href="'.get_delete_post_link($post->ID).'" ><img src="'.$imgtrash.'" ></img></a>';
        $move_uri = add_query_arg(array('move' => strval($post->ID)), $train_base);
        $move_uri = wp_nonce_url($move_uri, 'mct_ai_move'.$post->ID);
        $retstr .= '&nbsp; <a class="mct-ai-link" id="'.$post->ID.'" href="'.$move_uri.'" >[Make Live]</a>';
        if (empty($mct_ai_optarray['ai_edit_makelive'])) {
            $draft_uri = add_query_arg(array('draft' => strval($post->ID)), $train_base);
            $draft_uri = wp_nonce_url($draft_uri, 'mct_ai_draft'.$post->ID);
            $retstr .= '&nbsp; <a class="mct-ai-link" href="'.$draft_uri.'" >[Make Draft]</a>';
        }
        if (!$ismulti) {
            $multi_uri = add_query_arg(array('multi' => strval($post->ID)), $train_base);
            $multi_uri = wp_nonce_url($multi_uri, 'mct_ai_multi'.$post->ID);
            $retstr .= '&nbsp; <a class="mct-ai-link" id="'.$post->ID.'" href="'.$multi_uri.'" >[Multi]</a>';
        }
        $retstr .=  $quickstr;
        $retstr .=  $notebkstr;
        return $retstr;
    }
    
    //Trained, but on training page, so just put out make live and Multi (No and Filter are gone by now)
    if ($istrain != 'Yes' && $tgt) {
        $retstr .= '&nbsp; <a class="mct-ai-link" id="'.$post->ID.'" href="'.get_delete_post_link($post->ID).'" ><img src="'.$imgtrash.'" ></img></a>';
        $move_uri = add_query_arg(array('move' => strval($post->ID)), $train_base);
        $move_uri = wp_nonce_url($move_uri, 'mct_ai_move'.$post->ID);
        $retstr .= '&nbsp; <a class="mct-ai-link" id="'.$post->ID.'" href="'.$move_uri.'" >[Make Live]</a>';
        if (empty($mct_ai_optarray['ai_edit_makelive'])) {
            $draft_uri = add_query_arg(array('draft' => strval($post->ID)), $train_base);
            $draft_uri = wp_nonce_url($draft_uri, 'mct_ai_draft'.$post->ID);
            $retstr .= '&nbsp; <a class="mct-ai-link" href="'.$draft_uri.'" >[Make Draft]</a>';
        }
        if (!$ismulti) {
            $multi_uri = add_query_arg(array('multi' => strval($post->ID)), $train_base);
            $multi_uri = wp_nonce_url($multi_uri, 'mct_ai_multi'.$post->ID);
            $retstr .= '&nbsp; <a class="mct-ai-link" id="'.$post->ID.'" href="'.$multi_uri.'" >[Multi]</a>';
        }
        $retstr .=  $quickstr;
        $retstr .=  $notebkstr;
        return $retstr;
    }
    if ($istrain != 'Yes') return '';  //Already trained, so go
    
    if ($tgt && !$mct_ai_optarray['ai_keep_good_here']){
        $train_uri = add_query_arg(array('good' => strval($post->ID), 'move' => strval($post->ID)), $train_base);
        $train_uri = wp_nonce_url($train_uri, 'mct_ai_train_good'.$post->ID);
        $retstr .= '&nbsp; <a class="mct-ai-link" id="'.$post->ID.' ai-good" href="'.$train_uri.'" ><img src="'.$imggood.'" ></img></a>'; 
    } else {
        $train_uri = add_query_arg(array('good' => strval($post->ID)), $train_base);
        $train_uri = wp_nonce_url($train_uri, 'mct_ai_train_good'.$post->ID);
        $retstr .= '&nbsp; <a class="mct-ai-link" id="'.$post->ID.' ai-good" href="'.$train_uri.'" ><img src="'.$imggood.'" ></img></a>'; 
    }
    $train_uri = add_query_arg(array('bad' => strval($post->ID)), $train_base);
    $train_uri = wp_nonce_url($train_uri, 'mct_ai_train_bad'.$post->ID);
    $retstr .= '&nbsp; <a class="mct-ai-link" id="'.$post->ID.' ai-bad" href="'.$train_uri.'" ><img src="'.$imgbad.'" ></img></a>'; 
    //Set the trash key
    $retstr .= '&nbsp; <a class="mct-ai-link" id="'.$post->ID.'" href="'.get_delete_post_link($post->ID).'" ><img src="'.$imgtrash.'" ></img></a>';
    if ($tgt){
        $move_uri = add_query_arg(array('move' => strval($post->ID)), $train_base);
        $move_uri = wp_nonce_url($move_uri, 'mct_ai_move'.$post->ID);
        $retstr .= '&nbsp; <a class="mct-ai-link" id="'.$post->ID.' ai-make-live" href="'.$move_uri.'" >[Make Live]</a>';
        if (empty($mct_ai_optarray['ai_edit_makelive'])) {
            $draft_uri = add_query_arg(array('draft' => strval($post->ID)), $train_base);
            $draft_uri = wp_nonce_url($draft_uri, 'mct_ai_draft'.$post->ID);
            $retstr .= '&nbsp; <a class="mct-ai-link" href="'.$draft_uri.'" >[Make Draft]</a>';
        }
        if (!$ismulti) {
            $multi_uri = add_query_arg(array('multi' => strval($post->ID)), $train_base);
            $multi_uri = wp_nonce_url($multi_uri, 'mct_ai_multi'.$post->ID);
            $retstr .= '&nbsp; <a class="mct-ai-link" id="'.$post->ID.'" href="'.$multi_uri.'" >[Multi]</a>';
        }
        $retstr .=  $quickstr;
        $retstr .=  $notebkstr;
    }
    return $retstr;
}

function mct_ai_relmeta(){

    
    add_meta_box('mct_ai_metabox','Relevance Data','mct_ai_relmetashow','post','normal','low');
    
}

function mct_ai_relmetatarget(){
    add_meta_box('mct_ai_metabox','Relevance Data','mct_ai_relmetashow','target_ai','normal','low');
}

function mct_ai_relmetashow($post){
    //Show the relevance data for a post in the meta box
    $reldata = get_post_meta($post->ID, 'mct_ai_relevance',true);
    if (empty($reldata)) return;
    
    echo 'Classed as '.strtoupper($reldata['classed']).' --> Good Score: '.$reldata['good'].' Bad Score: '.$reldata['bad'];
    if (!empty($reldata['dbsize'])){
        echo '  Features: '.$reldata['dbsize'];
    }
}


function mct_ai_del_multi($post){
    //Remove multi posts if publish
    
    if (!isset($_POST['mct_ai_ismulti'])) return '';
    $postobj = get_post($post);
    if ($postobj->post_status != 'publish') return '';
    if ($postobj->post_type != 'post') return '';
    $topics = wp_get_object_terms($postobj->ID,'topic',array('fields' => 'slugs'));
    $args = array(
        'post_type'       => 'target_ai',
        'numberposts' => -1,
        'topic' => $topics[0],
        'ai_class'        => 'multi'
    );
    $multis = get_posts($args);
    foreach ($multis as $multi){
        wp_trash_post($multi->ID);
    }
}

function mct_ai_linkmeta(){
    add_meta_box('mct_sl_metabox','Link Replacement for MyCurator','mct_sl_linkmetashow','target_ai','normal','low');
}

//Add the Create News/Twitter menu item
function bwc_add_link_alerts(){
    //add_links_page('Source Quick Add', 'Source Quick Add', 'edit_posts','mct_ai_quick_source', 'mct_ai_quick_source'); //Quick Add
    //add_links_page('Create News Feed', 'News or Twitter', 'edit_posts', 'bwc_create_news', 'bwc_create_news');// Google News Feed
}

function mct_ai_sources() {
    global $mct_ai_optarray;
    //Dispatch to correct page
    if (isset($_REQUEST['newfeed'])) {
        mct_ai_quick_source();
        return;
    }
    if (isset($_REQUEST['newsource'])) {
        bwc_create_news();
        return;
    }
    if (isset($_POST['feeds']) && !empty($_POST['feeds'])){
        if (!current_user_can('manage_options')) wp_die("Insufficient Privelege");
        check_admin_referer('mct_ai_delfeeds','delfeedsclick');  
        foreach ($_POST['feeds'] as $linkid) {
            wp_delete_link(intval($linkid));
        } 
        $msg = count($_POST['feeds'])." Feeds Deleted";
    }
    //Set up prefixes for links
    $ruri = $_SERVER['REQUEST_URI'];
    $editpage = $ruri."&newfeed&edit=";
    $newpage = $ruri."&newsource";
    $newfeed = $ruri."&newfeed";
    //Get sources
    $args = array(
        'limit'     => -1,
        'orderby'         => 'name',
        'order'           => 'ASC'); 
    $sources = get_bookmarks($args);
    //Set up pagination
    $myCount = count($sources);
    $maxrow = 25;
    $currentPage = 1;
    if (isset($_GET['paged'])){
        $currentPage = $_GET['paged'];
    }
    //Check if sources over max
    mct_ai_getplan();
    $src = mct_ai_sourcemax();
    //render the page
    ?>
    <div class='wrap'>
    
    <h2>MyCurator Sources List</h2>  
    <p>Sources are RSS feeds that provide articles from sites that you would like to curate articles.  You can capture feeds to this list using 
        our Source It tool, or add them manually using the Add New Sources link below.  Some Sources can be created here from Google News, Twitter Searches
        and Bing News using the Create News or Search link below.</p>
    <p>All Sources are given a Source Group that groups them together and allows you to assign multiple Sources  to a Topic easily.  When you create
        a Topic, you will assign one or more of the Source Groups to the Topic.  That tells MyCurator which Sources to use to find
        articles for the Topic. <span  class="button-primary" onclick="mctaishowvideo()">Need Help?</span></p>
    <?php echo mct_ai_helpvideo('sources'); ?>
    <p>Click the Title to Edit a Source.  Check the box next to Sources you don't need and click Delete Checked Sources to remove them.</p>
    <?php
    if (!empty($msg)){ 
       echo '<div id="message" class="updated" ><p><strong>'.$msg.'</strong></p></div>';
    }
    if ($src !== false && $src <= 0) {
        mct_ai_showsrc();
    }
    elseif ($src !== false) {
        $plan = unserialize($mct_ai_optarray['ai_plan']);
        echo '<p>Your Plan allows '.$plan['maxsrc'].' Sources and you have '.$src.' left to use';
        echo '<p><a href="'.$newfeed.'" >Click Here</a> to Add a <strong>New RSS Feed</strong> to your Sources&nbsp&nbsp&nbsp';
        echo '<a href="'.$newpage.'" >Click Here</a> to Create a <strong>Google Alert, Google News, Twitter Search or Bing Search</strong> to your Sources</p>';
    } else {
        echo '<p><a href="'.$newfeed.'" >Click Here</a> to Add a <strong>New RSS Feed</strong> to your Sources&nbsp&nbsp&nbsp';
        echo '<a href="'.$newpage.'" >Click Here</a> to Add a <strong>Google Alert, Google News, Twitter Search or Bing Search</strong> to your Sources</p>';
    }
    //Display pagination
    print("<div class=\"tablenav\">"); 
   $qargs = array(
       'paged' => '%#%', 
       );
   $page_links = paginate_links( array(
            'base' => add_query_arg($qargs ) ,
            'format' => '',
            'total' => ceil($myCount/$maxrow),
            'current' => $currentPage
    ));
    //Pagination display
    if ( $page_links )
            echo "<div class='tablenav-pages'>$page_links</div>";
    ?>
    <form name="delete_feeds" method="post" >
        
        <?php wp_nonce_field('mct_ai_delfeeds','delfeedsclick'); ?>
        <p align="left"><input name="del_feeds_button" value="Delete Checked Sources" type="submit" class="button-secondary"></p>
        
        <style>
        th.mct-nb-action {width: 10%; }
        th.mct-nb-name {width: 40%; }
        th.mct-nb-category {width: 20%; }
        th.mct-nb-url {width: 30%; }
    </style>
        <table class="widefat" >
            <thead>
                <tr>
                <th class="column-cb check-column th.mct-nb-action">Delete<input id="cb-select-all" type="checkbox" ></th>
                <th class="mct-nb-name">Source</th>
                <th class="mct-nb-category">Source Group</th>
                <th class="mct-nb-url">RSS Feed URL</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $bottom = ($currentPage - 1) * $maxrow;
	    $top = $currentPage * $maxrow;
            if ($top > $myCount) $top = $myCount;
            for ($i=$bottom; $i < $top; $i++) {
                $row = $sources[$i];
                $src_grp = get_terms(array('taxonomy' => 'link_category','object_ids' => $row->link_id));
                if (! current_user_can('edit_others_posts') ) continue;
                echo('<tr class="post-'.$row->link_id.'">');
                echo('<td class="check-column"><input name="feeds[]" type="checkbox" value="'.$row->link_id.'"  /></td>');
                echo('<td id="title-'.$row->link_id.'"><strong><a href="'.$editpage.$row->link_id.'" >'.$row->link_name.'</a></strong></td>');
                $grpname = '';
                foreach ($src_grp as $grp){
                    if (empty($grpname)) $grpname = $grp->name;
                    else $grpname .= ', '.$grp->name;
                }
                echo('<td >'.$grpname.'</td>');
                echo('<td>'.$row->link_rss.'</td>');
                echo('</tr>');
            } ?>
           </tbody>
        </table>
    </form>

<?php

    
}
function mct_ai_quick_source() {
    //Simple page to quickly add a new source
    //Handle POST
    global $mct_ai_optarray;
    
    //set up for insert
    $args = array(
        'feed_name' => "",
        'keywords' => "",
        'link_category' => "0",
        'rss-url' => "",
        'newlinkcat' => ""
    );
    $updated = false; //Assume an insert
    //Create go back link
    $ruri = $_SERVER['REQUEST_URI'];
    $pos = stripos($ruri,"&new");
    $backpage = '<p><a href="'.substr($ruri,0,$pos) .'" >Click Here to go Back to Sources List Page</a></p>';
    $msg = '';
    $msgclass = 'error';
    
    if (isset($_POST['Submit'])){
        check_admin_referer('mct_ai_quick_source','quicksource');
        if (isset($_GET['edit'])) {
            $updated = true;
            $args['link-id'] = $_GET['edit'];
        }
        if (!empty($_POST['sourceChk'])){
            $args['link_category'] = $_POST['sourceChk'];
        } else {
            if (empty($args['newlinkcat'])) $msg .= "Must choose a Source Group. ";
        }
        $args['newlinkcat'] = trim(sanitize_text_field($_POST['newlinkcat']));
        $args['feed_name'] = trim(sanitize_text_field($_POST['feed_name']));
        $args['rss-url'] =  esc_url($_POST['rss-url']);
        $args['save-url'] = parse_url($args['rss-url'], PHP_URL_HOST);
        //Validate args
        if (strlen($args['feed_name']) == 0) $msg .= 'Must have a Feed Name. ';
        if (strlen($args['rss-url']) == 0) $msg .= 'Must have a Feed URL. ';
        //if ok, post it
        if ($msg == '')  $msg = mct_ai_postlink($args, $updated);
        
        if ($msg == '' ) {
            if (!$updated) { 
                $args = array(
                'feed_name' => "",
                'keywords' => "",
                'link_category' => "0",
                'rss-url' => "",
                'newlinkcat' => ""
                );  
                $msg = 'New Source added';
                $msgclass = 'updated';
            } else {
               $msg = 'Source Updated';
               $msgclass = 'updated'; 
            }
        } 
    }
    if (isset($_GET['edit'])){
        //came in on edit call
        $updated = true;
        $linkid = $_GET['edit'];
        $linkvals = get_bookmark($linkid);
        $sources = array();
        $src_grp = get_terms(array('taxonomy' => 'link_category','object_ids' => $linkid));
        foreach ($src_grp as $grp) {
            $sources[] = $grp->term_id;
        }
        $args = array(
        'feed_name' => $linkvals->link_name,
        'keywords' => "",
        'link_category' => $sources,
        'rss-url' => $linkvals->link_rss,
        'newlinkcat' => ""
    );
    }
    //Get Link Categories for checkbox
    $terms = get_terms(array('taxonomy' => 'link_category', 'hide_empty' => false));
    //Check if sources over max
    mct_ai_getplan();
    $src = mct_ai_sourcemax();
    ?>
    <div class='wrap'>
    
    <?php
    if (!$updated) echo '<h2>MyCurator - Quickly Add an RSS Feed to your Sources</h2>';
    else echo '<h2>MyCurator - Edit a Source</h2>';
    echo ($backpage);
    if (!empty($msg)){ ?>
       <div id="message" class="<?php echo $msgclass; ?>" ><p><strong><?php echo $msg ; ?></strong></p></div>
    <?php } ?>    
    <p>You can use this Source in any of your MyCurator Topics by assigning the Source Group to your Topic in the Topics menu item. 
    <span  class="button-primary" onclick="mctaishowvideo()">Need Help?</span></p>
    <?php echo mct_ai_helpvideo('addsource'); ?>
    <p><strong>All fields are required except a New Source Group</strong></p>
    <?php if ($src !== false && $src <= 0 && !$updated) {
        mct_ai_showsrc();
        exit;
    } elseif ($src !== false) {
        $plan = unserialize($mct_ai_optarray['ai_plan']);
        echo '<p>Your Plan allows '.$plan['maxsrc'].' Sources and you have '.$src.' left to use';
    }
?>
    <form method="post" action="<?php echo esc_url($_SERVER['REQUEST_URI']).'&updated='.$updated; ?>"> 
        <table class="form-table" >
            <tr>
                <th scope="row">Source Name</th>
                <td><input name="feed_name" type="input" id="aname" size="50" maxlength="200" value="<?php echo $args['feed_name']; ?>" /></td>    
            </tr>            
            <tr>
                <th scope="row">Source URL</th>
                <td><input name="rss-url" type="input" id="aname" size="100" maxlength="200" value="<?php echo $args['rss-url']; ?>" /></td>    
            </tr>            
            </table>
            <!-- Sources Selection -->
                <h3>Select Source Groups for this Source</h3>
                <table class="form-table" >
                <?php foreach ($terms as $term) {  ?>
                   <tr>
                       <th scope="row"><?php echo $term->name; ?></th>
                       <td><input name="sourceChk[]" type="checkbox" value="<?php echo $term->term_id; ?> "
                         <?php if (!empty($args['link_category']) && in_array($term->term_id,$args['link_category'])) echo 'checked="checked"'; ?>/></td>
                   </tr>
                <?php } ?>
            </table>
            <tr>
                <th scope="row">OR Enter a New Source Group</th>
                <td><input name="newlinkcat" type="input" id="newlinkcat" size="50" maxlength="200" value="<?php echo $args['newlinkcat']; ?>" /></td>    
            </tr>
       </table>
        <?php wp_nonce_field('mct_ai_quick_source','quicksource'); ?>
        <div class="submit">
            <?php $btn = "Create Feed";
            if ($updated) $btn = 'Update Feed'; ?>
          <input name="Submit" type="submit" value="<?php echo $btn; ?>" class="button-primary" />
        </div>
       </form> 
    </div>    

    <?php
}
 

//Create News/Twitter Feed Screen
function bwc_create_news(){

    global $mct_ai_optarray;
    $msg = '';
    $follow = false;
    $feed_type = 'G';
    //Get Link Categories for dropdown
    $cats = array (
        'orderby' => 'name',
        'hide_empty' => FALSE,
        'name' => 'link_category',
        'taxonomy' => 'link_category'
    );
    $args = array(
        'feed_name' => "",
        'keywords' => "",
        'link_category' => "0",
        'newlinkcat' => ""
    );
    //Create go back link
    $ruri = $_SERVER['REQUEST_URI'];
    $pos = stripos($ruri,"&new");
    $backpage = '<p><a href="'.substr($ruri,0,$pos) .'" >Click Here to go Back to Sources List Page</a></p>';
    //Handle POST
    if (isset($_POST['Submit'])){
        check_admin_referer('mct_ai_newsfeed','tweetnews');
        //Validate Fields
        $args = array (
            'feed_name' => trim(sanitize_text_field($_POST['feed_name'])),
            'keywords' => trim(sanitize_text_field(stripslashes($_POST['keywords']))),
            'newlinkcat' => trim(sanitize_text_field($_POST['newlinkcat']))
        );
        $feed_type = $_POST['ftype'];
        if (!empty($_POST['sourceChk'])){
            $args['link_category'] = $_POST['sourceChk'];
        } else {
            if (empty($args['newlinkcat'])) $msg .= "Must choose a Source Group. ";
        }
        //Get the keywords and set the url
        if (strpos($args['keywords'],'@')!== false) {
            if ($feed_type != 'T') {
                $msg = "Can use Twitter Username only in Twitter Search.  ";
            } else {
                if (preg_match('/^@([a-z0-9_]{1,15})$/i',$args['keywords'],$match)) {
                    $newsterm = $match[1];
                    $follow = true;
                } else {
                    $msg = "Twitter username not valid, only one allowed and must be a-z, 0-9, _ and 15 characters or less.  ";
                }
            }
        } else {
            $newsterm = rawurlencode($args['keywords']); 
        }
        if ($feed_type == 'G') {
            $args['rss-url'] = 'https://news.google.com/rss/search/section/q/'.$newsterm.'/'.$newsterm.'?hl=en&gl=US&ned=us'; //Google news feed
            $args['save-url'] = 'https://news.google.com/';
        } elseif ($feed_type == 'T') {
            if ($follow) {
                $args['rss-url'] = 'http://api.twitter.com/1/statuses/user_timeline.rss?screen_name='.$newsterm;
            } else {
                $args['rss-url'] = 'http://search.twitter.com/search.rss?q='.$newsterm;  //twitter search string
            }
            $args['save-url'] = 'http://search.twitter.com/';
        } elseif ($feed_type == 'B') {
            $args['rss-url'] = 'https://www.bing.com/news/search?q='.$newsterm.'&format=rss&qft=interval%3d%228%22+sortbydate%3d%221%22'; //Bing Search string
            $args['save-url'] = 'https://www.bing.com/';
        } elseif ($feed_type == 'Y') {
            $args['rss-url'] = 'http://www.youtube.com/rss/search/'.$newsterm.'.rss'; //YouTube Search string
            $args['save-url'] = 'http://www.youtube.com/';
        } else $msg = 'Invalid Type Entered';
        //Validate args
        if (strlen($args['feed_name']) == 0) $msg .= 'Must have a Feed Name. ';
        if (strlen($args['keywords']) == 0) $msg .= 'Keyword may not be blank. ';
        if (empty($msg)) $msg = mct_ai_postlink($args);
        if (!empty($msg)) {
            $msgclass = 'error';
        } else {
            if ($feed_type == 'G') {
                $msg = 'Google News Feed Created';
            } elseif ($feed_type == 'T') {
                $msg = 'Twitter Search Created';
            } elseif ($feed_type == 'Y') {
                $msg = 'YouTube Search Created';
            }
            $msgclass = 'updated';
            $args = array(
                'feed_name' => "",
                'keywords' => "",
                'link_category' => "0",
                'newlinkcat' => ""
            ); //Don't let them recreate same one
        }
    }
    //Get Link Categories for checkbox
    $terms = get_terms(array('taxonomy' => 'link_category', 'hide_empty' => false));
    //Check if sources over max
    mct_ai_getplan();
    $src = mct_ai_sourcemax();
    //Start screen page
    ?>
    <div class='wrap'>
    <?php //screen_icon('link-manager'); ?>
    <h2>MyCurator - Create Google News, Bing News or Twitter Search as a Source</h2>
    <?php 
    echo ($backpage);
    if (!empty($msg)){ ?>
       <div id="message" class="<?php echo $msgclass; ?>" ><p><strong><?php echo $msg ; ?></strong></p></div>
    <?php } ?>    
    <p>Use this option to create a Google News, Bing News or Twitter Search that will be placed into your Sources for the Source Group you choose.  
        You can also follow a twitter user by choosing Twitter Search Source and entering their @username in the Keywords.
        You can then use this Source in any of your MyCurator Topics by including the Source Group as a source.
    <span  class="button-primary" onclick="mctaishowvideo()">Need Help?</span></p>
    <?php echo mct_ai_helpvideo('news'); ?>
    <p>Create a <a href="https://www.google.com/alerts" target="_blank">Google Alert</a> or a <a href="https://www.talkwalker.com/alerts" target="_blank">Talkwalker Alert</a> 
    <span  class="button-primary" onclick="mctaishowvideo2()">Need Help?</span></p>
    <?php echo mct_ai_helpvideo('galerts'); ?>
    <p>To use Twitter Search or Follow a Twitter User you <strong>must set up a Twitter App</strong> in the Twitter Tab under the MyCurator Options menu item.</p>
    <p><strong>All fields are required except a New Source Group</strong></p>
    <?php if ($src !== false && $src <= 0) {
        mct_ai_showsrc();
        exit;
    }elseif ($src !== false) {
        $plan = unserialize($mct_ai_optarray['ai_plan']);
        echo '<p>Your Plan allows '.$plan['maxsrc'].' Sources and you have '.$src.' left to use';
    } ?>
    <form method="post" action="<?php echo esc_url($_SERVER['REQUEST_URI'] ); ?>"> 
        <table class="form-table" >
            <tr>
                <th scope="row">Google News Source? </th>
                <td><input name="ftype" type="radio" value="G" <?php if ($feed_type == 'G') echo 'checked="checked"'; ?> /></td>
            </tr>
            <tr>
                <th scope="row">Twitter Search Source? </th>
                <td><input name="ftype" type="radio" value="T" <?php if ($feed_type == 'T') echo 'checked="checked"'; ?> /></td>    
            </tr> 
            <tr>
                <th scope="row">Bing News Source? </th>
                <td><input name="ftype" type="radio" value="B" <?php if ($feed_type == 'B') echo 'checked="checked"'; ?> /></td>    
            </tr> 
            <tr>
                <th scope="row">Source Name</th>
                <td><input name="feed_name" type="input" id="aname" size="50" maxlength="200" value="<?php echo $args['feed_name']; ?>" /></td>    
            </tr>            
            <tr>
                <th scope="row">Source Keywords</th>
                <td><input name="keywords" type="input" id="keywords" size="50" maxlength="200" value="<?php echo esc_attr($args['keywords']); ?>" />
                    <em>You can follow a twitter user by entering their name as @username (only one may be entered)</em></td>    
            </tr>
            
            <!-- Sources Selection -->
            <tr>
                <th scope=""row"><h3>Select Source Groups</h3></th>
            </tr>

            <?php foreach ($terms as $term) {  ?>
               <tr>
                   <th scope="row"><?php echo $term->name; ?></th>
                   <td><input name="sourceChk[]" type="checkbox" value="<?php echo $term->term_id; ?> "
                   <?php if (!empty($args['link_category']) && in_array($term->term_id,$args['link_category'])) echo 'checked="checked"'; ?>/></td>
               </tr>
            <?php } ?>
            
            <tr>
                <th scope="row">OR Enter a New Source Group</th>
                <td><input name="newlinkcat" type="input" id="newlinkcat" size="50" maxlength="200" value="<?php echo $args['newlinkcat']; ?>" /></td>    
            </tr>
       </table>
        <?php wp_nonce_field('mct_ai_newsfeed','tweetnews'); ?>
        <div class="submit">
          <input name="Submit" type="submit" value="Create Feed" class="button-primary" />
        </div>
       </form> 
    </div>    

    <?php
}

//Set up bulk actions on Training Posts CPT Admin
//Adopted from FoxRunSoftware Custom Bulk Action Demo by Justin Stern
//At http://www.foxrunsoftware.net/articles/wordpress/add-custom-bulk-action/
if (!class_exists('mct_ai_Custom_Bulk_Action')) {
	class mct_ai_Custom_Bulk_Action {
		public function __construct() {
			
			if(is_admin()) {
				// admin actions/filters
                                add_action('admin_print_scripts-edit.php', array(&$this, 'custom_bulk_admin_hdrscript'));
                                add_action('admin_print_styles-edit.php', array(&$this, 'custom_bulk_admin_hdrstyle'));
				add_action('load-edit.php',         array(&$this, 'custom_bulk_action'));
				add_action('admin_notices',         array(&$this, 'custom_bulk_admin_notices'));
			}
		}

		/**
		 * Step 1: Queue Thickbox and add the custom Bulk Action to the select menus
		 */
                function custom_bulk_admin_hdrscript() {
                    global $typenow;
                    if ($typenow == 'target_ai' ){
                        if (isset($_REQUEST['post_status']) && $_REQUEST['post_status'] == 'trash') return;
                        mct_ai_trainscript('admin');
                    }
                }
                function custom_bulk_admin_hdrstyle() {
                    global $typenow;
                    if ($typenow == 'target_ai' ){
                        if (isset($_REQUEST['post_status']) && $_REQUEST['post_status'] == 'trash') return;  
                        mct_ai_trainstyle();
                    }
                    
                }
                
		/**
		 * Step 2: handle the custom Bulk Action
		 * 
		 * Based on the post http://wordpress.stackexchange.com/questions/29822/custom-bulk-action
		 */
		function custom_bulk_action() {
			global $typenow, $mct_ai_optarray;
			$post_type = $typenow;
			
			if($post_type == 'target_ai') {
				
				// get the action
				$wp_list_table = _get_list_table('WP_Posts_List_Table');  // depending on your resource type this could be WP_Users_List_Table, WP_Comments_List_Table, etc
				$action = $wp_list_table->current_action();
				
				$allowed_actions = array("postlive","postdraft","traingood","trainbad","multi","author");
				if(!in_array($action, $allowed_actions)) return;
				
				// security check
				check_admin_referer('bulk-posts');
				
				// make sure ids are submitted.  depending on the resource type, this may be 'media' or 'ids'
				if(isset($_REQUEST['post'])) {
					$post_ids = array_map('intval', $_REQUEST['post']);
				}
				
				if(empty($post_ids)) return;
				
				// this is based on wp-admin/edit.php - get rid of any notice message args
				$sendback = remove_query_arg( array('postedlive','posteddraft', 'trainedgood','trainedbad','setmulti','setauthor', 'untrashed', 'deleted', 'ids'), wp_get_referer() );
				if ( ! $sendback )
					$sendback = admin_url( "edit.php?post_type=$post_type" );
				
				$pagenum = $wp_list_table->get_pagenum();
				$sendback = add_query_arg( 'paged', $pagenum, $sendback );
				
				switch($action) {
					case 'postlive':
						// if we set up user permissions/capabilities, the code might look like:
						//if ( !current_user_can($post_type_object->cap->export_post, $post_id) )
						//	wp_die( __('You are not allowed to export this post.') );
						$cnt = 0;
						foreach( $post_ids as $post_id ) {
							mct_ai_traintoblog($post_id,'publish');
							$cnt++;
						}
						$sendback = add_query_arg( array('postedlive' => $cnt, 'ids' => join(',', $post_ids) ), $sendback );
					        break;

                                        case 'postdraft':
						$cnt = 0;
						foreach( $post_ids as $post_id ) {
							mct_ai_traintoblog($post_id,'draft');
							$cnt++;
						}
						$sendback = add_query_arg( array('posteddraft' => $cnt, 'ids' => join(',', $post_ids) ), $sendback );
					        break;
                                                
                                        case 'traingood':
						$cnt = 0;
						foreach( $post_ids as $post_id ) {
                                                    //already trained?
                                                    $trained = get_post_meta($post_id,'mct_ai_trained',true);
                                                    if (!empty($trained)) continue;
				                    // Get the topic name if Relevance type
                                                    $tname = mct_ai_get_tname_ai($post_id);
                                                    if ($tname != ''){
                                                        mct_ai_trainpost($post_id, $tname, 'good');  
                                                        $cnt++;
                                                        if (!empty($mct_ai_optarray['ai_keep_good_here'])) continue;//leave on training page
                                                        if (!empty($mct_ai_optarray['ai_edit_makelive'])) {
                                                            mct_ai_traintoblog($post_id,'draft');
                                                        } else {
                                                            mct_ai_traintoblog($post_id,'publish');
                                                        }
                                                    }
						}
						$sendback = add_query_arg( array('trainedgood' => $cnt, 'ids' => join(',', $post_ids) ), $sendback );
					        break;      
                                                
                                        case 'trainbad':
						$cnt = 0;
						foreach( $post_ids as $post_id ) {
                                                    //already trained?
                                                    $trained = get_post_meta($post_id,'mct_ai_trained',true);
                                                    if (!empty($trained)) continue;
				                    // Get the topic name if Relevance type
                                                    $tname = mct_ai_get_tname_ai($post_id);
                                                    if ($tname != ''){
                                                        mct_ai_trainpost($post_id, $tname, 'bad');  
                                                        wp_trash_post($post_id);
                                                        $cnt++;
                                                    }
						}
						$sendback = add_query_arg( array('trainedbad' => $cnt, 'ids' => join(',', $post_ids) ), $sendback );
					        break;   
                                                
                                        case 'multi':
						$cnt = 0;
						foreach( $post_ids as $post_id ) {
                                                        //Check if relevance type
                                                        $rel = get_the_terms($post_id, 'ai_class');
                                                        if (!$rel) continue;
							mct_ai_train_multi($post_id);
							$cnt++;
						}
						$sendback = add_query_arg( array('setmulti' => $cnt, 'ids' => join(',', $post_ids) ), $sendback );
					        break;                                                
					
					 case 'author':
                                                $cnt = 0;
                                                if ($_REQUEST['post_author'] == -1) return;
                                                $new_author = absint($_REQUEST['post_author']);
						foreach( $post_ids as $post_id ) {
                                                    wp_update_post(array('ID' => $post_id, 'post_author' => $new_author));
                                                    $cnt += 1;
                                                }
                                                $sendback = add_query_arg( array('setauthor' => $cnt, 'ids' => join(',', $post_ids) ), $sendback );
					        break;  
                                         default: return;
				}
				
				$sendback = remove_query_arg( array('action', 'action2', 'tags_input', 'post_author', 'comment_status', 'ping_status', '_status',  'post', 'bulk_edit', 'post_view'), $sendback );
				
				wp_redirect($sendback);
				exit();
			}
		}
		
		
		/**
		 * Step 3: display an admin notice on the Posts page after exporting
		 */
		function custom_bulk_admin_notices() {
			global $post_type, $pagenow, $mct_ai_optarray;
                        
			if (isset($_REQUEST['post_status']) && $_REQUEST['post_status'] == 'trash') return;
                        $message = '';
			if($pagenow == 'edit.php' && $post_type == 'target_ai' && isset($_REQUEST['postedlive']) && (int) $_REQUEST['postedlive']) {
				$message = sprintf( _n( 'Posts made live', '%s posts made live.', $_REQUEST['postedlive'] ), number_format_i18n( $_REQUEST['postedlive'] ) );
				echo "<div class=\"updated\"><p>{$message}</p></div>";
			}
                        if($pagenow == 'edit.php' && $post_type == 'target_ai' && isset($_REQUEST['posteddraft']) && (int) $_REQUEST['posteddraft']) {
				$message = sprintf( _n( 'Draft Posts', '%s draft posts.', $_REQUEST['posteddraft'] ), number_format_i18n( $_REQUEST['posteddraft'] ) );
				echo "<div class=\"updated\"><p>{$message}</p></div>";
			}
                        if($pagenow == 'edit.php' && $post_type == 'target_ai' && isset($_REQUEST['trainedgood']) && (int) $_REQUEST['trainedgood']) {
				$message = sprintf( _n( 'Train Good', '%s trained good.', $_REQUEST['trainedgood'] ), number_format_i18n( $_REQUEST['trainedgood'] ) );
				echo "<div class=\"updated\"><p>{$message}</p></div>";
			}
                        if($pagenow == 'edit.php' && $post_type == 'target_ai' && isset($_REQUEST['trainedbad']) && (int) $_REQUEST['trainedbad']) {
				$message = sprintf( _n( 'Train Bad', '%s trained bad and removed.', $_REQUEST['trainedbad'] ), number_format_i18n( $_REQUEST['trainedbad'] ) );
				echo "<div class=\"updated\"><p>{$message}</p></div>";
			}
                        if($pagenow == 'edit.php' && $post_type == 'target_ai' && isset($_REQUEST['setmulti']) && (int) $_REQUEST['setmulti']) {
				$message = sprintf( _n( 'Set to Multi', '%s set to multi.', $_REQUEST['setmulti'] ), number_format_i18n( $_REQUEST['setmulti'] ) );
				echo "<div class=\"updated\"><p>{$message}</p></div>";
			}
                        if($pagenow == 'edit.php' && $post_type == 'target_ai' && isset($_REQUEST['setauthor']) && (int) $_REQUEST['setauthor']) {
				$message = sprintf( _n( 'Changed Author', '%s Authors changed.', $_REQUEST['setauthor'] ), number_format_i18n( $_REQUEST['setauthor'] ) );
				echo "<div class=\"updated\"><p>{$message}</p></div>";
			}
                        //Remove query arg from URI, otherwise we sometimes lose the chance
                        if (!empty($message)){
                            $_SERVER['REQUEST_URI'] = remove_query_arg( array('postedlive','posteddraft', 'trainedgood','trainedbad','setmulti','setauthor', 'ids'), $_SERVER['REQUEST_URI'] );
                        }
                        if ($pagenow == 'edit.php' && $post_type == 'target_ai') {
                            mct_nb_dialog();
                            mct_ai_checkcron();
                            if (empty($mct_ai_optarray['ai_no_fmthelp'])){
                               $message = '<a class="thickbox" href="#TB_inline?&width=550&height=450&inlineId=ai-format-help" title="Training Posts Formatting">Click if you have Format Problems</a> ';
                               $message .= '<span  class="button-primary" onclick="mctaishowvideo()">Need Help?</span>';
                               $message .= mct_ai_helpvideo('curation');
                               $message = $message.mct_ai_inline_fmthelp();
                               echo "<div class=\"updated\">{$message}</div>";
                            } 
                            //Max Sources Message
                            if (mct_ai_sourcemax() < 0) {
                                $message = "Too Many Sources - Some Feeds are being Skipped - Check the MyCurator Error Log";
                                echo "<div class=\"error\">{$message}</div>";
                            }
                        }
		}
		
		function perform_export($post_id) {
			// do whatever work needs to be done
			return true;
		}
	}
}

new mct_ai_Custom_Bulk_Action();

function mct_ai_trainscript($page) {
    //Enque training script
    
    global $mct_ai_optarray;
    
    $jsdir = plugins_url('js/MyCurator_training.js',__FILE__);
    wp_enqueue_script('mct_ai_train',$jsdir,array('jquery','thickbox','jquery-ui-dialog'),'1.0.4');
    $includes_url = includes_url();
    $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
    $params = array(
        'tb_pathToImage' => "{$includes_url}js/thickbox/loadingAnimation.gif",
        'tb_closeImage'  => "{$includes_url}js/thickbox/tb-close.png",
        'page_type' => $page,
        'editmove' => (empty($mct_ai_optarray['ai_edit_makelive'])) ? '0' : '1',
        'editurl' => admin_url('post.php',$protocol),
        'ajaxurl' => admin_url('admin-ajax.php',$protocol)
    );
    wp_localize_script('mct_ai_train', 'mct_ai_train', $params);
}

function mct_ai_trainstyle(){
    //Enque training styles
    wp_enqueue_style('thickbox');
    wp_enqueue_style('wp-jquery-ui-dialog');
    $style = plugins_url('css/MyCurator_train.css',__FILE__);
    wp_register_style('myctrain',$style,array(),'1.0.2');
    wp_enqueue_style('myctrain');
}

function mct_ai_checkcron(){
    //Check if cron is set up, reset if not
    global $mct_ai_optarray;
    
    if ($mct_ai_optarray['ai_on']){
        if (!wp_next_scheduled('mct_ai_cron_process')){
            $cronperiod = 'mct6hour';  //default if not set
            if ($mct_ai_optarray['ai_cron_period'] == '3') $cronperiod = 'mct3hour';
            if ($mct_ai_optarray['ai_cron_period'] == '12') $cronperiod = 'twicedaily';
            if ($mct_ai_optarray['ai_cron_period'] == '24') $cronperiod = 'daily';
            $hour = rand(4,8)-get_option('gmt_offset');
            $strt = mktime($hour);  
            wp_schedule_event($strt,$cronperiod,'mct_ai_cron_process');
        }
        if (!empty($mct_ai_optarray['ai_page_rqst'])){
            if (!wp_next_scheduled('mct_ai_cron_rqstproc')){
                $strt = time()+(60*30);  //30 minutes from now
                wp_schedule_event($strt,'hourly','mct_ai_cron_rqstproc');
            }
        }
    }
}

?>