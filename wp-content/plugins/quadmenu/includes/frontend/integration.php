<?php

if (!defined('ABSPATH')) {
    die('-1');
}

add_shortcode('quadmenu', 'quadmenu_shortcode');

add_filter('quadmenu_get_nav_menu_args', 'quadmenu_add_nav_menu_theme', 10);
add_filter('quadmenu_get_nav_menu_args', 'quadmenu_add_nav_menu_theme_options', 20);
add_filter('quadmenu_get_nav_menu_args', 'quadmenu_add_nav_menu_location_options', 20);
add_filter('quadmenu_get_nav_menu_args', 'quadmenu_add_nav_menu_classes', 30);
add_filter('quadmenu_get_nav_menu_args', 'quadmenu_add_nav_menu_template', 40);

add_filter('wp_nav_menu_args', 'quadmenu_auto_nav_menu_args', 100000, 1);
add_filter('wp_nav_menu', 'quadmenu_layout', 5, 2); //5 before customizer

function quadmenu_shortcode($atts = array()) {

    extract(shortcode_atts(
                    array(
        'echo' => 'true',
        'theme' => '',
        'theme_location' => '',
                    ), $atts
            )
    );

    $args = array(
        'echo' => $echo == 'false' ? false : true,
        'theme' => $theme,
        'theme_location' => '',
        'sticky' => 0,
    );

    return quadmenu($args);
}

function quadmenu($args = array()) {

    //Removes the filder in all functions
    //remove_filter('wp_nav_menu_args', 'quadmenu_auto_nav_menu_args', 100000);

    $args = quadmenu_get_nav_menu_args($args);

    return wp_nav_menu($args);
}

function quadmenu_auto_nav_menu_args($args) {

    if (isset($args['theme_location']) && is_quadmenu_location($args['theme_location'])) {

        $args = quadmenu_get_nav_menu_args($args);

        remove_all_filters('wp_nav_menu_items', 60);
        remove_all_filters('wp_nav_menu_args', 60);
    }

    return $args;
}

function quadmenu_layout($nav_menu, $args) {

    if (!empty($args->menu_template)) {

        $args->menu_items = $nav_menu;

        ob_start();
        
        quadmenu_get_template($args->menu_template, $args);

        $nav_menu = ob_get_clean();
    }

    return $nav_menu;
}

function quadmenu_get_nav_menu_args($args = array()) {

    static $instance = 0;

    $defaults = array(
        'echo' => true,
        'instance' => '',
        'menu' => '',
        'theme' => '',
        'theme_location' => '',
    );

    $args = wp_parse_args($args, $defaults);

    // WP
    // -------------------------------------------------------------------------
    $args['depth'] = 99;

    $args['container'] = false;

    $args['items_wrap'] = '<ul class="%2$s">%3$s</ul>';

    $args['walker'] = new QuadMenuWalker;

    $args['id'] = $instance;

    $args['target_id'] = 'quadmenu_' . $instance;

    $args['fallback_cb'] = 'QuadMenuWalker::fallback';

    $instance++;

    return apply_filters('quadmenu_get_nav_menu_args', $args);
}

function quadmenu_add_nav_menu_theme($args) {

    if ($args['theme'] == '' && isset($args['theme_location'])) {
        $args['theme'] = quadmenu_get_menu_theme($args['theme_location']);
    }

    return $args;
}

function quadmenu_add_nav_menu_theme_options($args) {

    static $sticky;

    global $quadmenu;

    $opts = array();

    foreach ($quadmenu as $key => &$val) {
        if (!empty($args['theme']) && strpos($key, $args['theme']) !== false) {
            $opts[str_replace("{$args['theme']}_", '', $key)] = $val;
        }
    }

    $opts['layout_width_inner_selector'] = $opts['layout_width_inner'] ? $opts['layout_width_inner_selector'] : '';

    if ($opts['layout_sticky'] > 0) {
        $sticky++;
    }

    $opts['layout_sticky'] = (int) $sticky > 1 ? 0 : $opts['layout_sticky'];

    return wp_parse_args($args, $opts);
}

function quadmenu_add_nav_menu_location_options($args) {

    global $quadmenu;

    $args['unwrap'] = 0;

    if (isset($args['theme_location']) && isset($quadmenu[$args['theme_location'] . '_unwrap'])) {
        $args['unwrap'] = $quadmenu[$args['theme_location'] . '_unwrap'];
    }


    return $args;
}

function quadmenu_add_nav_menu_template($args) {

    $defaults = array(
        'layout' => 'alert',
    );

    $args = wp_parse_args($args, $defaults);

    $args['menu_template'] = apply_filters('quadmenu_add_nav_menu_template', "layout/{$args['layout']}.php", $args['layout'], $args);

    return $args;
}

function quadmenu_add_nav_menu_classes($args) {

    $args['menu_class'] = 'quadmenu-navbar-nav';

    $classes = array();

    $classes[] = 'quadmenu-' . $args['theme'];
    $classes[] = 'quadmenu-v' . QUADMENU_VERSION;
    $classes[] = 'quadmenu-align-' . $args['layout_align'];
    $classes[] = 'quadmenu-divider-' . $args['layout_divider'];
    $classes[] = 'quadmenu-carets-' . $args['layout_caret'];
    $classes[] = 'quadmenu-background-' . $args['navbar_background'];
    $classes[] = 'quadmenu-mobile-shadow-' . $args['mobile_shadow'];
    $classes[] = 'quadmenu-dropdown-shadow-' . $args['dropdown_shadow'];

    if (in_array(sanitize_key($args['layout']), array('offcanvas', 'vertical'))) {
        $classes[] = 'quadmenu-offcanvas-' . $args['layout_offcanvas_float'];
    }

    if (!empty($args['layout_hover_effect'])) {
        $classes[] = $args['layout_hover_effect'];
    }

    if (!empty($args['layout_classes'])) {
        $classes[] = $args['layout_classes'];
    }

    $args['navbar_class'] = join(' ', array_map('esc_attr', $classes));

    return $args;
}

function quadmenu_get_template($template_name, $args = array(), $template_path = '', $default_path = '') {

    if ($args && is_array($args)) {
        extract($args);
    }

    $located = quadmenu_locate_template($template_name, $template_path, $default_path, $args);

    if (!file_exists($located)) {
        _doing_it_wrong(__FUNCTION__, sprintf('<code>%s</code> does not exist.', $located), '2.1');
        return;
    }
    // Allow 3rd party plugin filter template file from their plugin
    $located = apply_filters('quadmenu_get_template', $located, $template_name, $args, $template_path, $default_path);

    include( $located );

}

function quadmenu_locate_template($template_name, $template_path = '', $default_path = '', $args = null) {

    if (!$template_path) {
        $template_path = quadmenu_layout_path();
    }

    if (!$default_path) {
        $default_path = QUADMENU_PATH . 'templates/';
    }

    // Look within passed path within the theme - this is priority
    $template = locate_template(
            array(
                trailingslashit($template_path) . $template_name,
                $template_name
            )
    );

    // Get default template
    if (!$template) {
        $template = $default_path . $template_name;
    }

    // Return what we found
    return apply_filters('quadmenu_locate_template', $template, $template_name, $template_path, $default_path, $args);
}

function quadmenu_layout_path($slash = false) {
    return apply_filters('quadmenu_layout_path', 'quadmenu') . ( $slash ? '/' : '' );
}
