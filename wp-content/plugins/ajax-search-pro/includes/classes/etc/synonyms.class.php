<?php
/* Prevent direct access */
defined( 'ABSPATH' ) or die( "You can't access this file directly." );

if ( ! class_exists( 'asp_synonyms' ) ) {
    /**
     * Class operating the synonyms
     *
     * @class        asp_synonyms
     * @version        1.0
     * @package        AjaxSearchPro/Classes
     * @category      Class
     * @author        Ernest Marcinko
     */

    class asp_synonyms {

        /**
         * Core singleton class
         * @var asp_synonyms self
         */
        private static $_instance;

        private $_prefix;

        /**
         * Initialize and run the class
         */
        private function __construct() {
            global $wpdb;
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
}