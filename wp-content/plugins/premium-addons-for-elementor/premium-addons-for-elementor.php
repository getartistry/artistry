<?php
/*
Plugin Name: Premium Addons for Elementor
Description: Premium Addons Plugin Includes 20 premium widgets for Elementor Page Builder.
Plugin URI: https://premiumaddons.com
Version: 2.5.1
Author: Leap13
Author URI: http://leap13.com/
Text Domain: premium-addons-for-elementor
Domain Path: /languages
License: GNU General Public License v3.0
*/


/**
* Checking if WordPress is installed
*/
if (!function_exists('add_action')) {
    die('WordPress not Installed'); // if WordPress not installed kill the page.
}

if (!defined('ABSPATH')) exit; // No access of directly access


define('PREMIUM_ADDONS_VERSION', '2.5.1');
define('PREMIUM_ADDONS_URL', plugins_url('/', __FILE__));
define('PREMIUM_ADDONS_PATH', plugin_dir_path(__FILE__));
define('PREMIUM_ADDONS_FILE', __FILE__);
define('PREMIUM_ADDONS_BASENAME', plugin_basename(__FILE__));
define('PREMIUM_ADDONS_STABLE_VERSION', '2.5.0');

/**
 * Loading text domain, Including required files
 */
add_action('plugins_loaded', 'premium_addons_elementor_setup');
function premium_addons_elementor_setup() {
    
    load_plugin_textdomain('premium-addons-for-elementor', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    
    // Requires System Info When on Dashboard
    if (is_admin()) {
        require_once (PREMIUM_ADDONS_PATH . 'includes/system-info.php');
        require_once (PREMIUM_ADDONS_PATH . 'includes/maintenance.php');
        require_once (PREMIUM_ADDONS_PATH . 'includes/rollback.php');
        require_once (PREMIUM_ADDONS_PATH . 'includes/beta-testers.php');
        require_once (PREMIUM_ADDONS_PATH . 'plugin.php');
        require_once (PREMIUM_ADDONS_PATH . 'admin/settings/about.php');
        require_once (PREMIUM_ADDONS_PATH . 'admin/settings/version-control.php');
        require_once (PREMIUM_ADDONS_PATH . 'admin/settings/sys-info.php');
        require_once (PREMIUM_ADDONS_PATH . 'admin/settings/gopro.php');
        $beta_testers = new PA_Beta_Testers();
    }
    
    require_once (PREMIUM_ADDONS_PATH . 'includes/helper-functions.php');
    require_once (PREMIUM_ADDONS_PATH . 'admin/settings/gomaps.php');
    require_once (PREMIUM_ADDONS_PATH . 'admin/settings/elements.php');
    
    // load the template tags
    if (file_exists(PREMIUM_ADDONS_PATH . 'elementor-helper.php')) {
        require_once (PREMIUM_ADDONS_PATH . 'elementor-helper.php');
    }
    
    /*
     * Instantiate the premium Addons for the elementor page builder
     * Included 1. 'premium_addons_widget_register()' for the main method for initaite addons
     *          2. 'premium_addons_widgets_area()'  creating the widgets
     *          3. 'initiate_elementor_addons()' creating the category of the widgets
    */
    
    new premium_Addon_Elementor();
}

// CSS THAT DISPLAYS ON EDITOR PANEL
add_action('elementor/editor/before_enqueue_scripts', function () {
    
    wp_register_style('premium-elements-progression-admin-styles', PREMIUM_ADDONS_URL . 'admin/assets/pa-elements-font/css/pa-elements.css');
    wp_enqueue_style('premium-elements-progression-admin-styles');
    
});

/*Automatic Redirection Upon Activation*/
register_activation_hook(__FILE__, 'pa_activation');

add_action('admin_init', 'pa_redirection');

function pa_activation() {
    add_option('pa_activation_redirect', true);
}

function pa_redirection() {
    
    if (get_option('pa_activation_redirect', false)) {
        delete_option('pa_activation_redirect');
        if (!is_network_admin()) {
            wp_redirect("admin.php?page=premium-addons");
        }
    }   
}

class premium_Addon_Elementor {
    
    protected $pa_elements_keys = ['premium-banner', 'premium-blog', 'premium-carousel', 'premium-countdown', 'premium-counter', 'premium-dual-header', 'premium-fancytext', 'premium-image-separator', 'premium-maps', 'premium-modalbox', 'premium-person', 'premium-progressbar', 'premium-testimonials', 'premium-title', 'premium-videobox', 'premium-pricing-table', 'premium-contactform', 'premium-button', 'premium-image-button', 'premium-grid'];
       
    /**
     * Load all the hooks here
     * @since 1.0
     */
    public function __construct() {
        add_action('admin_init', array( $this, 'handle_get_pro_notice'));
        add_action('admin_notices', array( $this, 'required_plugins_check' ));
        add_action('admin_notices', array( $this, 'get_premium_pro_notice')) ;
        add_action('elementor/init', array($this, 'initiate_elementor_addons'));
        add_action('elementor/widgets/widgets_registered', array($this, 'premium_addons_widget_register'));
        add_action('elementor/frontend/after_register_scripts', array($this, 'premium_addons_register_scripts'));
        add_action('elementor/frontend/after_register_styles', array($this, 'premium_addons_register_styles'));
        add_action('elementor/frontend/after_enqueue_styles', array($this, 'premium_addons_enqueue_styles'));
        add_action('wp_enqueue_scripts', array($this, 'premium_maps_required_script'));
        add_action('admin_post_premium_addons_rollback', 'post_premium_addons_rollback');        
    }

    /**
     * Shows an admin notice when Elementor is missing
     */
    public function required_plugins_check() {

        $elementor_path = 'elementor/elementor.php';
        
        if( !defined('ELEMENTOR_VERSION') ) {
            
            if ( ! is_plugin_installed( $elementor_path ) ) {

                if( current_user_can( 'install_plugins' ) ) {

                    $install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );

                    $message =  __( '<p>Premium Addons for Elementor is not working because you need to Install Elementor plugin.</p>', 'premium-addons-for-elementor' );

                    $message .= sprintf( '<p><a href="%s" class="button-primary">%s</a></p>', $install_url, __( 'Install Now', 'premium-addons-for-elementor' ) );

                    $this->render_admin_notices( $message );

                }
            } else {
                if( current_user_can( 'activate_plugins' ) ) {

                    $plugin = 'elementor/elementor.php';

                    $activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );

                    $message = '<p>' . __( 'Premium Addons for Elementor is not working because you need to activate Elementor plugin.', 'premium-addons-for-elementor' ) . '</p>';

                    $message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Activate Now', 'premium-addons-for-elementor' ) ) . '</p>';

                    $this->render_admin_notices( $message );    
                }
            }
        }
    }
    
    /*
     * Checks if get pro version message is dismissed
     */
    public function handle_get_pro_notice() {
        
        if ( ! isset( $_GET['get_pa_pro'] ) ) {
			return;
		}
        
        if ( 'opt_out' === $_GET['get_pa_pro'] ) {
			check_admin_referer( 'opt_out' );

			update_option( 'get_pa_pro_notice', '1' );
		}
        
        wp_redirect( remove_query_arg( 'get_pa_pro' ) );
		exit;
        
    }
    
    /**
     * Shows a dismissible admin notice to get Premium PRO version
     */
    public function get_premium_pro_notice() {
        
        $pro_path = 'premium-addons-pro/premium-addons-pro-for-elementor.php';
        
        if ( ! is_plugin_installed( $pro_path ) && current_user_can( 'install_plugins' ) ) {

            if ( '1' === get_option( 'get_pa_pro_notice' ) ) {
                return;
            }
            
            $optout_url = wp_nonce_url( add_query_arg( 'get_pa_pro', 'opt_out' ), 'opt_out' );
                
            $theme = $this->get_installed_theme();

            $url = sprintf( 'https://premiumaddons.com/pro/?utm_source=notification&utm_medium=wp-dash&utm_campaign=get-pro&utm_term=%s', $theme );

            $message = sprintf( __('<p style="display: flex; align-items: center; padding:10px 10px 10px 0;"><img src="%s" style="margin-right: 0.8em; width: 40px;"><strong>Premium Addons PRO&nbsp</strong><span> is now available!&nbsp</span><a href="%s" target="_blank" style="flex-grow: 2;"> Check it out now.</a>', 'premium-addons-for-elementor' ), PREMIUM_ADDONS_URL .'admin/images/premium-addons-logo.png'  ,$url );

            $message .= sprintf(__('<a href="%s" style="text-decoration: none; margin-left: 1em; float:right; "><span class="dashicons dashicons-dismiss"></span></a></p>', 'premium-addons-for-elementor'),  $optout_url );

            $this->render_admin_notices( $message );

        
        }
    }

    /**
    * Returns the active theme slug
    */
    public function get_installed_theme() {

        $theme = wp_get_theme();

        if( $theme->parent() ) {

            $theme_name = $theme->parent()->get('Name');

        } else {

            $theme_name = $theme->get('Name');

        }

        $theme_name = sanitize_key( $theme_name );

        return $theme_name;
    }

    /**
     * Renders an admin notice error message
     */
    public function render_admin_notices( $message ) {
        ?>
        <div class="error pa-notice-wrap">
            <?php echo $message; ?>
        </div>
        <?php 

    }
    
    /**
     * Register all frontend stylesheets
     */
    public function premium_addons_register_styles(){
        wp_register_style('premium-addons', PREMIUM_ADDONS_URL . 'assets/css/premium-addons.css', array(), PREMIUM_ADDONS_VERSION, 'all');
        $check_grid_active = isset(get_option('pa_save_settings')['premium-grid']) ? get_option('pa_save_settings')['premium-grid']: true;
        if($check_grid_active){
            wp_register_style('pa-prettyphoto', PREMIUM_ADDONS_URL . 'assets/css/prettyphoto.css', array(), PREMIUM_ADDONS_VERSION, 'all');
        }
    }
    
    /*
     * Enqueue all frontend stylesheets
     */
    public function premium_addons_enqueue_styles(){
        wp_enqueue_style('premium-addons');
        $check_grid_active = isset(get_option('pa_save_settings')['premium-grid']) ? get_option('pa_save_settings')['premium-grid']: true;
            if($check_grid_active){
                wp_enqueue_style('pa-prettyphoto');
            }
        }

    /*
     * Enqueue Premium Maps API script
     */
    public function premium_maps_required_script() {
        $premium_maps_api = get_option('pa_maps_save_settings')['premium-map-api'];
        $premium_maps_disable_api = get_option('pa_maps_save_settings')['premium-map-disable-api'];
        $premium_maps_enabled = get_option('pa_save_settings')['premium-maps'];
        if ($premium_maps_enabled == 1 && $premium_maps_disable_api == 1) {
            wp_enqueue_script('google-maps-script', 'https://maps.googleapis.com/maps/api/js?key=' . $premium_maps_api, array(), PREMIUM_ADDONS_VERSION, false);
        }
    }
    
    /**
     * Load only the required javascript files
     */
    public function premium_addons_register_scripts() {
        $pa_default_settings = array_fill_keys($this->pa_elements_keys, true);
        
        $check_component_active = get_option('pa_save_settings', $pa_default_settings);
        
        if ($check_component_active['premium-progressbar']) {
            wp_register_script('premium-addons-js', PREMIUM_ADDONS_URL . 'assets/js/premium-addons.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
            wp_register_script('waypoints', PREMIUM_ADDONS_URL . 'assets/js/lib/jquery.waypoints.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
        }
        if ($check_component_active['premium-videobox']) {
            wp_register_script('premium-addons-js', PREMIUM_ADDONS_URL . 'assets/js/premium-addons.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
        }
        if ($check_component_active['premium-grid']) {
            wp_register_script('premium-addons-js', PREMIUM_ADDONS_URL . 'assets/js/premium-addons.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
            wp_register_script('isotope-js', PREMIUM_ADDONS_URL . 'assets/js/lib/isotope.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
            wp_register_script('prettyPhoto-js', PREMIUM_ADDONS_URL . 'assets/js/lib/prettyPhoto.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
        }
        if ($check_component_active['premium-counter']) {
            wp_register_script('counter-up-js', PREMIUM_ADDONS_URL . 'assets/js/lib/countUpmin.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
            wp_register_script('waypoints', PREMIUM_ADDONS_URL . 'assets/js/lib/jquery.waypoints.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
        }
        if ($check_component_active['premium-fancytext']) {
            wp_register_script('vticker-js', PREMIUM_ADDONS_URL . 'assets/js/lib/Vticker.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
            wp_register_script('typed-js', PREMIUM_ADDONS_URL . 'assets/js/lib/typedmin.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
            wp_register_script('premium-addons-js', PREMIUM_ADDONS_URL . 'assets/js/premium-addons.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
        }
        if ($check_component_active['premium-countdown']) {
            wp_register_script('count-down-timer-js', PREMIUM_ADDONS_URL . 'assets/js/lib/jquerycountdown.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
            wp_register_script('premium-addons-js', PREMIUM_ADDONS_URL . 'assets/js/premium-addons.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
        }
        if ($check_component_active['premium-carousel']) {
            wp_register_script('premium-addons-js', PREMIUM_ADDONS_URL . 'assets/js/premium-addons.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
            wp_register_script('slick-carousel-js', PREMIUM_ADDONS_URL . 'assets/js/lib/slickmin.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
        }
        if ($check_component_active['premium-banner']) {
            wp_register_script('premium-addons-js', PREMIUM_ADDONS_URL . 'assets/js/premium-addons.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
        }
        if ($check_component_active['premium-modalbox']) {
            wp_register_script('modal-js', PREMIUM_ADDONS_URL . 'assets/js/lib/modal.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
            wp_register_script('premium-addons-js', PREMIUM_ADDONS_URL . 'assets/js/premium-addons.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
        }
        if ($check_component_active['premium-maps']) {
            wp_register_script('premium-maps-js', PREMIUM_ADDONS_URL . 'assets/js/premium-maps.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
        }
        if ($check_component_active['premium-blog']) {
            wp_register_script('isotope-js', PREMIUM_ADDONS_URL . 'assets/js/lib/isotope.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
            wp_register_script('premium-addons-js', PREMIUM_ADDONS_URL . 'assets/js/premium-addons.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
        }
    }
    
    /**
     * Load only the enabled widgets
     */
    public function premium_addons_widget_register() {
        $this->initiate_elementor_addons();
        $this->premium_addons_widgets_area();
    }
    
    private function premium_addons_widgets_area() {
        
        $pa_default_settings = array_fill_keys($this->pa_elements_keys, true);
        
        $check_component_active = get_option('pa_save_settings', $pa_default_settings);
        
        if ($check_component_active['premium-banner']) {
            require_once (PREMIUM_ADDONS_PATH . 'widgets/premium-banner.php');
        }
        if ($check_component_active['premium-carousel']) {
            require_once (PREMIUM_ADDONS_PATH . 'widgets/premium-carousel.php');
        }
        if ($check_component_active['premium-countdown']) {
            require_once (PREMIUM_ADDONS_PATH . 'widgets/premium-countdown.php');
        }
        if ($check_component_active['premium-counter']) {
            require_once (PREMIUM_ADDONS_PATH . 'widgets/premium-counter.php');
        }
        if ($check_component_active['premium-image-separator']) {
            require_once (PREMIUM_ADDONS_PATH . 'widgets/premium-imageseparator.php');
        }
        if ($check_component_active['premium-modalbox']) {
            require_once (PREMIUM_ADDONS_PATH . 'widgets/premium-modalbox.php');
        }
        if ($check_component_active['premium-progressbar']) {
            require_once (PREMIUM_ADDONS_PATH . 'widgets/premium-progressbar.php');
        }
        if ($check_component_active['premium-testimonials']) {
            require_once (PREMIUM_ADDONS_PATH . 'widgets/premium-testimonials.php');
        }
        if ($check_component_active['premium-title']) {
            require_once (PREMIUM_ADDONS_PATH . 'widgets/premium-title.php');
        }
        if ($check_component_active['premium-fancytext']) {
            require_once (PREMIUM_ADDONS_PATH . 'widgets/premium-fancytext.php');
        }
        if ($check_component_active['premium-videobox']) {
            require_once (PREMIUM_ADDONS_PATH . 'widgets/premium-videobox.php');
        }
        if ($check_component_active['premium-pricing-table']) {
            require_once (PREMIUM_ADDONS_PATH . 'widgets/premium-pricing-table.php');
        }
        if ($check_component_active['premium-blog']) {
            require_once (PREMIUM_ADDONS_PATH . 'queries.php');
            require_once (PREMIUM_ADDONS_PATH . 'widgets/premium-blog.php');
        }
        if ($check_component_active['premium-person']) {
            require_once (PREMIUM_ADDONS_PATH . 'widgets/premium-person.php');
        }
        if ($check_component_active['premium-maps']) {
            require_once (PREMIUM_ADDONS_PATH . 'widgets/premium-maps.php');
        }
        if ($check_component_active['premium-dual-header']) {
            require_once (PREMIUM_ADDONS_PATH . 'widgets/premium-dual-header.php');
        }
        if ($check_component_active['premium-button']) {
            require_once (PREMIUM_ADDONS_PATH . 'widgets/premium-button.php');
        }
        if (function_exists('wpcf7') && $check_component_active['premium-contactform']) {
            require_once (PREMIUM_ADDONS_PATH . 'widgets/premium-contactform.php');
        }
        if ($check_component_active['premium-image-button']) {
            require_once (PREMIUM_ADDONS_PATH . 'widgets/premium-image-button.php');
        }
        if ($check_component_active['premium-grid']) {
            require_once (PREMIUM_ADDONS_PATH . 'widgets/premium-grid.php');
        }
        
    }
    
    public function initiate_elementor_addons() {
        Elementor\Plugin::instance()->elements_manager->add_category(
            'premium-elements',
            array(
                'title' => \PremiumAddons\Helper_Functions::get_category()
            ),
        1);
    }
}

if ( ! function_exists( 'is_plugin_installed' ) ) {

    function is_plugin_installed($plugin_path){

        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        
        $plugins = get_plugins();
        
        return isset( $plugins[ $plugin_path ] );
    }
}