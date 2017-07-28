<?php
/**
 * Editor CSS Properties Panel Template
 *
 * @author 		WaspThemes
 * @category 	Template
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/* ---------------------------------------------------- */
/* Register Yellow Pencil Panel							*/
/* ---------------------------------------------------- */
function yp_yellow_penci_bar() {

	$yellow_pencil_uri = yp_get_uri();


	// Get protocol
	$protocol = is_ssl() ? 'https' : 'http';

	// Href
	$hrefA = $_GET['href'];

	// Update protocol.
	if(strstr($hrefA,'://') == true){
		$hrefNew = explode("://",$hrefA);
		$hrefNew = $protocol.'://'.$hrefNew[1];
	}elseif(strstr($hrefA,'://') == false){
		$hrefNew = $protocol.'://'.$hrefA;
	}

	// Filter
	$hrefNew = esc_url($hrefNew);


	$liveLink = add_query_arg(array('yp_live_preview' => 'true'),$hrefNew);

	if(isset($_GET['yp_id'])){
		$liveLink = add_query_arg(array('yp_id' => intval($_GET['yp_id'])),esc_url($liveLink));
	}elseif(isset($_GET['yp_type'])){
		$liveLink = add_query_arg(array('yp_type' => trim( strip_tags( $_GET['yp_type'] ) )),esc_url($liveLink));
	}

	// if isset out, set yp_out to live preview
	if(isset($_GET['yp_out'])){

		$liveLink = add_query_arg(array('yp_out' => 'true'),$liveLink);

	}

	$liveLink = str_replace("#038;yp_live_preview", "&amp;yp_live_preview", $liveLink);
	
    echo "<div class='yp-select-bar yp-disable-cancel'>
		<div class='yp-editor-top'>
			
			<a href='".$hrefNew."' class='wf-close-btn-link'><span data-toggle='tooltip' data-placement='left' title='".__('Close Editor','yp')."' class='yp-close-btn'></span></a>

			<a class='yp-button yp-save-btn'>".__('Save','yp')."</a>

			<a data-toggle='tooltipTopBottom' data-placement='bottom' title='".__('Reset Changes','yp')."' class='yp-button-reset'></a>

			<a target='_blank' data-href='".$liveLink."' data-toggle='tooltipTopBottom' data-placement='bottom' title='".__('Live Preview','yp')."' class='yp-button-live'></a>
				
			<div class='yp-clearfix'></div>

		</div>";
		
		// Set variables.
		$tag_id = null;
		$category_id = null;
		$last_post_id = null;
		$last_portfolio_id = null;
		$last_page_id = null;
		
		// Getting tags
		$tags = get_tags(array('orderby' => 'count', 'order' => 'DESC','number'=> 1 ));
		if(empty($tags) == false){
			$tag_id = $tags[0];
		}
		
		// Getting categories
		$categories = get_categories(array('orderby' => 'count', 'order' => 'DESC','number'=> 1 ));
		if(empty($categories) == false){
			$category_id = $categories[0];
		}
		
		// Set null to variables.
		$category_page = '';
		$homepage = '';
		$global_current_page = '';
		$global_current_page_url = '';
		$tag_page = '';
		$is_type = '';
		$is_id = '';
		$all_singles = '';
		$all_pages = '';
		$gb_category_active = false;
		$gb_tag_active = false;
		$editingHas = '0';
		$url = '';
		
		// Checking if its is a type
		if(isset($_GET['yp_type'])){
			$is_type = trim( strip_tags($_GET['yp_type']));
		}
		
		// Checking if its id.
		if(isset($_GET['yp_id'])){
			$is_id = intval($_GET['yp_id']);
		}
		
		// Getting current URL
		if(is_ssl()){
			$current_url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		}else{
			$current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		}

		$current_url = remove_query_arg("yp_out", $current_url);
	
		// Category Page
		if($category_id != '' && $category_id != null){
			
			$url = add_query_arg(array('href' => yp_urlencode(get_term_link($category_id))),$yellow_pencil_uri);
			
			$active = '';
			if($current_url == $url){
				$active = ' class="active" ';
				$editingHas = '1';
				$gb_category_active = true;
			}
			
			$category_page = '<li'.$active.'><a href="'.esc_url($url).'">'.__("Category Page","yp").'</a></li>';
			
		}

		// if is global, try to add current page to global section.
		if(isset($_GET['yp_id']) == false && isset($_GET['yp_type']) == false && $editingHas == 0){

			$postid = url_to_postid($hrefNew);

			if($postid != null){

				$url = add_query_arg(array('href' => yp_urlencode(get_permalink($postid))),$yellow_pencil_uri);

				$global_current_page = '<li class="active"><a data-toggle="tooltip" data-placement="left" title="'.yp_get_long_tooltip_title(get_the_title($postid)).'" href="'.esc_url($url).'">'.yp_get_short_title(get_the_title($postid)).'</a></li>';
				$global_current_page_url = esc_url($url);

			}else if($gb_category_active == false && $gb_tag_active == false){

				$global_current_page = '<li class="active"><a>Unknown Page</a></li>';
				$global_current_page_url = '';

			}

		}

		// tag Page
		if($tag_id != '' && $tag_id != null){
			
			$url = add_query_arg(array('href' => yp_urlencode(get_term_link($tag_id))),$yellow_pencil_uri);
			
			$active = '';
			if($current_url == $url){
				$active = ' class="active" ';
				$editingHas = '1';
				$gb_tag_active = true;
			}
			
			$tag_page = '<li'.$active.'><a href="'.esc_url($url).'">'.__("Tag Page","yp").'</a></li>';
			
		}
		
		// Home Page
		if($global_current_page_url != $url || $url == ''){
			$url = add_query_arg(array('href' => yp_urlencode(esc_url(get_home_url().'/'))),$yellow_pencil_uri);
				
			$active = '';
			if($current_url == $url){
				$active = ' class="active" ';
				$global_current_page = '';
				$editingHas = '1';
			}
				
			$homepage = '<li'.$active.'><a href="'.esc_url($url).'">'.__("Home","yp").'</a></li>';

		}

		
		// Getting pages with custom templates.
		$args = array(
			'posts_per_page' => 8,
			"post_status" => "publish",
		    'post_type' => 'page',
		    'meta_query' => array(
		        array(
		            'key' => '_wp_page_template',
		            'value' => 'default',
					'compare' => '!='
				)
			)
		);

		$other_pages = get_posts($args);
		$c = 1;
		$current_pages_id = array();
		foreach($other_pages as $page){

			$c++;

			array_push($current_pages_id, $page->ID);
			
			$url = add_query_arg(array('href' => yp_urlencode(get_permalink($page->ID)), 'yp_id' => $page->ID),$yellow_pencil_uri);
			
			$active = '';
			if($current_url == $url){
				$active = ' class="active" ';
				$editingHas = '1';
			}

			$title = $page->post_title;

			if($title == '' || $title == ' '){
				$title = 'Untitled';
			}
		
			$all_pages .= '<li'.$active.' id="page-'.esc_attr($page->ID).'-btn"><a data-toggle="tooltip" data-placement="left" title="'.yp_get_long_tooltip_title($title).'" href="'.esc_url($url).'">'.yp_get_short_title($title).'</a></li>';
			
		}

		// First get pages with templates,
		// if there not more 6 page templates,
		// so show normal pages.
		if($c < 6){
			// Getting all pages.
			$args = array(
				'posts_per_page' => (6-$c),
				"post_status" => "publish",
			    'post_type' => 'page',
			    'exclude' => $current_pages_id
			);

			$other_pages = get_posts($args);

			foreach($other_pages as $page){

				$url = add_query_arg(array('href' => yp_urlencode(get_permalink($page->ID)), 'yp_id' => $page->ID),$yellow_pencil_uri);
				
				$active = '';
				if($current_url == $url){
					$active = ' class="active" ';
					$editingHas = '1';
				}

				$title = $page->post_title;

				if($title == '' || $title == ' '){
					$title = 'Untitled';
				}
				
				$all_pages .= '<li'.$active.' id="page-'.esc_attr($page->ID).'-btn"><a data-toggle="tooltip" data-placement="left" title="'.yp_get_long_tooltip_title($title).'" href="'.esc_url($url).'">'.yp_get_short_title($title).'</a></li>';
				
			}

		}


		// Search Page.
		$url = add_query_arg(array('href' => yp_urlencode(esc_url(get_home_url()).'/?s='.yp_getting_last_post_title().'').'&yp_type=search'),$yellow_pencil_uri);
		$active = '';

		if($current_url == $url){
			$active = ' class="active" ';
			$editingHas = '1';
		}

		$all_singles .= '<li'.$active.' id="search-page-btn"><a href="'.esc_url($url).'">'.__("Search","yp").'</a></li>';


		// 404 Page.
		$url = add_query_arg(array('href' => yp_urlencode(esc_url(get_home_url()).'/?p=987654321').'&yp_type=404'),$yellow_pencil_uri);
		$active = '';

		if($current_url == $url){
			$active = ' class="active" ';
			$editingHas = '1';
		}

		$all_singles .= '<li'.$active.' id="error-page-btn"><a href="'.esc_url($url).'">404</a></li>';


		// tag Page.
		if($tag_id != '' && $tag_id != null){

			$url = add_query_arg(array('href' => yp_urlencode(esc_url(get_term_link($tag_id))).'&yp_type=tag'),$yellow_pencil_uri);
			$active = '';

			if($current_url == $url){
				$active = ' class="active" ';
				$editingHas = '1';
			}

			$all_singles .= '<li'.$active.' id="tag-page-btn"><a href="'.esc_url($url).'">'.__("Tag","yp").'</a></li>';

		}

		// Category Page.
		if($category_id != '' && $category_id != null){

			$url = add_query_arg(array('href' => yp_urlencode(esc_url(get_term_link($category_id))).'&yp_type=category'),$yellow_pencil_uri);
			$active = '';

			if($current_url == $url){
				$active = ' class="active" ';
				$editingHas = '1';
			}

			$all_singles .= '<li'.$active.' id="category-page-btn"><a href="'.esc_url($url).'">'.__("Category","yp").'</a></li>';

		}

		// Author Page.
		$url = add_query_arg(array('href' => yp_urlencode(esc_url(get_author_posts_url("1"))).'&yp_type=author'),$yellow_pencil_uri);
		$active = '';

		if($current_url == $url){
			$active = ' class="active" ';
			$editingHas = '1';
		}

		$all_singles .= '<li'.$active.' id="author-page-btn"><a href="'.esc_url($url).'">'.__("Author","yp").'</a></li>';


		// Home Page.
		$frontpage_id = get_option('page_on_front');

		if($frontpage_id == 0 || $frontpage_id == null){

			$url = add_query_arg(array('href' => yp_urlencode(esc_url(get_home_url().'/')).'&yp_type=home'),$yellow_pencil_uri);
			$active = '';

			if($current_url == $url){
				$active = ' class="active" ';
				$editingHas = '1';
			}

			$all_pages .= '<li'.$active.' id="page-page-home-btn"><a href="'.esc_url($url).'">'.__("Home Page","yp").'</a></li>';

		}

		$post_types = get_post_types(array(
		   'public'   => true,
		   '_builtin' => false
		));

		// Adding default post types.
		array_push($post_types, 'post');
		array_push($post_types, 'page');

		$pi = 0;
		foreach ($post_types as $post_type){

			$pi++;

				if($pi < 7){

				if($post_type == 'page'){
					$last_post = wp_get_recent_posts(array("post_status" => "publish","meta_key" => "_wp_page_template", "meta_value" => "default", "numberposts" => 1, "post_type" => $post_type));
				}else{
					$last_post = wp_get_recent_posts(array("post_status" => "publish","numberposts" => 1, "post_type" => $post_type));
				}

				if(empty($last_post) == false){

					$last_post_id = $last_post['0']['ID'];

					$url = add_query_arg(array('href' => yp_urlencode(get_permalink($last_post_id)), 'yp_type' => $post_type),$yellow_pencil_uri);

					$active = '';
					if(isset($_GET['yp_type'])){
						if(trim( strip_tags( $_GET['yp_type'] ) ) == $post_type){
						$active = ' class="active" ';
						$editingHas = '1';
						}
					}

					$postType = preg_replace('![_-]!s', " ", $post_type);
					$postType = ucfirst($postType);

					
					$all_singles .= '<li'.$active.' id="single-'.esc_attr($post_type).'-page-btn"><a data-toggle="tooltip" data-placement="left" title="'.yp_get_long_tooltip_title($postType).'" href="'.esc_url($url).'">'.yp_get_short_title($postType).'</a></li>';

				}

			}

		}
		
		// Show editing page on all pages list.
		if(isset($_GET['yp_id'])){
			if($editingHas == '0'){
				$url = add_query_arg(array('href' => yp_urlencode(get_permalink($is_id)), 'yp_id' => $is_id),$yellow_pencil_uri);

				$title = get_the_title($is_id);

				if($title == '' || $title == ' '){
					$title = 'Untitled';
				}

				$all_pages .= '<li class="active" id="page-'.$is_id.'-btn"><a data-toggle="tooltip" data-placement="left" title="'.yp_get_long_tooltip_title($title).'" href="'.esc_url($url).'">'.yp_get_short_title($title).'</a></li>';
			}
		}elseif(isset($_GET['yp_type'])){
			if($editingHas == '0'){

				// Getting last post for current post type.
				if($is_type == 'page'){
					$last_post = wp_get_recent_posts(array("post_status" => "publish","meta_key" => "_wp_page_template", "meta_value" => "default", "numberposts" => 1, "post_type" => $is_type));
				}else{
					$last_post = wp_get_recent_posts(array("post_status" => "publish","numberposts" => 1, "post_type" => $is_type));
				}

				if(empty($last_post) == false){
					$last_post_id = $last_post['0']['ID'];

					$url = add_query_arg(array('href' => yp_urlencode(get_permalink($last_post_id)), 'yp_type' => $is_type),$yellow_pencil_uri);

					$title = $is_type;

					if($title == '' || $title == ' '){
						$title = 'Untitled';
					}

					$all_singles .= '<li class="active" id="page-'.$last_post_id.'-btn"><a data-toggle="tooltip" data-placement="left" title="'.yp_get_long_tooltip_title($title).'" href="'.esc_url($url).'">'.yp_get_short_title($title).'</a></li>';
				}

			}
		}


		// Markup For Global Page Links etc.
		$other_pages = '<div class="yp-other-pages">
		<span data-toggle="popover" class="yp-start-info" title="'.__("Global Customize","yp").'" data-placement="left" data-content="'.__("Global changes will be loading on every page. Global customize ideal to edit 'Header', 'Footer', 'General Site Design' etc.","yp").'">'.__('Global Customize','yp').':</span>
		
		<ul class="yp-ul-global-list">'.$category_page.''.$homepage.''.$global_current_page.''.$tag_page.'</ul>';
		
		if($all_pages != '' && $all_pages != null){
		$other_pages .= '<span class="yp-other-other-pages yp-start-info" data-toggle="popover" title="'.__("Customize One Page","yp").'" data-placement="left" data-content="'.__("Use following links for apply changes to just one page.","yp").'">'.__('Customize One Page','yp').':</span>
		
		<ul class="yp-ul-all-pages-list">'.$all_pages.'</ul>'; }
		
		$other_pages .= '<span class="yp-start-info yp-other-other-pages" data-toggle="popover" title="'.__("Customize Templates","yp").'" data-placement="left" data-content="'.__("Use following links for edit Templates. Sample: 'all single posts', 'all product pages' etc.","yp").'">'.__('Customize Templates','yp').':</span>
		
		<ul class="yp-ul-single-list">'.$all_singles.'</ul></div>';
		
		$logoutTitle = 'Show as the visitor';
			$userImage = 'background-image:url(\''.get_avatar_url(get_current_user_id(),array('size' => '24'))."');";


		if(isset($_GET['yp_out'])){

			$logoutTitle = 'Show as the logged user';
			$userImage = '';
			$newLink = remove_query_arg("yp_out", $current_url);
		
		}else{

			$newLink = add_query_arg(array('yp_out' => 'true'),$current_url);
			
		}

		// Default.
		echo '<div class="yp-no-selected"><div class="yp-hand"></div><div class="yp-hand-after"></div>'.__('Click on any element that you want to customize!','yp').' '.$other_pages.'<div class="yp-tip"><span class="dashicons dashicons-arrow-right"></span> '.__("Press to H key for hiding plugin panel.","yp").'</div><a href="'.$newLink.'" data-toggle="tooltip" data-placement="left" title="'.$logoutTitle.'" class="yp-logout-btn" style="'.$userImage.'"></a></div>';
		
		
		// Options
		include( WT_PLUGIN_DIR . 'options.php' );
		
		
	echo "</div>";
	
}