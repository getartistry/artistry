<?php
if (!class_exists("wpdreamsGradient")) {
    class wpdreamsGradient extends wpdreamsType {
        /**
         * Class wpdreamsGradient
         *
         * A simple gradient selector with two color inputs. Radial and Linear supported.
         *
         * @package  WPDreams/OptionsFramework/Classes
         * @category Class
         * @author Ernest Marcinko <ernest.marcinko@wp-dreams.com>
         * @link http://wp-dreams.com, http://codecanyon.net/user/anago/portfolio
         * @copyright Copyright (c) 2014, Ernest Marcinko
         */
        function getType() {
            parent::getType();
            $this->processData();

            echo "<div class='wpdreamsGradient'>";
            if ($this->label != "")
                echo "<label for='wpdreamsgradient_" . self::$_instancenumber . "'>" . $this->label . "</label>";

            ?>
            <select id='<?php echo $this->name ?>_gradex' name='grad_type' class='grad_type'>
                <option value='1' <?php echo(($this->grad_type == 1) ? 'selected' : ''); ?>>Linear</option>
                <option value='0' <?php echo(($this->grad_type == 0) ? 'selected' : ''); ?>>Radial</option>
            </select>
            <?php

            echo "<input isparam=1 type='hidden' class='gradient' id='" . $this->name . "' id='wpdreamsgradient_" . self::$_instancenumber . "'  name='" . $this->name . "' id='wpdreamsgradient_" . self::$_instancenumber . "' value='" . $this->data . "' />";
            new wpdreamsColorPickerDummy('leftcolor_' . self::$_instancenumber, "", $this->leftcolor);
            new wpdreamsColorPickerDummy('rightcolor_' . self::$_instancenumber, "", $this->rightcolor);
            echo "<div class='grad_ex'></div><br>";
            echo "<div class='dslider' id='dslider" . self::$_instancenumber . "'></div>";
            echo "<div class='ddisplay'>
               <div class='dbg' id='dbg" . self::$_instancenumber . "'></div>
            </div>";
            echo "<div id='dtxt" . self::$_instancenumber . "' class='dtxt'>" . $this->rotation . "</div>&#176;";
            echo "
        <script>
          jQuery(document).ready(function(){
            jQuery('#dslider" . self::$_instancenumber . "').slider({
              orientation: 'horizontal',
              range: 'min',
              max: 360,
              value: " . $this->rotation . ",
              step: 5,
              change: function() {
                 jQuery('#" . $this->name . "_gradex').change();
              },
              slide: function(e, ui) {
                 jQuery('#dtxt" . self::$_instancenumber . "').html(ui.value);
                 jQuery('#" . $this->name . "_gradex').change();
              }
            });
            
            jQuery('#dslider" . self::$_instancenumber . "').change();
            //jQuery('#" . $this->name . "_gradex').change();
          });
        </script>          
      ";
            echo "<div class='triggerer'></div>
      </div>";
        }

        function processData() {
            $this->data = str_replace("\n", "", $this->data);
            preg_match("/(.*?)-(.*?)-(.*?)-(.*)/", $this->data, $matches);
            $this->grad_type = $matches[1];
            $this->rotation = $matches[2];
            if ($this->rotation == null || $this->rotation == '') $this->rotation = 0;
            $this->leftcolor = wpdreams_admin_hex2rgb($matches[3]);
            $this->rightcolor = wpdreams_admin_hex2rgb($matches[4]);
            $this->data = $this->grad_type . '-' . $this->rotation . '-' . $this->leftcolor . '-' . $this->rightcolor;
        }

        final function getData() {
            return $this->data;
        }

    }
}