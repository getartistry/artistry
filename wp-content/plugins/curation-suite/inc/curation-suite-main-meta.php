<?php 
// this file exists so we can check IonCube, if it wasn't here this file would throw an error
if (function_exists('ybi_checkPHPVersionGood')):
	// we check to see if we load a link.
	//$loadLink = isset($_GET['u']) ? esc_url($_GET['u']) : '';
	// include the main meta file
	//include_once( YBI_CURATION_SUITE_PATH . 'inc/main-meta-worker.php' );
else: //if (function_exists('ybi_isIoncubeInstalled')) ?>
<p>To use the Curation Suite you have install and activate the <strong>You Brand, Inc. License Plugin</strong> <a href="http://members.youbrandinc.com/dashboard/getting-started/license-keys/">visit You Brand, Inc. Members Area to Download</a>.</p>
<?php endif; //if (function_exists('ybi_isIoncubeInstalled')) ?>
<div id='B' class="curation_suite_side_panel_control"><a href='javascript:' id='toggle'>Toggle</a></div>