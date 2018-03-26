<?php
namespace Elementor;

function eael_gravity_form_init(){
    Plugin::instance()->elements_manager->add_category(
        'elementor-gravity-forms',
        [
            'title'  => 'Elementor Gravity Form',
            'icon' => 'font'
        ],
        1
    );
}
add_action( 'elementor/init','Elementor\eael_gravity_form_init' );



