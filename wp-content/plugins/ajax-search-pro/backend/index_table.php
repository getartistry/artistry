<?php
/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

$it_options = wd_asp()->o['asp_it_options'];
$_args = array();
foreach ($it_options as $_k => $_opt) {
    $_args[str_replace('it_', '', $_k)] = $_opt;
}
$index_obj = new asp_indexTable($_args);
$pool_sizes = asp_indexTable::suggestPoolSizes(true);

if (ASP_DEMO) {
    $_POST = null;
}

$asp_cron_data = get_option("asp_it_cron", array(
    "last_run" => "",
    "result" => array()
));
?>
<?php if ( !wd_asp()->db->exists('index', true) ): ?>
    <div id="wpdreams" class='wpdreams wrap'>
        <div class="wpdreams-box">
            <p class="errorMsg">The index table does not exist and cannot be created. Please check <a
                    href="https://wp-dreams.com/go/?to=kb-asp-missing-tables" target="_blank">this article</a> to resolve the issue.</p>
        </div>
    </div>
    <?php return; ?>
<?php endif; ?>
    <link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) . 'settings/assets/options_search.css?v='.ASP_CURR_VER; ?>" />
    <div id="wpdreams" class='wpdreams wrap<?php echo isset($_COOKIE['asp-accessibility']) ? ' wd-accessible' : ''; ?>'>
        <?php if (wd_asp()->updates->needsUpdate()): ?>
            <p class='infoMsgBox'>Version <strong><?php echo wd_asp()->updates->getVersionString(); ?></strong> is
                available.
                Download the new version from Codecanyon. <a target="_blank"
                                                             href="http://wpdreams.gitbooks.io/ajax-search-pro-documentation/content/update_notes.html">How
                    to update?</a></p>
        <?php endif; ?>

        <?php
        $_comp = wpdreamsCompatibility::Instance();
        if ($_comp->has_errors()):
            ?>
            <div class="wpdreams-box errorbox">
                <p class='errors'>Possible incompatibility! Please go to the <a
                        href="<?php echo get_admin_url() . "admin.php?page=asp_compatibility_settings"; ?>">error
                        check</a> page to see the details and solutions!</p>
            </div>
        <?php endif; ?>

        <div class="wpdreams-box" style="float:left;">

            <?php ob_start(); ?>

            <!-- TODO Relevanssi table detection -->
            <div tabid="1">
                <div class="item">
                    <?php $o = new wpdreamsYesNo("it_index_title", "Index titles?",
                        $it_options['it_index_title']
                    ); ?>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsYesNo("it_index_content", "Index content?",
                        $it_options['it_index_content']
                    ); ?>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsYesNo("it_index_excerpt", "Index excerpt?",
                        $it_options['it_index_excerpt']
                    ); ?>
                </div>
                <div class="item">
                    <?php
                    $o = new wpdreamsCustomPostTypesAll("it_post_types", "Post types to index",
                        $it_options['it_post_types']);
                    ?>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsTextarea("it_attachment_mime_types", "Attachment mime types to index",
                        $it_options['it_attachment_mime_types']
                    ); ?>
                    <p class="descMsg"><strong>Comma separated list</strong> of allowed mime types. List of <a href="https://codex.wordpress.org/Function_Reference/get_allowed_mime_types"
                                                                                                               target="_blank">default allowed mime types</a> in WordPress.</p>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsYesNo("it_index_tags", "Index post tags?",
                        $it_options['it_index_tags']
                    ); ?>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsYesNo("it_index_categories", "Index post categories?",
                        $it_options['it_index_categories']
                    ); ?>
                </div>
                <div class="item">
                    <?php
                    $o = new wpdreamsTaxonomySelect("it_index_taxonomies", "Index taxonomies", array(
                        "value" => $it_options['it_index_taxonomies'],
                        "type" => "include"
                    ));
                    ?>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsYesNo("it_index_permalinks", "Index permalinks?",
                        $it_options['it_index_permalinks']
                    ); ?>
                </div>
                <div class="item"><?php
                    $o = new wpdreamsCustomFields("it_index_customfields", "Index custom fields",
                        $it_options['it_index_customfields']
                    ); ?>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsText("it_post_statuses", "Post statuses to index",
                        $it_options['it_post_statuses']
                    ); ?>
                    <p class="descMsg">Comma separated list. WP Defaults: publish, future, draft, pending, private,
                        trash,
                        auto-draft</p>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsYesNo("it_index_author_name", "Index post author name?",
                        $it_options['it_index_author_name']
                    ); ?>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsYesNo("it_index_author_bio", "Index post author bio (description)?",
                        $it_options['it_index_author_bio']
                    ); ?>
                </div>
            </div>
            <div tabid="2">
                <div class="item"><?php
                    $o = new wpdreamsBlogselect("it_blog_ids", "Blogs to index posts from",
                        $it_options['it_blog_ids']
                    ); ?>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsTextSmall("it_limit", "Post limit per iteration",
                        $it_options['it_limit']
                    ); ?>
                    <p class="descMsg">Posts to index per ajax call. Reduce this number if the process fails. Default:
                        25</p>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsYesNo("it_use_stopwords", "Enable stop-words?",
                        $it_options['it_use_stopwords']
                    ); ?>
                    <p class="descMsg">Words from the list below (common words, stop words) will be excluded if
                        enabled.</p>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsTextarea("it_stopwords", "Stop words list",
                        $it_options['it_stopwords']
                    ); ?>
                    <p class="descMsg"><strong>Comma</strong> separated list of stop words.</p>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsTextSmall("it_min_word_length", "Min. word length",
                        $it_options['it_min_word_length']
                    ); ?>
                    <p class="descMsg">Words below this length will be ignored. Default: 2</p>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsYesNo("it_extract_iframes", "Extract IFRAME contents?",
                        $it_options['it_extract_iframes']
                    ); ?>
                    <p class="descMsg">Will try parsing IFRAME sources and extracting them. This <strong>may not work</strong> in some cases.</p>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsYesNo("it_extract_shortcodes", "Execute shortcodes?",
                        $it_options['it_extract_shortcodes']
                    ); ?>
                    <p class="descMsg">Will execute shortcodes in content as well. Great if you have lots of content
                        generated by shortcodes.</p>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsTextarea("it_exclude_shortcodes", "Remove these shortcodes",
                        $it_options['it_exclude_shortcodes']
                    ); ?>
                    <p class="descMsg"><strong>Comma</strong> separated list of shortcodes to remove. Use this to
                        exclude
                        shortcodes, which does not reflect your content appropriately.</p>
                </div>
            </div>
            <div tabid="4">
                <fieldset>
                    <legend>Pool sizes</legend>
                    <div class="errorMsg">The pool size greatly affects the search performance in bigger databases (50k+ keywords). While high pool values may give more accurate results, lower values cause much better performance.</div>
                    <div class="item">
                        <?php $o = new wpdreamsYesNo("it_pool_size_auto", "Let the plugin determine the pool size values?",
                            $it_options['it_pool_size_auto']
                        ); ?>
                        <p class="descMsg">When enabled (default), the plugin will adjust these values depending on the index table size and other factors.</p>
                    </div>
                    <div class="item it_pool_size">
                        <?php $o = new wpdreamsTextSmall("it_pool_size_one", "Pool size for keywords of one character long (recommended: <strong>".$pool_sizes['one']."</strong>)",
                            $it_options['it_pool_size_one']
                        ); ?>
                        <p class="descMsg">The maximum number in a sub-set of results pool for a search phrase (or part of the phrase) that is one character long.</p>
                    </div>
                    <div class="item it_pool_size">
                        <?php $o = new wpdreamsTextSmall("it_pool_size_two", "Pool size for keywords of two characters long (recommended: <strong>".$pool_sizes['two']."</strong>)",
                            $it_options['it_pool_size_two']
                        ); ?>
                        <p class="descMsg">The maximum number in a sub-set of results pool for a search phrase (or part of the phrase) that is one character long.</p>
                    </div>
                    <div class="item it_pool_size">
                        <?php $o = new wpdreamsTextSmall("it_pool_size_three", "Pool size for keywords of three characters long (recommended: <strong>".$pool_sizes['three']."</strong>)",
                            $it_options['it_pool_size_three']
                        ); ?>
                        <p class="descMsg">The maximum number in a sub-set of results pool for a search phrase (or part of the phrase) that is one character long.</p>
                    </div>
                    <div class="item it_pool_size">
                        <?php $o = new wpdreamsTextSmall("it_pool_size_rest", "Pool size for keywords of four and more characters long (recommended: <strong>".$pool_sizes['rest']."</strong>)",
                            $it_options['it_pool_size_rest']
                        ); ?>
                        <p class="descMsg">The maximum number in a sub-set of results pool for a search phrase (or part of the phrase) that is one character long.</p>
                    </div>
                </fieldset>
            </div>
            <div tabid="3">
                <div class="item">
                    <?php $o = new wpdreamsYesNo("it_index_on_save", "Index new posts upon creation?",
                        $it_options['it_index_on_save']
                    ); ?>
                    <p class="descMsg">When turned OFF, the posts will still be indexed only upon updating, or when the cron-job runs (if enabled) or when the index table is extended manually.</p>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsYesNo("it_cron_enable", "Use wp_cron() to extend the index table automatically?",
                        $it_options['it_cron_enable']
                    ); ?>
                    <p class="descMsg">Will register a cron job with wp_cron() and run it periodically.</p>
                </div>
                <div class="item">
                    <?php $o = new wpdreamsCustomSelect("it_cron_period", "Period",
                        array(
                            'selects' => array(
                                array("option" => "Hourly", "value" => "hourly"),
                                array("option" => "Twice Daily", "value" => "twicedaily"),
                                array("option" => "Daily", "value" => "daily")
                            ),
                            'value' => $it_options['it_cron_period']
                        )
                    ); ?>
                    <p class="descMsg">The periodicity of execution. wp_cron() only accepts these values.</p>
                </div>
                <div class="item">
                    <fieldset class="asp-last-execution-info">
                        <legend>Last execution info</legend>
                        <ul style="float:right;text-align:left;width:50%;">
                            <li><b>Last exeuction
                                    time: </b><?php echo $asp_cron_data['last_run'] != "" ? date("H:i:s, F j. Y", $asp_cron_data['last_run']) : "No information."; ?>
                            </li>
                            <li><b>Current system time: </b><?php echo date("H:i:s, F j. Y", time()); ?></li>
                            <li><b>Posts
                                    indexed: </b><?php echo w_isset_def($asp_cron_data['result']['postsIndexedNow'], "No information."); ?>
                            </li>
                            <li><b>Keywords
                                    found: </b><?php echo w_isset_def($asp_cron_data['result']['keywordsFound'], "No information."); ?>
                            </li>
                        </ul>
                    </fieldset>
                </div>
            </div>
            <?php $_r = ob_get_clean(); ?>

            <?php
            $updated = false;
            if (isset($_POST) && isset($_POST['submit_asp_index_options']) && (wpdreamsType::getErrorNum() == 0)) {
                $values = array(
                    'it_index_title' => $_POST['it_index_title'],
                    'it_index_content' => $_POST['it_index_content'],
                    'it_index_excerpt' => $_POST['it_index_excerpt'],
                    'it_post_types' => $_POST['it_post_types'],
                    'it_index_tags' => $_POST['it_index_tags'],
                    'it_index_categories' => $_POST['it_index_categories'],
                    'it_index_taxonomies' => $_POST['it_index_taxonomies'],
                    'it_attachment_mime_types' => $_POST['it_attachment_mime_types'],
                    'it_index_customfields' => $_POST['it_index_customfields'],
                    'it_post_statuses' => $_POST['it_post_statuses'],
                    'it_index_author_name' => $_POST['it_index_author_name'],
                    'it_index_author_bio' => $_POST['it_index_author_bio'],
                    'it_blog_ids' => $_POST['it_blog_ids'],
                    'it_limit' => $_POST['it_limit'],
                    'it_use_stopwords' => $_POST['it_use_stopwords'],
                    'it_stopwords' => $_POST['it_stopwords'],
                    'it_min_word_length' => $_POST['it_min_word_length'],
                    'it_extract_iframes' => $_POST['it_extract_iframes'],
                    'it_extract_shortcodes' => $_POST['it_extract_shortcodes'],
                    'it_exclude_shortcodes' => $_POST['it_exclude_shortcodes'],
                    'it_index_on_save' => $_POST['it_index_on_save'],
                    'it_cron_enable' => $_POST['it_cron_enable'],
                    'it_cron_period' => $_POST['it_cron_period'],
                    'it_pool_size_auto' => $_POST['it_pool_size_auto'],
                    'it_pool_size_one' => $_POST['it_pool_size_one'],
                    'it_pool_size_two' => $_POST['it_pool_size_two'],
                    'it_pool_size_three' => $_POST['it_pool_size_three'],
                    'it_pool_size_rest' => $_POST['it_pool_size_rest']
                );
                update_option('asp_it_options', $values);
                asp_parse_options();
                $updated = true;
                update_option("asp_recreate_index", 1);
            }
            ?>
            <div class='wpdreams-slider'>

                <?php if ($updated): ?>
                    <div class='errorMsg asp-notice-ri'>The options have changed, don't forget to re-create the index table with the <b>Create new index</b> button!</div>
                <?php endif; ?>

                <?php if (ASP_DEMO): ?>
                    <p class="infoMsg">DEMO MODE ENABLED - Please note, that these options are read-only on the demo</p>
                <?php endif; ?>

                <form name='asp_indextable_settings' id='asp_indextable_settings' class="asp_indextable_settings"
                      method='post'>

                    <fieldset>
                        <legend>Index Table Operations</legend>
                        <div id="index_buttons" style="margin: 0 0 15px 0;">
                            <input type="button" name="asp_index_new" id="asp_index_new" class="submit wd_button_green"
                                   index_action='new' index_msg='Do you want to generate a new index table?'
                                   value="Create new index">
                            <input type="button" name="asp_index_extend" id="asp_index_extend"
                                   class="submit wd_button_blue"
                                   index_action='extend' index_msg='Do you want to extend the index table?'
                                   value="Continue existing index">
                            <input type="button" name="asp_index_delete" id="asp_index_delete" class="submit"
                                   index_action='delete' index_msg='Do you really want to empty the index table?'
                                   value="Delete the index">
                        </div>
                        <div class="wd_progress_text hiddend">Initializing, please wait. This might take a while.</div>
                        <div class="wd_progress wd_progress_75 hiddend"><span style="width:0%;"></span></div>
                        <span class="wd_progress_stop hiddend">Stop</span>

                        <div id='asp_i_success' class="infoMsg hiddend">100% - Index table successfully generated!</div>
                        <div id='asp_i_error' class="errorMsg hiddend">Something went wrong :(</div>
                        <textarea id="asp_i_error_cont" class="hiddend"></textarea>

                        <p class="descMsg">To read more about the index table, please read the <a
                                href="http://wpdreams.gitbooks.io/ajax-search-pro-documentation/content/index_table.html">documentation
                                chapter about Index table</a> usage.</p>
                        <?php if (is_multisite()): ?>
                            <p class="descMsg" style="color:#666; ">
                                Total keywords: <b
                                    id="keywords_counter"><?php echo $index_obj->getTotalKeywords(); ?></b>
                            </p>
                        <?php else: ?>
                            <p class="descMsg" style="color:#666; ">
                                Items Indexed: <b id="indexed_counter"><?php echo $index_obj->getPostsIndexed(); ?></b>
                                &nbsp;|&nbsp;Items not indexed: <b
                                    id="not_indexed_counter"><?php echo $index_obj->getPostIdsToIndexCount(); ?></b>
                                &nbsp;|&nbsp;Total keywords: <b
                                    id="keywords_counter"><?php echo $index_obj->getTotalKeywords(); ?></b>
                            </p>
                        <?php endif; ?>
                    </fieldset>

                    <fieldset id='asp_indextable_options'>
                        <div id="asp_it_disable" class="hiddend"></div>

                        <legend>Index Table options</legend>

                        <?php if ($updated): ?>
                            <div class='infoMsg'>Index table options successfuly updated!</div><?php endif; ?>

                        <ul id="tabs" class='tabs'>
                            <li><a tabid="1" class='current general'>General Options</a></li>
                            <li><a tabid="2" class='advanced'>Advanced Options</a></li>
                            <li><a tabid="3" class='advanced'>Indexing & Cron options</a></li>
                            <li><a tabid="4" class='advanced'>Performance & Accuracy</a></li>
                        </ul>
                        <div class='tabscontent'>
                            <?php print $_r; ?>
                        </div>
                        <input type='hidden' name='asp_index_table_page' value='1'/>

                        <div class="item">
                            <input name="submit_asp_index_options" type="submit" value="Save options"/>
                        </div>
                    </fieldset>
                </form>

            </div>
        </div>

        <div id="asp-options-search">
            <a class="wd-accessible-switch" href="#"><?php echo isset($_COOKIE['asp-accessibility']) ? 'DISABLE ACCESSIBILITY' : 'ENABLE ACCESSIBILITY'; ?></a>
        </div>
        <div class="clear"></div>
    </div>
<?php
$media_query = ASP_DEBUG == 1 ? asp_gen_rnd_str() : get_option("asp_media_query", "defn");
wp_enqueue_script('asp-backend-index-table', plugin_dir_url(__FILE__) . 'settings/assets/index_table.js', array(
    'jquery'
), $media_query, true);
wp_localize_script('asp-backend-index-table', 'ASP_IT', array(
    "current_blog_id" => array(get_current_blog_id())
));