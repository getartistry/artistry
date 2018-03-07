<?php
/**
 * Order table. Can only be used with the order.items variable
 * Override this template by copying it to yourtheme/automatewoo/email/order-table.php
 *
 * @var WC_Order $order
 * @var AutomateWoo\Workflow $workflow
 * @var string $variable_name
 * @var string $data_type
 * @var string $data_field
 */

if ( ! defined( 'ABSPATH' ) ) exit;

do_action( 'woocommerce_email_order_details', $order, false, false, '' );
