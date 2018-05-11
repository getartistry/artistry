<?php
if (!class_exists("wpdreamsCustomPostTypesAll")) {
	/**
	 * Class wpdreamsCustomPostTypesAll
	 *
	 * A custom post types selector UI element with.
	 *
	 * @package  WPDreams/OptionsFramework/Classes
	 * @category Class
	 * @author Ernest Marcinko <ernest.marcinko@wp-dreams.com>
	 * @link http://wp-dreams.com, http://codecanyon.net/user/anago/portfolio
	 * @copyright Copyright (c) 2014, Ernest Marcinko
	 */
	class wpdreamsCustomPostTypesAll extends wpdreamsType {
		function getType() {
			parent::getType();
			$this->processData();
			$this->types = get_post_types();
			echo "
      <div class='wpdreamsCustomPostTypesAll' id='wpdreamsCustomPostTypesAll-" . self::$_instancenumber . "'>
        <fieldset>
          <legend>" . $this->label . "</legend>";
			echo '<div class="sortablecontainer" id="sortablecontainer' . self::$_instancenumber . '">
            <div class="arrow-all-left"></div>
            <div class="arrow-all-right"></div>
            <p>Available post types</p><ul id="sortable' . self::$_instancenumber . '" class="connectedSortable">';
			if ($this->types != null && is_array($this->types)) {
				foreach ($this->types as $k => $v) {
					if ($this->selected == null || !in_array($v, $this->selected)) {
						echo '<li class="ui-state-default">' . $k . '</li>';
					}
				}
			}
			echo "</ul></div>";
			echo '<div class="sortablecontainer"><p>Drag here the post types you want to use!</p><ul id="sortable_conn' . self::$_instancenumber . '" class="connectedSortable">';
			if ($this->selected != null && is_array($this->selected)) {
				foreach ($this->selected as $k => $v) {
					echo '<li class="ui-state-default">' . $v . '</li>';
				}
			}
			echo "</ul></div>";
			echo "
         <input isparam=1 type='hidden' value='" . $this->data . "' name='" . $this->name . "'>";
			echo "
         <input type='hidden' value='wpdreamsCustomPostTypesAll' name='classname-" . $this->name . "'>";
			echo "
        </fieldset>
      </div>";
		}

		function processData() {
			$this->data = str_replace("\n", "", $this->data);
			if ($this->data != "")
				$this->selected = explode("|", $this->data);
			else
				$this->selected = null;
			//$this->css = "border-radius:".$this->topleft."px ".$this->topright."px ".$this->bottomright."px ".$this->bottomleft."px;";
		}

		final function getData() {
			return $this->data;
		}

		final function getSelected() {
			return $this->selected;
		}
	}
}