<?php
if (!defined('ABSPATH')) die('-1');

if (!class_exists("WD_ASP_Menu")) {
    /**
     * Class WD_ASP_Menu
     *
     * Menu handler for Ajax Search Pro plugin. This class encapsulates the menu elements as well.
     *
     * @class         WD_ASP_Menu
     * @version       1.0
     * @package       AjaxSearchPro/Classes/Core
     * @category      Class
     * @author        Ernest Marcinko
     */
    class WD_ASP_Menu {

        /**
         * Holds the main menu item
         *
         * @var array the main menu
         */
        private static $main_menu = array(
            "title" => "Ajax Search Pro",
            "slug" => "asp_settings",
            "file" => "/backend/settings.php",
            "position" => "207.9",
            "icon_url" => "icon.png"
        );

        private static $hooks = array();

        /**
         * Submenu titles and slugs
         *
         * @var array
         */
        private static $submenu_items = array(
            array(
                "title" => "Index Table",
                "file" => "/backend/index_table.php",
                "slug" => "asp_index_table"
            ),
            array(
                "title" => "Priorities",
                "file" => "/backend/priorities.php",
                "slug" => "asp_priorities"
            ),
            array(
                "title" => "Search Statistics",
                "file" => "/backend/statistics.php",
                "slug" => "asp_statistics"
            ),
            array(
                "title" => "Analytics Integration",
                "file" => "/backend/analytics.php",
                "slug" => "asp_analytics"
            ),
            array(
                "title" => "Cache Settings",
                "file" => "/backend/cache_settings.php",
                "slug" => "asp_cache_settings"
            ),
            array(
                "title" => "Performance tracking",
                "file" => "/backend/performance.php",
                "slug" => "asp_performance"
            ),
            array(
                "title" => "Compatibility & Other Settings",
                "file" => "/backend/compatibility_settings.php",
                "slug" => "asp_compatibility_settings"
            ),
            array(
                "title" => "Export/Import",
                "file" => "/backend/export_import.php",
                "slug" => "asp_export_import"
            ),
            array(
                "title" => "Maintenance",
                "file" => "/backend/maintenance.php",
                "slug" => "asp_maintenance"
            ),
            array(
                "title" => "Help & Updates",
                "file" => "/backend/updates_help.php",
                "slug" => "asp_updates_help"
            )
        );

        /**
         * Runs the menu registration process
         */
        public static function register() {

            $capability = ASP_DEMO == 1 ? 'read' : 'manage_options';

            $h = add_menu_page(
                self::$main_menu['title'],
                self::$main_menu['title'],
                $capability,
                self::$main_menu['slug'],
                array("WD_ASP_Menu", "route"),
                ASP_URL . self::$main_menu['icon_url'],
                self::$main_menu['position']
            );
            self::$hooks[$h] = self::$main_menu['slug'];

            foreach (self::$submenu_items as $submenu) {
                $h = add_submenu_page(
                    self::$main_menu['slug'],
                    self::$main_menu['title'],
                    $submenu['title'],
                    $capability,
                    $submenu['slug'],
                    array("WD_ASP_Menu", "route")
                );
                self::$hooks[$h] = $submenu['slug'];
            }

        }

        public static function route() {
            $current_view = self::$hooks[current_filter()];
            include(ASP_PATH.'backend/'.str_replace("asp_", "", $current_view).'.php');
        }

        /**
         * Method to obtain the menu pages for context checking
         *
         * @return array
         */
        public static function getMenuPages() {
            $ret = array();

            $ret[] = self::$main_menu['slug'];

            foreach (self::$submenu_items as $menu)
                $ret[] = $menu['slug'];

            return $ret;
        }

    }
}