<?php

/*
 * Plugin Name: QuadMenu
 * Plugin URI:  https://www.quadmenu.com
 * Description: The best drag & drop WordPress Mega Menu plugin which allow you to create Tabs Menus & Carousel Menus.
 * Version:     1.5.0
 * Author:      Mega Menu
 * Author URI:  https://www.quadmenu.com
 * Copyright:   2018 QuadMenu (https://www.quadmenu.com)
 * Text Domain: quadmenu
 */

if (!defined('ABSPATH')) {
    die('-1');
}

if (!class_exists('QuadMenu')) :

    final class QuadMenu {

        private $prefix = '#quadmenu-';
        private static $instance;
        private static $registered_icons;
        private static $registered_icons_names;
        public static $selected_icons;

        public static function instance() {
            if (!isset(self::$instance)) {
                self::$instance = new QuadMenu;
                self::$instance->constants();
                self::$instance->config();
                self::$instance->includes();
                self::$instance->compatibility();
                self::$instance->pro();
                self::$instance->woo();
                self::$instance->divi();
                self::$instance->updates();
                self::$instance->hooks();
                self::$instance->errors();
            }
            return self::$instance;
        }

        function pro() {

            if (is_file(QUADMENU_PATH . 'premium/pro/pro.php')) {
                require_once QUADMENU_PATH . 'premium/pro/pro.php';
            }
        }

        function woo() {
            if (is_file(QUADMENU_PATH . 'premium/woo/woocommerce.php')) {
                require_once QUADMENU_PATH . 'premium/woo/woocommerce.php';
            }
        }

        function divi() {
            if (is_file(QUADMENU_PATH . 'premium/divi/divi.php')) {
                require_once QUADMENU_PATH . 'premium/divi/divi.php';
            }
        }

        function updates() {
            if (is_file(QUADMENU_PATH . 'premium/updates/license.php')) {
                require_once QUADMENU_PATH . 'premium/updates/license.php';
            }
        }

        private function config() {
            require_once QUADMENU_PATH . 'includes/configuration.php';
        }

        private function compatibility() {
            require_once QUADMENU_PATH . 'includes/3rd/compatibility.php';
            require_once QUADMENU_PATH . 'includes/3rd/megamenu.php';
            require_once QUADMENU_PATH . 'includes/3rd/lmm.php';
            require_once QUADMENU_PATH . 'includes/3rd/wpml.php';
            require_once QUADMENU_PATH . 'includes/3rd/polylang.php';
            require_once QUADMENU_PATH . 'includes/3rd/vc.php';
            //require_once QUADMENU_PATH . 'includes/3rd/elementor.php';
            //require_once QUADMENU_PATH . 'includes/widget.php';
        }

        private function hooks() {

            add_filter('wp_get_nav_menu_items', array($this, 'remove_nav_menu_item'), 20, 3);
            add_filter('wp_setup_nav_menu_item', array($this, 'setup_nav_menu_item_options'), 90);
            add_filter('quadmenu_setup_nav_menu_item', array($this, 'setup_nav_menu_item_parents'), 5);
            add_filter('quadmenu_setup_nav_menu_item', array($this, 'setup_nav_menu_item_validation'), 10);

            add_action('init', array($this, 'register_sidebar'));
            add_action('init', array($this, 'register_icons'), -35);
            add_action('init', array($this, 'admin'), -25);
            add_action('init', array($this, 'compiler'), -20);
            add_action('init', array($this, 'locations'), -15);
            add_action('init', array($this, 'init'), -10);
            add_action('init', array($this, 'frontend'), -5);
            add_action('admin_init', array($this, 'navmenu'));

            add_action('plugins_loaded', array($this, 'i18n'));
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

            if (empty($quadmenu['styles_icons'])) {
                return $this->registered_icons()->dashicons;
            }

            if (empty($this->registered_icons()->{$quadmenu['styles_icons']})) {
                return $this->registered_icons()->dashicons;
            }

            return $this->registered_icons()->{$quadmenu['styles_icons']};
        }

        private function theme() {

            $theme = get_stylesheet();

            $theme = preg_replace('/[^a-zA-Z0-9_\-]/', '', $theme);

            return $theme;
        }

        private function constants() {

            $upload_dir = wp_upload_dir();

            define('QUADMENU_NAME', 'QuadMenu');

            define('QUADMENU_VERSION', '1.5.0');

            define('QUADMENU_OPTIONS', "quadmenu_{$this->theme()}");

            define('QUADMENU_THEMES', "quadmenu_{$this->theme()}_themes");

            define('QUADMENU_LOCATIONS', "quadmenu_{$this->theme()}_locations");

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

            define('QUADMENU_PATH_CSS', trailingslashit("{$upload_dir['basedir']}/{$this->theme()}"));

            define('QUADMENU_URL_CSS', set_url_scheme(trailingslashit("{$upload_dir['baseurl']}/{$this->theme()}")));

            define('QUADMENU_PANEL', apply_filters('quadmenu_hook_menu_panel', 'quadmenu_options'));

            define('QUADMENU_DEMO', 'https://quadmenu.com/?utm_source=quadmenu_admin');

            define('QUADMENU_SUPPORT', 'https://quadmenu.com/submit-ticket/?utm_source=quadmenu_admin');

            define('QUADMENU_DOCUMENTATION', 'https://quadmenu.com/documentation/?utm_source=quadmenu_admin');

            define('QUADMENU_VIDEOS', 'https://www.youtube.com/channel/UC4K_eP-dbttJUAC9ToMiKUQ');

            define('QUADMENU_PURCHASE', 'https://quadmenu.com/#pricing?utm_source=quadmenu_admin');
        }

        private function includes() {

            require_once QUADMENU_PATH . 'includes/functions.php';

            require_once QUADMENU_PATH . 'includes/import.php';

            require_once QUADMENU_PATH . 'includes/activation.php';

            require_once QUADMENU_PATH . 'includes/panel.php';
        }

        public function admin() {
            require_once QUADMENU_PATH . 'includes/admin.php';
            require_once QUADMENU_PATH . 'includes/panel/system.php';
            require_once QUADMENU_PATH . 'includes/panel/options.php';
        }

        public function locations() {
            require_once QUADMENU_PATH . 'includes/locations.php';
        }

        public function init() {
            require_once QUADMENU_PATH . 'includes/themes.php';
            require_once QUADMENU_PATH . 'includes/options.php';
            require_once QUADMENU_PATH . 'includes/redux.php';
            require_once QUADMENU_PATH . 'includes/icons.php';
        }

        public function compiler() {

            if (!is_admin() && !is_customize_preview())
                return;

            require_once QUADMENU_PATH . 'includes/compiler.php';
        }

        function nav_menu_selected_id() {

            if (wp_doing_ajax() && isset($_REQUEST['menu_id'])) {
                return (int) $_REQUEST['menu_id'];
            }

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

            global $quadmenu_active_locations;

            if (!$menu_locations = isset($_REQUEST['menu-locations']) && is_array($_REQUEST['menu-locations']) ? $_REQUEST['menu-locations'] : get_nav_menu_locations()) {
                return false;
            }

            if (!$nav_menu_selected_id = $this->nav_menu_selected_id()) {
                return false;
            }

            // chek if this menu id is in the theme locations
            if (!in_array(sanitize_key($nav_menu_selected_id), $menu_locations)) {
                return false;
            }

            if (count(array_intersect(array_keys($menu_locations, $nav_menu_selected_id), array_keys((array) $quadmenu_active_locations))) > 0) {
                return true;
            }

            return false;
        }

        function is_quadmenu_location($location = false) {

            global $quadmenu_active_locations;

            if (!empty($quadmenu_active_locations[$location])) {
                return true;
            }

            return false;
        }

        public function navmenu() {

            if (is_quadmenu()) {

                require_once QUADMENU_PATH . 'includes/backend/settings.php';
                require_once QUADMENU_PATH . 'includes/backend/walker/widgets.php';
                require_once QUADMENU_PATH . 'includes/backend/walker/columns.php';
                require_once QUADMENU_PATH . 'includes/backend/walker/mega.php';
                require_once QUADMENU_PATH . 'includes/backend/walker/defaults.php';
                require_once QUADMENU_PATH . 'includes/backend/ajax.php';
            }
        }

        public function frontend() {

            //if (is_admin())
            //    return;

            require_once QUADMENU_PATH . 'includes/frontend/frontend.php';
            require_once QUADMENU_PATH . 'includes/frontend/integration.php';
            require_once QUADMENU_PATH . 'includes/frontend/items.php';
        }

        public function setup_nav_menu_item_options($item) {

            //if (empty($item->quadmenu)) {
            if (isset($item->ID)) {

                $saved_settings = (array) get_post_meta($item->ID, QUADMENU_DB_KEY, true);

                foreach ($saved_settings as $key => $value) {
                    $item->{$key} = $value;
                }

                return apply_filters('quadmenu_setup_nav_menu_item', $item);
            }
            //}

            return $item;
        }

        public function setup_nav_menu_item_parents($item) {

            global $wp_registered_widgets;

            $items = QuadMenu_Configuration::custom_nav_menu_items();

            // Quadmenu
            // -----------------------------------------------------------------

            if (strpos($item->url, $this->prefix) !== false) {
                $item->quadmenu = str_replace($this->prefix, '', $item->url);
            }

            if (isset($item->quadmenu)) {
                if (!empty($items->{$item->quadmenu}->label)) {
                    $item->type_label = '[' . $items->{$item->quadmenu}->label . ']';
                }
            }

            if (isset($item->quadmenu) && $item->object == 'custom') {
                $item->object = $item->quadmenu;
            }

            if (!isset($item->quadmenu)) {
                $item->quadmenu = $item->type;
            }

            // Replace quadmenu with object if defined
            // -----------------------------------------------------------------

            if (isset($items->{$item->object})) {
                $item->quadmenu = $item->object;
            }

            // Replace quadmenu with object post_archive
            // -----------------------------------------------------------------
            if ($item->type === 'post_type_archive' && isset($items->{$item->object . '_archive'})) {
                $item->quadmenu = $item->object . '_archive';
            }

            // Parent
            // -----------------------------------------------------------------
            if (empty($item->quadmenu_menu_item_parent)) {
                if (!empty($item->menu_item_parent)) {

                    /* if ($item->quadmenu_menu_item_parent = get_post_meta($item->menu_item_parent, '_menu_item_type', true)) {

                      if ($parent_obj = (object) get_post_meta($item->menu_item_parent, QUADMENU_DB_KEY, true)) {

                      if (isset($parent_obj->quadmenu)) {
                      $item->quadmenu_menu_item_parent = $parent_obj->quadmenu;
                      }
                      }
                      } */

                    //$parent_obj = get_post($item->menu_item_parent);
                    //if (!empty($parent_obj->ID)) {
                    //$parent_obj = wp_setup_nav_menu_item($parent_obj);
                    $parent_obj = QuadMenu::wp_setup_nav_menu_item($item->menu_item_parent);
                    //}
                    if (isset($parent_obj->type)) {
                        if (isset($parent_obj->quadmenu) && $parent_obj->type === 'custom') {
                            $item->quadmenu_menu_item_parent = $parent_obj->quadmenu;
                        } else {
                            $item->quadmenu_menu_item_parent = $parent_obj->type;
                        }
                    }
                } else {
                    $item->quadmenu_menu_item_parent = 'main';
                }
            }

            // Validation
            // -----------------------------------------------------------------

            if (!empty($items->{$item->quadmenu}->parent)) {

                $item->quadmenu_allowed_parents = $items->{$item->quadmenu}->parent;

                // Main
                // -----------------------------------------------------------------
                if (!is_array($item->quadmenu_allowed_parents) && $item->quadmenu_allowed_parents === 'main') {
                    $item->menu_item_parent = 0;
                    $item->quadmenu_menu_item_parent = 'main';
                }

                // Invalid
                // -----------------------------------------------------------------           
                if (is_array($item->quadmenu_allowed_parents) && !in_array($item->quadmenu_menu_item_parent, $item->quadmenu_allowed_parents)) {
                    $item->_invalid = true;
                }

                // Invalid
                // -----------------------------------------------------------------
                if (!is_array($item->quadmenu_allowed_parents) && $item->quadmenu_allowed_parents != $item->quadmenu_menu_item_parent) {
                    $item->_invalid = true;
                }
            } else {
                $item->quadmenu_allowed_parents = 'all';
            }

            if ($item->quadmenu == 'widget' && (empty($item->widget_id) || !isset($wp_registered_widgets[$item->widget_id]))) {
                $item->_invalid = true;
            }

            return $item;
        }

        static function wp_setup_nav_menu_item($ID) {

            if (!$item_obj = wp_cache_get('quadmenu', "wp_setup_nav_menu_item_{$ID}")) {

                $item_obj = get_post($ID);

                if (!empty($item_obj->ID)) {
                    $item_obj = wp_setup_nav_menu_item($item_obj);
                }

                wp_cache_add('quadmenu', $item_obj, "wp_setup_nav_menu_item_{$ID}");
            }

            return $item_obj;
        }

        function setup_nav_menu_item_validation($item) {

            if (isset($item->target) && $item->target === 'on') {
                $item->target = '_blank';
            }

            if (isset($item->target) && $item->target === 'off') {
                $item->target = '';
            }

            if (isset($item->columns)) {
                $item->columns = array_diff(array_filter($item->columns), array('off'));
            }

            return $item;
        }

        public function remove_nav_menu_item($items, $menu, $args) {

            if (is_quadmenu()) {

                foreach ($items as $key => $item) {

                    if (!wp_doing_ajax()) {

                        // Remove invalid items in frontend
                        if (!is_admin() && $item->_invalid) {
                            unset($items[$key]);
                        }

                        // Remove valid quadmenu items
                        if (is_admin() && !$item->_invalid && in_array(sanitize_key($item->quadmenu_menu_item_parent), apply_filters('quadmenu_remove_nav_menu_item', array('column', 'mega', 'login')))) {
                            unset($items[$key]);
                        }

                        // Remove invalid items without parent
                        if (is_admin() && $item->_invalid && !$item->quadmenu_menu_item_parent) {
                            unset($items[$key]);
                        }
                    }
                }
            }

            return $items;
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

        private function errors() {

            if (!QUADMENU_DEV)
                return;

            ini_set('error_reporting', E_ALL);
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
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

register_activation_hook(__FILE__, array('QuadMenu_Activation', 'activation'));
