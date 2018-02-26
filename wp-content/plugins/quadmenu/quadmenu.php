<?php
/*
 * Plugin Name: QuadMenu
 * Plugin URI:  https://www.quadmenu.com
 * Description: The best drag & drop WordPress Mega Menu plugin which allow you to create Mega Tabs & Carousel Menus.
 * Version:     1.0.9
 * Author:      QuadLayers
 * Author URI:  https://www.quadmenu.com
 * License:     GPL-2.0+
 * Copyright:   2018 QuadMenu (https://www.quadmenu.com)
 * Text Domain: quadmenu
 */

if (!defined('ABSPATH')) {
    die('-1');
}

if (!class_exists('QuadMenu')) :

    final class QuadMenu {

        private static $instance;
        private static $registered_icons;
        private static $registered_icons_names;
        public static $selected_icons;

        public static function instance() {
            if (!isset(self::$instance)) {
                self::$instance = new QuadMenu;
                self::$instance->constants();
                self::$instance->config();
                self::$instance->compatibility();
                self::$instance->includes();
                self::$instance->pro();
                self::$instance->hooks();
            }
            return self::$instance;
        }

        function pro() {
            if (is_file(QUADMENU_PATH . 'pro/quadmenu.pro.php')) {
                require_once QUADMENU_PATH . 'pro/quadmenu.pro.php';
            }
        }

        private function config() {
            require_once QUADMENU_PATH . 'includes/configuration.php';
        }

        private function compatibility() {
            require_once QUADMENU_PATH . 'includes/compatibility.php';
            require_once QUADMENU_PATH . 'includes/compatibility/megamenu.php';
            require_once QUADMENU_PATH . 'includes/compatibility/lmm.php';
            require_once QUADMENU_PATH . 'includes/compatibility/wpml.php';
        }

        private function hooks() {

            add_action('init', array($this, 'setup'));
            add_action('init', array($this, 'register_sidebar'));
            add_action('init', array($this, 'register_icons'), -35);
            add_action('init', array($this, 'admin'), -25);
            add_action('init', array($this, 'redux'), -20);
            add_action('init', array($this, 'compiler'), -15);
            //add_action('init', array($this, 'customizer'), 26);
            add_action('init', array($this, 'frontend'), -5);
            add_action('init', array($this, 'navmenu'));

            add_action('plugins_loaded', array($this, 'i18n'));
        }

        function setup() {
            add_filter('wp_setup_nav_menu_item', array($this, 'setup_nav_menu_item'));
        }

        public function register_sidebar() {

            register_sidebar(
                    array(
                        'id' => 'quadmenu-widgets',
                        'name' => esc_html__('QuadMenu Widgets', 'quadmenu'),
                        'description' => esc_html__('Do not manually edit this sidebar.', 'quadmenu')
                    )
            );
        }

        function register_icons() {

            foreach (apply_filters('quadmenu_register_icons', array()) as $id => $settings) {

                wp_register_style($id, $settings['url']);

                $settings['ID'] = $id;

                self::$registered_icons[$id] = (object) $settings;

                self::$registered_icons_names[$id] = $settings['name'];
            }
        }

        function registered_icons() {
            return (object) self::$registered_icons;
        }

        function registered_icons_names() {
            return self::$registered_icons_names;
        }

        function selected_icons() {

            global $quadmenu;

            if (empty($this->registered_icons()->{$quadmenu['styles_icons']})) {
                return false;
            }

            return $this->registered_icons()->{$quadmenu['styles_icons']};
        }

        private function constants() {

            $upload_dir = wp_upload_dir();

            $theme = get_stylesheet();

            define('QUADMENU_NAME', 'QuadMenu');

            define('QUADMENU_VERSION', '1.0.9');

            define('QUADMENU_REDUX', "quadmenu_{$theme}");

            define('QUADMENU_THEMES', "quadmenu_{$theme}_themes");

            define('QUADMENU_LOCATIONS', "quadmenu_{$theme}_locations");

            define('QUADMENU_THEME_DB_KEY', '_quadmenu_theme');

            define('QUADMENU_DB_KEY', '_menu_item_quadmenu');

            define('QUADMENU_DEV', false);

            define('QUADMENU_COMPILE', true);

            define('QUADMENU_FILE', __FILE__);

            define('QUADMENU_URL', plugin_dir_url(__FILE__));

            define('QUADMENU_PATH', plugin_dir_path(__FILE__));

            define('QUADMENU_BASENAME', plugin_basename(__FILE__));

            define('QUADMENU_BASEDIR', dirname(plugin_basename(__FILE__)));

            define('QUADMENU_URL_ASSETS', QUADMENU_URL . 'assets/');

            define('QUADMENU_PATH_CSS', trailingslashit("{$upload_dir['basedir']}/{$theme}"));

            define('QUADMENU_URL_CSS', trailingslashit("{$upload_dir['baseurl']}/{$theme}"));

            define('QUADMENU_PANEL', apply_filters('quadmenu_hook_menu_panel', 'quadmenu_options'));

            define('QUADMENU_DEMO', 'http://quadmenu.com/');

            define('QUADMENU_DOCUMENTATION', 'http://quadmenu.com/documentation/');

            define('QUADMENU_FORUM', 'http://quadmenu.com/documentation/');

            define('QUADMENU_VIDEOS', 'https://www.youtube.com/channel/UC3u4nceTZN4hPeeWvdKIXBA');

            define('QUADMENU_THEMEFOREST', 'http://themeforest.net/user/quadlayers?ref=quadlayers');
        }

        private function includes() {

            require_once QUADMENU_PATH . 'includes/functions.php';

            require_once QUADMENU_PATH . 'includes/import.php';

            require_once QUADMENU_PATH . 'includes/vc.php';

            require_once QUADMENU_PATH . 'includes/activation.php';

            require_once QUADMENU_PATH . 'includes/frontend/items.php';
        }

        public function admin() {
            require_once QUADMENU_PATH . 'includes/admin.php';
            require_once QUADMENU_PATH . 'includes/panel.php';
            require_once QUADMENU_PATH . 'includes/panel/redux.php';
            require_once QUADMENU_PATH . 'includes/panel/system.php';
        }

        public function redux() {
            require_once QUADMENU_PATH . 'includes/themes.php';
            require_once QUADMENU_PATH . 'includes/options.php';
            require_once QUADMENU_PATH . 'includes/redux.php';
            require_once QUADMENU_PATH . 'includes/icons.php';
        }

        public function compiler() {
            require_once QUADMENU_PATH . 'includes/compiler.php';
        }

        public function customizer() {

            if (!is_customize_preview())
                return;

            require_once QUADMENU_PATH . 'includes/customizer.php';
        }

        function nav_menu_selected_id() {

            $nav_menus = wp_get_nav_menus(array('orderby' => 'name'));

            $menu_count = count($nav_menus);

            // Get recently edited nav menu
            $recently_edited = (int) get_user_option('nav_menu_recently_edited');

            $nav_menu_selected_id = isset($_REQUEST['menu']) ? (int) $_REQUEST['menu'] : 0;

            // Are we on the add new screen?
            $add_new_screen = ( isset($_GET['menu']) && 0 == $_GET['menu'] ) ? true : false;

            $page_count = wp_count_posts('page');

            $one_theme_location_no_menus = ( 1 == count(get_registered_nav_menus()) && !$add_new_screen && empty($nav_menus) && !empty($page_count->publish) ) ? true : false;

            if (empty($recently_edited) && is_nav_menu($nav_menu_selected_id))
                $recently_edited = $nav_menu_selected_id;

            // Use $recently_edited if none are selected.
            if (empty($nav_menu_selected_id) && !isset($_GET['menu']) && is_nav_menu($recently_edited))
                $nav_menu_selected_id = $recently_edited;

            // On deletion of menu, if another menu exists, show it.
            if (!$add_new_screen && 0 < $menu_count && isset($_GET['action']) && 'delete' == $_GET['action'])
                $nav_menu_selected_id = $nav_menus[0]->term_id;

            // Set $nav_menu_selected_id to 0 if no menus.
            if ($one_theme_location_no_menus) {
                $nav_menu_selected_id = 0;
            } elseif (empty($nav_menu_selected_id) && !empty($nav_menus) && !$add_new_screen) {
                // if we have no selection yet, and we have menus, set to the first one in the list.
                $nav_menu_selected_id = $nav_menus[0]->term_id;
            }

            return $nav_menu_selected_id;
        }

        function is_quadmenu($nav_menu_selected_id = false) {

            global $quadmenu_locations;

            if (!$menu_locations = isset($_REQUEST['menu-locations']) && is_array($_REQUEST['menu-locations']) ? $_REQUEST['menu-locations'] : get_nav_menu_locations()) {
                return false;
            }

            if (!$nav_menu_selected_id && !$nav_menu_selected_id = $this->nav_menu_selected_id()) {
                return false;
            }

            // chek if this menu id is in the theme locations
            if (!in_array(sanitize_key($nav_menu_selected_id), $menu_locations)) {
                return false;
            }

            if (count(array_intersect(array_keys($menu_locations, $nav_menu_selected_id), array_keys((array) $quadmenu_locations))) > 0) {
                return true;
            }

            return false;
        }

        function is_quadmenu_location($location = false) {

            global $quadmenu_locations;

            if (isset($quadmenu_locations[$location])) {
                return true;
            }

            return false;
        }

        public function navmenu() {

            if (is_quadmenu()) {

                require_once QUADMENU_PATH . 'includes/backend/walker.php';
                require_once QUADMENU_PATH . 'includes/backend/settings.php';
                require_once QUADMENU_PATH . 'includes/backend/walker/widgets.php';
                require_once QUADMENU_PATH . 'includes/backend/walker/columns.php';
                require_once QUADMENU_PATH . 'includes/backend/walker/mega.php';
                require_once QUADMENU_PATH . 'includes/backend/walker/defaults.php';
                require_once QUADMENU_PATH . 'includes/backend/ajax.php';

                add_filter('wp_edit_nav_menu_walker', array($this, 'edit_nav_menu_walker'), 999);
            }
        }

        public function frontend() {

            if (is_admin())
                return;

            require_once QUADMENU_PATH . 'includes/frontend/frontend.php';
            require_once QUADMENU_PATH . 'includes/frontend/integration.php';
        }

        public function setup_nav_menu_item($item) {

            $saved_settings = array_filter((array) get_post_meta($item->ID, QUADMENU_DB_KEY, true));

            foreach ($saved_settings as $key => $value) {
                $item->{$key} = $value;
            }

            return apply_filters('quadmenu_setup_nav_menu_item', $item);
        }

        public function edit_nav_menu_walker($menu_id) {
            return 'QuadMenu_Walker_Nav_Menu_Edit';
        }

        function i18n() {
            load_plugin_textdomain('quadmenu', false, QUADMENU_BASEDIR . '/languages');
        }

        public static function taburl($id = 0) {
            return admin_url('admin.php?page=' . QUADMENU_PANEL . '&tab=' . $id);
        }

        public static function isMin() {
            $min = '';

            if (false == QUADMENU_DEV) {
                $min = '.min';
            }

            return $min;
        }

        public static function send_json_success($json) {
            if (ob_get_contents())
                ob_clean();

            wp_send_json_success($json);
        }

        public static function send_json_error($json) {
            if (ob_get_contents())
                ob_clean();

            wp_send_json_error($json);
        }

    }

    endif; // End if class_exists check

if (!function_exists('_QuadMenu')) {

    function _QuadMenu() {
        return QuadMenu::instance();
    }

    _QuadMenu();
}

if (!function_exists('is_quadmenu_location')) {

    function is_quadmenu_location($location = false) {
        return QuadMenu::instance()->is_quadmenu_location($location);
    }

}