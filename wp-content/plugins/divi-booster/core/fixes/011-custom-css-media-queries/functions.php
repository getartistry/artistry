<?php
if (!defined('ABSPATH')) { exit(); } // No direct access

// Use mobile detect to add more details of browser to body classes
include_once(dirname(__FILE__).'/Mobile-Detect-2.8.11/Mobile_Detect.php');

function wtfdivi011_add_body_classes($classes) {
		$detect = new Mobile_Detect_DM;
		 
		if ($detect->isTablet()) { $classes[] = 'tablet'; }
		elseif ($detect->isMobile()) { $classes[]='mobile'; }
		else { $classes[] = 'desktop'; }
		
		if($detect->isiOS()){ $classes[] = 'ios'; }
		if($detect->isAndroidOS()){ $classes[] = "android"; }
		
        return $classes;
}
add_filter('body_class', 'wtfdivi011_add_body_classes');

// Deals with html checkbox issue where unchecked values are not submitted. Uses zeros from hidden field as divider.
function wtfdivi011_html_checkbox_vals($orig) {
	$vals = array();
	while ($count = count($orig)) {
		if ($count>=2 and $orig[1]=='1') { // starts with 0,1 so enabled
			$vals[]=1; 
			$orig = array_slice($orig, 2); 
		} else { 
			$vals[]=0; 
			$orig = array_slice($orig,1); 
		}
	}
	return $vals;
}

function db011_user_css($plugin) {
	list($name, $option) = $plugin->get_setting_bases(__FILE__); 
	
	include_once(dirname(__FILE__).'/media_queries.php');
	$wtfdivi011_media_queries = wtfdivi011_media_queries();

	if (isset($option['customcss'])) {
		
		// Output each enabled CSS block	
		foreach(wtfdivi011_html_checkbox_vals($option['customcss']['enabled']) as $k=>$enabled) {
			if ($k==0) { continue; } // ignore template block
			
			
			if ($enabled) {
				
				// === build the media query === //
				$media_query = ($option['customcss']['mediaqueries'][$k]=='all')?'':$wtfdivi011_media_queries[$option['customcss']['mediaqueries'][$k]]['css'];
				
				// === build the selector === //
				
				// apply the body classes
				$selector = 'body';
				foreach (array('user', 'device', 'browser', 'pagetype', 'elegantthemes') as $selection) {
					$selector.= ($option['customcss'][$selection][$k]=='all')?'':'.'.$option['customcss'][$selection][$k];
				}
				
				// === build the CSS === //
				$css = trim($option['customcss']['css'][$k]);
				$css = booster_minify_css($css);
				$css_rules = array_filter(explode("}", $css)); // break into individual css rules
				
				foreach ($css_rules as $id=>$rule) {
				
					// get selectors for the rule
					list($rule_selectors, $rule_css) = explode('{', $rule); // get the selector list
					$rule_selectors = explode(',', $rule_selectors); // split them up
				
					// add the base selector to each selector
					foreach($rule_selectors as $j=>$rule_selector) {
						$rule_selectors[$j] = $selector.' '.$rule_selector;
					}
					
					$final_selectors = implode(",\n",$rule_selectors);
					
					$css_rules[$id] = $final_selectors.' { '.trim($rule_css).' }'; // add base selector to first selector
				}
				
				// === output the CSS === //
				$css = implode("\n", $css_rules);
				echo (empty($media_query))?$css:"$media_query {\n$css\n}";
				
			}
		}
	}
}
add_action('wp_head.css', 'db011_user_css');