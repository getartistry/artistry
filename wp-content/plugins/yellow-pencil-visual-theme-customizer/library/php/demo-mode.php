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

// to load editor for visitors on demo mode
add_action("template_redirect", 'yp_theme_redirect');

function yp_theme_redirect(){

    global $wp;

	if(defined('YP_DEMO_MODE') && isset($_GET['yellow_pencil']) == true){
		$yellow_pencil = WT_PLUGIN_DIR . '/library/php/frame.php';
		yp_do_theme_redirect($yellow_pencil);
	}
	
}


// to load customize type iframe for visitors on demo mode
add_action("template_redirect", 'yp_theme_redirect2');

function yp_theme_redirect2(){

    global $wp;

	if(defined('YP_DEMO_MODE') && isset($_GET['yp_customize_type']) == true){
		$yellow_pencil = WT_PLUGIN_DIR . '/library/php/popup.php';
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

	// get data
    $data = yp_get_page_ids();

    // Getting page informations
    $page_id = $data[0];
    $page_type = $data[1];
    $edit_mode = $data[2];

    // URL OF Editor
    $yellow_pencil_uri = yp_get_uri();

    // Getting current page
    $href = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    $edit_link = add_query_arg(array(
        'href' => yp_urlencode($href),
        'yp_page_id' => $page_id,
        'yp_page_type' => $page_type,
        'yp_mode' => $edit_mode
    ),$yellow_pencil_uri);

	echo '<a href="'.$edit_link.'" class="yp-demo-link yp-live-editor-link">Live Editor</a>';

}

add_action("wp_footer","yp_demo_editor_footer");