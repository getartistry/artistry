<?php

if (!defined('ABSPATH')) {
    die('-1');
}

class QuadMenuItemPostType extends QuadMenuItem {

    protected $type = 'post_type';

    function init() {

        if (0 < $this->depth) {

            $this->args->has_description = (bool) $this->item->description;

            $this->args->has_subtitle = (bool) ($this->args->has_subtitle && !$this->args->has_description);

            $this->args->has_thumbnail = (bool) ($this->item->thumb);

            if ($this->args->has_thumbnail) {
                $this->args->has_badge = false;
            }
        }
    }

    function get_start_el() {

        $item_output = '';

        $this->add_item_description();

        $this->add_item_classes();

        $this->add_item_classes_prefix();

        $this->add_item_classes_current();

        $this->add_item_classes_quadmenu();

        $id = $this->get_item_id();

        $class = $this->get_item_classes();

        $item_output .= '<li' . $id . $class . '>';

        $this->add_link_atts();

        $this->add_link_atts_toggle();

        $item_output .= $this->get_link();

        return $item_output;
    }

    function add_item_description() {

        if (0 < $this->depth && $this->item->thumb && !$this->args->has_description) {

            $post = get_post($this->item->object_id);

            if (isset($post->post_excerpt)) {
                $this->item->description = wp_trim_words(wpautop($this->clean_item_content($post->post_excerpt ? $post->post_excerpt : $post->post_content)), 10);
                $this->args->has_description = true;
            }
        }
    }

}
