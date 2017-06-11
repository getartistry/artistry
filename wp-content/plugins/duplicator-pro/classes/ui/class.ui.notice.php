<?php

/**
 * Used to display notices in the WordPress Admin area
 * This class takes advatage of the 'admin_notice' action.
 *
 * Standard: PSR-2
 * @link http://www.php-fig.org/psr/psr-2
 *
 * @package DUP_PRO
 * @subpackage classes/ui
 * @copyright (c) 2017, Snapcreek LLC
 * @license	https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since 3.3.0
 *
 */
class DUP_PRO_UI_Notice
{

    /**
     * Shows a display message in the wp-admin if any researved files are found
     *
     * @return null
     */
    public static function showReservedFilesNotice()
    {

        $dpro_active = is_plugin_active('duplicator-pro/duplicator-pro.php');
        $dup_perm    = current_user_can('manage_options');
        if (!$dpro_active || !$dup_perm) return;

        //Hide free error message if Pro is active
        if (is_plugin_active('duplicator/duplicator.php')) {
            echo "<style>div#dup-global-error-reserved-files {display:none}</style>";
        }

        $screen = get_current_screen();
        if (!isset($screen)) return;

        //Hide on save permalinks to prevent user distraction
        if ($screen->id == 'options-permalink') return;

        if (DUP_PRO_Server::hasInstallFiles()) {
            $txt_messgate  = DUP_PRO_U::__('Reserved Duplicator Pro installation files have been detected in the root directory.  Please delete these installation files to avoid security issues. <br/>'
                    .'Go to: Tools > Diagnostics > Stored Data > and click the "Delete Installation Files" button');
            $on_active_tab = isset($_GET['tab']) && $_GET['tab'] == 'diagnostics' ? true : false;
            echo '<div class="error" id="dpro-global-error-reserved-files"><p>';
            if ($screen->id == 'duplicator-pro_page_duplicator-pro-tools' && $on_active_tab) {
                echo $txt_messgate;
            } else {
                $duplicator_pro_nonce = wp_create_nonce('duplicator_pro_cleanup_page');
                echo $txt_messgate;
                $diagnostics_url      = self_admin_url('admin.php?page=duplicator-pro-tools&tab=diagnostics&_wpnonce='.$duplicator_pro_nonce);

                @printf("<br/><a href='$diagnostics_url'>%s</a>", DUP_PRO_U::__('Take me there now!'));
            }
            echo "</p></div>";
        }
    }
}