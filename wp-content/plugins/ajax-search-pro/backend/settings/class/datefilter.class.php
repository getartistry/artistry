<?php
if (!class_exists("wd_DateFilter")) {
    /**
     * Class wd_DateFilter
     *
     * Displays a date filter configuration element.
     *
     * @package  WPDreams/OptionsFramework/Classes
     * @category Class
     * @author Ernest Marcinko <ernest.marcinko@wp-dreams.com>
     * @link http://wp-dreams.com, http://codecanyon.net/user/wpdreams/portfolio
     * @copyright Copyright (c) 2016, Ernest Marcinko
     */
    class wd_DateFilter extends wpdreamsType {
        private $raw;

        function getType() {
            parent::getType();
            $this->processData();
            ?>
            <div class="wd_DateFilter" id="wd_DateFilter-<?php echo self::$_instancenumber; ?>">
                <div class="wd_col_40"></div>
                <div class="wd_col_60" style="line-height: 33px; vertical-align: top;">
                    <label><?php echo $this->label; ?></label>
                    <select class="wd_di_state">
                        <option value="date">Date</option>
                        <option value="rel_date">Relative Date</option>
                        <option value="disabled">Disabled</option>
                    </select><br>
                    <input class="wd_di_date"/>
                    <div class="wd_di_rel_date" style="display: inline;">
                        <input class="wd_di_yy twodigit" value=""/> years
                        <input class="wd_di_mm twodigit" value=""/> months
                        <input class="wd_di_dd twodigit" value=""/> days before current date
                    </div>
                </div>
                <div style="clear:both;"></div>
                <input isparam=1 type="hidden" value='<?php echo $this->data; ?>' name="<?php echo $this->name; ?>">
                <input type='hidden' value='wd_DateFilter' name='classname-<?php echo $this->name; ?>'>
                <div style="clear:both;"></div>
            </div>
        <?php
        }

        function processData() {

            /**
             * Expected raw format
             *
             * [0] state:       date|rel_date|disabled
             * [1] date:        yyyy-mm-dd
             * [2] rel_date:    0,0,0
             */
            $this->raw = explode("|", $this->data);

            if ( $this->raw[2] != "" )
                $this->raw[2] = explode(",", $this->raw[2]);

            $this->selected = array(
                "state" => $this->raw[0],
                "date"  => $this->raw[1],
                "rel_date" => $this->raw[2]
            );

        }

        final function getData() {
            return $this->data;
        }

        final function getSelected() {
            return $this->selected;
        }
    }
}