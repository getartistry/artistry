<?php
if (!class_exists("wpdreamsLabelPosition")) {
    /**
     * Class wpdreamsLabelPosition
     *
     * DEPRECATED
     *
     * @deprecated
     * @package  WPDreams/OptionsFramework/Classes
     * @category Class
     * @author Ernest Marcinko <ernest.marcinko@wp-dreams.com>
     * @link http://wp-dreams.com, http://codecanyon.net/user/anago/portfolio
     * @copyright Copyright (c) 2014, Ernest Marcinko
     */
    class wpdreamsLabelPosition extends wpdreamsType {
        function __construct($name, $label, $width, $height, $data) {
            $this->constraints = null;
            $this->name = $name;
            $this->label = $label;
            $this->data = $data;
            $this->width = $width;
            $this->height = $height;
            $this->ratio = 400 / $this->width;
            $this->cheight = $this->ratio * $this->height;
            self::$_instancenumber++;
            $this->direction = "";
            $this->duration = "";
            $this->getType();
        }

        function getType() {
            parent::getType();
            $this->processData();
            $inst = self::$_instancenumber;
            echo "
        <div class='labeldrag' id='labeldrag_" . $inst . "' style='height:" . ($this->cheight + 90) . "px;'>
           <div class='inner' style='overflow:auto;width:400px;height:" . $this->cheight . "px;'>
              <script>
                jQuery(document).ready(function() { 
                  var drag = jQuery('#" . $this->name . "_" . $inst . "').draggable({ containment: 'parent', refreshPositions: true, appendTo: 'body' });
                  jQuery('#" . $this->name . "_" . $inst . "').bind( 'dragstop', function(event, ui) {
                      var pos = drag.position();
                      var ratio = " . $this->ratio . ";
                      var hidden = jQuery('#labelposition_hidden_" . $inst . "');
                      var duration = jQuery('input[name=\"induration_" . $this->name . "\"]')[0];
                      var direction= jQuery('input[name=\"indirection_" . $this->name . "\"]').prev();
                      jQuery(hidden).val('duration:'+jQuery(duration).val()+';direction:'+jQuery(direction).val()+';position:'+((pos.top+5)/ratio)+'||'+((pos.left+5)/ratio)+';');
                  });
                  jQuery('#labeldrag_" . $inst . " input').keyup(function(){
                     jQuery('#" . $this->name . "_" . $inst . "').trigger('dragstop');
                  });
                  jQuery('#labeldrag_" . $inst . " select').change(function(){
                     jQuery('#" . $this->name . "_" . $inst . "').trigger('dragstop');
                  });                 
                });
              </script>
              <div class='dragme' style='top:" . (($this->top * $this->ratio) - 5) . "px;left:" . (($this->left * $this->ratio) - 5) . "px;' id='" . $this->name . "_" . $inst . "'>
              </div>
           </div>
      ";
            echo "<div style='margin-top:" . ($this->cheight + 10) . "px;'>";
            new wpdreamsSelect("indirection_" . $this->name, "Animation direction", $this->_direction);
            new wpdreamsText("induration_" . $this->name, "Animation duration (ms)", $this->duration);
            echo "</div>";
            echo "
        </div>
        <div style='clear:both'></div>
        <input type='hidden' id='labelposition_hidden_" . $inst . "' name='" . $this->name . "' value='" . $this->data . "' />
      ";
            echo "
      
      ";
        }

        function processData() {
            // string: 'duration:123;direction:bottom-left;position:123||321;'
            $this->data = str_replace("\n", "", $this->data);
            preg_match("/duration:(.*?);/", $this->data, $matches);
            $this->duration = $matches[1];
            if ($this->duration == "")
                $this->duration = 500;
            preg_match("/direction:(.*?);/", $this->data, $matches);
            $this->direction = $matches[1];
            if ($this->direction == "")
                $this->direction = "top-left";
            $this->_direction = "
        Top|top;
        Bottom|bottom;
        Left|left;
        Right|right;
        Bottom-Left|bottom-left;
        Bottom-Right|bottom-right;
        Top-Left|top-left;
        Top-Right|top-right;
        Random|random|| 
      " . $this->direction;
            preg_match("/position:(.*?);/", $this->data, $matches);
            $this->position = $matches[1];
            $_temp = explode("||", $this->position);
            $this->top = $_temp[0];
            $this->left = $_temp[1];
        }
    }
}