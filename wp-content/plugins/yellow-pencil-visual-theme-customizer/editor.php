<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
	<meta name="robots" content="noindex">
	<meta name="google" value="notranslate">
	<title>YellowPencil</title>
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
		    background-color: #F1F1F1;
		    background-image: url(data:image/gif;base64,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);
		    background-repeat: no-repeat;
		    background-position: center calc(50% - 12px);
		    background-position: center -webkit-calc(50% - 13px);
		    background-position: center -moz-calc(50% - 13px);
		    background-position: center -o-calc(50% - 13px);
		    z-index: 999999999;
		    font-family: -apple-system, BlinkMacSystemFont, 'Helvetica Neue', Helvetica, Arial, sans-serif !important;
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
		    color:#7a7a7a;
		    text-align:center;
		    font-size:12px;
		    left: 50%;
		    position: fixed;
		    margin-left: -65px;
		    margin-top: 13px;
		    font-family: -apple-system, BlinkMacSystemFont, 'Helvetica Neue', Helvetica, Arial, sans-serif !important;
		    font-weight:600;
		}
	</style>
	<link rel="icon" type="image/ico" href="<?php echo esc_url(plugins_url( 'images/favicon.png' , __FILE__ )); ?>"/>
	<script type="text/javascript">

	// Vars
	var protocol = "<?php if(is_ssl()){echo 'https';}else{echo 'http';} ?>";
	var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
	var siteurl = "<?php echo get_site_url(); ?>";

	// Languages
	var lang = {};

	// Basic
	lang.back_to_menu = "Back to menu";
	lang.close_editor = "Close Editor";
	lang.saving = "Saving";
	lang.save = "Save";
	lang.saved = "Saved";
	lang.unknown = "Unknown";

	// Demo mode
	lang.live_preview_alert = "This tool is disabled in demo mode!";
	lang.save_alert = "Saving is disabled in demo mode!";


	// Notices
	lang.list_notice = "The selected element is not a list item, Select a list item to edit styles.";
	lang.list_notice1 = "Disable list style image property to use this property.";
	lang.display_notice = "This property may not work, Set \'block\' or \'inline-block\' value to display option from Extra Section.";
	lang.absolute_notice = "The absolute value could harm mobile view, Set absolute value just to high screen sizes with Responsive Tool.";
	lang.fixed_notice = "The fixed value could harm mobile view, Set fixed value just to high screen sizes with Responsive Tool.";
	lang.negative_margin_notice = "Negative margin value could break the website layout.";
	lang.high_position_notice = "High position value could harm mobile view, Please apply this change only to large screen sizes using the Responsive Tool.";
	lang.bg_img_notice_two = "Set a background image for using this feature.";
	lang.bg_img_notice_tree = "Set a background color or image for using this feature.";
	lang.sure = "Are you sure you want to leave the page without saving?";
	
	// messages
	lang.cantUndo = "You can\'t undo the changes while creating a new animation. Click \"reset icon\" if you want to disable any option.";
	lang.cantUndoAnimManager = "You can\'t undo the changes while animation manager on.";
	lang.cantEditor = "You can\'t use the CSS editor while creating a new animation.";
	lang.allScenesEmpty = "Please add properties to the scenes to play the animation.";
	lang.scene = "Scene";
	lang.closeAnim = "Are you sure you want to close Animation Generator without saving?";
	lang.notice = "<i class='yp-notice-icon'></i>Notice";
	lang.warning = "<i class='yp-notice-icon'></i>Warning";
	lang.none = "Default value";

	// New
	lang.empty = "empty";
	lang.rule = "rule";
	lang.type_not_available = "Can not be used on the current page.";
	lang.you_are_sure = "You are sure?";
	lang.delete_anim = "Delete Animate";
	lang.welcome_pro = "Welcome to Pro Club!";
	lang.license_activated = "License Activated! Thank you for your purchase. We are here to help! Check out <a href=\'http://waspthemes.com/yellow-pencil/documentation/\' target=\'_blank\'>Plugin Docs</a> and join <a href=\'https://www.facebook.com/groups/YellowPencils/\' target=\'_blank\'>Facebook Community</a>.";

	lang.general = "General";
	lang.paragraph = "Paragraph";
	lang.heading_level = "Heading Level";
	lang.element_id = "Element ID";
	lang.tag = "Tag";
	lang.affected_els = "Affected elements";
	lang.box_sizing = "Box Sizing";
	lang.page_width = "Page Width";
	lang.page_height = "Page Height";

	lang.pseudo_class = "Events&hellip;";
	lang.all_devices = "All Devices";
	lang.delay = "Delay";
	lang.duration = "Duration";
	lang.delete_t = "Delete";
	lang.add_new_anim = "Add New Animate";
	lang.sorry = "Sorry.";
	lang.all_scenes_empty = "All scenes are empty.";
	lang.animation_name = "Animation name";
	lang.save_animation = "Save Animation";
	lang.set_animation_name = "Set a name to the animation to save.";
	lang.scene_properties = "Scene Properties";
	lang.no_property_yet = "No properties yet.";
	lang.save_error = "An error occurred while saving.";
	lang.save_error_msg = "The server may be offline either server\'s maximum post limit is not enough. Please try again later.";

	lang.define_breakpoints = "defined breakpoints";
	lang.breakpoint_size = "{$1}px and {$2} screen sizes";
	lang.phones = "phones";
	lang.large_phones = "high resolution phones";
	lang.tablet_landscape_phones = "tablets & landscape phones";
	lang.tablets = "tablets";
	lang.small_desktop_tablets_phones = "low resolution desktops & tablets and phones";
	lang.medium_desktops_tablets_phones = "normal resolution desktops & tablets and phones";
	lang.large_desktops_tablets_phones = "high resolution desktops & tablets and phones";
	lang.phones_tablets_desktops = "phones & tablets and desktops";
	lang.large_phones_tablets_desktops = "high resolution phones & tablets and desktops";
	lang.landscape_phones_tablets_desktops = "landscape phones & tablets and desktops";
	lang.desktops = "desktops";
	lang.medium_large_desktops = "normal resolution desktops & high resolution desktops";
	lang.large_desktops = "high resolution desktops";

	lang.css_parse_error = "CSS Parse Error.";
	lang.css_parse_error_text = "The changes you made in the CSS editor seems to be invalid. To continue, undo changes with CTRL + Z or fix this CSS error.";

	lang.delete_media_query = "Delete {$1} Media Query?";
	lang.delete_media_query_msg = "This will only delete the media query from the current customization type.";
	lang.review_breakpoint = "Review Breakpoint";
	lang.show_in_editor = "Show In Editor";
	lang.parent_elements = "Parent Elements&hellip;";
	lang.select_only_this = "Select Only This";
	lang.write_css = "Write CSS";
	lang.edit_selector = "Edit Selector";
	lang.review_styles = "Review Styles";
	lang.reset_styles = "Reset Styles&hellip;";
	lang.single = "Single&hellip;";
	lang.the_element = "The Element";
	lang.child_elements = "The Child Elements";
	lang.template = "Template&hellip;";
	lang.global_t = "Global&hellip;";
	lang.leave = "Leave";
	lang.above_t = "above";
	lang.below_t = "below";
	lang.toggle_media_query_condition = "Toggle media query condition as {$1}";
	lang.customize_type_not_available = "This customizing type can not be used on the current page.";
	lang.cursor_warning = "This change does not appear in the editor, check it with Live Preview.";
	lang.empty_element_tree = "Select an element to show element tree.";
	lang.reset_type_msg = "You are sure to reset all styles in <strong>{$1} customization</strong>?";
	lang.reset_btn = "Yes, Reset!";

	lang.manager_msg1 = "There is no style matching with the selected element.";
	lang.manager_msg2 = "Select an item to review matching styles.";
	lang.manager_msg3 = "Single customization is empty.";
	lang.manager_msg4 = "Template customization is empty.";
	lang.manager_msg5 = "Global customization is empty.";
	lang.manager_msg6 = "There is no style in this media query.";
	lang.manager_msg7 = "There are no styles matching your search term.";
	lang.manager_msg8 = "No style found. Check again after making a few edits.";
	lang.manager_msg9 = "All styles on the current page are listed below.";
	lang.manager_msg10 = "The styles matching with the selected element are listed below.";
	lang.manager_msg11 = "Single Customization styles listed below.";
	lang.manager_msg12 = "Template Customization styles listed below.";
	lang.manager_msg13 = "Global Customization styles listed below.";
	lang.manager_msg14 = "All styles in this media query are listed below.";
	lang.manager_msg15 = "No styles were found. Check again after making a few edits.";
	lang.selector_no_match = "The selector doesn\'t match any element on this page";
	lang.all_msg = "Any screen size";
	lang.not_wp_link = "This link is not an wordpress page. You can\'t edit this page.";
	lang.external_link = "This is an external link. You can\'t edit this page.";
	lang.link_not_valid = "This link is not an wordpress page. You can\'t edit this page.";
	lang.page_loading = "Page loading";
	lang.page_information_cant_be_retrieved = "Page information cannot be retrieved.";
	lang.page_information_cant_be_retrieved_msg = "Please close the page and open the target page manually with YellowPencil.";

	</script>
	<?php yp_head(); ?>
</head><?php

	$classes[] = 'yp-yellow-pencil yp-metric-disable yp-body-selector-mode-active';

	if(current_user_can("edit_theme_options") == false){
		if(defined("YP_DEMO_MODE")){
			$classes[] = 'yp-yellow-pencil-demo-mode';
		}
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

		$mode = trim( strip_tags( $_GET['yp_mode'] ) );
		$type = trim( strip_tags( $_GET['yp_page_type'] ) );
		$id = intval($_GET['yp_page_id']);
    	$rand = rand(136900, 963100);

		$frame = add_query_arg(array('yellow_pencil_frame' => 'true','yp_page_type' => $type,'yp_page_id' => $id,'yp_mode' => $mode,'yp_rand' => $rand), $frameLink);

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

		// get customize type link
		if(defined("YP_DEMO_MODE")){
			$customize_type_link = add_query_arg(array('yp_customize_type' => "true"), get_home_url());
		}else{
			$customize_type_link = admin_url('admin.php?page=yellow-pencil-customize-type');
		}

	?>
	<iframe id="iframe" class="yellow_pencil_iframe" data-href="<?php echo $frameNew; ?>" tabindex="-1"></iframe>

	<iframe data-page-href="<?php echo yp_urlencode(esc_url($_GET['href'])); ?>" data-page-id="<?php echo $_GET['yp_page_id']; ?>" data-page-type="<?php echo $_GET['yp_page_type']; ?>" data-page-visitor="<?php echo isset($_GET['yp_out']); ?>" id="yp-customizing-type-frame" style="border-width: 0px;display:none;position:fixed;width:100%;height:100%;top:0;left:0;z-index:2147483647;" data-src="<?php echo $customize_type_link; ?>" tabindex="-1"></iframe>

	<div id="parent-bar"><span>Select an element to show element tree.</span><ul></ul></div>
	
	<div id="visual-css-view">
		<div class="css-view-top">
			<input id="visual-rule-filter" placeholder="filter.." type="text" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" />
			<div class="visual-manager-close"></div>
		</div>
		<div id="visual-css-content"></div>
	</div>

	<span class='dashicons dashicons-admin-collapse yp-panel-show'></span>

	<style id="yp-animate-helper"></style>

	<div class="yp-animate-manager">

		<h3 class="animation-manager-empty">There is no animation on this page.<small>Select an element to add animation</small>.</h3>

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
				<div class="yp-clear"></div>
			</div>
		</div>

		<div class="yp-animate-manager-inner">

			<div class="yp-anim-left-part-column"></div>
			<div class="yp-anim-right-part-column">
				<div class="yp-anim-playing-over"></div>
				<div class="yp-anim-playing-border"></div>
			</div>
			<div class="yp-clear"></div>

		</div>

	</div>

	<div class="responsive-right-handle"></div>

	<div class="responsive-size-text default-responsive-text"><a href="http://waspthemes.com/yellow-pencil/documentation/#responsive-tool" target="_blank" class="support-icon" data-toggle="tooltip" data-placement="right" title="Click here for learning how Responsive Tool work.">?</a> Customizing for <span class='device-size'></span>px and <span class='media-control' data-code='max-width'>below</span> screen sizes. <span class="device-name"></span></div>
	<div class="responsive-size-text"><a href="http://waspthemes.com/yellow-pencil/documentation/#responsive-tool" target="_blank" class="support-icon" data-toggle="tooltip" data-placement="right" title="Click here for learning how Responsive Tool work.">?</a> Customizing <span class='property-size-text'></span>.</div>

	<?php yp_yellow_penci_bar(); ?>
	
	<div class="top-area-btn-group">
		<a target="blank" class="yellow-pencil-logo" href="http://waspthemes.com/yellow-pencil" tabindex="-1"></a>
		<div class="top-area-btn cursor-main-btn yp-selector-mode active"><span class="no-aiming-icon"></span><span class="aiming-icon"></span><span class="sharp-selector-icon"></span></div>
		<div data-toggle='tooltip-bar' data-placement='right' title='Find An Element <span class="yp-s-shortcut">(F)</span><span class="yp-tooltip-shortcut">Find elements by CSS selector.</span>' class="top-area-btn yp-search-btn active"><span class="search-selector-icon"></span></div>
		<div data-toggle='tooltip-bar' data-placement='right' title='CSS Editor <span class="yp-s-shortcut">(É)</span><span class="yp-tooltip-shortcut">Edit style codes.</span>' class="top-area-btn css-editor-btn"><span class="css-editor-icon"></span></div>
		<div data-toggle='tooltip-bar' data-placement='right' title='Responsive Mode <span class="yp-s-shortcut">(R)</span><span class="yp-tooltip-shortcut">Edit for a specific screen size.</span>' class="top-area-btn yp-responsive-btn active"><span class="responsive-icon"></span></div>
		<div data-toggle='tooltip-bar' data-placement='right' title='Measuring Tool <span class="yp-s-shortcut">(M)</span><span class="yp-tooltip-shortcut">Measure elements.</span>' class="top-area-btn yp-ruler-btn"><span class="ruler-icon"></span></div>
		<div data-toggle='tooltip-bar' data-placement='right' title='Wireframe <span class="yp-s-shortcut">(W)</span><span class="yp-tooltip-shortcut">Work on the layout easily.</span>' class="top-area-btn yp-wireframe-btn"><span class="wireframe-icon"></span></div>
		<div data-toggle='tooltip-bar' data-placement='right' title='Design Information <span class="yp-s-shortcut">(D)</span><span class="yp-tooltip-shortcut">Typography, sizes and others.</span>' class="top-area-btn info-btn"><span class="design-information-icon"></span></div>
		<div data-toggle='tooltip-bar' data-placement='right' title='Animation Manager <span class="yp-tooltip-shortcut">Manage animations visually.</span>' class="top-area-btn animation-manager-btn"><span class="animation-manager-icon"></span></div>
		<div data-toggle='tooltip-bar' data-placement='right' title='Undo <span class="yp-tooltip-shortcut">CTRL + Z</span>' class="top-area-btn top-area-center undo-btn"><span class="undo-icon"></span></div>
		<div data-toggle='tooltip-bar' data-placement='right' title='Redo <span class="yp-tooltip-shortcut">CTRL + Y</span>' class="top-area-btn redo-btn"><span class="redo-icon"></span></div>

		<div class="left-menu-sublist inspector-sublist">
			<ul>
				<li class="inspector-sublist-cursor" data-cursor-action="cursor">Cursor</li>
				<li class="inspector-sublist-default active" data-cursor-action="default">Flexible Inspector</li>
				<li class="inspector-sublist-single" data-cursor-action="single">Single Inspector</li>
			</ul>
		</div>
	</div>

	<div class="yp-right-panel-placeholder"></div>
	<div class="breakpoint-bar"></div>
	<div class="metric"></div>

	<div class="metric-left-border"></div>
	<div class="metric-top-border"></div>
	<div class="metric-top-tooltip">Y: <span></span> px</div>
	<div class="metric-left-tooltip">X: <span></span> px</div>

	<div class="search-box">
		<div class="search-close-link"><span class="dashicons dashicons-arrow-left-alt2"></span></div>
		<div class="search-box-topbar">Find By CSS Selector</div>
		<div class="search-input-area">
			<input id="search" type="text" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" placeholder="Search by class, ID or HTML tag." />
			<div class="yp-clear"></div>
		</div>
		<div class="search-box-inner">
			<ul class="search-live-result"></ul>
		</div>
	</div>

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

				<h3 id="animations-heading">Animations</h3>
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
								<i class="model-view-margin"><span>margin</span></i>
								<i class="model-view-margin"></i>
								<i class="model-view-margin"></i>
								<i class="model-view-margin model-margin-top"></i>
								<i class="model-view-margin"></i>
								<i class="model-view-margin"></i>
								<i class="model-view-margin"></i>
							</div>

							<div class="box-view-section">
								<i class="model-view-margin"></i>
								<i class="model-view-border"><span>border</span></i>
								<i class="model-view-border"></i>
								<i class="model-view-border model-border-top"></i>
								<i class="model-view-border"></i>
								<i class="model-view-border"></i>
								<i class="model-view-margin"></i>
							</div>

							<div class="box-view-section">
								<i class="model-view-margin"></i>
								<i class="model-view-border"></i>
								<i class="model-view-padding"><span>padding</span></i>
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
								<i class="model-view-margin model-margin-right"></i>
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

				<p class="info-no-element-selected">Please select an element to show information.</p>

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

	<div class="unvalid-css-cover"></div>

	<div id="image_uploader">
		<iframe data-url="<?php echo admin_url('media-upload.php?TB_iframe=true&reauth=1&yp_uploader=1'); ?>"></iframe>
	</div>
	<div id="image_uploader_background"></div>

	<div id="selector-editor-box">
		<p class="selector-editor-notice">Press Enter to select, ESC cancel.</p>
		<input autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" type='text' class='yp-selector-editor' placeholder='Search by class, ID or HTML tag.' id='yp-selector-editor' />
		<ul id="autocomplate-selector-list"><li>a</li></ul>
	</div>
	<div id="selector-editor-background"></div>

	<div id="leftAreaEditor">
		<div id="cssData"></div>
		<div id="cssEditorBar">
			<span class="dashicons yp-css-close-btn dashicons-no-alt" title='Hide (ESC)'></span>
			<span class="editor-tabs single-tab" title="The styles applied to the current page." data-type-value="single">Single<i></i></span>
			<span class="editor-tabs template-tab" title="The styles applied to all pages of the current post type." data-type-value="template">Template<i></i></span>
			<span class="editor-tabs global-tab" title="The styles applied to the entire website." data-type-value="global">Global<i></i></span>
			<div class="editor-tab-border"></div>
		</div>
		<div class="unvalid-css-error">Error: <span></span></div>
	</div>

	<div class="yp-popup-background"></div>
	<div class="yp-info-modal">
		<div class="yp-info-modal-top"></div>
		<div class="yp-info-modal-top-inner">
			<h2>Changes Not Saved. Upgrade To Pro!</h2>
			<p>You are using some premium properties. Upgrade to Pro or disable premium properties to save changes.</p>
		</div>
		<ul>
			<li>Access 800+ Quality Fonts</li>
			<li>Hundreds of background patterns</li>
			<li>Visual Resizing</li>
			<li>Gradient backgrounds & color palettes</li>
			<li>Access all properties and tools</li>
			<li>6 months premium support</li>
			<li>Lifetime license & free updates</li>
		</ul>

		<div class="yp-action-area">
			<a class="yp-info-modal-close">No Thanks</a>
			<a class="yp-buy-link" target="_blank" href="http://waspthemes.com/yellow-pencil/buy">Go Pro</a>
			<p class="yp-info-last-note">Money back guarantee. You can request a refund at any time!</p>
		</div>
		<a class='activate-pro' href="<?php echo admin_url('admin.php?page=yellow-pencil-license'); ?>" target="_blank">Activate License</a>
	</div>

	<div class="anim-bar">
		<div class="anim-bar-title">
			<div class="anim-title">Animation Generator</div>
			<div class="yp-anim-save yp-anim-btn" data-toggle="tooltipAnimGenerator" data-placement="top" title="Done"><span class="dashicons dashicons-flag"></span></div>
			<div class="yp-anim-play yp-anim-btn" data-toggle="tooltipAnimGenerator" data-placement="top" title="Play"><span class="dashicons dashicons-controls-play"></span></div>
			<div class="yp-anim-cancel yp-anim-btn" data-toggle="tooltipAnimGenerator" data-placement="top" title="Cancel"><span class="dashicons dashicons-no-alt"></span></div>
			<div class="yp-clear"></div>
		</div>
		<div class="scenes">
			<div class="scene scene-active scene-1" data-scene="scene-1"><p><span class="scene-info">i</span>Scene 1 <span><input autocomplete="off" type='text' value='0' /></span></p></div>
			<div class="scene scene-2 scene-no-click-yet" data-scene="scene-2"><p><span class="scene-info">i</span>Scene 2 <span><input type='text' autocomplete="off" value='100' /></span></p></div>
			<div class="scene scene-add"><span class="dashicons dashicons-plus"></span></div>
			<div class="yp-clear"></div>
		</div>
	</div>

	<script src='<?php echo plugins_url( 'js/jquery.js?ver='.YP_VERSION.'' , __FILE__ ); ?>'></script>

	<script>
	(function($){

		// Reload the page after browser undo & undo
		if (!!window.performance && window.performance.navigation.type === 2) {
            yp_load_note("Editor Reloading..");
            window.location.reload();
        }

		// All plugin element list
        window.plugin_classes_list = 'yp-no-wireframe|yp-element-not-visible|yp-iframe-placeholder|yp-element-picker-active|yp-data-updated|yp-animate-data|yp-inline-data|yellow-pencil-data|yp-animating|yp-scene-1|yp-sharp-selector-mode-active|yp-scene-2|yp-scene-3|yp-scene-4|yp-scene-5|yp-scene-6|yp-anim-creator|data-anim-scene|yp-animate-test-playing|ui-draggable-handle|yp-css-data-trigger|yp-yellow-pencil-demo-mode|yp-yellow-pencil-loaded|yp-element-resized|resize-time-delay|yp-selected-handle|yp_onscreen|yp_hover|yp_click|yp_focus|yp-recent-hover-element|yp-selected-others|yp-multiple-selected|yp-demo-link|yp-live-editor-link|yp-yellow-pencil|yp-content-selected|yp-hide-borders-now|ui-draggable|yp-selector-editor-active|yp-closed|yp-responsive-device-mode|yp-metric-disable|yp-css-editor-active|wtfv|yp-clean-look|yp-has-transform|yp-will-selected|yp-selected|yp-element-resizing|yp-element-resizing-width-left|yp-element-resizing-width-right|yp-element-resizing-height-top|yp-element-resizing-height-bottom|context-menu-active|yp-selectors-hide|yp-contextmenuopen|yp-control-key-down|yp-selected-others-multiable-box|yp-iframe-mouseleave|yp-selected-boxed-top|yp-selected-boxed-bottom|yp-selected-boxed-left|yp-selected-boxed-right|yp-selected-boxed-margin-left|yp-zero-margin-w|yp-animate-manager-active|yp-wireframe-mode|yp-selector-hover|yp-size-handle|ypdw|ypdh|yp-body-selector-mode-active|yp-selected-boxed-margin-top|yp-selected-boxed-margin-bottom|yp-selected-boxed-margin-right|yp-selected-boxed-padding-left|yp-selected-boxed-padding-top|yp-selected-boxed-padding-bottom|yp-selected-boxed-padding-right|yp-selected-tooltip|yp-edit-tooltip|yp-edit-menu|yp-resorted|yp-full-width-selected|yp-zero-margin-h|yp-tooltip-small';

        // Replace all parent ways
        for(var s = 0; s < 51; s++){
        	window.plugin_classes_list += "|yp-parent-way-" + s;
        }

        // Any visible element.
        window.simple_not_selector = 'head, script, style, link, meta, title, noscript, svg, canvas, br';

        // Any visible element 2.
        window.simple_not_selector2 = 'head, script, style, link, meta, title, noscript, svg, canvas, br, [class^="yp-"]:not(.yp-selected):not(.yp-selected-others), [class*="yellow-pencil-"]';

        // basic simple.
        window.basic_not_selector = '*:not(script):not(style):not(link):not(meta):not(title):not(noscript):not(head):not(circle):not(rect):not(polygon):not(defs):not(linearGradient):not(stop):not(ellipse):not(text):not(canvas):not(line):not(polyline):not(path):not(g):not(tspan)';

		// Variable
		window.loadStatus = false;

		// Document Load Note:
		yp_load_note("Editor loading..");

		// Document ready.
		$(document).ready(function(){

			// Load iframe.
	        ($('#iframe')[0].contentWindow || $('#iframe')[0].contentDocument).location.replace($("#iframe").attr("data-href"));

	        // Frame load note:
	        yp_load_note("Reading styles..");

	        // Frame ready
	        $('#iframe').on('load', function(){

	        	var iframe = $($('#iframe').contents().get(0));
            	var iframeBody = iframe.find("body");
            	var body = $(document.body).add(iframeBody);

            	// Get iframe in JS
            	var iframejs = document.getElementById('iframe');
            	var iframeContentWindow = (iframejs.contentWindow || iframejs.contentDocument);
            	var iframeURL = iframeContentWindow.location.href;

            	// check if iframe URL is not valid.
            	if(iframeURL.indexOf("yellow_pencil_frame") == -1){
            		
            		// give information before redirect
            		yp_load_note("Page was redirected..");
            		alert("The target page was redirected to another page, click OK to continue...");

            		// Get parent url
                    var parentURL = window.location;

                    //delete after href.
                    parentURL = parentURL.toString().split("href=")[0] + "href=";

                    // Dedect the target page and load
                    $.post(iframeURL, {

                        yp_get_details: "true",

                    }).done(function(data){

                        // Find page details
                        data = $('<div />').append(data).find('#yp_page_details').html();

                        // find all
                        var pageID = data.split("|")[0];
                        var pageTYPE = data.split("|")[1];
                        var pageMODE = data.split("|")[2];

                        // Update result URL
                        iframeURL = iframeURL.replace(/.*?:\/\//g, ""); // delete protocol
                        iframeURL = iframeURL.replace("&yellow_pencil_frame=true", "").replace("?yellow_pencil_frame=true", "");
                        iframeURL = encodeURIComponent(iframeURL); // encode url
                        parentURL = parentURL + iframeURL + "&yp_page_id="+pageID+"&yp_page_type="+pageTYPE+"&yp_mode=" + pageMODE; // update parent URL

                        // GO
                        window.location = parentURL;

                    }).fail(function(){
                        alert(lang.page_information_cant_be_retrieved);
                    });

                    return false;
            		
            	}

            	// Moving styles to iframe
				var editorData = $("#yellow-pencil-iframe-data");
				iframeBody.append(editorData.html().replace(/(^\<\!\-\-|\-\-\>$)/g, ""));

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

	        	// Loading Styles
				var styles = [
					"<?php echo esc_url(includes_url( 'css/dashicons.min.css' , __FILE__ )); ?>",
					"<?php echo esc_url(plugins_url( 'css/contextmenu.css?ver='.YP_VERSION.'' , __FILE__ )); ?>",
					"<?php $prtcl = is_ssl() ? 'https' : 'http'; echo $prtcl; ?>://fonts.googleapis.com/css?family=Roboto+Mono|Roboto:400,500",
					"<?php echo esc_url(plugins_url( 'css/nouislider.css?ver='.YP_VERSION.'' , __FILE__ )); ?>",
					"<?php echo esc_url(plugins_url( 'css/iris.css?ver='.YP_VERSION.'' , __FILE__ )); ?>",
					"<?php echo esc_url(plugins_url( 'css/bootstrap-tooltip.css?ver='.YP_VERSION.'' , __FILE__ )); ?>",
					"<?php echo esc_url(plugins_url( 'css/sweetalert.css?ver='.YP_VERSION.'' , __FILE__ )); ?>",
					"<?php echo esc_url(plugins_url( 'css/perfect-scrollbar.css?ver='.YP_VERSION.'' , __FILE__ )); ?>",
					"<?php echo esc_url(plugins_url( 'css/yellow-pencil.css?ver='.YP_VERSION.'' , __FILE__ )); ?>"
				];

				// Loading.
				for(var i = 0; i < styles.length; i++){
					yp_load_css(styles[i]);
				}

				// let the user feel as that loads quickly.
				setTimeout(function(){
					yp_load_note("Analyzes the page..");
				},1600);

				setTimeout(function(){
					yp_load_note("Playing with codes..");
				},3200);

				setTimeout(function(){
					yp_load_note("Almost ready..");
				},6400);

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
					"<?php echo plugins_url( 'library/ace/ace.js' , __FILE__ ); ?>",
					"<?php echo plugins_url( 'library/ace/ext-language_tools.js' , __FILE__ ); ?>",
					"<?php echo plugins_url( 'js/sweetalert.js?ver='.YP_VERSION.'' , __FILE__ ); ?>",
					"<?php echo plugins_url( 'js/perfect-scrollbar.js?ver='.YP_VERSION.'' , __FILE__ ); ?>",
					"<?php echo plugins_url( 'js/yellow-pencil.js?ver='.YP_VERSION.'' , __FILE__ ); ?>"
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
				    window.definedStyleStatus = false;
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
				            
				            href.indexOf("color") == -1 &&
				            href.indexOf("skin") == -1 &&
				            href.indexOf("scheme") == -1 &&

				            href.indexOf("setting") == -1 &&
				            href.indexOf("admin") == -1 &&
				            newLoadList.length <= 10){

				            	// Add
				               	newLoadList.push(href);

				        }

				      });


				    // Start the editor, loading stylesheets while user start customization.
				    yp_start_editor();


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
				                	window.definedStyleStatus = true;
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
		            yp_load_note("Just a sec!");

		            // Set true.
		            window.loadStatus = true;
		            
		            // Okay. Load it.
		            setTimeout(function(){
		            	yellow_pencil_main();
		            },20);

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

					    error : function (jqXHR, textStatus, errorThrown){
					        alert('An error occurred while loading.');
					    }

					});

				}); // frame ready

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

	<?php yp_editor_styles($id, $type, $mode, "all"); ?>
	<?php yp_footer(); ?>
	</body>
</html>