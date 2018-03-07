<?php

if ( ! defined( 'ABSPATH' ) ) exit;


/** @deprecated */
abstract class AW_Integration extends AutomateWoo\Integration {}

/** @deprecated */
abstract class AW_Action extends AutomateWoo\Action {

	function __construct() {
		add_action( 'automatewoo_init_actions', [ $this, 'init' ] );
		parent::__construct();
	}

	function init() {
		AutomateWoo\Actions::$loaded[ $this->name ] = $this;
	}

}

/** @deprecated */
abstract class AW_Trigger extends AutomateWoo\Trigger {

	function __construct() {
		$this->group = __( 'Other', 'automatewoo' );
		add_action( 'automatewoo_init_triggers', [ $this, 'init' ] );
	}

	function init() {
		$this->register_hooks();
		AutomateWoo\Triggers::$loaded[ $this->get_name() ] = $this;
	}
}


/** @deprecated */
abstract class AW_Report_List_Table extends AutomateWoo\Admin_List_Table {}

/** @deprecated */
abstract class AW_Data_Type extends AutomateWoo\Data_Type {}

/** @deprecated */
abstract class AW_Database_Table extends AutomateWoo\Database_Table {}

/** @deprecated */
abstract class AW_Query_Custom_Table extends AutomateWoo\Query_Custom_Table {}

/** @deprecated */
abstract class AW_Field extends AutomateWoo\Fields\Field {}

/** @deprecated */
abstract class AW_Model extends AutomateWoo\Model {}

/** @deprecated */
class AW_Model_Guest extends AutomateWoo\Guest {}

/** @deprecated */
class AW_Model_Abandoned_Cart extends AutomateWoo\Cart {}

/** @deprecated */
class AW_Model_Log extends AutomateWoo\Log {}

/** @deprecated */
class AW_Model_Unsubscribe extends AutomateWoo\Unsubscribe {}

/** @deprecated */
class AW_Model_Queued_Event extends AutomateWoo\Queued_Event {}

/** @deprecated */
class AW_Model_Order_Note extends AutomateWoo\Order_Note {}

/** @deprecated */
class AW_Model_Order_Guest extends AutomateWoo\Order_Guest {}

/** @deprecated */
class AW_Query_Abandoned_Carts extends AutomateWoo\Cart_Query {}

/** @deprecated */
class AW_Query_Guests extends AutomateWoo\Guest_Query {}

/** @deprecated */
class AW_Query_Logs extends AutomateWoo\Log_Query {}

/** @deprecated */
class AW_Query_Queue extends AutomateWoo\Queue_Query {}

/** @deprecated */
class AW_Query_Workflows extends AutomateWoo\Workflow_Query {}

/** @deprecated */
class AW_Query_Unsubscribes extends AutomateWoo\Unsubscribe_Query {}

/** @deprecated */
class AW_Model_Workflow extends AutomateWoo\Workflow {}

/** @deprecated */
class AW_Remote_Request extends AutomateWoo\Remote_Request {}

/** @deprecated */
abstract class AW_Options_API extends AutomateWoo\Options_API {}

/** @deprecated */
class AW_Mailer extends AutomateWoo\Mailer {}

/** @deprecated */
class AW_Replace_Helper extends AutomateWoo\Replace_Helper {}


/**
 * @deprecated
 */
class AW_Cache_Helper {

	/**
	 * @param $key
	 * @param $value
	 * @param bool|int $expiration - In hours. Optional.
	 */
	function set( $key, $value, $expiration = false ) {
		AutomateWoo\Cache::set_transient( $key, $value, $expiration );
	}

	/**
	 * @param $key
	 * @return bool|mixed
	 */
	function get( $key ) {
		AutomateWoo\Cache::get_transient( $key );
	}

	/**
	 * @param $key
	 */
	function delete( $key ) {
		AutomateWoo\Cache::delete_transient( $key );
	}

}
