<?php

/**
 * Class for Wsi providers
 *
 * @link       http://wp.timersys.com/wordpress-social-invitations/
 * @since      2.5
 *
 * @package    Wsi
 * @subpackage Wsi/includes/providers
 */

abstract class Wsi_Providers {

	/*
	 * Provider name
	 */
	public $name;
	protected $hybridauth;
	protected $provider;

	/**
	 * Create the connection with given provider
	 */
	public function connect(){
		$this->hybridauth = new Wsi_Hybrid();
		$this->provider = $this->hybridauth->connect( $this->name );
	}
	/**
	 * Display collector for provider
	 */
	public function collector() {
		global $wsi_plugin;
		$opts = $wsi_plugin->get_opts();
		$invited_friends = Wsi_Queue::getInvitedFriends();
		$friends         = $this->getFriends();

		$template = wsi_locate_template('popup/collector/hybridauth.php');
		wsi_get_template('popup/collector.php', array(
			'template' => $template,
			'provider' => $this->name,
			'friends'  => $friends,
			'already_invited'  => $invited_friends,
			'force_invites'    => @$opts['force_invites'],
			)
		);
		$this->provider->logout();
	}

	/**
	 * Return provider name
	 * @return mixed
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Get provider user info
	 * @return array
	 */
	public function getUserInfo() {
		// Defaults
		$userInfo = array(
			'displayName'   => ''
		);

		return $userInfo;
	}
	/**
	 * Get provider connection session data
	 * @return string
	 */
	public function getSessionData() {

		return $_SESSION['wsi_data']['sdata'];
	}

	/**
	 * Get users contacts and return an ordered array with already invited at the bottom
	 * @return array
	 */
	private function getFriends() {
		$contacts = $this->hybridauth->getUserContacts();
		$already_invited = Wsi_Queue::getInvitedFriends();
		$invited_contacts = array();

		if( !is_array($already_invited) || empty( $already_invited ) )
			return $contacts;
		for($i=0, $n=count($contacts); $i<$n; ++$i) {
			$identifier =  Wsi_Hybrid::getFriendId($contacts[$i]);
			if ( in_array( $identifier, $already_invited ) ){
				$contacts[$i]->already_invited = true;
				$invited_contacts[] = $contacts[$i];
				unset($contacts[$i]);
			}
		}
		return array_merge($contacts, $invited_contacts);
	}

}