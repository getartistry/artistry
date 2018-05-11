<div class="wrap">
<div>
    <div class="products_header" style="background: url(<?php echo plugins_url('youbrandinc_products/i/you-brand-guys-32.png');?>); background-repeat: no-repeat; background-position: 0px 7px;">
		 <h3><?php echo _e('You Brand, Inc. Analytics and Sharing Module'); ?></h3>
         <div style="clear: both; overflow:auto; margin: 0 auto;"></div>
		<p>
			Please note: the view stat is a not an intelligent view stat. It simply measures the view of that piece of content. Most pageview stats in tools like Google Analytics, Clicky or other analytic tools sift out bots, your own visits, and spam visits.
			Since this is a relatively minor feature we built it simple not to add additional load or processing on your site. While this does make it less useful as a reliable stat it does measure the content that has the most visits/views on your site from all sources.</p>
    </div>
    <div id="analytics_shares_wrapper">
<?php 
function bit_ly_short_url($url, $format='txt') {
	$login = "your-bitly-login";
	$appkey = "your-bitly-application-key";
	$bitly_api = 'http://api.bit.ly/v3/shorten?login='.$login.'&apiKey='.$appkey.'&uri='.urlencode($url).'&format='.$format;
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$bitly_api);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}    

function gpi_find_image_id($post_id) {
    if (!$img_id = get_post_thumbnail_id ($post_id)) {
        $attachments = get_children(array(
            'post_parent' => $post_id,
            'post_type' => 'attachment',
            'numberposts' => 1,
            'post_mime_type' => 'image'
        ));
        if (is_array($attachments)) foreach ($attachments as $a)
            $img_id = $a->ID;
    }
    if ($img_id)
        return $img_id;
    return false;
}

function find_img_src($post) {
    if (!$img = gpi_find_image_id($post->ID))
        if ($img = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches))
            $img = $matches[1][0];
    if (is_int($img)) {
        $img = wp_get_attachment_image_src($img);
        $img = $img[0];
    }
    return $img;
}

	$isPostRecentView= false;
	$isPostRecentView = ($_GET['postorder'] == 'postrecent');
?>
<div style="clear: both;">
<?php if($isPostRecentView) { ?>
	<h4>Showing Posts in Latest Order - <a href="admin.php?page=youbrandinc-analytic-share">show most popular</a></h4>
<?php } else { ?>
	<h4>Showing Most Popular by Views - <a href="admin.php?page=youbrandinc-analytic-share&postorder=postrecent">show latest posts</a></h4>
<?php } ?>
</div>
<div class="list_w">
    <div class="row title-row">
        <div class="column post_title_ybi title">Post Title (click to view)</div>
        <div class="column post_views_ybi title">Views</div>
        <div class="column post_social_quotes title">Social Quotes<br />
<a href="http://SocialQuoteTraffic.com" target="_blank" style="font-size: 11px;">what's this?</a></div>
        <div class="column post_share_ybi title">Share</div>
    </div>

<?php   

	$currentYBIMULTIPLIER = YBIMULTIPLIER;
	if($_GET['ybim'] <> '')
		$currentYBIMULTIPLIER = $_GET['ybim'];
	
	global $wpdb;
	//SELECT * FROM wp_postmeta WHERE meta_key = 'post_views_count' ORDER BY meta_value DESC
	if($isPostRecentView)
		$theSQL = "SELECT * FROM " . $wpdb->prefix . "posts WHERE post_status ='publish' and post_type = 'post' ORDER BY post_date DESC LIMIT 15";			
	else
		$theSQL = "SELECT * FROM " . $wpdb->prefix . "postmeta WHERE meta_key = 'post_views_count' ORDER BY meta_value * 1 DESC LIMIT 15";

			//echo $theSQL;
    $theResults=$wpdb->get_results($theSQL);
	$numRows = $wpdb->num_rows;
	$row = 1;
	foreach ($theResults as $aResult)
	{
		//var_dump($aResult);
		if($row&1)
			$addCSS = ' alt';
		else
			$addCSS = '';
	
		echo '<div class="row'. $addCSS . '">';
		
		//$thePost = get_post($aResult->post_id);


		if($isPostRecentView)
		{
			$thePost = get_post($aResult->ID);
			$theViews = get_post_meta($aResult->ID, 'post_views_count', true);
			$theURL = get_permalink($aResult->ID);
			$aPost = get_post_field('post_content', $aResult->ID);
			$howManyQuotes = substr_count($aPost,'[social_quote');
		}
		else
		{
			$thePost = get_post($aResult->post_id);	
			$theViews = $aResult->meta_value;		
			$theURL = get_permalink($aResult->post_id);
			$aPost = get_post_field('post_content', $aResult->post_id);
			$howManyQuotes = substr_count($aPost,'[social_quote');
			if($howManyQuotes == 0)
				$howManyQuotes =  '<span class="zero_social_quotes">0</span>';
			else
		        $howManyQuotes = number_format($howManyQuotes);
//			var_dump($aPost);
//			var_dump($howManyQuotes);
		}
		if($theViews == '')
			$theViews = 0;

//		$date = date("format", $thePost->post_date);
		$date = mysql2date('M j Y', $thePost->post_date);
//	echo $thePost->post_date;	

		$theUpdate = $thePost->post_title . ' ' . $theURL;
		$theImage = find_img_src($thePost);
//    echo get_permalink($aResult->post_id) . '<br>';
		echo _e('<div class="column post_title_ybi"><a href="'.$theURL. '" target="_blank">'. $thePost->post_title . '</a> (' . $date . ') - <a href="post.php?post='.$aResult->post_id.'&action=edit">edit</a></div><div class="column post_views_ybi">'. number_format($theViews*$currentYBIMULTIPLIER)  .'</div><div class="column post_social_quotes">'. $howManyQuotes  .'</div><div class="column post_shares_ybi">');
		echo '<a href="http://hootsuite.com/hootlet/load?title='.urlencode($thePost->post_title).'&address='.urlencode($theURL).'" target="_blank" class="hootsuite"><img src="' . plugins_url( 'i/hootsuite-icon_24.png' , __FILE__ ) . '" /></a>';
		echo '<a href="http://www.twitter.com/home?status='. urlencode($theUpdate).'" target="_blank" class="twitter"><i class="fa fa-twitter fa-2x"></i></a>';
		//http://www.facebook.com/sharer.php?s=100&p[title]=titlehere&p[url]=http://www.yoururlhere.com&p[summary]=yoursummaryhere&p[images][0]=http://www.urltoyourimage.com
		echo '<a href="http://www.facebook.com/sharer.php?s=100&p[title]='.urlencode($thePost->post_title).'&p[url]='.urlencode($theURL).'&p[images][0]='.urlencode($theImage).'" target="_blank" class="facebook"><i class="fa fa-facebook fa-2x"></i></a>';
		//http://www.linkedin.com/shareArticle?mini=true&url={articleUrl}&title={articleTitle}&summary={articleSummary}&source={articleSource}
		echo '<a href="http://www.linkedin.com/shareArticle?mini=true&title='.urlencode($thePost->post_title).'&url='.urlencode($theURL).'" target="_blank" class="linkedin"><i class="fa fa-linkedin fa-2x"></i></a>';
		echo '<a href="https://plus.google.com/share?url='.urlencode($theURL).'" target="_blank" class="googleplus"><i class="fa fa-google-plus fa-2x"></i></a>';
		echo '<a href="http://www.tumblr.com/share?v=3&u='.urlencode($theURL).'&title='.urlencode($thePost->post_title).'" target="_blank" class="tumblr"><i class="fa fa-tumblr fa-2x"></i></a>';
/*

http://ajtroxell.com/articles/pinterest-and-google-plus-share-links-without-javascript/

icon-pinterest

<a href="http://pinterest.com/pin/create/button/?url=<?php the_permalink();?>&media=<?php echo gangmei_get_the_post_thumbnail_url($post->ID, 'large'); ?>&description=<?php echo get_the_excerpt(); ?>" onclick="window.open(this.href); return false;">Pinterest</a>
*/
		$row++; // next row of aResult for Loop
		echo '</div></div>';
		
    }
	
	?>

        </div>
    </div><!--products_left-->
    </div>
</div><!--wrap-->