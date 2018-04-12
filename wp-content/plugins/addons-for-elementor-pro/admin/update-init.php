<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class LAE_Updater {


    protected $plugin_slug = 'livemesh_el_addons';

    public function __construct() {

        $this->setup_constants();

        $this->includes();

        $this->init_hooks();

    }

    private function setup_constants() {

        // this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
        define('LAE_EDD_STORE_URL', 'https://www.livemeshthemes.com'); // you should use your own CONSTANT name, and be sure to replace it throughout this file

        // the name of your product. This should match the download name in EDD exactly
        define('LAE_EDD_ITEM_NAME', 'Addons for Elementor Pro'); // you should use your own CONSTANT name, and be sure to replace it throughout this file

        // the name of the settings page for the license input to be displayed
        define('LAE_EDD_PLUGIN_LICENSE_PAGE', $this->plugin_slug . '_license');

    }

    public function includes() {

        if (!class_exists('EDD_SL_Plugin_Updater')) {
            // load our custom updater
            include(dirname(__FILE__) . '/updates/EDD_SL_Plugin_Updater.php');
        }

    }

    public function init_hooks() {

        add_action('admin_init', array($this, 'sl_plugin_updater'), 0);

        add_action('admin_init', array($this, 'register_option'));

        add_action('admin_init', array($this, 'activate_license'));

        add_action('admin_init', array($this, 'deactivate_license'));

        // Build license submenu - AFTER the main plugin menu is built
        add_action('admin_menu', array($this, 'add_license_submenu'), 12);

        add_action('admin_notices', array($this, 'admin_notices'));

    }

    function sl_plugin_updater() {

        // retrieve our license key from the DB
        $license_key = trim(get_option('lae_license_key'));

        // setup the updater
        $edd_updater = new EDD_SL_Plugin_Updater(LAE_EDD_STORE_URL, LAE_PLUGIN_FILE, array(
                'version' => LAE_VERSION,                // current version number
                'license' => $license_key,        // license key (used get_option above to retrieve from DB)
                'item_name' => LAE_EDD_ITEM_NAME,    // name of this plugin
                'author' => 'Livemesh Themes',  // author of this plugin
                'beta' => false
            )
        );

    }

    function add_license_submenu() {
        // add license submenu page
        add_submenu_page(
            $this->plugin_slug,
            __('Elementor Addons License', 'livemesh-el-addons'),
            __('License', 'livemesh-el-addons'),
            'manage_options',
            LAE_EDD_PLUGIN_LICENSE_PAGE,
            array($this, 'display_license_page')
        );
    }

    function get_hidden_license_key() {

        $input_string = get_option('lae_license_key');

        $start = 5;
        $length = mb_strlen( $input_string ) - $start - 5;

        $mask_string = preg_replace( '/\S/', 'X', $input_string );
        $mask_string = mb_substr( $mask_string, $start, $length );
        $input_string = substr_replace( $input_string, $mask_string, $start, $length );

        return $input_string;
    }

    function display_license_page() {

        $license_key = get_option('lae_license_key');

        $status = get_option('lae_license_status');
        ?>

        <div class="wrap lae-license-wrap">

            <h2><?php _e('License Settings', 'livemesh-el-addons'); ?></h2>

            <form class="lae-license-box" method="post" action="options.php">

                <?php if ((empty($license_key)) || $status === false || $status !== 'valid'): ?>

                    <h4><?php _e( 'Enter your license key below to qualify for premium support and to enable automatic updates straight to your dashboard.', 'livemesh-el-addons' ); ?></h4>

                    <ol>
                        <li><?php printf( __( 'You can find your key in your purchase receipt email or login to <a href="%s" target="_blank">your account</a> to get your license key.', 'livemesh-el-addons' ), 'https://www.livemeshthemes.com/your-account/' ); ?></li>
                        <li><?php printf( __( 'If you don\'t yet have a license key, <a href="%s" target="_blank">purchase Addons for Elementor PRO</a>.', 'livemesh-el-addons' ), 'https://www.livemeshthemes.com/elementor-addons/pricing/' ); ?></li>
                        <li><?php _e( __( 'Copy the license key from your account and paste it below.', 'livemesh-el-addons' ) ); ?></li>
                    </ol>

                <?php elseif ($status === 'valid'): ?>

                    <p><?php _e( 'Your license entitles you for premium support and enables automatic updates straight to your dashboard.', 'livemesh-el-addons' ); ?></p>

                <?php endif; ?>

                <?php settings_fields('lae_license'); ?>

                <table class="form-table">
                    <tbody>
                    <tr valign="top">
                        <th scope="row" valign="top">
                            <?php _e('License Key', 'livemesh-el-addons'); ?>
                        </th>
                        <td>

                            <?php if ((empty($license_key)) || $status === false || $status !== 'valid'): ?>

                                <input id="lae_license_key" name="lae_license_key" type="text" class="regular-text code" placeholder="<?php _e( 'Please enter your license key here', 'livemesh-el-addons' ); ?>"
                                       value="<?php esc_attr_e($license_key); ?>"/>

                                <p class="description"><?php printf( __( 'License key looks this: fb351f05958872E193feb37a505a84be', 'livemesh-el-addons' ), 'https://www.livemeshthemes.com/your-account/' ); ?></p>

                            <?php else: ?>

                                <input id="lae_license_key" name="lae_license_key" type="text" class="regular-text code"
                                       value="<?php echo esc_attr( $this->get_hidden_license_key() ); ?>" disabled />

                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>

                    </tr>
                    <tr valign="top">
                        <th scope="row" valign="top">
                            <?php _e('License Status', 'livemesh-el-addons'); ?>
                        </th>
                        <td>
                            <?php if ($status !== false && $status === 'valid') { ?>
                                <span style="color:green; margin-right: 10px; line-height: 26px;"><?php _e('active', 'livemesh-el-addons'); ?></span>
                                <?php wp_nonce_field('lae_license_nonce', 'lae_license_nonce'); ?>
                                <input type="submit" class="button button-primary" name="lae_license_deactivate"
                                       value="<?php _e('Deactivate License', 'livemesh-el-addons'); ?>"/>
                            <?php }
                            else {
                                wp_nonce_field('lae_license_nonce', 'lae_license_nonce'); ?>
                                <input type="submit" class="button button-primary" name="lae_license_activate"
                                       value="<?php _e('Activate License', 'livemesh-el-addons'); ?>"/>
                            <?php } ?>
                        </td>
                    </tr>
                    </tbody>
                </table>

            </form>

        </div>

        <?php
    }

    function register_option() {
        // creates our settings in the options table
        register_setting('lae_license', 'lae_license_key', array($this, 'sanitize_license'));
    }

    function sanitize_license($new) {
        $old = get_option('lae_license_key');
        if ($old && $old != $new) {
            delete_option('lae_license_status'); // new license has been entered, so must reactivate
        }
        return $new;
    }

    function activate_license() {

        // listen for our activate button to be clicked
        if (isset($_POST['lae_license_activate'])) {

            // run a quick security check
            if (!check_admin_referer('lae_license_nonce', 'lae_license_nonce'))
                return; // get out if we didn't click the Activate button

            // retrieve the license from the database
            $license_key = trim($_POST['lae_license_key']);

            if (empty($license_key)) {
                $message = __('Please enter your license key.', 'livemesh-el-addons');
            }
            else {

                // data to send in our API request
                $api_params = array(
                    'edd_action' => 'activate_license',
                    'license' => $license_key,
                    'item_name' => urlencode(LAE_EDD_ITEM_NAME), // the name of our product in EDD
                    'url' => home_url()
                );

                // Call the custom API.
                $response = wp_remote_post(LAE_EDD_STORE_URL, array('timeout' => 15, 'sslverify' => false, 'body' => $api_params));

                // make sure the response came back okay
                if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {

                    if (is_wp_error($response)) {
                        $message = $response->get_error_message();
                    }
                    else {
                        $message = __('An error occurred, please try again.', 'livemesh-el-addons');
                    }

                }
                else {

                    $license_data = json_decode(wp_remote_retrieve_body($response));

                    if (false === $license_data->success) {

                        switch ($license_data->error) {

                            case 'expired' :

                                $message = sprintf(
                                    __('Your license key expired on %s.', 'livemesh-el-addons'),
                                    date_i18n(get_option('date_format'), strtotime($license_data->expires, current_time('timestamp')))
                                );
                                break;

                            case 'revoked' :

                                $message = __('Your license key has been disabled.', 'livemesh-el-addons');
                                break;

                            case 'missing' :

                                $message = __('Invalid license.', 'livemesh-el-addons');
                                break;

                            case 'invalid' :
                            case 'site_inactive' :

                                $message = __('Your license is not active for this URL.', 'livemesh-el-addons');
                                break;

                            case 'item_name_mismatch' :

                                $message = sprintf(__('This appears to be an invalid license key for %s.', 'livemesh-el-addons'), LAE_EDD_ITEM_NAME);
                                break;

                            case 'no_activations_left':

                                $message = __('Your license key has reached its activation limit.', 'livemesh-el-addons');
                                break;

                            default :

                                $message = __('An error occurred, please try again.', 'livemesh-el-addons');
                                break;
                        }

                    }

                }
            }

            // Check if anything passed on a message constituting a failure
            if (!empty($message)) {
                $base_url = admin_url('admin.php?page=' . LAE_EDD_PLUGIN_LICENSE_PAGE);
                $redirect = add_query_arg(array('sl_activation' => 'false', 'message' => urlencode($message)), $base_url);

                wp_redirect($redirect);
                exit();
            }

            // $license_data->license will be either "valid" or "invalid"

            update_option('lae_license_key', $license_key);

            update_option('lae_license_status', $license_data->license);

            wp_redirect(admin_url('admin.php?page=' . LAE_EDD_PLUGIN_LICENSE_PAGE));

            exit();
        }
    }

    /***********************************************
     * Illustrates how to deactivate a license key.
     * This will decrease the site count
     ***********************************************/

    function deactivate_license() {

        // listen for our activate button to be clicked
        if (isset($_POST['lae_license_deactivate'])) {

            // run a quick security check
            if (!check_admin_referer('lae_license_nonce', 'lae_license_nonce'))
                return; // get out if we didn't click the Activate button

            // retrieve the license from the database
            $license = trim(get_option('lae_license_key'));


            // data to send in our API request
            $api_params = array(
                'edd_action' => 'deactivate_license',
                'license' => $license,
                'item_name' => urlencode(LAE_EDD_ITEM_NAME), // the name of our product in EDD
                'url' => home_url()
            );

            // Call the custom API.
            $response = wp_remote_post(LAE_EDD_STORE_URL, array('timeout' => 15, 'sslverify' => false, 'body' => $api_params));

            // make sure the response came back okay
            if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {

                if (is_wp_error($response)) {
                    $message = $response->get_error_message();
                }
                else {
                    $message = __('An error occurred, please try again.', 'livemesh-el-addons');
                }

                $base_url = admin_url('admin.php?page=' . LAE_EDD_PLUGIN_LICENSE_PAGE);
                $redirect = add_query_arg(array('sl_activation' => 'false', 'message' => urlencode($message)), $base_url);

                wp_redirect($redirect);
                exit();
            }

            // decode the license data
            $license_data = json_decode(wp_remote_retrieve_body($response));

            // $license_data->license will be either "deactivated" or "failed"
            if ($license_data->license == 'deactivated') {

                delete_option( 'lae_license_key' );

                delete_option('lae_license_status');
            }

            wp_redirect(admin_url('admin.php?page=' . LAE_EDD_PLUGIN_LICENSE_PAGE));
            exit();

        }
    }


    /************************************
     * this illustrates how to check if
     * a license key is still valid
     * the updater does this for you,
     * so this is only needed if you
     * want to do something custom
     *************************************/

    function check_license() {

        global $wp_version;

        $license = trim(get_option('lae_license_key'));

        $api_params = array(
            'edd_action' => 'check_license',
            'license' => $license,
            'item_name' => urlencode(LAE_EDD_ITEM_NAME),
            'url' => home_url()
        );

        // Call the custom API.
        $response = wp_remote_post(LAE_EDD_STORE_URL, array('timeout' => 15, 'sslverify' => false, 'body' => $api_params));

        if (is_wp_error($response))
            return false;

        $license_data = json_decode(wp_remote_retrieve_body($response));

        if ($license_data->license == 'valid') {
            echo 'valid';
            exit;
            // this license is still valid
        }
        else {
            echo 'invalid';
            exit;
            // this license is no longer valid
        }
    }

    /**
     * This is a means of catching errors from the activation method above and displaying it to the customer
     */
    function admin_notices() {
        if ( isset( $_GET['sl_activation'] ) && ! empty( $_GET['message'] ) ) {

            switch( $_GET['sl_activation'] ) {

                case 'false':
                    $message = urldecode( $_GET['message'] );
                    ?>
                    <div class="error">
                        <p><?php echo $message; ?></p>
                    </div>
                    <?php
                    break;

                case 'true':
                default:
                    // Developers can put a custom success message here for when activation is successful if they way.
                    break;

            }
        }
    }

}

new LAE_Updater();