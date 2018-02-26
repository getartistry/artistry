<?php

if (!defined('ABSPATH')) {
    die('-1');
}

class Quadmenu_Themes extends QuadMenu_Admin {

    public $args = array();
    public $sections = array();
    public $theme;
    public $ReduxFramework;

    public function __construct() {

        $this->themes();

        add_action('wp_ajax_quadmenu_add_theme', array($this, 'ajax_theme_create'));

        add_action('wp_ajax_quadmenu_delete_theme', array($this, 'ajax_theme_delete'));

        add_filter('redux/options/' . QUADMENU_REDUX . '/options', array($this, 'options'));

        add_action('redux/options/' . QUADMENU_REDUX . '/import', array($this, 'import'), 10, 2);

        add_action('redux/options/' . QUADMENU_REDUX . '/settings/change', array($this, 'title'), 10, 2);

        //add_action('redux/' . QUADMENU_REDUX . '/localize/reset', array($this, 'message'));
        add_action('wp_ajax_' . QUADMENU_REDUX . '_ajax_save', array($this, 'themes_delete'));
    }

    public function themes() {

        global $quadmenu_themes;

        $defaults = array(
            'default_theme' => esc_html('Default Theme', 'quadmenu')
        );

        $args = apply_filters('quadmenu_default_themes', (array) get_option(QUADMENU_THEMES, array()));

        $quadmenu_themes = wp_parse_args($args, $defaults);
    }

    public function options($options) {

        if ($saved_themes = get_option(QUADMENU_THEMES, array())) {
            $options['quadmenu_themes'] = $saved_themes;
        }

        return $options;
    }

    public function import($plugin_options = null, $imported_options = null) {

        if (!empty($imported_options['quadmenu_themes'])) {
            update_option(QUADMENU_THEMES, $imported_options['quadmenu_themes']);
        }
    }

    public function ajax_theme_create() {

        check_ajax_referer('quadmenu', 'nonce');

        $saved_themes = get_option(QUADMENU_THEMES, array());

        $next_id = count($saved_themes) + 1;

        $next_key = 'custom_theme_' . $next_id;

        $saved_themes[$next_key] = sprintf(esc_html__('Custom Theme %s', 'quadmenu'), $next_id);

        if (update_option(QUADMENU_THEMES, $saved_themes)) {

            Quadmenu_Compiler::do_compiler(true);

            QuadMenu_Redux::add_notification('blue', sprintf(esc_html__('New theme created. Your options panel will be reloaded to include their options. %s.', 'quadmenu'), esc_html__('Please wait', 'quadmenu')));

            QuadMenu::send_json_success(QuadMenu::taburl('quadmenu_theme_' . $next_key));
        } else {
            QuadMenu::send_json_error(esc_html__('Can\'t create theme.', 'quadmenu'));
        }

        wp_die();
    }

    public function ajax_theme_delete() {

        global $quadmenu_themes;

        check_ajax_referer('quadmenu', 'nonce');

        if (!empty($_REQUEST['current_theme'])) {

            $key = sanitize_text_field($_REQUEST['current_theme']);

            $saved_themes = get_option(QUADMENU_THEMES, array());

            unset($saved_themes[$key]);

            $prev_key = reset(array_keys($quadmenu_themes));

            if (update_option(QUADMENU_THEMES, $saved_themes)) {

                Quadmenu_Compiler::do_compiler(true);

                QuadMenu_Redux::add_notification('blue', sprintf(esc_html__('Theme deleted. Your options panel will be reloaded to remove their options. %s.', 'quadmenu'), esc_html__('Please wait', 'quadmenu')));

                QuadMenu::send_json_success(QuadMenu::taburl('quadmenu_theme_' . $prev_key));
            } else {
                QuadMenu::send_json_error(esc_html__('Can\'t delete theme.', 'quadmenu'));
            }
        }

        exit;
    }

    function themes_delete() {

        if (!empty($_POST['data']) && wp_verify_nonce($_REQUEST['nonce'], 'redux_ajax_nonce' . QUADMENU_REDUX)) {

            $redux = ReduxFrameworkInstances::get_instance(QUADMENU_REDUX);

            $values = array();

            $_POST['data'] = stripslashes($_POST['data']);

            $values = $redux->redux_parse_str($_POST['data']);

            $values = $values[QUADMENU_REDUX];

            if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
                $values = array_map('stripslashes_deep', $values);
            }

            if (!empty($values['defaults'])) {

                delete_option(QUADMENU_THEMES);
            }
        }
    }

    function title($options = false, $changed = false) {

        $update = false;

        if ($saved_themes = get_option(QUADMENU_THEMES, array())) {

            foreach ($saved_themes as $key => $name) {

                if (empty($options[$key . '_theme_title']) && $options[$key . '_theme_title'] != $name)
                    continue;

                $update = true;

                $saved_themes[$key] = $options[$key . '_theme_title'];
            }

            if ($update && update_option(QUADMENU_THEMES, $saved_themes)) {
                QuadMenu_Redux::add_notification('blue', esc_html__('Theme name changed.', 'quadmenu'));
            }
        }
    }

    function message() {
        return esc_html__('Are you sure? Resetting will lose all custom values and themes.', 'quadmenu');
    }

}

new Quadmenu_Themes();
