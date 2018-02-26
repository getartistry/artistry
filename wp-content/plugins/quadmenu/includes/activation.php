<?php

if (!defined('ABSPATH')) {
    die('-1');
}

register_activation_hook(QUADMENU_FILE, array('QuadMenu_Activation', 'activation'));

class QuadMenu_Activation {

    function __construct() {

        if (!is_admin())
            return;

        add_action('init', array($this, 'redirect'));

        add_action('init', array($this, 'compiler'), 26);

        add_action('after_switch_theme', array($this, 'do_compiler'));

        add_action('after_switch_theme', array($this, 'do_redirect'));

        add_action('upgrader_process_complete', array($this, 'update'), 10, 2);
    }
    
    static function do_compiler() {
        set_transient('_quadmenu_compiler', true, 30);
    }
    
    static function do_redirect() {
        set_transient('_quadmenu_redirect', true, 30);
    }

    function update($upgrader_object, $options) {

        if ($options['action'] == 'update' && $options['type'] == 'plugin' && isset($options['plugins'])) {

            foreach ($options['plugins'] as $plugin) {
                if ($plugin == QUADMENU_BASENAME) {
                    QuadMenu_Activation::do_compiler();
                    QuadMenu_Activation::do_redirect();
                }
            }
        }
    }

    function compiler() {

        if (!QUADMENU_COMPILE)
            return;

        if (!get_transient('_quadmenu_compiler'))
            return;    

        delete_transient('_quadmenu_compiler');

        Quadmenu_Compiler::do_compiler(true);

        QuadMenu_Redux::add_notification('blue', sprintf('%s. %s.',esc_html__('We have to create the stylesheets', 'quadmenu'), esc_html__('Please wait', 'quadmenu')));
    }

    function redirect() {

        if (!get_transient('_quadmenu_redirect'))
            return;

        delete_transient('_quadmenu_redirect');

        wp_redirect(admin_url('admin.php?page=' . QUADMENU_PANEL));
    }

    static function activation() {

        QuadMenu_Activation::do_compiler();

        if (!is_network_admin()) {
            QuadMenu_Activation::do_redirect();
        }
    }

}

new QuadMenu_Activation();
