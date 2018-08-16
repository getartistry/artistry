<?php

if (!defined('ABSPATH')) {
    die('-1');
}

class QuadMenu_Redux {

    public $theme;

    public function __construct() {

        if (!class_exists('Redux')) {
            require_once QUADMENU_PATH . 'lib/ReduxCore/framework.php';
        }

        add_action('admin_menu', array($this, 'remove_redux_menu'), 12);

        add_action('redux/options/' . QUADMENU_OPTIONS . '/settings/change', array($this, 'notification_bar'), 30, 2);

        add_filter('redux/options/' . QUADMENU_OPTIONS . '/ajax_save/response', array($this, 'reload'));

        add_action('redux/extensions/' . QUADMENU_OPTIONS . '/before', array($this, 'ad_remove'), 0);

        add_action('redux/extensions/' . QUADMENU_OPTIONS . '/before', array($this, 'customizer'), 0);

        add_filter('redux/extension/' . QUADMENU_OPTIONS . '/customizer', '__return_null');

        add_filter('redux/' . QUADMENU_OPTIONS . '/field/class/icons', array($this, 'field_icons'));

        add_filter('redux/' . QUADMENU_OPTIONS . '/field/class/rgba', array($this, 'field_rgba'));
        
        add_filter('redux/' . QUADMENU_OPTIONS . '/field/class/animation', array($this, 'field_animation'));

        add_filter('redux/' . QUADMENU_OPTIONS . '/panel/template/header.tpl.php', array($this, 'header'));

        add_filter('redux/' . QUADMENU_OPTIONS . '/panel/template/header_stickybar.tpl.php', array($this, 'header_stickybar'));

        add_filter('redux/' . QUADMENU_OPTIONS . '/panel/template/footer.tpl.php', array($this, 'footer'));

        add_filter('ReduxFramework_icons_classes', array($this, 'selected_icons_iconmap'));

        $this->redux();
    }

    function selected_icons_iconmap() {
        return _QuadMenu()->selected_icons()->iconmap;
    }

    function header($path = false) {
        return QUADMENU_PATH . 'lib/redux/template/header.tpl.php';
    }

    function header_stickybar($path = false) {
        return QUADMENU_PATH . 'lib/redux/template/header_stickybar.tpl.php';
    }

    function footer($path = false) {
        return QUADMENU_PATH . 'lib/redux/template/footer.tpl.php';
    }

    function remove_redux_menu() {
        remove_submenu_page('tools.php', 'redux-about');
    }

    static function notification_bar() {

        if ($notices = get_option('quadmenu_redux_notices', false)) {
            foreach ($notices as $notice) {

                if (empty($notice['class']) || empty($notice['notice']))
                    continue;

                echo '<div class="saved_notice admin-notice notice-' . $notice['class'] . '">' . $notice['notice'] . '</div>';
            }
            delete_option('quadmenu_redux_notices');
        }
    }

    static function add_notification($class = 'updated', $notice = false) {

        if (!$notice)
            return;

        $notices = get_option('quadmenu_redux_notices', array());

        $notices[] = array(
            'class' => $class,
            'notice' => $notice
        );

        update_option('quadmenu_redux_notices', $notices);
    }

    function ad_remove($ReduxFramework) {

        if (!class_exists('ReduxFramework_extension_ad_remove')) {

            require_once(QUADMENU_PATH . 'lib/redux/ad_remove/extension_ad_remove.php');

            new ReduxFramework_extension_ad_remove($ReduxFramework);
        }
    }

    function customizer($ReduxFramework) {

        if (!is_admin() && !is_customize_preview())
            return;

        if (is_file(QUADMENU_PATH . 'premium/customizer/customizer.php')) {

            require_once QUADMENU_PATH . 'premium/customizer/customizer.php';

            new QuadMenu_Customizer($ReduxFramework);
        }
    }

    function field_rgba($field) {
        return QUADMENU_PATH . 'lib/redux/rgba/field_rgba.php';
    }

    function field_icons($field) {
        return QUADMENU_PATH . 'lib/redux/icons/field_icons.php';
    }

    function field_animation($field) {
        return QUADMENU_PATH . 'lib/redux/animation/field_animation.php';
    }

    function reload($return_array) {

        if (get_transient('_quadmenu_saved_reload')) {

            $return_array['action'] = 'reload';
        }

        return $return_array;
    }

    static function do_reload($run = true) {

        if ($run) {
            set_transient('_quadmenu_saved_reload', true, 30);
        } else {
            delete_transient('_quadmenu_saved_reload');
        }
    }

    public function redux() {

        $args = array(
            'class' => 'quadmenu-admin-wrap',
            'opt_name' => QUADMENU_OPTIONS,
            'disable_tracking' => true,
            'display_name' => QUADMENU_NAME,
            'display_version' => QUADMENU_VERSION,
            'menu_type' => 'submenu',
            'allow_sub_menu' => true,
            'menu_title' => QUADMENU_NAME,
            'page' => QUADMENU_NAME,
            'google_api_key' => 'AIzaSyBNsacnx37lZpIIyDyNAjGC1qdE7Z0CrEQ',
            'async_typography' => false,
            'show_options_object' => false,
            'global_variable' => 'quadmenu',
            'customizer' => true,
            'page_priority' => null,
            'page_parent' => 'themes.php',
            'page_permissions' => 'edit_theme_options',
            'page_slug' => QUADMENU_PANEL,
            'save_defaults' => true,
            'default_show' => false,
            'default_mark' => '',
            'transient_time' => 60 * MINUTE_IN_SECONDS,
            'output' => true,
            'output_tag' => false,
            'database' => '',
            'use_cdn' => true,
            'hints' => array(
                'icon' => 'el el-question-sign',
                'icon_position' => 'right',
                'icon_color' => 'lightgray',
                'icon_size' => 'normal',
                'tip_style' => array(
                    'color' => 'dark',
                    'shadow' => true,
                    'rounded' => false,
                    'style' => '', //youtube',
                ),
                'tip_position' => array(
                    'my' => 'top left',
                    'at' => 'bottom right',
                ),
                'tip_effect' => array(
                    'show' => array(
                        'effect' => 'slide',
                        'duration' => '500',
                        'event' => 'click',
                    ),
                    'hide' => array(
                        'effect' => 'slide',
                        'duration' => '500',
                        'event' => 'click mouseleave',
                    ),
                ),
            ),
            'show_import_export' => true, // REMOVE                
            'dev_mode' => QUADMENU_DEV, // Show the time the page took to load, etc                 
            'dev_mode_icon' => 'quadmenu-database',
            'dev_mode_icon_class' => 'quadmenu-database',
            'system_info' => QUADMENU_DEV, // REMOVE
            'ajax_save' => true,
            'footer_credit' => ' '
        );

        // Panel Intro text -> before the form
        if (!isset($args['global_variable']) || $args['global_variable'] !== false) {
            if (!empty($args['global_variable'])) {
                $v = $args['global_variable'];
            } else {
                $v = str_replace("-", "_", $args['opt_name']);
            }
        }

        new ReduxFramework(array(), apply_filters('quadmenu_redux_args', $args));
    }

}

new QuadMenu_Redux();
