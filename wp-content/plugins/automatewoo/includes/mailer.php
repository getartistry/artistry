<?php

namespace AutomateWoo;

/**
 * @class Mailer
 */
class Mailer {

	/** @var string */
	public $email;

	/** @var string */
	public $template = 'default';

	/** @var string */
	public $heading;

	/** @var string */
	public $preheader;

	/** @var string */
	public $content;

	/** @var string */
	public $subject;

	/** @var string */
	public $from_name;

	/** @var string */
	public $from_email;

	/** @var array */
	public $attachments = [];

	/** @var string e.g. 'John Smith <email@example.org>' */
	public $reply_to;

	/** @var string */
	public $email_type = 'html';

	/** @var string */
	public $extra_footer_text;

	/** @var string */
	public $tracking_pixel_url;

	/** @var callable - use to replace URLs in content e.g. for click tracking */
	public $replace_content_urls_callback;

	/** @var bool */
	public $include_automatewoo_styles = true;



	/**
	 * @param $subject
	 * @param $email
	 * @param $content
	 * @param string $template
	 */
	function __construct( $subject = false, $email = false, $content = false, $template = 'default' ) {

		// deprecated
		$this->email = $email;
		$this->subject = $subject;
		$this->content = $content;
		$this->template = $template;

		$this->update_email_from_properties();

		// include css inliner
		if ( ! class_exists( 'AW_Emogrifier' ) && class_exists( 'DOMDocument' ) ) {
			include_once AW()->lib_path( '/emogrifier/emogrifier.php' );
		}

		// also include the WC packaged emogrifier incase other plugins are looking for this e.g. YITH email customizer
		if ( ! class_exists( 'Emogrifier' ) && class_exists( 'DOMDocument' ) ) {
			include_once( WC()->plugin_path() . '/includes/libraries/class-emogrifier.php' );
		}
	}


	/**
	 * Set email recipient
	 *
	 * @param $email
	 */
	function set_email( $email ) {
		$this->email = $email;
	}


	/**
	 * @param $heading
	 */
	function set_heading( $heading ) {
		$this->heading = $heading;
	}


	/**
	 * @param string $preheader
	 */
	function set_preheader( $preheader ) {
		$this->preheader = $preheader;
	}


	/**
	 * @param string $content
	 */
	function set_content( $content ) {
		$this->content = $content;
	}


	/**
	 * @param string $subject
	 */
	function set_subject( $subject ) {
		$this->subject = $subject;
	}


	/**
	 * @param string $template
	 */
	function set_template( $template ) {
		$this->template = $template;
		$this->update_email_from_properties();
	}


	/**
	 * @return string
	 */
	function get_from_email() {
		return $this->from_email;
	}


	/**
	 * @return string
	 */
	function get_from_name() {
		return $this->from_name;
	}


	/**
	 * @param bool $include
	 */
	function set_include_automatewoo_styles( $include ) {
		$this->include_automatewoo_styles = $include;
	}


	/**
	 * Update from properties, this can be based on the template
	 */
	function update_email_from_properties() {
		$this->from_email = Emails::get_from_address( $this->template );
		$this->from_name = Emails::get_from_name( $this->template );
	}


	/**
	 * @return true|\WP_Error
	 */
	function validate_recipient_email() {
		if ( ! $this->email ) {
			return new \WP_Error( 'email_blank', __( 'The email address is blank.', 'automatewoo' ) );
		}

		if ( ! is_email( $this->email ) ) {
			return new \WP_Error( 'email_invalid', sprintf(__( "'%s' is not a valid email address.", 'automatewoo' ), $this->email ) );
		}

		/**
		 * @since 3.6.0
		 */
		$blacklist = apply_filters( 'automatewoo/mailer/blacklist', [] );

		foreach( $blacklist as $pattern ) {
			if ( strstr( $this->email, $pattern ) ) {
				return new \WP_Error( 'email_blacklisted', sprintf(__( "The email '%s' is blacklisted.", 'automatewoo' ), $this->email ) );
			}
		}

		return true;
	}


	/**
	 * @return true|\WP_Error
	 */
	function send() {

		$validate_email = $this->validate_recipient_email();

		if ( is_wp_error( $validate_email ) ) {
			return $validate_email;
		}

		do_action( 'automatewoo/email/before_send', $this );

		add_filter( 'wp_mail_from', [ $this, 'get_from_email' ] );
		add_filter( 'wp_mail_from_name', [ $this, 'get_from_name' ] );
		add_filter( 'wp_mail_content_type', [ $this, 'get_content_type' ] );
		add_action( 'wp_mail_failed', [ $this, 'log_wp_mail_errors' ] );

		$headers = [
			'Content-Type: ' . $this->get_content_type()
		];

		if ( isset( $this->reply_to ) ) {
			$headers[] = 'Reply-To: ' . $this->reply_to;
		}

		$sent = wp_mail(
			$this->email,
			$this->subject,
			$this->get_html(),
			$headers,
			$this->attachments
		);

		remove_filter( 'wp_mail_from', [ $this, 'get_from_email' ] );
		remove_filter( 'wp_mail_from_name', [ $this, 'get_from_name' ] );
		remove_filter( 'wp_mail_content_type', [ $this, 'get_content_type' ] );
		remove_action( 'wp_mail_failed', [ $this, 'log_wp_mail_errors' ] );

		if ( $sent === false ) {

			global $phpmailer;

			if ( $phpmailer && is_array( $phpmailer->ErrorInfo ) && ! empty( $phpmailer->ErrorInfo ) ) {

				$error = current( $phpmailer->ErrorInfo );
				return new \WP_Error( 4, sprintf( __( 'PHP Mailer - %s', 'automatewoo' ), is_object( $error ) ? $error->message : $error ) );
			}

			return new \WP_Error( 5, __( 'The wp_mail() function returned false.', 'automatewoo' ) );
		}

		return $sent;
	}


	/**
	 * @return string
	 */
	function get_html() {
		return apply_filters( 'woocommerce_mail_content', $this->style_inline( $this->generate_raw_html() ) );
	}


	/**
	 * Returns html without CSS inline
	 *
	 * @return string
	 */
	function generate_raw_html() {

		add_filter( 'woocommerce_email_footer_text', [ $this, 'add_extra_footer_text' ] );

		$this->content = $this->prepare_content( $this->content );

		// Buffer
		ob_start();

		$this->get_template_part( 'email-header.php', [
			'email_heading' => $this->heading
		] );

		echo $this->content;

		$this->get_template_part( 'email-footer.php' );

		$html = ob_get_clean();

		remove_filter( 'woocommerce_email_footer_text', [ $this, 'add_extra_footer_text' ] );

		if ( $this->preheader ) {
			$html = $this->inject_preheader( $html );
		}

		if ( $this->tracking_pixel_url ) {
			$html = $this->inject_tracking_pixel( $html );
		}

		return $html;
	}


	/**
	 * Prepare mailer content
	 * @param string $content
	 * @return string
	 */
	function prepare_content( $content ) {

		$content = $this->process_email_variables( $content );
		$content = $this->fix_links_with_double_http( $content );

		// replace <del> and <ins> tags for outlook
		$content = str_replace( '<del>', '<span style="text-decoration: line-through;">', $content );
		$content = str_replace( '<ins>', '<span style="text-decoration: underline;">', $content );
		$content = str_replace( [ '</del>', '</ins>' ], '</span>', $content );

		$content = $this->replace_urls_in_content( $content );

		// pass through content filters to convert emojis and do autop etc
		// IMPORTANT do this after URLs are modified so HTML entities are not encoded
		return apply_filters( 'automatewoo_email_content', $content );
	}


	/**
	 * Fix any duplicate http in links, can happen due to variables
	 *
	 * @param $content
	 * @return mixed
	 */
	function fix_links_with_double_http( $content ) {
		$content = str_replace( '"http://http://', '"http://', $content );
		$content = str_replace( '"https://https://', '"https://', $content );
		$content = str_replace( '"http://https://', '"https://', $content );
		$content = str_replace( '"https://http://', '"http://', $content );
		return $content;
	}


	/**
	 * Apply inline styles to dynamic content.
	 *
	 * @param string|null $content
	 * @return string
	 */
	function style_inline( $content ) {
		if ( ! class_exists( 'DOMDocument' ) ) return $content;

		ob_start();

		if ( $this->include_automatewoo_styles ) {
			aw_get_template( 'email/styles.php' );
		}

		$this->get_template_part( 'email-styles.php' );
		$css = apply_filters( 'woocommerce_email_styles', ob_get_clean() );
		$css = apply_filters( 'automatewoo/mailer/styles', $css, $this );

		try {
			$emogrifier = new \AW_Emogrifier( $content, $css );
			$emogrifier->disableStyleBlocksParsing();
			$emogrifier->disableInvisibleNodeRemoval();
			$content = $emogrifier->emogrify();
		}
		catch ( \Exception $e ) {
			$logger = new \WC_Logger();
			$logger->add( 'emogrifier', $e->getMessage() );
		}

		return $content;
	}


	/**
	 * @param $text
	 * @return string
	 */
	function add_extra_footer_text( $text ) {

		if ( ! $this->extra_footer_text )
			return $text;

		// add separator if there is footer text
		if ( trim( $text ) ) {
			$text .= apply_filters( 'automatewoo_email_footer_separator',  ' - ' );
		}

		$text .= $this->extra_footer_text;

		return $text;
	}


	/**
	 * @param $file
	 * @param array $args
	 */
	function get_template_part( $file, $args = [] ) {

		if ( $args && is_array( $args ) ) {
			extract( $args );
		}

		switch( $this->template ) {

			// default is the woocommerce template
			case 'default':
				$template_name = 'emails/' . $file;
				$template_path = '';
				break;

			case 'plain':
				// plain templates are not
				return aw_get_template('email/plain/' . $file, $args );
				break;

			default:
				$template_name = $file;
				$template_path = 'automatewoo/custom-email-templates/'. $this->template;
				break;
		}

		$located = wc_locate_template( $template_name, $template_path );

		// if using woo default, apply filters to support email customizer plugins
		if ( $this->template === 'default' ) {
			$located = apply_filters( 'wc_get_template', $located, $template_name, $args, $template_path, '' );

			do_action( 'woocommerce_before_template_part', $template_name, $template_path, $located, $args );

			include( $located );

			do_action( 'woocommerce_after_template_part', $template_name, $template_path, $located, $args );
		}
		else {
			include( $located );
		}
	}


	/**
	 * Maybe replace URLs with trackable URLs
	 *
	 * @param $content string
	 * @return string
	 */
	function replace_urls_in_content( $content ) {
		if ( ! $this->replace_content_urls_callback ) {
			return $content;
		}

		$replacer = new Replace_Helper( $content, $this->replace_content_urls_callback, 'href_urls' );
		return $replacer->process();
	}


	/**
	 * Process email variables. Currently only {{ unsubscribe_url }}.
	 *
	 * @param $content string
	 * @return string
	 */
	function process_email_variables( $content ) {
		$replacer = new Replace_Helper( $content, [ $this, 'callback_process_email_variables' ], 'variables' );
		return $replacer->process();
	}

	/**
	 * @param $variable
	 * @return string
	 */
	function callback_process_email_variables( $variable ) {
		$variable = trim( $variable );
		$value = '';

		switch ( $variable ) {
			case 'unsubscribe_url':
				$value = \AW_Mailer_API::unsubscribe_url();
				break;
		}

		return apply_filters( 'automatewoo/mailer/variable_value', $value, $this );
	}


	/**
	 * @return string
	 */
	function get_email_type() {
		return $this->email_type && class_exists( 'DOMDocument' ) ? $this->email_type : 'plain';
	}


	/**
	 * @return string
	 */
	function get_content_type() {
		switch ( $this->get_email_type() ) {
			case 'html' :
				return 'text/html';
			case 'multipart' :
				return 'multipart/alternative';
			default :
				return 'text/plain';
		}
	}


	/**
	 * Injects preheader HTML after opening <body> tag
	 *
	 * @param $html
	 * @return string
	 */
	function inject_preheader( $html ) {
		return preg_replace_callback( "/<body[^>]*>/", function( $matches ) {
			$preheader = '<div class="automatewoo-email-preheader" style="display: none !important; font-size: 1px;">' . $this->preheader . '</div>';
			return $matches[0] . $preheader;
		}, $html, 1 );
	}


	/**
	 * Injects tracking pixel before closing </body> tag
	 *
	 * @param $html
	 * @return string
	 */
	function inject_tracking_pixel( $html ) {
		return preg_replace_callback( "/<\/body[^>]*>/", function( $matches ) {
			$pixel = '<img src="' . esc_url( $this->tracking_pixel_url ) . '" height="1" width="1" alt="" style="display:inline">';
			return $pixel . $matches[0] ;
		}, $html, 1 );
	}


	/**
	 * @param $error \WP_Error
	 */
	function log_wp_mail_errors( $error ) {
		$log = new \WC_Logger();
		$log->add( 'automatewoo-wp-mail', $error->get_error_message() );
	}

}
