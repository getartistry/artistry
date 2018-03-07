<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Iconic_WooThumbs_Transition_Settings.
 *
 * Transition any old settings to new ones
 *
 * @class    Iconic_WooThumbs_Transition_Settings
 * @version  1.0.0
 * @package  Iconic_WooThumbs
 * @category Class
 * @author   Iconic
 */
class Iconic_WooThumbs_Transition_Settings {

    /*
     * Construct
     */
    function __construct() {

        $this->transition_redux();
        $this->transition_meta();

    }

    /**
     * Transition: Redux
     */
    public function transition_redux() {

        $redux_settings = get_option( 'jck-wt' );

        if( !$redux_settings )
            return;

        $new_settings = array();

        foreach( $redux_settings as $key => $value ) {

            switch ($key) {
                case 'slideMode':
                    $new_settings['carousel_general_mode'] = $value;
                    break;
                case 'slideSpeed':
                    $new_settings['carousel_general_transition_speed'] = $value;
                    break;
                case 'slideAutoplay':
                    $new_settings['carousel_general_autoplay'] = $value;
                    break;
                case 'slideDuration':
                    $new_settings['carousel_general_duration'] = $value;
                    break;
                case 'largeImageSize':
                    $new_settings['display_general_large_image_size'] = $value;
                    break;
                case 'iconColour':
                    $new_settings['display_general_icon_colours'] = $value;
                    break;
                case 'hoverIcons':
                    $new_settings['display_general_icons_hover'] = $value;
                    break;
                case 'iconTooltips':
                    $new_settings['display_general_icons_tooltips'] = $value;
                    break;
                case 'enableInfiniteLoop':
                    $new_settings['carousel_general_infinite_loop'] = $value;
                    break;
                case 'sliderWidth':
                    $new_settings['display_general_width'] = str_replace(array('px','%','em'), '', $value['width']);
                    break;
                case 'sliderPosition':
                    $new_settings['display_general_position'] = $value;
                    break;
                case 'enableArrows':
                    $new_settings['navigation_general_controls'] = $value;
                    break;
                case 'enableNavigation':
                    $new_settings['navigation_thumbnails_enable'] = $value;
                    break;
                case 'navigationType':
                    $new_settings['navigation_thumbnails_type'] = $value;
                    break;
                case 'enableNavigationControls':
                    $new_settings['navigation_thumbnails_controls'] = $value;
                    break;
                case 'thumbnailLayout':
                    $new_settings['navigation_thumbnails_position'] = $value;
                    break;
                case 'thumbnailWidth':
                    $new_settings['navigation_thumbnails_width'] = $value;
                    break;
                case 'thumbnailCount':
                    $new_settings['navigation_thumbnails_count'] = $value;
                    break;
                case 'thumbnailSpeed':
                    $new_settings['navigation_thumbnails_transition_speed'] = $value;
                    break;
                case 'thumbnailSpacing':
                    $new_settings['navigation_thumbnails_spacing'] = $value;
                    break;
                case 'enableZoom':
                    $new_settings['zoom_general_enable'] = $value;
                    break;
                case 'zoomType':
                    $new_settings['zoom_general_zoom_type'] = $value;
                    break;
                case 'innerShape':
                    $new_settings['zoom_follow_zoom_zoom_shape'] = $value;
                    break;
                case 'zoomPosition':
                    $new_settings['zoom_outside_zoom_zoom_position'] = $value;
                    break;
                case 'zoomDimensions':
                    $new_settings['zoom_outside_follow_zoom_lens_width'] = $value['width'];
                    $new_settings['zoom_outside_follow_zoom_lens_height'] = $value['height'];
                    break;
                case 'lensColour':
                    $new_settings['zoom_outside_zoom_lens_colour'] = $value;
                    break;
                case 'lensOpacity':
                    $new_settings['zoom_outside_zoom_lens_opacity'] = $value;
                    break;
                case 'enableLightbox':
                    $new_settings['fullscreen_general_enable'] = $value;
                    break;
                case 'clickAnywhere':
                    $new_settings['fullscreen_general_click_anywhere'] = $value;
                    break;
                case 'enableImageTitle':
                    $new_settings['fullscreen_general_image_title'] = $value;
                    break;
                case 'enableBreakpoint':
                    $new_settings['responsive_general_breakpoint_enable'] = $value;
                    break;
                case 'breakpoint':
                    $new_settings['responsive_general_breakpoint'] = str_replace(array('px','%','em'), '', $value['width']);
                    break;
                case 'sliderWidthBreakpoint':
                    $new_settings['responsive_general_width'] = str_replace(array('px','%','em'), '', $value['width']);
                    break;
                case 'sliderPositionBreakpoint':
                    $new_settings['responsive_general_position'] = $value;
                    break;
                case 'thumbnailsBelowBreakpoint':
                    $new_settings['responsive_general_thumbnails_below'] = $value;
                    break;
                case 'thumbnailCountBreakpoint':
                    $new_settings['responsive_general_thumbnails_count'] = $value;
                    break;
            }

        }

        add_option( 'iconic_woothumbs_settings', $new_settings );
        delete_option( 'jck-wt' );

    }

    /**
     * Transition: Product meta
     */
    public function transition_meta() {

        global $wpdb;

        $old_meta = $wpdb->get_row( "SELECT * FROM $wpdb->postmeta WHERE meta_key = '_jck_wt'" );

        if( !$old_meta )
            return;

        $wpdb->update(
            $wpdb->postmeta,
            array( 'meta_key' => '_iconic_woothumbs' ),
            array( 'meta_key' => '_jck_wt' ),
            array( '%s' ),
            array( '%s' )
        );

    }

}