<?php

// Create the new widget area
function myprefix_widget_area() {
    register_sidebar(array(
        'name' => 'PBE Above Header',
        'id' => 'pbe-above-header-wa',
        'before_widget' => '<div id="%1$s" class="et_pb_widget %2$s">',
        'after_widget' => '</div> <!-- end .et_pb_widget -->',
        'before_title' => '<h4 class="widgettitle">',
        'after_title' => '</h4>',
    ));
    register_sidebar(array(
        'name' => 'PBE Below Header',
        'id' => 'pbe-below-header-wa',
        'before_widget' => '<div id="%1$s" class="et_pb_widget %2$s">',
        'after_widget' => '</div> <!-- end .et_pb_widget -->',
        'before_title' => '<h4 class="widgettitle">',
        'after_title' => '</h4>',
    ));
    register_sidebar(array(
        'name' => 'PBE Footer',
        'id' => 'pbe-footer-wa',
        'before_widget' => '<div id="%1$s" class="et_pb_widget %2$s">',
        'after_widget' => '</div> <!-- end .et_pb_widget -->',
        'before_title' => '<h4 class="widgettitle">',
        'after_title' => '</h4>',
    ));
    register_sidebar(array(
        'name' => 'PBE Above Content',
        'id' => 'pbe-above-content-wa',
        'before_widget' => '<div id="%1$s" class="et_pb_widget %2$s">',
        'after_widget' => '</div> <!-- end .et_pb_widget -->',
        'before_title' => '<h4 class="widgettitle">',
        'after_title' => '</h4>',
    ));
    register_sidebar(array(
        'name' => 'PBE Below Content',
        'id' => 'pbe-below-content-wa',
        'before_widget' => '<div id="%1$s" class="et_pb_widget %2$s">',
        'after_widget' => '</div> <!-- end .et_pb_widget -->',
        'before_title' => '<h4 class="widgettitle">',
        'after_title' => '</h4>',
    ));
}
add_action('widgets_init', 'myprefix_widget_area');

?>