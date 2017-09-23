<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
if ( ! defined( 'YITH_WCMS_VERSION' ) ) {
	exit( 'Direct access forbidden.' );
}

/**
 *
 *
 * @class      YITH_Multistep_Checkout_Admin_Premium
 * @package    Yithemes
 * @since      Version 1.0.0
 * @author     Andrea Grillo <andrea.grillo@yithemes.com>
 *
 */

if ( ! class_exists( 'YITH_Multistep_Checkout_Admin_Premium' ) ) {
	/**
	 * Class YITH_Multistep_Checkout_Admin_Premium
	 *
	 * @author Andrea Grillo <andrea.grillo@yithemes.com>
	 */
	class YITH_Multistep_Checkout_Admin_Premium extends YITH_Multistep_Checkout_Admin {

        /**
         * Construct
         *
         * @author Andrea Grillo <andrea.grillo@yithemes.com>
         * @since  1.0
         */
        public function __construct() {
            $this->show_premium_landing = false;

            // register plugin to licence/update system
			add_action( 'wp_loaded', array( $this, 'register_plugin_for_activation' ), 99 );
			add_action( 'admin_init', array( $this, 'register_plugin_for_updates' ) );

            /* === Premium Options === */
            add_filter( 'yith_wcms_admin_tabs', array( $this, 'admin_tabs' ) );
            add_action( 'woocommerce_admin_field_yith_timeline_template_style', array( $this, 'timeline_template_option' ), 10, 1 );
            add_filter( 'yith_wcms_settings_options', array( $this, 'settings_options' ) );

            /* === Enqueue Scripts === */
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

            /* === WooCommerce Options Customizzation === */
            add_action( 'woocommerce_admin_field_yith_wcms_title', array( $this, 'option_section_title' ) );
            add_action( 'woocommerce_admin_field_yith_wcms_media_upload', array( $this, 'option_media_upload' ) );

            parent::__construct();
        }

        /**
         * Add premium admin tabs options
         *
         * @param $free Array The tabs array
         *
         * @author Andrea Grillo <andrea.grillo@yithemes.com>
         * @since 1.0.0
         * @return Array The tabs array
         */
        public function admin_tabs( $free ){
            $premium = array(
                'timeline'          => _x( 'Timeline and Button', 'Admin: Page title', 'yith-woocommerce-multi-step-checkout' ),
                'order_received'    => _x( '"Order Received" & "My Account" Page', 'Admin: Page title', 'yith-woocommerce-multi-step-checkout' ),
            );

            return array_merge( $free, $premium );
        }

        /**
         * Custom WooCommerce Option
         *
         * @param $value The Array value
         *
         * @author Andrea Grillo <andrea.grillo@yithemes.com>
         * @since 1.0.0
         * @return Array The tabs array
         */
        public function timeline_template_option( $value ) {

            $description = $value['desc'];
            $option_value = get_option( $value['id'], $value['default'] );

            ?>
            <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
            </th>
            <td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
                <select
                    name="<?php echo esc_attr( $value['id'] ); ?><?php if ( $value['type'] == 'multiselect' ) {
                        echo '[]';
                    } ?>"
                    id="<?php echo esc_attr( $value['id'] ); ?>"
                    style="<?php echo esc_attr( $value['css'] ); ?>"
                    class="<?php echo esc_attr( $value['class'] ); ?>"
                    <?php echo ( 'multiselect' == $value['type'] ) ? 'multiple="multiple"' : ''; ?>
                    >
                    <?php
                    foreach ( $value['options'] as $key => $val ) {
                        ?>
                        <option value="<?php echo esc_attr( $key ); ?>" <?php

                            if ( is_array( $option_value ) ) {
                                selected( in_array( $key, $option_value ), true );
                            }
                            else {
                                selected( $option_value, $key );
                            }

                            ?>><?php echo $val ?></option>
                    <?php
                    }
                    ?>
                </select> <?php echo $description; ?>
            </td>
            </tr>
        <tr>
            <th scope="row" class="titledesc">
                <?php _ex( 'Preview:', 'Admin: option description', 'yith-woocommerce-multi-step-checkout' ) ?>
            </th>
            <td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
                <img src="<?php echo YITH_WCMS_ASSETS_URL . 'images/multi-step.jpg' ?>" alt="<?php _ex( 'Timeline Style', 'HTML: alt attribute', 'yith-woocommerce-multi-step-checkout' )?>"/>
            </td>
        </tr>
        <?php
        }

         /**
         * Admin enqueue scripts
         *
         *
         * @author Andrea Grillo <andrea.grillo@yithemes.com>
         * @since 1.0.0
         * @return void
         */
        public function enqueue_scripts(){
            wp_register_script( 'yith-wcms-admin', YITH_WCMS_ASSETS_URL . 'js/admin.js', array( 'jquery' ), YITH_WCMS_VERSION, 'true' );
            wp_register_script( 'yith-wcms-admin-upload', YITH_WCMS_ASSETS_URL . 'js/admin-upload.js', array( 'jquery', 'thickbox', 'media-upload' ), YITH_WCMS_VERSION, 'true' );

            $is_plugin_panel    = ! empty( $_GET['page'] )  && $_GET['page'] == $this->get_panel_page();
            $is_timeline_tab    = ! empty( $_GET['tab'] )   && 'timeline' == $_GET['tab'];

            if ( $is_plugin_panel ){
                wp_enqueue_script( 'yith-wcms-admin' );
            }

            if ( $is_plugin_panel && $is_timeline_tab ) {
                wp_enqueue_style( 'thickbox' );
                wp_enqueue_script( 'yith-wcms-admin-upload' );
                wp_localize_script( 'yith-wcms-admin-upload', 'yith_wcms', yith_wcms_checkout_timeline_default_icon( 'all' ) );
            }
        }

        /**
         * Custom WooCommerce title option
         *
         * @author Andrea Grillo <andrea.grillo@yithemes.com>
         * @since  1.0.0
         *
         * @param $value array The option value array
         *
         * @return void
         */
        public function option_section_title( $value ) {

            if ( ! empty( $value['title'] ) ) {
                echo '<h3 class="yith_wcms_title ' . $value['refer_to'] . '">' . esc_html( $value['title'] ) . '</h3>';
            }
            if ( ! empty( $value['desc'] ) ) {
                echo wpautop( wptexturize( wp_kses_post( $value['desc'] ) ) );
            }
            echo '<table class="form-table yith_wcms_table ' . $value['refer_to'] . '">' . "\n\n";
            if ( ! empty( $value['id'] ) ) {
                do_action( 'woocommerce_settings_' . sanitize_title( $value['id'] ) );
            }
        }

         /**
         * Custom WooCommerce upload option
         *
         * @author Andrea Grillo <andrea.grillo@yithemes.com>
         * @since  1.0.0
         *
         * @param $value array The option value array
         *
         * @return void
         */
        public function option_media_upload( $value ){
            $image_id = get_option( $value['id'], $value['default'] );
            $args = array(
                'image_wrapper_id'      => 'yith_wcms_image_wrapper_id_' .      $value['custom_attributes']['data-step'],
                'hidden_field_id'       => 'yith_wcms_hidden_field_id_' .       $value['custom_attributes']['data-step'],
                'hidden_field_name'     => 'yith_wcms_hidden_field_name_' .     $value['custom_attributes']['data-step'],
                'remove_image_button'   => 'yith_wcms_remove_image_button_' .   $value['custom_attributes']['data-step'],
                'upload_image_button'   => 'yith_wcms_upload_image_button',
            );

            extract($args);
            ob_start(); ?>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
                </th>
                <td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
                    <div id="<?php echo $args['image_wrapper_id']; ?>" class="yith-wcms-icon-preview" style="background-color: #e2e2e2; padding: 5px; display: inline-block; margin: 0 10px 10px 0;">
                        <img src="<?php echo ! is_numeric( $image_id ) ? $value['default'] : wp_get_attachment_url( $image_id ); ?>" style="max-height: 50px; width: auto;" />
                    </div>

                    <input type="hidden" id="<?php echo $value['id'] ?>" name="<?php echo $value['id'] ?>" value="<?php echo is_numeric( $image_id ) ? $image_id : '' ?>" data-default="<?php echo is_numeric( $image_id ) ? 'no' : 'yes' ?>"/>
                    <button style="vertical-align: bottom; margin-bottom: 10px;" type="button" class="<?php echo $upload_image_button; ?> button" data-step="<?php echo $value['custom_attributes']['data-step']; ?>"><?php _e( 'Upload/Add Icon', 'yith-woocommerce-product-vendors' ); ?></button>
                    <button style="vertical-align: bottom; margin-bottom: 10px;" type="button" id="<?php echo $remove_image_button ?>" class="button yith_wcms_remove_image_button" data-step="<?php echo $value['custom_attributes']['data-step']; ?>" data-default="<?php echo is_numeric( $image_id ) ? 'no' : 'yes' ?>"><?php _e( 'Restore default icon', 'yith-woocommerce-product-vendors' ); ?></button>
                    <span class="description" style="display: block;"><?php echo $value['desc']; ?></span>
                </td>
            </tr>
            <?php echo ob_get_clean();
        }

		/**
		 * @param $old
		 *
		 * @return array
		 */
        public function settings_options( $old ){
            $new = array(
                'settings_options_pro_start'    => array(
                    'type' => 'sectionstart',
                ),

                'settings_options_pro_title'    => array(
                    'title' => _x( 'AJAX validation', 'Panel: page title', 'yith-woocommerce-multi-step-checkout' ),
                    'type'  => 'title',
                    'desc'  => '',
                ),

                'settings_options_pro_ajax validator' => array(
                    'title'   => _x( 'Enable AJAX validation in Multi-step Checkout', 'Admin option: Enable plugin', 'yith-woocommerce-multi-step-checkout' ),
                    'type'    => 'checkbox',
                    'desc'    => _x( "Prevent users from proceeding to the next step if they haven't first filled in mandatory fields", 'Admin option description: Enable live validation', 'yith-woocommerce-multi-step-checkout' ),
                    'id'      => 'yith_wcms_enable_ajax_validator',
                    'default' => 'no'
                ),

                'settings_options_pro_end'      => array(
                    'type' => 'sectionend',
                ),

                'settings_options_last_step_start'    => array(
                    'type' => 'sectionstart',
                ),

                'settings_options_last_step_title'    => array(
                    'title' => _x( 'Payments tabs', 'Panel: page title', 'yith-woocommerce-multi-step-checkout' ),
                    'type'  => 'title',
                    'desc'  => '',
                ),

                'settings_options_last_step_check' => array(
                    'title'   => _x( 'Show order total amount in Payment tab', 'Admin option: Enable featrues', 'yith-woocommerce-multi-step-checkout' ),
                    'type'    => 'checkbox',
                    'id'      => 'yith_wcms_show_amount_on_payments',
                    'default' => 'no'
                ),

                'settings_options_last_step_check_text' => array(
                    'title'   => _x( 'Customize your text', 'Admin option: Enable featrues', 'yith-woocommerce-multi-step-checkout' ),
                    'type'    => 'text',
                    'desc'    => 'e.g.: Order total amount: 13,00$ (includes 0,60$ VAT)',
                    'id'      => 'yith_wcms_show_amount_on_payments_text',
                    'default' => __( 'Order total amount', 'yith-woocommerce-multi-step-checkout' ),
                    'style' => array(
                            'width' => '250px'
                    )
                ),

                'settings_options_last_step_end'      => array(
                    'type' => 'sectionend',
                ),
            );

            return array_merge( $old, $new );
        }

        /**
		 * Register plugins for activation tab
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function register_plugin_for_activation() {
			YIT_Plugin_Licence()->register( YITH_WCMS_INIT, YITH_WCMS_SECRETKEY, YITH_WCMS_SLUG );
		}

		/**
		 * Register plugins for update tab
		 *
		 * @return void
		 * @since 2.0.0
		 */
		public function register_plugin_for_updates() {
			YIT_Upgrade()->register( YITH_WCMS_SLUG, YITH_WCMS_INIT );
		}
    }
}