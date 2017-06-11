<?php
/** 
 * DropPHP sample
 *
 * http://fabi.me/en/php-projects/dropphp-dropbox-api-client/
 *
 * @author     Fabian Schlieper <fabian@fabi.me>
 * @copyright  Fabian Schlieper 2012
 * @version    1.1
 * @license    See license.txt
 *
 */
 

// these 2 lines are just to enable error reporting and disable output buffering (don't include this in you application!)
error_reporting(E_ALL);
enable_implicit_flush();
// -- end of unneeded stuff

// if there are many files in your Dropbox it can take some time, so disable the max. execution time
set_time_limit(0);

require_once(__DIR__ . "/DropboxClient.php");

/* @var $dropbox DropboxClient */
// you have to create an app at https://www.dropbox.com/developers/apps and enter details below:
$dropbox = new DUP_PRO_DropboxClient(array(
	'app_key' => "oowt2lf7zmfb2z2", 
	'app_secret' => "vi27hg6i2whkgx1",
	'app_full_access' => false,
),'en');


// first try to load existing access token
$access_token = load_token("access");
if(!empty($access_token)) {
	$dropbox->SetAccessToken($access_token);
	echo "loaded access token:";
	print_r($access_token);
}
elseif(!empty($_GET['auth_callback'])) // are we coming from dropbox's auth page?
{
	// then load our previosly created request token
	$request_token = load_token($_GET['oauth_token']);
	if(empty($request_token)) die('Request token not found!');
	
	// get & store access token, the request token is not needed anymore
	$access_token = $dropbox->GetAccessToken($request_token);	
	store_token($access_token, "access");
	delete_token($_GET['oauth_token']);
}

// checks if access token is required
if(!$dropbox->IsAuthorized())
{
	// redirect user to dropbox auth page
	$return_url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?auth_callback=1";
	$auth_url = $dropbox->BuildAuthorizeUrl($return_url);
	$request_token = $dropbox->GetRequestToken();
	store_token($request_token, $request_token['t']);
	die("Authentication required. <a href='$auth_url'>Click here.</a>");
}

echo "<pre>";
echo "<b>Account:</b>\r\n";
print_r($dropbox->GetAccountInfo());

$keep_going = true;
$current_offset = 0;
$upload_id = null;
$src_file = __DIR__ . '/HexkitSource.zip';

while($keep_going)
{   
    echo "<p>Uploading $current_offset of $src_file</p>";
    
    /* @var $upload_info DUP_PRO_DropboxClient_UploadInfo */
    $upload_info = $dropbox->upload_file_chunk($src_file, $dropbox_path = '', 500000, 15, $current_offset, $upload_id);
    
    sleep(5);
    $current_offset = $upload_info->next_offset;
    $upload_id = $upload_info->upload_id;
    
    $keep_going = ($upload_info->file_meta == null);
    echo "<p>Keep going=$keep_going</p>";
}

echo '<p>Done upload!</p>';


function store_token($token, $name)
{
	if(!file_put_contents("tokens/$name.token", serialize($token)))
		die('<br />Could not store token! <b>Make sure that the directory `tokens` exists and is writable!</b>');
}

function load_token($name)
{
	if(!file_exists("tokens/$name.token")) return null;
	return @unserialize(@file_get_contents("tokens/$name.token"));
}

function delete_token($name)
{
	@unlink("tokens/$name.token");
}





function enable_implicit_flush()
{
	@apache_setenv('no-gzip', 1);
	@ini_set('zlib.output_compression', 0);
	@ini_set('implicit_flush', 1);
	for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
	ob_implicit_flush(1);
	echo "<!-- ".str_repeat(' ', 2000)." -->";
}
?>