<?php
function curation_suite_display_settings()
{
    if (!class_exists('ybi_product')) {
        wp_die('<strong>Curation Suite requires the latest version of the You Brand, Inc. Plugin v1.5</strong><br /><br />
		<a href="https://members.youbrandinc.com/dashboard/curation-suite/" target="_blank">Click here to download</a> or 
		go back to the WordPress <a href="' . get_admin_url(null, 'plugins.php') . '">Plugins page</a>');
    }
    $CurationSuiteProduct = new ybi_product('Curation Suite');
    if (do_validate_license($CurationSuiteProduct) !== true)
        die(header('Location: ' . self_admin_url('admin.php?page=youbrandinc-license')));


    $showCurationLink = true;
    $curate_this_url = admin_url('curate-action.php');
    // set the default width and height, used in form and when called in the function below.
    $curate_this_width = "1250";
    $curate_this_height = "820";
    function get_curation_link($curate_this_width, $curate_this_height, $isQuickAdd)
    {
        //changed this line for 404 errors
        //u=f+'?u='+e(l.href)+'&t='+e(d.title)+'&s='+e(s)+'&v=4';
        $quickAddText = '';
        if ($isQuickAdd)
            $quickAddText = '&quickAdd=yes';

        if ($isQuickAdd) {
            // the shortcuts point to either the file in the wp-admin folder (if it can be copied), if not then point to the plugins directory
            if (get_option('ybi_cu_use_plugin_files') == 'yes')
                $plugin_file = plugins_url() . '/curation-suite/admin-files/curate-action.php?homepath=' . get_home_path();
            else
                $plugin_file = admin_url('curate-action.php?l=1');


            $link = "javascript:
				var d=document,
				w=window,
				e=w.getSelection,
				k=d.getSelection,
				x=d.selection,
				s=(e?e():(k)?k():(x?x.createRange().text:0)),
				f='" . $plugin_file . "',
				l=d.location,
				e=encodeURIComponent,
				u=f+'&u='+e(l.href.replace(/\//g,'\\\/'))+'&t='+e(d.title)+'&s='+e(s)+'&v=4" . $quickAddText . "';
				a=function(){if(!w.open(u,'t','toolbar=0,resizable=1,scrollbars=1,status=1,width=" . $curate_this_width . ",height=" . $curate_this_height . "'))l.href=u;};
				if (/Firefox/.test(navigator.userAgent)) setTimeout(a, 0); else a();
				void(0)";
        } else {
            if (get_option('ybi_cu_use_plugin_files') == 'yes')
                $plugin_file = plugins_url() . '/curation-suite/admin-files/curate-action.php?homepath=' . get_home_path();
            else
                $plugin_file = admin_url('curate-action.php?l=1');

            $link = "javascript:
				var d=document,
				w=window,
				e=w.getSelection,
				k=d.getSelection,
				x=d.selection,
				s=(e?e():(k)?k():(x?x.createRange().text:0)),
				f='" . $plugin_file . "',
				l=d.location,
				e=encodeURIComponent,
				u=f+'&u='+e(l.href.replace(/\//g,'\\\/'))+'&t='+e(d.title)+'&s='+e(s)+'&v=4" . $quickAddText . "';
				a=function(){if(!w.open(u,'_blank',''))l.href=u;};
				if (/Firefox/.test(navigator.userAgent)) setTimeout(a, 0); else a();
				void(0)";
        }
        $link = str_replace(array("\r", "\n", "\t"), '', $link);
        return apply_filters('curation_link', $link);

        //javascript:var d=document,w=window,e=w.getSelection,k=d.getSelection,x=d.selection,s=(e?e():(k)?k():(x?x.createRange().text:0)),f='http://localhost/plugin_dev/wp-admin/curate-this.php',l=d.location,e=encodeURIComponent,u=f+'?u='+e(l.href.replace(/\//g,'\\/'))+'&t='+e(d.title)+'&s='+e(s)+'&v=4';a=function(){if(!w.open(u,'t','toolbar=0,resizable=1,scrollbars=1,status=1,width=1100,height=720'))l.href=u;};if (/Firefox/.test(navigator.userAgent)) setTimeout(a, 0); else a();void(0)" oncontextmenu="if(window.navigator.userAgent.indexOf('WebKit')!=-1||window.navigator.userAgent.indexOf('MSIE')!=-1)jQuery('.pressthis-code').show().find('textarea').focus().select();return false;
    }

    ?>
    <div class="wrap">
        <div style="clear: both; margin: 0 auto; overflow: auto;">
            <img src="<?php echo plugins_url(); ?>/curation-suite/i/curation-suite-icon-20x25.png" style="float: left;margin: 12px 5px 0 0;"/>
            <h2>Curation Suite Options</h2>
            <?php settings_errors(); ?>
            <?php
            $copyCurationFiles = isset($_GET['copyCurationFiles']) ? $_GET['copyCurationFiles'] : '';
            if ($copyCurationFiles == 'yes') {
                if (copy_worker_files_wp_admin())
                    echo 'Files copied.';
                else
                    echo 'Problem with file copy';
            }
            $manualFileInstall = isset($_GET['manualFileInstall']) ? $_GET['manualFileInstall'] : '';
            if ($manualFileInstall == 'yes') {
                update_option('curation_suite_parse_page_version_copied', CURATION_SUITE_VERSION);
                echo 'Installation complete.';
            }

            ?>
        </div>

        <div class="curation_suite_admin_left">
            <form method="post" action="options.php">
                <?php
                // only show options to admin users
                // if (current_user_can( 'edit_users' )):
                submit_button();
                settings_fields('curation_suite_plugin_options');
                do_settings_sections(__FILE__);
                submit_button();
                submit_button();
                //endif;
                ?>

            </form>
        </div>
        <div class="curation_suite_admin_right">
            <div id="curation_links_wrapper">


                <p class="support_request_w"><a href="<?php echo YBI_SUPPORT_URL; ?>" target="_blank" class="green_button green_button_support"><i class="fa fa-users fa-lg"></i>&nbsp;&nbsp;Contact Support Team</a></p>

                <hr/>
                <h2>CurateThis&trade; and AddLink Bookmarklets</h2>
                <p>Mouse over and drag each bookmarklet to your address bar (<em>scroll down for instructions for iDevices and Android</em>):</p>
                <?php if ($showCurationLink): ?>
                <script>
                    //curate_this_url
                    jQuery(document).ready(function ($) {
                        $("#setCurateThisWindowSize").click(function (event) {
                            var curateThisLink = jQuery('#curate_bookmarklet_link').attr('href');
                            var curateThisWidth = $("#curateThisWidth").val()
                            var curateThisHeight = $("#curateThisHeight").val()
                            curateThisLink = curateThisLink.replace(/width=(.+?),/, 'width=' + curateThisWidth + ',');
                            curateThisLink = curateThisLink.replace(/height=(.+?)'/, 'height=' + curateThisHeight + '\'');
                            jQuery('#curate_bookmarklet_link').attr('href', curateThisLink);
                        });
                        $("#site_bookmark_text_set_link").click(function (event) {

                            var the_user_text = $("#site_bookmark_text").val()
                            jQuery('.curate_bookmarklet').html('CurateThis ' + the_user_text);
                            jQuery('.quicklink_bookmarklet').html('AddLink ' + the_user_text);
                        });


                    });
                </script>
                <h3 class="heading" style="font-size: .9em;">Add Custom Text to Bookmarklets Name</h3>
                <label>Custom Text:</label>
                <input type="text" name="site_bookmark_text" value="<?php echo get_bloginfo('name'); ?>" size="20" id="site_bookmark_text">
                <a href="javascript:;" id="site_bookmark_text_set_link"><i class="fa fa-pencil-square-o"></i> Set Text</a>


                <?php
                $curate_this_width = "500";
                $curate_this_height = "300";
                ?>
                <div style="clear:both;">
                    <!--<img src="<?php echo plugins_url(); ?>/curation-suite/i/bookmarklet-background.png" />-->
                    <p class="pressthis-bookmarklet-wrapper">
                        <a onclick="return false;" oncontextmenu="if(window.navigator.userAgent.indexOf('WebKit')!=-1||window.navigator.userAgent.indexOf('MSIE')!=-1)jQuery('.pressthis-code').show().find('textarea').focus().select();return false;"
                           href="<?php echo htmlspecialchars(get_curation_link($curate_this_width, $curate_this_height, true)); ?>" id="link_bookmarklet" class="pressthis-bookmarklet"><span class="quicklink_bookmarklet">
            <?php _e('AddLink') ?></span></a></p>
                </div>

                <?php
                $curate_this_width = "1250";
                $curate_this_height = "820";
                ?>

                <p class="pressthis-bookmarklet-wrapper">
                    <a onclick="return false;" oncontextmenu="if(window.navigator.userAgent.indexOf('WebKit')!=-1||window.navigator.userAgent.indexOf('MSIE')!=-1)jQuery('.pressthis-code').show().find('textarea').focus().select();return false;"
                       href="<?php echo htmlspecialchars(get_curation_link($curate_this_width, $curate_this_height, false)); ?>" id="curate_bookmarklet_link" class="pressthis-bookmarklet"><span class="curate_bookmarklet" style="margin-left: 10px;">
            <?php _e('CurateThis') ?></span></a></p>
                <!--<div style="clear: both; margin: 0 auto; overflow:auto;">
            <h3 class="heading" style="font-size: .9em;">Set Size of Curation Popup Window</h3>
            <label>Width:</label>
            <input type="text" name="width" value="<?php echo $curate_this_width; ?>" size="6" id="curateThisWidth">
            <label>Height:</label>
            <input type="text" name="height" value="<?php echo $curate_this_height; ?>" size="6" id="curateThisHeight">
            <a href="javascript:;" id="setCurateThisWindowSize"><i class="fa fa-cog fa-lg"></i> Set Size</a>
            <p>To change the size of the CurateThis&trade; popup window <strong>enter the pixel sizes (numbers only)</strong> above. <strong>Click <em>Set Size</em></strong> and drag Curate to your bookmarks bar.</p>
            <p>Please note this only effects the size of the CurateThis&trade; bookmarklet. The AddLinks bookmarklet will open in a smaller window and close almost immediately.</p>
          </div>-->

                <br/>
                <div id="bookmarks_backup_wrapper">
                    <hr/>
                    <h4>About the Above Bookmarklets:</h4>
                    <p>Above are the two bookmarklets bundled with Curation Suite. These bookmarlets allow you to easily curate content or add a link to your Link Buckets.</p>
                    <ul>
                        <li><strong>AddLink</strong> - When clicking on this bookmarklet the webpage your on will be added to the "Quick Add Links" links bucket.</li>
                        <li><strong>CurateThis&trade;</strong> - When clicking on this bookmarklet you'll be taken to the Curate Action page allowing you to save a link to a custom link bucket, curate a new post, or add the curation to an existing published or draft post.</li>
                    </ul>
                    <p>You can also easily customize the bookmarks text by changing it below and clicking on <em>Set Text</em></p>
                    <p><a href="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>&manualFileInstall=yes">Click Here to Confirm Manual Install</a> |
                        <a href="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>&copyCurationFiles=yes">Manual Curation Files Copy</a></p>
                    <h2>Hidden Bookmarks Bar and Mobile Devices</h2>
                    <p><em>Scroll down for iDevices and Android</em></p>
                    <p>If your bookmarks toolbar is hidden or your browser does not allow you to drag and drop the link then:</p>
                    <ol>
                        <li>Highlight the Bookmark code in the box below then Ctrl-c/Command-c to copy the code.</li>
                        <li>Open your Bookmarks/Favorites manager and create a new bookmark/favorite.</li>
                        <li>Edit the name to each individual bookmark (CurateThis or AddLink) and save.</li>
                        <li>Click Manage/Organize Bookmarks/Favorites and edit the Bookmark Name you just created.</li>
                        <li>Paste the code into the URL/Location/Address field using Ctrl-v/Command-v.</li>
                        <li>Save the entry</li>
                    </ol>
                    <h3>AddLink Bookmarklet Code:</h3>
                    <textarea style="height: 165px; width: 98%;"><?php echo htmlspecialchars(get_curation_link($curate_this_width, $curate_this_height, true)); ?></textarea>
                    <h3>CurateThis&trade; Bookmarklet Code:</h3>
                    <textarea style="height: 165px; width: 98%;"><?php echo htmlspecialchars(get_curation_link($curate_this_width, $curate_this_height, false)); ?></textarea>
                    <h3>iPhone or iPad Instructions</h3>
                    <ol>
                        <li>Touch the code box above once (keyboard appears) then touch and hold until the magnifier appears and choose Select All then Copy.</li>
                        <li>Add a Bookmark and set the title to (CurateThis or AddLink) then save.</li>
                        <li>Now touch the bookmarks option again and choose Edit bookmarks from the top right and select the bookmark you just created.</li>
                        <li>Touch the location box then the x and remove the old location.</li>
                        <li>Next, touch and paste your previous copied text into the bookmark.</li>
                        <li>Finally, Press the Bookmarks button at the top to finish editing and then touch done in the upper right.</li>
                    </ol>
                    <h3>Android Phone/Tablet Instructions</h3>
                    <ol>
                        <li>Touch the code box above until the Edit Text menu appears, choose Copy All.</li>
                        <li>Touch the menu and choose Add Bookmark.</li>
                        <li>Edit the title to (CurateThis or AddLink) then touch the Location box until the Edit Text menu appears.</li>
                        <li>Choose Paste then Done to save the bookmark</li>
                    </ol>

                </div>

            </div><!--curation_links_wrapper-->
            <?php endif; ?>
        </div>

    </div>

    <?php
} //function curation_suite_display_settings()

function curation_suite_init_register_settings()
{
    // only show options to admin users
    //if (current_user_can( 'edit_posts' ))
    //{

    // This function registers our options to be updated.
    add_thickbox();
    register_setting('curation_suite_plugin_options', 'curation_suite_data', 'curation_suite_validate_data');

    //------------------------ Start Section 1 -------------------------//
    add_settings_section('curation_suite_settings_top', // Unique ID
        'Curation Suite Customization Settings', // Name for this section
        'curation_suite_top_section', // Function to call
        __FILE__ // Page
    );

    add_settings_field('curation_suite_listening_platform',// Unique ID
        'Listening Engine', // Name for this field
        'curation_suite_listening_platform_field', //Function to call
        __FILE__, // Page
        'curation_suite_settings_top' // Section to belong to
    );

    function curation_suite_listening_platform_field()
    {
        $options = get_option('curation_suite_data');
        $value = 0;
        if (is_array($options) && array_key_exists('curation_suite_listening_platform', $options))
            $value = $options['curation_suite_listening_platform'];
        //echo '<label><input type="checkbox" id="curation_suite_listening_platform_input" name="curation_suite_data[curation_suite_listening_platform]" value="1"' . checked( 1, $options['curation_suite_listening_platform'], false ) . '/> Turn on Listening Engine</label>';
        //
        echo '<div class="on_off_switch"><input type="checkbox" name="curation_suite_data[curation_suite_listening_platform]" class="on_off_switch-checkbox" id="curation_suite_data[curation_suite_listening_platform]" style="display:none;" ' . checked("on", $value, false) . '>
            <label class="on_off_switch-label" for="curation_suite_data[curation_suite_listening_platform]">
                <div class="on_off_switch-switch"></div>
                <div class="on_off_switch-inner"></div>
            </label></div>';
    }

    add_settings_field('curation_suite_default_sidebar_width',// Unique ID
        'Sidebar Size <span class="explanation">This will set the size of the sidebar relative to your screen. For more space select a higher percentage. Also keep in mind this will mean it will cover more of your post box area.</span>', // Name for this field
        'curation_suite_default_sidebar_width_field', //Function to call
        __FILE__, // Page
        'curation_suite_settings_top' // Section to belong to
    );

    function curation_suite_default_sidebar_width_field()
    {
        $name = 'curation_suite_default_sidebar_width';
        $options = get_option('curation_suite_data');
        $html = '<select id="'.$name.'" name="curation_suite_data['.$name.']">';
        $valuesArr = array(
            "641" => "Fixed - 641px",
            "40" => "40%",
            "45" => "45%",
            "50" => "50% (default)",
            "55" => "55%",
            "60" => "60%",
            "65" => "65%",
            "70" => "70%",
        );

        foreach ($valuesArr as $key => $value) {
            $html .= '<option value="' . $key . '"' . selected($options[$name], $key, false) . '>' . $value . '</option>';
        }
        $html .= '</select>';

        echo $html;

    }

    add_settings_field('curation_suite_default_curatethis_action',// Unique ID
        'CurateThis Default Behavior <span class="explanation">'.cs_get_thickbox_link_video('0iM5DJSAMdo','p','','CurateThis Default Behavior').'This will set the default way the CurateThis shortcut works. 
        By default it will open up the Curation Suite Action Screen allowing you to curate to a new, existing or draft post, a custom post type, or new page</span>', // Name for this field
        'curation_suite_default_curatethis_action_field', //Function to call
        __FILE__, // Page
        'curation_suite_settings_top' // Section to belong to
    );

    function curation_suite_default_curatethis_action_field()
    {
        $name = 'curation_suite_default_curatethis_action';
        $options = get_option('curation_suite_data');
        $html = '<select id="'.$name.'" name="curation_suite_data['.$name.']">';
        $valuesArr = array(
            "show_curate_action" => "Show Curate Action Screen",
            "new_post" => "Create New Post",
            "new_page" => "Create New Page",
        );
        $option_value = 'show_curate_action';
        if(is_array($options) && array_key_exists($name, $options)) {
            $option_value = $options[$name];
        }
        foreach ($valuesArr as $key => $value) {
            $html .= '<option value="' . $key . '"' . selected($option_value, $key, false) . '>' . $value . '</option>';
        }
        $html .= '</select>';

        echo $html;

    }

    add_settings_field('curation_suite_default_image_size',// Unique ID
        'Default Image Size', // Name for this field
        'curation_suite_default_image_size_field', //Function to call
        __FILE__, // Page
        'curation_suite_settings_top' // Section to belong to
    );
    function curation_suite_default_image_size_field()
    {
        $options = get_option('curation_suite_data');
        echo "<input id='curation_suite_default_image_size_input' name='curation_suite_data[curation_suite_default_image_size]' type='text' value='" . $options['curation_suite_default_image_size'] . "' />";
    }

    add_settings_field('curation_suite_custom_image_sizes',// Unique ID
        'Custom Image Shortcut Sizes<span class="explanation">Enter shortcut text block for your links. Seperate each option with , (comma).</span>', // Name for this field
        'curation_suite_custom_image_sizes_field', //Function to call
        __FILE__, // Page
        'curation_suite_settings_top' // Section to belong to
    );
    function curation_suite_custom_image_sizes_field()
    {
        $name = 'curation_suite_custom_image_sizes';
        $options = get_option('curation_suite_data');
        $value = '';
        if (is_array($options) && array_key_exists($name, $options))
            $value = $options[$name];

        echo "<input id='curation_suite_custom_image_sizes' name='curation_suite_data[curation_suite_custom_image_sizes]' type='text' value='" . $value . "' />";
    }

    add_settings_field('curation_suite_link_images_default',// Unique ID
        'Image Link Default<span class="explanation">'.cs_get_thickbox_link_video('U33nj3WxCxc','p','','Image Link Default Tutorial').'Select to have the image be linked by default</span>', // Name for this field
        'curation_suite_link_images_default_field', //Function to call
        __FILE__, // Page
        'curation_suite_settings_top' // Section to belong to
    );

    function curation_suite_link_images_default_field()
    {
        $name = 'curation_suite_link_images_default';
        $options = get_option('curation_suite_data');
        $value = 0;
        if (is_array($options) && array_key_exists($name, $options))
            $value = $options[$name];

        echo '<label><input type="checkbox" id="'.$name.'" name="curation_suite_data['.$name.']" value="1"' . checked(1, $value, false) . '/> 
   Select to <strong>link images</strong> when curating</label>
   <span class="explanation">Note: this will only work when using one of the Add to Post buttons not when adding an individual image or a featured image. If you turn this on you will have 2 links to the story you are curating. One link will be your attribution link and one link on the image.</span>';

    }

    add_settings_field('curation_suite_custom_link_text',// Unique ID
        'Custom Shortcut Link Text<span class="explanation">Enter shortcut text block for your links. Seperate each option with | character.<br /><br /><em>e.x. read the rest of the post...|option 2|option 3</em></span>', // Name for this field
        'curation_suite_custom_link_text_field', //Function to call
        __FILE__, // Page
        'curation_suite_settings_top' // Section to belong to
    );

    function curation_suite_custom_link_text_field()
    {
        $options = get_option('curation_suite_data');
        echo "<textarea id='curation_suite_custom_link_text_input' name='curation_suite_data[curation_suite_custom_link_text]' type='textarea'>" . $options['curation_suite_custom_link_text'] . "</textarea>";
    }

    add_settings_field('curation_suite_headline_wrap_default',// Unique ID
        'Headline Default Wrap', // Name for this field
        'curation_suite_headline_wrap_default_field', //Function to call
        __FILE__, // Page
        'curation_suite_settings_top' // Section to belong to
    );

    function curation_suite_headline_wrap_default_field()
    {

        $options = get_option('curation_suite_data');
        $html = '<select id="curation_suite_headline_wrap_default" name="curation_suite_data[curation_suite_headline_wrap_default]">';
        $valuesArr = array(
            "strong" => "Strong Tag",
            "h1" => "H1",
            "h2" => "H2",
            "h3" => "H3",
            "h4" => "H4",
            "h5" => "H5",
            "h6" => "H6",
        );

        foreach ($valuesArr as $key => $value) {
            $html .= '<option value="' . $key . '"' . selected($options['curation_suite_headline_wrap_default'], $key, false) . '>' . $value . '</option>';
        }
        $html .= '</select>';
        echo $html;
    }

    add_settings_field('curation_suite_link_attribution_location_default',// Unique ID
        'Link Attribution Default', // Name for this field
        'curation_suite_link_attribution_location_default_field', //Function to call
        __FILE__, // Page
        'curation_suite_settings_top' // Section to belong to
    );
    function curation_suite_link_attribution_location_default_field()
    {
        $name = 'curation_suite_link_attribution_location_default';
        $options = get_option('curation_suite_data');
        $saved_value = 'link_after';
        if (is_array($options) && array_key_exists($name, $options))
            $saved_value = $options[$name];

        $html = '<select id="curation_suite_link_attribution_location_default" name="curation_suite_data[curation_suite_link_attribution_location_default]">';
        $valuesArr = array(
            "link_before" => "Link Before",
            "link_after" => "Link After",
            "link_headline" => "Headline Link",
            "link_above" => "Link Above",
        );

        foreach ($valuesArr as $key => $value) {
            $html .= '<option value="' . $key . '"' . selected($saved_value, $key, false) . '>' . $value . '</option>';
        }
        $html .= '</select>';

        echo $html;

    }

    add_settings_field('curation_suite_blockquote_default',// Unique ID
        'Blockquote Default Setting', // Name for this field
        'curation_suite_blockquote_default_field', //Function to call
        __FILE__, // Page
        'curation_suite_settings_top' // Section to belong to
    );
    function curation_suite_blockquote_default_field()
    {
        $options = get_option('curation_suite_data');
        $value = 0;
        if (is_array($options) && array_key_exists('curation_suite_blockquote_default', $options))
            $value = $options['curation_suite_blockquote_default'];

        echo '<label><input type="checkbox" id="curation_suite_blockquote_default_input" name="curation_suite_data[curation_suite_blockquote_default]" value="1"' . checked(1, $value, false) . '/> 
   Select to turn <strong>Blockquote On</strong> as default value</label>
   <span class="explanation">This will be the default value but you can change this on a curation by curation basis by clicking the Blockquote button.</span>';
    }

    add_settings_field('curation_suite_headline_default',// Unique ID
        'Headline Default Setting', // Name for this field
        'curation_suite_headline_default_field', //Function to call
        __FILE__, // Page
        'curation_suite_settings_top' // Section to belong to
    );
    function curation_suite_headline_default_field()
    {
        $options = get_option('curation_suite_data');
        $value = 0;
        if (is_array($options) && array_key_exists('curation_suite_headline_default', $options))
            $value = $options['curation_suite_headline_default'];
        echo '<label><input type="checkbox" id="curation_suite_headline_default_input" name="curation_suite_data[curation_suite_headline_default]" value="1"' . checked(1, $value, false) . '/> 
   Select to turn <strong>Headline On</strong> as default value</label>
   <span class="explanation">This will be the default value but you can change this on a curation by curation basis by clicking the Headline button.</span>';
    }

    add_settings_field('curation_suite_direct_share_default',// Unique ID
        'Direct Share Default', // Name for this field
        'curation_suite_direct_share_field', //Function to call
        __FILE__, // Page
        'curation_suite_settings_top' // Section to belong to
    );
    function curation_suite_direct_share_field()
    {
        $options = get_option('curation_suite_data');
        $value = 0;
        if (is_array($options) && array_key_exists('curation_suite_direct_share_default', $options))
            $value = $options['curation_suite_direct_share_default'];
        echo '<label><input type="checkbox" id="curation_suite_auto_summary_input" name="curation_suite_data[curation_suite_direct_share_default]" value="1"' . checked(1, $value, false) . '/> Select to always turn on direct share icons in content on demand searches.</label>';
    }

    add_settings_field('curation_suite_auto_summary',// Unique ID
        'Auto Add Summary', // Name for this field
        'curation_suite_auto_summary_field', //Function to call
        __FILE__, // Page
        'curation_suite_settings_top' // Section to belong to
    );

    function curation_suite_auto_summary_field()
    {
        $options = get_option('curation_suite_data');
        $value = 0;
        if (is_array($options) && array_key_exists('curation_suite_auto_summary', $options))
            $value = $options['curation_suite_auto_summary'];
        echo '<label><input type="checkbox" id="curation_suite_auto_summary_input" name="curation_suite_data[curation_suite_auto_summary]" value="1"' . checked(1, $value, false) . '/> Select to automatically load summary in staging</label>';
    }

    add_settings_field('curation_suite_no_follow',// Unique ID
        'Add No Follow to Links', // Name for this field
        'curation_suite_no_follow_field', //Function to call
        __FILE__, // Page
        'curation_suite_settings_top' // Section to belong to
    );

    function curation_suite_no_follow_field()
    {
        $options = get_option('curation_suite_data');
        $curation_suite_no_follow = 0;
        if (is_array($options) && array_key_exists('curation_suite_no_follow', $options))
            $curation_suite_no_follow = $options['curation_suite_no_follow'];
        echo '<label><input type="checkbox" id="curation_suite_no_follow_input" name="curation_suite_data[curation_suite_no_follow]" value="1"' . checked(1, $curation_suite_no_follow, false) . '/> Select to have all curated links be nofollow links</label>';
    }

    add_settings_field('curation_suite_upload_images',// Unique ID
        'Upload & Use Local Images', // Name for this field
        'curation_suite_upload_images_field', //Function to call
        __FILE__, // Page
        'curation_suite_settings_top' // Section to belong to
    );
    function curation_suite_upload_images_field()
    {
        $options = get_option('curation_suite_data');
        $curation_suite_upload_images = 0;
        if (is_array($options) && array_key_exists('curation_suite_upload_images', $options))
            $curation_suite_upload_images = $options['curation_suite_upload_images'];
        echo '<label><input type="checkbox" id="curation_suite_upload_images_input" name="curation_suite_data[curation_suite_upload_images]" value="1"' . checked(1, $curation_suite_upload_images, false) . '/> 
   Select to turn <strong>image uploads on</strong> as default value</label>
   <span class="explanation">With this option selected when you click the Add to Post Action button the image you have selected will be uploaded to your site (added to your media) and the local image will be used in the post. 
   Note: image uploads can be chosen on a curation by curation basis as well.</span>';
    }

    add_settings_field('curation_suite_image_credit_defaults',// Unique ID
        'Image Credit Defaults', // Name for this field
        'curation_suite_image_credit_defaults', //Function to call
        __FILE__, // Page
        'curation_suite_settings_top' // Section to belong to
    );

    function curation_suite_image_credit_defaults()
    {
        $options = get_option('curation_suite_data');
        $name = 'image_credit_value_one_default';
        $saved_value = 'Thumbnail';
        if (is_array($options) && array_key_exists($name, $options))
            $saved_value = $options[$name];

        $html = '<select id="image_credit_value_one_default" name="curation_suite_data[image_credit_value_one_default]">';
        $valuesArr = array(
            "Image" => "Image",
            "Thumbnail" => "Thumbnail",
            "Photo" => "Photo",
        );

        foreach ($valuesArr as $key => $value) {
            $html .= '<option value="' . $key . '"' . selected($saved_value, $key, false) . '>' . $value . '</option>';
        }
        $html .= '</select>';

        echo "<div class='image_wrap_block'><label class='text_label first'>Value 1:</label>" . $html;

        $name = 'image_credit_value_two_default';
        $saved_value = 'credit';
        if (is_array($options) && array_key_exists($name, $options))
            $saved_value = $options[$name];

        $html = '<select id="image_credit_value_two_default" name="curation_suite_data[image_credit_value_two_default]">';
        $valuesArr = array(
            "from" => "from",
            "via" => "via",
            "courtesy" => "courtesy",
            "credit" => "credit",
        );

        foreach ($valuesArr as $key => $value) {
            $html .= '<option value="' . $key . '"' . selected($saved_value, $key, false) . '>' . $value . '</option>';
        }
        $html .= '</select>';
        echo "<label class='text_label last'>Value 2:</label>" . $html;
        echo '<div class="video_explanation">Choose the default values for image credit (these can also be selected on a post by post basis in the Image Credit Module).</div></div>';
    }

    add_settings_field('curation_suite_image_credit_wrap',// Unique ID
        'Image Credit Wrap', // Name for this field
        'curation_suite_image_credit_wrap_field', //Function to call
        __FILE__, // Page
        'curation_suite_settings_top' // Section to belong to
    );

    function curation_suite_image_credit_wrap_field()
    {
        $options = get_option('curation_suite_data');
        $name = 'curation_suite_image_credit_wrap_element';
        $saved_value = 'i';
        if (is_array($options) && array_key_exists($name, $options))
            $saved_value = $options[$name];

        $html = '<select id="curation_suite_headline_default" name="curation_suite_data[curation_suite_image_credit_wrap_element]">';
        $valuesArr = array(
            "none" => "None",
            "p" => "p tag",
            "i" => "i tag",
            "strong" => "bold",
            "span" => "span",
            "div" => "div",
        );

        foreach ($valuesArr as $key => $value) {
            $html .= '<option value="' . $key . '"' . selected($saved_value, $key, false) . '>' . $value . '</option>';
        }
        $html .= '</select>';


        $name = 'curation_suite_image_credit_wrap_class';
        $saved_value = '';
        if (is_array($options) && array_key_exists($name, $options))
            $saved_value = $options[$name];

        echo "<div class='image_wrap_block'><label class='text_label first'>Wrap:</label>" . $html;
        echo "<label class='text_label last'>Class:</label><input id='curation_suite_image_credit_wrap_class_input' name='curation_suite_data[curation_suite_image_credit_wrap_class]' type='text' value='" . $saved_value . "' />";
        echo '<div class="video_explanation">Choose what element (if any) you want to wrap image attribution text. You can also assign a custom class to be attached to this element for custom styling.</div></div>';
    }

    add_settings_field('curation_suite_default_video_size',// Unique ID
        'Default Video Size<span class="explanation">This will only effect iframe videos you put in the post box.</span>', // Name for this field
        'curation_suite_default_video_size_field', //Function to call
        __FILE__, // Page
        'curation_suite_settings_top' // Section to belong to
    );

    function curation_suite_default_video_size_field()
    {
        $options = get_option('curation_suite_data');
        echo "<div class='sizing_block'><label class='text_label first'>Width:</label><input id='curation_suite_default_video_width_input' name='curation_suite_data[curation_suite_default_video_width]' type='text' value='" . $options['curation_suite_default_video_width'] . "' />";
        echo "<label class='text_label last'>Height:</label><input id='curation_suite_default_video_height_input' name='curation_suite_data[curation_suite_default_video_height]' type='text' value='" . $options['curation_suite_default_video_height'] . "' />";
        echo '<div class="video_explanation">Common video sizing: 560 x 315, 640 x 360, 853 x 480, 1280 x 720</div></div>';
    }

    add_settings_field('curation_suite_load_google_plus_script',// Unique ID
        'Google Plus Embed Fix', // Name for this field
        'curation_suite_load_google_plus_script_field', //Function to call
        __FILE__, // Page
        'curation_suite_settings_top' // Section to belong to
    );

    function curation_suite_load_google_plus_script_field()
    {
        $options = get_option('curation_suite_data');
        $value = 0;
        if (is_array($options) && array_key_exists('curation_suite_load_google_plus_script', $options))
            $value = $options['curation_suite_load_google_plus_script'];
        echo '<label><input type="checkbox" id="curation_suite_load_google_plus_script_input" name="curation_suite_data[curation_suite_load_google_plus_script]" value="1"' . checked(1, $value, false) . '/> 
   Select to load Google Plus Embed Script</label><span class="explanation">This loads the script so you can embed Google Plus Posts (if found) on your curations.
   Please note, this Google Plus script will be loaded on every page of your site regardless if there is an embedded Google+ post on that page. <a href="" target="_blank">Click here to read more about this</a>.</span>';
    }

    add_settings_field('curation_suite_user_level_control',// Unique ID
        'Select User Level', // Name for this field
        'curation_suite_user_level_control_field', //Function to call
        __FILE__, // Page
        'curation_suite_settings_top' // Section to belong to
    );

    function curation_suite_user_level_control_field()
    {
        $options = get_option('curation_suite_data');
        $name = 'curation_suite_user_level';
        $saved_value = 'edit_posts';
        if (is_array($options) && array_key_exists($name, $options))
            $saved_value = $options[$name];

        $html = '<select id="curation_suite_user_level" name="curation_suite_data[curation_suite_user_level]">';
        $valuesArr = array(
            "edit_users" => "Admin",
            "edit_others_posts" => "Editor",
            "publish_posts" => "Author",
            "edit_posts" => "Contributor",
        );

        foreach ($valuesArr as $key => $value) {
            $html .= '<option value="' . $key . '"' . selected($saved_value, $key, false) . '>' . $value . '</option>';
        }
        $html .= '</select>';


        echo $html . '<div class="video_explanation">Choose the lowest level of user that you want to have access to Curation Suite. This will effect access to Curation Suite in Post area and access to the CurateThis & AddLink shortcuts. Note: Only admins can update options.</div>';
    }

    add_settings_field('curation_suite_custom_post_type',// Unique ID
        'Select Post Types', // Name for this field
        'curation_suite_custom_post_type_field', //Function to call
        __FILE__, // Page
        'curation_suite_settings_top' // Section to belong to
    );

    function curation_suite_custom_post_type_field()
    {
        $options = get_option('curation_suite_data');
        $name = 'curation_suite_custom_post_type';
        $saved_value = '';
        if (is_array($options) && array_key_exists($name, $options))
            $saved_value = $options[$name];

        $post_types = get_post_types('', 'names');
        $ignore_post_types = array('attachment', 'revision', 'nav_menu_item', 'curation_suite_links');
        $post_type_s = '';
        foreach ($post_types as $post_type) {
            if (!in_array($post_type, $ignore_post_types)) {
                if ($post_type_s != '')
                    $post_type_s .= ', ';

                $post_type_s .= $post_type;
            }
        }
        if ($saved_value == '')
            $saved_value = 'post,page';

// echo "<input id='curation_suite_custom_post_type' name='curation_suite_data[curation_suite_custom_post_type]' type='text' value='" . $options['curation_suite_custom_post_type'] . "' />";
        echo "<textarea id='curation_suite_custom_post_type' name='curation_suite_data[curation_suite_custom_post_type]' type='textarea'>" . $saved_value . "</textarea>";

        echo '<div class="video_explanation">
<p><strong>Available Post Types:</strong></p><p style="font-style: normal;">' . $post_type_s . '</p>
<p><strong>Advanced Feature</strong>: seperate each post type name with a comma that you want Curation Suite functionality.
If adding custom post types make sure you still include both post and page if you want functionality in standard post and pages.</p></div>';

//   echo $html . $options['curation_suite_custom_post_type'] . '<div class="video_explanation">Choose the lowest level of user that you want to have access to Curation Suite. This will effect access to Curation Suite in Post area and access to the CurateThis & AddLink shortcuts. Note: Only admins can update options.</div>';
    }

    add_settings_field('curation_suite_auto_visual_switch_off',// Unique ID
        'Auto Visual Switch', // Name for this field
        'curation_suite_auto_visual_switch_off_field', //Function to call
        __FILE__, // Page
        'curation_suite_settings_top' // Section to belong to
    );

    function curation_suite_auto_visual_switch_off_field()
    {
        $options = get_option('curation_suite_data');
        $value = 0;
        if (is_array($options) && array_key_exists('curation_suite_auto_visual_switch_off', $options))
            $value = $options['curation_suite_auto_visual_switch_off'];
        echo '<label><input type="checkbox" id="curation_suite_auto_visual_switch_off" name="curation_suite_data[curation_suite_auto_visual_switch_off]" value="1"' . checked(1, $value, false) . '/> Turn off auto visual switch on post/page save or update. Note, for some themes this might have undesirable results. See Members area for more details.</label>';
    }

    add_settings_field('curation_suite_direct_curated_links',// Unique ID
        'Direct Curation Linking<span class="explanation">'.cs_get_thickbox_link_video('ja-Q2ta1_Tk','p','','Overview', '<i class="fa fa-video-camera" aria-hidden="true"></i> Overview'). cs_get_thickbox_link_video('wlddYEKsczE','p','','Direct Link Tutorial').'Important! Watch tutorial before you use this feature.</span>', // Name for this field
        'curation_suite_direct_curated_links_field', //Function to call
        __FILE__, // Page
        'curation_suite_settings_top' // Section to belong to
    );

    function curation_suite_direct_curated_links_field()
    {
        $name = 'curation_suite_direct_curated_links';
        $options = get_option('curation_suite_data');
        $value = 0;
        if (is_array($options) && array_key_exists($name, $options))
            $value = $options[$name];

        echo '<label><input type="checkbox" id="'.$name.'" name="curation_suite_data['.$name.']" value="1"' . checked(1, $value, false) . '/> 
   Select to <strong>direct link</strong> to your curations.</label>
   <span class="explanation">Select this if you want to have your curated content to link directly to the curated story. What this means is instead of going to your post on your site the visitor will get a direct link to the story opened in a new tab. Watch the tutorial video before use so you understand exactly how this works and what this feature does.</span>';

    }

    /*add_settings_field('curation_suite_sub_headlines',// Unique ID
        'Turn On Sub-Headlines (Alpha Feature)<span class="explanation">'.cs_get_thickbox_link_video('ja-Q2ta1_Tk','p','','Overview', '<i class="fa fa-video-camera" aria-hidden="true"></i> Overview'). cs_get_thickbox_link_video('wlddYEKsczE','p','','Direct Link Tutorial').'Important! Watch tutorial before you use this feature.</span>', // Name for this field
        'curation_suite_sub_headlines'.'_field', //Function to call
        __FILE__, // Page
        'curation_suite_settings_top' // Section to belong to
    );*/

    function curation_suite_sub_headlines_field()
    {
        $name = 'curation_suite_sub_headlines';
        $options = get_option('curation_suite_data');
        $value = 0;
        if (is_array($options) && array_key_exists($name, $options))
            $value = $options[$name];

        echo '<label><input type="checkbox" id="'.$name.'" name="curation_suite_data['.$name.']" value="1"' . checked(1, $value, false) . '/> 
   Select to turn <strong>Sub Headline</strong> for your posts.</label>
   ';
        $html = '<div style="padding-top: 8px;"><label>Wrap Element</label> <select id="curation_suite_sub_headline_wrap_default" name="curation_suite_data[curation_suite_sub_headline_wrap_default]">';
        $valuesArr = array(
            "strong" => "Strong Tag",
            "h1" => "H1",
            "h2" => "H2",
            "h3" => "H3",
            "h4" => "H4",
            "h5" => "H5",
            "h6" => "H6",
        );

        foreach ($valuesArr as $key => $value) {
            $html .= '<option value="' . $key . '"' . selected($options['curation_suite_sub_headline_wrap_default'], $key, false) . '>' . $value . '</option>';
        }
        $value = '';
        $name = 'curation_suite_sub_headline_color';
        if (is_array($options) && array_key_exists($name, $options))
            $value = $options[$name];
        $html .= '</select></div><div style="display: block; padding: 6px 0 0 1px;"><input type="text" value="'.$value.'" id="'.$name.'" name="curation_suite_data['.$name.']" class="cpa-color-picker" /> 
<span style="display: inline-block;">* not required</span></div>';

        $html .= '<div style="padding-top: 8px;"><label>Position</label> <select id="curation_suite_sub_headline_position" name="curation_suite_data[curation_suite_sub_headline_position]">';

        $i = 0;
        while ($i <= 10) {
            $html .= '<option value="' . $i . '"' . selected($options['curation_suite_sub_headline_position'], $i, false) . '>' . $i . '</option>';
            $i++;
        }
        $html .='</select><span class="explanation"><strong>This feature is in Alpha and might not work for your theme.</strong> If it doesn\'t work we offer no support (except for paid custom support quote).</span>';
        echo $html;
    }

    add_settings_field('cs_google_news_blog_search_language_default',// Unique ID
        'Default Google News & Blogs Search Region', // Name for this field
        'cs_google_news_blog_search_language_default_field', //Function to call
        __FILE__, // Page
        'curation_suite_settings_top' // Section to belong to
    );

    function cs_google_news_blog_search_language_default_field()
    {

        $options = get_option('curation_suite_data');
        $html = '<select id="cs_google_news_blog_search_language_default" name="curation_suite_data[cs_google_news_blog_search_language_default]">';
        $valuesArr = array(
            'es_ar' => 'Argentina (es_ar)',
            'au' => 'Australia (au)',
            'nl_be' => 'Belgie (nl_be)',
            'fr_be' => 'Belgigue (fr_be)',
            'en_bw' => 'Botswana (en_bw)',
            'pt-BR_br' => 'Brasil (pt-BR_br)',
            'ca' => 'Canada English (ca)',
            'fr_ca' => 'Canada French (fr_ca)',
            'cs_cz' => 'Česká republika (cs_cz)',
            'es_cl' => 'Chile (es_cl)',
            'es_co' => 'Colombia (es_co)',
            'es_cu' => 'Cuba (es_cu)',
            'de' => 'Deutschland (de)',
            'es_us' => 'Estados Unidos (es_us)',
            'en_et' => 'Ethopia (en_et)',
            'fr' => 'France (fr)',
            'en_gh' => 'Ghana (en_gh)',
            'in' => 'India (in)',
            'hi_in' => 'తెలుగు (India) (hi_in)',
            'ta_in' => 'TA India (ta_in)',
            'te_in' => 'TE India (ta_in)',
            'id_id' => 'Indonesia (id_id)',
            'en_ie' => 'Ireland (en_ie)',
            'en_il' => 'Isreal English (en_il)',
            'iw_il' => 'Isreal (iw_il)',
            'it' => 'Italia (it)',
            'en_ke' => 'Kenya (en_ke)',
            'lv_lv' => 'Latvija (lv_lv)',
            'lt_lt' => 'Lietuva (lt_lt)',
            'hu_hu' => 'Magyarorszag (hu_hu)',
            'en_my' => 'Malaysia (en_my)',
            'fr_ma' => 'Maroc (fr_ma)',
            'es_mx' => 'Mexico (es_mx)',
            'en_na' => 'Namibia (es_na)',
            'nl_nl' => 'Nederland (nl_nl)',
            'nz' => 'New Zealand (nz)',
            'en_nq' => 'Nigeria (en_nq)',
            'no_no' => 'Norge (no_no)',
            'de_at' => 'Osterreich (de_at)',
            'en_pk' => 'Pakistan (en_pk)',
            'es_pe' => 'Peru (es_pe)',
            'en_ph' => 'Philippines (en_ph)',
            'pl_pl' => 'Polska (pl_pl)',
            'pt-PT_pt' => 'Portugal (pt-PT_pt)',
            'ro_ro' => 'Romania (ro_ro)',
            'de_ch' => 'Schweiz (de_ch)',
            'fr_sn' => 'Sénégal (fr_sn)',
            'en_sg' => 'Singapore English (en_sg)',
            'sl_si' => 'Slovenia (sl_si)',
            'sk_sk' => 'Slovensko (sk_sk)',
            'en_za' => 'South Africa (en_za)',
            'fr_ch' => 'Suisse (fr_ch)',
            'sv_se' => 'Sverige (sv_se)',
            'en_tz' => 'Tanzania (en_tz)',
            'tr_tr' => 'Turkiye (tr_tr)',
            'us' => 'United States (us)',
            'uk' => 'U.K. (uk)',
            'en_ug' => 'Uganda (en_ug)',
            'es_ve' => 'Venezuela (es_ve)',
            'vi_vn' => 'Việt Nam (Vietnam)‎ (vi_vn)',
            'en_zw' => 'Zimbabwe (en_zw)',
            'el_gr' => 'Ελλάδα (Greece) (el_gr)',
            'ru_ru' => 'Россия (Russia) (ru_ru)',
            'sr_rs' => 'Србија (Serbia) (sr_rs)',
            'ru_ua' => 'Украина (Ukraine) (ru_ua)',
            'uk_ua' => 'Україна (Ukraine) (uk_ua)',
            'ar_at' => 'الإمارات (UAE) (ar_at)',
            'ar_sa' => '- السعودية  (ar_sa) (KSA)',
            'ar_me' => 'العالم العربي (Arabic world)(ar_me)',
            'ar_lb' => ' لبنان (Lebanon)(ar_lb)',
            'ar_eg' => ' مصر (Egypt) (ar_eg)',
            'kr' => '한국 (Korea) (kr)',
            'cn' => '中国版 (China) (cn)',
            'tw' => '台灣版 (Taiwan) (tw)',
            'jp' => '日本 (Japan) (jp)',
            'hk' => '香港版 (Hong Kong) (hk)'
        );

        $options = get_option('curation_suite_data');
        $default_lang = 'us';
        if (is_array($options) && array_key_exists('cs_google_news_blog_search_language_default', $options))
            $default_lang = $options['cs_google_news_blog_search_language_default'];

        if ($default_lang == '')
            $default_lang = 'us';
        foreach ($valuesArr as $key => $value) {
            $html .= '<option value="' . $key . '"' . selected($default_lang, $key, false) . '>' . $value . '</option>';
        }
        $html .= '</select>';
        echo $html;
    }

    add_settings_field('cs_iso_search_language_default',// Unique ID
        'Default Youtube, Google News & Twitter Search Language', // Name for this field
        'cs_iso_search_language_default_field', //Function to call
        __FILE__, // Page
        'curation_suite_settings_top' // Section to belong to
    );

    function cs_iso_search_language_default_field()
    {

        $options = get_option('curation_suite_data');
        $html = '<select id="cs_iso_search_language_default" name="curation_suite_data[cs_iso_search_language_default]">';
        $valuesArr = array(
            "aa" => "Afar",
            "ab" => "Abkhazian",
            "ae" => "Avestan",
            "af" => "Afrikaans",
            "ak" => "Akan",
            "am" => "Amharic",
            "an" => "Aragonese",
            "ar" => "Arabic",
            "as" => "Assamese",
            "av" => "Avaric",
            "ay" => "Aymara",
            "az" => "Azerbaijani",
            "ba" => "Bashkir",
            "be" => "Belarusian",
            "bg" => "Bulgarian",
            "bh" => "Bihari",
            "bi" => "Bislama",
            "bm" => "Bambara",
            "bn" => "Bengali",
            "bo" => "Tibetan",
            "br" => "Breton",
            "bs" => "Bosnian",
            "ca" => "Catalan",
            "ce" => "Chechen",
            "ch" => "Chamorro",
            "co" => "Corsican",
            "cr" => "Cree",
            "cs" => "Czech",
            "cu" => "Church Slavic",
            "cv" => "Chuvash",
            "cy" => "Welsh",
            "da" => "Danish",
            "de" => "German",
            "dv" => "Divehi",
            "dz" => "Dzongkha",
            "ee" => "Ewe",
            "el" => "Greek",
            "en" => "English",
            "eo" => "Esperanto",
            "es" => "Spanish",
            "et" => "Estonian",
            "eu" => "Basque",
            "fa" => "Persian",
            "ff" => "Fulah",
            "fi" => "Finnish",
            "fj" => "Fijian",
            "fo" => "Faroese",
            "fr" => "French",
            "fy" => "Western Frisian",
            "ga" => "Irish",
            "gd" => "Scottish Gaelic",
            "gl" => "Galician",
            "gn" => "Guarani",
            "gu" => "Gujarati",
            "gv" => "Manx",
            "ha" => "Hausa",
            "he" => "Hebrew",
            "hi" => "Hindi",
            "ho" => "Hiri Motu",
            "hr" => "Croatian",
            "ht" => "Haitian",
            "hu" => "Hungarian",
            "hy" => "Armenian",
            "hz" => "Herero",
            "ia" => "Interlingua",
            "id" => "Indonesian",
            "ie" => "Interlingue",
            "ig" => "Igbo",
            "ii" => "Sichuan Yi",
            "ik" => "Inupiaq",
            "io" => "Ido",
            "is" => "Icelandic",
            "it" => "Italian",
            "iu" => "Inuktitut",
            "ja" => "Japanese",
            "jv" => "Javanese",
            "ka" => "Georgian",
            "kg" => "Kongo",
            "ki" => "Kikuyu",
            "kj" => "Kwanyama",
            "kk" => "Kazakh",
            "kl" => "Kalaallisut",
            "km" => "Khmer",
            "kn" => "Kannada",
            "ko" => "Korean",
            "kr" => "Kanuri",
            "ks" => "Kashmiri",
            "ku" => "Kurdish",
            "kv" => "Komi",
            "kw" => "Cornish",
            "ky" => "Kirghiz",
            "la" => "Latin",
            "lb" => "Luxembourgish",
            "lg" => "Ganda",
            "li" => "Limburgish",
            "ln" => "Lingala",
            "lo" => "Lao",
            "lt" => "Lithuanian",
            "lu" => "Luba-Katanga",
            "lv" => "Latvian",
            "mg" => "Malagasy",
            "mh" => "Marshallese",
            "mi" => "Maori",
            "mk" => "Macedonian",
            "ml" => "Malayalam",
            "mn" => "Mongolian",
            "mr" => "Marathi",
            "ms" => "Malay",
            "mt" => "Maltese",
            "my" => "Burmese",
            "na" => "Nauru",
            "nb" => "Norwegian Bokmal",
            "nd" => "North Ndebele",
            "ne" => "Nepali",
            "ng" => "Ndonga",
            "nl" => "Dutch",
            "nn" => "Norwegian Nynorsk",
            "no" => "Norwegian",
            "nr" => "South Ndebele",
            "nv" => "Navajo",
            "ny" => "Chichewa",
            "oc" => "Occitan",
            "oj" => "Ojibwa",
            "om" => "Oromo",
            "or" => "Oriya",
            "os" => "Ossetian",
            "pa" => "Panjabi",
            "pi" => "Pali",
            "pl" => "Polish",
            "ps" => "Pashto",
            "pt" => "Portuguese",
            "qu" => "Quechua",
            "rm" => "Raeto-Romance",
            "rn" => "Kirundi",
            "ro" => "Romanian",
            "ru" => "Russian",
            "rw" => "Kinyarwanda",
            "sa" => "Sanskrit",
            "sc" => "Sardinian",
            "sd" => "Sindhi",
            "se" => "Northern Sami",
            "sg" => "Sango",
            "si" => "Sinhala",
            "sk" => "Slovak",
            "sl" => "Slovenian",
            "sm" => "Samoan",
            "sn" => "Shona",
            "so" => "Somali",
            "sq" => "Albanian",
            "sr" => "Serbian",
            "ss" => "Swati",
            "st" => "Southern Sotho",
            "su" => "Sundanese",
            "sv" => "Swedish",
            "sw" => "Swahili",
            "ta" => "Tamil",
            "te" => "Telugu",
            "tg" => "Tajik",
            "th" => "Thai",
            "ti" => "Tigrinya",
            "tk" => "Turkmen",
            "tl" => "Tagalog",
            "tn" => "Tswana",
            "to" => "Tonga",
            "tr" => "Turkish",
            "ts" => "Tsonga",
            "tt" => "Tatar",
            "tw" => "Twi",
            "ty" => "Tahitian",
            "ug" => "Uighur",
            "uk" => "Ukrainian",
            "ur" => "Urdu",
            "uz" => "Uzbek",
            "ve" => "Venda",
            "vi" => "Vietnamese",
            "vo" => "Volapuk",
            "wa" => "Walloon",
            "wo" => "Wolof",
            "xh" => "Xhosa",
            "yi" => "Yiddish",
            "yo" => "Yoruba",
            "za" => "Zhuang",
            "zh" => "Chinese",
            "zu" => "Zulu"
        );

        $default_lang = 'en';
        if (is_array($options) && array_key_exists('cs_iso_search_language_default', $options))
            $default_lang = $options['cs_iso_search_language_default'];

        foreach ($valuesArr as $key => $value) {
            $html .= '<option value="' . $key . '"' . selected($default_lang, $key, false) . '>' . $value . '</option>';
        }
        $html .= '</select>';
        echo $html;
    }

    add_settings_field('cs_bing_search_language_default',// Unique ID
        'Default Bing Search Language', // Name for this field
        'cs_bing_search_language_default_field', //Function to call
        __FILE__, // Page
        'curation_suite_settings_top' // Section to belong to
    );

    function cs_bing_search_language_default_field()
    {

        $options = get_option('curation_suite_data');
        $html = '<select id="cs_bing_search_language_default" name="curation_suite_data[cs_bing_search_language_default]">';
        $valuesArr = array(
            'ar-XA' => 'Arabic - Arabia',
            'bg-BG' => 'Bulgarian - Bulgaria',
            'cs-CZ' => 'Czech - Czech Republic',
            'da-DK' => 'Danish - Denmark',
            'de-AT' => 'German - Austria',
            'de-CH' => 'German - Switzerland',
            'de-DE' => 'German - Germany',
            'el-GR' => 'Greek - Greece',
            'en-AU' => 'English - Australia',
            'en-CA' => 'English - Canada',
            'en-GB' => 'English - United Kingdom',
            'en-ID' => 'English - Indonesia',
            'en-IE' => 'English - Ireland',
            'en-IN' => 'English - India',
            'en-MY' => 'English - Malaysia',
            'en-NZ' => 'English - New Zealand',
            'en-PH' => 'English - Philippines',
            'en-SG' => 'English - Singapore',
            'en-US' => 'English - United States',
            'en-XA' => 'English - Arabia',
            'en-ZA' => 'English - South Africa',
            'es-AR' => 'Spanish - Argentina',
            'es-CL' => 'Spanish - Chile',
            'es-ES' => 'Spanish - Spain',
            'es-MX' => 'Spanish - Mexico',
            'es-US' => 'Spanish - United States',
            'es-XL' => 'Spanish - Latin America',
            'et-EE' => 'Estonian - Estonia',
            'fi-FI' => 'Finnish - Finland',
            'fr-BE' => 'French - Belgium',
            'fr-CA' => 'French - Canada',
            'fr-CH' => 'French - Switzerland',
            'fr-FR' => 'French - France',
            'he-IL' => 'Hebrew - Israel',
            'hr-HR' => 'Croatian - Croatia',
            'hu-HU' => 'Hungarian - Hungary',
            'it-IT' => 'Italian - Italy',
            'ja-JP' => 'Japanese - Japan',
            'ko-KR' => 'Korean - Korea',
            'lt-LT' => 'Lithuanian - Lithuania',
            'lv-LV' => 'Latvian - Latvia',
            'nb-NO' => 'Norwegian - Norway',
            'nl-BE' => 'Dutch - Belgium',
            'nl-NL' => 'Dutch - Netherlands',
            'pl-PL' => 'Polish - Poland',
            'pt-BR' => 'Portuguese - Brazil',
            'pt-PT' => 'Portuguese - Portugal',
            'ro-RO' => 'Romanian - Romania',
            'ru-RU' => 'Russian - Russia',
            'sk-SK' => 'Slovak - Slovak Republic',
            'sl-SL' => 'Slovenian - Slovenia',
            'sv-SE' => 'Swedish - Sweden',
            'th-TH' => 'Thai - Thailand',
            'tr-TR' => 'Turkish - Turkey',
            'uk-UA' => 'Ukrainian - Ukraine',
            'zh-CN' => 'Chinese - China',
            'zh-HK' => 'Chinese - Hong Kong SAR',
            'zh-TW' => 'Chinese - Taiwan'

        );

        $default_lang = 'en-US';
        if (is_array($options) && array_key_exists('cs_bing_search_language_default', $options))
            $default_lang = $options['cs_bing_search_language_default'];

        foreach ($valuesArr as $key => $value) {
            $html .= '<option value="' . $key . '"' . selected($default_lang, $key, false) . '>' . $value . '</option>';
        }
        $html .= '</select>';
        echo $html;
    }

    add_settings_field('cs_reddit_default',// Unique ID
        'Default Reddit Search Options', // Name for this field
        'cs_reddit_default_field', //Function to call
        __FILE__, // Page
        'curation_suite_settings_top' // Section to belong to
    );

    function cs_reddit_default_field()
    {

        $options = get_option('curation_suite_data');

        $html = '<select id="cs_reddit_sort_default" name="curation_suite_data[cs_reddit_sort_default]">';
        $valuesArr = array(
            'new' => 'New',
            'hot' => 'Hot',
            'top' => 'Top',
            'comments' => 'Comments',
            'relevance' => 'Relevance',
        );
        $cs_default = $options['cs_reddit_sort_default'];
        if ($cs_default == '')
            $cs_default = 'new';
        foreach ($valuesArr as $key => $value) {
            $html .= '<option value="' . $key . '"' . selected($cs_default, $key, false) . '>' . $value . '</option>';
        }
        $html .= '</select>';

        $html .= '<select id="cs_reddit_total_default" name="curation_suite_data[cs_reddit_total_default]">';
        $valuesArr = array(
            '10' => '10',
            '25' => '25',
            '40' => '40',
            '50' => '50',
            '75' => '75',
            '100' => '100',
        );
        $cs_default = $options['cs_reddit_total_default'];
        if ($cs_default == '')
            $cs_default = 'new';
        foreach ($valuesArr as $key => $value) {
            $html .= '<option value="' . $key . '"' . selected($cs_default, $key, false) . '>' . $value . '</option>';
        }
        $html .= '</select>';

        $html .= '<select id="cs_reddit_show_threads_default" name="curation_suite_data[cs_reddit_show_threads_default]">';
        $valuesArr = array(
            'threads' => 'Show Threads',
            'ignore-threads' => 'Ignore Threads',
        );
        $cs_default = $options['cs_reddit_show_threads_default'];
        if ($cs_default == '')
            $cs_default = 'new';
        foreach ($valuesArr as $key => $value) {
            $html .= '<option value="' . $key . '"' . selected($cs_default, $key, false) . '>' . $value . '</option>';
        }
        $html .= '</select>';

        $html .= '<select id="cs_reddit_time_frame_default" name="curation_suite_data[cs_reddit_time_frame_default]">';
        $valuesArr = array(
            'all' => 'All',
            'hour' => 'Hour',
            'day' => 'Day',
            'week' => 'Week',
            'month' => 'Month',
            'year' => 'Year',
        );
        $cs_default = $options['cs_reddit_time_frame_default'];
        if ($cs_default == '')
            $cs_default = 'new';
        foreach ($valuesArr as $key => $value) {
            $html .= '<option value="' . $key . '"' . selected($cs_default, $key, false) . '>' . $value . '</option>';
        }
        $html .= '</select>';

        echo $html;
    }

    add_settings_field('cs_twitter_credentials',// Unique ID
        'Twitter App Credentials<span class="explanation">This is required to use the Twitter Search. It\'s easy and simple to do, <a href="http://curationsuite.com/tutorial/twitter-app-setup" target="_blank">just follow along with this tutorial</a>.</span>', // Name for this field
        'curation_suite_twitter_credentials_field', //Function to call
        __FILE__, // Page
        'curation_suite_settings_top' // Section to belong to
    );

    function curation_suite_twitter_credentials_field()
    {


        $options = get_option('curation_suite_data');
        $name = 'cs_twitter_oauth_access_token';
        $saved_value = '';
        if (is_array($options) && array_key_exists($name, $options))
            $saved_value = $options[$name];

        $html = "<label>Access Token</label>
        <input id='cs_twitter_oauth_access_token' name='curation_suite_data[cs_twitter_oauth_access_token]' type='text' value='" . $saved_value . "' class='twitter_credentials' />";

        $name = 'cs_twitter_oauth_access_token_secret';
        $saved_value = '';
        if (is_array($options) && array_key_exists($name, $options))
            $saved_value = $options[$name];

        $html .= "<label>Access Token Secret</label>
            <input id='cs_twitter_oauth_access_token_secret' name='curation_suite_data[cs_twitter_oauth_access_token_secret]' type='text' value='" . $saved_value . "' class='twitter_credentials' />";

        $name = 'cs_twitter_consumer_key';
        $saved_value = '';
        if (is_array($options) && array_key_exists($name, $options))
            $saved_value = $options[$name];

        $html .= "<label>Consumer Key</label>
            <input id='cs_twitter_consumer_key' name='curation_suite_data[cs_twitter_consumer_key]' type='text' value='" . $saved_value . "' class='twitter_credentials' />";

        $name = 'cs_twitter_consumer_secret';
        $saved_value = '';
        if (is_array($options) && array_key_exists($name, $options))
            $saved_value = $options[$name];
        $html .= "<label>Consumer Secret</label>
            <input id='cs_twitter_consumer_secret' name='curation_suite_data[cs_twitter_consumer_secret]' type='text' value='" . $saved_value . "' class='twitter_credentials' />";

        echo $html;
    }


    add_settings_field('cs_instagram_credentials',// Unique ID
    'Instagram Credentials<span class="explanation">This is required to use the Instagram Search.</span>',
    'curation_suite_instagram_credentials_field', //Function to call
    __FILE__, // Page
    'curation_suite_settings_top' // Section to belong to
);

    function curation_suite_instagram_credentials_field()
    {
        $options = get_option('curation_suite_data');
        $name = 'cs_instagram_username';
        $saved_value = '';
        if (is_array($options) && array_key_exists($name, $options))
            $saved_value = $options[$name];

        $html = "<label>Instagram Username</label>
            <input id='cs_instagram_username' name='curation_suite_data[cs_instagram_username]' type='text' value='" . $saved_value . "' class='twitter_credentials' />";

        $name = 'cs_instagram_password';
        $saved_value = '';
        if (is_array($options) && array_key_exists($name, $options))
            $saved_value = $options[$name];

        $html .= "<label>Instagram Password</label>
            <input id='cs_instagram_password' name='curation_suite_data[cs_instagram_password]' type='password' value='" . $saved_value . "' class='twitter_credentials' />";

        echo $html;
    }

    add_settings_field('cs_pinterest_credentials',// Unique ID
        'Pinterest Credentials<span class="explanation">This is required to use the Pinterest Search.</span>',
        'curation_suite_pinterest_credentials_field', //Function to call
        __FILE__, // Page
        'curation_suite_settings_top' // Section to belong to
    );

    function curation_suite_pinterest_credentials_field()
    {
        $options = get_option('curation_suite_data');
        $name = 'cs_pinterest_username';
        $saved_value = '';
        if (is_array($options) && array_key_exists($name, $options))
            $saved_value = $options[$name];

        $html = "<label>Pinterest Username</label>
            <input id='cs_pinterest_username' name='curation_suite_data[cs_pinterest_username]' type='text' value='" . $saved_value . "' class='twitter_credentials' />";

        $name = 'cs_pinterest_password';
        $saved_value = '';
        if (is_array($options) && array_key_exists($name, $options))
            $saved_value = $options[$name];

        $html .= "<label>Pinterest Password</label>
            <input id='cs_pinterest_password' name='curation_suite_data[cs_pinterest_password]' type='password' value='" . $saved_value . "' class='twitter_credentials' />";

        echo $html;
    }
    
    //------------------------ End Section 1 -------------------------//
}
/**
 * Function that will check if value is a valid HEX color.
 */
function cs_check_color( $value ) {

    if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) { // if user insert a HEX color with #
        return true;
    }

    return false;
}

function curation_suite_top_section()
{
    echo '<p>Visit the Members Site for <a href="https://members.youbrandinc.com/dashboard/curation-suite/curation-suite-product-tutorials/" target="_blank" class="green_button">Product Tutorials</a></p>';
    if (version_compare(phpversion(), '5.3.0', '>='))
        echo '<p>Your site is PHP Version: ' . phpversion() . '</p>';
    else
        echo '<p style="color: #ff0000;">Curation Suite&trade; requires PHP Version 5.3+, please upgrade. You\'re running PHP Version: ' . phpversion() . '</p>';
}


function curation_suite_validate_data($fields)
{
    $fields['curation_suite_default_image_size'] = preg_replace('/\s+/', '', $fields['curation_suite_default_image_size']);
//   $input['curation_suite_custom_link_text'] = preg_replace('/\s+/', '', $input['curation_suite_custom_link_text']);
    //$input['curation_suite_twitter_username'] = preg_replace('/\s+/', '', $input['curation_suite_twitter_username']);

    // Validate Background Color
    /*$background = trim( $fields['background'] );
    $background = strip_tags( stripslashes( $background ) );

    // Check if is a valid hex color
    if( FALSE === cs_check_color( $background ) ) {

        // Set the error message
        add_settings_error( 'curation_suite_plugin_options', 'cpa_bg_error', 'Insert a valid color for Background', 'error' ); // $setting, $code, $message, $type

        // Get the previous valid value
        $valid_fields['background'] = $this->options['background'];

    } else {

        $valid_fields['background'] = $background;

    }*/

    return $fields;
}