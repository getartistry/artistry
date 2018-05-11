<?php
/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

/**
 * Class aspInit
 *
 * AJAX SEARCH PRO initializator Class
 */
class WD_ASP_Init {

    /**
     * Core singleton class
     * @var WD_ASP_Init self
     */
    private static $_instance;

    private function __construct() {
        wd_asp()->db = WD_ASP_DBMan::getInstance();
        // @TODO 4.10.5
        /*
        wd_asp()->instant = new WD_ASP_Instant(array(
            'table_name' => 'asp_instant'
        ));
        */

        load_plugin_textdomain( 'ajax-search-pro', false, ASP_DIR . '/locale' );
    }

    /**
     * Runs on activation OR if this->safety_check() detects a silent change
     */
    public function activate() {

        WD_ASP_DBMan::getInstance()->create();

        $indexObj = new asp_indexTable();
        $indexObj->createIndexTable();

        // @TODO 4.10.5
        /*wd_asp()->instant->db_create();
        wd_asp()->instant->db_update();*/

        $this->create_chmod(true);

        $stored_ver = get_option('asp_version', 0);
        // Was the plugin previously installed, and updated?
        if ($stored_ver != 0 && $stored_ver != ASP_CURR_VER) {
            update_option("asp_recently_updated", 1);
        }

        /**
         * Store the version number after everything is done. This is going to help distinguishing
         * stored asp_version from the ASP_CURR_VER variable. These two are different in cases:
         *  - Uninstalling, installing new versions
         *  - Uploading and overwriting old version with a new one
         */
        update_option('asp_version', ASP_CURR_VER);

        set_transient('asp_just_activated', 1);
    }

    /**
     *  Checks if the user correctly updated the plugin and fixes if not
     */
    public function safety_check() {
        $curr_stored_ver = get_option('asp_version', 0);

        // Run the re-activation actions if this is actually a newer version
        if ($curr_stored_ver != ASP_CURR_VER) {
            $this->activate();
            // Run a backwards compatibility check
            $this->backwards_compatibility_fixes();
            asp_generate_the_css();
            // Take a note on the recent update
            update_option("asp_recently_updated", 1);
        } else {
            // Still, check the folders, they might have been deleted by accident
            $this->create_chmod();

            // Was the plugin just activated, without version change?
            if ( get_transient('asp_just_activated') !== false ) {
                // Run a backwards compatibility check
                $this->backwards_compatibility_fixes();
                asp_generate_the_css();
                delete_transient('asp_just_activated');
            }
        }
    }

    /**
     * Fix known backwards incompatibilities
     */
    public function backwards_compatibility_fixes() {
        /*
         * - Get instances
         * - Check options
         * - Transition to new options based on old ones
         * - Save instances
         */

        foreach (wd_asp()->instances->get() as $si) {
            $id = $si['id'];
            $sd = $si['data'];

            // -------------------------- 4.10 ------------------------------
            // Primary and secondary fields
            $values = array('-1', '0', '1', '2', 'c__f');
            $adv_fields = array(
                'primary_titlefield',
                'secondary_titlefield',
                'primary_descriptionfield',
                'secondary_descriptionfield'
            );
            foreach($adv_fields as $field) {
                // Force string conversion for proper comparision
                if ( !in_array($sd[$field].'', $values) ) {
                    // Custom field value is selected
                    $sd[$field.'_cf'] = $sd[$field];
                    $sd[$field] = 'c__f';
                }
            }
            // -------------------------- 4.10 ------------------------------

            // ------------------------- 4.10.4 -----------------------------
            // Autocomplete aggreagated to one option only.
            if ( isset($sd['autocomplete_mobile']) ) {
                if ( $sd['autocomplete_mobile'] == 1 && $sd['autocomplete'] == 1 ) {
                    $sd['autocomplete'] = 1;
                } else if ( $sd['autocomplete_mobile'] == 1 ) {
                    $sd['autocomplete'] = 3;
                } else if ( $sd['autocomplete'] == 1 ) {
                    $sd['autocomplete'] = 2;
                } else {
                    $sd['autocomplete'] = 0;
                }
                unset($sd['autocomplete_mobile']);
            }
            // ------------------------- 4.10.4 -----------------------------

            // ------------------------- 4.11 -------------------------------
            // Autocomplete aggreagated to one option only.
            if ( !isset($sd['frontend_fields']['unselected']) )
                $sd['frontend_fields']['unselected'] = array();
            if ( isset($sd['showexactmatches'], $sd['exactmatchestext']) ) {
                $sd['frontend_fields']['labels']['exact'] = $sd['exactmatchestext'];
                if ($sd['showexactmatches'] == 0) {
                    $sd['frontend_fields']['unselected'][] = 'exact';
                    $sd['frontend_fields']['selected'] =
                        array_diff( $sd['frontend_fields']['selected'], array('exact') );
                }
            }
            if ( isset($sd['showsearchintitle'], $sd['searchintitletext']) ) {
                $sd['frontend_fields']['labels']['title'] = $sd['searchintitletext'];
                if ($sd['showsearchintitle'] == 0) {
                    $sd['frontend_fields']['unselected'][] = 'title';
                    $sd['frontend_fields']['selected'] =
                        array_diff( $sd['frontend_fields']['selected'], array('title') );
                }
            }
            if ( isset($sd['showsearchincontent'], $sd['searchincontenttext']) ) {
                $sd['frontend_fields']['labels']['content'] = $sd['searchincontenttext'];
                if ($sd['showsearchincontent'] == 0) {
                    $sd['frontend_fields']['unselected'][] = 'content';
                    $sd['frontend_fields']['selected'] =
                        array_diff( $sd['frontend_fields']['selected'], array('content') );
                }
            }
            if ( isset($sd['showsearchincomments'], $sd['searchincommentstext']) ) {
                $sd['frontend_fields']['labels']['comments'] = $sd['searchincommentstext'];
                if ($sd['showsearchincomments'] == 0) {
                    $sd['frontend_fields']['unselected'][] = 'comments';
                    $sd['frontend_fields']['selected'] =
                        array_diff( $sd['frontend_fields']['selected'], array('comments') );
                }
            }
            if ( isset($sd['showsearchinexcerpt'], $sd['searchinexcerpttext']) ) {
                $sd['frontend_fields']['labels']['excerpt'] = $sd['searchinexcerpttext'];
                if ($sd['showsearchinexcerpt'] == 0) {
                    $sd['frontend_fields']['unselected'][] = 'excerpt';
                    $sd['frontend_fields']['selected'] =
                        array_diff( $sd['frontend_fields']['selected'], array('excerpt') );
                }
            }
            // ------------------------- 4.11 -------------------------------

            // ------------------------- 4.11.6 -----------------------------
            // User meta fields to array
            if ( isset($sd['user_search_meta_fields']) && !is_array($sd['user_search_meta_fields']) ) {
                $sd['user_search_meta_fields'] = explode(',', $sd['user_search_meta_fields']);
                foreach ( $sd['user_search_meta_fields'] as $umk=>$umv ) {
                    $sd['user_search_meta_fields'][$umk] = trim($umv);
                    if( $sd['user_search_meta_fields'][$umk] == '' )
                        unset($sd['user_search_meta_fields'][$umk]);
                }
            }
            // ------------------------- 4.11.6 -----------------------------

            // ------------------------- 4.11.10 ----------------------------
            // Before, this was a string
            if ( isset($sd['customtypes']) && !is_array($sd['customtypes']) ) {
                $sd['customtypes'] = explode('|', $sd['customtypes']);
                foreach ($sd['customtypes'] as $ck => $ct) {
                    if ( $ct == '' )
                        unset($sd['customtypes'][$ck]);
                }
            }
            // No longer exists
            if ( isset($sd['selected-customtypes']) )
                unset($sd['selected-customtypes']);
            // No longer exists
            if ( isset($sd['searchinpages']) ) {
                if ( $sd['searchinpages'] == 1 && !in_array('page', $sd['customtypes']) ) {
                    array_unshift($sd['customtypes'] , 'page');
                }
                unset($sd['searchinpages']);
            }
            // No longer exists
            if ( isset($sd['searchinposts']) ) {
                if ( $sd['searchinposts'] == 1 && !in_array('post', $sd['customtypes']) ) {
                    array_unshift($sd['customtypes'] , 'post');
                }
                unset($sd['searchinposts']);
            }
            // ------------------------- 4.11.10 ----------------------------

            // ------------------------- 4.12 -------------------------------
            if ( is_numeric($sd['i_item_width']) ) {
                $sd['i_item_width'] = $sd['i_item_width'].'px';
            }
            // ------------------------- 4.12 -------------------------------

            // ----------------- Unset some unused search data --------------
            // Leave this here, so it is executed as last
            $values = array(
                // from 4.10
                'magnifierimage_selects', 'settingsimage_selects', 'loadingimage_selects',
                'i_res_magnifierimage_selects', 'i_pagination_arrow_selects', 'keyword_logic_def',
                'user_search_title_field_def', 'frontend_search_settings_position_def', 'term_logic_def',
                'cf_logic_def', 'resultstype_def', 'resultsposition_def', 'box_compact_float_def',
                'box_compact_position_def', 'keyword_suggestion_source_def', 'bpgroupstitle_def', 'bpgroupstitle',
                'settingsimagepos_def', 'blogtitleorderby_def', 'i_ifnoimage_def', 'i_pagination_position_def',
                'weight_def', 'user_search_description_field_def', 'triggeronclick', 'triggeronreturn', 'redirectonclick',
                'redirect_click_to', 'redirect_on_enter', 'redirect_enter_to', 'mob_trigger_on_click',
                // from 4.11
                'showexactmatches', 'exactmatchestext', 'showsearchintitle', 'searchintitletext', 'showsearchincontent',
                'searchincontenttext', 'showsearchincomments', 'searchincommentstext', 'showsearchinexcerpt', 'searchinexcerpttext'
            );
            foreach ($values as $v) {
                if ( isset($sd[$v]) )
                    unset($sd[$v]);
            }

            // At the end, update
            wd_asp()->instances->update($id, $sd);
        }
    }

    /**
     * Extra styles if needed..
     */
    public function styles() {
        // Fallback on IE<=8
        if(isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(?i)msie [6-8]/',$_SERVER['HTTP_USER_AGENT']) ) {
            $comp_options = wd_asp()->o['asp_compatibility'];
            if ( $comp_options['old_browser_compatibility'] == 1 ) {
                return;
            }
        }
    }

    /**
     * Prints the scripts
     */
    public function scripts() {
        $comp_settings = wd_asp()->o['asp_compatibility'];

        // Fallback on IE<=8
        if(isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(?i)msie [6-8]/',$_SERVER['HTTP_USER_AGENT']) ) {
            if ( $comp_settings['old_browser_compatibility'] == 1 ) {
                return;
            }
        }

        $exit = apply_filters('asp_load_css_js', false);
        if ( $exit )
            return false;

        $comp_settings = wd_asp()->o['asp_compatibility'];
        $load_in_footer = w_isset_def($comp_settings['load_in_footer'], 1) == 1 ? true : false;
        $css_async_load = w_isset_def($comp_settings['css_async_load'], 0) == 1 ? true : false;
        $media_query = ASP_DEBUG == 1 ? asp_gen_rnd_str() : get_option("asp_media_query", "defn");

        if ($comp_settings !== false && isset($comp_settings['loadpolaroidjs']) && $comp_settings['loadpolaroidjs'] == 0) {
            ;
        } else {
            wp_register_script('wd-asp-photostack', ASP_URL_NP . 'js/nomin/photostack.js', array("jquery"), $media_query, $load_in_footer);
            wp_enqueue_script('wd-asp-photostack');
        }

        $js_source = w_isset_def($comp_settings['js_source'], 'min');
        $load_mcustom = w_isset_def($comp_settings['load_mcustom_js'], "yes") == "yes";
        $load_noui = w_isset_def($comp_settings['load_noui_js'], 1);
        $load_isotope = w_isset_def($comp_settings['load_isotope_js'], 1);
        $load_datepicker = w_isset_def($comp_settings['load_datepicker_js'], 1);
        $load_chosen = w_isset_def($comp_settings['load_chosen_js'], 1);
        // @TODO 4.10.5
        //$load_autocomplete = w_isset_def($comp_settings['load_autocomplete_js'], 1);
        $minify_string = (($load_noui == 1) ? '-noui' : '') . (($load_isotope == 1) ? '-isotope' : '');

        if (ASP_DEBUG) $js_source = 'nomin';

        if ( $css_async_load ) {
            wp_register_script('wd-asp-async-loader', ASP_URL_NP . 'js/nomin/async.css.js', array("jquery"), $media_query, $load_in_footer);
            wp_enqueue_script('wd-asp-async-loader');
        }

        // @TODO 4.10.5
        // https://gist.github.com/anag0/d7211125b0eda4ecb8f459118bb67938
        /*if ( $load_autocomplete ) {
            wp_register_script('wd-asp-ajaxsearchpro-ac', ASP_URL_NP . 'js/nomin/asp_autocomplete.js', array('jquery'), $media_query, $load_in_footer);
            wp_enqueue_script('wd-asp-ajaxsearchpro-ac');
        }*/

        if ( $load_chosen ) {
            if ( ASP_DEBUG == 1 || defined('WP_ASP_TEST_ENV') ) {
                wp_register_script('wd-asp-chosen', ASP_URL_NP . 'js/nomin/chosen.jquery.js', array('jquery'), $media_query, $load_in_footer);
                wp_enqueue_script('wd-asp-chosen');
            } else if ( strpos($js_source, 'scoped') !== false ) {
                wp_register_script('wd-asp-chosen', ASP_URL_NP . 'js/min-scoped/chosen.jquery.min.js', array('wd-asp-ajaxsearchpro'), $media_query, $load_in_footer);
                wp_enqueue_script('wd-asp-chosen');
            } else {
                wp_register_script('wd-asp-chosen', ASP_URL_NP . 'js/min/chosen.jquery.min.js', array('jquery'), $media_query, $load_in_footer);
                wp_enqueue_script('wd-asp-chosen');
            }
        }

        if ($js_source == 'nomin' || $js_source == 'nomin-scoped') {
            $prereq = "jquery";
            if ($js_source == "nomin-scoped") {
                $prereq = "wd-asp-aspjquery";
                wp_register_script('wd-asp-aspjquery', ASP_URL_NP . 'js/' . $js_source . '/aspjquery.js', array(), $media_query, $load_in_footer);
                wp_enqueue_script('wd-asp-aspjquery');
            }

            wp_register_script('wd-asp-gestures', ASP_URL_NP . 'js/' . $js_source . '/jquery.gestures.js', array($prereq), $media_query, $load_in_footer);
            wp_enqueue_script('wd-asp-gestures');
            if ($load_mcustom) {
                wp_register_script('wd-asp-scroll', ASP_URL_NP . 'js/' . $js_source . '/jquery.mCustomScrollbar.js', array($prereq), $media_query, $load_in_footer);
                wp_enqueue_script('wd-asp-scroll');
            }
            wp_register_script('wd-asp-highlight', ASP_URL_NP . 'js/' . $js_source . '/jquery.highlight.js', array($prereq), $media_query, $load_in_footer);
            wp_enqueue_script('wd-asp-highlight');
            if ($load_noui) {
                wp_register_script('wd-asp-nouislider', ASP_URL_NP . 'js/' . $js_source . '/jquery.nouislider.all.js', array($prereq), $media_query, $load_in_footer);
                wp_enqueue_script('wd-asp-nouislider');
            }
            if ($load_isotope) {
                wp_register_script('wd-asp-rpp-isotope', ASP_URL_NP . 'js/' . $js_source . '/rpp_isotope.js', array($prereq), $media_query, $load_in_footer);
                wp_enqueue_script('wd-asp-rpp-isotope');
            }
            wp_register_script('wd-asp-ajaxsearchpro', ASP_URL_NP . 'js/' . $js_source . '/jquery.ajaxsearchpro.js', array($prereq), $media_query, $load_in_footer);
            wp_enqueue_script('wd-asp-ajaxsearchpro');

            wp_register_script('wd-asp-ajaxsearchpro-widgets', ASP_URL_NP . 'js/' . $js_source . '/asp_widgets.js', array($prereq, "wd-asp-ajaxsearchpro"), $media_query, $load_in_footer);
            wp_enqueue_script('wd-asp-ajaxsearchpro-widgets');

            wp_register_script('wd-asp-ajaxsearchpro-wrapper', ASP_URL_NP . 'js/' . $js_source . '/asp_wrapper.js', array($prereq, "wd-asp-ajaxsearchpro"), $media_query, $load_in_footer);
            wp_enqueue_script('wd-asp-ajaxsearchpro-wrapper');
        } else {
            wp_enqueue_script('jquery');
            wp_register_script('wd-asp-ajaxsearchpro', ASP_URL_NP . "js/" . $js_source . "/jquery.ajaxsearchpro" . $minify_string . ".min.js", array('jquery'), $media_query, $load_in_footer);
            wp_enqueue_script('wd-asp-ajaxsearchpro');
        }

        if ($load_datepicker) {
            wp_enqueue_script('jquery-ui-datepicker');
        }

        $ajax_url = admin_url('admin-ajax.php');
        if (w_isset_def($comp_settings['usecustomajaxhandler'], 0) == 1) {
            $ajax_url = ASP_URL . 'ajax_search.php';
        }

        if (ASP_DEBUG < 1 && strpos(w_isset_def($comp_settings['js_source'], 'min-scoped'), "scoped") !== false) {
            $scope = "aspjQuery";
        } else {
            $scope = "jQuery";
        }

        /**
         * This stays here for a bit, let customers transition smoothly
         *
         * @deprecated since version 4.5.3
         */
        wp_localize_script('wd-asp-ajaxsearchpro', 'ajaxsearchpro', array(
            'ajaxurl' => $ajax_url,
            'backend_ajaxurl' => admin_url('admin-ajax.php'),
            'js_scope' => $scope
        ));

        // The new variable is ASP
        wp_localize_script('wd-asp-ajaxsearchpro', 'ASP', array(
            'ajaxurl' => $ajax_url,
            'backend_ajaxurl' => admin_url('admin-ajax.php'),
            'js_scope' => $scope,
            'asp_url' => ASP_URL,
            'upload_url' => wd_asp()->upload_url,
            'detect_ajax' => w_isset_def($comp_settings['detect_ajax'], 0),
            'media_query' => get_option("asp_media_query", "defn"),
            'version' => ASP_CURR_VER,
            'scrollbar' => $load_mcustom,
            'css_loaded' => $css_async_load == 1 ? 0 : 1,
            'js_retain_popstate' => w_isset_def($comp_settings['js_retain_popstate'], 1),
            'fix_duplicates' => w_isset_def($comp_settings['js_fix_duplicates'], 1)
        ));
    }

    /**
     *  Create and chmod the upload directory, and adds an index.html file to it
     */
    public function create_chmod( $is_activation = false ) {
        if ( $is_activation ) {
            global $wp_filesystem;
            if ( empty($wp_filesystem) ) {
                require_once (ABSPATH . '/wp-admin/includes/file.php');
                WP_Filesystem();
            }

            if ( !$wp_filesystem->is_dir( wd_asp()->upload_path ) ) {
                if ( !$wp_filesystem->mkdir( wd_asp()->upload_path, 0777 ) ) {
                    return false;
                } else {
                    if ( !@chmod(wd_asp()->upload_path, 0777) ) {
                        @chmod(wd_asp()->upload_path, 0755);
                    }
                }
            }
        } else {
            /**
             * DO NOT initialize WP_Filesystem() nor $wp_filesystem here!
             * It causes conflicts later on. Instead just use the native PHP functions.
             */
            if ( !is_dir( wd_asp()->upload_path ) ) {
                if ( !mkdir( wd_asp()->upload_path, '0777', true ) ) {
                    return false;
                } else {
                    if ( !@chmod(wd_asp()->upload_path, 0777) ) {
                        @chmod(wd_asp()->upload_path, 0755);
                    }
                }
            }
        }
        return true;
    }

    public function pluginReset( $triggerActivate = true ) {
        $options = array(
            'asp_version',
            'asp_glob_d',
            'asp_performance_def',
            'asp_performance',
            'asp_it_def',
            'asp_it',
            'asp_analytics_def',
            'asp_analytics',
            'asp_caching_def',
            'asp_caching',
            'asp_compatibility_def',
            'asp_compatibility',
            'asp_defaults',
            'asp_st_override',
            'asp_woo_override',
            'asp_stat',
            'asp_updates',
            'asp_updates_lc',
            'asp_media_query',
            'asp_performance_stats',
            'asp_recently_updated',
            '_asp_tables',
            '_asp_priority_groups',
            '_asp_it_pool_sizes'
        );
        foreach ($options as $o)
            delete_option($o);

        if ( $triggerActivate )
            $this->activate();
    }

    public function pluginWipe() {
        // Options
        $this->pluginReset( false );

        // Meta
        if ( is_multisite() ) {
            global $switched;
            $sites = get_sites(array('fields' => 'ids'));
            foreach ($sites as $site) {
                switch_to_blog($site);
                delete_metadata('post', 1, '_asp_additional_tags', '', true);
                delete_metadata('post', 1, '_asp_metadata', '', true);
                restore_current_blog();
            }
        } else {
            delete_metadata('post', 1, '_asp_additional_tags', '', true);
            delete_metadata('post', 1, '_asp_metadata', '', true);
        }

        // Database
        wd_asp()->db->delete();
        // Files with safety check for alterations
        if (
            str_replace('/', '', get_home_path()) != str_replace('/', '', wd_asp()->upload_path) &&
            strpos(wd_asp()->upload_path, 'wp-content') > 5 &&
            strpos(wd_asp()->upload_path, 'wp-includes') === false &&
            strpos(wd_asp()->upload_path, 'wp-admin') === false &&
            is_dir( wd_asp()->upload_path )
        ) {
            wpd_rmdir( wd_asp()->upload_path  );
            if ( is_dir( wd_asp()->upload_path ) ) {
                wpd_rmdir( wd_asp()->upload_path, true);
                if ( is_dir( wd_asp()->upload_path ) ) {
                    // Last attempt, with force
                    wpd_rmdir( wd_asp()->upload_path, true, true);
                }
            }
        }
        // Deactivate
        deactivate_plugins(ASP_FILE);
    }


    /**
     *  If anything we need in the footer
     */
    public function footer() {

    }

    /**
     * Get the instane of asp_indexTable
     *
     * @return self
     */
    public static function getInstance() {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
}