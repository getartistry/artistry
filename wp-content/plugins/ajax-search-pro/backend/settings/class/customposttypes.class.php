<?php
if (!class_exists("wpdreamsCustomPostTypes")) {
    /**
     * Class wpdreamsCustomPostTypes
     *
     * A custom post types selector UI element with.
     *
     * @package  WPDreams/OptionsFramework/Classes
     * @category Class
     * @author Ernest Marcinko <ernest.marcinko@wp-dreams.com>
     * @link http://wp-dreams.com, http://codecanyon.net/user/anago/portfolio
     * @copyright Copyright (c) 2017, Ernest Marcinko
     */
    class wpdreamsCustomPostTypes extends wpdreamsType {
        function getType() {
            parent::getType();
            $this->processData();

            echo "
      <div class='wpdreamsCustomPostTypes' id='wpdreamsCustomPostTypes-" . self::$_instancenumber . "'>
        <fieldset>
          <legend>" . $this->label . "</legend>";
            echo '<div class="sortablecontainer" id="sortablecontainer' . self::$_instancenumber . '">
            <div class="arrow-all-left"></div>
            <div class="arrow-all-right"></div>
            <p>Available post types</p><ul id="sortable' . self::$_instancenumber . '" class="connectedSortable">';
            if ($this->types != null && is_array($this->types)) {
                foreach ($this->types as $k => $v) {
                    if ($this->selected == null || !in_array($k, $this->selected)) {
                        echo '<li class="ui-state-default" data-ptype="'.$k.'">' . $v->labels->name . '</li>';
                    }
                }
            }
            echo "</ul></div>";
            echo '<div class="sortablecontainer"><p>Drag here the post types you want to use!</p><ul id="sortable_conn' . self::$_instancenumber . '" class="connectedSortable">';
            if ($this->selected != null && is_array($this->selected)) {
                foreach ($this->selected as $k => $v) {
                    echo '<li class="ui-state-default" data-ptype="'.$v.'">' . $this->types[trim($v)]->labels->name . '</li>';
                }
            }
            echo "</ul></div>";
            echo "
         <input isparam=1 type='hidden' value='" . $this->data . "' name='" . $this->name . "'>";
            echo "
         <input type='hidden' value='wpdreamsCustomPostTypes' name='classname-" . $this->name . "'>";
            echo "
        </fieldset>
      </div>";
        }

        function processData() {
            $this->types = get_post_types(array(
                "public" => true,
                "_builtin" => false
            ), "objects", "OR");
            foreach ($this->types as $k => $v) {
                if (in_array($k, array("revision", "nav_menu_item", "attachment"))) {
                    unset($this->types[$k]);
                    continue;
                }
            }

            $this->selected = $this->decode_param($this->data);
            $this->data = $this->encode_param($this->data);

            /*$this->data = str_replace("\n", "", $this->data);
            if ($this->data != "")
                $this->selected = explode("|", $this->data);
            else
                $this->selected = null;*/
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