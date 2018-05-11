<?php
if (!defined('ABSPATH')) die('-1');

if (!class_exists("WD_ASP_IndexTable_Handler")) {
    /**
     * Class WD_MS_IndexTable_Handler
     *
     * Index Table requests handler
     *
     * @class         WD_ASP_IndexTable_Handler
     * @version       1.0
     * @package       AjaxSearchPro/Classes/Ajax
     * @category      Class
     * @author        Ernest Marcinko
     */
    class WD_ASP_IndexTable_Handler extends WD_ASP_Handler_Abstract {

        /**
         * This function handles the index table ajax requests
         */
        public function handle() {

            if (isset($_POST['data'])) {
                if (is_array($_POST['data']))
                    $options = $_POST['data'];
                else
                    parse_str($_POST['data'], $options);
            } else {
                print "No post data detected, function terminated.";
                die();
            }

            update_option("asp_recreate_index", 0);

            $it_obj = new asp_indexTable(array(
                'index_title' => $options['it_index_title'],
                'index_content' => $options['it_index_content'],
                'index_excerpt' => $options['it_index_excerpt'],
                'index_tags' => $options['it_index_tags'],
                'index_categories' => $options['it_index_categories'],
                'post_types' => $options['it_post_types'],
                'post_statuses' => $options['it_post_statuses'],
                'index_taxonomies' =>$options['it_index_taxonomies'],
                'index_permalinks' =>$options['it_index_permalinks'],
                'index_customfields' => $options['it_index_customfields'],
                'index_author_name'  => $options['it_index_author_name'],
                'index_author_bio'   => $options['it_index_author_bio'],
                'blog_id' => $_POST['blog_id'],
                'extend' => (w_isset_def($_POST['asp_index_action'], 'new') == 'extend' ? 1 : 0),
                'limit'  => $options['it_limit'],
                'use_stopwords' => $options['it_use_stopwords'],
                'stopwords' => $options['it_stopwords'],
                'min_word_length' => $options['it_min_word_length'],
                'extract_shortcodes' => $options['it_extract_shortcodes'],
                'exclude_shortcodes' => $options['it_exclude_shortcodes']
            ));
            if (isset($_POST['asp_index_action'])) {
                switch ($_POST['asp_index_action']) {
                    case 'new':
                        $ret = $it_obj->newIndex();
                        print "New index !!!ASP_INDEX_START!!!";
                        print_r(json_encode($ret));
                        print "!!!ASP_INDEX_STOP!!!";
                        die();
                        break;
                    case 'extend':
                        $ret = $it_obj->extendIndex();
                        print "Extend index !!!ASP_INDEX_START!!!";
                        print_r(json_encode($ret));
                        print "!!!ASP_INDEX_STOP!!!";
                        die();
                        break;
                    case 'switching_blog':
                        $ret = $it_obj->extendIndex(true);
                        print "Extend index (blog_switch) !!!ASP_INDEX_START!!!";
                        print_r(json_encode($ret));
                        print "!!!ASP_INDEX_STOP!!!";
                        die();
                        break;
                    case 'delete':
                        $ret = $it_obj->emptyIndex();
                        print "Delete index !!!ASP_INDEX_START!!!";
                        print_r(json_encode($ret));
                        print "!!!ASP_INDEX_STOP!!!";
                        die();
                        break;
                }
            }
            // no action set, or other failure
            print "No action !!!ASP_INDEX_START!!!0!!!ASP_INDEX_STOP!!!";
            die();
        }

        // ------------------------------------------------------------
        //   ---------------- SINGLETON SPECIFIC --------------------
        // ------------------------------------------------------------
        public static function getInstance() {
            if ( ! ( self::$_instance instanceof self ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }
    }
}