<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

/**
 * Compatibility Class
 *
 * @class   YITH_WCPB_Compatibility_Premium
 * @package Yithemes
 * @since   1.1.15
 * @author  Yithemes
 *
 */
class YITH_WCPB_Compatibility_Premium extends YITH_WCPB_Compatibility {

    /** @var \YITH_WCPB_Compatibility_Premium
     */
    protected static $_instance;

    /** @var YITH_WCPB_Wpml_Compatibility_Premium */
    public $wpml;

    /**
     * set the plugins
     */
    protected function _set_plugins() {
        $this->_plugins = array(
            'wpml'            => array(
                'always_enabled' => true,
            ),
            'dynamic'         => array(
                'always_enabled' => true,
            ),
            'pdf-invoice'     => array(
                'always_enabled' => true,
            ),
            'role-based'      => array(),
            'request-a-quote' => array(),
            'catalog-mode'    => array(),
        );
    }

    /**
     * Check if user has plugin
     *
     * @param string $plugin_name
     *
     * @author  Leanza Francesco <leanzafrancesco@gmail.com>
     * @since   1.1.15
     * @return bool
     */
    static function has_plugin( $plugin_name ) {
        switch ( $plugin_name ) {
            case 'catalog-mode':
                return defined( 'YWCTM_PREMIUM' ) && YWCTM_PREMIUM && defined( 'YWCTM_VERSION' ) && version_compare( YWCTM_VERSION, '1.4.8', '>=' );
            case 'role-based':
                return defined( 'YWCRBP_PREMIUM' ) && YWCRBP_PREMIUM && defined( 'YWCRBP_VERSION' ) && version_compare( YWCRBP_VERSION, '1.0.9', '>=' );
            case 'request-a-quote':
                return defined( 'YITH_YWRAQ_PREMIUM' ) && YITH_YWRAQ_PREMIUM && defined( 'YITH_YWRAQ_VERSION' ) && version_compare( YITH_YWRAQ_VERSION, '1.5.7', '>=' );
            default:
                return false;
        }
    }
}