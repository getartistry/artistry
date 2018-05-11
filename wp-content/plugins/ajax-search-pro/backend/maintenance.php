<?php
/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

if (ASP_DEMO) $_POST = null;
?>
<style>
    #wpdreams .asp_maintenance ul {
        list-style-type: disc;
        margin-bottom: 10px;
    }
    #wpdreams .asp_maintenance ul li {
        list-style-type: disc;
        margin-left: 30px;
        margin-top: 10px;
    }
</style>
<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) . 'settings/assets/options_search.css?v='.ASP_CURR_VER; ?>" />
<div id="wpdreams" class='wpdreams wrap<?php echo isset($_COOKIE['asp-accessibility']) ? ' wd-accessible' : ''; ?>'>
    <?php if (wd_asp()->updates->needsUpdate()): ?>
    <p class='infoMsgBox'>Version <strong><?php echo wd_asp()->updates->getVersionString(); ?></strong> is available.
        Download the new version from Codecanyon. <a target="_blank" href="http://wpdreams.gitbooks.io/ajax-search-pro-documentation/content/update_notes.html">How to update?</a></p>
    <?php endif; ?>
    <div class="wpdreams-box asp_maintenance" style="float: left;">
        <?php if (ASP_DEMO): ?>
            <p class="infoMsg"><strong>DEMO MODE ENABLED</strong> - Please note, that these options are read-only!</p>
        <?php endif; ?>
        <div id='asp_i_success' class="infoMsg<?php echo isset($_POST['asp_mnt_msg']) ? '' : ' hiddend'; ?>">
            <?php echo isset($_POST['asp_mnt_msg']) ? strip_tags($_POST['asp_mnt_msg']) : ''; ?>
        </div>
        <div id='asp_i_error' class="errorMsg hiddend"></div>
        <textarea id="asp_i_error_cont" class="hiddend"></textarea>

        <form name="asp_reset_form" id="asp_reset_form" action="maintenance.php" method="POST">
            <fieldset>
                <legend>Maintencance -  Reset</legend>
                <p>This option will reset all the plugin options to the defaults. Use this option if you want to keep using the plugin, but you need to reset the default options.
                <ul>
                    <li>All plugin options <strong>will</strong> reset to defaults (caching, compatibility, index table and statistics options)</li>
                    <li>The search instance options <strong>will not</strong> be changed</li>
                    <li>The database tables, contents and the files <strong>will not</strong> be deleted either.</li>
                </ul>
                </p>
                <div style="text-align: center;">
                    <?php if (ASP_DEMO): ?>
                        <input type="button" name="asp_reset" id="asp_reset" class="submit wd_button_green" value="Reset all options to defaults" disabled>
                    <?php else: ?>
                        <input type="hidden" name="asp_reset_nonce" id="asp_reset_nonce" value="<?php echo wp_create_nonce( "asp_reset_nonce" ); ?>">
                        <input type="button" name="asp_reset" id="asp_reset" class="submit wd_button_green" value="Reset all options to defaults">
                        <span class="loading-small hiddend"></span>
                    <?php endif; ?>
                </div>
            </fieldset>
        </form>
        <form name="asp_wipe_form" id="asp_wipe_form" action="maintenance.php" method="POST">
            <fieldset>
                <legend>Maintencance -  Wipe & Deactivate</legend>
                <p>This option will wipe everything related to Ajax Search Pro, as if it was never installed. Use this if you don't want to use the plugin anymore, or if you want to perform a clean installation.
                <ul>
                    <li>All plugin options <strong>will be deleted</strong></li>
                    <li>The search instances <strong>will be deleted</strong></li>
                    <li>The database tables and the files <strong>will be deleted</strong></li>
                    <li>The plugin <strong>will deactivate</strong> and redirect to the plugin manager screen after, where you can delete it or re-install it again.</li>
                </ul>
                </p>
                <div style="text-align: center;">
                    <?php if (ASP_DEMO): ?>
                        <input type="button" name="asp_wipe" id="asp_wipe" class="submit" value="Wipe all plugin data & deactivate Ajax Search Pro" disabled>
                    <?php else: ?>
                        <input type="hidden" name="asp_wipe_nonce" id="asp_wipe_nonce" value="<?php echo wp_create_nonce( "asp_wipe_nonce" ); ?>">
                        <input type="button" name="asp_wipe" id="asp_wipe" class="submit" value="Wipe all plugin data & deactivate Ajax Search Pro">
                        <span class="loading-small hiddend"></span>
                    <?php endif; ?>
                </div>
            </fieldset>
        </form>
        <form name="asp_empty_redirect"id="asp_empty_redirect" method="post" style="display: none;">
            <input type="hidden" name="asp_mnt_msg" value="">
        </form>
    </div>
    <div id="asp-options-search">
        <a class="wd-accessible-switch" href="#"><?php echo isset($_COOKIE['asp-accessibility']) ? 'DISABLE ACCESSIBILITY' : 'ENABLE ACCESSIBILITY'; ?></a>
    </div>
    <div class="clear"></div>
</div>
<?php
if (!ASP_DEMO) {
    $media_query = ASP_DEBUG == 1 ? asp_gen_rnd_str() : get_option("asp_media_query", "defn");
    wp_enqueue_script('asp-backend-maintenance', plugin_dir_url(__FILE__) . 'settings/assets/maintenance.js', array(
        'jquery'
    ), $media_query, true);
    wp_localize_script('asp-backend-maintenance', 'ASP_MNT', array(
        "admin_url" => admin_url()
    ));
}
