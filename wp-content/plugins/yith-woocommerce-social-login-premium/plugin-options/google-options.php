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

    'google' => array(

        'social_networks_menu' => array(
            'id'      => 'ywsl_social_networks_menu',
            'type'    => 'ywsl_social_networks_menu'
        ),

        'section_google_settings'     => array(
            'name' => __( 'Google settings', 'yith-woocommerce-social-login' ),
            'desc'    =>  __( '<strong>Callback URL</strong>: '.YITH_WC_Social_Login()->get_base_url() . '?hauth.done=Google', 'yith-woocommerce-social-login' ),
            'type' => 'title',
            'id'   => 'ywsl_section_google'
        ),

        'google_enable' => array(
            'name'    => __( 'Enable Google Login', 'yith-woocommerce-social-login' ),
            'desc'    => '',
            'id'      => 'ywsl_google_enable',
            'default' => 'no',
            'type'    => 'checkbox'
        ),

        'google_id' => array(
            'name'    => __( 'Google ID', 'yith-woocommerce-social-login' ),
            'desc'    => '',
            'id'      => 'ywsl_google_id',
            'default' => '',
            'type'    => 'text'
        ),

        'google_secret' => array(
            'name'    => __( 'Google secret', 'yith-woocommerce-social-login' ),
            'desc'    => '',
            'id'      => 'ywsl_google_secret',
            'default' => '',
            'type'    => 'text'
        ),

        'google_icon'         => array(
            'name'              => __( 'Google Icon', 'yit' ),
            'desc'              => '',
            'id'                => 'ywsl_google_icon',
            'default'           => '',
            'type'              => 'ywsl_upload'
        ),

        'section_google_settings_end' => array(
            'type' => 'sectionend',
            'id'   => 'ywsl_section_google_end'
        )
    )
);