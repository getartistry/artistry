<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
	<meta name="robots" content="noindex">
	<title>Yellow Pencil</title>
	<style>
		body,html {
    		overflow: hidden;
    	}

		.yp-iframe-loader{
			display: block;
		    width: 100%;
		    height: 100%;
		    top: 0;
		    left: 0;
		    position: fixed;
		    background-size: 24px;
		    background-color: #FFFFFF;
		    background-image: url(data:image/gif;base64,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);
		    background-repeat: no-repeat;
		    background-position: center calc(50% - 12px);
		    background-position: center -webkit-calc(50% - 13px);
		    background-position: center -moz-calc(50% - 13px);
		    background-position: center -o-calc(50% - 13px);
		    z-index: 999999999;
		    font-family: Arial,"Helvetica Neue",Helvetica,sans-serif !important;
		}

		body:not(.yp-yellow-pencil-loaded) #iframe{
			width: -webkit-calc(100% - 49px);
			width: -moz-calc(100% - 49px);
			width: -ms-calc(100% - 49px);
			width: -o-calc(100% - 49px);
			width: calc(100% - 49px);
			height: 100%;
			position: fixed;
			top: 0;
			left: 0;
			border-width: 0;
			z-index: 99;
			background: #FFF;
			margin-left:0px;
			padding-left: 49px;
		}

		.loading-files{
		    width: 130px;
		    height: 24px;
		    top: 50%;
		    color:#666;
		    text-align:center;
		    font-size:12px;
		    left: 50%;
		    position: fixed;
		    margin-left: -65px;
		    margin-top: 13px;
		    font-family: Arial,"Helvetica Neue",Helvetica,sans-serif !important;
		}
	</style>
	<link rel="icon" type="image/ico" href="<?php echo esc_url(plugins_url( 'images/favicon.png' , __FILE__ )); ?>"/>
	<script src='<?php echo plugins_url( 'js/jquery.js?ver='.YP_VERSION.'' , __FILE__ ); ?>'></script>
	<script type="text/javascript">

	// Vars
	var protocol = "<?php if(is_ssl()){echo 'https';}else{echo 'http';} ?>";
	var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
	var siteurl = "<?php echo get_site_url(); ?>";

	// Languages
	var l18_saving = "<?php _e('Saving','yp'); ?>";
	var l18_back_to_menu = "<?php _e('Back to menu','yp'); ?>";
	var l18_close_editor = "<?php _e('Close Editor','yp'); ?>";
	var l18_saving = "<?php _e('Saving','yp'); ?>";
	var l18_save = "<?php _e('Save','yp'); ?>";
	var l18_saved = "<?php _e('Saved','yp'); ?>";
	var l18_demo_alert = "<?php _e('Saving is disabled in demo mode.','yp'); ?>";
	var l18_live_preview = "<?php _e('Live preview disabled in demo mode.','yp'); ?>";
	var l18_visitor_view = "<?php _e('Visitor view disabled in demo mode.','yp'); ?>";
	var l18_clear = "<?php _e('Clear','yp'); ?>";
	var l18_footer = "<?php _e('Footer','yp'); ?>";
	var l18_content = "<?php _e('Content','yp'); ?>";
	var l18_topbar = "<?php _e('Top Bar','yp'); ?>";
	var l18_simple_title = "<?php _e('Basic selector','yp'); ?>";
	var l18_clean_selector = "<?php _e('Alternative Class selector','yp'); ?>";
	var l18_simple_sharp_selector = "<?php _e('Simple selector','yp'); ?>";
	var l18_sharp_selector = "<?php _e('Sharp selector','yp'); ?>";
	var l18_list_notice = "<?php _e('The selected element is not a list item, Select a list item to edit styles.','yp'); ?>";
	var l18_list_notice1 = "<?php _e('Disable list style image property to use this property.','yp'); ?>";
	var l18_display_notice = "<?php _e('This property may not work, Set \'block\' or \'inline-block\' value to display option from Extra Section.','yp'); ?>";
	var l18_absolute_notice = "<?php _e('The absolute value could harm mobile view, Set absolute value just too big screen sizes with Responsive Tool.','yp'); ?>";
	var l18_fixed_notice = "<?php _e('The fixed value could harm mobile view, Set absolute value just too big screen sizes with Responsive Tool.','yp'); ?>";
	var l18_negative_margin_notice = "<?php _e('Negative margin value could break the website layout.','yp'); ?>";
	var l18_high_position_notice = "<?php _e('High position value could harm mobile view, Please apply this change to big screen sizes with Responsive Tool.','yp'); ?>";
	var l18_responsive_notice = "<?php _e('Slowly resize the page width and be sure it\'s looks good on all screen sizes.','yp'); ?>";
	var l18_bg_img_notice_two = "<?php _e('Set a background image for using this feature.','yp'); ?>";
	var l18_logo = "<?php _e('Logo','yp'); ?>";
	var l18_google_map = "<?php _e('Google Map','yp'); ?>";
	var l18_entry_title_link = "<?php _e('Entry Title Link','yp'); ?>";
	var l18_category_link = "<?php _e('Category Link','yp'); ?>";
	var l18_tag_link = "<?php _e('Tag Link','yp'); ?>";
	var l18_widget = "<?php _e('Widget','yp'); ?>";
	var l18_font_awesome_icon = "<?php _e('Font Awesome Icon','yp'); ?>";
	var l18_submit_button = "<?php _e('Submit Button','yp'); ?>";
	var l18_menu_item = "<?php _e('Menu Item','yp'); ?>";
	var l18_post_meta_division = "<?php _e('Post Meta Division','yp'); ?>";
	var l18_comment_reply_title = "<?php _e('Comment Reply Title','yp'); ?>";
	var l18_login_info = "<?php _e('Login Info','yp'); ?>";
	var l18_allowed_tags = "<?php _e('Allowed Tags','yp'); ?>";
	var l18_post_title = "<?php _e('Post Title','yp'); ?>";
	var l18_comment_form = "<?php _e('Comment Form','yp'); ?>";
	var l18_widget_title = "<?php _e('Widget title','yp'); ?>";
	var l18_tag_cloud = "<?php _e('Tag Cloud','yp'); ?>";
	var l18_row = "<?php _e('Row','yp'); ?>";
	var l18_button = "<?php _e('Button','yp'); ?>";
	var l18_lead = "<?php _e('Lead','yp'); ?>";
	var l18_well = "<?php _e('Well','yp'); ?>";
	var l18_accordion_toggle = "<?php _e('Accordion Toggle','yp'); ?>";
	var l18_accordion_content = "<?php _e('Accordion Content','yp'); ?>";
	var l18_alert_division = "<?php _e('Alert Division','yp'); ?>";
	var l18_footer_content = "<?php _e('Footer Content','yp'); ?>";
	var l18_global_section = "<?php _e('Section','yp'); ?>";
	var l18_menu_link = "<?php _e('Menu Link','yp'); ?>";
	var l18_submenu = "<?php _e('Sub Menu','yp'); ?>";
	var l18_show_more_link = "<?php _e('Show More Link','yp'); ?>";
	var l18_wrapper = "<?php _e('Wrapper','yp'); ?>";
	var l18_article_title = "<?php _e('Article title','yp'); ?>";
	var l18_column = "<?php _e('Column','yp'); ?>";
	var l18_post_division = "<?php _e('Post Division','yp'); ?>";
	var l18_content_division = "<?php _e('Content Division','yp'); ?>";
	var l18_entry_title = "<?php _e('Entry Title','yp'); ?>";
	var l18_entry_content = "<?php _e('Entry Content','yp'); ?>";
	var l18_entry_footer = "<?php _e('Entry Footer','yp'); ?>";
	var l18_entry_header = "<?php _e('Entry Header','yp'); ?>";
	var l18_enter_time = "<?php _e('Entry Time','yp'); ?>";
	var l18_post_edit_link = "<?php _e('Post Edit Link','yp'); ?>";
	var l18_post_thumbnail = "<?php _e('Post Thumbnail','yp'); ?>";
	var l18_thumbnail = "<?php _e('Thumbnail','yp'); ?>";
	var l18_thumbnail_image = "<?php _e('Thumbnail Image','yp'); ?>";
	var l18_edit_link = "<?php _e('Edit Link','yp'); ?>";
	var l18_comments_link_division = "<?php _e('Comments Link Division','yp'); ?>";
	var l18_site_description = "<?php _e('Site Description','yp'); ?>";
	var l18_post_break = "<?php _e('Post Break','yp'); ?>";
	var l18_paragraph = "<?php _e('Paragraph','yp'); ?>";
	var l18_line_break = "<?php _e('Line Break','yp'); ?>";
	var l18_horizontal_rule = "<?php _e('Horizontal Rule','yp'); ?>";
	var l18_link = "<?php _e('Link','yp'); ?>";
	var l18_list_item = "<?php _e('List Item','yp'); ?>";
	var l18_unorganized_list = "<?php _e('Unorganized List','yp'); ?>";
	var l18_image = "<?php _e('Image','yp'); ?>"; 
	var l18_bold_tag = "<?php _e('Bold Tag','yp'); ?>";
	var l18_italic_tag = "<?php _e('Italic Tag','yp'); ?>";
	var l18_strong_tag = "<?php _e('Strong Tag','yp'); ?>";
	var l18_blockquote = "<?php _e('Block Quote','yp'); ?>";
	var l18_preformatted = "<?php _e('Preformatted','yp'); ?>";
	var l18_table = "<?php _e('Table','yp'); ?>";
	var l18_table_row = "<?php _e('Table Row','yp'); ?>";
	var l18_table_data = "<?php _e('Table Data','yp'); ?>";
	var l18_header_division = "<?php _e('Header Division','yp'); ?>";
	var l18_footer_division = "<?php _e('Footer Division','yp'); ?>";
	var l18_section = "<?php _e('Section','yp'); ?>";
	var l18_form_division = "<?php _e('Form Division','yp'); ?>";
	var l18_centred_block = "<?php _e('Centred block','yp'); ?>";
	var l18_definition_list = "<?php _e('Definition list','yp'); ?>";
	var l18_definition_term = "<?php _e('Definition term','yp'); ?>";
	var l18_definition_description = "<?php _e('Definition description','yp'); ?>";
	var l18_header = "<?php _e('Header','yp'); ?>";
	var l18_level = "<?php _e('Level','yp'); ?>";
	var l18_smaller_text = "<?php _e('Smaller text','yp'); ?>";
	var l18_text_area = "<?php _e('Text Area','yp'); ?>";
	var l18_body_of_table = "<?php _e('Body Of Table','yp'); ?>";
	var l18_head_of_table = "<?php _e('Head Of Table','yp'); ?>";
	var l18_foot_of_table = "<?php _e('Foot of table','yp'); ?>";
	var l18_underline_text = "<?php _e('Underline text','yp'); ?>";
	var l18_span = "<?php _e('Span','yp'); ?>";
	var l18_quotation = "<?php _e('Quotation','yp'); ?>";
	var l18_citation = "<?php _e('Citation','yp'); ?>";
	var l18_expract_of_code = "<?php _e('Extract of code','yp'); ?>";
	var l18_navigation = "<?php _e('Navigation','yp'); ?>";
	var l18_label = "<?php _e('Label','yp'); ?>";
	var l18_time = "<?php _e('Time','yp'); ?>";
	var l18_division = "<?php _e('Division','yp'); ?>";
	var l18_caption_of_table = "<?php _e('Caption Of table','yp'); ?>";
	var l18_input = "<?php _e('Input','yp'); ?>";
	var l18_sure = "<?php _e('Are you sure you want to leave the page without saving?','yp'); ?>";
	var l18_reset = "<?php _e('Do you want reset current options?','yp'); ?>";
	var l18_process = "<?php _e('CSS styles are processing. Please be patient and wait until process end.','yp'); ?>";
	var l18_cantUndo = "<?php _e('You can\'t undo the changes while creating a new animation. Click \"reset icon\" if you want to disable any option.','yp'); ?>";
	var l18_cantUndoAnimManager = "<?php _e('You can\'t undo the changes while animation manager on.','yp'); ?>";
	var l18_cantEditor = "<?php _e('You can\'t use the CSS editor while creating a new animation.','yp'); ?>";
	var l18_allScenesEmpty = "<?php _e('All scenes are empty.','yp'); ?>";
	var l18_create = "<?php _e('Create','yp'); ?>";
	var l18_CreateAnimate = "<?php _e('Create Animation','yp'); ?>";
	var l18_cancel = "<?php _e('Cancel','yp'); ?>";
	var l18_scene = "<?php _e('SCENE','yp'); ?>";
	var l18_closeAnim = "<?php _e('Do you want to close animation creator?','yp'); ?>";
	var l18_animExits = "<?php _e('This animation name already exists, please try another one.','yp'); ?>";
	var l18_notjustit = "<?php _e('Not possible, Can\'t select just this element. Please add a custom id or class to this element.','yp'); ?>";
	var l18_notice = "<i class='yp-notice-icon'></i><?php _e('Notice','yp'); ?>";
	var l18_warning = "<i class='yp-notice-icon'></i><?php _e('Warning','yp'); ?>";
	var l18_none = "Default value for this rule";
	var l18_picker = "Active and move cursor to on any element. (Picker not work with images)";
	</script>
	<?php yp_head(); ?>
</head><?php

	$classes[] = 'yp-yellow-pencil wt-yellow-pencil yp-metric-disable yp-body-selector-mode-active';

	if(current_user_can("edit_theme_options") == false){
		if(defined("YP_DEMO_MODE")){
			$classes[] = 'yp-yellow-pencil-demo-mode';
		}
	}
	
	if(defined("WT_DISABLE_LINKS")){
		$classes[] = 'yp-yellow-pencil-disable-links';
	}

	if(!defined('WTFV')){
		$classes[] = 'wtfv';
	}

	$classesReturn = '';

	if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'CriOS') !== false) {
	    $classes[] = 'browser_chrome';
	}

	foreach ($classes as $class){
		$classesReturn .= ' '.$class;
	}

	$classesReturn = trim($classesReturn);

?>
<body class="<?php echo $classesReturn; ?>">

	<?php

		$frameLink = esc_url(urldecode($_GET['href']));

		if(empty($frameLink)){
			$frameLink = trim( strip_tags( urldecode( $_GET['href']) ) );
		}

		if(isset($_GET['yp_type'])){

			$type = trim( strip_tags( $_GET['yp_type'] ) );

			$frame = add_query_arg(array('yellow_pencil_frame' => 'true','yp_type' => $type),$frameLink);
		
		}elseif(isset($_GET['yp_id'])){

			$id = intval($_GET['yp_id']);

			$frame = add_query_arg(array('yellow_pencil_frame' => 'true','yp_id' => $id),$frameLink);
		
		}else{

			$frame = add_query_arg(array('yellow_pencil_frame' => 'true'),$frameLink);
		
		}

		// if isset out, set yp_out to frame
		if(isset($_GET['yp_out'])){

			$frame = add_query_arg(array('yp_out' => 'true'),$frame);

		}

	?>
	
	<?php

		$protocol = is_ssl() ? 'https' : 'http';

		$frameNew = esc_url($frame,array($protocol));

		if(empty($frameNew) == true && strstr($frame,'://') == true){
			$frameNew = explode("://",$frame);
			$frameNew = $protocol.'://'.$frameNew[1];
		}elseif(empty($frameNew) == true && strstr($frame,'://') == false){
			$frameNew = $protocol.'://'.$frame;
		}

		$frameNew = str_replace("&#038;", "&amp;", $frameNew);
		$frameNew = str_replace("&#38;", "&amp;", $frameNew);

	?>
	<iframe id="iframe" class="yellow_pencil_iframe" data-href="<?php echo $frameNew; ?>"></iframe>

	<style id="yp-animate-helper"></style>

	<div class="yp-animate-manager">

		<h3 class="animation-manager-empty">There is no animation on this page.<small>Select an element to add animation.</small></h3>

		<div class="yp-anim-list-menu"><ul></ul></div>

		<div class="yp-anim-control-overflow">
			<div class="yp-anim-controls">
				<div class="yp-anim-control-left">
					<div class="yp-anim-manager-control">
						<a class="yp-anim-control-btn yp-anim-control-close" data-toggle='tooltipAnim' data-placement='top' title='Close'><span class="dashicons dashicons-no-alt"></span></a>
						<a class="yp-anim-control-btn yp-anim-control-pause" data-toggle='tooltipAnim' data-placement='top' title='Stop'><span class="dashicons dashicons-controls-pause"></span></a>
						<a class="yp-anim-control-btn yp-anim-control-play" data-toggle='tooltipAnim' data-placement='top' title='Play'><span class="dashicons dashicons-controls-play"></span></a>
						<span class="yp-anim-current-duration"><span class="yp-counter-min">00</span>:<span class="yp-counter-second">00</span>.<span class="yp-counter-ms">00</span></span>
					</div>
				</div>
				<div class="yp-anim-control-right">
					<div class="yp-anim-playing-border"></div>
					<div class="yp-anim-metric">
					</div>
				</div>
				<div class="yp-clearfix"></div>
			</div>
		</div>

		<div class="yp-animate-manager-inner">

			<div class="yp-anim-left-part-column"></div>
			<div class="yp-anim-right-part-column">
				<div class="yp-anim-playing-over"></div>
				<div class="yp-anim-playing-border"></div>
			</div>
			<div class="yp-clearfix"></div>

		</div>

	</div>

	<div class="responsive-bottom-handle"></div>
	<div class="responsive-right-handle"></div>

	<div id="responsive-size-text"><a href="http://waspthemes.com/yellow-pencil/documentation/#responsive-tool" target="_blank" class="support-icon" data-toggle='tooltip' data-placement='right' title='Click here to take a look at Responsive Tool docs.'>?</a> Customizing for <span class="device-size"></span>px and <span class="media-control" data-code="max-width">below</span> screen sizes. <span class="device-name"></span></div>

	<?php yp_yellow_penci_bar(); ?>
	
	<div class="top-area-btn-group">
		<a target="blank" class="yellow-pencil-logo" href="http://waspthemes.com/yellow-pencil"></a>
		<div data-toggle='tooltip-bar' data-placement='right' title='<?php _e('Element Inspector','yp'); ?>' class="top-area-btn yp-selector-mode active"><span class="aiming-icon"></span></div>
		<div data-toggle='tooltip-bar' data-placement='right' title='<?php _e('Single Inspector','yp'); ?> <span class="yp-tooltip-shortcut"><?php _e('Select single element','yp'); ?></span>' class="top-area-btn yp-sharp-selector-btn"><span class="sharp-selector-icon"></span></div>
		<div data-toggle='tooltip-bar' data-placement='right' title='<?php _e('CSS Editor','yp'); ?> <span class="yp-tooltip-shortcut"><?php _e('shortcut: É','yp'); ?></span>' class="top-area-btn css-editor-btn"><span class="css-editor-icon"></span></span></div>
		<div data-toggle='tooltip-bar' data-placement='right' title='<?php _e('Responsive Mode','yp'); ?> <span class="yp-tooltip-shortcut"><?php _e('edit for a specific screen size','yp'); ?></span>' class="top-area-btn yp-responsive-btn active"><span class="responsive-icon"></span></div>
		<div data-toggle='tooltip-bar' data-placement='right' title='<?php _e('Search an element','yp'); ?> <span class="yp-tooltip-shortcut"><?php _e('Shortcut: F','yp'); ?></span>' class="top-area-btn yp-button-target active"><span class="search-selector-icon"></span></div>
		<div data-toggle='tooltip-bar' data-placement='right' title='<?php _e('Measuring Tool','yp'); ?> <span class="yp-tooltip-shortcut"><?php _e('Shortcut: M','yp'); ?></span>' class="top-area-btn yp-ruler-btn"><span class="ruler-icon"></span></div>
		<div data-toggle='tooltip-bar' data-placement='right' title='<?php _e('Wireframe','yp'); ?> <span class="yp-tooltip-shortcut"><?php _e('Work on the layout easily.','yp'); ?></span>' class="top-area-btn yp-wireframe-btn"><span class="wireframe-icon"></span></div>
		<div data-toggle='tooltip-bar' data-placement='right' title='<?php _e('Design Information','yp'); ?> <span class="yp-tooltip-shortcut"><?php _e('Typography, sizes and others','yp'); ?></span>' class="top-area-btn info-btn"><span class="design-information-icon"></span></div>
		<div data-toggle='tooltip-bar' data-placement='right' title='<?php _e('Animation Manager','yp'); ?> <span class="yp-tooltip-shortcut"><?php _e('Control Animations.','yp'); ?></span>' class="top-area-btn animation-manager-btn"><span class="animation-manager-icon"></span></div>
		<div data-toggle='tooltip-bar' data-placement='right' title='<?php _e('Undo','yp'); ?> <span class="yp-tooltip-shortcut"><?php _e('Hold CTRL + Z key down','yp'); ?></span>' class="top-area-btn top-area-center undo-btn"><span class="undo-icon"></span></div>
		<div data-toggle='tooltip-bar' data-placement='right' title='<?php _e('Redo','yp'); ?> <span class="yp-tooltip-shortcut"><?php _e('Hold CTRL + Y key down','yp'); ?></span>' class="top-area-btn redo-btn"><span class="redo-icon"></span></div>
		<div data-toggle='tooltip-bar' data-placement='right' title='<?php _e('Full-screen','yp'); ?> <span class="yp-tooltip-shortcut"><?php _e('Switch to full-screen','yp'); ?></span>' class="top-area-btn fullscreen-btn"><span class="dashicons dashicons-editor-contract"></span><span class="dashicons dashicons-editor-expand"></span></div>
	</div>

	<div class="breakpoint-bar"></div>
	<div class="metric"></div>

	<div class="metric-left-border"></div>
	<div class="metric-top-border"></div>
	<div class="metric-top-tooltip">Y: <span></span> px</div>
	<div class="metric-left-tooltip">X: <span></span> px</div>

	<div class="advanced-info-box">
		<div class="advanced-close-link"><span class="dashicons dashicons-arrow-left-alt2"></span></div>
		<div class="advanced-info-box-menu">
			<span class="advance-info-btns element-btn">Element</span> <span class="advance-info-btns typography-btn">Typography</span> <span class="advance-info-btns advanced-btn">Advanced</span>
		</div>
		<div class="advanced-info-box-inner">

			<div class="typography-content advanced-info-box-content">

				<h3>Color Scheme</h3>
				<div class="info-color-scheme-list">
				</div>

				<h3 class="no-top">Basic</h3>
				<ul class="info-basic-typography-list">
				</ul>

				<h3>Font Families</h3>
				<ul class="info-font-family-list">
				</ul>

				<h3>Animations</h3>
				<ul class="info-animation-list">
				</ul>

			</div>

			<div class="element-content advanced-info-box-content">

				<div class="info-element-selected-section">

					<div class="info-element-selector-section">
						<h3 class="no-top">CSS Selector</h3>
						<ul class="info-element-selector-list">
						</ul>
					</div>
					
					<h3>General</h3>
					<ul class="info-element-general">
					</ul>

					<div class="info-element-classes-section">
						<h3>Classes</h3>
						<ul class="info-element-class-list">
						</ul>
					</div>

					<h3>Box Model</h3>
					<div id="box-element-view">

						<div class="box-element-view-inner">

							<div class="box-view-section">
								<i class="model-view-margin">M</i>
								<i class="model-view-margin"></i>
								<i class="model-view-margin"></i>
								<i class="model-view-margin model-margin-top"></i>
								<i class="model-view-margin"></i>
								<i class="model-view-margin"></i>
								<i class="model-view-margin"></i>
							</div>

							<div class="box-view-section">
								<i class="model-view-margin"></i>
								<i class="model-view-border">B</i>
								<i class="model-view-border"></i>
								<i class="model-view-border model-border-top"></i>
								<i class="model-view-border"></i>
								<i class="model-view-border"></i>
								<i class="model-view-margin"></i>
							</div>

							<div class="box-view-section">
								<i class="model-view-margin"></i>
								<i class="model-view-border"></i>
								<i class="model-view-padding">P</i>
								<i class="model-view-padding model-padding-top"></i>
								<i class="model-view-padding"></i>
								<i class="model-view-border"></i>
								<i class="model-view-margin"></i>
							</div>

							<div class="box-view-section">
								<i class="model-view-margin model-margin-left"></i>
								<i class="model-view-border model-border-left"></i>
								<i class="model-view-padding model-padding-left"></i>
								<i class="model-view-size model-size"></i>
								<i class="model-view-padding model-padding-right"></i>
								<i class="model-view-border model-border-right"></i>
								<i class="model-view-margin model-margin-left"></i>
							</div>

							<div class="box-view-section">
								<i class="model-view-margin"></i>
								<i class="model-view-border"></i>
								<i class="model-view-padding"></i>
								<i class="model-view-padding model-padding-bottom"></i>
								<i class="model-view-padding"></i>
								<i class="model-view-border"></i>
								<i class="model-view-margin"></i>
							</div>

							<div class="box-view-section">
								<i class="model-view-margin"></i>
								<i class="model-view-border"></i>
								<i class="model-view-border"></i>
								<i class="model-view-border model-border-bottom"></i>
								<i class="model-view-border"></i>
								<i class="model-view-border"></i>
								<i class="model-view-margin"></i>
							</div>

							<div class="box-view-section">
								<i class="model-view-margin"></i>
								<i class="model-view-margin"></i>
								<i class="model-view-margin"></i>
								<i class="model-view-margin model-margin-bottom"></i>
								<i class="model-view-margin"></i>
								<i class="model-view-margin"></i>
								<i class="model-view-margin"></i>
							</div>

						</div>

					</div>

					<h3>DOM Code</h3>
					<textarea disabled="disabled" class="info-element-dom"></textarea>

				</div>

				<p class="info-no-element-selected">Please select one element to show informations.</p>

			</div>

			<div class="advanced-content advanced-info-box-content">
				<h3>Dimensions</h3>
				<ul class="info-basic-size-list">
				</ul>
				<h3>All Ids</h3>
				<ul class="info-global-id-list">
				</ul>
				<h3>All Classes</h3>
				<ul class="info-global-class-list">
				</ul>
			</div>

		</div>
	</div>

	<div class="yp-iframe-loader">
		<div class="loading-files"></div>
	</div>

	<div id="image_uploader">
		<iframe data-url="<?php echo admin_url('media-upload.php?type=image&TB_iframe=true&reauth=1&yp_uploader=1'); ?>"></iframe>
	</div>
	<div id="image_uploader_background"></div>

	<p class="yp-target-helper-note"><?php _e("Press Enter to continue. ESC Cancel.","yp"); ?></p>
	<input type='text' class='yp-button-target-input' placeholder='<?php _e('The element ID, Class either Tag name','yp'); ?>.' id='yp-button-target-input' />
	<ul id="yp-target-dropdown"><li>a</li></ul>
	<div id="target_background"></div>

	<div id="leftAreaEditor">
		<div id="cssData"></div>
		<div id="cssEditorBar"><span title="Fullscreen" class="dashicons yp-css-fullscreen-btn dashicons-editor-code"></span><a target="_blank" title="CSS Sources" href="<?php echo admin_url('admin.php?page=yellow-pencil-changes'); ?>"><span class="yp-source-page-link dashicons dashicons-admin-settings"></span></a><span title='<?php _e('Hide','yp'); ?>' class="dashicons yp-css-close-btn dashicons-no-alt "></span></div>
	</div>

	<div class="yp-popup-background"></div>
	<div class="yp-info-modal">
		<div class="yp-info-modal-top"></div>
		<div class="yp-info-modal-top-inner">
			<h2><?php _e("Changes Not Saved. Upgrade To Pro!","yp"); ?></h2>
			<p><?php _e("You are using some premium features. Upgrade to Pro or disable premium properties to save changes.","yp"); ?></p>
		</div>
		<ul>
			<li><?php _e("800+ Font families","yp"); ?></li>
			<li><?php _e("300+ Background patterns","yp"); ?></li>
			<li><?php _e("50+ Ready to use animations","yp"); ?></li>
			<li><?php _e("Visual Resizing","yp"); ?></li>
			<li><?php _e("Gradient generator & Color palettes","yp"); ?></li>
			<li><?php _e("Unlock all other features","yp"); ?></li>
			<li><?php _e("Lifetime license & Free updates","yp"); ?></li>
		</ul>

		<div class="yp-action-area">
			<a class="yp-info-modal-close"><?php _e("Maybe Later","yp"); ?></a>
			<a class="yp-buy-link" target="_blank" href="http://waspthemes.com/yellow-pencil/buy"><?php _e("Get Premium","yp"); ?></a>
			<p class="yp-info-last-note">Money back guarantee. You can request a refund at any time!</p>
		</div>
	</div>
	
	<div class="yp_debug"></div>

	<div class="anim-bar">
		<div class="anim-bar-title">
			<div class="anim-title"><?php _e("Animation Scenes","yp"); ?></div>
			<div class="yp-anim-save yp-anim-btn" data-toggle="tooltipAnimGenerator" data-placement="top" title="<?php _e("Done","yp"); ?>"><span class="dashicons dashicons-flag"></span></div>
			<div class="yp-anim-play yp-anim-btn" data-toggle="tooltipAnimGenerator" data-placement="top" title="<?php _e("Play","yp"); ?>"><span class="dashicons dashicons-controls-play"></span></div>
			<div class="yp-anim-cancel yp-anim-btn" data-toggle="tooltipAnimGenerator" data-placement="top" title="<?php _e("Cancel","yp"); ?>"><span class="dashicons dashicons-no-alt"></span></div>
			<div class="yp-clearfix"></div>
		</div>
		<div class="scenes">
			<div class="scene scene-active scene-1" data-scene="scene-1"><p><?php _e("SCENE","yp"); ?> 1 <span><input autocomplete="off" type='text' value='0' /></span></p></div>
			<div class="scene scene-2" data-scene="scene-2"><p><?php _e("SCENE","yp"); ?> 2 <span><input type='text' autocomplete="off" value='100' /></span></p></div>
			<div class="scene scene-add"><span class="dashicons dashicons-plus"></span></div>
			<div class="yp-clearfix"></div>
		</div>
	</div>

	<script>
	(function($){


		// All plugin element list
        window.plugin_classes_list = 'yp-styles-area|yp-animating|yp-animate-data|yp-scene-1|yp-sharp-selector-mode-active|yp-scene-2|yp-scene-3|yp-scene-4|yp-scene-5|yp-scene-6|yp-anim-creator|data-anim-scene|yp-anim-link-toggle|yp-animate-test-playing|ui-draggable-handle|yp-css-data-trigger|yp-yellow-pencil-demo-mode|yp-yellow-pencil-loaded|yp-element-resized|resize-time-delay|yp-selected-handle|yp-parallax-disabled|yp_onscreen|yp_hover|yp_click|yp_focus|yp-recent-hover-element|yp-selected-others|yp-multiple-selected|yp-demo-link|yp-live-editor-link|yp-yellow-pencil|wt-yellow-pencil|yp-content-selected|yp-selected-has-transform|yp-hide-borders-now|ui-draggable|yp-target-active|yp-yellow-pencil-disable-links|yp-closed|yp-responsive-device-mode|yp-metric-disable|yp-css-editor-active|wtfv|yp-clean-look|yp-has-transform|yp-will-selected|yp-selected|yp-fullscreen-editor|yp-element-resizing|yp-element-resizing-width-left|yp-element-resizing-width-right|yp-element-resizing-height-top|yp-element-resizing-height-bottom|context-menu-active|yp-selectors-hide|yp-contextmenuopen|yp-control-key-down|yp-selected-others-multiable-box';

        // Any visible element.
        window.simple_not_selector = 'head, script, style, [class^="yp-"], [class*=" yellow-pencil-"], link, meta, title, noscript, svg, canvas';

        // basic simple.
        window.basic_not_selector = '*:not(script):not(style):not(link):not(meta):not(title):not(noscript):not(head):not(circle):not(rect):not(polygon):not(defs):not(linearGradient):not(stop):not(ellipse):not(text):not(canvas):not(line):not(polyline):not(path):not(g):not(tspan)';


		// Variable
		window.loadStatus = false;

		// Document Load Note:
		yp_load_note("Editor loading..");

		// Document ready.
		$(document).ready(function(){

			// Load iframe.
			var s = $("#iframe").attr("data-href");
	        $("#iframe").attr("src", s);


	        // Frame load note:
	        yp_load_note("Reading styles..");

	        // Frame ready
	        $('#iframe').on('load', function(){

	        	var iframe = $($('#iframe').contents().get(0));
            	var iframeBody = iframe.find("body");
            	var body = $(document.body).add(iframeBody);

	        	// Adding yp-animating class to animating elements.
	        	iframe.find(window.basic_not_selector).on('animationstart webkitAnimationStart oanimationstart MSAnimationStart',function(){

	        			// Stop if any yp animation tool works
	        			if(body.hasClass("yp-anim-creator") || body.hasClass("yp-animate-manager-active")){
	        				return false;
	        			}

                		var element = $(this);

                		// Add an animating class.
                    	if(!element.hasClass("yp-animating")){
                        	element.addClass("yp-animating");
                        }

                        // Set outline selected style if selected element has animating.
                        if(element.hasClass("yp-selected") && body.hasClass("yp-has-transform") == false && body.hasClass("yp-content-selected") == true){
                        	body.addClass("yp-has-transform");
                        }

                        return false;

                });


	        	// Styles load Note:
	        	yp_load_note("Loading fonts..");

	        	// Loading Styles
				var styles = [
					"<?php echo esc_url(includes_url( 'css/dashicons.min.css' , __FILE__ )); ?>",
					"<?php $prtcl = is_ssl() ? 'https' : 'http'; echo $prtcl; ?>://fonts.googleapis.com/css?family=Open+Sans:400,300,600&subset=latin,latin-ext",
					"<?php echo esc_url(plugins_url( 'css/contextmenu.css?ver='.YP_VERSION.'' , __FILE__ )); ?>",
					"<?php echo esc_url(plugins_url( 'css/nouislider.css?ver='.YP_VERSION.'' , __FILE__ )); ?>",
					"<?php echo esc_url(plugins_url( 'css/iris.css?ver='.YP_VERSION.'' , __FILE__ )); ?>",
					"<?php echo esc_url(plugins_url( 'css/bootstrap-tooltip.css?ver='.YP_VERSION.'' , __FILE__ )); ?>",
					"<?php echo esc_url(plugins_url( 'css/sweetalert.css?ver='.YP_VERSION.'' , __FILE__ )); ?>",
					"<?php echo esc_url(plugins_url( 'css/yellow-pencil.css?ver='.YP_VERSION.'' , __FILE__ )); ?>"
				];

				// Loading.
				for(var i = 0; i < styles.length; i++){
					yp_load_css(styles[i]);
				}

				// Scripts Load note:
				yp_load_note("Preparing tools..");

				// Scripts Loading.
				setTimeout(function(){

					// let the user feel as that loads quickly.
					yp_load_note("Drawing wireframe..");

					setTimeout(function(){
						yp_load_note("Analyzes the design..");
					},600);

					setTimeout(function(){
						yp_load_note("Preparing palettes..");
					},1000);

					setTimeout(function(){
						yp_load_note("Generating selectors..");
					},1500);

					setTimeout(function(){
						yp_load_note("Playing with codes..");
					},2800);

					setTimeout(function(){
						yp_load_note("Preparing..");
					},4000);

					// Ace Code Editor Base.
					window.aceEditorBase = "<?php echo (plugins_url( 'library/ace/' , __FILE__ )); ?>";

					var scripts   = [
						"<?php echo plugins_url( 'js/jquery-migrate.js?ver='.YP_VERSION.'' , __FILE__ ); ?>",
						"<?php echo includes_url( 'js/jquery/ui/core.min.js' , __FILE__ ); ?>",
						"<?php echo includes_url( 'js/jquery/ui/widget.min.js' , __FILE__ ); ?>",
						"<?php echo includes_url( 'js/jquery/ui/mouse.min.js' , __FILE__ ); ?>",
						"<?php echo includes_url( 'js/jquery/ui/slider.min.js' , __FILE__ ); ?>",
						"<?php echo includes_url( 'js/jquery/ui/draggable.min.js' , __FILE__ ); ?>",
						"<?php echo includes_url( 'js/jquery/ui/resizable.min.js' , __FILE__ ); ?>",
						"<?php echo includes_url( 'js/jquery/ui/menu.min.js' , __FILE__ ); ?>",
						"<?php echo includes_url( 'js/jquery/ui/autocomplete.min.js' , __FILE__ ); ?>",
						"<?php echo plugins_url( 'js/contextmenu.js?ver='.YP_VERSION.'' , __FILE__ ); ?>",
						"<?php echo plugins_url( 'js/nouislider.js?ver='.YP_VERSION.'' , __FILE__ ); ?>",
						"<?php echo plugins_url( 'js/iris.js?ver='.YP_VERSION.'' , __FILE__ ); ?>",
						"<?php echo plugins_url( 'js/bootstrap-tooltip.js?ver='.YP_VERSION.'' , __FILE__ ); ?>",
						"<?php echo plugins_url( 'library/js/library.'.YP_MODE.'.js?ver='.YP_VERSION.'' , __FILE__ ); ?>",
						"<?php echo plugins_url( 'library/ace/ace.js' , __FILE__ ); ?>",
						"<?php echo plugins_url( 'library/ace/ext-language_tools.js' , __FILE__ ); ?>",
						"<?php echo plugins_url( 'js/sweetalert.js?ver='.YP_VERSION.'' , __FILE__ ); ?>",
						"<?php echo plugins_url( 'js/yellow-pencil.'.YP_MODE.'.js?ver='.YP_VERSION.'' , __FILE__ ); ?>"
					];

					//setup object to store results of AJAX requests
					var responses = {};

					//create function that evaluates each response in order
					function yp_eval_scripts() {

					    for (var i = 0, len = scripts.length; i < len; i++){

					    	// Eval
					    	eval(responses[scripts[i]]);

					    }

						// New List
						var newLoadList = Array();

						// Getting all CSS Stylesheets
				        window.definedStyleData = '';
				        iframe.find("link[rel='stylesheet'][href]").each(function(){

				        	// Get href
				            var href = $(this).attr("href");

				            // check and add
				            if(href.indexOf("waspthemes-yellow-pencil") == -1 &&
				            	href.indexOf("animate") == -1  &&
				            	href.indexOf("webkit") == -1 &&
				            	href.indexOf("animation") == -1 &&
				            	href.indexOf("keyframe") == -1 &&
				            	href.indexOf("font") == -1 &&
				            	href.indexOf("icon") == -1 &&
				            	href.indexOf("googleapis.com") == -1 &&
				            	href.indexOf("print") == -1 &&
				            	href.indexOf("reset") == -1 &&

				            	href.indexOf("preloader") == -1 &&
				            	href.indexOf("fancybox") == -1 &&
				            	href.indexOf("colorbox") == -1 &&
				            	href.indexOf("prettyPhoto") == -1 &&
				            	href.indexOf("popup") == -1 &&

				            	href.indexOf("player") == -1 &&
				            	href.indexOf("video") == -1 &&
				            	href.indexOf("audio") == -1 &&

				            	href != 'ie' &&
				            	href.indexOf("ie6") == -1 &&
				            	href.indexOf("ie7") == -1 &&
				            	href.indexOf("ie8") == -1 &&
				            	href.indexOf("ie9") == -1 &&
				            	href.indexOf("ie10") == -1 &&
				            	href.indexOf("ie11") == -1 &&
				            	href.indexOf("jquery") == -1 &&
				            	
				            	href.indexOf("skin") == -1 &&
				            	href.indexOf("scheme") == -1 &&

				            	href.indexOf("setting") == -1 &&
				            	href.indexOf("admin") == -1 &&

				            	// page builders
				            	href.indexOf("visualcomposer-assets") == -1 &&
				            	href.indexOf("elementor/css") == -1 &&
				            	href.indexOf("elementor/css") == -1 &&
				            	href.indexOf("page-builder-sandwich") == -1 &&
				            	href.indexOf("/Divi/") == -1 &&
				            	href.indexOf("live-composer-page-builder") == -1 &&
				            	newLoadList.length <= 10){

				            		// Add
				                	newLoadList.push(href);

				            }

				      	});


				        // There not have css stylesheets to load?, so start editor.
				      	if(newLoadList.length == 0){
				      		yp_start_editor();
				      	}


				        // Loading all stylesheets and Open Editor.
				      	var load_style_loop = function(i){

						    if(i < newLoadList.length) {

						        // Load styles
				     			$.get({
				     				url:newLoadList[i],
				     				timeout:2000,
				     				cache:true
				     			}).always(function(data){

				     				// Update
				     				if($.type(data) === "string"){
				                		window.definedStyleData += minimize_css(data);
				                	}

				                	// If last
				                	if(i+1 == newLoadList.length){

						                yp_start_editor();

									}

									// Repait
				                	load_style_loop(i + 1);

				                });

						    }

						};

						// Go
						load_style_loop(0);

					}


					// Minimize CSS before load
					function minimize_css(data){

		                // Clean.
		                data = data.replace(/(\r\n|\n|\r)/g, "").replace(/\t/g, '');

		                // Don't care rules in comment.
		                data = data.replace(/\/\*(.*?)\*\//g, "");

		                // clean.
		                data = data.replace(/\}\s+\}/g, '}}').replace(/\s+\{/g, '{');

		                // clean.
		                data = data.replace(/\s+\}/g, '}').replace(/\{\s+/g, '{');
		                data = data.replace(/[\u2018\u2019\u201A\u201B\u2032\u2035\u201C\u201D]/g,'');

		                // data
		                return data;

		            }


					// Stop load and call editor function.
					function yp_start_editor(){

						// Ready!:
						yp_load_note("Ready!");

						// Set true.
						window.loadStatus = true;

						// Okay. Load it.
						setTimeout(function(){
							yellow_pencil_main();
						},150);

					}


					$.each(scripts, function (index, value) {

					    $.ajax({

					        url      : scripts[index],

					        //force the dataType to be "text" rather than "script"
					        dataType : 'text',

					        success  : function (textScript) {

					            //add the response to the "responses" object
					            responses[value] = textScript;

					            //check if the "responses" object has the same length as the "scripts" array,
					            //if so then evaluate the scripts
					            if (Object.keys(responses).length === scripts.length) {
					            	yp_eval_scripts();
					            }

					        },

					        error    : function (jqXHR, textStatus, errorThrown){
					        	alert('An error occurred while loading.');

					        }

					    });

					});


			}); // Frame ready.

		}); // Document ready.

	});
	
	// CSS Loader
	function yp_load_css(link){
		$('<link>').appendTo('head').attr({type: 'text/css',rel: 'stylesheet',href: link});
	}

	// Update loading notes.
	function yp_load_note(text){
		if(window.loadStatus == false){
			$(".loading-files").html(text);
		}
	}

	})(jQuery);
	</script>

	<?php yp_footer(); ?>
	</body>
</html>