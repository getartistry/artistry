<?php

namespace AutomateWoo;

/**
 * @class Workflow_Email
 * @since 2.8.6
 */
class Workflow_Email {

	/** @var Workflow  */
	public $workflow;

	/** @var string */
	public $recipient;

	/** @var string */
	public $subject;

	/** @var string */
	public $content;

	/** @var string */
	public $heading;

	/** @var string */
	private $preheader;

	/** @var string */
	public $template;

	/** @var bool */
	protected $tracking_enabled = false;

	/** @var string */
	public $raw_html;

	/** @var bool */
	public $has_raw_html = false;

	/** @var bool */
	public $include_automatewoo_styles = true;


	/**
	 * @param Workflow $workflow
	 */
	function __construct( $workflow ) {
		$this->workflow = $workflow;

		if ( $workflow->is_tracking_enabled() ) {
			$this->set_tracking_enabled( true );
		}
	}


	/**
	 * @param string $recipient
	 */
	function set_recipient( $recipient ) {
		$this->recipient = $recipient;
	}


	/**
	 * @param string $subject
	 */
	function set_subject( $subject ) {
		$this->subject = $subject;
	}


	/**
	 * @param string $content
	 */
	function set_content( $content ) {
		$this->content = $content;
	}


	/**
	 * @param string $heading
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
	 * @param string $template
	 */
	function set_template( $template ) {
		$this->template = $template;
	}


	/**
	 * @param bool $enabled
	 */
	function set_tracking_enabled( $enabled ) {
		$this->tracking_enabled = $enabled;
	}


	/**
	 * If raw HTML is set other props like content, heading, preheader, template will be ignored
	 * @param string $html
	 */
	function set_raw_html( $html ) {
		$this->has_raw_html = true;
		$this->raw_html = $html;
	}


	/**
	 * @param bool $include
	 */
	function set_include_automatewoo_styles( $include ) {
		$this->include_automatewoo_styles = $include;
	}


	/**
	 * @return Mailer|Mailer_Raw_HTML
	 */
	function get_mailer() {

		if ( $this->has_raw_html ) {
			$mailer = new Mailer_Raw_HTML();
			$mailer->set_raw_html( $this->raw_html );
		}
		else {
			$mailer = new Mailer();
			$mailer->set_content( $this->content );
			$mailer->set_template( $this->template );
			$mailer->set_heading( $this->heading );
			$mailer->set_preheader( $this->preheader );
			$mailer->extra_footer_text = $this->get_unsubscribe_link();
		}

		$mailer->set_include_automatewoo_styles( $this->include_automatewoo_styles );
		$mailer->set_subject( $this->subject );
		$mailer->set_email( $this->recipient );

		if ( $this->tracking_enabled ) {
			$mailer->tracking_pixel_url = Emails::generate_open_track_url( $this->workflow );
			$mailer->replace_content_urls_callback = [ $this, 'replace_content_urls_callback' ];
		}

		return apply_filters( 'automatewoo/workflow/mailer', $mailer, $this );
	}


	/**
	 * @return bool|string
	 */
	function get_unsubscribe_link() {

		$customer = Customer_Factory::get_by_email( $this->recipient );
		$url = Emails::generate_unsubscribe_url( $this->workflow->get_id(), $customer );

		if ( ! $url ) {
			return false;
		}

		$text = apply_filters( 'automatewoo_email_unsubscribe_text', __( 'Unsubscribe', 'automatewoo' ), $this, $this->workflow );

		return '<a href="' . $url . '" target="_blank">' . $text . '</a>';
	}


	/**
	 * @param string $url
	 * @return string
	 */
	function replace_content_urls_callback( $url ) {
		if ( strstr( $url, 'aw-action=unsubscribe' ) ) {
			// don't count unsubscribe clicks
		}
		else {
			$url = html_entity_decode( $url );
			$url = $this->workflow->append_ga_tracking_to_url( $url );
			$url = Emails::generate_click_track_url( $this->workflow, $url );
		}

		return 'href="' . esc_url( $url ) . '"';
	}


	/**
	 * @return bool|\WP_Error
	 */
	function send() {

		$mailer = $this->get_mailer();

		if ( ! $this->workflow ) {
			return new \WP_Error( 'workflow_blank', __( 'Workflow was not defined for email.', 'automatewoo' ) );
		}

		// validate email before checking if unsubscribed
		$validate_email = $mailer->validate_recipient_email();

		if ( is_wp_error( $validate_email ) ) {
			return $validate_email;
		}

		$customer = Customer_Factory::get_by_email( $this->recipient );

		if ( $this->workflow->is_customer_unsubscribed( $customer ) ) {
			return new \WP_Error( 'email_unsubscribed', sprintf( __( "The email '%s' is unsubscribed from this workflow.", 'automatewoo' ), $this->recipient ) );
		}

		\AW_Mailer_API::setup( $mailer, $this->workflow );

		$sent = $mailer->send();

		\AW_Mailer_API::cleanup();

		return $sent;
	}


	/**
	 * This method is only used in email previews
	 *
	 * @return string
	 */
	function get_html() {
		$mailer = $this->get_mailer();
		\AW_Mailer_API::setup( $mailer, $this->workflow );
		$html = $mailer->get_html();
		\AW_Mailer_API::cleanup();
		return $html;
	}

}
