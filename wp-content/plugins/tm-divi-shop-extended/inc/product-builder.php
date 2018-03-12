<?php
function my_et_builder_post_types( $post_types ) {
    $post_types[] = 'product';
    return $post_types;
}
add_filter( 'et_builder_post_types', 'my_et_builder_post_types' );
?>