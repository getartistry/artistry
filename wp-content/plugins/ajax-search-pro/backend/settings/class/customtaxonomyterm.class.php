<?php
if (!class_exists("wpdreamsCustomTaxonomyTerm")) {
    /**
     * Class wpdreamsCustomTaxonomyTerm
     *
     * A taxonomy-term drag and drop UI element.
     *
     * @package  WPDreams/OptionsFramework/Classes
     * @category Class
     * @author Ernest Marcinko <ernest.marcinko@wp-dreams.com>
     * @link http://wp-dreams.com, http://codecanyon.net/user/anago/portfolio
     * @copyright Copyright (c) 2014, Ernest Marcinko
     */
    class wpdreamsCustomTaxonomyTerm extends wpdreamsType {
        function getType() {
            parent::getType();
            $this->processData();
            $this->types = $this->getAllTerms();
            echo "
      <div class='wpdreamsCustomTaxonomyTerm' id='wpdreamsCustomTaxonomyTerm-" . self::$_instancenumber . "'>
        <fieldset>                               
          <div style='margin:15px 30px;text-align: left;'>
          <label>Select the taxonomy: </label>
          <select name='" . $this->name . "_taxonomies' id='taxonomy_selector_" . self::$_instancenumber . "'> ";
            foreach ($this->types as $taxonomy => $v) {
                $tax = get_taxonomy($taxonomy);
                $custom_post_type = "";
                if ($tax->object_type != null && $tax->object_type[0] != null)
                    $custom_post_type = $tax->object_type[0] . " - ";
                echo "<option  value='" . $taxonomy . "' taxonomy='" . $taxonomy . "'>" . $custom_post_type . $tax->labels->name . "</option>";
            }
            echo "</select>
            <label>Show parent categories only <input class='hide-children' type='checkbox'></label>
          </div>
          <legend>" . $this->label . "</legend>";
            echo '<div class="sortablecontainer" id="sortablecontainer' . self::$_instancenumber . '">
                  <div class="arrow-all-left"></div>
                  <div class="arrow-all-right"></div>
            <p>Available terms for the selected taxonomy</p>
            <ul id="sortable' . self::$_instancenumber . '" class="connectedSortable">';

            if ($this->types != null && is_array($this->types)) {
                foreach ($this->types as $kk => $vv) {
                    $vvHierarchical = array();
                    wd_sort_terms_hierarchicaly($vv, $vvHierarchical);
                    $this->printTermsRecursive($vvHierarchical);
                }
            }
            echo "</ul></div>";
            echo '<div class="sortablecontainer"><p>Drag here the terms you want to <b>' . $this->otype . '</b>!</p><ul id="sortable_conn' . self::$_instancenumber . '" class="connectedSortable">';
            if ($this->selected != null && is_array($this->selected)) {
                foreach ($this->selected as $k => $v) {
                    $term = get_term($v[0], $v[1]);
                    if ( !isset($term->term_id) ) {
                        continue;
                    } else {
                        $this->verified_checked[] = $term->term_id . "-". $term->taxonomy;
                    }
                    echo '<li class="ui-state-default" term_id="' . $term->term_id . '" taxonomy="' . $term->taxonomy . '">' . $term->name . '</li>';
                }
                $this->data["value"] = implode("|", $this->verified_checked);
            }
            echo "</ul></div>";
            echo "
         <input isparam=1 type='hidden' value='" . (( is_array($this->data) && isset($this->data['value']) ) ? $this->data["value"] : $this->data) . "' name='" . $this->name . "'>
         <input type='hidden' value='wpdreamsCustomTaxonomyTerm' name='classname-" . $this->name . "'>";
            echo "
        </fieldset>
      </div>";
        }

        function getAllTaxonomies() {
            $args = array(
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
            $overall = 0;
            $limit = 500; // terms per taxonomy to display

            foreach ($taxonomies as $taxonomy) {
                $terms[$taxonomy] = get_terms($taxonomy, 'orderby=name');
                if (is_array($terms[$taxonomy])) {
                    $terms[$taxonomy] = array_slice($terms[$taxonomy], 0, $limit, true);
                    $overall += count($terms[$taxonomy]);
                    if ($overall > 600) $limit = 50; // lower the limit if we already have too many terms
                }
            }

            return $terms;
        }

        function printTermsRecursive ($terms, $level = 0) {
            foreach ($terms as $term) {
                if ($this->selected == null || !wd_in_array_r($term->term_id, $this->selected))
                    echo '<li class="ui-state-default termlevel-'.$level.'" term_id="' . $term->term_id . '" taxonomy="' . $term->taxonomy . '">' . $term->name . '</li>';
                if (is_array($term->children) && count($term->children) >0 )
                    $this->printTermsRecursive($term->children, ($level + 1));
            }
        }

        function processData() {
            if (is_array($this->data) && isset($this->data['type']) && isset($this->data['value'])) {
              $this->otype = $this->data['type'];
              $this->v = str_replace("\n", "", $this->data["value"]);
            } else {
              $this->otype = "include";
              $this->v = str_replace("\n", "", $this->data);
            }

            $this->selected = array();
            $this->_selected = array();

            // In the printing process we re-work put only existing ones here.
            $this->verified_checked = array();

            if ($this->v != "") {
                $_sel = explode("|", $this->v);
                foreach ($_sel as $k => $v) {
                    if ($v == "") continue;
                    preg_match('/^(\d*)\-(.*)/', $v, $xmatches);
                    $this->selected[] = array($xmatches[1], $xmatches[2]);
                }
                foreach ($this->selected as $kk => $vv)
                    $this->_selected[$vv[1]][] = $vv[0];
            } else {
                $this->selected = null;
                $this->_selected = null;
            }

        }

        final function getData() {
            return $this->data;
        }

        final function getSelected() {
            return $this->_selected;
        }
    }
}