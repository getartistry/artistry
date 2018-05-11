<?php

if (!defined('ABSPATH')) {
    die('-1');
}

class QuadMenu_Icons extends QuadMenu_Redux {

    public $args = array();
    public $sections = array();
    public $theme;
    public $ReduxFramework;

    public function __construct() {
        
        add_action('redux/options/' . QUADMENU_OPTIONS . '/settings/change', array($this, 'icons'), 10, 2);

    }

    static function icons($options = false, $changed = false) {
            
        //if settings change, take new values from redux update
        if (empty($changed['styles_icons']))
            return;

        QuadMenu_Redux::do_reload(true);
        
        QuadMenu_Redux::add_notification('blue', sprintf(esc_html__('Theme icons have been changed. Your options panel will be reloaded. %s.', 'quadmenu'), esc_html__('Please wait', 'quadmenu')));

    }

}

new QuadMenu_Icons();
