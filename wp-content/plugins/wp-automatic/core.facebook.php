<?php  
// Main Class
require_once 'core.php';

Class WpAutomaticFacebook extends wp_automatic{
	


	/**
	 * function : fb_get_post
	 */
	function fb_get_post($camp){
 	
		//get page id
		$camp_general=unserialize(base64_decode($camp->camp_general));
		$camp_opt = unserialize ( $camp->camp_options );
	
		  echo '<br>Processing FB page:'.$camp_general['cg_fb_page'];
	
		//getting access tocken
		$cg_fb_access = get_option('wp_automatic_fb_token','');
	
		 
		
		if(trim($cg_fb_access ) == ''){
			
			echo '<br><span style="color:red">Please visit the plugin settings page and add the required Facebook access token.</span>';
			return false;
	
			  echo '<br>Getting a FB access token..';
	
			$wp_automatic_fb_app = trim( get_option('wp_automatic_fb_app','') );
			$wp_automatic_fb_secret = trim( get_option('wp_automatic_fb_secret','') );
	
			if(trim($wp_automatic_fb_app) == '' || trim($wp_automatic_fb_secret) == ''){
				  echo '<br>NO APP ID FOUND, PLEASE VISIT THE PLUGIN SETTING AND ADD THE FACEBOOK APP ID/SECRET';
				return false;
			}
	
			//get token
			//curl get
			$x='error';
			$url="https://graph.facebook.com/oauth/access_token?client_id=$wp_automatic_fb_app&client_secret=$wp_automatic_fb_secret&grant_type=client_credentials";
			curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
			curl_setopt($this->ch, CURLOPT_URL, trim($url));
	
			$exec=curl_exec($this->ch);
			$x=curl_error($this->ch);
	
			if(stristr($exec, 'access_token":')){
	
				//found
				  echo '<br>Successfully got an access token';
				
				$jsonR = json_decode($exec);
				
				$cg_fb_access = $jsonR->access_token;
	
				update_option('wp_automatic_fb_token', $cg_fb_access);
	
			}else{
	
				  echo '<br>Can not find access token at content after requesting it'.$x.$exec;
				return false;
	
			}
	
	
		}
	
	
		// PAGE ID
		$cg_fb_page_id = get_post_meta($camp->camp_id,'cg_fb_page_id',1);
	
		//if a numeric id use it direclty
		$url= $camp_general['cg_fb_page'] ;
	
		if(is_numeric($url)){
			  echo '<br>Numeric id added manually using it as the page id.';
			$cg_fb_page_id = trim($url);
		}
	
	
		//get page id if not still extracted
		if(trim($cg_fb_page_id) == ''){
			  echo '<br>Extracting page id from original page link';
	
			//getting page name from url
				
			//curl get
			$x='error';
			$url= $camp_general['cg_fb_page'] ;
			curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
			curl_setopt($this->ch, CURLOPT_URL, trim($url));
			$exec=curl_exec($this->ch);
			$x  = curl_error($this->ch);
	
		 		
			if(stristr($exec, 'PageComposerPagelet_')){
	
				//extracting
				preg_match_all('{PageComposerPagelet_(\d*?)"}', $exec,$matches);
				$smatch =  $matches[1];
				$cg_fb_page_id = $smatch[0];
					
				if(trim($cg_fb_page_id) !=''){
					  echo '<br>Successfully extracted  :'.$cg_fb_page_id;
					update_post_meta($camp->camp_id, 'cg_fb_page_id', $cg_fb_page_id);
				}else{
					  echo '<br>Can not find numeric entityID';
				}
	
			}else{
	
				//entity_id if the fb page validation check
				if(stristr($exec, 'entity_id')){
					  echo '<br>entity_id found getting id from it';
						
					preg_match_all('{entity_id":"(\d*?)"}', $exec,$matches);
					$smatch =  $matches[1];
					$cg_fb_page_id = $smatch[0];
	
					if(trim($cg_fb_page_id) !=''){
						  echo '<br>Successfully extracted entityID:'.$cg_fb_page_id;
						update_post_meta($camp->camp_id, 'cg_fb_page_id', $cg_fb_page_id);
					}else{
						  echo '<br>Can not find numeric entityID';
					}
						
						
						
				}else{
					  echo '<br>entity_id does not exists either ';
					  echo '<br>Can not find valid FB reply.';
				}
			}
		}
	
	
		//building feed
		if(  (trim($cg_fb_page_id) !='' ) &&  (trim($cg_fb_access) !='' )  ){
								
			$cg_fb_source = $camp_general['cg_fb_source'];
			$cg_fb_from     = $camp_general['cg_fb_from'] ;
			
			if(trim($cg_fb_from) == '') $cg_fb_from = 'posts';
			
			if($cg_fb_source == 'group'){
	
				$cg_fb_page_feed = "https://graph.facebook.com/v2.7/$cg_fb_page_id/feed?access_token=$cg_fb_access&limit=100&fields=message,likes.limit(0).summary(true),story,attachments{title,media,type,subattachments.limit(100)},created_time,id,type,picture,link,name,description,from";
				$cg_fb_page_feed2 = "https://graph.facebook.com/v2.7/$cg_fb_page_id/feed?access_token=[token]";
				//$cg_fb_page_feed2 = $cg_fb_page_feed;
				
			}else{
	
				$cg_fb_page_feed = "https://graph.facebook.com/v2.7/$cg_fb_page_id/$cg_fb_from?access_token=$cg_fb_access&limit=100&fields=message,likes.limit(0).summary(true),story,attachments{title,media,type,subattachments.limit(100)},created_time,id,type,picture,link,name,description,from";
				$cg_fb_page_feed2 = "https://graph.facebook.com/v2.7/$cg_fb_page_id/$cg_fb_from?access_token=[token]";
	
				
				
			}
			
			//events endpoint
			if($cg_fb_from == 'events'){
				$cg_fb_page_feed = "https://graph.facebook.com/v2.7/$cg_fb_page_id/$cg_fb_from?access_token=$cg_fb_access&limit=100&fields=description,end_time,name,place,start_time,id,picture,type,updated_time,cover";
				$cg_fb_page_feed2 ="https://graph.facebook.com/v2.7/$cg_fb_page_id/$cg_fb_from?access_token=[token]";
			}
			
			//locale
			if(in_array('OPT_OPT_FB_LANG', $camp_opt)){
				$cg_fb_lang = trim($camp_general['cg_fb_lang']);
			    $cg_fb_page_feed.='&locale='.$cg_fb_lang;
			    $cg_fb_page_feed2.='&locale='.$cg_fb_lang;
			}
				
			echo '<br>FB URL:'.$cg_fb_page_feed2;
				
			//load feed
			//curl get
			$x='error';
			$url=$cg_fb_page_feed;
			curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
			curl_setopt($this->ch, CURLOPT_URL, trim($url));
				
			//CACHE
			$saveCache = false;
				
			if(in_array('OPT_FB_CACHE', $camp_opt)){
	
				$temp = get_post_meta($camp->camp_id,'wp_automatic_cache',true);
				@$temp = base64_decode($temp);
	
				if(stristr($temp, '"data"')){
						
					  echo '<br>Results loaded from the cache';
					$exec = $temp;
					
	
				}else{
					  echo '<br>No valid cache found requesting facebook';
					$saveCache = true;
					
					//nextpage if available 
					$nextPageUrl = get_post_meta($camp->camp_id,'nextPageUrl',true);
					if(trim($nextPageUrl != '') && in_array('OPT_FB_OLD', $camp_opt)){

						  echo '<br>Pagination url:'.$nextPageUrl;
						
						// Current Until in the next page URL
						preg_match('{until\=(\d*)}', $nextPageUrl,$untilMatchs);
						$currentUntil = $untilMatchs[1] ;
						  echo '<br>CurrentUntil:'.$currentUntil;
						
						// Maximum until reached 
						$maximumUntil = get_post_meta($camp->camp_id,'maximumUntil',1);
						if(trim($maximumUntil) == '') $maximumUntil = 0 ;
						
						// Check if current until > max until and if larger set it at the max until
						if($currentUntil > $maximumUntil){
							update_post_meta($camp->camp_id, 'maximumUntil', $currentUntil);
							$maximumUntil = $currentUntil;
						}
						  echo '<br>MaxUntil'.$maximumUntil;
						
						// Max until when end reached
						$maximumUntilEndReached = get_post_meta($camp->camp_id,'maximumUntilEndReached',1);
						if(trim($maximumUntilEndReached) == '') $maximumUntilEndReached = 0 ;
						  echo '<br>maximumUntilEndReached:'.$maximumUntilEndReached;
						
						
						if($currentUntil <= $maximumUntilEndReached ){
							  echo '<br>Not valid until below maximumUntilEndReached getting first 100 items';

							//new end reach 
							update_post_meta($camp->camp_id, 'maximumUntilEndReached' , $maximumUntil);
							delete_post_meta($camp->camp_id	, 'nextPageUrl');
							  
						}else{
						
							curl_setopt($this->ch, CURLOPT_URL, trim($nextPageUrl));

						}
						 
					}
					
					$exec=curl_exec($this->ch);
					
				}
	
			}else{
				$exec=curl_exec($this->ch);
			}
	
			$x=curl_error($this->ch);
	
			if ( stristr($exec, '"data"') ){ // Checks that the object is created correctly
					
				//if save cache enbaled
				if($saveCache){
					  echo '<br>Caching the results..';
					update_post_meta($camp->camp_id, 'wp_automatic_cache', base64_encode($exec));
				}
	
				$fb_json =json_decode($exec);
	
				$items = $fb_json->data;
					
					
				// Loop through each feed item and display each item as a hyperlink.
				$i = 0;
	
				  echo ' items:'.count($items);
	
				foreach ( $items as $item ){
	 
					// txt content for title generation
					$txtContent = '' ;
	
					// building the link
					$item_id = $item->id;
					$isEvent = false; //ini
					
					if(stristr($item_id, '_')){
						$id_parts = explode('_', $item_id);
						$url = "https://www.facebook.com/{$id_parts[0]}/posts/{$id_parts[1]}";
						$lastItemUntil = strtotime( $item->created_time);
					}else{
						//events
						$id_parts = array( $cg_fb_page_id , $item_id);
						$url = "https://www.facebook.com/$item_id";
						$lastItemUntil = strtotime( $item->updated_time);
						$item->created_time = $item->updated_time;
						$item->type = 'event';
						$isEvent = true;
					}
					
					 
					  echo '<br>Link:'.$url ;
	
					//check if execluded link due to exact match does not exists
					if( $this->is_execluded($camp->camp_id, $url)){
						  echo '<-- Excluded link';
						continue;
					}
						
					//check if old
					$foundOldPost = false;
					if(in_array('OPT_YT_DATE', $camp_opt)     ){
						if($this->is_link_old($camp->camp_id,  strtotime(  $item->created_time  ) )){
							  echo '<--old post execluding...';
							$foundOldPost = true;
							continue;
						}
					}
						
					if (! $this->is_duplicate($url) ) {
						  echo '<-- new link';
	
						/*
						   echo '<pre>';
						 print_r($item);
						   echo '</pre>';
						 */
							
						//hyperlinking
						if(isset($item->message) ){
	
							$item->message = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$0</a>', $item->message);
	
							//hyperlinking the message links
							if( isset($item->message) && trim($item->message) != '' ){
									
								//extracting  hashtags
								$item->message = preg_replace('/#(\w+)/u', ' <a href="https://www.facebook.com/hashtag/$1">#$1</a>', $item->message);
	
							}
								
						}else{
							$item->message = '';
						}
	
							
						$i ++;
						// posting content to emails
						$created_time  = $item->created_time;	
						$created_time_parts = explode('+', $created_time);
						$created_time = $created_time_parts[0];
						$created_time = str_replace('T', ' ', $created_time);
	
						//utc convert
						$created_time = get_date_from_gmt($created_time);
						$wpdate = $date = $created_time;
	
	
					}else{
						  echo '<-- duplicate in post <a href="'.get_edit_post_link($this->duplicate_id).'">#'.$this->duplicate_id.'</a>';
						continue;
	
					}
						
					//check if older than minimum date
					if($this->is_link_old($camp->camp_id,  strtotime($wpdate) )){
						  echo '<--old post execluding...';
						continue;
					}
	
					//building content
					$type = $item->type; 
					  echo '<br>Item Type:'.$type;
						
					//type check
					if(in_array('OPT_FB_POST_FILTER', $camp_opt)){
	
						if( ! in_array('OPT_FB_POST_'.$type, $camp_opt)){
							  echo '<-- Skip this type not selected ';
							continue;
						}
	
					}
						
					//buidling content
					$title = '';
					$content = '';
					
					 
					
					if($type == 'link'){
						
						//If shared a fb post sharing a link
				 		
						if( isset( $item->attachments->data[0]->type )){
							if($item->attachments->data[0]->type == 'share' ){
								if(isset($item->attachments->data[0]->title )){
									$item->name = $item->attachments->data[0]->title;
								}
							}
						}
						 
						$title = $item->name;
						
						$txtContent = $item->message;
						
						if(! in_array('OPT_FB_TXT_SKIP', $camp_opt)) $content = $item->message; 
						 
						$link = $item->link;
						
					 
					 
						if( isset( $item->attachments->data[0]->media->image->src ) ){
							
							$content .= '<p><a href="'.$link.'"><img title="'.$title.'" src="'.  $item->attachments->data[0]->media->image->src  .'" /></a> </p>';
							$imgsrc = $item->picture;
							
						}elseif ( trim( $item->picture )  != '' ){
							
							$content .= '<p><a href="'.$link.'"><img title="'.$title.'" src="'. $item->picture .'" /></a> </p>';
							$imgsrc = $item->picture;
							
						}
						
						 	
						//link
						$content .= '<p><a href="'.$link.'">'.@$item->name.'</a></p>';
							
						if( trim(@$item->description) !=''){
								
							$txtContent.= $item->description;
							if(! in_array('OPT_FB_TXT_SKIP', $camp_opt)) $content.= $item->description;
							
						}
						
	
					}elseif($type == 'photo'  || ( $type == 'status'  && isset( $item->attachments->data[0]->type) &&  $item->attachments->data[0]->type == 'album' ) ){
	
						if(trim($item->message) != ''){
							
							$txtContent = $item->message;
							if(! in_array('OPT_FB_TXT_SKIP', $camp_opt)) $content = $item->message;
						
						}elseif( isset($item->attachments->data[0]->description) &&  trim($item->attachments->data[0]->description) != ''){
								
							if(! in_array('OPT_FB_TXT_SKIP', $camp_opt))  $content = $item->attachments->data[0]->description;
							$txtContent = $item->attachments->data[0]->description;
						
						}elseif( trim(@$item->description) =='' ){
								
							if(! in_array('OPT_FB_TXT_SKIP', $camp_opt))  $content = @$item->story;
							$txtContent = @$item->story;
							
						}
	
						$content.='<br>';
	
						$link = $item->link;
							
						$attachment_type = $item->attachments->data[0]->type;
						  echo '<br>Attachment Type:'. $attachment_type;
	
						  if($attachment_type == 'album' || $attachment_type == 'new_album'){
							
							$allImgs = $item->attachments->data[0]->subattachments->data ;
							
							if( trim($txtContent) == '' && isset($item->attachments->data[0]->title)){
								
								if(! in_array('OPT_FB_TXT_SKIP', $camp_opt))  $content = $item->attachments->data[0]->title;
								$txtContent = $item->attachments->data[0]->title;
								
								
							} 
						 
							
						}else{
							$allImgs = $item->attachments->data;
						}
	
						if(count($allImgs) > 0){
								
							foreach ($allImgs as $singleImage ){
								
								$imgsrc = $singleImage->media->image->src;
								
								if( in_array('OPT_FB_IMG_LNK_DISABLE', $camp_opt) ){
									$content .= '<br><img class="wp_automatic_fb_img" title="'.$title.'" src="'. $singleImage->media->image->src .'" />';
								}else{
									$content .= '<br><a href="'.$link.'"><img class="wp_automatic_fb_img" title="'.$title.'" src="'. $singleImage->media->image->src .'" /></a>';
								}
								
							}
	
						}

						
						
						//description
						if(trim(@$item->description) !=''){
							
							if(! stristr($content, $item->description)){
							
								$txtContent.= '<br>'.$item->description;
								if(! in_array('OPT_FB_TXT_SKIP', $camp_opt)) $content.= '<br>'.$item->description;
							
							}
						}
						
					  
					}elseif($type == 'status'){
						
						if(! in_array('OPT_FB_TXT_SKIP', $camp_opt)) $content = $item->message;
						$txtContent = $item->message.' ';
						
						//check attachment
						$attachment = @$item->attachments->data[0];
						
						if(trim(@$attachment->type) != ''){
							
							$attach_img = $attachment->media->image->src;
							$imgsrc = $attach_img;
							
							if( in_array('OPT_FB_IMG_LNK_DISABLE', $camp_opt) ){
								$content .= '<br><img class="wp_automatic_fb_img" title="'.$attachment->title.'" src="'. $attach_img .'" />';
							}else{
								$content .= '<br><a href="'.$link.'"><img class="wp_automatic_fb_img" title="'.$attachment->title.'" src="'. $attach_img .'" /></a>';
							}
							
						}
						
						//attachment description
						if(@$attachment->description != ''){
							$txtContent.= $attachment->description;
							if(! in_array('OPT_FB_TXT_SKIP', $camp_opt)) $content.= ' <br>'.$attachment->description;
						}
						
						
						if(trim($content) == ''){
							echo '<-- skip status, no content';
							$this->link_execlude( $camp->camp_id, $url );
							continue;
						}
							
					}elseif( $type == 'video'  ){
	
						if(isset($item->attachments->data[0]->title)){
							$title = $item->attachments->data[0]->title;
						}
						
						if(isset($item->name) && trim($item->name) != '' && trim($title) == ''){
							$title = $item->name;
						}
							
						$style='';
	
						if (in_array('OPT_FB_VID_IMG_HIDE', $camp_opt) ){
							$style = ' style="display:none" ';
						}
	
						$imgsrc = '';
						$imgsrc = $item->attachments->data[0]->media->image->src;
				 
						//if video with pics 
						if(trim($imgsrc) == ''){
							echo ' empty vid';
							$imgsrc =  $item->attachments->data[0]->subattachments->data[0]->media->image->src;
						}
						
						$content = '<img '.$style.' title="'.$title.'" src="'. $imgsrc .'" /></a><br>';
	
						if(trim($item->message) != ''){
							
							$txtContent .= $item->message;
							if(! in_array('OPT_FB_TXT_SKIP', $camp_opt)) $content .= $item->message;
							
						}
	
						$vidurl = $item->link;
	
	
						if( stristr($vidurl, '/videos/') ){
							$vi_parts = explode('/videos/', $vidurl);
							$vid_id = $vi_parts[1];
								
							$vid_id = str_replace('/', '', $vid_id);
							  echo '<br>Found video id:'. $vid_id;
							  
							  $ret['vid_embed'] = '<div id="fb-root"></div><script>(function(d, s, id) {  var js, fjs = d.getElementsByTagName(s)[0];  if (d.getElementById(id)) return;  js = d.createElement(s); js.id = id;  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3";  fjs.parentNode.insertBefore(js, fjs);}(document, \'script\', \'facebook-jssdk\'));</script><div class="fb-video" data-autoplay="'.$autoplay.'" data-allowfullscreen="true" data-href="https://www.facebook.com/video.php?v='.$vid_id.'&amp;set=vb.500808182&amp;type=1"><div class="fb-xfbml-parse-ignore"></div></div>';
							
							if( (defined('PARENT_THEME') &&  (PARENT_THEME =='truemag' || PARENT_THEME =='newstube'))  || class_exists('Cactus_video') ){
								
							}else{
								
								if(! in_array('OPT_FB_VID_SKIP', $camp_opt)){
									
									$vidAuto = '';
									if(in_array('OPT_FB_VID_AUTO', $camp_opt)){
										$vidAuto = ' autoplay= "true" ';
									}
									
									$content.= '[fb_vid '.$vidAuto.' id="'.$vid_id.'"]';
									
									
								}	
								
							
							}
							
 							
						}elseif(stristr($vidurl, 'youtube.com')){
								
							$content.= '<br><br>[embed]'.$vidurl.'[/embed]';
								
						}
	
						$txtContent .= $item->description;
						
						
						if( $type == 'video' && in_array( 'OPT_FB_VID_TXT_SKIP' , $camp_opt) ){
							  echo ' skipinning video description......';
						}else{
						
							if(! in_array('OPT_FB_TXT_SKIP', $camp_opt))  $content .= $item->description;
						
						}
					
					}elseif($type == 'event' && $isEvent == true){
						
					 
						
						$content = '';
						 
						
						//event name
						if(isset($item->name)){
							$title = $item->name;
						}
						
						//description check
						if(isset($item->description)){
							$txtContent.= $item->description.' ';
							if(! in_array('OPT_FB_TXT_SKIP', $camp_opt)) $content.= $item->description.' ';
						}
						
						//cover pic
						$imgsrc ='';
						
						if(isset($item->cover->source)){
							$attach_img = $item->cover->source ;
							if( in_array('OPT_FB_IMG_LNK_DISABLE', $camp_opt) ){
								$content = '<img class="wp_automatic_fb_img"   src="'. $attach_img .'" /><br>' . $content;
							}else{
								$content = '<a href="'.$url.'"><img class="wp_automatic_fb_img"  src="'. $attach_img .'" /></a><br>' . $content;
							}
						}
						
					
					}elseif($type == 'event' || $type == 'offer' || $type == ''){
						
						$content = '';
						if(! in_array('OPT_FB_TXT_SKIP', $camp_opt))  @$content = $item->message.' ';
						@$txtContent = $item->message.' ';
						
						//event name
						if(isset($item->name)){
							$title = $item->name;
						}
						
						//description check
						if(isset($item->description) && $item->description != $item->message){
							$txtContent.= $item->description.' ';
							if(! in_array('OPT_FB_TXT_SKIP', $camp_opt)) $content.= $item->description.' ';
						}
						
						//check attachment
						$attachment = $item->attachments->data[0];
						
						if(trim($attachment->type) != ''){
						
							$attach_img = $attachment->media->image->src;
							
							//event cover img
							if($type == 'event'){
								
								  echo '<br>Getting event cover...';
								
								$eventID = $attachment->target->id;
								
								if( trim( $eventID )  != '' ){
									
									$cg_fb_event = "https://graph.facebook.com/v2.7/$eventID?fields=cover&access_token=$cg_fb_access";
									
									//curl get
									$x='error';
									curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
									curl_setopt($this->ch, CURLOPT_URL, trim($cg_fb_event));
									$exec=curl_exec($this->ch);
									$x=curl_error($this->ch);
									
									if( trim($exec) != '' ){
										
										$eventJson = json_decode($exec);
										$coverPic = $eventJson->cover->source;
										
										if(trim($coverPic) != ''){
											$attach_img = $coverPic;
										}
										
									}
								}
								
							}
							
							$imgsrc = $attach_img; 
							
							if( in_array('OPT_FB_IMG_LNK_DISABLE', $camp_opt) ){
								$content .= '<br><img class="wp_automatic_fb_img" title="'.$attachment->title.'" src="'. $attach_img .'" />';
							}else{
								$content .= '<br><a href="'.$link.'"><img class="wp_automatic_fb_img" title="'.$attachment->title.'" src="'. $attach_img .'" /></a>';
							}
							
						}
						
						//attachment description
						if($attachment->description != ''){

							$txtContent.='<br>'.$attachment->description;
						 		
								if(! in_array('OPT_FB_TXT_SKIP', $camp_opt) )  $content.='<br>'.$attachment->description;
								 
						}
						
						if(trim($content) == ''){
							  echo '<-- skip status, no content';
							$this->link_execlude( $camp->camp_id, $url );
							continue;
						}
						
					}elseif($type == 'note'){
						
						//shared note 
						$content = '';
						
						//message
						if(! in_array('OPT_FB_TXT_SKIP', $camp_opt))  @$content = $item->message.'<br>';
						@$txtContent = $item->message.'<br>';
						
						//note title
						if(isset($item->attachments->data[0]->title)){
							$title = $item->attachments->data[0]->title;
						}
						
						//note description
						if(isset($item->attachments->data[0]->description)){
							if(! in_array('OPT_FB_TXT_SKIP', $camp_opt))  @$content.=$item->attachments->data[0]->description.' ';
							@$txtContent.= $item->attachments->data[0]->description.' ';
						}
						
						//picture
						if($item->picture){
							$content .= '<p><a href="'.$link.'"><img title="'.$title.'" src="'. $item->picture .'" /></a> </p>';
							$imgsrc = $item->picture;
						}
						
						
					}
	
					//check if title exits or generate it
					if(trim($title) == '' && in_array('OPT_GENERATE_FB_TITLE', $camp_opt) ){
	
						  echo '<br>No title generating...';
						
						
						$tempContent = $this->removeEmoji( strip_tags(strip_shortcodes($txtContent)));
						$tempContent = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '', $tempContent);
	
						
						// Chars count
						$charsCount = $camp_general['cg_fb_title_count'];
						if(! is_numeric($charsCount)) $charsCount = 80;
	
						if(function_exists('mb_substr')){
							$newTitle =  mb_substr($tempContent, 0,$charsCount) ;
							 
							if( in_array( 'OPT_GENERATE_FB_RETURN' , $camp_opt ) && stristr($newTitle, "\n") ){

								$suggestedTitle =  preg_replace("{\n.*}", '', $newTitle);
								if(trim($suggestedTitle) != '') {
									$newTitle = $suggestedTitle;
									
									if(in_array('OPT_FB_STRIP_TITLE', $camp_opt)){
										$content = str_replace($suggestedTitle . "\n", '', $content);
									}
									
								}
								
							} 
							 
							
						}else{
							$newTitle =  substr($tempContent, 0,$charsCount) ;
							
							  echo '<br>mb_str is not installed !!!';
							
						}
							
						if(trim($newTitle) == ''){
							  echo '<- did not appropriate title';
						}else{
								
							$title = $newTitle;
								
							if( ! in_array('OPT_GENERATE_FB_DOT', $camp_opt)  && $title != $tempContent  ){
								$title.= '...';
							}
							
							  echo ':'.$title;
								
						}
	
					}
						
	
					if(trim($title) == '' && in_array('OPT_FB_TITLE_SKIP', $camp_opt)){
						  echo '<-- No title skiping.';
						continue;
					}
						
						
					//remove referral suffix
					if( stristr($content, 'com/l.php') ){
	
						//extract links
						preg_match_all('{"http://l\.facebook\.com/l\.php\?u=(.*?)"}', $content,$matches);
	
						$founds = $matches[0];
						$links = $matches[1];
	
						$i=0;
						foreach ($founds as $found){
								
							$found = str_replace('"', '', $found);
							$link = $links[$i];
								
							$link_parts = explode('&h', $link);
							$link = $link_parts[0];
	
							$content = str_replace($found, urldecode($link), $content);
								
							$i++;
						}
	
					}
						
					//replace thumbnails by full image for external links
					if (  stristr($content, 'safe_image.php')    ){
	
						if(! stristr($content, 'fbstaging')){
						
							preg_match_all('{https://[^:]*?safe_image\.php.*?url=(.*?)"}', $content, $matches);
		
							$found_imgs = $matches[0];
							$found_imgs_links = $matches[1];
		
		
							$i=0;
		
							foreach ($found_imgs as $found_img ){
									
								$found_imgs_links[$i] = preg_replace('{&.*}', '', $found_imgs_links[$i]);
		
								$found_img_link = urldecode($found_imgs_links[$i] );
								
								$content = str_replace($found_img, $found_img_link."\"", $content);

								$imgsrc = $found_img_link;
									
							}
		
						}else{
							
							$content = str_replace('&w=130', '&w=650', $content);
							$content = str_replace('&h=130', '&h=650', $content);
							
							$imgsrc = str_replace('&w=130', '&w=650', $imgsrc);
							$imgsrc = str_replace('&h=130', '&h=650', $imgsrc);
							
						}
							
					}
						
	
					//small images check s130x130
					if( 0 &&  stristr($content, '130x130') || 0 && $type == 'photo' ){
						  echo '<br>Small images found extracting full images..';
	
						preg_match_all('{"https://[^"]*?\w130x130/(.*?)\..*?"}', $content,$matches);
	
						$small_imgs_srcs = str_replace('"', '', $matches[0]);
						$small_imgs_ids = $matches[1];
	
						//remove _o or _n
						$small_imgs_ids = preg_replace('{_\D}', '', $small_imgs_ids);
	
						//remove start of the id
						$small_imgs_ids = preg_replace('{^\d*?_}', '', $small_imgs_ids);
	
						//get oritinal page
						$x='error';
						curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
						curl_setopt($this->ch, CURLOPT_URL, trim( html_entity_decode( $url)));
						$exec=curl_exec($this->ch);
						$x=curl_error($this->ch);
							
						if(stristr($exec, '<img class="scaled') && 0){
							  echo '<br>success loaded original page';
								
							//get imgs displayed
							preg_match_all('{<img class="scaled.*?>}s', $exec,$all_scalled_imgs_matches);
							$plain_imas_html = implode(' ', $all_scalled_imgs_matches[0]) ;
								
	
								
							//get ids without date at start \d{8}_(\d*?_\d*?)_
							preg_match_all('{\d{4,8}_(\d*?_\d*?)_}', $plain_imas_html,$all_ids_imgs_matches);
								
							$all_ids_imgs = array_unique($all_ids_imgs_matches[1]);
							$small_imgs_ids = $all_ids_imgs;
								
	
	
							$firstImage = '';
							@$firstImage = $all_ids_imgs[0];
								
	
							$i=0;
							foreach ($small_imgs_ids as $small_imgs_id){
	
	
								unset($large_imgs_matches);
	
									
								//searching full image
								preg_match('{src="(https://[^"]*?'.$small_imgs_id.'.*?)"}', $exec,$large_imgs_matches);
	
								//ajaxify images
								unset($large_imgs_matches_ajax);
								preg_match('{src=(https%3A%2F%2F[^&]*?'.$small_imgs_id.'.*?)&}', $exec,$large_imgs_matches_ajax);
	
	
								if(trim($large_imgs_matches[1]) != ''){
	
									$replace_img = $large_imgs_matches[1];
										
										
										
									//check if there is a larger ajaxify image or not
									if( isset($large_imgs_matches_ajax[1]) && trim($large_imgs_matches_ajax[1]) != ''){
										$replace_img = urldecode($large_imgs_matches_ajax[1]);
									}
										
	
										
									//if first image and image in the original content differs: case: added x photos to album
									if(  $i == 0  && (! stristr($content,$small_imgs_id) || ! stristr($content, 'w130x130'))  ){
	
										  echo '<br>Removing first image first';
										$content = preg_replace('{<img.*?>}', '', $content);
	
									}
										
									//  echo ' Replacing  '.$small_imgs_srcs[$i] . ' with '.$replace_img;
									if( stristr($content,$small_imgs_id) ){
											
										$content = str_replace( $small_imgs_srcs[$i], $replace_img, $content);
									}else{
										$content = str_replace('<!--reset_images-->', '<img class="wp_automatic_fb_img" src="'.$replace_img.'"/><!--reset_images-->', $content);
									}
	
	
								}
	
	
									
								$i++;
							}
								
							if($type == 'video'){
								  echo '<br>Extracting vid image';
	
								preg_match('{background-image: url\((.*?)\)}', $exec, $vid_img_match);
	
								$vid_img = $vid_img_match[1] ;
	
								if(trim($vid_img) != ''){
									$content = str_replace($item->picture, $vid_img, $content);
									  echo '-> success';
								}else{
									  echo '-> failed';
								}
									
	
									
							}
								
						}else{
							  echo '<br>Can not find image id at soure loaded page small img id:'.$small_imgs_ids[0];
								
						}
	
	
					}
						
					//fix links of facebook short /
					//$content = str_replace('href="/', 'href="https://facebook.com/', $content);
					$content = preg_replace('{href="/(\w)}', 'href="https://facebook.com/$1', $content);
	
					//change img class
					$content = str_replace('class="img"', 'class="wp_automatic_fb_img"', $content);
						
					//skip if no image
					if(in_array('OPT_FB_IMG_SKIP', $camp_opt)){
	
						if(  ! stristr($content, '<img')){
							  echo 'Post have no image skipping...';
							continue;
						}
					}
	
					if($isEvent == true){
						
					 
						
						$ret['original_title'] = $title;
						$ret['original_link'] = $url;
						$ret['matched_content'] = $content;
						$ret['original_date'] = $wpdate;
						$ret['image_src'] =$attach_img;
						$ret['post_id'] = $item_id;
						$ret['place_name'] = $item->place->name;
						$ret['place_city'] = $item->place->location->city;
						$ret['place_country'] = $item->place->location->country;
						$ret['place_latitude'] = $item->place->location->latitude;
						$ret['place_longitude'] = $item->place->location->longitude;
						$ret['place_street'] = $item->place->location->street;
						$ret['place_zip'] = $item->place->location->zip;
						$ret['start_time'] = get_date_from_gmt( gmdate(  'Y-m-d H:i:s' ,  strtotime($item->start_time)));
						$ret['end_time'] = isset($item->end_time) ? get_date_from_gmt( gmdate(  'Y-m-d H:i:s' ,  strtotime($item->end_time))) : '';
						$ret['place_map'] = isset($item->place->location->latitude) ? '<iframe src = "https://maps.google.com/maps?q='.$item->place->location->latitude . ',' . $item->place->location->longitude. '&hl=es;z=14&amp;output=embed"></iframe>' : '';
						$ret['event_description'] = $item->description;
					}else{
						
						//likes
						$item_likes = 0 ;
						$item_likes = @$item->likes->summary->total_count;
						
						$ret['original_title'] = $title;
						$ret['original_link'] = $url;
						$ret['matched_content'] = $content;
						$ret['original_date'] = $wpdate;
						$ret['from_name'] = $item->from->name;
						$ret['from_id'] = $item->from->id;
						$ret['from_url'] = 'https://facebook.com/'.$item->from->id;
						$ret['from_thumbnail'] = 'https://graph.facebook.com/'.$item->from->id.'/picture?type=large';
						$ret['post_id'] = $item_id;
						$ret['post_id_single'] =  $id_parts[1] ;
						$ret['image_src'] =$imgsrc;
						$ret['likes_count'] = $item_likes;
						
						//original url of the shared post
						if($type == 'link'){
							$ret['external_url'] = $link;
						}else{
							$ret['external_url'] = '';
						}
						
						//video url
						if(stristr($item->link, '/videos/')){
							$ret['vid_url'] = $item->link;
						}else{
							$ret['vid_embed'] = '';
							$ret['vid_url']= '';
						}
						
						
						//shares
						$shares_count = 0;
						$shares_count = @$item->shares->count;
						
						if(!is_numeric($shares_count)) $shares_count = 0 ;
						
						$ret['shares_count'] = $shares_count;
						
						if(trim($title) == '') $ret['original_title']= '(notitle)';
						
						//embed code
						$ret['post_embed'] = '<div id="fb-root"></div>
<script>
(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id))
        return;
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
    fjs.parentNode.insertBefore(js, fjs);
}(document, \'script\', \'facebook-jssdk\'));
</script>
<div class="fb-post" data-href="https://www.facebook.com/'.$ret['from_id'].'/posts/'. $id_parts[1] .'"></div>';
						
						//hashtags >#support</a>
						//echo $ret['matched_content'];
						
						if(stristr($ret['matched_content'], '#')){
							
							preg_match_all('{>#(.*?)</a>}', $ret['matched_content'] , $hash_matchs);
							$hash_matchs = $hash_matchs[1];
							$hash_tags = implode(',', $hash_matchs);
							$ret['item_tags'] = $hash_tags;
							
						}else{
							$ret['item_tags'] = '';
						}
						
					}
					
					$ret['source_link']= $ret['original_link'];
					 
					return $ret;
	
	
						
				}//endforeach
	
				echo '<br>End of available items reached....';
	
				if(in_array('OPT_FB_CACHE', $camp_opt)){
	
					echo '<br>Deleting cache as no more valid items found...';
					delete_post_meta($camp->camp_id,'wp_automatic_cache');
					
					//Setting next page url 
					$nextPageUrl = '';
					if(isset($fb_json->paging->next)){
						
						$nextPageUrl = $fb_json->paging->next;
						
						//if not until parameter in v2.10 of the api
						if(! stristr($nextPageUrl, 'until')){
							$nextPageUrl.= '&until='.$lastItemUntil;
						}
						
						echo '<br>Next Page url:'.$nextPageUrl;
						
						//if old reached disable nextpage
						if($foundOldPost) $nextPageUrl = '';
						
					}else{
						
						  echo '<br>No Next page, Mark this page as reached end';
						
						if(! isset($maximumUntil)){
							$maximumUntil = get_post_meta($camp->camp_id,'maximumUntil',1);
							if(trim($maximumUntil) == '') $maximumUntil = 0 ;
						}   

						update_post_meta($camp->camp_id, 'maximumUntilEndReached' , $maximumUntil);
						
					}
					
					
					update_post_meta($camp->camp_id, 'nextPageUrl', $nextPageUrl);
						
				}
	
			}else{
				  echo '<br>Unexpected api response: '.$x.$exec;
	
			}//wp error
				
		}//trim pageid
	}
	
	
}