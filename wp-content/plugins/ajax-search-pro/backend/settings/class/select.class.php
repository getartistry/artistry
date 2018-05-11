<?php
if (!class_exists("wpdreamsSelect")) {
    /**
     * Class wpdreamsSelect
     *
     * Similar to wprdreamsCustomSelect, but stores the select values in an input field.
     *
     * @package  WPDreams/OptionsFramework/Classes
     * @category Class
     * @author Ernest Marcinko <ernest.marcinko@wp-dreams.com>
     * @link http://wp-dreams.com, http://codecanyon.net/user/anago/portfolio
     * @copyright Copyright (c) 2014, Ernest Marcinko
     */
    class wpdreamsSelect extends wpdreamsType {
        function getType() {
            parent::getType();
            $this->processData();
            echo "<div class='wpdreamsSelect'>";
            echo "<label for='wpdreamsselect_" . self::$_instancenumber . "'>" . $this->label . "</label>";
            echo "<select class='wpdreamsselect' id='wpdreamsselect_" . self::$_instancenumber . "' name='" . $this->name . "_select'>";
            foreach ($this->selects as $sel) {
                preg_match('/(?<option>.*?)\\|(?<value>.*)/', $sel, $matches);
                $matches['value'] = trim($matches['value']);
                $matches['option'] = trim($matches['option']);
                if ($matches['value'] == $this->selected)
                    echo "<option value='" . $matches['value'] . "' selected='selected'>" . $matches['option'] . "</option>";
                else
                    echo "<option value='" . $matches['value'] . "'>" . $matches['option'] . "</option>";
            }
            echo "</select>";
            echo "<input isparam=1 type='hidden' value='" . $this->data . "' name='" . $this->name . "'>";
            echo "<input type='hidden' value='" . $this->selected . "' name='selected-" . $this->name . "'>";
            echo "<div class='triggerer'></div>
      </div>";
        }

        function processData() {
            //$this->data = str_replace("\n","",$this->data);
            $_temp = explode("||", $this->data);
            $this->selects = explode(";", $_temp[0]);
            $this->selected = trim($_temp[1]);
        }

        final function getData() {
            return $this->data;
        }

        final function getSelected() {
            return $this->selected;
        }
    }
}