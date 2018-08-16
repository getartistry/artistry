<?php

if (!defined('ABSPATH')) {
    die('-1');
}

class QuadMenu_LMM extends QuadMenu_Compatibility {

    public function __construct() {

        add_action('quadmenu_compatibility_import_lmm', array($this, 'import'));

        add_filter('quadmenu_setup_nav_menu_item', array($this, 'lmm'));
    }

    function import() {
        $this->add_themes();
        $this->add_themes_settings();
        $this->add_themes_locations();

        QuadMenu_Redux::add_notification('blue', sprintf(esc_html__('You\'ve imported %s Settings. %s. %s.', 'quadmenu'), 'LMM', esc_html__('We have to create the stylesheets', 'quadmenu'), esc_html__('Please wait', 'quadmenu')));

        Quadmenu_Compiler::do_compiler(true);

        QuadMenu::send_json_success(QuadMenu::taburl('0'));
    }

    function add_themes() {

        if (is_array($saved_locations = get_option('lmm_locations')) && count($saved_locations)) {

            $saved_themes = get_option(QUADMENU_THEMES, array());

            foreach ($saved_locations as $key => $location) {

                $saved_themes[$key] = $location['name'];
            }

            update_option(QUADMENU_THEMES, $saved_themes);
        }
    }

    function add_themes_locations() {

        global $_wp_registered_nav_menus;

        if (!is_array($_wp_registered_nav_menus))
            return;

        if (!count($_wp_registered_nav_menus))
            return;

        if (is_array($saved_themes = get_option(QUADMENU_THEMES)) && count($saved_themes)) {

            $quadmenu = get_option(QUADMENU_OPTIONS, array());

            foreach ($_wp_registered_nav_menus as $key => $name) {

                if (!isset($saved_themes[$key]))
                    continue;

                $quadmenu[$key . '_integration'] = 1;

                $quadmenu[$key . '_theme'] = $key;
            }

            update_option(QUADMENU_OPTIONS, $quadmenu);
        }
    }

    function add_themes_settings() {

        if (is_array($quadmenu = get_option(QUADMENU_OPTIONS)) && count($quadmenu)) {

            $lmm = get_option('lmm', array());
            $lmm = $this->add_settings_compatibility($lmm);
            $lmm = $this->add_settings_fonts($lmm);

            $args = wp_parse_args($lmm, $quadmenu);

            update_option(QUADMENU_OPTIONS, $args);
        }
    }

    function add_settings_compatibility($lmm) {

        foreach ($lmm as $key => $option) {

            if (strpos($key, 'navbar_mode_') !== false) {

                unset($lmm[$key]);

                $new_key = str_replace('navbar_mode_', 'layout_', $key);

                $lmm[$new_key] = $option;
            }
        }

        foreach ($lmm as $key => $option) {

            if (strpos($key, 'lmm_') !== false) {

                unset($lmm[$key]);

                $new_key = str_replace('lmm_', '', $key);

                $lmm[$new_key] = $option;
            }
        }

        foreach ($lmm as $key => $option) {

            if (strpos($key, 'scheme_') !== false) {
                unset($lmm[$key]);

                $new_key = str_replace('scheme_', '', $key);

                $lmm[$new_key] = $option;
            }
        }

        foreach ($lmm as $key => $option) {
            $lmm[$key] = str_replace('px', '', $option);
        }

        return $lmm;
    }

    function add_settings_fonts($lmm) {

        if (is_interable($saved_themes = get_option(QUADMENU_THEMES)) && count($saved_themes)) {

            foreach ($saved_themes as $key => $name) {
                $lmm[$key . '_font'] = isset($lmm['general_font']) ? $lmm['general_font'] : '';
                $lmm[$key . '_navbar_font'] = isset($lmm['navbar_font']) ? $lmm['navbar_font'] : '';
                $lmm[$key . '_dropdown_font'] = isset($lmm['dropdown_font']) ? $lmm['dropdown_font'] : '';
            }
        }

        return $lmm;
    }

    function lmm($item) {

        if (!defined('LMM')) {
            define('LMM', 'lmm');
        }

        if ($item->type == 'custom' && empty($item->quadmenu)) {
            if (strpos($item->url, 'lmm') !== false) {
                $item->quadmenu = str_replace('lmm-', '', str_replace('#', '', $item->url));
                update_post_meta($item->ID, LMM . '_menu_item_lmm', $item->quadmenu);
            } elseif ($lmm = get_post_meta($item->ID, LMM . '_menu_item_lmm', true)) {
                $item->quadmenu = str_replace('lmm-', '', str_replace('#', '', $lmm));
            }
        }

        $item_parent = get_post_meta($item->menu_item_parent, LMM . '_menu_item_lmm', true);

        if (!empty($item_parent)) {
            $item->quadmenu_menu_item_parent = str_replace('lmm-', '', $item_parent);
        }

        if ($icon = get_post_meta($item->ID, LMM . '_menu_item_icon', true)) {
            $item->icon = $icon;
        }

        if ($subtitle = get_post_meta($item->ID, LMM . '_menu_item_subtitle', true)) {
            $item->subtitle = $subtitle;
        }
        if ($float = get_post_meta($item->ID, LMM . '_menu_item_float', true)) {
            $item->float = $float;
        }
        if ($dropdown = get_post_meta($item->ID, LMM . '_menu_item_dropdown', true)) {
            $item->dropdown = str_replace('dropdown-', '', $item->dropdown);
        }

        if ($thumb = get_post_meta($item->ID, LMM . '_menu_item_thumb', true)) {
            $item->thumb = $thumb == 'thumb' ? 'thumbnail' : $item->thumb;
        }

        if (isset($item->quadmenu) && $item->quadmenu == 'mega' && $columns = get_post_meta($item->ID, LMM . '_menu_item_mega_columns', true)) {

            $item->columns = explode(',', $columns);
        }

        if (isset($item->quadmenu) && $item->quadmenu == 'column' && $columns = get_post_meta($item->ID, LMM . '_menu_item_mega_sub_columns', true)) {

            $item->columns = explode(',', $columns);
        }

        //var_dump();
        return $item;
    }

}

new QuadMenu_LMM();
