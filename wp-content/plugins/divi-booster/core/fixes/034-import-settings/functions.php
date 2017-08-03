<?php
if (!defined('ABSPATH')) { exit(); } // No direct access

/* Ensure gzdecode exists */
if (!function_exists("gzdecode")) {
	function gzdecode($data) { return gzinflate(substr($data,10,-8)); } 
}

//Сheck for settings file import attempt
if((!empty($_FILES["uploaded_file"])) && ($_FILES['uploaded_file']['error'] == 0)) {
	
	// load wordpress and check user is allowed access
	if ($this->slug!='wtfdivi') { wp_die(__("You do not have permission to access this page.")); }
	
	$filename = basename($_FILES['uploaded_file']['name']);
	$ext = substr($filename, strrpos($filename, '.') + 1);
	
	//Check if the file is plaintext and its size is less than 1Mb
	if (($ext == "conf") && ($_FILES["uploaded_file"]["type"] == "application/octet-stream") && ($_FILES["uploaded_file"]["size"] < 1000000)) {

		$newname = get_temp_dir().'wtfdivi-tmp.conf';
		  
		// delete any previous upload
		if (file_exists($newname)) { unlink($newname); }
		
		//Attempt to move the uploaded file to its new place
		if ((move_uploaded_file($_FILES['uploaded_file']['tmp_name'],$newname))) {
	
			// uploaded successfully
			$newoption = unserialize(gzdecode(file_get_contents($newname)));
			update_option('wtfdivi', $newoption);
			$page = ($this->config['plugin']['admin_menu']=='themes.php'?'themes.php':'admin.php');
			header('Location: '.admin_url($page.'?page=wtfdivi_settings&settings-updated=true'));
			exit;
			
		} else {
		   wp_die("A problem occurred during file upload. Please try again.");
		}
			
	} else {
		wp_die("File is not recognized as a valid settings file"); // too big or wrong mime type
	}
} 
?>