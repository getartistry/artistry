<?php

/**
 * Plugin Name: WooCommerce Conditional Shipping (formerly WooShip)
 * Plugin URI: http://www.rightpress.net/wooship
 * Description: Powerful yet easy to use WooCommerce shipping plugin
 * Author: RightPress
 * Author URI: http://www.rightpress.net
 *
 * Text Domain: wooship
 * Domain Path: /languages
 *
 * Version: 1.3.3
 *
 * Requires at least: 3.6
 * Tested up to: 4.8
 *
 * WC requires at least: 2.3
 * WC tested up to: 3.2
 *
 * @package WooShip
 * @category Core
 * @author RightPress
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define Constants
define('WOOSHIP_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('WOOSHIP_PLUGIN_URL', plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__)));
define('WOOSHIP_VERSION', '1.3.3');
define('WOOSHIP_OPTIONS_VERSION', '1');
define('WOOSHIP_SUPPORT_PHP', '5.3');
define('WOOSHIP_SUPPORT_WP', '3.6');
define('WOOSHIP_SUPPORT_WC', '2.3');

if (!class_exists('WooShip')) {

/**
 * Main plugin class
 *
 * @package WooShip
 * @author RightPress
 */
class WooShip
{
    // Singleton instance
    private static $instance = false;

    /**
     * Singleton control
     */
    public static function get_instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Class constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        // Load translation
        load_textdomain('wooship', WP_LANG_DIR . '/wooship/wooship-' . apply_filters('plugin_locale', get_locale(), 'wooship') . '.mo');
        load_plugin_textdomain('wooship', false, dirname(plugin_basename(__FILE__)) . '/languages/');

        // Load helper classes
        include_once WOOSHIP_PLUGIN_PATH . 'rightpress/rightpress-helper.class.php';
        include_once WOOSHIP_PLUGIN_PATH . 'rightpress/rightpress-wc-meta.class.php';
        include_once WOOSHIP_PLUGIN_PATH . 'rightpress/rightpress-wc-legacy.class.php';

        // Additional Plugins page links
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'plugins_page_links'));

        // Check environment
        if (!self::check_environment()) {
            return;
        }

        // Load includes
        foreach (glob(WOOSHIP_PLUGIN_PATH . 'includes/*.inc.php') as $filename)
        {
            include $filename;
        }

        // Load classes
        foreach (glob(WOOSHIP_PLUGIN_PATH . 'includes/classes/*.class.php') as $filename)
        {
            include $filename;
        }

        // Initialize automatic updates
        require_once(plugin_dir_path(__FILE__) . 'rightpress/rightpress-updates.class.php');
        RightPress_Updates_13593884::init(__FILE__, WOOSHIP_VERSION);

        // Load advanced configuration (stored separately from general shipping method settings)
        $this->config = $this->load_config();

        // Load backend assets conditionally
        if (self::is_settings_page()) {
            add_action('init', array($this, 'buffer_output'), 1);
            add_action('init', array($this, 'enqueue_select2'), 1);
            add_action('admin_enqueue_scripts', array($this, 'enqueue_backend_assets'));
            add_action('admin_enqueue_scripts', array($this, 'dequeue_woocommerce_scripts'), 11);
            add_filter('woocommerce_settings_tabs_array', array($this, 'dequeue_woocommerce_scripts_footer'), 11);
        }

        // Set up custom post types
        add_action('init', array($this, 'set_up_custom_post_types'), 1);

        // Update config with shipping zones
        add_action('init', array($this, 'update_config'));

        // Add shipping method
        add_filter('woocommerce_shipping_methods', array($this, 'add_shipping_method'));

        // Work around WooCommerce shipping method caching
        add_action('woocommerce_cart_loaded_from_session', array($this, 'maybe_reset_shipping_cache'));
        add_action('woocommerce_before_calculate_totals', array($this, 'maybe_reset_shipping_cache'));

        // Filter WP_Query results by title
        add_filter('posts_where', array($this, 'query_limit_by_title'), 10, 2);

        // Display notice on activation if shipping is not enabled in WooCommerce 2.6+
        register_activation_hook(__FILE__, array($this, 'maybe_add_shipping_disabled_notice'));
        add_action('admin_notices', array($this, 'maybe_display_shipping_disabled_notice'));
    }

    /**
     * Load advanced configuration
     *
     * @access public
     * @return array
     */
    public function load_config()
    {
        // Get config
        $config = get_option('wooship_options', array());

        // Defaults
        if (!isset($config['weight_rounding'])) {
            $config['weight_rounding'] = 0;
        }
        if (!isset($config['weight_multiplier']) || !is_numeric($config['weight_multiplier']) || empty($config['weight_multiplier'])) {
            $config['weight_multiplier'] = 1;
        }

        // Return config
        return $config;
    }

    /**
     * Update config
     *
     * @access public
     * @return void
     */
    public function update_config()
    {
        $this->config['shipping_zones'] = WooShip_Shipping_Zone::get_shipping_zones_array();
    }

    /**
     * Save config
     *
     * @access public
     * @param array $options
     * @param bool $redirect
     * @return void
     */
    public static function save_config($options, $redirect = false)
    {
        update_option('wooship_options', $options);

        // Maybe redirect to refresh config in JS
        if ($redirect) {
            wp_redirect($_SERVER['REQUEST_URI']);
            exit;
        }
    }

    /**
     * Add settings link on plugins page
     *
     * @access public
     * @param array $links
     * @return void
     */
    public function plugins_page_links($links)
    {
        // Add support link
        $settings_link = '<a href="http://url.rightpress.net/wooship-help" target="_blank">'.__('Support', 'wooship').'</a>';
        array_unshift($links, $settings_link);

        // Check if shipping is enabled in WooCommerce
        if (WooShip::check_environment()) {
            if (!RightPress_Helper::wc_version_gte('2.6') || get_option('woocommerce_ship_to_countries') !== 'disabled') {

                // Get correct section id
                $section_id = RightPress_Helper::wc_version_gte('2.6') ? 'wooship' : 'wc_shipping_wooship';

                // Add settings link
                $settings_link = '<a href="admin.php?page=wc-settings&tab=shipping&section=' . $section_id . '">'.__('Settings', 'wooship').'</a>';
                array_unshift($links, $settings_link);
            }
        }

        return $links;
    }

    /**
     * Set up custom post types
     *
     * @access public
     * @return void
     */
    public function set_up_custom_post_types()
    {
        // Register Shipping Zone post type
        register_post_type('wooship_ship_zone');
    }

    /**
     * Buffer output (used to redirect user back to the settings page so JS gets fresh config when settings are updated)
     *
     * @access public
     * @return void
     */
    public function buffer_output()
    {
        ob_start();
    }

    /**
     * Add shipping method to WooCommerce shipping methods array
     *
     * @access public
     * @param array $shipping_methods
     * @return array
     */
    public function add_shipping_method($shipping_methods)
    {
        $shipping_methods['wooship'] = 'WC_Shipping_WooShip';
        return $shipping_methods;
    }

    /**
     * Maybe reset shipping cache
     * Simply updates a custom hash within cart data and WooCommerce recalculates shipping after noticing this change
     *
     * @access public
     * @param object $cart
     * @return void
     */
    public function maybe_reset_shipping_cache($cart)
    {
        global $woocommerce;
        $properties = array();

        // Get cart items
        $cart_items = $cart->get_cart();

        // Stop execution if there are no items in cart
        if (empty($cart_items)) {
            return;
        }

        // Get first cart item key
        $cart_item_keys = array_keys($cart_items);
        $first_key = array_shift($cart_item_keys);

        // Reset if shipping method options were updated
        $properties['options'] = get_option('woocommerce_wooship_settings');

        // Reset if config was updated
        $properties['config'] = $this->config;

        // Reset if cart properties changed
        $properties['cart'] = array(
            'coupons'   => $cart->get_applied_coupons(),
            'subtotal'  => isset($cart->subtotal) ? $cart->subtotal : 0,
        );

        // Reset cache every hour
        $properties['time'] = date('YmdH');

        // Reset if customer properties changed
        $properties['customer'] = array(
            'id'            => get_current_user_id(),
            'order_count'   => self::get_user_order_count(),
        );

        // Get properties hash
        $hash = md5(json_encode($properties));

        // Set it on first product in cart (so it goes all the way to the package)
        $cart->cart_contents[$first_key]['wooship_hash'] = $hash;
    }

    /**
     * Load backend assets conditionally
     *
     * @access public
     * @return void
     */
    public function enqueue_backend_assets()
    {
        // jQuery UI Accordion
        wp_enqueue_script('jquery-ui-accordion');

        // jQuery UI Sortable
        wp_enqueue_script('jquery-ui-sortable');

        // Font awesome (icons)
        wp_enqueue_style('font-awesome', WOOSHIP_PLUGIN_URL . '/assets/font-awesome/css/font-awesome.min.css', array(), '4.1');

        // Our own scripts and styles
        wp_enqueue_script('wooship-backend-scripts', WOOSHIP_PLUGIN_URL . '/assets/js/backend.js', array('jquery'), WOOSHIP_VERSION);
        wp_enqueue_style('wooship-backend-styles', WOOSHIP_PLUGIN_URL . '/assets/css/backend.css', array(), WOOSHIP_VERSION);

        // Load datepicker
        self::enqueue_backend_datepicker();

        // Pass variables to JS
        wp_localize_script('wooship-backend-scripts', 'wooship', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
        ));

        // Pass configuration values to JS
        wp_localize_script('wooship-backend-scripts', 'wooship_config', $this->config);
        wp_localize_script('wooship-backend-scripts', 'wooship_multiselect_options', $this->get_selected_option_labels());
    }

    /**
     * Enqueue Select2
     *
     * @access public
     * @return void
     */
    public function enqueue_select2()
    {
        // Select2
        wp_enqueue_script('wooship-select2-scripts', WOOSHIP_PLUGIN_URL . '/assets/select2/js/select2.min.js', array('jquery'), '4.0.0');
        wp_enqueue_script('wooship-select2-rp', WOOSHIP_PLUGIN_URL . '/assets/js/rp-select2.js', array(), WOOSHIP_VERSION);
        wp_enqueue_style('wooship-select2-styles', WOOSHIP_PLUGIN_URL . '/assets/select2/css/select2.min.css', array(), '4.0.0');

        // Print scripts before WordPress takes care of it automatically (helps load our version of Select2 before any other plugin does it)
        add_action('wp_print_scripts', array($this, 'print_select2'));
    }

    /**
     * Print Select2 scripts
     *
     * @access public
     * @return void
     */
    public function print_select2()
    {
        remove_action('wp_print_scripts', array($this, 'print_select2'));
        wp_print_scripts('wooship-select2-scripts');
        wp_print_scripts('wooship-select2-rp');
    }

    /**
     * Dequeue some WooCommerce scripts that interfere with our UI (this is done on our settings page only)
     *
     * @access public
     * @return void
     */
    public function dequeue_woocommerce_scripts()
    {
        wp_dequeue_script('wc-enhanced-select');
        wp_dequeue_style('woocommerce_admin_styles');
        wp_dequeue_style('woocommerce_admin');
    }

    /**
     * Dequeue some WooCommerce scripts that interfere with our UI (this is done on our settings page only)
     *
     * @access public
     * @return void
     */
    public function dequeue_woocommerce_scripts_footer($value)
    {
        wp_dequeue_script('woocommerce_settings');
        return $value;
    }

    /**
     * Enqueue datepicker in backend settings page
     *
     * @access public
     * @return void
     */
    public static function enqueue_backend_datepicker()
    {
        // Datepicker configuration
        wp_localize_script('wooship-backend-scripts', 'wooship_datepicker_config', array(
            'dateFormat' => get_option('date_format')
        ));

        // jQuery UI Datepicker
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style('wooship-jquery-ui-styles', WOOSHIP_PLUGIN_URL . '/assets/jquery-ui/jquery-ui.min.css', array(), '1.11.4');

        // jQuery UI Datepicker language file
        $locale = RightPress_Helper::get_optimized_locale('mixed');
        if (file_exists(WOOSHIP_PLUGIN_PATH . 'assets/jquery-ui/i18n/datepicker-' . $locale . '.js')) {
            wp_enqueue_script('wooship-jquery-ui-language', WOOSHIP_PLUGIN_URL . '/assets/jquery-ui/i18n/datepicker-' . $locale . '.js', array('jquery-ui-datepicker'), WOOSHIP_VERSION);
        }
    }

    /**
     * Get selected multiselect field option labels
     *
     * @access public
     * @return array
     */
    public function get_selected_option_labels()
    {
        $labels = array();

        // Get option types
        $option_types = WooShip_Options::get_option_types();

        // Iterate over option types
        foreach ($option_types as $option_type) {

            // Iterate over configuration for particular option type
            if (!empty($this->config[$option_type]) && is_array($this->config[$option_type])) {
                foreach ($this->config[$option_type] as $row_key => $row) {
                    if (!empty($row['conditions']) && is_array($row['conditions'])) {
                        foreach ($row['conditions'] as $condition_key => $condition) {
                            foreach (WooShip_Options::get_multiselect_field_keys() as $key) {
                                if (!empty($condition[$key]) && is_array($condition[$key])) {
                                    $labels[$option_type][$row_key]['conditions'][$condition_key][$key] = WooShip_Conditions::get_items_by_ids($key, $condition[$key]);
                                }
                            }
                        }
                    }
                }
            }
        }

        return $labels;
    }

    /**
     * Check if current request is for WooShip settings page
     *
     * @access public
     * @return bool
     */
    public static function is_settings_page()
    {
        // We check wc_shipping_wooship too as it was the section id in pre-2.6 WooCommerce
        return (isset($_GET['section']) && in_array($_GET['section'], array('wc_shipping_wooship', 'wooship')));
    }

    /**
     * Get customer order count
     *
     * @access public
     * @param int $user_id
     * @return int
     */
    public static function get_user_order_count($user_id = null)
    {
        // Get user id
        $user_id = $user_id !== null ? $user_id : get_current_user_id();

        // No user
        if (!$user_id) {
            return 0;
        }

        // Return user order count
        return wc_get_customer_order_count($user_id);
    }

    /**
     * Enhande WP_Query by including title search
     *
     * @access public
     * @param string $where
     * @param object $wp_query
     * @return string
     */
    public function query_limit_by_title($where, $wp_query)
    {
        global $wpdb;

        // Check if title query needs to be performed
        if ($title = $wp_query->get('wooship_title_query')) {
            $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql($wpdb->esc_like($title)) . '%\'';
        }

        return $where;
    }

    /**
     * Check if environment meets requirements
     *
     * @access public
     * @return bool
     */
    public static function check_environment()
    {
        $is_ok = true;

        // Check PHP version
        if (!version_compare(PHP_VERSION, WOOSHIP_SUPPORT_PHP, '>=')) {

            // Add notice
            add_action('admin_notices', array('WooShip', 'php_version_notice'));

            // Do not proceed as RightPress Helper requires PHP 5.3 for itself
            return false;
        }

        // Check WordPress version
        if (!RightPress_Helper::wp_version_gte(WOOSHIP_SUPPORT_WP)) {
            add_action('admin_notices', array('WooShip', 'wp_version_notice'));
            $is_ok = false;
        }

        // Check if WooCommerce is enabled
        if (!class_exists('WooCommerce')) {
            add_action('admin_notices', array('WooShip', 'wc_disabled_notice'));
            $is_ok = false;
        }
        else if (!RightPress_Helper::wc_version_gte(WOOSHIP_SUPPORT_WC)) {
            add_action('admin_notices', array('WooShip', 'wc_version_notice'));
            $is_ok = false;
        }

        return $is_ok;
    }

    /**
     * Display PHP version notice
     *
     * @access public
     * @return void
     */
    public static function php_version_notice()
    {
        echo '<div class="error"><p>' . sprintf(__('<strong>WooShip</strong> requires PHP %s or later. Please update PHP on your server to use this plugin.', 'wooship'), WOOSHIP_SUPPORT_PHP) . ' ' . sprintf(__('If you have any questions, please contact %s.', 'wooship'), '<a href="http://url.rightpress.net/new-support-ticket">' . __('RightPress Support', 'wooship') . '</a>') . '</p></div>';
    }

    /**
     * Display WP version notice
     *
     * @access public
     * @return void
     */
    public static function wp_version_notice()
    {
        echo '<div class="error"><p>' . sprintf(__('<strong>WooShip</strong> requires WordPress version %s or later. Please update WordPress to use this plugin.', 'wooship'), WOOSHIP_SUPPORT_WP) . ' ' . sprintf(__('If you have any questions, please contact %s.', 'wooship'), '<a href="http://url.rightpress.net/new-support-ticket">' . __('RightPress Support', 'wooship') . '</a>') . '</p></div>';
    }

    /**
     * Display WC disabled notice
     *
     * @access public
     * @return void
     */
    public static function wc_disabled_notice()
    {
        echo '<div class="error"><p>' . sprintf(__('<strong>WooShip</strong> requires WooCommerce to be active. You can download WooCommerce %s.', 'wooship'), '<a href="http://url.rightpress.net/woocommerce-download-page">' . __('here', 'wooship') . '</a>') . ' ' . sprintf(__('If you have any questions, please contact %s.', 'wooship'), '<a href="http://url.rightpress.net/new-support-ticket">' . __('RightPress Support', 'wooship') . '</a>') . '</p></div>';
    }

    /**
     * Display WC version notice
     *
     * @access public
     * @return void
     */
    public static function wc_version_notice()
    {
        echo '<div class="error"><p>' . sprintf(__('<strong>WooShip</strong> requires WooCommerce version %s or later. Please update WooCommerce to use this plugin.', 'wooship'), WOOSHIP_SUPPORT_WC) . ' ' . sprintf(__('If you have any questions, please contact %s.', 'wooship'), '<a href="http://url.rightpress.net/new-support-ticket">' . __('RightPress Support', 'wooship') . '</a>') . '</p></div>';
    }

    /**
     * Get single option
     *
     * @access public
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function opt($key, $default = false)
    {
        $wooship = self::get_instance();
        return isset($wooship->config[$key]) ? $wooship->config[$key] : $default;
    }

    /**
     * Maybe add shipping disabled notice (WC 2.6+)
     *
     * @access public
     * @return void
     */
    public function maybe_add_shipping_disabled_notice()
    {
        if (class_exists('WooCommerce') && RightPress_Helper::wc_version_gte('2.6') && get_option('woocommerce_ship_to_countries') === 'disabled') {
            update_option('wooship_shipping_disabled_notice', 1, true);
        }
    }

    /**
     * Maybe display shipping disabled notice
     *
     * @access public
     * @return void
     */
    public function maybe_display_shipping_disabled_notice()
    {
        // Check if notice needs to be displayed
        if (get_option('wooship_shipping_disabled_notice', false)) {

            // Display notice
            echo '<div class="notice notice-warning is-dismissible">';
            echo '<p>' . sprintf(__('<strong>Warning!</strong> It looks like shipping is disabled in WooCommerce settings. You must enable shipping on <a href="%s">this page</a> to use <strong>WooShip</strong>.', 'wooship'), 'admin.php?page=wc-settings') . '</p>';
            echo '</div>';

            // Delete option
            delete_option('wooship_shipping_disabled_notice');
        }
    }

    /**
     * Check if proprietary shipping zones need to be used
     * (WooCommerce comes with its own shipping zones since 2.6)
     *
     * @access public
     * @return bool
     */
    public static function use_proprietary_shipping_zones()
    {
        $proprietary_shipping_zones = WooShip::opt('shipping_zones');
        return (!RightPress_Helper::wc_version_gte('2.6') || !empty($proprietary_shipping_zones));
    }


}

WooShip::get_instance();

}
