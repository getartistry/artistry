<?php

namespace AutomateWoo;

/**
 * Track guests across sessions via cookies. Store their info in the database.
 *
 * @class Session_Tracker
 */
class Session_Tracker {

	/** @var int (days) */
	private $tracking_cookie_expiry;

	/** cookie name */
	private $tracking_key_cookie_name;

	/** @var int (cache for the detected user id) */
	private $detected_user_id;

	/** @var string */
	private $tracking_key;

	/** @var bool */
	private $tracking_key_to_set = false;

	/** @var bool */
	private $has_done_init_session = false;


	/**
	 * Construct
	 */
	function __construct() {

		if ( ! AW()->options()->session_tracking_enabled ) {
			return;
		}

		$this->tracking_key_cookie_name = apply_filters( 'automatewoo/session_tracker/cookie_name', 'wp_automatewoo_visitor_' . COOKIEHASH );
		$this->tracking_cookie_expiry = apply_filters('automatewoo_visitor_tracking_cookie_expiry', 730 ); // 2 years

		add_action( 'wp', [ $this, 'maybe_set_session_cookies' ], 99 );
		add_action( 'shutdown', [ $this, 'maybe_set_session_cookies' ], 0 );
		add_action( 'automatewoo/ajax/before_send_json', [ $this, 'maybe_set_session_cookies' ] );

		add_action( 'user_register', [ $this, 'maybe_convert_guest_after_registration' ], 5 );
		add_action( 'set_logged_in_cookie', [ $this, 'user_login' ], 10, 4 );

		add_action( 'comment_post', [ $this, 'capture_from_comment' ], 10, 2 );
		add_action( 'automatewoo_capture_guest_email', [ $this, 'maybe_store_guest' ] ); // for third-party
	}


	/**
	 * @param bool|int $user_id
	 * @param bool $force_refresh
	 */
	function init_session( $user_id = false, $force_refresh = false ) {

		if ( ! AW()->options()->session_tracking_enabled ) {
			return;
		}

		if ( $this->has_done_init_session && ! $force_refresh ) {
			return;
		}

		$this->has_done_init_session = true;

		$cookie_key = isset( $_COOKIE[$this->tracking_key_cookie_name] ) ? Clean::string( $_COOKIE[$this->tracking_key_cookie_name] ) : false;

		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}

		if ( $user_id ) {

			$database_key = get_user_meta( $user_id, 'automatewoo_visitor_key', true );

			// is tracking cookie set?
			if ( $cookie_key ) {

				if ( $database_key ) {

					if ( $database_key != $cookie_key ) {
						// if a database key exists but is different from the cookie key, update the cookie key
						$this->tracking_key_to_set = $database_key;
					}
					else {
						// cookie and db match
						$this->tracking_key = $database_key;
					}
				}
				else {
					// cookie key exists but is not in db yet

					// before storing remove this key from any other users
					// ensures anyone with multiple user accounts always has the session key attached to their most recent login
					delete_metadata( 'user', $user_id, 'automatewoo_visitor_key', $cookie_key, true );

					$this->store_user_tracking_key( $user_id, $cookie_key );
					$this->tracking_key = $cookie_key;
				}
			}
			elseif ( $database_key ) {
				// no cookie key set but there is a database key so lets use that
				$this->tracking_key_to_set = $database_key;
			}
			else {
				// no cookie key or stored key, lets generate a new one
				$this->tracking_key_to_set = $this->generate_key();
				$this->store_user_tracking_key( $user_id, $this->tracking_key_to_set );
			}
		}
		else {
			if ( $cookie_key ) {
				// guest has cookie key so use it
				$this->tracking_key = $cookie_key;
			}
			else {
				// guest has no cookie so generate one
				$this->tracking_key_to_set = $this->generate_key();
			}
		}

		if ( empty( $_COOKIE[ 'wp_automatewoo_session_started' ] ) ) {
			$this->new_session_initiated();
		}
	}


	/**
	 * New browser session initiated
	 */
	function new_session_initiated() {
		if ( $guest = $this->get_current_guest() ) {
			$guest->do_check_in();
		}
		do_action( 'automatewoo_new_session_initiated' );
	}


	/**
	 * Clear the stored guest before their cookie key is updated.
	 * Store the cart upon login
	 *
	 * @param $logged_in_cookie
	 * @param $expire
	 * @param $expiration
	 * @param $user_id
	 */
	function user_login( $logged_in_cookie, $expire, $expiration, $user_id ) {

		$converted_guest = $this->maybe_convert_guest_after_registration( $user_id );

		if ( ! $converted_guest ) {

			// if no guest was converted clear the cart attached to the current cookie because a new cart will be created for the user
			if ( $tracking_key = AW()->session_tracker->get_tracking_key() ) {
				if ( $guest = Guest_Factory::get_by_key( $tracking_key ) ) {
					$guest->delete_cart();
				}
			}
		}

		// init the session after login so new tracking key gets set
		$this->init_session( $user_id, true );
	}



	/**
	 * @param $user_id
	 * @return bool - returns true if a guest was converted
	 */
	function maybe_convert_guest_after_registration( $user_id ) {

		$user = get_userdata( $user_id );

		if ( ! $user || ! $user->user_email ) {
			return false;
		}

		// if the guest and user have the same email address convert and delete the guest
		// we won't delete the guest record if the emails don't match, eg with a cookie matched guest
		// but we do delete the guest cart and a new cart will be created for the now logged in user
		$guest = Guest_Factory::get_by_email( Clean::email( $user->user_email ) );

		if ( ! $guest ) {
			return false;
		}

		do_action( 'automatewoo/session_tracker/convert_guest', $guest, $user );
		$guest->delete(); // clear all guest data (including cart)
		return true;
	}



	/**
	 * Attempt to set session tracking cookies
	 */
	function maybe_set_session_cookies() {

		if ( ! $this->can_set_cookies() ) {
			return;
		}

		if ( $this->tracking_key_to_set ) {

			$this->tracking_key = $this->tracking_key_to_set;
			$this->tracking_key_to_set = false;

			wc_setcookie( $this->tracking_key_cookie_name, $this->tracking_key, time() + DAY_IN_SECONDS * $this->tracking_cookie_expiry );
		}

		if ( $this->tracking_key ) {
			wc_setcookie( 'wp_automatewoo_session_started', 1 );
		}
	}


	/**
	 * @return bool
	 */
	function can_set_cookies() {
		return ! headers_sent();
	}


	/**
	 * Returns false if not id is set
	 *
	 * @return string|false
	 */
	function get_tracking_key() {

		if ( ! AW()->options()->session_tracking_enabled ) {
			return false;
		}

		$this->init_session();

		// prioritise newly set key over stored key
		if ( $this->tracking_key_to_set && $this->can_set_cookies() ) {
			return $this->tracking_key_to_set;
		}

		if ( $this->tracking_key ) {
			return $this->tracking_key;
		}

		return false;
	}



	/**
	 * @return string
	 */
	function generate_key() {
		return aw_generate_key( 32 );
	}


	/**
	 * Detect the user id based from their visitor id
	 *
	 * Returns false if no use is detected.
	 *
	 * @return int
	 */
	function get_detected_user_id() {

		if ( is_user_logged_in() ) {
			return get_current_user_id();
		}

		if ( ! AW()->options()->session_tracking_enabled ) {
			return 0; // only return the real user id
		}

		if ( isset( $this->detected_user_id ) ) {
			return $this->detected_user_id; // check cached
		}

		$this->detected_user_id = 0;

		$this->init_session();

		// only search for existing tracking keys not newly set ones
		if ( $this->tracking_key ) {
			$this->detected_user_id = $this->get_user_id_from_tracking_key( $this->tracking_key );
		}

		return $this->detected_user_id;
	}


	/**
	 * @param string $key
	 * @return int
	 */
	function get_user_id_from_tracking_key( $key ) {
		$user_id = 0;

		$user_query = new \WP_User_Query([
			'meta_query' => [
				[
					'key' => 'automatewoo_visitor_key',
					'value' => $key
				]
			],
			'fields' => 'ids',
			'count_total' => false
		]);

		$results = $user_query->get_results();

		if ( ! empty( $results ) ) {
			$user_id = absint( $results[0] );
		}

		return $user_id;
	}


	/**
	 * Returns the current guest as tracked by cookies
	 * @return Guest|bool
	 */
	function get_current_guest() {

		if ( ! AW()->options()->session_tracking_enabled ) {
			return false;
		}

		if ( is_user_logged_in() ) {
			return false;
		}

		if ( $tracking_key = $this->get_tracking_key() ) {
			return Guest_Factory::get_by_key( $tracking_key );
		}

		return false;
	}


	/**
	 * Store the guest against the current session key
	 * Also can be used to update the current guest's email
	 *
	 * @param $email
	 * @param string|bool $language
	 * @param string|bool $capture_page - location the guest was captured
	 * @return Guest|false
	 */
	function maybe_store_guest( $email, $language = false, $capture_page = false ) {

		if ( ! is_email( $email ) || is_user_logged_in() ) {
			return false;
		}

		// if there is no tracking key or no ability to set one there is no point storing the guest
		if ( ! $tracking_key = $this->get_tracking_key() ) {
			return false;
		}

		$email = Clean::email( $email );

		// Check if there is already a guest based on cookies
		$guest = $this->get_current_guest();

		if ( $guest ) {

			// check if guest email addresses still match
			// if the email hasn't changed then do nothing
			if ( $guest->get_email() !== $email ) {

				if ( $guest->is_locked() ) {
					// email has changed and guest has been linked with a customer so we must create a new guest
					// first clear the old guests cart, to avoid duplicate abandoned cart emails
					$guest->delete_cart();
					$guest = $this->create_new_guest( $email, $language, $capture_page );
				}
				else {
					// guest email has changed

					if ( Guest_Factory::get_by_email( $email ) ) {
						// the email is already a different guest
						$guest->delete_cart();
						$this->create_new_guest( $email, $language, $capture_page );
					}
					else {
						// update the current guests email
						$guest->set_email( $email );
						$guest->save();
					}

				}
			}
		}
		else {
			$guest = $this->create_new_guest( $email, $language, $capture_page );
		}

		return $guest;
	}


	/**
	 * @param $email
	 * @param $language
	 * @param $capture_page
	 * @return Guest|bool
	 */
	function create_new_guest( $email, $language, $capture_page ) {

		$stored_new_guest = false;

		// first check for matching guest email
		$guest = Guest_Factory::get_by_email( $email );

		// create new guest if none exists
		if ( ! $guest ) {
			$guest = Guest_Factory::create( $email, $this->get_tracking_key() );
			$stored_new_guest = true;
		}

		// always update the tracking key, it may have changed
		$this->tracking_key_to_set = $guest->get_key();

		$guest->do_check_in();

		// maybe set language
		if ( $language ) {
			$guest->set_language( $language );
			$guest->save();
		}

		if ( $capture_page && ! $guest->get_meta( 'location_captured' ) ) {
			$guest->add_meta( 'location_captured', $capture_page );
		}

		// save the cart
		if ( AW()->options()->abandoned_cart_enabled ) {
			Carts::store_guest_cart( $guest );
		}

		if ( $stored_new_guest ) {
			// fire hook after new guest is saved
			do_action( 'automatewoo/session_tracker/new_stored_guest', $guest );
		}

		return $guest;
	}


	/**
	 * Store guest info if they place a comment
	 * @param $comment_ID
	 */
	function capture_from_comment( $comment_ID ) {

		$comment = get_comment( $comment_ID );

		if ( $comment && ! $comment->user_id ) {
			$this->maybe_store_guest( $comment->comment_author_email );
		}
	}


	/**
	 * @param int $user_id
	 * @param string $key
	 */
	function store_user_tracking_key( $user_id, $key ) {
		update_user_meta( $user_id, 'automatewoo_visitor_key', $key );
	}

}
