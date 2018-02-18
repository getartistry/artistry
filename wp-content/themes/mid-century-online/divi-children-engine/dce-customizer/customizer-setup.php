<?php

/**
 * Divi Children Engine Customizer panels and sections setup
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


global $dce_customizer_panels, $dce_customizer_sections, $dce_locked;


/**
 * Translate all Divi Children Engine strings for the Customizer 
 */

load_textdomain( 'divi-children-engine', DCE_PATH . '/lang/divi-children-engine-' . get_user_locale() . '.mo' );


/**
 * Customizer panels array
 */
	
$dce_customizer_panels = array(
	'general-settings'	=>	array( 'dce_general_settings_panel', __( 'General Settings', 'divi-children-engine' ) ),
	'header'			=>	array( 'dce_header_panel', __( 'Header', 'divi-children-engine' ) ),
	'footer'			=>	array( 'dce_footer_panel', __( 'Footer', 'divi-children-engine' ) ),
	'post-customizer'	=>	array( 'dce_post_customizer_panel', __( 'Posts', 'divi-children-engine' ) ),
	'social-icons'		=>	array( 'dce_social_icons_panel', __( 'Social Icons', 'divi-children-engine' ) ),
	'sidebar'			=>	false,
	'custom-modules'	=>	array( 'dce_custom_modules_panel', __( 'Custom Modules', 'divi-children-engine' ) ),
	'custom-css'		=>	false,
	'output-options'	=>	false,	
);


/**
 * Customizer sections array
 *
 * 'section' =>	array( 'panel', 'output?', 'scripts?', 'update?', 'section-title', 'section-description' )
 */

$dce_customizer_sections = array(

	'hide-general'				=>	array( 'general-settings', false, false, false, __( 'Hide sections - General Settings', 'divi-children-engine' ), __( 'Once you have finished customizing some parts of your site you may want those settings to be hidden, so you get a less cluttered Customizer. Here you can check any section and it will not appear the next time you open the Customizer (you can uncheck it back at any time).', 'divi-children-engine' ) ),
	'heading-styles'			=>	array( 'general-settings', 'output', false, false, __( 'Headings h1-h6', 'divi-children-engine' ), __( 'The settings in this section are for general headings and may not apply to every part of the site, depending on specific styles being applied, for example, to Divi modules.', 'divi-children-engine' ) ),
	'paragraphs'				=>	array( 'general-settings', 'output', false, false, __( 'Paragraphs', 'divi-children-engine' ), __( 'The settings in this section are for general paragraphs and may not apply to every part of the site, depending on specific styles being applied, for example, to Divi modules.', 'divi-children-engine' ) ),
	'lists'						=>	array( 'general-settings', 'output', false, false, __( 'Lists', 'divi-children-engine' ), __( 'The settings in this section are for general UL and OL lists and may not apply to every part of the site, depending on specific styles being applied, for example, to Divi modules.', 'divi-children-engine' ) ),

	'hide-header'				=>	array( 'header', false, false, false, __( 'Hide sections - Header', 'divi-children-engine' ), __( 'Once you have finished customizing some parts of your site you may want those settings to be hidden, so you get a less cluttered Customizer. Here you can check any section and it will not appear the next time you open the Customizer (you can uncheck it back at any time).', 'divi-children-engine' ) ),
	'main-header'				=>	array( 'header', 'output', false, false, __( 'Main Header (horizontal navigation)', 'divi-children-engine' ), __( 'These are settings for the Divi Main Header, also named "Primary Menu Bar" in Divi Customizer settings.', 'divi-children-engine' ) ),
	'top-header'				=>	array( 'header', 'output', false, false, __( 'Top Header', 'divi-children-engine' ), __( 'These are settings for the Divi Top Header, also named "Secondary Menu Bar" in Divi Customizer settings.', 'divi-children-engine' ) ),

	'hide-footer'				=>	array( 'footer', false, false, false, __( 'Hide sections - Footer', 'divi-children-engine' ), __( 'Once you have finished customizing some parts of your site you may want those settings to be hidden, so you get a less cluttered Customizer. Here you can check any section and it will not appear the next time you open the Customizer (you can uncheck it back at any time).', 'divi-children-engine' ) ),
	'main-footer'				=>	array( 'footer', 'output', false, false, __( 'Main Footer', 'divi-children-engine' ), '' ),
	'footer-bottom'				=>	array( 'footer', 'output', false, false, __( 'Footer Bottom', 'divi-children-engine' ), '' ),
	'footer-credits'			=>	array( 'footer', 'output', false, false, __( 'Footer Credits', 'divi-children-engine' ), '' ),

	'main-sidebar'				=>	array( 'sidebar', 'output', false, false, __( 'Sidebar', 'divi-children-engine' ), __( 'These are settings for the main sidebar of your site. If you are using any custom Divi Sidebar modules, you will find settings for them in Custom Modules sections.', 'divi-children-engine' ) ),

	'hide-social-icons'			=>	array( 'social-icons', false, false, false, __( 'Hide sections - Social Icons', 'divi-children-engine' ), __( 'Once you have finished customizing some parts of your site you may want those settings to be hidden, so you get a less cluttered Customizer. Here you can check any section and it will not appear the next time you open the Customizer (you can uncheck it back at any time).', 'divi-children-engine' ) ),
	'social-icons'				=>	array( 'social-icons', false, false, false, __( 'Social Icons', 'divi-children-engine' ), '' ),
	'header-social-icons'		=>	array( 'social-icons', 'output', false, false, __( 'Header Social Icons Styles', 'divi-children-engine' ), '' ),
	'footer-social-icons'		=>	array( 'social-icons', 'output', false, false, __( 'Footer Social Icons Styles', 'divi-children-engine' ), '' ),

	'hide-post-customizer'		=>	array( 'post-customizer', false, false, false, __( 'Hide sections - Posts', 'divi-children-engine' ), __( 'Once you have finished customizing some parts of your site you may want those settings to be hidden, so you get a less cluttered Customizer. Here you can check any section and it will not appear the next time you open the Customizer (you can uncheck it back at any time).', 'divi-children-engine' ) ),
	'post-layout'				=>	array( 'post-customizer', 'output', false, false, __( 'Post Layout Builder', 'divi-children-engine' ), '' ),
	'post-custom-styles'		=>	array( 'post-customizer', 'output', false, false, __( 'Custom Post Styles', 'divi-children-engine' ), '' ),
	'post-metadata-elements'	=>	array( 'post-customizer', false, false, false, __( 'Meta Elements', 'divi-children-engine' ), '' ),
	'post-metadata'				=>	array( 'post-customizer', 'output', false, false, __( 'Meta Styles', 'divi-children-engine' ), '' ),

	'hide-custom'				=>	array( 'custom-modules', false, false, false, __( 'Hide sections - Custom Modules', 'divi-children-engine' ), __( 'Once you have finished customizing some parts of your site you may want those settings to be hidden, so you get a less cluttered Customizer. Here you can check any section and it will not appear the next time you open the Customizer (you can uncheck it back at any time).', 'divi-children-engine' ) ),
	'custom-fullwidth-headers'	=>	array( 'custom-modules', 'output', false, false, 'dynamic', __( 'Settings for Fullwidth Header Divi modules with the custom class', 'divi-children-engine' ) ),
	// 'custom-sliders'			=>	array( 'custom-modules', 'output', false, false, 'dynamic', __( 'Settings for Slider Divi modules with the custom class', 'divi-children-engine' ) ),
	'custom-sidebars'			=>	array( 'custom-modules', 'output', false, false, 'dynamic', __( 'Settings for Sidebar Divi modules with the custom ID', 'divi-children-engine' ) ),
	'custom-ctas'				=>	array( 'custom-modules', 'output', false, false, 'dynamic', __( 'Settings for Call To Action Divi modules with the custom class', 'divi-children-engine' ) ),

	'live-custom-css'			=>	array( 'custom-css', 'output', false, false, __( 'Live Custom CSS', 'divi-children-engine'), __( 'The custom CSS you add here will apply only to this child theme, and it can override any CSS added via the Custom CSS field of the Divi Theme Options or the Additional CSS section of the Customizer.', 'divi-children-engine' ) ),
	'output-options'			=>	array( 'output-options', false, false, false, __( 'Output Mode', 'divi-children-engine' ), __( 'Set your site into Development or Production mode, depending on the way your customized CSS is sent to output.', 'divi-children-engine' ) ),	
	
	'cross-section'				=>	array( false, 'output', false, false, false, false ),

);


$dce_locked = get_theme_mod( 'dce_locked', 0 );

?>
