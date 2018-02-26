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

        $this->add_item_classes_mega();

        $this->add_item_dropdown_classes();

        $id = $this->get_item_id();

        $class = $this->get_item_classes();

        $item_output .= '<li' . $id . $class . '>';

        $this->add_link_atts();

        $this->add_link_atts_toggle();

        $item_output .= $this->get_link();

        $this->add_dropdown_background();

        return $item_output;
    }

    function add_item_dropdown_classes() {
        $this->dropdown_classes = array_merge($this->dropdown_classes, $this->item->columns);
    }

    function add_item_classes_mega() {
        if (empty($this->item->columns)) {
            $this->item_classes[] = esc_attr('quadmenu-dropdown-full');
        }
    }    
    
    function add_dropdown_ul_classes() {
        $this->dropdown_ul_classes[] = 'row';
    }

}
