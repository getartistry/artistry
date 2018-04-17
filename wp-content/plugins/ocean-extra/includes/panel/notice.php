<?php
/**
 * Admin notice
 *
 * @package Ocean_Extra
 * @category Core
 * @author OceanWP
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// The Notice class
if ( ! class_exists( 'Ocean_Extra_Admin_Notice' ) ) {

    class Ocean_Extra_Admin_Notice {

        /**
         * Admin constructor
         */
        public function __construct() {
            add_action( 'admin_notices', array( $this, 'admin_notice' ) );
            add_action( 'admin_init', array( $this, 'dismiss_notice' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
        }

        /**
         * Display admin notice
         *
         * @since   1.2.6
         */
        public static function admin_notice() {
            // Show notice after 24 hours from installed time.
            if ( self::get_installed_time() > strtotime( '-24 hours' )
                || '1' === get_option( 'ocean_extra_dismiss_notice' )
                || ! current_user_can( 'manage_options' )
                || apply_filters( 'ocean_show_hooks_notice', false ) ) {
                return;
            }

            $no_thanks  = wp_nonce_url( add_query_arg( 'ocean_extra_notice', 'no_thanks_btn' ), 'no_thanks_btn' );
            $dismiss    = wp_nonce_url( add_query_arg( 'ocean_extra_notice', 'dismiss_btn' ), 'dismiss_btn' ); ?>
            
            <div class="notice notice-success ocean-extra-notice">
                <div class="notice-inner">
                    <span class="dashicons dashicons-heart"></span>
                    <div class="notice-content">
                        <p><?php echo sprintf(
                            esc_html__( 'Thanks for installing OceanWP. Do you know that it&rsquo;s even better with %1$spremium extensions%2$s? As a gesture of our appreciation, here&rsquo;s your chance to participate in our core extensions bundle giveaway. The winner gets all the premium extensions for free!', 'ocean-extra' ),
                            '<a href="https://oceanwp.org/extension-category/premium/" target="_blank">', '</a>'
                            ); ?></p>
                        <p><a href="https://oceanwp.org/bundle-contest/" class="btn button-primary" target="_blank"><?php _e( 'I want to participate', 'ocean-extra' ); ?></a><a href="<?php echo $no_thanks; ?>" class="btn button-secondary"><?php _e( 'No thanks', 'ocean-extra' ); ?></a></p>
                    </div>
                    <a href="<?php echo $dismiss; ?>" class="dismiss"><span class="dashicons dashicons-dismiss"></span></a>
                </div>
            </div>
        <?php
        }

        /**
         * Dismiss admin notice
         *
         * @since   1.2.6
         */
        public static function dismiss_notice() {
            if ( ! isset( $_GET['ocean_extra_notice'] ) ) {
                return;
            }

            if ( 'dismiss_btn' === $_GET['ocean_extra_notice'] ) {
                check_admin_referer( 'dismiss_btn' );
                update_option( 'ocean_extra_dismiss_notice', '1' );
            }

            if ( 'no_thanks_btn' === $_GET['ocean_extra_notice'] ) {
                check_admin_referer( 'no_thanks_btn' );
                update_option( 'ocean_extra_dismiss_notice', '1' );
            }

            wp_redirect( remove_query_arg( 'ocean_extra_notice' ) );
            exit;
        }

        /**
         * Installed time
         *
         * @since   1.2.6
         */
        private static function get_installed_time() {
            $installed_time = get_option( 'ocean_extra_installed_time' );
            if ( ! $installed_time ) {
                $installed_time = time();
                update_option( 'ocean_extra_installed_time', $installed_time );
            }
            return $installed_time;
        }

        /**
         * Style
         *
         * @since 1.2.1
         */
        public static function admin_scripts() {

            if ( self::get_installed_time() > strtotime( '-24 hours' )
                || '1' === get_option( 'ocean_extra_dismiss_notice' )
                || ! current_user_can( 'manage_options' )
                || apply_filters( 'ocean_show_hooks_notice', false ) ) {
                return;
            }

            // CSS
            wp_enqueue_style( 'oe-admin-notice', plugins_url( '/assets/css/notice.min.css', __FILE__ ) );

        }

    }

    new Ocean_Extra_Admin_Notice();
}