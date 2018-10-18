<?php

if ( ! defined( 'ABSPATH' ) )
	exit;



/**
 *  wpuxss_eml_mimes_validate
 *
 *  @type     callback function
 *  @since    1.0
 *  @created  15/10/13
 */

if ( ! function_exists( 'wpuxss_eml_mimes_validate' ) ) {

    function wpuxss_eml_mimes_validate( $input ) {

        if ( ! $input ) $input = array();


        if ( isset( $_POST['eml-restore-mime-types-settings'] ) ) {

            $input = get_site_option( 'wpuxss_eml_mimes_backup', array() );

            add_settings_error(
                'mime-types',
                'eml_mime_types_restored',
                __('MIME Types settings restored.', 'enhanced-media-library'),
                'updated'
            );
        }
        else {

            add_settings_error(
                'mime-types',
                'eml_mime_types_saved',
                __('MIME Types settings saved.', 'enhanced-media-library'),
                'updated'
            );
        }


        foreach ( $input as $type => $mime ) {

            $sanitized_type = wpuxss_eml_sanitize_extension( $type );

            if ( $sanitized_type !== $type ) {

                $input[$sanitized_type] = $input[$type];
                unset($input[$type]);
                $type = $sanitized_type;
            }

            $input[$type]['filter'] = isset( $mime['filter'] ) && !! $mime['filter'] ? 1 : 0;
            $input[$type]['upload'] = isset( $mime['upload'] ) && !! $mime['upload'] ? 1 : 0;

            $input[$type]['mime'] = sanitize_mime_type($mime['mime']);
            $input[$type]['singular'] = sanitize_text_field($mime['singular']);
            $input[$type]['plural'] = sanitize_text_field($mime['plural']);
        }

        return $input;
    }
}



/**
 *  wpuxss_eml_sanitize_extension
 *
 *  Based on the original sanitize_key
 *
 *  @since    1.0
 *  @created  24/10/13
 */

if ( ! function_exists( 'wpuxss_eml_sanitize_extension' ) ) {

    function wpuxss_eml_sanitize_extension( $key ) {

        $key = strtolower( $key );
        $key = preg_replace( '/[^a-z0-9|]/', '', $key );
        return $key;
    }
}



/**
 *  wpuxss_eml_post_mime_types
 *
 *  @since    1.0
 *  @created  03/08/13
 */

add_filter( 'post_mime_types', 'wpuxss_eml_post_mime_types' );

if ( ! function_exists( 'wpuxss_eml_post_mime_types' ) ) {

    function wpuxss_eml_post_mime_types( $post_mime_types ) {

        $wpuxss_eml_mimes = get_option('wpuxss_eml_mimes');

        if ( ! empty( $wpuxss_eml_mimes ) ) {

            foreach ( $wpuxss_eml_mimes as $extension => $mime ) {

                if ( (bool) $mime['filter'] ) {

                    $mime_type = sanitize_mime_type( $mime['mime'] );

                    $post_mime_types[$mime_type] = array(
                        esc_html( $mime['plural'] ),
                        'Manage ' . esc_html( $mime['plural'] ),
                        _n_noop( esc_html( $mime['singular'] ) . ' <span class="count">(%s)</span>', esc_html( $mime['plural'] ) . ' <span class="count">(%s)</span>' )
                    );
                }
            }
        }

        return $post_mime_types;
    }
}



/**
 *  wpuxss_eml_upload_mimes
 *
 *  Allowed mime types
 *
 *  @since    1.0
 *  @created  03/08/13
 */

add_filter('upload_mimes', 'wpuxss_eml_upload_mimes');

if ( ! function_exists( 'wpuxss_eml_upload_mimes' ) ) {

    function wpuxss_eml_upload_mimes( $existing_mimes = array() ) {

        $wpuxss_eml_mimes = get_option('wpuxss_eml_mimes');

        if ( ! empty( $wpuxss_eml_mimes ) ) {

            foreach ( $wpuxss_eml_mimes as $extension => $mime ) {

                $extension = wpuxss_eml_sanitize_extension( $extension );


                if ( (bool) $mime['upload'] ) {
                    $existing_mimes[$extension] = sanitize_mime_type( $mime['mime'] );
                }
                else {
                    unset( $existing_mimes[$extension] );
                }
            }
        }

        return $existing_mimes;
    }
}



/**
 *  wpuxss_eml_mime_types
 *
 *  All mime Types
 *
 *  @since    1.0
 *  @created  03/08/13
 */

add_filter( 'mime_types', 'wpuxss_eml_mime_types' );

if ( ! function_exists( 'wpuxss_eml_mime_types' ) ) {

    function wpuxss_eml_mime_types( $default_mimes ) {

        $new_mimes = array();
        $wpuxss_eml_mimes = get_option( 'wpuxss_eml_mimes' );

        if ( false !== $wpuxss_eml_mimes ) {

            foreach ( $wpuxss_eml_mimes as $extension => $mime ) {

                $extension = wpuxss_eml_sanitize_extension( $extension );
                $new_mimes[$extension] = sanitize_mime_type( $mime['mime'] );
            }

            return $new_mimes;
        }

        return $default_mimes;
    }
}

?>
