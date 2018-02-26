<?php

if (!defined('ABSPATH')) {
    die('-1');
}

add_filter('wp_import_post_data_raw', 'quadmenu_import_meta', 10, 1);

function quadmenu_import_meta($item, $i = 0, $quadmenu_meta = array()) {

    global $wp_import, $quadmenu_meta;

    if ('nav_menu_item' != $item['post_type'] || 'draft' == $item['status'])
        return $item;

    foreach ($item['postmeta'] as $key => $meta) {

        if ($meta['key'] != QUADMENU_DB_KEY || empty($meta['value']))
            continue;

        $wp_import->quadmenu[(int) $item['post_id']] = maybe_unserialize($meta['value']);
    }

    return $item;
}

add_action('import_end', 'quadmenu_import_save');

function quadmenu_import_save() {

    global $wpdb, $wp_import, $quadmenu_meta;

    if (empty($wp_import->quadmenu))
        return;

    foreach ($wp_import->quadmenu as $post_id => $quadmenu_meta) {

        if (empty($wp_import->processed_menu_items[$post_id]))
            continue;

        update_post_meta((int) $wp_import->processed_menu_items[$post_id], QUADMENU_DB_KEY, $quadmenu_meta);
    }
}
