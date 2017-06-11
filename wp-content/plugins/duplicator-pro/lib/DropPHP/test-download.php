<?php 
require_once dirname(C9DAT_DIR) . '/duplicator-pro/lib/DropPHP/DropboxV2Client.php';
require_once dirname(C9DAT_DIR) . '/duplicator-pro/lib/DropPHP/DropboxClient.php';

$configuration['app_key']=get_option('c9dat_app_key');
$configuration['app_secret']=get_option('c9dat_app_secret');
$configuration['v2_access_token']=get_option('c9dat_v2_access_token');

$dropbox = new DUP_PRO_DropboxV2Client($configuration,'en', $use_curl);
$dropbox->SetAccessToken($configuration);

$data=$dropbox->GetAccountInfo();
$actual=array('name'=>$data->name->display_name,'email'=>$data->email);
$expected=array('name'=>'nice cool','email'=>'opensoftcoder@gmail.com');
echo c9t_is_passed_or_failed($actual,$expected,'GetAccountInfo');


$folder='sandbox.cms90.com';
$data=$dropbox->GetMetadata($folder);
$actual=array('path_display'=>'/sandbox.cms90.com');
$expected=array('path_display'=>$data->path_display);
echo c9t_is_passed_or_failed($actual,$expected,'GetMetadata');

$dst='sandbox.cms90.com/test4/robots4.txt';
$src='/sandbox/wp-content/backups-dup-pro/robots.txt';
$data=$dropbox->UploadFile($src,$dst);
$actual=array('path_display'=>'/sandbox.cms90.com');
$expected=$data;//array('path_display'=>$data->path_display);
echo c9t_is_passed_or_failed($actual,$expected,'UploadFile');

