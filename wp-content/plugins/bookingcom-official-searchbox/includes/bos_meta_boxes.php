<?php
/**
 * META BOXES
 * ----------------------------------------------------------------------------
 */
/* Add a destination metabox to pages */
add_action( 'add_meta_boxes', 'bos_mb_add' );
function bos_mb_add( ) {
                $default_post_types           = array(
                                 'post',
                                'page' 
                );
                $post_types                   = $default_post_types;
                $options                      = bos_searchbox_retrieve_all_user_options();
                $display_in_custom_post_types = !empty( $options[ 'display_in_custom_post_types' ] ) && isset( $options[ 'display_in_custom_post_types' ] ) ? $options[ 'display_in_custom_post_types' ] : '';
                // Check if we have a value 
                if ( !empty( $display_in_custom_post_types ) ) {
                                //check if there is a comma as last character
                                $display_in_custom_post_types_length = strlen( $display_in_custom_post_types );
                                if ( $display_in_custom_post_types[ $display_in_custom_post_types_length - 1 ] === ',' ) {
                                                // Delete last character if is a comma 
                                                $display_in_custom_post_types = substr( $display_in_custom_post_types, 0, -1 );
                                } //$display_in_custom_post_types[ $display_in_custom_post_types_length - 1 ] === ','
                                if ( $display_in_custom_post_types[ 0 ] === ',' ) {
                                                // Delete first character if is a comma 
                                                $display_in_custom_post_types = substr( $display_in_custom_post_types, 0, 1 );
                                } //$display_in_custom_post_types[ 0 ] === ','
                                //If we have multiple entries splitted by commas
                                if ( substr_count( $display_in_custom_post_types, ',' ) > 0 ) {
                                                $display_in_custom_post_types = explode( ',', $display_in_custom_post_types );
                                                $post_types                   = array_merge( $default_post_types, $display_in_custom_post_types );
                                } //substr_count( $display_in_custom_post_types, ',' ) > 0
                                // else insert just the single custom type slug getting rid of any eventual space
                                else {
                                                $post_types[ ] = trim( $display_in_custom_post_types );
                                }
                } //!empty( $display_in_custom_post_types )
                foreach ( $post_types as $post_type ) {
                                add_meta_box( 'bos_dest', __( 'Booking.com search box destination', 'bookingcom-official-searchbox' ), 'bos_mb_create', $post_type, 'normal', 'high' );
                } //$post_types as $post_type
}
function bos_mb_create( $post ) {
                $bos_mb_info_icon   = '<a href="#" id="bos_mb_info_displayer" title="Info box"><img  style="border: none;" src="' . BOS_IMG_PLUGIN_DIR . '/bos_info_icon.png" alt="info"></a>';
                $output             = '';
                $bos_mb_destination = get_post_meta( $post->ID, '_bos_mb_destination', true ); // underscore close to the variable make not displaying value in the default custom field
                $bos_mb_dest_type   = get_post_meta( $post->ID, '_bos_mb_dest_type', true );
                $bos_mb_dest_id     = get_post_meta( $post->ID, '_bos_mb_dest_id', true );
                $output .= __( 'Use the following fields to select a location for your Booking.com search box widget for this specific post or page', 'bookingcom-official-searchbox' );
                $bos_mb_dest_placeholder = '';
                if ( empty( $bos_mb_destination ) || $bos_mb_destination == ' ' || $bos_mb_destination == '' ) {
                                $bos_mb_dest_placeholder = ' placeholder="' . __( 'e.g. Amsterdam', 'bookingcom-official-searchbox' ) . '" ';
                } //empty( $bos_mb_destination ) || $bos_mb_destination == ' ' || $bos_mb_destination == ''
                $bos_mb_label_style = 'style="font-weight: bold;margin-right: 5px; display: inline-block;"';
                // Destination   
                $output .= '<p class="bos_mb_p"><label for="bos_mb_destination" ' . $bos_mb_label_style . '>' . __( 'Destination', 'bookingcom-official-searchbox' ) . '</label>';
                $output .= '&nbsp;<input style="width: 350px;" class="bos_mb_field bos_mb_text" type="text" name="bos_mb_destination" value="' . esc_attr( trim( $bos_mb_destination ) ) . '" ' . $bos_mb_dest_placeholder . '>';
                $output .= '</p>';
                // Destination type
                $output .= '<p class="bos_mb_p"><label for="bos_mb_dest_type" ' . $bos_mb_label_style . '>' . __( 'Destination type', 'bookingcom-official-searchbox' ) . '</label>';
                $output .= '&nbsp;<select class="bos_mb_field bos_mb_select" name="bos_mb_dest_type" >';
                $output .= '<option value="select" ' . selected( 'select', $bos_mb_dest_type, false ) . ' >' . __( 'select...', 'bookingcom-official-searchbox' ) . '</option>';
                $output .= '<option value="city" ' . selected( 'city', $bos_mb_dest_type, false ) . ' >' . __( 'city', 'bookingcom-official-searchbox' ) . '</option>';
                $output .= '<option value="landmark" ' . selected( 'landmark', $bos_mb_dest_type, false ) . ' >' . __( 'landmark', 'bookingcom-official-searchbox' ) . '</option>';
                $output .= '<option value="district" ' . selected( 'district', $bos_mb_dest_type, false ) . ' >' . __( 'district', 'bookingcom-official-searchbox' ) . '</option>';
                $output .= '<option value="region" ' . selected( 'region', $bos_mb_dest_type, false ) . ' >' . __( 'region', 'bookingcom-official-searchbox' ) . '</option>';
                $output .= '</select>';
                $output .= '</p>';
                // Destination id
                $bos_mb_dest_id_placeholder = '';
                if ( empty( $bos_mb_dest_id ) || $bos_mb_dest_id == ' ' || $bos_mb_dest_id == '' ) {
                                $bos_mb_dest_id_placeholder = ' placeholder="' . __( 'e.g. -2140479 for Amsterdam', 'bookingcom-official-searchbox' ) . '" ';
                } //empty( $bos_mb_dest_id ) || $bos_mb_dest_id == ' ' || $bos_mb_dest_id == ''
                $output .= '<p class="bos_mb_p"><label for="bos_mb_dest_id" ' . $bos_mb_label_style . '">' . __( 'Destination ID ( e.g. -2140479 for Amsterdam )', 'bookingcom-official-searchbox' ) . '</label>';
                $output .= '&nbsp;<input  style="width: 200px;"  class="bos_mb_field bos_mb_text" type="text" name="bos_mb_dest_id" value="' . esc_attr( trim( $bos_mb_dest_id ) ) . '" ' . $bos_mb_dest_id_placeholder . '>&nbsp;' . $bos_mb_info_icon;
                $output .= '</p>';
                $output .= '<div id="bos_mb_info_box" style="display: none;padding: 1em; background-color:#FFFFE0;border:1px solid  #E6DB55; margin:10px 0 10px;">';
                $output .= __( 'For more info on your destination ID, login to the <a href="https://admin.booking.com/partner/" target="_blank">Partner Center</a>. Check <em>&quot;URL constructor&quot;</em> section to find your destination ID. These IDs, also known as UFIs, are usually a negative number ( e.g. <strong>-2140479 is for Amsterdam</strong> , but can be positive ones in the US ) while regions, district and landmarks are always positive ( e.g. <strong>1408 is for Ibiza</strong> ).', 'bookingcom-official-searchbox' );
                $output .= '</div>';
                $output .= '<script>(function($) { $(function(){';
                $output .= '$(document).ready(function(){ $( "#bos_mb_info_displayer" ).click(function( event ) { event.preventDefault(); $( "#bos_mb_info_box" ).toggle(); }); });';
                $output .= '});})(jQuery);</script>';
                echo $output;
}
// Save meta box values
add_action( 'save_post', 'bos_mb_save_data' );
function bos_mb_save_data( $post_id ) {
                if ( isset( $_POST[ 'bos_mb_destination' ] ) ) { // update meta box values if destintion exists
                                update_post_meta( $post_id, '_bos_mb_destination', strip_tags( $_POST[ 'bos_mb_destination' ] ) );
                                update_post_meta( $post_id, '_bos_mb_dest_type', strip_tags( $_POST[ 'bos_mb_dest_type' ] ) );
                                update_post_meta( $post_id, '_bos_mb_dest_id', strip_tags( $_POST[ 'bos_mb_dest_id' ] ) );
                } //isset( $_POST[ 'bos_mb_destination' ] )
}
?>