<?php
//$url = ((!empty($_SERVER['HTTPS'])) ? "https://": "http://" ) . $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
function test_cqs()
{
	$apikey = "30e98d708b35fab928465c0f070c33be4d1d220c";
	$json = file_get_contents("http://free.sharedcount.com/?url=" . rawurlencode($url) . "&apikey=" . $apikey);
	$counts = json_decode($json, true);
	var_dump($counts);
	echo "This page has " . $counts["Twitter"] ." tweets, " . $counts["Facebook"]["like_count"] . " likes, and ". $counts["GooglePlusOne"] . "+1's";
}

/*
 * This function will return a score value based on the network and share count passed
 *
 * @since 1.2 Curation Suite
 *
 * @param int $inShareCount (required) total number of shares
 * @param string $inNetwork (required) the social network attached to this share count
 * @return int|a number 1-4 with 1 being the lowest score that will be returned... unless network is not found then it would be 0
 */
function ybi_share_network_score($inShareCount, $inNetwork)
{
	$retScore = 0;
	// all these could be modified in each case statement down below for weighting, right now they are the same.
	$red_high = 5; // anything less would be red
	$yellow_high = 6; // anything below this and red would be yello
	$green_low = 11; // anything above this would be green
	$bonus = 100; // if this threshhold is met then we give bonus points
	switch ($inNetwork) {
		case 'facebook':
			if ( $inShareCount >= $bonus ) {
					$retScore = 4;
				 } elseif ( $inShareCount >= $green_low ) {
					$retScore = 3; 
				 } 
					elseif ( $inShareCount >= $yellow_high ) {
					$retScore = 2; 
				 } else {
					$retScore = 1;
				 }
		break;	
		case 'twitter':
			if ( $inShareCount >= $bonus ) {
					$retScore = 4;
				 } elseif ( $inShareCount >= $green_low ) {
					$retScore = 3; 
				 } 
					elseif ( $inShareCount >= $yellow_high ) {
					$retScore = 2; 
				 } else {
					$retScore = 1;
				 }	
		break;
		case 'linkedin':
			if ( $inShareCount >= $bonus ) {
					$retScore = 4;
				 } elseif ( $inShareCount >= $green_low ) {
					$retScore = 3; 
				 } 
					elseif ( $inShareCount >= $yellow_high ) {
					$retScore = 2; 
				 } else {
					$retScore = 1;
				 }	
				 break;	
		case 'googleplus':
			if ( $inShareCount >= $bonus ) {
					$retScore = 4;
				 } elseif ( $inShareCount >= $green_low ) {
					$retScore = 3; 
				 } 
					elseif ( $inShareCount >= $yellow_high ) {
					$retScore = 2; 
				 } else {
					$retScore = 1;
				 }
				 break;
	}
	return $retScore;	
}
// this function will give an additional score based a channels parameters
function getChannelScore($details)
{
	$retScore = 0;
	$channel = $details['channel'];
		
	switch ($channel) 
	{
		case 'youtube':
			$views = $details['views']; // here we set a views return score
			if($views == '')
				$retScore = 0;
			elseif ($views <= 50)
				$retScore = 1;
			elseif($views <= 100)
				$retScore = 2;
			else
				$retScore = 3;
		break;
		case 'slideshare':
			$views = $details['views']; // same here for slideshare
			if($views == '')
				$retScore = 0;
			elseif ($views <= 50)
				$retScore = 1;
			elseif($views <= 100)
				$retScore = 2;
			else
				$retScore = 3;
		break;
		case 'googlenews':
			$retScore = 2; // we give a 2 because we don't assume GoogleNews would include bad search results here
		break;
		case 'googlenblog':
			$retScore = 1; // we give 1 because content from this source isn't as trusted as google news
		break;
		case 'bingnews':
			$retScore = 1; // bing get's a bump of 1
		break;
		case 'yahoonews':
			$retScore = 1; // Yahoo News get's a bump of 1
		break;
		case 'fromtoolssection':
			$retScore = 1; // our tools get's a bump of 1
		break;
		case 'fromlinkbuckets':
			$retScore = 1; // link buckets get's a bump of 1
		break;

	}

	return $retScore;	
}

function getOtherDataScore($detailsArr)
{
	$retScore = 0;
	if($detailsArr['pubdate'])
		return 1; // if there is a pubdate we give a bump in score. We should improve this logic here but right now we keep it simple
	
	return $retScore;	
}

/*
 * Very minimal function that returns CSS class to be used for display of scoring of content URL
 *
 * @since 1.4 Curation Suite
 *
 * @param string $url (required) The URL to be analyzed
 * @return string|a css representation of what the score is [cqs_red, yellow, green]
 */

function ybi_cs_get_CQs($inURL, $details)
{
	$apikey = "30e98d708b35fab928465c0f070c33be4d1d220c";
	$json = file_get_contents("http://free.sharedcount.com/?url=" . rawurlencode($inURL) . "&apikey=" . $apikey);
	$counts = json_decode($json, true);
	$detailsArr = array();
	parse_str($details, $detailsArr); // this is a string of values passed to this function, these are set on the front end
	
	// facebook
	$facebook_score_raw = $counts["Facebook"]["total_count"];
	$facebook_score = ybi_share_network_score($facebook_score_raw, 'facebook');
	// twitter
	$twitter_score_raw = $counts["Twitter"];
	$twitter_score = ybi_share_network_score($twitter_score_raw, 'twitter');
	// google+
	$googleplus_score_raw = $counts["GooglePlusOne"];
	$googleplus_score = ybi_share_network_score($googleplus_score_raw, 'googleplus');


	$CQ_Score = $facebook_score + $twitter_score + $googleplus_score;
	
	// this string is added to the return in the form of CSS classes, this way we can see the values, when it goes live we might want to remove this or just append a blank string.
	$data = ' CQS-'.$CQ_Score. ' ChanScore-'.getChannelScore($detailsArr) . ' OD-'. getOtherDataScore($detailsArr) . ' FB-'.$facebook_score_raw . ' TW-'.$twitter_score_raw;

	// since we don't want to be too harsh because our logic is simple if the score is below 3 we provide a way to bump the score if available.
	if($CQ_Score <= 3)
	{
		if (!empty($detailsArr))
		{
			$channelScore = getChannelScore($detailsArr);
			$CQ_Score = $CQ_Score + $channelScore;

			$otherScores = getOtherDataScore($detailsArr);
			$CQ_Score = $CQ_Score + $otherScores;
		}
	}
	
	if($CQ_Score <= 3)
		return 'cqs_red'.$data;
	if($CQ_Score <= 4)
		return 'cqs_yellow'.$data;
	if($CQ_Score >= 6)
		return 'cqs_green'.$data;
	
	return 'cqs_yellow no-score'.$data;
}
?>