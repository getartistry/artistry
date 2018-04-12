<?php

namespace LivemeshAddons\Blocks;

class LAE_Blocks_Manager {

    private static $block_instances = array();

    static function get_instance($block_type) {

        if (isset(self::$block_instances[$block_type])) {

            return self::$block_instances[$block_type];

        }
        else {

            $block_class = '\LivemeshAddons\Blocks\\' . self::get_class_name($block_type);

            if (class_exists($block_class)) {

                $new_instance = new $block_class();

                self::$block_instances[$block_type] = $new_instance;

                return $new_instance;
            }
        }
        return false;
    }

    static function get_class_name($template_id) {

        return 'LAE_' . ucwords($template_id, '_');

    }
}