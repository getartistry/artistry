<?php
if (!class_exists("wpdreamsCustomFields")) {
    /**
     * Class wpdreamsCustomFields
     *
     * A custom field selector UI element.
     *
     * @package  WPDreams/OptionsFramework/Classes
     * @category Class
     * @author Ernest Marcinko <ernest.marcinko@wp-dreams.com>
     * @link http://wp-dreams.com, http://codecanyon.net/user/anago/portfolio
     * @copyright Copyright (c) 2014, Ernest Marcinko
     */
    class wpdreamsCustomFields extends wpdreamsType
    {
        function getType()
        {
            parent::getType();
            $this->processData();
            $inst = self::$_instancenumber; // Need this, as the static variable is overwritten when the callback is created

            echo "
      <div class='wpdreamsCustomFields' id='wpdreamsCustomFields-" . $inst . "'>
        <fieldset>
          <legend>" . $this->label . "</legend>";
            echo '<div class="draggablecontainer" id="draggablecontainer' . $inst . '">
            <div class="arrow-all-left"></div>
            <div class="arrow-all-right"></div><div style="margin: -3px 0 5px -5px;">';
            new wd_CFSearchCallBack('wdcfs_' . $inst, '',
                array(
                    'value' => '',
                    'args' => array('callback' => 'wd_cf_ajax_callback'),
                    'limit' => 20
                )
            );
            echo '</div><ul id="sortable' . $inst . '" class="connectedSortable">
                Use the search bar above to look for custom fields :)
                </ul></div>
                <div class="sortablecontainer"><p>Drag here the custom fields you want to use!</p><ul id="sortable_conn' . $inst . '" class="connectedSortable">';
            if ($this->selected != null && is_array($this->selected)) {
                foreach ($this->selected as $k => $v) {
                    echo '<li class="ui-state-default" cf_name="' . $v . '">' . $v . '<a class="deleteIcon"></a></li>';
                }
            }
            echo "</ul></div>
                  <input isparam=1 type='hidden' value='" . $this->data . "' name='" . $this->name . "'>
                  <input type='hidden' value='wpdreamsCustomFields' name='classname-" . $this->name . "'>
                </fieldset>
              </div>";
        }

        function processData() {
            $this->data = str_replace("\n", "", $this->data);
            if ($this->data != "")
                $this->selected = explode("|", $this->data);
            else
                $this->selected = null;
        }

        final function getData() {
            return $this->data;
        }

        final function getSelected() {
            return $this->selected;
        }
    }
}