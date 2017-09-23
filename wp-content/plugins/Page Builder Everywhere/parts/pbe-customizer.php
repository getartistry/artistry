<?php

function pbe_customizer_settings($wp_customize) {
    
// --------------- Pascal Customizer Panel --------------- // PANEL

    $wp_customize->add_panel( 'pbe_customizer_options', array(
        'priority'       => 60,
        'capability'     => 'edit_theme_options',
        'title'          => __('Page Builder Everywhere'),
        'description'    => __('Advanced options for controlling the way PBE behaves.'),
    ));

// -------------- Device Preview ---------------------- // SECTION

    $wp_customize->add_section('main_header_options', array(
        'priority' => 10,
        'title' => __('Main Header'),
        'panel'  => 'pbe_customizer_options',
    )); 

    $wp_customize->add_section('above_header_options', array(
        'priority' => 20,
        'title' => __('Above Header'),
        'panel'  => 'pbe_customizer_options',
    ));

    $wp_customize->add_section('pbe_footer_options', array(
        'priority' => 30,
        'title' => __('Footer'),
        'panel'  => 'pbe_customizer_options',
    )); 

    $wp_customize->add_setting( 'fix_logo_size', array(
        'default'        => false,
        'type'           => 'option',
        'capability'     => 'edit_theme_options' 
    ));

    $wp_customize->add_control( 'fix_logo_size', array(
        'settings' => 'fix_logo_size',
        'label'    => 'Stop logo overlapping section above header',
        'section'  => 'main_header_options',
        'type'     => 'checkbox',
    ));

    $wp_customize->add_setting( 'hide_main_header', array(
        'default'        => false,
        'type'           => 'option',
        'capability'     => 'edit_theme_options' 
    ));

    $wp_customize->add_control( 'hide_main_header', array(
        'settings' => 'hide_main_header',
        'label'    => 'Remove Default Main Header',
        'section'  => 'main_header_options',
        'type'     => 'checkbox',
    ));

    $wp_customize->add_setting( 'hide_above_header', array(
        'default'        => false,
        'type'           => 'option',
        'capability'     => 'edit_theme_options' 
    ));

    $wp_customize->add_control( 'hide_above_header', array(
        'settings' => 'hide_above_header',
        'label'    => 'Hide above header section on scroll',
        'section'  => 'above_header_options',
        'type'     => 'checkbox',
    ));

    $wp_customize->add_setting( 'hide_bottom_footer', array(
        'default'        => false,
        'type'           => 'option',
        'capability'     => 'edit_theme_options' 
    ));

    $wp_customize->add_control( 'hide_bottom_footer', array(
        'settings' => 'hide_bottom_footer',
        'label'    => 'Hide bottom footer',
        'section'  => 'pbe_footer_options',
        'type'     => 'checkbox',
    ));
}
    
add_action( 'customize_register', 'pbe_customizer_settings', 999 );