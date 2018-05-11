<?php

if (!defined('ABSPATH')) {
    die('-1');
}

register_activation_hook(QUADMENU_FILE, array('QuadMenu_Activation', 'activation'));

class QuadMenu_Activation {

    function __construct() {

        add_action('admin_init', array(__CLASS__, 'redirect'));

        add_action('after_switch_theme', array(__CLASS__, 'do_compiler'));

        add_action('after_switch_theme', array(__CLASS__, 'do_redirect'));

        add_action('quadmenu_activation', array(__CLASS__, 'do_compiler'));

        add_action('quadmenu_activation', array(__CLASS__, 'do_redirect'));

        add_action('upgrader_process_complete', array(__CLASS__, 'update'), 10, 2);
    }

    static function update($upgrader_object, $options) {

        if ($options['action'] == 'update' && $options['type'] == 'plugin' && isset($options['plugins'])) {

            foreach ($options['plugins'] as $plugin) {
                if ($plugin == QUADMENU_BASENAME) {
                    self::do_compiler();
                    self::do_redirect();
                }
            }
        }
    }

    static function redirect() {

        if (is_network_admin())
            return;

        if (!get_transient('_quadmenu_redirect'))
            return;

        delete_transient('_quadmenu_redirect');

        wp_redirect(admin_url('admin.php?page=' . QUADMENU_PANEL));
    }

    static function do_compiler() {
        update_option('_quadmenu_compiler', true);
    }

    static function do_redirect() {
        set_transient('_quadmenu_redirect', true, 30);
    }

    static function activation() {
        do_action('quadmenu_activation');
    }

}

new QuadMenu_Activation();
