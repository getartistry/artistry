<?php

if (!defined('ABSPATH')) {
    die('-1');
}

if (defined('ELEMENTOR_PRO_VERSION')) {

    class QuadMenu_Elementor {

        public function __construct() {
            add_filter('wp_nav_menu_args', array($this, 'elementor'), 10, 1);
        }

        function elementor($args) {
            
            if (empty($args['theme_location']) && isset($args['menu_class']) && strpos($args['menu_class'], 'elementor-nav-menu') !== false) {
                $args['theme_location'] = 'primary';
            }

            return $args;
        }

    }

    new QuadMenu_Elementor();
}