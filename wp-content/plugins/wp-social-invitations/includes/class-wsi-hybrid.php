<?php

/**
 * Wsi Hybrid class
 *
 *
 * @link       http://wp.timersys.com/wordpress-social-invitations/
 * @since      2.5
 *
 * @package    Wsi
 * @subpackage Wsi/includes/
 */
class Wsi_Hybrid {

	public $adapter;
	protected $opts;
	protected $config;
	public $hybridauth;

	/**
	 * Class constructor
	 */
	function __construct( ) {
		global $wsi_plugin;

		$this->opts     = $wsi_plugin->get_opts();

		$this->config   = array(
			// "base_url" the url that point to HybridAuth Endpoint (where index.php and config.php are found)
			"base_url" => WSI_PLUGIN_URL .'hybridauth/hybridauth/',

			"providers" => array (

				"Google" => array (
					"enabled"   => !empty( $this->opts['enable_google'] ),
                    "keys"      => array ( "id" => $this->opts['google_key'], "secret" => $this->opts['google_secret'] ),
                ),
	            "Facebook" => array (
					"enabled"   => !empty( $this->opts['enable_facebook'] ),
					"keys"      => array ( "id" => '312931535517769', "secret" => '89f196d5d2cf14bd7839bdb300ec5a58' ),
					"scope"     => "email, user_about_me, user_birthday, user_hometown" // optional
				),
	            "Twitter" => array (
					"enabled"   => !empty( $this->opts['enable_twitter'] ),
					"keys"      => array ( "key" => $this->opts['twitter_key'], "secret" => $this->opts['twitter_secret'] ),
				),
				"LinkedIn" => array (
					"enabled"   => !empty( $this->opts['enable_linkedin'] ),
					"keys"      => array ( "key" => $this->opts['linkedin_key'], "secret" => $this->opts['linkedin_secret'] )
				),
				"Yahoo" => array (
					"enabled"   => !empty( $this->opts['enable_yahoo'] ),
					"keys"      => array ( "key" => $this->opts['yahoo_key'], "secret" => $this->opts['yahoo_secret'] )
				),
				"Foursquare" => array (
					"enabled"   => !empty( $this->opts['enable_foursquare'] ),
					"keys"      => array ( "id" => $this->opts['foursquare_key'], "secret" => $this->opts['foursquare_secret'] )
				),
				"Live" => array (
					"enabled"   => !empty( $this->opts['enable_live'] ),
				),
	        ),

	        "debug_mode" => ( !empty( $this->opts['enable_dev'] ) && is_writeable( WSI_PLUGIN_DIR . 'logs/hybrid.txt') ),

	        // to enable logging, set 'debug_mode' to true, then provide here a path of a writable file
	        "debug_file" => WSI_PLUGIN_DIR . 'logs/hybrid.txt',
		);

		try {
			return $this->hybridauth   = new Hybrid_Auth($this->config);
		}
		catch( Exception $e ){
			Wsi_Logger::log('new Hybrid_Auth error: ' .$e->getMessage());
			$this->checkException($e);
		}
	}

	/**
	 * Get the error exception and display template with message
	 * @param $e
	 */
	private function checkException( $e ) {
		wsi_get_template('popup/error.php',
			array(
				'e'         => $e,
				'provider'  => wsi_get_data('provider'),
				'adapter'   => $this->adapter
			)
		);
		Hybrid_Auth::logoutAllProviders();
		die();
	}


	/**
	 * Connect to provider and authentificate user and grab session and user info
	 * @param $provider
	 *
	 * @return Hybrid_Provider_Adapter
	 */
	public function connect( $provider ) {
		try {

			$this->adapter = $this->hybridauth->authenticate( ucfirst( $provider ) );
			$_SESSION['wsi_data']['sdata'] = $this->hybridauth->getSessionData();
			$_SESSION['wsi_data']['user_info'] = $this->adapter->getUserProfile();
			return $this->adapter;
		}
		catch( Exception $e ) {
			Wsi_Logger::log('Connect error: ' .$e->getMessage());
			$this->checkException($e);
		}
	}


	/**
	 * Return array of providers friends
	 * @return array of Hybrid_User_Contact
	 */
	public function getUserContacts() {
		try {
			$friends = $this->adapter->getUserContacts();
			if( empty($friends) )
				throw new Exception(__('Your contacts list on this provider is empty. Add some contacts first! - Providers like Yahoo or Mail only return contacts created with them, and not contacts imported from other networks.','wsi'),10);
			return $friends;
		}
		catch( Exception $e ) {
			Wsi_Logger::log('getUserContacts error: ' .$e->getMessage());
			$this->checkException($e);
		}
	}


	/**
	 * Return the correct provider identifier
	 * @param $friend
	 * @return string
	 */
	public static function getFriendId($friend){
		$provider = wsi_get_data('provider');
		return $provider == 'linkedin'  || $provider == 'facebook' || $provider == 'twitter' ? $friend->identifier : $friend->email;
	}

	/**
	 * Print user name using encoding functions
	 * @param $displayName
	 * @return string
	 */
	public static function printName( $displayName ) {

		if( function_exists('mb_convert_encoding')) {
			echo mb_convert_encoding($displayName, "HTML-ENTITIES", "UTF-8");
		} else {
			echo utf8_decode($displayName);
		}

	}

	/**
	 * Restore session data of provider
	 * @param $sdata
	 */
	public function restoreSessionData( $sdata ) {
		try{
			$this->hybridauth->restoreSessionData($sdata);
		}
		catch( Exception $e ) {
			Wsi_Logger::log('restoreSessionData error: ' .$e->getMessage());
			$this->checkException($e);
		}
	}

	/**
	 * Return adapter of connected provider
	 *
	 * @param $provider
	 *
	 * @return Hybrid_Provider_Adapter
	 */
	public function getAdapter($provider) {
		try{
			return	$this->hybridauth->getAdapter($provider);
		}
		catch( Exception $e ) {
			Wsi_Logger::log('getAdapter error: ' .$e->getMessage());
			$this->checkException($e);
		}
	}
}
