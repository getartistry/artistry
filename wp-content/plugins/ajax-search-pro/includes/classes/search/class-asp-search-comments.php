<?php
/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

if (!class_exists('ASP_Search_COMMENTS')) {
    /**
     * Comment search class
     *
     * @class       ASP_Search_COMMENTS
     * @version     1.0
     * @package     AjaxSearchPro/Abstracts
     * @category    Class
     * @author      Ernest Marcinko
     */
    class ASP_Search_COMMENTS extends ASP_Search_CPT {

        /**
         * The search function
         *
         * @return array of results
         */
        protected function do_search() {
            global $wpdb;
            $commentsresults = array();

            $args = &$this->args;

            if ( isset($args["_sd"]) )
                $sd = &$args["_sd"];
            else
                $sd = array();

            $s = $this->s;
            $_s = $this->_s;

            if ( $args['_limit'] > 0 ) {
                $limit = $args['_limit'];
            } else {
                if ( $args['_ajax_search'] )
                    $limit = $args['comments_limit'];
                else
                    $limit = $args['comments_limit_override'];
            }
            $query_limit = $limit * 3;

            if ($limit <= 0)
                return array();

            // Prefixes and suffixes
            $pre_field = '';
            $suf_field = '';
            $pre_like = '';
            $suf_like = '';
            $wcl = '%'; // Wildcard Left
            $wcr = '%'; // Wildcard right
            if ( $args["_exact_matches"] == 1 ) {
                if ( $args['_exact_match_location'] == 'start' )
                    $wcl = '';
                else if ( $args['_exact_match_location'] == 'end' )
                    $wcr = '';
            }

            $kw_logic = $args['keyword_logic'];

            if (count($_s) > 0) {


                // ------------------------ Categories/taxonomies ----------------------
                $term_query = $this->build_term_query( $wpdb->comments.".comment_post_ID", 'comment_post_type' );
                // ---------------------------------------------------------------------

                /*------------- Custom Fields with Custom selectors -------------*/
                $cf_select = $this->build_cff_query( $wpdb->comments.".comment_post_ID" );
                /*---------------------------------------------------------------*/

                /*----------------------- Date filtering ------------------------*/
                $date_query = "";
                $date_query_parts = $this->get_date_query_parts( $wpdb->comments, "comment_date" );

                if ( count($date_query_parts) > 0 )
                    $date_query = " AND (" . implode(" AND ", $date_query_parts).") ";
                /*---------------------------------------------------------------*/

                /*---------------------- Content query --------------------------*/

                $like_part = "";
                $relevance_part = "1";
                $words = $args["_exact_matches"] == 1 ? array($s) : $_s;
                if ( $s != "" ) {
                    if ($kw_logic == 'or' || $kw_logic == 'and') {
                        $op = strtoupper($kw_logic);
                        if (count($_s) > 0) {
                            $_like = implode("%'$suf_like " . $op . " " . $pre_field . $wpdb->comments . ".comment_content" . $suf_field . " LIKE $pre_like'%", $words);
                        } else {
                            $_like = $s;
                        }
                        $like_part = "AND ( " . $pre_field . $wpdb->comments . ".comment_content" . $suf_field . " LIKE $pre_like'$wcl" . $_like . "$wcr'$suf_like )";
                    } else {
                        $_like = array();
                        $op = $kw_logic == 'andex' ? 'AND' : 'OR';
                        foreach ($words as $word) {
                            $_like[] = "
                       (" . $pre_field . $wpdb->comments . ".comment_content" . $suf_field . " LIKE $pre_like'% " . $word . " %'$suf_like
                    OR  " . $pre_field . $wpdb->comments . ".comment_content" . $suf_field . " LIKE $pre_like'" . $word . " %'$suf_like
                    OR  " . $pre_field . $wpdb->comments . ".comment_content" . $suf_field . " LIKE $pre_like'% " . $word . "'$suf_like
                    OR  " . $pre_field . $wpdb->comments . ".comment_content" . $suf_field . " = '" . $word . "')";
                        }
                        $like_part = "AND (" . implode(' ' . $op . ' ', $_like) . ")";
                    }

                    if (count($_s) > 0) {
                        $relevance_part = "(case when
                (" . $pre_field . $wpdb->comments . ".comment_content" . $suf_field . " LIKE '%" . $_s[0] . "%')
                 then ".w_isset_def($sd['contentweight'], 10)." else 0 end)";
                    }
                }

                /*---------------------------------------------------------------*/

                /*----------------------- Exclude by id -----------------------*/
                $exclude_part = "";
                if ( isset($args['post_user_filter']['include']) ) {
                    if ( !in_array(-1, $args['post_user_filter']['include']) )
                        $user_query = "AND $wpdb->comments.user_id IN (".implode(", ", $args['post_user_filter']['include']).")
                    ";
                }
                /*---------------------------------------------------------------*/

                /*------------------------ Exclude ids --------------------------*/
                if ( !empty($args['post_not_in']) )
                    $exclude_posts = "AND ($wpdb->comments.comment_post_ID NOT IN (".(is_array($args['post_not_in']) ? implode(",", $args['post_not_in']) : $args['post_not_in'])."))";
                else
                    $exclude_posts = "";
                if ( !empty($args['post_not_in2']) )
                    $exclude_posts .= "AND ($wpdb->comments.comment_post_ID NOT IN (".implode(",", $args['post_not_in2'])."))";
                /*---------------------------------------------------------------*/

                /*------------------------ Include ids --------------------------*/
                if ( !empty($args['post_in']) )
                    $include_posts = "AND ($wpdb->comments.comment_post_ID IN (".(is_array($args['post_in']) ? implode(",", $args['post_in']) : $args['post_in'])."))";
                else
                    $include_posts = "";
                /*---------------------------------------------------------------*/

                /*----------------------- Exclude USER id -----------------------*/
                $user_query = "";
                if ( isset($args['post_user_filter']['include']) ) {
                    if ( !in_array(-1, $args['post_user_filter']['include']) )
                        $user_query = "AND $wpdb->comments.user_id IN (".implode(", ", $args['post_user_filter']['include']).")
                    ";
                }
                if ( isset($args['post_user_filter']['exclude']) ) {
                    if ( !in_array(-1, $args['post_user_filter']['exclude']) )
                        $user_query = "AND $wpdb->comments.user_id NOT IN (".implode(", ", $args['post_user_filter']['exclude']).") ";
                    else
                        return array();
                }
                /*---------------------------------------------------------------*/

                if ( strpos($args['post_primary_order'], 'customfp') !== false )
                    $orderby_primary = 'relevance DESC';
                else
                    $orderby_primary = str_replace('post_', '', $args['post_primary_order']);

                if ( strpos($args['post_secondary_order'], 'customfs') !== false )
                    $orderby_secondary = 'date DESC';
                else
                    $orderby_secondary = str_replace('post_', '', $args['post_secondary_order']);

                $querystr = "
      		SELECT 
            $wpdb->comments.comment_ID as id,
            $this->c_blogid as blogid,
            $wpdb->comments.comment_post_ID as post_id,
            'post' as comment_post_type,
            $wpdb->comments.user_id as user_id,
            $wpdb->comments.comment_content as title,
            $wpdb->comments.comment_content as content,
            'comment' as content_type,
            'comments' as g_content_type,
            $wpdb->comments.comment_date as date,
            $wpdb->comments.user_id as user_id,
            $relevance_part as relevance
      		FROM $wpdb->comments
      		WHERE
          ($wpdb->comments.comment_approved=1)
          $term_query
          AND $cf_select
          $date_query
          $user_query
          $like_part
          $exclude_posts
          $include_posts
          $exclude_part
      		ORDER BY $orderby_primary, $orderby_secondary
      		LIMIT " . $query_limit;
                $commentsresults = $wpdb->get_results($querystr, OBJECT);

            }
            $this->results_count = count($commentsresults);

            // For non-ajax search, results count needs to be limited to the maximum limit,
            // ..as nothing is parsed beyond that
            if ($args['_ajax_search'] == false && $this->results_count > $limit) {
                $this->results_count = $limit;
            }

            $commentsresults = array_slice($commentsresults, $args['_call_num'] * $limit, $limit);
            $this->results = &$commentsresults;
            $this->return_count = count($this->results);

            return $commentsresults;
        }

        public function post_process() {
            $r = &$this->results;
            $args = &$this->args;

            if ( isset($args["_sd"]) )
                $sd = &$args["_sd"];
            else
                $sd = array();

            if (is_array($r)) {
                foreach ($r as $k => $v) {
                    $r[$k]->link = get_comment_link($v->id);
                    $r[$k]->author = get_comment_author($v->id);

                    $r[$k]->title = wd_substr_at_word($r[$k]->content, 40) . "...";

                    /* Remove the results in polaroid mode */
                    if ($args['_ajax_search'] && empty($r->image) && isset($sd['resultstype']) &&
                        $sd['resultstype'] == 'polaroid' && $sd['pifnoimage'] == 'removeres') {
                        unset($this->results[$k]);
                        continue;
                    }

                    // --------------------------------- DATE -----------------------------------
                    if ( isset($sd["showdate"]) && $sd["showdate"] == 1 ) {
                        $post_time = strtotime($r[$k]->date);

                        if ( $sd['custom_date'] == 1) {
                            $date_format = w_isset_def($sd['custom_date_format'], "Y-m-d H:i:s");
                        } else {
                            $date_format = get_option('date_format', "Y-m-d") . " " . get_option('time_format', "H:i:s");
                        }

                        $r[$k]->date = @date_i18n($date_format, $post_time);
                    }
                    // --------------------------------------------------------------------------
                }
            }
        }

    }
}