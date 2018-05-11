<?php
if (!class_exists("wpdreamsDatePicker")) {
    /**
     * Class wpdreamsDatePicker
     *
     * A simple date picker element
     *
     * @package  WPDreams/OptionsFramework/Classes
     * @category Class
     * @author Ernest Marcinko <ernest.marcinko@wp-dreams.com>
     * @link http://wp-dreams.com, http://codecanyon.net/user/wpdreams/portfolio
     * @copyright Copyright (c) 2016, Ernest Marcinko
     */
    class wpdreamsDatePicker extends wpdreamsType {
        function getType() {
            parent::getType();
            // dateFormat: yy-mm-dd
            ?>
            <div class="wpdreamsDatePicker">
                <label for='wpdreamsdatep_<?php echo self::$_instancenumber; ?>'><?php echo $this->label; ?></label>
                <input isparam=1 type='text' id='wpdreamsdatep_<?php echo self::$_instancenumber; ?>'
                       value='<?php echo $this->data; ?>' name='<?php echo $this->name; ?>'>
                <div class='triggerer'></div>
            </div>
            <?php
        }

        final function getData() {
            return $this->data;
        }

        final function getCss() {
            return $this->css;
        }
    }
}