<?php
/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

if (!class_exists('ASP_Search_TERMS')) {
    /**
     * Search class ASP_Search_TERMS
     *
     * Term search class
     *
     * @class       ASP_Search_TERMS
     * @version     1.1
     * @package     AjaxSearchPro/Classes
     * @category    Class
     * @author      Ernest Marcinko
     */
    class ASP_Search_TERMS extends ASP_Search {

        /**
         * The search function
         *
         * @return array of results
         */
        protected function do_search() {
            global $wpdb;
            global $q_config;

            $args = &$this->args;

            if ( isset($args["_sd"]) )
                $sd = &$args["_sd"];
            else
                $sd = array();

            $parts = array();
            $relevance_parts = array();
            $types = array();

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

            $kw_logic             = $args['keyword_logic'];
            $q_config['language'] = $args['_qtranslate_lang'];

            $s = $this->s;      // full keyword
            $_s = $this->_s;    // array of keywords

            $words = $args["_exact_matches"] == 1 ? array( $s ) : $_s;

            if ( $args['_limit'] > 0 ) {
                $limit = $args['_limit'];
            } else {
                if ( $args['_ajax_search'] )
                    $limit = $args['taxonomies_limit'];
                else
                    $limit = $args['taxonomies_limit_override'];
            }
            $query_limit = $limit * 3;

            /*----------------------- Gather Types --------------------------*/
            $taxonomies = "( $wpdb->term_taxonomy.taxonomy IN ('".implode("','", $args['taxonomy_include'])."') )";
            /*---------------------------------------------------------------*/

            /*----------------------- Title query ---------------------------*/
            if ( $s != "" ) {
                if ($kw_logic == 'or' || $kw_logic == 'and') {
                    $op = strtoupper($kw_logic);
                    if (count($_s) > 0)
                        $_like = implode("%'$suf_like " . $op . " " . $pre_field . $wpdb->terms . ".name" . $suf_field . " LIKE $pre_like'%", $words);
                    else
                        $_like = $s;
                    $parts[] = "( " . $pre_field . $wpdb->terms . ".name" . $suf_field . " LIKE $pre_like'$wcl" . $_like . "$wcr'$suf_like )";
                } else {
                    $_like = array();
                    $op = $kw_logic == 'andex' ? 'AND' : 'OR';
                    foreach ($words as $word) {
                        $_like[] = "
                           (" . $pre_field . $wpdb->terms . ".name" . $suf_field . " LIKE $pre_like'% " . $word . " %'$suf_like
                        OR  " . $pre_field . $wpdb->terms . ".name" . $suf_field . " LIKE $pre_like'" . $word . " %'$suf_like
                        OR  " . $pre_field . $wpdb->terms . ".name" . $suf_field . " LIKE $pre_like'% " . $word . "'$suf_like
                        OR  " . $pre_field . $wpdb->terms . ".name" . $suf_field . " = '" . $word . "')";
                    }
                    $parts[] = "(" . implode(' ' . $op . ' ', $_like) . ")";
                }

                if ( count( $_s ) > 0 ) {
                    $relevance_parts[] = "(case when
                        (" . $pre_field . $wpdb->terms . ".name" . $suf_field . " LIKE '%" . $_s[0] . "%')
                         then ".w_isset_def($sd['titleweight'], 10)." else 0 end)";
                }
                $relevance_parts[] = "(case when
                    (" . $pre_field . $wpdb->terms . ".name" . $suf_field . " LIKE '$s%')
                     then ".(w_isset_def($sd['etitleweight'], 10) * 2)." else 0 end)";
                $relevance_parts[] = "(case when
                    (" . $pre_field . $wpdb->terms . ".name" . $suf_field . " LIKE '%$s%')
                     then ".w_isset_def($sd['etitleweight'], 10)." else 0 end)";
            }
            /*---------------------------------------------------------------*/

            /*--------------------- Description query -----------------------*/
            if ( $s != "" && $args['taxonomy_terms_search_description']) {
                if ($kw_logic == 'or' || $kw_logic == 'and') {
                    $op = strtoupper($kw_logic);
                    if (count($_s) > 0)
                        $_like = implode("%'$suf_like " . $op . " " . $pre_field . $wpdb->term_taxonomy . ".description" . $suf_field . " LIKE $pre_like'%", $words);
                    else
                        $_like = $s;
                    $parts[] = "( " . $pre_field . $wpdb->term_taxonomy . ".description" . $suf_field . " LIKE $pre_like'$wcl" . $_like . "$wcr'$suf_like )";
                } else {
                    $_like = array();
                    $op = $kw_logic == 'andex' ? 'AND' : 'OR';
                    foreach ($words as $word) {
                        $_like[] = "
                           (" . $pre_field . $wpdb->term_taxonomy . ".description" . $suf_field . " LIKE $pre_like'% " . $word . " %'$suf_like
                        OR  " . $pre_field . $wpdb->term_taxonomy . ".description" . $suf_field . " LIKE $pre_like'" . $word . " %'$suf_like
                        OR  " . $pre_field . $wpdb->term_taxonomy . ".description" . $suf_field . " LIKE $pre_like'% " . $word . "'$suf_like
                        OR  " . $pre_field . $wpdb->term_taxonomy . ".description" . $suf_field . " = '" . $word . "')";
                    }
                    $parts[] = "(" . implode(' ' . $op . ' ', $_like) . ")";
                }
                $relevance_parts[] = "(case when
                    (" . $pre_field . $wpdb->term_taxonomy . ".description" . $suf_field . " LIKE '%$s%')
                     then ".w_isset_def($sd['contentweight'], 8)." else 0 end)";
            }
            /*---------------------------------------------------------------*/

            /*------------------------ Exclude id's -------------------------*/
            $exclude_terms = "";
            if ( !empty($args['taxonomy_terms_exclude']) ) {
                $exclude_terms = " AND ($wpdb->terms.term_id NOT IN (" .(is_array($args['taxonomy_terms_exclude']) ? implode(",", $args['taxonomy_terms_exclude']) : $args['taxonomy_terms_exclude']). "))";
            }
            if ( !empty($args['taxonomy_terms_exclude2']) ) {
                $exclude_terms .= " AND ($wpdb->terms.term_id NOT IN (" .implode(",", $args['taxonomy_terms_exclude2']). "))";
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

            /*------------------------- WPML filter -------------------------*/
	        // New sub-select method instead of join
	        $wpml_query = "(1)";
	        if ( $args['_wpml_lang'] != "" )
		        $wpml_query = "
				EXISTS (
					SELECT DISTINCT(wpml.element_id)
					FROM " . $wpdb->prefix . "icl_translations as wpml
					WHERE
	                    $wpdb->terms.term_id = wpml.element_id AND
	                    wpml.language_code = '" . ASP_Helpers::escape($args['_wpml_lang']) . "' AND
	                    wpml.element_type LIKE 'tax_%'
                )";
            /*---------------------------------------------------------------*/

            /*-------------- Additional Query parts by Filters --------------*/
            /**
             * Use these filters to add additional parts to the select, join or where
             * parts of the search query.
             */
            $add_select = apply_filters('asp_term_query_add_select', '');
            $add_join = apply_filters('asp_term_query_add_join', '');
            $add_where = apply_filters('asp_term_query_add_where', '');
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
    		  $add_select
              $wpdb->terms.name as title,
              $wpdb->terms.term_id as id,
              $this->c_blogid as blogid,
              $wpdb->term_taxonomy.description as content,
              '' as date,
              '' as author,
              $wpdb->term_taxonomy.taxonomy as taxonomy,
              'term' as content_type,
              'terms' as g_content_type,
              $relevance as relevance
    		FROM
    		  $wpdb->terms
              LEFT JOIN $wpdb->term_taxonomy ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id
              $add_join
            WHERE
                $taxonomies
                AND $like_query
                $exclude_terms
                AND $wpml_query
                $add_where
            GROUP BY $wpdb->terms.term_id
            ";
            $querystr .= " ORDER BY $orderby_primary, $orderby_secondary, $wpdb->terms.name ASC
        LIMIT " . $query_limit;

            $term_res = $wpdb->get_results($querystr, OBJECT);
            $this->results_count = count($term_res);

            // For non-ajax search, results count needs to be limited to the maximum limit,
            // ..as nothing is parsed beyond that
            if ($args['_ajax_search'] == false && $this->results_count > $limit) {
                $this->results_count = $limit;
            }

            $term_res = array_slice($term_res, $args['_call_num'] * $limit, $limit);

            $this->results = $term_res;

            return $term_res;

        }

        /**
         * Post-processing the results
         *
         * @return array
         */
        protected function post_process() {
            $args = &$this->args;
            $s = $this->s;      // full keyword
            $_s = $this->_s;    // array of keywords

            if ( !isset($args['_sd']) )
                $sd = array();
            else
                $sd = $args['_sd'];

            $term_res = is_array($this->results)?$this->results:array();

	        // Get term affected post count if enabled
	        if ( w_isset_def($sd["display_number_posts_affected"], 0) == 1 ) {
		        foreach ($term_res as $k=>$v) {
			        $term = get_term_by('id', $v->id, $v->taxonomy);
			        $term_res[$k]->title .= " (" . $term->count . ")";
		        }
	        }


            /* WooCommerce Term image integration */
            if (function_exists('get_woocommerce_term_meta')) {
                foreach($term_res as $k => $result) {
                    if ( !empty($result->image) ) continue;

                    $thumbnail_id = get_woocommerce_term_meta( $result->id, 'thumbnail_id', true );
                    $image = wp_get_attachment_url( $thumbnail_id );
                    if (!empty($image))
                        $term_res[$k]->image = $image;
                }
            }

            /**
             * Do this here, so the term image might exist.
             * If you move this loop up, then the WooImage script might not work with isotope
             */
            foreach ($term_res as $k=>$v) {

                if ( $args["_ajax_search"]) {
                    // If no image and defined, remove the result here, to perevent JS confusions
                    if (isset($sd['resultstype']) && empty($term_res[$k]->image) && $sd['resultstype'] == "isotopic" && $sd['i_ifnoimage'] == 'removeres') {
                        unset($term_res[$k]);
                        continue;
                    }
                    /* Same for polaroid mode */
                    if (empty($v->image) && isset($sd['resultstype']) &&
                        $sd['resultstype'] == 'polaroid' && $sd['pifnoimage'] == 'removeres'
                    ) {
                        unset($term_res[$k]);
                        continue;
                    }
                }

                // ------------------------ CONTENT & CONTEXT --------------------------
                // Get the words from around the search phrase, or just the description
                $_content = $v->content;
                $_description_context = isset($sd['description_context']) ? $sd['description_context'] : 0;
                $_description_context_depth = isset($sd['description_context_depth']) ? $sd['description_context_depth'] : 10000;
                $_descriptionlength = isset($sd['descriptionlength']) ? $sd['descriptionlength'] : 220;
                $_content = strip_tags( $_content, $sd['striptagsexclude'] );
                if ( $_description_context == 1 && count( $_s ) > 0 ) {
                    // Try for an exact match
                    $_ex_content = $this->context_find(
                        $_content, $s,
                        floor($_descriptionlength / 6),
                        $_descriptionlength,
                        $_description_context_depth,
                        true
                    );
                    if ( $_ex_content === false ) {
                        // No exact match, go with the first keyword
                        $_content = $this->context_find(
                            $_content, $_s[0],
                            floor($_descriptionlength / 6),
                            $_descriptionlength,
                            $_description_context_depth
                        );
                    } else {
                        $_content = $_ex_content;
                    }
                } else if ( $_content != '' && (  ASP_mb::strlen( $_content ) > $_descriptionlength ) ) {
                    $_content = wd_substr_at_word($_content, $_descriptionlength) . "...";
                }
                $_content   = wd_closetags( $_content );
                $term_res[$k]->content = $_content;
                // ---------------------------------------------------------------------

                $term_url = get_term_link( (int)$v->id, $v->taxonomy);

                // In case of unset taxonomy term
                if ( !is_wp_error($term_url) )
                    $term_res[$k]->link = $term_url;
                else
                    unset($term_res[$k]);
            }

            $this->results = $term_res;

            return $term_res;

        }

    }
}