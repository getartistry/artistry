<?php
/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

/**
 * FILE CONTENTS:
 *  1. BASIC FUNCTIONS
 *  2. FILE SYSTEM SPECIFIC WRAPPERS
 *  3. TAXONOMY AND TERM SPECIFIC
 *  4. BACK-END SPECIFIC
 *  5. EXPORT IMPORT
 *  6. NON-AJAX RESULTS
 *  7. FRONT-END
*/

//----------------------------------------------------------------------------------------------------------------------
// 1. BASIC FUNCTIONS
//----------------------------------------------------------------------------------------------------------------------

if (!function_exists('wpd_is_wp_version')) {
    function wpd_is_wp_version($operator = '>', $version = '4.5') {
        global $wp_version;

        return version_compare($wp_version, $version, $operator);
    }
}

if (!function_exists('wpd_is_wp_older')) {
    function wpd_is_wp_older($version = '4.5') {
        return wpd_is_wp_version('<', $version);
    }
}

if (!function_exists('wpd_is_wp_newer')) {
    function wpd_is_wp_newer($version = '4.5') {
        return wpd_is_wp_version('>', $version);
    }
}

if (!function_exists('wpd_get_terms')) {
    function wpd_get_terms($args = array()) {
        if ( wpd_is_wp_older('4.5') ) {
            return get_terms($args['taxonomy'], $args);
        } else {
            return get_terms($args);
        }
    }
}


if (!function_exists("wd_get_inner_substring")) {
    /**
     * Get the string from inbetween delimiters
     *
     * @param $string
     * @param $delim
     * @return string
     */
    function wd_get_inner_substring($string, $delim) {

        $string = explode($delim, $string, 3); // also, we only need 2 items at most

        return isset($string[1]) ? $string[1] : '';
    }
}

if (!function_exists("wpd_comma_separated_to_array")) {
    /**
     * @param $string - Input string to convert to array
     * @param string $separator - Separator to separate by (default: ,)
     *
     * @return array
     */
    function wpd_comma_separated_to_array($string, $separator = ',') {
        //Explode on comma
        $vals = explode($separator, $string);

        //Trim whitespace
        foreach ($vals as $key => $val) {
            $vals[$key] = trim($val);
        }
        //Return empty array if no items found
        //http://php.net/manual/en/function.explode.php#114273
        return array_diff($vals, array(""));
    }
}

if (!function_exists("wd_closetags")) {
    /**
     * Close unclosed HTML tags
     *
     * @param $html
     * @return string
     */
    function wd_closetags ( $html ) {
        $unpaired = array('hr', 'br', 'img');

        // put all opened tags into an array
        preg_match_all ( "#<([a-z]+)( .*)?(?!/)>#iU", $html, $result );
        $openedtags = $result[1];
        // remove unpaired tags
        if (is_array($openedtags) && count($openedtags)>0) {
            foreach ($openedtags as $k=>$tag) {
                if (in_array($tag, $unpaired))
                    unset($openedtags[$k]);
            }
        } else {
	        // Replace a possible un-closed tag from the end, 30 characters backwards check
	        $html = preg_replace('/(.*)(\<[a-zA-Z].{0,30})$/', '$1', $html);
            return $html;
        }
        // put all closed tags into an array
        preg_match_all ( "#</([a-z]+)>#iU", $html, $result );
        $closedtags = $result[1];
        $len_opened = count ( $openedtags );
        // all tags are closed
        if( count ( $closedtags ) == $len_opened ) {
	        // Replace a possible un-closed tag from the end, 30 characters backwards check
	        $html = preg_replace('/(.*)(\<[a-zA-Z].{0,30})$/', '$1', $html);
            return $html;
        }
        $openedtags = array_reverse ( $openedtags );
        // close tags
        for( $i = 0; $i < $len_opened; $i++ ) {
            if ( !in_array ( $openedtags[$i], $closedtags ) ) {
                $html .= "</" . $openedtags[$i] . ">";
            } else {
                unset ( $closedtags[array_search ( $openedtags[$i], $closedtags)] );
            }
        }
	    // Replace a possible un-closed tag from the end, 30 characters backwards check
	    $html = preg_replace('/(.*)(\<[a-zA-Z].{0,30})$/', '$1', $html);
        return $html;
    }
}

if (!function_exists("wd_mysql_escape_mimic")) {
	/**
	 * Mimics the old mysql_escape function
	 *
	 * @internal
	 * @param $inp
	 * @return mixed
	 */
	function wd_mysql_escape_mimic($inp) {
		if(is_array($inp))
			return array_map(__METHOD__, $inp);

		if(!empty($inp) && is_string($inp)) {
			return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
		}

		return $inp;
	}
}

if (!function_exists("wd_substr_at_word")) {
    /**
     * Substring cut off at word endings
     *
     * @param $text
     * @param $length
     * @param $tolerance
     * @return string
     */
    function wd_substr_at_word($text, $length, $tolerance = 8) {

        if ( function_exists("mb_strlen") &&
             function_exists("mb_strrpos") &&
             function_exists("mb_substr")
        ) {
            $fn_strlen = "mb_strlen";
            $fn_strrpos = "mb_strrpos";
            $fn_substr = "mb_substr";
        } else {
            $fn_strlen = "strlen";
            $fn_strrpos = "strrpos";
            $fn_substr = "substr";
        }

        if ($fn_strlen($text) <= $length) return $text;

        $s = $fn_substr($text, 0, $length);
        $s = $fn_substr($s, 0, $fn_strrpos($s, ' '));

        // In case of a long mash-up, it will not let overflow the length
        if ( $fn_strlen($s) > ($length + $tolerance) )
            return $fn_substr($s, 0, ($length + $tolerance));

        return $s;
  }
}

if (!function_exists("wd_in_array_r")) {
    /**
     * Recursive in_array
     *
     * @param $needle
     * @param $haystack
     * @param bool $strict
     * @return bool
     */
    function wd_in_array_r($needle, $haystack, $strict = false) {
      foreach ($haystack as $item) {
          if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && wd_in_array_r($needle, $item, $strict))) {
              return true;
          }
      }
  
      return false;
  }
}

if (!function_exists("wd_current_page_url")) {
    /**
     * Returns the current page url
     *
     * @return string
     */
    function wd_current_page_url() {
        $pageURL = 'http';

        $port = !empty($_SERVER["SERVER_PORT"]) ? $_SERVER["SERVER_PORT"] : 80;

        $server_name = !empty($_SERVER["SERVER_NAME"]) ? $_SERVER["SERVER_NAME"] : "";
        $server_name = empty($server_name) && !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $server_name;

        if( isset($_SERVER["HTTPS"]) ) {
            if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
        }
        $pageURL .= "://";
        if ($port != "80") {
            $pageURL .= $server_name.":".$port.$_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $server_name.$_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }
} 
if (!function_exists("wpdreams_hex2rgb")) {
    /**
     * HEX to RGB string conversion
     *
     * Works both 3-6 lengths, with or without hash tags
     *
     * @param $color
     * @return bool|string
     * @uses wpdreams_rgb2hex()
     */
    function wpdreams_hex2rgb($color) {
      if (strlen($color)>7)
          $color = wpdreams_rgb2hex($color);
      if (strlen($color)>7)
          return $color;
      if (strlen($color)<3) return "0, 0, 0";
      if ($color[0] == '#')
          $color = substr($color, 1);
      if (strlen($color) == 6)
          list($r, $g, $b) = array($color[0].$color[1],
                                   $color[2].$color[3],
                                   $color[4].$color[5]);
      elseif (strlen($color) == 3)
          list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
      else
          return false;
      $r = hexdec($r); $g = hexdec($g); $b = hexdec($b); 
      return $r.", ".$g.", ".$b;
  }  
}

if (!function_exists("wpdreams_rgb2hex")) {
    /**
     * RGB to HEX string converter
     *
     * @param $color
     * @return string
     */
    function wpdreams_rgb2hex($color)
    {
        if (strlen($color)>7) {
          preg_match("/.*?\((\d+), (\d+), (\d+).*?/", $color, $c);
          if (is_array($c) && count($c)>3) {
             $color = "#".sprintf("%02X", $c[1]);
             $color .= sprintf("%02X", $c[2]);
             $color .= sprintf("%02X", $c[3]);
          }
        }
        return $color;
    }
} 

if (!function_exists("get_content_w")) {
    /**
     * Gets the post content, manually filtered
     *
     * @deprecated
     * @param $id
     * @return mixed
     */
    function get_content_w($id) {
      $my_postid = $id;
      $content_post = get_post($my_postid);
      $content = $content_post->post_content;
      $content = apply_filters('the_content', $content);
      $content = str_replace(']]>', ']]&gt;', $content);
      return $content;
    }
}

if (!function_exists("wpdreams_utf8safeencode")) {
    /**
     * UTF-8 safe encoding
     *
     * @param $s
     * @param $delimiter
     * @return string
     */
    function wpdreams_utf8safeencode($s, $delimiter)
  {
    $convmap= array(0x0100, 0xFFFF, 0, 0xFFFF);
    return $delimiter."_".base64_encode(mb_encode_numericentity($s, $convmap, 'UTF-8'));
  }  
}

if (!function_exists("wpdreams_utf8safedecode")) {
    /**
     * UTF-8 safe decoding
     *
     * @param $s
     * @param $delimiter
     * @return string
     */
    function wpdreams_utf8safedecode($s, $delimiter)
  {
    if (strpos($s, $delimiter)!=0) return $s;
    $convmap= array(0x0100, 0xFFFF, 0, 0xFFFF);
    $_s = explode($delimiter."_", $s);
    return base64_decode(mb_decode_numericentity($s[1], $convmap, 'UTF-8'));
  }  
}

if (!function_exists("postval_or_getoption")) {
    /**
     * Returns post value if set, option value otherwise
     *
     * @param $option
     * @return mixed
     */
    function postval_or_getoption($option)
  {
    if (isset($_POST) && isset($_POST[$option]))
      return $_POST[$option];
    return get_option($option);
  }  
}

if (!function_exists("wpdreams_get_image_from_content")) {
    /**
     * Gets an image from the HTML content
     *
     * @param $content
     * @param int $number
     * @return bool|string
     */
    function wpdreams_get_image_from_content($content, $number = 0) {
        if ($content == "" || !class_exists('domDocument'))
            return false;

        $dom = new domDocument;
        if ( function_exists('mb_convert_encoding') )
            @$dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
        else
            @$dom->loadHTML($content);
        $dom->preserveWhiteSpace = false;
        @$images = $dom->getElementsByTagName('img');
        if ($images->length > 0) {
            if ($images->length > $number) {
                $im = $images->item($number)->getAttribute('src');
            } else {
                $im = $images->item(0)->getAttribute('src');
            }
            return $im;
        } else {
            return false;
        }
    }
}

if (!function_exists("wpdreams_on_backend_page")) {
    /**
     * Checks if the current page is back-end page
     *
     * @param $pages
     * @return bool
     */
    function wpdreams_on_backend_page($pages) {
        if (isset($_GET) && isset($_GET['page'])) {
            return in_array($_GET['page'] ,$pages);
        }
        return false;
    }
}

/* Extra Functions */
if (!function_exists("wd_isEmpty")) {
    /**
     * @param $v
     * @return bool
     */
    function wd_isEmpty($v) {
  	if (trim($v) != "")
  		return false;
  	else
  		return true;
  }
}

if (!function_exists("wpdreams_on_backend_post_editor")) {
    /**
     * Checks if current page is the post editor
     *
     * @return bool
     */
    function wpdreams_on_backend_post_editor() {
        $current_url = wd_current_page_url();
        return (strpos($current_url, 'post-new.php')!==false ||
            strpos($current_url, 'post.php')!==false);
    }
}

if (!function_exists("wpdreams_get_blog_list")) {
    /**
     * Gets all the blogs from the multisite network
     *
     * @param int $start
     * @param int $num
     * @param bool $ids_only
     * @return array
     */
    function wpdreams_get_blog_list( $start = 0, $num = 10, $ids_only = false ) {
  
  	global $wpdb;
    if (!isset($wpdb->blogs)) return array();
  	$blogs = $wpdb->get_results( $wpdb->prepare("SELECT blog_id, domain, path FROM $wpdb->blogs WHERE site_id = %d ORDER BY registered DESC", $wpdb->siteid), ARRAY_A );

	if ($ids_only) {
		foreach ( (array) $blogs as $details ) {
			$blog_list[ $details['blog_id'] ] = $details['blog_id'];
			//$blog_list[ $details['blog_id'] ]['postcount'] = $wpdb->get_var( "SELECT COUNT(ID) FROM " . $wpdb->get_blog_prefix( $details['blog_id'] ). "posts WHERE post_status='publish' AND post_type='post'" );
		}
	} else {
		foreach ( (array) $blogs as $details ) {
			$blog_list[ $details['blog_id'] ] = $details;
			//$blog_list[ $details['blog_id'] ]['postcount'] = $wpdb->get_var( "SELECT COUNT(ID) FROM " . $wpdb->get_blog_prefix( $details['blog_id'] ). "posts WHERE post_status='publish' AND post_type='post'" );
		}
	}

  	unset( $blogs );
  	$blogs = $blog_list;
  
  	if ( false == is_array( $blogs ) )
  		return array();
  
  	if ( $num == 'all' )
  		return array_slice( $blogs, $start, count( $blogs ) );
  	else
  		return array_slice( $blogs, $start, $num );
  }
}

if (!function_exists("wpd_mem_convert")) {
    /**
     * Converts number to memory value with units
     *
     * @param $size
     * @return string
     */
    function wpd_mem_convert($size) {
        if ( $size <= 0 ) return "0B";
        $unit=array('B','KB','MB','GB','TB','PB');
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    }
}


//----------------------------------------------------------------------------------------------------------------------
// 2. FILE SYSTEM SPECIFIC WRAPPERS
//----------------------------------------------------------------------------------------------------------------------
if (!function_exists('wpd_mtime')) {
    /**
     * Checks the last modification time on file
     * NOTE: Use this function in own plugin pages/actions/ajax only! Loading the WP_Filesystem(); can cause compatibility issues
     *
     * @param $file
     * @return int|bool
     */
    function wpd_mtime($file) {
        global $wp_filesystem;
        // Try initializing the file system without inclusion
        if ( (!isset($wp_filesystem) || $wp_filesystem == null) && function_exists('WP_Filesystem') ) {
            WP_Filesystem();
        }
        // Did it fail?
        if ( !isset($wp_filesystem) || $wp_filesystem == null ) {
            /* any problems and we exit */
            return filemtime($file);
        }
        return $wp_filesystem->mtime($file);
    }
}

if (!function_exists('wpd_is_file')) {
    /**
     * Checks if the file exists and if it is a regular file
     * NOTE: Use this function in own plugin pages/actions/ajax only! Loading the WP_Filesystem(); can cause compatibility issues
     *
     * @param $file
     * @return bool
     */
    function wpd_is_file($file) {
        global $wp_filesystem;
        // Try initializing the file system without inclusion
        if ( (!isset($wp_filesystem) || $wp_filesystem == null) && function_exists('WP_Filesystem') ) {
            WP_Filesystem();
        }
        // Did it fail?
        if ( !isset($wp_filesystem) || $wp_filesystem == null ) {
            /* any problems and we exit */
            return is_file($file);
        }
        return $wp_filesystem->is_file($file);
    }
}

if (!function_exists('wpd_is_dir')) {
    /**
     * Checks if the directory exists and if it is indeed a directory
     * NOTE: Use this function in own plugin pages/actions/ajax only! Loading the WP_Filesystem(); can cause compatibility issues
     *
     * @param $file
     * @return bool
     */
    function wpd_is_dir($file) {
        global $wp_filesystem;
        // Try initializing the file system without inclusion
        if ( (!isset($wp_filesystem) || $wp_filesystem == null) && function_exists('WP_Filesystem') ) {
            WP_Filesystem();
        }
        // Did it fail?
        if ( !isset($wp_filesystem) || $wp_filesystem == null ) {
            /* any problems and we exit */
            return is_dir($file);
        }
        return $wp_filesystem->is_dir($file);
    }
}

if (!function_exists("wpd_get_file")) {
    /**
     * Gets file contents with the use of WordPress file API with a fallback to file_get_contents()
     * NOTE: Use this function in own plugin pages/actions/ajax only! Loading the WP_Filesystem(); can cause compatibility issues
     *
     * @param $filename
     * @param $contents
     * @return bool
     */
    function wpd_get_file($filename) {
        // Replace double
        $filename = str_replace(array('\\\\', '//'), array('\\', '/'), $filename);
        global $wp_filesystem;

        // Try initializing the file system without inclusion
        if ( (!isset($wp_filesystem) || $wp_filesystem == null) && function_exists('WP_Filesystem') ) {
            WP_Filesystem();
        }

        // Did it fail?
        if ( !isset($wp_filesystem) || $wp_filesystem == null ) {
            /* any problems and we exit */
            return @file_get_contents($filename);
        }

        // All went well, return
        return $wp_filesystem->get_contents( $filename );
    }
}

if (!function_exists("wpd_put_file")) {
    /**
     * Writes to a file with the use of WordPress file API with a fallback to file_put_contents()
     * NOTE: Use this function in own plugin pages/actions/ajax only! Loading the WP_Filesystem(); can cause compatibility issues
     *
     * @param $filename
     * @param $contents
     * @return bool
     */
    function wpd_put_file($filename, $contents) {
        // Replace double
        $filename = str_replace(array('\\\\', '//'), array('\\', '/'), $filename);
        global $wp_filesystem;

        // Try initializing the file system without inclusion
        if ( (!isset($wp_filesystem) || $wp_filesystem == null) && function_exists('WP_Filesystem') ) {
            WP_Filesystem();
        }

        // Did it fail?
        if ( !isset($wp_filesystem) || $wp_filesystem == null ) {
            /* any problems and we exit */
            return @file_put_contents($filename, $contents) === false ? false : true;
        }

        // It worked, use it!
        if ( defined('FS_CHMOD_FILE') ) {
            if (!$wp_filesystem->put_contents($filename, $contents, FS_CHMOD_FILE)) {
                return @file_put_contents($filename, $contents) === false ? false : true;
            }
        } else {
            if (!$wp_filesystem->put_contents($filename, $contents)) {
                return @file_put_contents($filename, $contents) === false ? false : true;
            }
        }

        // All went well, return
        return true;
    }
}

if (!function_exists("wpd_rmdir")) {
    /**
     * Deletes a directory
     * NOTE: Use this function in own plugin pages/actions only! Loading the WP_Filesystem(); can cause compatibility issues
     *
     * @param $dir
     * @param $recursive
     * @return bool
     */
    function wpd_rmdir($dir, $recursive = false, $force = false) {
        global $wp_filesystem;

        if ( $force ) {
            wpd_rec_rmdir($dir);
            return true;
        }

        // Try initializing the file system without inclusion
        if ( (!isset($wp_filesystem) || $wp_filesystem == null) && function_exists('WP_Filesystem') ) {
            WP_Filesystem();
        }

        // Did it fail?
        if ( !isset($wp_filesystem) || $wp_filesystem == null ) {
            // $recursive is not supported in the default php rmdir function
            return rmdir( $dir );
        }

        $wp_filesystem->rmdir($dir, $recursive);
    }
}

if ( !function_exists('wpd_rec_rmdir') ) {
    function wpd_rec_rmdir($dirPath) {
        if (!is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                wpd_rec_rmdir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }
}

if (!function_exists("asp_put_file")) {
    /**
     * Writes to a the pre-defined upload path
     * NOTE: Use this function in own plugin pages/actions only! Loading the WP_Filesystem(); can cause compatibility issues
     *
     * @param $filename
     * @param $contents
     * @return bool
     * @uses wd_asp()->upload_path
     * @uses wpd_put_file()
     */
    function asp_put_file($filename, $contents) {
        return wpd_put_file( wd_asp()->upload_path . $filename , $contents);
    }
}

if (!function_exists("wpd_del_file")) {
    /**
     * Deletes the file in the path
     * NOTE: Use this function in own plugin pages/actions only! Loading the WP_Filesystem(); can cause compatibility issues
     *
     * @param $filename
     * @return bool
     */
    function wpd_del_file($filename) {
        global $wp_filesystem;

        // Try initializing the file system without inclusion
        if ( (!isset($wp_filesystem) || $wp_filesystem == null) && function_exists('WP_Filesystem') ) {
            WP_Filesystem();
        }

        // Did it fail?
        if ( !isset($wp_filesystem) || $wp_filesystem == null ) {
            /* any problems and we exit */
            return @unlink( $filename );
        }

        $wp_filesystem->delete($filename);
    }
}

if (!function_exists("asp_del_file")) {
    /**
     * Deletes the file in pre-defined upload path
     *
     * @param $filename
     * @return bool
     * @uses wd_asp()->upload_path
     * @uses wpd_del_file()
     */
    function asp_del_file($filename) {
        return wpd_del_file( wd_asp()->upload_path . $filename );
    }
}


//----------------------------------------------------------------------------------------------------------------------
// 3. TAXONOMY AND TERM SPECIFIC
//----------------------------------------------------------------------------------------------------------------------

if (!function_exists("wd_sort_terms_hierarchicaly")) {
    /**
     * Recursively sort an array of taxonomy terms hierarchically. Child categories will be
     * placed under a 'children' member of their parent term. Handles missing parent categories as well.
     *
     * @param Array   $cats     taxonomy term objects to sort, use get_terms(..)
     * @param Array   $into     result array to put them in
     * @param integer $parentId the current parent ID to put them in
     * @param integer $depth the current recursion depth
     */
    function wd_sort_terms_hierarchicaly(Array &$cats, Array &$into, $parentId = 0, $depth = 0) {
        foreach ($cats as $i => $cat) {
            if ($cat->parent == $parentId) {
                $into[$cat->term_id] = $cat;
                unset($cats[$i]);
            }
        }

        foreach ($into as $k => $topCat) {
            $into[$k]->children = array();
            wd_sort_terms_hierarchicaly($cats, $into[$k]->children, $topCat->term_id, $depth + 1);
        }

        // Use a copy to go through, as the original is modified
        $copy_cats = $cats;

        // Try the remaining - the first parent might be excluded
        if (is_array($copy_cats) && $depth == 0) {
            foreach ($copy_cats as $k => $topCat) {
                // This item might not exist in the original array, check it first
                if ( isset($cats[$k]) ) {
                    $cats[$k]->children = array();
                    wd_sort_terms_hierarchicaly($cats, $cats[$k]->children, $topCat->term_id, $depth + 1);
                }
            }
        }

        // Still any remaining for some satanic reason? Put the rest to the end...
        if (is_array($cats) && $depth == 0)
            foreach ( $cats as $i => $cat ) {
                $into[$cat->term_id] = $cat;
                unset( $cats[$i] );
            }

    }
}


if (!function_exists("wd_flatten_hierarchical_terms")) {
	/**
	 * Flattens a hierarchical array of terms into a flat array, marking levels.
	 * Keeps ordering, sets a $cat->level attribute
	 *
	 * @param Array   $cats     Taxonomy term objects to sort, use get_terms(..)
	 * @param Array   $into     Target array
	 * @param int     $level    The current recursion depth
	 */
	function wd_flatten_hierarchical_terms(Array &$cats, Array &$into, $level = 0) {
		foreach ($cats as $i => $cat) {
			$cat->level = $level;
			$into[] = $cat;
			if ( isset($cat->children) && count($cat->children) > 0 ) {
				wd_flatten_hierarchical_terms( $cat->children, $into, $level + 1 );
			}
		}

		// We don't need the children structure
		foreach ($into as $cat) {
			unset($cat->children);
		}
	}
}


//----------------------------------------------------------------------------------------------------------------------
// 4. BACK-END SPECIFIC
//----------------------------------------------------------------------------------------------------------------------

if (!function_exists("w_isset_def")) {
    function w_isset_def(&$v, $d)
    {
        if (isset($v)) return $v;
        return $d;
    }
}

if (!function_exists("w_get_custom_fields")) {
    function w_get_custom_fields( $limit = 1000 )
    {
        global $wpdb;
        return $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM " . $wpdb->postmeta . " GROUP BY meta_key LIMIT %d", $limit),
            ARRAY_A
        );
    }
}

if (!function_exists("wd_opt_or_def")) {
    /**
     * Checks if the option is set in the options array, returns default if not.
     *
     * @param $options
     * @param $defaults
     * @param $key
     * @return int
     */
    function wd_opt_or_def($options, $defaults, $key) {
        if ( isset($options[$key]) )
            return $options[$key];
        if ( isset($defaults[$key]) )
            return $defaults[$key];
        return 0;
    }
}

if (!function_exists("wpdreams_parse_params")) {
    /**
     * This method is intended to use on params BEFORE written into the DB
     *
     * @param $params
     * @return mixed
     */
    function wpdreams_parse_params($params) {
        foreach ($params as $k=>$v) {
            $_tmp = explode('classname-', $k);
            if ($_tmp!=null && count($_tmp)>1) {
                ob_start();
                $c = new $v('0', '0', $params[$_tmp[1]]);
                $out = ob_get_clean();
                $params['selected-'.$_tmp[1]] = $c->getSelected();
            }
            $_tmp = null;
            $_tmp = explode('wpdfont-', $k);
            if ($_tmp!=null && count($_tmp)>1) {
                ob_start();
                $c = new $v('0', '0', $params[$_tmp[1]]);
                $out = ob_get_clean();
                $params['import-'.$_tmp[1]] = $c->getImport();

            }
        }
        return $params;
    }
}

if (!function_exists("wpdreams_admin_hex2rgb")) {
    function wpdreams_admin_hex2rgb($color)
    {
        if (strlen($color)>7) return $color;
        if (strlen($color)<3) return "rgba(0, 0, 0, 1)";
        if ($color[0] == '#')
            $color = substr($color, 1);
        if (strlen($color) == 6)
            list($r, $g, $b) = array($color[0].$color[1],
                $color[2].$color[3],
                $color[4].$color[5]);
        elseif (strlen($color) == 3)
            list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
        else
            return false;
        $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);
        return "rgba(".$r.", ".$g.", ".$b.", 1)";
    }
}

if (!function_exists("wpdreams_admin_rgb2hex")) {
    function wpdreams_admin_rgb2hex($color)
    {
        if ($color[0] == '#') return $color;
        if (strlen($color) == 6) return '#'.$color;
        preg_match("/.*?\((.*?),[\s]*(.*?),[\s]*(.*?)[,\)]/", $color, $matches);

        return "#" . dechex($matches[1]) . dechex($matches[2]) . dechex($matches[3]);

    }
}

if (!function_exists("wpdreams_four_to_string")) {
    function wpdreams_four_to_string($data) {
        // 1.Top 2.Bottom 3.Right 4.Left
        preg_match("/\|\|(.*?)\|\|(.*?)\|\|(.*?)\|\|(.*?)\|\|/", $data, $matches);
        // 1.Top 3.Right 2.Bottom 4.Left
        return $matches[1]." ".$matches[3]." ".$matches[2]." ".$matches[4];
    }
}

if (!function_exists("wpdreams_four_to_array")) {
    function wpdreams_four_to_array($data) {
        // 1.Top 2.Bottom 3.Right 4.Left
        preg_match("/\|\|(.*?)\|\|(.*?)\|\|(.*?)\|\|(.*?)\|\|/", $data, $matches);
        // 1.Top 3.Right 2.Bottom 4.Left
        return array(
            "top" => $matches[1],
            "right" => $matches[3],
            "bottom" => $matches[2],
            "left" => $matches[4]
        );
    }
}

if (!function_exists("wpdreams_box_shadow_css")) {
    function wpdreams_box_shadow_css($css) {
        $css = str_replace("\n", "", $css);
        preg_match("/box-shadow:(.*?)px (.*?)px (.*?)px (.*?)px (.*?);/", $css, $matches);
        $ci = $matches[5];
        $hlength = $matches[1];
        $vlength = $matches[2];
        $blurradius = $matches[3];
        $spread = $matches[4];
        $moz_blur = ($blurradius>2)?$blurradius - 2:0;
        if ($hlength==0 && $vlength==0 && $blurradius==0 && $spread==0) {
            echo "box-shadow: none;";
        } else {
            echo "box-shadow:".$hlength."px ".$vlength."px ".$moz_blur."px ".$spread."px ".$ci.";";
            echo "-webkit-box-shadow:".$hlength."px ".$vlength."px ".$blurradius."px ".$spread."px ".$ci.";";
            echo "-ms-box-shadow:".$hlength."px ".$vlength."px ".$blurradius."px ".$spread."px ".$ci.";";
        }
    }
}

if (!function_exists("wpdreams_gradient_css")) {
    function wpdreams_gradient_css($data, $print=true) {

        $data = str_replace("\n", "", $data);
        if ( $data == "" )
            return "";

        preg_match("/(.*?)-(.*?)-(.*?)-(.*)/", $data, $matches);

        if (!isset($matches[1]) || !isset($matches[2]) || !isset($matches[3])) {
            // Probably only 1 color..
            if ($print) echo "background: ".$data.";";
            return "background: ".$data.";";
        }

        $type = $matches[1];
        $deg = $matches[2];
        $color1 = wpdreams_admin_hex2rgb($matches[3]);
        $color2 = wpdreams_admin_hex2rgb($matches[4]);
        $color1_hex = wpdreams_admin_rgb2hex($color1);
        $color2_hex = wpdreams_admin_rgb2hex($color2);

        // Check for full transparency
        preg_match("/rgba\(.*?,.*?,.*?,[\s]*(.*?)\)/", $color1, $opacity1);
        preg_match("/rgba\(.*?,.*?,.*?,[\s]*(.*?)\)/", $color2, $opacity2);
        if (isset($opacity1[1]) && $opacity1[1] == "0" && isset($opacity2[1]) && $opacity2[1] == "0") {
            if ($print) echo "background: transparent;";
            return "background: transparent;";
        }

        ob_start();

        if ($type!='0' || $type!=0) {
            ?>
            background-image: -webkit-linear-gradient(<?php echo $deg; ?>deg, <?php echo $color1; ?>, <?php echo $color2; ?>);
            background-image: -moz-linear-gradient(<?php echo $deg; ?>deg, <?php echo $color1; ?>, <?php echo $color2; ?>);
            background-image: -o-linear-gradient(<?php echo $deg; ?>deg, <?php echo $color1; ?>, <?php echo $color2; ?>);
            background-image: -ms-linear-gradient(<?php echo $deg; ?>deg, <?php echo $color1; ?> 0%, <?php echo $color2; ?> 100%);
            background-image: linear-gradient(<?php echo $deg; ?>deg, <?php echo $color1; ?>, <?php echo $color2; ?>);
        <?php
        } else {
            //radial
            ?>
            background-image: -moz-radial-gradient(center, ellipse cover,  <?php echo $color1; ?>, <?php echo $color2; ?>);
            background-image: -webkit-gradient(radial, center center, 0px, center center, 100%, <?php echo $color1; ?>, <?php echo $color2; ?>);
            background-image: -webkit-radial-gradient(center, ellipse cover,  <?php echo $color1; ?>, <?php echo $color2; ?>);
            background-image: -o-radial-gradient(center, ellipse cover,  <?php echo $color1; ?>, <?php echo $color2; ?>);
            background-image: -ms-radial-gradient(center, ellipse cover,  <?php echo $color1; ?>, <?php echo $color2; ?>);
            background-image: radial-gradient(ellipse at center,  <?php echo $color1; ?>, <?php echo $color2; ?>);
        <?php
        }
        $out = ob_get_clean();
        if ($print) echo $out;
        return $out;
    }
}

if (!function_exists("wpdreams_border_width")) {
    function wpdreams_border_width($css) {
        $css = str_replace("\n", "", $css);
        preg_match("/border:(.*?)px (.*?) (.*?);/", $css, $matches);

        return $matches[1];
    }
}

if (!function_exists("wpdreams_width_from_px")) {
    function wpdreams_width_from_px($css) {
        $css = str_replace("\n", "", $css);
        preg_match("/(.*?)px/", $css, $matches);

        return $matches[1];
    }
}


//----------------------------------------------------------------------------------------------------------------------
// 5. EXPORT IMPORT
//----------------------------------------------------------------------------------------------------------------------

/**
 * Generates exported search instances in serialized base 64 encded format
 *
 * @return array
 */
function asp_get_all_exported_instances() {
    global $wpdb;

    $return = array();

    $search_instances = $wpdb->get_results("SELECT * FROM " . wd_asp()->tables->main, ARRAY_A);
    foreach ($search_instances as $instance)
        $return[$instance['id']] = base64_encode(serialize($instance));
    return $return;
}

/**
 * Get a single exported search instance by ID
 *
 * @param int $id
 * @return bool
 */
function asp_get_exported_instance($id=0) {
    $instances = asp_get_all_exported_instances();
    return isset($instances[$id])?$instances[$id]:false;
}

/**
 * Imports the search instance
 *
 * @param $data
 * @return false on failure, affected rows on success
 */
function asp_import_instances($data) {
    global $wpdb;

    $s_data = json_decode(stripcslashes($data));

    $asp_def = wd_asp()->options;

    $count = 0;

    if (is_array($s_data)) {
        foreach ($s_data as $dec_instance) {
            $_instance = unserialize(base64_decode($dec_instance));
            if (is_array($_instance)) {

                // Merge with the defaults, in case of updated imports..
                $data = json_decode($_instance['data'], true);
                if ( $data === null ) continue;

                $data = array_merge($asp_def['asp_defaults'], $data);

                $wpdb->insert(
                    wd_asp()->tables->main,
                    array(
                        'name' => $_instance['name'].' Imported',
                        'data' => json_encode($data)
                    ),
                    array('%s', '%s')
                );

                $count++;
            } else {
                return false;
            }
        }
    } else {
        return false;
    }

    return $count;
}

function asp_import_settings($id, $data) {
    global $wpdb;

    //$data = stripcslashes($data);
    $data = unserialize(base64_decode($data));

    $asp_def = wd_asp()->options;
    // Merge with the defaults, in case of updated imports..
    $data = json_decode($data['data'], true);
    if ( $data === null ) return;

    $data = array_merge($asp_def['asp_defaults'], $data);

    if (is_array($data)) {
        return $wpdb->update(
            wd_asp()->tables->main,
            array(
                'data' => json_encode($data)
            ),
            array( 'id' => $id ),
            array(
                '%s'
            ),
            array( '%d' )
        );
    } else {
        return false;
    }
}


//----------------------------------------------------------------------------------------------------------------------
// 6. NON-AJAX RESULTS
//----------------------------------------------------------------------------------------------------------------------

if ( !class_exists("ASP_Post") )  {
    /**
     * Class ASP_Post
     *
     * A default class to instantiate to generate post like results.
     */
    class ASP_Post {

        public $ID = 0;                     // Don't use negative value, because WPML will break into pieces
        public $post_title = "";
        public $post_author = "";
        public $post_name = "";
        public $post_type = "post";         // Everything unknown is going to be a post
        public $post_date = '0000-00-00 00:00:00';             // Format: 0000-00-00 00:00:00
        public $post_date_gmt = '0000-00-00 00:00:00';         // Format: 0000-00-00 00:00:00
        public $post_content = '';          // The full content of the post
        public $post_content_filtered = '';
        public $post_excerpt = "";          // User-defined post excerpt
        public $post_status = "publish";    // See get_post_status for values
        public $comment_status = "closed";  // Returns: { open, closed }
        public $ping_status = "closed";     // Returns: { open, closed }
        public $post_password = "";         // Returns empty string if no password
        public $post_parent = 0;            // Parent Post ID (default 0)
        public $post_mime_type = '';
        public $to_ping = '';
        public $pinged = '';
        public $post_modified = "";         // Format: 0000-00-00 00:00:00
        public $post_modified_gmt = "";     // Format: 0000-00-00 00:00:00
        public $comment_count = 0;          // Number of comments on post (numeric string)
        public $menu_order = 0;             // Order value as set through page-attribute when enabled (numeric string. Defaults to 0)
        public $guid = "";
        public $asp_guid;

        public function __construct() {}
    }
}

if ( !function_exists("asp_results_to_wp_obj") ) {
    /**
     * Converts ajax results from Ajax Search Pro to post like objects to be displayable
     * on the regular search results page.
     *
     * @param $results
     * @param int $from
     * @param string $count
     * @return array
     */
    function asp_results_to_wp_obj($results, $from = 0, $count = "all") {
        if (empty($results))
            return array();

        if ($count == "all")
            $results_slice = array_slice($results, $from);
        else
            $results_slice = array_slice($results, $from, $count);

        if (empty($results_slice))
            return array();

        $wp_res_arr = array();

        $date_format = get_option('date_format');
        $time_format = get_option('time_format');

        $current_date = date($date_format . " " . $time_format, time());

        foreach ($results_slice as $r) {

            $switched_blog = false;
            if (is_multisite()) {
                if ( get_current_blog_id() !== $r->blogid ) {
                    switch_to_blog($r->blogid);
                    $switched_blog = true;
                }
            }

            if ( !isset($r->content_type) ) continue;

            switch ($r->content_type) {
                case "pagepost":
                    $res = get_post($r->id);
                    $res->asp_guid = get_permalink($r->id);
                    $res->asp_id = $r->id;  // Save the ID in case needed for some reason
                    /**
                     * On multisite the page and other post type links are filtered in such a way
                     * that the post type object is reset with get_post(), deleting the ->asp_guid
                     * attribute. Therefore the post type post must be enforced.
                     */
                    if ( is_multisite() && $res->post_type != 'post' ) {
                        // Is this a WooCommerce search?
                        if (
                        !(
                            in_array($res->post_type, array('product', 'product_variation')) &&
                            isset($_GET['post_type']) &&
                            $_GET['post_type'] == 'product'
                        )
                        ) {
                            $res->post_type = 'post'; // Enforce
                            if ( $switched_blog )
                                $res->ID = -10;
                        }
                    }
                    break;
                case "blog":
                    $res = new ASP_Post();
                    $res->post_title = $r->title;
                    $res->asp_guid = $r->link;
                    $res->post_content = $r->content;
                    $res->post_excerpt = $r->content;
                    $res->post_date = $current_date;
                    $res->ID = -10;
                    break;
                case "bp_group":
                    $res = new ASP_Post();
                    $res->post_title = $r->title;
                    $res->asp_guid = $r->link;
                    $res->post_content = $r->content;
                    $res->post_excerpt = $r->content;
                    $res->post_date = $r->date;
                    $res->ID = -10;
                    break;
                case "bp_activity":
                    $res = new ASP_Post();
                    $res->post_title = $r->title;
                    $res->asp_guid = $r->link;
                    $res->post_content = $r->content;
                    $res->post_excerpt = $r->content;
                    $res->post_date = $r->date;
                    $res->ID = -10;
                    break;
                case "comment":
                    $res = get_post($r->post_id);
                    if (isset($res->post_title)) {
                        $res->post_title = $r->title;
                        $res->asp_guid = $r->link;
                        $res->post_content = $r->content;
                        $res->post_excerpt = $r->content;
                    }
                    break;
                case "term":
                    $res = new ASP_Post();
                    $res->post_title = $r->title;
                    $res->asp_guid = $r->link;
                    $res->guid = $r->link;
                    $res->post_date = $current_date;
                    $res->ID = -10;
                    break;
                case "user":
                    $res = new ASP_Post();
                    $res->post_title = $r->title;
                    $res->asp_guid = $r->link;
                    $res->guid = $r->link;
                    $res->post_date = $current_date;
                    $res->ID = -10;
                    break;
            }

            if (!empty($res)) {
                $res = apply_filters("asp_regular_search_result", $res);
                $wp_res_arr[] = $res;
            }

            if (is_multisite())
                restore_current_blog();
        }

        return $wp_res_arr;
    }
}


//----------------------------------------------------------------------------------------------------------------------
// 7. FRONT-END
//----------------------------------------------------------------------------------------------------------------------

if ( !function_exists("asp_parse_custom_field_filters") ) {
    /**
     * Parses the custom field input text values for radio, dropdown and checkboxes into arrays
     *
     * @param $f string
     * @return array
     */
    function asp_parse_custom_field_filters($f) {
        global $wpdb;

        $f = explode('|', $f);
        foreach ($f as $k => $v) {
            $m = null;
            $f[$k] = json_decode(base64_decode($v));
            if (isset($f[$k]->asp_f_radio_value)) {
                $lines = preg_split("/\\r\\n|\\r|\\n/", $f[$k]->asp_f_radio_value);
                $f[$k]->asp_f_radio_value = array();
                foreach ($lines as $kk => $val) {
                    if ( trim($val) == '{get_values}') {
                        $_used_acf = false;
                        if ( function_exists('get_field_object') ) {
                            $acf_field_o = get_field_object($f[$k]->asp_f_field);
                            if ( isset($acf_field_o['choices']) &&
                                is_array($acf_field_o['choices']) &&
                                count($acf_field_o['choices']) > 0
                            ) {
                                foreach ( $acf_field_o['choices'] as $acf_f_o_k => $acf_f_o_v)
                                    $f[$k]->asp_f_radio_value[] = array($acf_f_o_k, $acf_f_o_v);
                                $_used_acf = true;
                            }
                        }
                        if ( !$_used_acf ) {
                            $cf_query = $wpdb->prepare(
                                "SELECT DISTINCT(meta_value) FROM $wpdb->postmeta WHERE meta_key LIKE '%s' LIMIT %d",
                                $f[$k]->asp_f_field, 250);
                            $cf_results = $wpdb->get_results($cf_query, OBJECT);
                            if (!is_wp_error($cf_results) && count($cf_results) > 0) {
                                foreach ($cf_results as $cf_result) {
                                    $f[$k]->asp_f_radio_value[] = array($cf_result->meta_value, $cf_result->meta_value);
                                }
                            }
                        }
                    } else {
                        preg_match('/^(.*?)\|\|(.*)/', $val, $m);
                        $f[$k]->asp_f_radio_value[] = array($m[1], $m[2]);
                    }
                }
            }
            if (isset($f[$k]->asp_f_dropdown_value)) {
                $lines = preg_split("/\\r\\n|\\r|\\n/", $f[$k]->asp_f_dropdown_value);
                $f[$k]->asp_f_dropdown_value = array();
                foreach ($lines as $kk => $val) {
                    if ( trim($val) == '{get_values}') {
                        $_used_acf = false;
                        if ( function_exists('get_field_object') ) {
                            $acf_field_o = get_field_object($f[$k]->asp_f_field);
                            if ( isset($acf_field_o['choices']) &&
                                is_array($acf_field_o['choices']) &&
                                count($acf_field_o['choices']) > 0
                            ) {
                                foreach ( $acf_field_o['choices'] as $acf_f_o_k => $acf_f_o_v)
                                    $f[$k]->asp_f_dropdown_value[] = array($acf_f_o_k, $acf_f_o_v);
                                $_used_acf = true;
                            }
                        }
                        if ( !$_used_acf ) {
                            $cf_query = $wpdb->prepare(
                                "SELECT DISTINCT(meta_value) FROM $wpdb->postmeta WHERE meta_key LIKE '%s' LIMIT %d",
                                $f[$k]->asp_f_field, 250);
                            $cf_results = $wpdb->get_results($cf_query, OBJECT);
                            if (!is_wp_error($cf_results) && count($cf_results) > 0) {
                                foreach ($cf_results as $cf_result) {
                                    $f[$k]->asp_f_dropdown_value[] = array($cf_result->meta_value, $cf_result->meta_value);
                                }
                            }
                        }
                    } else {
                        preg_match('/^(.*?)\|\|(.*)/', $val, $m);
                        if (!isset($m[1], $m[2]))
                            $f[$k]->asp_f_dropdown_value[] = array('', '');
                        else
                            $f[$k]->asp_f_dropdown_value[] = array($m[1], $m[2]);
                    }
                }
            }
            if (isset($f[$k]->asp_f_checkboxes_value)) {
                $lines = preg_split("/\\r\\n|\\r|\\n/", $f[$k]->asp_f_checkboxes_value);
                $f[$k]->asp_f_checkboxes_value = array();
                foreach ($lines as $kk => $val) {
                    if ( strpos(trim($val), '{get_values') === 0 ) {
                        $_used_acf = false;
                        if ( function_exists('get_field_object') ) {
                            $acf_field_o = get_field_object($f[$k]->asp_f_field);
                            if ( isset($acf_field_o['choices']) &&
                                is_array($acf_field_o['choices']) &&
                                count($acf_field_o['choices']) > 0
                            ) {
                                foreach ( $acf_field_o['choices'] as $acf_f_o_k => $acf_f_o_v) {
                                    if (strpos(trim($val), 'checked') !== false)
                                        $f[$k]->asp_f_checkboxes_value[] = array($acf_f_o_k, $acf_f_o_v . '**');
                                    else
                                        $f[$k]->asp_f_checkboxes_value[] = array($acf_f_o_k, $acf_f_o_v);
                                }
                                $_used_acf = true;
                            }
                        }
                        if ( !$_used_acf ) {
                            $cf_query = $wpdb->prepare(
                                "SELECT DISTINCT(meta_value) FROM $wpdb->postmeta WHERE meta_key LIKE '%s' LIMIT %d",
                                $f[$k]->asp_f_field, 250);
                            $cf_results = $wpdb->get_results($cf_query, OBJECT);
                            if (!is_wp_error($cf_results) && count($cf_results) > 0) {
                                foreach ($cf_results as $cf_result) {
                                    if (strpos(trim($val), 'checked') !== false)
                                        $f[$k]->asp_f_checkboxes_value[] = array($cf_result->meta_value, $cf_result->meta_value . '**');
                                    else
                                        $f[$k]->asp_f_checkboxes_value[] = array($cf_result->meta_value, $cf_result->meta_value);
                                }
                            }
                        }
                    } else {
                        preg_match('/^(.*?)\|\|(.*)/', $val, $m);
                        $f[$k]->asp_f_checkboxes_value[] = array($m[1], $m[2]);
                    }
                }
            }
            if (isset($f[$k]->asp_f_slider_from, $f[$k]->asp_f_slider_to)) {
                if ( $f[$k]->asp_f_slider_from == '' ) {
                    $min = $wpdb->get_var(
                        $wpdb->prepare(
                        "SELECT MIN(CAST(meta_value as SIGNED)) FROM $wpdb->postmeta WHERE meta_key LIKE '%s'",
                        $f[$k]->asp_f_field)
                    );
                    if ( !is_wp_error($min) && $min != null )
                        $f[$k]->asp_f_slider_from = $min;
                }
                if ( $f[$k]->asp_f_slider_to == '' ) {
                    $max = $wpdb->get_var(
                        $wpdb->prepare(
                            "SELECT MAX(CAST(meta_value as SIGNED)) FROM $wpdb->postmeta WHERE meta_key LIKE '%s'",
                            $f[$k]->asp_f_field)
                    );
                    if ( !is_wp_error($max) && $max != null )
                        $f[$k]->asp_f_slider_to = $max;
                }
            }
            if ( isset($f[$k]->asp_f_range_from, $f[$k]->asp_f_range_to) ) {
                if ( $f[$k]->asp_f_range_from == '' ) {
                    $min = $wpdb->get_var(
                        $wpdb->prepare(
                            "SELECT MIN(CAST(meta_value as SIGNED)) FROM $wpdb->postmeta WHERE meta_key LIKE '%s'",
                            $f[$k]->asp_f_field)
                    );
                    if ( !is_wp_error($min) && $min != null )
                        $f[$k]->asp_f_range_from = $min;
                }
                if ( $f[$k]->asp_f_range_to == '' ) {
                    $max = $wpdb->get_var(
                        $wpdb->prepare(
                            "SELECT MAX(CAST(meta_value as SIGNED)) FROM $wpdb->postmeta WHERE meta_key LIKE '%s'",
                            $f[$k]->asp_f_field)
                    );
                    if ( !is_wp_error($max) && $max != null )
                        $f[$k]->asp_f_range_to = $max;
                }
            }
        }
        return $f;
    }
}

if (!function_exists('asp_icl_t')) {
    /* Ajax Search pro wrapper for WPML and Polylang print */
    function asp_icl_t($name, $value) {
        if (function_exists('icl_register_string') && function_exists('icl_t')) {
            @icl_register_string('ajax-search-pro', $name, $value);
            return stripslashes( @icl_t('ajax-search-pro', $name, $value) );
        }
        if (function_exists('pll_register_string') && function_exists('pll__')) {
            pll_register_string($name, $value, 'ajax-search-pro');
            return stripslashes( pll__($value) );
        }
        if (function_exists('qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage')) {
            return stripslashes( qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage( $value ) );
        }
        return stripslashes( $value );
    }
}

if (!function_exists("asp_gen_rnd_str")) {
    function asp_gen_rnd_str($length = 6) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if (!function_exists("asp_generate_the_css")) {
    /**
     * Generates all Ajax Search Pro CSS code
     */
    function asp_generate_the_css( $remake_media_query = true ) {
        $css_arr = array();

        $comp_settings = wd_asp()->o['asp_compatibility'];
        $async_load = w_isset_def($comp_settings['css_async_load'], false);

        $search = wd_asp()->instances->get();
        if (is_array($search) && count($search)>0) {
            foreach ($search as $s) {
                //$s['data'] = json_decode($s['data'], true);
                // $style and $id needed in the include
                $style = &$s['data'];
                $id = $s['id'];
                ob_start();
                include(ASP_PATH . "/css/style.css.php");
                $out = ob_get_contents();
                $css_arr[$id] = $out;
                ob_end_clean();
            }
            // Too big, disabled...
            //update_option('asp_styles_base64', base64_encode($css));
            $css = implode(" ", $css_arr);

            if ( $async_load == 1 ) {
                foreach ($css_arr as $sid => $c) {
                    if ( $comp_settings['css_minify'] == 1 )
                        $c = asp_css_minify($c);
                    asp_put_file("search".$sid.".css", $c);
                }
            }
            // Save the style instances file nevertheless, even if async enabled
            if ( $comp_settings['css_minify'] == 1 )
                $css = asp_css_minify($css);
            asp_put_file("style.instances.css", $css);


            if ( $remake_media_query )
                update_option( "asp_media_query", asp_gen_rnd_str() );

            return $css;
        }
    }
}

if (!function_exists("asp_css_minify")) {
    /**
     * Very simple CSS minification, with some additional logic, basic support for CSS3
     *
     * @param string $css CSS to minify
     * @return string Minified CSS
     */
    function asp_css_minify($css) {
        // Normalize whitespace
        $css = preg_replace( '/\s+/', ' ', $css );

        // Remove spaces before and after comment
        $css = preg_replace( '/(\s+)(\/\*(.*?)\*\/)(\s+)/', '$2', $css );
        // Remove comment blocks, everything between /* and */, unless
        // preserved with /*! ... */ or /** ... */
        $css = preg_replace( '~/\*(?![\!|\*])(.*?)\*/~', '', $css );
        // Remove ; before }
        $css = preg_replace( '/;(?=\s*})/', '', $css );
        // Remove space after , : ; { } */ >
        $css = preg_replace( '/(,|:|;|\{|}|\*\/|>) /', '$1', $css );
        // Remove space before , ; { } ( ) >
        $css = preg_replace( '/ (,|;|\{|}|\(|\)|>)/', '$1', $css );
        // Strips leading 0 on decimal values (converts 0.5px into .5px)
        $css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );
        // Strips units if value is 0 (converts 0px to 0)
        $css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );
        // Converts all zeros value into short-hand
        $css = preg_replace( '/0 0 0 0;/', '0;', $css );
        $css = preg_replace( '/0 0 0 0\}/', '0\}', $css );
        // Invisible inset box shadow
        $css = preg_replace( '/box-shadow:0 0 0(?: 0)? [a-fA-F0-9()#,rgb]+(?: inset)?([};])/i', 'box-shadow: none${1}', $css );
        // Transparent box shadow
        $css = preg_replace( '/box-shadow:[0-9px ]+ (transparent inset|transparent)([};])/i', 'box-shadow: none${2}', $css );
        // Invisible text shadow
        $css = preg_replace( '/text-shadow:0 0(?: 0 )? [a-fA-F0-9()#,rgb]+([};])/i', 'text-shadow: none${1}', $css );
        // Transparent text shadow
        $css = preg_replace( '/text-shadow:[0-9px ]+ transparent([};])/i', 'text-shadow: none${1}', $css );
        // Shorten 6-character hex color codes to 3-character where possible
        $css = preg_replace( '/#([a-f0-9])\\1([a-f0-9])\\2([a-f0-9])\\3/i', '#\1\2\3', $css );
        return trim( $css );
    }
}

if (!function_exists("asp_get_terms_ordered_by_ids")) {
    function asp_get_terms_ordered_by_ids($taxonomy, $ids) {
        if ( empty($ids) ) return array();

        $tag_keys_arr = array();
        $final_tags_arr = array();

        foreach ($ids as $position => $tag_id) {
            $tag_keys_arr[$tag_id] = $position;
        }

        $tags = get_terms($taxonomy, array("include" => $ids));

        foreach ($tags as $tag) {
            $final_tags_arr[$tag_keys_arr[$tag->term_id]] = $tag;
        }

        ksort($final_tags_arr);

        return $final_tags_arr;
    }
}

function asp_str_remove_protocol( $str ) {
    return str_replace( array(
        'https://',
        'http://',
    ), '//', $str );
}