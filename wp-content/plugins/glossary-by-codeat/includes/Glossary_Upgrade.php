<?php

/**
 * Glossary
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2016 GPL 2.0+
 * @license   GPL-2.0+
 * @link      http://codeat.co
 */

/**
 * The Upgrade system
 */
class Glossary_Upgrade {

    /**
     * Initialize the class
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function __construct() {
        register_activation_hook( 'glossary.php', array( $this, 'activate' ) );
        add_action( 'admin_init', array( $this, 'activate' ) );
    }

    /**
     * On activation
     *
     * @return void
     */
    public function activate() {
        if ( is_admin() ) {
            $version = get_option( 'glossary-version' );
            if ( version_compare( GT_VERSION, $version, '>' ) ) {
                $this->add_admin_cap();
                update_option( 'glossary-version', GT_VERSION );
                // Was wrong in previous release with a missing of an _
                delete_option( GT_SETTINGS . 'count_terms' );
                delete_option( GT_SETTINGS . 'count_related_terms' );
            }
        }
    }

    /**
     * Add admin capabilities
     *
     * @return void
     */
    public function add_admin_cap() {
        $caps = array(
            'create_glossaries',
            'read_glossary',
            'read_private_glossaries',
            'edit_glossary',
            'edit_glossaries',
            'edit_private_glossaries',
            'edit_published_glossaries',
            'edit_others_glossaries',
            'publish_glossaries',
            'delete_glossary',
            'delete_glossaries',
            'delete_private_glossaries',
            'delete_published_glossaries',
            'delete_others_glossaries',
            'manage_glossaries',
        );
        $roles = array(
            get_role( 'administrator' ),
            get_role( 'editor' ),
            get_role( 'author' ),
            get_role( 'contributor' ),
            get_role( 'subscriber' ),
        );
        foreach ( $roles as $role ) {
            if ( !is_null( $role ) ) {
                foreach ( $caps as $cap ) {
                    $role->add_cap( $cap );
                }
            }
        }
        $bad_caps = array(
            'create_glossaries',
            'read_private_glossaries',
            'edit_glossary',
            'edit_glossaries',
            'edit_private_glossaries',
            'edit_published_glossaries',
            'edit_others_glossaries',
            'publish_glossaries',
            'delete_glossary',
            'delete_glossaries',
            'delete_private_glossaries',
            'delete_published_glossaries',
            'delete_others_glossaries',
            'manage_glossaries',
        );
        $roles = array(
            get_role( 'author' ),
            get_role( 'contributor' ),
            get_role( 'subscriber' ),
        );
        foreach ( $roles as $role ) {
            if ( !is_null( $role ) ) {
                foreach ( $bad_caps as $cap ) {
                    $role->remove_cap( $cap );
                }
            }
        }
    }

}

new Glossary_Upgrade();

