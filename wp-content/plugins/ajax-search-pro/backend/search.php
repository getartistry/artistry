<?php
/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

if (
    isset($_GET['asp_sid'], $_POST['reset_' . ($_GET['asp_sid'] + 0)]) &&
    isset($_POST['asp_sett_nonce'])
) {
    if ( wp_verify_nonce( $_POST['asp_sett_nonce'], 'asp_sett_nonce' ) ) {
        wd_asp()->instances->reset($_GET['asp_sid'] + 0);
        asp_generate_the_css();
        $ch = new WD_ASP_Deletecache_Handler();
        $ch->handle(false);
        $_reset_success = true;
    } else {
        $_reset_success = false;
    }
}

$params = array();
if ( ASP_DEBUG == 1 )
    $_themes = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'settings' . DIRECTORY_SEPARATOR . 'themes.json');
else
    $_themes = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'settings' . DIRECTORY_SEPARATOR . 'themes.min.json');

$search = wd_asp()->instances->get($_GET['asp_sid'] + 0);
if (empty($search)) {
    $s_id = $_GET['asp_sid'] + 0;
    ?>
    <div id="wpdreams" class='wpdreams wrap'>
        <div class="wpdreams-box">
            <h1>Woops</h1>
            <div class="errorMsg">This search instance (id=<?php echo $s_id; ?>) does not exists.</div>
        </div>
    </div>
    <?php
    return;
}
/**
 * The search data does not have unset option values as the
 * $asp_globals->instances has it already merged with default options
 */
$sd = &$search['data'];

/**
 * If safe mode is enabled because of the low max_input_vars value, then decode the params.
 */
if ( isset($_POST['asp_options_serialized']) ) {
    parse_str(base64_decode($_POST['asp_options_serialized']), $_POST);
    $_POST['submit_' . $search['id']] = 1;
}

?>
<link rel="stylesheet" href="<?php echo ASP_URL_NP . 'css/style.basic.css?v='.ASP_CURR_VER; ?>" />
<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) . 'settings/assets/options_search.css?v='.ASP_CURR_VER; ?>" />
<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) . 'settings/assets/search_instance.css?v='.ASP_CURR_VER; ?>" />

<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=470596109688127&version=v2.0";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

<div id="wpd_body_loader"><div id="wpd_loading_msg">Loading...</div></div>

<div id='preview'>
    <span>Preview</span>
    <a name='refresh' class='refresh' searchid='0' href='#'>Refresh</a>
    <a name='hide' class='maximise'>Show</a>
    <label>Background: </label><input type="text" id="bgcolorpicker" value="#ffffff"/>

    <div style="text-align: center;
        margin: 11px 0 17px;
        font-size: 12px;
        color: #aaa;">Please note, that some functions may not work in preview mode.<br>The first loading can take up to
        15 seconds!
    </div>
    <div class='big-loading hidden'></div>
    <div class="data hidden asp_preview_data"></div>
</div>

<div id="wpd_white_fixed_bg"></div>

<div id="wpd_shortcode_modal_bg" class="wpd-modal-bg"></div>
<div id="wpd_shortcode_modal" sid="<?php echo $search['id']; ?>" class="wpd-modal hiddend">
    <h3 style="flex-wrap: wrap; flex-basis: 100%; min-width: 100%;text-align: left; margin-top: 0;margin-left: 40px;">Shortcode generator</h3>
    <div class="wpd-modal-close"></div>
    <div class="sortablecontainer wpd_md_col">
        <p class="descMsg">This tool is to help you generate a Column/Row based layout for the plugin. For more info on shortcodes, <a href="http://wp-dreams.com/go/?to=yt-shortcodes" target="_blank">check this video</a> tutorial.</p>
        <ul class="ui-sortable">
            <li item="search"><b>Search box</b><br><label>Ratio: <input type="number" value="100" min="5" max="100"/>%</label><a class="deleteIcon"></a></li>
            <li item="settings" class="hiddend"><b>Settings box</b><br><label>Ratio: <input type="number" value="100" min="5" max="100"/>%</label><a class="deleteIcon"></a></li>
            <li item="results" class="hiddend"><b>Results box</b><br><label>Ratio: <input type="number" value="100" min="5" max="100"/>%</label><a class="deleteIcon"></a></li>
        </ul>
    </div>

    <div class="wpd_generated_shortcode wpd_md_col">
        <select style="max-width: 175px;">
            <option disabled selected>Pre-defined variations</option>
            <option value="0,2|50,50">Search/Results 50/50</option>
            <option value="0,1|50,50">Search/Settings 50/50</option>
            <option value="0,1,2|33,33,33">Search/Settings/Results in columns</option>
            <option value="0,1,2|100,50,50">Search/Settings/Results in 100/50/50</option>
            <option value="0,1,2|50,50,100">Search/Settings/Results in 50/50/100</option>
        </select>
        <button item="search" disabled><< Add the search box</button>
        <button item="settings"><< Add the settings box</button>
        <button item="results"><< Add the results box</button>
        <p style="margin-top: 10px;"><b>Copy</b> the shorcode generated:<br></p><textarea>[wd_asp='search' id=1]</textarea>
    </div>
</div>
<div id="wpdreams" class='wpdreams wrap<?php echo isset($_COOKIE['asp-accessibility']) ? ' wd-accessible' : ''; ?>' style="min-width: 1280px;" data-searchid="<?php echo $_GET['asp_sid']; ?>">

    <?php if (ASP_DEBUG == 1): ?>
        <p class='infoMsg'>Debug mode is on!</p>
    <?php endif; ?>

    <?php if (wd_asp()->o['asp_compatibility']['usecustomajaxhandler'] == 1): ?>
        <p class='noticeMsgBox'>NOTICE: The custom ajax handler is enabled. In case you experience issues, please
            <a href='<?php echo get_admin_url() . "admin.php?page=asp_compatibility_settings"; ?>'>turn it off.</a></p>
    <?php endif; ?>

	<?php if (wd_asp()->updates->needsUpdate()): ?>
		<p class='infoMsgBox'>Version <strong><?php echo wd_asp()->updates->getVersionString(); ?></strong> is available.
            <a href="<?php echo get_admin_url() . "admin.php?page=asp_updates_help"; ?>">Update now!</a></p>
	<?php endif; ?>

    <a class='back' href='<?php echo get_admin_url() . "admin.php?page=asp_settings"; ?>'>Back
        to the search list</a>
    <a class='statistics'
       href='<?php echo get_admin_url() . "admin.php?page=asp_statistics"; ?>'>Search
        Statistics</a>
    <a class='error' href='<?php echo get_admin_url() . "admin.php?page=asp_compatibility_settings"; ?>'>Compatibility
        checking</a>
    <a class='cache'
       href='<?php echo get_admin_url() . "admin.php?page=asp_cache_settings"; ?>'>Caching
        options</a>
    <?php ob_start(); ?>
    <div class="wpdreams-box asp_b_shortcodes">
        <?php if (defined('ASL_PATH')): ?>
            <p class="errorMsg">Warning:  <strong>Ajax Search Lite</strong> is still activated, please deactivate it to assure every PRO feature works properly.</p>
        <?php endif; ?>

        <div class="asp_b_shortcodes_menu">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="18px" height="24px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
              <polygon transform = "rotate(90 256 256)" points="142.332,104.886 197.48,50 402.5,256 197.48,462 142.332,407.113 292.727,256 "/>
            </svg>
            <span class="asp_b_shortcodes_title">Toggle shortcodes for <strong><?php echo $search['name']; ?></strong></span>
            <button id="shortcode_generator">Shortcode generator</button>
        </div>
        <fieldset>
            <legend>Simple shortcodes</legend>
            <label class="shortcode">Search shortcode:</label>
            <input type="text" class="shortcode" value="[wpdreams_ajaxsearchpro id=<?php echo $search['id']; ?>]"
                   readonly="readonly"/>
            <label class="shortcode">Search shortcode for templates:</label>
            <input type="text" class="shortcode"
                   value="&lt;?php echo do_shortcode('[wpdreams_ajaxsearchpro id=<?php echo $search['id']; ?>]'); ?&gt;"
                   readonly="readonly"/>
        </fieldset>
        <fieldset>
            <legend>Result shortcodes</legend>
            <p style='margin:19px 10px 9px;'>Shortcodes for placing the result box elsewhere. (only works if the result
                layout position is <b>block</b> - see in layout options tab)</p>
            <label class="shortcode">Result box shortcode:</label>
            <input type="text" class="shortcode"
                   value="[wpdreams_ajaxsearchpro_results id=<?php echo $search['id']; ?> element='div']"
                   readonly="readonly"/>
            <label class="shortcode">Result shortcode for templates:</label>
            <input type="text" class="shortcode"
                   value="&lt;?php echo do_shortcode('[wpdreams_ajaxsearchpro_results id=<?php echo $search['id']; ?> element=&quot;div&quot;]'); ?&gt;"
                   readonly="readonly"/>
        </fieldset>
        <fieldset>
            <legend>Settings shortcodes</legend>
            <p style='margin:19px 10px 9px;'>Shortcodes for placing the settings box elsewhere.</p>
            <label class="shortcode">Settings box shortcode:</label>
            <input type="text" class="shortcode"
                   value="[wpdreams_asp_settings id=<?php echo $search['id']; ?> element='div']"
                   readonly="readonly"/>
            <label class="shortcode">Shortcode for templates:</label>
            <input type="text" class="shortcode"
                   value="&lt;?php echo do_shortcode('[wpdreams_asp_settings id=<?php echo $search['id']; ?> element=&quot;div&quot;]'); ?&gt;"
                   readonly="readonly"/>
        </fieldset>
        <fieldset>
            <legend>Two Column Shortcode</legend>
            <p style='margin:19px 10px 9px;'>Will place a search box (left) and a result box (right) next to each other, like the one on the demo front page.</p>
            <label class="shortcode">TC shortcode:</label>
            <input type="text" class="shortcode"
                   value="[wpdreams_ajaxsearchpro_two_column id=<?php echo $search['id']; ?> search_width=50 results_width=50 invert=0 element='div']"
                   readonly="readonly"/>
            <label class="shortcode">TC shortcode for templates:</label>
            <input type="text" class="shortcode"
                   value="&lt;?php echo do_shortcode('[wpdreams_ajaxsearchpro_two_column id=<?php echo $search['id']; ?> search_width=50 results_width=50 invert=0 element=&quot;div&quot;]'); ?&gt;"
                   readonly="readonly"/>
            <p style='margin:19px 10px 9px;'><strong>Extra Parameters</strong></p>
            <ul style='margin:19px 10px 9px;'>
                <li>search_width - {integer} the search bar width (in %, not px)</li>
                <li>results_width - {integer} the results box width (in %, not px)</li>
                <li>invert - {0 or 1} inverts the search and results box position from left to right</li>
            </ul>
        </fieldset>
    </div>

    <div style="width:100%; height: 1px; background:transparent; border: 0;"></div>

    <div class="wpdreams-box" style="float:left;">
        <?php if ( ini_get('max_input_vars') < 1000 ): ?>
        <form action='' style="display:none;" method='POST' name='asp_data_serialized'>
            <input type="hidden" id='asp_options_serialized' name='asp_options_serialized' value = "">
            <input type="submit"
                   id='asp_submit_serialized_<?php echo $search['id'] ?>'
                   name='asp_submit_serialized_<?php echo $search['id'] ?>'
                   style="display: none;">
        </form>
        <?php endif; ?>

        <form action='' method='POST' name='asp_data' autocomplete="off">
            <ul id="tabs" class='tabs'>
                <li><a tabid="1" class='current general'>General Options</a></li>
                <li><a tabid="2" class='multisite'>Multisite Options</a></li>
                <li><a tabid="3" class='frontend'>Frontend Search Settings</a></li>
                <li><a tabid="4" class='layout'>Layout options</a></li>
                <li><a tabid="5" class='autocomplete'>Autocomplete & Suggestions</a></li>
                <li><a tabid="6" class='theme'>Theme options</a></li>
                <li><a tabid="8" class='advanced'>Relevance options</a></li>
                <li><a tabid="7" class='advanced'>Advanced options</a></li>
            </ul>
            <div class='tabscontent'>
                <div tabid="1">
                    <fieldset>
                        <legend>Genearal Options</legend>

                        <?php include(ASP_PATH . "backend/tabs/instance/general_options.php"); ?>

                    </fieldset>
                </div>
                <div tabid="2">
                    <fieldset>
                        <legend>Multisite Options</legend>

                        <?php include(ASP_PATH . "backend/tabs/instance/multisite_options.php"); ?>

                    </fieldset>
                </div>
                <div tabid="3">
                    <fieldset>
                        <legend>Frontend Search Settings options</legend>

                        <?php include(ASP_PATH . "backend/tabs/instance/frontend_options.php"); ?>

                    </fieldset>
                </div>
                <div tabid="4">
                    <fieldset>
                        <legend>Layout Options</legend>

                        <?php include(ASP_PATH . "backend/tabs/instance/layout_options.php"); ?>

                    </fieldset>
                </div>
                <div tabid="5">
                    <fieldset>
                        <legend>Autocomplete & Suggestions</legend>

                        <?php include(ASP_PATH . "backend/tabs/instance/autocomplete_options.php"); ?>

                    </fieldset>
                </div>
                <div tabid="6">
                    <fieldset>
                        <legend>Theme Options</legend>

                        <?php include(ASP_PATH . "backend/tabs/instance/theme_options.php"); ?>

                    </fieldset>
                </div>
                <div tabid="8">
                    <fieldset>
                        <legend>Relevance Options</legend>

                        <?php include(ASP_PATH . "backend/tabs/instance/relevance_options.php"); ?>

                    </fieldset>
                </div>
                <div tabid="7">
                    <fieldset>
                        <legend>Advanced Options</legend>

                        <?php include(ASP_PATH . "backend/tabs/instance/advanced_options.php"); ?>

                    </fieldset>
                </div>
                <div tabid="loader">
                    <p>Loading...</p>
                </div>
            </div>
            <input type="hidden" name="sett_tabid" id="sett_tabid" value="1" />
            <input type="hidden" name="asp_sett_nonce" id="asp_sett_nonce" value="<?php echo wp_create_nonce( "asp_sett_nonce" ); ?>">
        </form>
    </div>

    <div id="asp-options-search">
        <a class="wd-accessible-switch" href="#"><?php echo isset($_COOKIE['asp-accessibility']) ? 'DISABLE ACCESSIBILITY' : 'ENABLE ACCESSIBILITY'; ?></a><br>
        <label>Can't find an option?</label>
        <input type="text" value="" id="asp-os-input" placeholder="Search in options">
        <div id="asp-os-results"></div>
        <div class="asp-back-help">
            <label>Need help?</label><br>
            <span>Check the <a href="<?php echo get_admin_url() . "admin.php?page=asp_updates_help"; ?>">Help & Updates</a> menu for resources.</span>
        </div>
        <div class="asp-back-social">
            <label>Show us some love &#10084;</label><br>
            <a id="asp_tw_share" class="asp_tw_share" data-text="Replace your #WordPress search bar with a powerful live search" href="https://twitter.com/ernest_marcinko">Tweet</a>
            <a id="asp_fb_share" class="asp_fb_share" href="https://www.facebook.com/wpdreams/">Share</a>
            <a class="asp_tw_share" target="_blank" href="https://twitter.com/ernest_marcinko">Follow</a>
            <a class="asp_fb_share" target="_blank" href="https://www.facebook.com/wpdreams/">Like</a>
        </div>
    </div>

    <?php $output = ob_get_clean(); ?>
    <?php
    if ( isset($_POST['submit_' . $search['id']]) ) {
        $params = wpdreams_parse_params($_POST);

        wd_asp()->instances->update($search['id'], $params);

        $style = $params;
        $id = $search['id'];

        asp_generate_the_css();

        // Clear all the cache just in case
        $ch = new WD_ASP_Deletecache_Handler();
        $ch->handle(false);

        // Do not clear cookies here, it might cause an error
        // WD_ASP_Cookies_Action::forceUnsetCookies();

        echo "<div class='successMsg'>Search settings saved!</div>";
    }
    if ( isset($_reset_success) ) {
        if ( $_reset_success ) {
            wd_asp()->instances->reset($search['id']);

            asp_generate_the_css();
            $ch = new WD_ASP_Deletecache_Handler();
            $ch->handle(false);

            // Do not clear cookies here, it might cause an error
            // WD_ASP_Cookies_Action::forceUnsetCookies();

            echo "<div class='successMsg'>Search settings were reset to defaults!</div>";
        } else {
            echo "<div class='errorMsg'><strong>ERROR:</strong> Something went wrong during the reset, please try again!</div>";
        }
    }
    echo $output;
    ?>
    <div class="clear"></div>
</div>

<?php
$media_query = ASP_DEBUG == 1 ? asp_gen_rnd_str() : get_option("asp_media_query", "defn");
// This needs to be enqueued first, so the node actions are attached, otherwise they will not work
// @TODO 4.10.5
/*wp_enqueue_script('wpd-backend-instant', plugin_dir_url(__FILE__) . 'settings/assets/instant_actions.js', array(
    'jquery'
), $media_query, true);
wp_enqueue_script('wpd-backend-instance', plugin_dir_url(__FILE__) . 'settings/assets/search_instance.js', array(
    'wpd-backend-instant'
), $media_query, true);
*/
// TODO 4.10.5 remove this, and use the one above
wp_enqueue_script('wpd-backend-instance', plugin_dir_url(__FILE__) . 'settings/assets/search_instance.js', array(
    'jquery'
), $media_query, true);
wp_enqueue_script('wpd-backend-options-search', plugin_dir_url(__FILE__) . 'settings/assets/option_search.js', array(
    'jquery'
), $media_query, true);