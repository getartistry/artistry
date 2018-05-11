<?php
if (!class_exists("wpdreamsTextShadow")) {
    /**
     * Class wpdreamsTextShadow
     *
     * A CSS text-shadow input UI element.
     *
     * @package  WPDreams/OptionsFramework/Classes
     * @category Class
     * @author Ernest Marcinko <ernest.marcinko@wp-dreams.com>
     * @link http://wp-dreams.com, http://codecanyon.net/user/anago/portfolio
     * @copyright Copyright (c) 2014, Ernest Marcinko
     */
    class wpdreamsTextShadow extends wpdreamsType {
        function getType() {
            parent::getType();
            $this->processData();
            echo "
      <div class='wpdreamsTextShadow'>
        <fieldset>
          <legend>" . $this->label . "</legend>";
            echo "
         <label>Horizontal offset</label><input type='text' class='twodigit' name='_xx_hlength_xx_' value='" . $this->hlength . "' />px
         <br><label>Vertical offset</label><input type='text' class='twodigit' name='_xx_vlength_xx_' value='" . $this->vlength . "' />px
         <br><label>Blur radius</label><input type='text' class='twodigit' name='_xx_blurradius_xx_' value='" . $this->blurradius . "' />px
         <br>
      ";
            new wpdreamsColorPickerDummy("_xx_color_xx_", "Shadow color", (isset($this->color) ? $this->color : "#000000"));
            echo "
         <input isparam=1 type='hidden' value='" . $this->data . "' name='" . $this->name . "'>
         <div class='triggerer'></div>
        </fieldset>
      </div>";
        }

        function processData() {
            $this->data = str_replace("\n", "", $this->data);
            preg_match("/text-shadow:(.*?)px (.*?)px (.*?)px (.*?);/", $this->data, $matches);
            $this->hlength = $matches[1];
            $this->vlength = $matches[2];
            $this->blurradius = $matches[3];
            $this->color = $matches[4];
        }

        final function getData() {
            return $this->data;
        }

        final function getCss() {
            return $this->css;
        }
    }
}
?>