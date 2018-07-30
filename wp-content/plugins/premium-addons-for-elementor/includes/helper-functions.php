<?php

namespace PremiumAddons;

if(!defined('ABSPATH')) exit;

class Helper_Functions {
    
    public static function is_show_rate(){
        
        if( defined('PREMIUM_PRO_ADDONS_VERSION') ) {
            if(isset(get_option('pa_wht_lbl_save_settings')['premium-wht-lbl-rate'])){
                $show_rate = get_option('pa_wht_lbl_save_settings')['premium-wht-lbl-rate'];
            }
        }
        
        return isset( $show_rate ) ? $show_rate : false;
    }
    
    public static function is_show_about(){
        
        if( defined('PREMIUM_PRO_ADDONS_VERSION') ) {
            if(isset(get_option('pa_wht_lbl_save_settings')['premium-wht-lbl-about'])){
                $show_about = get_option('pa_wht_lbl_save_settings')['premium-wht-lbl-about'];
            }
        }
        
        return isset( $show_about ) ? $show_about : false;
    }
    
    public static function is_show_version_control(){
        
        if( defined('PREMIUM_PRO_ADDONS_VERSION') ) {
                if(isset(get_option('pa_wht_lbl_save_settings')['premium-wht-lbl-version'])){
                    $show_version_tab = get_option('pa_wht_lbl_save_settings')['premium-wht-lbl-version'];
            }
        }
        
        return isset( $show_version_tab ) ? $show_version_tab : false;
    }
    
    public static function author(){
        
        if( defined('PREMIUM_PRO_ADDONS_VERSION') ) {
            if(isset(get_option('pa_wht_lbl_save_settings')['premium-wht-lbl-name'])){
                $author_free = get_option('pa_wht_lbl_save_settings')['premium-wht-lbl-name'];
            }
        }
        
        return ( isset($author_free) && '' != $author_free ) ? $author_free : 'Leap13';
    }
    
    public static function name(){
        
        if( defined('PREMIUM_PRO_ADDONS_VERSION') ) {
            if(isset(get_option('pa_wht_lbl_save_settings')['premium-wht-lbl-plugin-name'])){
                $name_free = get_option('pa_wht_lbl_save_settings')['premium-wht-lbl-plugin-name'];
            }
        }
        
        return ( isset($name_free) && '' != $name_free ) ? $name_free : 'Premium Addons for Elementor';
    }
   
    public static function is_show_logo(){
        
        if( defined('PREMIUM_PRO_ADDONS_VERSION') ) {
            if(isset(get_option('pa_wht_lbl_save_settings')['premium-wht-lbl-logo'])){
                $show_logo = get_option('pa_wht_lbl_save_settings')['premium-wht-lbl-logo'];
            }
        }
        
        return isset( $show_logo ) ? $show_logo : false;
    }
    
    public static function get_category(){
        
        if( defined('PREMIUM_PRO_ADDONS_VERSION') ) {
            if(isset(get_option('pa_wht_lbl_save_settings')['premium-wht-lbl-short-name'])){
                $category = get_option('pa_wht_lbl_save_settings')['premium-wht-lbl-short-name'];
            }
        }
        
        return ( isset($category) && '' != $category ) ? $category : 'Premium Addons';
        
    }
    
    public static function get_prefix(){
        
        if( defined('PREMIUM_PRO_ADDONS_VERSION') ) {
            if(isset(get_option('pa_wht_lbl_save_settings')['premium-wht-lbl-prefix'])){
                $prefix = get_option('pa_wht_lbl_save_settings')['premium-wht-lbl-prefix'];
            }
        }
        
        return ( isset($prefix) && '' != $prefix ) ? $prefix : 'Premium';
    }
}