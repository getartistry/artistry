<?php
/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

if (!class_exists('ASP_Search_USERS')) {
    /**
     * User search class
     *
     * @class       ASP_Search_USERS
     * @version     1.0
     * @package     AjaxSearchPro/Classes
     * @category    Class
     * @author      Ernest Marcinko
     */
    class ASP_Search_USERS extends ASP_Search {

        /**
         * @var string final query
         */
        private $query;

        /**
         * The search function
         *
         * @return array
         */
        protected function do_search() {
            global $wpdb;

            $args = &$this->args;

            if ( isset($args["_sd"]) )
                $sd = &$args["_sd"];
            else
                $sd = array();

            // Prefixes and suffixes
            $pre_field = $this->pre_field;
            $suf_field = $this->suf_field;
            $pre_like  = $this->pre_like;
            $suf_like  = $this->suf_like;

            $wcl = '%'; // Wildcard Left
            $wcr = '%'; // Wildcard right
            if ( $args["_exact_matches"] == 1 ) {
                if ( $args['_exact_match_location'] == 'start' )
                    $wcl = '';
                else if ( $args['_exact_match_location'] == 'end' )
                    $wcr = '';
            }

            // Keyword logics
            $kw_logic             = $args['keyword_logic'];
            $q_config['language'] = $args['_qtranslate_lang'];

            $s = $this->s; // full keyword
            $_s = $this->_s; // array of keywords

            $words = $args["_exact_matches"] == 1 ? array( $s ) : $_s;

            if ( $args['_limit'] > 0 ) {
                $limit = $args['_limit'];
            } else {
                if ( $args['_ajax_search'] )
                    $limit = $args['users_limit'];
                else
                    $limit = $args['users_limit_override'];
            }
            $query_limit = $limit * 3;

            $parts = array();
            $relevance_parts = array();

            $bp_cf_select = "";

            if ($s != "") {
                /*---------------------- Login Name query ------------------------*/
                if ($args['user_login_search']) {
                    if ($kw_logic == 'or' || $kw_logic == 'and') {
                        $op = strtoupper($kw_logic);
                        if (count($_s) > 0)
                            $_like = implode("%'$suf_like " . $op . " " . $pre_field . $wpdb->users . ".user_login" . $suf_field . " LIKE $pre_like'%", $words);
                        else
                            $_like = $s;
                        $parts[] = "( " . $pre_field . $wpdb->users . ".user_login" . $suf_field . " LIKE $pre_like'$wcl" . $_like . "$wcr'$suf_like )";
                    } else {
                        $_like = array();
                        $op = $kw_logic == 'andex' ? 'AND' : 'OR';
                        foreach ($words as $word) {
                            $_like[] = "
                               (" . $pre_field . $wpdb->users . ".user_login" . $suf_field . " LIKE $pre_like'% " . $word . " %'$suf_like
                            OR  " . $pre_field . $wpdb->users . ".user_login" . $suf_field . " LIKE $pre_like'" . $word . " %'$suf_like
                            OR  " . $pre_field . $wpdb->users . ".user_login" . $suf_field . " LIKE $pre_like'% " . $word . "'$suf_like
                            OR  " . $pre_field . $wpdb->users . ".user_login" . $suf_field . " = '" . $word . "')";
                        }
                        $parts[] = "(" . implode(' ' . $op . ' ', $_like) . ")";
                    }

                    if (count($_s) > 0)
                        $relevance_parts[] = "(case when
                        (" . $pre_field . $wpdb->users . ".user_login" . $suf_field . " LIKE '%" . $_s[0] . "%')
                         then ".w_isset_def($sd['titleweight'], 10)." else 0 end)";
                    $relevance_parts[] = "(case when
                    (" . $pre_field . $wpdb->users . ".user_login" . $suf_field . " LIKE '%$s%')
                     then ".w_isset_def($sd['titleweight'], 10)." else 0 end)";
                }
                /*---------------------------------------------------------------*/

                /*---------------------- Display Name query ------------------------*/
                if ($args['user_display_name_search']) {
                    if ($kw_logic == 'or' || $kw_logic == 'and') {
                        $op = strtoupper($kw_logic);
                        if (count($_s) > 0)
                            $_like = implode("%'$suf_like " . $op . " " . $pre_field . $wpdb->users . ".display_name" . $suf_field . " LIKE $pre_like'%", $words);
                        else
                            $_like = $s;
                        $parts[] = "( " . $pre_field . $wpdb->users . ".display_name" . $suf_field . " LIKE $pre_like'$wcl" . $_like . "$wcr'$suf_like )";
                    } else {
                        $_like = array();
                        $op = $kw_logic == 'andex' ? 'AND' : 'OR';
                        foreach ($words as $word) {
                            $_like[] = "
                               (" . $pre_field . $wpdb->users . ".display_name" . $suf_field . " LIKE $pre_like'% " . $word . " %'$suf_like
                            OR  " . $pre_field . $wpdb->users . ".display_name" . $suf_field . " LIKE $pre_like'" . $word . " %'$suf_like
                            OR  " . $pre_field . $wpdb->users . ".display_name" . $suf_field . " LIKE $pre_like'% " . $word . "'$suf_like
                            OR  " . $pre_field . $wpdb->users . ".display_name" . $suf_field . " = '" . $word . "')";
                        }
                        $parts[] = "(" . implode(' ' . $op . ' ', $_like) . ")";
                    }

                    if (count($_s) > 0)
                        $relevance_parts[] = "(case when
                        (" . $pre_field . $wpdb->users . ".display_name" . $suf_field . " LIKE '%" . $_s[0] . "%')
                         then ".w_isset_def($sd['titleweight'], 10)." else 0 end)";
                    $relevance_parts[] = "(case when
                    (" . $pre_field . $wpdb->users . ".display_name" . $suf_field . " LIKE '$s%')
                     then ".(w_isset_def($sd['titleweight'], 10) * 2)." else 0 end)";
                    $relevance_parts[] = "(case when
                    (" . $pre_field . $wpdb->users . ".display_name" . $suf_field . " LIKE '%$s%')
                     then ".w_isset_def($sd['titleweight'], 10)." else 0 end)";
                }
                /*---------------------------------------------------------------*/

                /*---------------------- First Name query -----------------------*/
                if ($args['user_first_name_search']) {
                    if ($kw_logic == 'or' || $kw_logic == 'and') {
                        $op = strtoupper($kw_logic);
                        if (count($_s) > 0)
                            $_like = implode("%'$suf_like " . $op . " " . $pre_field . $wpdb->usermeta . ".meta_value" . $suf_field . " LIKE $pre_like'%", $words);
                        else
                            $_like = $s;
                        $parts[] = "( $wpdb->usermeta.meta_key = 'first_name' AND ( " . $pre_field . $wpdb->usermeta . ".meta_value" . $suf_field . " LIKE $pre_like'$wcl" . $_like . "$wcr'$suf_like ) )";
                    } else {
                        $_like = array();
                        $op = $kw_logic == 'andex' ? 'AND' : 'OR';
                        foreach ($words as $word) {
                            $_like[] = "
                               (" . $pre_field . $wpdb->usermeta . ".meta_value" . $suf_field . " LIKE $pre_like'% " . $word . " %'$suf_like
                            OR  " . $pre_field . $wpdb->usermeta . ".meta_value" . $suf_field . " LIKE $pre_like'" . $word . " %'$suf_like
                            OR  " . $pre_field . $wpdb->usermeta . ".meta_value" . $suf_field . " LIKE $pre_like'% " . $word . "'$suf_like
                            OR  " . $pre_field . $wpdb->usermeta . ".meta_value" . $suf_field . " = '" . $word . "')";
                        }
                        $parts[] = "($wpdb->usermeta.meta_key = 'first_name' AND (" . implode(' ' . $op . ' ', $_like) . ") )";
                    }
                }
                /*---------------------------------------------------------------*/

                /*---------------------- Last Name query ------------------------*/
                if ($args['user_last_name_search']) {
                    if ($kw_logic == 'or' || $kw_logic == 'and') {
                        $op = strtoupper($kw_logic);
                        if (count($_s) > 0)
                            $_like = implode("%'$suf_like " . $op . " " . $pre_field . $wpdb->usermeta . ".meta_value" . $suf_field . " LIKE $pre_like'%", $words);
                        else
                            $_like = $s;
                        $parts[] = "( $wpdb->usermeta.meta_key = 'last_name' AND ( " . $pre_field . $wpdb->usermeta . ".meta_value" . $suf_field . " LIKE $pre_like'$wcl" . $_like . "$wcr'$suf_like ) )";
                    } else {
                        $_like = array();
                        $op = $kw_logic == 'andex' ? 'AND' : 'OR';
                        foreach ($words as $word) {
                            $_like[] = "
                               (" . $pre_field . $wpdb->usermeta . ".meta_value" . $suf_field . " LIKE $pre_like'% " . $word . " %'$suf_like
                            OR  " . $pre_field . $wpdb->usermeta . ".meta_value" . $suf_field . " LIKE $pre_like'" . $word . " %'$suf_like
                            OR  " . $pre_field . $wpdb->usermeta . ".meta_value" . $suf_field . " LIKE $pre_like'% " . $word . "'$suf_like
                            OR  " . $pre_field . $wpdb->usermeta . ".meta_value" . $suf_field . " = '" . $word . "')";
                        }
                        $parts[] = "( $wpdb->usermeta.meta_key = 'last_name' AND ( " . implode(' ' . $op . ' ', $_like) . ") )";
                    }
                }
                /*---------------------------------------------------------------*/

                /*---------------------- Email query ------------------------*/
                if ($args['user_email_search']) {
                    if ($kw_logic == 'or' || $kw_logic == 'and') {
                        $op = strtoupper($kw_logic);
                        if (count($_s) > 0)
                            $_like = implode("%'$suf_like " . $op . " " . $pre_field . $wpdb->users . ".user_email" . $suf_field . " LIKE $pre_like'%", $words);
                        else
                            $_like = $s;
                        $parts[] = "( " . $pre_field . $wpdb->users . ".user_email" . $suf_field . " LIKE $pre_like'$wcl" . $_like . "$wcr'$suf_like )";
                    } else {
                        $_like = array();
                        $op = $kw_logic == 'andex' ? 'AND' : 'OR';
                        foreach ($words as $word) {
                            $_like[] = "
                               (" . $pre_field . $wpdb->users . ".user_email" . $suf_field . " LIKE $pre_like'% " . $word . " %'$suf_like
                            OR  " . $pre_field . $wpdb->users . ".user_email" . $suf_field . " LIKE $pre_like'" . $word . " %'$suf_like
                            OR  " . $pre_field . $wpdb->users . ".user_email" . $suf_field . " LIKE $pre_like'% " . $word . "'$suf_like
                            OR  " . $pre_field . $wpdb->users . ".user_email" . $suf_field . " = '" . $word . "')";
                        }
                        $parts[] = "(" . implode(' ' . $op . ' ', $_like) . ")";
                    }
                }
                /*---------------------------------------------------------------*/

                /*---------------------- Biography query ------------------------*/
                if ($args['user_bio_search']) {
                    if ($kw_logic == 'or' || $kw_logic == 'and') {
                        $op = strtoupper($kw_logic);
                        if (count($_s) > 0)
                            $_like = implode("%'$suf_like " . $op . " " . $pre_field . $wpdb->usermeta . ".meta_value" . $suf_field . " LIKE $pre_like'%", $words);
                        else
                            $_like = $s;
                        $parts[] = "( $wpdb->usermeta.meta_key = 'description' AND ( " . $pre_field . $wpdb->usermeta . ".meta_value" . $suf_field . " LIKE $pre_like'$wcl" . $_like . "$wcr'$suf_like ) )";
                    } else {
                        $_like = array();
                        $op = $kw_logic == 'andex' ? 'AND' : 'OR';
                        foreach ($words as $word) {
                            $_like[] = "
                               (" . $pre_field . $wpdb->usermeta . ".meta_value" . $suf_field . " LIKE $pre_like'% " . $word . " %'$suf_like
                            OR  " . $pre_field . $wpdb->usermeta . ".meta_value" . $suf_field . " LIKE $pre_like'" . $word . " %'$suf_like
                            OR  " . $pre_field . $wpdb->usermeta . ".meta_value" . $suf_field . " LIKE $pre_like'% " . $word . "'$suf_like
                            OR  " . $pre_field . $wpdb->usermeta . ".meta_value" . $suf_field . " = '" . $word . "')";
                        }
                        $parts[] = "( $wpdb->usermeta.meta_key = 'description' AND (" . implode(' ' . $op . ' ', $_like) . ") )";
                    }
                }
                /*---------------------------------------------------------------*/

                /*-------------------- Other selected meta ----------------------*/
                $args['user_search_meta_fields'] = !is_array($args['user_search_meta_fields']) ? array($args['user_search_meta_fields']) : $args['user_search_meta_fields'];
                foreach ($args['user_search_meta_fields'] as $meta_field) {
                    $meta_field = trim($meta_field);
                    if ( empty($meta_field) )
                        continue;
                    if ($kw_logic == 'or' || $kw_logic == 'and') {
                        $op = strtoupper($kw_logic);
                        if (count($_s) > 0)
                            $_like = implode("%'$suf_like " . $op . " " . $pre_field . $wpdb->usermeta . ".meta_value" . $suf_field . " LIKE $pre_like'%", $words);
                        else
                            $_like = $s;
                        $parts[] = "( $wpdb->usermeta.meta_key = '" . $meta_field . "' AND ( " . $pre_field . $wpdb->usermeta . ".meta_value" . $suf_field . " LIKE $pre_like'$wcl" . $_like . "$wcr'$suf_like ) )";
                    } else {
                        $_like = array();
                        $op = $kw_logic == 'andex' ? 'AND' : 'OR';
                        foreach ($words as $word) {
                            $_like[] = "
                               (" . $pre_field . $wpdb->usermeta . ".meta_value" . $suf_field . " LIKE $pre_like'% " . $word . " %'$suf_like
                            OR  " . $pre_field . $wpdb->usermeta . ".meta_value" . $suf_field . " LIKE $pre_like'" . $word . " %'$suf_like
                            OR  " . $pre_field . $wpdb->usermeta . ".meta_value" . $suf_field . " LIKE $pre_like'% " . $word . "'$suf_like
                            OR  " . $pre_field . $wpdb->usermeta . ".meta_value" . $suf_field . " = '" . $word . "')";
                        }
                        $parts[] = "( $wpdb->usermeta.meta_key = '" . $meta_field . "' AND (" . implode(' ' . $op . ' ', $_like) . ") )";
                    }
                }
                /*---------------------------------------------------------------*/


                /*------------------ BP Xprofile field meta ---------------------*/
                $args['user_search_bp_fields'] = !is_array($args['user_search_bp_fields']) ? array($args['user_search_bp_fields']) : $args['user_search_bp_fields'];
                $bp_meta_table = $wpdb->base_prefix . "bp_xprofile_data";
                $bp_cf_parts = array();

                if (count($args['user_search_bp_fields']) > 0 && $wpdb->get_var("SHOW TABLES LIKE '$bp_meta_table'") == $bp_meta_table) {
                    foreach ($args['user_search_bp_fields'] as $field_id) {
                        if ($kw_logic == 'or' || $kw_logic == 'and') {
                            $op = strtoupper($kw_logic);
                            if (count($_s) > 0)
                                $_like = implode("%'$suf_like " . $op . " " . $pre_field . $bp_meta_table . ".value" . $suf_field . " LIKE $pre_like'%", $words);
                            else
                                $_like = $s;
                            $bp_cf_parts[] = "( $bp_meta_table.field_id = '" . $field_id . "' AND ( " . $pre_field . $bp_meta_table . ".value" . $suf_field . " LIKE $pre_like'$wcl" . $_like . "$wcr'$suf_like ) )";
                        } else {
                            $_like = array();
                            $op = $kw_logic == 'andex' ? 'AND' : 'OR';
                            foreach ($words as $word) {
                                $_like[] = "
                               (" . $pre_field . $bp_meta_table . ".value" . $suf_field . " LIKE $pre_like'% " . $word . " %'$suf_like
                            OR  " . $pre_field . $bp_meta_table . ".value" . $suf_field . " LIKE $pre_like'" . $word . " %'$suf_like
                            OR  " . $pre_field . $bp_meta_table . ".value" . $suf_field . " LIKE $pre_like'% " . $word . "'$suf_like
                            OR  " . $pre_field . $bp_meta_table . ".value" . $suf_field . " = '" . $word . "')";
                            }
                            $bp_cf_parts[] = "( $bp_meta_table.field_id = '" . $field_id . "' AND (" . implode(' ' . $op . ' ', $_like) . ") )";
                        }
                    }

                    if (count($bp_cf_parts) > 0) {
                        $bp_cf_query = implode(" OR ", $bp_cf_parts);
                        $bp_cf_select = "
                    OR ( (
                        SELECT COUNT(*) FROM $bp_meta_table WHERE
                            $bp_meta_table.user_id = $wpdb->users.ID
                        AND
                            ($bp_cf_query)
                    ) > 0 )";
                    }
                }

                /*---------------------------------------------------------------*/
            }

            /*------------------------ Exclude Roles ------------------------*/
            $roles_query = '';
            $args['user_search_exclude_roles'] = !is_array($args['user_search_exclude_roles']) ? array($args['user_search_exclude_roles']) : $args['user_search_exclude_roles'];
            if (count($args['user_search_exclude_roles']) > 0) {
                $role_parts = array();
                foreach ($args['user_search_exclude_roles'] as $role) {
                    $role_parts[] = $wpdb->usermeta . '.meta_value LIKE \'%"' . $role . '"%\'';
                }
                // Capabilities meta field is prefixed with the DB prefix
                $roles_query = "AND $wpdb->users.ID NOT IN (
                    SELECT DISTINCT($wpdb->usermeta.user_id)
                    FROM $wpdb->usermeta
                    WHERE $wpdb->usermeta.meta_key='".$wpdb->base_prefix."capabilities' AND (" . implode(' OR ', $role_parts) . ")
                )";
            }
            /*---------------------------------------------------------------*/

            /*------------------------ Exclude Users ------------------------*/
            $exclude_query = '';
            if ( count($args['user_search_exclude_ids']) > 0 ) {
                $exclude_query .= " AND $wpdb->users.ID NOT IN(" . implode(',',$args['user_search_exclude_ids']) . ") ";
            }
            $include_query = '';
            if ( isset($args['user_search_exclude']['include']) ) {
                $include_query .= " AND $wpdb->users.ID IN(" . implode(',',$args['user_search_exclude']['include']) . ") ";
            }
            /*---------------------------------------------------------------*/

            /*------------------------- Build like --------------------------*/
            $like_query = implode(' OR ', $parts);
            if ($like_query == "")
                $like_query = "(1)";
            else {
                $like_query = "($like_query)";
            }
            /*---------------------------------------------------------------*/

            /*---------------------- Build relevance ------------------------*/
            $relevance = implode(' + ', $relevance_parts);
            if ($args['_post_use_relevance'] != 1 || $relevance == "")
                $relevance = "(1)";
            else {
                $relevance = "($relevance)";
            }
            /*---------------------------------------------------------------*/

            /*----------------------- Title Field ---------------------------*/
            switch ( w_isset_def($sd['user_search_title_field'], "display_name") ) {
                case 'login':
                    $uname_select = "$wpdb->users.user_login";
                    break;
                case 'display_name':
                    $uname_select = "$wpdb->users.display_name";
                    break;
                default:
                    $uname_select = "$wpdb->users.display_name";
                    break;
            }
            /*---------------------------------------------------------------*/

            /*-------------- Additional Query parts by Filters --------------*/
            /**
             * Use these filters to add additional parts to the select, join or where
             * parts of the search query.
             */
            $add_select = apply_filters('asp_user_query_add_select', '');
            $add_join = apply_filters('asp_user_query_add_join', '');
            $add_where = apply_filters('asp_user_query_add_where', '');
            /*---------------------------------------------------------------*/

            if ( strpos($args['post_primary_order'], 'customfp') !== false )
                $orderby_primary = 'relevance DESC';
            else
                $orderby_primary = str_replace( "post_", "",  $args['post_primary_order'] );

            if ( strpos($args['post_secondary_order'], 'customfs') !== false )
                $orderby_secondary = 'date DESC';
            else
                $orderby_secondary = str_replace( "post_", "",  $args['post_secondary_order'] );

            $querystr = "
            SELECT
                $add_select
                $wpdb->users.ID as id,
                $this->c_blogid as blogid,
                $uname_select as title,
                $wpdb->users.user_registered as date,
                '' as author,
                '' as content,
                'user' as content_type,
                'users' as g_content_type,
                $relevance as relevance,
                $wpdb->users.user_login as user_login,
                $wpdb->users.user_nicename as user_nicename,
                $wpdb->users.display_name as user_display_name
            FROM
                $wpdb->users
                LEFT JOIN $wpdb->usermeta ON $wpdb->usermeta.user_id = $wpdb->users.ID
                $add_join
            WHERE
                (
                  $like_query
                  $bp_cf_select
                )
                $add_where
                $roles_query
                $exclude_query
                $include_query
            GROUP BY id
            ORDER BY $orderby_primary, $orderby_secondary
            LIMIT ".$query_limit;

            $userresults = $wpdb->get_results($querystr, OBJECT);
            $this->results_count = count($userresults);

            // For non-ajax search, results count needs to be limited to the maximum limit,
            // ..as nothing is parsed beyond that
            if ($args['_ajax_search'] == false && $this->results_count > $limit) {
                $this->results_count = $limit;
            }

            $userresults = array_slice($userresults, $args['_call_num'] * $limit, $limit);

            $this->results = $userresults;

            return $this->results;
        }


        /**
         * Post processing the user results
         */
        protected function post_process() {
            $userresults = is_array($this->results) ? $this->results : array();

            $args = &$this->args;

            if ( !isset($args['_sd']) )
                return $this->results;
            $sd = $args['_sd'];
            $com_options = wd_asp()->o['asp_compatibility'];

            foreach ($userresults as $k => $v) {

                if ( $args['_ajax_search'] ) {
                    // If no image and defined, remove the result here, to perevent JS confusions
                    if (empty($userresults[$k]->image) && $sd['resultstype'] == "isotopic" && $sd['i_ifnoimage'] == 'removeres') {
                        unset($userresults[$k]);
                        continue;
                    }
                    /* Same for polaroid mode */
                    if (empty($userresults[$k]->image) && isset($sd['resultstype']) &&
                        $sd['resultstype'] == 'polaroid' && $sd['pifnoimage'] == 'removeres'
                    ) {
                        unset($userresults[$k]);
                        continue;
                    }
                }

                /*--------------------------- Link ------------------------------*/
                switch ( $sd['user_search_url_source'] ) {
                    case "bp_profile":
                        if (function_exists('bp_core_get_user_domain'))
                            $userresults[$k]->link = bp_core_get_user_domain($v->id);
                        else
                            $userresults[$k]->link = get_author_posts_url($v->id);
                        break;
                    case "custom":
                        $userresults[$k]->link = str_replace(
                            array("{USER_ID}", "{USER_LOGIN}", "{USER_NICENAME}", "{USER_DISPLAYNAME}"),
                            array($v->id, $v->user_login, $v->user_nicename, $v->user_display_name),
                            $sd['user_search_custom_url']
                        );
                        break;
                    default:
                        $userresults[$k]->link = get_author_posts_url($v->id);
                }
                /*---------------------------------------------------------------*/

                /*-------------------------- Image ------------------------------*/
                if ( $sd['user_search_display_images'] ) {
                    if ( $sd['user_search_image_source'] == 'buddypress' &&
                         function_exists('bp_core_fetch_avatar') ) {

                        $im = bp_core_fetch_avatar(array('item_id' => $v->id, 'html' => false));
                        if ($im != '')
                            $userresults[$k]->image = $im;
                    } else {
                        $im = $this->get_avatar_url($v->id);
                        if ($im != '')
                            $userresults[$k]->image = $im;
                    }
                }
                /*---------------------------------------------------------------*/

                if ( !empty($sd['user_search_advanced_title_field']) )
                    $userresults[$k]->title = $this->adv_field(
                        array(
                            'main_field_slug' => 'titlefield',
                            'main_field_value'=> $v->title,
                            'r' => $v,
                            'field_pattern' => stripslashes( $sd['user_search_advanced_title_field'] )
                        ),
                        $com_options['use_acf_getfield']
                    );

                /*---------------------- Description ----------------------------*/
                switch ( $sd['user_search_description_field'] ) {
                    case 'buddypress_last_activity':
                        $update = get_user_meta($v->id, 'bp_latest_update', true);
                        if (is_array($update) && isset($update['content']))
                            $userresults[$k]->content = $update['content'];
                        if ($userresults[$k]->content != '') {
                            $userresults[$k]->content = wd_substr_at_word(strip_tags($userresults[$k]->content), $sd['descriptionlength']) . "...";
                        } else {
                            $userresults[$k]->content = "";
                        }
                        break;
                    case 'nothing':
                        $userresults[$k]->content = "";
                        break;
                    default:
                        $content = get_user_meta($v->id, 'description', true);
                        if ($content != '')
                            $userresults[$k]->content = $content;
                        if ($userresults[$k]->content != '') {
                            $userresults[$k]->content = wd_substr_at_word(strip_tags($userresults[$k]->content), $sd['descriptionlength']) . "...";
                        } else {
                            $userresults[$k]->content = "";
                        }
                }

                if ( !empty($sd['user_search_advanced_description_field']) )
                    $userresults[$k]->content = $this->adv_field(
                        array(
                            'main_field_slug' => 'descriptionfield',
                            'main_field_value'=> $v->content,
                            'r' => $v,
                            'field_pattern' => stripslashes( $sd['user_search_advanced_description_field'] )
                        ),
                        $com_options['use_acf_getfield']
                    );
                /*---------------------------------------------------------------*/

                // --------------------------------- DATE -----------------------------------
                if ($sd["showdate"] == 1) {
                    $post_time = strtotime($userresults[$k]->date);

                    if ( $sd['custom_date'] == 1) {
                        $date_format = w_isset_def($sd['custom_date_format'], "Y-m-d H:i:s");
                    } else {
                        $date_format = get_option('date_format', "Y-m-d") . " " . get_option('time_format', "H:i:s");
                    }

                    $userresults[$k]->date = @date_i18n($date_format, $post_time);
                }
                // --------------------------------------------------------------------------

            }

            $this->results = $userresults;

            return $userresults;
        }

        /**
         * Gets the avatar URL as a similar function is only supported in WP 4.2 +
         *
         * @param $user_id int the user ID
         * @param int $size int the size of the avatar
         * @return mixed
         */
        protected function get_avatar_url($user_id, $size = 96){
            $get_avatar = get_avatar($user_id, $size);
            preg_match('/src=(.*?) /i', $get_avatar, $matches);
	        if (isset($matches[1]))
                return str_replace(array('"',"'"), '', $matches[1]);
        }

        /**
         * Generates the final field, based on the advanced field pattern
         *
         * @uses ASP_Helpers::getUserCFValue()
         *
         * @param array     $f_args             Field related arguments
         * @param boolean   $use_acf            If true, uses ACF get_field() function to get the meta
         * @param boolean   $empty_on_missing   If true, returns an empty string if any of the fields is empty.
         *
         * @return string Final result title
         */
        protected function adv_field( $f_args, $use_acf = false, $empty_on_missing = false ) {
            $args = &$this->args;

            $f_args = wp_parse_args($f_args, array(
                'main_field_slug' => 'titlefield',  // The 'slug', aka the original field name
                'main_field_value'=> '',            // The default field value
                'r' => null,                        // Result object
                'field_pattern' => '{titlefield}'   // The field pattern
            ));
            $_f_args = $f_args;

            if ( $f_args['field_pattern'] == '' ) {
                return $f_args['field_value'];
            }
            $field_pattern = $f_args['field_pattern']; // Lets not make changes to arguments, shall we.

            // Find conditional patterns, like [prefix {field} suffix}
            preg_match_all( "/(\[.*?\])/", $field_pattern, $matches );
            if ( isset( $matches[0] ) && isset( $matches[1] ) && is_array( $matches[1] ) ) {
                foreach ( $matches[1] as $fieldset ) {
                    // Pass on each section to this function again, the code will never get here
                    $_f_args['field_pattern'] = str_replace(array('[', ']'), '', $fieldset);
                    $processed_fieldset = $this->adv_field(
                        $_f_args,
                        $use_acf,
                        true
                    );
                    // Replace the original with the processed version, first occurrence, in case of duplicates
                    $field_pattern = ASP_Helpers::replaceFirst($fieldset, $processed_fieldset, $field_pattern);
                }
            }

            preg_match_all( "/{(.*?)}/", $field_pattern, $matches );
            if ( isset( $matches[0] ) && isset( $matches[1] ) && is_array( $matches[1] ) ) {
                foreach ( $matches[1] as $field ) {
                    if ( $field == $f_args['main_field_slug'] ) {
                        $field_pattern = str_replace( '{'.$f_args['main_field_slug'].'}', $f_args['main_field_value'], $field_pattern );
                    } else {
                        $val        = ASP_Helpers::getUserCFValue($field, $f_args['r'], $use_acf, $args);
                        // For the recursive call to break, if any of the fields is empty
                        if ( $empty_on_missing && $val == '')
                            return '';
                        $field_pattern = str_replace( '{' . $field . '}', $val, $field_pattern );
                    }
                }
            }

            return $field_pattern;
        }

        /**
         * @param $parts
         * @param bool $is_multi
         * @return mixed
         */
        protected function build_query($parts, $is_multi = false) {
            $args = &$this->args;

            $l_parts = array();
            $r_parts = array();

            if ($is_multi == true) {
                foreach ($parts as $part) {
                    $l_parts = array_merge($l_parts, $part[0]);
                    $r_parts = array_merge($r_parts, $part[1]);
                }
            } else {
                $l_parts = $parts[0];
                $r_parts = $parts[1];
            }

            /*------------------------- Build like --------------------------*/
            $like_query = implode(' OR ', $l_parts);
            if ($like_query == "")
                $like_query = "(1)";
            else {
                $like_query = "($like_query)";
            }
            /*---------------------------------------------------------------*/

            /*---------------------- Build relevance ------------------------*/
            $relevance = implode(' + ', $r_parts);
            if ($args['_post_use_relevance'] != 1 || $relevance == "")
                $relevance = "(1)";
            else {
                $relevance = "($relevance)";
            }
            /*---------------------------------------------------------------*/


            return str_replace(
                array("{relevance_query}", "{like_query}", "{remaining_limit}"),
                array($relevance, $like_query, $this->remaining_limit),
                $this->query
            );

        }

    }
}