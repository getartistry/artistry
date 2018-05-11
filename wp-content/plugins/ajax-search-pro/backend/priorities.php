<?php
/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

if (ASP_DEMO) $_POST = null;

$args = array(
    'public'   => true,
    '_builtin' => false
);

$output = 'names'; // names or objects, note names is the default
$operator = 'or'; // 'and' or 'or'

$post_types = array_merge(array('all'), get_post_types( $args, $output, $operator ));

$blogs = array();
if (function_exists('wp_get_sites'))
    $blogs = wp_get_sites();

wd_asp()->priority_groups = WD_ASP_Priority_Groups::getInstance();
?>
<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) . 'settings/assets/options_search.css?v='.ASP_CURR_VER; ?>" />
<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) . 'settings/assets/priorities.css?v='.ASP_CURR_VER; ?>" />
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

    <div class="wpdreams-box" style="position: relative; float:left;">
        <ul id="tabs" class='tabs'>
            <li><a tabid="1" class='current general'>Priority Groups</a></li>
            <li><a tabid="2" class='general'>Individual Priorities</a></li>
        </ul>

        <div class='tabscontent'>
            <div tabid="1">
                <fieldset>
                    <legend>Priority Groups</legend>

                    <?php include(ASP_PATH . "backend/tabs/priorities/priority_groups.php"); ?>

                </fieldset>
            </div>
            <div tabid="2">
                <fieldset>
                    <legend>Individual Priorities</legend>

                    <?php include(ASP_PATH . "backend/tabs/priorities/priorities_individual.php"); ?>

                </fieldset>
            </div>
        </div>

    </div>
    <div id="asp-options-search">
        <a class="wd-accessible-switch" href="#"><?php echo isset($_COOKIE['asp-accessibility']) ? 'DISABLE ACCESSIBILITY' : 'ENABLE ACCESSIBILITY'; ?></a>
    </div>
    <div class="clear"></div>
</div>

<?php
$media_query = ASP_DEBUG == 1 ? asp_gen_rnd_str() : get_option("asp_media_query", "defn");
wp_enqueue_script('asp-backend-priorities', plugin_dir_url(__FILE__) . 'settings/assets/priorities.js', array(
    'jquery'
), $media_query, true);
wp_localize_script('asp-backend-priorities', 'ASP_PTS', array(
    "admin_url" => admin_url(),
    "ajax_url"  => admin_url('admin-ajax.php')
));
wp_enqueue_script('asp-backend-pg-controllers', plugin_dir_url(__FILE__) . 'settings/assets/priorities/controllers.js', array(
    'jquery'
), $media_query, true);
wp_enqueue_script('asp-backend-pg-events', plugin_dir_url(__FILE__) . 'settings/assets/priorities/events.js', array(
    'jquery',
    'asp-backend-pg-controllers'
), $media_query, true);