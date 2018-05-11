<?php 
define('IFRAME_REQUEST' , true);

$homepath = (isset($_GET['homepath']) ? $_GET['homepath'] : '');

if($homepath != '')
	include_once($homepath . '/wp-admin/admin.php' );
else
	require_once('./admin.php');
?>
<script type="text/javascript">
var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>
<?php
	wp_enqueue_style( 'colors' );
	wp_enqueue_script( 'post' );
	_wp_admin_html_begin();
	do_action('admin_print_styles');
	do_action('admin_print_scripts');
	do_action('admin_head');

if($homepath != '')
	include_once($homepath . '/wp-admin/includes/plugin.php' );
else
	include_once( 'includes/plugin.php' );


	if ( ! current_user_can('edit_posts') )
		wp_die( __( 'Cheatin&#8217; uh?' ) );

	if (class_exists('ybi_product'))
	{
		$CurationSuiteProduct = new ybi_product('Curation Suite');
		if(do_validate_license($CurationSuiteProduct)!==true)
			wp_die( __( '<p style="text-align:center;">Please enter your license key for Curation Suite&trade; using the You Brand, Inc. Licensing Plugin - <a href=" ' . self_admin_url('admin.php?page=youbrandinc-license') . '"> Enter License Here</p>' ) );	
	}
	else
	{
		wp_die( __( '<p style="text-align:center;">Please install and activate the You Brand, Inc. Licensing Plugin - <a href="https://members.youbrandinc.com/dashboard/getting-started/license-keys/">Visit You Brand, Inc. Members Area to Download</a>.</p>' ) );
	}

$title = isset( $_GET['t'] ) ? trim( strip_tags( html_entity_decode( stripslashes( $_GET['t'] ) , ENT_QUOTES) ) ) : '';

$selection = '';
if ( !empty($_GET['s']) ) {
	$selection = str_replace('&apos;', "'", stripslashes($_GET['s']));
	$selection = trim( htmlspecialchars( html_entity_decode($selection, ENT_QUOTES) ) );
}

if ( ! empty($selection) ) {
	$selection = preg_replace('/(\r?\n|\r)/', '</p><p>', $selection);
	//remove by scott and modified right below it
//	$selection = '<p>' . str_replace('<p></p>', '', $selection) . '</p>';
	$selection = str_replace('<p></p>', '\n\n', $selection);
}

// we capture the raw url to use it from an android or iOS share
$rawURL =  $_GET['u'];
$url = isset($_GET['u']) ? esc_url($_GET['u']) : '';
$is_quick_add = isset($_GET['quickAdd']) ? esc_attr($_GET['quickAdd']) : '';

$options = get_option('curation_suite_data');

$curation_suite_default_curatethis_action = 'show_curate_action';
if(is_array($options) && array_key_exists('curation_suite_default_curatethis_action',$options))
    $curation_suite_default_curatethis_action = $options['curation_suite_default_curatethis_action'];

$sourceDomain = ybi_cu_getDomainName($url);
$closeWindow = false;
$action_type = "";
$action_data = "";
//echo 'url : ' . $url;
// if this link is a quickadd link then we add it and we close the window right away
if ($is_quick_add == 'yes' ) {
	echo '<h2>' . __('Adding Link...') . '</h2>';
	$action_type = 'quick-add';

	// for some reason the shres from Android contain the title in the URL parameter or the URL in the title parameter
	// this will try to find a URL in the passed u value... it uses the raw u value and is not esecaped
	// there 
	/*if($_GET['fromAndroid'] == 'yes' && $url == '')
	{
		//http://css-tricks.com/snippets/php/find-urls-in-text-make-links/
		$reg_exUrl = "/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
		if(preg_match($reg_exUrl, $rawURL, $theURL)) {
			$url = $theURL[0];	
		}
	}*/
	// sometimes from shares we have the title in the url and the url in the title
	// these lines replace the url in the title and the title in the url
	$title = str_replace($url, '',$title);
	$url = str_replace($title, '',$url);

	// we send a blank array as the category array, that logic is handled in the function
	ybi_cu_add_link($url,$title, '', array(),'',true);

	//echo urldecode($_SERVER['REQUEST_URI']);

	$closeWindow = true;
} else {
    $action_type = 'curate-action';
}
    // log the action
    $action_response = cs_action_log($action_type, $url);

?>
<script type="text/javascript">
<?php 
// if window is close we don't load anything down below
// !! This If is closed all the way at the bottom of the page as that way we don't load anything below before we close as there is no need to do that.
if($closeWindow): ?>
	window.close();
	</script>
<?php else: ?>
//<![CDATA[
addLoadEvent = function(func){if(typeof jQuery!="undefined")jQuery(document).ready(func);else if(typeof wpOnload!='function'){wpOnload=func;}else{var oldonload=wpOnload;wpOnload=function(){oldonload();func();}}};
var userSettings = {'url':'<?php echo SITECOOKIEPATH; ?>','uid':'<?php if ( ! isset($current_user) ) $current_user = wp_get_current_user(); echo $current_user->ID; ?>','time':'<?php echo time() ?>'};
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>', pagenow = 'press-this', isRtl = <?php echo (int) is_rtl(); ?>;
var photostorage = false;
//]]>
</script>
</head>
<body class="press-this wp-admin" id="curate_action_body">
<div class="wrap">
<div id="top_bar">
		<img src="<?php echo plugins_url(); ?>/curation-suite/i/curation-suite-icon-15x19.png" style="float: left;margin: 0 5px 0 0;" />
<span>Curation Suite&#8482; - <?php  echo get_bloginfo('name'); ?></span>
        <span class="close_link"><a href="javascript:;" class="close_curate_window">Close Window</a></span>
</div>
<div id="left_column">
    <div class="source_wrap posts_wrap">
    	<div id="link_domain_tools">Tools:
        <a href="http://www.opensiteexplorer.org/links?site=<?php echo $url; ?>" target="_blank" title="MOZ Open Site Explorer">
        <img src="<?php echo plugins_url(); ?>/curation-suite/i/moz-icon.ico" />MOZ</a>
         <a href="http://www.majesticseo.com/reports/site-explorer/summary/<?php echo $sourceDomain; ?>?IndexDataSource=F" target="_blank" title="MajesticSEO Site Explorer"><img src="<?php echo plugins_url(); ?>/curation-suite/i/majestic-icon.png" />MajesticSEO</a>
         <a href="https://ahrefs.com/site-explorer/overview/subdomains/?target=<?php echo $url; ?>" target="_blank" title="aHREFS Site Explorer"><img src="<?php echo plugins_url(); ?>/curation-suite/i/ahrefs-icon.ico" />ahrefs</a>
        </div>

        <h3><strong>Title:</strong> <?php echo $title; ?></h3>
        <p class="source_url"><strong>Source:</strong> <?php echo $url; ?></p>
        <?php 
			$new_post_url = $url;
			$new_post_url = str_replace("http://", "", $new_post_url);
			$new_post_url = str_replace("https://", "xxxxs", $new_post_url);
			//http://localhost/wp_beta/wp-admin/post-new.php?post_type=page

		if($curation_suite_default_curatethis_action=='new_post' || $curation_suite_default_curatethis_action=='new_page') {
		    $redirect_link = self_admin_url('post-new.php')."?u=". urlencode($new_post_url);
            if($curation_suite_default_curatethis_action=='new_page')
                $redirect_link = self_admin_url('post-new.php?post_type=page')."&u=". urlencode($new_post_url);

            header("Location: ".$redirect_link);
		    ?>
		    <script type="text/javascript">
                // do nothing for now, here in case we need a javacript fall back.
		    </script>
<?php
		}
		?>


        <?php 
		$post_type_arr = ybi_cu_get_post_type_feature();
		foreach($post_type_arr as $post_type) { 
			if($post_type == 'post') {
			?>
	        <h2 style="display:block; float:right;"><a href="<?php echo self_admin_url('post-new.php') ?>?u=<?php echo urlencode($new_post_url); ?>" class="add-new-h2"><i class="fa fa-plus"></i> Create New Post</a></h2>
			<?php
			}
			else
			{
				$obj = get_post_type_object( $post_type );
		?>
        
	        <h2 style="display:block; float:right;"><a href="<?php echo self_admin_url('post-new.php?post_type=' . $post_type) ?>&u=<?php echo urlencode($new_post_url); ?>" class="add-new-h2">
            <i class="fa fa-plus"></i> Create New <?php echo $obj->labels->singular_name; ?></a></h2>        

        <?php 
			}
		} ?>
    </div>
	<div>
        <form action="" method="post" id="ybi-curation-suite-search-posts-form">
          <input type="hidden" name="url" id="ybi_url" class="regular-text ybi_url_txt" value="<?php echo $url; ?>" style="width: 400px;" />
          <input type="hidden" name="title" id="ybi_title" class="regular-text ybi_url_txt" value="<?php echo $title; ?>" style="width: 400px;" />
          <div class="post_search_w">
          <div class="input-group margin-bottom-sm">
	      <span class="input-group-addon"><i class="fa fa-search fa-fw"></i></span>
         <input type="text" name="user" id="rcp-user" class="regular-text ybi_curation_suite_post_search form-control" />
            </div>
          </div>
          <div class="posts_wrap">
            <div class="rcp-ajax waiting" style="display: none; text-align:center; font-size: 20px;"><i class="fa fa-spinner fa-spin"></i></div>
            <div id="ybi_curation_suite_post_search_results">
            <strong class="no_search_results">Enter Text Above to Search Published Content</strong></div>
          </div>
    </div>
    <div class="posts_wrap">
        <h2><i class="fa fa-calendar-o"></i> Current Drafts</h2>
        <table class="wp-list-table widefat fixed posts" cellspacing="0">
        <thead>
            <tr>
                <th id="title" class="manage-column column-title sortable desc" style="" scope="col"><span>Title</span></th>
                <th id="author" class="manage-column column-title sortable desc" style="" scope="col"><span>Author</span></th>
                <th id="categories" class="manage-column column-title sortable desc" style="" scope="col"><span>Categories</span></th>
                <th id="date" class="manage-column column-title sortable desc" style="" scope="col"><span>Date</span></th>
            </tr>
        </thead>
        <?php
            $alternate = 'alternate';
            $i = 1;
			$args = array(
			'post_status' => 'draft',
			'showposts' => 20,
			'post_type' => $post_type_arr
			);
			
            $my_query = new WP_Query($args);
            if ($my_query->have_posts()) : while ($my_query->have_posts()) :
                $my_query->the_post();
                $the_title = get_the_title();
                if($the_title == '')
                    $the_title = '<em>no title</em>';
                    
                ?>
    
          <tr class="type-post status-publish format-standard hentry category-marketing <?php if(0 != $i % 2): echo $alternate; endif; ?> iedit author-self level-0"><td class="post-title page-title column-title">
          <a href="<?php echo admin_url('post.php?post='.get_the_ID().'&action=edit&u='.$url); ?>" class="row-title"><?php echo $the_title; ?></a></td>
          <td><?php echo get_the_author(); ?></td>
          <td><?php
          $commaCats = '';
          $categories = the_category();
          if($categories)
            $commaCats = implode(", ", $categories);
          echo $commaCats; ?></td>
          <td><?php echo get_the_date(); ?></td>
          </tr>
        <?php 
        $i++;
        endwhile; ?>
        <?php else: ?>
         <tr class="type-post status-publish format-standard hentry category-marketing iedit author-self level-0">
            <td class="post-title page-title column-title">
              <?php _e('There are 0 upcoming drafts.'); ?>
            </td>
        </tr>
        <?php endif; ?>
        </table>
      </div>
      <div class="posts_wrap">
        <h2><i class="fa fa-calendar"></i> Latest Published Content</h2>
        <table class="wp-list-table widefat fixed posts" cellspacing="0">
        <thead>
            <tr>
                <th id="title" class="manage-column column-title sortable desc" style="" scope="col"><span>Title</span></th>
                <th id="author" class="manage-column column-title sortable desc" style="" scope="col"><span>Author</span></th>
                <th id="categories" class="manage-column column-title sortable desc" style="" scope="col"><span>Categories</span></th>
                <th id="date" class="manage-column column-title sortable desc" style="" scope="col"><span>Date</span></th>
            </tr>
        </thead>
        <?php
            $alternate = 'alternate';
            $i = 1;
			$args = array(
			'post_status' => 'publish',
			'showposts' => 20,
			'post_type' => $post_type_arr
			);
            $my_query = new WP_Query($args);
       if ($my_query->have_posts()) : while ($my_query->have_posts()) :
                $my_query->the_post();
                $the_title = get_the_title();
                if($the_title == '')
                    $the_title = '<em>no title</em>';
                    
                ?>
    
          <tr class="type-post status-publish format-standard hentry category-marketing <?php if(0 != $i % 2): echo $alternate; endif; ?> iedit author-self level-0"><td class="post-title page-title column-title">
          <a href="post.php?post=<?php echo the_ID(); ?>&action=edit&u=<?php echo $url; ?>" class="row-title"><?php echo $the_title; ?></a></td>
          <td><?php echo get_the_author(); ?></td>
          <td><?php
          $commaCats = '';
          $categories = the_category();
          if($categories)
          $commaCats = implode(", ", $categories);
          echo $commaCats; ?></td>
          <td><?php echo get_the_date(); ?></td>
          </tr>
        <?php 
        $i++;
        endwhile; ?>
    
        <?php else: ?>
         <tr class="type-post status-publish format-standard hentry category-marketing iedit author-self level-0">
            <td class="post-title page-title column-title">
              <?php _e('There are 0 published posts.'); ?>
            </td>
        </tr>
        <?php endif; ?>
        </table>
      </div>
  </div><!--left-column-->
  <div id="right_column">
        <div class="posts_wrap add_to_link_bucket_wrap">
        <h2><i class="fa fa-link"></i> Add to Link Bucket</h2>
        <table class="wp-list-table widefat fixed posts" cellspacing="0">
        <tr>
		  <td>
            <div id="add_link_message"></div>
                <div id="category-all" class="tabs-panel wp-tab-panel">
                <ul id="categorychecklist" class="categorychecklist form-no-clear">
                <?php 
                    $taxonomy = 'link_buckets';
                    $args = array('hide_empty' => false,);
                    $terms = get_terms( $taxonomy, $args );
                
                    //var_dump($terms);
                    foreach($terms as $val) {
                        $term_id = $val->term_id;
                        $term_name = $val->name;
                ?>
                <li><label class="link_bucket_cat_label"><input type="checkbox" value="<?php echo $term_id; ?>" rel="<?php echo $term_id; ?>" name="linkcategories" class="linkcats" id="link_category_<?php echo $term_id; ?>" />
                <?php echo $term_name; ?></label></li>
                <?php 
                    } //foreach($terms as $val)
                ?>
                <span class="added_category_chkbox"></span>
                </ul>
                <hr />
                 <label class="add_bucket_lbl">Add New Bucket:</label>
                <input type="text" name="add_new_link_bucket_category" id="add_new_link_bucket_category" />
            </div>
			<div class="link_notes_wrapper">
			 <label class="add_bucket_lbl">Notes (optional):</label>
				<textarea rows="30" class="links_notes_textarea" name="link_notes" id="link_notes"></textarea>
			</div>
          <div class="add_links_buttons_block">
              <h2><a href="javascript:;" class="ybi_add_link add-new-h2" rel="keep_open"><i class="fa fa-plus"></i> Add Link</a></h2>
              <h2><a href="javascript:;" class="ybi_add_link add-new-h2" rel="close"><i class="fa fa-plus"></i> Add Link & Close</a></h2>
          </div>
        </td>
     </tr>
     </table>
    </form>
  </div>
</div>
</body>
</html>
<?php
do_action('admin_footer');
do_action('admin_print_footer_scripts');
endif; // close window if
?>