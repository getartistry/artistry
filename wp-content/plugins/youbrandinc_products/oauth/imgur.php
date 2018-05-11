<?php
/**
 * Created by PhpStorm.
 * User: Scott-CM
 * Date: 12/14/2016
 * Time: 11:27 AM
 */
$service = isset($_GET['service']) ? $_GET['service'] : null;
$token = isset($_GET['token']) ? $_GET['token'] : null;
$code = isset($_GET['code']) ? $_GET['code'] : null;
$client = new \Imgur\Client();
$client->setOption('client_id', 'e07565ce853e1e9');
$client->setOption('client_secret', '12433439b84bbbd114998238109a660010229e80');
if (!$imgur_access_data) {
    //$action_link = '<a href="' . $currentUri->getRelativeUri() . '?page=youbrandinc-oauth&service=ImgUr&step=1">Connect ImgUr Account</a>';
    //$action_link= '<a href="'.$client->getAuthenticationUrl().'">Click to authorize</a>';
    $reset_link = admin_url('admin.php?page=youbrandinc-oauth&service=ImgUr');
    //$action_link= '<a href="http://localhost/listening-platform/oauth/oauth-control.php?service=ImgUr&site_url='.urlencode($reset_link).'">Click to authorize</a>';
    $action_link= '<a href="https://curationwp.com/oauth/oauth-control.php?service=ImgUr&site_url='.urlencode($reset_link).'">Click to authorize</a>';
}

if($service=='ImgUr') {
/*    if (isset($_SESSION['token'])) {
        $client->setAccessToken($_SESSION['token']);

        if ($client->checkAccessTokenExpired()) {
            $client->refreshToken();
        }
    }*/
    if (isset($_GET['code'])) {
        $client->requestAccessToken($_GET['code']);
        $_SESSION['token'] = $client->getAccessToken();
        $imgur_access_data = $client->getAccessToken();
        update_option('cs_ImgUr_access_data', $imgur_access_data);
        $connected_text = '<i class="fa fa-circle good" aria-hidden="true"></i> Connected: <i>' . $imgur_access_data['account_username'] . '</i>';
        $reset_link = admin_url('admin.php?page=youbrandinc-oauth&service_reset=ImgUr');
        $pocket_reset_link = '<a href="'.$reset_link.'">Delete/Reset Connection</a>';
        $action_link = $pocket_reset_link;
        ybi_print_jquery_notice_message($service . ' Connected', 'updated');
    }
}