<?php
if (!class_exists("wpdreamsTextareaIsParam")) {
    /**
     * Class wpdreamsSelect
     *
     * A simple textarea field.
     *
     * @package  WPDreams/OptionsFramework/Classes
     * @category Class
     * @author Ernest Marcinko <ernest.marcinko@wp-dreams.com>
     * @link http://wp-dreams.com, http://codecanyon.net/user/anago/portfolio
     * @copyright Copyright (c) 2014, Ernest Marcinko
     */
    class wpdreamsTextareaIsParam extends wpdreamsType {
        function getType() {
            parent::getType();
            echo "<div class='wpdreamsTextareaIsParam'>";
            echo "<label style='vertical-align: top;' for='wpdreamstextarea_" . self::$_instancenumber . "'>" . $this->label . "</label>";
            echo "<textarea isparam=1 id='wpdreamstextarea_" . self::$_instancenumber . "' name='" . $this->name . "'>" . stripcslashes($this->data) . "</textarea>";
            echo "<div class='triggerer'></div>";
            echo "</div>";
        }
    }
}