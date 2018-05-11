<?php
if (!class_exists("wpdreamsCategories")) {
    /**
     * Class wpdreamsCategories
     *
     * Creates a cetegory selector UI element. Each category is stored separated by the "|" element.
     *
     * @package  WPDreams/OptionsFramework/Classes
     * @category Class
     * @author Ernest Marcinko <ernest.marcinko@wp-dreams.com>
     * @link http://wp-dreams.com, http://codecanyon.net/user/anago/portfolio
     * @copyright Copyright (c) 2014, Ernest Marcinko
     */
    class wpdreamsCategories extends wpdreamsType {
        function getType() {
            parent::getType();
            global $wpdb;
            $this->processData();
            $args = array();
            if ($this->selected != "")
                $args = array('exclude' => implode(",", $this->selected));
            $this->types = get_categories($args);
            echo "
      <div class='wpdreamsCategories' id='wpdreamsCategories-" . self::$_instancenumber . "'>
        <fieldset>
          <legend>" . $this->label . "</legend>";
            echo '<div class="sortablecontainer" id="sortablecontainer' . self::$_instancenumber . '">
                  <div class="arrow-all-left"></div>
                  <div class="arrow-all-right"></div>
                Available categories<ul id="sortable' . self::$_instancenumber . '" class="connectedSortable">';
            if ($this->types != null && is_array($this->types)) {
                foreach ($this->types as $k => $v) {
                    if ($this->selected == null || !in_array($v->term_id, $this->selected)) {
                        echo '<li class="ui-state-default" bid="' . $v->term_id . '">' . $v->name . '</li>';
                    }
                }
            }
            echo "</ul></div>";
            echo '<div class="sortablecontainer">Drag here the categories you want to exclude!<ul id="sortable_conn' . self::$_instancenumber . '" class="connectedSortable">';
            if ($this->selected != null && is_array($this->selected)) {
                $args = "";
                if ($this->selected != "")
                    $args = array('include' => implode(",", $this->selected));
                $_cats = get_categories($args);
                foreach ($_cats as $k => $v) {
                    echo '<li class="ui-state-default" bid="' . $v->term_id . '">' . $v->name . '</li>';
                }
            }
            echo "</ul></div>";
            echo "
         <input isparam=1 type='hidden' value='" . $this->data . "' name='" . $this->name . "'>";
            echo "
         <input type='hidden' value='wpdreamsCategories' name='classname-" . $this->name . "'>";
            echo "
        </fieldset>
      </div>";
        }

        function processData() {
            $this->data = str_replace("\n", "", $this->data);
            if ($this->data != "")
                $this->selected = explode("|", $this->data);
            else
                $this->selected = null;
            //$this->css = "border-radius:".$this->topleft."px ".$this->topright."px ".$this->bottomright."px ".$this->bottomleft."px;";
        }

        final function getData() {
            return $this->data;
        }

        final function getSelected() {
            return $this->selected;
        }
    }
}