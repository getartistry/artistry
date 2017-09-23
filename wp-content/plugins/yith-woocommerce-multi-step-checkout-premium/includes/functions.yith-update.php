<?php

/**
 * Database Version Update
 */

/**
 * Remove old options for seactionstart, sectionend, title options
 */
function yith_wcms_update_db_1_0_1() {
    $db_version = get_option( 'yith_wcms_db_version', '1.0.0' );
    if ( $db_version && version_compare( $db_version, '1.0.1', '<' ) ) {
       
        $old_options = array(
            'yith_wcms_settings_options_start',
            'yith_wcms_settings_options_title',
            'yith_wcms_settings_options_end',
            'yith_wcms_order_received_options_start',
            'yith_wcms_order_received_options_title',
            'yith_wcms_order_received_options_end',
            'yith_wcms_timeline_options_title',
            'yith_wcms_timeline_options_title',
            'yith_wcms_timeline_template_options_title',
            'yith_wcms_timeline_template_options_start',
            'yith_wcms_timeline_style1_options_start',
            'yith_wcms_timeline_style2_options_start',
            'yith_wcms_timeline_style3_options_start',
            'yith_wcms_timeline_options_start',
            'yith_wcms_timeline_style_options_start',
            'yith_wcms_timeline_style_options_end',
            'yith_wcms_button_options_start',
            'yith_wcms_timeline_options_end',
            'yith_wcms_button_options_end',
            'yith_wcms_timeline_style_options_end',
            'yith_wcms_timeline_style1_options_end',
            'yith_wcms_timeline_style2_options_end',
            'yith_wcms_timeline_style3_options_end',
            'yith_wcms_settings_options_pro_title',
            'yith_wcms_settings_options_pro_start',
            'yith_wcms_settings_options_pro_end'
        );
        
        foreach( $old_options as $old_option ){
            delete_option( $old_option );
        }

        update_option( 'yith_wcms_db_version', '1.0.1' );
    }
}

add_action( 'admin_init', 'yith_wcms_update_db_1_0_1' );