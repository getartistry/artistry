<?php
if (!defined('ABSPATH')) {
    die('-1');
}

class QuadMenu_System extends QuadMenu_Panel {

    function __construct() {
        add_action('admin_menu', array($this, 'panel'), 10);
    }

    function panel() {
        add_submenu_page('quadmenu_welcome', 'System', 'System', 'edit_posts', 'quadmenu_system_status', array($this, 'system'));
    }

    function system() {
        $this->header();
        ?>
        <div class="about-wrap quadmenu-admin-wrap">
            <h1><?php esc_html_e('System Status', 'quadmenu'); ?></h1>
            <div class="about-text">
                <?php esc_html_e('Here you can check the system status. Yellow status means that the site will work as expected on the front end but it may cause problems in wp-admin.', 'quadmenu'); ?>
            </div>

            <?php
            global $quadmenu_active_locations, $_wp_registered_nav_menus;

            // Plugin name
            $this->add('Plugin config', array(
                'check_name' => 'Plugin name',
                'tooltip' => '',
                'value' => QUADMENU_NAME,
                'status' => 'info'
            ));

            // Plugin version
            $this->add('Plugin config', array(
                'check_name' => 'Plugin version',
                'tooltip' => '',
                'value' => QUADMENU_VERSION,
                'status' => 'info'
            ));

            // Redux version
            if (class_exists('ReduxFramework')) {
                if (version_compare(ReduxFramework::$_version, '3.6.5') < 0) {
                    $this->add('Plugin config', array(
                        'check_name' => 'Redux version',
                        'tooltip' => '',
                        'value' => sprintf('%1$s - <span class="quadmenu-status-small-text">%2$s <a href="%3$s" target="_blank">%4$s</a></span>', ReduxFramework::$_version, esc_html__('Your Redux version is outdated version: ', 'quadmenu'), 'https://es.wordpress.org/plugins/redux-framework/', esc_html__('Please install it as a plugin', 'quadmenu')),
                        'status' => 'red',
                    ));
                } else {
                    $this->add('Plugin config', array(
                        'check_name' => 'Redux version',
                        'tooltip' => '',
                        'value' => ReduxFramework::$_version,
                        'status' => 'green'
                    ));
                }
            } else {
                $this->add('Plugin config', array(
                    'check_name' => 'Redux version',
                    'tooltip' => '',
                    'value' => esc_html__('Activate ReduxFramework', 'quadmenu'),
                    'status' => 'red',
                ));
            }

            // Menu Locations            
            if (is_array($quadmenu_active_locations)) {
                $this->add('Plugin config', array(
                    'check_name' => 'Active Locations',
                    'tooltip' => '',
                    'value' => sprintf(esc_html__('You have %s active menu locations', 'quadmenu'), count($quadmenu_active_locations)),
                    'status' => count($quadmenu_active_locations) ? 'green' : 'info'
                ));
            }

            // Server status
            // -----------------------------------------------------------------
            // server info
            $this->add('php.ini configuration', array(
                'check_name' => 'Server software',
                'tooltip' => '',
                'value' => esc_html($_SERVER['SERVER_SOFTWARE']),
                'status' => 'info'
            ));

            // php version
            $this->add('php.ini configuration', array(
                'check_name' => 'PHP Version',
                'tooltip' => '',
                'value' => phpversion(),
                'status' => 'info'
            ));

            // post_max_size
            $this->add('php.ini configuration', array(
                'check_name' => 'post_max_size',
                'tooltip' => '',
                'value' => sprintf('%1$s - <span class="quadmenu-status-small-text">%2$s</span>', ini_get('post_max_size'), esc_html__('You cannot upload images, themes and plugins that have a size bigger than this value', 'quadmenu')),
                'status' => 'info'
            ));

            // php time limit
            $max_execution_time = ini_get('max_execution_time');
            if ($max_execution_time == 0 or $max_execution_time >= 300) {
                $this->add('php.ini configuration', array(
                    'check_name' => 'max_execution_time',
                    'tooltip' => '',
                    'value' => esc_html($max_execution_time),
                    'status' => 'green'
                ));
            } else {
                $this->add('php.ini configuration', array(
                    'check_name' => 'max_execution_time',
                    'tooltip' => '',
                    'value' => sprintf('%1$s - <span class="quadmenu-status-small-text">%2$s <a href="%3$s" target="_blank">%4$s</a></span>', $max_execution_time, esc_html__('To import the demo content 300 seconds of max execution time is required. See: ', 'quadmenu'), 'http://codex.wordpress.org/Common_WordPress_Errors#Maximum_execution_time_exceeded', esc_html__('Increasing max execution to PHP', 'quadmenu')),
                    'status' => 'red'
                ));
            }

            // php max input vars
            $max_input_vars = ini_get('max_input_vars');
            if ($max_input_vars == 0 or $max_input_vars >= 2000) {
                $this->add('php.ini configuration', array(
                    'check_name' => 'max_input_vars',
                    'tooltip' => '',
                    'value' => esc_html($max_input_vars),
                    'status' => 'green'
                ));
            } else {
                $this->add('php.ini configuration', array(
                    'check_name' => 'max_input_vars',
                    'tooltip' => '',
                    'value' => esc_html($max_input_vars),
                    'status' => 'yellow'
                ));
            }

            // suhosin
            if (extension_loaded('suhosin') !== true) {
                $this->add('php.ini configuration', array(
                    'check_name' => 'SUHOSIN Installed',
                    'tooltip' => '',
                    'value' => 'False',
                    'status' => 'green'
                ));
            } else {
                $this->add('php.ini configuration', array(
                    'check_name' => 'SUHOSIN Installed',
                    'tooltip' => '',
                    'value' => sprintf('%1$s - <span class="quadmenu-status-small-text">%2$s</span>', esc_html__('SUHOSIN is installed', 'quadmenu'), esc_html__('It may cause problems with saving the plugin panel if it\'s not properly configured', 'quadmenu')),
                    'status' => 'yellow'
                ));
            }

            $response_code = wp_remote_retrieve_response_code(wp_remote_get(QUADMENU_URL_ASSETS . 'frontend/less/quadmenu-locations.less'));
            // mime types
            if ($response_code == 200) {
                $this->add('Compiler', array(
                    'check_name' => esc_html__('LESS files allowed', 'quadmenu'),
                    'tooltip' => '',
                    'value' => $response_code,
                    'status' => 'green'
                ));
            } else {
                $this->add('Compiler', array(
                    'check_name' => esc_html__('Can\'t download LESS files', 'quadmenu'),
                    'tooltip' => '',
                    'value' => sprintf('%1$s error - <span class="quadmenu-status-small-text">%2$s</span>', $response_code, esc_html__('Can\'t download less mime types', 'quadmenu')),
                    'status' => 'red'
                ));
            }

            // mime types
            if (wp_is_writable(QUADMENU_PATH_CSS)) {
                $this->add('Compiler', array(
                    'check_name' => esc_html__('Folder is writable', 'quadmenu'),
                    'tooltip' => '',
                    'value' => QUADMENU_PATH_CSS,
                    'status' => 'green'
                ));
            } else {
                $this->add('Compiler', array(
                    'check_name' => esc_html__('Can\'t write uploads folder', 'quadmenu'),
                    'tooltip' => '',
                    'value' => QUADMENU_PATH_CSS,
                    'status' => 'red'
                ));
            }


            // WordPress
            // -----------------------------------------------------------------
            // home url
            $this->add('WordPress and plugins', array(
                'check_name' => 'WP Home URL',
                'tooltip' => 'test tooltip',
                'value' => home_url(),
                'status' => 'info'
            ));

            // site url
            $this->add('WordPress and plugins', array(
                'check_name' => 'WP Site URL',
                'tooltip' => 'test tooltip',
                'value' => site_url(),
                'status' => 'info'
            ));

            // home_url == site_url
            if (home_url() != site_url()) {
                $this->add('WordPress and plugins', array(
                    'check_name' => 'Home URL - Site URL',
                    'tooltip' => 'Home URL not equal to Site URL, this may indicate a problem with your WordPress configuration.',
                    'value' => sprintf('%1$s - <span class="quadmenu-status-small-text">%2$s</span>', esc_html__('Home URL != Site URL', 'quadmenu'), esc_html__('Home URL not equal to Site URL, this may indicate a problem with your WordPress configuration.', 'quadmenu')),
                    'status' => 'red'
                ));
            }

            // version
            $this->add('WordPress and plugins', array(
                'check_name' => 'WP version',
                'tooltip' => '',
                'value' => get_bloginfo('version'),
                'status' => 'info'
            ));


            // is_multisite
            $this->add('WordPress and plugins', array(
                'check_name' => 'WP multisite enabled',
                'tooltip' => '',
                'value' => is_multisite() ? 'Yes' : 'No',
                'status' => 'info'
            ));


            // language
            $this->add('WordPress and plugins', array(
                'check_name' => 'WP Language',
                'tooltip' => '',
                'value' => get_locale(),
                'status' => 'info'
            ));

            // theme locations            
            if (!is_array($_wp_registered_nav_menus) || !count($_wp_registered_nav_menus)) {
                $this->add('WordPress and plugins', array(
                    'check_name' => 'WP Menu Locations',
                    'tooltip' => '',
                    'value' => esc_html__('Your theme doesn\'t natively support menus'),
                    'status' => 'red'
                ));
            } else {
                $this->add('WordPress and plugins', array(
                    'check_name' => 'WP Menu Locations',
                    'tooltip' => '',
                    'value' => count($_wp_registered_nav_menus),
                    'status' => 'green'
                ));
            }

            // memory limit
            $memory_limit = $this->wp_memory_notation_to_number(WP_MEMORY_LIMIT);
            if ($memory_limit < 67108864) {
                $this->add('WordPress and plugins', array(
                    'check_name' => 'WP Memory Limit',
                    'tooltip' => '',
                    'value' => sprintf('%1$s - <span class="quadmenu-status-small-text">%2$s <a href="%3$s" target="_blank">%4$s</a></span>', size_format($memory_limit) . '/request', esc_html__('We recommend setting memory to at least 64MB. The Plugin is well tested with a 40MB/request limit, but if you are using multiple plugins that may not be enough. See: ', 'quadmenu'), 'http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP', esc_html__('Increasing memory allocated to PHP', 'quadmenu')),
                    'status' => 'red'
                ));
            } else {
                $this->add('WordPress and plugins', array(
                    'check_name' => 'WP Memory Limit',
                    'tooltip' => '',
                    'value' => size_format($memory_limit) . '/request',
                    'status' => 'green'
                ));
            }


            // wp debug
            if (defined('WP_DEBUG') and WP_DEBUG === true) {
                $this->add('WordPress and plugins', array(
                    'check_name' => 'WP_DEBUG',
                    'tooltip' => '',
                    'value' => esc_html__('WP_DEBUG is enabled', 'quadmenu'),
                    'status' => 'yellow'
                ));
            } else {
                $this->add('WordPress and plugins', array(
                    'check_name' => 'WP_DEBUG',
                    'tooltip' => '',
                    'value' => 'False',
                    'status' => 'green'
                ));
            }

            // caching
            $caching_plugin_list = array(
                'wp-super-cache/wp-cache.php' => array(
                    'name' => 'WP super cache',
                    'status' => 'green',
                ),
                'w3-total-cache/w3-total-cache.php' => array(
                    'name' => 'W3 total cache',
                    'status' => 'green',
                ),
                'wp-fastest-cache/wpFastestCache.php' => array(
                    'name' => 'WP Fastest Cache (We haven\'t tested this plugin.)',
                    'status' => 'yellow',
                ),
                'wp-rocket/wp-rocket.php' => array(
                    'name' => 'WP Rocket (We haven\'t tested this plugin.)',
                    'status' => 'yellow',
                ),
            );

            $active_plugins = get_plugins();

            $caching_plugin = esc_html__('No caching plugin detected', 'quadmenu');

            $caching_plugin_status = 'yellow';

            foreach (array_keys($active_plugins) as $active_plugin) {

                if (isset($caching_plugin_list[$active_plugin])) {

                    $caching_plugin = $caching_plugin_list[$active_plugin]['name'];
                    $caching_plugin_status = $caching_plugin_list[$active_plugin]['status'];
                    break;
                }
            }
            $this->add('WordPress and plugins', array(
                'check_name' => 'Caching plugin',
                'tooltip' => '',
                'value' => $caching_plugin,
                'status' => $caching_plugin_status
            ));

            $this->tables();
            ?>

        </div>
        <?php
    }

    function wp_memory_notation_to_number($size) {
        $l = substr($size, -1);
        $ret = substr($size, 0, -1);
        switch (strtoupper($l)) {
            case 'P':
                $ret *= 1024;
            case 'T':
                $ret *= 1024;
            case 'G':
                $ret *= 1024;
            case 'M':
                $ret *= 1024;
            case 'K':
                $ret *= 1024;
        }
        return $ret;
    }

}

new QuadMenu_System();
