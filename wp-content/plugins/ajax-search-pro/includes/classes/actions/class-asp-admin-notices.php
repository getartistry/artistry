<?php
if (!defined('ABSPATH')) die('-1');

if (!class_exists("WD_ASP_Compatibility_AdminNotices")) {
    /**
     * Class WD_ASP_Compatibility_AdminNotices
     *
     * Hide admin notices, they are so annoying
     *
     * @class         WD_ASP_Compatibility_Action
     * @version       1.0
     * @package       AjaxSearchPro/Classes/Actions
     * @category      Class
     * @author        Ernest Marcinko
     */
    class WD_ASP_Compatibility_AdminNotices extends WD_ASP_Action_Abstract {

        function handle() {
            global $wp_filter;
            if(is_network_admin() && isset($wp_filter["network_admin_notices"])) {
                unset($wp_filter['network_admin_notices']);
            } elseif(is_user_admin() && isset($wp_filter["user_admin_notices"])) {
                unset($wp_filter['user_admin_notices']);
            } else {
                if(isset($wp_filter["admin_notices"])) {
                    unset($wp_filter['admin_notices']);
                }
            }

            if(isset($wp_filter["all_admin_notices"])) {
                unset($wp_filter['all_admin_notices']);
            }
        }

        // ------------------------------------------------------------
        //   ---------------- SINGLETON SPECIFIC --------------------
        // ------------------------------------------------------------
        public static function getInstance() {
            if ( ! ( self::$_instance instanceof self ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }
    }
}