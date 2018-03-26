<?php

if ( ! class_exists( 'Eael_Gravity_Form_Upsell' ) ) :

/**
 * eael Upsell Class
 */
class Eael_Gravity_Form_Upsell {

    /**
     * Instantiate the class
     *
     * @param string $affiliate
     */
    function __construct() {
        add_action( 'init', array( $this, 'init_hooks' ) );
    }

    /**
     * Initialize the hooks
     *
     * @return void
     */
    public function init_hooks() {

        if ( function_exists( 'eael_activate' ) && class_exists( 'GFForms' ) ) {
            return;
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        add_action( 'admin_notices', array( $this, 'activation_notice' ) );

        add_action( 'wp_ajax_eael_gravity_form_upsell_installer', array( $this, 'install_eael' ) );
    }
    /**
     * Show the plugin installation notice
     *
     * @return void
     */
    public function activation_notice() {

        ?>

        <script type="text/javascript">
            jQuery(document).ready( function($) {
                $('#eael-gravity-form-install-now').on('click', function (e) {
                    var self = $(this);
                    e.preventDefault();
                    self.addClass('install-now updating-message');
                    self.text('<?php echo esc_js( 'Installing...' ); ?>');

                    $.ajax({
                        url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                        type: 'post',
                        data: {
                            action: 'eael_gravity_form_upsell_installer',
                            _wpnonce: '<?php echo wp_create_nonce('eael_gravity_form_upsell_installer'); ?>'
                        },
                        success: function(response) {
                            self.text('<?php echo esc_js( 'Installed' ); ?>');
                            window.location.href = '<?php echo admin_url( 'admin.php?page=eael-settings' ); ?>';
                        },
                        error: function(error) {
                            self.removeClass('install-now updating-message');
                            alert( error );
                        },
                        complete: function() {
                            self.attr('disabled', 'disabled');
                            self.removeClass('install-now updating-message');
                        }
                    });
                });
            } );
        </script>
        <?php
    }


    /**
     * Fail if plugin installtion/activation fails
     *
     * @param  Object $thing
     *
     * @return void
     */
    public function fail_on_error( $thing ) {
        if ( is_wp_error( $thing ) ) {
            wp_send_json_error( $thing->get_error_message() );
        }
    }

    /**
     * Install eael
     *
     * @return void
     */
    public function install_eael() {
        check_ajax_referer( 'eael_gravity_form_upsell_installer' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( __( 'You don\'t have permission to install the plugins' ) );
        }

        $eael_status = $this->install_plugin( 'essential-addons-for-elementor-lite', 'essential_adons_elementor.php' );
        $this->fail_on_error( $eael_status );

        wp_send_json_success();
    }

    /**
     * Install and activate a plugin
     *
     * @param  string $slug
     * @param  string $file
     *
     * @return WP_Error|null
     */
    public function install_plugin( $slug, $file ) {
        include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

        $plugin_basename = $slug . '/' . $file;

        // if exists and not activated
        if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin_basename ) ) {
            return activate_plugin( $plugin_basename );
        }

        // seems like the plugin doesn't exists. Download and activate it
        $upgrader = new Plugin_Upgrader( new WP_Ajax_Upgrader_Skin() );

        $api      = plugins_api( 'plugin_information', array( 'slug' => $slug, 'fields' => array( 'sections' => false ) ) );
        $result   = $upgrader->install( $api->download_link );

        if ( is_wp_error( $result ) ) {
            return $result;
        }

        return activate_plugin( $plugin_basename );
    }
}
endif;