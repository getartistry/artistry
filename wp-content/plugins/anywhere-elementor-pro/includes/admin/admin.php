<?php

namespace Aepro;

class Admin{

    public function __construct()
    {
        add_action('admin_menu', [ $this, 'aep_settings_page']);
    }

    public function aep_settings_page(){
        add_options_page('Anywhere Elementor Pro Settings','Settings', 'manage-options' );
    }
}