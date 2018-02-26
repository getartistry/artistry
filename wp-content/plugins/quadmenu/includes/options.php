<?php

if (!defined('ABSPATH')) {
    die('-1');
}

class QuadMenu_Options {

    private $themes_defaults = array();
    private $locations_defaults = array();

    public function __construct() {

        // include options that the user can't change
        add_filter('redux/options/' . QUADMENU_REDUX . '/options', array($this, 'developer'));
        add_filter('redux/options/' . QUADMENU_REDUX . '/ajax_save/response', array($this, 'developer_ajax'));

        if (!is_admin() && !is_customize_preview())
            return;

        add_filter('redux/options/' . QUADMENU_REDUX . '/sections', array($this, 'configuration'));
        add_filter('redux/options/' . QUADMENU_REDUX . '/sections', array($this, 'locations'));
        add_filter('redux/options/' . QUADMENU_REDUX . '/sections', array($this, 'responsive'));
        add_filter('redux/options/' . QUADMENU_REDUX . '/sections', array($this, 'themes'));
        add_filter('redux/options/' . QUADMENU_REDUX . '/sections', array($this, 'css'));
        add_filter('redux/page/' . QUADMENU_REDUX . '/form/before', array($this, 'remove'));
    }
    
    function developer($options = array()) {

        $developer = apply_filters('quadmenu_developer_options', array());

        if (!empty($developer) && is_array($developer)) {

            $options = array_merge($options, $developer);

            foreach ($developer as $id => $value) {

                add_filter('redux/options/' . QUADMENU_REDUX . '/field/' . $id, '__return_null');
            }
        }

        return $options;
    }

    function developer_ajax($return_array) {

        $return_array['options'] = apply_filters('quadmenu_developer_options', $return_array['options']);

        return $return_array;
    }

    function remove($redux) {

        foreach ($redux->parent->sections as $key => $section) {

            if (empty($section['fields'])) {

                unset($redux->parent->sections[$key]);
            }
        }

        return $redux;
    }

    function configuration($sections) {

        $defaults = apply_filters('quadmenu_default_options', array());

        $sections[] = array(
            'id' => 'quadmenu_configuration',
            'title' => esc_html__('Configuration', 'quadmenu'),
            'heading' => false,
            'icon' => 'quadmenu-cog',
            'customizer' => false,
            'permissions' => 'manage_options',
            'fields' => array(
                array(
                    'id' => 'viewport',
                    'type' => 'switch',
                    'title' => esc_html__('Viewport', 'quadmenu'),
                    'subtitle' => esc_html__('Include the viewport meta tag in site head.', 'quadmenu'),
                    'compiler' => false,
                    'default' => (bool) $defaults['viewport'],
                ),
                array(
                    'id' => 'styles',
                    'type' => 'switch',
                    'title' => esc_html__('Stylesheet', 'quadmenu'),
                    'subtitle' => esc_html__('Load styles in your theme.', 'quadmenu'),
                    'compiler' => false,
                    'default' => (bool) $defaults['styles'],
                ),
                array(
                    'id' => 'styles_normalize',
                    'type' => 'switch',
                    'title' => esc_html__('Normalize', 'quadmenu'),
                    'subtitle' => esc_html__('Try to clean residual styling of the theme.', 'quadmenu'),
                    'required' => array(
                        'styles',
                        '=',
                        1
                    ),
                    'compiler' => false,
                    'default' => (bool) $defaults['styles_normalize'],
                ),
                array(
                    'id' => 'styles_widgets',
                    'type' => 'switch',
                    'title' => esc_html__('Widgets', 'quadmenu'),
                    'subtitle' => esc_html__('Load default widgets stylesheets.', 'quadmenu'),
                    'required' => array(
                        'styles',
                        '=',
                        1
                    ),
                    'compiler' => false,
                    'default' => (bool) $defaults['styles_widgets'],
                ),
                array(
                    'id' => 'styles_pscrollbar',
                    'type' => 'switch',
                    'title' => esc_html__('Perfect ScrollBar', 'quadmenu'),
                    'subtitle' => esc_html__('Load Perfect scroll bar files.', 'quadmenu'),
                    'compiler' => false,
                    'default' => (bool) $defaults['styles_pscrollbar'],
                ),
                array(
                    'id' => 'styles_owlcarousel',
                    'type' => 'switch',
                    'title' => esc_html__('OWL Carousel', 'quadmenu'),
                    'subtitle' => esc_html__('Load OWL Carousel files.', 'quadmenu'),
                    'compiler' => false,
                    'default' => (bool) $defaults['styles_owlcarousel'],
                ),
                array(
                    'id' => 'styles_icons',
                    'type' => 'select',
                    'options' => _QuadMenu()->registered_icons_names(),
                    'title' => esc_html__('Icons', 'quadmenu'),
                    'subtitle' => esc_html__('Select the fonticon for your menus.', 'quadmenu'),
                    'select2' => array(
                        'allowClear' => false
                    ),
                    'required' => array(
                        'styles',
                        '=',
                        1
                    ),
                    'compiler' => false,
                    'default' => $defaults['styles_icons'],
                    'validate' => 'no_special_chars',
                ),
            /* array(
              //'class' => 'hide',
              'id' => 'quadmenu_themes',
              'type' => 'multi_text',
              'title' => __('Themes'),
              'validate' => 'no_special_chars',
              'subtitle' => __('Create new themes', 'quadmenu'),
              'add_text' => '+',
              'default' => ''
              ), */
            ),
        );

        return $sections;
    }

    function css($sections) {

        $defaults = apply_filters('quadmenu_default_options', array());

        $sections[] = array(
            'id' => 'css',
            'title' => esc_html__('Customize', 'quadmenu'),
            'heading' => false,
            'icon' => 'quadmenu-magic-wand',
            'permissions' => 'edit_theme_options',
            'fields' => array(
                array(
                    'id' => 'css',
                    'type' => 'ace_editor',
                    'mode' => 'css',
                    'title' => esc_html__('Custom CSS', 'quadmenu'),
                    'subtitle' => esc_html__('Quickly add some CSS to your theme by adding it to this block.', 'quadmenu'),
                    'theme' => 'chrome',
                    'compiler' => false,
                    'default' => $defaults['css']
                ),
            ),
        );

        return $sections;
    }

    function locations($sections) {

        global $_wp_registered_nav_menus;

        $theme = get_stylesheet();

        $this->locations_defaults = apply_filters('quadmenu_default_options_locations', $this->locations_defaults);

        foreach ($_wp_registered_nav_menus as $location => $name) {

            $location_defaults = apply_filters('quadmenu_default_options_location_' . $location, $this->locations_defaults);

            foreach ($location_defaults as $key => $value) {

                $this->locations_defaults["{$location}_{$key}"] = $value;
            }
        }

        foreach ($_wp_registered_nav_menus as $key => $name) {

            $sections[] = array(
                'id' => 'quadmenu_location_' . $key,
                'title' => $name,
                'heading' => false,
                'subsection' => true,
                'customizer' => false,
                'permissions' => 'edit_theme_options',
                'fields' => array(
                    array(
                        'id' => $key . '_integration',
                        'type' => 'switch',
                        'title' => esc_html__('Integration', 'quadmenu'),
                        'subtitle' => esc_html__('Integrate QuadMenu in this theme location.', 'quadmenu'),
                        'customizer' => false,
                        'default' => $this->locations_defaults[$key . '_integration'],
                    ),
                    array(
                        'id' => $key . '_unwrap',
                        'type' => 'switch',
                        'title' => esc_html__('Conflicts', 'quadmenu'),
                        'subtitle' => esc_html__('Try to solve conflicts of residual theme style.', 'quadmenu'),
                        'customizer' => false,
                        'default' => $this->locations_defaults[$key . '_unwrap'],
                    ),
                    array(
                        'id' => $key . '_theme',
                        'type' => 'select',
                        'title' => __('Theme', 'quadmenu'),
                        'subtitle' => __('Select a theme for this theme location', 'redux-framework-demo'),
                        'options' => (array) $GLOBALS['quadmenu_themes'],
                        'default' => $this->locations_defaults[$key . '_theme'],
                    ),
                    array(
                        'id' => $key . '_information',
                        'type' => 'info',
                        'title' => esc_html__('Integration', 'quadmenu'),
                        'style' => 'success',
                        'desc' => sprintf('
              <p>%1$s</p>
              <p>%2$s</p>'
                                , esc_html__('Integrate QuadMenu in this theme location automatically. Works with most modularly coded themes.', 'quadmenu')
                                , esc_html__('If your menu doesnt seem to be working properly after using Automatic Integration, the most common scenario is that you have residual styling from your theme and would need to use Manual Integration instead.', 'quadmenu')
                        ),
                        'customizer' => false,
                        'required' => array(
                            $key . '_integration',
                            '=',
                            1
                        ),
                    ),
                    array(
                        'id' => $key . '_manual',
                        'type' => 'info',
                        'title' => esc_html__('Manual Integration', 'quadmenu'),
                        'style' => 'info',
                        'desc' => sprintf(''
                                . '<p>This methods allows you to integrate QuadMenu into your theme. '
                                . '<p><code>[quadmenu theme_location=&quot;%2$s&quot;]</code></p>'
                                . '<p>Simply copy the generated shortcode and paste it where you would like the menu to appear.</p>'
                                . '<p><code>&lt;?php quadmenu(array(&quot;theme_location&quot; => &quot;%2$s&quot;)); ?&gt;</code></p>'
                                . '<p>Simply copy the generated PHP function code and paste it into the appropriate template in your theme.</p>', $theme, $key == 'all' ? 'replace with your theme location' : $key, 'quadmenu'),
                        'customizer' => false,
                    ),
                )
            );
        }

        return $sections;
    }

    function responsive($sections) {

        $defaults = apply_filters('quadmenu_default_options', array());

        $sections[] = array(
            'id' => 'quadmenu_responsive',
            'title' => esc_html__('Responsive', 'quadmenu'),
            'heading' => false,
            'icon' => 'quadmenu-responsive',
            'customizer' => false,
            'permissions' => 'edit_theme_options',
            'fields' => array(
                array(
                    'id' => 'gutter',
                    'type' => 'slider',
                    'title' => esc_html__('Gutter', 'quadmenu'),
                    'subtitle' => esc_html__('Padding between columns.', 'quadmenu'),
                    'min' => '0',
                    'step' => '2',
                    'max' => '60',
                    'compiler' => true,
                    'default' => (int) $defaults['gutter'],
                    'validate' => 'numeric'
                ),
                array(
                    'id' => 'screen_sm_width',
                    'type' => 'slider',
                    'title' => esc_html__('Screen Small', 'quadmenu'),
                    'subtitle' => esc_html__('Small screens breakpoint.', 'quadmenu'),
                    'min' => '480',
                    'step' => '1',
                    'max' => '1000',
                    'compiler' => true,
                    'default' => (int) $defaults['screen_sm_width'],
                    'validate' => 'numeric'
                ),
                array(
                    'id' => 'screen_md_width',
                    'type' => 'slider',
                    'title' => esc_html__('Screen Medium', 'quadmenu'),
                    'subtitle' => esc_html__('Medium screens breakpoint.', 'quadmenu'),
                    'min' => '481',
                    'step' => '1',
                    'max' => '1200',
                    'compiler' => true,
                    'default' => (int) $defaults['screen_md_width'],
                    'validate' => 'numeric'
                ),
                array(
                    'id' => 'screen_lg_width',
                    'type' => 'slider',
                    'title' => esc_html__('Screen Large', 'quadmenu'),
                    'subtitle' => esc_html__('Large screens breakpoint.', 'quadmenu'),
                    'min' => '801',
                    'step' => '1',
                    'max' => '1600',
                    'compiler' => true,
                    'default' => (int) $defaults['screen_lg_width'],
                    'validate' => 'numeric'
                ),
            )
        );

        return $sections;
    }

    function themes($sections) {

        global $quadmenu_themes;

        $this->themes_defaults = apply_filters('quadmenu_default_options_themes', $this->themes_defaults);

        foreach ($quadmenu_themes as $theme => $name) {

            $theme_defaults = apply_filters('quadmenu_default_options_theme_' . $theme, $this->themes_defaults);

            foreach ($theme_defaults as $key => $value) {

                $this->themes_defaults["{$theme}_{$key}"] = $value;
            }
        }

        foreach ($quadmenu_themes as $key => $theme) {

            $sections[] = array(
                'id' => 'quadmenu_layout_' . $key,
                'title' => $theme,
                'icon' => 'quadmenu-th-menu-outline',
                'heading' => false,
                'subsection' => false,
                'customizer' => true,
                'permissions' => 'edit_theme_options',
                'class' => 'quadmenu_theme_' . $key,
                'fields' => array(
                    array(
                        'id' => $key . '_theme_title',
                        'type' => 'text',
                        'customizer' => false,
                        'title' => esc_html__('Theme', 'quadmenu'),
                        'subtitle' => esc_html__('Change theme name.', 'quadmenu'),
                        'default' => $theme,
                        'validate' => 'no_special_chars',
                    ),
                    array(
                        'id' => $key . '_layout',
                        'type' => 'image_select',
                        'title' => esc_html__('Menu', 'quadmenu'),
                        'subtitle' => esc_html__('Change menu layout.', 'quadmenu'),
                        'options' => array(
                            'collapse' => array(
                                'alt' => esc_html__('Collapse', 'quadmenu'),
                                'img' => QUADMENU_URL_ASSETS . 'backend/images/layouts/collapse.png'
                            ),
                            'offcanvas' => array(
                                'alt' => esc_html__('Offcanvas', 'quadmenu'),
                                'img' => QUADMENU_URL_ASSETS . 'backend/images/layouts/offcanvas.png'
                            ),
                            'vertical' => array(
                                'alt' => esc_html__('Vertical', 'quadmenu'),
                                'img' => QUADMENU_URL_ASSETS . 'backend/images/layouts/vertical.png'
                            ),
                            'inherit' => array(
                                'alt' => esc_html__('Inherit', 'quadmenu'),
                                'img' => QUADMENU_URL_ASSETS . 'backend/images/layouts/inherit.png'
                            ),
                        ),
                        'default' => $this->validate($this->themes_defaults[$key . '_layout'], array('collapse', 'offcanvas', 'vertical', 'inherit')),
                    ),
                    array(
                        'id' => $key . '_layout_offcanvas_float',
                        'type' => 'image_select',
                        'title' => esc_html__('Float', 'quadmenu'),
                        'subtitle' => esc_html__('Vertical menu position.', 'quadmenu'),
                        'options' => array(
                            'left' => array(
                                'alt' => esc_html__('Left', 'quadmenu'),
                                'img' => QUADMENU_URL_ASSETS . 'backend/images/layouts/fleft.png'
                            ),
                            'right' => array(
                                'alt' => esc_html__('Right', 'quadmenu'),
                                'img' => QUADMENU_URL_ASSETS . 'backend/images/layouts/fright.png'
                            ),
                        ),
                        'required' => array(
                            array($key . '_layout', '=', array('offcanvas', 'vertical')),
                        ),
                        'default' => $this->validate($this->themes_defaults[$key . '_layout_offcanvas_float'], array('left', 'right')),
                    ),
                    array(
                        'id' => $key . '_layout_align',
                        'type' => 'image_select',
                        'title' => esc_html__('Align', 'quadmenu'),
                        'subtitle' => esc_html__('Menu items alignment.', 'quadmenu'),
                        'options' => array(
                            'left' => array(
                                'alt' => esc_html__('Left', 'quadmenu'),
                                'img' => QUADMENU_URL_ASSETS . 'backend/images/layouts/aleft.png'
                            ),
                            /* 'center' => array(
                              'alt' => esc_html__('Center', 'quadmenu'),
                              'img' => QUADMENU_URL_ASSETS . 'backend/images/layouts/acenter.png'
                              ), */
                            'right' => array(
                                'alt' => esc_html__('Right', 'quadmenu'),
                                'img' => QUADMENU_URL_ASSETS . 'backend/images/layouts/aright.png'
                            ),
                        ),
                        'required' => array(
                            array($key . '_layout', '=', array('collapse', 'offcanvas')),
                        ),
                        'default' => $this->validate($this->themes_defaults[$key . '_layout_align'], array('left', 'right')),
                    ),
                    // Width
                    // ---------------------------------------------------------
                    array(
                        'id' => $key . '_behaviour_divider',
                        'type' => 'section',
                        'title' => esc_html__('Behaviour', 'quadmenu'),
                        'indent' => true,
                    ),
                    array(
                        'id' => $key . '_layout_breakpoint',
                        'type' => 'slider',
                        'title' => esc_html__('Breakpoint', 'quadmenu'),
                        'subtitle' => esc_html__('Point at which the navbar becomes uncollapsed.', 'quadmenu'),
                        'min' => '0',
                        'step' => '1',
                        'max' => '1600',
                        'required' => array(
                            array($key . '_layout', '=', array('collapse', 'offcanvas', 'vertical')),
                        ),
                        'default' => (int) $this->themes_defaults[$key . '_layout_breakpoint'],
                    ),
                    array(
                        'id' => $key . '_layout_width',
                        'type' => 'switch',
                        'title' => esc_html__('Width', 'quadmenu'),
                        'subtitle' => esc_html__('Try to force menu width to fit screen.', 'quadmenu'),
                        'required' => array(
                            array($key . '_layout', '=', array('collapse', 'offcanvas')),
                        ),
                        'default' => (int) $this->themes_defaults[$key . '_layout_width'],
                    ),
                    array(
                        'id' => $key . '_layout_width_selector',
                        'type' => 'text',
                        'title' => esc_html__('Inner', 'quadmenu'),
                        'required' => array(
                            array($key . '_layout', '=', array('collapse', 'offcanvas')),
                        ),
                        'default' => $this->themes_defaults[$key . '_layout_width_selector'],
                        'subtitle' => esc_html__('Menu inner width.', 'quadmenu'),
                        'desc' => esc_html__('Force menu inner width to fit selector width.', 'quadmenu')
                    ),
                    array(
                        'id' => $key . '_layout_trigger',
                        'type' => 'button_set',
                        'title' => esc_html__('Trigger', 'quadmenu'),
                        'options' => array(
                            'hoverintent' => esc_html__('Hover', 'quadmenu'),
                            'click' => esc_html__('Click', 'quadmenu')
                        ),
                        'subtitle' => esc_html__('Open dropdown menu on mouseover or click.', 'quadmenu'),
                        'default' => $this->validate($this->themes_defaults[$key . '_layout_trigger'], array('hoverintent', 'click')),
                        'required' => array(
                            array($key . '_layout', '=', array('collapse', 'offcanvas')),
                        ),
                    ),
                    array(
                        'id' => $key . '_layout_current',
                        'type' => 'switch',
                        'title' => esc_html__('Open', 'quadmenu'),
                        'subtitle' => esc_html__('Open dropdown if is current page.', 'quadmenu'),
                        'compiler' => false,
                        'default' => (bool) $this->themes_defaults[$key . '_layout_current'],
                    ),
                    array(
                        'id' => $key . '_layout_animation',
                        'type' => 'select',
                        'options' => apply_filters('quadmenu_options_animations', array(
                            'quadmenu_btt' => esc_html__('Bottom to top', 'quadmenu'),
                            //'quadmenu_rtl' => esc_html__('Right to left', 'quadmenu'),
                            //'quadmenu_ltr' => esc_html__('Left to right', 'quadmenu'),
                            'quadmenu_hinge' => esc_html__('Hinge', 'quadmenu'),
                            'quadmenu_fadeIn' => esc_html__('FadeIn', 'quadmenu'),
                        )),
                        'title' => esc_html__('Dropdown Animation', 'quadmenu'),
                        'subtitle' => esc_html__('Select the animation for the dropdowns.', 'quadmenu'),
                        'default' => $this->themes_defaults[$key . '_layout_animation'],
                        'validate' => 'no_special_chars',
                    ),
                    'required' => array(
                        array($key . '_layout', '=', array('collapse', 'offcanvas')),
                    ),
                    array(
                        'id' => $key . '_layout_classes',
                        'type' => 'text',
                        'customizer' => false,
                        'title' => esc_html__('Classes', 'quadmenu'),
                        'subtitle' => esc_html__('Include your own classes in the menu.', 'quadmenu'),
                        'default' => $this->themes_defaults[$key . '_layout_classes'],
                        'validate' => 'no_special_chars',
                    ),
                    // Sticky
                    // ---------------------------------------------------------
                    array(
                        'id' => $key . '_layout_sticky_divider',
                        'type' => 'section',
                        'title' => esc_html__('Sticky', 'quadmenu'),
                        'indent' => true,
                        'required' => array(
                            array($key . '_layout', '=', array('collapse', 'offcanvas')),
                        ),
                    ),
                    array(
                        'id' => $key . '_layout_sticky',
                        'type' => 'switch',
                        'title' => esc_html__('Sticky', 'quadmenu'),
                        'subtitle' => esc_html__('Make the menu sticky on scroll.', 'quadmenu'),
                        'default' => (int) $this->themes_defaults[$key . '_layout_sticky'],
                    ),
                    array(
                        'id' => $key . '_layout_sticky_offset',
                        'type' => 'slider',
                        'title' => esc_html__('Offset', 'quadmenu'),
                        'subtitle' => esc_html__('Set the length of the scroll for each user to pass before the menu will stick to the top of the window.', 'quadmenu'),
                        'min' => '0',
                        'step' => '1',
                        'max' => '340',
                        'required' => array(
                            $key . '_layout_sticky',
                            '=',
                            1
                        ),
                        'default' => (int) $this->themes_defaults[$key . '_layout_sticky_offset'],
                    ),
                    // Navbar
                    // ---------------------------------------------------------
                    array(
                        'id' => $key . '_layout_menu',
                        'type' => 'section',
                        'title' => esc_html__('Menu', 'quadmenu'),
                        'indent' => true
                    ),
                    array(
                        'id' => $key . '_layout_divider',
                        'type' => 'button_set',
                        'title' => esc_html__('Divider', 'quadmenu'),
                        'subtitle' => esc_html__('Show a small divider bar between each menu item.', 'quadmenu'),
                        'options' => array(
                            'show' => esc_html__('Show', 'quadmenu'),
                            'hide' => esc_html__('Hide', 'quadmenu')
                        ),
                        'required' => array(
                            array($key . '_layout', '=', array('collapse', 'offcanvas')),
                        ),
                        'default' => $this->validate($this->themes_defaults[$key . '_layout_divider'], array('show', 'hide')),
                    ),
                    array(
                        'id' => $key . '_layout_caret',
                        'type' => 'button_set',
                        'title' => esc_html__('Caret', 'quadmenu'),
                        'subtitle' => esc_html__('Show carets on items with dropdown menus.', 'quadmenu'),
                        'options' => array(
                            'show' => esc_html__('Show', 'quadmenu'),
                            'hide' => esc_html__('Hide', 'quadmenu')
                        ),
                        'required' => array(
                            array($key . '_layout', '=', array('collapse', 'offcanvas')),
                        ),
                        'default' => $this->validate($this->themes_defaults[$key . '_layout_caret'], array('show', 'hide')),
                    ),
                    array(
                        'id' => $key . '_layout_hover_effect',
                        'type' => 'select',
                        'options' => array(
                            '' => esc_html__('None', 'quadmenu'),
                            'sl-top' => esc_html__('SlideBar Top (Horizontal)', 'quadmenu'),
                            'sl-middle' => esc_html__('SlideBar Middle (Horizontal)', 'quadmenu'),
                            'sl-bottom' => esc_html__('SlideBar Bottom (Horizontal)', 'quadmenu'),
                        ),
                        'title' => esc_html__('Hover Effect', 'quadmenu'),
                        'subtitle' => esc_html__('Select the animation for the dropdowns.', 'quadmenu'),
                        'required' => array(
                            array($key . '_layout', '=', array('collapse', 'offcanvas')),
                        ),
                        'default' => $this->themes_defaults[$key . '_layout_hover_effect'],
                    ),
                ),
            );

            $sections[] = array(
                'id' => 'quadmenu_navbar_' . $key,
                'title' => esc_html__('Menu', 'quadmenu'),
                'heading' => false, //'heading' => sprintf(esc_html__('Menu options for %1$s', 'quadmenu'), $theme),
                'subsection' => true,
                'customizer' => true,
                'permissions' => 'edit_theme_options',
                'fields' => array(
                    array(
                        'id' => $key . '_navbar_background',
                        'type' => 'button_set',
                        'title' => esc_html__('Background', 'quadmenu'),
                        'subtitle' => esc_html__('Select the navbar background.', 'quadmenu'),
                        'options' => array(
                            'color' => esc_html__('Color', 'quadmenu'),
                            'gradient' => esc_html__('Gradient', 'quadmenu'),
                        ),
                        'compiler' => true,
                        'default' => $this->validate($this->themes_defaults[$key . '_navbar_background'], array('color', 'gradient'))
                    ),
                    array(
                        'id' => $key . '_navbar_background_color',
                        'title' => esc_html__('Color', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a background color for the navbar.', 'quadmenu'),
                        'type' => 'color_rgba',
                        'compiler' => true,
                        'validate' => 'colorrgba',
                        'options' => array(
                            'allow_empty' => false,
                        ),
                        'default' => $this->themes_defaults[$key . '_navbar_background_color']
                    ),
                    array(
                        'id' => $key . '_navbar_background_to',
                        'title' => esc_html__('Gradient', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a background color for the navbar.', 'quadmenu'),
                        'type' => 'color_rgba',
                        'required' => array(
                            $key . '_navbar_background',
                            '=',
                            'gradient'
                        ),
                        'compiler' => true,
                        'validate' => 'colorrgba',
                        'options' => array(
                            'allow_empty' => false,
                        ),
                        'default' => $this->themes_defaults[$key . '_navbar_background_to']
                    ),
                    array(
                        'id' => $key . '_navbar_background_deg',
                        'title' => esc_html__('Degrees', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a degrees angle for background gradient.', 'quadmenu'),
                        'type' => 'spinner',
                        'min' => '0',
                        'step' => '1',
                        'max' => '360',
                        'compiler' => true,
                        'validate' => 'numeric',
                        'required' => array(
                            $key . '_navbar_background',
                            '=',
                            'gradient'
                        ),
                        'default' => (int) $this->themes_defaults[$key . '_navbar_background_deg']
                    ),
                    array(
                        'title' => esc_html__('Divider', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the navbar links divider.', 'quadmenu'),
                        'id' => $key . '_navbar_divider',
                        'type' => 'color_rgba',
                        'compiler' => true,
                        'validate' => 'colorrgba',
                        'options' => array(
                            'allow_empty' => false,
                        ),
                        'default' => $this->themes_defaults[$key . '_navbar_divider']
                    ),
                    array(
                        'title' => esc_html__('Text', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the navbar text.', 'quadmenu'),
                        'id' => $key . '_navbar_text',
                        'type' => 'color',
                        'transparent' => false,
                        'validate' => 'not_empty',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_navbar_text']
                    ),
                    // Navbar
                    // ---------------------------------------------------------
                    array(
                        'id' => $key . '_navbar',
                        'type' => 'section',
                        'title' => esc_html__('Menu', 'quadmenu'),
                        'indent' => true
                    ),
                    array(
                        'id' => $key . '_navbar_height',
                        'type' => 'slider',
                        'title' => esc_html__('Height', 'quadmenu'),
                        'subtitle' => esc_html__('Change the items height.', 'quadmenu'),
                        'min' => '30',
                        'step' => '2',
                        'max' => '160',
                        'compiler' => true,
                        'default' => (int) $this->themes_defaults[$key . '_navbar_height'],
                        'validate' => 'numeric',
                    ),
                    array(
                        'id' => $key . '_navbar_width',
                        'type' => 'slider',
                        'title' => esc_html__('Width', 'quadmenu'),
                        'subtitle' => esc_html__('Change the items width.', 'quadmenu'),
                        'min' => '60',
                        'step' => '1',
                        'max' => '500',
                        'compiler' => true,
                        'default' => (int) $this->themes_defaults[$key . '_navbar_width'],
                        'validate' => 'numeric',
                    ),
                    // Mobile
                    // ---------------------------------------------------------
                    array(
                        'id' => $key . '_mobile',
                        'type' => 'section',
                        'title' => esc_html__('Mobile', 'quadmenu'),
                        'indent' => true
                    ),
                    array(
                        'title' => esc_html__('Border', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the mobile bottom border.', 'quadmenu'),
                        'id' => $key . '_navbar_mobile_border',
                        'type' => 'color_rgba',
                        'transparent' => false,
                        'validate' => 'colorrgba',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_navbar_mobile_border']
                    ),
                    // Toggle
                    // ---------------------------------------------------------
                    array(
                        'id' => $key . '_toggle',
                        'type' => 'section',
                        'title' => esc_html__('Toggle', 'quadmenu'),
                        'indent' => true
                    ),
                    array(
                        'title' => esc_html__('Open', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the toggle icon.', 'quadmenu'),
                        'id' => $key . '_navbar_toggle_open',
                        'type' => 'color',
                        'transparent' => false,
                        'validate' => 'not_empty',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_navbar_toggle_open']
                    ),
                    array(
                        'title' => esc_html__('Close', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the toggle button background on hover.', 'quadmenu'),
                        'id' => $key . '_navbar_toggle_close',
                        'type' => 'color',
                        'transparent' => false,
                        'validate' => 'not_empty',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_navbar_toggle_close']
                    ),
                    // Logo
                    // ---------------------------------------------------------
                    array(
                        'id' => $key . '_logo',
                        'type' => 'section',
                        'title' => esc_html__('Logo', 'quadmenu'),
                        'indent' => true
                    ),
                    array(
                        'id' => $key . '_navbar_logo',
                        'type' => 'media',
                        'title' => esc_html__('Logo', 'quadmenu'),
                        'subtitle' => esc_html__('Upload the navbar logo.', 'quadmenu'),
                        'default' => $this->themes_defaults[$key . '_navbar_logo']
                    ),
                    array(
                        'id' => $key . '_navbar_logo_height',
                        'type' => 'slider',
                        'title' => esc_html__('Height', 'quadmenu'),
                        'subtitle' => esc_html__('Max logo height in px.', 'quadmenu'),
                        'min' => '20',
                        'step' => '1',
                        'max' => '160',
                        'compiler' => true,
                        'default' => (int) $this->themes_defaults[$key . '_navbar_logo_height'],
                        'validate' => 'numeric',
                    ),
                    array(
                        'id' => $key . '_navbar_logo_bg',
                        'title' => esc_html__('Background', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a background color for the navbar logo.', 'quadmenu'),
                        'type' => 'color_rgba',
                        'compiler' => true,
                        'validate' => 'colorrgba',
                        'options' => array(
                            'allow_empty' => false,
                        ),
                        'default' => $this->themes_defaults[$key . '_navbar_logo_bg']
                    ),
                    // Layout
                    // ---------------------------------------------------------
                    array(
                        'id' => $key . '_navbar_layout',
                        'type' => 'section',
                        'title' => esc_html__('Layout', 'quadmenu'),
                        'indent' => true
                    ),
                    array(
                        'title' => esc_html__('Margin', 'quadmenu'),
                        'subtitle' => esc_html__('Set the margin for the navbar links.', 'quadmenu'),
                        'id' => $key . '_navbar_link_margin',
                        'type' => 'border',
                        'all' => false,
                        'style' => false,
                        'color' => false,
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_navbar_link_margin']
                    ),
                    array(
                        'title' => esc_html__('Radius', 'quadmenu'),
                        'subtitle' => esc_html__('Set the radius for the navbar links.', 'quadmenu'),
                        'id' => $key . '_navbar_link_radius',
                        'type' => 'border',
                        'all' => false,
                        'style' => false,
                        'color' => false,
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_navbar_link_radius']
                    ),
                    // Link
                    // ---------------------------------------------------------
                    array(
                        'id' => $key . '_link',
                        'type' => 'section',
                        'title' => esc_html__('Links', 'quadmenu'),
                        'indent' => true
                    ),
                    array(
                        'title' => esc_html__('Link', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a text transform for the link.', 'quadmenu'),
                        'id' => $key . '_navbar_link_transform',
                        'type' => 'select',
                        'options' => array(
                            'none' => esc_html__('None', 'quadmenu'),
                            'capitalize' => esc_html__('Capitalize', 'quadmenu'),
                            'uppercase' => esc_html__('Uppercase', 'quadmenu'),
                            'lowercase' => esc_html__('Lowercase', 'quadmenu'),
                        ),
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_navbar_link_transform']
                    ),
                    array(
                        'title' => esc_html__('Color', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the navbar links.', 'quadmenu'),
                        'id' => $key . '_navbar_link',
                        'type' => 'color',
                        'transparent' => false,
                        //'validate' => 'color',                    
                        'validate' => 'not_empty',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_navbar_link']
                    ),
                    array(
                        'title' => esc_html__('Hover', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the navbar links on mousehover.', 'quadmenu'),
                        'id' => $key . '_navbar_link_hover',
                        'type' => 'color',
                        'transparent' => false,
                        //'validate' => 'color',                    
                        'validate' => 'not_empty',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_navbar_link_hover']
                    ),
                    array(
                        'title' => esc_html__('Background', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the navbar background.', 'quadmenu'),
                        'id' => $key . '_navbar_link_bg',
                        'type' => 'color_rgba',
                        'compiler' => true,
                        'validate' => 'colorrgba',
                        'options' => array(
                            'allow_empty' => false,
                        ),
                        'default' => $this->themes_defaults[$key . '_navbar_link_bg']
                    ),
                    array(
                        'title' => esc_html__('Hover', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the navbar background on mousehover.', 'quadmenu'),
                        'id' => $key . '_navbar_link_bg_hover',
                        'type' => 'color_rgba',
                        'compiler' => true,
                        'validate' => 'colorrgba',
                        'options' => array(
                            'allow_empty' => false,
                        ),
                        'default' => $this->themes_defaults[$key . '_navbar_link_bg_hover']
                    ),
                    array(
                        'title' => esc_html__('Effect', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the navbar link effect on mousehover.', 'quadmenu'),
                        'id' => $key . '_navbar_link_hover_effect',
                        'type' => 'color_rgba',
                        'compiler' => true,
                        'validate' => 'colorrgba',
                        'options' => array(
                            'allow_empty' => false,
                        ),
                        'default' => $this->themes_defaults[$key . '_navbar_link_hover_effect']
                    ),
                    // Button
                    // ---------------------------------------------------------
                    array(
                        'id' => $key . '_button',
                        'type' => 'section',
                        'title' => esc_html__('Button', 'quadmenu'),
                        'indent' => true
                    ),
                    array(
                        'title' => esc_html__('Color', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the navbar button.', 'quadmenu'),
                        'id' => $key . '_navbar_button',
                        'type' => 'color',
                        'transparent' => false,
                        //'validate' => 'color',                    
                        'validate' => 'not_empty',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_navbar_button']
                    ),
                    array(
                        'title' => esc_html__('Background', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the navbar button.', 'quadmenu'),
                        'id' => $key . '_navbar_button_bg',
                        'type' => 'color',
                        'transparent' => false,
                        //'validate' => 'color',                    
                        'validate' => 'not_empty',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_navbar_button_bg']
                    ),
                    array(
                        'title' => esc_html__('Hover', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the navbar button on hover.', 'quadmenu'),
                        'id' => $key . '_navbar_button_hover',
                        'type' => 'color',
                        'transparent' => false,
                        //'validate' => 'color',                    
                        'validate' => 'not_empty',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_navbar_button_hover']
                    ),
                    array(
                        'title' => esc_html__('Background Hover', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the navbar button on hover.', 'quadmenu'),
                        'id' => $key . '_navbar_button_bg_hover',
                        'type' => 'color',
                        'transparent' => false,
                        //'validate' => 'color',                    
                        'validate' => 'not_empty',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_navbar_button_bg_hover']
                    ),
                    // Icon
                    // ---------------------------------------------------------
                    array(
                        'id' => $key . '_icon',
                        'type' => 'section',
                        'title' => esc_html__('Icon', 'quadmenu'),
                        'indent' => true
                    ),
                    array(
                        'title' => esc_html__('Icon', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the navbar links icon.', 'quadmenu'),
                        'id' => $key . '_navbar_link_icon',
                        'type' => 'color',
                        'transparent' => false,
                        //'validate' => 'color',                    
                        'validate' => 'not_empty',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_navbar_link_icon']
                    ),
                    array(
                        'title' => esc_html__('Icon Hover', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the navbar links icon on hover.', 'quadmenu'),
                        'id' => $key . '_navbar_link_icon_hover',
                        'type' => 'color',
                        'transparent' => false,
                        //'validate' => 'color',                    
                        'validate' => 'not_empty',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_navbar_link_icon_hover']
                    ),
                    // Subtitle
                    // ---------------------------------------------------------
                    array(
                        'id' => $key . '_subtitle',
                        'type' => 'section',
                        'title' => esc_html__('Subtitle', 'quadmenu'),
                        'indent' => true
                    ),
                    array(
                        'title' => esc_html__('Color', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the navbar links subtitle.', 'quadmenu'),
                        'id' => $key . '_navbar_link_subtitle',
                        'type' => 'color',
                        'transparent' => false,
                        //'validate' => 'color',                    
                        'validate' => 'not_empty',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_navbar_link_subtitle']
                    ),
                    array(
                        'title' => esc_html__('Hover', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the navbar links subtitle on hover.', 'quadmenu'),
                        'id' => $key . '_navbar_link_subtitle_hover',
                        'type' => 'color',
                        'transparent' => false,
                        //'validate' => 'color',                    
                        'validate' => 'not_empty',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_navbar_link_subtitle_hover']
                    ),
                    // Badge
                    // ---------------------------------------------------------
                    array(
                        'id' => $key . '_badge',
                        'type' => 'section',
                        'title' => esc_html__('Badge', 'quadmenu'),
                        'indent' => true
                    ),
                    array(
                        'title' => esc_html__('Background', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a background color for the badge.', 'quadmenu'),
                        'id' => $key . '_navbar_badge',
                        'type' => 'color',
                        'transparent' => false,
                        //'validate' => 'color',                    
                        'validate' => 'not_empty',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_navbar_badge']
                    ),
                    array(
                        'title' => esc_html__('Color', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the badge.', 'quadmenu'),
                        'id' => $key . '_navbar_badge_color',
                        'type' => 'color',
                        'transparent' => false,
                        //'validate' => 'color',                    
                        'validate' => 'not_empty',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_navbar_badge_color']
                    ),
                    // Sticky
                    // ---------------------------------------------------------
                    array(
                        'id' => $key . '_sticky',
                        'type' => 'section',
                        'title' => esc_html__('Sticky', 'quadmenu'),
                        'indent' => true
                    ),
                    array(
                        'id' => $key . '_sticky_background',
                        'title' => esc_html__('Background', 'quadmenu'),
                        'subtitle' => esc_html__('Change the navbar sticky background.', 'quadmenu'),
                        'type' => 'color_rgba',
                        'compiler' => true,
                        'validate' => 'colorrgba',
                        /* 'required' => array(
                          $key . '_navbar_background',
                          '=',
                          'color'
                          ), */
                        'options' => array(
                            'allow_empty' => false,
                        ),
                        'default' => $this->themes_defaults[$key . '_sticky_background']
                    ),
                    array(
                        'id' => $key . '_sticky_height',
                        'type' => 'slider',
                        'title' => esc_html__('Height', 'quadmenu'),
                        'subtitle' => esc_html__('Change the navbar sticky height.', 'quadmenu'),
                        'min' => '30',
                        'step' => '2',
                        'max' => '160',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_sticky_height'],
                    ),
                    array(
                        'id' => $key . '_sticky_logo_height',
                        'type' => 'slider',
                        'title' => esc_html__('Logo', 'quadmenu'),
                        'subtitle' => esc_html__('Max logo height in px.', 'quadmenu'),
                        'min' => '20',
                        'step' => '1',
                        'max' => '160',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_sticky_logo_height'],
                    ),
                    // Scrollbar
                    // ---------------------------------------------------------                    
                    array(
                        'id' => $key . '_scrollbar',
                        'type' => 'section',
                        'title' => esc_html__('ScrollBar', 'quadmenu'),
                        'indent' => true,
                        'required' => array(
                            'styles_pscrollbar',
                            '=',
                            true
                        ),
                    ),
                    array(
                        'title' => esc_html__('Bar', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the scrollbar.', 'quadmenu'),
                        'id' => $key . '_navbar_scrollbar',
                        'type' => 'color',
                        'transparent' => false,
                        //'validate' => 'color',                    
                        'validate' => 'not_empty',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_navbar_scrollbar']
                    ),
                    array(
                        'title' => esc_html__('Rail', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the scrollbar rail.', 'quadmenu'),
                        'id' => $key . '_navbar_scrollbar_rail',
                        'type' => 'color',
                        'transparent' => false,
                        //'validate' => 'color',                    
                        'validate' => 'not_empty',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_navbar_scrollbar_rail']
                    ),
                )
            );

            $sections[] = array(
                'id' => 'quadmenu_dropdown_' . $key,
                'title' => esc_html__('Dropdown', 'quadmenu'),
                'heading' => false,
                'subsection' => true,
                'customizer' => true,
                'permissions' => 'edit_theme_options',
                'fields' => array(
                    array(
                        'id' => $key . '_dropdown_shadow',
                        'type' => 'button_set',
                        'title' => esc_html__('Shadow', 'quadmenu'),
                        'subtitle' => esc_html__('Display shadow on dropdown menus.', 'quadmenu'),
                        'options' => array(
                            'show' => esc_html__('Show', 'quadmenu'),
                            'hide' => esc_html__('Hide', 'quadmenu')
                        ),
                        'default' => $this->validate($this->themes_defaults[$key . '_dropdown_shadow'], array('show', 'hide')),
                    ),
                    array(
                        'id' => $key . '_dropdown_margin',
                        'type' => 'slider',
                        'title' => esc_html__('Margin', 'quadmenu'),
                        'subtitle' => esc_html__('Dropdown margin top.', 'quadmenu'),
                        'min' => '0',
                        'step' => '1',
                        'max' => '45',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_dropdown_margin'],
                    ),
                    array(
                        'id' => $key . '_dropdown_radius',
                        'type' => 'slider',
                        'title' => esc_html__('Radius', 'quadmenu'),
                        'subtitle' => esc_html__('Dropdown border radius.', 'quadmenu'),
                        'min' => '0',
                        'step' => '1',
                        'max' => '30',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_dropdown_radius'],
                    ),
                    array(
                        'title' => esc_html__('Border', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a border color for the dropdown border.', 'quadmenu'),
                        'id' => $key . '_dropdown_border',
                        'compiler' => true,
                        'type' => 'border',
                        'style' => false,
                        'default' => $this->themes_defaults[$key . '_dropdown_border']
                    ),
                    array(
                        'title' => esc_html__('Background', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a background color for the dropdown menu.', 'quadmenu'),
                        'id' => $key . '_dropdown_background',
                        'type' => 'color_rgba',
                        'compiler' => true,
                        'validate' => 'colorrgba',
                        'options' => array(
                            'allow_empty' => false,
                        ),
                        'default' => $this->themes_defaults[$key . '_dropdown_background']
                    ),
                    // Scrollbar
                    // ---------------------------------------------------------                    
                    array(
                        'id' => $key . '_scrollbar',
                        'type' => 'section',
                        'title' => esc_html__('ScrollBar', 'quadmenu'),
                        'indent' => true,
                        'required' => array(
                            'styles_pscrollbar',
                            '=',
                            true
                        ),
                    ),
                    array(
                        'title' => esc_html__('Bar', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the scrollbar.', 'quadmenu'),
                        'id' => $key . '_dropdown_scrollbar',
                        'type' => 'color',
                        'transparent' => false,
                        //'validate' => 'color',                    
                        'validate' => 'not_empty',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_dropdown_scrollbar']
                    ),
                    array(
                        'title' => esc_html__('Rail', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the scrollbar rail.', 'quadmenu'),
                        'id' => $key . '_dropdown_scrollbar_rail',
                        'type' => 'color',
                        'transparent' => false,
                        //'validate' => 'color',                    
                        'validate' => 'not_empty',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_dropdown_scrollbar_rail']
                    ),
                    // Title
                    // ---------------------------------------------------------
                    array(
                        'id' => $key . '_title',
                        'type' => 'section',
                        'title' => esc_html__('Title', 'quadmenu'),
                        'indent' => true
                    ),
                    array(
                        'title' => esc_html__('Title', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the dropdown menu title.', 'quadmenu'),
                        'id' => $key . '_dropdown_title',
                        'type' => 'color',
                        'transparent' => false,
                        //'validate' => 'color',                    
                        'validate' => 'not_empty',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_dropdown_title']
                    ),
                    array(
                        'title' => esc_html__('Border', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the dropdown menu title border.', 'quadmenu'),
                        'id' => $key . '_dropdown_title_border',
                        'type' => 'border',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_dropdown_title_border']
                    ),
                    // Link
                    // ---------------------------------------------------------
                    array(
                        'id' => $key . '_link',
                        'type' => 'section',
                        'title' => esc_html__('Link', 'quadmenu'),
                        'indent' => true
                    ),
                    array(
                        'title' => esc_html__('Color', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the dropdown menu links.', 'quadmenu'),
                        'id' => $key . '_dropdown_link',
                        'type' => 'color',
                        'transparent' => false,
                        //'validate' => 'color',                    
                        'validate' => 'not_empty',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_dropdown_link']
                    ),
                    array(
                        'title' => esc_html__('Hover', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the dropdown menu links on mousehover.', 'quadmenu'),
                        'id' => $key . '_dropdown_link_hover',
                        'type' => 'color',
                        'transparent' => false,
                        //'validate' => 'color',                    
                        'validate' => 'not_empty',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_dropdown_link_hover']
                    ),
                    array(
                        'title' => esc_html__('Background Hover', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a background color for the links onmouseover.', 'quadmenu'),
                        'id' => $key . '_dropdown_link_bg_hover',
                        'type' => 'color_rgba',
                        'compiler' => true,
                        'validate' => 'colorrgba',
                        'options' => array(
                            'allow_empty' => false,
                        ),
                        'default' => $this->themes_defaults[$key . '_dropdown_link_bg_hover']
                    ),
                    array(
                        'title' => esc_html__('Border', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a border color for the dropdown menu links border.', 'quadmenu'),
                        'id' => $key . '_dropdown_link_border',
                        'compiler' => true,
                        'type' => 'border',
                        'default' => $this->themes_defaults[$key . '_dropdown_link_border']
                    ),
                    array(
                        'title' => esc_html__('Transform', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a text transform for the link.', 'quadmenu'),
                        'id' => $key . '_dropdown_link_transform',
                        'type' => 'select',
                        'options' => array(
                            'none' => esc_html__('None', 'quadmenu'),
                            'capitalize' => esc_html__('Capitalize', 'quadmenu'),
                            'uppercase' => esc_html__('Uppercase', 'quadmenu'),
                            'lowercase' => esc_html__('Lowercase', 'quadmenu'),
                        ),
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_dropdown_link_transform']
                    ),
                    // Button
                    // ---------------------------------------------------------
                    array(
                        'id' => $key . '_button',
                        'type' => 'section',
                        'title' => esc_html__('Button', 'quadmenu'),
                        'indent' => true
                    ),
                    array(
                        'title' => esc_html__('Color', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the navbar button.', 'quadmenu'),
                        'id' => $key . '_dropdown_button',
                        'type' => 'color',
                        'transparent' => false,
                        //'validate' => 'color',                    
                        'validate' => 'not_empty',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_dropdown_button']
                    ),
                    array(
                        'title' => esc_html__('Hover', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the navbar button on hover.', 'quadmenu'),
                        'id' => $key . '_dropdown_button_hover',
                        'type' => 'color',
                        'transparent' => false,
                        //'validate' => 'color',                    
                        'validate' => 'not_empty',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_dropdown_button_hover']
                    ),
                    array(
                        'title' => esc_html__('Background', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the navbar button.', 'quadmenu'),
                        'id' => $key . '_dropdown_button_bg',
                        'type' => 'color',
                        'transparent' => false,
                        //'validate' => 'color',                    
                        'validate' => 'not_empty',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_dropdown_button_bg']
                    ),
                    array(
                        'title' => esc_html__('Background Hover', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the navbar button on hover.', 'quadmenu'),
                        'id' => $key . '_dropdown_button_bg_hover',
                        'type' => 'color',
                        'transparent' => false,
                        //'validate' => 'color',                    
                        'validate' => 'not_empty',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_dropdown_button_bg_hover']
                    ),
                    // Icon
                    // ---------------------------------------------------------
                    array(
                        'id' => $key . '_icon',
                        'type' => 'section',
                        'title' => esc_html__('Icon', 'quadmenu'),
                        'indent' => true
                    ),
                    array(
                        'title' => esc_html__('Icon', 'quadmenu'),
                        'icon' => esc_html__('Pick a color for the dropdown links icon.', 'quadmenu'),
                        'id' => $key . '_dropdown_link_icon',
                        'type' => 'color',
                        'transparent' => false,
                        //'validate' => 'color',                    
                        'validate' => 'not_empty',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_dropdown_link_icon']
                    ),
                    array(
                        'title' => esc_html__('Hover', 'quadmenu'),
                        'icon' => esc_html__('Pick a color for the dropdown links icon on hover.', 'quadmenu'),
                        'id' => $key . '_dropdown_link_icon_hover',
                        'type' => 'color',
                        'transparent' => false,
                        //'validate' => 'color',                    
                        'validate' => 'not_empty',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_dropdown_link_icon_hover']
                    ),
                    // Subtitle
                    // ---------------------------------------------------------
                    array(
                        'id' => $key . '_subtitle',
                        'type' => 'section',
                        'title' => esc_html__('Subtitle', 'quadmenu'),
                        'indent' => true
                    ),
                    array(
                        'title' => esc_html__('Subtitle', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the dropdown links subtitle.', 'quadmenu'),
                        'id' => $key . '_dropdown_link_subtitle',
                        'type' => 'color',
                        'transparent' => false,
                        //'validate' => 'color',                    
                        'validate' => 'not_empty',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_dropdown_link_subtitle']
                    ),
                    array(
                        'title' => esc_html__('Hover', 'quadmenu'),
                        'subtitle' => esc_html__('Pick a color for the dropdown links subtitle on hover.', 'quadmenu'),
                        'id' => $key . '_dropdown_link_subtitle_hover',
                        'type' => 'color',
                        'transparent' => false,
                        //'validate' => 'color',                    
                        'validate' => 'not_empty',
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_dropdown_link_subtitle_hover']
                    ),
                ),
            );

            $sections[] = array(
                'id' => 'quadmenu_fonts_' . $key,
                'title' => esc_html__('Fonts', 'quadmenu'),
                //'heading' => false,
                //'icon' => 'quadmenu-params',
                'heading' => false,
                'subsection' => true,
                'customizer' => true,
                'permissions' => 'edit_theme_options',
                'fields' => array(
                    array(
                        'id' => $key . '_font',
                        'type' => 'typography',
                        'title' => esc_html__('General', 'quadmenu'),
                        'subtitle' => esc_html__('Specify the font properties.', 'quadmenu'),
                        'font-weight' => true,
                        'font-size' => true,
                        'line-height' => false,
                        'google' => true,
                        'text-align' => false,
                        'color' => false,
                        'subsets' => true,
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_font']
                    ),
                    array(
                        'id' => $key . '_navbar_font',
                        'type' => 'typography',
                        'title' => esc_html__('Menu', 'quadmenu'),
                        'subtitle' => esc_html__('Specify the font properties.', 'quadmenu'),
                        'font-weight' => true,
                        'font-size' => true,
                        'line-height' => false,
                        'google' => true,
                        'text-align' => false,
                        'color' => false,
                        'subsets' => true,
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_navbar_font']
                    ),
                    array(
                        'id' => $key . '_dropdown_font',
                        'type' => 'typography',
                        'title' => esc_html__('Submenu', 'quadmenu'),
                        'subtitle' => esc_html__('Specify the font properties.', 'quadmenu'),
                        'font-weight' => true,
                        'font-size' => true,
                        'line-height' => false,
                        'google' => true,
                        'text-align' => false,
                        'color' => false,
                        'subsets' => true,
                        'compiler' => true,
                        'default' => $this->themes_defaults[$key . '_dropdown_font']
                    ),
                )
            );
        }

        return $sections;
    }

    function validate($option, $defaults) {

        if (!$option || !in_array(sanitize_key($option), $defaults))
            return reset($defaults);

        return $option;
    }

}

new QuadMenu_Options();
