<?php
if (!class_exists("wpdreamsSelectTerm")) {
    /**
     * Class wpdreamsSelectTerm
     *
     * Lists custom taxonomy terms in a select drop-down
     *
     * @package  WPDreams/OptionsFramework/Classes
     * @category Class
     * @author Ernest Marcinko <ernest.marcinko@wp-dreams.com>
     * @link http://wp-dreams.com, http://codecanyon.net/user/anago/portfolio
     * @copyright Copyright (c) 2014, Ernest Marcinko
     */
    class wpdreamsSelectTerm extends wpdreamsType {

        // Overall number of terms
        private $overall = 0;

        function getType() {
            parent::getType();
            $this->processData();
            $this->types = $this->getAllTerms();
            echo "<div class='wpdreamsSelectTerm'>";
            echo "<label for='wpdreamsselectterm_" . self::$_instancenumber . "'>" . $this->label . "</label>";
            echo "<select isparam=1 class='wpdreamsselectterm' id='wpdreamsselectterm_" . self::$_instancenumber . "' name='" . $this->name . "'>";
            if ( $this->overall > 0 && !empty($this->types) ) {
                foreach ($this->types as $kk => $vv) {
                    echo "<optgroup label='" . $kk . "'>";
                    $vvHierarchical = array();
                    wd_sort_terms_hierarchicaly($vv, $vvHierarchical);
                    $this->printTermsRecursive($vvHierarchical);
                    echo "</optgroup>";
                }
            } else {
                echo "<option value='-1'>No taxonomy terms detected</option>";
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

        function getAllTaxonomies() {
            $args = array(
                'public' => true,
                '_builtin' => false

            );
            $output = 'names'; // or objects
            $operator = 'and'; // 'and' or 'or'
            $taxonomies = get_taxonomies($args, $output, $operator);
            return $taxonomies;
        }

        function getAllTerms() {
            $taxonomies = $this->getAllTaxonomies();
            $terms = array();
            $this->overall = 0;
            $limit = 500; // terms per taxonomy to display

            foreach ($taxonomies as $taxonomy) {
                $terms[$taxonomy] = get_terms($taxonomy, 'orderby=name');
                if (is_array($terms[$taxonomy])) {
                    $terms[$taxonomy] = array_slice($terms[$taxonomy], 0, $limit, true);
                    $this->overall += count($terms[$taxonomy]);
                    if ($this->overall > 600) $limit = 50; // lower the limit if we already have too many terms
                }
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