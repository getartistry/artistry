<?php 

$app_id = "1759663597660762";
$app_secret = "c846a34f2176da884d602e1cf73f6e58";
$redirect = "http://deandev.com/fb/token.php";

//verify code exist
if(! isset($_GET['code'])){
	echo ' Can not find any returned code ';
	exit;
}else{
	$code = trim($_GET['code']);
}

//issue request to get the shortlived accesstoken
//curl ini
$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER,0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT,20);
curl_setopt($ch, CURLOPT_REFERER, 'http://www.bing.com/');
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8');
curl_setopt($ch, CURLOPT_MAXREDIRS, 5); // Good leeway for redirections.
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // Many login forms redirect at least once.
curl_setopt($ch, CURLOPT_COOKIEJAR , "cookie.txt");

//curl get
$x='error';
$url= "https://graph.facebook.com/v3.0/oauth/access_token?client_id=$app_id&redirect_uri=$redirect&client_secret=$app_secret&code=$code";
curl_setopt($ch, CURLOPT_HTTPGET, 1);
curl_setopt($ch, CURLOPT_URL, trim($url));
$exec=curl_exec($ch);
$x=curl_error($ch);

if( ! stristr($exec, 'access_token')){
	echo ' Can not find access token #1 when exchanging the provided code';
	exit;
}

$json = json_decode($exec);
$access_token = $json->access_token;

if(trim($access_token) == ''){
	echo ' empty token #1 ';
	exit;
}

// long lived token 
//curl get
$x='error';
$url="https://graph.facebook.com/v3.0/oauth/access_token?grant_type=fb_exchange_token&client_id=$app_id&client_secret=$app_secret&fb_exchange_token=$access_token";
curl_setopt($ch, CURLOPT_HTTPGET, 1);
curl_setopt($ch, CURLOPT_URL, trim($url));
$exec=curl_exec($ch);
$x=curl_error($ch);

if( ! stristr($exec, 'access_token')){
	echo ' Can not find access token #2 when exchanging the provided code';
	exit;
}

$json = json_decode($exec);
$access_token = $json->access_token;

if(trim($access_token) == ''){
	echo ' empty token #2 ';
	exit;
}

echo '<center><h2>Success, Please copy the access token below to your settings page</h2>';
echo '<br><br><br><textarea style ="width:400px;height:50px">'.$access_token.'</textarea></center>';


 

echo '<pre>';
print_r($json);
exit;
echo $exec;
exit;

echo htmlentities('welcome omigo');
exit;
$text = file_get_contents('test.txt');

preg_match_all('{\s*\[.*?\]\s*}', $text,$pre_tags_matches_s);
$pre_tags_matches_s = ($pre_tags_matches_s[0]);

echo '<pre>';
print_r($pre_tags_matches_s);

exit;

 
require_once 'inc/class.dom.php';

$wpAutomaticDom = new wpAutomaticDom(file_get_contents('test.txt'));

 

//$regexMatchs = $wpAutomaticDom->getContentByXPath("//*[@class='user-html']");
//$regexMatchs = $wpAutomaticDom->getContentByXPath('//table[contains(concat (" ", normalize-space(@class), " "), " outer ")]/tbody/tr[2]/td/table/tbody/tr/td[1]');
//$regexMatchs = $wpAutomaticDom->getContentByXPath('/html/body/table/tr/td');
//$regexMatchs = $wpAutomaticDom->getContentByXPath('//table');
//$regexMatchs = $wpAutomaticDom->getContentByXPath('/html/body/table/tr[2]/td/table/tr/td[1]/table/tr[1]/td/table');
//$regexMatchs = $wpAutomaticDom->getContentByXPath('/html/body/table/tbody/tr/td[3]/font[2]',false);
//$regexMatchs = $wpAutomaticDom->getContentByXPath('//*[@id="selDistrict1"]/div[3]/a' , false);

$regexMatchs = $wpAutomaticDom->getContentByClass('subbuzz',false);



////html/body/table/tbody/tr[2]/td/table/tbody/tr/td[1]

print_r($regexMatchs);


?>