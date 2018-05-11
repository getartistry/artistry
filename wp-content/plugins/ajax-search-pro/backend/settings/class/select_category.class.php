<?php
if (!class_exists("wpdreamsSelectCategory")) {
    /**
     * Class wpdreamsSelectCategory
     *
     * Lists post categories in a select drop-down
     *
     * @package  WPDreams/OptionsFramework/Classes
     * @category Class
     * @author Ernest Marcinko <ernest.marcinko@wp-dreams.com>
     * @link http://wp-dreams.com, http://codecanyon.net/user/anago/portfolio
     * @copyright Copyright (c) 2014, Ernest Marcinko
     */
    class wpdreamsSelectCategory extends wpdreamsType {
        function getType() {
            parent::getType();
            $this->processData();
            $this->types = $this->getAllTerms();
            echo "<div class='wpdreamsSelectCategory'>";
            echo "<label for='wpdreamsselectcategory_" . self::$_instancenumber . "'>" . $this->label . "</label>";
            echo "<select isparam=1 class='wpdreamsselectcategory' id='wpdreamsselectcategory_" . self::$_instancenumber . "' name='" . $this->name . "'>";
            if (!empty($this->types)) {
                $vvHierarchical = array();
                wd_sort_terms_hierarchicaly($this->types, $vvHierarchical);
                $this->printTermsRecursive($vvHierarchical);
            } else {
                echo "<option value='-1'>No categories detected</option>";
            }
            echo "</select>";
            echo "<div class='triggerer'></div>
      </div>";
        }

        function processData() {
            //$this->data = str_replace("\n","",$this->data);
            $this->selected = $this->data;
        }

        final function getData() {
            return $this->data;
        }

        final function getSelected() {
            return $this->selected;
        }

        function getAllTerms() {
            $limit = 500; // terms per taxonomy to display

            $terms = get_terms("category", 'orderby=name');
            if (is_array($terms)) {
                $terms = array_slice($terms, 0, $limit, true);
            }
            return $terms;
        }

        function printTermsRecursive ($terms, $level = 0) {
            foreach ($terms as $term) {
                $sel = $term->term_id == $this->selected ? " selected='selected'" : "";
                echo '<option value="' . $term->term_id . '" taxonomy="' . $term->taxonomy . '"'.$sel.'>' . str_repeat("--", $level) . $term->name . '</option>';
                if ( is_array($term->children) && count($term->children) >0 )
                    $this->printTermsRecursive($term->children, ($level + 1));
            }
        }


    }
}