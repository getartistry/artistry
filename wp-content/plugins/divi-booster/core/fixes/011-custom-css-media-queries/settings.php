<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db011_add_setting($plugin) { 
	$plugin->setting_start(); 

list($name, $option) = $plugin->get_setting_bases(__FILE__); 

include_once(dirname(__FILE__).'/media_queries.php');
$media_queries = wtfdivi011_media_queries();
$media_queries = is_array($media_queries)?$media_queries:array();
?>

<input type="hidden" name="<?php echo $name; ?>[enabled]" value="1"/>

<style>
/* === sections === */
input.wtfdivi011_enabled { float:left; }
div.wtfdivi011_cssblock { width:100%; margin-bottom:16px; box-sizing:border-box; padding-right:30px; }
textarea.wtfdivi011_css { width:100%; box-sizing:border-box; min-height:100px; margin:2px 0px 2px 26px; overflow-y:hidden; line-height:1.4em; padding-top:0.3em; max-height: 500px; }

/* Hide first css block to use as template */
div.wtfdivi011_cssblock:nth-of-type(1) { display:none; }

/* Adjust line height so select box doesn't affect layout */
.wtfdivi011_selector { line-height:2.4em !important; float:left; padding:8px 8px; margin-left:30px; display:none; }
.wtfdivi011_selector td:nth-of-type(1) { padding-right:6px !important; height:2.4em; vertical-align:bottom;}
.wtfdivi011_selector td:nth-of-type(2) { width:260px;}

/* === actions === */
a.wtfdivi011_action { margin:6px; vertical-align:bottom; background-repeat:no-repeat; background-size:16px 16px; display:inline-block; text-decoration:none; background-position:left center; padding-left:24px; }
a.wtfdivi011_delete { background-image:url('<?php echo addslashes(htmlentities(plugin_dir_url(__FILE__))); ?>/../../../core/img/delete.png'); color:#c06; float:right; margin-right:-24px; }
a.wtfdivi011_add { background-image:url('<?php echo addslashes(htmlentities(plugin_dir_url(__FILE__))); ?>/../../../core/img/icon_plus_alt2.png'); }
</style>
<?php 
// set up a blank custom css box if none exists
if (empty($option['customcss']['css'])) { 
	$option['customcss']['css']=array('');
	$option['customcss']['enabled']=array(1);
	$option['customcss']['mediaqueries']=array('all');
} else {
	// fix checkbox vals
	$option['customcss']['enabled'] = wtfdivi011_html_checkbox_vals($option['customcss']['enabled']);
}

foreach($option['customcss']['css'] as $k=>$v) { 

		// user select		
		$selected_user = isset($option['customcss']['user'][$k])?$option['customcss']['user'][$k]:'all';		
		$options_user = array('all'=>'All users', 'logged-in'=>'Logged in users', 'not-logged-in'=>'Non logged in users'); 
				
		// device select	
		$selected_device = isset($option['customcss']['device'][$k])?$option['customcss']['device'][$k]:'all';		
		$options_device = array('all'=>'Any device', 'windows'=>'Windows', 'mac'=>'Mac', 'linux'=>'Linux', 'iphone'=>'iPhone', "android"=>"Android", "mobile"=>"Mobiles", "tablet"=>"Tablets", "desktop"=>"Desktops"); 
		
		// browser select	
		$selected_browser = isset($option['customcss']['browser'][$k])?$option['customcss']['browser'][$k]:'all';		
		$options_browser = array('all'=>'Any browser', 'chrome'=>'Google Chrome', 'gecko'=>'Firefox', 'ie'=>'Internet Explorer', 'lynx'=>'Lynx', 'opera'=>'Opera', 'safari'=>'Safari');
		
		// media query select	
		$selected_mq = isset($option['customcss']['mediaqueries'][$k])?$option['customcss']['mediaqueries'][$k]:'all';		
		$options_mq = array(); 
		foreach($media_queries as $mqslug=>$data) { $options_mq[$mqslug] = $data['name']; }
		
		// page type select
		$selected_pagetype = isset($option['customcss']['pagetype'][$k])?$option['customcss']['pagetype'][$k]:'all';		
		$options_pagetype = array(
			'all'=>'All pages & posts',
			'home'=>'Home page',
			'blog'=>'Blog posts index',
			'archive'=>'Archives',
			'search'=>'Search pages',
			'paged'=>'Paginated posts / pages',
			'error404'=>'404 error pages',
			'single'=>'All posts'
		);
		
		// add list of posts
		$posts = get_posts(array('posts_per_page'=>'100')); //'posts_per_page'=>'-1'));
		$posts = is_array($posts)?$posts:array();
		foreach($posts as $p) { 
			$options_pagetype["postid-".$p->ID] = 'Post "'.$p->post_title.'"'; 
		}
		
		// add list of pages		
		$options_pagetype['page']='All pages';
		$pages = get_pages(array()); 
		$pages = is_array($pages)?$pages:array();
		foreach($pages as $p) { 
			$options_pagetype["page-id-".$p->ID] = 'Page "'.$p->post_title.'"'; 
		}
		
		// et layout select
		$selected_et = isset($option['customcss']['elegantthemes'][$k])?$option['customcss']['elegantthemes'][$k]:'all';		
		$options_et = array(
			'all'=>'Any Divi layout',
			'et_vertical_nav'=>'Vertical navigation',
			'et_fixed_nav'=>'Fixed navigation',
			'et_boxed_layout'=>'Boxed layout',
			'et_cover_background'=>'Stretched background image',
			'et_left_sidebar'=>'Left sidebar',
			'et_right_sidebar'=>'Right sidebar',
			'et_includes_sidebar'=>'Either sidebar',
			'et_secondary_nav_enabled'=>'Secondary navigation',
			'et_secondary_nav_two_panels'=>'Secondary navigation (two panels)',
			'et_secondary_nav_only_menu'=>'Secondary navigation (menu only)',
			'et_pb_side_nav_page'=>'Side navigation'
		);
		
?>

	<input type="hidden" name="<?php echo $name; ?>[customcss][enabled][]" value="0"/>
	
	<div class="wtfdivi011_cssblock">
		
		<input type="checkbox" class="wtfdivi011_enabled" name="<?php echo $name; ?>[customcss][enabled][]" value="1" <?php checked(@$option['customcss']['enabled'][$k],1); ?>/> 
		<p style="line-height:2.4em;margin-left:4px">Custom CSS for <a href="javascript:;" class="wtfdivi011_selector_summary"></a></p>
		
		<table class="wtfdivi011_selector">
		<tr><td>User type:</td><td><?php $plugin->selectpicker(__FILE__, '[customcss][user][]', $options_user, $selected_user); ?></td></tr>
		<tr><td>Page:</td><td><?php $plugin->selectpicker(__FILE__, '[customcss][pagetype][]', $options_pagetype, $selected_pagetype); ?></td></tr>
		<tr><td>Divi layouts:</td><td><?php $plugin->selectpicker(__FILE__, '[customcss][elegantthemes][]', $options_et, $selected_et); ?></td></tr>
		</table>	
		
		<table class="wtfdivi011_selector">
		<tr><td>Browser:</td><td><?php $plugin->selectpicker(__FILE__, '[customcss][browser][]', $options_browser, $selected_browser); ?></td></tr>
		<tr><td>Device:</td><td><?php $plugin->selectpicker(__FILE__, '[customcss][device][]', $options_device, $selected_device); ?></td></tr>
		<tr><td>Screen width:</td><td><?php $plugin->selectpicker(__FILE__, '[customcss][mediaqueries][]', $options_mq, $selected_mq); ?></td></tr>
		</table>		

		<textarea class="wtfdivi011_css wtfdivi011_expandable" 
				  name="<?php echo $name; ?>[customcss][css][]" 
				  placeholder="Enter CSS here"><?php echo esc_textarea(@$option['customcss']['css'][$k]); ?></textarea>
		<a href="javascript:;" class="wtfdivi011_action wtfdivi011_delete">Delete</a>
	</div>
<?php
}
?>
<a href="javascript:;" class="wtfdivi011_action wtfdivi011_add">Add another custom CSS box</a>

<script>
jQuery(function($){

	$('.wtfdivi011_cssblock').wtfdivi011("add_delete_handler");
	$('.wtfdivi011_cssblock').wtfdivi011("add_toggle_handler");
	$('.wtfdivi011_cssblock').wtfdivi011('add_selector_update_handler');
	$('.wtfdivi011_cssblock').wtfdivi011('update_selector_summary');
	
	/* Create new textbox when add clicked */
	$('.wtfdivi011_add').click(function(){
		var box = $(".wtfdivi011_cssblock:nth-of-type(1)").clone(true).insertBefore(this);
		box.find('select option').prop('selected', false);
		box.find('input[type=checkbox]').prop('checked', true);
		var ta = box.find('textarea');
		ta.val('');
		wtfdivi011_textarea_init(box);
		ta.keyup(function(){ wtfdivi011_textarea_keyup(this); });
		$(".wtfdivi011_expandable").keyup();
	});

	/* Make textboxes autoexpand to height. Based on https://github.com/jackmoore/autosize */
	function wtfdivi011_textarea_init(collection) {
		collection.each(function(){
			var ta = this;
			var style = window.getComputedStyle(ta, null);
			ta.style.resize = 'none';
			ta.style.wordWrap = 'break-word';
			var width = ta.style.width;
			ta.style.width = '0px';
			ta.style.width = width;
		});
	}
	wtfdivi011_textarea_init($(".wtfdivi011_expandable"));
	
	function wtfdivi011_textarea_keyup(ta) {
		var startHeight = ta.style.height;
		var style = window.getComputedStyle(ta, null);
		var maxHeight = style.maxHeight !== 'none' ? parseFloat(style.maxHeight) : false;
		var heightOffset;
		if (style.boxSizing === 'content-box') {
			heightOffset = -(parseFloat(style.paddingTop)+parseFloat(style.paddingBottom));
		} else {
			heightOffset = parseFloat(style.borderTopWidth)+parseFloat(style.borderBottomWidth);
		}
		var htmlTop = document.documentElement.scrollTop;
		var bodyTop = document.body.scrollTop;
		ta.style.height = 'auto';
		var endHeight = ta.scrollHeight+heightOffset;
		if (maxHeight !== false && maxHeight < endHeight) {
			endHeight = maxHeight;
			ta.style.overflowY = 'scroll';
		} else if (ta.style.overflowY !== 'hidden') {
			ta.style.overflowY = 'hidden';
		}
		ta.style.height = endHeight+'px';
		document.documentElement.scrollTop = htmlTop;
		document.body.scrollTop = bodyTop;
	}
	
	$(".wtfdivi011_expandable").keyup(function(){ wtfdivi011_textarea_keyup(this); }).keyup();
	$(".wtfdivi-section-head").click(function(){ setTimeout(function(){ $(".wtfdivi011_expandable").keyup(); }, 250); });

});

(function($){ 
	$.fn.wtfdivi011 = function(action) {
		
		// add handler for css block delete button
        if (action === "add_delete_handler") { 
			this.find('.wtfdivi011_delete').click(
				function(){$(this).closest('.wtfdivi011_cssblock').remove();
			}) 
		}
		
		// add handler to show / hide selector settings area
		if (action === "add_toggle_handler") { 
			this.find('.wtfdivi011_selector_summary').click(function(){ 
				$(this).closest('.wtfdivi011_cssblock').find('.wtfdivi011_selector').toggle(); 
			});
		}
		
		// filter out select boxes with default values
		if (action === "exclude_defaults") { 
			return this.filter(function(){ return jQuery(this).val() != 'all'; });
		}
		
		// get text from each element and put into comma-separated list
		if (action === "text_list") { 
			return this.map(function(){ return jQuery(this).text(); }).get().join(', ');
		}
		
		// return elements with empty text
		if (action === "empty_text_elems") { 
			return this.filter(function() { return jQuery(this).text() == ''; }); 
		}
		
		// update the text of the selector toggle link
		if (action === "update_selector_summary") { 
			return this.each(function(){
			
				$(this).find('.wtfdivi011_selector_summary').text(
					$(this).find('.wtfdivi011_selector :selected').wtfdivi011('exclude_defaults').wtfdivi011('text_list').toLowerCase()
				).wtfdivi011('empty_text_elems').text('everywhere');  // default text
			});
		}
		
		// add handler to show / hide selector settings area
		if (action === "add_selector_update_handler") { 
			this.find('.wtfdivi011_selector_summary').click(function(){ 
				$(this).closest('.wtfdivi011_cssblock').wtfdivi011('update_selector_summary'); 
			});
			this.find('.wtfdivi011_selector select').change(function(){ 
				$(this).closest('.wtfdivi011_cssblock').wtfdivi011('update_selector_summary');
			});
		}
		
    };
}(jQuery));

</script>
<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('customcss', 'db011_add_setting');