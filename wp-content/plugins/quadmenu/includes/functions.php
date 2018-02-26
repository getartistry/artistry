<?php

if (!defined('ABSPATH')) {
    die('-1');
}

if (!function_exists('is_quadmenu')) {

    function is_quadmenu($menu_id = false) {
        return QuadMenu::instance()->is_quadmenu($menu_id);
    }

}

if (!function_exists('quadmenu_get_menu_theme')) {

    function quadmenu_get_menu_theme($location = null, $menu_id = null) {

        global $quadmenu_themes, $quadmenu_locations;

        $theme = '';

        if (isset($quadmenu_locations[$location])) {
            $theme = $quadmenu_locations[$location];
        }

        if ($theme && isset($quadmenu_themes[$theme])) {
            return $theme;
        }

        /*if ($menu_id) {

            $theme = get_term_meta($menu_id, QUADMENU_THEME_DB_KEY, true);

            if ($theme && isset($quadmenu_themes[$theme])) {
                return $theme;
            }
        }*/

        if (is_array($quadmenu_themes)) {
            return current(array_keys($quadmenu_themes));
        }

        return 'default_theme';
    }

}

// Developers
// -----------------------------------------------------------------------------

if (!function_exists('_quadmenu_compiler_integration')) {

    function _quadmenu_compiler_integration() {
        return QuadMenu_Compiler::instance()->enqueue();
    }

}

if (!function_exists('_quadmenu_do_compiler')) {

    function _quadmenu_do_compiler() {
        return QuadMenu_Compiler::instance()->do_compiler();
    }

}

if (!function_exists('_quadmenu_compiler_variables')) {

    function _quadmenu_compiler_variables() {
        return QuadMenu_Compiler::instance()->redux_compiler();
    }

}

if (!function_exists('quadmenu_do_compiler')) {

    function quadmenu_do_compiler() {
        add_action('init', '_quadmenu_do_compiler', 26);
    }

}

if (!function_exists('quadmenu_compiler_integration')) {

    function quadmenu_compiler_integration() {
        add_action('init', '_quadmenu_compiler_integration', 26);
    }

}

