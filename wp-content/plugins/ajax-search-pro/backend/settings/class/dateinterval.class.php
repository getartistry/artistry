<?php
if (!class_exists("wd_DateInterval")) {
    /**
     * Class wd_DateInterval
     *
     * Displays a tag selector element.
     *
     * @package  WPDreams/OptionsFramework/Classes
     * @category Class
     * @author Ernest Marcinko <ernest.marcinko@wp-dreams.com>
     * @link http://wp-dreams.com, http://codecanyon.net/user/anago/portfolio
     * @copyright Copyright (c) 2016, Ernest Marcinko
     */
    class wd_DateInterval extends wpdreamsType {
        private $raw;

        function getType() {
            parent::getType();
            $this->processData();
            ?>
            <div class="wd_DateInterval" id="wd_DateInterval-<?php echo self::$_instancenumber; ?>">
                <div class="wd_col_20"></div>
                <div class="wd_col_40" style="line-height: 33px; vertical-align: top;">
                    <select class="wd_di_mode">
                        <option value="exclude">Exclude</option>
                        <option value="include">Include</option>
                    </select>&nbsp;from:
                    <select class="wd_di_from">
                        <option value="date">Date</option>
                        <option value="rel_date">Relative Date</option>
                        <option value="disabled">Disabled</option>
                    </select>
                    <input class="wd_di_fromDate"/>
                    <div class="wd_di_fromreld" style="display: inline;">
                        <input class="wd_di_fromyy twodigit" value=""/> years
                        <input class="wd_di_frommm twodigit" value=""/> months
                        <input class="wd_di_fromdd twodigit" value=""/> days before current date
                    </div>
                </div>
                <div class="wd_col_40" style="line-height: 33px; vertical-align: top;">
                    &nbsp;to:
                    <select class="wd_di_to">
                        <option value="date">Date</option>
                        <option value="rel_date">Relative Date</option>
                        <option value="disabled">Disabled</option>
                    </select><br>
                    <input class="wd_di_toDate"/>
                    <div class="wd_di_toreld" style="display: inline;">
                        <input class="wd_di_toyy twodigit" value=""/> years
                        <input class="wd_di_tomm twodigit" value=""/> months
                        <input class="wd_di_todd twodigit" value=""/> days before current date
                    </div>
                </div>
                <div style="clear:both;"></div>
                <input isparam=1 type="hidden" value='<?php echo $this->data; ?>' name="<?php echo $this->name; ?>">
                <input type='hidden' value='wd_DateInterval' name='classname-<?php echo $this->name; ?>'>
                <div style="clear:both;"></div>
            </div>
        <?php
        }

        function processData() {

            /**
             * Expected raw format
             *
             * [0] mode:     exclude|include
             * [1] from:     disabled|date|interval
             * [2] to:       disabled|date|interval
             * [3] fromDate: yyyy-mm-dd
             * [4] toDate:   yyyy-mm-dd
             * [5] fromInt:  0,0,0
             * [6] toInt:    0,0,0
             */
            $this->raw = explode("|", $this->data);

            if ( $this->raw[5] != "" )
                $this->raw[5] = explode(",", $this->raw[5]);
            if ( $this->raw[6] != "" )
                $this->raw[6] = explode(",", $this->raw[6]);

            $this->selected = array(
                "mode" => $this->raw[0],
                "from"  => $this->raw[1],
                "to" => $this->raw[2],
                "fromDate" => $this->raw[3],
                "toDate"=> $this->raw[4],
                "fromInt"=> $this->raw[5],
                "toInt" => $this->raw[6]
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