<?php

namespace AutomateWoo;

/**
 * @deprecated
 *
 * @class Unsubscribe_Factory
 * @since 2.9
 */
class Unsubscribe_Factory extends Factory {

	static $model = 'AutomateWoo\Unsubscribe';

	/**
	 * @param int $id
	 * @return Unsubscribe|bool
	 */
	static function get( $id ) {
		return parent::get( $id );
	}

}