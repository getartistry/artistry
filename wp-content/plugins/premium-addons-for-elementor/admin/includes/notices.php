<?php

namespace PremiumAddons;

if( !defined( 'ABSPATH') ) exit();

class Premium_Admin_Notices {
    
    private static $instance = null;
    
    private static $elementor = 'elementor';
    
    /**
    * Constructor for the class
    */
    public function __construct() {
        add_action('admin_init', array( $this, 'init') );
        
        add_action('admin_notices', array( $this, 'check_admin_notices' ) );
    }
    
    /**
    * init required functions
    */
    public function init(){
//        $this->handle_get_pro_notice();
//        $this->handle_review_notice();
          $this->handle_multi_scroll_notice();
    }
    
    /**
    * init notices check functions
    */
    public function check_admin_notices() {
        $this->required_plugins_check();
//        $this->get_review_notice();
//        $this->get_pro_notice();
        $this->get_multi_scroll_notice();
    }
    
    /**
    * Checks if get pro version message is dismissed.
    * @access public
    * @return void
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
    * Checks if review message is dismissed.
    * @access public
    * @return void
    */
    public function handle_review_notice() {

        if ( ! isset( $_GET['pa_review'] ) ) {
            return;
        }

        if ( 'opt_out' === $_GET['pa_review'] ) {
            check_admin_referer( 'opt_out' );

            update_option( 'pa_review_notice', '1' );
        }

        wp_redirect( remove_query_arg( 'pa_review' ) );
        exit;
    }
    
    /**
    * Checks if multiscroll message is dismissed.
    * @access public
    * @return void
    */
    public function handle_multi_scroll_notice() {
        if ( ! isset( $_GET['pro_scroll'] ) ) {
            return;
        }

        if ( 'opt_out' === $_GET['pro_scroll'] ) {
            check_admin_referer( 'opt_out' );

            update_option( 'pro_scroll_notice', '1' );
        }

        wp_redirect( remove_query_arg( 'pro_scroll' ) );
        exit;
    }
    
    /**
    * Shows an admin notice when Elementor is missing.
    * @since 1.0.0
    * @return boolean
    */
    public function required_plugins_check() {

        $elementor_path = sprintf( '%s/%s.php', self::$elementor, self::$elementor );
        
        if( ! defined('ELEMENTOR_VERSION' ) ) {

            if ( ! self::is_plugin_installed( $elementor_path ) ) {

                if( self::check_user_can( 'install_plugins' ) ) {

                    $install_url = wp_nonce_url( self_admin_url( sprintf( 'update.php?action=install-plugin&plugin=%s', self::$elementor ) ), 'install-plugin_elementor' );

                    $message =  __( '<p>Premium Addons for Elementor is not working because you need to Install Elementor plugin.</p>', 'premium-addons-for-elementor' );

                    $message .= sprintf( '<p><a href="%s" class="button-primary">%s</a></p>', $install_url, __( 'Install Now', 'premium-addons-for-elementor' ) );

                }
            } else {
                if( self::check_user_can( 'activate_plugins' ) ) {

                    $activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $elementor_path . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $elementor_path );

                    $message = '<p>' . __( 'Premium Addons for Elementor is not working because you need to activate Elementor plugin.', 'premium-addons-for-elementor' ) . '</p>';

                    $message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Activate Now', 'premium-addons-for-elementor' ) ) . '</p>';

                }
            }
            $this->render_admin_notices( $message );
        }
    }
        
    /**
    * Checks if review admin notice is dismissed
    * @since 2.6.8
    * @return void
    */
    public function get_review_notice() {

        $review = get_option( 'pa_review_notice' );

        $review_url = 'https://wordpress.org/support/plugin/premium-addons-for-elementor/reviews/?filter=5';

        if ( '1' === $review ) {
            return;
        } else if ( '1' !== $review ) {
            $optout_url = wp_nonce_url( add_query_arg( 'pa_review', 'opt_out' ), 'opt_out' );

            $review_message = sprintf( __('<p style="display: flex; align-items: center; padding:10px 10px 10px 0;"><img src="%s" style="margin-right: 0.8em; width: 40px;">Did you like<strong>&nbspPremium Addons for Elementor&nbsp</strong>Plugin?<span>&nbspplease help us by leaving a five star review on WordPress.org.&nbsp</span><a href="%s" target="_blank" style="flex-grow: 2;">Leave a Review.</a>', 'premium-addons-for-elementor' ), PREMIUM_ADDONS_URL .'admin/images/premium-addons-logo.png'  ,$review_url );

        }

        $review_message .= sprintf(__('<a href="%s" style="text-decoration: none; margin-left: 1em; float:right; "><span class="dashicons dashicons-dismiss"></span></a></p>', 'premium-addons-for-elementor'),  $optout_url );

        $this->render_admin_notices( $review_message );
    }
    
    
    /**
    * Shows an admin notice when Elementor is missing. 
    * @since 2.6.8
    * @return void
    */
    public function get_pro_notice() {
        
        $pro_path = 'premium-addons-pro/premium-addons-pro-for-elementor.php';
        
        $theme = self::get_installed_theme();

        $url = sprintf( 'https://premiumaddons.com/pro/?utm_source=notification&utm_medium=wp-dash&utm_campaign=get-pro&utm_term=%s', $theme );
        
        if ( ! self::is_plugin_installed( $pro_path ) && current_user_can( 'install_plugins' ) ) {

            $get_pro = get_option( 'get_pa_pro_notice' );
            
            if ( '1' === $get_pro ) {
                return;
            } else if ( '1' !== $get_pro ) {
                $optout_url = wp_nonce_url( add_query_arg( 'get_pa_pro', 'opt_out' ), 'opt_out' );

                $message = sprintf( __('<p style="display: flex; align-items: center; padding:10px 10px 10px 0;"><img src="%s" style="margin-right: 0.8em; width: 40px;"><strong>Premium Addons PRO&nbsp</strong><span> is now available!&nbsp</span><a href="%s" target="_blank" style="flex-grow: 2;"> Check it out now.</a>', 'premium-addons-for-elementor' ), PREMIUM_ADDONS_URL .'admin/images/premium-addons-logo.png'  ,$url );

            }

            $message .= sprintf(__('<a href="%s" style="text-decoration: none; margin-left: 1em; float:right; "><span class="dashicons dashicons-dismiss"></span></a></p>', 'premium-addons-for-elementor'),  $optout_url );

            $this->render_admin_notices( $message );

        }
    }
    
    /**
    * Shows an admin notice for multiscroll.
    * @since 2.7.0
    * @return void
    */
    public function get_multi_scroll_notice() {
        
        $scroll_notice = get_option( 'pro_scroll_notice' );
        
        $theme = self::get_installed_theme();
    
        $notice_url = sprintf( 'https://premiumaddons.com/multi-scroll-widget-for-elementor-page-builder?utm_source=multiscroll-notification&utm_medium=wp-dash&utm_campaign=get-pro&utm_term=%s', $theme );

        if ( '1' === $scroll_notice ) {
            return;
        } else if ( '1' !== $scroll_notice ) {
            $optout_url = wp_nonce_url( add_query_arg( 'pro_scroll', 'opt_out' ), 'opt_out' );
            
            $scroll_message = sprintf( __('<p style="display: flex; align-items: center; padding:10px 10px 10px 0;"><img src="%s" style="margin-right: 0.8em; width: 40px;"><span>NEW!&nbsp</span><strong><span>Multi-Scroll Widget for Elementor&nbsp</strong>is Now Available in Premium Addons PRO.&nbsp</span><a href="%s" target="_blank" style="flex-grow: 2;"> Check it out now.</a>', 'premium-addons-for-elementor' ), PREMIUM_ADDONS_URL .'admin/images/premium-addons-logo.png', $notice_url );

        }

        $scroll_message .= sprintf(__('<a href="%s" style="text-decoration: none; margin-left: 1em; float:right; "><span class="dashicons dashicons-dismiss"></span></a></p>', 'premium-addons-for-elementor'),  $optout_url );

        $this->render_admin_notices( $scroll_message );
        
    }
        
    /**
    * Returns the active theme slug
    */
    public static function get_installed_theme() {

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
    * Checks if a plugin is installed
    * @since 1.0.0
    * @return boolean
    */
    public static function is_plugin_installed( $plugin_path ){
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        $plugins = get_plugins();
        return isset( $plugins[ $plugin_path ] );
    }
    
    /**
    * Checks user credentials
    * @since 2.6.8
    * @return boolean
    */
    public static function check_user_can($action) {
        return current_user_can( $action );
    }
    
    /**
    * Renders an admin notice error message
    * @since 1.0.0
    * @access private
    * @return void
    */
    private function render_admin_notices( $message ) {
        ?>
            <div class="error pa-notice-wrap">
                <?php echo $message; ?>
            </div>
        <?php
    }

    public static function get_instance(){
        if( self::$instance == null ) {
            self::$instance = new self;
        }
        return self::$instance;
    }
       
}

if( ! function_exists('get_notices_instance') ) {
    /**
	 * Returns an instance of the plugin class.
	 * @since  1.1.1
	 * @return object
	 */
    function get_notices_instance() {
        return Premium_Admin_Notices::get_instance();
    }
}
get_notices_instance();