<?php
/*
Plugin Name: WooCommerce PDF Invoices
Plugin URI: https://www.woothemes.com/products/pdf-invoices/
Description: Attach a PDF Invoice to the completed order email and allow invoices to be downloaded from customer's My Account page. 
Version: 3.7.2
Author: Andrew Benbow
Author URI: http://www.addonenterprises.com
*/

/*  Copyright 2011  Add On Enterprises LLC  (email : support@addonenterprises.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

    /**
     * Required functions
     */
    if ( ! function_exists( 'woothemes_queue_update' ) ) {
        require_once( 'woo-includes/woo-functions.php' );
    }

    /**
     * Plugin updates
     */
    woothemes_queue_update( plugin_basename( __FILE__ ), '7495e3f13cc0fa3ee07304691d12555c', '228318' );

    /**
     * Defines
     */
    define( 'PDFVERSION' , '3.7.2' );
    define( 'PDFLANGUAGE', 'woocommerce-pdf-invoice' );
    define( 'PDFSETTINGS' , admin_url( 'admin.php?page=woocommerce_pdf' ) );
    define( 'PDFSUPPORTURL' , 'http://support.woothemes.com/' );
    define( 'PDFDOCSURL' , 'http://docs.woothemes.com/document/woocommerce-pdf-invoice-setup-and-customization/');
    define( 'PDFPLUGINPATH', plugin_dir_path( __FILE__ ) );

    /**
     * Localization
     */
    $locale = apply_filters( 'plugin_locale', get_locale(), 'woocommerce-pdf-invoice' );
    load_textdomain( 'woocommerce-pdf-invoice', WP_LANG_DIR . "/woocommerce-pdf-invoice/woocommerce-pdf-invoice-$locale.mo" );
    load_plugin_textdomain( 'woocommerce-pdf-invoice', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

    /**
     * Don't do anything else unless WC is active
     */
    if ( is_woocommerce_active() ) {
        /**
         * Admin Settings
         */
        if ( is_admin() ) {
            include( 'classes/class-pdf-settings-class.php' );
        }

        /**
         * Sending PDFs and such
         */
        include( 'classes/class-pdf-send-pdf-class.php' );


        /**
         * Various PDF functions
         * - Order meta box
         * - My Account download PDF Invoice link
         */
        include( 'classes/class-pdf-functions-class.php' );

        /**
         * WPML Compatibility
         */
        include 'classes/class-wpml-integration.php';

    } // End is_woocommerce_active

    /**
     * Load Admin Class
     * Used for plugin links, seems to break if added to an include file
     * so it's got it's own class for now.
     */
    class WC_pdf_admin {

        public function __construct() {

            add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this,'plugin_links' ) );

            // Support for Order / Customer CSV Export Suite
            add_filter( 'wc_customer_order_csv_export_order_headers', array( $this, 'add_pdf_invoice_to_csv_export_column_headers' ) );
            add_filter( 'wc_customer_order_csv_export_order_row', array( $this, 'add_pdf_invoice_to_csv_export_column_data' ), 10, 3 );

        }

        /**
         * Plugin page links
         */
        public static function plugin_links( $links ) {

            $plugin_links = array(
                '<a href="' . PDFSUPPORTURL . '">' . __( 'Support', 'woocommerce-pdf-invoice' ) . '</a>',
                '<a href="' . PDFDOCSURL . '">' . __( 'Docs', 'woocommerce-pdf-invoice' ) . '</a>',
            );

            return array_merge( $plugin_links, $links );

        }

        public static function activate() {
            self::do_install_woocommerce_pdf_invoice();
        }

        public static function deactivate() {
            // empty
        }

        /**
         * Installation functions
         *
         * Create temporary folder and files. PDFs will be stored here as required
         *
         * empty_pdf_task will delete them hourly
         */
        public static function do_install_woocommerce_pdf_invoice() {

            // Install files and folders for uploading files and prevent hotlinking
            $upload_dir =  wp_upload_dir();

            $files = array(
                array(
                    'base'      => $upload_dir['basedir'] . '/woocommerce_pdf_invoice',
                    'file'      => '.htaccess',
                    'content'   => 'deny from all'
                ),
                array(
                    'base'      => $upload_dir['basedir'] . '/woocommerce_pdf_invoice',
                    'file'      => 'index.html',
                    'content'   => ''
                )
            );

            foreach ( $files as $file ) {

                if ( wp_mkdir_p( $file['base'] ) && ! file_exists( trailingslashit( $file['base'] ) . $file['file'] ) ) {

                    if ( $file_handle = @fopen( trailingslashit( $file['base'] ) . $file['file'], 'w' ) ) {
                        fwrite( $file_handle, $file['content'] );
                        fclose( $file_handle );
                    }

                }

            }

        } // do_install_woocommerce_pdf_invoice

        // add custom column headers
        public function add_pdf_invoice_to_csv_export_column_headers( $column_headers ) { 
 
            $column_headers['invoice_num']  = __('Invoice_Number', 'woocommerce-pdf-invoice');
            $column_headers['invoice_date'] = __('Invoice_Date', 'woocommerce-pdf-invoice');

            return $column_headers;
        }

        // set the data for each for custom columns
        public function add_pdf_invoice_to_csv_export_column_data( $order_data, $order, $csv_generator ) {

            if( !class_exists('WC_send_pdf') ){
                include( 'classes/class-pdf-send-pdf-class.php' );
            }

            $one_row_per_item = false;
            $new_order_data   = array();

            $nvoice_num       = esc_html( get_post_meta( $order->id, '_invoice_number_display', true ) );
            $invoice_date     = esc_html( WC_send_pdf::get_woocommerce_pdf_date( $order->id,'completed' ) );

            $pdf_data = array(
                    'invoice_num'   => $nvoice_num,
                    'invoice_date'  => $invoice_date,
            );

            // determine if the selected format is "one row per item"
            if ( version_compare( wc_customer_order_csv_export()->get_version(), '4.0.0', '<' ) ) {
                $one_row_per_item = ( 'default_one_row_per_item' === $csv_generator->order_format || 'legacy_one_row_per_item' === $csv_generator->order_format );
            // v4.0.0 - 4.0.2
            } elseif ( ! isset( $csv_generator->format_definition ) ) {
                // get the CSV Export format definition
                $format_definition = wc_customer_order_csv_export()->get_formats_instance()->get_format( $csv_generator->export_type, $csv_generator->export_format );
                $one_row_per_item = isset( $format_definition['row_type'] ) && 'item' === $format_definition['row_type'];
            // v4.0.3+
            } else {
                $one_row_per_item = 'item' === $csv_generator->format_definition['row_type'];
            }

            if ( $one_row_per_item ) {
                foreach ( $order_data as $data ) {
                    $new_order_data[] = array_merge( (array) $data, $pdf_data );
                }
            } else {
                $new_order_data = array_merge( $order_data, $pdf_data );
            }

            return $new_order_data;
        }

    } // WC_pdf_admin

    if ( is_admin() ) {

        // Load the admin class
        $GLOBALS['WC_pdf_admin'] = new WC_pdf_admin();

        // Installation and uninstallation hooks
        register_activation_hook(__FILE__, array('WC_pdf_admin', 'activate'));
        register_deactivation_hook(__FILE__, array('WC_pdf_admin', 'deactivate'));

    }

    /**
     * empty_pdf_task()
     *
     * Create CRON task to delete temporary PDFs from uploads/woocommerce_pdf_invoice/ folder
     *
     * Runs hourly
     */
    if ( ! wp_next_scheduled( 'empty_pdf_task' ) ) {
        wp_schedule_event( time(), 'hourly', 'empty_pdf_task' );
    }

    add_action( 'empty_pdf_task', 'woocommerce_pdf_invoice_empty_temp_folder' );

    function woocommerce_pdf_invoice_empty_temp_folder() {

        $upload_dir =  wp_upload_dir();
        if ( file_exists( $upload_dir['basedir'] . '/woocommerce_pdf_invoice/index.html' ) ) {
            $pdftemp = $upload_dir['basedir'] . '/woocommerce_pdf_invoice';

            $files = glob( $pdftemp . '/*.pdf' ); // get all file names
            foreach($files as $file) {

                if( is_file($file) ) {
                    unlink( $file ); // delete file
                }

            }

        }

    } // woocommerce_pdf_invoice_empty_temp_folder()

    /**
     * woocommerce_pdf_invoice_temp_folder_check()
     *
     * Make sure temporary folder and files exist.
     * usefull if site if moved from test domain and plugin is already active
     *
     * Only happens when admin visits dashboard
     */
    add_action( 'wp_dashboard_setup', 'woocommerce_pdf_invoice_temp_folder_check' );
    add_action( 'wp_user_dashboard_setup', 'woocommerce_pdf_invoice_temp_folder_check' );

    function woocommerce_pdf_invoice_temp_folder_check() {

        $upload_dir =  wp_upload_dir();
        if ( !file_exists( $upload_dir['basedir'] . '/woocommerce_pdf_invoice/.htaccess' ) ) {
            $upload_dir =  wp_upload_dir();
            WC_pdf_admin::do_install_woocommerce_pdf_invoice();
        }

    } // woocommerce_pdf_invoice_temp_folder_check()
