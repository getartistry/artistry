<?php

if (!defined('ABSPATH')) {
    die('-1');
}

class QuadMenuItemIcon extends QuadMenuItemDefault {

    protected $type = 'icon';

    function init() {        
        $this->args->has_caret = false;
        //$this->args->has_dropdown = $this->has_children = true;
        $this->args->has_title = $this->args->link_before = $this->args->link_after = false;
    }

}
