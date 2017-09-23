<?php

class ITSEC_Magic_Links {

	const T_LOGIN_PAGE = 'login-page';

	const LENGTH = 64;
	const EXPIRES = 900; // 15 Minutes

	const META = '_itsec_magic_link_tokens';

	const TOKEN_VAR = 'itsec-ml-token';
	const TYPE_VAR = 'itsec-ml-type';

	const E_MISSING = 'itsec-magic-links-missing-token';
	const E_EXPIRED = 'itsec-magic-links-expired-token';
	const E_INVALID = 'itsec-magic-links-invalid-token';
	const E_HASH_FAILED = 'itsec-magic-links-failed-hash-token';
	const E_MAIL_FAILED = 'itsec-magic-links-mail-failed';

	/** @var WP_Error|null */
	private $login_page_error;

	/** @var bool|WP_Error */
	private $login_page_link_sent = false;

	/**
	 * Setup the magic links module.
	 */
	public function run() {
		add_filter( 'itsec_brute_force_lockout_message', array( $this, 'add_login_page_instructions_to_lockout_message' ), 10, 2 );
		add_action( "login_form_{$this->login_action( self::T_LOGIN_PAGE ) }", array( $this, 'trigger_send_login_page_link' ) );
		add_filter( 'wp_login_errors', array( $this, 'report_login_page_link_email_status' ) );
		add_action( 'login_form', array( $this, 'ferry_login_page_link_tokens' ) );
		add_filter( 'authenticate', array( $this, 'maybe_remove_lockout_check_for_login_page' ), 29 );
	}

	/**
	 * Add additional instructions.
	 *
	 * @param string $message
	 * @param array  $context
	 *
	 * @return string
	 */
	public function add_login_page_instructions_to_lockout_message( $message, $context ) {

		if ( isset( $context['user'] ) ) {
			$username = $context['user']->user_login;
		} elseif ( isset( $context['username'] ) ) {
			$username = $context['username'];
		} else {
			return $message;
		}

		add_filter( 'itsec_brute_force_lockout_format_message', '__return_true' );

		if ( $this->login_page_error ) {
			$message = $this->inline_notice( $this->login_page_error, 'warning' ) . $message;
		}

		$send_link_trigger = add_query_arg( array( 'action' => $this->login_action( self::T_LOGIN_PAGE ), 'username' => $username ), wp_login_url() );
		$a_tag             = '<a href="' . esc_url( $send_link_trigger ) . '">';

		$message .= ' ' . sprintf( esc_html__( '%1$sSend authorized login link%2$s to your account\'s email address.', 'it-l10n-ithemes-security-pro' ), $a_tag, '</a>' );

		return $message;
	}

	/**
	 * When the login page is loaded with the send login page token action, attempt to send the email.
	 *
	 * Pretends the email was successfully even if the username does not exist to prevent trivial username disclosure.
	 */
	public function trigger_send_login_page_link() {

		/** @var ITSEC_Lockout $itsec_lockout */
		global $itsec_lockout;

		if ( empty( $_REQUEST['username'] ) ) {
			return;
		}

		$username = $_REQUEST['username'];

		$user = get_user_by( 'login', $username );

		if ( $user && ! $itsec_lockout->is_user_locked_out( $user->ID ) ) {
			return;
		}

		if ( ! $user && ! $itsec_lockout->is_username_locked_out( $username ) ) {
			return;
		}

		if ( ! $user ) {
			$this->login_page_link_sent = true;

			return;
		}


		if ( $this->send_login_page_link( $username ) ) {
			$this->login_page_link_sent = true;
		} else {
			$this->login_page_link_sent = new WP_Error( self::E_MAIL_FAILED, esc_html__( 'The email could not be sent.', 'it-l10n-ithemes-security-pro' ) );
		}
	}

	/**
	 * Display an error if the email to send the login page link failed.
	 *
	 * @param WP_Error $errors
	 *
	 * @return WP_Error
	 */
	public function report_login_page_link_email_status( $errors ) {

		if ( ! is_wp_error( $errors ) ) {
			$errors = new WP_Error();
		}

		if ( is_wp_error( $this->login_page_link_sent ) ) {
			$errors->add( $this->login_page_link_sent->get_error_code(), $this->login_page_link_sent->get_error_message() );
		} elseif ( true === $this->login_page_link_sent ) {
			$errors->add( 'sent',  esc_html__( 'Please check your email for an authorized login link.', 'it-l10n-ithemes-security-pro' ), 'message' );
		}

		return $errors;
	}

	/**
	 * Include login page tokens on the login form to ensure they are present when the form is submitted.
	 */
	public function ferry_login_page_link_tokens() {

		$vars = $this->extract_token_from_state();

		if ( ! $vars ) {
			return;
		}

		$type_var = self::TYPE_VAR;
		$type_val = esc_attr( $vars['type'] );

		$token_var = self::TOKEN_VAR;
		$token_val = esc_attr( $vars['token'] );

		echo "<input type='hidden' name='{$type_var}' value='{$type_val}'>";
		echo "<input type='hidden' name='{$token_var}' value='{$token_val}'>";
	}

	/**
	 * Prevent the user lockout from firing if the user has valid tokens.
	 *
	 * @param WP_User|WP_Error|null $maybe_user
	 *
	 * @return WP_User|WP_Error|null
	 */
	public function maybe_remove_lockout_check_for_login_page( $maybe_user ) {

		/** @var ITSEC_Lockout $itsec_lockout */
		global $itsec_lockout;

		if ( ! $maybe_user instanceof WP_User ) {
			return $maybe_user;
		}

		$has_valid = $this->has_valid_login_page_tokens_for_user( $maybe_user );

		if ( $has_valid === true ) {
			// This feels very fragile.
			remove_filter( 'authenticate', array( $itsec_lockout, 'check_authenticate_lockout' ), 30 );
			$this->delete_token( $maybe_user, self::T_LOGIN_PAGE );
		}

		if ( is_wp_error( $has_valid ) && $has_valid->get_error_code() !== self::E_MISSING ) {
			$this->login_page_error = $has_valid;
		}

		return $maybe_user;
	}

	/**
	 * Generate a link to the login page that will allow a user to login even if a brute force lockout exists.
	 *
	 * @param WP_User|int|string $user
	 *
	 * @return string|false
	 */
	public function generate_login_page_link( $user ) {

		$user = ITSEC_Lib::get_user( $user );

		if ( ! $user ) {
			return false;
		}

		$token = $this->create_and_save_token( $user, self::T_LOGIN_PAGE );

		if ( ! $token ) {
			return false;
		}

		return add_query_arg( array( self::TOKEN_VAR => $token, self::TYPE_VAR => self::T_LOGIN_PAGE ), wp_login_url() );
	}

	/**
	 * Send the link to an unlocked login page to a given user.
	 *
	 * @param WP_User|int|string $user
	 * @param string|false       $link The login link to send or empty to automatically generate one.
	 *
	 * @return bool
	 */
	public function send_login_page_link( $user, $link = '' ) {

		$user = ITSEC_Lib::get_user( $user );
		$link = $link ? $link : $this->generate_login_page_link( $user );

		if ( ! $link ) {
			return false;
		}

		/* translators: Do not translate the curly brackets or their contents, those are placeholders. */
		$message = esc_html__( 'Hi {{ $username }},

For security purposes please use the link below to login.

{{ $login_url }}

Regards,
All at {{ $site_name }}
{{ $site_url }}', 'it-l10n-ithemes-security-pro' );

		$replaced = ITSEC_Lib::replace_tags( $message, array(
			'username'  => $user->user_login,
			'login_url' => $link,
			'site_name' => wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ),
			'site_url'  => site_url(),
		) );

		$subject = sprintf( __( 'Login link for %s', 'it-l10n-ithemes-security-pro' ), esc_url( preg_replace( '|^https?://|i', '', get_site_url() ) ) );

		return wp_mail( $user->user_email, $subject, $replaced );
	}

	/**
	 * Display an inline notice.
	 *
	 * @param WP_Error|string $message
	 * @param string          $type
	 *
	 * @return string
	 */
	private function inline_notice( $message, $type = 'error' ) {

		switch ( $type ) {
			case 'error':
				$bkg = '#dc3232';
				$bdr = '#fbeaea';
				break;
			case 'warning':
				$bkg = '#fff8e5';
				$bdr = '#ffb900';
				break;
			case 'info':
				$bkg = '#e5f5fa';
				$bdr = '#00a0d2';
				break;
			case 'success':
			default:
				$bkg = '#ecf7ed';
				$bdr = '#46b450';
				break;

		}

		ob_start();
		?>
		<div style="background: <?php echo $bkg; ?>;border-left: 4px solid <?php echo $bdr; ?>;padding: 1px 12px; margin: 5px 0 15px;">
			<p style="margin: 0.5em 6px 0.5em 0;padding: 2px;vertical-align: bottom;">
				<?php echo is_wp_error( $message ) ? $message->get_error_message() : $message; ?>
			</p>
		</div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Check for valid login page magic link tokens.
	 *
	 * @param WP_User $user
	 *
	 * @return bool|WP_Error
	 */
	private function has_valid_login_page_tokens_for_user( $user ) {

		if ( ! $user ) {
			return false;
		}

		$vars = $this->extract_token_from_state();

		if ( ! $vars ) {
			return new WP_Error( self::E_MISSING, esc_html__( 'No magic link tokens found.', 'it-l10n-ithemes-security-pro' ) );
		}

		$valid = $this->verify_token( $user, $vars['type'], $vars['token'] );

		if ( is_wp_error( $valid ) ) {
			return $valid;
		}

		return true;
	}

	/**
	 * Extract the token pair from the request state.
	 *
	 * @return array|false
	 */
	private function extract_token_from_state() {

		if ( empty( $_REQUEST[ self::TYPE_VAR ] ) || empty( $_REQUEST[ self::TOKEN_VAR ] ) ) {
			return false;
		}

		return array( 'type' => $_REQUEST[ self::TYPE_VAR ], 'token' => $_REQUEST[ self::TOKEN_VAR ] );
	}

	/**
	 * Verify that a token is valid and has not yet expired.
	 *
	 * @param WP_User $user
	 * @param string  $type  The magic link type.
	 * @param string  $token The unhashed magic link token.
	 *
	 * @return true|WP_Error
	 */
	private function verify_token( $user, $type, $token ) {

		$links = get_user_meta( $user->ID, self::META, true );

		if ( ! is_array( $links ) || ! isset( $links[ $type ] ) ) {
			return new WP_Error( self::E_INVALID, esc_html__( 'This magic link is invalid.', 'it-l10n-ithemes-security-pro' ) );
		}

		if ( $links[ $type ]['expires'] < ITSEC_Core::get_current_time_gmt() ) {
			return new WP_Error( self::E_EXPIRED, esc_html__( 'This magic link has expired.', 'it-l10n-ithemes-security-pro' ) );
		}

		$known_hash = $links[ $type ]['hash'];
		$user_hash  = $this->hash_token( $token );

		if ( ! $user_hash ) {
			return new WP_Error( self::E_HASH_FAILED, esc_html__( 'Internal Server Error', 'it-l10n-ithemes-security-pro' ) );
		}

		if ( ! hash_equals( $known_hash, $user_hash ) ) {
			return new WP_Error( self::E_INVALID, esc_html__( 'This magic link is invalid.', 'it-l10n-ithemes-security-pro' ) );
		}

		return true;
	}

	/**
	 * Create and save a magic link token for a given type.
	 *
	 * @param WP_User $user
	 * @param string  $type
	 *
	 * @return string|false
	 */
	private function create_and_save_token( $user, $type ) {

		$token = $this->generate_token();
		$hash  = $this->hash_token( $token );

		if ( ! $hash ) {
			return false;
		}

		$tokens = get_user_meta( $user->ID, self::META, true );
		$tokens = is_array( $tokens ) ? $tokens : array();

		$tokens[ $type ] = array(
			'expires' => ITSEC_Core::get_current_time_gmt() + self::EXPIRES,
			'type'    => $type,
			'hash'    => $hash,
		);

		update_user_meta( $user->ID, self::META, $tokens );

		return $token;
	}

	/**
	 * Delete a magic link token for a user.
	 *
	 * @param WP_User $user
	 * @param string  $type
	 */
	private function delete_token( $user, $type ) {

		$tokens = get_user_meta( $user->ID, self::META, true );

		if ( ! is_array( $tokens ) ) {
			return;
		}

		unset( $tokens[ $type ] );

		if ( $tokens ) {
			update_user_meta( $user->ID, self::META, $tokens );
		} else {
			delete_user_meta( $user->ID, self::META );
		}
	}

	/**
	 * Generate a random token.
	 *
	 * @return string Hex token.
	 */
	private function generate_token() {

		try {
			$random = bin2hex( random_bytes( self::LENGTH / 2 ) );
		} catch ( Exception $e ) {
			$unpacked = unpack( 'H*', wp_generate_password( self::LENGTH / 2, true, true ) );
			$random   = reset( $unpacked );
		}

		return $random;
	}

	/**
	 * Generate a hash of the token for storage.
	 *
	 * @param string $token
	 *
	 * @return false|string
	 */
	private function hash_token( $token ) {
		return hash_hmac( $this->get_hash_algo(), $token, wp_salt() );
	}

	/**
	 * Get the hash algorithm to use.
	 *
	 * PHP can be compiled without the hash extension and the supported hash algos can be variable. WordPress shims
	 * support for md5 and sha1 hashes with hash_hmac.
	 *
	 * @return string
	 */
	private function get_hash_algo() {

		if ( ! function_exists( 'hash_algos' ) ) {
			return 'sha1';
		}

		$algos = hash_algos();

		if ( in_array( 'sha256', $algos, true ) ) {
			return 'sha256';
		}

		return 'sha1';
	}

	/**
	 * Get the action for the login page for a given magic link type.
	 *
	 * @param string $type
	 *
	 * @return string
	 */
	private function login_action( $type ) {
		return "itsec-magic-links-action-{$type}";
	}
}