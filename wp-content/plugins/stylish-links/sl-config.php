<?php

if ( ! class_exists( 'Redux' ) ) {
    return;
}

// This is your option name where all the Redux data is stored.
$opt_name = "sl_settings";

/**
 * ---> SET ARGUMENTS
 * All the possible arguments for Redux.
 * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
 * */

$theme = wp_get_theme(); // For use with some settings. Not necessary.

$args = array(
    // TYPICAL -> Change these values as you need/desire
    'opt_name'             => $opt_name,
    // This is where your data is stored in the database and also becomes your global variable name.
    'display_name'         => __( 'Stylish Links', 'sl' ),
    // Name that appears at the top of your panel
    'display_version'      => '1.0',
    // Version that appears at the top of your panel
    'menu_type'            => 'menu',
    //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
    'allow_sub_menu'       => true,
    // Show the sections below the admin menu item or not
    'menu_title'           => __( 'Stylish Links', 'sl' ),
    'page_title'           => __( 'Stylish Links', 'sl' ),
    // You will need to generate a Google API key to use this feature.
    // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
    'google_api_key'       => '',
    // Set it you want google fonts to update weekly. A google_api_key value is required.
    'google_update_weekly' => false,
    // Must be defined to add google fonts to the typography module
    'async_typography'     => true,
    // Use a asynchronous font on the front end or font string
    //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
    'admin_bar'            => false,
    // Show the panel pages on the admin bar
    'admin_bar_icon'       => 'dashicons-portfolio',
    // Choose an icon for the admin bar menu
    'admin_bar_priority'   => 50,
    // Choose an priority for the admin bar menu
    'global_variable'      => '',
    // Set a different name for your global variable other than the opt_name
    'dev_mode'             => false,
    // Show the time the page took to load, etc
    'update_notice'        => true,
    // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
    'customizer'           => true,
    // Enable basic customizer support
    //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
    //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

    // OPTIONAL -> Give you extra features
    'page_priority'        => null,
    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
    'page_parent'          => 'themes.php',
    // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
    'page_permissions'     => 'manage_options',
    // Permissions needed to access the options panel.
    'menu_icon'            => 'dashicons-admin-links',
    // Specify a custom URL to an icon
    'last_tab'             => '',
    // Force your panel to always open to a specific tab (by id)
    'page_icon'            => 'icon-themes',
    // Icon displayed in the admin panel next to your menu_title
    'page_slug'            => 'sl_options',
    // Page slug used to denote the panel
    'save_defaults'        => true,
    // On load save the defaults to DB before user clicks save or not
    'default_show'         => false,
    // If true, shows the default value next to each field that is not the default value.
    'default_mark'         => '',
    // What to print by the field's title if the value shown is default. Suggested: *
    'show_import_export'   => true,
    // Shows the Import/Export panel when not used as a field.

    // CAREFUL -> These options are for advanced use only
    'transient_time'       => 60 * MINUTE_IN_SECONDS,
    'output'               => true,
    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
    'output_tag'           => true,
    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
    // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

    // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
    'database'             => '',
    // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!

    'use_cdn'              => true,
    // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.

    //'compiler'             => true,

    // HINTS
    'hints'                => array(
        'icon'          => 'el el-question-sign',
        'icon_position' => 'right',
        'icon_color'    => 'lightgray',
        'icon_size'     => 'normal',
        'tip_style'     => array(
            'color'   => 'light',
            'shadow'  => true,
            'rounded' => false,
            'style'   => '',
        ),
        'tip_position'  => array(
            'my' => 'top left',
            'at' => 'bottom right',
        ),
        'tip_effect'    => array(
            'show' => array(
                'effect'   => 'fade',
                'duration' => '200',
                'event'    => 'mouseover',
            ),
            'hide' => array(
                'effect'   => 'fade',
                'duration' => '500',
                'event'    => 'click mouseleave',
            ),
        ),
    )
);

// ADMIN BAR LINKS -> Setup custom links in the admin bar menu as external items.
/*
$args['admin_bar_links'][] = array(
    'id'    => 'redux-docs',
    'href'  => 'http://docs.reduxframework.com/',
    'title' => __( 'Documentation', 'sl' ),
);

$args['admin_bar_links'][] = array(
    //'id'    => 'redux-support',
    'href'  => 'https://github.com/ReduxFramework/redux-framework/issues',
    'title' => __( 'Support', 'sl' ),
);

$args['admin_bar_links'][] = array(
    'id'    => 'redux-extensions',
    'href'  => 'reduxframework.com/extensions',
    'title' => __( 'Extensions', 'sl' ),
);
*/

// SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
/*
$args['share_icons'][] = array(
    'url'   => 'https://github.com/ReduxFramework/ReduxFramework',
    'title' => 'Visit us on GitHub',
    'icon'  => 'el el-github'
    //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
);
*/

// Panel Intro text -> before the form
/*
if ( ! isset( $args['global_variable'] ) || $args['global_variable'] !== false ) {
    if ( ! empty( $args['global_variable'] ) ) {
        $v = $args['global_variable'];
    } else {
        $v = str_replace( '-', '_', $args['opt_name'] );
    }
    $args['intro_text'] = sprintf( __( '<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>', 'sl' ), $v );
} else {
    $args['intro_text'] = __( '<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'sl' );
}
*/
$args['intro_text'] = __( '<p>Pro version available soon!</p>', 'sl' );
// Add content after the form.
$args['footer_text'] = __( '<p>Pro version available soon!</p>', 'sl' );


Redux::setArgs( $opt_name, $args );

/*
 * ---> END ARGUMENTS
 */

/*
 * ---> START HELP TABS
 */

/*
$tabs = array(
    array(
        'id'      => 'redux-help-tab-1',
        'title'   => __( 'Theme Information 1', 'sl' ),
        'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'sl' )
    ),
    array(
        'id'      => 'redux-help-tab-2',
        'title'   => __( 'Theme Information 2', 'sl' ),
        'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'sl' )
    )
);
Redux::setHelpTab( $opt_name, $tabs );

// Set the help sidebar
$content = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'sl' );
Redux::setHelpSidebar( $opt_name, $content );
*/

/*
 * <--- END HELP TABS
 */


/*
 *
 * ---> START SECTIONS
 *
 */

/*

    As of Redux 3.5+, there is an extensive API. This API can be used in a mix/match mode allowing for


 */

// -> START Styles
Redux::setSection( $opt_name, array(
    'title'      => __( 'General', 'sl' ),
    'id'         => 'general',
    'desc'		 => __( 'These settings will be applied to all hyperlinks within the content of all of your posts and pages.', 'sl' ),
    'icon'       => 'el el-adjust-alt',
    'fields'     => array(
        array(
            'id'       => 'general-enable-posts',
            'type'     => 'switch',
            'title'    => __( 'Single Post Content', 'sl' ),
            'subtitle' => __( 'Applies to links within main content when viewing an individual post.', 'sl' ),
            'default'  => true,
        ),
        array(
            'id'       => 'general-enable-pages',
            'type'     => 'switch',
            'title'    => __( 'Single Page Content', 'sl' ),
            'subtitle' => __( 'Applies to links within main content when viewing a standard page.', 'sl' ),
            'default'  => true,
        ),
        array(
            'id'       => 'general-enable-archives',
            'type'     => 'switch',
            'title'    => __( 'Archives', 'sl' ),
            'subtitle' => __( 'Applies to links within the loop only when full post content is displayed.', 'sl' ),
            'class'    => 'sl-locked',
            'default'  => false,
        ),
        array(
            'id'       => 'general-enable-home',
            'type'     => 'switch',
            'title'    => __( 'Home Page Posts', 'sl' ),
            'subtitle' => __( 'Applies to links within the loop only when full content is displayed.', 'sl' ),
            'class'    => 'sl-locked',
            'default'  => false,
        ),
        array(
            'id'       => 'general-enable-menus',
            'type'     => 'switch',
            'title'    => __( 'Menus', 'sl' ),
            'subtitle' => __( 'Applies to links within all menus.', 'sl' ),
            'class'    => 'sl-locked',
            'default'  => false,
        ),
        array(
            'id'       => 'general-enable-comments',
            'type'     => 'switch',
            'title'    => __( 'Comments', 'sl' ),
            'subtitle' => __( 'Applies to links within post comments.', 'sl' ),
            'class'    => 'sl-locked',
            'default'  => false,
        ),
        array(
            'id'       => 'general-enable-widgets-text',
            'type'     => 'switch',
            'title'    => __( 'Text Widgets', 'sl' ),
            'subtitle' => __( 'Applies to links within all text widgets.', 'sl' ),
            'class'    => 'sl-locked',
            'default'  => false,
        ),
        array(
            'id'       => 'general-enable-widgets-all',
            'type'     => 'switch',
            'title'    => __( 'All Widgets', 'sl' ),
            'subtitle' => __( 'Applies to links within all widgets.', 'sl' ),
            'class'    => 'sl-locked',
            'default'  => false,
        ),
        array(
            'id'            => 'general-underline-thickness',
            'type'          => 'slider',
            'title'         => __( 'Underline Thickness', 'sl' ),
            'subtitle'      => __( 'Recommended range: 1 - 5', 'sl' ),
            'hint'          => array(
                                    'title'   => 'Pixel-based',
                                    'content' => 'We provide a maximum of 20px but you generally would never need to go that high unless you have some GIANT text on your site.'
                                ),
            'default'       => 1,
            'min'           => 1,
            'step'          => 1,
            'max'           => 20,
            'display_value' => 'text'
        ),
        array(
            'id'            => 'general-underline-offset',
            'type'          => 'slider',
            'title'         => __( 'Underline Offset', 'sl' ),
            'subtitle'      => __( 'Recommended range: 90 - 100', 'sl' ),
            'hint'          => array(
                                    'title'   => 'Percentage-based',
                                    'content' => ''
                                ),
            'default'       => 94,
            'min'           => 0,
            'step'          => 1,
            'max'           => 100,
            'display_value' => 'text'
        ),
        array(
            'id'       => 'general-hide-outline-hover',
            'type'     => 'switch',
            'title'    => __( 'Hide Outline On Hover', 'sl' ),
            'subtitle' => __( 'This is handy if you use a hover background.', 'sl' ),
            'default'  => false,
        ),
        array(
            'id'       => 'general-animation-underline-style',
            'type'     => 'radio',
            'title'    => __( 'Underline Hover Animation', 'sl' ),
            'subtitle' => __( 'Drop, Stretch, and Bounce options only take effect if Underline Offset (above) is set less than 100%. Also, any option selected other than None, Drop, Stretch, and Bounce will disable the background hover color (if one is selected).', 'sl' ),
            'desc' => __( '* does not work in Microsoft browsers (IE and Edge)', 'sl' ),
            'class'    => 'sl-locked',
            'options'  => array(
                'sl-animate-underline-none' => 'None',
                'sl-animate-underline-drop' => 'Drop',
                'sl-animate-underline-stretch' => 'Stretch',
                'sl-animate-underline-bounce' => 'Bounce',
                'sl-animate-underline-vanish-center' => 'Vanish center*',
                'sl-animate-underline-vanish-left' => 'Vanish left*',
                'sl-animate-underline-vanish-right' => 'Vanish right*',
                'sl-animate-underline-pulse-center' => 'Pulse center*',
                'sl-animate-underline-pulse-left' => 'Pulse left*',
                'sl-animate-underline-pulse-right' => 'Pulse right*',
                'sl-animate-underline-progress-left' => 'Progress left*',
                'sl-animate-underline-progress-right' => 'Progress right*'
            ),
            'default'  => 'sl-animate-underline-vanish-center',
            'required' => array( 'color-underline-hover', 'not_empty_and', 'transparent' )
        ),
        array(
            'id'       => 'sl-css',
            'type'     => 'ace_editor',
            'title'    => __( 'Custom CSS', 'sl' ),
            'subtitle' => __( 'You can overwrite the plugin css with your own custom css.', 'sl' ),
            'mode'     => 'css',
            'theme'    => 'monokai',
            'default'  => "
a.stylish-link, 
li.stylish-link > a { 
    /* applies to all links that are set to be styled by the plugin */
    
    box-shadow:none;
    /* ... */

}
a.stylish-link i, 
li.stylish-link > a i { 
    /* applies to icons within the styled links */
    
    font-size:60%;
    /* ... */

}"
        ),
    )
) );

// -> START Colors
Redux::setSection( $opt_name, array(
    'title'      => __( 'Colors', 'sl' ),
    'id'         => 'color',
    'desc'       => __( 'Dress up your post and page content hyperlinks with custom colors.', 'sl' ),
    'icon'       => 'el el-tint',
    'fields'     => array(
        array(
            'id'       => 'color-content-background',
            'type'     => 'color',
            'title'    => __( 'Content Background', 'sl' ),
            'subtitle' => __( 'Select the color of your main content backround so the link effects work correctly.', 'sl' ),
            'default'  => '#FFFFFF'
        ),
        array(
            'id'       => 'color-underline',
            'type'     => 'color',
            'title'    => __( 'Underline', 'sl' ),
            'default'  => '#dd3360'
        ),
        array(
            'id'       => 'color-text',
            'type'     => 'color',
            'title'    => __( 'Text', 'sl' ),
            'default'  => '#000000',
            'output'   => array(
                            'color' => 'a.stylish-link, li.stylish-link > a'
                        )
        ),
        array(
            'id'       => 'color-background',
            'type'     => 'color',
            'title'    => __( 'Background', 'sl' ),
            'default'  => 'transparent',
            'output'   => array(
                            'background-color' => 'a.stylish-link, li.stylish-link > a'
                        )
        ),
        array(
            'id'       => 'color-icon',
            'type'     => 'color',
            'title'    => __( 'Icons', 'sl' ),
            'default'  => '#dd9fb0',
            'class'    => 'sl-locked',
            'output'   => array(
                            'color' => 'a.stylish-link i, li.stylish-link > a i'
                        )
        ),
        array(
            'id'       => 'color-underline-hover',
            'type'     => 'color',
            'title'    => __( 'Underline Hover', 'sl' ),
            'subtitle' => __( 'Select a color in order for Underground Hover Animation effect to be accessible (in the General panel)', 'sl' ),
            'default'  => 'transparent',
        ),
        array(
            'id'       => 'color-text-hover',
            'type'     => 'color',
            'title'    => __( 'Text Hover', 'sl' ),
            'default'  => '#dd3360',
        ),
        array(
            'id'       => 'color-background-hover',
            'type'     => 'color',
            'title'    => __( 'Background Hover', 'sl' ),
            'default'  => 'transparent',
            'output'   => array(
                            'background-color' => 'a.stylish-link:hover, li.stylish-link > a:hover'
                        )
        ),
        array(
            'id'       => 'color-icon-hover',
            'type'     => 'color',
            'title'    => __( 'Icons Hover', 'sl' ),
            'default'  => '#dd3360',
            'class'    => 'sl-locked',
            'output'   => array(
                            'color' => 'a.stylish-link:hover i, li.stylish-link > a:hover i'
                        )
        ),
        
    )
) );

// -> START Icons
Redux::setSection( $opt_name, array(
    'title'      => __( 'Icons', 'sl' ),
    'id'         => 'icon',
    'desc'       => __( 'Give visual cues to indicate what is being linked to. Leave blank or unselect an icon to remove that functionality.', 'sl' ),
    'icon'       => 'el el-eye-open',
    'fields'     => array(
        array(
            'id'       => 'icon-animate',
            'type'     => 'switch',
            'title'    => __( 'Animate Icons On Hover', 'sl' ),
            'class'    => 'sl-locked',
            'default'  => false,
        ),
        array(
            'id'       => 'icon-external',
            'type'     => 'select',
            'data'     => 'elusive-icons',
            'title'    => __( 'External Icon', 'sl' ),
            'subtitle' => __( 'Detects if a link points to a different domain', 'sl' ),
            'class'    => 'sl-locked',
            'default'  => 'el el-share-alt'
        ),
        array(
            'id'       => 'icon-anchor',
            'type'     => 'select',
            'data'     => 'elusive-icons',
            'title'    => __( 'Anchor Icon', 'sl' ),
            'subtitle' => __( 'Detects if a link points to an anchor on the same page', 'sl' ),
            'class'    => 'sl-locked',
            'default'  => 'el el-chevron-down'
        ),
        array(
            'id'       => 'icon-file',
            'type'     => 'select',
            'data'     => 'elusive-icons',
            'title'    => __( 'File Download Icon', 'sl' ),
            'subtitle' => __( 'Detects if a link points to a file download', 'sl' ),
            'class'    => 'sl-locked',
            'default'  => 'el el-file'
        ),
    )
) );


/*
 * <--- END SECTIONS
 */


?>