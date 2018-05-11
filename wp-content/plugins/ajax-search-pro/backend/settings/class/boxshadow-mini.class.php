<?php
if (!class_exists("wpdreamsBoxShadowMini")) {
    /**
     * Class wpdreamsBoxShadowMini
     *
     * Creates a CSS box-shadow defining element of small width.
     *
     * @package  WPDreams/OptionsFramework/Classes
     * @category Class
     * @author Ernest Marcinko <ernest.marcinko@wp-dreams.com>
     * @link http://wp-dreams.com, http://codecanyon.net/user/anago/portfolio
     * @copyright Copyright (c) 2014, Ernest Marcinko
     */
    class wpdreamsBoxShadowMini extends wpdreamsType {
        function getType() {
            parent::getType();
            $this->processData();
            echo "
      <div class='wpdreamsBoxShadow mini'>
        <fieldset>
          <legend>" . $this->label . "</legend>";
            echo "
         <label>Inset</label><select class='smaller' name='_xx_inset_xx_'>
             <option value='' " . (($this->inset == '') ? 'selected="selected"' : '') . ">None</option>
             <option value='inset' " . (($this->inset == 'inset') ? 'selected="selected"' : '') . ">Inset</option>
         </select>
         <br><label>Horizontal offset</label><input type='text' class='twodigit' name='_xx_hlength_xx_' value='" . $this->hlength . "' />px
         <br><label>Vertical offset</label><input type='text' class='twodigit' name='_xx_vlength_xx_' value='" . $this->vlength . "' />px
         <br><label>Blur radius</label><input type='text' class='twodigit' name='_xx_blurradius_xx_' value='" . $this->blurradius . "' />px
         <br><label>Spread</label><input type='text' class='twodigit' name='_xx_spread_xx_' value='" . $this->spread . "' />px<br>
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
            preg_match("/box-shadow:(.*?)px (.*?)px (.*?)px (.*?)px (.*?) (.*?);/", $this->data, $matches);
            $this->inset = $matches[6];
            $this->hlength = $matches[1];
            $this->vlength = $matches[2];
            $this->blurradius = $matches[3];
            $this->spread = $matches[4];
            $this->color = $matches[5];
        }

        final function getData() {
            return $this->data;
        }

        final function getCss() {
            return $this->css;
        }
    }
}