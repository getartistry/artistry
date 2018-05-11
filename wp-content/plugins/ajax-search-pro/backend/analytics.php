<?php
/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

$ana_options = wd_asp()->o['asp_analytics'];
if (ASP_DEMO) $_POST = null;
?>
<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) . 'settings/assets/options_search.css?v='.ASP_CURR_VER; ?>" />
<div id="wpdreams" class='wpdreams wrap<?php echo isset($_COOKIE['asp-accessibility']) ? ' wd-accessible' : ''; ?>'>
    <?php if (wd_asp()->updates->needsUpdate()): ?>
        <p class='infoMsgBox'>Version <strong><?php echo wd_asp()->updates->getVersionString(); ?></strong> is available.
            Download the new version from Codecanyon. <a target="_blank" href="http://wpdreams.gitbooks.io/ajax-search-pro-documentation/content/update_notes.html">How to update?</a></p>
    <?php endif; ?>

    <?php
    $_comp = wpdreamsCompatibility::Instance();
    if ($_comp->has_errors()):
        ?>
        <div class="wpdreams-box errorbox">
            <p class='errors'>Possible incompatibility! Please go to the <a href="<?php echo get_admin_url()."admin.php?page=asp_compatibility_settings"; ?>">error check</a> page to see the details and solutions!</p>
        </div>
    <?php endif; ?>

    <div class="wpdreams-box" style="float:left;">
        <?php ob_start(); ?>
        <div class="item">
            <?php $o = new wpdreamsYesNo("analytics", "Enable search Google Analytics integration?", $ana_options["analytics"]); ?>
        </div>
        <div class="item">
            <?php $o = new wpdreamsText("analytics_string", "Google analytics pageview string", $ana_options["analytics_string"]); ?>
            <p class='infoMsg'>
                This is how the pageview will look like on the google analytics website. Use the {asp_term} variable to add the search term to the pageview.
            </p>
        </div>
        <div class="item">
            <input type='submit' class='submit' value='Save options'/>
        </div>
        <?php $_r = ob_get_clean(); ?>

        <?php
        $updated = false;
        if (isset($_POST) && isset($_POST['asp_analytics']) && (wpdreamsType::getErrorNum()==0)) {
            print "saving!";
            $values = array(
                "analytics" => $_POST['analytics'],
                "analytics_string" => $_POST['analytics_string']
            );
            update_option('asp_analytics', $values);
            asp_parse_options();
            $updated = true;
        }
        ?>

        <div class='wpdreams-slider'>
            <?php if (ASP_DEMO): ?>
                <p class="infoMsg">DEMO MODE ENABLED - Please note, that these options are read-only</p>
            <?php endif; ?>
            <form name='asp_analytics1' method='post'>
                <?php if($updated): ?><div class='successMsg'>Analytics options successfuly updated!</div><?php endif; ?>
                <fieldset>
                    <legend>Analytics options</legend>
                    <?php print $_r; ?>
                    <input type='hidden' name='asp_analytics' value='1' />
                </fieldset>
                <fieldset>
                    <legend>Result</legend>
                    <p class='infoMsg'>
                        After some time you should be able to see the hits on your analytics board.
                    </p>
                    <img src="http://i.imgur.com/s7BXiPV.png">
                </fieldset>
            </form>
        </div>
    </div>
    <div id="asp-options-search">
        <a class="wd-accessible-switch" href="#"><?php echo isset($_COOKIE['asp-accessibility']) ? 'DISABLE ACCESSIBILITY' : 'ENABLE ACCESSIBILITY'; ?></a>
    </div>
    <div class="clear"></div>
</div>