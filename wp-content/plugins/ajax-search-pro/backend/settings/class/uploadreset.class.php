<?php
if (!class_exists("wpdreamsUploadReset")) {
    /**
     * Class wpdreamsUploadReset
     *
     * DEPRECATED
     *
     * @deprecated
     * @author Ernest Marcinko <ernest.marcinko@wp-dreams.com>
     * @link http://wp-dreams.com, http://codecanyon.net/user/anago/portfolio
     * @copyright Copyright (c) 2014, Ernest Marcinko
     */
    class wpdreamsUploadReset extends wpdreamsType {
        function __construct($name, $label, $data, $default_data, $constraints = null, $errormsg = "") {
            $this->name = $name;
            $this->label = $label;
            $this->constraints = $constraints;
            $this->errormsg = $errormsg;
            $this->data = $data;
            $this->default_data = $default_data;
            $this->isError = false;
            self::$_instancenumber++;
            $this->getType();
        }

        function getType() {
            parent::getType();
            echo "<div>";
            if ($this->data != "") {
                echo "<img class='preview' rel='#overlay_" . self::$_instancenumber . "' src=" . $this->data . " />";
            } else {
                echo "<img class='preview' style='display:none;' rel='#overlay_" . self::$_instancenumber . "' />";
            }
            echo "<label for='wpdreamsUploadReset_" . self::$_instancenumber . "'>" . $this->label . "</label>";
            echo "<input type='text' class='wpdreamsUpload' id='wpdreamsUploadReset_" . self::$_instancenumber . "' name='" . $this->name . "' value='" . $this->data . "' />";
            echo "<input class='wpdreamsUpload_button' type='button' value='Upload Image' />";
            echo "<input type='button' class='default' name='default' value='Default' />";
            echo "<input type='hidden' value='" . $this->default_data . "' />";
            echo "<br />Enter an URL or upload an image!";
            echo "<div class='overlay' id='overlay_" . self::$_instancenumber . "'><img src='" . $this->data . "'' /></div>";
            echo "</div>";
        }
    }
}