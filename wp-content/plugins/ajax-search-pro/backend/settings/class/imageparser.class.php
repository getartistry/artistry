<?php
if (!class_exists("wpdreamsImageParser")) {
    /**
     * Class wpdreamsImageParser
     *
     * DEPRECATED
     * A selector for image parsing sources.
     *
     * @deprecated
     * @package  WPDreams/OptionsFramework/Classes
     * @category Class
     * @author Ernest Marcinko <ernest.marcinko@wp-dreams.com>
     * @link http://wp-dreams.com, http://codecanyon.net/user/anago/portfolio
     * @copyright Copyright (c) 2014, Ernest Marcinko
     */
    class wpdreamsImageParser extends wpdreamsType {
        function __construct($name, $label, $uid, $callback) {
            $this->name = $name;
            $this->uid = $uid;
            $this->label = $label;
            $this->callback = $callback;
            $this->isError = false;
            self::$_instancenumber++;
            $this->getType();
        }

        function getType() {
            echo "<form name='" . $this->name . "' class='wpdreams-ajaxinput' style='height:40px;margin-left: -535px;'>";
            //echo "<label for='wpdreamsAjaxInput_".self::$_instancenumber."'>".$this->label."</label>";
            echo "<input type='hidden' name='callback' value='" . $this->callback . "' />";
            echo "<input type='hidden' name='uid' value='" . $this->uid . "' />";
            echo "<input type='text' id='wpdreamsAjaxInput_" . self::$_instancenumber . "' name='url' value='Enter the feed url here...' />";
            echo "
      <select style='width: 70px;' name='itemsnum'>
           <option value='1'>1</option>
           <option value='2'>2</option>
           <option value='3'>3</option>
           <option value='4'>4</option>
           <option value='5'>5</option>
           <option value='6'>6</option>
           <option value='7'>7</option>
           <option value='8'>8</option>
           <option value='9'>9</option>
           <option value='10'>10</option>
      </select>";
            echo "<select  style='width: 130px;' name='itemsnum'>";
            echo "
              <option value='flickr'>Source</option>
              <option value='flickr'>Flickr.com</option>
              <option value='500px'>500px.com</option>
      ";
            echo "</select>";
            echo "<input type='button' class='default' value='Generate!'/>";
            echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $this->label . "<img opened='0' style='cursor:pointer;vertical-align:middle;height:20px;' src='" . plugins_url('/types/icons/arrow-right.png', __FILE__) . "' />";
            echo "</form>";
        }
    }
}