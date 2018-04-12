<?php
/**
 * Convert Pro Services Abstract Class
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * Base class for third party services.
 *
 * @since 1.0.0
 */
abstract class CPRO_Service {

	/**
	 * The ID for this service such as aweber or mailchimp.
	 *
	 * @since 1.0.0
	 * @var string $id
	 */
	public $id = '';

	/**
	 * Test the API connection.
	 *
	 * @since 1.0.0
	 * @param array $fields Connection fields.
	 * @return array{
	 *      @type bool|string $error The error message or false if no error.
	 *      @type array $data An array of data used to make the connection.
	 * }
	 */
	abstract public function connect( $fields = array() );

	/**
	 * Renders the markup for the connection settings.
	 *
	 * @since 1.0.0
	 * @return string The connection settings markup.
	 */
	abstract public function render_connect_settings();

	/**
	 * Render the markup for service specific fields.
	 *
	 * @since 1.0.0
	 * @param string $account The name of the saved account.
	 * @param object $postdata Posted data.
	 * @return array {
	 *      @type bool|string $error The error message or false if no error.
	 *      @type string $html The field markup.
	 * }
	 */
	abstract public function render_fields( $account, $postdata );

	/**
	 * Get the saved data for a specific account.
	 *
	 * @since 1.0.0
	 * @param string $account The account name.
	 * @return array|bool The account data or false if it doesn't exist.
	 */
	public function get_account_data( $account ) {
		$saved_services = ConvertPlugHelper::get_services();

		if ( isset( $saved_services[ $this->id ] ) && isset( $saved_services[ $this->id ][ $account ] ) ) {
			return $saved_services[ $this->id ][ $account ];
		}

		return false;
	}
}
