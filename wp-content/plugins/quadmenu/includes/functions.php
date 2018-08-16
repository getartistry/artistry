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

        global $quadmenu_themes, $quadmenu_active_locations;

        $theme = '';

        if (isset($quadmenu_active_locations[$location])) {
            $theme = $quadmenu_active_locations[$location];
        }

        if ($theme && isset($quadmenu_themes[$theme])) {
            return $theme;
        }
        
        if (is_array($quadmenu_themes)) {
            return current(array_keys($quadmenu_themes));
        }

        return 'default_theme';
    }

}

// Developers
// -----------------------------------------------------------------------------

if (!function_exists('quadmenu_compiler_enqueue')) {

    function quadmenu_compiler_enqueue() {

        $screen = get_current_screen();

        if (isset($screen->base) && !strpos($screen->base, QUADMENU_PANEL) === false)
            return;

        QuadMenu_Compiler::instance()->enqueue();
    }

}

if (!function_exists('quadmenu_compiler_integration')) {

    function quadmenu_compiler_integration() {
        add_action('admin_init', 'quadmenu_compiler_enqueue', 26);
    }

}

if (!function_exists('quadmenu_do_compiler')) {

    function quadmenu_do_compiler() {
        QuadMenu_Compiler::instance()->do_compiler(true);
    }

}

if (!function_exists('quadmenu_compiler_variables')) {

    function quadmenu_compiler_variables() {
        return QuadMenu_Compiler::instance()->redux_compiler();
    }

}

