<?php
require_once(DUPLICATOR_PRO_PLUGIN_PATH.'classes/utilities/class.u.low.php');

/**
 * Used to generate a alert in the main WP admin screens
 *
 * Standard: PSR-2
 * @link http://www.php-fig.org/psr/psr-2
 *
 * @package DUP_PRO
 * @subpackage classes/ui
 * @copyright (c) 2017, Snapcreek LLC
 * @license	https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since 2.0.0
 *
 */
class DUP_PRO_UI_ALERT
{

    /**
     * Used by the WP action hook to detect the state of the endpoint licence
     * which calls the various show* methods for which alert to display
     *
     * @return null
     */
    public static function licenseAlertCheck()
    {
        $on_licensing_tab = (isset($_REQUEST['tab']) && ($_REQUEST['tab'] === 'licensing'));

        if ($on_licensing_tab === false) {
            if (!file_exists(DUPLICATOR_PRO_SSDIR_PATH."/ovr.dup")) {
                //Style needs to be loaded here because css is global across wp-admin
                wp_enqueue_style('dup-pro-plugin-style-notices', DUPLICATOR_PRO_PLUGIN_URL.'assets/css/admin-notices.css', null, DUPLICATOR_PRO_VERSION);
                $license_status = DUP_PRO_License_U::getLicenseStatus(false);

                if ($license_status === DUP_PRO_License_Status::Expired) {
                    self::showExpired();
                } else if ($license_status !== DUP_PRO_License_Status::Valid) {
                    $global = DUP_PRO_Global_Entity::get_instance();

                    if ($global->license_no_activations_left) {
                        self::showNoActivationsLeft();
                    } else {
                        $days_invalid = floor((time() - $global->initial_activation_timestamp) / 86400);

                        // If an md5 is present always do standard nag
                        $license_key = get_option(DUP_PRO_Constants::LICENSE_KEY_OPTION_NAME, '');
                        $md5_present = DUP_PRO_Low_U::isValidMD5($license_key);

                        if ($md5_present || ($days_invalid < DUP_PRO_Constants::UNLICENSED_SUPER_NAG_DELAY_IN_DAYS)) {
                            self::showInvalidStandardNag();
                        } else {
                            self::showInvalidSuperNag($days_invalid);
                        }
                    }
                }
            }
        }
    }

    /**
     * Shows the expired message alert
     *
     * @return string	HTML alert message hook
     */
    private static function showExpired()
    {
        $license_key = get_option(DUP_PRO_Constants::LICENSE_KEY_OPTION_NAME, '');
        $renewal_url = 'https://snapcreek.com/checkout?edd_license_key='.$license_key;
        $img_url     = plugins_url('duplicator-pro/assets/img/plug.png');

        echo "<div class='update-nag dpro-admin-notice'><p><img src='{$img_url}' style='float:left; padding:0 10px 0 5px' />".
        "<b>Warning! Your Duplicator Pro license has expired...</b> <br/>".
        "You're currently missing important updates for <b>security patches</b>, <i>bug fixes</i>, support requests, &amp; <u>new features</u>.<br/>".
        "<a target='_blank' href='{$renewal_url}'>Renew now to receive a 40% discount off the current price!</a> </p></div>";
    }

    /**
     * Shows the licence count used up alert
     *
     * @return string	HTML alert message hook
     */
    private static function showNoActivationsLeft()
    {
        $licensing_tab_url = self_admin_url()."admin.php?page=".DUP_PRO_Constants::$SETTINGS_SUBMENU_SLUG.'&tab=licensing';
        $dashboard_url     = 'https://snapcreek.com/dashboard';
        $img_url           = plugins_url('duplicator-pro/assets/img/warning.png');

        echo '<div class="update-nag dpro-admin-notice" style="font-size:1.2rem">'.
        '<div style="text-align:center">'.
        "<img src='$img_url' style='/* float:left; */text-align: center;margin: auto;padding:0 10px 0 5px; width:80px'>".
        '</div>'.
        '<p style="text-align: center;font-size: 2rem;line-height: 2.7rem; margin-top:10px">'.
        'Duplicator Pro\'s license is deactivated because you\'re out of site activations.</p>'.
        "<p style='text-align: center;font-size: 1.3rem; line-height: 2.2rem'> Upgrade your license using the <a href='$dashboard_url' target='_blank'>Snap Creek Dashboard</a> or deactivate plugin on old sites.<br/>".
        "After making necessary changes <a href='$licensing_tab_url'>refresh the license status.</a>".
        '</div>';
    }

    /**
     * Shows the smaller standard nag screen
     *
     * @return string	HTML alert message hook
     */
    private static function showInvalidStandardNag()
    {
        $img_url           = plugins_url('duplicator-pro/assets/img/warning.png');
        $licensing_tab_url = self_admin_url()."admin.php?page=".DUP_PRO_Constants::$SETTINGS_SUBMENU_SLUG.'&tab=licensing';

        echo "<div class='update-nag dpro-admin-notice'><p><img src='{$img_url}' style='float:left; padding:0 10px 0 5px' /> ".
        "<b>Warning!</b> Your Duplicator Pro license is invalid or disabled... <br/>".
        "This means this plugin doesn't have access to <b>security updates</b>, <i>bug fixes</i>, <b>support request</b> or <i>new features</i>.<br/>".
        "Please <a href='{$licensing_tab_url}'>Activate Your License</a> -or-  go to <a target='_blank' href='https://snapcreek.com'>snapcreek.com</a> to get a license.</p></div>";
    }

    /**
     * Shows the larger super nag screen used for display after the trial period
     *
     * @param int $daysInvalid The number of days the licence has been invalid
     *
     * @return string	HTML alert message hook
     */
    private static function showInvalidSuperNag($daysInvalid)
    {
        $img_url           = plugins_url('duplicator-pro/assets/img/rejected_350.png');
        $licensing_tab_url = self_admin_url()."admin.php?page=".DUP_PRO_Constants::$SETTINGS_SUBMENU_SLUG.'&tab=licensing';

        echo
        '<div class="update-nag dpro-admin-notice" style="text-align:center; font-size:16px; line-height:22px">'
        ."<img src='{$img_url}' style='margin-top:15px;'>"
        .'<p style="font-size:1.5em; line-height:1.4em;">'
        .'<b>The Bad News:</b> Your Duplicator Pro License is Invalid. <br/>'
        .'<b>The Good News:</b> You Can Get 30% Off Duplicator Pro Today! </p>'
        ."The Duplicator Pro plugin has been running for at least 30 days without a valid license.<br/>"
        .'...which means you don\'t have access to <b>security updates</b>, <i>bug fixes</i>, <b>support requests</b> or <i>new features</i>.<br/>'
        ."<p style='font-size:1.5rem'><a href='{$licensing_tab_url}'>Activate Your License Now...</a> <br/> - OR - <br/> "
        ."<a target='_blank' href='https://snapcreek.com/duplicator/pricing?discount=SUPERN_30_F4'>Purchase and Get 30% Off!*</a></p>"
        .'<p style="text-align:center; font-size:1rem"><small>*Discount appears in cart at checkout time.</small></p></div>';
    }
}