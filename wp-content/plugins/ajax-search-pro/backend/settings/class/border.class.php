<?php
if (!class_exists("wpdreamsBorder")) {
    /**
     * Class wpdreamsBorder
     *
     * Creates a CSS border defining element.
     *
     * @package  WPDreams/OptionsFramework/Classes
     * @category Class
     * @author Ernest Marcinko <ernest.marcinko@wp-dreams.com>
     * @link http://wp-dreams.com, http://codecanyon.net/user/anago/portfolio
     * @copyright Copyright (c) 2014, Ernest Marcinko
     */
    class wpdreamsBorder extends wpdreamsType {
        function getType() {
            parent::getType();
            $this->processData();
            echo "
      <div class='wpdreamsBorder'>
        <fieldset>
          <legend>" . $this->label . "</legend>";
            echo "
         <label>Border Style</label><select class='smaller' name='_xx_style_xx_'>
            <option value='none' " . (($this->style == 'none') ? 'selected="selected"' : '') . ">None</option>
            <option value='hidden' " . (($this->style == 'hidden') ? 'selected="selected"' : '') . ">Hidden</option>
            <option value='dotted' " . (($this->style == 'dotted') ? 'selected="selected"' : '') . ">Dotted</option>
            <option value='dashed' " . (($this->style == 'dashed') ? 'selected="selected"' : '') . ">Dashed</option>
            <option value='solid' " . (($this->style == 'solid') ? 'selected="selected"' : '') . ">Solid</option>
            <option value='double' " . (($this->style == 'double') ? 'selected="selected"' : '') . ">Double</option>
            <option value='groove' " . (($this->style == 'groove') ? 'selected="selected"' : '') . ">Groove</option>
            <option value='groove' " . (($this->style == 'groove') ? 'selected="selected"' : '') . ">Ridge</option>
            <option value='inset' " . (($this->style == 'inset') ? 'selected="selected"' : '') . ">Inset</option>
            <option value='outset' " . (($this->style == 'outset') ? 'selected="selected"' : '') . ">Outset</option>
         </select>
         <label>Border Width</label><input type='text' class='twodigit' name='_xx_width_xx_' value='" . $this->width . "' />px";
            new wpdreamsColorPickerDummy("_xx_color_xx_", "Border color", (isset($this->color) ? $this->color : "#000000"));
            echo "
         <fieldset>
           <legend>Border Radius:</legend>
           <label>Top-Left</label><input type='text' class='twodigit' name='_xx_topleft_xx_' value='" . $this->topleft . "' />px
           <label>Top-Right</label><input type='text' class='twodigit' name='_xx_topright_xx_' value='" . $this->topright . "' />px
           <label>Bottom-Right</label><input type='text' class='twodigit' name='_xx_bottomright_xx_' value='" . $this->bottomright . "' />px
           <label>Bottom-Left</label><input type='text' class='twodigit' name='_xx_bottomleft_xx_' value='" . $this->bottomleft . "' />px
         </fieldset>
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

            preg_match("/border:(.*?)px (.*?) (.*?);/", $this->data, $matches);
            $this->width = $matches[1];
            $this->style = $matches[2];
            $this->color = $matches[3];

        }

        final function getData() {
            return $this->data;
        }

        final function getCss() {
            return $this->css;
        }
    }
}