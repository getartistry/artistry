<?php

// Main class to inherit wp_automatic
require_once 'core.php';

// Specific articles class
Class WpAutomaticArticles extends wp_automatic{

/*
 * ---* article base get links for a keyword ---
 */
function article_base_getlinks($keyword, $camp) {
		
	// get associated page num from the keyword and camp id from wp_automatic_articles_keys
	$query = "select * from {$this->wp_prefix}automatic_articles_keys where keyword = '$keyword' and camp_id  = '$camp->camp_id'";
	$camp_key = $this->db->get_results ( $query );
	$camp_key = $camp_key [0];
	$startPageBloked = get_post_meta($camp->camp_id,'startPageBloked',1);
	$foundUrls = array();
	
	//$startPageBloked="yes";

	if (count ( $camp_key ) == 0){
		  echo '<br>Keyword record not found';
		return false;
	}
		
	$page = $camp_key->page_num;
	if (   $page == - 1) {
		//check if it is reactivated or still deactivated
		if($this->is_deactivated($camp->camp_id, $keyword)){
			$page = '0000';
		}else{
			//still deactivated
			return false;
		}
	}
		
	//Make sure start is 0,1,2 for yandex
	if( ! stristr($page, '1995') ){
		$page = 0;
		$startTxt = "&startat=";
	}else{
		$page = str_replace('1995', '', $page);
		$startNum = $page * 20;
		$startTxt = "&startat=$startNum";
	}
	 
	echo '<br>Trying to call EA for new links start from page:' . $page;
	$keywordenc = urlencode ( 'site:ezinearticles.com '. trim($keyword)   );
	
	//StartPage Method
	$startPageBloked='yes';
	if( trim($startPageBloked)  != 'yes' ){
		
		echo '<br>Using startPage method...';
		
		if($page == 0){
			$curlurl="https://ixquick.com/do/asearch?hmb=1&cat=web&cmd=process_search&language=english&engine0=v1all&query=$keywordenc&abp=1&nj=0&pg=0";
		}else{
			
			$qid = get_post_meta($camp->camp_id,'qid_'.md5($keyword) ,1);
			 
			//if time below one hour use it
			 	 
				if(trim($qid) == '') $qid = 'LJLOPQMPQRKR461GJWRGSA';
				
				$qid_server  = get_post_meta($camp->camp_id,'qid_'.md5($keyword).'_server' ,1);
				
				if(trim($qid_server) !=''){
					$qid_server = $qid_server.'.';
				} 
				
				$curlurl="https://{$qid_server}ixquick.com/do/search?cmd=process_search&language=english&qid=$qid&rcount=3&rl=NONE&abp=1&query=$keywordenc&cat=web$startTxt&nj=0";
				
		}
		 
		  echo '<br>startpage url:'.$curlurl;
		
		 //curl get
		 $x='error';
		 curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
		 curl_setopt($this->ch, CURLOPT_URL, trim($curlurl));
		 curl_setopt($this->ch,CURLOPT_COOKIE,'preferences=design_typeEEE1N1Nlang_homepageEEEs/air/eng/N1Nenable_post_methodEEE0N1NsslEEE1N1Nlanguage_uiEEEenglishN1Ndisable_open_in_new_windowEEE1N1Nnum_of_resultsEEE100N1NlanguageEEEenglishN1Ngeo_mapEEE1N1N');
		 $exec=$this->curl_exec_follow($this->ch);
		 $x=curl_error($this->ch);
		 
		  
		//no content and 302 header exists
		if(trim($exec) == ''){
			  echo '<br>Empty results from startpage with possible curl error '.$x;
			return false;
		}
			
		if(stristr($exec, 'no web results')   ){
			  echo '<br>No results found';
			$this->deactivate_key($camp->camp_id, $keyword);
			return false;
		}
			
		$exec = str_replace('&amp;', '&', $exec );
		
		if(stristr($exec, 'proxy service to reach Startpage')  || stristr($exec, 'educe abuse and improve')  ){
			  echo '<br>StartPage has this server ip blacklisted disabling using this method and using another one...';
			update_post_meta($camp->camp_id,'startPageBloked','yes');
			$startPageBloked = 'yes';
		}
		
		// Articles links
		preg_match_all("{<a href='(http://ezinearticles.com.*?)'}s", $exec , $matchsArr);
		$rawUrls = $matchsArr[1];
		$foundUrls = array();
	 		
		// Verify valid article link 	
		foreach ($rawUrls as $ezineUrl){
			
			if(trim($ezineUrl) != '' && stristr($ezineUrl, 'ezinearticles.com') ){
				$foundUrls[] = $ezineUrl;
			}
			
		}
		
	}else{
		 // echo '<br>startPage method is not suitable..';
	}//end startPage Method
	
	//DuckDuckGo method
	if( $startPageBloked == 'yes'){
		  echo '<br>Using DuckDuckGo method...';
		$agent = $this->randomUserAgent();
		  echo '<br>Agent:'.$agent;
		//load home page
		$headers = array();
		$headers[] = "Host: duckduckgo.com";
		$headers[] = "User-Agent: ".$agent;
		$headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
		$headers[] = "Accept-Language: en-US,en;q=0.5";
		$headers[] = "Referer: https://duckduckgo.com/";
		$headers[] = "Connection: keep-alive";
		$headers[] = "Upgrade-Insecure-Requests: 1";
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
		
		
 
		// Prepare url
		if($page == 0){
			
			//get the d.js link
			$duckUrl = "https://duckduckgo.com/?q=$keywordenc" . "&t=h_&ia=web" ;
			echo '<br>DuckURL:'.$duckUrl;

			//first page no s var is needed
			curl_setopt($this->ch, CURLOPT_URL, $duckUrl);
			
			//curl get
			$x='error';
			curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
			curl_setopt($this->ch, CURLOPT_URL, trim($duckUrl));
			$exec=curl_exec($this->ch);
			$x=curl_error($this->ch);
		 
			if(stristr($exec, 'd.js')){
				preg_match( "{'(/d.js.*?)'}" , $exec , $djsMatchs);
				$djs = $djsMatchs[1];
				if(stristr($djs, 'd.js')){
					$djs = 'https://duckduckgo.com'.$djs;
				}else{
					echo '<br>d.js extraction failed.';
					echo $exec.$x;
				}
				 
				
			}else{
				echo '<br>No d.js found...';
				echo $exec.$x;
			}
			 
		}else{
			
			//get d.js link
			$djs = get_post_meta( $camp->camp_id , 'duckDuckGoDjs',1);
		}
		
		echo '<br>djs:'.$djs;
		
		if(stristr($djs, 'd.js')){
 
			//curl get
			$x='error';
			curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
			curl_setopt($this->ch, CURLOPT_URL, trim($djs));
			$exec = $this->curl_exec_follow($this->ch);
			
		}else{
			$exec = '';
		}
		 
		if(stristr($exec,'d.js') ){
			
			preg_match( '{"(/d.js.*?)"}' , $exec , $djsMatchs);
			$newDjs = $djsMatchs[1];
		 	
			if( stristr($newDjs, 'd.js') ){
				  echo '<br>Next d.js for DuckDuckGo is '.$newDjs;
				update_post_meta( $camp->camp_id , 'duckDuckGoDjs','https://duckduckgo.com'.$newDjs);
			}
			
		}
		
		//Extracting ezine urls
		preg_match_all('{"(http://ezinearticles.com/.*?)"}', $exec,$urlsMatchs);
		$foundUrls = array_unique( $urlsMatchs[1] );
	}
	
	
	// No links? return if yes	
	if(count($foundUrls) == 0 ){
		echo '<br> no matching results found for this keyword';
		$query = "update {$this->wp_prefix}automatic_articles_keys set page_num = '-1'  where keyword = '$keyword' and camp_id  = '$camp->camp_id'";
		$this->db->query ( $query );

		//deactivate for 60 minutes
		$this->deactivate_key($camp->camp_id, $keyword);
		return false;
	}
		
	// Report links count
	echo '<br>Articles links got from EA:' . count ( $foundUrls );
	$this->log ( 'links found', count ( $foundUrls ) . ' New Links added from ezine articles to post articles from' );
	
	
	echo '<ol>';
	$i = 0;
	foreach ( $foundUrls as $link ) {

		$link =  urldecode($link);
		
		// verify id in link
		echo '<li>Link:'.($link);
		$link_url = $link;
			
		if (stristr ( $link, 'id=' )) {
				
			// verify uniqueness
			if( $this->is_execluded($camp->camp_id, $link_url) ){
				  echo '<-- Execluded';
				continue;
			}

			if ( ! $this->is_duplicate($link_url) )  {
				
				$title = '';
				$cache = '';
				
				// cache link
				$urlEncoded = urlencode($link_url);
				
				$query = "insert into {$this->wp_prefix}automatic_articles_links (link,keyword,page_num,title,bing_cache) values('$link' ,'$keyword','$page','$title','')";
				$this->db->query ( $query );
				
				
				$freshlinks = 1;
				
				
			} else {
				  echo ' <- duplicated <a href="'.get_edit_post_link($this->duplicate_id).'">#'.$this->duplicate_id.'</a>';
			}
				
			  echo '</li>';

			// incrementing i
				
		} // if contain id
		$i ++;
	} // foreach link

	  echo '</ol>';
		
	// updating page num
	$page = $page + 1;
	$pageDisplayed = $page + 1;
	
	
	if($startPageBloked != 'yes'){
		//check if this page exists
		if(  stristr($exec, "value='$pageDisplayed'" ) && $page <11){
			  echo '<br>Next Page exists starts at: '.$page;
		
			//extracting qid
			preg_match('{qid" value="(.*?)" }s', $exec,$qMatches);
			$qid = $qMatches[1];
		
			//extract server
			preg_match('{<form action="https://(.*?).startpage.com/do/search}', $exec,$serverMatchs);
			$server = ($serverMatchs[1]);
		
			if(trim($qid) !='')
				update_post_meta($camp->camp_id, 'qid_'.md5($keyword), $qid);
			update_post_meta($camp->camp_id, 'qid_'.md5($keyword).'_server', $server );
		
			  echo '<-- qid:'.$qid.' @:'.$server;
		
		}else{
			//  echo $exec;
			  echo '<br>Last page reached resetting search index...';
				
			$page = -1;
			$this->deactivate_key($camp->camp_id, $keyword);
		
		}
		
	}else{
		
		//DuckDuckGo check next page
		
		if( isset($newDjs) && stristr($newDjs, 'd.js') ){
			//valid
		}else{

			echo '<br>No next DuckDuckGo page';
			delete_post_meta( $camp->camp_id , 'duckDuckGoDjs' );
			$page = -1;
			$this->deactivate_key($camp->camp_id, $keyword);
			
		}
		
		
	}
		
	if($page != -1)
	$page= "1995$page";
	
	$query = "update {$this->wp_prefix}automatic_articles_keys set page_num = $page  where keyword = '$keyword' and camp_id  = '$camp->camp_id' ";
	$this->db->query ( $query );
		
	//last page check


	return;
}
	
/*
 * ---* articlebase process camp ---
 */
function articlebase_get_post($camp) {
	
	$keywords = $camp->camp_keywords;
	$keywords = explode ( ",", $keywords );

	foreach ( $keywords as $keyword ) {
			
		$keyword = trim($keyword);
			
		if (trim ( $keyword ) != '') {
				
				
			//update last keyword
			update_post_meta($camp->camp_id, 'last_keyword', trim($keyword));

			// check if keyword exhausted to skip
			$query = "select * from {$this->wp_prefix}automatic_articles_keys where keyword = '$keyword' and camp_id='$camp->camp_id'";
			$key = $this->db->get_results ( $query );
			$key = $key [0];

				
			// process feed
			  echo '<br><b>Getting article for Keyword:</b>' . $keyword;
				
			// get links to fetch and post on the blogs
			$query = "select * from {$this->wp_prefix}automatic_articles_links where keyword = '$keyword' and status =0 and link like '%ezinearticles.com%'";
			$links = $this->db->get_results ( $query );
				
			// when no links available get some links
			if (count ( $links ) == 0) {

				$this->article_base_getlinks ( $keyword, $camp );
				// get links to fetch and post on the blogs
				$query = "select * from {$this->wp_prefix}automatic_articles_links where keyword = '$keyword' and status =0 and link like '%ezinearticles.com%'";
				$links = $this->db->get_results ( $query );
			}
				
			// if no links then return
			if (count ( $links ) != 0) {

				foreach ( $links as $link ) {
						
					// updating status of the link to posted or 1
					$query = "update {$this->wp_prefix}automatic_articles_links set status = '1' where id = '$link->id'";
					$this->db->query ( $query );
						
					// processing page and getting content
					$url = ($link->link) ;
					$title = $link->title;

					echo '<br>Processing Article :' . urldecode($url);
					
					//duplicaate check
					if($this->is_duplicate($url)){
						echo ' <- duplicated <a href="'.get_edit_post_link($this->duplicate_id).'">#'.$this->duplicate_id.'</a>';
						continue;
					}

					$binglink =  "http://webcache.googleusercontent.com/search?q=cache:".urlencode($url);
					echo '<br>Cache link:'.$binglink;

					$headers = array();
					curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
					
					
					curl_setopt ( $this->ch, CURLOPT_HTTPGET, 1 );
					curl_setopt ( $this->ch, CURLOPT_URL, trim (  ( $binglink ) ) );
					curl_setopt ( $this->ch, CURLOPT_REFERER, 'http://ezinearticles.com' );
					$exec = curl_exec ( $this->ch );
					$x = curl_error($this->ch);
 
					$cacheLoadSuccess = false; // success flag
					
					if(stristr($exec,'comments')){
						//valid google cache
						  echo '<br>Successfully loaded the page from Google Cache';
						$cacheLoadSuccess = true;
					}else{
						
						// Google translate
						  echo '<br>Google cache failed Loading using GtranslateProxy...';
						
						require_once 'inc/proxy.GoogleTranslate.php';
						
						try {
							
							$GoogleTranslateProxy = new GoogleTranslateProxy($this->ch);
							$exec = $GoogleTranslateProxy->fetch($url);
						
						} catch (Exception $e) {
						
							  echo '<br>ProxyViaGoogleException:'.$e->getMessage();
						
						}
						
						// Validate Google Translate Proxy						
						if(stristr($exec,'comments')){
							//valid google cache
							  echo '<br>Successfully loaded the page from GTranslateProxy';
							$cacheLoadSuccess = true;
							
							// pre Gtranslate adaption
							$exec = str_replace( 'article-content>','article-content">',$exec);
							$exec = str_replace('<div id=article-resource', '<div id="article-resource', $exec);
							$exec = str_replace('rel=author', 'rel="author', $exec);
							
						} 
						 	
						if(!$cacheLoadSuccess){
							
							// Direct call
							  echo '<br>Google cache didnot return valid result direct call to ezine '.$x;
							curl_setopt ( $this->ch, CURLOPT_URL, trim (  ( urldecode( $url ) ) ) );
							curl_setopt ( $this->ch, CURLOPT_REFERER, 'http://ezinearticles.com' );
							$exec = curl_exec ( $this->ch );
							 
	
							if(stristr($exec, 'comments')){
								  echo '<br>Ezinearticles returned the article successfully ';
							}else{
								if(stristr($exec,'excessive amount')){
									  echo '<br>Ezinearticles says there is excessive amount of traffic';
									return false ;
								}else{
									  echo '<br>Ezinearticles did not return the article we called... Will die now and try with a new article another time. ';
									return false ;
								}
							}
							
						}
 	
					}
						

						
					// extracting articles
					$arr = explode ( 'article-content">', $exec );
					$lastpart = $arr [1];
						
					unset ( $arr );
					$newarr = explode ( '<div id="article-resource', $lastpart );
					
						
					$cont =   $newarr [0];
					
					//remove last closing </div>
					$cont = preg_replace('{</div>$}s', '', trim($cont));

					//striping js
					$cont = preg_replace('{<script.*?script>}s', '', $cont);
					$cont = preg_replace('{<div class=["]?mobile-ad-container["]?>.*?</div>}s', '', $cont);

					// get the title <title>Make Money With Google Profit Kits Exposed - Don't Get Ripped Off!</title>
					@preg_match_all ( "{<title>(.*?)</title>}", $exec, $matches, PREG_PATTERN_ORDER );
					@$res = $matches [1];
					@$ttl = $res [0];
						
					if (isset ( $ttl )) {
						$title = $ttl;
					}
						
					// get author name and author link <a href="/?expert=Naina_Jain" rel="author" class="author-name" title="EzineArticles Expert Author Naina Jain"> Naina Jain </a>
					@preg_match_all ( '{<a href=(.*?) rel="author.*?>(.*?)</a>}', $exec, $matches, PREG_PATTERN_ORDER );
					
					$author_link =  $matches [1] [0];
					
					// remove "
					$author_link = str_replace('"', '', $author_link);
					
					// fix from translation url
					if (stristr($author_link, 'translate')){
						
						$authorParts = explode('u=', $author_link);
						$author_link = $authorParts[1] ;
						$author_link = preg_replace('{&.*}', '', $author_link );
						
						
					}
					
					// fix relative linking
					if( ! stristr( $author_link , 'ezinearticles' )){
						$author_link = 'http://ezinearticles.com' . $author_link;
					}
					
					$author_name = trim ( $matches [2] [0] );
						
					$ret ['cont'] = $cont;
					$ret ['title'] = $title;
					$ret ['original_title'] = $title;
					$ret ['source_link'] = ($url);
					$ret ['author_name'] = $author_name;
					$ret ['author_link'] = $author_link;
					$ret ['matched_content'] = $cont;
					$this->used_keyword=$link->keyword;
					if( trim($ret['cont']) == '' )   echo ' exec:'.$exec;
						
						
					return $ret;
				} // foreach link
			} // if count(links)
				
		} // if keyword not ''
	} // foreach keyword
}

}