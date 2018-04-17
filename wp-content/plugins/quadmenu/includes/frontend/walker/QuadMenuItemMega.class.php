<?php

if (!defined('ABSPATH')) {
    die('-1');
}

class QuadMenuItemMega extends QuadMenuItem {

    protected $type = 'mega';

    function init() {
        $this->args->has_background = (0 === $this->depth && isset($this->item->background['thumbnail-id']) && is_array(wp_get_attachment_image_src($this->item->background['thumbnail-id'], 'full')));
    }

    function get_start_el() {

        $item_output = '';

        $this->add_item_classes();

        $this->add_item_classes_prefix();

        $this->add_item_classes_quadmenu();
        
        $this->add_item_classes_maxheight();

        $this->add_item_dropdown_width();

        $id = $this->get_item_id();

        $class = $this->get_item_classes();

        $item_output .= '<li' . $id . $class . '>';

        $this->add_link_atts();

        $this->add_link_atts_toggle();

        $item_output .= $this->get_link();

        //$this->add_dropdown_background();

        return $item_output;
    }

    function add_item_dropdown_width() {

        if (!empty($this->item->stretch)) {
            $this->dropdown_classes[] = 'quadmenu-dropdown-stretch-' . $this->item->stretch;
        }

        if (empty($this->item->stretch) && !empty($this->item->columns)) {
            $this->dropdown_classes = array_merge($this->dropdown_classes, $this->item->columns);
        }

        if (empty($this->item->stretch) && empty($this->item->columns)) {
            $this->dropdown_classes[] = 'quadmenu-dropdown-stretch-boxed';
        }
    }

    function add_item_dropdown_ul_classes() {
        $this->dropdown_ul_classes[] = 'quadmenu-row';
    }

}
