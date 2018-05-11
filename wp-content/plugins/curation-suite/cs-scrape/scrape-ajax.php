<?php 

// http://localhost/scoop.php
// http://www.newswhip.com/World
// http://www.scoop.it/t/marketing-buzz
// http://www.scoop.it/t/google-project-vs-facebook
function ybi_load_url_title(){

	$url = trim($_POST['url']);
	if(!function_exists('file_get_html'))
		require_once(YBI_CURATION_SUITE_PATH ."lib/web/simple_html_dom.php");
				// create a simple html dom file
	$html = new simple_html_dom();


	require_once(YBI_CURATION_SUITE_PATH ."lib/web/http.php");
	require_once(YBI_CURATION_SUITE_PATH ."lib/web/web_browser.php");
	$web = new WebBrowser();
	$result = $web->Process($url);
	if($result['success'])
		$html->load($result["body"]);

	//	$html->load_file($url);
//$title = 'no title';
	if($title == '')
		$title = $html->find('title', 0)->innertext;

	echo json_encode(array('title' => $title) );

	die();
}
add_action('wp_ajax_ybi_load_url_title', 'ybi_load_url_title');


function ybi_toggle_scraping_feature(){

	$result = '';
	if(	get_option('curation_suite_scraping_feature'))
		delete_option('curation_suite_scraping_feature');
	else
		update_option( 'curation_suite_scraping_feature', 1 );

	echo json_encode(array('result' => $result) );

	die();
}
add_action('wp_ajax_ybi_toggle_scraping_feature', 'ybi_toggle_scraping_feature');



function ybi_source_action(){
	$current_action = trim($_POST['current_action']);
	$url = trim($_POST['url']);
	$source_type = trim($_POST['source_type']);
	$scrape_name = trim($_POST['scrape_name']);
	$inKey = trim($_POST['key']);

	$all_sources = get_option( 'cs_scrape_sources' );
	if($current_action == 'new')
	{
		if(!empty($all_sources))
		{
			$key = $source_type . '--' . $url;
			$source = array('key' => $key, 'source' => $source_type, 'title' => $scrape_name, 'url' => $url);
			$all_sources[] = $source;
		}
		else
		{
			$all_sources = array($source);
		}
		
		update_option( 'cs_scrape_sources', $all_sources);
	}
	else
	{
		$updated_sources = array();
		foreach($all_sources as $single_source) {
			$key = $single_source['source'] . '--' . $single_source['url'];
	
			if($inKey == $key && $current_action == 'delete')
				continue;
	
			if($inKey == $key && $current_action == 'edit')
			{
				$source = array('key' => $key, 'source' => $source_type, 'title' => $scrape_name, 'url' => $url);
			}
			else
				$source = array('key' => $key, 'source' => $single_source['source'] , 'title' => $single_source['title'], 'url' => $single_source['url']);
			
			$updated_sources[] = $source;
		}
		update_option( 'cs_scrape_sources', $updated_sources);
	}

	$status = 'added' . $source_type;
	
	echo json_encode(array('status' => $status) );

	die();
}
add_action('wp_ajax_ybi_source_action', 'ybi_source_action');

function ybi_load_scrape_sources(){

 $i = 0;
 $alternate = '';
 
	$html .= '<table class="wp-list-table widefat fixed posts" cellspacing="0"><thead>
 <tr>
 	<th class="name_col">Name/Title</th>
 	<th class="source_col">Source</th>
 	<th class="url_col">URL</th>
 	<th class="actions_col">Actions</th>
 </tr>
 </thead><tbody>';
	$all_sources = get_option( 'cs_scrape_sources' );
	foreach($all_sources as $single_source) {
		$key = $single_source['source'] . '--' . $single_source['url'];
		$alternate = '';
		if(0 != $i % 2): $alternate = ' alternate'; endif;
		$html .= '<tr class="type-post status-publish format-standard hentry' . $alternate .'">
		<td>'.stripslashes($single_source['title']) . '</td>
		<td>'.$single_source['source'] . '</td>
		<td><a href="'.$single_source['url'].'" target="_blank">'.$single_source['url'] . '</a></td>
		<td><a href="javascript:;" class="edit" rel="'.$key.'">edit</a> | <a href="javascript:;" class="delete" rel="'.$key.'">delete</a></td>
		</tr>';
		$i++;

	}
	$html .= '</tbody></table>';
	echo json_encode(array('html' => $html) );

	die();
}
add_action('wp_ajax_ybi_load_scrape_sources', 'ybi_load_scrape_sources');

function ybi_load_scrape_single_source(){

	$inKey = trim($_POST['key']); 
	$all_sources = get_option( 'cs_scrape_sources' );
	$source = '';
	$url = '';
	$title = '';
	foreach($all_sources as $single_source) {
		$key = $single_source['source'] . '--' . $single_source['url'];
		if($inKey == $key)
		{
			$source = $single_source['source'];
			$url = $single_source['url'];
			$title = $single_source['title'];
			break;	
		}
	}

	echo json_encode(array('key' => $inKey, 'source' => $source, 'url' => $url,'title' => $title,) );

	die();
}
add_action('wp_ajax_ybi_load_scrape_single_source', 'ybi_load_scrape_single_source');



?>