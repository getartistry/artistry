<?php

function detect_search_engines($useragent)
{
	$searchengines = array(
		'Googlebot', 
		'Slurp', 
		'search.msn.com', 
		'nutch', 
		'simpy', 
		'bot', 
		'ASPSeek', 
		'crawler', 
		'msnbot', 
		'Libwww-perl', 
		'FAST', 
		'Baidu', 
		);
	$is_se = false;
	foreach ($searchengines as $searchengine){
	   if (!empty($_SERVER['HTTP_USER_AGENT']) and 
				false !== strpos(strtolower($useragent), strtolower($searchengine)))
		{
				$is_se = true;
				break;
		}
	}
	if ($is_se) { return true; }
	else
		return false;

}
?>