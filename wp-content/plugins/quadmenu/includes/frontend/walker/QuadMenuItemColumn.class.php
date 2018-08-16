<?php

if (!defined('ABSPATH')) {
    die('-1');
}

class QuadMenuItemColumn extends QuadMenuItem {

    protected $type = 'column';

    function init() {
        $this->item->custom_type = 'column';
    }

    function get_start_el() {

        $item_output = '';

        $this->add_item_classes();

        $this->add_item_classes_prefix();

        $this->add_item_classes_columns();

        $id = $this->get_item_id();

        $class = $this->get_item_classes();

        $item_output .= '<li' . $id . $class . '>';

        $item_output .= $this->get_title();

        return $item_output;
    }

    function add_item_classes_columns() {

        $this->item_classes = array_diff($this->item_classes, array('quadmenu-item-type-custom'));

        $this->item_classes[] = 'quadmenu-item-type-' . $this->item->quadmenu;

        if (!empty($this->item->columns) && is_array($this->item->columns)) {
            $this->item_classes[] = join(' ', array_map('sanitize_html_class', $this->item->columns));
        }
    }

    function get_title() {
    }

    function add_item_dropdown_classes() {
        return false;
    }

    function add_item_dropdown_ul_classes() {
        return false;
    }

}
