<?php

if (!defined('ABSPATH')) {
    die('-1');
}

class Quadmenu_Locations {

    public function __construct() {

        $this->locations();

        add_action('init', array($this, 'active'), -10);

        add_action('admin_init', array($this, 'save'), 999);
    }

    function locations() {

        global $quadmenu_locations;

        if (count($quadmenu_locations = get_option(QUADMENU_LOCATIONS, array()))) {
            
        }
    }

    function active() {

        global $quadmenu, $quadmenu_locations, $quadmenu_active_locations;

        $quadmenu_active_locations = array();

        foreach ($quadmenu_locations as $id => $location) {            
            if (!empty($quadmenu[$id . '_integration']) && !empty($quadmenu[$id . '_theme'])) {
                $quadmenu_active_locations[$id] = $quadmenu[$id . '_theme'];
            }
        }
    }

    public function save() {

        global $_wp_registered_nav_menus, $quadmenu, $quadmenu_locations;

        if (count($quadmenu) && is_array($_wp_registered_nav_menus) && count($_wp_registered_nav_menus)) {

            $quadmenu_locations = array();

            foreach ($_wp_registered_nav_menus as $location => $name) {

                $quadmenu_locations[$location] = array(
                    'name' => $name
                );
            }

            update_option(QUADMENU_LOCATIONS, $quadmenu_locations);
        }
    }

}

new Quadmenu_Locations();
