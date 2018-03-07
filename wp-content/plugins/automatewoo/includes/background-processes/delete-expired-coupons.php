<?php

namespace AutomateWoo\Background_Processes;

use AutomateWoo\Clean;
use AutomateWoo\Compat;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Background processor to delete expired coupons
 */
class Delete_Expired_Coupons extends Base {

	/** @var string  */
	public $action = 'delete_expired_coupons';


	/**
	 * @param int $coupon_id
	 * @return bool
	 */
	protected function task( $coupon_id ) {

		$coupon_id = Clean::id( $coupon_id );
		$days_to_keep_expired = (int) apply_filters( 'automatewoo/coupons/days_to_keep_expired', 14 );

		$delete_date = new \DateTime();
		$delete_date->modify( "-$days_to_keep_expired days" );

		$expires_timestamp = Compat\Coupon::get_date_expires_by_id( $coupon_id );

		if ( ! $expires_timestamp || ! is_numeric( $expires_timestamp ) ) {
			return false;
		}

		if ( $expires_timestamp < $delete_date->getTimestamp() ) {
			wp_delete_post( $coupon_id, true );
		}

		return false;
	}

}

return new Delete_Expired_Coupons();
