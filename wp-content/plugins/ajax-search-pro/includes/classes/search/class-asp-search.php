<?php
/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

if (!class_exists('ASP_Search')) {
    /**
     * Search class Abstract
     *
     * All search classes should be descendants to this abstract.
     *
     * @class       ASP_Search
     * @version     2.0
     * @package     AjaxSearchPro/Abstracts
     * @category    Class
     * @author      Ernest Marcinko
     */
    abstract class ASP_Search {

        // Total results count (unlimited)
        public $results_count = 0;

        // Actual results count, results which are returned
        public $return_count = 0;

        /**
         * @var array of parameters
         */
        protected $args;

        protected $pre_field = '';
        protected $suf_field = '';
        protected $pre_like  = '';
        protected $suf_like  = '';

        /**
         * @var int the remaining limit (number of items to look for)
         */
        protected $remaining_limit;
        /**
         * @var int the start of the limit
         */
        protected $limit_start = 0;
        /**
         * @var int remaining limit modifier
         */
        protected $remaining_limit_mod = 10;

        /**
         * @var array of submitted options from the front end
         */
        protected $options;
        /**
         * @var int the ID of the current search instance
         */
        protected $searchId;
        /**
         * @var array of the current search options
         */
        protected $searchData;
        /**
         * @var array of results
         */
        protected $results;
        /**
         * @var string the search phrase
         */
        protected $s;
        /**
         * @var array of each search phrase
         */
        protected $_s;
        /**
         * @var string the reversed search phrase
         */
        protected $sr;
        /**
         * @var array of each reversed search phrase
         */
        protected $_sr;

        /**
         * @var int the current blog ID
         */
        protected $c_blogid;

        /**
         * Create the class
         *
         * @param $params
         */
        public function __construct($args) {
            $this->args = $args;

            if ( isset($args['_remaining_limit_mod']) )
                $this->remaining_limit_mod = $args['_remaining_limit_mod'];

            $this->c_blogid = get_current_blog_id();
        }

        /**
         * Initiates the search operation
         *
         * @return array
         */
        public function search( $s = false ) {

            $this->prepare_keywords($s);
            $this->do_search();
            $this->post_process();

            return is_array($this->results) ? $this->results : array();
        }

        public function prepare_keywords($s) {
            if ( $s !== false )
                $keyword = $s;
            else
                $keyword = $this->args['s'];

            $keyword = $this->compatibility($keyword);
            $keyword_rev = ASP_Helpers::reverseString($keyword);

            $this->s = ASP_Helpers::escape( $keyword );
            $this->sr = ASP_Helpers::escape( $keyword_rev );

            // Avoid double escape, explode the $keyword instead of $this->s
            $this->_s = ASP_Helpers::escape( array_slice(array_unique( explode(" ", $keyword) ), 0, $this->args['_keyword_count_limit']) );
            $this->_sr = ASP_Helpers::escape( array_slice(array_unique( array_reverse( explode(" ", $keyword_rev ) ) ), 0, $this->args['_keyword_count_limit']) );

            foreach ($this->_s as $k=>$w) {
                if ( ASP_mb::strlen($w) < $this->args['min_word_length'] ) {
                    unset($this->_s[$k]);
                }
            }

            foreach ($this->_sr as $k=>$w) {
                if ( ASP_mb::strlen($w) < $this->args['min_word_length'] ) {
                    unset($this->_sr[$k]);
                }
            }
        }

        /**
         * The search function
         */
        protected function do_search() {}

        /**
         * Post processing abstract
         */
        protected function post_process() {

            if (is_array($this->results) && count($this->results) > 0) {
                foreach ($this->results as $k => $v) {

                    $r = & $this->results[$k];

                    if (!is_object($r) || count($r) <= 0) continue;
                    if (!isset($r->id)) $r->id = 0;
                    $r->image = isset($r->image) ? $r->image : "";
                    $r->title = apply_filters('asp_result_title_before_prostproc', $r->title, $r->id);
                    $r->content = apply_filters('asp_result_content_before_prostproc', $r->content, $r->id);
                    $r->image = apply_filters('asp_result_image_before_prostproc', $r->image, $r->id);
                    $r->author = apply_filters('asp_result_author_before_prostproc', $r->author, $r->id);
                    $r->date = apply_filters('asp_result_date_before_prostproc', $r->date, $r->id);


                    $r->title = apply_filters('asp_result_title_after_prostproc', $r->title, $r->id);
                    $r->content = apply_filters('asp_result_content_after_prostproc', $r->content, $r->id);
                    $r->image = apply_filters('asp_result_image_after_prostproc', $r->image, $r->id);
                    $r->author = apply_filters('asp_result_author_after_prostproc', $r->author, $r->id);
                    $r->date = apply_filters('asp_result_date_after_prostproc', $r->date, $r->id);

                }
            }

        }

        /**
         * Converts the keyword to the correct case and sets up the pre-suff fields.
         *
         * @param $s
         * @return string
         */
        protected function compatibility($s) {
            /**
             *  On forced case sensitivity: Let's add BINARY keyword before the LIKE
             *  On forced case in-sensitivity: Append the lower() function around each field
             */
            if ( $this->args['_db_force_case'] === 'sensitivity' ) {
                $this->pre_like = 'BINARY ';
            } else if ( $this->args['_db_force_case'] === 'insensitivity' ) {
                if ( function_exists( 'mb_convert_case' ) )
                    $s = mb_convert_case( $s, MB_CASE_LOWER, "UTF-8" );
                else
                    $s = strtoupper( $s );
                // if no mb_ functions :(

                $this->pre_field .= 'lower(';
                $this->suf_field .= ')';
            }

            /**
             *  Check if utf8 is forced on LIKE
             */
            if ( w_isset_def( $this->args['_db_force_utf8_like'], 0 ) == 1 )
                $this->pre_like .= '_utf8';

            /**
             *  Check if unicode is forced on LIKE, but only apply if utf8 is not
             */
            if ( w_isset_def( $this->args['_db_force_unicode'], 0 ) == 1
                && w_isset_def( $this->args['_db_force_utf8_like'], 0 ) == 0
            )
                $this->pre_like .= 'N';

            return $s;
        }

        /**
         * Returns the context of a phrase within a text.
         * Uses preg_split method to iterate through strings.
         *
         * @uses ASP_mb
         * @uses wd_substr_at_word()
         * @param $str string context
         * @param $needle string context
         * @param $context int length of the context
         * @param $maxlength int maximum length of the string in characters
         * @param $str_length_limit source string maximum length
         * @return string
         */
        public function context_find($str, $needle, $context, $maxlength, $str_length_limit = 10000, $false_on_no_match = false) {
            $haystack = ' '.trim($str).' ';

            // To prevent memory overflow, we need to limit the hay to relatively low count
            $haystack = wd_substr_at_word(ASP_mb::strtolower($haystack), $str_length_limit);
            $needle = ASP_mb::strtolower($needle);

            if ( $needle == "" ) return $str;

            /**
             * This is an interesting issue. Turns out mb_substr($hay, $start, 1) is very ineffective.
             * the preg_split(..) method is far more efficient in terms of speed, however it needs much more
             * memory. In our case speed is the top priority. However to prevent memory overflow, the haystack
             * is reduced to 10000 characters (roughly 1500 words) first.
             *
             * Reference ticket: https://wp-dreams.com/forums/topic/search-speed/
             * Speed tests: http://stackoverflow.com/questions/3666306/how-to-iterate-utf-8-string-in-php
             */
            $chrArray = preg_split('//u', $haystack, -1, PREG_SPLIT_NO_EMPTY);
            $hay_length = count($chrArray) - 1;

            if ( $i = ASP_mb::strpos($haystack, $needle) ) {
                $start=$i;
                $end=$i;
                $spaces=0;

                while ($spaces < ((int) $context/2) && $start > 0) {
                    $start--;
                    if ($chrArray[$start] == ' ') {
                        $spaces++;
                    }
                }

                while ($spaces < ($context +1) && $end < $hay_length) {
                    $end++;
                    if ($chrArray[$end] == ' ') {
                        $spaces++;
                    }
                }

                while ($spaces < ($context +1) && $start > 0) {
                    $start--;
                    if ($chrArray[$start] == ' ') {
                        $spaces++;
                    }
                }

                $str_start = ($start - 1) < 0 ? 0 : ($start -1);
                $str_end = ($end - 1) < 0 ? 0 : ($end -1);

                $result = trim( ASP_mb::substr($str, $str_start, ($str_end - $str_start)) );

                // Somewhere inbetween..
                if ( $start != 0 && $end < $hay_length )
                    return "..." . $result . "...";

                // Beginning
                if ( $start == 0 && $end < $hay_length )
                    return $result . "...";

                // End
                if ( $start != 0 && $end == $hay_length )
                    return "..." . $result;

                // If it is too long, strip it
                if ( ASP_mb::strlen($result) > $maxlength)
                    return wd_substr_at_word( $result, $maxlength ) . "...";

                // Else, it is the whole
                return $result;

            } else {
                if ( $false_on_no_match )
                    return false;

                // If it is too long, strip it
                if ( ASP_mb::strlen($str) > $maxlength)
                    return wd_substr_at_word( $str, $maxlength ) . "...";

                return $str;
            }
        }
    }
}