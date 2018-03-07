<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Abstract_Generate_Coupon
 */
abstract class Variable_Abstract_Generate_Coupon extends Variable {


	function load_admin_details() {

		$this->description = sprintf(
			__( "Generates a unique coupon based on a template coupon. <%s>View documentation<%s>.", 'automatewoo' ),
			'a href="'. Admin::get_docs_link( 'variables/personalized-coupons', 'variable-description' ) . '" target="_blank"',
			'/a'
		);

		$this->add_parameter_text_field( 'template', __( "Name of the coupon that will be cloned.", 'automatewoo'), true );
		$this->add_parameter_text_field( 'expires', __( "Number of days the coupon will be valid for. If left blank then the expiry set for the template coupon will be used.", 'automatewoo' ) );
		$this->add_parameter_text_field( 'prefix', __( "The prefix for the coupon code, defaults to 'aw-'. To remove the prefix set this field to a single space character.", 'automatewoo'), false, 'aw-' );
		$this->add_parameter_text_field( 'prefix', __( "The prefix for the coupon code, defaults to 'aw-'. To remove the prefix set this field to a single space character.", 'automatewoo'), false, 'aw-' );
		$this->add_parameter_text_field( 'limit', __( "The number of times the generated coupon can be used. Set to '0' for unlimited.", 'automatewoo'), false, '1' );
	}


	/**
	 * @param $email string
	 * @param $parameters array
	 * @param $workflow Workflow
	 * @return bool|string
	 */
	function generate_coupon( $email, $parameters, $workflow ) {

		// requires a template
		if ( empty( $parameters['template'] ) )
			return false;

		$coupon = new Coupon_Generator();
		$coupon->set_template_coupon_code( $parameters['template'] );

		if ( ! $coupon->get_template_coupon_id() ) {
			return false;
		}

		// override with parameter
		if ( isset( $parameters['prefix'] ) ) {
			$coupon->set_prefix( $parameters['prefix'] );
		}

		if ( $workflow->is_test_mode() ) {
			$coupon->set_suffix('[test]');
			$coupon->set_description( __( 'AutomateWoo Test Coupon', 'automatewoo' ) );
		}

		$coupon->set_code( $coupon->generate_code() );

		// filter to enable use of coupon email restriction
		if ( apply_filters( 'automatewoo/variables/coupons/use_email_restriction', false, $workflow, $email, $parameters ) ) {
			$coupon->set_email_restriction( $email );
		}

		// don't generate a new coupon every time we preview
		if ( $workflow->is_preview_mode() ) {
			return $coupon->code;
		}

		if ( ! empty( $parameters['expires'] ) ) {
			$coupon->set_expires( $parameters['expires'] );
		}

		if ( isset( $parameters['limit'] ) ) {
			$coupon->set_usage_limit( $parameters['limit'] );
		}

		if ( ! $coupon = $coupon->generate_coupon() ) {
			return false;
		}

		if ( $workflow->is_test_mode() ) {
			Compat\Coupon::update_meta( $coupon, '_is_aw_test_coupon', true );
		}


		// store customer ID and workflow ID
		if ( $customer = Customer_Factory::get_by_email( $email ) ) {
			Compat\Coupon::update_meta( $coupon, '_aw_customer_id', $customer->get_id() );
		}

		Compat\Coupon::update_meta( $coupon, '_aw_workflow_id', $workflow->get_id() );


		return Compat\Coupon::get_code( $coupon );
	}
}
