<?php 

function returnSpecialDomainParsing($url)
{
	if(strpos($url,"youtube.com") !== false)
	{
        $url = str_replace("?feature=player_embedded&v=", "?v=", $url);
	}
	return $url;
}
function ybi_cs_getYouTubeVideoID($url)
{
	//http://stackoverflow.com/questions/3392993/php-regex-to-get-youtube-video-id
	preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches);
	$video_id = '';
	if($matches)
	   $video_id = $matches[1];
   return $video_id;	
}
function getVimeoThumb($id) 
{
	//http://vimeo.com/58511112
	$data = file_get_contents("http://vimeo.com/api/v2/video/$id.json");
	$data = json_decode($data);
	//return $data[0]->thumbnail_medium;
	return $data[0]->thumbnail_large;
}

/**
* This function cleans common bad text we get back from the ClippedAPI
*
* @param string $inText
*
* @return string $text - clean result text
*/
	function cleanForClipped($inText)
	{
		$trans = 
			array(
				',' => ', ',
				':' => ': ',
				'\'' => '\\\'',
				'â' => '\'',
				'Â' => '',
				
				);
		$inText = strtr($inText, $trans);			
		return $inText;	
	}
	function replaceBRwithLB($inString)
	{
		$inString = str_replace("<br />", "\n", $inString);
		$inString = str_replace("<Br />", "\n", $inString); // added this because when we parse the string for uppercase of each word it makes the B upper case
		$inString = str_replace("<Br>", "\n", $inString);
		$inString = str_replace("<br>", "\n", $inString);
		return $inString;
	}

	function ybi_cu_startswith($haystack, $needle) {
		return substr($haystack, 0, strlen($needle)) === $needle;
	}
	// this function is for images, it looks for common strings contained in SRC's that don't have http/https	
	function justAddHTTP($inSRC)
	{
		$returnVal = false;
		$badStrings = array('//cdn','cloudfront.net','//cdn0');
		foreach ($badStrings as $value) 
		{
			if(strpos($inSRC,$value) !== false)
			{
				$returnVal = true;
				break;
			}
		}
		if(ybi_cu_startswith($inSRC, '//'))
			$returnVal = true;
		
		return $returnVal;
		
	}
	// this function removes all non utf-8 chars... atleast that's the theory, we aren't using this anymore.
	function RemoveBS($Str) {  
	  $StrArr = str_split($Str); $NewStr = '';
	  foreach ($StrArr as $Char) {    
		$CharNo = ORD($Char);
		if ($CharNo == 163) { $NewStr .= $Char; continue; } // keep £ 
		if ($CharNo > 31 && $CharNo < 127) {
		  $NewStr .= $Char;    
		}
	  }  
	  return $NewStr;
	}
	function removeUnwantedText($inText)
	{
		//$inText = removeBS($inText);
		// use this chart maybe: http://www.danshort.com/HTMLentities/
		// possibly add this later
		//$inText = trim( ( html_entity_decode($inText, ENT_QUOTES, 'UTF-8') ) );	
		// look at this: http://us2.php.net/htmlentities
		// this is here because sometimes when we get text that'sn next to a link (a href) there's an hidden &nbsp; 
		$inText = htmlentities($inText, null, 'utf-8');
        $inText = str_replace("&nbsp;", " ", $inText);

		$trans = 
			array(
				'&#8216;' => '‘',
				'&#8217;' => '’',
				'&#8220;' => '“', 
				'&#8221;' => '”',
				'&#8211;' => '–',
				'&#8212;' => '—', 
				'&#8230;' => '…',
				' Â' => '',  // this was taken out for now but when we see it again do the same thing below, copy the string in a textarea and replace it here
				'â' => '\\\'', // this a here has two hidden spaces at the end for it to work
				'â' => '“', // this has hidden strings at the end
				'â' => '”', // this has hidden spaces in the beggining
				'â' => '‘', // ‘buy-me’ <hidden characters in this for left single quote
				'Â' => '', // this replaces a space character
				'Â ' => ' ',
				'&nbsp;' => '',
				'\'' => '\\\'', 
				);
		$inText = strtr($inText, $trans);
	//	$inText = str_replace('\n','&#10;',$inText);
		preg_replace( "/\r|\n/", "", $inText );

  $search = array(chr(145), 
                    chr(146), 
                    chr(147), 
                    chr(148), 
                    chr(151)); 

    $replace = array("'", 
                     "'", 
                     '"', 
                     '"', 
                     '-'); 

    $inText = str_replace($search, $replace, $inText); 

		
		//$inText = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x80-\xFF]/u', '', $inText);
		return $inText;	
	}
	
 /**
    * Returns an string clean of UTF8 characters. It will convert them to a similar ASCII character
    * www.unexpectedit.com 
    */
function cleanString($text) {
	//http://www.unexpectedit.com/php/php-clean-string-of-utf8-chars-convert-to-similar-ascii-char
    // 1) convert á ô => a o


	$text = htmlentities($text, null, 'utf-8');
		$trans = 
			array(
				'&#8216;' => '‘',
				'&#8217;' => '’',
				'&#8220;' => '“', 
				'&#8221;' => '”',
				'&#8211;' => '–',
				'&#8212;' => '—', 
				'&#8230;' => '…',
				' Â' => '',  // this was taken out for now but when we see it again do the same thing below, copy the string in a textarea and replace it here
				'â' => '\\\'', // this a here has two hidden spaces at the end for it to work
				'â' => '“', // this has hidden strings at the end
				'â' => '”', // this has hidden spaces in the beelining
				'â' => '‘', // ‘buy-me’ <hidden characters in this for left single quote
				'Â' => '', // this replaces a space character
				'Â ' => ' ',
				'&nbsp;' => '',
				'\'' => '\\\'', 
				);
	$text = strtr($text, $trans);
    $text = str_replace("&nbsp;", " ", $text);

    $text = str_replace("&#8217;","’",$text);
    $text = str_replace("&nbsp;", " ", $text);	

    $text = preg_replace("/[áàâãªä]/u","a",$text);
    $text = preg_replace("/[ÁÀÂÃÄ]/u","A",$text);
    $text = preg_replace("/[ÍÌÎÏ]/u","I",$text);
    $text = preg_replace("/[íìîï]/u","i",$text);
    $text = preg_replace("/[éèêë]/u","e",$text);
    $text = preg_replace("/[ÉÈÊË]/u","E",$text);
    $text = preg_replace("/[óòôõºö]/u","o",$text);
    $text = preg_replace("/[ÓÒÔÕÖ]/u","O",$text);
    $text = preg_replace("/[úùûü]/u","u",$text);
    $text = preg_replace("/[ÚÙÛÜ]/u","U",$text);
    $text = preg_replace("/[’‘‹›‚]/u","'",$text);
    $text = preg_replace("/[“”«»„]/u",'"',$text);
    $text = str_replace("–","-",$text);
    $text = str_replace(" "," ",$text);
    $text = str_replace("ç","c",$text);
    $text = str_replace("Ç","C",$text);
    $text = str_replace("ñ","n",$text);
    $text = str_replace("Ñ","N",$text);

 
    //2) Translation CP1252. &ndash; => -
    $trans = get_html_translation_table(HTML_ENTITIES); 
    $trans[chr(130)] = '&sbquo;';    // Single Low-9 Quotation Mark 
    $trans[chr(131)] = '&fnof;';    // Latin Small Letter F With Hook 
    $trans[chr(132)] = '&bdquo;';    // Double Low-9 Quotation Mark 
    $trans[chr(133)] = '&hellip;';    // Horizontal Ellipsis 
    $trans[chr(134)] = '&dagger;';    // Dagger 
    $trans[chr(135)] = '&Dagger;';    // Double Dagger 
    $trans[chr(136)] = '&circ;';    // Modifier Letter Circumflex Accent 
    $trans[chr(137)] = '&permil;';    // Per Mille Sign 
    $trans[chr(138)] = '&Scaron;';    // Latin Capital Letter S With Caron 
    $trans[chr(139)] = '&lsaquo;';    // Single Left-Pointing Angle Quotation Mark 
    $trans[chr(140)] = '&OElig;';    // Latin Capital Ligature OE 
    $trans[chr(145)] = '&lsquo;';    // Left Single Quotation Mark 
    $trans[chr(146)] = '&rsquo;';    // Right Single Quotation Mark 
    $trans[chr(147)] = '&ldquo;';    // Left Double Quotation Mark 
    $trans[chr(148)] = '&rdquo;';    // Right Double Quotation Mark 
    $trans[chr(149)] = '&bull;';    // Bullet 
    $trans[chr(150)] = '&ndash;';    // En Dash 
    $trans[chr(151)] = '&mdash;';    // Em Dash 
    $trans[chr(152)] = '&tilde;';    // Small Tilde 
    $trans[chr(153)] = '&trade;';    // Trade Mark Sign 
    $trans[chr(154)] = '&scaron;';    // Latin Small Letter S With Caron 
    $trans[chr(155)] = '&rsaquo;';    // Single Right-Pointing Angle Quotation Mark 
    $trans[chr(156)] = '&oelig;';    // Latin Small Ligature OE 
    $trans[chr(159)] = '&Yuml;';    // Latin Capital Letter Y With Diaeresis 
    $trans['euro'] = '&euro;';    // euro currency symbol 

	ksort($trans); 
     
    foreach ($trans as $k => $v) {
        $text = str_replace($v, $k, $text);
    }

    // 3) remove <p>, <br/> ...
    $text = strip_tags($text); 
     
    // 4) &amp; => & &quot; => '
    $text = html_entity_decode($text);
     
    // 5) remove Windows-1252 symbols like "TradeMark", "Euro"...
    $text = preg_replace('/[^(\x20-\x7F)]*/','', $text); 
     
    $targets=array('\r\n','\n','\r','\t',
	'  ', '  ' // we added double, triple spaces that covert to single below
	);
    $results=array(" "," "," ","",
	' ', ' ',
	);
    $text = str_replace($targets,$results,$text);

    //XML compatible
    /*
    $text = str_replace("&", "and", $text);
    $text = str_replace("<", ".", $text);
    $text = str_replace(">", ".", $text);
    $text = str_replace("\\", "-", $text);
    $text = str_replace("/", "-", $text);
    */

    return ($text);
}

function clean_string_simple($text)
{
	$text = htmlentities($text, null, 'utf-8');
	$text = strip_tags($text);
	$text = str_replace("&#8217;","’",$text);
	$text = str_replace("&nbsp;", " ", $text);
	$text = html_entity_decode($text);
	$targets=array('\r\n','\n','\r','\t',
		'  ', '  ' // we added double, triple spaces that covert to single below
	);
	$results=array(" "," "," ","",
		' ', ' ',
	);
	$text = str_replace($targets,$results,$text);
	return $text;
}

	function ybi_cs_trim_value(&$value)
	{ 
		$value = trim($value); 
	}
	
/**
* This function loops through an array of bad string text of commonly found images that we don't want to show to the user
* If it finds a bad string it will return true, these images have been found through testing and using the tool and are constantly updated
* once this function finds a bad SRC it breaks and returns right away
*
* @param string $inSRC
*
* @return boolean $foundBadURL - if it returns true then a bad image (or one we've deemed bad is found).
*/
	function checkForBadImage($inSRC)
	{
	  $badURLStrings = 
	  array(
		  'doubleclick.net', 'tracking/?', 'tracking.cmcigroup.com', 'ib.adnxs.com',
          'bounceexchange.com',
          'bounceexchange.com',
          'liadm.com',
          'bat.bing.com',
          'GDContent/applogos',
          'googleadservices.com',
          'simpli.fi',
          'images.outbrain.com/transform',
          'get_flash_player.gif',
          //advertising
		  'clear.gif', 'spacer.gif', 'noscript.gif', 'njs.gif', 'event=noscript', 'ajax-loader.gif', '1px.gif', 'loading.gif', 'trans.gif', 'transparent.gif', 'blank.gif', 'transparent-',
		  'img-dot.gif', 'spaceout.gif', '1.gif', 'transparent.png', 'separator.png', 'font-dec.gif','font-inc.gif', // kinda obvious
		  'rss.png','email.jpg','avatar.png', 'gplus-32.png','email-icon.gif','rss.gif', 'rss-share.png', 'rss-icon.png', 'lazy-load-image', 'pixel.png', // some pretty common
		  '/cgi-bin', '.php?',// unknown
		  'data:image', // found on buzzfeed
		  'lg-share-en.gif', // http://s7.addthis.com/static/btn/lg-share-en.gif
		  'zor.livefyre.com', '/livefyre-avatar', // livefyre commenting
		  'site-logo-cutout.png', // techcrunch logo
		  'scorecardresearch.com', // Comscore research image
		  'quantserve.com', // Quantcast Measurement
		  'http://stats.wordpress.com/b.gif?v=noscript', // wordpress tracking
		  'sharethis.com/chicklets', 'share_save_106_16.gif', // sharethis small icons
		  'webtrendslive.com', // webtrends tracking
		  '.youtube-nocookie.com','yt/img/pixel', '//i2.ytimg.com', // youtube
		  'http://passets-cdn.pinterest.com', 'pinterest.com/avatars/', 'PinExt.png', 'pin_it_button', // pinterest.com common images
		  'http://graph.facebook.com', 'FBbutton.jpg', // facebook social graph images
		  'gravatar.com/avatar', // gravatar avatar images
		  'getclicky.gif', 'in.getclicky.com', //get clicky
		  '/static.networkedblogs.com', // networked blogs logo
		  'http://www.1shoppingcart.com/app/', // 1shopping cart buttons
		  'wsj.net/img/b.gif', // wsj site
		  'leadback.advertising.com', //advertising platform
		  'toolkit-addthis.jpg', 'media.fastclick.net',
		  'ico-rss.gif', 'ico-twitter.gif', 'ico-apple.gif', 'ico-facebook.png', 'ico-twitter.png', 'ico-del.png', 'ico-digg.png',// standard icon naming
		  '.html', 'adx_remoif',
		  // Below are images that if we need to remove because this get's to big we should as they aren't as wide as the ones above
		  'print_190.gif', 'print-button.gif', 'x.gif', 'rating_on.gif', 'rating_off.gif', 'favicon_poll.png', 'header-ie8.png',
		  '?P=', // yeahoo images URL parameter
		  'reddit.gif', // reddit share
		  'transparent-1093278.png', // yahoo
		  'btn', // standard btns
		  'facebook.com/tr', 'analytics.twitter.com/i/adsct', 't.co/i/adsct', // social tracking
		  'amplifypixel.outbrain.com',
		  '24x24','16x16','sprites/','&h=64&w=64'
	  );
		
	  $foundBadURL = false;
	  foreach ($badURLStrings as $value) 
	  {
		  if(strpos($inSRC,$value) !== false)
		  {
			  $foundBadURL = true;
			  break;
		  }
	  }
	  //if we found a bad url then we skip this element so continue
	  return $foundBadURL;
	}
/**
* This function operates similar to the checkforBAdImages except this one we always run because we've confirmed or are about 95% sure these images should not ever be shown, even on an advanced repull.
*
* @param string $inSRC
*
* @return boolean $foundBadURL - if it returns true then a bad image (or one we've deemed bad is found).
*/
	function checkForConfirmedBadImage($inSRC)
	{
	  $badURLStrings =
	  array(
		  'doubleclick.net', '', //advertising
		  'clear.gif', 'spacer.gif', 'noscript.gif', 'njs.gif', 'event=noscript', 'ajax-loader.gif', '1px.gif', 'loading.gif', 'trans.gif', 'transparent.gif', 'blank.gif',
		  'img-dot.gif', 'spaceout.gif', '1.gif', 'transparent.png', 'separator.png', 'font-dec.gif','font-inc.gif', // kinda obvious
		  'rss.png','email.jpg','avatar.png', 'gplus-32.png','email-icon.gif','rss.gif', 'rss-share.png', 'rss-icon.png', 'lazy-load-image', 'pixel.png', 'mail_icon.png', 'mobile-icon.png', 'gplus-16.png',// some pretty common icons
		  '/cgi-bin', '.php?',// unknown
          'g.doubleclick.net', //doublclick
		  'lg-share-en.gif', // http://s7.addthis.com/static/btn/lg-share-en.gif
		  'zor.livefyre.com', '/livefyre-avatar', // livefyre commenting
		  'site-logo-cutout.png', // techcrunch logo
		  'scorecardresearch.com', // Comscore research image
		  'quantserve.com', // Quantcast Measurement
		  'http://stats.wordpress.com/b.gif?v=noscript', // wordpress tracking
		  'sharethis.com/chicklets', 'share_save_106_16.gif', // sharethis small icons
		  'webtrendslive.com', // webtrends tracking
		  '.youtube-nocookie.com','yt/img/pixel', '//i2.ytimg.com', // youtube
		  'http://passets-cdn.pinterest.com', 'pinterest.com/avatars/', 'PinExt.png', 'pin_it_button', // pinterest.com common images
		  'http://graph.facebook.com', 'FBbutton.jpg', // facebook social graph images
		  'gravatar.com/avatar', // gravatar avatar images
		  'getclicky.gif', 'in.getclicky.com', //get clicky
		  '/static.networkedblogs.com', // networked blogs logo
		  'http://www.1shoppingcart.com/app/', // 1shopping cart buttons
		  'wsj.net/img/b.gif', // wsj site
		  'leadback.advertising.com', //advertising platform
		  'toolkit-addthis.jpg', 'media.fastclick.net',
		  'ico-rss.gif', 'ico-twitter.gif', 'ico-apple.gif', 'ico-facebook.png', 'ico-twitter.png', 'ico-del.png', 'ico-digg.png',// standard icon naming
		  '.html', 'adx_remoif',
		  // Below are images that if we need to remove because this get's to big we should as they aren't as wide as the ones above
		  'print_190.gif', 'print-button.gif', 'x.gif', 'rating_on.gif', 'rating_off.gif', 'favicon_poll.png', 'header-ie8.png',
		  '?P=', // yeahoo images URL parameter
		  'reddit.gif', // reddit share
		  'transparent-1093278.png', // yahoo
          'btn', // standard btns
		  'facebook.com/tr', 'https://analytics.twitter.com/i/adsct', 'http://t.co/i/adsct', // social tracking


	  );

	  $foundBadURL = false;
	  foreach ($badURLStrings as $value)
	  {
		  if(strpos($inSRC,$value) !== false)
		  {
			  $foundBadURL = true;
			  break;
		  }
	  }
	  //if we found a bad url then we skip this element so continue
	  return $foundBadURL;
	}



/**
* This function checks for bad links we don't want to commonly display to the user
* it does this by going through an array of predefined bad strings that we check the SRC ($inLink) against
*
* @param string $inLink
*
* @return boolean foundBadUrl - returns true if it found a bad url we don't typically want
*/
	function checkForBadLinks($inLink)
	{
		// '#', took out
	  $badURLStrings = 
	  array(
	  	'twitter.com/share','twitter.com/intent/tweet', 'pinterest.com/pin/create/button/','facebook.com/dialog/feed','linkedin.com/shareArticle','plus.google.com/share','tumblr.com/share',
		'http://disqus.com','ad.doubleclick.net/','sharer.php','javascript:void', 'addthis.com/bookmark', 'disqus.com/?url', 'get.adobe.com/flashplayer', 'gp/redirect.html',
		'lrd.yahoo.com', 'login.yahoo.com', // yahoo
	  );
		  $foundBadURL = false;
		  foreach ($badURLStrings as $value) 
		  {
			  if(strpos($inLink,$value) !== false)
			  {
				  $foundBadURL = true;
				  break;
			  }
		  }
	  //if we found a bad url then we skip this element so continue
	  return $foundBadURL;
		
	}
/**
* This function looks for common text that provide no value for curating. Text such as subscribe or breadcrumbs
*
* @param string $inPossiblePara
*
* @return boolean $foundBad - returns true if it found text we don't want to display
*/
	function isBadParagraph($inPossiblePara)
	{
		// this function cycles through words and phrases looking for common ones we don't want to show as options
		$foundBad = false;
		$inPossiblePara = trim($inPossiblePara);
		$paraLen = strlen($inPossiblePara);
		if($paraLen <= 2)
			return true;
	
		$badStrings = array(
		'function(', // these ones we might want to add back
		'!function','setTimeout','TWP.Util','createElement','window.location','wp_meta_data','#post_most','thisnode','analytics.init','display: none','getElementById',
		'jQuery(','Home>Archive', 'Previous / Next', 'Last updated at', 'View the discussion thread', 'Last Updated: ', '| Subscribe',
		'Like on Facebook','Share on Twitter','new date()', 'AudioPlayer.setup','leave a comment', 'view comments','[CDATA', 'var cloudfront',
            'window._taboola','chartbeat.com','document.body.appendChild', '___','View gallery',
            '$("#','src=','generic_err','{"','Sign in to comment!'
		);
		foreach ($badStrings as $value) 
		{
			if(stripos($inPossiblePara,$value) !== false)
			{
				$foundBad = true;
				break;
			}
		}
		return $foundBad;	
	}
	
// BOTH OF THESE functions are used further below
// the below function parses a string and gives you back all the sentances
function getSentances($inTextBlock)
{
  $re = '/# Split sentences on whitespace between them.
  (?<=                # Begin positive lookbehind.
	[.!?]             # Either an end of sentence punct,
  | [.!?][\'"]        # or end of sentence punct and quote.
  )                   # End positive lookbehind.
  (?<!                # Begin negative lookbehind.
	Mr\.              # Skip either "Mr."
  | Mrs\.             # or "Mrs.",
  | Ms\.              # or "Ms.",
  | Jr\.              # or "Jr.",
  | Dr\.              # or "Dr.",
  | Prof\.            # or "Prof.",
  | Sr\.              # or "Sr.",
  | \s[A-Z]\.              # or initials ex: "George W. Bush",
					  # or... (you get the idea).
  )                   # End negative lookbehind.
  \s+                 # Split on whitespace between sentences.
  /ix';
  return preg_split($re, $inTextBlock, -1, PREG_SPLIT_NO_EMPTY);
}

// here we combine the sentances, we did this to be extra careful and parsing out all bad characters...
function getSentancesCombined($inTextBlock)
{
	$theRetString = '';
	// add '/(\'|&#0 *39;)/' to check for apostophre
	// removed ,'<br>' because it was taking out br in words....
	$inTextBlock = preg_replace(array('/\r/', '/\n/', '/\t/'), '', $inTextBlock);
	$inTextBlock = str_replace("\t",'',$inTextBlock);
	$getSentanceArr = getSentances($inTextBlock);
	$i = 0;
	foreach ($getSentanceArr as $val) 
	{
		//echo "<br>" . $val;
		if($i == 0)
			$theRetString .= $val;	
		else
			$theRetString .= ' ' . $val;
		$i++;
	}
	return $theRetString;
}

	
	
/**
* This function looks verifies we have a good twitter status. Pass a valid URL we think is Twitter (we should have this pre-scraped)
*
* @param string $inSRC
*
* @return boolean $foundBad - returns true if the Twitter status appears to be good
*/	
	function isGoodTwitterStatus($inSRC)
	{
		if(preg_match('[statuses|status]', $inSRC)) // all updates pretty much ahve status or statuses
		{
			
			$badStrings = array(
			'user_timeline','home?status=',
			);
			foreach ($badStrings as $value) 
			{
				if(stripos($inSRC,$value) !== false)
				{
					return false;
				}
			}
		}
		else
			return false;

		// if all fails we return true
		return true;
	}
	// this function goes thru potential SRc's looking for slideshare
	// if it finds one it gets the ID and then calls the SlideShare API to get the thumb
	// it then returns an array with each element having two values, ID and thumbnail
	function getSlideShares($inSourceDomain, $url, $allSlideShareSrcFromIframes)
	{
		$returnSlideShareArr = array();
		$returnSlideShowEmbedSrcs = array();
		$returnSlideShowEmbedSrcs = $allSlideShareSrcFromIframes;
		if($inSourceDomain == 'slideshare.net')
			$returnSlideShowEmbedSrcs[] = $url;
	
		$i = 0;
		foreach($returnSlideShowEmbedSrcs as $element) 
		{
				if($inSourceDomain == 'slideshare.net')
				{
					$ch = curl_init();
					// Disable SSL verification
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					// Will return the response, if false it print the response
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					// set the timeout
					curl_setopt($ch, CURLOPT_TIMEOUT, 10);
					// Set the url
					curl_setopt($ch, CURLOPT_URL,"http://www.slideshare.net/api/oembed/2?url=".$url.'&format=json');
					// Execute
					$clippJSON=curl_exec($ch);
					
					// this was the old call using file_get_contents
					//$clippJSON = mb_convert_encoding(file_get_contents("http://clipped.me/algorithm/clippedapi.php?url=".$url), "HTML-ENTITIES","UTF-8");
					$jsonData = json_decode($clippJSON, FALSE);
					//var_dump($jsonData);
			
					// verify the json data exists
					//<iframe src="http://www.slideshare.net/slideshow/embed_code/31993522?rel=0" width="427" height="356" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" style="border:1px solid #CCC; border-width:1px 1px 0; margin-bottom:5px; max-width: 100%;" allowfullscreen> </iframe> <div style="margin-bottom:5px"> <strong> <a href="https://www.slideshare.net/vaynerchuk/how-to-rock-sxsw" title="How to Rock SXSW" target="_blank">How to Rock SXSW</a> </strong> from <strong><a href="http://www.slideshare.net/vaynerchuk" target="_blank">Gary Vaynerchuk</a></strong> </div>
					$slideshow_id = $jsonData->slideshow_id;
					$thumbnailSRC = $jsonData->thumbnail;
				}
				else
				{
						$slideshow_id = str_replace("http://www.slideshare.net/slideshow/embed_code/", "", $element);
						$thumbnailSRC = '';
				}
				if($slideshow_id)
				{
					//$slideshow_id;
					$enbedSRC = 'http://www.slideshare.net/slideshow/embed_code/'.$slideshow_id;
					$singleElement = array('id' => $slideshow_id,'thumbnail' => $thumbnailSRC);
					$returnSlideShareArr[] = $singleElement;
				}
		}
	
		return $returnSlideShareArr;
	}
	function MakeUniqueArr($inArr)
	{
		//we might add more logic here later
		return array_unique($inArr);	
	}
	function ybi_cs_isCurl(){
    	return function_exists('curl_version');
	}
?>