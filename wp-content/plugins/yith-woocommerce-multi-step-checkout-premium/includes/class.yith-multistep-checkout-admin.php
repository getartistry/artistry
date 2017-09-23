<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
if ( ! defined( 'YITH_WCMS_VERSION' ) ) {
	exit( 'Direct access forbidden.' );
}

/**
 *
 *
 * @class      YITH_Multistep_Checkout_Admin
 * @package    Yithemes
 * @since      Version 1.0.0
 * @author     Andrea Grillo <andrea.grillo@yithemes.com>
 *
 */

if ( ! class_exists( 'YITH_Multistep_Checkout_Admin' ) ) {
	/**
	 * Class YITH_Multistep_Checkout_Admin
	 *
	 * @author Andrea Grillo <andrea.grillo@yithemes.com>
	 */
	class YITH_Multistep_Checkout_Admin {

        /**
         * @var Panel object
         */
        protected $_panel = null;


        /**
         * @var Panel page
         */
        protected $_panel_page = 'yith_wcms_panel';

        /**
         * @var bool Show the premium landing page
         */
        public $show_premium_landing = true;

         /**
         * @var string Official plugin documentation
         */
        protected $_official_documentation = 'http://yithemes.com/docs-plugins/yith-woocommerce-multi-step-checkout/' ;

        /**
         * @var string Official plugin landing page
         */
        protected $_premium_landing = 'https://yithemes.com/themes/plugins/yith-woocommerce-multi-step-checkout/' ;

                /**
         * @var string Official plugin landing page
         */
        protected $_premium_live = 'http://plugins.yithemes.com/yith-woocommerce-multi-step-checkout' ;

        /**
         * Construct
         *
         * @author Andrea Grillo <andrea.grillo@yithemes.com>
         * @since 1.0
         */
        public function __construct(){
            /* === Register Panel Settings === */
            add_action( 'admin_menu', array( $this, 'register_panel' ), 5 );

            /* === Premium Tab === */
            add_action( 'yith_wcms_premium_tab', array( $this, 'show_premium_landing' ) );
        }

        /**
         * Add a panel under YITH Plugins tab
         *
         * @return   void
         * @since    1.0
         * @author   Andrea Grillo <andrea.grillo@yithemes.com>
         * @use     /Yit_Plugin_Panel class
         * @see      plugin-fw/lib/yit-plugin-panel.php
         */
        public function register_panel() {

            if ( ! empty( $this->_panel ) ) {
                return;
            }

            $menu_title = __( 'Multi-step Checkout', 'yith-woocommerce-multi-step-checkout' );

            $admin_tabs = apply_filters( 'yith_wcms_admin_tabs', array(
                    'settings'      => __( 'Settings', 'yith-woocommerce-multi-step-checkout' ),
                )
            );

            if( $this->show_premium_landing ){
                $admin_tabs['premium'] = __( 'Premium Version', 'yith-woocommerce-multi-step-checkout' );
            }

            $args = array(
                'create_menu_page' => true,
                'parent_slug'      => '',
                'page_title'       => $menu_title,
                'menu_title'       => $menu_title,
                'capability'       => 'manage_options',
                'parent'           => '',
                'parent_page'      => 'yit_plugin_panel',
                'page'             => $this->_panel_page,
                'admin-tabs'       => $admin_tabs,
                'options-path'     => YITH_WCMS_OPTIONS_PATH,
                'links'            => $this->get_sidebar_link()
            );


            /* === Fixed: not updated theme/old plugin framework  === */
            if ( ! class_exists( 'YIT_Plugin_Panel_WooCommerce' ) ) {
                require_once( 'plugin-fw/lib/yit-plugin-panel-wc.php' );
            }

            $this->_panel = new YIT_Plugin_Panel_WooCommerce( $args );
        }

        /**
         * Show the premium landing
         *
         * @author Andrea Grillo <andrea.grillo@yithemes.com>
         * @since 1.0.0
         * @return void
         */
        public function show_premium_landing(){
            if( file_exists( YITH_WCMS_TEMPLATE_PATH . 'premium.php' )&& $this->show_premium_landing ){
                require_once( YITH_WCMS_TEMPLATE_PATH . 'premium.php' );
            }
        }

         /**
         * Get the premium landing uri
         *
         * @since   1.0.0
         * @author  Andrea Grillo <andrea.grillo@yithemes.com>
         * @return  string The premium landing link
         */
        public function get_premium_landing_uri() {
            return defined( 'YITH_REFER_ID' ) ? $this->_premium_landing . '?refer_id=' . YITH_REFER_ID : $this->_premium_landing . '?refer_id=1030585';
        }

        /**
         * Get the panle page id
         *
         * @since   1.2.1
         * @author  Andrea Grillo <andrea.grillo@yithemes.com>
         * @return  string The premium landing link
         */
        public function get_panel_page(){
            return $this->_panel_page;
        }

        /**
         * Sidebar links
         *
         * @return   array The links
         * @since    1.2.1
         * @author   Andrea Grillo <andrea.grillo@yithemes.com>
         */
        public function get_sidebar_link(){
            $links =  array(
                array(
                    'title' => __( 'Plugin documentation', 'yith-woocommerce-multi-step-checkout' ),
                    'url'   => $this->_official_documentation,
                ),
                array(
                    'title' => __( 'Help Center', 'yith-woocommerce-multi-step-checkout' ),
                    'url'   => 'http://support.yithemes.com/hc/en-us/categories/202568518-Plugins',
                ),
            );

            if( defined( 'YITH_WCMS_FREE_INIT' ) ){
                $links[] = array(
                    'title' => __( 'Discover the premium version', 'yith-woocommerce-multi-step-checkout' ),
                    'url'   => $this->_premium_landing,
                );

                $links[] = array(
                    'title' => __( 'Free vs Premium', 'yith-woocommerce-multi-step-checkout' ),
                    'url'   => 'https://yithemes.com/themes/plugins/yith-woocommerce-multi-step-checkout/#tab-free_vs_premium_tab',
                );

                $links[] = array(
                    'title' => __( 'Premium live demo', 'yith-woocommerce-multi-step-checkout' ),
                    'url'   => $this->_premium_live
                );

                $links[] =  array(
                    'title' => __( 'WordPress support forum', 'yith-woocommerce-multi-step-checkout' ),
                    'url'   => 'https://wordpress.org/plugins/yith-woocommerce-multi-step-checkout/',
                );

                $links[] =  array(
                    'title' => sprintf( '%s (%s %s)', __( 'Changelog', 'yith-woocommerce-multi-step-checkout' ), __( 'current version','yith-woocommerce-multi-step-checkout' ), YITH_WCMS_VERSION ),
                    'url'   => 'https://yithemes.com/docs-plugins/yith-woocommerce-multi-step-checkout/06-changelog-free.html',
                );
            }

            if( defined( 'YITH_WCMS_PREMIUM' ) ){
                $links[] =  array(
                    'title' => __( 'Support platform', 'yith-woocommerce-multi-step-checkout' ),
                    'url'   => 'https://yithemes.com/my-account/support/dashboard/',
                );

                $links[] =  array(
                    'title' => sprintf( '%s (%s %s)', __( 'Changelog', 'yith-woocommerce-multi-step-checkout' ), __( 'current version','yith-woocommerce-multi-step-checkout' ), YITH_WCMS_VERSION ),
                    'url'   => 'https://yithemes.com/docs-plugins/yith-woocommerce-multi-step-checkout/07-changelog-premium.html',
                );
            }

            return $links;
        }
    }
}