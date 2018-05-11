<?php
if (!class_exists("wpdreamsInfo")) {
    /**
     * Class wpdreamsInfo
     *
     * DEPRECATED
     *
     * @deprecated
     * @package  WPDreams/OptionsFramework/Classes
     * @category Class
     * @author Ernest Marcinko <ernest.marcinko@wp-dreams.com>
     * @link http://wp-dreams.com, http://codecanyon.net/user/anago/portfolio
     * @copyright Copyright (c) 2014, Ernest Marcinko
     */
    class wpdreamsInfo extends wpdreamsType {
        function __construct($data) {
            $this->data = $data;
            $this->getType();
        }

        function getType() {
            echo "<img style='display:none;' class='infoimage' src='" . plugins_url('../types/icons/info.png', __FILE__) . "' title='" . $this->data . "' />";
        }
    }
}