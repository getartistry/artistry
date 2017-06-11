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

    'twitter' => array(

        'social_networks_menu' => array(
            'id'      => 'ywsl_social_networks_menu',
            'type'    => 'ywsl_social_networks_menu'
        ),

        'section_twitter_settings'     => array(
            'name' => __( 'Twitter settings', 'yith-woocommerce-social-login' ),
            'desc'    =>  __( '<strong>Callback URL</strong>: '.YITH_WC_Social_Login()->get_base_url(). '?hauth.done=Twitter', 'yith-woocommerce-social-login' ),
            'type' => 'title',
            'id'   => 'ywsl_section_twitter'
        ),

        'twitter_enable' => array(
            'name'    => __( 'Enable Twitter Login', 'yith-woocommerce-social-login' ),
            'desc'    => '',
            'id'      => 'ywsl_twitter_enable',
            'default' => 'no',
            'type'    => 'checkbox'
        ),

        'twitter_key' => array(
            'name'    => __( 'Twitter Key', 'yith-woocommerce-social-login' ),
            'desc'    => '',
            'id'      => 'ywsl_twitter_key',
            'default' => '',
            'type'    => 'text'
        ),

        'twitter_secret' => array(
            'name'    => __( 'Twitter Secret', 'yith-woocommerce-social-login' ),
            'desc'    => '',
            'id'      => 'ywsl_twitter_secret',
            'default' => '',
            'type'    => 'text'
        ),

        'twitter_icon'         => array(
            'name'              => __( 'Twitter Icon', 'yit' ),
            'desc'              => '',
            'id'                => 'ywsl_twitter_icon',
            'default'           => '',
            'type'              => 'ywsl_upload'
        ),

        'section_twitter_settings_end' => array(
            'type' => 'sectionend',
            'id'   => 'ywsl_section_twitter_end'
        ),

    )
);