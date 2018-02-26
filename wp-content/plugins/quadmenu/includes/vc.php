<?php

if (!defined('ABSPATH')) {
    die('-1');
}

add_action('init', 'quadmenu_vc', 50);

function quadmenu_vc() {

    if (!class_exists('WPBakeryShortCode'))
        return;

    class WPBakeryShortCode_quadmenu_vc extends WPBakeryShortCode {

        protected function content($atts, $content = null) {

            $theme = '';

            extract(shortcode_atts(array('menu' => '', 'theme' => ''), $atts));

            $args = array(
                'echo' => false,
                'menu' => $menu,
                'theme' => $theme,
                'layout' => 'inherit'
            );

            return quadmenu($args);
        }

    }

    if (!function_exists('vc_map'))
        return;

    vc_map(
            array('base' => 'quadmenu_vc',
                'name' => QUADMENU_NAME,
                'icon' => '',
                'category' => esc_html__('Content', 'quadmenu'),
                'description' => esc_html__('QuadMenu Shortcode', 'quadmenu'),
                'params' => array(
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Menus', 'quadmenu'),
                        'param_name' => 'menu',
                        'value' => quadmenu_vc_menus(),
                        'description' => esc_html__('Choose a menu.', 'quadmenu')
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Theme', 'quadmenu'),
                        'param_name' => 'theme',
                        'value' => quadmenu_vc_themes(),
                        'description' => esc_html__('Choose a theme location.', 'quadmenu')
                    ),
                )
            )
    );
}

function quadmenu_vc_themes($themes = array()) {

    global $quadmenu_themes;

    foreach ($quadmenu_themes as $key => $theme) {

        $themes[$theme] = $key;
    }

    return $themes;
}

function quadmenu_vc_menus($menus_ids = array()) {

    $menus = wp_get_nav_menus();

    foreach ($menus as $key => $menu) {
        $menus_ids[$menu->name] = $menu->term_id;
    }

    return $menus_ids;
}
