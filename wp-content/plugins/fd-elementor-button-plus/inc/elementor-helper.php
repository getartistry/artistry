<?php
namespace Elementor;   

// Create Widgets category into elementor.
  
function adv_btn_widgets_init(){
    Plugin::instance()->elements_manager->add_category(
        'adv-btn',
        [
            'title'  => 'FD-Elementor Button Plus',
            'icon' => 'font'
        ],
        1
    );
}
add_action('elementor/init','Elementor\adv_btn_widgets_init');