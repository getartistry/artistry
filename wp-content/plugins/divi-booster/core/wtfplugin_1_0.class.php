<?php
if (!class_exists('wtfplugin_1_0')) { 

class wtfplugin_1_0 {
	
	var $inlinecss = false;
	var $minifiedcss = true;
	var $inlinejs = false;
	var $minifiedjs = true;
	
	var $config, $slug, $cacheurl, $cachedir;
	var $error_handler;
	
	
	function __construct($config) {
	
		$this->config = $config;
		$this->slug = $config['plugin']['slug']; // used a lot, so create a shorthand
		$this->package_slug = $config['plugin']['package_slug'];

		// Set up the cache
		$uploads = wp_upload_dir();  
		$this->cacheurl = set_url_scheme($uploads['baseurl'].'/'.$this->slug.'/');
		$this->cachedir = $uploads['basedir'].'/'.$this->slug.'/'; 
		wp_mkdir_p($this->cachedir);
		
		/* Check for plugin update */
		add_action('init', array($this, 'update_cache'));
		add_action('booster_update', array($this, 'compile_patch_files'));
		
		
		// Customizer
		// Regenerate cache files when customizer saved
		add_action('customize_save_after', array($this, 'compile_patch_files'), 99);
		add_action('customize_controls_print_styles', array($this, 'customizer_label_css'));
		
		// Load the function files (needs to happen before file compilation)
		$options = get_option($this->slug);
		if (isset($options['fixes']) and is_array($options['fixes'])) { 
			foreach($options['fixes'] as $fix=>$data) {
				if (isset($data['enabled']) && $data['enabled']) { 
					$fnfile = BOOSTER_DIR_FIXES."$fix/functions.php";
					if (file_exists($fnfile)) { include($fnfile); }	
				}
			}
		}
			
		if (is_admin()) { // Create the plugin settings page
			
			add_action('admin_menu', array($this, 'create_settings_page'), 11); // register the settings page
			add_action('divibooster_settings_page_init', array($this, 'settings_page_init')); // register the settings
			add_action('admin_init', array($this, 'register_settings')); // register the settings
			add_filter('plugin_action_links_'.$this->config['plugin']['basename'], array($this, 'add_plugin_action_links')); // add settings link to plugin listing		
			//add_action('admin_enqueue_scripts', array($this, 'enqueue_media_loader')); // load the media uploader js
			
		} else {
		
			// === Site is being displayed, so output the various theme fix components === //
			
			// javascript
			if ($this->inlinejs) { 
				add_action('wp_footer', array($this, 'output_user_js_inline')); 
			} else { 
				add_action('wp_enqueue_scripts', array($this, 'enqueue_user_js'), 9999); // load late to ensure dependencies available
			}
			
			// css
			if ($this->inlinecss) { 
				add_action('wp_head', array($this, 'output_user_css_inline'));
			} else { 
				add_action('wp_enqueue_scripts', array($this, 'enqueue_user_css'));
			}
			
			// footer html
			add_action('wp_footer', array($this, 'output_user_footer_html_inline'));
			
		}
	}
	
	function settings_page_init() {
		// Re-compile the css / js / html files if the settings have been saved
		if (isset($_GET['settings-updated']) and $_GET['settings-updated']==true) {
			$this->compile_patch_files();
		}
		
		// Load the settings page CSS and JS
		$this->enqueue_settings_files();
	}
	
	/* Rebuild the cache if needed */
	function update_cache() {
		if (!file_exists($this->cachedir) or !file_exists($this->cachedir.'wp_head.css')) {
			$this->compile_patch_files(); // rebuild the cached files
		}
	}
	
	// === Handle JS and CSS files
	
	function enqueue_user_js() { 
	
		// Get the js dependencies
		$dependencies = array('jquery');
		$filter = $this->slug.'-js-dependencies';
		if(has_filter($filter)) {
			$dependencies = apply_filters($filter, $dependencies);
		}	
		
		$options = get_option($this->slug); 
		wp_enqueue_script($this->slug.'-user-js', $this->cacheurl.'wp_footer.js', $dependencies, $this->last_save(), true); 
	} // put in footer
	function enqueue_user_css() { 
		wp_enqueue_style($this->slug.'-user-css', $this->cacheurl.'wp_head.css', array(), $this->last_save()); 
	}
	
	function last_save() {
		$options = get_option($this->slug);
		$timestamp = isset($options['lastsave'])?$options['lastsave']:0; 	
		return $timestamp;
	}
	
	function output_user_js_inline() { echo '<script>'.@file_get_contents($this->cachedir.'wp_footer.js').'</script>'; }
	function output_user_css_inline() { echo '<style>'.@file_get_contents($this->cachedir.'wp_head.css').'</style>'; }
	function output_user_footer_html_inline() { echo @file_get_contents($this->cachedir.'wp_footer.txt'); }
	
	function enqueue_settings_files() { 
	
		// plugin style and js
		wp_enqueue_style($this->slug.'_admin_css', plugin_dir_url(__FILE__).'admin/settings.css');
		wp_enqueue_script($this->slug.'_admin_js', plugin_dir_url(__FILE__).'admin/admin.js', array('jquery'));
		
		// color picker
		wp_enqueue_script('wp-color-picker');
		wp_enqueue_script('wp-color-picker-alpha', plugins_url('libs/wp-color-picker-alpha.min.js', __FILE__), array('wp-color-picker'), time());
		wp_enqueue_style('wp-color-picker');

		// jquery
		wp_enqueue_script('jquery');
	}
	
	function add_plugin_action_links($links) {
		$page = ($this->config['plugin']['admin_menu']=='themes.php'?'themes.php':'admin.php');
		$links[] = '<a href="'.get_bloginfo('wpurl').'/wp-admin/'.$page.'?page='.$this->slug.'_settings">Settings</a>';
		return $links;
	}
	
	function compile_patch_files() {
		//if (!file_exists($this->cachedir)) { mkdir($this->cachedir); }
	
		$options = get_option($this->slug);
		
		$files = array(
			'wp_head_style.php'=>'wp_head.css', 
			'wp_footer_script.php'=>'wp_footer.js',
			'wp_footer.php'=>'wp_footer.txt',
			'wp_htaccess.php'=>'htaccess.txt'
		);
		
		foreach($files as $in=>$out) {
			
			$content = '';
			
			// Old way - pre-hooking
			if (isset($options['fixes'])) { 
				foreach(@$options['fixes'] as $fix=>$data) {
					if (@$data['enabled']) { 
						ob_start();
						$fixfile = BOOSTER_DIR_FIXES."$fix/$in";
						if (file_exists($fixfile)) { include($fixfile); }
						$content.= "\n".trim(ob_get_contents());
						ob_end_clean();
					}
				}
			}
			
			$content.= "\n";
			// New way - hooks 
			ob_start();
			do_action($out, $this); // use target file name as hook name
			$content.= trim(ob_get_contents());
			ob_end_clean();
			
			// Minify files
			if (preg_match('/\.css$/', $out) and $this->minifiedcss) { $content = booster_minify_css($content); }
			if (preg_match('/\.js$/', $out) and $this->minifiedjs) { $content = booster_minify_js($content); } 
			
			file_put_contents($this->cachedir.$out, $content);
		}
		
		// Append our htaccess rules to the wordpress htaccess file
		if (!function_exists('get_home_path')) { require_once(ABSPATH.'/wp-admin/includes/file.php'); }
		$wp_htaccess_file = get_home_path().'/.htaccess';
		if (@is_readable($wp_htaccess_file) && @is_writeable($wp_htaccess_file)) {
			$htaccess =@file_get_contents($wp_htaccess_file); 
			if ($htaccess !== false) {
				$rules = file_get_contents($this->cachedir.'htaccess.txt');
				if (strpos($htaccess, '# BEGIN '.$this->slug)!==false) { 
					$htaccess = preg_replace(
						'/# BEGIN '.preg_quote($this->slug,'/').'.*# END '.preg_quote($this->slug,'/').'/is', 
						"# BEGIN ".$this->slug."\n$rules\n# END ".$this->slug, 
						$htaccess
					);
				} else { 
					$htaccess.= "\n# BEGIN ".$this->slug."\n$rules\n# END ".$this->slug."\n";
				}
				@file_put_contents($wp_htaccess_file, $htaccess);
			}
		}
	}
	
	function register_settings() { 
		register_setting($this->slug.'-group', $this->slug);
	}
	
	function create_settings_page() {
		$page = add_submenu_page($this->config['plugin']['admin_menu'], $this->config['plugin']['name'], $this->config['plugin']['shortname'], 'manage_options', BOOSTER_SETTINGS_PAGE_SLUG, array($this, 'settings_page'));
	}
	
	// create the options page
	function settings_page() {
		if (!current_user_can('manage_options')) { wp_die(__('You do not have sufficient permissions to access this page.')); }
		
		// Shorthand
		$slug = $this->slug;
		
		// Hook prior to settings page execution
		do_action("$slug-before-settings-page", $this);
		
		// Get license info
		//$license = get_option(BOOSTER_LICENCE_NAME);
		//$status = get_option(BOOSTER_LICENCE_STATUS);
		
		// Get last error, if any
		$last_error = get_option(BOOSTER_OPTION_LAST_ERROR);
		$last_error_details = get_option(BOOSTER_OPTION_LAST_ERROR_DESC);
		$has_error = !empty($last_error);
		$has_error_details = !empty($last_error_details);
		update_option(BOOSTER_OPTION_LAST_ERROR, ''); // clear last error
		
		// updates
		$plugins_url = is_network_admin()?network_admin_url('plugins.php'):admin_url('plugins.php');
		$update_link = wp_nonce_url(add_query_arg(array('puc_check_for_updates'=>1,'puc_slug' => urlencode($this->package_slug)),$plugins_url),'puc_check_for_updates');
		
		?>
		
		<div id="wtf-settings-page" class="wrap">
			

		<form method="post" class="wtf-form wtf-form-license" action="options.php">
			
			<h2><?php echo $this->config['plugin']['name']; ?></h2>
			
			<?php /*
					
			<?php if ($has_error) { ?>
				<div class="wtf error">
				<p>
				<?php if ($has_error_details) { ?>
					<a href="javascript:jQuery('.wtf-error-details').toggle()" style="float:right">details</a>
				<?php } ?>
				Error: <?php esc_html_e($last_error); ?>
				</p>
				<?php if ($has_error_details) { ?>
					<p class="wtf-error-details" style="display:none"><?php esc_html_e($last_error_details) ?></p>
				<?php } ?>
				</div>
			<?php } ?>
			
			*/ ?>

			<span class="wtf-form-license-area">Plugin active. <a href="<?php echo esc_url($update_link);?>">Check for updates</a>.<br><i>License keys are no longer required</i></span>
			
			
		</form>
		
		<form id="wtf-form" class="wtf-form" enctype="multipart/form-data" method="post" action="options.php">
		
		<input type="hidden" name="<?php esc_attr_e($slug); ?>[lastsave]" value="<?php esc_attr_e(intval(time())); ?>"/>
		
		<?php 
		$options = get_option($slug);
		$plugin_dir_url = plugin_dir_url(__FILE__);
		$image_dir_url = $plugin_dir_url.'img/';
			
		// Output the setting sections
		foreach($this->config['sections'] as $sectionslug=>$sectionheading) {
			//if ($sectionslug=='divi24' and !$this->config['theme']['divi2.4+']) { continue; }
			$open = (isset($options[$sectionslug]['open']) and $options[$sectionslug]['open']=='1')?1:0; 
			$is_subheading = (strpos($sectionslug, '-')==true);
			?>
			
			<h3 class="wtf-section-head <?php esc_attr_e($is_subheading?'wtf-subheading':'wtf-topheading'); ?>">
				<img src="<?php esc_attr_e($image_dir_url); ?>collapsed.png" 
					 class="wtf-expanded-icon <?php esc_attr_e($open?'rotated':''); ?>"/>
				<?php echo $sectionheading; ?>
			</h3>
			
			<input type="hidden" name="<?php esc_attr_e($slug); ?>[<?php esc_attr_e($sectionslug); ?>][open]" value="<?php esc_attr_e($open); ?>"/>
			
			<div class="wtf-setting-group <?php esc_attr_e($is_subheading?'wtf-subheading-group':''); ?> clearfix" 
				 style="<?php esc_attr_e((!$open and !$is_subheading)?'display:none':''); ?>;">
				<?php if (has_action("$slug-$sectionslug")) { ?>
					<hr/>
					<?php do_action("$slug-$sectionslug", $this); // output settings ?>
				<?php } ?>
			</div>
			
			<?php
		} 

		settings_fields("$slug-group");
		do_settings_sections("$slug-group");
		submit_button();
		?>
		<hr/>
		</form>
		<div id="wtf-sidebar"><?php do_action("$slug-plugin-sidebar"); ?></div>
		<div style="clear:both"></div>
		<?php do_action("$slug-plugin-footer"); ?>
		</div>
		
		<?php
	}
	
	function add_setting($hook, $callback) { add_action($this->slug.'-'.$hook, $callback); }
	
	function setting_start() { echo '<div class="wtf-setting">'; }
	function setting_end() { echo '</div><hr/>'; }

	// return the base name and option var for a given settings file
	function get_setting_bases($file) {
		$fixslug = $this->feature_slug($file); // use the fix's directory name as its slug
		$options = get_option($this->slug); 
		$namebase = $this->slug.'[fixes]['.$fixslug.']';
		$optionbase = @$options['fixes'][$fixslug];
		return array($namebase, $optionbase);
	}
	
	// Return the slug for a fix from the __FILE__ value
	function feature_slug($file) {
		return basename(dirname($file));
	}
	
	function get_option_name($group, $feature, $setting) {
		$feature = basename(dirname($feature)); // allow __FILE__ or direct setting name
		return $this->slug."[$group][$feature][$setting]";
	}
	
	// === Settings UI Components === //
	
	function techlink($url) { ?>
		<a href="<?php echo htmlentities($url); ?>" title="Read my post on this fix" target="_blank"><img src="<?php echo plugin_dir_url(__FILE__); ?>/img/information.png" style="width:24px;height:24px;vertical-align:baseline;margin-top:5px;float:right;"/></a>
		<?php
	}
	
	function status($status) {
		if ($status=='broken') { echo '<span style="color:red;float:right">broken</span>'; }
		elseif ($status=='untested') { echo '<span style="color:red;float:right">untested</span>'; }
		elseif ($status=='working') { echo '<span style="color:green;float:right">working</span>'; }
		else { echo '<span style="float:right;margin:7px;color:grey">'.$status.'</span>'; }
	}
	
	function hiddenfield($file, $field='') { 
		list($name, $option) = $this->get_setting_bases($file); ?>
		<input type="hidden" name="<?php echo $name; ?><?php echo empty($field)?'':htmlentities("[$field]"); ?>" value="<?php echo htmlentities(@$option[$field]); ?>"/>
		<?php
	}
	
	function hiddencheckbox($file, $field='enabled') { 
		list($name, $option) = $this->get_setting_bases($file); ?>
		<input type="checkbox" style="visibility:hidden" name="<?php echo $name; ?>[<?php echo htmlentities($field); ?>]" value="1" checked="checked"/>
		<?php
	}
	
	function checkbox($file, $field='enabled') { 
		list($name, $option) = $this->get_setting_bases($file); 
		
		$feature_slug = $this->feature_slug($file);
		
		// Get current checkbox status
		$is_checked = empty($option[$field])?false:$option[$field];
		$is_checked = apply_filters("divibooster_checkbox_{$feature_slug}_{$field}", $is_checked);
		
		$field_name = "{$name}[{$field}]";
		?>
		<input type="checkbox" name="<?php esc_attr_e($field_name); ?>" value="1" <?php checked($is_checked,1); ?>/>
		<?php
	}
	
	function selectpicker($file, $field, $options, $selected) { 
		list($name, $option) = $this->get_setting_bases($file); ?>
		<div class="wtf-select">
		<a href="javascript:;" onclick="jQuery(this).toggle().next().toggle().focus()">
		<?php echo @htmlentities(strtolower($options[$selected])); ?></a>
		<select name="<?php echo $name; ?><?php echo $field; ?>"
				onblur="jQuery(this).toggle().prev().toggle().text(jQuery(this).find(':selected').text().toLowerCase())">
		<?php foreach($options as $val=>$text) { ?>
			<option value="<?php echo esc_attr($val); ?>" <?php echo ($selected==$val)?'selected':''; ?>><?php echo htmlentities($text); ?></option>
		<?php } ?>
		</select>
		</div>
		<script>

		</script>
		<?php
	}
	
	function numberpicker($file, $field, $default=1, $min=0) { 
		list($name, $option) = $this->get_setting_bases($file); 	
		echo $this->input(array(
				'type'=>'number',
				'name'=>"{$name}[{$field}]",
				'value'=>(isset($option[$field]) and is_numeric($option[$field]))?$option[$field]:$default,
				'min'=>$min,
				'style'=>'width:64px'
			)
		);
	}
	
	function textpicker($file, $field, $default='') { 
		list($name, $option) = $this->get_setting_bases($file);
		echo $this->input(array(
				'type'=>'text',
				'name'=>"{$name}[{$field}]",
				'value'=>empty($option[$field])?$default:$option[$field],
				'style'=>'width:300px'
			)
		);
	}
	
	function textboxpicker($file, $field, $default='') { 
		list($name, $option) = $this->get_setting_bases($file); ?>
		<textarea class="wtf-textbox" name="<?php echo $name; ?>[<?php echo htmlentities($field); ?>]"><?php echo htmlentities(!empty($option[$field])?$option[$field]:$default); ?></textarea>
		<?php
	}
	
	// function enqueue_media_loader() {
		// wp_enqueue_media();
	// }
	
	function imagepicker($file, $field) { 
		list($name, $option) = $this->get_setting_bases($file); ?>
		<span class="wtf-imagepicker" style="display:inline">
		<?php
		echo $this->input(array(
				'type'=>'url',
				'id'=>"wtf-imagepicker-$field",
				'name'=>"{$name}[{$field}]",
				'class'=>'wtf-imagepicker',
				'size'=>36,
				'maxlength'=>1024,
				'placeholder'=>'Image URL',
				'value'=>empty($option[$field])?'':$option[$field]
			)
		);
		echo $this->input(array(
				'type'=>'button',
				'class'=>'wtf-imagepicker-btn upload-button',
				'value'=>'Choose Image'
			)
		);
		?>
		<img class="wtf-imagepicker-thumb" src="<?php echo htmlentities(set_url_scheme(@$option[$field])); ?>" style=""/>
		</span>
		<?php
	}
	
	function colorpicker($file, $field, $defaultcol="#ffffff", $alpha=false) { 
		list($name, $option) = $this->get_setting_bases($file); 
		$attribs = array(
			'type'=>'text',
			'name'=>"{$name}[{$field}]",
			'value'=>empty($option[$field])?$defaultcol:$option[$field],
			'class'=>'wtf-colorpicker',
			'data-default-color'=>is_null($defaultcol)?'':$defaultcol
		);
		if ($alpha) { 
			$attribs['data-alpha'] = true;
			$attribs['class'].=' color-picker';
		}
		echo $this->input($attribs);
	}
	
	// === Customizer === //
	function customizer_label($text, $url='') {
		
		$logo = 'DB';
		
		// Add documentation link
		if (!empty($url)) {
			$logo = '<a href="'.esc_attr($url).'" target="_blank" title="Added by Divi Booster. Click to view feature post.">'.$logo.'</a></div>';
		} 
		return "$text <div class='booster-customizer-doclink'>$logo</div>"; 
	}
	
	function customizer_label_css() { ?>
		<style>.booster-customizer-doclink, .booster-customizer-doclink a { float:right; color: #ccc; }</style>
	<?php
	}
	
	// === General Functions === //
	
	// create html input 
	function input($attribs=array()) { 
		$html = "";
		foreach($attribs as $k=>$v) { $html.= " ".esc_html($k).'="'.esc_attr($v).'"'; }
		return "<input $html/>";
	}
	
	// return html for a responsive image
	function responsive_img($title, $width, $height, $url) { ?>
		<div class="wtf-responsive" style="padding-bottom:<?php echo round(100*intval($height)/intval($width),1); ?>%;">
			<img src="<?php echo esc_attr($url); ?>" title="<?php echo esc_attr($title); ?>"/>
		</div>
		<?php
	}
	
}

}

// === Hooks ===

/*
// divibooster_customizer_js
add_action('customize_register', 'divibooster_customizer_init');
function divibooster_customizer_init($wp_customize) {
	if ($wp_customize->is_preview()) { add_action('wp_footer', 'divibooster_customizer_js_wrapper', 21); }
}
function divibooster_customizer_js_wrapper() { ?>
<script>jQuery(function($){ <?php do_action('divibooster_customizer_js'); ?> });</script>
<?php }
*/


