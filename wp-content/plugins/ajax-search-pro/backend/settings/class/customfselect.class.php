<?php
if (!class_exists("wpdreamsCustomFSelect")) {
    /**
     * Class wpdreamsCustomFSelect
     *
     * A custom field selector UI element with prependable custom values.
     *
     * @package  WPDreams/OptionsFramework/Classes
     * @category Class
     * @author Ernest Marcinko <ernest.marcinko@wp-dreams.com>
     * @link http://wp-dreams.com, http://codecanyon.net/user/anago/portfolio
     * @copyright Copyright (c) 2014, Ernest Marcinko
     */
    class wpdreamsCustomFSelect extends wpdreamsType {
        function getType() {
            parent::getType();
            $this->processData();
            echo "<div class='wpdreamsCustomSelect'>";
            echo "<label for='wpdreamscustomselect_" . self::$_instancenumber . "'>" . $this->label . "</label>";
            echo "<select isparam=1 class='wpdreamscustomselect' id='wpdreamscustomselect_" . self::$_instancenumber . "' name='" . $this->name . "'>";
            foreach ($this->selects as $sel) {
                if (($sel['value'] . "") == ($this->selected . ""))
                    echo "<option value='" . $sel['value'] . "' selected='selected'>" . $sel['option'] . "</option>";
                else
                    echo "<option value='" . $sel['value'] . "'>" . $sel['option'] . "</option>";
            }
            $types = $this->get_custom_fields_list();
            if (count($types) > 0) {
                echo "<option value='c_f' disabled>Custom Fields</option>";
                foreach ($types as $sel) {
                    if (($sel['value'] . "") == ($this->selected . ""))
                        echo "<option value='" . $sel['value'] . "' selected='selected'>" . $sel['option'] . "</option>";
                    else
                        echo "<option value='" . $sel['value'] . "'>" . $sel['option'] . "</option>";
                }
            }
            echo "</select>";
            echo "<div class='triggerer'></div>
      </div>";
        }

        function processData() {
            $this->selects = array();
            $this->selects = $this->data['selects'];
            $this->selected = $this->data['value'];
        }

        final function getData() {
            return $this->data;
        }

        final function getSelected() {
            return $this->selected;
        }

        final function get_custom_fields_list() {
            global $wpdb;
            $ret = array();
            $types = $wpdb->get_results("SELECT * FROM " . $wpdb->postmeta . " WHERE substring(meta_key,1,1)<>'_' GROUP BY meta_key LIMIT 300", ARRAY_A);
            if ($types != null && is_array($types)) {
                foreach ($types as $k => $v) {
                    $_t = array();
                    $_t['option'] = $v['meta_key'];
                    $_t['value'] = $v['meta_key'];
                    $ret[] = $_t;
                }
            }
            return $ret;
        }

    }
}