<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly


return array(

    'facebook' => array(

        'social_networks_menu' => array(
            'id'      => 'ywsl_social_networks_menu',
            'type'    => 'ywsl_social_networks_menu'
        ),

        'section_facebook_settings'     => array(
            'name' => __( 'Facebook settings', 'yith-woocommerce-social-login' ),
            'type' => 'title',
            'id'   => 'ywsl_section_facebook'
        ),

        'facebook_enable' => array(
            'name'    => __( 'Enable Facebook Login', 'yith-woocommerce-social-login' ),
            'desc'    => '',
            'id'      => 'ywsl_facebook_enable',
            'default' => 'no',
            'type'    => 'checkbox'
        ),

        'facebook_id' => array(
            'name'    => __( 'Facebook App ID', 'yith-woocommerce-social-login' ),
            'desc'    => '',
            'id'      => 'ywsl_facebook_id',
            'default' => '',
            'type'    => 'text'
        ),

        'facebook_secret' => array(
            'name'    => __( 'Facebook Secret', 'yith-woocommerce-social-login' ),
            'desc'    => '',
            'id'      => 'ywsl_facebook_secret',
            'default' => '',
            'type'    => 'text'
        ),

        'facebook_icon'         => array(
            'name'              => __( 'Facebook Icon', 'yit' ),
            'desc'              => '',
            'id'                => 'ywsl_facebook_icon',
            'default'           => '',
            'type'              => 'ywsl_upload'
        ),

        'section_facebook_settings_end' => array(
            'type' => 'sectionend',
            'id'   => 'ywsl_section_facebook_end'
        ),

    )
);