<?php
/**
 * Demo Mode Fuctions
 *
 * @author 		WaspThemes
 * @category 	Core
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

//Template fallback
add_action("template_redirect", 'yp_theme_redirect');

function yp_theme_redirect(){

    global $wp;

	if(defined('YP_DEMO_MODE') && isset($_GET['yellow_pencil']) == true){
		$n = 'frame.php';
		$yellow_pencil = dirname( __FILE__ ) . '/' . $n;
		yp_do_theme_redirect($yellow_pencil);
	}
	
}


function yp_do_theme_redirect($url) {

	global $post, $wp_query;
	
	if (have_posts()) {
	
		include($url);
		die();
		
	}else{
	
		$wp_query->is_404 = true;
		
	}
	
}

function get_yp_editor_link(){
	
	if(isset($_GET['page_id'])){
		$id = intval($_GET['page_id']);
	}elseif(isset($_GET['post']) && is_admin() == true){
		$id = intval($_GET['post']);
	}else{
		$id = get_queried_object_id();
	}

	if($id === 0 || $id === null || is_tag() || is_category() || is_archive() || is_author() || is_search() || is_404()){
		$href = add_query_arg(array('yellow_pencil' => 'true', 'href' => yp_urlencode(get_home_url().'/')),get_home_url());
	}else{
		$href = add_query_arg(array('yellow_pencil' => 'true','href' => yp_urlencode(get_permalink($id)),'yp_id' =>  $id),get_home_url());
	}

	return $href;

}

function yp_demo_editor_header(){

	echo '<style>
	.yp-demo-link{
		font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
		text-transform: uppercase;
		position:fixed;
		top:18%;
		left:0px;
		width:auto;
		z-index:9999999;
	    padding: 9px 11px !important;
	    border-radius: 0px 3px 3px 0px !important;
	    font-size: 14px !important;
	    font-weight: 600 !important;
	    background: #27AE60 !important;
	    color: #FFF !important;
	    border-width:0 !important;
	    text-transform: none !important;
	    
	    -webkit-transition: background-color 250ms ease;
	    -moz-transition: background-color 250ms ease;
	    -ms-transition: background-color 250ms ease;
	    -o-transition: background-color 250ms ease;
	    transition: background-color 250ms ease;
	    text-decoration: none !important;
	}

	.yp-demo-link:hover{
	    background-color:#2ABC67 !important;   
	}

	body.yp-yellow-pencil .theme-demo-options{display:none !important;}@media(max-width:992px){.yp-demo-link{display:none !important;}}</style>';

}

add_action("wp_head","yp_demo_editor_header");


function yp_demo_editor_footer(){

	echo '<a href="'.get_yp_editor_link().'" class="yp-demo-link yp-live-editor-link">Live Editor</a>';

}

add_action("wp_footer","yp_demo_editor_footer");