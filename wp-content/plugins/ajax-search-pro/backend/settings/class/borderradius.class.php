<?php
if (!class_exists("wpdreamsBorderRadius")) {
    /**
     * Class wpdreamsBorderRadius
     *
     * Creates a CSS border-radius defining element.
     *
     * @package  WPDreams/OptionsFramework/Classes
     * @category Class
     * @author Ernest Marcinko <ernest.marcinko@wp-dreams.com>
     * @link http://wp-dreams.com, http://codecanyon.net/user/anago/portfolio
     * @copyright Copyright (c) 2014, Ernest Marcinko
     */
    class wpdreamsBorderRadius extends wpdreamsType {
        function getType() {
            parent::getType();
            $this->processData();
            echo "
      <div class='wpdreamsBorderRadius'>
        <fieldset>
          <legend>" . $this->label . "</legend>";
            echo "
         <label>Top Left</label><input type='text' class='twodigit' name='topleft' value='" . $this->topleft . "' />px
         <label>Top Right</label><input type='text' class='twodigit' name='topright' value='" . $this->topright . "' />px
         <label>Bottom Right</label><input type='text' class='twodigit' name='bottomright' value='" . $this->bottomright . "' />px
         <label>Bottom Left</label><input type='text' class='twodigit' name='bottomleft' value='" . $this->bottomleft . "' />px<br><br>
      ";
            echo "
         <input isparam=1 type='hidden' value='" . $this->data . "' name='" . $this->name . "'>
         <div class='triggerer'></div>
        </fieldset>
      </div>";
        }

        function processData() {
            $this->data = str_replace("\n", "", $this->data);
            preg_match("/border-radius:(.*?)px(.*?)px(.*?)px(.*?)px;/", $this->data, $matches);
            $this->topleft = $matches[1];
            $this->topright = $matches[2];
            $this->bottomright = $matches[3];
            $this->bottomleft = $matches[4];
        }

        final function getData() {
            return $this->data;
        }
    }
}